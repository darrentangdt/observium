<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage rrdtool
 * @author     Adam Armstrong <adama@observium.org>
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

/**
 * Get full path for rrd file.
 *
 * @param array $device Device arrary
 * @param string $filename Base filename for rrd file
 * @return string Full rrd file path
 */
// TESTME needs unit testing
function get_rrd_path($device, $filename)
{
  global $config;

  $filename = safename(trim($filename));

  // If filename empty, return base rrd dirname for device (for example in delete_device())
  $rrd_file = trim($config['rrd_dir']) . '/';
  if (strlen($device['hostname']))
  {
    $rrd_file .= $device['hostname'] . '/';
  }

  if (strlen($filename) > 0)
  {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if ($ext != 'rrd') { $filename .= '.rrd'; } // Add rrd extension if not already set
    $rrd_file .= safename($filename);

    // Add rrd filename to global array $graph_return
    $GLOBALS['graph_return']['rrds'][] = $rrd_file;
  }

  return $rrd_file;
}

/**
 * Rename rrd file for device is some schema changes.
 *
 * @param array $device
 * @param string $old_rrd Base filename for old rrd file
 * @param string $new_rrd Base filename for new rrd file
 * @param boolean $overwrite Force overwrite new rrd file if already exist
 * @return bool TRUE if renamed
 */
function rename_rrd($device, $old_rrd, $new_rrd, $overwrite = FALSE)
{
  $old_rrd = get_rrd_path($device, $old_rrd);
  $new_rrd = get_rrd_path($device, $new_rrd);
  if (is_file($old_rrd))
  {
    if (!$overwrite && is_file($new_rrd))
    {
      // If not forced overwrite file, return false
      $renamed = FALSE;
    } else {
      $renamed = rename($old_rrd, $new_rrd);
    }
  } else {
    $renamed = FALSE;
  }
  if ($renamed)
  {
    print_debug("RRD moved: '$old_rrd' -> '$new_rrd'");
  }

  return $renamed;
}

/**
 * Rename rrd file for device (same as in rename_rrd()),
 * but rrd filename detected by common entity params
 *
 * @param array $device
 * @param string $entity Entity type (sensor, status, etc..)
 * @param array $old Old entity params, based on discovery entity
 * @param array $new New entity params, based on discovery entity
 * @param boolean $overwrite Force overwrite new rrd file if already exist
 * @return bool TRUE if renamed
 */
function rename_rrd_entity($device, $entity, $old, $new, $overwrite = FALSE)
{
  switch ($entity)
  {
    case 'sensor':
      $old_sensor = array('poller_type'  => $old['poller_type'],
                          'sensor_descr' => $old['descr'],
                          'sensor_class' => $old['class'],
                          'sensor_type'  => $old['type'],
                          'sensor_index' => $old['index']);
      $new_sensor = array('poller_type'  => $new['poller_type'],
                          'sensor_descr' => $new['descr'],
                          'sensor_class' => $new['class'],
                          'sensor_type'  => $new['type'],
                          'sensor_index' => $new['index']);

      $old_rrd = get_sensor_rrd($device, $old_sensor);
      $new_rrd = get_sensor_rrd($device, $new_sensor);
      break;
    case 'status':
      $old_status = array('poller_type'  => $old['poller_type'],
                          'status_descr' => $old['descr'],
                          'status_type'  => $old['type'],
                          'status_index' => $old['index']);
      $new_status = array('poller_type'  => $new['poller_type'],
                          'status_descr' => $new['descr'],
                          'status_type'  => $new['type'],
                          'status_index' => $new['index']);

      $old_rrd = get_status_rrd($device, $old_status);
      $new_rrd = get_status_rrd($device, $new_status);
      break;
    default:
      print_debug("skipped unknown entity for rename rrd");
      return FALSE;
  }

  return rename_rrd($device, $old_rrd, $new_rrd, $overwrite);
}

/**
 * Opens up a pipe to RRDTool using handles provided
 *
 * @return boolean
 * @global array $config
 * @param &rrd_process
 * @param &rrd_pipes
 */
