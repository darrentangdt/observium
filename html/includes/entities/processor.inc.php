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

function generate_processor_query($vars)
{
  $sql  = "SELECT * FROM `processors`";
  //$sql .= " LEFT JOIN `processors-state` USING(`processor_id`)";
  if(!isset($vars['sort']) || $vars['sort'] == 'hostname' || $vars['sort'] == 'device' || $vars['sort'] == 'device_id')
  {
    $sql .= ' LEFT JOIN `devices` USING(`device_id`)';
  }
  $sql .= ' WHERE 1' . generate_query_permitted(array('device'));

  // Build query
  foreach($vars as $var => $value)
  {
    switch ($var)
    {
      case "group":
      case "group_id":
        $values = get_group_entities($value);
        $sql .= generate_query_values($values, 'processor_id');
        break;
      case "device":
      case "device_id":
        $sql .= generate_query_values($value, 'device_id');
        break;
    }
  }

  switch ($vars['sort_order'])
  {
    case 'descr':
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
    case 'usage':
      $sql .= ' ORDER BY `processor_usage` '.$sort_neg;
      break;
    case 'descr':
      $sql .= ' ORDER BY `processor_descr` '.$sort_order;
      break;
    default:
      $sql .= ' ORDER BY `hostname` '.$sort_order.', `processor_descr` '.$sort_order;
      break;
  }

  return $sql;
}


function print_processor_table($vars)
{
  global $cache;

  $sql = generate_processor_query($vars);

  $processors = array();
  foreach(dbFetchRows($sql) as $proc)
  {
    if (isset($cache['devices']['id'][$proc['device_id']]))
    {
      $proc['hostname']       = $cache['devices']['id'][$proc['device_id']]['hostname'];
      $proc['html_row_class'] = $cache['devices']['id'][$proc['device_id']]['html_row_class'];
      $processors[] = $proc;
    }
  }

  $processors_count = count($processors);

  // Pagination
  $pagination_html = pagination($vars, $processors_count);
  echo $pagination_html;

  if ($vars['pageno'])
  {
    $processors = array_chunk($processors, $vars['pagesize']);
    $processors = $processors[$vars['pageno']-1];
  }
  // End Pagination

  echo generate_box_open();

  print_processor_table_header($vars);

  foreach($processors as $processor)
  {
    print_processor_row($processor, $vars);
  }

  echo("</tbody></table>");

  echo generate_box_close();

  echo $pagination_html;

}

function print_processor_table_header($vars)
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
    'descr'     => array('Processor'),
                   array('', 'style="width: 100px;"'),
    'usage'     => array('Usage', 'style="width: 250px;"'),
  );

  if ($vars['page'] == "device")
  {
    unset($cols['device']);
  }

  echo(get_table_header($cols, $vars));
  echo('<tbody>' . PHP_EOL);
}

function print_processor_row($processor, $vars)
{
  echo generate_processor_row($processor, $vars);
}

function generate_processor_row($processor, $vars)
{
  global $config;

  $table_cols = 4;
  if ($vars['page'] != "device" && $vars['popup'] != TRUE) { $table_cols++; } // Add a column for device.

  // FIXME should that really be done here? :-)
  // FIXME - not it shouldn't. we need some per-os rewriting on discovery-time.
  $text_descr = $processor['processor_descr'];
  $text_descr = str_replace("Routing Processor", "RP", $text_descr);
  $text_descr = str_replace("Switching Processor", "SP", $text_descr);
  $text_descr = str_replace("Sub-Module", "Module ", $text_descr);
  $text_descr = str_replace("DFC Card", "DFC", $text_descr);

  $graph_array           = array();
  $graph_array['to']     = $config['time']['now'];
  $graph_array['id']     = $processor['processor_id'];
  $graph_array['type']   = 'processor_usage';
  $graph_array['legend'] = "no";

  $link_array = $graph_array;
  $link_array['page'] = "graphs";
  unset($link_array['height'], $link_array['width'], $link_array['legend']);
  $link_graph = generate_url($link_array);

  $link = generate_url(array("page" => "device", "device" => $processor['device_id'], "tab" => "health", "metric" => 'processor'));

  $overlib_content = generate_overlib_content($graph_array, $processor['hostname'] ." - " . $text_descr);

  $graph_array['width'] = 80;
  $graph_array['height'] = 20;
  $graph_array['bg'] = 'ffffff00';
  $graph_array['from'] = $config['time']['day'];
  $mini_graph =  generate_graph_tag($graph_array);

  $perc = round($processor['processor_usage']);
  $background = get_percentage_colours($perc);

  $processor['html_row_class'] = $background['class'];

  $row .= '<tr class="' . $processor['html_row_class'] . '">
          <td class="state-marker"></td>';

  if ($vars['page'] != "device" && $vars['popup'] != TRUE) { $row .= '<td class="entity">' . generate_device_link($processor) . '</td>'; }

  $row .= '  <td class="entity">'.generate_entity_link('processor', $processor).'</td>
      <td>'.overlib_link($link_graph, $mini_graph, $overlib_content).'</td>
      <td><a href="'.$link_graph.'">
        '.print_percentage_bar (400, 20, $perc, $perc."%", "ffffff", $background['left'], (100 - $perc)."%" , "ffffff", $background['right']).'
        </a>
      </td>
    </tr>
   ';

  if ($vars['view'] == "graphs" || $vars['processor_id'] == $processor['processor_id'])
  {

      $vars['graph'] = "usage";

    $row .= '<tr class="' . $processor['html_row_class'] . '">';
    $row .= '<td class="state-marker"></td>';
    $row .= '<td colspan='.$table_cols.'>';

    unset($graph_array['height'], $graph_array['width'], $graph_array['legend']);
    $graph_array['to']     = $config['time']['now'];
    $graph_array['id']     = $processor['processor_id'];
    $graph_array['type']   = 'processor_'.$vars['graph'];

    $row .= generate_graph_row($graph_array, TRUE);

    $row .= '</td></tr>';
  } # endif graphs

  return $row;

}

// EOF
