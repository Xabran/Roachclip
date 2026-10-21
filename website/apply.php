<?php
require_once("constants.php");
require_once("helpers.php");
require_once 'classes/mailchimp/MCAPI.class.php';
require_once 'classes/mailchimp/config.inc.php'; //contains apikey
$con = mysql_connect($dbhost, $dbusr, $dbpw);
mysql_select_db($targetDB);
$submitted = false;
if(isset($_REQUEST["Email"]))
{
    $api = new MCAPI($apikey);
    $sql = "INSERT INTO applicants (Name,HowHear,Skills,Serve,Email,ListSignup,Submitted) VALUES ('".prepareforDB($_REQUEST["Name"])."','";
    $sql .= prepareforDB($_REQUEST["HowHear"])."','";
    $sql .= prepareforDB($_REQUEST["Skills"])."','";
    $sql .= prepareforDB($_REQUEST["Serve"])."','";
    $sql .= prepareforDB($_REQUEST["Email"])."','";
    $sql .= prepareforDB($_REQUEST["ListSignup"])."',NOW());";
    $results = (mysql_query($sql));

    if($_REQUEST["ListSignup"] == "1")
    {
        $merge_vars = array('NAME'=>$_REQUEST["Name"], 'HOWHEAR'=>$_REQUEST["HowHear"], 'SKILLS'=>$_REQUEST["Skills"], 'SERVE'=>$_REQUEST["Serve"]);
        $retval = $api->listSubscribe( $applicantListId, $_REQUEST["Email"], $merge_vars, "html", false );

//        if ($api->errorCode){
//                echo "Unable to load listSubscribe()!\n";
//                echo "\tCode=".$api->errorCode."\n";
//                echo "\tMsg=".$api->errorMessage."\n";
//        } else {
//            echo "Returned: ".$retval."\n";
//        }
    }
    $submitted = true;
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
                if(document.getElementById("Name").value == "" || document.getElementById("HowHear").value == "" || document.getElementById("Skills").value == "" || document.getElementById("Serve").value == "" || document.getElementById("Email").value == ""){
                    alert("You must fill out all the fields!"); return false;
                }
                if(document.getElementById("HowHear").value.length > 100){
                    alert("You cannot exceed 100 characters when describing how you heard about us."); return false;
                }
                if(document.getElementById("Skills").value.length > 500){
                    alert("You cannot exceed 500 characters when describing how you heard about us."); return false;
                }
                if(document.getElementById("Serve").value.length > 500){
                    alert("You cannot exceed 500 characters when describing how you heard about us."); return false;
                }

                var emailOk = /^([a-zA-Z0-9_\-\.]+)@[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]$/.test(document.getElementById("Email").value);
                if(!emailOk)
                {
                       alert("You must input a valid Email address! (ex. xxx@yyy.com)"); return false;
                }
                return true;
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
        <form action="apply.php" method="post" id="theform" onsubmit="return verifyInfo();">
            <div style="width:100%;text-align:center;">
                <div style="background-color:#669a41;border-bottom: 2px solid #EAEAEA;">
                    <a href="http://www.roachclip.com"><img src="/public/admin/se-logo.png" style="border:0;" alt="logo"/></a>

                </div>
                <div style="width:100%;background-image: url('images/page_g_short.jpg'); background-position: top left;float:center;height:450px;">
                    <div style="width:20%;float:left;">&nbsp;</div>
                    <?php
                        if(!$submitted)
                        {
                    ?>
                    <div class="content">
                        <p style="font-style:italic;">
                            Were you given the secret password?  If so <a href="http://www.roachclip.com/opensesame.php">click here</a> to be granted access.
                        </p>
                        <p>
                            Is this your first time visiting our humble community?  Well then, you are probably wondering...
                        </p>
                        <div style="text-align:left;margin: 10px 50px 10px 50px">
                            <h2>
                                What the heck is the Festival-365 Project all about anyway?
                            </h2>
                            <p>
                                Roachclip.com's Festival-365 Project is a true community born from the festival culture.
                                <br/><br/>
                                We embrace expression and cultivate collaboration.  Working together, we can each make this a better place for us all.  You could call our project a little overly idealistic, but we'd frankly just smile.
                                <br/><br/>
                                We are building the largest collection of festival information & knowledge with the help of you, the one who has been or will be going there.
                                <br/><br/>
                                This is OUR website: free of advertising and devoid of censorship.  Roachclip.com is still in our infancy and we have no plans for rapid growth.  We would prefer to keep this place special so that everyone has the ability to make an impact.
                                <br/><br/>
                                We are a very diverse group and we'd like to think ourselves quite a creative bunch.  Being the online home of the festival scene, many of us are naturally artists, musicians and vendors.  Regardless of individual passions, all of us share a love of good times and good people.
                                <br/><br/>
                                Most of us choose to go by a secret alias within the community.  This only further enhances the flow of unrestricted genius as well as exacerbates all-around shenanigans.
                                <br/><br/>
                                In other words, Roachclip.com's Festival-365 Project is the online manifestation of the growing festival subculture.
                            </p>
                            <h2>
                                Sounds sweet, so how do I become a member?
                            </h2>
                            <p>
                                Well my friend, Roachclip.com is an invite only community.  Why, you might ask?
                                <br/><br/>
                                By remaining invite only, our members are able to freely express themselves without worry of being judged or having professional repercussions.   It allows us to protect the sanctity of what we have all worked so hard to build.
                                <br/><br/>
                                While you may not yet be a member, the fact that you have read this far leads us to believe that you likely jive with our community's ideals.  The community can only gain from the addition of amazing people.
                                <br/><br/>
                                So here's the scoop.  There are 3 ways to get in...
                                <br/><br/>
                                <b>Option 1</b> - Get an official invite email from a friend who is already a member.  Or...
                                <br/><br/>
                                <b>Option 2</b> - Meet one of our fine members in person and get the secret password.  Or...
                                <br/><br/>
                                <b>Option 3</b> - Persuade our members below as to why you would be a great addition to the family.
                                <br/><br/>
                                The last option is the online version of showing up late to the festival without a ticket and trying to hop the fence.  You can still get in, but you must earn your way.
                                <br/><br/>
                                Our members will review your submission below and will send out invites to promising applicants.  Rest assured that we never, ever discriminate - that's not our style.
                                <br/><br/>
                                The Roachclip.com community is free and we don't have advertising.  We have nothing to gain from members that don't care and don't contribute.  For those that do understand the culture, you will find the community to be the closest thing to festival life online.
                                <br/><br/>
                                So, what we would love to know is...
                            </p>
                            <h2>Why do you want to join and why would you be good addition to the community?</h2>
                            <p>
                                To request an invite please complete the survey below.  We truly look forward to learning about you and hearing what you have to say!
                                <br/><br/>
                                <b>Your name or alias?</b><br/>
                                <input type="text" id="Name" name="Name" maxlength="50" width="100px" value="" /><br/>
                                <b>How did you hear about Roachclip.com? (100 characters or less)</b><br/>
                                <textarea id="HowHear" name="HowHear" cols="50" rows="4"></textarea>
                                <br/>
                                <b>What skills or interests do you have that you would like to contribute to the community? (Ex. Artist, Musician, Crafter, Publication, Business)(500 characters or less)</b><br/>
                                <textarea id="Skills" name="Skills" cols="50" rows="5"></textarea>
                                <br/>
                                <b>How would you like Roachclip.com to serve/cultivate/interact with you? (500 characters or less)</b><br/>
                                <textarea id="Serve" name="Serve" cols="50" rows="5"></textarea>
                                <br/>
                                <b>What is your best E-Mail address?</b><br/>
                                <input type="text" id="Email" name="Email" maxlength="50" width="100px" value="" /><br/>
                                <input type="checkbox" id="ListSignup" name="ListSignup" value="1" checked /> Yes, I would like the Weekly Buzz Newsletter
                                <br/>
                                To stay in touch we would love to send you a copy of the Weekly Buzz.  You can choose to discontinue our world-famous, super informative, gut-busting hysterical and down right good looking email newsletters at any time with the simple click of a button (but frankly that would be silly).  We've got a feeling you are gonna love what our community has to share.  Trust us, it's the good stuff.
                                <br/>
                                <div style="text-align:center;width:100%;">
                                    <input type="image" src="images/submitApp.jpg" />
 
                                </div>
                            </p>
                        </div> 

                    </div>
                    <?php
                       }
                       else
                       {
                    ?>
                    <div class="content">
                        <h2>Thanks!</h2>
                        <p>
                            We love hearing from new people, and we're always interesting in growing.  One amazing individual at a time.  You may hear from us in the near future.  Regardless, we hope to see you out amongst the festivities!
                            <br/>
                            <br/>
                            <a href="http://www.roachclip.com" >Back Home</a>
                        </p>
                    </div>
                    <?php
                       }
                    ?>
                    <div style="width:20%;float:left;">&nbsp;</div>

                </div
            </div>

        </form>
    </body>
</html>
