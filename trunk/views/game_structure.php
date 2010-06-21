<?php //include_once("main_menu.php"); ?>

<div id="left_menu"></div>
<div id='mainmenu'>
    <a href='./user.php' target="_blank" >Энциклопедия</a>
    <a href='#' onclick="sendm('news');" >Новости проекта</a>
    <a href='#' onclick="sendm('galaxy');" >Игра</a>
    <a href='#' onclick="sendm('battle');" >Бой</a>
    <div id="user_profile">
        Вы в игре как <a href='#' onclick="sendm('userprofile');" ><?php echo $data['user'][0]['user_login']; ?></a>(<span id="exit"><a href='#' onclick="sendm('logout');" >выйти</a></span>)
    </div>
</div>
<div id="right_menu"></div>

<div id='modearea'></div>

<div id='messages'></div>
<div id='chat'>
    <div id='chat_monitor'></div>
    <input id='chat_input' type='text' value=''/>
    <input id='chat_timestamp' type='hidden' value='0' />
    <input id='chat_button' type='button' value='send' onclick="sendw('chat','chat_input;chat_timestamp');" />
</div>

<?php //include_once("chat.php"); ?>


