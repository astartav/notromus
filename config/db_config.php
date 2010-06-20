<?php

function exec_query(&$_query){
    if(!mysql_query($_query) ) {
        echo "<p>db configuration error</p>";
        exit (mysql_error());
    }
}

function exec_queries(&$_queries,$name){
    echo "<h5>".$name."</h5>";
    $n=0;
    foreach ( $_queries as $query) {
        exec_query($query);
        $n++;
    }
    echo "<p>".$name.": ".$n." successfully</p>";
}
// check that param is valid
//
//function check_incoming($param) {
//   return ($param!="");
//}

$link=NULL;

//if (isset ($_POST['project_name']) && check_incoming($_POST['project_name'])) {
//    $project_name = $_POST['project_name'];
//}

if( @file_exists( "../base.php")) {
    include_once "../base.php";
} else echo "<p>base.php fails</p>";

//echo "<h2>".$db_host.", ".$db_name.", ".$db_root_user.", ".$db_root_password."</h2>";

echo "<h1>db config utility</h1>";
echo "<p>site mode:".$site."</p>";
echo "<br>";
echo "<p> host: ".$db_host."</p>";
echo "<p> root login:".$db_root_user."</p>";
echo "<p> root password:".$db_root_password."</p>";
echo "<p> user login:".$db_user."</p>";
echo "<p> user password:".$db_password."</p>";

if($site=='no') {

 	$link = mysql_connect($db_host,$db_root_user,$db_root_password);


	if (!$link){
        	return false;
	}

    //echo "<h5>dropping base</h5>";
    //$query="drop database ".$db_name;
    //exec_query($query);

    echo "<h5>creating database</h5>";
    $query="create database if not exists ".$db_name;
    exec_query($query);

    echo "<h5>creating user</h5>";
    $query="grant all on ".$db_name.".* to ".$db_user."@'%' identified by '".$db_password."'";
    exec_query($query);

    echo "<h5>saving...</h5>";
    $query="flush privileges";
    exec_query($query);

    mysql_close($link);
	$link = NULL;

  /*  if(!mysql_select_db($db_name,$link)) {
        echo "<p>db select error</p>";
        exit (mysql_error());
    }*/

}
    echo "<h5>reopening with new regetered user account</h5>";
    if(!connect($db_host, $db_name, $db_user,$db_password)) {
        echo "<p>db connect error</p>";
        exit (mysql_error());
    }

   
    $query="show tables";
    $mq = mysql_query($query);
    $q=array();
    $amount=0;
    if($mq) {
        while(list($tname)=mysql_fetch_row($mq)) {
            $q[] = "drop table ".$tname."; ";
            $amount++;
        }
        if($amount > 0) {
            exec_queries($q,'droping tables');
        }
    }


$queries=array();
$queries[]="
  create table if not exists fractions (
  fraction_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  fraction_name SMALLINT UNSIGNED NOT NULL COMMENT 'планета, станция, битва' ,
  fraction_description SMALLINT UNSIGNED NOT NULL COMMENT 'описание фракции, история развития')
COMMENT = 'фракции'";
$queries[]="
create table if not exists persons (
  person_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  person_name VARCHAR(32) NOT NULL COMMENT 'игровое имя персонажа',
  person_mode TINYINT UNSIGNED NOT NULL COMMENT 'навигация, битва, тюнинг...' ,
  person_sector_id TINYINT UNSIGNED NOT NULL COMMENT 'id сектора местонахождения' ,
  person_status TINYINT UNSIGNED NOT NULL COMMENT 'online or offline' ,
  person_avatar VARCHAR(32) NULL COMMENT 'картинка персонажа - из внутренних игровых ресурсов',
  person_description TEXT NULL COMMENT 'описание персонажа, его предистория',
  person_account INT UNSIGNED NOT NULL COMMENT 'казна' ,
  person_score INT UNSIGNED NOT NULL COMMENT 'игровые очки' ,
  person_experience INT UNSIGNED NOT NULL COMMENT 'игровой опыт' ,
  person_rang INT UNSIGNED NOT NULL COMMENT 'ранг или звание - как угодно' ,
  person_fraction_id INT UNSIGNED NOT NULL COMMENT 'фракция к которой принадлежит')
