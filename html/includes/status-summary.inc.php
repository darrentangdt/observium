<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage webui
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

// Included in: html/pages/front/default.php, html/includes/panels/default.php

if ($devices['down'])  { $devices['class']  = "error"; } else { $devices['class']  = ""; }
if ($ports['down'])    { $ports['class']    = "error"; } else { $ports['class']    = ""; }

?>

<div class="<?php echo($div_class); ?>">
  <div class="box box-solid">
<table class="table table-condensed-more table-striped">
  <thead>
    <tr>
      <th class="state-marker"></th>
      <th></th>
      <th style="width: 10%">Total</th>
      <th style="width: 14%">Up</th>
      <th style="width: 14%">Down</th>
      <th style="width: 20%">Ignored (Dev)</th>
      <th style="width: 20%">Disabled / Shut</th>
    </tr>
  </thead>
  <tbody>
    <tr class="<?php echo($devices['class']); ?>">
      <td class="state-marker"></td>
      <td><strong><a       href="<?php echo(generate_url(array('page' => 'devices'))); ?>">Devices</a></strong></td>
      <td><a               href="<?php echo(generate_url(array('page' => 'devices')));                                   ?>"><?php echo($devices['count'])    ?></a></td>
      <td><a class="green" href="<?php echo(generate_url(array('page' => 'devices', 'status' => '1')));                  ?>"><?php echo($devices['up'])       ?> up</a></td>
      <td><a class="red"   href="<?php echo(generate_url(array('page' => 'devices', 'status' => '0', 'ignore' => '0'))); ?>"><?php echo($devices['down'])     ?> down</a></td>
      <td><a class="black" href="<?php echo(generate_url(array('page' => 'devices', 'ignore' => '1')));                  ?>"><?php echo($devices['ignored'])  ?> ignored</a></td>
      <td><a class="grey"  href="<?php echo(generate_url(array('page' => 'devices', 'disabled' => '1')));                ?>"><?php echo($devices['disabled']) ?> disabled</a></td>
    </tr>
    <tr class="<?php echo($ports['class']) ?>">
      <td class="state-marker"></td>
      <td><strong><a       href="<?php echo(generate_url(array('page' => 'ports'))); ?>">Ports</a></strong></td>
      <td><a               href="<?php echo(generate_url(array('page' => 'ports')));                                     ?>"><?php echo($ports['count'])      ?></a></td>
      <td><a class="green" href="<?php echo(generate_url(array('page' => 'ports', 'state' => 'up')));                    ?>"><?php echo($ports['up'])         ?> up</a></td>
      <td><a class="red"   href="<?php echo(generate_url(array('page' => 'ports', 'state' => 'down', 'ignore' => '0'))); ?>"><?php echo($ports['down'])       ?> down</a></td>
      <td><a class="black" href="<?php echo(generate_url(array('page' => 'ports', 'ignore' => '1')));                    ?>"><?php echo($ports['ignored']); ?> (<?php echo (count($cache['ports']['device_ignored'])) ?>) ignored</a></td>
      <td><a class="grey"  href="<?php echo(generate_url(array('page' => 'ports', 'state' => 'admindown')));             ?>"><?php echo($ports['shutdown'])   ?> shutdown</a></td>
    </tr>
<?php
  if ($sensors['count'])
  {
    if ($sensors['alert']) { $sensors['class'] = "error"; } else { $sensors['class'] = ""; }
?>
    <tr class="<?php echo($sensors['class']) ?>">
      <td class="state-marker"></td>
      <td><strong><a       href="<?php echo(generate_url(array('page' => 'health'))); ?>">Sensors</a></strong></td>
      <td><a               href="<?php echo(generate_url(array('page' => 'health', 'metric' => 'sensors')));                             ?>"><?php echo($sensors['count'])    ?></a></td>
      <td><a class="green" href="<?php echo(generate_url(array('page' => 'health', 'metric' => 'sensors', 'event' => 'ok')));            ?>"><?php echo($sensors['ok'])       ?> ok</a></td>
      <td><a class="red"   href="<?php echo(generate_url(array('page' => 'health', 'metric' => 'sensors', 'event' => 'alert,warning'))); ?>"><?php echo($sensors['alert'])    ?> alert</a></td>
      <td><a class="black" href="<?php echo(generate_url(array('page' => 'health', 'metric' => 'sensors', 'event' => 'ignore')));        ?>"><?php echo($sensors['ignored'])  ?> ignored</a></td>
      <td><a class="grey"  href="<?php echo(generate_url(array('page' => 'health', 'metric' => 'sensors')));                             ?>"><?php echo($sensors['disabled']) ?> disabled</a></td>
    </tr>
<?php
  } # end if sensors
  if ($statuses['count'])
  {
    if ($statuses['alert']) { $statuses['class'] = "error"; } else { $statuses['class'] = ""; }
?>
    <tr class="<?php echo($statuses['class']) ?>">
      <td class="state-marker"></td>
      <td><strong><a       href="<?php echo(generate_url(array('page' => 'health', 'metric' => 'status'))); ?>">Statuses</a></strong></td>
      <td><a               href="<?php echo(generate_url(array('page' => 'health', 'metric' => 'status')));                              ?>"><?php echo($statuses['count'])   ?></a></td>
      <td><a class="green" href="<?php echo(generate_url(array('page' => 'health', 'metric' => 'status', 'event' => 'ok,warning')));     ?>"><?php echo($statuses['ok'])      ?> ok</a></td>
      <td><a class="red"   href="<?php echo(generate_url(array('page' => 'health', 'metric' => 'status', 'event' => 'alert,')));         ?>"><?php echo($statuses['alert'])   ?> alert</a></td>
      <td><a class="black" href="<?php echo(generate_url(array('page' => 'health', 'metric' => 'status', 'event' => 'ignore')));         ?>"><?php echo($statuses['ignored']) ?> ignored</a></td>
      <td><a class="grey"  href="<?php echo(generate_url(array('page' => 'health', 'metric' => 'status')));                              ?>"><?php echo($statuses['disabled'])?> disabled</a></td>
    </tr>
<?php
  } # end if statuses
