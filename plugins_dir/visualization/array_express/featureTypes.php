<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.madasmap.php";
	

	//session
	$userId = @$_SESSION['idusers'];
	$pid =	@$_SESSION['current_project'];
	
	//preparing for next step
	$plugin_path = 'plugins_dir/visualization/madasmap/';
	$_SESSION['plugin_path'] = $plugin_path;
	
	$c = new Comodity;
	$p = new Project;
	$m = new Madasmap;
	
	$types = $m->getFeatureTypes();
	
	$b  = '<input type="hidden" id="ft" name="ft" value="1">';
	$b .= '<div style="border:1px solid #cac1b4;width:1000px;background-color:white;margin-left:20px;opacity:0.8" align="left"><table cellspacing="20" border="0"><tr>';
	
	$i = 0;
	foreach($types as $t){
	
	 if ($i % 6 == 0 && $i != 0)
	   $b .= '</tr><tr>';
	 
	 $b .= '<td style="color:#000000;"><input id="t_'.$t->tname.'" type="checkbox" onclick="$jQ(\'.'.$t->tname.'\').toggle(\'slide\',{direction: \'down\'},800);" checked="checked"> '.$t->tname.'</td>';
	 
	 $i++;
	}
	
	$b.= '</tr></table></div>';
	
	echo $b;
?>
