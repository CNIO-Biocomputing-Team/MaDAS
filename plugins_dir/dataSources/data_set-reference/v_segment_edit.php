<?
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.set-reference.php";
	
	$c = new Comodity;
	$sr =  new Set_Reference; 
	
	$plugin_path = 'plugins_dir/dataSources/data_set-reference/';
	$_SESSION['plugin_path'] = $plugin_path;
	
	$id = @$_REQUEST['id'];
	
	if ($id){
		$r = $sr->getSegmentById($id);
	}
?>
<div class="header1"><b>Manage Reference</b></div>
<div id="f-segments-edit-box" class="pluginBox" style="height:390px;">
    <form name="f-segment-edit" method="post" id="f-segment-edit" action="<?=$plugin_path?>v_segment_edit_r.php">
    	<?
    		if (@$_REQUEST['dsn_id'])
    			echo '<input type="hidden" name="n_value_iddas_commonserver_dsns" value="'.@$_REQUEST['dsn_id'].'" />';
    	?>
    	<input type="hidden" name="id" value="<?=@$_REQUEST['id']?>" />
    	<input type="hidden" name="n_value_modified" value="now()" />
        <table class="result" cellspaccing="0">
            <tr><td class="option">Name *</td><td class="value"><input class="required" type="text" name="t_value_sname" value="<?=$c->readSQL(@$r->sname)?>" size="50" /></td></tr>
            <tr><td class="option">Description</td><td class="value"><input type="text" name="t_value_sdescription" value="<?=$c->readSQL(@$r->sdescription)?>" size="50" /></td></tr>
             <tr>
                <td class="option">molecule type *</td>
                <td class="value">
                    <?
						$name = 'n_value_idmolecule_types';
						$class = 'required';
                        include('ajax-select-general.php');
                    ?>
                </td>
            </tr>

			<tr><td class="option">Start</td><td class="value"><input type="text" name="t_value_sstart" value="<?=$c->readSQL(@$r->sstart)?>" size="20" /></td></tr>
			<tr><td class="option">Stop </td><td class="value"><input type="text" name="t_value_sstop" value="<?=$c->readSQL(@$r->sstop)?>" size="20" /></td></tr>
			<tr><td class="option">orientation</td><td class="value"><input  type="text" name="t_value_sorientation" value="<?=$c->readSQL(@$r->sorientation)?>" size="20" /></td></tr>
            <tr><td class="option">version *</td><td class="value"><input class="required" type="text" name="t_value_sversion" value="<?=$c->readSQL(@$r->sversion)?>" size="20" /></td></tr>
            <tr><td class="option">Sequence</td><td class="value"><textarea name="t_value_ssequence" style="width:355px;height:150px;"><?=$c->readSQL(@$r->ssequence)?></textarea></td></tr>
    
	        <tr>
                <td>&nbsp;</td>
                <td class="value"><input class="button" type="submit" name="submit" id="submit" value="Save"></td>
            </tr>
        </table>
    </form>
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
		
		 //molecule type
		 <?
			  $table = 'molecule_types';
			  $vfield = 'idmolecule_types';
			  $master = '';
			  $masterv = '';
			  $nfield = 'mname';
  			  $selected =@$r->idmolecule_types;
		?>
		$jQ("#n_value_idmolecule_types").load("<?=$_SESSION['MaDAS_url']?>libs/ajax-options-general.php?table=<?=$table?>&master=<?=$master?>&masterv=<?=$masterv?>&selected=<?=$selected?>&vfield=<?=$vfield?>&nfield=<?=$nfield?>");
		
		
		/*form send*/
		var options = { 
			target:        '#f-segments-edit-box',   // target element(s) to be updated with server response 
			beforeSubmit:  showRequest  // pre-submit callback 
	 
		}; 
	 
		// bind form using 'ajaxForm' 
		$jQ('#f-segment-edit').ajaxForm(options); 
		
  });
</script>