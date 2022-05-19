<?php
ini_set('memory_limit', -1);

include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

// 설정
$review_images_array = array();

// 변수 정리
$error = $_FILES['myfile']['error'];
$name = $_FILES['myfile']['name'];

$src = $_REQUEST['src'];
$payment_log_seq = $_REQUEST['payment_log_seq'];
$artist_id = $_REQUEST['artist_id'];
$user_id = $_REQUEST['user_id'];

$check_delete = false;

$sql = "SELECT * FROM tb_usage_reviews WHERE customer_id = '" . $user_id . "' AND payment_log_seq = '" . $payment_log_seq . "';";
$result = mysqli_query($connection,$sql);
if ($result_datas = mysqli_fetch_object($result)) {
	$review_images_array = explode('|',$result_datas->review_images);
	if(sizeof($review_images_array) > 1){
		if($review_images_array[0] != $src){
			$review_images = str_replace('|'.$src,'',$result_datas->review_images);
		}else{
			$review_images = str_replace($src.'|','',$result_datas->review_images);
		}
	}else{
		$review_images = "";
    }

	$update_sql = "UPDATE tb_usage_reviews SET review_images = '" . $review_images . "', update_time = NOW() WHERE customer_id = '" . $user_id . "' AND payment_log_seq = '" . $payment_log_seq . "';";
	$update_result = mysqli_query($connection,$update_sql);
	$check_delete = true;
}

if($check_delete){
	if (!unlink($upload_static_directory.$src)) {
		exit;
	} else {
		return $review_images;
	}
}else{
	echo "삭제 중 오류가 발생했습니다.";
	exit;
}
?>