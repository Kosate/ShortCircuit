<?php
    session_start();


    require("connect.php");

    function redirectToHome() {
        header("Location: index.php");
        die();
    }

    if( !isset($_GET["id"]) ) {
        if( isset($_SESSION["curHash"]) && strlen($_SESSION["curHash"]) != 0 ) {
            header("Location: play.php?id=".$_SESSION["curHash"]);
            die();
        }
        redirectToHome();
    }

    $hash = $_GET["id"];

    $q = $connect->query("select * from shortcircuit_each_info where hash = '$hash'");
    if( $q->num_rows == 0 ) {
        redirectToHome();
    }
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>Short Circuit</title>
        <link href="font/thaisansneue.css" rel="stylesheet" type="text/css" />
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,100' rel='stylesheet' type='text/css' />
        <link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css' rel='stylesheet' type='text/css' />
        <style>
            html, body {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
            }
            body {
                font-family : 'Roboto', elvatica, Arial, sans-serif;
                background: #fdfdfd;
                color: #111;
            }`
        </style>
	</head>
	<body>
       5555

        <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
        <script>

        </script>
	</body>
</html>
