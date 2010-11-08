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
	include_once "lang_EN.php";

	//session
	$userId = @$_SESSION['idusers'];
	$pid =	@$_SESSION['current_project'];
	$trackid = @$_REQUEST['trackid'];
	
	$c = new Comodity;
	$p = new Project;
	$a = new Manage_Tracks;
	
	
	//preparing for next step
	$plugin_path = 'plugins_dir/dataSources/data_load-das/';
	$_SESSION['plugin_path'] = $plugin_path;
	
	
	$dsns = $a->getDsns($pid);
	
	if ($trackid)
		$r = $a->getTrackById($trackid);

   
?>
<div class="header1"><b>Manage DAS Track</b></div>
<div id="f-track-edit-box" class="pluginBox" style="height:150px;">
    <form name="f-track-edit" method="post" id="f-track-edit" action="<?=$plugin_path?>v-track-edit-r.php">
    	<input type="hidden" name="n_value_idprojects" value="<?=$pid?>" />
    	<input type="hidden" name="n_value_idusers" value="<?=$userId?>" />
    	<input type="hidden" name="id" value="<?=$trackid?>" />
    	<input type="hidden" name="n_value_modified" value="now()" />
        <table class="result" cellpadding="5">
            <tr>
				<td class="option">Reference *</td>
				<td  class="value">
					<select name="n_value_iddsn" id="iddsn" title="<br>Please provide a DSN" class="{required:true}">
					    <option value="0">Select one...</option>
					    <?php 
					    	foreach($dsns as $d){
					    		echo '<option value='.$d->iddas_commonserver_dsns;
					    		if ($r->iddsn == $d->iddas_commonserver_dsns)
					    			echo ' selected="selected" ';
					    		echo '>'.$d->dname.' [version '.$d->dversion.']</option>';
					    	}
					    ?>
					</select>
				<td>
			</tr>
            <tr>
            	<td class="option">DAS URL *</td>
            	<td class="value"><input type="text" name="t_value_url" value="<?=$c->readSQL(@$r->url)?>" size="100" /></td>
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
                	<input class="button" type="button" name="cancel" id="cancel" value="Cancel" onclick="$jQ('#dataSource').load('<?=$plugin_path?>index.php');$jQ('#track_list').remove();">
                </td>
            </tr>
        </table>
    </form>
</div>
<div>
	<p><b>INACTIVE</b> DAS tracks are not available for analysis or visualization.</p>
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
			target:        '#f-track-edit-box',   // target element(s) to be updated with server response 
			beforeSubmit:  showRequest  // pre-submit callback 
	 
		}; 
	 
		// bind form using 'ajaxForm' 
		$jQ('#f-track-edit').ajaxForm(options); 
		
  });
</script>