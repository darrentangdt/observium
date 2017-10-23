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

register_html_title("Printing");

$navbar = array();
$navbar['brand'] = "Printer supplies";
$navbar['class'] = "navbar-narrow";

foreach ($printing_tabs as $type)
{
  if (!$vars['supply']) { $vars['supply'] = $type; }

  $navbar['options'][$type]['url']  = generate_url(array('page' => 'device', 'device' => $device['device_id'], 'tab' => 'printing', 'supply' => $type));
  $navbar['options'][$type]['text'] = nicecase($type);
  if ($vars['supply'] == $type) { $navbar['options'][$type]['class'] = "active"; }

}

if (dbFetchCell('SELECT COUNT(*) FROM `sensors` WHERE `device_id` = ? AND `sensor_class` = ? AND `sensor_descr` LIKE ?', array($device['device_id'], 'counter', '%print%')) > 0)
{
  $navbar['options']['pagecount']['url'] = generate_url(array('page' => 'device', 'device' => $device['device_id'], 'tab' => 'printing', 'supply' => 'pagecount'));
  $navbar['options']['pagecount']['text'] = 'Printed counters';
  if ($vars['supply'] == 'pagecount') { $navbar['options']['pagecount']['class'] = "active"; }
}

print_navbar($navbar);
unset($navbar);

switch ($vars['supply'])
{
  case 'pagecount':
    echo generate_box_open();
    echo('<table class="table table-condensed table-striped  table-striped">');

    $graph_title = "Printed counters";
    $graph_type = "device_pagecount";

    include("includes/print-device-graph.php");

    echo('</table>');
    echo generate_box_close();

    print_sensor_table(array('device_id' => $device['device_id'], 'metric' => 'counter', 'sensor_descr' => 'print', 'page' => 'device'));
    break;
  default:
    echo generate_box_open();
    echo('<table class="table table-condensed table-striped  table-striped">');

    $graph_title = nicecase($vars['supply']);
    $graph_type = "device_printersupplies_" . $vars['supply'];

    include("includes/print-device-graph.php");

    echo('</table>');
    echo generate_box_close();

    print_printersupplies_table($vars);
    break;
}

// EOF
