<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage discovery
 * @subpackage functions
 * @author     Adam Armstrong <adama@observium.org>
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2017 Observium Limited
 *
 */

// DOCME needs phpdoc block
// TESTME needs unit testing
function discover_new_device($hostname, $source = 'xdp', $protocol = NULL, $device = NULL, $snmp_port = 161)
{
  global $config;

  $source = strtolower($source);

  // Check if source is enabled for autodiscovery
  if ($config['autodiscovery'][$source])
  {
    $flags = OBS_DNS_ALL;

    if (!$protocol) { $protocol = strtoupper($source); }
    print_cli_data("Trying to discover host", "$hostname through $protocol ($source)", 3);

    // By first detect hostname is IP or domain name (IPv4/6 == 4/6, hostname == FALSE)
    $ip_version = get_ip_version($hostname);
    if ($ip_version)
    {
      // Hostname is IPv4/IPv6
      $use_ip = TRUE;
      $ip = $hostname;
    } else {
      $use_ip = FALSE;

      // Add "mydomain" configuration if this resolves, converts switch1 -> switch1.mydomain.com
      if (!empty($config['mydomain']) && isDomainResolves($hostname . '.' . $config['mydomain'], $flags))
      {
        $hostname .= '.' . $config['mydomain'];
      }

      // Determine v4 vs v6
      $ip = gethostbyname6($hostname, $flags);
      if ($ip)
      {
        $ip_version = get_ip_version($ip);
        print_debug("Host $hostname resolved as $ip");
      } else {
        // No DNS records
        print_debug("Host $hostname not resolved, autodiscovery fails.");
        return FALSE;
      }
    }

    if ($ip_version == 6)
    {
      $flags = $flags ^ OBS_DNS_A; // Exclude IPv4
    }
    if (isset($config['autodiscovery']['ping_skip']) && $config['autodiscovery']['ping_skip'])
    {
      $flags = $flags | OBS_PING_SKIP; // Add skip pings flag
    }

    if (match_network($ip, $config['autodiscovery']['ip_nets']))
    {
      print_debug("Host $hostname ($ip) founded inside configured nets, trying to add:");

      // By first check if pingable
      $pingable = isPingable($ip, $flags);
      if (!$pingable && (isset($config['autodiscovery']['ping_skip']) && $config['autodiscovery']['ping_skip']))
      {
        $flags = $flags | OBS_PING_SKIP; // Add skip pings flag if allowed in config
        $pingable = TRUE;
      }
      if ($pingable)
      {
        // Check if device duplicated by IP
        $ip = ($ip_version == 4 ? $ip : Net_IPv6::uncompress($ip, TRUE));
        $db = dbFetchRow('SELECT D.`hostname` FROM ipv'.$ip_version.'_addresses AS A
                         LEFT JOIN `ports`   AS P ON A.`port_id`   = P.`port_id`
                         LEFT JOIN `devices` AS D ON D.`device_id` = P.`device_id`
                         WHERE D.`disabled` = 0 AND A.`ipv'.$ip_version.'_address` = ?', array($ip));
        if ($db)
        {
          print_debug('Already have device '.$db['hostname']." with IP $ip");
          return FALSE;
        }

        // Detect snmp transport, net-snmp needs udp6 for ipv6
        $snmp_transport = ($ip_version == 4 ? 'udp' : 'udp6');

        $new_device = detect_device_snmpauth($ip, $snmp_port, $snmp_transport);
        if ($new_device)
        {
          if ($use_ip)
          {
            // Detect FQDN hostname
            // by sysName
            $snmphost = snmp_get($new_device, 'sysName.0', '-Oqv', 'SNMPv2-MIB');
            if ($snmphost)
            {
              $snmp_ip = gethostbyname6($snmphost, $flags);
            }

            if ($snmp_ip == $ip)
            {
              $hostname = $snmphost;
            } else {
              // by PTR
              $ptr = gethostbyaddr6($ip);
              if ($ptr)
              {
                $ptr_ip = gethostbyname6($ptr, $flags);
              }

              if ($ptr && $ptr_ip == $ip)
              {
                $hostname = $ptr;
              }
              else if ($config['autodiscovery']['require_hostname'])
              {
                print_debug("Device IP $ip does not seem to have FQDN.");
                return FALSE;
              } else {
                $hostname = $ip_version == 4 ? $ip : Net_IPv6::compress($hostname, TRUE); // Always use compressed IPv6 name
              }
            }
            print_debug("Device IP $ip linked to FQDN name: $hostname");
          }

          $new_device['hostname'] = $hostname;
          if (!check_device_duplicated($new_device))
          {
            $snmp_v3 = array();
            if ($new_device['snmp_version'] === 'v3')
            {
              $snmp_v3['snmp_authlevel']  = $new_device['snmp_authlevel'];
              $snmp_v3['snmp_authname']   = $new_device['snmp_authname'];
              $snmp_v3['snmp_authpass']   = $new_device['snmp_authpass'];
              $snmp_v3['snmp_authalgo']   = $new_device['snmp_authalgo'];
              $snmp_v3['snmp_cryptopass'] = $new_device['snmp_cryptopass'];
              $snmp_v3['snmp_cryptoalgo'] = $new_device['snmp_cryptoalgo'];
            }
            $remote_device_id = createHost($new_device['hostname'], $new_device['snmp_community'], $new_device['snmp_version'], $new_device['snmp_port'], $new_device['snmp_transport'], $snmp_v3);

            if ($remote_device_id)
            {
              if (is_flag_set(OBS_PING_SKIP, $flags))
              {
                set_entity_attrib('device', $remote_device_id, 'ping_skip', 1);
              }
              $remote_device = device_by_id_cache($remote_device_id, 1);

              if ($port)
              {
                humanize_port($port);
                log_event("Device autodiscovered through $protocol on " . $device['hostname'] . " (port " . $port['port_label'] . ")", $remote_device_id, 'port', $port['port_id']);
              } else {
                log_event("Device autodiscovered through $protocol on " . $device['hostname'], $remote_device_id, $protocol);
              }

              //array_push($GLOBALS['devices'], $remote_device); // createHost() already puth this
              return $remote_device_id;
            }
          }
        }
      }
    } else {
      print_debug("IP $ip ($hostname) not permitted inside \$config['autodiscovery']['ip_nets'] in config.php");
    }
    print_debug('Autodiscovery for host ' . $hostname . ' failed.');
  } else {
    print_debug('Autodiscovery for protocol ' . $protocol . ' disabled.');
  }
  return FALSE;
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function discover_device($device, $options = NULL)
{
  global $config, $valid, $exec_status, $discovered_devices;

  // Initialise variables
  $valid           = array(); // Reset $valid array
  $modules         = array();
  $cache_discovery = array(); // Specific discovery cache for exchange snmpwalk data between modules (memory/storage/sensors/etc)
  $attribs         = get_entity_attribs('device', $device['device_id']);
  $device_start    = utime(); // Start counting device poll time

  // Check if device discovery already running
  $pid_info = check_process_run($device);
  if ($pid_info)
  {
    // Process ID exist in DB
    print_message("%rAnother ".$pid_info['process_name']." process (PID: ".$pid_info['PID'].", UID: ".$pid_info['UID'].", STARTED: ".$pid_info['STARTED'].") already running for device ".$device['hostname']." (".$device['device_id'].").%n", 'color');
    return FALSE;
  }
  add_process_info($device); // Store process info

  if (OBS_DEBUG > 1)
  {
    // Cache cleanup for new device
    $device_discovery_cache_keys = array_keys($GLOBALS['cache']);
    print_vars($device_discovery_cache_keys);
  }

  print_cli_heading($device['hostname'] . " [".$device['device_id']."]", 1);

  $detect_os = TRUE; // Set TRUE or FALSE for module 'os' (exclude double os detection)
  if ($device['os'] == 'generic' || (isset($options['h']) && $options['h'] == 'new')) // verify if OS has changed
  {
    $detect_os = FALSE;
    $old_os = $device['os'];
    $device['os'] = get_device_os($device);
    if ($device['os'] != $old_os)
    {
      print_cli_data("Device OS changed",  $old_os . " -> ".$device['os'], 1);
      log_event('OS changed: '.$old_os.' -> '.$device['os'], $device, 'device', $device['device_id'], 'warning');
      dbUpdate(array('os' => $device['os']), 'devices', '`device_id` = ?', array($device['device_id']));
    }
  }

  print_cli_data("OS Type",  $device['os'], 1);

  if ($config['os'][$device['os']]['group'])
  {
    $device['os_group'] = $config['os'][$device['os']]['group'];
    print_cli_data("OS Group", $device['os_group'], 1);
  }

  print_cli_data("SNMP Version", $device['snmp_version'], 1);

  print_cli_data("Last discovery", $device['last_discovered'], 1);
  print_cli_data("Last duration", $device['last_discovered_timetaken']. " seconds", 1);

  echo(PHP_EOL);

  // Either only run the modules specified on the commandline, or run all modules in config.
  if ($options['m'])
  {
    foreach (explode(",", $options['m']) as $module)
    {
      $modules[$module] = TRUE;
    }
  }
  else if ($device['force_discovery'] && $options['h'] == 'new' && isset($attribs['force_discovery_modules']))
  {
    // Forced discovery specific modules
    foreach (json_decode($attribs['force_discovery_modules'], TRUE) as $module)
    {
      $modules[$module] = TRUE;
    }
    log_event('Forced discovery module(s): '.implode(', ', array_keys($modules)), $device, 'device', $device['device_id'], 'debug');
  } else {
    $modules = $config['discovery_modules'];
  }

  // Use os specific modules order
  //print_vars($modules);
  if (isset($config['os'][$device['os']]['discovery_order']))
  {
    //print_vars($config['os'][$device['os']]['discovery_order']);
    foreach ($config['os'][$device['os']]['discovery_order'] as $module => $module_order)
    {
      if (array_key_exists($module, $modules))
      {
        $module_status = $modules[$module];
        switch ($module_order)
        {
          case 'last':
            // add to end of modules list
            unset($modules[$module]);
            $modules[$module] = $module_status;
            break;
          case 'first':
            // add to begin of modules list, but not before os/system
            $new_modules = array();
            if ($modules['os'])
            {
              $new_modules['os']     = $modules['os'];
              unset($modules['os']);
            }
            if ($modules['system'])
            {
              $new_modules['system'] = $modules['system'];
              unset($modules['system']);
            }
            $new_modules[$module] = $module_status;
            unset($modules[$module]);
            $modules = $new_modules + $modules;
            break;
          default:
            // add into specific place (after module name in $module_order)
            // yes, this is hard and magically
            if (array_key_exists($module_order, $modules))
            {
              unset($modules[$module]);
              $new_modules = array();
              foreach ($modules as $new_module => $new_status)
              {
                array_shift($modules);
                $new_modules[$new_module] = $new_status;
                if ($new_module == $module_order)
                {
                  $new_modules[$module] = $module_status;
                  break;
                }
              }
              $modules = array_merge($new_modules, (array)$modules);
            }
        }
      }
    }
    //print_vars($modules);
  }

  foreach ($modules as $module => $module_status)
  {
    if (discovery_module_excluded($device, $module) === FALSE)
    {
      if ($attribs['discover_'.$module] || ( $module_status && !isset($attribs['discover_'.$module])))
      {
        $m_start = utime();
        $GLOBALS['module_stats'][$module] = array();

        print_cli_heading("Module Start: %R".$module."");

        include("includes/discovery/$module.inc.php");

        $m_end   = utime();
        $GLOBALS['module_stats'][$module]['time'] = round($m_end - $m_start, 4);
        print_module_stats($device, $module);
        echo PHP_EOL;
        //print_cli_heading("Module End: %R".$module."");
      } elseif (isset($attribs['discover_'.$module]) && $attribs['discover_'.$module] == "0")
      {
        print_debug("Module [ $module ] disabled on host.");
      } else {
        print_debug("Module [ $module ] disabled globally.");
      }
    }
  }

  // Set type to a predefined type for the OS if it's not already set
  if ($device['type'] == "unknown" || $device['type'] == "")
  {
    if ($config['os'][$device['os']]['type'])
    {
      $device['type'] = $config['os'][$device['os']]['type'];
    }
  }

  $device_end = utime(); $device_run = $device_end - $device_start; $device_time = substr($device_run, 0, 5);

  dbUpdate(array('last_discovered' => array('NOW()'), 'type' => $device['type'], 'last_discovered_timetaken' => $device_time, 'force_discovery' => 0), 'devices', '`device_id` = ?', array($device['device_id']));

  // Clean force discovery
  if (isset($attribs['force_discovery_modules']))
  {
    del_entity_attrib('device', $device['device_id'], 'force_discovery_modules');
  }

  // Put performance into devices_perftimes table
  // Not worth putting discovery data into rrd. it's not done every 5 mins :)
  dbInsert(array('device_id' => $device['device_id'], 'operation' => 'discover', 'start' => $device_start, 'duration' => $device_run), 'devices_perftimes');

  print_cli_heading($device['hostname']. " [" . $device['device_id'] . "] completed discovery modules at " . date("Y-m-d H:i:s"), 1);

  print_cli_data("Discovery time", $device_time." seconds", 1);

  echo(PHP_EOL);
  $discovered_devices++;

  // Clean
  if (OBS_DEBUG > 1)
  {
    $device_discovery_cache_keys = array_diff(array_keys($GLOBALS['cache']), $device_discovery_cache_keys);
    print_vars($device_discovery_cache_keys);
    //print_vars($GLOBALS['cache']);
  }
  del_process_info($device); // Remove process info
  unset($cache_discovery, $GLOBALS['cache']['snmp']);
}

// TESTME needs unit testing
// FIXME don't pass valid, use this as a global variable
/**
 * Discover a new virtual machine on a device
 *
 * This function adds a virtual machine to a device, if it does not already exist.
 * Data on the VM is updated if it has changed, and an event is logged with regards to the changes.
 * If the VM has a valid hostname, Observium attempts to discover this as a new device (calling discover_new_device).
 *
 * Valid array keys for the $options array: type, id, name, os, memory (in bytes), cpucount, status, source (= snmp, agent, etc)
 *
 * @param array &$valid
 * @param array $device
 * @param array $options
*/
function discover_virtual_machine(&$valid, $device, $options = array())
{
  print_debug('Discover VM: ' . $options['type'] . '/' . $options['source'] . ' (' . $options['id'] . ') ' . $options['name'] . ' CPU: ' . $options['cpucount'] . ' RAM: ' . $options['memory'] . ' Status: ' . $options['status']);

  if (dbFetchCell("SELECT COUNT(`vm_id`) FROM `vminfo` WHERE `device_id` = ? AND `vm_name` = ? AND `vm_type` = ? AND `vm_source` = ?",
    array($device['device_id'], $options['name'], $options['type'], $options['source'])) == 0)
  {
    $vm_insert = array('device_id' => $device['device_id'], 'vm_type' => $options['type'], 'vm_uuid' => $options['id'], 'vm_name' => $options['name'],
      'vm_guestos' => $options['os'], 'vm_memory' => $options['memory'] / 1024 / 1024, 'vm_cpucount' => $options['cpucount'], 'vm_state' => $options['status'],
      'vm_source' => $options['source']);
    $vm_id = dbInsert($vm_insert, 'vminfo');
    echo('+');
    log_event("Virtual Machine added: " . $options['name'] . ' (' . format_bi($options['memory']) . 'B RAM, ' . $options['cpucount'] . ' CPU)', $device, $options['type'], $vm_id);

    if (is_valid_hostname($options['name']) && in_array($options['status'], array('running', 'powered on', 'poweredOn')))
    {
      // Try to discover this VM as a new device, if it's actually running. discover_new_device() will check for valid hostname, duplicates, etc.
      // Libvirt, Proxmox (= QEMU-powered) return "running"; VMWare returns "powered on" (or "poweredOn" in older versions).
      discover_new_device($options['name'], $options['type'], $options['protocol']);
    }
  } else {
    $vm = dbFetchRow("SELECT * FROM `vminfo` WHERE `device_id` = ? AND `vm_uuid` = ? AND `vm_type` = ?", array($device['device_id'], $options['id'], $options['type']));
    if ($vm['vm_state'] != $options['status'] || $vm['vm_name'] != $options['name'] || $vm['vm_cpucount'] != $options['cpucount'] || $vm['vm_guestos'] != $options['os'] || $vm['vm_memory'] != $options['memory'] / 1024 / 1024)
    {
      $update = array('vm_state' => $options['status'], 'vm_guestos' => $options['os'], 'vm_name' => $options['name'],
        'vm_memory' => $options['memory'] / 1024 / 1024, 'vm_cpucount' => $options['cpucount']);
      dbUpdate($update, 'vminfo', "device_id = ? AND vm_type = ? AND vm_uuid = ? AND vm_source = ?", array($device['device_id'], $options['type'], $options['id'], $options['source']));
      echo('U');
      /// FIXME eventlog changed fields
    }
    else
    {
      echo('.');
    }
  }

  $valid['vm'][$options['type']][(string)$options['id']] = 1;
}

/**
 * Discover a new application on a device
 *
 * This function returns an app_id for the application code to use.
 * If the app+instance combination already exists in the database, the current id will be returned.
 * If not, a new row will be created and the newly created id will be returned.
 *
 * @param array $device
 * @param string $type
 * @param string $instance
 * @return integer
*/
function discover_app($device, $type, $instance = NULL)
{
  if ($instance == NULL)
  {
    $app_data = dbFetchRow("SELECT * FROM `applications` WHERE `device_id` = ? AND `app_type` = ? AND `app_instance` IS NULL", array($device['device_id'], $type));
  } else {
    $app_data = dbFetchRow("SELECT * FROM `applications` WHERE `device_id` = ? AND `app_type` = ? AND `app_instance` = ?", array($device['device_id'], $type, $instance));
  }

  if ($app_data == FALSE)
  {
    $app_insert = array('device_id' => $device['device_id'], 'app_type' => $type, 'app_instance' => ($instance == NULL ? array('NULL') : $instance));
    echo('+');
    return dbInsert($app_insert, 'applications');
  } else {
    echo('.');
    return $app_data['app_id'];
  }
}

// TESTME needs unit testing
/**
 * Discover a new status sensor on a device - called from discover_sensor()
 *
 * This function adds a status sensor to a device, if it does not already exist.
 * Data on the sensor is updated if it has changed, and an event is logged with regards to the changes.
 *
 * @param array $device        Device array status sensor is being discovered on
 * @param string $oid          SNMP OID of status sensor
 * @param string $index        SNMP index of status sensor
 * @param string $type         Type of status sensor (used as key in $config['status_states'])
 * @param string $status_descr Description of status sensor
 * @param string $value        Current value of status sensor
 * @param array $options       Options
 * @param string $poller_type  Type of poller being used (SNMP, Agent, etc) - Used to check valid sensors per poller type
 * @return bool
*/
function discover_status($device, $oid, $index, $type, $status_descr, $value = NULL, $options = array(), $poller_type = 'snmp')
{
  global $config;

  $status_deleted = 0; // Obviously the status is not deleted, so hardcode it.

  // Init main
  $param_main = array('oid' => 'status_oid', 'status_descr' => 'status_descr', 'status_deleted' => 'status_deleted');

  // Init optional
  $param_opt = array('entPhysicalIndex', 'entPhysicalClass', 'entPhysicalIndex_measured', 'measured_class', 'measured_entity');
  foreach ($param_opt as $key)
  {
    $$key = ($options[$key] ? $options[$key] : NULL);
  }

  // Check state value
  $status_state = array();
  //if ($value !== NULL)
  //{
    $state_array = get_state_array($type, $value, $poller_type);
    $state = $state_array['value'];
    if ($state === FALSE)
    {
      print_debug("Skipped by unknown state value: $value, $status_descr ");
      return FALSE;
    }
    else if ($state_array['event'] == 'exclude')
    {
      print_debug("Skipped by 'exclude' event value: ".$config['status_states'][$type][$state]['name'].", $status_descr ");
      return FALSE;
    }
    $value = $state;

    //$status_state['status_event'] = $state_array['event'];
    //$status_state['status_name']  = $state_array['name'];
  //}

  print_debug("Discover status: ".$device['hostname'].", $oid, $index, $type, $status_descr, $value, $poller_type, $entPhysicalIndex, $entPhysicalClass");

  // Check sensor ignore filters
  foreach ($config['ignore_sensor'] as $bi)        { if (strcasecmp($bi, $status_descr) == 0)   { print_debug("Skipped by equals: $bi, $status_descr "); return FALSE; } }
  foreach ($config['ignore_sensor_string'] as $bi) { if (stripos($status_descr, $bi) !== FALSE) { print_debug("Skipped by strpos: $bi, $status_descr "); return FALSE; } }
  foreach ($config['ignore_sensor_regexp'] as $bi) { if (preg_match($bi, $status_descr) > 0)    { print_debug("Skipped by regexp: $bi, $status_descr "); return FALSE; } }

  $where  = ' WHERE `device_id` = ? AND `status_type` = ? AND `status_index` = ? AND `poller_type`= ?';
  $params = array($device['device_id'], $type, $index, $poller_type);
  if (dbFetchCell('SELECT COUNT(*) FROM `status`' . $where, $params) == '0')
  {
    $status_insert = array('poller_type'  => $poller_type,
                           'device_id'    => $device['device_id'],
                           'status_index' => $index,
                           'status_type'  => $type,
                           'status_id'    => $status_id,
                           'status_value' => $value,
                           'status_polled' => array('NOW()'),
                           'status_event' => $state_array['event'],
                           'status_name'  => $state_array['name']
                          );

    foreach ($param_main as $key => $column)
    {
      $status_insert[$column] = $$key;
    }

    foreach ($param_opt as $key)
    {
      if (is_null($$key)) { $$key = array('NULL'); } // If param null, convert to array(NULL)
      $status_insert[$key] = $$key;
    }

    $status_id = dbInsert($status_insert, 'status');

    // Insert initial state for status sensor
    //$status_state['status_id']     = $status_id;
    //$status_state['status_value']  = $value;
    //$status_state['status_polled'] = array('NOW()');
    //dbInsert($status_state, 'status-state');

    print_debug("( $status_id inserted )");
    echo('+');
    log_event("Status added: $class $type $index $status_descr", $device, 'status', $status_id);
  } else {
    $status_entry = dbFetchRow('SELECT * FROM `status`' . $where, $params);


    $update = array();
    foreach ($param_main as $key => $column)
    {
      if ($$key != $status_entry[$column])
      {
        $update[$column] = $$key;
      }
    }
    foreach ($param_opt as $key)
    {
      if ($$key != $status_entry[$key])
      {
        $update[$key] = $$key;
      }
    }

    if (count($update))
    {
      $updated = dbUpdate($update, 'status', '`status_id` = ?', array($status_entry['status_id']));
      echo('U');
      log_event("Status updated: $type $index $status_descr", $device, 'status', $status_entry['status_id']);
    } else {
      echo('.');
    }
  }
  $GLOBALS['valid']['status'][$type][$index] = 1;
}

// TESTME needs unit testing
// FIXME don't pass valid, use this as a global variable
/**
 * Discover a new sensor on a device
 *
 * This function adds a status sensor to a device, if it does not already exist.
 * Data on the sensor is updated if it has changed, and an event is logged with regards to the changes.
 *
 * Status sensors are handed off to discover_status().
 * Current sensor values are rectified in case they are broken (added spaces, etc).
 *
 * @param array &$valid        Array of currently valid sensors for poller type (used to delete later)
 * @param string $class        Class of sensor (voltage, temperature, etc.)
 * @param array $device        Device array sensor is being discovered on
 * @param string $oid          SNMP OID of sensor
 * @param string $index        SNMP index of sensor
 * @param string $type         Type of sensor
 * @param string $sensor_descr Description of sensor
 * @param int $scale           Scale of sensor (0.1 for 1:10 scale, 10 for 10:1 scale, etc)
 * @param string $value        Current sensor value
 * @param array $options       Options (sensor_unit, limit_auto, limit*)
 * @param string $poller_type  Type of poller being used (SNMP, Agent, etc) - Used to check valid sensors per poller type
 * @return bool
*/
function discover_sensor(&$valid, $class, $device, $oid, $index, $type, $sensor_descr, $scale = 1, $value = NULL, $options = array(), $poller_type = 'snmp')
{
  global $config;

  $sensor_deleted = 0; // This is obviously not deleted, so hardcode it as so.

  // If this is actually a status indicator, pass it off to discover_status() then return.
  if ($class == 'state' || $class == 'status')
  {
    print_debug("Redirecting call to discover_status().");
    return discover_status($device, $oid, $index, $type, $sensor_descr, $value, $options, $poller_type);
  }

  // Init main
  $param_main = array('oid' => 'sensor_oid', 'sensor_descr' => 'sensor_descr', 'scale' => 'sensor_multiplier', 'sensor_deleted' => 'sensor_deleted');

  // Init numeric values
  if (!is_numeric($scale) || $scale == 0) { $scale = 1; }

  // Skip discovery sensor if value not numeric or null (default)
  if (strlen($value))
  {
    // Some retarded devices report data with spaces and commas
    // STRING: "  20,4"
    $value = snmp_fix_numeric($value);
  }

  if (is_numeric($value))
  {
    $attrib_type = 'sensor_addition';
    if (isset($options[$attrib_type]) && is_numeric($options[$attrib_type]))
    {
      // This option currently required and used only in FOUNDRY-POE-MIB
      $value += $options[$attrib_type];
    }
    $value = scale_value($value, $scale);
    // $value *= $scale; // Scale before unit conversion
    $value = value_to_si($value, $options['sensor_unit'], $class); // Convert if not SI unit
  } else {
    print_debug("Sensor skipped by not numeric value: '$value', '$sensor_descr'");
    if (strlen($value))
    {
      print_debug("Perhaps this is named sensor, use discover_status() instead.");
    }
    return FALSE;
  }

  $param_limits = array('limit_high' => 'sensor_limit',     'limit_high_warn' => 'sensor_limit_warn',
                        'limit_low'  => 'sensor_limit_low', 'limit_low_warn'  => 'sensor_limit_low_warn');
  foreach ($param_limits as $key => $column)
  {
    // Set limits vars and unit convert if required
    $$key = (is_numeric($options[$key]) ? value_to_si($options[$key], $options['sensor_unit'], $class) : NULL);
  }
  // Auto calculate high/low limits if not passed
  $limit_auto = !isset($options['limit_auto']) || (bool)$options['limit_auto'];

  // Init optional
  $param_opt = array('entPhysicalIndex', 'entPhysicalClass', 'entPhysicalIndex_measured', 'measured_class', 'measured_entity', 'sensor_unit');
  foreach ($param_opt as $key)
  {
    $$key = ($options[$key] ? $options[$key] : NULL);
  }

  print_debug("Discover sensor: $class, ".$device['hostname'].", $oid, $index, $type, $sensor_descr, SCALE: $scale, LIMITS: ($limit_low, $limit_low_warn, $limit_high_warn, $limit_high), CURRENT: $value, $poller_type, $entPhysicalIndex, $entPhysicalClass");

  // Check sensor ignore filters
  foreach ($config['ignore_sensor'] as $bi)        { if (strcasecmp($bi, $sensor_descr) == 0)   { print_debug("Skipped by equals: $bi, $sensor_descr "); return FALSE; } }
  foreach ($config['ignore_sensor_string'] as $bi) { if (stripos($sensor_descr, $bi) !== FALSE) { print_debug("Skipped by strpos: $bi, $sensor_descr "); return FALSE; } }
  foreach ($config['ignore_sensor_regexp'] as $bi) { if (preg_match($bi, $sensor_descr) > 0)    { print_debug("Skipped by regexp: $bi, $sensor_descr "); return FALSE; } }

  if (!is_null($limit_low_warn) && !is_null($limit_high_warn) && ($limit_low_warn > $limit_high_warn))
  {
    // Fix high/low thresholds (i.e. on negative numbers)
    list($limit_high_warn, $limit_low_warn) = array($limit_low_warn, $limit_high_warn);
  }

  if (dbFetchCell('SELECT COUNT(`sensor_id`) FROM `sensors`
                   WHERE `poller_type`= ? AND `sensor_class` = ? AND `device_id` = ? AND `sensor_type` = ? AND `sensor_index` = ?',
                   array($poller_type, $class, $device['device_id'], $type, $index)) == '0')
  {
    if (!$limit_high) { $limit_high = sensor_limit_high($class, $value, $limit_auto); }
    if (!$limit_low)  { $limit_low  = sensor_limit_low($class, $value, $limit_auto); }

    if (!is_null($limit_low) && !is_null($limit_high) && ($limit_low > $limit_high))
    {
      // Fix high/low thresholds (i.e. on negative numbers)
      list($limit_high, $limit_low) = array($limit_low, $limit_high);
      print_debug("High/low limits swapped.");
    }

    $sensor_insert = array('poller_type' => $poller_type, 'sensor_class' => $class, 'device_id' => $device['device_id'],
                           'sensor_index' => $index, 'sensor_type' => $type);

    foreach ($param_main as $key => $column)
    {
      $sensor_insert[$column] = $$key;
    }

    foreach ($param_limits as $key => $column)
    {
      // Convert strings/numbers to (float) or to array('NULL')
      $$key = ($$key === NULL ? array('NULL') : (float)$$key);
      $sensor_insert[$column] = $$key;
    }
    foreach ($param_opt as $key)
    {
      if (is_null($$key)) { $$key = array('NULL'); }
      $sensor_insert[$key] = $$key;
    }

    $sensor_insert['sensor_value'] = $value;
    $sensor_insert['sensor_polled'] = array('NOW()');

    $sensor_id = dbInsert($sensor_insert, 'sensors');

    $attrib_type = 'sensor_addition';
    if (isset($options[$attrib_type]) && is_numeric($options[$attrib_type]))
    {
      // Add sensor attrib for use in poller
      set_entity_attrib('sensor', $sensor_id, $attrib_type, $options[$attrib_type]);
    }

    //$state_insert = array('sensor_id' => $sensor_id, 'sensor_value' => $value, 'sensor_polled' => 'NOW()');
    //dbInsert($state_insert, 'sensors-state');

    print_debug("( $sensor_id inserted )");
    echo('+');

    log_event("Sensor added: $class $type $index $sensor_descr", $device, 'sensor', $sensor_id);
  } else {
    $sensor_entry = dbFetchRow("SELECT * FROM `sensors` WHERE `sensor_class` = ? AND `device_id` = ? AND `sensor_type` = ? AND `sensor_index` = ?", array($class, $device['device_id'], $type, $index));

    // Limits
    if (!$sensor_entry['sensor_custom_limit'])
    {
      if (!is_numeric($limit_high))
      {
        if ($sensor_entry['sensor_limit'] !== '')
        {
          // Calculate a reasonable limit
          $limit_high = sensor_limit_high($class, $value, $limit_auto);
        } else {
          // Use existing limit. (this is wrong! --mike)
          $limit_high = $sensor_entry['sensor_limit'];
        }
      }

      if (!is_numeric($limit_low))
      {
        if ($sensor_entry['sensor_limit_low'] !== '')
        {
          // Calculate a reasonable limit
          $limit_low = sensor_limit_low($class, $value, $limit_auto);
        } else {
          // Use existing limit. (this is wrong! --mike)
          $limit_low = $sensor_entry['sensor_limit_low'];
        }
      }

      // Fix high/low thresholds (i.e. on negative numbers)
      if (!is_null($limit_low) && !is_null($limit_high) && ($limit_low > $limit_high))
      {
        list($limit_high, $limit_low) = array($limit_low, $limit_high);
        print_debug("High/low limits swapped.");
      }

      // Update limits
      $update = array();
      $update_msg = array();
      $debug_msg = 'Current sensor value: "'.$value.'", scale: "'.$scale.'"'.PHP_EOL;
      foreach ($param_limits as $key => $column)
      {
        // $key - param name, $$key - param value, $column - column name in DB for $key
        $debug_msg .= '  '.$key.': "'.$sensor_entry[$column].'" -> "'.$$key.'"'.PHP_EOL;
        //convert strings/numbers to identical type (float) or to array('NULL') for correct comparison
        $$key = ($$key === NULL ? array('NULL') : (float)$$key);
        $sensor_entry[$column] = ($sensor_entry[$column] === NULL ? array('NULL') : (float)$sensor_entry[$column]);
        if (float_cmp($$key, $sensor_entry[$column], 0.1) !== 0)
        {
          $update[$column] = $$key;
          $update_msg[] = $key.' -> "'.(is_array($$key) ? 'NULL' : $$key).'"';
        }
      }
      if (count($update))
      {
        echo("L");
        print_debug($debug_msg);
        log_event('Sensor updated (limits): '.implode(', ', $update_msg), $device, 'sensor', $sensor_entry['sensor_id']);
        $updated = dbUpdate($update, 'sensors', '`sensor_id` = ?', array($sensor_entry['sensor_id']));
      }
    }

    $update = array();
    foreach ($param_main as $key => $column)
    {
      if (float_cmp($$key, $sensor_entry[$column]) !== 0)
      {
        $update[$column] = $$key;
      }
    }

    foreach ($param_opt as $key)
    {
      if ($$key != $sensor_entry[$key])
      {
        $update[$key] = $$key;
      }
    }

    $attrib_type = 'sensor_addition';
    $sensor_addition = get_entity_attrib('sensor', $sensor_entry['sensor_id'], $attrib_type);
    if (is_numeric($sensor_addition))
    {
      if (!is_numeric($options[$attrib_type]))
      {
        del_entity_attrib('sensor', $sensor_entry['sensor_id'], $attrib_type);
      }
      else if ($options[$attrib_type] != $sensor_addition)
      {
        set_entity_attrib('sensor', $sensor_entry['sensor_id'], $attrib_type, $options[$attrib_type]);
      }
    }
    else if (is_numeric($options[$attrib_type]))
    {
      set_entity_attrib('sensor', $sensor_entry['sensor_id'], $attrib_type, $options[$attrib_type]);
    }

    if (count($update))
    {
      $updated = dbUpdate($update, 'sensors', '`sensor_id` = ?', array($sensor_entry['sensor_id']));
      echo('U');
      log_event("Sensor updated: $class $type $index $sensor_descr", $device, 'sensor', $sensor_entry['sensor_id']);
    } else {
      echo('.');
    }
  }
  $valid[$class][$type][$index] = 1;
}

// TESTME needs unit testing
/**
 * Calculate lower limit on a sensor
 *
 * @param string $class Sensor class (voltage, temperature, ...)
 * @param string $value Current sensor value to use as base
 * @param bool $auto    Set to false to not set an automatic limit
 * @return string
*/
function sensor_limit_low($class, $value, $auto = TRUE)
{
  $limit = NULL;

  if (!$auto) { return $limit; } // Do not calculate limit

  switch($class)
  {
    case 'temperature':
      if ($value > 0)
      {
        $limit = 0; // Freezing cold should be enough of a lower limit.
      }
      break;
    case 'voltage':
      if ($value < 0)
      {
        $limit = $value * (1 + (sgn($value) * 0.15));
      } else {
        $limit = $value * (1 - (sgn($value) * 0.15));
      }
      break;
    case 'humidity':
      $limit = 20;
      break;
    case 'frequency':
      $limit = $value * 0.95;
      break;
    case 'current':
      $limit = NULL;
      break;
    case 'fanspeed':
      $limit = $value * 0.80;
      break;
    case 'power':
      $limit = NULL;
      break;
  }

  return $limit;
}

// TESTME needs unit testing
/**
 * Calculate upper limit on a sensor
 *
 * @param string $class Sensor class (voltage, temperature, ...)
 * @param string $value Current sensor value to use as base
 * @param bool $auto    Set to false to not set an automatic limit
 * @return string
*/
function sensor_limit_high($class, $value, $auto = TRUE)
{
  $limit = NULL;

  if (!$auto) { return $limit; } // Do not calculate limit

  switch($class)
  {
    case 'temperature':
      if ($value < 0)
      {
        // Negative temperatures are usually used for "Thermal margins",
        // indicating how far from the critical point we are.
        $limit = 0;
      } else {
        $limit = $value * 1.60;
      }
      break;
    case 'voltage':
      if ($value < 0)
      {
        $limit = $value * (1 - (sgn($value) * 0.15));
      } else {
        $limit = $value * (1 + (sgn($value) * 0.15));
      }
      break;
    case 'humidity':
      $limit = 70;
      break;
    case 'frequency':
      $limit = $value * 1.05;
      break;
    case 'current':
      $limit = $value * 1.50;
      break;
    case 'fanspeed':
      $limit = $value * 1.80;
      break;
    case 'power':
      $limit = $value * 1.50;
      break;
  }

  return $limit;
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function check_valid_sensors($device, $class, $valid, $poller_type = 'snmp')
{
  $entries = dbFetchRows("SELECT * FROM `sensors` WHERE `device_id` = ? AND `sensor_class` = ? AND `poller_type` = ? AND `sensor_deleted` = '0'", array($device['device_id'], $class, $poller_type));

  if (is_array($entries) && count($entries))
  {
    foreach ($entries as $entry)
    {
      $index = $entry['sensor_index'];
      $type  = $entry['sensor_type'];
      if (!$valid[$class][$type][$index])
      {
        echo("-");
        print_debug("Sensor deleted: $index -> $type");
        //dbDelete('sensors',       "`sensor_id` = ?", array($entry['sensor_id']));

        dbUpdate(array('sensor_deleted' => '1'), 'sensors', '`sensor_id` = ?', array($entry['sensor_id']));
        //dbDelete('sensors-state', "`sensor_id` = ?", array($entry['sensor_id']));

        foreach (get_entity_attribs('sensor', $entry['sensor_id']) as $attrib_type => $value)
        {
          del_entity_attrib('sensor', $sensor_entry['sensor_id'], $attrib_type);
        }
        log_event("Sensor deleted: ".$entry['sensor_class']." ".$entry['sensor_type']." ". $entry['sensor_index']." ".$entry['sensor_descr'], $device, 'sensor', $entry['sensor_id']);
      }
    }
  }
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function check_valid_virtual_machines($device, $valid, $source)
{
  $entries = dbFetchRows("SELECT * FROM `vminfo` WHERE `device_id` = ? AND `vm_source` = ?", array($device['device_id'], $source));

  if (is_array($entries) && count($entries))
  {
    foreach ($entries as $entry)
    {
      $id   = $entry['vm_uuid'];
      $type = $entry['vm_type'];
      if (!$valid['vm'][$type][(string)$id])
      {
        echo("-");
        print_debug("Virtual Machine deleted: $id -> $type");
        dbDelete('vminfo', "`vm_id` = ?", array($entry['vm_id']));
        log_event("Virtual Machine deleted: ".$entry['name']." ".$entry['vm_type']." ". $entry['vm_uuid'], $device, 'vm', $entry['vm_uuid']);
      } else {
        echo('.');
      }
    }
  }
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function check_valid_status($device, $valid, $poller_type = 'snmp')
{
  $entries = dbFetchRows("SELECT * FROM `status` WHERE `device_id` = ? AND `poller_type` = ? AND `status_deleted` = '0'", array($device['device_id'], $poller_type));

  if (is_array($entries) && count($entries))
  {
    foreach ($entries as $entry)
    {
      $index = $entry['status_index'];
      $type  = $entry['status_type'];
      if (!$valid[$type][$index])
      {
        echo("-");
        print_debug("Status deleted: $index -> $type");
        //dbDelete('status',       "`status_id` = ?", array($entry['status_id']));
        //dbDelete('status-state', "`status_id` = ?", array($entry['status_id']));

        dbUpdate(array('status_deleted' => '1'), 'status', '`status_id` = ?', array($entry['status_id']));

        log_event("Status deleted: ".$entry['status_class']." ".$entry['status_type']." ". $entry['status_index']." ".$entry['status_descr'], $device, 'status', $entry['status_id']);
      }
    }
  }
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function check_valid_printer_supplies($device, $valid)
{
  $entries = dbFetchRows("SELECT * FROM `printersupplies` WHERE `device_id` = ?", array($device['device_id']));

  if (count($entries))
  {
    foreach ($entries as $entry)
    {
      $index   = $entry['supply_index'];
      $mib = $entry['supply_mib'];
      if (!$valid['printersupplies'][$mib][(string)$index])
      {
        echo("-");
        print_debug("Printer supply deleted: $index -> $mib");
        dbDelete('printersupplies', "`supply_id` = ?", array($entry['supply_id']));
        log_event("Printer supply deleted: " . $entry['supply_descr'] . " (" . nicecase($entry['supply_type']) . ", index $supply_index)", $device, 'toner', $entry['supply_id']);
      } else {
        echo(".");
      }
    }
  }
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function discover_juniAtmVp(&$valid, $port_id, $vp_id, $vp_descr)
{
  global $config;

  if (dbFetchCell("SELECT COUNT(*) FROM `juniAtmVp` WHERE `port_id` = ? AND `vp_id` = ?", array($port_id, $vp_id)) == "0")
  {
     $inserted = dbInsert(array('port_id' => $port_id,'vp_id' => $vp_id,'vp_descr' => $vp_descr), 'juniAtmVp');

     #FIXME vv no $device in front of 'juniAtmVp' - will not log correctly!
     log_event("Juniper ATM VP Added: port $port_id vp $vp_id descr $vp_descr", 'juniAtmVp', $inserted);
  }
  else
  {
    echo('.');
  }
  $valid[$port_id][$vp_id] = 1;
}

// FIXME. remove in r7000 -- uhh how? all link mibs still use discover_link. -T
function discover_link(&$valid, $local_port_id, $protocol, $remote_port_id, $remote_hostname, $remote_port, $remote_platform, $remote_version, $remote_address = NULL)
{
  $params = array('remote_port_id', 'remote_hostname', 'remote_port', 'remote_platform', 'remote_version', 'remote_address');
  foreach ($params as $param)
  {
    $neighbour[$param] = $$param;
  }
  // Call to new function
  discover_neighbour($protocol, $local_port_id, $neighbour);
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function discover_neighbour($protocol, $local_port_id, $neighbour)
{
  $port = get_port_by_id_cache($local_port_id);
  print_debug("Discover neighbour: " . $port['device_id'] . " -> $protocol, $local_port_id, " . implode(', ', $neighbour));

  $neighbour['protocol'] = $protocol;
  $params   = array('protocol', 'remote_port_id', 'remote_hostname', 'remote_port', 'remote_platform', 'remote_version', 'remote_address');
  $neighbour_db = dbFetchRow("SELECT * FROM `neighbours` WHERE `port_id` = ? AND `protocol` = ? AND `remote_hostname` = ? AND `remote_port` = ?", array($local_port_id, $protocol, $neighbour['remote_hostname'], $neighbour['remote_port']));
  if (!isset($neighbour_db['neighbour_id']))
  {
    $update = array('port_id' => $local_port_id);
    foreach ($params as $param)
    {
      $update[$param] = $neighbour[$param];
      if ($neighbour[$param] == NULL) { $update[$param] = array('NULL'); }
    }
    $id = dbInsert($update, 'neighbours');

    $GLOBALS['module_stats']['neighbours']['added']++; //echo('+');
  } else {
    $update = array();
    foreach ($params as $param)
    {
      if ($neighbour[$param] != $neighbour_db[$param])
      {
        $update[$param] = $neighbour[$param];
      }
    }
    if (count($update))
    {
      dbUpdate($update, 'neighbours', '`neighbour_id` = ?', array($neighbour_db['neighbour_id']));
      $GLOBALS['module_stats']['neighbours']['updated']++; //echo('U');
    } else {
      $GLOBALS['module_stats']['neighbours']['unchanged']++; //echo('.');
    }
  }
  $GLOBALS['valid']['neighbours'][$local_port_id][$neighbour['remote_hostname']][$neighbour['remote_port']] = 1;
}

// DOCME needs phpdoc block
// TESTME needs unit testing
// FIXME don't pass valid, use this as a global variable
function discover_storage(&$valid, $device, $storage_index, $storage_type, $storage_mib, $storage_descr, $storage_units, $storage_size, $storage_used, $options = array())
{
  global $config;

  // options && limits
  $option = 'storage_hc';
  $$option = isset($options[$option]) ? $options[$option] : 0;
  $option = 'storage_ignore';
  $$option = isset($options[$option]) ? $options[$option] : 0;

  if (isset($options['limit_high']))      { $storage_crit_limit = $options['limit_high']; }
  if (isset($options['limit_high_warn'])) { $storage_warn_limit = $options['limit_high_warn']; }

  print_debug($device['device_id']." -> $storage_index, $storage_type, $storage_mib, $storage_descr, $storage_units, $storage_size, $storage_used, $storage_hc");

  $storage_mib  = strtolower($storage_mib);

  // Check storage description and size
  if (!($storage_descr && $storage_size > 0)) { return FALSE; }

  // Check storage ignore filters
  foreach ($config['ignore_mount'] as $bi)        { if (strcasecmp($bi, $storage_descr) == 0)   { print_debug("Skipped by equals: $bi, $storage_descr "); return FALSE; } }
  foreach ($config['ignore_mount_string'] as $bi) { if (stripos($storage_descr, $bi) !== FALSE) { print_debug("Skipped by strpos: $bi, $storage_descr "); return FALSE; } }
  foreach ($config['ignore_mount_regexp'] as $bi) { if (preg_match($bi, $storage_descr) > 0)    { print_debug("Skipped by regexp: $bi, $storage_descr "); return FALSE; } }
  // Search duplicates for same mib/descr
  if (in_array($storage_descr, array_values($valid[$storage_mib]))) { print_debug("Skipped by already exist: $storage_descr "); return FALSE; }

  $params       = array('storage_index', 'storage_mib', 'storage_type', 'storage_descr', 'storage_hc',
                        'storage_ignore', 'storage_crit_limit', 'storage_warn_limit', 'storage_units');
  $params_state = array('storage_size', 'storage_used', 'storage_free', 'storage_perc'); // This is changable params, not required for update

  $device_id    = $device['device_id'];
  $storage_free = $storage_size - $storage_used;
  $storage_perc = round($storage_used / $storage_size * 100, 2);

  $storage_db = dbFetchRow("SELECT * FROM `storage` WHERE `device_id` = ? AND `storage_index` = ? AND `storage_mib` = ?", array($device_id, $storage_index, $storage_mib));
  if (!isset($storage_db['storage_id']))
  {
    $update = array('device_id' => $device_id);
    foreach (array_merge($params, $params_state) as $param)
    {
      $update[$param] = ($$param === NULL ? array('NULL') : $$param);
    }
    $id = dbInsert($update, 'storage');

    //$update_state = array('storage_id' => $id);
    //foreach ($params_state as $param) { $update_state[$param] = $$param; }
    //dbInsert($update_state, 'storage-state');

    $GLOBALS['module_stats']['storage']['added']++; //echo('+');
    log_event("Storage added: index $storage_index, mib $storage_mib, descr $storage_descr", $device, 'storage', $id);
  } else {
    $update = array();
    foreach ($params as $param)
    {
      if ($$param != $storage_db[$param] ) { $update[$param] = ($$param === NULL ? array('NULL') : $$param); }
    }
    if (count($update))
    {
      //if (isset($update['storage_descr']))
      //{
      //  // Rename storage rrds, because its filename based on description
      //  $old_rrd = $config['rrd_dir'] . '/' . $device['hostname'] . '/' . safename('storage-' . $storage_db['storage_mib'] . '-' . $storage_db['storage_descr'] . '.rrd');
      //  $new_rrd = $config['rrd_dir'] . '/' . $device['hostname'] . '/' . safename('storage-' . $storage_db['storage_mib'] . '-' . $storage_descr . '.rrd');
      //  if (is_file($old_rrd) && !is_file($new_rrd)) { rename($old_rrd, $new_rrd); print_warning("Moved RRD"); }
      //}

      dbUpdate($update, 'storage', '`storage_id` = ?', array($storage_db['storage_id']));
      $GLOBALS['module_stats']['storage']['updated']++; //echo('U');
      log_event("Storage updated: index $storage_index, mib $storage_mib, descr $storage_descr", $device, 'storage', $storage_db['storage_id']);
    } else {
      $GLOBALS['module_stats']['storage']['unchanged']++; //echo('.');
    }
  }
  print_debug_vars($update);
  if ($storage_ignore)
  {
    $GLOBALS['module_stats']['storage']['ignored']++;
  }
  $valid[$storage_mib][$storage_index] = $storage_descr;
}

// DOCME needs phpdoc block
// TESTME needs unit testing
// FIXME don't pass valid, use this as a global variable
function discover_processor(&$valid, $device, $processor_oid, $processor_index, $processor_type, $processor_descr, $processor_precision = 1, $value = NULL, $entPhysicalIndex = NULL, $hrDeviceIndex = NULL, $processor_returns_idle = 0)
{
  global $config;

  print_debug($device['device_id' ] . " -> $processor_oid, $processor_index, $processor_type, $processor_descr, $processor_precision, $value, $entPhysicalIndex, $hrDeviceIndex");

  // Check processor description
  if (!($processor_descr))
  {
    print_debug("Skipped by empty description: $processor_descr ");
    return FALSE;
  } else {
    $processor_descr = substr($processor_descr, 0, 64); // Limit descr to 64 chars accordingly as in DB
  }
  // Skip discovery processor if value not numeric or null(default)
  if ($value !== NULL) { $value = snmp_fix_numeric($value); } // Remove unnecessary spaces
  if (!(is_numeric($value) || $value === NULL))
  {
    print_debug("Skipped by not numeric value: $value, $processor_descr ");
    return FALSE;
  }

  // Check processor ignore filters
  foreach ($config['ignore_processor'] as $bi)        { if (strcasecmp($bi, $processor_descr) == 0)   { print_debug("Skipped by equals: $bi, $processor_descr "); return FALSE; } }
  foreach ($config['ignore_processor_string'] as $bi) { if (stripos($processor_descr, $bi) !== FALSE) { print_debug("Skipped by strpos: $bi, $processor_descr "); return FALSE; } }
  foreach ($config['ignore_processor_regexp'] as $bi) { if (preg_match($bi, $processor_descr) > 0)    { print_debug("Skipped by regexp: $bi, $processor_descr "); return FALSE; } }

  $params       = array('processor_index', 'entPhysicalIndex', 'hrDeviceIndex', 'processor_oid', 'processor_type', 'processor_descr', 'processor_precision', 'processor_returns_idle');
  //$params_state = array('processor_usage');

  $processor_db = dbFetchRow("SELECT * FROM `processors` WHERE `device_id` = ? AND `processor_index` = ? AND `processor_type` = ?", array($device['device_id'], $processor_index, $processor_type));
  if (!isset($processor_db['processor_id']))
  {
    $update = array('device_id' => $device['device_id']);
    if (!$processor_precision) { $processor_precision = 1; };
    foreach ($params as $param) { $update[$param] = ($$param === NULL ? array('NULL') : $$param); }

    if ($processor_precision != 1)
    {
      $value = round($value / $processor_precision, 2);
    }
    // The OID returns idle value, so we subtract it from 100.
    if ($processor_returns_idle) { $value = 100 - $value; }

    $update['processor_usage'] = $value;
    $id = dbInsert($update, 'processors');

    $GLOBALS['module_stats']['processors']['added']++; //echo('+');
    log_event("Processor added: index $processor_index, type $processor_type, descr $processor_descr", $device, 'processor', $id);
  } else {
    $update = array();
    foreach ($params as $param)
    {
      if ($$param != $processor_db[$param] ) { $update[$param] = ($$param === NULL ? array('NULL') : $$param); }
    }
    if (count($update))
    {
      dbUpdate($update, 'processors', '`processor_id` = ?', array($processor_db['processor_id']));
      $GLOBALS['module_stats']['processors']['updated']++; //echo('U');
      log_event("Processor updated: index $processor_index, mib $processor_type, descr $processor_descr", $device, 'processor', $processor_db['processor_id']);
    } else {
      $GLOBALS['module_stats']['processors']['unchanged']++; //echo('.');
    }
  }

  $valid[$processor_type][$processor_index] = 1;
}

// DOCME needs phpdoc block
// TESTME needs unit testing
// FIXME don't pass valid, use this as a global variable
// FIXME move all the other stuff over to $options too
// $options accepts:
//  - table
function discover_mempool(&$valid, $device, $mempool_index, $mempool_mib, $mempool_descr, $mempool_multiplier = 1, $mempool_total, $mempool_used, $mempool_hc = 0, $options = array())
{
  global $config;

  print_debug($device['device_id']." -> $mempool_index, $mempool_mib::" . $options['table'] . ", $mempool_descr, $mempool_multiplier, $mempool_total, $mempool_used");

  // Check mempool description
  if (!($mempool_descr))
  {
    return FALSE;
  } else {
    $mempool_descr = substr($mempool_descr, 0, 64); // Limit descr to 64 chars accordingly as in DB
  }

  // Init $options -> regular variables until all others have been converted to be inside $options (FIXME)
  $param_opt = array('table' => 'mempool_table');
  foreach ($param_opt as $key => $varname)
  {
    $$varname = ($options[$key] ? $options[$key] : NULL);
  }

  // Limit mempool_multiplier input to fit the MySQL field (FLOAT(14,5))
  $mempool_multiplier = round($mempool_multiplier, 5);

  // Check mempool ignore filters
  foreach ($config['ignore_mempool'] as $bi)        { if (strcasecmp($bi, $mempool_descr) == 0)   { print_debug("Skipped by equals: $bi, $mempool_descr "); return FALSE; } }
  foreach ($config['ignore_mempool_string'] as $bi) { if (stripos($mempool_descr, $bi) !== FALSE) { print_debug("Skipped by strpos: $bi, $mempool_descr "); return FALSE; } }
  foreach ($config['ignore_mempool_regexp'] as $bi) { if (preg_match($bi, $mempool_descr) > 0)    { print_debug("Skipped by regexp: $bi, $mempool_descr "); return FALSE; } }

  $params       = array('mempool_index', 'mempool_mib', 'mempool_descr', 'mempool_multiplier', 'mempool_hc', 'mempool_table');
  $params_state = array('mempool_total', 'mempool_used', 'mempool_free', 'mempool_perc');

  if (!is_numeric($mempool_total) || $mempool_total <= 0 || !is_numeric($mempool_used))
  {
    print_debug("Skipped by not numeric or too small mempool total ($mempool_total) or used ($mempool_used)"); return FALSE;
  }
  if (!$mempool_multiplier) { $mempool_multiplier = 1; }

  if ( !(isset($options['table']) && isset($config['mibs'][$mempool_mib]['mempool'][$options['table']])))
  {
    // Scale for non-definitions based
    $mempool_total *= $mempool_multiplier;
    $mempool_used  *= $mempool_multiplier;
  }
  $mempool_free = $mempool_total - $mempool_used;
  $mempool_perc = round($mempool_used / $mempool_total * 100, 2);
  $mempool_db   = dbFetchRow("SELECT * FROM `mempools` WHERE `device_id` = ? AND `mempool_index` = ? AND `mempool_mib` = ?", array($device['device_id'], $mempool_index, $mempool_mib));

  if (!isset($mempool_db['mempool_id']))
  {
    $update = array('device_id' => $device['device_id']);
    foreach ($params as $param) { $update[$param] = $$param; }
    foreach ($params_state as $param) { $update[$param] = $$param; }
    $update['mempool_polled'] = time();
    $id = dbInsert($update, 'mempools');

    //$update_state = array('mempool_id' => $id, 'mempool_polled' => time());
    //foreach ($params_state as $param) { $update_state[$param] = $$param; }
    //dbInsert($update_state, 'mempools-state');
    $GLOBALS['module_stats']['mempools']['added']++; //echo('+');
    log_event("Memory pool added: mib $mempool_mib" . ($mempool_table ? "::$mempool_table" : '') . ", index $mempool_index, descr $mempool_descr", $device, 'mempool', $id);
  } else {
    $update = array();
    foreach ($params as $param)
    {
      if ($$param != $mempool_db[$param]) { $update[$param] = $$param; }
    }
    if (count($update))
    {
      dbUpdate($update, 'mempools', '`mempool_id` = ?', array($mempool_db['mempool_id']));
      $GLOBALS['module_stats']['mempools']['updated']++; //echo('U');
      log_event("Memory pool updated: mib $mempool_mib" . ($mempool_table ? "::$mempool_table" : '') . ", index $mempool_index, descr $mempool_descr", $device, 'mempool', $mempool_db['mempool_id']);
    } else {
      $GLOBALS['module_stats']['mempools']['unchanged']++; //echo('.');
    }
  }

  $valid[$mempool_mib][$mempool_index] = 1;
}

// DOCME needs phpdoc block
// TESTME needs unit testing
// Valid params: description, type (fuser, toner, etc), index, oid, mib, level, capacity, capacity_oid, colour
// FIXME don't pass valid, use this as a global variable
function discover_printersupply(&$valid, $device, $options = array())
{
  global $config;

  // Check for valid description
  if (!isset($options['description']) || $options['description'] == '') { return FALSE; }

  // Check toner ignore filters
  // FIXME rename variables (s/toner/supply)!
  foreach ($config['ignore_toner'] as $bi)        { if (strcasecmp($bi, $options['description']) == 0)   { print_debug("Skipped by equals: $bi, " . $options['description']); return FALSE; } }
  foreach ($config['ignore_toner_string'] as $bi) { if (stripos($options['description'], $bi) !== FALSE) { print_debug("Skipped by strpos: $bi, " . $options['description']); return FALSE; } }
  foreach ($config['ignore_toner_regexp'] as $bi) { if (preg_match($bi, $options['description']) > 0)    { print_debug("Skipped by regexp: $bi, " . $options['description']); return FALSE; } }

  if (dbFetchCell("SELECT COUNT(`supply_id`) FROM `printersupplies` WHERE `device_id` = ? AND `supply_index` = ?", array($device['device_id'], $options['index'])) == '0')
  {
    $supply_insert = array('device_id'            => $device['device_id'],
                           'supply_index'         => $options['index'],
                           'supply_mib'           => $options['mib'],
                           'supply_descr'         => $options['description'],
                           'supply_capacity'      => $options['capacity'],
                           'supply_capacity_oid'  => $options['capacity_oid'],
                           'supply_oid'           => $options['oid'],
                           'supply_value'         => $options['level'],
                           'supply_type'          => $options['type'],
                           'supply_colour'        => $options['colour']);
    $supply_id = dbInsert($supply_insert, 'printersupplies');
    $GLOBALS['module_stats']['printersupplies']['added']++; //echo('+');
    log_event("Printer supply added: " . $options['description'] . " (" . nicecase($options['type']) . ", index " . $options['index'] . ")", $device, 'printersupply', $supply_id);
  } else {
    $supply = dbFetchRow("SELECT * FROM `printersupplies` WHERE `device_id` = ? AND `supply_index` = ?", array($device['device_id'], $options['index']));

    $field_map = array('description'  => 'supply_descr',
                       'type'         => 'supply_type',
                       'mib'          => 'supply_mib',
                       'colour'       => 'supply_colour',
                       'capacity'     => 'supply_capacity',
                       'oid'          => 'supply_oid',
                       'capacity_oid' => 'supply_capacity_oid');

    $update = array();
    foreach ($field_map as $param => $db_param)
    {
      if ($options[$param] != $supply[$db_param]) { $update[$db_param] = $options[$param]; }
    }

    if (count($update))
    {
      dbUpdate($update, 'printersupplies', '`supply_id` = ?', array($supply['supply_id']));
      $GLOBALS['module_stats']['printersupplies']['updated']++; //echo('U');
      log_event("Printer supply updated: " . $options['description'] . " (" . nicecase($options['type']) . ", index " . $options['index'] . ")", $device, 'printersupply', $supply_id);
    } else {
      $GLOBALS['module_stats']['printersupplies']['unchanged']++; //echo('.');
    }
  }

  $valid[$options['mib']][$options['index']] = 1;
}

// DOCME needs phpdoc block
// TESTME needs unit testing
// FIXME don't pass valid, use this as a global variable
function discover_inventory(&$valid, $device, $index, $inventory_tmp, $mib = 'entPhysical')
{
  $entPhysical_oids = array('entPhysicalDescr', 'entPhysicalClass', 'entPhysicalName',
                            'entPhysicalHardwareRev', 'entPhysicalFirmwareRev', 'entPhysicalSoftwareRev',
                            'entPhysicalAlias', 'entPhysicalAssetID', 'entPhysicalIsFRU',
                            'entPhysicalModelName', 'entPhysicalVendorType', 'entPhysicalSerialNum',
                            'entPhysicalContainedIn', 'entPhysicalParentRelPos', 'entPhysicalMfgName',
                            'ifIndex');

  $numeric_oids     = array('entPhysicalContainedIn', 'entPhysicalParentRelPos', 'ifIndex'); // DB type 'int'

  if (!is_array($inventory_tmp) || !is_numeric($index)) { return FALSE; }
  $inventory = array('entPhysicalIndex' => $index);
  foreach ($entPhysical_oids as $oid)
  {
    $inventory[$oid] = str_replace(array('"', "'"), '', $inventory_tmp[$oid]);
  }

  if (!isset($inventory['entPhysicalModelName'])) { $inventory['entPhysicalModelName'] = $inventory['entPhysicalName']; }

  $query = 'SELECT * FROM `entPhysical` WHERE `device_id` = ? AND `entPhysicalIndex` = ?'; // AND `inventory_mib` = $inventory_mib
  $inventory_db = dbFetchRow($query, array($device['device_id'], $index));

  if (!is_array($inventory_db))
  {
    $inventory['device_id'] = $device['device_id'];
    $id = dbInsert($inventory, 'entPhysical');
    print_debug('Inventory added: class '.$inventory['entPhysicalClass'].', name '.$inventory['entPhysicalName'].', index '.$index);
    $GLOBALS['module_stats']['inventory']['added']++; //echo('+');
  } else {
    foreach ($entPhysical_oids as $oid)
    {
      if ($inventory[$oid] != $inventory_db[$oid])
      {
        if (in_array($oid, $numeric_oids) && $inventory[$oid] == '')
        {
          $update[$oid] = array('NULL');
        } else {
          $update[$oid] = $inventory[$oid];
        }
      }
    }
    // Reset deleted date if not empty
    print_debug_vars($inventory_db);
    if (!empty($inventory_db['deleted']))
    {
      $update['deleted'] = array('NULL');
    }
    if (count($update))
    {
      print_debug_vars($update);
      $id = $inventory_db['entPhysical_id'];
      dbUpdate($update, 'entPhysical', '`device_id` = ? AND `entPhysicalIndex` = ?', array($device['device_id'], $index));
      print_debug('Inventory updated: class '.$inventory['entPhysicalClass'].', name '.$inventory['entPhysicalName'].', index '.$index);
      $GLOBALS['module_stats']['inventory']['updated']++; //echo('U');
    } else {
      $GLOBALS['module_stats']['inventory']['unchanged']++; //echo('.');
    }
  }
  $valid[$mib][$index] = 1;
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function check_valid_inventory($device, $valid_tmp)
{
  // Note. For now $valid mib type not used
  $valid = array();
  foreach ($valid_tmp as $mib => $array)
  {
    $valid += $array;
  }

  $query = 'SELECT * FROM `entPhysical` WHERE `device_id` = ?'; // AND `inventory_mib` = $inventory_mib
  $entries = dbFetchRows($query, array($device['device_id']));

  if (is_array($entries) && count($entries))
  {
    foreach ($entries as $entry)
    {
      $index = $entry['entPhysicalIndex'];
      if (!$valid[$index] && $entry['deleted'] == NULL)
      {
        dbUpdate(array('deleted' => array('NOW()')),'entPhysical', "`entPhysical_id` = ?", array($entry['entPhysical_id']));
        // dbDelete('entPhysical', "`entPhysical_id` = ?", array($entry['entPhysical_id']));
        print_debug('Inventory deleted: class '.$entry['entPhysicalClass'].', name '.$entry['entPhysicalName'].', index '.$index);
        $GLOBALS['module_stats']['inventory']['deleted']++; //echo('-');
      }
    }
  }
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function is_bad_xdp($hostname, $platform = '')
{
  global $config;

  if (is_array($config['bad_xdp']))
  {
    foreach ($config['bad_xdp'] as $bad_xdp)
    {
      if (strstr($hostname, $bad_xdp)) { return TRUE; }
    }
  }

  if (is_array($config['bad_xdp_regexp']))
  {
    foreach ($config['bad_xdp_regexp'] as $bad_xdp)
    {
      if (preg_match($bad_xdp ."i", $hostname)) { return TRUE; }
    }
  }

  if ($platform)
  {
    foreach ($config['bad_xdp_platform'] as $bad_xdp)
    {
      if (stripos($platform, $bad_xdp) !== FALSE) { return TRUE; }
    }
  }

  return FALSE;
}

function discovery_module_excluded($device, $module)
{
  global $config;

  if (in_array($module, $config['os_group'][$device['os_group']]['discovery_blacklist']))
  {
    // Module is blacklisted for this OS group.
    print_debug("Module [ $module ] is in the blacklist for ".$device['os_group']);
    return true;
  } elseif(in_array($module, $config['os'][$device['os']]['discovery_blacklist']))
  {
    // Module is blacklisted for this OS.
    print_debug("Module [ $module ] is in the blacklist for ".$device['os']);
    return true;
  } else {
    return false;
  }
}

// EOF
