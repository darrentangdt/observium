<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package        observium
 * @subpackage     webui
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

function build_printersupplies_query($vars)
{
  $sql = 'SELECT * FROM `printersupplies`';
  $sql .= ' WHERE 1' . generate_query_permitted(array('device'));

  // Build query
  foreach($vars as $var => $value)
  {
    switch ($var)
    {
      case "group":
      case "group_id":
        $values = get_group_entities($value);
        $sql .= generate_query_values($values, 'printersupplies.supply_id');
        break;
      case "device":
      case "device_id":
        $sql .= generate_query_values($value, 'printersupplies.device_id');
        break;
      case "supply":
        $sql .= generate_query_values($value, 'printersupplies.supply_type');
        break;
    }
  }

  return $sql;
}

function print_printersupplies_table($vars)
{
  $supplies = array();
  foreach(dbFetchRows(build_printersupplies_query($vars)) as $supply)
  {
    global $cache;

    if (isset($cache['devices']['id'][$supply['device_id']]))
    {
      $supply['hostname'] = $cache['devices']['id'][$supply['device_id']]['hostname'];
      $supply['html_row_class'] = $cache['devices']['id'][$supply['device_id']]['html_row_class'];
      $supplies[] = $supply;
    }
  }
  $supplies = array_sort_by($supplies, 'hostname', SORT_ASC, SORT_STRING, 'supply_descr', SORT_ASC, SORT_STRING);
  $supplies_count = count($supplies);

  echo generate_box_open();

  // Pagination
  $pagination_html = pagination($vars, $supplies_count);
  echo $pagination_html;

  if ($vars['pageno'])
  {
    $supplies = array_chunk($supplies, $vars['pagesize']);
    $supplies = $supplies[$vars['pageno'] - 1];
  }
  // End Pagination

  if ($vars['view'] == "graphs")
  {
    $stripe_class = "table-striped-two";
  } else {
    $stripe_class = "table-striped";
  }

  // Allow the table to be printed headerless for use in some places.
  if ($vars['headerless'] != TRUE)
  {
    echo('<table class="table ' . $stripe_class . '  table-condensed">');
    echo('  <thead>');

    echo '<tr class="strong">';
    echo '<th class="state-marker"></th>';
    if ($vars['page'] != "device" && $vars['popup'] != TRUE)
    {
      echo('      <th style="width: 250px;">Device</th>');
    }
    echo '<th>Toner</th>';
    if (!isset($vars['supply']))
    {
      echo '<th>Type</th>';
    }
    echo '<th></th>';
    echo '<th>Level</th>';
    echo '<th>Remaining</th>';
    echo '</tr>';

    echo '</thead>';
  }

  foreach($supplies as $supply)
  {
    print_printersupplies_row($supply, $vars);
  }

  echo("</table>");

  echo generate_box_close();

  echo $pagination_html;
}

function print_printersupplies_row($supply, $vars)
{
  echo generate_printersupplies_row($supply, $vars);
}

function generate_printersupplies_row($supply, $vars)
{
  $graph_type = "printersupply_usage";

  $table_cols = 5;

  $total = $supply['supply_capacity'];
  $perc = $supply['supply_value'];

  $graph_array['type'] = $graph_type;
  $graph_array['id'] = $supply['supply_id'];
  $graph_array['from'] = $GLOBALS['config']['time']['day'];
  $graph_array['to'] = $GLOBALS['config']['time']['now'];
  $graph_array['height'] = "20";
  $graph_array['width'] = "80";

  if ($supply['supply_colour'] != '')
  {
    $background = toner_to_colour($supply['supply_colour'], $perc);
  } else {
    $background = toner_to_colour($supply['supply_descr'], $perc);
  }

  /// FIXME - popup for printersupply entity.

  $output .= '<tr class="' . $supply['html_row_class'] . '">';
  $output .= '<td class="state-marker"></td>';
  if ($vars['popup'] == TRUE )
  {
    $output .= '<td style="width: 40px; text-align: center;"><i class="'.$GLOBALS['config']['entities']['printersupply']['icon'].'"></i></td>';
  } else {
    //$output .= '<td style="width: 1px;"></td>';
  }

  if ($vars['page'] != "device" && $vars['popup'] != TRUE)
  {
    $output .= '<td class="entity">' . generate_device_link($supply) . '</td>';
    $table_cols++;
  }
  $output .=  '<td class="entity">' . generate_entity_link('printersupply', $supply) . '</td>';

  if (!isset($vars['supply']))
  {
    $output .=  '<td>' . nicecase($supply['supply_type']) . '</td>';
  }

  $output .=  '<td style="width: 70px;">' . generate_graph_popup($graph_array) . '</td>';
  $output .=  '<td style="width: 200px;"><a href="' . $link . '">' . print_percentage_bar(400, 20, $perc, $perc . '%', 'ffffff', $background['right'], NULL, "ffffff", $background['left']) . '</a></td>';
  $output .=  '<td style="width: 50px; text-align: right;"><span class="label">' . $perc . '%</span></td>';
  $output .=  '</tr>';

  if ($vars['view'] == "graphs")
  {
    $output .= '<tr class="' . $supply['html_row_class'] . '">';
    $output .= '<td class="state-marker"></td>';
    $output .=  '<td colspan='.$table_cols.'>';

    unset($graph_array['height'], $graph_array['width'], $graph_array['legend']);
    $graph_array['to'] = $config['time']['now'];
    $graph_array['id'] = $supply['supply_id'];
    $graph_array['type'] = $graph_type;

    $output .= generate_graph_row($graph_array, TRUE);

    $output .= "</td></tr>";
  } # endif graphs

  return $output;
}

// EOF
