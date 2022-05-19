<?php
include "../include/configure.php";
include "../include/db_connection.php";

$user_id = $_REQUEST['customer_id'];
$mypet_name = $_REQUEST['pet_name'];
$pet_seq = $_REQUEST['pet_seq'];

$old_pet_photo_delete = "false";

if($user_id != "" && $mypet_name != ""){
	$sql = "SELECT * FROM tb_mypet WHERE customer_id = '" . $user_id . "' AND name_for_owner = '" . $mypet_name . "';";
	// error_log('----- $sql : '.$sql);
	$result = mysql_query($sql);
	if ($result_datas = mysql_fetch_object($result)) {
		$old_photo = $upload_static_directory . $result_datas->photo;

		if (@unlink($old_photo)) {
			$old_pet_photo_delete = "true";
		}
	} else {
		echo "Failed to load an existing pet image";
	}

	$s_sql = "DELETE FROM tb_mypet WHERE customer_id = '" . $user_id . "' AND name_for_owner = '" . $mypet_name . "';";
	// error_log('----- $s_sql : '.$s_sql);
	$s_result = mysql_query($s_sql);

	// 해당 펫 payment_log 삭제처리
	$u_sql = "
		UPDATE tb_payment_log SET is_cancel = '1', cancel_time = NOW()
		WHERE customer_id = '".$user_id."'
		AND pet_seq = '".$pet_seq."'
		AND DATE_FORMAT(CONCAT(year,'-', month,'-', day,' ', hour,':', minute), '%Y-%m-%d %H:%i') > NOW();
	";
	$u_result = mysql_query($u_sql);
}

?>
<!-- {
"old_pet_photo_delete":"<?= $old_pet_photo_delete ?>",
"old_pet_photo_delete_sql":"<?= $sql ?>",
"old_photo":"<?= $old_photo ?>",
"user_id":"<?= $user_id ?>",
"mypet_name":"<?= $mypet_name ?>",
"delete_sql":"<?= $s_sql ?>"
} -->