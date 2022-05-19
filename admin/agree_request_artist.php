<?
ini_set('memory_limit', -1); 

include "../include/top.php";
include "../include/Crypto.class.php";

$user_id = $_SESSION['gobeauty_user_id'];

$customer_id = $_REQUEST['customer_id'];

$crypto = new Crypto();
$enc_customer_id = $crypto->encode(trim($customer_id), $access_key, $secret_key);

$sql = "update tb_customer set my_shop_flag = true where id = '".$customer_id."';";
$result = mysql_query($sql);


$sql1 = "update tb_request_artist set step = 6 where customer_id = '".$enc_customer_id."';";
$result1 = mysql_query($sql1);
if ($result)
{
	$artist_id = $customer_id;
	
	if ($artist_id != null && $artist_id != "") {
                $path = "http://gopet.kr/pet/mainpage/mainpage_my_menu.php";
                //$image = "http://gopet.kr/pet/images/logo_login.jpg";
				$image = "";
                //a_push($artist_id, "반짝, 반려생활의 단짝. 입점승인", "입점신청이 승인되었습니다. My SHOP 오픈을 준비해주세요.", $path, $image);
				// 20200526 ulmo 벨라의 요청으로 임시로 입점신청 푸시 발송 막아놓음
        }
	echo "승인되었습니다.";
}
else
{
	echo "승인이 안되었어요. 개발자에게 연락부탁드립니다.";
}

include "../include/bottom.php";
?>
