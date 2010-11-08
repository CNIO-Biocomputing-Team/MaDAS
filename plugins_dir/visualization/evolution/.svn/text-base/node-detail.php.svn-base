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
	
	$plugin_path = 'plugins_dir/visualization/evolution/';
	
	$c 			= new Comodity;
	$p 			= new Project;
	$ev 		= new Evolution;
	$circles	= 'Genes';
	
	//session
	$userId 		= @$_SESSION['idusers'];
	$pid 			= @$_SESSION['current_project'];
	
	//parameters
	$idtree = 1;
	$nodes 	= @$_GET['nodes'];
	$tag	= @$_GET['tag'];
	$sent	= @$_GET['sent'];
	$log	= (@$_GET['log'])?@$_GET['log']:0;
	$display	= (@$_GET['display'])?@$_GET['display']:'All';
	$groups 	= (@$_GET['groups'])?@$_GET['groups']:'';
	
	//echo $log;
	
	if ($sent){
		$series = $ev->getData($tag,explode(',',$nodes),$log,$display,$groups);
	}
	
	
	//species
	$strSQL = "SELECT SQL_CACHE TAX_ID, TAXNAME FROM datasource_treefam_species_tree WHERE idspecie_tree=".$idtree;
	$tmp =  $db->get_results($strSQL);
	foreach ($tmp as $r){
		$tax[$r->TAX_ID] = $r->TAXNAME;
	}
	
	
?>
<table style="background-color:#E0E0E0;border:1px solid #CCCCCC;padding:10px;" width="100%">
	<tr>
		<td class="label" valign="top">Property</td>
		<td id="divtag" class="value" valign="top"></td>
		<td class="label" rowspan="4" valign="top">Species</td>
		<td id="divnode" class="value" rowspan="4" valign="top"></td>
		<td class="label" rowspan="4" valign="top">Groups</td>
		<td id="divgroup" class="value" rowspan="4" valign="top"></td>
		<td class="value" rowspan="4" valign="top"><input id="send" type="button" value="Go!" class="button"></td>
	</tr>
	<tr>	
		<td class="label" valign="top">Order By</td>
		<td id="divorder" class="value" valign="top"></td>
	</tr>
	<tr>	
		<td class="label" valign="top">Min value</td>
		<td id="divlog" class="value" valign="top">
			 <? 
				$name='log';
				$array = array ('0' => '0', '10' => '10', '20' => '20', '30' => '30', '40' => '40', '50' => '50','60' => '60', '70' => '70', '80' => '80', '90' => '90', '100' => '100', '500' => '500' , '1000' => '1000', '1500' => '1500');
				$value = $log;
				$class = '';
				include('select-general.php');
			?>
		</td>
	</tr>
	<tr>	
		<td class="label" valign="top">Display</td>
		<td id="divshow" class="value" valign="top">
			 <? 
				$name='display';
				$array = array ('All' => 'All', 'Differences' => 'Differences', 'Coincidences' => 'Coincidences');
				$value = $display;
				$class = '';
				include('select-general.php');
			?>
		</td>
	</tr>
