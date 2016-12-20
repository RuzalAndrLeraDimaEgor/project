<?php
include ('config.php'); 
$session_id = mysql_real_escape_string($_GET['session_id']);
$check  = mysql_fetch_array(mysql_query("select role from sessions inner join users_info using (user_id) where session_id = '$session_id'"));
if ($check['role'] == 'employee')
{
	$delete = mysql_real_escape_string($_GET['delete']);
	//для формы price
	if ($delete== 'price')
	{
		$dish_name = mysql_real_escape_string($_GET['dish_name']);
		$volume = mysql_real_escape_string($_GET['volume']);
		$name_cafe = mysql_real_escape_string($_GET['restname']);
		$delete_dishes = mysql_query("delete  from price where dish_id= (select dish_id from dishes where dish_name='$dish_name' and volume='$volume') and cafe_id = (select cafe_id from all_cafe where name_cafe='$name_cafe')");
		error_answer('success');
	}
	//для формы delete_dish
	if ($delete== 'dish')
	{
		$dish_name = mysql_real_escape_string($_GET['dish_name']);
		$volume = mysql_real_escape_string($_GET['volume']);
		$delete_dishes = mysql_query("delete  from dishes where dish_name='$dish_name' and volume='$volume'");
		error_answer('success');
	}
}
else{
	error_answer('Access is denied');
}
?>