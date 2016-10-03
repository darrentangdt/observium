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

include($config['html_dir']."/includes/alerting-navbar.inc.php");


print_syslog_rules_table($vars);

$modals = '';

function print_syslog_rules_table($vars)
{

  if(isset($vars['la_id']))
  {
    $las = dbFetchRows("SELECT * FROM `syslog_rules` WHERE `la_id` = ?", array($vars['la_id']));
  } else {
    $las = dbFetchRows("SELECT * FROM `syslog_rules` ORDER BY `la_name`");
  }

  if(is_array($las) && count($las))
  {

    $string = generate_box_open();
    $string .= '<table class="table table-striped table-hover table-condensed">' . PHP_EOL;

    $cols = array(
      array(NULL, 'class="state-marker"'),
      'name'         => array('Name', 'style="width: 160px;"'),
      'descr'        => array('Description', 'style="width: 400px;"'),
      'rule'         => 'Rule',
      'severity'     => array('Severity', 'style="width: 60px;"'),
      'disabled'      => array('Status', 'style="width: 60px;"'),
      'controls'      => array('', 'style="width: 40px;"'),
    );

    $string .= get_table_header($cols, $vars);

    foreach($las as $la)
    {

      if($la['disable'] == 0) { $la['html_row_class'] = "up"; } else { $la['html_row_class'] = "disabled"; }

      $string .= '<tr class="' . $la['html_row_class'] . '">';
      $string .= '<td class="state-marker"></td>';

      $string .= '    <td><strong><a href="'.generate_url(array('page' => 'syslog_rules', 'la_id' => $la['la_id'])).'">' . escape_html($la['la_name']) . '</a></strong></td>' . PHP_EOL;
      $string .= '    <td><a href="'.generate_url(array('page' => 'syslog_rules', 'la_id' => $la['la_id'])).'">' . escape_html($la['la_descr']) . '</a></td>' . PHP_EOL;
      $string .= '    <td><code>' . escape_html($la['la_rule']) . '</code></td>' . PHP_EOL;
      $string .= '    <td>' . escape_html($la['la_severity']) . '</td>' . PHP_EOL;
      $string .= '    <td>' . ($la['la_disable'] ? '<span class="label label-error">disabled</span>' : '<span class="label label-success">enabled</span>') . '</td>' . PHP_EOL;
      $string .= '    <td style="text-align: right;"><a href="#edit_modal_'.$la['la_id'].'" data-toggle="modal"><i class="icon-cog text-muted"></i></a>&nbsp;';
      $string .= '                                   <a href="#del_modal_'.$la['la_id'].'" data-toggle="modal"><i class="icon-trash text-danger"></i></a></td>';
      $string .= '  </tr>' . PHP_EOL;


      // Delete Rule Modal
      $modals .= '<div id="del_modal_'.$la['la_id'].'" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="delete_syslog_rule" aria-hidden="true">
 <form id="edit" name="edit" method="post" class="form" action="'.generate_url(array('page' => 'syslog_rules')).'">
  <input type="hidden" name="la_id" value="'.$la['la_id'].'">

  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Delete Syslog Rule '.escape_html($la['la_descr']).'</h3>
  </div>
  <div class="modal-body">

  <span class="help-block">This will completely delete the rule and all associations and history.</span>
  <fieldset>
    <div class="control-group">
      <label class="control-label" for="confirm">
        <strong>Confirm</strong>
      </label>
      <div class="controls">
        <label class="checkbox">
          <input type="checkbox" name="confirm" value="confirm" onchange="javascript: showWarning(this.checked, '.$la['la_id'].');" />
          Yes, please delete this rule.
        </label>

      <script type="text/javascript">'."
        function showWarning(checked, id) {
          $('#warning'+id).toggle();
          if (checked) {
            $('#delete_button'+id).removeAttr('disabled');
          } else {
            $('#delete_button'+id).attr('disabled', 'disabled');
          }
        } ".'
      </script>

      </div>
    </div>
  </fieldset>

  <div class="alert alert-message alert-danger" id="warning'.$la['la_id'].'" style="display:none;">
    <h4 class="alert-heading"><i class="icon-warning-sign"></i> Warning!</h4>
    This rule and all history will be completely deleted!
  </div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button id="delete_button'.$la['la_id'].'" type="submit" class="btn btn-danger" name="submit" value="delete_syslog_rule" disabled><i class="icon-trash icon-white"></i> Delete Rule</button>
  </div>
 </form>
</div>';

      // Edit Rule Modal

      $modals .= '
<div id="edit_modal_'.$la['la_id'].'" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <form id="edit" name="edit" method="post" class="form form-horizontal" action="">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="edit_modal_label">Edit Syslog Rule</h3>
  </div>
  <div class="modal-body">

  <input type="hidden" name="la_id" value="'.$la['la_id'].'">

  <fieldset>

    <div class="control-group">
      <label class="control-label" for="la_name">Rule Name</label>
      <div class="controls">
        <input type="text" name="la_name" size="32" value="'.escape_html($la['la_name']).'"/>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="la_descr">Description</label>
      <div class="controls">
        <textarea class="form-control col-sm-12" name="la_descr" rows="3">'.escape_html($la['la_descr']).'</textarea>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="la_rule">Regular Expression</label>
      <div class="controls">
        <textarea class="form-control col-sm-12" name="la_rule" rows="3">'.escape_html($la['la_rule']).'</textarea>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="la_disable">Status</label>
      <div class="controls">
        <input type=checkbox id="la_disable" name="la_disable" '. ($la['la_disable'] ? 'checked' : '') .' data-toggle="switch" data-on-text="disabled" data-off-text="enabled" data-on-color="danger" data-off-color="primary">
      </div>
    </div>


  </fieldset>

  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button type="submit" class="btn btn-primary" name="submit" value="edit_syslog_rule"><i class="icon-ok icon-white"></i> Save Changes</button>
  </div>
 </form>
</div>';



    }

    $string .= '</table>';
    $string .= generate_box_close();

    echo $string;

  } else {

    print_warning("There are currently no Syslog alerting filters defined.");

  }

  echo $modals;

}

if (isset($vars['la_id']))
{
  // Pagination
  $vars['pagination'] = TRUE;

  print_logalert_log($vars);
}

// EOF
