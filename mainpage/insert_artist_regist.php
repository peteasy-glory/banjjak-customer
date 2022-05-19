<?php

include "../include/top.php";
include "../include/Crypto.class.php";

$user_id = $_SESSION['gobeauty_user_id'];

$step = $_REQUEST["step"];

$crypto = new Crypto();
$user_id = $crypto->encode(trim($user_id), $access_key, $secret_key);

if ($step == "1") {
    $name = $_REQUEST["artist_real_name"];
    $cellphone = $_REQUEST["artist_real_cellphone"];
    $name = $crypto->encode(trim($name), $access_key, $secret_key);
    $cellphone = $crypto->encode(trim($cellphone), $access_key, $secret_key);

    $sql = "select * from tb_request_artist where customer_id = '" . $user_id . "';";
    $result = mysql_query($sql);
    if ($result_datas = mysql_fetch_object($result)) {
        $update_sql = "update tb_request_artist set name = '" . $name . "', cellphone = '" . $cellphone . "', update_time = now(), step = 1, is_business = false, is_personal = true where customer_id = '" . $user_id . "';";
        $update_result = mysql_query($update_sql);
        if ($update_result) {
            ?>
            <script language="javascript">
                location.href = "request_artist_region.php";
            </script>
        <?php
        } else {
            ?>
            <script language="javascript">
                $.MessageBox({
                    buttonDone: "확인",
                    message: "제출실패 재시도해주세요."
                }).done(function() {
                    location.href = "view_event2.php";
                });
            </script>
        <?php
        }
    } else {
        $insert_sql = "insert into tb_request_artist (customer_id, cellphone, step, name, update_time, is_business, is_personal) values ('" . $user_id . "', '" . $cellphone . "', 1, '" . $name . "', now(), false, true);";
        $insert_result = mysql_query($insert_sql);
        if ($insert_result) {
            ?>
            <script language="javascript">
                location.href = "request_artist_region.php";
            </script>
        <?php
        } else {
            ?>
            <script language="javascript">
                $.MessageBox({
                    buttonDone: "확인",
                    message: "제출실패 재시도해주세요."
                }).done(function() {
                    location.href = "view_event2.php";
                });
            </script>
        <?php
        }
    }
}
        if ($step == "1-1") {
            $name = $_REQUEST["artist_real_name"];
            $sa_num1 = $_REQUEST["sa_num1"];
            $sa_num2 = $_REQUEST["sa_num2"];
            $sa_num3 = $_REQUEST["sa_num3"];
            $sa_num = $sa_num1 . $sa_num2 . $sa_num3;
            $name = $crypto->encode(trim($name), $access_key, $secret_key);
            $sa_num = $crypto->encode(trim($sa_num), $access_key, $secret_key);

            $sql = "select * from tb_request_artist where customer_id = '" . $user_id . "';";
            $result = mysql_query($sql);
            if ($result_datas = mysql_fetch_object($result)) {
                $update_sql = "update tb_request_artist set is_business = true, is_personal = false, name = '" . $name . "', update_time = now(), step = 1, business_number = '" . $sa_num . "' where customer_id = '" . $user_id . "';";
                $update_result = mysql_query($update_sql);
                if ($update_result) {
                    ?>
            <script language="javascript">
                location.href = "request_artist_region.php";
            </script>
        <?php
                } else {
                    ?>
            <script language="javascript">
                $.MessageBox({
                    buttonDone: "확인",
                    message: "제출실패 재시도해주세요."
                }).done(function() {
                    location.href = "view_event2.php";
                });
            </script>
        <?php
                }
            } else {
                $insert_sql = "insert into tb_request_artist (customer_id, step, name, update_time, is_business, is_personal, business_number) values ('" . $user_id . "', 1, '" . $name . "', now(), true, false, '" . $sa_num . "');";
                $insert_result = mysql_query($insert_sql);
                if ($insert_result) {
                    ?>
            <script language="javascript">
                location.href = "request_artist_region.php";
            </script>
        <?php
                } else {
                    ?>
            <script language="javascript">
                $.MessageBox({
                    buttonDone: "확인",
                    message: "제출실패 재시도해주세요."
                }).done(function() {
                    location.href = "view_event2.php";
                });
            </script>
        <?php
                }
            }
        } else if ($step == "2") {
            $top_region = $_REQUEST["top_region"];
            $middle_region = $_REQUEST["middle_region"];
            $region = $top_region . ":" . $middle_region;
            $region = $crypto->encode(trim($region), $access_key, $secret_key);

            $sql = "select * from tb_request_artist where customer_id = '" . $user_id . "';";
            $result = mysql_query($sql);
            if ($result_datas = mysql_fetch_object($result)) {
                $update_sql = "update tb_request_artist set region = '" . $region . "', update_time = now(), step = 2 where customer_id = '" . $user_id . "';";
                $update_result = mysql_query($update_sql);
                if ($update_result) {
                    ?>
            <script language="javascript">
                location.href = "request_artist_offline.php";
                // location.href="request_artist_professional.php";
            </script>
        <?php
                } else {
                    ?>
            <script language="javascript">
                $.MessageBox({
                    buttonDone: "확인",
                    message: "제출실패 재시도해주세요."
                }).done(function() {
                    location.href = "view_event2.php";
                });
            </script>
        <?php
                }
            } else {
                ?>
        <script language="javascript">
            $.MessageBox({
                buttonDone: "확인",
                message: "잘못된 접근입니다.처음으로 이동합니다."
            }).done(function() {
                location.href = "view_event2.php";
            });
        </script>
        <?php
            }
        } else if ($step == "3") {
            $pro_value = "";
            for ($pro_index = 0; $pro_index < 10; $pro_index++) {
                $apro = $_REQUEST["region_$pro_index"];
                if ($apro && $apro != "") {
                    if ($pro_value == "") {
                        $pro_value = $apro;
                    } else {
                        $pro_value = $pro_value . ":" . $apro;
                    }
                }
            }
            $pro_value = $crypto->encode(trim($pro_value), $access_key, $secret_key);

            $sql = "select * from tb_request_artist where customer_id = '" . $user_id . "';";
            $result = mysql_query($sql);
            if ($result_datas = mysql_fetch_object($result)) {
                $update_sql = "update tb_request_artist set professional = '" . $pro_value . "', update_time = now(), step = 3 where customer_id = '" . $user_id . "';";
                $update_result = mysql_query($update_sql);
                if ($update_result) {
                    ?>
            <script language="javascript">
                location.href = "request_artist_offline.php";
            </script>
        <?php
                } else {
                    ?>
            <script language="javascript">
                $.MessageBox({
                    buttonDone: "확인",
                    message: "제출실패 재시도해주세요."
                }).done(function() {
                    location.href = "view_event2.php";
                });
            </script>
        <?php
                }
            } else {
                ?>
        <script language="javascript">
            $.MessageBox({
                buttonDone: "확인",
                message: "잘못된 접근입니다.처음으로 이동합니다."
            }).done(function() {
                location.href = "view_event2.php";
            });
        </script>
        <?php
            }
        } else if ($step == "4") {
			$choice_service = ($_REQUEST["service_type"] && $_REQUEST["service_type"] != "")? implode(',', $_REQUEST["service_type"]) : "";
            $is_offline = $_REQUEST["offline_yesno"];
            $shopname = $_REQUEST["offline_shopname"];
            $cellphone = $_REQUEST["offline_shop_cellphone"];
            $address = $_SESSION['gobeauty_address'];
            $rest_address = $_SESSION['gobeauty_rest_address'];
            $result_address = $address . " " . $rest_address;
			$lat = $_SESSION['gobeauty_lat'];
			$lng = $_SESSION['gobeauty_lng'];

            $shopname = $crypto->encode(trim($shopname), $access_key, $secret_key);
            $cellphone = $crypto->encode(trim($cellphone), $access_key, $secret_key);
            $result_address = $crypto->encode(trim($result_address), $access_key, $secret_key);

            if ($is_offline == "1") {

                $sql = "select * from tb_request_artist where customer_id = '" . $user_id . "';";
                $result = mysql_query($sql);
                if ($result_datas = mysql_fetch_object($result)) {
                    $update_sql = "update tb_request_artist set step = 4, is_got_offline_shop = true, offline_shop_name = '" . $shopname . "', offline_shop_phonenumber = '" . $cellphone . "', offline_shop_address = '" . $result_address . "', lat = '".$lat."', lng = '".$lng."', choice_service = '".$choice_service."', update_time = now() where customer_id = '" . $user_id . "';";
                    $update_result = mysql_query($update_sql);
                    if ($update_result) {
                        ?>
                <script language="javascript">
                    location.href = "request_artist_notice.php";
                </script>
            <?php
                        } else {
                            ?>
                <script language="javascript">
                    $.MessageBox({
                        buttonDone: "확인",
                        message: "제출실패 재시도해주세요."
                    }).done(function() {
                        location.href = "view_event2.php";
                    });
                </script>
            <?php
                        }
                    } else {
                        ?>
            <script language="javascript">
                $.MessageBox({
                    buttonDone: "확인",
                    message: "잘못된 접근입니다.처음으로 이동합니다."
                }).done(function() {
                    location.href = "view_event2.php";
                });
            </script>
            <?php
                    }
                } else {
                    $sql = "select * from tb_request_artist where customer_id = '" . $user_id . "';";
                    $result = mysql_query($sql);
                    if ($result_datas = mysql_fetch_object($result)) {
                        $update_sql = "update tb_request_artist set step = 4, is_got_offline_shop = false, update_time = now() where customer_id = '" . $user_id . "';";
                        $update_result = mysql_query($update_sql);
                        if ($update_result) {
                            ?>
                <script language="javascript">
                    location.href = "request_artist_notice.php";
                </script>
            <?php
                        } else {
                            ?>
                <script language="javascript">
                    $.MessageBox({
                        buttonDone: "확인",
                        message: "제출실패 재시도해주세요."
                    }).done(function() {
                        location.href = "view_event2.php";
                    });
                </script>
            <?php
                        }
                    } else {
                        ?>
            <script language="javascript">
                $.MessageBox({
                    buttonDone: "확인",
                    message: "잘못된 접근입니다.처음으로 이동합니다."
                }).done(function() {
                    location.href = "view_event2.php";
                });
            </script>
        <?php
                }
            }
        } else if ($step == "5") {
            $sql = "select * from tb_request_artist where customer_id = '" . $user_id . "';";
            $result = mysql_query($sql);
            if ($result_datas = mysql_fetch_object($result)) {
                $update_sql = "update tb_request_artist set step = 5, update_time = now() where customer_id = '" . $user_id . "';";
                $update_result = mysql_query($update_sql);
                if ($update_result) {
                    ?>
            <script language="javascript">
                location.href = "<?= $mainpage_directory ?>/";
            </script>
        <?php
                } else {
                    ?>
            <script language="javascript">
                $.MessageBox({
                    buttonDone: "확인",
                    message: "제출실패 재시도해주세요."
                }).done(function() {
                    location.href = "view_event2.php";
                });
            </script>
        <?php
                }
            } else {
                ?>
        <script language="javascript">
            $.MessageBox({
                buttonDone: "확인",
                message: "잘못된 접근입니다.처음으로 이동합니다."
            }).done(function() {
                location.href = "view_event2.php";
            });
        </script>
<?php
    }
}

include "../include/bottom.php";
?>