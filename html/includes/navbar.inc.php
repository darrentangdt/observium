<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage webui
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2017 Observium Limited
 *
 */

// FIXME - this could do with some performance improvements, i think. possible rearranging some tables and setting flags at poller time (nothing changes outside of then anyways)

// Time our menu filling.
$menu_start = utime();

?>

<header class="navbar navbar-fixed-top">
    <div class="navbar-inner">
      <div class="container">
        <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target="#main-nav">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="brand brand-observium" href="/" <?php if(isset($config['web']['logo'])) { echo 'style="background-image: url(../images/'.$config['web']['logo'].');"'; }  ?> >&nbsp;</a>
        <div class="nav-collapse" id="main-nav">
          <ul class="nav">
<?php

//////////// Build main "globe" menu
$navbar['observium'] = array('url' => generate_url(array('page' => 'overview')), 'icon' => $config['icon']['globe']);

$navbar['observium']['entries'][] = array('title' => 'Overview', 'url' => generate_url(array('page' => 'overview')), 'icon' => $config['icon']['overview']);
$navbar['observium']['entries'][] = array('divider' => TRUE);

// Show Groups
if (OBSERVIUM_EDITION != 'community')
{
 if ($_SESSION['userlevel'] >= 5)
 {
  $group_menu = array();
  $entity_group_menu = array();
  foreach (get_type_groups() as $group)
  {
    $group['member_count'] = dbFetchCell("SELECT COUNT(*) FROM `group_table` WHERE `group_id` = ?", array($group['group_id']));
    $entity_type           = $config['entities'][$group['entity_type']];

    $group_menu[] = array('url' => generate_url(array('page' => 'group', 'group_id' => $group['group_id'])), 'title' => escape_html($group['group_name']), 'icon' => $config['entities'][$group['entity_type']]['icon'], 'count' => $group['member_count']);

    $entity_group_menu[$group['entity_type']][] = array('url' => generate_url(array('page' => 'group', 'group_id' => $group['group_id'])), 'title' => escape_html($group['group_name']), 'icon' => $config['entities'][$group['entity_type']]['icon'], 'count' => $group['member_count']);
  }

  $navbar['observium']['entries'][] = array('title' => 'Groups', 'url' => generate_url(array('page' => 'groups')), 'icon' => $config['icon']['group'], 'count' => count($group_menu), 'entries' => $group_menu);
  $navbar['observium']['entries'][] = array('divider' => TRUE);
 }
}

$navbar['observium']['entries'][] = array('title' => 'Alerts', 'url' => generate_url(array('page' => 'alerts')), 'icon' => $config['icon']['alert']);
if ($_SESSION['userlevel'] >= 5)
{
  $counts['alert_checks'] = dbFetchCell("SELECT COUNT(*) FROM `alert_tests`");
  $navbar['observium']['entries'][] = array('title' => 'Alert Checks', 'url' => generate_url(array('page' => 'alert_checks')), 'count' => $counts['alert_checks'], 'icon' => $config['icon']['alert-rules']);
}

$navbar['observium']['entries'][] = array('title' => 'Alert Logs', 'url' => generate_url(array('page' => 'alert_log')), 'icon' => $config['icon']['alert-log']);

if ($_SESSION['userlevel'] >= 7 && OBSERVIUM_EDITION != 'community')
{
  $navbar['observium']['entries'][] = array('title' => 'Scheduled Maintenance', 'url' => generate_url(array('page' => 'alert_maintenance')), 'icon' => $config['icon']['scheduled-maintenance']);
}

$navbar['observium']['entries'][] = array('divider' => TRUE);

if (isset($config['enable_syslog']) && $config['enable_syslog'])
{
  $navbar['observium']['entries'][] = array('title' => 'Syslog', 'url' => generate_url(array('page' => 'syslog')), 'icon' => $config['icon']['syslog']);

  if (OBSERVIUM_EDITION != 'community')
  {
    $navbar['observium']['entries'][] = array('title' => 'Syslog Alerts', 'url' => generate_url(array('page' => 'syslog_alerts')), 'icon' => $config['icon']['syslog-alerts']);
    $navbar['observium']['entries'][] = array('title' => 'Syslog Rules',  'url' => generate_url(array('page' => 'syslog_rules')), 'icon' => $config['icon']['syslog-rules']);
    $navbar['observium']['entries'][] = array('divider' => TRUE);
  }
}

if (isset($config['enable_map']) && $config['enable_map'])
{ // FIXME link is wrong. Is this a supported feature?
  $navbar['observium']['entries'][] = array('title' => 'Network Map', 'url' => generate_url(array('page' => 'map')), 'icon' => $config['icon']['netmap']);
}

$navbar['observium']['entries'][] = array('title' => 'Event Log', 'url' => generate_url(array('page' => 'eventlog')), 'icon' => $config['icon']['eventlog']);


$navbar['observium']['entries'][] = array('divider' => TRUE);

if ($_SESSION['userlevel'] >= 7)
{
  // Print Contacts
  $counts['contacts'] = dbFetchCell("SELECT COUNT(*) FROM `alert_contacts`");

  $navbar['observium']['entries'][] = array('title' => 'Contacts', 'url' => generate_url(array('page' => 'contacts')), 'count' => $counts['contacts'], 'icon' => $config['icon']['contacts']);
  $navbar['observium']['entries'][] = array('divider' => TRUE);
}

if (OBSERVIUM_EDITION != 'community' && $_SESSION['userlevel'] >= 5)
{
  // Custom OIDs

  $oids = dbFetchRows("SELECT * FROM `oids` ORDER BY `oid_descr`");

  $oid_count = count($oids);

  foreach ($oids AS $oid)
  {
      $oids_menu[] = array('title' => $oid['oid_descr'], 'url' => generate_url(array('page' => 'customoid', 'oid_id' => $oid['oid_id'])), 'icon' => $config['icon']['customoid']);
  }

    $navbar['observium']['entries'][] = array('title' => 'Custom OIDs', 'url' => generate_url(array('page' => 'customoids')), 'count' => $oid_count, 'icon' => $config['icon']['customoid'], 'entries' => $oids_menu);
    $navbar['observium']['entries'][] = array('divider' => TRUE);

}

