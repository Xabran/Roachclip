<?php
    require("../constants.php");
	$con = mysql_connect($dbhost, $dbusr, $dbpw);
	mysql_select_db($targetDB);
	
        $sql = "SELECT V.video_id,title,text_parsed,video_url,name FROM phpfox_video AS V ";
        $sql .= "LEFT OUTER JOIN phpfox_video_text AS VT ON VT.video_id=V.video_id ";
        $sql .= "LEFT OUTER JOIN phpfox_video_category_data AS VCD ON VCD.video_id=V.video_id ";
        $sql .= "LEFT OUTER JOIN phpfox_video_category AS VC ON VC.category_id=VCD.category_id ";
        $sql .= "INNER JOIN phpfox_video_embed AS VE ON VE.video_id=V.video_id ";
        $sql .= "WHERE V.video_id = ";
	if(isset($_REQUEST["i"])){
		$sql .= $_REQUEST["i"];
	}
	
	$results = (mysql_query($sql));
	if ($results && $con) 
    {
		$theVid = mysql_fetch_assoc($results);
		
	}
        //echo $sql;
?>
<div style="text-align:center;">
<span class="catLink" onclick="menuSummon(document.getElementById('menu_vids'),'/ajaxsrc/videos.php?c=All');">Back to Videos</span>
<br/>
<img src="/images/horz-grad.png" width="600px" height="3px" class="image" />
</div>

<div style="text-align:center;margin-bottom:10px;margin-top:4px;">
        <h3 style="margin-top:-1px;"><?=$theVid["title"] ?></h3>
	<p><i><?=$theVid["text_parsed"] ?></i></p>
</div>
<table  style="border-width: 0px;" cellpadding="1" cellspacing="1">
			<tr style="border-width: 0px;" >
				<td style="border-width:0px; padding: 5 5 5 5;text-align:center;">
				<?php
					if (isset($theVid)) 
				    {
				?>
					<iframe title="YouTube video player" class="youtube-player" type="text/html" width="620px" height="350px" src="http://www.youtube.com/embed/<?=getYTVidID($theVid["video_url"])?>?autoplay=1&amp;hl=en&amp;fs=1" frameborder="0"></iframe>
					<br/>
					<span class="catLink" onclick="menuSummon(document.getElementById('menu_vids'),'/ajaxsrc/videos.php?c=All');">Back to Videos</span>
				<?php
					}
					else{
						?>
						No video found<br/>
						<span class="catLink" onclick="menuSummon('/ajaxsrc/videos.php?c=All','menu_vids');">Back to Videos</span>
						<?php
					}
				?>
				</td>
			</tr>
</table>
<?php
function getYTVidID($val1) {
    $x = substr($val1,stripos($val1,"?v=")+3);
    if(stripos($x,"&"))
    {
        $y = substr($x,0,stripos($x,"&"));
        return $y;
    }
    
    return $x;
}
?>