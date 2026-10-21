<?php

/**
 * @author: Oliver Putzer
 * @ website: www.phpfoxtools.com
 * @copyright 2009
 */

//Do not change anything here unless you know exactly what you are doing
/*mysql_connect($_CONF['db']['host'], $_CONF['db']['user'], $_CONF['db']['pass']);

@mysql_select_db($_CONF['db']['name']) or die("Unable to select database: " . $_CONF['db']['name']);
*/
$dbhostname = $_CONF['db']['host'];
$dbname = $_CONF['db']['name'];
$dbuser = $_CONF['db']['user'];
$dbpass = $_CONF['db']['pass'];
$dbprefix = $_CONF['db']['prefix'];

try {
    $dbh = new PDO("mysql:host=$dbhostname;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo $e->getMessage();
}

?>