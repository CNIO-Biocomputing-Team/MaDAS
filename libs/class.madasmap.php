<?php
	###############################################################################
	#                      class.madasmap.php -  description                      #
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

class Madasmap{

  	function formatSize($size){
  	  
  	  $pre = '';
  	  if ($size >= 1000000){
	    return $pre.number_format(round($size/1000000)).' MB';
	   
	  }else if ($size >= 1000){
	      return $pre.number_format(round($size/1000)).' KB';
	  }else{
	      return $pre.number_format($size) . ' Bp';

	  }
  	}
  	
  	
  	/* get feature types */
	function getFeatureTypes() {
		
		global $db;
		$strSQL="SELECT * FROM das_commonserver_types ORDER BY tname";
		$types = $db->get_results($strSQL);
		return $types;
	}
	
	/* get Type */
	function getType($type) {

		global $db;

		$strSQL="SELECT * FROM das_commonserver_types WHERE tname = '".$type."'";
		$r = $db->get_row($strSQL);
/*
		if (!$r){
			$th = rand(1,360);

			$strSQL1 = "INSERT INTO das_commonserver_types SET tname ='".$type."', tcreated = now(), th = ".$th;
			$db->query($strSQL1);
			return $th; 

		}
*/
		return $r;
	}
	
	/* get dsn */
	function getDsns($pid) {
		
		global $db;
		$strSQL = "SELECT * FROM das_commonserver_dsns where projects_idprojects = ".$pid." ORDER BY dname";
				 
		$dsn = $db->get_results($strSQL);
		return $dsn;
	}
	
	
	/* get dsn  by Id */
	function getDsnById($id) {
		
		global $db;
		$strSQL = "select * from das_commonserver_dsns where iddas_commonserver_dsns = ".$id;
				 
		$dsn = $db->get_row($strSQL);
		return $dsn;
	}
	
	//get das server by Id
	function getDasById($id) {
		
		global $db;
		$strSQL = "SELECT * FROM das_commonserver_das_tracks WHERE iddas_commonserver_das_tracks = ".$id;
				 
		$das = $db->get_row($strSQL);
		return $das;
	}
	
	
	//store Segment
	function  storeSegment($iddsn,$idmolecule_type,$segment_name,$segment_size){
		
		global $db;
		$strSQL = "SELECT * 
					 FROM das_commonserver_segments d 
					WHERE d.sname = '".$segment_name."' AND iddas_commonserver_dsns = ".$iddsn;
				 
		$segment = $db->get_row($strSQL);
		if ($segment)
			return $segment->iddas_commonserver_segments;
		
		
		$strSQL = "INSERT INTO das_commonserver_segments
						   SET iddas_commonserver_dsns = ".$iddsn.",
							   sname = '".$segment_name."',
							   idmolecule_types = ".$idmolecule_type.",
							   sstart = 1,
							   sstop = ".$segment_size.",
							   sversion = '1',
							   created =  now()";

		$db->query($strSQL);
		
		return $db->getLastId();
	}
	
	
	
