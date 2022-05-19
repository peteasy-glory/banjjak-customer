<?php
ini_set('memory_limit', -1); 

include "../include/configure.php";
include "../include/db_connection.php";
include "../include/session.php";
include "../include/php_function.php";
include "../include/Crypto.class.php";

$user_id = $_SESSION['gobeauty_user_id'];
make_user_directory($upload_static_directory.$upload_directory, $user_id);

// 설정
$allowed_ext = array('jpg','jpeg','png','gif');
 
// 변수 정리
$error = $_FILES['myfile']['error'];
$name = $_FILES['myfile']['name'];

$new_filename = $_POST['filepath'];
$ext = array_pop(explode('.', $name));

// 오류 확인
if( $error != UPLOAD_ERR_OK ) {
	switch( $error ) {
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
			echo "파일이 너무 큽니다. ($error)";
			break;
		case UPLOAD_ERR_NO_FILE:
			echo "파일이 첨부되지 않았습니다. ($error)";
			break;
		default:
			echo "파일이 제대로 업로드되지 않았습니다. ($error)";
	}
	exit;
}
 
// 확장자 확인
if( !in_array(strtolower($ext), $allowed_ext) ) {
	echo "허용되지 않는 확장자입니다.";
	exit;
}
 
$upload_direcoty_full_path = $upload_directory."/".$new_filename;
move_uploaded_file( $_FILES['myfile']['tmp_name'], $upload_static_directory.$upload_direcoty_full_path);

$crypto = new Crypto();
$user_id = $crypto->encode(trim($user_id), $access_key, $secret_key);

$sql = "select * from tb_request_artist where customer_id = '".$user_id."';";
$result = mysql_query($sql);
if ($result_datas = mysql_fetch_object($result)) {
	$asql = "update tb_request_artist set business_license = '".$upload_direcoty_full_path."' where customer_id = '".$user_id."';";
//	echo $asql;
	$aresult = mysql_query($asql);
} else {
	$insert_sql = "insert into tb_request_artist (customer_id, business_license, update_time) values ('".$user_id."', '".$upload_direcoty_full_path."', now());";
//	echo $insert_sql;
        $insert_result = mysql_query($insert_sql);
}

// 파일 정보 출력
/*echo "<h2>파일 정보</h2>
<ul>
	<li>파일명: $name</li>
	<li>확장자: $ext</li>
	<li>파일형식: {$_FILES['myfile']['type']}</li>
	<li>파일크기: {$_FILES['myfile']['size']} 바이트</li>
</ul>";
*/

?>
