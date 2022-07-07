<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login.php");
include($_SERVER['DOCUMENT_ROOT']."/include/skin/header.php");
include($_SERVER['DOCUMENT_ROOT']."/data/get_artist_id.php");
// include "../include/check_header_log.php";

$user_id = $_SESSION['gobeauty_user_id'];
$artist_id = $_REQUEST['artist_id'];
$payment_log_seq = $_REQUEST['payment_log_seq'];
$user_id = $_SESSION['gobeauty_user_id'];

$is_only_point = 0;
$pay_type = 'card';
$receipt_id = "";
$spend_point = "";
$year = 0;
$month = 0;
$day = 0;
$hour = 0;
$s_sql = "SELECT * 
FROM tb_payment_log 
WHERE payment_log_seq = '" . $payment_log_seq . "' 
AND artist_id = '" . $artist_id . "' 
AND is_cancel = 0;";
$s_result = mysqli_query($connection,$s_sql);
if ($s_rows = mysqli_fetch_object($s_result)) {
    $is_only_point = $s_rows->is_only_point;
    $pay_type = $s_rows->pay_type;
    $receipt_id = $s_rows->receipt_id;
    $order_id = $s_rows->order_id;
    $total_point = $s_rows->total_point;
    $spend_point = $s_rows->spend_point;
    $year = $s_rows->year;
    $month = $s_rows->month;
    $day = $s_rows->day;
    $hour = $s_rows->hour;
    $pg_log = $s_rows->pg_log;
    $customer_cellphone = $s_rows->cellphone;
    $pet_seq = $s_rows->pet_seq;
} else {
    return "false";
}

?>
<script>
    $("#loading").addClass('actived');
</script>
<?php

$is_p_ok_2 = 1;
if ($is_only_point == 0 && $pay_type == 'card') {
    //	$receipt_id = '5ad861c0ed32b362cd341102';
    //	$application_id = '5acc2185b6d49c7b637d9c12';
    //	$private_key = 'oZq//0OpaSulB2uzNU8l7mQGgQpePEmpihnUb5TuAvA=';

    $pg_log_price = 0;
    if(strpos($pg_log, "&") !== false){
        $pg_log = explode("&", $pg_log);
        foreach($pg_log AS $key => $value){
            $tmp_var = explode("=", $value);
            if($tmp_var[0] == "P_AMT"){
                $pg_log_price = $tmp_var[1];
            }
        }
    }

    $tid = $receipt_id;
    $msg = "견주가 직접 예약취소";
    //$price = $total_point - round($spend_point);
    //$price = ($pg_log['data']['price'] != "")? $pg_log['data']['price'] : "0";
    $price = $pg_log_price;
    $confirmPrice = "0";

    $return_data = INI_PartialRefund($tid, $msg, $price, $confirmPrice);

    if($return_data["code"] == "000000"){
        $csql =
            "UPDATE tb_payment_log 
        SET is_done_for_cancel_card = 1, 
        update_time = NOW() 
        WHERE payment_log_seq = '" . $payment_log_seq . "' 
        AND artist_id = '" . $artist_id . "';";

        $cresult = mysqli_query($connection,$csql);
    }else{
        ?>
        <script>
            alert("카드사 에러로 결제 취소가 안되고 있습니다.<br>(고객센터로 전화 부탁드립니다.)");
            location.href = '../mypage_reserve_list';
        </script>
        <?php
        //include "../include/bottom.php";
        return false;
    }

    /*
    $headers = array('Content-Type: application/json');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.bootpay.co.kr/cancel');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'application_id' => $rest_application_id,
        'private_key' => $rest_private_key,
        'receipt_id' => $receipt_id,
        'name' => '관리자 양원일',
        'reason' => '테스트로인한 취소'
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    //echo "response : ".$response."<br>";

    $response_json = json_decode($response);
    if ($response_json->{'status'} == '200') {
        $csql =
            "UPDATE tb_payment_log
        SET is_done_for_cancel_card = 1,
        update_time = NOW()
        WHERE payment_log_seq = '" . $payment_log_seq . "'
        AND artist_id = '" . $artist_id . "';";

        $cresult = mysql_query($csql);
    } else {
        $is_p_ok_2 = 0;
        ?>
        <script>
            $.MessageBox({
                buttonDone: "확인",
                message: "카드사 에러로 결제 취소가 안되고 있습니다.<br>(고객센터로 전화 부탁드립니다.)"
            }).done(function() {
                location.href = 'manage_my_reservation.php';
            });
        </script>
<?php
        include "../include/bottom.php";
        return false;
    }
    */
}

