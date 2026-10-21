<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: AdminManageController.php 7244 2010-09-01 01:49:53Z john $
 * @author     Jung
 */

/**
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 */
class Import_AdminManageController extends Core_Controller_Action_Admin {

    private $phpfox_db;
    private $phpfox_config;

    public function init() {
        //phpinfo();
        //exit();
        //require_once 'Zend/Db/Adapter/Pdo/Mysql.php';
        //require_once dirname(__FILE__).'/../libraries/header.php';
        //require_once 'Debug/PhpConsole.php';
        //Zend_Loader::loadClass('Zend_Db_Adapter_Pdo_Mysql');
        //PhpConsole::start();

        $this->phpfox_config = $this->getPhpfoxbaseSetting();
        //echo 0;
        //exit();

        try {

				try{
            $this->phpfox_db = new Zend_Db_Adapter_Pdo_Mysql(array(
                        'host' => $this->phpfox_config['phpfox_host'],
                        'username' => $this->phpfox_config['phpfox_user'],
                        'password' => $this->phpfox_config['phpfox_password'],
                        'dbname' => $this->phpfox_config['phpfox_dbname']
//			    'host'     => 'localhost',
//			    'username' => 'root',
//			    'password' => '',
//			    'dbname'   => 'phpfox205'
                    ));

            $this->phpfox_db->getConnection();

            //$this->phpfox_db_select =  new Zend_Db_Select($this->phpfox_db);
		     } catch (Zend_Db_Adapter_Exception $e) {

		         $this->phpfox_db = new Zend_Db_Adapter_Mysqli(array(
		                     'host' => $this->phpfox_config['phpfox_host'],
		                     'username' => $this->phpfox_config['phpfox_user'],
		                     'password' => $this->phpfox_config['phpfox_password'],
		                     'dbname' => $this->phpfox_config['phpfox_dbname']
	//			    'host'     => 'localhost',
	//			    'username' => 'root',
	//			    'password' => '',
	//			    'dbname'   => 'phpfox205'
		                 ));

		         $this->phpfox_db->getConnection();
			}
            // perhaps a failed login credential, or perhaps the RDBMS is not running
        } catch (Zend_Exception $e) {

				return;
            // perhaps factory() failed to load the specified Adapter class
        }
    }

