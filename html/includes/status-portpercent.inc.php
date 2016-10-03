<?php


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

$classes = array('primary', 'success', 'danger');

$legend = '<table class="table table-condensed-more">';

$i=0;
foreach ($totals_array as $type => $dir) {
        // derp we actually need whole #s here for the graph. muiltiply by 100, yo!
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
                <td><i class="icon-circle-arrow-up" style="color: #323b7c;"></i> <small><b>'.format_si($dir['out']).'bps</b></small></td>
</tr>';

}

$legend .= '</table>';

?>

<table class="table table-condensed">
<tr>
  <td rowspan="3" width="220"><?php echo $legend; ?></td>
  <th width="40"><span class="label label-success"><i class="icon-circle-arrow-up"></i> In</span></th>
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
