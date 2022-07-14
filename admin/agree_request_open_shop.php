<?
ini_set('memory_limit', -1);

include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login.php");

$user_id = $_SESSION['gobeauty_user_id'];

$customer_id = $_REQUEST['customer_id'];

$crypto = new Crypto();
$enc_customer_id = $crypto->encode(trim($customer_id), $access_key, $secret_key);

$sql = "update tb_shop set open_flag = true where customer_id = '".$customer_id."';";
$result = mysqli_query($connection,$sql);
if ($result)
{
        $artist_id = $customer_id;
        if ($artist_id != null && $artist_id != "") {
                $path = "https://partner.banjjakpet.com";
                //$image = "http://gopet.kr/pet/images/logo_login.jpg";
				$image = "";
                a_push($artist_id, "반짝, 반려생활의 단짝. 오픈승인", "오픈 신청이 승인되었습니다. My SHOP을 방문해보세요.", $path, $image);
        }
	echo "승인되었습니다.";
}
else
{
	echo "승인이 안되었어요. 개발자에게 연락부탁드립니다.";
}

?>