COMMENT = 'персонажи'";
$queries[]="
create table if not exists users (
  user_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_login VARCHAR(32) NOT NULL ,
  user_password VARCHAR(32) NOT NULL ,
  user_email VARCHAR(32) NOT NULL ,
  user_avatar VARCHAR(32) NULL COMMENT 'пользователь загружает свой',
  session CHAR(64) NULL COMMENT 'сессия - для авторизации пользователя при каждом запросе',
  session_timestamp BIGINT UNSIGNED NULL COMMENT 'временной отпечаток последнего запроса',
  owned_person_id INT UNSIGNED COMMENT 'персонаж для этого юзера' ,
  user_mode TINYINT NOT NULL )
COMMENT = 'пользователи'";
$queries[]="
create table if not exists ships_type (
  ship_type_id TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  type_name VARCHAR(32) NOT NULL,
  attack_max_value TINYINT UNSIGNED NULL COMMENT 'максимально возможный уровень атаки' ,
  flying_max_value TINYINT UNSIGNED NULL COMMENT 'максимальная дистанция перелета',
  slots_max_value TINYINT UNSIGNED NULL COMMENT 'максимальная количество слотов'
  )
COMMENT = 'типы кораблей-определяют максимально возможные характеристики корабля для данного типа'";
$queries[]="
create table if not exists ships (
  ship_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  ship_name VARCHAR(32) NULL COMMENT 'имя корабля',
  ship_xcoord SMALLINT UNSIGNED NULL COMMENT 'х координата' ,
  ship_ycoord SMALLINT UNSIGNED NULL COMMENT 'у координата',
  attack_xcoord SMALLINT UNSIGNED NULL COMMENT 'х координата атаки' ,
  attack_ycoord SMALLINT UNSIGNED NULL COMMENT 'у координата атаки',
  attack_round_value TINYINT UNSIGNED NULL COMMENT 'уровень атаки на данный ход' ,
  attack_max_value TINYINT UNSIGNED NULL COMMENT 'максимально возможный уровень атаки' ,
  flying_xcoord SMALLINT UNSIGNED NULL COMMENT 'х координата перелета',
  flying_ycoord SMALLINT UNSIGNED NOT NULL COMMENT 'y координата перелета',
  flying_round_value TINYINT UNSIGNED NULL COMMENT 'текущая дистанция для перелета' ,
  flying_max_value TINYINT UNSIGNED NULL COMMENT 'максимальная дистанция перелета' ,
  owner_person_id INT UNSIGNED NOT NULL COMMENT 'какому персу принадлежит',
  ship_type_id TINYINT NOT NULL COMMENT 'тип корабля'
  )
COMMENT = 'корабли'";
$queries[]="
create table if not exists systems (
  system_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  system_xcoord SMALLINT UNSIGNED NOT NULL COMMENT 'x координата системы',
  system_ycoord SMALLINT UNSIGNED NOT NULL COMMENT 'y координата системы',
  system_icon VARCHAR(64) COMMENT 'картинка системы',
  system_background VARCHAR(64) COMMENT 'картинка фон системы. Если null то картинка для системы по умолчанию',
  system_size SMALLINT UNSIGNED NOT NULL COMMENT 'размер системы на карте',
  system_name VARCHAR(32) NOT NULL COMMENT 'имя системы',
  system_desc text COMMENT 'описание системы',
  system_owner_id TINYINT UNSIGNED NULL COMMENT 'id фракции - владелеца системы'
  )
COMMENT = 'системы'";
$queries[]="
create table if not exists sectors (
  sector_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  sector_type TINYINT UNSIGNED NOT NULL COMMENT 'планета, искуственный объект, битва, пустой' ,
  sector_xcoord SMALLINT UNSIGNED NOT NULL COMMENT 'x координата сектора',
  sector_ycoord SMALLINT UNSIGNED NOT NULL COMMENT 'y координата сектора',
  sector_icon VARCHAR(64) COMMENT 'картинка сектора',
  sector_background VARCHAR(64) COMMENT 'картинка фона сектора. Если null то картинка для типа сектора по умолчанию',
  sector_name VARCHAR(32) NOT NULL COMMENT 'имя сектора',
  sector_desc text COMMENT 'описание сектора',
  owner_system_id INT UNSIGNED NOT NULL COMMENT 'владелец сектора - либо фракция либо игрок. надо обсудить' )
