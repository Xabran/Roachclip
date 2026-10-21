<?php
class Notifications
{
    public $ToName = "";
    public $ToAcctName = "";
    public $ToEmail = "";
    public $Forums = array ();
    public $FRequests = array ();
    public $BlogCmmnts = array ();
    public $PhotoCmmnts = array ();
    public $FeedCmmnts = array ();
    public $ProfileCmmnts = array ();
    public $VideoCmmnts = array ();
    public $Messages = array ();
}

class Notification
{
    public $item_title = "";
    public $item_id = "";
    public $FromName = "";
}