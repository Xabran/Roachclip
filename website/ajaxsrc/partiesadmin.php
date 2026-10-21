<?php
    require_once("../constants.php");
    require_once("../classes/event.php");
    $con = mysql_connect($dbhost, $dbusr, $dbpw);
    mysql_select_db($targetDB);

    $cat = "All";
    $sort = "StartDate";
    $search = "";
    $eventlist  = array ();
    $stateList = array();
    $rownum = 1;

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

    $sql = "SELECT DISTINCT alpha_code FROM us_states ORDER BY alpha_code";
    $results = (mysql_query($sql));
    if ($results)
    {
        while ($aState = mysql_fetch_assoc($results)) {
            $stateList[] = $aState["alpha_code"];
        }
    }
?>
<link rel="stylesheet" href="internalstyle.css" type="text/css" media="screen" />
<script type="text/javascript" src="../ajax.js"></script>
<script type="text/javascript" >
    function addEvent()
    {
        if(document.getElementById("EventName").value == "")
        {
            alert("You must give an event name!");
            document.getElementById("EventName").focus();
        }
        else if(document.getElementById("City").value == "")
        {
            alert("You must input the city!");
            document.getElementById("City").focus();
        }
        else if(document.getElementById("State").value == "")
        {
            alert("You must input the state!");
            document.getElementById("State").focus();
        }
        else if(document.getElementById("Venue").value == "")
        {
            alert("You must input the venue!");
            document.getElementById("Venue").focus();
        }
        else if(document.getElementById("StartDate").value == "")
        {
            alert("You must input the starting date!");
            document.getElementById("StartDate").focus();
        }
        else{
            if(document.getElementById("StartDate").value != "")
            {
                var isValid =/^[ \s]*(0[1-9]|[1-9]|1[012])[- \/]([1-9]|0[1-9]|[12][0-9]|3[01])[- \/]((19|20)\d\d)[ \s]*$/.test(document.getElementById("StartDate").value);
                if(!isValid)
                {
                    alert("You must input the starting date in a mm/dd/yyyy format!");
                    document.getElementById("StartDate").focus();
                    return;
                }
            }
            if(document.getElementById("EndDate").value != "")
            {
                var isValid =/^[ \s]*(0[1-9]|[1-9]|1[012])[- \/]([1-9]|0[1-9]|[12][0-9]|3[01])[- \/]((19|20)\d\d)[ \s]*$/.test(document.getElementById("EndDate").value);
                if(!isValid)
                {
                    alert("You must input the ending date in a mm/dd/yyyy format!");
                    document.getElementById("EndDate").focus();
                    return;
                }
            }

            var catList = document.getElementById("catList").value.split(",");
            var selectedcatList = "";
            var catCheckOK = false;
            for(var i=0;i<catList.length;i++){
                if(document.getElementById("Cat"+catList[i]).checked)
                {
                    catCheckOK = true;
                    selectedcatList += catList[i]+",";
                }
            }

            if(catCheckOK){

                selectedcatList = selectedcatList.substr(0,selectedcatList.length-1);
                var requestString = "partiesadmindb.php?action=addparty";
                requestString += "&EventName="+encodeURIComponent(document.getElementById("EventName").value);
                requestString += "&Lineup="+encodeURIComponent(document.getElementById("Lineup").value);
                requestString += "&StartDate="+encodeURIComponent(document.getElementById("StartDate").value);
                requestString += "&EndDate="+encodeURIComponent(document.getElementById("EndDate").value);
                requestString += "&Venue="+encodeURIComponent(document.getElementById("Venue").value);
                requestString += "&Address1="+encodeURIComponent(document.getElementById("Address1").value);
                requestString += "&City="+encodeURIComponent(document.getElementById("City").value);
                requestString += "&State="+encodeURIComponent(document.getElementById("State").value);
                requestString += "&Zip="+encodeURIComponent(document.getElementById("Zip").value);
                requestString += "&Link="+encodeURIComponent(document.getElementById("Link").value);
                requestString += "&Categories="+selectedcatList;
                HttpRequest(requestString,"");
                window.location.href=window.location.href;
            }
            else{
                alert("You must choose at least one category!");
            }
        }
    }
    function updateEvent(id)
    {
        if(document.getElementById("EventName-"+id).value == "")
        {
            alert("You must give an event name!");
            document.getElementById("EventName-"+id).focus();
        }
        else if(document.getElementById("City-"+id).value == "")
        {
            alert("You must input the city!");
            document.getElementById("City-"+id).focus();
        }
        else if(document.getElementById("State-"+id).value == "")
        {
            alert("You must input the state!");
            document.getElementById("State-"+id).focus();
        }
        else if(document.getElementById("Venue-"+id).value == "")
        {
            alert("You must input the venue!");
            document.getElementById("Venue-"+id).focus();
        }
        else if(document.getElementById("StartDate-"+id).value == "")
        {
            alert("You must input the starting date!");
            document.getElementById("StartDate-"+id).focus();
        }
        else{
            if(document.getElementById("StartDate-"+id).value != "")
            {
                var isValid =/^[ \s]*(0[1-9]|[1-9]|1[012])[- \/]([1-9]|0[1-9]|[12][0-9]|3[01])[- \/]((19|20)\d\d)[ \s]*$/.test(document.getElementById("StartDate-"+id).value);
                if(!isValid)
                {
                    alert("You must input the starting date in a mm/dd/yyyy format!");
                    document.getElementById("StartDate-"+id).focus();
                    return;
                }
            }
            if(document.getElementById("EndDate-"+id).value != "")
            {
                var isValid =/^[ \s]*(0[1-9]|[1-9]|1[012])[- \/]([1-9]|0[1-9]|[12][0-9]|3[01])[- \/]((19|20)\d\d)[ \s]*$/.test(document.getElementById("EndDate-"+id).value);
                if(!isValid)
                {
                    alert("You must input the ending date in a mm/dd/yyyy format!");
                    document.getElementById("EndDate-"+id).focus();
                    return;
                }
            }
            
            var catList = document.getElementById("catList").value.split(",");
            var selectedcatList = "";
            var catCheckOK = false;
            for(var i=0;i<catList.length;i++){
                if(document.getElementById("Cat-"+id+"-"+catList[i]).checked)
                {
                    catCheckOK = true;
                    selectedcatList += catList[i]+",";
                }
            }

            if(catCheckOK){
                
                selectedcatList = selectedcatList.substr(0,selectedcatList.length-1);
                var requestString = "partiesadmindb.php?action=updateparty";
                requestString += "&id="+id;
                requestString += "&EventName="+encodeURIComponent(document.getElementById("EventName-"+id).value);
                requestString += "&Lineup="+encodeURIComponent(document.getElementById("Lineup-"+id).value);
                requestString += "&StartDate="+encodeURIComponent(document.getElementById("StartDate-"+id).value);
                requestString += "&EndDate="+encodeURIComponent(document.getElementById("EndDate-"+id).value);
                requestString += "&Venue="+encodeURIComponent(document.getElementById("Venue-"+id).value);
                requestString += "&Address1="+encodeURIComponent(document.getElementById("Address1-"+id).value);
                requestString += "&City="+encodeURIComponent(document.getElementById("City-"+id).value);
                requestString += "&State="+encodeURIComponent(document.getElementById("State-"+id).value);
                requestString += "&Zip="+encodeURIComponent(document.getElementById("Zip-"+id).value);
                requestString += "&Link="+encodeURIComponent(document.getElementById("Link-"+id).value);
                requestString += "&Categories="+selectedcatList;
                HttpRequest(requestString,"feedback-"+id);
                document.getElementById("feedback-"+id).innerHTML = "Updated!";
                setTimeout("document.getElementById('feedback-"+id+"').innerHTML ='';",2000);
            }
            else{
                alert("You must choose at least one category!");
            }
        }
    }

    function deleteEvent(id, source)
    {
        if(confirm("Are you sure you want to delete this event?")){
            HttpRequest("partiesadmindb.php?action=deleteparty&id="+id,"");
            source.parentNode.parentNode.parentNode.removeChild(source.parentNode.parentNode);
        }
    }
