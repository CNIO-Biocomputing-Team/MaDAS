<?php
	###############################################################################
	#                      class.plugins.php -  description                       #
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

class Plugins
{
	/* cleandbf */
	function cleandbf($value) {
		
		return trim($value);
	}
	
	
	/* DATA SOURCES */
	
	/* get data sources */
	function getAllDataSources() {
		
		global $db;
		$strSQL="SELECT *
				   FROM sources s, users u, das_servers d
		  		  WHERE u.idusers = s.users_idusers
				   AND s.das_servers_iddas_servers = d.iddas_servers
				  ORDER BY s.dsname, s.das_servers_iddas_servers";
		$sources = $db->get_results($strSQL);
		return $sources;
	}	
	
	
	/* get data sources */
	function getDataSources($page,$start,$limit,$sidx,$sord,$wh,$uid) {
		
		global $db;
		$strSQL="SELECT s.idsources, s.name, u.name as user, u.public_data, u.company, s.das, s.created, s.description
				   FROM sources s, users u
				  WHERE u.idusers=s.users_idusers ".$wh." ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;
		$sources = $db->get_results($strSQL);
		return $sources;
	}	
	
	/* get data sources count */
	function getdataSourcesCount() {
		
		global $db;
		$strSQL="SELECT count(s.idsources) as total
				   FROM sources s";
		
		$count = $db->get_row($strSQL);
		return $count;
	}
	
	
	/* get data source by Id */
	function getDataSourceById($id) {
		
		global $db;
		$strSQL="SELECT *
				   FROM sources s, users u, das_servers d
		  		  WHERE s.users_idusers=u.idusers 
		  		    AND s.das_servers_iddas_servers = d.iddas_servers
		  		    AND s.idsources=".$id;
		$source = $db->get_row($strSQL);
		return $source;
	}
	
	/* get data source by DAS server */
	function getDataSourcesByDAS($did) {
		
		global $db;
		$strSQL="SELECT *
				   FROM sources s, users u, das_servers d
		  		  WHERE u.idusers = s.users_idusers
					AND s.das_servers_iddas_servers = d.iddas_servers
				    AND s.das_servers_iddas_servers=".$did;
	  
		$sources = $db->get_results($strSQL);
		return $sources;
	}
	
	
	/* add data source */
	function addDataSource($name,
					    $description,
						$das,
						$directory) {
		
		global $db,$_SESSION;
		
		//defaults
		$user=$_SESSION['idusers'];
		$active=1;
		
		//check for duplicated plugin name
		$strSQL="SELECT idsources FROM sources s WHERE s.name='".$name."'";
		$r = $db->get_row($strSQL);
		if ($r)
			return $_SESSION['duplicated_plugin_name'];
	
		//insert plugin
		$strSQL="INSERT INTO sources
					     SET users_idusers=".$this->cleandbf($user).",
						 	 name='".$this->cleandbf($name)."',
							 description='".$this->cleandbf($description)."',
							 directory='".$this->cleandbf($directory)."',
							 das='".$this->cleandbf($das)."',
							 active=".$this->cleandbf($active).",
							 created=now(),
							 modified=now()";
							 
		$db->query($strSQL);	
		
		return $_SESSION['plugin_created'];
	}	
	
	
	/*DAS SERVERS */
	
	/* get all das servers */
	function getAllDasServers() {
		
		global $db;
		$strSQL="SELECT d.iddas_servers, d.dasname, d.dasprotocol, u.name as user, u.public_data, u.company, d.dascreated, d.dasdescription
				   FROM das_servers d, users u
				   WHERE d.users_idusers = u.idusers
				   ORDER BY d.dasname";
		$servers = $db->get_results($strSQL);
		return $servers;
	}	
	
	/* get das servers */
	function getDasServers($page,$start,$limit,$sidx,$sord,$wh,$uid) {
		
		global $db;
		$strSQL="SELECT d.iddas_servers, d.name, d.protocol, u.name as user, u.public_data, u.company, d.created, d.description
				   FROM das_servers d, users u
				   WHERE u.idusers=d.users_idusers ".$wh." ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;
		$servers = $db->get_results($strSQL);
		return $servers;
	}	
	
	/* get data sources count */
	function getDasServersCount() {
		
		global $db;
		$strSQL="SELECT count(d.iddas_servers) as total
				   FROM das_servers d";
		
		$count = $db->get_row($strSQL);
		return $count;
	}
	
	/* get das server by Id */
	function getDasServerById($id) {
		
		global $db;
		$strSQL="SELECT d.iddas_servers, d.users_idusers, d.name, d.description, d.protocol, d.active, d.created, d.modified, u.name as user, u.email, u.company, u.address, u.public_data
				   FROM das_servers d, users u
		  		  WHERE d.users_idusers=u.idusers AND d.iddas_servers=".$id;
		$das = $db->get_row($strSQL);
		return $das;
	}
	
	
	
	/* VISUALIZATION */
	
	
	/* get all visualization plugins */
	function getAllVisualization() {
		
		global $db;
		$strSQL="SELECT *
				   FROM visualization v, users u, das_servers d
		  		  WHERE u.idusers = v.users_idusers
				   AND v.das_servers_iddas_servers = d.iddas_servers
				  ORDER BY v.viname, v.das_servers_iddas_servers";
		$visualization = $db->get_results($strSQL);
		return $visualization;
	}	
	
	/* get visualization by DAS */
	function getVisualizationByDAS($id) {
		
		global $db;
		$strSQL="SELECT *
				   FROM visualization v, users u, das_servers d
		  		  WHERE u.idusers = v.users_idusers
				   AND v.das_servers_iddas_servers = d.iddas_servers
				   AND v.das_servers_iddas_servers = ".$id."
				  ORDER BY v.viname, v.das_servers_iddas_servers";
		$visualization = $db->get_results($strSQL);
		return $visualization;
	}
	
		/* get visualization by Id */
	function getVisualizationById($id) {
		
		global $db;
		$strSQL="SELECT *
				   FROM visualization v, users u, das_servers d
		  		  WHERE u.idusers = v.users_idusers
		  		    AND v.das_servers_iddas_servers = d.iddas_servers
					AND v.idvisualization = ".$id;
		$visualization = $db->get_row($strSQL);;
		return $visualization;
	}
	
	
}

?>