// TESTME needs unit testing
function rrdtool_pipe_open(&$rrd_process, &$rrd_pipes)
{
  global $config;

  $command = $config['rrdtool'] . ' -'; // Waits for input via standard input (STDIN)

  $descriptorspec = array(
     0 => array('pipe', 'r'),  // stdin
     1 => array('pipe', 'w'),  // stdout
     2 => array('pipe', 'w')   // stderr
  );

  $cwd = $config['rrd_dir'];
  $env = array();

  $rrd_process = proc_open($command, $descriptorspec, $rrd_pipes, $cwd, $env);

  stream_set_blocking($rrd_pipes[1], 0);
  stream_set_blocking($rrd_pipes[2], 0);

  if (is_resource($rrd_process))
  {
    // $pipes now looks like this:
    // 0 => writeable handle connected to child stdin
    // 1 => readable handle connected to child stdout
    // 2 => readable handle connected to child stderr
    if (OBS_DEBUG > 1)
    {
      print_message('RRD PIPE OPEN[%gTRUE%n]', 'console');
    }

    return TRUE;
  } else {
    if (isset($config['rrd']['debug']) && $config['rrd']['debug'])
    {
      logfile('rrd.log', "RRD pipe process not opened '$command'.");
    }
    if (OBS_DEBUG > 1)
    {
      print_message('RRD PIPE OPEN[%rFALSE%n]', 'console');
    }
    return FALSE;
  }
}

/**
 * Closes the pipe to RRDTool
 *
 * @return integer
 * @param resource rrd_process
 * @param array rrd_pipes
 */
// TESTME needs unit testing
function rrdtool_pipe_close($rrd_process, &$rrd_pipes)
{
  if (OBS_DEBUG > 1)
  {
    $rrd_status['stdout'] = stream_get_contents($rrd_pipes[1]);
    $rrd_status['stderr'] = stream_get_contents($rrd_pipes[2]);
  }

  if (is_resource($rrd_pipes[0]))
  {
    fclose($rrd_pipes[0]);
  }
  fclose($rrd_pipes[1]);
  fclose($rrd_pipes[2]);

  // It is important that you close any pipes before calling
  // proc_close in order to avoid a deadlock

  $rrd_status['exitcode'] = proc_close($rrd_process);
  if (OBS_DEBUG > 1)
  {
    print_message('RRD PIPE CLOSE['.($rrd_status['exitcode'] !== 0 ? '%rFALSE' : '%gTRUE').'%n]', 'console');
    if ($rrd_status['stdout'])
    {
      print_message("RRD PIPE STDOUT[\n".$rrd_status['stdout']."\n]", 'console', FALSE);
    }
    if ($rrd_status['exitcode'] && $rrd_status['stderr'])
    {
      // Show stderr if exitcode not 0
      print_message("RRD PIPE STDERR[\n".$rrd_status['stderr']."\n]", 'console', FALSE);
    }
  }

  return $rrd_status['exitcode'];
}

/**
 * Generates a graph file at $graph_file using $options
 * Opens its own rrdtool pipe.
 *
 * @return integer
 * @param string graph_file
 * @param string options
 */
