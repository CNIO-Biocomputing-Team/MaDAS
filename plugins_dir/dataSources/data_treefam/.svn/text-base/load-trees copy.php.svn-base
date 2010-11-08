<?
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "../../../libs/ez_sql.php";

	$file = $_GET['file'];

	$url = 'http://madas2.bioinfo.cnio.es/plugins_dir/dataSources/data_treefam/'.$file;
	$idtree = 1;
	
	$reader = new XMLReader();
	$reader->open($url);
	
	$name = '';
	$organismTAX = array();
	$path = array();
	
	
	$inTaxonomy = 0;
	while ($reader->read()) {
		
	    switch ($reader->nodeType) {
	   
		   case (XMLREADER::ELEMENT):
		   	  if ($reader->localName == "clade") {
		   	  	$taxID = '';
		   	  	$inTaxonomy = 0;
		   	  }
		   	  if ($reader->localName == "taxonomy") {
		   	  	$inTaxonomy = 1;
		   	  }
	
			

		   	  if ($reader->localName == "id") {
		   	  	 if ($inTaxonomy){
		   	  	 	$reader->read();
		   	  	 	$taxID = $reader->value;
		   	  	 }
		   	  }
		   	  	
		   	  if ($reader->localName == "code") {
		   	  	
		   	  	$reader->read();
		   	   	$taxNAME = $reader->value;
		   	/*    	if (!in_array($taxNAME,$path)) */
		   	   		array_push($path,$taxNAME);
		   	  }
		   	  
		   	  if ($reader->localName == "name") {
		   	  	$reader->read();
		   	   	$name = $reader->value;
		   	   	$nameAdded = 1;
		   	   	array_push($path,$name);
		   	  }
		   	  break;
		   
		   case (XMLREADER::END_ELEMENT):
		   	  if ($reader->localName == "clade") {
		   	  	
		   	  	$symbol = array_pop($path);
		   	  	if ($nameAdded == 1)
		   	  		array_pop($path);
		   	  	$nameAdded = 0;	
		   	  	$last = implode('//',$path);
		   	  	if ($last){
		   	  		
		   	  		$strSQL1 = "SELECT path FROM datasource_treefam_taxonomy WHERE idtree=3 AND TAX_ID=".$taxID;
			   	  	$tmp = $db->get_row($strSQL1);
			   	  	if ($tmp){
				   	  	$specie_path = explode('/',$tmp->path);
				   	  	foreach ($specie_path as $p){
				   	  		$strSQL2 = "SELECT TAXNAME FROM datasource_treefam_taxonomy WHERE idtree=3 AND TAX_ID=".$p;
				   	  		$tmp2 =  $db->get_row($strSQL2);
				   	  		//echo $tmp2->TAXNAME.' ';
				   	  		if (in_array($tmp2->TAXNAME,$path)){
				   	  		
				   	  		
				   	  			$strSQL = "UPDATE datasource_treefam_genes SET path = '".trim(implode('/',$specie_path))."' WHERE GID='".trim($symbol)."' AND TAX_ID=".$taxID;
				   	  			echo $strSQL.'<br>';
				   	  			$db->query($strSQL);	
				   	  			break;
				   	  		}else{
				   	  			array_shift($specie_path);
				   	  		}
		
				   	  	}
			   	  	}
			   	  	//echo '<br>';
		   	  	}
		   	  }
		   	  if ($reader->localName == "taxonomy") {
		   	  	$inTaxonomy = 0;
		   	  }
		   	  break;
		}
	}

?>