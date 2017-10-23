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

function build_netscalersvc_query($vars)
{

    global $config, $cache;

    $sql = 'SELECT *, `netscaler_services`.`svc_id` AS `svc_id` FROM `netscaler_services`';
    $sql .= ' WHERE 1' . generate_query_permitted(array('device'));

    // Build query
    foreach ($vars as $var => $value)
    {
        switch ($var)
        {
            case "group":
            case "group_id":
                $values = get_group_entities($value);
                $sql .= generate_query_values($values, 'netscaler_services.svc_id');
                break;
            case "device":
            case "device_id":
                $sql .= generate_query_values($value, 'netscaler_services.device_id');
                break;
        }
    }

    return $sql;
}


function print_netscalersvc_table($vars)
{

    global $cache;

    $sql  = build_netscalersvc_query($vars);
    //$sql .= build_netscalersvc_sort($vars);

    $svcs = array();
    foreach (dbFetchRows($sql) as $svc)
    {
        if (isset($cache['devices']['id'][$svc['device_id']]))
        {
            $svc['hostname'] = $cache['devices']['id'][$svc['device_id']]['hostname'];
            $svc['html_row_class'] = $cache['devices']['id'][$svc['device_id']]['html_row_class'];
            $svcs[] = $svc;
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
        case 'input':
            $svcs = array_sort_by($svcs, 'svc_bps_in', $sort_neg, SORT_NUMERIC);
            break;
        case 'output':
            $svcs = array_sort_by($svcs, 'svc_bps_out', $sort_neg, SORT_NUMERIC);
            break;
        case 'status':
            $svcs = array_sort_by($svcs, 'svc_state', $sort_neg, SORT_STRING);
            break;
        case 'address':
            $svcs = array_sort_by($svcs, 'svc_address', $sort_neg, SORT_STRING);
            break;
        default:
            $svcs = array_sort_by($svcs, 'svc_label', $sort_order, SORT_STRING);
            break;
    }

    $svcs_count = count($svcs);

    // Pagination
    $pagination_html = pagination($vars, $svcs_count);
    echo $pagination_html;

    if ($vars['pageno'])
    {
        $svcs = array_chunk($svcs, $vars['pagesize']);
        $svcs = $svcs[$vars['pageno'] - 1];
    }
    // End Pagination

    echo generate_box_open();

    print_netscalersvc_table_header($vars);

    foreach ($svcs as $svc)
    {
        echo generate_netscalersvc_row($svc, $vars);
    }

    echo("</tbody></table>");

    echo generate_box_close();

    echo $pagination_html;

}

function print_netscalersvc_row($svc, $vars)
{
    echo generate_netscalersvc_row($svc, $vars);
}

function generate_netscalersvc_row($svc, $vars)
{

    $table_cols = 6;

    $row = '';

    if ($svc['svc_state'] == "up") { $svc_class="label label-success"; $row_class = ""; } else { $svc_class="label label-error"; $row_class = "error"; }

    $row .= '<tr class="' . $row_class . '">';
    $row .= '<td class="state-marker">';
    if ($vars['page'] != "device" && $vars['popup'] != TRUE)
    {
        $row .= '<td class="entity">' . generate_device_link($svc) . '</td>';
        $table_cols++; // Add a column for device.
    }
    $row .= '<td style="width: 320px;"><strong><a href="' . generate_url(array('page' => 'device', 'device' => $svc['device_id'], 'tab' => 'loadbalancer', 'type' => 'netscaler_services', 'svc' => $svc['svc_id'], 'view' => NULL, 'graph' => NULL)).'">' . $svc['svc_label'] . '</a></strong></td>';
    $row .= '<td style="width: 320px;">' . $svc['svc_ip'] . ':' . $svc['svc_port'] . '</td>';
    $row .= '<td style="width: 100px;"><span class="'.$svc_class.'">' . $svc['svc_state'] . '</span></td>';
    $row .= '<td style="width: 100px;"><span class="green"><i class="icon-circle-arrow-down"></i> ' . format_si($svc['svc_bps_in']*8) . 'bps</span></td>';
    $row .= '<td style="width: 100px;"><span style="color: #394182;"><i class="icon-circle-arrow-up"></i> ' . format_si($svc['svc_bps_out']*8) . 'bps</span></td>';
    $row .= '</tr>';

    if ($vars['graph'] == "summary")
    {
        $row .= '<tr class="entity">';
        $row .= '<td colspan="'.$table_cols.'">';
        $graph_array['to']     = $config['time']['now'];
        $graph_array['id']     = $svc['svc_id'];
        $graph_array['types']  = array('netscalersvc_bits', 'netscalersvc_pkts', 'netscalersvc_conns', 'netscalersvc_reqs', 'netscalersvc_ttfb');

        $row .= generate_graph_summary_row($graph_array);

        echo("
    </td>
    </tr>");

    }
    elseif (isset($vars['graph']))
    {
        $row .= '<tr class="entity">';
        $row .= '<td colspan="'.$table_cols.'">';
        $graph_type = "netscalersvc_" . $vars['graph'];
        $graph_array['to']     = $config['time']['now'];
        $graph_array['id']     = $svc['svc_id'];
        $graph_array['type']   = $graph_type;

        $row .= generate_graph_row($graph_array);

        $row .= "
      </td>
      </tr>";
    }

    return $row;

}

function print_netscalersvc_table_header($vars)
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
        'descr'     => array('Service'),
        'address'   => array('Address', 'style="width: 250px;"'),
        'status'    => array('Status', 'style="width: 130px;"'),
        'input'     => array('Input', 'style="width: 130px;"'),
        'output'    => array('Output', 'style="width: 130px;"'),

    );

    if ($vars['page'] == "device")
    {
        unset($cols['device']);
    }

    echo(get_table_header($cols, $vars));
    echo('<tbody>' . PHP_EOL);
}
