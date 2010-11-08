<?
	//requiered initializations
	session_start();
	ini_set('include_path',$_SESSION['include_path']);
	
	//includes  clases
	include_once "ez_sql.php";
	include_once "class.comodity.php";
	include_once "class.user.php";
	include_once "class.projects.php";
	include_once "class.set-reference.php";
	
	
	$c = new Comodity;
	
	

	
	$page 		= (@$_REQUEST['page'])?@$_REQUEST['page']:1;
	$rp 		= (@$_REQUEST['rp'])?@$_REQUEST['rp']:10;
	$sortname 	= (@$_REQUEST['sortname'])?@$_REQUEST['sortname']:'created';
	$sortorder 	= (@$_REQUEST['sortorder'])?@$_REQUEST['sortorder']:'DESC';
	$query 		= @$_REQUEST['query'];
	$qtype 		= @$_REQUEST['qtype'];
	$dsn_id     = @$_REQUEST['dsnid'];
	
	$key = 'iddas_commonserver_segments';
	
	$strSQL = "SELECT iddas_commonserver_segments, sname,sstart,sstop, created	 FROM das_commonserver_segments WHERE iddas_commonserver_dsns = ".$dsn_id." ";
		
		
		
	if ($query)
		$strSQL .= " AND $qtype LIKE '%$query%'  ";
		
	$strSQL .= " ORDER BY $sortname $sortorder ";
	
	echo $c->buildXML($page,$rp,$key,$strSQL);	
	
?>

