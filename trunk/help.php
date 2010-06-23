<?php  session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>game help</title>
            <link rel="stylesheet" type="text/css" href="css/help.css">
            <script src="js/help.js" ></script>
    </head>
    <body>
        <div id="main">

        <?php
            $helpname="";

            $patterns="";
            $replacements="";

            $namelist=array(
                "битва"=>"battle",
                "навигация"=>"navigation",
                "корабль"=>"ship",
                "введение"=>"main"
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
            $fh=fopen("help/".$helpname.".php",'r') or die($php_errormsg);
            $s="";
            while(! feof($fh)) {
                $s = fgets($fh);
                if($s) {
                  echo preg_replace($patterns, $replacements, $s);
                }
            }
            fclose($fh) or die($php_errormsg);
        ?>

        </div>
    </body>
</html>