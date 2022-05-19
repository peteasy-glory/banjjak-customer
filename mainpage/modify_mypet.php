<?php
include "../include/top.php";
// include "../include/check_header_log.php";

$user_id = $_SESSION['gobeauty_user_id'];
$before_mypet_name = $_REQUEST['before_mypet_name'];
$mypet_name = $_REQUEST['mypet_name'];
$mypet_name = trim($mypet_name);
$pet_type = $_REQUEST['pet_kind'];
$mypet_type = $_REQUEST['mypet_type'];
$mypet_type2 = $_REQUEST['mypet_type2'];
$mypet_b_year = $_REQUEST['mypet_b_year'];
$mypet_b_month = $_REQUEST['mypet_b_month'];
$mypet_b_day = $_REQUEST['mypet_b_day'];
$pet_gender = $_REQUEST['gender'];
$mypet_neutral = $_REQUEST['neutral'];

$mypet_weight1 = $_REQUEST['mypet_weight1'];
$mypet_weight2 = $_REQUEST['mypet_weight2'];
if (trim($mypet_type) != '기타') {
    $mypet_type2 = "";
}
$mypet_weight = $mypet_weight1 . "." . $mypet_weight2;

$mypet_beauty_exp = $_REQUEST['beauty_exp'];
$mypet_vaccination = $_REQUEST['vaccination'];
$mypet_bite = $_REQUEST['bite'];

$mypet_luxation = $_REQUEST['luxation'];
$mypet_etc_for_owner = $_REQUEST['etc_for_owner'];
$mypet_etc_for_owner = addslashes($mypet_etc_for_owner);
$flag = $_REQUEST['flag'];

$mypet_seq = 0;

//----- [싫어하는 부위] 체크 목록
$array_check_dt = $_REQUEST['check_dt'];
$cnt_check_dt = sizeof($array_check_dt);
$where_check_dt = "";
for ($i = 0; $i < $cnt_check_dt; $i++) {
    $where_check_dt .= $array_check_dt[$i] . "=1, ";
}

//----- [특이사항] 체크 목록
$array_check_special = $_REQUEST['check_special'];
$cnt_check_special = sizeof($array_check_special);
$where_check_special = "";
for ($i = 0; $i < $cnt_check_special; $i++) {
    $where_check_special .= $array_check_special[$i] . "=1, ";
}

$s_sql = "SELECT * from tb_mypet WHERE customer_id = '{$user_id}' AND name_for_owner = '{$before_mypet_name}';";
$s_result = mysql_query($s_sql);
if ($s_rows = mysql_fetch_object($s_result)) {
    $init_sql = "UPDATE tb_mypet SET dt_eye = 0, dt_nose = 0, dt_mouth = 0, dt_ear = 0, dt_neck = 0, dt_body = 0, dt_leg = 0, dt_tail = 0, dt_genitalia = 0, nothing = 0,
                        dermatosis = 0, heart_trouble = 0, marking = 0, mounting = 0 
                WHERE customer_id = '{$user_id}' 
                AND name_for_owner = '{$before_mypet_name}';";
    // error_log('----- $init_sql : ' . $init_sql);
    $init_result = mysql_query($init_sql);

    $sql = "UPDATE tb_mypet SET 
                name_for_owner = '{$mypet_name}', 
                TYPE = '{$pet_type}', 
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
            AND name_for_owner = '{$before_mypet_name}';";
    // error_log('----- $sql : ' . $sql);
    $result = mysql_query($sql);
}
?>
<?
include "../include/bottom.php";
?>