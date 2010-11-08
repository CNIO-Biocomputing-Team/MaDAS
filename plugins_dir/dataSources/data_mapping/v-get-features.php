<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.mapping.php";
	include_once "lang_EN.php";

	//session
	$userId = @$_SESSION['idusers'];
	$pid =	@$_SESSION['current_project'];
	$annotid = @$_REQUEST['annotid'];
	
	$c = new Comodity;
	$p = new Project;
	$map = new Mapping;

	
	$results = $map->getFeatureNamesByAnnot($annotid);

	
	header('Content-type: text/plain');
	header('Content-Disposition: attachment; filename="identifiers.txt"');
	
	foreach ($results as $r){
		if ($r->id)
			echo $r->id."\n";
	}
  
?>