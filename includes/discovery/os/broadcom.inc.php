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

if (!$os && strpos($sysObjectId, '.1.3.6.1.4.1.4413') === 0)
{
  $os = 'broadcom_fastpath'; // Generic Broadcom
  if (preg_match('/^USW-/', $sysDescr))
  {
    //USW-48P-750, 3.3.1.3458, Linux 3.6.5
    //USW-48P-500, 3.3.5.3734, Linux 3.6.5
    $os = 'unifi-switch';
  }
  else if (preg_match('/^LB\d/', $sysDescr))
  {
    //LB9, Runtime Code 1.4.12.00, Linux 2.6.35, ONIE
    //LB4M 48x1G 2x10G, 1.1.0.8, VxWorks 6.6
    $os = 'quanta-switch';
  }
  else if ($sysObjectId == '.1.3.6.1.4.1.4413.2.10' && preg_match('/bcm963/i', $sysDescr))
  {
    //Bcm963xx Software Version 3.10L.02.
    //Broadcom Bcm963xx Software Version 3-12-01-0G00
    //Broadcom Bcm963xx Software Version RU_DSL-2500U_3-06-04-0Z00
    $os = 'dlink-dsl';
  }
  else if ($sysObjectId == '.1.3.6.1.4.1.4413' && preg_match('/bcm963/i', $sysDescr))
  {
    //Broadcom Bcm963xx Software Version 3.00L.01V.
    //Broadcom Bcm963xx Software Version A131-306CTU-C08_R04
    //$os = 'comtrend-';
  } else {
    $data = snmpget_cache_multi($device, 'agentInventoryMachineType.0 agentInventoryMachineModel.0', array(), 'FASTPATH-SWITCHING-MIB');
    if (is_array($data[0]))
    {
      $data = $data[0];
      $agent_os = array('quanta-switch' => '/^(Quanta )?L[A-Z]\d/i',
                        'unifi-switch'  => '/^USW-/',
                        'edgemax'       => '/^Edge(Point|Switch)/');
      foreach ($agent_os as $cos => $pattern)
      {
        if (preg_match($pattern, $data['agentInventoryMachineType']) ||
            preg_match($pattern, $data['agentInventoryMachineModel']))
        {
          $os = $cos;
          break;
        }
      }
    }
  }

}

// EOF
