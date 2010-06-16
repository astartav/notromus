<div id="monitor">
    <h2>Профиль пользователя</h2>
    Имя пользователя:
    <input id='login1' type='text' value="<?php echo $data['user']['user_login']; ?>" /><br />
    Пароль:
    <input id='password1' type='password' value="<?php echo $data['user']['user_password']; ?>" /><br />
    E-mail:
    <input id='email' type='text' value="<?php echo $data['user']['user_email']; ?>" /><br />
    Avatar:
    <img src="images/users/<?php echo $data['user']['user_avatar']; ?>" /><br />
    <a href='#' onclick="sendw('userprofile','login1;password1;password2');" >Обновить данные</a>
</div>