<html>
  <head>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["linechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Gene');
        data.addColumn('number', 'Up regulated');
        data.addColumn('number', 'Up regulated **');
        data.addColumn('number', 'Down regulated');
        data.addColumn('number', 'Down regulated **');
        data.addRows(4);
        data.setValue(0, 0, '2004');
        data.setValue(0, 1, 1000);
        data.setValue(0, 2, 400);
        data.setValue(1, 0, '2005');
        data.setValue(1, 1, 1170);
        data.setValue(1, 2, 460);
        data.setValue(2, 0, '2006');
        data.setValue(2, 1, 860);
        data.setValue(2, 2, 580);
        data.setValue(3, 0, '2007');
        data.setValue(3, 1, 1030);
        data.setValue(3, 2, 540);

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, {width: 400, height: 240, legend: 'bottom', title: 'Company Performance'});
      }
    </script>
  </head>

  <body>
    <div id="chart_div"></div>
  </body>
</html>