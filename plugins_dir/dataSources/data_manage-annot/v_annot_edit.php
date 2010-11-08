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
	include_once "lang_EN.php";

	//session
	$userId = @$_SESSION['idusers'];
	$pid =	@$_SESSION['current_project'];
	$annotid = @$_REQUEST['annotid'];
	
	$c = new Comodity;
	$p = new Project;
	$a = new Manage_Annotations;
	
	
	//preparing for next step
	$plugin_path = 'plugins_dir/dataSources/data_manage-annot/';
	$_SESSION['plugin_path'] = $plugin_path;
	
	$r = $a->getAnnotById($annotid);
   
?>
<div class="header1"><b>Manage Annotations</b></div>
<div id="f-annot-edit-box" class="pluginBox" style="height:100px;">
    <form name="f-annot-edit" method="post" id="f-annot-edit" action="<?=$plugin_path?>v_annot_edit_r.php">
    	<input type="hidden" name="id" value="<?=$annotid?>" />
    	<input type="hidden" name="n_value_modified" value="now()" />
        <table class="result" cellpadding="5">
            <tr>
            	<td class="option">Description *</td>
            	<td class="value"><input type="text" class="required" name="t_value_description" value="<?=$c->readSQL(@$r->description)?>" size="50" /></td>
            </tr>
            <tr>
                <td class="option">Status *</td>
                <td class="value">
                    <? 
						$name='t_value_status';
						$array = array ('ACTIVE' => 'ACTIVE', 'INACTIVE' => 'INACTIVE');
						$value = $r->status;
						$class = 'required';
						include('select-general.php');
					?>
                </td>
            </tr>
	        <tr>
                <td>&nbsp;</td>
                <td class="value">
                	<input class="button" type="submit" name="submit" id="submit" value="Save">&nbsp;
                	<input class="button" type="button" name="cancel" id="cancel" value="Cancel" onclick="$jQ('#dataSource').load('<?=$plugin_path?>index.php');$jQ('#annot_list').remove();">
                </td>
            </tr>
        </table>
    </form>
</div>
<div>
	<p><b>INACTIVE</b> annotations are not available for analysis or visualization.</p>
</div>
<script type="text/javascript">

	// pre-submit callback 
	function showRequest(formData, jqForm, options) { 
		
		//requeridos
		var flag = 0;
		$jQ('.required').each(
			function( intIndex ){
				if ($jQ(this).val() == ''){
					$jQ(this).addClass("red");
					flag=1;
				}else{
					$jQ(this).removeClass("red");
				}
			} 
		)
		
		if (flag){
			alert('Please fill all the required filds');
			return false;
		}	
		
		
		
		return true; 
	} 
	 
	
  $jQ(document).ready(function(){
		
				
		
		/*form send*/
		var options = { 
			target:        '#f-annot-edit-box',   // target element(s) to be updated with server response 
			beforeSubmit:  showRequest  // pre-submit callback 
	 
		}; 
	 
		// bind form using 'ajaxForm' 
		$jQ('#f-annot-edit').ajaxForm(options); 
		
  });
</script>