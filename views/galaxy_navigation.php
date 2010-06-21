<div id='modemenu'>
    <span> | </span>
    <?php
        foreach($data['mode_menu'] as $name=>$action) {
            echo "<a href='#' onclick=\"".$action."\">".$name."</a><span> | </span>";
        }
    ?>
</div>


<div id='monitor'>
    <div id="map"></div>
</div>


<div id='info'>
    <p>наиподробнейшая инфа о том сколько в данной галактике систем. сколько всего того. сколько того всего. и т.д.</p>
</div>
