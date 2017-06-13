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

if (!$os && ($sysObjectId == '.1.3.6.1.4.1.890' || str_starts($sysObjectId, '.1.3.6.1.4.1.890.')))
{
  if      (str_contains($sysDescr, 'ZyWALL'))     { $os = "zywall"; }
  else if (preg_match("/^X?(ES|GS)/", $sysDescr)) { $os = "zyxeles"; }
  else if (str_starts($sysDescr, 'NWA-'))         { $os = "zyxelnwa"; }
  else if (str_starts($sysDescr, 'P'))            { $os = "prestige"; }
  else if (str_contains($sysDescr, 'IES'))        { $os = "ies"; }
  else if (!str_contains($sysDescr, 'Alcatel'))   { $os = "ies"; } // All other ZyXEL DSL, except Alcatel
}

// EOF
