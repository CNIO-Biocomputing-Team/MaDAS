<?php 
	//requiered initializations
	session_start();
	ob_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.load-array.php";
	include_once "lang_EN.php";
	
	include 'FlashUploader_102/SolmetraUploader.php';
	
	$solmetraUploader = new SolmetraUploader();
	$solmetraUploader->gatherUploadedFiles();

	//session
	$userId			= @$_SESSION['idusers'];
	$uemail         = @$_SESSION['email'];
	$pid			= @$_SESSION['current_project'];
	$plugin_path	= @$_SESSION['plugin_path'];
	$target_path    = 'tmp/';
	
	$c 		= new Comodity;
	$p 		= new Project;
	$gene 	= new Load_Array;
	
	//requests
    $id_dsn = $_REQUEST['id_dsn'];
    $mtype = 2;
    $description = $_REQUEST['description'];
	$file = @$_FILES['geneFile'];

	
	//check parameters
	if (!$file['name'] or !$id_dsn){
		$c->runMesg($mesg['required_fields'],false);
		echo '&nbsp;<a onClick=$jQ(\'#projectsCanvas\').load(\'projects/myProjectManageSource.php?sid='.@$_SESSION['current_data_source'].'\')>Try again</a>';
		exit;
	}
	
	$c->runMesg($mesg['processing']);
	
	$annot = $gene->storeAnnotation($pid,$userId,$id_dsn,$description);
	exec('./insert_file.sh '.$file['tmp_name'].' '.$id_dsn.' '.$mtype.' '.$annot.' 1>error_log 2>error_log &');	
?>
