<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.manage-annot.php";
	include_once "lang_EN.php";

	//session
	$userId = @$_SESSION['idusers'];
	$pid =	@$_SESSION['current_project'];
	$annotid = @$_REQUEST['annotid'];
	
	$c = new Comodity;
	$p = new Project;
	$r = new Manage_Annotations;
	
	
	//preparing for next step
	$plugin_path = 'plugins_dir/dataSources/data_manage-annot/';
	$_SESSION['plugin_path'] = $plugin_path;
	
	
        //delete annotation	
	if ($_REQUEST['delete']) {
	    $dsn = $r->deleteAnnotById($annotid);
	}    
?>
<div class="header1"><b>Manage Annotations</b></div>
<div id="f-annot-edit-box" class="pluginBox" style="height:100px;">
<?=$mesg['annot_deleted']?>
</div>