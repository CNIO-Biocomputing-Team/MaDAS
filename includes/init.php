<?php
	//start session
	session_start();
	//parse config file and store variables in the user session
	if (!file_exists('config.php'))
		die('Unable to load config file (config.php)');
	else{	
		$lines = file('config.php');
		foreach ($lines as $line_num => $line){

			$line=str_replace(array("$","'",";"),"",$line);
			if (!ereg("(\?)|#",$line)){
			
				$l=explode('=',$line);
				$_SESSION[trim($l[0])]=trim($l[1]);
			}
		}	
	}
?>