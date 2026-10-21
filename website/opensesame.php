<?php
require_once("constants.php");

if(isset($_REQUEST["password"]))
{
    if($_REQUEST["password"] == $communityPW || $_REQUEST["password"] == $communityPWalt)
    {
        header( 'Location: http://www.roachclip.com/signup' ) ;
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US" xml:lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <meta property="og:title" content="Roachclip.com | Everything Cool" />
        <meta property="og:description" content="Roachclip.com is a community thats built for you, the festival goer, the musician, the artist, the crafter.  Here we have one goal and one goal only – Bring the coolest people together with the craziest stuff on earth." />
        <meta property="og:image" content="http://www.roachclip.com/images/logo-thumbnail.png" />
        <title>Roachclip.com</title>
        <script type="text/javascript">
            function verifyInfo()
            {
                if(document.getElementById("password").value == ""){
                    alert("You must type in a password!"); return false;
                }
            }
        </script>
        <style type="text/css">
            body
            {
                margin: 0 auto;
                padding: 0;
                background-color: #669a41;
                background-image: url('images/page_t.jpg');
                background-repeat: repeat;
                background-attachment: scroll;
                background-position: top left;
                font-family: segoe ui, Helvetica, Ariel, Sans-Serif;
                font-size: 12px;
            }
            .content
            {
                -webkit-border-radius: 3px 3px;
                border-radius: 3px 3px ;
                border: 2px solid #EAEAEA;
                width:60%;
                background-color:white;
                float:left;
            }
}
        </style>
    </head>
    <body>
<?php include_once("googleanalytics.php") ?>
        <form action="opensesame.php" method="post" id="theform" onsubmit="return verifyInfo();">
            <div style="width:100%;text-align:center;">
                <div style="background-color:#669a41;border-bottom: 2px solid #EAEAEA;">
                    <a href="http://www.roachclip.com"><img src="/public/admin/se-logo.png" style="border:0;" alt="logo"/></a>

                </div>
                <div style="width:100%;background-image: url('images/page_g_short.jpg'); background-position: top left;float:center;height:450px;">
                    <div style="width:20%;float:left;">&nbsp;</div>
                    <div class="content">
                        <h2>
                            None shall pass!
                        </h2>
                        <p>... unless of course you've been given the secret password to get into our awesome community: <b>The Festival-365 Project</b></p>
                        <p>If so, type it in below and press enter.  Those with the right password will be taken to an account creation screen.</p>
                        <br/>
                        <p>
                            <h3><i>Enter The Secret Password:</i></h3>
                            <input type="password" name="password" id="password" maxlength="10" />
                            <br/>
                            <input type="submit" value="Enter!" />

                        </p>
                    </div>
                    <div style="width:20%;float:left;">&nbsp;</div>

                </div
            </div>

        </form>
    </body>
</html>
