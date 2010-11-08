<?php
	###############################################################################
	#                      class.user.php -  description                          #
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

class User
{
	/* cleandbf */
	function cleandbf($value) {
		
		return trim($value);
	}
	
	/* get all registered users */
	function getAllUsers() {
		
		global $db;
		$strSQL="SELECT u.*
				   FROM users u 
				  WHERE u.confirmed=1
				    AND u.public_data <>0";
		
		$users = $db->get_results($strSQL);
		return $users;
	}
	
	
	/* get online users */
	function getOnlineUsers() {
		
		global $db;
		$exp_time=$_SESSION['session_expire_time']; 
		$uid=@$_SESSION['idusers'];
		
		$strSQL='SELECT u.*, max(s.start_time)
				   FROM users u, sessions s
				  WHERE u.confirmed=1
  				    AND u.public_data <>0
				    AND u.idusers=s.users_idusers 
					AND ';
					
		if 	($uid)		
			$strSQL.= '(u.idusers='.$uid.' OR ((UNIX_TIMESTAMP() - UNIX_TIMESTAMP(s.start_time) ) < '.$exp_time.')) ';
		else	
			$strSQL.= '((UNIX_TIMESTAMP() - UNIX_TIMESTAMP(s.start_time) ) < '.$exp_time.') ';
			
		$strSQL .=' GROUP BY u.idusers';
		
		//echo '<div>'.$strSQL.'</div>';
		
		$users = $db->get_results($strSQL);
		return $users;
	}
	
	
	/* get user by Id */
	function getUserById($id) {
		
		global $db;
		
		$strSQL='SELECT u.*
				  FROM users u
				  WHERE u.idusers='.$id;
		
		$users = $db->get_row($strSQL);
		return $users;
	}
	
	
	/* create user */
	function createUser($uname,
						$company,
						$address,
						$city,
						$country,
						$email,
						$phone,
						$fax,
						$website,
						$passw,
						$public_data,
						$notify_projects,
						$notify_users,
						$notify_annotations,
						$notify_plugins) {
		
		global $db,$_SESSION;
		
		//defaults
		$confirmed=1;
		$utype='NORMAL';
		$active=1;
		$public_data=($public_data)?1:0;
		$notify_projects=($notify_projects)?1:0;
		$notify_users=($notify_users)?1:0;
		$notify_annotations=($notify_annotations)?1:0;
		$notify_plugins=($notify_plugins)?1:0;
		$passw=md5($passw);
		
		
		//check for empty values
		/*if (!$name or !$company or !$email or !$passw)
			return $_SESSION['required_fields'];*/
		
		
		//check for duplicated emails
		$strSQL="SELECT idusers FROM users u WHERE u.email='".$email."'";
		$r = $db->get_row($strSQL);
		if ($r)
			return $_SESSION['duplicated_user'];
	
		//insert user
		$strSQL="INSERT INTO users
					     SET name='".$this->cleandbf($uname)."',
							 company='".$this->cleandbf($company)."',
							 address='".$this->cleandbf($address)."',
							 city='".$this->cleandbf($city)."',
							 country='".$this->cleandbf($country)."',
							 email='".$this->cleandbf($email)."',
							 phone='".$this->cleandbf($phone)."',
							 fax='".$this->cleandbf($fax)."',
							 website='".$this->cleandbf($website)."',
							 passw='".$this->cleandbf($passw)."',
							 public_data=".$this->cleandbf($public_data).",
							 notify_projects=".$this->cleandbf($notify_projects).",
							 notify_users=".$this->cleandbf($notify_users).",
							 notify_annotations=".$this->cleandbf($notify_annotations).",
							 notify_plugins=".$this->cleandbf($notify_plugins).",
							 confirmed=".$this->cleandbf($confirmed).",
							 utype='".$this->cleandbf($utype)."',
							 active=".$this->cleandbf($active).",
							 created=now(),
							 modified=now()";
							 
		$db->query($strSQL);	
		
		//$debug=$strSQL;
		

		$strSQL="SELECT max(u.idusers) as id FROM users u";				 
		$r = $db->get_row($strSQL);
		$id = $r->id;
		$md5 = md5(uniqid(rand(),true));
							 
		//create user session					 
/*
		$strSQL= "INSERT INTO sessions
				     SET users_idusers=".$id.",
					 	 start_time=now(),
						 cookie='".$this->cleandbf($md5)."'";					 
		$db->query($strSQL);
*/

		
		$_SESSION['idusers'] = $id;
		$_SESSION['cookie'] = $md5;
		$_SESSION['name']  = $uname;
		$_SESSION['email']  = $email;
		$_SESSION['company']  = $company;
		$_SESSION['utype']  = $utype;
		
		//create user cookies
		setcookie("idusers", $id, time()+(60*60*24),'/');
		setcookie("name", $uname, time()+(60*60*24),'/');
		setcookie("email", $email, time()+(60*60*24),'/');
		setcookie("company", $company, time()+(60*60*24),'/');
		setcookie("utype", $utype, time()+(60*60*24),'/');
			
		
		return $_SESSION['user_created'];
	}
	
	
	/* update user */
	function updateUser($idusers,
						$uname,
						$company,
						$address,
						$city,
						$country,
						$email,
						$phone,
						$fax,
						$website,
						$passw,
						$public_data,
						$notify_projects,
						$notify_users,
						$notify_annotations,
						$notify_plugins) {
		
		global $db,$_SESSION;
		
		//defaults
		$confirmed=1;
		$utype='NORMAL';
		$active=1;
	    $notify_projects=($notify_projects)?1:0;
		$notify_users=($notify_users)?1:0;
		$notify_annotations=($notify_annotations)?1:0;
		$notify_plugins=($notify_plugins)?1:0;
		
		//check for empty values
		/*if (!$name or !$company or !$email or !$passw)
			return $_SESSION['required_fields'];*/
		
		
		//update user
		if ($passw){
		         $passw=md5($passw);
			 $strSQL="UPDATE users
					     SET name='".$this->cleandbf($uname)."',
							 company='".$this->cleandbf($company)."',
							 address='".$this->cleandbf($address)."',
							 city='".$this->cleandbf($city)."',
							 country='".$this->cleandbf($country)."',
							 email='".$this->cleandbf($email)."',
							 phone='".$this->cleandbf($phone)."',
							 fax='".$this->cleandbf($fax)."',
							 website='".$this->cleandbf($website)."',
							 passw='".$this->cleandbf($passw)."',
							 public_data=".$this->cleandbf($public_data).",
							 notify_projects=".$this->cleandbf($notify_projects).",
							 notify_users=".$this->cleandbf($notify_users).",
							 notify_annotations=".$this->cleandbf($notify_annotations).",
							 notify_plugins=".$this->cleandbf($notify_plugins).",
							 confirmed=".$this->cleandbf($confirmed).",
							 modified=now()
						  WHERE idusers=".$idusers;
		}else{
			 $strSQL="UPDATE users
					     SET name='".$this->cleandbf($uname)."',
							 company='".$this->cleandbf($company)."',
							 address='".$this->cleandbf($address)."',
							 city='".$this->cleandbf($city)."',
							 country='".$this->cleandbf($country)."',
							 email='".$this->cleandbf($email)."',
							 phone='".$this->cleandbf($phone)."',
							 fax='".$this->cleandbf($fax)."',
							 website='".$this->cleandbf($website)."',
							 public_data=".$this->cleandbf($public_data).",
							 notify_projects=".$this->cleandbf($notify_projects).",
							 notify_users=".$this->cleandbf($notify_users).",
							 notify_annotations=".$this->cleandbf($notify_annotations).",
							 notify_plugins=".$this->cleandbf($notify_plugins).",
							 confirmed=".$this->cleandbf($confirmed).",
							 modified=now()
						WHERE idusers=".$idusers; 
							 
		}					 
		$db->query($strSQL);	
		
		return $_SESSION['user_updated'];
	}
	
	
	
	
	/* loginUser */
	function loginUser($email,$passw,$rememberme) {
		
		global $db;
		$exp_time=$_SESSION['session_expire_time']; 
		
		$strSQL="SELECT u.idusers, u.name, u.company, u.address, u.city, u.country, u.email, u.phone, u.fax, u.website, u.passw, 
u.public_data, u.confirmed, u.utype, u.active, u.created, u.modified 
				   FROM users u
				  WHERE u.confirmed=1
				    AND u.email='".$this->cleandbf($email)."'
					AND u.passw='".$this->cleandbf(md5($passw))."'";
					
		$user = $db->get_row($strSQL);
		//echo $strSQL;
		
		if ($user){
			
			//create user session	
			$md5 = md5(uniqid(rand(),true));
							 
			$strSQL= "INSERT INTO sessions
						 SET users_idusers=".$user->idusers.",
							 start_time=now(),
							 cookie='".$this->cleandbf($md5)."'";					 
			$db->query($strSQL);
			
			$_SESSION['idusers'] = $user->idusers;
			$_SESSION['cookie'] = $md5;			
			$_SESSION['name']  = $user->name;
			$_SESSION['email']  = $user->email;
			$_SESSION['company']  = $user->company;
			$_SESSION['utype']  = $user->utype;
			
			//create user cookies
			setcookie("idusers", $user->idusers, time()+(60*60*24),'/');
			setcookie("name", $user->name, time()+(60*60*24),'/');
			setcookie("email", $user->email, time()+(60*60*24),'/');
			setcookie("company", $user->company, time()+(60*60*24),'/');
			setcookie("utype", $user->utype, time()+(60*60*24),'/');

			
			return $_SESSION['user_logged']; 
				
		}else
			return $_SESSION['invalid_user_password']; 

		
	}
	
