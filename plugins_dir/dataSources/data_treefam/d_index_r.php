<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "lang_EN.php";
	
	//session
	$userId			= @$_SESSION['idusers'];
	$uemail         = @$_SESSION['email'];
	$pid			= @$_SESSION['current_project'];
	$plugin_path	= @$_SESSION['plugin_path'];
	$target_path    = @$_SESSION['MaDAS_home'].'/'.$_SESSION['uploads_path'];
	
	$c = new Comodity;
	$p = new Project;
	
	//request
	$genefile 	= @$_REQUEST['genefile'];
	$treefile 	= @$_REQUEST['treefile'];
	
	
	//check parameters
	$return = 0;

    if (!$treefile || !$genefile){
    	$c->runMesg($mesg['required_fields']);
    	$return = 1;
    }
	
	if ($return == 1){
		echo '&nbsp;<a onClick=$jQ(\'#projectsCanvas\').load(\'projects/myProjectManageSource.php?sid='.@$_SESSION['current_data_source'].'\')>Try again</a>';
		exit;	
	}
	

	
	if ($treefile && $genefile){
	
		//launch download
		$c->runMesg($mesg['downloading']);
		$rand = time();
		exec('./insert_treefam.sh '.$genefile.' tmp_genef_'.$rand.' '.$treefile.' tmp_treef_'.$rand.' '.$uemail.'  1>error_log 2>error_log &');
		
	}	

?>