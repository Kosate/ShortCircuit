<?php
    if( !isset($_GET['adminToken']) || $_GET['adminToken'] != 'eiei' ) {
        header("Location: ../index.php");
        die();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>Short Circuit</title>
        <link href="../font/thaisansneue.css" rel="stylesheet" type="text/css" />
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
                padding-top: 70px;
                font-family : 'Roboto', 'thaisans_neue_light', elvatica, Arial, sans-serif;
                background: #fdfdfd;
                color: #111;
            }
        </style>
    </head>
    <body>

       <nav class="navbar navbar-default navbar-fixed-top">
          <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#">Short Circuit</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav">
                <li class="active"><a href="#">Controller</a></li>
                <li><a href="#">Clicker</a></li>
              </ul>
            </div>
          </div>
        </nav>

        <div class="container">
            <h2 style="font-weight:300;">Controller System</h2>
            <hr>

            <div class="panel panel-default">
              <div class="panel-heading">Clustering</div>
              <div class="panel-body container-fluid" id="clusteringController">
              </div>
            </div>
            
            <div class="panel panel-default">
              <div class="panel-heading">Clustering</div>
              <div class="panel-body container-fluid" id="adminInjectController">
              </div>
            </div>

            <div class="panel panel-default">
              <div class="panel-heading">Default</div>
              <div class="panel-body container-fluid">
                  <div class="col-xs-6 col-sm-4">
                      <div class="ratio">
                      <label>
                          <input type="radio" name="controller" value="waiting">
                          &nbsp;<span style="font-weight:300;">Waiting</span>
                      </label>
                    </div>
                  </div>
                  <div class="col-xs-6 col-sm-4">
                      <div class="ratio">
                      <label>
                          <input type="radio" name="controller" value="default">
                          &nbsp;<span style="font-weight:300;">Default</span>
                      </label>
                    </div>
                  </div>
                  <div class="col-xs-6 col-sm-4">
                      <div class="ratio">
                      <label>
                          <input type="radio" name="controller" value="random">
                          &nbsp;<span style="font-weight:300;">Random</span>
                      </label>
                    </div>
                  </div>
              </div>
            </div>

            <div class="panel panel-default">
              <div class="panel-heading">Update Time</div>
              <div class="panel-body container-fluid">
                  <div class="col-xs-6 col-sm-3">
                      <div class="ratio">
                      <label>
                          <input type="radio" name="time" value="0.5">
                          &nbsp;<span style="font-weight:300;">0.5s</span>
                      </label>
                    </div>
                  </div>
                  <div class="col-xs-6 col-sm-3">
                      <div class="ratio">
                      <label>
                          <input type="radio" name="time" value="1">
                          &nbsp;<span style="font-weight:300;">1s</span>
                      </label>
                    </div>
                  </div>
                  <div class="col-xs-6 col-sm-3">
                      <div class="ratio">
                      <label>
                          <input type="radio" name="time" value="2">
                          &nbsp;<span style="font-weight:300;">2s</span>
                      </label>
                    </div>
                  </div>
                  <div class="col-xs-6 col-sm-3">
                      <div class="ratio">
                      <label>
                          <input type="radio" name="time" value="3">
                          &nbsp;<span style="font-weight:300;">3s</span>
                      </label>
                    </div>
                  </div>
                  <div class="col-xs-6 col-sm-3">
                      <div class="ratio">
                      <label>
                          <input type="radio" name="time" value="5">
                          &nbsp;<span style="font-weight:300;">5s</span>
                      </label>
                    </div>
                  </div>
                  <div class="col-xs-6 col-sm-3">
                      <div class="ratio">
                      <label>
                          <input type="radio" name="time" value="10">
                          &nbsp;<span style="font-weight:300;">10s</span>
                      </label>
                    </div>
                  </div>
              </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
        <script>

            var clustering;
            var adminInject;
            
            function attachEventToRadio() {
                $("input[type='radio']").click(function($this) {
                    $this = $($this.target);
                    
                    if( $this.attr("name") == "controller" ) {
                        var types = "";
                        var subType = "";
                        var val = $this.val();
                        
                        if( val.indexOf("_") == -1 ) {
                            types = val;
                        } else {
                            types = val.split("_")[0];
                            subType = val.split("_")[1];
                        }
                        $.get(
                            'update.php',
                            'adminToken=cf1a44&name=controller&state='+types+"&type="+subType
                        );
                    } else if( $this.attr("name") == "time" ) {
                        $.get(
                            'update.php',
                            'adminToken=cf1a44&name=time&val='+$this.val()
                        );
                    }
                    
                    console.log($this);
                });
            }

            $(function() {
                
                $.ajax({
                    url : "../flow.php",
                    data : "type=register_structure",
                    dataType : "json",
                    error: function() {
                        alert("There are some problem with internet connection");
                    },
                    success : function(res) {
                        clustering = res;

                        var enter = "";
                        for( var i = 0; i < res.length; i++ ) {

                            enter += '' +
                            '<div class="col-xs-6 col-sm-4">' +
                                '<div class="ratio">' +
                                    '<label><input type="radio" name="controller" value="clustering_'+res[i].name+'">' +
                                    '&nbsp;<span style="font-weight:100; font-size:1.5em;">' + res[i].nameTh + '</span></label>' +
                                '</div>';


                            enter += '' +
                            '</div>';

                        }

                        $("#clusteringController").html($("<div class='row'>").html(enter));
                        attachEventToRadio();
                        $("input[name='controller'][value='default']").click();
                    }
                });
                
                $.ajax({
                    url : "../data/admin_inject.json",
                    dataType : "json",
                    error: function() {
                        alert("Admin inject error");
                    },
                    success : function(res) {
                        adminInject = res;

                        var enter = "";
                        for( var i = 0; i < res.length; i++ ) {

                            enter += '' +
                            '<div class="col-xs-6 col-sm-4">' +
                                '<div class="ratio">' +
                                    '<label><input type="radio" name="controller" value="admin_'+res[i].name+'">' +
                                    '&nbsp;<span style="font-weight:300;">' + res[i].name + '</span></label>' +
                                '</div>';


                            enter += '' +
                            '</div>';

                        }

                        $("#adminInjectController").html($("<div class='row'>").html(enter));
                        
                        attachEventToRadio();
                        $("input[name='time'][value='1']").click();
                    }
                });
            });
        </script>
    </body>
</html>
