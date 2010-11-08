<?php
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.manage-annot.php";
	
	
	$r = new Manage_Annotations;
	
	$uid = @$_SESSION['idusers'];
	$pid = @$_SESSION['current_project'];
	
	

	$page = (@$_REQUEST["page"])?@$_REQUEST["page"]:1;
        $rp = (@$_REQUEST["rp"])?@$_REQUEST["rp"]:10;
        $sortname = (@$_REQUEST['sortname'])?@$_REQUEST['sortname']:' created ';
        $sortorder = (@$_REQUEST['sortorder'])?@$_REQUEST['sortorder']:' desc ';

        // the where clause from searchdb
        $pquery = str_replace("*","'",@$_REQUEST['pquery']);
        $query = @$_REQUEST['query'];
        $qtype = @$_REQUEST['qtype'];
        $wh = "";
        if ($query) $wh = " AND $qtype LIKE '%$query%' ";
        if ($pquery) $wh .= " AND ". $pquery;
	
	
	$count = $r->getAnnotCount($pid);
	
	if( $count >0 ) {
		$total_pages = ceil($count/$rp);
	} else {
		$total_pages = 0;
		$page = 0;
	}
	// set this if we ask records, that are already deleted
	if( $page>$total_pages) $page = $total_pages;
	
	
	$start = $rp*$page - $rp; // do not put $limit*($page - 1)
	
	if ($start <= 0)
	 $start = 0;
	
	$annot = $r->getAllAnnotByProject($page,$start,$rp,$sortname,$sortorder,$wh,$uid,$pid);
	
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
        header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
        header("Pragma: no-cache" );
        header("Content-type: text/xml");
?>
	<rows>
	<?php
	if ($annot) {
	
		echo "<page>".$page."</page>";
		echo "<total>".count($annot)."</total>";

		foreach ( $annot as $a ) {
			echo '<row id = "'.$a->iddas_commonserver_annotations.'">';
			echo '<cell><![CDATA['.$a->iddas_commonserver_annotations.']]></cell>';
			echo '<cell><![CDATA['.$a->description.']]></cell>';
			echo '<cell><![CDATA['.$a->dname.']]></cell>';
			echo '<cell><![CDATA['.$a->features.']]></cell>';
			echo '<cell><![CDATA['.$a->status.']]></cell>';
			echo '<cell><![CDATA['.$a->created.']]></cell>';
			echo '</row>';
		}	
	}		
	?>
	</rows>