// TESTME needs unit testing
function rrdtool_graph($graph_file, $options)
{
  global $config;

  // Note, always use pipes, because standard command line has limits!
  if ($config['rrdcached'])
  {
    $options = str_replace($config['rrd_dir'].'/', '', $options);
    $cmd = 'graph --daemon ' . $config['rrdcached'] . " $graph_file $options";
  } else {
    $cmd = "graph $graph_file $options";
  }
  $GLOBALS['rrd_status']  = FALSE;
  $GLOBALS['exec_status'] = array('command'  => $config['rrdtool'] . ' ' . $cmd,
                                  'stdout'   => '',
                                  'exitcode' => -1);

  $start = microtime(TRUE);
  rrdtool_pipe_open($rrd_process, $rrd_pipes);
  if (is_resource($rrd_process))
  {
    // $pipes now looks like this:
    // 0 => writeable handle connected to child stdin
    // 1 => readable handle connected to child stdout
    // Any error output will be appended to /tmp/error-output.txt

    fwrite($rrd_pipes[0], $cmd);
    fclose($rrd_pipes[0]);

    $iter = 0;
    while (strlen($line) < 1 && $iter < 1000)
    {
      // wait for 10 milliseconds to loosen loop
      usleep(10000);
      $line = fgets($rrd_pipes[1], 1024);
      $stdout .= $line;
      $iter++;
    }
    $stdout = preg_replace('/(?:\n|\r\n|\r)$/D', '', $stdout); // remove last (only) eol
    unset($iter);

    $runtime  = microtime(TRUE) - $start;

    // Check rrdtool's output for the command.
    if (preg_match('/\d+x\d+/', $stdout))
    {
      $GLOBALS['rrd_status'] = TRUE;
    } else {
      $stderr = trim(stream_get_contents($rrd_pipes[2]));
      if (isset($config['rrd']['debug']) && $config['rrd']['debug'])
      {
        logfile('rrd.log', "RRD $stderr, CMD: " . $GLOBALS['exec_status']['command']);
      }
    }
    $exitcode = rrdtool_pipe_close($rrd_process, $rrd_pipes);

    $GLOBALS['exec_status']['exitcode'] = $exitcode;
    $GLOBALS['exec_status']['stdout']   = $stdout;
    $GLOBALS['exec_status']['stderr']   = $stderr;
  } else {
    $runtime = microtime(TRUE) - $start;
    $stdout  = NULL;
  }
  $GLOBALS['exec_status']['runtime']  = $runtime;
  // Add some data to global array $graph_return
  $GLOBALS['graph_return']['status']   = $GLOBALS['rrd_status'];
  $GLOBALS['graph_return']['command']  = $GLOBALS['exec_status']['command'];
  $GLOBALS['graph_return']['filename'] = $graph_file;
  $GLOBALS['graph_return']['output']   = $stdout;
  $GLOBALS['graph_return']['runtime']  = $GLOBALS['exec_status']['runtime'];

  if (OBS_DEBUG)
  {
    print_message(PHP_EOL . 'RRD CMD[%y' . $cmd . '%n]', 'console', FALSE);
    $debug_msg  = 'RRD RUNTIME['.($runtime > 0.1 ? '%r' : '%g').round($runtime, 4).'s%n]' . PHP_EOL;
    $debug_msg .= 'RRD STDOUT['.($GLOBALS['rrd_status'] ? '%g': '%r').$stdout.'%n]' . PHP_EOL;
    if ($stderr)
    {
      $debug_msg .= 'RRD STDERR[%r'.$stderr.'%n]' . PHP_EOL;
    }
    $debug_msg .= 'RRD_STATUS['.($GLOBALS['rrd_status'] ? '%gTRUE': '%rFALSE').'%n]';

    print_message($debug_msg . PHP_EOL, 'console');
  }

  return $stdout;
}

/**
 * Generates and pipes a command to rrdtool
 *
 * @param string command
 * @param string filename
 * @param string options
 * @global config
 * @global debug
 * @global rrd_pipes
 */
