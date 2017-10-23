<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 *   These functions perform rewrites on strings and numbers.
 *
 * @package    observium
 * @subpackage functions
 * @author     Adam Armstrong <adama@observium.org>
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

/**
 * Process strings to give them a nicer capitalisation format
 *
 * This function does rewrites from the lowercase identifiers we use to the
 * standard capitalisation. UK English style plurals, please.
 * This uses $config['nicecase']
 *
 * @param string $item
 * @return string
*/
function nicecase($item)
{
  $mappings = $GLOBALS['config']['nicecase'];
  if (isset($mappings[$item])) { return $mappings[$item]; }
  //$item = preg_replace('/([a-z])([A-Z]{2,})/', '$1 $2', $item); // turn "fixedAC" into "fixed AC"

  return ucfirst($item);
}

/**
 * Trim string and remove paired and/or escaped quotes from string
 *
 * @param string $string Input string
 * @return string Cleaned string
 */
function trim_quotes($string, $flags = OBS_QUOTES_TRIM)
{
  $string = trim($string); // basic trim of string
  if (strpos($string, '"') !== FALSE && is_flag_set(OBS_QUOTES_TRIM, $flags))
  {
    if (strpos($string, '\"') !== FALSE)
    {
      $string = str_replace('\"', '"', $string); // replace escaped quotes
    }
    $quotes = array('["\']', // remove single quotes
                    //'\\\"',  // remove escaped quotes
                    );
    foreach ($quotes as $quote)
    {
      $pattern = '/^(' . $quote . ')(?<value>.*?)(\1)$/s';
      while (preg_match($pattern, $string, $matches))
      {
        $string = $matches['value'];
      }
    }
  }
  return $string;
}

 /**
  * Humanize User
  *
  *   Process an array containing user info to add/modify elements.
  *
  * @param array $user
  */
// TESTME needs unit testing
function humanize_user(&$user)
{
  $level_permissions = auth_user_level_permissions($user['level']);
  $level_real        = $level_permissions['level'];
  if (isset($GLOBALS['config']['user_level'][$level_real]))
  {
    $def        = $GLOBALS['config']['user_level'][$level_real];
    $user['level_label'] = $def['name'];
    $user['level_name']  = $def['name'];
    $user['level_real']  = $level_real;
    unset($def['name'], $level_permissions['level']);
    $user = array_merge($user, $def, $level_permissions);
    // Add label class
    $user['label_class']  = ($user['row_class'] == 'disabled' ? 'inverse' : $user['row_class']);
  }
  //r($user);
}

/**
 * Humanize Scheduled Maintanance
 *
 *   Process an array containing a row from `alert_maintenance` and in-place add/modify elements for use in the UI
 *
 *
 */
function humanize_maintenance(&$maint)
{

  $maint['duration'] = $maint['maint_end'] - $maint['maint_start'];

  if ($maint['maint_global'] == 1)
  {
    $maint['entities_text'] = '<span class="label label-info">Global Maintenance</span>';
  } else {
    $entities = dbFetchRows("SELECT * FROM `alerts_maint_assoc` WHERE `maint_id` = ?", array($maint['maint_id']));
    if (is_array($entities) && count($entities))
    {
      foreach ($entities as $entity)
      {
        // FIXME, what here should be?
      }
    } else {
      $maint['entities_text'] = '<span class="label label-error">Maintenance is not associated with any entities.</span>';
    }
  }

  $maint['row_class'] = '';

  if ($maint['maint_start'] > $GLOBALS['config']['time']['now'])
  {
    $maint['start_text'] = "+".formatUptime($maint['maint_start'] - $GLOBALS['config']['time']['now']);
  } else {
    $maint['start_text'] = "-".formatUptime($GLOBALS['config']['time']['now'] - $maint['maint_start']);
    $maint['row_class']  = "warning";
    $maint['active_text'] = '<span class="label label-warning pull-right">active</span>';
  }

  if ($maint['maint_end'] > $GLOBALS['config']['time']['now'])
  {
    $maint['end_text'] = "+".formatUptime($maint['maint_end'] - $GLOBALS['config']['time']['now']);
  } else {
    $maint['end_text'] = "-".formatUptime($GLOBALS['config']['time']['now'] - $maint['maint_end']);
    $maint['row_class']  = "disabled";
    $maint['active_text'] = '<span class="label label-disabled pull-right">ended</span>';
  }

}

/**
 * Humanize Alert Check
 *
 *   Process an array containing a row from `alert_checks` and in place to add/modify elements.
 *
 * @param array $alert_check
 */
// TESTME needs unit testing
function humanize_alert_check(&$check)
{
  // Fetch the queries to build the alert table.
  list($query, $param, $query_count) = build_alert_table_query(array('alert_test_id' => $check['alert_test_id']));

  // Fetch a quick set of alert_status values to build the alert check status text
  $query = str_replace(" * ", " `alert_status` ", $query);
  $check['entities'] = dbFetchRows($query, $param);

  $check['entity_status'] = array('up' => 0, 'down' => 0, 'unknown' => 0, 'delay' => 0, 'suppress' => 0);
  foreach ($check['entities'] as $alert_table_id => $alert_table_entry)
  {
    if ($alert_table_entry['alert_status'] == '1')      { ++$check['entity_status']['up'];
    } elseif($alert_table_entry['alert_status'] == '0') { ++$check['entity_status']['down'];
    } elseif($alert_table_entry['alert_status'] == '2') { ++$check['entity_status']['delay'];
    } elseif($alert_table_entry['alert_status'] == '3') { ++$check['entity_status']['suppress'];
    } else                                              { ++$check['entity_status']['unknown']; }
  }

  $check['num_entities'] = count($check['entities']);

  if ($check['entity_status']['up'] == $check['num_entities'])
  {
    $check['class']  = "green"; $check['html_row_class'] = "up";
  } elseif($check['entity_status']['down'] > '0') {
    $check['class']  = "red"; $check['html_row_class'] = "error";
  } elseif($check['entity_status']['delay'] > '0') {
    $check['class']  = "orange"; $check['html_row_class'] = "warning";
  } elseif($check['entity_status']['suppress'] > '0') {
    $check['class']  = "purple"; $check['html_row_class'] = "suppressed";
  } elseif($check['entity_status']['up'] > '0') {
    $check['class']  = "green"; $check['html_row_class'] = "success";
  } else {
    $check['entity_status']['class']  = "gray"; $check['table_tab_colour'] = "#555555"; $check['html_row_class'] = "disabled";
  }

  $check['status_numbers'] = '<span class="label label-success">'. $check['entity_status']['up']. '</span><span class="label label-suppressed">'. $check['entity_status']['suppress'].
         '</span><span class="label label-error">'. $check['entity_status']['down']. '</span><span class="label label-warning">'. $check['entity_status']['delay'].
         '</span><span class="label">'. $check['entity_status']['unknown']. '</span>';

  // We return nothing, $check is modified in place.
}

 /**
  * Humanize Alert
  *
  *   Process an array containing a row from `alert_entry` and `alert_entry-state` in place to add/modify elements.
  *
  * @param array $alert_entry
  */
// TESTME needs unit testing
function humanize_alert_entry(&$entry)
{
  // Exit if already humanized
  if ($entry['humanized']) { return; }

  // Set colours and classes based on the status of the alert
  if ($entry['alert_status'] == '1')
  {
    // 1 means ok. Set blue text and disable row class
    $entry['class']  = "green"; $entry['html_row_class'] = "up"; $entry['status'] = "OK";
  } elseif($entry['alert_status'] == '0') {
    // 0 means down. Set red text and error class
    $entry['class']  = "red"; $entry['html_row_class'] = "error"; $entry['status'] = "FAILED";
  } elseif($entry['alert_status'] == '2') {
    // 2 means the checks failed but we're waiting for x repetitions. set colour to orange and class to warning
    $entry['class']  = "orange"; $entry['html_row_class'] = "warning"; $entry['status'] = "DELAYED";
  } elseif($entry['alert_status'] == '3') {
    // 3 means the checks failed but the alert is suppressed. set the colour to purple and the row class to suppressed
    $entry['class']  = "purple"; $entry['html_row_class'] = "suppressed"; $entry['status'] = "SUPPRESSED";
  } else {
    // Anything else set the colour to grey and the class to disabled.
    $entry['class']  = "gray"; $entry['html_row_class'] = "disabled"; $entry['status'] = "Unknown";
  }

  // Set the checked/changed/alerted entries to formatted date strings if they exist, else set them to never
  if (!isset($entry['last_checked']) || $entry['last_checked'] == '0') { $entry['checked'] = "<i>Never</i>"; } else { $entry['checked'] = formatUptime(time()-$entry['last_checked'], 'short-3'); }
  if (!isset($entry['last_changed']) || $entry['last_changed'] == '0') { $entry['changed'] = "<i>Never</i>"; } else { $entry['changed'] = formatUptime(time()-$entry['last_changed'], 'short-3'); }
  if (!isset($entry['last_alerted']) || $entry['last_alerted'] == '0') { $entry['alerted'] = "<i>Never</i>"; } else { $entry['alerted'] = formatUptime(time()-$entry['last_alerted'], 'short-3'); }
  if (!isset($entry['last_recovered']) || $entry['last_recovered'] == '0') { $entry['recovered'] = "<i>Never</i>"; } else { $entry['recovered'] = formatUptime(time()-$entry['last_recovered'], 'short-3'); }

  if (!isset($entry['ignore_until']) || $entry['ignore_until'] == '0') { $entry['ignore_until_text'] = "<i>Disabled</i>"; } else { $entry['ignore_until_text'] = format_timestamp($entry['ignore_until']); }
  if (!isset($entry['ignore_until_ok']) || $entry['ignore_until_ok'] == '0') { $entry['ignore_until_ok_text'] = "<i>Disabled</i>"; } else { $entry['ignore_until_ok_text'] = '<span class="purple">Yes</span>'; }

  // Set humanized so we can check for it later.
  $entry['humanized'] = TRUE;

  // We return nothing as we're working on a reference.
}

/**
 * Humanize Device
 *
 *   Process an array containing a row from `devices` to add/modify elements.
 *
 * @param array $device
 * @return none
 */
// TESTME needs unit testing
function humanize_device(&$device)
{
  global $config;

  // Exit if already humanized
  if ($device['humanized']) { return; }

  // Expand the device state array from the php serialized string
  $device['state'] = unserialize($device['device_state']);

  // Set the HTML class and Tab color for the device based on status
  if ($device['status'] == '0')
  {
    $device['row_class'] = "danger";
    $device['html_row_class'] = "error";
  } else {
    $device['row_class'] = "";
    $device['html_row_class'] = "up";  // Fucking dull gay colour, but at least there's a semicolon now - tom
                                            // Your mum's a semicolon - adama
                                            // Haha - mike
  }
  if ($device['ignore'] == '1')
  {
    $device['html_row_class'] = "suppressed";
    if ($device['status'] == '1')
    {
      $device['html_row_class'] = "success";  // Why green for ignore? Confusing!
                                              // I chose this purely because using green for up and blue for up/ignore was uglier.
    } else {
      $device['row_class'] = "suppressed";
    }
  }
  if ($device['disabled'] == '1')
  {
    $device['row_class'] = "disabled";
    $device['html_row_class'] = "disabled";
  }

  // Set country code always lowercase
  if (isset($device['location_country']))
  {
    $device['location_country'] = strtolower($device['location_country']);
  }

  // Set the name we print for the OS
  $device['os_text'] = $config['os'][$device['os']]['text'];

  // Mark this device as being humanized
  $device['humanized'] = TRUE;
}

/**
 * Humanize BGP Peer
 *
 * Returns a the $peer array with processed information:
 * row_class, table_tab_colour, state_class, admin_class
 *
 * @param array $peer
 * @return array $peer
 *
 */
// TESTME needs unit testing
function humanize_bgp(&$peer)
{
  // Exit if already humanized
  if ($peer['humanized']) { return; }

  // Set colours and classes based on the status of the peer
  if ($peer['bgpPeerAdminStatus'] == 'stop' || $peer['bgpPeerAdminStatus'] == 'halted')
  {
    // Peer is disabled, set row to warning and text classes to muted.
    $peer['html_row_class'] = "warning";
    $peer['state_class']    = "muted";
    $peer['admin_class']    = "muted";
    $peer['alert']          = 0;
    $peer['disabled']       = 1;
  }
  else if ($peer['bgpPeerAdminStatus'] == "start" || $peer['bgpPeerAdminStatus'] == "running" )
  {
    // Peer is enabled, set state green and check other things
    $peer['admin_class'] = "success";
    if ($peer['bgpPeerState'] == "established")
    {
      // Peer is up, set colour to blue and disable row class
      $peer['state_class'] = "success"; $peer['html_row_class'] = "up";
    } else {
      // Peer is down, set colour to red and row class to error.
      $peer['state_class'] = "danger"; $peer['html_row_class'] = "error";
    }
  }

  // Set text and colour if peer is same AS, private AS or external.
  if ($peer['bgpPeerRemoteAs'] == $peer['bgpLocalAs'])                                  { $peer['peer_type_class'] = "info";    $peer['peer_type'] = "iBGP"; }
  else if ($peer['bgpPeerRemoteAS'] >= '64512' && $peer['bgpPeerRemoteAS'] <= '65535')  { $peer['peer_type_class'] = "warning"; $peer['peer_type'] = "Priv eBGP"; }
  else                                                                                  { $peer['peer_type_class'] = "primary"; $peer['peer_type'] = "eBGP"; }

  // Format (compress) the local/remote IPs if they're IPv6
  $peer['human_localip']  = (strstr($peer['bgpPeerLocalAddr'],  ':')) ? Net_IPv6::compress($peer['bgpPeerLocalAddr'])  : $peer['bgpPeerLocalAddr'];
  $peer['human_remoteip'] = (strstr($peer['bgpPeerRemoteAddr'], ':')) ? Net_IPv6::compress($peer['bgpPeerRemoteAddr']) : $peer['bgpPeerRemoteAddr'];

  // Set humanized entry in the array so we can tell later
  $peer['humanized'] = TRUE;
}

