<?php
include "../include/top.php";
include "../include/Region.class.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

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
        <p>포인트 사용내역 조회(PC)</p>
    </div>

    <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">

    <form action="manage_point_logs.php" method="POST">
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
                    고객ID <input type="text" name="ph_customer_id" value="<?= $_REQUEST['ph_customer_id'] ?>" />
                    <button type="submit">검 색</button> <br>

                </td>
            </tr>
            <tr>
                <td height="10px"></td>
            </tr>
        </table>
    </form>

    <?php
        if ($_REQUEST['ph_start_year'] && $_REQUEST['ph_start_month'] && $_REQUEST['ph_start_day']) {
            ?>
        <center>
            <table width="100%" border="1" style="border:3px double #000000;border-collapse:collapse;">
                <tr style="font-weight:bold;text-align:center;font-size:15px;border-bottom:3px double #000000;">
                    <td width="80px">날짜</td>
                    <td width="80px">ID</td>
                    <td width="80px">종류</td>
                    <td width="50px">추가된 포인트</td>
                    <td width="50px">차감된 포인트</td>
                    <td width="50px">차감된 적립포인트</td>
                    <td width="50px">차감된 구매포인트</td>
                    <td width="60px">적립포인트잔액</td>
                    <td width="60px">구매포인트잔액</td>
                    <td width="100px">구매키값</td>
                    <td width="250px">이벤트명</td>

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
                        <td colspan="20" align="center">
                            <br>
                            날짜가 잘못 입력되었습니다.
                        </td>
                    </tr>
                    <?php
                            } else {

                                $all_count = 0;

                                $sql = "select * from tb_point_history where ";
                                if ($_REQUEST['ph_customer_id']) {
                                    $sql = $sql . " customer_id like '%" . $_REQUEST['ph_customer_id'] . "%' and ";
                                }
                                $sql = $sql . " DATE(update_time) BETWEEN '" . $start_date . "' AND '" . $end_date . "';";
                                $result = mysql_query($sql);
                                while ($rows = mysql_fetch_object($result)) {
                                    $all_count = $all_count + 1;
                                    ?>
                        <tr style="font-size:13px;">
                            <td><?= $rows->update_time ?></td>
                            <td><?= $rows->customer_id ?></td>
                            <td>
                                <?php
                                                if ($rows->type == "ACCUMLATE") {
                                                    echo "적립";
                                                } else if ($rows->type == "SPEND") {
                                                    echo "사용";
                                                } else if ($rows->type == "EVENT") {
                                                    echo "이벤트적립";
                                                } else if ($rows->type == "CANCEL") {
                                                    echo "예약취소<br>적립차감";
                                                } else if ($rows->type == "BUY") {
                                                    echo "포인트구매적립";
                                                } else {
                                                    echo $rows->type;
                                                }
                                                ?>
                            </td>
                            <td><?= $rows->adding_point ?></td>
                            <td><?= $rows->spending_point ?></td>
                            <td><?= $rows->spending_accumulate_point ?></td>
                            <td><?= $rows->spending_purchase_point ?></td>
                            <td><?= $rows->accumulate_point ?></td>
                            <td><?= $rows->purchase_point ?></td>
                            <td><?= $rows->payment_log_seq ?></td>
                            <td><?= $rows->event_name ?></td>
                        </tr>
                <?php
                            }
                        }
                        ?>
                <tr>
                    <td colspan="2">
                        총 <?= $all_count ?>건
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