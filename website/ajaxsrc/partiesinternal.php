<?php
    require_once("../constants.php");
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
<link rel="stylesheet" href="internalstyle.css" type="text/css" media="screen" />
<script type="text/javascript" src="../ajax.js"></script>
<script type="text/javascript" src="../scripts/partyScript.js"></script>
<div id="contentDisplay">
<img src="/public/admin/partyheader.png" alt="header" class="image" />
<div style="text-align:center;margin-top:-9px;margin-bottom:10px;">
    <img src="/public/admin/horz-grad.png" width="850px" height="3px" class="image" alt="divider" />
        <p class="internalStyle" style="font-size:11pt;font-weight:normal;font-style:italic;width:99%;">
            The Party Oracle knows of all parties worth going to.  The Party Oracle will help get you to the next raging party nearby!
            <br/><span style="font-size:9pt;margin-top:2px;">*Note, the Party Oracle is currently near-sighted and can only see within California</span>
        </p>
</div>
<div style="width:49%;text-align:left;height:40px;float:left;">
    <input type="text" maxlength="30" id="search" value="<?=$search?>"  /> <input type="button" value="Search" onclick="HttpRequest('partiesinternal.php?c=<?=$cat?>&s=<?=$sort?>&search='+document.getElementById('search').value,'contentDisplay');" />
</div>
<div style="width:45%;text-align:right;height:40px;float:left;">
    Type:
    <select onchange="HttpRequest('partiesinternal.php?c='+this.value+'&s=<?=$sort?>&search=<?=$search?>','contentDisplay');">
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
    <select onchange="HttpRequest('partiesinternal.php?c=<?=$cat?>&s='+this.value+'&search=<?=$search?>','contentDisplay');">
        <option value="StartDate" <?=IsSelected('StartDate',$sort); ?> >Soonest</option>
        <option value="StartDate desc" <?=IsSelected('StartDate desc',$sort); ?> >Latest</option>
     </select>
</div>

<table class="internalStyle" style=";whitespace:nowrap;">
    <tr style='border-width: 0px;font-size:12pt;'>
        <td>Event</td>
        <td width="80px">Starts</td>
        <td>Location</td>
        <td>Details</td>
        <td>Type
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
        $i = 0;
		while ($aParty = mysql_fetch_assoc($results)) {
                    if($lastID != $aParty['ID']){
			?>
                                </td>
                            </tr>
                            <tr style='border-width: 0px;<?php if($i % 2 == 1){echo "background-color:#dffbb7;";} ?>'>
				<td>
                                    <span style="font-size:12pt;color:#5ba5de;"><?=$aParty["EventName"]?></span>
				</td>
                                <td>
                                    <?=strftime("%m/%d/%Y",strtotime($aParty["StartDate"]))?>
				</td>
                                <td>
                                    <?=$aParty["City"]?>, <?=$aParty["State"]?>
				</td>
                                <td width="100px">
                                    <span class='internalStyle_link' onclick="openPartyDetails(event,'<?=trim($aParty["ID"])?>');" >Click here</span>
                                    <?php
                                    //if(trim($aParty["Link"]) != ""){echo "<a  class='internalStyle_link' target='_blank' href='".$aParty["Link"]."' >Website</a>";}
                                    ?>
				</td>
                                <td>
                                    <?=trim($aParty["category_name"])?>
		<?php }else{ ?>,  <?=trim($aParty["category_name"])?>
                        <?php
                        }
                    $lastID = $aParty['ID'];
		}
	}
?>
                                </td>
                            </tr>
</table>
</div>
<div id="detailsPopup" style="visibility:hidden;z-index:20;position:absolute;background-color:White;padding:5px;border-width:2;border-color:Black;border-style:solid;">

</div>
<?php
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