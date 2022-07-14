<?php include "../include/top.php";

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

        .cell_confirm {
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #c123de), color-stop(1, #a20dbd));
            background: -moz-linear-gradient(center top, #c123de 5%, #a20dbd 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#c123de', endColorstr='#a20dbd');
            background-color: #c123de;
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
            border: 1px solid #a511c0;
            display: inline-block;
            color: #ffffff;
            font-family: Arial;
            font-size: 20px;
            font-weight: bold;
            font-style: normal;
            height: 36px;
            line-height: 36px;
            width: 100%;
            text-decoration: none;
            text-align: center;
        }

        .cell_confirm:hover {
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #a20dbd), color-stop(1, #c123de));
            background: -moz-linear-gradient(center top, #a20dbd 5%, #c123de 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#a20dbd', endColorstr='#c123de');
            background-color: #a20dbd;
        }

        .change_reservation:active {
            position: relative;
            top: 1px;
        }

        .cell_confirm:active {
            position: relative;
            top: 1px;
        }
    </style>
    <?php
        $sequence = $_REQUEST['sequence'];
        if (!$sequence) {
            $sequence = 1;
        }
        ?>
    <div class="header-back-btn"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/back.png" width="35px"></a></div>
    <div style="width:100%;height:19px;text-align:center;font-size:18px;font-weight:bold;">
        <p>신규회원가입</p>
    </div>
    <div style="position:absolute;z-index:5;top:5px;right:10px;"><a href="<?= $mainpage_directory ?>/"><img src="<?= $image_directory ?>/main_logo.png" height="28px"></a></div>

    <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">

    <div style="height:20px;"></div>

    <form action="manage_new_customer_process.php" id="next_form" method="POST">
        <b>이메일 아이디</b><br>
        <input type="text" name="email_id" id="email_id" required>
        <br><br>
        <b>전화번호</b><br>
        <input type="text" name="cellphone" id="email_id" required>
        <br>
        <br>
        <input type="submit" value="생성">
    </form>

<?php
    include "../include/bottom.php";
}
?>