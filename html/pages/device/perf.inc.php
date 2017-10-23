<?php

/**
 * Observium Network Management and Monitoring System
 * Copyright (C) 2006-2015, Adam Armstrong - http://www.observium.org
 *
 * @package    observium
 * @subpackage webui
 * @author     Adam Armstrong <adama@observium.org>
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

?>

<div class="row">
  <div class="col-md-12">

<?php

$graph_array = array('type'   => 'device_poller_perf',
                     'device' => $device['device_id']
                     );
?>


<?php

echo generate_box_open(array('title' => 'Poller Performance'));
print_graph_row($graph_array);
echo generate_box_close();

$navbar = array('brand' => "Performance", 'class' => "navbar-narrow");

$navbar['options']['overview']['text']       = 'Overview';
$navbar['options']['poller']['text']         = 'Poller Modules';

foreach ($navbar['options'] as $option => $array)
{
  if (!isset($vars['view'])) { $vars['view'] = "overview"; }
  if ($vars['view'] == $option) { $navbar['options'][$option]['class'] .= " active"; }
  $navbar['options'][$option]['url'] = generate_url($vars, array('view' => $option));
}

print_navbar($navbar);
unset($navbar);

arsort($device['state']['poller_mod_perf']);


if($vars['view'] == poller)
{

  echo generate_box_open();
  echo '<table class="' .OBS_CLASS_TABLE_STRIPED_TWO.' table-hover">' . PHP_EOL;

  foreach ($device['state']['poller_mod_perf'] as $module => $time)
  {

    echo '<tr><td><h3>'.$module.'</h3></td><td width=40>'.$time.'s</td></tr>';
    echo '<tr><td colspan=2>';

    $graph = array('type'   => 'device_pollermodule_perf',
                   'device' => $device['device_id'],
                   'module' => $module);

    print_graph_row($graph);

    echo '</td></tr>';

  }

  echo '</tbody></table>';
  echo generate_box_close();

} else {

?>

  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="box box-solid">
      <div class="box-header with-border">
        <h3 class="box-title">Poller Module Times</h3>
      </div>
      <div class="box-body no-padding">
        <table class="table table-hover table-striped table-condensed">
          <thead>
            <tr>
              <th>Module</th>
              <th colspan="2">Duration</th>

            </tr>
          </thead>
          <tbody>
<?php

foreach ($device['state']['poller_mod_perf'] as $module => $time)
{
  if ($time > 0.001)
  {
    $perc = round($time / $device['last_polled_timetaken'] * 100, 2, 2);

    echo('    <tr>
      <td><strong>'.$module.'</strong></td>
      <td style="width: 80px;">'.number_format($time, 4).'s</td>
      <td style="width: 70px;">'.$perc.'%</td>
    </tr>');
  }
}

?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-3">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Poller Total Times</h3>
        </div>
        <div class="box-body no-padding">
          <table class="table table-hover table-striped table-condensed ">
            <thead>
              <tr>
                <th>Time</th>
                <th>Duration</th>
              </tr>
            </thead>
            <tbody>
<?php

$times = dbFetchRows("SELECT * FROM `devices_perftimes` WHERE `operation` = 'poll' AND `device_id` = ? ORDER BY `start` DESC LIMIT 30", array($device['device_id']));

foreach ($times as $time)
{
  echo('    <tr>
      <td>'.format_unixtime($time['start']).'</td>
      <td>'.$time['duration'].'s</td>
    </tr>');
}

?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Discovery Times</h3>
        </div>
        <div class="box-body no-padding">
          <table class="table table-hover table-striped  table-condensed ">
            <thead>
              <tr>
                <th>Time</th>
                <th>Duration</th>
              </tr>
            </thead>
            <tbody>
<?php

$times = dbFetchRows('SELECT * FROM `devices_perftimes` WHERE `operation` = "discover" AND `device_id` = ? ORDER BY `start` DESC LIMIT 30', array($device['device_id']));

foreach ($times as $time)
{
  echo('    <tr>
      <td>'.format_unixtime($time['start']).'</td>
      <td>'.$time['duration'].'s</td>
    </tr>');
}

?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php

}

// EOF
