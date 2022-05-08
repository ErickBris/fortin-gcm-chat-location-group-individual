<?php

$userid = $_POST['userid'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$gender = $_POST['gender'];
$city = $_POST['city'];
$country = $_POST['country'];
$gcmid = $_POST['gcmid'];
$profilepic = $_POST['profilepic'];
$deviceid = $_POST['deviceid'];
$logintype = $_POST['logintype'];

$id = '';
$memberid = '';
include("db.php");
$query1 = "SELECT * FROM usertable where userid='" . $userid . "';";
//echo $query1;die;
$result1 = mysqli_query($conn, $query1) or die(mysql_error());

if (mysqli_num_rows($result1) > 0) {

    $query = "UPDATE `usertable` set "
            . "gcmid='$gcmid', firstname='$firstname', lastname='$lastname', gender='$gender', city='$city',country='$country', profilepic='$profilepic', isloggin='$deviceid'  "
            . "where userid=" . $userid;
    $result = mysqli_query($conn, $query);

    if ($result == 1) {
        
          $result_status = mysqli_query($conn, "SELECT *  FROM usertable where userid=$userid") or die(mysql_error());
          
           $status = "Available";
        if (mysqli_num_rows($result_status) > 0) {

            while ($row = $result_status->fetch_assoc()) {
                include_once './GCM.php';
               
                $status = $row['status'];
               
            }
        }
        $response["success"] = 1;
          $response["status"] = $status;
        $response["message"] = "Registration Completed! Thank You..";
        echo json_encode($response);
    } else {
        $response["success"] = 0;
        $response["message"] = "Please try again!";
        echo json_encode($response);
    }
} else {
    $query = "INSERT INTO `usertable`(userid,firstname,lastname,gender,city,country,gcmid,profilepic,logintype,isloggin) VALUES ('$userid','$firstname','$lastname','$gender','$city','$country','$gcmid','$profilepic','$logintype','$deviceid')";

    $result = mysqli_query($conn, $query);

    $id = mysqli_insert_id($conn);


    if ($result == 1) {

        $adminid = 1;
        $sender_name = '';
        $sender_lname = '';
        $logintype = '';
        $regId = '';
        $groupid = 1;
        
        $result_sendername = mysqli_query($conn, "SELECT *  FROM usertable where id=$id") or die(mysql_error());

        $allregid = array();
        $status = "Available";
        if (mysqli_num_rows($result_sendername) > 0) {

            while ($row = $result_sendername->fetch_assoc()) {
                include_once './GCM.php';
                $memberid = $row['userid'];
                $sender_name = $row['firstname'];  /// sender details
                $sender_lname = $row['lastname'];
                $logintype = $row['logintype'];
                $status = $row['status'];
                $regId = $row["gcmid"];
                array_push($allregid, $regId);
                $gcm = new GCM();
                $registatoin_ids = array($regId);
            }
        }

          $response["success"] = 1;
          $response["status"] = $status;
          $response["message"] = "Registration Completed! Thank You..";
          echo json_encode($response);

    } else {
        $response["success"] = 0;
        $response["message"] = "Please try again!";
        echo json_encode($response);
    }
}

 
 