	/* logoutUser */
	function logoutUser() {
		
		$_SESSION['idusers'] = '';
		$_SESSION['cookie'] = '';			
		$_SESSION['name']  = '';
		$_SESSION['email']  = '';
		$_SESSION['company']  = '';
		$_SESSION['utype']		= '';
		
		//delete user cookies
		setcookie("idusers", '', time()-(60*60*24),'/');
    	setcookie("name", '', time()-(60*60*24),'/');
    	setcookie("email", '', time()-(60*60*24),'/');
    	setcookie("company", '', time()-(60*60*24),'/');
    	setcookie("utype", '', time()-(60*60*24),'/');
			
	}
	
	/* changePassword */
	function changePassword($email) {
		
		global $db;
		
		$passw=uniqid(rand(),true);
		
		$strSQL="SELECT u.idusers, u.name, u.company, u.address, u.city, u.country, u.email, u.phone, u.fax, u.website, u.passw, 
u.public_data, u.confirmed, u.utype, u.active, u.created, u.modified 
				   FROM users u
				  WHERE u.confirmed=1
				    AND u.email='".$this->cleandbf($email)."'";

		
		$user = $db->get_row($strSQL);
		
		if (!$user)
			return $_SESSION['invalid_email']; 
				
		$strSQL="UPDATE users u
					SET u.passw='".$this->cleandbf(md5($passw))."' 
				  WHERE u.email='".$email."'";
		
		$db->query($strSQL);

		return $_SESSION['password_changed']; 
	}
	
