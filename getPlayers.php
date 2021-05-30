<?php

session_start();
include "connect.php";

$date = $_GET["date"];

$sql = "
SELECT players.id_player id, date, rank, rating, name, country_name, title_name, games
FROM rankings
INNER JOIN players ON rankings.id_player = players.id_player
INNER JOIN countries ON players.id_country = countries.id_country
INNER JOIN titles ON players.id_title = titles.id_title
WHERE date = ?
ORDER BY rank ASC, rating DESC;
";

$date = $date . "-27";

$stmt = $conn->prepare($sql); 
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        
        if ($row["rank"] == 1 or $row["rank"] == 2 or $row["rank"] == 3) { 
            
            if($row["rank"] == 1) {
                echo "<div class='center'>";
                echo "<div class='player first'>";        
            }
            else if($row["rank"] == 2) {
                echo "<div class='player second'>";        
            }
            else if($row["rank"] == 3) {
                echo "<div class='player third'>";        
            }

            echo "<img class='player' src='";
            $position = strpos($row["name"], ",");
            $filename = substr($row["name"], 0, $position);
            if (file_exists("src/players/" . $filename . ".png")) {
                echo "src/players/" . $filename . ".png";
            }
            else {
                echo "src/players/404.png";
            }
            echo "'>";
            echo "<div class='info'>
                <h2>" . $row["rank"] . "° - " . $row["rating"] . "</h2>
                <h1>" . $row["name"] . "</h1>";

            echo "<h3>" . $row["country_name"] . "</h3>
                <h5>" . $row["title_name"] . " - " . $row["games"] . " games</h5></div>";

            if ($_SESSION["is_login"]) {
                $id_user = $_SESSION["id"];
                $id_player = $row["id"];
                $sql2 = "SELECT * FROM fav_players WHERE id_user = ? AND id_player = ?";

                $stmt2 = $conn->prepare($sql2); 
                $stmt2->bind_param("ii", $id_user, $id_player);
                $stmt2->execute();
                $result2 = $stmt2->get_result();

                if ($result2->num_rows > 0) {
                    echo "<i class='fas fa-heart' onclick='addPlayerToFav(this, " . $row["id"] . ")'> </i>";
                }
                else {
                    echo "<i class='far fa-heart' onclick='addPlayerToFav(this, " . $row["id"] . ")'> </i>";
                }
            }
            else {
                echo "<i class='far fa-heart' onclick=\"window.location.href='account.php'\"></i>";
            }

            echo "<form method='POST' action='player.php'>
            <input type='hidden' name='id' value='" . $row["id"] . "'>
            <input type='submit' value='Info' name='submit'>
            </form>";
            
            echo "</div>";

            if($row["rank"] == 3) {
            echo "</div>";

                echo "
                <div class='table'>
                <table>
                 <tr>
                 <th> Rank </th>
                 <th> Name </th>
                 <th> Rating </th>
                 <th> Federation </th>
                 <th> Title </th>
                 <th> Games </th>
                 <th style='min-width: 80px'> More info </th>
                 <th class='center'> Favourite </th>
                 </tr>
                 ";
            }


            continue;

        }

        echo "<tr>";
        echo "
        <td>" . $row["rank"] . "°</td>
        <td>" . $row["name"] . "</td>
        <td>" . $row["rating"] . "</td>
        <td>" . $row["country_name"] . "</td>
        <td>" . $row["title_name"] . "</td>
        <td>" . $row["games"] . "</td>";
        
        echo "<td class='center'><form method='POST' action='player.php'><input type='hidden' name='id' value='" . $row["id"] . "'>
        <input type='submit' value='Info' name='submit'></form></td>";
        
        echo "<td class='center'>";
        if ($_SESSION["is_login"]) {
            $id_user = $_SESSION["id"];
            $id_player = $row["id"];
            $sql2 = "SELECT * FROM fav_players WHERE id_user = ? AND id_player = ?";

            $stmt2 = $conn->prepare($sql2); 
            $stmt2->bind_param("ii", $id_user, $id_player);
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            if ($result2->num_rows > 0) {
                echo "<i class='fas fa-heart' onclick='addPlayerToFav(this, " . $row["id"] . ")'></i>";
            }
            else {
                echo "<i class='far fa-heart' onclick='addPlayerToFav(this, " . $row["id"] . ")'></i>";
            }
        }
        else {
            echo "<i class='far fa-heart' onclick=\"window.location.href='account.php'\"></i>";
        }

        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "0 results";
}

echo "</table></div>";

?>