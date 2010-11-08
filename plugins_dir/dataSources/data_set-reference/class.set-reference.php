<?php
	###############################################################################
	#                      class.set-reference.php -  description                 #
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
	
class Set_Reference {

	
	// get molecule types 
	function getMoleculeTypes() {
		
		global $db;
		$strSQL = "SELECT *
				     FROM molecule_types m
			   	 ORDER BY m.mname";
				 
		$types = $db->get_results($strSQL);
		return $types;
	}
	
	// get all DSN 
	function getAllDsn() {
		
		global $db;
		$strSQL = "SELECT *
					 FROM das_commonserver_dsns d, users u
					 WHERE d.idusers = u.idusers
			   	 ORDER BY d.dname, d.dversion";
				 
		$dsns = $db->get_results($strSQL);
		return $dsns;
	}
	
	
	// get DSN count  
	function getDsnCount($pid) {
		
		global $db;
		$strSQL = "SELECT count(*) as total 
		             FROM das_commonserver_dsns d
			    WHERE projects_idprojects = ".$pid;
				 
		$dsns = $db->get_row($strSQL);
		return $dsns->total;
	}
	
	// get dsn by ID
	function getDsnById($id) {
		
		global $db;
		$strSQL = "SELECT * FROM das_commonserver_dsns d
			   	   WHERE iddas_commonserver_dsns = ".$id;
				 
		$dsns = $db->get_row($strSQL);
		return $dsns;
	}
	
	
	// delete dsn by ID
	function deleteDsnById($id) {
		
		global $db;
		$strSQL = "DELETE FROM das_commonserver_dsns 
			   	   WHERE iddas_commonserver_dsns = ".$id;
				 
		$dsns = $db->query($strSQL);
	}
	
	
	function getAllDsnsByProject($page,$start,$limit,$sidx,$sord,$wh,$uid,$pid) {
		
		global $db;
		$strSQL="SELECT m.*,d.*,u.*, count(s.iddas_commonserver_segments) as segments 
				   FROM users u,molecule_types m, das_commonserver_dsns d 
		      LEFT JOIN das_commonserver_segments s ON (s.iddas_commonserver_dsns = d.iddas_commonserver_dsns)
				  WHERE d.dmolecule_type = m.idmolecule_types
				    AND d.idusers = u.idusers
		            AND d.projects_idprojects = ".$pid." 
			   	 ".$wh." 
			      GROUP BY d.iddas_commonserver_dsns
			      ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;
		
		$dsns = $db->get_results($strSQL);
		return $dsns;
	}
	
	
	
	
	//store DSN
	function storeDSN($dsn,$pid){
		
		global $db;
		
		//update
		if ($dsn['dsnid']){
			
			 $strSQL = "UPDATE das_commonserver_dsns
	                       SET dname = '".$dsn['dname']."',
	                         idusers = ".$dsn['idusers'].",
	                        dversion = ".$dsn['version'].",
	                      dmap_master = '".$dsn['dmap_master']."',
	                     ddescription = '".$dsn['description']."',
	                   dmolecule_type = '".$dsn['mtype']."',
	                    dinclude_seq = ".$dsn['dinclude_seq'].",
	              projects_idprojects = ".$pid."
	                    WHERE iddas_commonserver_dsns = ".$dsn['dsnid'];
	                                                          
	          $db->query($strSQL);
	                  
	          return $dsn['dsnid'];
		
		//insert
		}else{
		
			//check for same dsn name and version within the project.
			$strSQL = "SELECT * FROM das_commonserver_dsns WHERE dname = '".$dsn['dname']."' AND dversion = '".$dsn['version']."' AND projects_idprojects = ".$pid;
			$r = $db->query($strSQL);
			
			
			if (!$r){
	                  $strSQL = "INSERT INTO das_commonserver_dsns
	                                                SET dname = '".$dsn['dname']."',
	                                                  idusers = ".$dsn['idusers'].",
	                                                  dversion = ".$dsn['version'].",
	                                              dmap_master = '".$dsn['dmap_master']."',
	                                              ddescription = '".$dsn['description']."',
	                                            dmolecule_type = '".$dsn['mtype']."',
	                                              dinclude_seq = ".$dsn['dinclude_seq'].",
	                                                  dcreated = now(),
	                                    projects_idprojects = ".$pid;
	                                                          
	                  $db->query($strSQL);
	                  
	                  return $db->getLastId();
			}
		
		
		}
		return 0;
	}
	
	
	// parse fasta file
	function parseFile($f,$dsn,$mtype,$version){
		
		$c = new Comodity;
		
		if (!$lines = @file($f,FILE_SKIP_EMPTY_LINES)){
			$c->mesg('File is missing or some error ocurred. Process aborted',false);
			exit;
		}

		$seq = array();
		$curr = '';
		$i = 0;
		
		foreach ($lines as $line_num => $line){
		        if (preg_match('/\>/',$line)){
		            //NCBI format
		            if (preg_match('/|/',$line)){
		              $t = array();
		              $t = explode('|',$line);
		              $curr = $t[3];
		            }else{
		              $curr = rtrim($line);
		            }

		            if ($i>0){
		                $segment_id = $this->segmentExist($curr);
                                //new segment in the file
                                if (!$segment_id){
                                        $segment_id = $this->storeSegment($curr,$dsn,$mtype,$seq[$curr],$version);
                                }
		            }

		        
		        }else{
		          
		          $seq[$curr] .= rtrim($line);
		          if ($i == count($lines)-1 ){
		                $segment_id = $this->segmentExist($curr);
                                //new segment in the file
                                if (!$segment_id){
                                        $segment_id = $this->storeSegment($curr,$dsn,$mtype,$seq[$curr],$version);
                                }
		            }
		          
		        }
			$i++;
		}
	        $c->runMesg('[Processing '.count($seq).' record(s)]');  
	}
	
	
	//segment exist
	function segmentExist($name){
	
		global $db;
		$strSQL = "SELECT * 
					 FROM das_commonserver_segments d 
					WHERE d.sname = '".$name."'";
				 
		$segment = $db->get_row($strSQL);
		if ($segment)
			return $segment->iddas_commonserver_segments;
			
		return false;
	}
	
	//store Segment
	function storeSegment($name,$dsn,$mtype,$seq,$version){
		
		global $db;
		$strSQL = "INSERT INTO das_commonserver_segments
						   SET iddas_commonserver_dsns = ".$dsn.",
							   sname = '".$name."',
							   idmolecule_types = ".$mtype.",
							   ssequence = '".$seq."',
							   sstart = 1,
							   sstop = ".strlen($seq).",
							   sversion = '".$version."',
							   created =  now()";

		$db->query($strSQL);
		
		return $db->getLastId();
	}
	
	// get segment by ID
	function getSegmentById($id) {
		
		global $db;
		$strSQL = "SELECT * 
					 FROM das_commonserver_segments d 
					WHERE d.iddas_commonserver_segments = ".$id;
				 
		$segment = $db->get_row($strSQL);
		return $segment;
	}
	
	
	
	
	
	
}
	
	
	
	
	
?>