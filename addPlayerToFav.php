<?php

session_start();
include "connect.php";

$id_player = $_GET["id"];
$id_user = $_SESSION["id"];

$sql2 = "SELECT * FROM fav_players WHERE id_user = ? AND id_player = ?";

$stmt2 = $conn->prepare($sql2); 
$stmt2->bind_param("ii", $id_user, $id_player);
$stmt2->execute();
$result2 = $stmt2->get_result();

if ($result2->num_rows > 0) {
    $sql = "DELETE FROM fav_players WHERE id_user = ? AND id_player = ?";
}
else {
    $sql = "INSERT INTO fav_players (id_user, id_player) VALUES (?, ?);";
}

$stmt = $conn->prepare($sql); 
$stmt->bind_param("ii", $id_user, $id_player);
$stmt->execute();

?>