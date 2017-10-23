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

$version = snmp_get($device, 'ceAssetSoftwareRevision.1', '-OQv', 'CISCO-ENTITY-ASSET-MIB');
$hardware = snmp_get($device, 'entPhysicalDescr.1', '-OQv', 'ENTITY-MIB');

// EOF
