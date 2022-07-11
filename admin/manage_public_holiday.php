<?php
include "../include/top.php";

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
        <p>공휴일 관리</p>
    </div>

    <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">
    <br>

    <?php
        if ($_REQUEST['year'] && $_REQUEST['month'] && $_REQUEST['day']) {
            $sql = "insert into tb_public_holiday (year, month, day) values ('" . $_REQUEST['year'] . "', '" . $_REQUEST['month'] . "', '" . $_REQUEST['day'] . "');";
            $result = mysql_query($sql);
        }
        ?>

    <form action="manage_public_holiday.php" method="POST">
        <input type="text" name="year" value="<?= $_REQUEST['year'] ?>" style="height:20px;width:80px;">년
        <input type="text" name="month" value="<?= $_REQUEST['month'] ?>" style="height:20px;width:80px;">월
        <input type="text" name="day" value="<?= $_REQUEST['day'] ?>" style="height:20px;width:80px;">일 <button type="submit">추가</button>
    </form>

    <font style="font-size:13px;">* 일요일을 제외 한 공휴일을 입력해줘야 합니다.</font><br>
    <?php
        $sql = "select * from tb_public_holiday;";
        $result = mysql_query($sql);
        $result_count = mysql_num_rows($result);
        ?>
    총 : <?= $result_count ?> 개<br>
    <table width="100%" border="1" style="font-size:12px;border:1px solid #999999;border-collapse:collapse;">
        <tr style="font-weight:bold;text-align:center;">
            <td>년</td>
            <td>월</td>
            <td>일</td>
        </tr>
        <?php
            while ($rows = mysql_fetch_object($result)) {
                ?>
            <tr>
                <td><?= $rows->year ?></td>
                <td><?= $rows->month ?></td>
                <td><?= $rows->day ?></td>
            </tr>
        <?php
            }
            ?>
    </table>

<?php
    include "../include/buttom.php";
}
?>