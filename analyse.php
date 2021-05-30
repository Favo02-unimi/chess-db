<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChessDB - Analyse</title>
    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="cssAnalyse.css">
    <link rel="icon" type="image/png" href="src/light_favicon.svg"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="jsFavourite.js"></script>
    <script src="jsMatches.js"></script>
    <!-- <script src="./node_modules/@mliebelt/pgn-viewer/lib/pgnv.js" type="text/javascript" ></script> -->
    <script src="./node_modules/@mliebelt/pgn-viewer/lib/dist.js" type="text/javascript" ></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
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
                <li class="active"><a href="analyse.php">Analyse</a></li>
                
                <li class="right"><a href="account.php">
                    <?php
                    session_start();
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

        <section class="description animationFade">

            <h1><i class="fas fa-chess-king"></i> Analyse</h1>

            <div class="flex ">
                <p>Analyse a match between the hundreds in the <a href="matches.php">Top Matches</a> section, or upload a match in <a href="https://en.wikipedia.org/wiki/Portable_Game_Notation">PGN</a> format!</p>

                <p class='reset'><a href="analyse.php">Reset</a> to analyse another game</p>
            </div>

        </section>

        <div class="flex analysis">

            <?php

            include "connect.php";

            if (isset($_POST["save"])) {
                if ($_SESSION["is_login"]) {
                    $white = $_POST["white"];
                    $black = $_POST["black"];
                    $game = $_POST["pgn"];
                    $id_user = $_SESSION["id"];

                    $sql = "INSERT INTO saved_matches (id_match, id_user, date, white, black, game) VALUES (NULL, ?, CURDATE(), ?, ?, ?)";

                    $stmt = $conn->prepare($sql); 
                    $stmt->bind_param("isss", $id_user, $white, $black, $game);
                    $stmt->execute();

                    echo "<h2>Game saved: " . $white . " vs " . $black . "</h2>";
                }
                else {
                    header("Location: account.php");
                    exit();
                }

            }
            else if (isset($_POST["submit"])) {
                if (isset($_POST["id"])) {
                    echo "<h2>Game analysed:</h2>";
                    echo "<div class='info'>";

                    $id_match = $_POST["id"];

                    $sql = "SELECT id, white.id_player id_white, black.id_player id_black, result, game, white.name white, welo, black.name black, belo, date, site FROM matches_new
                        INNER JOIN players white ON matches_new.white = white.id_player
                        INNER JOIN players black ON matches_new.black = black.id_player
                        WHERE id = ?";

                    $stmt = $conn->prepare($sql); 
                    $stmt->bind_param("i", $id_match);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {

                            $wres = substr($row["result"], 0, strpos($row["result"], "-"));
                            $bres = substr($row["result"], strpos($row["result"], "-")+1);

                            echo "<h1><i class='white fas fa-chess-pawn'></i>
                                <form action='player.php' method='POST'>";
                                    echo "<input type='hidden' name='id' value='" . $row["id_white"] . "'>";
                                    echo "<input class='player' type='submit' name='submit' value='" .  $row["white"] . " (" . $row["welo"] . "): " . "'>";
                                echo "</form>";
                            if ($wres == "1") {
                                echo "<span class='win'>";
                            }
                            else if ($wres == "1/2") {
                                echo "<span class='draw'>";
                            }
                            else if ($wres == "0") {
                                echo "<span class='lose'>";
                            }
                            echo $wres . "</span></h1>";

                            echo "<h1><i class='black fas fa-chess-pawn'></i><form action='player.php' method='POST'>";
                                    echo "<input type='hidden' name='id' value='" . $row["id_black"] . "'>";
                                    echo "<input class='player' type='submit' name='submit' value='" .  $row["black"] . " (" . $row["belo"] . "): " . "'>";
                                    echo "</form>";
                            if ($bres == "1") {
                                echo "<span class='win'>";
                            }
                            else if ($bres == "1/2") {
                                echo "<span class='draw'>";
                            }
                            else if ($bres == "0") {
                                echo "<span class='lose'>";
                            }
                            echo $bres . "</span></h1>";

                            echo "<form method='POST' action='match.php'><input type='hidden' name='id' value='" . $id_match . "'>
                            <input class='button' type='submit' value='Match page' name='submit'></form>";

                            if ($_SESSION["is_login"]) {
                                $id_user = $_SESSION["id"];
                                $sql2 = "SELECT * FROM fav_matches WHERE id_user = ? AND id_match = ?";

                                $stmt2 = $conn->prepare($sql2); 
                                $stmt2->bind_param("ii", $id_user, $id_match);
                                $stmt2->execute();
                                $result2 = $stmt2->get_result();

                                if ($result2->num_rows > 0) {
                                    echo "<i class='fas fa-heart' onclick='addMatchToFav(this, " . $id_match . ")'></i>";
                                }
                                else {
                                    echo "<i class='far fa-heart' onclick='addMatchToFav(this, " . $id_match . ")'></i>";
                                }
                            }
                            else {
                                echo "<i class='far fa-heart' onclick=\"window.location.href='account.php'\"></i>";
                            }
                        }
                    }
                    echo "<script>hoverHeartbroken()</script>";
                    echo "</div>";




                }
                else {
                    echo "<h2>Game analysed:</h2>";
                    echo "<h3>Custom game: ". $_POST["white"] . " vs " . $_POST["black"] . "</h3>";
                }
            }
            else {
                echo "<h2>Upload your PGN to get the game analysed:</h2>";
                echo "<form action='' method='POST'>

                        <label for='pgn'>Game PGN:</label><br>
                        <textarea rows='4' cols='50' name='pgn' required placeholder='1. d4 Nf6 2. c4 e6 ...'></textarea><br>

                        <label for='white'>White player:</label>
                        <input type='text' name='white' placeholder='John' required>
                        <br>

                        <label for='black'>Black player:</label>
                        <input type='text' name='black' placeholder='Pamela' required>
                        <br>

                        <input type='submit' name='submit' value='Analyse'>
                        <input type='submit' name='save' value='Save game'>
                    </form>";
                echo "<h2>...or just play</h2>";

            }


            ?>

            <div id="board"></div>
            <script>
            config = {
                pgn: '<?php echo $_POST["pgn"]; ?>',
                position: 'start',
                showCoords: true,
                orientation: 'white',
                theme: 'informator',
                pieceStyle: 'merida',
                locale: 'en',
                timerTime: '1000',
                width: '500px',
                boardSize: '500px',
                layout: 'left',
                showFen: false,
                coordsInner: false,
                headers: true,
                coordsFactor: '0.7',
                coordsFontSize: '',
                colorMarker: '',
                startPlay: '',
                hideMovesBefore: false,
                notation: 'short',
                notationLayout: 'list'
            };

            var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
            if (width <= 1100) {
                config.layout = 'top';
                config.width = '300px';
                config.boardSize = '300px';
            }
            PGNV.pgnEdit('board', config);
            </script>
        </div>

    </main>

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
