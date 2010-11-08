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

	function paintRuler($sstart,$sstop,$tile_width,$img_height){
	
		global $im;
		
		$ligth	= imagecolorallocate($im, 205, 205, 205);
		$dark	= imagecolorallocate($im, 105, 105, 105);
		
		$c = 0;
		$wzoom = $sstop-$sstart;
		$step = round($wzoom/2);
		$wstep = round($step*$tile_width/$wzoom);
		
		//vertical
		for($i=$sstart;$i<$sstop;$i+=$step){
		    
		    imageline($im,$c,0,$c,$img_height,$ligth);
		    if ($step >= 1000000){
		        $label = number_format(round($i/1000000)).' MB';
		        
		    }else if ($step >= 1000){
		        
		        $label = number_format(round($i/1000)).' KB';
		    }else{
		        $label = number_format($i).' Bp';
		    }
		    if ($tile_width > 10 and $i == $sstart)
		    	imagestring($im, 2, $c+5, 5, $label, $dark);
		    $c+=round($tile_width/2);
		}
	}
	
	function paintFeatures($types,$features,$tile_width,$img_height,$sstart,$sstop){
	
		global $im;
		global $map;
		global $com;
		
		$wzoom		= $sstop-$sstart;
		$dark		= imagecolorallocate($im, 105, 105, 105);
	  	$orange  	= imagecolorallocate($im, 254, 101,0);
	  	foreach ($features as $f){
	  		//echo count($types).':'.$f['tname'].': '.array_search($f['tname'],$types).'<br>';
	  		$feature = $this->scaleToTile($f,$sstart,$sstop,$tile_width,$wzoom);
			$this->paintFeature(array_search($f['tname'],$types),$feature);
	  	}

	}


	function scaleToTile($f,$sstart,$sstop,$tile_width,$wzoom){
	
		//correction to the window request
    	//orientation +
    	if ($f['start'] < $f['stop']){
    	
			if ($f['start'] <	$sstart){
				$f['tileStart'] = 0;
				$f['partial'] = 'left';
			}else{
				$f['tileStart'] = $f['start'] - $sstart;
			}
			if ($f['stop'] > $sstop){
				$f['tileStop'] = $sstop-$sstart;
				$f['partial'] = 'right';
			}else{
				$f['tileStop'] = $f['stop'] - $sstart;	
			}
			if ($f['start'] <	$sstart and $f['stop'] > $sstop)
        		$f['partial'] = 'all';
    			  					
    	}else {	
    		
		//orientation -
		if ($f['stop'] <	$sstart){
			$f['tileStop'] = 0;
			$f['partial'] = 'left';
		}else{
			$f['tileStop'] = $f['stop'] - $sstart;	
		}
		if ($f['start'] > $sstop){
			$f['tileStart'] = $sstop-$sstart;
			$f['partial'] = 'right';
		}else{
			$f['tileStart'] = $f['start'] -  $sstart;	
		}
		if ($f['stop'] <	$sstart and $f['start'] > $sstop)
    		$f['partial'] = 'all';
		}
		  	
		//scale to windows 
	  	$f['tileStart']	= round($f['tileStart']*$tile_width/$wzoom);
	  	$f['tileStop']	= round($f['tileStop']*$tile_width/$wzoom);

	  	return $f;
	}
	
	function paintFeature($track,$f){
	
		global $map;
	  	global $com;
	  	global $im;
	  	global $types;
	  	
		$t	= $map->getType($f['tname']);	  	
	  	$B	= 90;
		$S 	= 100;
		$H	= $t->th;
		
		if (!$H || $H>360)
			$H = 360;
	  	
		switch ($f['tname']){
		
			case 'reference':
			
				$color = $map->hsbToRgb($H,$S,$B);
				$dark  = $map->hsbToRgb($H,$S,$B-20);
				$fill   = imagecolorallocate($im, 118, 135, 222);
				$border	= imagecolorallocate($im, 255, 255, 255);
				
				$trackPos = 20;
				imagefilledrectangle($im,$f['tileStart'],$trackPos,$f['tileStop'],$trackPos+15,$fill);
				
				$this->paintSequence($im,$border,$fill,$f,$trackPos);
				break;
	  	
	  		case 'Gene':
/*
	  			$darkyellow   = imagecolorallocate($im, 175, 140, 29);
				$yellow 	  = imagecolorallocate($im, 255, 207, 66);
				
				
				if ($xstop-$xstart >5){
				    
		  		    imagefilledpolygon($im,array($xstart,480,$xstart,455,$xstop-5,455,$xstop,467,$xstop-5,480),5,$yellow);
		  		    imagepolygon($im,array($xstart,480,$xstart,455,$xstop-5,455,$xstop,467,$xstop-5,480),5,$darkyellow);	
				    
				    if ($f['partial'] == 'all'){
				    
				    	imagefilledrectangle($im,$xstart,455,$xstop,480,$yellow);
				    	imageline($im,$xstart,455,$xstop,455,$darkyellow);
				    	imageline($im,$xstart,480,$xstop,480,$darkyellow);
				    
				    }else if ($f['partial'] == 'left'){
				    	
				    	imageline($im,$xstart,455,$xstart,480,$yellow);
				    
				    }else if ($f['partial'] == 'right'){
				    
				    	imagefilledrectangle($im,$xstop-5,455,$xstop,480,$yellow);
				    	imageline($im,$xstop-5,455,$xstop,455,$darkyellow);
				    	imageline($im,$xstop-5,480,$xstop,480,$darkyellow);
				    	

				    }
				    
				}else if ($xstart - $xstop >5){
				    
		  		    imagefilledpolygon($im,array($xstart,480,$xstart,455,$xstop,455,$xstop-5,467,$xstop,480),5,$yellow);
		  		    imagepolygon($im,array($xstart,480,$xstart,455,$xstop,455,$xstop-5,467,$xstop,480),5,$darkyellow);	
				    
				    if ($f['partial'] == 'all'){
				    
				    	imagefilledrectangle($im,$xstart,455,$xstop,480,$yellow);
				    	imageline($im,$xstart,455,$xstop,455,$darkyellow);
				    	imageline($im,$xstart,480,$xstop,480,$darkyellow);
				    
				    }else if ($f['partial'] == 'left'){
				    	
				    	imagefilledrectangle($im,$xstop-5,455,$xstop,480,$yellow);
				    	imageline($im,$xstop-5,455,$xstop,455,$darkyellow);
				    	imageline($im,$xstop-5,480,$xstop,480,$darkyellow);
				    
				    }else if ($f['partial'] == 'right'){
				    
				    	imageline($im,$xstart,455,$xstart,480,$yellow);
				    }
				    
   
				}else{
				
				    imagefilledrectangle($im,$xstart,455,$xstop,480,$yellow);
		  		    imagerectangle($im,$xstart,455,$xstop,480,$darkyellow);
		  		}
*/

	  			break;
	  			
	  		case 'Promoter':
	  			
/*
	  			$black   = imagecolorallocate($im, 0, 0, 0);
	  			
	  			imagefilledrectangle($im,$xstart-1,445,$xstart+1,488,$black);
	  			imagefilledrectangle($im,$xstart-1,443,$xstart+20,445,$black);
*/
	  			break;	
	  		
	  		case 'Probe':
/*
	  			$darkyellow   = imagecolorallocate($im, 175, 140, 29);
				$yellow 	  = imagecolorallocate($im, 255, 207, 66);
				imagefilledrectangle($im,$xstart,$ystart-5,$xstop,$ystop,$yellow);
	  			imagerectangle($im,$xstart,$ystart-5,$xstop,$ystop,$darkyellow);
	  			//imagecopyresampled($im, imagecreatefrompng('img/dna.png'), 0, 420, 0, 0, 284, 20, 284,20);
*/
	  			break;	
	  			
	  		case 'Down regulated Gene':
/*
	  			$darkblue   = imagecolorallocate($im, 90, 103, 168);
				$blue   	= imagecolorallocate($im, 118, 135, 222);
				imagefilledrectangle($im,$xstart,$ystart-5,$xstop,$ystop,$blue);
	  			imagerectangle($im,$xstart,$ystart-5,$xstop,$ystop,$darkblue);
*/
	  			break;	
	  			
	  		case 'Up regulated Gene':
/*
	  			$darkred   = imagecolorallocate($im, 178, 71, 71);
				$red 	  = imagecolorallocate($im, 255, 76, 76);
				imagefilledrectangle($im,$xstart,$ystart-5,$xstop,$ystop,$red);
	  			imagerectangle($im,$xstart,$ystart-5,$xstop,$ystop,$darkred);
*/
	  			break;	
	  			
	  		default:
				
				$trackPos = 50+$track*20;
				$color = $map->hsbToRgb($H,$S,$B);
				$dark  = $map->hsbToRgb($H,$S,$B-20);
				
				$fill = imagecolorallocate($im, $color['red'], $color['green'], $color['blue']);
				$border = imagecolorallocate($im, $dark['red'], $dark['green'], $dark['blue']);
				imagefilledrectangle($im,$f['tileStart'],$trackPos,$f['tileStop'],$trackPos+10,$fill);
				imagerectangle($im,$f['tileStart'],$trackPos,$f['tileStop'],$trackPos+10,$border);
				
				//display sequence note field
				$this->paintSequence($im,$border,$fill,$f,$trackPos);

	  			break;
	  	}
	}
	
	function paintSequence($im,$border,$fill,$f,$trackPos){
	
		if (count($f['note'])){
			foreach ($f['note'] as $note){
				if (preg_match('/Sequence:/i',$note)){
						
					$seq	= trim(str_replace(array('Sequence:','SEQUENCE:'),'',$note));
					
					if ($f['start'] >= $f['sstart']){
						$bstart = 0;
					}else{
						$bstart = $f['sstart']-$f['start'];
					}
					
					if ($f['stop'] <= $f['sstop'])
						$blength = strlen($seq);
					else{
					    
						$blength = (strlen($seq)-$bstart)-($f['stop']-$f['sstop'])-1; 
						//echo $f['stop'].':'.$f['sstop'].':'.$blength.'<br>';  
					}	
					$seq = substr($seq,$bstart,$blength);	
					$bp	 = strlen($seq);
					if ($bp){
						$bb 	= ($f['tileStop']-$f['tileStart'])/$bp;
						$bstart = $f['tileStart'];
						
			
						if ($bb>10){
							for ($j=0;$j<$bp;$j++){
								imagefilledrectangle($im,$bstart,$trackPos,$bstart+$bb,$trackPos+15,$fill);
								imagerectangle($im,$bstart,$trackPos,$bstart+$bb,$trackPos+15,$border);
								imagestring($im, 4,$bstart+($bb/2)-2, $trackPos,$seq[$j],$border);	
								$bstart +=$bb;			
							}
						}
					}
				}
				
			} 
		}
	}

	function checkFeatureBoundary($f,$start,$stop){

		if ($f['start'] < $start and $f['stop'] < $start)
			return false;
		if ($f['start'] > $stop and $f['stop'] > $stop)
			return false;
		return true;		
	}
	
	function paintFeatureDetails($types,$features,$typePos,$start,$stop){
	
		global $map;
		global $com;
		
		$pass 	= array();
		$tName	= '';
		$i = 50;
		foreach ($types as $t){
			if ($i < $typePos and $typePos < $i+20){
				$tName	= $t;
				break;
			}
			$i+=20;
		}
		//filter features
		foreach ($features as $f){
  			if ($f['tname'] == $tName and $this->checkFeatureBoundary($f,$start,$stop))
				array_push($pass,$f);
		}	   
		
		echo '<features>'."\n";
		if (count($pass) > 0){
			foreach ($pass as $b){
  				
			   	$notes = array();
	    		$notes = $b['note'];
	   		    echo '<feature>'."\n";
	   		    echo '<id><![CDATA['.$com->declean_str_type($b['id']).']]></id>'."\n";
	   		    echo '<type><![CDATA['.$com->declean_str_type($b['tname']).']]></type>'."\n";
	   		    echo '<method><![CDATA['.$com->declean_str_type($b['method']).']]></method>'."\n";
	   		    echo '<start>'.number_format($b['start']).'</start>'."\n";
	   		    echo '<stop>'.number_format($b['stop']).'</stop>'."\n";
	   		    echo '<score>'.$b['score'].'</score>'."\n";
	   		    echo '<orientation>'.$b['orientation'].'</orientation>'."\n";
	   		    echo '<score>'.$b['score'].'</score>'."\n";
   		        echo '<phase>'.$b['phase'].'</phase>'."\n"; 
   		        echo '<link><![CDATA[<a href="'.$b['href'].'" target="_blank">'.$b['text'].'</a>]]></link>'."\n"; 
				if (count($notes)){
					echo '<notes>'."\n";
					while($n = array_pop($notes)){
						if ($n != ':')
							echo '<note><![CDATA['.$n.']]></note>'."\n";
					}
					echo '</notes>'."\n";
				}
				echo '</feature>'."\n";		
			}
        }
       echo '</features>'."\n"; 
	}
}