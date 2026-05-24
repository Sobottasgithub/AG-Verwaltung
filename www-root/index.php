<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AG-Verwaltung</title>
    <link rel="stylesheet" href="styles/index.css">
  </head>
  <body>
    <ul>
      <li><a href="/utils/lehrer.php" class="active">Lehrer</a></li>
      <li><a href="/utils/schulleitung.php">Schulleitung</a></li>
      <li><a href="/utils/admin.php">Admin</a></li>
    </ul>
    <div class="center">
      <div class="pageBody">
        <h2 class="center">Arbeitsgemeinschaften</h2>
        <div class="center">
          <?php
              include "utils/agDisplay.php";
          ?>
        </div>
        <br/>
        <h2 class="center">Anmelden</h2>
        <span class="center">Melde dich für eine Ag an!</span>
        <br/>
        <div class="center">
          <?php
              include "utils/agRegisterForm.php";
          ?>
        </div>
      </div>
    </div>
  </body>
</html>
