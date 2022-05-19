<?
ini_set('memory_limit', -1); 

include "../include/top.php";
include "../include/Crypto.class.php";

$user_id = $_SESSION['gobeauty_user_id'];
$customer_id = $_REQUEST['customer_id'];

$crypto = new Crypto();
$enc_customer_id = $crypto->encode(trim($customer_id), $access_key, $secret_key);

$sql = "update tb_customer set my_shop_flag = 0, artist_flag= 0 where id = '".$customer_id."';";
error_log('----- $sql : '.$sql);
$result = mysql_query($sql);
if ($result){
    $sql_shop = "update tb_shop set is_finish_open_magic = 0 where customer_id = '".$customer_id."';";
    error_log('----- $sql : '.$sql_shop);
    $result_shop = mysql_query($sql_shop);
    if($result_shop){
        echo "반려되었습니다.";
    }else{
        echo "반려에 실패했습니다.(ErrorCode-2)";
    }
}else{
	echo "반려에 실패했습니다.(ErrorCode-1)";
}

include "../include/bottom.php";
?>
