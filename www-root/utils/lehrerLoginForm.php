<?php
    $kuerzel = "";
    $userPassword = "";
    if (isset($_POST['submitLogin'])) {
        $kuerzel = $_POST['kuerzel'];
        $userPassword = $_POST['password'];

        $status = "";
        if ($kuerzel != "" and $userPassword != "") {
            require __DIR__ . "/../login/lehrer.php";
            $getPasswordHashStatement= $conn->prepare("SELECT PasswordHash FROM LehrerLogin WHERE Kuerzel = :kuerzel");
            $getPasswordHashStatement->execute([':kuerzel' => $kuerzel]);
            $getPasswordHashResult = $getPasswordHashStatement->fetchAll(PDO::FETCH_ASSOC);
            if(count($getPasswordHashResult)==1) {
                if (password_verify($userPassword, $getPasswordHashResult[0]["PasswordHash"])) {
                    $status = "document.getElementById('status').textContent='Angemeldet!';";
                    setcookie("lehrerLogin", $kuerzel, [
                        'expires' => time() + 3600,
                        'path' => '/',
                        'domain' => '',
                        'httponly' => true,
                        'samesite' => 'Lax'
                    ]);
                    header('Location: /utils/lehrer.php');
                } else {
                    $status = "document.getElementById('status').textContent='Falsches Kürzel oder Passwort!';";
                }
            } else {
                $status = "document.getElementById('status').textContent='Falsches Kürzel oder Passwort!';"; 
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
        echo "document.getElementById('kuerzel').value = '" . $kuerzel . "';
          document.getElementById('password').value = '" . $userPassword . "';";

        echo "});</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AG-Verwaltung - Lehrer</title>
    <link rel="stylesheet" href="../styles/login.css">
  </head>
  <body>
    <div class="pageBody">
        <form action="../index.php"><button type="submit">Back</button></form>
        <br>
        <div class="center">
            <form method="post" action="/utils/lehrer.php">
                <h2>Lehrer Login</h2>
                <input id="kuerzel" name="kuerzel" type="text" placeholder="Kürzel"/>*
                <input id="password" name="password" type="password" placeholder="Passwort"/>*<br/>
                <span>* Pflichtfelder</span>
                <p id="status"></p>
                <button type="submit" name="submitLogin">Anmelden</button>
            </form>
        </div>
    </div>
  </body>
</html>
