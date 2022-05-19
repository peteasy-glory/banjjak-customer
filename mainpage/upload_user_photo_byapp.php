<?php
ini_set('memory_limit', -1);

include "../include/configure.php";
include "../include/db_connection.php";
include "../include/session.php";
include "../include/php_function.php";
include "../include/Crypto.class.php";

$petname = $_REQUEST['petname'];
$user_id = $_SESSION['gobeauty_user_id'];

make_user_directory($upload_static_directory . $upload_directory, $user_id);

// 설정
$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');

$filename = $_REQUEST['filepath'];
$filename = trim($filename);

$new_filename = $_REQUEST['newfilepath'];
$new_filename = trim($new_filename);

$filename_array = explode('.', $filename);
$ext = array_pop($filename_array);

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

$upload_direcoty_full_path = $upload_directory . "/" . $new_filename;
//move_uploaded_file( $_FILES['myfile']['tmp_name'], $upload_static_directory.$upload_direcoty_full_path);

$oldfile = $upload_static_directory . "/pet/upload/appupload/" . $filename;
$newfile = $upload_static_directory . $upload_direcoty_full_path;
$imgpath = $oldfile;
$target = $newfile;

//모바일 세로 이미지 로테이션...
$mklotaion = false;
//			$tmpp_file=$_FILES["imgupfile"]["tmp_name"];
$exifData = exif_read_data($imgpath);
//print_r($exifData);
if ($exifData['Orientation']) {
    //echo "lotation";
    if ($exifData['Orientation'] == 6) {
        // 시계방향으로 90도 돌려줘야 정상인데 270도 돌려야 정상적으로 출력됨 
        $degree = 270;
    } else if ($exifData['Orientation'] == 8) {
        // 반시계방향으로 90도 돌려줘야 정상 
        $degree = 90;
    } else if ($exifData['Orientation'] == 3) {
        $degree = 180;
    }
    //echo $exifData['Orientation'];
    if ($degree) {
        if ($exifData[FileType] == 1) {
            $source = imagecreatefromgif($imgpath);
            $source = imagerotate($source, $degree, 0);
            imagegif($source, $target);
            $mklotaion = true;
            //	echo "-1".$degree;
        } else if ($exifData[FileType] == 2) {
            $source = imagecreatefromjpeg($imgpath);
            $source = imagerotate($source, $degree, 0);
            imagejpeg($source, $target);
            $mklotaion = true;
            //	echo "-2/".$degree."/".$source."/".$target;
        } else if ($exifData[FileType] == 3) {
            $source = imagecreatefrompng($imgpath);
            $source = imagerotate($source, $degree, 0);
            imagepng($source, $target);
            $mklotaion = true;
            //	echo "-3".$degree;
        }
    } //if($degree)
    //로테이션 처리...를 못했으면 그냥 업로드 처리
    if ($mklotaion == false) {
        //move_uploaded_file($imgpath, $target);
        copy($imgpath, $target);
    }
} else {  //if($exifData['Orientation'])
    //move_uploaded_file($imgpath, $target);
    copy($imgpath, $target);
    //echo "그냥업로드";
} //if($exifData['Orientation'])

//기존 앱에서 올린 파일 삭제..
@unlink($imgpath);

$old_pet_photo_delete = "false";
$old_customer_photo_delete = "false";

if ($petname && $petname != "gobeauty_profile") {
    $sql = "SELECT * FROM tb_mypet WHERE customer_id = '" . $user_id . "' AND name_for_owner = '" . $petname . "';";
    $result = mysql_query($sql);
    if ($result_datas = mysql_fetch_object($result)) {
        $old_photo = $upload_static_directory . $result_datas->photo;

        if (@unlink($old_photo)) {
            $old_pet_photo_delete = "true";
        }
    } else {
        echo "Failed to load an existing pet image";
    }

    $update_sql = "update tb_mypet set photo = '" . $upload_direcoty_full_path . "' where customer_id = '" . $user_id . "' and name_for_owner = '" . $petname . "';";
    $update_result = mysql_query($update_sql);
} else {
    $sql = "SELECT * FROM tb_customer WHERE id = '" . $user_id . "';";
    $result = mysql_query($sql);
    if ($result_datas = mysql_fetch_object($result)) {
        $old_photo = $upload_static_directory . $result_datas->photo;

        if (@unlink($old_photo)) {
            $old_customer_photo_delete = "true";
        }
    } else {
        echo "Failed to load an existing customer image";
    }

    $update_sql = "update tb_customer set photo = '" . $upload_direcoty_full_path . "' where id = '" . $user_id . "';";
    $update_result = mysql_query($update_sql);
}

?>
    <!-- {
    "old_pet_photo_delete":"<?= $old_pet_photo_delete ?>",
    "old_customer_photo_delete":"<?= $old_customer_photo_delete ?>",
    "petname":"<?= $petname ?>",
    "old_photo":"<?= $old_photo ?>",
    "upfilename": "<?= $upload_direcoty_full_path ?>",
    "oldfile": "<?= $oldfile ?>",
    "newfile": "<?= $newfile ?>"
    } -->