COMMENT = 'секторы'";
$queries[]="
create table if not exists locations (
  location_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  location_type TINYINT UNSIGNED NOT NULL COMMENT 'планета, станция, битва' ,
  location_xcoord SMALLINT UNSIGNED NULL COMMENT 'x координата локации',
  location_ycoord SMALLINT UNSIGNED NULL COMMENT 'y координата локации',
  location_icon VARCHAR(64) COMMENT 'картинка локации',
  location_background VARCHAR(64) COMMENT 'картинка фона локации. Если null то картинка для типа локации по умолчанию',
  location_name VARCHAR(32) NOT NULL COMMENT 'имя локации',
  location_desc text NOT NULL COMMENT 'описание локации',
  owner_sector_id INT UNSIGNED NULL COMMENT 'игрок - владелец локации')
COMMENT = 'локации'";
$queries[]="
create table if not exists inventories (
  inventory_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  inventory_type TINYINT NOT NULL COMMENT 'тип инвентаря - от него зависит в какой тип слота попадет и когда применять',
  inventory_name VARCHAR(64) COMMENT 'уникальное название для некоторого инвентаря - например для артефакта',
  inventory_slot TINYINT UNSIGNED COMMENT 'Номер слота. если нулл то в трюме',
  inventory_icon VARCHAR(64) COMMENT 'картинка инвентаря',
  inventory_quality_level TINYINT UNSIGNED NOT NULL COMMENT 'уровень износа. пока всегда будет 1 - без износа',
  owner_ship_id INT UNSIGNED NOT NULL COMMENT 'какому кораблю принадлежит'
)
COMMENT = 'инвентарь'";
$queries[]="
create table if not exists chat_messages (
  message_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  message_target_type TINYINT UNSIGNED COMMENT 'тип отправки - всем, фракции, конкретному игроку' ,
  message_target_id INT UNSIGNED COMMENT 'того кому отправлено' ,
  message_text TEXT NOT NULL COMMENT 'текст сообщения',
  message_timestamp INT UNSIGNED NOT NULL COMMENT 'когда отправлено',
  message_author_id INT UNSIGNED COMMENT 'игрок - автор сообщения. null - сообщение от системы')
COMMENT = 'сообщения в чат'";
$queries[]="
create table if not exists posts (
  post_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  created_data DATETIME NOT NULL ,
  author_user_id INT UNSIGNED NOT NULL ,
  post_text TEXT NOT NULL ,
  post_parrent INT UNSIGNED NULL COMMENT 'айди родителя... если null то это топик')
COMMENT = 'посты в форум'";
$queries[]="
create table if not exists news (
  new_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  created_data DATETIME NOT NULL ,
  new_caption VARCHAR(64) NOT NULL ,
  new_text TINYINT NOT NULL ,
  new_author_id INT UNSIGNED NOT NULL)
COMMENT = 'новостная лента'";
$queries[]="
create table if not exists pictures (
  picture_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  created_data DATETIME NOT NULL ,
  picture_comment TEXT NULL ,
  picture_user_id INT UNSIGNED NOT NULL )
COMMENT = 'картинки для галереи'";
$queries[]="
create table if not exists encyc_chapters (
  encyc_chapter_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  topic varchar(64) NOT NULL,
  chapter varchar(64) NOT NULL)
  COMMENT = 'перечень глав'";
$queries[]="
create table if not exists encyc_pages (
  encyc_page_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  encyc_chapter_id INT UNSIGNED NOT NULL,
  encyc_page_num TINYINT unsigned,
  encyc_article TEXT)
  COMMENT = 'перечень страниц статей'";

exec_queries($queries,'creating tables');
//$n=0;
//foreach ( $queries as $query) {
//    exec_query($query);
//    $n++;
//}
//echo "<p>".$n." tables has been created successfully</p>";


//locations
$filldata=array();
$filldata[]="insert into locations values
(1,3,400,400,'GasStation-icon.png',null,'Ремонтная мастерская, заправочный пункт.','Производит все виды заправки. В наличии качественное урановое и водяное топливо. ремонт на уровне замены блоков',10)";
$filldata[]="insert into locations values
(2,2,200,40,'Entry_Office_Building.png',null,'Рынок.','Продажа-покупка',10)";
$filldata[]="insert into locations values
(3,4,80,440,'Pharmacy-icon.png',null,'Центр получения информации.','Здесь можно узнать точную информацию, нанять шпионов, продать информацию',10)";
$filldata[]="insert into locations values
(4,2,10,40,'defense1.png',null,'Локация Мастерская мечей','Здесь можно заказать меч',12)";
$filldata[]="insert into locations values
(5,3,200,200,'Rojo.png',null,'Столица Амазонок','Добро пожаловать в город женщин',12)";

exec_queries($filldata,'locations');



