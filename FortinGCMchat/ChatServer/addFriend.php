<?php
if (isset($_POST["message"])) {

    $messagetype = $_POST["messagetype"];
    $message = $_POST["message"];
    $fromid = $_POST["fromid"];
    $toid = $_POST["toid"];
    $deviceid = $_POST["deviceid"];
    require 'db.php';

    include_once './GCM.php';
    $cklogin = new GCM();

    $result = $cklogin->checkuserlogin($fromid, $deviceid);

    if ($result == 'loggedin') {
        $blockcheck = new GCM();
        $checkblock = $blockcheck->checkblockuser($fromid, $toid);

        if ($checkblock == 'true') {

            $sender_name = '';
            $sender_lname = '';
            $logintype = '';
            $chattype = 'individual';

            $query_exist = "select * from user_relation_map where user_id='$fromid' and friend_id='$toid'";
            $result_exist = mysqli_query($conn, $query_exist);

            if (mysqli_num_rows($result_exist) > 0) {
                
                $response["success"] = "already";
           $response["message"] = "You have already added this friend";
           echo json_encode($response);
               
            } else {
                $query = "INSERT INTO `user_relation_map`(user_id,friend_id) VALUES ($fromid,$toid)";
                $result = mysqli_query($conn, $query);
            

            $result_sendername = mysqli_query($conn, "SELECT *  FROM usertable where userid='$fromid'") or die(mysql_error());

            if (mysqli_num_rows($result_sendername) > 0) {

                while ($row = $result_sendername->fetch_assoc()) {

                    $sender_name = $row['firstname'];
                    $sender_lname = $row['lastname'];
                    $logintype = $row['logintype'];
                }
            }
    
            $result = mysqli_query($conn, "SELECT * FROM usertable where userid='$toid'") or die(mysql_error());

            if (mysqli_num_rows($result) > 0) {

                while ($row = $result->fetch_assoc()) {
                    include_once './GCM.php';
                    $regId = $row["gcmid"];
                    $gcm = new GCM();
                    $registatoin_ids = array($regId);
                    $res = $gcm->send_notification($registatoin_ids, $message, $sender_name, $fromid, $sender_lname, $logintype, $chattype, $messagetype);
                    
                }//end of while
            }
            }
        }
        else
        {
           $response["success"] = "block";
           $response["message"] = "You can not message this user.";
           echo json_encode($response);
        }
    } else {
        $response["success"] = "false";
        $response["message"] = "User Not Logged in.";
        echo json_encode($response);
    }
}
?>
