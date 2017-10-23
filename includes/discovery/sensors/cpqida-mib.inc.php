<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage discovery
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

// Controllers

$oids = snmpwalk_cache_oid($device, 'cpqDaCntlrEntry', array(), 'CPQIDA-MIB');

foreach ($oids as $index => $entry)
{
  if (isset($entry['cpqDaCntlrBoardStatus']))
  {
    $hardware   = rewrite_cpqida_hardware($entry['cpqDaCntlrModel']);
    $descr      = $hardware.' ('.$entry['cpqDaCntlrHwLocation'].')';

    $oid        = ".1.3.6.1.4.1.232.3.2.2.1.1.10.".$index;
    $status     = $entry['cpqDaCntlrBoardStatus'];

    discover_status($device, $oid, 'cpqDaCntlrBoardStatus'.$index, 'cpqDaCntlrBoardStatus', $descr . ' Board Status', $status, array('entPhysicalClass' => 'controller'));

    $oid        = ".1.3.6.1.4.1.232.3.2.2.1.1.6.".$index;
    $status     = $entry['cpqDaCntlrCondition'];

    discover_status($device, $oid, 'cpqDaCntlrCondition'.$index, 'cpqDaCntlrCondition', $descr . ' Condition', $status, array('entPhysicalClass' => 'controller'));



    if ($entry['cpqDaCntlrCurrentTemp'] > 0)
    {
      $oid       = ".1.3.6.1.4.1.232.3.2.2.1.1.32.".$index;
      $value     = $entry['cpqDaCntlrCurrentTemp'];
      $descr     = $hardware.' ('.$entry['cpqDaCntlrHwLocation'].')';
      discover_sensor($valid['sensor'], 'temperature', $device, $oid, 'cpqDaCntlrEntry'.$index, 'cpqida-cntrl-temp', $descr, 1, $value);
    }
  }
}

// Physical Disks

$oids = snmpwalk_cache_oid($device, 'cpqDaPhyDrv', array(), 'CPQIDA-MIB');

foreach ($oids as $index => $entry)
{

  $name    = $entry['cpqDaPhyDrvLocationString'];
  if(!empty($entry['cpqDaPhyDrvModel'])) { $name .= ' ('.$entry['cpqDaPhyDrvModel'].')'; }
  if(!empty($entry['cpqDaPhyDrvSerialNum'])) { $name .= ' ('.$entry['cpqDaPhyDrvSerialNum'].')'; }

  if ($entry['cpqDaPhyDrvTemperatureThreshold'] > 0)
  {
    $descr      = $name; // "HDD ".$entry['cpqDaPhyDrvBay'];
    $oid        = ".1.3.6.1.4.1.232.3.2.5.1.1.70.".$index;
    $value      = $entry['cpqDaPhyDrvCurrentTemperature'];
    $limits     = array('limit_high' => $entry['cpqDaPhyDrvTemperatureThreshold']);

    discover_sensor($valid['sensor'], 'temperature', $device, $oid, 'cpqDaPhyDrv.'.$index, 'cpqida', $descr, 1, $value, $limits);
  }

  $oid     = '1.3.6.1.4.1.232.3.2.5.1.1.6.' . $index;
  $oidn    = 'cpqDaPhyDrvStatus.' . $index;
  $state  = $entry['cpqDaPhyDrvStatus'];

  discover_status($device, $oid, $oidn, 'cpqDaPhyDrvStatus', $name . ' Status', $state, array('entPhysicalClass' => 'physicalDrive'));

  $oid     = '1.3.6.1.4.1.232.3.2.5.1.1.37.' . $index;
  $oidn    = 'cpqDaPhyDrvCondition.' . $index;
  $state  = $entry['cpqDaPhyDrvCondition'];

  discover_status($device, $oid, $oidn, 'cpqDaPhyDrvCondition', $name . ' Condition', $state, array('entPhysicalClass' => 'physicalDrive'));

}

// Logical Disks

$oids = snmpwalk_cache_oid($device, 'cpqDaLogDrv', array(), 'CPQIDA-MIB');

foreach ($oids as $index => $entry)
{

  $name   = 'Controller '. $entry['cpqDaLogDrvCntlrIndex'] . ' Logical Drive ' . $entry['cpqDaLogDrvIndex'];
  if(!empty($entry['cpqDaLogDrvOsName'])) { $name .= ' ('.$entry['cpqDaLogDrvOsName'].')'; }

  $oid    = '.1.3.6.1.4.1.232.3.2.3.1.1.4.' . $index;
  $oidn    = 'cpqDaLogDrvStatus.' . $index;
  $state  = $entry['cpqDaLogDrvStatus'];

  discover_status($device, $oid, $oidn, 'cpqDaLogDrvStatus', $name . ' Status', $state, array('entPhysicalClass' => 'logicalDrive'));

  $oid    = '.1.3.6.1.4.1.232.3.2.3.1.1.11.' . $index;
  $oidn    = 'cpqDaLogDrvCondition.' . $index;
  $state  = $entry['cpqDaLogDrvCondition'];

  discover_status($device, $oid, $oidn, 'cpqDaLogDrvCondition', $name . ' Condition', $state, array('entPhysicalClass' => 'logicalDrive'));


}

// EOF
