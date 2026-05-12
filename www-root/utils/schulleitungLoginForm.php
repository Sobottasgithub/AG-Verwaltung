<?php
    if (isset($_POST['submitLogin'])) {
        $kuerzel = $_POST['kuerzel'];
        $password = $_POST['password'];

        $status = "";
        if ($kuerzel != "" and $password != "") {
            require __DIR__ . "/../login/schulleitung.php";
            $query="SELECT PasswordHash FROM Schulleitung LEFT JOIN LehrerLogin ON Schulleitung.Kuerzel=LehrerLogin.Kuerzel WHERE Schulleitung.Kuerzel='" . $kuerzel . "'";
            $result = $conn->query($query);
            if($result->rowCount()==1) {
                $row = $result->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $row["PasswordHash"])) {
                    $status = "document.getElementById('status').textContent='Angemeldet!';";
                    setcookie("schulleitungLogin", $kuerzel, [
                        'expires' => time() + 3600,
                        'path' => '/',
                        'domain' => '',
                        'httponly' => true,
                        'samesite' => 'Lax'
                    ]);
                    header('Location: /utils/schulleitung.php');
                } else {
                    $status = "document.getElementById('status').textContent='Falsches Kürzel, Passwort oder sie sind kein Teil der Schulleitung! ". $row["PasswordHash"]."';";
                }
            } else {
                $status = "document.getElementById('status').textContent='Falsches Kürzel, Passwort oder sie sind kein Teil der Schulleitung!';"; 
            }
            $conn=null;
        }
        echo "<script type='text/javascript'>
            document.addEventListener('DOMContentLoaded', function() {";
        echo $status;
        if ($kuerzel == "") {
            echo "document.getElementById('kuerzel').style.backgroundColor = 'red';
                  document.getElementById('status').textContent='Fill out both input boxes!';";
        }
        if ($password == "") {
            echo "document.getElementById('password').style.backgroundColor = 'red';
                  document.getElementById('status').textContent='Fill out both input boxes!';";
        }

        echo "});</script>";
    }
?>
<a href="../index.php">Back</a>
<form method="post">
    <input id="kuerzel" name="kuerzel" type="text" placeholder="Kürzel"/>
    <input id="password" name="password" type="password" placeholder="Passwort"/>
    <p id="status"></p>
    <button type="submit" name="submitLogin">Anmelden</button>
</form>
