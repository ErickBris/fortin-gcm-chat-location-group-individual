<?php
$messagetype = $_POST["messagetype"];
$userid = $_POST["userid"];
$groupname = $_POST['groupname'];
$adminid = $_POST['adminid'];
$memberid = $_POST['memberid'];
$membername = $_POST['membername'];
$deviceid = $_POST["deviceid"];
include("db.php");


include_once './GCM.php';
$cklogin = new GCM();

$resultnew = $cklogin->checkuserlogin($userid, $deviceid);

if ($resultnew == 'loggedin') {
    $groupid = '';
    $query = "INSERT INTO `groupmaster`(groupname,adminid) VALUES ('$groupname','$adminid')";

    $result = mysqli_query($conn, $query);
    $result2 = '';
    $registatoin_ids = '';

    if ($result == 1) {
        $groupid = mysqli_insert_id($conn);

        $memberarray = explode(",", $memberid);

        array_push($memberarray, $adminid);
        $memid = '';
        $chattype = 'alert';

        $allregid = array();

        foreach ($memberarray as $memid):

            $newquery = "INSERT INTO `groupdetail`(memberid,groupid) VALUES ('$memid','$groupid')";

            $result2 = mysqli_query($conn, $newquery);



            $result = mysqli_query($conn, "SELECT * FROM usertable where userid='$memid'") or die(mysql_error());

            if (mysqli_num_rows($result) > 0) {

                while ($row = $result->fetch_assoc()) {
                    include_once './GCM.php';
                    $regId = $row["gcmid"];
                    array_push($allregid, $regId);
                    $gcm = new GCM();
                    $registatoin_ids = array($regId);
                }//end of while
            }

        endforeach;

        $res = $gcm->sendgroupalert($allregid, 'New Group Created!', $groupname, $groupid, $chattype, $memberid, $adminid, $membername);

        $sender_name = '';
        $sender_lname = '';
        $logintype = '';
        $message = 'Welcome to Group ' . $groupname;

        $result_sendername = mysqli_query($conn, "SELECT *  FROM usertable where userid='$adminid'") or die(mysql_error());

        if (mysqli_num_rows($result_sendername) > 0) {

            while ($row = $result_sendername->fetch_assoc()) {

                $sender_name = $row['firstname'];  /// sender details
                $sender_lname = $row['lastname'];
                $logintype = $row['logintype'];
            }
        }

        $chattype = 'group';
        $res2 = $gcm->send_groupnotification($allregid, $message, $sender_name, $adminid, $sender_lname, $logintype, $chattype, $groupid, $messagetype);
    }


    if ($result2 == 1) {
        $response["success"] = 1;
        $response["message"] = "Group Created Successfully...";
        echo json_encode($response);
    } else {
        $response["success"] = 0;
        $response["message"] = "Please try again!";
        echo json_encode($response);
    }
} else {
    $response["success"] = "false";
    $response["message"] = "User Not Logged in.";
    echo json_encode($response);
}

 
 