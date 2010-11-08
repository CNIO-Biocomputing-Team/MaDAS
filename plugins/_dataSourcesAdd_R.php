<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.plugins.php";
?>
<?php
	$c = new Comodity;
	$p = new Plugins;
	
	$plugin_dir = $c->UploadTgz($HTTP_POST_FILES['file'],$_SESSION['data_sources_dir']);

	//upload failed
	if (preg_match('/ERROR/',$plugin_dir)){
	
		 echo '<div class="mesg">'.$plugin_dir.'</div>';	 
	//uploaded OK	 
	}else{
		$result = $p->addDataSource(@$_REQUEST['name'],
									@$_REQUEST['description'],
									@$_REQUEST['das'],
									$plugin_dir);
 	    echo '<div class="mesg">'.$result.'</div>';							  
	}
?>