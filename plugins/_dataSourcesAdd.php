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
	$user=$u->getUserById($userId);
?>
<div class="jqmdTC jqDrag">Upload a Data Source Plugin</div>
<div class="jqmdBC">
	<div class="jqmdMSG">
	<!-- content -->	
		<table height="100%">
			<tr>
				<td class="rightBorder"><img src="images/sources.png" border="0"/></td>
				<td valign="top" align="center">
					<div id="newSourceBox">
						<form id="newSourceForm" action="plugins/dataSourcesAdd_R.php" method="post">
							<table align="center" style="margin:10px;">
								<tbody>
								    <tr>
										<td class="option">Plugin file (tgz)*</td>
										<td  class="value">
											<input type="file" name="file" title="<br>You must upload the plugin" class="{required:true}" />
										</td>
									</tr>
									<tr>
										<td />
										<td class="value">
											<input type="submit" value="Upload" class="button" />&nbsp;<input type="reset" value="Clear" class="button" />
										</td>
									</tr>
								</tbody>
							</table>
						</form>
					</div>
				</td>
			</tr>
		</table>
	<!-- /content -->
	</div>
</div>
<input id="cimg" type="image" src="images/close.gif" class="jqmdX jqmClose" border="0" />
<script language="javascript">
$jQ(document).ready(function() { 
	$jQ("#newSourceForm").submit(function() {
		  return false; // cancel conventional submit
 	});
	$jQ('#newSourceForm').validate({
		submitHandler: function(form) { 
			$jQ(form).ajaxSubmit({
				target: '#newSourceBox'
			});
		}
			
	});	
});
</script>