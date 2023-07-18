<?php


include '../wp-config.php';

$servername = constant("DB_HOST");
$username   = constant("DB_USER");
$password   = constant("DB_PASSWORD");
$dbname     = constant("DB_NAME");


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
  }


?>