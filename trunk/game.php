<?php
session_start();

function code_id($_in) {
    return "obj_".$_in;
}

function decode_id($_in) {
    return substr_replace($_in,"",0,4);
}

function send_mail(&$email_body) {

    require_once "Mail.php";
    gen_debug("email sending",4);
    $headers = array (  'From'=>"agameadmin@rambler.ru",
                        'To'=>$email_body['to'],
                        'Subject'=>'=?UTF-8?B?'.base64_encode($email_body['subj']).'?=',
                        'Content-Type'=>'text/plain; charset=utf-8'
    );

    $smtp = Mail::factory('smtp',
        array ( 'host' => "mail.rambler.ru",
                'auth' => true,
                'username' => "agameadmin",
                'password' => "a2g0a1m0e") );
    $mail = $smtp->send($email_body['to'], $headers, $email_body['mess']);

    if (PEAR::isError($mail)) {
        gen_debug("email sending fails: ".$mail->getMessage(),2);
        return false;
    }
    gen_debug("email successfully sent!",4);
    return true;
}


function navigation_menu() {
    return "
        <a href='#' onclick=\"sendm('galaxy');\" >Галактика</a><span> | </span>
        <a href='#' onclick=\"sendm('system');\" >Система</a><span> | </span>
        <a href='#' onclick=\"sendm('sector');\" >Сектор</a><span> | </span>
        <a href='#' onclick=\"sendm('location');\" >Локация</a><span> | </span>
        ";
}

function game_new_person() {

  /*  $out="
        <h2>Персонаж - выбор персонажа</h2>
        <form>
        фракция:<br>
        <input name='fraction' id='fraction' type='hidden' value='' \>
        <select size='3' onchange=\"this.form.fraction.value=this.selectedIndex;\" >
            <option value='0'>Биониды</option>
            <option value='1'>Культ машин</option>
            <option value='2'>Инквизиторы</option>
        </select><br>
        имя персонажа:<br>
        <input name='name' id='name' type='text'\><br>
        описание:<br>
        <input name='description' id='description' type='text'\><br>
        <br>
        <input type='button' value='создать персонаж' onclick=\"sendw('addnew','name;fraction;description');\" \>
        </form>
        ";*/
    return $out;
}

function game_person(&$data) {
    $fraction=array('Биониды','Культ машин','Инквизиторы');
    $status=array('offline','online');
 /*   $out="
        <h2>Персонаж - просмотр характеристик</h2>
        <form>
        фракция: ".$fraction[$data['person_fraction_id']]."<br>
        имя персонажа:".$data['person_name']."<br>
        описание:".$data['person_description']."<br>
        денежный счет:".$data['person_account']."<br>
        игровые очки:".$data['person_score']."<br>
        статус:".$status[$data['person_status']]."<br>
        игровой опыт:".$data['person_experience']."<br>
        ранг:".$data['person_rang']."<br>
        </form>
        ";*/
    return $out;
}

function game_maintenance_depot(&$data) {
    $out="
        <h2>Ремонтная база</h2>
        <h5>кстати тут не работает ничего</h5>
        <form>
        <input name='service_type' id='service_type' type='hidden' value='' \>
        <select size='10' onchange=\"this.form.service_type.value=this.selectedIndex;\" >
            <option value='0'>Покраска мультиустойчивым покрытием</option>
            <option value='1'>Рихтовка метеоритных отбойников</option>
            <option value='2'>Широкоспектральное тонирование иллюминаторов</option>

            <option value='3'>Замена маршевого двигателя</option>
            <option value='4'>Заправка</option>
        </select>
        <input type='button' value='купить' onclick=\"sendw('service','service_type');\" \><br>
        </form>
        ";
    return $out;
}

function game_market(&$data) {
    $out="
        <h2>Рынок - купля-продажа</h2>
        <h5>кстати тут не работает ничего</h5>
        <form>
        <input name='product_list' id='product_list' type='hidden' value='' \>
        <table border=0>
        <tr>
            <td>
            <select size='10' onchange=\"this.form.product_list.value=this.selectedIndex;\" >
                <option value='0'>Хлам железный</option>
                <option value='1'>Небольшой корабль</option>
                <option value='2'>Пища</option>
            </select>
            </td>

            <td>
            <select size='10' onchange=\"this.form.product_list.value=this.selectedIndex;\" >
                <option value='0'>Движок</option>
                <option value='1'>Небольшой корабль</option>
                <option value='2'>Пища</option>
            </select>
            </td>
        </tr>
        <tr>
            <td>
                <input type='button' value='продать' onclick=\"sendw('sell','product_list');\" \><br>
            </td>
            <td>
                <input type='button' value='купить' onclick=\"sendw('buy','product_list');\" \><br>
            </td>
        </tr>
        </form>
        ";
    return $out;
}

function game_ship() {
    $out="
    <div style='position:relative'>
    <div id='trum' style='position: absolute; overflow: auto; top: 495px; left: 10px; height: 90px; width: 780px; background-color: #eeeeee; border: 1px #ff0000 solid;'>
    </div>
    </div>
    ";

    return $out;
}

function battle_menu() {
    //onclick=\"sendw('addnew','name;fraction;description');\"
    $out="
    <form>
        <input name='battle_panel' id='battle_panel' type='hidden' value='' \>
        
        <input name='attack_x' id='attack_x' type='hidden' value='' \>
        <input name='attack_y' id='attack_y' type='hidden' value='' \>
        <input name='move_x' id='attack_x' type='hidden' value='' \>
        <input name='move_y' id='attack_x' type='hidden' value='' \>

        <input type='button' value='attack' onclick=\"sendi('battle','attack');\" />
        <input type='button' value='move' onclick=\"sendi('battle','move');\"/>

    </form>
    ";

    return $out;
}




