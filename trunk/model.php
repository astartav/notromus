<?php

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
?>
