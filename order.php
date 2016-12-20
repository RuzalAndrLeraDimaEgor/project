<?php
include ('config.php'); 
$session_id = mysql_real_escape_string($_GET['session_id']);
$select_session = mysql_query("select session_id, user_id,role from sessions inner join users_info using (user_id) where session_id = '$session_id'");
if (mysql_num_rows($select_session) != 0)
{
	$result = mysql_fetch_array($select_session);
	$select = mysql_real_escape_string($_GET['select']);
	
	if ($result['role'] != 'user')
	{
		//all_order - получаем заказы всех пользователей
		if ($select == 'all_orders')
		{
			$select_all = mysql_query("select name as fio, name_cafe as restname,orders.total as summa ,date from orders inner join users_info using (user_id) inner join all_cafe using (cafe_id)");
			if (mysql_num_rows($select_all) > 0){
				json_answer($select_all);
			}
			else{
				error_answer('null');
			}
		}
	}
	//получаем заказы только одного пользователя
	if ($select == 'order_user')
	{
		$user_id = $result['user_id'];
		$select_order_user = mysql_query("select order_id, name_cafe as restname, orders.total as summa, users_info.total as user_summa, date from orders inner join all_cafe using (cafe_id) inner join users_info using (user_id) where user_id = '$user_id' order by date desc");
		if (mysql_num_rows($select_order_user) > 0){
			json_answer($select_order_user);
		}
		else{
			error_answer('null');
		}
	}
	//получаем содержимое заказа
	if ($select == 'order_content')
	{
		$order_id = mysql_real_escape_string($_GET['order_id']);
		$select_content = mysql_query("select dish_name as dname,count,price from order_content inner join dishes using (dish_id) where order_id='$order_id'");
		json_answer($select_content);
	}
	
	//для формы order
	if ($select == 'get_price')
	{
		$name_cafe=mysql_real_escape_string($_GET['restname']);
		$dish_name = mysql_real_escape_string($_GET['dish_name']);
		$volume = mysql_real_escape_string($_GET['volume']);
		$select_price = mysql_query("select price from price inner join all_cafe using (cafe_id) inner join dishes using (dish_id) where dish_name='$dish_name' and volume='$volume' and name_cafe='$name_cafe'");
		json_answer($select_price);
	}
	
	//для формы order
	if ($select == 'make_order')
	{
		$name_cafe=mysql_real_escape_string($_GET['restname']);
		$dish_name = mysql_real_escape_string($_GET['dish_name']);
		$volume = mysql_real_escape_string($_GET['volume']);
		$count = mysql_real_escape_string($_GET['count']);
		$card = mysql_real_escape_string($_GET['card']);
		$select_price = mysql_query("select price from price inner join all_cafe using (cafe_id) inner join dishes using (dish_id) where dish_name='$dish_name' and volume='$volume' and name_cafe='$name_cafe'");
		$res_price = mysql_fetch_array($select_price);
		$price = $res_price['price'];
		$total = $count*$price;
		$flag = mysql_real_escape_string($_GET['flag']);
		//если за сессию был уже сделан заказ, то получаем id заказа и используем его для добавления в табл order_content
		if ($flag=='with')
		{	
			$order_id = mysql_real_escape_string($_GET['order_id']);
			$insert_content = mysql_query("insert into order_content (order_id, dish_id,count,price) values ('$order_id', (select dish_id from dishes where dish_name='$dish_name' and volume='$volume'),'$count','$total')");
			print mysql_error();
			error_answer('success');
		}
		//если заказ за сессию еще не сделан, необходимо добавить его в таблицу orders и получить id заказа для всех остальных заказов в данную сессию
		if ($flag=='without')
		{	
			$sql1 = mysql_query("select uuid() as uuid ");
			$sql = mysql_fetch_array($sql1);
			$uuid = $sql['uuid'];
			$user_id=$result['user_id'];
			$insert_order = mysql_query("insert into orders (order_id,cafe_id,user_id,total,date,card) values ('$uuid',(select cafe_id from all_cafe where name_cafe='$name_cafe'),'$user_id',0,now(),'$card') ");
			$insert_content = mysql_query("insert into order_content (order_id, dish_id,count,price) values ('$uuid', (select dish_id from dishes where dish_name='$dish_name' and volume='$volume'),'$count','$total')");
			//print mysql_error();
			$res = (array(  'error'=> "success"));
			$result1 = 	(array(
			   'uuid()' => $uuid,
			));
			print_answer($res,array($result1));
		}
	}
	
}
else{
	error_answer('Access is denied');
}


?>