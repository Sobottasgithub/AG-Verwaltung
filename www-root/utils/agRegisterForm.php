<?php
    // Set to "" here to allow to be used as input value before submit has been pressed
    $vorname = "";
    $nachname = "";
    $email = "";

    if(isset($_POST['submitAnmeldung'])) {
        $klasse = $_POST['klasse'];
        $ag = $_POST['ag'];
        $vorname = $_POST['vorname'];
        $nachname = $_POST['nachname'];
        $email = $_POST['email'];

        echo "<script type='text/javascript'>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('klasse').value = '" . $klasse . "';";
        echo "document.getElementById('ag').value = '" . $ag . "';";
        if ($vorname == "") {
            echo "document.getElementById('vorname').style.backgroundColor = 'red';";
        }
        if ($nachname == "") {
            echo "document.getElementById('nachname').style.backgroundColor = 'red';";
        }
        if ($email == "") {
            echo "document.getElementById('email').style.backgroundColor = 'red';";
        }
        echo "});</script>";

        if ($vorname != "" and $nachname != "" and $email != "") {
            require __DIR__ . "/../login/defaultUser.php";

            // Is student already in AG?
            $query="SELECT SID FROM Schueler NATURAL JOIN Teilnahme WHERE
                    Vorname='".$vorname."' AND Nachname='".$nachname."' AND Klasse='".$klasse."' AND AgName='".$ag."'";
            $result = $conn->query($query);
            if($result->rowCount() == 0) {
                // Does student exist?
                $query="SELECT SID FROM Schueler NATURAL JOIN Teilnahme WHERE Vorname='".$vorname."' AND Nachname='".$nachname."' AND Klasse='".$klasse."'";
                $result = $conn->query($query);
                
                if ($result->rowCount() == 0) {
                    // Create student
                    $query="INSERT INTO Schueler (Vorname, Nachname, Email, Klasse) VALUES (:vorname, :nachname, :email, :klasse)";
                    $result = $conn->prepare($query);
                    $result->execute([
                        ":vorname" => $vorname,
                        ":nachname" => $nachname,
                        ":email" => $email,
                        ":klasse" => $klasse
                    ]);
        
                    // Fetch SID again
                    $query="SELECT SID FROM Schueler NATURAL JOIN Teilnahme WHERE Vorname='".$vorname."' AND Nachname='".$nachname."' AND Klasse='".$klasse."'";
                    $result = $conn->query($query);
                }

                $row = $result->fetch(PDO::FETCH_ASSOC);
                $sid = $row["SID"];
                echo $sid;
                $query="INSERT INTO Teilnahme (AGName, SID) VALUES (:ag, :sid)";
                $result = $conn->prepare($query);
                $result->execute([
                    ":ag" => $ag,
                    ":sid" => $sid
                ]);
            }

            $conn=null;
        }
    }
?>

<form method="post">
    <input type="text" placeholder="Vorname" name="vorname" id="vorname" <?php echo "value='".$vorname."'"?>/>*<br/>
    <input type="text" placeholder="Nachname" name="nachname" id="nachname" <?php echo "value='".$nachname."'"?>/>*<br/>
    <input type="email" placeholder="email@example.com" name="email" id="email" <?php echo "value='".$email."'"?>/>*<br/>
    <?php
        require __DIR__ . "/../login/defaultUser.php";

        $query = "SELECT Klasse FROM Klassen ORDER BY CAST(Klasse AS UNSIGNED) ASC, Klasse ASC";
        $result = $conn->query($query);

        echo "<select name='klasse' id='klasse'>";
        for ($index = 0; $result->rowCount() > $index; $index++) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            echo "<option value='" . $row["Klasse"] . "'>" . $row["Klasse"] . "</option>";
        }
        echo "</select>";
        
        $query = "SELECT Name FROM Ag";
        $result = $conn->query($query);

        echo "<select name='ag' id='ag'>";
        for ($index = 0; $result->rowCount() > $index; $index++) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            echo "<option value='" . $row["Name"] . "'>" . $row["Name"] . "</option>";
        }
        echo "</select>";
        
        $conn = null;
    ?>
    <br/>
    * Pflichtfelder
    <br>
    <button type="submit" name="submitAnmeldung">Anmelden</button>
</form>
