<?php
include "../include/top.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

$login_insert_sql = "select * from tb_customer where id = '" . $user_id . "' and admin_flag = true";
$result = mysql_query($login_insert_sql);

if ($result_datas = mysql_fetch_object($result)) {
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
        <p>운영자 관리</p>
    </div>

    <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">

    <font style="font-size:16px;font-weight:bold;">관리자</font><br>
    <?php
        $sql = "select * from tb_customer where enable_flag = 1 and admin_flag = 1;";
        $result = mysql_query($sql);
        while ($rows = mysql_fetch_object($result)) {
            echo "<font style='font-size:14px;'>" . $rows->id . "</font><br>";
        }
        ?>
    <br>
    <font style="font-size:16px;font-weight:bold;">운영자</font><br>
    <?php
        $sql = "select * from tb_customer where enable_flag = 1 and operator_flag = 1;";
        $result = mysql_query($sql);
        while ($rows = mysql_fetch_object($result)) {
            echo "<font style='font-size:14px;'>" . $rows->id . " <a href='#' onclick=\"del_region('" . $rows->id . "', 1);\">X</a></font><br>";
        }
        ?>

    <input type="text" id="oper_name" name="oper_name" /> <a href="#" onclick="change_region(0);" class="gray_small_button"> 추가 </a>

    <script>
        function change_region(in_status) {
            var id = document.getElementById('oper_name').value;
            $.ajax({
                url: 'change_operator_status.php',
                data: {
                    type: in_status,
                    customer_id: id
                },
                type: 'POST',
                success: function(data) {
                    location.reload();
                },
                error: function(xhr, status, error) {}
            });
        }

        function del_region(id, in_status) {
            $.ajax({
                url: 'change_operator_status.php',
                data: {
                    type: in_status,
                    customer_id: id
                },
                type: 'POST',
                success: function(data) {
                    location.reload();
                },
                error: function(xhr, status, error) {}
            });
        }
    </script>

    <br><br>
    <?php
        $is_bank_account = 0;
        $is_request_artist = 0;
        $is_shop_open = 0;
        $is_1vs1 = 0;
        $is_report = 0;
        $is_change_user = 0;
        $is_fake_review = 0;
        $is_push_to_all = 0;
        $is_new_customer = 0;
        $is_user = 0;
        $is_region = 0;
        $is_notice = 0;
        $is_statistics = 0;
        $is_pet_shop = 0;
        $is_balance_accounts = 0;
        $is_payment_logs = 0;
        $is_point_logs = 0;
        $is_public_holiday = 0;
        $is_admin_user = 0;
        $is_point = 0;

        $sqqql = "select * from tb_operator_management;";
        $sqqq_result = mysql_query($sqqql);
        if ($sq_rows = mysql_fetch_object($sqqq_result)) {
            $is_bank_account = $sq_rows->is_bank_account;
            $is_request_artist = $sq_rows->is_request_artist;
            $is_shop_open = $sq_rows->is_shop_open;
            $is_1vs1 = $sq_rows->is_1vs1;
            $is_report = $sq_rows->is_report;
            $is_change_user = $sq_rows->is_change_user;
            $is_fake_review = $sq_rows->is_fake_review;
            $is_push_to_all = $sq_rows->is_push_to_all;
            $is_new_customer = $sq_rows->is_new_customer;
            $is_user = $sq_rows->is_user;
            $is_region = $sq_rows->is_region;
            $is_notice = $sq_rows->is_notice;
            $is_statistics = $sq_rows->is_statistics;
            $is_pet_shop = $sq_rows->is_pet_shop;
            $is_balance_accounts = $sq_rows->is_balance_accounts;
            $is_payment_logs = $sq_rows->is_payment_logs;
            $is_point_logs = $sq_rows->is_point_logs;
            $is_public_holiday = $sq_rows->is_public_holiday;
            $is_admin_user = $sq_rows->is_admin_user;
            $is_point = $sq_rows->is_point;
        }
        ?>
    <table width="100%" style="font-size:13px;">
        <tr>
            <td>계좌이체 결제 관리</td>
            <td>
                <input onclick="javascript:change_operation('<?= $is_bank_account ?>', 'is_bank_account');" type="checkbox" <?php
                                                                                                                                if ($is_bank_account) {
                                                                                                                                    echo "checked";
                                                                                                                                }
                                                                                                                                ?> />
            </td>
        </tr>
        <tr>
            <td>입점신청 관리</td>
            <td>
                <input onclick="javascript:change_operation('<?= $is_request_artist ?>', 'is_request_artist');" type="checkbox" <?php
                                                                                                                                    if ($is_request_artist) {
                                                                                                                                        echo "checked";
                                                                                                                                    }
                                                                                                                                    ?> />
            </td>
        </tr>
        <tr>
            <td>오픈신청 관리</td>
            <td>
                <input onclick="javascript:change_operation('<?= $is_shop_open ?>', 'is_shop_open');" type="checkbox" <?php
                                                                                                                            if ($is_shop_open) {
                                                                                                                                echo "checked";
                                                                                                                            }
                                                                                                                            ?> />
            </td>
        </tr>
        <tr>
            <td>1대1 답글달기</td>
            <td>
                <input onclick="javascript:change_operation('<?= $is_1vs1 ?>', 'is_1vs1');" type="checkbox" <?php
                                                                                                                if ($is_1vs1) {
                                                                                                                    echo "checked";
                                                                                                                }
                                                                                                                ?> />
            </td>
        </tr>
        <tr>
            <td>신고된 글 관리하기</td>
            <td>
                <input onclick="javascript:change_operation('<?= $is_report ?>', 'is_report');" type="checkbox" <?php
                                                                                                                    if ($is_report) {
                                                                                                                        echo "checked";
                                                                                                                    }
                                                                                                                    ?> />
            </td>
        </tr>
        <tr>
            <td>사용자전환</td>
            <td>
                <input onclick="javascript:change_operation('<?= $is_change_user ?>', 'is_change_user');" type="checkbox" <?php
                                                                                                                                if ($is_change_user) {
                                                                                                                                    echo "checked";
                                                                                                                                }
                                                                                                                                ?> />
            </td>
        </tr>
        <tr>
            <td>후기 작성하기</td>
            <td>
                <input onclick="javascript:change_operation('<?= $is_fake_review ?>', 'is_fake_review');" type="checkbox" <?php
                                                                                                                                if ($is_fake_review) {
                                                                                                                                    echo "checked";
                                                                                                                                }
                                                                                                                                ?> />
            </td>
        </tr>
        <tr>
            <td>푸시 메시지</td>
            <td>
                <input onclick="javascript:change_operation('<?= $is_push_to_all ?>', 'is_push_to_all');" type="checkbox" <?php
                                                                                                                                if ($is_push_to_all) {
                                                                                                                                    echo "checked";
                                                                                                                                }
                                                                                                                                ?> />
            </td>
        </tr>
        <tr>
            <td>임시 신규 사용자 추가</td>
            <td>
                <input onclick="javascript:change_operation('<?= $is_new_customer ?>', 'is_new_customer');" type="checkbox" <?php
                                                                                                                                if ($is_new_customer) {
                                                                                                                                    echo "checked";
                                                                                                                                }
                                                                                                                                ?> />
            </td>
        </tr>
        <tr>
            <td>가입자 정보</td>
            <td>
                <input onclick="javascript:change_operation('<?= $is_user ?>', 'is_user');" type="checkbox" <?php
                                                                                                                if ($is_user) {
                                                                                                                    echo "checked";
                                                                                                                }
                                                                                                                ?> />
            </td>
        </tr>
        <tr>
            <td>검색 영업지역 오픈관리</td>
            <td>
                <input onclick="javascript:change_operation('<?= $is_region ?>', 'is_region');" type="checkbox" <?php
                                                                                                                    if ($is_region) {
                                                                                                                        echo "checked";
                                                                                                                    }
                                                                                                                    ?> />
            </td>
        </tr>
        <tr>
            <td>펫샵 공지사항 관리</td>
            <td>
                <input onclick="javascript:change_operation('<?= $is_notice ?>', 'is_notice');" type="checkbox" <?php
                                                                                                                    if ($is_notice) {
                                                                                                                        echo "checked";
                                                                                                                    }
                                                                                                                    ?> />
            </td>
        </tr>
        <tr>
            <td>통계</td>
            <td>
                <input onclick="javascript:change_operation('<?= $is_statistics ?>', 'is_statistics');" type="checkbox" <?php
                                                                                                                            if ($is_statistics) {
                                                                                                                                echo "checked";
                                                                                                                            }
                                                                                                                            ?> />
            </td>
        </tr>
        <tr>
            <td>펫샵 오픈관리</td>
            <td>
                <input onclick="javascript:change_operation('<?= $is_pet_shop ?>', 'is_pet_shop');" type="checkbox" <?php
                                                                                                                        if ($is_pet_shop) {
                                                                                                                            echo "checked";
                                                                                                                        }
                                                                                                                        ?> />
            </td>
        </tr>
        <tr>
            <td>정산</td>
            <td>
                <input onclick="javascript:change_operation('<?= $is_balance_accounts ?>', 'is_balance_accounts');" type="checkbox" <?php
                                                                                                                                        if ($is_balance_accounts) {
                                                                                                                                            echo "checked";
                                                                                                                                        }
                                                                                                                                        ?> />
            </td>
        </tr>
        <tr>
            <td>구매정보 조회</td>
            <td>
                <input onclick="javascript:change_operation('<?= $is_payment_logs ?>', 'is_payment_logs');" type="checkbox" <?php
                                                                                                                                if ($is_payment_logs) {
                                                                                                                                    echo "checked";
                                                                                                                                }
                                                                                                                                ?> />
            </td>
        </tr>
        <tr>
            <td>포인트 사용내역 조회</td>
            <td>
                <input onclick="javascript:change_operation('<?= $is_point_logs ?>', 'is_point_logs');" type="checkbox" <?php
                                                                                                                            if ($is_point_logs) {
                                                                                                                                echo "checked";
                                                                                                                            }
                                                                                                                            ?> />
            </td>
        </tr>
        <tr>
            <td>공휴일 관리</td>
            <td>
                <input onclick="javascript:change_operation('<?= $is_public_holiday ?>', 'is_public_holiday');" type="checkbox" <?php
                                                                                                                                    if ($is_public_holiday) {
                                                                                                                                        echo "checked";
                                                                                                                                    }
                                                                                                                                    ?> />
            </td>
        </tr>
        <tr>
            <td>포인트 추가</td>
            <td>
                <input onclick="javascript:change_operation('<?= $is_point ?>', 'is_point');" type="checkbox" <?php
                                                                                                                    if ($is_point) {
                                                                                                                        echo "checked";
                                                                                                                    }
                                                                                                                    ?> />
            </td>
        </tr>
    </table>

    <script>
        function change_operation(in_status, id) {
            $.ajax({
                url: 'change_operator_menu.php',
                data: {
                    type: in_status,
                    name_id: id
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
    include "../include/buttom.php";
}
?>