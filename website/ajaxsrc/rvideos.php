<?php
    require("../constants.php");
	$con = mysql_connect($dbhost, $dbusr, $dbpw);
	mysql_select_db($targetDB);
	

?>

<img src="/images/rvidsheader.png" class="image" />
<div style="text-align:center;margin-top:-10px;margin-bottom:10px;">
	<img src="/images/horz-grad.png" width="600px" height="3px" class="image" />
</div>
<table  style="border-width: 0px;" cellpadding="1" cellspacing="1">
<?php
	$sql = "SELECT V.video_id,title,text_parsed,video_url,embed_code FROM phpfox_video AS V ";
        $sql .= "LEFT OUTER JOIN phpfox_video_text AS VT ON VT.video_id=V.video_id ";
        $sql .= "INNER JOIN phpfox_video_embed AS VE ON VE.video_id=V.video_id ";
        //$sql .= "INNER JOIN phpfox_user_featured AS UF ON UF.user_id=V.user_id ";
        $sql .= "INNER JOIN roachclip_vids AS RV ON RV.video_id=V.video_id ";
	$sql .= " ORDER BY ";

	if(isset($_REQUEST["s"])){
		$sql .= $_REQUEST["s"];
	}
	else{
		$sql .= " V.video_id desc ";
	}
        //echo $sql;

	$results = (mysql_query($sql));
    if ($results) 
    {
		while ($aVid = mysql_fetch_assoc($results)) {
			?>
			<tr style="border-width: 0px;" >
				<td style="border-width:0px; padding: 0px 5px 5px 5px;width:350px;">
					<p><font style="font-weight:bold;font-size:12pt;margin-bottom:5px;"><?=$aVid["title"]?></font>
                                            <br/>
                                            <?=$aVid["text_parsed"]?>
					</p>
                                        <span style="border:0px;text-align:center;cursor:pointer;margin-left:4px;" onclick="menuSummon('menu_vids','/ajaxsrc/video.php?i=<?=$aVid["video_id"]?>');">
						<!--img class="image" src="http://img.youtube.com/vi/<?=getYTVidID($aVid["video_url"])?>/0.jpg" style="margin-top:10px;" /-->
                                                <iframe title="YouTube video player" class="youtube-player" type="text/html" width="500px" height="300px" src="http://www.youtube.com/embed/<?=getYTVidID($aVid["video_url"])?>?autoplay=0&amp;hl=en&amp;fs=1" frameborder="0"></iframe>
					</span>
                                        <hr style="width:50%;margin-top:15px;margin-bottom:5px;" />
				</td>
			</tr>
			<?php
		}
	}
?>
</table>

<?php
function getYTVidID($val1) {
    return substr($val1,stripos($val1,"?v=")+3);
}
?>