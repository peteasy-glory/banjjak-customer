<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-152043924-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'UA-152043924-1');
</script>
<?php
include "../include/top.php";
include "../include/Crypto.class.php";
include "../Mobile_Detect.php";
include "../include/check_header_log.php";

include "mainpage_top_menu.php";
include "test_slick.php";
?>

<?php
$cart_artist_id = "";
$cart_artist_id = $_SESSION['gobeauty_cart_artist_id'];
?>
<style>
@font-face {font-family: 'BMJUA';src: url("../fonts/BMJUA.ttf");}
@font-face {font-family: 'Jalnan';src: url("../fonts/Jalnan.otf");}
@font-face {font-family: 'NanumGothic';src: url("../fonts/NanumGothic.ttf");}
* {font-family: 'SCDream2','Jalnan','NanumGothic';font-weight: bold;}
* {font-family: 'BMJUA';font-weight: normal;}
b{font-family:'NanumGothic';font-weight: bold;}
.sub_title {height: 40px;text-align: center;padding: 10px 0px 5px 0px;background-color: #e1e1e1;color: #333333;font-size: 16px;font-weight: bold;}
.artist_list {width: 100%;margin-bottom: 5px;padding: 20px 0px;border-top: 5px solid #e1e1e1;}
.artist_name {padding-bottom: 10px;line-height: 40px;font-family:'NanumGothic';}
.artist_rating_1 {width: 18px;float: left;margin-top: 2px;margin-right: 5px;}
.artist_rating_1 img {width: 100%;}
.artist_content {padding-top: 10px;width: 90%;margin: 0px auto;font-size: 16px;}
.content_01 {height: 30px;clear: both;}
.content_01 div {float: left;width: 50px;margin-right: 2px;}
.content_01 div img {width: 100%;}
.content_02 div {padding: 2px 0px;}
.content_02 div div:first-child {float: left;width: 80px;margin-right: 5px;letter-spacing: 2px;}
a {text-decoration: none;}
.artist_list a:link {color: #333333;}
.artist_list a:visited {color: #333333;}
.artist_list a:hover {color: #333333;}
.artist_list a:active {color: #333333;}
.bottom_notice a:link {color: #999999;}
.bottom_notice a:visited {color: #999999;}
.bottom_notice a:hover {color: #999999;}
.bottom_notice a:active {color: #999999;}
select {font-family: 'BMJUA';height: 30px;width: 120px;padding-left: 7px;font-size: 15px;font-weight: normal;color: #000000;border: 1px solid #999999;margin-left: 5px;border-radius: 5px;background-color: #fff;}
.f_subwrap ul {padding: 0px;padding-top: 10px;}
.f_subwrap li {font-family:'NanumGothic';font-weight: bold;list-style: none;margin-bottom: 3px;}
.home_pro_type {background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #f9f9f9), color-stop(1, #e9e9e9));background: -moz-linear-gradient(center top, #f9f9f9 5%, #e9e9e9 100%);filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f9f9f9', endColorstr='#e9e9e9');background-color: #f9f9f9;-webkit-border-top-left-radius: 28px;-moz-border-radius-topleft: 28px;border-top-left-radius: 28px;-webkit-border-top-right-radius: 28px;-moz-border-radius-topright: 28px;border-top-right-radius: 28px;-webkit-border-bottom-right-radius: 0px;-moz-border-radius-bottomright: 0px;border-bottom-right-radius: 0px;-webkit-border-bottom-left-radius: 0px;-moz-border-radius-bottomleft: 0px;border-bottom-left-radius: 0px;text-indent: 0px;border: 1px solid #dcdcdc;display: inline-block;color: #666666;font-family: Arial;font-size: 16px;font-weight: bold;font-style: normal;height: 33px;line-height: 33px;width: 100%;text-decoration: none;text-align: center;}
.home_pro_type:hover {background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #e9e9e9), color-stop(1, #f9f9f9));background: -moz-linear-gradient(center top, #e9e9e9 5%, #f9f9f9 100%);filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#e9e9e9', endColorstr='#f9f9f9');background-color: #e9e9e9;}
.home_pro_type:active {position: relative;top: 1px;}
.more_view {width: 100%;height: 50px;line-height: 50px;margin: 0px;text-align: center;font-size: 15px;font-weight: bold;display: inline-block;color: #999999;vertical-align: bottom;}
hr.type_4 {border: 0;height: 5px;background-color: #d6d6d6;background-repeat: no-repeat;}
 #fixed-menu {height:auto;}
.reservation_button {
background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #2869B8), color-stop(1, #2869B8));
background: -moz-linear-gradient(center top, #2869B8 5%, #2869B8 100%);
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#2869B8', endColorstr='#2869B8');
background-color: #2869B8;
-webkit-border-top-left-radius: 42px;
-moz-border-radius-topleft: 42px;
border-top-left-radius: 42px;
-webkit-border-top-right-radius: 42px;
-moz-border-radius-topright: 42px;
border-top-right-radius: 42px;
-webkit-border-bottom-right-radius: 42px;
-moz-border-radius-bottomright: 42px;
border-bottom-right-radius: 42px;
-webkit-border-bottom-left-radius: 42px;
-moz-border-radius-bottomleft: 42px;
border-bottom-left-radius: 42px;
text-indent: 0;
display: inline-block;
color: #ffffff;
font-family: Arial;
font-size: 30px;
font-weight: bold;
font-style: normal;
height: 59px;
line-height: 59px;
width: 59px;
text-decoration: none;
text-align: center;
}

.reservation_button:hover {
background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #2869B8), color-stop(1, #2869B8));
background: -moz-linear-gradient(center top, #2869B8 5%, #2869B8 100%);
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#2869B8', endColorstr='#2869B8');
background-color: #2869B8;
}

.reservation_button:active {
position: relative;
top: 1px;
}

a {
text-decoration: none;
}

a:link {
color: #333333;
}

a:visited {
color: #333333;
}

a:hover {
color: #333333;
}

a:active {
color: #333333;
}

.custom_modal {
display: block;
/* Hidden by default */
position: fixed;
/* Stay in place */
z-index: 999999;
/* Sit on top */
padding-top: 100px;
/* Location of the box */
left: 0;
top: 0;
width: 100%;
/* Full width */
height: 100%;
/* Full height */
overflow: auto;
/* Enable scroll if needed */
background-color: rgb(0, 0, 0);
/* Fallback color */
background-color: rgba(0, 0, 0, 0.9);
/* Black w/ opacity */
}

/* Modal Content (image) */
.custom-modal-content {
margin: auto;
display: block;
width: 90%;
}

.popup_wrap {
margin: 0px auto;
display: none;
}

.popup_img img {
width: 100%;
}

.popup_btn_wrap {
width: 100%;
display: table;

}

.popup_btn_01 {
width: 100%;
text-align: center;
font-size: 18px;
color: #ffffff;
background-color: #f5bf2e;
margin-top: 10px;
padding: 10px 0px;
font-weight: bold;
border-radius: 3px;
}

.popup_btn_02 {
width: 100%;
margin-top: 10px;
font-size: 14px;
color: #ffffff;
text-align: center;
text-decoration: underline;
text-underline-position: under;
}

.search img {
width: 24px;
margin-top: 3px;
}

.reflash {
margin: 0px 10px;
}

.reflash img {
width: 24px;
margin-top: 3px;
}

.search img {
width: 24px;
margin-top: 3px;
}

.reflash {
margin: 0px 10px;
}

.reflash img {
width: 24px;
margin-top: 3px;
}

.footer_btn img {
width: 23px;
}

.quick-btn03 {
position: fixed;
bottom: 210px;
right: 0px;
z-index: 9800;
width: 70px;
height: 70px;
font-size: 16px;
}

.quick-btn03 img {
width: 100%;
}

.quick-btn01 {
position: fixed;
bottom: 145px;
right: 0px;
z-index: 9800;
width: 70px;
height: 70px;
font-size: 16px;
}

.quick-btn01 img {
width: 100%;
}

.quick-btn02 {
position: fixed;
bottom: 80px;
right: 0px;
z-index: 9800;
width: 70px;
height: 70px;

}

.quick-btn04 {
position: fixed;
bottom: 80px;
left: 0px;
z-index: 9800;
width: 70px;
height: 70px;

}

.quick-btn04 img {
width: 100%;
}

.quick-btn02 img {
width: 100%;
}

.f_subwrap ul:last-child {
margin-top: 10px;
padding-bottom: 20px;
}


/* .right_wrap {} */

.right_wrap div {
color: #ffffff;
float: left;
}

.right_wrap div:last-child {

margin-left: 6px;
}

/* .left_wrap {} */

.left_wrap div {
color: #ffffff;
float: left;
}

.left_wrap div:last-child {

margin-left: 6px;
}

.info_wrap {
width: 90%;
padding: 20px 0px;
background-color: #E6EBF0;
margin: 0px auto;
text-align: center;
color: #353535;
border-radius: 10px;
}

.info_icon {
width: 40px;
margin: 0px auto;
margin-bottom: 10px;
}

.info_icon img {
width: 100%;

}

.info_1 {
font-size: 18px;
font-weight: bold;
margin-bottom: 5px;

}

.info_2,
.info_3 {
font-size: 14px;
font-weight: bold;

}

.shop_wrap {
width: 90%;
margin: 0px auto;
}

.shop_reco {
width: 40px;
height: 40px;
float: left;
margin-right: 6px;
}

.shop_reco img {
width: 100%;
}

.count_wrap {
position: relative;
}

.count {
width: 60px;
position: absolute;
top: 0px;
left: 0px;
color: #fff;
text-align: center;
line-height: 40px;
font-size: 20px;
}



</style>
<?php
$user_id = isset($_SESSION['gobeauty_user_id']) ? $_SESSION['gobeauty_user_id'] : "";
$user_name = isset($_SESSION['gobeauty_user_nickname']) ? $_SESSION['gobeauty_user_nickname'] : "";

$is_artist = 0;
$is_android = "";
$is_login = false;
$reg_date;
$wait_count = 0;
// $update_date = strtotime("2019-11-03");
$is_sql = "select * from tb_customer tc, tb_shop ts where tc.id = '" . $user_id . "' and ts.customer_id = '" . $user_id . "';";
// error_log('-----'.$is_sql);
$is_result = mysql_query($is_sql);
if ($is_rows = mysql_fetch_object($is_result)) {
    $reg_date = $is_rows->registration_time;
    $reg_date = strtotime($reg_date);
    $is_login = true;
    if ($is_rows->token != null && $is_rows->token != "") {
        if ($is_rows->is_android == 1) {
            $is_android = "1";
        }
    }

    if ($is_rows->my_shop_flag && $is_rows->open_flag) {
        $is_artist = 1;
    }
}

//----- ?????? ?????? ?????? ??????
if ($is_artist == 1) {
    $pet_sql =
        "SELECT count(tpl.payment_log_seq) AS wait_count
            FROM tb_payment_log tpl, tb_mypet tm, tb_customer tc
            WHERE tpl.approval = 0
            AND tpl.pet_seq = tm.pet_seq 
            AND tpl.customer_id = tc.id
            AND timestampdiff(minute, update_time, now()) < 720
            AND tpl.artist_id = '{$user_id}'";
    $pet_result = mysql_query($pet_sql);
    // error_log('----- $pet_sql : ' . $pet_sql);
    if ($pet_result_rows = mysql_fetch_object($pet_result)) {
        $wait_count = $pet_result_rows->wait_count;
    }
}

// $detect = new Mobile_Detect;
// if ($detect->isMobile()) {
//     if ($is_login) {
//         if ($reg_date < $update_date) {
?>
<!-- <div class="custom_modal popup_wrap">
                    <div class="custom-modal-content">
                        <div class="popup_img"><img src="../images/update_notice.jpg"></div>
                        <div class="popup_btn_wrap">
                            <?php
                            // if ($is_android == "1") {
                            ?>
                                <button class="popup_btn_01" onclick="close_popup_for_days(1)">????????? ?????????</button>
                                <button class="popup_btn_02" onclick="update_now(100)">?????? ????????????</button>
                            <?php
                            // } else {
                            ?>
                                <button class="popup_btn_01" onclick="close_popup_for_days(1)">????????? ??????<br>????????? ??????</button>
                                <button class="popup_btn_02" onclick="update_now(100)">???????????? ??????<br>?????? ??????</button>
                            <?php
                            // }
                            ?>
                        </div>
                    </div>
                </div> -->
<?php
//         }
//     }
// }
?>
<script>
    if (window.Android != undefined) {
        var get_token = window.Android.onAppGetToken();
        if (get_token) {
            $.ajax({
                url: '/login/android.php',
                data: {
                    id: '<?= $user_id ?>',
                    token: get_token
                },
                type: 'POST',
                success: function(data) {},
                error: function(xhr, status, error) {}
            });
        }
    }

    <?php
    if ($cart_artist_id != "") {
    ?>
        $.ajax({
            url: '../artist/set_cart_session.php',
            data: {
                "key": "cart_init"
            },
            type: 'POST',
            success: function(data) {
                // alert(data);
            },
            error: function(xhr, status, error) {}
        });
    <?php
    }
    ?>
</script>
<table width="100%" height="40px" style="background-color:#ffffff; margin-bottom: 4px;">
    <tr style="text-align:center;">
        <td height="30px" style="font-size:15px;font-weight:bold;">
            <!--??????<br>??????-->
        </td>
        <td align="left" class="select_wrap"><select name="top_region" id="top_region">
                <?php
                $s_top = isset($_REQUEST['top']) ? $_REQUEST['top'] : "";
                $s_middle = isset($_REQUEST['middle']) ? $_REQUEST['middle'] : "";

                //$top_sql = "select distinct top from tb_region where open_flag = true and ( top = '??????' or top = '?????????' or top = '??????' );";
                $top_sql = "select distinct top from tb_region where open_flag = true;";
                $top_result = mysql_query($top_sql);
                while ($top_datas = mysql_fetch_object($top_result)) {
                    $top = $top_datas->top;
                    echo "<option value='$top'";
                    if (trim($top) == trim($s_top)) {
                        echo "selected";
                    }
                    echo ">$top</option>";
                }
                ?>
            </select>
            <select name="middle_region" id="middle_region">
            </select></td>
        <td align="right">
            <!-- <img src="<?= $image_directory ?>/main_search_button.png" width="30px" onclick="select_region();"> -->
            <a class="search" href="javascript:select_region();"><img src="../images/search.png"></a> <a class="reflash" href="javascript:reset_region();" style="color:#000"><img src="../images/reflash.png"></a></td>
    </tr>
    <tr>
        <td height="3px"></td>
    </tr>
</table>

<!-- <?php
        // $detect = new Mobile_Detect;
        // if ($detect->isMobile()) {
        //     if (!$is_login) {
        ?>
        <div class="custom_modal popup_wrap">
            <div class="custom-modal-content">
                <div class="popup_img"><img src="../images/popup_app.png"></div>
                <div class="popup_btn_wrap">
                    <button class="popup_btn_01" onclick="go_app(1)">?????? ????????? ?????? ></button>
                    <button class="popup_btn_02" onclick="close_popup_for_days(1)">???????????????. ?????? ?????????!</button>
                </div>
            </div>
        </div>
<?php
//     }
// }
?> -->

<script>


    function checkMobile() {
        var varUA = navigator.userAgent.toLowerCase(); //userAgent ??? ??????
        if (varUA.indexOf('android') > -1) {
            //???????????????
            return "android";
        } else if (varUA.indexOf("iphone") > -1 || varUA.indexOf("ipad") > -1 || varUA.indexOf("ipod") > -1) {
            //IOS
            return "ios";
        } else {
            //?????????, ??????????????? ???
            return "other";
        }
    }

    function setCookie_popup(name, value, expiredays) {
        var todayDate = new Date();
        todayDate.setDate(todayDate.getDate() + expiredays);
        document.cookie = name + '=' + escape(value) + '; path=/; expires=' + todayDate.toGMTString() + ';'
    }

    function getCookie_popup(name) {
        var obj = name + "=";
        var x = 0;
        while (x <= document.cookie.length) {
            var y = (x + obj.length);
            if (document.cookie.substring(x, y) == obj) {
                if ((endOfCookie = document.cookie.indexOf(";", y)) == -1)
                    endOfCookie = document.cookie.length;
                return unescape(document.cookie.substring(y, endOfCookie));
            }
            x = document.cookie.indexOf(" ", x) + 1;
            if (x == 0)
                break;
        }
        return "";
    }

    function close_popup_for_days(days) {
        setCookie_popup('review_popup', 'Y', days);
        $(".popup_wrap").css({
            "display": "none"
        });
    }

    function go_app(days) {
        // setCookie_popup('review_popup', 'Y', days);
        $(".popup_wrap").css({
            "display": "none"
        });

        if (checkMobile() != "ios") {
            window.open("https://play.google.com/store/apps/details?id=m.kr.gobeauty&hl=ko", "_blank");
        } else {
            window.open("https://apps.apple.com/kr/app/id1436568194", "_blank");
        }
    }

    function close_popup() {
        $(".popup_wrap").css({
            "display": "none"
        });
    }

    var top_region = document.getElementById('top_region');
    var middle_region = document.getElementById('middle_region');

    function reset_region() {
        location.href = '<?= $mainpage_directory ?>';
    }

    function select_region() {
        var selected_top = top_region.options[top_region.selectedIndex].value;
        var selected_middle = middle_region.options[middle_region.selectedIndex].value;
        location.href = '?top=' + selected_top + '&middle=' + selected_middle;
    }

    function add_middle() {
        var artist_id = '<?= isset($artist_id) ? $artist_id : "" ?>';
        var selected_top = top_region.options[top_region.selectedIndex].value;
        var post_data = 'top_region=' + encodeURI(selected_top) + '&artist_id=' + artist_id + '&type=open_flag';
        $.ajax({
            url: '<?= $mainpage_directory ?>/get_middle_region.php',
            data: post_data,
            type: 'POST',
            success: function(data) {
                var array_middle = data.split(",");
                var select = document.getElementById('middle_region');
                select.options.length = 0; // clear out existing items
                for (var i = 0; i < array_middle.length; i++) {
                    var d = array_middle[i];
                    select.options.add(new Option(d, d));
                    if (d.trim() == '<?= $s_middle ?>'.trim()) {
                        $("#middle_region").val(d).prop("selected", true);
                    }
                }
                //$("#middle_region").val().prop("selected", true);
            },
            error: function(xhr, status, error) {}
        });
    }
    add_middle();
    top_region.addEventListener('change', function(e) {
        add_middle();
    });
</script>
<?php
if ($s_top && $s_middle) {
?>
    <?php
    $is_able_offline_shop = 0;
    $crypto = new Crypto();
    $enc_region = $crypto->encode(trim($s_top) . ":" . trim($s_middle), $access_key, $secret_key);
    $artist_sql1 = "select * from tb_request_artist where TRIM(region) = TRIM('" . $enc_region . "') ORDER BY RAND();";
    $artist_result1 = mysql_query($artist_sql1);
    for ($index = 0; $artist_datas1 = mysql_fetch_object($artist_result1); $index++) {
        $is_got_offline_shop = $artist_datas1->is_got_offline_shop;
        if (!$is_got_offline_shop) {
            continue;
        }
        $enc_customer_id = $artist_datas1->customer_id;
        $dec_customer_id = $crypto->decode(trim($enc_customer_id), $access_key, $secret_key);
        $artist_sql = "select * from tb_shop where open_flag = true and enable_flag = true and customer_id = '" . $dec_customer_id . "';";
        $artist_result = mysql_query($artist_sql);
        if ($artist_datas = mysql_fetch_object($artist_result)) {

            //----- ?????? ??????
            $avg_value = "0";
            $avg_sql = "SELECT avg(rating) AS avg_rating FROM tb_usage_reviews WHERE artist_id = '" . $dec_customer_id . "' AND is_delete = 0 AND rating IS NOT NULL;";
            $avg_result = mysql_query($avg_sql);
            if ($avg_rows = mysql_fetch_object($avg_result)) {
                $avg_value = $avg_rows->avg_rating;
                $avg_value = substr($avg_value, 0, 3);
            }
            if (!$avg_value) {
                $avg_value = '0';
            }

            //----- ?????? ???
            $review_count = "0";
            $review_sql = "SELECT count(review_seq) as review_cnt FROM tb_usage_reviews WHERE artist_id = '" . $dec_customer_id . "' AND is_delete = 0;";
            $review_result = mysql_query($review_sql);
            if ($review_rows = mysql_fetch_object($review_result)) {
                $review_count = $review_rows->review_cnt;
            }
            if (!$review_count) {
                $review_count = '0';
            }

            //----- ?????? ?????? ??? ?????? ??????
            $str_rest_public_holiday = "";
            $working_sql = "select * from tb_working_schedule tws, tb_regular_holiday trh where tws.customer_id = '" . $dec_customer_id . "' and trh.customer_id = '" . $dec_customer_id . "';";
            $working_result = mysql_query($working_sql);
            if ($working_datas = mysql_fetch_object($working_result)) {
                $working_start = $working_datas->working_start;
                $working_end = $working_datas->working_end;
                $rest_public_holiday = $working_datas->rest_public_holiday;
                if ($working_datas->is_monday) {
                    $str_rest_public_holiday = "?????????";
                }
                if ($working_datas->is_tuesday) {
                    $str_rest_public_holiday = " ?????????";
                }
                if ($working_datas->is_wednesday) {
                    $str_rest_public_holiday = " ?????????";
                }
                if ($working_datas->is_thursday) {
                    $str_rest_public_holiday = " ?????????";
                }
                if ($working_datas->is_friday) {
                    $str_rest_public_holiday = " ?????????";
                }
                if ($working_datas->is_saturday) {
                    $str_rest_public_holiday = " ?????????";
                }
                if ($working_datas->is_sunday) {
                    $str_rest_public_holiday = " ?????????";
                }
                if (!$working_datas->is_monday && !$working_datas->is_tuesday && !$working_datas->is_wednesday && !$working_datas->is_thursday && !$working_datas->is_friday && !$working_datas->is_saturday && !$working_datas->is_sunday) {
                    $str_rest_public_holiday = "??????";
                }
            }

            //----- ?????? ??????
            $shop_address = "";
            $crypto = new Crypto();
            $enc_artist_id = $crypto->encode(trim($dec_customer_id), $access_key, $secret_key);
            $off_sql = "select * from tb_request_artist where customer_id = '" . $enc_artist_id . "';";
            $off_result = mysql_query($off_sql);
            if ($off_rows = mysql_fetch_object($off_result)) {
                if ($off_rows->is_got_offline_shop == 1) {
                    $shop_address = $crypto->decode($off_rows->offline_shop_address, $access_key, $secret_key);
                    if ($shop_address) {
                        $shop_address = str_replace("|", "", strstr($shop_address, "|"));
                    } else {
                        $shop_address = "(?????? ?????? ??????)";
                    }
                }
            }

            /** ????????? ??? ?????? ??? ??????/?????? ??????
             * 
             * icon_01.png = ?????????
             * icon_02.png = ?????????
             * icon_03.png = ?????????
             * icon_04.png = ?????????
             * icon_05.png = ??????
             * icon_06.png = ??????
             */
            $total_service = "";
            $crypto = new Crypto();
            $enc_artist_id = $crypto->encode(trim($dec_customer_id), $access_key, $secret_key);

            $cat_query = "SELECT * FROM tb_product_cat WHERE customer_id = '{$dec_customer_id}'";
            $cat_result = mysql_query($cat_query);
            $cat_count = mysql_num_rows($cat_result);

            $dog_query = "SELECT * FROM tb_product_dog_static WHERE customer_id = '{$dec_customer_id}'";
            $dog_result = mysql_query($dog_query);
            $dog_count = mysql_num_rows($dog_result);

            $out_query = "SELECT second_type FROM tb_product_dog_static WHERE out_shop_product = 1 AND customer_id = '{$dec_customer_id}'
                                                                UNION ALL
                                                                SELECT second_type FROM tb_product_cat WHERE out_shop_product = 1 AND customer_id = '{$dec_customer_id}'";
            $out_result = mysql_query($out_query);
            $out_count = mysql_num_rows($out_result);

            $in_query = "SELECT second_type FROM tb_product_dog_static WHERE in_shop_product = 1 AND customer_id = '{$dec_customer_id}'
                                                                UNION ALL
                                                                SELECT second_type FROM tb_product_cat WHERE in_shop_product = 1 AND customer_id = '{$dec_customer_id}'";
            $in_result = mysql_query($in_query);
            $in_count = mysql_num_rows($in_result);

            if ($dog_count > 0) {
                $dog_product_query = "SELECT * FROM tb_product_dog_static WHERE customer_id = '{$dec_customer_id}'";
                $dog_product_result = mysql_query($dog_product_query);
                for ($index = 0; $dog_product_rows = mysql_fetch_object($dog_product_result); $index++) {
                    if ($dog_product_rows->second_type == "???????????????") {
                        $total_service .= "<div><img src='../images/icon_01.png'></div>";
                    } else if ($dog_product_rows->second_type == "???????????????") {
                        $total_service .= "<div><img src='../images/icon_02.png'></div>";
                    } else if ($dog_product_rows->second_type == "???????????????") {
                        $total_service .= "<div><img src='../images/icon_03.png'></div>";
                    } else if ($dog_product_rows->second_type == "???????????????") {
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


            $check_sql = "select enable_flag from tb_customer where id = '" . $artist_datas->customer_id . "';";
            $check_result = mysql_query($check_sql);
            if ($check_rows = mysql_fetch_object($check_result)) {
                if (!$check_rows->enable_flag) {
                    continue;
                }
            }

            $is_able_offline_shop = 1;
    ?>
            <a href="../artist/?artist_name=<?= urlencode($artist_datas->name) ?>&top=<?= $s_top ?>&middle=<?= $s_middle ?>">
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
                            <font style="font-size:18px;"><b>
                                    <?= $artist_datas->name ?>
                                </b></font>
                            <div>
                                <!-- <div class="artist_rating_1"><img src="../images/img_star_1.png"></div>
										 <div class="artist_rating_2"><b><?= $avg_value ?></b>(<?= $review_count ?>)</div> -->
                            </div>
                        </div>
                    </div>
                    <div style="width:90%;height:200px;margin: 0px auto;position:relative;z-index:-1;background-image:url(<?= $artist_datas->front_image ?>);background-size: 100%; background-position: center;border-radius:25px;"> </div>
                    <div class="artist_content">
                        <div class="content_01">
                            <?= $total_service ?>
                        </div>
                        <div class="content_02">
                            <div class="con_01">
                                <div>????????????</div>
                                <div>
                                    <?= $working_start ?>
                                    :00 ~
                                    <?= $working_end ?>
                                    :00</div>
                            </div>
                            <div class="con_02">
                                <div>????????????</div>
                                <div>
                                    <?= $str_rest_public_holiday ?>
                                </div>
                            </div>
                            <div class="con_03">
                                <div>????????????</div>
                                <div>
                                    <?= $shop_address ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        <?php
        }
        ?>
    <?php
    }
    if ($index == 0 || $is_able_offline_shop == 0) {
    ?>
        <center>
            <br>
            <font style="font-size:14px;font-weight:bold;">??? ????????? ?????????<br>
            </font>
            <font style="font-size:14px;font-weight:bold;">??????/???????????????????????? ????????????.<br>
            </font>
            <font style="font-size:12px;font-weight:bold;">(????????? Beta????????? ?????????,<br>
            </font>
            <font style="font-size:12px;font-weight:bold;">????????? ?????????????????? ????????? ??? ????????????.)<br>
            </font>
        </center>
    <?php
    }
    ?>
    <div style="width:100%;height:10px;"></div>
    <div class="sub_title"> <b>???????????? ???</b><br>
    </div>
    <?php
    $is_able_goout_shop = 0;
    $artist_sql = "select * from tb_shop where open_flag = true and enable_flag = true and customer_id in (select customer_id from tb_working_region where region_id in (select id from tb_region where TRIM(top) = TRIM('" . $s_top . "') and TRIM(middle) = TRIM('" . $s_middle . "')));";
    $artist_result = mysql_query($artist_sql);
    for ($index = 0; $artist_datas = mysql_fetch_object($artist_result); $index++) {

        $is_out = 0;
        $shop_fi_sql = "select * from tb_product_dog_static where customer_id = '" . $artist_datas->customer_id . "';";
        $shop_fi_result = mysql_query($shop_fi_sql);
        while ($shop_fi_datas = mysql_fetch_object($shop_fi_result)) {
            $n_out_shop_product = $shop_fi_datas->out_shop_product;
            if ($n_out_shop_product) {
                $is_out = 1;
            }
        }

        $shop_fi_sql = "select * from tb_product_cat where customer_id = '" . $artist_datas->customer_id . "';";
        $shop_fi_result = mysql_query($shop_fi_sql);
        if ($shop_fi_datas = mysql_fetch_object($shop_fi_result)) {
            $n_out_shop_product = $shop_fi_datas->out_shop_product;
            if ($n_out_shop_product) {
                $is_out = 1;
            }
        }

        //----- ?????? ??????
        $avg_value = "0";
        $avg_sql = "SELECT avg(rating) AS avg_rating FROM tb_usage_reviews WHERE artist_id = '" . $artist_datas->customer_id . "' AND is_delete = 0 AND rating IS NOT NULL;";
        $avg_result = mysql_query($avg_sql);
        if ($avg_rows = mysql_fetch_object($avg_result)) {
            $avg_value = $avg_rows->avg_rating;
            $avg_value = substr($avg_value, 0, 3);
        }
        if (!$avg_value) {
            $avg_value = '0';
        }

        //----- ?????? ???
        $review_count = "0";
        $review_sql = "SELECT count(review_seq) as review_cnt FROM tb_usage_reviews WHERE artist_id = '" . $artist_datas->customer_id . "' AND is_delete = 0;";
        $review_result = mysql_query($review_sql);
        if ($review_rows = mysql_fetch_object($review_result)) {
            $review_count = $review_rows->review_cnt;
        }
        if (!$review_count) {
            $review_count = '0';
        }

        //----- ?????? ?????? ??? ?????? ??????
        $str_rest_public_holiday = "";
        $working_sql = "select * from tb_working_schedule tws, tb_regular_holiday trh where tws.customer_id = '" . $artist_datas->customer_id . "' and trh.customer_id = '" . $artist_datas->customer_id . "';";
        $working_result = mysql_query($working_sql);
        if ($working_datas = mysql_fetch_object($working_result)) {
            $working_start = $working_datas->working_start;
            $working_end = $working_datas->working_end;
            $rest_public_holiday = $working_datas->rest_public_holiday;
            if ($working_datas->is_monday) {
                $str_rest_public_holiday = "?????????";
            }
            if ($working_datas->is_tuesday) {
                $str_rest_public_holiday = " ?????????";
            }
            if ($working_datas->is_wednesday) {
                $str_rest_public_holiday = " ?????????";
            }
            if ($working_datas->is_thursday) {
                $str_rest_public_holiday = " ?????????";
            }
            if ($working_datas->is_friday) {
                $str_rest_public_holiday = " ?????????";
            }
            if ($working_datas->is_saturday) {
                $str_rest_public_holiday = " ?????????";
            }
            if ($working_datas->is_sunday) {
                $str_rest_public_holiday = " ?????????";
            }
            if (!$working_datas->is_monday && !$working_datas->is_tuesday && !$working_datas->is_wednesday && !$working_datas->is_thursday && !$working_datas->is_friday && !$working_datas->is_saturday && !$working_datas->is_sunday) {
                $str_rest_public_holiday = "??????";
            }
        }

        //----- ?????? ??????
        $shop_address = "";
        $crypto = new Crypto();
        $enc_artist_id = $crypto->encode(trim($artist_datas->customer_id), $access_key, $secret_key);
        $off_sql = "select * from tb_request_artist where customer_id = '" . $enc_artist_id . "';";
        $off_result = mysql_query($off_sql);
        if ($off_rows = mysql_fetch_object($off_result)) {
            if ($off_rows->is_got_offline_shop == 1) {
                $shop_address = $crypto->decode($off_rows->offline_shop_address, $access_key, $secret_key);
                if ($shop_address) {
                    $shop_address = str_replace("|", "", strstr($shop_address, "|"));
                } else {
                    $shop_address = "(?????? ?????? ??????)";
                }
            }
        }

        /** ????????? ??? ?????? ??? ??????/?????? ??????
         * 
         * icon_01.png = ?????????
         * icon_02.png = ?????????
         * icon_03.png = ?????????
         * icon_04.png = ?????????
         * icon_05.png = ??????
         * icon_06.png = ??????
         */
        $total_service = "";
        $crypto = new Crypto();
        $enc_artist_id = $crypto->encode(trim($artist_datas->customer_id), $access_key, $secret_key);

        $cat_query = "SELECT * FROM tb_product_cat WHERE customer_id = '{$artist_datas->customer_id}'";
        $cat_result = mysql_query($cat_query);
        $cat_count = mysql_num_rows($cat_result);

        $dog_query = "SELECT * FROM tb_product_dog_static WHERE customer_id = '{$artist_datas->customer_id}'";
        $dog_result = mysql_query($dog_query);
        $dog_count = mysql_num_rows($dog_result);

        $out_query = "SELECT second_type FROM tb_product_dog_static WHERE out_shop_product = 1 AND customer_id = '{$artist_datas->customer_id}'
                                                            UNION ALL
                                                            SELECT second_type FROM tb_product_cat WHERE out_shop_product = 1 AND customer_id = '{$artist_datas->customer_id}'";
        $out_result = mysql_query($out_query);
        $out_count = mysql_num_rows($out_result);

        $in_query = "SELECT second_type FROM tb_product_dog_static WHERE in_shop_product = 1 AND customer_id = '{$artist_datas->customer_id}'
                                                            UNION ALL
                                                            SELECT second_type FROM tb_product_cat WHERE in_shop_product = 1 AND customer_id = '{$artist_datas->customer_id}'";
        $in_result = mysql_query($in_query);
        $in_count = mysql_num_rows($in_result);

        if ($dog_count > 0) {
            $dog_product_query = "SELECT * FROM tb_product_dog_static WHERE customer_id = '{$artist_datas->customer_id}'";
            $dog_product_result = mysql_query($dog_product_query);
            for ($index = 0; $dog_product_rows = mysql_fetch_object($dog_product_result); $index++) {
                if ($dog_product_rows->second_type == "???????????????") {
                    $total_service .= "<div><img src='../images/icon_01.png'></div>";
                } else if ($dog_product_rows->second_type == "???????????????") {
                    $total_service .= "<div><img src='../images/icon_02.png'></div>";
                } else if ($dog_product_rows->second_type == "???????????????") {
                    $total_service .= "<div><img src='../images/icon_03.png'></div>";
                } else if ($dog_product_rows->second_type == "???????????????") {
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


        $check_sql = "select enable_flag from tb_customer where id = '" . $artist_datas->customer_id . "';";
        $check_result = mysql_query($check_sql);
        if ($check_rows = mysql_fetch_object($check_result)) {
            if (!$check_rows->enable_flag) {
                continue;
            }
        }

        if (!$is_out) {
            continue;
        }


        $is_able_goout_shop = 1;
    ?>
        <a href="../artist/?artist_name=<?= urlencode($artist_datas->name) ?>&top=<?= $s_top ?>&middle=<?= $s_middle ?>">
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
                        <font style="font-size:18px;"><b>
                                <?= $artist_datas->name ?>
                            </b></font>
                        <div>
                            <!-- <div class="artist_rating_1"><img src="../images/img_star_1.png"></div>
                        <div class="artist_rating_2"><b><?= $avg_value ?></b>(<?= $review_count ?>)</div> -->
                        </div>
                    </div>
                </div>
                <div style="width:90%;height:200px;margin: 0px auto;position:relative;z-index:-1;background-image:url(<?= $artist_datas->front_image ?>);background-size: 100%; background-position: center;border-radius:25px;"> </div>
                <div class="artist_content">
                    <div class="content_01">
                        <?= $total_service ?>
                    </div>
                    <div class="content_02">
                        <div class="con_01">
                            <div>????????????</div>
                            <div>
                                <?= $working_start ?>
                                :00 ~
                                <?= $working_end ?>
                                :00</div>
                        </div>
                        <div class="con_02">
                            <div>????????????</div>
                            <div>
                                <?= $str_rest_public_holiday ?>
                            </div>
                        </div>
                        <div class="con_03">
                            <div>????????????</div>
                            <div>
                                <?= $shop_address ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    <?php
    }
    if ($index == 0 || $is_able_goout_shop == 0) {
    ?>
        <center>
            <br>
            <font style="font-size:14px;">??? ????????? ?????????<br>
            </font>
            <font style="font-size:14px;">??????/???????????????????????? ????????????.<br>
            </font>
        </center>
    <?php
    }
} else {

    $select_list = "select customer_id from tb_shop where open_flag = true and enable_flag = true order by rand();";
    $select_list_result = mysql_query($select_list);
    ?>
    <table width="100%">
        <tbody id="main_table_addition">
            <?php
            $artist_array = array();
            $artist_array_value = "";
            for ($rai = 0; $rows = mysql_fetch_object($select_list_result); $rai = $rai + 1) {
                $check_sql = "select enable_flag from tb_customer where id = '" . $rows->customer_id . "';";
                $check_result = mysql_query($check_sql);
                if ($check_rows = mysql_fetch_object($check_result)) {
                    if (!$check_rows->enable_flag) {
                        continue;
                    }
                }

                if ($rai < 10) {
            ?>
                    <tr>
                        <td><?php
                            $artist_id = $rows->customer_id;
                            include "main_artist_shop.php";
                            ?></td>
                    </tr>
            <?php
                } else {
                    if ($rai == 10) {
                        $artist_array_value = $rows->customer_id;
                    } else {
                        $artist_array_value = $artist_array_value . "|" . $rows->customer_id;
                    }
                    array_push($artist_array, $rows->customer_id);
                }
            }
            ?>
        </tbody>
    </table>
    <script>
        var increase_section = document.getElementById('main_table_addition');
        var artist_array_value = '<?= $artist_array_value ?>';
        var artist_array = artist_array_value.split("|");
        var addtion_count = 0;

        function add_section() {
            for (var i = 0; i < 10; i++) {
                var artist_id = artist_array[i + (10 * addtion_count)];
                if (!artist_id && i == 0) {
                    // ??????????????? ?????? ????????? ?????? ??????????????????.
                    addtion_count = 0;
                    continue;
                }

                if (!artist_id) {
                    break;
                }
                $.ajax({
                    url: 'main_artist_shop_ajax.php',
                    data: {
                        artist_id: artist_id
                    },
                    type: 'POST',
                    success: function(data) {
                        var row = increase_section.insertRow(increase_section.rows.length);
                        var cell1 = row.insertCell(0);
                        cell1.innerHTML = data;
                    },
                    error: function(xhr, status, error) {}
                });
            }
            addtion_count++;
        }


    </script>
    <div onclick="javascript:add_section();" style=" width:90%; margin: 0px auto; padding:10px 0px; background-color: #f5bf2e;  text-align:center;font-size:15px;color:#ffffff;border-radius: 5px;">?????????</div>
    <?php
    if ($rai == 0) {
    ?>
        <center>
            <br>
            <br>
            <font style="font-size:15ps;font-weight:bold;">??? ????????? ?????????<br>
                ????????????????????? ????????????.</font> <br>
            <br>
            <br>
            <div style="font-size:14px;width:100%;position:absolute;display:block;text-align:center;color:#fff;font-weight:bold;">
                <div style="height:70px;"></div>
                <br>
                <font style="font-size:16px;">???????????? ????????? ?????????<br>
                    Beta????????? ??? ?????????.</font><br>
                <br>
                ????????? ??????????????????<br>
                ????????? ??? ????????????.<br>
                ???????????????.
            </div>
            <img src="<?= $image_directory ?>/no_response.jpg" width="100%">
        </center>
<?php
    }
}
?>
<br>
<div class="info_wrap">
    <div class="info_icon"><img src="../images/icon_call.png"></div>
    <div class="info_1">????????????</div>
    <div class="info_2"><?=$company_help_number?></div>
    <div class="info_3"><?=$company_help_email?></div>
</div>
<br>
<font style="font-size:13px;color:#333333;" class="bottom_notice">
    <center>
        <a href="terms_of_service.php">????????????</a> | <a href="privacy_policy.php">????????????????????????</a> | <a href="proprietorship.php">?????????????????????</a>
    </center>
</font>
<div class="f_wrap" style="width: 90%; margin: 0px auto; padding-top: 20px;padding-bottom: 60px;">
    <font style="font-size:11px;color:#333333;line-height:1em;" class="bottom_notice">
        <div class="f_subwrap">
            <ul>
                <li>????????????(PetEasy Co.,Ltd) | ?????????:?????????</li>
                <li>????????????????????? 157-86-01070 </li>
                <li>??????????????? ??? 2019-????????????-0540???</li>
                <li>?????? ????????? ????????? 174??? 27 ????????????????????? 3??? 301???</li>
                <li>????????????????????? ????????? privacy@peteasy.kr</li>
                <li>?? PetEasy Co. Ltd. All Rights Reserved.</li>
            </ul>
            <ul>
                <li>????????? ??????????????????????????? ??????????????? ???????????? ????????????.</li>
                <li>????????? ????????? ?????????????????? ??? ????????? ?????? ???????????? ????????????. </li>
                <li>?????? ????????? ???????????? ????????? ????????? ?????? ??????????????? ????????? ?????? </li>
                <li>?????????.</li>
            </ul>
        </div>
    </font>
</div>
<div class="footer_btn">
    <?php
    if ($is_artist == 1) {
    ?>
        <div class="quick-btn03"> <a href="<?= $shop_directory ?>/index.php?from=main" class="phone_button">
                <div><img src="../images/footer_btn_3.png"></div>
            </a> </div>
        <div class="quick-btn01"> <a href="<?= $shop_directory ?>/manage_sell_info.php?ch=month&from=main" class="phone_button">
                <div><img src="../images/footer_btn_2.png"></div>
            </a> </div>
        <div class="quick-btn02"> <a href="<?= $shop_directory ?>/manage_customer_list.php?from=main">
                <div><img src="../images/footer_btn_list.png"></div>
            </a> </div>
        <div class="quick-btn04"> <a href="<?= $shop_directory ?>/manage_counseling_request.php?from=main">
                <div class="count_wrap">
                    <div class="count"><?=$wait_count?></div><img src="../images/footer_btn_4_2.png">
                </div>
            </a> </div>
    <?php
    }
    ?>
</div>
<?php include "../include/bottom.php"; ?>