// TESTME needs unit testing
function rrdtool($command, $filename, $options)
{
  global $config, $rrd_pipes;

  // We now require rrdcached 1.5.5
  if($config['rrdcached'] && ($config['rrd']['no_local'] == TRUE || $command != 'create'))
  {
    $filename = str_replace($config['rrd_dir'].'/', '', $filename);
  }

  $cmd = "$command $filename $options";
  if ($config['rrdcached'] && ($config['rrd']['no_local'] == TRUE || $command != 'create'))
  {
    $cmd .= ' --daemon ' . $config['rrdcached'];
  }

  $GLOBALS['rrd_status'] = FALSE;
  $GLOBALS['exec_status'] = array('command' => $config['rrdtool'] . ' ' . $cmd,
                                  'exitcode' => 1);

  if ($config['norrd'])
  {
    print_message("[%rRRD Disabled - $cmd%n]", 'color');
    return NULL;
  } else {
    // FIXME, need add check if pipes exist
    $start = microtime(TRUE);
    fwrite($rrd_pipes[0], $cmd."\n");
    usleep(1000);
  }

  $stdout = trim(stream_get_contents($rrd_pipes[1]));
  $stderr = trim(stream_get_contents($rrd_pipes[2]));
  $runtime = microtime(TRUE) - $start;

  // Check rrdtool's output for the command.
  if (strpos($stdout, 'ERROR') !== FALSE)
  {
    if (isset($config['rrd']['debug']) && $config['rrd']['debug'])
    {
      logfile('rrd.log', "RRD $stdout, CMD: $cmd");
    }
  } else {
    $GLOBALS['rrd_status'] = TRUE;
    $GLOBALS['exec_status']['exitcode'] = 0;
  }
  $GLOBALS['exec_status']['stdout']  = $stdout;
  $GLOBALS['exec_status']['stdin']   = $stdin;
  $GLOBALS['exec_status']['runtime'] = $runtime;

  $GLOBALS['rrdtool'][$command]['time'] += $runtime;
  $GLOBALS['rrdtool'][$command]['count']++;

  if (OBS_DEBUG)
  {
    print_message(PHP_EOL . 'RRD CMD[%y' . $cmd . '%n]', 'console', FALSE);
    $debug_msg  = 'RRD RUNTIME['.($runtime > 1 ? '%r' : '%g').round($runtime, 4).'s%n]' . PHP_EOL;
    $debug_msg .= 'RRD STDOUT['.($GLOBALS['rrd_status'] ? '%g': '%r').$stdout.'%n]' . PHP_EOL;
    if ($stderr)
    {
      $debug_msg .= 'RRD STDERR[%r'.$stderr.'%n]' . PHP_EOL;
    }
    $debug_msg .= 'RRD_STATUS['.($GLOBALS['rrd_status'] ? '%gTRUE': '%rFALSE').'%n]';

    print_message($debug_msg . PHP_EOL, 'console');
  }
}

/**
 * Generates an rrd database at $filename using $options
 * Creates the file if it does not exist yet.
 * DEPRECATED: use rrdtool_create_ng(), this will disappear and ng will be renamed when conversion is complete.
 *
 * @param array  device
 * @param string filename
 * @param string ds
 * @param string options
 */
function rrdtool_create($device, $filename, $ds, $options = '')
{
  global $config;

  if ($filename[0] == '/')
  {
    print_debug("You should pass the filename only (not the full path) to this function! Passed filename: ".$filename);
    $filename = basename($filename);
  }

  $fsfilename = get_rrd_path($device, $filename);

  if (rrd_exists($device, $filename))
  {
    if (OBS_DEBUG > 1)
    {
      print_message("RRD $fsfilename already exists - no need to create.");
    }
    return FALSE; // Bail out if the file exists already
  }

  if (!$options)
  {
    $options = preg_replace('/\s+/', ' ', $config['rrd']['rra']);
  }

  $step = "--step ".$config['rrd']['step'];

  if ($config['norrd'])
  {
    print_message("[%rRRD Disabled - create $fsfilename%n]", 'color');
    return NULL;
  } else {
    //$command = $config['rrdtool'] . " create $fsfilename $ds $step $options";
    //return external_exec($command);

    // Clean up old ds strings. This is kinda nasty.
    $ds = str_replace("\
", '', $ds);
    return rrdtool('create', $fsfilename, $ds . " $step $options");
  }
}

/**
 * Generates RRD filename from templated string.
 *
 * @param string filename       Original filename, using %index% (or %custom% %keys%) as placeholder for indexes
 * @param string/array index    Index, if RRD type is indexed (or array of multiple indexes)
 */ 
// TESTME needs unit testing
function rrdtool_generate_filename($filename, $index)
{
  // Generate warning for indexed filenames containing %index% - does not help if you use custom field names for indexing
  if (strstr($filename, '%index%') !== FALSE)
  {
    if ($index === NULL)
    {
      print_warning("RRD filename generation error: filename contains %index%, but \$index is NULL!");
    }
  }

  // Convert to single element array if not an array.
  // This will automatically use %index% as the field to replace (see below).
  if (!is_array($index)) { $index = array('index' => $index); }

  // Replace %index% by $index['index'], %foo% by $index['foo'] etc. 
  foreach ($index as $key => $value)
  {
    $filename = str_replace('%' . $key . '%', $value, $filename);
  }

  return $filename;
}

