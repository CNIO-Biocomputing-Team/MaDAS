<?php
        //requiered initializations
	session_start();
	ob_start();
	ini_set('include_path',$_SESSION['include_path']);
        
        include_once "class.comodity.php";
        $c = new Comodity;
        
	if (!@$_REQUEST['url']){
		echo 'You must provide a DAS server URL';
		exit;
	}	
	
	$url = @$_REQUEST['url'];
	$mtype = @$_REQUEST['mtype'];
	
        $xml = simplexml_load_file($url);
	$colors = array('#3872D3','#AA0B03','#AA0B03','#B1CCF5','#289100','#E3554C','#85D46A');
?>	
	<style>
		body{
			color:#333333;
		}
		.dark{
			width:300px;
			background-color:#E4E4E4;
		}
		.normal{
			width:300px;
			background-color:#FFFFFF;
		}
	</style>
    <body>
 	<div style="color:#E3554C">Processing URL: <?=$url?><br><br></div> 	
    <div style="height:500px;overflow:auto;">
	<table>

<?php    	
	$i=1;
	foreach ($xml->DSN as $dsn){
			
			echo '<tr><td><input type="radio" name="dsn" value="'.$dsn->MAPMASTER.'" onclick="$jQ(\'#dname\').val(\''.$c->clean_str($dsn->SOURCE['id']).'\');$jQ(\'#dmap_master\').val(\''.$c->clean_str($dsn->MAPMASTER).'\');$jQ(\'#description\').val(\''.$c->clean_str($dsn->DESCRIPTION).'\');$jQ(\'#mtype\').val('.$mtype.');$jQ(\'.tfile\').css(\'display\',\'none\');"></td><td ';
			if ($i)
				echo 'class="dark"';
			else 
				echo 'class="normal"';	
			echo ' align="left"><table align="left" width="460">';
			echo '<tr><td align="left" style="font-weight:bold;">'.$dsn->SOURCE['id'].'</td></tr>';
			echo '<tr><td align="left">'.$dsn->DESCRIPTION.'</td></tr>';	
			echo '</table></td></tr>';
			$i=($i==0)?1:0;
	}	
?>
	</table>
    </div>
    </body>