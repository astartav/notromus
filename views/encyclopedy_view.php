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

<h2>Энциклопедия</h2>
        <form>
        <input name='topic' id='topic' type='hidden' value='' \>
        <input name='chapter' id='chapter' type='hidden' value='' \>
        <input name='page' id='page' type='hidden' value='' \>
        <select id='topics' size='10' onchange="this.form.topic.value=options[this.selectedIndex].value;sendw('navigation','topic;chapter;page');" >
            <option value='fractions'>Фракции</option>
            <option value='navigation'>Навигация</option>
            <option value='ship'>Корабли</option>
            <option value='locations'>Локации</option>
        </select>
        <br>
        <input name='newtopic' id='newtopic' type='text'\>
        <input type='button' value='new topic' onclick="sendw('newtopic','topic;chapter;page');" \>
        </form>

    </div>
    <div id='debug'>
    </div>
    </body>
</html>