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
  if (str_starts($sysDescr, 'SunOS'))
  {
    $os = "solaris";
    list(,, $version) = explode (' ', $sysDescr);
    if (version_compare($version, '5.10', '>'))
    {
      $os = "opensolaris";
      if (str_contains($sysDescr, 'oi_')) { $os = "openindiana"; }
    }
  }

  if (str_contains($sysDescr, 'Nexenta')) { $os = "nexenta"; }
}

// EOF
