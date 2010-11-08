<?php
	###############################################################################
	#                      class.load-array.php -  description                    #
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
	
class Load_Array {

	
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
					 FROM das_commonserver_dsns d
			   	 ORDER BY d.dname, d.dversion";
				 
		$dsns = $db->get_results($strSQL);
		return $dsns;
	}
	
	// get dsn by name
	function getDsnByNameProject($name,$pid) {
		
		global $db;
		$strSQL = "SELECT * FROM das_commonserver_dsns d
			   	   WHERE dname = '".$name."'
			   	   AND projects_idprojects =".$pid;
				 
		$dsn = $db->get_row($strSQL);
		return $dsn;
	}
	
	
	
	function storeAnnotation($pid,$userId,$id_dsn,$description){

	        global $db;
	        global $c;
	        
                $strSQL = "INSERT INTO das_commonserver_annotations
                                   SET idusers = ".$userId.",
                                       idprojects = ".$pid.",
                                       iddas_commonserver_dsns = ".$id_dsn.",
                                       description = '".$c->preparesql($description)."',
                                       status = 'ACTIVE',
                                       created = now(),
                                       modified = now()";

			                       
                $db->query($strSQL);
                return mysql_insert_id();
	}
	
	
	
	// parse .csv file
	function parseFile($f,$dsn,$mtype,$annot){
		
		$c = new Comodity;
		
		if (!$lines = @file($f,FILE_SKIP_EMPTY_LINES)){
			$c->mesg('File is missing or some error ocurred. Process aborted',false);
			exit;
		}
		$c->runMesg('[Processing '.count($lines).' records]');  
		
		$flag = 0;
		foreach ($lines as $line_num => $line){
			
			if ($flag != 0){
			
				$record = $this->parseLine(trim($line));
				$this->storeFeature($record['segment'],$record,$annot);	
			}
			$flag = 1;
		}
		
	}
	
	// parse gff record
	function parseLine($l){
	
		global $db;
	
		if (!preg_match("/#/",$l)){

			$tmp = split(";",$l);
			
			if ((int) str_replace('"','',$tmp[1]) > 0)
				$record['type']		= 'Up regulated gene';
			else
				$record['type']		= 'Down regulated gene';			
			
			$record['source']		= 'Gene Expresion';
			$record['attributes']	= 'ID='.str_replace('"','',$tmp[0]).';Obs stat='.str_replace('"','',$tmp[1]).';qvalue='.str_replace('"','',$tmp[2]).';LogFC='.str_replace('"','',$tmp[3]).';';
			
			//search for stored features
			$strSQL = "SELECT iddas_commonserver_segments, start, stop, orientation FROM das_commonserver_features WHERE id LIKE '%".$tmp[0]."%'";
			$r = $db->get_row($strSQL);
			
			$record['segment'] = $r->iddas_commonserver_segments;
			$record['start'] = $r->start;
			$record['end'] = $r->stop;
			$record['strand'] = $r->orientation;
			
			return $record;
			
		}
	}
	
	//segment exist
	function segmentExist($name,$dsn){
	
		global $db;
		$strSQL = "SELECT * 
					 FROM das_commonserver_segments d 
					WHERE d.sname = '".$name."'
					  AND iddas_commonserver_dsns = ".$dsn;
				 
		$segment = $db->get_row($strSQL);
		if ($segment)
			return $segment->iddas_commonserver_segments;
			
		return false;
	}
	
	//store Segment
	function storeSegment($name,$dsn,$mtype){
		
		global $db;
		
		$strSQL = "INSERT INTO das_commonserver_segments
						   SET iddas_commonserver_dsns = ".$dsn.",
							   sname = '".$name."',
							   idmolecule_types = ".$mtype.",
							   created =  now()";

		$db->query($strSQL);
		
		return $db->getLastId();
	}
	
	//store Feature
	function storeFeature($segment_id,$record,$annot){
		
		
		global $db;
		global $c;
		
		//get attributes
		if (@$record['attributes']){
		
			$tmp = split(";",$record['attributes']);
			foreach ($tmp as $att){
				list ($key,$value) = split("=",$att);
				$attributes[strtoupper($key)] = $value;
			}
		}
	
		$id = $attributes['ID'];
		//store feature type
		$ftype = $record['type'];
		$category = '';
		$subtype= '';
		$subparts = '';
		$superparts = '';
		$reference = '';
		
		 
		$type_id = $this->storeFType($ftype,$subtype,$category);
		
	
		//store feature
		$strSQL = "INSERT INTO das_commonserver_features
						   SET iddas_commonserver_segments = ".$segment_id.",
						           iddas_commonserver_annotations =".$annot.",
						   	   iddas_commonserver_types = ".$type_id.",	
						   	   id = '".$c->preparesql($id)."',
							   label = '".$c->preparesql($id)."',
							   start = '".$record['start']."',
							   stop = '".$record['end']."',
							   score = '".$record['score']."', 
							   method = '".$record['source']."',
							   orientation = '".str_replace(".","0",$record['strand'])."',
							   phase = '".$record['frame']."',
							   reference = '".$reference."',
							   superparts = '".$superparts."',
							   subparts = '".$subparts."',
							   created = now()";

		$db->query($strSQL);
		$feature_id = $db->getLastId();
		
		
		
		
		
		//store feature notes
		if (@$record['attributes']){
			foreach($attributes as $k=>$v){
				if ($k and $v)
					$this->storeFNote($feature_id,$k.': '.$v);				
			}
		}
		
		return $feature_id;
	}
	
	
	//store FType
	function storeFtype($ftype,$subtype,$category){
		
		global $db;
		$strSQL = "SELECT * 
					 FROM das_commonserver_types d
					WHERE tname = '".$ftype."' ";

		$t = $db->get_row($strSQL);
		
		//new type
		if (!$t){
			$th = rand(1,360);
			$strSQL = "INSERT INTO das_commonserver_types
							   SET tname ='".$ftype."',
							   	   tsubtype = '".$subtype."',
							       tcategory = '".$category."',
								   th = ".$th.",
								   tcreated = now()";
			$db->query($strSQL);					   
			return $db->getLastId();
			
		//type exist	
		}else {
			return $t->iddas_commonserver_types;
		}
	}
	
	
	
	//store FNote
	function storeFNote($fid,$text){
		
		global $db;
		global $c;
		
		$strSQL = "INSERT INTO das_commonserver_notes
						   SET iddas_commonserver_features = '".$fid."',
						   text = '".$c->preparesql($text)."',
						   created = now()";
		$db->query($strSQL);
		
		return $db->getLastId();
	}
	

	
}
?>	