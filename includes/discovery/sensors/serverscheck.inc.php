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

$oid_list = array('1' => '.1.3.6.1.4.1.17095.3.2.0',
                  '2' => '.1.3.6.1.4.1.17095.3.6.0',
                  '3' => '.1.3.6.1.4.1.17095.3.10.0',
                  '4' => '.1.3.6.1.4.1.17095.3.14.0',
                  '5' => '.1.3.6.1.4.1.17095.3.18.0');

foreach ($oid_list as $index => $oid)
{
  $value = snmp_get($device, $oid, '-Oqv');

  if (is_numeric($value))
  {

    $descr_oid = 'sensor'.$index.'name.0';
    $descr = snmp_get($device, $descr_oid, '-Oqv', 'ServersCheck');

    if ($descr != "-")
    {

      $descr = trim(str_replace("\"", "", $descr));

      unset($unit);
      $type = "temperature";

      if     (strpos($descr, "Temp")      !== FALSE) { $type = "temperature"; }
      elseif (strpos($descr, "Humidity")  !== FALSE) { $type = "humidity"; }
      elseif (strpos($descr, "Dew Point") !== FALSE) { $type = "dewpoint"; }
      elseif (strpos($descr, "Airflow")   !== FALSE) { $type = "airflow"; }
      elseif (strpos($descr, "Dust")      !== FALSE) { $type = "dust"; }
      elseif (strpos($descr, "Sound")     !== FALSE) { $type = "sound"; }

      // If the global setting is set telling us all of our serverscheck devices are F, set the unit as F.
      if ($type == "temperature" && $config['devices']['serverscheck']['temp_f'] == TRUE)
      {
        $options = array('sensor_unit' => 'F');
      }

      discover_sensor($valid['sensor'], $type, $device, $oid, $index, 'serverscheck_sensor', $descr, 1, $value, $options);
    }

  }

  unset($options);
  unset($unit);
  unset($type);
  unset($descr);
  unset($descr_oid);

}
