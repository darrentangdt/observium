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


  if (!$vars['graph'])
  {
    $graph_type = "device_lb-snatpool_conns";
  } else {
    $graph_type = "device_lb-snatpool_".$vars['graph'];
  }

  $menu_options = array('basic' => 'Basic',
                        );

  if (!$vars['view']) { $vars['view'] = "basic"; }

  $navbar['brand'] = "SNATPools";
  $navbar['class'] = "navbar-narrow";

  foreach ($menu_options as $option => $text)
  {
    if ($vars['view'] == $option) { $navbar['options'][$option]['class'] = "active"; }
    $navbar['options'][$option]['text'] = $text;
    $navbar['options'][$option]['url'] = generate_url($vars, array('view'=>$option, 'graph' => NULL));
  }

  $graph_types = array("bits"   => "Bits",
                       "conns"  => "Connections",
                       "cur_conns" => "Current Connections",
                      );

  foreach ($graph_types as $type => $descr)
  {
    if ($vars['graph'] == $type) { $navbar['options_right'][$type]['class'] = "active"; }
    $navbar['options_right'][$type]['text'] = $descr;
    $navbar['options_right'][$type]['url'] = generate_url($vars,array('view' => 'graphs', 'graph'=>$type));
  }

  print_navbar($navbar); unset($navbar);

  if ($vars['view'] == "graphs" || $vars['view'] == "services") { $table_class="table-striped-two"; } else { $table_class="table-striped"; }

  echo generate_box_open();

  echo '<table class="table table-striped table-condensed" style="margin-top: 10px;">';
  echo '  <thead>';
  echo '    <tr>';
  echo '      <th class="state-marker"></th>';
  echo '      <th>SNAT Pool</th>';
  echo '      <th>&nbsp;</th>';
  echo '      <th>Connections</th>';
  echo '   </tr>';
  echo '  </thead>';

  if(isset($vars['snatpool']))
  {
    $snatpool_db = dbFetchRows("SELECT * FROM `lb_snatpools` WHERE `device_id` = ? AND `snatpool_id` = ? ORDER BY `snatpool_name`", array($device['device_id'], $vars['snatpool']));
  } else {
    $snatpool_db = dbFetchRows("SELECT * FROM `lb_snatpools` WHERE `device_id` = ? ORDER BY `snatpool_name`", array($device['device_id']));
  }

  foreach ($snatpool_db as $snatpool)
  {

    $row_class = "";
    echo '<tr class="' . $row_class . '">';
    echo '<td class="state-marker">';

    echo('<td class="entity"><a href="'.generate_url($vars, array('snatpool' => $snatpool['snatpool_id'], 'view' => NULL, 'graph' => NULL)).'">' . $snatpool['snatpool_name'] . '</a></td>');

    echo('<td style="width: 90px">');
    foreach ($graph_types as $graph_type => $graph_text)
    {
      $graph_type = "lb-snatpool_" . $graph_type;
      $graph_array['to']     = $config['time']['now'];
      $graph_array['from']   = $config['time']['day'];
      $graph_array['id']     = $snatpool['snatpool_id'];
      $graph_array['type']   = $graph_type;
      $graph_array['legend'] = "no";
      $graph_array['width'] = 80; $graph_array['height'] = 20; $graph_array['bg'] = 'ffffff00';

      $link_array = $graph_array;
      $link_array['page'] = "graphs";
      unset($link_array['height'], $link_array['width'], $link_array['legend']);
      $link = generate_url($link_array);

      $minigraph = generate_graph_tag($graph_array);
      $overlib_content = generate_overlib_content($graph_array, $device['hostname'] . " - " . $snatpool['snatpool_name'] . " - " . $graph_text);
      echo(overlib_link($link, $minigraph, $overlib_content));
      unset($graph_array);
    }
    echo('</td>');

    echo '<td>'.$snatpool['snatpool_conns'].'</td>';
    echo '</tr>';

  }

  if (isset($vars['snatpool']))
  {
    foreach ($graph_types as $graph_type => $graph_text)
    {
      echo('<tr class="entity">');
      echo '<td colspan="4">';
      $graph_type = "lb-snatpool_" . $graph_type;
      $graph_array['to']     = $config['time']['now'];
      $graph_array['id']     = $snatpool['snatpool_id'];
      $graph_array['type']   = $graph_type;

      echo('<h3>'.$graph_text.'</h3>');

      print_graph_row($graph_array);

      echo("
      </td>
      </tr>");
    }
  }

  unset($snatpool_db);
  echo('</table>');

  echo generate_box_close();

// EOF
