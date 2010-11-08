<?
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);

	$table = 'das_commonserver_das_tracks';
	$key = 'iddas_commonserver_das_tracks';
	

	
	if ($_REQUEST['delete']){
		
		echo '<div class="header1"><b>Manage Annotations</b></div>';
		echo '<div id="f-annot-edit-box" class="pluginBox" style="height:100px;">';
		include('edit-r-general.php');
		echo '</div>';
		$strSQL = "DELETE FROM das_commonserver_das_tracks WHERE iddas_commonserver_das_tracks=".$_REQUEST['id'];
		$db->query($strSQL);
	
	}else{
		include('edit-r-general.php');
	}
?>