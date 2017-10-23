<?php


function generate_bandwidth_chart($url)
{

  $div_id = generate_random_string('5');

  register_html_resource('js', 'd3.v3.min.js');
  register_html_resource('js', 'c3.min.js');

  $string = '
  <div class="box box-solid" style="padding:10px; width:100%%; height:350px;">
    <div id="chart_'.$div_id.'" style="width:100%%; height:330px;">
    </div>
  </div>';

  $string .= "
<script>
var chart = c3.generate({
  bindto: '#chart_".$div_id."',
  data: {
    url: '". $url ."',
    mimeType: 'json',
    type: 'bar',
    labels: { format: { total: d3.format('.2s') } },
    keys: {
      x: 'label',
      value: ['in', 'total', 'out']
    },
    colors: {
      in: '#4A8328',
      out: '#323B7C',
      total: '#F25C05'
    },
    names: {
      in: 'Incoming',
      out: 'Outgoing',
      total: 'Total',
    }
  },
  legend: { position: 'inset' },
  axis: {
        y: { tick: { format: d3.format('.2s') } },
        x: { type: 'category' }
  },
  grid: {
    x: { show: false },
    y: { show: true },
  }
});
</script>";

  return $string;

}

function generate_billing_graph($url)
{

  register_html_resource('js', 'dygraph-combined.js');

  $div_id = generate_random_string('5');

  $output = '
<div class="box box-solid" style="padding:10px; width:100%%; height:400px;">
  <div id="chart_'.$div_id.'" style="width:100%%; height:360px;">
  </div>
</div>';

  $output .= "
<script type='text/javascript'>
  g2 = new Dygraph(
    document.getElementById('chart_".$div_id."'),
    '".$url."', // path to CSV file
    {
      axes: {
                x: {
                  gridLineWidth: 1,
                  drawGrid: true,
                  gridLineColor: '#999999',
                },
                y: {
                  ticker: Dygraph.numericLinearTicks,
                  gridLineWidth: 1,
                  drawGrid: true,
                  gridLineColor: '#bbbbbb',
                  gridLinePattern: [4,4]
                },
      },
      ylabel: 'Bits per second',
      xlabel: 'Date (Ticks indicate the start of the indicated time period)',
      highlightCircleSize: 2,
      strokeWidth: 1,
      colors: ['#4A8328', '#323B7C'],
      fillGraph: true,
      fillAlpha: 0.25,
      axisLabelFontSize: 12,
      highlightSeriesOpts: {
         strokeWidth: 2,
         strokeBorderWidth: 0,
          highlightCircleSize: 4,
        },
      showRangeSelector: true,
      //dateWindow: [ '<?php echo $data_from; ?>000', '<?php echo $data_to; ?>999' ],
      labels: ['Date','Input', 'Output'],
      labelsKMB: true,
      legend: 'always',
      labelsDivStyles: { 'textAlign': 'right' },
      rangeSelectorHeight: 30,
    }          // options
  );
</script>
";

  return $output;

}
