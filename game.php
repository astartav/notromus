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


function battack_init_controller(&$data) {
    gen_debug("battle attack controller..",4);
    echo gen_mess('mode', 'set', 'battack');
    $mess="";
    $mess.= "<pp pn='pb'>white</pp>";
    echo gen_multi_mess('setpp', 'attack_target', $mess);

    $mess="";
    $mess.= "<pp pn='pselect'>coord</pp>";
    echo gen_multi_mess('setpp', 'monitor', $mess);
}

function battack_down_controller(&$data) {
    gen_debug("battle down controller..",4);
    list($xx,$yy)=split(";",$data['val']);
    $mess="";
    $mess.= "<pp pn='pb'>orange</pp>";
    $mess.= "<pp pn='px'>".$xx."</pp>";
    $mess.= "<pp pn='py'>".$yy."</pp>";
    echo gen_multi_mess('setpp', 'attack_target', $mess);


    $mess="";
    $mess.= "<pp pn='pselect'>unset</pp>";
    echo gen_multi_mess('setpp', 'monitor', $mess);

    echo gen_mess('mode', 'set', 'battle');
}


function bflying_init_controller(&$data) {
    gen_debug("battle flying controller..",4);
    echo gen_mess('mode', 'set', 'bflying');
    $mess="";
    $mess.= "<pp pn='pb'>white</pp>";
    echo gen_multi_mess('setpp', 'flying_target', $mess);

    $mess="";
    $mess.= "<pp pn='pselect'>coord</pp>";
    echo gen_multi_mess('setpp', 'monitor', $mess);
}

function bflying_down_controller(&$data) {
    gen_debug("battle down controller..",4);
    list($xx,$yy)=split(";",$data['val']);
    $mess="";
    $mess.= "<pp pn='pb'>green</pp>";
    $mess.= "<pp pn='px'>".$xx."</pp>";
    $mess.= "<pp pn='py'>".$yy."</pp>";
    echo gen_multi_mess('setpp', 'flying_target', $mess);


    $mess="";
    $mess.= "<pp pn='pselect'>unset</pp>";
    echo gen_multi_mess('setpp', 'monitor', $mess);

    echo gen_mess('mode', 'set', 'battle');
}

function battle_init_controller(&$data) {
    gen_debug("battle init controller..",4);
    $images_path="images/battle/";
    $mess="";
    $size=64;

    if (do_person($data)) {
        $data['persons'][0]['person_mode']=3;
       
        do_setperson_mode($data);
        echo gen_mess('mode', 'set', 'battle');
        $data['mode_menu']=array( "аттака"=>"sendm('battack');",
                              "полет"=>"sendm('flying');",
                              "тюнинг"=>"sendm('battle_tuning');",
                            );


        cmd_from_view($data, "battle", "replace", "modearea");

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
                echo gen_multi_mess('addobj', 'map', $mess);

            }
                $mess="";
                $mess.= "<pp pn='pid'>attack_target</pp>";
                $mess.= "<pp pn='px'>100</pp>";
                $mess.= "<pp pn='py'>100</pp>";
                $mess.= "<pp pn='ppic'>".$images_path."attack.png</pp>";
                $mess.= "<pp pn='pd'>Стрельнет сюда</pp>";
                $mess.= "<pp pn='ps'>10</pp>";
                $mess.= "<pp pn='pb'>orange</pp>";
                $mess.= "<pp pn='pselect'>object</pp>";
                echo gen_multi_mess('addobj', 'map', $mess);

                $mess="";
                $mess.= "<pp pn='pid'>flying_target</pp>";
                $mess.= "<pp pn='px'>300</pp>";
                $mess.= "<pp pn='py'>400</pp>";
                $mess.= "<pp pn='ppic'>".$images_path."flying.png</pp>";
                $mess.= "<pp pn='pd'>Полетит сюда</pp>";
                $mess.= "<pp pn='ps'>10</pp>";
                $mess.= "<pp pn='pb'>lime</pp>";
                $mess.= "<pp pn='pselect'>coord</pp>";
                echo gen_multi_mess('addobj', 'map', $mess);

                $mess="";
                $mess.= "<pp pn='pselect'>unset</pp>";
                echo gen_multi_mess('setpp', 'monitor', $mess);

        }
    }
}

