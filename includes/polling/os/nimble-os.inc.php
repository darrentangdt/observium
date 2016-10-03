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

if (preg_match('/version (?<version>[\d\.]+)/', $poll_device['sysDescr'], $matches))
{
  //Nimble Storage XXX-XX running software version 3.4.1.0-382414-opt
  $version = $matches['version'];
}

// EOF
