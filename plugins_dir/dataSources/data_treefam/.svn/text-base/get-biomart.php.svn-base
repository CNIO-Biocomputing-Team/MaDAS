<?php
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "../../../libs/ez_sql.php";
	include_once "../../../libs/class.comodity.php";
	
	$c = new Comodity;


	//SET
	$taxes	  = array(6239,7159,7165,7227,7719,7955,8364,9544,9598,9606,9615,9913,10090,10116,13616,51511,69293,99883);
	$taxes	  = array(10116,13616,51511);
	$feature = 'go_biological_process_id';

	foreach ($taxes as $TAX_ID){


		//get organism
		$strSQL = "SELECT biomart FROM datasource_treefam_species_tree WHERE TAX_ID=".$TAX_ID;
		$tmp = $db->get_row($strSQL);
		$organism = $tmp->biomart;
		
		if ($organism){
		
			//get id tag
			$strSQL = "SELECT id FROM datasource_treefam_tags WHERE tag='".$feature."'";
			$tmp = $db->get_row($strSQL);
			$tag = $tmp->id;
			
			//get genes
			$genes = array();
			$strSQL = "SELECT GID from datasource_treefam_genes WHERE TAX_ID = ".$TAX_ID;
			$tmp = $db->get_results($strSQL);
			foreach ($tmp as $t)
				array_push($genes,$t->GID);
		
			//query biomart 
			$biomartQuery = '<?xml version="1.0" encoding="UTF-8"?>
		<!DOCTYPE Query>
		<Query  virtualSchemaName = "default" formatter = "CSV" header = "0" uniqueRows = "1" count = "" datasetConfigVersion = "0.6" >
					
			<Dataset name = "'.$organism.'" interface = "default" >
				<Filter name = "ensembl_gene_id" value = "'.implode(',',$genes).'"/>
				<Attribute name = "ensembl_gene_id" />
				<Attribute name = "'.$feature.'" />
			</Dataset>
		</Query>';
		
			$res = $c->query_biomart($biomartQuery);
			
			foreach ($res as $r){
				$line = explode(',',$r);
				$GID = $line[0];
				$f = $line[1];
				if ($f){
					$strSQL = "INSERT INTO datasource_treefam_tag_values SET idtag = ".$tag.", GID = '".$GID."', string_value='".$f."'";
					$db->query($strSQL);
				}
			}
	
	
		}

	}

?>