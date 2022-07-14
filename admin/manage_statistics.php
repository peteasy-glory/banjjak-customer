<?php
include "../include/top.php";
include "../include/Region.class.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

$squence = $_REQUEST['seq'];
if (!$squence) {
    $squence = 1;
}

$login_insert_sql = "select * from tb_customer where id = '" . $user_id . "' and (admin_flag = true or operator_flag = true)";
$result = mysql_query($login_insert_sql);

if ($result_datas = mysql_fetch_object($result)) {
    ?>

    <script>
        function change_region(in_status, in_top, in_middle) {
            $.ajax({
                url: 'change_region_status.php',
                data: {
                    type: in_status,
                    top_region: in_top,
                    middle_region: in_middle
                },
                type: 'POST',
                success: function(data) {
                    location.reload();
                },
                error: function(xhr, status, error) {}
            });
        }
    </script>

    <div class="header-back-btn"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/back.png" width="35px"></a></div>
    <div style="width:100%;height:19px;text-align:center;font-size:18px;font-weight:bold;">
        <p>통계</p>
    </div>

    <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">

    <?php
        $sql = "select count(*) from tb_customer where is_android = 1;";
        $result = mysql_query($sql);
        $android_count = mysql_result($result, 0, 0);
        ?>
    <font style="font-weight:bold;text-align:center;">
        안드로이드 접속 사용자 수 : <?= $android_count ?><br>
        <br>

        <form action="manage_statistics.php" method="POST">
            <input type="hidden" name="seq" value="<?= $seq ?>" />
            <table width="100%">
                <tr>
                    <td align="center">


                        <select name="ph_start_year">
                            <?php
                                for ($index = 2018; $index <= date('Y'); $index++) {
                                    echo "<option value='$index'";
                                    if ($_REQUEST['ph_start_year'] && $index == intval($_REQUEST['ph_start_year'])) {
                                        echo " selected";
                                    }
                                    echo ">$index</option>";
                                }
                                ?>
                        </select> 년
                        <select name="ph_start_month">
                            <?php
                                for ($index = 1; $index < 13; $index++) {
                                    echo "<option value='$index'";
                                    if ($_REQUEST['ph_start_month'] && intval($_REQUEST['ph_start_month']) == $index) {
                                        echo "selected";
                                    }
                                    echo ">$index</option>";
                                }
                                ?>
                        </select> 월
                        <select name="ph_start_day">
                            <?php
                                for ($index = 1; $index < 32; $index++) {
                                    echo "<option value='$index'";
                                    if ($_REQUEST['ph_start_day'] && intval($_REQUEST['ph_start_day']) == $index) {
                                        echo "selected";
                                    }
                                    echo ">$index</option>";
                                }
                                ?>
                        </select> 일 부터
                        <br>
                        <br>
                        <select name="ph_end_year">
                            <?php
                                for ($index = 2018; $index <= date('Y'); $index++) {
                                    echo "<option value='$index'";
                                    if ($_REQUEST['ph_end_year'] && $index == intval($_REQUEST['ph_end_year'])) {
                                        echo " selected";
                                    }
                                    echo ">$index</option>";
                                }
                                ?>
                        </select> 년
                        <select name="ph_end_month">
                            <?php
                                for ($index = 1; $index < 13; $index++) {
                                    echo "<option value='$index'";
                                    if ($_REQUEST['ph_end_month'] && intval($_REQUEST['ph_end_month']) == $index) {
                                        echo "selected";
                                    }
                                    echo ">$index</option>";
                                }
                                ?>
                        </select> 월
                        <select name="ph_end_day">
                            <?php
                                for ($index = 1; $index < 32; $index++) {
                                    echo "<option value='$index'";
                                    if ($_REQUEST['ph_end_day'] && intval($_REQUEST['ph_end_day']) == $index) {
                                        echo "selected";
                                    }
                                    echo ">$index</option>";
                                }
                                ?>
                        </select> 일 까지


                    </td>
                </tr>
                <tr>
                    <td height="10px"></td>
                </tr>
                <tr>
                    <td>
                        <button class="go_login" type="submit">검 색</button>
                    </td>
                    </td>
            </table>
        </form>

        <?php
            if ($_REQUEST['ph_start_year'] && $_REQUEST['ph_start_month'] && $_REQUEST['ph_start_day']) {
                ?>
            <center>
                <table width="90%">
                    <tr style="font-weight:bold;text-align:center;font-size:15px;">
                        <td>일시</td>
                        <td>금액</td>
                    </tr>
                    <?php
                            $s_year = $_REQUEST['ph_start_year'];
                            $s_month = $_REQUEST['ph_start_month'];
                            $s_day = $_REQUEST['ph_start_day'];
                            $start_date = date('Y-m-d H:i:s', strtotime($s_year . "-" . $s_month . "-" . $s_day . " 00:00:00"));
                            $e_year = $_REQUEST['ph_end_year'];
                            $e_month = $_REQUEST['ph_end_month'];
                            $e_day = $_REQUEST['ph_end_day'];
                            $end_date = date('Y-m-d H:i:s', strtotime($e_year . "-" . $e_month . "-" . $e_day . " 23:59:59"));

                            $date_from = date('Y-m-d', strtotime($s_year . "-" . $s_month . "-" . $s_day . " 00:00:00"));
                            $date_to = date('Y-m-d', strtotime($e_year . "-" . $e_month . "-" . $e_day . " 00:00:00"));

                            $interval = strtotime($date_to) - strtotime($date_from);

                            $days = ceil($interval / (60 * 60 * 24));
                            if ($days < 0) {
                                ?>
                        <tr style="font-size:13px;">
                            <td colspan="2" align="center">
                                <br>
                                날짜가 잘못 입력되었습니다.
                            </td>
                        </tr>
                        <?php
                                } else {

                                    $all_count = 0;
                                    $all_price = 0;

                                    for ($next_day = 0; ($pro_day = strtotime("+" . $next_day . "days", strtotime($date_from))) <= strtotime($date_to); $next_day = $next_day + 1) {
                                        $a_pro_day = date('Y-m-d', $pro_day);
                                        $sql = "select sum(total_price) as total_price from tb_payment_log where is_cancel = 0 and DATE(update_time) BETWEEN '" . $a_pro_day . " 00:00:00' AND '" . $a_pro_day . " 23:59:59';";
                                        $result = mysql_query($sql);
                                        if ($rows = mysql_fetch_object($result)) {
                                            if ($rows->total_price > 0) {
                                                $all_count = $all_count + 1;
                                                $all_price = $all_price + $rows->total_price;
                                                ?>
                                    <tr style="font-size:13px;">
                                        <td align="left">
                                            <?= $a_pro_day ?>
                                        </td>
                                        <td align="right">
                                            <?= number_format($rows->total_price) ?>원
                                        </td>
                                    </tr>
                    <?php
                                        }
                                    }
                                }
                            }
                            ?>
                    <tr>
                        <td>
                            총 <?= $all_count ?>건, <?= number_format($all_price) ?>원
                        </td>
                    </tr>
                </table>
            </center>

        <?php
            }
            ?>


        <br>
        <br>
        <br>
        <br>
        <br>
    <?php
        include "../include/buttom.php";
    }
    ?>