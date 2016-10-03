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

// DELL-RAC-MIB::drsFirmwareVersion.0 = STRING: "1.23.23"
// DELL-RAC-MIB::drsProductShortName.0 = STRING: "iDRAC7"
// DELL-RAC-MIB::drsSystemServiceTag.0 = STRING: "CGJ2H5J"

$data = snmp_get_multi($device, 'drsFirmwareVersion.0 drsProductShortName.0 drsSystemServiceTag.0 drsProductChassisAssetTag.0', '-OQUs', 'DELL-RAC-MIB');
if (is_array($data[0]))
{
  $version   = $data[0]['drsFirmwareVersion'];
  $hardware  = $data[0]['drsProductShortName'];
  $serial    = $data[0]['drsSystemServiceTag'];
  $asset_tag = $data[0]['drsProductChassisAssetTag'];
}

// DELL-RAC-MIB::drsProductURL.0 = STRING: "https://192.168.2.1:443"
$ra_url_http = snmp_get($device, 'drsProductURL.0', '-Oqv', 'DELL-RAC-MIB');

if ($ra_url_http != '')
{
  set_dev_attrib($device, 'ra_url_http', $ra_url_http);
} else {
  // Not found in DELL-RAC-MIB, try getting from IDRAC-MIB-SMIv2 instead

  $ra_url_http = snmp_get($device, 'racURL.0', '-Oqv', 'IDRAC-MIB-SMIv2');
  if ($ra_url_http != '')
  {
    set_dev_attrib($device, 'ra_url_http', $ra_url_http);
  } else {
    del_dev_attrib($device, 'ra_url_http');
  }
}

// EOF