function battle_attack_controller(&$data) {
    gen_debug("battle attack controller..",4);
    $data['cmd']='';
    echo gen_mess('mode', 'set', 'attack');
}

function battle_move_controller(&$data) {
    gen_debug("battle move controller..",4);
    $data['cmd']='';
    echo gen_mess('mode', 'set', 'move');
}

function battle_init_controller(&$data) {
    gen_debug("battle init controller..",4);
    $images_path="images/battle/";
    $mess="";
    $size=64;
    if (do_person($data)) {
        $data['persons'][0]['person_mode']=3;
       // gen_debug("current person:".$data['users'][0]['owned_person_id'],4);
        do_setperson_mode($data);
        echo gen_mess('mode', 'set', 'battle');
      //  echo gen_mess('replace', 'controlbar', battle_menu());
        echo gen_mess('replace', 'monitor', "<div id='map'></div>");//onmousedown=handleMouseDown(event,'map');
        if ( do_battle_ships($data) ) {
            gen_debug("processing battle..",4);
            $mess="<pp pn='ppic'>".$images_path."battle_background.png</pp>";
            echo gen_multi_mess('setpp', 'monitor', $mess);
            foreach($data['ships'] as $ship) {
                $mess="";
                $mess.= "<pp pn='pid'>ship_".code_id($ship['ship_id'])."</pp>";
                $mess.= "<pp pn='px'>".$ship['ship_xcoord']."</pp>";
                $mess.= "<pp pn='py'>".$ship['ship_ycoord']."</pp>";
                $mess.= "<pp pn='ppic'>".$images_path."ship_".$ship['ship_type_id'].".png</pp>";
                
                $mess.= "<pp pn='pd'>корабль ".$ship['ship_name']."</pp>";
                $mess.= "<pp pn='ps'>".$size."</pp>";
                gen_debug("shipowner:".$ship['owner_person_id'].", current person:".$data['user'][0]['owned_person_id'],4);
                if($ship['owner_person_id'] == $data['user'][0]['owned_person_id']) {
                    $mess.= "<pp pn='pb'>grey</pp>";
                }
                echo gen_multi_mess('addobj', 'monitor', $mess);
            }
        }
    }
}

function tuning_init_controller(&$data) {
    $images_path="images/tuning/";
    $mess="";
    gen_debug("selected ship: ".$data['val_dec'],2);
    echo gen_mess('mode', 'set', 'tuning');
    if (do_ship($data) && do_inventories($data) ) {
        echo gen_mess('replace', 'monitor', game_ship());
        switch(game_ship($data['ship'][0]['ship_type_id'])) {
        case 0:
        default:
            $size=64;
            $mess="<pp pn='ppic'>".$images_path."lander.png"."</pp>";
            echo gen_multi_mess('setpp', 'monitor', $mess);

            $mess="";
            $mess.= "<pp pn='pid'>slot_weapon_1</pp>";
            $mess.= "<pp pn='px'>150</pp>";
            $mess.= "<pp pn='py'>10</pp>";
            $mess.= "<pp pn='ppic'>".$images_path."slot.png</pp>";
            $mess.= "<pp pn='pd'>Оружие 1</pp>";
            $mess.= "<pp pn='ps'>".$size."</pp>";
            $mess.= "<pp pn='pb'>red</pp>";
            echo gen_multi_mess('addobj', 'monitor', $mess);

            $mess="";
            $mess.= "<pp pn='pid'>slot_weapon_2</pp>";
            $mess.= "<pp pn='px'>10</pp>";
            $mess.= "<pp pn='py'>10</pp>";
            $mess.= "<pp pn='ppic'>".$images_path."slot.png</pp>";
            $mess.= "<pp pn='pd'>Оружие 2</pp>";
            $mess.= "<pp pn='ps'>".$size."</pp>";
            $mess.= "<pp pn='pb'>red</pp>";
            echo gen_multi_mess('addobj', 'monitor', $mess);

            $mess="";
            $mess.= "<pp pn='pid'>slot_defense_1</pp>";
            $mess.= "<pp pn='px'>10</pp>";
            $mess.= "<pp pn='py'>150</pp>";
            $mess.= "<pp pn='ppic'>".$images_path."slot.png</pp>";
            $mess.= "<pp pn='pd'>Защита 1</pp>";
            $mess.= "<pp pn='ps'>".$size."</pp>";
            $mess.= "<pp pn='pb'>red</pp>";
            echo gen_multi_mess('addobj', 'monitor', $mess);

            $mess="";
            $mess.= "<pp pn='pid'>slot_defense_2</pp>";
            $mess.= "<pp pn='px'>150</pp>";
            $mess.= "<pp pn='py'>150</pp>";
            $mess.= "<pp pn='ppic'>".$images_path."slot.png</pp>";
            $mess.= "<pp pn='pd'>Оружие 2</pp>";
            $mess.= "<pp pn='ps'>".$size."</pp>";
            $mess.= "<pp pn='pb'>red</pp>";
            echo gen_multi_mess('addobj', 'monitor', $mess);

            for ($i=0; $i<13; $i++) {
                $mess="";
                $mess.= "<pp pn='pid'>slot_trum_".($i+1)."</pp>";
                $x=5+$i*($size+5);
                $mess.= "<pp pn='px'>".$x."</pp>";
                $mess.= "<pp pn='py'>5</pp>";
                $mess.= "<pp pn='ppic'>".$images_path."slot.png</pp>";
                $mess.= "<pp pn='pd'>trum ".($i+1)."</pp>";
                $mess.= "<pp pn='ps'>".$size."</pp>";
                $mess.= "<pp pn='pb'>red</pp>";
                echo gen_multi_mess('addobj', 'trum', $mess);
            }
            
            break;
        }

        $images_path="images/inventories/";
        
        foreach($data['inventories'] as $obj) {
            $name="slot_";
            $desc="";
            $mess="";

            switch($obj['inventory_type']) {
                case 1:
                    $name.="weapon_";
                    break;
                case 2:
                    $name.="defense_";
                    break;
                default:
                    $name.="trum_";
                    break;
            }
            $name.=$obj['inventory_slot'];
            $desc.=$obj['inventory_name'].htmlentities("<br>",ENT_NOQUOTES,'UTF-8');
            $desc.="Уровень износа ".$obj['inventory_quality_level'];
            $mess.="<pp pn='ppic'>".$images_path.$obj['inventory_icon']."</pp>";
            $mess.="<pp pn='pd'>".$desc."</pp>";
            //gen_debug("изменение слота ".$name." - pic:".$images_path.$obj['inventory_icon'].", desc:".$desc,2);
            echo gen_multi_mess('setpp', $name, $mess);
        }
    }
    
 /*   if ( do_inventories($data) ) {
        
    }*/


}

