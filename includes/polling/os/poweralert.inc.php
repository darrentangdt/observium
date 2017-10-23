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

// .1.3.6.1.2.1.33.1.1.2.0 = STRING: "TRIPP LITE PDUMH20HVATNET"
// .1.3.6.1.2.1.33.1.1.4.0 = STRING: "12.04.0052"
// .1.3.6.1.2.1.33.1.1.5.0 = STRING: "sysname.company.com"
// .1.3.6.1.4.1.850.10.2.2.1.12.1 = STRING: "This Is My Location"

$data = snmp_get_multi_oid($device, 'upsIdentModel.0', array(), 'UPS-MIB');
if (is_array($data[0]))
{
  $hardware = trim(str_replace('TRIPP LITE', '', $data[0]['upsIdentModel']));
} else {
  //$hardware = $poll_device['sysDescr'];
  $hw = snmp_get($device, '.1.3.6.1.4.1.850.10.2.2.1.9.1', '-Ovq', 'TRIPPLITE-12X');
  if ($hw)
  {
    $hardware = trim(str_replace('TRIPP LITE', '', $hw));
  }
  $version  = snmp_get($device, '.1.3.6.1.4.1.850.10.1.2.3.0', '-Ovq', 'TRIPPLITE-12X');
}

// EOF
