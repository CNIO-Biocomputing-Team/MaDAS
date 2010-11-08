<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.user.php";
	include_once "class.projects.php";

	$u = new User;
	$p = new Project;
	
	$userId = @$_COOKIE['idusers'];
	$projectId=@$_GET['pid'];
	$user=$u->getUserById($userId);
	
	if ($projectId)	
		$project=$p->getProjectById($projectId);
		
?>
<table height="100%">
    <tr>
    	<td class="rightBorder"><img src="images/new_project.png" border="0"/></td>
    	<td valign="top" align="center">
    		<div id="newProjectBox">
    			<form id="newProjectForm" action="projects/projectsAdd_R.php" method="post">
    				<input type="hidden" name="pid" value="<?=$projectId?>" />
    				<table align="center">
    					<tbody>
    						<tr>
    							<td class="option">Name *</td>
    							<td  class="value">
    								<input name="name" id="name" type="text" style="width:300px;" <?php if ($projectId) echo 'value="'.$project->name.'"'?> title="<br>Please provide a project name" class="{required:true}" />
    							</td>
    						</tr>
    						<tr>
    							<td class="option">Description </td>
    							<td  class="value">
    								<textarea name="description" style="width:304px;height:100px;"><?php if ($projectId) echo $project->description;?></textarea>
    							</td>
    						</tr>
    						<tr>
    							<td class="option">Category *</td>
    							<td>
    								<select name="category" class="{required:true}" title="<br>Please provide a project category">
    									<option value="">Please select one</option>
    								<?php 
    									$strSQL="SELECT * FROM project_categories WHERE active = 1 ORDER BY name ";
    									$categories = $db->get_results($strSQL);
    									foreach ($categories as $ctg){
    										echo '<option value="'.$ctg->idproject_categories.'"';
    										if ($projectId) 
    											if ($project->idproject_categories == $ctg->idproject_categories)
    												echo ' selected ';
    										echo '>'.$ctg->name.'</option>';
    									}	
    								?>
    								</select>
    							</td>
    						</tr>
    						<tr>
    							<td class="option">Security *</td>
    							<td>
    								<select name="security" class="{required:true}">
    									<option value="PUBLIC" <?php if ($projectId and $project->security == 'PUBLIC') echo ' selected '?>>Public</option>
    									<option value="PRIVATE" <?php if ($projectId and $project->security == 'PRIVATE') echo ' selected '?>>Private</option>
    								</select>
    							</td>
    						</tr>
    						<tr>
    							<td class="option">Alert project leaders when a new user join the Project</td>
    							<td>
    								<input name="notify_user" type="checkbox" value="1" <?php if ($projectId && $project->notify_user == 1) echo ' checked="true" '; else if (!$projectId) echo ' checked="true" '; ?> />
    							</td>
    						</tr>
    						<tr>
    							<td class="option">Alert users when the project is created</td>
    							<td>
    								<input name="notify_project" type="checkbox" value="1" <?php if ($projectId && $project->notify_project == 1) echo 'checked="true"'; else if (!$projectId) echo 'checked="true"'; ?> />
    							</td>
    						</tr>
    						<tr>
    							<td class="option">Alert project leaders when a new annotation is submitted</td>
    							<td>
    								<input name="notify_annotation" type="checkbox" value="1" <?php if ($projectId && $project->notify_annotation == 1) echo 'checked="true"'; else if (!$projectId) echo 'checked="true"'; ?> />
    							</td>
    						</tr>
    						<tr>
    							<td />
    							<td class="value">
    								<input type="submit" value="Send" class="button" />&nbsp;<input type="reset" value="Clear" class="button" />
    							</td>
    						</tr>
    					</tbody>
    				</table>
    			</form>
    		</div>
    	</td>
    </tr>
</table>

<script language="javascript">
$jQ(document).ready(function() { 
	$jQ('#name').focus();
	$jQ('#newProjectForm').ajaxForm({target: '#newProjectBox'});
});
</script>