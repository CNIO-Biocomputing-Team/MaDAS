<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.mapping.php";
	include_once "lang_EN.php";

	//session
	$userId 	= @$_SESSION['idusers'];
	$pid 		= @$_SESSION['current_project'];
	$annotid 	= @$_REQUEST['annotid'];
	
	$c = new Comodity;
	$p = new Project;
	$map = new Mapping;
	
	//preparing for next step
	$plugin_path = 'plugins_dir/dataSources/data_mapping/';
	$_SESSION['plugin_path'] = $plugin_path;
	
?>
<div class="header1"><b>Map Annotations</b></div>
<div class="pluginBox" style="height:150px;">
<div id="step0" />
<div id="step1">
	<form id="mappingForm" action="<?=$plugin_path?>d-store-new-features-r.php" method="post" enctype="multipart/form-data">
	<input type="hidden" name="annotid" value="<?=$annotid?>" />
	<table align="left" cellpadding="0" cellspacing="10">
		<tbody>
        <tr>
        	<td class="option">Current Feature *</td>
          	<td  class="value">
            	<? 
					$name='current';
					$array = array ('Ensembl Gene ID'=>'ensembl_gene_id', 'HGNC symbol' => 'hgnc_symbol', 'Uniprot/Swissprot ID' => 'uniprot_swissprot', 'Uniprot/Swissprot Accesion'=>'uniprot_swissprot_accession');
					$value = '';
					$class = '';
					include('select-general.php');
				?>  
          	</td>
        </tr>
        <tr>
        	<td class="option">New Feature *</td>
          	<td  class="value">
            	<? 
					$name='new';
					$array = array ('Uniprot/Swissprot ID' => 'uniprot_swissprot', 'Uniprot/Swissprot Accesion'=>'uniprot_swissprot_accession', 'GO ID'=>'go_molecular_function_id', 'GO descrption' => 'go_molecular_function_description', 'PDB'=>'pdb', 'MIM gene accession'=>'mim_gene_accession', 'MIM gene description'=>'mim_gene_description', '% GC content'=>'percentage_gc_content','Anatomical system (Egenetics)'=>'anatomical_system','Development stage (Egenetics)'=>'development_stage','Cell type (Egenetics)'=>'cell_type','Pathology (Egenetics)'=>'pathology','Variation allele'=>'allele');
					$value = '';
					$class = '';
					include('select-general.php');
				?>  
          	</td>
        </tr>
		<tr>
			<td class="option">Organism *</td>
			<td  class="value">
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
<script language="javascript">
	$jQ(document).ready(function() { 
		$jQ("#mappingForm").submit(function() {
			  return false; // cancel conventional submit
		});
		$jQ('#mappingForm').validate({
			submitHandler: function(form) { 
				$jQ(form).ajaxSubmit({
					target: '#step1',
					 beforeSubmit: function(formArray, jqForm) {
					 	$jQ('#step0').prepend('<div class="run"><b>Mapping your data, please wait...</b></div>');
					 	$jQ('#mappingForm').css('visibility','hidden');
				     }
				});
			}

		});	
	});
</script>