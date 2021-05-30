<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChessDB - Home</title>
    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="cssHome.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <link rel="icon" type="image/png" href="src/light_favicon.svg"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
                <li class="active"><a href="index.php">Home</a></li>
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

        <section class="welcome">
            <h1>The best website to find and analyse <span class="highlight">chess</span> statistics!</h1>
            <img src="src/home/home_main.png" alt="Chess board" class="image">
        </section>

        <div class="dividerOpen"></div>

        <section class="services">

            <h1 class="animation">Our services:</h1>

            <ul>
                <li class="animation"><a href="players.php"><i class="fas fa-chess-king"></i>Top players</a></li>
                <li class="more animation"><a href="#more-players">Learn more</a></li>
                <li class="animation"><a href="matches.php"><i class="fas fa-chess-knight"></i>Top matches</a></li>
                <li class="more animation"><a href="#more-matches">Learn more</a></li>
                <li class="animation"><a href="analyse.php"><i class="fas fa-chess-bishop"></i> Analyse</a></li>
                <li class="more animation"><a href="#more-analyse">Learn more</a></li>
            </ul>

        </section>

        <div class="dividerClose" id="more-players"></div>

        <section class="players">

            <h1 class="animation"><a href="players.php"><i class="fas fa-chess-king"></i> Top players</a></h1>

            <div class="flex animation">
                <p class="animation">Who are the best players in the world? Discover just jumping to the Top players page: FIDE ranking since 2000.</p>
                <img src="src/home/players.png" alt="Player">
            </div>

            <h3 class="animation"><a href="players.php">Jump to Top players <i class="fas fa-external-link-square-alt"></i></a></h3>


        </section>

        <div class="dividerOpenOpposite" id="more-matches"></div>

        <section class="matches">

            <h1 class="animation"><a href="matches.php"><i class="fas fa-chess-knight"></i> Top matches</a></h1>

            <div class="flex animation">
                <img src="src/home/matches.png" alt="Match">
                <p class="animation">How does a professional player plays? Watch and analyse the top matches played during the biggest tournaments of the last two decades.</p>
            </div>

            <h3 class="animation"><a href="matches.php">Jump to Top matches <i class="fas fa-external-link-square-alt"></i></a></h3>
                
        </section>

        <div class="dividerCloseOpposite" id="more-analyse"></div>

        <section class="analyse">

            <h1 class="animation"><a href="analyse.php"><i class="fas fa-chess-bishop"></i> Analyse</a></h1>

            <div class="flex animation">
                <p class="animation">Did you lost a very close game? Do you want to catch every mistake you made? Analyse every game you want in the analyse section.</p>
                <img src="src/home/analyse.png" alt="Analyse">
            </div>

            <h3 class="animation"><a href="analyse.php">Jump to Analyse <i class="fas fa-external-link-square-alt"></i></a></h3>

        </section>

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