</script>
<div id="contentDisplay" style="overflow-x:hidden;">
<img src="/public/admin/partyheader.png" alt="header" class="image" />
<div style="text-align:center;margin-top:-9px;margin-bottom:10px;">
    <img src="/public/admin/horz-grad.png" width="850px" height="3px" class="image" alt="divider" />
</div>
<div>
    <table class="internalStyle" style="white-space:nowrap;">
    <tr style='border-width: 0px;font-size:12pt;background-color:darkgreen;color:white;'>
        <td>New Event</td>
        <td>Lineup/Description</td>
        <td>Location/Link</td>
        <td>Categories</td>
        <td></td>
    </tr>
    <tr style="border-width: 0px;vertical-align:top;background-color:#cdecd9;">
        <td style="vertical-align:top;">
            <input type="text" maxlength="50" id="EventName" value="" style="font-size:9pt;"><br>
            Starts&nbsp;<input type="text" maxlength="10" style="width:90px;" id="StartDate" value=""><br>
            Ends&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" style="width:90px;" maxlength="10" id="EndDate" value="">
        </td>
        <td>
            <textarea rows="7" cols="40" id="Lineup"></textarea>
        </td>
        <td>
            Venue<input style="margin-left:15px;" type="text" maxlength="50" id="Venue" value=""><br>
            Address<input type="text" maxlength="100" id="Address1" value=""><br>
            City<input style="margin-left:31px;" type="text" maxlength="25" id="City" value=""><br>
            State<select style="margin-left:20px;" id="State">
                    <option></option>
                    <?php
                    foreach($stateList as $state)
                    {
                        echo "<option value='".$state."'>".$state."</option>";
                    }
                    ?>
            </select>
            &nbsp;Zip<input style="margin-left:2px;width:76px;" type="text" maxlength="10" id="Zip" value="" />
            <br/>
            Link&nbsp;<input style="margin-left:27px;" type="text" maxlength="200" id="Link" value="">
        </td>
        <td>
            <div style="overflow-x:hidden;overflow-y:auto;">

            <?php
                    $sql = "SELECT category_name,category_id FROM parties_cats ORDER BY category_name";
                    $results = (mysql_query($sql));
                if ($results)
                {
                            while ($aCat = mysql_fetch_assoc($results)) {
                                $catList .= $aCat["category_id"] . "|" . $aCat["category_name"] . ",";
                                $catListForJava .= $aCat["category_id"].",";
            ?>
                <input type="checkbox" id="Cat<?= $aCat["category_id"] ?>" value="<?= $aCat["category_id"] ?>"/><?= $aCat["category_name"] ?><br/>
            <?php
                            }
                    }
                    $catList = substr($catList,0,strlen($catList)-1);
                    $catListForJava = substr($catListForJava,0,strlen($catListForJava)-1);

                    $catList = explode(",",$catList);
            ?>
            </div>
        </td>
        <td style="vertical-align:middle;">
            <input type="button" value="Add!" onclick="addEvent();">
        </td>
    </tr>
    </table>