	//store annotation
	function  storeAnnotation($iddsn,$pid,$userid,$uname){
		
		global $db;
		global $c;
		
		$description = 'Manual annotatation by '.$uname. ' ['.date('F d Y').']'; 
		
		$strSQL = "SELECT * 
					 FROM das_commonserver_annotations d 
					WHERE description = '".$description."' AND iddas_commonserver_dsns = ".$iddsn;
				 
		$annot = $db->get_row($strSQL);
		if ($annot)
			return $annot->iddas_commonserver_annotations;
		
		
		$strSQL = "INSERT INTO das_commonserver_annotations
						   SET iddas_commonserver_dsns = ".$iddsn.",
							   idprojects = '".$pid."',
							   idusers = ".$userid.",
							   description = '".$c->preparesql($description)."',
							   status = 'ACTIVE',
							   modified = now(),
							   created =  now()";
							      

		$db->query($strSQL);
		
		return $db->getLastId();
	}

	
	/**
	 * This function can round a float or number by $precision digitst, taking in account the digits before te decimal point.
	 *
	 * @param int|float $number The number that needs to be rounded
	 * @param int $precision The number of digits to wich $number needs to be rounded.
	 * @return float
	 */
	function significantRound($number, $precision) {
	    if (!is_numeric($number)) {
	        throw new InvalidArgumentException('Argument $number must be an number.');
	    }
	    if (!is_int($precision)) {
	        throw new InvalidArgumentException('Argument $precision must be an integer.');
	    }
	    return round($number, $precision - strlen(floor($number)));
	}
	/**
	 * This function converts an color defined in the HSB/HSV color space to the equivalent colordefinition in the RGB color space.
	 *
	 * @uses significantRound()
	 * @param float|int $hue This parameter can be a integer or float between 0 and 360. Note that is makes no sense to pass through a float with more than three digits, because it is rounded at three digits.
	 * @param float|int $saturation This parameter can be a integer or float between 0 and 100. Note that is makes no sense to pass through a float with more than three digits, because it is rounded at three digits.
	 * @param float|int $brightness This paramater can be a integer or float between 0 and 100. Note that it makes no sense to pass through a float with more than trhee digits, because it is rounded at three digits.
	 * @return array|boolean On succes, this function returns an array with elements 'red' 'green' and 'blue', containing integers with a range from 0 to 255. On failure this function returns false.
	 */
	function hsbToRgb($hue, $saturation, $brightness) {
	    $hue = $this->significantRound($hue, 3);
	    if ($hue < 0 || $hue > 360) {
	        throw new LengthException('Argument $hue is not a number between 0 and 360');
	    }
	    $hue = $hue == 360 ? 0 : $hue;
	    $saturation = $this->significantRound($saturation, 3);
	    if ($saturation < 0 || $saturation > 100) {
	        throw new LengthException('Argument $saturation is not a number between 0 and 100');
	    }
	    $brightness = $this->significantRound($brightness, 3);
	    if ($brightness < 0 || $brightness > 100) {
	        throw new LengthException('Argument $brightness is not a number between 0 and 100.');
	    }
	    $hexBrightness = (int) round($brightness * 2.55);
	    if ($saturation == 0) {
	        return array('red' => $hexBrightness, 'green' => $hexBrightness, 'blue' => $hexBrightness);
	    }
	    $Hi = floor($hue / 60);
	    $f = $hue / 60 - $Hi;
	    $p = (int) round($brightness * (100 - $saturation) * .0255);
	    $q = (int) round($brightness * (100 - $f * $saturation) * .0255);
	    $t = (int) round($brightness * (100 - (1 - $f) * $saturation) * .0255);
	    switch ($Hi) {
	        case 0:
	            return array('red' => $hexBrightness, 'green' => $t, 'blue' => $p);
	        case 1:
	            return array('red' => $q, 'green' => $hexBrightness, 'blue' => $p);
	        case 2:
	            return array('red' => $p, 'green' => $hexBrightness, 'blue' => $t);
	        case 3:
	            return array('red' => $p, 'green' => $q, 'blue' => $hexBrightness);
	        case 4:
	            return array('red' => $t, 'green' => $p, 'blue' => $hexBrightness);
	        case 5:
	            return array('red' => $hexBrightness, 'green' => $p, 'blue' => $q);
	    }
	    return false;
	}

	
	function madas_search($query,$pid,$dsn,$psegment){
	
		global $db;
		global $time_start;
		
		$types = array();
		$pockets = array();		
		
		$strSQL= "SELECT SQL_CACHE f.id,f.start,f.stop, f.method,f.orientation,f.version, t.tname, t.th, t.showas, GROUP_CONCAT(n.text SEPARATOR '|') as notes, d.dname , s.sname,d.iddas_commonserver_dsns,s.iddas_commonserver_segments, s.sstart, s.sstop
FROM das_commonserver_dsns d, das_commonserver_annotations a, das_commonserver_segments s, das_commonserver_types t, das_commonserver_features f
					LEFT JOIN das_commonserver_notes n ON (f.iddas_commonserver_features = n.iddas_commonserver_features) 
					WHERE f.iddas_commonserver_segments = s.iddas_commonserver_segments 
					  AND a.iddas_commonserver_annotations = f.iddas_commonserver_annotations 
					  AND a.status = 'ACTIVE'
					  AND a.idprojects = ".$pid."
					  AND a.iddas_commonserver_dsns = d.iddas_commonserver_dsns";
					  
	    $strSQL .=	" AND (f.id LIKE '%".$query."%' OR n.text LIKE '%".$query."%')";
	
		$strSQL .=	" AND t.iddas_commonserver_types = f.iddas_commonserver_types
			     GROUP BY f.iddas_commonserver_features 
			     ORDER BY d.dname,s.sname
			     LIMIT 0,50";   

		$features = $db->get_results($strSQL);
	
		return $features;
	}
	
	
	function pubmed_search($query){
	
		if (!$query or !is_numeric($query))
			return 0;
		$features = array();
	
		$m = new xmlrpcmsg('Annotations.getAnnotations',array(new xmlrpcval($query, "int")));
		$c = new xmlrpc_client("http://bcms.bioinfo.cnio.es/xmlrpc/");
		
		$r = $c->send($m);
 		if (!$r->faultCode()) {
 		    $v = $r->value();
 		    
 		    $results =  php_xmlrpc_decode($r->value());
 		    //echo var_export($results);
 		    
 		    $i = 0;
 		    $mentions = array();
 		    $proteins = array(); 		    
 		    
 		    foreach ($results as $a){
 		    	

 		    	
 		    	foreach ($a as $b=>$c){
 		    		
 		    		//mentions
 		    		if (trim($b) == 'mentions'){
	 		    		foreach ($c as $d){
	 		    			if (is_array($d)){
	 		    				foreach ($d as $e =>$f){
	 		    					//echo $e.': '.$f.'<br>';
	 		    					if ($e == 'mention'){
	 		    						array_push($mentions,$f);
	 		    											
	 		    					}
	 		    				}
	 		    			}else{
	 		    				//echo $d.'<br>';
	 		    			}
	 		    		}
 		    		}
 		    		//proteins
 		    		if (trim($b) == 'normalizations'){
	 		    		//echo '<b>'.$b.'</b><br>';
	 		    		foreach ($c as $d){
	 		    			if (is_array($d)){
	 		    				foreach ($d as $e =>$f){
	 		    					//echo $e.': '.$f.'<br>';
	 		    					if ($e == 'dbid'){
	 		    						array_push($proteins,$f);
	 		 
	 		    					}
	 		    				}
	 		    			}else{
	 		    				//echo $d.'<br>';
	 		    			}
	 		    		}
 		    		}
 		    		
 		    		
 		    	}
 		    	//echo '-------<br>';
 		    	$features[$i]->id 		= $query;
 		    	$features[$i]->notes 	= implode(' ... ',array_unique($mentions));
 		    	$features[$i]->proteins	= array_unique($proteins);
  		    }
		    
 		    
 		} else {
 		    print "Fault <BR>";
 		    print "Code: " . htmlentities($r->faultCode()) . "<BR>" .
 		          "Reason: '" . htmlentities($r->faultString()) . "'<BR>";
 		}
 		
 		
 		
 		return $features;
	}
	
	
	function query_biomart($proteins){
	
		global $c;
		
		$host 	= 'www.biomart.org';
		$script = '/biomart/martservice';
		$data	= 'query=<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE Query>
<Query  virtualSchemaName = "default" formatter = "CSV" header = "0" uniqueRows = "1" count = "" datasetConfigVersion = "0.6" >
			
	<Dataset name = "hsapiens_gene_ensembl" interface = "default" >
		<Filter name = "uniprot_sptrembl" value = "'.$proteins.'"/>
		<Attribute name = "ensembl_gene_id" />
		<Attribute name = "start_position" />
		<Attribute name = "end_position" />
		<Attribute name = "chromosome_name" />
	</Dataset>
</Query>';

	  $response =  $c->post_data($host,$script,$data);
	  $results	=  split('text/plain',$response);
	  $temp		=  split("\n",$results[1]);
	  
	  return $temp;
	}
	
	function highlight_query($query,$text){
		return str_replace($query,'<span style="color:#FFFFFF;background-color:#FE6500">'.$query.'</span>',$text);
	}	
}
?>