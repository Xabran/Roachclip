<?php
class TeeSites
{
    public $SiteList = array (
        "6dollarshirts",
        "bustedtees",
        "crazydogtshirts",
        "cracksmokingshirts",
        "foulmouthshirts",
        "ilovewaterloo",
        "loserkids",
        "prankplace",
        "snorgtees",
        "tshirthell",
        "tshirtbordello"
        );
}

function fullAfLink($shirtURL)
{
    if(strpos($shirtURL,"snorgtees"))
    {
        return "http://www.shareasale.com/r.cfm?u=475008&b=175316&m=5993&afftrack=&urllink=".str_replace("http://","",$shirtURL);
    }
    else if(strpos($shirtURL,"crazydogtshirts"))
    {
        return "http://www.shareasale.com/r.cfm?u=475008&b=99261&m=14592&afftrack=&urllink=".str_replace("http://","",$shirtURL);
    }
    else if(strpos($shirtURL,"cracksmokingshirts"))
    {
        return "http://www.shareasale.com/r.cfm?u=475008&b=35502&m=7816&afftrack=&urllink=".str_replace("http://","",$shirtURL);
    }
    else if(strpos($shirtURL,"retroduck"))
    {
        return "http://www.shareasale.com/r.cfm?u=475008&b=35502&m=26168&afftrack=&urllink=".str_replace("http://","",$shirtURL);
    }
    else if(strpos($shirtURL,"anvilgraphics"))
    {
        return "http://www.shareasale.com/r.cfm?u=475008&b=35502&m=30241&afftrack=&urllink=".str_replace("http://","",$shirtURL);
    }
    else if(strpos($shirtURL,"designerteez"))
    {
        return "http://www.shareasale.com/r.cfm?u=475008&b=35502&m=23066&afftrack=&urllink=".str_replace("http://","",$shirtURL);
    }
    else if(strpos($shirtURL,"funny-tshirts"))
    {
        return "http://www.shareasale.com/r.cfm?u=475008&b=35502&m=8239&afftrack=&urllink=".str_replace("http://","",$shirtURL);
    }
    else if(strpos($shirtURL,"headlineshirts"))
    {
        return "http://www.shareasale.com/r.cfm?u=475008&b=35502&m=23066&afftrack=&urllink=".str_replace("http://","",$shirtURL);
    }
    else if(strpos($shirtURL,"lapopart"))
    {
        return "http://www.shareasale.com/r.cfm?u=475008&b=35502&m=27696&afftrack=&urllink=".str_replace("http://","",$shirtURL);
    }
    else if(strpos($shirtURL,"spreadshirt"))
    {
        return "http://www.shareasale.com/r.cfm?u=475008&b=35502&m=7127&afftrack=&urllink=".str_replace("http://","",$shirtURL);
    }
    else if(strpos($shirtURL,"tilteed"))
    {
        return "http://www.shareasale.com/r.cfm?u=475008&b=35502&m=23289&afftrack=&urllink=".str_replace("http://","",$shirtURL);
    }
    else if(strpos($shirtURL,"t-shirthumor"))
    {
        return "http://www.shareasale.com/r.cfm?u=475008&b=35502&m=4492&afftrack=&urllink=".str_replace("http://","",$shirtURL);
    }
    else if(strpos($shirtURL,"uneetee"))
    {
        return "http://www.shareasale.com/r.cfm?u=475008&b=35502&m=12558&afftrack=&urllink=".str_replace("http://","",$shirtURL);
    }
    else if(strpos($shirtURL,"foulmouthshirts"))
    {
        return "http://www.foulmouthshirts.com/store/?AFID=18732&redirect=".str_replace("http://","",$shirtURL);
    }
    else if(strpos($shirtURL,"tshirthell"))
    {
        return "http://tshirthell.com/store/clicks.php?partner=Roachclip&page=".str_replace("www.","",$shirtURL);
    }
    else if(strpos($shirtURL,"tshirtbordello"))
    {
        return "http://scripts.affiliatefuture.com/AFClick.asp?affiliateID=240799&merchantID=3315&programmeID=8562&mediaID=0&tracking=&url=".str_replace("http://","",$shirtURL);
    }
    else if(strpos($shirtURL,"ilovewaterloo"))
    {
        return "http://scripts.affiliatefuture.com/AFClick.asp?affiliateID=240799&merchantID=4486&programmeID=11425&mediaID=0&tracking=&url=".str_replace("http://","",$shirtURL);
    }
    else if(strpos($shirtURL,"bustedtees"))
    {
        return "http://www.tkqlhce.com/click-4220791-10406574?url=".str_replace("http://","",$shirtURL);
    }
    else if(strpos($shirtURL,"6dollarshirts"))
    {
        return "http://www.dpbolvw.net/click-4220791-10650221?url=".str_replace("http://","",$shirtURL);
    }
    else if(strpos($shirtURL,"loserkids"))
    {
        return "http://www.tkqlhce.com/click-4220791-10798448?url=".str_replace("http://","",$shirtURL);
    }
    else if(strpos($shirtURL,"prankplace"))
    {
        return "http://www.dpbolvw.net/click-4220791-10486006?url=".str_replace("http://","",$shirtURL);
    }
    else if(strpos($shirtURL,"bant-shirts"))
    {
        return str_replace("http://","",$shirtURL);
    }
    else
    {
        return null;
    }
}