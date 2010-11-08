<?php 
	session_start();
	if (file_exists('includes/init.php'))
		include_once('includes/init.php'); 
	else 
		die('Unable to load init file (includes/init.php)');
	
	//includes  clases
	include_once "libs/ez_sql.php";
	include_once "libs/class.user.php";
	include_once "libs/class.projects.php";

	$u	= new User;
	$p 	= new Project;	

	
	//parameters
	$pkey = @$_GET['pkey'];
	$plugin = @$_GET['plugid'];
	
	if ($pkey){
		
		$project = $p->getProjectByKey($pkey);
		$_SESSION['gotopid'] = $project->idprojects;
	}else{
		$_SESSION['gotopid'] = 0;
		//$u->loginUser('demo@email.com','demo',0);
	}	
	
	
	//plugins parameters in the URL
	if ($plugin){
	
		$_SESSION['gotoplugin'] = $plugin;
		
		//madasmap
		if (@$_GET['map_entry_point'] and @$_GET['map_size']){
		
			$_SESSION['map_entry_point'] = @$_GET['map_entry_point'];
			$_SESSION['map_size'] = @$_GET['map_size'];
		
		}
	
	}else{
		$_SESSION['gotoplugin'] = 0;
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="verify-v1" content="D3EUXtLMvgrWZKEEaZ8N4vvOi4fIfWU91uDKRND32Sc=" /> 
	<title>MaDas</title>

	<link rel="stylesheet" type="text/css" href="css/general.css" />
	<link rel="stylesheet" type="text/css" href="css/jqModal.css" />
	<link rel="stylesheet" type="text/css" href="css/projects.css" />
    <link href="jscripts/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="jscripts/flexigrid/css/flexigrid/flexigrid.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="jscripts/jquery/css/ui-lightness/jquery-ui-1.7.3.custom.css" type="text/css" media="screen">
    <link rel="stylesheet" href="jscripts/smartlists.css" type="text/css" />
    <link rel="stylesheet" href="jscripts/jScrollPane.css" type="text/css" /> 

	<!-- jquery http://docs.jquery.com -->
	<script src="jscripts/jquery/js/jquery-1.3.2.min.js" type="text/javascript"></script>
	<script src="jscripts/jquery/js/jquery-ui-1.7.3.custom.min.js" type="text/javascript"></script>
	<script src="jscripts/jquery.form.js" type="text/javascript"></script>
	<script src="jscripts/flexigrid/flexigrid.js" type="text/javascript"></script>
	<script src="jscripts/jScrollPane.js" type="text/javascript"></script>
	<script src="jscripts/facebox/facebox.js" type="text/javascript"></script>
	
	<!-- <script src="jscripts/jqModal.js" type="text/javascript"></script>
	<script src="jscripts/validate/jquery.validate.pack.js" type="text/javascript"></script>
	<script src="jscripts/flot/jquery.flot.pack.js" type="text/javascript"></script>
	<script src="jscripts/validate/jquery.metadata.js" type="text/javascript"></script>
	
	<script src="jscripts/interface/interface.js" type="text/javascript"></script>
	<script src="jscripts/flexigrid/flexigrid.js" type="text/javascript"></script>
	<script src="jscripts/jquery.corner.js" type="text/javascript" ></script>
	<script src="jscripts/jquery.smartlists.js" type="text/javascript" ></script>

	 -->
	
	 
	<script src="home/jquery.home.js" type="text/javascript"></script>
	<script src="help/jquery.help.js" type="text/javascript"></script>
	<script src="users/jquery.users.js" type="text/javascript"></script>
	<script src="projects/jquery.projects.js" type="text/javascript"></script>
	<script src="plugins/jquery.plugins.js" type="text/javascript"></script>
	<script src="plugins_dir/visualization/common/jquery.search.js" type="text/javascript"></script>	
   
        
	<script type="text/javascript">
		 var $jQ = jQuery.noConflict();
	</script>
	<script type="text/javascript">
	 $jQ(document).ready(function(){
	 
	 		
	 		<?php
	 			//goto plug-in
	 			if ($project and $plugin){
	 		?>	
	 				$jQ(this).gotoPlugin(<?=$project->idprojects?>,<?=$plugin?>);
	 		<?
	 			//goto project
	 			}else if ($project){
	 		?>
	 			$jQ(this).gotoProject(<?=$project->idprojects?>);
	 
	 		<? } else { ?>
	 		
				$jQ(this).loadHome();
			
			<? } ?>
			
			$jQ("#mMenu").load("header/menu.php",{async: false});
			$jQ().ajaxSend(function(r,s){  
    				 $jQ("#contentLoading").css('visibility','visible'); 
    		});  
    			   
    		$jQ().ajaxComplete(function(r,s){  
    				 $jQ("#contentLoading").css('visibility','hidden');  
    				/*  $jQ(".scroll-pane").jScrollPane({scrollbarWidth:5, scrollbarMargin:1, showArrows:false}); */
    		});  

	 });
	</script>
</head>

<body>
	<div id="contentLoading" style="visibility:hidden;text-align:right;padding-right:100px"><span style="background-color:red;color:#FFFFFF;">Loading...</span></div>
	<table class="tfixed" align="center" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td style="border-bottom:1px solid #E0E0E0;">
				<img id="logo" src="images/madas_logo.png" style="position:relative;top:+30px;left:+100px;" />
			</td>
			<td id="mMenu" valign="bottom"></td> 
			<td rowspan="2" valign="top">
				<div id="rightSide" class="scroll-pane"></div>
			</td>
		</tr>
		<tr>
			<td valign="top" colspan="2">
				<div id="canvas">
				 <div id="pcanvas"></div>
				 <div id="mBody"></div>
				 <div id="loginBox"></div>
				 <div id="projects_c"></div>
				 <div id="things"></div>
				</div> 
			</td>
		</tr>
	</table>
	<!-- Google Analytics -->	
	<script type="text/javascript">
	$jQ(".scroll-pane").jScrollPane({scrollbarWidth:5, scrollbarMargin:10, showArrows:false});
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
	try {
	var pageTracker = _gat._getTracker("UA-2545734-5");
	pageTracker._trackPageview();
	} catch(err) {}</script>
</body>
</html>
