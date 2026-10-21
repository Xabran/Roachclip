<?php
	mail($_GET["to"],$_GET["subject"],$_GET["message"],"From:".$_GET["email"]);
	require("constants.php");
    $con = mysql_connect($dbhost, $dbusr, $dbpw);
	mysql_select_db($targetDB);
	$sql = "INSERT INTO promotion_track (Email,Promotion,SourceSite) VALUES ('".$_GET["email"]."','iPad-MJVote','www.roachclip.com');";
	mysql_query($sql);
	echo "<h4 style=\"text-align:center;\">Emails sent!</h4><div style=\"width:342px;cursor:pointer;\" onclick=\"closeMe('emailPopup');\"><p style='text-align:center;color:Blue;text-decoration:underline;'>Close Window</p></div>";
?>