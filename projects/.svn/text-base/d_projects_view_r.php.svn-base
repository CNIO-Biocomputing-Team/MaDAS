<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.phpmailer.php";
	include_once "html2text.php";

	$u = new User;
	$p = new Project;
	$mail = new phpmailer();

	
	
	$userId=@$_SESSION['idusers'];
	$pid=$_REQUEST['pid'];
	$security=$_REQUEST['security'];
	
	$result=$p->joinToProject($userId,$pid,$security); 
	echo '<div style="padding:20px;">';
	echo $result;
	echo '</div>';
?>