$navbar['observium']['entries'][] = array('title' => 'Hardware Inventory', 'url' => generate_url(array('page' => 'inventory')), 'icon' => $config['icon']['inventory']);

if ($cache['packages']['count'])
{
  $navbar['observium']['entries'][] = array('title' => 'Software Packages', 'url' => generate_url(array('page' => 'packages')), 'icon' => $config['icon']['packages']);
}

$navbar['observium']['entries'][] = array('divider' => TRUE);

// Build search submenu
$search_sections = array('ipv4' => 'IPv4 Address', 'ipv6' => 'IPv6 Address', 'mac' => 'MAC Address', 'arp' => 'ARP/NDP Tables', 'fdb' => 'FDB Tables');
if ($cache['wifi_sessions']['count'])
{
  $search_sections['dot1x'] = '.1x Sessions';
}

foreach ($search_sections as $search_page => $search_name)
{
  $search_menu[] = array('title' => $search_name, 'url' => generate_url(array('page' => 'search', 'search' => $search_page)), 'icon' => $config['icon']['search']);
}

$navbar['observium']['entries'][] = array('title' => 'Search', 'url' => generate_url(array('page' => 'search')), 'icon' => $config['icon']['search'], 'entries' => $search_menu);

//////////// Build devices menu
$navbar['devices'] = array('url' => generate_url(array('page' => 'devices')), 'icon' => $config['icon']['devices'], 'title' => 'Devices');

$navbar['devices']['entries'][] = array('title' => 'All Devices', 'count' => $cache['devices']['stat']['count'], 'url' => generate_url(array('page' => 'devices')), 'icon' => $config['icon']['devices']);

if(count($entity_group_menu['device']))
{
  $navbar['devices']['entries'][] = array('divider' => TRUE);
  $navbar['devices']['entries'][] = array('title' => 'Groups', 'url' => generate_url(array('page' => 'groups', 'entity_type' => 'device')), 'icon' => $config['icon']['group'], 'count' => count($entity_group_menu['device']), 'entries' => $entity_group_menu['device']);
}

$navbar['devices']['entries'][] = array('divider' => TRUE);

// Build location submenu
if ($config['show_locations'])
{
  switch ($config['location']['menu']['type'])
  {
    case 'geocoded':
      $navbar['devices']['entries'][] = array('locations' => TRUE); // Pretty complicated recursive function, workaround not having it converted to returning an array
      break;
    case 'nested':
      $locations = array('title' => 'Locations', 'icon' => $config['icon']['location']); // Init empty array

      foreach (get_locations() as $location)
      {
        // If location is empty, substitute by OBS_VAR_UNSET as empty location parameter would be ignored
        $name = ($location === '' ? OBS_VAR_UNSET : escape_html($location));

        $location_split = explode($config['location']['menu']['nested_split_char'], $name, $config['location']['menu']['nested_max_depth']);

        // Turn array around if nested reversed option is active
        if ($config['location']['menu']['nested_reversed']) { $location_split = array_reverse($location_split); }

        $ref = &$locations; // Start from top menu array

        for ($i = 0; $i < count($location_split); $i++)
        {
          $location_part = trim($location_split[$i]);

          $ref = &$ref['entries'][$location_part];

          if (!is_array($ref))
          {
            // Get partial location string up to the point where we are
            $location_slice = array_slice($location_split, 0, $i+1);
            // Turn array around again (to normal presentation) if nested reversed option is active
            if ($config['location']['menu']['nested_reversed']) { $location_slice = array_reverse($location_slice); }
            // Generate URL based on slice
            $location_url = generate_url(array('page' => 'devices',
              'location_text' => str_replace(',', '%1F', trim(implode($config['location']['menu']['nested_split_char'], $location_slice)))));
            // , replaced by %1F (Observium Special), otherwise the value turns into an array (see get_vars() for parsing)

            $ref = array('icon' => $config['icon']['location'], 'title' => $location_part, 'url' => $location_url);
          }
        }
      }

      $navbar['devices']['entries'][] = $locations;
      break;
    case 'plain':
    default:
      foreach (get_locations() as $location)
      {
        // If location is empty, substitute by OBS_VAR_UNSET as empty location parameter would be ignored
        $name = ($location === '' ? OBS_VAR_UNSET : escape_html($location));

        // No nested menu, just list all locations one after another
        $location_menu[] = array('url' => generate_location_url($location), 'icon' => $config['icon']['location'], 'title' => $name);
      }

      $navbar['devices']['entries'][] = array('title' => 'Locations', 'url' => generate_url(array('page' => 'locations')), 'icon' => $config['icon']['locations'], 'entries' => $location_menu);
      break;
  }

  $navbar['devices']['entries'][] = array('divider' => TRUE);
}

// Build list per device type
foreach ($config['device_types'] as $devtype)
{
  if (in_array($devtype['type'], array_keys($cache['device_types'])))
  {
    $navbar['devices']['entries'][] = array('title' => $devtype['text'], 'icon' => $devtype['icon'], 'count' => $cache['device_types'][$devtype['type']], 'url' => generate_url(array('page' => 'devices', 'type' => $devtype['type'])));
  }
}

