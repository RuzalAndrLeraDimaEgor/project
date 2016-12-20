<?php
include ('config.php'); 
$session_id = mysql_real_escape_string($_GET['session_id']);
$check  = mysql_fetch_array(mysql_query("select role from sessions inner join users_info using (user_id) where session_id = '$session_id'"));
if ($check['role'] == 'employee')
{
	$add = mysql_real_escape_string($_GET['add']);
	//для формы add_dish
	if ($add == 'dish')
	{
		$dish_name = mysql_real_escape_string($_GET['dish_name']);
		$volume = mysql_real_escape_string($_GET['volume']);
		$name_cafe = mysql_real_escape_string($_GET['name']);
		$price = mysql_real_escape_string($_GET['price']);
		$select_dishes = mysql_query("select * from dishes where dish_name = '$dish_name' and volume='$volume'");
		if (mysql_num_rows($select_dishes) == 0)
		{
			$select_cafe = mysql_query("select name_cafe from all_cafe where name_cafe = '$name_cafe'");
			if (mysql_num_rows($select_cafe) != 0)
			{
				$select_uid = mysql_fetch_array(mysql_query("select uuid() "));
				$uuid = $select_uid['uuid()'];
				$insert_into_dish = mysql_query("insert into dishes (dish_name,volume) values ('$dish_name', '$volume')");
				$select_new_dish = mysql_fetch_array(mysql_query("select dish_id from dishes where dish_name='$dish_name' and volume='$volume'"));
				$dish_id = $select_new_dish['dish_id'];
				$insert_into_price = mysql_query("insert into price (dish_id, cafe_id, price) values ('$dish_id', (select cafe_id from all_cafe where name_cafe='$name_cafe'), '$price')");
				error_answer('success');
			}
			else{
				error_answer('notcafe');
			}
		}
		else{
			error_answer('exist_dish');
		};
	}
	
	//для формы add_price
	if ($add == 'price')
	{
		$dish_name = mysql_real_escape_string($_GET['dish_name']);
		$volume = mysql_real_escape_string($_GET['volume']);
		$name_cafe = mysql_real_escape_string($_GET['restname']);
		$price = mysql_real_escape_string($_GET['price']);
		$select_check = mysql_query("select * from price inner join all_cafe using (cafe_id) inner join dishes using (dish_id) where dish_name = '$dish_name' and name_cafe='$name_cafe' and volume='$volume'");
		if (mysql_num_rows($select_check) == 0)
		{
			$insert_price = mysql_query("insert into price (cafe_id,dish_id,price) values ((select cafe_id from all_cafe where name_cafe='$name_cafe'),(select dish_id from dishes where dish_name='$dish_name' and volume='$volume'),'$price')");
			error_answer('success');
		}
		else { error_answer('exist');}
	}
}
else{
	error_answer('Access is denied');
}
?>