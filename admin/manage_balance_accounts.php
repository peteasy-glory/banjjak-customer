<?php
include "../include/top.php";
include "../include/Crypto.class.php";

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

    <div class="header-back-btn"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/back.png" width="35px"></a></div>
    <div style="width:100%;height:19px;text-align:center;font-size:18px;font-weight:bold;">
        <p>정산(PC)</p>
    </div>

    <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">

    <form id="go_form" action="manage_balance_accounts.php" method="POST">
        <table border="1" style="font-size:14px;border:1px double #000000;border-collapse:collapse;">
            <tr style="font-weight:bold;text-align:center;">
                <td>정산차수</td>
                <td>샵</td>
            </tr>
            <tr>
                <td align="center">
                    <?php
                        $now_year = date('Y');
                        $now_month = date('m');
                        $html_select_option = '';
                        for ($y_i = 2018; $y_i <= intval(date('Y')); $y_i++) {
                            $round = 1;
                            for ($m_i = 1; $m_i <= 12; $m_i++) {
                                if ($now_year == $y_i && $now_month < $m_i) {
                                    break;
                                }
                                $index = 1;
                                for ($ch_i = 1; $ch_i <= 30; $ch_i += 10) {
                                    // $first_i = $ch_i;
                                    $now_day = intval(date('d'));
                                    // $now_day = 11;
                                    if ($now_year == $y_i && $now_month == $m_i && $now_day < $ch_i + 10) {
                                        break;
                                    } // [patch] 2019.01.04 | developlay - 마지막 일자 기준 차수노출 재정의
                                    // if ( $index >= 2 ) { break; }
                                    // if ( $now_day < $ch_i ) { break; }
                                    $html_select_option[$y_i . '_' . $round . '_' . $m_i . '_' . $index] = $y_i . "년 " . $m_i . '월 ' . $index . '차 (' . $ch_i . '일 ~ ' . ($index < 3 ? $ch_i + 9 : '말') . '일)';
                                    $round++;
                                    $index++;
                                }
                            }
                        }
                        // echo '<pre>';
                        // var_dump($html_select_option);
                        // echo '</pre>';
                        ?>
                    <select name="round" id="round">
                        <?php foreach ((array) $html_select_option as $key => $val) {
                                $selected = ($_REQUEST['round'] && $key == $_REQUEST['round']) ? "selected" : "";
                                ?>
                            <option value="<?= $key ?>" <?= $selected ?>><?= $val ?></option>
                        <?php } ?>
                    </select> 차
                </td>
                <td align="center">

                    <select name="artist_id" id="artist_id">
                        <?php
                            $shop_sql = "select * from tb_shop where open_flag = 1 order by name asc;";
                            $shop_result = mysql_query($shop_sql);
                            for ($index = 1; $shop_rows = mysql_fetch_object($shop_result); $index++) {
                                echo "<option value='" . $shop_rows->customer_id . "'";
                                if (trim($shop_rows->customer_id) == trim($_REQUEST['artist_id'])) {
                                    echo " selected";
                                }
                                echo ">$shop_rows->name($shop_rows->customer_id)</option>";
                            }
                            ?>
                    </select>
                </td>
            </tr>
        </table>
    </form>

    <script>
        var n_round = document.getElementById('round');
        var n_artist_id = document.getElementById('artist_id');

        var round = '<?= $_REQUEST['round'] ?>';
        var artist_id = '<?= $_REQUEST['artist_id'] ?>';
        if (!round || !artist_id) {
            document.getElementById('go_form').submit();
        } else {
            n_round.addEventListener('change', function(e) {
                document.getElementById('go_form').submit();
            });
            n_artist_id.addEventListener('change', function(e) {
                document.getElementById('go_form').submit();
            });
        }
    </script>

    <?php
        if ($_REQUEST['round'] && $_REQUEST['artist_id']) {
            $customer_id = "";
            $id = "";
            $make_date = "";
            $bank = "";
            $artist_info = "";
            $default_balance_accounts = "";
            $total_price = "";
            $total_private_tax = "";
            $total_platform_tax = "";
            $total_vat_tax = "";
            $total_result = "";
            $cancel_history = "";
            $plus_option = "";
            $minus_option = "";
            $final_result = "";
            $update_time = "";

            // $y_i."_".$round."_".$m_i."_".$index
            $select_ac_sql = "select * from tb_balance_accounts where customer_id = '" . $_REQUEST['artist_id'] . "' and id = '" . $_REQUEST['round'] . "';";
            $select_ac_result = mysql_query($select_ac_sql);
            if ($select_ac_rows = mysql_fetch_object($select_ac_result)) {
                $customer_id = $select_ac_rows->customer_id;
                $id = $select_ac_rows->id;
                $make_date = $select_ac_rows->make_date;
                $bank = $select_ac_rows->bank;
                $artist_info = $select_ac_rows->artist_info;
                $default_balance_accounts = $select_ac_rows->default_balance_accounts;
                $total_price = $select_ac_rows->total_price;
                $total_private_tax = $select_ac_rows->total_private_tax;
                $total_platform_tax = $select_ac_rows->total_platform_tax;
                $total_vat_tax = $select_ac_rows->total_vat_tax;
                $total_result = $select_ac_rows->total_result;
                $cancel_history = $select_ac_rows->cancel_history;
                $plus_option = $select_ac_rows->plus_option;
                $minus_option = $select_ac_rows->minus_option;
                $final_result = $select_ac_rows->final_result;
                $update_time = $select_ac_rows->update_time;
                ?>

            <br>
            <a href="manage_make_balance_accounts.php?id=<?= $_REQUEST['round'] ?>&artist_id=<?= $_REQUEST['artist_id'] ?>" class="gobeauty_small_button">재정산</a>
            <br>
        <?php
                } else {
                    ?>
            <br><br>
            * 정산정보가 없습니다.<br>
            * 아래 버튼을 눌러서 정산정보를 생성해주세요.<br><br>
            <a href="manage_make_balance_accounts.php?id=<?= $_REQUEST['round'] ?>&artist_id=<?= $_REQUEST['artist_id'] ?>" class="gobeauty_small_button">정산하기</a>
        <?php
                    return;
                }

                $round_info = explode("_", $id);
                $a_yy = $round_info[0];
                $a_round = $round_info[1];
                $a_mm = $round_info[2];
                $a_index = $round_info[3];
                ?>
        <br>
        <table border="1" style="font-size:14px;border:1px double #000000;border-collapse:collapse;">
            <tr style="font-weight:bold;text-align:center;">
                <td>정산계좌정보</td>
                <td>개인/사업자</td>
            </tr>
            <tr>
                <td><?= $bank ?></td>
                <td><?= $artist_info ?></td>
            </tr>
        </table>

        <br>
        <table border="1" style="text-align:center;font-size:14px;border:1px double #000000;border-collapse:collapse;">
            <tr style="font-weight:bold;text-align:center;">
                <td rowspan="2">판매일</td>
                <td rowspan="2">판매액</td>
                <td colspan="3">정산차감액</td>
                <td rowspan="2">이번차수 정산액</td>
            </tr>
            <tr>
                <td>개인원천징수</td>
                <td>카드수수료</td>
                <td>수수료VAT</td>
            </tr>
            <?php
                    $dba_array = explode("|", $default_balance_accounts);
                    for ($dba_i = 0; $dba_i < sizeof($dba_array); $dba_i++) {
                        $dba_value = explode(",", $dba_array[$dba_i]);
                        ?>
                <tr>
                    <td><?= $dba_value[1] ?></td>
                    <td align="right"><?= number_format($dba_value[2]) ?></td>
                    <td align="right"><?= number_format($dba_value[3]) ?></td>
                    <td align="right"><?= number_format($dba_value[4]) ?></td>
                    <td align="right"><?= number_format($dba_value[5]) ?></td>
                    <td align="right"><?= number_format($dba_value[6]) ?></td>
                </tr>
            <?php
                    }
                    ?>
            <tr>
                <td>합계</td>
                <td align="right"><?= number_format($total_price) ?></td>
                <td align="right"><?= number_format($total_private_tax) ?></td>
                <td align="right"><?= number_format($total_platform_tax) ?></td>
                <td align="right"><?= number_format($total_vat_tax) ?></td>
                <td align="right"><?= number_format($total_result) ?></td>
            </tr>
        </table>

        <br>
        정산후 예약취소건 차감
        <table border="1" style="text-align:center;font-size:14px;border:1px double #000000;border-collapse:collapse;">
            <tr style="font-weight:bold;text-align:center;">
                <td>판매일</td>
                <td>정산일</td>
                <td>예약취소일</td>
                <td>정산금액</td>
            </tr>
            <?php
                    $cencel_total_price = 0;
                    $cancel_array = explode("|", $cancel_history);
                    for ($dba_i = 0; $dba_i < sizeof($cancel_array); $dba_i++) {
                        $cancel_value = explode(",", $cancel_array[$dba_i]);
                        ?>
                <tr>
                    <td><?= $cancel_value[1] ?></td>
                    <td><?= $cancel_value[3] ?></td>
                    <td><?= $cancel_value[2] ?></td>
                    <td><?= $cancel_value[4] ?></td>
                </tr>
            <?php
                        $cencel_total_price = $cencel_total_price + intval($cancel_value[4]);
                    }
                    ?>
            <tr>
                <td colspan="3">합계</td>
                <td align="right"><?= number_format($cencel_total_price) ?></td>
            </tr>
        </table>
        <br>
        차감 후 정산액
        <table border="1" style="text-align:center;font-size:14px;border:1px double #000000;border-collapse:collapse;">
            <tr style="font-weight:bold;text-align:center;">
                <td><?= number_format($total_result - $cencel_total_price) ?></td>
            </tr>
        </table>

        <br>

        정산 조정 (+)
        <table border="1" style="text-align:center;font-size:14px;border:1px double #000000;border-collapse:collapse;">
            <tr style="font-weight:bold;text-align:center;">
                <td>내용</td>
                <td>가격</td>
                <td>삭제</td>
            </tr>
            <?php
                    $plus_total_price = 0;
                    $plus_array = explode("|", $plus_option);
                    for ($dba_i = 0; $dba_i < sizeof($plus_array); $dba_i++) {
                        $plus_value = explode(",", $plus_array[$dba_i]);
                        ?>
                <tr>
                    <td><?= $plus_value[0] ?></td>
                    <td><?= $plus_value[1] ?></td>
                    <td><a href="manage_make_balance_accounts_process.php?type=delplus&round=<?= $_REQUEST['round'] ?>&artist_id=<?= $_REQUEST['artist_id'] ?>&index=<?= ($dba_i + 1) ?>">X</a></td>
                </tr>
            <?php
                        $plus_total_price = $plus_total_price + intval($plus_value[1]);
                    }
                    ?>
            <tr>
                <td>합계</td>
                <td align="right"><?= number_format($plus_total_price) ?></td>
            </tr>
        </table>

        <br>
        정산 조정 (-)
        <table border="1" style="text-align:center;font-size:14px;border:1px double #000000;border-collapse:collapse;">
            <tr style="font-weight:bold;text-align:center;">
                <td>내용</td>
                <td>가격</td>
                <td>삭제</td>
            </tr>
            <?php
                    $minus_total_price = 0;
                    $minus_array = explode("|", $minus_option);
                    for ($dba_i = 0; $dba_i < sizeof($minus_array); $dba_i++) {
                        $minus_value = explode(",", $minus_array[$dba_i]);
                        ?>
                <tr>
                    <td><?= $minus_value[0] ?></td>
                    <td><?= $minus_value[1] ?></td>
                    <td><a href="manage_make_balance_accounts_process.php?type=delminus&round=<?= $_REQUEST['round'] ?>&artist_id=<?= $_REQUEST['artist_id'] ?>&index=<?= ($dba_i + 1) ?>">X</a></td>
                </tr>
            <?php
                        $minus_total_price = $minus_total_price + intval($minus_value[1]);
                    }
                    ?>
            <tr>
                <td>합계</td>
                <td align="right"><?= number_format($minus_total_price) ?></td>
            </tr>
        </table>

        <br>
        조정 후 정산액
        <table border="1" style="text-align:center;font-size:14px;border:1px double #000000;border-collapse:collapse;">
            <tr style="font-weight:bold;text-align:center;">
                <td><?= number_format($total_result - $cencel_total_price + $plus_total_price - $minus_total_price) ?></td>
            </tr>
        </table>

        <br>

        <form action="manage_make_balance_accounts_process.php" method="POST">
            <input type="hidden" name="type" value="plus">
            <input type="hidden" name="round" value="<?= $_REQUEST['round'] ?>">
            <input type="hidden" name="artist_id" value="<?= $_REQUEST['artist_id'] ?>">
            <table border="1" style="text-align:center;font-size:14px;border:1px double #000000;border-collapse:collapse;">
                <tr style="font-weight:bold;text-align:center;">
                    <td colspan="3">정산조정(+)</td>
                </tr>
                <tr>
                    <td><input type="text" name="name" placeholder="조정명"></td>
                    <td><input type="text" name="price" placeholder="가격, 숫자만"></td>
                    <td><input type="submit" value="+ 적용"></td>
                </tr>
            </table>
        </form>

        <br>

        <form action="manage_make_balance_accounts_process.php" method="POST">
            <input type="hidden" name="type" value="minus">
            <input type="hidden" name="round" value="<?= $_REQUEST['round'] ?>">
            <input type="hidden" name="artist_id" value="<?= $_REQUEST['artist_id'] ?>">
            <table border="1" style="text-align:center;font-size:14px;border:1px double #000000;border-collapse:collapse;">
                <tr style="font-weight:bold;text-align:center;">
                    <td colspan="3">정산조정(-)</td>
                </tr>
                <tr>
                    <td><input type="text" name="name" placeholder="조정명"></td>
                    <td><input type="text" name="price" placeholder="가격, 숫자만"></td>
                    <td><input type="submit" value="- 적용"></td>
                </tr>
            </table>
        </form>
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