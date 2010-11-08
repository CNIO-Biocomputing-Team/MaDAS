<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.load-affymetrix.php";

	//session
	$userId = @$_SESSION['idusers'];
	$pid =	@$_SESSION['current_project'];
	
	//preparing for next step
	$plugin_path = 'plugins_dir/dataSources/data_load-affymetrix/';
	$_SESSION['plugin_path'] = $plugin_path;
	
	$c = new Comodity;
	$p = new Project;
	$gene = new Load_Affymetrix;
?>
<script type="text/javascript" src="libs/FlashUploader_102/SolmetraUploader.js"></script>
<div class="header1"><b>Load Affymetrix</b></div>
<div class="pluginBox" style="height:300px;">
	<div id="step0" />
	<div id="step1">
		<form id="affymetrixForm" name="affymetrixForm" action="<?=$plugin_path?>d_index.php" method="post" enctype="multipart/form-data">
		    
		    <!-- Hidden form fields are used by Uploader JavaScript to control its behaviour -->
			<input type="hidden" name="solmetraUploaderInstance" value="file1" />
			<input type="hidden" id="solmetraUploaderData_file1" name="solmetraUploaderData[file1]" value="affymetrix" /> <!-- set value to form field name -->
			<input type="hidden" id="solmetraUploaderHijack_file1" value="y" /> <!-- set value to "y" if you want to "hijack" HTML form -->
			<input type="hidden" id="solmetraUploaderRequired_file1" value="y" /> <!-- set value to "y" to prevent form submission if this one is not set -->
	 
	
		    <input id="id_dsn" name="id_dsn" type="hidden" value="" />
			
			<table align="left">
				<tbody>
	              <tr>
	                <td class="option">1) Select your reference Sequence *</td>
	                <td  class="f_value" style="padding-bottom:10px;">
	                  <table cellspacing="0" border="0">
	                          <tr>
	                          <td style="background:url('<?=$plugin_path?>img/square.png') no-repeat;padding-left:15px;width:90px;height:86px;"><a href="<?=$plugin_path?>getDsn.php?url=<?=$_SESSION['MaDAS_url']?>plugins_dir/dasServers/das_common-server/das.php/dsn" class="face"><img src="<?=$plugin_path?>/img/madas.jpg" border="0"></a></td>
	                          <td><a href="<?=$plugin_path?>getDsn.php?url=<?=$_SESSION['MaDAS_url']?>/plugins_dir/dasServers/das_common-server/das.php/dsn" class="face">MaDAS</a></td>
	                          </tr>
	                  </table>
	                  <span id="dsn_name"></span>
	                  </td>
	              </tr>
	              <tr>
	                <td class="option">2) Type an optional description</td>
	                <td  class="f_value">
	                  <textarea name="description" style="width:300px;height:80px;"></textarea>    
	                </td>
	              </tr>
	              <tr>
	                   <td class="option">3) Upload your Affymetrix file *</td>
	                   <td  class="value" style="padding-bottom:10px;padding-top:10px;">
	                          <div id="solmetraUploaderPlaceholder_file1">
	                          </div>
	                   </td>
	                </tr>
	                <tr>
	                    <td />
	                    <td class="f_value">
	                           <input type="submit" value="Upload" class="button" />&nbsp;<input type="reset" value="Clear" class="button" />
	                    </td>
	                </tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
<!-- Here we create main object and set config parameters -->
<script type="text/javascript">
<!--
   var so = new SWFObject("libs/FlashUploader_102/uploader.swf", "solmetraUploaderMovie_file1", "500", "50", "8", "#ffffff");
   so.useExpressInstall("expressinstall.swf");
   so.addParam("allowScriptAccess", "always");
   so.addParam("allowFullScreen", "false");
   so.addVariable("language", "en");                     	// [optional] language to use for textual prompts
   so.addVariable("baseurl", "libs/FlashUploader_102/"); 	// [optional] will be applied to all eternal calls except uploadurl
   so.addVariable("uploadurl", "upload.php");            	// [optional] an url to post files to; relative to uploader.swf
   so.addVariable("config", "uploader.xml");             	// [optional] path to front-end configuration file
   so.addVariable("instance", "file1");          			// instance id - should be unique
   so.addVariable("allowed", "");
   so.addVariable("disallowed", "php,php3,php4,php5");
   so.addVariable("verifyupload", "true");
   so.addVariable("configXml", "");
   so.addVariable("maxsize", "100000000");
   so.addVariable("hijackForm", "yes");
   so.addVariable("externalErrorHandler", "SolmetraUploader.broadcastError");
   so.addVariable("externalEventHandler", "SolmetraUploader.broadcastEvent");
   so.write("solmetraUploaderPlaceholder_file1");
   solmetraUploaderMovie_file1 = document.getElementById("solmetraUploaderMovie_file1");
//-->
</script>
<script language="javascript">
	$jQ(document).ready(function() { 
		$jQ('.face').facebox();
	});
</script>
