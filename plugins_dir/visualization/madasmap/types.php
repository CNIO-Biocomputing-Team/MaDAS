<?
	//includes  clases
	include_once "../../../libs/ez_sql.php";
	include_once "../../../libs/class.comodity.php";
	include_once "../../../libs/class.madasmap.php";
	include_once "../../../libs/class.wms.php";
	include_once "class.paint.php";
	
	$com 		= new Comodity;
	$map	 	= new Madasmap;
	$wms 		= new Wms;
	$paint 		= new Paint;
	$reader 	= new XMLReader();
	
	//get parameters
	$project		= @$_GET['project'];
	$dsn 			= @$_GET['dsn'];
	$das_server    	= @$_GET['das_server'];
	$segment		= @$_GET['segment'];
	
	$types		= array();
	//Get types from external sources
	if ($das_server and $das_server  != 'undefined' and $das_server  != 'null'){
		
		$das = $map->getDasById($das_server);
		$types = $wms->getDAStypes($das->url,$segment);
	}
	//Get types from MaDAS	
	$types = array_values(array_unique(array_merge($types,$wms->getMaDAStypes($project,$dsn,$segment))));
	header('Content-type: text/xml'); 
	echo '<types>';			
	foreach ($types as $t){
		echo '<type>'.ucfirst(strtolower(trim(str_replace('_',' ',$t)))).'</type>'."\n";
	}
	echo '</types>';
?>