</table>
<div id="graphic-box" style="width:850px;height:400px"></div>
<script type="text/javascript">

	//tag
	<?
	     $multiple = '';
	     $name = 'tag';
	     $table = 'datasource_treefam_tags';
	     $vfield = 'id';
	     $master = '';
	     $masterv = '';
	     $nfield = 'tag';
  	     $selected = $tag;
	?>
	$jQ("#divtag").load("<?=$_SESSION['MaDAS_url']?>libs/ajax-select-multiple-simple.php?multiple=<?=$multiple?>&name=<?=$name?>&table=<?=$table?>&master=<?=$master?>&masterv=<?=$masterv?>&selected=<?=$selected?>&vfield=<?=$vfield?>&nfield=<?=$nfield?>&cols=<?=$cols?>");
	
	//node
	<?
	     $multiple = 'yes';
	     $cols	= 5;
	     $name = 'node';
	     $table = 'datasource_treefam_species_tree';
	     $vfield = 'TAX_ID';
	     $master = '';
	     $masterv = '';
	     $nfield = 'TAXNAME';
  	     $selected = $nodes;
	?>
	$jQ("#divnode").load("<?=$_SESSION['MaDAS_url']?>libs/ajax-select-multiple-simple.php?multiple=<?=$multiple?>&name=<?=$name?>&table=<?=$table?>&master=<?=$master?>&masterv=<?=$masterv?>&selected=<?=$selected?>&vfield=<?=$vfield?>&nfield=<?=$nfield?>&cols=<?=$cols?>");
	
	//order by
	<?
	     $multiple = '';
	     $name = 'order';
	     $table = 'datasource_treefam_species_tree';
	     $vfield = 'TAX_ID';
	     $master = '';
	     $masterv = '';
	     $nfield = 'TAXNAME';
  	     $selected = '';
	?>
	$jQ("#divorder").load("<?=$_SESSION['MaDAS_url']?>libs/ajax-select-multiple-simple.php?multiple=<?=$multiple?>&name=<?=$name?>&table=<?=$table?>&master=<?=$master?>&masterv=<?=$masterv?>&selected=<?=$selected?>&vfield=<?=$vfield?>&nfield=<?=$nfield?>&cols=<?=$cols?>");
	
	
	//groups
	<?
	     $multiple = '';
	     $name = 'groups';
	     $table = 'datasource_treefam_groups';
	     $vfield = 'id';
	     $master = '';
	     $masterv = '';
	     $nfield = 'group_name';
  	     $selected = $groups;
	?>
	$jQ("#divgroup").load("<?=$_SESSION['MaDAS_url']?>libs/ajax-select-multiple-simple.php?multiple=<?=$multiple?>&name=<?=$name?>&table=<?=$table?>&master=<?=$master?>&masterv=<?=$masterv?>&selected=<?=$selected?>&vfield=<?=$vfield?>&nfield=<?=$nfield?>&cols=<?=$cols?>");

	
	
	$jQ('#send').bind('click',function(e){
		$jQ('#evolution').load('<?=$plugin_path?>node-detail.php?sent=1&display='+$jQ('#display').val()+'&log='+$jQ('#log').val()+'&nodes='+$jQ('#node').val()+'&tag='+$jQ('#tag').val()+'&groups='+$jQ('#groups').val());
	})
</script>	
<? if ($sent) { ?>
<script type="text/javascript">
	

	$jQ(document).ready(function(){ 


	   <?php
	   		$ticks_x = '[';
	   		$pairs = array('AA','BB','CC','DD','EE','FF','00','11','22','33','44','55','66','77','88','99');
	   		//$colors = array('#109618','#3366CC','#DC3912','#FF9900','#990099','#AAAA11','#326DA7','#0099BB','#109616','#994499','#66AA00');
	   		$gserie = '';
	   		$i = 0;
	   		if ($series){
		   		foreach ($series as $k=>$v){
		   		
		   			$a = rand(0,15);
		   			$b = rand(0,15);
		   			$c = rand(0,15);
		   			echo 'var s'.$i.' = '.$v.";\n";
		   			$gserie .= '{ color:\'#'.$pairs[$a].$pairs[$b].$pairs[$c].'\',data: s'.$i.' , label: "'.$tax[$k].'"},';
		   			$i++;
		   		}
		   		$gserie = substr($gserie,0,strlen($geserie)-1);	
		   $ticks_x = substr($ticks_x,0,strlen($ticks_x)-1);	
		   $ticks_x .= ']'; 
	   ?>	
	   
	   			var options = {
		/*
					lines: { show: true}, */
					points: { show: true,radius:5 },

		            bars: { show: true},
		            selection: { mode: "xy" },
					grid: {
						hoverable: true,
						clickable: true,
						borderWidth: 0,
						backgroundColor:'#FFFFFF'
					},
					xaxis:{ticks:[]},
					legend: {
						show: true,
						position: "se"
				    },
				};	
	   
	   			$jQ.plot($jQ("#graphic-box"),[<?=$gserie?>],options);
	   
	   <? } ?>

	});
</script>
<? } ?>