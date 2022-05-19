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
        z-index: 5;
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
        width: 88px;
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
        <p>1대1 문의 답글달기</p>
    </div>

    <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">

    <center>
        <table width="100%">
            <tr>
                <td align="center" width="50%">
                    <div style="padding:9px;width:70%;border-bottom:3px solid #b523ac;color:#b523ac;font-weight:bold;">문의내역</div>
                </td>
                <td align="right" width="50%">
                </td>
            </tr>
        </table><br>

        <?php
            $login_insert_sql = "select * from tb_1vs1_pna group by update_time desc;";
            $result = mysql_query($login_insert_sql);

            for ($ch_index = 0; $result_datas = mysql_fetch_object($result); $ch_index++) {
                $uuid = $result_datas->id;
                $customer_id = $result_datas->customer_id;
                $email = $result_datas->email;
                $title = $result_datas->title;
                $type = $result_datas->request_main_type;
                $body = $result_datas->body;
                $update_time = $result_datas->update_time;
                ?>
            <div class="my_reservation">
                <table style="float:right;border:1px solid #999999;width:80%;padding:5px;font-size:14px;">
                    <tr>
                        <td>
                            <b><?= $update_time ?> (<?= $type ?>)</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b><?= $customer_id ?></b><br>
                            <b><?= $title ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?= $body ?>
                        </td>
                    </tr>
                </table>
            </div><br>
            <div width="100%" style="height:10px"></div>

            <?php
                    $re_sql = "select * from tb_1vs1_pna_sub where qna_id = '" . $uuid . "';";
                    $re_result = mysql_query($re_sql);
                    if ($re_rows = mysql_fetch_object($re_result)) {
                        ?>
                <div class="my_reservation">
                    <table style="border:1px solid #999999;width:80%;padding:5px;font-size:14px;">
                        <tr>
                            <td>
                                <b><?= $re_rows->update_time ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <form action="save_1vs1_reply.php" id="re_shop_form<?= $ch_index ?>" method="POST">
                                    <input type="hidden" name="id" value="<?= $uuid ?>">
                                    <input type="hidden" name="customer_id" value="<?= $customer_id ?>">
                                    <textarea style="width:95%;height:50px;font-size:15px;" id="1vs1_reply" name="1vs1_reply" type="text" value="<?= $re_rows->body ?>" placeholder="답글을 작성해주세>요." required><?= $re_rows->body ?></textarea>
                                    <a href="#" onclick="document.getElementById('re_shop_form<?= $ch_index ?>').submit();" class="gobeauty_small_button">수정</a>
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
            <?php
                    } else {
                        ?>
                <div class="my_reservation">
                    <table style="padding:5px;border:1px solid #999999;width:80%;font-size:14px;">
                        <tr>
                            <td>
                                <form action="save_1vs1_reply.php" id="shop_form<?= $ch_index ?>" method="POST">
                                    <input type="hidden" name="customer_id" value="<?= $customer_id ?>">
                                    <input type="hidden" name="id" value="<?= $uuid ?>">
                                    <textarea style="width:95%;height:50px;font-size:15px;" id="1vs1_reply" name="1vs1_reply" type="text" value="<?= $self_introduction ?>" placeholder="답글을 작성해주세요." required><?= $self_introduction ?></textarea>
                                    <a href="#" onclick="document.getElementById('shop_form<?= $ch_index ?>').submit();" class="gobeauty_small_button">답글달기</a>
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
            <?php
                    }
                    ?>
            <div>
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