function process_port_label(&$this_port, $device)
{
  global $config;

  // OS Specific rewrites (get your shit together, vendors)
  if ($device['os'] == 'zxr10') { $this_port['ifAlias'] = preg_replace("/^" . str_replace("/", "\\/", $this_port['ifName']) . "\s*/", '', $this_port['ifDescr']); }
  if ($device['os'] == 'ciscosb' && $this_port['ifType'] == 'propVirtual' && is_numeric($this_port['ifDescr'])) { $this_port['ifName'] = 'Vl'.$this_port['ifDescr']; }

  // Please don't use snmp_ functions here
  //$this_port['ifAlias'] = snmp_fix_string($this_port['ifAlias']); // Fix ord chars

  // Added for Brocade NOS. Will copy ifDescr -> ifAlias if ifDescr != ifName
  // ifAlias can be passed over SW-MIB
  if ($config['os'][$device['os']]['ifDescr_ifAlias'] && $this_port['ifAlias'] == '' && $this_port['ifDescr'] != $this_port['ifName'])
  {
    $this_port['ifAlias'] = $this_port['ifDescr'];
  }

  // Write port_label, port_label_base and port_label_num

  // Here definition override for ifDescr, because Calix switch ifDescr <> ifName since fw 2.2
  // Note, only for 'calix' os now
  if ($device['os'] == 'calix')
  {
    unset($config['os'][$device['os']]['ifname']);
    $version_parts = explode('.', $device['version']);
    if ($version_parts[0] > 2 || ($version_parts[0] == 2 && $version_parts[1] > 1))
    {
      if ($this_port['ifName'] == '')
      {
        $this_port['port_label'] = $this_port['ifDescr'];
      } else {
        $this_port['port_label'] = $this_port['ifName'];
      }
    }
  }

  if ($this_port['ifDescr'] === '' && $config['os'][$device['os']]['ifType_ifDescr'] && $this_port['ifIndex'])
  {
    // This happen on some liebert UPS devices
    $type = rewrite_iftype($this_port['ifType']);
    if ($type)
    {
      $this_port['ifDescr'] = $type . ' ' . $this_port['ifIndex'];
      print_debug("Port 'ifDescr' rewritten: '' -> '" . $this_port['ifDescr'] . "'");
    }
  }

  if (isset($config['os'][$device['os']]['ifname']))
  {
    if ($this_port['ifName'] == '')
    {
      $this_port['port_label'] = $this_port['ifDescr'];
    } else {
      $this_port['port_label'] = $this_port['ifName'];
    }
  }
  else if (isset($config['os'][$device['os']]['ifalias']))
  {
    $this_port['port_label'] = $this_port['ifAlias'];
  } else {
    $this_port['port_label'] = $this_port['ifDescr'];
    if (isset($config['os'][$device['os']]['ifindex']))
    {
      $this_port['port_label'] .= ' ' . $this_port['ifIndex'];
    }
  }
  if ($device['os'] == "speedtouch")
  {
    list($this_port['port_label']) = explode("thomson", $this_port['port_label']);
  }

  // Process label by os definition rewrites
  $oid = 'port_label';
  if (isset($config['os'][$device['os']][$oid]))
  {
    $oid_base = $oid.'_base';
    $oid_num  = $oid.'_num';
    foreach ($config['os'][$device['os']][$oid] as $pattern)
    {
      if (preg_match($pattern, $this_port[$oid], $matches))
      {
        print_debug("Port '$oid' rewritten: '" . $this_port[$oid] . "' -> '" . $matches[1] . "'");
        $this_port[$oid] = $matches[1];
        if (isset($matches[$oid_base]))
        {
          $this_port[$oid_base] = $matches[$oid_base];
        }
        if (isset($matches[$oid_num]))
        {
          $this_port[$oid_num] = $matches[$oid_num];
        }
        break;
      }
    }
  }

  // Extract bracket part from port label and remove it
  $label_bracket = '';
  if (preg_match('/\s*(\([^\)]+\))$/', $this_port['port_label'], $matches))
  {
    // GigaVUE-212 Port  8/48 (Network Port)
    // rtif(172.20.30.46/28)
    $label_bracket = $matches[0];
    list($this_port['port_label']) = explode($matches[0], $this_port['port_label'], 2);
  }
  else if (preg_match('!^10*(?:/10*)*\s*[MGT]Bit\s+(.*)!i', $this_port['port_label'], $matches))
  {
    // remove 10/100 Mbit part from beginning, this broke detect label_base/label_num (see hirschmann-switch os)
    // 10/100 MBit Ethernet Switch Interface 6
    // 1 MBit Ethernet Switch Interface 6
    $label_bracket = $this_port['port_label'];
    $this_port['port_label'] = $matches[1];
  }

  // Detect port_label_base and port_label_num
  if (isset($this_port['port_label_base'])) {} // already set by previous processing, ie os definitions
  else if (preg_match('/\d+(?:(?:[\/:](?:[a-z])?[\d\.:]+)+[a-z\d\.\:]*(?:[\-\_][\w\.\:]+)*|\/\w+$)/i', $this_port['port_label'], $matches))
  {
    // Multipart numeric
    /*
    1/1/1
    e1-0/0/1.0
    e1-0/2/0:13.0
    dwdm0/1/0/6
    DTI1/1/0
    Cable8/1/4-upstream2
    Cable8/1/4
    16GigabitEthernet1/2/1
    cau4-0/2/0
    dot11radio0/0
    Dialer0/0.1
    Downstream 0/2/0
    1000BaseTX Port 8/48 Name
    Backplane-GigabitEthernet0/3
    Ethernet1/10
    FC port 0/19
    GigabitEthernet0/0/0/1
    GigabitEthernet0/1.ServiceInstance.206
    Integrated-Cable7/0/0:0
    Logical Upstream Channel 1/0.0/0
    Slot0/1
    sonet_12/1
    GigaVUE-212 Port  8/48 (Network Port)
    Stacking Port 1/StackA
    1:38
    1/4/x24, mx480-xe-0-0-0
    1/4/x24
    */
    $this_port['port_label_num'] = $matches[0];
    list($this_port['port_label_base']) = explode($matches[0], $this_port['port_label'], 2);
    $this_port['port_label'] = $this_port['port_label_base'] . $this_port['port_label_num']; // Remove additional part (after port number)
  }
  else if (preg_match('/(?<port_label_num>(?:\d+[a-z])?\d[\d\.\:]*(?:[\-\_]\w+)?)(?: [a-z()\[\] ]+)?$/i', $this_port['port_label'], $matches))
  {
    // Simple numeric
    /*
    GigaVUE-212 Port  1 (Network Port)
    MMC-A s3 SW Port
    Atm0_Physical_Interface
    wan1_phys
    fwbr101i0
    Nortel Ethernet Switch 325-24G Module - Port 1
    lo0.32768
    vlan.818
    jsrv.1
    Bundle-Ether1.1701
    Ethernet1
    ethernet_13
    eth0
    eth0.101
    BVI900
    A/1
    e1
    CATV-MAC 1
    16
    */
    $this_port['port_label_num'] = $matches['port_label_num'];
    $this_port['port_label_base'] = substr($this_port['port_label'], 0, 0 - strlen($matches[0]));
    $this_port['port_label'] = $this_port['port_label_base'] . $this_port['port_label_num']; // Remove additional part (after port number)
  } else {
    // All other (non-numeric)
    /*
    UniPing Server Solution v3/SMS Enet Port
    MMC-A s2 SW Port
    Control Plane
    */
    $this_port['port_label_base'] = $this_port['port_label'];
  }

  // When not empty label brackets and empty numeric part, re-add brackets to label
  if (!empty($label_bracket) && $this_port['port_label_num'] == '')
  {
    // rtif(172.20.30.46/28)
    $this_port['port_label'] .= $label_bracket;
  }

  $this_port['port_label_short'] = short_ifname($this_port['port_label']);

  // Set entity variables for use by code which uses entities
  // Base label part: TenGigabitEthernet3/3 -> TenGigabitEthernet, GigabitEthernet4/8.722 -> GigabitEthernet, Vlan2603 -> Vlan
  //$port['port_label_base'] = preg_replace('/^([A-Za-z ]*).*/', '$1', $port['port_label']);
  //$port['port_label_num']  = substr($port['port_label'], strlen($port['port_label_base'])); // Second label part
  //
  //  // Index example for TenGigabitEthernet3/10.324:
  //  //  $ports_links['Ethernet'][] = array('label_base' => 'TenGigabitEthernet', 'label_num0' => '3', 'label_num1' => '10', 'label_num2' => '324')
  //  $label_num  = preg_replace('![^\d\.\/]!', '', substr($data['port_label'], strlen($data['port_label_base']))); // Remove base part and all not-numeric chars
  //  preg_match('!^(\d+)(?:\/(\d+)(?:\.(\d+))*)*!', $label_num, $label_nums); // Split by slash and point (1/1.324)
  //  $ports_links[$data['human_type']][$data['ifIndex']] = array(
  //    'label'      => $data['port_label'],
  //    'label_base' => $data['port_label_base'],
  //    'label_num0' => $label_nums[0],
  //    'label_num1' => $label_nums[1],
  //    'label_num2' => $label_nums[2],
  //    'link'       => generate_port_link($data, $data['port_label_short'])
  //  );

  return TRUE;
}

/**
 * Humanize port.
 *
 * Returns a the $port array with processed information:
 * label, humans_speed, human_type, html_class and human_mac
 * row_class, table_tab_colour
 *
 * Escaping should not be done here, since these values are used in the API too.
 *
 * @param array $port
 * @return array $port
 *
 */
// TESTME needs unit testing
function humanize_port(&$port)
{
  global $config, $cache;

  // Exit if already humanized
  if ($port['humanized']) { return; }

  // If we can get the device data from the global cache, do it, else pull it from the db (mostly for external scripts)
  if (is_array($GLOBALS['cache']['devices']['id'][$port['device_id']]))
  {
    $device = &$GLOBALS['cache']['devices']['id'][$port['device_id']];
  } else {
    $device = device_by_id_cache($port['device_id']);
  }

  // Workaround for devices/ports who long time not updated and have empty port_label
  if (empty($port['port_label']) || strlen($port['port_label_base'].$port['port_label_num']) == 0)
  {
    process_port_label($port, $device);
  }

  // Set humanised values for use in UI
  $port['human_speed'] = humanspeed($port['ifSpeed']);
  $port['human_type']  = rewrite_iftype($port['ifType']);
  $port['html_class']  = port_html_class($port['ifOperStatus'], $port['ifAdminStatus'], $port['encrypted']);
  $port['human_mac']   = format_mac($port['ifPhysAddress']);

  // Set entity_* values for code which expects them.
  $port['entity_name']      = $port['port_label'];
  $port['entity_shortname'] = $port['port_label_short'];
  $port['entity_descr']     = $port['ifAlias'];

  $port['table_tab_colour'] = "#aaaaaa"; $port['row_class'] = ""; $port['icon'] = 'port-ignored'; // Default
  $port['admin_status'] = $port['ifAdminStatus'];
  if     ($port['ifAdminStatus'] == "down")
  {
    $port['admin_status'] = 'disabled';
    $port['row_class'] = "disabled";
    $port['icon'] = 'port-disabled';
  }
  elseif ($port['ifAdminStatus'] == "up")
  {
    $port['admin_status'] = 'enabled';
    switch ($port['ifOperStatus'])
    {
      case 'up':
        $port['table_tab_colour'] = "#194B7F"; $port['row_class'] = "up";      $port['icon'] = 'port-up';
        break;
      case 'monitoring':
        // This is monitoring ([e|r]span) ports
        $port['table_tab_colour'] = "#008C00"; $port['row_class'] = "success"; $port['icon'] = 'port-up';
        break;
      case 'down':
        $port['table_tab_colour'] = "#cc0000"; $port['row_class'] = "error";   $port['icon'] = 'port-down';
        break;
      case 'lowerLayerDown':
        $port['table_tab_colour'] = "#ff6600"; $port['row_class'] = "warning"; $port['icon'] = 'port-down';
        break;
      case 'testing':
      case 'unknown':
      case 'dormant':
      case 'notPresent':
        $port['table_tab_colour'] = "#85004b"; $port['row_class'] = "info";    $port['icon'] = 'port-ignored';
        break;
    }
  }

  // If the device is down, colour the row/tab as 'warning' meaning that the entity is down because of something below it.
  if ($device['status'] == '0')
  {
    $port['table_tab_colour'] = "#ff6600"; $port['row_class'] = "warning"; $port['icon'] = 'port-ignored';
  }

  $port['in_rate'] = $port['ifInOctets_rate'] * 8;
  $port['out_rate'] = $port['ifOutOctets_rate'] * 8;

  // Colour in bps based on speed if > 50, else by UI convention.
  if ($port['ifSpeed'] > 0)
  {
    $in_perc  = round($port['in_rate']/$port['ifSpeed']*100);
    $out_perc = round($port['out_rate']/$port['ifSpeed']*100);
  } else {
    // exclude division by zero error
    $in_perc  = 0;
    $out_perc = 0;
  }
  if ($port['in_rate'] == 0)
  {
    $port['bps_in_style'] = '';
  } elseif ($in_perc < '50') {
    $port['bps_in_style'] = 'color: #008C00;';
  } else {
    $port['bps_in_style'] = 'color: ' . percent_colour($in_perc) . '; ';
  }

  // Colour out bps based on speed if > 50, else by UI convention.
  if ($port['out_rate'] == 0)
  {
    $port['bps_out_style'] = '';
  } elseif ($out_perc < '50') {
    $port['bps_out_style'] = 'color: #394182;';
  } else {
    $port['bps_out_style'] = 'color: ' . percent_colour($out_perc) . '; ';
  }

  // Colour in and out pps based on UI convention
  $port['pps_in_style'] = ($port['ifInUcastPkts_rate'] == 0) ? '' : 'color: #740074;';
  $port['pps_out_style'] = ($port['ifOutUcastPkts_rate'] == 0) ? '' : 'color: #FF7400;';

  $port['humanized'] = TRUE; /// Set this so we can check it later.

}

// Rewrite arrays

$rewrite_entSensorType = array(
  'celsius' => 'C',
  'unknown' => '',
  'specialEnum' => 'C',
  'watts' => 'W',
  'truthvalue' => '',
);

// List of real names for cisco entities
$entPhysicalVendorTypes = array(
  'cevC7xxxIo1feTxIsl'    => 'C7200-IO-FE-MII',
  'cevChassis7140Dualfe'  => 'C7140-2FE',
  'cevChassis7204'        => 'C7204',
  'cevChassis7204Vxr'     => 'C7204VXR',
  'cevChassis7206'        => 'C7206',
  'cevChassis7206Vxr'     => 'C7206VXR',
  'cevCpu7200Npe200'      => 'NPE-200',
  'cevCpu7200Npe225'      => 'NPE-225',
  'cevCpu7200Npe300'      => 'NPE-300',
  'cevCpu7200Npe400'      => 'NPE-400',
  'cevCpu7200Npeg1'       => 'NPE-G1',
  'cevCpu7200Npeg2'       => 'NPE-G2',
  'cevPa1feTxIsl'         => 'PA-FE-TX-ISL',
  'cevPa2feTxI82543'      => 'PA-2FE-TX',
  'cevPa8e'               => 'PA-8E',
  'cevPaA8tX21'           => 'PA-8T-X21',
  'cevMGBIC1000BaseLX'    => '1000BaseLX GBIC',
  'cevPort10GigBaseLR'    => '10GigBaseLR'
);

