<?php
    $dsn = "mysql:host=localhost; dbname=drcc_collector; charset=utf8";
    $user = "drcc_admin";
    $password = "Aa24619622";
    $link = new PDO($dsn, $user, $password);
    date_default_timezone_set ("asia/taipei");
?>