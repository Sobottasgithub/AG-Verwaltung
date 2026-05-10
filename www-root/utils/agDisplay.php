<?php
    require __DIR__ . "/../login/defaultUser.php";

    $query = "SELECT Vorname, Nachname, Leitung, Raum, Wochentag FROM Ag JOIN Lehrer ON Ag.Leitung = Lehrer.Kuerzel";
    $result = $conn->query($query);

    if ($result->rowCount() > 0) {
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo $row["Vorname"] . " " . $row["Nachname"] . " " . $row["Leitung"] . " " . $row["Raum"] . " " . $row["Wochentag"];
        }
    } else {
        echo "<table>Hello</table>";
    }

    $conn = null;
?>