$rewrite_junos_hardware = array(
  '.1.3.6.1.4.1.4874.1.1.1.6.2' => 'E120',
  '.1.3.6.1.4.1.4874.1.1.1.6.1' => 'E320',
  '.1.3.6.1.4.1.4874.1.1.1.1.1' => 'ERX1400',
  '.1.3.6.1.4.1.4874.1.1.1.1.3' => 'ERX1440',
  '.1.3.6.1.4.1.4874.1.1.1.1.5' => 'ERX310',
  '.1.3.6.1.4.1.4874.1.1.1.1.2' => 'ERX700',
  '.1.3.6.1.4.1.4874.1.1.1.1.4' => 'ERX705',
  '.1.3.6.1.4.1.2636.1.1.1.2.43' => 'EX2200',
  '.1.3.6.1.4.1.2636.1.1.1.2.30' => 'EX3200',
  '.1.3.6.1.4.1.2636.1.1.1.2.76' => 'EX3300',
  '.1.3.6.1.4.1.2636.1.1.1.2.31' => 'EX4200',
  '.1.3.6.1.4.1.2636.1.1.1.2.44' => 'EX4500',
  '.1.3.6.1.4.1.2636.1.1.1.2.74' => 'EX6210',
  '.1.3.6.1.4.1.2636.1.1.1.2.32' => 'EX8208',
  '.1.3.6.1.4.1.2636.1.1.1.2.33' => 'EX8216',
  '.1.3.6.1.4.1.2636.1.1.1.2.16' => 'IRM',
  '.1.3.6.1.4.1.2636.1.1.1.2.13' => 'J2300',
  '.1.3.6.1.4.1.2636.1.1.1.2.23' => 'J2320',
  '.1.3.6.1.4.1.2636.1.1.1.2.24' => 'J2350',
  '.1.3.6.1.4.1.2636.1.1.1.2.14' => 'J4300',
  '.1.3.6.1.4.1.2636.1.1.1.2.22' => 'J4320',
  '.1.3.6.1.4.1.2636.1.1.1.2.19' => 'J4350',
  '.1.3.6.1.4.1.2636.1.1.1.2.15' => 'J6300',
  '.1.3.6.1.4.1.2636.1.1.1.2.20' => 'J6350',
  '.1.3.6.1.4.1.2636.1.1.1.2.38' => 'JCS1200',
  '.1.3.6.1.4.1.2636.10' => 'BX7000',
  '.1.3.6.1.4.1.12532.252.2.1' => 'SA-2000',
  '.1.3.6.1.4.1.12532.252.6.1' => 'SA-6000',
  '.1.3.6.1.4.1.4874.1.1.1.5.1' => 'UMC Sys Mgmt',
  '.1.3.6.1.4.1.2636.3.41.1.1.5.4' => 'WXC1800',
  '.1.3.6.1.4.1.2636.3.41.1.1.5.1' => 'WXC250',
  '.1.3.6.1.4.1.2636.3.41.1.1.5.5' => 'WXC2600',
  '.1.3.6.1.4.1.2636.3.41.1.1.5.6' => 'WXC3400',
  '.1.3.6.1.4.1.2636.3.41.1.1.5.2' => 'WXC500',
  '.1.3.6.1.4.1.2636.3.41.1.1.5.3' => 'WXC590',
  '.1.3.6.1.4.1.2636.3.41.1.1.5.7' => 'WXC7800',
  '.1.3.6.1.4.1.2636.1.1.1.2.4' => 'M10',
  '.1.3.6.1.4.1.2636.1.1.1.2.11' => 'M10i',
  '.1.3.6.1.4.1.2636.1.1.1.2.18' => 'M120',
  '.1.3.6.1.4.1.2636.1.1.1.2.3' => 'M160',
  '.1.3.6.1.4.1.2636.1.1.1.2.2' => 'M20',
  '.1.3.6.1.4.1.2636.1.1.1.2.9' => 'M320',
  '.1.3.6.1.4.1.2636.1.1.1.2.1' => 'M40',
  '.1.3.6.1.4.1.2636.1.1.1.2.8' => 'M40e',
  '.1.3.6.1.4.1.2636.1.1.1.2.5' => 'M5',
  '.1.3.6.1.4.1.2636.1.1.1.2.10' => 'M7i',
  '.1.3.6.1.4.1.2636.1.1.1.2.68' => 'MAG6610',
  '.1.3.6.1.4.1.2636.1.1.1.2.67' => 'MAG6611',
  '.1.3.6.1.4.1.2636.1.1.1.2.66' => 'MAG8600',
  '.1.3.6.1.4.1.2636.1.1.1.2.89' => 'MX10',
  '.1.3.6.1.4.1.2636.1.1.1.2.29' => 'MX240',
  '.1.3.6.1.4.1.2636.1.1.1.2.88' => 'MX40',
  '.1.3.6.1.4.1.2636.1.1.1.2.25' => 'MX480',
  '.1.3.6.1.4.1.2636.1.1.1.2.90' => 'MX5',
  '.1.3.6.1.4.1.2636.1.1.1.2.57' => 'MX80',
  '.1.3.6.1.4.1.2636.1.1.1.2.21' => 'MX960',
  '.1.3.6.1.4.1.3224.1.1' => 'Netscreen',
  '.1.3.6.1.4.1.3224.1.3' => 'Netscreen 10',
  '.1.3.6.1.4.1.3224.1.4' => 'Netscreen 100',
  '.1.3.6.1.4.1.3224.1.5' => 'Netscreen 1000',
  '.1.3.6.1.4.1.3224.1.9' => 'Netscreen 204',
  '.1.3.6.1.4.1.3224.1.10' => 'Netscreen 208',
  '.1.3.6.1.4.1.3224.1.8' => 'Netscreen 25',
  '.1.3.6.1.4.1.3224.1.2' => 'Netscreen 5',
  '.1.3.6.1.4.1.3224.1.7' => 'Netscreen 50',
  '.1.3.6.1.4.1.3224.1.6' => 'Netscreen 500',
  '.1.3.6.1.4.1.3224.1.13' => 'Netscreen 5000',
  '.1.3.6.1.4.1.3224.1.14' => 'Netscreen 5GT',
  '.1.3.6.1.4.1.3224.1.17' => 'Netscreen 5GT-ADSL-A',
  '.1.3.6.1.4.1.3224.1.23' => 'Netscreen 5GT-ADSL-A-WLAN',
  '.1.3.6.1.4.1.3224.1.19' => 'Netscreen 5GT-ADSL-B',
  '.1.3.6.1.4.1.3224.1.25' => 'Netscreen 5GT-ADSL-B-WLAN',
  '.1.3.6.1.4.1.3224.1.21' => 'Netscreen 5GT-WLAN',
  '.1.3.6.1.4.1.3224.1.12' => 'Netscreen 5XP',
  '.1.3.6.1.4.1.3224.1.11' => 'Netscreen 5XT',
  '.1.3.6.1.4.1.3224.1.15' => 'Netscreen Client',
  '.1.3.6.1.4.1.3224.1.28' => 'Netscreen ISG1000',
  '.1.3.6.1.4.1.3224.1.16' => 'Netscreen ISG2000',
  '.1.3.6.1.4.1.3224.1.52' => 'Netscreen SSG140',
  '.1.3.6.1.4.1.3224.1.53' => 'Netscreen SSG140',
  '.1.3.6.1.4.1.3224.1.35' => 'Netscreen SSG20',
  '.1.3.6.1.4.1.3224.1.36' => 'Netscreen SSG20-WLAN',
  '.1.3.6.1.4.1.3224.1.54' => 'Netscreen SSG320',
  '.1.3.6.1.4.1.3224.1.55' => 'Netscreen SSG350',
  '.1.3.6.1.4.1.3224.1.29' => 'Netscreen SSG5',
  '.1.3.6.1.4.1.3224.1.30' => 'Netscreen SSG5-ISDN',
  '.1.3.6.1.4.1.3224.1.33' => 'Netscreen SSG5-ISDN-WLAN',
  '.1.3.6.1.4.1.3224.1.31' => 'Netscreen SSG5-v92',
  '.1.3.6.1.4.1.3224.1.34' => 'Netscreen SSG5-v92-WLAN',
  '.1.3.6.1.4.1.3224.1.32' => 'Netscreen SSG5-WLAN',
  '.1.3.6.1.4.1.3224.1.50' => 'Netscreen SSG520',
  '.1.3.6.1.4.1.3224.1.18' => 'Netscreen SSG550',
  '.1.3.6.1.4.1.3224.1.51' => 'Netscreen SSG550',
  '.1.3.6.1.4.1.2636.1.1.1.2.84' => 'QFX3000',
  '.1.3.6.1.4.1.2636.1.1.1.2.85' => 'QFX5000',
  '.1.3.6.1.4.1.2636.1.1.1.2.82' => 'QFX Switch',
  '.1.3.6.1.4.1.2636.1.1.1.2.41' => 'SRX100',
  '.1.3.6.1.4.1.2636.1.1.1.2.64' => 'SRX110',
  '.1.3.6.1.4.1.2636.1.1.1.2.49' => 'SRX1400',
  '.1.3.6.1.4.1.2636.1.1.1.2.36' => 'SRX210',
  '.1.3.6.1.4.1.2636.1.1.1.2.58' => 'SRX220',
  '.1.3.6.1.4.1.2636.1.1.1.2.39' => 'SRX240',
  '.1.3.6.1.4.1.2636.1.1.1.2.35' => 'SRX3400',
  '.1.3.6.1.4.1.2636.1.1.1.2.34' => 'SRX3600',
  '.1.3.6.1.4.1.2636.1.1.1.2.86' => 'SRX550',
  '.1.3.6.1.4.1.2636.1.1.1.2.28' => 'SRX5600',
  '.1.3.6.1.4.1.2636.1.1.1.2.26' => 'SRX5800',
  '.1.3.6.1.4.1.2636.1.1.1.2.40' => 'SRX650',
  '.1.3.6.1.4.1.2636.1.1.1.2.27' => 'T1600',
  '.1.3.6.1.4.1.2636.1.1.1.2.7' => 'T320',
  '.1.3.6.1.4.1.2636.1.1.1.2.6' => 'T640',
  '.1.3.6.1.4.1.2636.1.1.1.2.17' => 'TX',
  '.1.3.6.1.4.1.2636.1.1.1.2.37' => 'TXPlus',
);

# FIXME needs a rewrite, preferrably in form above? ie cat3524tXLEn etc
$rewrite_cisco_hardware = array(
  '.1.3.6.1.4.1.9.1.275' => 'C2948G-L3',
);

$rewrite_breeze_type = array(
  'aubs'     => 'AU-BS',    // modular access unit
  'ausa'     => 'AU-SA',    // stand-alone access unit
  'su-6-1d'  => 'SU-6-1D',  // subscriber unit supporting 6 Mbps (after 5.0 - deprecated)
  'su-6-bd'  => 'SU-6-BD',  // subscriber unit supporting 6 Mbps
  'su-24-bd' => 'SU-24-BD', // subscriber unit supporting 24 Mbps
  'bu-b14'   => 'BU-B14',   // BreezeNET Base Unit supporting 14 Mbps
  'bu-b28'   => 'BU-B28',   // BreezeNET Base Unit supporting 28 Mbps
  'rb-b14'   => 'RB-B14',   // BreezeNET Remote Bridge supporting 14 Mbps
  'rb-b28'   => 'RB-B28',   // BreezeNET Remote Bridge supporting 28 Mbps
  'su-bd'    => 'SU-BD',    // subscriber unit
  'su-54-bd' => 'SU-54-BD', // subscriber unit supporting 54 Mbps
  'su-3-1d'  => 'SU-3-1D',  // subscriber unit supporting 3 Mbps (after 5.0 - deprecated)
  'su-3-4d'  => 'SU-3-4D',  // subscriber unit supporting 3 Mbps
  'ausbs'    => 'AUS-BS',   // modular access unit supporting maximum 25 subscribers
  'aussa'    => 'AUS-SA',   // stand-alone access unit supporting maximum 25 subscribers
  'aubs4900' => 'AU-BS-4900', // BreezeAccess 4900 modular access unit
  'ausa4900' => 'AU-SA-4900', // BreezeAccess 4900 stand alone access unit
  'subd4900' => 'SU-BD-4900', // BreezeAccess 4900 subscriber unit
  'bu-b100'  => 'BU-B100',  // BreezeNET Base Unit unlimited throughput
  'rb-b100'  => 'BU-B100',  // BreezeNET Remote Bridge unlimited throughput
  'su-i'     => 'SU-I',
  'au-ez'    => 'AU-EZ',
  'su-ez'    => 'SU-EZ',
  'su-v'     => 'SU-V',     // subscriber unit supporting 12 Mbps downlink and 8 Mbps uplink
  'bu-b10'   => 'BU-B10',   // BreezeNET Base Unit supporting 5 Mbps
  'rb-b10'   => 'RB-B10',   // BreezeNET Base Unit supporting 5 Mbps
  'su-8-bd'  => 'SU-8-BD',  // subscriber unit supporting 8 Mbps
  'su-1-bd'  => 'SU-1-BD',  // subscriber unit supporting 1 Mbps
  'su-3-l'   => 'SU-3-L',   // subscriber unit supporting 3 Mbps
  'su-6-l'   => 'SU-6-L',   // subscriber unit supporting 6 Mbps
  'su-12-l'  => 'SU-12-L',  // subscriber unit supporting 12 Mbps
  'au'       => 'AU',       // security access unit
  'su'       => 'SU',       // security subscriber unit
);

