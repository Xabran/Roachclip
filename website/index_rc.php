<?php
    //require("constants.php");
    //$con = mysql_connect($dbhost, $dbusr, $dbpw);
	//mysql_select_db($targetDB);
	
	$PWVal = "Secret Password";
	
	if(isset($_REQUEST["tribepw"]))
	{
		if(strtoupper($_REQUEST["tribepw"]) == "PARTY")
		{//Set cookie for a month
			setcookie("roachclip_pw", "Hello from Roachclip.com!", time()+2551443840);
			header( "Location: ".$root."/tribe/" );
		}
	}
	
	if(isset($_COOKIE["roachclip_pw"]))
	{
            $PWVal = "PARTY";
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
	<meta property="og:description" content="Roachclip.com is a community thats built for you, the partier, the event goer, the musician, the artist, the crafter.  Here we have one goal and one goal only – Bring the coolest people together with the craziest stuff on earth." />
	<meta property="og:image" content="http://www.roachclip.com/images/logo-thumbnail.png" />
    <title>Roachclip.com | Everything Cool</title>

    <link rel="stylesheet" href="style.css" type="text/css" media="screen" />
    <!--[if IE 6]><link rel="stylesheet" href="style.ie6.css" type="text/css" media="screen" /><![endif]-->
    <!--[if IE 7]><link rel="stylesheet" href="style.ie7.css" type="text/css" media="screen" /><![endif]-->
   <script type="text/javascript" src="ajax.js"></script>
   <script type="text/javascript" src="/scripts/partyScript.js"></script>
    <link rel="stylesheet" type="text/css" href="/menu_style.css" />
	
	<script type="text/javascript" >
		function setup()
		{
			menuSummon(document.getElementById("menu_welcome"),'/ajaxsrc/welcome.php');
			
		}
		function verifyPW()
		{
			if(document.getElementById("tribepw").value.toUpperCase() == "PARTY")
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
		function menuSummon(originator, target)
		{
			if(target != null)
			{
				HttpRequest(target,"contentDisplay");
			}
                        document.getElementById("contentDisplay").scrollTop = 0;
			document.getElementById("menu_welcome").className = "vert-bubblemenu-off";
                        document.getElementById("menu_about").className = "vert-bubblemenu-off";
			document.getElementById("menu_blogs").className = "vert-bubblemenu-off";
			document.getElementById("menu_vids").className = "vert-bubblemenu-off";
                        document.getElementById("menu_rvids").className = "vert-bubblemenu-off";
			document.getElementById("menu_parties").className = "vert-bubblemenu-off";
                        document.getElementById("menu_tees").className = "vert-bubblemenu-off";
                        originator.className = "vert-bubblemenu-on";
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
			if(document.getElementById(newemailbox).value == "" || document.getElementById(newnamebox).value == "" || document.getElementById(newemailbox).value == "Type Primarily Email" || document.getElementById(newnamebox).value == "Enter First Name")
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
		function UPPERCASE(target)
		{
			target.value = target.value.toUpperCase();
		}
	</script>
</head>
<body onload="setup();">
	<?php include_once("googleanalytics.php") ?>
	<form action="index_rc.php" method="post" id="theform" onsubmit="return verifyPW();">
    <div id="art-page-background-gradient"></div>
    <div class="art-header-jpeg-alt">
				<div align="center" style="vertical-align:top;text-align:center;width:100%">
					<div style="z-index:2;position:relative;">
                                            <a style="z-index:2;" href="index.php" ><img src="/images/logowbanner.png" alt="Logo" style="z-index:2;position:relative;" name="toplogo" id="toplogo" /></a>
					</div>
                                        <div style="text-align:center;color:white;z-index:5;width:100%;position:relative;top:-75px;">
                                            <p style="font-size:11pt;text-align:center;font-style:italic;font-size:14pt;">We are currently in<br/>double <b>top-secret</b> mode</p>
                                        </div>
					<div style="float:left;width:40%;top:-80px;z-index:5;position:relative;height:170px;margin-right:-10px;">
						<div style="vertical-align:top;color:white;font-weight:bold;float:right;">
							<p style="text-align:center;width:300px;">
								<font style="font-weight:bold;font-size:12pt;">Come in and join the fun!</font>
								<br/>
								The Roachclip Community is now alive and rocking, currently available by invite only. 
								If a friend hooked you up with the password go ahead and enter it below.
								Then hit the big button and knock 3 times.  
								<br/><br/>
								<input type="text" id="tribepw" name="tribepw" maxlength="15" value="<?php echo $PWVal; ?>" style="width:170px;height:32px;text-align:center;vertical-align:middle;font-size:12pt;font-weight:bold;color:Grey;font-style:italic;" onkeyup="UPPERCASE(this);" onkeydown="UPPERCASE(this);" onfocus="focused(this,'Secret Password');" onblur="blurred(this,'Secret Password');"  />
								<br/>
								<input type="image" src="/images/opensesameo.png" style="border:0px;margin-top:10px;" />
								<br/>
								<font id="status" style="visibility:hidden;color:orange;"></font>
							</p>
						</div>
					</div>
                                        <div style="float:left;width:20%;top:-80px;z-index:5;position:relative;height:170px;">
                                            <img src="/images/orsplit.jpg" height="190px" />
                                        </div>
					<div style="float:left;width:40%;top:-80px;z-index:5;position:relative;height:170px;margin-left:-10px;">
						<div style="vertical-align:top;color:white;font-weight:bold;float:left;">
						<p style="text-align:center;width:300px;">
						<font style="font-weight:bold;font-size:12pt;">Don’t have the secret code?</font>
						<br/>
						Don't worry friend, even though our scientists are still developing our snazzy technology, we won't hold the line for long.	Add your name and email below and you will be one of the first to know when we open the doors.
						<br/> 
						<div id="joinlist">
							<input type="text" id="newname" name="newname" maxlength="20" value="Enter First Name" style="color:Grey;font-style:italic;width:170px;" onfocus="focused(this,'Enter First Name');" onblur="blurred(this,'Enter First Name');" onkeypress="if (event.keyCode == 13) {document.getElementById('newmemberbutton').click(); return false;}" />
							<br/>
							<input type="text" id="newemail" name="newemail" maxlength="30" value="Type Primarily Email" style="color:Grey;font-style:italic;width:170px;" onfocus="focused(this,'Type Primarily Email');" onblur="blurred(this,'Type Primarily Email');" onkeypress="if (event.keyCode == 13) {document.getElementById('newmemberbutton').click(); return false;}" />
							<br/>
							<input type="image" src="/images/getonlisto.png" style="margin-top:8px;" id="newmemberbutton" onclick="return verifyNewMember('newname','newemail','joinlist');" />
							<br/>
                                                        <div style="margin-top:10px;"><font style="font-style:italic;font-size:9pt;font-weight:normal;">We think spam sucks too</font></div>
						</div>

						</p>
						</div>
					</div>

				</div>
				
		<!--div class="art-header">	 ORIGINAL w/ MENU
			<div class="art-header-jpeg">
				<?php //readfile("menu.htm"); ?>  
			</div>
		</div-->
    </div>
    <!--div id="art-main"  style="visibility:hidden;">
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
                                <div class="art-post-body" style="padding: 5px 5px 5px 5px;">
                                    <div class="art-post-inner art-article">
                                        <div class="art-content-layout overview-table">
                                           	<div class="art-content-layout-row">
                                           		<div class="art-layout-cell" style="width:100%;">
                                                    <div class="overview-table-inner" style="margin: 0px 0px 0px 0px;">
                                                                <div style="float:left;width:193px;height:400px;text-align:left;margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;">
                                                                    <img src="images/explore.png" class="image"/>
                                                                    <div class="vert-bubblemenu-off" id="menu_welcome" onclick="menuSummon(this,'/ajaxsrc/welcome.php');">Welcome</div>
                                                                    <div class="vert-bubblemenu-off" id="menu_about" onclick="menuSummon(this,'/ajaxsrc/about.php');" >About Roachclip</div>
                                                                    <div class="vert-bubblemenu-off" id="menu_rvids" onclick="menuSummon(this,'/ajaxsrc/rvideos.php');" >Roachclip Videos</div>
                                                                    <div class="vert-bubblemenu-off" id="menu_blogs" onclick="menuSummon(this,'/ajaxsrc/blogs.php');" >Featured Blogs</div>
                                                                    <div class="vert-bubblemenu-off" id="menu_vids" onclick="menuSummon(this,'/ajaxsrc/videos.php');" >Video Channels</div>
                                                                    <div class="vert-bubblemenu-off" id="menu_parties" onclick="menuSummon(this,'/ajaxsrc/parties.php');" >Party Oracle</div>
                                                                    <div class="vert-bubblemenu-off" id="menu_tees" onclick="menuSummon(this,'/ajaxsrc/tees.php');" >Sweet Tees</div>
                                                                    <a href="legalizationdebate.php"><img src="images/legalizemenu.png" class="image" style="margin-top:20px;margin-left:12px;" /></a>
                                                                </div>
                                                                <div style="float:left;width:2px;vertical-align:top;">
                                                                        <img class="image" src="/images/top-down.png" width="2px" height="375px" alt="or" />
                                                                </div>
                                                                <div id="contentDisplay" style="float:right;overflow:auto;height:550px;width:692px;margin: 0px 0px 0px 4px;-ms-overflow-x: hidden;overflow-x: hidden;">
                                                                    
                                                                </div>
                                                    </div>
                                               	</div>
												<!-- end cell >
                                            </div>
											<!-- end table >

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
								<?//php readfile("footer.htm"); ?>

                            </div>
                    		<div class="cleared"></div>
                        </div>
                    </div>
            		<div class="cleared"></div>
            </div>
            <div class="cleared"></div>
            <p class="art-page-footer"></p>
        </div>
    </div-->
    </form>
    <!--script type="text/javascript" src="http://shots.snap.com/ss/1ad91acdaabee6f8ff9507e9a4ef712c/snap_shots.js"></script-->
</body>
</html>
