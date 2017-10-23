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

switch ($device['os'])
{
  case 'linux':
  case 'endian':
  case 'openwrt':
  case 'ddwrt':
    list(,,$version) = explode (' ', $poll_device['sysDescr']);

    $kernel = $version;

    // Use agent DMI data if available
    if (isset($agent_data['dmi']))
    {
      if ($agent_data['dmi']['system-product-name'])
      {
        $hw = ($agent_data['dmi']['system-manufacturer'] ? $agent_data['dmi']['system-manufacturer'] . ' ' : '') . $agent_data['dmi']['system-product-name'];

        // Clean up "Dell Computer Corporation" and "Intel Corporation"
        $hw = str_replace(' Computer Corporation', '', $hw);
        $hw = str_replace(' Corporation', '', $hw);
      }

      // If these exclude lists grow any further we should move them to definitions...
      if (isset($agent_data['dmi']['system-serial-number'])
        && $agent_data['dmi']['system-serial-number'] != '............'
        && $agent_data['dmi']['system-serial-number'] != 'Not Specified'
        && $agent_data['dmi']['system-serial-number'] != '0123456789')
      {
        $serial = $agent_data['dmi']['system-serial-number'];
      }

      if (isset($agent_data['dmi']['chassis-asset-tag'])
        && $agent_data['dmi']['chassis-asset-tag'] != '....................'
        && strcasecmp($agent_data['dmi']['chassis-asset-tag'], 'To be filled by O.E.M.') != 0
        && $agent_data['dmi']['chassis-asset-tag'] != 'No Asset Tag')
      {
        $asset_tag = $agent_data['dmi']['chassis-asset-tag'];
      }
      elseif (isset($agent_data['dmi']['baseboard-asset-tag'])
        && $agent_data['dmi']['baseboard-asset-tag'] != '....................'
        && strcasecmp($agent_data['dmi']['baseboard-asset-tag'], 'To be filled by O.E.M.') != 0
        && $agent_data['dmi']['baseboard-asset-tag'] != 'Tag 12345')
      {
        $asset_tag = $agent_data['dmi']['baseboard-asset-tag'];
      }
    }

    if (is_array($entPhysical) && !$hw)
    {
      $hw = $entPhysical['entPhysicalDescr'];
      if (!empty($entPhysical['entPhysicalSerialNum']))
      {
        $serial = $entPhysical['entPhysicalSerialNum'];
      }
    }
    
    if (!$hardware)
    {
      $hardware = rewrite_unix_hardware($poll_device['sysDescr'], $hw);
    }
    break;

  case 'aix':
    list($hardware,,$os_detail,) = explode("\n", $poll_device['sysDescr']);
    if (preg_match('/: 0*(\d+\.)0*(\d+\.)0*(\d+\.)(\d+)/', $os_detail, $matches))
    {
      // Base Operating System Runtime AIX version: 05.03.0012.0001
      $version = $matches[1] . $matches[2] . $matches[3] . $matches[4];
    }

    $hardware_model = snmp_get($device, 'aixSeMachineType.0', '-Oqv', 'IBM-AIX-MIB');
    if ($hardware_model)
    {
      list(,$hardware_model) = explode(',', $hardware_model);

      $serial = snmp_get($device, 'aixSeSerialNumber.0', '-Oqv', 'IBM-AIX-MIB');
      list(,$serial) = explode(',', $serial);

      $hardware .= " ($hardware_model)";
    }
    break;

  case 'freebsd':
    preg_match('/FreeBSD ([\d\.]+-[\w\d-]+)/i', $poll_device['sysDescr'], $matches);
    $kernel = $matches[1];
    $hardware = rewrite_unix_hardware($poll_device['sysDescr']);
    break;

  case 'dragonfly':
    list(,,$version,,,$features) = explode (' ', $poll_device['sysDescr']);
    $hardware = rewrite_unix_hardware($poll_device['sysDescr']);
    break;

  case 'netbsd':
    list(,,$version,,,$features) = explode (' ', $poll_device['sysDescr']);
    $features = str_replace('(', '', $features);
    $features = str_replace(')', '', $features);
    $hardware = rewrite_unix_hardware($poll_device['sysDescr']);
    break;

  case 'openbsd':
  case 'solaris':
  case 'opensolaris':
    list(,,$version,$features) = explode (' ', $poll_device['sysDescr']);
    $features = str_replace('(', '', $features);
    $features = str_replace(')', '', $features);
    $hardware = rewrite_unix_hardware($poll_device['sysDescr']);
    break;

  case 'darwin':
    list(,,$version) = explode (' ', $poll_device['sysDescr']);
    $hardware = rewrite_unix_hardware($poll_device['sysDescr']);
    break;

  case 'monowall':
  case 'pfsense':
    list(,,$version,,, $kernel) = explode(' ', $poll_device['sysDescr']);
    $distro = $device['os'];
    $hardware = rewrite_unix_hardware($poll_device['sysDescr']);
    break;

  case 'freenas':
  case 'nas4free':
    preg_match('/Software: FreeBSD ([\d\.]+-[\w\d-]+)/i', $poll_device['sysDescr'], $matches);
    $version = $matches[1];
    $hardware = rewrite_unix_hardware($poll_device['sysDescr']);
    break;

  case 'qnap':
    $hardware = $entPhysical['entPhysicalName'];
    $version  = $entPhysical['entPhysicalFirmwareRev'];
    $serial   = $entPhysical['entPhysicalSerialNum'];
    break;

  case 'ipso':
    // IPSO Bastion-1 6.2-GA039 releng 1 04.14.2010-225515 i386
    // IP530 rev 00, IPSO ruby.infinity-insurance.com 3.9-BUILD035 releng 1515 05.24.2005-013334 i386
    if (preg_match('/IPSO [^ ]+ ([^ ]+) /', $poll_device['sysDescr'], $matches))
    {
      $version = $matches[1];
    }

    $data = snmp_get_multi($device, 'ipsoChassisMBType.0 ipsoChassisMBRevNumber.0', '-OQUs', 'NOKIA-IPSO-SYSTEM-MIB');
    if (isset($data[0]))
    {
      $hw = $data[0]['ipsoChassisMBType'] . ' rev ' . $data[0]['ipsoChassisMBRevNumber'];
    }
    $hardware = rewrite_unix_hardware($poll_device['sysDescr'], $hw);
    break;

  case 'sofaware':
    // EMBEDDED-NGX-MIB::swHardwareVersion.0 = "1.3T ADSL-A"
    // EMBEDDED-NGX-MIB::swHardwareType.0 = "SBox-200-B"
    // EMBEDDED-NGX-MIB::swLicenseProductName.0 = "Safe@Office 500, 25 nodes"
    // EMBEDDED-NGX-MIB::swFirmwareRunning.0 = "8.2.26x"
    $data = snmp_get_multi($device, 'swHardwareVersion.0 swHardwareType.0 swLicenseProductName.0 swFirmwareRunning.0', '-OQUs', 'EMBEDDED-NGX-MIB');
    if (isset($data[0]))
    {
      list($hw) = explode(',', $data[0]['swLicenseProductName']);
      $hardware = $hw . ' ' . $data[0]['swHardwareType'] . ' ' . $data[0]['swHardwareVersion'];
      $version  = $data[0]['swFirmwareRunning'];
    }
    break;
}

