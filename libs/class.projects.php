<?php
	###############################################################################
	#                      class.projects.php -  description                      #
	#                             -------------------                             #   
	#    begin                : Wed Jul 25 14:18:02 CEST 2007                     # 
	#    copyright            : (C) 2007 by Victor de la Torre                    # 
	#    email                : victor@madas.es                          # 
	###############################################################################
	
	###############################################################################
	#                                                                             #
	#   This file is part of MaDas                                                #
	#                                                                             #
	#   MaDas is free software; you can redistribute it and/or modify             #
	#   it under the terms of the GNU General Public License as published by      #
	#   the Free Software Foundation; either version 2 of the License, or         #
	#   (at your option) any later version.                                       #
	#                                                                             #
	#   MaDas is distributed in the hope that it will be useful,                  #
	#   but WITHOUT ANY WARRANTY; without even the implied warranty of            #
	#   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             #
	#   GNU General Public License for more details.                              #
	#                                                                             #
	#   You should have received a copy of the GNU General Public License         #
	#   along with MaDas; if not, write to the Free Software                      #
	#   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA #
	#                                                                             #
	###############################################################################

class Project
{
	/* cleandbf */
	function cleandbf($value) {
		
		return trim($value);
	}
	
	/* get projects */
	function getProjects($page,$start,$limit,$sidx,$sord,$wh,$uid) {
		
		global $db;
		$strSQL="SELECT p.idprojects, p.project_categories_idproject_categories, p.users_idusers, p.name, p.description, p.notify_project, p.notify_user, p.notify_annotation, p.security, p.active, p.configured, p.created, p.modified, pr.name as category, pr.idproject_categories, u.name as user, u.public_data, u.company, count(pm.users_idusers) as members
				   FROM project_categories pr, users u , projects p
				   LEFT JOIN project_members pm ON (p.idprojects = pm.projects_idprojects)
				  WHERE p.project_categories_idproject_categories=pr.idproject_categories 
				    AND u.idusers=p.users_idusers ".$wh."
				    AND p.security = 'PUBLIC' 
			   GROUP BY p.idprojects 
			   ORDER BY ".$sidx." ".$sord." 
			      LIMIT ".$start.", ".$limit;
		
		$projects = $db->get_results($strSQL);
		return $projects;
	}
	
	/* get projects count*/
	function getProjectsCount() {
		
		global $db;
		$strSQL="SELECT count(p.idprojects) as total
				   FROM projects p";

		
		$count = $db->get_row($strSQL);
		return $count;
	}	
	
	
	/* create project */
	function createProject ($name,
						$description,
						$category,
						$security,
						$notify_project,
						$notify_user,
						$notify_annotation) {
		
		global $db,$_SESSION;
		
		//defaults
		$user=$_SESSION['idusers'];
		$active=1;
		$notify_project=($notify_project)?1:0;
		$notify_user=($notify_user)?1:0;
		$notify_annotation=($notify_annotation)?1:0;
		$md5 = md5(uniqid(rand(),true));	
		
		//check for empty values
		/*if (!$name or !$company or !$email or !$passw)
			return $_SESSION['required_fields'];*/
		
		
		//check for duplicated project name
		$strSQL="SELECT idprojects FROM projects p WHERE p.name='".$name."'";
		$r = $db->get_row($strSQL);
		if ($r)
			return $_SESSION['duplicated_project_name'];
	
		//insert project
		$strSQL="INSERT INTO projects
					     SET users_idusers=".$this->cleandbf($user).",
						 	 name='".$this->cleandbf($name)."',
							 description='".$this->cleandbf($description)."',
							 project_categories_idproject_categories='".$this->cleandbf($category)."',
							 security='".$this->cleandbf($security)."',
							 notify_project=".$this->cleandbf($notify_project).",
							 notify_user=".$this->cleandbf($notify_user).",
							 notify_annotation=".$this->cleandbf($notify_annotation).",
							 active=".$this->cleandbf($active).",
							 pkey = '".$md5."',
							 created=now(),
							 modified=now()";
							 
		$db->query($strSQL);	
		
		//$debug=$strSQL;
		
		$strSQL="SELECT max(p.idprojects) as pid FROM projects p";				 
		$r = $db->get_row($strSQL);
		$pid = $r->pid;
							 
		//update user activity
		$strSQL= "INSERT INTO user_activities
				     SET projects_idprojects=".$pid.",
					 	 users_idusers=".$user.",
					 	 time=now()";					 
		$db->query($strSQL);
		
		$_SESSION['idprojects'] = $pid;
		
		return $_SESSION['project_created'];
	}
	
	
	/* update project */
	function updateProject($idprojects,
						$name,
						$description,
						$category,
						$security,
						$active,
						$notify_project,
						$notify_user,
						$notify_annotation) {
		
		global $db,$_SESSION;
		
		//defaults
		$user=$_SESSION['idusers'];
		$active=($active)?$active:1;
		$notify_project=($notify_project)?1:0;
		$notify_user=($notify_user)?1:0;
		$notify_annotation=($notify_annotation)?1:0;
		
		//check for empty values
		/*if (!$name or !$company or !$email or !$passw)
			return $_SESSION['required_fields'];*/
		
		
		//update projects
		 $strSQL="UPDATE projects
					 SET users_idusers=".$this->cleandbf($user).",
					 	 name='".$this->cleandbf($name)."',
						 description='".$this->cleandbf($description)."',
						 project_categories_idproject_categories='".$this->cleandbf($category)."',
						 security='".$this->cleandbf($security)."',
						 notify_project=".$this->cleandbf($notify_project).",
						 notify_user=".$this->cleandbf($notify_user).",
						 notify_annotation=".$this->cleandbf($notify_annotation).",
						 active=".$this->cleandbf($active).",
						 created=now(),
						 modified=now()
					  WHERE idprojects=".$idprojects;
		
							 
							 
		$db->query($strSQL);	
		
		//update user activity
		$strSQL= "INSERT INTO user_activities
				     SET projects_idprojects=".$idprojects.",
					 	 users_idusers=".$user.",
					 	 time=now()";					 
		$db->query($strSQL);
		
		$_SESSION['idprojects'] = $idprojects;
		
		return $_SESSION['project_updated'];
	}
	
