<?php
session_start();
ini_set('include_path',$_SESSION['include_path']);
require_once 'mc-google-init.php';

if(isset($_GET['tq'])) {
    $vis->addEntity('datasource_treefam_genes', array(
    	'table' => 'datasource_treefam_genes',
        'fields' => array(
            'IDX' => array('field' => 'IDX', 'type' => 'number'),
            'TAX_ID' => array('field' => 'TAX_ID', 'type' => 'number'),
            'GID' => array('field' => 'GID', 'type' => 'text')
        )
    ));
    
    $vis->handleRequest();
    die();
}
?>
<html>
<head>
    <title>Simple single-table visualization example</title>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
            google.load('visualization', '1', {'packages': ['linechart']});
        
        google.setOnLoadCallback(function() {
         	motion_chart = null;
            var query = new google.visualization.Query('google-chart.php');
            query.setQuery('select IDX,TAX_ID,GID from datasource_treefam_genes order by GID LIMIT 10000');
            query.send(function(res) {
                if(res.isError()) {
                    alert(res.getDetailedMessage());
                } else {
                    var table = new google.visualization.LineChart(document.getElementById('chart-div'));
                    table.draw(res.getDataTable(), {'height': 400});
                }
            });
        });
    </script>
</head>
<body>
    <div id="chart-div"></div>
</body>
</html>
