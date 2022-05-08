<?php
$userid = $_POST['userid'];
$password = $_POST['password'];
$deviceid = $_POST["deviceid"];

include("db.php");

   include_once './GCM.php';
     $cklogin = new GCM();
     
     $result = $cklogin->checkuserlogin($userid, $deviceid);
     
     if($result=='loggedin')
     {
            $query1 = "SELECT * FROM usertable where userid='$userid';";
            $result1 = mysqli_query($conn, $query1) or die(mysql_error());

if (mysqli_num_rows($result1) > 0) {

    $query = "UPDATE `usertable` set "
            . "password='$password'"
            . "where userid=" . $userid;
    $result = mysqli_query($conn, $query);

    if ($result == 1) {
        $response["success"] = 1;
          $response["message"] = "Password Updated Succeessfully";
        echo json_encode($response);
    } else {
        $response["success"] = 0;
        $response["message"] = "Please try again!";
        echo json_encode($response);
    }
} else {
    $query = "INSERT INTO `location_table`(userid,latitude,longitude) VALUES ('$userid','$latitude','$longitude')";

    $result = mysqli_query($conn, $query);

    $id = mysqli_insert_id($conn);


    if ($result == 1) {

      
       

          $response["success"] = 1;
        $response["message"] = "Updated Succeessfully";
        echo json_encode($response);

    } else {
        $response["success"] = 0;
        $response["message"] = "Please try again!";
        echo json_encode($response);
    }
}
}else
     {
         $response["success"] = "false";
           $response["message"] = "User Not Logged in.";
           echo json_encode($response);
     }
 
 
