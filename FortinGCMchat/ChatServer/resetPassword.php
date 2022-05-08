<?php 
include("db.php");
$email=$_POST['email'];

$to = $email;
$subject  = 'Your Fortin Chat Password is';
$message = "Your current password is ";


$query="select * from usertable where email='$email'";
$result=mysqli_query($conn, $query) or die(error);

if(mysqli_num_rows($result))
{
    while ($row_user = $result->fetch_assoc()) {
    $password = $row_user["password"];
   
     $from_add = "utpal@swiftomatics.in"; 

	$to_add =  $email; //<-- put your yahoo/gmail email address here

	$message =  $message.$password;
	
	$headers = "From: $from_add \r\n";
	$headers .= "Reply-To: $from_add \r\n";
	$headers .= "Return-Path: $from_add\r\n";
	$headers .= "X-Mailer: PHP \r\n";
	
	
	if(mail($to_add,$subject,$message,$headers)) 
	{
		$msg = "Mail sent OK";
	} 
	else 
	{
 	   $msg = "Error sending email!";
	}

    }
 
}
else
{
echo "No user exist with this email id";
}

?>