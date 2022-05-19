<?php include "../include/top.php"; ?>
<?php include "../include/Crypto.class.php"; ?>

<link rel="stylesheet" href="<?= $css_directory ?>/manage_my_coupon.css?<?= filemtime($upload_static_directory . $css_directory . '/manage_my_coupon.css') ?>">

<?php
//공통변수
$type = $_GET['type'];
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

if ($type == NULL || $type == "") {
    $type = "beauty";
}

//보유 쿠폰
$coupon_query = "SELECT uc.*, c.name FROM tb_user_coupon uc 
                LEFT OUTER JOIN tb_coupon c ON c.coupon_seq = uc.coupon_seq
                WHERE uc.customer_id = '{$user_id}' AND uc.given > 0 AND uc.del_yn = 'N'";
$coupon_result = mysql_query($coupon_query);

$coupon_list = array();
while ($coupon_data = mysql_fetch_object($coupon_result)) {
    $coupon_list[] = $coupon_data;
}

//쿠폰 이력
$history_query = "SELECT 
                c.name as coupon_name
                , DATE_FORMAT(CONCAT(pl.year,'-',pl.month,'-',pl.day,' ',pl.hour,':',IFNULL(pl.minute,'00'),':00'),'%Y-%m-%d %H:%i') as reservation_date
                , ch.amount as amount
                , ch.balance as balance
                , pl.buy_time as use_date
                , (SELECT nicname FROM tb_artist_list WHERE `name`= pl.worker GROUP BY `name`) as nickname
                , pl.cancel_time as cancel_date
                , pl.is_cancel as is_cancel
                , (SELECT buy_time FROM tb_payment_log WHERE payment_log_seq = uc.payment_log_seq) as buy_date
                , (SELECT name FROM tb_shop WHERE customer_id = uc.artist_id) as shop_name
                , pl.product as product
                , pl.cellphone as cellphone
            FROM tb_coupon_history ch 
            LEFT OUTER JOIN tb_payment_log pl ON pl.payment_log_seq = ch.payment_log_seq
            LEFT OUTER JOIN tb_coupon c ON c.coupon_seq = ch.coupon_seq
            LEFT OUTER JOIN tb_user_coupon uc ON uc.user_coupon_seq = ch.user_coupon_seq
            WHERE ch.customer_id = '{$user_id}' AND ch.type = 'U'
            ORDER BY ch.date DESC";
$history_result = mysql_query($history_query);

$history_array = array();
while ($history_data = mysql_fetch_object($history_result)) {
    $product = $history_data->product;
    $product_arr = explode("|", $product);

    //펫 이름
    $pet_name = $product_arr[0];

    //전화번호
    $cellphone = $history_data->cellphone;

    //펫 정보
    $tmp_user_sql = "SELECT * FROM tb_tmp_user WHERE cellphone = '{$cellphone}'";
    $tmp_user_result = mysql_query($tmp_user_sql);
    $tmp_user_data = mysql_fetch_object($tmp_user_result);
    $tmp_user_cnt = mysql_num_rows($tmp_user_result);

    $crypto = new Crypto();
    $cellphone_encode = $crypto->encode($cellphone, $access_key, $secret_key);

    $customer_query = "SELECT * FROM tb_customer WHERE cellphone = '{$cellphone_encode}'";
    $customer_result = mysql_query($customer_query);
    $search_data = mysql_fetch_object($customer_result);

    $user_id = $search_data->id;
    $pet_data = null;
    if ($user_id != null && $user_id != "") {
        //정회원일 때
        $pet_list_sql = "SELECT * FROM tb_mypet WHERE customer_id = '{$user_id}' AND tmp_yn = 'N' AND name = '{$pet_name}' LIMIT 1";
        $pet_result = mysql_query($pet_list_sql);
        $pet_data = mysql_fetch_object($pet_result);
    } else if ($tmp_user_cnt > 0) {
        //가회원일 때
        $pet_list_sql = "SELECT * FROM tb_mypet WHERE tmp_seq = '{$tmp_user_data->tmp_seq}' AND tmp_yn = 'Y' AND name = '{$pet_name}' LIMIT 1";
        $pet_result = mysql_query($pet_list_sql);
        $pet_data = mysql_fetch_object($pet_result);
    }

    if ($pet_data != null && isset($pet_data)) {
        $pet_type = $pet_data->type;

        if ($pet_type == "dog") {
            //미용 내역
            $history_data->product_name = explode(":", $product_arr[4])[0];
            //추가 내역
            $history_data->add_product = explode(":", $product_arr[6])[0];
        } else {
            //미용 내역
            $history_data->product_name = $product_arr[3];
            //추가 내역
            $history_data->add_product = explode(":", $product_arr[5])[0];
        }
    }

    $history_array[] = $history_data;
}
?>
<div class="top_menu">
    <div class="top_back"><a href="<?= $mainpage_directory ?>/mainpage_my_menu.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div class="top_title">
        <p>구매 쿠폰</p>
    </div>
