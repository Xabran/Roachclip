<?php

/**
 * @author: Oliver Putzer
 * @website: www.phpfoxtools.com 
 * @copyright: 2010
 * 
 */

define('PHPFOX', true);

include ("../include/setting/server.sett.php");
require_once ('./include/db.inc.php');
include ('./include/config.inc.php');

$images = "";
$limit = $_GET['limit'];
//$limit = 5;
//(int) trim($_GET['skip'])

if ($_GET['mode'] == 'photo')
{
    $www = '';

if($_GET['www'] == 1)
{
    $www = 'www.';
}
    
    try
    {
        if($_GET['modeState'] == "view_" || $_GET['modeState'] == "")
        {
        $qPhoto = $dbh->prepare('SELECT * FROM ' . $_CONF['db']['prefix'] .
            'photo WHERE view_id=0 ORDER BY photo_id DESC LIMIT :limit');
        $qPhoto->bindValue(':limit', intval($limit), PDO::PARAM_INT);
        $qPhoto->execute();
        $resultPhoto = $qPhoto->fetchAll();
}
elseif($_GET['modeState'] == "view_featured")
{
        $qPhoto = $dbh->prepare('SELECT * FROM ' . $_CONF['db']['prefix'] .
            'photo WHERE is_featured=1 AND view_id=0 ORDER BY photo_id DESC LIMIT :limit');
        $qPhoto->bindValue(':limit', intval($limit), PDO::PARAM_INT);
        $qPhoto->execute();
        $resultPhoto = $qPhoto->fetchAll();    
}
elseif($_GET['modeState'] == "view_most-viewed")
{
        $qPhoto = $dbh->prepare('SELECT * FROM ' . $_CONF['db']['prefix'] .
            'photo WHERE view_id=0 ORDER BY total_view DESC LIMIT :limit');
        $qPhoto->bindValue(':limit', intval($limit), PDO::PARAM_INT);
        $qPhoto->execute();
        $resultPhoto = $qPhoto->fetchAll();    
}
elseif($_GET['modeState'] == "view_top-rated")
{
        $qPhoto = $dbh->prepare('SELECT * FROM ' . $_CONF['db']['prefix'] .
            'photo WHERE view_id=0 ORDER BY total_rating DESC LIMIT :limit');
        $qPhoto->bindValue(':limit', intval($limit), PDO::PARAM_INT);
        $qPhoto->execute();
        $resultPhoto = $qPhoto->fetchAll();    
}
elseif($_GET['modeState'] == "view_top-battle")
{
        $qPhoto = $dbh->prepare('SELECT * FROM ' . $_CONF['db']['prefix'] .
            'photo WHERE view_id=0 ORDER BY total_battle DESC LIMIT :limit');
        $qPhoto->bindValue(':limit', intval($limit), PDO::PARAM_INT);
        $qPhoto->execute();
        $resultPhoto = $qPhoto->fetchAll();    
}
elseif($_GET['modeState'] == "view_approval")
{
        $qPhoto = $dbh->prepare('SELECT * FROM ' . $_CONF['db']['prefix'] .
            'photo WHERE view_id=1 ORDER BY photo_id DESC LIMIT :limit');
        $qPhoto->bindValue(':limit', intval($limit), PDO::PARAM_INT);
        $qPhoto->execute();
        $resultPhoto = $qPhoto->fetchAll();    
}
else
{
    die("View not defined");
}

        foreach ($resultPhoto as $rowPhoto)
        {
            try
            {
                $qUser = $dbh->prepare('SELECT * FROM ' . $_CONF['db']['prefix'] .
                    'user WHERE user_id=:user_id');
                $qUser->bindParam(':user_id', $rowPhoto['user_id'], PDO::PARAM_INT);
                $qUser->execute();
                $resultUser = $qUser->fetchAll();

                foreach ($resultUser as $rowUser)
                {
                    $userName = $rowUser['user_name'];
                }
            }
            catch (PDOException $e)
            {
                die('666'); //SQL Exception
            }

            if ($rowPhoto['album_id'] != 0)
            {
                try
                {
                    $qAlbum = $dbh->prepare('SELECT * FROM ' . $_CONF['db']['prefix'] .
                        'photo_album WHERE album_id=:album_id');
                    $qAlbum->bindParam(':album_id', $rowPhoto['album_id'], PDO::PARAM_INT);
                    $qAlbum->execute();
                    $resultAlbum = $qAlbum->fetchAll();

                    $albumPrivacy = 0;
                    foreach ($resultAlbum as $rowAlbum)
                    {
                        $albumPrivacy = $rowAlbum['privacy'];
			$albumPassword = $rowAlbum['password'];
                        $albumURL = $rowAlbum['name_url'] . '/';
                    }
                }
                catch (PDOException $e)
                {
                    echo $e->getMessage();
                }
            }
            if ($albumPrivacy == 0 && strlen($albumPassword) == 0)
            {
                $destination_thumb = str_replace("%s", "_75", $rowPhoto['destination']);
                $destination_large = str_replace("%s", "_240", $rowPhoto['destination']);


                if ($shortURL == 0)
                {
                    if (strlen($albumURL) > 0)
                    {
                        $url = 'http://' .$www . $_SERVER['SERVER_NAME'] . '/'.$subDir.'index.php?do=/' . $userName .
                            '/photo/' . $albumURL . $rowPhoto['title_url'];
                    } else
                    {
                        $url = 'http://' .$www  . $_SERVER['SERVER_NAME'] . '/'.$subDir.'index.php?do=/' . $userName .
                            '/photo/view/' . $rowPhoto['title_url'];
                    }
                } else
                {
                    if (strlen($albumURL) > 0)
                    {
                        $url = 'http://' .$www . $_SERVER['SERVER_NAME'] . '/'. $subDir . $userName .
                            '/photo/' . $albumURL . $rowPhoto['title_url'];
                    } else
                    {
                        $url = 'http://' .$www . $_SERVER['SERVER_NAME'] . '/'. $subDir . $userName .
                            '/photo/view/' . $rowPhoto['title_url'];
                    }
                }
                
                //Get Photo Description
                try
                {
                    $qPhotoInfo = $dbh->prepare('SELECT * FROM ' . $_CONF['db']['prefix'] .
                        'photo_info WHERE photo_id=:photo_id');
                    $qPhotoInfo->bindParam(':photo_id', $rowPhoto['photo_id'], PDO::PARAM_INT);
                    $qPhotoInfo->execute();
                    $resultPhotoInfo = $qPhotoInfo->fetchAll();

                }
                catch (PDOException $e)
                {
                    echo $e->getMessage();
                }
                
               $images .= '<li>';
			   $images .= '<a class="thumb" name="'. $rowPhoto['title'] .'" href="../file/pic/photo/'. $destination_large .'" title="'. $rowPhoto['title'] .'">';
			   $images .= '<img src="../file/pic/photo/'. $destination_thumb .'" alt="'. $rowPhoto['title'] .'" width="50" height="40" />';
			   $images .= '</a>';
			   $images .= '<div class="caption">';
			   $images .= '<div class="download">';
			   $images .= '<a href="'.$url.'" target="_parent">Go To Photo</a>';
			   $images .= '</div>';
               $images .= '<div class="image-title">'. $rowPhoto['title'] .'</div>';
               $images .= '<div class="image-desc">Description</div>';
               $images .= $rowPhotoInfo['description'];
               $images .= '</div>';
               $images .= '</li>';




                //$images .= '<img src="../file/pic/photo/' . $destination . '" longdesc="' . $url .
                  //  '" width="400" height="300" alt="' . $rowPhoto['title'] . '" />';
            }

        }
    }
    catch (PDOException $e)
    {
        die('666'); //SQL Exception
    }
}

