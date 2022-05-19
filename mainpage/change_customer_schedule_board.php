<?php
include "../include/top.php";
include "../include/check_login.php";
// include "../include/check_header_log.php";
?>
<style>
@font-face {font-family: 'NL2GB';font-weight: normal;src: url("../fonts/BMJUA.otf");}
@font-face {font-family: 'NanumGothic';src: url("../fonts/NanumGothic.ttf");}
@font-face {font-family: 'NL2GB';src: url("../fonts/NEXON_Lv2_Gothic_Bold.woff");}
    * {
        font-family: 'NL2GB';
        font-weight: normal;
    }

    a {
        text-decoration: none;
    }

    a:link {
        color: black;
    }

    a:visited {
        color: black;
    }

    a:hover {
        color: black;
    }

    a:active {
        color: black;
    }

    select {
        height: 20px;
        padding-left: 7px;
        font-size: 14px;
        color: #000000;
        border: 1px solid #999999;
        border-radius: 3px;
    }
	html, button, select {
   font-family: 'NanumGothic';
    font-weight: bold;
}

    .date_submit {
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #c123de), color-stop(1, #a20dbd));
        background: -moz-linear-gradient(center top, #c123de 5%, #a20dbd 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#c123de', endColorstr='#a20dbd');
        background-color: #c123de;
        -webkit-border-top-left-radius: 6px;
        -moz-border-radius-topleft: 6px;
        border-top-left-radius: 6px;
        -webkit-border-top-right-radius: 6px;
        -moz-border-radius-topright: 6px;
        border-top-right-radius: 6px;
        -webkit-border-bottom-right-radius: 6px;
        -moz-border-radius-bottomright: 6px;
        border-bottom-right-radius: 6px;
        -webkit-border-bottom-left-radius: 6px;
        -moz-border-radius-bottomleft: 6px;
        border-bottom-left-radius: 6px;
        text-indent: 0;
        border: 1px solid #a511c0;
        display: inline-block;
        color: #ffffff;
        font-family: Arial;
        font-size: 14px;
        font-weight: bold;
        font-style: normal;
        height: 25px;
        line-height: 20px;
        width: 100%;
        text-decoration: none;
        text-align: center;
    }

    .date_submit:active {
        position: relative;
        top: 1px;
    }

    .btn_time_choice_ok {
        position: fixed;
        right: 15px;
        bottom: 15px;
        width: 60px;
        height: 60px;
        background-color: #f5a82e;
        border-radius: 60px;
        line-height: 60px;
        vertical-align: middle;
        font-size: 17px;
        color: #fff !important;
        /* padding: 1em; */
        box-sizing: border-box;
        z-index: 9999;
        font-weight: bold;
    }

    .line_1px {
        color: #999999;
        width: 100%;
        height: 1px;
        border: 0;
        border: 0px solid #999999;
        background-color: #666666;
    }

    .margin_top_2px {
        margin-top: 2px;
    }

    .margin_bottom_2px {
        margin-bottom: 2px;
    }

    .time_table_caption {
     
        margin: 8px auto 10px;
        padding-top: 0px;
        padding-bottom: 4px;
		width:90%;
    }

.top_menu {position: relative;}
.top_back {position: absolute;top: 15px;left: 10px;}
.top_title {width: 100%;text-align: center;font-size: 25px;font-weight: normal;padding: 15px 0px 15px 0px;border-bottom: 0.5px solid #e1e1e1;}
.top_title p {margin: 0px;font-family: 'NL2GB';font-weight: normal;line-height:normal;}

    #notice {
        text-align: center;
        font-size: 15px;
    }
</style>

<div class="top_menu">
    <div class="top_back"><a href="manage_my_reservation.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div class="top_title">
        <p>예약 변경</p>
    </div>
</div>

<!-- <script>
    function scrollToBottom(id) {
        var div = document.getElementById(id);
        div.scrollTop = div.scrollHeight - div.clientHeight;
    }
</script> -->
<?php
// echo '<pre>';
// var_dump($_SESSION);
// echo '</pre>';

$artist_id = ($_GET['artist_id'] && $_GET['artist_id'] != "")? $_GET['artist_id'] : "";
$user_id = $_SESSION['gobeauty_user_id'];

// 예약된 날짜가 없으면
$ch = $ch ? $ch : 'time';
$yy = $_REQUEST['yy'] ? $_REQUEST['yy'] : date('Y');
$mm = $_REQUEST['mm'] ? $_REQUEST['mm'] : date('m');
$dd = $_REQUEST['dd'] ? $_REQUEST['dd'] : date('d');

$r_payment_log_seq = ($_GET["payment_log_seq"] && $_GET["payment_log_seq"] != "")? $_GET["payment_log_seq"] : "";
if($artist_id == "" || $r_payment_log_seq == ""){
?>
    <script>
        $.MessageBox({
            buttonDone: "확인",
            message: "잘못된 접근입니다. 메인페이지로 이동합니다."
        }).done(function() {
            location.href = "<?= $mainpage_directory ?>/index.php";
        });
    </script>
<?php
		return false;
}

// echo $ch;
$week_name_arr = array('일', '월', '화', '수', '목', '금', '토');

function sel_yy($yy, $func)
{
    if ($yy == '') $yy = date('Y');
    if ($func == '') {
        $str = "<select name='yy'>\n<option value=''></option>\n";
    } else {
        $str = "<select name='yy' onChange='$func'>\n<option value=''></option>\n";
    }
    $gijun = date('Y');
    for ($i = $gijun; $i < $gijun + 2; $i++) {
        if ($yy == $i) $str .= "<option value='$i' selected>$i</option>";
        else $str .= "<option value='$i'>$i</option>";
    }
    $str .= "</select>";

    return $str;
}

function sel_mm($mm, $func)
{
    if ($func == '') {
        $str = "<select name='mm'>\n";
    } else {
        $str = "<select name='mm' onChange='$func'>\n";
    }

    for ($i = 1; $i < 13; $i++) {
        if ($mm == $i) $str .= "<option value='$i' selected>$i</option>";
        else $str .= "<option value='$i'>$i</option>";
    }

    $str .= "</select>";

    return $str;
}

// function get_schedule($yy, $mm, $dd)
// {
//     $str = "";

//     $mm = str_pad($mm, 2, "0", STR_PAD_LEFT);
//     $dd = str_pad($dd, 2, "0", STR_PAD_LEFT);
//     $dtstr = $yy . "-" . $mm . "-" . $dd;
//     $sql = "SELECT * FROM schedule WHERE frdt <= '$dtstr' AND todt >= '$dtstr' ORDER BY frdt, todt";

//     $ret = dbquery($sql) or die(mysql_error());
//     while ($row = dbfetch($ret)) {
//         $str .= "<font style='font-size:8pt;'>- $row[name]</font><br>";
//     }
//     return $str;
// }

// 1. 총일수 구하기
$last_day = date("t", strtotime($yy . "-" . $mm . "-01"));

// 2. 시작요일 구하기
$start_week = date("w", strtotime($yy . "-" . $mm . "-01"));

// 3. 총 몇 주인지 구하기
$total_week = ceil(($last_day + $start_week) / 7);

// 4. 마지막 요일 구하기
$last_week = date('w', strtotime($yy . "-" . $mm . "-" . $last_day));

// 법정 공휴일 쉬는 여부 1이 쉰다.
$is_rest_public_holiday = 1;
$working_sql = "select * from tb_working_schedule where customer_id = '" . $artist_id . "';";
$working_result = mysql_query($working_sql);
if ($working_datas = mysql_fetch_object($working_result)) {
    $is_rest_public_holiday = $working_datas->rest_public_holiday;
}

// 2019.01.07 developlay - 요일별 휴일여부 추출 [START]
$holiweek_arr = array();
$sql_regular = "select * from tb_regular_holiday where customer_id = '" . $artist_id . "';";
$sql_regular_result = mysql_query($sql_regular);
if ($sql_regular_row = mysql_fetch_object($sql_regular_result)) {
    $holiweek_arr[0] = $sql_regular_row->is_sunday;
    $holiweek_arr[1] = $sql_regular_row->is_monday;
    $holiweek_arr[2] = $sql_regular_row->is_tuesday;
    $holiweek_arr[3] = $sql_regular_row->is_wednesday;
    $holiweek_arr[4] = $sql_regular_row->is_thursday;
    $holiweek_arr[5] = $sql_regular_row->is_friday;
    $holiweek_arr[6] = $sql_regular_row->is_saturday;
}
// 2019.01.07 developlay - 요일별 휴일여부 추출 [END]

// 개인 정기 휴일 설정 1이 쉰다.
$is_monday = 0;
$is_tuesday = 0;
$is_wednesday = 0;
$is_thursday = 0;
$is_friday = 0;
$is_saturday = 0;
$is_sunday = 0;
$get_regular_sql = "select * from tb_regular_holiday where customer_id = '" . $artist_id . "';";
$get_regular_result = mysql_query($get_regular_sql);
if ($get_regular_datas = mysql_fetch_object($get_regular_result)) {
    $is_monday = $get_regular_datas->is_monday;
    $is_tuesday = $get_regular_datas->is_tuesday;
    $is_wednesday = $get_regular_datas->is_wednesday;
    $is_thursday = $get_regular_datas->is_thursday;
    $is_friday = $get_regular_datas->is_friday;
    $is_saturday = $get_regular_datas->is_saturday;
    $is_sunday = $get_regular_datas->is_sunday;
}

$holiday_array = array();
$select_public_holiday = "select * from tb_public_holiday where year = " . $yy . " and month = " . $mm . ";";
$select_result = mysql_query($select_public_holiday);
while ($select_datas = mysql_fetch_object($select_result)) {
    $a_day = $select_datas->day;
    array_push($holiday_array, $a_day);
}

$artist_query = "SELECT GROUP_CONCAT(DISTINCT name ORDER BY name ASC) as worker FROM tb_artist_list WHERE artist_id = '{$artist_id}' GROUP BY artist_id";
$artist_result = mysql_query($artist_query);
$artist_data = mysql_fetch_object($artist_result);

$private_all_holiday_array = array();
$private_notall_holiday_array = array();
$ph_sql = "SELECT *, GROUP_CONCAT(A.worker ORDER BY A.worker ASC) AS worker 
		FROM(
			SELECT 
				*,
				DATE_FORMAT(CONCAT(start_year,'-',start_month,'-',start_day,' ',start_hour,':',IFNULL('00',start_minute),':00'),'%Y-%m-%d %H:%i:%s') as start_date, 
        		DATE_FORMAT(CONCAT(end_year,'-',end_month,'-',end_day,' ',IF(end_hour = 24 AND (end_minute IS NULL OR end_minute = 0), '23', end_hour),':',IF(end_hour = 24 AND end_minute IS NULL, '59', IFNULL('00',end_minute)),':00'),'%Y-%m-%d %H:%i:%s') as end_date
			FROM tb_private_holiday 
			WHERE customer_id = '{$artist_id}' AND ('{$yy}' BETWEEN start_year AND end_year) AND ('{$mm}' BETWEEN start_month AND end_month)
		)A
		GROUP BY start_date, end_date, customer_id";
$ph_result = mysql_query($ph_sql);
while ($ph_datas = mysql_fetch_object($ph_result)) {
    $type = $ph_datas->type;
    $sy = $ph_datas->start_year;
    $sm = $ph_datas->start_month;
    $sd = $ph_datas->start_day;
    $sh = $ph_datas->start_hour;
    $ey = $ph_datas->end_year;
    $em = $ph_datas->end_month;
    $ed = $ph_datas->end_day;
    $eh = $ph_datas->end_hour;
    if ($type == "all" && $artist_data->worker == $ph_datas->worker) {
        if ($sm < $mm && $em > $mm) {
            $sd = 1;
            $ed = (int) date('t', strtotime("$yy-$mm-01"));
            for ($index = $sd; $index <= $ed; $index++) {
                array_push($private_all_holiday_array, $index);
            }
        } else if ($sm == $mm && $em != $mm) {
            $ed = (int) date('t', strtotime("$yy-$mm-01"));
            for ($index = $sd; $index <= $ed; $index++) {
                array_push($private_all_holiday_array, $index);
            }
        } else if ($em == $mm && $sm != $mm) {
            $sd = 1;
            for ($index = $sd; $index <= $ed; $index++) {
                array_push($private_all_holiday_array, $index);
            }
        } else {
            for ($index = $sd; $index <= $ed; $index++) {
                array_push($private_all_holiday_array, $index);
            }
        }
    } else if ($type == "notall" || $type == "not") {
        array_push($private_notall_holiday_array, $sd);
    }
}
/*$ph_sql = "select * from tb_private_holiday where customer_id = '".$artist_id."' and end_year = ".$yy." and end_month = ".$mm.";";
$ph_result = mysql_query($ph_sql);
while ($ph_datas = mysql_fetch_object($ph_result)) {
        $type = $ph_datas->type;
        $sy = $ph_datas->start_year;
        $sm = $ph_datas->start_month;
        $sd = $ph_datas->start_day;
        $sh = $ph_datas->start_hour;
        $ey = $ph_datas->end_year;
        $em = $ph_datas->end_month;
        $ed = $ph_datas->end_day;
        $eh = $ph_datas->end_hour;

        if ($type == "all") {
                if ($sm != $em) {
                        $sd = 0;
                }
                for ($index = $sd ; $index <= $ed ; $index++) {
                        array_push($private_all_holiday_array, $index);
                }
        }
        if ($type == "notall") {
                array_push($private_notall_holiday_array, $sd);
        }
}*/

?>
<?php



?>

<table width="100%" style="display:none;">
    <tr>
        <td style="font-size:15px;font-weight:bold;text-align:center">
            <?php if ($_SESSION['gobeauty_cart_year'] && $_SESSION['gobeauty_cart_month'] && $_SESSION['gobeauty_cart_day'] && $_SESSION['gobeauty_cart_day'] !== 'undefined') { ?>
                <hr>
                <a href="?artist_id=<?= $artist_id ?>" onclick="select_date2('<?= $artist_id ?>',<?= $yy ?>, <?= $mm ?>, <?= $day - 1 ?>)" style="float:left;">
                    <</a> <span style="font-size:20px;">
                        <!-- <?= $yy ?> 년 [patch] 2019.01.03 | developlay - 년도 주석처리 -->
                        <?= $mm ?> 월
                        <?= $dd ?> 일
                        <?php
                        $cart_date = new DateTime($yy . '-' . $mm . '-' . $dd);
                        ?>
                        (<?= $week_name_arr[$cart_date->format('w')] ?>요일)
                        </span>
                        <a href="?artist_id=<?= $artist_id ?>" onclick="select_date2('<?= $artist_id ?>',<?= $yy ?>, <?= $mm ?>, <?= $day + 1 ?>)" style="float:right;">></a>
                        <hr>
                    <? } else { ?>
                        * 날짜를 선택하세요. (오늘 기준 3개월까지 예약가능)
                    <? } ?>
        </td>
    </tr>
</table>
<form name="form" method="get" style="width:90%; margin:10px auto;">
    <input type="hidden" name="artist_id" value="<?= $artist_id ?>">
    <table id="calander" width='100%' cellpadding='0' cellspacing='1' bgcolor="#999999" <?php if ($ch == 'time') { ?> style="display:none;" <?php } ?>>
        <tr>
            <td colspan="7" bgcolor="#FFFFFF" height="40">
                <table width="100%">
                    <tr>
                        <td align="left">
                            <?php
                            $go_year = $yy;
                            $go_month = $mm;
                            if ($go_month == 1) {
                                $go_year = $go_year - 1;
                                $go_month = 12;
                            } else {
                                $go_month = $go_month - 1;
                            }
                            ?>
                            <a href="?artist_id=<?=$artist_id ?>&ch=<?= $ch ?>&yy=<?= $go_year ?>&mm=<?= $go_month ?>&payment_log_seq=<?=$r_payment_log_seq ?>&timediff=<?=$_GET['timediff'] ?>" onclick="move_month();"><img src="<?= $image_directory ?>/arrow2left.png" width="30px"></a>
                        </td>
                        <td align="center">
                            <?= sel_yy($yy, 'submit();') ?>년 <?= sel_mm($mm, 'submit();') ?>월
                            <!--input type="submit" value="보기"-->
                        </td>
                        <td align="right">
                            <?php
                            $go2_year = $yy;
                            $go2_month = $mm;
                            if ($go2_month == 12) {
                                $go2_year = $go2_year + 1;
                                $go2_month = 1;
                            } else {
                                $go2_month = $go2_month + 1;
                            }
                            ?>
                            <a href="?artist_id=<?=$artist_id ?>&ch=<?= $ch ?>&yy=<?= $go2_year ?>&mm=<?= $go2_month ?>&payment_log_seq=<?=$r_payment_log_seq ?>&timediff=<?=$_GET['timediff'] ?>" onclick="move_month();"><img src="<?= $image_directory ?>/arrow2right.png" width="30px"></a></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height="30" align="center" bgcolor="#DDDDDD"><b>일</b></td>
            <td align="center" bgcolor="#DDDDDD"><b>월</b></td>
            <td align="center" bgcolor="#DDDDDD"><b>화</b></td>
            <td align="center" bgcolor="#DDDDDD"><b>수</b></td>
            <td align="center" bgcolor="#DDDDDD"><b>목</b></td>
            <td align="center" bgcolor="#DDDDDD"><b>금</b></td>
            <td align="center" bgcolor="#DDDDDD"><b>토</b></td>
        </tr>
        <?

        $today_yy = date('Y');
        $today_mm = date('m');

        // 5. 화면에 표시할 화면의 초기값을 1로 설정
        $day = 1;

        // 6. 총 주 수에 맞춰서 세로줄 만들기
        for ($i = 1; $i <= $total_week; $i++) { ?>
            <tr>
                <?
                // 7. 총 가로칸 만들기
                for ($j = 0; $j < 7; $j++) {
                ?>
                    <td width="130" height="50" align="left" valign="top" style="padding:1px;" <?php
                                                                                                $is_rest = false;
                                                                                                $rest_word = "";

                                                                                                $current_time = date("" . $yy . "-" . $mm . "-" . $day . " 00:00:00");
                                                                                                $next_3month_time = date("Y-m-d 00:00:00", strtotime("+3 months"));
                                                                                                $next_3month_int = strtotime($next_3month_time);

                                                                                                $cal_time = date('Y-m-d 00:00:00');
                                                                                                $current_int = strtotime($current_time);
                                                                                                $cal_int = strtotime($cal_time);

                                                                                                $filled_mm = sprintf("%02d", $mm);
																								$filled_dd = sprintf("%02d", $day);
                                                                                                $diff_Ymd = $yy.$filled_mm.$filled_dd;
                                                                                                $today_Ymd = date('Ymd');

                                                                                                if ($current_int < $cal_int || $current_int > $next_3month_int) {
                                                                                                    echo " bgcolor='#bbbbbb' ";
                                                                                                    $rest_word = "";
                                                                                                    $is_rest = true;
                                                                                                } else if (($j == 0 && $is_sunday) || ($j == 0 && $is_rest_public_holiday) || ($is_rest_public_holiday && in_array($day, $holiday_array))) {
                                                                                                    echo " bgcolor='#bbbbbb' ";
                                                                                                    $rest_word = "정휴";
                                                                                                    $is_rest = true;
                                                                                                } else if ($j == 1 && $is_monday) {
                                                                                                    echo " bgcolor='#bbbbbb' ";
                                                                                                    $rest_word = "정휴";
                                                                                                    $is_rest = true;
                                                                                                } else if ($j == 2 && $is_tuesday) {
                                                                                                    echo " bgcolor='#bbbbbb' ";
                                                                                                    $rest_word = "정휴";
                                                                                                    $is_rest = true;
                                                                                                } else if ($j == 3 && $is_wednesday) {
                                                                                                    echo " bgcolor='#bbbbbb' ";
                                                                                                    $rest_word = "정휴";
                                                                                                    $is_rest = true;
                                                                                                } else if ($j == 4 && $is_thursday) {
                                                                                                    echo " bgcolor='#bbbbbb' ";
                                                                                                    $rest_word = "정휴";
                                                                                                    $is_rest = true;
                                                                                                } else if ($j == 5 && $is_friday) {
                                                                                                    echo " bgcolor='#bbbbbb' ";
                                                                                                    $rest_word = "정휴";
                                                                                                    $is_rest = true;
                                                                                                } else if ($j == 6 && $is_saturday) {
                                                                                                    echo " bgcolor='#bbbbbb' ";
                                                                                                    $rest_word = "정휴";
                                                                                                    $is_rest = true;
                                                                                                } else if ($i == $total_week && $j > $last_week) {
                                                                                                    echo " bgcolor='#fff' ";
                                                                                                } else if (in_array($day, $private_all_holiday_array)) {
                                                                                                    echo " bgcolor='#bbbbbb' ";
                                                                                                    $rest_word = "임휴";
                                                                                                    $is_rest = true;
                                                                                                } else if (in_array($day, $private_notall_holiday_array)) {
                                                                                                    echo " bgcolor='#fff' "; /*#eceaea*/
                                                                                                    $rest_word = "";/*반휴*/
                                                                                                } else if ($diff_Ymd == $today_Ymd) {
                                                                                                    echo " bgcolor='#bbbbbb' ";
                                                                                                    $is_rest = true;
                                                                                                    $rest_word = "";
                                                                                                } else {
                                                                                                    echo " bgcolor='#FFFFFF' ";
                                                                                                }

                                                                                                /*                $current_time = date("".$yy."-".$mm."-".$day." 00:00:00");
                $cal_time = date('Y-m-d 00:00:00');
                $current_int = strtotime($current_time);
                $cal_int = strtotime($cal_time);
                if ($current_int > $cal_int) {
//                      echo "미래";
                }
                else if ($current_int < $cal_int) {
//                      echo "과거";
			echo " bgcolor='#bbbbbb' "; $rest_word = ""; $is_rest = true;
                }
                else {
//                      echo "현재";
                }
*/

                                                                                                ?>>
                        <?
                        // 8. 첫번째 주이고 시작요일보다 $j가 작거나 마지막주이고 $j가 마지막 요일보다 크면 표시하지 않아야하므로
                        //    그 반대의 경우 -  ! 으로 표현 - 에만 날자를 표시한다.
                        if (!(($i == 1 && $j < $start_week) || ($i == $total_week && $j > $last_week))) {
                        ?>
                            <?
                            if (!$is_rest) {
                            ?>
                                <div onclick="select_date('<?= $artist_id ?>',<?= $yy ?>, <?= $mm ?>, <?= $day ?>);" style="height:100%;width:100%;">
                                <?php
                            }

                            if ($j == 0 || in_array($day, $holiday_array)) {
                                // $j가 0이면 일요일이므로 빨간색
                                echo "<font color='#FF0000'><b>";
                            } else if ($j == 6) {
                                // 10. $j가 0이면 토요일이므로 파란색
                                echo "<font color='#0000FF'><b>";
                            } else {
                                // 11. 그외는 평일이므로 검정색
                                echo "<font color='#000000'><b>";
                            }

                            // check한 날자면 동그라미
                            if (
                                $_SESSION['gobeauty_cart_year'] && $_SESSION['gobeauty_cart_month'] && $_SESSION['gobeauty_cart_day'] &&
                                $_SESSION['gobeauty_cart_year'] == $yy && $_SESSION['gobeauty_cart_month'] == $mm && $_SESSION['gobeauty_cart_day'] == $day
                            ) {
                                //echo "<img src='".$image_directory."/check1.png' width='30px' style='position:absolute;z-index:5'>";
                                //			echo "<img src='".$image_directory."/check1.png' width='30px'>";
                            }

                            // 12. 오늘 날자면 굵은 글씨
                            if ($today_yy == $yy && $today_mm == $mm && $day == date("j")) {
                                //			echo "<u>";
                            }

                            // 13. 날자 출력
                            echo $day;

                            if ($today_yy == $yy && $today_mm == $mm && $day == date("j")) {
                                //			echo "</u>";
                                // echo "<font style='font-size:12px;'> 오늘</font>";
                            }

                            if (
                                $_SESSION['gobeauty_cart_year'] && $_SESSION['gobeauty_cart_month'] && $_SESSION['gobeauty_cart_day'] &&
                                $_SESSION['gobeauty_cart_year'] == $yy && $_SESSION['gobeauty_cart_month'] == $mm && $_SESSION['gobeauty_cart_day'] == $day
                            ) {
                                // echo "<br><img src='".$image_directory."/check4.png' width='25px' style='position:absolute;z-index:5;'>"; // [change] 2019.01.06 : developlay - 아이콘 제거
                            }

                            echo "</b></font>";

                            //스케줄 출력
                            //$schstr = get_schedule($yy,$mm,$day);
                            echo $schstr;
                            echo "<br><font style='font-size:12px;'>" . $rest_word . "</font>";

                            if (!$is_rest) {
                                echo "</div>";
                            }

                            // 14. 날자 증가
                            $day++;
                                ?>
                            <? } ?>
                    </td>
                <? } ?>
            </tr>
        <? } ?>
    </table>
</form>

<!-- <div style="height:10px;"></div> -->
<?php
// [peach] 2019.01.16 : developlay - 타임테이블의 날짜 이동 버튼의 날짜정보 추출(이전일)
$go_Date = new DateTime(date('Y-m-d', strtotime($yy . '-' . $mm . '-' . $dd . ' -1 day')));
// $go_Date = new DateInterval( $yy .'-'. $mm .'-'. $dd );
$go_year = $go_Date->format('Y');
$go_month = $go_Date->format('m');
$go_day = $go_Date->format('d');

// [peach] 2019.01.16 : developlay - 타임테이블의 날짜 이동 버튼의 날짜정보 추출(다음일)
$go2_Date = new DateTime(date('Y-m-d', strtotime($yy . '-' . $mm . '-' . $dd . ' +1 day')));
// $go_Date = new DateInterval( $yy .'-'. $mm .'-'. $dd );
$go2_year = $go2_Date->format('Y');
$go2_month = $go2_Date->format('m');
$go2_day = $go2_Date->format('d');

if ($ch == 'time') {
?>

    <!-- <hr class="line_1px margin_bottom_2px" /> -->
    <div class="time_table_caption">
        <table width="100%" style="font-size:18px;font-weight:bold;">
            <tr>
                <td align="left"><a href="?artist_id=<?=$artist_id ?>&ch=<?= $ch ?>&yy=<?= $go_year ?>&mm=<?= $go_month ?>&dd=<?= $go_day ?>&payment_log_seq=<?=$r_payment_log_seq ?>&timediff=<?=$_GET['timediff'] ?>" style="font-size:20px;">&lt;</a></td>
                <td align="center">
                    <!-- <?= (int) $yy ?>년  -->
                    <?= (int) $mm ?>월
                    <?= (int) $dd ?>일
                    (<?= $week_name_arr[date('w', strtotime($yy . '-' . $mm . '-' . $dd))] ?>)
                </td>
                <td align="right"><a href="?artist_id=<?=$artist_id ?>&ch=<?= $ch ?>&yy=<?= $go2_year ?>&mm=<?= $go2_month ?>&dd=<?= $go2_day ?>&payment_log_seq=<?=$r_payment_log_seq ?>&timediff=<?=$_GET['timediff'] ?>" style="font-size:20px;">&gt;</a></td>
            </tr>
        </table>
    </div>
    <!-- <hr class="line_1px margin_top_2px" /> -->

    <?php

    // var_dump($_SESSION);

    // 결제완료건 추출
    $data = array();
    $data['payment'] = array();
    $sql['payment'] = "SELECT
    pay.* ,
    DATE_FORMAT( CONCAT( pay.year,'-',pay.month,'-',pay.day,' ',pay.hour,':',IFNULL(pay.minute,'00'),':00' ), '%Y-%m-%d %H:%i' ) res_date ,
    DATE_FORMAT( CONCAT( pay.year,'-',pay.month,'-',pay.day,' ',pay.hour,':',IFNULL(pay.minute,'00'),':00' ), '%Y-%m-%d %H:%i' ) res_time_start ,
    DATE_FORMAT( CONCAT( pay.year,'-',pay.month,'-',pay.day,' ',pay.to_hour,':',IFNULL(pay.to_minute,'00'),':00' ), '%Y-%m-%d %H:%i' ) res_time_end
FROM
    `tb_payment_log` pay
WHERE
    DATE_FORMAT( CONCAT( pay.year,'-',pay.month,'-',pay.day ), '%Y-%m-%d' ) = DATE_FORMAT( '{$yy}-{$mm}-{$dd}' , '%Y-%m-%d' ) AND
    pay.artist_id = '{$artist_id}' AND
    pay.is_cancel = 0
";
    $result = mysql_query($sql['payment']);
    while ($rows = mysql_fetch_assoc($result)) {
        // echo '<pre>';
        // var_dump($rows->res_time_start);
        // var_dump($rows->res_time_end);
        // echo '</pre>';
        $worker = $rows['worker'];
        if ($worker) {
            $datetime1 = new DateTime($rows['res_time_start']);
            $datetime2 = new DateTime($rows['res_time_end']);
            $interval = $datetime2->diff($datetime1);
            // echo ( $interval->format('%h') * 60 ) / 30 ;
            // echo $rows['res_time_start'];
            $resTime = date('H:i', strtotime($rows['res_time_start']));
            // echo $resTime = date('H:i',strtotime($rows['res_time_start']));

            $time_start = date2timestamp(strtotime(date('Y-m-d H:i', strtotime($rows['res_time_start']))));
            $time_end = date2timestamp(strtotime(date('Y-m-d H:i', strtotime($rows['res_time_end']))));
            $time = ($time_end - $time_start) ? ($time_end - $time_start) : (60 * 30);

            $time_block_cnt = $time / (60 * 30); // 블럭갯수

            $rowspan_count = $time_block_cnt;
            $rowspan['payment'][$worker][$resTime] = $rowspan_count;
            $data['payment'][$worker][$resTime]['data'] = $rows;
            for ($t = 0; $t < $rowspan_count; $t++) {
                $thisTime = date('H:i', strtotime($rows['res_time_start'] . ' +' . ($t * 30) . ' minute'));
                $data['payment'][$worker][$resTime]['time'][] = $thisTime;
            }
        }

        // $data['payment'][] = $payment;

    }
    // $payment_time = array_keys($payment);
    // echo '<pre>';
    // var_dump($payment);
    // var_dump($payment_time);
    // var_dump($rowspan);
    // echo '</pre>';
    // 결제완료건 추출



    // 예약중인 시간을 막는다.
    $data['reservation'] = array();
    $sql['reservation'] = "SELECT res.* ,
                                DATE_FORMAT( CONCAT( res.year,'-',res.month,'-',res.day,' ',res.hour,':',IFNULL(res.minute,'00'),':00' ), '%Y-%m-%d %H:%i' ) res_time_start
                            FROM 
                                `tb_reservation` res 
                            WHERE 
                                1
                                and res.artist_id = '{$artist_id}'
                                and res.year = '{$yy}'
                                and res.month = '{$mm}'
                                and res.day = '{$dd}'
                                and res.update_time > DATE_ADD(now(), INTERVAL -10 MINUTE )";
    // $sql = "select * from tb_reservation where artist_id = '".$_SESSION['gobeauty_cart_artist_id']."' and year = ".$_SESSION['gobeauty_cart_year']." and month = ".$_SESSION['gobeauty_cart_month']." and day = ".$_SESSION['gobeauty_cart_day']." and update_time > DATE_ADD(now(), INTERVAL -10 MINUTE);";
    $result = mysql_query($sql['reservation']);
    $reservation = array();
    while ($rows = mysql_fetch_assoc($result)) {
        // var_dump($rows);
        $rows_year = $rows['year'];
        $rows_month = $rows['month'];
        $rows_day = $rows['day'];
        $rows_hour = $rows['hour'];
        $rows_minute = $rows['minute'];
        $rows_rowspan = $rows['rowspan'];
        $rows_my_order = $rows['my_order'];
        $rows_session_id = $rows['session_id'];
        $worker = $rows['worker'];

        if ($worker) {
            $date =  $rows_year . '-' . $rows_month . '-' . $rows_day . ' ' . $rows_hour . ":" . $rows_minute . ":00";
            // echo '<Br/>';
            $now = date_create($date);
            $time = date_format($now, "H:i");

            // $data['reservation'][$worker][$time]['time'][] = $reservation[$worker][$time]['time'][] = $time; // 각 시간대 저장
            $data['reservation'][$worker][$time]['data'] = $reservation[$worker][$time]['data'] = $rows; // 예약중인 건에 대한 정보

            // $time = date('H:i', strtotime($private_holiday_rows['pri_start_date']));
            $time_start = date2timestamp(strtotime(date('Y-m-d H:i', strtotime($rows['res_time_start']))));
            $time_end =  $time_start + (int) ($rows_rowspan * (60 * 30));
            $time_full = ($time_end - $time_start) ? ($time_end - $time_start) : (60 * 30);

            $time_block_cnt = $time_full / (60 * 30); // 블럭갯수

            $rowspan['reservation'][$worker][$time]['count'] = $time_block_cnt;
            $session_id[$worker][$time] = $rows_session_id;
            if ($rows_my_order) $order_my[$time][] = $time;

            for ($h = $time_start; $h < $time_end; $h += (60 * 30)) {
                // echo date('H:i',$h);
                $rowspan['reservation'][$worker][$time]['time'][] = date('H:i', $h);
                $reservation_artist[$firstTime]['time'];
                $data['reservation'][$worker][$time]['time'][] = $reservation[$worker][$time]['time'][] = date('H:i', $h); // 각 시간대 저장
            }

            // for( $t=0 ; $t<$rows_rowspan ; $t++ ) {
            //     $date_add = date_add($now,date_interval_create_from_date_string("30 minutes"));
            //     $data['reservation'][$worker][$time]['time'][] = $reservation[$worker][$time]['time'][] = date_format($date_add, "H:i"); // 각 시간대 저장
            //     // if ( $rows_my_order ) $order_my[$time][] = date_format($date_add, "H:i");
            // }
        }

        // $data['reservation'][] = $reservation;

    }
    // echo '<pre>';
    // var_dump($data['reservation']);
    // var_dump($rowspan['reservation']);
    // echo '</pre>';
    // $data['reservation'] = null;
    foreach ($data['payment'] as $key => $val) ksort($data['payment'][$key]);
    foreach ($data['reservation'] as $key => $val) ksort($data['reservation'][$key]);
    if (!empty($rowspan['payment'])) {
        foreach ($rowspan['payment'] as $key => $val) ksort($rowspan['payment'][$key]);
    }
    if (!empty($rowspan['reservation'])) {
        foreach ($rowspan['reservation'] as $key => $val) ksort($rowspan['reservation'][$key]);
    }


    // 일일 오픈시간과 종료시간을 가지고 온다.
    $sql['schedule'] = "select * from tb_working_schedule where customer_id='{$artist_id}'";
    $result = mysql_query($sql['schedule']);
    $data['schedule'] = $schedule = $rows = mysql_fetch_assoc($result);
    if (isset($order_my) ? $order_my : array()) {
        ksort($order_my);
    }
    // echo '<pre>';
    // var_dump($order_my);
    // echo '</pre>';

    // 미용사별 근무시간
    $week_idx = date('w', strtotime($yy . '-' . $mm . '-' . $dd));
    $sql['artist'] = " SELECT * FROM `tb_artist_list` WHERE artist_id='{$artist_id}' AND week='{$week_idx}' GROUP BY name ";
    $result = mysql_query($sql['artist']);
    while ($artist_rows = mysql_fetch_assoc($result)) {
        $data['artist'][] = $artist_rows;
    }

    foreach ($data['artist'] as $key => $artist) {
        // 임시휴일[START]
        $sql_private_holiday = " SELECT 
        pholiday.* ,
        DATE_FORMAT( CONCAT( pholiday.start_year,'-',pholiday.start_month,'-',pholiday.start_day,' ',pholiday.start_hour,':',IFNULL(pholiday.start_minute,'00'),':00' ), '%Y-%m-%d %H:%i' ) pri_start_date ,
        DATE_FORMAT( CONCAT( pholiday.end_year,'-',pholiday.end_month,'-',pholiday.end_day,' ',pholiday.end_hour,':',IFNULL(pholiday.end_minute,'00'),':00' ), '%Y-%m-%d %H:%i' ) pri_end_date 
    FROM 
        tb_private_holiday pholiday 
    WHERE 
        pholiday.customer_id='{$artist_id}'
        AND pholiday.worker='{$artist['name']}'
        AND DATE_FORMAT( '{$yy}-{$mm}-{$dd}' , '%Y-%m-%d' ) BETWEEN 
            DATE_FORMAT( CONCAT( pholiday.start_year,'-',pholiday.start_month,'-',pholiday.start_day ), '%Y-%m-%d' ) AND
            DATE_FORMAT( CONCAT( pholiday.end_year,'-',pholiday.end_month,'-',pholiday.end_day ), '%Y-%m-%d' )
    ";
        $result_private_holiday = mysql_query($sql_private_holiday);
        // echo mysql_num_rows($result_private_holiday);
        while ($private_holiday_rows = mysql_fetch_assoc($result_private_holiday)) {

            // var_dump($private_holiday_rows['pri_start_date']);
            //         // $date = date('Y-m-d', strtotime($rows['pri_start_date']));
            $type = $private_holiday_rows['type'];
            $time = null;
            $time_start = null;
            $time_end = null;

            if ($type == "all") {
                $time = date('H:i', strtotime($artist['time_start']));
                $time_start = date2timestamp(strtotime(date('Y-m-d H:i', strtotime($private_holiday_rows['start_year'] . "-" . $private_holiday_rows['start_month'] . "-" . $private_holiday_rows['start_day'] . " " . $artist['time_start']))));
                $time_end = date2timestamp(strtotime(date('Y-m-d H:i', strtotime($private_holiday_rows['start_year'] . "-" . $private_holiday_rows['start_month'] . "-" . $private_holiday_rows['start_day'] . " " . $artist['time_end']))));
            } else {
                $time = date('H:i', strtotime($private_holiday_rows['pri_start_date']));
                $time_start = date2timestamp(strtotime(date('Y-m-d H:i', strtotime($private_holiday_rows['pri_start_date']))));
                $time_end = date2timestamp(strtotime(date('Y-m-d H:i', strtotime($private_holiday_rows['pri_end_date']))));
            }

            $sec = ($time_end - $time_start) ? ($time_end - $time_start) : (60 * 30);
            $time_block_cnt = $sec / (60 * 30); // 블럭갯수

            for ($h = $time_start; $h < $time_end; $h += (60 * 30)) {
                $private[$private_holiday_rows['worker']][] = date('H:i', $h);
                $data['private_holiday'][$artist['name']]['time'][] = date('H:i', $h);
            }

            // $data['private_holiday'][$artist['name']]['time'] = $private[$artist['name']];
            $data['private_holiday'][$artist['name']]['count'][$time] = (int) $time_block_cnt;
            $data['private_holiday'][$artist['name']]['data'][$time] = $private_holiday_rows;
        }
    }

	// 2020.04.07 ulmo 시간 예약 활성화, 비활성화 관련 
	$time_off_sql = "select * from tb_time_off where customer_id = '$artist_id' ";
	$time_off_result = mysql_query($time_off_sql);
	$time_off_row = mysql_fetch_object($time_off_result);

	$data['time_off']['res_time_off'] = $time_off_row->res_time_off;

    // echo '<pre>';
    // var_dump($_SESSION);
    // var_dump($data['private_holiday']);
    // echo '</pre>';

    // 세션에 담긴 예약시간정보 및 미용사정보
    $cart_worker = $_SESSION['gobeauty_cart_worker'];
    $cart_date = ($_SESSION['gobeauty_cart_year'] && $_SESSION['gobeauty_cart_month'] && $_SESSION['gobeauty_cart_day']) ? "{$_SESSION['gobeauty_cart_year']}-{$_SESSION['gobeauty_cart_month']}-{$_SESSION['gobeauty_cart_day']}" : '';
    $cart_time_from = ($_SESSION['gobeauty_cart_hour'] && $_SESSION['gobeauty_cart_minute']) ? "{$_SESSION['gobeauty_cart_hour']}:{$_SESSION['gobeauty_cart_minute']}" : '';
    $cart_time_to = ($_SESSION['gobeauty_cart_to_hour'] && $_SESSION['gobeauty_cart_to_minute']) ? "{$_SESSION['gobeauty_cart_to_hour']}:{$_SESSION['gobeauty_cart_to_minute']}" : '';
    // 세션에 담긴 예약시간정보 및 미용사정보

	// 2020.04.08 ulmo 타임제 체크하여 화면 표시를 변경(1-자유시간제, 2-타임제)
	$shop_sql = "
		SELECT * 
		FROM tb_shop
		WHERE customer_id = '".$artist_id."'
	";
	$shop_result = mysql_query($shop_sql);
	$shop_row = mysql_fetch_object($shop_result);
	$data["shop"]["is_time_type"] = $shop_row->is_time_type;
	if($data["shop"]["is_time_type"] == "1"){

    ?>
    <style type="text/css">
        #time_table {
            font-size: 14px;
                width: 90%;
    margin: 0 auto;
        }

        #time_table>.comment {
            display: inline-block;
            width: 100%;
            font-weight: bold;
            padding: 0.2em 0.4em;
        }

        #time_table>.comment>span {
            display: block;
        }

        #time_table>.comment>span:nth-child(3) {
            font-size: 0.8em;
        }

        #time_table>.table {
            overflow: auto;
            border: 1px solid #aaaaaa;
            background-color: #ffffff;
            border-radius: 4px;
        }

        #time_table>.table>table {
            width: 100%;
            border-collapse: collapse;
        }

        #time_table>.table>table>tbody.head>tr {}

        #time_table>.table>table>tbody.head>tr.space {}

        #time_table>.table>table>tbody.head>tr.space>td {
            text-align: center;
            background-color: #eeeeee;
            height: 4px;
        }

        #time_table>.table>table>tbody.head>tr.artist {}

        #time_table>.table>table>tbody.head>tr.artist>td {
            text-align: center;
            background-color: #eeeeee;
            padding: 0.4em;
            text-align: center;
        }

        #time_table>.table>table>tbody.head>tr.artist>td>label.artist_nicname {
            text-align: center;
			font-family: 'NanumGothic';
			font-weight:bold;
        }

        #time_table>.table>table>tbody.time>tr.time>th {
            text-align: center;
            padding: 0px;
            border-top: 1px solid #dfdfdf;
            background-color: #eeeeee;
        }

        #time_table>.table>table>tbody.time>tr.time>th .time_line {}

        #time_table>.table>table>tbody.time>tr.time>th .time_line>.time_cell {
            height: 30px;
            line-height: 30px;
            border-bottom: 1px dotted #dfdfdf;
            box-sizing: border-box;
            font-size: 13px;
            font-weight: normal;
			font-family: 'NanumGothic';
			font-weight:bold;
        }

        #time_table>.table>table>tbody.time>tr.time>th .time_line>.time_cell.line_solid {
            border-bottom: 1px solid #dfdfdf !important;
        }

        #time_table>.table>table>tbody.time>tr.time>td {
            padding: 0px;
            border-top: 1px solid #dfdfdf;
            border-right: 1px dotted #eeeeee;
            background-color: #ffffff;
        }

        #time_table>.table>table>tbody.time>tr.time>td:last-child {
            border-right: 0px;
        }

        #time_table>.table>table>tbody.time>tr.time>td .artist_time_line {}

        #time_table>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell {
            cursor: pointer;
            position: relative;
            height: 30px;
            line-height: 30px;
            text-align: center;
            border-bottom: 1px dotted #dfdfdf;
            box-sizing: border-box;
            font-size: 0.9em;
            font-weight: normal;
            background-color: #ffffff;
        }

        /* #time_table>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell.avoid {
            background-color: rgba(0, 0, 0, 0.1);
        }

        #time_table>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell.after-res {
            background-color: rgba(0, 0, 0, 0.1);
        } */

        #time_table>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell.not {
            background-color: rgba(0, 0, 0, 0.1);
        }

        #time_table>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell.holiday {
            background-color: rgba(0, 0, 0, 0.1);
            cursor: default;
            color: rgba(0, 0, 0, 0.5);
            font-size: 0.8em;
            letter-spacing: -0.2em;
            border: 2px solid #fff;
            border-radius: 6px;
        }

        #time_table>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell.on {
            background-color: #fff390;
            border: 2px solid #fff;
            border-radius: 6px;
        }

        #time_table>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell.ing {
            background-color: rgba(0, 0, 0, 0.1);
            border: 2px solid #fff;
            border-radius: 6px;
        }

        #time_table>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell.res {
            background-color: rgba(0, 0, 0, 0.1);
            border: 2px solid #fff;
            border-radius: 6px;
        }

        #time_table>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell.line_solid {
            border-bottom: 1px solid #dfdfdf !important;
        }


        @media only screen and (min-width: 768px) {
            #time_table>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell.on:hover {
                background-color: #fff390;
            }

            #time_table>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell:not(.holiday):hover::before {
                display: block;
                left: 0px;
                width: 100%;
                content: attr(data-time);
                font-size: 12px;
                box-sizing: border-box;
                position: absolute;
                text-align: center;
            }

            #time_table>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell:not(.not):hover::before {
                display: block;
                left: 0px;
                width: 100%;
                content: attr(data-time);
                font-size: 12px;
                box-sizing: border-box;
                position: absolute;
                text-align: center;
            }

            .time_func a.btn:hover {
                background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #a20dbd), color-stop(1, #c123de));
                background: -moz-linear-gradient(center top, #a20dbd 5%, #c123de 100%);
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#a20dbd', endColorstr='#c123de');
                background-color: #a20dbd;
            }

            .date_submit:hover {
                background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #a20dbd), color-stop(1, #c123de));
                background: -moz-linear-gradient(center top, #a20dbd 5%, #c123de 100%);
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#a20dbd', endColorstr='#c123de');
                background-color: #a20dbd;
            }
        }
    </style>
    <div id="time_table" style="">
        <div class="comment">
            <a href="#calander" class="btn_calander"><img src="/pet/images/calendar-2027122_640.png" width="30" height="30" /></a> <!-- [peach] 2019.01.16 : developlay - 카렌더 이미지 변경 -->
            <span style="font-family: 'NanumGothic';font-weight: bold;">시간선택 후 선택 버튼을 눌러주세요</span>
            <span style="font-family: 'NanumGothic';font-weight: bold;">(모든 예약은 기본 2시간으로 설정됩니다)</span>
            <!-- [patch] 2019.01.03 | developlay - 달력아이콘 위치 이동 -->
        </div>
        <div class="table">
            <table>
                <colgroup>
                    <col style="width:90px;">
                    <?php foreach ((array) $data['artist'] as $key => $artist) { ?>
                        <col style="width:auto;"><?php } ?>
                </colgroup>
                <tbody class="head">
                    <tr class="artist">
                        <td></td>
                        <?php
                        if (!($week_idx == 0 && $is_rest_public_holiday)) {
                            foreach ((array) $data['artist'] as $key => $artist) { ?><td style=""><label class="artist_nicname"><?= $artist['nicname'] ?></label></td><?php }
                                                                                                                                                                }
                                                                                                                                                                        ?>
                    </tr>
                </tbody>
                <tbody class="time">
                    <tr class="time">
                        <th>
                            <div class="time_line">
                                <?php for ($t = $data['schedule']['working_start']; $t < $data['schedule']['working_end']; $t++) { ?>
                                    <?php for ($t30 = 0; $t30 < 60; $t30 += 30) {
                                        $css_line = $t30 == 30 ? "line_solid" : ''; ?><div class="time_cell <?= $css_line ?>" data-hour="<?= sprintf('%02d', $t) ?>" data-min="<?= sprintf('%02d', $t30) ?>" date-session-id="<?= $session_id[$firstTime] ?>"><?php
                                                                                                                                                                                                                                                                echo $thisTime = sprintf('%02d', $t) . ":" . sprintf('%02d', $t30); // 현재 시간
                                                                                                                                                                                                                                                                echo '~';
                                                                                                                                                                                                                                                                echo date_format(date_add(date_create($thisTime), date_interval_create_from_date_string("30 minutes")), "H:i");
                                                                                                                                                                                                                                                                // $reservation_time = array_keys($reservation); // 예약시간 리스트
                                                                                                                                                                                                                                                                ?></div><?php } ?>
                                <?php } ?>
                            </div>
                        </th>
                        <?php
                        if (!($week_idx == 0 && $is_rest_public_holiday)) {
                            foreach ((array) $data['artist'] as $artist_key => $artist) {
                                $payment_artist = $data['payment'][$artist['name']];
                                $reservation_artist = $data['reservation'][$artist['name']];
                                // echo '<pre>';
                                // var_dump($data['payment']);
                                // echo '</pre>';
                        ?>
                                <td>
                                    <div class="artist_time_line <?= $artist_key == 0 ? 'on' : '' ?>" data-user-id="<?= $user_id ?>" data-artist-id="<?= $artist['artist_id'] ?>" data-artist-name="<?= $artist['name'] ?>" data-year="<?= $yy ?>" data-month="<?= $mm ?>" data-day="<?= $dd ?>">
                                        <?php
                                        $i = 0;
                                        $check = false;

                                        for ($t = $data['schedule']['working_start']; $t < $data['schedule']['working_end']; $t++) {
                                            for ($t30 = 0; $t30 < 60; $t30 += 30) {
                                                $thisTime = sprintf('%02d', $t) . ":" . sprintf('%02d', $t30); // 현재 시간
												$thisTimeTo = date('H:i', date2timestamp(strtotime(date('Y-m-d H:i', strtotime($thisDate . ' ' . $thisTime)))));
												$thisTimeFrom = date('H:i', date2timestamp(strtotime(date('Y-m-d H:i', strtotime($thisDate . ' ' . $thisTime)))) + (60 * 30));
                                                $payment_time = $payment_artist[$thisTime]['time'];
                                                $reservation_time = $reservation_artist[$thisTime]['time'];
                                                $time_block_cnt = 1;
                                                // $reservation_time_sess = 

                                                $css_line = $t30 == 30 ? "line_solid" : '';

                                                // if ( !is_null($payment_time) ) var_dump($payment_time);
                                                // $reservation_time = array_keys($reservation_data); // 예약시간 리스트
                                                // if ( !is_null($reservation_time) ) var_dump($reservation_time);
                                                // if ( in_array($thisTime,$reservation_time) ) echo 'true';
                                                $payment_time = isset($payment_time) ? $payment_time : array();
                                                $reservation_time = isset($reservation_time) ? $reservation_time : array();

                                                if (in_array($thisTime, $payment_time)) {
                                                    $firstTime = $thisTime;

                                                    // $tmp_rowspan = $rowspan['payment'][$artist['name']][$thisTime];
                                                    $payment_time[$firstTime]['time'] = isset($payment_time[$firstTime]['time']) ? $payment_time[$firstTime]['time'] : array();
                                                    $status = in_array($thisTime, $payment_time[$firstTime]['time']) ? '' : ""; // 예약중
                                                    $comment = "";
                                                } else
                                                    // if ( in_array($thisTime,) ) {} else
                                                    if (in_array($thisTime, $reservation_time)) {
                                                        $firstTime = $thisTime;

                                                        // $tmp_rowspan = $rowspan['reservation'][$artist['name']][$thisTime];
                                                        // if ( in_array( $thisTime , $reservation_artist[$firstTime]['time'] ) ) {}
                                                        $status = in_array($thisTime, $reservation_time) ? "" : ""; // 예약됨


                                                        $date =  $rows_year . '-' . $rows_month . '-' . $rows_day . ' ' . $reservation_artist[$firstTime]['time'][count($reservation_artist[$firstTime]['time']) - 1] . ":00";
                                                        $now = date_create($date);
                                                        $date_add = date_add($now, date_interval_create_from_date_string("30 minutes"));
                                                        $lastTime = date_format($date_add, "H:i");

                                                        // echo (int) $lastResult = (time($last) - time($first)) / 3600;
                                                        // echo '미용가능시간이 2시간 이하인 경우 샵에서 조정될 수 있습니다.';
                                                        $comment = in_array($thisTime, $reservation_artist[$firstTime]['time']) ? '모든 예약은 기본 2시간으로 설정됩니다.' : "";
                                                        // $comment = in_array( $thisTime , $reservation[$firstTime] ) ? '( 예약시간 : '.$reservation[$firstTime][0]." 부터 ".$lastTime.' 까지 )' : "";

                                                        $date =  $rows_year . '-' . $rows_month . '-' . $rows_day . ' ' . $reservation_artist[$firstTime]['time'][count($reservation_artist[$firstTime]['time']) - 1] . ":00";
                                                        $now = date_create($date);
                                                        $date_add = date_add($now, date_interval_create_from_date_string("30 minutes"));
                                                        $lastTime = date_format($date_add, "H:i");

                                                        $comment = in_array($thisTime, $order_my[$firstTime]) ? '모든 예약은 기본 2시간으로 설정됩니다.' : $comment;
                                                    } else {
                                                        // $firstTime = $thisTime;
                                                        // $firstTime = '';
                                                        $status = '';
                                                    }

                                                // if ( $tmp_rowspan ) $tmp_rowspan--;
                                                // $thisTime = sprintf('%02d',$t).":".sprintf('%02d',$t30);

                                                // 시간을 숫자타입으로 변경
                                                $int_thisTime = (int) date('Hi', strtotime($thisTime));
                                                $int_time_start = (int) date('Hi', strtotime($artist['time_start']));
                                                $int_time_end = (int) date('Hi', strtotime($artist['time_end']));
                                                // if ( !is_null( $reservation_artist[$firstTime]['time'] ) ) var_dump($reservation_artist[$firstTime]['time']);
                                                // 상황별 클래스 정의


                                                //오늘 시간 지나면 예약 선택 불가능하게
                                                $nowTime = strtotime(date("Y-m-d H:i:s"));
                                                $reservationTime = strtotime("$yy-$mm-$dd " . $thisTime);
                                                
                                                $filled_mm = sprintf("%02d", $mm);
                                                $diff_Ymd = $yy.$filled_mm.$dd;
                                                $today_Ymd = date('Ymd');
                                                // error_log('----- $today_Ymd : '.$today_Ymd);
                                                // error_log('----- $diff_Ymd : '.$diff_Ymd);

                                                $data['private_holiday'][$artist['name']]['time'] = isset($data['private_holiday'][$artist['name']]['time']) ? $data['private_holiday'][$artist['name']]['time'] : array();
                                                $payment_artist[$firstTime]['time'] = isset($payment_artist[$firstTime]['time']) ? $payment_artist[$firstTime]['time'] : array();
                                                $reservation_artist[$firstTime]['time'] = isset($reservation_artist[$firstTime]['time']) ? $reservation_artist[$firstTime]['time'] : array();

                                                if ($holiweek_arr[(int) date('w', strtotime("{$yy}-{$mm}-{$dd}"))] == 1) {
                                                    $css_status = 'space';
                                                } else if ($reservationTime <= $nowTime) {
                                                    //오늘 중 시간 초과 시 예약 불가능
                                                    $css_status = 'not';
                                                } else if ($int_thisTime < $int_time_start || (($int_time_end != 0) && ($int_thisTime >= $int_time_end))) {
                                                    // 근무시간 외 시간
                                                    $css_status = 'not';
                                                } else if (($reservationTime - $nowTime) <= 3600) {
                                                    // 근무시간 외 시간
                                                    $css_status = 'not';
                                                } else if ($today_Ymd == $diff_Ymd) {
                                                    //----- 당일 예약 불가
                                                    $css_status = 'not';
                                                } else if (in_array($thisTime, $data['private_holiday'][$artist['name']]['time'])) {
                                                    // 근무시간 외 시간
                                                    $css_status = 'holiday';
                                                    $status = '';
                                                } else if (in_array($thisTime, $payment_artist[$firstTime]['time'])) {
                                                    // 예약완료된 시간
                                                    $css_status = 'res';
                                                    $check = true;
                                                } else if (in_array($thisTime, $reservation_artist[$firstTime]['time']) && $reservation_artist[$firstTime]['data']['customer_id'] == $user_id) {
                                                    // 예약중인 시간
                                                    $css_status = 'on';
                                                } else if (in_array($thisTime, $reservation_artist[$firstTime]['time'])) {
                                                    // 예약중인 시간
                                                    $css_status = 'ing';
                                                } else if ($i > 0 && $i < 3) {
                                                    $css_status = "avoid";
                                                    if ($check) $css_status .= " after-res";
												} else if (strpos($data['time_off']['res_time_off'], $thisTimeTo."~".$thisTimeFrom) !== false) {
													$css_status = "not";
                                                } else {
                                                    $css_status = '';
                                                }

                                                if ($css_status != "" && $css_status != "avoid" && $css_status != "avoid after-res") {
                                                    $i = 0;
                                                }
                                                // var_dump($reservation_artist[$firstTime]['time']);
                                                $is_payment_time = in_array($thisTime, $payment_artist[$firstTime]['time']);
                                                $is_reservation_time = in_array($thisTime, $reservation_artist[$firstTime]['time']);

                                                if ($is_payment_time) {
                                                    $time_start = date2timestamp(strtotime(date('Y-m-d H:i', strtotime($payment_artist[$firstTime]['data']['res_time_start']))));
                                                    $time_end = date2timestamp(strtotime(date('Y-m-d H:i', strtotime($payment_artist[$firstTime]['data']['res_time_end']))));
                                                    $time_full = ($time_end - $time_start) ? ($time_end - $time_start) : (60 * 30);

                                                    $time_block_cnt = $time_full / (60 * 30); // 블럭갯수

                                                    if ($rowspan['payment'][$artist['name']][$thisTime]) {
                                                        $style_height = 30 * (int) $time_block_cnt;
                                                        // $style_height = 30*($rowspan['payment'][$artist['name']][$thisTime]);
                                        ?>
                                                        <div class="time_cell <?= $css_status ?> <?= $css_line ?>" onClick="" data-hour="<?= sprintf('%02d', $t) ?>" data-minute="<?= sprintf('%02d', $t30) ?>" data-rowspan="<?= $time_block_cnt ?>" data-time="<? $thisTime ?>" date-session-id="<?= $session_id[$firstTime] ?>" style="height:<?= $style_height ?>px;line-height:<?= $style_height ?>px;"><? $thisTime ?><?= $status ?></div>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <div class="time_cell <?= $css_status ?> <?= $css_line ?>" onClick="" data-hour="<?= sprintf('%02d', $t) ?>" data-minute="<?= sprintf('%02d', $t30) ?>" data-rowspan="" data-time="<?= $thisTime ?>" date-session-id="<?= $session_id[$firstTime] ?>" style="display: none"><? $thisTime ?><?= $status ?></div>
                                                    <?php
                                                    }
                                                } else 
                                            if ($is_reservation_time) {
                                                    // 예약상황
                                                    $time_start = date2timestamp(strtotime(date('Y-m-d H:i', strtotime($reservation_artist[$firstTime]['data']['res_time_start']))));
                                                    $time_end =  $time_start + (int) ($rowspan['reservation'][$artist['name']][$thisTime]['count'] * (60 * 30));
                                                    $time_full = ($time_end - $time_start) ? ($time_end - $time_start) : (60 * 30);

                                                    $time_block_cnt = $time_full / (60 * 30); // 블럭갯수
                                                    // echo in_array($thisTime,$reservation_artist[$firstTime]['time']);
                                                    if ($rowspan['reservation'][$artist['name']][$thisTime]['count']) {
                                                        // var_dump($rowspan['reservation'][$artist['name']][$thisTime]);
                                                        // var_dump($reservation_artist[$firstTime]['time']);
                                                        // echo $time_block_cnt;
                                                        $style_height = 30 * (int) $time_block_cnt;
                                                    ?>
                                                        <div class="time_cell <?= $css_status ?> <?= $css_line ?>" onClick="" data-hour="<?= sprintf('%02d', $t) ?>" data-minute="<?= sprintf('%02d', $t30) ?>" data-rowspan="<?= $time_block_cnt ?>" data-time="<?= $thisTime ?>" date-session-id="<?= $session_id[$firstTime] ?>" style="height:<?= $style_height ?>px;line-height:<?= $style_height ?>px;"><? $thisTime ?><?= $status ?></div>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <div class="time_cell <?= $css_status ?> <?= $css_line ?>" onClick="" data-hour="<?= sprintf('%02d', $t) ?>" data-minute="<?= sprintf('%02d', $t30) ?>" data-rowspan="" data-time="<?= $thisTime ?>" date-session-id="<?= $session_id[$firstTime] ?>" style="display: none"><? $thisTime ?><?= $status ?></div>
                                                    <?php
                                                    }
                                                } else 
                                            if (in_array($thisTime, $data['private_holiday'][$artist['name']]['time'])) {
                                                    // var_dump($data['private_holiday'][$artist['name']]['data']);
                                                    // echo $thisTime;
                                                    if ($ph_seq = $data['private_holiday'][$artist['name']]['data'][$thisTime]['ph_seq']) {
                                                        // echo $ph_seq;
                                                        // echo '<br/>';
                                                        $block_count = $data['private_holiday'][$artist['name']]['count'][$thisTime];
                                                        $style_height = 30 * (int) $block_count;
                                                    ?>
                                                        <div class="time_cell <?= $css_status ?> <?= $css_line ?>" onClick="" style="height:<?= $style_height ?>px;line-height:<?= $style_height ?>px;" data-hour="<?= sprintf('%02d', $t) ?>" data-minute="<?= sprintf('%02d', $t30) ?>" data-rowspan="" data-time="" date-session-id="<?= $session_id[$firstTime] ?>"><? $thisTime ?><?= $status ?></div>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <div class="time_cell <?= $css_status ?> <?= $css_line ?>" onClick="" data-hour="<?= sprintf('%02d', $t) ?>" data-minute="<?= sprintf('%02d', $t30) ?>" data-rowspan="" data-time="" date-session-id="<?= $session_id[$firstTime] ?>" style="display:none;"><? $thisTime ?><?= $status ?></div>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
													<div class="time_cell <?= $css_status ?> <?= $css_line ?>" onClick="" data-hour="<?= sprintf('%02d', $t) ?>" data-minute="<?= sprintf('%02d', $t30) ?>" data-rowspan="" data-time="" date-session-id="<?= $session_id[$firstTime] ?>"><? $thisTime ?><?= $status ?></div>
													<?php
                                                }
                                                if ($css_status == "" || $css_status == "avoid" || $css_status == "avoid after-res") $i++;
                                            }
                                            ?>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </td>
                        <?php }
                        }
                        ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <?php if ($ch == 'time') { ?>
        <div class="time_func">
            <a href="#" class="btn_time_choice_ok" style="display:none;"><span>선택</span></a>
        </div>
    <?php } ?>

    <script type="text/javascript">
        var artist_idx = '';
        jQuery(function($) {
            var obj_time_table = $(document).find("#time_table");
            var obj_calender = $(document).find("#calander");

            // 셀 Over 함수
            $.fn.time_cell_over = function(e) {
                // console.log('time_cell_over', $(e));
            }

            $(document).find(".btn_calander").on('click', function(e) {
                e.preventDefault();

                // alert( obj_calender.css('display') );
                if (obj_calender.css('display') == 'none') {
                    obj_calender.show();
                    obj_time_table.hide();
                    $(document).find('.time_func').hide();
                    $(document).find('.time_table_caption').hide();
                    $(this).hide(); // [patch] 2019.01.03 | developlay - 달력아이콘 감춤
                } else {
                    // obj_calender.hide();
                    // obj_time_table.show();
                }
            });

            $.fn.time_clear = function(e) {
                var obj_time_row = $(this);
                var obj_time = obj_time_row.closest('tr.time');
                artist_idx = obj_time_row.closest('td').index();
                // console.log('artist_idx', artist_idx);

                // on 클래스를 가진것을 대상으로 초기화를 한다.
                obj_time.find('.artist_time_line').each(function() {
                    var obj_time_line = $(this);

                    // artist_time_line 의 선택여부 on 클래스를 초기화한다.
                    if (obj_time_line.hasClass('on')) {
                        obj_time_line.removeClass('on');
                    }

                    if (obj_time_line.find('.time_cell').hasClass('on')) {
                        obj_time_line.find('.time_cell').not('.not').not('.res').not('.ing').removeClass('on').attr('data-rowspan', '').removeAttr('style').show();
                    }
                });

            };
            $.fn.time_choice = function(e) {
                var obj_time_row = $(this);
                var obj_time = obj_time_row.closest('tr.time');
                artist_idx = obj_time_row.closest('td').index();
                // console.log('artist_idx', artist_idx);

                // on 클래스를 가진것을 대상으로 초기화를 한다.
                obj_time.find('.artist_time_line').each(function() {
                    var obj_time_line = $(this);

                    // artist_time_line 의 선택여부 on 클래스를 초기화한다.
                    if (obj_time_line.hasClass('on')) {
                        obj_time_line.removeClass('on');
                    }

                    if (obj_time_line.find('.time_cell').hasClass('on')) {
                        obj_time_line.find('.time_cell').not('.not').not('.res').not('.ing').not('.holiday').removeClass('on').attr('data-rowspan', '').removeAttr('style').show();
                    }
                });

                obj_time_row.closest('.artist_time_line').addClass('on');

                var obj_time_row_next = obj_time_row;
                // console.log( obj_time_row_next );
				var selected_worktime = ("<?=$_GET['timediff'] ?>" != "")? Math.round(parseInt("<?=$_GET['timediff'] ?>") / 30) : 1;
                var rr = 1;
                for (var r = 1; r < selected_worktime; r++) {
                    var obj_time_row_next = obj_time_row_next.next();
                    // console.log( r , obj_time_row_next.length , obj_time_row_next.hasClass('on') , obj_time_row_next.hasClass('ing') );
                    if (obj_time_row_next.length) {
                        if (!obj_time_row_next.hasClass('ing') && !obj_time_row_next.hasClass('not') && !obj_time_row_next.hasClass('holiday') && !obj_time_row_next.hasClass('res')) {
                            if (!obj_time_row_next.hasClass('on')) {
                                rr++;
                            }
                        } else {
                            r = selected_worktime;
                            // if ( !obj_time_row_next.hasClass('on') ) { rr++; }
                        }
                    }
                }

                //if (rr <= 2) {
				if (selected_worktime != rr) {
					var _selected_worktime = selected_worktime * 30;
					_selected_worktime = (_selected_worktime % 60 > 0)? Math.floor(_selected_worktime / 60)+"시간 "+(_selected_worktime % 60)+"분" : Math.floor(_selected_worktime / 60)+"시간";
					$(document).find('.btn_time_choice_ok').hide();

                    $.MessageBox({
                        buttonDone: {
                            "확인": {
                                text: "확인",
                                keyCode: 13
                            }
                        },
                        message: "선택하신 미용의 소요예상시간("+_selected_worktime+", "+selected_worktime+"칸)보다 <br/>예약가능 칸이 더 적습니다. 다른 시간을 예약해주세요." // 예약 시간이 1시간 이하인 경우 샵에서 예약이 조정 될 수 있습니다. > // 마감 시간이 인접한 예약인 경우 샵에서 예약이 조정 될 수 있습니다.
                    }).done(function() {});
                }
                // console.log( 'rr' , rr );
                var obj_time_row_next = obj_time_row;
                for (var r = 1; r < rr; r++) {
                    // console.log( $r );
                    var obj_time_row_next = obj_time_row_next.next();
                    obj_time_row_next.addClass('on').hide();
                }

                obj_time_row.addClass('on');
                obj_time_row.attr('data-rowspan', rr);
                obj_time_row.css({
                    'height': (30 * rr) + 'px',
                    'line-height': (30 * rr) + 'px'
                });

                $(document).find('.btn_time_choice_ok').show();

            };

            $.fn.time_selector = function(e) {
                var obj_time_row = $(this);

                // 초기화
                // alert( obj_time_table.find('tr').length );
                obj_time_table.find('tr').each(function() {
                    if (!$(this).hasClass('space') && !$(this).hasClass('res')) {
                        $(this).removeClass('on');
                        $(this).find('td').eq(0).removeClass('bg_red');
                        $(this).find('td').eq(1).attr('rowspan', '1').html('').show();
                    }
                    //  console.log($(this).find('td').eq(1).length);
                });
                // 초기화

                obj_time_row.addClass('on');
                var val_gobeauty_cart_hour = obj_time_row.find('input[name="gobeauty_cart_hour"]').val();


                var obj_time_sel = obj_time_row;
                var $val_rowspan = 1;

                for ($i = obj_time_row.index(); $i < obj_time_row.index() + 5; $i++) {
                    // console.log(obj_time_table.find('tr').eq($i + 1));
                    var obj_time_sel = obj_time_table.find('tr').eq($i + 1);
                    // console.log($i,$val_rowspan,obj_time_row.index(),obj_time_row.index()+6,obj_time_sel.eq($i).length,obj_time_sel.eq($i).find(">td").length);

                    if (obj_time_sel.hasClass('res') || obj_time_sel.hasClass('space')) {
                        // 예약되어 있거나 빈공간인경우 패스
                        break;
                    } else {
                        if (obj_time_sel.length) {

                            $val_rowspan++;

                            obj_time_sel.addClass('on');
                            obj_time_sel.find(">td").eq(1).hide();

                            obj_time_row.find(">td").eq(1).empty();
                            obj_time_row.find(">td").eq(1).attr('rowspan', $val_rowspan);

                            var obj_label_status = $('<div></div>');
                            obj_label_status.html('');
                            obj_time_row.find(">td").eq(1).append(obj_label_status);

                            // var obj_label_between = $('<div></div>');
                            // obj_label_between.html('( 예약시간 : '+obj_time_row.find(">td").eq(0).html()+' 부터 '+obj_time_sel.next().find(">td").html()+' 까지 )');
                            // obj_time_row.find(">td").eq(1).append(obj_label_between);
                        }
                    }
                }

                // console.log( $val_rowspan );
                if ($val_rowspan <= 4) {
                    var obj_label_warning = $('<div></div>');
                    obj_label_warning.html('미용가능시간이 1시간 이하인 경우 샵에서 조정될 수 있습니다.');
                    obj_time_row.find(">td").eq(1).append(obj_label_warning);
                } else {
                    var obj_label_warning = $('<div></div>');
                    obj_label_warning.html('모든 예약은 기본 2시간으로 설정됩니다.');
                    obj_time_row.find(">td").eq(1).append(obj_label_warning);
                }
            }

            obj_time_table.on('mouseover', 'div.artist_time_line.on > div.time_cell.on', function() {
                $(this).time_cell_over();
            });

            // 미용사 타임라인을 선택
            obj_time_table.off('click.time_table').on('click.time_table', 'div.artist_time_line > div.time_cell', function(e) {
                var obj_time_row = $(this);
                // e.preventDefault();

                if (obj_time_row.hasClass('on')) return;
                if (obj_time_row.hasClass('res')) return;
                if (obj_time_row.hasClass('space')) return;
                if (obj_time_row.hasClass('ing')) return;
                if (obj_time_row.hasClass('not')) return;
                if (obj_time_row.hasClass('holiday')) return;
                if (obj_time_row.hasClass('avoid')) {
                    if (obj_time_row.hasClass('after-res')) {
                        $.MessageBox({
                            buttonDone: {
                                "확인": {
                                    text: "확인",
                                    keyCode: 13
                                }
                            },
                            message: "앞 시간이 비어있어요!<br/> 앞 시간부터 선택해 주세요!"
                        }).done(function() {});
                    } else {
                        $.MessageBox({
                            buttonDone: {
                                "확인": {
                                    text: "확인",
                                    keyCode: 13
                                }
                            },
                            message: "앞 시간이 비어있어요!<br/> 앞 시간부터 선택해 주세요!"
                        }).done(function() {});
                    }
                    return;
                }

                // $(this).time_selector();
                obj_time_row.time_choice();
            });

            // 전체 타임라인 선택
            obj_time_table.off('click.time_table_caption').on('click.time_table_caption', 'div.time_line > div.time_cell', function(e) {
                var obj_time_row = $(this);
                var idx_time_row = obj_time_row.index();
                var obj_target = obj_time_table.find('div.artist_time_line.on > div.time_cell').eq(idx_time_row);
                // console.log( idx_time_row , obj_target );
                // e.preventDefault();

                // if ( obj_target.hasClass('on') ) return;
                if (obj_target.hasClass('res')) return;
                if (obj_target.hasClass('space')) return;
                if (obj_target.hasClass('ing')) return;
                if (obj_target.hasClass('not')) return;
                if (obj_target.hasClass('holiday')) return;
                if (obj_time_row.hasClass('avoid')) {
                    if (obj_time_row.hasClass('after-res')) {
                        $.MessageBox({
                            buttonDone: {
                                "확인": {
                                    text: "확인",
                                    keyCode: 13
                                }
                            },
                            message: "펫샵의 원할한 영업을 위해<br />빈시간 없이 선택해주세요."
                        }).done(function() {});
                    } else {
                        $.MessageBox({
                            buttonDone: {
                                "확인": {
                                    text: "확인",
                                    keyCode: 13
                                }
                            },
                            message: "펫샵의 원할한 영업을 위해<br />영업시작 시간부터 선택해주세요."
                        }).done(function() {});
                    }
                    return;
                }

                // $(this).time_selector();
                obj_target.time_choice();
                // $(this).time_choice();
            });

            // 전체 타임라인 중 시간 마우스 인/아웃
            // obj_time_table.on('mouseover','div.time_line > div.time_cell',function(e) {
            //     var obj_time_row = $(this);
            //     var idx_time_row = obj_time_row.index();
            //     var obj_target = obj_time_table.find('div.artist_time_line.on > div.time_cell').eq(idx_time_row);
            //     console.log( 'idx_time_row',idx_time_row );
            //     e.preventDefault();

            //     if ( obj_target.hasClass('on') ) return;
            //     if ( obj_target.hasClass('res') ) return;
            //     if ( obj_target.hasClass('space') ) return;
            //     if ( obj_target.hasClass('ing') ) return;
            //     if ( obj_target.hasClass('not') ) return;

            //     // $(this).time_selector();
            //     // obj_time_table.find('div.artist_time_line.on > div.time_cell').eq(idx_time_row);
            //     // $(this).time_choice();
            // });

            // 완료버튼 클릭
            $(document).on('click', '.btn_time_choice_ok', function(e) {
				setParentText();
				/*
                e.preventDefault();

                var obj_time_line = obj_time_table.find('div.artist_time_line.on');
                var obj_time_cell = obj_time_line.find('> div.time_cell.on');

                var val_artist_id = obj_time_line.attr('data-artist-id');
                var val_artist_name = obj_time_line.attr('data-artist-name');
                var val_year = obj_time_line.attr('data-year');
                var val_month = obj_time_line.attr('data-month');
                var val_day = obj_time_line.attr('data-day');
                var val_hour = obj_time_cell.attr('data-hour');
                var val_minute = obj_time_cell.attr('data-minute');
                var val_time = obj_time_cell.attr('data-time');
                var val_rowspan = obj_time_cell.attr('data-rowspan');
                var val_user_id = obj_time_line.attr('data-user-id');

                var data_post = {
                    artist_id: val_artist_id,
                    worker: val_artist_name,
                    year: val_year,
                    month: val_month,
                    day: val_day,
                    hour: val_hour,
                    minute: val_minute,
                    time: val_time,
                    rowspan: val_rowspan,
                    user_id: val_user_id
                };

                $.ajax({
                    async: false,
                    type: 'post',
                    url: 'data_set_schedule_cart_save.php',
                    data: data_post,
                    dataType: 'json',
                    error: function() {
                        alert('Error!');
                    },
                    success: function(json) {
                        // console.log(json);
                        // document.location.reload(true);
                        document.location.href = 'reservation.php?artist_name=<?= urlencode($_SESSION['gobeauty_cart_artist_name']) ?>';
                    }
                });

                // console.log(val_artist_name, val_hour, val_minute, val_time, val_user_id);
				*/
            });

            $(document).on('scroll', function(e) {
                if ($(document).height() - $(window).height() < $(this).scrollTop() + 100) {
                    $(document).find('.btn_time_choice_ok').css('bottom', '100px');
                } else {
                    $(document).find('.btn_time_choice_ok').css('bottom', '10px');
                }
                // console.log( $(document).height()-$(window).height() , $(this).scrollTop() );
            });

        });
    </script>

<?php
	}else{
		// 2020.04.08 ulmo 타임제 스케줄 테이블
		$time_schedule_sql = "select * from tb_time_schedule where artist_id = '$artist_id' ";
		$time_schedule_result = mysql_query($time_schedule_sql);
		$time_schedule_row = mysql_fetch_object($time_schedule_result);

		$data['time_schedule']['res_time_off'] = $time_schedule_row->res_time_off;
		$schedule_arr = explode(",", $data['time_schedule']['res_time_off']);
?>
	<style>
		#time_table2 { background: white; width: 100%; font-size: 14px; }
		#time_table2 > .comment {
            display: inline-block;
            width: 100%;
            font-weight: bold;
            padding: 0.2em 0.4em;
        }
        #time_table2 > .comment > span {
            display: block;
        }
        #time_table2 > .comment > span:nth-child(3) {
            font-size: 0.8em;
        }
        #time_table2>.table {
            overflow: auto;
            border: 1px solid #aaaaaa;
            background-color: #ffffff;
            border-radius: 4px;
        }

        #time_table2>.table>table {
            width: 100%;
            border-collapse: collapse;
        }

        #time_table2>.table>table>tbody.head>tr { }

        #time_table2>.table>table>tbody.head>tr.space {}

        #time_table2>.table>table>tbody.head>tr.space>td {
            text-align: center;
            background-color: #eeeeee;
            height: 4px;
        }

        #time_table2>.table>table>tbody.head>tr.artist {}

        #time_table2>.table>table>tbody.head>tr.artist>td {
            text-align: center;
            background-color: #eeeeee;
            padding: 0.4em;
            text-align: center;
        }

        #time_table2>.table>table>tbody.head>tr.artist>td>label.artist_nicname {
            text-align: center;
        }

        #time_table2>.table>table>tbody.time>tr.time>th {
            text-align: center;
            padding: 0px;
            border-top: 1px solid #dfdfdf;
            background-color: #eeeeee;
        }

        #time_table2>.table>table>tbody.time>tr.time>th .time_line {}

        #time_table2>.table>table>tbody.time>tr.time>th .time_line>.time_cell {
            height: 30px;
            line-height: 30px;
            border-bottom: 1px dotted #dfdfdf;
            box-sizing: border-box;
            font-size: 0.9em;
            font-weight: normal;
        }

        #time_table2>.table>table>tbody.time>tr.time>th .time_line>.time_cell.line_solid {
            border-bottom: 1px solid #dfdfdf !important;
        }

        #time_table2>.table>table>tbody.time>tr.time>td {
            padding: 0px;
            border-top: 1px solid #dfdfdf;
            border-right: 1px dotted #eeeeee;
            background-color: #ffffff;
        }

        #time_table2>.table>table>tbody.time>tr.time>td:last-child {
            border-right: 0px;
        }

        #time_table2>.table>table>tbody.time>tr.time>td .artist_time_line {}

        #time_table2>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell {
            cursor: pointer;
            position: relative;
            height: 30px;
            line-height: 30px;
            text-align: center;
            border-bottom: 1px dotted #dfdfdf;
            box-sizing: border-box;
            font-size: 1em;
            font-weight: normal;
            background-color: #ffffff;
			border: 1px solid #ccc;
			margin: 10px;

        }

        /* #time_table2>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell.avoid {
            background-color: rgba(0, 0, 0, 0.1);
        }

        #time_table2>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell.after-res {
            background-color: rgba(0, 0, 0, 0.1);
        } */

        #time_table2>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell.not {
            background-color: rgba(0, 0, 0, 0.1);
			color: #999;
        }

        #time_table2>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell.holiday {
            background-color: rgba(0, 0, 0, 0.1);
            cursor: default;
            color: rgba(0, 0, 0, 0.5);
            font-size: 0.8em;
            letter-spacing: -0.2em;
        }

        #time_table2>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell.on {
            background-color: #fff390;
			border: 1px solid #999;
        }

        #time_table2>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell.ing {
            background-color: rgba(0, 0, 0, 0.1);
        }

        #time_table2>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell.res {
            background-color: rgba(0, 0, 0, 0.1);
        }

        #time_table2>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell.line_solid {
            border-bottom: 1px solid #dfdfdf !important;
        }

        @media only screen and (min-width: 768px) {
            #time_table2>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell.on:hover {
                background-color: #fff390;
            }

            #time_table2>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell:not(.holiday):hover::before {
                display: block;
                left: 0px;
                width: 100%;
                content: attr(data-time);
                font-size: 12px;
                box-sizing: border-box;
                position: absolute;
                text-align: center;
            }

            #time_table2>.table>table>tbody.time>tr.time>td .artist_time_line>.time_cell:not(.not):hover::before {
                display: block;
                left: 0px;
                width: 100%;
                content: attr(data-time);
                font-size: 12px;
                box-sizing: border-box;
                position: absolute;
                text-align: center;
            }
        }
	</style>
	<div id="time_table2" class="time_schedule">
        <div class="comment">
            <a href="#calander" class="btn_calander"><img src="/pet/images/calendar-2027122_640.png" width="30" height="30" /></a> <!-- [peach] 2019.01.16 : developlay - 카렌더 이미지 변경 -->
            <span>시간선택 후 선택 버튼을 눌러주세요</span>
            <!-- <span>(모든 예약은 기본 2시간으로 설정됩니다)</span> -->
            <!-- [patch] 2019.01.03 | developlay - 달력아이콘 위치 이동 -->
        </div>
		<div class="table">
            <table>
                <colgroup>
                    <?php foreach ((array) $data['artist'] as $key => $artist) { ?>
                        <col style="width:auto;"><?php } ?>
                </colgroup>
                <tbody class="head">
                    <tr class="artist">
					<?php
						if (!($week_idx == 0 && $is_rest_public_holiday)) {
							foreach ((array) $data['artist'] as $key => $artist) { 
					?>
						<td style="">
							<label class="artist_nicname"><?= $artist['nicname'] ?></label>
						</td>
					<?php 
							}
						}
					?>
                    </tr>
                </tbody>
                <tbody class="time">
					<tr class="time">
						<?php
						if (!($week_idx == 0 && $is_rest_public_holiday)) {
							foreach ((array) $data['artist'] as $artist_key => $artist) {
								$payment_artist = $data['payment'][$artist['name']];
								$reservation_artist = $data['reservation'][$artist['name']];
								//echo "<pre>"; print_r($data); echo "</pre>";
						?>
						<td>
							<div class="artist_time_line <?= $artist_key == 0 ? 'on' : '' ?>" data-user-id="<?= $user_id ?>" data-artist-id="<?= $artist['artist_id'] ?>" data-artist-name="<?= $artist['name'] ?>" data-year="<?= $yy ?>" data-month="<?= $mm ?>" data-day="<?= $dd ?>">
								<?php
								// 결제, 예약시간 가져오기
								//$payment_time = $payment_artist[$time_arr[0]]['time'];
								//$reservation_time = $reservation_artist[$time_arr[0]]['time'];
								$payment_time = array();
								foreach($payment_artist as $key => $value){
									foreach($value as $key2 => $value2){
										if($key2 == "time"){
											foreach($value2 as $key3 => $value3){
												$payment_time[$value3] = $value3;
											}
										}
									}
								}
								$reservation_time = array();
								foreach($reservation_artist as $key => $value){
									foreach($value as $key2 => $value2){
										if($key2 == "time"){
											foreach($value2 as $key3 => $value3){
												$reservation_time[$value3] = $value3;
											}
										}
									}
								}
								//echo "<pre>"; print_r($payment_time); echo "</pre>";

								foreach($schedule_arr as $key => $value){
									$time_arr = explode("~", $value);
									$t = explode(":", $time_arr[0]);
									$css_status = '';
									$comment = '';
									$status = '';

									//오늘 시간 지나면 예약 선택 불가능하게
									$nowTime = strtotime(date("Y-m-d H:i:s"));
									$reservationTime = strtotime("$yy-$mm-$dd " . $time_arr[0]);

									// 시간을 숫자타입으로 변경
									$int_thisTime = (int) date('Hi', strtotime($time_arr[0]));
									$int_time_start = (int) date('Hi', strtotime($artist['time_start']));
									$int_time_end = (int) date('Hi', strtotime($artist['time_end']));

									// 날짜형식변경(YYYYMMDD)
									$filled_mm = sprintf("%02d", $mm);
									$diff_Ymd = $yy.$filled_mm.$dd;
									$today_Ymd = date('Ymd');

									if (in_array($time_arr[0], $payment_time)) { // 예약중
										$firstTime = $time_arr[0];
									}else if(in_array($time_arr[0], $reservation_time)){ // 예약완료
										$firstTime = $time_arr[0];
									}

									if ($holiweek_arr[(int) date('w', strtotime("{$yy}-{$mm}-{$dd}"))] == 1) {
										$css_status = 'space';
									} else if ($reservationTime <= $nowTime) {
										//오늘 중 시간 초과 시 예약 불가능
										$css_status = 'not';
									} else if ($int_thisTime < $int_time_start || (($int_time_end != 0) && ($int_thisTime >= $int_time_end))) {
										// 근무시간 외 시간
										$css_status = 'not';
									} else if (($reservationTime - $nowTime) <= 3600) {
										// 근무시간 외 시간
										$css_status = 'not';
									} else if ($today_Ymd == $diff_Ymd) {
										//----- 당일 예약 불가
										$css_status = 'not';
									} else if (in_array($time_arr[0], $data['private_holiday'][$artist['name']]['time'])) {
										// 근무시간 외 시간
										$css_status = 'holiday';
										$status = '';
									} else if (in_array($time_arr[0], $payment_time)) {
										// 예약완료된 시간
										$css_status = 'res';
										$check = true;
									} else if (in_array($time_arr[0], $reservation_time) && $reservation_artist[$firstTime]['data']['customer_id'] == $user_id) {
										// 예약중인 시간
										$css_status = 'on';
									} else if (in_array($time_arr[0], $reservation_time)) {
										// 예약중인 시간
										$css_status = 'ing';									
									} else if (strpos($data['time_off']['res_time_off'], $value) !== false) {
										// 비활성화 선택
										$css_status = "not";
                                    }
								?>
									<div class="time_cell <?= $css_status ?>" onClick="" data-hour="<?= sprintf('%02d', $t[0]) ?>" data-minute="<?= sprintf('%02d', $t[1]) ?>" data-rowspan="" data-time="" date-session-id="<?= $session_id[$time_arr[0]] ?>"><?= $time_arr[0]; ?><?= $status ?></div>
								<?php
								}
								?>
							</div>
						</td>
						<?php
								}
							}
						?>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
    <?php if ($ch == 'time') { ?>
        <div class="time_func">
            <a href="#" class="btn_time_choice_ok" style="display:none;"><span>선택</span></a>
        </div>
    <?php } ?>

	<script>
		var artist_idx = '';
        jQuery(function($) {
            var obj_time_table = $(document).find("#time_table2");
            var obj_calender = $(document).find("#calander");

            // 셀 Over 함수
            $.fn.time_cell_over = function(e) {
                // console.log('time_cell_over', $(e));
            }

            $(document).find(".btn_calander").on('click', function(e) {
                e.preventDefault();

                // alert( obj_calender.css('display') );
                if (obj_calender.css('display') == 'none') {
                    obj_calender.show();
                    obj_time_table.hide();
                    $(document).find('.time_func').hide();
                    $(document).find('.time_table_caption').hide();
                    $(this).hide(); // [patch] 2019.01.03 | developlay - 달력아이콘 감춤
                } else {
                    // obj_calender.hide();
                    // obj_time_table.show();
                }
            });

            $.fn.time_clear = function(e) {
                var obj_time_row = $(this);
                var obj_time = obj_time_row.closest('tr.time');
                artist_idx = obj_time_row.closest('td').index();
                // console.log('artist_idx', artist_idx);

                // on 클래스를 가진것을 대상으로 초기화를 한다.
                obj_time.find('.artist_time_line').each(function() {
                    var obj_time_line = $(this);

                    // artist_time_line 의 선택여부 on 클래스를 초기화한다.
                    if (obj_time_line.hasClass('on')) {
                        obj_time_line.removeClass('on');
                    }

                    if (obj_time_line.find('.time_cell').hasClass('on')) {
                        obj_time_line.find('.time_cell').not('.not').not('.res').not('.ing').removeClass('on').attr('data-rowspan', '').removeAttr('style').show();
                    }
                });

            };
            $.fn.time_choice = function(e) {
                var obj_time_row = $(this);
                var obj_time = obj_time_row.closest('tr.time');
                artist_idx = obj_time_row.closest('td').index();
                // console.log('artist_idx', artist_idx);

                // on 클래스를 가진것을 대상으로 초기화를 한다.
                obj_time.find('.artist_time_line').each(function() {
                    var obj_time_line = $(this);

                    // artist_time_line 의 선택여부 on 클래스를 초기화한다.
                    if (obj_time_line.hasClass('on')) {
                        obj_time_line.removeClass('on');
                    }

                    if (obj_time_line.find('.time_cell').hasClass('on')) {
                        obj_time_line.find('.time_cell').not('.not').not('.res').not('.ing').not('.holiday').removeClass('on').attr('data-rowspan', '').removeAttr('style').show();
                    }
                });

                obj_time_row.closest('.artist_time_line').addClass('on');
				obj_time_row.addClass('on');
				obj_time_row.attr('data-rowspan', 1); // 타임제 기본예약은 최소단위인 30분으로 추가

                var obj_time_row_next = obj_time_row;
                // console.log( obj_time_row_next );
				/*
                var rr = 1;
                for (var r = 1; r < 4; r++) {
                    var obj_time_row_next = obj_time_row_next.next();
                    // console.log( r , obj_time_row_next.length , obj_time_row_next.hasClass('on') , obj_time_row_next.hasClass('ing') );
                    if (obj_time_row_next.length) {
                        if (!obj_time_row_next.hasClass('ing') && !obj_time_row_next.hasClass('not') && !obj_time_row_next.hasClass('holiday') && !obj_time_row_next.hasClass('res')) {
                            if (!obj_time_row_next.hasClass('on')) {
                                rr++;
                            }
                        } else {
                            r = 4;
                            // if ( !obj_time_row_next.hasClass('on') ) { rr++; }
                        }
                    }
                }

                if (rr <= 2) {
                    $.MessageBox({
                        buttonDone: {
                            "확인": {
                                text: "확인",
                                keyCode: 13
                            }
                        },
                        message: "예약 시간이 1시간 이하인 경우 샵에서 예약이 조정 될 수 있습니다."
                    }).done(function() {});
                }
                // console.log( 'rr' , rr );
                var obj_time_row_next = obj_time_row;
                for (var r = 1; r < rr; r++) {
                    // console.log( $r );
                    var obj_time_row_next = obj_time_row_next.next();
                    obj_time_row_next.addClass('on').hide();
                }

                obj_time_row.addClass('on');
                obj_time_row.attr('data-rowspan', rr);
                obj_time_row.css({
                    'height': (30 * rr) + 'px',
                    'line-height': (30 * rr) + 'px'
                });
				*/

                $(document).find('.btn_time_choice_ok').show();

            };

            $.fn.time_selector = function(e) {
                var obj_time_row = $(this);

                // 초기화
                // alert( obj_time_table.find('tr').length );
                obj_time_table.find('tr').each(function() {
                    if (!$(this).hasClass('space') && !$(this).hasClass('res')) {
                        $(this).removeClass('on');
                        $(this).find('td').eq(0).removeClass('bg_red');
                        $(this).find('td').eq(1).attr('rowspan', '1').html('').show();
                    }
                    //  console.log($(this).find('td').eq(1).length);
                });
                // 초기화

                obj_time_row.addClass('on');
                var val_gobeauty_cart_hour = obj_time_row.find('input[name="gobeauty_cart_hour"]').val();


                var obj_time_sel = obj_time_row;
                var $val_rowspan = 1;

                for ($i = obj_time_row.index(); $i < obj_time_row.index() + 5; $i++) {
                    // console.log(obj_time_table.find('tr').eq($i + 1));
                    var obj_time_sel = obj_time_table.find('tr').eq($i + 1);
                    // console.log($i,$val_rowspan,obj_time_row.index(),obj_time_row.index()+6,obj_time_sel.eq($i).length,obj_time_sel.eq($i).find(">td").length);

                    if (obj_time_sel.hasClass('res') || obj_time_sel.hasClass('space')) {
                        // 예약되어 있거나 빈공간인경우 패스
                        break;
                    } else {
                        if (obj_time_sel.length) {

                            $val_rowspan++;

                            obj_time_sel.addClass('on');
                            obj_time_sel.find(">td").eq(1).hide();

                            obj_time_row.find(">td").eq(1).empty();
                            obj_time_row.find(">td").eq(1).attr('rowspan', $val_rowspan);

                            var obj_label_status = $('<div></div>');
                            obj_label_status.html('');
                            obj_time_row.find(">td").eq(1).append(obj_label_status);

                            // var obj_label_between = $('<div></div>');
                            // obj_label_between.html('( 예약시간 : '+obj_time_row.find(">td").eq(0).html()+' 부터 '+obj_time_sel.next().find(">td").html()+' 까지 )');
                            // obj_time_row.find(">td").eq(1).append(obj_label_between);
                        }
                    }
                }

                // console.log( $val_rowspan );
                if ($val_rowspan <= 4) {
                    var obj_label_warning = $('<div></div>');
                    obj_label_warning.html('미용가능시간이 1시간 이하인 경우 샵에서 조정될 수 있습니다.');
                    obj_time_row.find(">td").eq(1).append(obj_label_warning);
                } else {
                    var obj_label_warning = $('<div></div>');
                    obj_label_warning.html('모든 예약은 기본 2시간으로 설정됩니다.');
                    obj_time_row.find(">td").eq(1).append(obj_label_warning);
                }
            }

            obj_time_table.on('mouseover', 'div.artist_time_line.on > div.time_cell.on', function() {
                $(this).time_cell_over();
            });

            // 미용사 타임라인을 선택
            obj_time_table.off('click.time_table').on('click.time_table', 'div.artist_time_line > div.time_cell', function(e) {
                var obj_time_row = $(this);
                // e.preventDefault();

                if (obj_time_row.hasClass('on')) return;
                if (obj_time_row.hasClass('res')) return;
                if (obj_time_row.hasClass('space')) return;
                if (obj_time_row.hasClass('ing')) return;
                if (obj_time_row.hasClass('not')) return;
                if (obj_time_row.hasClass('holiday')) return;
                if (obj_time_row.hasClass('avoid')) {
                    if (obj_time_row.hasClass('after-res')) {
                        $.MessageBox({
                            buttonDone: {
                                "확인": {
                                    text: "확인",
                                    keyCode: 13
                                }
                            },
                            message: "펫샵의 원할한 영업을 위해<br />빈시간 없이 선택해주세요."
                        }).done(function() {});
                    } else {
                        $.MessageBox({
                            buttonDone: {
                                "확인": {
                                    text: "확인",
                                    keyCode: 13
                                }
                            },
                            message: "펫샵의 원할한 영업을 위해<br />영업시작 시간부터 선택해주세요."
                        }).done(function() {});
                    }
                    return;
                }

                // $(this).time_selector();
                obj_time_row.time_choice();
            });

            // 전체 타임라인 선택
            obj_time_table.off('click.time_table_caption').on('click.time_table_caption', 'div.time_line > div.time_cell', function(e) {
                var obj_time_row = $(this);
                var idx_time_row = obj_time_row.index();
                var obj_target = obj_time_table.find('div.artist_time_line.on > div.time_cell').eq(idx_time_row);
                // console.log( idx_time_row , obj_target );
                // e.preventDefault();

                // if ( obj_target.hasClass('on') ) return;
                if (obj_target.hasClass('res')) return;
                if (obj_target.hasClass('space')) return;
                if (obj_target.hasClass('ing')) return;
                if (obj_target.hasClass('not')) return;
                if (obj_target.hasClass('holiday')) return;
                if (obj_time_row.hasClass('avoid')) {
                    if (obj_time_row.hasClass('after-res')) {
                        $.MessageBox({
                            buttonDone: {
                                "확인": {
                                    text: "확인",
                                    keyCode: 13
                                }
                            },
                            message: "펫샵의 원할한 영업을 위해<br />빈시간 없이 선택해주세요."
                        }).done(function() {});
                    } else {
                        $.MessageBox({
                            buttonDone: {
                                "확인": {
                                    text: "확인",
                                    keyCode: 13
                                }
                            },
                            message: "펫샵의 원할한 영업을 위해<br />영업시작 시간부터 선택해주세요."
                        }).done(function() {});
                    }
                    return;
                }

                // $(this).time_selector();
                obj_target.time_choice();
                // $(this).time_choice();
            });

            // 전체 타임라인 중 시간 마우스 인/아웃
            // obj_time_table.on('mouseover','div.time_line > div.time_cell',function(e) {
            //     var obj_time_row = $(this);
            //     var idx_time_row = obj_time_row.index();
            //     var obj_target = obj_time_table.find('div.artist_time_line.on > div.time_cell').eq(idx_time_row);
            //     console.log( 'idx_time_row',idx_time_row );
            //     e.preventDefault();

            //     if ( obj_target.hasClass('on') ) return;
            //     if ( obj_target.hasClass('res') ) return;
            //     if ( obj_target.hasClass('space') ) return;
            //     if ( obj_target.hasClass('ing') ) return;
            //     if ( obj_target.hasClass('not') ) return;

            //     // $(this).time_selector();
            //     // obj_time_table.find('div.artist_time_line.on > div.time_cell').eq(idx_time_row);
            //     // $(this).time_choice();
            // });

            // 완료버튼 클릭
            $(document).on('click', '.btn_time_choice_ok', function(e) {
                e.preventDefault();

                var obj_time_line = obj_time_table.find('div.artist_time_line.on');
                var obj_time_cell = obj_time_line.find('> div.time_cell.on');

                var val_artist_id = obj_time_line.attr('data-artist-id');
                var val_artist_name = obj_time_line.attr('data-artist-name');
                var val_year = obj_time_line.attr('data-year');
                var val_month = obj_time_line.attr('data-month');
                var val_day = obj_time_line.attr('data-day');
                var val_hour = obj_time_cell.attr('data-hour');
                var val_minute = obj_time_cell.attr('data-minute');
                var val_time = obj_time_cell.attr('data-time');
                var val_rowspan = obj_time_cell.attr('data-rowspan');
                var val_user_id = obj_time_line.attr('data-user-id');

                var data_post = {
                    artist_id: val_artist_id,
                    worker: val_artist_name,
                    year: val_year,
                    month: val_month,
                    day: val_day,
                    hour: val_hour,
                    minute: val_minute,
                    time: val_time,
                    rowspan: val_rowspan,
                    user_id: val_user_id
                };

                $.ajax({
                    async: false,
                    type: 'post',
                    url: 'data_set_schedule_cart_save.php',
                    data: data_post,
                    dataType: 'json',
                    error: function() {
                        alert('Error!');
                    },
                    success: function(json) {
                        // console.log(json);
                        // document.location.reload(true);
                        document.location.href = 'reservation.php?artist_name=<?= urlencode($_SESSION['gobeauty_cart_artist_name']) ?>';
                    }
                });

                // console.log(val_artist_name, val_hour, val_minute, val_time, val_user_id);
            });

            $(document).on('scroll', function(e) {
                if ($(document).height() - $(window).height() < $(this).scrollTop() + 100) {
                    $(document).find('.btn_time_choice_ok').css('bottom', '100px');
                } else {
                    $(document).find('.btn_time_choice_ok').css('bottom', '10px');
                }
                // console.log( $(document).height()-$(window).height() , $(this).scrollTop() );
            });

        });
	</script>
