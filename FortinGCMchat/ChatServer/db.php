<?php  
	
     
         
         $conn = mysqli_connect('localhost', 'username', 'password', 'databasename');
	 
        if (!$conn) {
            die('Could not connect: ' . mysqli_connect_error());
	}

        mysqli_select_db($conn,"databasename");
        
     

?>

