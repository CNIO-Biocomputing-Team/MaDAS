<?php

include("./config.php");
// in this file are stored connection parameters
//SecurityRedirect("", "login.php");


$page = $_GET["page"];
$limit = $_GET["rows"];
$sidx = $_GET["sidx"];
$sord = $_GET["sord"];
$wh = $_GET["whr"];

// the where clause from searchdb
if ($wh ) $wh = "AND ".$wh;
if(!$sidx) $sidx =1;

// connect to the database
$db = mysql_connect($dbserver, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($dbname) or die("Error conecting to db.");

// count the total number of pages
$result = mysql_query("SELECT COUNT(*) AS count FROM forum_messages WHERE message_id_parent IS NULL ".$wh);
$row = mysql_fetch_array($result,MYSQL_ASSOC);
$count = $row['count'];

if( $count >0 ) {
	$total_pages = ceil($count/$limit);
} else {
	$total_pages = 0;
	$page = 0;
}
// set this if we ask records, that are already deleted
if( $page>$total_pages) $page = $total_pages;


$start = $limit*$page - $limit; // do not put $limit*($page - 1)
//put your encoding here
mysql_query("SET NAMES cp1251");

$SQL = "SELECT message_id,message_title,author,date_add, date_update, smile_url FROM forum_messages a ".
	"LEFT JOIN smiles ON a.smile_id = smiles.smile_id ".
	"WHERE a.message_id_parent IS NULL ".$wh." ORDER BY ".$sidx." ".$sord." LIMIT $start, $limit";
$result = mysql_query( $SQL ) or die("Couldn’t execute query.".mysql_error());

if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
	header("Content-type: application/xhtml+xml"); } else {
	header("Content-type: text/xml");
}
$et = ">";
// encoding
echo "<?xml version='1.0' encoding='windows-1251'?$et\n";

echo "<rows>";
echo "<page>".$page."</page>";
echo "<total>".$total_pages."</total>";

// be sure to put text data in CDATA

while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
	echo "<row id='". $row[message_id]."'>";
	echo "<cell>". $row[message_id]."</cell>";
	echo "<cell><![CDATA[". $row[message_title]."]]></cell>";
	echo "<cell><![CDATA[". $row[author]."]]></cell>";
	echo "<cell>".$row[date_add]."</cell>";
	echo "<cell>".$row[date_update]."</cell>";
	echo "<cell><![CDATA[".$row[smile_url]."]]></cell>";
	echo "</row>";
}
echo "</rows>";

?>
