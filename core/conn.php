<?php

$url = "localhost";
$username = "root";
$password = "";
$database = "texturetiler";

$conn = new mysqli($url, $username, $password, $database);
if ($conn->connect_error) { 
  die("Connection failed: " . $conn->connect_error);
}

?>