$rewrite_extreme_hardware = array (
  'ags100-24t' => 'AGS 100-24t',
  'ags150-24p' => 'AGS 150-24p',
  'alpine3802' => 'Alpine 3802',
  'alpine3804' => 'Alpine 3804',
  'alpine3808' => 'Alpine 3808',
  'altitude300' => 'Altitude 300',
  'altitude350' => 'Altitude 350',
  'altitude3510' => 'Altitude 3510',
  'altitude3550' => 'Altitude 3550',
  'altitude360' => 'Altitude 360',
  'altitude450' => 'Altitude 450',
  'altitude4610' => 'Altitude 4610',
  'altitude4620' => 'Altitude 4620',
  'altitude4700' => 'Altitude 4700',
  'bd10808' => 'Black Diamond 10808',
  'bd12802' => 'Black Diamond 12802',
  'bd12804' => 'Black Diamond 12804',
  'bd20804' => 'Black Diamond 20804',
  'bd20808' => 'Black Diamond 20808',
  'bd8806' => 'Black Diamond 8806',
  'bd8810' => 'Black Diamond 8810',
  'bdx8' => 'Black Diamond X8',
  'blackDiamond6800' => 'Black Diamond 6800',
  'blackDiamond6804' => 'Black Diamond 6804',
  'blackDiamond6808' => 'Black Diamond 6808',
  'blackDiamond6816' => 'Black Diamond 6816',
  'e4g-200' => 'E4G-200',
  'e4g-400' => 'E4G-400',
  'enetSwitch24Port' => 'EnetSwitch 24Port',
  'nwi-e450a' => 'NWI-e450a',
  'sentriantAG200' => 'Sentriant AG200',
  'sentriantAGSW' => 'Sentriant AGSW',
  'sentriantCE150' => 'Sentriant CE150',
  'sentriantNG300' => 'Sentriant NG300',
  'sentriantPS200v1' => 'Sentriant PS200v1',
  'summit1' => 'Summit 1',
  'summit1iSX' => 'Summit 1iSX',
  'summit1iTX' => 'Summit 1iTX',
  'summit2' => 'Summit 2',
  'summit200-24' => 'Summit 200-24',
  'summit200-24fx' => 'Summit 200-24fx',
  'summit200-48' => 'Summit 200-48',
  'summit24' => 'Summit 24',
  'summit24e2SX' => 'Summit 24e2SX',
  'summit24e2TX' => 'Summit 24e2TX',
  'summit24e3' => 'Summit 24e3',
  'summit3' => 'Summit 3',
  'summit300-24' => 'Summit 300-24',
  'summit300-48' => 'Summit 300-48',
  'summit4' => 'Summit 4',
  'summit400-24p' => 'Summit 400-24p',
  'summit400-24t' => 'Summit 400-24t',
  'summit400-48t' => 'Summit 400-48t',
  'summit48' => 'Summit 48',
  'summit48i' => 'Summit 48i',
  'summit48si' => 'Summit 48si',
  'summit4fx' => 'Summit 4fx',
  'summit5i' => 'Summit 5i',
  'summit5iLX' => 'Summit 5iLX',
  'summit5iTX' => 'Summit 5iTX',
  'summit7iSX' => 'Summit 7iSX',
  'summit7iTX' => 'Summit 7iTX',
  'summitPx1' => 'Summit Px1',
  'summitStack' => 'Summit Stack',
  'summitVer2Stack' => 'Summit Stack V2',
  'summitWM100' => 'Summit WM100',
  'summitWM1000' => 'Summit WM1000',
  'summitWM100Lite' => 'Summit WM100Lite',
  'summitWM20' => 'Summit WM20',
  'summitWM200' => 'Summit WM200',
  'summitWM2000' => 'Summit WM2000',
  'summitWM3400' => 'Summit WM3400',
  'summitWM3600' => 'Summit WM3600',
  'summitWM3700' => 'Summit WM3700',
  'summitX150-24p' => 'Summit X150-24p',
  'summitX150-24t' => 'Summit X150-24t',
  'summitX150-24tDC' => 'Summit X150-24tDC',
  'summitX150-24x' => 'Summit X150-24x',
  'summitX150-24xDC' => 'Summit X150-24xDC',
  'summitX150-48p' => 'Summit X150-48p',
  'summitX150-48t' => 'Summit X150-48t',
  'summitX150-48tDC' => 'Summit X150-48tDC',
  'summitX250-24p' => 'Summit X250-24p',
  'summitX250-24t' => 'Summit X250-24t',
  'summitX250-24tDC' => 'Summit X250-24tDC',
  'summitX250-24x' => 'Summit X250-24x',
  'summitX250-24xDC' => 'Summit X250-24xDC',
  'summitX250-48p' => 'Summit X250-48p',
  'summitX250-48t' => 'Summit X250-48t',
  'summitX250-48tDC' => 'Summit X250-48tDC',
  'summitX350-24t' => 'Summit X350-24t',
  'summitX350-48t' => 'Summit X350-48t',
  'summitX440-24p' => 'Summit X440-24p',
  'summitX440-24p-10G' => 'Summit X440-24p-10G',
  'summitX440-24t' => 'Summit X440-24t',
  'summitX440-24t-10G' => 'Summit X440-24t-10G',
  'summitX440-24tdc' => 'Summit X440-24tdc',
  'summitX440-24x' => 'Summit X440-24x',
  'summitX440-24x-10g' => 'Summit X440-24x-10g',
  'summitX440-48p' => 'Summit X440-48p',
  'summitX440-48p-10G' => 'Summit X440-48p-10G',
  'summitX440-48t' => 'Summit X440-48t',
  'summitX440-48t-10G' => 'Summit X440-48t-10G',
  'summitX440-48tdc' => 'Summit X440-48tdc',
  'summitX440-8p' => 'Summit X440-8p',
  'summitX440-8t' => 'Summit X440-8t',
  'summitX440-L2-24t' => 'Summit X440-L2-24t',
  'summitX440-L2-48t' => 'Summit X440-L2-48t',
  'summitX450-24t' => 'Summit X450-24t',
  'summitX450-24x' => 'Summit X450-24x',
  'summitX450a-24t ' => 'Summit X450a-24t ',
  'summitX450a-24tDC' => 'Summit X450a-24tDC',
  'summitX450a-24x' => 'Summit X450a-24x',
  'summitX450a-24xDC' => 'Summit X450a-24xDC',
  'summitX450a-48t' => 'Summit X450a-48t',
  'summitX450a-48tDC' => 'Summit X450a-48tDC',
  'summitX450e-24p ' => 'Summit X450e-24p ',
  'summitX450e-24t' => 'Summit X450e-24t',
  'summitX450e-48p' => 'Summit X450e-48p',
  'summitX450e-48t' => 'Summit X450e-48t',
  'summitX460-24p' => 'Summit X460-24p',
  'summitX460-24t' => 'Summit X460-24t',
  'summitX460-24x' => 'Summit X460-24x',
  'summitX460-48p' => 'Summit X460-48p',
  'summitX460-48t' => 'Summit X460-48t',
  'summitX460-48x' => 'Summit X460-48x',
  'summitX480-24x' => 'Summit X480-24x',
  'summitX480-24x-10G4X' => 'Summit X480-24x-10G4X',
  'summitX480-24x-40G4X' => 'Summit X480-24x-40G4X',
  'summitX480-24x-SS' => 'Summit X480-24x-SS',
  'summitX480-24x-SS128' => 'Summit X480-24x-SS128',
  'summitX480-24x-SSV80' => 'Summit X480-24x-SSV80',
  'summitX480-48t' => 'Summit X480-48t',
  'summitX480-48t-10G4X' => 'Summit X480-48t-10G4X',
  'summitX480-48t-40G4X' => 'Summit X480-48t-40G4X',
  'summitX480-48t-SS' => 'Summit X480-48t-SS',
  'summitX480-48t-SS128' => 'Summit X480-48t-SS128',
  'summitX480-48t-SSV80' => 'Summit X480-48t-SSV80',
  'summitX480-48x' => 'Summit X480-48x',
  'summitX480-48x-10G4X' => 'Summit X480-48x-10G4X',
  'summitX480-48x-40G4X' => 'Summit X480-48x-40G4X',
  'summitX480-48x-SS' => 'Summit X480-48x-SS',
  'summitX480-48x-SS128' => 'Summit X480-48x-SS128',
  'summitX480-48x-SSV80' => 'Summit X480-48x-SSV80',
  'summitX650-24t' => 'Summit X650-24t',
  'summitX650-24t-10G8X' => 'Summit X650-24t-10G8X',
  'summitX650-24t-40G4X' => 'Summit X650-24t-40G4X',
  'summitX650-24t-SS' => 'Summit X650-24t-SS',
  'summitX650-24t-SS256' => 'Summit X650-24t-SS256',
  'summitX650-24t-SS512' => 'Summit X650-24t-SS512',
  'summitX650-24t-SSns' => 'Summit X650-24t-SSns',
  'summitX650-24x' => 'Summit X650-24x',
  'summitX650-24x-10G8X' => 'Summit X650-24x-10G8X',
  'summitX650-24x-40G4X' => 'Summit X650-24x-40G4X',
  'summitX650-24x-SS' => 'Summit X650-24x-SS',
  'summitX650-24x-SS256' => 'Summit X650-24x-SS256',
  'summitX650-24x-SS512' => 'Summit X650-24x-SS512',
  'summitX650-24x-SSns' => 'Summit X650-24x-SSns',
  'summitX670-48x' => 'Summit X670-48x',
  'summitX670v-48t' => 'Summit X670v-48t',
  'summitX670v-48x' => 'Summit X670v-48x',
);

$rewrite_cpqida_hardware = array(
  'other' => 'Other',
  'ida' => 'IDA',
  'idaExpansion' => 'IDA Expansion',
  'ida-2' => 'IDA - 2',
  'smart' => 'SMART',
  'smart-2e' => 'SMART - 2/E',
  'smart-2p' => 'SMART - 2/P',
  'smart-2sl' => 'SMART - 2SL',
  'smart-3100es' => 'Smart - 3100ES',
  'smart-3200' => 'Smart - 3200',
  'smart-2dh' => 'SMART - 2DH',
  'smart-221' => 'Smart - 221',
  'sa-4250es' => 'Smart Array 4250ES',
  'sa-4200' => 'Smart Array 4200',
  'sa-integrated' => 'Integrated Smart Array',
  'sa-431' => 'Smart Array 431',
  'sa-5300' => 'Smart Array 5300',
  'raidLc2' => 'RAID LC2 Controller',
  'sa-5i' => 'Smart Array 5i',
  'sa-532' => 'Smart Array 532',
  'sa-5312' => 'Smart Array 5312',
  'sa-641' => 'Smart Array 641',
  'sa-642' => 'Smart Array 642',
  'sa-6400' => 'Smart Array 6400',
  'sa-6400em' => 'Smart Array 6400 EM',
  'sa-6i' => 'Smart Array 6i',
  'sa-generic' => 'Generic Array',
  'sa-p600' => 'Smart Array P600',
  'sa-p400' => 'Smart Array P400',
  'sa-e200' => 'Smart Array E200',
  'sa-e200i' => 'Smart Array E200i',
  'sa-p400i' => 'Smart Array P400i',
  'sa-p800' => 'Smart Array P800',
  'sa-e500' => 'Smart Array E500',
  'sa-p700m' => 'Smart Array P700m',
  'sa-p212' => 'Smart Array P212',
  'sa-p410' => 'Smart Array P410',
  'sa-p410i' => 'Smart Array P410i',
  'sa-p411' => 'Smart Array P411',
  'sa-b110i' => 'Smart Array B110i SATA RAID',
  'sa-p712m' => 'Smart Array P712m',
  'sa-p711m' => 'Smart Array P711m',
  'sa-p812' => 'Smart Array P812',
  'sw-1210m' => 'StorageWorks 1210m',
  'sa-p220i' => 'Smart Array P220i',
  'sa-p222' => 'Smart Array P222',
  'sa-p420' => 'Smart Array P420',
  'sa-p420i' => 'Smart Array P420i',
  'sa-p421' => 'Smart Array P421',
  'sa-b320i' => 'Smart Array B320i',
  'sa-p822' => 'Smart Array P822',
  'sa-p721m' => 'Smart Array P721m',
  'sa-b120i' => 'Smart Array B120i',
  'hps-1224' => 'HP Storage p1224',
  'hps-1228' => 'HP Storage p1228',
  'hps-1228m' => 'HP Storage p1228m',
  'sa-p822se' => 'Smart Array P822se',
  'hps-1224e' => 'HP Storage p1224e',
  'hps-1228e' => 'HP Storage p1228e',
  'hps-1228em' => 'HP Storage p1228em',
  'sa-p230i' => 'Smart Array P230i',
  'sa-p430i' => 'Smart Array P430i',
  'sa-p430' => 'Smart Array P430',
  'sa-p431' => 'Smart Array P431',
  'sa-p731m' => 'Smart Array P731m',
  'sa-p830i' => 'Smart Array P830i',
  'sa-p830' => 'Smart Array P830',
  'sa-p831' => 'Smart Array P831'
);

