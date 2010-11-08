<?php
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.projects.php";
	
	$uid=$_SESSION['idusers'];
	$p = new Project;
	

	$page = (@$_REQUEST["page"])?@$_REQUEST["page"]:1;
        $rp = (@$_REQUEST["rp"])?@$_REQUEST["rp"]:10;
        $sortname = (@$_REQUEST['sortname'])?@$_REQUEST['sortname']:' created ';
        $sortorder = (@$_REQUEST['sortorder'])?@$_REQUEST['sortorder']:' DESC ';

        // the where clause from searchdb
        $pquery = str_replace("*","'",@$_REQUEST['pquery']);
        $query = @$_REQUEST['query'];
        $qtype = @$_REQUEST['qtype'];
        $wh = "";
        if ($query) $wh = " AND $qtype LIKE '%$query%' ";
        if ($pquery) $wh .= " AND ". $pquery;
	
	
	$ctmp = $p->getProjectsCount();
	$count = $ctmp->total;
	
	if( $count >0 ) {
		$total_pages = ceil($count/$rp);
	} else {
		$total_pages = 0;
		$page = 0;
	}
	// set this if we ask records, that are already deleted
	if( $page>$total_pages) $page = $total_pages;
	
	
	$start = $rp*$page - $rp; // do not put $limit*($page - 1)
	
	
	$projects = $p->getProjects($page,$start,$rp,$sortname,$sortorder,$wh,$uid);
	

	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
        header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
        header("Cache-Control: no-cache, must-revalidate" ); 
        header("Pragma: no-cache" );
        header("Content-type: text/xml");
?>
	<rows>
	<?php
	if ($projects) {
	
		echo "<page>".$page."</page>";
		echo "<total>".count($projects)."</total>";

		foreach ( $projects as $p ) {
			echo '<row id = "'.$p->idprojects.'">';
			echo '<cell><![CDATA['.$p->idprojects.']]></cell>';
			echo '<cell><![CDATA['.$p->name.']]></cell>';
			echo '<cell><![CDATA['.$p->category.']]></cell>';
			echo '<cell><![CDATA['.(1+$p->members).']]></cell>';
			if ($p->public_data >0)
				echo '<cell><![CDATA['.$p->user.' ('.$p->company.')]]></cell>';
			else
				echo '<cell><![CDATA[Not available]]></cell>';
			echo '<cell><![CDATA['.$p->created.']]></cell>';
			echo '</row>';
		}	
	}		
	?>
	</rows>


