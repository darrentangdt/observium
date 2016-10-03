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

$data = snmpget_cache_multi($device, 'mimosaDeviceName.0 mimosaFirmwareVersion.0 mimosaSerialNumber.0', array(), 'MIMOSA-NETWORKS-BFIVE-MIB');
$hardware   = $data[0]['mimosaDeviceName']; // Not sure, seems as this is sysName
$serial     = $data[0]['mimosaSerialNumber'];
$version    = $data[0]['mimosaFirmwareVersion'];

// EOF