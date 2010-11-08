<?php 
	//includes  clases
	include_once "../../../libs/ez_sql.php";
	include_once "../../../libs/class.comodity.php";
	include_once "../../../libs/class.user.php";
	include_once "../../../libs/class.projects.php";
	include_once "class.load-affymetrix.php";
	include_once "lang_EN.php";
	
	$c 		= new Comodity;
	$p 		= new Project;
	$chip 	= new Load_Affymetrix;
	
	
	$target_path 	= $_REQUEST['file'];
	$id_dsn 		= $_REQUEST['dsn'];
	$mtype			= $_REQUEST['mtype'];
	$annot			= $_REQUEST['annot'];
	
	$chip->parseFile($target_path,$id_dsn,$mtype,$annot);	
?>