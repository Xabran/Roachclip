<?php
    //require("../constants.php");
    $con = mysql_connect("localhost", "roachcli_admin", "smokepot420");
    mysql_select_db("roachcli_db");

    $random_hash = md5(date('r', time()));
    //define the headers we want passed. Note that they are separated with \r\n
    $headers = "From: roach@roachclip.com\r\nReply-To: info@roachclip.com";
    //add boundary string and mime type specification
    $headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";

    $sql = "SELECT full_name,email FROM phpfox_user;";
    $results = (mysql_query($sql));

    //Now switch to social engine DB
    mysql_select_db("roachcli_se");

    $msg = "Hey everybody!<br/><br/>";
    $msg .= "The Roachclip team wanted to let you know that we released the new version of Roachclip.com's community!<br><br>";
    $msg .= "Roachclip.com's community area is still invite only via existing users or meeting people at festivals.  We want to build a true community the right way -";
    $msg .= " from the ground up with people we know and love!<br><br>";
    $msg .= "The community is using a new system, so we had to reset your password to: <br><br>";
    $msg .= "<b></b>@NEWPW<br><br>";
    $msg .= "You can find this new community at <a href='http://se.roachclip.com/' >se.roachclip.com/</a><br><br>";
    $msg .= "<i>Thanks for being there for us during the birth of a new online world!</i><br/><br/>";
    $msg .= "-Roach";

    if($results)
    {
	while ($aUser = mysql_fetch_assoc($results)) {
            //if(trim($aUser["email"]) != "" && $aUser["email"] != "roach@roachclip.com" && $aUser["email"] != "clip@roachclip.com"){
            if(trim($aUser["email"]) == "xabran1@hotmail.com"){
                //Update password in social engine
                $password = (string) rand(10000000, 99999999);
                $salt = (string) rand(100000, 999999);
                $crypt_password = md5("289d9cf55ee57cd04636af779e5cf8db23d951c7" . $password . $salt);

                $sql = "UPDATE engine4_users SET password = '" . $crypt_password . "', salt = '" . $salt . "' WHERE email = '" . $aUser["email"] . "' LIMIT 1";
                echo $sql . "<br>";
                mysql_query($sql);

                mail($aUser["email"],"Roachclip.com's Brand New Community!",str_replace("@NEWPW",$password,$msg),"From:roach@roachclip.com\r\nReply-To: info@roachclip.com\r\nContent-Type: text/html;");
                echo " emailed ".$aUser["email"]."<br><br>";
                echo str_replace("@NEWPW",$password,$msg);
            }
        }
    }


?>