// 사용한 포인트 돌려주기
$point = new Point;
if (intval($spend_point) > 0) {
    $sp_sql =
        "SELECT * 
    FROM tb_point_history 
    WHERE payment_log_seq = '" . $payment_log_seq . "' 
    AND customer_id = '" . $user_id . "' 
    AND type = 'SPEND';";
    $sp_result = mysqli_query($connection,$sp_sql);
    if ($sp_rows = mysqli_fetch_object($sp_result)) {
        $spending_accumulate_point = intval($sp_rows->spending_accumulate_point);
        $spending_purchase_point = intval($sp_rows->spending_purchase_point);

        $result = $point->select_point($user_id);
        if ($spending_accumulate_point > 0) {
            $point->add_accumulate_point($spending_accumulate_point, $payment_log_seq, "cancel_" . $order_id);
        }
        if ($spending_purchase_point > 0) {
            $point->add_purchase_point($spending_purchase_point, $payment_log_seq, $order_id);
        }
        $csql = "UPDATE tb_payment_log 
        SET is_done_for_return_point = 1, update_time = NOW() 
        WHERE payment_log_seq = '" . $payment_log_seq . "' 
        AND artist_id = '" . $artist_id . "';";
        // error_log('----- $csql222 : ' . $csql);
        $cresult = mysqli_query($connection,$csql);
    }
}

//if ($is_only_point == 0 && $pay_type == 'card') {
// 적립한 포인트 회수
$load_result = $point->select_point($user_id);
if ($load_result) {
    $point->cancel_accumulate_new($payment_log_seq, $order_id);
}
//}

// 예약 취소하기
$sql = "UPDATE tb_payment_log SET is_cancel = 1, cancel_time = NOW() WHERE payment_log_seq = '" . $payment_log_seq . "' AND artist_id = '" . $artist_id . "';";
$result = mysqli_query($connection,$sql);

$grade_sql = "SELECT * FROM tb_grade_reserve_approval_mgr WHERE payment_log_seq = {$payment_log_seq};";
$grade_result = mysqli_query($connection, $grade_sql);
$grade_cnt = mysqli_num_rows($grade_result);
if($grade_cnt > 0){
    $grade_update_sql = "
        UPDATE tb_grade_reserve_approval_mgr SET
        is_approve = 4,
        mod_date = NOW()
        WHERE payment_log_seq = {$payment_log_seq}
    ";
    $grade_update_result = mysqli_query($connection, $grade_update_sql);
}

if ($result) {
    //echo "success";

    if ($artist_id != null && $artist_id != "") {
        $message = $year . "년" . $month . "월" . $day . "일 예약취소. 예약내용을 확인하세요.";
        $path = "https://partner.banjjakpet.com/customer_view?customer_cellphone=".$customer_cellphone."&pet_seq=".$pet_seq;
        //$image = "http://gopet.kr/pet/images/logo_login.jpg";
        $image = "";
        a_push($artist_id, "반짝, 반려생활의 단짝. 예약취소 알림", $message, $path, $image);

        $admin_message = $user_id . "가 펫샵 ".get_artist_id($artist_id)."(" . $artist_id . ")의 " . $year . "년" . $month . "월" . $day . "일 예약취소.";
        a_push("pickmon@pickmon.com", "반짝, 반려생활의 단짝. 예약취소 알림", $admin_message, $path, $image);
    }
    ?>
    <script>
        location.href = '../mypage_reserve_list?type=cancel';
    </script>
    <?php
} else {
    //echo "fail";
    ?>
    <script>
        alert('에약취소에 실패했습니다.')
        location.href = '../mypage_reserve_list';
    </script>
    <?php
}

