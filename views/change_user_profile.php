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
            <h2>Профиль пользователя</h2>
            Имя пользователя:
            <input id='login1' type='text' value='<?php echo $data['user']['user_login']; ?>' /><br>
            Пароль:
            <input id='password1' type='password' value='<?php echo $data['user']['user_password']; ?>' /><br>
            E-mail:
            <input id='email' type='text' value='<?php echo $data['user']['user_email']; ?>' /><br>
            Avatar:
            <img src='images/users/<?php echo $data['user']['user_avatar']; ?>' /><br>
            <a href='#' onclick="sendw('userprofile','login1;password1;password2');" >Обновить данные</a>
         </div>
    </div>
    <div id='debug'>
    </div>
    </body>
</html>