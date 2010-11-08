<?php
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.set-reference.php";
	
	
	$r = new Set_Reference;
	
	$uid = @$_COOKIE['idusers'];
	$pid = @$_COOKIE['current_project'];
	
	

	$page = (@$_REQUEST["page"])?@$_REQUEST["page"]:1;
    $rp = (@$_REQUEST["rp"])?@$_REQUEST["rp"]:10;
    $sortname = (@$_REQUEST['sortname'])?@$_REQUEST['sortname']:' dname ';
    $sortorder = (@$_REQUEST['sortorder'])?@$_REQUEST['sortorder']:' asc ';

    // the where clause from searchdb
    $pquery = str_replace("*","'",@$_REQUEST['pquery']);
    $query = @$_REQUEST['query'];
    $qtype = @$_REQUEST['qtype'];
    $wh = "";
    if ($query) $wh = " AND $qtype LIKE '%$query%' ";
    if ($pquery) $wh .= " AND ". $pquery;
	
	
	$count = $r->getDsnCount($pid);
	
	
	
	if( $count >0 ) {
		$total_pages = ceil($count/$rp);
	} else {
		$total_pages = 0;
		$page = 0;
	}
	// set this if we ask records, that are already deleted
	if( $page>$total_pages) $page = $total_pages;
	
	
	$start = $rp*$page - $rp; // do not put $limit*($page - 1)
	
	if ($start <0)
		$start = 0;
	
	
	$dsns = $r->getAllDsnsByProject($page,$start,$rp,$sortname,$sortorder,$wh,$uid,$pid);
	
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
    header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
    header("Pragma: no-cache" );
    header("Content-type: text/xml");
?>
	<rows>
	<?php
	if ($dsns) {
	
		echo "<page>".$page."</page>";
		echo "<total>".$count."</total>";

		foreach ( $dsns as $d ) {
			echo '<row id = "'.$d->iddas_commonserver_dsns.'">';
			echo '<cell><![CDATA['.$d->iddas_commonserver_dsns.']]></cell>';
			echo '<cell><![CDATA['.$d->dname.']]></cell>';
			echo '<cell><![CDATA['.$d->dversion.']]></cell>';
			echo '<cell><![CDATA['.$d->mname.']]></cell>';
			echo '<cell><![CDATA['.$d->segments.']]></cell>';
			echo '<cell><![CDATA['.$d->dcreated.']]></cell>';
			echo '<cell><![CDATA['.$d->name.']]></cell>';
			echo '</row>';
		}	
	}		
	?>
	</rows>


