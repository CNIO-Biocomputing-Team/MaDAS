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
	
	//parameters
	$circles = $_GET["circles"];

	list($i,$inf_human,$inf_danio,$inf_celegans,$inf_rat,$inf_Monodelphis_domestica,$tick_y) = $ev->drawPlot($circles);

?>

<div id="graphic-box" style="width:850px;height:470px"></div>
<script type="text/javascript">
	
	function showTooltip(leyend,x, y, xv,yv,zv) {
		var myticks = <?=$tick_y?>;
        $jQ('<div id="tooltip">'+leyend+' of '+ myticks[yv-2] +'='+ zv + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#fff',
            opacity: 0.80,
            'z-index': 3000
        }).appendTo("body").fadeIn(200);
    }

	
	$jQ(document).ready(function(){ 
		
		var human = <?=$inf_human?>;
		var danio = <?=$inf_danio?>;
		var celegans = <?=$inf_celegans?>;
		var rat = <?=$inf_rat?>;
		var Monodelphis_domestica =<?=$inf_Monodelphis_domestica?>;
		
		var options = {
			lines: { show: true},
			points: { show: true,radius:5 },
            selection: { mode: "xy" },
			grid: {
				hoverable: true,
				clickable: true,
				borderWidth: 0,
				backgroundColor:'#FFFFFF'
			},
			xaxis: {max: 13,tickSize:1},

			legend: {
				show: true,
				position: "se"
		    },
		};	
		
		
		var previousPoint = null;
	    $jQ("#graphic-box").bind("plothover", function (event, pos, item) {


	            if (item) {
	                if (previousPoint != item.datapoint) {
	                    previousPoint = item.datapoint;
	                    
	                    $jQ("#tooltip").remove();
	                    var x = item.datapoint[0],
	                        y = item.datapoint[1],
	                        z = item.datapoint[2];
	                    
	                    showTooltip('<?=$circles?>',item.pageX, item.pageY,x,y,z);
	                                
	                }
	            }
	            else {
	                $jQ("#tooltip").remove();
	                previousPoint = null;            
	            }

	    });

		

	   $jQ.plot($jQ("#graphic-box"),[{ color:'#109618',data: human , label: "Human path"},{ color:'#3366CC',data: danio , label: "Danio path"},{ color:'#DC3912',data: celegans , label: "C elegans path"},{ color:'#FF9900',data: rat , label: "Rattus norvegicus path"},{ color:'#990099',data: Monodelphis_domestica , label: "Monodelphis domestica path"}],options);

	});
</script>