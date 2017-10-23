<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage web
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2017 Observium Limited
 *
 */

/**
 * Humanize sensor.
 *
 * Returns a the $sensor array with processed information:
 * sensor_state (TRUE: state sensor, FALSE: normal sensor)
 * human_value, sensor_symbol, state_name, state_event, state_class
 *
 * @param array $sensor
 * @return array $sensor
 *
 */
// TESTME needs unit testing
function humanize_sensor(&$sensor)
{
  global $config;

  // Exit if already humanized
  if ($sensor['humanized']) { return; }

  $sensor['sensor_symbol'] = $GLOBALS['config']['sensor_types'][$sensor['sensor_class']]['symbol'];
  $sensor['sensor_format'] = strval($GLOBALS['config']['sensor_types'][$sensor['sensor_class']]['format']);
  $sensor['state_class']   = ''; //'text-success';

  // Generate "pretty" thresholds
  if (is_numeric($sensor['sensor_limit_low']))
  {
    $sensor_threshold_low = format_value($sensor['sensor_limit_low'], $sensor['sensor_format']) . $sensor['sensor_symbol'];
  } else {
    $sensor_threshold_low = "&infin;";
  }

  if (is_numeric($sensor['sensor_limit_low_warn']))
  {
    $sensor_warn_low = format_value($sensor['sensor_limit_low_warn'], $sensor['sensor_format']) . $sensor['sensor_symbol'];
  } else {
    $sensor_warn_low = NULL;
  }

  if ($sensor_warn_low) { $sensor_threshold_low = $sensor_threshold_low . " (".$sensor_warn_low.")"; }

  if (is_numeric($sensor['sensor_limit']))
  {
    $sensor_threshold_high = format_value($sensor['sensor_limit'], $sensor['sensor_format']) . $sensor['sensor_symbol'];
  } else {
    $sensor_threshold_high = "&infin;";
  }

  if (is_numeric($sensor['sensor_limit_warn']))
  {
    $sensor_warn_high = format_value($sensor['sensor_limit_warn'], $sensor['sensor_format']) . $sensor['sensor_symbol'];
  } else {
    $sensor_warn_high = "&infin;";
  }

  if ($sensor_warn_high) { $sensor_threshold_high = "(".$sensor_warn_high.") " . $sensor_threshold_high; }

  $sensor['sensor_thresholds'] = $sensor_threshold_low . ' - ' . $sensor_threshold_high;

  // generate pretty value
  if (!is_numeric($sensor['sensor_value']))
  {
    $sensor['human_value'] = 'NaN';
    $sensor['sensor_symbol'] = '';
  } else {
    $sensor['human_value'] = format_value($sensor['sensor_value'], $sensor['sensor_format']);
  }

  if (isset($config['entity_events'][$sensor['sensor_event']]))
  {
    $sensor = array_merge($sensor, $config['entity_events'][$sensor['sensor_event']]);
  } else {
    $sensor['event_class'] = 'label label-primary';
    $sensor['row_class']   = '';
  }
  //r($sensor);
  if ($sensor['sensor_deleted'])
  {
    $sensor['row_class']   = 'disabled';
  }

  $device = &$GLOBALS['cache']['devices']['id'][$sensor['device_id']];
  if ((isset($device['status']) && !$device['status']) || (isset($device['disabled']) && $device['disabled']))
  {
    $sensor['row_class']     = 'error';
  }

  // Set humanized entry in the array so we can tell later
  $sensor['humanized'] = TRUE;
}

