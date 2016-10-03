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
  if (strpos($sysObjectId, '.1.3.6.1.4.1.1916.2') === 0)
  {
    if (strstr($sysDescr, 'XOS'))
    {
      // ExtremeWare XOS version 11.5.2.10 v1152b10 by release-manager on Thu Oct 26 09:53:04 PDT 2006
      // ExtremeXOS version 12.5.4.5 v1254b5-patch1-20 by release-manager on Tue Apr 24 16:16:37 EDT 2012
      // ExtremeXOS (X670G2-48x-4q) version 15.7.1.4 v1571b4-patch1-2 by release-manager on Fri May 1 15:16:42 EDT 2015
      // ExtremeXOS (X480-24x(SS128)) version 16.1.2.14 16.1.2.14 by release-manager on Tue Oct 6 19:03:00 EDT 2015
      $os = "xos";
    }
    else if (strstr($sysDescr, 'Wireless Controller'))
    {
      // WM3600 Wireless Controller, Version 4.2.1.3-001R MIB=01a
      // WM3400 Wireless Controller, Version 5.5.4.0-018R MIB=01a
      $os = "extreme-wlc";
    } else {
      // ENET SWITCH 24 PORT
      // sambong_pc - Version 4.1.19 (Build 2) by Release_Master Wed 08/09/2000 6:09p
      // Summit200-24 - Version 6.2e.2 (Build 16) by Release_Master_ABU Thu 06/26/2003 16:33:54
      // Alpine3804 - Version 7.8.3 (Build 5) by Release_Master 03/15/10 14:21:36
      // BD6808 - Version 7.8.4 (Build 1) by Patch_Master 02/17/12 03:51:25
      $os = "extremeware";
    }
  }
}

// EOF
