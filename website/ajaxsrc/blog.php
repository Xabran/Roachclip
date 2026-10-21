<?php
    require("../constants.php");
	$con = mysql_connect($dbhost, $dbusr, $dbpw);
	mysql_select_db($targetDB);
	
	$sql = "SELECT B.title, B.blog_id,BT.text_parsed,B.time_stamp,U.full_name,BC.category_id,BCN.name,U.user_image FROM phpfox_blog AS B ";
	$sql .= "INNER JOIN phpfox_blog_text AS BT ON BT.blog_id=B.blog_id ";
	$sql .= "INNER JOIN phpfox_blog_category_data AS BC ON BC.blog_id=B.blog_id ";
	$sql .= "INNER JOIN phpfox_blog_category AS BCN ON BC.category_id=BCN.category_id ";
	$sql .= "INNER JOIN phpfox_user AS U ON U.user_id=B.user_id WHERE B.blog_id=";
	if(isset($_REQUEST["i"])){
		$sql .= $_REQUEST["i"];
	}
	
	$results = (mysql_query($sql));
	if ($results && $con) 
        {
		$theBlog = mysql_fetch_assoc($results);
		$origBlog = $theBlog;
	}
	//echo $sql;
?>

<div style="text-align:center;width:720px;">
<span class="catLink" onclick="menuSummon(document.getElementById('menu_blogs'),'/ajaxsrc/blogs.php?c=All');">Back to Blogs</span>
<div style="text-align:center;margin-bottom:10px;width:640px;margin-left:20px;">

	<div style="float:right;text-align:center;margin-left:20px;">
		<br/>
                <img class="image" src=<?=getUserImg($root,$theBlog["user_image"])?> width="75px" style="margin-top:10px;margin-bottom:4px;" /><br/>
		<font style="font-weight:bold;text-decoration:underline;margin-bottom:5px;"><?=$theBlog["full_name"]?></font>
	</div>
	<h3 style="margin-top:5px;"><?=$theBlog["title"] ?></h3>
	<p style="margin-top:-10px;"><!--b><?= date('F-d-Y', $theBlog["time_stamp"])?></b><br/-->
	
	Categories:&nbsp;<span class="catLink" onclick="menuSummon(document.getElementById('menu_blogs'),'/ajaxsrc/blogs.php?c=<?=$theBlog["category_id"]?>&s=id');"><?=$theBlog["name"]?></span>
	<?php
		while($theBlog = mysql_fetch_assoc($results))
		{
		?>
		|&nbsp;<span class="catLink" onclick="menuSummon(document.getElementById('menu_blogs'),'/ajaxsrc/blogs.php?c=<?=$theBlog["category_id"]?>&s=id');"><?=$theBlog["name"]?></span>
		<?php
		}
	?>
	<br/><br/>
	<i><?=$origBlog["text_parsed"] ?></i></p>
	<img src="/images/horz-grad.png" width="600px" height="3px" class="image" />
	<br/>
		<span class="catLink" onclick="menuSummon(document.getElementById('menu_blogs'),'/ajaxsrc/blogs.php?c=All');">Back to Blogs</span>
</div>
</div>
<?php
function getUserImg($root, $path)
{
    return $root . "/tribe/file/pic/user/".str_replace('%s',"_75",$path);
}
?>