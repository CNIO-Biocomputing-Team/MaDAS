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
	include_once "lang_EN.php";

	//session
	$userId = @$_SESSION['idusers'];
	$pid =	@$_SESSION['current_project'];
	$dsnid = @$_REQUEST['dsnid'];
	
	//preparing for next step
	$plugin_path = 'plugins_dir/dataSources/data_set-reference/';
	$_SESSION['plugin_path'] = $plugin_path;
	
	$c = new Comodity;
	$p = new Project;
	$reference = new Set_Reference;
	
	
	$mtypes = $reference->getMoleculeTypes();
        
    //delete dsn	
	if ($_REQUEST['delete']) {
      
      $dsn = $reference->deleteDsnById($dsnid);
      echo '<div class="header1"><b>Manage Reference</b></div>';
	  echo '<div class="pluginBox" style="height:100px;">';
      echo '<br><br><div>'.$mesg['dsn_deleted'].'</div>';
	  echo '</div>';
        #edit or add dsn
     }else{ 

            if ($dsnid){
              $dsn = $reference->getDsnById($dsnid);
            }
?>	
<div id="step0" />
<div id="step1">
	<form id="das_load-gffForm" action="<?=$plugin_path?>v_dsn_edit_r.php" method="post" enctype="multipart/form-data">
	<input type="hidden" name="dsnid" value="<?=$dsnid?>">
	<table align="center" cellpadding="0" cellspacing="0">
		<tbody>
				<tr>
					<td colspan="2" style="padding-bottom:10px;"><div class="header1">Set a <b>local reference sequence</b></div></td>
					<td style="padding-left:30px;padding-bottom:10px;"><div class="header1">Or select one <b>external reference</b> from anyone of these sites:</div></td>
				</tr>
                <tr>
                    <td class="option" style="padding-top:15px;background-color:#9BB9DD;border-top:1px solid #426BBD;border-left:1px solid #426BBD;">DSN Name *</td>
                    <td class="value" style="padding-top:15px;background-color:#9BB9DD;border-top:1px solid #426BBD;border-right:1px solid #426BBD;"><input name="dname" type="text" size="45" id="dname" value="<?=$dsn->dname?>" />&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td  class="value" rowspan="6" valign="top" align="center" style="background-color:#FFFFFF;">
	                    <table cellspacing="5" align="center" border="0">
	                       <tr>
	                           <td style="background:url('<?=$plugin_path?>img/square.png') no-repeat;width:90px;height:86px;padding-left:20px;"><a href="<?=$plugin_path?>getDsn.php?mtype=2&url=http://www.ensembl.org/das/dsn" class="face"><img src="<?=$plugin_path?>/img/e-bang.gif" border="0"></a></td>
	                           <td><a href="<?=$plugin_path?>getDsn.php?mtype=2&url=http://www.ensembl.org/das/dsn" class="face">ENSMBL</a></td>
	                       </tr>
	                       <tr>
	                           <td style="background:url('<?=$plugin_path?>img/square.png') no-repeat;width:90px;height:86px;padding-left:20px;"><a href="<?=$plugin_path?>getDsn.php?mtype=1&url=http://www.ebi.ac.uk/das-srv/uniprot/das/dsn" class="face"><img src="<?=$plugin_path?>/img/embl.gif" border="0"></a></td>
	                           <td><a href="<?=$plugin_path?>getDsn.php?mtype=1&url=http://www.ebi.ac.uk/das-srv/uniprot/das/dsn" class="face">EBI</a></td>
	                        </tr>
	                    </table>
                  </td>
                </tr>	
                <tr>
                	<td class="option" style="padding-top:5px;background-color:#9BB9DD;border-left:1px solid #426BBD;">Mapmaster *</td>
                    <td class="value" style="padding-top:5px;background-color:#9BB9DD;border-right:1px solid #426BBD;"><input name="dmap_master" type="text" size="45" id="dmap_master" value="<?=$dsn->dmap_master?>" /></td>
                </tr>	
				<tr>
					<td class="option" style="padding-top:5px;background-color:#9BB9DD;border-left:1px solid #426BBD;">Version *</td>
					<td  class="value" style="padding-top:5px;background-color:#9BB9DD;border-right:1px solid #426BBD;">
						<select name="version" title="<br>Please provide a DSN version" class="{required:true}">
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
					<td class="option" style="padding-top:5px;background-color:#9BB9DD;border-left:1px solid #426BBD;">Molecule Type *</td>
					<td  class="value" style="padding-top:5px;background-color:#9BB9DD;border-right:1px solid #426BBD;">
					<select id="mtype" name="mtype" title="<br>Please select the molecule type" class="{required:true}">
							<option value="0">Select one...</option>
							<?php 
								foreach($mtypes as $mt){
									echo '<option value='.$mt->idmolecule_types;
									if ($mt->idmolecule_types == $dsn->dmolecule_type)
									 echo ' selected ';
									echo '>'.strtoupper($mt->mname).'</option>';
								}	
							?>
						</select>
					</td>
				</tr>
				<tr>
                	<td class="option" style="padding-top:5px;background-color:#9BB9DD;border-left:1px solid #426BBD;">Include sequence</td>
                    <td class="value" style="padding-top:5px;background-color:#9BB9DD;border-right:1px solid #426BBD;"><input name="dinclude_seq" type="checkbox" id="dinclude_seq" <? if (@$dsn->dinclude_seq) echo 'checked';?> /></td>
                </tr>	
				<tr>
					<td class="option" style="padding-top:5px;background-color:#9BB9DD;border-left:1px solid #426BBD;">Description *</td>
					<td  class="value" style="padding-top:5px;background-color:#9BB9DD;border-right:1px solid #426BBD;">
						<textarea name="description" id="description" rows="8" cols="39"><?=$dsn->ddescription?></textarea>
					</td>
				</tr>
				<tr class="tfile">
					<td class="option" style="padding-top:5px;background-color:#9BB9DD;border-left:1px solid #426BBD;">FASTA </td>
					<td  class="value" style="padding-top:5px;background-color:#9BB9DD;border-right:1px solid #426BBD;">
						<div style="margin-bottom:5px;margin-top:20px;">Upload a file (Max size 10 Mb)</div>
						<input type="file" name="file" title="<br>You must upload the Fasta" />
						<div style="margin-bottom:5px;margin-top:5px">Or provide a link to automatically download and insert the reference</div>
						<input name="rfile" type="text" size="45" />
					</td>
				</tr>
				<tr>
					<td style="padding-top:5px;padding-bottom:15px;background-color:#9BB9DD;border-left:1px solid #426BBD;border-bottom:1px solid #426BBD;">&nbsp;</td>
					<td class="value" style="padding-top:10px;padding-bottom:15px;background-color:#9BB9DD;border-right:1px solid #426BBD;border-bottom:1px solid #426BBD;">
						<input type="submit" value="Submit" name="upload" id="upload" class="button" />&nbsp;<input type="reset" value="Clear" class="button" />
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
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
							
							$jQ('#das_load-gffForm').css('visibility','hidden');
					               }
				});
			}
		});	
	});
</script>
<? } ?>