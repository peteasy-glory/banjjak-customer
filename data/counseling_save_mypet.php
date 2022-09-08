<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$crypto = new Crypto();

$user_id = $_SESSION['gobeauty_user_id'];
$artist_id = $_SESSION['gobeauty_cart_artist_id'] ? $_SESSION['gobeauty_cart_artist_id'] : $_REQUEST['artist_id'];

$before_pet_name = $_REQUEST['before_pet_name'];
$mypet_name = $_REQUEST['pet_name'];
$mypet_name = trim($mypet_name);

$mypet_kind = $_REQUEST['pet_kind'];
$mypet_type = $_REQUEST['mypet_type'];
$mypet_type2 = $_REQUEST['mypet_type2'];
$mypet_year = $_REQUEST['pet_year'];
$mypet_month = $_REQUEST['pet_month'];
$mypet_day = $_REQUEST['pet_day'];
$mypet_gender = $_REQUEST['gender'];
$mypet_neutral = $_REQUEST['neutralize'];

$mypet_weight1 = $_REQUEST['mypet_weight1'];
$mypet_weight2 = $_REQUEST['mypet_weight2'];
$mypet_weight = $mypet_weight1 . "." . $mypet_weight2;

$mypet_beauty_exp = $_REQUEST['beauty_exp'];
$mypet_vaccination = $_REQUEST['vaccination'];
$mypet_bite = $_REQUEST['bite'];

$cellphone = trim($_REQUEST['cellphone']);
$cellphone = preg_replace("/[^0-9]/", "", $cellphone);
$encode_cellphone = $crypto->encode($cellphone, $access_key, $secret_key);

$artist_name = "";

//----- [싫어하는 부위] 체크 목록
$array_check_dt = $_REQUEST['check_dt'];
$cnt_check_dt = sizeof($array_check_dt);
$where_check_dt = "";
for ($i = 0; $i < $cnt_check_dt; $i++) {
    $where_check_dt .= $array_check_dt[$i] . "=1, ";
}

//----- [특이사항] 체크 목록
$array_check_special = $_REQUEST['check_special'];
$cnt_check_special = sizeof($array_check_special);
$where_check_special = "";
for ($i = 0; $i < $cnt_check_special; $i++) {
    $where_check_special .= $array_check_special[$i] . "=1, ";
}

$mypet_luxation = $_REQUEST['luxation'];
$mypet_etc_for_owner = $_REQUEST['etc_for_owner'];
$mypet_etc_for_owner = addslashes($mypet_etc_for_owner);

//----- [원하는 미용] 체크 목록
/*
$array_request_beauty = $_REQUEST['request_beauty'];
$cnt_request_beauty = sizeof($array_request_beauty);
$where_request_beauty = "";
for ($i = 0; $i < $cnt_request_beauty; $i++) {
    $where_request_beauty .= $array_request_beauty[$i] . ",";
}
$where_request_beauty = substr($where_request_beauty, 0, -1);
*/
$mypet_request_beauty = $_REQUEST['request_beauty'];
$mypet_photo_counseling = $_REQUEST['photo_counseling'];

//$flag = $_REQUEST['flag'];

$mypet_seq = 0;

// 20200724 ulmo 이미 신청된 상태에서 추가로 등록되지 않도록 예외처리 추가
// 20200807 ulmo 중복 신청되어 정상 신청건 제외 나머지 갯수 체크로 변경 > approval : "= 0" >> "<> 1"
$diff_12hours_time = strtotime("-12 hours");
$str_update_time = date('Y-m-d H:i', $diff_12hours_time);

// 12시간 이내 상담 완료 및 취소 안된 신청건 여부
$sql = "
	SELECT *
	FROM tb_payment_log
	WHERE customer_id = '".$user_id."'
		AND artist_id = '".$artist_id."'
		AND approval = 0 
		AND product_type = 'B'
		and update_time > '{$str_update_time}'
";
$result = mysqli_query($connection, $sql);
$approval_cnt = mysqli_num_rows($result);

// 미용 취소되었는지
$sql = "
	SELECT *
	FROM tb_payment_log
	WHERE customer_id = '".$user_id."'
		AND artist_id = '".$artist_id."'
		AND approval = 3 
		AND product_type = 'B'
