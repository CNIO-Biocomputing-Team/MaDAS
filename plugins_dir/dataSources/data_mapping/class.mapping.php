<?php
	###############################################################################
	#                      class.manage-annot.php -  description                  #
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
	
class Mapping {

    // get annotations count  
      function getAnnotCount($pid) {
              
              global $db;
              $strSQL = "SELECT count(*) as total 
                            FROM das_commonserver_annotations 
                          WHERE idprojects = ".$pid;
                                
              $r = $db->get_row($strSQL);
             
              return $r->total;
      }
    
    
    
    function getAllAnnotByProject($page,$start,$limit,$sidx,$sord,$wh,$uid,$pid) {
		
		global $db;
		$strSQL="SELECT a.*,d.*, count(f.iddas_commonserver_features) as features FROM das_commonserver_dsns d , das_commonserver_annotations a 
		                 LEFT JOIN das_commonserver_features f ON (f.iddas_commonserver_annotations = a.iddas_commonserver_annotations)
				 WHERE a.idprojects = ".$pid." 
				   AND d.iddas_commonserver_dsns = a.iddas_commonserver_dsns
			   	 ".$wh." 
			      GROUP BY a.iddas_commonserver_annotations
			      ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;
		
		
		$annots = $db->get_results($strSQL);
		return $annots;
    }
    
    
    function duplicateAnnot($annotId,$ftype) {
		
		global $db;
		
		$strSQL = "INSERT INTO das_commonserver_annotations
					SELECT null,
						  idprojects, 
						  idsources, 
						  idusers, 
						  CONCAT(description,' Maped to ".$ftype."'), 
						  'ACTIVE',
						  now(), 
						  now(), 
						  iddas_commonserver_dsns
					 FROM das_commonserver_annotations
					WHERE iddas_commonserver_annotations = ".$annotId;	
		
		
		$db->query($strSQL);
		
		$strSQL = "SELECT a.iddas_commonserver_annotations as id,a.iddas_commonserver_dsns as dsn, d.dmolecule_type as mtype 
					 FROM das_commonserver_annotations  a, das_commonserver_dsns d 
					WHERE a.iddas_commonserver_annotations=".$db->getLastId()."
					  AND a.iddas_commonserver_dsns = d.iddas_commonserver_dsns";
							   
		$res = $db->get_row($strSQL);					   
		return $res;
    }
    
    function getFeatureNamesByAnnot($annot) {
            
            global $db;
            
            $ids = array();
            
            $strSQL = "SELECT DISTINCT(id) as id  FROM das_commonserver_features WHERE iddas_commonserver_annotations =".$annot ." LIMIT 1,40000";
            $res = $db->get_results($strSQL);
            
            if ($res){
            	foreach($res as $r)
            		array_push($ids,$r->id);
            }
                        
            return $ids;
    }
    
    // parse biomart results
	function parseBiomart($results,$dsn,$mtype,$annot,$feature){
		
		global $c;
		global $d;

		
		foreach ($results as $line){
			if (strlen($line)>5){
				$record = $this->parseLine(trim($line),$feature);
				if ($record){	
					$segment_id = $d->segmentExist($record['seqname'],$dsn);
					//new segment in the file
						if (!$segment_id){
						$segment_id = $d->storeSegment($record['seqname'],$dsn,$mtype);
					}
		
					$d->storeFeature($segment_id,$record,$annot);
				}	
			}
		}
	}
	
	// parse biomart record
	function parseLine($line,$feature){
		
		$tmp = explode(",",$line);
		//echo $line.'<br>';
		if (count($tmp) == 6 and @$tmp[5]){
			
			$record['seqname']		= @$tmp[1];
			$record['source']		= 'Biomart';
			$record['type']		    = $feature;
			$record['start']		= @$tmp[2];
			$record['end']			= @$tmp[3];
			$record['score']		= 0;
			$record['strand']		= (@$tmp[4]>0)?'+':'-';
			$record['frame']    	= 0;
			$record['attributes']   = 'ID='.@$tmp[5].';ENSEMBL-ID='.@$tmp[0].';';

			return $record;
		}else
			return 0;
	}

}
?>