<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require("../constants.php");

    $con = mysql_connect($dbhost, $dbusr, $dbpw);
    mysql_select_db($targetDB);

    $sql = "SELECT full_name, (activity_total - last_total) as 'change' FROM phpfox_user_activity ";
    $sql .= "INNER JOIN phpfox_user ON phpfox_user.user_id=phpfox_user_activity.user_id ";
    $sql .= "INNER JOIN weekly_activity ON weekly_activity.user_id= phpfox_user_activity.user_id ";
    $sql .= "WHERE phpfox_user_activity.user_id IN (32,4,8,3,7,10,9) ";
    $sql .= "ORDER BY date DESC LIMIT 7;";
    echo $sql. "<br><br>";

    $results = mysql_query($sql);
    if($results)
    {
        $msg = "Below is a count of each users activity in the past week:<br/><br/>";
	while ($aRow = mysql_fetch_assoc($results)) {
            $msg .= $aRow["full_name"].": " . $aRow["change"] . "<br/>";
        }

        //Now update table and THEN send email.  This way we know there was an error if no email is sent
        $sql = "INSERT INTO weekly_activity (SELECT user_id, activity_total, NOW() FROM phpfox_user_activity ";
        $sql .= "WHERE phpfox_user_activity.user_id IN (32,4,8,3,7,10,9));";
        mysql_query($sql);
        echo $sql. "<br><br>";

        mail("derek@roachclip.com,matthew@roachclip.com","Weekly Activity Report",$msg,"From:roach@roachclip.com\r\nReply-To: info@roachclip.com\r\nContent-Type: text/html;");
        echo "Email sent:<br/>".$msg;
    }


    mysql_close($con);

?>