function galaxy_select_controller(&$data) {
  gen_debug("galaxy_select_controller",4);
  $images_path="images/systems/";
  $mess="";
  if ( do_person($data) ) {
    if( $data['person'][0]['owner_system_id'] == $data['val_dec'] ) {
        gen_debug("located system has been select",4);
        $data['mode']='system';
    } else {
        gen_debug("target system has been select:".$data['val_dec'],4);
        $data['mode']='gnavi';
        echo gen_mess('mode', 'set', 'gnavi');
    }
    $data['cmd']='init';
    cmd_sink($data);
  }
}

function gnavi_select_controller(&$data) {
  gen_debug("gnavi_select_controller",4);
  $images_path="images/systems/";
  $mess="";
  if ( do_person($data) ) {
    if( $data['person'][0]['owner_system_id'] == $data['val_dec'] ) {
        gen_debug("located system has been select",4);
    } else {
        gen_debug("target system has been select:".$data['val_dec'],4);
        do_system_flying($data);
    }
    $data['mode']='galaxy';
    $data['cmd']='init';
    cmd_sink($data);
  }
}

function person_init_controller(&$data) {
    echo gen_multi_mess('setpp', 'monitor', "<pp pn='ppic'>none</pp>");
    echo gen_mess('mode', 'set', 'person');
    if ( do_person($data) ) {
        echo gen_mess('replace', 'monitor', game_person($data['person'][0]));
    } else {
        echo gen_mess('replace', 'monitor', game_new_person());
    }
}

function system_fly_controller(&$data){
   gen_debug("start to system flying to ".$data['val_dec'],2);
   //if(do_flying($data)) {
//       gen_debug("you are flying to system ".$data['val'],2);
//   }
}

function sector_fly_controller(&$data){
   gen_debug("start to sector flying to ".$data['val_dec'],2);
   if(do_sector_flying($data)) {
       gen_debug("you are flying to sector ".$data['val_dec'],2);
   }
}

//function market_init_controller(&$data) {
//    $images_path="images/locations/";
//    $mess="";
//    echo gen_mess('replace', 'monitor', game_market());
//}

function location_init_controller(&$data) {
    gen_debug("location_init_controller",4);
    $images_path="images/locations/";
    $mess="";
    gen_debug("selected location: ".$data['val_dec'],2);
    $data['mode_menu']=array("назад"=>"sendm('galaxy');");
    cmd_from_view($data, "mode_menu", "replace", "modemenu");
    echo gen_mess('mode', 'set', 'location');
    if (do_location($data) ) {
        echo gen_mess('replace', 'controlbar', '<span>|элементы управления локациями</span><span>|</span>');
        switch($data['location'][0]['location_type']) {
            case 2: //market
                echo gen_mess('replace', 'monitor', game_market());
                $mess="<pp pn='ppic'>".$images_path."market_.png"."</pp>";
                echo gen_multi_mess('setpp', 'monitor', $mess);
                break;
            case 3: //remount
                echo gen_mess('replace', 'monitor', game_maintenance_depot());
                $mess="<pp pn='ppic'>".$images_path."maintenance_.png"."</pp>";
                echo gen_multi_mess('setpp', 'monitor', $mess);
                break;
            default:
                break;
        }
    }
}

function sector_init_controller(&$data) {
    gen_debug("sector_init_controller",4);
    $images_path="images/locations/";
    $mess="";
    if (do_locations($data) ) {
        echo gen_mess('replace', 'monitor', "<div id='map'></div>");
        $data['mode_menu']=array("назад"=>"sendm('galaxy');");
        cmd_from_view($data, "mode_menu", "replace", "modemenu");
        //$mess="<pp pn='ppic'>".$images_path."sector_background.png"."</pp>";
        $mess="<pp pn='ppic'>images/sectors/".$data['locations'][0]['sector_background']."</pp>";
        echo gen_multi_mess('setpp', 'monitor', $mess);
        echo gen_mess('mode', 'set', 'sector');
        foreach($data['locations'] as $obj) {
            $mess="";
            $mess.= "<pp pn='pid'>".code_id($obj['location_id'])."</pp>";
            $mess.= "<pp pn='px'>".$obj['location_xcoord']."</pp>";
            $mess.= "<pp pn='py'>".$obj['location_ycoord']."</pp>";
            $mess.= "<pp pn='ppic'>".$images_path.$obj['location_icon']."</pp>";
            $mess.= "<pp pn='pd'>".$obj['location_name'].".".$obj['location_desc']."</pp>";
            $mess.= "<pp pn='ps'>128</pp>";
            $mess.= "<pp pn='pselect'>object</pp>";
            gen_debug($mess,2);
            echo gen_multi_mess('addobj', 'map', $mess);
        }
    }
}

