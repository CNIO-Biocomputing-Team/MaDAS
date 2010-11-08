<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.data_sources.php";
	include_once "class.mapping.php";
	include_once "lang_EN.php";

	//session
	$userId = @$_SESSION['idusers'];
	$pid 	= @$_SESSION['current_project'];

	//paremeters
	$annotid 	= @$_REQUEST['annotid'];
	$current	= @$_POST['current'];
	$new		= @$_POST['new'];
	$organism	= @$_POST['organism'];
	
	$c 		= new Comodity;
	$p 		= new Project;
	$d		= new Data_source;
	$map 	= new Mapping;
	
	//check parameters
	$return = 0;

	if (!$annotid or !$current or !$new){
		$c->runMesg($mesg['required_fields'],false);
		$return = 1;
	}
	
	if ($return == 1){
		echo '&nbsp;<a onClick=$jQ(\'#projectsCanvas\').load(\'projects/myProjectManageSource.php?sid='.@$_SESSION['current_data_source'].'\')>Try again</a>';
		exit;	
	}
	
	$genes = $map->getFeatureNamesByAnnot($annotid);
	
	$idfType = $d->storeFtype($new,'','');
	$new_annot = $map->duplicateAnnot($annotid,$new);
	
	//query biomart 
	$biomartQuery = '<?xml version="1.0" encoding="UTF-8"?>
		<!DOCTYPE Query>
		<Query  virtualSchemaName = "default" formatter = "CSV" header = "0" uniqueRows = "1" count = "" datasetConfigVersion = "0.6" >
					
			<Dataset name = "'.$organism.'" interface = "default" >
				<Filter name = "'.$current.'" value = "'.implode(',',$genes).'"/>
					<Attribute name = "ensembl_gene_id" />
					<Attribute name = "chromosome_name" />
					<Attribute name = "start_position" />
					<Attribute name = "end_position" />
					<Attribute name = "strand" />
					<Attribute name = "'.$new.'" />
			</Dataset>
		</Query>';
	
	$res = $c->query_biomart($biomartQuery);
	
	if ($res){
		$map->parseBiomart($res,$new_annot->dsn,$new_annot->mtype,$new_annot->id,$new);
		$c->runMesg($mesg['file_processed'],true);
	}

?>