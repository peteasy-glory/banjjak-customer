<?php include "../include/top.php"; ?>
<?php include "../include/Point.class.php";?>

<?php
	$user_id = $_SESSION['gobeauty_user_id'];
	$receipt_id = $_REQUEST['receipt_id'];
	//echo $receipt_id;

	/*if ($receipt_id == "") {
		return;
	}*/
	$application_id = "5acc2185b6d49c7b637d9c12";
	$private_key = 'oZq//0OpaSulB2uzNU8l7mQGgQpePEmpihnUb5TuAvA=';
	//$receipt_id = "5ae84d51e23d1b1ea202d929";

	$url = "https://api.bootpay.co.kr/receipt/".$receipt_id."?application_id=".$application_id."&private_key=".$private_key;

	$headers = array('Content-Type: application/json');
        $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
//		'application_id' => '5acc2185b6d49c7b637d9c12',
//		'private_key' => 'oZq//0OpaSulB2uzNU8l7mQGgQpePEmpihnUb5TuAvA='
//          ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$response = curl_exec($ch);
        //echo "response : ".$response."<br>";
        $errno = curl_errno($ch);
        //echo "errno : ".$errno."<br>";
        $errstr = curl_error($ch);
        //echo "errstr : ".$errstr."<br>";

	if ($errno) {
		$response = $response."|".$errno."|".$errstr;
	}
	// throw new Exception('error: ' . $errno . ', msg: ' . $errstr);

	$artist_sql = "insert into tb_payment_point_log (receipt_id, total_price, session_id, order_id, customer_id, status, pay_type, product, update_time, pg_log) values ('".$receipt_id."', '".$_SESSION['gobeauty_point_price']."', '".$sessionid."', '".$_SESSION['gobeauty_order_id']."', '".$user_id."', 'R1', 'card', '".$_SESSION['gobeauty_point_product']."', now(), '".$response."');";
	//echo $artist_sql;
	$result = mysql_query($artist_sql);
?>
	<script language="javascript">
		<?php
		if(mysql_affected_rows() > 0){
			$point = new Point;
			$result = $point->select_point ($user_id);
			$point->add_purchase_point($_SESSION['gobeauty_point_price'], $_SESSION['gobeauty_order_id']);
		?>
			// success
			location.href="manage_my_point.php";
		<?}else{?>
			//error
			echo "ERRRRRRRRRRORO";
		 <?}?>
	</script>
<?php include "../include/buttom.php"; ?>