function system_init_controller(&$data) {
    gen_debug("system_init_controller",4);
    $images_path="images/sectors/";
    $mess="";
    if ( do_person($data) && do_sectors($data) ) {
        gen_debug("__person:".$data['person'][0]['person sector_id'],2);
        echo gen_mess('replace', 'monitor', "<div id='map'></div>");
        $data['mode_menu']=array("назад"=>"sendm('galaxy');");
        cmd_from_view($data, "mode_menu", "replace", "modemenu");
        $mess="<pp pn='ppic'>images/systems/".$data['sectors'][0]['system_background']."</pp>";
        echo gen_multi_mess('setpp', 'monitor', $mess);
        echo gen_mess('mode', 'set', 'system');
        foreach($data['sectors'] as $obj) {
            $mess="";
            $mess.= "<pp pn='pid'>".code_id($obj['sector_id'])."</pp>";
            $mess.= "<pp pn='px'>".$obj['sector_xcoord']."</pp>";
            $mess.= "<pp pn='py'>".$obj['sector_ycoord']."</pp>";
            $mess.= "<pp pn='ppic'>".$images_path.$obj['sector_icon']."</pp>";
            $mess.= "<pp pn='pd'>".$obj['sector_name'].".".$obj['sector_desc']."</pp>";
            $mess.= "<pp pn='ps'>38</pp>";
            $mess.= "<pp pn='pselect'>object</pp>";
            if($data['person'][0]['person_sector_id']==$obj['sector_id']) {
                $mess.= "<pp pn='pb'>green</pp>";
                gen_debug("sector_mismatch",2);
            }
            gen_debug($mess,2);
            echo gen_multi_mess('addobj', 'map', $mess);
        }
    }
}

function galaxy_init_controller(&$data) {
    gen_debug("galaxy_init_controller",4);
    $images_path="images/systems/";
    $mess="";
    if ( do_person($data) && do_systems($data) ) {
        //foreach ($data['person'] as $e) {
         //   gen_debug("sample:".$e['owner_system_id'].", ".$e['person_id'],4);
        //}
        gen_debug("location system_id:".$data['person'][0]['owner_system_id'],2);
       // echo gen_mess('replace', 'controlbar', '<span>|</span><span>элементы управления галактикой... не предусмотрено!</span><span>|</span>');
        echo gen_mess('replace', 'monitor', "<div id='map'></div>");
        //$data['mode_menu']=array("назад"=>"sendm('galaxy');");
        echo gen_mess('replace', 'modemenu',"<span \>");

        $mess="<pp pn='ppic'>images/galaxy/galaxy_background.png"."</pp>";
        echo gen_multi_mess('setpp', 'monitor', $mess);
        echo gen_mess('mode', 'set', 'galaxy');
        foreach($data['systems'] as $obj) {
            $mess="";
            $mess.= "<pp pn='pid'>".code_id($obj['system_id'])."</pp>";
            $mess.= "<pp pn='px'>".$obj['system_xcoord']."</pp>";
            $mess.= "<pp pn='py'>".$obj['system_ycoord']."</pp>";
            $mess.= "<pp pn='ppic'>".$images_path.$obj['system_icon']."</pp>";
            $mess.= "<pp pn='pd'>".$obj['system_name'].".".$obj['system_desc']."</pp>";
            $mess.= "<pp pn='ps'>".$obj['system_size']."</pp>";
            $mess.= "<pp pn='pselect'>object</pp>";
            // person mode
            // 0 - galaxy
            // 1 - system
            // 2 - sector
            // 3 - location
          if($data['person'][0]['owner_system_id']==$obj['system_id']) {
                $mess.= "<pp pn='pb'>green</pp>";
           }
     //       gen_debug("EE:".$mess,2);
            echo gen_multi_mess('addobj', 'map', $mess);
      }
    } else {
        // possible personage dosn't exists... navigate to person mode
        $data['mode']='person';
        $data['cmd']='init';
        cmd_sink($data);
    }
}

//function isPointInArea($_area, $_xPoint, $_yPoint) {

//}
/*
function galaxy_mouse_controller(&$data) {
    //echo gen_mess('replace', 'monitor', game_galaxy());
    if (do_systems($data) ) {
        switch($data['cmd']) {
            case 'down':
                gen_debug("down processing",2);
                break;
            case 'hover':
                gen_debug("hover processing",2);
                break;
        }
//        foreach($data['systems'] as $obj) {
  //          $obj['system_id']."</pp>";
    //        $obj['system_xcoord']."</pp>";
      //      $obj['system_ycoord']."</pp>";

//            gen_debug($mess,2);
  //          echo gen_multi_mess('addobj', 'map', $mess);
//        }
    }
}*/


