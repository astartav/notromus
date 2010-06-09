<div id="monitor">
<h2>Новости проекта</h2>
<ul class="news">
<?php
    foreach($data['pnews'] as $line) {
        echo "<li>".$line;
    }
?>
</ul>
</div>

