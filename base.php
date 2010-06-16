<?php
function gen_debug($_data,$level=1) {
    global $debug;
    $color="";
    $_out="";
    if($debug>=$level) {
            switch($level) {
                case 1:
                    $color="style=\"color: white\"";
                    break;
                case 2:
                    $color="style=\"color: yellow\"";
                    break;
                case 3:
                    $color="style=\"color: orange\"";
                    break;
                case 4:
                    $color="style=\"color: lime\"";
                    break;
                case 5:
                default:
                    $color="style=\"color: grey\"";
            }
            $_out .= gen_mess('aas','debug',"<p ".$color.">".$level.":".$_data."</p>");
            echo $_out;
    }
    return $_out;
}

function ob_tagRemover($text) {
    return htmlentities($text,ENT_NOQUOTES,'UTF-8');
}

function cmd_from_view(&$data, $view_name, $_cmd, $_param) {
    $view_path="views/".$view_name.".php";
    $out = "";
    if( @file_exists($view_path)) {
        echo "<m><c>".$_cmd."</c><p>".$_param."</p><d>";
        ob_start("ob_tagRemover");
        include_once($view_path);
        ob_end_flush();
        echo "</d></m>";
    }  else gen_debug("view ".$view_path." has not been found");

    /*if( @file_exists($view_path)) {
        $f=@fopen($view_path,"r") or gen_debug("view ".$_data, $view_path." has not been opened");
        while(!feof($f)) {
            $out .= fgets($f,1024);
        }
	} else gen_debug("view ".$_data, $view_path." has not been found");*/
}

function gen_mess($_cmd, $_param, $_data) {
    return "<m><c>".$_cmd."</c><p>".$_param."</p><d>".htmlentities($_data,ENT_NOQUOTES,'UTF-8')."</d></m>";
}

function gen_multi_mess($_cmd, $_param, $_data) {
    return "<m><c>".$_cmd."</c><p>".$_param."</p><d type='multi'>".$_data."</d></m>";
}

function notez($message) {
     return "<p class='notez'>".$message."</p>";
}

function gettime()
{
    list($usec, $sec) = explode(" ", microtime());
    return $sec;
}

function getmicrotime()
{
    list($usec, $sec) = explode(" ", microtime());
    return $sec.substr($usec,2,5);
}

function set_inc_get(&$data, $name,$name_list=NULL) {
    if (isset($_GET[$name])) {
        $data[$name] = $_GET[$name];
        if($name_list==NULL) {
            return true;
        } else {
            return in_array($data[$name],$name_list);
        }
    }
    return false;
}


///// db ///////////
$link=NULL;
$site='no';

$db_host="zorro";
$db_name='db_agame';
$db_user="user_agame";
$db_password="user_agame";

$db_root_user="root";
$db_root_password="3mk8a9";

if($site=='yes') {
  $db_host="sql111.byethost13.com";
  $db_name='b13_3935334_db_agame';
  $db_user="b13_3935334";
  $db_password="G4meH0sT";

  $db_root_user=$db_user;
  $db_root_password=$db_password;
}

function connect($_host, $_name ,$_user, $_password)
{
	global $link;
	$link = mysql_connect($_host,$_user,$_password);
	if ($link && mysql_select_db($_name)) {
            return true;
	}
        gen_debug('connect subroutine - bad connect or db selecting',2);
	return false;
}

function disconnect()
{
	global $link;
	mysql_close($link);
	$link = NULL;
}

function mysql_update(&$_query) {
    return (mysql_query($_query)==true && mysql_affected_rows()>0);
}

function mysql_add(&$_query) {
    return (mysql_query($_query) && mysql_affected_rows());
}

function mysql_ask(&$_data,$_query) {
    $mq = mysql_query($_query);
    $amount=0;
    if($mq) {
        while($assoc=mysql_fetch_assoc($mq)) {
            $_data[]=$assoc;
            $amount++;
        }
    }
    gen_debug('mysql_ask-amount:'.$amount,5);
    return ($amount>0)?true:false;
}

function mysql_del(&$_query) {
    return (mysql_query($_query) && mysql_affected_rows());
}

?>
