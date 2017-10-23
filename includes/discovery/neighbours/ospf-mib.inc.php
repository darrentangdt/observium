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

## OSPF-MIB::ospfNbrIpAddr.172.22.203.98.0

if ($config['autodiscovery']['ospf'] != FALSE)
{

  $ips = snmpwalk_values($device, "ospfNbrIpAddr", array(), "OSPF-MIB");

  foreach ($ips as $ip)
  {
    discover_new_device($ip, 'ospf', 'OSPF', $device);
  }
} else {
}

// EOF

