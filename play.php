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
                font-family : 'Roboto', 'thaisans_neue_light', elvatica, Arial, sans-serif;
                background: #fdfdfd;
                color: #111;
            }
            #show-text {
                position: fixed;
                text-align: center;
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
        </div>

        <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
        <script>
            
            var intervalHandle;
            var refreshTime = 10000;
            var randomToken = Math.floor(Math.random()*1000);
            var randomScreenInterval;
            var currentData = <?= $q['info'] ?>;
            
            var colorSet = colorSet = [
                "#e52416",
                "#ea1c62",
                "#600a6f",
                "#42109b",
                "#2196F3",
                "#068479",
                "#569123",
                "#ffc73a",
                "#ff8912",
                "#4d1c0a",
                "#f849c2"
            ];
            
            function short(obj) {
                clearInterval(randomScreenInterval);
                
                if( obj.state == 'random' ) {
                    randomScreenInterval = setInterval(
                        function() {
                            $('body').css('background', colorSet[Math.floor(Math.random()*colorSet.length)]);    
                        },
                        100  
                    );
                } else {
                    $('body').css('background','#fdfdfd');
                }
                
                $('#show-text').html('');
                
                if( obj.state == 'waiting' ) {
                    $('#show-text').html('กรุณาอย่าเพิ่งปิดหน้านี้<br>แต่สามารถ Sleep ไว้ชั่วคราวได้ หากคุณใช้โทรศัพท์');
                }
                
                if( obj.state == 'admin' ) {
                    if( obj.data.type == 'text' || obj.data.type == 'html' ) {
                        if( obj.data.type == 'text' )
                            $('#show-text').text( obj.data.text[ randomToken % obj.data.text.length ] );
                        else
                            $('#show-text').html( obj.data.text[ randomToken % obj.data.text.length ] );
                        
                        if( obj.data.fontColor != null )
                            $('#show-text').css('color', obj.data.fontColor[ randomToken % obj.data.fontColor.length ] );
                        if( obj.data.backgroundColor != null )
                            $('body').css('background', obj.data.backgroundColor[ randomToken % obj.data.backgroundColor.length ] );
                    }
                } else {
                    $('body').css('background','#fdfdfd');
                }
                
                if( obj.state == 'clustering' ) {
                    $('body').css('background', currentData['_color'][obj.data] );
                } else {
                    $('body').css('background', '#fdfdfd');
                }
            }
            
            function fetchData() {
                $.ajax({
                   url : "data/current.json?v="+Math.floor(Math.random()*1000000),
                   dataType : "json",
                   error : function() {
                       window.location.reload();
                   },
                   success : function(res) {
                       
                       short(res);
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
