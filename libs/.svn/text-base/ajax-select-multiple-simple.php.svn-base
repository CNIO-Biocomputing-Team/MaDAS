<?
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	
	$c = new Comodity;
	
	$multiple = @$_REQUEST['multiple'];
	$name = @$_GET['name'];
	$table = @$_REQUEST['table'];
	$master = @$_REQUEST['master'];
	$masterv = @$_REQUEST['masterv'];
	$vfield = @$_REQUEST['vfield'];
	$nfield = @$_REQUEST['nfield'];
	$selected = (@$_REQUEST['selected'])?@$_REQUEST['selected']:md5('nada');
	$aselected = explode(',',$selected);

	
	$query = (@$_REQUEST['query'])?@$_REQUEST['query']:'';
	$cols = (@$_REQUEST['cols'])?@$_REQUEST['cols']:6;

	$query =  str_replace('**','>=',base64_decode($query));


	if (!$table || !$nfield)
		exit;


	if (!$query){

		$strSQL = "SELECT * FROM ".$table;
		
		if ($master){
			if ($masterv == 'null')
				$strSQL .= " WHERE ".$master." is null ";
			else if ($masterv == 'not null')
				$strSQL .= " WHERE ".$master." is not null ";	
			else if ($masterv)
				$strSQL .= " WHERE ".$master." = ".$masterv;
			else
				$strSQL .= " WHERE ".$master." = '".md5('nada')."'";
		}	
			
			
		$strSQL .=	" ORDER BY ".$nfield;
	
	}else{
		$strSQL = $query;
	
	}
	

	$results = $db->get_results($strSQL);
	
	$flag_no_aplicable = false;

	
	
	
	if ($results){
	
	if ($multiple){
		echo '<select name="'.$name.'[]" id="'.$name.'" class="'.$class.'" multiple size="'.$cols.'">'."\n";
   	}else{
   		echo '<select name="'.$name.'" id="'.$name.'" class="'.$class.'">'."\n";
   	}
   		echo '<option value="">Please select</option>'."\n";
		foreach ($results as $re){
			
			$value = ($vfield)?$re->$vfield:$re->$nfield;
			
			echo '<option value="'.$value.'" ';
			if ($vfield && in_array($re->$vfield,$aselected))
				echo ' selected="selected" ';
			echo '>'.strtoupper($re->$nfield).'</option>'."\n";
			
		}
	echo('</select>');
	if ($multiple)
		echo '<p>*Ctrl+click to multiple selection</p>';	
		
	}
?>	