function INI_PartialRefund($tid, $msg, $price, $confirmPrice){
    // $P_REQ_URL = "https://stginiapi.inicis.com/api/v1/refund"; // test
    $P_REQ_URL = "https://iniapi.inicis.com/api/v1/refund"; // live
    // IV = "r9o5yPICNELvqY=="; // live

    // $KEY = "ItEQKi3rY7uvDS8l"; // test
    $KEY = "dTHUN5hZi8Fx9KTO"; // live

    $type = "PartialRefund";
    $paymethod = "Card";
    $timestamp = DATE("YmdHis");
    $clientIp = "175.126.123.165";
    //$mid = "INIpayTest"; // test
    $mid = "banjjak001"; // live

    $hashData = hash('sha512', $KEY.$type.$paymethod.$timestamp.$clientIp.$mid.$tid.$price.$confirmPrice);
    //echo $KEY.$type.$paymethod.$timestamp.$clientIp.$mid.$tid.$price.$confirmPrice."<br/>";
    //echo $hashData."<br/>";

    $post_data  = "type=".$type;
    $post_data .= "&paymethod=".$paymethod;
    $post_data .= "&timestamp=".$timestamp;
    $post_data .= "&clientIp=".$clientIp;
    $post_data .= "&mid=".$mid;
    $post_data .= "&tid=".$tid;
    $post_data .= "&msg=".$msg;
    $post_data .= "&price=".$price;
    $post_data .= "&confirmPrice=".$confirmPrice;
    $post_data .= "&hashData=".$hashData;
    //echo $post_data."<br/>";
    $headers = array('Content-Type: application/x-www-form-urlencoded;charset=utf-8');

    $ch = curl_init(); //curl 사용 전 초기화 필수(curl handle)

    curl_setopt($ch, CURLOPT_URL, $P_REQ_URL);			// URL 지정하기
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		// 결과를 노출(0-print, 1-변수저장)
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	// https ssl 인증서 확인 하지 않도록 함
//    curl_setopt($ch, CURLOPT_SSLVERSION,3);				// 주소가 https가 아니라면 지울것
//    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);		// header 정보 전달
//
//    curl_setopt($ch, CURLOPT_POST, 1);					// 0이 default 값이며 POST 통신을 위해 1로 설정해야 함
//    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);	// POST로 보낼 데이터 지정하기

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);     //connection timeout 10초
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);           //원격 서버의 인증서가 유효한지 검사 안함
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);    //POST 로 $data 를 보냄
    curl_setopt($ch, CURLOPT_POST, 1);

    $response = curl_exec($ch);

    //echo '>> result << <br/>';
    $tmp_arr = json_decode($response, true); //결과값 확인하기
    //echo '<br/><br/>';
    //print_r(curl_getinfo($ch));//마지막 http 전송 정보 출력
    if(curl_errno($ch)){ //마지막 에러 번호 출력
        $tmp_err = "curl_error: ".curl_error($ch)."(".curl_errno($ch).")";//현재 세션의 마지막 에러 출력
    }
    curl_close($ch);

    if($tmp_err == ""){
        if($tmp_arr["resultCode"] == "00"){ // 정상 취소
            return array("code" => "000000", "data" => $tmp_arr);
        }else{
            return array("code" => "002401", "data" => $tmp_arr["resultMsg"]." ( ".$tmp_arr["resultCode"]." ) ");
            //print_r($tmp_arr);
        }
    }else{
        return array("code" => "002402", "data" => $tmp_err);
    }
}

closeDB();
//include "../include/bottom.php";
?>
<script>
    location.href = '../mypage_reserve_list?type=cancel';
</script>