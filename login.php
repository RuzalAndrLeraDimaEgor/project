<?php
include ('config.php'); 
//получаем данные с паролем и логином
$email = mysql_real_escape_string($_GET['email']); 
$password = md5(mysql_real_escape_string($_GET['password']));
$sql="SELECT * FROM users WHERE email='$email' and password='$password'";  
$result=mysql_query($sql); 
$counts=mysql_num_rows($result);
if($counts==1){
	$match_value = mysql_fetch_array($result);
	$user_id = $match_value['user_id'];
	$insert_session = mysql_query("INSERT INTO sessions (session_id,start_session,user_id) values (uuid(),now(),'$user_id')");
	$select_session = mysql_query("select session_id,role from sessions inner join users_info using (user_id) where user_id = '$user_id'  order by start_session desc limit 1");
	$json = array(mysql_fetch_array($select_session));
	$result = (array(  'error'=> "success"));
	print_answer($result, $json);
}
else {
	error_answer('error');
};

?>