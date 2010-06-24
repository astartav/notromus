<?php  session_start();
/*$memcache = new Memcache;
$memcache->connect('localhost', 11211) or die ("Could not connect");*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>game help</title>
            <link rel="stylesheet" type="text/css" href="css/help.css">
            <script src="js/help.js" ></script>
    </head>
    <body>
        <?php
            $helpname="";

            $patterns="";
            $replacements="";

            $namelist=array(
                "введение"=>"main",
                "игровой мир"=>"gameworld",
                "галактика"=>"galaxy",
                "система"=>"system",
                "сектор"=>"sector",
                "локация"=>"location",
                "пользователь"=>"user",
                "персонаж"=>"person",
                "фракция"=>"fraction",
                "биониды"=>"bionyds",
                "культ машин"=>"mechculture",
                "инквизиторы"=>"inqizitors",
                "навигация"=>"navigation",
                "корабль"=>"ship",
                "битва"=>"battle",
            );

            foreach($namelist as $key=>$value) {
                $replacements[] = "<a href=./help.php?name=".$value.">".$key."</a>";
                $patterns[] = "/".$key."/";
            }

            if( isset($_GET['name']) && isset($_GET['name'])!="") {
                $helpname=$_GET['name'];
            } else {
                $helpname="main";
            }

            ?>
            <div id='menu'>
                <?php echo join(" | ", $replacements); ?>
            </div>
            <div id="main">
            <?php

            /*$value = $memcache->get($helpname);
            if($value) {
                echo $value;
            } else {*/
                if( @file_exists( "help/".$helpname.".php")) {
                $fh=fopen("help/".$helpname.".php",'r');// or die($php_errormsg);
                if( $fh ) {
                    $all_s="";
                    $s="";
                    while(! feof( $fh )) {
                        $s = fgets( $fh );
                        if($s) {
                            $all_s .= preg_replace($patterns, $replacements, $s);
                        }
                    }
                fclose( $fh );// or die($php_errormsg);
                //$memcache->set($helpname,$all_s,false, 600) or die ("Failed to save data at the server");
                echo $all_s;
                //}
                } else echo "<h3>not found!</h3>";
            }
            ?>
            </div>
    </body>
</html>