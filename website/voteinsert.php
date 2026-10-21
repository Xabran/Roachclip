<?php
    require("constants.php");
    $con = mysql_connect($dbhost, $dbusr, $dbpw);
	mysql_select_db($targetDB);

	$sql = "INSERT INTO vote_tracking (FirstName, Vote, Email, Zip, Comment, PostComment, MailComment, RoachclipAcct, FreeDownload, SourceSite, SubmitDate ) VALUES ('".prepareforDB($_GET["FirstName"])."', ".prepareforDB(translateBooleanDB($_GET["Vote"])).", '".prepareforDB($_GET["Email"])."', '".prepareforDB($_GET["Zip"])."', '".prepareforDB($_GET["Comment"])."', ".prepareforDB(translateBooleanDB($_GET["PostComment"])).", ".prepareforDB(translateBooleanDB($_GET["MailComment"])).", ".prepareforDB(translateBooleanDB($_GET["RoachclipAcct"])).", ".prepareforDB(translateBooleanDB($_GET["FreeDownload"])).", 'www.roachclip.com', '".date("Y/m/d")."')";
	mysql_query($sql);
	setcookie("roachclip_clm", "Hello from Roachclip.com!", time()-1);
	
	function translateBooleanDB($boolVal)
	{
		if($boolVal == "" || !isset($boolVal))
		{
			return "false";
		}
		else return $boolVal;
	}
	function prepareforDB($boolVal)
	{
		return str_replace("'","''",$boolVal);
	}
?>