<?php
    require("constants.php");
    $con = mysql_connect($dbhost, $dbusr, $dbpw);
	mysql_select_db($targetDB);
	$page_num = 1;
	$pageination_amt = 10;
	$paging_breadth = 3;
	$already_voted = "false";
	$total_comments = 0;

	if(isset($_REQUEST['p']))
	{
		$page_num = $_REQUEST['p'];
	}
	if(isset($_COOKIE["roachclip_clm"]))
	{
		$already_voted = "true";
	}
	
	$PWVal = "";
	if(isset($_COOKIE["roachclip_pw"]))
	{
		$PWVal = "party";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US" xml:lang="en">
<head>
    <!--
    Created by Artisteer v2.5.0.29918
    Base template (without user's data) checked by http://validator.w3.org : "This page is valid XHTML 1.0 Transitional"
    -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<meta property="og:title" content="Roachclip.com | Everything Cool" />
	<meta property="og:description" content="Should marijuana be legal? Check out this online legalization debate… http://roachclip.com/legalizationdebate.php Next week California is voting on whether or not to legalize pot, very interesting topic no matter what side you are on. Short video tells more about the controversial issue... http://roachclip.com/legalizationdebate.php They're giving away an Apple iPad to get more people to vote and share opinions (its quick and you can participate anonymously)." />
	<meta property="og:image" content="http://www.roachclip.com/images/logo-thumbnail.png" />
    <title>Roachclip.com | Everything Cool</title>

    <link rel="stylesheet" href="style.css" type="text/css" media="screen" />
    <!--[if IE 6]><link rel="stylesheet" href="style.ie6.css" type="text/css" media="screen" /><![endif]-->
    <!--[if IE 7]><link rel="stylesheet" href="style.ie7.css" type="text/css" media="screen" /><![endif]-->
    <script type="text/javascript" src="script.js"></script>
	<script type="text/javascript" src="ajax.js"></script>
	<script type="text/javascript" src="http://www.plaxo.com/css/m/js/util.js"></script>
	<script type="text/javascript" src="http://www.plaxo.com/css/m/js/basic.js"></script>
	<script type="text/javascript" src="http://www.plaxo.com/css/m/js/abc_launcher.js"></script>
	<script type="text/javascript"><!--
        function verifyStepOne() {
            var radioAnswer = document.getElementsByName("vote");
            if (!radioAnswer[0].checked && !radioAnswer[1].checked) {
                alert("You must choose 'Yes' or 'No'!");
                return false;
            }
            else if (document.getElementById("Comment").value == "") {
                return confirm("You didn't include your opinion!  Are you sure you want to continue?");
            }
			return true;
        }
		function verifyStepTwo()
		{
			if(document.getElementById("Email").value == "")
			{
				document.getElementById("Email").focus();
				alert("Please type in your primary Email Address!");
				return false;
			}
			else if(!/^([a-zA-Z0-9_\-\.]+)@[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]$/.test(document.getElementById("Email").value))
			{
				document.getElementById("Email").focus();
				alert("Your Email address doesn't look right.  It should follow a xxx@yyy.zzz format!");
				return false;
			}
			else if(document.getElementById("MailComment").checked)
			{
				/*if(document.getElementById("Zip").value == "")
				{
					document.getElementById("Zip").focus();
					alert("We need your zip code to mail your comments to your Local Representative!");
					return false;
				}
				else if(!/^\d{5}$/.test(document.getElementById("Zip").value))
				{
					document.getElementById("Zip").focus();
					alert("Your zip code must be 5 digits!");
					return false;
				}
				if(document.getElementById("FirstName").value == "")
				{
					document.getElementById("FirstName").focus();
					alert("We need your first name to mail your comments to your Local Representative!");
					return false;
				}*/
			}
			document.getElementById("emailfrom").value = document.getElementById("Email").value;
			
			var requeststring = "voteinsert.php?FirstName="+document.getElementById("FirstName").value+"&Vote="+document.getElementById("voteval").value+"&Email="+document.getElementById("Email").value+"&Zip="+document.getElementById("Zip").value+"&Comment="+document.getElementById("commentval").value+"&PostComment="+document.getElementById("postcommentval").value+"&MailComment="+document.getElementById("MailComment").value+"&RoachclipAcct="+document.getElementById("RoachclipAcct").value+"&FreeDownload="+document.getElementById("FreeDownload").value+"&SourceSite=www.roachclip.com";
			HttpRequest(requeststring,"step1");
			HttpRequest("finished.htm","step1");
			return true;
		}
		
		function stepTwo()
		{
			if(verifyStepOne())
			{
			    var radioAnswer = document.getElementsByName("vote");
	            if (radioAnswer[0].checked) {
					document.getElementById("voteval").value = "True";
	            }
				else
				{
					document.getElementById("voteval").value = "False";
				}
				document.getElementById("commentval").value = document.getElementById("Comment").value;
				if(document.getElementById("PostComment").checked)
				{
					document.getElementById("postcommentval").value = "True";
				}
				else {document.getElementById("postcommentval").value = "False";}
				HttpRequest("step2.htm", "step1");
			}
			return false;
		}
		
		function findPos(obj) {
			var curleft = curtop = 0;
			if (obj.offsetParent) {
				do {
					curleft += obj.offsetLeft;
					curtop += obj.offsetTop;
				} while (obj = obj.offsetParent);
			}
				return [curleft,curtop];
		}
	
		function popupGo(clicksource, target)
		{
			//overrode to default position on the getlifted div
			var coords = findPos(document.getElementById(clicksource));
			var x = coords[0];
			var y = coords[1];
			document.getElementById(target).style.pixelLeft = x - 10;
			document.getElementById(target).style.pixelTop = y-70;
			document.getElementById(target).style.left = (x - 10) + "px";
			document.getElementById(target).style.top = (y-70) + "px";
			document.getElementById(target).style.visibility = "visible";
		}
		function closeMe(x)
		{
			document.getElementById(x).style.visibility = "hidden";
		}
		
		function onABCommComplete() {
		  // OPTIONAL: do something here after the new data has been populated in your text area
		}
		
				function verifyPW()
		{
			if(document.getElementById("tribepw").value.toLowerCase() == "party")
			{
				return true;
			}
			else
			{
				document.getElementById("status").innerHTML = "Incorrect password";
				document.getElementById("status").style.visibility = "visible";
				return false;
			}
			return false;
		}
		
		function sendEmail()
		{
			var go = true;
			if(document.getElementById("emailfrom").value == "" || document.getElementById("emailsubject").value == "" || document.getElementById("emailmessage").value == "" || document.getElementById("emaillist").value == "")
			{
				alert("You must fill out every field!");
				go = false;
			}
			else if(!/^([a-zA-Z0-9_\-\.]+)@[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]$/.test(document.getElementById("emailfrom").value))
			{
				alert("You must input your email address in the common format xxx@yyy.zzz!");
				go = false;
			}
			else if(!/([a-zA-Z0-9_\-\.]+)@[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]/.test(document.getElementById("emaillist").value))
			{
				alert("You must input an email address to send to in the common format xxx@yyy.zzz!");
				go = false;
			}
			
			if(go == true)
			{
				var requeststring = "sendemails.php?email="+document.getElementById("emailfrom").value+"&to=" +document.getElementById("emaillist").value+ "&subject="+document.getElementById("emailsubject").value+"&message="+document.getElementById("emailmessage").value;
				HttpRequest(requeststring,"emailPopup");		
			}
		}
		
		function focused(origin, text)
		{
			if(origin.value == text)
			{
				origin.value = "";
				origin.style.color = "Black";
				origin.style.fontStyle = "normal";
			}
		}
		function blurred(origin, text)
		{
			if(origin.value == "")
			{
				origin.value = text;
				origin.style.color = "Grey";
				origin.style.fontStyle = "italic";
			}
		}
		
		function verifyNewMember(newnamebox, newemailbox, updatewhichdiv)
		{
			if(document.getElementById(newemailbox).value == "" || document.getElementById(newnamebox).value == "" || document.getElementById(newemailbox).value == "[Primary E-Mail]" || document.getElementById(newnamebox).value == "[Your Name]")
			{
				alert("You must give us both your Name and Email!");
			}
			else if(!/^([a-zA-Z0-9_\-\.]+)@[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]$/.test(document.getElementById(newemailbox).value))
			{
				alert("You must give us a valid E-mail address!");
			}
			else{//Must be good
				HttpRequest("/listjoin.php?name="+document.getElementById(newnamebox).value+"&email="+document.getElementById(newemailbox).value+"&source=homepage-pw",updatewhichdiv);
			}
			return false;
		}
	//-->
    </script>
	
    <link rel="stylesheet" type="text/css" href="/menu_style.css" />
</head>
<body>
	<?php include_once("googleanalytics.php") ?>
	<form action="legalizationdebate.php" method="post" id="theform" onSubmit="return verifyPW();">
    <div id="art-page-background-gradient" style="top:0;"></div>
    <div class="art-header">
	
		<a href="index.php"><div class="art-header-jpeg">
			<?php //readfile("menu.htm"); ?>
		</div></a>
          
    </div>
    <!--div class="art-header-jpeg-alt" style=height:210px;">
				<div align="center" style="vertical-align:top;text-align:center;width:100%">
					<div style="margin-top:-90px;z-index:2;position:relative;">
						<a style="z-index:2;" href="index.php" ><img src="/images/logo.png" alt="Logo" style="z-index:2;position:relative;" /></a>
					</div>
					
				</div>
    </div-->
    <div id="art-main">
        <div class="art-sheet">
            <div class="art-sheet-tl"></div>
            <div class="art-sheet-tr"></div>
            <div class="art-sheet-bl"></div>
            <div class="art-sheet-br"></div>
            <div class="art-sheet-tc"></div>
            <div class="art-sheet-bc"></div>
            <div class="art-sheet-cl"></div>
            <div class="art-sheet-cr"></div>
            <div class="art-sheet-cc"></div>
            <div class="art-sheet-body">
                <div class="art-content-layout">
                    <div class="art-content-layout-row">
                        <div class="art-layout-cell art-content">
                            <div class="art-post">
                                <div class="art-post-body" style="padding-left:0px;padding-top:0px;">
                                    <div class="art-post-inner art-article">
                                        <div class="art-content-layout overview-table">
                                           	<div class="art-content-layout-row">
                                                    <div class="art-layout-cell">
                                                        <div class="overview-table-inner" >
                                                            <div style="float:left;width:500px;text-align:left;">
                                                                <h1>Should&nbsp;Marijuana&nbsp;Be&nbsp;Legal?</h1>
                                                                        <iframe title="YouTube video player" class="youtube-player" width="480" height="340" src="http://www.youtube.com/embed/eLunLhIXRKE?autoplay=1&amp;hl=en&amp;fs=1" frameborder="0"></iframe>

                                                                <h3><a name="otherOps"></a><img src="images/gr_arrow.gif" alt="" class="image" /> Other's Opinions</h3>
                                                                            <?php
                                                                        $sql  = "SELECT COUNT(ID) AS Total, Vote FROM vote_tracking GROUP BY Vote";
                                                                        $results = (mysql_query($sql));
                                                                        $yes=0;
                                                                        $no=0;
                                                                        if($results)
                                                                        {
                                                                            while ($voteSum = mysql_fetch_assoc($results)) {
                                                                                if($voteSum["Vote"] == 1) $yes=$voteSum["Total"];
                                                                                else $no=$voteSum["Total"];
                                                                            }
                                                                        }

                                                                                    $sql  = "SELECT COUNT(ID) AS Total FROM vote_tracking WHERE PostComment=1";
                                                                        $results = mysql_query($sql);
                                                                                    if($results)
                                                                                    {
                                                                                            if ($theSum = mysql_fetch_assoc($results)) {
                                                                                                    $total_comments = $theSum['Total'];
                                                                                                    $num_pages = ceil($total_comments/$pageination_amt);
                                                                                            }
                                                                                    }


                                                                $sql  = "SELECT FirstName,LastName,Vote,Comment,po_name,alpha_code FROM vote_tracking VT LEFT OUTER JOIN us_zipcodes UZ ON UZ.zip_code=VT.zip LEFT OUTER JOIN us_states US ON US.state_num=UZ.state WHERE PostComment=1 ORDER BY ID DESC LIMIT ".(($page_num * $pageination_amt)-10).", ".$pageination_amt;
                                                                                    $results = (mysql_query($sql));
                                                                if ($results && $con)
                                                                {
                                                                    while ($aVote = mysql_fetch_assoc($results)) {
                                                                        echo "<p style=\"font-size:10pt;margin-left:4px;margin-right:3px;\"><b>";
                                                                        if($aVote['Vote'] == 1)
                                                                            echo "<img src='/images/greencheck.png' class='image' />&nbsp;<font style='color:DarkGreen;'>Yes</font> - ";
                                                                        else
                                                                            echo "<img src='/images/redx.png' class='image' />&nbsp;<font style='color:Red;'>No</font> - ";
                                                                        echo  $aVote['FirstName'] . " " . $aVote['LastName'];

                                                                                                    if($aVote['po_name'] != "")
                                                                                                            echo "</b> from " . ucwords(strtolower($aVote['po_name'])) . ", " . $aVote['alpha_code'] . " <br/><i>" . $aVote['Comment'] . "</i></p>";
                                                                                                    else
                                                                            echo "</b><br/><i>" . $aVote['Comment'] . "</i></p>";
                                                                    }
                                                                                    }
                                                                                    else echo mysql_error();

                                                                                    echo "<p style='font-size:10pt;text-align:center;white-space:nowrap;'>View Page&nbsp;";
                                                                                    for($counter = 1; $counter <= $num_pages; $counter ++)
                                                                                    {
                                                                                            //First, only show page links plus and minus 3 of the current location
                                                                                            if(($counter < $page_num + $paging_breadth) && ($counter > $page_num - $paging_breadth))
                                                                                            {
                                                                                                    if($counter == $page_num)
                                                                                                    {
                                                                                                            echo $counter . " ";
                                                                                                    }
                                                                                                    else{
                                                                                                            echo "<a style='font-size:10pt;' href='legalizationdebate.php?p=".$counter."#otherOps'>".$counter."</a> ";
                                                                                                    }
                                                                                            }
                                                                                    }
                                                                                    echo "<br/> ".$total_comments." Comments</p>";
                                                            ?>

                                                            </div>
                                                            <div style="float:left;width:340px;vertical-align:top;">
                                                            <h2 id="test" class="green"><?php echo $yes ?> People have said YES!</h2>
                                                            <h2 class="red"><?php echo $no ?>  People have said NO.</h2>
                                                                <div id="step1" style="z-index:0;width:340px;">
                                                                            <?php
                                                                                    if($already_voted == "true")
                                                                                    {
                                                                                            include("finished.htm");
                                                                                    }
                                                                                    else{
                                                                            ?>
                                                                            <h3><img src='images/gr_arrow.gif' alt="" class="image" />Do you think Marijuana should be legal?
                                                                                                                                            <br/>&nbsp;&nbsp;<font style="color:Red;">Enter to win an <b><u>iPAD</u></b>!  Just vote & share!</font>
                                                                                                                                            </h3>
                                                                            <p style="text-align:center;font-weight:bold;font-size: 14pt;margin-top:-7px;">
                                                                                                    <font class='green'>Yes</font> <input type="radio" name="vote" id="YesVote" value="True" />
                                                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                                    <font class='red'>No</font> <input type="radio" value="False" name="vote" id="NoVote" />
                                                                                            </p>
                                                                                    <p>We would like to hear your opinion.  Tell us why!</p>
                                                                            <textarea rows="7" cols="100" style="width:98%;" name="Comment" id="Comment"></textarea>
                                                                                            <div style="float:left;padding-top:4px;">
                                                                                                    <input type="checkbox" id="PostComment" name="PostComment" value="True" style="font-size:10pt;" checked="checked" />
                                                                                 Post my opinion below (you can remain anonymous)!
                                                                                            </div>
                                                                            <p style="text-align:center;padding-top:35px;">
                                                                                                    <input type="image" src="images/buttonsubmit.png" onclick="return stepTwo();" alt="Cast your vote!" style="cursor:pointer;"/>
                                                                            </p>
                                                                                                                                            <?php
                                                                                                                                            }
                                                                                                                                            ?>
                                                                        </div>

                                                                <div style="margin-left:4px;margin-right:8px;">
                                                                    <h3><img src='images/gr_arrow.gif' alt="" class="image" /> Common Opinions</h3>
                                                                    <p>
                                                                       <img src='images/greencheck.png' alt="Yes" class="image" />&nbsp;<b><font style='color:DarkGreen;'>Yes</font></b>
                                                                        <br />
                                                                        Prohibition does not deter usage, and only supports organized crime!
                                                                    </p>
                                                                                    <p>
                                                                        <img src='images/redx.png' alt="No" class="image" />&nbsp;<b><font style='color:Red;'>No</font></b>
                                                                        <br />
                                                                        Legalizing Marijuana could lead to an increase in usage, especially amongst teens! This could lead them to harder, more dangerous drugs!
                                                                    </p>
                                                                    <p>
                                                                        <img src='images/greencheck.png' alt="Yes" class="image" />&nbsp;<b><font style='color:DarkGreen;'>Yes</font></b>
                                                                        <br />
                                                                        Make marijuana safer, but less available to minors by regulating the market, just like alcohol!
                                                                    </p>
                                                                    <p>
                                                                        <img src="/images/greencheck.png" class="image"  />&nbsp;<b><font style='color:DarkGreen;'>Yes</font></b>
                                                                        <br />
                                                                        Ecnomists say it could save America an estimated 7.7 Billion in enforcement costs, while generating up to an estimated 6.2 Billion in Federal Tax Revenue. Totalling a 13.9 swing.
                                                                    </p>
                                                                    <p>
                                                                        <img src="/images/redx.png" class="image"  />&nbsp;<b><font style='color:Red;'>No</font></b>
                                                                        <br />
                                                                        Governer Schwarzenegger just de-criminalized small-time marijuana offenses, why vote yes?  We probably won't save as much money.
                                                                    </p>
                                                                                    <p>
                                                                        <img src="/images/greencheck.png" class="image"  />&nbsp;<b><font style='color:DarkGreen;'>Yes</font></b>
                                                                        <br />
                                                                        Nearly one-million American Citizens were arrested last year for Marijuana charges.  America imprisons more of its own citizens than any country in the world.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                    </div>
                                                    </div>
                                               	</div><!-- end cell -->
                                            </div><!-- end table -->

                                            <div class="cleared"></div>
                                        </div>

                            		    <div class="cleared"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="cleared"></div>
                    <div class="art-footer">
                        <div class="art-footer-t"></div>
                        <div class="art-footer-l"></div>
                        <div class="art-footer-b"></div>
                        <div class="art-footer-r"></div>
                        <div class="art-footer-body">
                            <div class="art-footer-text">
								<?php readfile("footer.htm"); ?>  
                            </div>
                    		<div class="cleared"></div>
                        </div>
                    </div>
            		<div class="cleared"></div>

            </div>
            <div class="cleared"></div>
            <p class="art-page-footer"></p>
        </div>
	<div id="govPopup" style="visibility:hidden;z-index:20;position:absolute;width:342px;background-color:White;padding:5px;border-width:2;border-color:Black;border-style:solid;">
		<p style="vertical-align:middle;color:Black;">
			Based upon zip codes, we will collect all related opinions and mail them to the designated Representative for that area every few weeks.<br/>
		</p>
		<div style="width:342px;cursor:pointer;" onclick="closeMe('govPopup');">
			<p style="text-align:center;color:Blue;text-decoration:underline;">Close Window</p>
		</div>
	</div>
	<div id="roachclipPopup" style="visibility:hidden;z-index:20;position:absolute;width:342px;background-color:White;padding:5px;border-width:2;border-color:Black;border-style:solid;">
		<p style="vertical-align:middle;color:Black;">
			Roachclip.com is an up-and-comming portal that is bringing only the most interesting, sub-culture related art, music, events, wares, activism, information and people under one flag.  Become a part of our community to trade information on the coolest stuff found in the wide-world! (We do not share your information, we just want to hear from you!)
			<br/>
		</p>
		<div style="width:342px;cursor:pointer;" onclick="closeMe('roachclipPopup');">
			<p style="text-align:center;color:Blue;text-decoration:underline;">Close Window</p>
		</div>
	</div>
	<div id="getLiftedPopup" style="visibility:hidden;z-index:20;position:absolute;width:342px;background-color:White;padding:5px;border-width:2;border-color:Black;border-style:solid;">
		<p style="vertical-align:middle;color:Black;">
			Get a free 'I Get Lifted' Song Download by the Green Brothers.  No strings or BS attached!
		</p>
		<div style="width:342px;cursor:pointer;" onclick="closeMe('getLiftedPopup');">
			<p style="text-align:center;color:Blue;text-decoration:underline;">Close Window</p>
		</div>
	</div>
	<div id="emailPopup" style="visibility:hidden;z-index:20;position:absolute;width:342px;background-color:White;padding:5px;border-width:2;border-color:Black;border-style:solid;">
		<p style="vertical-align:middle;color:Black;">
			Share this with your friends!<br/>
			From:<br/>
			<input type="text" columns="60" id="emailfrom" name="emailfrom" style="width:300px;" value="" />
			<br/>
			Subject:<br/>
			<input type="text" columns="60" id="emailsubject" name="emailsubject" style="width:300px;" value="Marijuana Legalization debate at Roachclip.com" />
			<br/>
			Message:<br/>
			<textarea columns="60" id="emailmessage" name="emailmessage" style="width:300px;" rows="3">Hey!  I found this cool site hosting a debate about the legalization of marijuana.  They're collecting opinions, check it out at www.roachclip.com
			</textarea>
			<br/>
			To:
			<a href="#" onclick="showPlaxoABChooser('emaillist', '/emailinvite.html'); return false"><img src="http://www.plaxo.com/images/abc/buttons/add_button.gif" alt="Add from my address book" /></a>
			<br/>
			<textarea id="emaillist" name="emaillist" columns="40" rows="3" style="width:300px;"></textarea>
			<br/>
		</p>
		<div style="width:342px;text-align:center;" >
			<input type="button" id="emailbutton" name="emailbutton" value="Send Emails" onclick="sendEmail()" />
		</div>
		<div style="width:342px;cursor:pointer;" onclick="closeMe('emailPopup');">
			<p style="text-align:center;color:Blue;text-decoration:underline;">Close Window</p>
		</div>
	</div>
	<input type="hidden" id="voteval" name="voteval" />
	<input type="hidden" id="commentval" name="commentval" />
	<input type="hidden" id="postcommentval" name="postcommentval" />
	</form>
</body>
</html>