/**
 * Generates an rrd database based on $type definition, using $options
 * Only creates the file if it does not exist yet.
 * Should most likely not be called on its own, as an update call will check for existence.
 *
 * @param array        device   Device array
 * @param string/array type     rrd file type from $config['rrd_types'] or actual config array
 * @param string/array index    Index, if RRD type is indexed (or array of multiple indexes)
 * @param string       options  RRA options to pass (defaults to $config['rrd']['rra'])
 */
// TESTME needs unit testing
function rrdtool_create_ng($device, $type, $index = NULL, $options = NULL)
{
  global $config;

  if (!is_array($type)) // We were passed a string
  {
    if (!is_array($config['rrd_types'][$type])) // Check if definition exists
    {
      print_warning("Cannot create RRD for type $type - not found in definitions!");
      return FALSE;
    }

    $definition = $config['rrd_types'][$type];
  } else { // We were passed an array, use as-is
    $definition = $type;
  }

  $filename = rrdtool_generate_filename($definition['file'], $index);

  $fsfilename = get_rrd_path($device, $filename);

  if (rrd_exists($device, $filename))
  {
    print_debug("RRD $fsfilename already exists - no need to create.");
    return FALSE; // Bail out if the file exists already
  }

  if ($options === NULL)
  {
    $options = preg_replace('/\s+/', ' ', $config['rrd']['rra']);
  }

  $step = '--step ' . $config['rrd']['step'];

  // Create DS parameter based on the definition
  $ds = array();

  foreach ($definition['ds'] as $name => $def)
  {
    if (strlen($name) > 19) { print_warning("SEVERE: DS name $name is longer than 19 characters - over RRD limit!"); }

    // Set defaults for missing attributes
    if (!isset($def['type']))      { $def['type'] = 'COUNTER'; }
    if (!isset($def['max']))       { $def['max'] = 'U'; }
    if (!isset($def['min']))       { $def['min'] = 'U'; }
    if (!isset($def['heartbeat'])) { $def['heartbeat'] = 2 * $config['rrd']['step']; }

    // Create DS string to pass on the command line
    $ds[] = "DS:$name:" . $def['type'] . ':' . $def['heartbeat'] . ':' . $def['min'] . ':' . $def['max'];
  }

  if ($config['norrd'])
  {
    print_message("[%rRRD Disabled - create $fsfilename%n]", 'color');
    return NULL;
  } else {
    return rrdtool('create', $fsfilename, implode(' ', $ds) . " $step $options");
  }
}

/**
 * Checks if an RRD database at $filename for $device exists
 * Checks via rrdcached if configured, else via is_exists
 *
 * @param array  device
 * @param string filename
**/
function rrd_exists($device, $filename)
{

  global $config;

  $fsfilename = get_rrd_path($device, $filename);

  if ($config['rrdcached'] && ($config['rrd']['no_local'] == TRUE))
  {
    $last = rrdtool_last($fsfilename);
    return $GLOBALS['rrd_status'];
  } else {
    if(is_file($fsfilename))
    {
      return TRUE;
    } else {
      return FALSE;
    }
  }

}

/**
 * Updates an rrd database at $filename using $options
 * Where $options is an array, each entry which is not a number is replaced with "U"
 *
 * @param array        device  Device array
 * @param string/array type    RRD file type from $config['rrd_types'] or actual config array
 * @param array        ds      DS data (key/value)
 * @param string/array index   Index, if RRD type is indexed (or array of multiple indexes)
 * @param bool         create  Create RRD file if it does not exist
 * @param string       options Options to pass to create function if file does not exist
 */
