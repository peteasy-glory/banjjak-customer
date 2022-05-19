<?php include "../include/top.php"; ?>
<?php include "../include/Push.class.php"; ?>

<?php
$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}
?>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<?php
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
?>

<?php
$push = new Push();
$is_push = $push->is_push($user_id);
?>
<div class="top_menu">
    <div class="top_back"><a href="<?= $mainpage_directory ?>/mainpage_my_menu.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div class="top_title">
        <p>설 정</p>
    </div>
</div>


<center id="mainpage_manage_setting">
    <a href="manage_password.php">
        <div class="my_menu_div">
            <table class="my_menu_text">
                <tr>
                    <td>비밀번호 변경</td>
                </tr>
            </table>
            <table class="my_menu_img2">
                <tr>
                    <td>
                        <font style="color:#999999;font-size:16px;"><b>〉</b></font>
                    </td>
                </tr>
            </table>
        </div>
    </a>

    <div class="my_menu_div2">
        <table class="my_menu_text">
            <tr>
                <td>알림 수신</td>
            </tr>
        </table>
        <table class="my_menu_img2">
            <tr>
                <td>
                    <p>OFF</p>
                    <p style="display:none;">ON</p>
                </td>
                <td>
                    <label class="switch">
                        <input type="checkbox" <?php
                                                                                                                if ($is_push == 1) {
                                                                                                                    echo "checked";
                                                                                                                }
                                                ?>><span class="slider round"></span>
                    </label>
                </td>
            </tr>
        </table>
    </div>

    <div class="my_menu_div3">
        <table class="my_menu_text">
            <tr>
                <td>
                    <font style="font-size:12px;font-family: 'NanumGothic';font-weight:bold;">* Off상태이면 예약 관련 알림, 포인트 적립 등의 알림을 받을 수 없습니다.</font>
                </td>
            </tr>
        </table>
    </div>

    <a href="<?= $login_directory ?>/logout_process.php">
        <div class="my_menu_div">
            <table class="my_menu_text">
                <tr>
                    <td>로그아웃</td>
                </tr>
            </table>
            <table class="my_menu_img2">
                <tr>
                    <td><img src="<?= $image_directory ?>/btn_logout.png" height="20px" style="opacity:0.5;"></td>
                </tr>
            </table>
        </div>
    </a>

    <br>
    <br>

    <a href="<?= $mainpage_directory ?>/privacy_policy.php">
        <div class="my_menu_div">
            <table class="my_menu_text">
                <tr>
                    <td>개인정보보호지침</td>
                </tr>
            </table>
            <table class="my_menu_img2">
                <tr>
                    <td>
                        <font style="color:#999999;font-size:16px;"><b>〉</b></font>
                    </td>
                </tr>
            </table>
        </div>
    </a>

    <a href="<?= $mainpage_directory ?>/terms_of_service.php">
        <div class="my_menu_div">
            <table class="my_menu_text">
                <tr>
                    <td>이용약관</td>
                </tr>
            </table>
            <table class="my_menu_img2">
                <tr>
                    <td>
                        <font style="color:#999999;font-size:16px;"><b>〉</b></font>
                    </td>
                </tr>
            </table>
        </div>
    </a>

    <br>
    <br>

    <a href="withdraw.php">
        <div class="my_menu_div">
            <table class="my_menu_text">
                <tr>
                    <td>탈퇴하기</td>
                </tr>
            </table>
            <table class="my_menu_img2">
                <tr>
                    <td>
                        <font style="color:#999999;font-size:16px;"><b>〉</b></font>
                    </td>
                </tr>
            </table>
        </div>
    </a>

</center>

<script>
    function change_push() {
        $.ajax({
            url: 'change_user_push.php',
            type: 'POST',
            success: function(data) {
                location.reload();
            },
            error: function(xhr, status, error) {}
        });
    }

    var check = $("input[type='checkbox']");
    check.click(function() {
        $(".my_menu_img2 p").toggle();
        change_push();
    });
    var is_push = <?= $is_push ?>;
    if (is_push == 1) {
        $(".my_menu_img2 p").toggle();
    }
</script>
<?php include "../include/bottom.php"; ?>