$rewrite_liebert_hardware = array(
  // UpsProducts - Liebert UPS Registrations
  'lgpSeries7200'                     => array('name' => 'Series 7200 UPS',                             'type' => 'ups'),
  'lgpUPStationGXT'                   => array('name' => 'UPStationGXT UPS',                            'type' => 'ups'),
  'lgpPowerSureInteractive'           => array('name' => 'PowerSure Interactive UPS',                   'type' => 'ups'),
  'lgpNfinity'                        => array('name' => 'Nfinity UPS',                                 'type' => 'ups'),
  'lgpNpower'                         => array('name' => 'Npower UPS',                                  'type' => 'ups'),
  'lgpGXT2Dual'                       => array('name' => 'GXT2 Dual Inverter',                          'type' => 'ups'),
  'lgpPowerSureInteractive2'          => array('name' => 'PowerSure Interactive 2 UPS',                 'type' => 'ups'),
  'lgpNX'                             => array('name' => 'ENPC Nx UPS',                                 'type' => 'ups'),
  'lgpHiNet'                          => array('name' => 'Hiross HiNet UPS',                            'type' => 'ups'),
  'lgpNXL'                            => array('name' => 'NXL UPS',                                     'type' => 'ups'),
  'lgpSuper400'                       => array('name' => 'Super 400 UPS',                               'type' => 'ups'),
  'lgpSeries600or610'                 => array('name' => 'Series 600/610 UPS',                          'type' => 'ups'),
  'lgpSeries300'                      => array('name' => 'Series 300 UPS',                              'type' => 'ups'),
  'lgpSeries610SMS'                   => array('name' => 'Series 610 Single Module System (SMS) UPS',   'type' => 'ups'),
  'lgpSeries610MMU'                   => array('name' => 'Series 610 Multi Module Unit (MMU) UPS',      'type' => 'ups'),
  'lgpSeries610SCC'                   => array('name' => 'Series 610 System Control Cabinet (SCC) UPS', 'type' => 'ups'),
  'lgpNXr'                            => array('name' => 'APM UPS',                                     'type' => 'ups'),
  // AcProducts - Liebert Environmental Air Conditioning Registrations
  'lgpAdvancedMicroprocessor'         => array('name' => 'Environmental Advanced Microprocessor control',        'type' => 'environment'),
  'lgpStandardMicroprocessor'         => array('name' => 'Environmental Standard Microprocessor control',        'type' => 'environment'),
  'lgpMiniMate2'                      => array('name' => 'Environmental Mini-Mate 2',                            'type' => 'environment'),
  'lgpHimod'                          => array('name' => 'Environmental Himod',                                  'type' => 'environment'),
  'lgpCEMS100orLECS15'                => array('name' => 'Australia Environmental CEMS100 and LECS15 control',   'type' => 'environment'),
  'lgpIcom'                           => array('name' => 'Environmental iCOM control',                           'type' => 'environment'),
  'lgpIcomPA'                         => array('name' => 'iCOM PA (Floor mount) Environmental',                  'type' => 'environment'),
  'lgpIcomXD'                         => array('name' => 'iCOM XD (Rack cooling with compressor) Environmental', 'type' => 'environment'),
  'lgpIcomXP'                         => array('name' => 'iCOM XP (Pumped refrigerant) Environmental',           'type' => 'environment'),
  'lgpIcomSC'                         => array('name' => 'iCOM SC (Chiller) Environmental',                      'type' => 'environment'),
  'lgpIcomCR'                         => array('name' => 'iCOM CR (Computer Row) Environmental',                 'type' => 'environment'),
  // iCOM PA Family - Liebert PA (Floor mount) Environmental Registrations
  'lgpIcomPAtypeDS'                   => array('name' => 'DS Environmental',                            'type' => 'environment'),
  'lgpIcomPAtypeHPM'                  => array('name' => 'HPM Environmental',                           'type' => 'environment'),
  'lgpIcomPAtypeChallenger'           => array('name' => 'Challenger Environmental',                    'type' => 'environment'),
  'lgpIcomPAtypePeX'                  => array('name' => 'PeX Environmental',                           'type' => 'environment'),
  'lgpIcomPAtypeDeluxeSys3'           => array('name' => 'Deluxe System 3 Environmental',               'type' => 'environment'),
  'lgpIcomPAtypeJumboCW'              => array('name' => 'Jumbo CW Environmental',                      'type' => 'environment'),
  'lgpIcomPAtypeDSE'                  => array('name' => 'DSE Environmental',                           'type' => 'environment'),
  'lgpIcomPAtypePEXS'                 => array('name' => 'PEX-S Environmental',                         'type' => 'environment'),
  'lgpIcomPAtypePDX'                  => array('name' => 'PDX - PCW Environmental',                     'type' => 'environment'),
  // iCOM XD Family - Liebert XD Environmental Registrations
  'lgpIcomXDtypeXDF'                  => array('name' => 'XDF Environmental',                           'type' => 'environment'),
  'lgpIcomXDtypeXDFN'                 => array('name' => 'XDFN Environmental',                          'type' => 'environment'),
  'lgpIcomXPtypeXDP'                  => array('name' => 'XDP Environmental',                           'type' => 'environment'),
  'lgpIcomXPtypeXDPCray'              => array('name' => 'XDP Environmental products for Cray',         'type' => 'environment'),
  'lgpIcomXPtypeXDC'                  => array('name' => 'XDC Environmental',                           'type' => 'environment'),
  'lgpIcomXPtypeXDPW'                 => array('name' => 'XDP-W Environmental',                         'type' => 'environment'),
  // iCOM SC Family - Liebert SC (Chillers) Environmental Registrations
  'lgpIcomSCtypeHPC'                  => array('name' => 'HPC Environmental',                           'type' => 'environment'),
  'lgpIcomSCtypeHPCSSmall'            => array('name' => 'HPC-S Small',                                 'type' => 'environment'),
  'lgpIcomSCtypeHPCSLarge'            => array('name' => 'HPC-S Large',                                 'type' => 'environment'),
  'lgpIcomSCtypeHPCR'                 => array('name' => 'HPC-R',                                       'type' => 'environment'),
  'lgpIcomSCtypeHPCM'                 => array('name' => 'HPC-M',                                       'type' => 'environment'),
  'lgpIcomSCtypeHPCL'                 => array('name' => 'HPC-L',                                       'type' => 'environment'),
  'lgpIcomSCtypeHPCW'                 => array('name' => 'HPC-W',                                       'type' => 'environment'),
  // iCOM CR Family - Liebert CR (Computer Row) Environmental Registrations
  'lgpIcomCRtypeCRV'                  => array('name' => 'CRV Environmental',                           'type' => 'environment'),
  // PowerConditioningProducts - Liebert Power Conditioning Registrations
  'lgpPMP'                            => array('name' => 'PMP (Power Monitoring Panel)',                'type' => 'power'),
  'lgpEPMP'                           => array('name' => 'EPMP (Extended Power Monitoring Panel)',      'type' => 'power'),
  // Transfer Switch Products - Liebert Transfer Switch Registrations
  'lgpStaticTransferSwitchEDS'        => array('name' => 'EDS Static Transfer Switch',                  'type' => 'network'),
  'lgpStaticTransferSwitch1'          => array('name' => 'Static Transfer Switch 1',                    'type' => 'network'),
  'lgpStaticTransferSwitch2'          => array('name' => 'Static Transfer Switch 2',                    'type' => 'network'),
  'lgpStaticTransferSwitch2FourPole'  => array('name' => 'Static Transfer Switch 2 - 4Pole',            'type' => 'network'),
  // MultiLink Products - Liebert MultiLink Registrations
  'lgpMultiLinkBasicNotification'     => array('name' => 'MultiLink MLBN device proxy',                 'type' => 'power'),
  // Power Distribution Products - Liebert Power Conditioning Distribution
  'lgpRackPDU'                        => array('name' => 'Rack Power Distribution Products (RPDU)',     'type' => 'pdu'),
  'lgpMPX'                            => array('name' => 'MPX product distribution (PDU)',              'type' => 'pdu'),
  'lgpMPH'                            => array('name' => 'MPH product distribution (PDU)',              'type' => 'pdu'),
  'lgpRackPDU2'                       => array('name' => 'Rack Power Distribution Products 2 (RPDU2)',  'type' => 'pdu'),
  'lgpRPC2kMPX'                       => array('name' => 'MPX product distribution 2 (PDU2)',           'type' => 'pdu'),
  'lgpRPC2kMPH'                       => array('name' => 'MPH product distribution 2 (PDU2)',           'type' => 'pdu'),
  // Combined System Product Registrations
  'lgpPMPandLDMF'                     => array('name' => 'PMP version 4/LDMF',                          'type' => 'power'),
  'lgpPMPgeneric'                     => array('name' => 'PMP version 4',                               'type' => 'power'),
  'lgpPMPonFPC'                       => array('name' => 'PMP version 4 for FPC',                       'type' => 'power'),
  'lgpPMPonPPC'                       => array('name' => 'PMP version 4 for PPC',                       'type' => 'power'),
  'lgpPMPonFDC'                       => array('name' => 'PMP version 4 for FDC',                       'type' => 'power'),
  'lgpPMPonRDC'                       => array('name' => 'PMP version 4 for RDC',                       'type' => 'power'),
  'lgpPMPonEXC'                       => array('name' => 'PMP version 4 for EXC',                       'type' => 'power'),
  'lgpPMPonSTS2'                      => array('name' => 'PMP version 4 for STS2',                      'type' => 'power'),
  'lgpPMPonSTS2PDU'                   => array('name' => 'PMP version 4 for STS2/PDU',                  'type' => 'power'),
);

// rewrite oids used for snmp_translate()
$rewrite_oids = array(
  // JunOS/JunOSe BGP4 V2
  'BGP4-V2-MIB-JUNIPER' => array(
    'jnxBgpM2PeerTable'                 => '.1.3.6.1.4.1.2636.5.1.1.2.1.1',
    'jnxBgpM2PeerState'                 => '.1.3.6.1.4.1.2636.5.1.1.2.1.1.1.2',
    'jnxBgpM2PeerStatus'                => '.1.3.6.1.4.1.2636.5.1.1.2.1.1.1.3',
    'jnxBgpM2PeerInUpdates'             => '.1.3.6.1.4.1.2636.5.1.1.2.6.1.1.1',
    'jnxBgpM2PeerOutUpdates'            => '.1.3.6.1.4.1.2636.5.1.1.2.6.1.1.2',
    'jnxBgpM2PeerInTotalMessages'       => '.1.3.6.1.4.1.2636.5.1.1.2.6.1.1.3',
    'jnxBgpM2PeerOutTotalMessages'      => '.1.3.6.1.4.1.2636.5.1.1.2.6.1.1.4',
    'jnxBgpM2PeerFsmEstablishedTime'    => '.1.3.6.1.4.1.2636.5.1.1.2.4.1.1.1',
    'jnxBgpM2PeerInUpdatesElapsedTime'  => '.1.3.6.1.4.1.2636.5.1.1.2.4.1.1.2',
    'jnxBgpM2PeerLocalAddr'             => '.1.3.6.1.4.1.2636.5.1.1.2.1.1.1.7',
    'jnxBgpM2PeerIdentifier'            => '.1.3.6.1.4.1.2636.5.1.1.2.1.1.1.1',
    'jnxBgpM2PeerRemoteAs'              => '.1.3.6.1.4.1.2636.5.1.1.2.1.1.1.13',
    'jnxBgpM2PeerRemoteAddr'            => '.1.3.6.1.4.1.2636.5.1.1.2.1.1.1.11',
    'jnxBgpM2PeerRemoteAddrType'        => '.1.3.6.1.4.1.2636.5.1.1.2.1.1.1.10',
    'jnxBgpM2PeerIndex'                 => '.1.3.6.1.4.1.2636.5.1.1.2.1.1.1.14',
    'jnxBgpM2PrefixInPrefixesAccepted'  => '.1.3.6.1.4.1.2636.5.1.1.2.6.2.1.8',
    'jnxBgpM2PrefixInPrefixesRejected'  => '.1.3.6.1.4.1.2636.5.1.1.2.6.2.1.9',
    'jnxBgpM2PrefixOutPrefixes'         => '.1.3.6.1.4.1.2636.5.1.1.2.6.2.1.10',
    'jnxBgpM2PrefixCountersSafi'        => '.1.3.6.1.4.1.2636.5.1.1.2.6.2.1.2',
    'jnxBgpM2CfgPeerAdminStatus'        => '.1.3.6.1.4.1.2636.5.1.1.2.8.1.1.1',
  ),
  // Force10 BGP4 V2
  'FORCE10-BGP4-V2-MIB' => array(
    'f10BgpM2PeerTable'                 => '.1.3.6.1.4.1.6027.20.1.2.1.1',
    'f10BgpM2PeerState'                 => '.1.3.6.1.4.1.6027.20.1.2.1.1.1.3',
    'f10BgpM2PeerStatus'                => '.1.3.6.1.4.1.6027.20.1.2.1.1.1.4',
    'f10BgpM2PeerInUpdates'             => '.1.3.6.1.4.1.6027.20.1.2.6.1.1.1',
    'f10BgpM2PeerOutUpdates'            => '.1.3.6.1.4.1.6027.20.1.2.6.1.1.2',
    'f10BgpM2PeerInTotalMessages'       => '.1.3.6.1.4.1.6027.20.1.2.6.1.1.3',
    'f10BgpM2PeerOutTotalMessages'      => '.1.3.6.1.4.1.6027.20.1.2.6.1.1.4',
    'f10BgpM2PeerFsmEstablishedTime'    => '.1.3.6.1.4.1.6027.20.1.2.3.1.1.1',
    'f10BgpM2PeerInUpdatesElapsedTime'  => '.1.3.6.1.4.1.6027.20.1.2.3.1.1.2',
    'f10BgpM2PeerLocalAddr'             => '.1.3.6.1.4.1.6027.20.1.2.1.1.1.8',
    'f10BgpM2PeerIdentifier'            => '.1.3.6.1.4.1.6027.20.1.2.1.1.1.2',
    'f10BgpM2PeerRemoteAs'              => '.1.3.6.1.4.1.6027.20.1.2.1.1.1.14',
    'f10BgpM2PeerRemoteAddr'            => '.1.3.6.1.4.1.6027.20.1.2.1.1.1.12',
    'f10BgpM2PeerRemoteAddrType'        => '.1.3.6.1.4.1.6027.20.1.2.1.1.1.11',
    'f10BgpM2PeerIndex'                 => '.1.3.6.1.4.1.6027.20.1.2.1.1.1.15',
    'f10BgpM2PrefixInPrefixesAccepted'  => '.1.3.6.1.4.1.6027.20.1.2.6.2.1.8',
    'f10BgpM2PrefixInPrefixesRejected'  => '.1.3.6.1.4.1.6027.20.1.2.6.2.1.9',
    'f10BgpM2PrefixOutPrefixes'         => '.1.3.6.1.4.1.6027.20.1.2.6.2.1.10',
    'f10BgpM2PrefixCountersSafi'        => '.1.3.6.1.4.1.6027.20.1.2.6.2.1.2',
    'f10BgpM2CfgPeerAdminStatus'        => '.1.3.6.1.4.1.6027.20.1.2.8.1.1.1'
  ),
  // Arista BGP4 V2
  // Note that these rewrites are only here because of the
  // bgp-peer code; the MIB has the right info.
  // To regenerate this array:
  // SMIPATH=/usr/share/snmp/eos-mibs:/usr/share/snmp/mibs smidump -f identifiers ARISTA-BGP4V2-MIB | awk '{print "'"'"'" $2 "'"'"'", "=>", "'"'"'." $4 "'"'"',"}'
  'ARISTA-BGP4V2-MIB' => array(
    'aristaBgp4V2PeerTable' => '.1.3.6.1.4.1.30065.4.1.1.2',
    'aristaBgp4V2PeerEntry' => '.1.3.6.1.4.1.30065.4.1.1.2.1',
    'aristaBgp4V2PeerInstance' => '.1.3.6.1.4.1.30065.4.1.1.2.1.1',
    'aristaBgp4V2PeerLocalAddrType' => '.1.3.6.1.4.1.30065.4.1.1.2.1.2',
    'aristaBgp4V2PeerLocalAddr' => '.1.3.6.1.4.1.30065.4.1.1.2.1.3',
    'aristaBgp4V2PeerRemoteAddrType' => '.1.3.6.1.4.1.30065.4.1.1.2.1.4',
    'aristaBgp4V2PeerRemoteAddr' => '.1.3.6.1.4.1.30065.4.1.1.2.1.5',
    'aristaBgp4V2PeerLocalPort' => '.1.3.6.1.4.1.30065.4.1.1.2.1.6',
    'aristaBgp4V2PeerLocalAs' => '.1.3.6.1.4.1.30065.4.1.1.2.1.7',
    'aristaBgp4V2PeerLocalIdentifier' => '.1.3.6.1.4.1.30065.4.1.1.2.1.8',
    'aristaBgp4V2PeerRemotePort' => '.1.3.6.1.4.1.30065.4.1.1.2.1.9',
    'aristaBgp4V2PeerRemoteAs' => '.1.3.6.1.4.1.30065.4.1.1.2.1.10',
    'aristaBgp4V2PeerRemoteIdentifier' => '.1.3.6.1.4.1.30065.4.1.1.2.1.11',
    'aristaBgp4V2PeerAdminStatus' => '.1.3.6.1.4.1.30065.4.1.1.2.1.12',
    'aristaBgp4V2PeerState' => '.1.3.6.1.4.1.30065.4.1.1.2.1.13',
    'aristaBgp4V2PeerDescription' => '.1.3.6.1.4.1.30065.4.1.1.2.1.14',
    'aristaBgp4V2PeerErrorsTable' => '.1.3.6.1.4.1.30065.4.1.1.3',
    'aristaBgp4V2PeerErrorsEntry' => '.1.3.6.1.4.1.30065.4.1.1.3.1',
    'aristaBgp4V2PeerLastErrorCodeReceived' => '.1.3.6.1.4.1.30065.4.1.1.3.1.1',
    'aristaBgp4V2PeerLastErrorSubCodeReceived' => '.1.3.6.1.4.1.30065.4.1.1.3.1.2',
    'aristaBgp4V2PeerLastErrorReceivedTime' => '.1.3.6.1.4.1.30065.4.1.1.3.1.3',
    'aristaBgp4V2PeerLastErrorReceivedText' => '.1.3.6.1.4.1.30065.4.1.1.3.1.4',
    'aristaBgp4V2PeerLastErrorReceivedData' => '.1.3.6.1.4.1.30065.4.1.1.3.1.5',
    'aristaBgp4V2PeerLastErrorCodeSent' => '.1.3.6.1.4.1.30065.4.1.1.3.1.6',
    'aristaBgp4V2PeerLastErrorSubCodeSent' => '.1.3.6.1.4.1.30065.4.1.1.3.1.7',
    'aristaBgp4V2PeerLastErrorSentTime' => '.1.3.6.1.4.1.30065.4.1.1.3.1.8',
    'aristaBgp4V2PeerLastErrorSentText' => '.1.3.6.1.4.1.30065.4.1.1.3.1.9',
    'aristaBgp4V2PeerLastErrorSentData' => '.1.3.6.1.4.1.30065.4.1.1.3.1.10',
    'aristaBgp4V2PeerEventTimesTable' => '.1.3.6.1.4.1.30065.4.1.1.4',
    'aristaBgp4V2PeerEventTimesEntry' => '.1.3.6.1.4.1.30065.4.1.1.4.1',
    'aristaBgp4V2PeerFsmEstablishedTime' => '.1.3.6.1.4.1.30065.4.1.1.4.1.1',
    'aristaBgp4V2PeerInUpdatesElapsedTime' => '.1.3.6.1.4.1.30065.4.1.1.4.1.2',
    'aristaBgp4V2PeerConfiguredTimersTable' => '.1.3.6.1.4.1.30065.4.1.1.5',
    'aristaBgp4V2PeerConfiguredTimersEntry' => '.1.3.6.1.4.1.30065.4.1.1.5.1',
    'aristaBgp4V2PeerConnectRetryInterval' => '.1.3.6.1.4.1.30065.4.1.1.5.1.1',
    'aristaBgp4V2PeerHoldTimeConfigured' => '.1.3.6.1.4.1.30065.4.1.1.5.1.2',
    'aristaBgp4V2PeerKeepAliveConfigured' => '.1.3.6.1.4.1.30065.4.1.1.5.1.3',
    'aristaBgp4V2PeerMinASOrigInterval' => '.1.3.6.1.4.1.30065.4.1.1.5.1.4',
    'aristaBgp4V2PeerMinRouteAdverInterval' => '.1.3.6.1.4.1.30065.4.1.1.5.1.5',
    'aristaBgp4V2PeerNegotiatedTimersTable' => '.1.3.6.1.4.1.30065.4.1.1.6',
    'aristaBgp4V2PeerNegotiatedTimersEntry' => '.1.3.6.1.4.1.30065.4.1.1.6.1',
    'aristaBgp4V2PeerHoldTime' => '.1.3.6.1.4.1.30065.4.1.1.6.1.1',
    'aristaBgp4V2PeerKeepAlive' => '.1.3.6.1.4.1.30065.4.1.1.6.1.2',
    'aristaBgp4V2PeerCountersTable' => '.1.3.6.1.4.1.30065.4.1.1.7',
    'aristaBgp4V2PeerCountersEntry' => '.1.3.6.1.4.1.30065.4.1.1.7.1',
    'aristaBgp4V2PeerInUpdates' => '.1.3.6.1.4.1.30065.4.1.1.7.1.1',
    'aristaBgp4V2PeerOutUpdates' => '.1.3.6.1.4.1.30065.4.1.1.7.1.2',
    'aristaBgp4V2PeerInTotalMessages' => '.1.3.6.1.4.1.30065.4.1.1.7.1.3',
    'aristaBgp4V2PeerOutTotalMessages' => '.1.3.6.1.4.1.30065.4.1.1.7.1.4',
    'aristaBgp4V2PeerFsmEstablishedTransitions' => '.1.3.6.1.4.1.30065.4.1.1.7.1.5',
    'aristaBgp4V2PrefixGaugesTable' => '.1.3.6.1.4.1.30065.4.1.1.8',
    'aristaBgp4V2PrefixGaugesEntry' => '.1.3.6.1.4.1.30065.4.1.1.8.1',
    'aristaBgp4V2PrefixGaugesAfi' => '.1.3.6.1.4.1.30065.4.1.1.8.1.1',
    'aristaBgp4V2PrefixGaugesSafi' => '.1.3.6.1.4.1.30065.4.1.1.8.1.2',
    'aristaBgp4V2PrefixInPrefixes' => '.1.3.6.1.4.1.30065.4.1.1.8.1.3',
    'aristaBgp4V2PrefixInPrefixesAccepted' => '.1.3.6.1.4.1.30065.4.1.1.8.1.4',
    'aristaBgp4V2PrefixOutPrefixes' => '.1.3.6.1.4.1.30065.4.1.1.8.1.5',
  ),
  // IPV6-MIB
  'IPV6-MIB' => array(
    'ipv6AddrPfxLength'                 => '.1.3.6.1.2.1.55.1.8.1.2',
    'ipv6AddrType'                      => '.1.3.6.1.2.1.55.1.8.1.3'
  )
);

