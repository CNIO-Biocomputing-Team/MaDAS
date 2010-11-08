<?
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.madasmap.php";
	
	$c 		= new Comodity;
	$map 	= new Madasmap;
	
	$plugin_path = 'plugins_dir/visualization/madasmap/';
	$_SESSION['plugin_path'] = $plugin_path;
	
	//parameters
	if (@$_REQUEST['param']){
	
		$param = explode(',',base64_decode($_REQUEST['param']));
		
		
		$id 			= $c->declean_str_type($param[0]);
		$type			= $param[1];
		$start			= $param[2];
		$end			= $param[3];
		$score			= $param[4];
		$orientation	= $param[5];
		$phase			= $param[6];
		$note			= $param[7];
		$version		= $param[8];
	
	}
	
	$newVersion 	= ($version)?$version+1:1;
	
	//session
	$userId 			= @$_SESSION['idusers'];
	$uname				= @$_SESSION['name']; 
	$pid 				= @$_COOKIE['current_project'];
	$iddsn				= @$_SESSION['current_iddsn'];
	$idmolecule_type	= @$_SESSION['current_idmolecule_type'];
	$segment_name		= @$_SESSION['current_segment_name'];
	$segment_size		= @$_SESSION['current_segment_size'];
	
	
	$segmentId			= $map->storeSegment($iddsn,$idmolecule_type,$segment_name,$segment_size);
	$annotationId		= $map->storeAnnotation($iddsn,$pid,$userId,$uname);

?>

<div id="f-segments-edit-box">
    <?
    	if (@$_REQUEST['param']){
    ?>
    <div>
    	<p>NOTE: Your feature will be saved as a copy.</p>
    </div>
    <?
    	}
    ?>
    <form name="f-segment-edit" method="post" id="f-segment-edit" action="<?=$plugin_path?>v_feature_edit_r.php">
    	<input type="hidden" name="n_value_iddas_commonserver_segments" value="<?=$segmentId?>" />
    	<input type="hidden" name="n_value_iddas_commonserver_annotations" value="<?=$annotationId?>" />
    	<input type="hidden" name="n_value_idusers" value="<?=$userId?>" />
    	<input type="hidden" name="n_value_version" value="<?=$newVersion?>" />
    	<input type="hidden" name="t_value_label" id="t_value_label" value="<?=$id?>" />
    	<input type="hidden" name="n_value_modified" value="now()" />
        <table class="result" cellspaccing="0">
            <tr><td class="option">ID/Label *</td><td class="value"><input class="edit_required" type="text" id="t_value_id" name="t_value_id" value="<?=$id?>" size="50" /></td></tr>
             <tr>
                <td class="option">Type *</td>
                <td class="value">
                    <?
						$name = 'n_value_iddas_commonserver_types';
						$class = 'edit_required';
						include('ajax-select-general.php');
                    ?>
                </td>
            </tr>

			<tr><td class="option">Start *</td><td class="value"><input class="edit_required" type="text" name="t_value_start" value="<?=$start?>" size="20" /></td></tr>
			<tr><td class="option">End *</td><td class="value"><input class="edit_required" type="text" name="t_value_stop" value="<?=$end?>" size="20" /></td></tr>
			<tr><td class="option">Score</td><td class="value"><input  type="text" name="t_value_score" value="<?=$score?>" size="20" /></td></tr>
			<tr>
				<td class="option">Orientation *</td>
				<td class="value">
					<? 
						$name='t_value_orientation';
						$array = array ('0' => '0', '+' => '+', '-' => '-');
						$value = $orientation;
						$class = 'edit_required';
						include('select-general.php');
					?>
				</td>
			</tr>
			<tr>
				<td class="option">Phase *</td>
				<td class="value">
					<? 
						$name='t_value_phase';
						$array = array ('0' => '0', '1' => '1' , '2' => '2', '-' => '-');
						$value = $phase;
						$class = 'edit_required';
						include('select-general.php');
					?>
				</td>
			</tr>
            <tr><td class="option">Note</td><td class="value"><textarea name="x_value_note" style="width:355px;height:150px;"><?=$note?></textarea></td></tr>
    		<tr><td class="option">Link text</td><td class="value"><input size="50" type="text" name="x_value_link_text" value="<?=$link_text?>" size="20" /></td></tr>
    		<tr><td class="option">Link url</td><td class="value"><input size="50" type="text" name="x_value_link_href" value="<?=$link_href?>" size="20" /></td></tr>
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
		$jQ('.edit_required').each(
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
		
		$jQ('#t_value_label').val($jQ('#t_value_id').val());
		
		return true; 
	} 
	 
	
  $jQ(document).ready(function(){
		
		 //molecule type
		 <?
			  $table = 'das_commonserver_types';
			  $vfield = 'iddas_commonserver_types';
			  $master = '';
			  $masterv = '';
			  $nfield = 'tname';
			  $selected = $type;

		?>
		$jQ("#n_value_iddas_commonserver_types").load("<?=$_SESSION['MaDAS_url']?>libs/ajax-options-general.php?table=<?=$table?>&master=<?=$master?>&masterv=<?=$masterv?>&selected=<?=$selected?>&vfield=<?=$vfield?>&nfield=<?=$nfield?>");
		
		
		/*form send*/
		var options = { 
			target:        '#f-segments-edit-box',   // target element(s) to be updated with server response 
			beforeSubmit:  showRequest  // pre-submit callback 
	 
		}; 
	 
		// bind form using 'ajaxForm' 
		$jQ('#f-segment-edit').ajaxForm(options); 
		
  });
</script>