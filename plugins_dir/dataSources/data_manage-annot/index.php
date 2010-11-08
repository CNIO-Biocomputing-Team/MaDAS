<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);

	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.manage-annot.php";
	
	
	//preparing for next step
	$plugin_path = 'plugins_dir/dataSources/data_manage-annot/';
	$_SESSION['plugin_path'] = $plugin_path;
?>
<div class="header1"><b>Manage Annotations</b></div>
<table id="reference-list" style="display:none"></table> 
<script language="JavaScript">

jQuery.fn.annotEdit = function(com,grid) {
	
	
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
            $jQ("#dataSource").load('<?=$plugin_path?>v_annot_edit.php?annotid='+id);
            $jQ("#options2").append($jQ('<span id="annot_list"><span>|</span> <a href="#" onclick=$jQ("#dataSource").load("<?=$plugin_path?>index.php");$jQ("#annot_list").remove();>Annotation List</a></span>'));
        }

}


jQuery.fn.annotDelete = function(com,grid) {
	
	
	var items = $jQ('.trSelected');
        var id = '';
		
        if (items.length == 0){
                alert('Select one reference to delete');
        }else if (items.length >1){
                alert('Select only one reference to delete');
        }else{
                id = items[0].id.substring(3);
        }	
	if (confirm("Are you sure that you want to delete this set of annotations?. this operation could not be undone")){
          if (id)	{
            $jQ("#dataSource").load('<?=$plugin_path?>v_annot_delete.php?delete=true&annotid='+id);
            $jQ("#options2").append($jQ('<span id="annot_list"><span>|</span> <a href="#" onclick=$jQ("#dataSource").load("<?=$plugin_path?>index.php");$jQ("#annot_list").remove();>Annotation List</a></span>'));
          }
	}
}
 
 $jQ("#reference-list").flexigrid({
                          url: '<?=$plugin_path?>d_annot_list.php',
                          dataType: 'xml',
                          colModel : [
                                  {display: 'ID', name : 'iddas_commonserver_dsns', width : 40, sortable : true, align: 'center', hide: true},
                                  {display: 'Description', name : 'a.description', width : 300, sortable : true, align: 'left'},
                                  {display: 'DSN', name : 'd.dname', width : 200, sortable : true, align: 'left'},
                                  {display: 'Number of Features', name : 'features', width : 120, sortable : true, align: 'left'},
                                  {display: 'Status', name : 'a.status', width : 58, sortable : true, align: 'left'},
                                  {display: 'Created', name : 'a.created', width : 100, sortable : true, align: 'left'}
                                  ],
                          buttons : [
                          {name: 'Edit', bclass: 'delete', onpress : $jQ('...').annotEdit },
                          {name: 'Delete', bclass: 'delete', onpress : $jQ('...').annotDelete }
                          ],
                          searchitems : [
                                  {display: 'Description', name : 'a.description', isdefault: true},
                                  {display: 'DSN', name : 'd.dname', isdefault: true}
                                  ],
                          sortname: "a.created",
                          sortorder: "desc",
                          usepager: true,
                          title: 'Annotations',
                          useRp: true,
                          rp: 50,
                          showTableToggleBtn: false,
                          width: 840,
                          height: 400
                          }
                          );
</script>