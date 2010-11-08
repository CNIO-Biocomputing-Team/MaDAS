<?
	###############################################################################
	#                      class.PaintSearch.php -  description                   #
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

class PaintSearch{

	function paintMadas($pid,$query,$results,$img_height){
	
		global $db;
		global $map;
		
		if ($results)
			foreach ($results as $r){
			
				$type_pos = $img_height-$r->th-48;
				
				if ($ae)
					switch (trim($r->showas)){
					  	
				  		case 'Probe':
				  			$type_pos = $pstart+5;
				  			break;	
				  			
				  		case 'Down regulated Gene':
				  			$type_pos = $pstop+5;
				  			break;	
				  			
				  		case 'Up regulated Gene':
				  			$type_pos = $pstart-5;
				  			break;	
			  		}
			
				echo '<div class="item" style="padding-bottom:20px;">';
				echo '<table>';
				
				echo '<tr>';
				echo '<td style="font-weight:bold;">'.$map->highlight_query($query,$r->tname).' '.$map->highlight_query($query,$r->id).'</td>';
				echo '</tr>';
				
				//dsn
				echo '<tr>';
				echo '<td style="color:#666666;">'.$r->dname.'</td>';
				echo '</tr>';

				//notes
				echo '<tr>';
				echo '<td style="color:#666666;">'.$map->highlight_query($query,$r->notes).'</td>';
				echo '</tr>';
				
				echo '<tr>';
				echo '<td style="color:#666666;font-size:10px;">'.$r->sname.' '.number_format($r->start).' - '.number_format($r->stop).' <a href="#" onclick="goToBp('.$pid.',\''.$r->dname.'\',\''.$r->sname.'\','.($r->sstop-$r->sstart).','.$r->start.','.$type_pos.')">[show in Madasmap]</a></td>';
				echo '</tr>';
				
				echo '</table>';
				echo '</div>';
			}
			
		
	}
	
	
	function paintPubmed($query,$results,$img_height){
	
		global $db;
		global $map;
		
		if ($results)
			foreach ($results as $r){
			
				$type_pos = $img_height-$r->th-48;
				
				if ($ae)
					switch (trim($r->showas)){
					  	
				  		case 'Probe':
				  			$type_pos = $pstart+5;
				  			break;	
				  			
				  		case 'Down regulated Gene':
				  			$type_pos = $pstop+5;
				  			break;	
				  			
				  		case 'Up regulated Gene':
				  			$type_pos = $pstart-5;
				  			break;	
			  		}
			
				echo '<div class="item" style="padding-bottom:20px;">';
				echo '<table border="0" cellpadding="5">';
				
				echo '<tr>';
				echo '<td style="width:200px;">PUBMED ID</td>';
				echo '<td><a href="http://www.ncbi.nlm.nih.gov/pubmed/'.$r->id.'" target="_blank">'.$r->id.'</a></td>';
				echo '</tr>';
				
				echo '<tr>';
				echo '<td>RELEVANT PHRASES</td>';
				echo '<td style="color:#666666;">'.$map->highlight_query($query,$r->notes).'</td>';
				echo '</tr>';
				
				echo '<tr>';
				echo '<td>INFERRED PROTEIN LIST</td>';
				echo '<td style="color:#666666;">';
				echo implode(' ',$r->proteins);
				echo '</td>';
				echo '</tr>';
				
				
				echo '<tr>';
				echo '<td>INFERRED GENE LIST</td>';
				echo '<td style="color:#666666;">';
				
				$results =  $map->query_biomart(trim(implode(',',$r->proteins)));
				$this->paint_biomart($results);
								
				echo '</td>';
				echo '</tr>';
				

				

				
				echo '</table>';
				echo '</div>';
			}
		
	}
	
	function paint_biomart($results){

	  	foreach($results as $t){
	  		$temp1 = explode(',',$t);
	  		if ($temp1[1])
	  			echo '<span style="color:#000000;font-weight:bold;">'.$temp1[0].'</span> '.number_format($temp1[1]).' - '.number_format($temp1[2]).' <a href="#" onclick="pubmedPopup('.$temp1[1].',200)">[show in Madasmap]</a><br>';
	  	}
	}
}
?>