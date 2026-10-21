<?php
    require("../constants.php");
    $con = mysql_connect($dbhost, $dbusr, $dbpw);
    mysql_select_db($targetDB);

    $sql = "SELECT affiliatelink,imagelink FROM tshirts ORDER BY RAND() LIMIT 1";
    $results = (mysql_query($sql));
    if ($results)
    {
        $aTee = mysql_fetch_assoc($results);

?>

    <a href="<?=$aTee["affiliatelink"]?>" target="_blank" ><img class="image"  alt="randomtee" src="<?=$aTee["imagelink"]?>" width="180px" height="180px" style="margin-bottom:4px;" /></a>
<?php
    }
?>
                