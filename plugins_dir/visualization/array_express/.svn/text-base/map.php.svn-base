<?
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.madasmap.php";
	
	$c = new Comodity;
	$p = new Project;
	$map = new Madasmap;
	
	//Session
	$plugin_path 	= @$_SESSION['plugin_path'];
	$userId 		= @$_SESSION['idusers'];
	$pid 			= @$_SESSION['current_project'];
	$current_dsn 	= @$_SESSION['current_dsn']; 
	
	//Parameters
	$segment 		= @$_REQUEST['entry_point'];
	$size 			= @$_REQUEST['size'];
	$das_server		= @$_REQUEST['das_servers'];
	$qvalue			= @$_REQUEST['qvalue'];
	
	
	if (!$segment or !$size or !$qvalue){
		echo '<div class="pluginBox" style="height:50px;padding-top:20px;">Please fill all the required fields</div>';
		exit;
	}
	
	$_SESSION['current_segment_name'] = $segment;
	$_SESSION['current_segment_size'] = $size;
?>

<!-- Toolbar -->
<table style="margin-bottom:10px;margin-top:10px;" border="0">
	<tr>
		<td style="width:600px;"><b style="text-transform:uppercase;">Gene expression. Segment <?=$segment?> [<?=$map->formatSize($size)?>] Qvalue: <?=$qvalue?></b>
		</td>
		<td>
			<a href="#" onclick="activateSearch()" id="tsearch">[Search/View results]</a>
			<a style="display:none" href="#" onclick="deactivateSearch()" id="tdesearch">[View Map]</a>
		</td>
	</tr>
</table>

<!-- Search -->
<div style="display:none;background-color:#F2F2F2;border:1px solid #CDCDCD;padding-left:10px;padding-top:20px;padding-bottom:20px;" id="search">
		<input type="hidden" name="ae" id="ae" value="1">
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

<!-- Map -->
<div id="map" class="smallmap" style="width:870px;height:530px;border:1px solid #CDCDCD;background-color:#FFFFFF"></div>
<div id="resolution"></div>


<!-- Bubble -->
<div id="bubble" style="background:url('<?=$plugin_path?>img/bubbleg.png') no-repeat; width:300px; height:400px; display:none; position:absolute; z-index:10000"></div>
<div id="bubbler" style="background:url('<?=$plugin_path?>img/bubblegr.png') no-repeat; width:300px; height:400px; display:none;position:absolute; z-index:10000"></div>

<!-- Edit feature -->
<div id="editF" class="flora"></div>

<script src="<?=$plugin_path?>jquery.madasmap.js" type="text/javascript"></script>
<script type="text/javascript">

    var map, layers,popup;
    var chr = '<?=$segment?>';
    var size = <?=$size?>;
    var bp;
    var server = '<?=$plugin_path?>wms.php?qvalue=<?=$qvalue?>&das_server=<?=$das_server?>&size=<?=$size?>';
    var ds = 588;
    var org = "Human";
    
    
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
	$jQ("#types").load("<?=$_SESSION['MaDAS_url']?>libs/ajax-select-multiple-simple.php?multiple=<?=$multiple?>&name=<?=$name?>&table=<?=$table?>&master=<?=$master?>&masterv=<?=$masterv?>&selected=<?=$selected?>&vfield=<?=$vfield?>&nfield=<?=$nfield?>&cols=<?=$cols?>");
    
     init();

</script>