if ($_GET['mode'] == 'album')
{
    try
                {
                    $qUser = $dbh->prepare('SELECT * FROM ' . $_CONF['db']['prefix'] .
                        'user WHERE user_name=:user_name');
                    $qUser->bindParam(':user_name', $_GET['userName'], PDO::PARAM_STR);
                    $qUser->execute();
                    $resultUser = $qUser->fetchAll();
                    
                    foreach ($resultUser as $rowUser)
                    {
                        $userID = $rowUser['user_id'];
                        //echo $userID;
                    }
                }
                catch (PDOException $e)
                {
                    echo $e->getMessage();
                }
    
    try
                {
                    $qAlbum = $dbh->prepare('SELECT * FROM ' . $_CONF['db']['prefix'] .
                        'photo_album WHERE user_id=:user_id AND name_url=:name_url');
                    $qAlbum->bindParam(':name_url', $_GET['albumName'], PDO::PARAM_STR);
                    $qAlbum->bindParam(':user_id', $userID, PDO::PARAM_INT);
                    $qAlbum->execute();
                    $resultAlbum = $qAlbum->fetchAll();

                    $albumPrivacy = 0;
                    foreach ($resultAlbum as $rowAlbum)
                    {
                        $albumID = $rowAlbum['album_id'];
                    }
                }
                catch (PDOException $e)
                {
                    echo $e->getMessage();
                }
    
    
    try
    {
        $qPhoto = $dbh->prepare('SELECT * FROM ' . $_CONF['db']['prefix'] .
            'photo WHERE album_id=:album_id ORDER BY photo_id DESC LIMIT :limit');
        $qPhoto->bindValue(':limit', intval($limit), PDO::PARAM_INT);
        $qPhoto->bindValue(':album_id', $albumID, PDO::PARAM_INT);
        $qPhoto->execute();
        $resultPhoto = $qPhoto->fetchAll();


        foreach ($resultPhoto as $rowPhoto)
        {
                $destination_thumb = str_replace("%s", "_75", $rowPhoto['destination']);
                $destination_large = str_replace("%s", "_240", $rowPhoto['destination']);

$www = '';

if($_GET['www'] == 1)
{
    $www = 'www.';
}
                if ($shortURL == 0)
                {
                    if (strlen($albumURL) > 0)
                    {
                        $url = 'http://'.$www . $_SERVER['SERVER_NAME'] . '/'.$subDir.'index.php?do=/' . $userName .
                            '/photo/' . $albumURL . $rowPhoto['title_url'];
                    } else
                    {
                        $url = 'http://'. $www . $_SERVER['SERVER_NAME'] . '/'.$subDir.'index.php?do=/' . $userName .
                            '/photo/view/' . $rowPhoto['title_url'];
                    }
                } else
                {
                    if (strlen($albumURL) > 0)
                    {
                        $url = 'http://' . $www . $_SERVER['SERVER_NAME'] . '/'. $subDir . $userName .
                            '/photo/' . $albumURL . $rowPhoto['title_url'];
                    } else
                    {
                        $url = 'http://'. $www . $_SERVER['SERVER_NAME'] . '/'. $subDir . $userName .
                            '/photo/view/' . $rowPhoto['title_url'];
                    }
                }
                
                //Get Photo Description
                try
                {
                    $qPhotoInfo = $dbh->prepare('SELECT * FROM ' . $_CONF['db']['prefix'] .
                        'photo_info WHERE photo_id=:photo_id');
                    $qPhotoInfo->bindParam(':photo_id', $rowPhoto['photo_id'], PDO::PARAM_INT);
                    $qPhotoInfo->execute();
                    $resultPhotoInfo = $qPhotoInfo->fetchAll();

                }
                catch (PDOException $e)
                {
                    echo $e->getMessage();
                }
                
               $images .= '<li>';
			   $images .= '<a class="thumb" name="'. $rowPhoto['title'] .'" href="../file/pic/photo/'. $destination_large .'" title="'. $rowPhoto['title'] .'">';
			   $images .= '<img src="../file/pic/photo/'. $destination_thumb .'" alt="'. $rowPhoto['title'] .'" width="50" height="40" />';
			   $images .= '</a>';
			   $images .= '<div class="caption">';
			   $images .= '<div class="download">';
			   $images .= '<a href="'.$url.'" target="_parent">Go To Photo</a>';
			   $images .= '</div>';
               $images .= '<div class="image-title">'. $rowPhoto['title'] .'</div>';
               $images .= '<div class="image-desc">Description</div>';
               $images .= $rowPhotoInfo['description'];
               $images .= '</div>';
               $images .= '</li>';




                //$images .= '<img src="../file/pic/photo/' . $destination . '" longdesc="' . $url .
                  //  '" width="400" height="300" alt="' . $rowPhoto['title'] . '" />';
            

        }
    }
    catch (PDOException $e)
    {
        die('666'); //SQL Exception
    }
}
// End mode Album