$rewrite_iftype = array(
  'other' => 'Other',
  'regular1822',
  'hdh1822',
  'ddnX25',
  'rfc877x25',
  'ethernetCsmacd' => 'Ethernet',
  'iso88023Csmacd' => 'Ethernet',
  'iso88024TokenBus',
  'iso88025TokenRing' => 'Token Ring',
  'iso88026Man',
  'starLan' => 'StarLAN',
  'proteon10Mbit',
  'proteon80Mbit',
  'hyperchannel',
  'fddi' => 'FDDI',
  'lapb',
  'sdlc',
  'ds1' => 'DS1',
  'e1' => 'E1',
  'basicISDN' => 'Basic Rate ISDN',
  'primaryISDN' => 'Primary Rate ISDN',
  'propPointToPointSerial' => 'PtP Serial',
  'ppp' => 'PPP',
  'softwareLoopback' => 'Loopback',
  'eon' => 'CLNP over IP',
  'ethernet3Mbit' => 'Ethernet',
  'nsip' => 'XNS over IP',
  'slip' => 'SLIP',
  'ultra' => 'ULTRA technologies',
  'ds3' => 'DS3',
  'sip' => 'SMDS',
  'frameRelay' => 'Frame Relay',
  'rs232' => 'RS232 Serial',
  'para' => 'Parallel',
  'arcnet' => 'Arcnet',
  'arcnetPlus' => 'Arcnet Plus',
  'atm' => 'ATM Cells',
  'miox25',
  'sonet' => 'SONET or SDH',
  'x25ple',
  'iso88022llc',
  'localTalk',
  'smdsDxi',
  'frameRelayService' => 'FRNETSERV-MIB',
  'v35',
  'hssi',
  'hippi',
  'modem' => 'Generic Modem',
  'aal5' => 'AAL5 over ATM',
  'sonetPath' => 'SONET Path',
  'sonetVT' => 'SONET VT',
  'smdsIcip' => 'SMDS InterCarrier Interface',
  'propVirtual' => 'Virtual/Internal',
  'propMultiplexor' => 'proprietary multiplexing',
  'ieee80212' => '100BaseVG',
  'fibreChannel' => 'Fibre Channel',
  'hippiInterface' => 'HIPPI',
  'frameRelayInterconnect' => 'Frame Relay',
  'aflane8023' => 'ATM Emulated LAN for 802.3',
  'aflane8025' => 'ATM Emulated LAN for 802.5',
  'cctEmul' => 'ATM Emulated circuit ',
  'fastEther' => 'Ethernet',
  'isdn' => 'ISDN and X.25',
  'v11' => 'CCITT V.11/X.21',
  'v36' => 'CCITT V.36 ',
  'g703at64k' => 'CCITT G703 at 64Kbps',
  'g703at2mb' => 'Obsolete see DS1-MIB',
  'qllc' => 'SNA QLLC',
  'fastEtherFX' => 'Ethernet',
  'channel' => 'Channel',
  'ieee80211' => 'IEEE802.11 Radio',
  'ibm370parChan' => 'IBM System 360/370 OEMI Channel',
  'escon' => 'IBM Enterprise Systems Connection',
  'dlsw' => 'Data Link Switching',
  'isdns' => 'ISDN S/T',
  'isdnu' => 'ISDN U',
  'lapd' => 'Link Access Protocol D',
  'ipSwitch' => 'IP Switching Objects',
  'rsrb' => 'Remote Source Route Bridging',
  'atmLogical' => 'ATM Logical Port',
  'ds0' => 'Digital Signal Level 0',
  'ds0Bundle' => 'Group of DS0s on the same DS1',
  'bsc' => 'Bisynchronous Protocol',
  'async' => 'Asynchronous Protocol',
  'cnr' => 'Combat Net Radio',
  'iso88025Dtr' => 'ISO 802.5r DTR',
  'eplrs' => 'Ext Pos Loc Report Sys',
  'arap' => 'Appletalk Remote Access Protocol',
  'propCnls' => 'Proprietary Connectionless Protocol',
  'hostPad' => 'CCITT-ITU X.29 PAD Protocol',
  'termPad' => 'CCITT-ITU X.3 PAD Facility',
  'frameRelayMPI' => 'Multiproto Interconnect over FR',
  'x213' => 'CCITT-ITU X213',
  'adsl' => 'ADSL',
  'radsl' => 'Rate-Adapt. DSL',
  'sdsl' => 'SDSL',
  'vdsl' => 'VDSL',
  'iso88025CRFPInt' => 'ISO 802.5 CRFP',
  'myrinet' => 'Myricom Myrinet',
  'voiceEM' => 'Voice recEive and transMit',
  'voiceFXO' => 'Voice FXO',
  'voiceFXS' => 'Voice FXS',
  'voiceEncap' => 'Voice Encapsulation',
  'voiceOverIp' => 'Voice over IP',
  'atmDxi' => 'ATM DXI',
  'atmFuni' => 'ATM FUNI',
  'atmIma' => 'ATM IMA',
  'pppMultilinkBundle' => 'PPP Multilink Bundle',
  'ipOverCdlc' => 'IBM ipOverCdlc',
  'ipOverClaw' => 'IBM Common Link Access to Workstn',
  'stackToStack' => 'IBM stackToStack',
  'virtualIpAddress' => 'IBM VIPA',
  'mpc' => 'IBM multi-protocol channel support',
  'ipOverAtm' => 'IBM ipOverAtm',
  'iso88025Fiber' => 'ISO 802.5j Fiber Token Ring',
  'tdlc  ' => 'IBM twinaxial data link control',
  'gigabitEthernet' => 'Ethernet',
  'hdlc' => 'HDLC',
  'lapf' => 'LAP F',
  'v37' => 'V.37',
  'x25mlp' => 'Multi-Link Protocol',
  'x25huntGroup' => 'X25 Hunt Group',
  'transpHdlc' => 'Transp HDLC',
  'interleave' => 'Interleave channel',
  'fast' => 'Fast channel',
  'ip' => 'IP',
  'docsCableMaclayer' => 'CATV Mac Layer',
  'docsCableDownstream' => 'CATV Downstream interface',
  'docsCableUpstream' => 'CATV Upstream interface',
  'a12MppSwitch' => 'Avalon Parallel Processor',
  'tunnel' => 'Tunnel',
  'coffee' => 'coffee pot',
  'ces' => 'Circuit Emulation Service',
  'atmSubInterface' => 'ATM Sub Interface',
  'l2vlan' => 'L2 VLAN (802.1Q)',
  'l3ipvlan' => 'L3 VLAN (IP)',
  'l3ipxvlan' => 'L3 VLAN (IPX)',
  'digitalPowerline' => 'IP over Power Lines',
  'mediaMailOverIp' => 'Multimedia Mail over IP',
  'dtm' => 'Dynamic Syncronous Transfer Mode',
  'dcn' => 'Data Communications Network',
  'ipForward' => 'IP Forwarding Interface',
  'msdsl' => 'Multi-rate Symmetric DSL',
  'ieee1394' => 'IEEE1394 High Performance Serial Bus',
  'if-gsn--HIPPI-6400 ',
  'dvbRccMacLayer' => 'DVB-RCC MAC Layer',
  'dvbRccDownstream' => 'DVB-RCC Downstream Channel',
  'dvbRccUpstream' => 'DVB-RCC Upstream Channel',
  'atmVirtual' => 'ATM Virtual Interface',
  'mplsTunnel' => 'MPLS Tunnel Virtual Interface',
  'srp' => 'Spatial Reuse Protocol       ',
  'voiceOverAtm' => 'Voice Over ATM',
  'voiceOverFrameRelay' => 'Voice Over FR',
  'idsl' => 'DSL over ISDN',
  'compositeLink' => 'Avici Composite Link Interface',
  'ss7SigLink' => 'SS7 Signaling Link ',
  'propWirelessP2P' => 'Prop. P2P wireless interface',
  'frForward' => 'Frame Forward Interface',
  'rfc1483       ' => 'Multiprotocol over ATM AAL5',
  'usb' => 'USB Interface',
  'ieee8023adLag' => '802.3ad LAg',
  'bgppolicyaccounting' => 'BGP Policy Accounting',
  'frf16MfrBundle' => 'FRF .16 Multilink Frame Relay ',
  'h323Gatekeeper' => 'H323 Gatekeeper',
  'h323Proxy' => 'H323 Proxy',
  'mpls' => 'MPLS ',
  'mfSigLink' => 'Multi-frequency signaling link',
  'hdsl2' => 'High Bit-Rate DSL - 2nd generation',
  'shdsl' => 'Multirate HDSL2',
  'ds1FDL' => 'Facility Data Link 4Kbps on a DS1',
  'pos' => 'Packet over SONET/SDH Interface',
  'dvbAsiIn' => 'DVB-ASI Input',
  'dvbAsiOut' => 'DVB-ASI Output ',
  'plc' => 'Power Line Communtications',
  'nfas' => 'Non Facility Associated Signaling',
  'tr008' => 'TR008',
  'gr303RDT' => 'Remote Digital Terminal',
  'gr303IDT' => 'Integrated Digital Terminal',
  'isup' => 'ISUP',
  'propDocsWirelessMaclayer' => 'Cisco proprietary Maclayer',
  'propDocsWirelessDownstream' => 'Cisco proprietary Downstream',
  'propDocsWirelessUpstream' => 'Cisco proprietary Upstream',
  'hiperlan2' => 'HIPERLAN Type 2 Radio Interface',
  'propBWAp2Mp' => 'PropBroadbandWirelessAccesspt2multipt',
  'sonetOverheadChannel' => 'SONET Overhead Channel',
  'digitalWrapperOverheadChannel' => 'Digital Wrapper',
  'aal2' => 'ATM adaptation layer 2',
  'radioMAC' => 'MAC layer over radio links',
  'atmRadio' => 'ATM over radio links',
  'imt' => 'Inter Machine Trunks',
  'mvl' => 'Multiple Virtual Lines DSL',
  'reachDSL' => 'Long Reach DSL',
  'frDlciEndPt' => 'Frame Relay DLCI End Point',
  'atmVciEndPt' => 'ATM VCI End Point',
  'opticalChannel' => 'Optical Channel',
  'opticalTransport' => 'Optical Transport',
  'propAtm' => 'Proprietary ATM',
  'voiceOverCable' => 'Voice Over Cable',
  'infiniband' => 'Infiniband',
  'teLink' => 'TE Link',
  'q2931' => 'Q.2931',
  'virtualTg' => 'Virtual Trunk Group',
  'sipTg' => 'SIP Trunk Group',
  'sipSig' => 'SIP Signaling',
  'docsCableUpstreamChannel' => 'CATV Upstream Channel',
  'econet' => 'Acorn Econet',
  'pon155' => 'FSAN 155Mb Symetrical PON',
  'pon622' => 'FSAN 622Mb Symetrical PON',
  'bridge' => 'Transparent bridge interface',
  'linegroup' => 'Interface common to multiple lines',
  'voiceEMFGD' => 'voice E&M Feature Group D',
  'voiceFGDEANA' => 'voice FGD Exchange Access North American',
  'voiceDID' => 'voice Direct Inward Dialing',
  'mpegTransport' => 'MPEG transport interface',
  'sixToFour' => '6to4 interface',
  'gtp' => 'GTP (GPRS Tunneling Protocol)',
  'pdnEtherLoop1' => 'Paradyne EtherLoop 1',
  'pdnEtherLoop2' => 'Paradyne EtherLoop 2',
  'opticalChannelGroup' => 'Optical Channel Group',
  'homepna' => 'HomePNA ITU-T G.989',
  'gfp' => 'GFP',
  'ciscoISLvlan' => 'ISL VLAN',
  'actelisMetaLOOP' => 'MetaLOOP',
  'fcipLink' => 'FCIP Link ',
  'rpr' => 'Resilient Packet Ring Interface Type',
  'qam' => 'RF Qam Interface',
  'lmp' => 'Link Management Protocol',
  'cblVectaStar' => 'Cambridge Broadband Networks Limited VectaStar',
  'docsCableMCmtsDownstream' => 'CATV Modular CMTS Downstream Interface',
  'adsl2' => 'Asymmetric Digital Subscriber Loop Version 2 ',
  'macSecControlledIF' => 'MACSecControlled ',
  'macSecUncontrolledIF' => 'MACSecUncontrolled',
  'aviciOpticalEther' => 'Avici Optical Ethernet Aggregate',
  'atmbond' => 'atmbond',
  'voiceFGDOS' => 'voice FGD Operator Services',
  'mocaVersion1' => 'MultiMedia over Coax Alliance (MoCA) Interface',
  'ieee80216WMAN' => 'IEEE 802.16 WMAN interface',
  'adsl2plus' => 'Asymmetric Digital Subscriber Loop Version 2, ',
  'dvbRcsMacLayer' => 'DVB-RCS MAC Layer',
  'dvbTdm' => 'DVB Satellite TDM',
  'dvbRcsTdma' => 'DVB-RCS TDMA',
  'x86Laps' => 'LAPS based on ITU-T X.86/Y.1323',
  'wwanPP' => '3GPP WWAN',
  'wwanPP2' => '3GPP2 WWAN',
  'voiceEBS' => 'voice P-phone EBS physical interface',
  'ifPwType' => 'Pseudowire',
  'ilan' => 'Internal LAN on a bridge per IEEE 802.1ap',
  'pip' => 'Provider Instance Port IEEE 802.1ah PBB',
  'aluELP' => 'A-Lu ELP',
  'gpon' => 'GPON',
  'vdsl2' => 'VDSL2)',
  'capwapDot11Profile' => 'WLAN Profile',
  'capwapDot11Bss' => 'WLAN BSS',
  'capwapWtpVirtualRadio' => 'WTP Virtual Radio',
  'bits' => 'bitsport',
  'docsCableUpstreamRfPort' => 'DOCSIS CATV Upstream RF',
  'cableDownstreamRfPort' => 'CATV Downstream RF',
  'vmwareVirtualNic' => 'VMware Virtual NIC',
  'ieee802154' => 'IEEE 802.15.4 WPAN',
  'otnOdu' => 'OTN ODU',
  'otnOtu' => 'OTN OTU',
  'ifVfiType' => 'VPLS Forwarding Instance',
  'g9981' => 'G.998.1 Bonded',
  'g9982' => 'G.998.2 Bonded',
  'g9983' => 'G.998.3 Bonded',
  'aluEpon' => 'EPON',
  'aluEponOnu' => 'EPON ONU',
  'aluEponPhysicalUni' => 'EPON Physical UNI',
  'aluEponLogicalLink' => 'EPON Logical Link',
  'aluGponOnu' => 'GPON ONU',
  'aluGponPhysicalUni' => 'GPON Physical UNI',
  'vmwareNicTeam' => 'VMware NIC Team',
);

