<?php
// include "../include/top.php";
// include "../include/check_header_log.php";
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$user_id = $_SESSION['gobeauty_user_id'];
$pet_seq = $_POST['pet_seq'];

$mypet_name = $_POST['mypet_name'];
$mypet_name = trim($mypet_name);
$pet_type = $_POST['pet_kind'];
$mypet_type = $_POST['mypet_type'];
$mypet_type2 = $_POST['mypet_type2'];
$mypet_b_year = $_POST['mypet_b_year'];
$mypet_b_month = $_POST['mypet_b_month'];
$mypet_b_day = $_POST['mypet_b_day'];
$pet_gender = $_POST['gender'];
$mypet_neutral = $_POST['neutralize'];

$mypet_weight1 = $_POST['mypet_weight1'];
$mypet_weight2 = $_POST['mypet_weight2'];
if (trim($mypet_type) != '기타') {
    $mypet_type2 = "";
}
$mypet_weight = $mypet_weight1 . "." . $mypet_weight2;

$mypet_beauty_exp = $_POST['beauty_exp'];
$mypet_vaccination = $_POST['vaccination'];
$mypet_bite = $_POST['bite'];

$mypet_luxation = $_POST['luxation'];
$mypet_etc_for_owner = $_POST['etc_for_owner'];
$mypet_etc_for_owner = addslashes($mypet_etc_for_owner);
$flag = $_POST['flag'];

$mypet_seq = 0;

//----- [싫어하는 부위] 체크 목록
 $array_check_dt = $_POST['check_dt'];
 $cnt_check_dt = sizeof($array_check_dt);
$where_check_dt = "";
 for ($i = 0; $i < $cnt_check_dt; $i++) {
     $where_check_dt .= $array_check_dt[$i] . "=1, ";
 }

//----- [특이사항] 체크 목록
 $array_check_special = $_POST['special'];
 $cnt_check_special = count($array_check_special);
$where_check_special = "";
 for ($i = 0; $i < $cnt_check_special; $i++) {
     $where_check_special .= $array_check_special[$i] . "=1, ";
 }

$s_sql = "SELECT * from tb_mypet WHERE customer_id = '{$user_id}' AND pet_seq = '{$pet_seq}';";
$s_result = mysqli_query($connection, $s_sql);
$s_rows = mysqli_fetch_object($s_result);
if (isset($s_rows)) {

    $init_sql =
        "UPDATE tb_mypet SET dt_eye = 0, dt_nose = 0, dt_mouth = 0, dt_ear = 0, dt_neck = 0, dt_body = 0, dt_leg = 0, dt_tail = 0, dt_genitalia = 0, nothing = 0,
                dermatosis = 0, heart_trouble = 0, marking = 0, mounting = 0 
        WHERE customer_id = '{$user_id}' 
        AND pet_seq = '{$pet_seq}';";
    // error_log('----- $init_sql : ' . $init_sql);
    $init_result = mysqli_query($connection, $init_sql);
    $mypet_seq = $s_rows->pet_seq;
    $sql = "UPDATE tb_mypet SET 
                name = '{$mypet_name}',
                type = '{$pet_type}', 
                pet_type = '{$mypet_type}', 
                pet_type2 = '{$mypet_type2}', 
                year = {$mypet_b_year}, 
                month = {$mypet_b_month}, 
                day = {$mypet_b_day}, 
                gender = '{$pet_gender}', 
                neutral = '{$mypet_neutral}', 
                weight = '{$mypet_weight}', 
                beauty_exp = '{$mypet_beauty_exp}', 
                vaccination = '{$mypet_vaccination}', 
                bite = '{$mypet_bite}', 
                luxation = '{$mypet_luxation}', 
                {$where_check_dt}
                {$where_check_special}
                etc_for_owner = '{$mypet_etc_for_owner}'
            WHERE customer_id = '{$user_id}' 
            AND pet_seq = '{$pet_seq}';";
    // error_log('----- $sql : ' . $sql);
    $result = mysqli_query($connection, $sql);
    $result_array = array("result" => "ok","mypet_seq" => $sql);
    echo json_encode($result_array);
} else {
    $sql = "INSERT INTO tb_mypet (
                customer_id, 
                name, 
                name_for_owner, 
                type, 
                pet_type, 
                pet_type2, 
                year, 
                month, 
                day, 
                gender, 
                neutral, 
                weight, 
                beauty_exp, 
                vaccination, 
                bite, 
                luxation, 
                etc_for_owner,
                enable_flag
            ) values (
                '".$user_id."', 
                '".$mypet_name."', 
                '".$mypet_name."', 
                '".$pet_type."', 
                '".$mypet_type."', 
                '".$mypet_type2."', 
                ".$mypet_b_year.", 
                ".$mypet_b_month.", 
                ".$mypet_b_day.", 
                '".$pet_gender."', 
                '".$mypet_neutral."', 
                '".$mypet_weight."', 
                '".$mypet_beauty_exp."', 
                '".$mypet_vaccination."', 
                '".$mypet_bite."', 
                '".$mypet_luxation."', 
                '".$mypet_etc_for_owner."',
                1
            );";
    //error_log('----- $sql : ' . $sql);
	error_log('----- $sql : ' . $sql, 3, "/var/www/html/debug.log");
    $result = mysqli_query($connection, $sql);
    $mypet_seq = mysqli_insert_id($connection);
    $after_sql = "UPDATE tb_mypet SET 
                        {$where_check_dt}
                        {$where_check_special}
                        etc_for_owner = '{$mypet_etc_for_owner}'
                    WHERE pet_seq = '{$mypet_seq}';";
    //error_log('----- $after_sql : ' . $after_sql);
    $after_result = mysqli_query($connection, $after_sql);
    $result_array = array("result" => "ok","mypet_seq" => $sql);
    echo json_encode($result_array);
}
?>
