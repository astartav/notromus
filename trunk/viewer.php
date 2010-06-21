<?php

// RULES!!
//
//
//
// 1. TEMPLATE VIEW - the set of VIEWs are included into div blocks.
//
// 2. VIEW - HTML code with PHP parametarisation defining UI block
//
// 3. VIEWER - php script to select and show the template view with a test data

function goto_view(&$data,$view_path) {
	if( @file_exists( $view_path)) {
		include_once $view_path;
	} else echo $view_path." fails";
}

function preparedata(&$data,$viewname) {

    
    switch($viewname) {
        case 'galaxy_navigation_page':
            $data['mode_menu']=array("назад"=>"alert('нельзя... позади москва!');");
            break;
        case 'show_user_profile':
            $data['person']['fraction_id']='1';
            $data['person']['name']='имя персонажа';
            $data['person']['description']='описание персонажа. бла бла бла бла бла...';
            $data['person']['account']='10000';
            $data['person']['score']='145000';
            $data['person']['status']='EFK';
            $data['person']['experience']='8438';
            $data['person']['rang']='новичок';
            break;
        case 'change_user_profile':
        case 'create_user_profile':
            $data['user']['user_login']='l-o-g-i-n';
            $data['user']['user_password']='p-a-s-s-w-o-r-d';
            $data['user']['user_email']='e-m-a-i-l';
            break;
        case 'main_menu':
            $data['user'][0]['user_login']='megauser';
            break;
        case 'project_news':
            $data['pnews'][]="Издательство Warner Bros. объявило о том, что перезапустит многопользовательскую ролевую игру The Lord of the Rings Online на основе бизнес-модели free2play. Об этом сообщает Eurogamer. Открытое бета-тестирование бесплатной The Lord of the Rings Online начнется 16 июня 2010 года, а перезапуск проекта запланирован на осень. ";
            $data['pnews'][]="В ночь на 8 июня грузовой корабль \"Прогресс М-05М\" в два этапа завершил коррекцию орбиты МКС. Об этом сообщает \"Интерфакс\" со ссылкой на представителя Центра управления полетами. ";
            $data['pnews'][]="Французские психиатры поставили диагноз Дарту Вейдеру - персонажу киноэпопеи \"Звездные войны\", одному из самых известных и популярных злодеев в истории кинематографа. Как сообщает Fox News, оказалось, что руководитель армии Галактической Империи страдает пограничным расстройством личности - состоянием, характеризующимся чрезмерной импульсивностью и высоким уровнем тревожности. ";
            break;
        case 'map':
            $data['map']['name']='лесов полей и рек';
            $data['map']['coord1']='868678686';
            break;
        default:
            break;
    }
}


////////////////////////////////////////////////////////////

	$viewname="";

	$view_dir="views/";
    $view_template_dir="view_templates/";

    $data=array('person'=>array(), 'user'=>array(), 'pnews'=>array(), 'map'=>array());


    $view_templates=array(
        'galaxy_navigation_page'=>array('galaxy_navigation_page'),
        'guest_login'=>array('guest_login'),
        'game_structure'=>array('game_structure'),
        'guest_remind'=>array('guest_remind'),
        'guest_registry'=>array('guest_registry'),
		'user_mode'=>array('user_mode'),
        'create_profile'=>array('main_menu','mode_menu','create_user_profile','chat'),
        'change_profile'=>array('main_menu','mode_menu','change_user_profile','chat'),
        'show_profile'=>array('main_menu','mode_menu','show_user_profile','chat'),
        'project_news'=>array('main_menu','mode_menu','project_news','chat'),
        'map'=>array('main_menu','mode_menu','map','chat')
    );

	if(isset($_GET['viewname'])) {
		$viewname=$_GET['viewname'];
	}

	if($viewname=="") {
    ?>

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
		echo "<form name='viewform' method='get'><input type='hidden' name='viewname' value='' />";
        echo "<div id='viewlist'><ul>";
        foreach ($view_templates as $k=>$v) {
            echo "<li><a href='#' onclick=\"return send_to_form('viewname','".$k."');\">".$k."</a>";
        }

        echo "</ul></div>";
		echo "</form>";
		echo "</body></html>";
	} else {
        
        goto_view($data,$view_dir."html_header".".php");
        if(isset($view_templates[$viewname])) {
            foreach ($view_templates[$viewname] as $v) {
                preparedata($data, $v);
                goto_view($data,$view_dir.$v.".php");
                //echo "<li><a href='#' onclick=\"return send_to_form('viewname','".$v."');\">".$v."</a>";
            }
        }
        goto_view($data,$view_dir."html_footer".".php");
	}
?>
