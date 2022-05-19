<?php 
include "../include/top.php";
?>

<?php
$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}
?>

<?php
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

$squence = $_REQUEST['seq'];
if (!$squence) {
    $squence = 1;
}
?>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">

<div class="top_menu">
    <div class="top_back" ><a href="<?= $mainpage_directory ?>/mainpage_my_menu.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div class="top_title">
        <p>문의내역</p>
    </div>
    <div class="top_question"><a href="manage_1vs1_request.php" class="request_1vs1">문의하기</a></div>
</div>

<center id="manage_1vs1">


    <?php
    $login_insert_sql = "SELECT * FROM tb_1vs1_pna WHERE customer_id = '" . $user_id . "' GROUP BY update_time ORDER BY update_time DESC;";
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
            <div class="user_question">
                <div class="question_wrap">
                    <div>
                        <b><?= $update_time ?> (<?= $type ?>)</b>
                    </div>

                    <div>
                        <div>
                            <b style="font-size: 16px;"><?= $title ?></b>
                        </div>
                    </div>
                    <div>
                        <div>
                            <?= $body ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?php
            $re_sql = "SELECT * FROM tb_1vs1_pna_sub WHERE qna_id = '" . $uuid . "';";
            $re_result = mysql_query($re_sql);
            if ($re_rows = mysql_fetch_object($re_result)) {
                ?>
            <div class="my_reservation">
                <div class="user_answer">
                    <div class="answer_wrap">
                        <div>
                            <b><?= $re_rows->update_time ?></b>
                        </div>
                    </div>
                    <div class="answer_wrap">
                        <div>
                            <?= $re_rows->body ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php
            } else {
                ?>
            <div class="my_reservation">
                <div class="user_answer">
                    <div class="answer_wrap">
                        <div>
                            문의 감사드립니다. <br /> 최대한 빠른시간내에 답글을 드리겠습니다.<br>
                        </div>
                    </div>
                </div>
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