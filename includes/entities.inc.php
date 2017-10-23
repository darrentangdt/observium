<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage functions
 * @author     Adam Armstrong <adama@observium.org>
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2017 Observium Limited
 *
 */

/**
 *
 * Get attribute value for entity
 *
 * @param string $entity_type
 * @param mixed $entity_id
 * @param string $attrib_type
 * @return string
 */
function get_entity_attrib($entity_type, $entity_id, $attrib_type)
{
  if (is_array($entity_id))
  {
    // Passed entity array, instead id
    $translate = entity_type_translate_array($entity_type);
    $entity_id = $entity_id[$translate['id_field']];
  }
  if (!$entity_id) { return NULL; }

  if (isset($GLOBALS['cache']['entity_attribs'][$entity_type][$entity_id][$attrib_type]))
  {
    return $GLOBALS['cache']['entity_attribs'][$entity_type][$entity_id][$attrib_type];
  }

  if ($entity_type == 'device' && get_db_version() < 240)
  {
    // CLEANME. Compatibility, remove in r8000, but not before CE 0.16.1 (Oct 7, 2015)
    if ($row = dbFetchRow("SELECT `attrib_value` FROM `devices_attribs` WHERE `device_id` = ? AND `attrib_type` = ?", array($entity_id, $attrib_type)))
    {
      return $row['attrib_value'];
    }
  }
  else if ($row = dbFetchRow("SELECT `attrib_value` FROM `entity_attribs` WHERE `entity_type` = ? AND `entity_id` = ? AND `attrib_type` = ?", array($entity_type, $entity_id, $attrib_type)))
  {
    return $row['attrib_value'];
  }

  return NULL;
}

/**
 *
 * Get all attributes for entity
 *
 * @param string $entity_type
 * @param mixed $entity_id
 * @return array
 */
function get_entity_attribs($entity_type, $entity_id)
{
  if (is_array($entity_id))
  {
    // Passed entity array, instead id
    $translate = entity_type_translate_array($entity_type);
    $entity_id = $entity_id[$translate['id_field']];
  }
  if (!$entity_id) { return NULL; }

  if (!isset($GLOBALS['cache']['entity_attribs'][$entity_type][$entity_id]))
  {
    $attribs = array();
    if ($entity_type == 'device' && get_db_version() < 240)
    {
      // CLEANME. Compatibility, remove in r8000, but not before CE 0.16.1 (Oct 7, 2015)
      foreach (dbFetchRows("SELECT * FROM `devices_attribs` WHERE `device_id` = ?", array($entity_id)) as $entry)
      {
        $attribs[$entry['attrib_type']] = $entry['attrib_value'];
      }
    } else {
      foreach (dbFetchRows("SELECT * FROM `entity_attribs` WHERE `entity_type` = ? AND `entity_id` = ?", array($entity_type, $entity_id)) as $entry)
      {
        $attribs[$entry['attrib_type']] = $entry['attrib_value'];
      }
    }
    $GLOBALS['cache']['entity_attribs'][$entity_type][$entity_id] = $attribs;
  }
  return $GLOBALS['cache']['entity_attribs'][$entity_type][$entity_id];
}

/**
 *
 * Set value for specific attribute and entity
 *
 * @param string $entity_type
 * @param mixed $entity_id
 * @param string $attrib_type
 * @param string $attrib_value
 * @return boolean
 */
