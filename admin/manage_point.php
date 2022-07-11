<?php
include "../include/top.php";
include "../include/Point.class.php";

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

    <div class="header-back-btn"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/back.png" width="35px"></a></div>
    <div style="width:100%;height:19px;text-align:center;font-size:18px;font-weight:bold;">
        <p>포인트 충전</p>
    </div>

    <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">

    <form action="manage_point.php" method="POST">
        <table width="100%">
            <tr>
                <td align="center">
                    고객ID <input type="text" name="ph_customer_id" />
                </td>
            </tr>
            <tr>
                <td align="center">
                    포인트 <input type="text" name="ph_point" />
                </td>
            </tr>
            <tr>
                <td>
                    <button type="submit" style="width:100%">추가</button>
                </td>
            </tr>
            <tr>
                <td height="10px"></td>
            </tr>
        </table>
    </form>

    <?php
        if ($_REQUEST['ph_customer_id'] && $_REQUEST['ph_point']) {
            $customer_id = trim($_REQUEST['ph_customer_id']);
            $point_value = trim($_REQUEST['ph_point']);

            echo $customer_id . "님에게 " . $point_value . "포인트를 추가합니다.<br><br>";

            $point = new Point();
            $result = $point->select_point($customer_id);
            $event_id = "ADMIN_" . rand_id();

            echo "<b>추가전 포인트현황</b><br>";
            $point->print_stdio();
            $point->add_accumulate_point_by_event($point_value, $event_id);

            echo "<br><br><b>추가후 포인트현황</b><br>";
            $point->print_stdio();
            ?>
        <br>
        5초후 메인으로 이동합니다.
        <br>
        <meta http-equiv="refresh" content="5; url=/pet/admin/index.php">
    <?php
        }
        ?>

<?php
    include "../include/bottom.php";
}
?>