<?php
	}
}
?>
    <style type="text/css">
        #time_view {
            margin-bottom: 2px;
        }

        .status_nomal {
            padding: 0.2em 0.4em;
            background-color: rgba(0, 0, 0, 0.1) !important;
            border-radius: 0.8em;
        }

        .status_on {
            padding: 0.2em 0.4em;
            color: #fff;
            background-color: rgba(255, 0, 0, 1) !important;
            border-radius: 0.8em;
        }

        .status_res {
            padding: 0.2em 0.4em;
            color: #fff;
            background-color: #c123de !important;
            border-radius: 0.8em;
        }

        .bg_red {
            background-color: rgba(0, 0, 0, 0.1) !important;
        }

        .time_func {
            text-align: center;
        }

        /* .time_func a.btn { line-height: 1.8em; padding: 0.4em 0.7em; background-color: rgba(0,255,255,1) } */
        .time_func a.btn {
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #c123de), color-stop(1, #a20dbd));
            background: -moz-linear-gradient(center top, #c123de 5%, #a20dbd 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#c123de', endColorstr='#a20dbd');
            background-color: #c123de;
            -webkit-border-top-left-radius: 6px;
            -moz-border-radius-topleft: 6px;
            border-top-left-radius: 6px;
            -webkit-border-top-right-radius: 6px;
            -moz-border-radius-topright: 6px;
            border-top-right-radius: 6px;
            -webkit-border-bottom-right-radius: 6px;
            -moz-border-radius-bottomright: 6px;
            border-bottom-right-radius: 6px;
            -webkit-border-bottom-left-radius: 6px;
            -moz-border-radius-bottomleft: 6px;
            border-bottom-left-radius: 6px;
            text-indent: 0;
            border: 1px solid #a511c0;
            display: inline-block;
            color: #ffffff;
            font-family: Arial;
            font-size: 20px;
            font-weight: bold;
            font-style: normal;
            height: 40px;
            line-height: 40px;
            width: 100%;
            text-decoration: none;
            text-align: center;
        }

        .time_func a.btn:active {
            position: relative;
            top: 1px;
        }

        .time_func a.btn:disabled {
            position: relative;
            top: 1px;
        }

        .btn_calander {
            display: inline-block;
            float: right;
            margin-right: 4%;
        }

        .btn_calander>img {
            width: 30px;
            height: 30px;
        }
    </style>
    <table style="border:1px solid #999999;width:100%;padding:0px;font-size:15px;display:none;">
        <tr>
            <td>
                <b style="margin:0px;"><?= $_SESSION['gobeauty_cart_year'] ?>년 <?= $_SESSION['gobeauty_cart_month'] ?>월 <?= $_SESSION['gobeauty_cart_day'] ?>일</b><br>
                <table width="100%" id="hour_details">
                    <tbody id='region_tbody'>
                        <tr>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <table width="100%">
                    <tr>
                        <td width="20%">
                            시간선택</td>
                        <td width="50%">
                            <select name="c_input_hour" id="c_input_hour" style="height:20px;padding-left:7px;font-size:14px;color: #000000;border:1px solid #999999;border-radius: 3px;width:80%;">
                                <?php
                                /*			        for ($d_index=0;$d_index < 25;$d_index++) {
                        echo "<option value='$d_index'>$d_index</option>";
                }
*/
                                ?>
                            </select> 시</td>
                        <td width="30%">
                            <a href="#" onclick="setParentText()" class="date_submit">
                                <font style="color:#ffffff;">선택 완료</font>
                            </a>
                        </td>
                    </tr>
                </table>
                <font style="font-size:10px;"> &nbsp; *현재시간 기준 1시간 이후 부터 예약 가능</font><br>

            </td>
        </tr>
    </table>
<script>
    var hour_details = document.getElementById("hour_details");
    var region_tbody = document.getElementById('region_tbody');
    var artist_name = '<?= addslashes($_SESSION['gobeauty_cart_artist_name']) ?>';

    function zero_fill(str, cnt) {
        str = '000000' + str;
        return str.substr(str.length - cnt, cnt);
    }

    function get_hour_schedule(artist_id, yy, mm, dd) {
        var post_data = 's_year=' + yy + '&s_month=' + mm + '&s_day=' + dd;
        $.ajax({
            url: '<?=$artist_directory?>/get_hour_schedule.php',
            data: post_data,
            type: 'POST',
            success: function(data) {
                //alert(data);
                var array_middle = data.split(",");

                region_tbody.deleteRow(region_tbody.rows.length - 1);

                var region_tr = document.createElement('tr');
                region_tbody.appendChild(region_tr);
                var region_td = document.createElement('td');
                region_tr.appendChild(region_td);

                region_td.setAttribute('colspan', '3');

                clearSelectOption();
                var result = '<table width="100%" height="80px" border="1" style="border:1px solid #999999;border-collapse:collapse;text-align:center;"><tr>';
                for (var i = 0; i < array_middle.length; i++) {
                    result += '<td align=center ';
                    if (array_middle[i].trim() == '1') {
                        result += ' bgcolor="#999999"><font style="color:#ffffff;font-size:10px;">' + i + 'H<br>Off</font></div></td>';
                    } else if (array_middle[i].trim() == '2') {
                        result += ' bgcolor="#eba9f1"><font style="color:#ffffff;font-size:10px;">' + i + 'H<br>예약</font></td>';
                    } else if (array_middle[i].trim() == '3') {
                        result += ' bgcolor="#b523ac"><font style="color:#ffffff;font-size:10px;">' + i + 'H<br>예약</font></td>';
                    } else if (array_middle[i].trim() == '4') {
                        result += ' bgcolor="#d4d4d4"><font style="color:#000;font-size:10px;">' + i + 'H</font></td>';
                    } else {
                        result += '><div onclick="$(c_input_hour).val(' + i + ');"><table><tr><td><font style="font-size:10px;"><b>' + i + 'H</b></font></td></tr></table></div></td>';
                        setSelectOption(i);
                    }
                    if (i == 11) {
                        result += '</tr><tr>';
                    }
                }
                result += '</tr></table></div>';

                region_td.innerHTML = result;
                // window.scrollTo(0,document.body.scrollHeight);
            },
            error: function(xhr, status, error) {}
        });
    }

    function select_date(artist_id, yy, mm, dd) {
		location.href='?artist_id='+artist_id+'&yy=' + yy + '&mm=' + mm + '&dd=' + dd + '&payment_log_seq=<?=$r_payment_log_seq ?>';
        //var post_data = 'key=check_date&year=' + yy + '&month=' + mm + '&day=' + dd;
        //add_cart('date', post_data, 1, 0, '?ch=time&yy=' + yy + '&mm=' + mm + '&dd=' + dd);
    }

    function select_date2(artist_id, yy, mm, dd) {
        var post_data = 'key=check_date&ch=time&year=' + yy + '&month=' + mm + '&day=' + dd;
        add_cart('date', post_data, 1, 0);
    }

    function move_month() {
        var post_data = 'key=uncheck_date';
        add_cart('date', post_data, 0, 0);
    }

    function add_cart(key, post_data, reloadf, closef, href) {
        $.ajax({
            url: 'set_cart_session.php',
            data: post_data,
            type: 'POST',
            success: function(data) {
                // alert(post_data);
                // alert(data);
                // return false;
                if (data == "fail") {
                    $.MessageBox({
                        buttonDone: "확인",
                        message: "죄송합니다. 방금 다른 회원이 이 시간을 예약하였습니다."
                    }).done(function() {
                        location.reload();
                        //					opener.location.reload();
                    });
                    return;
                } else if (data == "warring") {
                    $.MessageBox({
                        buttonDone: "확인",
                        message: "선택하신 시간은 바로 뒷예약과 가까워 출장거리, 아티스트 스케줄 등의 이유로 예약이 취소될 수 있습니다."
                    }).done(function() {
                        if (reloadf) {
                            location.reload();
                        }
                        if (closef) {
                            //                		                opener.location.reload();
                            //window.close();
                            location.href = 'reservation.php?artist_name=' + artist_name;
                        }
                    });
                    return;
                }
                if (reloadf) {
                    if (!href || href === undefined) location.reload();
                    else location.href = href;
                }
                if (closef) {
                    //opener.location.reload();
                    //window.close();
                    location.href = 'reservation.php?artist_name=' + artist_name;
                }
            },
            error: function(xhr, status, error) {}
        });
    }
	function add_cart2(key, post_data, reloadf, closef)
	{
			$.ajax({
					url: '<?=$mainpage_directory ?>/update_payment_log_date.php',
					data: post_data,
					type: 'POST',
					success: function(data){
							if (data == "fail") {
									$.MessageBox({
											buttonDone      : "확인",
											message         : "죄송합니다. 방금 다른 회원이 이 시간을 예약하였습니다."
									}).done(function(){
											location.reload();
	//                                        opener.location.reload();
									});
									return;
							} else if (data == "warring") {
									$.MessageBox({
											buttonDone      : "확인",
											message         : "선택하신 시간은 바로 뒷예약과 가까워 출장거리, 아티스트 스케줄 등의 이유로 예약이 취소될 수 있습니다."
									}).done(function(){
											//if(reloadf) {
	  //                                              location.reload();
											//}else if (closef) {
		//                                            opener.location.reload();
		  //                                          window.close();
											//}else{
												location.href = 'manage_my_reservation.php';
											//}
									});
									return;
							} else {
									$.MessageBox({
											buttonDone      : "확인",
											message         : "변경되었습니다."
									}).done(function(){
											location.href = 'manage_my_reservation.php';
									});
							}
					},
					error : function(xhr, status, error) {
					}
			});
	}

	function setParentText(){
	//	var input_year = document.getElementById("c_input_year");
	//	var input_month = document.getElementById("c_input_month");
	//	var input_day = document.getElementById("c_input_day");
		var artist_id = '<?=$artist_id?>';
		var payment_log_seq = '<?=$r_payment_log_seq?>';
		/*
		var input_hour = document.getElementById("c_input_hour");
		if (!input_hour.options[input_hour.selectedIndex].value) {
			return false;
		}
		*/
		var input_hour = $(".time_cell.on").data("hour");
		var input_minute = $(".time_cell.on").data("minute");
		var input_worker = $(".time_cell.on").parent("div").data("artist-name");

		var check_year = '<?=$yy ?>';
		var check_month = '<?=$mm ?>';
		var check_day = '<?=$dd ?>';

		var post_data = 'key=date&year='+check_year+'&month='+zero_fill(check_month,2)+'&day='+zero_fill(check_day,2)+'&hour='+zero_fill(input_hour,2)+'&minute='+zero_fill(input_minute,2)+'&worker='+input_worker+'&artist_id='+artist_id+'&payment_log_seq='+payment_log_seq;
		//console.log(post_data);
		add_cart2('date', post_data, 1, 1);	
	}

    function setParentTextNew() {
        var artist_id = '<?= $artist_id ?>';
        var input_hour = document.getElementById("c_input_hour");
        var hour = $(document).find('#time_table').find('tr.on').attr('data-hour');
        var minute = $(document).find('#time_table').find('tr.on').attr('data-min');
        var rowspan = $(document).find('#time_table').find('tr.on').find('td').eq(1).attr('rowspan');

        // alert(rowspan);

        var check_year = '<?= $yy ?>';
        var check_month = '<?= $mm ?>';
        var check_day = '<?= $dd ?>';

        // var result = check_year+'년 '+zero_fill(check_month,2)+'월 '+zero_fill(check_day,2)+'일 '+zero_fill(input_hour.options[input_hour.selectedIndex].value, 2)+"시";
        //opener.document.getElementById("reservation_date").value = result;
        if (!hour || hour == undefined || !minute || minute == undefined) {
            alert('예약하실 시간을 선택하세요.');
            return false;
        } else {
            var post_data = 'key=date&artist_id=' + artist_id + '&year=' + check_year + '&month=' + zero_fill(check_month, 2) + '&day=' + zero_fill(check_day, 2) + '&hour=' + hour + '&minute=' + minute + '&rowspan=' + rowspan;
        }
        // alert(post_data);
        add_cart('date', post_data, 1, 1);
    }

    function setParentText_() {
        //	var input_year = document.getElementById("c_input_year");
        //	var input_month = document.getElementById("c_input_month");
        //	var input_day = document.getElementById("c_input_day");
        var artist_id = '<?= $artist_id ?>';
        var input_hour = document.getElementById("c_input_hour");

        var check_year = '<?= $_SESSION['gobeauty_cart_year'] ?>';
        var check_month = '<?= $_SESSION['gobeauty_cart_month'] ?>';
        var check_day = '<?= $_SESSION['gobeauty_cart_day'] ?>';

        // var result = check_year+'년 '+zero_fill(check_month,2)+'월 '+zero_fill(check_day,2)+'일 '+zero_fill(input_hour.options[input_hour.selectedIndex].value, 2)+"시";
        //opener.document.getElementById("reservation_date").value = result;
        var post_data = 'key=date&year=' + check_year + '&month=' + zero_fill(check_month, 2) + '&day=' + zero_fill(check_day, 2) + '&hour=' + zero_fill(input_hour.value, 2);
        add_cart('date', post_data, 1, 1);
    }

    function clearSelectOption() {
        var select = document.getElementById('c_input_hour');
        select.options.length = 0;
    }

    function setSelectOption(hour) {
        var select = document.getElementById('c_input_hour');
        select.options.add(new Option(hour, hour))
    }

    var check_year = '<?= $_SESSION['gobeauty_cart_year'] ?>';
    var check_month = '<?= $_SESSION['gobeauty_cart_month'] ?>';
    var check_day = '<?= $_SESSION['gobeauty_cart_day'] ?>';
    var artist_id = '<?= $artist_id ?>';

    if (check_year > '0' && check_month > '0' && check_day > '0') {
        get_hour_schedule(artist_id, check_year, check_month, check_day);
    }

    //hour_details.style.visibility = "hidden";
</script>
<br>
<div id="notice">
    <b style="font-family: 'NanumGothic';font-weight: bold;">당일 예약은 제공하지 않습니다</b>
</div>
<?php include "../include/bottom.php"; ?>