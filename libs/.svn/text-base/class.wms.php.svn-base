<?php
	###############################################################################
	#                      class.wms.php -  description                           #
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

class Wms {

	public $tname = '';
	public $start = 0;
	public $stop  = 0;
	
	function getDAStypes($das_url,$segment){
		
		global $reader;
		
		$proxy 		= '';
		$types		= array();	
		
/*
		uniprot exceptions
		UniProt type command returns a false list of types. We need to get the features of the protein in order to get the right type list. We also remove feautures with start and end 0 from the type list
*/
		if ($das_url == 'http://www.ebi.ac.uk/das-srv/uniprot/das/uniprot'){
		
			$proxy 		= 'http://bwsp.bioinfo.cnio.es/bwsp.php?bwsp_service=uniprot_das&bwsp_response_format=row&bwsp_url=';
			$reader->open($proxy.$das_url.'/features?segment='.$segment);
				
			$type  = '';
			$start = 0;
			$stop  = 0;
				
			while (@$reader->read()) {
					
			    switch ($reader->nodeType) {
			   
			   		case (XMLREADER::ELEMENT):
			   			if ($reader->localName == "TYPE") {
		       			  	$reader->read();
		       			  	$type = $reader->value;
		 	       		}
		 	       		if ($reader->localName == "START") {
	       			  		
	       			  	$reader->read();
	       			  	$start = (int) $reader->value;
	       			  	}
		       			if ($reader->localName == "END") {
		       			  	
		       			  	$reader->read();
			       			$stop = (int) $reader->value;
		       			}
		 	       		break;
		 	       	case (XMLREADER::END_ELEMENT):
	       					if ($reader->localName == "FEATURE") {
	       						if ($start and $stop and abs($stop-$start>2))
	       							array_push($types,$type);	
	       					}
	       				break;		
		 	       		
				}
			}
		}
		return array_values(array_unique($types));		
	}

	function getDASannotations($das_url,$psegment,$psize,$sstart,$sstop){
	
		global $reader;
		
		$proxy 		= '';
		$sequence 	= '';
		$feature	= array();	
		$features	= array();
				
		//uniprot exceptions
		if ($das_url == 'http://www.ebi.ac.uk/das-srv/uniprot/das/uniprot'){
		
			$proxy 		= 'http://bwsp.bioinfo.cnio.es/bwsp.php?bwsp_service=uniprot_das&bwsp_response_format=row&bwsp_url=';
			$xml 		= @simplexml_load_file($proxy.$das_url.'/sequence?segment='.$psegment);
			$psize		= strlen($xml->SEQUENCE);
			$ss			= ($sstart-1 <0)?0:$sstart-1;
			$sequence 	= substr($xml->SEQUENCE,($sstart-1 <0)?0:$sstart-1,$sstop-($sstart));			
		}
		
		$comand		= '/features?';
		$segment	= ($psize > 5000)?'segment='.$psegment.':'.$sstart.','.$sstop:'segment='.$psegment;
		
		$url = $proxy.$das_url.$comand.$segment;		
		

		if ($sequence){
			$feature['sstart']	= $sstart;
			$feature['sstop'] 	= $sstop;
			$feature['tname']	= 'reference';
			$feature['start'] 	= $sstart;
			$feature['stop']  	= $sstop;
			$feature['note'][0]	= 'Sequence: '.$sequence;
			array_push($features,$feature);
			$feature['note']	= array();	
		}
			
		
		$reader->open($url);
		$i=1;

		//iterate over each feature
		$notes = array();
		while (@$reader->read()) {
					
		    switch ($reader->nodeType) {
		   
		   		case (XMLREADER::ELEMENT):
		   			  
		   			if ($reader->localName == "FEATURE") {

			   		    $featureId = $reader->getAttribute("id");
			   		    $projectId = $reader->getAttribute("project");
		   			}
		   			if ($reader->localName == "TYPE") {
	       			  	
	       			  	$reader->read();
	       			  	$type = $reader->value;
	 	       		}
	 	       		if ($reader->localName == "METHOD") {
	       			  	
	       			  	$reader->read();
	       			  	$method = $reader->value;
	 	       		}
	       			if ($reader->localName == "START") {
	       			  		
	       			  	$reader->read();
	       			  	$frstart = (int) $reader->value;
	       			  	$this->start = $frstart;
	       			}
	       			if ($reader->localName == "END") {
	       			  	
	       			  	$reader->read();
		       			$frstop = (int) $reader->value;
	       			}
	       			if ($reader->localName == "SCORE") {
	       			  	
	       			  	$reader->read();
	       			  	$score = $reader->value;
	 	       		}
	 	       		if ($reader->localName == "ORIENTATION") {
	       			  	
	       			  	$reader->read();
	       			  	$orientation = $reader->value;
	 	       		}
	 	       		if ($reader->localName == "PHASE") {
	       			  	
	       			  	//$reader->read();
	       			  	//$phase = $reader->value;
	 	       		}
	 	       		if ($reader->localName == "NOTE") {
	       			  	
	       			  	$reader->read();
	       			  	array_push($notes,$reader->value);
	 	       		}
	       			break;

	       		case (XMLREADER::END_ELEMENT):
	       			if ($reader->localName == "FEATURE") {
	
			   		    if ($type && $frstart && $frstop){
			   		    	$feature['sstart'] 		= $sstart;
    						$feature['sstop'] 		= $sstop;
							$feature['id']	 		= $featureId;
							$feature['tname'] 		= $type;
							$feature['method'] 		= $method;
							$feature['start'] 		= $frstart;
							$feature['stop']		= $frstop;
							$feature['score']		= $score;
							$feature['orientation']	= $orientation;
							$feature['phase']		= $phase;
							$feature['note']		= $notes;
 							array_push($features,$feature);
 							$notes = array();	
					  	}
		   			}
	       			break;
			}   			
		}
		
		return $features;
	}
	
