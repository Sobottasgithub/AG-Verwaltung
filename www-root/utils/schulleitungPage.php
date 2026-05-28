<?php
    if (!isset($_COOKIE["schulleitungLogin"])) {
        header('Location: /utils/schulleitung.php');
    }
    require __DIR__ . "/autoAcceptAg.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>AG-Verwaltung - Schulleitung</title>
        <link rel="stylesheet" href="../styles/schulleitungPage.css">
    </head>
    <body>
        <div class="center">
            <div class="pageBody">
                <form action="../index.php"><button type="submit">Back</button></form>
                <br>
                <div class="center">
                    <div>
                        <?php
                            require __DIR__ . "/../login/schulleitung.php";

                            $query = "SELECT Vorname, Nachname FROM Lehrer WHERE Kuerzel='".$_COOKIE["schulleitungLogin"]."'";
                            $result = $conn->query($query);
                            if ($result->rowCount() > 0) {
                                $row = $result->fetch(PDO::FETCH_ASSOC);
                                echo "<h1>Guten Tag " . $row["Vorname"] . " " . $row["Nachname"] . "!</h1>";
                            }
                        ?>
                        <h2>Arbeitsgemeinschaften</h2>
                        <?php
                            if (isset($_POST['submitAgGenehmigung'])) {
                                $agName = $_POST['submitAgGenehmigung'];
                                $setFindetStattStatement = $conn->prepare("UPDATE Ag SET FindetStatt=true WHERE Name = :name");
                                $setFindetStattStatement->execute([
                                   ":name" => $agName
                                ]);
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
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