//video mode
if ($_GET['mode'] == 'video')
{
    try
    {
        $qVideo = $dbh->prepare('SELECT * FROM ' . $_CONF['db']['prefix'] .
            'video WHERE in_process=0 AND privacy=0 ORDER BY video_id DESC LIMIT :limit');
        $qVideo->bindValue(':limit', intval($limit), PDO::PARAM_INT);
        $qVideo->execute();
        $resultVideo = $qVideo->fetchAll();

        foreach ($resultVideo as $rowVideo)
        {
            try
            {
                $qUser = $dbh->prepare('SELECT * FROM ' . $_CONF['db']['prefix'] .
                    'user WHERE user_id=:user_id');
                $qUser->bindParam(':user_id', $rowVideo['user_id'], PDO::PARAM_INT);
                $qUser->execute();
                $resultUser = $qUser->fetchAll();

                foreach ($resultUser as $rowUser)
                {
                    $userName = $rowUser['user_name'];
                }
            }
            catch (PDOException $e)
            {
                die('666'); //SQL Exception
            }

            $destination = str_replace("%s", "_120", $rowVideo['image_path']);

            if ($shortURL == 0)
            {
                $url = 'http://' . $_SERVER['SERVER_NAME'] . '/' .$subDir .'index.php?do=/' . $userName .
                    '/video/' . $rowVideo['title_url'];
            } else
            {
                $url = 'http://' . $_SERVER['SERVER_NAME'] . '/' .$subDir . $userName .
                    '/video/' . $rowVideo['title_url'];
            }

            $images .= '<img src="../file/pic/video/' . $destination . '" longdesc="' . $url .
                '" width="400" height="300" alt="' . $rowVideo['title'] . '" />';
        }
    }
    catch (PDOException $e)
    {
        echo $e->getMessage();
    }
}
// end video mode

