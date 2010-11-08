<?
	session_start();
	//includes  clases
	include_once "../../../libs/ez_sql.php";
	include_once "../../../libs/class.comodity.php";
	include_once "../../../libs/class.madasmap.php";
	include_once "../../../libs/class.wms.php";
	include_once "class.paint.php";

	$com 	= new Comodity;
	$map 	= new Madasmap;
	$wms 	= new Wms;
	$paint 	= new Paint;
	$reader = new XMLReader();
	
	//parameters
	$img_height		= @$_GET['iheight'];
	$project 		= @$_GET['project'];
	$dsn		 	= @$_GET['dsn'];
	$segment		= @$_GET['segment'];
	$size			= @$_GET['size'];
	$das_server    	= @$_GET['das_server'];
	$view_types  	= explode(',',@$_REQUEST['view_types']);
	
	//GetMap parameters
	$tile_width		= @$_GET['width'];
	$start 			= round(@$_REQUEST['start']);
	$stop 			= round(@$_REQUEST['stop']);
	$wzoom	        = $sstop-$sstart;

	//session
	$_SESSION['current_project']		= $roject;
	$_SESSION['current_dsn']			= $dsn;
	$_SESSION['current_segment_name']	= $segment;
	
	$features	= array();
	$types		= array();
	
	if ($sstart<0 or $sstop <0)
		exit;
		
	//Get types and features from external sources
	if ($das_server and $das_server  != 'undefined' and $das_server  != 'null'){
		
		$das = $map->getDasById($das_server);
		$types = $wms->getDAStypes($das->url,$segment);
		$features = $wms->getDASannotations($das->url,$segment,$size,$start,$stop);
	}
	//Get types and features from MaDAS	
	$types = array_values(array_unique(array_merge($types,$wms->getMaDAStypes($project,$dsn,$segment))));			
	$features = array_merge($features,$wms->getMaDASannotations($project,$dsn,$segment,$start,$stop));

	//create image
	$im  			= imagecreatetruecolor($tile_width,$img_height);
	$background 	= imagecolorallocate($im, 242, 242, 242);
	imagefill($im,0,0,$background);

	//Paint tile
	$paint->paintRuler($start,$stop,$tile_width,$img_height);
	$paint->paintFeatures($types,$features,$tile_width,$img_height,$start,$stop);

	$wms->outputHeader();
	imagepng($im);
	imagedestroy($im);
?>