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
?>	
<div class="header1"><b>Manage Reference</b></div>
<table id="reference-list" style="display:none"></table> 
<script language="JavaScript">

jQuery.fn.dsnAdd = function() {
	$jQ("#dataSource").load('<?=$plugin_path?>v_dsn_edit.php');
	$jQ("#options2").append($jQ('<span id="reference_list"><span>|</span> <a href="#" onclick=$jQ("#dataSource").load("<?=$plugin_path?>index.php");$jQ("#reference_list").remove();>Reference List</a></span>'));
	
}


jQuery.fn.dsnEdit = function(com,grid) {
	
	
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
          $jQ("#dataSource").load('<?=$plugin_path?>v_dsn_edit.php?dsnid='+id);
          $jQ("#options2").append($jQ('<span id="reference_list"><span>|</span> <a href="#" onclick=$jQ("#dataSource").load("<?=$plugin_path?>index.php");$jQ("#reference_list").remove();>Reference List</a></span>'));
	}
}


jQuery.fn.dsnDelete = function(com,grid) {
	
	
	var items = $jQ('.trSelected');
        var id = '';
		
        if (items.length == 0){
                alert('Select one reference to delete');
        }else if (items.length >1){
                alert('Select only one reference to delete');
        }else{
                id = items[0].id.substring(3);
        }	
	if (confirm("Are you sure that you want to delete this DSN?. this operation could not be undone")){
          if (id)	{
            $jQ("#dataSource").load('<?=$plugin_path?>v_dsn_edit.php?delete=true&dsnid='+id);
            $jQ("#options2").append($jQ('<span id="reference_list"><span>|</span> <a href="#" onclick=$jQ("#dataSource").load("<?=$plugin_path?>index.php");$jQ("#reference_list").remove();>Reference List</a></span>'));
          }
	}
}
 
 
jQuery.fn.dsnSegments = function(com,grid) {
	
	
	var items = $jQ('.trSelected');
        var id = '';
		
        if (items.length == 0){
                alert('Select one reference to view segments');
        }else if (items.length >1){
                alert('Select only one reference view segments');
        }else{
                id = items[0].id.substring(3);
        }	
	
	if (id)	{
          $jQ("#dataSource").load('<?=$plugin_path?>v_segment_list.php?dsnid='+id);
          
          $jQ("#options2").append($jQ('<span id="reference_list"><span>|</span> <a href="#" onclick=$jQ("#dataSource").load("<?=$plugin_path?>index.php");$jQ("#reference_list").remove();$jQ("#segment_list").remove();>Reference List</a></span>'));
          
          $jQ("#options2").append($jQ('<span id="segment_list"><span>|</span> <a href="#" onclick=$jQ("#dataSource").load("<?=$plugin_path?>v_segment_list.php?dsnid='+id+'");>Segment List</a></span>'));
	}
}


 $jQ("#reference-list").flexigrid({
                          url: '<?=$plugin_path?>d_dsn_list.php',
                          dataType: 'xml',
                          colModel : [
                                  {display: 'ID', name : 'iddas_commonserver_dsns', width : 40, sortable : true, align: 'center', hide: true},
                                  {display: 'Name', name : 'dname', width : 340, sortable : true, align: 'left'},
                                  {display: 'Version', name : 'dversion', width : 40, sortable : true, align: 'left'},
                                  {display: 'Molecule type', name : 'dmolecule_type', width : 70, sortable : true, align: 'left'},
                                  {display: 'Num segments', name : 'segments', width : 80, sortable : true, align: 'left'},
                                  {display: 'Created', name : 'dcreated', width : 110, sortable : true, align: 'left'},
                                  {display: 'By', name : 'name', width : 120, sortable : true, align: 'left'}
                                  ],
                          buttons : [
                          {name: 'Add New', bclass: 'delete', onpress : $jQ('...').dsnAdd },
                          {name: 'Edit', bclass: 'delete', onpress : $jQ('...').dsnEdit },
                          {name: 'Delete', bclass: 'delete', onpress : $jQ('...').dsnDelete },
                          {name: 'View MaDAS Segments', bclass: 'delete', onpress : $jQ('...').dsnSegments }
                          ],
                          searchitems : [
                                  {display: 'Name', name : 'dname', isdefault: true},
                                  {display: 'Version', name : 'dversion', isdefault: true},
                                  {display: 'Molecule type', name : 'mname', isdefault: true}

                                  ],
                          sortname: "dname",
                          sortorder: "asc",
                          usepager: true,
                          title: 'Reference Sequences',
                          useRp: true,
                          rp: 40,
                          showTableToggleBtn: false,
                          width: 840,
                          height: 400
                          }
                          );
</script>