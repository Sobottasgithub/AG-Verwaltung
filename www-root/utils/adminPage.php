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
            echo "Abort!";
        }

        $passwordHash = password_hash($newPasswd, PASSWORD_ARGON2ID, ['memory_cost' => 1<<17, 'time_cost' => 4, 'threads' => 2]);

        $query = "SELECT * FROM LehrerLogin WHERE Kuerzel='".$_POST["setPasswd"]."'";
        $result = $conn->query($query);

        if ($result->rowCount() > 0) {
            $query = "UPDATE LehrerLogin SET PasswordHash='".$passwordHash."' WHERE Kuerzel='".$_POST["setPasswd"]."';";
        } else {
            $query = "INSERT INTO LehrerLogin (Kuerzel, PasswordHash) VALUES ('".$_POST["setPasswd"]."', '".$passwordHash."');";
        }
        $result = $conn->query($query);
    }

    if(isset($_POST["delTeacher"])) {
        $hasLehrerLoginQuery = "SELECT * FROM LehrerLogin WHERE Kuerzel='".$_POST["delTeacher"]."'";
        $hasLehrerLoginResult = $conn->query($hasLehrerLoginQuery);
        if ($hasLehrerLoginResult->rowCount() > 0) {
            $deleteLehrerLoginQuery = "DELETE FROM LehrerLogin WHERE Kuerzel='".$_POST["delTeacher"]."';";
            $deleteLehrerLoginResult = $conn->query($deleteLehrerLoginQuery);
        }
        
        $deleteLehrerQuery = "DELETE FROM Lehrer WHERE Kuerzel='".$_POST["delTeacher"]."';";
        $deleteLehrerResult = $conn->query($deleteLehrerQuery);
    }

    if(isset($_POST["submitNewTeacher"])) {
        $firstName = $_POST["firstNameNewTeacher"];
        $lastName = $_POST["lastNameNewTeacher"];
        $newPasswd = $_POST["passwdNewTeacher"];

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
            } else {
                echo "Teacher already exists!";
            }            
        }
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

    $conn = null;
?>

<form method="post">
    <p id="teacherCreateStatus" name="teacherCreateStatus"></p>
    <input type="text" id="firstNameNewTeacher" name="firstNameNewTeacher" placeholder="Vorname"/>
    <input type="text" id="lastNameNewTeacher" name="lastNameNewTeacher" placeholder="Nachname"/>
    <input type="password" id="passwdNewTeacher" name="passwdNewTeacher" placeholder="Passwort"/>
    <button id="submitNewTeacher" name="submitNewTeacher" type="submit">Create</button>
</form>

<h1>Schulleitung</h1>
