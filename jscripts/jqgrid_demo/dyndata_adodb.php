<?php

include("./config.php");
// in this file are stored connection parameters
//SecurityRedirect("", "login.php");

include("adodb-errorhandler.inc.php");
include("adodb.inc.php");

$page = $_GET["page"];
$recs = $_GET["rows"];
$sidx = $_GET["sidx"];
$sord = $_GET["sord"];
$wh = $_GET["whr"];

// the where clause from searchdb
if ($wh ) $wh = "AND ".$wh;
if(!$sidx) $sidx =1;


$ADODB_FETCH_MODE = ADODB_FETCH_NUM;


$db = ADONewConnection($dbdriver);
$db->Connect($dbserver, $dbuser, $dbpassword, $dbname);
if( $dbcharset ) $db->Execute( $dbcharset );

$SQL = "SELECT message_id,message_title,author,date_add, date_update, smile_url FROM forum_messages a LEFT JOIN smiles ON a.smile_id = smiles.smile_id WHERE a.message_id_parent IS NULL ".$wh." ORDER BY ".$sidx." ".$sord;
$rs = $db->PageExecute($SQL, $recs ,$page);
if ( $rs ) { 
	if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
		header("Content-type: application/xhtml+xml"); } else {
		header("Content-type: text/xml");
	}
	$et = ">";
	echo "<?xml version='1.0' encoding='windows-1251'?$et\n";
	if( $page > $rs->LastPageNo())$page=$rs->LastPageNo();
	echo "<rows>";
	echo "<page>".$page."</page>";
	echo "<total>".$rs->LastPageNo()."</total>";
	$rs->MoveFirst();    
	$fldcnt = $rs->FieldCount();
	while (!$rs->EOF) {
		echo "<row id='",$rs->fields[0],"'>";
		for ($i=0; $i<$fldcnt; $i++) {
			$fld = $rs->FetchField($i);
			$type = $rs->MetaType($fld->type);
			if ( $type == 'C' || $type == 'X') {
				echo "<cell><![CDATA[",$rs->fields[$i],"]]></cell>";      
			}
			elseif ($type == 'N' ) { 
				echo "<cell>",number_format($rs->fields[$i], 2, '.', ' '),'</cell>';      
			} else {
				echo "<cell>",$rs->fields[$i],'</cell>';      
			}
		}
//      adodb_movenext($rs);
		echo '</row>';
		$rs->MoveNext();
	} // while
	echo '</rows>';
} // if

?>
