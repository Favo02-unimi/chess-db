<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChessDB - Top players</title>
    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="cssPlayers.css">
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
                <li class="active"><a href="players.php">Top players</a></li>
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

        <section class="description animationFade">

            <h1><i class="fas fa-chess-king"></i> FIDE Ranking</h1>
            
            <div class="flex ">
                <p>The global chess rating is released monthly by the FIDE <i>(Fédération Internationale des Échecs)</i>.
                <br>The FIDE ranking is the most recognized ranking of chess players, and it is used as point of reference by the whole chess community.</p>
                <img src="src/players/fide.png" alt="Fide logo">
            </div>

        </section>

        <script src="jsPlayers.js"></script>
        
        <form action="" class="animationFade">
            
            <script>
                //carica al primo caricamento pagina
                ajaxDate("2017-06");
            </script>
            
            <label for='date'>You can select the date to visualize the relative ranking:</label>
            <select id="select" name='date' onchange="ajaxDate(this.value)">

        <?php

        include "connect.php";
        $sql = "
        SELECT YEAR(date), MONTH(date)
        FROM rankings
        GROUP BY YEAR(date), MONTH(date)
        ORDER BY YEAR(date) DESC, MONTH(date) DESC;
        ";

        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $year = $row["YEAR(date)"];
                $month = $row["MONTH(date)"];

                $month_name = date("F", mktime(0, 0, 0, $month, 10));
                
                echo "<option value='$year-$month'>$month_name $year</option>";
            }

        } else {
            echo "0 results";
        }

        ?>

            </select>
        </form>

        <div id="response" class="animationFade">
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