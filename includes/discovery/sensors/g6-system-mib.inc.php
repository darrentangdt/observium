<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage discovery
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

$value = snmp_get($device, 'systemTemperature.0', '-Ovq', 'G6-SYSTEM-MIB');

if (is_numeric($value))
{
  discover_sensor($valid['sensor'], 'temperature', $device, '.1.3.6.1.4.1.3181.10.6.1.30.104.0', '0', 'microsens', 'wireway', 1, $value);
}

unset($value);

// EOF
