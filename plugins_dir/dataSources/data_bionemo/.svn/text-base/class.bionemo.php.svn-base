<?
	class Bionemo{
	
		function cleanField($field){
		
			return trim(str_ireplace(array(' ','sp.','/'),array('_','','_'),$field));
		}
		
		
		function storeAnnotation($dsn,$version){

	        global $db;
	        global $c;
	        
                $strSQL = "INSERT INTO das_commonserver_annotations
                                   SET idusers = ".$_COOKIE['idusers'].",
                                       idprojects = ".$_COOKIE['current_project'].",
                                       iddas_commonserver_dsns = ".$dsn.",
                                       description = 'Bionemo Database. Version ".$version."',
                                       status = 'ACTIVE',
                                       created = now(),
                                       modified = now()";
                                       
                $db->query($strSQL);
                return mysql_insert_id();
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
		}
	
		function copyDsns($version){
			
			global $pg;
			global $db;
		
			/* $strSQL  = "SELECT * FROM organism WHERE scientificname LIKE '%coli%'"; */
			$strSQL  = "SELECT * FROM organism";
			$results = pg_query($pg,$strSQL);
			
			if ($results){
				for($i=0; $i< pg_num_rows($results);$i++){
					$row = pg_fetch_object($results,$i);
					
					$name = $this->cleanField($row->scientificname);
					if ($row->strain and !preg_match('/'.$this->cleanField($row->strain).'/',$name))
						$name .= '_'.$this->cleanField($row->strain);
					
					$insert = "INSERT INTO das_commonserver_dsns SET 
													  dname = '".$name."',
        										   dversion = ".$version.",
        									 dmolecule_type	= 2,
        										dmap_master = '".$_SESSION['MaDAS_url']."plugins_dir/dasServers/das_common-server/das.php/".$name."',
        										
        										dinclude_seq = 1,
        											dcreated = now(),
        											 idusers = ".$_COOKIE['idusers'].",
        								 projects_idprojects = ".$_COOKIE['current_project'].",
        											  dcache = 0" ;
				
					$db->query($insert);
					
					
					$dsn = mysql_insert_id();
					//store annotation
					$annotation = $this->storeAnnotation($dsn,$version);
					
					//store segments
					$this->copySegments($row->id_organism,$dsn,$version,$annotation);
				}
			}
		}
		
		
		function copySegments($organism,$dsn,$version,$annotation){
			
			global $pg;
			global $db;
			
			$strSQL  = "SELECT * FROM dna WHERE id_organism = ".$organism." AND sequence IS NOT NULL AND sequence <> '' ";
			$results = pg_query($pg,$strSQL);
			
			if ($results){
				for($i=0; $i< pg_num_rows($results);$i++){
					
					$row 	= pg_fetch_object($results,$i);
					$insert = "INSERT INTO  das_commonserver_segments
							   SET  idmolecule_types = 2,
    								iddas_commonserver_dsns = ".$dsn.",
    								sname = 'Segment_".$i."',
     								sstart = 1,
    								sstop = ".strlen($this->cleanField($row->sequence)).",
								    sversion = '".$version."',
								    modified = now(),
								    created = now()";
								    
					@$db->query($insert);
					$segment = 	mysql_insert_id();
					
					//store features
					if ($segment and !mysql_error()){
						$this->copyGenes($row->id_dna,$segment,$annotation);
						$this->copyPromoters($row->id_dna,$segment,$annotation);
						$this->copyOperons($row->id_dna,$segment,$annotation);
						$this->copyProteins($row->id_dna,$segment,$annotation);
					}		    
				}	
			
			}
			
			
			
		}
		
		function copyGenes($dna,$segment,$annotation){
			
			global $pg;
			global $db;
			
			$strSQL  = "SELECT * FROM gene WHERE id_dna = ".$dna." AND starts IS NOT NULL AND ends IS NOT NULL AND starts <> 0 AND ends <> 0 ";
			$results = pg_query($pg,$strSQL);
			
			if ($results){
				for($i=0; $i< pg_num_rows($results);$i++){
					
					$row 	= pg_fetch_object($results,$i);
					
					if ($row->starts and $row->ends){
					
						$orientation = ($row->ends >$row->starts)?'+':'-';
						
						$insert = "INSERT INTO das_commonserver_features
										SET iddas_commonserver_types = 54,
	    							  		iddas_commonserver_annotations = ".$annotation.",
	    								 	iddas_commonserver_segments = ".$segment.",
										    id = '".$this->cleanField($row->name)."',
										    label = '".$this->cleanField($row->name)."',
										    start = ".$this->cleanField($row->starts).",
										    stop = ".$this->cleanField($row->ends).",
										    method = 'Bionemo',
										    orientation = '".$orientation."',
										    phase = 0,
										    created = now(),
										    modified = now(),
										    idusers = ".$_COOKIE['idusers'].",
										    version = 1";
									    
						@$db->query($insert);			 
					}   
				}	
			}
		}
		
		function copyPromoters($dna,$segment,$annotation){
			
			global $pg;
			global $db;
			
			$strSQL  = "SELECT p.id_promoter,p.start,p.direction, a.action,b.id_site,bs.starts,bs.ends, bs.evidence,bs.comments ,c.activity,c.description
						FROM promoter p
						LEFT JOIN action a ON (p.id_promoter = a.id_promoter)
						LEFT JOIN action_b_complex ac ON (a.id_action = ac.id_action)
						LEFT JOIN binding_complex b ON (ac.id_bind_complex = b.id_bind_complex)
						LEFT JOIN complex c ON (b.id_complex = c.id_complex)
						LEFT JOIN binding_site bs ON (b.id_site = bs.id_site)
						WHERE p.start IS NOT NULL
						AND p.id_dna = ".$dna;
						
			$results = pg_query($pg,$strSQL);
			
			if ($results){
				
				$idpromoter = 0;
				$fid = 0;
				
				
				for($i=0; $i< pg_num_rows($results);$i++){
					
					$row 	= pg_fetch_object($results,$i);
					
					//new promoter
					if ($row->id_promoter != $idpromoter){
					
						if ($this->cleanField($row->direction) == '+')
							$stop = (int) $row->start +1;
						else
							$stop = (int) $row->start -1;	
					
						$name = 'Promoter. Sigma '.$row->sigma_class.' dependent';
						$insert = "INSERT INTO das_commonserver_features
					    			SET iddas_commonserver_types = 171,
	    			    		  		iddas_commonserver_annotations = ".$annotation.",
	    			    			 	iddas_commonserver_segments = ".$segment.",
					    			    id = '".$name."',
					    			    label = '".$name."',
					    			    start = ".$this->cleanField($row->start).",
					    			    stop = ".$stop.",
					    			    method = 'Bionemo',
					    			    orientation = '".$this->cleanField($row->direction)."',
					    			    phase = '0',
					    			    created = now(),
					    			    modified = now(),
					    			    idusers = ".$_COOKIE['idusers'].",
					    			    version = 1";
					    		    
						@$db->query($insert);
						$fid = 	mysql_insert_id();
						
						if ($row->description and $row->starts and $row->ends and $row->action){
							$text = $row->description.' binded to '.$row->starts.'-'.$row->ends.' => '.$row->action;
							$this->storeFNote($fid,$text);
						}

		
					//fill notes
					}else{
						
						if ($row->description and $row->starts and $row->ends and $row->action){
							$text = $row->description.' binded to '.$row->starts.'-'.$row->ends.' => '.$row->action;
							$this->storeFNote($fid,$text);
						}
					
					}
					
					$idpromoter = $row->id_promoter;
								 
 
				}	
			}
		}
		
		function copyOperons($dna,$segment,$annotation){
			
			global $pg;
			global $db;
			
			$strSQL  = "SELECT o.id_operon, o.description, g.starts , g.ends FROM operon o, gene_operon go, gene g
						WHERE  g.id_dna = ".$dna."
						AND o.id_operon = go.id_operon
						AND g.id_gene = go.id_gene 
						ORDER BY o.id_operon";
						
			$results = pg_query($pg,$strSQL);
			
			if ($results){
				
				$idoperon = 0;
				$start = 10000000000000;
				$end = 0;
				$name = '';
				
				
				for($i=0; $i< pg_num_rows($results);$i++){
					
					$row 	= pg_fetch_object($results,$i);
					
					//new operon
					if ((int)$row->id_operon != $idoperon and $i != 0){
	
						
						//only one gene
						if ($end == 0){
							$start = $row->starts;
							$end = $row->ends;
							$name = 'Operon '.$row->description;
						}
						
						$orientation = ($end >$start)?'+':'-';
							
						$insert = "INSERT INTO das_commonserver_features
					    			SET iddas_commonserver_types = 172,
	    			    		  		iddas_commonserver_annotations = ".$annotation.",
	    			    			 	iddas_commonserver_segments = ".$segment.",
					    			    id = '".$name."',
					    			    label = '".$name."',
					    			    start = ".$start.",
					    			    stop = ".$end.",
					    			    method = 'Bionemo',
					    			    orientation = '".$orientation."',
					    			    phase = '0',
					    			    created = now(),
					    			    modified = now(),
					    			    idusers = ".$_COOKIE['idusers'].",
					    			    version = 1";
					    		    
						$db->query($insert);
						$start = 10000000000000;
						$end = 0;
		
					//find start-end
					}else{
						
						if ((int)$row->ends > (int)$row->starts){
							
							if ((int)$row->starts < $start)
								$start = (int)$row->starts;
							if ((int)$row->ends > $end)
								$end = (int)$row->ends;	
						}else{
							
							if ((int)$row->ends < $start)
								$start = (int)$row->ends;
							if ($row->starts > $end)
								$end = (int)$row->starts;	
							
						}
						$name = 'Operon '.$row->description;
					}
					
					$idoperon = (int)$row->id_operon;
								 
 
				}	
			}
		}
		
		function copyProteins($dna,$segment,$annotation){
			
			global $pg;
			global $db;
			
			$strSQL  = "SELECT * FROM gene g, protein p WHERE p.id_gene = g.id_gene AND g.id_dna = ".$dna." AND g.starts IS NOT NULL AND g.ends IS NOT NULL AND g.starts <> 0 AND g.ends <> 0 ";
			$results = pg_query($pg,$strSQL);
			
			if ($results){
				for($i=0; $i< pg_num_rows($results);$i++){
					
					$row 	= pg_fetch_object($results,$i);
					
					if ($row->starts and $row->ends){
					
						$orientation = ($row->ends >$row->starts)?'+':'-';
						
						$insert = "INSERT INTO das_commonserver_features
										SET iddas_commonserver_types = 23,
	    							  		iddas_commonserver_annotations = ".$annotation.",
	    								 	iddas_commonserver_segments = ".$segment.",
										    id = '".$this->cleanField($row->code_sw)."',
										    label = '".$this->cleanField($row->code_sw)."',
										    start = ".$this->cleanField($row->starts).",
										    stop = ".$this->cleanField($row->ends).",
										    method = 'Bionemo',
										    orientation = '".$orientation."',
										    phase = 0,
										    created = now(),
										    modified = now(),
										    idusers = ".$_COOKIE['idusers'].",
										    version = 1";
									    
						@$db->query($insert);			 
					}   
				}	
			}
		}
	
	
	}
?>