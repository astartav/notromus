<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>game</title>
            <link rel="stylesheet" type="text/css" href="css/game.css">
            <script src="js/game.js" ></script>
    </head>
    <body>
    <div id="main">
        <div id='login'>
            Логин:
            <input id='login1' type='text' \><br>
            Пароль:
            <input id='password1' type='password' \><br>
            Повторить пароль:
            <input id='password2' type='password' \><br>
            E-mail:
            <input id='email' type='text' \><br><br>
            <input id='login_button' type='button' value='Зарегистрировать' onclick="sendw('registry','login1;password1;password2;email');" />
            <a href='#' onclick="sendi('login','');" >Войти</a>
            <a href='#' onclick="sendi('reminder','');" >Забыли пароль?</a>
        </div>";
    </div>
    <div id='debug'>
    </div>
    </body>
</html>