?>
    </tbody>
  </table>
  </div>
</div>

<?php

  switch (TRUE)
  {
    case ($cache['alert_entries']['up'] == $cache['alert_entries']['count']):
      $check['class']  = "green";  $check['table_tab_colour'] = "#194b7f"; $check['html_row_class'] = "";
      break;
    case ($cache['alert_entries']['down'] > 0):
      $check['class']  = "red";    $check['table_tab_colour'] = "#cc0000"; $check['html_row_class'] = "error";
      break;
    case ($cache['alert_entries']['delay'] > 0):
      $check['class']  = "orange"; $check['table_tab_colour'] = "#ff6600"; $check['html_row_class'] = "warning";
      break;
    case ($cache['alert_entries']['suppress'] > 0):
      $check['class']  = "purple"; $check['table_tab_colour'] = "#740074"; $check['html_row_class'] = "suppressed";
      break;
    case ($cache['alert_entries']['up'] > 0):
      $check['class']  = "green";  $check['table_tab_colour'] = "#194b7f"; $check['html_row_class'] = "";
      break;
    default:
      $check['class']  = "gray";   $check['table_tab_colour'] = "#555555"; $check['html_row_class'] = "disabled";
  }

  $check['status_numbers'] = '
          <span class="green">'  . $cache['alert_entries']['up']       . '</span>/
          <span class="purple">' . $cache['alert_entries']['suppress'] . '</span>/
          <span class="red">'    . $cache['alert_entries']['down']     . '</span>/
          <span class="orange">' . $cache['alert_entries']['delay']    . '</span>/
          <span class="gray">'   . $cache['alert_entries']['unknown']  . '</span>';
?>

<div class="<?php echo($div_class); ?>">
 <div class="box box-solid">
  <table class="table  table-condensed-more  table-striped">
  <thead>
    <tr>
     <th class="state-marker"></th>
     <th></th>
     <th>Ok</th>
     <th>Fail</th>
     <th>Delay</th>
     <th>Suppress</th>
     <th>Other</th>
    </tr>
  </thead>
    <tbody>
      <tr class="<?php echo($check['html_row_class']); ?>">
        <td class="state-marker"></td>
        <td><a href="/alerts/"><strong>Alerts</strong></a></td>
        <td><span class="green"><?php  echo $cache['alert_entries']['up'];       ?></span></td>
        <td><span class="red"><?php    echo $cache['alert_entries']['down'];     ?></span></td>
        <td><span class="orange"><?php echo $cache['alert_entries']['delay'];    ?></span></td>
        <td><span class="purple"><?php echo $cache['alert_entries']['suppress']; ?></span></td>
        <td><span class="gray"><?php   echo $cache['alert_entries']['unknown'];  ?></span></td>
      </tr>
    </tbody>
  </table>
 </div>
<?php


$navbar = array();
$navbar['class'] = 'navbar-narrow';
$navbar['brand'] = 'Groups';
$navbar['style'] = 'margin-top: 10px';
$navbar['community'] = FALSE;

$groups = get_groups_by_type($config['wui']['groups_list']);

foreach ($config['wui']['groups_list'] as $entity_type)
{
  if (!isset($config['entities'][$entity_type])) { continue; } // Skip unknown types

  $navbar['options'][$entity_type]['icon'] = $config['entities'][$entity_type]['icon'];
  $navbar['options'][$entity_type]['text'] = nicecase($entity_type);
  foreach ($groups[$entity_type] as $group)
  {
    $navbar['options'][$entity_type]['suboptions'][$group['group_id']]['text'] = escape_html($group['group_name']);
    $navbar['options'][$entity_type]['suboptions'][$group['group_id']]['icon'] = $config['entities'][$entity_type]['icon'];
    $navbar['options'][$entity_type]['suboptions'][$group['group_id']]['url']  = generate_url(array('page' => 'group', 'group_id' => $group['group_id']));
    if ($vars['group_id'] == $group['group_id'])
    {
      $navbar['options'][$entity_type]['suboptions'][$group['group_id']]['class'] = "active";
      $navbar['options'][$entity_type]['class'] = "active";
    }
  }
}

print_navbar($navbar);
unset($navbar);

?>

</div>
<?php

// EOF