function login_controller(&$data) {
    if(check_login($data)) {
        gen_debug('login successfully:'.$data['user'][0]['user_login'],4);
        cmd_from_view($data, 'game_structure', 'replace', 'main');
        echo gen_mess('mode', 'set', 'galaxy');
        echo gen_mess('replace', 'monitor', "<div id=map></div>");
        $data['mode']='galaxy';
        $data['cmd']='init';
        cmd_sink($data);
    } else {
        gen_debug('login fails',2);
        //
        //echo gen_mess('replace', 'main', guest_login());
    }
}

function registry_controller(&$data) {
    if(     $data['login']!="" &&
            $data['password1']!="" &&
            $data['password1'] == $data['password2'] &&
            $data['email']!="") {
        if( do_registry($data) ) {
            $mess=array(    'to'=>$data['email'],
                            'subj'=>"Регистрация в игре",
                            'mess'=>"Привет!\n\nВы успешно зарегились в игрухе.\n\nАдминистрация");
            if ( send_mail($mess) ){
                echo gen_mess('replace', 'main', game_structure($data['login']));
                echo gen_mess('mode', 'set', 'userpofile');
                $data['user'][0]['user_login'] = $data['login'];
                $data['user'][0]['user_password'] = $data['password1'];
                $data['user'][0]['user_email'] = $data['email'];
                $data['user'][0]['user_avatar'] = "test_avatar.jpg";
                echo gen_mess('replace', 'monitor', user_profile($data));
                return true;
            }
        }
    }
    gen_debug('registration fails',2);
    echo gen_mess('replace', 'main', guest_login());
    return false;
}

function reminder_controller(&$data) {
    gen_debug('reminder_controller:'.$data['email'],4);
    if( do_reminder($data) ) {
        gen_debug('reminder_controller2:'.$data['reminder'][0]['user_login'].", ".$data['reminder'][0]['user_password'],5);

        if( $data['reminder'][0]['user_login'] !="" && $data['reminder'][0]['user_password'] !="" ) {

            $mess=array(
                'to'=>$data['email'],
                'subj'=>"напоминание акаунта к игре",
                'mess'=>"Привет!\nВы забыли логин или пароль!\nлогин:".$data['reminder'][0]['user_login']."\nпароль:".$data['reminder'][0]['user_password']."\n\nАдминистрация"
            );
            if ( send_mail($mess) ) {
                return true;
            }
        }
    }
    gen_debug('regmind fails',2);
    return false;
}

function update_userprofile(&$data){
    gen_debug('userprofile update: not implemented',2);
}

function encyclopedia_controller(&$data) {
    gen_debug('encyclopedia: not implemented',2);
}

function check_session(&$data) {
    global $link;
    if(isset($data['session']) && $data['session']!="") {
        $query="select * from users where session='".$data['session']."'";
        if ( mysql_ask($data['user'],$query) ) {
            $query="update users set session_timestamp=".$data['stamp']." where session='".$data['session']."'";
            gen_debug('operator updating successfull',5);
            return mysql_update($query);
        }
    }
    return false;
}

function check_login(&$data) {
    global $link;
    $query="select user_id, user_login, user_mode, user_avatar, owned_person_id from users where user_login='".$data['login']."' and user_password='".$data['password']."'";
    gen_debug($query,5);
    if ( mysql_ask($data['user'],$query) ) {
        $query="update users set session='".$data['session']."', session_timestamp=".$data['stamp']."  where user_login='".$data['login']."' and user_password='".$data['password']."'";
        return mysql_update($query);
    }
    return false;
}

// потенциально кривое решение !!! надо либо транзакцию добавить либо делать id такой же как и юзер id.
function do_addperson(&$data) {
    $query="insert into persons values (NULL,'".$data['person_name']."',1,9,1,'persons/guest.png','".$data['person_description']."',0,0,0,0,".$data['person_fraction'].")";
    gen_debug("do_addperson_1:".$query,5);
    if ( mysql_add($query) ) {
        $data['user'][0]['owned_person_id']=mysql_insert_id();
        $query="update users set owned_person_id=".$data['user'][0]['owned_person_id']." where user_id=".$data['user'][0]['user_id'];
        gen_debug("do_addperson_2:".$query,5);
        $res=mysql_update($query);
        if(!$res) {
            $data['user'][0]['owned_person_id']=NULL;
        }
        return $res;
    }
    return false;
}

function do_setperson_mode(&$data) {
    gen_debug("person mode - set to:".$data['persons'][0]['person_mode'],4);
    $query="update persons set person_mode=".$data['persons'][0]['person_mode']." where person_id=".$data['user'][0]['owned_person_id'];
    gen_debug($query,5);
    return mysql_update($query);
}

function do_battle_ships(&$data) {
    $query="select ships.* from ships, persons where persons.person_mode=3";
    //and ships.owner_person_id=persons.person_id";
    gen_debug($query,5);
    return mysql_ask($data['ships'],$query);
}

// to join with do_ssector_flying in the future
function do_system_flying(&$data) {
    gen_debug("subsystem flying",4);
    $query="select sector_id from sectors where owner_system_id=".$data['val_dec'];
    gen_debug($query,5);
    if(isset($data['sectors'])) {
        unset($data['sectors']);
    }
    if(mysql_ask($data['sectors'],$query)) {
        $query="update persons set person_sector_id=".$data['sectors'][0]['sector_id']." where person_id=".$data['user'][0]['owned_person_id'];
        gen_debug($query,5);
        return mysql_update($query);
    }
    return false;
}

