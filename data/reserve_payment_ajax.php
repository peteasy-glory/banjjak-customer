<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");

$mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";
$data = array();

if($mode){
    // 중복결제확인
    if($mode == "get_payment_chk") {
        $user_id = $_SESSION['gobeauty_user_id'];
        $artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
        $order_id = ($_POST["order_id"] && $_POST["order_id"] != "")? $_POST["order_id"] : "";
        $worker = ($_POST["worker"] && $_POST["worker"] != "")? $_POST["worker"] : "";
        $year = ($_POST["year"] && $_POST["year"] != "")? $_POST["year"] : "";
        $month = ($_POST["month"] && $_POST["month"] != "")? $_POST["month"] : "";
        $day = ($_POST["day"] && $_POST["day"] != "")? $_POST["day"] : "";
        $hour = ($_POST["hour"] && $_POST["hour"] != "")? $_POST["hour"] : "";
        $minute = ($_POST["minute"] && $_POST["minute"] != "")? $_POST["minute"] : "";
        $cellphone = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";

        $sql = "
            SELECT * FROM tb_payment_log WHERE customer_id = '".$user_id."' AND artist_id = '".$artist_id."' AND order_id = '".$order_id."'
            AND worker = '".$worker."' AND year = ".$year." AND month = ".$month." AND day = ".$day." AND is_cancel = 0 
            AND hour = ".$hour." AND minute = ".$minute." AND cellphone = '".$cellphone."'
        ";
        $result = mysqli_query($connection, $sql);
        $cnt = mysqli_num_rows($result);

        $return_data = array("code" => "000000", "data" => $cnt);

    // 매장결제
    }else if($mode == "payment_shop"){
//        if($_SESSION['gobeauty_order_process'] != "1"){ // 20210216 ulmo 뒤로가기 예외처리
            $user_id = $_SESSION['gobeauty_user_id'];
            $order_id = ($_POST["order_id"] && $_POST["order_id"] != "")? $_POST["order_id"] : "";
            $pet_seq = ($_POST["pet_seq"] && $_POST["pet_seq"] != "")? $_POST["pet_seq"] : "";
            $artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
            $is_vat = ($_POST["is_vat"] && $_POST["is_vat"] != "")? $_POST["is_vat"] : 0;
            $worker = ($_POST["worker"] && $_POST["worker"] != "")? $_POST["worker"] : "";
            $year = ($_POST["year"] && $_POST["year"] != "")? $_POST["year"] : "";
            $month = ($_POST["month"] && $_POST["month"] != "")? $_POST["month"] : "";
            $day = ($_POST["day"] && $_POST["day"] != "")? $_POST["day"] : "";
            $hour = ($_POST["hour"] && $_POST["hour"] != "")? $_POST["hour"] : "";
            $to_hour = ($_POST["to_hour"] && $_POST["to_hour"] != "")? $_POST["to_hour"] : "";
            $minute = ($_POST["minute"] && $_POST["minute"] != "")? $_POST["minute"] : "";
            $to_minute = ($_POST["to_minute"] && $_POST["to_minute"] != "")? $_POST["to_minute"] : "";
            $product = ($_POST["product"] && $_POST["product"] != "")? $_POST["product"] : "";
            $cellphone = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";
            $total_price = ($_POST["total_price"] && $_POST["total_price"] != "")? $_POST["total_price"] : "";

//            $_SESSION['gobeauty_order_process'] = "1"; // 정상진행 flag


            if($artist_id != "" && $cellphone != ""){
                $sql = "INSERT INTO `tb_payment_log` (
                        pet_seq, is_vat, go_2_offline, is_only_point, local_price,
                        session_id, order_id, artist_id, worker, customer_id, status, 
                        year, month, day, hour, to_hour, minute , to_minute , is_able_to_cancel, 
                        product, cellphone, pay_type, per_diem, update_time, buy_time
                    ) values (
                        ".$pet_seq.", ".$is_vat.", 1, 0, '".$total_price."',
                        '".$sessionid."', '".$order_id."','".$artist_id."','".$worker."', '".$user_id."', 'OR', 
                        ".$year.",".$month.",".$day.", ".$hour.",'".$to_hour."',".$minute.",'".$to_minute."', 0,
                        '".$product."', '".$cellphone."', 'offline-card', 0, now(), now()
                    )";
                $result = mysqli_query($connection, $sql);
                $seq = mysqli_insert_id($connection);

                // 회원 등급 확인
                $sql1 = "
                    SELECT a.idx, b.grade_ord FROM tb_grade_of_customer a
                    INNER JOIN tb_grade_of_shop b ON a.grade_idx = b.idx
                    WHERE a.customer_id = '".$user_id."'
                    AND b.artist_id = '".$artist_id."'
                ";
                $result1 = mysqli_query($connection, $sql1);
                $datas = mysqli_fetch_object($result1);
                $grade_ord = $datas->grade_ord;
                $customer_idx = $datas->idx;
                if($grade_ord != 1){
                    $sql2 = "INSERT INTO `tb_grade_reserve_approval_mgr` (`payment_log_seq`, `grade_customer_idx`, `is_approve`, `mod_date`, `reg_date`, `is_delete`) VALUES (".$seq.", ".$customer_idx.", 0, NOW(), NOW(), 0);";
                    $result2 = mysqli_query($connection, $sql2);
                }

                if ($result === true){ // success
                    $return_data = array("code" => "000000", "data" => "ok", "seq" => $seq);
                }else{ // fail
                    $return_data = array("code" => "000000", "data" => "fail");
                }
            }else{ // fail
                $return_data = array("code" => "000000", "data" => "fail");
            }
//        }else{ // mainpage
//
//        }

    // 무통장입금
    }else if($mode == "payment_bank"){

        $user_id = $_SESSION['gobeauty_user_id'];
        $order_id = ($_POST["order_id"] && $_POST["order_id"] != "")? $_POST["order_id"] : "";
        $pet_seq = ($_POST["pet_seq"] && $_POST["pet_seq"] != "")? $_POST["pet_seq"] : "";
        $artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
        $is_vat = ($_POST["is_vat"] && $_POST["is_vat"] != "")? $_POST["is_vat"] : 0;
        $worker = ($_POST["worker"] && $_POST["worker"] != "")? $_POST["worker"] : "";
        $year = ($_POST["year"] && $_POST["year"] != "")? $_POST["year"] : "";
        $month = ($_POST["month"] && $_POST["month"] != "")? $_POST["month"] : "";
        $day = ($_POST["day"] && $_POST["day"] != "")? $_POST["day"] : "";
        $hour = ($_POST["hour"] && $_POST["hour"] != "")? $_POST["hour"] : "";
        $to_hour = ($_POST["to_hour"] && $_POST["to_hour"] != "")? $_POST["to_hour"] : "";
        $minute = ($_POST["minute"] && $_POST["minute"] != "")? $_POST["minute"] : "";
        $to_minute = ($_POST["to_minute"] && $_POST["to_minute"] != "")? $_POST["to_minute"] : "";
        $product = ($_POST["product"] && $_POST["product"] != "")? $_POST["product"] : "";
        $cellphone = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";
        $total_price = ($_POST["total_price"] && $_POST["total_price"] != "")? $_POST["total_price"] : "";
        $bank = ($_POST["bank"] && $_POST["bank"] != "")? $_POST["bank"] : "";
        $expire_time = ($_POST["expire_time"] && $_POST["expire_time"] != "")? $_POST["expire_time"] : "";


        if($artist_id != "" && $cellphone != ""){
            $sql = "INSERT INTO `tb_payment_log` (
                        pet_seq, is_vat, go_2_offline, is_only_point, total_price, bank,
                        session_id, order_id, artist_id, worker, customer_id, status, 
                        year, month, day, hour, to_hour, minute , to_minute , is_able_to_cancel, 
                        product, cellphone, pay_type, per_diem, update_time, buy_time, expire_time
                    ) values (
                        ".$pet_seq.", ".$is_vat.", 1, 0, '".$total_price."', '".$bank."', 
                        '".$sessionid."', '".$order_id."','".$artist_id."','".$worker."', '".$user_id."', 'BR', 
                        ".$year.",".$month.",".$day.", ".$hour.",'".$to_hour."',".$minute.",'".$to_minute."', 0,
                        '".$product."', '".$cellphone."', 'bank', 0, now(), now(), '".$expire_time."'
                    )";
            $result = mysqli_query($connection, $sql);
            $seq = mysqli_insert_id($connection);



            // 알림톡 발송 / PUSH 발송
            $artist_name = explode("|", $product);
            $artist_name = $artist_name[2];
            $path = "https://www.gopet.kr/pet/shop/manage_sell_info.php?yy=".$year."&mm=".$month."&dd=".$day;
            //$image = "https://www.gopet.kr/pet/images/logo_login.jpg";
            $image = "";
            $admin_message = $user_id."가 펫샵(".$artist_id." | ".$artist_name.")에 예약(계좌이체 결제 진행중)하였습니다. ".$year."년".$month."월".$day."일 신규 예약등록. 작업스케줄을 관리하세요.";
            a_push("pickmon@pickmon.com", "반짝, 반려생활의 단짝. 신규 예약 알림", $admin_message, $path, $image);

            if ($result === true){ // success
                $return_data = array("code" => "000000", "data" => "ok", "seq" => $seq);
            }else{ // fail
                $return_data = array("code" => "000000", "data" => "fail");
            }
        }else{ // fail
            $return_data = array("code" => "000000", "data" => "fail");
        }

    // 카드결제
    }else if($mode == "payment_card"){

        $user_id = $_SESSION['gobeauty_user_id'];
        $order_id = ($_POST["order_id"] && $_POST["order_id"] != "")? $_POST["order_id"] : "";
        $pet_seq = ($_POST["pet_seq"] && $_POST["pet_seq"] != "")? $_POST["pet_seq"] : "";
        $artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
        $is_vat = ($_POST["is_vat"] && $_POST["is_vat"] != "")? $_POST["is_vat"] : 0;
        $worker = ($_POST["worker"] && $_POST["worker"] != "")? $_POST["worker"] : "";
        $year = ($_POST["year"] && $_POST["year"] != "")? $_POST["year"] : "";
        $month = ($_POST["month"] && $_POST["month"] != "")? $_POST["month"] : "";
        $day = ($_POST["day"] && $_POST["day"] != "")? $_POST["day"] : "";
        $hour = ($_POST["hour"] && $_POST["hour"] != "")? $_POST["hour"] : "";
        $to_hour = ($_POST["to_hour"] && $_POST["to_hour"] != "")? $_POST["to_hour"] : "";
        $minute = ($_POST["minute"] && $_POST["minute"] != "")? $_POST["minute"] : "";
        $to_minute = ($_POST["to_minute"] && $_POST["to_minute"] != "")? $_POST["to_minute"] : "";
        $product = ($_POST["product"] && $_POST["product"] != "")? $_POST["product"] : "";
        $cellphone = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";
        $total_price = ($_POST["total_price"] && $_POST["total_price"] != "")? $_POST["total_price"] : "";


        if($artist_id != "" && $cellphone != ""){
            $sql = "INSERT INTO `tb_payment_log` (
                        pet_seq, is_vat, go_2_offline, is_only_point, total_price,
                        session_id, order_id, artist_id, worker, customer_id, status, 
                        year, month, day, hour, to_hour, minute , to_minute , is_cancel, 
                        product, cellphone, pay_type, per_diem, update_time, buy_time
                    ) values (
                        ".$pet_seq.", ".$is_vat.", 1, 0, '".$total_price."', 
                        '".$sessionid."', '".$order_id."','".$artist_id."','".$worker."', '".$user_id."', 'R0', 
                        ".$year.",".$month.",".$day.", ".$hour.",'".$to_hour."',".$minute.",'".$to_minute."', 1,
                        '".$product."', '".$cellphone."', 'card', 0, now(), now()
                    )";
            $result = mysqli_query($connection, $sql);
            $seq = mysqli_insert_id($connection);


            if ($result === true){ // success
                $return_data = array("code" => "000000", "data" => "ok", "seq" => $seq);
            }else{ // fail
                $return_data = array("code" => "000000", "data" => "fail");
            }
        }else{ // fail
            $return_data = array("code" => "000000", "data" => "fail");
        }

    }else{
        $return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
    }
}else{
    $return_data = array("code" => "999998", "data" => "올바르지 않은 접근입니다.");
}

echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>