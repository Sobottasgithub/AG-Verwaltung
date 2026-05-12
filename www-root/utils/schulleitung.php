<?php
    if (isset($_COOKIE["schulleitungLogin"])) {
        require "schulleitungPage.php";    
    } else {
        require "schulleitungLoginForm.php";
    }
?>