// to join with do_system_flying in the future
function do_sector_flying(&$data) {
    gen_debug("subsector flying",4);
    $query="select sector_id from sectors where sector_id=".$data['val_dec'];
    gen_debug($query,5);
    if(isset($data['sectors'])) {
        unset($data['sectors']);
    }
    if(mysql_ask($data['sectors'],$query)) {
        $query="update persons set person_sector_id=".$data['sectors'][0]['sector_id']." where person_id=".$data['user'][0]['owned_person_id'];
        gen_debug($query,5);
        return mysql_update($query);
    }
    return false;
}

function do_person(&$data) {
    //$query="select * from persons where person_id=".$data['user'][0]['owned_person_id'];
    if(isset($data['person'])) {
        unset($data['person']);
    }
    $query="select persons.*, sectors.owner_system_id from sectors, persons where
        persons.person_id=".$data['user'][0]['owned_person_id']." and
        sectors.sector_id=persons.person_sector_id";
    gen_debug($query,5);
    return mysql_ask($data['person'],$query);
}

//function do_person_location(&$data) {
//    $query="select sectors.owner_system_id from sectors,persons where persons.person_id=".$data['person'][0]['person_id']." and sectors.sector_id=".$data['person'][0]['person_sector_id'];
//    gen_debug($query,5);
//    return mysql_ask($data['person'],$query);
/////}

function do_inventories(&$data) {
    $query="select * from inventories where owner_ship_id=".$data['ship'][0]['ship_id'];
    gen_debug($query,5);
    return mysql_ask($data['inventories'],$query);
}


function do_ship(&$data) {
    $query="select * from ships where owner_person_id=".$data['user'][0]['owned_person_id'];
    gen_debug($query,5);
    return mysql_ask($data['ship'],$query);
}

function do_systems(&$data) {
    $query="select * from systems";
    if(isset($data['systems'])) {
        unset($data['systems']);
    }
    gen_debug($query,5);
    return mysql_ask($data['systems'],$query);
}

function do_location(&$data) {
    $query="select * from locations where location_id=".$data['val_dec'];
    gen_debug($query,5);
    return mysql_ask($data['location'],$query);
}

function do_locations(&$data) {
    $query="select locations.*,sectors.sector_background from locations,sectors where locations.owner_sector_id=".$data['val_dec']. " and sectors.sector_id=".$data['val_dec'];
    gen_debug("!!!".$query,5);
    return mysql_ask($data['locations'],$query);
}

function do_sectors(&$data) {
    $query="select sectors.*,systems.system_background from sectors,systems where sectors.owner_system_id=".$data['val_dec']." and systems.system_id=".$data['val_dec'];
    gen_debug($query,5);
    return mysql_ask($data['sectors'],$query);
}

function do_addchat(&$data) {
    global $link;
        //$t=gettime();
        gen_debug('do_addchat, stamp:'.$t,4);
        $query="insert into chat_messages values (NULL, 0, 0,'".$data['chat_message']."',".gettime().",".$data['user'][0]['owned_person_id'].")";
        gen_debug($query,5);
        return mysql_add($query);
}

function do_getchat(&$data){
    gen_debug('do_getchat',4);
 //   if( !isset($data['chat_message_id'])) {
//        $data['chat_message_id']=0;
 //   }
    $query="select c.message_id, p.person_name, c.message_timestamp, c.message_text from chat_messages as c,persons as p where c.message_id>".$data['chat_message_id']." and c.message_author_id=p.person_id";
    gen_debug($query,5);
    return mysql_ask($data['chat'],$query);
}

function do_registry(&$data) {
    global $link;
    gen_debug('do registry starting',5);
    $query="select user_login, user_email from users where user_login='".$data['login']."' or user_email='".$data['email']."'";
    $res=mysql_ask($data['temp_user'],$query);
    if(!$res) {
        $query="insert into users values (NULL, '".$data['login']."','".$data['password1']."','".$data['email']."','test_avatar.jpg',
            '".$data['session']."', ".$data['stamp'].",NULL,1)";
        return mysql_add($query);
    }
    return false;
}

function do_reminder(&$data) {
    global $link;
    $query="select user_login, user_password from users where user_email='".$data['email']."'";
    return mysql_ask($data['reminder'],$query);
}

function do_logout($data) {
    $query="update users set session='".md5(microtime())."', session_timestamp=".$data['stamp']." where session='".$data['session']."'";
    gen_debug($query,5);
    return mysql_update($query);
}



function round_process($data) {
}

