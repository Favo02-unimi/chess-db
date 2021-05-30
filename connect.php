<?php

$servername = "localhost";
$username = "root";
$password = "X2iNI3moDURdHQeK";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";

$conn->query("use chessdb");

?>