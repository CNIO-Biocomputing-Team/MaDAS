<?
	session_start();
	//includes  clases
	include_once "../../../libs/ez_sql.php";
	include_once "../../../libs/class.comodity.php";
	include_once "../../../libs/class.madasmap.php";
	include_once "../../../libs/class.wms.php";
	include_once "class.paint.php";
	
	$com 		= new Comodity;
	$map 		= new Madasmap;
	$wms 		= new Wms;
	$paint 		= new Paint;
	$reader 	= new XMLReader();
	
	//get parameters
	$project		= @$_GET['project'];
	$dsn 			= @$_GET['dsn'];
	$das_server    	= @$_GET['das_server'];
	$segment		= @$_GET['segment'];
	$size			= @$_GET['size'];
	$start 			= round(@$_GET['start']);
	$stop 			= round(@$_GET['stop']);
	$typePos		= @$_REQUEST['h'];
		
	$types		= array();	
	$features	= array();
	//Get types and features from external sources
	if ($das_server and $das_server  != 'undefined' and $das_server  != 'null'){
		
		$das = $map->getDasById($das_server);
		$types = $wms->getDAStypes($das->url,$segment);
		$features = $wms->getDASannotations($das->url,$segment,$size,$start,$stop);
	}
	//Get types and features from MaDAS	
	$types = array_values(array_unique(array_merge($types,$wms->getMaDAStypes($project,$dsn,$segment))));			
	$features = array_merge($features,$wms->getMaDASannotations($project,$dsn,$segment,$start,$stop));	
	if (count($features)){
		header('Content-type: text/xml'); 
		$paint->paintFeatureDetails($types,$features,$typePos,$start,$stop);
	}
?>