function build_sensor_query($vars)
{

  $sql  = "SELECT * FROM `sensors`";
  if($vars['sort'] == 'hostname' || $vars['sort'] == 'device' || $vars['sort'] == 'device_id')
  {
    $sql .= ' LEFT JOIN `devices` USING(`device_id`)';
  }
  $sql .= " WHERE `sensor_deleted` = 0";

  // Build query
  foreach($vars as $var => $value)
  {
    switch ($var)
    {
      case "metric":
        if ($value != "sensors") { $sql .= generate_query_values($value, 'sensors.sensor_class'); }
        break;
      case "group":
      case "group_id":
        $values = get_group_entities($value);
        $sql .= generate_query_values($values, 'sensors.sensor_id');
        break;
      case "device":
      case "device_id":
        $sql .= generate_query_values($value, 'sensors.device_id');
        break;
      case "entity_id":
        $sql .= generate_query_values($value, 'sensors.measured_entity');
        break;
      case "entity_type":
        $sql .= generate_query_values($value, 'sensors.measured_class');
        break;
      case "sensor_descr":
        $sql .= generate_query_values($value, 'sensors.sensor_descr', '%LIKE%');
        break;
      case "sensor_type":
        $sql .= generate_query_values($value, 'sensors.sensor_type', '%LIKE%');
        break;
      case "id":
        $sql .= generate_query_values($value, 'sensors.sensor_id');
        break;
      case "event":
        $sql .= generate_query_values($value, 'sensor_event');
        break;
    }
  }
  // $sql .= $GLOBALS['cache']['where']['devices_permitted'];

  $sql .= generate_query_permitted(array('device', 'sensor'));

  switch ($vars['sort_order'])
  {
    case 'desc':
      $sort_order = 'DESC';
      $sort_neg   = 'ASC';
      break;
    case 'reset':
      unset($vars['sort'], $vars['sort_order']);
      // no break here
    default:
      $sort_order = 'ASC';
      $sort_neg   = 'DESC';
  }


  switch($vars['sort'])
  {
    case 'device':
      $sql .= ' ORDER BY `hostname` '.$sort_order;
      break;
    case 'descr':
    case 'event':
      $sql .= ' ORDER BY `sensor_'.$vars['sort'].'` '.$sort_order;
      break;
    case 'value':
    case 'last_change':
      $sql .= ' ORDER BY `sensor_'.$vars['sort'].'` '.$sort_order;
      break;
    default:
      // $sql .= ' ORDER BY `hostname` '.$sort_order.', `sensor_descr` '.$sort_order;
  }

  if(isset($vars['pageno']))
  {
    $start = $vars['pagesize'] * ($vars['pageno'] - 1);
    $sql .= ' LIMIT '.$start.','.$vars['pagesize'];
  }

  return $sql;
}

function print_sensor_table($vars)
{

  $sql = build_sensor_query($vars);

//r($vars);
//r($sql);


  $sensors = array();
  foreach(dbFetchRows($sql) as $sensor)
  {
    //if (isset($GLOBALS['cache']['devices']['id'][$sensor['device_id']]))
    //{
      $sensor['hostname'] = $GLOBALS['cache']['devices']['id'][$sensor['device_id']]['hostname'];
      $sensors[] = $sensor;
    //}
  }

  $sensors_count = count($sensors);

  // Pagination
  $pagination_html = pagination($vars, $sensors_count);
  echo $pagination_html;

  echo generate_box_open();

  print_sensor_table_header($vars);

  foreach($sensors as $sensor)
  {
    print_sensor_row($sensor, $vars);
  }

  echo("</tbody></table>");

  echo generate_box_close();

  echo $pagination_html;
}

function print_sensor_table_header($vars)
{
  if ($vars['view'] == "graphs" || $vars['graph'] || isset($vars['id']))
  {
    $stripe_class = "table-striped-two";
  } else {
    $stripe_class = "table-striped";
  }

  echo('<table class="table ' . $stripe_class . ' table-condensed ">' . PHP_EOL);
  $cols = array(
                     array(NULL, 'class="state-marker"'),
    'device'      => array('Device', 'style="width: 250px;"'),
    'descr'       => array('Description'),
    'class'       => array('Class', 'style="width: 100px;"'),
                     array('Thresholds', 'style="width: 100px;"'),
                     array('History'),
    'last_change' => array('Last&nbsp;changed', 'style="width: 80px;"'),
    'event'       => array('Event', 'style="width: 60px; text-align: right;"'),
    'value'       => array('Value', 'style="width: 80px; text-align: right;"'),
  );

  if ($vars['page'] == "device")  { unset($cols['device']); }
  if (!$vars['show_class'])       { unset($cols['class']); }
  if ($vars['tab'] == "overview") { unset($cols[2]); } // Thresholds

  echo(get_table_header($cols, $vars));
  echo('<tbody>' . PHP_EOL);
}

