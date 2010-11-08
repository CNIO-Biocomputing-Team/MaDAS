<?
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.madasmap.php";
	include_once "class.wms.php";
	include_once "class.paint.php";
	
	$com 	= new Comodity;
	$map 	= new Madasmap;
	$wms 	= new Wms;
	$paint 	= new Paint;
	$reader = new XMLReader();
	
	//session
	$plugin_path 	= @$_SESSION['plugin_path'];
	$userId 		= @$_SESSION['idusers'];
	$pid 			= @$_SESSION['current_project'];
	$current_dsn 	= @$_SESSION['current_dsn']; 
	$psegment 		= @$_SESSION['current_segment_name'];
	
	
	//get DAS Parameters
	$das_server    	= @$_REQUEST['das_server'];
	$sstart 		= round(@$_REQUEST['start']);
	$sstop 			= round(@$_REQUEST['stop']);
	$r				= @$_REQUEST['r'];
	$h				= @$_REQUEST['h'];
	
	$as_types 	= array();
	$pocket 	= array();
	
	
	//Corrections for bubble position
    if ($r != 1) { 
    	echo '<div style="margin-top:40px;">';
	} else {
		echo '<div style="margin-bottom:20px;">';
	} 
	

	list($as_types,$pocket) = $wms->getMaDASannotations($pid,$current_dsn,$psegment,$sstart,$sstop);
	$paint->paintFeatureDetails($as_types,$pocket,$h,$img_height);

	
	echo '</div>'
?>