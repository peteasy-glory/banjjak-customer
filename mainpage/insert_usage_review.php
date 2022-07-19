<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/common/TEmoji.php");

$emoji = new TEmoji();

$user_id = $_SESSION['gobeauty_user_id'];

//$rating = $_REQUEST["rating"];
$rating = $emoji->emojiStrToDB($_POST["rating"]);


$payment_log_seq = $_REQUEST["payment_log_seq"];
$artist_id = $_REQUEST["artist_id"];

//$memo = $_REQUEST["memo"];
$memo = $emoji->emojiStrToDB($_POST["memo"]);

$review_images = $_REQUEST["review_images"];
//echo $memo;
$key = $_REQUEST["key"];
//echo $key;
if ($key == "delete") {
	$check_delete_images = false;

	$images_sql = "SELECT * FROM tb_usage_reviews WHERE customer_id = '" . $user_id . "' AND payment_log_seq = '" . $payment_log_seq . "';";
	$images_sql_result = mysqli_query($connection,$images_sql);

	$images_array = array();
	if ($images_result_datas = mysqli_fetch_object($images_sql_result)) {
		$images_array = explode('|', $images_result_datas->review_images);
		$images_array_size = sizeof($images_array);
		if (sizeof($images_array) > 0) {
				for ($i = 0; $i < $images_array_size; $i++) {
						error_log('----- $images_array[' . $i . '] : ' . $images_array[$i]);
						unlink($upload_static_directory . $images_array[$i]);
				}
				$check_delete_images = true;
		}
	} else {
		echo "이미지 삭제 중 오류가 발생했습니다.";
		exit;
	}

	if ($check_delete_images) {
		$delete_sql = "UPDATE tb_usage_reviews SET is_delete = 1, update_time = NOW(), review_images = '' WHERE customer_id = '" . $user_id . "' AND payment_log_seq = '" . $payment_log_seq . "';";
		$update_result = mysqli_query($connection,$delete_sql);
		if ($update_result) {
?>
			<script language="javascript">
					location.href = "../mypage_review_beauty_list";
			</script>
<?php
		} else {
?>
			<script language="javascript">
					alert("후기 삭제 중 오류가 발생했습니다.");
                    location.href = "../mypage_review_beauty_list";
			</script>
<?php
		}
	} else {
?>
			<script language="javascript">
                alert("이미지 삭제 중 오류가 발생했습니다.");
                location.href = "../mypage_review_beauty_list";
			</script>
<?php
	}
} else {
	if($rating != "" && $memo != ""){
		$nickname = "";
		$photo = "";
		$c_sql = "SELECT * FROM tb_customer WHERE id = '" . $user_id . "';";
		$c_result = mysqli_query($connection,$c_sql);
		if ($c_rows = mysqli_fetch_object($c_result)) {
				$photo = $c_rows->photo;
				$nickname = $c_rows->nickname;
		}

		$sql = "SELECT * FROM tb_usage_reviews WHERE customer_id = '" . $user_id . "' AND payment_log_seq = '" . $payment_log_seq . "';";
		$result = mysqli_query($connection,$sql);
		$review_cnt = mysqli_num_rows($result);
		if($review_cnt == 0){
			if($user_id != ""){
				$insert_sql = "INSERT INTO tb_usage_reviews (photo, nickname, customer_id, artist_id, payment_log_seq, rating, review, review_images, is_delete, reg_time) VALUES ('" . $photo . "', '" . $nickname . "', '" . $user_id . "', '" . $artist_id . "', '" . $payment_log_seq . "', '" . $rating . "', '" . $memo . "', '" . $review_images . "', 0, NOW());";
				$insert_result = mysqli_query($connection,$insert_sql);
				if ($insert_result) {
					if ($artist_id != null && $artist_id != "") {
						$path = "http://gopet.kr/pet/shop/manage_my_post_story.php";
						//$image = "http://gopet.kr/pet/images/logo_login.jpg";
						$image = "";
						a_push($artist_id, "반짝, 반려생활의 단짝. 후기등록 알림", "새로운 후기등록. 내용을 확인하세요.", $path, $image);
					}
?>
			<script language="javascript">
                location.href = "../mypage_review_beauty_list";
			</script>
<?php
				} else {
?>
			<script language="javascript">
                alert("저장 중 오류가 발생했습니다.");
                location.href = "../mypage_review_beauty_list";
			</script>
<?php
				}
			}
		} else {
			if ($result_datas = mysqli_fetch_object($result)) {
				$update_sql = "UPDATE tb_usage_reviews SET artist_id ='" . $artist_id . "', photo = '" . $photo . "', nickname = '" . $nickname . "', review = '" . $memo . "', rating = '" . $rating . "', review_images = '" . $review_images . "', update_time = NOW(), is_delete = 0, is_report = 0 WHERE customer_id = '" . $user_id . "' AND payment_log_seq = '" . $payment_log_seq . "';";
				$update_result = mysqli_query($connection,$update_sql);
				if ($update_result) {
					if ($artist_id != null && $artist_id != "") {
						$path = "https://partner.banjjakpet.com/shop_review_list";
						//$image = "http://gopet.kr/pet/images/logo_login.jpg";
						$image = "";
						a_push($artist_id, "반짝, 반려생활의 단짝. 후기등록 알림", "새로운 후기등록. 내용을 확인하세요.", $path, $image);
					}
?>
			<script language="javascript">
                location.href = "../mypage_review_beauty_list";
			</script>
<?php
				} else {
?>
			<script language="javascript">
                alert("수정 중 오류가 발생했습니다.");
                location.href = "../mypage_review_beauty_list";
			</script>
<?php
				}
                echo "test1";
			}
            echo "test2";
		}
        echo "test3";
	}
    echo "test4".$rating;
}
echo "test5".$memo;

//include "../include/bottom.php";
?>