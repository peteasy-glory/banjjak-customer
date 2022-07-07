<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$return_data = array("code" => "999999", "data" => "잘못된경로입니다.");
$mode = isset($_POST['mode']) ? $_POST['mode'] : "";

$data = array();
if(isset($mode)) {
    if($mode == "worker") {

        $artist_id = isset($_POST['artist_id']) ? $_POST['artist_id'] : "";
        $selectDate = isset($_POST['selectDate']) ? $_POST['selectDate'] : "";

        $week = date('w', strtotime($selectDate));

        // 해당요일 근무하는 미용사 구하기
        $data = array();
        $sql = "SELECT * FROM tb_artist_list WHERE artist_id='{$artist_id}' AND WEEK='{$week}' AND is_out = '2' AND is_view = '2' ORDER BY is_main desc";
        $result = mysqli_query($connection, $sql);
        while ($datas = mysqli_fetch_object($result)) {
            $data[] = $datas;
        }
        $return_data = array("code" => "000000", "data" => $data);
    }else if($mode == "artist_holiday") {
        $artist_id = isset($_POST['artist_id']) ? $_POST['artist_id'] : "";
        $name = isset($_POST['name']) ? $_POST['name'] : "";

        $sql = "SELECT * FROM tb_artist_list WHERE artist_id='{$artist_id}' and name = '{$name}' AND is_out = '2' AND is_view = '2' ORDER BY week";
        $result = mysqli_query($connection, $sql);
        while ($datas = mysqli_fetch_object($result)) {
            $data[] = $datas;
        }
        $return_data = array("code" => "000000", "data" => $data);
    }else if($mode == "artist_time"){
        $artist_id = isset($_POST['artist_id']) ? $_POST['artist_id'] : "";
        $worker = isset($_POST['worker']) ? $_POST['worker'] : "";
        $selectDate = isset($_POST['selectDate']) ? $_POST['selectDate'] : "";

        $year = date('Y', strtotime($selectDate));
        $month = date('n', strtotime($selectDate));
        $day = date('j', strtotime($selectDate));

        // 당일 예약미용, 휴무, 타인의 앱에약중
        $sql = "
            SELECT hour, minute, to_hour, to_minute
            FROM tb_payment_log WHERE artist_id = '".$artist_id."' AND worker = '".$worker."' AND is_cancel = 0 
            AND YEAR = ".$year." AND MONTH = ".$month." AND DAY = ".$day."
            
            UNION
            
            SELECT start_hour hour, start_minute minute, end_hour to_hour, end_minute to_minute
            FROM tb_private_holiday WHERE customer_id = '".$artist_id."' AND worker = '".$worker."'
            AND start_year = ".$year." AND start_month = ".$month." AND start_day = ".$day."
            
            UNION
            
            SELECT DATE_FORMAT(str_to_date(CONCAT(HOUR,' : ',MINUTE), '%H:%i'), '%H') AS hour,
            DATE_FORMAT(str_to_date(CONCAT(HOUR,' : ',MINUTE), '%H:%i'), '%i') AS minute,
            date_format(DATE_ADD(str_to_date(CONCAT(HOUR,' : ',MINUTE), '%H:%i'), INTERVAL rowspan*30 minute), '%H') AS to_hour,
            date_format(DATE_ADD(str_to_date(CONCAT(HOUR,' : ',MINUTE), '%H:%i'), INTERVAL rowspan*30 minute), '%i') AS to_minute
            FROM tb_reservation WHERE artist_id = '".$artist_id."' AND worker = '".$worker."'
            AND YEAR = ".$year." AND MONTH = ".$month." AND DAY = ".$day."
        ";
        $result = mysqli_query($connection, $sql);
        while ($datas = mysqli_fetch_object($result)) {
            $data[] = $datas;
        }
        $return_data = array("code" => "000000", "data" => $data);

    // 타임제일 경우
    }else if($mode == "time_schedule") {
        $artist_id = isset($_POST['artist_id']) ? $_POST['artist_id'] : "";
        $worker = isset($_POST['worker']) ? $_POST['worker'] : "";

        $sql = "SELECT * FROM tb_time_schedule WHERE artist_id='{$artist_id}' and artist_name = '{$worker}'";
        $result = mysqli_query($connection, $sql);
        while ($datas = mysqli_fetch_object($result)) {
            $data[] = $datas;
        }
        $return_data = array("code" => "000000", "data" => $data);

    // 휴무시간
    }else if($mode == "rest_time") {
        $artist_id = isset($_POST['artist_id']) ? $_POST['artist_id'] : "";
        $worker = isset($_POST['worker']) ? $_POST['worker'] : "";

        $sql = "SELECT * FROM tb_time_off WHERE customer_id='{$artist_id}'";
        $result = mysqli_query($connection, $sql);
        while ($datas = mysqli_fetch_object($result)) {
            $data[] = $datas;
        }
        $return_data = array("code" => "000000", "data" => $data);

    }else if($mode == "empty_time"){
        $mgr_idx = isset($_POST['mgr_idx']) ? $_POST['mgr_idx'] : "";
        $worker = isset($_POST['worker']) ? $_POST['worker'] : "";
        $selectDate = isset($_POST['selectDate']) ? $_POST['selectDate'] : "";


        // 당일 예약미용, 휴무, 타인의 앱에약중
        $sql = "
            SELECT 
                TIME_FORMAT(st_date, '%H') hour,
                TIME_FORMAT(st_date, '%i') minute,
                TIME_FORMAT(fi_date, '%H') to_hour,
                TIME_FORMAT(fi_date, '%i') to_minute
            FROM tb_sale_free_time_product WHERE mgr_idx = ".$mgr_idx." AND worker = '".$worker."'
            AND DATE_FORMAT(st_date, '%Y-%m-%d') = DATE_FORMAT('".$selectDate."', '%Y-%m-%d')
        ";
        $result = mysqli_query($connection, $sql);
        while ($datas = mysqli_fetch_object($result)) {
            $data[] = $datas;
        }
        $return_data = array("code" => "000000", "data" => $data);
    }
}



echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>