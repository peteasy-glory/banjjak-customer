<?php
include "../include/top.php";
include "../include/Crypto.class.php";

// 신규 가입
$email_id = $_REQUEST['email_id'];
$cellphone = $_REQUEST['cellphone'];

$id_array = array(trim($email_id));
$phone_array = array(trim($cellphone));

$crypto = new Crypto();

for ($i = 0 ; $i < sizeof($id_array) ; $i = $i + 1) {

	$email_id = $id_array[$i];
	$nickname = $email_id;
	$random_number = sprintf("%06d",rand(000000,999999));
	$id_pos = strpos($email_id, "@");
	if ($id_pos > 5) {
        	$nickname = substr($email_id, 0, $id_pos-3)."_".$random_number;
	} else {
        	$nickname = substr($email_id, 0, $id_pos)."_".$random_number;
	}

	$enc_phone = $crypto->encode($phone_array[$i], $access_key, $secret_key);

	$password = make_password_hash("go!".$phone_array[$i]);
	$sql = "insert into tb_customer (id,password,cellphone,cellphone_confirm,bill_flag,nickname,last_login_time,registration_time,enable_flag,push_option,my_shop_flag) values ('".$id_array[$i]."','".$password."','".$enc_phone."',1,0,'".$nickname."',now(),now(),1,1,0);";
	echo $sql."<br>";
	$result = mysql_query($sql);
}

include "../include/bottom.php";
?>

<script>
	$.MessageBox({
                    buttonDone      : "확인",
                    message         : "완료"
                }).done(function(){
	location.href = 'manage_new_customer.php';
                });
</script>