    protected function getPhpfoxbaseSetting() {
        $settings = array();

        if (Engine_Api::_()->getApi('settings', 'core')->getSetting('phpfox_host'))
            $settings['phpfox_host'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('phpfox_host');
        else
            $settings['phpfox_host'] = "locahost";

        if (Engine_Api::_()->getApi('settings', 'core')->getSetting('phpfox_prefix'))
            $settings['phpfox_prefix'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('phpfox_prefix');
        else
            $settings['phpfox_prefix'] = "phpfox_";

        if (Engine_Api::_()->getApi('settings', 'core')->getSetting('phpfox_user'))
            $settings['phpfox_user'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('phpfox_user');
        else
            $settings['phpfox_user'] = "root";

        if (Engine_Api::_()->getApi('settings', 'core')->getSetting('phpfox_password'))
            $settings['phpfox_password'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('phpfox_password');
        else
            $settings['phpfox_password'] = "";

        if (Engine_Api::_()->getApi('settings', 'core')->getSetting('phpfox_dbname'))
            $settings['phpfox_dbname'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('phpfox_dbname');
        else
            $settings['phpfox_dbname'] = "phpfox";

        if (Engine_Api::_()->getApi('settings', 'core')->getSetting('phpfox_url'))
            $settings['phpfox_url'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('phpfox_url');
        else
            $settings['phpfox_url'] = "";
            
       	if (Engine_Api::_()->getApi('settings', 'core')->getSetting('send_password'))
            $settings['send_password'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('send_password');
        else
            $settings['send_password'] = 0;
            
        if (Engine_Api::_()->getApi('settings', 'core')->getSetting('send_message'))
            $settings['send_message'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('send_message');
        else
            $settings['send_message'] = 'Dear members,
	
	Your ' . $_SERVER['SERVER_NAME'] . ' new password is: %d
	Best regards ' . $_SERVER['SERVER_NAME'] . ' administration.';

        return $settings;
    }
    
    public function dumpAction(){
    	require_once dirname(__FILE__) . '/../libraries/header.php';
	    //$host = "localhost";  // имя сервера   
		//$user = "root";       // имя пользователя
		//$password = "";       // пароль
		//$db_name = "mydb";    // имя базы данных
		
		$dump_dir = dirname(__FILE__).'/../../../../temporary/dump'; // директория, куда будем сохранять резервную копию БД
		unlink($dump_dir);
		mkdir($dump_dir, 0777);
		
		//$link = mysql_connect($host, $user, $password) or die( "Сервер базы данных не доступен" );
		//$db = mysql_select_db($db_name) or die( "База данных не доступна" );
		$tables = "SHOW TABLES";
		$res = $database->database_query($tables); //or die( "Ошибка при выполнении запроса: ".mysql_error() );
		
		while( $table = mysql_fetch_row($res) )
		{
		    $fp = fopen( $dump_dir."/".$table[0].".sql", "a" );
		    if ( $fp )
		    {
		        $query = "TRUNCATE TABLE `".$table[0]."`;###\n";
		        fwrite ($fp, $query);
		        $rows = 'SELECT * FROM `'.$table[0].'`';
		        $r = $database->database_query($rows);// or die("Ошибка при выполнении запроса: ".mysql_error());
		        while( $row = mysql_fetch_row($r) )
		        {
		            $query = "";
		            foreach ( $row as $field )
		            {
		                if ( is_null($field) )
		                    $field = "NULL";
		                else
		                    $field = "'".mysql_escape_string( $field )."'";
		                if ( $query == "" )
		                    $query = $field;
		                else
		                    $query = $query.', '.$field;
		            }
		            $query = "INSERT INTO `".$table[0]."` VALUES (".$query.");###\n";
		            fwrite ($fp, $query);
		        }
		        fclose ($fp);
		    }
		}
    	
    }
    
    public function restoreAction($table){
    	require_once dirname(__FILE__) . '/../libraries/header.php';
	    $scandir = dirname(__FILE__).'/../../../../temporary/dump/';
		$scan = scandir($scandir);
		
		$table = $this->getRequest()->getQuery('table');
		
		
		if(!$table && !isset($_GET['table'])){
			//var_dump($_GET);
			
			$this->view->table = $table = $scan[2];
			
			
		}elseif(isset($_GET['table'])){
			
			if($_GET['table'] == ""){
				$this->view->stop = true;
				$this->view->restorestatus = "Ok";
				return;
			}else{
				$this->view->stop = false;
			}
			
			//$this->view->table = $table = $scandir.$scan[0]; 
			
			$file = file_get_contents($scandir.$table);
			
			$queries = explode(";###", $file);
			 
			foreach ($queries as $query){
				$database->database_query($query);
			}
			
			unlink($scandir.$table);
			
			$this->view->table = $scan[3];
			$this->view->restorestatus = "Restore..."; 
		} 
		
		/*
		for ($i=0; $i<count($scan); $i++) {
			//$scan[$i]
			
			$table = file_get_contents($scandir.$scan[$i]);
			
			$queries = explode(";", $table);
			 
			foreach ($queries as $query){
			//	$database->database_query($query);
			}
			
			
			
			/*$fileot = $dir . "/" . $i . ".jpg";
		     $fhf = fopen($fileot, "w+");
		     fwrite($fhf, $image);
		     fclose($fhf);*/
			//unlink($scandir.$scan[$i]);
		//}
    }

    
    public function settingsAction() {
        //debug('test message');

        if (isset($_POST['submit'])) {
            unset($_POST['submit']);

            foreach ($_POST as $key => $value) {
                Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
            }
        }

        $settings = $this->getPhpfoxbaseSetting();

        foreach ($settings as $keyfox => $valuefox) {
            $this->view->$keyfox = $valuefox;
        }
    }

    public function importmusicAction() {

        $users_table = Engine_Api::_()->getItemTable('user');
        $userTableName = $users_table->info('name');
        $users_select = $users_table->select();
        $user_result = $users_select->query();
        $users = $user_result->fetchAll();

        foreach ($users as $user) {

            $select = $this->phpfox_db->select()
                    ->from(array('m' => $this->phpfox_config['phpfox_prefix'] . 'music_song'))
                    ->join(array('u' => $this->phpfox_config['phpfox_prefix'] . 'user'), 'u.user_id = m.user_id')
                    ->where('u.user_name = ?', $user['username']);

            $stmt = $this->phpfox_db->query($select);
            $results = $stmt->fetchAll();

            if (count($results) == 0) {
                continue;
            }

            //Сначала созданм песни

            $file_ids = array();
            foreach ($results as $result) {


                //копируем песни к нам на серв

                $nfile_url = "public/import/" . $user['user_id'] . "/" . $results['song_path']; // .".". $picture_info['extension'] ;

                $url = $this->phpfox_config['phpfox_url'] . '/file/music/';
                $path = "http://".str_replace('%s', '', $url . $result['song_path']);


                //exit();
                @$mp3 = file_get_contents($path);

                if (!is_dir("public/import/"))
                    mkdir('public/import/', 0777, true);

                if (!is_dir("public/import/" . $user['user_id']))
                    mkdir("public/import/" . $user['user_id'], 0777, true);

                $folder = "public/import/" . $user['user_id'];


                //$mp3_name = substr($result['song_path'], 8, $lenth - 15).".mp3";
                $mp3_name = $result['title'] . ".mp3";
                $fileot = $folder . "/" . $mp3_name;
                $fhf = fopen($fileot, "w+");
                fwrite($fhf, $mp3);
                fclose($fhf);
                $mp3_path = $fileot;

			

                //создаем записи в сторадже и формируем список ид для нового плей листа
                $params = array(
                    'type' => 'song',
                    'name' => $result['title'],
                    'parent_type' => 'music_song',
                    'parent_id' => $user['user_id'],
                    'user_id' => $user['user_id'],
                    'extension' => 'mp3',
                );

                //print_r($params); exit();
                $song = Engine_Api::_()->storage()->create($mp3_path, $params);

                $file_ids[] = $song->getIdentity();
            }


            // Get form
            $this->view->form = $form = new Music_Form_Create();
            $this->view->playlist_id = $this->_getParam('playlist_id', '0');


            //print_r($result); exit();
            // Process
            //теперь делаем плейлист из тех песен, что скопировали выше
            $db = Engine_Api::_()->getDbTable('playlists', 'music')->getAdapter();
            $db->beginTransaction();

            try {
                $playlist = null;
                $values = array(
                    'title' => 'unsorted',
                    'description' => 'unsorted',
                    'auth_view' => 'everyone',
                    'auth_comment' => 'everyone',
                    'MAX_FILE_SIZE' => '209715200',
                    'submit' => '',
                    'fancyuploadfileids' => '',
                    'search' => 1,
                );
                $translate = Zend_Registry::get('Zend_Translate');

                if (!empty($values['playlist_id']))
                    $playlist = Engine_Api::_()->getItem('music_playlist', $values['playlist_id']);
                else {
                    $playlist = $this->_playlist = Engine_Api::_()->getDbtable('playlists', 'music')->createRow();
                    $playlist->title = trim($values['title']);
                    if (empty($playlist->title))
                        $playlist->title = $translate->_('_MUSIC_UNTITLED_PLAYLIST');

                    $playlist->owner_type = 'user';
                    $playlist->owner_id = $user['user_id'];
                    $playlist->description = trim($values['description']);
                    $playlist->search = $values['search'];
                    $playlist->save();
                    $values['playlist_id'] = $playlist->playlist_id;

                    // Assign $playlist to a Core_Model_Item
                    $playlist = $this->_playlist = Engine_Api::_()->getItem('music_playlist', $values['playlist_id']);




                    // Attach songs (file_ids) to playlist
                    if (!empty($file_ids))
                        foreach ($file_ids as $file_id)
                            $playlist->addSong($file_id);

                    // Only create activity feed item if "search" is checked
                    if ($playlist->search) {
                        $activity = Engine_Api::_()->getDbtable('actions', 'activity');
                        $action = $activity->addActivity(
                                        Engine_Api::_()->user()->getViewer(), $playlist, 'music_playlist_new', null, array('count' => count($file_ids))
                        );
                        if (null !== $action)
                            $activity->attachActivity($action, $playlist);
                    }
                }
                $db->commit();
            } catch (Exception $e) {
                $db->rollback();
                throw $e;
            }
        }
    }

    public function importgroupAction() {

        $select = $this->phpfox_db->select()
                ->from(array('g' => $this->phpfox_config['phpfox_prefix'] . 'group'))
                ->join(array('gt' => $this->phpfox_config['phpfox_prefix'] . 'group_text'), 'g.group_id = gt.group_id')
                ->join(array('u' => $this->phpfox_config['phpfox_prefix'] . 'user'), 'u.user_id = g.user_id');

        $stmt = $this->phpfox_db->query($select);
        $results = $stmt->fetchAll();

        // Process


        $db = Engine_Api::_()->getDbtable('groups', 'group')->getAdapter();
        $db->beginTransaction();

        foreach ($results as $result) {

            $users_table = Engine_Api::_()->getItemTable('user');

            $userTableName = $users_table->info('name');

            $users_select = $users_table->select()->where($userTableName . '.username = ?', $result['user_name']);

            $user_result = $users_select->query();
            $user = $user_result->fetch();

            //print_r($user);
            //exit();

            $nfile_url = "public/import/" . $user['user_id'] . "/" . $results['image_path']; // .".". $picture_info['extension'] ;

            $url = $this->phpfox_config['phpfox_url'] . '/file/pic/group/';
            $path = "http://".str_replace('%s', '', $url . $result['image_path']);


            //exit();
           
            @$image = file_get_contents($path);

            if (!is_dir("public/import/"))
                mkdir('public/import/', 0777, true);

            if (!is_dir("public/import/" . $user['user_id']))
                mkdir("public/import/" . $user['user_id'], 0777, true);

            $folder = "public/import/" . $user['user_id'];

            //if(!$image)
            //continue;
            $image_name = substr($result['image_path'], 8, $lenth - 15) . ".jpg";
            $fileot = $folder . "/" . $image_name;
            $fhf = fopen($fileot, "w+");
            fwrite($fhf, $image);
            fclose($fhf);
            $image_path = $fileot;


            try {
                // Create classified
                $values = array('title' => $result['title'],
                    'description' => $result['description'],
                    'category_id' => 0,
                    'search' => 1,
                    'auth_invite' => 'member',
                    'approval' => 1,
                    'auth_view' => 'everyone',
                    'auth_comment' => 'registered',
                    'auth_photo' => 'registered',
                    'auth_event' => 'registered',
                    'user_id' => $user['user_id']
                );


                // Create group
                $table = $this->_helper->api()->getDbtable('groups', 'group');
                $group = $table->createRow();
                $group->setFromArray($values);
                $group->save();

                // Add owner as member
                $viewer = $this->_helper->api()->user()->getUser($user['user_id']);

                $group->membership()->addMember($viewer)
                        ->setUserApproved($viewer)
                        ->setResourceApproved($viewer);

                //Add more members
                //print_r($result['group_id']);
                //exit();
                
                $select_inv = $this->phpfox_db->select()
		                ->from(array('gi' => $this->phpfox_config['phpfox_prefix'] . 'group_invite'))
		                ->join(array('u' => $this->phpfox_config['phpfox_prefix'] . 'user'), 'u.user_id = gi.invited_user_id')
		                ->where('gi.group_id= ?', (int)$result['group_id']);

				
		        $stmt_inv = $this->phpfox_db->query($select_inv);
		        
		        $members = $stmt_inv->fetchAll();
		        
		        foreach ($members as $member){
		        	
		        	$users_group_table = Engine_Api::_()->getItemTable('user');
                	$userCommentTableName = $users_group_table->info('name');
                	$users_group_select = $users_group_table->select()->where($userCommentTableName . '.username = ?', $member['user_name']);
                	$user_group_result = $users_group_select->query();
                	$user_group = $user_group_result->fetch();
		        	
		        	$group_member = $this->_helper->api()->user()->getUser($user_group['user_id']);
						
		        	try {
                	$group->membership()->addMember($group_member)
                        ->setUserApproved($group_member)
                        ->setResourceApproved($group_member);
		        	}catch(Exception $e){
		        		
		        	}
		        }
					
		        if(file_exists($image_path)){
			       	try{
	                	$group->setPhoto($image_path);
			       	}catch (Exception $e){
			       		
			       	}
		        }



                // Process privacy
                $auth = Engine_Api::_()->authorization()->context;

                $roles = array('officer', 'member', 'registered', 'everyone');

                if (empty($values['auth_view'])) {
                    $values['auth_view'] = 'everyone';
                }

                if (empty($values['auth_comment'])) {
                    $values['auth_comment'] = 'everyone';
                }


                $viewMax = array_search($values['auth_view'], $roles);
                $commentMax = array_search($values['auth_comment'], $roles);
                $photoMax = array_search($values['auth_photo'], $roles);
                $eventMax = array_search($values['auth_event'], $roles);
                $inviteMax = array_search($values['auth_invite'], $roles);

                $officerList = $group->getOfficerList();

                foreach ($roles as $i => $role) {
                    if ($role === 'officer') {
                        $role = $officerList;
                    }
                    $auth->setAllowed($group, $role, 'view', ($i <= $viewMax));
                    $auth->setAllowed($group, $role, 'comment', ($i <= $commentMax));
                    $auth->setAllowed($group, $role, 'photo', ($i <= $photoMax));
                    $auth->setAllowed($group, $role, 'event', ($i <= $eventMax));
                    $auth->setAllowed($group, $role, 'invite', ($i <= $inviteMax));
                }

                // Create some auth stuff for all officers
                $auth->setAllowed($group, $officerList, 'photo.edit', 1);
                $auth->setAllowed($group, $officerList, 'topic.edit', 1);

                // Add auth for invited users
                $auth->setAllowed($group, 'member_requested', 'view', 1);

                // Add action
                $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
                $action = $activityApi->addActivity($viewer, $group, 'group_create');
                if ($action) {
                    $activityApi->attachActivity($action, $group);
                }

                // Commit
                $db->commit();
            } catch (Exception $e) {

                $db->rollBack();
                throw $e;
            }


            $group_ = Engine_Api::_()->getItemTable('group');
            $new_group_select = $group_->select()->order(array('group_id DESC'));

            $new_group_result = $new_group_select->query();
            $new_group = $new_group_result->fetch();

            $item_id = $new_group['group_id'];


            $select_comment = $this->phpfox_db->select()
                            ->from(array('c' => $this->phpfox_config['phpfox_prefix'] . 'comment'))
                            ->join(array('ct' => $this->phpfox_config['phpfox_prefix'] . 'comment_text'), 'c.comment_id = ct.comment_id')
                            ->join(array('u' => $this->phpfox_config['phpfox_prefix'] . 'user'), 'u.user_id = c.user_id')
                            ->where('c.item_id = ?', $result['group_id'])->where('type_id = ?', 'group');

            $stmt_com = $this->phpfox_db->query($select_comment);
            $comment_results = $stmt_com->fetchAll();

            //print_r($comment_results);
            //exit();		

            foreach ($comment_results as $comment_result) {

                $users_comment_table = Engine_Api::_()->getItemTable('user');
                $userCommentTableName = $users_comment_table->info('name');
                $users_comment_select = $users_comment_table->select()->where($userCommentTableName . '.username = ?', $comment_result['user_name']);
                $user_comment_result = $users_comment_select->query();
                $user_comment = $user_comment_result->fetch();

                //$item = Engine_Api::_()->getItem('classified', $item_id);
                //$subject = Engine_Api::_()->core()->getSubject('comments');
                //$comment_table = Engine_Api::_()->getItemTable('comment');

                $viewer_ = $this->_helper->api()->user()->getUser($user_comment['user_id']);
                $type = 'post';
                $body = $comment_result['text'];

                // Add activity
                $action = $this->_helper->api()->getDbtable('actions', 'activity')->addActivity($viewer_, $group, $type, $body);
            }
        }
    }

    public function importclassifiedsAction() {
        //$classifieds_query = "SELECT * FROM `" . PHPFOX_PREFIX . "marketplace` AS m LEFT JOIN " . PHPFOX_PREFIX . "marketplace_text AS mt ON m.listing_id = mt.listing_id LEFT JOIN " . PHPFOX_PREFIX . "user AS u ON m.user_id = u.user_id";


        $select = $this->phpfox_db->select()
                ->from(array('m' => $this->phpfox_config['phpfox_prefix'] . 'marketplace'))
                ->join(array('mt' => $this->phpfox_config['phpfox_prefix'] . 'marketplace_text'), 'm.listing_id = mt.listing_id')
                ->join(array('u' => $this->phpfox_config['phpfox_prefix'] . 'user'), 'u.user_id = m.user_id');

        $stmt = $this->phpfox_db->query($select);
        $results = $stmt->fetchAll();


        // Process
        $table = Engine_Api::_()->getItemTable('classified');
        $db = $table->getAdapter();
        $db->beginTransaction();

        foreach ($results as $result) {
            $users_table = Engine_Api::_()->getItemTable('user');

            $userTableName = $users_table->info('name');

            $users_select = $users_table->select()->where($userTableName . '.username = ?', $result['user_name']);

            $user_result = $users_select->query();
            $user = $user_result->fetch();

            //print_r($user);
            //exit();

            $nfile_url = "public/import/" . $user['user_id'] . "/" . $results['image_path']; // .".". $picture_info['extension'] ;

            $url = $this->phpfox_config['phpfox_url'] . '/file/pic/marketplace/';
            $path = "http://".str_replace('%s', '', $url . $result['image_path']);


            //exit();
            @$image = file_get_contents($path);

            if (!is_dir("public/import/"))
                mkdir('public/import/', 0777, true);

            if (!is_dir("public/import/" . $user['user_id']))
                mkdir("public/import/" . $user['user_id'], 0777, true);

            $folder = "public/import/" . $user['user_id'];

            //if(!$image)
            //continue;
            $image_name = substr($result['image_path'], 8, $lenth - 15) . ".jpg";
            $fileot = $folder . "/" . $image_name;
            $fhf = fopen($fileot, "w+");
            fwrite($fhf, $image);
            fclose($fhf);
            $image_path = $fileot;

            $form = new Classified_Form_Create();
            try {
                // Create classified
                $values = array('title' => $result['title'],
                    'tags' => '',
                    'category_id' => 0,
                    'body' => $result['description'],
                    //'photo' => '',
                    'auth_view' => 'everyone',
                    'auth_comment' => 'everyone',
                    'fields' => array(
                        '0_0_2' => $result['price'],
                        '0_0_3' => $result['city']
                    ),
                    'owner_type' => $user['level_id'],
                    'owner_id' => $user['user_id']
                );

                $classified = $table->createRow();
                $classified->setFromArray($values);
                $classified->save();

                // Set photo
                //if( !empty($values['photo']) ) {
                //if($image){
                // echo $image_path;
                // exit();
                $classified->setPhoto($image_path);
                //}
                //}
                // Add tags
                //$tags = preg_split('/[,]+/', $values['tags']);
                //$tags = array_filter(array_map("trim", $tags));
                //$classified->tags()->addTagMaps($viewer, $tags);
                // Add fields
                $customfieldform = $form->getSubForm('fields');
                $customfieldform->setItem($classified);
                $customfieldform->saveValues();

                // Set privacy
                $auth = Engine_Api::_()->authorization()->context;
                $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');

                if (empty($values['auth_view'])) {
                    $values['auth_view'] = array("everyone");
                }
                if (empty($values['auth_comment'])) {
                    $values['auth_comment'] = array("everyone");
                }

                $viewMax = array_search($values['auth_view'], $roles);
                $commentMax = array_search($values['auth_comment'], $roles);

                foreach ($roles as $i => $role) {
                    $auth->setAllowed($classified, $role, 'view', ($i <= $viewMax));
                    $auth->setAllowed($classified, $role, 'comment', ($i <= $commentMax));
                }

                // Commit
                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }

            $classified_ = Engine_Api::_()->getItemTable('classified');
            $new_classifieds_select = $classified_->select()->order(array('classified_id DESC'));

            $new_classifieds_result = $new_classifieds_select->query();
            $new_classifieds = $new_classifieds_result->fetch();

            $item_id = $new_classifieds['classified_id'];
            //print_r($new_classifieds);
            //exit();

            $select_comment = $this->phpfox_db->select()
                            ->from(array('c' => $this->phpfox_config['phpfox_prefix'] . 'comment'))
                            ->join(array('ct' => $this->phpfox_config['phpfox_prefix'] . 'comment_text'), 'c.comment_id = ct.comment_id')
                            ->join(array('u' => $this->phpfox_config['phpfox_prefix'] . 'user'), 'u.user_id = c.user_id')
                            ->where('c.item_id = ?', $result['listing_id'])->where('type_id = ?', 'marketplace');

            $stmt_com = $this->phpfox_db->query($select_comment);
            $comment_results = $stmt_com->fetchAll();



            foreach ($comment_results as $comment_result) {

                $users_comment_table = Engine_Api::_()->getItemTable('user');
                $userCommentTableName = $users_comment_table->info('name');
                $users_comment_select = $users_comment_table->select()->where($userCommentTableName . '.username = ?', $comment_result['user_name']);
                $user_comment_result = $users_comment_select->query();
                $user_comment = $user_comment_result->fetch();

                //$item = Engine_Api::_()->getItem('classified', $item_id);
                //$subject = Engine_Api::_()->core()->getSubject('comments');
                //$comment_table = Engine_Api::_()->getItemTable('comment');
                $comment_table = Engine_Api::_()->getDbtable('comments', 'core');

                //print_r($comment_table->info('name')); exit();
                $commentTableName = $comment_table->info('name');

                $data = array(
                    'resource_type' => 'classified',
                    'resource_id' => $item_id,
                    'poster_type' => 'user',
                    'poster_id' => $user_comment['user_id'],
                    'body' => $comment_result['text'],
                    'creation_date' => date('Y-m-d h:i:s', $comment_result['time_stamp']),
                    'like_count' => 0);

                $comment_table->insert($data);
            }
        }
    }

    public function indexAction() {
        
    }
    
    function resetpasswordAction(){
    	require_once dirname(__FILE__) . '/../libraries/header.php';
    	$users = $database->database_query("SELECT * FROM `" . SOCIAL_PREFIX . "users`");
    	while ($user_info = $database->database_fetch_assoc($users)) {

        	if($user_info['level_id'] != 1){
	            $password = (string) rand(10000000, 99999999);
	            //$password = 111;
	            $salt = (string) rand(100000, 999999);
	            
	            $settings = Engine_Api::_()->getApi('settings', 'core');
	            $crypt_password = md5($settings->getSetting('core.secret', 'staticSalt') . $password . $salt);
	            $database->database_query("UPDATE `" . SOCIAL_PREFIX . "users` SET `password` = '" . $crypt_password . "', `salt` = '" . $salt . "' WHERE `user_id` = " . $user_info['user_id'] . " LIMIT 1");
	      		$message = 'Dear members,
		
				Your ' . $_SERVER['SERVER_NAME'] . ' new password is: ' . $password . '
		
				Best regards
		
				' . $_SERVER['SERVER_NAME'] . ' administration.';
	
	            $headers = 'From: info@' . $_SERVER['SERVER_NAME'] . "\r\n" .
	                    'Reply-To: info@' . $_SERVER['SERVER_NAME'] . "\r\n" .
	                    'X-Mailer: PHP/' . phpversion();
	        	
	       
		            if((int)$this->phpfox_config['send_password']){
		            	mail($user_info['email'], $_SERVER['SERVER_NAME'] . ' New Password', $message, $headers);
		            }
	        	}
    	}
    }
    
    public function importfriendsAction(){
    //import friends
		require_once dirname(__FILE__) . '/../libraries/header.php';
        $friends_c = $pdatabase->database_query("SELECT count(*) as cc FROM `" . PHPFOX_PREFIX . "friend`");
        $friend_count = $pdatabase->database_fetch_assoc($friends_c);
        
 $friends = $pdatabase->database_query("SELECT * FROM  `" . PHPFOX_PREFIX . "friend`");
        
//        $friends = $pdatabase->database_query("SELECT f.*, u.user_name as user, u2.user_name as friend FROM
//			`" . PHPFOX_PREFIX . "friend` f, " . PHPFOX_PREFIX . "user u, 
//			" . PHPFOX_PREFIX . "user u2 WHERE f.friend_user_id = u.user_id AND
//			f.user_id = u2.user_id");
        
        
        $i = 1;
        $k = 1000;

        while ($friend_info = $pdatabase->database_fetch_assoc($friends)) {
        	
        	$select_user = $this->phpfox_db->select()
                ->from(array('u' => $this->phpfox_config['phpfox_prefix'] . 'user'))
                ->where('u.user_id = ?', $friend_info['friend_user_id']);

        $stmt = $this->phpfox_db->query($select_user);
        $results_user = $stmt->fetch();
        
        $friend_info['user'] = $results_user['user_name'];
        
        $select_friend = $this->phpfox_db->select()
                ->from(array('u' => $this->phpfox_config['phpfox_prefix'] . 'user'))
                ->where('u.user_id = ?', $friend_info['user_id']);

        $stmt = $this->phpfox_db->query($select_friend);
        $results_friend = $stmt->fetch();
        
     
        
        $friend_info['friend'] = $results_friend['user_name'];
        	

            $user_info = $database->database_fetch_assoc($database->database_query("SELECT user_id, member_count
		FROM `" . SOCIAL_PREFIX . "users` WHERE username = '" . $friend_info['user'] . "' LIMIT 1"));
            $owner_info = $database->database_fetch_assoc($database->database_query("SELECT user_id
		FROM `" . SOCIAL_PREFIX . "users` WHERE username = '" . $friend_info['friend'] . "' LIMIT 1"));

            $members_count = (int) $user_info['member_count'] + 1;

            $friend_info['user_id'] = (int) $user_info['user_id'];
            $friend_info['friend_id'] = (int) $owner_info['user_id'];

            $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "user_membership`
		(`resource_id`, `user_id`, `active`, `resource_approved`, `user_approved`, `message`, `description`) VALUES
		(" . $friend_info['user_id'] . ", " . $friend_info['friend_id'] . ", 1, 1, 1, NULL, NULL)");
            $database->database_query("UPDATE `" . SOCIAL_PREFIX . "users` SET `member_count` = '" . $members_count . "'
		WHERE `user_id` = " . $friend_info['user_id'] . " LIMIT 1");
            //echo $friend_info['id']."<br/>";
            if ($i == $k) {
                $k = $k + 1000;
            }
            //echo '<pre>'; print_r($friend_info); echo '</pre>';
            $i++;
           
        }
    }

    public function importusersAction() {

        require_once dirname(__FILE__) . '/../libraries/header.php';

        $pusers = $pdatabase->database_query("SELECT * FROM `" . PHPFOX_PREFIX . "user`");
		
        while ($puser_info = $pdatabase->database_fetch_assoc($pusers)) {
            //print_r($puser_info);
            //	    $user_fields_query = $pdatabase->database_query("SELECT ft.*, f.custom_field_name FROM " . PHPFOX_PREFIX . "custom_field2text as ft LEFT JOIN " . PHPFOX_PREFIX . "custom_field as f ON ft.custom_field_id = f.custom_field_id WHERE ft.user_id = '".$puser_info['id']."'");
            //echo "SELECT ft.*, f.custom_field_name FROM custom_field2text as ft LEFT JOIN custom_field as f ON ft.custom_field_id = f.custom_field_id WHERE ft.user_id = '".$puser_info['id']."'";
            //	    while($fields_info = $pdatabase->database_fetch_assoc($user_fields_query)){
            //	      $puser_info['fields_user'][$fields_info['custom_field_id']] = $fields_info;
            //	    }
            //	Sys::dumpd($puser_info['fields_user']);
            $puser_info['signup'] = date("Y-m-d h:i:s", $puser_info['signup']);
            $puser_info['login'] = date("Y-m-d h:i:s", $puser_info['login']);

            if ($puser_info['gender'] == 1 || $puser_info['gender'] == 328) {
                $puser_info['gender'] = 2;
            } else if ($puser_info['gender'] == 2 || $puser_info['gender'] == 329) {
                $puser_info['gender'] = 3;
            }

            $select = $this->phpfox_db->select()
                ->from(array('u' => $this->phpfox_config['phpfox_prefix'] . 'user_custom_value'))
                ->where('u.user_id = ?', $puser_info['user_id']);


	        $stmt = $this->phpfox_db->query($select);
	        $user_more_info = $stmt->fetch();
	      /*echo "<pre>";
	        print_r($user_more_info);
	        echo "</pre>";*/
            $puser_info['birthday'] = preg_replace('/(\d{2})(\d{2})(\d{4})/is', '$3-$1-$2', $puser_info['birthday']);
//joined  
$creation_date = date('Y-m-d h:i:s', $puser_info['joined']);
            $database->database_query("INSERT INTO 
		          `" . SOCIAL_PREFIX . "users` 
		        (user_id,         
				email,         
				username,         
				displayname,         
				photo_id,         
				status,         
				status_date,         
				password,         
				salt,         
				locale,         
				language,         
				timezone,         
				search,         
				show_profileviewers,         
				level_id,         
				invites_used,          
				enabled,         
				verified,         
				approved,         
				creation_date,         
				creation_ip,         
				modified_date,         
				lastlogin_date,         
				lastlogin_ip,         
				update_date,         
				member_count,         
				view_count)
		        VALUES (
		           NULL, 
		           '" . $puser_info['email'] . "', 
		           '" . $puser_info['user_name'] . "', 
		           '" . $puser_info['full_name'] . "',
		           0, 
		           NULL, 
		           NULL, 
		           'ffa970cb2e03ad96fdf8c12b29cf48c4',  
		           '6925580', 
		           'auto', 
		           'en_US', 
		           'America/Los_Angeles', 
		           1, 
		           1, 
		           4, 
		           0, 
		           0,
		           1, 
		           1, 
		           '" . $creation_date . "', 
		           2130706433, 
		           '" . $creation_date . "', 
		           '0000-00-00 00:00:00', 
		           1270, 
		           NULL, 
		           0, 
		           0
		           )");



            $insert_id = (int) $database->database_insert_id();

            if ($insert_id != 0) {
                $puser_info['full_name'] = explode(' ', $puser_info['full_name']);
                $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "user_fields_values` VALUES (" . $insert_id . ", 1, 0, '1')");
                if (!empty($puser_info['full_name'])) {
                    $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "user_fields_values` VALUES (" . $insert_id . ", 3, 0, '" . $puser_info['full_name'][0] . "')");
                }
                if (!empty($puser_info['full_name'])) {
                    $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "user_fields_values` VALUES (" . $insert_id . ", 4, 0, '" . $puser_info['full_name'][1] . "')");
                }
                if (!empty($puser_info['gender'])) {
                    $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "user_fields_values` VALUES (" . $insert_id . ", 5, 0, '" . $puser_info['gender'] . "')");
                }
                if (!empty($puser_info['birthday'])) {
                    $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "user_fields_values` VALUES (" . $insert_id . ", 6, 0, '" . $puser_info['birthday'] . "')");
                }
                
            	if (!empty($user_more_info['cf_about_me'])) {
                    $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "user_fields_values` VALUES (" . $insert_id . ", 13, 0, '" . $user_more_info['cf_about_me'] . "')");
                }
            }


            $i = $i + 1;
            
          
	        
        }
        
        
        //import friends

        $friends_c = $pdatabase->database_query("SELECT count(*) as cc FROM `" . PHPFOX_PREFIX . "friend`");
        $friend_count = $pdatabase->database_fetch_assoc($friends_c);
        
       // $friends = $pdatabase->database_query("SELECT * FROM  `" . PHPFOX_PREFIX . "friend`");
        
        $friends = $pdatabase->database_query("SELECT f.*, u.user_name as user, u2.user_name as friend FROM
			`" . PHPFOX_PREFIX . "friend` f, " . PHPFOX_PREFIX . "user u, 
			" . PHPFOX_PREFIX . "user u2 WHERE f.friend_user_id = u.user_id AND
			f.user_id = u2.user_id");
        
        
        $i = 1;
        $k = 1000;

        while ($friend_info = $pdatabase->database_fetch_assoc($friends)) {
        	
//        	$select_user = $this->phpfox_db->select()
//                ->from(array('u' => $this->phpfox_config['phpfox_prefix'] . 'user'))
//                ->where('u.user_id = ?', $friend_info['friend_user_id']);
//
//        $stmt = $this->phpfox_db->query($select_user);
//        $results_user = $stmt->fetch();
//        
//        $friend_info['user'] = $results_user['user_name'];
//        
//        $select_friend = $this->phpfox_db->select()
//                ->from(array('u' => $this->phpfox_config['phpfox_prefix'] . 'user'))
//                ->where('u.user_id = ?', $friend_info['user_id']);
//
//        $stmt = $this->phpfox_db->query($select_friend);
//        $results_friend = $stmt->fetch();
//        
//        echo "<pre>";
//        print_r($results_friend);
//        echo "</pre>";
//        
//        $friend_info['friend'] = $results_friend['user_name'];
        	

            $user_info = $database->database_fetch_assoc($database->database_query("SELECT user_id, member_count
		FROM `" . SOCIAL_PREFIX . "users` WHERE username = '" . $friend_info['user'] . "' LIMIT 1"));
            $owner_info = $database->database_fetch_assoc($database->database_query("SELECT user_id
		FROM `" . SOCIAL_PREFIX . "users` WHERE username = '" . $friend_info['friend'] . "' LIMIT 1"));

            $members_count = (int) $user_info['member_count'] + 1;

            $friend_info['user_id'] = (int) $user_info['user_id'];
            $friend_info['friend_id'] = (int) $owner_info['user_id'];

            $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "user_membership`
		(`resource_id`, `user_id`, `active`, `resource_approved`, `user_approved`, `message`, `description`) VALUES
		(" . $friend_info['user_id'] . ", " . $friend_info['friend_id'] . ", 1, 1, 1, NULL, NULL)");
            $database->database_query("UPDATE `" . SOCIAL_PREFIX . "users` SET `member_count` = '" . $members_count . "'
		WHERE `user_id` = " . $friend_info['user_id'] . " LIMIT 1");
            //echo $friend_info['id']."<br/>";
            if ($i == $k) {
                $k = $k + 1000;
            }
            //echo '<pre>'; print_r($friend_info); echo '</pre>';
            $i++;
        }

        $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "core_statistics` (`type`, `date`, `value`) VALUES ('user.creations', '".date("Y-m-d h:i:s")."', ".$i.") ");
        //create folders fo users

        $users = $database->database_query("SELECT * FROM `" . SOCIAL_PREFIX . "users`");

        while ($user_info = $database->database_fetch_assoc($users)) {


            $subdir1 = $user_info['user_id'] + 999999 - (($user_info['user_id'] - 1) % 1000000);
            $subdir2 = $user_info['user_id'] + 999 - (($user_info['user_id'] - 1) % 1000);

            $user_directory = "../public/user/$subdir1/$subdir2/" . $user_info['user_id'] . "/";

            //echo $user_directory . '<br>';
            //$user_directory = $this->url_userdir($this->user_info['user_id']);
            $user_path_array = explode("/", $user_directory);
            array_pop($user_path_array);

            $subdir = "/public/user/$subdir1/$subdir2/";
            //echo "--../public/user/$subdir1/$subdir2/--";
            if (!is_dir($subdir)) {

                mkdir($subdir, 0777);
                chmod($subdir, 0777);
                $handle = fopen($subdir . "index.php", 'x+');
                fclose($handle);
                chmod($subdir . "/index.php", 0777);
            }

            if (!is_dir($user_directory)) {
                mkdir($user_directory, 0777);
                chmod($user_directory, 0777);
                $handle = fopen($user_directory . "/index.php", 'x+');
                fclose($handle);
                chmod($user_directory . "/index.php", 0777);
            }
        }

        //обновляем пароли и выводим на показ
        $users = $database->database_query("SELECT user_id, email, password, username, displayname, salt, level_id FROM `" . SOCIAL_PREFIX . "users`");
        $user_list = array();
        $i = 1;
        while ($user_info = $database->database_fetch_assoc($users)) {

        	if($user_info['level_id'] != 1){
	            $password = (string) rand(10000000, 99999999);
	            //$password = 111;
	            $salt = (string) rand(100000, 999999);
	            //echo $setting_info['value'] . ' - ' . $password . ' - ' . $user_info['salt'].'<br />';
	            $settings = Engine_Api::_()->getApi('settings', 'core');
	            $crypt_password = md5($settings->getSetting('core.secret', 'staticSalt') . $password . $salt);
	            $database->database_query("UPDATE `" . SOCIAL_PREFIX . "users` SET `password` = '" . $crypt_password . "', `salt` = '" . $salt . "' WHERE `user_id` = " . $user_info['user_id'] . " LIMIT 1");
        	
//	$this->view->user_list = $user_list;
//	return ;
            //echo "UPDATE `engine4_users` SET `password` = '".$crypt_password."', `salt` = '".$salt."' WHERE `user_id` = " . $user_info['user_id'] . " LIMIT 1";
            //$message = 

            //$format = 'There are %d monkeys in the %s';
            
			$message = sprintf($this->phpfox_config['send_message'], $password);

            $headers = 'From: info@' . $_SERVER['SERVER_NAME'] . "\r\n" .
                    'Reply-To: info@' . $_SERVER['SERVER_NAME'] . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
        	
	            if((int)$this->phpfox_config['send_password']){
	            	mail($user_info['email'], $_SERVER['SERVER_NAME'] . ' New Password', $message, $headers);
	            }
        	}
            $user_list[$user_info['user_id']]['user_id'] = $user_info['user_id'];
            $user_list[$user_info['user_id']]['email'] = $user_info['email'];
            $user_list[$user_info['user_id']]['username'] = $user_info['username'];
            $user_list[$user_info['user_id']]['displayname'] = $user_info['displayname'];
            
            
            $select_owner = $this->phpfox_db->select()
                ->from(array('u' => $this->phpfox_config['phpfox_prefix'] . 'user'))
               	->where('u.user_name = ?', $user_info['username']);
               	
            $stmt = $this->phpfox_db->query($select_owner);
        	$result_owner = $stmt->fetch();
            
        	
            
            //import avatar
        	$nfile_url = "public/import/" . $insert_id . "/" . $result_owner['user_image']; // .".". $picture_info['extension'] ;

            $url = $this->phpfox_config['phpfox_url'] . 'file/pic/user/';
            $path = "http://".str_replace('%s', '', $url . $result_owner['user_image']);
//echo $path . "<br />";
            
			$img_ext = end(explode(".", $path));
			
			//$path = str_replace($img_ext, "", $path);
			//print_r($path);
            //exit();
            //exit();
//echo "<img src='".$path."'/>";
            @$image = file_get_contents($path);
//var_dump($image);
//exit();
            if (!is_dir("public/import/"))
                mkdir('public/import/', 0777, true);

            if (!is_dir("public/import/" . $user_info['user_id']))
                mkdir("public/import/" . $user_info['user_id'], 0777, true);

            $folder = "public/import/" . $user_info['user_id'];

            //if(!$image)
            //continue;
            $image_name = substr($result_owner['user_image'], 8, (int)strlen($result_owner['user_image']) - 15) . "." . $img_ext;
            $fileot = $folder . "/" . $image_name;
            $fhf = fopen($fileot, "w+");
            fwrite($fhf, $image);
            fclose($fhf);
            $image_path = $fileot;
           // echo $image_path ."<br />";
            //exit();
            try {
            $newuser = Engine_Api::_()->user()->getUser($user_info['user_id']);
            $newuser->setPhoto($image_path);
            }catch(Exception $e){
            	
            }
            
            $privacy = array(
            	//"blockList" => ,
			    "search" => 1,
			    "privacy" => "everyone",
			    "comment" => "registered",
//			    "publishTypes" => array
//			        (
//			            0 => "album_photo_new",
//			            1 => "blog_new",
//			            2 => "classified_new",
//			            3 => "comment_album",
//			            4 => "comment_album_photo",
//			            5 => "comment_blog",
//			            6 => "comment_classified",
//			            7 => "comment_playlist",
//			            8 => "comment_poll",
//			            9 => "comment_video",
//			            10 => "event_create",
//			            11 => "event_join",
//			            12 => "event_photo_upload",
//			            13 => "event_topic_create",
//			            14 => "event_topic_reply",
//			            15 => "forum_promote",
//			            16 => "forum_topic_create",
//			            17 => "forum_topic_reply",
//			            18 => "friends",
//			            19 => "group_create",
//			            20 => "group_join",
//			            21 => "group_photo_upload",
//			            22 => "group_promote",
//			            23 => "group_topic_create",
//			            24 => "group_topic_reply",
//			            25 => "music_playlist_new",
//			            26 => "network_join",
//			            27 => "poll_new",
//			            28 => "post",
//			            29 => "post_self",
//			            30 => "profile_photo_update",
//			            31 => "status",
//			            32 => "tagged",
//			            33 => "video_new",
//        		)
            );
            
            $form = new User_Form_Settings_Privacy(array(
		      'item' => $newuser,
		    ));
		    
            if($form->isValid($privacy)){
		    	$form->save();
            	$newuser->setFromArray($privacy)->save();
            }
        }

        

        $this->view->user_list = $user_list;
    }

    public function importvideoAction() {

        require_once dirname(__FILE__) . '/../libraries/header.php';

        $videos_query = "SELECT * FROM `" . PHPFOX_PREFIX . "video` AS v LEFT JOIN " . PHPFOX_PREFIX . "video_embed AS ve ON v.video_id = ve.video_id LEFT JOIN " . PHPFOX_PREFIX . "video_text AS vt ON v.video_id = vt.video_id LEFT JOIN " . PHPFOX_PREFIX . "user AS u ON v.user_id = u.user_id";

        $videos = $pdatabase->database_query($videos_query);

        $i = 1;
        while ($video_info = $pdatabase->database_fetch_assoc($videos)) {

            $user_query = "SELECT user_id FROM " . SOCIAL_PREFIX . "users WHERE username = '" . $video_info['user_name'] . "' LIMIT 1";

            $user_info = $database->database_fetch_assoc($database->database_query($user_query));

            $video_info['seuser_id'] = (int) $user_info['user_id'];
            $video_info['dateadd'] = date("Y-m-d h:i:s", $video_info['time_stamp']);
            $video_info['title'] = addslashes($video_info['title']);
            $video_info['description'] = addslashes($video_info['text']);



            //	$file_url = "public/import/". $user_info['user_id'] ."/" .$user_info['username']."-". $picture_info['file_name']; //.".". $picture_info['extension'] ;
            //	$nfile_url = "public/import/". $user_info['user_id'] ."/" .$user_info['username']."-". $picture_info['file_name'];// .".". $picture_info['extension'] ;
            //							
            //	$url = Engine_Api::_()->getApi('settings', 'core')->getSetting('phpfox_url') . '/file/pic/photo/';
            //	$path = str_replace('%s', '', $url.$picture_info['destination']);
            //							
            $url = Engine_Api::_()->getApi('settings', 'core')->getSetting('phpfox_url') . 'file/pic/video/';
            $path = "http://".str_replace('%s', '', $url . $video_info['image_path']);

            $path = substr($path, 0, strlen($path) - 4);
            $path = $path . "_120.jpg";


            @$image = file_get_contents($path);

            if (!is_dir("public/import/"))
                mkdir('public/import/', 0777, true);

            if (!is_dir("public/import/" . $user_info['user_id']))
                mkdir("public/import/" . $user_info['user_id'], 0777, true);

            $folder = "public/import/" . $user_info['user_id'];

            $lenth = (int) mb_strlen($video_info['image_path']);

            $fname = substr($video_info['image_path'], 8, $lenth - 15) . ".jpg";
            $thumb_url = "public/import/" . $user_info['user_id'] . "/" . $fname;

//		$thumb_url = "../../thumbs/".$video_info['video'].".jpg";
//		$file_url = '../../videos/'.$video_info['video'].'.flv';

            $folder = "public/import/" . $user_info['user_id'];

            $fileot = $folder . "/" . $fname;
            $fhf = fopen($fileot, "w+");
            fwrite($fhf, $image);
            fclose($fhf);



            $category_id = 0;


            if ($video_info['seuser_id'] != 0) {

                $pre_code = str_replace("http://www.youtube.com/watch?v=", "", $video_info['video_url']);
                $code = str_replace("&feature=feedu", "", $pre_code);
                // $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "storage_files` (`file_id`, `parent_file_id`, `type`, `parent_type`, `parent_id`, `user_id`, `creation_date`, `modified_date`, `storage_type`, `storage_path`, `extension`, `name`, `mime_major`, `mime_minor`, `size`, `hash`) VALUES
                // (NULL, NULL, NULL, 'video', 1, ".$video_info['seuser_id'].", '".$video_info['vid_time']."', '".$video_info['vid_time']."', 'local', '".$file_url."', 'flv', 'link_thumb_acec2184c52ce2a6fc397186223e7dfd.jpg', 'image', 'jpeg', 2390, 'f5b0bd2bdf4bab617b4a681f157058bf')");
                // $file_id = (int)$database->database_insert_id();

                $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "storage_files` VALUES (NULL, NULL, NULL, 'video', 1, " . $user_info['user_id'] . ", NOW(), NOW(), '1', '" . $thumb_url . "', 'jpg', '" . $fname . "', 'image', 'jpeg', 44948, '6590d2cd4a8e25477cf924812fe85fd9')");
                $video_id = (int) $database->database_insert_id();

                $insert_video_query = "INSERT INTO `" . SOCIAL_PREFIX . "video_videos` (`video_id`, `title`, `description`, `search`, `owner_type`, `owner_id`, `creation_date`, `modified_date`, `view_count`, `comment_count`, `type`, `code`, `photo_id`, `rating`, `category_id`, `status`, `file_id`, `duration`) VALUES
		  (NULL, '" . $video_info['title'] . "', '" . $video_info['description'] . "', 1, '', " . $video_info['seuser_id'] . ", '" . $video_info['dateadd'] . "', '" . $video_info['dateadd'] . "', 0, 0, 1, '" . $code . "', " . $video_id . ", 0, " . $category_id . ", 1, 0, 0)";

                $database->database_query($insert_video_query);
//echo "thumb_url: ".$thumb_url."<br />";
//echo "thumb_id: ".$video_id ."<br />";
//print_r($image);
//exit();
                $video_id_new = (int) $database->database_insert_id();

                //-----
                $comments_sql = "SELECT c.*, u.*, ct.* FROM `" . PHPFOX_PREFIX . "comment` AS c inner JOIN `" . PHPFOX_PREFIX . "user` AS u ON c.user_id = u.user_id LEFT JOIN `" . PHPFOX_PREFIX . "comment_text` AS ct ON c.comment_id = ct.comment_id   WHERE c.type_id = 'video' AND c.item_id = " . $video_info['video_id'] . " order by c.item_id desc";


                $comments_query = $pdatabase->database_query($comments_sql);


                while ($comments_info = $pdatabase->database_fetch_assoc($comments_query)) {


                    $user_info = $database->database_fetch_assoc($database->database_query("SELECT user_id FROM " . SOCIAL_PREFIX . "users WHERE username = '" . $comments_info['user_name'] . "' LIMIT 1"));

                    $comments_info['user_id'] = (int) $user_info['user_id'];
                    $comments_info['comment'] = $comments_info['text'];
                    $comments_info['date'] = date("Y-m-d h:i:s", $comments_info['time_stamp']);


                    $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "core_comments` (`comment_id`, `resource_type`, `resource_id`, `poster_type`, `poster_id`, `body`, `creation_date`) VALUES
											(NULL, 'video', " . $video_id_new . ", 'user', " . $comments_info['user_id'] . ", '" . $comments_info['comment'] . "', '" . $comments_info['date'] . "')");
                }
                //-----
            } else {

                $importvideo = false;
            }

            $importvideo = true;

            $i++;
        }
        $this->view->importvideo = $importvideo;
    }

    public function importeventsAction() {

        require_once dirname(__FILE__) . '/../libraries/header.php';

        $events_query = "SELECT * FROM `" . PHPFOX_PREFIX . "event` AS e LEFT JOIN " . PHPFOX_PREFIX . "event_text AS et ON e.event_id = et.event_id LEFT JOIN " . PHPFOX_PREFIX . "user AS u ON e.user_id = u.user_id";

        $events = $pdatabase->database_query($events_query);

        $i = 1;
        while ($event_info = $pdatabase->database_fetch_assoc($events)) {

            $user_query = "SELECT user_id FROM " . SOCIAL_PREFIX . "users WHERE username = '" . $event_info['user_name'] . "' LIMIT 1";

            $user_info = $database->database_fetch_assoc($database->database_query($user_query));

            $event_info['seuser_id'] = (int) $user_info['user_id'];
            $event_info['dateadd'] = date("Y-m-d h:i:s", $event_info['time_stamp']);
            $event_info['title'] = addslashes($event_info['title']);
            $event_info['description'] = addslashes($event_info['description']);

            $url = Engine_Api::_()->getApi('settings', 'core')->getSetting('phpfox_url') . '/file/pic/event/';
            $path = "http://".str_replace('%s', '', $url . $event_info['image_path']);

            $path = mb_substr($path, 0, strlen($path) - 4);
            $jpgpath = $path . ".jpg";


            @$image = file_get_contents($jpgpath);
            if(!$image){
            	$pngpath = $path . ".png";
            	@$image = file_get_contents($pngpath);
            }

            if (!is_dir("public/import/"))
                mkdir('public/import/', 0777, true);

            if (!is_dir("public/import/" . $user_info['user_id']))
                mkdir("public/import/" . $user_info['user_id'], 0777, true);

            $lenth = (int) mb_strlen($event_info['image_path']);

            $fname = substr($event_info['image_path'], 8, $lenth - 15) . ".jpg";
            $thumb_url = "public/import/" . $user_info['user_id'] . "/" . $fname;


//		$thumb_url = "../../thumbs/".$video_info['video'].".jpg";
//		$file_url = '../../videos/'.$video_info['video'].'.flv';

            $folder = "public/import/" . $user_info['user_id'];

            $fileot = $folder . "/" . $fname;
            $fhf = fopen($fileot, "w+");
            fwrite($fhf, $image);
            fclose($fhf);

            $category_id = 0;

            if ($event_info['seuser_id'] != 0) {


                //$database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "storage_files` (`file_id`, `parent_file_id`, `type`, `parent_type`, `parent_id`, `user_id`, `creation_date`, `modified_date`, `storage_type`, `storage_path`, `extension`, `name`, `mime_major`, `mime_minor`, `size`, `hash`) VALUES
                //(NULL, NULL, NULL, 'video', 1, ".$video_info['seuser_id'].", '".$video_info['vid_time']."', '".$video_info['vid_time']."', 'local', '".$file_url."', 'flv', 'link_thumb_acec2184c52ce2a6fc397186223e7dfd.jpg', 'image', 'jpeg', 2390, 'f5b0bd2bdf4bab617b4a681f157058bf')");
                //$file_id = (int)$database->database_insert_id();

                $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "storage_files` VALUES (NULL, NULL, NULL, 'event', 1, " . $user_info['user_id'] . ", NOW(), NOW(), '1', '" . $thumb_url . "', 'jpg', '" . $fname . "', 'image', 'jpeg', 44948, '6590d2cd4a8e25477cf924812fe85fd9')");
                $photo_id = (int) $database->database_insert_id();

                $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "storage_files` VALUES (NULL, NULL, thumb.profile, 'event', 1, " . $user_info['user_id'] . ", NOW(), NOW(), '1', '" . $thumb_url . "', 'jpg', '" . $fname . "', 'image', 'jpeg', 44948, '6590d2cd4a8e25477cf924812fe85fd9')");
                $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "storage_files` VALUES (NULL, NULL, thumb.normal, 'event', 1, " . $user_info['user_id'] . ", NOW(), NOW(), '1', '" . $thumb_url . "', 'jpg', '" . $fname . "', 'image', 'jpeg', 44948, '6590d2cd4a8e25477cf924812fe85fd9')");
                $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "storage_files` VALUES (NULL, NULL, thumb.icon, 'event', 1, " . $user_info['user_id'] . ", NOW(), NOW(), '1', '" . $thumb_url . "', 'jpg', '" . $fname . "', 'image', 'jpeg', 44948, '6590d2cd4a8e25477cf924812fe85fd9')");

                $start_time = date("Y-m-d h:i:s", $event_info['start_time']);
                $end_time = date("Y-m-d h:i:s", $event_info['end_time']);

                $insert_event_query = "INSERT INTO `" . SOCIAL_PREFIX . "event_events` (
																	event_id,         
																	title,         
																	description,         
																	user_id,         
																	parent_type,         
																	parent_id,         
																	search,         
																	creation_date,         
																	modified_date,         
																	starttime,         
																	endtime,         
																	host,         
																	location,         
																	view_count,         
																	member_count,         
																	approval,         
																	invite,         
																	photo_id,         
																	category_id ) 
		  														VALUES
		  															(NULL, 
		  															'" . $event_info['title'] . "', 
		  															'" . $event_info['description'] . "', 
		  															" . $event_info['seuser_id'] . ", 
		  															'user',
		  															" . $event_info['seuser_id'] . ",
		  															1,
		  															'" . $event_info['dateadd'] . "', 
		  															'" . $event_info['dateadd'] . "', 
		  															'" . $start_time . "', 
		  															'" . $end_time . "',
		  															'" . $event_info['host'] . "', 
		  															'" . $event_info['country_iso'] . " " . $event_info['location'] . "', 
		  															0,
		  															1,
		  															1,
		  															0,
		  															" . $photo_id . ",
		  															0)";



                $database->database_query($insert_event_query);



                $event_id_new = (int) $database->database_insert_id();
                
                
                
                //-----
                $comments_sql = "SELECT c.*, u.*, ct.* FROM `" . PHPFOX_PREFIX . "comment` AS c inner JOIN `" . PHPFOX_PREFIX . "user` AS u ON c.user_id = u.user_id LEFT JOIN `" . PHPFOX_PREFIX . "comment_text` AS ct ON c.comment_id = ct.comment_id   WHERE c.type_id = 'event' AND c.item_id = " . $event_info['event_id'] . " order by c.item_id desc";


                $comments_query = $pdatabase->database_query($comments_sql);


                while ($comments_info = $pdatabase->database_fetch_assoc($comments_query)) {


                    $user_info = $database->database_fetch_assoc($database->database_query("SELECT user_id FROM " . SOCIAL_PREFIX . "users WHERE username = '" . $comments_info['user_name'] . "' LIMIT 1"));

                    $comments_info['user_id'] = (int) $user_info['user_id'];
                    $comments_info['comment'] = $comments_info['text'];
                    $comments_info['date'] = date("Y-m-d h:i:s", $comments_info['time_stamp']);

				
                    $insert_comment_query = "INSERT INTO `" . SOCIAL_PREFIX . "activity_actions` (         
											type,         
											subject_type,         
											subject_id,         
											object_type,         
											object_id,         
											body,         
											params,         
											date,         
											attachment_count,         
											comment_count,         
											like_count     
			       						) VALUES( 
											'post',
											'user',
											".$user_info['user_id'].",
											'event', 
											" . $event_id_new . ",
											'" . $comments_info['comment'] . "', 
											'[]', 
											'" . $comments_info['date'] . "',
											0,
											0,
											0)";
                    $database->database_query($insert_comment_query);
                    
                    $action_id = (int) $database->database_insert_id();
                    
                    $insert_activity1_query = "INSERT INTO `" . SOCIAL_PREFIX . "activity_stream` (         
											target_type,         
											target_id,         
											subject_type,         
											subject_id,         
											object_type,         
											object_id,         
											type,         
											action_id    
			       						) VALUES( 
											'owner',
											2,
											'user',
											".$user_info['user_id'].", 
											'event',
											" .$event_id_new . ", 
											'post', 
											" . $action_id . ")";
                    $database->database_query($insert_activity1_query);
                    
                    $insert_activity2_query = "INSERT INTO `" . SOCIAL_PREFIX . "activity_stream` (         
											target_type,         
											target_id,         
											subject_type,         
											subject_id,         
											object_type,         
											object_id,         
											type,         
											action_id    
			       						) VALUES( 
											'parent',
											6,
											'user',
											".$user_info['user_id'].", 
											'event',
											" .$event_id_new . ", 
											'post', 
											" . $action_id . ")";
                    $database->database_query($insert_activity2_query);
                }


                $invite_sql = "SELECT * FROM `" . PHPFOX_PREFIX . "event_invite` AS i inner JOIN `" . PHPFOX_PREFIX . "user` AS u ON i.invited_user_id = u.user_id    WHERE i.event_id = " . $event_info['event_id'];


                $invite_query = $pdatabase->database_query($invite_sql);

//                 $select_inv = $this->phpfox_db->select()
//		                ->from(array('e' => $this->phpfox_config['phpfox_prefix'] . 'event_invite'))
//		                ->where('e.event_id = ?', $event_info['event_id']);
//		        $stmt_inv = $this->phpfox_db->query($select_inv);
//		        $results = $stmt_inv->fetchAll();
//
//		        
//		        echo "<pre>";
//		        print_r($results);
//		        echo "</pre>";

                while ($invite_info = $pdatabase->database_fetch_assoc($invite_query)) {
//echo "<pre>";
//print_r($invite_info);
//echo "</pre>";
                    $seuser_info = $database->database_fetch_assoc($database->database_query("SELECT user_id FROM " . SOCIAL_PREFIX . "users WHERE username = '" . $invite_info['user_name'] . "' LIMIT 1"));

                    $invite_info['user_id'] = (int) $seuser_info['user_id'];

                    $rsvp_id = 2; 
                    
                    if($invite_info['rsvp_id'] == 2)$rsvp_id = 1;
                    
                    $member_query = "INSERT INTO `" . SOCIAL_PREFIX . "event_membership` 
			      	 (resource_id,      
						user_id,         
						active,         
						resource_approved,         
						user_approved,         
						message,         
						rsvp,         
						title
			       ) VALUES
						(" . $event_id_new . ", 
						" . $seuser_info['user_id'] . ", 
						1, 
						1, 
						1, 
						'',
						".$rsvp_id.", 
						'')";

                    $database->database_query($member_query);
                }
                //-----
            }
//            exit();

            $i++;
        }
        $this->view->importevents = true;
    }

    public function importprofilecommentsAction() {

        require_once dirname(__FILE__) . '/../libraries/header.php';

        $database->database_query("TRUNCATE TABLE `" . SOCIAL_PREFIX . "activity_actions`");
        $database->database_query("TRUNCATE TABLE `" . SOCIAL_PREFIX . "activity_stream`");

        $comments = $pdatabase->database_query("SELECT cc.text, c.user_id, c.owner_user_id 'itemid', u.email, c.owner_user_id 'item_user_id', c.*
FROM `" . PHPFOX_PREFIX . "comment_text` cc , `" . PHPFOX_PREFIX . "comment` AS c inner JOIN `" . PHPFOX_PREFIX . "user` AS u ON c.user_id = u.user_id
WHERE c.type_id = 'profile' AND cc.comment_id=c.comment_id ORDER BY c.time_stamp ASC");

        $i = 1;

        $import_comments = array();
        while ($comment_info = $pdatabase->database_fetch_assoc($comments)) {
//	6lhaqw

            $pitem_info = $pdatabase->database_fetch_assoc($pdatabase->database_query("SELECT * FROM `" . PHPFOX_PREFIX . "user` WHERE `user_id` = '" . $comment_info['itemid'] . "' LIMIT 1"));
            $item_info = $database->database_fetch_assoc($database->database_query("SELECT user_id, email, username FROM `" . SOCIAL_PREFIX . "users` WHERE `email` = '" . $pitem_info['email'] . "' LIMIT 1"));

            $user_info = $database->database_fetch_assoc($database->database_query("SELECT user_id, email, username FROM `" . SOCIAL_PREFIX . "users` WHERE `email` = '" . $comment_info['email'] . "' LIMIT 1"));

            $comment_info['item_user_id'] = (int) $item_info['user_id'];
            $comment_info['user_id'] = (int) $user_info['user_id'];

      
            if(!empty($comment_info['time_stamp']))
            	$comment_info['date'] = date("Y-m-d h:i:s", $comment_info['time_stamp']);
            else 
            	$comment_info['date'] = date("Y-m-d h:i:s", $comment_info['time']);
            $comment_info['text'] = addslashes($comment_info['text']);
            
            $comment_info['text'] = str_replace("[b]", "", $comment_info['text']);
            $comment_info['text'] = str_replace("[/b]", "", $comment_info['text']);
            $comment_info['text'] = str_replace("[img]", "", $comment_info['text']);
            $comment_info['text'] = str_replace("[/img]", "", $comment_info['text']);
            $comment_info['text'] = str_replace("[quote=10]", "", $comment_info['text']);
            $comment_info['text'] = str_replace("[/quote]", "", $comment_info['text']);
            
            //$comment_info['item_user_id'] = $user_comment['user_id'];
			
            if (($comment_info['user_id'] != 0) && ($comment_info['item_user_id'] != 0)) {

                $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "activity_actions` (`action_id`, `type`, `subject_type`, `subject_id`, `object_type`, `object_id`, `body`, `params`, `date`, `attachment_count`, `comment_count`, `like_count`) VALUES
									(NULL, 'post', 'user', " . $comment_info['user_id'] . ", 'user', " . $comment_info['item_user_id'] . ", '" . $comment_info['text'] . "', '[]', '" . $comment_info['date'] . "', 0, 0, 0)");
                $action_id = (int) $database->database_insert_id();

                $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "activity_stream` (`target_type`, `target_id`, `subject_type`, `subject_id`, `object_type`, `object_id`, `type`, `action_id`) VALUES
									('everyone', 0, 'user', " . $comment_info['user_id'] . ", 'user', " . $comment_info['item_user_id'] . ", 'post', " . $action_id . ")");

                $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "activity_stream` (`target_type`, `target_id`, `subject_type`, `subject_id`, `object_type`, `object_id`, `type`, `action_id`) VALUES
									('owner', " . $comment_info['user_id'] . ", 'user', " . $comment_info['user_id'] . ", 'user', " . $comment_info['item_user_id'] . ", 'post', " . $action_id . ")");
                $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "activity_stream` (`target_type`, `target_id`, `subject_type`, `subject_id`, `object_type`, `object_id`, `type`, `action_id`) VALUES
									('parent', " . $comment_info['item_user_id'] . ", 'user', " . $comment_info['user_id'] . ", 'user', " . $comment_info['item_user_id'] . ", 'post', " . $action_id . ")");
            }

            $import_comments[$i]['item_user_id'] = $comment_info['item_user_id'];
            $import_comments[$i]['user_id'] = $comment_info['user_id'];
            $import_comments[$i]['date'] = $comment_info['date'];
            $import_comments[$i]['text'] = $comment_info['text'];

            $i++;
        }

//	Sys::dumpd($import_comments);
        $this->view->import_comments = $import_comments;
    }

    public function importpermissionsAction() {

        require_once dirname(__FILE__) . '/../libraries/header.php';

        $users = $database->database_query("SELECT user_id FROM `" . SOCIAL_PREFIX . "users`");
        while ($user_info = $database->database_fetch_assoc($users)) {
            $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "authorization_allow` VALUES ('user', " . (int) $user_info['user_id'] . ", 'view', 'everyone', 0, 1, NULL)");
            $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "authorization_allow` VALUES ('user', " . (int) $user_info['user_id'] . ", 'comment', 'everyone', 0, 1, NULL)");
        }


        $videos = $database->database_query("SELECT video_id FROM `" . SOCIAL_PREFIX . "video_videos`");
        while ($video_info = $database->database_fetch_assoc($videos)) {
            $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "authorization_allow` VALUES ('video', " . (int) $video_info['video_id'] . ", 'view', 'everyone', 0, 1, NULL)");
            $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "authorization_allow` VALUES ('video', " . (int) $video_info['video_id'] . ", 'comment', 'everyone', 0, 1, NULL)");
        }


        $blogs = $database->database_query("SELECT blog_id FROM `" . SOCIAL_PREFIX . "blog_blogs`");
        while ($blog_info = $database->database_fetch_assoc($blogs)) {
            $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "authorization_allow` VALUES ('blog', " . (int) $blog_info['blog_id'] . ", 'view', 'everyone', 0, 1, NULL)");
            $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "authorization_allow` VALUES ('blog', " . (int) $blog_info['blog_id'] . ", 'comment', 'everyone', 0, 1, NULL)");
        }

