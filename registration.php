<?php
include ('config.php'); 
$email = mysql_real_escape_string($_GET['email']); 
$password = md5(mysql_real_escape_string($_GET['password']));
$check=mysql_query("SELECT * FROM users WHERE email='$email'");  
$counts=mysql_num_rows($check);
if($counts==0){
	$password = mysql_real_escape_string($_GET['password']);
	$address = mysql_real_escape_string($_GET['address']);
	$phone=mysql_real_escape_string( $_GET['phone']);
	$fio = mysql_real_escape_string($_GET['fio']);
	$role = "user";
	$sql = mysql_fetch_array(mysql_query("select uuid() "));
	$uuid = $sql['uuid()'];
	$document_get = mysql_query("insert into users (user_id,email,password,date_reg) values ('$uuid','$email',md5('$password'),now())");
	$insert_into_users_info = mysql_query("insert into users_info (user_id, name,address,phone,role,total) values ('$uuid','$fio','$address','$phone','user',0)");
	error_answer('success');
}
else {
	error_answer('error');
};
?>