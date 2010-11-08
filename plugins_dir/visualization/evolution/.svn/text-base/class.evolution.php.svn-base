<?php
	###############################################################################
	#                      class.evolution.php -  description                     #
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

class Evolution{

	
	function drawPlot($circles){
	
		global $db;
		
		$types = array();
		$pockets = array();
		
		
		$tick_y = '[';
		$strSQL = "SELECT s.order, s.TAXNAME, s.path
				     FROM datasource_treefam_species_tree s 
				    order BY s.order ";		  
		$features = $db->get_results($strSQL);
		
		if ($features){
			foreach($features as $f){
					
					$tick_y .= '"'.$f->TAXNAME.'",';
			} 
		}
		$tick_y = substr($tick_y,0,strlen($ticy_y)-1);	
		$tick_y .= ']';
		

		//echo $tick_y.'<br>';
		if ($circles == 'genes')
			$select = "s.order, s.TAXNAME, s.path, count(n.nodeid) as number";
		else if ($circles == 'trees')
			$select = "s.order, s.TAXNAME, s.path, count(DISTINCT n.tree) as number";
		
		//human
		$inf_human 		= '[';	
		$strSQL = "SELECT ".$select." 
				     FROM datasource_treefam_inferred_nodes n, datasource_treefam_species_tree s 
				    WHERE n.TAX_ID = s.TAX_ID 
				      AND n.flagged=1 
				      AND s.TAX_ID IN (33213,33316,7711,117571,32523,32524,32525,9347,314146,9526,207598,9606)
				    GROUP BY n.TAX_ID
				    order BY s.path ";		  


		$features = $db->get_results($strSQL);
		
		if ($features){
			$i = 1;	
			foreach($features as $f){
					$radius = $f->number;
					$path_length = count(explode('/',$f->path));
					$inf_human .= '['.$path_length.','.$f->order.','.$radius.'],' ;
					$i++;
			
			} 
		}

		$inf_human = substr($inf_human,0,strlen($inf_human)-1);	
		$inf_human .= ']';
		
		
			
		
		
		
		
		$inf_danio 		= '[';	
		
		//danio
		$strSQL = "SELECT ".$select."
				     FROM datasource_treefam_inferred_nodes n, datasource_treefam_species_tree s 
				    WHERE n.TAX_ID = s.TAX_ID 
				      AND n.flagged=1 
				      AND s.TAX_ID IN (33213,33316,7711,117571,186625,7955)
				    GROUP BY n.TAX_ID
				    order BY s.path ";		  


		$features = $db->get_results($strSQL);
		
		
		if ($features){
			
			$i = 1;	
			foreach($features as $f){
					$radius = $f->number;
					$path_length = count(explode('/',$f->path));
					$inf_danio .= '['.$path_length.','.$f->order.','.$radius.'],' ;
					$i++;
			
			} 
		}
		
		$inf_danio = substr($inf_danio,0,strlen($inf_danio)-1);	
		$inf_danio .= ']';
		
		
		$inf_celegans 		= '[';	
		
		//celegans
		$strSQL = "SELECT ".$select." 
				     FROM datasource_treefam_inferred_nodes n, datasource_treefam_species_tree s 
				    WHERE n.TAX_ID = s.TAX_ID 
				      AND n.flagged=1 
				      AND s.TAX_ID IN (33213,6237,6239)
				    GROUP BY n.TAX_ID
				    order BY s.path ";		  


		$features = $db->get_results($strSQL);
		
		
		if ($features){
			
			$i = 1;	
			foreach($features as $f){
					$radius = $f->number;
					$path_length = count(explode('/',$f->path));
					$inf_celegans .= '['.$path_length.','.$f->order.','.$radius.'],' ;
					$i++;
			
			} 
		}
		
		$inf_celegans = substr($inf_celegans,0,strlen($inf_celegans)-1);	
		$inf_celegans .= ']';



		$inf_rat		= '[';	
		
		//rat
		$strSQL = "SELECT ".$select." 
				     FROM datasource_treefam_inferred_nodes n, datasource_treefam_species_tree s 
				    WHERE n.TAX_ID = s.TAX_ID 
				      AND n.flagged=1 
				      AND s.TAX_ID IN (33213,33316,7711,117571,32523,32524,32525,9347,314146,39107,10116)
				    GROUP BY n.TAX_ID
				    order BY s.path ";		  


		$features = $db->get_results($strSQL);
		
		
		if ($features){
			
			$i = 1;	
			foreach($features as $f){
					$radius = $f->number;
					$path_length = count(explode('/',$f->path));
					$inf_rat .= '['.$path_length.','.$f->order.','.$radius.'],' ;
					$i++;
			
			} 
		}
		
		$inf_rat = substr($inf_rat,0,strlen($inf_rat)-1);	
		$inf_rat .= ']';
		
		
		//Monodelphis_domestica
		$inf_Monodelphis_domestica 		= '[';	
		$strSQL = "SELECT ".$select."
				     FROM datasource_treefam_inferred_nodes n, datasource_treefam_species_tree s 
				    WHERE n.TAX_ID = s.TAX_ID 
				      AND n.flagged=1 
				      AND s.TAX_ID IN (33213,33316,7711,117571,32523,32524,32525,13616)
				    GROUP BY n.TAX_ID
				    order BY s.path ";		  


		$features = $db->get_results($strSQL);
		
		if ($features){
			$i = 1;	
			foreach($features as $f){
					$radius = $f->number;
					$path_length = count(explode('/',$f->path));
					$inf_Monodelphis_domestica .= '['.$path_length.','.$f->order.','.$radius.'],' ;
					$i++;
			
			} 
		}

		$inf_Monodelphis_domestica = substr($inf_Monodelphis_domestica,0,strlen($inf_Monodelphis_domestica)-1);	
		$inf_Monodelphis_domestica .= ']';
		
		
	
		return array($i,$inf_human,$inf_danio,$inf_celegans,$inf_rat,$inf_Monodelphis_domestica,$tick_y);
	}
	
	
	
