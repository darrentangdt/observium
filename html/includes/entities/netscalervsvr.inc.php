<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package        observium
 * @subpackage     functions
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

function build_netscalervsvr_query($vars)
{

    global $config, $cache;

    $sql = 'SELECT *, `netscaler_vservers`.`vsvr_id` AS `vsvr_id` FROM `netscaler_vservers`';
    $sql .= ' WHERE 1' . generate_query_permitted(array('device'));

    // Build query
    foreach ($vars as $var => $value)
    {
        switch ($var)
        {
            case "group":
            case "group_id":
                $values = get_group_entities($value);
                $sql .= generate_query_values($values, 'netscaler_vservers.vsvr_id');
                break;
            case "device":
            case "device_id":
                $sql .= generate_query_values($value, 'netscaler_vservers.device_id');
                break;

        }
    }

    return $sql;
}

function build_netscalervsvr_sort($vars)
{
    $order = '';
    switch ($vars['sort'])
    {
        case 'traffic':
            $order = ' ORDER BY `netscaler_vservers`.`vsvr_bps_out`';
            if(isset($vars['sort_desc']) && $vars['sort_desc']) { $order .= " DESC"; }
            break;
        default:
            $order = ' ORDER BY `netscaler_vservers`.`vsvr_label`';
            break;
    }
    return $order;
}

function print_netscalervsvr_table($vars)
{

    global $cache;

    $sql  = build_netscalervsvr_query($vars);
    //$sql .= build_netscalervsvr_sort($vars);

    $vsvrs = array();
    foreach (dbFetchRows($sql) as $vsvr)
    {
        if (isset($cache['devices']['id'][$vsvr['device_id']]))
        {
            $vsvr['hostname'] = $cache['devices']['id'][$vsvr['device_id']]['hostname'];
            $vsvr['html_row_class'] = $cache['devices']['id'][$vsvr['device_id']]['html_row_class'];
            $vsvrs[] = $vsvr;
        }
    }

    // Sorting
    // FIXME. Sorting can be as function, but in must before print_table_header and after get table from db
    switch ($vars['sort_order'])
    {
        case 'desc':
            $sort_order = SORT_DESC;
            $sort_neg   = SORT_ASC;
            break;
        case 'reset':
            unset($vars['sort'], $vars['sort_order']);
        // no break here
        default:
            $sort_order = SORT_ASC;
            $sort_neg   = SORT_DESC;
    }
    switch($vars['sort'])
    {
        case 'traffic':
            $vsvrs = array_sort_by($vsvrs, 'vsvr_bps_out', $sort_neg, SORT_NUMERIC);
            break;
        case 'status':
            $vsvrs = array_sort_by($vsvrs, 'vsvr_state', $sort_neg, SORT_STRING);
            break;
        case 'type':
            $vsvrs = array_sort_by($vsvrs, 'vsvr_type', $sort_neg, SORT_STRING);
            break;
        default:
            $vsvrs = array_sort_by($vsvrs, 'vsvr_label', $sort_order, SORT_STRING);
            break;
    }

    $vsvrs_count = count($vsvrs);

    // Pagination
    $pagination_html = pagination($vars, $vsvrs_count);
    echo $pagination_html;

    if ($vars['pageno'])
    {
        $vsvrs = array_chunk($vsvrs, $vars['pagesize']);
        $vsvrs = $vsvrs[$vars['pageno'] - 1];
    }
    // End Pagination

    echo generate_box_open();

    print_netscalervsvr_table_header($vars);

    foreach ($vsvrs as $vsvr)
    {
        echo generate_netscalervsvr_row($vsvr, $vars);
    }

    echo("</tbody></table>");

    echo generate_box_close();

    echo $pagination_html;

}

function print_netscalervsvr_row($vsvr, $vars)
{
  echo generate_netscalervsvr_row($vsvr, $vars);
}

