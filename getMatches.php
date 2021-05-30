<?php

session_start();
include "connect.php"; //connessione al database

$search = $_GET["search"]; //preso il parametro della ricerca inserito dall'utente
$molt = $_GET["molt"]; //preso il moltiplicatore del limite dei match da resituire

$limit = 10 * intval($molt); //limite di match da restituire

if (ctype_space($search)) { //se la ricerca è vuota (solo spazi)

    $sql = "SELECT id, date, site, game, white.name white_name, welo, result, black.name black_name, belo FROM matches_new
        INNER JOIN players white ON matches_new.white = white.id_player
        INNER JOIN players black ON matches_new.black = black.id_player
        ORDER BY date DESC LIMIT ?"; //selezione dal database dei dati dei match in ordin di data
        
        $stmt = $conn->prepare($sql); //prepared statement
        $stmt->bind_param("i", $limit); //fornisce il parametro (i = intero) al prepared statement

}
else { //altrimenti la ricerca non è vuota

    $search = clean($search); //vengono rimossi i caratteri speciali dalla ricerca
    $search = $search . "%"; //viene aggiunto la keyword necessaria alla query per il LIKE

    $sql = "SELECT id, date, site, game, white.name white_name, welo, result, black.name black_name, belo FROM matches_new
     INNER JOIN players white ON matches_new.white = white.id_player
     INNER JOIN players black ON matches_new.black = black.id_player
     WHERE white.name LIKE ? OR black.name LIKE ? OR site LIKE ?
     ORDER BY date DESC LIMIT ?"; //query che seleziona solo i match che rispondono alla ricerca

    $stmt = $conn->prepare($sql); //prepared statement
    $stmt->bind_param("sssi", $search, $search, $search, $limit); //fornisce il parametro (s = stringa) al prepared statement

}
    
$stmt->execute(); //prepared statement eseguito
$result = $stmt->get_result(); //assegnato il risultato della query a $result

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

function clean($string) { 
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
}

/*
$sql = "SELECT id, date, site, game, white.name white_name, welo, result, black.name black_name, belo FROM matches_new
        INNER JOIN players white ON matches_new.white = white.id_player
        INNER JOIN players black ON matches_new.black = black.id_player
        WHERE MATCH(white_name, black_name, site) AGAINST('$search*' IN BOOLEAN MODE)
        ORDER BY date DESC LIMIT $limit";

$sql = "SELECT id, date, site, game, white.name white_name, welo, result, black.name black_name, belo FROM matches_new
        INNER JOIN (SELECT p.*, MATCH(name) AGAINST('$search*' IN BOOLEAN MODE) as M FROM players p)
            white ON matches_new.white = white.id_player
        INNER JOIN (SELECT p.*, MATCH(name) AGAINST('$search*' IN BOOLEAN MODE) as M FROM players p)
            black ON matches_new.black = black.id_player
        WHERE 
            MATCH(site) AGAINST('$search*' IN BOOLEAN MODE) OR white.M or black.M
        ORDER BY date DESC LIMIT $limit";
*/  
?>