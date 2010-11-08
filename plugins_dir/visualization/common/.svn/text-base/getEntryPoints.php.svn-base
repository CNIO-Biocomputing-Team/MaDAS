<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.madasmap.php";

	//session
	$userId = @$_SESSION['idusers'];
	$pid =	@$_SESSION['current_project'];
	
	$c = new Comodity;
	$p = new Project;
	$map = new Madasmap;
	
	$dsnid = $_REQUEST['dsn'];
	
	if (!$dsnid){
		echo 'Please provide first a reference sequence';
		exit;
	}	

	$dsn = $map->getDsnById($dsnid);
	
	
	$url = $dsn->dmap_master.'/entry_points';
	$entry_point_search =  @$_REQUEST['entry_point'];
	
	
	//set dsn session
	$tmp = @explode('/',$dsn->dmap_master);
	$_SESSION['current_iddsn'] = $dsn->iddas_commonserver_dsns;
	$_SESSION['current_dsn'] = array_pop($tmp);
	$_SESSION['current_idmolecule_type'] = $dsn->dmolecule_type;

	$divsize = 100;
	if ($dsn->dmolecule_type != 1){
	    $xml = @simplexml_load_file($url);
	    $divsize = 500;
	}
	    
?>	
	<style>
		body{
			color:#333333;
		}
		.dark{
			width:300px;
			background-color:#E4E4E4;
		}
		.normal{
			width:300px;
			background-color:#FFFFFF;
		}
	</style>
	<script src="http://ubio.bioinfo.cnio.es/biotools/bws/bwsl.js" type="text/javascript"></script>
	<script type="text/JavaScript">
	
		function uniprot(mapmaster){
		
			var serv = new UniprotDas();
			$jQ('#entry_point').val($jQ('#protein').val());
			
			$jQ('#das_dsn').text(mapmaster);
			$jQ('#das_segment').text($jQ('#protein').val());
			serv.getSequence(function(data){
				var str = data.DASSEQUENCE.SEQUENCE;
				$jQ('#size').val(str.length);
			},$jQ('#protein').val());
		}
		
	</script>
	
    <body>
 	<div style="color:#E3554C">Processing URL: <?=$url?><br><br></div>
 	
 	
    <div style="height:<?=$divsize?>px;overflow:auto;">
	<table align="left">

<?php    
	if ($xml){	
		$i=1; $flag = 1;$count = 0;
		foreach ($xml->ENTRY_POINTS->SEGMENT as $segment){
				
				if ($entry_point_search)
					$flag = 0;
					
				if (preg_match("/".$entry_point_search."/i",$segment['id']))
					$flag = 1;
						
				if ($flag == 1){	
					$size=$segment['stop']-$segment['start'];
					if ($size <= 0)
						$size = 100000;
					echo '<tr><td><input type="radio" name="dsn" value="'.$segment['id'].'" onclick="$jQ(\'#entry_point\').val(\''.$segment['id'].'\');$jQ(\'#size\').val(\''.$size.'\');$jQ(\'#das_segment\').text(\'['.$segment['type'].' '.$segment['id'].']\');"></td><td ';
					if ($i)
						echo 'class="dark"';
					else 
						echo 'class="normal"';	
					echo ' align="left"><table align="left" width="760">';
					echo '<tr><td align="left" style="font-weight:bold;">'.$segment['type'].' '.$segment['id'].'</td></tr>';
					if ($size != 100000)
						echo '<tr><td align="left">'.$size.'</td></tr>';
					else
						echo '<tr><td align="left">Size not available assuming 10000pb</td></tr>';		
					echo '</table></td></tr>';
					$i=($i==0)?1:0;
					$count++;
				} 
		}
	
	}else{
	
		if ($dsn->dmolecule_type != 1){
			echo '<tr><td>Sorry but some error ocurred retriving the entry points. Please check the DAS query.</td></tr>';
		
		}else {
			echo '<tr><td colspan="2" style="padding-bottom:20px;">Please enter the Protein ACC (e.g. P04637) and then close this window.</td></tr>';
			echo '<tr><td><b>Protein ACC</b></td><td><input type="text" name="protein" id="protein" onchange="uniprot(\''.$dsn->map_master.'\')" /></td></tr>';
			
		}	
	}
?>
    </table>
    </div>
    </body>