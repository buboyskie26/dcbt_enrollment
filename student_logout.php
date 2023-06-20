<?php
    session_start();
    session_destroy();
    header("Location: /dcbt/dashboard.php");


?>