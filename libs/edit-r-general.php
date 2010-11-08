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
	if (@$_REQUEST['delete']){
		$strSQL = "DELETE FROM ".$table." WHERE ".$key."=".$_REQUEST['id'];
		$db->query($strSQL);
		echo '<div class="msg">Operation completed</div>';
		exit;
	}
	
	
	
	
	//añadir
	if (!@$_REQUEST['id'] != ''){
		if (@$_REQUEST['control_field']){
			$cf = str_replace('t_value_','',@$_REQUEST['control_field']); 
			$strSQL = "SELECT * from ".$table." WHERE ".$cf." = '".$_REQUEST[@$_REQUEST['control_field']]."'";
			$r = $db->get_results($strSQL);
			if ($r){
				echo '<div class="msg">Ya existe un elemento con '.$cf.' = '.$_REQUEST[@$_REQUEST['control_field']].'</div>';
				exit;
			}
				
		}
		$strSQL = 'INSERT INTO '.$table.' SET created = now(), ';
		
	//editar	
	}else		
		$strSQL = 'UPDATE '.$table.' SET ';
	
	
	foreach ($_REQUEST as $k=>$v){
	
		//texto
		if (preg_match("/t_value/i",$k)){
				$strSQL.=str_replace('t_value_','',$k)."='".$c->preparesql($v)."',";
		//numerico
		}else if (preg_match("/n_value/i",$k)){
				if ($v != '')
					$strSQL.=str_replace('n_value_','',$k)."=".$v.",";
		//fecha	
		}else if (preg_match("/f_value/i",$k)){
			if (preg_match("/dia/i",$k))
				$dia = $v;
			if (preg_match("/mes/i",$k))
				$mes = $v;	
			if (preg_match("/ano/i",$k))
				$ano = $v;	
			if ($dia != '' && $mes != '' && $ano != ''){	
				$fecha = $ano.'-'.$mes.'-'.$dia;
				$strSQL.=str_replace(array('f_value_','-dia','-mes','-ano'),'',$k)."='".$fecha."',";
			}
		}		
			
		
	}
	
	
	//checkboxes
	if (@$_REQUEST['checkboxes']){
	
		if (preg_match("/,/i",@$_REQUEST['checkboxes'])){
			
			$checkboxes = array();		
			$checkboxes = split(',',@$_REQUEST['checkboxes']);		
			foreach ($checkboxes as $ch){
				if (preg_match("/on/i",$_REQUEST[$ch]))
					$strSQL.=str_replace('c_value_','',$ch)."= 1,";
				else 	
					$strSQL.=str_replace('c_value_','',$ch)."= 0,";
			
			}
		}else{
			if (preg_match("/on/i",$_REQUEST[@$_REQUEST['checkboxes']]))
				$strSQL.=str_replace('c_value_','',@$_REQUEST['checkboxes'])."= 1,";
			else 	
				$strSQL.=str_replace('c_value_','',@$_REQUEST['checkboxes'])."= 0,";
		}
	
	
	}
	
	//ficheros
	foreach ($_FILES as $k=>$v){	
		
		if (preg_match("/file_value/i",$k)){
			
			$uploaddir = '../../uploads/';
			$uploadfile = $uploaddir . basename($_FILES[$k]['name']);
			
			if(move_uploaded_file($_FILES[$k]['tmp_name'], $uploadfile))
				$strSQL.=str_replace('file_value_','',$k)."='".$c->preparesql($_FILES[$k]['name'])."',";
		}
	}	
	
	
	$strSQL = substr($strSQL,0,strlen($strSQL)-1);	
	

	
	if(@$_REQUEST['id'] != '')
		$strSQL .= ' WHERE '.$key.' = '.@$_REQUEST['id'];
	
	
	
	
	$db->query($strSQL);
	
	$lastInsertID =  mysql_insert_id();
	
	echo '<div class="msg">Operation completed</div>';
?>
	
