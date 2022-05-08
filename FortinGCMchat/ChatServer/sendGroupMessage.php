<?php

if (isset($_POST["message"])) 
    
    {

    $messagetype = $_POST["messagetype"];
    $message = $_POST["message"];
    $fromid = $_POST["fromid"];
    $groupid = $_POST["groupid"];
    $deviceid = $_POST["deviceid"];
    
    
    require 'db.php';
    
     include_once './GCM.php';
     $cklogin = new GCM();
     
     $result = $cklogin->checkuserlogin($fromid, $deviceid);
     
     if($result=='loggedin')
     {
    
    $sender_name = '';
    $sender_lname = '';
    $logintype = '';
    $chattype = 'group';

    $result_sendername = mysqli_query($conn, "SELECT *  FROM usertable where userid='$fromid'") or die(mysql_error());

    if (mysqli_num_rows($result_sendername) > 0) {

        while ($row = $result_sendername->fetch_assoc()) {

            $sender_name = $row['firstname'];  /// sender details
            $sender_lname = $row['lastname'];
            $logintype = $row['logintype'];
        }
    }



    $result_group = mysqli_query($conn, "SELECT *  FROM groupdetail where groupid='$groupid'") or die(mysql_error());

    if (mysqli_num_rows($result_group) > 0) {

        while ($row = $result_group->fetch_assoc()) {

            $memberid = $row['memberid'];  // group ids

            $result_grp = mysqli_query($conn, "SELECT *  FROM usertable where userid='$memberid' AND userid <> '$fromid' group by userid;") or die(mysql_error());

            if (mysqli_num_rows($result_grp) > 0) {

                while ($row = $result_grp->fetch_assoc()) {


                    include_once './GCM.php';
                    $regId = $row["gcmid"];
                    $gcm = new GCM();
                    $registatoin_ids = array($regId);
                    $res = $gcm->send_groupnotification($registatoin_ids, $message, $sender_name, $fromid, $sender_lname, $logintype, $chattype, $groupid, $messagetype);
                }
            }
        }
    }
     }else
     {
          $response["success"] = "false";
           $response["message"] = "User Not Logged in.";
           echo json_encode($response);
     }
}
?>
