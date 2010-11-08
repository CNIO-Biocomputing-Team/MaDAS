<?php
	###############################################################################
	#                      class.madasdb.php -  description                       #
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

class Madasdb{

	function getFeaturesByPos($pid,$dsn,$psegment,$start,$end){
	
		global $db;
		
		$strSQL= "SELECT SQL_CACHE f.id,f.start,f.stop, f.method,f.orientation,f.version, t.tname, t.th, GROUP_CONCAT(n.text SEPARATOR '|') as notes, l.href,l.text 
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
		
		if ($start and $end)
		  $strSQL .= " AND ( (start >= ".$start." AND stop <= ".$end.") OR (start <= ".$start." AND stop >= ".$end.") OR (start <= ".$start." AND stop <= ".$end." AND stop >=".$start." ) OR (start >= ".$start." AND stop >= ".$end." AND start <= ".$end." ) ) ";
		
		$strSQL .=	" AND t.iddas_commonserver_types = f.iddas_commonserver_types
			     GROUP BY f.iddas_commonserver_features";   

		//echo $strSQL;

		$features = $db->get_results($strSQL);
		
		if ($features)
			return $features;
		else
			return array();
		  
	}	

}