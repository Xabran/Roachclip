<?php
    require_once("../constants.php");
    $con = mysql_connect($dbhost, $dbusr, $dbpw);
    mysql_select_db($targetDB);

    $cat = "All";
    $sort = "Category";
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
<div id="contentDisplay">
<img src="/public/admin/greenpagesheader.png" alt="header" class="image" />
<div style="text-align:center;margin-top:-9px;margin-bottom:10px;">
    <img src="/public/admin/horz-grad.png" width="850px" height="3px" class="image" alt="divider" />
        <p class="internalStyle" style="font-size:11pt;font-weight:normal;width:70%;">
            Welcome to The Green Pages!  We've scoured the internet to find the most interesting or useful Marijuana related websites around!
            <br/><br/><span style="font-size:9pt;margin-top:2px;">*Are we missing a great site?  E-Mail us at <a href="mailto:info@roachclip.com">info@roachclip.com</a></span>
        </p>
</div>
<div style="width:49%;text-align:left;height:40px;float:left;">
    <input type="text" maxlength="30" id="search" value="<?=$search?>"  /> <input type="button" value="Search" onclick="HttpRequest('greenpagesinternal.php?c=<?=$cat?>&s=<?=$sort?>&search='+document.getElementById('search').value,'contentDisplay');" />
</div>
<div style="width:45%;text-align:right;height:40px;float:left;">
    Sort by
    <select onchange="HttpRequest('greenpagesinternal.php?c=<?=$cat?>&s='+this.value+'&search=<?=$search?>','contentDisplay');">
        <option value="Title" <?=IsSelected('StartDate',$sort); ?> >Title</option>
        <option value="Category asc" <?=IsSelected('Category asc',$sort); ?> >Category</option>
     </select>
</div>

<table class="internalStyle" style="whitespace:nowrap;width:100%;">
    <tr style='border-width: 0px;font-size:12pt;'>
        <td>Title</td>
        <td width="80px">Category</td>
        <td>Website
<?php
	$sql = "SELECT ID,Title,URL,Category FROM greenpages AS P WHERE 1=1 ";

//        if($cat == "All"){
//                $sql .= "INNER JOIN parties_cats_link AS PCL ON PCL.party_id=P.id ";
//        }
//        else{
//                $sql .= "INNER JOIN parties_cats_link AS PCL ON PCL.party_id=P.id AND PCL.category_id=".$cat." ";
//        }
//        $sql .= "INNER JOIN parties_cats AS PC ON PCL.category_id=PC.category_id WHERE 1=1 ";
//        //$sql .= "WHERE (StartDate >= CURDATE() OR EndDate >= CURDATE()) ";
        if($search != "")
        {
            $sql .= " AND (Title LIKE '%".prepareforDB($search)."%' OR Category LIKE '%".prepareforDB($search)."%' OR URL LIKE '%".prepareforDB($search)."%')";
        }
        $sql .= "ORDER BY " . $sort . ", Title";
	//echo $sql;

	$results = (mysql_query($sql));
    if ($results)
    {
        $lastID="";
		while ($aParty = mysql_fetch_assoc($results)) {
			?>
                                </td>
                            </tr>
                            <tr style='border-width: 0px;'>
				<td>
                                    <span style="font-size:12pt;color:#5ba5de;"><?=$aParty["Title"]?></span>
				</td>
                                <td>
                                    <?=$aParty["Category"]?>
				</td>
                                <td>
                                    <a  class='internalStyle_link' target='_blank' href='<?=$aParty["URL"]?>'>Website</a>
                        <?php
		}
	}
?>
                                </td>
                            </tr>
</table>
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