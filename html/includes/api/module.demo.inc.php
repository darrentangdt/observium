<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    Simple Observium API
 * @subpackage Demo module
 * @author     Dennis de Houx <dennis@aio.be>
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */


/**
 * Show the module data
 *
 * @return array
 *
*/
function api_module_data() {
  $res = api_errorcodes("102","info");
  $res['value'] = "This is only a demo module witch doesn't return any live data.";
  return $res;
}

?>
