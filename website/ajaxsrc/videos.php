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
<div style="float:left;"><img src="images/vidsheader.png" class="image" /></div>
<div style="float:right;vertical-align:bottom;margin-top:18px;">
    <b>Tune in to:</b> <select onchange="menuSummon(document.getElementById('menu_videos'),'/ajaxsrc/videos.php?c='+this.value);">
             <option value="All" <?=IsSelected('All',$cat); ?> >All Channels</option>
	<?php
		$sql = "SELECT category_id,name FROM phpfox_video_category order by name";
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
<div style="text-align:center;margin-bottom:10px;float:left;">
<img src="/images/horz-grad.png" width="600px" height="3px" class="image" />
<table  style="border-width: 0px;" cellpadding="1" cellspacing="1">
<?php
	$sql = "SELECT DISTINCT V.video_id,title,text_parsed,video_url,embed_code,RV.video_id AS KillMe FROM phpfox_video AS V ";
        $sql .= "LEFT OUTER JOIN phpfox_video_text AS VT ON VT.video_id=V.video_id ";
        $sql .= "LEFT OUTER JOIN phpfox_video_category_data AS VC ON VC.video_id=V.video_id ";
        $sql .= "INNER JOIN phpfox_video_embed AS VE ON VE.video_id=V.video_id ";
        $sql .= "LEFT OUTER JOIN roachclip_vids AS RV ON RV.video_id=V.video_id ";
        //$sql .= "INNER JOIN phpfox_user_featured AS UF ON UF.user_id=V.user_id WHERE ";
        //$sql .= "WHERE V.is_featured = 1 AND ";
        $sql .= "WHERE ";
        if($cat == "All"){
            $sql .= "1=1 ";
        }
        else{
            $sql .= "VC.category_id=".$cat." ";
        }

	$sql .= " ORDER BY ";

        if(isset($_REQUEST["s"])){
            $sql .= $_REQUEST["s"];
	}
	else{
            $sql .= " V.video_id desc ";
	}
        $sql .= " LIMIT 0, 15 ";
        //echo $sql;

	$results = (mysql_query($sql));

    if ($results && $con) 
    {
		while ($aVid = mysql_fetch_assoc($results)) {
                    if($aVid['KillMe'] == ""){
			?>
			<tr style="border-width: 0px;" >
				<td style="border-width:0px; padding: 0px 5px 5px 10px;width:350px;">
                                    <div style="height:200px;width:400px;overflow:hidden;" >
					<p><font style="font-weight:bold;font-size:12pt;margin-bottom:5px;"><?=$aVid["title"]?></font><br/>
						<?=$aVid["text_parsed"]?>
					</p>
                                    </div>
				</td>
				<td style="border-width:0px;">
					<span style="border:0px;text-align:center;cursor:pointer;margin-left:10px;" onclick="menuSummon(document.getElementById('menu_vids'),'/ajaxsrc/video.php?i=<?=$aVid["video_id"]?>');">
						<img class="image" src="http://i1.ytimg.com/vi/<?=getYTVidID($aVid["video_url"])?>/default.jpg" width="180px" height="120px" style="margin-top:30px;" />
					</span>
				</td>
			</tr>
                        <tr  >
                            <td colspan="2" style="border-width: 0px;">
                                <hr width="60%" />
                            </td>
                        </tr>
			<?php
                    }
		}
	}
?>
</table>
</div>
<?php
function getYTVidID($val1) {
    $midway = substr($val1,stripos($val1,"?v=")+3);
    if(stripos($midway,"&")){
        return substr($midway,0,stripos($midway,"&"));
    }else{
        return $midway;
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
?>