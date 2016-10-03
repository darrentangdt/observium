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


if (strstr($poll_device['sysObjectID'], '.1.3.6.1.4.1.13742.4'))
{
  // PDU-MIB::firmwareVersion.0 = STRING: 01.04.00.9851
  // PDU-MIB::serialNumber.0 = STRING: AEQ0900002
  // PDU-MIB::objectName.0 = STRING: DPXR8-20L
  $data = snmpget_cache_multi($device, 'objectName.0 firmwareVersion.0 serialNumber.0', array(), 'PDU-MIB');

  if (is_array($data[0]))
  {
    $hardware = $data[0]['objectName'];
    $version  = $data[0]['firmwareVersion'];
    $serial   = $data[0]['serialNumber'];
  }
} else {
  // PDU2-MIB::pduModel.1 = STRING: PX2-5486
  // PDU2-MIB::pduSerialNumber.1 = STRING: QRN4850046
  // PDU2-MIB::boardFirmwareVersion.1.mainController.1 = STRING: 3.1.0.5-42165
  $data = snmpget_cache_multi($device, 'pduModel.1 pduSerialNumber.1', array(), 'PDU2-MIB');

  if (is_array($data[1]))
  {
    $hardware = $data[1]['pduModel'];
    $version  = snmp_get($device, 'boardFirmwareVersion.1.mainController.1', '-Osqv', 'PDU2-MIB');
    $serial   = $data[1]['pduSerialNumber'];
  }
}

//EOF
