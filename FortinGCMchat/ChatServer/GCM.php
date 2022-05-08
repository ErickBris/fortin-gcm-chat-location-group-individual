<?php

class GCM {

    //put your code here
    // constructor
    function __construct() {
        
    }
    
    public function checkblockuser($userid, $friendid) {
       
        $status = "yes";
        $uname = $userid;
        $fid = $friendid;
        require 'db.php';
        include_once './db.php';

        $resp = 'false';
        $db_username = '';
        $db_deviceid = '';

            $query_exist = "SELECT * FROM user_relation_map where user_id='$uname' and friend_id='$fid' and blocked_status='$status'";
        $result = mysqli_query($conn, $query_exist);
        if (mysqli_num_rows($result) > 0) {

            $resp = 'false';
        }  else {
            
            $newquery = "SELECT * FROM user_relation_map where user_id='$fid' and friend_id='$uname' and blocked_status='$status'";
            $result1 = mysqli_query($conn, $newquery);
            
            if (mysqli_num_rows($result1) > 0) {
                $resp = 'false';
            }else
            {
                 $resp = 'true';
            }
          
        }
        return $resp;
    }

    public function checkuserlogin($usernmae, $deviceid) {
       
        $uname = $usernmae;
        $did = $deviceid;
        require 'db.php';
        include_once './db.php';

        $resp = 'logout';
        $db_username = '';
        $db_deviceid = '';

        $query_exist = "SELECT * FROM usertable where userid='$usernmae' and isloggin='$deviceid'";

        $result = mysqli_query($conn, $query_exist);
        if (mysqli_num_rows($result) > 0) {

            $resp = 'loggedin';
        }  else {
            $resp = 'logout';
        }
        return $resp;
    }

    /**
     * Sending Push Notification
     */
    public function send_notification($registatoin_ids, $message, $title, $senderid, $sender_lname, $logintype, $chattype, $messagetype) {
        // include config
        include_once './config.php';

        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';

        $data = array('message' => $message, 'title' => $title, 'senderid' => $senderid, 'sender_lname' => $sender_lname, 'logintype' => $logintype, 'chattype' => $chattype, 'messagetype' => $messagetype);

        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $data,
        );

        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
            echo $result;
        }

        // Close connection
        curl_close($ch);

        echo $result;
    }

    public function sendgroupalert($registatoin_ids, $message, $title, $groupid, $chattype, $memberid, $adminid, $membername) {
        // include config
        include_once './config.php';

        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';

        $data = array('message' => $message, 'title' => $title, 'groupid' => $groupid, 'memberid' => $memberid, 'adminid' => $adminid, 'membername' => $membername, 'chattype' => $chattype);

        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $data,
        );

        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
            echo $result;
        }

        // Close connection
        curl_close($ch);

        echo $result;
    }

    public function send_groupnotification($registatoin_ids, $message, $title, $senderid, $sender_lname, $logintype, $chattype, $groupid, $messagetype) {
        // include config
        include_once './config.php';

        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';

        $data = array('message' => $message, 'title' => $title, 'senderid' => $senderid, 'sender_lname' => $sender_lname, 'logintype' => $logintype, 'chattype' => $chattype, 'groupid' => $groupid, 'messagetype' => $messagetype);

        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $data,
        );

        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
            echo $result;
        }

        // Close connection
        curl_close($ch);

        echo $result;
    }

}

?>
