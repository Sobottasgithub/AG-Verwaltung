<?php
    if (isset($_COOKIE["lehrerLogin"])) {
        require "lehrerPage.php";    
    } else {
        require "lehrerLoginForm.php";
    }
?>
