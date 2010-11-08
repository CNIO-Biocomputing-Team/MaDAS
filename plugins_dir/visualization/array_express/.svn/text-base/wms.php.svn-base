<?
	session_start();
	ob_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	$time_start = microtime(true);
	
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
	
	//Session
	$plugin_path 	= @$_SESSION['plugin_path'];
	$userId 		= @$_SESSION['idusers'];
	$pid 			= @$_SESSION['current_project'];
	$current_dsn 	= @$_SESSION['current_dsn']; 
	
	$as_types = array();
	if ($s_types and !$_REQUEST['view_types'])
		$as_types = explode(',',$s_types);
	
	
	//GetMap parameters
	$version 		= @$_REQUEST['VERSION'];		//m
	$request		= @$_REQUEST['REQUEST'];		//m
	$layers			= @$_REQUEST['LAYERS'];			//m
	$img_width		= @$_REQUEST['width'];			//m
	$img_height		= 530;
	
	$sstart 		= round(@$_REQUEST['start']);
	$sstop 			= round(@$_REQUEST['stop']);
	
	$das_server    	= @$_REQUEST['das_server'];
	$psegment       = @$_REQUEST['chr'];
	$psize			= @$_REQUEST['size'];
	$qvalue			= @$_REQUEST['qvalue'];
	
	$view_types  	= explode(',',@$_REQUEST['view_types']);
	$wzoom	        = $sstop-$sstart;
	
	if ($sstart<0 or $sstop <0)
		exit;

	//create image
	$im  			= imagecreatetruecolor($img_width,$img_height);
	$background 	= imagecolorallocate($im, 242, 242, 242);
	imagefill  ($im,0,0,$background);
	
	
	//External sources
	if ($das_server  != 'undefined' and $das_server  != 'null'){
		
		$das = $map->getDasById($das_server);
		list($as_types,$pocket) = $wms->getDASannotations($das->url,$psegment,$sstart,$sstop);
		
	//MaDAS
	}else{
		
		list($as_types,$pocket) = $wms->getMaDASannotations($pid,$current_dsn,$psegment,$sstart,$sstop);
		
	}
/*
	
	echo microtime(true)-$time_start.'<br>';
	$time_start = microtime(true);
*/
	
	//Paint tile
	$paint->paintRuler($sstart,$sstop,$img_width,$img_height);

	if ($as_types)
		$paint->paintFeatures($as_types,$pocket,$img_width,$img_height,$sstart,$sstop,$qvalue);

/* 	echo microtime(true)-$time_start.'<br>'; */
	imagepng($im);
	imagedestroy($im);
	
	$wms->outputHeader();
	ob_end_flush();
?>