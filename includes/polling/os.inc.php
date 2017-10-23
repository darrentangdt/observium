<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage poller
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

// Force rediscover os if os definition changed
if (!isset($config['os'][$device['os']]))
{
  print_debug('OS name change detected, forced os rediscover.');
  force_discovery($device, 'os');
}

// Cache hardware/version/serial info from ENTITY-MIB (if possible use inventory module data)
if (is_device_mib($device, 'ENTITY-MIB') &&
    (in_array($device['os_group'], array('unix', 'cisco')) ||
     in_array($device['os'], array('acme', 'nos', 'ibmnos', 'acsw', 'fabos', 'wlc', 'h3c', 'hh3c', 'hpuww'))))
{
  // Get entPhysical tables for some OS and OS groups
  if ($config['discovery_modules']['inventory'])
  {
    $entPhysical = dbFetchRow('SELECT * FROM `entPhysical` WHERE `device_id` = ? AND `entPhysicalContainedIn` = ?', array($device['device_id'], '0'));
  } else {
    switch (TRUE)
    {
      case ($device['os_group'] == 'cisco' || in_array($device['os'], array('acme', 'h3c', 'hh3c'))):
        $oids = 'entPhysicalDescr.1 entPhysicalSerialNum.1 entPhysicalModelName.1 entPhysicalContainedIn.1 entPhysicalName.1 entPhysicalSoftwareRev.1';
        break;
      case ($device['os'] == 'qnap'):
        $oids = 'entPhysicalDescr.1 entPhysicalName.1 entPhysicalSerialNum.1 entPhysicalFirmwareRev.1';
        break;
      case ($device['os'] == 'ibmnos'):
        $oids = 'entPhysicalName.1 entPhysicalSerialNum.1 entPhysicalSoftwareRev.1';
        break;
      case ($device['os'] == 'wlc'):
        $oids = 'entPhysicalDescr.1 entPhysicalModelName.1 entPhysicalSoftwareRev.1';
        break;
      default:
        $oids = 'entPhysicalDescr.1 entPhysicalSerialNum.1';
    }
    $data = snmp_get_multi($device, $oids, '-OQUs', 'ENTITY-MIB');
    $entPhysical = $data[1];
  }

  if (!empty($entPhysical['entPhysicalDescr']))
  {
    // FIXME this should be in definitions
    $entPhysical['entPhysicalDescr'] = str_replace(array(' Inc.', ' Computer Corporation', ' Corporation'), '', $entPhysical['entPhysicalDescr']);
    $entPhysical['entPhysicalDescr'] = str_replace('IBM IBM', 'IBM', $entPhysical['entPhysicalDescr']);

    if (strpos($entPhysical['entPhysicalSerialNum'], '..') !== FALSE)
    {
      $entPhysical['entPhysicalSerialNum'] = '';
    }
  } else {
    unset($entPhysical);
  }
}

// List of variables/params parsed from sysDescr or snmp for each os
$os_metatypes = array('serial', 'version', 'hardware', 'features', 'asset_tag', 'ra_url_http');
$os_values    = array();

// Find OS-specific SNMP data via OID regex: serial number, version number, hardware description, features, asset tag
foreach ($config['os'][$device['os']]['sysDescr_regex'] as $pattern)
{
  if (preg_match($pattern, $poll_device['sysDescr'], $matches))
  {
    foreach ($os_metatypes as $metatype)
    {
      if (isset($matches[$metatype]) && $matches[$metatype] != '')
      {
        $os_values[$metatype] = $matches[$metatype];

        // Additional sub-data (up to 2), ie hardware1, hardware2
        // See example in timos definition
        for ($num = 1; $num <= 2; $num++)
        {
          if (isset($matches[$metatype.$num]) && $matches[$metatype.$num] != '')
          {
            $os_values[$metatype] .= ' ' . $matches[$metatype.$num];
          } else {
            break;
          }
        }

        $$metatype = $os_values[$metatype]; // Set metatype variable
        print_debug("Added OS param from sysDescr parse: '$metatype' = '" . $os_values[$metatype] . "'");
      }
    }

    break; // Do not match other sysDescr regex, use correct patterns order instead!
  }
}

