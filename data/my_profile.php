<?
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$request_method = $_SERVER["REQUEST_METHOD"];

$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];


if ($request_method == "GET"){
  global $connection;
  $sql = "
  SELECT *
  FROM tb_customer
  WHERE id = '".$user_id."'
  ";
  $result = mysqli_query($connection, $sql);
	$row = mysqli_fetch_assoc($result);

  
	$crypto = new Crypto();
  $row["cellphone"] = $crypto->decode(trim($row["cellphone"]), $access_key, $secret_key);
  
  echo json_encode(array("id"=>$row["id"],
  "cellphone"=>$row["cellphone"],
  "cellphone_confirm"=>$row["cellphone_confirm"],
  "email"=>$row["email"],
  "email_confirm"=>$row["email_confirm"],
  "bill_flag"=>$row["bill_flag"],
  "nickname"=>$row["nickname"],
  "photo"=>$row["photo"],
  "last_login_time"=>$row["last_login_time"],
  "registration_time"=>$row["registration_time"],
  "delete_time"=>$row["delete_time"],
  "mileage"=>$row["mileage"],
  "enable_flag"=>$row["enable_flag"],
  "push_option"=>$row["push_option"],
  "my_shop_flag"=>$row["my_shop_flag"],
  "artist_flag"=>$row["artist_flag"],
  "admin_flag"=>$row["admin_flag"],
  "my_hotel_flag"=>$row["my_hotel_flag"],
  "my_playroom_flag"=>$row["my_playroom_flag"],
  "partner_flag"=>$row["partner_flag"],
  "mainpage_type"=>$row["mainpage_type"],
  "is_regist_by_naver"=>$row["is_regist_by_naver"],
  "is_regist_by_apple"=>$row["is_regist_by_apple"],
  "age"=>$row["age"],
  "gender"=>$row["gender"],
  "is_android"=>$row["is_android"],
  "operator_flag"=>$row["operator_flag"],
  "last_applogin_time"=>$row["last_applogin_time"],
  "data_delete"=>$row["data_delete"]
  ));
}
?>