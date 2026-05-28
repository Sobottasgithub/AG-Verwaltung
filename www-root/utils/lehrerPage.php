<?php
    if (!isset($_COOKIE["lehrerLogin"])) {
        header('Location: /utils/lehrer.php');
    }

    require __DIR__ . "/../login/lehrer.php";

    if (isset($_POST["genehmigen"])) {
        $sid = $_POST["genehmigen"];
        $setGenehmigtStatement = $conn->prepare("UPDATE Teilnahme SET genehmigt=true WHERE SID = :sid");
        $setGenehmigtStatement->execute([
            ":sid" => $sid   
        ]);
        
        $conn = null;
        require __DIR__ . "/autoAcceptAg.php";
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>AG-Verwaltung - Lehrer</title>
        <link rel="stylesheet" href="../styles/lehrerPage.css">
    </head>
    <body>
        <div class="center">
            <div class="pageBody">
                <form action="../index.php"><button type="submit">Back</button></form>
                <br>
                <div class="center">
                    <div>
                        <?php
                            require __DIR__ . "/../login/lehrer.php";
                            
                            $query = "SELECT Vorname, Nachname FROM Lehrer WHERE Kuerzel='".$_COOKIE["lehrerLogin"]."'";
                            $result = $conn->query($query);
                            if ($result->rowCount() > 0) {
                                $row = $result->fetch(PDO::FETCH_ASSOC);
                                echo "<h1>Guten Tag " . $row["Vorname"] . " " . $row["Nachname"] . "!</h1>";
                            }
                        ?>
                        <h2>Anmeldungen für ihre Arbeitsgemeinschaft(en)</h2>
                        <?php
                            $query = "SELECT Name FROM Ag WHERE Leitung='".$_COOKIE["lehrerLogin"]."'";
                            $result = $conn->query($query);
                            
                            echo "<form method='post'><table>";
                            echo "<tr><th>AG</th><th>Vorname</th><th>Nachname</th><th>Email</th><th>Klasse</th><th>Genehmigen</th></tr>";

                            if ($result->rowCount() > 0) {
                                $selectSchuelerStatement = $conn->prepare("SELECT * FROM Schueler NATURAL JOIN Teilnahme WHERE AgName = :agName");
                                while($agRow = $result->fetch(PDO::FETCH_ASSOC)) {
                                    $selectSchuelerStatement->execute([':agName' => $agRow['Name']]);
                                    $selectSchuelerResult = $selectSchuelerStatement->fetchAll(PDO::FETCH_ASSOC);
                                                                            
                                    if (count($selectSchuelerResult) > 0) {
                                        foreach($selectSchuelerResult as $schuelerRow) {
                                            echo "<tr><td>" . $agRow["Name"] . "</td><td>" . $schuelerRow["Vorname"] . "</td><td>" . $schuelerRow["Nachname"] . "</td><td>" . $schuelerRow["Email"] . "</td><td>" . $schuelerRow["Klasse"] . "</td><td>";
                                            if ($schuelerRow["Genehmigt"] == 0) {
                                                echo "<button type='submit' name='genehmigen' value='" . $schuelerRow["SID"] . "'>Genehmigen</button>";
                                            } else {
                                                echo "Genehmigt";
                                            }
                                            echo "</td></tr>";
                                        }
                                    }
                                }
                            }
                            echo "</form></table>";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