$rewrite_ifname = array(
  'ether' => 'Ether',
  'gig' => 'Gig',
  'fast' => 'Fast',
  'ten' => 'Ten',
  '-802.1q vlan subif' => '',
  '-802.1q' => '',
  'bvi' => 'BVI',
  'vlan' => 'Vlan',
  'ether' => 'Ether',
  'tunnel' => 'Tunnel',
  'serial' => 'Serial',
  '-aal5 layer' => ' aal5',
  'null' => 'Null',
  'atm' => 'ATM',
  'port-channel' => 'Port-Channel',
  'dial' => 'Dial',
  'hp procurve switch software loopback interface' => 'Loopback Interface',
  'uniping server solution v3/sms' => '',
  'control plane interface' => 'Control Plane',
  'loopback' => 'Loopback',
  '802.1q encapsulation tag' => 'Vlan',
);

$rewrite_ifname_regexp = array(
  '/Nortel .* Module - /i' => '',
  '/Baystack .* - /i' => '',
  '/DEC [a-z\d]+ PCI /i' => '',
  //'/^APC /' => '',
);

$rewrite_shortif = array(
  'tengigabitethernet' => 'Te',
  'tengige' => 'Te',
  'gigabitethernet' => 'Gi',
  'gigabit ethernet' => 'Gi',
  'fastethernet' => 'Fa',
  'fast ethernet' => 'Fa',
  'ethernet' => 'Et',
  'fortygige' => 'Fo',
  'management' => 'Mgmt',
  'serial' => 'Se',
  'pos' => 'Pos',
  'port-channel' => 'Po',
  'bundle-ether' => 'BE',
  'atm' => 'Atm',
  'null' => 'Null',
  'loopback' => 'Lo',
  'dialer' => 'Di',
  'vlan' => 'Vlan',
  'tunnel' => 'Tu',
  'serviceinstance' => 'SI',
  'dwdm' => 'DWDM',
);

$rewrite_adslLineType = array(
  'noChannel'          => 'No Channel',
  'fastOnly'           => 'Fastpath',
  'interleavedOnly'    => 'Interleaved',
  'fastOrInterleaved'  => 'Fast/Interleaved',
  'fastAndInterleaved' => 'Fast+Interleaved'
);

$rewrite_hrDevice = array (
  'GenuineIntel:' => '',
  'AuthenticAMD:' => '',
  'Intel(R)' => '',
  'CPU' => '',
  '(R)' => '',
  '  ' => ' ',
);

// Rewrite functions

/**
 * Return model array, based on device sysObjectID
 *
 * @param	array	 $device          Device array required keys -> os, sysObjectID
 * @param	string $sysObjectID_new If passed, than use "new" sysObjectID instead from device array
 * @return array                  Model array
 */
function get_model_array($device, $sysObjectID_new = NULL)
{
  if (isset($GLOBALS['config']['os'][$device['os']]['model']))
  {
    $model  = $GLOBALS['config']['os'][$device['os']]['model'];
    $models = $GLOBALS['config']['model'][$model];
    if ($sysObjectID_new && preg_match('/^\.\d[\d\.]+$/', $sysObjectID_new))
    {
      $sysObjectID = $sysObjectID_new;
    }
    else if (preg_match('/^\.\d[\d\.]+$/', $device['sysObjectID']))
    {
      $sysObjectID = $device['sysObjectID'];
    } else {
      // Just random non empty string
      $sysObjectID = 'WRONG_ID_3948ffakc';
    }
    if (isset($models[$sysObjectID]))
    {
      // Exactly match
      return $models[$sysObjectID];
    }
    krsort($GLOBALS['config']['model'][$model]); // Resort array by key with high to low order!
    foreach ($GLOBALS['config']['model'][$model] as $key => $entry)
    {
      if (strpos($sysObjectID, $key) === 0)
      {
        return $entry;
        break;
      }
    }
  }
}

/**
 * Rewrites device hardware based on device os/sysObjectID and hw definitions
 *
 * @param array $device Device array required keys -> os, sysObjectID
 * @param string $sysObjectID_new If passed, than use "new" sysObjectID instead from device array
 * @return string Device hw name or empty string
 */
function rewrite_definition_hardware($device, $sysObjectID_new = NULL)
{
  $model_array = get_model_array($device, $sysObjectID_new);
  if (is_array($model_array) && isset($model_array['name']))
  {
    return $model_array['name'];
  }
}

/**
 * Rewrites device type based on device os/sysObjectID and hw definitions
 *
 * @param array $device Device array required keys -> os, sysObjectID
 * @param string $sysObjectID_new If passed, than use "new" sysObjectID instead from device array
 * @return string Device type or empty string
 */
