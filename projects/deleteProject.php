<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.projects.php";
	
	$pid=$_SESSION['current_project'];
	echo $pid;
	$p=new Project;
	$p->deleteProject($pid);
?>


