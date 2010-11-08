<?
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "../../../libs/ez_sql.php";

	$file = $_GET['file'];

	$url = 'http://localhost/madas/plugins_dir/dataSources/data_treefam/'.$file;
	
	$tf = str_replace(array('tmp/families_work/','/clean.nhx.xml.html'),'',$file);
	$tf1 = explode('/',$tf);
	$treefam = trim($tf1[1]);
	
	$idtree = 1;
	$event = '';
	
		
	function getNodes($xmlObj,$depth=0,$clade=0,$path=array(),$nodes=array(),$duplication='',$speciation='') {
	
	  global $db;
	  global $treefam;
	  global $idtree;
	  global $path_TAX_ID;
	  global $path_TAXNAME;
	  
	  $childrens = 0;
	  $nodeInserted = 0;

	  $tmp_duplication = $duplication;
	  $tmp_speciation = $speciation;	
	  foreach($xmlObj->children() as $child) {
	  
	  		  			
	  	$duplication = $tmp_duplication;
	  	$speciation = $tmp_speciation;
	  	$flagged = 0;
	  	
	  	$nodeName = $child->getName();
	  	
	  	if ($nodeName == 'clade'){

	  		$clade++;
	  		$nodeID 		= md5(serialize($child));
	  		$branch_length 	= ($child->branch_length)?$child->branch_length:0.0;
	  		$confidence 	= ($child->confidence)?$child->confidence:0.0;
	  		$tax_name		= $child->taxonomy->code;
	  		$specie_TAX_ID 	= ($child->taxonomy->id)?$child->taxonomy->id:0;
	  		if ($child->events->speciations == 1)
	  			$event	= 'speciation';
	  		else if ($child->events->duplications == 1)	 
	  			$event	= 'duplication';
	  		else if ($child->taxonomy->id)	
	  			$event	= 'speciation';
	  		

			if ($path_TAX_ID["$specie_TAX_ID"])
				$current_path = explode('/',$path_TAX_ID["$specie_TAX_ID"]);
			else if ($path_TAXNAME["$tax_name"])
				$current_path = explode('/',$path_TAXNAME["$tax_name"]);		
			else
				$current_path = array();


	  		if ($childrens > 0){
	  			array_pop($path);
	  			array_pop($nodes);
	  		}	
	  		array_push($path,$tax_name);
	  		array_push($nodes,$nodeID);
	  		$childrens++;	
	  		
	  		if (count($current_path)>0){

	  			//internal node
	  		
	  			//INSERT INFERRED NODES
	  			
	  			//check if we need to insert extra nodes
	  			$difference = array();
	  			$tax1 = $path[count($path)-2];
	  			
	  			if ($tax1 and $tax1 != $tax_name){
		  		    
		  		    $strSQL = "SELECT SQL_CACHE path FROM datasource_treefam_species_tree WHERE idspecie_tree=".$idtree." AND  TAXNAME='".$tax1."'"; //FAST
		  		    
		  		    $tmpl =  $db->get_row($strSQL);
		  		    if ($tmpl)
		  		    	$last_path = explode('/',$tmpl->path);  
		  		    //echo 'L: '.$strSQL.'<br>';
	  			}
	  			if ($last_path){
	  			    
	  			    $difference = array_diff($current_path,$last_path);
	  			    
	  			}	
	  			//adding identical taxname
	  			if (count($difference) == 0 ){
	  			    
	  			    $difference = array(array_pop($current_path));
				    
				}
				//adding speciation after final duplication
				if ($duplication and $difference[0] != $duplication){
				    	array_unshift($difference,$duplication);
				}
	  			
	  			
	  			//echo '<br><br>----------->'.implode('/',$path).'<br>';
	  			//echo '----------->'.implode('/',$nodes).'<br>';		
	  			//echo '----------->'.implode('/',$difference).'<br><br><br>';							

					
					
					
	  			if ($difference){
	  			    $l = 1;
	  			    foreach ($difference as $tax){
						//echo $tax."<br>";
		  		    	//inserted-speciations
		  		    	if ($l != count($difference)){
				    	    
				    	    $vbranch_length = 0.0;
				    	    $vconfidence = 0.0;
		  		    	    $vevent = 'inserted_speciation';
		  		    	    $duplication = '';
		  		    	    $vnodeID = md5(rand(1,10000000000000000));
		  		    	    array_push($nodes,$vnodeID);
				
		  		    	    $strSQL = "INSERT INTO datasource_treefam_inferred_nodes SET tree='".$treefam."', tree_node_id='".$vnodeID."', branch_length=".$vbranch_length.", confidence=".$vconfidence.", event='".$vevent."', TAX_ID=".$tax.", path='".implode('/',$nodes)."', method=1, flagged = 1";
		  		    	    $nodeInserted = 1;
		  		    	
		  		    	}else{
		  		    			  						
		  		    		if ($event == 'duplication')
		  		    			$duplication = $tax;
		  		    		
		  		    		
		  		    		if ($event == 'speciation'){
		  		    			
		  		    			// una especiación detrás de otra
		  		    			$duplication = '';
		  		    			if ($tax == $speciation){
		  		    				$flagged = 0;
		  		    				
		  		    			// una especiación normal	
		  		    			}else{
		  		    				$flagged = 1;
		  		    				$speciation = $tax;
		  		    			}		
		  		    		}	
		  		    			
		  		    		$strSQL = "INSERT INTO datasource_treefam_inferred_nodes SET tree='".$treefam."', tree_node_id='".$nodeID."', branch_length=".$branch_length.", confidence=".$confidence.", event='".$event."', TAX_ID=".$tax.", path='".implode('/',$nodes)."', method=1, flagged=".$flagged;
		  		    		$nodeInserted = 1;
		  		    		
		  		    		//echo $tax.': '.$event.' '.$flagged."<br>";
	  			    	}
	  			    	if ($tax){
	  			    		//echo implode('/',$nodes). "<br>";
	  			    		//echo $strSQL .'<br>';
	  			    		$db->query($strSQL); 
		  		    	}
	  			    	$l++;
	  			    }
	  			}
	  			
	  			//leaf
				
	  			if ($child->taxonomy->id){
	  				$gen_name	= $child->sequence->name;
	
	  				
	  				//INSERT SPECIE PATH
	  				
	  				//get IDX
	  				$strSQL = "SELECT IDX FROM datasource_treefam_genes WHERE GID='".$gen_name."' AND TAX_ID=".$specie_TAX_ID;  //FAST
	  				//echo $strSQL.'<br>';
	  				$tmp = $db->get_row($strSQL);
	  				$idx = $tmp->IDX;
	  				
	  				if ($idx){
	  					foreach ($nodes as $n){
	  						
	  						$strSQL = "INSERT IGNORE INTO datasource_treefam_inferred_node_label SELECT SQL_CACHE null, nodeid, 'GENE', 'datasource_treefam_genes', 'IDX', '".$idx."' FROM datasource_treefam_inferred_nodes WHERE tree='".$treefam."' AND tree_node_id='".$n."'";
	  						
	  						$db->query($strSQL);
	  						//echo $strSQL.'<br>';
	  					
	  					}
	  				}
		  		}	
	  		}		
	  	}
/*
	  	if (!$nodeInserted)
	  		array_pop($nodes);	
*/
		getNodes($child,$depth+1,$clade,$path,$nodes,$duplication,$speciation);
		
	  }
	}

	$xml = simplexml_load_file($url);
	
	
	//if the specie exist process else ignore
	$strSQL = "SELECT SQL_CACHE TAX_ID, TAXNAME, path FROM datasource_treefam_species_tree WHERE idspecie_tree=".$idtree;
	$tmp =  $db->get_results($strSQL);
	foreach ($tmp as $r){
		$path_TAX_ID[$r->TAX_ID] = $r->path;
		$path_TAXNAME[$r->TAXNAME] = $r->path;
	}

	
		
	getNodes($xml);
 
?>