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

// FIXME this mib is missing not only from definitions but actually from mibs/ !
$mempool['used']  = snmp_get($device, "nsResMemAllocate.0", "-OvQ", 'NETSCREEN-RESOURCES-MIB');
$mempool['free']  = snmp_get($device, "nsResMemLeft.0",     "-OvQ", 'NETSCREEN-RESOURCES-MIB');
$mempool['total'] = $mempool['used'] + $mempool['free'];

// EOF
