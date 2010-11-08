<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";

	$userId=@$_SESSION['idusers'];
	$p = new Project;

	$pid =			$_REQUEST['pid'];
	$das_id =		$_REQUEST['das_id'];
	
	$result = $p->setProjectDasServer($pid,$das_id);
?>
$jQ("#projectsCanvas").load("projects/myProjectWork.php?pid=<?=$_REQUEST['pid']?>&msg=<?=base64_encode($result)?>");
