<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.user.php";

	$u=new User;
	$result=$u->loginUser(@$_POST['email'],@$_POST['passw'],@$_REQUEST['rememberme']);	
	echo '<div class="mesg">'.$result.'</div>';
?>
