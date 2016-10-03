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

// EMD-MIB::firmwareVersion.0 = STRING: 3.2.30.5-43188
// EMD-MIB::model.0 = STRING: EMX2-888

$data = snmpget_cache_multi($device, 'model.0 firmwareVersion.0', array(), 'EMD-MIB');

if (is_array($data[0]))
{
  $hardware = $data[0]['model'];
  $version  = $data[0]['firmwareVersion'];
  //$serial   = $data[0][''];
}

//EOF
