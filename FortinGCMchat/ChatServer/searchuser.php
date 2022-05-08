<?php
$userid = $_GET['userid'];
//$deviceid = $_GET["deviceid"];
$search = $_GET["search"];
require 'db.php';

//  include_once './GCM.php';
//     $cklogin = new GCM();
//     
//     $result = $cklogin->checkuserlogin($userid, $deviceid);
//     
//     if($result=='loggedin')
//     {
//     
         
$response = array();
$sql = "Select * from `usertable` where Concat(firstname,lastname, city, country) like \"%$search%\" AND userid <> '$userid'";
//$sql = "SELECT * FROM `usertable` WHERE `userid`<> '$userid' AND `isloggin` <> 'no'";
    $result = mysqli_query($conn,$sql) or die(mysql_error());
    

    if (mysqli_num_rows($result) > 0) {
    $response["users"] = array();
    
    while ($row_user = $result->fetch_assoc()) {
             
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
    // no userss found
    $response["success"] = 0;
    $response["message"] = "No userss found";
    // echo no users JSON
    echo json_encode($response);
}
//} else {
//   $response["success"] = "false";
//    $response["message"] = "User Not Logged in.";
//    echo json_encode($response);
//}