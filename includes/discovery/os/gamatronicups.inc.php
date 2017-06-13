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

if (!$os)
{
  if ($sysDescr == "" && str_contains(snmp_get($device, "psUnitManufacture.0", "-Oqv", "GAMATRONIC-MIB"), 'Gamatronic'))
  {
    $os = "gamatronicups";
  }
}

// EOF
