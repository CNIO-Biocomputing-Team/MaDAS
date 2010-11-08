<?php 
	//requiered initializations
	session_start();
	ob_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.data_sources.php";
	include_once "class.load-list.php";
	include_once "lang_EN.php";
	include_once "FlashUploader_102/SolmetraUploader.php";
	
	$solmetraUploader = new SolmetraUploader();
	$solmetraUploader->gatherUploadedFiles();
	
	$c 	= new Comodity;
	$p 	= new Project;
	$d	= new Data_source;
	$l	= new Load_list; 
	
	//session
	$userId			= @$_SESSION['idusers'];
	$uemail     	= @$_SESSION['email'];
	$pid			= @$_SESSION['current_project'];
	$plugin_path	= @$_SESSION['plugin_path'];
	$target_path    = 'tmp/';
	
	//requests
    $id_dsn 		= @$_POST['id_dsn'];
    $mtype 			= @$_POST['mtype'];
    $description 	= @$_POST['description'];
    $gene_list		= @$_POST['gene_list'];
	$file 			= @$_FILES['flash-file'];
	$format			= @$_POST['format'];
	$organism		= @$_POST['organism'];
	
	//check parameters
	$return = 0;

	if (!$id_dsn or !$format or !$organism){
		$c->runMesg($mesg['required_fields'],false);
		$return = 1;
	}
	
	if (!$gene_list and !$file and !$return){
		$c->runMesg($mesg['require_gene_list'],false);
		$return = 1;
	}
	
	if ($return == 1){
		echo '&nbsp;<a onClick=$jQ(\'#projectsCanvas\').load(\'projects/myProjectManageSource.php?sid='.@$_SESSION['current_data_source'].'\')>Try again</a>';
		exit;	
	}
	
	//gene_list
	if ($gene_list){
		$genes = explode("\n",$gene_list);
	}
	
	//query biomart 
	$biomartQuery = '<?xml version="1.0" encoding="UTF-8"?>
		<!DOCTYPE Query>
		<Query  virtualSchemaName = "default" formatter = "CSV" header = "0" uniqueRows = "1" count = "" datasetConfigVersion = "0.6" >
					
			<Dataset name = "'.$organism.'" interface = "default" >
				<Filter name = "'.$format.'" value = "'.implode(',',$genes).'"/>
					<Attribute name = "ensembl_gene_id" />
					<Attribute name = "chromosome_name" />
					<Attribute name = "start_position" />
					<Attribute name = "end_position" />
					<Attribute name = "strand" />
					<Attribute name = "external_gene_id" />
			</Dataset>
		</Query>';
	$res = $c->query_biomart($biomartQuery);
	
	if ($res){
		
		$annot = $d->storeAnnotation($pid,$userId,$id_dsn,$description);
		$l->parseBiomart($res,$id_dsn,$mtype,$annot);
		$c->runMesg($mesg['file_processed'],true);
	}
?>
