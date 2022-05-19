<?php include "../include/top.php"; ?>

<?php
$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}
?>

<?php
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
?>

<style>
    @font-face {
        font-family: 'SCDream2';
        src: url("../fonts/SCDream2.otf");
    }

    * {
        font-family: 'SCDream2';
        font-weight: bold;
    }


    .top_menu {
        height: 51px;
        position: relative;
    }

    .top_title {
        width: 100%;
        height: 19px;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        padding: 15px 0px 15px 0px;
        display: table;
        border-bottom: 0.5px solid #e1e1e1;
    }

    .top_title p {
        margin: 0px;
    }

    .top_back {
        position: absolute;
        bottom: 11px;
        left: 10px;
    }

    .filebox label {
        position: relative;
        display: inline-block;
        width: 100%;
        text-align: center;
        height: 29px;
        color: #999;
        font-size: inherit;
        vertical-align: middle;
        background-color: #fdfdfd;
        cursor: pointer;
        border: 0px solid #ebebeb;
        border-bottom-color: #e2e2e2;
    }

    .filebox input[type="file"] {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        border: 0;
    }

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

    .my_reservation {
        position: relative;
        z-index: 5;
        width: 100%;
        text-align: left;
        padding: 10px;
        border: 1 solid #999999;
        margin: auto;
    }

    .change_reservation {
        -webkit-appearance: none;
        border-radius: 0;
        background-color: #f5bf2e;
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
        font-family: 'SCDream2';
        font-size: 20px;
        font-weight: bold;
        font-style: normal;
        height: 36px;
        line-height: 36px;
        text-decoration: none;
        text-align: center;
    }

    .change_reservation:hover {
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #f5a82e), color-stop(1, #f5bf2e));
        background: -moz-linear-gradient(center top, #f5a82e 5%, #f5bf2e 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f5a82e', endColorstr='#f5bf2e');
        background-color: #f5a82e;
    }

    .change_reservation:active {
        position: relative;
        top: 1px;
    }

    .cancel_reservation {
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #b5b5b5), color-stop(1, #999999));
        background: -moz-linear-gradient(center top, #ededed 5%, #dfdfdf 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#dfdfdf');
        background-color: #999999;
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
        border: 1px solid #dcdcdc;
        display: inline-block;
        color: #777777;
        font-family: Arial;
        font-size: 15px;
        font-weight: bold;
        font-style: normal;
        height: 36px;
        line-height: 36px;
        width: 88px;
        text-decoration: none;
        text-align: center;
    }

    .cancel_reservation:hover {
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #b5b5b5), color-stop(1, #999999));
        background: -moz-linear-gradient(center top, #dfdfdf 5%, #ededed 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#dfdfdf', endColorstr='#ededed');
        background-color: #999999;
    }

    .cancel_reservation:active {
        position: relative;
        top: 1px;
    }

    .event_title {
        color: #000;
        font-size: 20px;
        font-weight: bold;
    }

    .event_body {
        color: #000;
        font-size: 16px;
    }

    .go_button {
        -webkit-appearance: none;
        border-radius: 0;
        background-color: #f5bf2e;
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
        border: 0px solid #a511c0;
        display: inline-block;
        color: #ffffff;
        font-family: 'SCDream2';
        font-size: 16px;
        font-weight: bold;
        font-style: normal;
        height: 40px;
        line-height: 40px;
        width: 90%;
        margin: 0px auto;
        text-decoration: none;
        text-align: center;
    }

    .go_button:hover {
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #f5a82e), color-stop(1, #f5bf2e));
        background: -moz-linear-gradient(center top, #f5a82e 5%, #f5bf2e 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f5a82e', endColorstr='#f5bf2e');
        background-color: #f5a82e;
    }

    .go_button:active {
        position: relative;
        top: 1px;
    }

    input[type=text],
    input[type=number],
    select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-sizing: border-box;
    }

    .layer_pop {
        border: 1px solid #999999;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        position: absolute;
        background: #fff;
        z-index: 5000;
        left: 0;
        top: 106px;
        text-align: center;
    }

    .layer_con {
        border: 1px solid #999999;
        padding: 3px;
        position: relative;
    }

    .layer_con .layer_close {
        position: absolute;
        right: 5px;
        top: 5px;
    }

    .layer_con .allow_bg {
        position: absolute;
        left: 42px;
        bottom: -8px;
    }

    .fwb {
        font-weight: bold !important;
    }

    .withdraw_wrap {
        border: 1px solid #999999;
        padding: 20px;
        font-size: 14px;
    }

    .withdraw {
        width: 90%;
        margin: 0px auto;
        margin-top: 14px;
    }

    .agree_wrap {
        width: 90%;
        margin: 0px auto;
        line-height: 38px;
    }

    .agree_chk {
        float: left;
        margin-top: 10px;
    }
</style>

<div class="top_menu">
    <div class="top_back" style="top:13px;"><a href="<?= $mainpage_directory ?>/manage_setting.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div class="top_title">
        <p>회원 탈퇴</p>
    </div>
</div>

<form action="<?= $mainpage_directory ?>/withdraw_process.php" method="POST" id="withdraw_form">
    <input type="hidden" name="step" value="10" />
    <div style="width:100%;font-size:20px;margin-top: 20px;">
        <div>
            <div colspan="2" style="font-size:15px;text-align:center; ">
                <b>회원탈퇴 전에 유의사항을 반드시 확인해주세요.</b><br>
            </div>
        </div>
        <div>
            <div height="5px"></div>
        </div>
        <div class="withdraw">
            <div class="withdraw_wrap">
                * 탈퇴 시 보유하고 계신 포인트, 쿠폰은 소멸되며 재발행이 불가능합니다.<br>
                <br>
                * 탈퇴한 계정의 이용기록은 모두 삭제되며, 삭제된 데이터는 복구되지 않습니다. <br>
                (단, 작성된 리뷰와 결제내역은 5년까지 보관)<br>
                <br>
                * 탈퇴 후에는 본인여부 확인이 불가하여 게시글을 임의로 삭제해드릴수 없습니다. <br><br>
                [삭제되는 정보] <br>
                이메일아이디, 닉네임, 휴대폰 번호, 주문이력, 단골아티스트, 포인트, 쿠폰 <br>
            </div>

        </div>
        <div>
            <div colspan="2" style="font-size:12px;">
                <div width="100%">
                    <div class="agree_wrap">
                        <div class="agree_chk">
                            <input type="checkbox" name="agreement" id="agreement1">
                        </div>
                        <div>반짝 서비스의 계정을 삭제하겠습니다.</div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div style="font-size:15px;text-align:center;" width="50%">
                <input type="submit" onclick="return checkDisable();" class="go_button" value="탈퇴합니다" id="payment_submit_button" name="payment_submit_button" />
                <div id="end_scroll"></div>
                <br>
            </div>
        </div>
    </div>
</form>

<br>
<br><br><br>
<br><br><br>

<script>
    function checkDisable() {
        if (document.getElementById("agreement1").checked == false) {
            $.MessageBox({
                buttonDone: "확인",
                message: "계정 삭제 체크에 동의 하셔야 합니다."
            }).done(function() {
                return false;
            });
        } else {
            $.MessageBox({
                buttonFail: "취소",
                buttonDone: "예",
                message: "탈퇴하시겠습니까?"
            }).done(function() {
                $("#withdraw_form").submit();
            });
        }
        return false;
    }
</script>

<?php include "../include/bottom.php"; ?>