if ($cache['devices']['stat']['down']+$cache['devices']['stat']['ignored']+$cache['devices']['stat']['disabled'])
{
  $navbar['devices']['entries'][] = array('divider' => TRUE);
  if ($cache['devices']['stat']['down'])
  {
    $navbar['devices']['entries'][] = array('url' => generate_url(array('page' => 'devices', 'status' => '0')), 'icon' => $config['icon']['exclamation'], 'title' => 'Down', count => $cache['devices']['stat']['down']);
  }
  if ($cache['devices']['stat']['ignored'])
  {
    $navbar['devices']['entries'][] = array('url' => generate_url(array('page' => 'devices', 'ignore' => '1')), 'icon' => $config['icon']['ignore'], 'title' => 'Ignored', count => $cache['devices']['stat']['ignored']);
  }
  if ($cache['devices']['stat']['disabled'])
  {
    $navbar['devices']['entries'][] = array('url' => generate_url(array('page' => 'devices', 'disabled' => '1')), 'icon' => $config['icon']['shutdown'], 'title' => 'Disabled', count => $cache['devices']['stat']['disabled']);
  }
}

if ($_SESSION['userlevel'] >= 10)
{
  $navbar['devices']['entries'][] = array('divider' => TRUE);
  $navbar['devices']['entries'][] = array('url' => generate_url(array('page' => 'addhost')), 'icon' => $config['icon']['plus'], 'title' => 'Add Device');
  $navbar['devices']['entries'][] = array('url' => generate_url(array('page' => 'delhost')), 'icon' => $config['icon']['minus'], 'title' => 'Delete Device');
}

if ($cache['vm']['count'])
{
  $navbar['devices']['entries'][] = array('divider' => TRUE);
  $navbar['devices']['entries'][] = array('title' => 'Virtual Machines', 'count' => $cache['vm']['count'], 'url' => generate_url(array('page' => 'vms')), 'icon' => $config['icon']['virtual-machine']);
}

//////////// Build ports menu
$navbar['ports'] = array('url' => generate_url(array('page' => 'ports')), 'icon' => $config['entities']['port']['icon'], 'title' => 'Ports');

$navbar['ports']['entries'][] = array('title' => 'All Ports', 'count' => $cache['ports']['stat']['count'], 'url' => generate_url(array('page' => 'ports')), 'icon' => $config['entities']['port']['icon']);
$navbar['ports']['entries'][] = array('divider' => TRUE);

if(count($entity_group_menu['port']))
{
  $navbar['ports']['entries'][] = array('title' => 'Groups', 'url' => generate_url(array('page' => 'groups', 'entity_type' => 'port')), 'icon' => $config['icon']['group'], 'count' => count($entity_group_menu['port']), 'entries' => $entity_group_menu['port']);
  $navbar['ports']['entries'][] = array('divider' => TRUE);
}

if ($cache['p2pradios']['count'])
{
  $navbar['ports']['entries'][] = array('title' => 'P2P Radios', 'count' => $cache['p2pradios']['count'], 'url' => generate_url(array('page' => 'p2pradios')), 'icon' => $config['entities']['p2pradio']['icon']);
  $navbar['ports']['entries'][] = array('divider' => TRUE);
}

if ($config['enable_billing'])
{
  $navbar['ports']['entries'][] = array('title' => 'Traffic Accounting', 'url' => generate_url(array('page' => 'bills')), 'icon' => $config['icon']['billing']);
  $ifbreak = 1;
}

if ($cache['neighbours']['count'])
{
  $navbar['ports']['entries'][] = array('title' => 'Neighbours', 'url' => generate_url(array('page' => 'neighbours')), 'icon' => $config['icon']['neighbours'], 'count' => $cache['neighbours']['count']);
  $ifbreak = 1;
}

if ($config['enable_pseudowires'] && $cache['pseudowires']['count'])
{
  $navbar['ports']['entries'][] = array('title' => 'Pseudowires', 'count' => $cache['pseudowires']['count'], 'url' => generate_url(array('page' => 'pseudowires')), 'icon' => $config['icon']['pseudowire']);
  $ifbreak = 1;
}

if ($cache['mac_accounting']['count'])
{
  $navbar['ports']['entries'][] = array('title' => 'MAC Accounting', 'count' => $cache['mac_accounting']['count'], 'url' => generate_url(array('page' => 'ports', 'mac_accounting' => 'yes')),'icon' => $config['icon']['port']);
  $ifbreak = 1;
}

if ($cache['cbqos']['count'])
{
  $navbar['ports']['entries'][] = array('title' => 'CBQoS', 'count' => $cache['cbqos']['count'], 'url' => generate_url(array('page' => 'ports', 'cbqos' => 'yes')), 'icon' => $config['icon']['cbqos']);
  $ifbreak = 1;
}

if ($cache['sla']['count'])
{
  $navbar['ports']['entries'][] = array('title' => 'IP SLA', 'count' => $cache['sla']['count'], 'url' => generate_url(array('page' => 'slas')), 'icon' => $config['icon']['sla']);
  $ifbreak = 1;
}

if ($ifbreak)
{
  $navbar['ports']['entries'][] = array('divider' => TRUE);
  $ifbreak = 0;
}

if ($_SESSION['userlevel'] >= '5')
{
  // FIXME new icons
  if ($config['int_customers']) { $navbar['ports']['entries'][] = array('url' => generate_url(array('page' => 'customers')), 'icon' => $config['icon']['port-customer'], 'title' => 'Customers'); $ifbreak = 1; }
  if ($config['int_l2tp'])      { $navbar['ports']['entries'][] = array('url' => generate_url(array('page' => 'iftype', 'type' => 'l2tp')), 'icon' => $config['icon']['users'], 'title' => 'L2TP'); $ifbreak = 1; }
  if ($config['int_transit'])   { $navbar['ports']['entries'][] = array('url' => generate_url(array('page' => 'iftype', 'type' => 'transit')), 'icon' => $config['icon']['port-transit'], 'title' => 'Transit');  $ifbreak = 1; }
  if ($config['int_peering'])   { $navbar['ports']['entries'][] = array('url' => generate_url(array('page' => 'iftype', 'type' => 'peering')), 'icon' => $config['icon']['port-peering'], 'title' => 'Peering'); $ifbreak = 1; }
  if ($config['int_peering'] && $config['int_transit']) { $navbar['ports']['entries'][] = array('url' => generate_url(array('page' => 'iftype', 'type' => 'peering,transit')), 'icon' => $config['icon']['port-peering-transit'], 'title' => 'Peering & Transit'); $ifbreak = 1; }
  if ($config['int_core']) { $navbar['ports']['entries'][] = array('url' => generate_url(array('page' => 'iftype', 'type' => 'core')), 'icon' => $config['icon']['port-core'], 'title' => 'Core'); $ifbreak = 1; }

  // Custom interface groups can be set - see Interface Description Parsing
  foreach ($config['int_groups'] as $int_type)
  {
    $navbar['ports']['entries'][] = array('url' => generate_url(array('page' => 'iftype', 'type' => $int_type)), 'icon' => $config['icon']['port'], 'title' => str_replace(',', ' & ', $int_type)); $ifbreak = 1;
  }
}

