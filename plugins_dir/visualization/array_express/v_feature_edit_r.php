<?
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	if ($_SESSION['name'] == 'Nobody'){
	
		echo $_SESSION['demo_restricted'];
		exit;
	}
	$table = 'das_commonserver_features';
	$key = 'iddas_commonserver_features';
	
	include('edit-r-general.php');
	
	if ($_REQUEST['x_value_note']){
	
		$strSQL = "INSERT INTO das_commonserver_notes 
						   SET iddas_commonserver_features = ".$lastInsertID.",
							   text = '".$c->preparesql($_REQUEST['x_value_note'])."',
							   created = now()";
							   
		$db->query($strSQL);	
	}
	
?>