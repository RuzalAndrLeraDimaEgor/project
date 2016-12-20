<?php
include ('config.php'); 
//экранируем полученные данные 
$search = mysql_real_escape_string($_GET['search']);
//в зависимости от действия входим в нужную часть 
//для формы menu
if ($search == 'menu')
{
	//делаем запрос
	$select_for_menu = mysql_query("select dish_name as dname, volume as cap from dishes");
	//если количество полученных строк запроса > 0, то вернем результат запроса
	if (mysql_num_rows($select_for_menu) > 0){
		json_answer($select_for_menu);
	}
	else{
		error_answer('error');
	};
}
//для формы search_dish
if ($search == 'dish')
{
	$dish_name = mysql_real_escape_string($_GET['dish_name']);
	$select_dishes = mysql_query("select dish_name as dname, price, address, name_cafe as restname, volume as cap from dishes inner join price using (dish_id) inner join all_cafe using (cafe_id) where dish_name like '%$dish_name%'");
	if (mysql_num_rows($select_dishes) > 0){
		json_answer($select_dishes);
	}
	else{
		error_answer('error');
	};
}
//для формы price
if ($search == 'price')
{
	$dish_name = mysql_real_escape_string($_GET['dish_name']);
	if ($dish_name == 'all')
	{
		$select_all = mysql_query("select dish_name as dname, volume as cap, name_cafe as restname, price from price inner join dishes using (dish_id) inner join all_cafe using (cafe_id) ");
		if (mysql_num_rows($select_all) > 0){
			json_answer($select_all);
		}
		else{
			error_answer('null');
		}
	}
	else
	{
		$select_dish = mysql_query("select dish_name as dname, volume as cap, name_cafe as restname, price from price inner join dishes using (dish_id) inner join all_cafe using (cafe_id) where dish_name='$dish_name'");
		if (mysql_num_rows($select_dish) > 0){
			json_answer($select_dish);
		}
		else{
			error_answer('null');
		}
	}
	
	$select_price = mysql_query("select  price, address, name_cafe as restname from dishes inner join price using (dish_id) inner join all_cafe using (cafe_id) where dish_name='$dish_name'");
	if (mysql_num_rows($select_price) > 0){
		json_answer($select_price);
	}
	else{
		error_answer('error');
	};
}

//для формы order
if ($search == 'all_dish')
{
	$select_dishes = mysql_query("select dish_name as dname, volume as cap,name_cafe as restname from dishes inner join price using (dish_id) inner join all_cafe using (cafe_id) ");
	if (mysql_num_rows($select_dishes) > 0 ){
		json_answer($select_dishes);
	}
	else{
		error_answer('error');
	}
}
//для формы order
if ($search == 'all_rest')
{
	$select_rest= mysql_query("select name_cafe as restname from  price inner join all_cafe using (cafe_id) group by name_cafe");
	if (mysql_num_rows($select_rest) > 0 ){
		json_answer($select_rest);
	}
	else{
		error_answer('error');
	}
}
//для формы add_price
if ($search == 'dishes')
{
	$select_dishes = mysql_query("select dish_name as dname, volume as cap from dishes");
	if (mysql_num_rows($select_dishes) > 0){
		json_answer($select_dishes);
	}
	else{
		error_answer('null');
	}
}

//для формы add_price
if ($search == 'rest')
{
	$name_cafe = mysql_real_escape_string($_GET['restname']);
	$select_rest = mysql_query("select name_cafe  from all_cafe where name_cafe = '$name_cafe'");
	if (mysql_num_rows($select_rest) > 0){
		error_answer('exist');
	}
	else{
		error_answer('null');
	}
}
//для формы change_data
if ($search == 'info')
{
	$session_id=mysql_real_escape_string($_GET['session_id']);
	$check  = mysql_fetch_array(mysql_query("select role,user_id from sessions inner join users_info using (user_id) where session_id = '$session_id'"));
	if ($check['role'] == 'employee' or $check['role'] == 'user')
	{
		$user_id = $check['user_id'];
		$select_data = mysql_query("select name as fio,address,phone,card,about as info from users_info where user_id = '$user_id'");
		json_answer($select_data);
	}
	else{
		error_answer('Access is denied');
	}
}

//для формы статистика
if ($search == 'stat')
{
	$session_id = mysql_real_escape_string($_GET['session_id']);
	$check  = mysql_fetch_array(mysql_query("select role,user_id from sessions inner join users_info using (user_id) where session_id = '$session_id'"));
	if ($check['role'] == 'employee')
	{
		$select_stat = mysql_query("select count(order_id) as count, name_cafe as restname,sum(total) as summa from orders inner join all_cafe using (cafe_id) group by name_cafe order by count desc ;  ");
		if (mysql_num_rows($select_stat) > 0){
				json_answer($select_stat);
		}
		else{
			error_answer('null');
		}
	}
	else{
		error_answer('Access is denied');
	}
}

?>