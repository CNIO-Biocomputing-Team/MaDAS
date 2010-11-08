<?
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);

	$table = 'das_commonserver_annotations';
	$key = 'iddas_commonserver_annotations';
	
	include('edit-r-general.php');
?>