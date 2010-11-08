<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "class.comodity.php";
	$c 		= new Comodity;
	
	//session and cookies
	$userId = @$_COOKIE['idusers'];
	$pid =	@$_COOKIE['current_project'];
	
	//plugin_path
	$plugin_path = 'plugins_dir/dataSources/data_bionemo/'; 
	
	if (@$_COOKIE['utype'] != 'ROOT'){
		echo '<br><br>';
		$c->mesg("Sorry but this plug-in can be only used by the MaDAS administrator.",false);
		exit;	
	}
		
?>
<div class="header1"><b>Bionemo</b></div>
<div class="pluginBox" style="height:260px;">
	<div id="step0" />
	<div id="step1">
		<div>
			<p>Set the connection to the <b>Bionemo</b> database.</p>
		</div>
		<form id="bionemoForm" name="bionemoForm" action="<?=$plugin_path?>d_index.php" method="post" enctype="multipart/form-data">
			<table align="left" cellspacing="10">
				<tbody>
	              <tr>
	                <td class="option">1) Host *</td>
	                <td  class="value"><input type="text" name="host" id="host" class="required" style="width:300px;" value="padme.cnio.es"></td>
	              </tr>
	              <tr>
	                <td class="option">2) User *</td>
	                <td  class="value"><input type="text" name="user" id="user" class="required" value="madas"></td>
	              </tr>
	              <tr>
	                <td class="option">3) Password *</td>
	                <td  class="value"><input type="password" name="pass" id="pass" class="required" value="madas.madas"></td>
	              </tr>
	              <tr>
	                <td class="option">3) Database *</td>
	                <td  class="value"><input type="text" name="database" id="database" class="required" style="width:300px;" value="bionemo_v6_0"></td>
	              </tr>
	              <tr>
	                <td class="option">4) Version *</td>
	                <td  class="value">
	                	<? 
						$name='version';
						$array = array ('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10');
						$value = 6;
						$class = 'required';
						include('select-general.php');
						?>
	                </td>
	              </tr>
	              <tr>
	                <td />
	                <td class="value">
	                   <input type="submit" value="Import Data" class="button" />&nbsp;<input type="reset" value="Clear" class="button" />
	                </td>
	              </tr>
	             </tbody>
	         </table>       
		</form>
	</div>
</div>
<script language="javascript">
	$jQ(document).ready(function() { 
		$jQ("#bionemoForm").submit(function() {
			  return false; // cancel conventional submit
		});
		$jQ('#bionemoForm').validate({
			submitHandler: function(form) { 
				$jQ(form).ajaxSubmit({
					target: '#step1'
				     
				});
			}
		});	
	});
</script>