<?
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.madasmap.php";
	
	$c 		= new Comodity;
	$p 		= new Project;
	$map 	= new Madasmap;
	
	//Session
	$plugin_path 	= @$_SESSION['plugin_path'];
	$userId 		= @$_SESSION['idusers'];
	$pid 			= @$_SESSION['current_project'];
	$current_dsn 	= @$_SESSION['current_dsn']; 
	
	//Parameters
	$segment 		= @$_REQUEST['entry_point'];
	$size 			= @$_REQUEST['size'];
	$das_server		= @$_REQUEST['das_servers'];
	
	if (!$segment or !$size ){
		echo '<div class="pluginBox" style="height:50px;padding-top:20px;">Please fill all the required fields</div>';
		exit;
	}
	
	$_SESSION['current_segment_name'] = $segment;
	$_SESSION['current_segment_size'] = $size;
?>

<!-- Toolbar -->
<table style="margin-bottom:10px;margin-top:10px;" border="0">
	<tr>
		<td>
			<a href="#" onclick="activateSearch()" id="tsearch">[Search/View results]</a>
			&nbsp;<a href="#" onclick="activateFilter()" id="tfilter">[Filter]</a>
			&nbsp;<a href="#" onclick="$jQ('...').editF()" id="tadd">[Add new annotation]</a>
			<a style="display:none" href="#" onclick="deactivateSearch()" id="tdesearch">[View Map]</a>
			<a style="display:none" href="#" onclick="deactivateFilter()" id="tdefilter">[View Map]</a>
		</td>
	</tr>
</table>

<!-- Search -->
<div style="display:none;background-color:#F2F2F2;border:1px solid #CDCDCD;padding-left:10px;padding-top:20px;padding-bottom:20px;" id="search">
		<input type="hidden" name="ae" id="ae" value="0">
		<input type="text" name="keyword" id="keyword" class="required" style="width:205px;">&nbsp;
		<input type="button" class="button" id="msearch" name="msearch" value="Search" onclick="madas_search('<?=$segment?>')" />
		<input type="button" class="button" id="cancel" name="cancel" value="Cancel" onclick="deactivateSearch()" /><br>
		Search in: MaDAS<input type="radio" class="where" name="where" value="madas" checked="checked"> PubMed (Beta)<input class="where" type="radio" name="where" value="pubmed">
		<div>
			<p><b>HELP:</b></p>
			<p><b>MaDAS search:</b></p>
			<p></p> Start searching for any keywords related to your MaDAS annotations (i.e. Gene Id, Protein ACC, Disease name etc.). Using MaDAS search you can query our system to find the location of any of your annotated features.</p>
			<p><b>PubMed search:</b></p>
			<p>Using Pubmed search you can find which genes are related to your article and locate these genes in the Genome to perform further annotations.</p>
			<p>We use a text mining tool (<a href="http://bcms.bioinfo.cnio.es/" target="_blank">http://bcms.bioinfo.cnio.es/</a> , Leitner F et al., Genome Biology 2008, 9(Suppl 2):S6) to extract these relevant genes.</p>
			<p>Currently only a small number of Papers (~22000) are available to perform the search. In a nearest feature the full Pubmed abstract collection will be available.</p>
			<p>Type a <b>Pubmed ID</b> to start the search. (Example: 16828757) 
</p>
		</div>
</div>

<!-- Filter features -->
<div style="display:none;background-color:#F2F2F2;border:1px solid #CDCDCD;padding:10px;" id="filter">
	<div id="types"></div>
	<br>
	<input type="button" class="button" id="accept" name="accept" value="Select" onclick="addTypes()" />
	<input type="button" class="button" id="cancel" name="cancel" value="Cancel" onclick="deactivateFilter()" />
</div>
<!-- Edit feature -->
<div id="editF" class="flora"></div>
<div id="madasmap1"></div>
<script type="text/javascript">

	
	jQuery.fn.editF = function(parameters){
    
	    $jQ("#bubble").hide();
		$jQ("#bubble").empty();
		$jQ("#bubbler").hide();
		$jQ("#bubbler").empty();
	
		if (parameters){
	 		$jQ('#editF').load('plugins_dir/visualization/madasmap/v_feature_edit.php?param='+parameters);
	 	}else{
	 		$jQ('#editF').load('plugins_dir/visualization/madasmap/v_feature_edit.php');
	 	}
		$jQ('#editF').dialog({title:'Add/Edit Feature', modal:true,overlay:{opacity: 0.2,background: "black"} ,width:500,height:500,close:function(){
	 			/* $jQ('#editF').empty(); */
	 	}});
	
	}
	
	
	das_server	= <?=$das_server?>;
	pid			= <?=$pid?>;
	dsn			= '<?=$current_dsn?>';
	segment		= '<?=$segment?>';
	size		= <?=$size?>;		
		
	$jQ.bwe.madasmap.instance($jQ('#madasmap1'),segment,820,500,true);

	//Types
	<?
	     $multiple = 5;
	     $name = 'view_types';
	     $table = 'das_commonserver_types';
	     $vfield = 'tname';
	     $master = '';
	     $masterv = '';
	     $nfield = 'tname';
  	     $selected = $types;
	?>
/* 	$jQ("#types").load("<?=$_SESSION['MaDAS_url']?>libs/ajax-select-multiple-simple.php?multiple=<?=$multiple?>&name=<?=$name?>&table=<?=$table?>&master=<?=$master?>&masterv=<?=$masterv?>&selected=<?=$selected?>&vfield=<?=$vfield?>&nfield=<?=$nfield?>&cols=<?=$cols?>"); */

</script>
