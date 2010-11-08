<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.user.php";
?>
<?php

/*
	if ($_SESSION['utype'] == 'DEMO'){
		echo '<div class="mesg">'.$_SESSION['demo_restricted'].'</div>';
		exit;
	}
*/

	$u=new User;
	
	$userId=@$_COOKIE['idusers'];
	
	//new user
	if (!$userId){
		$result = $u->createUser(@$_POST['uname'],
							  @$_POST['company'],
							  @$_POST['address'],
							  @$_POST['city'],
							  @$_POST['country'],
							  @$_POST['email'],
							  @$_POST['phone'],
							  @$_POST['fax'],
							  @$_POST['website'],
							  @$_POST['passw'],
							  @$_POST['public_data'],
							  @$_POST['notify_projects'],
							  @$_POST['notify_users'],
							  @$_POST['notify_annotations'],
							  @$_POST['notify_plugins']);
	}else{
		$result = $u->updateUser($userId,
							  @$_POST['uname'],
							  @$_POST['company'],
							  @$_POST['address'],
							  @$_POST['city'],
							  @$_POST['country'],
							  @$_POST['email'],
							  @$_POST['phone'],
							  @$_POST['fax'],
							  @$_POST['website'],
							  @$_POST['passw'],
							  @$_POST['public_data'],
							  @$_POST['notify_projects'],
							  @$_POST['notify_users'],
							  @$_POST['notify_annotations'],
							  @$_POST['notify_plugins']);
	}	
	echo '<div class="mesg">'.$result.'</div>';	
?>