</div>
<div><h3>Current Events</h3></div>
<div style="width:49%;text-align:left;height:40px;float:left;">
    <input type="text" maxlength="30" id="search" value="<?=$search?>"  /> <input type="button" value="Search" onclick="HttpRequest('partiesadmin.php?c=<?=$cat?>&s=<?=$sort?>&search='+document.getElementById('search').value,'contentDisplay');" />
</div>
<div style="width:45%;text-align:right;height:40px;float:left;">
    Type:
    <select onchange="HttpRequest('partiesadmin.php?c='+this.value+'&s=<?=$sort?>&search=<?=$search?>','contentDisplay');">
        <option value="All" <?=IsSelected('All',$cat); ?> >All</option>
            <?php
            foreach($catList as $acat)
            {
                $catDetails = explode("|",$acat);
            ?>
                <option value="<?= $catDetails[0] ?>" <?=IsSelected($catDetails[0],$cat); ?> ><?= $catDetails[1] ?></option>
            <?php
            }
            ?>
     </select>
    <select onchange="HttpRequest('partiesadmin.php?c=<?=$cat?>&s='+this.value+'&search=<?=$search?>','contentDisplay');">
        <option value="StartDate" <?=IsSelected('StartDate',$sort); ?> >Soonest</option>
        <option value="StartDate desc" <?=IsSelected('StartDate desc',$sort); ?> >Latest</option>
     </select>
</div>
<div style="width:100%;text-align:center;float:left;">
<table id="mainTable" class="internalStyle" style="white-space:nowrap;">
    <tr style='border-width: 0px;font-size:12pt;background-color:darkorange;color:white;'>
        <td>Title</td>
        <td>Lineup/Description</td>
        <td>Location/Link</td>
        <td>Categories</td>
        <td></td>
    </tr>
