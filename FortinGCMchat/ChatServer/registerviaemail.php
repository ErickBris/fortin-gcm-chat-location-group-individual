<?php
$email = $_POST['email'];
$firstname = $_POST['firstname'];
$password = $_POST['password'];
$lastname = $_POST['lastname'];
$gender = $_POST['gender'];
$city = $_POST['city'];
$country = $_POST['country'];
$gcmid = $_POST['gcmid'];
$deviceid = $_POST['deviceid'];
$logintype = $_POST['logintype'];

$id = '';
$memberid = '';
$userid = time();
include("db.php");
$target_path = "";
$query1 = "SELECT * FROM usertable where email='$email'";

$result1 = mysqli_query($conn, $query1);

if ($result1) {

    if ($_FILES) {
        echo 'here';
        $target_path = "uploads/";

        $target_path = $target_path . basename($_FILES['image']['name']);


        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            
        }



        $query = "INSERT INTO `usertable`(userid,email,password,firstname,lastname,gender,city,country,gcmid,profilepic,logintype,isloggin) VALUES ('$userid','$email',$password,'$firstname','$lastname','$gender','$city','$country','$gcmid','$target_path','$logintype','$deviceid')";


        $result = mysqli_query($conn, $query);
    } else {

        $target_path = "no";

        $query = "INSERT INTO `usertable`(userid,email,password,firstname,lastname,gender,city,country,gcmid,profilepic,logintype,isloggin) VALUES ('$userid','$email','$password','$firstname','$lastname','$gender','$city','$country','$gcmid','$target_path','$logintype','$deviceid')";

        $result = mysqli_query($conn, $query);
    }

    if ($result == 1) {

        $response["success"] = 1;
        $response["message"] = "Registration Completed! Thank You..";
        echo json_encode($response);
    } else {
        $response["success"] = 0;
        $response["message"] = "Please try again!";
        echo json_encode($response);
    }
} else {
    $response["success"] = 2;
    $response["message"] = "Email Already Registered.";
    echo json_encode($response);
}
