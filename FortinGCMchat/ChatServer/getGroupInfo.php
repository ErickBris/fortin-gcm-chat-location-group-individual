<?php
$userid = $_GET['userid'];
$deviceid = $_GET["deviceid"];
$groupid = $_GET["groupid"];
$response = array();
require 'db.php';
  $response = array();
include_once './GCM.php';
$cklogin = new GCM();

$result = $cklogin->checkuserlogin($userid, $deviceid);

if ($result == 'loggedin') {
     $response["group"] = array();
    $querygrp = "select * from groupmaster where groupid=$groupid";
    $result_group = mysqli_query($conn, $querygrp) or die(mysqli_error());
    
    if (mysqli_num_rows($result_group) > 0) {
        while ($row_group = mysqli_fetch_array($result_group)) {
        
        $group = array();
              $group["adminid"] = $row_group["adminid"];
              $group["groupname"] = $row_group["groupname"];
        }
        
             $response["group"] = $group;
    }
    
    
  
$response["users"] = array();
    $query_user = "select * from usertable where userid in (select memberid from groupdetail where groupid=$groupid)";

    $result_user = mysqli_query($conn, $query_user) or die(mysqli_error());

    if (mysqli_num_rows($result_user) > 0) {



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
        $response["message"] = "No Followers found";

        echo json_encode($response);
    }
} else {
   $response["success"] = "false";
    $response["message"] = "User Not Logged in.";
    echo json_encode($response);
}