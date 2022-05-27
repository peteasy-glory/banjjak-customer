<?php
ini_set('memory_limit', -1);

include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$user_id = $_SESSION['gobeauty_user_id'];
$payment_log_seq = $_REQUEST['payment_log_seq'];
$artist_id = $_REQUEST['artist_id'];
make_user_directory($upload_static_directory2.$upload_directory2, $user_id);

// 설정
$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');

// 변수 정리
$error = $_FILES['myfile']['error'];
$name = $_FILES['myfile']['name'];

$new_filename = $_POST['filepath'];
// error_log($new_filename);
$name = explode('.', $name);
$ext = array_pop($name);

// 오류 확인
if ($error != UPLOAD_ERR_OK) {
	switch ($error) {
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
if (!in_array(strtolower($ext), $allowed_ext)) {
	echo "허용되지 않는 확장자입니다.";
	exit;
}

$upload_direcoty_full_path = $upload_directory2 . "/" . $new_filename;

$nickname = "";
$photo = "";
$check_upload = false;
$review_images = "";

$c_sql = "SELECT * FROM tb_customer WHERE id = '" . $user_id . "';";
$c_result = mysqli_query($connection,$c_sql);
if ($c_rows = mysqli_fetch_object($c_result)) {
	$photo = $c_rows->photo;
	$nickname = $c_rows->nickname;
}

$sql = "SELECT * FROM tb_usage_reviews WHERE customer_id = '" . $user_id . "' AND payment_log_seq = '" . $payment_log_seq . "';";
$result = mysqli_query($connection,$sql);
if ($result_datas = mysqli_fetch_object($result)) {
	if ($result_datas->review_images != "" || $result_datas->review_images != null) {
		$review_images = $result_datas->review_images . "|" . $upload_direcoty_full_path;
	} else {
		$review_images = $upload_direcoty_full_path;
	}

	$update_sql = "UPDATE tb_usage_reviews SET update_time = NOW(), review_images = '" . $review_images . "' WHERE customer_id = '" . $user_id . "' AND payment_log_seq = '" . $payment_log_seq . "';";
	$update_result = mysqli_query($connection,$update_sql);
	$check_upload = true;
} else {
	if ($result_datas->review_images != "" || $result_datas->review_images != null) {
		$review_images = $result_datas->review_images . "|" . $upload_direcoty_full_path;
	} else {
		$review_images = $result_datas->review_images;
	}
	// is_delete = 1(삭제)로 변경 : 사진 등록시 review 테이블에 데이터가 입력됨으로 리뷰가 작성된 것으로 인식되는 현상 해결 - 20210802
	$insert_sql = "INSERT INTO tb_usage_reviews (photo, nickname, customer_id, artist_id, payment_log_seq, review_images, is_delete, reg_time) VALUES ('" . $photo . "', '" . $nickname . "', '" . $user_id . "', '" . $artist_id . "', '" . $payment_log_seq . "', '" . $review_images . "', 1, NOW());";
	$insert_result = mysqli_query($connection,$insert_sql);
	$check_upload = true;
}

if ($check_upload) {
	move_uploaded_file($_FILES['myfile']['tmp_name'], $upload_static_directory2.$upload_direcoty_full_path);

    $s3 = new TAwsS3('banjjak-s3', 'AKIATLSPGL6BNM6VOYWX', 'JJagfUCVzN4fCOrX3cdGHlX+8WL9PJ7T0GUHlFao');
    $s3->resizeImage($upload_static_directory2.$upload_direcoty_full_path, $upload_static_directory2.$upload_direcoty_full_path);
    $s3->fileToS3($upload_static_directory2.$upload_direcoty_full_path, $upload_directory."/".$new_filename);
} else {
	echo "처리 중 오류가 발생했습니다.";
	exit;
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
//header('Content-type: application/json');
?>

	{"upfilename": "<?= $upload_direcoty_full_path ?>","allpath": "<?= $upload_static_directory . $upload_direcoty_full_path ?>","msg": "","imagewidth": "300"}
