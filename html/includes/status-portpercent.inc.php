<?php

$graph_data = array();

$classes = array('primary', 'success', 'danger');
$colours = array('0a5f7f', '4d9221', 'd9534f');

$i=0;

if(isset($config['frontpage']['portpercent']['options'])) { $options = $config['frontpage']['portpercent']['options']; unset($config['frontpage']['portpercent']['options']); }

//add up totals in/out for each type, put it in an array.
$totals_array  = array();
foreach ($config['frontpage']['portpercent'] as $type => $data) {

        $totalInOctets = 0;
        $totalOutOctets = 0;

        //fetch ports in group using existing observium functioon
        foreach (get_group_entities($data['group']) as $port) {
                $octets = dbFetchRow("SELECT `ifInOctets_rate`, `ifOutOctets_rate` FROM `ports` WHERE `port_id` = ?", array($port));
                $totalInOctets = $totalInOctets + $octets['ifInOctets_rate'];
                $totalOutOctets = $totalOutOctets + $octets['ifOutOctets_rate'];
        }
        $totals_array[$type]["in"]      = $totalInOctets * 8;
        $totals_array[$type]["out"]     = $totalOutOctets * 8;

        $port_ids[$type][] = $port;

        $graph_data[] = array('group_id' => $data['group'],
                              'descr'    => $type,
                              'colour'   => $colours[$i]);

 $i++;

}

// total things up
$totalIn=0;
$totalOut=0;
foreach ($totals_array as $type => $dir) {
        $totalIn = $totalIn + $dir[in];
        $totalOut = $totalOut + $dir[out];
}


$percentage_bar            = array();
$percentage_bar['border']  = "#EEE";
$percentage_bar['bg']      = "#f0f0f0";
$percentage_bar['width']   = "100%";
//$percentage_bar['text']    = $avai_perc."%";
//$percentage_bar['text_c']  = "#E25A00";

$percentage_bar_out = $percentage_bar;

// do the real work
$percentIn="";
$percentOut="";

$legend = '<table class="table table-condensed-more">';

$i=0;



foreach ($totals_array as $type => $dir)
{
  $percentIn = $dir["in"] / $totalIn * 100;
  $percentOut = $dir["out"] / $totalOut * 100;
  $percent = ($dir["in"]+$dir["out"]) / ($totalIn+$totalOut) * 100;

  $color = $config['graph_colours']['mixed'][$i];
  $class = $classes[$i];

  $bars_in  .= '  <div class="progress-bar progress-bar-'.$class.'" style="width: '.$percentIn.'%"><span class="sr-only">'.round($percentIn).'%'.'</span></div>';
  $bars_out .= '  <div class="progress-bar progress-bar-'.$class.'" style="width: '.$percentOut.'%"><span class="sr-only">'.round($percentOut).'%'.'</span></div>';
  $bars     .= '  <div class="progress-bar progress-bar-'.$class.'" style="width: '.$percent.'%"><span class="sr-only">'.round($percent).'%'.'</span></div>';

  $i++;

  $legend .= '<tr><td><span class="label label-'.$class.'">'.$type.'</span></td><td><i class="icon-circle-arrow-down green"></i> <small><b>'.format_si($dir['in']).'bps</b></small></td>
                <td><i class="icon-circle-arrow-up" style="color: #323b7c;"></i> <small><b>'.format_si($dir['out']).'bps</b></small></td></tr>';

}

$legend .= '</table>';


$box_args = array('title' => 'Traffic Comparison',
                                'header-border' => TRUE,
                                'padding' => FALSE,
                    );
$box_args['header-controls'] = array('controls' => array('tooltip'   => array('icon'   => $config['icon']['info'],
                                                                              'anchor' => TRUE,
                                                                              'class'  => 'tooltip-from-element',
                                                                              //'url'    => '#',
                                                                              'data'   => 'data-tooltip-id="tooltip-help-conditions"')));


echo generate_box_open($box_args);


?>
<div id="tooltip-help-conditions" style="display: none;">
  <h3><?php if($options['graph_format'] == "multi" || $options['graph_format'] == "multi_bare") {  echo 'Graph periods: day, week, month, year'; } else { echo 'Graph period is 48 hours'; }  ?></h3>
</div>

<table class="table table-condensed">

    <?php

      if($options['graph_format'] != "none")
      {


        $graph_array = array('type'   => 'multi-port_groups_bits',
                             'width'  => 1239,
                             'height' => 90,
                             'legend' => no,
                             'from'   => $config['time']['twoday'],
                             'to'     => $config['time']['now'],
                             'perc_agg' => TRUE,
                             'data'   => var_encode(json_encode($graph_data)),
//                             'width'  => '305'
                         );

        echo '<tr><td colspan=3>';

        switch($options['graph_format'])
        {
          case 'single':
            $graph_array['height'] = 100;
            $graph_array['width']  = 1148;
            echo generate_graph_tag($graph_array);
            break;

          case 'multi':
            $graph_array['height'] = 100;
            unset($graph_array['width']);
            print_graph_row($graph_array);
            break;

          case 'multi_bare':
            $graph_array['width']  = 305;
            print_graph_row($graph_array);
            break;

          case 'single_bare':
          default:
            echo(generate_graph_tag($graph_array));
           break;
        }

        echo '</td></tr>';

      }

    ?>

<table class="table table-condensed">
<tr>
  <td rowspan="3" width="220"><?php echo $legend; ?></td>
  <th width="40"><span class="label label-success"><i class="icon-circle-arrow-down"></i> In</span></th>
  <td>
  <div class="progress" style="margin-bottom: 0;">
  <?php echo $bars_in; ?>
  </div>
  </td>
</tr>
<tr>
  <th><span class="label label-primary"><i class="icon-circle-arrow-up"></i> Out</span></th>
  <td>
  <div class="progress"  style="margin-bottom: 0;">
  <?php echo $bars_out; ?>
  </div>
  </td>
</tr>
<tr>
  <th><span class="label"><i class="icon-refresh"></i> Total</span></th>
  <td>
  <div class="progress"  style="margin-bottom: 0;">
  <?php echo $bars; ?>
  </div>
  </td>
</tr>

</table>

<?php echo generate_box_close(); ?>
