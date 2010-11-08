function madas_search(segment){
     $jQ('#search').load('plugins_dir/visualization/common/v_search.php?where='+$jQ('.where:checked').val()+'&ae='+$jQ('#ae').val()+'&segment='+segment+'&keyword='+$jQ('#keyword').val());
};
function activateSearch(){
	$jQ('#tsearch').hide();
	$jQ('#tdesearch').show();
	$jQ('#tfilter').hide();
	$jQ('#tadd').hide();
	$jQ('#search').toggle();
	$jQ('#map').toggle();
	$jQ("#bubble").hide();
	$jQ("#bubble").empty();
	$jQ("#bubbler").hide();
	$jQ("#bubbler").empty();
}
function deactivateSearch(){
	$jQ('#tsearch').show();
	$jQ('#tdesearch').hide();
	$jQ('#tfilter').show();
	$jQ('#tadd').show();
	$jQ('#search').toggle();
	$jQ('#map').toggle();
	$jQ("#bubble").hide();
	$jQ("#bubble").empty();
	$jQ("#bubbler").hide();
	$jQ("#bubbler").empty();
}
function activateFilter(){
	$jQ('#tfilter').hide();
	$jQ('#tdefilter').show();
	$jQ('#tsearch').hide();
	$jQ('#tadd').hide();
	$jQ('#filter').toggle();
	$jQ('#map').toggle();
	$jQ("#bubble").hide();
	$jQ("#bubble").empty();
	$jQ("#bubbler").hide();
	$jQ("#bubbler").empty();
}
function deactivateFilter(){
	$jQ('#tfilter').show();
	$jQ('#tdefilter').hide();
	$jQ('#tsearch').show();
	$jQ('#tadd').show();
	$jQ('#filter').toggle();
	$jQ('#map').toggle();
	$jQ("#bubble").hide();
	$jQ("#bubble").empty();
	$jQ("#bubbler").hide();
	$jQ("#bubbler").empty();
}
function goToBp(pid,current_dsn,segment,size,bp,y){
	$jQ("#bubble").hide();
	$jQ("#bubble").empty();
	$jQ("#bubbler").hide();
	$jQ("#bubbler").empty();
	map.destroy();
	$jQ("#segment_name").text(segment);
	init(0,pid,current_dsn,segment,size);
	map.setCenter(new OpenLayers.BasePair(bp), 10);
	$jQ('#tsearch').show();
	$jQ('#tdesearch').hide();
	$jQ('#tfilter').show();
	$jQ('#tadd').show();
	$jQ('#search').toggle();
	$jQ('#map').toggle();
	getFeature(436,y,bp);
}
function pubmedPopup(bp,y){
	map.setCenter(new OpenLayers.BasePair(bp), 10);
	$jQ('#tsearch').show();
	$jQ('#tdesearch').hide();
	$jQ('#tfilter').show();
	$jQ('#tadd').show();
	$jQ('#search').toggle();
	$jQ('#map').toggle();
}
