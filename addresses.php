<?php
include ('config.php'); 
$select_addresses = mysql_query("select  address, name_cafe as restname from all_cafe");
if (mysql_num_rows($select_addresses) > 0){
	json_answer($select_addresses);
}
else{
	error_answer('error');
};
?>