function cmd_sink(&$data) {
    switch($data['mode']) {
        case 'logout':
            if (do_logout($data)) {
                cmd_from_view($data, 'guest_login', 'replace', 'main');
                echo gen_mess('mode', 'set', 'login');
            } else gen_debug('logout fails',2);
            break;
        case 'attack':
            switch($data['cmd']) {
                case 'down':
                    gen_debug("attack: down map select",4);
                    break;
                case 'select':
                    gen_debug("battle attack select",4);
                    break;
                default:
                    $data['mode']='battle';
                    $data['cmd']='continue';
                    cmd_sink($data);
                    break;
            }
            break;
        case 'move':
            switch($data['cmd']) {
                case 'down':
                    gen_debug("move: down map select",4);
                    break;
                case 'select':
                    gen_debug("battle move select",4);
                    break;
                default:
                    $data['mode']='battle';
                    $data['cmd']='continue';
                    cmd_sink($data);
                    break;
            }
            break;
        case 'battle':
            switch($data['cmd']) {
//                case 'select':
//                    gen_debug("battle select:".$data['val_dec'],4);
//                    galaxy_select_controller($data);
//                    break;
                case 'attack':
                    gen_debug("battle: attack processing..",4);
                    battle_attack_controller($data);
                    break;
                case 'move':
                    gen_debug("battle: move  processing..",4);
                    battle_move_controller($data);
                    break;
                case 'continue':
                    gen_debug("continue battling..",4);
                    break;
                case 'init':
                default:
                    battle_init_controller($data);
                    break;
            }
            break;
        case 'person':
            switch($data['cmd']) {
 /*               case 'up':
                    $data['mode']='sector';
                    $data['cmd']='init';
                    cmd_sink($data);
                    break;*/
                case 'addnew':
                    list($data['person_name'],$data['person_fraction'],$data['person_description'])=split(";",$data['val']);
                    if(do_addperson($data)) {
                        $data['cmd']='init';
                        cmd_sink($data);
                    }
                case 'init':
                default:
                    person_init_controller($data);
                    break;
            }
            break;
        case 'userprofile':
            switch($data['cmd']) {
 /*                case 'up':
                    $data['mode']='sector';
                    $data['cmd']='init';
                    cmd_sink($data);
                    break;*/
                case 'update':
                    list($data['name'],$data['password1'],$data['password2'],$data['email'])=split(";",$data['val']);
                    update_userprofile($data);
                    break;
                case 'init':
                default:
                    echo gen_mess('replace', 'monitor', user_profile($data));
                    break;
            }
            break;
        case 'encyclopedia':
            gen_debug('encyclopedia case...',4);
            switch($data['cmd']) {
 /*                case 'up':
                    $data['mode']='sector';
                    $data['cmd']='init';
                    cmd_sink($data);
                    break;*/
                case 'navigation':
                    list($data['topic'],$data['chapter'],$data['page'])=split(";",$data['val']);
                    gen_debug('encyclopedia-navigation:'.$data['topic'].','.$data['chapter'].','.$data['page'],2);
                    encyclopedia_controller($data);
                    break;
                case 'init':
                default:
                    gen_debug('encyclopedia default case...',4);
                    echo gen_mess('replace', 'monitor', user_encyclopedia());
                    echo gen_mess('replace', 'controlbar', '<span>|элементы управления энциклОпедией</span><span>|</span>');
                    break;
            }
            break;
        case 'news':
            switch($data['cmd']) {
 /*               case 'up':
                    $data['mode']='sector';
                    $data['cmd']='init';
                    cmd_sink($data);
                    break;*/
                case 'init':
                default:
                    echo gen_mess('replace', 'monitor', user_news());
                    echo gen_mess('replace', 'controlbar', '<span>|элементы управления новостями</span><span>|</span>');
                    break;
            }
            break;
         case 'location':
            switch($data['cmd']) {
  /*               case 'up':
                    $data['mode']='sector';
                    $data['cmd']='init';
                    cmd_sink($data);
                    break;*/
                case 'init':
                default:
                    location_init_controller($data);
                    break;
            }
            break;
         case 'market':
            switch($data['cmd']) {
//                case 'up':
                case 'exit':
                    $data['mode']='sector';
                    $data['cmd']='init';
                    cmd_sink($data);
                    break;
                case 'init':
                default:
                    market_init_controller($data);
                    echo gen_mess('replace', 'controlbar', '<span>|элементы управления рынками</span><span>|</span>');
                    break;
            }
            break;
         case 'tuning':
            switch($data['cmd']) {
 /*                case 'up':
                    gen_debug("up go to sector",2);
                    $data['mode']='sector';
                    $data['cmd']='init';
                    cmd_sink($data);
                    break;*/
                case 'init':
                default:
                    tuning_init_controller($data);
                    echo gen_mess('replace', 'controlbar', '<span>|элементы управления настройками корабля</span><span>|</span>');
                    break;
            }
            break;
         case 'sector':
            switch($data['cmd']) {
 /*               case 'up':
                    gen_debug("up go to system",2);
                    $data['mode']='system';
                    $data['cmd']='init';
                    cmd_sink($data);
                    break;*/
                case 'flying':
                    sector_fly_controller($data);
                    break;
                case 'select':
                    $data['mode']='location';
                    $data['cmd']='init';
                    cmd_sink($data);
                    break;
                case 'init':
                default:
                    sector_init_controller($data);
                    break;
            }
            break;
        case 'system':
            switch($data['cmd']) {
 /*                case 'up':
                    gen_debug("up go to galaxy",2);
                    $data['mode']='galaxy';
                    $data['cmd']='init';
                    cmd_sink($data);
                    break;*/
                case 'flying':
                    system_fly_controller($data);
                    break;
                case 'select':
                    $data['mode']='sector';
                    $data['cmd']='init';
                    cmd_sink($data);
                    break;
                case 'init':
                default:
                    system_init_controller($data);
                    break;
            }
            break;
        case 'navigation':
            switch($data['cmd']) {
                case 'select':
                    break;
                default:
                    $data['mode']=$data['data'];
                    $data['cmd']='init';
                    cmd_sink($data);
                    break;
            }
            break;
        case 'gnavi':
            gen_debug("gnavi mode parsing",4);
            switch($data['cmd']) {
                case 'select':
                    gen_debug("gnavi select:".$data['val_dec'],4);
                    gnavi_select_controller($data);
                    break;
                case 'init':
                default:
                    gen_debug("gnavi init:".$data['val_dec'],4);
                    echo gen_multi_mess('setpp', code_id($data['val_dec']), "<pp pn='pb'>red</pp>");
                    echo gen_mess('mode', 'set', 'gnavi');
                    break;
            }
            break;
        case 'galaxy':
            switch($data['cmd']) {
 /*                case 'up':
                    $data['mode']='galaxy';
                    $data['cmd']='init';
                    cmd_sink($data);
                    break;*/
                case 'select':
                    gen_debug("galaxy select:".$data['val_dec'],4);
                    galaxy_select_controller($data);
                    break;
                    /*
                        case 'hover':
                            list($data['sel_obj'])=split(";",$data['val_dec']);
                            gen_debug('galaxy is having mouse action... hover:'.$data['sel_obj'],4);
                            //galaxy_mouse_controller($data);
                            break;
                        case 'down':
                            list($data['mouse_x'],$data['mouse_y'])=split(";",$data['val_dec']);
                            gen_debug('galaxy is having mouse action... x='.$data['mouse_x'].', y='.$data['mouse_y'],4);
                            //galaxy_mouse_controller($data);
                            break;*/
                case 'init':
                default:
                    galaxy_init_controller($data);
                    //echo gen_mess('replace', 'menu', game_menu());//navigation_menu())
                    //echo gen_mess('replace', 'menu', game_menu());
                    break;
            }
            break;
        default:
            cmd_from_view($data, 'game_structure', 'replace', 'main');
            //echo gen_mess('replace', 'main', game_structure($data['user'][0]['user_login']));
            //echo gen_mess('mode', 'set', 'userpofile');
            //echo gen_mess('replace', 'monitor', user_profile($data));
            $data['mode']='galaxy';
            $data['cmd']='init';
            cmd_sink($data);
            break;
        } // end of default switch
        switch($data['cmd']) {
            case 'chat':
                // we have chat_message_id masked as timestamp in browser side...
                list($data['chat_message'],$data['chat_message_id'])=split(";",$data['val']);
                if($data['chat_message']!="" && $data['user'][0]['owned_person_id']!=null && do_addchat($data) ) {
                    gen_debug('recording chat message',4);
                    echo gen_mess('setvalue','chat_input',' ');
                }
            case 'chatup':
                gen_debug('chatup processing',4);
                if(!isset($data['chat_message_id'])) {list($data['chat_message_id'])=split(";",$data['val']);}
                if(do_getchat($data)) {
                    gen_debug('chat message:'.$data['chat_message'].", timestamp:".$data['chat_message_id'],4);
                    foreach ($data['chat'] as $line) {
                        //echo gen_mess('aas','chat_monitor','<p>'.$line['person_name']."(".date("Y-m-d H:i:s", $line['message_timestamp']).")> ".$line['message_text']);
                        echo gen_mess('aas','chat_monitor',"<p><span class=\"nic\">".$line['person_name']."</span><span class=\"dat\">(".date("H:i:s", $line['message_timestamp']).")</span><span class=\"mess\"> ".$line['message_text']."</span></p>");
                    }
                    echo gen_mess('setvalue','chat_timestamp',$line['message_id']);
                }
                break;
            default:
                break;
        }

//                        case 'addmessage':
//
//                            gen_debug('chat incoming message:'.$data['chat_message'].", timestamp:".$data['chat_timestamp'],4);
//                            if (if $data['user'][0]['owned_person_id']!=null && do_addchat($data) ) {
//                                gen_debug('chat incoming message ok',4);
//                            } else gen_debug('chat incoming message fail',2);
//                        case 'getmessages':
//                        default:
//                            list($data['chat_timestamp'])=split(";",$data['val']);
//                            gen_debug('chat new message query... timestamp:'.$data['chat_timestamp'],4);
//                            break;
//                    }

}


