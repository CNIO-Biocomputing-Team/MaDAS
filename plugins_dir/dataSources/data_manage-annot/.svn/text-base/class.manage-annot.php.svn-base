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
	
class Manage_Annotations {

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
    
    
    //get annotation by ID
    function getAnnotById($id) {
            
            global $db;
            $strSQL = "SELECT * FROM das_commonserver_annotations
                                WHERE iddas_commonserver_annotations = ".$id;
                              
            return $db->get_row($strSQL);
    }
    
    
    // delete annotation by ID
    function deleteAnnotById($id) {
            
            global $db;
            $strSQL = "DELETE FROM das_commonserver_annotations
                                WHERE iddas_commonserver_annotations = ".$id;
                              
            $db->query($strSQL);
    }


}
?>