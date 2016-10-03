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

$hardware = 'ALB-X'; // Currently know only one device

// version
#JETNEXUS-MIB::jetnexusVersionInfo.0 = STRING: "4.1.2 (Build 1644) "
$data = snmpget_cache_multi($device, 'jetnexusVersionInfo.0', array(), 'JETNEXUS-MIB');
if (is_array($data[0]))
{
  $version = $data[0]['jetnexusVersionInfo'];
}

// EOF

