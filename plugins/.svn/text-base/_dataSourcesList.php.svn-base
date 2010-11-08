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
			<img src="images/sources.png" border="0"/><br />
			<div class="header2">Data Sources</div>
			<a onClick='javascript:$jQ("...").loadPlugins()'>&lt;&lt; Back</a>
		</td>
		<td valign="top" align="left" style="padding-left:20px;">	
		<div id="dataSources" style="width:650px;height:500px;text-align:left">
				<p style="text-align:justify"><b>Available Data Source Plug-ins</b>. Click in the box to read the description. <br><br>(Your favorite plug-ins are in orange)</p><br><br>
				<?php
				$sources = $plu->getAllDataSources();
				$x=0;
				$y=0;
				$c=0;
				$columns = 6;
				if ($sources){
					foreach ($sources as $sou){
						if ($c%$columns ==0 && $c!=0){
							$y +=100;
							$x = 0;
						}	
						echo '<div id="'.$sou->idsources.'_box" style="display:none;"><b>DAS Server:</b><br /><br />'.$sou->dasname.' (Protocol: '.$sou->dasprotocol.')<br /><br /><b>Description:</b><br /><br />'.$sou->dsdescription.'<br /><br /><a onclick="$jQ(\'#dataSources\').load(\'users/favoriteAdd.php?fid='.$sou->idsources.'&ftype=pluginsource\');$jQ(\'#'.$sou->idsources.'_box\').disposejBox();">Mark as favorite</a></div>';
						
						//favorite
						if ($u->isFavorite($userId,$sou->idsources,'pluginsource')){
						
							echo '<div id="'.$sou->idsources.'" style="position:relative;background-color:#EF7800;width:80px;height:80px;padding:5px;left:'.$x.'px;top:'.$y.'px" class="sources" onclick="$jQ(\'#'.$sou->idsources.'_box\').openjBox(\'inline,draggable=true,center=true,width=300,height=300\',\''.$sou->dsname.'\');" onMouseOver="$jQ(\'#'.$sou->idsources.'\').css(\'background-color\',\'#696969\');" onMouseOut="$jQ(\'#'.$sou->idsources.'\').css(\'background-color\',\'#EF7800\');">
								<div align="center" style="font-weight:bold;height:40px;color:#FFFFFF">'.$sou->dsname.'</div>
								<div align="center" style="margin-top:5px;color:#FFFFFF;text-transform:capitalize">By '.$sou->name.'</div>
							  </div>';
						
						//no favorite
						}else {
						
							echo '<div id="'.$sou->idsources.'" style="position:relative;background-color:#E4E4E4;width:80px;height:80px;padding:5px;left:'.$x.'px;top:'.$y.'px" class="sources" onclick="$jQ(\'#'.$sou->idsources.'_box\').openjBox(\'inline,draggable=true,center=true,width=300,height=300\',\''.$sou->dsname.'\');" onMouseOver="$jQ(\'#'.$sou->idsources.'\').css(\'background-color\',\'#E7F5E8\');" onMouseOut="$jQ(\'#'.$sou->idsources.'\').css(\'background-color\',\'#E4E4E4\');">
								<div align="center" style="font-weight:bold;height:40px;">'.$sou->dsname.'</div>
								<div align="center" style="margin-top:5px;color:#626B8A;text-transform:capitalize">By '.$sou->name.'</div>
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
			$jQ(".sources").corner("dog br 15px").corner("round tr tl bl  5px");
	});
</script>
