<?php
$userid = $_POST['userid'];
$friend_id = $_POST['friendid'];
$deviceid = $_POST["deviceid"];

include("db.php");

include_once './GCM.php';
$cklogin = new GCM();

$result = $cklogin->checkuserlogin($userid, $deviceid);

if ($result == 'loggedin') {

    if ($userid == $friend_id) {
        echo 'You Cannot Block yourself';
        die;
    }

    $status = "yes";
    $query = "UPDATE `user_relation_map` set "
            . "blocked_status='$status'"
            . " where user_id=" . $userid . " and friend_id=$friend_id";

    $result = mysqli_query($conn, $query);

    if ($result == 1) {
        $response["success"] = 1;
        $response["message"] = "Updated Succeessfully";
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
	

