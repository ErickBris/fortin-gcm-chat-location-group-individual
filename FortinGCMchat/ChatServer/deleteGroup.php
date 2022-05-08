<?php
$userid = $_POST['userid'];
$groupid = $_POST['groupid'];
$deviceid = $_POST["deviceid"];

  $sender_name = '';
    $sender_lname = '';
    $logintype = '';
    $chattype = 'delete';
include("db.php");

   include_once './GCM.php';
     $cklogin = new GCM();
     
     $result = $cklogin->checkuserlogin($userid, $deviceid);
     
     if($result=='loggedin')
     {
            $result_group = mysqli_query($conn, "SELECT *  FROM groupdetail where groupid='$groupid'") or die(mysql_error());

    if (mysqli_num_rows($result_group) > 0) {
        
        

        while ($row = $result_group->fetch_assoc()) {

            $memberid = $row['memberid'];  // group ids

            $result_grp = mysqli_query($conn, "SELECT *  FROM usertable where userid='$memberid' AND userid <> '$fromid';") or die(mysql_error());

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

    $query = "DELETE * FROM `groupmaster` where groupid=".$groupid;
    $result = mysqli_query($conn, $query);
    
    
        $query1 = "DELETE * FROM `groupdetail` where groupid=".$groupid;
    $result1 = mysqli_query($conn, $query1);

    if ($result == 1) {
        $response["success"] = 1;
          $response["message"] = "Delete Succeessfully";
        echo json_encode($response);
    } else {
        $response["success"] = 0;
        $response["message"] = "Please try again!";
        echo json_encode($response);
    }

}else
     {
         $response["success"] = "false";
           $response["message"] = "User Not Logged in.";
           echo json_encode($response);
     }
 
 
