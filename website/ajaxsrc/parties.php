<?php
    require("../constants.php");
    $con = mysql_connect($dbhost, $dbusr, $dbpw);
    mysql_select_db($targetDB);

    $cat = "All";
    $sort = "StartDate";
    $search = "";

    if(isset($_REQUEST["c"]))
    {
        $cat = $_REQUEST["c"];
    }
    if(isset($_REQUEST["s"]))
    {
        $sort = $_REQUEST["s"];
    }
    if(isset($_REQUEST["search"]))
    {
        $search = $_REQUEST["search"];
    }
?>
<img src="../images/partyheader.png" alt="header" class="image" />
<div style="text-align:center;margin-top:-9px;margin-bottom:10px;">
    <img src="../images/horz-grad.png" width="850px" height="3px" class="image" alt="divider" />
        <p class="oracleStyle" style="font-size:11pt;font-weight:normal;font-style:italic;width:70%;">
            The Party Oracle knows of all parties worth going to.  The Party Oracle will help get you to the next raging party nearby!
            <br/><span style="font-size:9pt;margin-top:2px;">*Note, the Party Oracle is currently near-sighted and can only see within California</span>
        </p>
</div>
<div style="width:49%;text-align:left;height:40px;float:left;">
    <input type="text" maxlength="30" id="search" value="<?=$search?>"  /> <input type="button" value="Search" onclick="menuSummon(document.getElementById('menu_parties'),'/ajaxsrc/parties.php?c=<?=$cat?>&s=<?=$sort?>&search='+document.getElementById('search').value);" />
</div>
<div style="width:45%;text-align:right;height:40px;float:left;">
    Type:
    <select onchange="menuSummon(document.getElementById('menu_parties'),'/ajaxsrc/parties.php?c='+this.value+'&s=<?=$sort?>&search=<?=$search?>');">
        <option value="All" <?=IsSelected('All',$cat); ?> >All</option>
            <?php
                    $sql = "SELECT category_name,category_id FROM parties_cats ORDER BY category_name";
                    $results = (mysql_query($sql));
                    $i=0;
                if ($results)
                {
                            while ($aCat = mysql_fetch_assoc($results)) {

            ?>
                <option value="<?= $aCat["category_id"] ?>" <?=IsSelected($aCat["category_id"],$cat); ?> ><?= $aCat["category_name"] ?></option>
            <?php
                            }
                    }
            ?>
     </select>
    <select onchange="menuSummon(document.getElementById('menu_parties'),'/ajaxsrc/parties.php?c=<?=$cat?>&s='+this.value+'&search=<?=$search?>');">
        <option value="StartDate" <?=IsSelected('StartDate',$sort); ?> >Soonest</option>
        <option value="StartDate desc" <?=IsSelected('StartDate desc',$sort); ?> >Latest</option>
     </select>
</div>

<table class="oracleStyle" style="width:96%;whitespace:nowrap;" id="mainPartyTbl">
    <tr style='border-width: 0px;font-size:12pt;text-decoration:underline;'>
        <td style='border-width: 0px;'>Event</td>
        <td style='border-width: 0px;' width="80px">Starts</td>
        <td style='border-width: 0px;'>Location</td>
        <td style='border-width: 0px;'>Details</td>
        <td style='border-width: 0px;'>Type
<?php
	$sql = "SELECT ID,EventName,Lineup,StartDate,EndDate,Venue,Address1,City,State,Link,PC.category_name,PC.category_id FROM parties AS P ";

        if($cat == "All"){
                $sql .= "INNER JOIN parties_cats_link AS PCL ON PCL.party_id=P.id ";
        }
        else{
                $sql .= "INNER JOIN parties_cats_link AS PCL ON PCL.party_id=P.id AND PCL.category_id=".$cat." ";
        }
        $sql .= "INNER JOIN parties_cats AS PC ON PCL.category_id=PC.category_id ";
        $sql .= "WHERE (StartDate >= CURDATE() OR EndDate >= CURDATE()) ";
        if($search != "")
        {
            $sql .= " AND (City LIKE '%".prepareforDB($search)."%' OR State LIKE '%".prepareforDB($search)."%' OR EventName LIKE '%".prepareforDB($search)."%' OR Lineup LIKE '%".prepareforDB($search)."%' OR Venue LIKE '%".prepareforDB($search)."%' OR Address1 LIKE '%".prepareforDB($search)."%')";
        }
        $sql .= "ORDER BY " . $sort . ", EventName";
	//echo $sql;

	$results = (mysql_query($sql));
    if ($results)
    {
        $lastID="";
        $i=0;
		while ($aParty = mysql_fetch_assoc($results)) {
                    if($lastID != $aParty['ID']){
			?>
                                </td>
                            </tr>
                            <tr style='border-width: 0px;<?php if($i % 2 == 1){echo "background-color:#dffbb7;";} ?>'>
				<td style='border-width: 0px;vertical-align:middle;'>
                                    <span style="font-size:12pt;"><?=$aParty["EventName"]?></span>
				</td>
                                <td style='border-width: 0px;vertical-align:middle;'>
                                    <?=strftime("%m/%d/%Y",strtotime($aParty["StartDate"]))?>
				</td>
                                <td style='border-width: 0px;vertical-align:middle;'>
                                    <?=$aParty["City"]?>, <?=$aParty["State"]?>
				</td>
                                <td style='border-width: 0px;vertical-align:middle;white-space:nowrap;'>
                                    <div>
                                        <span class='roachclip_link' onclick="openPartyDetails(event,'<?=trim($aParty["ID"])?>');" >Click here</span>
                                        <?php
                                        //if(trim($aParty["Link"]) != ""){echo "<a  class='roachclip_link' target='_blank' href='".$aParty["Link"]."' >Website</a>";}
                                        ?>
                                    </div>
				</td>
                                <td style='border-width: 0px;vertical-align:middle;'>
                                    <?=trim($aParty["category_name"])?>
		<?php 
                    $i++;
                    }else{ ?>&nbsp;<?=trim($aParty["category_name"])?>
                        <?php
                        }
                    $lastID = $aParty['ID'];
		}
	}
?>
                                </td>
                            </tr>
</table>
<div id="detailsPopup" style="visibility:hidden;z-index:100;position:absolute;background-color:White;padding:5px;border-width:2;border-color:Black;border-style:solid;">

</div>
<?php
function checkForActive($val1, $val2, $ifTrueClass, $ifFalseClass) {
	if($val1 == $val2)
	{
		echo $ifTrueClass;
	}
	else
	{
		echo $ifFalseClass;
	}
}

function IsSelected($val1,$val2)
{
    	if($val1 == $val2)
	{
		echo "selected='selected'";
	}
	else
	{
		echo "";
	}
}
	function prepareforDB($boolVal)
	{
		return str_replace("'","''",$boolVal);
	}
?>