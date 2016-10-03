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

$version = str_replace('Blue Coat Management Center release ', '', $poll_device['sysDescr']);

$serial  = snmp_get($device, 'sgProxySerialNumber.0', '-OQv', 'BLUECOAT-SG-PROXY-MIB'); // FIXME. I think this copy-paste from proxysg

// EOF
