<?php 
    //CONTROLLO CHE NON SIA LOGINNATO

    session_start();
    if (isset($_SESSION["is_login"]) and $_SESSION["is_login"]) {
        header("Location: account.php");
        exit();
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChessDB - Login</title>
    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="cssLogin.css">
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

        <div class="flex">

            <div class="left">
                <div class="img"></div>
            </div>

            <form action="loginPHP.php" method="post">
                <h1>Login:</h1>
                <br>
               
                <?php
                    //visualizza l'errore se esistente
                    if (isset($_SESSION["login_error"])) {
                        echo "
                        <div id='error'>
                        <h2>Failed login:</h2>
                        <h4>". $_SESSION["login_error"] ."</h4>
                        </div><br>";
                    }
                ?>

                <label for="username">Username</label>
                <div class="tooltip"><i class="fas fa-info-circle"></i>
                    <span class="tooltiptext">4-20 characters, only alphanumeric and dashes (- _), no dash at start or end, no consecutive dashes</span>
                </div>
                <input type="text" name="username" placeholder="Your username" pattern="^[a-zA-Z0-9]([_-](?![_-])|[a-zA-Z0-9]){2,18}[a-zA-Z0-9]$" required>
                <br><br>

                <label for="password">Password</label>
                <div class="tooltip"><i class="fas fa-info-circle"></i>
                    <span class="tooltiptext">8-20 characters, at least one number, one lowercase, one uppercase and one special character (? = . * [ @ $ ! % * ? & ])</span>
                </div>
                <input type="password" name="password" placeholder="Your password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$" required>
                <br><br>

                <input type="submit" value="Login" name="submit">
                <br><br>

                <h3>No account? <a href="register.php">Register!</a></h3>
                
            </form>
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