<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require("../constants.php");
    require("../classes/notifications.php");

    $con = mysql_connect($dbhost, $dbusr, $dbpw);
    mysql_select_db($targetDB);

    //$random_hash = md5(date('r', time()));
    //define the headers we want passed. Note that they are separated with \r\n
    //$headers = "From: roach@roachclip.com\r\nReply-To: info@roachclip.com";
    //add boundary string and mime type specification
    //$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";

    $sql = "SELECT type_id, item_id, item_title, TU.full_name AS 'to', TU.user_id, TU.user_name, TU.email, U.full_name AS 'made_by' FROM weekly_notification AS W ";
    $sql .= "INNER JOIN phpfox_user AS U ON U.user_id=W.owner_user_id ";
    $sql .= "INNER JOIN phpfox_user AS TU ON TU.user_id=W.user_id ";
    $sql .= "LEFT JOIN weekly_notif_nosend AS N ON N.user_id=W.user_id ";
    $sql .= "WHERE N.user_id IS NULL ";
    $sql .= "ORDER BY TU.user_id,type_id,item_title";
    echo $sql. "<br><br>";

    $results = mysql_query($sql);
    $lastUser = "";
    $mailedCount = 0;
    $BatchNots = new Notifications();
    if($results)
    {
        //do first row for proper setup
        $aNot = mysql_fetch_assoc($results);
        $lastUser = $aNot["user_id"];
        $BatchNots = new Notifications();
        $BatchNots->ToName = $aNot["to"];//
        $BatchNots->ToAcctName = $aNot["user_name"];
        $BatchNots->ToEmail = $aNot["email"];

	while ($aNot = mysql_fetch_assoc($results)) {
            if($aNot["user_id"] != $lastUser)
            {
                echo $BatchNots->ToName;
                //if($mailedCount < 8){
                    sendNotEmail($BatchNots);
                //}
                $mailedCount++;
                $BatchNots = new Notifications();
                $BatchNots->ToName = $aNot["to"];
                $BatchNots->ToAcctName = $aNot["user_name"];
                $BatchNots->ToEmail = $aNot["email"];
            }

            $newNot = new Notification();
            $newNot->item_title = $aNot["item_title"];
            $newNot->item_id = $aNot["item_id"];
            $newNot->FromName = $aNot["made_by"];
            switch ($aNot["type_id"])
            {
                case "comment_blog":
                    $BatchNots->BlogCmmnts[] = $newNot; break;
                case "comment_photo":
                    $BatchNots->PhotoCmmnts[] = $newNot; break;
                case "comment_profile":
                    $BatchNots->ProfileCmmnts[] = $newNot; break;
                case "comment_feed":
                    $BatchNots->FeedCmmnts[] = $newNot; break;
                case "comment_video":
                    $BatchNots->VideoCmmnts[] = $newNot; break;
                case "forum_subscribed_post":
                    $BatchNots->Forums[] = $newNot; break;
                case "friend_request":
                    $BatchNots->FRequests[] = $newNot; break;
                case "mail_send":
                    $BatchNots->Messages[] = $newNot; break;
                default:
                    break;
            }

            $lastUser = $aNot["user_id"];
        }

        //Send off final batch
        sendNotEmail($BatchNots);
    }

    $sql = "DELETE FROM weekly_notification;";
    mysql_query($sql);
    echo "wipe complete.";
    
    mysql_close($con);


    
    function sendNotEmail($NotList)
    {
        $subject = "Your Daily Roachclip Buzz";
        $msg = "<p>Hey ". $NotList->ToName . ",</p>";
        $msg .= "<p>There are people talking to you on <a href='http://www.roachclip.com/tribe/'>Roachclip.com</a>!  Here's a summary:</p>";
        $msg .= "<table style='color:green;'>";
        if(sizeof($NotList->Messages) > 0){
            $msg .= "<tr><td style='white-space:nowrap;'><b>Private Messages: </b></td><td><a href='http://www.roachclip.com/tribe/mail/' target='_blank'>" . sizeof($NotList->Messages) . "</a></td></tr>";
        }
        if(sizeof($NotList->FRequests) > 0){
            $msg .= "<tr><td style='vertical-align:top;white-space:nowrap;'><b>Friend Requests: </b></td><td>";
            foreach($NotList->FRequests as $aReq){
                $msg .= $aReq->FromName . "<br/>";
            }
            $msg .= "</td></tr>";
        }
        if(sizeof($NotList->BlogCmmnts) > 0){
            $msg .= "<tr><td style='vertical-align:top;color;green;white-space:nowrap;'><b>Blog Comments: </b></td><td><span style='color:green;'>";
            $lastTitle = "";
            $last_item_id = "";
            $count = 0;
            $hasReset = false;
            foreach($NotList->BlogCmmnts as $aBlog){
                $count++;
                if($aBlog->item_title != $lastTitle && $lastTitle != "")
                {
                    $msg .= $count. " comment(s) on \"<a href='http://www.roachclip.com/tribe/blog/redirect_".$aBlog->item_id."/' target='_blank'>" . $aBlog->item_title . "\"<br/>";
                    $count = 0;
                    $hasReset = true;
                }

                $lastTitle = $aBlog->item_title;
                $last_item_id = $aBlog->item_id;
            }
            //If item_title never changes then the post count isn't outputted.  therefore I set this up to ensure it gets displayed
            if(!$hasReset)
            {
                $msg .= $count. " comment(s) on \"<a href='http://www.roachclip.com/tribe/blog/redirect_".$last_item_id."/' target='_blank'>" . $lastTitle . "\"</a><br/>";
            }
            $msg .= "</span></td></tr>";
        }
        if(sizeof($NotList->PhotoCmmnts) > 0){
            $msg .= "<tr><td style='white-space:nowrap;'><b>Photo Comments: </b></td><td><a href='http://www.roachclip.com/tribe/".$NotList->ToAcctName."/photo/' target='_blank'>" . sizeof($NotList->PhotoCmmnts) . "</a></td></tr>";
        }
        if(sizeof($NotList->ProfileCmmnts) > 0){
            $msg .= "<tr><td style='white-space:nowrap;'><b>Profile Comments: </b></td><td><a href='http://www.roachclip.com/tribe/".$NotList->ToAcctName."/' target='_blank'>" . sizeof($NotList->ProfileCmmnts) . "</a></td></tr>";
        }
        if(sizeof($NotList->FeedCmmnts) > 0){
            $msg .= "<tr><td style='white-space:nowrap;'><b>Feed Comments: </b></td><td>" . sizeof($NotList->FeedCmmnts) . "</td></tr>";
        }
        if(sizeof($NotList->VideoCmmnts) > 0){
            $msg .= "<tr><td style='vertical-align:top;color;green;white-space:nowrap;'><b>Video Comments: </b></td><td><span style='color:green;'>";
            $lastTitle = "";
            $last_item_id = "";
            $count = 0;
            $hasReset = false;
            foreach($NotList->VideoCmmnts as $aVid){
                $count++;
                if($aVid->item_title != $lastTitle && $lastTitle != "")
                {
                    $msg .= $count. " comment(s) on \"<a href='http://www.roachclip.com/tribe/video/redirect_".$aVid->item_id."/' target='_blank'>" . $aVid->item_title . "\"<br/>";
                    $count = 0;
                    $hasReset = true;
                }

                $lastTitle = $aVid->item_title;
                $last_item_id = $aVid->item_id;
            }
            //If item_title never changes then the post count isn't outputted.  therefore I set this up to ensure it gets displayed
            if(!$hasReset)
            {
                $msg .= $count. " comment(s) on \"<a href='http://www.roachclip.com/tribe/video/redirect_".$last_item_id."/' target='_blank'>" . $lastTitle . "\"</a><br/>";
            }
            $msg .= "</span></td></tr>";
        }
        if(sizeof($NotList->Forums) > 0){
            $msg .= "<tr><td style='vertical-align:top;color;green;white-space:nowrap;'><b>Forum Replies: </b></td><td><span style='color:green;'>";
            $lastTitle = "";
            $last_item_id = "";
            $count = 0;
            $hasReset = false;
            foreach($NotList->Forums as $aRep){
                $count++;
                if($aRep->item_title != $lastTitle && $lastTitle != "")
                {
                    $msg .= $count. " posts to \"<a href='http://www.roachclip.com/tribe/forum/thread/predirect_".$aRep->item_id."/' target='_blank'>" . $aRep->item_title . "\"</a><br/>";
                    $count = 0;
                    $hasReset = true;
                }

                $lastTitle = $aRep->item_title;
                $last_item_id = $aRep->item_id;
            }
            //If item_title never changes then the post count isn't outputted.  therefore I set this up to ensure it gets displayed
            if(!$hasReset)
            {
                $msg .= $count. " posts to \"<a href='http://www.roachclip.com/tribe/forum/thread/predirect_".$last_item_id."/' target='_blank'>" . $lastTitle . "\"</a><br/>";
            }
            $msg .= "</span></td></tr>";
        }
        $msg .= "</table><br/><p><a href='http://www.roachclip.com/tribe/'>Login</a> to Roachclip.com to see what the buzz is all about!</p>";
        $msg .= "~~<p style='font-size:10pt;color:blue;margin-top:5px;'>This message summarizes your activity on Roachclip so you can stay in the loop without getting a message every time someone tries to communicate with you on the community.  If you wish to unsubscribe email derek@roachclip.com</p>";
        $msg .= "<b>As always, remember to live, love and celebrate<br/><i>-Roach</i></b>";
        mail($NotList->ToEmail,$subject,$msg,"From:roach@roachclip.com\r\nReply-To: info@roachclip.com\r\nContent-Type: text/html;");
        
        mail("derek@roachclip.com",$subject,$msg,"From:roach@roachclip.com\r\nReply-To: info@roachclip.com\r\nContent-Type: text/html;");
        echo " emailed ".$NotList->ToEmail."<br><br>";
    }

    function pfoxurlfrmt($input)
    {
        return str_replace(" ","-",$input);
    }
?>