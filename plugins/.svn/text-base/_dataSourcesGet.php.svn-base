<?php
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.plugins.php";
	
	$uid=$_SESSION['idusers'];
	$p = new Plugins;
	
	$page = (@$_GET["page"])?@$_GET["page"]:1;
	$limit = (@$_GET["rows"])?@$_GET["rows"]:50;
	$sidx = (@$_GET["sidx"])?@$_GET["sidx"]:' created ';
	$sord = (@$_GET["sord"])?@$_GET["sord"]:' DESC ';
	$wh = (@$_GET["whr"])?str_replace('\\','',@$_GET["whr"]):' 1=1 ';
	
	// the where clause from searchdb
	if ($wh ) $wh = " AND ".$wh;
	if(!$sidx) $sidx =1;
	
	$ctmp = $p->getDataSourcesCount();
	$count = $ctmp->total;
	
	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 0;
		$page = 0;
	}
	// set this if we ask records, that are already deleted
	if( $page>$total_pages) $page = $total_pages;
	
	
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	
	
	$sources= $p->getDataSources($page,$start,$limit,$sidx,$sord,$wh,$uid);
	
	if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
		header("Content-type: application/xhtml+xml"); } 
	else {
		header("Content-type: text/xml");
	}
	echo("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");
?>
	<rows>
	<?php
	if ($sources) {
	
		echo "<page>".$page."</page>";
		echo "<total>".$total_pages."</total>";
		
		foreach ( $sources as $s ) {
			echo '<row id = "'.$s->idsources.'">';
			echo '<cell><![CDATA['.$s->name.']]></cell>';
			if ($s->public_data >0)
				echo '<cell><![CDATA['.$s->user.' ('.$s->company.')]]></cell>';
			else
				echo '<cell><![CDATA[Not available]]></cell>';
			echo '<cell><![CDATA['.$s->das.']]></cell>';
			echo '<cell><![CDATA['.$s->created.']]></cell>';
			echo '</row>';
		}	
	}		
	?>
	</rows>


