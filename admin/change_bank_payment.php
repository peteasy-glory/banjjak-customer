<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login.php");
include($_SERVER['DOCUMENT_ROOT']."/include/skin/header.php");
?>


<?php
$payment_log_seq = $_REQUEST['payment_log_seq'];
$order_id = $_REQUEST["order_id"];
$customer_id = $_REQUEST['customer_id'];
$artist_id = $_REQUEST['artist_id'];
$original_price = $_REQUEST['org'];

$sql = "update tb_payment_log set status = 'R1' where payment_log_seq = '".$payment_log_seq."' and customer_id = '".$customer_id."';";
$result = mysqli_query($connection,$sql);

// 실 결제 포인트 적립
if ($original_price > 0) {
	$point = new Point;
	$result = $point->select_point ($customer_id);
	$point->add_accumulate_percent_point($original_price, 0.5, $payment_log_seq, $order_id);
}

$path = "https://customer.banjjakpet.com/mypage_reserve_list";
//$image = "http://gopet.kr/pet/images/logo_login.jpg";
$image = "";
a_push($customer_id, "반짝, 반려생활의 단짝. 입금 확인", "예약관련 계좌이체 입금확인 되었습니다.", $path, $image);

$path = "https://partner.banjjakpet.com/reserve_main_day?ch=day&yy=".$_REQUEST['year']."&mm=".$_REQUEST['month']."&dd=".$_REQUEST['day'];
$message = $_REQUEST['year']."년".$_REQUEST['month']."월".$_REQUEST['day']."일 신규 예약등록";
a_push($artist_id, "반짝, 반려생활의 단짝. 예약 확인", $message, $path, $image);

?>



<script>
	location.href = 'manage_bank_payment.php';
</script>
