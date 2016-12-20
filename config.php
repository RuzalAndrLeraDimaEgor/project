<?php
define("DB_SERVER", "localhost"); 
define("DB_USERNAME", "root"); 
define("DB_PASSWORD", "");
define("DB_DATABASE", "cafe"); 
$connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
$database = mysql_select_db(DB_DATABASE) or die(mysql_error());

//здесь формируются ответ от сервера в виде преобразованных  полученных строк запроса в json
function json_answer($data){
	while ($row = mysql_fetch_assoc($data)){
			$json[]=$row;
	}
	print json_encode(array(
        'type' => array((array(  'error'=> "success"))),
        'data' =>  $json));
    exit();
}

function error_answer($error){
	$result = (array(  'error'=> $error));
	print json_encode(array(
        'type' => array((array(  'error'=> $error)))));
    exit();
}

function print_answer($type, $data) {
    print json_encode(array(
        'type' => array($type),
        'data' =>  $data));
    exit();
}
?>