// TESTME needs unit testing
function rrdtool_update_ng($device, $type, $ds, $index = NULL, $create = TRUE)
{
  global $config;

  if (!is_array($type)) // We were passed a string
  {
    if (!is_array($config['rrd_types'][$type])) // Check if definition exists
    {
      print_warning("Cannot create RRD for type $type - not found in definitions!");
      return FALSE;
    }

    $definition = $config['rrd_types'][$type];
  } else { // We were passed an array, use as-is
    $definition = $type;
  }

  $filename = rrdtool_generate_filename($definition['file'], $index);

  $fsfilename = get_rrd_path($device, $filename);

  // Create the file if missing (if we have permission to create it)
  if ($create)
  {
    rrdtool_create_ng($device, $type, $index, $options);
  }

  $update = array('N');

  foreach ($definition['ds'] as $name => $def)
  {
    if (isset($ds[$name]))
    {
      if (is_numeric($ds[$name]))
      {
        // Add data to DS update string
        $update[] = $ds[$name];
      } else {
        // Data not numeric, mark unknown
        $update[] = 'U';
      }
    } else {
      // Data not sent, mark unknown
      $update[] = 'U';
    }
  }

  return rrdtool('update', $fsfilename, implode(':', $update));
}

/**
 * Updates an rrd database at $filename using $options
 * Where $options is an array, each entry which is not a number is replaced with "U"
 * DEPRECATED: use rrdtool_update_ng(), this will disappear and ng will be renamed when conversion is complete.
 *
 * @param array  device
 * @param string filename
 * @param array  options
 */