$filldata=array();
//sectors
$filldata[]="insert into sectors values
(9,2,400,280,'Pluto.png','sector_background_01.png','Сектор Плутония','Небольшой астероид с развитой добычей плутония',5)";
$filldata[]="insert into sectors values
(11,1,40,400,'Moon.png','sector_background_02.png','Сектор Личи','Лич - планета средних размеров. населена немногочисленными туземцами',5)";
$filldata[]="insert into sectors values
(10,3,200,80,'Mercury.png','sector_background_03.png','Сектор Ящера','Космическая станция клана ящеров',5)";
$filldata[]="insert into sectors values
(12,1,120,320,'corbeile pleine .png','sector_background_04.png','Сектор Прото Лим','Планета амазонок. Делают мечи на заказ',4)";
$filldata[]="insert into sectors values
(13,1,520,320,'mine_field.png','sector_background_05.png','Сектор Мин','Весь сектор - сплошное минное поле',4)";
$filldata[]="insert into sectors values
(120,1,0,0,'sexfoto3_20070812_1774253573.jpg','sector_background_120.png','Сектор несуществующей системы','Сектор для проверки работы. Имеет сверх большой (не существующий) айди системы',20000)";
exec_queries($filldata,'sectors');

$filldata=array();
//systems
$filldata[]="insert into systems values
(1,100,100,'Mars.png','system_background_01.png',128,'Медузы','Населена медузами - заманивают и съедают заживо',0)";
$filldata[]="insert into systems values
(3,600,250,'Earth.png','system_background_02.png',128,'Громкого эха','Содержит уникальный рельеф способствующий возникновению уникального акустического эффекта',0)";
$filldata[]="insert into systems values
(4,50,300,'Anubis_128.png','system_background_03.png',128,'Система прото красавицы','С незапамятных времен отважные путешественники пропадали едва ступив (ногой) на поверхность малого спутника главной планеты системы',0)";
$filldata[]="insert into systems values
(5,250,10,'Pyramid.png','system_background_04.png',70,'Система Пирамиды','Конечно же тут не обошлось без пришельцев которые когда то построили пирамиды на планете земля',0)";
$filldata[]="insert into systems values
(6,400,400,'Space Pod Betty.png','system_background_05.png',100,'Механизированные диверсанты.','Неслышно крадутся гремя своими металлическими потрохами к складам противника в надежде стырить немного машинного масла для поддержания своего вида',0)";
$filldata[]="insert into systems values
(7,250,300,'music.png','system_background_06.png',48,'Система грамофон ','Все на планете граммофона любят слушать музыку. Причом громко.',0)";
exec_queries($filldata,'systems');

$filldata=array();
//users
$filldata[]="insert into users values
(1,1,1,'gymlyg@rambler.ru','test_avatar.jpg','93b765a316ae6e81a49ceec5185cc2e8',126854855423031,1,1)";
$filldata[]="insert into users values
(4,'notromus','jabadabadoo','notromus@yandex.ru','test_avatar.jpg','99b25f5c9dd45a20bc7b5a7e3c3a2872',126840199274305,null,1)";
$filldata[]="insert into users values
(5,'Teran','354045','Topun@Inbox.ru','test_avatar.jpg','29fac289e3e674bd0f5e5c7e6435903e',126840221075718,null,1)";
exec_queries($filldata,'users');

// inventories
$filldata=array();
$filldata[]="insert into inventories values
(1,1,'самонаводящаяся ракета средней дальности',1,'rocket1.png',100,1)";
$filldata[]="insert into inventories values
(2,2,'термоустойчивое защитное поле',2,'defense1.png',80,1)";
$filldata[]="insert into inventories values
(3,10,'гусеничный трактор',1,'tractor.png',50,1)";
exec_queries($filldata,'inventories');

/////////////
// persons
$filldata=array();
$filldata[]="insert into persons values
(NULL,'katom',1,9,1,'guest.png','родился на каракуте, учился в армкиторской средней академии...',0,0,0,0,1)";
exec_queries($filldata,'persons');


///////////
// ships
$filldata=array();
$filldata[]="insert into ships values
(NULL,'Hercules',200,400,0,0,0,0,0,0,0,0,1,1)";
$filldata[]="insert into ships values
(NULL,'DemoShip',500,100,0,0,0,0,0,0,0,0,0,2)";



exec_queries($filldata,'ships');

disconnect();
echo "<p>db has been configured successfully</p>";
?>
