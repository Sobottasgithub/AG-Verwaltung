<?php
    if (isset($_POST['submitSchulleitungLogin'])) {
        $kuerzel = $_POST['schulleitungKuerzel'];
        $userPassword = $_POST['schulleitungPassword'];
        $status = "";
        if ($kuerzel != "" and $userPassword != "") {
            require __DIR__ . "/../login/schulleitung.php";
            $query="SELECT PasswordHash FROM Schulleitung LEFT JOIN LehrerLogin ON Schulleitung.Kuerzel=LehrerLogin.Kuerzel WHERE Schulleitung.Kuerzel='" . $kuerzel . "'";
            $result = $conn->query($query);
            if($result->rowCount()==1) {
                $row = $result->fetch(PDO::FETCH_ASSOC);
                if (password_verify($userPassword, $row["PasswordHash"])) {
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
                    $status = "document.getElementById('status').textContent='Falsches Kürzel, Passwort oder sie sind kein Teil der Schulleitung!';";
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
                  document.getElementById('status').textContent='Bitte fülle alle Pflichtfelder aus!';";
        }
        if ($userPassword == "") {
            echo "document.getElementById('password').style.backgroundColor = 'red';
                  document.getElementById('status').textContent='Bitte fülle alle Pflichtfelder aus!';";
        }

        echo "});</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AG-Verwaltung - Schulleitung</title>
    <link rel="stylesheet" href="../styles/login.css">
  </head>
  <body>
    <div class="pageBody">
        <form action="../index.php"><button type="submit">Back</button></form>
        <br>
        <div class="center">
            <form method="post">
                <h2>Schulleitung Login</h2>
                <input id="schulleitungKuerzel" name="schulleitungKuerzel" type="text" placeholder="Kürzel"/>*
                <input id="schulleitungPassword" name="schulleitungPassword" type="password" placeholder="Passwort"/>*<br>
                <span>* Pflichtfelder</span>
                <p id="status"></p>
                <button type="submit" name="submitSchulleitungLogin">Anmelden</button>
            </form>
        </div>
    </div>
  </body>
</html>
