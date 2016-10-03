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

$where = ' WHERE 1 ' . generate_query_values($device['device_id'], 'device_id');

$timestamp_min = dbFetchCell('SELECT `timestamp` FROM `syslog` '.$where.' ORDER BY `timestamp` LIMIT 0,1;');
if ($timestamp_min)
{
  $timestamp_max = dbFetchCell('SELECT `timestamp` FROM `syslog` '.$where.' ORDER BY `timestamp` DESC LIMIT 0,1;');

  // Note, this form have more complex grid and class elements for responsive datetime field
  $form = array('type'          => 'rows',
                'space'         => '5px',
                'submit_by_key' => TRUE,
                'url'           => generate_url($vars));

  // Message field
  $form['row'][0]['message'] = array(
                                'type'        => 'text',
                                'name'        => 'Message',
                                'placeholder' => 'Message',
                                'width'       => '100%',
                                'div_class'   => 'col-lg-4 col-md-6 col-sm-6',
                                'value'       => $vars['message']);

  // Priority field
  $form_filter = dbFetchColumn('SELECT DISTINCT `priority` FROM `syslog`' . $where);
  $form_items['priorities'] = generate_form_values('syslog', $form_filter, 'priorities');
  $form['row'][0]['priority'] = array(
                                'type'        => 'multiselect',
                                'name'        => 'Priorities',
                                'width'       => '100%',
                                'div_class'   => 'col-lg-1 col-md-2 col-sm-2',
                                'subtext'     => TRUE,
                                'value'       => $vars['priority'],
                                'values'      => $form_items['priorities']);

  // Program field
  $form_filter = dbFetchColumn('SELECT DISTINCT `program` FROM `syslog` IGNORE INDEX (`program`)' . $where);
  $form_items['programs'] = generate_form_values('syslog', $form_filter, 'programs');
  $form['row'][0]['program'] = array(
                                'type'        => 'multiselect',
                                'name'        => 'Programs',
                                'width'       => '100%',
                                'div_class'   => 'col-lg-1 col-md-2 col-sm-2',
                                'size'        => '15',
                                'value'       => $vars['program'],
                                'values'      => $form_items['programs']);

  // Datetime field
  $form['row'][0]['timestamp'] = array(
                                'type'        => 'datetime',
                                'div_class'   => 'col-lg-5 col-md-7 col-sm-10',
                                'presets'     => TRUE,
                                'min'         => $timestamp_min,
                                'max'         => $timestamp_max,
                                'from'        => $vars['timestamp_from'],
                                'to'          => $vars['timestamp_to']);
  // Second row with timestamp for md and sm
  //$form['row_options'][1]  = array('class' => 'hidden-lg hidden-xs');
  //$form['row'][1]['timestamp'] = $form['row'][0]['timestamp'];
  //$form['row'][1]['timestamp']['div_class'] = 'text-nowrap col-md-7 col-sm-8';

  // search button
  $form['row'][0]['search']   = array(
                                'type'        => 'submit',
                                //'name'        => 'Search',
                                //'icon'        => 'icon-search',
                                'div_class'   => 'col-lg-1 col-md-5 col-sm-2',
                                //'grid'        => 1,
                                'right'       => TRUE);

  print_form($form);
  unset($form, $form_items, $form_devices);

  // Pagination
  $vars['pagination'] = TRUE;

  // Print syslog
  print_syslogs($vars);
} else {
  print_warning('<h3>No syslog entries found!</h4>
This device does not have any syslog entries.
Check that the syslog daemon and Observium configuration options are set correctly, that this device is configured to send syslog to Observium and that there are no firewalls blocking the messages.

See <a href="'.OBSERVIUM_URL.'/wiki/Category:Documentation" target="_blank">documentation</a> and <a href="'.OBSERVIUM_URL.'/wiki/Configuration_Options#Syslog_Settings" target="_blank">configuration options</a> for more information.');
}

register_html_title('Syslog');

// EOF
