<?php
    if (isset($_POST['submitLogin'])) {
        $username = $_POST['username'];
        $userPassword = $_POST['password'];

        $status = "";
        if ($username != "" and $userPassword != "") {
            require __DIR__ . "/../login/admin.php";
            $query="SELECT PasswordHash FROM Admin WHERE Name='" . $username . "'";
            $result = $conn->query($query);
            if($result->rowCount()==1) {
                $row = $result->fetch(PDO::FETCH_ASSOC);
                if (password_verify($userPassword, $row["PasswordHash"])) {
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
                  document.getElementById('status').textContent='Fill out both input boxes!';";
        }
        if ($userPassword == "") {
            echo "document.getElementById('username').style.backgroundColor = 'red';
                  document.getElementById('status').textContent='Fill out both input boxes!';";
        }

        echo "});</script>";
    }
?>
<a href="../index.php">Back</a>
<form method="post" action="/utils/admin.php">
    <input id="username" name="username" type="text" placeholder="Benutzername"/>
    <input id="password" name="password" type="password" placeholder="Passwort"/>
    <p id="status"></p>
    <button type="submit" name="submitLogin">Anmelden</button>
</form>
