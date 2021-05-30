<?php 

    session_start();

    //se l'utente non ha effettuato il login
    if (!isset($_SESSION["is_login"]) or !$_SESSION["is_login"]) {
        header("Location: login.php"); //rimanda alla pagina di login
        exit(); //blocca l'esecuzione della pagina
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChessDB - Account</title>
    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="cssAccount.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <link rel="icon" type="image/png" href="src/light_favicon.svg"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="jsFavourite.js"></script>
    <script src="jsMatches.js"></script>
</head>
<body>
    
    <header>

        <div class="logo">
            <a href="index.php" class="logo">
                <i class="fas fa-chess logo" id="logo"></i>
                <h1>ChessDB</h1>
            </a>
        </div>
        
        <nav id="nav">
            <ul class="mobileHidden">
                <li><a href="index.php">Home</a></li>
                <li><a href="players.php">Top players</a></li>
                <li><a href="matches.php">Top matches</a></li>
                <li><a href="analyse.php">Analyse</a></li>

                <li class="right active"><a href="account.php">
                    <?php
                    if (isset($_SESSION["is_login"]) and $_SESSION["is_login"]) {
                        echo "Welcome, " . $_SESSION["username"];
                    }
                    else {
                        echo "ACCOUNT";
                    }
                    ?>
                </a></li>
            </ul>
            
            <a href="index.php"><i class="mobile mLogo fas fa-chess"></i></a>
            <i class="mobile mMenu fas fa-bars" onclick="showMobileMenu()"></i>
            <a href="account.php"><i class="mobile mAccount fas fa-user"></i></a>

        </nav>
    </header>

    <main id="main">


        <?php

        include "connect.php";
        $id = $_SESSION["id"];

        $sql = "SELECT * FROM users WHERE id_user = ?";

        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $username = $row["username"];
                $name = $row["name"];
                $surname = $row["surname"];
                $date = $row["date"];
                $email = $row["email"];
                $regDate = $row["registration_date"];
            }
        } else {
            echo "0 results";
        }

        ?>

        <div class="profile">
            <div class="account">
                <?php
                    echo "<h1>" . $name . " \"" . $username . "\" " . $surname . "</h1>";
                    echo "<h2>" . $email . "</h2>";
                    echo "<h3>Born " . $date . " | Member since " . $regDate . "</h3>";
                ?>

                <div class="buttons">
                    <div class="edit">
                        <a href="logout.php"><h2>Logout</h2></a>
                        <a href="editProfile.php"><h2>Edit profile</h2></a>
                    </div>
                    <div class="delete">
                        <a href="deleteAccount.php" onclick="return confirm('Are you sure to delete your account?')"><h2>Delete account</h2></a>
                    </div>
                </div>

            </div>
            <div class="players">
                <h1>Favourite players:</h1>
                <?php

                    $id = $_SESSION["id"];

                    $sql = "SELECT * FROM fav_players INNER JOIN players ON fav_players.id_player = players.id_player WHERE id_user = ? ORDER BY name";
            
                    $stmt = $conn->prepare($sql); 
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<form action='player.php' method='POST'>";
                            echo "<input type='hidden' name='id' value='" . $row["id_player"] . "'>";
                            echo "<input type='submit' name='submit' value='" . $row["name"] . 
                            "'>";
                            echo "<i class='fas fa-heart' onclick='addPlayerToFav(this, " . $row["id_player"] . ")'></i>";
                            echo "</form>";
                        }
                    } else {
                        echo "<h3>You don't have any favourite player yet</h3>";
                        echo "<h5>Go to <a href='players.php'>players</a> to add some!</h5>";
                    }

                ?>
            </div>
            <div class="matches">
                <h1>Favourite matches:</h1>
                <?php

                    $id = $_SESSION["id"];

                    $sql = "SELECT id, white.name white, result, black.name black FROM fav_matches
                    INNER JOIN matches_new ON fav_matches.id_match = matches_new.id
                    INNER JOIN players white ON matches_new.white = white.id_player
                    INNER JOIN players black ON matches_new.black = black.id_player
                    WHERE id_user = ?";

                    $stmt = $conn->prepare($sql); 
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<form action='match.php' method='POST'>";
                            echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                            echo "<input type='submit' name='submit' value='" . substr($row["white"], 0, strpos($row["white"], ",")) . " " . $row["result"] . " " . substr($row["black"], 0, strpos($row["black"], ",")) . "'>";
                            echo "<i class=\"fas fa-heart\" onclick=\"addMatchToFav(this, " . $row["id"] . ")\"></i>";
                            echo "</form>";
                        }
                    } else {
                        echo "<h3>You don't have any favourite match yet</h3>";
                        echo "<h5>Go to <a href='matches.php'>matches</a> to add some!</h5>";
                    }

                ?>
            </div>
            <div class="saved">
                <h1>Saved matches:</h1>
                <?php

                    $id = $_SESSION["id"];

                    $sql = "SELECT date, white, black, game FROM saved_matches
                    WHERE id_user = ?";

                    $stmt = $conn->prepare($sql); 
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $game = $row["game"];
                                echo "<h3>" . $row["date"] . ": " . $row["white"] . " vs " . $row["black"];
                                echo "<button onclick=\"copyPGN('$game')\">Copy PGN</button></h3>";
                            }
                    }
                    else {
                        echo "<h3>You don't have any favourite match yet</h3>";
                        echo "<h5>Go to <a href='matches.php'>matches</a> to add some!</h5>";
                    }

                ?>
            </div>
        </div>

    </main>

    <script>hoverHeartbroken();</script>
    
    <footer>

        <div class="footer-left">
            <table>
                <tr>
                    <td class="flex">
                        <a href="index.php" class="logo">
                            <i class="fas fa-chess logo" id="logo"></i>
                            <h1>ChessDB</h1>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Developed with &#129504; by Luca Favini</p>
                    </td>
                </tr>
            </table>
        </div>
        <div class="footer-right">
            <div class="list">
                <ul>
                    <h4>Follow:</h4>
                    <li><a href="https://instagram.com">Instragram</a></li>
                    <li><a href="https://facebook.com">Facebook</a></li>
                    <li><a href="https://twitter.com">Twitter</a></li>
                </ul>
            </div>
            <div class="list">
                <ul>
                    <h4>Navigate:</h4>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="players.php">Top players</a></li>
                    <li><a href="matches.php">Top matches</a></li>
                    <li><a href="analyse.php">Analyse</a></li>
                    <li><a href="account.php">Your account</a></li>
                </ul>
            </div>
        </div>

    </footer>

<script src="js.js"></script>

</body>
</html>