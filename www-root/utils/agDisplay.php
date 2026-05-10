<?php
    require __DIR__ . "/../login/defaultUser.php";

    $query = "SELECT Name, Vorname, Nachname, Leitung, Raum, Wochentag FROM Ag JOIN Lehrer ON Ag.Leitung = Lehrer.Kuerzel";
    $result = $conn->query($query);

    echo "<table>";
    echo "<tr><td>Vorname</td><td>Nachname</td><td>Leitung</td><td>Raum</td><td>Wochentag</td></tr>";
    echo "<form action='/utils/agDetails.php' method='post'>";
    if ($result->rowCount() > 0) {
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td><button type='submit' name='agName' value='".$row["Name"]."'>" . $row["Name"] . "</button></td>";
            echo "<td>" . $row["Vorname"] . "</td>";
            echo "<td>" . $row["Nachname"] . "</td>";
            echo "<td>" . $row["Leitung"] . "</td>";
            echo "<td>" . $row["Raum"] . "</td>";
            echo "<td>" . $row["Wochentag"] . "</td>";
            echo "</tr>";
        }
    }
    echo "</form>";
    echo "</table>";

    $conn = null;
?>
