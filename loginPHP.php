<?php 
    //CONTROLLO CHE NON SIA LOGINNATO

    session_start();
    if (isset($_SESSION["is_login"]) and $_SESSION["is_login"]) {
        header("Location: account.php");
        exit();
    }

    include "connect.php";

    if (isset($_POST["submit"])) {

        $username = $_POST["username"];
        $password = $_POST["password"];
        $password = hash("sha256", $password);

        $sql = "SELECT id_user, username, password FROM users WHERE username = ? AND password = ?;";

        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        //login a buon fine
        if(!empty($result) && $result->num_rows > 0) { 
            unset($_SESSION["login_error"]); // togliere eventuali errori precedenti login
            $_SESSION["is_login"] = true; 
            while($row = $result->fetch_assoc()) {
                $_SESSION["username"] = $row["username"];
                $_SESSION["id"] = $row["id_user"];
            }
            header('Location: account.php');
            exit();
        }
        //login sbagliato
        else {
            $_SESSION["login_error"] = "Either your username or password are wrong.";
            header('Location: login.php');
            exit();
        }
    }

?>
