<?php

 $username = htmlspecialchars($_GET['username']);  
 $deviceid = htmlspecialchars($_GET['deviceid']);
    



$db_username='';
$db_deviceid='';

require 'db.php';

    $result = mysqli_query($conn,"SELECT * FROM usertable");    
    
    if (mysqli_num_rows($result) > 0) {       
  
    while ($row = $result->fetch_assoc()) {   
        $db_uname = $row["userid"];     
        $db_pwd = $row["isloggin"];    
     }    
    }
    
    
     
    if($db_uname==$username):
        if($db_pwd==$deviceid):
             $response["success"] = 1;
            $response["message"] = "loggedin";
            echo json_encode($response);

        else:
             $response["success"] = "false";
             $response["message"] = "User Not Logged in";
             echo json_encode($response);

        endif;
        
    endif;