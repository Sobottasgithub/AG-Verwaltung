<?php
    require __DIR__ . "/../login/schulleitung.php";

    $agDataQuery = "SELECT Name, Leitung, Raum, Wochentag, FindetStatt FROM Ag";
    $agDataResult = $conn->query($agDataQuery);

    if ($agDataResult->rowCount() > 0) {
        while($agDataRow = $agDataResult->fetch(PDO::FETCH_ASSOC)) {            
            $teilnehmerCountStatement = $conn->prepare("SELECT COUNT(*) AS count FROM Teilnahme WHERE AgName = :name AND Genehmigt=1");
            $teilnehmerCountStatement->execute([':name' => $agDataRow["Name"]]);
            $count = $teilnehmerCountStatement->fetch(PDO::FETCH_ASSOC);
            
            if ($count["count"] >= 10) {
                $setGenehmigtStatement = $conn->prepare("UPDATE Ag SET FindetStatt=true WHERE Name = :name");
                $setGenehmigtStatement->execute([':name' => $agDataRow["Name"]]);
            }
        }
    }
    $conn=null;
?>
