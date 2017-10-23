<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage graphs
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

if (is_numeric($vars['id']))
{
  $virt = dbFetchRow("SELECT * FROM `lb_virtuals` AS I, `devices` AS D WHERE I.virt_id = ? AND I.device_id = D.device_id", array($vars['id']));

  if (is_numeric($virt['device_id']) && ($auth || device_permitted($virt['device_id'])))
  {
    $device = device_by_id_cache($virt['device_id']);

    $rrd_filename = get_rrd_path($device, "lb-virtual-" . $virt['virt_name']);

    $title_array   = array();
    $title_array[] = array('text' => $device['hostname'], 'url' => generate_url(array('page' => 'device', 'device' => $device['device_id'])));
    $title_array[] = array('text' => 'F5 Virtuals', 'url' => generate_url(array('page' => 'device', 'device' => $device['device_id'], 'tab' => 'loadbalancer', 'type' => 'lb_virtuals')));
    $title_array[] = array('text' => $virt['virt_name']   , 'url' => generate_url(array('page' => 'device', 'device' => $device['device_id'], 'tab' => 'loadbalancer', 'type' => 'lb_virtuals', 'virt' => $virt['virt_id'])));

    $auth = TRUE;
  }
}

// EOF
