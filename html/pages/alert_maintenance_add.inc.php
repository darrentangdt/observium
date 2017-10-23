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

// Global write permissions required.
if ($_SESSION['userlevel'] < 8)
{
  print_error_permission();
  return;
}

include($config['html_dir']."/includes/alerting-navbar.inc.php");
include($config['html_dir']."/includes/maintenance-navbar.inc.php");

?>


<div class="row">
  <div class="col-md-8">
<?php

  $form = array('type'      => 'horizontal',
                'id'        => 'add_maintenance',
                'title'     => 'Add Scheduled Maintenance',
                'url'       => generate_url(array('page' => 'alert_maintenance')),
               );

  $form['row'][1]['maint_name'] = array(
                                  'type'        => 'text',
                                  'name'        => 'Name',
                                  'live-search' => FALSE,
                                  'width'       => '600px',
                                  'value'       => $vars['maint_name']);
  $form['row'][2]['maint_descr'] = array(
                                  'type'        => 'textarea',
                                  'name'        => 'Description',
                                  'width'       => '600px',
                                  //'class'       => 'col-md-8',
                                  'rows'        => 12,
                                  'value'       => $vars['maint_descr']);
  $form['row'][3]['maint_time'] = array(
                                  'type'        => 'datetime',
                                  'name'        => 'Maintenance Period');
  $form['row'][4]['maint_global'] = array(
                                  'type'        => 'switch',
                                  'name'        => 'Global Maintenance',
                                  'value'       => 0);
  //$form['row'][30]['submit']    = array(
  $form['row'][30]['action']    = array(
                                  'type'        => 'submit',
                                  'name'        => 'Add Schedule Maintenance',
                                  'icon'        => 'icon-plus',
                                  //'right'       => TRUE,
                                  'class'       => 'btn-success',
                                  'value'       => 'add_maintenance');

  print_form($form);
  unset($form);

?>

  </div>
  <div class="col-md-4">
  <?php

  $box_args['title']         = 'Timezone Guidelines';
  $box_args['header-border'] = TRUE;
  $box_args['padding']       = TRUE;

  echo generate_box_open($box_args);

  echo '<p>Please note that all dates and times used within Observium are relative to the timezone set on the Observium server.</p>';

  echo '<p>The server timezone is: <strong><i>'.date_default_timezone_get().'</i></strong><br />';
  echo 'The current server time is: <strong><i>'.date("D M j G:i:s T Y").'</i></strong></p>';

  echo generate_box_close();

  ?>
  </div>
</div>

<?php

// EOF
