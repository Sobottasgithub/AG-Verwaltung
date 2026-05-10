<?php
    $serverName = "localhost";
    $dbName = "AG_Verwaltung";

    try {
        $conn = new PDO("mysql:host=$serverName;dbname=$dbName", $userName, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        echo "Conncted successfully";

        $conn = null;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
?>
