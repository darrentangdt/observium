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

$version  = snmp_get($device, "upsIdentUPSSoftwareVersion.0", "-OQv", "GEPARALLELUPS-MIB");

$hardware = snmp_get($device, "upsIdentManufacturer.0", "-OQv", "GEPARALLELUPS-MIB");
$hardware .= ' ' . snmp_get($device, "upsIdentModel.0", "-OQv", "GEPARALLELUPS-MIB");

$serial   = snmp_get($device, "upsIdentUPSSerialNumber.0", "-OQv", "GEPARALLELUPS-MIB");

// EOF
