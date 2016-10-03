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

$data = snmpget_cache_multi($device, 'rlPhdUnitGenParamSoftwareVersion.1 rlPhdUnitGenParamSerialNum.1 rlPhdUnitGenParamModelName.1', array(), 'RADLAN-Physicaldescription-MIB');
if (is_array($data[1]))
{
  /*
RADLAN-Physicaldescription-MIB::rlPhdNumberOfUnits.0 = INTEGER: 1
RADLAN-Physicaldescription-MIB::rlPhdUnitGenParamStackUnit.1 = INTEGER: 1
RADLAN-Physicaldescription-MIB::rlPhdUnitGenParamSoftwareVersion.1 = STRING: 1.1.40
RADLAN-Physicaldescription-MIB::rlPhdUnitGenParamFirmwareVersion.1 = STRING: 0.0.0.3
RADLAN-Physicaldescription-MIB::rlPhdUnitGenParamHardwareVersion.1 = STRING: 01.03
RADLAN-Physicaldescription-MIB::rlPhdUnitGenParamSerialNum.1 = STRING: ES2B000166
RADLAN-Physicaldescription-MIB::rlPhdUnitGenParamAssetTag.1 = STRING:
RADLAN-Physicaldescription-MIB::rlPhdUnitGenParamServiceTag.1 = STRING: 24 + 4 combo ports
RADLAN-Physicaldescription-MIB::rlPhdUnitGenParamSoftwareDate.1 = STRING:  20-Jul-2015
RADLAN-Physicaldescription-MIB::rlPhdUnitGenParamFirmwareDate.1 = STRING:  23-Feb-2011
RADLAN-Physicaldescription-MIB::rlPhdUnitGenParamManufacturer.1 = STRING:
RADLAN-Physicaldescription-MIB::rlPhdUnitGenParamModelName.1 = STRING: MES1124MB
   */
  $hardware = $data[1]['rlPhdUnitGenParamModelName'];
  $version  = $data[1]['rlPhdUnitGenParamSoftwareVersion'];
  $serial   = $data[1]['rlPhdUnitGenParamSerialNum'];
} else {
  /*
RADLAN-DEVICEPARAMS-MIB::rndBrgVersion.0 = STRING: 1.1.40
RADLAN-DEVICEPARAMS-MIB::rndBrgFeatures.0 = Hex-STRING: 10 02 90 02 01 00 00 04 00 60 00 00 00 00 00 00
00 00 00 00
RADLAN-DEVICEPARAMS-MIB::rndIpHostManagementSupported.0 = INTEGER: false(2)
RADLAN-DEVICEPARAMS-MIB::rndManagedTime.0 = STRING: 081002
RADLAN-DEVICEPARAMS-MIB::rndManagedDate.0 = STRING: 230916
RADLAN-DEVICEPARAMS-MIB::rndBaseBootVersion.0 = STRING: 0.0.0.3
RADLAN-DEVICEPARAMS-MIB::genGroupHWVersion.0 = STRING: 01.03
RADLAN-DEVICEPARAMS-MIB::rndBasePhysicalAddress.0 = STRING: a8:f9:4b:7c:b:40
RADLAN-DEVICEPARAMS-MIB::rndUnitNumber.1 = INTEGER: 1
RADLAN-DEVICEPARAMS-MIB::rndActiveSoftwareFile.1 = INTEGER: image1(1)
RADLAN-DEVICEPARAMS-MIB::rndActiveSoftwareFileAfterReset.1 = INTEGER: image1(1)
RADLAN-DEVICEPARAMS-MIB::rlResetStatus.0 = BITS: 00
RADLAN-DEVICEPARAMS-MIB::rndBackupConfigurationEnabled.0 = INTEGER: false(2)
RADLAN-DEVICEPARAMS-MIB::rndStackUnitNumber.1 = INTEGER: 1
RADLAN-DEVICEPARAMS-MIB::rndImage1Name.1 = STRING: image-1
RADLAN-DEVICEPARAMS-MIB::rndImage2Name.1 = STRING: image-2
RADLAN-DEVICEPARAMS-MIB::rndImage1Version.1 = STRING: 1.1.40
RADLAN-DEVICEPARAMS-MIB::rndImage2Version.1 = STRING: 1.1.40
RADLAN-DEVICEPARAMS-MIB::rndImage1Date.1 = STRING:  20-Jul-2015
RADLAN-DEVICEPARAMS-MIB::rndImage2Date.1 = STRING:  20-Jul-2015
RADLAN-DEVICEPARAMS-MIB::rndImage1Time.1 = STRING:  14:44:31
RADLAN-DEVICEPARAMS-MIB::rndImage2Time.1 = STRING:  14:44:31
   */
  $hardware = trim(str_replace('ATI', '', $poll_device['sysDescr']));
  $version  = snmp_get($device, 'rndBrgVersion.0', '-Ovq', 'RADLAN-DEVICEPARAMS-MIB');
}

// EOF
