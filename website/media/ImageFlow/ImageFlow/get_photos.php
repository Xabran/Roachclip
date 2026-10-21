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
    try
    {
        $qPhoto = $dbh->prepare('SELECT * FROM ' . $_CONF['db']['prefix'] .
            'photo ORDER BY photo_id DESC LIMIT :limit');
        $qPhoto->bindValue(':limit', intval($limit), PDO::PARAM_INT);
        $qPhoto->execute();
        $resultPhoto = $qPhoto->fetchAll();


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
                $destination = str_replace("%s", "_240", $rowPhoto['destination']);


                if ($shortURL == 0)
                {
                    if (strlen($albumURL) > 0)
                    {
                        $url = 'http://' . $_SERVER['SERVER_NAME'] . '/'.$subDir.'index.php?do=/' . $userName .
                            '/photo/' . $albumURL . $rowPhoto['title_url'];
                    } else
                    {
                        $url = 'http://' . $_SERVER['SERVER_NAME'] . '/'.$subDir.'index.php?do=/' . $userName .
                            '/photo/view/' . $rowPhoto['title_url'];
                    }
                } else
                {
                    if (strlen($albumURL) > 0)
                    {
                        $url = 'http://' . $_SERVER['SERVER_NAME'] . '/'. $subDir . $userName .
                            '/photo/' . $albumURL . $rowPhoto['title_url'];
                    } else
                    {
                        $url = 'http://' . $_SERVER['SERVER_NAME'] . '/'. $subDir . $userName .
                            '/photo/view/' . $rowPhoto['title_url'];
                    }
                }
                //http://pagebraker.com/nico/video/funny-sexy-bulgarian-russian-beer-ads/

                $images .= '<img src="../file/pic/photo/' . $destination . '" longdesc="' . $url .
                    '" width="400" height="300" alt="' . $rowPhoto['title'] . '" />';
            }

        }
    }
    catch (PDOException $e)
    {
        die('666'); //SQL Exception
    }
}

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