function generate_netscalervsvr_row($vsvr, $vars)
{

    $table_cols = 6;

    $vsvr['num_services'] = dbFetchCell ("SELECT COUNT(*) FROM `netscaler_services_vservers` AS SV WHERE SV.device_id = ? AND SV.vsvr_name = ?",array($vsvr['device_id'], $vsvr['vsvr_name']));

    $row = '';

    if ($vsvr['vsvr_state'] == "up") { $vsvr_class="label label-success"; $row_class = ""; } else { $vsvr_class="label label-error"; $row_class = "error"; }

    if ($vsvr['vsvr_port'] != "0") {
        if ($vsvr['vsvr_ip']   != "0.0.0.0") { $vsvr['addrs'][] = $vsvr['vsvr_ip'].":".$vsvr['vsvr_port']; }
        if ($vsvr['vsvr_ipv6'] != "0:0:0:0:0:0:0:0") { $vsvr['addrs'][] = "[".Net_IPv6::compress($vsvr['vsvr_ipv6'])."]:".$vsvr['vsvr_port']; }
    }

    $row .= '<tr class="'.$row_class.'">';
    $row .= '<td class="state-marker"></td>';

    if ($vars['page'] != "device" && $vars['popup'] != TRUE)
    {
        $row .= '<td class="entity">' . generate_device_link($vsvr) . '</td>';
        $table_cols++; // Add a column for device.
    }

    switch($vsvr['vsvr_type'])
    {
      case "http":
        $vsvr['type_class'] = 'label-success';
        break;
      case "ftp":
        $vsvr['type_class'] = 'label-warning';
        break;
      case "ssl":
        $vsvr['type_class'] = 'label-suppressed';
        break;
      case "any":
        $vsvr['type_class'] = 'label-primary';
        break;

      default:
        $vsvr['type_class'] = '';
    }

    switch($vsvr['vsvr_entitytype'])
    {
      case "loadbalancing":
        $vsvr['entitytype_class'] = 'label-success';
        break;
      case "sslvpn":
        $vsvr['entitytype_class'] = 'label-suppressed';
        break;
      default:
        $vsvr['entitytype_class'] = '';
    }

    $row .= '<td class="entity-name"><a href="'.generate_url(array('page' => 'device', 'device' => $vsvr['device_id'], 'tab' => 'loadbalancer', 'type' => 'netscaler_vsvr', 'vsvr' => $vsvr['vsvr_id'], 'view' => NULL, 'graph' => NULL)).'">' . $vsvr['vsvr_label'] . '</a></td>';
    $row .= "<td>" . implode($vsvr['addrs'], "<br />") . "</td>";
    $row .= '<td><span class="label '.$vsvr['type_class'].'">'.$vsvr['vsvr_type'].'</span><br /><span class="label '.$vsvr['entitytype_class'].'">'.$vsvr['vsvr_entitytype'].'<span></td>';
    $row .= "<td><span class='label ".$vsvr_class."'>" . $vsvr['vsvr_state'] . "</span><br />".$vsvr['num_services']." service(s)</td>";
    $row .= '<td><span class="green"><i class="icon-circle-arrow-down"></i> ' . format_si($vsvr['vsvr_bps_in']*8) . 'bps</span><br />';
    $row .= '<span style="color: #394182;"><i class="icon-circle-arrow-up" style="color: #394182;"></i> ' . format_si($vsvr['vsvr_bps_out']*8) . 'bps</a></span></td>';
    $row .= "</tr>";
    if ($vars['view'] == "services")
    {
        $service_types = array(array("table" => "netscaler_services", "desc" => "Service"),
                               array("table" => "netscaler_servicegroupmembers", "desc" => "Service Group Member"));

        foreach ($service_types as $service_type)
        {
            $svcs = dbFetchRows("SELECT * FROM `netscaler_services_vservers` AS SV, `". $service_type["table"] ."` AS S WHERE SV.device_id = ? AND SV.vsvr_name = ? AND S.device_id = ? AND S.svc_name = SV.svc_name", array($vsvr['device_id'], $vsvr['vsvr_name'], $vsvr['device_id']));
            $row .= '<tr><td colspan="'.$table_cols.'">';
            if (count($svcs))
            {
                $row .= '<table class="table table-striped table-condensed ">';
                $row .= "  <thead>";
                $row .= '    <th class="state-marker"></th>';
                $row .= "    <th>". $service_type["desc"] ."</th>";
                $row .= "    <th>Address</th>";
                $row .= "    <th>Status</th>";
                $row .= "    <th>Input</th>";
                $row .= "    <th>Output</th>";
                $row .= "  </thead>";

                foreach ($svcs as $svc)
                {
                    if ($svc['svc_state'] == "up") { $svc_class="success"; unset($svc_row);} else { $svc_class="error"; $svc_row = "error"; }
                    $row .= '<tr class="'.$svc_row.'">';
                    $row .= '<td class="state-marker"></td>';
                    $row .= '<td class="entity-name"><a href="'.generate_url($vars, array('type' => $service_type["table"], 'svc' => $svc['svc_id'], 'view' => NULL, 'graph' => NULL)).'">' . $svc['svc_label'] . '</a></td>';
                    $row .= "<td width=320>" . $svc['svc_ip'] . ":" . $svc['svc_port'] . "</a></td>";
                    $row .= "<td width=100><span class='label label-".$svc_class."'>" . $svc['svc_state'] . "</span></td>";
                    $row .= '<td width=150><span class="green"><i class="icon-circle-arrow-down"></i> ' . format_si($svc['svc_bps_in']*8) . "bps</span></td>";
                    $row .= '<td width=150><span style="color: #394182;"><i class="icon-circle-arrow-up"></i> ' . format_si($svc['svc_bps_out']*8) . "bps</span></td>";
                    $row .= "</td></tr>";
                }
                $row .= "</table>";
            }
        }
    }
    echo("</td></tr>");
    if ($vars['graph'] == "summary")
    {
        $row .= '<tr class="entity" bgcolor="'.$bg_colour.'">';
        $row .= '<td colspan="'.$table_cols.'">';
        $graph_array['to']     = $config['time']['now'];
        $graph_array['id']     = $vsvr['vsvr_id'];
        $graph_array['types']  = array('netscalervsvr_bits', 'netscalervsvr_pkts', 'netscalervsvr_conns', 'netscalervsvr_reqs', 'netscalervsvr_hitmiss');

        $row .= generate_graph_summary_row($graph_array);

        $row .= "
    </td>
    </tr>";

    }
    elseif (isset($vars['graph']))
    {
        $row .= '<tr class="entity" bgcolor="'.$bg_colour.'">';
        $row .= '<td colspan="'.$table_cols.'">';
        $graph_type = "netscalervsvr_" . $vars['graph'];
        $graph_array['to']     = $config['time']['now'];
        $graph_array['id']     = $vsvr['vsvr_id'];
        $graph_array['type']   = $graph_type;

        $row .= generate_graph_row($graph_array);

        $row .= "
    </td>
    </tr>";
    }

    $row .= "</td>";
    $row .= "</tr>";

    return $row;

}


function print_netscalervsvr_table_header($vars)
{
    if ($vars['view'] == "graphs")
    {
        $table_class = OBS_CLASS_TABLE_STRIPED_TWO;
    } else {
        $table_class = OBS_CLASS_TABLE_STRIPED;
    }

    echo('<table class="' . $table_class . '">' . PHP_EOL);
    $cols = array(
        array(NULL, 'class="state-marker"'),
        'device'    => array('Device', 'style="width: 200px;"'),
        'descr'     => array('vServer'),
        'addresses' => array('Addresses', 'style="width: 250px;"'),
        'type'      => array('Type', 'style="width: 200px;"'),
        'status'    => array('Status', 'style="width: 130px;"'),
        'traffic'   => array('Traffic', 'style="width: 130px;"'),

    );

    if ($vars['page'] == "device")
    {
        unset($cols['device']);
    }

    echo(get_table_header($cols, $vars));
    echo('<tbody>' . PHP_EOL);
}
