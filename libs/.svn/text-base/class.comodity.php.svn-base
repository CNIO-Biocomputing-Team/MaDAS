<?php
	###############################################################################
	#                      class.comodity.php -  description                      #
	#                             -------------------                             #   
	#    begin                : Wed Jul 25 14:18:02 CEST 2007                     # 
	#    copyright            : (C) 2007 by Victor de la Torre                    # 
	#    email                : victor@madas.es                                   # 
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

class Comodity{

	//clean_str
	function clean_str($str){
		
		$str = str_replace(array("'","\n","\t"),"",$str);
		return $str;
	}
	
	//clean_str_type
	function clean_str_type($str){
		
		$str = str_replace(array("'","\n","\t"," "),array("","","","_"),$str);
		return $str;
	}
	
	//declean_str_type
	function declean_str_type($str){
		
		$str = str_replace(array("_"),array(" "),$str);
		return $str;
	}
		
	function preparesql($str){
		
		$str = addslashes($str);
		return $str;
	}
	
	
	function readSQL($v) {

		return stripslashes($v);

	}	
	
	
	
	
	//display error or OK messages
	function mesg($msg,$success){
		
		if ($msg){
			if ($success)
				echo '<div class="ok"><table cellpadding="10"><tr><td><img src="images/comments_alert.png" align="middle" /></td><td>'.$msg.'</td></tr></table></div>';
			else 
				echo '<div class="wrong"><table cellpadding="10"><tr><td><img src="images/comments_report.png" align="middle" /></td><td>'.$msg.'</td></tr></table></div>';	
		}
	}	
	
	//display run messages
	function runMesg($msg){
		
		if ($msg)
			echo '<div class="run">'.$msg.'</div>';

	}	
	
	//upload tgz
	function UploadTgz($file,$directory){
	
		$file_name = $file['name'];
		
		//check file
		if (!$file_name)
			return $_SESSION['file_required'];
			

		$file_type = $file['type'];
		$file_size = $file['size']; 
		$file_tmp  = $file['tmp_name'] ;
			
		//check size
		if ($file_size > $_SESSION['max_file_size'])
			return $_SESSION['max_file_exceded'];

		//check type
		if ($file_type != 'application/x-compressed' and $file_type != 'application/x-gzip' )
			return $_SESSION['invalid_file_type'];	
		
		
		system('tar -xzf '.$file_tmp.' '.$_SESSION['data_sources_dir']);
		$plugin_dir = $_SESSION['data_sources_dir'].$file_name;
			
		//check plugin.xml
		if (!file_exists($plugin_dir.'/plugin.xml'))
			return $_SESSION['plugin_upload_failed'];	

		
		return $plugin_dir;
	}
	
	
	function countRec($strSQL) {

		global $db;	
		$results = $db->get_results($strSQL);
		$count = count($results);
		return $count;

	}
	
	function buildXML($page,$rp,$key,$strSQL){
	
		global $db;
		
		$start = (($page-1) * $rp);
		$limit = "LIMIT $start, $rp";
		$total = $this->countRec($strSQL);
		$strSQL .= " $limit ";
		

		
		$results = $db->get_results($strSQL);
		
		

	
		//xml format
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
		header("Cache-Control: no-cache, must-revalidate" );
		header("Pragma: no-cache" );
		header("Content-type: text/xml");
		$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		$xml .= "<rows>";
		$xml .= "<page>$page</page>";
		$xml .= "<total>$total</total>";
	
		if ($results){
			foreach ($results as $r){ 
		
				$xml .= "<row id='".$r->$key."'>";
				
				foreach ($r as $k=>$v){
					$xml .= "<cell><![CDATA[".utf8_encode($v)."]]></cell>";
				}	
				$xml .= "</row>";	
			}
		}
		
		$xml .= "</rows>";
		return $xml;
		
	}
	
	function unzipFile($f,$dir){
	
	     $zip = new ZipArchive;
     	 $res = $zip->open($f);
    	 if ($res === TRUE) {
         	$zip->extractTo($dir);
         	$zip->close();
    	}
     }
    
    
    //-----------------------------------
	function post_data($host,$script,$data){
	
		
		$buf ="POST ".$script." HTTP/1.0\r\n";
		$buf .="Host: ".$host."\r\n";
		$buf .="Content-type: application/x-www-form-urlencoded; charset=UTF-8\r\n";
		$buf .="Content-length: ".strlen($data)."\r\n";
		$buf .="\r\n";
		$buf .=$data;

		$fp = @fsockopen ($host, 80);
		
		if (!$fp){
			return 0;

		}else{
			fputs($fp, $buf);
			$buf ="";
			while (!feof($fp))
				$buf .=@fgets($fp,128);
			fclose($fp);
			return $buf;
		}
	}
	
	
	//query biomart
	function query_biomart($query){
	
		global $c;
		
		$host 	= 'www.biomart.org';
		$script = '/biomart/martservice';
		$data	= 'query='.$query;

	  $response =  $c->post_data($host,$script,$data);
	  $results	=  split('text/plain',$response);
	  $temp		=  split("\n",$results[1]);
	  
	  return $temp;
	}
	
	
	function shareLink($uid,$pid,$email,$permalink){
	
			global $db;
			global $mail;
			
			$strSQL = "SELECT * FROM users WHERE idusers=".$uid;
			$user = $db->get_row($strSQL);

			$strSQL = "SELECT * FROM projects WHERE idprojects=".$pid;
			$project = $db->get_row($strSQL);
			
			//project invitation
			if (!preg_match('/plugid/',$permalink)){
			
				$subject = $user->name.' has invited you to participate a MaDAS project.';
			
				$texto  = $user->name.' has invited you to participate in the MaDAS project:<br><br><b>'.$project->name.'</b><br>'.$project->description.'<br><br>Please click on <a href="'.$permalink.'">this link</a> to view the project details or paste the following URL in your browser:<br><br>'.$permalink.'<br><br>Thanks. <br>MaDAS team.';
			}else{
			
			
				$subject = $user->name.' are sharing some results with you.';
			
			$texto  = $user->name.' are sharing some results with you inside the MaDAS project:<br><br><b>'.$project->name.'</b><br>'.$project->description.'<br><br>Please click on <a href="'.$permalink.'">this link</a> to view the details or paste the following URL in your browser:<br><br>'.$permalink.'<br><br>Thanks. <br>MaDAS team.';
			}
			
			
			
			
			
			
			$mail->From 	= 'no-reply@madas.bioinfo.cnio.es';
			$mail->FromName = 'MaDAS';
			$mail->Sender   = 'MaDAS';
			$mail->CharSet  = 'utf-8';
			$mail->Subject 	= $subject;	
			$mail->Body 	= $texto;
			$mail->AltBody  = html2text($texto);
			$mail->IsHTML(true);
			$mail->AddBCC(trim($email));
			$exito = $mail->Send();
			$mail->ClearAllRecipients();
	
	}
	


}

?>