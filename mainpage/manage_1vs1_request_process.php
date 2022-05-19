<?php

include "../include/db_connection.php";
include "../include/session.php";

$user_id = $_SESSION['gobeauty_user_id'];
$type = $_REQUEST["type"];
$email = $_REQUEST["email"];
$title = $_REQUEST["title"];
$body = $_REQUEST["body"];

//echo $type.$email.$title.$body;
$uuid_sql = "select uuid() as uuid;";
$uuid_result = mysql_query($uuid_sql);
if ($uuid_datas = mysql_fetch_object($uuid_result)) {
	$uuid = $uuid_datas->uuid;

	$sql = "insert into tb_1vs1_pna (id,customer_id,email,title,request_main_type,request_sub_type,body,update_time) values ('".$uuid."','".$user_id."','".$email."','".$title."','".$type."','','".$body."',now());";
	$result = mysql_query($sql);
	if ($result) {
		echo "접수 되었습니다.";
	} else {
		echo "접수에 실패하였습니다. 관리자에게 문의해주세요.";
	}
}

closeDB();

?>
