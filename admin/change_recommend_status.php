<?php
include "../include/configure.php";
include "../include/db_connection.php";
include "../include/session.php";
include "../include/check_header_log.php";
?>

<?php
$status = $_REQUEST['type'];
$customer_id = $_REQUEST['customer_id'];

if ($status == '1') {
    $sql = "update tb_shop set is_recommend = 0 where customer_id = '" . $customer_id . "';";
    $result = mysql_query($sql);
    if (mysql_affected_rows() > 0) {
        echo "추천 펫샵에서 제외하였습니다.";
    } else {
        echo "추천 펫샵 제외에 실패하였습니다.";
    }
} else {
    $sql = "update tb_shop set is_recommend = 1 where customer_id = '" . $customer_id . "';";
    $result = mysql_query($sql);
    if (mysql_affected_rows() > 0) {
        echo "추천 펫샵으로 설정하였습니다.";
    } else {
        echo "추천 펫샵 설정에 실패하였습니다.";
    }
}
?>