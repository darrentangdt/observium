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

// If we have a single vserver id, show data for that vserver

if(is_numeric($vars['vsvr']))
{

$graph_types = array("bits"   => "Bits",
                     "pkts"   => "Packets",
                     "conns"  => "Connections",
                     "reqs"   => "Requests",
                     "hitmiss" => "Hit/Miss");

echo generate_box_open();

echo('<table class="table table-striped table-condensed " style="margin-top: 10px;">');
echo("  <thead>");
echo("    <tr>");
echo '      <th class="state-marker"></th>';
echo("      <th>vServer</th>");
echo("      <th style=\"width: 250px;\">Addresses</th>");
echo("      <th style=\"width: 200px;\">Type</th>");
echo("      <th style=\"width: 130px;\">Status</th>");
echo("      <th style=\"width: 130px;\">Traffic</th>");
echo("    </tr>");
echo("   </thead>");

foreach (dbFetchRows("SELECT * FROM `netscaler_vservers` WHERE `device_id` = ? AND `vsvr_id` = ? ORDER BY `vsvr_label`", array($device['device_id'], $vars['vsvr'])) as $vsvr)
{
  $vsvr['num_services'] = dbFetchCell ("SELECT COUNT(*) FROM `netscaler_services_vservers` AS SV WHERE SV.device_id = ? AND SV.vsvr_name = ?",array($device['device_id'], $vsvr['vsvr_name']));

  if ($vsvr['vsvr_state'] == "up") { $vsvr_class="label label-success"; $row_class = ""; } else { $vsvr_class="label label-error"; $row_class = "error"; }

  if ($vsvr['vsvr_port'] != "0") {
    if ($vsvr['vsvr_ip']   != "0.0.0.0") { $vsvr['addrs'][] = $vsvr['vsvr_ip'].":".$vsvr['vsvr_port']; }
    if ($vsvr['vsvr_ipv6'] != "0:0:0:0:0:0:0:0") { $vsvr['addrs'][] = "[".Net_IPv6::compress($vsvr['vsvr_ipv6'])."]:".$vsvr['vsvr_port']; }
  }

  echo '<tr class="'.$row_class.'">';
  echo '<td class="state-marker">';
  echo('<td class="entity-name"><a href="'.generate_url($vars, array('vsvr' => $vsvr['vsvr_id'], 'view' => NULL, 'graph' => NULL)).'">' . $vsvr['vsvr_label'] . '</a></td>');
  echo("<td>" . implode($vsvr['addrs'], "<br />") . "</td>");
  echo('<td>'.$vsvr['vsvr_type'].'<br />'.$vsvr['vsvr_entitytype'].'</td>');
  echo("<td><span class='".$vsvr_class."'>" . $vsvr['vsvr_state'] . "</span><br />".$vsvr['num_services']." service(s)</td>");
  echo '<td><span class="green"><i class="icon-circle-arrow-down"></i> ' . format_si($vsvr['vsvr_bps_in']*8) . 'bps</span><br />';
  echo '<span style="color: #394182;"><i class="icon-circle-arrow-up" style="color: #394182;"></i> ' . format_si($vsvr['vsvr_bps_out']*8) . 'bps</a></span></td>';
  echo("</tr>");

  $service_types = array(array("table" => "netscaler_services", "desc" => "Service"),
                         array("table" => "netscaler_servicegroupmembers", "desc" => "Service Group Member"));

  foreach ($service_types as $service_type) {
    $svcs = dbFetchRows("SELECT * FROM `netscaler_services_vservers` AS SV, `". $service_type["table"] ."` AS S WHERE SV.device_id = ? AND SV.vsvr_name = ? AND S.device_id = ? AND S.svc_name = SV.svc_name", array($device['device_id'], $vsvr['vsvr_name'], $device['device_id']));

    if (count($svcs))
    {
      echo('<tr><td colspan="6">');
      echo('<table class="table table-striped table-condensed " style="margin-top: 10px;">');
      echo("  <thead>");
      echo("    <th>". $service_type["desc"] ."</th>");
      echo("    <th>Address</th>");
      echo("    <th>Status</th>");
      echo("    <th>Input</th>");
      echo("    <th>Output</th>");
      echo("  </thead>");
      foreach ($svcs as $svc)
      {
        if ($svc['svc_state'] == "up") { $svc_class="green"; $svc_row=""; } else { $svc_class="red"; $svc_row = "error"; }
        echo('<tr class="'.$svc_row.'">');
        echo('<td class="entity-name"><a href="'.generate_url($vars, array('type' => $service_type["table"], 'vsvr' => NULL, 'svc' => $svc['svc_id'], 'view' => NULL, 'graph' => NULL)).'">' . $svc['svc_label'] . '</a></td>');
        echo("<td width=320>" . $svc['svc_ip'] . ":" . $svc['svc_port'] . "</a></td>");
        echo("<td width=100><span class='".$svc_class."'>" . $svc['svc_state'] . "</span></td>");
        echo("<td width=150>" . format_si($svc['svc_bps_in']*8) . "bps</a></td>");
        echo("<td width=150>" . format_si($svc['svc_bps_out']*8) . "bps</a></td>");
        echo("</td></tr>");
      }
      echo("</table>");
    }
  }
  echo("</tr>");
  }

  foreach ($graph_types as $graph_type => $graph_text)
  {
    $i++;
    echo('<tr class="entity">');
    echo('<td colspan="6">');
    $graph_type = "netscalervsvr_" . $graph_type;
    $graph_array['to']     = $config['time']['now'];
    $graph_array['id']     = $vsvr['vsvr_id'];
    $graph_array['type']   = $graph_type;

    echo('<h3>'.$graph_text.'</h4>');

    print_graph_row($graph_array);

    echo("
    </td>
    </tr>");
  }

echo("</table>");

echo generate_box_close();

} else {

// If we dont' have a vserver ID, show data for all vservers

if(!$vars['graph'])
{ $graph_type = "device_netscalervsvr_bits"; } else {
  $graph_type = "device_netscalervsvr_".$vars['graph'];  }

$graph_array['to']     = $config['time']['now'];
$graph_array['device'] = $device['device_id'];
$graph_array['nototal'] = "yes";
$graph_array['legend'] = "no";
$graph_array['type']   = $graph_type;

echo generate_box_open(array('title' => 'Aggregate', 'header-border' => TRUE));

if($vars['graph'] == "summary")
{
  $graph_array['types']  = array('device_netscalervsvr_bits', 'device_netscalervsvr_pkts', 'device_netscalervsvr_conns', 'device_netscalervsvr_reqs', 'device_netscalervsvr_hitmiss');
  print_graph_summary_row($graph_array);
} else {
  print_graph_row($graph_array);
}

echo generate_box_close();

unset($graph_array);

$menu_options = array('basic' => 'Basic',
                      'services' => 'Services',
                      );

if (!$vars['view']) { $vars['view'] = "basic"; }

$navbar['brand'] = "VServers";
$navbar['class'] = "navbar-narrow";

foreach ($menu_options as $option => $text)
{
  if ($vars['view'] == $option) { $navbar['options'][$option]['class'] = "active"; }
  $navbar['options'][$option]['text'] = $text;
  $navbar['options'][$option]['url'] = generate_url($vars, array('view'=>$option, 'graph' => NULL));
}

if ($vars['view'] == "summary_graphs") { $navbar['options_right']['summary_graphs']['class'] = "active"; }

$graph_types = array("summary" => "Summary",
                     "bits"    => "Bits",
                     "pkts"    => "Packets",
                     "conns"   => "Connections",
                     "reqs"    => "Requests",
                     "hitmiss" => "Hit/Miss");

foreach ($graph_types as $type => $descr)
{
  if ($vars['graph'] == $type) { $navbar['options_right'][$type]['class'] = "active"; }
  $navbar['options_right'][$type]['text'] = $descr;
  $navbar['options_right'][$type]['url'] = generate_url($vars,array('view' => 'graphs', 'graph'=>$type));
}

print_navbar($navbar); unset($navbar);

print_netscalervsvr_table($vars);

}

// EOF
