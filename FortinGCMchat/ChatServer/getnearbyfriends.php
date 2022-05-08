<?php
$userid = $_GET['userid'];
$longitude = $_GET['longitude'];
$latitude = $_GET['latitude'];
$distance = $_GET['distance'];
$deviceid = $_GET["deviceid"];
$response = array();

require 'db.php';
include_once './GCM.php';
 $cklogin = new GCM();
     
     $result = $cklogin->checkuserlogin($userid, $deviceid);
     
     if($result=='loggedin')
     {
$query = "SELECT
   userid, (
     3959 * acos (
     cos ( radians($latitude) )
     * cos( radians( `latitude` ) )
     * cos( radians( `longitude` ) - radians($longitude) )
     + sin ( radians($latitude) )
     * sin( radians( `latitude` ) )
   )
) AS distance
FROM location_table
WHERE userid <> '$userid'
HAVING distance < $distance
ORDER BY distance 
LIMIT 0,100 ;";


$result = mysqli_query($conn, $query) or die(mysql_error());
$seluser = '';


if (mysqli_num_rows($result) > 0) {
 $response["users"] = array();
    while ($row = mysqli_fetch_array($result)) {
        
        $seluser = $row["userid"];
        
        
         $result1 = mysqli_query($conn,"SELECT * FROM `usertable` WHERE userid='$seluser'") or die(mysql_error());
    
    if (mysqli_num_rows($result1) > 0) {
        
   
    
    while ($row_user = mysqli_fetch_array($result1)) {
             
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

    // echoing JSON response
             // 
        } 
        
    }
echo json_encode($response);
} else {
    // no userss found
    $response["success"] = 0;
    $response["message"] = "No userss found";
    // echo no users JSON
    echo json_encode($response);
}
} else {
   $response["success"] = "false";
    $response["message"] = "User Not Logged in.";
    echo json_encode($response);
}