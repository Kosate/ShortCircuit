<!DOCTYPE html>
<html>
	<body>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>Short Circuit</title>
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,100' rel='stylesheet' type='text/css'>
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
            .container {
                position: fixed;
                text-align: center;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }
            .head-title {
                text-align: center;
                font-size: 3em;
                font-weight: 100;
            }
            .register-btn {
                margin-top:40px;
                font-size: 20px;
                padding: 20px 0;
                display: block;
                background: #600b0b;
                color: #fff;
                border-radius: 5px;
                text-align: center;
                cursor: pointer;
                font-weight: 300;
            }
            .footer {
                position: fixed;
                left: 10px;
                bottom: 10px;
                font-size: 0.75em;
                text-align: center;
            }
        </style>
	</body>
	<head>
		<title>Short Circuit</title>
		<meta charset="utf-8" />
	</head>
	<body>
        <div class="container">
            <div class="head-title">
                Short Circuit
            </div>
            <div class="register-btn">
                Register
            </div>
        </div>
        <div>
            
        </div>
        <div>
            <div class="footer">
                Create by Kosate Limpongsa
            </div>
		</div>
	</body>

	<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script>
        $(function() {
            $(".register-btn").click(function() {
                $.ajax({
                    url : "flow.php",
                    type : "get",
                    data : "type=next_id",
                    dataType : "json",
                    error : function() {
                        alert("Can't connect to database");
                    },
                    success : function(res) {
                        window.location.href = "register.php?id="+res.id;
                    }
                });
            });
        });
    </script>
</html>
