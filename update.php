<?php
include ('config.php'); 
$session_id = mysql_real_escape_string($_GET['session_id']);
$check  = mysql_fetch_array(mysql_query("select role,user_id from sessions inner join users_info using (user_id) where session_id = '$session_id'"));
$update = mysql_real_escape_string($_GET['update']);
//для формы price
if ($update== 'price')
{
	if ($check['role'] == 'employee')
	{
	
		$dish_name = mysql_real_escape_string($_GET['dish_name']);
		$volume = mysql_real_escape_string($_GET['volume']);
		$price = mysql_real_escape_string($_GET['price']);
		$name_cafe = mysql_real_escape_string($_GET['restname']);
		$update_dish_price = mysql_query("update price set price = '$price'  where dish_id= (select dish_id from dishes where dish_name='$dish_name' and volume='$volume') and cafe_id = (select cafe_id from all_cafe where name_cafe='$name_cafe')");
		print mysql_error();
		error_answer('success');
	}
	else{
		error_answer('Access is denied');
	}
}
//для формы change_data
if ($update== 'data')
{
	if ($check['role'] == 'employee' or $check['role']=='user')
	{
		$name= mysql_real_escape_string($_GET['fio']);
		$address= mysql_real_escape_string($_GET['address']);
		$card = mysql_real_escape_string($_GET['card']);
		$phone = mysql_real_escape_string($_GET['phone']);
		$info = mysql_real_escape_string($_GET['about']);
		$user_id = $check['user_id'];
		$update_data = mysql_query("update users_info set name = '$name',address='$address',card='$card',phone='$phone',about='$info' where user_id='$user_id' ");
		error_answer('success');
	}
	else{
		error_answer('Access is denied');
	}
}
?>