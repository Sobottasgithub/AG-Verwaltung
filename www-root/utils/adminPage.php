<?php
    if (!isset($_COOKIE["adminLogin"])) {
        header('Location: /utils/admin.php');
    }

    require __DIR__ . "/../login/admin.php";
?>

<?php
    // SCHUELER
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

    //LEHRER
    if(isset($_POST["setPasswd"])) {
        $newPasswd = $_POST["passwd"];

        if ($newPasswd == "") {
            echo "<script type='text/javascript'>
                document.addEventListener('DOMContentLoaded', function() {
                    alert('Kein Passwort eingegben!');
                });</script>";
        } else {
            $passwordHash = password_hash($newPasswd, PASSWORD_ARGON2ID, ['memory_cost' => 1<<17, 'time_cost' => 4, 'threads' => 2]);

            $query = "SELECT * FROM LehrerLogin WHERE Kuerzel='".$_POST["setPasswd"]."'";
            $result = $conn->query($query);

            if ($result->rowCount() > 0) {
                $query = "UPDATE LehrerLogin SET PasswordHash='".$passwordHash."' WHERE Kuerzel='".$_POST["setPasswd"]."';";
            } else {
                $query = "INSERT INTO LehrerLogin (Kuerzel, PasswordHash) VALUES ('".$_POST["setPasswd"]."', '".$passwordHash."');";
            }

            echo "<script type='text/javascript'>
                document.addEventListener('DOMContentLoaded', function() {
                    alert('Neues Passwort wurde gesetzt!');
                });</script>";

            $result = $conn->query($query);
        }
    }

    if(isset($_POST["delTeacher"])) {
        $hasLehrerLoginQuery = "SELECT * FROM LehrerLogin WHERE Kuerzel='".$_POST["delTeacher"]."'";
        $hasLehrerLoginResult = $conn->query($hasLehrerLoginQuery);
        if ($hasLehrerLoginResult->rowCount() > 0) {
            $deleteLehrerLoginQuery = "DELETE FROM LehrerLogin WHERE Kuerzel='".$_POST["delTeacher"]."';";
            $deleteLehrerLoginResult = $conn->query($deleteLehrerLoginQuery);
        }

        $isInSchulleitungQuery = "SELECT * FROM Schulleitung WHERE Kuerzel='".$_POST["delTeacher"]."'";
        $isInSchulleitungResult = $conn->query($isInSchulleitungQuery);
        if ($isInSchulleitungResult->rowCount() > 0) {
            $deleteSchulleitungQuery = "DELETE FROM Schulleitung WHERE Kuerzel='".$_POST["delTeacher"]."';";
            $deleteSchulleitungResult = $conn->query($deleteSchulleitungQuery);
        }

        
        $deleteLehrerQuery = "DELETE FROM Lehrer WHERE Kuerzel='".$_POST["delTeacher"]."';";
        $deleteLehrerResult = $conn->query($deleteLehrerQuery);
    }

    if(isset($_POST["submitNewTeacher"])) {
        $firstName = $_POST["firstNameNewTeacher"];
        $lastName = $_POST["lastNameNewTeacher"];
        $newPasswd = $_POST["passwdNewTeacher"];

        echo "<script type='text/javascript'>
            document.addEventListener('DOMContentLoaded', function() {";
        if ($firstName != "" && $lastName != "" && $newPasswd != "") {
            $newKuerzel = str_replace("ä", "ae", $lastName);
            $newKuerzel = str_replace("ü", "ue", $newKuerzel);
            $newKuerzel = str_replace("ö", "oe", $newKuerzel);
            $newKuerzel = str_replace("Ä", "Ae", $newKuerzel);
            $newKuerzel = str_replace("Ü", "Ue", $newKuerzel);
            $newKuerzel = str_replace("Ö", "Oe", $newKuerzel);
            $newKuerzel = str_replace("ß", "ss", $newKuerzel);

            $newKuerzel = strtoupper($newKuerzel);
            $newKuerzel = substr($newKuerzel,0,4);

            $selectWhereKuerzelQuery = "SELECT * FROM Lehrer WHERE Kuerzel='".$newKuerzel."';";
            $selectWhereKuerzelResult = $conn->query($selectWhereKuerzelQuery);


            if ($selectWhereKuerzelResult->rowCount() == 0) {
                $insertNewTeacherQuery = "INSERT INTO Lehrer (Kuerzel, Vorname, Nachname) VALUES ('".$newKuerzel."', '".$firstName."', '".$lastName."');";
                $insertNewTeacherResult = $conn->query($insertNewTeacherQuery);
                echo "document.getElementById('teacherCreateStatus').textContent='Lehrer erstellt!';";
            } else {
                echo "document.getElementById('teacherCreateStatus').textContent='Lehrer Kürzel existiert schon!';";
            }            
        } else {
            if ($firstName == "") {
                echo "document.getElementById('firstNameNewTeacher').style.backgroundColor = 'red';";
            }
            if ($lastName == "") {
                echo "document.getElementById('lastNameNewTeacher').style.backgroundColor = 'red';";
            }
            if ($newPasswd == "") {
                echo "document.getElementById('passwdNewTeacher').style.backgroundColor = 'red';";
            }
        }
        echo "});</script>";
    }

    if (isset($_POST["promote"])) {
        $description = $_POST["description"];
        $lehrerKuerzelPromote = $_POST["lehrerKuerzelPromote"];

        $promoteTeacherQuery = "INSERT INTO Schulleitung (Kuerzel, Bezeichnung) VALUES ('".$lehrerKuerzelPromote."', '".$description."')";
        $promoteTeacherResult = $conn->query($promoteTeacherQuery);
    }

    if (isset($_POST["submitNewAg"])) {
        $newAgName = $_POST["newAgName"];
        $newAgRoom = $_POST["newAgRoom"];
        $newAgDescription = $_POST["newAgDescription"];
        $newAgLeitungKuerzel = $_POST["newAgLeitungKuerzel"];
        $newAgWochentag = $_POST["newAgWochentag"];

        echo "<script type='text/javascript'>
            document.addEventListener('DOMContentLoaded', function() {";
        if ($newAgName != "" && $newAgRoom != "" && $newAgDescription != "") {
            $selectAgWithSameNameQuery = "SELECT * FROM Ag WHERE Name='".$newAgName."';";
            $selectAgWithSameNameResult = $conn->query($selectAgWithSameNameQuery);

            if ($selectAgWithSameNameResult->rowCount() == 0) {
                $selectAgWithSameNameQuery = "INSERT INTO Ag (Name, Leitung, Raum, Wochentag, FindetStatt, Beschreibung) VALUES ('".$newAgName."', '".$newAgLeitungKuerzel."', '".$newAgRoom."', '".$newAgWochentag."', false, '".$newAgDescription."');";
                $selectAgWithSameNameResult = $conn->query($selectAgWithSameNameQuery);

                echo "document.getElementById('newAgStatus').textContent='Neue Ag erstellt!';";
            } else {
                echo "document.getElementById('newAgStatus').textContent='AG existiert schon!';";
            }
        } else {
            if ($newAgName == "") {
                echo "document.getElementById('newAgName').style.backgroundColor = 'red';";
            }
            if ($newAgRoom == "") {
                echo "document.getElementById('newAgRoom').style.backgroundColor = 'red';";
            }
            if ($newAgDescription == "") {
                echo "document.getElementById('newAgDescription').style.backgroundColor = 'red';";
            }
        }
        echo "});</script>";
    }

    if (isset($_POST["deleteAg"])) {
        $deleteAgName = $_POST["deleteAgName"];

        $deleteAgTeilnahmeQuery = "DELETE FROM Teilnahme WHERE AgName='".$deleteAgName."';";
        $deleteAgTeilnahmeResult = $conn->query($deleteAgTeilnahmeQuery);
        
        $deleteAgQuery = "DELETE FROM Ag WHERE Name='".$deleteAgName."';";
        $deleteAgResult = $conn->query($deleteAgQuery);

        echo "<script type='text/javascript'>
            document.addEventListener('DOMContentLoaded', function() {
                alert('Ag wurde gelöscht!');
            });</script>";
    }
?>

<?php
    echo "<h1>Schüler</h1>";
    // SCHUELER
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
            $tableRow = $tableRow."<td><form method='post'><button type='submit' name='delTeilnahme' id='delTeilnahme' value='".$row["TID"]."'>Löschen</button></form></td></tr>";
            echo $tableRow;
           
        }
    }
    echo "</table>";

    echo "<h1>Lehrer</h1>";
    // LEHRER
    $lehrerQuery = "SELECT * FROM Lehrer;";
    $lehrerResult = $conn->query($lehrerQuery);

    echo "<table>";
    echo "<tr><td>Kürzel</td><td>Vorname</td><td>Nachname</td><td></td><td></td><td></td></tr>";
    if($lehrerResult->rowCount() > 0) {
        while($lehrerRow = $lehrerResult->fetch(PDO::FETCH_ASSOC)) {
            $lehrerTableRow = "<tr><form method='post'><td>".$lehrerRow["Kuerzel"]."</td>".
                "<td>".$lehrerRow["Vorname"]."</td>".
                "<td>".$lehrerRow["Nachname"]."</td>";
            $lehrerTableRow = $lehrerTableRow."<td><input id='passwd' name='passwd' placeholder='Neues Passwort'/></td>";
            $lehrerTableRow = $lehrerTableRow."<td><button type='submit' name='setPasswd' id='setPasswd' value='".$lehrerRow["Kuerzel"]."'>Set Passwort</button></td>";
            $lehrerTableRow = $lehrerTableRow."<td><button type='submit' name='delTeacher' id='delTeacher' value='".$lehrerRow["Kuerzel"]."'>Löschen</button></td>";
            $lehrerTableRow = $lehrerTableRow."</form></tr>";
            echo $lehrerTableRow;
        }
    }
    echo "</table>";
?>

<form method="post">
    <p id="teacherCreateStatus" name="teacherCreateStatus"></p>
    <input type="text" id="firstNameNewTeacher" name="firstNameNewTeacher" placeholder="Vorname"/>
    <input type="text" id="lastNameNewTeacher" name="lastNameNewTeacher" placeholder="Nachname"/>
    <input type="password" id="passwdNewTeacher" name="passwdNewTeacher" placeholder="Passwort"/>
    <button id="submitNewTeacher" name="submitNewTeacher" type="submit">Create</button>
</form>

<h1>Schulleitung</h1>
<form method="post">
    <select name="lehrerKuerzelPromote" id="lehrerKuerzelPromote">
        <?php
            $selectLehrerKuerzelQuery = "SELECT Kuerzel FROM Lehrer WHERE Kuerzel not in (SELECT Kuerzel FROM Schulleitung);";
            $selectLehrerKuerzelResult = $conn->query($selectLehrerKuerzelQuery);

            for ($index = 0; $selectLehrerKuerzelResult->rowCount() > $index; $index++) {
                $row = $selectLehrerKuerzelResult->fetch(PDO::FETCH_ASSOC);
                echo "<option value='" . $row["Kuerzel"] . "'>" . $row["Kuerzel"] . "</option>";
            }
        ?>
    </select>
    <input id="description" name="description" type="text" placeholder="Titel/Beschreibung"/>
    <button id="promote" name="promote">Befördern</button>
</form>

<h1>AG</h1>
<h2>Neue AG erstellen</h2>
<p id="newAgStatus" name="newAgStatus"></p>
<form method="post">
    <input type="text" id="newAgName" name="newAgName" placeholder="AG Name"/>
    <select id="newAgLeitungKuerzel" name="newAgLeitungKuerzel">
        <?php
            $teacherKuerzelQuery = "SELECT Kuerzel FROM Lehrer;";
            $teacherKuerzelResult = $conn->query($teacherKuerzelQuery);

            for ($index = 0; $teacherKuerzelResult->rowCount() > $index; $index++) {
                $row = $teacherKuerzelResult->fetch(PDO::FETCH_ASSOC);
                echo "<option value='" . $row["Kuerzel"] . "'>" . $row["Kuerzel"] . "</option>";
            }
        ?>
    </select>
    <select id="newAgWochentag" name="newAgWochentag">
        <option id="Montag" name="Montag">Montag</option>
        <option id="Dienstag" name="Dienstag">Dienstag</option>
        <option id="Mittwoch" name="Mittwoch">Mittwoch</option>
        <option id="Donnerstag" name="Donnerstag">Donnerstag</option>
        <option id="Freitag" name="Freitag">Freitag</option>
    </select>
    <input type="text" id="newAgRoom" name="newAgRoom" placeholder="Raum"/>
    <input type="text" id="newAgDescription" name="newAgDescription" placeholder="Beschreibung"/>
    <button type="submit" id="submitNewAg" name="submitNewAg">Erstellen</button>
</form>
<h2>AG löschen</h2>
<form method="post">
    <select id="deleteAgName" name="deleteAgName">
        <?php
            $deleteAgNameQuery = "SELECT Name FROM Ag;";
            $deleteAgNameResult = $conn->query($deleteAgNameQuery);

            for ($index = 0; $deleteAgNameResult->rowCount() > $index; $index++) {
                $row = $deleteAgNameResult->fetch(PDO::FETCH_ASSOC);
                echo "<option value='" . $row["Name"] . "'>" . $row["Name"] . "</option>";
            }
        ?>
    </select>
    <button type="submit" name="deleteAg" id="deleteAg">Löschen</button>
</form>

<?php
    $conn = null;
?>
