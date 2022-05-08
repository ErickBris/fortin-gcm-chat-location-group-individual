<?php

$userid = $_POST['userid'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$gender = $_POST['gender'];


include("db.php");



$result = mysqli_query($conn, "SELECT * FROM usertable where userid='$userid'");
//echo "SELECT * FROM usertable where password='$password' and email=$email";
//die();
if (mysqli_num_rows($result) > 0) {
$query = "";
    $target_path = "";
    if ($_FILES) {

        $target_path = "uploads/";

        $target_path = $target_path . basename($_FILES['image']['name']);

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {

        }
          $query = "UPDATE `usertable` set "
            . "firstname='$firstname', lastname='$lastname', gender='$gender', profilepic='$target_path' "
            . "where userid=" . $userid;
//    $result2 = mysqli_query($conn, $query);
    }  else {
       
          $query = "UPDATE `usertable` set "
            . "firstname='$firstname', lastname='$lastname', gender='$gender'"
            . " where userid=" . $userid;
  
    }
    $result2 = mysqli_query($conn, $query);

    if ($result2 == 1) {

        $result_status = mysqli_query($conn, "SELECT *  FROM usertable where userid='$userid'") or die(mysql_error());
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
        $response["message"] = $query;
        echo json_encode($response);
    }
} else {
    $response["success"] = 2;
    $response["message"] = "Invalid User";
    echo json_encode($response);
}
 
 