        $albums = $database->database_query("SELECT album_id FROM `" . SOCIAL_PREFIX . "album_albums`");
        while ($album_info = $database->database_fetch_assoc($albums)) {
            $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "authorization_allow` VALUES ('album', " . (int) $album_info['album_id'] . ", 'view', 'everyone', 0, 1, NULL)");
            $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "authorization_allow` VALUES ('album', " . (int) $album_info['album_id'] . ", 'comment', 'everyone', 0, 1, NULL)");
        }

        $photos = $database->database_query("SELECT photo_id FROM `" . SOCIAL_PREFIX . "album_photos`");
        while ($photo_info = $database->database_fetch_assoc($photos)) {
            $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "authorization_allow` VALUES ('album_photo', " . (int) $photo_info['photo_id'] . ", 'view', 'everyone', 0, 1, NULL)");
            $database->database_query("INSERT INTO `" . SOCIAL_PREFIX . "authorization_allow` VALUES ('album_photo', " . (int) $photo_info['photo_id'] . ", 'comment', 'everyone', 0, 1, NULL)");
        }
    }

    
    function importmessagesAction() {
    	
    	$select = $this->phpfox_db->select()
                ->from(array('m' => $this->phpfox_config['phpfox_prefix'] . 'mail'))
                ->join(array('mt' => $this->phpfox_config['phpfox_prefix'] . 'mail_text'), 'm.mail_id = mt.mail_id');
                //->join(array('uowner' => $this->phpfox_config['phpfox_prefix'] . 'user'), 'uowner.user_id = m.owner_user_id')
                //->join(array('urecipient' => $this->phpfox_config['phpfox_prefix'] . 'user'), 'urecipient.user_id = m.viewer_user_id');
                


        $stmt = $this->phpfox_db->query($select);
        $results = $stmt->fetchAll();
    	
    	
    	
    	foreach ($results as $result){
		    	// Get navigation
		
    		$select_owner = $this->phpfox_db->select()
                ->from(array('uowner' => $this->phpfox_config['phpfox_prefix'] . 'user'))
               	->where('uowner.user_id = ?', $result['owner_user_id']);

        	$stmt_owner = $this->phpfox_db->query($select_owner); 
        	$result_owner = $stmt_owner->fetch();
        	
    		$users_table = Engine_Api::_()->getItemTable('user');
            $userTableName = $users_table->info('name');
            $users_select = $users_table->select()->where($userTableName . '.username = ?', $result_owner['user_name']);
            $user_result = $users_select->query();
            $user_owner = $user_result->fetch();
            
            $select_recipient = $this->phpfox_db->select()
                ->from(array('uowner' => $this->phpfox_config['phpfox_prefix'] . 'user'))
               	->where('uowner.user_id = ?', $result['viewer_user_id']);

        	$stmt_recipient = $this->phpfox_db->query($select_recipient); 
        	$result_recipient = $stmt_recipient->fetch();
        	
    		$users_table = Engine_Api::_()->getItemTable('user');
            $userTableName = $users_table->info('name');
            $users_select = $users_table->select()->where($userTableName . '.username = ?', $result_recipient['user_name']);
            $user_result = $users_select->query();
            $user_recipient = $user_result->fetch();
    		
            
    		//exit();
    		
		    // Make form
		    //$form->setAction($this->view->url(array('to' => null, 'multi' => null)));
		
		    // Get params
		    $to = $user_recipient['user_id'];
		    $viewer = $this->_helper->api()->user()->getUser($user_owner['user_id']);
		    $toObject = null;
		
				
		    
		    
		    $multi = null;
		      // Prepopulate user
		      $toUser = Engine_Api::_()->getItem('user', $to);
		      if( $toUser instanceof User_Model_User &&
		          !$viewer->isBlockedBy($toUser) ) {
		        $this->view->toObject = $toObject = $toUser;
		        //$form->toValues->setValue($toUser->getGuid());
		        $isPopulated = true;
		      } else {
		        $multi = null;
		        $to = null;
		      }
		    
		      //print_r($to);
		      //exit();
		    // Assign the composing stuff
		    $composePartials = array();
		    foreach( Zend_Registry::get('Engine_Manifest') as $data )
		    {
		      if( empty($data['composer']) ) continue;
		      foreach( $data['composer'] as $type => $config )
		      {
		        $composePartials[] = $config['script'];
		      }
		    }
		 
		
		
		    // Check method/data
//		    if( !$this->getRequest()->isPost() ) {
//		      return;
//		    }
//		
//		    if( !$form->isValid($this->getRequest()->getPost()) ) {
//		      return;
//		    }
//		
		    // Process
		    $db = Engine_Api::_()->getDbtable('messages', 'messages')->getAdapter();
		    //$db->beginTransaction();
		
		    try {
//		      // Try attachment getting stuff
		      $attachment = null;
//		      $attachmentData = $this->getRequest()->getParam('attachment');
//		      if( !empty($attachmentData) && !empty($attachmentData['type']) ) {
//		        $type = $attachmentData['type'];
//		        $config = null;
//		        foreach( Zend_Registry::get('Engine_Manifest') as $data )
//		        {
//		          if( !empty($data['composer'][$type]) )
//		          {
//		            $config = $data['composer'][$type];
//		          }
//		        }
//		        if( $config ) {
//		          $plugin = Engine_Api::_()->loadClass($config['plugin']);
//		          $method = 'onAttach'.ucfirst($type);
//		          $attachment = $plugin->$method($attachmentData);
//		          $parent = $attachment->getParent();
//		          if($parent->getType() === 'user'){
//		            $attachment->search = 0;
//		            $attachment->save();
//		          }
//		          else {
//		            $parent->search = 0;
//		            $parent->save();
//		          }
//		        }
//		      }
		      
		      $viewer = $this->_helper->api()->user()->getUser($user_owner['user_id']);
		      $values = array('title' => $result['subject'], 'body' => $result['text']);
		
		      // Prepopulated
		      if( $toObject instanceof User_Model_User ) {
		      	
		        $recipientsUsers = array($toObject);
		        $recipients = $toObject;
		      } else if( $toObject instanceof Core_Model_Item_Abstract &&
		          method_exists($toObject, 'membership') ) {
		        $recipientsUsers = $toObject->membership()->getMembers();
		//        $recipients = array();
		//        foreach( $recipientsUsers as $recipientsUser ) {
		//          $recipients[] = $recipientsUser->getIdentity();
		//        }
		        $recipients = $toObject;
		      }
		      // Normal
		      else {
		        $recipients = preg_split('/[,. ]+/', $values['toValues']);
		        // clean the recipients for repeating ids
		        // this can happen if recipient is selected and then a friend list is selected
		        $recipients = array_unique($recipients);
		        // Slice down to 10
		        $recipients = array_slice($recipients, 0, 1);
		        $recipientsUsers = Engine_Api::_()->getItemMulti('user', $recipients);
		      }
		
		 
		      // Create conversation
		      $conversation = Engine_Api::_()->getItemTable('messages_conversation')->send(
		        $viewer,
		        $recipients,
		        $values['title'],
		        $values['body']
		      );
		
		      // Send notifications
		      /*foreach( $recipientsUsers as $user ) {
		        if( $user->getIdentity() == $viewer->getIdentity() ) {
		        	
		          continue;
		        }
		        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification(
		          $user,
		          $viewer,
		          $conversation,
		          'message_new'
		        );
		      }*/
		
		      // Increment messages counter
		      Engine_Api::_()->getDbtable('statistics', 'core')->increment('messages.creations');
		
		      // Commit
		     // $db->commit();
		    } catch( Exception $e ) {
		   //   $db->rollBack();
		   //   echo $e;die();
		   //   throw $e;
		    }
    		
    	
    	
    	
    	
    	}
    }

    function importalbumsAction() {

        
        $select = $this->phpfox_db->select()
                ->from(array('p' => $this->phpfox_config['phpfox_prefix'] . 'photo'))
                ->join(array('pi' => $this->phpfox_config['phpfox_prefix'] . 'photo_info'), 'p.photo_id = pi.photo_id')
                ->join(array('u' => $this->phpfox_config['phpfox_prefix'] . 'user'), 'u.user_id = p.user_id')
					 //->order(array('photo_id ASC'))
					->where('p.photo_id > ?', 400);
//					->where('p.photo_id <= ?', 400);
//->limit(50);



        $stmt = $this->phpfox_db->query($select);
        $results = $stmt->fetchAll();
        
        
        $table = Engine_Api::_()->getDbtable('albums', 'album');
        $db = $table->getAdapter();
        $db->beginTransaction();
        
        foreach ($results as $result) {
            $users_table = Engine_Api::_()->getItemTable('user');

            $userTableName = $users_table->info('name');

            $users_select = $users_table->select()->where($userTableName . '.username = ?', $result['user_name']);

            $user_result = $users_select->query();
            $user = $user_result->fetch();
            
            
            $nfile_url = "public/import/" . $user['user_id'] . "/" . $results['file_name']; // .".". $picture_info['extension'] ;

            $url = $this->phpfox_config['phpfox_url'] . '/file/pic/photo/';
            $path = "http://".str_replace('%s', '', $url . $result['destination']);

            @$image = file_get_contents($path);

            if (!is_dir("public/import/"))
                mkdir('public/import/', 0777, true);

            if (!is_dir("public/import/" . $user['user_id']))
                mkdir("public/import/" . $user['user_id'], 0777, true);

            $folder = "public/import/" . $user['user_id'];

          
            
            $image_name = (string) rand(10000000, 99999999);// . ".jpg";
            $fileot = $folder . "/" . $image_name;
            
         
            
            $fhf = fopen($fileot, "w+");
            fwrite($fhf, $image);
            fclose($fhf);
            $image_path = $fileot;
            
            chmod($fileot, 0777);
            
            
            if($result['album_id'] == 0){
                $album_title = 'unsorted';
            }else{
               $old_select_albums = $this->phpfox_db->select()
                ->from(array('pa' => $this->phpfox_config['phpfox_prefix'] . 'photo_album'))
                ->join(array('ai' => $this->phpfox_config['phpfox_prefix'] . 'photo_album_info'), 'pa.album_id = ai.album_id')
                ->where('pa.album_id = ?', $result['album_id']);
                
                $old_album_stmt = $this->phpfox_db->query($old_select_albums);
                $old_album_results = $old_album_stmt->fetchAll();
                $album_title = $old_album_results[0]['name'];
                
            }
            
            
            $tableAlbum = Engine_Api::_()->getDbtable('albums', 'album');
            $tableAlbumNmae = $tableAlbum->info('name');

            $tableAlbumSelect = $tableAlbum->select()
				->where($tableAlbumNmae . '.title = ?', $album_title)
				->where($tableAlbumNmae . '.owner_id = ?', $user['user_id']);

            $tableAlbumResult = $tableAlbumSelect->query();
            $albums = $tableAlbumResult->fetchAll();
            
           
            
            if(!count($albums)){
                $album_id = 0;
            }else{
                $album_id = $albums[0]['album_id'];
            }
                $form = new Album_Form_Album();

                $value = array(
                    'album' => $album_id,
                    'title' => $album_title,
                    'category_id' => 0,
                    'description' => '',
                    'search' => 1,
                    'auth_view' => 'everyone',
                    'auth_comment' => 'everyone',
                    'auth_tag' => 'everyone',
                    //'file' => '',
                );
           
                $form->isValid($value);
                $album = $form->saveValues();
            
                $file = array(
                    'tmp_name' => $image_path,
                    'name' => $image_name . ".jpg",
                );
                
                try{
                $photo = Engine_Api::_()->album()->createPhoto(array(
                      'owner_type' => 'user',
                      'owner_id' => $user['user_id']
                      ),$file);
                }catch(Exception $e){
                	continue;
                }

              $album->owner_id = $user['user_id'];
              $album->save(); 
              
              $photo->collection_id = $album->album_id;
              $photo->save();
 
              
            $tablePhoto = Engine_Api::_()->getItemTable('photo');
            $tablePhotoSelect = $tablePhoto->select()->order(array('photo_id DESC'));
            $tablePhotoResult = $tablePhotoSelect->query();
            $photo_ = $tablePhotoResult->fetch();

            $item_id = $photo_['photo_id'];
           

            $select_comment = $this->phpfox_db->select()
                            ->from(array('c' => $this->phpfox_config['phpfox_prefix'] . 'comment'))
                            ->join(array('ct' => $this->phpfox_config['phpfox_prefix'] . 'comment_text'), 'c.comment_id = ct.comment_id')
                            ->join(array('u' => $this->phpfox_config['phpfox_prefix'] . 'user'), 'u.user_id = c.user_id')
                            ->where('c.item_id = ?', $result['photo_id'])->where('type_id = ?', 'photo');

            $stmt_com = $this->phpfox_db->query($select_comment);
            $comment_results = $stmt_com->fetchAll();

        

            foreach ($comment_results as $comment_result) {

                $users_comment_table = Engine_Api::_()->getItemTable('user');
                $userCommentTableName = $users_comment_table->info('name');
                $users_comment_select = $users_comment_table->select()->where($userCommentTableName . '.username = ?', $comment_result['user_name']);
                $user_comment_result = $users_comment_select->query();
                $user_comment = $user_comment_result->fetch();
                $comment_table = Engine_Api::_()->getDbtable('comments', 'core');

                //print_r($comment_table->info('name')); exit();
                $commentTableName = $comment_table->info('name');

                $data = array(
                    'resource_type' => 'album_photo',
                    'resource_id' => $item_id,
                    'poster_type' => 'user',
                    'poster_id' => $user_comment['user_id'],
                    'body' => $comment_result['text'],
                    'creation_date' => date('Y-m-d h:i:s', $comment_result['time_stamp']),
                    'like_count' => 0);

                $comment_table->insert($data);
            }
        
        }
        
        $db->commit();
        
        
        
        return;
        
    }

    function importalbumscommentsAction() {

        require_once dirname(__FILE__) . '/../libraries/header.php';

        $comments_sql = "SELECT c.*, u.*, ct.*, pi.* FROM `" . PHPFOX_PREFIX . "comment` AS c inner JOIN `" . PHPFOX_PREFIX . "user` AS u ON c.user_id = u.user_id LEFT JOIN `" . PHPFOX_PREFIX . "comment_text` AS ct ON c.comment_id = ct.comment_id LEFT JOIN `" . PHPFOX_PREFIX . "photo_info` AS pi ON pi.photo_id = c.item_id  WHERE c.type_id = 'photo' order by c.item_id desc";
        $comments = $pdatabase->database_query($comments_sql);

        $i = 1;

        while ($comment_info = $pdatabase->database_fetch_assoc($comments)) {


            $file_info_sql = "SELECT f.file_id, p.photo_id, p.comment_count  FROM `engine4_storage_files` as f left join `engine4_album_photos` as p ON f.file_id = f.file_id WHERE f.name = '" . $comment_info['user_name'] . "-" . $comment_info['file_name'] . "' ORDER BY f.file_id ASC LIMIT 1";
            $file_info = $database->database_fetch_assoc($database->database_query($file_info_sql));

            $user_info = $database->database_fetch_assoc($database->database_query("SELECT user_id, email, username FROM `engine4_users` WHERE `email` = '" . $comment_info['email'] . "' LIMIT 1"));


            $comment_info['user_id'] = (int) $comment_info['user_id'];
            $comment_info['file_id'] = (int) $file_info['file_id'];
            $comment_info['photo_id'] = (int) $file_info['photo_id'];


            $comment_info['date'] = date("Y-m-d h:i:s", $comment_info['time_stamp']);
            $comment_info['text'] = addslashes($comment_info['text']);

            if (($comment_info['user_id'] != 0) && ($comment_info['photo_id'] != 0)) {

                $insert_query = "INSERT INTO `engine4_core_comments` (`comment_id`, `resource_type`, `resource_id`, `poster_type`, `poster_id`, `body`, `creation_date`) VALUES
									(NULL, 'album_photo', " . $comment_info['file_id'] . ", 'user', " . $comment_info['user_id'] . ", '" . $comment_info['text'] . "', '" . $comment_info['date'] . "')";

                $database->database_query($insert_query);

                $database->database_query("UPDATE `engine4_album_photos` SET `comment_count` = '" . ($file_info['comment_count'] + 1) . "' WHERE `photo_id` = " . $comment_info['photo_id'] . " LIMIT 1");
            }
            $i++;
        }
    }

    function importblogsAction() {

        require_once dirname(__FILE__) . '/../libraries/header.php';

        $database->database_query("TRUNCATE TABLE `engine4_blog_blogs`");

        $blogs = $pdatabase->database_query("SELECT j.*, u.*, b.*, u.email FROM `" . PHPFOX_PREFIX . "blog` AS j LEFT JOIN " . PHPFOX_PREFIX . "user AS u ON j.user_id = u.user_id LEFT JOIN " . PHPFOX_PREFIX . "blog_text AS b ON j.blog_id = b.blog_id ");

        //print_r("SELECT j.*, u.*, b.*, u.email FROM `" . PHPFOX_PREFIX . "blog` AS j LEFT JOIN " . PHPFOX_PREFIX . "user AS u ON j.user_id = u.user_id LEFT JOIN " . PHPFOX_PREFIX . "blog_text AS b ON j.blog_id = b.blog_id ");

        while ($blog_info = $pdatabase->database_fetch_assoc($blogs)) {

            $user_info = $database->database_fetch_assoc($database->database_query("SELECT user_id FROM engine4_users WHERE email = '" . $blog_info['email'] . "' LIMIT 1"));

            $blog_info['seuser_id'] = (int) $user_info['user_id'];
            $blog_info['date'] = date("Y-m-d h:i:s", $blog_info['time_stamp']);
            $blog_info['title'] = addslashes($blog_info['title']);
            $blog_info['text'] = addslashes($blog_info['text']);


            if ($blog_info['seuser_id'] != 0) {
                $database->database_query("INSERT INTO `engine4_blog_blogs` (`blog_id`, `title`, `body`, `owner_type`, `owner_id`, `category_id`, `creation_date`, `modified_date`, `view_count`, `comment_count`, `search`, `draft`) VALUES
			    (" . $blog_info['blog_id'] . ", '" . $blog_info['title'] . "', '" . $blog_info['text_parsed'] . "', 'user', " . $blog_info['seuser_id'] . ", 13, '" . $blog_info['date'] . "', '" . $blog_info['date'] . "', 0, 0, 1, 0)");
            }
        }
    }

    function importblogscommentsAction() {

        require_once dirname(__FILE__) . '/../libraries/header.php';

        $database->database_query("TRUNCATE TABLE `engine4_core_comments`");

        $comments = $pdatabase->database_query("SELECT c.*, u.*, ct.* FROM `" . PHPFOX_PREFIX . "comment` AS c inner JOIN `" . PHPFOX_PREFIX . "user` AS u ON c.user_id = u.user_id inner JOIN `" . PHPFOX_PREFIX . "comment_text` AS ct ON ct.comment_id = c.comment_id WHERE c.type_id = 'blog'");

        $i = 1;
        while ($comment_info = $pdatabase->database_fetch_assoc($comments)) {


            $blog_info = $database->database_fetch_assoc($database->database_query("SELECT `title`, comment_count  FROM `engine4_blog_blogs` WHERE `blog_id` = '" . $comment_info['itemid'] . "' LIMIT 1"));

            $pblog_info = $pdatabase->database_fetch_assoc($pdatabase->database_query("SELECT `title` FROM `" . PHPFOX_PREFIX . "journal` WHERE `id` = '" . $comment_info['itemid'] . "' LIMIT 1"));

            $user_info = $database->database_fetch_assoc($database->database_query("SELECT user_id, email, username FROM `engine4_users` WHERE `email` = '" . $comment_info['email'] . "' LIMIT 1"));

            $comment_info['blog_title'] = $blog_info['title'];
            $comment_info['pblog_title'] = $pblog_info['title'];
            $comment_info['user_id'] = (int) $user_info['user_id'];
            $comment_info['date'] = date("Y-m-d h:i:s", $comment_info['time_stamp']);
            $comment_info['text'] = addslashes($comment_info['text_parsed']);

            if ($comment_info['user_id'] != 0) {
                $database->database_query("INSERT INTO `engine4_core_comments` (`comment_id`, `resource_type`, `resource_id`, `poster_type`, `poster_id`, `body`, `creation_date`) VALUES
					(NULL, 'blog', " . $comment_info['item_id'] . ", 'user', " . $comment_info['user_id'] . ", '" . $comment_info['text'] . "', '" . $comment_info['date'] . "')");
                $database->database_query("UPDATE `engine4_blog_blogs` SET `comment_count` = '" . ($blog_info['comment_count'] + 1) . "' WHERE `blog_id` = " . $comment_info['item_id'] . " LIMIT 1");
            }
            $i++;
        }
    }

    function importfoldersAction() {
        require_once dirname(__FILE__) . '/../libraries/header.php';

        $users = $database->database_query("TRUNCATE TABLE `engine4_activity_comments`");

        $users = $database->database_query("SELECT * FROM `engine4_users` LIMIT 1, 10000");

        while ($user_info = $database->database_fetch_assoc($users)) {

            $subdir1 = $user_info['user_id'] + 999999 - (($user_info['user_id'] - 1) % 1000000);
            $subdir2 = $user_info['user_id'] + 999 - (($user_info['user_id'] - 1) % 1000);

            $user_directory = "../public/user/$subdir1/$subdir2/" . $user_info['user_id'] . "/";
            $user_path_array = explode("/", $user_directory);
            array_pop($user_path_array);

            $subdir = "../public/user/$subdir1/$subdir2/";

            if (!is_dir($subdir)) {
                mkdir($subdir, 0777);
                chmod($subdir, 0777);
                $handle = fopen($subdir . "index.php", 'x+');
                fclose($handle);
                chmod($subdir . "/index.php", 0777);
            }
            if (!is_dir($user_directory)) {
                mkdir($user_directory, 0777);
                chmod($user_directory, 0777);
                $handle = fopen($user_directory . "/index.php", 'x+');
                fclose($handle);
                chmod($user_directory . "/index.php", 0777);
            }
        }
    }
}
