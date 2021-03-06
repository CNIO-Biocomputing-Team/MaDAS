<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.projects.php";
	include_once "class.plugins.php";
	include_once "class.phpmailer.php";
	include_once "html2text.php";
	
	$userId=@$_COOKIE['idusers'];
	$pid=@$_REQUEST['pid'];
	
	//se current project cookie and session
	setcookie("current_project",$pid,time()+30*24*60*60,"/");
	$_SESSION['current_project'] = $pid;
	
	$c 		= new Comodity;
	$p 		= new Project;
	$plu 	= new Plugins;
	$mail 	= new phpmailer();

	$project=$p->getProjectById($pid);
	$role = $p->getProjectRole($userId,$pid);
	$status = $p->getProjectStatus($userId,$pid);
	
	//project owner , no configured
	if ($project->idusers == $userId && $project->configured == 0){
?>
<p style="text-align:justify">A <b>MaDAS project</b> is built from three component types: <b>Data Source plugins</b>, <b>DAS servers</b>, and <b>Visualization plugins</b> to display and manipulate the annotations.</p>
<p style="text-align:justify">To start with your project just <b>select which DAS server</b> do you want to use.</p>
<p style="text-align:justify">Based on your selection you will be able to use different plugins to manage and visualize your data.</p>
<p style="text-align:justify">Other people that don't use MaDAS will be able to retrieve your data using standards DAS clients.</p>
<br /><br />
<form id="projectRun" action="projects/myProjectWork_R.php" method="post">
  <input type="hidden" name="pid" value="<?=$pid?>" />
  <table align="center" width="90%" border="0">
    <tr>
      <td align="center"><img id="sources" src="images/sources.png" border="0" /><div class="header2">Data Source</div></td>
      <td valign="middle" ><img id="1arrow" src="images/arrow.jpg" style="visibility:hidden;" /></td>
      <td align="center" id="das"><img src="images/das.png" border="0" /><div class="header2">DAS Server</div></td>
      <td valign="middle"><img id="2arrow" src="images/arrow.jpg" style="visibility:hidden;" /></td>
      <td align="center"><img src="images/visualization.png" border="0" /><div class="header2">Visualization</div></td>
    <tr>
    <tr>
      <td />
      <td />
      <td>
              <?php  $servers = $plu->getAllDasServers(); ?>
              <select name="das_id" id="dasSelect" onchange="$jQ('#projectRun').ajaxSubmit({dataType: 'script'});" class="{required:true}">
                      <option value="0">Select one...</option>
                      <?php 
                              
                          foreach ($servers as $s)
                                      echo '<option value="'.$s->iddas_servers.'">'.$s->dasname.'</option>';
                      ?>
              </select>
      </td>
      <td />
      <td />
    <tr>
  </table>
</form>
<script language="javascript">
	$jQ(document).ready(function() { 
	
		$jQ("#projectRun").submit(function() {
			  return false; // cancel conventional submit
		});
		$jQ('#projectRun').validate({
			submitHandler: function(form) { 
			        var options = {
                                  dataType: 'script'
			        };
				$jQ(form).ajaxSubmit(options);
			}
		});	
	});
</script>

<?php 
	//project owner , configured
	//} elseif ($project->idusers == $userId && $project->configured == 1){
	} elseif ($project->configured == 1){
		echo '<br><br>';
		$c->mesg(base64_decode(@$_REQUEST['msg']),true);
		if (!$role and !$userId){
			
			$c->mesg('You have recieved an invitation to join this project. Please <b>Login</b> with your username and password or <b>create a new account</b> in MaDAS.',true);
			
		}else if (!$role){
			
			$p->joinToProject($userId,$pid,0);

			$c->mesg('Your request to join the <b>'.$project->name.'</b> project have been sent to the project leader. You <b>will receive an email</b> when '.$project->owner.' activate your project membership.<br><br>Welcome to the <b>MaDAS</b> community!.',true);
		
		}else if ($status == 1){

			$c->mesg('Your request to join the <b>'.$project->name.'</b> project have been sent to the project leader. You <b>will receive an email</b> when '.$project->owner.' activate your project membership.<br><br>Welcome to the <b>MaDAS</b> community!.',true);

		}else{

?>
		
	<table align="left">
		<? if ($role == 'PROJECT LEADER'){ ?>
		<tr>
			<td style="padding-bottom:35px;"><a onClick='javascript:$jQ(this).myProjectManageSource(0)' id="p_sources"><img src="images/sources.png" align="top" hspace="10" border="0" /><br />Setup your data</a></td>
			<td class="textJustfied">Through these Plug-ins you will be able to <b>add</b>, <b>edit</b> or <b>delete</b> your own data. Pick the plug-in that best fits your needs from the list.</td>
		</tr>
		<? } ?>
		<tr>
			<td style="padding-bottom:35px;"><img src="images/das.png" align="top" hspace="10" border="0" /><br /><a href="<?=$_SESSION['MaDAS_url']?>plugins_dir/dasServers/das_common-server/das.php/dsn" target="_blank">Query DAS server</a></td>
			<td class="textJustfied"><b>Query the DAS server</b>.<br> Example: <a href="<?=$_SESSION['MaDAS_url']?>plugins_dir/dasServers/das_common-server/das.php/dsn" target="_blank"><?=$_SESSION['MaDAS_url']?>plugins_dir/dasServers/das_common-server/das.php/dsn</a></td>
		</tr>
		<tr>
			<td style="padding-bottom:35px;"><a onClick='javascript:$jQ(this).myProjectBrowseAnnotations(0)' id="p_browse"><img src="images/visualization.png" align="top" hspace="10" border="0" /><br />Analyze data</a></td>
			<td class="textJustfied">Through <b>MaDAS Analysis</b> Plug-ins you will be able to display and perfom several analysis over your data.</td>
		</tr>
	</table>
<?php
		}	
	}
?>





