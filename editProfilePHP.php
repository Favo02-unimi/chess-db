<?php 


error_reporting(E_ALL);
ini_set('display_errors', 'on');

//CONTROLLO CHE SIA LOGINNATO

session_start();
if (!isset($_SESSION["is_login"]) or !$_SESSION["is_login"]) {
    header("Location: login.php");
    exit();
}

include "connect.php";

//restituzione dell'errore al register.php
function editProfile_fail($error) {
    $_SESSION["editProfile_error"] = $error;
    header('Location: editProfile.php');
    exit();
}

if (isset($_POST["submit"])) {
    
    $id_user = $_SESSION["id"];
    $username = $_POST["username"];
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $date = $_POST["date"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $password2 = $_POST["password2"];

    if (empty($username)) {
        editProfile_fail("You must fill the username field");
    }
    if (empty($name)) {
        editProfile_fail("You must fill the name field");
    }
    if (empty($surname)) {
        editProfile_fail("You must fill the surname field");
    }
    if (empty($date)) {
        editProfile_fail("You must fill the date field");
    }
    if (empty($email)) {
        editProfile_fail("You must fill the email field");
    }
    if (empty($password)) {
        editProfile_fail("You must fill the password field");
    }
    if (empty($password2)) {
        editProfile_fail("You must fill the confirm password field");
    }
    if ($password != $password2) {
        editProfile_fail("Passwords are not the same");
    }
    
    //controllo se l'username esiste già
    $alreadyExistsQuery = "SELECT * FROM users WHERE username = ? LIMIT 1";
    
    $stmt = $conn->prepare($alreadyExistsQuery); 
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!empty($result) && $result->num_rows > 0) {    
        editProfile_fail("Another user with the same username exists");
    }

    $password = hash("sha256", $password);
    //registrazione a buon fine
    unset($_SESSION["editProfile_error"]); //togliere eventuali precedenti registrazioni fallite

    $query = "UPDATE users SET username = ?, name = ?, surname = ?, date = ?, email = ?, password = ? WHERE id_user = ?;";
    
    $stmt = $conn->prepare($query); 
    $stmt->bind_param("ssssssi", $username, $name, $surname, $date, $email, $password, $id_user);
    $stmt->execute();

    $sql = "SELECT id_user, username, password FROM users WHERE username = ? AND password = ?;";

    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    //login per non ripetere il login dopo la registrazione
    if(!empty($result) && $result->num_rows > 0) { 
        $_SESSION["is_login"] = true; 
        while($row = $result->fetch_assoc()) {
            $_SESSION["username"] = $row["username"];
            $_SESSION["id"] = $row["id_user"];
        }
        header('Location: account.php');
        exit();
    }

    

}


?>