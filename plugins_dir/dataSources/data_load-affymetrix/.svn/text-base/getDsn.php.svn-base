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
	include_once "class.load-affymetrix.php";
	include_once "lang_EN.php";
	
	$c 		= new Comodity;
	$p 		= new Project;
	$gff 	= new Load_Affymetrix;
	$reader = new XMLReader();
	
	
	if (!@$_REQUEST['url']){
		echo 'You must provide a DAS server URL';
		exit;
	}	
	
	
	$url = @$_REQUEST['url'];
        
?>	
	<style>
		body{
			color:#333333;
		}
		.dark{
			width:300px;
			background-color:#E4E4E4;
		}
		.normal{
			width:300px;
			background-color:#FFFFFF;
		}
	</style>
    <body>
 	<div style="color:#E3554C">Processing URL: <?=$url?><br><br></div> 	
    <div style="height:500px;overflow:auto;">
	<table>

<?php    	
	$reader->open($url);
	$i=1;
	$use = 0;
	while ($reader->read()) {
		
	   switch ($reader->nodeType) {
	   case (XMLREADER::ELEMENT):
	   	  if ($reader->localName == "SOURCE") {
	   	  	$source_id = $reader->getAttribute("id");
	   	  	$version = $reader->getAttribute("version");

	   	  	
	   	  	//project context
	   	  	$use = 0;
		    if ($reader->getAttribute("project") == $_COOKIE["current_project"])
			  $use = 1; 
	   	    }
	   	    
	   	    
	   	  if ($reader->localName == "MAPMASTER") {
	      	$reader->read();
	      	$mapmaster = $reader->value;
	      }
	      if ($reader->localName == "DESCRIPTION" and $use == 1) {
	       	$reader->read();
	      	$description = $reader->value;
	      	
	      	$sdsn = $gff->getDsnByNameProject($source_id,$_COOKIE["current_project"]);
			echo '<tr><td><input type="radio" name="dsn" value="'.$mapmaster.'" onclick="$jQ(\'#id_dsn\').val(\''.$sdsn->iddas_commonserver_dsns.'\');$jQ(\'#mtype\').val(\''.$sdsn->dmolecule_type.'\');$jQ(\'#dsn_name\').text(\''.$sdsn->dname.'\')"></td><td ';
			if ($i)
				echo 'class="dark"';
			else 
				echo 'class="normal"';	
			echo ' align="left"><table align="left" width="460">';
			echo '<tr><td align="left" style="font-weight:bold;">'.$source_id.'</td></tr>';
			echo '<tr><td align="left">'.$description.'</td></tr>';	
			echo '</table></td></tr>';
			$i=($i==0)?1:0;
	      	
	      	
	      }

	   }
	}



?>
	</table>
    </div>
    </body>