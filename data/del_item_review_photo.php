<?php
ini_set('memory_limit', -1);

include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

// 설정
$review_images_array = array();

// 변수 정리
$error = $_FILES['myfile']['error'];
$name = $_FILES['myfile']['name'];

$src = $_REQUEST['src'];
$seq = $_REQUEST['seq'];

$check_delete = false;

$sql = "SELECT * FROM tb_item_review WHERE ir_seq = {$seq};";
$result = mysqli_query($connection,$sql);
if ($result_datas = mysqli_fetch_object($result)) {
    $review_images_array = explode(',',$result_datas->review_image);
    if(sizeof($review_images_array) > 1){
        if($review_images_array[0] != $src){
            $review_images = str_replace(','.$src,'',$result_datas->review_image);
        }else{
            $review_images = str_replace($src.',','',$result_datas->review_image);
        }
    }else{
        $review_images = "";
    }

    $update_sql = "UPDATE tb_item_review SET review_image = '" . $review_images . "', update_dt = NOW() WHERE ir_seq = '" . $seq . "';";
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