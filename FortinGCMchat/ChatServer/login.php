<?php

$email = $_POST['email'];
$password = $_POST['password'];
//$lastname = $_POST['lastname'];
//$gender = $_POST['gender'];
$city = $_POST['city'];
$country = $_POST['country'];
$gcmid = $_POST['gcmid'];
//$profilepic = $_POST['profilepic'];
$deviceid = $_POST['deviceid'];
$logintype = $_POST['logintype'];



include("db.php");



$result = mysqli_query($conn, "SELECT * FROM usertable where password='$password' and email='$email'") ;
//echo "SELECT * FROM usertable where password='$password' and email=$email";
//die();
if (mysqli_num_rows($result) > 0) {

   
    $query = "UPDATE `usertable` set "
            . "gcmid='$gcmid', city='$city',country='$country', isloggin='$deviceid'  "
            . "where email='$email'";
    $result2 = mysqli_query($conn, $query);

    if ($result2 == 1) {
 
        $result_status = mysqli_query($conn, "SELECT *  FROM usertable where email='$email'") or die(mysql_error());
    $response["users"] = array();
         while ($row_user = $result_status->fetch_assoc()) {
             
            $users = array();
              $users["email"] = $row_user["email"];
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

    // echoing JSON response
    echo json_encode($response);
    } else {
        $response["success"] = 0;
        $response["message"] = "Please try again!";
        echo json_encode($response);
    }
} else {
    $response["success"] = 2;
    $response["message"] = "Username or Password invalid";
    echo json_encode($response);
}
 
 
