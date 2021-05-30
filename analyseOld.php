<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChessDB - Analyse</title>
    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="cssAnalyse.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <link rel="icon" type="image/png" href="src/light_favicon.svg"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://pgn.chessbase.com/CBReplay.css"/>
    <script src="https://pgn.chessbase.com/cbreplay.js" type="text/javascript"></script> 
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

    <div class='flex'>

    <?php

    if (isset($_POST["submit"])) {
        echo "<h3>Game analysed:</h3>";
        if (isset($_POST["result"])) {
            echo "<h4>" . $_POST["date"] . " - White: " . $_POST["welo"] . " ELO " . $_POST["result"] . " Black: " . $_POST["belo"] . " ELO</h4>";
        }
        else {
            echo "<h4>Your upload</h4";
        }
    }
    else {
        echo "
            <form action='' method='POST'>
            <h3>Load the game you want to analyse in a <a href='https://en.wikipedia.org/wiki/Portable_Game_Notation'>PGN</a> format:</h3>
            <input type='text' name='pgn'>
            <input type='submit' name='submit' value='Load Game'>
            </form>";
    }
    ?>

    </div>

    <div class="cbreplay">
        <?php
            if (isset($_POST["submit"])) {
                echo $_POST["pgn"];
            }
        ?>
    </div>

    <?php

    if (isset($_POST["submit"])) {
        echo "<div class='flex'>
            <h2>Analyse another game!</h2><br>
            <form action='' method='POST'>
            <h3>Load the game you want to analyse in a <a href='https://en.wikipedia.org/wiki/Portable_Game_Notation'>PGN</a> format:</h3>
            <input type='text' name='pgn'>
            <input type='submit' name='submit' value='Load Game'>
            </form></div>";
    }

    ?>
  
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