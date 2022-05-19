<?
ini_set('memory_limit', -1); 

include "../include/top.php";
include "../include/Crypto.class.php";

$user_id = $_SESSION['gobeauty_user_id'];
$uuid = $_REQUEST['id'];
$memo = $_REQUEST['1vs1_reply'];
$customer_id = $_REQUEST['customer_id'];

//$crypto = new Crypto();
//$enc_customer_id = $crypto->encode(trim($customer_id), $access_key, $secret_key);

$sql = "select * from tb_1vs1_pna_sub where qna_id = '".$uuid."';";
$result = mysql_query($sql);
if ($rows = mysql_fetch_object($result)) {
	$u_sql = "update tb_1vs1_pna_sub set body = '".addslashes($memo)."', update_time = now() where qna_id = '".$uuid."';";
	$u_result = mysql_query($u_sql);
} else {
        $u_sql = "insert into tb_1vs1_pna_sub (qna_id, body, update_time) values ('".$uuid."', '".addslashes($memo)."', now());";
        $u_result = mysql_query($u_sql);

        $message = "1대1 문의에 대한 답글이 게시되었습니다.";
        $path = "http://gopet.kr/pet/mainpage/manage_1vs1.php";
        //$image = "http://gopet.kr/pet/images/logo_login.jpg";
		$image = "";
        a_push($customer_id, "반짝, 반려생활의 단짝. 1대1 문의 답글알림", $message, $path, $image);

}
?>

<script>
	location.href = 'manage_1vs1_reply.php';
</script>

<?php
include "../include/bottom.php";
?>
