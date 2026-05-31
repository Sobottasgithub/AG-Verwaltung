<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>AG-Verwaltung - Details</title>
        <link rel="stylesheet" href="../styles/details.css">
    </head>
    <body>
        <div class="center">
            <div class="pageBody">
                <form action="../index.php"><button type="submit">Back</button></form>
                <br>
                <div class="center">
                    <div>
                        <?php
                            if(isset($_POST['agName'])){
                                require __DIR__ . "/../login/defaultUser.php";

                                $agName = $_POST['agName'];
                                $getAgStatement = $conn->prepare("SELECT Name, Vorname, Nachname, Leitung, Raum, Wochentag, Beschreibung, Uhrzeit
                                         FROM Ag JOIN Lehrer ON Ag.Leitung = Lehrer.Kuerzel WHERE Name = :name");
                                $getAgStatement->execute([':name' => $agName]);

                                $teilnehmerCountStatement = $conn->prepare("SELECT COUNT(*) AS count FROM Teilnahme WHERE AgName = :name AND Genehmigt=1");
                                $teilnehmerCountStatement->execute([':name' => $agName]);
                                
                                $count = $teilnehmerCountStatement->fetch(PDO::FETCH_ASSOC); 

                                $row = $getAgStatement->fetch(PDO::FETCH_ASSOC);
                                echo "<h1>" . $row["Name"] . "</h1>";
                                echo "<table>";
                                echo "<tr><th>Vorname</th><th>Nachname</th><th>Leitung</th><th>Raum</th><th>Wochentag</th><th>Uhrzeit</th><th>Teilnehmer</th></tr>";
                                echo "<tr>";
                                echo "<td>" . $row["Vorname"] . "</td>";
                                echo "<td>" . $row["Nachname"] . "</td>";
                                echo "<td>" . $row["Leitung"] . "</td>";
                                echo "<td>" . $row["Raum"] . "</td>";
                                echo "<td>" . $row["Wochentag"] . "</td>";
                                echo "<td>" . $row["Uhrzeit"] . "</td>";
                                echo "<td>" . $count["count"] . "</td>";
                                echo "</tr>";
                                echo "</table><br>";
                                echo "<p>" . $row["Beschreibung"] . "</p>";
                                
                                $conn = null;
                            } else {
                                echo "Something went wrong!";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
