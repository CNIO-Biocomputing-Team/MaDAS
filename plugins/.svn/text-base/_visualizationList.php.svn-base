<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.projects.php";
	include_once "class.plugins.php";
	include_once "class.user.php";
	
	$u = new User;
	$c = new Comodity;
	$p = new Project;
	$plu = new Plugins;
	$userId=@$_SESSION['idusers'];
?>
<table height="100%" cellspacing="10">
	<tr>
		<td class="rightBorder">
			<img src="images/visualization.png" border="0"/><br />
			<div class="header2">Visualization</div>
			<a onClick='javascript:$jQ("...").loadPlugins()'>&lt;&lt; Back</a>
		</td>
		<td valign="top" align="left" style="padding-left:20px;">	
		<div id="datavisualization" style="width:650px;height:500px;text-align:left">
				<p style="text-align:justify"><b>Available visualization Plug-ins</b>. Click in the box to read the description. <br><br>(Your favorite plug-ins are in orange)</p><br><br>
				<?php
				$visualization = $plu->getAllVisualization();
				$x=0;
				$y=0;
				$c=0;
				$columns = 6;
				if ($visualization){
					foreach ($visualization as $vis){
						if ($c%$columns ==0 && $c!=0){
							$y +=100;
							$x = 0;
						}	
						echo '<div id="'.$vis->idvisualization.'_box" style="display:none;"><b>DAS Server:</b><br /><br />'.$vis->dasname.' (Protocol: '.$vis->dasprotocol.')<br /><br /><b>Description:</b><br /><br />'.$vis->videscription.'<br /><br /><a onclick="$jQ(\'#datavisualization\').load(\'users/favoriteAdd.php?fid='.$vis->idvisualization.'&ftype=pluginvisualization\');$jQ(\'#'.$vis->idvisualization.'_box\').disposejBox();">Mark as favorite</a></div>';
						
						//favorite
						if ($u->isFavorite($userId,$vis->idvisualization,'pluginvisualization')){
						
							echo '<div id="'.$vis->idvisualization.'" style="position:relative;background-color:#EF7800;width:80px;height:80px;padding:5px;left:'.$x.'px;top:'.$y.'px" class="visualization" onclick="$jQ(\'#'.$vis->idvisualization.'_box\').openjBox(\'inline,draggable=true,center=true,width=300,height=300\',\''.$vis->viname.'\');" onMouseOver="$jQ(\'#'.$vis->idvisualization.'\').css(\'background-color\',\'#696969\');" onMouseOut="$jQ(\'#'.$vis->idvisualization.'\').css(\'background-color\',\'#EF7800\');">
								<div align="center" style="font-weight:bold;height:40px;color:#FFFFFF">'.$vis->viname.'</div>
								<div align="center" style="margin-top:5px;color:#FFFFFF;text-transform:capitalize">By '.$vis->name.'</div>
							  </div>';
						
						//no favorite
						}else {
						
							echo '<div id="'.$vis->idvisualization.'" style="position:relative;background-color:#E4E4E4;width:80px;height:80px;padding:5px;left:'.$x.'px;top:'.$y.'px" class="visualization" onclick="$jQ(\'#'.$vis->idvisualization.'_box\').openjBox(\'inline,draggable=true,center=true,width=300,height=300\',\''.$vis->viname.'\');" onMouseOver="$jQ(\'#'.$vis->idvisualization.'\').css(\'background-color\',\'#E7F5E8\');" onMouseOut="$jQ(\'#'.$vis->idvisualization.'\').css(\'background-color\',\'#E4E4E4\');">
								<div align="center" style="font-weight:bold;height:40px;">'.$vis->viname.'</div>
								<div align="center" style="margin-top:5px;color:#626B8A;text-transform:capitalize">By '.$vis->name.'</div>
							  </div>';
						}	  
							  
							  
							  
							  
						$x += 110;	  
						$y -= 90;	  
						$c++;
					}
				}	
				?>
			</div>	
		</td>
	</tr>
</table>
<script language="javascript">
	$jQ(document).ready(function() { 
			$jQ(".visualization").corner("dog br 15px").corner("round tr tl bl  5px");
	});
</script>
