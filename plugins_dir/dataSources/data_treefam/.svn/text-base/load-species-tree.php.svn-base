<?
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";

	$url = 'http://madas2.bioinfo.cnio.es/plugins_dir/dataSources/data_treefam/resources/treefam_taxa_view_reduced_species.xml';
	$idtree = 1;
	
	$reader = new XMLReader();
	$reader->open($url);
	
	$name = '';
	$names = array();
	$path = array();
	
	while ($reader->read()) {
		
	    switch ($reader->nodeType) {
	   
		   case (XMLREADER::ELEMENT):
		   	  if ($reader->localName == "clade") {
		   	  	//array_push($path,$name);
		   	  }
		   	  if ($reader->localName == "name") {
		   	  	$reader->read();
		   	   	$tmp = explode('-',$reader->value);
		   	   	$tax = ereg_replace('[A-Z]|[a-z]|\*|\.|\\\\','',$tmp[1]);
		   	   	$names[$tax] = ereg_replace('[0-9]|\*|\.|\\\\','',$tmp[0]);
		   	   	//$name = $tmp[0];
		   	   	array_push($path,$tax);
		   	  }
		   	  break;
		   
		   case (XMLREADER::END_ELEMENT):
		   	  if ($reader->localName == "clade") {
		   	  	$last = implode('/',$path);
		   	  	$taxid = array_pop($path);
		   	  	$strSQL = "INSERT IGNORE INTO datasource_treefam_species_tree SET idtree = ".$idtree.", TAX_ID =".trim($taxid).", TAXNAME= '".trim($names[$taxid])."', path= '".trim($last)."'";
		   	  	
		   	  	$db->query($strSQL);	
		   	  	
		   	  	
		   	  	echo $strSQL.'<br>';
		   	  }
		   	  break;
		}
	}
	
	$strSQL = "UPDATE datasource_treefam_species_tree t LEFT JOIN datasource_treefam_species s ON (t.TAX_ID = s.TAX_ID) SET internal =1 WHERE s.TAXNAME IS NULL AND idtree =".$idtree;
	$db->query($strSQL);	
?>