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

    html,
    div,
    p,
    a {
        font-family: 'SCDream2';
    }

    .header_t p {
        margin: 0px;
        line-height: 55px;
    }

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
        padding: 10px;
        border: 1 solid #999999;
        margin: auto;
    }

    .change_reservation {
        font-family: 'SCDream2';
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
        border: 1px solid #f5bf2e;
        display: inline-block;
        color: #ffffff;
        font-size: 16px;
        font-weight: bold;
        font-style: normal;
        height: 36px;
        line-height: 36px;
        width: 100%;
        text-decoration: none;
        text-align: center;
    }

.change_reservation1 {
        font-family: 'SCDream2';
        background-color: #f5bf2e !important;
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
        border: 1px solid #f5bf2e;
        display: inline-block;
        color: #ffffff;
        font-size: 17px;
        font-weight: bold;
        font-style: normal;
        height: 36px;
        line-height: 36px;
        width: 100%;
        text-decoration: none;
        text-align: center;
    }

    .change_reservation:hover {

        background-color: #f5bf2e;

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
        border: 0px solid #f5bf2e;
        display: inline-block;
        color: #ffffff;
        font-family: Arial;
        font-size: 20px;
        font-weight: bold;
        font-style: normal;
        height: 47px;
        line-height: 47px;
        width: 100%;
        text-decoration: none;
        text-align: center;
    }

    .go_button:hover {

        background-color: #f5bf2e;
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

    .header_t {
        height: 55px;
        border-bottom: 0.5px solid #e1e1e1;
    }

    .section_c {
        width: 90%;
        margin: 0px auto;
        font-weight: bold;
    }

    .s_agree {
        font-size: 12px;
    }
</style>

<script>
    var pay_flag = 0;

    function set_pay_notice_flag() {
        if (pay_flag == 0) {
            pay_flag = 1;
            document.getElementById("pay_notice").style.display = "block";
        } else {
            pay_flag = 0;
            document.getElementById("pay_notice").style.display = "none";
        }
    }

    function checkDisable() {
        if (document.getElementById("agreement").checked == true) {
            document.getElementById("payment_submit_button").disabled = false;
        } else {
            document.getElementById("payment_submit_button").disabled = true;
        }
    }
</script>
<div class="header_t">
    <div style="position:absolute;z-index:5;top:15px;left:10px;"><a href="<?= $mainpage_directory ?>/request_artist_offline.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div style="width:100%;height:19px;text-align:center;font-size:18px;font-weight:bold;">
        <p>반짝 입점 신청</p>
    </div>
</div>

<form action="<?= $mainpage_directory ?>/insert_artist_regist.php" method="POST">
    <input type="hidden" name="step" value="5" />
    <table class="section_c">
        <tr>
            <td height="10px" colspan="3"></td>
        </tr>
        <tr>
            <td colspan="3" style="font-size:14px;">
                입점 신청을 해주셔서 감사합니다.<br><br>
                신청이 승인되면 카톡으로 오픈에 필요한 정보를<br>
				요청해드립니다.
                <br><br>
                이후 정보를 전달해주신 날로부터 1영업일내에<br>
				오픈해드리고 있습니다.
				<br><br>
                감사합니다.
            </td>
        </tr>
        <tr>
            <td height="5px" colspan="3"></td>
        </tr>
        <tr>
            <td style="font-size:18px;" colspan="3">
                <div style="border:1px solid #000;">
                    <div style="font-size:15px;padding:5px;">이후 입점 절차 안내</div>
                    <img src="<?= $image_directory ?>/n_request_artist.jpg" width="100%">
                </div>
            </td>
        </tr>
        <tr>
            <td height="10px" colspan="3"></td>
        </tr>
        <tr>
            <td width="10%">
                <input type="checkbox" name="agreement" id="agreement" onclick="checkDisable();">
            </td>
            <td class="s_agree">
                <a href="#" onclick="set_pay_notice_flag();">
                    <font style="color:#000;">(필수) 개인정보 수집 및 이용에 동의합니다.</font>
                </a>
            </td>

        </tr>
        <tr>
            <td colspan="3">
                <iframe id="pay_notice" src="agree_artist_notice.php" style="overflow:hidden;height:100%;width:100%;display:none;border:1px solid #999999;" frameborder="1" width="100%" height="500" marginwidth="0" marginheight="0" scrolling="0"></iframe>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <input type="submit" id="payment_submit_button" name="payment_submit_button" href="#" class="change_reservation1" value="반짝 입점신청 완료" disabled="true">
            </td>
        </tr>
    </table>
</form>


<br>
<br><br><br>
<br><br><br>
<?php include "../include/bottom.php"; ?>