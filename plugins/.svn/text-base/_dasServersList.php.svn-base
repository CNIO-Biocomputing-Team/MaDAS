<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.user.php";

	$u = new User;
	$userId=@$_SESSION['idusers'];
?>
<table height="100%" cellspacing="10">
	<tr>
		<td class="rightBorder">
			<img src="images/das.png" border="0"/><br />
			<div class="header2">DAS Servers</div>
			<a onClick='javascript:$jQ("...").loadPlugins()'>&lt;&lt; Back</a>
		</td>
		<td valign="top" align="center">
				<p style="text-align:justify">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Quisque fermentum convallis felis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Duis ultricies quam a neque. Pellentesque quis mauris.</p><br /><br />
				<table id="dasServers" class="scroll" cellpadding="0" cellspacing="0"></table>
				<div id="pager" class="scroll" style="text-align:center;" ></div>
				<br /><br />
				<span id="dasServersSearch"></span>
		</td>
	</tr>
</table>
