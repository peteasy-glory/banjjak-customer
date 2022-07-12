<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login.php");
include($_SERVER['DOCUMENT_ROOT']."/include/skin/header.php");
?>


<?php
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
?>
    <link rel="stylesheet" href="m_new.css">
<style>
    a {
        text-decoration: none;
    }

    a:link {
        color: white;
    }

    a:visited {
        color: white;
    }

    a:hover {
        color: white;
    }

    a:active {
        color: white;
    }

    .my_shop_div {
        position: relative;
        z-index: 5;
        width: 93%;
        height: 30px;
        text-align: left;
        padding: 5px;
        border-bottom: 1px solid #efefef;
        border: 1;
        font-size: 15px;
        font-weight: bold;
        background-image: url('<?= $image_directory ?>/shop_back3.jpg');
        background-size: 100%;
        background-repeat: no-repeat;
        margin: auto;
    }

    .my_shop_img {
        position: absolute;
        z-index: 5;
        right: 10px;
        height: 30px;
    }

    .my_shop_text {
        position: absolute;
        z-index: 5;
        left: 10px;
        height: 30px;
    }

    .my_menu_div {
        position: relative;
        z-index: 0;
        width: 97%;
        text-align: left;
        padding: 5px;
        border: 1px solid #999999;
        border: 1;
        font-size: 14px;
        //font-weight:bold;
        //margin:auto;
    }

    .my_menu_img {
        position: absolute;
        z-index: 5;
        right: 10px;
        height: 23px;
    }

    .my_menu_text {
        position: absolute;
        z-index: 5;
        left: 10px;
        height: 30px;
    }

    .my_menu_img2 {
        position: absolute;
        z-index: 5;
        right: 10px;
        height: 30px;
    }
