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
  //FIXME. Make it generic for printers group
  $prtGeneralServicePerson = snmp_get($device, 'prtGeneralServicePerson.1', '-OQv', 'Printer-MIB');
  if (str_icontains($prtGeneralServicePerson, 'Samsung')) { $os = 'samsung-printer'; }
}

// EOF
