<?php
	###############################################################################
	#                      das_common-server_view -  description                  #
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


	define("DEBUG",0);
	
	//requiered initializations
	session_start();
	preg_match('/(.*)plugins_dir/',$_SERVER['SCRIPT_FILENAME'],$m);
	$include_path = $m[1].'libs/';
	ini_set('include_path',$include_path);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.das_server.php";
	include_once "das_view.php";
	
	
	//init das_server object
	$ds= new Das_server;
	
	//get parameters
	$param=@$_REQUEST;
	
	$request = $ds->parse_das_url();
	
	if (!$request)
		//400 	Bad command (command not recognized)
		header_error(400);

	$param = $ds->parse_das_url(); 
	
	if (DEBUG){
		foreach($param as $k=>$v){
			if (is_array($v)){
				echo $k.': ';
				foreach ($v as $v1){
					echo $v1.', ';
				}	
			}else{
				echo $k.': '.$v;
			}
			echo '<br>';	
		}	
	}


	//Bad command (command not recognized)
	if (!@$param['command'])
		header_error(400);
		
	//tasks
	switch ($param['command']){
	
		case	'dsn' : 			dsn($param);
									break;
		
		case	'entry_points' : 	entry_points($param);
								 	break;
									
		case	'sequence' : 		sequence($param);
									break; 									
		
		case	'dna' :				sequence($param);
									break;
		
		/*case	'types' :			types($param);
									break;*/
		
		case	'features' :		features($param);
									break;
		

									
		//Bad command (command not recognized)
		default : 					header_error(400);	
	}
	
	//dsn
	function dsn($p){
	
		global $db;
		$strSQL= 'SELECT * 
				    FROM das_commonserver_dsns d ';

		//project context
		if (isset($_COOKIE["current_project"]))
			$strSQL .= "WHERE d.projects_idprojects = ".$_COOKIE["current_project"]; 
 
		
		$dsns = $db->get_results($strSQL);
		
		if ($dsns)		
			dsn_view($dsns);
	}
	
	//entry_points
	function entry_points($p){
	
		global $db;
		
		//Bad data source (data source unknown)
		if (!@$p['dsn'])
			header_error(401);
		
		
		$strSQL= "SELECT * 
		            FROM das_commonserver_segments d, das_commonserver_dsns da 
				   WHERE d.iddas_commonserver_dsns=da.iddas_commonserver_dsns
				     AND da.dname='".$p['dsn']."'";
				     
		//project context
		if (isset($_COOKIE["current_project"]))
			$strSQL .= " AND da.projects_idprojects = ".$_COOKIE["current_project"]; 
					     
		$entry_points = $db->get_results($strSQL);


		//Bad data source (data source unknown)
		if (!$entry_points)		
			header_error(401);
		
		entry_points_view($entry_points);
	}

	//features
	function features($p){
	
		global $db;
		
		//Bad data source (data source unknown)
		if (!@$p['dsn'])
			header_error(401);
			
		$start = '';
		$end = '';
		
		if (preg_match('/:/',$p['segment'][0])){
			$tmp = split(':',$p['segment'][0]);
			$seg = $tmp[0];
			
			if (preg_match('/,/',$tmp[1])){
			   $tmp1 = split(',',$tmp[1]);
			   $start = $tmp1[0];
			   if ($start == 0)
			   	$start=1; 
			   $end = $tmp1[1];
			   
			}
		}else{
			$seg = $p['segment'][0];
		}
		

		
		$strSQL= "SELECT s.sname,sstart,sstop,id,start,stop, tname, method,orientation,f.version,a.idprojects, GROUP_CONCAT(n.text) as notes FROM  das_commonserver_annotations a, das_commonserver_segments s, das_commonserver_types t, das_commonserver_features f
			  LEFT JOIN das_commonserver_notes n ON (f.iddas_commonserver_features = n.iddas_commonserver_features)
				  WHERE f.iddas_commonserver_segments = s.iddas_commonserver_segments
				    AND t.iddas_commonserver_types = f.iddas_commonserver_types
				    AND a.iddas_commonserver_annotations = f.iddas_commonserver_annotations
				    AND a.status = 'ACTIVE'
				    AND s.sname	='".$seg."'";
		            
		            
		if ($start and $end)
		  $strSQL .= "AND ((start >= ".$start." AND stop <= ".$end.") OR (start < ".$start." AND stop > ".$end.") OR (start <= ".$start." AND stop >= ".$start.") OR (start <= ".$end." AND stop >= ".$end.")) ";

 
		
		//project context
		if ($p['pid'][0])
			$strSQL .= " AND a.idprojects = ".$p['pid'][0]." ";

			
			 
		$strSQL .= "GROUP BY f.iddas_commonserver_features ORDER BY  start ";
		
		if ($p['maxbins'])
		 $strSQL .= ' LIMIT '.$p['maxbins'][0];
		 
		if (DEBUG){
			echo 'Get features SQL query:<br>'.$strSQL;
		}	
		
		$features = $db->get_results($strSQL);
		

		//Bad data source (data source unknown)
		if (!$features)		
			header_error(401);
		
		features_view($features,$start,$end);
	}
	
	//sequence
	function sequence($p){
	
		global $db;
		
		//Bad data source (data source unknown)
		if (!@$p['data_source'])
			header_error(401);
			
		//Bad reference object (reference sequence unknown)	
		if (!@$p['segment'])
			header_error(403);	
		
		//search multiple segments	
		if (!is_array($p['segment'])){
			$where = ' AND d.sname = '.$p['segment'].' ';
		}else{
			$segment = explode(',',$p['segment']);
			$where = ' AND d.sname IN ('.$p['segment'].') ';
		}
			
		$strSQL= 'SELECT * 
		            FROM das_commonserver_segments d, das_commonserver_dsns da, das_commonserver_types t
				   WHERE d.iddas_commonserver_dsns=da.iddas_commonserver_dsns
				     AND t.iddas_commonserver_types = d.iddas_commonserver_types
				     AND da.name='.$p['data_source'].$where;
					 
		$entry_points = $db->get_results($strSQL);

		//Bad reference object (reference sequence unknown)	
		if (!$entry_points)		
			header_error(403);
		
		sequence_view($entry_points);
	}
	
	
	//types
	function types($p){
	
		global $db;
		
		$strSQL = '';
		$where  = '';
			
		if (@$p['segment']){
			
			//search multiple segments	
			if (!is_array($p['segment'])){
				$where = ' AND d.sname = '.$p['segment'].' ';
			}else{
				$segment = explode(',',$p['segment']);
				$where = ' AND d.sname IN ('.$p['segment'].') ';
			}
			
			$strSQL= 'SELECT DISTINCT t.name 
		 	            FROM das_commonserver_segments d, das_commonserver_types t
					   WHERE t.iddas_commonserver_types = d.iddas_commonserver_types
				    	 AND da.name='.$p['data_source'].$where;
		}else{
			$strSQL= 'SELECT DISTINCT t.name 
		 	            FROM das_commonserver_types t';
		}
			
		
					 
		$types = $db->get_results($strSQL);

		//Server error, not otherwise specified
		if (!$types)		
			header_error(500);
		
		types_view($types);
	}
?>