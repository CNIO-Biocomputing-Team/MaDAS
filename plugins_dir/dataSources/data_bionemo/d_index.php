<?php 
	//requiered initializations
	session_start();
	ob_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.bionemo.php";
	include_once "ez_sql.php";
	include_once "lang_EN.php";
	
	$c 		= new Comodity;
	$bio	= new Bionemo;
	
	if (!$_POST['host'] or !$_POST['user'] or !$_POST['pass'] or !$_POST['database'] or !$_POST['version']){
		$c->runMesg($mesg['required_fields'],false);
		exit; 
	}
	
	if (!$pg = @pg_connect("host=".trim($_POST['host'])." dbname=".trim($_POST['database'])." user=".trim($_POST['user'])." password=".trim($_POST['pass']))){
		$c->runMesg($mesg['connection_failed'],false);
		exit;
	}
	
	$bio->copyDsns($_POST['version']);
	$c->runMesg('Done',false);
	
?>