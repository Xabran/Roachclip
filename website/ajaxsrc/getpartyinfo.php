<?php
    require("../constants.php");
    require("../helpers.php");
    $con = mysql_connect($dbhost, $dbusr, $dbpw);
    mysql_select_db($targetDB);

    $sql = "SELECT EventName,Lineup,StartDate,EndDate,Venue,Address1,City,State,Zip,Link,PC.category_name,PC.category_id FROM parties AS P ";
    $sql .= "INNER JOIN parties_cats_link AS PCL ON PCL.party_id=P.id ";
    $sql .= "INNER JOIN parties_cats AS PC ON PCL.category_id=PC.category_id ";
    $sql .= "WHERE ID=".$_GET["ID"];
    //echo $sql;

    $results = (mysql_query($sql));
    if ($results)
    {
        $theParty = mysql_fetch_assoc($results);
        $lineup = $theParty["Lineup"];
        $link = $theParty["Link"];
        $pos = strpos($link,"http://");
        if($pos === false){
            $link = "http://" . $link;
        }
?>
<div style="background-color:#d1f7e7;">
    <table class="oracleStyle" style="width:500px;">
        <tr style="border-width: 0px;">
            <td style="text-align:left;border-width: 0px;padding-top:0px;vertical-align:top;" colspan="3">
                <table style="border-width: 0px;width:100%;">
                    <tr style="border-width: 0px;">
                        <td style="border-width: 0px;">
                            <h2 style="margin-left:2px;padding-top:2px;margin-top:2px;"><?=$theParty["EventName"]?></h2>
                        </td>
                        <td style="text-align:right;border-width: 0px;vertical-align:top;">
                            <span style="text-decoration:underline;cursor:pointer;" onclick="document.getElementById('detailsPopup').style.visibility = 'hidden';">Close</span>
                        </td>
                    </tr>
                </table>
                
            </td>
        </tr>
        <tr style="background-color:powderblue;margin-left:3px;">
            <td>
                <span style="font-size:12pt;">When</span>
            </td>
            <td>
                <span style="font-size:12pt;">Where</span>
            </td>
            <td>
                <span style="font-size:12pt;">What</span>
            </td>
        </tr>
        <tr style="padding-left:5px;padding-right:5px;">
            <td>
                <table>
                    <tr style="border-width:0px;">
                        <td style="border-width:0px;">
                            <p style="font-weight:bold;">Starts</p>
                        </td>
                        <td style="border-width:0px;">
                            <p><?=strftime("%m/%d/%Y",strtotime($theParty["StartDate"]))?></p>
                        </td>
                    </tr>
                    <tr style="border-width:0px;">
                        <td style="border-width:0px;">
                            <p style="font-weight:bold;">Ends</p>
                        </td>
                        <td style="border-width:0px;">
                            <p><?=strftime("%m/%d/%Y",strtotime($theParty["EndDate"]))?></p>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="padding-left:5px;padding-right:5px;">
                <p style="font-weight:bold;"><?=$theParty["Venue"]?></p>
                <p>
                    <?=$theParty["Address1"]?><br/>
                    <?=$theParty["City"]?>, <?=$theParty["State"]?>&nbsp;<?=$theParty["Zip"]?>
                </p>
            </td>
            <td style="padding-left:5px;padding-right:5px;">
                <p>
                    <?=$theParty["category_name"]?>
                    <?php
                    while ($theParty = mysql_fetch_assoc($results)) {
                        echo ",<br/>".$theParty["category_name"];
                    }
                    ?>
                </p>
                
            </td>
        </tr>
        <tr style="padding-left:5px;padding-right:5px;">
            <td colspan="3">
                <span style="font-size:12pt;font-weight:bold;">Lineup / Description</span>
                <p style="max-width:500px;"><?=$lineup?></p>
                <a href="<?php echo $link; ?>" target="_blank">Website</a>
            </td>
        </tr>
    </table>
</div>
<?php } ?>