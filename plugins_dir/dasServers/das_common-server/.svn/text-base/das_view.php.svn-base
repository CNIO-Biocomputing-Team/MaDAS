<?php
	###############################################################################
	#                      das_common-server_view -  description                  #
	#                             -------------------                             #   
	#    begin                : Wed Jul 25 14:18:02 CEST 2007                     # 
	#    copyright            : (C) 2007 by Victor de la Torre                    # 
	#    email                : victor@madas.es                          # 
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


	define("Content_Type", "text/xml");
	define("X_DAS_Version", "DAS/1.5");
	define("X_DAS_Capabilities", "dsn/1.0;dna/1.0;types/1.0;features/1.0;entry_points/1.0;error-segment/1.0;unknown-segment/1.0;unknown-feature/1.0;feature-by-id/1.0;group-by-id/1.0;component/1.0;supercomponent/1.0;sequence/1.0");
	
	/* Display good response header */
	function header_ok(){

//	    200 	OK, data follows

		header ("Content-Type: ".Content_Type);
/*
		header ("X-DAS-Version: ".X_DAS_Version);
		header ("X-DAS-Status: 200");
		header ("X-DAS-Capabilities: ".X_DAS_Capabilities);
*/
		
	}
	
	/* Display error header */
	function header_error($X_DAS_Status){
		
//		400 	Bad command (command not recognized)
//		401 	Bad data source (data source unknown)
//		402 	Bad command arguments (arguments invalid)
//		403 	Bad reference object (reference sequence unknown)
//		404 	Bad stylesheet (requested stylesheet unknown)
//		405 	Coordinate error (sequence coordinate is out of bounds/invalid)
//		500 	Server error, not otherwise specified
//		501 	Unimplemented feature

		header ("Content-Type: ".Content_Type);
		header ("X-DAS-Version: ".X_DAS_Version);
		header ("X-DAS-Status: ".$X_DAS_Status);
		header ("X-DAS-Capabilities: ".X_DAS_Capabilities);
		exit;
	}
	
	//dsn
	function dsn_view($dsns){
		header_ok();
		echo '<?xml version="1.0" standalone="no"?>'."\n";
		echo '<!DOCTYPE DASDSN SYSTEM "http://www.biodas.org/dtd/dasdsn.dtd">'."\n";
		echo '<DASDSN>'."\n";
		
		foreach ($dsns as $d){
			echo '<DSN>'."\n";
			echo '<SOURCE id="'.$d->dname.'" version="'.$d->dversion.'" project="'.$d->projects_idprojects.'">'.$d->dname.'</SOURCE>'."\n";
		    echo '<MAPMASTER>'.$d->dmap_master.'</MAPMASTER>'."\n";
			echo '<DESCRIPTION>'.$d->ddescription.'</DESCRIPTION>'."\n";
			echo '</DSN>'."\n";
		}
		
		echo '</DASDSN>'."\n";
		
	}
	
	//entry_points
	function entry_points_view($entry_points){
		header_ok();
		echo '<?xml version="1.0" standalone="no"?>'."\n";
		echo '<!DOCTYPE DASDSN SYSTEM "http://www.biodas.org/dtd/dasdsn.dtd">'."\n";
		echo '<DASEP>'."\n";
		echo '<ENTRY_POINTS href="http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'" version="'.$entry_points[0]->dversion.'">'."\n";
		foreach ($entry_points as $ep){
			echo ' <SEGMENT id="'.$ep->sname.'" start="'.$ep->sstart.'" stop="'.$ep->sstop.'" type="'.$ep->stype.'" orientation="'.$ep->sorientation.'"';
			if ($ep->ssubparts)
				echo ' subparts="yes "';
			echo '>'.$ep->sname.'</SEGMENT>'."\n";
		
		}
		echo '</ENTRY_POINTS>'."\n";
		
		echo '</DASEP>'."\n";
		
	}
	
	//features
	function features_view($features,$sstart,$send){
		
		$segstart = ($sstart)?$sstart:$features[0]->sstart;
		$segend = ($send)?$send:$features[0]->sstop;
		
		if ($segend >$features[0]->sstop)
			$segend = $features[0]->sstop;
		
		header_ok();
		echo '<?xml version="1.0" standalone="no"?>'."\n";
		echo '<!DOCTYPE DASGFF SYSTEM "http://www.biodas.org/dtd/dasgff.dtd">'."\n";
		echo '<DASGFF>'."\n";
		echo '<GFF version="1.01" href="http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'">'."\n";
		echo '<SEGMENT id="'.$features[0]->sname.'" start="'.$segstart.'" stop="'.$segend.'">'."\n";
		
		foreach ($features as $fe){
			
			echo '<FEATURE id="'.$fe->id.'" version="'.$fe->version.'" project="'.$fe->idprojects.'">'."\n";
    			echo '<START>'.$fe->start.'</START>'."\n";
    			echo '<END>'.$fe->stop.'</END>'."\n";
    			echo '<TYPE id="'.$fe->tname.'">'.$fe->tname.'</TYPE>'."\n";
    			echo '<METHOD id="'.$fe->method.'">'.$fe->method.'</METHOD>'."\n";
				echo '<SCORE>'.$fe->score.'</SCORE>'."\n";
    			echo '<ORIENTATION>'.$fe->orientation.'</ORIENTATION>'."\n";
 				
 				foreach (explode(',',$fe->notes) as $note)
 					echo '<NOTE>'.$note.'</NOTE>'."\n";
 			
 			echo '</FEATURE>'."\n";
		
		}

		echo '</SEGMENT>'."\n";
		echo '</GFF>'."\n";
		echo '</DASGFF>'."\n";
		
	}
	
	
	//sequence
	function sequence_view($entry_points){
		header_ok();
		echo '<?xml version="1.0" standalone="no"?>'."\n";
		echo '<!DOCTYPE DASSEQUENCE SYSTEM "http://www.biodas.org/dtd/dassequence.dtd">'."\n";
		echo '<DASSEQUENCE>'."\n";

		foreach ($entry_points as $ep){
		
			echo '<SEQUENCE id="'.$ep->iddas_commonserver_segments.'" start="'.$ep->sstart.'" stop="'.$ep->sstop.'" moltype="'.$ep->smol_type.'" version="'.$ep->sversion.'">'."\n";
			echo $ep->ssequence;	
			echo '</SEQUENCE>';	
			
		}
		
		echo '</DASSEQUENCE>'."\n";
		
	}
	
	//types
/*	function types_view($types){

		echo '<?xml version="1.0" standalone="no"?>'."\n";
		echo '<!DOCTYPE DASTYPES SYSTEM "http://www.biodas.org/dtd/dastypes.dtd">'."\n";
		echo '<DASTYPES>'."\n";

		foreach ($entry_points as $ep){
		
			echo '<SEQUENCE id="'.$ep->iddas_commonserver_segments.'" start="'.$ep->sstart.'" stop="'.$ep->sstop.'" moltype="'.$ep->smol_type.'" version="'.$ep->sversion.'">'."\n";
			echo $ep->ssequence;	
			echo '</SEQUENCE>';	
			
		}
		
		echo '</DASTYPES>'."\n";
		
	}*/
?>