<?php
ini_set('include_path', realpath(dirname(__FILE__) . '/../lib/'));

require_once 'MC/Google/Visualization.php';

/*
$db = new PDO('sqlite:example.db');
*/

$dsn = 'mysql:dbname=startver_madas;host=127.0.0.1';
$user = 'startver_madas';
$password = 'madas';

try {
    $dbh = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}	

$vis = new MC_Google_Visualization($dbh);
?>
