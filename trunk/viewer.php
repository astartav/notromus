<?php

function goto_view($view_path) {
	if( @file_exists( $view_path)) {
		include_once $view_path;
	} else echo $view_path." fails";
}


function preparedata(&$data,$viewname) {
}

	$viewname="";
	$view_dir="views/";

	if(isset($_GET['viewname'])) {
		$viewname=$_GET['viewname'];
	}

	if($viewname=="") {?>

	<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>config</title>
    <style type="text/css">

        a {
        font-family: Arial, Helvetica, sans-serif;
        border-spacing: 4px;
        }

    </style>
    <script src="js/viewer.js" ></script>
    </head>
    <body>
<?php
		echo "<form name='viewform' method='get'><input type='hidden' name='viewname' value='' />";
		foreach (new DirectoryIterator($view_dir) as $file) {
			if(!$file->isDot() ) {
                $v=explode('.',$file);
				echo "<li><a href='".$file."' onclick=\"return send_to_form('viewname','".$v[0]."');\">".$file."</a>";
			}
		}
		
		echo "</form>";
		echo "</body></html>";
	} else {
		
		$data=array();
		preparedata($data, $viewname);
		goto_view($view_dir.$viewname.".php");
	}
?>


<?php
/*
    $view_dir="./";

    $data=array(
        'mainmenu'=>array(
            "menuitem1"=>"onclick=\"function1();\" ",
            "menuitem2"=>"onclick=\"function2();\" ",
            "menuitem3"=>"onclick=\"function3();\" ",
            "menuitem4"=>"onclick=\"function4();\" ",
            "menuitem5"=>"onclick=\"function5();\" ",
            "menuitem6"=>"onclick=\"function6();\" "),

        'modemenu'=>array(
            "menuitem1"=>"onclick=\"function1();\" ",
            "menuitem2"=>"onclick=\"function2();\" ",
            "menuitem3"=>"onclick=\"function3();\" ",
            "menuitem4"=>"onclick=\"function4();\" ",
            "menuitem5"=>"onclick=\"function5();\" ",
            "menuitem6"=>"onclick=\"function6();\" "),

        'userprofile'=>array(
            "имя"=>"аш99",
            "fraction"=>"фракция 99",
            "параметр3"=>"значение3",
            "параметр4"=>"значение4",
            "параметр5"=>"значение5" ),

        'debug'=>array(
            "что то где то случилось",
            "нафига козе баян",
            "где взять денег?",
            "и т.д. ... ... "
        )

    );


*/
?>
