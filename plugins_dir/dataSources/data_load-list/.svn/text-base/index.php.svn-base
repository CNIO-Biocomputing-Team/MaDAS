<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.data_sources.php";

	//session
	$userId = @$_SESSION['idusers'];
	$pid =	@$_SESSION['current_project'];
	
	//preparing for next step
	$plugin_path = 'plugins_dir/dataSources/data_load-list/';
	$_SESSION['plugin_path'] = $plugin_path;
	
	$c 	= new Comodity;
	$p 	= new Project;
	$d	= new Data_source;
?>
<script type="text/javascript" src="libs/FlashUploader_102/SolmetraUploader.js"></script>
<div class="header1"><b>Load Gene or Protein List</b></div>
<div class="pluginBox" style="height:480px;">
<div id="step0" />
<div id="step1">
	<form id="pluginForm" action="<?=$plugin_path?>d_index.php" method="post" enctype="multipart/form-data">
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
		                    	<td style="background:url('plugins_dir/dataSources/common/img/square.png') no-repeat;padding-left:15px;width:90px;height:86px;">
		                    		<a href="plugins_dir/dataSources/common/getDsn.php?url=<?=$_SESSION['MaDAS_url']?>plugins_dir/dasServers/das_common-server/das.php/dsn" class="face"><img src="plugins_dir/dataSources/common/img/madas.jpg" border="0"></a>
		                    	</td>
		                        <td>
		                        	<a href="plugins_dir/dataSources/common/getDsn.php?url=<?=$_SESSION['MaDAS_url']?>/plugins_dir/dasServers/das_common-server/das.php/dsn" class="face">MaDAS</a>
		                        </td>
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
	            	<td class="option">3) Paste your gene or protein list<br><small>(One name per line)</small></td>
	              	<td  class="f_value">
	                	<textarea name="gene_list" style="width:300px;height:80px;"></textarea>    
	              	</td>
	            </tr>
<!--
	            <tr>
	            	<td class="option">or upload your gene or protein file<br><small>(One name per line)</small></td>
	                <td  class="value" style="padding-bottom:10px;padding-top:10px;">
	                	<?
	                		$d->flashDiv();
	                	?>                                         
	                </td>
	            </tr>
-->
	            <tr>
	            	<td class="option">Organism *</td>
	              	<td  class="f_value">
	                	<? 
							$name='organism';
							$array = array ('Homo sapiens' => 'hsapiens_gene_ensembl', 'Danio Rerio' => 'drerio_gene_ensembl', 'Gallus Gallus'=>'ggallus_gene_ensembl', 'Mus musculus'=>'mmusculus_gene_ensembl', 'Rattus norvegicus'=>'rnorvegicus_gene_ensembl');
							$value = '';
							$class = '';
							include('select-general.php');
						?>  
	              	</td>
	            </tr>
	            <tr>
	            	<td class="option">Format *</td>
	              	<td  class="f_value">
	                	<? 
							$name='format';
							$array = array ('Ensembl Gene ID'=>'ensembl_gene_id', 'HGNC symbol' => 'hgnc_symbol', 'Uniprot/Swissprot ID' => 'uniprot_swissprot', 'Uniprot/Swissprot Accesion'=>'uniprot_swissprot_accession');
							$value = '';
							$class = '';
							include('select-general.php');
						?>  
	              	</td>
	            </tr>
	            <tr>
	            	<td />
	                <td class="f_value" style="padding-top:10px;">
	                	<input type="submit" value="Next" class="button" />&nbsp;<input type="reset" value="Clear" class="button" />
	                </td>
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
		$jQ("#pluginForm").submit(function() {
			  return false; // cancel conventional submit
		});
		$jQ('#pluginForm').validate({
			submitHandler: function(form) { 
				$jQ(form).ajaxSubmit({
					target: '#step1',
					 beforeSubmit: function(formArray, jqForm) {
					 	$jQ('#step0').prepend('<div class="run"><b>Processing your data, please wait...</b></div>');
					 	$jQ('#pluginForm').css('visibility','hidden');
				     }
				});
			}
		});	
	});
</script>
