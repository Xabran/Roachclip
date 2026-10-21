<?php
    require("../constants.php");
    require("../helpers.php");
    require("../classes/teesites.php");
    $con = mysql_connect($dbhost, $dbusr, $dbpw);
    mysql_select_db($targetDB);

    if($_GET["action"] == "deleteshirt")
    {
        $sql = "DELETE FROM tshirts WHERE id=" . $_GET["id"];
        mysql_query($sql);
        $sql = "DELETE FROM tshirt_cats_link WHERE shirt_id=" . $_GET["id"];
        mysql_query($sql);
    }
    else if($_GET["action"] == "deletecat")
    {
        $sql = "DELETE FROM tshirt_cats_link WHERE shirt_id=" . $_GET["shirt_id"] . " AND category_id=". $_GET["category_id"] ;
        mysql_query($sql);
    }
    else if($_GET["action"] == "addcat")
    {
        $sql = "SELECT COUNT(shirt_id) AS CT FROM tshirt_cats_link WHERE shirt_id=". $_GET["shirt_id"] ." AND category_id=". $_GET["category_id"];
        $results = mysql_fetch_assoc(mysql_query($sql));
        if($results['CT'] == 0)
        {
            $sql = "INSERT INTO tshirt_cats_link (shirt_id,category_id) VALUES (" . $_GET["shirt_id"] . ",". $_GET["category_id"] . ")" ;
            mysql_query($sql);
        }

    }
    else if($_GET["action"] == "addshirt")
    {
        $sql = "INSERT INTO tshirts (affiliatelink,imagelink) VALUES ('".prepareforDB($_GET["affiliatelink"])."','".prepareforDB($_GET["imagelink"])."');";
        mysql_query($sql);
        $sql = "INSERT INTO tshirt_cats_link (shirt_id,category_id) VALUES (LAST_INSERT_ID(),". $_GET["category1"] . ");" ;
        mysql_query($sql);
        if($_GET["category2"] != null)
        {
            $sql = "INSERT INTO tshirt_cats_link (shirt_id,category_id) VALUES (LAST_INSERT_ID(),". $_GET["category2"] . ")" ;
            mysql_query($sql);
        }
    }
    echo "";
?>