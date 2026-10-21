<?php
    require("../constants.php");
    require("../helpers.php");
    $con = mysql_connect($dbhost, $dbusr, $dbpw);
    mysql_select_db($targetDB);

    if($_GET["action"] == "deleteparty")
    {
        $sql = "DELETE FROM parties WHERE id=" . $_GET["id"];
        mysql_query($sql);
        $sql = "DELETE FROM parties_cats_link WHERE party_id=" . $_GET["id"];
        mysql_query($sql);
    }
    else if($_GET["action"] == "updateparty")
    {
        $selectedCats = explode(",",$_GET["Categories"]);
        $sql = "UPDATE parties SET EventName='".prepareforDB(urldecode($_GET["EventName"]))."',";
        $sql .= "Lineup='".prepareforDB(urldecode($_GET["Lineup"]))."',";
        $sql .= "StartDate='".strftime("%Y/%m/%d",strtotime(prepareforDB(urldecode($_GET["StartDate"]))))."',";
        if(prepareforDB(urldecode($_GET["EndDate"])) == null){
            $sql .= "EndDate='".strftime("%Y/%m/%d",strtotime(prepareforDB(urldecode($_GET["StartDate"]))))."',";
        }
        else{
            $sql .= "EndDate='".strftime("%Y/%m/%d",strtotime(prepareforDB(urldecode($_GET["EndDate"]))))."',";
        }
        $sql .= "Venue='".prepareforDB(urldecode($_GET["Venue"]))."',";
        $sql .= "Address1='".prepareforDB(urldecode($_GET["Address1"]))."',";
        $sql .= "City='".prepareforDB(urldecode($_GET["City"]))."',";
        $sql .= "State='".prepareforDB(urldecode($_GET["State"]))."',";
        $sql .= "Zip='".prepareforDB(urldecode($_GET["Zip"]))."',";
        $sql .= "Link='".prepareforDB(urldecode($_GET["Link"]))."' ";
        $sql .= "WHERE parties.id=" . $_GET["id"].";";
        mysql_query($sql);
        foreach($selectedCats as $aCat){
            $sql = "INSERT INTO parties_cats_link (paty_id,category_id) VALUES (" .$_GET["id"].",".$aCat.");";
            mysql_query($sql);
        }
        //echo "Updated!";
    }
    else if($_GET["action"] == "addparty")
    {
        $selectedCats = explode(",",$_GET["Categories"]);
        $sql = "INSERT INTO parties (EventName,Lineup,StartDate,EndDate,Venue,Address1,City,State,Zip,Link) VALUES ('";
        $sql .= prepareforDB(urldecode($_GET["EventName"]))."','";
        $sql .= prepareforDB(urldecode($_GET["Lineup"]))."','";
        $sql .= strftime("%Y/%m/%d",strtotime(prepareforDB(urldecode($_GET["StartDate"]))))."','";
        if(prepareforDB(urldecode($_GET["EndDate"])) == null){
            $sql .= strftime("%Y/%m/%d",strtotime(prepareforDB(urldecode($_GET["StartDate"]))))."','";}
        else{
            $sql .= strftime("%Y/%m/%d",strtotime(prepareforDB(urldecode($_GET["EndDate"]))))."','";
        }
        $sql .= prepareforDB(urldecode($_GET["Venue"]))."','";
        $sql .= prepareforDB(urldecode($_GET["Address1"]))."','";
        $sql .= prepareforDB(urldecode($_GET["City"]))."','";
        $sql .= prepareforDB(urldecode($_GET["State"]))."','";
        $sql .= prepareforDB(urldecode($_GET["Zip"]))."','";
        $sql .= prepareforDB(urldecode($_GET["Link"]))."')";
        echo $sql;
        mysql_query($sql);

        foreach($selectedCats as $aCat){
            $sql = "INSERT INTO parties_cats_link (party_id,category_id) VALUES (LAST_INSERT_ID(),".$aCat.");" ;
            mysql_query($sql);
        }
    }
    echo "";

?>