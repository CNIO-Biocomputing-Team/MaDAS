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
	include_once "class.set-reference.php";
	include_once "lang_EN.php";
	

	//session
	$userId			= @$_COOKIE['idusers'];
	$uemail         = @$_COOKIE['email'];
	$pid			= @$_SESSION['current_project'];
	$plugin_path	= @$_SESSION['plugin_path'];
	$target_path    = 'tmp/';
	
	$c = new Comodity;
	$p = new Project;
	$reference = new Set_Reference;
	
?>
<div class="header1"><b>Manage Reference</b></div>
<div class="pluginBox" style="height:100px;">
<?
	
	//requests
	$dsn = array();
	$dsn['idusers'] = $userId;
	$dsn['dsnid'] = @$_REQUEST['dsnid'];
	$dsn['dname'] = @$_REQUEST['dname'];
	$dsn['dmap_master'] = @$_REQUEST['dmap_master'];
	$dsn['version'] = @$_REQUEST['version'];
	$dsn['description'] = @$_REQUEST['description'];
	$dsn['mtype'] = @$_REQUEST['mtype'];
	if (preg_match("/on/i",@$_REQUEST['dinclude_seq']))
	
	
		$dsn['dinclude_seq'] = 1;
	else
		$dsn['dinclude_seq'] = 0;	
	
	$file = @$_FILES['file'];
	$rfile = @$_REQUEST['rfile'];
	
	//check parameters
	$return = 0;
	if (!$dsn['dname'] || !$dsn['dmap_master']){
		$c->runMesg($mesg['require_dsn']);
		$return = 1;
	}
	
	if ((!$dsn['version'] || !$dsn['description'] || !$dsn['mtype']) && $return == 0 ){
		$c->runMesg($mesg['required_fields']);
		$return = 1;
	}
	
	if ($return == 1){
// 		echo '<a onClick=\'javascript:$jQ("...").myProjectAddSource('.@$_SESSION['current_data_source'].')\'>Try again</a>';
		exit;	
	}
	
	
	//save new DSN data
	if ($dsn['dname']){
		$id_dsn = $reference->storeDSN($dsn,$pid);	
		$c->runMesg($mesg['dsn_created']);
	}
	
	if (!$id_dsn){
	   $c->runMesg($mesg['dsn_already_exists']);
	   exit;
	
	}
	
	
	
	
	//uploaded file
	if ($file['name']){
	

		$target_path .= basename( $file['name']);
		
		//upload failed
		if(!move_uploaded_file($file['tmp_name'], $target_path)) {
		
			$c->mesg($mesg['upload_failed'],false);
			echo '<a onClick=\'javascript:$jQ("...").myProjectAddSource('.$_SESSION['current_data_source'].')\'>Try again</a>';
			exit;
		}else{
			$c->runMesg($mesg['file_uploaded']);		
			$c->runMesg($mesg['processing']);
                        $reference->parseFile($target_path,$id_dsn,$dsn['mtype'],$dsn['version']);
                        $c->runMesg($mesg['file_processed']);
		}	
		
	//ftp file	
	}elseif ($rfile){
	
		//launch download
		$c->runMesg($mesg['downloading']);
		exec('./insert_fasta.sh '.$rfile.' tmp_f_'.srand(time()).' '.$id_dsn.' '.$dsn['mtype'].' '.$uemail.'  2>error_log &');
		
	}	
?>
</div>