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

if (is_device_mib($device, 'EATON-EPDU-MA-MIB'))
{
  //EATON-EPDU-MA-MIB::firmwareVersion.0 = STRING: 01.01.01
  //EATON-EPDU-MA-MIB::serialNumber.0 = STRING: ADZC050100
  //EATON-EPDU-MA-MIB::hardwareRev.0 = INTEGER: 26
  //EATON-EPDU-MA-MIB::objectName.0 = STRING: PW104MA1UB44
  //EATON-EPDU-MA-MIB::objectInstance.0 = STRING: Master_Switch_2
  $data = snmpget_cache_multi($device, 'firmwareVersion.0 serialNumber.0 objectName.0', array(), 'EATON-EPDU-MA-MIB');
  if (is_array($data[0]))
  {
    $hardware = $data[0]['objectName'];
    $version  = $data[0]['firmwareVersion'];
    $serial   = $data[0]['serialNumber'];
  }
} else {

  //EATON-EPDU-MIB::productName.0 = STRING: "EPDU MI 40U-A IN: CS8365 35A 3P OUT: 42XC13"
  //EATON-EPDU-MIB::partNumber.0 = STRING: "EMI315-10"
  //EATON-EPDU-MIB::serialNumber.0 = STRING: "B6xxxxx180"
  //EATON-EPDU-MIB::firmwareVersion.0 = STRING: "02.00.0041"
  //EATON-EPDU-MIB::unitName.0 = STRING: "PDU"
  //EATON-EPDU-MIB::lcdControl.0 = INTEGER: notApplicable(0)
  //EATON-EPDU-MIB::clockValue.0 = STRING: 2016-7-9,12:56:56.0,+0:00
  //EATON-EPDU-MIB::temperatureScale.0 = INTEGER: celsius(0)

  //$hardware_long = trim(snmp_get($device, 'productName.0', '-OQv', 'EATON-EPDU-MIB', ''),'" ');
  $data = snmpget_cache_multi($device, 'firmwareVersion.0 serialNumber.0 partNumber.0', array(), 'EATON-EPDU-MIB');
  if (is_array($data[0]))
  {
    $hardware = $data[0]['partNumber'];
    $version  = $data[0]['firmwareVersion'];
    $serial   = $data[0]['serialNumber'];
  }
}

// EOF
