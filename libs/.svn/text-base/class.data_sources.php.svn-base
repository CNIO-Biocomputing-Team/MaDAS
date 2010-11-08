<?php
	###############################################################################
	#                      class.data_sources.php -  description	              #
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
	
class Data_Source {

	// get dsn by name
	function getDsnByNameProject($name,$pid) {
		
		global $db;
		$strSQL = "SELECT * FROM das_commonserver_dsns d
			   	   WHERE dname = '".$name."'
			   	   AND projects_idprojects =".$pid;
				 
		$dsn = $db->get_row($strSQL);
		return $dsn;
	}
	
	function flashInputs(){
	
		echo '<input type="hidden" name="solmetraUploaderInstance" value="file1" />
		<input type="hidden" id="solmetraUploaderData_file1" name="solmetraUploaderData[file1]" value="flashFile" /> <!-- set value to form field name -->
		<input type="hidden" id="solmetraUploaderHijack_file1" value="y" /> <!-- set value to "y" if you want to "hijack" HTML form -->
		<input type="hidden" id="solmetraUploaderRequired_file1" value="y" /> <!-- set value to "y" to prevent form submission if this one is not set -->
';
	}
	
	function flashDiv(){
	
		echo '<div id="solmetraUploaderPlaceholder_file1"></div>';
	}
	
	function flashFileConfig(){
	
		echo '<!-- Here we create main object and set config parameters -->
<script type="text/javascript">
<!--
   var so = new SWFObject("libs/FlashUploader_102/uploader.swf", "solmetraUploaderMovie_file1", "500", "50", "8", "#ffffff");
   so.useExpressInstall("expressinstall.swf");
   so.addParam("allowScriptAccess", "always");
   so.addParam("allowFullScreen", "false");
   so.addVariable("language", "en");                     	// [optional] language to use for textual prompts
   so.addVariable("baseurl", "libs/FlashUploader_102/"); 	// [optional] will be applied to all eternal calls except uploadurl
   so.addVariable("uploadurl", "upload.php");            	// [optional] an url to post files to; relative to uploader.swf
   so.addVariable("config", "uploader.xml");             	// [optional] path to front-end configuration file
   so.addVariable("instance", "file1");          			// instance id - should be unique
   so.addVariable("allowed", "");
   so.addVariable("disallowed", "php,php3,php4,php5");
   so.addVariable("verifyupload", "true");
   so.addVariable("configXml", "");
   so.addVariable("maxsize", "100000000");
   so.addVariable("hijackForm", "yes");
   so.addVariable("externalErrorHandler", "SolmetraUploader.broadcastError");
   so.addVariable("externalEventHandler", "SolmetraUploader.broadcastEvent");
   so.write("solmetraUploaderPlaceholder_file1");
   solmetraUploaderMovie_file1 = document.getElementById("solmetraUploaderMovie_file1");
//-->
</script>';
	
	
	}
	
	
	function storeAnnotation($pid,$userId,$id_dsn,$description){

        global $db;
        global $c;
        
            $strSQL = "INSERT INTO das_commonserver_annotations
                               SET idusers = ".$userId.",
                                   idprojects = ".$pid.",
                                   iddas_commonserver_dsns = ".$id_dsn.",
                                   description = '".$c->preparesql($description)."',
                                   status = 'ACTIVE',
                                   created = now(),
                                   modified = now()";
                                   
            $db->query($strSQL);
            return mysql_insert_id();
	}
	
	
	
	//segment exist
	function segmentExist($name,$dsn){
	
		global $db;
		$strSQL = "SELECT * 
					 FROM das_commonserver_segments d 
					WHERE d.sname = '".$name."'
					  AND iddas_commonserver_dsns = ".$dsn;
				 
		$segment = $db->get_row($strSQL);
		if ($segment)
			return $segment->iddas_commonserver_segments;
			
		return false;
	}
	
	//store Segment
	function storeSegment($name,$dsn,$mtype){
		
		global $db;
		
		$strSQL = "INSERT INTO das_commonserver_segments
						   SET iddas_commonserver_dsns = ".$dsn.",
							   sname = '".$name."',
							   idmolecule_types = ".$mtype.",
							   created =  now()";

		$db->query($strSQL);
		
		return $db->getLastId();
	}
	
	//store Feature
	function storeFeature($segment_id,$record,$annot){
		
		
		global $db;
		global $c;
		
		//get attributes
		if (@$record['attributes']){
		
			$tmp = split(";",$record['attributes']);
			foreach ($tmp as $att){
				list ($key,$value) = split("=",$att);
				$attributes[strtoupper($key)] = $value;
			}
		}
	
		
		//is a component?
		//if has an ID is a reference sequence and has subparts?. I guess "yes"
		$id=(@$attributes['ID'])?@$attributes['ID']:$record['type'];
		$reference=(@$attributes['ID'])?'yes':'';
		$subparts=(@$attributes['ID'])?'yes':'';
		$superparts=(@$attributes['PARENT'])?'yes':'';
		
		//store feature type
		$ftype = $record['type'];
		$category = '';
		$subtype= '';
		
		if ($superparts)
			$category = 'component';
		elseif ($subparts)
			$category = 'supercomponent';	
		 
		$type_id = $this->storeFType($ftype,$subtype,$category);
		
	
		//store feature
		$strSQL = "INSERT INTO das_commonserver_features
						   SET iddas_commonserver_segments = ".$segment_id.",
						           iddas_commonserver_annotations =".$annot.",
						   	   iddas_commonserver_types = ".$type_id.",	
						   	   id = '".$c->preparesql($id)."',
							   label = '".$c->preparesql($id)."',
							   start = '".$record['start']."',
							   stop = '".$record['end']."',
							   score = '".$record['score']."', 
							   method = '".$record['source']."',
							   orientation = '".str_replace(".","0",$record['strand'])."',
							   phase = '".$record['frame']."',
							   reference = '".$reference."',
							   superparts = '".$superparts."',
							   subparts = '".$subparts."',
							   created = now()";
	
		$db->query($strSQL);
		$feature_id = $db->getLastId();
		
		
		
		
		
		//store feature notes
		if (@$record['attributes']){
			foreach($attributes as $k=>$v){
				$this->storeFNote($feature_id,$k.':'.$v);				
			}
		}
		
		return $feature_id;
	}
	
	
	//store FType
	function storeFtype($ftype,$subtype,$category){
		
		global $db;
		$strSQL = "SELECT * 
					 FROM das_commonserver_types d
					WHERE tname = '".$ftype."' ";

		$t = $db->get_row($strSQL);
		
		//new type
		if (!$t){
			$th = rand(1,360);
			$strSQL = "INSERT INTO das_commonserver_types
							   SET tname ='".$ftype."',
							   	   tsubtype = '".$subtype."',
							       tcategory = '".$category."',
								   th = ".$th.",
								   tcreated = now()";
			$db->query($strSQL);					   
			return $db->getLastId();
			
		//type exist	
		}else {
			return $t->iddas_commonserver_types;
		}
	}
	
	
	
	//store FNote
	function storeFNote($fid,$text){
		
		global $db;
		global $c;
		
		$strSQL = "INSERT INTO das_commonserver_notes
						   SET iddas_commonserver_features = '".$fid."',
						   text = '".$c->preparesql($text)."',
						   created = now()";
		$db->query($strSQL);
		
		return $db->getLastId();
	}



}