<?php include "../include/top.php"; ?>
<?php include "../include/Point.class.php";?>

<?php
$payment_log_seq = $_REQUEST['payment_log_seq'];
$order_id = $_REQUEST["order_id"];
$customer_id = $_REQUEST['customer_id'];
$artist_id = $_REQUEST['artist_id'];
$original_price = $_REQUEST['org'];

$sql = "update tb_payment_log set status = 'R1' where payment_log_seq = '".$payment_log_seq."' and customer_id = '".$customer_id."';";
$result = mysql_query($sql);

// 실 결제 포인트 적립
if ($original_price > 0) {
	$point = new Point;
	$result = $point->select_point ($customer_id);
	$point->add_accumulate_percent_point($original_price, 0.5, $payment_log_seq, $order_id);
}

$path = "http://gopet.kr/pet/mainpage/manage_my_reservation.php";
//$image = "http://gopet.kr/pet/images/logo_login.jpg";
$image = "";
a_push($customer_id, "반짝, 반려생활의 단짝. 입금 확인", "예약관련 계좌이체 입금확인 되었습니다.", $path, $image);

// 파트너앱 설치여부 판단
$partner_sql = "SELECT * FROM tb_customer WHERE id = '".$artist_id."' AND (partner_token IS NOT NULL AND partner_token != '')";
$partner_result = mysql_query($partner_sql);
$partner_rows = mysql_fetch_object($partner_result);
$is_partner = $partner_rows->partner_token;
$partner = ($is_partner && $is_partner != "")? "partner" : "" ;
$path = "http://gopet.kr/pet/shop/manage_sell_info.php?yy=".$_REQUEST['year']."&mm=".$_REQUEST['month']."&dd=".$_REQUEST['day'];
$path = ($is_partner && $is_partner != "")? "https://partner.banjjakpet.com/reserve_main_day?ch=day&yy=".$_REQUEST['year']."&mm=".$_REQUEST['month']."&dd=".$_REQUEST['day'] : $path;
$message = $_REQUEST['year']."년".$_REQUEST['month']."월".$_REQUEST['day']."일 신규 예약등록";
a_push($artist_id, "반짝, 반려생활의 단짝. 예약 확인", $message, $path, $image, $partner);

?>

<?php include "../include/bottom.php"; ?>

<script>
	location.href = 'manage_bank_payment.php';
</script>
