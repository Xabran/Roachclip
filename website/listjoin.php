<?php
        require_once 'classes/mailchimp/MCAPI.class.php';
        require_once 'classes/mailchimp/config.inc.php'; //contains apikey
	require("constants.php");
        require("helpers.php");

        $api = new MCAPI($apikey);
        $merge_vars = array('FNAME'=>$_REQUEST["name"]);
        $retval = $api->listSubscribe( $shopifyListID, $_REQUEST["email"], $merge_vars, "html", false );

//        if ($api->errorCode){
//            echo "Unable to load listSubscribe()!\n";
//            echo "\tCode=".$api->errorCode."\n";
//            echo "\tMsg=".$api->errorMessage."\n";
//        }

        /*$con = mysql_connect($dbhost, $dbusr, $dbpw);
	mysql_select_db($targetDB);
	$sql = "INSERT INTO email_list (Name,Email,Added,Source) VALUES ('".prepareforDB($_GET["name"])."','".prepareforDB($_GET["email"])."','".date("Y/m/d")."','".$_GET["source"]."');";
	mysql_query($sql);

	


        $subject = "List Submission";
        $msg = "Email: " . prepareforDB($_GET["email"]) . "<br/>";
        $msg .= "Name: " . prepareforDB($_GET["name"]);
        mail("derek@roachclip.com",$subject,$msg,"From:roach@roachclip.com\r\nReply-To: info@roachclip.com\r\nContent-Type: text/html;");*/

        echo "<p style='color:white;text-align:center;font-weight:bold;font-size:12pt;'>We've taken your info down!<br/>You'll hear from us soon..</p>";
?>