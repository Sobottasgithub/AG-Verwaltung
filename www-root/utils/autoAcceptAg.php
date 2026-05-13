<?php
    require __DIR__ . "/../login/schulleitung.php";

    $agDataQuery = "SELECT Name, Leitung, Raum, Wochentag, FindetStatt FROM Ag";
    $agDataResult = $conn->query($agDataQuery);

    if ($agDataResult->rowCount() > 0) {
        while($agDataRow = $agDataResult->fetch(PDO::FETCH_ASSOC)) {            
            $teilnehmerCountQuery = "SELECT COUNT(*) AS count FROM Teilnahme WHERE AgName='" . $agDataRow["Name"] . "' AND Genehmigt=1";
            $teilnehmerCountResult = $conn->query($teilnehmerCountQuery);
            $count = $teilnehmerCountResult->fetch(PDO::FETCH_ASSOC);

            if ($count["count"] >= 10) {
                $query = "UPDATE Ag SET FindetStatt=true WHERE Name='" . $_POST['submitAgGenehmigung'] . "'";
                $result = $conn->query($query);
            }
        }
    }
    $conn=null;
?>
