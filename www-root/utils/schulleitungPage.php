<?php
    require __DIR__ . "/../login/schulleitung.php";

    if (isset($_POST['submitAgGenehmigung'])) {
        $query = "UPDATE Ag SET FindetStatt=true WHERE Name='" . $_POST['submitAgGenehmigung'] . "'";
        $result = $conn->query($query);
    }

    $agDataQuery = "SELECT Name, Leitung, Raum, Wochentag, FindetStatt FROM Ag";
    $agDataResult = $conn->query($agDataQuery);

    echo "<table><form method='post'>";
    echo "<tr><td>Ag</td><td>Leitung</td><td>Raum</td><td>Wochentag</td><td>Teilnehmer</td><td>Findet Statt</td></tr>";
    if ($agDataResult->rowCount() > 0) {
        while($agDataRow = $agDataResult->fetch(PDO::FETCH_ASSOC)) {            
            echo "<tr>";
            echo "<td>" . $agDataRow["Name"] . "</td>";
            echo "<td>" . $agDataRow["Leitung"] . "</td>";
            echo "<td>" . $agDataRow["Raum"] . "</td>";
            echo "<td>" . $agDataRow["Wochentag"] . "</td>";

            $teilnehmerCountQuery = "SELECT COUNT(*) AS count FROM Teilnahme WHERE AgName='" . $agDataRow["Name"] . "' AND Genehmigt=1";
            $teilnehmerCountResult = $conn->query($teilnehmerCountQuery);
            $count = $teilnehmerCountResult->fetch(PDO::FETCH_ASSOC); 
            
            echo "<td>" . $count["count"] . "</td>";
            if ($agDataRow["FindetStatt"] == 0) {
                echo "<td><button name='submitAgGenehmigung' value='" . $agDataRow["Name"] . "' type='submit'>Genehmigen</button></td>";
            } else {
                echo "<td>Genehmigt!</td>";
            }
            echo "</tr>";   
        }
    }
    echo "</form></table>";
    $conn=null;
?>
<a href="../index.php">Back</a>