";
$result = mysqli_query($connection, $sql);
$denied_cnt = mysqli_num_rows($result);
if($approval_cnt > 0) {
    echo "already";
}else if($denied_cnt > 0){
    echo "denied";
}else{
	$s_sql = "SELECT * FROM tb_mypet 
	WHERE customer_id = '{$user_id}' 
	AND name_for_owner = '{$before_pet_name}';";
	// error_log('----- $s_sql : ' . $s_sql);

	$s_result = mysqli_query($connection, $s_sql);
	if ($s_rows = mysqli_fetch_object($s_result)) {
		$init_sql =
			"UPDATE tb_mypet SET dt_eye = 0, dt_nose = 0, dt_mouth = 0, dt_ear = 0, dt_neck = 0, dt_body = 0, dt_leg = 0, dt_tail = 0, dt_genitalia = 0, nothing = 0,
					dermatosis = 0, heart_trouble = 0, marking = 0, mounting = 0 
			WHERE customer_id = '{$user_id}' 
			AND name_for_owner = '{$before_pet_name}';";
		$init_result = mysqli_query($connection, $init_sql);

		$sql =
			"UPDATE tb_mypet SET 
				name_for_owner = '{$mypet_name}',
				type = '{$mypet_kind}', 
				pet_type = '{$mypet_type}', 
				pet_type2 = '{$mypet_type2}', 
				year = {$mypet_year}, 
				month = {$mypet_month}, 
				day = {$mypet_day}, 
				gender = '{$mypet_gender}', 
				neutral = '{$mypet_neutral}', 
				weight = '{$mypet_weight}', 
				beauty_exp = '{$mypet_beauty_exp}', 
				vaccination = '{$mypet_vaccination}', 
				bite = '{$mypet_bite}', 
				luxation = '{$mypet_luxation}', 
				{$where_check_dt}
				{$where_check_special}
				etc_for_owner = '{$mypet_etc_for_owner}',
				photo_counseling = '{$mypet_photo_counseling}'
			WHERE customer_id = '{$user_id}' 
			AND name_for_owner = '{$before_pet_name}';";
		$result = mysqli_query($connection, $sql);
		$mypet_seq = intval($s_rows->pet_seq);

		$payment_log_sql =
			"INSERT INTO tb_payment_log(
				pet_seq,
				session_id, 
				customer_id,
				order_id,
				artist_id,
				cellphone,
				etc_memo,
				update_time,
				approval
			) VALUES (
				'{$mypet_seq}',
				'0', 
				'{$user_id}', 
				'0', 
				'{$artist_id}', 
				'{$cellphone}', 
				'{$mypet_request_beauty}', 
				NOW(), 
				'0'
			);";
		$payment_log_result = mysqli_query($connection, $payment_log_sql);
	} else {
		$sql = "INSERT INTO tb_mypet (
					customer_id, 
					name, 
					name_for_owner, 
					type, 
					pet_type, 
					pet_type2, 
					year, 
					month, 
					day, 
					gender, 
					neutral,
					weight, 
					beauty_exp, 
					vaccination, 
					bite, 
					luxation, 
					etc_for_owner,
					photo_counseling, 
					enable_flag
				) values (
					'{$user_id}', 
					'{$mypet_name}', 
					'{$mypet_name}', 
					'{$mypet_kind}', 
					'{$mypet_type}', 
					'{$mypet_type2}', 
					{$mypet_year}, 
					{$mypet_month}, 
					{$mypet_day}, 
					'{$mypet_gender}', 
					'{$mypet_neutral}', 
					'{$mypet_weight}', 
					'{$mypet_beauty_exp}', 
					'{$mypet_vaccination}', 
					'{$mypet_bite}', 
					'{$mypet_luxation}', 
					'{$mypet_etc_for_owner}',
					'{$mypet_photo_counseling}',
					1
				);";
		$result = mysqli_query($connection, $sql);
		$mypet_seq = mysqli_insert_id($connection);

		$after_sql =
			"UPDATE tb_mypet SET 
				{$where_check_dt}
				{$where_check_special}
				etc_for_owner = '{$mypet_etc_for_owner}'
			WHERE pet_seq = '{$mypet_seq}';";
		$after_result = mysqli_query($connection, $after_sql);

		$payment_log_sql =
			"INSERT INTO tb_payment_log(
				pet_seq,
				session_id, 
				customer_id,
				order_id,
				artist_id,
				cellphone,
				etc_memo,
				update_time,
				approval
			) VALUES (
				'{$mypet_seq}',
				'0', 
				'{$user_id}', 
				'0', 
				'{$artist_id}', 
				'{$cellphone}', 
				'{$mypet_request_beauty}', 
				NOW(), 
				'0'
			);";
		$payment_log_result = mysqli_query($connection, $payment_log_sql);
	}
	// error_log('----- $payment_log_sql : ' . $payment_log_sql);
	// error_log('----- $sql : ' . $sql);
	$_SESSION['gobeauty_product_selected_pet'] = $mypet_seq;


	$customer_info_sql = "SELECT * FROM tb_customer WHERE id = '{$user_id}';";
	// error_log('----- $customer_info_sql : ' . $customer_info_sql);
	$customer_info_result = mysqli_query($connection, $customer_info_sql);
	if ($customer_info_rows = mysqli_fetch_object($customer_info_result)) {
		$check_cellphone = $customer_info_rows->cellphone;

		if ($check_cellphone == "" || $check_cellphone == null) {
			$update_customer_cellphone_sql = "UPDATE tb_customer SET cellphone='{$encode_cellphone}' WHERE id='{$user_id}';";
			// error_log('----- $update_customer_cellphone_sql : ' . $update_customer_cellphone_sql);
			$update_customer_cellphone_result = mysqli_query($connection, $update_customer_cellphone_sql);
		}
	}

	$shop_info_sql = "SELECT * FROM tb_shop WHERE customer_id = '{$artist_id}';";
	// error_log('----- $customer_info_sql : ' . $customer_info_sql);
	$shop_info_result = mysqli_query($connection, $shop_info_sql);
	if ($shop_info_rows = mysqli_fetch_object($shop_info_result)) {
		$artist_name = $shop_info_rows->name;
	}

	$cellphone_back_num = substr($cellphone, -4);

	if ($artist_id != null && $artist_id != "") {
		$message = "[".$mypet_name." (".$cellphone_back_num.")]님이 이용 상담을 신청하셨네요. 12시간 이내에 상담을 완료해주세요.";
		$message_admin = "[".$mypet_name." (".$cellphone_back_num.")]님이 펫샵(".$artist_name." | ".$artist_id.")에 이용 상담을 신청.";
		$path = "https://partner.banjjakpet.com/reserve_advice_list_1";
		$path_admin = "https://www.gopet.kr/pet/mainpage/mainpage_my_menu.php";
		$image = "https://customer.banjjakpet.com/static/pub/images/icon/icon-logo.png";
		a_push($artist_id, "반짝, 첫 이용 상담 신청 알림", $message, $path, $image);
		a_push("pickmon@pickmon.com", "반짝, [이용 상담 신청] 관리자 알림", $message_admin, $path_admin, $image);
	}

	echo "OK";
}
?>