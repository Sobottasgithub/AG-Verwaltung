<?php
    require __DIR__ . "/../login/defaultUser.php";

    $query = "SELECT Name, Vorname, Nachname, Leitung, Raum, Wochentag FROM Ag JOIN Lehrer ON Ag.Leitung = Lehrer.Kuerzel";
    $result = $conn->query($query);

    echo "<form action='/utils/agDetails.php' method='post'>";
    echo "<table>";
    echo "<tr><th>AG</th><th>Lehrkraft</th><th>Kürzel</th><th>Raum</th><th>Wochentag</th></tr>";
    if ($result->rowCount() > 0) {
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td><button class='agButtonDisplay' type='submit' name='agName' value='".$row["Name"]."'>" . $row["Name"] . "</button></td>";
            echo "<td>" . $row["Vorname"] . " " . $row["Nachname"] . "</td>";
            echo "<td>" . $row["Leitung"] . "</td>";
            echo "<td>" . $row["Raum"] . "</td>";
            echo "<td>" . $row["Wochentag"] . "</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
    echo "</form>";

    $conn = null;
?>
