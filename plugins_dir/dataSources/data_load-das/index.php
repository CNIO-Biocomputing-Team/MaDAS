<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);

	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.manage-tracks.php";
	
	
	//preparing for next step
	$plugin_path = 'plugins_dir/dataSources/data_load-das/';
	$_SESSION['plugin_path'] = $plugin_path;
?>
<div class="header1"><b>Manage Das Tracks</b></div>
<table id="reference-list" style="display:none"></table> 
<script language="JavaScript">


jQuery.fn.trackAdd = function(com,grid) {

     $jQ("#dataSource").load('<?=$plugin_path?>v-track-edit.php');
     $jQ("#options2").append($jQ('<span id="track_list"><span>|</span> <a href="#" onclick=$jQ("#dataSource").load("<?=$plugin_path?>index.php");$jQ("#track_list").remove();>Track List</a></span>'));

}

jQuery.fn.trackEdit = function(com,grid) {
	
	
		var items = $jQ('.trSelected');
        var id = '';
		
        if (items.length == 0){
                alert('Select one reference to edit');
        }else if (items.length >1){
                alert('Select only one reference to edit');
        }else{
                id = items[0].id.substring(3);
        }	
	    if (id)	{
            $jQ("#dataSource").load('<?=$plugin_path?>v-track-edit.php?trackid='+id);
            $jQ("#options2").append($jQ('<span id="track_list"><span>|</span> <a href="#" onclick=$jQ("#dataSource").load("<?=$plugin_path?>index.php");$jQ("#track_list").remove();>Track List</a></span>'));
        }

}


jQuery.fn.trackDelete = function(com,grid) {
	
	
	var items = $jQ('.trSelected');
        var id = '';
		
        if (items.length == 0){
                alert('Select one reference to delete');
        }else if (items.length >1){
                alert('Select only one reference to delete');
        }else{
                id = items[0].id.substring(3);
        }	
	if (confirm("Are you sure that you want to delete this DAS track?. this operation could not be undone")){
          if (id)	{
            $jQ("#dataSource").load('<?=$plugin_path?>v-track-edit-r.php?delete=true&id='+id);
            $jQ("#options2").append($jQ('<span id="track_list"><span>|</span> <a href="#" onclick=$jQ("#dataSource").load("<?=$plugin_path?>index.php");$jQ("#track_list").remove();>Track List</a></span>'));
          }
	}
}
 
 $jQ("#reference-list").flexigrid({
                          url: '<?=$plugin_path?>d-index.php',
                          dataType: 'xml',
                          colModel : [
                                  {display: 'ID', name : 't.iddas_commonserver_das_tracks', width : 40, sortable : true, align: 'center', hide: true},
                                  {display: 'DSN', name : 'd.dname', width : 300, sortable : true, align: 'left'},
                                  {display: 'URL', name : 't.url', width : 300, sortable : true, align: 'left'},
                                  {display: 'Status', name : 't.status', width : 58, sortable : true, align: 'left'},
                                  {display: 'Created', name : 't.created', width : 100, sortable : true, align: 'left'}
                                  ],
                          buttons : [
                          {name: 'Add', bclass: 'delete', onpress : $jQ('...').trackAdd },
                          {name: 'Edit', bclass: 'delete', onpress : $jQ('...').trackEdit },
                          {name: 'Delete', bclass: 'delete', onpress : $jQ('...').trackDelete }
                          ],
                          searchitems : [
                                  {display: 'Description', name : 'a.description', isdefault: true},
                                  {display: 'DSN', name : 'd.dname', isdefault: true}
                                  ],
                          sortname: "t.created",
                          sortorder: "desc",
                          usepager: true,
                          title: 'DAS Tracks',
                          useRp: true,
                          rp: 50,
                          showTableToggleBtn: false,
                          width: 840,
                          height: 400
                          }
                          );
</script>