<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
?>
<table cellspacing="0" align="right" border="0">
	<tr>
		<!--user msg-->
		<?php if (!@$_SESSION['cookie'] or @$_SESSION['name'] == 'Nobody'){ ?>
		<td class="menu" />
		<?php } else { ?>
		<td class="menu" >
			Welcome
			<a id="profile" onClick='javascript:$jQ(this).register()'>
			<?php 
				$msg = '';
				if(@$_SESSION['name'])
					$msg.= @$_SESSION['name'];
				else
					$msg.= 'Unamed user';
				$msg.='';	
				echo $msg;	
			?>
			</a>	
			<img id="logout_menu" src="images/logout.png" border="0" align="absmiddle" style="cursor:pointer;" onClick='javascript:$jQ(this).logout()' />
		</td>
		<?php } ?>
		<!-- Home -->
		<td class="menu">
			<a id="home" onClick='javascript:$jQ(this).loadHome()'>Home</a>
		</td>
		<!--login register-->
		<?php if (!@$_SESSION['cookie'] or @$_SESSION['name'] == 'Nobody') { ?>
		<td class="menu">
			<a id="login" onClick='javascript:$jQ(this).login(<?=$_SESSION['gotopid']?>)'>Login</a> / <a id="register" onClick='javascript:$jQ(this).register(<?=$_SESSION['gotopid']?>)'>Register</a>
		</td>
		<?php } ?>
		<!-- Projects -->
		<td class="menu">
			<?php if (@$_SESSION['cookie'] ) { ?>
				<a id="projects" onClick='javascript:$jQ(this).loadProjects()'>Projects</a>
			<?php } else { ?>
				<span style="color:#CCCCCC;">Projects</span>
			<?php } ?>
		</td>
		<!-- Plugins -->
		<td class="menu">
				<a id="plugins" onClick='javascript:$jQ(this).loadPlugins()'>Plug-ins</a>
		</td>
		<!-- Help -->
		<td class="menu">
			<a id="help" onClick='javascript:$jQ(this).loadHelp()'>Help</a>
		</td>
		<td style="width:40px;">&nbsp;</td>
	</tr>
</table>