if ($ifbreak) { $navbar['ports']['entries'][] = array('divider' => TRUE); }

$navbar['ports']['entries']['statuses'] = array('title' => 'Status Breakdown', 'url' => generate_url(array('page' => '#')), 'icon' => $config['entities']['port']['icon'], 'entries' => array());

if ($cache['ports']['stat']['errored'])
{
  $navbar['ports']['entries']['statuses']['entries'][] = array('url' => generate_url(array('page' => 'ports', 'errors' => 'yes')), 'icon' => $config['icon']['flag'], 'title' => 'Errored', 'count' => $cache['ports']['stat']['errored']);
}

if ($cache['ports']['stat']['down'])
{
  $navbar['ports']['entries']['statuses']['entries'][] = array('url' => generate_url(array('page' => 'ports', 'state' => 'down')), 'icon' => $config['icon']['down'], 'title' => 'Down', 'count' => $cache['ports']['stat']['down']);
}

if ($cache['ports']['stat']['shutdown'])
{
  $navbar['ports']['entries']['statuses']['entries'][] = array('url' => generate_url(array('page' => 'ports', 'state' => 'shutdown')), 'icon' => $config['icon']['shutdown'], 'title' => 'Shutdown', 'count' => $cache['ports']['stat']['shutdown']);
}

if ($cache['ports']['stat']['ignored'])
{
  $navbar['ports']['entries']['statuses']['entries'][] = array('url' => generate_url(array('page' => 'ports', 'ignore' => '1')), 'icon' => $config['icon']['ignore'], 'title' => 'Ignored', 'count' => $cache['ports']['stat']['ignored']);
}

if ($cache['ports']['stat']['poll_disabled'])
{
  $navbar['ports']['entries']['statuses']['entries'][] = array('url' => generate_url(array('page' => 'ports', 'disabled' => '1')), 'icon' => $config['icon']['ignore'], 'title' => 'Poll Disabled', 'count' => $cache['ports']['stat']['poll_disabled']);
}

if ($cache['ports']['stat']['deleted'])
{
  $navbar['ports']['entries']['statuses']['entries'][] = array('url' => generate_url(array('page' => 'deleted-ports')), 'icon' => $config['icon']['stop'], 'title' => 'Deleted', 'count' => $cache['ports']['stat']['deleted']);
}

//////////// Build health menu
$navbar['health'] = array('url' => '#', 'icon' => $config['icon']['health'], 'title' => 'Health');

$health_items = array('processor' => array('text' => 'Processors', 'icon' => $config['icon']['processor']),
                      'mempool'   => array('text' => 'Memory',     'icon' => $config['icon']['mempool']),
                      'storage'   => array('text' => 'Storage',    'icon' => $config['icon']['storage'])
                     );

if ($cache['printersupplies']['count'])
{
  $health_items['printersupplies'] = array('text' => 'Printer Supplies', 'icon' => $config['icon']['printersupply']);
}

if ($cache['status']['count'])
{
  $health_items['status'] = array('text' => 'Status', 'icon' => $config['entities']['status']['icon']);
}

foreach ($health_items as $item => $item_data)
{
  $navbar['health']['entries'][] = array('url' => generate_url(array('page' => 'health', 'metric' => $item)), 'icon' => $item_data['icon'], 'title' => $item_data['text']);
  unset($menu_sensors[$item]);$sep++;
}

$menu_items[0] = array('fanspeed','humidity','temperature','airflow');
$menu_items[1] = array('current','voltage','power','apower','rpower','frequency');
$menu_items[2] = array_diff(array_keys($cache['sensor_types']), $menu_items[0], $menu_items[1]);
foreach ($menu_items as $items)
{
  foreach ($items as $item)
  {
    if ($cache['sensor_types'][$item])
    {
      if ($sep)
      {
        $navbar['health']['entries'][] = array('divider' => TRUE);
        $sep = 0;
      }
      $alert_icon = ($cache['sensor_types'][$item]['alert'] ? '<i class="'.$config['icon']['flag'].' mini-icon"></i>' : '');
      $navbar['health']['entries'][] = array('url' => generate_url(array('page' => 'health', 'metric' => $item)), 'icon' => $config['sensor_types'][$item]['icon'], 'title' => nicecase($item) . ' ' . $alert_icon);
    }
  }
  $sep++;
}

