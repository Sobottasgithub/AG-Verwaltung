<?php
    if (isset($_COOKIE["adminLogin"])) {
        require "adminPage.php";    
    } else {
        require "adminLoginForm.php";
    }
?>
