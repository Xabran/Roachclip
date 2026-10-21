<?php
    require("../constants.php");
    $con = mysql_connect($dbhost, $dbusr, $dbpw);
    mysql_select_db($targetDB);

    $sql = "SELECT user_id FROM (SELECT user_id FROM phpfox_user UNION ALL SELECT user_id FROM phpfox_fbconnect) AS allusers ORDER BY user_id";
    $results = (mysql_query($sql));
    $sql = "DELETE FROM phpfox_user_notification;";
                if ($results)
                {
                            while ($aUser = mysql_fetch_assoc($results)) {
                                $sql = $sql . "INSERT INTO phpfox_user_notification (user_id,user_notification) VALUES (".$aUser["user_id"].",'comment.add_new_comment');";
                                $sql = $sql . "INSERT INTO phpfox_user_notification (user_id,user_notification) VALUES (".$aUser["user_id"].",'comment.approve_new_comment');";
                                $sql = $sql . "INSERT INTO phpfox_user_notification (user_id,user_notification) VALUES (".$aUser["user_id"].",'forum.subscribe_new_post');";
                                $sql = $sql . "INSERT INTO phpfox_user_notification (user_id,user_notification) VALUES (".$aUser["user_id"].",'friend.new_friend_accepted');";
                                $sql = $sql . "INSERT INTO phpfox_user_notification (user_id,user_notification) VALUES (".$aUser["user_id"].",'friend.new_friend_request');";
                                $sql = $sql . "INSERT INTO phpfox_user_notification (user_id,user_notification) VALUES (".$aUser["user_id"].",'mail.new_message');";
                                $sql = $sql . "INSERT INTO phpfox_user_notification (user_id,user_notification) VALUES (".$aUser["user_id"].",'newsletter.can_receive_notification');";
                            }
                            echo $sql . "<br/><br/><br/>";
                            mysql_query($sql);
                            $sql ="";
                }
?>