//////////// Build applications menu
if ($_SESSION['userlevel'] >= '5' && ($cache['applications']['count']) > 0)
{
  $navbar['apps'] = array('url' => '#', 'icon' => $config['icon']['apps'], 'title' => 'Apps');

  $app_list = dbFetchRows("SELECT `app_type` FROM `applications` WHERE 1 ".$cache['where']['devices_permitted']." GROUP BY `app_type`;");
  foreach ($app_list as $app)
  {
    $image = $config['html_dir']."/images/icons/".$app['app_type'].".png";
    $icon = (is_file($image) ? $app['app_type'] : "apps");

    // Detect and add application icon
    $icon = $app['app_type'];
    $image = $config['html_dir'].'/images/apps/'.$icon.'.png';
    if (is_file($image))
    {
      // Icon found
      //$icon = $app['app_type'];
    } else {
      list($icon) = explode('-', str_replace('_', '-', $app['app_type']));
      $image = $config['html_dir'].'/images/apps/'.$icon.'.png';
      if ($icon != $app['app_type'] && is_file($image))
      {
        // 'postfix_qshape' -> 'postfix'
        // 'exim-mailqueue' -> 'exim'
      } else {
        $icon = 'apps'; // Generic
      }
    }

    $entry = array('url' => generate_url(array('page' => 'apps', 'app' => $app['app_type'])), 'title' => nicecase($app['app_type']));
    $entry['image'] = 'images/apps/'.$icon.'.png';
    if (is_file($config['html_dir'].'/images/apps/'.$icon.'_2x.png'))
    {
      // HiDPI icon
      $entry['image_2x'] = 'images/apps/'.$icon.'_2x.png';
    }

    $navbar['apps']['entries'][] = $entry;
  }
  unset($entry);
}

//////////// Build routing menu
if ($_SESSION['userlevel'] >= '5' && ($cache['routing']['bgp']['count']+$cache['routing']['ospf']['count']+$cache['routing']['cef']['count']+$cache['routing']['vrf']['count']) > 0)
{
  $navbar['routing'] = array('url' => '#', 'icon' => $config['icon']['routing'], 'title' => 'Routing');

  $separator = 0;

  if ($cache['routing']['vrf']['count'])
  {
    $navbar['routing']['entries'][] = array('url' => generate_url(array('page' => 'routing', 'protocol' => 'vrf')), 'icon' => $config['icon']['vrf'], 'title' => 'VRFs', 'count' => $cache['routing']['vrf']['count']);
    $separator++;
  }

  if ($cache['routing']['cef']['count'])
  {
    if ($separator)
    {
      $navbar['routing']['entries'][] = array('divider' => TRUE);
      $separator = 0;
    }

    $navbar['routing']['entries'][] = array('url' => generate_url(array('page' => 'routing', 'protocol' => 'cef')), 'icon' => $config['icon']['cef'], 'title' => 'CEF', 'count' => $cache['routing']['cef']['count']);
    $separator++;
  }

  if ($cache['routing']['ospf']['count'])
  {
    if ($separator)
    {
      $navbar['routing']['entries'][] = array('divider' => TRUE);
      $separator = 0;
    }

    $navbar['routing']['entries'][] = array('url' => generate_url(array('page' => 'routing', 'protocol' => 'ospf')), 'icon' => $config['icon']['ospf'], 'title' => 'OSPF', 'count' => $cache['routing']['ospf']['count']);
    $separator++;
  }

  // BGP Sessions
  if ($cache['routing']['bgp']['count'])
  {
    if ($separator)
    {
      $navbar['routing']['entries'][] = array('divider' => TRUE);
      $separator = 0;
    }

    $navbar['routing']['entries'][] = array('url' => generate_url(array('page' => 'routing', 'protocol' => 'bgp', 'type' => 'all', 'graph' => 'NULL')), 'icon' => $config['icon']['bgp'], 'title' => 'BGP All Sessions', 'count' => $cache['routing']['bgp']['count']);
    $navbar['routing']['entries'][] = array('url' => generate_url(array('page' => 'routing', 'protocol' => 'bgp', 'type' => 'external', 'graph' => 'NULL')), 'icon' => $config['icon']['bgp-external'], 'title' => 'BGP External', count => $cache['routing']['bgp']['external']);
    $navbar['routing']['entries'][] = array('url' => generate_url(array('page' => 'routing', 'protocol' => 'bgp', 'type' => 'internal', 'graph' => 'NULL')), 'icon' => $config['icon']['bgp-internal'], 'title' => 'BGP Internal', count => $cache['routing']['bgp']['internal']);
  }

  // Do Alerts at the bottom
  if ($cache['routing']['bgp']['alerts'])
  {
    $navbar['routing']['entries'][] = array('divider' => TRUE);
    $navbar['routing']['entries'][] = array('url' => generate_url(array('page' => 'routing', 'protocol' => 'bgp', 'adminstatus' => 'start', 'state' => 'down')), 'icon' => $config['icon']['bgp-alert'], 'title' => 'BGP Alerts', 'count' => $cache['routing']['bgp']['alerts']);
  }
}

// Custom navbar entries.
if (is_file("includes/navbar-custom.inc.php"))
{
  include("includes/navbar-custom.inc.php");
}

