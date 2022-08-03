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
                $cnt = mysqli_num_rows($result1);
                $grade_ord = $datas->grade_ord;
                $customer_idx = $datas->idx;

                // 회원등급 데이터가 없을때(예약승인으로 넘겨야함)
                if($cnt == 0){
                    // 해당샵 gold 등급 찾아서 넣기
                    $find_sql = "SELECT idx FROM tb_grade_of_shop WHERE artist_id = '".$artist_id."' AND grade_ord = 2";
                    $find_result = mysqli_query($connection, $find_sql);
                    $find_data = mysqli_fetch_object($find_result);
                    $insert_idx = $find_data->idx;

                    $insert_sql = "INSERT INTO `tb_grade_of_customer` (`grade_idx`, `customer_id`, `is_delete`) VALUES (".$insert_idx.", '".$user_id."', 0);";
                    $insert_result = mysqli_query($connection, $insert_sql);
                    $grade_idx = mysqli_insert_id($connection);

                    // 방금 등록한 grade_customer_idx 값 넣기
                    $sql2 = "INSERT INTO `tb_grade_reserve_approval_mgr` (`payment_log_seq`, `grade_customer_idx`, `is_approve`, `mod_date`, `reg_date`, `is_delete`) VALUES (".$seq.", ".$grade_idx.", 0, NOW(), NOW(), 0);";

                    $result2 = mysqli_query($connection, $sql2);
                    $mgr_idx = mysqli_insert_id($connection);

                    $select_sql = "SELECT a.cellphone, b.name pet_name, c.name shop_name FROM tb_payment_log a
                        LEFT JOIN tb_mypet b ON a.pet_seq = b.pet_seq 
                        LEFT JOIN tb_shop c on a.artist_id = c.customer_id 
                        WHERE a.payment_log_seq = {$seq}";
                    $select_result = mysqli_query($connection,$select_sql);
                    $select_datas = mysqli_fetch_object($select_result);
                    $cellphone = $select_datas->cellphone;
                    $pet_name = $select_datas->pet_name;
                    $shop_name = $select_datas->shop_name;

                    $message = "{$cellphone}님({$pet_name})이 {$month}월{$day}일 {$hour}시{$minute}분 예약승인을 요청하였습니다.";
                    $path = "https://partner.banjjakpet.com/reserve_main_day?ch=day&yy={$year}&mm={$month}&dd={$day}";
                    $image = "https://customer.banjjakpet.com/static/pub/images/icon/icon-logo.png";
                    a_push($artist_id, "[반짝][예약승인대기]", $message, $path, $image);

                    // 관리자 푸시
                    $admin_message = "{$cellphone}님({$pet_name})이 ({$shop_name})({$artist_id})에 승인요청";
                    a_push("pickmon@pickmon.com", "[예약승인요청]", $admin_message, "", $image);
                }else{ // 데이터는 있을 때
                    // vip 고객이 아닐경우(예약승인으로 등록)
                    if($grade_ord != 1){

                        $sql2 = "INSERT INTO `tb_grade_reserve_approval_mgr` (`payment_log_seq`, `grade_customer_idx`, `is_approve`, `mod_date`, `reg_date`, `is_delete`) VALUES (".$seq.", ".$customer_idx.", 0, NOW(), NOW(), 0);";

                        $result2 = mysqli_query($connection, $sql2);
                        $mgr_idx = mysqli_insert_id($connection);

                        $select_sql = "SELECT a.cellphone, b.name pet_name, c.name shop_name FROM tb_payment_log a
                        LEFT JOIN tb_mypet b ON a.pet_seq = b.pet_seq 
                        LEFT JOIN tb_shop c on a.artist_id = c.customer_id 
                        WHERE a.payment_log_seq = {$seq}";
                        $select_result = mysqli_query($connection,$select_sql);
                        $select_datas = mysqli_fetch_object($select_result);
                        $cellphone = $select_datas->cellphone;
                        $pet_name = $select_datas->pet_name;
                        $shop_name = $select_datas->shop_name;

                        $message = "{$cellphone}님({$pet_name})이 {$month}월{$day}일 {$hour}시{$minute}분 예약승인을 요청하였습니다.";
                        $path = "https://partner.banjjakpet.com/reserve_main_day?ch=day&yy={$year}&mm={$month}&dd={$day}";
                        $image = "https://customer.banjjakpet.com/static/pub/images/icon/icon-logo.png";
                        a_push($artist_id, "[반짝][예약승인대기]", $message, $path, $image);

                        // 관리자 푸시
                        $admin_message = "{$cellphone}님({$pet_name})이 ({$shop_name})({$artist_id})에 승인요청";
                        a_push("pickmon@pickmon.com", "[예약승인요청]", $admin_message, "", $image);
                    }else{
                        $message = "{$month}월 {$day}일 신규 예약등록. 매장결제를 선택하신 고객입니다. 매장에서 요금을 받으시면 됩니다.작업스케줄을 관리하세요.";
                        $path = "https://partner.banjjakpet.com/reserve_main_day?ch=day&yy=".$year."&mm=".$month."&dd=".$day;
                        //$image = "http://gopet.kr/pet/images/logo_login.jpg";
                        $image = "";

                        a_push($artist_id, "반짝, 반려생활의 단짝. 신규 예약 알림(매장결제)", $message, $path, $image);

                        $select_sql = "SELECT * from tb_shop where customer_id = '".$artist_id."'";
                        $select_result = mysqli_query($connection,$select_sql);
                        $select_datas = mysqli_fetch_object($select_result);
                        $artist_name = $select_datas->name;

                        $admin_message = $user_id . "가 펫샵(" . $artist_id . " | " . $artist_name . ")에 예약(매장결제)을 하였습니다. " . $year . "년" . $month . "월" . $day . "일 신규 예약등록. 작업스케줄을 관리하세요.";
                        a_push("pickmon@pickmon.com", "반짝, 반려생활의 단짝. 신규 예약 알림", $admin_message, $path, $image);
                    }
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
        $spend_point = ($_POST["spend_point"] && $_POST["spend_point"] != "")? $_POST["spend_point"] : ""; // 포인트 사용 금액


        if($artist_id != "" && $cellphone != ""){
            $sql = "INSERT INTO `tb_payment_log` (
                        pet_seq, is_vat, go_2_offline, is_only_point, total_price, bank, spend_point,
                        session_id, order_id, artist_id, worker, customer_id, status, 
                        year, month, day, hour, to_hour, minute , to_minute , is_able_to_cancel, 
                        product, cellphone, pay_type, per_diem, update_time, buy_time, expire_time
                    ) values (
                        ".$pet_seq.", ".$is_vat.", 1, 0, '".$total_price."', '".$bank."', '".$spend_point."', 
                        '".$sessionid."', '".$order_id."','".$artist_id."','".$worker."', '".$user_id."', 'BR', 
                        ".$year.",".$month.",".$day.", ".$hour.",'".$to_hour."',".$minute.",'".$to_minute."', 0,
                        '".$product."', '".$cellphone."', 'bank', 0, now(), now(), '".$expire_time."'
                    )";
            $result = mysqli_query($connection, $sql);
            $seq = mysqli_insert_id($connection);

            // 포인트 처리
            $point = new Point;
            $result = $point->select_point($user_id);
            $point->spend_point($spend_point, $seq, $order_id);

            // 알림톡 발송 / PUSH 발송
            $artist_name = explode("|", $product);
            $artist_name = $artist_name[2];
            $path = "https://partner.banjjakpet.com/reserve_main_day?ch=day&yy=".$year."&mm=".$month."&dd=".$day;
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
    }else if($mode == "payment_card") {

        $user_id = $_SESSION['gobeauty_user_id'];
        $order_id = ($_POST["order_id"] && $_POST["order_id"] != "") ? $_POST["order_id"] : "";
        $pet_seq = ($_POST["pet_seq"] && $_POST["pet_seq"] != "") ? $_POST["pet_seq"] : "";
        $artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "") ? $_POST["artist_id"] : "";
        $is_vat = ($_POST["is_vat"] && $_POST["is_vat"] != "") ? $_POST["is_vat"] : 0;
        $worker = ($_POST["worker"] && $_POST["worker"] != "") ? $_POST["worker"] : "";
        $year = ($_POST["year"] && $_POST["year"] != "") ? $_POST["year"] : "";
        $month = ($_POST["month"] && $_POST["month"] != "") ? $_POST["month"] : "";
        $day = ($_POST["day"] && $_POST["day"] != "") ? $_POST["day"] : "";
        $hour = ($_POST["hour"] && $_POST["hour"] != "") ? $_POST["hour"] : "";
        $to_hour = ($_POST["to_hour"] && $_POST["to_hour"] != "") ? $_POST["to_hour"] : "";
        $minute = ($_POST["minute"] && $_POST["minute"] != "") ? $_POST["minute"] : "";
        $to_minute = ($_POST["to_minute"] && $_POST["to_minute"] != "") ? $_POST["to_minute"] : "";
        $product = ($_POST["product"] && $_POST["product"] != "") ? $_POST["product"] : "";
        $cellphone = ($_POST["cellphone"] && $_POST["cellphone"] != "") ? $_POST["cellphone"] : "";
        $total_price = ($_POST["total_price"] && $_POST["total_price"] != "") ? $_POST["total_price"] : "";
        $spend_point = ($_POST["spend_point"] && $_POST["spend_point"] != "")? $_POST["spend_point"] : ""; // 포인트 사용 금액


        if ($artist_id != "" && $cellphone != "") {
            $sql = "INSERT INTO `tb_payment_log` (
                        pet_seq, is_vat, go_2_offline, is_only_point, total_price, spend_point,
                        session_id, order_id, artist_id, worker, customer_id, status, 
                        year, month, day, hour, to_hour, minute , to_minute , is_cancel, 
                        product, cellphone, pay_type, per_diem, update_time, buy_time
                    ) values (
                        " . $pet_seq . ", " . $is_vat . ", 1, 0, '" . $total_price . "', '".$spend_point."', 
                        '" . $sessionid . "', '" . $order_id . "','" . $artist_id . "','" . $worker . "', '" . $user_id . "', 'R0', 
                        " . $year . "," . $month . "," . $day . ", " . $hour . ",'" . $to_hour . "'," . $minute . ",'" . $to_minute . "', 1,
                        '" . $product . "', '" . $cellphone . "', 'card', 0, now(), now()
                    )";
            $result = mysqli_query($connection, $sql);
            $seq = mysqli_insert_id($connection);

            // 포인트 처리
            $point = new Point;
            $result = $point->select_point($user_id);
            $point->spend_point($spend_point, $seq, $order_id);


            if ($result === true) { // success
                $return_data = array("code" => "000000", "data" => "ok", "seq" => $seq);
            } else { // fail
                $return_data = array("code" => "000000", "data" => "fail");
            }
        } else { // fail
            $return_data = array("code" => "000000", "data" => "fail");
        }

    // 포인트로 결제
    }else if($mode == 'payment_point'){

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
        $expire_time = ($_POST["expire_time"] && $_POST["expire_time"] != "")? $_POST["expire_time"] : "";
        $spend_point = ($_POST["spend_point"] && $_POST["spend_point"] != "")? $_POST["spend_point"] : ""; // 포인트 사용 금액
        $pay_type = ($_POST["pay_type"] && $_POST["pay_type"] != "")? $_POST["pay_type"] : ""; // pay_type



        if($artist_id != "" && $cellphone != ""){
            $sql = "INSERT INTO `tb_payment_log` (
                        pet_seq, is_vat, go_2_offline, is_only_point, total_price, spend_point,
                        session_id, order_id, artist_id, worker, customer_id, status, 
                        year, month, day, hour, to_hour, minute , to_minute , is_able_to_cancel, 
                        product, cellphone, pay_type, per_diem, update_time, buy_time, expire_time
                    ) values (
                        ".$pet_seq.", ".$is_vat.", 1, 1, '".$total_price."',  '".$spend_point."',
                        '".$sessionid."', '".$order_id."','".$artist_id."','".$worker."', '".$user_id."', 'R1', 
                        ".$year.",".$month.",".$day.", ".$hour.",'".$to_hour."',".$minute.",'".$to_minute."', 0,
                        '".$product."', '".$cellphone."', '".$pay_type."', 0, now(), now(), '".$expire_time."'
                    )";
            $result = mysqli_query($connection, $sql);
            $seq = mysqli_insert_id($connection);

            // 포인트 처리
            $point = new Point;
            $result = $point->select_point($user_id);
            $point->spend_point($spend_point, $seq, $order_id);

            // 알림톡 발송 / PUSH 발송
            $artist_name = explode("|", $product);
            $artist_name = $artist_name[2];
            $message = $year."년".$month."월".$day."일 신규 예약등록. 작업스케줄을 관리하세요.";
            $path = "https://partner.banjjakpet.com/reserve_main_day?ch=day&yy=".$year."&mm=".$month."&dd=".$day;
            //$image = "https://www.gopet.kr/pet/images/logo_login.jpg";
            $image = "";
            a_push($artist_id, "반짝, 반려생활의 단짝. 신규 예약 알림", $message, $path, $image);

            $admin_message = $user_id."가 펫샵(".$artist_id." | ".$artist_name.")에 예약하였습니다. ".$year."년".$month."월".$day."일 신규 예약등록. 작업스케줄을 관리하세요.";
            a_push("pickmon@pickmon.com", "반짝, 반려생활의 단짝. 신규 예약 알림(견주앱)", $admin_message, $path, $image);

            if ($result === true){ // success
                $return_data = array("code" => "000000", "data" => "ok", "seq" => $seq);
            }else{ // fail
                $return_data = array("code" => "000000", "data" => "fail");
            }
        }else{ // fail
            $return_data = array("code" => "000000", "data" => "fail");
        }

    // 예약시간변경
    }else if($mode == 'update_date'){

        $user_id = $_SESSION['gobeauty_user_id'];
        $artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
        $worker = ($_POST["worker"] && $_POST["worker"] != "")? $_POST["worker"] : "";
        $year = ($_POST["year"] && $_POST["year"] != "")? $_POST["year"] : "";
        $month = ($_POST["month"] && $_POST["month"] != "")? $_POST["month"] : "";
        $day = ($_POST["day"] && $_POST["day"] != "")? $_POST["day"] : "";
        $hour = ($_POST["hour"] && $_POST["hour"] != "")? $_POST["hour"] : "";
        $to_hour = ($_POST["to_hour"] && $_POST["to_hour"] != "")? $_POST["to_hour"] : "";
        $minute = ($_POST["minute"] && $_POST["minute"] != "")? $_POST["minute"] : "";
        $to_minute = ($_POST["to_minute"] && $_POST["to_minute"] != "")? $_POST["to_minute"] : "";
        $payment_log_seq = ($_POST["payment_log_seq"] && $_POST["payment_log_seq"] != "")? $_POST["payment_log_seq"] : "";


        if($artist_id != "" && $user_id != ""){
            $sql = "
                UPDATE tb_payment_log SET
                    worker = '{$worker}',
                    year = '{$year}',
                    month = '{$month}',
                    day = '{$day}',
                    hour = '{$hour}',
                    minute = '{$minute}',
                    to_hour = '{$to_hour}',
                    to_minute = '{$to_minute}'
                WHERE artist_id = '{$artist_id}' 
                AND customer_id = '{$user_id}'
                AND payment_log_seq = {$payment_log_seq}
            ";
            $result = mysqli_query($connection, $sql);

            if ($result === true){ // success

                $message = $year."년".$month."월".$day."일 예약변경등록. 예약 내용을 확인하세요.";
                $path = "https://partner.banjjakpet.com/reserve_main_day?ch=day&yy=".$year."&mm=".$month."&dd=".$day;
                //$image = "http://gopet.kr/pet/images/logo_login.jpg";
                $image = "";
                a_push($artist_id, "반짝, 반려생활의 단짝. 예약변경 알림", $message, $path, $image);

                $return_data = array("code" => "000000", "data" => "ok");
            }else{ // fail
                $return_data = array("code" => "000000", "data" => $sql);
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