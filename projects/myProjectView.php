<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.projects.php";
	
		
	$uid=@$_COOKIE['idusers'];
	$pid = $_GET['pid'];
	$p = new Project;

	$_SESSION['project_rol'] = '';
	setcookie("project_rol", '', time()+(60*60*24),'/');	
	
	$project = $p->getProjectById($pid);
	$members = $p->getProjectMembers($pid);
	$tm = count($members);
	
	$role = $p->getProjectRole($uid,$pid);

	if ($role)
		$_SESSION['project_rol'] = $role;
    	

?>
<table style="padding:5px;width:182px;">
	<tr><td><div class="headerc2rounded">Now working in</div></td></tr>
	<tr><td style="height:10px;">&nbsp;</td></tr>
	<tr>
		<td class="projectName">
			<?php 
				echo $project->name;
				if ($project->idusers == $uid){
					echo '&nbsp;<img src="images/Star.png" title="You are the Project Leader" />';
				}	
			?>	
		</td>
	</tr>
	<?php
		if ($project->public_data >0){
				echo '<tr><td class="projectDate">By: <span style="text-transform:capitalize">';
				if ($project->public_data == 2)
					echo '<a href="mailto:'.$project->email.'" title="mail to">'.$project->owner.'</a>';
				else 	
					echo '<span style="color:#000000;">'.$project->owner.'</span>';
				echo '</span></td></tr>';
				echo '<tr><td class="projectDate">At: <span style="color:#000000;">'.$project->company.'</span></td></tr>';
			}
	?>		
	<tr><td class="projectDate">Created: <span style="color:#000000;"><?=substr($project->created,0,10)?></span></td></tr>
	<tr><td class="projectDate">Category: <span style="color:#000000;"><?=strtolower($project->category)?></span></td></tr>
	<tr><td class="projectDate">Description: <br /><span style="color:#000000;"><?=$project->description?></span><br /><br /></td></tr>
	<tr><td class="projectDate"></td></tr>
	<tr>
		<td align="left" style="border-top:1px solid #E0E0E0" class="projectDate">
		<?php
			if ($role == 'PROJECT LEADER'){				
				//echo '<a href="#" onclick="$jQ(this).shareLink(\'Share this project\','.$pid.',0);"><img align="absmiddle" title="Share this project" src="images/mail_add.png" border="0"></a>&nbsp;';
				echo '<a href="#" onclick="$jQ(this).projectsEdit('.$pid.');"><img align="absmiddle" title="Edit project" src="images/document_pen.png" border="0"></a>&nbsp;';
				echo '<img align="absmiddle" title="Delete project" style="cursor:pointer;" src="images/entry_delete.png" onClick=\'javascript:$jQ(this).myProjectDelete()\'><a onClick=\'javascript:$jQ(this).myProjectDelete()\'></a>';
			}	
		?>
		</td>
	</tr>
</table>
<table style="padding:5px;margin-top:40px;width:182px;">	
	<tr><td><div class="headerc2rounded">Project members (<?=$tm?>)</div></td></tr>
	<?php
		$pubm = 0;
		if ($tm){
			foreach ($members as $m){
			
				if ($m->public_data >0 or $role == 'PROJECT LEADER'){
					echo '<tr><td class="projectDate">';
					
					if ($project->idusers == $uid){
						if ($m->iduser_status == 2)
							echo '<a href="#" onclick="$jQ(this).editMember('.$m->idproject_members.','.$pid.')"><img border="0" src="images/user_check.png" title="EDIT" alt="EDIT" align="absmiddle"   /></a> ';
						else
							echo '<a href="#" onclick="$jQ(this).editMember('.$m->idproject_members.','.$pid.')"><img border="0" src="images/user.png" title="EDIT" alt="EDIT" align="absmiddle"  /></a> ';
					} 
					
					if ($m->public_data == 2 or $role == 'PROJECT LEADER')
						echo '<a href="mailto:'.$m->email.'" title="mail to">'.$m->name.'</a>';
					else 	
						echo '<span style="color:#000000;">'.$m->name.'</span>';
					
						
						
					echo ' </td></tr>';
					$pubm ++;
				}
			}
			if ($pubm < $tm)
				echo '<tr><td class="projectDate">*Some members decided to keep private their contact information.</td></tr>';
		}	
	?>
</table>
<table style="padding:5px;margin-top:40px;width:182px;">	
	<tr><td><div class="headerc2rounded">Join similar Projects</div></td></tr>
	<tr><td class="projectDate">In category: <a onClick='javascript:$jQ(this).projectsList("pr.idproject_categories=<?=$project->idproject_categories?>")'><?=strtolower($project->category)?></a></td></tr>
	<tr><td class="projectDate">By: <span style="text-transform:capitalize"><a onClick='javascript:$jQ(this).projectsList("p.users_idusers=<?=$project->idusers?>")'><?=$project->owner?></a></span></td></tr>
	<tr><td class="projectDate">At: <a onClick='javascript:$jQ(this).projectsList("u.company=*<?=$project->company?>*")'><?=$project->company?></a></td></tr>
	<tr><td style="height:30px;">&nbsp;</td></tr>
	<tr><td class="projectDate"><< Back to <a onClick='javascript:$jQ(this).loadProjects()'>your projects</a></td></tr>
</table>