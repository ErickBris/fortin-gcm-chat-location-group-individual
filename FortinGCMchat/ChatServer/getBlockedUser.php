<?php
$userid = $_GET['userid'];
$deviceid = $_GET["deviceid"];
$response = array();
require 'db.php';

include_once './GCM.php';
$cklogin = new GCM();

$result = $cklogin->checkuserlogin($userid, $deviceid);

if ($result == 'loggedin') {
    $response = array();

    $query_user = "select * from usertable where userid in (select friend_id from user_relation_map where blocked_status='yes' and user_id='$userid')";

    $result_user = mysqli_query($conn, $query_user) or die(mysqli_error());

    if (mysqli_num_rows($result_user) > 0) {

        $response["users"] = array();

        while ($row_user = mysqli_fetch_array($result_user)) {
            $users = array();
              $users["userid"] = $row_user["userid"];
            $users["firstname"] = $row_user["firstname"];
            $users["lastname"] = $row_user["lastname"];
            $users["gender"] = $row_user["gender"];
            $users["city"] = $row_user["city"];
            $users["country"] = $row_user["country"];
            $users["gender"] = $row_user["gender"];
            $users["profilepic"] = $row_user["profilepic"];
            $users["status"] = $row_user["status"];
            $users["logintype"] = $row_user["logintype"];
              array_push($response["users"], $users);
        }
        $response["success"] = 1;
        echo json_encode($response);
    } else {

        $response["success"] = 0;
        $response["message"] = "No Block users found";

        echo json_encode($response);
    }
} else {
   $response["success"] = "false";
    $response["message"] = "User Not Logged in.";
    echo json_encode($response);
}