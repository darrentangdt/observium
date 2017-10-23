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

// GBNPlatformOAM-MIB::softwareVersion.0 = STRING: EL5600-04P V100R001B01D001P001SP13
$version  = snmp_get($device, 'softwareVersion.0', '-Osqv', 'GBNPlatformOAM-MIB');
preg_match('/(V.*)/', $version, $matches);
if ($matches[1]) { $version = $matches[1]; }

// EOF
