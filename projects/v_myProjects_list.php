<?php
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.projects.php";
	
	$uid=$_COOKIE['idusers'];
	$p = new Project;
	if (!$uid)	
		exit;
	$my_projects = $p->getMyProjects($uid);
?>
<div>
	<table id="myProjects" cellpadding="0" cellspacing="0" align="center" class="myProjectsList rounded">
	<tr><td><div class="headerc2rounded">My projects</div></td></tr>
	<tr><td style="height:7px;background-color:#FFFFFF">&nbsp;</td></tr>
	<tr><td align="center" style="background-color:#FFFFFF">Click on the project<br />to work with it</td></tr>
	<tr><td style="height:15px;background-color:#FFFFFF">&nbsp;</td></tr>
	<tr><td><div style="background-color:#FFFFFF;">
	<?php
	$tmp_p = array();
	if ($my_projects) {
		foreach ( $my_projects as $p ) {
			if (!in_array($p->idprojects,$tmp_p)){
                          echo '<table class="project" id="'.$p->idprojects.'" onClick=\'javascript:$jQ(this).myProjectViewTransfer(this)\' onMouseOver=\'javascript:$jQ("#'.$p->idprojects.'").css("background-color","#FFEAE0")\' onMouseOut=\'javascript:$jQ("#'.$p->idprojects.'").css("background-color","#FFFFFF")\' ><tr><td>';
                          echo '<table cellpadding="2" cellspacing="0">';
                          echo '<tr>';
                          echo '<td class="projectName">'.$p->name;
                          if ($p->owner == $uid)
                                  echo '&nbsp;<img src="images/StarOra.png" title="You are the Project Leader" style="cursor:pointer;" />';
                          echo '</tr>';
                          echo  '<tr><td class="projectDate">Created: '.$p->created.'</td></tr>';
                          echo '</table>';
                          echo '</td></tr></table>';
                          array_push($tmp_p,$p->idprojects);
			}
		}	
	}		
	?>
	</div></td></tr></table>
</div>