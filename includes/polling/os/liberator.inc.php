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

#SUB10SYSTEMS-MIB::sub10UnitLclUnitType.0 = INTEGER: v1000ROWB(9)
#SUB10SYSTEMS-MIB::sub10UnitLclDescription.0 = STRING: Sub10 Systems - Wireless Ethernet Bridge Liberator V1000
#SUB10SYSTEMS-MIB::sub10UnitLclHWSerialNumber.0 = STRING: "S1000653B201504504"
#SUB10SYSTEMS-MIB::sub10UnitLclTerminalType.0 = INTEGER: terminalB(1)
#SUB10SYSTEMS-MIB::sub10UnitLclFirmwareVersion.0 = STRING: "02.01.03.16"
$data = snmpget_cache_multi($device, 'sub10UnitLclUnitType.0 sub10UnitLclHWSerialNumber.0 sub10UnitLclTerminalType.0 sub10UnitLclFirmwareVersion.0', array(), 'SUB10SYSTEMS-MIB');
if (is_array($data[0]))
{
  $hardware = $data[0]['sub10UnitLclUnitType'];
  $features = $data[0]['sub10UnitLclTerminalType'];
  $serial   = $data[0]['sub10UnitLclHWSerialNumber'];
  $version  = $data[0]['sub10UnitLclFirmwareVersion'];
}

// EOF
