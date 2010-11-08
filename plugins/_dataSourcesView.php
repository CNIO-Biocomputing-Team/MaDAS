<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.user.php";
	include_once "class.plugins.php";

	$u = new User;
	$p = new Plugins;
	
	$userId=@$_SESSION['idusers'];
	$pid=$_REQUEST['pid'];
	
	$plugin=$p->getDataSourceById($pid);
?>
<div class="jqmdTC jqDrag">Plugin details</div>
<div class="jqmdBC">
	<div class="jqmdMSG">
	<div id="pluginDetails">
	<!-- content -->	
		<table height="100%" align="center" cellpadding="10">
			<tr>
				<td class="option">Name:</td>
				<td  class="value">
					<?=$plugin->name?>
				</td>
			</tr>
			<tr>
				<td class="option">Uploaded By:</td>
				<td class="value">
					<?php 
						if ($plugin->public_data >0){
							if ($plugin->public_data ==1)
								echo $plugin->user.' ('.$plugin->company.' )';
							else 
								echo '<a href="mailto:'.$plugin->email.'" title="Mail to">'.$plugin->user.'</a> ('.$plugin->company.' )';	
						}else{
							echo $_SESSION['information_not_available'];
						}	
							
					
					?>
				</td>
			</tr>
			<tr>
				<td class="option">Created:</td>
				<td class="value">
					<?=$plugin->created;?>
				</td>
			</tr>
			<tr>
				<td class="option">Supported DAS server:</td>
				<td class="value">
					<?=$plugin->das;?>
				</td>
			</tr>
			<tr>
				<td class="option">Description:</td>
				<td  class="value">
					<?=$plugin->description;?>
				</td>
			</tr>
		</table>
	</div>	
	<!-- /content -->
	</div>
</div>
<input id="cimg" type="image" src="images/close.gif" class="jqmdX jqmClose" border="0" />
