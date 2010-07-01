<div id='modemenu'>
    <span> | </span>
    <?php
        foreach($data['mode_menu'] as $name=>$action) {
            echo "<a href='#' onclick=\"".$action."\">".$name."</a><span> | </span>";
        }
    ?>
    <div id="tictac" style="float: right;">
        <span> | </span><span>00:00:00</span><span> | </span>
    </div>;
    <div style="float: right;">
        <span> | </span><a href='#' onclick=>Сделать ход!</a><span> | </span>
    </div>;
</div>


<div id='monitor'>
    <div id="map">
    </div>
</div>


<div id='info'>
    <p>информация о ходе битвы</p>
</div>