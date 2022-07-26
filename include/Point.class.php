<?php
class Point
{
    public $is_load = false;
    public $customer_id;
    public $accumulate_point;
    public $purchase_point;
    public $update_time;

    // 포인트 조회
    function select_point($customer_id)
    {
        global $connection;
        $sql = "select * from tb_point where customer_id = '" . $customer_id . "';";
        $result = mysqli_query($connection, $sql);
        if ($row = mysqli_fetch_object($result)) {
            $this->customer_id = $row->customer_id;
            $this->accumulate_point = $row->accumulate_point;
            $this->purchase_point = $row->purchase_point;
            $this->update_time = $row->update_time;
            $this->is_load = true;
            return true;
        } else {
            $sql = "insert into tb_point (customer_id, accumulate_point, purchase_point, update_time) values ('" . $customer_id . "', 0, 0, now());";
            $result = mysqli_query($connection,$sql);
            $this->customer_id = $customer_id;
            $this->accumulate_point = '0';
            $this->purchase_point = '0';
            $this->update_time = date('Y-m-d H:i:s');
            $this->is_load = true;
            return true;
        }
        return false;
    }

    // db 에 저장
    function update_point()
    {
        global $connection;
        $sql = "UPDATE tb_point 
                SET accumulate_point = '" . $this->accumulate_point . "', 
                purchase_point = '" . $this->purchase_point . "', 
                update_time = NOW() 
                WHERE customer_id = '" . $this->customer_id . "';";
        // error_log('----- update_point / $sql : ' . $sql);
        $result = mysqli_query($connection,$sql);
        if (mysqli_affected_rows($connection) > 0) {
            return true;
        } else {
            return false;
        }
    }

    // 이벤트 시에 이미 참가한 이벤트인지 검사한다. 이미 포인트 가능여부 true면 적립, false 면 불가
    function is_able_to_event_point($event_id)
    {
        global $connection;
        if ($this->is_load == false) {
            return false;
        }
        $sql = "select * from tb_point_history where customer_id = '" . $this->customer_id . "' and event_name = '" . $event_id . "' and type = 'EVENT';";
        $result = mysqli_query($connection, $sql);
        if ($row = mysqli_fetch_object($result)) {
            return false;
        } else {
            return true;
        }
    }

    // history 저장
    function insert_history($type, $event_name, $payment_log_seq, $order_id = "-", $spending_point, $adding_point, $spending_accumulate_point, $spending_purchase_point)
    {
        global $connection;
        $sql = "insert into tb_point_history (customer_id, event_name, type, spending_point, adding_point, accumulate_point, purchase_point, payment_log_seq, order_id, update_time, spending_accumulate_point, spending_purchase_point) 
                values ('" . $this->customer_id . "', '" . $event_name . "', '" . $type . "', '" . $spending_point . "', '" . $adding_point . "', '" . $this->accumulate_point . "', '" . $this->purchase_point . "', '" . $payment_log_seq . "', '" . $order_id . "', now(), '" . strval($spending_accumulate_point) . "', '" . strval($spending_purchase_point) . "');";
        $result = mysqli_query($connection, $sql);
        if (mysqli_affected_rows($connection) > 0) {
            return true;
        } else {
            return false;
        }
    }

    function print_stdio()
    {
        echo "고객ID : " . $this->customer_id . "<br>";
        echo "적립포인트 : " . floor($this->accumulate_point) . "<br>";
        echo "구매포인트 : " . floor($this->purchase_point) . "<br>";
        echo "적용일시 : " . $this->update_time . "<br>";
        echo "총포인트 : " . (floor($this->accumulate_point) + floor($this->purchase_point)) . "<br>";
    }

    // 포인트 합
    function get_point()
    {
        if ($this->is_load == false) {
            return -1;
        }
        return $this->accumulate_point + $this->purchase_point;
    }

