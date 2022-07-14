<?php include "../include/top.php"; ?>

<?php
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
?>

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

    .my_reservation {
        position: relative;
        z-index: 0;
        width: 100%;
        text-align: left;
        border: 1 solid #999999;
        margin: auto;
    }

    .request_1vs1 {
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #eea1fc), color-stop(1, #d441ee));
        background: -moz-linear-gradient(center top, #eea1fc 5%, #d441ee 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#eea1fc', endColorstr='#d441ee');
        background-color: #eea1fc;
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
        border: 1px solid #dd5df4;
        display: inline-block;
        color: #ffffff;
        font-family: Arial;
        font-size: 15px;
        font-weight: bold;
        font-style: normal;
        height: 28px;
        line-height: 28px;
        width: 120px;
        text-decoration: none;
        text-align: center;
    }

    .request_1vs1:hover {
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #d441ee), color-stop(1, #eea1fc));
        background: -moz-linear-gradient(center top, #d441ee 5%, #eea1fc 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#d441ee', endColorstr='#eea1fc');
        background-color: #d441ee;
    }

    .request_1vs1:active {
        position: relative;
        top: 1px;
    }
</style>

<?php
$login_insert_sql = "select * from tb_customer where id = '" . $user_id . "' and (admin_flag = true or operator_flag = true)";
$result = mysql_query($login_insert_sql);

if ($result_datas = mysql_fetch_object($result)) {
    ?>
    <div class="header-back-btn"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/back.png" width="35px"></a></div>
    <div style="width:100%;height:19px;text-align:center;font-size:18px;font-weight:bold;">
        <p>신고된 글 관리하기</p>
    </div>

    <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">

    <center>
        <table width="100%">
            <tr>
                <td align="center" width="50%">
                    <div style="padding:9px;width:70%;border-bottom:3px solid #b523ac;color:#b523ac;font-weight:bold;">신고내역</div>
                </td>
                <td align="right" width="50%">
                </td>
            </tr>
        </table><br>
        <script>
            function delete_report(review_seq, review_customer_id) {
                $.MessageBox({
                    buttonFail: "취소",
                    buttonDone: "확인",
                    message: "신고를 해제 하시겠습니까?"
                }).done(function() {
                    location.href = 'report_process.php?key=deleteReport&review_seq=' + review_seq + '&review_customer_id=' + review_customer_id;
                });
            }

            function delete_review(review_seq, review_customer_id) {
                $.MessageBox({
                    buttonFail: "취소",
                    buttonDone: "확인",
                    message: "원본글을 삭제하시겠습니까?"
                }).done(function() {
                    location.href = 'report_process.php?key=deleteReview&review_seq=' + review_seq + '&review_customer_id=' + review_customer_id;
                });
            }
        </script>
        <?php
            $login_insert_sql = "select *, turr.update_time as utime from tb_usage_reviews_report turr, tb_usage_reviews tur where turr.review_seq = tur.review_seq group by turr.update_time desc;";
            $result = mysql_query($login_insert_sql);
            for ($ch_index = 0; $result_datas = mysql_fetch_object($result); $ch_index++) {
                $review_customer_id = $result_datas->review_customer_id;
                $report_customer_id = $result_datas->report_customer_id;
                $review_seq = $result_datas->review_seq;
                $report = $result_datas->report;
                $review = $result_datas->review;
                $update_time = $result_datas->utime;
                ?>
            <div class="my_reservation">
                <table style="float:right;border:1px solid #999999;width:80%;padding:5px;font-size:14px;">
                    <tr>
                        <td>
                            <b><?= $update_time ?> (<?= $report_customer_id ?>)</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>신고내용 :</b> <?= str_replace("|", "<br>", $report) ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <hr style="color:#999999;width:100%;border:0;border:1px dotted #999999;">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>원본글 :</b> <?= $review ?> (<?= $review_customer_id ?>)
                        </td>
                    </tr>
                </table>
            </div><br>
            <div width="100%" style="height:10px"></div>
            <div class="my_reservation">
                <table style="padding:5px;border:1px solid #999999;width:80%;font-size:14px;">
                    <tr>
                        <td>
                            <a href="#" onclick="javascript:delete_review('<?= $review_seq ?>', '<?= $review_customer_id ?>');" class="request_1vs1">원본글 삭제하기</a>

                            <a href="#" onclick="javascript:delete_report('<?= $review_seq ?>', '<?= $review_customer_id ?>');" class="request_1vs1">신고 해제하기</a>

                        </td>
                    </tr>
                </table>
            </div>

            <div width="100%" style="height:10px"></div>
        <?php
            }

            if ($ch_index == 0) {
                ?>
            <br>
            <br>
            <img src="<?= $image_directory ?>/1vs1_1.png" width="20%" style="opacity:0.5;">
            <br>
            <br>
            <br>
            <font style="font-size:18px;">문의 내역이 없습니다.</font>
        <?php
            }

            closeDB();
            ?>
    </center>

    <?php include "../include/bottom.php"; ?>
<?php } ?>