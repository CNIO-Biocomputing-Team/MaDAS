<?php 
	//includes  clases
	include_once "../../../libs/ez_sql.php";
	include_once "../../../libs/class.comodity.php";
	include_once "../../../libs/class.user.php";
	include_once "../../../libs/class.projects.php";
	include_once "class.load-array.php";
	include_once "lang_EN.php";
	
	$c 		= new Comodity;
	$p 		= new Project;
	$gene 	= new Load_Array;
	
	
	$target_path 	= $_REQUEST['file'];
	$id_dsn 		= $_REQUEST['dsn'];
	$mtype			= $_REQUEST['mtype'];
	$annot			= $_REQUEST['annot'];
	
	$gene->parseFile($target_path,$id_dsn,$mtype,$annot);	
?>