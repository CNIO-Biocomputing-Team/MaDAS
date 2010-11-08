<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.evolution.php";
	
	$plugin_path = $_SESSION['plugin_path'];
	
	$c 			= new Comodity;
	$p 			= new Project;
	$ev 		= new Evolution;
	
	//session
	$userId 		= @$_SESSION['idusers'];
	$pid 			= @$_SESSION['current_project'];

	list($i,$inf_Organismos,$tick_d) = $ev->drawPlot();

?>
<div style="width:860px;height:500px;overflow:auto;">
<div id="graphic-box" style="width:800px;height:400px"></div>
</div>
<script type="text/javascript">
	
	function showTooltip(leyend,x, y, xv,yv) {
		var myticks = <?=$tick_d?>;
        $jQ('<div id="tooltip">'+leyend+' of ' + myticks[xv][1] +'='+ yv + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#fff',
            opacity: 0.80
        }).appendTo("body").fadeIn(200);
    }

	
	$jQ(document).ready(function(){ 
		
		var Organismos = <?=$inf_Organismos?>;
		
		var options = {
			lines: { show: true },
			points: { show: true },
            selection: { mode: "xy" },
			grid: {
				hoverable: true,
				clickable: true,
				borderWidth: 0,
				backgroundColor:'#FFFFFF'
			},
			xaxis: {ticks: null},

			legend: {
				show: true,
				position: "sw"
		    },
		};	
		
		
		var previousPoint = null;
	    $jQ("#graphic-box").bind("plothover", function (event, pos, item) {


	            if (item) {
	                if (previousPoint != item.datapoint) {
	                    previousPoint = item.datapoint;
	                    
	                    $jQ("#tooltip").remove();
	                    var x = item.datapoint[0],
	                        y = item.datapoint[1];
	                    
	                    showTooltip(item.series.label,item.pageX, item.pageY,x,y	);
	                                
	                }
	            }
	            else {
	                $jQ("#tooltip").remove();
	                previousPoint = null;            
	            }

	    });

		

	   $jQ.plot($jQ("#graphic-box"),[{ color:'#109618',data: Organismos , label: "Genes"}],options);

	});
</script>