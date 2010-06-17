<?php session_start();?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>config</title>
    <style type="text/css">
        body {
        }
        table {
        font-family: Arial, Helvetica, sans-serif;
        border-spacing: 4px;
        }
        td {
        font-family: Arial, Helvetica, sans-serif;
        
        font-size: 12pt;
        background-color: #e8ffe8;
        padding: 4px;
        margin: 2px;
        border: 1px solid #00aa00;
        -moz-border-radius: 4px;
        }
    </style>
    </head>
<body style="font-size: 12pt;">

<form enctype="multipart/form-data" method="post" >
 <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
 <input type='hidden' name='act' value='file'/>
 загрузить рисунок на сервак: <input name="userfile" type="file" />
 <input type="submit" value="Send Pic"/>
 <a href="../images/" target="_blank">смореть галерею</a>
</form>
  

<?php

if( @file_exists( "../base_model.php")) {
    include_once "../base_model.php";
} else gen_debug('base_model.php fails',2);

/*
function connect($_host,$_user,$_password)
{
	global $link;
 	$link = mysql_connect($_host,$_user,$_password);
	if ($link){
        	return true;
	}
	return false;
}

function disconnect()
{
	global $link;
	mysql_close($link);
	$link = NULL;
}
*/

function exec_query(&$_query){
    $out=mysql_query($_query);
    if(!$out) {
        echo "<p>db configuration error</p>";
        exit (mysql_error());
    }
    return $out;
}

function verify_post(&$data){
    foreach ($data['check_list'] as $cur_par) {
        if(isset($_POST[$cur_par]) &&  $_POST[$cur_par]!="" ) {
            $data[$cur_par]=$_POST[$cur_par];
        } else return false;
    }
    return true;
}

$link=NULL;

if(!connect($db_host,$db_name,$db_user,$db_password)) {
    echo "<p>db connect error</p>";
    exit (mysql_error());
}

if(!mysql_select_db($db_name,$link)) {
    echo "<p>db select error</p>";
    exit (mysql_error());
}

$data=array(
    'check_list'=>array('mode','action'),
    'sel_table'=>'',
    'sel_table_desc'=>array()
);



if(isset($_POST['tables_list']) && $_POST['tables_list']!="" ) {
    $data['sel_table']=$_POST['tables_list'];
}

if(isset($_POST['act']) && $_POST['act']!="" ) {
    $data['act']=$_POST['act'];
}


if ($data['act']=="file") {
    $uploaddir = '../images/';
    $uploadfile = $uploaddir.basename($_FILES['userfile']['name']);
    echo "<h1>".$uploadfile."</h1>";
    echo "<pre>";
    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
        echo "<p>File is valid, and was successfully uploaded. Here's some more debugging info:</p>";
        print_r($_FILES);
    } else {
        echo "<p>Possible file upload attack!  Here's some debugging info:</p>";
        print_r($_FILES);
    }
    echo "</pre>";
}

$_POST['act']='select';

echo "<form method='POST'>";
echo "<input type='hidden' name='act' value='select'/>";
$query="show tables";
$t_res=exec_query($query);

if($t_res) {
  echo "<select name='tables_list' id='tables_list' onchange=\"this.form.act.value='select'; this.form.submit()\">";
  while(list($table_name)=mysql_fetch_row($t_res)) {

    if( $data['sel_table']== $table_name) {
        $selected="selected";
    } else {
        $selected="";
    }

    echo "<option value=".$table_name." ".$selected.">".$table_name."</option>";
  }
  //echo "<input type='submit' value='send' />";
  echo "</select>";
}

if($data['sel_table']!="" ) {
    $remove_field="";
    $query="describe ".$data['sel_table'];
    $t_res=exec_query($query);
    if($t_res) {
        while(list($par1,$par2,$par3,$par4,$par5)=mysql_fetch_row($t_res)) {
            $data['sel_table_desc'][$par1]=array($par2,$par3,$par4);
            if($data['remove_field']=="") {
                $data['remove_field']=$par1;
            }
        }
//        echo "<p>remove field:".$data['remove_field']."</p>";
        switch( $data['act'] ) {
        case 'add':
            $val_list="";
            foreach ($data['sel_table_desc'] as $k=>$v) {
                if ( isset($_POST[$k]) ) {
                    if( strpos($v[0],"text")!==false || strpos($v[0],"char")!==false) {
                        $val_list .= "'".$_POST[$k]."',";
                    } else {
                        $val_list .= $_POST[$k].",";
                    }
                }
            }
            $val_list=substr_replace($val_list,"",-1);
            $query="insert into ".$data['sel_table']." values (".$val_list.")";
 //           echo "<h3>".$query."</h3>";
            $t_res=exec_query($query);
            if($t_res) {
                echo "<h3>adding done:".mysql_affected_rows()."</h3>";
            } else {
                echo "<h3>adding fails</h3>";
            }

            break;
        case 'remove':
            if( isset($_POST[$data['remove_field']]) && $_POST[$data['remove_field']]!="" ) {
                $query="delete from ".$data['sel_table']." where ".$data['remove_field']."=".$_POST[$data['remove_field']];
 //               echo "<h3>".$query."</h3>";
                $t_res=exec_query($query);
                if($t_res) {
                    echo "<h3>removing done:".mysql_affected_rows()."</h3>";
                } else {
                    echo "<h3>removing fails</h3>";
                }
            }
            break;
        default:
            break;
        }
    }

    $query="select * from ".$_POST['tables_list'];//." limit 10";
    $t_res=exec_query($query);
    if($t_res) {
        $input_line="";
        
        echo "<table><tr>";
        foreach ($data['sel_table_desc'] as $k=>$v) {
            echo "<td>".$k."<br>".$v[0]."<br>NULL:".$v[1]."</td>";
            $input_line .= "<td><input type='text' name='".$k."' value='' size='8'></td>";
        }
        $input_line .= "<td><input type='button' value='addnew' onclick=\"this.form.act.value='add'; this.form.submit();\" /></td>";

        echo "<td>remove flag</td>";
        echo "</tr>";

        while($line=mysql_fetch_row($t_res)) {
            echo "<tr>";
            foreach($line as $mini_line) {
                if($mini_line!="") {
                    echo "<td>".$mini_line."</td>";
                } else {
                    echo "<td>&nbsp;</td>";
                }
            }
            echo "<td><input type='button' value='delete' onclick=\"this.form.act.value='remove'; this.form.".$data['remove_field'].".value='".$line[0]."'; this.form.submit();\" /></td>";
            echo "</tr>";
        }

        echo "<tr>".$input_line."</tr>";
        echo "</table>";
    }

}
/*

echo "<p>POST DATA</p>";
foreach ($_POST as $k=>$v) {
    echo "<p>".$k.", ".$v."</p>";
}

echo "<p>GET DATA</p>";
foreach ($_GET as $k=>$v) {
    echo "<p>".$k.", ".$v."</p>";
}
 */
echo "</form>";
disconnect();

?>
</body></html>