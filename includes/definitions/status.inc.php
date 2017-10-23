<?php

/**
* Observium
*
*   This file is part of Observium.
*
* @package    observium
* @subpackage definitions
* @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
*
*/

// NOTE. Here only Unix-agent and IPMI specific state definitions!
// All other (MIB based) place in mibs.inc.php

// UNIX AGENT specific states

$config['agent']['states']['unix-agent-state'][0] = array('name' => 'fail',     'event' => 'alert');
$config['agent']['states']['unix-agent-state'][1] = array('name' => 'ok',       'event' => 'ok');
$config['agent']['states']['unix-agent-state'][2] = array('name' => 'warn',     'event' => 'warning');

// Hrm, currently not used
//$config['ipmi']['states']['ipmi-<change>-state'][0] = array('name' => 'fail',     'event' => 'alert');
//$config['ipmi']['states']['ipmi-<change>-state'][1] = array('name' => 'ok',       'event' => 'ok');
//$config['ipmi']['states']['ipmi-<change>-state'][2] = array('name' => 'warn',     'event' => 'warning');

// Old $config['status_states'] now complete ignored

// EOF