function set_entity_attrib($entity_type, $entity_id, $attrib_type, $attrib_value)
{
  if (is_array($entity_id))
  {
    // Passed entity array, instead id
    $translate = entity_type_translate_array($entity_type);
    $entity_id = $entity_id[$translate['id_field']];
  }
  if (!$entity_id) { return NULL; }

  if (isset($GLOBALS['cache']['entity_attribs'][$entity_type][$entity_id]))
  {
    // Reset entity attribs
    unset($GLOBALS['cache']['entity_attribs'][$entity_type][$entity_id]);
  }

  if ($entity_type == 'device' && get_db_version() < 240)
  {
    // // CLEANME. Compatibility, remove in r8000, but not before CE 0.16.1 (Oct 7, 2015)
    if (dbFetchCell("SELECT COUNT(*) FROM `devices_attribs` WHERE `device_id` = ? AND `attrib_type` = ?", array($entity_id, $attrib_type)))
    {
      $return = dbUpdate(array('attrib_value' => $attrib_value), 'devices_attribs', '`device_id` = ? AND `attrib_type` = ?', array($entity_id, $attrib_type));
    } else {
      $return = dbInsert(array('device_id' => $entity_id, 'attrib_type' => $attrib_type, 'attrib_value' => $attrib_value), 'devices_attribs');
      if ($return !== FALSE) { $return = TRUE; } // Note dbInsert return IDs if exist or 0 for not indexed tables
    }
  } else {
    if (dbFetchCell("SELECT COUNT(*) FROM `entity_attribs` WHERE `entity_type` = ? AND `entity_id` = ? AND `attrib_type` = ?", array($entity_type, $entity_id, $attrib_type)))
    {
      $return = dbUpdate(array('attrib_value' => $attrib_value), 'entity_attribs', '`entity_type` = ? AND `entity_id` = ? AND `attrib_type` = ?', array($entity_type, $entity_id, $attrib_type));
    } else {
      $return = dbInsert(array('entity_type' => $entity_type, 'entity_id' => $entity_id, 'attrib_type' => $attrib_type, 'attrib_value' => $attrib_value), 'entity_attribs');
      if ($return !== FALSE) { $return = TRUE; } // Note dbInsert return IDs if exist or 0 for not indexed tables
    }
  }
  return $return;
}

/**
 *
 * Delete specific attribute for entity
 *
 * @param string $entity_type
 * @param mixed $entity_id
 * @param string $attrib_type
 * @return boolean
 */
function del_entity_attrib($entity_type, $entity_id, $attrib_type)
{
  if (is_array($entity_id))
  {
    // Passed entity array, instead id
    $translate = entity_type_translate_array($entity_type);
    $entity_id = $entity_id[$translate['id_field']];
  }
  if (!$entity_id) { return NULL; }

  if (isset($GLOBALS['cache']['entity_attribs'][$entity_type][$entity_id]))
  {
    // Reset entity attribs
    unset($GLOBALS['cache']['entity_attribs'][$entity_type][$entity_id]);
  }

  if ($entity_type == 'device' && get_db_version() < 240)
  {
    // CLEANME. Compatibility, remove in r8000, but not before CE 0.16.1 (Oct 7, 2015)
    return dbDelete('devices_attribs', '`device_id` = ? AND `attrib_type` = ?', array($entity_id, $attrib_type));
  } else {
    return dbDelete('entity_attribs', '`entity_type` = ? AND `entity_id` = ? AND `attrib_type` = ?', array($entity_type, $entity_id, $attrib_type));
  }
}

/**
 *
 * Get array of entities (id) linked to device
 *
 * @param mixed $device_id Device array of id
 * @param mixed $entity_types List of entities as array, if empty get all
 * @return array
 */
function get_device_entities($device_id, $entity_types = NULL)
{
  if (is_array($device_id))
  {
    // Passed device array, instead id
    $device_id = $device_id['device_id'];
  }
  if (!$device_id) { return NULL; }

  if (!is_array($entity_types) && strlen($entity_types))
  {
    // Single entity type passed, convert to array
    $entity_types = array($entity_types);
  }
  $all = empty($entity_types);
  $entities = array();
  foreach (array_keys($GLOBALS['config']['entities']) as $entity_type)
  {
    if ($all || in_array($entity_type, $entity_types))
    {
      $translate = entity_type_translate_array($entity_type);
      if (!$translate['device_id_field']) { continue; }
      $query = 'SELECT `' . $translate['id_field'] . '` FROM `' . $translate['table'] . '` WHERE `' . $translate['device_id_field'] . '` = ?;';
      $entity_ids = dbFetchColumn($query, array($device_id));
      if (is_array($entity_ids) && count($entity_ids))
      {
        $entities[$entity_type] = $entity_ids;
      }
    }
  }
  return $entities;
}

/**
 *
 * Get all attributes for all entities from device
 *
 * @param string $entity_type
 * @param mixed $entity_id
 * @return array
 */
