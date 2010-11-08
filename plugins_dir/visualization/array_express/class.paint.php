<?php
	###############################################################################
	#                      class.paint.php -  description                         #
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

class Paint{

	function paintRuler($sstart,$sstop,$img_width,$img_height){
	
		global $im;
		
		$ligth	= imagecolorallocate($im, 205, 205, 205);
		$dark	= imagecolorallocate($im, 105, 105, 105);
		
		$c = 0;
		$wzoom = $sstop-$sstart;
		$step = round($wzoom/2);
		$wstep = round($step*$img_width/$wzoom);
		
		
		//horizontal
		for($j=$img_height/4;$j<=$img_height;$j+=$img_height/4){
			imageline($im,0,$j,$img_width,$j,$ligth);
		}
		
		//vertical
		for($i=$sstart;$i<$sstop;$i+=$step){
		    
		    imageline($im,$c,0,$c,$img_height,$ligth);
		    if ($step >= 1000000){
		        $label = number_format(round($i/1000000)).' MB';
		        
		    }else if ($step >= 1000){
		        
		        $label = number_format(round($i/1000)).' KB';
		    }else{
		        $label = number_format($i);
		    }
		    if ($img_width > 10)
		    	imagestring($im, 2, $c+5, 5, $label, $dark);
		    $c+=round($img_width/2);
		}
		
	}


	function paintFeatures($as_types,$pocket,$img_width,$img_height,$sstart,$sstop,$qvalue){
	
		global $im;
		global $map;
		global $com;
		
		$wzoom = $sstop-$sstart;

		$dark		= imagecolorallocate($im, 105, 105, 105);
	  	$orange  	= imagecolorallocate($im, 254, 101,0);
		
		//TYPES
		$types	  		= array();
		$s_types  		= explode(',',$_SESSION['types']);
	    $types 			= array_unique(array_merge($s_types,$as_types));
	    if ($_COOKIE['view_types']){
	    	$view_types = explode(',',$_COOKIE['view_types']);
	    }
	    $_SESSION['types'] = implode(',',$types);
	    
		foreach ($types as $k){
		    
		    if (!$_COOKIE['view_types'] || in_array($k,$view_types)){
		   
			    $v = $pocket[$k];
			  	$type = $map->getType($com->preparesql($k));

	  		
			    //POCKETS
			    if ($v){
			        foreach ($v as $a=>$b){
			        
			        	$tmp 			= explode('-',$a);
			        	$frstart		= (int) $tmp[0];
			        	$frstop 		= (int) $tmp[1];
			        
			        	$partial = '';
			        	
			        	//correction to the window request
			        	//orientation +
			        	if ($frstart < $frstop){
			        	
							if ($frstart <	$sstart){
								$fstart = 0;
								$partial = 'left';
							}else{
								$fstart = $frstart - $sstart;	
							}
							if ($frstop > $sstop){
								$fstop = $sstop-$sstart;
								$partial = 'right';
							}else{
								$fstop = $frstop -  $sstart;	
							}
							if ($frstart <	$sstart and $frstop > $sstop)
				        		$partial = 'all';
			        		
			        	}else {	
			        		
						//orientation -
						if ($frstop <	$sstart){
							$fstop = 0;
							$partial = 'left';
						}else{
							$fstop = $frstop - $sstart;	
						}
						if ($frstart > $sstop){
							$fstart = $sstop-$sstart;
							$partial = 'right';
						}else{
							$fstart = $frstart -  $sstart;	
						}
						if ($frstop <	$sstart and $frstart > $sstop)
			        		$partial = 'all';
						
						}
       				  	
       				  	//scale to windows 
					  	$start 	= round($fstart*$img_width/$wzoom);
					  	$stop   = round($fstop*$img_width/$wzoom);
					  	$size 	= $stop-$start;
					  	
					  	if ($size < 1)
					  		$size = 1;
						
					
		         	 	$this->paintTypeImg($b,$im,$type,$start,$start+$size,$qvalue,$partial);
			        }
			    }
		    }
		}
	}
	