function print_sensor_row($sensor, $vars)
{
  echo generate_sensor_row($sensor, $vars);
}

function generate_sensor_row($sensor, $vars)
{
  global $config;

  humanize_sensor($sensor);

  $table_cols = 4;

  $graph_array           = array();
  $graph_array['to']     = $config['time']['now'];
  $graph_array['id']     = $sensor['sensor_id'];
  $graph_array['type']   = "sensor_graph";
  $graph_array['width']  = 80;
  $graph_array['height'] = 20;
  $graph_array['bg']     = 'ffffff00';
  $graph_array['from']   = $config['time']['day'];

  if ($sensor['sensor_event'] && is_numeric($sensor['sensor_value']))
  {
    $mini_graph = generate_graph_tag($graph_array);
  } else {
    // Do not show "Draw Error" minigraph
    $mini_graph = '';
  }

  $row = '
      <tr class="'.$sensor['row_class'].'">
        <td class="state-marker"></td>';

  if ($vars['page'] != "device" && $vars['popup'] != TRUE)
  {
    $row .= '        <td class="entity">' . generate_device_link($sensor) . '</td>' . PHP_EOL;
    $table_cols++;
  }

  if ($vars['entity_icon'] == TRUE)
  {
    $row .= '        <td width="20px"><i class="'.$config['sensor_types'][$sensor['sensor_class']]['icon'].'"></i></td>';
  }
  $row .= '        <td class="entity">' . generate_entity_link("sensor", $sensor) . '</td>';

  if ($vars['show_class'])
  {
    $row .= '        <td>' . nicecase($sensor['sensor_class']). '</td>' . PHP_EOL;
    $table_cols++;
  }

  if ($vars['tab'] != 'overview')
  {
    $row .= '        <td><span class="label ' . ($sensor['sensor_custom_limit'] ? 'label-warning' : '') . '">' . $sensor['sensor_thresholds'] . '</span></td>' . PHP_EOL;
    $table_cols++;
  }
  $row .= '        <td style="width: 90px; text-align: right;">' . generate_entity_link('sensor', $sensor, $mini_graph, NULL, FALSE) . '</td>';

  if ($vars['tab'] != 'overview')
  {
    $row .= '        <td style="white-space: nowrap">' . generate_tooltip_link(NULL, formatUptime(($config['time']['now'] - $sensor['sensor_last_change']), 'short-2') . ' ago', format_unixtime($sensor['sensor_last_change'])) . '</td>';
    $table_cols++;
    $row .= '        <td style="text-align: right;"><strong>' . generate_tooltip_link('', $sensor['sensor_event'], $sensor['event_descr'], $sensor['event_class']) . '</strong></td>';
    $table_cols++;
  }
  $row .= '        <td style="width: 80px; text-align: right;"><strong>' . generate_tooltip_link('', $sensor['human_value'] . $sensor['sensor_symbol'], $sensor['event_descr'], $sensor['event_class']) . '</strong>
        </tr>' . PHP_EOL;

  if ($vars['view'] == "graphs" || $vars['id'] == $sensor['sensor_id']) { $vars['graph'] = "graph"; }
  if ($vars['graph'])
  {
    $row .= '
      <tr class="'.$sensor['row_class'].'">
        <td class="state-marker"></td>
        <td colspan="'.$table_cols.'">';

    $graph_array = array();
    $graph_array['to']     = $config['time']['now'];
    $graph_array['id']     = $sensor['sensor_id'];
    $graph_array['type']   = 'sensor_'.$vars['graph'];

    $row .= generate_graph_row($graph_array, TRUE);

    $row .= '</td></tr>';
  } # endif graphs

  return $row;
}

// EOF
