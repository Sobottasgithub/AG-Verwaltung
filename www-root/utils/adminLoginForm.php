<?php
    if (isset($_POST['submitLogin'])) {
        $username = $_POST['username'];
        $userPassword = $_POST['password'];

        $status = "";
        if ($username != "" and $userPassword != "") {
            require __DIR__ . "/../login/admin.php";
            $getPasswordHashStatement = $conn->prepare("SELECT PasswordHash FROM Admin WHERE Name = :name");
            $getPasswordHashStatement->execute([':name' => $username]);
            $getPasswordHashResult = $getPasswordHashStatement->fetchAll(PDO::FETCH_ASSOC);
            if(count($getPasswordHashResult)==1) {
                if (password_verify($userPassword, $getPasswordHashResult[0]["PasswordHash"])) {
                    $status = "document.getElementById('status').textContent='Angemeldet!';";
                    setcookie("adminLogin", $username, [
                        'expires' => time() + 3600,
                        'path' => '/',
                        'domain' => '',
                        'httponly' => true,
                        'samesite' => 'Lax'
                    ]);
                    header('Location: /utils/admin.php');
                } else {
                    $status = "document.getElementById('status').textContent='Falscher Username oder Passwort!';";
                }
            } else {
                $status = "document.getElementById('status').textContent='Falscher Username oder Passwort!';"; 
            }
            $conn=null;
        }
        echo "<script type='text/javascript'>
            document.addEventListener('DOMContentLoaded', function() {";
        echo $status;
        if ($username == "") {
            echo "document.getElementById('username').style.backgroundColor = 'red';
                  document.getElementById('status').textContent='Bitte fülle alle Pflichtfelder aus!';";
        }
        if ($userPassword == "") {
            echo "document.getElementById('username').style.backgroundColor = 'red';
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
    <title>AG-Verwaltung - Admin</title>
    <link rel="stylesheet" href="../styles/login.css">
  </head>
  <body>
    <div class="pageBody">
        <form action="../index.php"><button type="submit">Back</button></form>
        <br>
        <div class="center">
            <form method="post" action="/utils/admin.php">
                <h2>Admin Login</h2>
                <input id="username" name="username" type="text" placeholder="Benutzername"/>*
                <input id="password" name="password" type="password" placeholder="Passwort"/>*<br/>
                <span>* Pflichtfelder</span>
                <p id="status"></p>
                <button type="submit" name="submitLogin">Anmelden</button>
            </form>
        </div>
    </div>
  </body>
</html>
