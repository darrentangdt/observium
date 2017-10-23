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

$hardware = snmp_get($device, 'productHardware.0', '-Ovq', 'GEIST-MIB-V3') . ' ' . snmp_get($device, 'productTitle.0', '-Ovq', 'GEIST-MIB-V3');

$hardware = str_replace('GEIST','Geist',$hardware);

// EOF
