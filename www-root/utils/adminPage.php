<?php
    if (!isset($_COOKIE["adminLogin"])) {
        header('Location: /utils/admin.php');
    }

    require __DIR__ . "/../login/admin.php";
?>

<?php
    if(isset($_POST["delTeilnahme"])) {
        $tid = $_POST["delTeilnahme"];

        $query = "SELECT SID FROM Teilnahme WHERE TID='".$tid."';";
        $result = $conn->query($query);

        if($result->rowCount() > 0) {
            $row = $result->fetch(PDO::FETCH_ASSOC);

            $deleteTeilnahmeQuery = "DELETE FROM Teilnahme WHERE TID='".$tid."';";
            $deleteQueryResult = $conn->query($deleteTeilnahmeQuery);

            $teilnahmeCountSIDQuery = "SELECT COUNT(*) AS count FROM Teilnahme WHERE SID='".$row["SID"]."'";
            $teilnahmeCountSIDResult = $conn->query($teilnahmeCountSIDQuery);
            $teilnahmeCountSID = $teilnahmeCountSIDResult->fetch(PDO::FETCH_ASSOC);
            if ($teilnahmeCountSID["count"] < 1) {
                $deleteSID = "DELETE FROM Schueler WHERE SID='".$row["SID"]."';";
                $deleteSIDResult = $conn->query($deleteSID);
            }   
        }
    }
?>

<?php
    $query = "SELECT * FROM Teilnahme LEFT JOIN Schueler ON Teilnahme.SID = Schueler.SID;";
    $result = $conn->query($query);
    
    echo "<table>";
    echo "<tr><td>AG</td><td>Vorname</td><td>Nachname</td><td>Email</td><td>Klasse</td><td>Genehmigt(?)</td><td></td></tr>";
    if($result->rowCount() > 0) {
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
           $tableRow = "<tr><td>".$row["AgName"]."</td>".
                "<td>".$row["Vorname"]."</td>".
                "<td>".$row["Nachname"]."</td>".
                "<td>".$row["Email"]."</td>".
                "<td>".$row["Klasse"]."</td>";
            if ($row["Genehmigt"] == 1) {
                $tableRow = $tableRow."<td>Ja</td>";
            } else {
                $tableRow = $tableRow."<td>Nein</td>";
            }
            $tableRow = $tableRow."<td><form method='post'><button type='submit' name='delTeilnahme' id='delTeilnahme' value='".$row["TID"]."'>Löschen</button></form><td>";
            $tableRow = $tableRow."</tr>";
            echo $tableRow;
           
        }
    }
    echo "</table>";

    $conn = null;
?>
