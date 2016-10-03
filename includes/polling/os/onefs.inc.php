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

// hardware, serial
#ISILON-MIB::chassisSerialNumber.1 = STRING: SX200-251510-0104
#ISILON-MIB::chassisModel.1 = STRING: X200-2U-Single-48GB-2x1GE-2x10GE SFP+-36TB

#ISILON-MIB::chassisSerialNumber.1 = STRING: JAMER153800294
#ISILON-MIB::chassisModel.1 = STRING: X210-2U-Single-48GB-2x1GE-2x10GE SFP+-22TB-800GB SSD
$data = snmpget_cache_multi($device, 'chassisModel.1 chassisSerialNumber.1', array(), 'ISILON-MIB');
if (is_array($data[1]))
{
  list($hardware, $features) = explode('-', $data[1]['chassisModel'], 2);
  $serial = $data[1]['chassisSerialNumber'];
}


// version
#Isilon OneFS isilon-1 v5.5.7.9 Isilon OneFS v5.5.7.9 B_5_5_7_9(RELEASE) i386
#Isilon OneFS LV-PROD-X400-2 v6.5.5.4 Isilon OneFS v6.5.5.4 B_6_5_5_55(RELEASE) amd64
#Isilon OneFS sipb-isilon-4 v7.0.1.8 Isilon OneFS v7.0.1.8 B_7_0_1_208(RELEASE) amd64
#Isilon OneFS isi-power-4 v7.1.1.2 Isilon OneFS v7.1.1.2 B_7_1_1_123(RELEASE) amd64
#ice-3 189406574 Isilon OneFS v8.0.0.1
if (preg_match('/Isilon OneFS v(?<version>[\d\.\-]+)/', $poll_device['sysDescr'], $matches))
{
  $version = $matches['version'];
}

// EOF
