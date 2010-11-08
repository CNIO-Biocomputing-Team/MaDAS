<?php 
	//requiered initializations
	session_start();
	$_SESSION['gff-launched'] = 0;
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.data_sources.php";
	include_once "class.load-gff.php";

	//session
	$userId = @$_SESSION['idusers'];
	$pid 	= @$_SESSION['current_project'];
	
	//preparing for next step
	$plugin_path = 'plugins_dir/dataSources/data_load-gff/';
	$_SESSION['plugin_path'] = $plugin_path;
	
	$c 		= new Comodity;
	$p 		= new Project;
	$d		= new Data_source;
	$gff 	= new Load_Gff;
?>
<script type="text/javascript" src="libs/FlashUploader_102/SolmetraUploader.js"></script>
<div class="header1"><b>Load Gff</b></div>
<div class="pluginBox" style="height:410px;">
<div id="step0" />
<div id="step1">
	<form id="das_load-gffForm" action="<?=$plugin_path?>d_index.php" method="post" enctype="multipart/form-data">
		<?
			$d->flashInputs();
		?>
	    <input id="id_dsn" name="id_dsn" type="hidden" value="" />
	    <input id="mtype" name="mtype" type="hidden" value="" />
		<table align="left">
			<tbody>
				<tr>
					<td class="option">1) Select your reference Sequence *</td>
					<td  class="f_value" style="padding-bottom:20px;">
						<table cellspacing="5">
						      <tr>
						      <td style="background:url('<?=$plugin_path?>img/square.png') no-repeat;padding-left:15px;width:90px;height:86px;"><a href="plugins_dir/dataSources/common/getDsn.php?url=<?=$_SESSION['MaDAS_url']?>plugins_dir/dasServers/das_common-server/das.php/dsn" class="face"><img src="<?=$plugin_path?>/img/madas.jpg" border="0"></a></td>
						      <td><a href="<?=$plugin_path?>getDsn.php?url=<?=$_SESSION['MaDAS_url']?>/plugins_dir/dasServers/das_common-server/das.php/dsn" class="face">MaDAS</a></td>
						      <td></td>
						  </tr>
						</table>
				  		<span id="dsn_name"></span>
				  	</td>
				</tr>
				<tr>
					<td class="option">2) Type an optional description</td>
					<td  class="f_value">
					  <textarea name="description" style="width:300px;height:80px;"></textarea>    
					</td>
				</tr>
				<tr>
					<td class="option">3) Upload your GFF *</td>
					<td  class="value" style="padding-bottom:10px;padding-top:10px;">
		               	<?
	                		$d->flashDiv();
	                	?>                                            
					</td>
				</tr>
                <tr>
                	<td>&nbsp;</td>
                	<td  class="f_value">
    					<div style="margin-bottom:5px;">Or provide a link to download and insert the GFF</div>
						<input name="remotefile" type="text" style="width:300px;" value="" />
    				</td>
    			</tr>	
                <tr>
                    <td />
                    <td class="f_value" style="padding-top:10px;">
                            <input type="submit" value="Next" class="button" />&nbsp;<input type="reset" value="Clear" class="button" />
                    </td>
                </tr>
                <tr>
                    <td />
                    <td  class="f_value" style="padding-top:10px;">Please read the <a href="http://www.sanger.ac.uk/Software/formats/GFF/GFF_Spec.shtml" title="GFF specification" target="_blank">GFF</a> specification to avoid errors.</td>
                </tr>
			</tbody>
		</table>
	</form>
</div>
</div>
<?
	$d->flashFileConfig();
?>
<script language="javascript">
	$jQ(document).ready(function() { 
		$jQ('.face').facebox();
		$jQ("#das_load-gffForm").submit(function() {
			  return false; // cancel conventional submit
		});
		$jQ('#das_load-gffForm').validate({
			submitHandler: function(form) { 
				$jQ(form).ajaxSubmit({
					target: '#step1',
					 beforeSubmit: function(formArray, jqForm) {
							$jQ('#step0').prepend('<div class="run"><b>Loadding your file, please wait...</b></div>');
							$jQ('#das_load-gffForm').css('visibility','hidden');
				     }
				});
			}
		});	
	});
</script>
