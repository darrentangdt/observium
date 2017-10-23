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

//LCOS-MIB::lcsFirmwareVersionTableEntryIfc.eIfc = INTEGER: eIfc(1)
//LCOS-MIB::lcsFirmwareVersionTableEntryVersion.eIfc = STRING: 8.82.0100RU1 / 28.08.2013

$data = snmp_get_multi($device, 'lcsFirmwareVersionTableEntryVersion.eIfc', '-OQUs', 'LCOS-MIB');

list($version, $features) = explode(' / ', $data['eIfc']['lcsFirmwareVersionTableEntryVersion']);

// EOF
