<div id="monitor">
<h2>Персонаж - просмотр характеристик</h2>
<form>
    фракция: <?php echo $data['person'][0]['person_fraction_id']; ?><br />
    имя персонажа:<?php echo $data['person'][0]['person_name']; ?><br />
    описание:<?php echo $data['person'][0]['person_description']; ?><br />
    денежный счет:<?php echo $data['person'][0]['person_account']; ?><br />
    игровые очки:<?php echo $data['person'][0]['person_score']; ?><br />
    статус:<?php echo $data['person'][0]['person_status']; ?><br />
    игровой опыт:<?php echo $data['person'][0]['person_experience']; ?><br />
    ранг:<?php echo $data['person'][0]['person_rang']; ?><br />
</form>
</div>
