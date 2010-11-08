<?php 
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	$plugin_path = 'plugins_dir/visualization/evolution/';
?>	
<br>
<table width="100%">
	<tr>
		<td>
			Show: <a class="grayLink" href="#" onclick="$jQ('#evolution').load('<?=$plugin_path?>life-tree.php?circles=genes')">Tree View (Genes)</a> | <a class="grayLink" href="#" onclick="$jQ('#evolution').load('<?=$plugin_path?>life-tree.php?circles=trees')">Tree View (Treefam families)</a> | <a href="#" onclick="$jQ('#evolution').load('<?=$plugin_path?>node-detail.php')">Properties comparison</a>
		</td>
	</tr>
</table>
<div id="evolution">
	
</div>
<script type="text/javascript">
	$jQ(document).ready(function(){ 
		$jQ('#evolution').load('<?=$plugin_path?>life-tree.php?circles=genes');

	});
</script>