// DOCME needs phpdoc block
function navbar_location_menu($array)
{
  global $config;

  ksort($array['entries']);

  echo('<ul role="menu" class="dropdown-menu">');

  if (count($array['entries']) > "5")
  {
    foreach ($array['entries'] as $entry => $entry_data)
    {
      $image = '<i class="'.$config['icon']['location'].'"></i>';
      if ($entry_data['level'] == "location_country")
      {
        $code = $entry;
        $entry = country_from_code($entry);
        $image = '<i class="flag flag-'.$code.'"></i>';
      }
      else if ($entry_data['level'] == "location")
      {
        $name = ($entry === '' ? OBS_VAR_UNSET : escape_html($entry));
        echo('            <li>' . generate_menu_link(generate_location_url($entry), '<i class="'.$config['icon']['location'].'"></i>&nbsp;' . $name, $entry_data['count']) . '</li>');
        continue;
      }

      if ($entry_data['level'] == "location_country")
      {
        $url = $code;
        // Attach country code to sublevel
        $entry_data['country'] = strtolower($code);
      } else {
        $url = $entry;
        // Attach country code to sublevel
        $entry_data['country'] = $array['country'];
      }
      if ($url === '') { $url = array(''); }
      $link_array = array('page' => 'devices',
                          $entry_data['level'] => var_encode($url));
      if (isset($array['country'])) { $link_array['location_country'] = var_encode($array['country']); }

      echo('<li class="dropdown-submenu">' . generate_menu_link(generate_url($link_array), $image . '&nbsp;' . $entry, $entry_data['count']));

      navbar_location_menu($entry_data);
      echo('</li>');
    }
  } else {
    $new_entry_array = array();

    foreach ($array['entries'] as $new_entry => $new_entry_data)
    {
      if ($new_entry_data['level'] == "location_country")
      {
        $code = $new_entry;
        $new_entry = country_from_code($new_entry);
        $image = '<i class="flag flag-'.$code.'"></i> ';
      }
      elseif ($new_entry_data['level'] == "location")
      {
        $name = ($new_entry === '' ? OBS_VAR_UNSET : escape_html($new_entry));
        echo('            <li>' . generate_menu_link(generate_location_url($new_entry), '<i class="' . $entry['icon']['location'] . '"></i>&nbsp;' . $name, $new_entry_data['count']) . '</li>');
        continue;
      }

      echo('<li class="nav-header">'.$image.$new_entry.'</li>');
      foreach ($new_entry_data['entries'] as $sub_entry => $sub_entry_data)
      {
        if (is_array($sub_entry_data['entries']))
        {
          $link_array = array('page' => 'devices',
                              $sub_entry_data['level'] => var_encode($sub_entry));
          if (isset($array['country'])) { $link_array['location_country'] = var_encode($array['country']); }

          echo('<li class="dropdown-submenu">' . generate_menu_link(generate_url($link_array), '<i class="' . $entry['icon']['location'] . '"></i>&nbsp;' . $sub_entry, $sub_entry_data['count']));
          navbar_location_menu($sub_entry_data);
        } else {
          $name = ($sub_entry === '' ? OBS_VAR_UNSET : escape_html($sub_entry));
          echo('            <li>' . generate_menu_link(generate_location_url($sub_entry), '<i class="' . $entry['icon']['location'] . '"></i>&nbsp;' . $name, $sub_entry_data['count']) . '</li>');
        }
      }
    }
  }
  echo('</ul>');
}

// DOCME needs phpdoc block
function navbar_submenu($entry, $level = 1)
{
  echo(str_pad('',($level-1)*2) . '                <li class="dropdown-submenu">' . generate_menu_link($entry['url'], '<i class="' . $entry['icon'] . '"></i>&nbsp;' . $entry['title'], $entry['count']) . PHP_EOL);
  echo(str_pad('',($level-1)*2) . '                  <ul role="menu" class="dropdown-menu">' . PHP_EOL);

  foreach ($entry['entries'] as $subentry)
  {
    if (count($subentry['entries']))
    {
      navbar_submenu($subentry, $level+1);
    } else {
      navbar_entry($subentry, $level+2);
    }
  }

  echo(str_pad('',($level-1)*2) . '                  </ul>' . PHP_EOL);
  echo(str_pad('',($level-1)*2) . '                <li>' . PHP_EOL);
}

// DOCME needs phpdoc block
// FIXME. Move to print navbar
function navbar_entry($entry, $level = 1)
{
  global $cache;

  if ($entry['divider'])
  {
    echo(str_pad('',($level-1)*2) . '                <li class="divider"></li>' . PHP_EOL);
  } elseif ($entry['locations']) { // Workaround until the menu builder returns an array instead of echo()
    echo(str_pad('',($level-1)*2) . '                <li class="dropdown-submenu">' . PHP_EOL);
    echo(str_pad('',($level-1)*2) . '                  ' . generate_menu_link(generate_url(array('page'=>'locations')), '<i class="'.$GLOBALS['config']['icon']['location'].'"></i> Locations') . PHP_EOL);
    navbar_location_menu($cache['locations']);
    echo(str_pad('',($level-1)*2) . '                </li>' . PHP_EOL);
  } else {
    $entry_text = '<i class="menu-icon ' . $entry['icon'] . '"></i> ';

    if (isset($entry['image']))
    {
      // Detect allowed screen ratio for current browser, cached!
      $ua_info = detect_browser();
      if (isset($entry['image_2x']) && $ua_info['screen_ratio'] > 1)
      {
        // Add hidpi image set
        $srcset = ' srcset="' . $entry['image_2x'] . ' 2x"';
      } else {
        $srcset = '';
      }
      $entry_text .= '<img src="' . $entry['image'] . '"' . $srcset . ' alt="" /> ';
    }

    $entry_text .= $entry['title'];

    echo(str_pad('',($level-1)*2) . '                <li>' . generate_menu_link($entry['url'], $entry_text, $entry['count']) . '</li>' . PHP_EOL);
  }
}

// Build navbar from $navbar array
foreach ($navbar as $dropdown)
{
//  echo('            <li class="divider-vertical" style="margin: 0;"></li>' . PHP_EOL);

  if(strpos($dropdown['icon'], 'sprite') !== FALSE) { $element = 'span'; } else { $element = 'i'; }

  echo('            <li class="dropdown">' . PHP_EOL);
  echo('              <a href="' . $dropdown['url'] . '" class="visible-lg visible-xl dropdown-toggle" data-hover="dropdown" data-toggle="dropdown">' . PHP_EOL);
  echo('                <'.$element.' class="' . $dropdown['icon'] . '"></'.$element.'> ' . $dropdown['title'] . ' <b class="caret"></b></a>' . PHP_EOL);
  echo('              <a href="' . $dropdown['url'] . '" class="visible-xs visible-md dropdown-toggle" data-hover="dropdown" data-toggle="dropdown">' . PHP_EOL);
  echo('                <'.$element.' class="' . $dropdown['icon'] . '" style="margin-right: 5px;"></i></a>' . PHP_EOL);
  echo('              <ul role="menu" class="dropdown-menu">' . PHP_EOL);

  foreach ($dropdown['entries'] as $entry)
  {
    if (count($entry['entries']))
    {
      navbar_submenu($entry);
    } else {
      navbar_entry($entry);
    }
  }

  echo('              </ul>' . PHP_EOL);
  echo('            </li>' . PHP_EOL);
}

