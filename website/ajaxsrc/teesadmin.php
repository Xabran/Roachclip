<?php
    require("../constants.php");
    require("../helpers.php");
    require("../classes/teesites.php");
    $con = mysql_connect($dbhost, $dbusr, $dbpw);
    mysql_select_db($targetDB);

    $cat = "All";
    $site = "All";

    if(isset($_REQUEST["c"]))
    {
        $cat = $_REQUEST["c"];
    }
    if(isset($_REQUEST["site"]))
    {
        $site = $_REQUEST["site"];
    }
?>
<link rel="stylesheet" href="internalstyle.css" type="text/css" media="screen" />
<script type="text/javascript" src="../ajax.js"></script>
<script type="text/javascript" >
    function addShirt()
    {
        if(document.getElementById("ShirtURL").value == "")
        {
            alert("You must input the URL to the shirts page! (Shirt URL)");
            document.getElementById("ShirtURL").focus();
        }
        else if(document.getElementById("ImageURL").value == "")
        {
             alert("You must input the URL of the thumbnail image for the shirt! (Image URL)");
             document.getElementById("ImageURL").focus();
        }
        else if(document.getElementById("category1").value == "")
        {
              alert("You must choose a category from at least Category 1!");
              document.getElementById("category1").focus();
        }
        else{
            HttpRequest('teesadmindb.php?action=addshirt&affiliatelink='+document.getElementById("ShirtURL").value+"&imagelink="+document.getElementById("ImageURL").value+"&category1="+document.getElementById("category1").value+"&category2="+document.getElementById("category2").value,'');
            //window.location.href=window.location.href;
            var newnode = "<tr style='border-width: 0px;'><td style='border-width:0px; padding: 5px 0px 5px 0px;text-align:center;'>";
            newnode += "<img alt='tshirt' class='image' src='"+document.getElementById("ImageURL").value+"' width='180px' height='180px' style='margin-bottom:4px;'></td>";
            newnode += "<td color='BlueGreen'><i>Newly Added Shirt<br/>Refresh the page to modify</i></td>";
            newnode += "<td><b>Categories:</b><br>"+document.getElementById("category1")[document.getElementById("category1").selectedIndex].innerHTML;
            if(document.getElementById("category2").value != ""){
                newnode += "<br>"+document.getElementById("category2")[document.getElementById("category2").selectedIndex].innerHTML;
            }
            newnode += "</td></tr>";
            var oldnode = document.getElementById('mainTable').innerHTML;
            document.getElementById('mainTable').innerHTML = newnode + oldnode;
        }
        
    }
    function deleteShirt(source, id)
    {
        if(confirm("Are you sure you want to delete this shirt?"))
        {
            HttpRequest('teesadmindb.php?action=deleteshirt&id='+id,'');
            source.parentNode.parentNode.parentNode.removeChild(source.parentNode.parentNode);
        }
        
    }
    function deleteCat(source, shirtid, catid)
    {
        if(confirm("Are you sure you want to delete this category?"))
        {
            HttpRequest('teesadmindb.php?action=deletecat&shirt_id='+shirtid+"&category_id="+catid,'');
            source.parentNode.parentNode.removeChild(source.parentNode);
        }
    }
    function addCat(source, shirtid)
    {
        var catid = source.value;

        if(catid != null && catid!= "")
        {
            HttpRequest('teesadmindb.php?action=addcat&shirt_id='+shirtid+"&category_id="+catid,'');

            var newnode = "<div><b>"+source[source.selectedIndex].innerHTML+"</b><span style='color:red;cursor:pointer;' onclick='deleteCat(this,"+shirtid+","+catid+")'> x</span></div>";
            var oldnode = document.getElementById('catlist-'+shirtid).innerHTML;
            document.getElementById('catlist-'+shirtid).innerHTML = newnode + oldnode;
        }
    }
