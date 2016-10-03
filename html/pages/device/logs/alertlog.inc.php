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

if (!is_array($alert_rules)) { $alert_rules = cache_alert_rules(); }

// Note, this form have more complex grid and class elements for responsive datetime field
$form = array('type'          => 'rows',
              'space'         => '5px',
              'submit_by_key' => TRUE,
              'url'           => generate_url($vars));

$where = ' WHERE 1 ' . generate_query_values($device['device_id'], 'device_id');

// Checkers Field
$form_filter = dbFetchColumn('SELECT DISTINCT `alert_test_id` FROM `alert_log`' . $where);
$form_items['checkers'] = generate_form_values('alert_log', $form_filter, 'alert_test_id');
$form['row'][0]['alert_test_id'] = array(
                              'type'        => 'multiselect',
                              'name'        => 'Checkers',
                              'width'       => '100%',
                              'div_class'   => 'col-lg-2 col-md-2 col-sm-4',
                              'subtext'     => TRUE,
                              'value'       => $vars['alert_test_id'],
                              'values'      => $form_items['checkers']);

// Status Type Field
$form_filter = dbFetchColumn('SELECT DISTINCT `log_type` FROM `alert_log`' . $where);
$form_items['statuses'] = generate_form_values('alert_log', $form_filter, 'log_type');
$form['row'][0]['log_type'] = array(
                              'type'        => 'multiselect',
                              'name'        => 'Status Type',
                              'width'       => '100%',
                              'div_class'   => 'col-lg-2 col-md-2 col-sm-4',
                              'size'        => '15',
                              'value'       => $vars['log_type'],
                              'values'      => $form_items['statuses']);

// Datetime Field
$form['row'][0]['timestamp'] = array(
                              'type'        => 'datetime',
                              //'grid'        => 5,
                              //'width'       => '70%',
                              'div_class'   => 'col-lg-5 col-md-7 col-sm-9',
                              'presets'     => TRUE,
                              'min'         => dbFetchCell('SELECT `timestamp` FROM `alert_log`' . $where . ' ORDER BY `timestamp` LIMIT 0,1;'),
                              'max'         => dbFetchCell('SELECT `timestamp` FROM `alert_log`' . $where . ' ORDER BY `timestamp` DESC LIMIT 0,1;'),
                              'from'        => $vars['timestamp_from'],
                              'to'          => $vars['timestamp_to']);
// Second row with timestamp for sm
//$form['row_options'][1]  = array('class' => 'hidden-lg hidden-md hidden-xs');
//$form['row'][1]['timestamp'] = $form['row'][0]['timestamp'];
//$form['row'][1]['timestamp']['div_class'] = 'text-nowrap col-sm-9';

// search button
$form['row'][0]['search']   = array(
                              'type'        => 'submit',
                              //'name'        => 'Search',
                              //'icon'        => 'icon-search',
                              'div_class'   => 'col-lg-3 col-md-1 col-sm-3',
                              //'grid'        => 1,
                              'right'       => TRUE);

print_form($form);
unset($form, $form_items, $form_filter);

// Pagination
$vars['pagination'] = TRUE;

// Print Alert Log
print_alert_log($vars);

register_html_title('Alert Log');

?>
  </div> <!-- col-md-12 -->

</div> <!-- row -->
<?php

// EOF