	/* update project */
	function setProjectDasServer($idprojects,$das_id) {
		
		global $db,$_SESSION;
		
		//update projects
		 $strSQL="UPDATE projects
					 SET das_servers_iddas_servers=".$das_id.",
						 configured = 1,
					 	 modified=now()
					  WHERE idprojects=".$idprojects;
		
							 
							 
		$db->query($strSQL);	
		$_SESSION['idprojects'] = $idprojects;
		
		return $_SESSION['project_configured'];
	}
	
	
	/*delete project */
	function deleteProject($pid){
		
		global $db;
		$strSQL= "DELETE FROM projects WHERE idprojects=".$pid;
		$db->query($strSQL);
		return $_SESSION['project_deleted'];
	}
	
	/* get my projects */
	function getMyProjects($uid) {
		
		global $db;
		//I'm the owner
		$strSQL="SELECT p.idprojects, p.project_categories_idproject_categories, p.users_idusers as owner, p.name, p.description, p.notify_project, p.notify_user, p.notify_annotation, p.security, p.active, p.configured, p.created, p.modified, pr.name as category
				   FROM projects p, project_categories pr
				  WHERE p.project_categories_idproject_categories=pr.idproject_categories
				    AND p.users_idusers=".$uid;

		
		$projects = $db->get_results($strSQL);
		
		//I'm a project member
		$strSQL="SELECT p.idprojects, p.project_categories_idproject_categories, p.users_idusers as owner, p.name, p.description, p.notify_project, p.notify_user, p.notify_annotation, p.security, p.active, p.configured, p.created, p.modified, pr.name as category
				   FROM projects p, project_categories pr, project_members pm
				  WHERE p.project_categories_idproject_categories=pr.idproject_categories
				    AND p.idprojects=pm.projects_idprojects
				    AND pm.users_idusers=".$uid."
				    AND pm.iduser_status = 2";
					
		$projects1 = $db->get_results($strSQL);
	
		if ($projects and $projects1){
			return array_merge($projects,$projects1);;
		}
		elseif 	($projects)
			return $projects;
		elseif 	($projects1)
			return $projects1;			
	}
	
		
	
