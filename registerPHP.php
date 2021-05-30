<?php 


error_reporting(E_ALL);
ini_set('display_errors', 'on');

//CONTROLLO CHE NON SIA LOGINNATO

session_start();
if (isset($_SESSION["is_login"]) and $_SESSION["is_login"]) {
    header("Location: account.php");
    exit();
}

include "connect.php";

//restituzione dell'errore al register.php
function registration_fail($error) {
    $_SESSION["registration_error"] = $error;
    header('Location: register.php');
    exit();
}

if (isset($_POST["submit"])) {
    
    $username = $_POST["username"];
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $date = $_POST["date"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $password2 = $_POST["password2"];

    if (empty($username)) {
        registration_fail("You must fill the username field");
    }
    if (empty($name)) {
        registration_fail("You must fill the name field");
    }
    if (empty($surname)) {
        registration_fail("You must fill the surname field");
    }
    if (empty($date)) {
        registration_fail("You must fill the date field");
    }
    if (empty($email)) {
        registration_fail("You must fill the email field");
    }
    if (empty($password)) {
        registration_fail("You must fill the password field");
    }
    if (empty($password2)) {
        registration_fail("You must fill the confirm password field");
    }
    if ($password != $password2) {
        registration_fail("Passwords are not the same");
    }
    
    //controllo se l'username esiste già
    $alreadyExistsQuery = "SELECT * FROM users WHERE username = ? LIMIT 1";
    
    $stmt = $conn->prepare($alreadyExistsQuery); 
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!empty($result) && $result->num_rows > 0) {    
        registration_fail("Another user with the same username exists");
    }

    $password = hash("sha256", $password);
    //registrazione a buon fine
    unset($_SESSION["registration_error"]); //togliere eventuali precedenti registrazioni fallite

    $query = "INSERT INTO users (id_user, username, name, surname, date, email, password, registration_date) VALUES (NULL, ?, ?, ?, ?, ?, ?, CURDATE());";
    
    $stmt = $conn->prepare($query); 
    $stmt->bind_param("ssssss", $username, $name, $surname, $date, $email, $password);
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