    // 포인트 사용
    function spend_point($point, $payment_log_seq, $order_id)
    {
        global $connection;
        if ($point <= 0) {
            return false;
        }
        if ($this->is_load == false) {
            return false;
        }

        // 보유 일반포인트보다 사용할 포인트가 많을때, 즉 산책포인트를 사용해야할때
        if (($this->accumulate_point + $this->purchase_point) < $point) {
            // 사용하고자 하는 포인트가 일반 포인트보다 많을때 산책포인트에서 사용가능 포인트 조회
            $tracking_sql = "
             SELECT SUM(point) point, SUM(is_invalid_point) invalid_point FROM tb_tracking_mgr 
            WHERE owner_id = '".$this->customer_id."' 
            AND NOT POINT = is_invalid_point 
            GROUP BY owner_id;
        ";
            $tracking_result = mysqli_query($connection,$tracking_sql);
            if($tracking_row = mysqli_fetch_object($tracking_result)){
                $tracking_point = $tracking_row->point - $tracking_row->invalid_point;

                // 산책포인트까지 합쳐도 포인트가 모자라면 false
                if(($point - $this->accumulate_point) > $tracking_point){
                    return false;
                }
            };

        }
        $spending_accumulate_point = 0;
        $spending_purchase_point = 0;
        // 보유 일반포인트가 사용하고자 하는 포인트보다 많을때(일반포인트에서만 쓰면됨)
        if ($this->accumulate_point >= $point) {
            $this->accumulate_point -= $point;
            $spending_accumulate_point = $point;

        // 차액 포인트 산책포인트에서 사용해야함
        } else {
            $spend_point = $point - $this->accumulate_point; // 산책포인트에서 차감해야할 포인트
            $point = $this->accumulate_point; // 현재 보유하고있는 포인트 전부 사용
            $spending_accumulate_point = $this->accumulate_point; // history에 차감할 포인트(일반포인트 전체)
            $this->accumulate_point = 0; // 보유 포인트 0으로 변경

            // 구매타입 구하기
            $pos_type = strpos($order_id, "product_");
            if($pos_type === false){
                $type = '0';
            }else{
                $type = '1';
            }
            // 산책포인트 사용 히스토리 insert
            $tracking_insert_sql = "
                INSERT INTO `tb_tracking_point_history` (`payment_seq`, `pay_type`, `pay_status`, `reg_date`, `mod_date`, `is_delete`) 
                VALUES ('".$payment_log_seq."', ".$type.", 0, NOW(), NOW(), 0);
            ";
            $tracking_insert_result = mysqli_query($connection,$tracking_insert_sql);
            $history_idx = mysqli_insert_id($connection);

            // 포인트 사용 가능한 산책 데이터 가져오기
            $tracking_point_history_sql = "
                SELECT idx, point, is_invalid_point, (point-is_invalid_point) pos_point FROM tb_tracking_mgr 
                WHERE owner_id = '".$this->customer_id."'
                AND POINT > 0
                AND NOT POINT = is_invalid_point
                ORDER BY st_date DESC
            ";
            $tracking_point_history_result = mysqli_query($connection,$tracking_point_history_sql);

            //
            while ($tracking_point_history_row = mysqli_fetch_object($tracking_point_history_result)) {

                // 가장 최근 tracking_mgr row의 적립포인트 보다 사용해야할 포인트가 많을때(해당 row의 적립 포인트 모두 사용)
                if($spend_point >= $tracking_point_history_row->pos_point){
                    $update_sql = "UPDATE tb_tracking_mgr SET is_invalid_point = point WHERE idx = {$tracking_point_history_row->idx}";
                    $update_result = mysqli_query($connection,$update_sql);
                    $insert_sql = "
                        INSERT INTO `tb_tracking_point_used` (`history_idx`, `tracking_idx`, `point`) 
                        VALUES ('".$history_idx."', ".$tracking_point_history_row->idx.", ".$tracking_point_history_row->pos_point.");
                    ";
                    $insert_result = mysqli_query($connection,$insert_sql);

                }else{
                    // 남은 포인트가 해당 row의 적립포인트보다 적을때
                    if($spend_point != 0){
                        $update_sql = "UPDATE tb_tracking_mgr SET is_invalid_point = (is_invalid_point + {$spend_point}) WHERE idx = {$tracking_point_history_row->idx}";
                        $update_result = mysqli_query($connection,$update_sql);
                        $insert_sql = "
                        INSERT INTO `tb_tracking_point_used` (`history_idx`, `tracking_idx`, `point`) 
                        VALUES ('".$history_idx."', ".$tracking_point_history_row->idx.", ".$spend_point.");
                    ";
                        $insert_result = mysqli_query($connection,$insert_sql);
                    }
                    break;
                }
                // 각 로우에 적용하고 남은 포인트
                $spend_point -= $tracking_point_history_row->pos_point;
            }

        }

        $this->insert_history("SPEND", "-", $payment_log_seq, $order_id, $point, "0", $spending_accumulate_point, $spending_purchase_point);

        return $this->update_point();
    }