// Find MIB-specific SNMP data via OID fetch: serial number, version number, hardware description, features, asset tag
foreach ($os_metatypes as $metatype)
{
  if (!isset($os_values[$metatype])) // Skip search if already set by sysDescr regex
  {
    foreach (get_device_mibs($device, TRUE) as $mib) // Check every MIB supported by the device, in order
    {
      if (isset($config['mibs'][$mib][$metatype]))
      {
        foreach ($config['mibs'][$mib][$metatype] as $entry)
        {
          if (isset($entry['oid_num'])) // Use numeric OID if set, otherwise fetch text based string
          {
            $value = trim(snmp_hexstring(snmp_get($device, $entry['oid_num'], '-Oqv')));
          } else {
            $value = trim(snmp_hexstring(snmp_get($device, $entry['oid'], '-Oqv', $mib)));
          }

          if ($GLOBALS['snmp_status'] && $value != '')
          {
            // Field found (no SNMP error), perform optional transformations.
            $os_values[$metatype] = string_transform($value, $entry['transformations']);

            $$metatype = $os_values[$metatype]; // Set metatype variable
            print_debug("Added OS param from SNMP definition walk: '$metatype' = '$value'");

            // Exit both foreach loops and move on to the next field.
            break 2;
          }
        }
      }
    }
  }
}
print_debug_vars($os_values);

// Include OS-specific poller code, if available
if (is_file($config['install_dir'] . "/includes/polling/os/".$device['os'].".inc.php"))
{
  print_cli_data("OS Poller", 'OS', 2);
  // OS Specific
  include($config['install_dir'] . "/includes/polling/os/".$device['os'].".inc.php");
}
else if ($device['os_group'] && is_file($config['install_dir'] . "/includes/polling/os/".$device['os_group'].".inc.php"))
{
  // OS Group-specific code as fallback, if OS-specific code does not exist
  print_cli_data("OS Poller", 'Group', 2);

  include($config['install_dir'] . "/includes/polling/os/".$device['os_group'].".inc.php");
} else {
  print_cli_data("OS Poller", '%rGeneric%w', 2);
}

// Now recheck and set empty os params from os/group by regex/definitions values
foreach ($os_values as $metatype => $value)
{
  if (!isset($$metatype) || $$metatype == '' || str_istarts($$metatype, 'generic'))
  {
    print_debug("Redded OS param from sysDescr parse or SNMP definition walk: '$metatype' = '$value'");
    $$metatype = $value;
  }
}

print_cli_data("Hardware", ($hardware ?: "%b<empty>%n"));
print_cli_data("Version",  ($version ?: "%b<empty>%n"));
print_cli_data("Features", ($features ?: "%b<empty>%n"));
print_cli_data("Serial",   ($serial ?: "%b<empty>%n"));
print_cli_data("Asset",    ($asset_tag ?: "%b<empty>%n"));

// Remote access URLs are stored as a device attribute instead of a database field
foreach (array('ra_url_http') as $ra_url)
{
  if (isset($$ra_url))
  {
    set_dev_attrib($device, $ra_url, $$ra_url);
    print_cli_data("Management URL", $ra_url_http);
  }
  else if (isset($attribs[$ra_url]))
  {
    del_dev_attrib($device, $ra_url);
  }
}

echo(PHP_EOL);
foreach ($os_additional_info as $header => $entries)
{
  print_cli_heading($header, 3);
  foreach ($entries as $field => $entry)
  {
    print_cli_data($field, $entry, 3);
  }
  echo(PHP_EOL);
}

// Fields notified in event log
$update_fields = array('version', 'features', 'hardware', 'serial', 'kernel', 'distro', 'distro_ver', 'arch', 'asset_tag');

// Log changed variables
foreach ($update_fields as $field)
{
  if (isset($$field)) { $$field = snmp_fix_string($$field); } // Fix unprintable chars

  if ((isset($$field) || strlen($device[$field])) && $$field != $device[$field])
  {
    $update_array[$field] = $$field;
    log_event(nicecase($field)." changed: '".$device[$field]."' -> '".$update_array[$field]."'", $device, 'device', $device['device_id']);
  }
}

// Here additional fields, change only if not set already
foreach (array('type', 'icon') as $field)
{
  if (!isset($$field) || isset($attribs['override_'.$field])) { continue; }

  if ($device[$field] == "unknown" || empty($device[$field]) || ($field == 'type' && $$field != $device[$field]))
  {
    $update_array[$field] = $$field;
    log_event(nicecase($field)." changed: '".$device[$field]."' -> '".$update_array[$field]."'", $device, 'device', $device['device_id']);
  }
}

unset($entPhysical, $oids, $hw, $os_additional_info, $entry);

// EOF
