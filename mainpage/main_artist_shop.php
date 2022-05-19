<?php
if (!$artist_id) {
    $artist_id = $_REQUEST["artist_id"];
}

// 각 종목별 화면
$artist_sql = "select * from tb_shop where open_flag = true and customer_id = '" . $artist_id . "';"; // limit 0, 10;";
$artist_result = mysql_query($artist_sql);
if ($artist_datas = mysql_fetch_object($artist_result)) {

    //----- 별점 평균
    $avg_value = "0";
    $avg_sql = "SELECT avg(rating) AS avg_rating FROM tb_usage_reviews WHERE artist_id = '" . $artist_id . "' AND is_delete = 0 AND rating IS NOT NULL;";
    $avg_result = mysql_query($avg_sql);
    if ($avg_rows = mysql_fetch_object($avg_result)) {
        $avg_value = $avg_rows->avg_rating;
        $avg_value = substr($avg_value, 0, 3);
    }
    if (!$avg_value) {
        $avg_value = '0';
    }

    //----- 댓글 수
    $review_count = "0";
    $review_sql = "SELECT count(review_seq) as review_cnt FROM tb_usage_reviews WHERE artist_id = '" . $artist_id . "' AND is_delete = 0;";
    $review_result = mysql_query($review_sql);
    if ($review_rows = mysql_fetch_object($review_result)) {
        $review_count = $review_rows->review_cnt;
    }
    if (!$review_count) {
        $review_count = '0';
    }

    //----- 영업 시간 및 정기 휴일
    $str_rest_public_holiday = "";
    $working_sql = "select * from tb_working_schedule tws, tb_regular_holiday trh where tws.customer_id = '" . $artist_id . "' and trh.customer_id = '" . $artist_id . "';";
    $working_result = mysql_query($working_sql);
    if ($working_datas = mysql_fetch_object($working_result)) {
        $working_start = $working_datas->working_start;
        $working_end = $working_datas->working_end;
        $rest_public_holiday = $working_datas->rest_public_holiday;
        if ($working_datas->is_monday) {
            $str_rest_public_holiday = "월요일";
        }
        if ($working_datas->is_tuesday) {
            $str_rest_public_holiday = " 화요일";
        }
        if ($working_datas->is_wednesday) {
            $str_rest_public_holiday = " 수요일";
        }
        if ($working_datas->is_thursday) {
            $str_rest_public_holiday = " 목요일";
        }
        if ($working_datas->is_friday) {
            $str_rest_public_holiday = " 금요일";
        }
        if ($working_datas->is_saturday) {
            $str_rest_public_holiday = " 토요일";
        }
        if ($working_datas->is_sunday) {
            $str_rest_public_holiday = " 일요일";
        }
        if (!$working_datas->is_monday && !$working_datas->is_tuesday && !$working_datas->is_wednesday && !$working_datas->is_thursday && !$working_datas->is_friday && !$working_datas->is_saturday && !$working_datas->is_sunday) {
            $str_rest_public_holiday = "없음";
        }
    }

    //----- 매장 위치
    $shop_address = "";
    $crypto = new Crypto();
    $enc_artist_id = $crypto->encode(trim($artist_id), $access_key, $secret_key);
    $off_sql = "select * from tb_request_artist where customer_id = '" . $enc_artist_id . "';";
    $off_result = mysql_query($off_sql);
    if ($off_rows = mysql_fetch_object($off_result)) {
        if ($off_rows->is_got_offline_shop == 1) {
            $shop_address = $crypto->decode($off_rows->offline_shop_address, $access_key, $secret_key);
            if ($shop_address) {
                $shop_address = str_replace("|", "", strstr($shop_address, "|"));
            } else {
                $shop_address = "(주소 표기 오류)";
            }
        }
    }

    /** 서비스 펫 종류 및 출장/매장 여부
     * 
     * icon_01.png = 소형견
     * icon_02.png = 중형견
     * icon_03.png = 대형견
     * icon_07.png = 특수견
     * icon_04.png = 고양이
     * icon_05.png = 출장
     * icon_06.png = 매장
     */
    $total_service = "";
    $crypto = new Crypto();
    $enc_artist_id = $crypto->encode(trim($artist_id), $access_key, $secret_key);

    $cat_query = "SELECT * FROM tb_product_cat WHERE customer_id = '{$artist_id}'";
    $cat_result = mysql_query($cat_query);
    $cat_count = mysql_num_rows($cat_result);

    $dog_query = "SELECT * FROM tb_product_dog_static WHERE customer_id = '{$artist_id}'";
    $dog_result = mysql_query($dog_query);
    $dog_count = mysql_num_rows($dog_result);

    $out_query = "SELECT second_type FROM tb_product_dog_static WHERE out_shop_product = 1 AND customer_id = '{$artist_id}'
                                                                UNION ALL
                                                                SELECT second_type FROM tb_product_cat WHERE out_shop_product = 1 AND customer_id = '{$artist_id}'";
    $out_result = mysql_query($out_query);
    $out_count = mysql_num_rows($out_result);

    $in_query = "SELECT second_type FROM tb_product_dog_static WHERE in_shop_product = 1 AND customer_id = '{$artist_id}'
                                                                UNION ALL
                                                                SELECT second_type FROM tb_product_cat WHERE in_shop_product = 1 AND customer_id = '{$artist_id}'";
    $in_result = mysql_query($in_query);
    $in_count = mysql_num_rows($in_result);

    if ($dog_count > 0) {
        $dog_product_query = "SELECT * FROM tb_product_dog_static WHERE customer_id = '{$artist_id}'";
        // error_log('----- $dog_product_query : '.$dog_product_query);
        $dog_product_result = mysql_query($dog_product_query);
        for ($index = 0; $dog_product_rows = mysql_fetch_object($dog_product_result); $index++) {
            if ($dog_product_rows->second_type == "소형견미용") {
                $total_service .= "<div><img src='../images/icon_01.png'></div>";
            } else if ($dog_product_rows->second_type == "중형견미용") {
                $total_service .= "<div><img src='../images/icon_02.png'></div>";
            } else if ($dog_product_rows->second_type == "대형견미용") {
                $total_service .= "<div><img src='../images/icon_03.png'></div>";
            } else if ($dog_product_rows->second_type == "특수견미용") {
                $total_service .= "<div><img src='../images/icon_07.png'></div>";
            }
        }
    }

    if ($cat_count > 0) {
        $total_service .= "<div><img src='../images/icon_04.png'></div>";
    }

    if ($out_count > 0) {
        $total_service .= "<div><img src='../images/icon_05.png'></div>";
    }

    if ($in_count > 0) {
        $total_service .= "<div><img src='../images/icon_06.png'></div>";
    }
    ?>

    <a href="../artist/?artist_name=<?= urlencode($artist_datas->name) ?>">
        <div class="artist_list">
            <div class="shop_wrap">
                <?php
                    if ($artist_datas->is_recommend) {
                        ?>
                    <div class="shop_reco"><img src="../images/shop_recommend.png"></div>
                <?php
                    }
                    ?>
                <div class="artist_name">
                    <font style="font-size:18px;"><b style="font-family: 'NanumGothic';"><?= $artist_datas->name ?></b></font>
                    <div>
                        <!-- <div class="artist_rating_1"><img src="../images/img_star_1.png"></div>
                    <div class="artist_rating_2"><b><?= $avg_value ?></b>(<?= $review_count ?>)</div> -->
                    </div>
                </div>
            </div>
            <div style="width:90%;height:200px;margin: 0px auto;position:relative;z-index:-1;background-image:url(<?= $artist_datas->front_image ?>);background-size: 100%; background-position: center;border-radius:25px;">
            </div>
            <div class="artist_content">
                <div class="content_01">
                    <?= $total_service ?>
                </div>
                <div class="content_02">
                    <div class="con_01">
                        <div>영업시간</div>
                        <div><?= $working_start ?>:00 ~ <?= $working_end ?>:00</div>
                    </div>
                    <div class="con_02">
                        <div>정기휴일</div>
                        <div><?= $str_rest_public_holiday ?></div>
                    </div>
                    <div class="con_03">
                        <div>매장위치</div>
                        <div><?= $shop_address ?></div>
                    </div>
                </div>
            </div>
        </div>
    </a>
<?php
}
?>