function rewrite_definition_type($device, $sysObjectID_new = NULL)
{
  $model_array = get_model_array($device, $sysObjectID_new);
  if (is_array($model_array) && isset($model_array['type']))
  {
    return $model_array['type'];
  }
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function rewrite_extreme_hardware($hardware)
{
  global $rewrite_extreme_hardware;

  $hardware = $rewrite_extreme_hardware[$hardware];

  return ($hardware);
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function rewrite_cpqida_hardware($hardware)
{
  global $rewrite_cpqida_hardware;

  $hardware = array_str_replace($rewrite_cpqida_hardware, $hardware);

  return ($hardware);
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function rewrite_liebert_hardware($hardware)
{
  global $rewrite_liebert_hardware;

  if (isset($rewrite_liebert_hardware[$hardware]))
  {
    $hardware = $rewrite_liebert_hardware[$hardware]['name'];
  }

  return ($hardware);
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function rewrite_junose_hardware($hardware)
{
  global $rewrite_junos_hardware;

  $hardware = $rewrite_junos_hardware[$hardware];
  return ($hardware);
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function rewrite_junos_hardware($hardware)
{
  global $rewrite_junos_hardware;

  $hardware = $rewrite_junos_hardware[$hardware];
  return ($hardware);
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function rewrite_breeze_type($type)
{
  $type = strtolower($type);
  if (isset($GLOBALS['rewrite_breeze_type'][$type]))
  {
    return $GLOBALS['rewrite_breeze_type'][$type];
  } else {
    return strtoupper($type);
  }
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function rewrite_unix_hardware($descr, $hw = NULL)
{
  $hardware = (!empty($hw) ? trim($hw): 'Generic');

  if     (preg_match('/i[3456]86/i',    $descr)) { $hardware .= ' x86 [32bit]'; }
  elseif (preg_match('/x86_64|amd64/i', $descr)) { $hardware .= ' x86 [64bit]'; }
  elseif (stristr($descr, 'ppc'))     { $hardware .= ' PPC [32bit]'; }
  elseif (stristr($descr, 'sparc32')) { $hardware .= ' SPARC [32bit]'; }
  elseif (stristr($descr, 'sparc64')) { $hardware .= ' SPARC [32bit]'; }
  elseif (stristr($descr, 'mips64'))  { $hardware .= ' MIPS [64bit]'; }
  elseif (stristr($descr, 'mips'))    { $hardware .= ' MIPS [32bit]'; }
  elseif (stristr($descr, 'armv5'))   { $hardware .= ' ARMv5'; }
  elseif (stristr($descr, 'armv6'))   { $hardware .= ' ARMv6'; }
  elseif (stristr($descr, 'armv7'))   { $hardware .= ' ARMv7'; }
  elseif (stristr($descr, 'armv'))    { $hardware .= ' ARM'; }

  return ($hardware);
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function rewrite_ftos_vlanid($device, $ifindex)
{
  // damn DELL use them one known indexes
  //dot1qVlanStaticName.1107787777 = Vlan 1
  //dot1qVlanStaticName.1107787998 = mgmt
  $ftos_vlan = dbFetchCell('SELECT ifName FROM `ports` WHERE `device_id` = ? AND `ifIndex` = ?', array($device['device_id'], $ifindex));
  list(,$vlanid) = explode(' ', $ftos_vlan);
  return $vlanid;
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function rewrite_iftype($type)
{
  $type = array_key_replace($GLOBALS['rewrite_iftype'], $type);
  return $type;
}

// NOTE. For graphs use $escape = FALSE
// TESTME needs unit testing
function rewrite_ifname($inf, $escape = TRUE)
{
  //$inf = strtolower($inf); // ew. -tom
  $inf = array_str_replace($GLOBALS['rewrite_ifname'], $inf);
  $inf = array_preg_replace($GLOBALS['rewrite_ifname_regexp'], $inf);
  if ($escape) { $inf = escape_html($inf); } // By default use htmlentities

  return $inf;
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function rewrite_adslLineType($adslLineType)
{
  global $rewrite_adslLineType;

  if (isset($rewrite_adslLineType[$adslLineType])) { $adslLineType = $rewrite_adslLineType[$adslLineType]; }
  return($adslLineType);
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function rewrite_hrDevice($dev)
{
  global $rewrite_hrDevice;

  $dev = array_str_replace($rewrite_hrDevice, $dev);
  $dev = preg_replace("/\ +/"," ", $dev);
  $dev = trim($dev);

  return $dev;
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function short_hostname($hostname, $len = NULL, $escape = TRUE)
{
  $len = (is_numeric($len) ? (int)$len : (int)$GLOBALS['config']['short_hostname']['length']);

  if (function_exists('custom_shorthost'))
  {
    $short_hostname = custom_shorthost($hostname, $len);
  }
  else if (function_exists('custom_short_hostname'))
  {
    $short_hostname = custom_short_hostname($hostname, $len);
  } else {

    if (get_ip_version($hostname)) { return $hostname; } // If hostname is IP address, always return full hostname

    $parts = explode('.', $hostname);
    $short_hostname = $parts[0];
    $i = 1;
    while ($i < count($parts) && strlen($short_hostname.'.'.$parts[$i]) < $len)
    {
      $short_hostname = $short_hostname.'.'.$parts[$i];
      $i++;
    }
  }
  if ($escape) { $short_hostname = escape_html($short_hostname); }

  return $short_hostname;
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function short_port_descr($descr, $len = NULL, $escape = TRUE)
{
  $len = (is_numeric($len) ? (int)$len : (int)$GLOBALS['config']['short_port_descr']['length']);

  if (function_exists('custom_short_port_descr'))
  {
    $descr = custom_short_port_descr($descr, $len);
  } else {

    list($descr) = explode("(", $descr);
    list($descr) = explode("[", $descr);
    list($descr) = explode("{", $descr);
    list($descr) = explode("|", $descr);
    list($descr) = explode("<", $descr);
    $descr = truncate(trim($descr), $len, '');
  }
  if ($escape) { $descr = escape_html($descr); }

  return $descr;
}

// NOTE. For graphs use $escape = FALSE
// NOTE. short_ifname() differs from short_port_descr()
// short_ifname('FastEternet0/10') == 'Fa0/10'
// DOCME needs phpdoc block
// TESTME needs unit testing
function short_ifname($if, $len = NULL, $escape = TRUE)
{
  $len = (is_numeric($len) ? (int)$len : FALSE);

  $if = rewrite_ifname($if, $escape);
  // $if = strtolower($if);
  $if = array_str_replace($GLOBALS['rewrite_shortif'], $if);
  if ($len) { $if = truncate($if, $len, ''); }

  return $if;
}

// DOCME needs phpdoc block
function rewrite_entity_name($string)
{
  $string = str_replace("Distributed Forwarding Card", "DFC", $string);
  $string = preg_replace("/7600 Series SPA Interface Processor-/", "7600 SIP-", $string);
  $string = preg_replace("/Rev\.\ [0-9\.]+\ /", "", $string);
  $string = preg_replace("/12000 Series Performance Route Processor/", "12000 PRP", $string);
  $string = preg_replace("/^12000/", "", $string);
  $string = preg_replace("/Gigabit Ethernet/", "GigE", $string);
  $string = preg_replace("/^ASR1000\ /", "", $string);
  //$string = str_replace("Routing Processor", "RP", $string);
  //$string = str_replace("Route Processor", "RP", $string);
  //$string = str_replace("Switching Processor", "SP", $string);
  $string = str_replace("Sub-Module", "Module ", $string);
  $string = str_replace("DFC Card", "DFC", $string);
  $string = str_replace("Centralized Forwarding Card", "CFC", $string);
  $string = str_replace(array('fan-tray'), 'Fan Tray', $string);
  $string = str_replace(array('Temp: ', 'CPU of ', 'CPU ', '(TM)', '(R)', '(r)'), '', $string);
  $string = str_replace('GenuineIntel Intel', 'Intel', $string);
  $string = preg_replace("/(HP \w+) Switch/", "$1", $string);
  $string = preg_replace("/power[ -]supply( \d+)?(?: (?:module|sensor))?/i", "Power Supply$1", $string);
  $string = preg_replace("/([Vv]oltage|[Tt]ransceiver|[Pp]ower|[Cc]urrent|[Tt]emperature|[Ff]an|input|fail)\ [Ss]ensor/", "$1", $string);
  $string = preg_replace("/^(temperature|voltage|current|power)s?\ /", "", $string);
  $string = preg_replace('/\s{2,}/', ' ', $string);
  $string = preg_replace('/([a-z])([A-Z]{2,})/', '$1 $2', $string); // turn "fixedAC" into "fixed AC"

  return trim($string);
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function rewrite_storage($string)
{
  $string = preg_replace('/.*mounted on: (.*)/', "\\1", $string);                 // JunOS
  $string = preg_replace("/(.*), type: (.*), dev: (.*)/", "\\1", $string);        // FreeBSD: '/mnt/Media, type: zfs, dev: Media'
  $string = preg_replace("/(.*) Label:(.*) Serial Number (.*)/", "\\1", $string); // Windows: E:\ Label:Large Space Serial Number 26ad0d98

  return trim($string);
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function rewrite_location($location)
{
  global $config, $attribs;

  $location = str_replace(array('\"', '"'), '', $location);

  // Allow override sysLocation from DB.
  if ($attribs['override_sysLocation_bool'])
  {
    $new_location = $attribs['override_sysLocation_string'];
    $by = 'DB override';
  }
  // This will call a user-defineable function to rewrite the location however the user wants.
  if (!isset($new_location) && function_exists('custom_rewrite_location'))
  {
    $new_location = custom_rewrite_location($location);
    $by = 'function custom_rewrite_location()';
  }
  // This uses a statically defined array to map locations.
  if (!isset($new_location))
  {
    if (isset($config['location']['map'][$location]))
    {
      $new_location = $config['location']['map'][$location];
      $by = '$config[\'location\'][\'map\']';
    }
    else if (isset($config['location']['map_regexp']))
    {
      foreach ($config['location']['map_regexp'] as $pattern => $entry)
      {
        if (preg_match($pattern, $location))
        {
          $new_location = $entry;
          $by = '$config[\'location\'][\'map_regexp\']';
          break; // stop foreach
        }
      }
    }
  }

  if (isset($new_location))
  {
    print_debug("sysLocation rewritten from '$location' to '$new_location' by $by.");
    $location = $new_location;
  }
  return $location;
}

// Underlying rewrite functions
// DOCME needs phpdoc block
// TESTME needs unit testing
function array_key_replace($array, $string)
{
  if (array_key_exists($string, $array))
  {
    $string = $array[$string];
  }
  return $string;
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function array_str_replace($array, $string)
{
  foreach ($array as $search => $replace)
  {
    $string = str_ireplace($search, $replace, $string);
  }

  return $string;
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function array_preg_replace($array, $string)
{
  foreach ($array as $search => $replace)
  {
    $string = preg_replace($search, $replace, $string);
  }

  return $string;
}

// FIXME move somewhere else? definitions?
$countries = array(
  'AF' => 'Afghanistan',
  'AX' => 'Åland Islands',
  'AL' => 'Albania',
  'DZ' => 'Algeria',
  'AS' => 'American Samoa',
  'AD' => 'Andorra',
  'AO' => 'Angola',
  'AI' => 'Anguilla',
  'AQ' => 'Antarctica',
  'AG' => 'Antigua and Barbuda',
  'AR' => 'Argentina',
  'AM' => 'Armenia',
  'AW' => 'Aruba',
  'AU' => 'Australia',
  'AT' => 'Austria',
  'AZ' => 'Azerbaijan',
  'BS' => 'Bahamas',
  'BH' => 'Bahrain',
  'BD' => 'Bangladesh',
  'BB' => 'Barbados',
  'BY' => 'Belarus',
  'BE' => 'Belgium',
  'BZ' => 'Belize',
  'BJ' => 'Benin',
  'BM' => 'Bermuda',
  'BT' => 'Bhutan',
  'BO' => 'Bolivia, Plurinational State of',
  'BA' => 'Bosnia and Herzegovina',
  'BW' => 'Botswana',
  'BV' => 'Bouvet Island',
  'BR' => 'Brazil',
  'IO' => 'British Indian Ocean Territory',
  'BN' => 'Brunei Darussalam',
  'BG' => 'Bulgaria',
  'BF' => 'Burkina Faso',
  'BI' => 'Burundi',
  'KH' => 'Cambodia',
  'CM' => 'Cameroon',
  'CA' => 'Canada',
  'CV' => 'Cape Verde',
  'KY' => 'Cayman Islands',
  'CF' => 'Central African Republic',
  'TD' => 'Chad',
  'CL' => 'Chile',
  'CN' => 'China',
  'CX' => 'Christmas Island',
  'CC' => 'Cocos (Keeling) Islands',
  'CO' => 'Colombia',
  'KM' => 'Comoros',
  'CG' => 'Congo',
  'CD' => 'Congo, the Democratic Republic of the',
  'CK' => 'Cook Islands',
  'CR' => 'Costa Rica',
  'CI' => "Côte d'Ivoire",
  'HR' => 'Croatia',
  'CU' => 'Cuba',
  'CY' => 'Cyprus',
  'CZ' => 'Czech Republic',
  'DK' => 'Denmark',
  'DJ' => 'Djibouti',
  'DM' => 'Dominica',
  'DO' => 'Dominican Republic',
  'EC' => 'Ecuador',
  'EG' => 'Egypt',
  'SV' => 'El Salvador',
  'GQ' => 'Equatorial Guinea',
  'ER' => 'Eritrea',
  'EE' => 'Estonia',
  'ET' => 'Ethiopia',
  'FK' => 'Falkland Islands (Malvinas)',
  'FO' => 'Faroe Islands',
  'FJ' => 'Fiji',
  'FI' => 'Finland',
  'FR' => 'France',
  'GF' => 'French Guiana',
  'PF' => 'French Polynesia',
  'TF' => 'French Southern Territories',
  'GA' => 'Gabon',
  'GM' => 'Gambia',
  'GE' => 'Georgia',
  'DE' => 'Germany',
  'GH' => 'Ghana',
  'GI' => 'Gibraltar',
  'GR' => 'Greece',
  'GL' => 'Greenland',
  'GD' => 'Grenada',
  'GP' => 'Guadeloupe',
  'GU' => 'Guam',
  'GT' => 'Guatemala',
  'GG' => 'Guernsey',
  'GN' => 'Guinea',
  'GW' => 'Guinea-Bissau',
  'GY' => 'Guyana',
  'HT' => 'Haiti',
  'HM' => 'Heard Island and McDonald Islands',
  'VA' => 'Holy See (Vatican City State)',
  'HN' => 'Honduras',
  'HK' => 'Hong Kong',
  'HU' => 'Hungary',
  'IS' => 'Iceland',
  'IN' => 'India',
  'ID' => 'Indonesia',
  'IR' => 'Iran, Islamic Republic of',
  'IQ' => 'Iraq',
  'IE' => 'Ireland',
  'IM' => 'Isle of Man',
  'IL' => 'Israel',
  'IT' => 'Italy',
  'JM' => 'Jamaica',
  'JP' => 'Japan',
  'JE' => 'Jersey',
  'JO' => 'Jordan',
  'KZ' => 'Kazakhstan',
  'KE' => 'Kenya',
  'KI' => 'Kiribati',
  'KP' => "Korea, Democratic People's Republic of",
  'KR' => 'Korea, Republic of',
  'KW' => 'Kuwait',
  'KG' => 'Kyrgyzstan',
  'LA' => "Lao People's Democratic Republic",
  'LV' => 'Latvia',
  'LB' => 'Lebanon',
  'LS' => 'Lesotho',
  'LR' => 'Liberia',
  'LY' => 'Libyan Arab Jamahiriya',
  'LI' => 'Liechtenstein',
  'LT' => 'Lithuania',
  'LU' => 'Luxembourg',
  'MO' => 'Macao',
  'MK' => 'Macedonia, the former Yugoslav Republic of',
  'MG' => 'Madagascar',
  'MW' => 'Malawi',
  'MY' => 'Malaysia',
  'MV' => 'Maldives',
  'ML' => 'Mali',
  'MT' => 'Malta',
  'MH' => 'Marshall Islands',
  'MQ' => 'Martinique',
  'MR' => 'Mauritania',
  'MU' => 'Mauritius',
  'YT' => 'Mayotte',
  'MX' => 'Mexico',
  'FM' => 'Micronesia, Federated States of',
  'MD' => 'Moldova, Republic of',
  'MC' => 'Monaco',
  'MN' => 'Mongolia',
  'ME' => 'Montenegro',
  'MS' => 'Montserrat',
  'MA' => 'Morocco',
  'MZ' => 'Mozambique',
  'MM' => 'Myanmar',
  'NA' => 'Namibia',
  'NR' => 'Nauru',
  'NP' => 'Nepal',
  'NL' => 'Netherlands',
  'AN' => 'Netherlands Antilles',
  'NC' => 'New Caledonia',
  'NZ' => 'New Zealand',
  'NI' => 'Nicaragua',
  'NE' => 'Niger',
  'NG' => 'Nigeria',
  'NU' => 'Niue',
  'NF' => 'Norfolk Island',
  'MP' => 'Northern Mariana Islands',
  'NO' => 'Norway',
  'OM' => 'Oman',
  'PK' => 'Pakistan',
  'PW' => 'Palau',
  'PS' => 'Palestinian Territory, Occupied',
  'PA' => 'Panama',
  'PG' => 'Papua New Guinea',
  'PY' => 'Paraguay',
  'PE' => 'Peru',
  'PH' => 'Philippines',
  'PN' => 'Pitcairn',
  'PL' => 'Poland',
  'PT' => 'Portugal',
  'PR' => 'Puerto Rico',
  'QA' => 'Qatar',
  'RE' => 'Réunion',
  'RO' => 'Romania',
  'RU' => 'Russian Federation',
  'RW' => 'Rwanda',
  'BL' => 'Saint Barthélemy',
  'SH' => 'Saint Helena',
  'KN' => 'Saint Kitts and Nevis',
  'LC' => 'Saint Lucia',
  'MF' => 'Saint Martin (French part)',
  'PM' => 'Saint Pierre and Miquelon',
  'VC' => 'Saint Vincent and the Grenadines',
  'WS' => 'Samoa',
  'SM' => 'San Marino',
  'ST' => 'Sao Tome and Principe',
  'SA' => 'Saudi Arabia',
  'SN' => 'Senegal',
  'RS' => 'Serbia',
  'SC' => 'Seychelles',
  'SL' => 'Sierra Leone',
  'SG' => 'Singapore',
  'SK' => 'Slovakia',
  'SI' => 'Slovenia',
  'SB' => 'Solomon Islands',
  'SO' => 'Somalia',
  'ZA' => 'South Africa',
  'GS' => 'South Georgia and the South Sandwich Islands',
  'ES' => 'Spain',
  'LK' => 'Sri Lanka',
  'SD' => 'Sudan',
  'SR' => 'Suriname',
  'SJ' => 'Svalbard and Jan Mayen',
  'SZ' => 'Swaziland',
  'SE' => 'Sweden',
  'CH' => 'Switzerland',
  'SY' => 'Syrian Arab Republic',
  'TW' => 'Taiwan, Province of China',
  'TJ' => 'Tajikistan',
  'TZ' => 'Tanzania, United Republic of',
  'TH' => 'Thailand',
  'TL' => 'Timor-Leste',
  'TG' => 'Togo',
  'TK' => 'Tokelau',
  'TO' => 'Tonga',
  'TT' => 'Trinidad and Tobago',
  'TN' => 'Tunisia',
  'TR' => 'Turkey',
  'TM' => 'Turkmenistan',
  'TC' => 'Turks and Caicos Islands',
  'TV' => 'Tuvalu',
  'UG' => 'Uganda',
  'UA' => 'Ukraine',
  'AE' => 'United Arab Emirates',
  'GB' => 'United Kingdom',
  'US' => 'United States',
  'UM' => 'United States Minor Outlying Islands',
  'UY' => 'Uruguay',
  'UZ' => 'Uzbekistan',
  'VU' => 'Vanuatu',
  'VE' => 'Venezuela, Bolivarian Republic of',
  'VN' => 'Viet Nam',
  'VG' => 'Virgin Islands, British',
  'VI' => 'Virgin Islands, U.S.',
  'WF' => 'Wallis and Futuna',
  'EH' => 'Western Sahara',
  'YE' => 'Yemen',
  'ZM' => 'Zambia',
  'ZW' => 'Zimbabwe'
);

// DOCME needs phpdoc block
// TESTME needs unit testing
function country_from_code($code)
{
  global $countries;

  $code = strtoupper(trim($code));
  if (array_key_exists($code, $countries))
  {
    return $countries[$code];
  }
  return "Unknown";
}

// EOF
