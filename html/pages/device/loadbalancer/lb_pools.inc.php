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
    $graph_type = "device_lb-pool-member_bits";
  } else {
    $graph_type = "device_lb-pool-member_".$vars['graph'];
  }

  $menu_options = array('basic' => 'Basic',
                        );

  if (!$vars['view']) { $vars['view'] = "basic"; }

  $navbar['brand'] = "Pools";
  $navbar['class'] = "navbar-narrow";

  foreach ($menu_options as $option => $text)
  {
    if ($vars['view'] == $option) { $navbar['options'][$option]['class'] = "active"; }
    $navbar['options'][$option]['text'] = $text;
    $navbar['options'][$option]['url'] = generate_url($vars, array('view'=>$option, 'graph' => NULL));
  }

  $graph_types = array("bits"   => "Bits",
                       "conns"  => "Connections",
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
  echo '      <th width=100>&nbsp;</th>';
  echo '      <th width=100>Method</th>';
  echo '      <th width=200>Members</th>';
  echo '      <th width=300>Used</th>';
  echo '      <th width=130>Status</th>';
  echo '   </tr>';
  echo '  </thead>';

  if(isset($vars['pool']))
  {
    $pool_db = dbFetchRows("SELECT * FROM `lb_pools` WHERE `device_id` = ? AND `pool_id` = ? ORDER BY `pool_name`", array($device['device_id'], $vars['pool']));
  } else {
    $pool_db = dbFetchRows("SELECT * FROM `lb_pools` WHERE `device_id` = ? ORDER BY `pool_name`", array($device['device_id']));
  }

  foreach ($pool_db as $pool)
  {

    if ($pool['active_members'] == 0)
    {
      // No members up
      $pool_class = "label label-error";
      $pool_state = "red";
      $row_class  = "error";
    } else
    {
      // Some members up
      $pool_class = "label label-success";
      $pool_state = "green";
      $row_class  = "";
    }

    echo '<tr class="' . $row_class . '">';

    echo '<td class="state-marker">';
    echo('<td class="entity"><a href="'.generate_url($vars, array('pool' => $pool['pool_id'], 'view' => NULL, 'graph' => NULL)).'">' . $pool['pool_name'] . '</a></td>');

    echo('<td><span class="label label-primary">'.$pool['pool_lb'].'</span></td>');

    $members = array();
    $pool_members_db = dbFetchRows("SELECT * FROM `lb_pool_members` WHERE `device_id` = ? AND `pool_name` = ?", array($device['device_id'], $pool['pool_name']));
    foreach ($pool_members_db as $member)
    {
      if (strpos($member['member_ip'], ":") !== FALSE)
      {
        $ip =  Net_IPv6::compress($member['member_ip']) . '.';
      } else {
        $ip = $member['member_ip'] . ':';
      }
      $members[] = $ip.$member['member_port'];
    }
    $mem = '<i><small>'.implode("<br />",$members).'</small></i>';
    echo('<td>'.$mem.'</td>');
    
    $virt_names = array();
    $virts_db = dbFetchRows("SELECT `virt_name` FROM `lb_virtuals` WHERE `device_id` = ? AND `virt_pool` = ?", array($device['device_id'], $pool['pool_name']));
    if (count($virts_db))
    {
      foreach ($virts_db as $virt)
      {
        $virt_names[] = $virt['virt_name']; // use generate_url here to link to the virtuals page
      }
      $virts = '<br />&nbsp;&nbsp;<i><small>'.implode("<br />&nbsp;&nbsp;",$virt_names).'</small></i>';
    } else
    {
      $virts = '<br />&nbsp;&nbsp;<i><small>None</small></i>';
    }
    echo('<td><span class="label label-success">Virtuals</span>'.$virts);
    // If we can record which pools are used by which rules, do those here
    //echo('<br /><span class="label label-primary">Rules</span>');
    echo('</td>');

    $members_state = $pool['active_members'] . '/' . $pool['num_members'];
    echo '<td><span class="'.$pool_class.'">'.$pool_state.'</span><br />Up: '.$members_state.'</td>';
    echo '</tr>';

    // If we are a single pool then print the member details
    if (isset($vars['pool']))
    {
      if (count($pool_members_db))
      {
        echo('<tr><td colspan="6">'.PHP_EOL);
        echo('    <table class="table table-striped table-condensed box box-solid">'.PHP_EOL);
        echo('      <thead>');
        echo('<tr><th class="state-marker"></th>');
        echo('<th>Pool Member</th>');
        echo('<th>&nbsp;</th>');
        echo('<th>Address</th>');
        echo('<th>Status</th>');
        echo('<th>Enabled</th>');
        echo('<th>Connections</th>');
        echo('</tr>  </thead>'.PHP_EOL);

        foreach ($pool_members_db as $member)
        {
          switch($member['member_state'])
          {
            case 'none': // Error
              $member_class = "label label-error";
              $member_state = "none";
              $row_class  = "unknown";
              break;
            case 'green': // Available in some capacity
              $member_class = "label label-success";
              $member_state = "green";
              $row_class  = "";
              break;
            case 'yellow': // Not currently available
              $member_class = "label label-warn";
              $member_state = "yellow";
              $row_class  = "warn";
              break;
            case 'red': // Not available
              $member_class = "label label-error";
              $member_state = "red";
              $row_class  = "error";
              break;
            case 'blue': // Availability unknown
              $member_class = "label label-primary";
              $member_state = "blue";
              $row_class  = "unknown";
              break;
            case 'gray': // Unlicensed
              $member_class = "label label-disabled";
              $member_state = "gray";
              $row_class  = "unknown";
              break;
          }

          switch($member['member_enabled'])
          {
            case 'none':
              $member_enabled = "None";
              $enable_class = "label label-error";
              break;
            case 'enabled':
              $member_enabled = "Enabled";
              $enable_class = "label label-success";
              break;
            case 'disabled':
              $member_enabled = "Disabled";
              $enable_class = "label label-warn";
              break;
            case 'disabledbyparent':
              $member_enabled = "Node Disabled";
              $enable_class = "label label-warn";
              break;
          }

          if (strpos($member['member_ip'], ":") !== FALSE)
          {
            $addr = Net_IPv6::compress($member['member_ip']) . '.';
          } else {
            $addr = $member['member_ip'] . ':';
          }

          echo('        <tr class="'.$row_class.'">');
          echo('<td class="state-marker">');
          echo('<td class="entity-name">'.$member['member_name'].'</td>');

    echo('<td style="width: 90px">');
    foreach ($graph_types as $graph_type => $graph_text)
    {
      $graph_type = "lb-pool-member_" . $graph_type;
      $graph_array['to']     = $config['time']['now'];
      $graph_array['from']   = $config['time']['day'];
      $graph_array['id']     = $member['member_id'];
      $graph_array['type']   = $graph_type;
      $graph_array['legend'] = "no";
      $graph_array['width'] = 80; $graph_array['height'] = 20; $graph_array['bg'] = 'ffffff00';

      $link_array = $graph_array;
      $link_array['page'] = "graphs";
      unset($link_array['height'], $link_array['width'], $link_array['legend']);
      $link = generate_url($link_array);

      $minigraph = generate_graph_tag($graph_array);
      $overlib_content = generate_overlib_content($graph_array, $device['hostname'] . " - " . $member['member_name'] . " - " . $graph_text);
      echo(overlib_link($link, $minigraph, $overlib_content));
      unset($graph_array);
    }
    echo('</td>');

          echo('<td>' . $addr . $member['member_port'] . '</td>');
          echo('<td><span class="'.$member_class.'">' . $member_state . '</span></td>');
          echo('<td><span class="'.$enable_class.'">' . $member_enabled . '</span></td>');
          echo('<td>' . $member['member_conns'] . '</td>');
          echo('</tr>'.PHP_EOL);
        }
        echo('    </table>');
        echo '</td></tr>';
      }
    }


    unset($pool, $virt_type, $virt_class, $virt_state, $rules);
  }

  unset($virt_db, $pool_db);
  echo('</table>');

  echo generate_box_close();

// EOF
