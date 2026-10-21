<?php
    require("../constants.php");
    require("facebook.php");
    $con = mysql_connect($dbhost, $dbusr, $dbpw);
    mysql_select_db($targetDB);

    $facebook = new Facebook(array(
      'appId'  => $FBAppID,
      'secret' => $FBAppSecret,
      'cookie' => true, // enable optional cookie support
    ));

    $sql = "DELETE FROM phpfox_user_facebook;";
    (mysql_query($sql));
    $sql = "SELECT user_id,fb_user_id FROM phpfox_fbconnect;";
    $results = (mysql_query($sql));

    if($results)
    {
	while ($anFBUser = mysql_fetch_assoc($results)) {
            $aPerson = $facebook->api('/'.$anFBUser['fb_user_id']);
            echo $aPerson["name"] . "  " . $aPerson["email"] . "<br><br>";
            $sql = "UPDATE phpfox_user SET email ='".$aPerson["email"]."' WHERE user_id='".$anFBUser["user_id"]."';";
            mysql_query($sql);
        }
    }
?>