    // 취소 포인트
    function cancel_point($point, $payment_log_seq, $order_id)
    {
        if ($point <= 0) {
            return false;
        }
        if ($this->is_load == false) {
            return false;
        }
        if (($this->accumulate_point + $this->purchase_point) < $point) {
            return false;
        }
        $spending_accumulate_point = 0;
        $spending_purchase_point = 0;
        $spend_point = $point;
        if ($this->accumulate_point >= $point) {
            $this->accumulate_point -= $point;
            $spending_accumulate_point = $point;
        } else {
            $spend_point = $point - $this->accumulate_point;
            $spending_accumulate_point = $this->accumulate_point;
            $spending_purchase_point = $spend_point;
            $this->accumulate_point = 0;
            if ($spend_point > 0) {
                $this->purchase_point -= $spend_point;
            }
        }

        $this->insert_history("CANCEL", "-", $payment_log_seq, $order_id, $point, "0", $spending_accumulate_point, $spending_purchase_point);

        return $this->update_point();
    }


    function cancel_point_new($point, $payment_log_seq, $order_id)
    {
        if ($point <= 0) {
            return false;
        }
        if ($this->is_load == false) {
            return false;
        }
        if (($this->accumulate_point + $this->purchase_point) < $point) {
            return false;
        }
        $spending_accumulate_point = $point;
        $spending_purchase_point = 0;
        $this->accumulate_point -= $point;
        // error_log('----- cancel_point_new / purchase_point : '.$this->purchase_point);
        // error_log('----- cancel_point_new / accumulate_point : '.$this->accumulate_point);

        $this->insert_history("CANCEL", "-", $payment_log_seq, $order_id, $point, "0", $spending_accumulate_point, $spending_purchase_point);

        return $this->update_point();
    }

    // 적립으로 인한 증가
    function add_accumulate_point($point, $payment_log_seq, $order_id)
    {
        if ($point <= 0) {
            return false;
        }
        if ($this->is_load == false) {
            return false;
        }
        $this->accumulate_point += $point;

        $this->insert_history("ACCUMLATE", "-", $payment_log_seq, $order_id, "0", $point, 0, 0);

        return $this->update_point();
    }

    // 포인트 구매로 인한 증가
    function add_purchase_point($point, $payment_log_seq, $order_id)
    {
        if ($point <= 0) {
            return false;
        }
        if ($this->is_load == false) {
            return false;
        }
        $this->purchase_point += $point;

        $this->insert_history("BUY", "-", $payment_log_seq, $order_id, "0", $point, 0, 0);

        return $this->update_point();
    }

    // 이벤트로 인한 적립 point 증가
    function add_accumulate_point_by_event($point, $event_id, $ip_log)
    {
        if ($point <= 0) {
            return false;
        }
        if ($this->is_load == false) {
            return false;
        }
        if ($this->is_able_to_event_point($event_id) == false) {
            return false;
        }
        $this->accumulate_point += $point;

        $this->insert_history("EVENT", $event_id, $ip_log, "-", "0", $point, 0, 0);

        return $this->update_point();
    }

    //  퍼센트 적립
    function add_accumulate_percent_point($price, $percent, $payment_log_seq, $order_id)
    {
        $percent_point = (($price * $percent) / 100);
        //echo $percent_point;
        if ($percent_point > 0) {
            return $this->add_accumulate_point($percent_point, $payment_log_seq, $order_id);
        }
        return true;
    }

    function cancel_accumulate($payment_log_seq, $order_id)
    {
        global $connection;
        if ($this->is_load == false) {
            return false;
        }
        $sql = "select * from tb_point_history where customer_id = '" . $this->customer_id . "' and payment_log_seq = '" . $payment_log_seq . "' and type = 'ACCUMLATE';";
        $result = mysqli_query($connection, $sql);
        if ($row = mysqli_fetch_object($result)) {
            $point = $row->adding_point;
            $this->cancel_point($point, $payment_log_seq, $order_id);
        } else {
            return true;
        }

        return true;
    }

    function cancel_accumulate_new($payment_log_seq, $order_id)
    {
        global $connection;
        if ($this->is_load == false) {
            return false;
        }
        $sql =
            "SELECT * 
        FROM tb_point_history 
        WHERE customer_id = '" . $this->customer_id . "' 
        AND payment_log_seq = '" . $payment_log_seq . "' 
        AND type = 'ACCUMLATE'
        AND NOT order_id like 'cancel_%';";
        // error_log('----- cancel_accumulate_new/ $sql : '.$sql);
        $result = mysqli_query($connection, $sql);
        if ($row = mysqli_fetch_object($result)) {
            $point = $row->adding_point;
            $this->cancel_point_new($point, $payment_log_seq, $order_id);
        } else {
            return true;
        }

        return true;
    }
}
