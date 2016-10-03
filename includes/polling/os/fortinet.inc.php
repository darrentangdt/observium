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

if (strstr($poll_device['sysObjectID'], '.1.3.6.1.4.1.12356.105'))
{
  //FORTINET-FORTIMAIL-MIB::fmlSysModel.0 = STRING: FortiMail-VM-HV
  //FORTINET-FORTIMAIL-MIB::fmlSysSerial.0 = STRING: FEVM040000058143
  //FORTINET-FORTIMAIL-MIB::fmlSysVersion.0 = STRING: v5.3,build599,160527 (5.3.3 GA)
  //FORTINET-FORTIMAIL-MIB::fmlSysVersionAv.0 = STRING: 39.497(09/20/2016 01:11)
  $data = snmpget_cache_multi($device, 'fmlSysModel.0 fmlSysSerial.0 fmlSysVersion.0 fmlSysVersionAv.0', array(), 'FORTINET-FORTIMAIL-MIB');
  if (is_array($data[0]))
  {
    $hardware = $data[0]['fmlSysModel'];
    $serial   = $data[0]['fmlSysSerial'];
    $version  = $data[0]['fmlSysVersion'];
  }
} else {

  $serial = snmp_get($device, 'fnSysSerial.0', '-Ovq', 'FORTINET-CORE-MIB');

  $hardware = rewrite_definition_hardware($device, $poll_device['sysObjectID']);
  $fn_type  = rewrite_definition_type($device, $poll_device['sysObjectID']);
  if (!empty($fn_type))
  {
    $type = $fn_type;
  }
}

// EOF
