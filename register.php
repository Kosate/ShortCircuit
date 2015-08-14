<?php
    session_start();

    require("connect.php");

    function redirectToHome() {
        header("Location: index.php");
        die();
    }

    if( !isset($_GET["id"]) ) {
        if( isset($_SESSION["curHash"]) && strlen($_SESSION["curHash"]) != 0 ) {
            header("Location: register.php?id=".$_SESSION["curHash"]);
            die();
        }
        redirectToHome();
    }

    $hash = $_GET["id"];
    $curData = "";

    $q = $connect->query("select * from shortcircuit_each_info where hash = '$hash'");
    if( $q->num_rows == 0 ) {
        redirectToHome();
    } else {
        $_SESSION["curHash"] = $hash;
        $q = $q->fetch_assoc();
        $curData = $q["info"];
        if( $curData != "{}" ) {
            //header("Location: play.php?id=".$_SESSION["curHash"]);
            //die();
        }
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
            #form {
                font-family : 'thaisans_neue_light';
                font-size: 1.5em;
            }
            #form .form-control {
                font-size: 1em;
                padding-top: 2px;
            }
            .head-title {
                margin-top: 40px;
                text-align: center;
                font-size: 1.75em;
                font-weight: 300;
            }
            .enter-btn {
                font-size: 1.25em;
                display: block;
                color: #fff;
                background: #6d0707;
                border-radius: 5px;
                padding: 12px 24px;
                margin: auto;
                margin-top: 25px;
                text-align: center;
                font-weight: 100;
                cursor: pointer;
            }
        </style>
	</head>
	<body>
        <div class="container">
            <div class="head-title">
                Register
            </div>
            <div id="form" class="row">

            </div>
            <div class="enter-btn">
                Short !!!
            </div>
        </div>
        <div>
		</div>
        
        <div class="modal fade" id="alerter">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Alerter</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        
        <div class="modal fade" id="confirm">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirm</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Confirm</button>
                </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
        <script>

            var config = {};
            var curData = <?= $curData ?>;
            
            function show_alerter( txt, callback ) {
                if( typeof callback != "undefined" && callback != null ) {
                    $("#alerter button").click( callback );
                }
                $("#alerter .modal-body").html(txt);
                $("#alerter").modal('show');
            }
            
            function show_confirm( txt, callback ) {
                $("#confirm .modal-footer .btn-primary").click( callback );
                $("#confirm .modal-body").html(txt);
                $("#confirm").modal('show');
            }

            $(function() {
                $.ajax({
                    url : "flow.php",
                    data : "type=register_structure",
                    dataType : "json",
                    error: function() {
                        show_alerter("อินเตอร์เน็ตของคุณมีปัญหา", function() {
                            window.location.reload();
                        });
                    },
                    success : function(res) {
                        config = res;

                        var enter = "";
                        for( var i = 0; i < res.length; i++ ) {

                            enter += '' +
                            '<div class="col-sm-' + res[i].width + '">' +
                                '<div class="form-group">' +
                                    '<label form="form_' + res[i].name + '">' +
                                    res[i].nameTh +
                                    '</label>';

                            if( res[i].type === "text" ) {
                                enter += '<input type="text" class="form-control" id="form_' + res[i].name + '" />';
                            } else if( res[i].type === "choice" ) {
                                enter += '<select class="form-control" id="form_' +  res[i].name + '">';
                                for( var j = 0; j < res[i].choice.length; j++ ) {
                                    var value = res[i].choice[j].value;
                                    if( typeof res[i].choice[j].valueTh != "undefined" ) {
                                        value = res[i].choice[j].valueTh;
                                    }
                                    enter += '<option name="'+res[i].choice[j].value+'">'+value+'</option>';
                                }
                                enter += '</select>'
                            }

                            enter += '' +
                                '</div>' +
                            '</div>';

                        }

                        $("#form").append(enter);

                        for( var i = 0; i < res.length; i++ ) {
                            if( typeof curData[res[i].name] != "undefined" ) {
                                if( res[i].type == 'choice' ) {
                                    $('#form_' + res[i].name)
                                        .find('option[name="'+curData[res[i].name]+'"]')
                                        .attr('selected','selected');   
                                } else {
                                    $("#form_" + res[i].name).val( curData[res[i].name] );
                                }
                            }
                        }
                    }
                });

                $(".enter-btn").click(function() {
                    var isEmpty = false;
                    $("#form input[type='text']").each(function(id, $elm) {
                        if( $.trim($($elm).val()).length == 0 ) {
                            console.log($elm);
                            isEmpty = true;
                        }
                    });
                    if( isEmpty ) {
                        show_alerter("กรุณากรอกข้อมูลให้ครบถ้วน");
                    } else {
                        
                        show_confirm(
                          'คุณต้องการจะยืนยันข้อมูลนี้ใช่หรือไม่',
                          function() {
                            
                            $('#confirm').modal('hide');
                              
                            var rawData = {};
                            for( var i = 0; i < config.length; i++ ) {
                                if( config[i].type == 'choice' ) {
                                    var v = $("#form_" + config[i].name).val();
                                    console.log(v);
                                    rawData[config[i].name] = $("#form_" + config[i].name).find('option:contains("'+v+'")').attr('name');
                                } else {
                                    rawData[config[i].name] = $("#form_" + config[i].name).val();
                                }
                            }
    
    
                            $.ajax({
                                url : "flow.php",
                                data : "type=update_each_info&data="+encodeURIComponent(JSON.stringify(rawData)),
                                type : "get",
                                success : function(res) {
                                    console.log("ok");
                                    if( res == "ok" ) {
                                        window.location.href = "play.php?id=<?= $hash ?>";
                                    } else {
                                        console.log(res);
                                        show_alerter("บันทึกข้อมูลไม่สมบูรณ์");
                                    }
                                },
                                error : function() {
                                    show_alerter("อินเตอร์เน็ตมีปัญหา");
                                }
                            });
                          }  
                        );
                    }
                });
            });
        </script>
	</body>
</html>
