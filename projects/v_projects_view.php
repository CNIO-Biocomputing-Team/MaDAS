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
	
	$userId=@$_SESSION['idusers'];
	$pid=$_REQUEST['pid'];
	
	$user=$u->getUserById($userId);
	$project=$p->getProjectById($pid);
?>
<div id="projectDetails">
<table height="100%" align="center" cellpadding="10">
    <tr>
    	<td class="option">Name:</td>
    	<td  class="value">
    		<?=$project->name?>
    	</td>
    </tr>
    <tr>
    	<td class="option">Created:</td>
    	<td class="value">
    		<?=$project->created;?>
    	</td>
    </tr>
    <tr>
    	<td class="option">Description:</td>
    	<td  class="value">
    		<?=$project->description;?>
    	</td>
    </tr>
    <tr>
    	<td class="option">Category:</td>
    	<td class="value">
    		<?=$project->category;?>
    	</td>
    </tr>
    <tr>
    	<td class="option">Security:</td>
    	<td class="value">
    		<?=$project->security;?>
    	</td>
    </tr>
    <tr>
    	<td colspan="2"><input type="button" class="button" value="Join Project" onclick='javascript:$jQ(this).joinToProj(<?=$userId?>,<?=$pid?>,"<?=$project->security;?>");$jQ("#rightSide").load("projects/myProjectsList.php");' /></td>
    </tr>
</table>
</div>


