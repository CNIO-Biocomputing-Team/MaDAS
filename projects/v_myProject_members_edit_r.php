<?
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);

	$table = 'project_members';
	$key = 'idproject_members';
	
	include('edit-r-general.php');
?>