	function getData($tag,$species,$log,$display,$groups) {
		
		global $db;
		$series = array();
		
		$tick_y = '[';
/*
		$strSQL = "SELECT DISTINCT v.string_value
					 FROM datasource_treefam_tags t, datasource_treefam_tag_values v 
					WHERE t.tag = '".$tag."' AND t.id = v.idtag";	
		
		$features = $db->get_results($strSQL);
		
		if ($features){
				$i = 1;	
				foreach($features as $f){
						$tick_y .= '"'.$f->string_value.'",';
						$i++;
				} 
		}
		
		
		$tick_y = substr($tick_y,0,strlen($ticy_y)-1);	
*/
		$tick_y .= ']';


		foreach ($species as $s){

			$crc = array();
			$feature 		= '[';	
					   
			$strSQL = "SELECT SQL_CACHE CRC32(v.string_value) as crc, v.string_value, count(DISTINCT n.tree_node_id) as number , n.TAX_ID as nodetax
						 FROM datasource_treefam_tag_values v, datasource_treefam_genes g, datasource_treefam_inferred_node_label l, datasource_treefam_inferred_nodes n ";
						 
			if ($groups)
				$strSQL .= ", datasource_treefam_groups_labels gl ";			 
						 
						 
			$strSQL .=	" WHERE n.TAX_ID = ".$s;
			
			if ($groups)
				$strSQL .= " AND gl.idgroup= ".$groups."
							AND gl.IDX = g.IDX ";
			 
			$strSQL .= " AND v.idtag = '".$tag."'
						 AND v.GID = g.GID
						 AND g.IDX = l.lavel_value
						 AND n.nodeid=l.nodeid
						 GROUP BY  v.string_value
						 HAVING count(DISTINCT n.tree_node_id) >= ".$log."
						 ORDER BY CRC32(v.string_value)";		   			  
	
	
			$features = $db->get_results($strSQL);
			
			if ($features){
				foreach($features as $f){
						$radius = 5;
						array_push($crc,$f->crc);
						$feature .= '['.$f->crc.','.($f->number).','.$radius.'],' ;
						
				} 
			}
	
			$feature = substr($feature,0,strlen($feature)-1);	
			$feature .= ']';
			
			if ($features)
			$series[$s] = $feature;
			
			if ($display == 'Differences'){
				if (count($differences)){
					$d1 = array_diff($crc,$differences);
					$d2 = array_diff($differences,$crc);
					$differences = array_merge($d1,$d2);
				}else{
					$differences = $crc;
				}	
			}
	
		}
		
		//remove tags
		if ($display == 'Differences'){
			
			foreach ($series as $k=>$se){
				
				$newFeature = '[[';			
				$tmp = explode('],[',$se);
				
				
				foreach ($tmp as $t){
					    
					$tmp1 = explode(',',$t);
					
					if (in_array(str_replace('[','',$tmp1[0]),$differences)){
						//echo str_replace('[','',$tmp1[0]).' : '.implode(',',$differences).'<br>';
						
						$newFeature .= str_replace(array('[',']'),'',$t).'],[';
					}
					
				}
				
				$newFeature = substr($newFeature,0,strlen($newFeature)-2);	
				$newFeature .= ']';
				
				
				if (strlen($newFeature) > 3){
					//echo '----->'.$newFeature.'<br>';
					$newSeries[$k] = $newFeature;
				}
				
			}
			
			return $newSeries;
		}
		
		return $series;
	
	}	


}