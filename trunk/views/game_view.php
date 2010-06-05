<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>game</title>
            <link rel="stylesheet" type="text/css" href="css/game.css">
            <script src="js/game.js" ></script>
    </head>
    <body onload="init();">
    <div id="main">

<div id='globalmenu'>
    <span> | </span>
    <a href='#' onclick="sendm('userprofile');" >Вы в игре как <?php echo $data['user']['login']; ?></a><span> | </span>
    <a href='#' onclick="sendm('logout');" >Выход ИЗ игры</a><span> | </span>
    <a href='./user.php' target="_blank" >Энциклопедия</a><span> | </span>
    <a href='#' onclick="sendm('news');" >Новости проекта</a><span> | </span>
    <a href='#' onclick="sendm('galaxy');" >Игра</a><span> | </span>
    <a href='#' onclick="sendm('battle');" >Бой</a><span> | </span>
    </div>
    <div id='menu'></div>
    <div id='controlbar'></div>
    <div id='monitor'></div>
    <div id='chat'>
        <div id='chat_monitor'></div>
        <div id='notifies'></div>
        <input id='chat_input' type='text' value=''/>
        <input id='chat_timestamp' type='hidden' value='0' />
        <input id='chat_button' type='button' value='send' onclick="sendw('chat','chat_input;chat_timestamp');" />
    </div>

    </div>
    <div id='debug'></div>
    </body>
</html>