<?
ini_set('memory_limit', -1);

include "../include/configure.php";
include "../include/db_connection.php";
include "../include/session.php";
include "../include/php_function.php";
include "../include/Crypto.class.php";

function correctImageOrientation($filename)
{
    if (function_exists('exif_read_data')) {
        $exif = exif_read_data($filename);
        if ($exif && isset($exif['Orientation'])) {
            $orientation = $exif['Orientation'];
            if ($orientation != 1) {
                $img = imagecreatefromjpeg($filename);
                $deg = 0;
                switch ($orientation) {
                    case 3:
                        $deg = 180;
                        break;
                    case 6:
                        $deg = 270;
                        break;
                    case 8:
                        $deg = 90;
                        break;
                }
                if ($deg) {
                    $img = imagerotate($img, $deg, 0);
                }
                // then rewrite the rotated image back to the disk as $filename 
                imagejpeg($img, $filename, 95);
            } // if there is some rotation necessary
        } // if have the exif orientation info
    } // if function exists      
}

$user_id = $_SESSION['gobeauty_user_id'];
make_user_directory($upload_static_directory . $upload_directory, $user_id);

// 설정
$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');

// 변수 정리
$error = $_FILES['myfile']['error'];
$name = $_FILES['myfile']['name'];

$new_filename = $_POST['filepath'];
$petname = $_POST['petname'];
$ext = array_pop(explode('.', $name));

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

move_uploaded_file($_FILES['myfile']['tmp_name'], $upload_static_directory . $upload_direcoty_full_path);
correctImageOrientation($upload_static_directory . $upload_direcoty_full_path);

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
        "upfilename": "<?= $upload_direcoty_full_path ?>",
        "old_photo": "<?= $old_photo ?>",
        } -->