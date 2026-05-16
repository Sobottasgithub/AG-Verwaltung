<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AG-Verwaltung</title>
    <link rel="stylesheet" href="styles/style.css">
  </head>
  <body>
    <a href="/utils/lehrer.php">Lehrer</a>
    <a href="/utils/schulleitung.php">Schulleitung</a>
    <a href="/utils/admin.php">Admin</a>

    <?php
        include "utils/agDisplay.php";
        include "utils/agRegisterForm.php";
    ?>
  </body>
</html>
