<?php 
$servername = "localhost";
$username 	= "root";
$password 	= "star@1234";
$dbname 	= "bitcoin";

// Create connection
$DB = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($DB->connect_error) {
    die("Connection failed: " . $DB->connect_error);
} 
?>