function rrdtool_update($device, $filename, $options)
{
  // Do some sanitisation on the data if passed as an array.
  if (is_array($options))
  {
    $values[] = "N";
    foreach ($options as $value)
    {
      if (!is_numeric($value)) { $value = 'U'; }
      $values[] = $value;
    }
    $options = implode(':', $values);
  }

  if ($filename[0] == '/')
  {
    $filename = basename($filename);
    print_debug("You should pass the filename only (not the full path) to this function!");
  }

  $fsfilename = get_rrd_path($device, $filename);

  return rrdtool("update", $fsfilename, $options);
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function rrdtool_fetch($filename, $options)
{
  return rrdtool('fetch', $filename, $options);
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function rrdtool_last($filename, $options)
{
  return rrdtool('last', $filename, $options);
}

// DOCME needs phpdoc block
// TESTME needs unit testing
function rrdtool_lastupdate($filename, $options)
{
  return rrdtool('lastupdate', $filename, $options);
}

// TESTME needs unit testing
/**
 * Renames a DS inside an RRD file
 *
 * @param array Device
 * @param string Filename
 * @param string Current DS name
 * @param string New DS name
 */
function rrdtool_rename_ds($device, $filename, $oldname, $newname)
{
  global $config;

  $return = FALSE;
  if ($config['norrd'])
  {
    print_message('[%gRRD Disabled%n] ');
  } else {
    $fsfilename = get_rrd_path($device, $filename);
    if (is_file($fsfilename))
    {
      // this function used in discovery, where not exist rrd pipes
      // FIXME shouldn't we fix that then? either pipes in discovery, or make the generic function aware of the lack of pipes?
      //rrdtool("tune", $fsfilename, "--data-source-rename $oldname:$newname");
      $command = $config['rrdtool'] . " tune $fsfilename --data-source-rename $oldname:$newname";
      $return  = external_exec($command);
      //print_vars($GLOBALS['exec_status']);
      if ($GLOBALS['exec_status']['exitcode'] === 0)
      {
        print_debug("RRD DS renamed, file $fsfilename: '$oldname' -> '$newname'");
      } else {
        $return = FALSE;
      }
    }
  }

  return $return;
}

// TESTME needs unit testing
/**
 * Adds a DS to an RRD file
 *
 * @param array Device
 * @param string Filename
 * @param string New DS name
 */
function rrdtool_add_ds($device, $filename, $add)
{
  global $config;

  $return = FALSE;
  if ($config['norrd'])
  {
    print_message("[%gRRD Disabled%n] ");
  } else {
    $fsfilename = get_rrd_path($device, $filename);
    if (is_file($fsfilename))
    {
      // this function used in discovery, where not exist rrd pipes
      //rrdtool("tune", $fsfilename, "--data-source-rename $oldname:$newname");
      //$command = $config['rrdtool'] . " tune $fsfilename --data-source-rename $oldname:$newname";
      // $return  = external_exec($command);

      // FIXME -- in future do this via rrdtool tune -- requires 1.5, so not for old versions.

      $fsfilename = get_rrd_path($device, $filename);

      $return  = external_exec($config['install_dir'] . "/scripts/add_ds_to_rrd.pl ".dirname($fsfilename)." ".basename($fsfilename)." $add");

      //print_vars($GLOBALS['exec_status']);
      if ($GLOBALS['exec_status']['exitcode'] === 0)
      {
        print_debug("RRD DS added, file ".$fsfilename.": '".$add."'");
      } else {
        $return = FALSE;
      }
    }
  }

  return $return;
}

// TESTME needs unit testing
/**
 * Adds one or more RRAs to an RRD file; space-separated if you want to add more than one.
 *
 * @param array  Device
 * @param string Filename
 * @param array  RRA(s) to be added to the RRD file
 */
function rrdtool_add_rra($device, $filename, $options)
{
  global $config;

  if ($config['norrd'])
  {
    print_message('[%gRRD Disabled%n] ');
  } else {
    $fsfilename = get_rrd_path($device, $filename);

    external_exec($config['install_dir'] . "/scripts/rrdtoolx.py addrra $fsfilename $fsfilename.new $options");
    rename("$fsfilename.new", $fsfilename);
  }
}

/**
 * Escapes strings for RRDtool
 *
 * @param string String to escape
 * @param integer if passed, string will be padded and trimmed to exactly this length (after rrdtool unescapes it)
 *
 * @return string Escaped string
 */
// TESTME needs unit testing
function rrdtool_escape($string, $maxlength = NULL)
{
  if ($maxlength != NULL)
  {
    $string = substr(str_pad($string, $maxlength),0,$maxlength);
  }

  $string = str_replace(array(':', "'", '%'), array('\:', '`', '%%'), $string);

  // FIXME: should maybe also probably escape these? # \ ? [ ^ ] ( $ ) '

  return $string;
}

/**
 * Helper function to strip quotes from RRD output
 *
 * @str RRD-Info generated string
 * @return String with one surrounding pair of quotes stripped
 */
// TESTME needs unit testing
function rrd_strip_quotes($str)
{
  if ($str[0] == '"' && $str[strlen($str)-1] == '"')
  {
    return substr($str, 1, strlen($str)-2);
  }

  return $str;
}

/**
 * Determine useful information about RRD file
 *
 * Copyright (C) 2009  Bruno Prémont <bonbons AT linux-vserver.org>
 *
 * @file Name of RRD file to analyse
 *
 * @return Array describing the RRD file
 *
 */
// TESTME needs unit testing
function rrdtool_file_info($file)
{
  global $config;

  $info = array('filename'=>$file);

  $rrd = array_filter(explode(PHP_EOL, external_exec($config['rrdtool'] . ' info ' . $file)), 'strlen');
  if ($rrd)
  {
    foreach ($rrd as $s)
    {
      $p = strpos($s, '=');
      if ($p === false)
      {
        continue;
      }

      $key = trim(substr($s, 0, $p));
      $value = trim(substr($s, $p+1));
      if (strncmp($key,'ds[', 3) == 0)
      {
        /* DS definition */
        $p = strpos($key, ']');
        $ds = substr($key, 3, $p-3);
        if (!isset($info['DS']))
        {
          $info['DS'] = array();
        }

        $ds_key = substr($key, $p+2);

        if (strpos($ds_key, '[') === false)
        {
          if (!isset($info['DS']["$ds"]))
          {
            $info['DS']["$ds"] = array();
          }
          $info['DS']["$ds"]["$ds_key"] = rrd_strip_quotes($value);
        }
      }
      else if (strncmp($key, 'rra[', 4) == 0)
      {
        /* RRD definition */
        $p = strpos($key, ']');
        $rra = substr($key, 4, $p-4);
        if (!isset($info['RRA']))
        {
          $info['RRA'] = array();
        }
        $rra_key = substr($key, $p+2);

        if (strpos($rra_key, '[') === false)
        {
          if (!isset($info['RRA']["$rra"]))
          {
            $info['RRA']["$rra"] = array();
          }
          $info['RRA']["$rra"]["$rra_key"] = rrd_strip_quotes($value);
        }
      } else if (strpos($key, '[') === false) {
        $info[$key] = rrd_strip_quotes($value);
      }
    }
  }

  return $info;
}

// EOF
