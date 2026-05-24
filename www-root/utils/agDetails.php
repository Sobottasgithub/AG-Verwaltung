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

                                $query = "SELECT Name, Vorname, Nachname, Leitung, Raum, Wochentag, Beschreibung
                                         FROM Ag JOIN Lehrer ON Ag.Leitung = Lehrer.Kuerzel WHERE Name='".$_POST['agName']."'";
                                $result = $conn->query($query);

                                $teilnehmerCountQuery = "SELECT COUNT(*) AS count FROM Teilnahme WHERE AgName='" . $_POST['agName']. "' AND Genehmigt=1";
                                $teilnehmerCountResult = $conn->query($teilnehmerCountQuery);
                                $count = $teilnehmerCountResult->fetch(PDO::FETCH_ASSOC); 
            
                                if ($result->rowCount() == 1) {
                                    $row = $result->fetch(PDO::FETCH_ASSOC);
                                    echo "<h1>" . $row["Name"] . "</h1>";
                                    echo "<table>";
                                    echo "<tr><td>Vorname</td><td>Nachname</td><td>Leitung</td><td>Raum</td><td>Wochentag</td><td>Teilnehmer</td></tr>";
                                    echo "<tr>";
                                    echo "<td>" . $row["Vorname"] . "</td>";
                                    echo "<td>" . $row["Nachname"] . "</td>";
                                    echo "<td>" . $row["Leitung"] . "</td>";
                                    echo "<td>" . $row["Raum"] . "</td>";
                                    echo "<td>" . $row["Wochentag"] . "</td>";
                                    echo "<td>" . $count["count"] . "</td>";
                                    echo "</tr>";
                                    echo "</table><br>";
                                    echo "<p>" . $row["Beschreibung"] . "</p>";
                                }
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
