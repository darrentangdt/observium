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
    $graph_type = "device_lb-virtual_bits";
  } else {
    $graph_type = "device_lb-virtual_".$vars['graph'];
  }

  $menu_options = array('basic' => 'Basic',
                        );

  if (!$vars['view']) { $vars['view'] = "basic"; }

  $navbar['brand'] = "Virtuals";
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

/*  echo '<table class="table table-striped table-condensed" style="margin-top: 10px;">';
  echo '  <thead>';
  echo '    <tr>';
  echo '      <th class="state-marker"></th>';
  echo '      <th>Virtual</th>';
  echo '      <th width=100>&nbsp;</th>';
  echo '      <th width=200>Address</th>';
  echo '      <th width=300>Type</th>';
  echo '      <th width=130>Status</th>';
  echo '   </tr>';
  echo '  </thead>'; */

  echo print_f5_lb_virtual_table_header($vars);

  switch($vars['sort'])
  {
    case "virt_name":
    case "virt_ip":
    case "virt_state":
    case "virt_type":
      $sort = 'ORDER BY `'.$vars['sort'].'`' . ($vars['sort_order'] == 'desc' ? ' DESC' : '' );
      break;
    default:
      $sort = "ORDER BY `virt_name`";
  }

  if(isset($vars['virt']))
  {
    $virt_db = dbFetchRows("SELECT * FROM `lb_virtuals` WHERE `device_id` = ? AND `virt_id` = ? ".$sort, array($device['device_id'], $vars['virt']));
  } else {
    $virt_db = dbFetchRows("SELECT * FROM `lb_virtuals` WHERE `device_id` = ? ".$sort, array($device['device_id']));
  }
  $pool_db = dbFetchRows("SELECT * FROM `lb_pools` WHERE `device_id` = ?", array($device['device_id']));

  foreach ($pool_db as $pool_id => $p)
  {
    $pools[$p['pool_name']] = $p;
  }

  foreach ($virt_db as $virt)
  {

   switch($virt['virt_state'])
    {
          case 'none': // Error
            $virt_class = "label label-error";
            $virt_state = "none";
            $row_class  = "unknown";
            break;
          case 'green': // Available in some capacity
            $virt_class = "label label-success";
            $virt_state = "green";
            $row_class  = "";
            break;
          case 'yellow': // Not currently available
            $virt_class = "label label-warn";
            $virt_state = "yellow";
            $row_class  = "warn";
            break;
          case 'red': // Not available
            $virt_class = "label label-error";
            $virt_state = "red";
            $row_class  = "error";
            break;
          case 'blue': // Availability unknown
            $virt_class = "label label-primary";
            $virt_state = "blue";
            $row_class  = "unknown";
            break;
          case 'gray': // Unlicensed
            $virt_class = "label label-disabled";
            $virt_state = "gray";
            $row_class  = "unknown";
            break;
    }

    if ($virt['virt_pool'])
    {
      $pool = $virt['virt_pool'];
      $pool_members_db = dbFetchRows("SELECT * FROM `lb_pool_members` WHERE `device_id` = ? AND `pool_name` = ?", array($device['device_id'], $pool));
      $pool_members = '<br>pool: '.$pools[$pool]['active_members'] . '/' . $pools[$pool]['num_members'];
      $pool_lb = $pools[$pool]['pool_lb'];
    } else {
      $pool = "none";
      $pool_members = "";
      $pool_lb = "";
    }

    switch($virt['virt_type'])
    {
      case 'poolbased':
        $virt['type'] = "pool";
        $virt['pool_lb'] = $pool_lb;
        $virt['pool'] = $pool;
        $virt['type_class'] = 'success';
        break;
      case 'ipforward':
        $virt['type'] = "ip-forward";
        $virt['type_class'] = 'warn';
        break;
      case 'l2forward':
        $virt['type'] = "l2-forward";
        $virt['type_class'] = 'delay';
        break;
      case 'reject':
        $virt['type'] = "reject";
        $virt['type_class'] = 'error';
        break;
      case 'fastl4':
        $virt['type'] = "fastl4";
        $virt['pool'] = $pool;
        $virt['type_class'] = 'suppressed';
        break;
      case 'fasthttp':
        $virt['type'] = "fasthttp";
        $virt['pool'] = $pool;
        $virt['type_class'] = 'delay';
        break;
      case 'stateless':
        $virt['type'] = "stateless";
        $virt['pool'] = $pool;
        break;
      case 'dhcp-relay':
        $virt['type'] = "dhcp-relay";
        $virt['pool'] = $pool;
        break;
      case internal:
        $virt['type'] = "internal";
        break;
    }

    $virt_type = '<span class="label label-'.$virt['type_class'].'">'.$virt['type'].'</span>';
    $virt_type .= (isset($virt['pool_lb']) ? '<span class="label label-primary">'.$virt['pool_lb'].'</span>' : '');
    $virt_type .= (isset($virt['pool']) ? '<br /><i><small>'.$virt['pool'].'</small></i>' : '');

    if ($virt['virt_rules'])
    {
      $virt_type = $virt_type . '<br /><span class="label">rules</span>';
      $rules = explode(",",$virt['virt_rules']);
      foreach ($rules as $rule)
      {
        $virt_type = $virt_type . '<br />&nbsp;&nbsp;<i><small>' . $rule .'</small></i>';
      }
    }

    echo '<tr class="' . $row_class . '">';
    echo '<td class="state-marker">';

    echo('<td class="entity"><a href="'.generate_url($vars, array('virt' => $virt['virt_id'], 'view' => NULL, 'graph' => NULL)).'">' . $virt['virt_name'] . '</a></td>');

    echo('<td style="width: 90px">');
    foreach ($graph_types as $graph_type => $graph_text)
    {
      $graph_type = "lb-virtual_" . $graph_type;
      $graph_array['to']     = $config['time']['now'];
      $graph_array['from']   = $config['time']['day'];
      $graph_array['id']     = $virt['virt_id'];
      $graph_array['type']   = $graph_type;
      $graph_array['legend'] = "no";
      $graph_array['width'] = 80; $graph_array['height'] = 20; $graph_array['bg'] = 'ffffff00';

      $link_array = $graph_array;
      $link_array['page'] = "graphs";
      unset($link_array['height'], $link_array['width'], $link_array['legend']);
      $link = generate_url($link_array);

      $minigraph = generate_graph_tag($graph_array);
      $overlib_content = generate_overlib_content($graph_array, $device['hostname'] . " - " . $virt['virt_name'] . " - " . $graph_text);
      echo(overlib_link($link, $minigraph, $overlib_content));
      unset($graph_array);
    }
    echo('</td>');

    if (strpos($virt['virt_ip'], ":") !== FALSE)
    {
      echo('<td>' . Net_IPv6::compress($virt['virt_ip']).".".$virt['virt_port'].'<br />'.$virt['virt_mask'].'<br />');
    } else {
      $long = ip2long($virt['virt_mask']);
      $base = ip2long('255.255.255.255');
      $cidr = 32-log(($long ^ $base)+1,2);
      echo('<td>' . $virt['virt_ip'].":".$virt['virt_port'].'/'.$cidr.'<br />');
    }

    switch ($virt['virt_proto'])
    {
      case 'ip':
        $virt['proto_class'] = 'success';
        break;
      case 'icmp':
        $virt['proto_class'] = 'delayed';
        break;
      case 'tcp':
        $virt['proto_class'] = 'primary';
        break;
      case 'udp':
        $virt['proto_class'] = 'suppressed';
        break;
      case 'ipv6-icmp':
        $virt['proto_class'] = 'error';
    }

    echo '<span class="label label-'.$virt['proto_class'].'">'.$virt['virt_proto'].'</span></td>' ;


    echo '<td>'.$virt_type.'</td>';
    echo '<td><span class="'.$virt_class.'">'.$virt_state.'</span>'.$pool_members.'</td>';
    echo '</tr>';

    // If we have a pool then print the member details
  if (isset($vars['virt']))
  {
    if (count($pool_members_db))
    {
      echo('<tr><td colspan="6">'.PHP_EOL);
      echo('    <table class="table table-striped table-condensed box box-solid">'.PHP_EOL);
      echo('      <thead>');
      echo('<tr><th class="state-marker"></th>');
      echo('<th>Pool Member</th>');
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
        echo('<td>' . $addr . $member['member_port'] . '</td>');
        echo('<td><span class="'.$member_class.'">' . $member_state . '</span></td>');
        echo('<td><span class="'.$enable_class.'">' . $member_enabled . '</span></td>');
        echo('<td>' . $member['member_conns'] . '</td>');
        echo('</tr>'.PHP_EOL);
      }
      echo('    </table>');
      echo '</td></tr>';
    }

    foreach ($graph_types as $graph_type => $graph_text)
    {
      echo('<tr class="entity">');
      echo '<td colspan="6">';
      $graph_type = "lb-virtual_" . $graph_type;
      $graph_array['to']     = $config['time']['now'];
      $graph_array['id']     = $virt['virt_id'];
      $graph_array['type']   = $graph_type;

      echo('<h3>'.$graph_text.'</h3>');

      print_graph_row($graph_array);

      echo("
      </td>
      </tr>");
    }
  }

    if (isset($vars['graph']) && !isset($vars['virt']))
    {
      echo('<tr class="entity" bgcolor="'.$bg_colour.'">');
      echo '<td colspan="6">';
      $graph_type = "lb-virtual_" . $vars['graph'];
      $graph_array           = array();
      $graph_array['to']     = $config['time']['now'];
      $graph_array['id']     = $virt['virt_id'];
      $graph_array['type']   = $graph_type;

      print_graph_row($graph_array);

      echo('
      </td>
      </tr>');
    }


    unset($pool, $virt_type, $virt_class, $virt_state, $rules);
  }

  unset($virt_db, $pool_db);
  echo('</table>');

  echo generate_box_close();

// EOF
