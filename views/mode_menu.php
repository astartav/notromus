<div id='modemenu'>
    <span> | </span>
    <?php
        $menus=array("стоять", "сидеть", "лежать", "к ноге");
        foreach($menus as  $menu) {
            echo "<a href='#'>".$menu."</a><span> | </span>";
        }
    ?>
</div>