function battle_tuning_init_controller(&$data) {
    $images_path="images/tuning/";
    $mess="";
    gen_debug("selected ship: ".$data['val_dec'],2);
    echo gen_mess('mode', 'set', 'battle_tuning');
    if (do_ship($data) && do_inventories($data) ) {
        //echo gen_mess('replace', 'monitor', game_ship());
        cmd_from_view($data, "battle_tuning", "replace", "monitor");

        //switch(game_ship($data['ship'][0]['ship_type_id'])) {
        //case 0:
        //default:
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

         //   break;
        //}

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

function navi_tuning_init_controller(&$data) {
    $images_path="images/tuning/";
    $mess="";
    gen_debug("selected ship: ".$data['val_dec'],2);
    echo gen_mess('mode', 'set', 'navi_tuning');
    if (do_ship($data) && do_inventories($data) ) {
        //echo gen_mess('replace', 'monitor', game_ship());
        cmd_from_view($data, "navi_tuning", "replace", "monitor");
   //     switch(game_ship($data['ship'][0]['ship_type_id'])) {
   //     case 0:
    //    default:
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
            
  //          break;
  //      }

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
    echo gen_mess('mode', 'set', 'person');
    if ( do_person($data) ) {
        cmd_from_view($data, "show_person", "replace", "modearea");
    } else {
        cmd_from_view($data, "create_person", "replace", "modearea");
    }
}

function user_profile_controller($data) {
    echo gen_mess('mode', 'set', 'userprofile');
    cmd_from_view($data, "change_user", "replace", "modearea");
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

function location_init_controller(&$data) {
    gen_debug("location_init_controller",4);
    $images_path="images/locations/";
    $mess="";
    gen_debug("selected location: ".$data['val_dec'],2);

    $data['mode_menu']=array("назад"=>"sendm('galaxy');");
    cmd_from_view($data, "navigation", "replace", "monitor");
    echo gen_mess('mode', 'set', 'location');
    if (do_location($data) ) {
        switch($data['location'][0]['location_type']) {
            case 2: //market
                cmd_from_view($data, 'market', 'replace', 'modearea');
                $mess="<pp pn='ppic'>".$images_path."market_.png"."</pp>";
                echo gen_multi_mess('setpp', 'monitor', $mess);
                break;
            case 3: //remount
                cmd_from_view($data, 'maintenance_depot', 'replace', 'modearea');
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
        $data['mode_menu']=array("назад"=>"sendm('galaxy');");
        cmd_from_view($data, "navigation", "replace", "monitor");
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
        $data['mode_menu']=array("назад"=>"sendm('galaxy');");
        cmd_from_view($data, "navigation", "replace", "monitor");
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
        $data['mode_menu']=array("назад"=>"sendm('galaxy');");
        cmd_from_view($data, "galaxy_navigation", "replace", "modearea");

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

function login_controller(&$data) {
    if(check_login($data)) {
        gen_debug('login successfully:'.$data['user'][0]['user_login'],4);
        cmd_from_view($data, 'game_structure', 'replace', 'main');
        echo gen_mess('mode', 'set', 'galaxy');
        $data['mode']='galaxy';
        $data['cmd']='init';
        cmd_sink($data);
    } else {
        gen_debug('login fails',2);
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
                $data['mode']='userprofile';
                $data['cmd']='init';
                cmd_sink($data);
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


function cmd_sink(&$data) {
    switch($data['mode']) {
        case 'logout':
            if (do_logout($data)) {
                cmd_from_view($data, 'guest_login', 'replace', 'main');
                echo gen_mess('mode', 'set', 'login');
            } else gen_debug('logout fails',2);
            break;
        case 'battack':
            switch($data['cmd']) {
                case 'down':
                    gen_debug("battack: down map select",4);
                    battack_down_controller($data);
                    gen_debug("battack: down map select proceed",4);
                    break;
                case 'select':
                    gen_debug("battack: select map:",4);
                    break;
                default:
                    battack_init_controller($data);
                    break;
            }
            break;
        case 'bflying':
            switch($data['cmd']) {
                case 'down':
                    bflying_down_controller($data);
                     break;
                case 'select':
                    gen_debug("bflying: select map:",4);
                    break;
                default:
                    bflying_init_controller($data);
                    break;
            }
            break;
        case 'battle':
            switch($data['cmd']) {
            
                case 'init':
                default:
                    battle_init_controller($data);
                    break;
            }
            break;
        case 'person':
            switch($data['cmd']) {
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
                case 'update':
                    list($data['name'],$data['password1'],$data['password2'],$data['email'])=split(";",$data['val']);
                    update_userprofile($data);
                    break;
                case 'init':
                default:
                    user_profile_controller($data);
                    break;
            }
            break;
        case 'encyclopedia':
            gen_debug('encyclopedia case...',4);
            switch($data['cmd']) {
                case 'navigation':
                    list($data['topic'],$data['chapter'],$data['page'])=split(";",$data['val']);
                    gen_debug('encyclopedia-navigation:'.$data['topic'].','.$data['chapter'].','.$data['page'],2);
                    encyclopedia_controller($data);
                    break;
                case 'init':
                default:
                    gen_debug('encyclopedia default case...',4);
                    echo gen_mess('replace', 'monitor', user_encyclopedia());
                    break;
            }
            break;
        case 'news':
            switch($data['cmd']) {
                case 'init':
                default:
                    echo gen_mess('replace', 'monitor', user_news());
                    break;
            }
            break;
         case 'location':
            switch($data['cmd']) {
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
                    break;
            }
            break;
        case 'navi_tuning':
            switch($data['cmd']) {
                case 'init':
                default:
                    navi_tuning_init_controller($data);
                    break;
            }
            break;
         case 'battle_tuning':
            switch($data['cmd']) {
                case 'init':
                default:
                    battle_tuning_init_controller($data);
                    break;
            }
            break;
         case 'sector':
            switch($data['cmd']) {
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

if( @file_exists( "./model.php")) {
    include_once "./model.php";
} else gen_debug('model.php fails',2);

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
