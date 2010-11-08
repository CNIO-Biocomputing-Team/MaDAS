<?php 
	//requiered initializations
	session_start();
	
	//avoid duplications
	if ($_SESSION['gff-launched'] == 1)
		exit;
		
	ob_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.load-gff.php";
	include_once "lang_EN.php";
	include 'FlashUploader_102/SolmetraUploader.php';
	
	$solmetraUploader = new SolmetraUploader();
	$solmetraUploader->gatherUploadedFiles();
	
	//session
	$_SESSION['gff-launched'] = 1;
	$userId			= @$_SESSION['idusers'];
	$uemail     	= @$_SESSION['email'];
	$pid			= @$_SESSION['current_project'];
	$plugin_path	= @$_SESSION['plugin_path'];
	$target_path    = 'tmp/';
	
	$c 		= new Comodity;
	$p 		= new Project;
	$gff	= new Load_Gff;
	
	//requests
    $id_dsn 		= $_REQUEST['id_dsn'];
    $mtype 			= $_REQUEST['mtype'];
    $description 	= $_REQUEST['description'];
	$file 			= @$_FILES['flashFile'];
	$remotefile 	= @$_REQUEST['remotefile'];
	
	//check parameters
	$return = 0;

	if (!$file['name'] && !$remotefile && $return == 0){
		$c->runMesg($mesg['require_file'],false);
		$return = 1;
	}
	
	if ($return == 1){
		echo '&nbsp;<a onClick=$jQ(\'#projectsCanvas\').load(\'projects/myProjectManageSource.php?sid='.@$_SESSION['current_data_source'].'\')>Try again</a>';
		exit;	
	}
	
	//uploaded file
	if ($file['name']){
	
		$c->runMesg($mesg['file_uploaded']);	
		$c->runMesg($mesg['processing']);
		$annot = $gff->storeAnnotation($pid,$userId,$id_dsn,$description);
		exec('./insertShort_gff.sh '.basename($file['tmp_name']).' '.$id_dsn.' '.$mtype.' '.$annot.' 1>error_log 2>error_log &');	
		
		$c->runMesg($mesg['file_processed']);
			
	//remote file	
	}elseif ($remotefile){
		
		//launch download
		$c->runMesg($mesg['downloading']);
		$annot = $gff->storeAnnotation($pid,$userId,$id_dsn,$description);
		exec('./insert_gff.sh '.$remotefile.' tmp_remotef_'.srand(time()).' '.$id_dsn.' '.$mtype.' '.$annot.' '.$uemail.'  1>error_log 2>error_log &');	
	}	
?>