	/*mailToUser*/
	function mailToUser($from,$to,$subject,$text){
		
		global $db,$_SESSION;	
		
		$user=$this->getUserById($from);
		$contact=$this->getUserById($to);
		
		switch ($user->public_data){
		
			case 0:
				$headers = 'From: MaDas System<'.$_SESSION['contact_email'].'> '. "\r\n";
				break;
			case 1:
				$headers = 'From: '.$user->name.'<'.$_SESSION['contact_email'].'> '. "\r\n";
				break;
			case 2:
				$headers = 'From: '.$user->name.'<'.$user->email.'> '. "\r\n";
				break;
		}	
		
		switch ($user->public_data){
		
			case 0:
				$content = 'Some MaDas user has sent you a comment:'. "\r\n\r\n";
				break;
			case 1:
				$content = $user->name.' has sent you a comment through MaDas system:'. "\r\n\r\n";
				break;
			case 2:
				$content = $user->name.' has sent you a comment through MaDas system:'. "\r\n\r\n";
				break;
		}	
		
		$content.= $text. "\r\n\r\n".$_SESSION['mail_note'];
		
		//mail($contact->email, $subject, $content, $headers);
		
		return $_SESSION['mail_sent']; 
	}
	
	/* addFavorite */
	function addFavorite($uid,$fid,$type) {
		
		global $db;
		
		$strSQL = "SELECT * 
					 FROM favorites 
					WHERE users_idusers = ".$uid."
					  AND cross_id = ".$fid."
					  AND ftype = '".$type."'";
					  
		$r = $db->get_row($strSQL);
		
		if($r)
			return $_SESSION['favorite_exist'];
			
		$strSQL = "INSERT INTO favorites 
						 SET users_idusers = ".$uid.",
							 cross_id = ".$fid.",
							 ftype = '".$type."',
							 created =  now()";
		
		$db->query($strSQL);
		return $_SESSION['favorite_created']; 
		
	}
	
	/* delFavorite */
	function delFavorite($uid,$fid,$type) {
		
		global $db;
		
		$strSQL = "DELETE
					 FROM favorites 
					WHERE users_idusers = ".$uid."
					  AND cross_id = ".$fid."
					  AND ftype = '".$type."'";
					  
		$r = $db->query($strSQL);
		
		return $_SESSION['favorite_deleted']; 
		
	}
	
	/* isFavorite */
	function isFavorite($uid,$fid,$type) {
		
		global $db;
		
		if (!$uid or !$fid or !$type)
			return false;
		
		$strSQL = "SELECT * 
					 FROM favorites 
					WHERE users_idusers = ".$uid."
					  AND cross_id = ".$fid."
					  AND ftype = '".$type."'";
					  
		$r = $db->get_row($strSQL);
		
		if($r)
			return true;
			
		return false;
		
	}
	
	
}
?>