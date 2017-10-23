<?php

/**
 * Observium Network Management and Monitoring System
 * Copyright (C) 2006-2015, Adam Armstrong - http://www.observium.org
 *
 * @package    observium
 * @subpackage webui
 * @author     Adam Armstrong <adama@observium.org>
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

if (is_numeric($vars['svc']))
{
  $graph_types = array("bits"   => "Bits",
                       "pkts"   => "Packets",
                       "conns"  => "Connections",
                       "reqs"   => "Requests",
                       "ttfb"   => "Time to first byte",
                       "surge"  => "Surge Count");

  $i = 0;

  echo generate_box_open();

  echo '<table class="table table-striped table-condensed table-hover">';
  echo '  <thead><tr>';
  echo '    <th class="state-marker"></th>';
  echo '    <th>Service</th>';
  echo '    <th>Address</th>';
  echo '    <th>Status</th>';
  echo '    <th>Input</th>';
  echo '    <th>Output</th>';
  echo '  </tr></thead>';

  foreach (dbFetchRows("SELECT * FROM `netscaler_services` WHERE `device_id` = ? AND `svc_id` = ? ORDER BY `svc_label`", array($device['device_id'], $vars['svc'])) as $svc)
  {
    if (is_integer($i/2)) { $bg_colour = $list_colour_a; } else { $bg_colour = $list_colour_b; }

    if ($svc['svc_state'] == "up") { $svc_class="label label-success"; $row_class = ""; } else { $svc_class="label label-error"; $row_class = "error"; }

    echo '<tr class="' . $row_class . '">';
    echo '<td class="state-marker">';
    echo '<td style="width: 320px;"><strong><a href="' . generate_url($vars, array('svc' => $svc['svc_id'], 'view' => NULL, 'graph' => NULL)).'">' . $svc['svc_label'] . '</a></strong></td>';
    echo '<td style="width: 320px;">' . $svc['svc_ip'] . ':' . $svc['svc_port'] . '</td>';
    echo '<td style="width: 100px;"><span class="'.$svc_class.'">' . $svc['svc_state'] . '</span></td>';
    echo '<td style="width: 100px;"><span class="green"><i class="icon-circle-arrow-down"></i> ' . format_si($svc['svc_bps_in']*8) . 'bps</span></td>';
    echo '<td style="width: 100px;"><span style="color: #394182;"><i class="icon-circle-arrow-up"></i> ' . format_si($svc['svc_bps_out']*8) . 'bps</span></td>';
    echo '</tr>';

    $vsvrs = dbFetchRows("SELECT * FROM `netscaler_services_vservers` AS SV, `netscaler_vservers` AS V ".
                         "WHERE SV.device_id = ? AND SV.svc_name = ? AND V.device_id = SV.device_id AND V.vsvr_name = SV.vsvr_name", array($device['device_id'], $svc['svc_name']));

    if (count($vsvrs))
    {
      echo('<tr><td colspan="6">');
      echo('<table class="table table-striped  table-condensed" style="margin-top: 10px;">');
      echo("  <thead>\n");
      echo("    <th>Vserver</th>");
      echo("    <th>Address</th>");
      echo("    <th>Status</th>");
      echo("    <th>Input</th>");
      echo("    <th>Output</th>");
      echo("  </thead>");

      foreach ($vsvrs as $vsvr)
      {
        if ($vsvr['vsvr_state'] == "up") { $vsvr_class="green"; } else { $vsvr_class="red"; }
        echo("<tr>");
        echo('<td style="width: 320px;" class="object-name"><a href="'.generate_url($vars, array('type' => 'netscaler_vsvr', 'vsvr' => $vsvr['vsvr_id'], 'svc' => NULL, 'view' => NULL, 'graph' => NULL)).'">' . $vsvr['vsvr_label'] . '</a></td>');
        echo("<td style=\"width: 320px;\">" . $vsvr['vsvr_ip'] . ":" . $vsvr['vsvr_port'] . "</td>");
        echo("<td style=\"width: 100px;\"><span class='".$vsvr_class."'>" . $vsvr['vsvr_state'] . "</span></td>");
        echo("<td style=\"width: 320px;\">" . format_si($vsvr['vsvr_bps_in']*8) . "bps</td>");
        echo("<td style=\"width: 320px;\">" . format_si($vsvr['vsvr_bps_out']*8) . "bps</td>");
        echo("</tr>");
      }
      echo("</table>");
    }

    foreach ($graph_types as $graph_type => $graph_text)
    {
      $i++;
      echo('<tr>');
      echo('<td colspan="6">');
      $graph_type = "netscalersvc_" . $graph_type;
      $graph_array['to']     = $config['time']['now'];
      $graph_array['id']     = $svc['svc_id'];
      $graph_array['type']   = $graph_type;

      echo('<h3>'.$graph_text.'</h4>');

      print_graph_row($graph_array);

      echo("
      </td>
      </tr>");
    }
  }

  echo '</table>';

  echo generate_box_close();

} else {
  // No service was specified so we show aggregate and a list of services in table

  if (!$vars['graph'])
  { $graph_type = "device_netscalersvc_bits"; } else {
    $graph_type = "device_netscalersvc_".$vars['graph'];  }

  $graph_array['to']     = $config['time']['now'];
  $graph_array['device'] = $device['device_id'];
  $graph_array['nototal'] = "yes";
  $graph_array['legend'] = "no";
  $graph_array['type']   = $graph_type;

  echo generate_box_open(array('title' => 'Aggregate', 'header-border' => TRUE));

  if($vars['graph'] == "summary")
  {
    $graph_array['types']  = array('device_netscalersvc_bits', 'device_netscalersvc_pkts', 'device_netscalersvc_conns', 'device_netscalersvc_reqs', 'device_netscalersvc_ttfb', 'device_netscalersvc_surge');
    print_graph_summary_row($graph_array);
  } else {
    print_graph_row($graph_array);
  }

  echo generate_box_close();

  unset($graph_array);

  $menu_options = array('basic' => 'Basic',
                       );

  if (!$vars['view']) { $vars['view'] = "basic"; }

  $navbar['brand'] = "Services";
  $navbar['class'] = "navbar-narrow";

  foreach ($menu_options as $option => $text)
  {
    if ($vars['view'] == $option) { $navbar['options'][$option]['class'] = "active"; }
    $navbar['options'][$option]['text'] = $text;
    $navbar['options'][$option]['url'] = generate_url($vars, array('view'=>$option, 'graph' => NULL));
  }

  $graph_types = array("summary" => "Summary",
                       "bits"   => "Bits",
                       "pkts"   => "Packets",
                       "conns"  => "Connections",
                       "reqs"   => "Requests",
                       "ttfb"   => "Time to 1st Byte",
                       "surge"  => "Surge Count");

  foreach ($graph_types as $type => $descr)
  {
    if ($vars['graph'] == $type) { $navbar['options_right'][$type]['class'] = "active"; }
    $navbar['options_right'][$type]['text'] = $descr;
    $navbar['options_right'][$type]['url'] = generate_url($vars,array('view' => 'graphs', 'graph'=>$type));
  }

  print_navbar($navbar);
  unset($navbar);

  print_netscalersvc_table($vars);

}


// EOF