// Has 'distro' script data already been returned via the agent?
if (isset($agent_data['distro']) && isset($agent_data['distro']['SCRIPTVER']))
{
  $distro     = $agent_data['distro']['DISTRO'];
  // Older version of the script used DISTROVER, newer ones use VERSION :-(
  $distro_ver = (isset($agent_data['distro']['DISTROVER']) ? $agent_data['distro']['DISTROVER'] : $agent_data['distro']['VERSION']);
  $kernel     = $agent_data['distro']['KERNEL'];
  $arch       = $agent_data['distro']['ARCH'];
  $virt       = $agent_data['distro']['VIRT'];
} else {

  // Distro "extend" support
  //if (is_device_mib($device, 'NET-SNMP-EXTEND-MIB'))
  //{
  //  //NET-SNMP-EXTEND-MIB::nsExtendOutput1Line."distro" = STRING: Linux|4.4.0-77-generic|amd64|Ubuntu|16.04|kvm
  //  $os_data = snmp_get_oid($device, '.1.3.6.1.4.1.8072.1.3.2.3.1.1.6.100.105.115.116.114.111', 'NET-SNMP-EXTEND-MIB');
  //}
  if (!$os_data && is_device_mib($device, 'UCD-SNMP-MIB'))
  {
    $os_data = snmp_get_oid($device, '.1.3.6.1.4.1.2021.7890.1.3.1.1.6.100.105.115.116.114.111', 'UCD-SNMP-MIB');

    if (!$os_data) // No "extend" support, try "exec" support
    {
      $os_data = snmp_get_oid($device, '.1.3.6.1.4.1.2021.7890.1.101.1', 'UCD-SNMP-MIB');
    }
  }

  // Disregard data if we're just getting an error.
  if (!$os_data || strpos($os_data, '/usr/bin/distro') !== FALSE)
  {
    unset($os_data);
  }
  else if (str_contains($os_data, '|'))
  {
    // distro version less than 1.2: "Linux|3.2.0-4-amd64|amd64|Debian|7.5"
    // distro version 1.2 and above: "Linux|4.4.0-53-generic|amd64|Ubuntu|16.04|kvm"
    list($osname, $kernel, $arch, $distro, $distro_ver, $virt) = explode('|', $os_data);
  } else {
    // Old distro, not supported now: "Ubuntu 12.04"
    list($distro, $distro_ver) = explode(' ', $os_data);
  }
}

// Use 'os' script virt output, if virt-what agent is not used
if (!isset($agent_data['virt']['what']) && isset($virt))
{
  $agent_data['virt']['what'] = $virt;
}

// Use agent virt-what data if available
if (isset($agent_data['virt']['what']))
{
  // We cycle through every line here, the previous one is overwritten.
  // This is OK, as virt-what prints general-to-specific order and we want most specific.
  foreach (explode("\n", $agent_data['virt']['what']) as $virtwhat)
  {
    if (isset($config['virt-what'][$virtwhat]))
    {
      $hardware = $config['virt-what'][$virtwhat];
    }
  }
}

if (!$features && isset($distro))
{
  $features = "$distro $distro_ver";
}

unset($hw, $data);

// EOF
