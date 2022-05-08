<?php

$groupid = $_POST['groupid'];
$memberid = $_POST['memberid'];
$membername = $_POST['membername'];
$groupname = $_POST['groupname'];
$adminid = $_POST['adminid'];
include("db.php");
$memberarray = explode(",", $memberid);
$memid = '';
$result2 = '';
$regid = '';

include_once './GCM.php';
 $cklogin = new GCM();
$query = "UPDATE `groupmaster` set "
        . "groupname='$groupname'"
        . "where groupid=" . $groupid;
$result1 = mysqli_query($conn, $query);
$allregid = array();
$chattype = 'update';
foreach ($memberarray as $memid):


    $query1 = "SELECT * FROM groupdetail where memberid='$memid' AND groupid='$groupid';";

    $result = mysqli_query($conn, $query1) or die(mysql_error());

    if (mysqli_num_rows($result) < 1) {

        $newquery = "INSERT INTO `groupdetail`(memberid,groupid) VALUES ('$memid','$groupid')";

        $result2 = mysqli_query($conn, $newquery);
    }

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

$res = $gcm->sendgroupalert($allregid, 'Group Updated!!', $groupname, $groupid, $chattype, $memberid, $adminid, $membername);

if ($result2 == 1) {
    $response["success"] = 1;
    $response["message"] = "Member inserted";
    echo json_encode($response);
} else {
    $response["success"] = 0;
    $response["message"] = "Please try again!";
    echo json_encode($response);
}


 
 
