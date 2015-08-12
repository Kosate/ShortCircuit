<?php

    $host = "localhost";
    $user = "";
    $password = "";
    $dbName = "";

    $connect = mysqli_connect(
        $host,
        $user,
        $password,
        $dbName
    ) or die("MySQL error ".mysqli_error($connect));
?>
