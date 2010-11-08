<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);

	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.set-reference.php";
	
	
	//preparing for next step
	$plugin_path = 'plugins_dir/dataSources/data_set-reference/';
	$_SESSION['plugin_path'] = $plugin_path;
	
	$dsn_id = @$_REQUEST['dsnid'];
	
?>
<div class="header1"><b>Manage Reference</b></div>
<table id="reference-list" style="display:none"></table> 
<script language="JavaScript">

jQuery.fn.segmentAdd = function() {
	$jQ("#dataSource").load('<?=$plugin_path?>v_segment_edit.php?dsn_id=<?=$dsn_id?>');
	
}


jQuery.fn.segmentEdit = function(com,grid) {
	
	
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
          $jQ("#dataSource").load('<?=$plugin_path?>v_segment_edit.php?id='+id);
	}
}


jQuery.fn.segmentDelete = function(com,grid) {
	
	
	var items = $jQ('.trSelected');
        var id = '';
		
        if (items.length == 0){
                alert('Select one segment to delete');
        }else if (items.length >1){
                alert('Select only one segment to delete');
        }else{
                id = items[0].id.substring(3);
        }	
	if (confirm("Are you sure that you want to delete this segment?. this operation could not be undone")){
          if (id)	{
            $jQ("#dataSource").load('<?=$plugin_path?>v_segment_edit_r.php?delete=true&id='+id);
          }
	}
}
 
 $jQ("#reference-list").flexigrid({
                          url: '<?=$plugin_path?>d_segment_list.php?dsnid=<?=$dsn_id?>',
                          dataType: 'xml',
                          colModel : [
                                  {display: 'ID', name : 'iddas_commonserver_segments', width : 40, sortable : true, align: 'center', hide: true},
                                  {display: 'Name', name : 'sname', width : 420, sortable : true, align: 'left'},
                                  {display: 'Start', name : 'sstart', width : 100, sortable : true, align: 'left'},
                                  {display: 'End', name : 'sstop', width : 100, sortable : true, align: 'left'},
                                  {display: 'Created', name : 'created', width : 150, sortable : true, align: 'left'}
                                  ],
                          buttons : [
                          {name: 'Add New', bclass: 'delete', onpress : $jQ('...').segmentAdd },
                          {name: 'Edit', bclass: 'delete', onpress : $jQ('...').segmentEdit },
                          {name: 'Delete', bclass: 'delete', onpress : $jQ('...').segmentDelete }
                          ],
                          searchitems : [
                                  {display: 'Name', name : 'dname', isdefault: true},
                                  {display: 'Version', name : 'dversion', isdefault: true},
                                  {display: 'Molecule type', name : 'mname', isdefault: true}

                                  ],
                          sortname: "created",
                          sortorder: "asc",
                          usepager: true,
                          title: 'Segments',
                          useRp: true,
                          rp: 50,
                          showTableToggleBtn: false,
                          width: 840,
                          height: 440
                          }
                          );
</script>