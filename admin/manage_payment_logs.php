<?php
include "../include/top.php";
include "../include/Region.class.php";
include "../include/Crypto.class.php";

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
        <p>구매정보 조회(PC)</p>
    </div>

    <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">

    <form action="manage_payment_logs.php" method="POST">
        <table width="100%">
            <tr>
                <td align="center">


                    <select name="ph_start_year">
                        <?php
                            for ($index = date('Y'); $index >= 2018; $index--) {
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
                                if (($_REQUEST['ph_start_month'] && intval($_REQUEST['ph_start_month']) == $index) || (!$_REQUEST['ph_start_month'] && DATE("m") == $index)) {
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
                            for ($index = date('Y'); $index >= 2018; $index--) {
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
                                if (($_REQUEST['ph_end_month'] && intval($_REQUEST['ph_end_month']) == $index) || (!$_REQUEST['ph_end_month'] && DATE("m") == $index)) {
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
                                if (($_REQUEST['ph_end_day'] && intval($_REQUEST['ph_end_day']) == $index) || !$_REQUEST['ph_end_day'] && "31" == $index) {
                                    echo "selected";
                                }
                                echo ">$index</option>";
                            }
                            ?>
                    </select> 일 까지
                    고객ID <input type="text" name="ph_customer_id" value="<?= $_REQUEST['ph_customer_id'] ?>" />
                    펫샵ID <input type="text" name="ph_artist_id" value="<?= $_REQUEST['ph_artist_id'] ?>" /> <button type="submit">검 색</button> <br>

                </td>
            </tr>
            <tr>
                <td height="10px"></td>
            </tr>
        </table>
    </form>

    <?php
        if ($_REQUEST['ph_start_year'] && $_REQUEST['ph_start_month'] && $_REQUEST['ph_start_day']) {
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

            $payment_arr = array();
            $moneySum = 0;
            $allCount = 0;
            if ($days >= 0) {
                $sql = "select 
                *, IF(pay_type = 'card' OR pay_type = 'bank', (total_price - spend_point) + local_price, local_price) as sum
                from tb_payment_log where ";
                if ($_REQUEST['ph_customer_id']) {
                    $sql = $sql . " customer_id like '%" . $_REQUEST['ph_customer_id'] . "%' and ";
                }
                if ($_REQUEST['ph_artist_id']) {
                    $sql = $sql . " artist_id like '%" . $_REQUEST['ph_artist_id'] . "%' and ";
                }
                $sql = $sql . " DATE(buy_time) BETWEEN '" . $start_date . "' AND '" . $end_date . "' order by buy_time desc;";
                $result = mysql_query($sql);
                $allCount = mysql_num_rows($result);

                while ($rows = mysql_fetch_object($result)) {
                    $payment_arr[] = $rows;
                    $moneySum += $rows->sum;
                }

				$sql = "
					SELECT COUNT(*) AS cnt
					FROM (
						SELECT artist_id, COUNT(*) AS cnt
						FROM tb_payment_log
						WHERE 
				";
                $sql .= ($_REQUEST['ph_customer_id'])? " customer_id like '%" . $_REQUEST['ph_customer_id'] . "%' and " : "";
                $sql .= ($_REQUEST['ph_artist_id'])? " artist_id like '%" . $_REQUEST['ph_artist_id'] . "%' and " : "";
                $sql .= " DATE(buy_time) BETWEEN '" . $start_date . "' AND '" . $end_date . "' GROUP BY artist_id ) AS a ";
				$result = mysql_query($sql);
				$row = mysql_fetch_assoc($result);
				$artist_cnt = $row["cnt"];

				$sql = "
					SELECT COUNT(*) AS cnt
					FROM (
						SELECT customer_id, COUNT(*) AS cnt
						FROM tb_payment_log
						WHERE 
				";
                $sql .= ($_REQUEST['ph_customer_id'])? " customer_id like '%" . $_REQUEST['ph_customer_id'] . "%' and " : "";
                $sql .= ($_REQUEST['ph_artist_id'])? " artist_id like '%" . $_REQUEST['ph_artist_id'] . "%' and " : "";
                $sql .= " DATE(buy_time) BETWEEN '" . $start_date . "' AND '" . $end_date . "' AND customer_id <> '' GROUP BY customer_id ) AS a ";
				$result = mysql_query($sql);
				$row = mysql_fetch_assoc($result);
				$customer_cnt = $row["cnt"];

				$sql = "
					SELECT COUNT(*) AS cnt
					FROM (
						SELECT cellphone, COUNT(*) AS cnt
						FROM tb_payment_log
						WHERE 
				";
                $sql .= ($_REQUEST['ph_customer_id'])? " customer_id like '%" . $_REQUEST['ph_customer_id'] . "%' and " : "";
                $sql .= ($_REQUEST['ph_artist_id'])? " artist_id like '%" . $_REQUEST['ph_artist_id'] . "%' and " : "";
                $sql .= " DATE(buy_time) BETWEEN '" . $start_date . "' AND '" . $end_date . "' AND customer_id = '' GROUP BY cellphone ) AS a ";
				$result = mysql_query($sql);
				$row = mysql_fetch_assoc($result);
				$tmp_user_cnt = $row["cnt"];

				$sql = "
					SELECT pay_type, COUNT(*) AS cnt
					FROM tb_payment_log
					WHERE 
				";
                $sql .= ($_REQUEST['ph_customer_id'])? " customer_id like '%" . $_REQUEST['ph_customer_id'] . "%' and " : "";
                $sql .= ($_REQUEST['ph_artist_id'])? " artist_id like '%" . $_REQUEST['ph_artist_id'] . "%' and " : "";
                $sql .= " DATE(buy_time) BETWEEN '" . $start_date . "' AND '" . $end_date . "' AND pay_type NOT IN ('pos-card', 'pos-cash', '-cash', '-card', '') GROUP BY pay_type ";
				$result = mysql_query($sql);
				while($row = mysql_fetch_assoc($result)){
					if (startsWith($row["pay_type"], "offline")) {
						$pay_type_arr["offline"] += $row["cnt"];
					}else{
						$pay_type_arr[$row["pay_type"]] = $row["cnt"];
					}
				}
				$pay_type_cnt_txt = array(
					"card" => "카드",
					"bank" => "계좌이체",
					"offline" => "매장결제"
				);
				//print_r($pay_type_arr);
				foreach($pay_type_arr AS $key => $value){
					$pay_type_cnt .= $pay_type_cnt_txt[$key].":".$value."건,";
				}
				$pay_type_cnt = substr($pay_type_cnt, 0, -1);
			}
            ?>
        <center>
            <table width="100%" border="1" style="border:3px double #000000;border-collapse:collapse;">
                <tr>
                    <td colspan="15">
                        총 <?= $allCount ?>건, <?= number_format($moneySum) ?>원 | 펫샵 수 <?= $artist_cnt ?>명 | 정회원 수 <?= $customer_cnt ?>명 / 가회원 수 <?= $tmp_user_cnt ?>명 | 앱 결제 수 [<?= $pay_type_cnt ?>]
                    </td>
                </tr>
                <tr style="font-weight:bold;text-align:center;font-size:15px;border-bottom:3px double #000000;">
                    <td width="80px">구매일</td>
                    <td width="80px">변경일</td>
                    <td width="120px">고객</td>
                    <td width="120px">아티스트</td>
                    <td width="200px">상품</td>
                    <td width="150px">
                        <table width="150px">
                            <tr>
                                <td colspan="2">총가격</td>
                            </tr>
                        </table>
                    </td>
                    <td width="150px">
                        <table width="150px">
                            <tr>
                                <td colspan="2">포인트사용</td>
                            </tr>
                        </table>
                    </td>
                    <td width="150px">
                        <table width="150px">
                            <tr>
                                <td colspan="2">출장비</td>
                            </tr>
                        </table>
                    </td>
                    <td width="50px">VAT</td>
                    <td width="120px">출장정보</td>
                    <td width="100px">예약정보</td>
                    <td width="30px">결제정보</td>
                    <td width="40px">취소</td>
                    <td width="80px">취소일</td>

                </tr>
                <?php
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
                                foreach ($payment_arr as $key => $rows) {
                                    $all_count = $all_count + 1;
                                    $all_price = $all_price + $rows->total_price;
                                    ?>
                        <tr style="font-size:13px;">
                            <td><?= $rows->buy_time ?></td>
                            <td><?= $rows->update_time ?></td>
                            <td><?= $rows->customer_id ?></td>
                            <td><?= $rows->artist_id ?></td>
                            <td><?php
                                                $products = explode(",", $rows->product);
                                                for ($spiai = 0; $spiai < sizeof($products); $spiai = $spiai + 1) {
                                                    $service_infos = explode("|", $products[$spiai]);
                                                    if ($service_infos[1] == "개") {
                                                        ?>
                                        <?= $service_infos[0] ?>/<?= $service_infos[3] ?>/<?= explode(":", $service_infos[4])[0] ?>/<?= "~" . explode(":", $service_infos[5])[0] . "Kg" ?>
                                    <?php
                                                        } else {
                                                            ?>
                                        <?= $service_infos[0] ?>/<?= $service_infos[3] ?>/<?= explode(":", $service_infos[5])[0] ?>/<?= "~" . explode(":", $service_infos[4])[0] . "Kg" ?>
                                <?php
                                                    }
                                                    echo "<br>";
                                                }
                                                ?></td>
                            <td><?= number_format($rows->total_price) ?></td>
                            <td><?= number_format($rows->spend_point) ?></td>
                            <td><?= number_format($rows->per_diem) ?></td>
                            <td><?php if ($rows->is_vat) {
                                                    echo "부가세10%";
                                                } else {
                                                    echo "-";
                                                } ?></td>
                            <td><?php
                                                if ($rows->go_2_offline) {
                                                    echo "매장방문";
                                                } else {
                                                    echo str_replace("|", "<br>", $rows->address1);
                                                    echo $rows->address2 . "<br>";
                                                    echo $rows->cellphone;
                                                }
                                                ?></td>
                            <td><?= $rows->year ?>년<?= $rows->month ?>월<?= $rows->day ?>일<?= $rows->hour ?>시~<?= ($rows->to_hour) ?>시 </td>
                            <td>
                                <?php
                                                $pay_type_str = $rows->pay_type;
                                                if (startsWith($pay_type_str, "pos")) {
                                                    $pay_type_str = "pos";
                                                } else if (startsWith($pay_type_str, "offline")) {
                                                    $pay_type_str = "offline";
                                                }
                                                echo $pay_type_str;
                                                ?>
                            </td>
                            <td><?php if ($rows->is_cancel) {
                                                    echo "취소";
                                                } else {
                                                    echo "-";
                                                } ?></td>
                            <td><?= $rows->cancel_time ?></td>
                        </tr>
                        <tr style="font-size:13px;border-bottom:3px double #000000;">
                            <td colspan="14">내부 구매키값 : <?= $rows->payment_log_seq ?><br>PG (receipt_id) : <?= $rows->receipt_id ?><br><?= $rows->pg_log ?></td>
                        </tr>
                <?php
                            }
                        }
                        ?>
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