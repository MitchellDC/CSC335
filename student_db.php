<?php

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "Students_recode_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn-> connect_error);
}

else{
	echo "Your student_rocord db Connected successfully";
	echo "<br>";
	



}
?>