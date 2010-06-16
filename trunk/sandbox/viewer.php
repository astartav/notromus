<?php

function goto_view(&$data,$view_path) {
	if( @file_exists( $view_path)) {
		include_once $view_path;
	} else echo $view_path." fails";
}

function apply_views($view_dir) {
    $temp_dir="temp/";
    foreach (new DirectoryIterator($view_dir) as $file) {
        if(!$file->isDot() ) {
            $contents=file_get_contents($view_dir.$file);
            $contents=preg_replace('@<\?php@i','php1',$contents);
            $contents=preg_replace('@\?>@i','php2',$contents);
            file_put_contents("temp".$view_dir.$file,$contents);

           // $sx=simplexml_load_file("temp".$view_dir.$file);

            echo "<h3>".$file."</h3>";
          //  foreach($sx->div as $divs) {
          //      echo "<p>div:".$divs['id']."</p>";
          //  }
            
                $dom=new DOMDocument;
                $dom->loadHTMLFile("temp".$view_dir.$file);
                foreach($dom->getElementsByTagName('div') as $div) {
                    if($div->hasAttributes() ) {
                        echo "<p>".$div->getAttribute('id')."</p>";
                        echo "<p>".$div->nodeValue."</p>";
                    }
                    //$dom->get
                    //echo "<p>".$div->firstChild->nodeValue."</p>";
                    //foreach ($dom->getEl
                            //items as $item) {
                      //  echo "<p> item bla bla </p>";
                    //}

                            //."</p>";
                    //echo "<p>".$div->nodeValue."</p>";
                }
		}
	}
    echo "<h2>applying!</h2>";
    return true;
}

function preparedata(&$data,$viewname) {

    
    switch($viewname) {
        case 'show_user_profile':
            $data['person']['fraction_id']='1';
            $data['person']['name']='имя персонажа';
            $data['person']['description']='описание персонажа. бла бла бла бла бла...';
            $data['person']['account']='10000';
            $data['person']['score']='145000';
            $data['person']['status']='EFK';
            $data['person']['experience']='8438';
            $data['person']['rang']='новичок';
        case 'change_user_profile':
            $data['user']['user_login']='l-o-g-i-n';
            $data['user']['user_password']='p-a-s-s-w-o-r-d';
            $data['user']['user_email']='e-m-a-i-l';
            break;
        case 'game_view':
            $data['user']['login']='megauser';
            break;
        default:
            break;
    }
}


////////////////////////////////////////////////////////////

	$viewname="";
	$view_dir="views/";
    $data=array('person'=>array(), 'user'=>array() );

	if(isset($_GET['viewname'])) {
		$viewname=$_GET['viewname'];
	}

    if(isset($_GET['toapply']) && $_GET['toapply']=='yes') {
        $viewname="";
		if(apply_views($view_dir)) {
            $_GET['toapply']="";
        }
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

        #viewlist {
            padding: 10px;
            border: 1px black dotted;
        }

    </style>
    <script src="js/viewer.js" ></script>
    </head>
    <body>
<?php
		echo "<form name='viewform' method='get'><input type='hidden' name='viewname' value='' /><input type='hidden' name='toapply' value='".$_GET['toapply']."' />";
        echo "<div id='viewlist'><ul>";
		foreach (new DirectoryIterator($view_dir) as $file) {
			if(!$file->isDot() ) {
                $v=explode('.',$file);
				echo "<li><a href='".$file."' onclick=\"return send_to_form('viewname','".$v[0]."');\">".$file."</a>";
			}
		}
        echo "</ul></div>";
		echo "<br><input type='button' value='apply views' onclick=\"return send_to_form('toapply','yes');\">";
		echo "</form>";
		echo "</body></html>";
	} else {
        preparedata($data, $viewname);
        goto_view($data,$view_dir.$viewname.".php");
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
