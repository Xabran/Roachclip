<?php
    require("../constants.php");
    $con = mysql_connect($dbhost, $dbusr, $dbpw);
    mysql_select_db($targetDB);
    
        $cat = "All";

        if(isset($_REQUEST["c"]))
	{
            $cat = $_REQUEST["c"];
        }
?>

<div style="float:left;"><img src="images/blogsheader.png" class="image" /></div>
<div style="float:right;vertical-align:bottom;margin-top:18px;">
            Categories: <select onchange="menuSummon(document.getElementById('menu_blogs'),'/ajaxsrc/blogs.php?c='+this.value);">
             <option value="All" <?=IsSelected('All',$cat); ?> >All</option>
	<?php
		$sql = "SELECT category_id,name FROM phpfox_blog_category order by name";
		$results = (mysql_query($sql));
	    if ($results)
	    {
			while ($aCat = mysql_fetch_assoc($results)) {

	?>
            <option value="<?= $aCat["category_id"] ?>" <?=IsSelected($aCat["category_id"],$cat); ?> ><?= $aCat["name"] ?></option>
	<?php
			}
		}
	?>
        </select>

</div>
<div style="text-align:center;margin-top:-7px;margin-bottom:10px;float:left;">
        <img src="/images/horz-grad.png" width="600px" height="3px" class="image" />

<table  style="border-width: 0px;margin-top:10px;" cellpadding="1" cellspacing="1">
<?php
	$sql = "SELECT DISTINCT B.title, B.blog_id,BT.text_parsed,B.time_stamp,U.full_name,U.user_image FROM phpfox_blog AS B ";
	$sql .= "INNER JOIN phpfox_blog_text AS BT ON BT.blog_id=B.blog_id ";
	$sql .= "INNER JOIN phpfox_blog_category_data AS BC ON BC.blog_id=B.blog_id ";
	$sql .= "INNER JOIN phpfox_user AS U ON U.user_id=B.user_id ";
	$sql .= "INNER JOIN phpfox_user_featured AS UF ON UF.user_id=B.user_id ";
	//$sql .= "INNER JOIN phpfox_photo AS P ON P.user_id=B.user_id AND P.is_cover=1 ";
	$sql .= "WHERE ";

        if($cat == "All"){
                $sql .= "1=1 ";
        }
        else{
                $sql .= "BC.category_id=".$cat." ";
        }
	
	$sql .= "ORDER BY B.blog_id desc";
	//echo $sql;
	/*if(isset($_REQUEST["s"])){
		$sql .= $_REQUEST["s"];
	}
	else{
		$sql .= " SubmitDate desc ";
	}*/
	
	$results = (mysql_query($sql));
    if ($results) 
    {
		while ($aBlog = mysql_fetch_assoc($results)) {
			?>
			<tr style="border-width: 0px;" >
				<td style="border-width:0px; padding: 5px 0px 5px 0px;width:692px;text-align:center;">
					<div style="height:200px;overflow:hidden;width:640px;margin-left:20px;">
						<div style="float:right;text-align:center;margin-left:20px;">
                                                        Author:<br/>
							<img class="image" src=<?=getUserImg($root,$aBlog["user_image"])?> width="75px" style="margin-bottom:4px;" /><br/>
							<font style="font-weight:bold;text-decoration:underline;margin-bottom:5px;"><?=$aBlog["full_name"]?></font>
							</div>
							<p><font class="catLink" onclick="menuSummon(document.getElementById('menu_blogs'),'/ajaxsrc/blog.php?i=<?=$aBlog["blog_id"]?>');" style="font-weight:bold;font-size:12pt;"><?=$aBlog["title"]?></font><br/>
						   <font style="font-weight:bold;font-style:italic;"><?php //echo date('F-d-Y', $aBlog["time_stamp"])?></font><br/>
							<?=$aBlog["text_parsed"]?>
						</p>
					</div>
					<div style="width:720px;text-align:center;">
						<span class="catLink" onclick="menuSummon(document.getElementById('menu_blogs'),'/ajaxsrc/blog.php?i=<?=$aBlog["blog_id"]?>');">Read more...</span>
					</div>
                                        <div style="width:720px;text-align:center;">
						<hr width="80%" />
					</div>
				</td>
			</tr>
			<?php
		}
	}
?>
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
function getUserImg($root, $path)
{
    return $root . "/tribe/file/pic/user/".str_replace('%s',"_75",$path);
}
?>