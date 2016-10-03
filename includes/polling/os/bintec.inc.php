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

// BIANCA-BRICK-MIB::biboABrdType.0.0.0 = STRING: Livebox 100 (4/16 MB)
// BIANCA-BRICK-MIB::biboABrdHWRelease.0.0.0 = STRING: 2.0
// BIANCA-BRICK-MIB::biboABrdFWRelease.0.0.0 = STRING: 1.1
// BIANCA-BRICK-MIB::biboABrdPartNo.0.0.0 = STRING: Business Livebox 100
// BIANCA-BRICK-MIB::biboABrdSerialNo.0.0.0 = STRING: SX3200208233880

// BIANCA-BRICK-MIB::biboAdmSWVersion.0 = STRING: V.7.5 Rev. 7 (Patch 6) IPSec from 2010/02/11 00:00:00
// BIANCA-BRICK-MIB::biboAdmSystemId.0 = STRING: SX3200208233880
// BIANCA-BRICK-MIB::biboAdmLocalPPPIdent.0 = STRING: x2301w

$data = snmpget_cache_multi($device, 'biboABrdPartNo.0.0.0 biboAdmLocalPPPIdent.0 biboAdmSWVersion.0 biboAdmSystemId.0', array(), 'BIANCA-BRICK-MIB');
$hardware   = $data[0]['biboAdmLocalPPPIdent'];
$features   = $data['0.0.0']['biboABrdPartNo'];
$serial     = $data[0]['biboAdmSystemId'];
$version    = $data[0]['biboAdmSWVersion'];
if (preg_match('/^(V\.)?(?<version>\d+[\.\d]+( Rev\. \d+)?( \(Patch \d+\))?)/', $version, $matches))
{
  $version = $matches['version'];
}

// EOF