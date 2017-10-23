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

$data = snmp_get_multi($device, 'extremeImageBooted.0 extremePrimarySoftwareRev.0 extremeSecondarySoftwareRev.0 sysObjectID.0 extremeImageSshCapability.cur extremeImageUAACapability.cur', '-OUQs', 'EXTREME-SYSTEM-MIB');

// hardware platform
$hardware = $data[0]['sysObjectID'];
$hardware = rewrite_extreme_hardware($hardware);

// determine running firmware version
switch ($data[0]['extremeImageBooted'])
{
  case 'primary':
    $version = $data[0]['extremePrimarySoftwareRev'];
    break;
  case 'secondary':
    $version = $data[0]['extremeSecondarySoftwareRev'];
    break;
  default:
    $version = 'UNKNOWN';
}

// features
$features = '';
if ($data['cur']['extremeImageSshCapability'] <> 'unknown' && trim($data['cur']['extremeImageSshCapability'] <> ''))
{
  $features .= ' ' . $data['cur']['extremeImageSshCapability'];
}

if ($data['cur']['extremeImageUAACapability'] <> 'unknown' && trim($data['cur']['extremeImageUAACapability'] <> ''))
{
  $features .= ' ' . $data['cur']['extremeImageUAACapability'];
}

// EOF