//profile mode
if ($_GET['mode'] == 'profile')
{
    try
    {
        $qProfile = $dbh->prepare('SELECT * FROM ' . $_CONF['db']['prefix'] .
            'user WHERE user_image IS NOT NULL ORDER BY user_id DESC LIMIT :limit');
        $qProfile->bindValue(':limit', intval($limit), PDO::PARAM_INT);
        $qProfile->execute();
        $resultProfile = $qProfile->fetchAll();

        foreach ($resultProfile as $rowProfile)
        {

            $destination = str_replace("%s", "_200", $rowProfile['user_image']);

            if ($shortURL == 0)
            {
                $url = 'http://' . $_SERVER['SERVER_NAME'] . '/' .$subDir. 'index.php?do=/' . $rowProfile['user_name'] .'/';
            } else
            {
                $url = 'http://' . $_SERVER['SERVER_NAME'] . '/' .$subDir . $rowProfile['user_name'] .'/' . '/';
            }

            $images .= '<img src="../file/pic/user/' . $destination . '" longdesc="' . $url .
                '" width="400" height="300" alt="' . $rowProfile['user_name'] . '" />';
        }
    }
    catch (PDOException $e)
    {
        echo $e->getMessage();
    }
}
// end profile mode

//profile mode
if ($_GET['mode'] == 'logins')
{
    try
    {
        $qProfile = $dbh->prepare('SELECT * FROM ' . $_CONF['db']['prefix'] .
            'user WHERE user_image IS NOT NULL ORDER BY last_login DESC LIMIT :limit');
        $qProfile->bindValue(':limit', intval($limit), PDO::PARAM_INT);
        $qProfile->execute();
        $resultProfile = $qProfile->fetchAll();

        foreach ($resultProfile as $rowProfile)
        {

            $destination = str_replace("%s", "_100", $rowProfile['user_image']);

            if ($shortURL == 0)
            {
                $url = 'http://' . $_SERVER['SERVER_NAME'] . '/' .$subDir. 'index.php?do=/' . $rowProfile['user_name'] .'/';
            } else
            {
                $url = 'http://' . $_SERVER['SERVER_NAME'] . '/'. $subDir . $rowProfile['user_name'] .'/' . '/';
            }

            $images .= '<img src="../file/pic/user/' . $destination . '" longdesc="' . $url .
                '" width="400" height="300" alt="' . $rowProfile['user_name'] . '" />';
        }
    }
    catch (PDOException $e)
    {
        echo $e->getMessage();
    }
}
// end logins mode

echo $images;

?>