	function paintTypeImg($feature,$im,$type,$xstart,$xstop,$qvalue,$partial){
	  	
	  	global $map;
	  	global $com;
	  	
	  	
	  	$gray   = imagecolorallocate($im, 224, 224, 224);
	  	
	  	$notes 	= explode('|',$feature->notes);
	  	$pstart = 255;
	  	$pstop  = 275;
	  	$y  	= 0;
	  	$sig    = 0;
	  	
	  	foreach ($notes as $n){

	 		if (preg_match('/LOGFC:/',$n)){
	 			$logfc =  str_replace(array('LOGFC:',','),array('','.'),$n);
	 			$tmp	   =  abs(round(floatval($logfc)*10000));
	 			$y		   =  round($tmp*255/10000);
	 		}
	 		if (preg_match('/QVALUE:/',$n)){
	 			$rqvalue 	=  str_replace(array('QVALUE:',','),array('','.'),$n);
	 			$tmp1	   	=  floatval($rqvalue);
	 			if ($tmp1 < floatval($qvalue))
	 				$sig = 1;
	 		}  		
	  	}
	 
	  	  			
	  	switch (trim($type->showas)){
			  	
	  		case 'Probe':
	  			$darkyellow   = imagecolorallocate($im, 175, 140, 29);
				$yellow 	  = imagecolorallocate($im, 255, 207, 66);
				imagefilledrectangle($im,$xstart,$pstart+5,$xstop,$pstop-5,$yellow);
	  			imagerectangle($im,$xstart,$pstart+5,$xstop,$pstop-5,$darkyellow);
	  			break;
	  			
	  			
	  			
	  		case 'Down regulated Gene':
	  			$darkblue   = imagecolorallocate($im, 90, 103, 168);
				$blue   	= imagecolorallocate($im, 118, 135, 222);
				
				$color	= ($sig)?$blue:$gray;
				$dcolor = ($sig)?$darkblue:$gray;
				
				if ($sig)
					imagefilledrectangle($im,$xstart,$pstop,$xstop,$pstop+$y,$color);
				imagerectangle($im,$xstart,$pstop,$xstop,$pstop+$y,$dcolor);
	  			
	  			break;	
	  			
	  		case 'Up regulated Gene':
	  			$darkred   	= imagecolorallocate($im, 178, 71, 71);
				$red 	 	= imagecolorallocate($im, 255, 76, 76);
				
				$color	= ($sig)?$red:$gray;
				$dcolor = ($sig)?$darkred:$gray;
				
				if ($sig)
					imagefilledrectangle($im,$xstart,$pstart-$y,$xstop,$pstart,$color);
	  			imagerectangle($im,$xstart,$pstart-$y,$xstop,$pstart,$dcolor);
	  			break;	
	  	}
	}
	
	function paintFeatureDetails($as_types,$pocket,$h,$img_height){
	
		global $map;
		global $com;
						    
		if ($as_types){
		
				
			//TYPES
			$types	  		= array();
			$s_types  		= explode(',',$_SESSION['types']);
		    $types 			= array_unique(array_merge($s_types,$as_types));
		    
		    if ($_COOKIE['view_types']){
		    	$view_types = explode(',',$_COOKIE['view_types']);
		    }
		    
		    $_SESSION['types'] = implode(',',$types);
		    
		    
	
			foreach ($types as $k){
			   	
			   	$type = $map->getType($com->preparesql($k));
			    if (($h <255 and $type->showas == 'Up regulated Gene') or ($h > 255 and $h <275 and $type->showas == 'Probe') or ($h >275 and $type->showas == 'Down regulated Gene')){
			   		
			   		$v = $pocket[$k];
			
				  	//POCKETS
				    if ($v){
				    	echo '<div class="fdetails">';
				        foreach ($v as $a=>$b){
				        	
				        	$notes = array();
   		    				$notes = explode('|',$b->notes);
   		    				
				   		    echo '<table  style="border-bottom:1px dotted #E0E0E0"margin-left:10px;>';
							echo '<tr><td align="left" class="label">ID</td><td align="left" class="gray">'.$com->declean_str_type($b->id).'</td></tr>';
							echo '<tr><td align="left" class="label">Type</td><td align="left" class="gray">'.$com->declean_str_type($b->tname).'</td></tr>';
							echo '<tr><td align="left" class="label">Method</td><td align="left" class="gray">'.$com->declean_str_type($b->method).'</td></tr>';
							echo '<tr><td align="left" class="label">Start</td><td align="left" class="gray">'.number_format($b->start).'</td></tr>';
							echo '<tr><td align="left" class="label">End</td><td align="left" class="gray">'.number_format($b->stop).'</td></tr>';
							echo '<tr><td align="left" class="label">Score</td><td align="left" class="gray">'.$b->score.'</td></tr>';
							echo '<tr><td align="left" class="label">Orientation</td><td align="left" class="gray">';
							if ($b->orientation == '+')
								echo '<img src="/plugins_dir/visualization/madasmap/img/plus.gif" title="+">';
							else if ($b->orientation == '-')
								echo '<img src="/plugins_dir/visualization/madasmap/img/minus.gif" title="-">';
									
							echo '</td></tr>';
							echo '<tr><td align="left" class="label">Phase</td><td align="left" class="gray">'.$b->phase.'</td></tr>';

							while($n = array_pop($notes)){
								if ($n != ':')
									echo '<tr><td align="left" class="label">Note</td><td align="left" class="gray">'.$n.'</td></tr>';
							}	
							echo '<tr><td align="left" class="label">&nbsp;</td><td align="left" class="gray"><a href="#" onclick="$jQ(\'...\').editF(\''.base64_encode($com->declean_str_type($b->id).','.$com->clean_str_type($b->tname).','.$b->start.','.$b->stop.','.$b->score.','.$b->orientation.','.$b->phase.','.str_replace(',','|',$b->notes).',1').'\')">[Edit]</a></td></tr>';
	
							echo '</table>';
				        }
				        echo '</div>';
				    }
			    }
			}
			
		
		}

		
	}



}