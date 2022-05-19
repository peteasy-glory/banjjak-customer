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

    $crypto = new Crypto();
    $seq = $_REQUEST['seq'];
    if ($seq == null || $seq == "") {
        $seq = 1;
    }
    ?>
    <style>
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
    </style>
    <div class="header-back-btn"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/back.png" width="35px"></a></div>
    <div style="width:100%;height:19px;text-align:center;font-size:18px;font-weight:bold;">
        <p>가입자 정보</p>
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
                    일반회원
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
                    펫샵회원
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
                    탈퇴회원
                </a>
            </td>
        </tr>
    </table>

    <br>

    <form action="manage_user.php" method="POST">
        <input type="hidden" name="seq" value="<?= $seq ?>" />
        <input type="text" name="search_id"> <button type="submit">검 색</button> <br>
    </form>
    <?php

        $sql = "select * from tb_customer;";
        if ($seq == 1) {
            $sql = "select * from tb_customer where my_shop_flag = 0 and enable_flag = 1";
            if ($_REQUEST['search_id']) {
                $sql = $sql . " and id like '%" . $_REQUEST['search_id'] . "%' order by registration_time desc;";
            } else {
                $sql = $sql . " order by registration_time desc;";
            }
        } else if ($seq == 2) {
            $sql = "select * from tb_customer where my_shop_flag = 1 and enable_flag = 1";
            if ($_REQUEST['search_id']) {
                $sql = $sql . " and id like '%" . $_REQUEST['search_id'] . "%' order by registration_time desc;";
            } else {
                $sql = $sql . " order by registration_time desc;";
            }
        } else {
            $sql = "select * from tb_customer where enable_flag = 0 ";
            if ($_REQUEST['search_id']) {
                $sql = $sql . " and id like '%" . $_REQUEST['search_id'] . "%' order by delete_time desc, registration_time desc;";
            } else {
                $sql = $sql . " order by delete_time desc, registration_time desc;";
            }
        }
        $result = mysql_query($sql);
        $result_count = mysql_num_rows($result);
        ?>
    총 : <?= $result_count ?> 명 <br>
    <table width="100%" border="1" style="font-size:12px;border:1px solid #999999;border-collapse:collapse;">
        <tr style="font-weight:bold;text-align:center;">
            <td>ID<br>닉네임
                <?php
                    if ($seq == 2) {
                        ?>
                    <br>펫샵
                <?php
                    }
                    ?>
            </td>
            <td>전화</td>
            <td>가입일</td>
            <?php
                if ($seq == 3) {
                    ?>
                <td>탈퇴일</td>
            <?php
                }
                ?>
            <?php
                if ($seq == 2) {
                    ?>
                <td>정산계좌</td>
            <?php
                }
                ?>
        </tr>
        <?php
            while ($rows = mysql_fetch_object($result)) {
                //$temp_id = $crypto->encode($rows->id, $access_key, $secret_key);
                ?>
            <tr>
                <td>
                    <?= $rows->id ?><br><?= $rows->nickname ?><br>
                    <?php
                            if ($seq == 2) {
                                $s_sql = "select name, open_flag, enable_flag from tb_shop where customer_id = '" . $rows->id . "';";
                                $s_result = mysql_query($s_sql);
                                if ($s_rows = mysql_fetch_object($s_result)) {
                                    echo $s_rows->name;
                                    if ($s_rows->open_flag == 1) {
                                        echo "<b>(오픈)</b>";
                                    } else {
                                        echo "<b>(미오픈)</b>";
                                    }
                                    if ($s_rows->enable_flag == 0) {
                                        echo "(감춰진 샵)";
                                    }
                                } else {
                                    echo "(Magic진행필요)";
                                }
                            }
                            ?>
                </td>
                <td style="text-align:center;">
                    <?php
                            $dec_cellphone = $crypto->decode($rows->cellphone, $access_key, $secret_key);
                            if ($dec_cellphone) {
                                echo "<a href='tel:" . $dec_cellphone . "'>" . $dec_cellphone . "</a>";
                            }
                            ?>
                    <?php
                            if ($seq == 2) {
                                $enc_customer_id = $crypto->encode($rows->id, $access_key, $secret_key);
                                $phone_sql = "select * from tb_request_artist where customer_id = '" . $enc_customer_id . "';";
                                $phone_result = mysql_query($phone_sql);
                                if ($phone_rows = mysql_fetch_object($phone_result)) {
                                    $phone_cellphone = $phone_rows->cellphone;
                                    $ddd_dec_cellphone = $crypto->decode($phone_cellphone, $access_key, $secret_key);
                                    echo "<br>입점신청:<a href='tel:" . $ddd_dec_cellphone . "'>" . $ddd_dec_cellphone . "</a>";
                                }
                            }
                            ?>
                </td>
                <td><?= $rows->registration_time ?></td>
                <?php
                        if ($seq == 3) {
                            ?>
                    <td><?= $rows->delete_time ?></td>
                <?php
                        }
                        ?>

                <?php
                        if ($seq == 2) {
                            ?>
                    <td>
                        <?php
                                    $bank_sql = "select * from tb_artist_payment_info where customer_id = '" . $rows->id . "';";
                                    $bank_result = mysql_query($bank_sql);
                                    if ($bank_datas = mysql_fetch_object($bank_result)) {
                                        $enc_bankname = $bank_datas->bankname;
                                        $enc_account_holder = $bank_datas->account_holder;
                                        $enc_bank_account = $bank_datas->bank_account;
                                        $bankname = $crypto->decode($enc_bankname, $access_key, $secret_key);
                                        $account_holder = $crypto->decode($enc_account_holder, $access_key, $secret_key);
                                        $bank_account = $crypto->decode($enc_bank_account, $access_key, $secret_key);
                                        ?>
                            <?= $bankname ?> <?= $bank_account ?> <?= $account_holder ?>
                        <?php
                                    }
                                    ?>
                    </td>
                <?php
                        }
                        ?>


            </tr>
        <?php
            }
            ?>
    </table>

<?php
    include "../include/buttom.php";
}
?>