</div>

<div class="wrap">
    <div class="my-coupon">
        <div class="header">보유한 쿠폰</div>
        <ul>
            <?php foreach ($coupon_list as $index => $coupon) { ?>
                <li>
                    <div class="name"><?php echo $coupon->name; ?>
                    </div>
                    <div class="money">
                        <?php
                        $type = $coupon->type;
                        echo (($type == "F") ? "￦" : "");
                        echo number_format($coupon->given);
                        echo (($type == "C") ? "회" : "");
                        ?>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>

    <div class="mobile">
        <div class="header">
            <div class="cell coupon_name">쿠폰명</div>
            <div class="cell use_date">사용일시</div>
            <div class="cell amount">차감</div>
            <div class="cell balance">보유</div>
        </div>
        <?php
        if (count($history_array) > 0) {
            $before_date = "";
            foreach ($history_array as $key => $history) {
                $coupon_name = $history->coupon_name;
                $use_date = date("j일 H:i", strtotime($history->use_date));
                $amount = number_format(intval($history->amount));
                $balance = number_format(intval($history->balance));
                $pet_name = $history->pet_name;
                $reservation_date = date("y.n.j H:i", strtotime($history->reservation_date));
                $nickname = $history->nickname;
                $add_product = $history->add_product;
                $cancel_date = ($history->is_cancel == 1) ? date("y.n.j H:i", strtotime($history->cancel_time)) : "";
                $buy_date = date("y.n.j H:i", strtotime($history->buy_date));
                $shop_name = $history->shop_name;

                $prefix = "";
                if ($amount > 0) {
                    $prefix = "+";
                }

                $use_date_group = date("y년 m월", strtotime($history->use_date));
                if ($before_date != $use_date_group) {
        ?>
                    <div class="date-line"><?= $use_date_group ?></div>
                <?php
                }
                ?>
                <div class="main" onclick="javascript:toggleList($(this));">
                    <div class="cell coupon_name"><?= $coupon_name ?></div>
                    <div class="cell use_date"><?= $use_date ?></div>
                    <div class="cell amount"><?= $prefix . $amount ?></div>
                    <div class="cell balance">
                        <div class="text">
                            <?= $balance ?>
                        </div>
                        <div class="btn">▼</div>
                    </div>
                </div>
                <div class="sub">
                    <ul>
                        <li><label>펫이름</label><span><?= $pet_name ?></span></li>
                        <li><label>예약일시</label><span><?= $reservation_date ?></span></li>
                        <li><label>미용사</label><span><?= $nickname ?></span></li>
                        <li><label>추가</label><span><?= $add_product ?></span></li>
                        <li><label>취소일시</label><span><?= $cancel_date ?></span></li>
                        <li><label>쿠폰구매일</label><span><?= $buy_date ?></span></li>
                        <li><label>사용가능샵</label><span><?= $shop_name ?></span></li>
                    </ul>
                </div>
            <?php
                $before_date = date("y년 m월", strtotime($history->use_date));
            }
        } else {
            ?>
            <div class="coupon_none">결제 내역이 없습니다.</div>
        <?php
        }
        ?>
    </div>
</div>

<script>
    function toggleList(selected) {
        $(".main").find(".btn").text("▼");
        $(".sub:visible").hide("slow");

        var sub = selected.next(".sub");
        if (sub.is(":visible")) {
            selected.find(".btn").text("▼");
            sub.hide("slow");
        } else {
            selected.find(".btn").text("▲");
            sub.show("slow");
        }
    }
</script>

<?php include "../include/bottom.php"; ?>