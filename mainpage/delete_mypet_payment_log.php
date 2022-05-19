<?php
include "../include/configure.php";
include "../include/db_connection.php";

$user_id = $_REQUEST['customer_id'];
$pet_seq = $_REQUEST['pet_seq'];



if($user_id != "" && $mypet_seq != ""){
	$sql = "
		UPDATE tb_payment_log SET is_cancel = '1'
		WHERE customer_id = '".$user_id."'
		AND pet_seq = '".$pet_seq."'
		AND DATE_FORMAT(CONCAT(year,'-', month,'-', day,' ', hour,':', minute), '%Y-%m-%d %H:%i') > NOW();
	";
	$result = mysql_query($sql);

}

?>
