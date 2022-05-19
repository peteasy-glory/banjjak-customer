<? 
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$request_method = $_SERVER["REQUEST_METHOD"];

$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

if ($request_method == "POST"){
  $new_nickname = $_REQUEST["nickname"];
  
  global $connection;
  $sql = "update tb_customer set nickname = '".$new_nickname."' where id = '".$user_id."';";
  $result = mysqli_query($connection, $sql);

  echo json_encode($result);
}

?>