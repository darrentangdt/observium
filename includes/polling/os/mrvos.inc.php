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

// DEV-ID-MIB::nbDevIdTypeName.0 = STRING: OptiSwitch 904
// DEV-ID-MIB::nbDevIdSysName.0 = STRING: OS904
// DEV-ID-MIB::nbDevIdHardwareSerialBoard.0 = STRING: 1517100668
// DEV-ID-MIB::nbDevIdHardwareSerialUnit.0 = STRING: 1525100300
// DEV-ID-MIB::nbDevIdSoftwareMasterOSVers.0 = STRING: 2_1_7L

$data = snmpget_cache_multi($device, 'nbDevIdTypeName.0 nbDevIdHardwareSerialBoard.0 nbDevIdSoftwareMasterOSVers.0', array(), 'DEV-ID-MIB');

if (is_array($data[0]))
{
  $hardware = $data[0]['nbDevIdTypeName'];
  $version  = $data[0]['nbDevIdSoftwareMasterOSVers'];
  $version  = str_replace('_', '.', $version);
  $serial   = $data[0]['nbDevIdHardwareSerialBoard'];
}
// sysDescr: OptiSwitch 904 Ver. 2_1_7L

//EOF