	function getMaDAStypes($pid,$dsn,$segment){
	
		global $db;
				
		$strSQL= "SELECT SQL_CACHE t.tname 
					FROM das_commonserver_dsns d, das_commonserver_annotations a, das_commonserver_segments s, das_commonserver_types t, das_commonserver_features f
				   	WHERE f.iddas_commonserver_segments = s.iddas_commonserver_segments 
					  AND s.sname	='".$segment."'  
					  AND a.iddas_commonserver_annotations = f.iddas_commonserver_annotations 
					  AND a.status = 'ACTIVE'
					  AND a.idprojects = ".$pid."
					  AND a.iddas_commonserver_dsns = d.iddas_commonserver_dsns 
					  AND d.dname = '".$dsn."' 
					  AND t.iddas_commonserver_types = f.iddas_commonserver_types";   

		$results = $db->get_results($strSQL);
		
		$types = array();
		if ($results){
			foreach($results as $r){
				array_push($types,$r->tname);
			} 
		}
		return $types; 
	}	


	function getMaDASannotations($pid,$dsn,$psegment,$start,$end){
	
		global $db;
				
		$strSQL= "SELECT SQL_CACHE f.id,f.start,f.stop, f.method,f.orientation,f.version, t.tname, GROUP_CONCAT(n.text SEPARATOR '|') as notes, l.href,l.text 
FROM das_commonserver_dsns d, das_commonserver_annotations a, das_commonserver_segments s, das_commonserver_types t, das_commonserver_features f
				    LEFT JOIN  das_commonserver_notes n ON (f.iddas_commonserver_features = n.iddas_commonserver_features) 
				    LEFT JOIN  das_commonserver_links l ON (f.iddas_commonserver_features = l.iddas_commonserver_features) 
					WHERE f.iddas_commonserver_segments = s.iddas_commonserver_segments 
					  AND s.sname	='".$psegment."'  
					  AND a.iddas_commonserver_annotations = f.iddas_commonserver_annotations 
					  AND a.status = 'ACTIVE'
					  AND a.idprojects = ".$pid."
					  AND a.iddas_commonserver_dsns = d.iddas_commonserver_dsns 
					  AND d.dname = '".$dsn."' ";
		
		if ($end)
		  $strSQL .= " AND ( (start >= ".$start." AND stop <= ".$end.") OR (start <= ".$start." AND stop >= ".$end.") OR (start <= ".$start." AND stop <= ".$end." AND stop >=".$start." ) OR (start >= ".$start." AND stop >= ".$end." AND start <= ".$end." ) ) ";
		
		$strSQL .=	" AND t.iddas_commonserver_types = f.iddas_commonserver_types
			     GROUP BY f.iddas_commonserver_features";   
    
		$results = $db->get_results($strSQL);
		
		if ($results){
			
			$features = array();
		
			foreach($results as $r){
				
				
				$feature['sstart'] 		= $start;
    			$feature['sstop'] 		= $end;
				$feature['id']	 		= $r->id;
				$feature['tname'] 		= $r->tname;
				$feature['method'] 		= $r->method;
				$feature['score']		= $r->score;
				$feature['orientation']	= $r->orientation;	
				$feature['phase']		= $r->phase;
				$feature['start'] 		= $r->start;
				$feature['stop']		= $r->stop;

				if ($r->start < 1)
					$feature['start'] = 1;
				if ($r->stop < 1)
					$feature['stop'] = 1;	
					
				if ($r->notes){
					$notes 				= explode('|',$r->notes);
					$feature['note']	= $notes;
				}
				array_push($features,$feature);	
			} 
			return $features;
		}else{
			return array();
		}   
	}	
	
	
	
	function outputHeader(){
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
			header('Cache-Control: no-store, no-cache, must-revalidate');
			header('Cache-Control: post-check=0, pre-check=0', false);
			header('Pragma: no-cache');
			header("Content-Type: image/jpeg");   
	}
	
	
}