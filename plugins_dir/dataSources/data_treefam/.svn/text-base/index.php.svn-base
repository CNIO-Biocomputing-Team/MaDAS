<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "lang_EN.php";

	//session
	$userId = @$_SESSION['idusers'];
	$pid =	@$_SESSION['current_project'];
	
	//preparing for next step
	$plugin_path = 'plugins_dir/dataSources/data_treefam/';
	$_SESSION['plugin_path'] = $plugin_path;
	
	$c = new Comodity;
	$p = new Project;
	
	
	if (@$_COOKIE['utype'] != 'ROOT'){
		echo '<br><br>';
		$c->mesg("Sorry but this plug-in can be only used by the MaDAS administrator.",false);
		exit;	
	}
?>
<div class="header1"><b>Load TreeFam Files</b></div>
<form id="treefam_form" name="treefam_form" action="<?=$plugin_path?>d_index_r.php" method="post" enctype="multipart/form-data">
	<input id="id_dsn" name="id_dsn" type="hidden" value="" />
	<input id="mtype" name="mtype" type="hidden" value="" />
	<input id="version" name="version" type="hidden" value="" />
	<input type="hidden" name="MAX_FILE_SIZE" value="10000000000" />
<div class="pluginBox" style="height:240px">
	<div id="step1">
		<table align="left">
	    	<tr>
				<td class="option">3) Pick a version *</td>
				<td  class="value">
						<select name="version" title="<br>Please provide a version" class="{required:true}">
							<option value="0">Select one...</option>
							<?php 
								for($i=1;$i<=100;$i++){
									echo '<option value='.$i;
									if ($i == $dsn->dversion)
									 echo ' selected ';
									echo '>'.$i.'</option>';
								}	
							?>
						</select>
				</td>
			</tr>
	    	<tr>
                <td class="option">4) Type an optional description</td>
                <td  class="value">
                  <textarea name="description" style="width:300px;height:80px;"></textarea>    
                </td>
            <tr>
            <tr class="tfile">
			    	<td class="option">5) Provide a link to download and insert the Gene File *</td>
			    	<td  class="value">
			    		<input name="genefile" type="text" style="width:300px;" value="ftp://ftp.sanger.ac.uk/pub/treefam/release-7.0/MySQL/genes.txt.table.gz" />
			    	</td>
			</tr>
	    	<tr class="tfile">
			    	<td class="option">6) Provide a link to  download and insert the Tree File *</td>
			    	<td  class="value">
			    		<input name="treefile" type="text" style="width:300px;" value="ftp://ftp.sanger.ac.uk/pub/treefam/release-7.0/flat_file/trees.tar.gz" />
			    	</td>
			</tr>
			<tr>
			    	<td>&nbsp;</td>
			    	<td class="value">
			    		<input type="submit" value="Submit" name="upload" id="upload" class="button" />
			    		&nbsp;<input type="reset" value="Clear" class="button" />
			    	</td>
			</tr>
		<table>
	</div>
</div>
</form>
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
		
		$jQ('.face').facebox();
	
		 
		 //species
		 <?
			  $multiple = '';
			  $name = 'specie';
			  $table = 'datasource_treefam_species';
			  $vfield = 'TAX_ID';
			  $master = 'FLAG';
			  $masterv = '1';
			  $nfield = 'TAXNAME';
  			  $selected =@$r->TAX_ID;
		?>
		$jQ("#specie").load("<?=$_SESSION['MaDAS_url']?>libs/ajax-select-multiple-simple.php?multiple=<?=$multiple?>&name=<?=$name?>&table=<?=$table?>&master=<?=$master?>&masterv=<?=$masterv?>&selected=<?=$selected?>&vfield=<?=$vfield?>&nfield=<?=$nfield?>&cols=<?=$cols?>");
		
		
		/*form send*/
		var options = { 
			target: '#step1',
					 beforeSubmit: function(formArray, jqForm) {
							$jQ('#step0').append('<div class="run"><b>Loadding your file, please wait...</b></div>');
							
				     }
	 
		}; 
	 
		// bind form using 'ajaxForm' 
		$jQ('#treefam_form').ajaxForm(options); 
		
  });
</script>