</style>
<style type="text/css">
    .agree_button {
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #c123de), color-stop(1, #a20dbd));
        background: -moz-linear-gradient(center top, #c123de 5%, #a20dbd 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#c123de', endColorstr='#a20dbd');
        background-color: #c123de;
        -webkit-border-top-left-radius: 0px;
        -moz-border-radius-topleft: 0px;
        border-top-left-radius: 0px;
        -webkit-border-top-right-radius: 0px;
        -moz-border-radius-topright: 0px;
        border-top-right-radius: 0px;
        -webkit-border-bottom-right-radius: 0px;
        -moz-border-radius-bottomright: 0px;
        border-bottom-right-radius: 0px;
        -webkit-border-bottom-left-radius: 0px;
        -moz-border-radius-bottomleft: 0px;
        border-bottom-left-radius: 0px;
        text-indent: 0;
        border: 1px solid #a511c0;
        display: inline-block;
        color: #ffffff;
        font-family: Arial;
        font-size: 15px;
        font-weight: bold;
        font-style: normal;
        height: 40px;
        line-height: 40px;
        width: 122px;
        text-decoration: none;
        text-align: center;
    }

    .agree_button:hover {
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #a20dbd), color-stop(1, #c123de));
        background: -moz-linear-gradient(center top, #a20dbd 5%, #c123de 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#a20dbd', endColorstr='#c123de');
        background-color: #a20dbd;
    }

    .agree_button:active {
        position: relative;
        top: 1px;
    }

    .agree_button2 {
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #ededed), color-stop(1, #dfdfdf));
        background: -moz-linear-gradient(center top, #ededed 5%, #dfdfdf 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#dfdfdf');
        background-color: #ededed;
        -webkit-border-top-left-radius: 0px;
        -moz-border-radius-topleft: 0px;
        border-top-left-radius: 0px;
        -webkit-border-top-right-radius: 0px;
        -moz-border-radius-topright: 0px;
        border-top-right-radius: 0px;
        -webkit-border-bottom-right-radius: 0px;
        -moz-border-radius-bottomright: 0px;
        border-bottom-right-radius: 0px;
        -webkit-border-bottom-left-radius: 0px;
        -moz-border-radius-bottomleft: 0px;
        border-bottom-left-radius: 0px;
        text-indent: 0;
        border: 1px solid #dcdcdc;
        display: inline-block;
        color: #777777;
        font-family: Arial;
        font-size: 15px;
        font-weight: bold;
        font-style: normal;
        height: 40px;
        line-height: 40px;
        width: 122px;
        text-decoration: none;
        text-align: center;
    }

    .agree_button2:hover {
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #dfdfdf), color-stop(1, #ededed));
        background: -moz-linear-gradient(center top, #dfdfdf 5%, #ededed 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#dfdfdf', endColorstr='#ededed');
        background-color: #dfdfdf;
    }

    .agree_button2:active {
        position: relative;
        top: 1px;
    }
</style>

<?php
$login_insert_sql = "select * from tb_customer where id = '" . $user_id . "' and (admin_flag = true or operator_flag = true)";
$result = mysqli_query($connection,$login_insert_sql);

if ($result_datas = mysqli_fetch_object($result)) {
    $nickname = $result_datas->nickname;
    $photo = $result_datas->photo;
    $c_cellphone = $result_datas->cellphone;
    $cellphone_confirm = $result_datas->cellphone_confirm;
    $id = $result_datas->id;
    $email_confirm = $result_datas->email_confirm;
    $mileage = $result_datas->mileage;
    $my_shop_flag = $result_datas->my_shop_flag;
    $push_option = $result_datas->push_option;
    $admin_flag = $result_datas->admin_flag;
    ?>

    <div class="header-back-btn"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/back.png" width="35px"></a></div>
    <div style="width:100%;height:19px;text-align:center;font-size:18px;font-weight:bold;">
        <p>반짝 SHOP 오픈 신청 관리</p>
    </div>

    <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">

    <?
        $sql = "select * from tb_shop where is_finish_open_magic = true and open_flag = false;";
        $result = mysqli_query($connection,$sql);
        $count = mysqli_num_rows($result);
        echo "<font style='font-size:13px;'>신청 수 : " . $count . "개</font><br>";
        while ($result_datas = mysqli_fetch_object($result)) {
            $customer_id = $result_datas->customer_id;
            $artist_id = $result_datas->customer_id;
            $name = $result_datas->name;
            $photo = $result_datas->photo;
            $front_image = $result_datas->front_image;
            $working_years = $result_datas->working_years;
            $self_introduction = $result_datas->self_introduction;
            $career = $result_datas->career;
            $offline_flag = $result_datas->offline_flag;
            $offline_address = $result_datas->offline_address;
            $update_time = $result_datas->update_time;
            ?>
        <div class="my_menu_div">
            <table width="100%">
                <tr>
                    <td>
                        아이디 : <?= $customer_id ?><br>
                        샵이름 : <?= $name ?><br>
                        사진 :<br><img src='<?= $photo ?>' width='100%'><br>
                        대문사진 :<br><img src='<?= $front_image ?>' width='100%'><br>
                        경력 : <?= $working_years ?><br>
                        자기소개 : <?= $self_introduction ?><br>
                        경력 : <?= $career ?><br>

                        <?php
                                if ($offline_flag) {
                                    echo "오프라인매장 : " . $offline_address . "<br>";
                                }
                                echo "전문분야 : ";
                                $custom_sql = "select * from tb_professional where customer_id = '" . $customer_id . "';";
                                $custom_result = mysqli_query($connection,$custom_sql);
                                while ($custom_datas = mysqli_fetch_object($custom_result)) {
                                    echo $custom_datas->value . " ";
                                }
                                echo "<br>";
                                ?>
                        입력일 : <?= $update_time ?><br>
                        영업지역 : <br>
                        <table width="100%" style="font-size:13px;">
                            <?php
                                    $now_sql = "select distinct tr.middle, tr.top from tb_working_region twr, tb_region tr where twr.customer_id = '" . $customer_id . "' and twr.region_id = tr.id;";
                                    $now_result = mysqli_query($connection,$now_sql);
                                    while ($now_datas = mysqli_fetch_object($now_result)) {
                                        $now_top = $now_datas->top;
                                        $now_middle = $now_datas->middle;
                                        ?>
                                <tr>
                                    <td style="height:50px;font-size:14px;border:1px dotted #999999;padding:5px;">
                                        <b><?= $now_top ?> <?= $now_middle ?></b><br>
                                        <?php
                                                    $select_bottom_sql = "select distinct tr.bottom, tr.id from tb_working_region twr, tb_region tr where twr.customer_id = '" . $customer_id . "' and twr.region_id = tr.id and tr.top = '" . $now_top . "' and tr.middle = '" . $now_middle . "';";
                                                    $sb_result = mysqli_query($connection,$select_bottom_sql);
                                                    while ($sb_datas = mysqli_fetch_object($sb_result)) {
                                                        ?>
                                            <?= $sb_datas->bottom ?>
                                    <?php
                                                }
                                                echo "<br></td></tr>";
                                            }
                                            ?>
                        </table>
                        출장비 : <br>
                        <?php
                                $now_sql = "select distinct tr.middle, tr.top from tb_working_region twr, tb_region tr where twr.customer_id = '" . $customer_id . "' and twr.region_id = tr.id;";
                                $now_result = mysqli_query($connection,$now_sql);
                                $notice_flag = 1;
                                while ($now_datas = mysqli_fetch_object($now_result)) {
                                    $now_top = $now_datas->top;
                                    $now_middle = $now_datas->middle;

                                    $select_bottom_sql = "select distinct tr.bottom, tr.id from tb_working_region twr, tb_region tr where twr.customer_id = '" . $customer_id . "' and twr.region_id = tr.id and tr.top = '" . $now_top . "' and tr.middle = '" . $now_middle . "' and twr.zone_id is null;";
                                    $sb_result = mysqli_query($connection,$select_bottom_sql);
                                    $count = mysqli_num_rows($sb_result);
                                    if ($count) {
                                        if ($notice_flag) {
                                            $notice_flag = 0;
                                            ?>
                                    <font style="font-size:15px;"><b>출장비를 설정하지 않은 영업지역이 있습니다.</b></font>
                                <?php
                                                }
                                                ?>
                                <table class="checks" width="100%" style="font-size:14px;border:1px dotted #999999;padding:5px;text-align:left;">
                                    <tr>
                                        <td>
                                            <div style="position:related;text-align:left;right:30px;top:5px;padding:5px;background-color:#ababab;color:#ffffff;font-size:15px;font-weight:bold;"><?= $now_top ?> <?= $now_middle ?></div>
                                            <?php
                                                            while ($sb_datas = mysqli_fetch_object($sb_result)) {
                                                                ?>
                                                <div class='region_bottom' style='font-size:14px;margin:2px;float:left;border:0px dotted #999999;'>
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <?= $sb_datas->bottom ?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            <?php
                                                            }
                                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            <?php
                                        }
                                    }
                                    if ($notice_flag) {
                                        ?>
                            <font style="font-size:15px;"><b>모든 지역의 출장비가 설정되어 있습니다.</b></font><br>
                        <?php
                                }
                                ?>
                        판매상품 : <br>
                        <?php
                                $vat_class = new VAT();
                                $is_vat = $vat_class->is_vat($artist_id);
                                $shop_fi_sql = "select * from tb_product_dog where customer_id = '" . $artist_id . "';";
                                $shop_fi_result = mysqli_query($connection,$shop_fi_sql);
                                while ($shop_fi_datas = mysqli_fetch_object($shop_fi_result)) {
                                    $n_first_type = $shop_fi_datas->first_type;
                                    $n_second_type = $shop_fi_datas->second_type;
                                    $n_in_shop_product = $shop_fi_datas->in_shop_product;
                                    $n_out_shop_product = $shop_fi_datas->out_shop_product;
                                    $n_bath_price = $shop_fi_datas->bath_price;
                                    $n_part_price = $shop_fi_datas->part_price;
                                    $n_bath_part_price = $shop_fi_datas->bath_part_price;
                                    $n_all_price = $shop_fi_datas->all_price;
                                    $n_spoting_price = $shop_fi_datas->spoting_price;
                                    $n_scissors_price = $shop_fi_datas->scissors_price;
                                    $n_increase_bath_price = $shop_fi_datas->increase_bath_price;
                                    $n_increase_part_price = $shop_fi_datas->increase_part_price;
                                    $n_increase_bath_part_price = $shop_fi_datas->increase_bath_part_price;
                                    $n_increase_all_price = $shop_fi_datas->increase_all_price;
                                    $n_increase_spoting_price = $shop_fi_datas->increase_spoting_price;
                                    $n_increase_scissors_price = $shop_fi_datas->increase_scissors_price;
                                    $n_section = $shop_fi_datas->section;
                                    ?>
                            <table width="100%">
                                <tr>
                                    <td style="height:50px;font-size:15px;border:1px solid #999999;padding:5px;">
                                        <div width="100%" style="position:absolute;z-index:1;height:20px;text-align:right;right:20px;padding:5px;">
                                            <?php if ($n_in_shop_product) { ?><img src="<?= $image_directory ?>/in_shop_b3.png" height="20px" /><?php } ?> <?php if ($n_out_shop_product) { ?><img src="<?= $image_directory ?>/out_shop.png" height="20px" /><?php } ?>
                                        </div>
                                        <div style="height:20px;text-align:center;padding:5px;background-color:#ababab;color:#ffffff;font-size:14px;font-weight:bold;"><?= $n_second_type ?></div>
                                        <div style="width:100%;font-size:13px;">
                                            <?php
                                                        if ($n_second_type == "소형견미용") {
                                                            echo "말티즈,요크셔.시츄,포메라이언,토이푸들,치와와,미니핀,페키니즈 등";
                                                        }
                                                        if ($n_second_type == "중형견미용") {
                                                            echo "슈나우저,코카스파니엘, 스피츠, 믹스견,미니어쳐푸들, 미디엄푸들 등";
                                                        }
                                                        if ($n_second_type == "대형견미용") {
                                                            echo "골든리트리버,사모예드,그레이트피레니즈,말라뮤트,도베르만 등";
                                                        }
                                                        if ($n_second_type == "특수견미용") {
                                                            echo "비숑, 배들링턴,웰시코기 등";
                                                        }
                                                        ?>
                                        </div>
                                        <div style="height:4px;"></div>
                                        <div style="width:100%;text-align:right;font-size:11px;">
                                            <table width="100%">
                                                <tr>
                                                    <td align="left"><?php if ($is_vat) {
                                                                                        echo "<b>(부가세 10%별도)</b>";
                                                                                    } ?></td>
                                                    <td align="right">(단위, 원)</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <?php
                                                    if ($n_second_type != "기타" && $n_second_type != "추가공통옵션") {
                                                        $n_section_array = explode(",", $n_section);
                                                        ?>
                                            <table width="100%" border="1" style="text-align:center;font-size:12px;border:1px solid #000000;border-collapse:collapse;">
                                                <tr style="font-weight:bold;">
                                                    <td></td>
                                                    <td>목욕</td>
                                                    <td>부분<br>미용</td>
                                                    <td>부분+<br>목욕</td>
                                                    <td>전체<br>미용</td>
                                                    <td>스포팅/<br>썸머컷</td>
                                                    <td>가위컷</td>
                                                </tr>
                                                <?php
                                                                for ($ii = 0; $ii < sizeof($n_section_array); $ii = $ii + 1) {
                                                                    ?>
                                                    <tr style="font-size:11px;">
                                                        <td>~<?= $n_section_array[$ii] ?>Kg</td>
                                                        <td><?= number_format($n_bath_price + ($ii * $n_increase_bath_price)) ?></td>
                                                        <td><?= number_format($n_part_price + ($ii * $n_increase_part_price)) ?></td>
                                                        <td><?= number_format($n_bath_part_price + ($ii * $n_increase_bath_part_price)) ?></td>
                                                        <td><?= number_format($n_all_price + ($ii * $n_increase_all_price)) ?></td>
                                                        <td><?= number_format($n_spoting_price + ($ii * $n_increase_spoting_price)) ?></td>
                                                        <td><?= number_format($n_scissors_price + ($ii * $n_increase_scissors_price)) ?></td>
                                                    </tr>
                                                <?php
                                                                }
                                                                ?>
                                            </table>
                                        <?php
                                                    }
                                                    ?>
                                    </td>
                                </tr>
                            </table>
                        <?php
                                }
                                ?>
                        <?php
                                $shop_fi_sql = "select * from tb_product_cat where customer_id = '" . $artist_id . "';";
                                $shop_fi_result = mysqli_query($connection,$shop_fi_sql);
                                while ($shop_fi_datas = mysqli_fetch_object($shop_fi_result)) {
                                    $n_first_type = $shop_fi_datas->first_type;
                                    $n_second_type = $shop_fi_datas->second_type;
                                    $n_in_shop_product = $shop_fi_datas->in_shop_product;
                                    $n_out_shop_product = $shop_fi_datas->out_shop_product;
                                    $n_short_price = $shop_fi_datas->short_price;
                                    $n_long_price = $shop_fi_datas->long_price;
                                    $n_increase_section_price = $shop_fi_datas->increase_section_price;
                                    $n_section = $shop_fi_datas->section;
                                    $n_shower_price = $shop_fi_datas->shower_price;
                                    $n_toenail_price = $shop_fi_datas->toenail_price;
                                    $n_addition_option_product = $shop_fi_datas->addition_option_product;
                                    $n_hair_clot_price = $shop_fi_datas->hair_clot_price;
                                    $n_ferocity_price = $shop_fi_datas->ferocity_price;
                                    $n_addition_work_product = $shop_fi_datas->addition_work_product;
                                    ?>
                            <table width="100%">
                                <tr>
                                    <td style="height:50px;font-size:15px;border:1px solid #999999;padding:5px;">
                                        <div width="100%" style="position:absolute;z-index:1;height:20px;text-align:right;right:20px;padding:5px;">
                                            <?php if ($n_in_shop_product) { ?><img src="<?= $image_directory ?>/in_shop_b3.png" height="20px" /><?php } ?> <?php if ($n_out_shop_product) { ?><img src="<?= $image_directory ?>/out_shop.png" height="20px" /><?php } ?>
                                        </div>
                                        <div style="height:20px;text-align:center;padding:5px;background-color:#ababab;color:#ffffff;font-size:14px;font-weight:bold;"><?= $n_first_type ?> <?= $n_second_type ?></div>
                                        <div style="height:4px;"></div>
                                        <div style="width:100%;text-align:right;font-size:11px;">
                                            <table width="100%">
                                                <tr>
                                                    <td align="left"><?php if ($is_vat) {
                                                                                        echo "<b>(부가세 10%별도)</b>";
                                                                                    } ?></td>
                                                    <td align="right">(단위, 원)</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <table width="100%" border="1" style="text-align:center;font-size:12px;border:1px solid #000000;border-collapse:collapse;">
                                            <tr style="font-weight:bold;">
                                                <td></td>
                                                <td>단모</td>
                                                <td>장모</td>
                                            </tr>
                                            <?php
                                                        $n_section_array = explode(",", $n_section);
                                                        for ($ii = 0; $ii < sizeof($n_section_array); $ii = $ii + 1) {
                                                            ?>
                                                <tr style="font-size:12px;">
                                                    <td>~<?= $n_section_array[$ii] ?>Kg</td>
                                                    <td><?= number_format($n_short_price + ($ii * $n_increase_section_price)) ?></td>
                                                    <td><?= number_format($n_long_price + ($ii * $n_increase_section_price)) ?></td>
                                                </tr>
                                            <?php
                                                        }
                                                        ?>
                                        </table>

                                        <div style="height:10px;"></div>
                                        <div style="height:20px;text-align:center;padding:5px;background-color:#ababab;color:#ffffff;font-size:14px;font-weight:bold;">추가 요금</div>
                                        <div style="height:4px;"></div>
                                        <table width="100%" border="1" style="font-size:12px;border:1px solid #000000;border-collapse:collapse;">
                                            <tr>
                                                <td style="font-weight:bold;">목욕</td>
                                                <td><?= number_format($n_shower_price) ?>원 추가</td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold;">발톱</td>
                                                <td><?= number_format($n_toenail_price) ?>원 추가</td>
                                            </tr>
                                            <?php
                                                        if ($n_addition_option_product != null && $n_addition_option_product != "") {
                                                            $n_addition_option_product_array = explode(",", $n_addition_option_product);
                                                            for ($ii = 0; $ii < sizeof($n_addition_option_product_array); $ii = $ii + 1) {
                                                                $bbb = explode(":", $n_addition_option_product_array[$ii]);
                                                                ?>
                                                    <tr>
                                                        <td style="font-weight:bold;"><?= $bbb[0] ?></td>
                                                        <td><?= number_format(intval($bbb[1])) ?>원 추가</td>
                                                    </tr>
                                            <?php
                                                            }
                                                        }
                                                        ?>

                                        </table>

                                        <div style="height:10px;"></div>
                                        <div style="height:20px;text-align:center;padding:5px;background-color:#ababab;color:#ffffff;font-size:14px;font-weight:bold;">현장판단 후 현장 결제추가 가능옵션</div>
                                        <div style="height:4px;"></div>
                                        <table width="100%" border="1" style="font-size:12px;border:1px solid #000000;border-collapse:collapse;">
                                            <tr>
                                                <td style="font-weight:bold;">털엉킴</td>
                                                <td><?= number_format($n_hair_clot_price) ?>원 부터</td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold;">사나움</td>
                                                <td><?= number_format($n_ferocity_price) ?>원 부터</td>
                                            </tr>
                                            <?php
                                                        if ($n_addition_work_product != null && $n_addition_work_product != "") {
                                                            $n_addition_work_product_array = explode(",", $n_addition_work_product);
                                                            for ($ii = 0; $ii < sizeof($n_addition_work_product_array); $ii = $ii + 1) {
                                                                $bbb = explode(":", $n_addition_work_product_array[$ii]);
                                                                ?>
                                                    <tr>
                                                        <td style="font-weight:bold;"><?= $bbb[0] ?></td>
                                                        <td><?= number_format(intval($bbb[1])) ?>원 추가</td>
                                                    </tr>
                                            <?php
                                                            }
                                                        }
                                                        ?>

                                        </table>

                                    </td>
                                </tr>
                            </table>
                        <?php
                                }
                                ?>

                        <?
                                //        }
                                ?>

                        <?php
                                $shop_fi_sql = "select * from tb_product_dog_common where customer_id = '" . $artist_id . "';";
                                $shop_fi_result = mysqli_query($connection,$shop_fi_sql);
                                while ($shop_fi_datas = mysqli_fetch_object($shop_fi_result)) {
                                    $n_first_type = $shop_fi_datas->first_type;
                                    $n_second_type = $shop_fi_datas->second_type;
                                    $n_in_shop_product = $shop_fi_datas->in_shop_product;
                                    $n_out_shop_product = $shop_fi_datas->out_shop_product;
                                    $n_basic_face_price = $shop_fi_datas->basic_face_price;
                                    $n_broccoli_price = $shop_fi_datas->broccoli_price;
                                    $n_highba_price = $shop_fi_datas->highba_price;
                                    $n_bear_price = $shop_fi_datas->bear_price;
                                    $n_beauty_length_1 = $shop_fi_datas->beauty_length_1;
                                    $n_beauty_length_1_price = $shop_fi_datas->beauty_length_1_price;
                                    $n_beauty_length_2 = $shop_fi_datas->beauty_length_2;
                                    $n_beauty_length_2_price = $shop_fi_datas->beauty_length_2_price;
                                    $n_short_hair_price = $shop_fi_datas->short_hair_price;
                                    $n_long_hair_price = $shop_fi_datas->long_hair_price;
                                    $n_double_hair_price = $shop_fi_datas->double_hair_price;
                                    $n_toenail_price = $shop_fi_datas->toenail_price;
                                    $n_trumpet_etc_price = $shop_fi_datas->trumpet_etc_price;
                                    $n_dyeing_price = $shop_fi_datas->dyeing_price;
                                    $n_cheek_touch_price = $shop_fi_datas->cheek_touch_price;
                                    $n_addition_option_product = $shop_fi_datas->addition_option_product;
                                    $n_hair_clot_price = $shop_fi_datas->hair_clot_price;
                                    $n_ferocity_price = $shop_fi_datas->ferocity_price;
                                    $n_tick_price = $shop_fi_datas->tick_price;
                                    $n_addition_work_product = $shop_fi_datas->addition_work_product;
                                    ?>
                            <table width="100%">
                                <tr>
                                    <td style="height:50px;font-size:15px;border:1px solid #999999;padding:5px;">
                                        <div style="width:100%;text-align:right;font-size:11px;">
                                            <table width="100%">
                                                <tr>
                                                    <td align="left"><?php if ($is_vat) {
                                                                                        echo "<b>(부가세 10%별도)</b>";
                                                                                    } ?></td>
                                                    <td align="right">(단위, 원)</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div style="height:20px;text-align:center;padding:5px;background-color:#ababab;color:#ffffff;font-size:14px;font-weight:bold;">얼굴 디자인컷 추가</div>
                                        <div style="height:4px;"></div>
                                        <table width="100%" border="1" style="font-size:12px;border:1px solid #000000;border-collapse:collapse;">
                                            <tr>
                                                <td style="font-weight:bold;">기본얼굴컷</td>
                                                <td><?= number_format($n_basic_face_price) ?>원 추가</td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold;">브로콜리컷</td>
                                                <td><?= number_format($n_broccoli_price) ?>원 추가</td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold;">하이바컷</td>
                                                <td><?= number_format($n_highba_price) ?>원 추가</td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold;">곰돌이컷</td>
                                                <td><?= number_format($n_bear_price) ?>원 추가</td>
                                            </tr>
                                        </table>

                                        <div style="height:5px;"></div>

                                        <div style="height:20px;text-align:center;padding:5px;background-color:#ababab;color:#ffffff;font-size:14px;font-weight:bold;">전체 미용 털길이 조절 추가</div>
                                        <div style="height:4px;"></div>
                                        <table width="100%" border="1" style="font-size:12px;border:1px solid #000000;border-collapse:collapse;">
                                            <tr>
                                                <td style="font-weight:bold;"><?= $n_beauty_length_1 ?>mm</td>
                                                <td><?= number_format($n_beauty_length_1_price) ?>원 추가</td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold;"><?= $n_beauty_length_2 ?>mm</td>
                                                <td><?= number_format($n_beauty_length_2_price) ?>원 추가</td>
                                            </tr>
                                        </table>

                                        <div style="height:5px;"></div>

                                        <div style="height:20px;text-align:center;padding:5px;background-color:#ababab;color:#ffffff;font-size:14px;font-weight:bold;">목욕 털길이 추가요금</div>
                                        <div style="height:4px;"></div>
                                        <table width="100%" border="1" style="font-size:12px;border:1px solid #000000;border-collapse:collapse;">
                                            <tr>
                                                <td style="font-weight:bold;">단모</td>
                                                <td><?= number_format($n_short_hair_price) ?>원 추가</td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold;">장모</td>
                                                <td><?= number_format($n_long_hair_price) ?>원 추가</td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold;">이중모</td>
                                                <td><?= number_format($n_double_hair_price) ?>원 추가</td>
                                            </tr>
                                        </table>

                                        <div style="height:5px;"></div>

                                        <div style="height:20px;text-align:center;padding:5px;background-color:#ababab;color:#ffffff;font-size:14px;font-weight:bold;">기타 옵션</div>
                                        <div style="height:4px;"></div>
                                        <table width="100%" border="1" style="font-size:12px;border:1px solid #000000;border-collapse:collapse;">
                                            <tr>
                                                <td style="font-weight:bold;">발톱</td>
                                                <td><?= number_format($n_toenail_price) ?>원 추가</td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold;">나팔/장화/방울</td>
                                                <td><?= number_format($n_trumpet_etc_price) ?>원 추가</td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold;">염색(포인트당)</td>
                                                <td><?= number_format($n_dyeing_price) ?>원 추가</td>
                                            </tr>
                                            <?php
                                                        if ($n_addition_option_product != null && $n_addition_option_product != "") {
                                                            $n_addition_option_product_array = explode(",", $n_addition_option_product);
                                                            for ($ii = 0; $ii < sizeof($n_addition_option_product_array); $ii = $ii + 1) {
                                                                $bbb = explode(":", $n_addition_option_product_array[$ii]);
                                                                ?>
                                                    <tr>
                                                        <td style="font-weight:bold;"><?= $bbb[0] ?></td>
                                                        <td><?= number_format(intval($bbb[1])) ?>원 추가</td>
                                                    </tr>
                                            <?php
                                                            }
                                                        }
                                                        ?>
                                        </table>

                                        <div style="height:5px;"></div>

                                        <div style="height:20px;text-align:center;padding:5px;background-color:#ababab;color:#ffffff;font-size:14px;font-weight:bold;">현장판단 후 현장결제 추가 가능옵션</div>
                                        <div style="height:4px;"></div>
                                        <table width="100%" border="1" style="font-size:12px;border:1px solid #000000;border-collapse:collapse;">
                                            <tr>
                                                <td style="font-weight:bold;">털엉킴</td>
                                                <td><?= number_format($n_hair_clot_price) ?>원 부터</td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold;">사나움</td>
                                                <td><?= number_format($n_ferocity_price) ?>원 부터</td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight:bold;">진드기</td>
                                                <td><?= number_format($n_tick_price) ?>원 부터</td>
                                            </tr>
                                            <?php
                                                        if ($n_addition_work_product != null && $n_addition_work_product != "") {
                                                            $n_addition_work_product_array = explode(",", $n_addition_work_product);
                                                            for ($ii = 0; $ii < sizeof($n_addition_work_product_array); $ii = $ii + 1) {
                                                                $bbb = explode(":", $n_addition_work_product_array[$ii]);
                                                                ?>
                                                    <tr>
                                                        <td style="font-weight:bold;"><?= $bbb[0] ?></td>
                                                        <td><?= number_format(intval($bbb[1])) ?>원 추가</td>
                                                    </tr>
                                            <?php
                                                            }
                                                        }
                                                        ?>
                                        </table>

                                    </td>
                                </tr>
                            </table>
                        <?php
                                }
                                ?>

                        <table width="100%">
                            <tr>
                                <td style="height:50px;font-size:15px;border:1px solid #999999;padding:5px;">
                                    <div style="width:100%;text-align:right;font-size:11px;">
                                        <table width="100%">
                                            <tr>
                                                <td align="left"><?php if ($is_vat) {
                                                                                echo "<b>(부가세 10%별도)</b>";
                                                                            } ?></td>
                                                <td align="right">(단위, 원)</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div width="100%" style="position:absolute;z-index:1;height:20px;text-align:right;left:20px;padding:5px;">
                                        <img src="<?= $image_directory ?>/pet_dog.jpg" height="20px" />
                                    </div>
                                    <table width="100%" style="font-size:15px;border:0px solid #999999;padding:5px;border-collapse:collapse;">
                                        <tr style="height:30px;background-color:#ababab;color:#ffffff;text-align:center;font-weight:bold;">
                                            <td width="60%">상품명</td>
                                            <td width="10%"></td>
                                            <td width="30%">서비스가격</td>
                                        </tr>
                                        <?php
                                                $shop_fi_sql = "select * from tb_product_dog_etc where customer_id = '" . $artist_id . "';";
                                                $shop_fi_result = mysqli_query($connection,$shop_fi_sql);
                                                for ($vi = 0; $shop_fi_datas = mysqli_fetch_object($shop_fi_result); $vi = $vi + 1) {
                                                    $n_name = $shop_fi_datas->name;
                                                    $n_price = $shop_fi_datas->price;
                                                    $n_sequence = $shop_fi_datas->sequence;
                                                    $n_description = $shop_fi_datas->description;
                                                    $n_in_shop_product = $shop_fi_datas->in_shop_product;
                                                    $n_out_shop_product = $shop_fi_datas->out_shop_product;
                                                    ?>
                                            <tr style="font-size:14px;padding:10px;">
                                                <td>
                                                    <?= $n_name ?><br>
                                                    <font style="font-size:11px;color:#bbbbbb;"><?= $n_description ?></font>
                                                </td>
                                                <td align="right">
                                                    <?php if ($n_in_shop_product) { ?><img src="<?= $image_directory ?>/in_shop_b3.png" height="15px" /><br><?php } ?> <?php if ($n_out_shop_product) { ?><img src="<?= $image_directory ?>/out_shop.png" height="15px" /><?php } ?>
                                                </td>
                                                <td align="right">
                                                    <?= number_format($n_price) ?>원
                                                </td>
                                            </tr>
                                        <?php
                                                }
                                                if ($vi == 0) {
                                                    echo "<tr><td colspan='3' style='font-size:14px;'>등록된 상품이 없습니다.</td></tr>";
                                                }
                                                ?>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        <table width="100%">
                            <tr>
                                <td style="height:50px;font-size:15px;border:1px solid #999999;padding:5px;">
                                    <div style="width:100%;text-align:right;font-size:11px;">
                                        <table width="100%">
                                            <tr>
                                                <td align="left"><?php if ($is_vat) {
                                                                                echo "<b>(부가세 10%별도)</b>";
                                                                            } ?></td>
                                                <td align="right">(단위, 원)</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div width="100%" style="position:absolute;z-index:1;height:20px;text-align:right;left:20px;padding:5px;">
                                        <img src="<?= $image_directory ?>/pet_cat.jpg" height="20px" />
                                    </div>
                                    <table width="100%" style="font-size:15px;border:0px solid #999999;padding:5px;border-collapse:collapse;">
                                        <tr style="height:30px;background-color:#ababab;color:#ffffff;text-align:center;font-weight:bold;">
                                            <td width="60%">상품명</td>
                                            <td width="10%"></td>
                                            <td width="30%">서비스가격</td>
                                        </tr>
                                        <?php
                                                $shop_fi_sql = "select * from tb_product_cat_etc where customer_id = '" . $artist_id . "';";
                                                $shop_fi_result = mysqli_query($connection,$shop_fi_sql);
                                                for ($vi = 0; $shop_fi_datas = mysqli_fetch_object($shop_fi_result); $vi = $vi + 1) {
                                                    $n_name = $shop_fi_datas->name;
                                                    $n_price = $shop_fi_datas->price;
                                                    $n_sequence = $shop_fi_datas->sequence;
                                                    $n_description = $shop_fi_datas->description;
                                                    $n_in_shop_product = $shop_fi_datas->in_shop_product;
                                                    $n_out_shop_product = $shop_fi_datas->out_shop_product;
                                                    ?>
                                            <tr style="font-size:14px;padding:10px;">
                                                <td>
                                                    <?= $n_name ?><br>
                                                    <font style="font-size:11px;color:#bbbbbb;"><?= $n_description ?></font>
                                                </td>
                                                <td align="right">
                                                    <?php if ($n_in_shop_product) { ?><img src="<?= $image_directory ?>/in_shop_b3.png" height="15px" /><br><?php } ?> <?php if ($n_out_shop_product) { ?><img src="<?= $image_directory ?>/out_shop.png" height="15px" /><?php } ?>
                                                </td>
                                                <td align="right">
                                                    <?= number_format($n_price) ?>원
                                                </td>
                                            </tr>
                                        <?php
                                                }
                                                if ($vi == 0) {
                                                    echo "<tr><td colspan='3' style='font-size:14px;'>등록된 상품이 없습니다.</td></tr>";
                                                }
                                                ?>
                                    </table>
                                </td>
                            </tr>
                        </table>
<?php
        $shop_fi_sql = "select * from tb_product where customer_id = '" . $customer_id . "' order by sequence asc;";
        $shop_fi_result = mysqli_query($connection,$shop_fi_sql);
        while ($shop_fi_datas = mysqli_fetch_object($shop_fi_result)) {
            $name = $shop_fi_datas->name;
            $price = $shop_fi_datas->price;
            $sequence = $shop_fi_datas->sequence;
            $description = $shop_fi_datas->description;
            $time_to_work_hour = $shop_fi_datas->time_to_work_hour;
            $time_to_work_minute = $shop_fi_datas->time_to_work_minute;
            ?>	
			
<?php
        }
        ?>
                        포트폴리오 : <br>
                        <table width="100%" style="padding:0px;padding:0px 10px;">
                            <tr style='height:1px;'>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <?
                                        $shop_sql = "select * from tb_portfolio where customer_id = '" . $customer_id . "'";
                                        $shop_result = mysqli_query($connection,$shop_sql);
                                        for ($ii = 0; $shop_datas = mysqli_fetch_object($shop_result); $ii++) {
                                            $image_value = $shop_datas->image;
                                            ?>
                                    <td>
                                        <div id="portfolios<?= $ii ?>" style="display:inline-block;width:100%;height:100px;margin:0;background-image:url(<?= $image_value ?>);background-size:cover;background-repeat: no-repeat;">
                                        </div><br>
                                    </td>
                                <?php
                                            if ($ii % 3 == 2) {
                                                echo "</tr><tr>";
                                            }
                                        }
                                        ?>
                            </tr>
                        </table>
                        <br>
                        계좌정보 :<br>
                        <?php
                                $bank_sql = "select * from tb_artist_payment_info where customer_id = '" . $customer_id . "';";
                                $bank_result = mysqli_query($connection,$bank_sql);
                                if ($bank_datas = mysqli_fetch_object($bank_result)) {
                                    $enc_bankname = $bank_datas->bankname;
                                    $enc_account_holder = $bank_datas->account_holder;
                                    $enc_bank_account = $bank_datas->bank_account;

                                    $crypto = new Crypto();
                                    $bankname = $crypto->decode($enc_bankname, $access_key, $secret_key);
                                    $account_holder = $crypto->decode($enc_account_holder, $access_key, $secret_key);
                                    $bank_account = $crypto->decode($enc_bank_account, $access_key, $secret_key);

                                    ?>
                            <?= $bankname ?> / <font style="color:#000000;"><?= $bank_account ?></font> (예금주 : <?= $account_holder ?>)<br>
                        <?php
                                }
                                ?>



                        <div style="height:5px;"></div>
                        <a href="#" class="agree_button" onclick="javascript:agree_artist('<?= $customer_id ?>');">승 인</a>
                        <a href="#" class="agree_button" onclick="javascript:return_artist('<?= $customer_id ?>');">반 려</a>
                    </td>
                </tr>
            </table>
        </div>
    <?php
        }
        ?>
    <br><br><br><br>
    <script>
        function delete_artist(c_id) {
            var result = confirm("삭제하시겠습니까?");
            if(result){
                $.ajax({
                    url: 'delete_request_artist.php',
                    data: {
                        customer_id: c_id
                    },
                    type: 'POST',
                    success: function(data) {
                        alert(data);
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        alert("적용실패");
                    }
                });
            }
        }

        function agree_artist(c_id) {
            var result = confirm("승인하시겠습니까?");
            if(result){
                $.ajax({
                    url: '<?= $admin_directory ?>/agree_request_open_shop.php',
                    data: {
                        customer_id: c_id
                    },
                    type: 'POST',
                    success: function(data) {
                        alert(data);
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        alert("적용실패");
                    }
                });
            }
        }

        function return_artist(c_id) {
            var result = confirm("반려하시겠습니까?");
            if(result){
                $.ajax({
                    url: '<?= $admin_directory ?>/return_request_open_shop.php',
                    data: {
                        customer_id: c_id
                    },
                    type: 'POST',
                    success: function(data) {
                        alert(data);
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        alert("적용실패");
                    }
                });
            }
        }
    </script>
<?php
}
?>