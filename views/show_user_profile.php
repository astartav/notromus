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
<h2>Персонаж - просмотр характеристик</h2>
        <form>
        фракция: <?php echo $data['person']['fraction_id']; ?><br>
        имя персонажа:<?php echo $data['person']['name']; ?><br>
        описание:<?php echo $data['person']['description']; ?><br>
        денежный счет:<?php echo $data['person']['account']; ?><br>
        игровые очки:<?php echo $data['person']['score']; ?><br>
        статус:<?php echo $data['person']['status']; ?><br>
        игровой опыт:<?php echo $data['person']['experience']; ?><br>
        ранг:<?php echo $data['person']['rang']; ?><br>
        </form>

    </div>
    <div id='debug'>
    </div>
    </body>
</html>