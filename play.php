<?php
    session_start();


    require("connect.php");
    require_once('data/color.php');

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
                font-size: 6rem;
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
            var refreshTime = 1000;
            var randomToken = Math.floor(Math.random()*1000);
            var randomScreenInterval;
            var currentData = <?= $q['info'] ?>;
            
            var colorSet = <?= json_encode($colorSet) ?>;
            
            function short(obj) {
                clearInterval(randomScreenInterval);
                
                var isBackgroundSetter = false;
                var isTextSettter = false;
                
                if( obj.state == 'random' ) {
                    isBackgroundSetter = true;
                    randomScreenInterval = setInterval(
                        function() {
                            $('body').css('background', colorSet[Math.floor(Math.random()*colorSet.length)]);    
                        },
                        100  
                    );
                }
                
                if( obj.state == 'waiting' ) {
                    isTextSettter = true;
                    var url = window.location.href;
                    var imgURL = 'https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl='+url;
                    $('#show-text').html(
                        '<img src="'+imgURL+'" width="300" height="300" />' +
                        '<div style="font-size: 1rem;">คุณสามารถเชื่อมต่อกับมือถือได้ผ่าน QR Code นี้</div>' +
                        '<div style="font-size: 2rem; margin-top: 15px;">กรุณาอย่าเพิ่งปิดหน้านี้<br>แต่สามารถ Sleep ไว้ชั่วคราวได้ หากคุณใช้โทรศัพท์</div>'
                    );
                }
                
                if( obj.state == 'admin' ) {
                    if( obj.data.type == 'text' || obj.data.type == 'html' ) {
                        
                        isTextSettter = true;
                        
                        if( obj.data.type == 'text' )
                            $('#show-text').text( obj.data.text[ randomToken % obj.data.text.length ] );
                        else
                            $('#show-text').html( obj.data.text[ randomToken % obj.data.text.length ] );
                        
                        if( obj.data.fontColor != null )
                            $('#show-text').css('color', obj.data.fontColor[ randomToken % obj.data.fontColor.length ] );
                        
                        if( obj.data.backgroundColor != null ) {
                            isBackgroundSetter = true;
                            $('body').css('background', obj.data.backgroundColor[ randomToken % obj.data.backgroundColor.length ] );
                        }
                    }
                }
                
                if( obj.state == 'clustering' ) {
                    isBackgroundSetter = true;
                    $('body').css('background', currentData['_color'][obj.data] );
                }
                
                if( !isBackgroundSetter ) {
                    $('body').css('background', '#fdfdfd');
                    $('#show-text').css('color', '#111');
                }
                if( !isTextSettter ) {
                    $('#show-text').html('');
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
