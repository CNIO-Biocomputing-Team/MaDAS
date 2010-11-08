<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.madasmap.php";

	//session
	$userId = @$_SESSION['idusers'];
	$pid =	@$_SESSION['current_project'];
	$_SESSION['types'] = '';
	setcookie('view_types','',time() - 3600,'/');
	
	//preparing for next step
	$plugin_path = 'plugins_dir/visualization/array_express/';
	$_SESSION['plugin_path'] = $plugin_path;
	
	$c = new Comodity;
	$p = new Project;
	$map = new Madasmap;
	
	$dsns = $map->getDsns($pid);

?>
<div class="header1"><b>Gene expression</b></div>
<div id="launch-box" class="pluginBox" style="height:220px;">
	<form name="f-launch" method="post" id="f-launch" action="<?=$plugin_path?>display.php">
		<input type="hidden" name="entry_point" id="entry_point" value="" class="required" />
		<input type="hidden" name="size" id="size" value="" class="required" />
	    <table cellspacing="20">
	    	<tr>
				<td class="option">1) Select a reference sequence *</td>
				<td  class="value">
					<select name="dsn" id="dsn" class="required">
					    <option value="">Select one...</option>
					    <?php 
					    	foreach($dsns as $d)
					    		echo '<option value='.$d->iddas_commonserver_dsns.'>'.$d->dname.' [version '.$d->dversion.']</option>';
					    ?>
					</select>
				<td>
			</tr>
			<tr>
				<td class="option">2) Select a chromosome or segment *</td>
				<td  class="value">
					<input type="button" value="Get chromosome" class="button" onclick="jQuery.facebox('<div id=\'ep\'></div>');$jQ('#ep').load('plugins_dir/visualization/common/getEntryPoints.php?dsn='+$jQ('#dsn').val())" />
					&nbsp;&nbsp;<span id="das_segment"></span>
				<td>
			</tr>
	        <tr>
                <td class="option">3) Q-value *</td>
                <td class="value">
                    <? 
						$name='qvalue';
						$array = array ('0.1' => '0.1', '0.05' => '0.05', '0.01' => '0.01');
						$value = $r->status;
						$class = 'required';
						include('select-general.php');
					?>
                </td>
            </tr>
	        <tr>
	            <td class="option" colspan="2" style="padding-top:10px;">
			       	<input type="button" name="launch" id="launch" class="button" value="Launch Graphic View" onclick="launchGraphic();">&nbsp;&nbsp;
	            </td>
	        </tr>
	    </table>
	</form>
</div>
        
       
<script type="text/javascript">

  function launchGraphic(){
	
		$jQ('#visualizationB').load('<?=$plugin_path?>map.php?qvalue='+$jQ('#qvalue').val()+'&das_servers='+$jQ('#das_servers').val()+'&entry_point='+$jQ('#entry_point').val()+'&size='+$jQ('#size').val());
		
  		$jQ("#options2").append($jQ('<span id="tmadasmap"><span>|</span> <a href="#" onclick=$jQ("#visualizationB").load("<?=$plugin_path?>index.php");$jQ("#tmadasmap").remove();>Go back</a></span>'));
  }

		
  $jQ(document).ready(function(){
		$jQ('.face').facebox();
  });
</script>