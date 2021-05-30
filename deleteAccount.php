<?php
 
session_start();

include "connect.php";

$id = $_SESSION["id"];

$sql = "DELETE FROM users WHERE id_user = ?";

$stmt = $conn->prepare($sql); 
$stmt->bind_param("i", $id);
$stmt->execute();

session_destroy();
header('Location: index.php');
exit();

?>