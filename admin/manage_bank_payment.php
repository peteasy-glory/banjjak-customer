<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login.php");
include($_SERVER['DOCUMENT_ROOT']."/include/skin/header.php");


$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

$seq = $_REQUEST['seq'];
if ($seq == null || $seq == "") {
    $seq = 1;
}

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

<?php
$login_insert_sql = "select * from tb_customer where id = '" . $user_id . "' and (admin_flag = true or operator_flag = true)";
$result = mysqli_query($connection,$login_insert_sql);

if ($result_datas = mysqli_fetch_object($result)) {
    ?>

    <div class="header-back-btn"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/back.png" width="35px"></a></div>
    <div style="width:100%;height:19px;text-align:center;font-size:18px;font-weight:bold;">
        <p>계좌이체 결제 관리</p>
    </div>

    <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">

    <table width="100%" style="color:#999999;font-size:15px;text-align:center;">
        <tr>
            <td>
                <a href="?seq=1" <?php
                                        if ($seq == 1) {
                                            echo " style='color:#000000;font-weight:bold;font-size:16px;padding:10px;border-bottom:3px solid #F69ECE;' ";
                                        } else {
                                            echo " style='color:#999999;font-weight:bold;font-size:16px;padding:10px;border-bottom:0px solid #F69ECE;' ";
                                        }
                                        ?>>
                    입금대기
                </a>
            </td>
            <td>
                <a href="?seq=2" <?php
                                        if ($seq == 2) {
                                            echo " style='color:#000000;font-weight:bold;font-size:16px;padding:10px;border-bottom:3px solid #F69ECE;' ";
                                        } else {
                                            echo " style='color:#999999;font-weight:bold;font-size:16px;padding:10px;border-bottom:0px solid #F69ECE;' ";
                                        }
                                        ?>>
                    입금완료
                </a>
            </td>
            <td>
                <a href="?seq=3" <?php
                                        if ($seq == 3) {
                                            echo " style='color:#000000;font-weight:bold;font-size:16px;padding:10px;border-bottom:3px solid #F69ECE;' ";
                                        } else {
                                            echo " style='color:#999999;font-weight:bold;font-size:16px;padding:10px;border-bottom:0px solid #F69ECE;' ";
                                        }
                                        ?>>
                    미입금자동취소/예약취소
                </a>
            </td>
        </tr>
    </table>
    <br>

    <table width="100%" border="1" style="border:3px double #000000;border-collapse:collapse;">
        <tr>
            <td>결제시간</td>
            <td>입금만료</td>
            <td>입금정보</td>
            <td>결제자/<br>아티스트</td>
            <td>예약정보</td>
            <?php
                if ($seq == 1) {
                    ?>
                <td>결제</td>
            <?php
                }
                ?>
        </tr>
        <?php
            $sql = "select * from tb_payment_log where is_cancel = 0 and status = 'BR' order by update_time asc;";
            if ($seq == 2) {
                $sql = "select * from tb_payment_log where is_cancel = 0 and pay_type = 'bank' and status = 'R1' order by update_time desc;";
            } else if ($seq == 3) {
                $sql = "select * from tb_payment_log where is_cancel = 1 and pay_type = 'bank' order by update_time desc;";
            }
            $result = mysqli_query($connection,$sql);
            while ($rows = mysqli_fetch_object($result)) {
                ?>
            <tr style="font-size:13px;">
                <td><?= $rows->update_time ?></td>
                <td><?= $rows->expire_time ?></td>
                <td>
                    <?php
                            echo $rows->bank . "<br>";
                            $total_price = $rows->total_price - $rows->spend_point;
                            echo number_format($total_price);
                            ?>
                </td>
                <td><?= $rows->customer_id ?>/<br><?= $rows->artist_id ?><br>
                    <?php
                            $s_sql = "select name, open_flag, enable_flag from tb_shop where customer_id = '" . $rows->artist_id . "';";
                            $s_result = mysqli_query($connection,$s_sql);
                            if ($s_rows = mysqli_fetch_object($s_result)) {
                                echo "(" . $s_rows->name . ")<br>";
                                /*                if ($s_rows->open_flag == 1) {
                        echo "<b>(오픈)</b>";
                } else {
                        echo "<b>(미오픈)</b>";
                }
                if ($s_rows->enable_flag == 0) {
                        echo "(감춰진 샵)";
                }
*/
                            }
                            ?>
                    <?php
                            $s_sql = "select name, open_flag, enable_flag from tb_shop where customer_id = '" . $rows->customer_id . "';";
                            $s_result = mysqli_query($connection,$s_sql);
                            if ($s_rows = mysqli_fetch_object($s_result)) {
                                echo $s_rows->name;
                            }
                            ?>
                </td>
                <td>
                    <?= $rows->year ?>년<?= $rows->month ?>월<?= $rows->day ?>일<?= $rows->hour ?>시~<?= ($rows->to_hour + 1) ?>시 <br>
                    <?php
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
                            ?>
                </td>
                <?php
                        if ($seq == 1) {
                            ?>
                    <td><a href="change_bank_payment.php?year=<?= $rows->year ?>&month=<?= $rows->month ?>&day=<?= $rows->day ?>&payment_log_seq=<?= $rows->payment_log_seq ?>&order_id=<?= $rows->order_id ?>&customer_id=<?= $rows->customer_id ?>&artist_id=<?= $rows->artist_id ?>&org=<?= ($rows->total_price - $rows->spend_point) ?>" class="gobeauty_small_button">결제완료</a></td>
                <?php
                        }
                        ?>
            </tr>
        <?php
            }
            ?>
    </table>
    </center>

    <br>
    <br>
    <br>
    <br>
    <br>

<?php } ?>