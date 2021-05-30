<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChessDB - Player</title>
    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="cssPlayer.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <link rel="icon" type="image/png" href="src/light_favicon.svg"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="jsFavourite.js"></script>
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

        <div class="flex">

            <div class="info">

                <?php
                include "connect.php";

                $id_player = $_POST["id"];

                $sql = "SELECT rating, rank, games, name, birth_year, country_name, country_code, title_name
                FROM rankings
                INNER JOIN players ON rankings.id_player = players.id_player
                INNER JOIN countries ON players.id_country = countries.id_country
                INNER JOIN titles ON players.id_title = titles.id_title
                WHERE rankings.id_player = ?
                ORDER BY date DESC LIMIT 1;";
                                
                $stmt = $conn->prepare($sql); 
                $stmt->bind_param("i", $id_player);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $name = $row["name"];
                        echo "<h1>" . $row["name"] . "</h1>";
                        
                        if ($_SESSION["is_login"]) {
                            $id_user = $_SESSION["id"];
                            $sql2 = "SELECT * FROM fav_players WHERE id_user = ? AND id_player = ?";

                            $stmt2 = $conn->prepare($sql2); 
                            $stmt2->bind_param("ii", $id_user, $id_player);
                            $stmt2->execute();
                            $result2 = $stmt2->get_result();

                            if ($result2->num_rows > 0) {
                                echo "<i class='fas fa-heart' onclick='addPlayerToFav(this, " . $id_player . ")'> </i>";
                            }
                            else {
                                echo "<i class='far fa-heart' onclick='addPlayerToFav(this, " . $id_player . ")'> </i>";
                            }
                        }
                        else {
                            echo "<i class='far fa-heart' onclick=\"window.location.href='account.php'\"></i>";
                        }

                        echo "<h3>" . $row["title_name"] . " - " . $row["country_code"];
                        echo "<h3>Born in " . $row["birth_year"] . "</h3>";
                        echo "<h2>Last rating: " . $row["rating"] . "</h2>";
                        echo "<h3>" . $row["rank"] . "° in the world</h3>";             
                        echo "<h3>" . $row["games"] . " games played</h3>";
                    }
                }

                $sql = "SELECT MAX(rating) max_rating, MIN(rank) min_rank
                FROM rankings
                INNER JOIN players ON rankings.id_player = players.id_player
                WHERE rankings.id_player = ?
                ORDER BY date DESC LIMIT 1;";
                                
                $stmt = $conn->prepare($sql); 
                $stmt->bind_param("i", $id_player);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $name = $row["name"];
                        echo "<h2>Best ever: </h2>";   
                        echo "<h3>" . $row["max_rating"] . " ELO</h3>";   
                        echo "<h3>" . $row["min_rank"] . "° in the world</h3>";
                    }
                }
            
                echo "<script>hoverHeartbroken()</script>";
                
                ?>

            </div>

            <div class="photo">
                <?php

                $filename = substr($name, 0, strpos($name, ","));
                echo "<img class='player' src='";
                if (file_exists("src/players/jpg/" . $filename . ".jpg")) {
                    echo "src/players/jpg/" . $filename . ".jpg";
                }
                else {
                    echo "src/players/jpg/404.jpg";
                }
                echo "'>";


                ?>
            </div>

        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.2.1/chart.min.js" integrity="sha512-tOcHADT+YGCQqH7YO99uJdko6L8Qk5oudLN6sCeI4BQnpENq6riR6x9Im+SGzhXpgooKBRkPsget4EOoH5jNCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <div class="chart">

            <h1>Statistics:</h1>

            <div class="elo-chart">
                <canvas id="elo-chart"></canvas>
            </div>
            <div class="rank-chart">
                <canvas id="rank-chart"></canvas>
            </div>

        </div>

        <script>
        new Chart(document.getElementById("elo-chart"), {
            type: 'line',
            data: {
                labels: [
                    <?php
                        $id_player = $_POST["id"];
                        $sql = "SELECT YEAR(date) year, MONTH(date) month FROM rankings WHERE id_player = ? ORDER BY date ASC";

                        $stmt = $conn->prepare($sql); 
                        $stmt->bind_param("i", $id_player);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $dateObj = DateTime::createFromFormat('!m', $row["month"]);
                                $monthName = substr($dateObj->format('F'), 0, 3);
                                echo "\"" . $monthName . "-" . $row["year"] . "\", ";        
                            }
                        }
                    ?>
                ],
                datasets: [
                { 
                    data: [
                        <?php
                            $id_player = $_POST["id"];
                            $sql = "SELECT rating FROM rankings WHERE id_player = ? ORDER BY date ASC";
                                
                            $stmt = $conn->prepare($sql); 
                            $stmt->bind_param("i", $id_player);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo $row["rating"] . ", ";        
                                }
                            }
                        ?>
                    ],
                    label: "ELO",
                    borderColor: "#505050",
                    fill: true,
                },
                ]
            },
            options: {
                animation: true,
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size: 20
                            }
                        }
                    }
                }
            }
        });     
        
        new Chart(document.getElementById("rank-chart"), {
            type: 'line',
            data: {
                labels: [
                    <?php
                        $id_player = $_POST["id"];
                        $sql = "SELECT YEAR(date) year, MONTH(date) month FROM rankings WHERE id_player = ? ORDER BY date ASC";

                        $stmt = $conn->prepare($sql); 
                        $stmt->bind_param("i", $id_player);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $dateObj = DateTime::createFromFormat('!m', $row["month"]);
                                $monthName = substr($dateObj->format('F'), 0, 3);
                                echo "\"" . $monthName . "-" . $row["year"] . "\", ";       
                            }
                        }
                    ?>
                ],
                datasets: [
                { 
                    data: [
                        <?php
                            $id_player = $_POST["id"];
                            $sql = "SELECT rank FROM rankings WHERE id_player = ? ORDER BY date ASC";
                                
                            $stmt = $conn->prepare($sql); 
                            $stmt->bind_param("i", $id_player);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo $row["rank"] . ", ";        
                                }
                            }
                        ?>
                    ],
                    label: "Rank",
                    borderColor: "#505050",
                    fill: "start",
                },
                ]
            },
            options: {
                animation: true,
                responsive: true,
                scales: {
                    y: {
                        reverse: true,
                        min: 1,
                    },
                },
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size: 20
                            }
                        }
                    }
                }
            }
        });
        </script>

        <div class="matches">

            <h1>Last 5 matches:</h1>

            <?php

            include "connect.php";

            $sql = "SELECT id, date, site, game, white.name white_name, welo, result, black.name black_name, belo FROM matches_new
            INNER JOIN players white ON matches_new.white = white.id_player
            INNER JOIN players black ON matches_new.black = black.id_player
            WHERE white.id_player = $id_player OR black.id_player = ?
            ORDER BY date DESC LIMIT 5";

            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("i", $id_player);
            $stmt->execute();
            $result = $stmt->get_result();


            echo "
                <div class='table'>
                <table>
                    <tr>
                        <th> Date </th>
                        <th> Site </th>
                        <th> White (ELO) </th>
                        <th> Result </th>
                        <th> Black (ELO) </th>
                        <th style='min-width: 80px'> Game PGN </th>
                        <th style='min-width: 110px'> Analyse </th>
                        <th class='center' style='min-width: 80px'> More info </th>
                        <th class='center'> Favourite </th>
                    </tr>";
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $game = $row["game"];
                    echo "
                    <td>" . $row["date"] . "</td>
                    <td>" . $row["site"] . "</td>
                    <td>" . substr($row["white_name"], 0, strpos($row["white_name"], ",")) . " (" . $row["welo"] . ")</td>
                    <td>" . $row["result"] . "</td>
                    <td>" . substr($row["black_name"], 0, strpos($row["black_name"], ",")) . " (" . $row["belo"] . ")</td>
                    <td class='center'> <button onclick=\"copyPGN('$game')\">Copy PGN</button> </td>";

                    echo "<td class='center'>
                    <form action='analyse.php' method='POST'>
                        <input type=hidden name='id' value='" . $row["id"] . "'>
                        <input type=hidden name='pgn' value='" . $row["game"] . "'>
                        <input type='submit' value='Analyse' name='submit'>
                    </form>";

                    echo "<td class='center'><form method='POST' action='match.php'><input type='hidden' name='id' value='" . $row["id"] . "'>
                    <input type='submit' value='Info' name='submit'></form></td>";

                    echo "<td class='center'>";

                    if ($_SESSION["is_login"]) {
                        $id_user = $_SESSION["id"];
                        $id_match = $row["id"];
                        $sql2 = "SELECT * FROM fav_matches WHERE id_user = ? AND id_match = ?";

                        $stmt2 = $conn->prepare($sql2); 
                        $stmt2->bind_param("ii", $id_user, $id_match);
                        $stmt2->execute();
                        $result2 = $stmt2->get_result();

                        if ($result2->num_rows > 0) {
                            echo "<i class='fas fa-heart' onclick='addMatchToFav(this, " . $row["id"] . ")'></i>";
                        }
                        else {
                            echo "<i class='far fa-heart' onclick='addMatchToFav(this, " . $row["id"] . ")'></i>";
                        }
                    }
                    else {
                        echo "<i class='far fa-heart' onclick=\"window.location.href='account.php'\"></i>";
                    }

                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table></div>";
            }

            ?>
            <h3>Go to <a href="matches.php">Top matches</a> to find more matches!</h3>
        </div>

        <h2 class="goback">Go back to <a href="players.php">Top players</a></h2>

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