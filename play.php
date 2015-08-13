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
    } else {
        $q = $q->fetch_assoc();
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
            }
            #show-text {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                font-size: 4em;
                font-weight: 300;
            }
        </style>
	</head>
	<body>
        <div id="show-text">
            5555
        </div>

        <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
        <script>
            
            var intervalHandle;
            var refreshTime = 10000;
            var randomToken = Math.floor(Math.random()*1000);
            var currentData = <?= $q['info'] ?>;
            
            function fetchData() {
                $.ajax({
                   url : "data/current.json?v="+Math.floor(Math.random()*1000000),
                   dataType : "json",
                   error : function() {
                       window.location.reload();
                   },
                   success : function(res) {
                       
                       $("#show-text").text(res.state);
                       refreshTime = res.updateTime * 1000;
                       
                       clearTimeout(intervalHandle);
                       setTimeout(
                            fetchData,
                            refreshTime
                       );
                   } 
                });
            }
            
            $(function() {
                fetchData();
                intervalHandle = setTimeout(
                    fetchData,
                    refreshTime
                );
            });
        </script>
	</body>
</html>
