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

$mempool['free']  = snmp_get($device, 'performanceDynMemFree.0',  '-OQUvs', 'AETHRA-MIB');
$mempool['total'] = snmp_get($device, 'performanceDynMemTotal.0', '-OQUvs', 'AETHRA-MIB');

// EOF
