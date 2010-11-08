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

	
	$userId=@$_COOKIE['idusers'];
	$pid=@$_SESSION['current_project'];
	$sid=@$_REQUEST['sid'];
	
	
	$favorites = (isset($_REQUEST['favorites']))?$_REQUEST['favorites']:0;
	

	$u = new User;
	$c = new Comodity;
	$p = new Project;
	$plu = new Plugins;
	
	if (!$_REQUEST['plugin']) {
		$project = $p->getProjectById($pid);
	}
?>
<table cellspacing="0" cellpadding="0" border="0">

	<tr>
		<td id="options2" align="left" style="padding-bottom:0px;padding-top:10px;width:850px;">
			<!-- <img id="sources" src="images/sources_30.png" border="0" align="middle" /> -->
			<? if (!$_REQUEST['plugin']) { ?>
			    <a onClick='javascript:$jQ(this).myProjectView(<?=$pid?>)'>Manage Project</a> | 
			    <a href="#" onclick="javascript:$jQ(this).myProjectManageSource(0)">Data source plugins</a>
			<? } else { ?>
			    <a onClick='javascript:$jQ(this).loadPlugins()'>Plugins</a>
			<? } ?>
			<? if ($sid) { ?>
				| <a href="#description_box" class="face">Plugin Help</a>
			<? } ?>
		</td>
	</tr>

	<tr>
		<td valign="top" align="left">
		<?php 
			// no data source selected
			if (!$sid){
				echo '<p><b>Available Data Source Plug-ins</b>.';
				if ($userId){
					echo ' Your favorite plug-ins are highlighted. [ ';
					if (!$_REQUEST['plugin']) {
						echo '<a onClick=\'javascript:$jQ(this).myProjectManageSource(1)\' id="p_sources">';
					}else{
						echo '<a onClick=\'javascript:$jQ(this).dataSourcesList(1)\' id="p_sources">';
					}
					echo 'View Only Favorites</a> | ';
					if (!$_REQUEST['plugin']) {
						echo '<a onClick=\'javascript:$jQ(this).myProjectManageSource(0)\' id="p_sources" class="pa">';
					}else{
						echo '<a onClick=\'javascript:$jQ(this).dataSourcesList(0)\' id="p_sources" class="pa">';
					}
					echo 'View All</a>]</p><br>';
				}
				$sources = $plu->getDataSourcesByDAS(1);
				$x=0;
				$y=0;
				$c=0;
				$columns = 4;
				if ($sources){
					foreach ($sources as $sou){
						if ($c%$columns ==0 && $c!=0){
							$y +=180;
							$x = 0;
						}	
						if (!$u->isFavorite($userId,$sou->idsources,'sourceplugin') && $favorites){
						
						}else{
                                                  echo '<div id="'.$sou->idsources.'_box" style="display:none;"><b>DAS Server:</b><br /><br />'.$sou->dasname.' (Protocol: '.$sou->dasprotocol.')<br /><br /><b>Description:</b><br /><br />'.$sou->dsdescription.'<br /><br />';
                                                  if (!$_REQUEST['plugin']) { 
                                                  	echo '<a onclick="$jQ(\'#projectsCanvas\').load(\'projects/myProjectManageSource.php?sid='.$sou->idsources.'\');$jQ(\'#'.$sou->idsources.'_box\').disposejBox();">Use Plugin</a>&nbsp;|&nbsp;';
                                                  }	
                                                  echo '<a onclick="$jQ(\'#dataSources\').load(\'users/favoriteAdd.php?fid='.$sou->idsources.'&ftype=sourceplugin\');$jQ(\'#'.$sou->idsources.'_box\').disposejBox();">Mark as favorite</a></div>';
                                                  
                                                  //favorite
                                                  if ($u->isFavorite($userId,$sou->idsources,'sourceplugin')){
                                                  
                                                          echo '<div id="'.$sou->idsources.'" style="left:'.$x.'px;top:'.$y.'px" class="sources-favorite" onMouseOver="$jQ(\'#'.$sou->idsources.'\').css(\'background-color\',\'#696969\');" onMouseOut="$jQ(\'#'.$sou->idsources.'\').css(\'background-color\',\'#FE6500\');">';
                                                  //no favorite
                                                  }else{
                                                          echo '<div id="'.$sou->idsources.'" style="left:'.$x.'px;top:'.$y.'px" class="sources">';
                                                  
                                                  }	
                                                  echo '<br><div class="source-name" align="center">'.$sou->dsname.'</div>
                                                            <div class="source-by" align="center">By '.$sou->name.'</div>
                                                            <div class="source-clicks"><a href="#'.$sou->idsources.'_box" class="face">+ View Description</a></div>';
                                                  if ($userId){          
	                                                  if (!$u->isFavorite($userId,$sou->idsources,'sourceplugin'))
	                                                          echo '<div class="source-clicks"><a onclick="$jQ.get(\'users/favoriteAdd.php?fid='.$sou->idsources.'&ftype=sourceplugin\',function(data,txt){$jQ(\'.pa\').trigger(\'click\')})">+ Mark as Favorite</a></div>';
	                                                  else
	                                                  		echo '<div class="source-clicks"><a onclick="$jQ.get(\'users/favoriteAdd.php?nofa=1&fid='.$sou->idsources.'&ftype=sourceplugin\',function(data,txt){$jQ(\'.pa\').trigger(\'click\')})">+ Unmark as Favorite</a></div>';        
  												  }
  												  if (!$_REQUEST['plugin']) { 	
                                                  	echo '<div class="source-clicks"><a onclick="$jQ(\'#projectsCanvas\').load(\'projects/myProjectManageSource.php?sid='.$sou->idsources.'\');">+ Use Plugin</a></div>';
                                                  }	
                                                  echo '</div>';
                                                  
  
                                                  $x += 181;	  
                                                  $y -= 174;	  
                                                  $c++;
						}
					}
				}	
		?>
		<?php	
			// data source selected
			}else {
				$_SESSION['current_data_source'] = $sid;
				$dataSource = $plu->getDataSourceById($sid);
								
				$plugin = $_SESSION['data_sources_dir'].'/'.$dataSource->dsdirectory.'/index.php';
			
				//plugin not found
				if (!file_exists('../'.$plugin)){
					$c->mesg($_SESSION['plugin_not_found'],false);
					exit;
				}
				echo '<div id="description_box" style="display:none;"><b>DAS Server:</b><br /><br />'.$dataSource->dasname.' (Protocol: '.$dataSource->dasprotocol.')<br /><br /><b>Description:</b><br /><br />'.$dataSource->dsdescription.'</div>';
		?>	
          <div id="dataSource" class="pluginContainer">Loading plugin...</div>
		  <div align="center">			
				<?=$dataSource->dsname?>.<br /> <span style="color:#626B8A">
				<?php		
				if ($dataSource->public_data >0){
					echo 'Created by </span><span style="text-transform:capitalize">';
					if ($project->public_data == 2)
						echo '<a href="mailto:'.$dataSource->email.'" title="mail to">'.$dataSource->name.'</a>';
					else 	
						echo '<span style="color:#000000;">'.$dataSource->name.'</span>';
					echo '</span>';
				}
				echo '. '.$dataSource->dscreated.'.';
				?>
			<div>
		<?php } ?>	
		</td>
	</tr>
</table>	
<script language="javascript">
	$jQ(document).ready(function() { 
		$jQ('.face').facebox();
		<?php if ($sid){ ?>
		$jQ("#dataSource").load('<?=$plugin?>');
		<?php } ?>
	});
</script>