unset($navbar);

// The menus on the right are not handled by the navbar array code yet.

?>
          </ul>
          <ul class="nav pull-right">
          <li class="dropdown hidden-xs">
            <form id="searchform" class="navbar-search" action="#" style="margin-left: 5px; margin-right: 10px;  margin-top: 5px; margin-bottom: -5px;">
              <input style="width: 100px;" onkeyup="lookup(this.value);" onblur="$('#suggestions').fadeOut()" type="text" value="" class="dropdown-toggle" placeholder="Search" />
            </form>
            <div id="suggestions" class="typeahead dropdown-menu"></div>
          </li>
<?php

// Script for go to first founded link in search
register_html_resource('script', '
$(function() {
  $(\'form#searchform\').each(function() {
    $(this).find(\'input\').keypress(function(e) {
      if (e.which==10 || e.which==13) {
        //console.log($(\'div#suggestions > li > a\').first().prop(\'href\'));
        $(\'form#searchform\').prop(\'action\', $(\'div#suggestions > li > a\').first().prop(\'href\'));
        // Only submit if we actually have a suggestion to link to
        if ($(\'div#suggestions > li > a\').length > 0) {
          this.form.submit();
        }
      }
    });
  });
});
');

$ua = array('browser' => detect_browser_type());
if ($_SESSION['touch'] == "yes")
{
  $ua['url'] = generate_url($vars, array('touch' => 'no'));
} else {
  $ua['url'] = generate_url($vars, array('touch' => 'yes'));
}

if ($vars['touch'] == 'yes')        { $ua['icon'] = 'glyphicon glyphicon-hand-up'; }
elseif ($ua['browser'] == 'mobile') { $ua['icon'] = 'glyphicon glyphicon-phone'; }
elseif ($ua['browser'] == 'tablet') { $ua['icon'] = 'icon-tablet'; }
else                                { $ua['icon'] = 'icon-laptop'; }

$ua['content']  = 'Your current IP:&nbsp;' . $_SERVER['REMOTE_ADDR'] . '<br />';
if ($config['web_mouseover'] && $ua['browser'] != 'mobile')
{
  // Add popup with current IP and previous session
  $last_id = dbFetchCell("SELECT `id` FROM `authlog` WHERE `user` = ? AND `result` LIKE 'Logged In%' ORDER BY `id` DESC LIMIT 1;", array($_SESSION['username']));
  $ua['previous_session'] = dbFetchRow("SELECT * FROM `authlog` WHERE `user` = ? AND `id` < ? AND `result` LIKE 'Logged In%' ORDER BY `id` DESC LIMIT 1;", array($_SESSION['username'], $last_id));
  if ($ua['previous_session'])
  {
    $ua['previous_browser'] = detect_browser($ua['previous_session']['user_agent']);
    $ua['content'] .= '<hr><span class="small">Previous session from&nbsp;<em class="pull-right">' . ($_SESSION['userlevel'] > 5 ? $ua['previous_session']['address'] : preg_replace('/^\d+/', '*', $ua['previous_session']['address'])) . '</em></span>';
    $ua['content'] .= '<br /><span class="small pull-right"><em> at ' . format_timestamp($ua['previous_session']['datetime']) . '</span>';
    $ua['content'] .= '<br /><span class="small pull-right"><i class="' . $ua['previous_browser']['icon'] . '"></i>&nbsp;' . $ua['previous_browser']['browser_full'] . ' (' . $ua['previous_browser']['platform'] . ')' . '</em></span>';
  }
}

echo('<li>' . generate_tooltip_link($ua['url'], ' <i class="' . $ua['icon'] . '"></i>', $ua['content']) . '</li>');

?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-hover="dropdown" data-toggle="dropdown"><i class="<?php echo $config['icon']['tools']; ?>"></i> <b class="caret"></b></a>
              <ul class="dropdown-menu">
<?php
  // Refresh menu
  echo('<li class="dropdown-submenu">');
  echo('  <a tabindex="-1" href="'.generate_url($vars).'"><i class="'. $config['icon']['refresh'].'"></i> Refresh <span id="countrefresh"></span></a>');
  echo('  <ul class="dropdown-menu">');
  foreach ($page_refresh['list'] as $refresh_time)
  {
    $refresh_class = ($refresh_time == $page_refresh['current'] ? 'active' : '');
    if (!$page_refresh['allowed']) { $refresh_class = 'disabled'; }
    if ($refresh_time == 0)
    {
      echo('    <li class="'.$refresh_class.'"><a href="'.generate_url($vars, array('refresh' => '0')).'"><i class="'. $config['icon']['stop'].'"></i> Manually</a></li>');
    } else {
      echo('    <li class="'.$refresh_class.'"><a href="'.generate_url($vars, array('refresh' => $refresh_time)).'"><i class="'. $config['icon']['refresh'].'"></i> Every '.formatUptime($refresh_time, 'longest').'</a></li>');
    }
  }
  echo('  </ul>');
  echo('</li>');
  echo('<li class="divider"></li>');

if ($_SESSION['big_graphs'] == 1)
{
  echo('<li><a href="'.generate_url($vars, array('big_graphs' => 'no')).'" title="Switch to normal graphs"><i class="'.$config['icon']['graphs-small'].'" style="font-size: 16px; color: #555;"></i> Normal Graphs</a></li>');
} else {
  echo('<li><a href="'.generate_url($vars, array('big_graphs' => 'yes')).'" title="Switch to larger graphs"><i class="'.$config['icon']['graphs-large'].'" style="font-size: 16px; color: #555;"></i> Large Graphs</a></li>');
}

if ($_SESSION['userlevel'] >= 10)
{
  echo('<li class="divider"></li>');
  echo('<li class="dropdown-submenu">');
  echo('  <a tabindex="-1" href="'.generate_url(array('page' => 'adduser')).'"><i class="'.$config['icon']['users'].'"></i> Users</a>');
  echo('  <ul class="dropdown-menu">');
  if (auth_usermanagement())
  {
    echo('    <li><a href="'.generate_url(array('page' => 'adduser')).'"><i class="'.$config['icon']['user-add'].'"></i> Add User</a></li>');
  }
  echo('    <li><a href="'.generate_url(array('page' => 'edituser')).'"><i class="'.$config['icon']['user-edit'].'"></i> Edit User</a></li>');
  if (auth_usermanagement())
  {
    echo('    <li><a href="'.generate_url(array('page' => 'edituser')).'"><i class="'.$config['icon']['user-delete'].'"></i> Remove User</a></li>');
  }
  echo('    <li><a href="'.generate_url(array('page' => 'authlog')).'"><i class="'.$config['icon']['user-log'].'"></i> Authentication Log</a></li>');
  echo('  </ul>');
  echo('</li>');

  echo('<li class="dropdown-submenu">');
  echo('  <a tabindex="-1" href="'.generate_url(array('page' => 'settings')).'"><i class="'.$config['icon']['settings'].'"></i> Global Settings</a>');
  echo('  <ul class="dropdown-menu">');
  echo('    <li><a href="'.generate_url(array('page' => 'settings')).'"><i class="'.$config['icon']['settings-change'].'"></i> Edit</a></li>');
  echo('    <li><a href="'.generate_url(array('page' => 'settings', 'format' => 'config')).'"><i class="'.$config['icon']['config'].'"></i> Full Dump</a></li>');
  echo('    <li><a href="'.generate_url(array('page' => 'settings', 'format' => 'changed_config')).'"><i class="'.$config['icon']['config'].'"></i> Changed Dump</a></li>');
  echo('  </ul>');
  echo('</li>');
}
?>
                <li class="divider"></li>
                <li><a href="<?php echo(generate_url(array('page' => 'preferences'))); ?>" title="My Profile"><i class="<?php echo $config['icon']['user-self']; ?>"></i> My Profile</a></li>
                <li class="divider"></li>

<?php

navbar_entry(array('title' => 'Polling Information', 'url' => generate_url(array('page' => 'pollerlog')), 'icon' => $config['icon']['pollerlog']));
if ($_SESSION['userlevel'] >= 7)
{
  navbar_entry(array('title' => 'Process List', 'url' => generate_url(array('page' => 'processes')), 'icon' => $config['icon']['processes']));
  navbar_entry(array('title' => 'OSes', 'url' => generate_url(array('page' => 'os')), 'icon' => $config['icon']['config']));
  navbar_entry(array('title' => 'MIBs', 'url' => generate_url(array('page' => 'mibs')), 'icon' => $config['icon']['mibs']));
}

if (auth_can_logout())
{
?>
                <li class="divider"></li>
                <li><a href="<?php echo(generate_url(array('page' => 'logout'))); ?>" title="Logout"><i class="<?php  echo $config['icon']['logout']; ?>"></i> Logout</a></li>
<?php
}
?>
                <li class="divider"></li>
                <li><a href="<?php echo OBSERVIUM_URL; ?>/docs" title="Help"><i class="<?php echo $config['icon']['help']; ?>"></i> Help</a></li>
                <li><?php echo(generate_link('<i class="'. $config['icon']['info'].'"></i> About '.OBSERVIUM_PRODUCT, array('page' => 'about'), array(), FALSE)); ?></li>
              </ul>
            </li>
          </ul>
        </div><!-- /.nav-collapse -->
      </div>
    </div><!-- /navbar-inner -->
  </header>

<script type="text/javascript">
if (!Date.now) {
    Date.now = function() { return new Date().getTime(); }
}


key_count_global = 0;
key_press_time = Date.now()

function lookup(inputString) {
  if (inputString.trim().length == 0) {
    $('#suggestions').fadeOut(); // Hide the suggestions box
  } else {
    key_count_global++;
    setTimeout("lookupwait("+key_count_global+",\""+inputString+"\")", 300); // Added timeout 0.3s before send query
  }
  key_press_time = Date.now()
}

function lookupwait(key_count,inputString) {
  if(key_count == key_count_global && (Date.now()-key_press_time >= 300 )) {
    $.post("ajax/search.php", {queryString: ""+inputString+""}, function(data) { // Do an AJAX call
      $('#suggestions').fadeIn(); // Show the suggestions box
      $('#suggestions').html(data); // Fill the suggestions box
    });
  }
}

<?php
if (isset($page_refresh['nexttime'])) // Begin Refresh JS
{
?>

// set initial seconds left we're counting down to
var seconds_left = <?php echo($page_refresh['nexttime'] - time()); ?>;
// get tag element
var countrefresh = document.getElementById('countrefresh');

// update the tag with id "countdown" every 1 second
setInterval(function () {
    // do some time calculations
    var minutes = parseInt(seconds_left / 60);
    var seconds = parseInt(seconds_left % 60);

    // format countdown string + set tag value
    if (minutes > 0) {
      minutes = minutes + 'min&nbsp;';
      seconds = seconds + 'sec';
    } else {
      minutes = '';
      if (seconds > 0) {
        seconds = seconds + 'sec';
      } else {
        seconds = '0sec';
      }
    }

    countrefresh.innerHTML = '<span class="label">' + minutes + seconds + '</span>';

    seconds_left = seconds_left - 1;

}, 1000);

<?php
} // End Refresh JS

$menu_time = utime() - $menu_start;

?>

</script>
