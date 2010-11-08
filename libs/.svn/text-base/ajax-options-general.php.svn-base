<?
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	
	
	$c = new Comodity;
	
	$table = @$_REQUEST['table'];
	$master = @$_REQUEST['master'];
	$masterv = @$_REQUEST['masterv'];
	$vfield = @$_REQUEST['vfield'];
	$nfield = @$_REQUEST['nfield'];
	$selected = (@$_REQUEST['selected'])?@$_REQUEST['selected']:md5('nada');

	if (!$table || !$nfield)
		exit;


	$strSQL = "SELECT * FROM ".$table;
	
	if ($master){
		if ($masterv)
			$strSQL .= " WHERE ".$master." = ".$masterv;
		else
			$strSQL .= " WHERE ".$master." = '".md5('nada')."'";
	}	
		
		
	$strSQL .=	" ORDER BY ".$nfield;

	$results = $db->get_results($strSQL);
	
	echo '<option value="">Select one</option>';
	
	$flag_no_aplicable = false;
	
	if ($results){
		foreach ($results as $re){
			
			$value = ($vfield)?$re->$vfield:$re->$nfield;
			
			echo '<option value="'.$value.'" ';
			if (($vfield && $re->$vfield == $selected) or ($nfield && $re->$nfield == $selected))
				echo ' selected="selected" ';
			echo '>'.strtoupper($re->$nfield).'</option>'."\n";
			
			if ($vfield && $re->$vfield == 1)
				$flag_no_aplicable = true;
		}
	}	

?>	
