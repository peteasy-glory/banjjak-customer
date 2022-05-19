<?php
include "../include/session.php";

include "../include/db_connection.php";
include "../include/php_function.php";

$user_id = $_SESSION['gobeauty_user_id'];

$key = $_POST['key'];

if ($key == 'init') {
	unset($_SESSION['gobeauty_cart_region_top']);
	unset($_SESSION['gobeauty_cart_region_middle']);
	unset($_SESSION['gobeauty_cart_region_bottom']);
	unset($_SESSION['gobeauty_cart_year']);
	unset($_SESSION['gobeauty_cart_month']);
	unset($_SESSION['gobeauty_cart_day']);
        unset($_SESSION['gobeauty_cart_hour']);
        unset($_SESSION['gobeauty_cart_to_hour']);
        unset($_SESSION['gobeauty_cart_product']);
        unset($_SESSION['gobeauty_address']);
        unset($_SESSION['gobeauty_rest_address']);
        unset($_SESSION['gobeauty_cellphone']);
        unset($_SESSION['gobeauty_pay_type']);
        unset($_SESSION['gobeauty_cash_type']);
        unset($_SESSION['gobeauty_cash_key']);
        unset($_SESSION['gobeauty_cash_value']);
        unset($_SESSION['gobeauty_cart_artist_id']);
        unset($_SESSION['gobeauty_cart_artist_name']);
        unset($_SESSION['gobeauty_cart_per_diem_price']);
	unset($_SESSION['gobeauty_cart_spend_point']);
	unset($_SESSION['gobeauty_total_price']);
	echo "INIT";
}
else if ($key == 'use_point') {
	$point = $_POST['my_point'];
	$price = $_POST['price'];
	if ($point <= $price) {
		$_SESSION['gobeauty_cart_spend_point'] = $point;
	} else {
		$_SESSION['gobeauty_cart_spend_point'] = $price;
	}
}
else if ($key == 'delete_point') {
	unset($_SESSION['gobeauty_cart_spend_point']);
}
else if ($key == 'region') {
	$_SESSION['gobeauty_cart_region_top'] = $_POST['top'];
	$_SESSION['gobeauty_cart_region_middle'] = $_POST['middle'];
	$_SESSION['gobeauty_cart_region_bottom'] = $_POST['bottom'];
}
else if ($key == 'check_date')
{
	$year = $_POST['year'];
	$month = $_POST['month'];
	$day = $_POST['day'];

	$_SESSION['gobeauty_cart_year'] = $year;
        $_SESSION['gobeauty_cart_month'] = $month;
        $_SESSION['gobeauty_cart_day'] = $day;
}
else if ($key == 'uncheck_date')
{
	unset($_SESSION['gobeauty_cart_year']);
	unset($_SESSION['gobeauty_cart_month']);
	unset($_SESSION['gobeauty_cart_day']);
}
else if ($key == 'update_date') {
	$artist_id = $_POST['artist_id'];
	$sql = "update tb_reservation set update_time = now() where artist_id = '".$artist_id."' and customer_id = '".$user_id."' and session_id = '".$sessionid."';";
	$result = mysql_query($sql);
}
else if ($key == 'date') {
	$artist_id = $_POST['artist_id'];
	$payment_log_seq = $_POST['payment_log_seq'];
	$year = $_POST['year'];
	$month = $_POST['month'];
	$day = $_POST['day'];
	$hour = $_POST['hour'];
	$minute = ($_POST['minute'] && $_POST['minute'] != "")? $_POST['minute'] : "0";
	$worker = ($_POST['worker'] && $_POST['worker'] != "")? $_POST['worker'] : $artist_id;

	$is_warring = 0;
	// reservationing
	$sql = "select * from tb_reservation where artist_id = '".$artist_id."' and year = ".$year." and month = ".$month." and day = ".$day." and update_time > DATE_ADD(now(), INTERVAL -10 MINUTE);";
	$result = mysql_query($sql);
	while ($rows = mysql_fetch_object($result)) {
		if ($rows->hour <= $hour && $hour < $rows->hour+3) {
			echo "fail";
			return;
		}
		if ($rows->hour <= $hour+2 && $hour+2 < $rows->hour+3) {
			$is_warring = $rows->hour - $hour;
		}
	}

	// purchase
	$sql = "select * from tb_payment_log where artist_id = '".$artist_id."' and year = ".$year." and month = ".$month." and day = ".$day." and is_cancel = 0;";
        $result = mysql_query($sql);
        while ($rows = mysql_fetch_object($result)) {
                if ($rows->hour < $hour && $hour < $rows->to_hour) {
                        echo "fail";
                        return;
                }
		if ($rows->hour <= $hour+2 && $hour+2 < $rows->to_hour) {
                        $is_warring = $rows->hour - $hour;
                }
        }
	
	$sql = "update tb_payment_log set year = ".$year.", month = ".$month.", day = ".$day.", hour = ".$hour.", to_hour = ".($hour+2).", minute = ".$minute.", to_minute = ".$minute.", worker = '".$worker."', update_time = now() where payment_log_seq = '".$payment_log_seq."' and artist_id = '".$artist_id."';";
	$result = mysql_query($sql);
	if (mysql_affected_rows() > 0) {
		if ($is_warring) {
			echo "warring";
		} else {
			echo "success";
		}
        	if ($artist_id != null && $artist_id != "") {
	                //$message = $year."년".$month."월".$day."일".$hour."시 예약변경등록. 예약 내용을 확인하세요.";
	                $message = $year."년".$month."월".$day."일 예약변경등록. 예약 내용을 확인하세요.";
	                $path = "http://gopet.kr/pet/shop/manage_sell_info.php?yy=".$year."&mm=".$month."&dd=".$day;
                	//$image = "http://gopet.kr/pet/images/logo_login.jpg";
					$image = "";
        	        a_push($artist_id, "반짝, 반려생활의 단짝. 예약변경 알림", $message, $path, $image);
	        }
	} else {
		echo "fail";
	}
	//echo $sql;
}
else if ($key == 'product') {
	$name_price = $_POST['name'];
	echo $name_price;
	$count = $_POST['count'];
	//echo $count;
	list($name, $price) = split ("_", $name_price);
	//echo $name;
	//echo $price;
	if ($_SESSION['gobeauty_cart_product'])	{
		$_SESSION['gobeauty_cart_product'] = $_SESSION['gobeauty_cart_product'].",";
	}
	$_SESSION['gobeauty_cart_product'] = $_SESSION['gobeauty_cart_product'].$name.":".$price.":".$count;
	//echo $_SESSION['gobeauty_cart_product'];
}
else if ($key == 'delproduct') {
	$name_price = $_POST['name'];
	//echo $name_price;
	$session_data = $_SESSION['gobeauty_cart_product'];
	//echo $session_data;
	$_SESSION['gobeauty_cart_product'] = str_replace($name_price, "", $session_data);
}
else if ($key == 'address') {
	$address = $_POST['address'];
	//echo $address;
	$_SESSION['gobeauty_address'] = $address;
}
else if ($key == 'rest_address') {
	$address = $_POST['rest_address'];
	$phone = $_POST['cellphone'];
	//echo $address;
	//echo $phone;
	$_SESSION['gobeauty_rest_address'] = $address;
	$_SESSION['gobeauty_cellphone'] = $phone;
}
else if ($key == 'pay_type') {
	$selected = $_POST['selected'];
	$_SESSION['gobeauty_pay_type'] = $selected;
	if ($selected == "card") {
//		$card = $_POST['card'];
//		$plan = $_POST['plan'];
//		$_SESSION['gobeauty_card'] = $card;
//		$_SESSION['gobeauty_plan'] = $plan;
	} else {
//		$bank = $_POST['bank'];
//		$_SESSION['gobeauty_bank'] = $bank;
	}
}
else if ($key == 'cash_receipts') {
	$selected = $_POST['selected'];
//	echo $selected;
//	echo $_POST['cash_key'];
//	echo $_POST['value'];
	$_SESSION['gobeauty_cash_type'] = $selected;
	$_SESSION['gobeauty_cash_key'] = $_POST['cash_key'];
	$_SESSION['gobeauty_cash_value'] = $_POST['value'];
}

closeDB();
?>
