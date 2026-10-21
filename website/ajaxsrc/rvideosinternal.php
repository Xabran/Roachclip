<?php
    require("../constants.php");
	$con = mysql_connect($dbhost, $dbusr, $dbpw);
	mysql_select_db($targetDB);
	

?>
<link rel="stylesheet" href="internalstyle.css" type="text/css" media="screen" />
<script type="text/javascript" src="../ajax.js"></script>
<div id="contentDisplay" style="width:100%;">
<img src="/images/rvidsheader.png" class="image" />
<div style="text-align:center;margin-top:-10px;margin-bottom:10px;">
	<img src="/images/horz-grad.png" width="850px" height="3px" class="image" />
</div>
<div style="width:70%;margin-bottom:15px;" class="internalStyle">
        Here you will find all of Roachclip.com's exclusive videos and video series.  Roachclip video will take you through a world of interests, social commentary, interviews and useful information!
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
				<td style="border-width:0px; padding: 0px 5px 5px 5px;width:350px;vertical-align:top;">
					<p><font style="font-weight:bold;font-size:12pt;margin-bottom:5px;"><?=$aVid["title"]?></font>
                                            <br/><br/>
                                            <?=$aVid["text_parsed"]?>
					</p>
                                        
				</td>
                                <td>
                                        <span style="border:0px;text-align:center;cursor:pointer;margin-left:4px;" onclick="HttpRequest('videointernal.php?i=<?=$aVid["video_id"]?>','contentDisplay');">
						<!--img class="image" src="http://img.youtube.com/vi/<?=getYTVidID($aVid["video_url"])?>/0.jpg" style="margin-top:10px;" /-->
                                                <iframe title="YouTube video player" class="youtube-player" type="text/html" width="500px" height="300px" src="http://www.youtube.com/embed/<?=getYTVidID($aVid["video_url"])?>?autoplay=0&amp;hl=en&amp;fs=1" frameborder="0"></iframe>
					</span>
                                </td>
			</tr>
                        <tr>
                            <td colspan="2">
                                <hr style="width:70%;" />
                            </td>
                        </tr>
			<?php
		}
	}
?>
</table>
</div>
<?php
function getYTVidID($val1) {
    return substr($val1,stripos($val1,"?v=")+3);
}
?>