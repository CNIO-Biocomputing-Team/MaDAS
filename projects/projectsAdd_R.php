<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.projects.php";
?>
<?php
	$p=new Project;
	if ($_SESSION['utype'] == 'DEMO'){
		echo '<div class="mesg">'.$_SESSION['demo_restricted'].'</div>';
		exit;
	}
	
	$userId=@$_SESSION['idusers'];
	$projectId=@$_POST['pid'];
	
	//new project
	if (!$projectId){
		$result = $p->createProject(@$_POST['name'],
							  @$_REQUEST['description'],
							  @$_REQUEST['category'],
							  @$_REQUEST['security'],
							  @$_REQUEST['notify_project'],
							  @$_REQUEST['notify_user'],
							  @$_REQUEST['notify_annotation']);
	}else{
		$result = $p->updateProject($projectId,
							 @$_POST['name'],
							  @$_REQUEST['description'],
							  @$_REQUEST['category'],
							  @$_REQUEST['security'],
							  1,
							  @$_REQUEST['notify_project'],
							  @$_REQUEST['notify_user'],
							  @$_REQUEST['notify_annotation']);
	}	
	echo '<div class="mesg">'.$result.'</div>';	
?>