	/* get my projects count*/
	function getMyProjectsCount($uid) {
		
		global $db;
		$strSQL="SELECT count(p.idprojects) as total
				   FROM projects p
				  WHERE p.users_idusers=".$uid;
		
		$count = $db->get_row($strSQL);
		return $count;
	}
	
	
	
	
	/* get project by Id */
	function getProjectById($id) {
		
		global $db;
		
		$strSQL='SELECT p.name, p.description, p.pkey, p.notify_project, p.notify_user, p.notify_annotation, p.security, p.active, p.configured,p.created, p.modified, p.das_servers_iddas_servers, pr.name as category, pr.idproject_categories , u.name as owner, u.email, u.company, u.email, u.public_data, u.idusers
				   FROM projects p, project_categories pr, users u
				  WHERE p.project_categories_idproject_categories=pr.idproject_categories 
				    AND p.users_idusers = u.idusers
				    AND p.idprojects='.$id;
		
		$project = $db->get_row($strSQL);
		return $project;
	}
	
	
	/* get project by key */
	function getProjectByKey($pkey) {
		
		global $db;
		
		$strSQL = "SELECT p.idprojects, p.name, p.description, p.notify_project, p.notify_user, p.notify_annotation, p.security, p.active, p.configured,p.created, p.modified, p.das_servers_iddas_servers, pr.name as category, pr.idproject_categories , u.name as owner, u.email, u.company, u.email, u.public_data, u.idusers
				   FROM projects p, project_categories pr, users u
				  WHERE p.project_categories_idproject_categories=pr.idproject_categories 
				    AND p.users_idusers = u.idusers
				    AND p.pkey='".$pkey."'";
		
		$project = $db->get_row($strSQL);
		return $project;
	}

	
	/* get projectMembers by Id */
	function getProjectMembers($pid) {
		
		global $db;
		
		$strSQL="SELECT p.*, u.*, r.*
				   FROM project_members p, users u, roles r
				  WHERE p.users_idusers=u.idusers
				  	AND r.idroles = p.idroles
				    AND p.projects_idprojects=".$pid."
				    AND u.name <> 'Nobody'
				    ORDER BY p.iduser_status,u.name ";
		
		$members = $db->get_results($strSQL);
		return $members;
	}
	
	/* get project member by Id */
	function getProjectMemberById($id) {
		
		global $db;
		
		$strSQL='SELECT *
				   FROM project_members p, users u
				  WHERE p.users_idusers=u.idusers
				    AND p.idproject_members='.$id;
				   
		
		$members = $db->get_row($strSQL);
		return $members;
	}
	
	
	function getProjectRole($uid,$pid){
	
		global $db;
		
		if (!$uid or !$pid)
			return '';
		
		$strSQL = "SELECT * FROM projects WHERE idprojects = ".$pid." AND users_idusers=".$uid;
		
		$r = $db->get_row($strSQL);
		
		if ($r)
			return 'PROJECT LEADER';
			
		$strSQL = "SELECT r.role FROM project_members pm, roles r WHERE pm.users_idusers=".$uid." AND pm.projects_idprojects=".$pid." AND r.idroles = pm.idroles";
		$r = $db->get_row($strSQL);
		
		if ($r)
			return $r->role;
			
		return '';	
	}
		
	function getProjectStatus($uid,$pid){
	
		global $db;
		
		if (!$uid or !$pid)
			return '';
		
			
		$strSQL = "SELECT pm.iduser_status as status FROM project_members pm WHERE pm.users_idusers=".$uid." AND pm.projects_idprojects=".$pid;
		$r = $db->get_row($strSQL);
		
		if ($r)
			return $r->status;
			
		return '';	
	}
	
	/* joinToProject */
	function joinToProject($userId,$pid,$security) {
		
		global $db;
		global $mail;
		

		$strSQL='SELECT * FROM project_members
					   WHERE users_idusers ='.$userId.'
						 AND projects_idprojects='.$pid;
		
		$res = $db->get_row($strSQL);
				
		if ($res){
			return $_SESSION['project_member_exist'];
		}

				
		$strSQL='INSERT INTO project_members
					     SET users_idusers='.$userId.',
						     projects_idprojects='.$pid.',
						     idroles = 1,
						     iduser_status = 1,
						     created=now(),
							 modified=now()';
		
		$project = $db->query($strSQL);
		
		$strSQL = 'SELECT p.*, u.name as user, u.email FROM projects p, users u WHERE idprojects = '.$pid.' AND u.idusers = p.users_idusers';
		$res 	= $db->get_row($strSQL);
		
		if ($res->notify_user == 1){
			
				$subject = 'User request to join one of your projects';
				$texto	 = 'Dear '.$res->user.'<br><br>A user is requesting to join your project: <br><br><b>'.$res->name.'</b><br>'.$res->description.'<br><br>Please check your project member list.<br><br><b>MaDAS</b><br><a href="http://madas2.bioinfo.cnio.es">http://madas2.bioinfo.cnio.es</a>';
			
				$mail->From 	= 'vdelatorre@cnio.es';
				$mail->FromName = 'MaDAS';
				$mail->Sender   = 'MaDAS';
				$mail->CharSet  = 'utf-8';
				$mail->Subject 	= $subject;	
				$mail->Body 	= $texto;
				$mail->AltBody  = html2text($texto);
				$mail->IsHTML(true);
				$mail->AddBCC(trim($res->email));
				$exito = $mail->Send();
				$mail->ClearAllRecipients();

		
		}
		
		return $_SESSION['project_member_queue'];
	}
	
}
?>
