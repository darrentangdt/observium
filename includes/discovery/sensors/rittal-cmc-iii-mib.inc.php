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

$cache['rittalcmc3'] = snmpwalk_cache_twopart_oid($device, "cmcIIIVarTable", array(),$mib);

foreach ($cache['rittalcmc3'] as $deviceIndex => $sensors)
{
  foreach ($sensors as $sensorIndex => $sensor)
  {
    $scale_s = $sensor['cmcIIIVarScale'];
    switch($scale_s[0])
    {
      case '-':
        $scale = 1/(int)substr($scale_s,1);
        break;
      case '+':
        $scale = (int)substr($scale_s,1);
        break;
      default:
        $scale = 1;
    }

    $unit = $sensor['cmcIIIVarUnit'];
    $type = $sensor['cmcIIIVarType'];
    $name = $sensor['cmcIIIVarName'];
    $value = $sensor['cmcIIIVarValueInt'];
    $oid = ".1.3.6.1.4.1.2606.7.4.2.2.1.11.".$deviceIndex.".".$sensorIndex;

    $t = null;
    switch($type)
    {
      case 'status':
      case 'statusEnum':
        $t = "status";
        break;
      case 'outputPWM':
        $t = "power";
        break;
      case 'rotation':
        $t = "fanspeed";
        break;
      case 'value':
        if (in_array($unit, array('.C','degree C')))
        {
          $t = "temperature";
        } elseif ($unit == "V") {
          $t = "voltage";
        } elseif ($unit == "%") {
          if (stristr($name,"RPM"))
          {
            $t = "load";
          }
        } elseif ($unit == "l/min") {
          $t = "waterflow";
        } elseif ($unit == "W") {
          $t = "power";
        } elseif ($unit == "A") {
          $t = "current";
        }
        break;
      default:
        continue;
    }

    if ($t)
    {
      if ($t == "status")
      {
        discover_status($device, $oid, $index, "rittal-cmc-iii-state", $name, $value, array('entPhysicalClass' => 'other'));
      } else {
        $limits = array();
        discover_sensor($valid['sensor'], $t, $device, $oid, "cmcIIIVarTable.$deviceIndex.$sensorIndex", "Rittal-CMC-III", $name, $scale, $value, $limits);
      }
    }
  }
}

// EOF