function get_device_entities_attribs($device_id, $entity_types = NULL)
{
  $attribs = array();
  foreach (get_device_entities($device_id, $entity_types) as $entity_type => $entities)
  {
    $where = generate_query_values($entities, 'entity_id');
    foreach (dbFetchRows("SELECT * FROM `entity_attribs` WHERE `entity_type` = ?" . $where, array($entity_type)) as $entry)
    {
      $attribs[$entry['entity_type']][$entry['entity_id']][$entry['attrib_type']] = $entry['attrib_value'];
    }
  }
  $GLOBALS['cache']['entity_attribs'] = $attribs;

  return $GLOBALS['cache']['entity_attribs'];
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function get_entity_by_id_cache($entity_type, $entity_id)
{
  global $cache;

  $translate = entity_type_translate_array($entity_type);

  if (is_array($cache[$entity_type][$entity_id]))
  {

    return $cache[$entity_type][$entity_id];

  } else {

    switch($entity_type)
    {
      case "bill":
        if (function_exists('get_bill_by_id'))
        {
          $entity = get_bill_by_id($entity_id);
        }
        break;

      case "port":
        $entity = get_port_by_id($entity_id);
        break;

      default:
        $sql = 'SELECT * FROM `'.$translate['table'].'`';

        if (isset($translate['state_table']))
        {
          $sql .= ' LEFT JOIN `'.$translate['state_table'].'` USING (`'.$translate['id_field'].'`)';
        }

          if (isset($translate['parent_table']))
          {
              $sql .= ' LEFT JOIN `'.$translate['parent_table'].'` USING (`'.$translate['parent_id_field'].'`)';
          }

        $sql .= ' WHERE `'.$translate['table'].'`.`'.$translate['id_field'].'` = ?';

        $entity = dbFetchRow($sql, array($entity_id));
        if (function_exists('humanize_'.$entity_type)) { $do = 'humanize_'.$entity_type; $do($entity); }
        else if (isset($translate['humanize_function']) && function_exists($translate['humanize_function'])) { $do = $translate['humanize_function']; $do($entity); }
        break;
    }

    if (is_array($entity))
    {
      entity_rewrite($entity_type, $entity);
      $cache[$entity_type][$entity_id] = $entity;
      return $entity;
    }
  }

  return FALSE;
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function entity_type_translate($entity_type)
{
  $data = entity_type_translate_array($entity_type);
  if (!is_array($data)) { return NULL; }

  return array($data['table'], $data['id_field'], $data['name_field'], $data['ignore_field'], $data['entity_humanize']);
}

// Returns a text name from an entity type and an id
// A little inefficient.
// DOCME needs phpdoc block
// TESTME needs unit testing
function entity_name($type, $entity)
{
  global $config, $entity_cache;

  if (is_numeric($entity))
  {
    $entity = get_entity_by_id_cache($type, $entity);
  }

  $translate = entity_type_translate_array($type);

  $text = $entity[$translate['name_field']];

  return($text);
}

// Returns a text name from an entity type and an id
// A little inefficient.
// DOCME needs phpdoc block
// TESTME needs unit testing
function entity_short_name($type, $entity)
{
  global $config, $entity_cache;

  if (is_numeric($entity))
  {
    $entity = get_entity_by_id_cache($type, $entity);
  }

  $translate = entity_type_translate_array($type);

  $text = $entity[$translate['name_field']];

  return($text);
}

// Returns a text description from an entity type and an id
// A little inefficient.
// DOCME needs phpdoc block
// TESTME needs unit testing
function entity_descr($type, $entity)
{
  global $config, $entity_cache;

  if (is_numeric($entity))
  {
    $entity = get_entity_by_id_cache($type, $entity);
  }

  $translate = entity_type_translate_array($type);

  $text = $entity[$translate['entity_descr_field']];

  return($text);
}

/**
 * Translate an entity type to the relevant table and the identifier field name
 *
 * @param string entity_type
 * @return string entity_table
 * @return array entity_id
*/
// TESTME needs unit testing
function entity_type_translate_array($entity_type)
{
  $translate = $GLOBALS['config']['entities'][$entity_type];

  // Base fields
  // FIXME, not listed here: agg_graphs, metric_graphs
  $fields = array('table', 'table_fields', 'state_table', 'state_fields', 'humanize_function', 'parent_type', 'parent_table', 'parent_id_field', 'where', 'icon', 'graph');
  foreach ($fields as $field)
  {
    if (isset($translate[$field]))
    {
      $data[$field] = $translate[$field];
    }
    else if (isset($GLOBALS['config']['entities']['default'][$field]))
    {
      $data[$field] = $GLOBALS['config']['entities']['default'][$field];
    }
  }

  // Table fields
  $fields_table = array('id', 'device_id', 'index', 'mib', 'name', 'shortname', 'descr', 'ignore', 'disable', 'deleted', 'limit_high', 'limit_low');
  if (isset($translate['table_fields']))
  {
    // New definition style
    foreach ($translate['table_fields'] as $field => $entry)
    {
      // Add old style name (ie 'id_field') for compatibility
      $data[$field . '_field'] = $entry;
    }
  } else {
    // Old definition style
    foreach ($fields_table as $field)
    {
      $field_old = $field . '_field';
      if (isset($translate[$field_old]))
      {
        $data[$field_old] = $translate[$field_old];
        // Additionally convert to new 'table_fields' array
        $data['table_fields'][$field] = $translate[$field_old];
      }
    }
  }

  return $data;
}

/**
 * Returns TRUE if the logged in user is permitted to view the supplied entity.
 *
 * @param $entity_id
 * @param $entity_type
 * @param $device_id
 * @param $permissions Permissions array, by default used global var $permissions generated by permissions_cache()
 *
 * @return bool
 */
// TESTME needs unit testing
function is_entity_permitted($entity_id, $entity_type, $device_id = NULL, $permissions = NULL)
{
  if (is_null($permissions) && isset($GLOBALS['permissions']))
  {
    // Note, pass permissions array by param used in permissions_cache()
    $permissions = $GLOBALS['permissions'];
  }

  //if (OBS_DEBUG)
  //{
  //  print_vars($permissions);
  //  print_vars($_SESSION);
  //  print_vars($GLOBALS['auth']);
  //  print_vars(is_graph());
  //}

  if (!is_numeric($device_id)) { $device_id = get_device_id_by_entity_id($entity_id, $entity_type); }

  if (isset($_SESSION['user_limited']) && !$_SESSION['user_limited'])
  {
    // User not limited (userlevel >= 5)
    $allowed = TRUE;
  }
  else if (is_numeric($device_id) && device_permitted($device_id))
  {
    $allowed = TRUE;
  }
  else if (isset($permissions[$entity_type][$entity_id]) && $permissions[$entity_type][$entity_id])
  {
    $allowed = TRUE;
  }
  else if (isset($GLOBALS['auth']) && is_graph())
  {
    $allowed = $GLOBALS['auth'];
  } else {
    $allowed = FALSE;
  }

  if (OBS_DEBUG)
  {
    $debug_msg = "PERMISSIONS CHECK. Entity type: $entity_type, Entity ID: $entity_id, Device ID: ".($device_id ? $device_id : 'NULL').", Allowed: ".($allowed ? 'TRUE' : 'FALSE').".";
    if (isset($GLOBALS['notifications']))
    {
      $GLOBALS['notifications'][] = array('text' => $debug_msg, 'severity' => 'debug');
    } else {
      print_debug($debug_msg);
    }
  }
  return $allowed;
}

/**
 * Generates standardised set of array fields for use in entity-generic functions and code.
 * Has no return value, it modifies the $entity array in-place.
 *
 * @param $entity_type string
 * @param $entity array
 *
 */
// TESTME needs unit testing
function entity_rewrite($entity_type, &$entity)
{
  $translate = entity_type_translate_array($entity_type);

  // By default, fill $entity['entity_name'] with name_field contents.
  if (isset($translate['name_field'])) { $entity['entity_name'] = $entity[$translate['name_field']]; }

  // By default, fill $entity['entity_shortname'] with shortname_field contents. Fallback to entity_name when field name is not set.
  if (isset($translate['shortname_field'])) { $entity['entity_shortname'] = $entity[$translate['name_field']]; } else { $entity['entity_shortname'] = $entity['entity_name']; }

  // By default, fill $entity['entity_descr'] with descr_field contents.
  if (isset($translate['descr_field'])) { $entity['entity_descr'] = $entity[$translate['descr_field']]; }

  // By default, fill $entity['entity_id'] with id_field contents.
  if (isset($translate['id_field'])) { $entity['entity_id'] = $entity[$translate['id_field']]; }

  switch($entity_type)
  {
    case "bgp_peer":
      // Special handling of name/shortname/descr for bgp_peer, since it combines multiple elements.

      if (Net_IPv6::checkIPv6($entity['bgpPeerRemoteAddr']))
      {
        $addr = Net_IPv6::compress($entity['bgpPeerRemoteAddr']);
      } else {
        $addr = $entity['bgpPeerRemoteAddr'];
      }

      $entity['entity_name']      = "AS".$entity['bgpPeerRemoteAs'] ." ". $addr;
      $entity['entity_shortname'] = $addr;
      $entity['entity_descr']     = $entity['astext'];
      break;

    case "sla":
      $entity['entity_name']      = 'SLA #' . $entity['sla_index'];
      if (!empty($entity['sla_target']) && ($entity['sla_target'] != $entity['sla_tag']))
      {
        if (get_ip_version($entity['sla_target']) === 6)
        {
          $sla_target = Net_IPv6::compress($entity['sla_target'], TRUE);
        } else {
          $sla_target = $entity['sla_target'];
        }
        $entity['entity_name']   .= ' (' . $entity['sla_tag'] . ': ' . $sla_target . ')';
      } else {
        $entity['entity_name']   .= ' (' . $entity['sla_tag'] . ')';
      }
      $entity['entity_shortname'] = "#". $entity['sla_index'] . " (". $entity['sla_tag'] . ")";
      break;

    case "pseudowire":
      $entity['entity_name']      = $entity['pwID'] . ($entity['pwDescr'] ? " (". $entity['pwDescr'] . ")" : '');
      $entity['entity_shortname'] = $entity['pwID'];
      break;
  }
}

/**
 * Generates a URL to reach the entity's page (or the most specific list page the entity appears on)
 * Has no return value, it modifies the $entity array in-place.
 *
 * @param $entity_type string
 * @param $entity array
 *
 */
// TESTME needs unit testing
function generate_entity_link($entity_type, $entity, $text = NULL, $graph_type = NULL, $escape = TRUE, $short = FALSE)
{
  if (is_numeric($entity))
  {
    $entity = get_entity_by_id_cache($entity_type, $entity);
  }

  entity_rewrite($entity_type, $entity);

  switch($entity_type)
  {
    case "device":
      $link = generate_device_link($entity);
      break;
    case "mempool":
      $url = generate_url(array('page' => 'device', 'device' => $entity['device_id'], 'tab' => 'health', 'metric' => 'mempool'));
      break;
    case "processor":
      $url = generate_url(array('page' => 'device', 'device' => $entity['device_id'], 'tab' => 'health', 'metric' => 'processor', 'processor_id' => $entity['processor_id']));
      break;
    case "status":
      $url = generate_url(array('page' => 'device', 'device' => $entity['device_id'], 'tab' => 'health', 'metric' => 'status', 'id' => $entity['status_id']));
      break;
    case "sensor":
      $url = generate_url(array('page' => 'device', 'device' => $entity['device_id'], 'tab' => 'health', 'metric' => $entity['sensor_class'], 'id' => $entity['sensor_id']));
      break;
    case "printersupply":
      $url = generate_url(array('page' => 'device', 'device' => $entity['device_id'], 'tab' => 'printing', 'supply' => $entity['supply_type']));
      break;
    case "port":
      $link = generate_port_link($entity, NULL, $graph_type, $escape, $short);
      break;
    case "storage":
      $url = generate_url(array('page' => 'device', 'device' => $entity['device_id'], 'tab' => 'health', 'metric' => 'storage'));
      break;
    case "bgp_peer":
      $url = generate_url(array('page' => 'device', 'device' => ($entity['peer_device_id'] ? $entity['peer_device_id'] : $entity['device_id']), 'tab' => 'routing', 'proto' => 'bgp'));
      break;
    case "netscalervsvr":
      $url = generate_url(array('page' => 'device', 'device' => $entity['device_id'], 'tab' => 'loadbalancer', 'type' => 'netscaler_vsvr', 'vsvr' => $entity['vsvr_id']));
      break;
    case "netscalersvc":
      $url = generate_url(array('page' => 'device', 'device' => $entity['device_id'], 'tab' => 'loadbalancer', 'type' => 'netscaler_services', 'svc' => $entity['svc_id']));
      break;
    case "netscalersvcgrpmem":
      $url = generate_url(array('page' => 'device', 'device' => $entity['device_id'], 'tab' => 'loadbalancer', 'type' => 'netscaler_servicegroupmembers', 'svc' => $entity['svc_id']));
      break;
    case "p2pradio":
      $url = generate_url(array('page' => 'device', 'device' => $entity['device_id'], 'tab' => 'p2pradios'));
      break;
    case "sla":
      $url = generate_url(array('page' => 'device', 'device' => $entity['device_id'], 'tab' => 'slas', 'id' => $entity['sla_id']));
      break;
    case "pseudowire":
      $url = generate_url(array('page' => 'device', 'device' => $entity['device_id'], 'tab' => 'pseudowires', 'id' => $entity['pseudowire_id']));
      break;
    case "maintenance":
      $url = generate_url(array('page' => 'alert_maintenance', 'maintenance' => $entity['maint_id']));
      break;
    case "group":
      $url = generate_url(array('page' => 'group', 'group_id' => $entity['group_id']));
      break;
    case "virtualmachine":
      // If we know this device by its vm name in our system, create a link to it, else just print the name.
      if (get_device_id_by_hostname($entity['vm_name']))
      {
        $link = generate_device_link(device_by_name($entity['vm_name']));
      } else {
        // Hardcode $link to just show the name, no actual link
        $link = $entity['vm_name'];
      }
      break;
    default:
      $url = NULL;
  }

  if (!isset($link))
  {
    if (!isset($text))
    {
      if ($short && $entity['entity_shortname'])
      {
        $text = $entity['entity_shortname'];
      } else {
        $text = $entity['entity_name'];
      }
    }
    if ($escape) { $text = escape_html($text); }
    $link = '<a href="' . $url . '" class="entity-popup ' . $entity['html_class'] . '" data-eid="' . $entity['entity_id'] . '" data-etype="' . $entity_type . '">' . $text . '</a>';
  }

  return($link);
}

// Entity specific, moved from common

// Get port id  by ip address (using cache)
// DOCME needs phpdoc block
// TESTME needs unit testing
function get_port_id_by_ip_cache($device, $ip)
{
  global $cache;

  $ip_version = get_ip_version($ip);

  if (is_array($device) && isset($device['device_id']))
  {
    $device_id = $device['device_id'];
  }
  else if (is_numeric($device))
  {
    $device_id = $device;
  }
  if (!isset($device_id) || !$ip_version)
  {
    print_error("Invalid arguments passed into function get_port_id_by_ip_cache(). Please report to developers.");
    return FALSE;
  }

  if ($ip_version == 6)
  {
    $ip = Net_IPv6::uncompress($ip, TRUE);
  }

  if (isset($cache['port_ip'][$device_id][$ip]))
  {
    return $cache['port_ip'][$device_id][$ip];
  }

  $ips = dbFetchRows('SELECT `port_id`, `ifOperStatus`, `ifAdminStatus` FROM `ipv'.$ip_version.'_addresses`
                      LEFT JOIN `ports` USING(`port_id`)
                      WHERE `deleted` = 0 AND `device_id` = ? AND `ipv'.$ip_version.'_address` = ?', array($device_id, $ip));
  if (count($ips) === 1)
  {
    // Simple
    $port = current($ips);
    //return $port['port_id'];
  } else {
    foreach ($ips as $entry)
    {
      if ($entry['ifAdminStatus'] == 'up' && $entry['ifOperStatus'] == 'up')
      {
        // First UP entry
        $port = $entry;
        break;
      }
      else if ($entry['ifAdminStatus'] == 'up')
      {
        // Admin up, but port down or other state
        $ips_up[]   = $entry;
      } else {
        // Admin down
        $ips_down[] = $entry;
      }
    }
    if (!isset($port))
    {
      if ($ips_up)
      {
        $port = current($ips_up);
      } else {
        $port = current($ips_down);
      }
    }
  }
  $cache['port_ip'][$device_id][$ip] = $port['port_id'] ? $port['port_id'] : FALSE;

  return $cache['port_ip'][$device_id][$ip];

}

function get_port_by_ent_index($device, $entPhysicalIndex, $allow_snmp = FALSE)
{
  $mib = 'ENTITY-MIB';
  if (!is_numeric($entPhysicalIndex) ||
      !is_numeric($device['device_id']) ||
      !is_device_mib($device, $mib))
  {
    return FALSE;
  }

  $allow_snmp = $allow_snmp || is_cli(); // Allow snmpwalk queries in poller/discovery or if in wui passed TRUE!

  if (isset($GLOBALS['cache']['snmp'][$mib][$device['device_id']]))
  {
    // Cached
    $entity_array = $GLOBALS['cache']['snmp'][$mib][$device['device_id']];
    if (empty($entity_array))
    {
      // Force DB queries
      $allow_snmp = FALSE;
    }
  }
  else if ($allow_snmp)
  {
    // Inventory module disabled, this DB empty, try to cache
    $entity_array = array();
    $oids = array('entPhysicalDescr', 'entPhysicalName', 'entPhysicalClass', 'entPhysicalContainedIn', 'entPhysicalParentRelPos');
    if (is_device_mib($device, 'ARISTA-ENTITY-SENSOR-MIB'))
    {
      $oids[] = 'entPhysicalAlias';
    }
    foreach ($oids as $oid)
    {
      $entity_array = snmpwalk_cache_multi_oid($device, $oid, $entity_array, 'ENTITY-MIB:CISCO-ENTITY-VENDORTYPE-OID-MIB');
      if (!$GLOBALS['snmp_status']) { break; }
    }
    $entity_array = snmpwalk_cache_twopart_oid($device, 'entAliasMappingIdentifier', $entity_array, 'ENTITY-MIB:IF-MIB');
    if (empty($entity_array))
    {
      // Force DB queries
      $allow_snmp = FALSE;
    }
    $GLOBALS['cache']['snmp'][$mib][$device['device_id']] = $entity_array;
  } else {
    // Or try to use DB
  }

  //print_vars($entity_array);
  $sensor_index = $entPhysicalIndex; // Initial ifIndex  
  do
  {
    if ($allow_snmp)
    {
      // SNMP (discovery)
      $sensor_port = $entity_array[$sensor_index];
    } else {
      // DB (web)
      $sensor_port = dbFetchRow('SELECT * FROM `entPhysical` WHERE `device_id` = ? AND `entPhysicalIndex` = ?', array($device['device_id'], $sensor_index));
    }
    //print_vars($sensor_index);
    //print_vars($sensor_port);
    if ($sensor_port['entPhysicalClass'] === 'port')
    {
      // Port found, get mapped ifIndex
      unset($entAliasMappingIdentifier);
      foreach (array(0, 1, 2) as $i)
      {
        if (isset($sensor_port[$i]['entAliasMappingIdentifier']))
        {
          $entAliasMappingIdentifier = $sensor_port[$i]['entAliasMappingIdentifier'];
          break;
        }
      }
      if (isset($entAliasMappingIdentifier) && str_contains($entAliasMappingIdentifier, 'fIndex'))
      {
        list(, $ifIndex) = explode('.', $entAliasMappingIdentifier);

        $port = get_port_by_index_cache($device['device_id'], $ifIndex);
        if (is_array($port))
        {
          // Hola, port really found
          //$options['entPhysicalIndex_measured'] = $ifIndex;
          //$options['measured_class']  = 'port';
          //$options['measured_entity'] = $port['port_id'];
          print_debug("Port is found: ifIndex = $ifIndex, port_id = " . $port['port_id']);
          return $port;
        }
      }
      else if (!$allow_snmp && $sensor_port['ifIndex'])
      {
        $port = get_port_by_index_cache($device['device_id'], $ifIndex);
        print_debug("Port is found: ifIndex = $ifIndex, port_id = " . $port['port_id']);
        return $port;
      }

      break; // Exit do-while
    }
    else if ($device['os'] == 'arista_eos' && $sensor_port['entPhysicalClass'] == 'container' && strlen($sensor_port['entPhysicalAlias']))
    {
      // Arista not have entAliasMappingIdentifier, but used entPhysicalAlias as ifDescr
      $port_id = get_port_id_by_ifDescr($device['device_id'], $sensor_port['entPhysicalAlias']);
      if (is_numeric($port_id))
      {
        // Hola, port really found
        $port    = get_port_by_id($port_id);
        $ifIndex = $port['ifIndex'];
        //$options['entPhysicalIndex_measured'] = $ifIndex;
        //$options['measured_class']  = 'port';
        //$options['measured_entity'] = $port_id;
        print_debug("Port is found: ifIndex = $ifIndex, port_id = " . $port_id);
        return $port;
        //break; // Exit do-while
      }
      $sensor_index = $sensor_port['entPhysicalContainedIn']; // Next ifIndex
    }
    else if ($sensor_index == $sensor_port['entPhysicalContainedIn'])
    {
      break; // Break if current index same as next to avoid loop
    } else {
      $sensor_index = $sensor_port['entPhysicalContainedIn']; // Next ifIndex
    }
    // NOTE for self: entPhysicalParentRelPos >= 0 because on iosxr trouble
  } while ($sensor_port['entPhysicalClass'] !== 'port' && $sensor_port['entPhysicalContainedIn'] > 0 && ($sensor_port['entPhysicalParentRelPos'] >= 0 || $device['os'] == 'arista_eos'));

}

// EOF
