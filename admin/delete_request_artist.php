<?
ini_set('memory_limit', -1); 

include "../include/top.php";
include "../include/Crypto.class.php";

$user_id = $_SESSION['gobeauty_user_id'];

$customer_id = $_REQUEST['customer_id'];

$crypto = new Crypto();
$customer_id = $crypto->encode(trim($customer_id), $access_key, $secret_key);

$sql = "delete from tb_request_artist where customer_id = '".$customer_id."';";
$result = mysql_query($sql);
if ($result)
{
	echo "삭제되었습니다.";
}
else
{
	echo "삭제가 안되었어요. 개발자에게 연락부탁드립니다.";
}

include "../include/bottom.php";
?>