header('Content-Type: text/xml');
echo "<?xml version='1.0' encoding='UTF-8' standalone='yes'?><response>";

// debug = 0 - no logs
// debug = 1 - based modes
// debug = 2 - errors
// debug = 3 - variables
// max debug = 5 - every query logged
$debug=5;

$link=NULL;

if( @file_exists( "./base.php")) {
    include_once "./base.php";
} else gen_debug('base.php fails',2);

gen_debug('start processing');

if(connect($db_host, $db_name, $db_user, $db_password)) {
    gen_debug('db connecting...');
    $data['session']=session_id();
    if( set_inc_get($data,'mode') && set_inc_get($data,'cmd') && set_inc_get($data,'val') ) {
        gen_debug("mode:".$data['mode'].", cmd:".$data['cmd'].", val:".$data['val'],3);
        $data['stamp']=getmicrotime();
        if( check_session($data) ) {
            gen_debug('session successfully...',4);
            $data['val_dec']=decode_id($data['val']);
            cmd_sink($data);
        } else {
            gen_debug('session fails',2);
       
            switch($data['cmd']) {
            case 'registry':
                if ($data['val']=="") { 
                   cmd_from_view($data, 'guest_registry', 'replace', 'main');
                } else {
                    list($data['login'],$data['password1'],$data['password2'],$data['email'])=split(";",$data['val']);
                    registry_controller($data);
                }
                break;
            case 'reminder':
                if ($data['val']=="") {
                    cmd_from_view($data, 'guest_remind', 'replace', 'main');
                } else {
                    list($data['email'])=split(";",$data['val']);
                    reminder_controller($data);
                    cmd_from_view($data, 'guest_login', 'replace', 'main');
                }
                break;
            case 'login':
                if ($data['val']=="") {
                    cmd_from_view($data, 'guest_login', 'replace', 'main');
                } else {
                    list($data['login'],$data['password'])=split(";",$data['val']);
                    login_controller($data);
                }
                break;
            default:
                    cmd_from_view($data, 'guest_login', 'replace', 'main');
                break;
            }
        }
    }
    disconnect();
} else {
  gen_debug('db connection fails',2);
}

gen_debug('end processing');
gen_debug('_');
echo "</response>";

?>