</script>
<div id="contentDisplay" style="overflow:visible;">
<img src="/public/admin/teesheader.png" alt="header" class="image" style="margin-top:-10px;" />
<div style="text-align:center;margin-bottom:2px;">
    <div id="feedback"></div>
	<img src="/public/admin/horz-grad.png" width="850px" height="3px" class="image" alt="divider" />
	<br/>
        <table class="image"  style="width:95%;border-width: 0px;" >
            <tr>
                <td style="text-align:left;vertical-align:top;border-width: 0px;padding-left:5px;vertical-align:bottom;">
                    <div>
                        <b>View By Type:</b> <select id="typeSelector" onchange="HttpRequest('teesadmin.php?c='+this.value+'&site='+document.getElementById('siteSelector').value,'contentDisplay');">
                            <option value="All" <?=IsSelected('All',$cat); ?> >All</option>
                <?php
                        $sql = "SELECT DISTINCT category_id,category_name FROM tshirt_cats ORDER BY category_name";
                        $results = (mysql_query($sql));
                        $i=0;
                    if ($results)
                    {
                                while ($aCat = mysql_fetch_assoc($results)) {

                                    $catList .= $aCat["category_id"] . "|" . $aCat["category_name"] . ",";
                ?>
                    <option value="<?= $aCat["category_id"] ?>" <?=IsSelected($aCat["category_id"],$cat); ?> ><?= $aCat["category_name"] ?></option>
                <?php
                                }
                        }
                    $catList = substr($catList,0,strlen($catList)-1);
                    $catList = explode(",",$catList);
                    
                ?>
                    </select>
                    <br/>
                    <b>View By Site:</b>
                    <select id="siteSelector" onchange="HttpRequest('teesadmin.php?c='+document.getElementById('typeSelector').value+'&site='+this.value,'contentDisplay');">
                        <option value="All" <?=IsSelected('All',$site); ?> >All</option>
                <?php
                        $TeeSiteObj = new TeeSites();
                        $theSites = $TeeSiteObj->SiteList;
                        foreach ($theSites as $aSite)
                        {
                ?>
                        <option value="<?=$aSite?>" <?=IsSelected($aSite,$site); ?> ><?=$aSite?></option>
                <?php
                        }
                ?>
                    </select>
                    </div>
                </td>
                <td style="text-align:right;">
                    <table  class="image" style="background-color:silver">
                            <tr>
                                <td colspan="4" style="text-align:center;">
                                    <h4>Add a New Shirt</h4>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Shirt URL
                                </td>
                                <td>
                                    Image URL
                                </td>
                                <td>
                                    Category 1
                                </td>
                                <td>
                                    Category 2
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" maxlength="500" id="ShirtURL" width="300px" value="" />
                                </td>
                                <td>
                                    <input type="text" maxlength="500" id="ImageURL" width="300px" value="" />
                                </td>
                                <td>
                                    <select id="category1">
                                        <option></option>
                                        <?php

                                        foreach($catList as $aCategory)
                                        {
                                            $catDetails = explode("|",$aCategory);
                                            echo "<option value='".$catDetails[0]."'>".$catDetails[1]."</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select id="category2">
                                        <option></option>
                                        <?php

                                        foreach($catList as $aCategory)
                                        {
                                            $catDetails = explode("|",$aCategory);
                                            echo "<option value='".$catDetails[0]."'>".$catDetails[1]."</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td style="white-space:nowrap;">
                                    <input type="button" onclick="addShirt()" value="Add Shirt!" />
                                </td>
                            </tr>
                        </table>
                </td>
            </tr>
        </table>
</div>

<table id="mainTable" style="border-width: 0px;width:99%;" cellpadding="1" cellspacing="1"><tr><td>
<?php
	$sql = "SELECT id, affiliatelink, imagelink, TCL.category_id, category_name FROM tshirts AS T LEFT OUTER JOIN tshirt_cats_link AS TCL ON TCL.shirt_id=T.id LEFT OUTER JOIN tshirt_cats AS TC ON TC.category_id=TCL.category_id  WHERE 1=1 ";

        if($cat == "All"){
            //do nothing
        }
        else{
                $sql .= "AND TCL.category_id=".$cat." ";
        }
        if($site == "All"){
            //do nothing
        }
        else{
                $sql .= "AND affiliatelink LIKE '%".$site."%' ";
        }

	$sql .= "ORDER BY id desc";
	//echo $sql;

	$results = (mysql_query($sql));
        $lastID = "";
    if ($results)
    {
		while ($aShirt = mysql_fetch_assoc($results)) {
                    if($lastID != $aShirt["id"]){
			?>
                        </td></tr>
                            <tr style='border-width: 0px;' >
				<td style="border-width:0px; padding: 5px 0px 5px 0px;text-align:center;width:20%;">
                                    <a href="<?=fullAfLink($aShirt["affiliatelink"])?>" target="_blank" ><img alt="tshirt" class="image" src="<?=$aShirt["imagelink"]?>" width="180px" height="180px" style="margin-bottom:4px;" /></a>
				</td>
                                <td>
                                    <span style="color:red;cursor:pointer;" onclick="deleteShirt(this,<?=$aShirt["id"]?>)">Delete Shirt</span>
                                </td>
                                <td>
                                    <b>Categories:</b><br/><br/>
                                    Add New: <select onchange="addCat(this,<?=$aShirt["id"]?>);">
                                        <option></option>
                                        <?php
                                        
                                        foreach($catList as $aCategory)
                                        {
                                            $catDetails = explode("|",$aCategory);
                                            echo "<option value='".$catDetails[0]."'>".$catDetails[1]."</option>";
                                        }
                                        ?>
                                    </select>
                                    <br/>
                                    <div id="catlist-<?=$aShirt["id"]?>">
                                    <?php
                                        if($aShirt["category_id"] != ""){
                                    ?>
                                        <div><b><?=$aShirt["category_name"]?></b><span style="color:red;cursor:pointer;" onclick="deleteCat(this,<?=$aShirt["id"]?>,<?=$aShirt["category_id"]?>)"> x</span></div>
			<?php
                                    }
                    }
                    else{
                        ?>
                                    <div><b><?=$aShirt["category_name"]?></b><span style="color:red;cursor:pointer;" onclick="deleteCat(this,<?=$aShirt["id"]?>,<?=$aShirt["category_id"]?>)"> x</span></div>
                        <?php
                    }
                        $lastID = $aShirt["id"];
		}
	}
?>
                                    </div>
                                </td>
                            </tr>
</table>
</div>