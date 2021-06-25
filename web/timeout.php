<html>
    <script type="text/javascript">
        function error(){
            setTimeout("stop()", 1000);
        }
        function stop(){
            location = "login.php";
        }
    </script>

    <head>
        <link rel="stylesheet" href="../assets/css/main.css" />
    </head>

    <body onload="error();return true">
        <div style="text-align:center;margin:200px 0 0 0;">
            <h2>
                已登出或操作逾時
                一秒後回到登入畫面
            </h2>
        </div>
    </body>
</html>