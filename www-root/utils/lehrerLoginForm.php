<?php
    if (isset($_POST['submitLogin'])) {
        $kuerzel = $_POST['kuerzel'];
        $password = $_POST['password'];

        echo "<script type='text/javascript'>
            document.addEventListener('DOMContentLoaded', function() {";
        if ($kuerzel == "") {
            echo "document.getElementById('kuerzel').style.backgroundColor = 'red';
                  document.getElementById('status').textContent='Fill out both input boxes!';";
        }
        if ($password == "") {
            echo "document.getElementById('password').style.backgroundColor = 'red';
                  document.getElementById('status').textContent='Fill out both input boxes!';";
        }

        if ($kuerzel != "" and $password != "") {
            require __DIR__ . "/../login/lehrer.php";
            $query="SELECT PasswordHash FROM LehrerLogin WHERE Kuerzel='" . $kuerzel . "'";
            $result = $conn->query($query);
            if($result->rowCount()==1) {
                $row = $result->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $row["PasswordHash"])) {
                    echo "document.getElementById('status').textContent='Success!';";
                } else {
                    echo "document.getElementById('status').textContent='Falsches Kürzel oder Passwort! ". $row["PasswordHash"]."';";
                }
            } else {
                echo "document.getElementById('status').textContent='Falsches Kürzel oder Passwort!';"; 
            }
            $conn=null;
        }
        echo "});</script>";
    }
?>

<form method="post">
    <input id="kuerzel" name="kuerzel" type="text" placeholder="Kürzel"/>
    <input id="password" name="password" type="password" placeholder="Passwort"/>
    <p id="status"></p>
    <button type="submit" name="submitLogin">Anmelden</button>
</form>
