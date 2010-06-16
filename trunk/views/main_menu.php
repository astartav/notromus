<div id="left_menu"></div>
<div id='mainmenu'>
    <a href='./user.php' target="_blank" >Энциклопедия</a>&nbsp;&nbsp;&nbsp;
    <a href='#' onclick="sendm('news');" >Новости проекта</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href='#' onclick="sendm('galaxy');" >Игра</a><span>&nbsp;&nbsp;&nbsp;
    <a href='#' onclick="sendm('battle');" >Бой</a><span> &nbsp;&nbsp;&nbsp;
    <div id="user_profile">Вы в игре как <a href='#' onclick="sendm('userprofile');" ><?php echo $_data['user'][0]['user_login']; ?></a>
    (<span id="exit"><a href='#' onclick="sendm('logout');" >выйти</a></span>)</div>
</div>
<div id="right_menu"></div>