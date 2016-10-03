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

if (preg_match("/AP/", $version, $regexp_result))
{
  $wificlients1 = snmp_get($device, "regCount.0", "-Ovq", "WHISP-APS-MIB");
}

// EOF
