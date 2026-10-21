<?php
    require("../constants.php");
    require("../helpers.php");
    require("../classes/teesites.php");
    $con = mysql_connect($dbhost, $dbusr, $dbpw);
    mysql_select_db($targetDB);

    $cat = "All";

    if(isset($_REQUEST["c"]))
    {
        $cat = $_REQUEST["c"];
    }
?>
<link rel="stylesheet" href="internalstyle.css" type="text/css" media="screen" />
<script type="text/javascript" src="../ajax.js"></script>
<div id="contentDisplay" style="overflow:visible;">
<img src="/public/admin/teesheader.png" alt="header" class="image" />
<div style="text-align:center;margin-top:-10px;margin-bottom:2px;">
	<img src="/public/admin/horz-grad.png" width="850px" height="3px" class="image" alt="divider" />
	<br/>
        <table class="image"  style="width:85%;border-width: 0px;" >
            <tr>
                <td style="text-align:left;vertical-align:top;border-width: 0px;padding-left:20px;" colspan="2">
                    <div style="width:400px;">
                        <p>We've searched, sorted, sifted and finally selected the coolest T-Shirts on the web.</p>
                    </div>
                </td>
                <td style="text-align:center;vertical-align:top;border-width: 0px;" rowspan="2">
                    <img src="/public/admin/randomheader.png" alt="randomfind" class="image" style="margin-bottom:2px;display:none;" />
                    <div id="randomtee" style="">
                    <?php
                        $sql = "SELECT affiliatelink,imagelink FROM tshirts ORDER BY RAND() LIMIT 1";
                        $results = (mysql_query($sql));
                        if ($results)
                        {
                            $aTee = mysql_fetch_assoc($results);
                    ?>

                        <a href="<?=fullAfLink($aTee["affiliatelink"])?>" target="_blank" ><img class="image"  alt="randomtee" src="<?=$aTee["imagelink"]?>" width="180px" height="180px" style="margin-bottom:4px;" /></a>
                    <?php
                        }
                    ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="text-align:left;vertical-align:top;border-width: 0px;padding-left:20px;">
                    <div style="width:250px;">
                        <p style="margin-top:32px;">You can choose a category below, or </p>
                    </div>
                    <div>
                        <b>Categories:</b> <select onchange="HttpRequest('teesinternal.php?c='+this.value,'contentDisplay');">
                            <option value="All" <?=IsSelected('All',$cat); ?> >All</option>
                <?php
                        $sql = "SELECT DISTINCT category_id,category_name FROM tshirt_cats ORDER BY category_name";
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
                    </div>
                </td>
                <td style="vertical-align:top;">
                    <div style="cursor:pointer;margin-top:30px;"><img src="/public/admin/randombutton.png" alt="randomtee" onclick="HttpRequest('randomtee.php','randomtee');" class="image"  /></div>
                </td>
            </tr>
        </table>
</div>
<hr style="width:96%;margin-top:2px;margin-bottom:2px;">

<table  style="border-width: 0px;width:99%;" cellpadding="1" cellspacing="1">
    <tr style='border-width: 0px;' >
<?php
	$sql = "SELECT id, affiliatelink, imagelink FROM tshirts AS T ";

        if($cat == "All"){
                $sql .= "WHERE 1=1 ";
        }
        else{
                $sql .= "INNER JOIN tshirt_cats_link AS TC ON TC.shirt_id=T.id AND category_id='".$cat."' ";
        }

	$sql .= "ORDER BY id desc";
	//echo $sql;

	$results = (mysql_query($sql));
        $i=1;
    if ($results)
    {
		while ($aShirt = mysql_fetch_assoc($results)) {
			?>
				<td style="border-width:0px; padding: 5px 0px 5px 0px;text-align:center;width:20%;">
                                    <a href="<?=fullAfLink($aShirt["affiliatelink"])?>" target="_blank" ><img alt="tshirt" class="image" src="<?=$aShirt["imagelink"]?>" width="180px" height="180px" style="margin-bottom:4px;" /></a>
				</td>
			<?php
                    if($i % 4 == 0){
                        echo "</tr><tr style='border-width: 0px;' >";
                    }
                    $i++;
		}
	}
?>
    </tr>
</table>
</div>