<?php
	$sql = "SELECT ID,EventName,Lineup,StartDate,EndDate,Venue,Address1,City,State,Zip,Link,PC.category_name,PC.category_id FROM parties AS P ";

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
        $anEvent;
		while ($aParty = mysql_fetch_assoc($results)) {
                    if($lastID != $aParty['ID']){
                        $anEvent  = new event();
                        $anEvent->ID = $aParty["ID"];
                        $anEvent->EventName = $aParty["EventName"];
                        $anEvent->Lineup = $aParty["Lineup"];
                        $anEvent->StartDate = $aParty["StartDate"];
                        $anEvent->EndDate = $aParty["EndDate"];
                        $anEvent->Venue = $aParty["Venue"];
                        $anEvent->Address1 = $aParty["Address1"];
                        $anEvent->City = $aParty["City"];
                        $anEvent->State = $aParty["State"];
                        $anEvent->Zip = $aParty["Zip"];
                        $anEvent->Link = $aParty["Link"];
                        $anEvent->Categories[] = array($aParty["category_id"],$aParty["category_name"]);
                        $eventlist[] = $anEvent;
                    }
                    else{
                        $eventlist[count($eventlist)-1]->Categories[] = array($aParty["category_id"],$aParty["category_name"]);
                    }
                    $lastID = $aParty['ID'];
                }
                $i=0;
                foreach($eventlist as $event)
                {
                    $rownum++;
			?>
                            <tr style='border-width: 0px;vertical-align:top;background-color:<?php if($i % 2 == 0){ echo "#bcdfe4;";}else{echo "White;";}?>'>
				<td style="vertical-align:top;">
                                    <input type="text" maxlength="50" id="EventName-<?=$event->ID?>" value="<?=$event->EventName?>" style="font-size:9pt;"/><br/>
                                    Starts&nbsp;<input type="text" maxlength="10" style="width:90px;" id="StartDate-<?=$event->ID?>" value="<?=strftime("%m/%d/%Y",strtotime($event->StartDate))?>" /><br/>
                                    Ends&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" style="width:90px;" maxlength="10" id="EndDate-<?=$event->ID?>" value="<?=strftime("%m/%d/%Y",strtotime($event->EndDate))?>" />
				</td>
                                <td>
                                    <textarea rows="7" cols="40" id="Lineup-<?=$event->ID?>" ><?=$event->Lineup?></textarea>
				</td>
                                <td>
                                    Venue<input style="margin-left:15px;" type="text" maxlength="50" id="Venue-<?=$event->ID?>" value="<?=$event->Venue?>" /><br/>
                                    Address<input type="text" maxlength="100" id="Address1-<?=$event->ID?>" value="<?=$event->Address1?>" /><br/>
                                    City<input style="margin-left:31px;" type="text" maxlength="25" id="City-<?=$event->ID?>" value="<?=$event->City?>" /><br/>
                                    State<select style="margin-left:20px;" id="State-<?=$event->ID?>">
                                        <option></option>
                                        <?php
                                        foreach($stateList as $state)
                                        {
                                            echo "<option ";
                                            if($event->State==$state){
                                                echo "selected='selected'";
                                            }
                                            echo " value='".$state."'>".$state."</option>";
                                        }
                                        ?>
                                    </select>
                                    &nbsp;Zip<input style="margin-left:2px;width:76px;" type="text" maxlength="10" id="Zip-<?=$event->ID?>" value="<?=$event->Zip?>" />
                                    <br/>
                                    Link&nbsp<input  style="margin-left:27px;" type="text" maxlength="200" id="Link-<?=$event->ID?>" value="<?=$event->Link?>" />
				</td>
                                <td>
                                    <div style="overflow-x:hidden;overflow-y:auto;">
                                        <?php
                                        foreach($catList as $cat)
                                        {
                                            $catDetails = explode("|",$cat);
                                            echo "<input type='checkbox' id='Cat-".$event->ID."-".$catDetails[0]."'";
                                            $theCats = $event->Categories;
                                            foreach($theCats as $partycat){
                                                if($partycat[1]==$catDetails[1]){
                                                    echo " checked ";
                                                }
                                            }
                                            echo " value='".$catDetails[0]."' />";
                                            echo $catDetails[1]."<br/>";
                                        }
                                        ?>
                                    </div>
                                </td>
                                <td style="vertical-align:middle;">
                                    <input type="button" value="Update" onclick="updateEvent('<?=$event->ID?>');" />
                                    <br/>
                                    <div id="feedback-<?=$event->ID?>" style="color:Green;"></div>
                                    <br/>
                                    <input type="button" value="Delete" style="color:red;" onclick="deleteEvent('<?=$event->ID?>',this);" />
                                </td>
                            </tr>
                <?php
                $i++;
                }
    }
                ?>
</table>
<input type="hidden" id="catList" value="<?=$catListForJava?>" />
</div>
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