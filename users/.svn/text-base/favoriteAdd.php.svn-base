<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.user.php";
	$u=new User;
	
	$uid = $userId=@$_SESSION['idusers'];
	$fid = @$_REQUEST['fid'];
	$type = @$_REQUEST['ftype'];
	
	if (!$_REQUEST['nofa'])
		echo $u->addFavorite($uid,$fid,$type);
	else
		echo $u->delFavorite($uid,$fid,$type);	
?>
