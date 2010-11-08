<?
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.madasmap.php";
	include_once "class.paintSearch.php";

	include_once "xmlrpc-2.2.1/lib/xmlrpc.inc";
	
	$c 		= new Comodity;
	$p 		= new Project;
	$map 	= new Madasmap;
	$paint	= new PaintSearch;
	
	//parameters
	$ae	 		= @$_REQUEST['ae'];
	$query 		= trim(@$_REQUEST['keyword']);
	$segment 	= @$_REQUEST['segment'];
		
	//session
	$pid 		= @$_SESSION['current_project'];
	$dsn	 	= @$_SESSION['current_dsn']; 
	
	$img_height	= 530;
	$pstart = 255;
	$pstop  = 275;

?>
	<input type="hidden" name="ae" id="ae" value="<?=$ae?>">
	<input type="text" name="keyword" id="keyword" class="required" style="width:205px;">&nbsp;
	<input type="button" class="button" id="msearch" name="msearch" value="Search" onclick="madas_search('<?=$segment?>')" />
	<input type="button" class="button" id="cancel" name="cancel" value="Cancel" onclick="deactivateSearch()" /><br>
	Search in: MaDAS<input type="radio" class="where" name="where" value="madas" checked="checked"> PubMed<input class="where" type="radio" name="where" value="pubmed">
	<br><br>
<?
	
	if (!$query){
		echo 'Please fill all the required fields';
		exit;
	}

	if ($_REQUEST['where'] == 'madas')
		$results = $map->madas_search($query,$pid,$dsn,$segment);
	else
		$results = $map->pubmed_search($query);	
?>
<div class="header1"><b>Results. [Query:<?=$query?>]</b></div>
<div id="sl-pagination"></div><br>
<div id="sl">
<?
	if ($results){
		if ($_REQUEST['where'] == 'madas')
			$paint->paintMadas($pid,$query,$results,$img_height);
		else{
			$paint->paintPubmed($query,$results,$img_height);
		}	
	}
?>
</div>



<script type="text/javascript">
  	 $jQ.smartlist({
	 	  defaultDropdownOptText: "Todas las etiquetas",
		  numItemsPerPage:3
	 }); 
</script>	
