<span> | </span>
    <?php
        foreach($data['mode_menu'] as $name=>$action) {
            echo "<a href='#' onclick=\"".$action."\">".$name."</a><span> | </span>";
        }
    ?>
