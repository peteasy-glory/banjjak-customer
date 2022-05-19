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
<link rel="stylesheet" href="../css/reset.css">
<meta name="apple-mobile-web-app-capable" content="yes" />
<script type="text/javascript" src="../js/jquery.easing.js"></script>
<script type="text/javascript" src="../js/vinyli.viSimpleSlider.js"></script>
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

    body {
        height: auto;
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
        font-size: 15px;
        font-weight: bold;
        font-style: normal;
        height: 36px;
        line-height: 36px;
        width: 88px;
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

        background-color: #f5bf2e;
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
        margin-top: 50px;
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
        font-size: 17px;
        font-weight: bold;

        height: 47px;
        line-height: 47px;
        width: 90%;
        margin: 0px 5%;

        margin-top: 50px;
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

    .b_section {
        width: 100%;
    }

    .b_section img {
        width: 100%;
    }

    .header_title {
        width: 100%;
        height: 19px;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        padding: 15px 0px 15px 0px;
        display: table;
        border-bottom: 0.5px solid #e1e1e1;
    }

    .header_title p {
        margin: 0px;
    }

    .header-back-btn{
        margin-top: 13px;
    }

    #testDiv {
        background-color: #ffffff;
    }

    h1 {
        text-align: center;
        font-family: 'Playfair Display', serif;
        font-weight: normal;
        font-style: italic;
        font-size: 48px;
        padding: 20px;
        color: #634bbc;
    }

    div.info {
        font-size: 16px;
        padding: 20px;
        margin-bottom: 40px;
    }

    div.info>dl>dt {
        font-size: 18px;
        margin-top: 30px;
        margin-bottom: 20px;
        padding-bottom: 5px;
        color: #333;
        border-bottom: 1px solid #ddd;
    }

    div.info>dl dd {
        font-size: 13px;
        padding-left: 20px;
        color: #666;
    }

    div.info>dl dd p.html {
        color: #fff;
        background-color: #0ead3b;
        padding: 10px;
    }

    div.info>dl dd p.js {
        color: #fff;
        background-color: #444;
        padding: 10px;
    }

    div.info>dl dd p.js2 {
        color: #fff;
        background-color: #666;
        padding: 10px;
    }

    div.info>dl dd>dl>dt {
        font-size: 16px;
        margin-top: 20px;
        margin-bottom: 5px;
        border-bottom: 1px dashed #ddd;
        color: #ff4280;
    }

    div.info>dl dd>dl>dd {
        padding-left: 0;
    }

    div.info>dl dd ul li {
        margin-bottom: 10px;
    }

    .viSimpleSlider,
    #testDiv {
        width: 80%;
        overflow: hidden;
    }

    .viSimpleSlider ul>li,
    #testDiv ul>li {
        position: absolute;
        font-size: 0;
        line-height: 0;
    }

    .viSimpleSlider ul>li img,
    #testDiv ul>li img {
        width: 100%;
        height: auto;
    }

    .viSimpleSlider ul>li .slideTo,
    #testDiv ul>li .slideTo {
        background-color: #222;
        color: #fff;
    }

    .viSimpleSlider ul>li .slideTo.active,
    #testDiv ul>li .slideTo.active {
        background-color: green;
    }

    .viSimpleSlider ul>li .slideTo>div,
    #testDiv ul>li .slideTo>div {
        font-size: 14px;
        margin-top: -20px;
    }

    .viSimpleSlider ul>li .slideTo>img,
    #testDiv ul>li .slideTo>img {
        vertical-align: top;
    }

    .viSimpleSlider ul>li span.indexNumber,
    #testDiv ul>li span.indexNumber {
        display: block;
        position: absolute;
        z-index: 3;
        left: 80px;
        top: 200px;
        color: #1e1e1e;
        font-size: 16px;
    }

    .viSimpleSlider .indicate,
    #testDiv .indicate {
        width: 100%;
        bottom: 10px;
        z-index: 990;
        text-align: center;
    }

    .viSimpleSlider .indicate>a,
    #testDiv .indicate>a {
        display: inline-block;
        width: 10px;
        height: 10px;
        background-color: #e1e1e1;
        margin-left: 10px;
        margin-right: 10px;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        -ms-border-radius: 10px;
        -o-border-radius: 10px;
        border-radius: 10px;
    }

    .viSimpleSlider .indicate>a.active,
    #testDiv .indicate>a.active {
        background-color: #f5bf2e;
    }

    .viSimpleSlider .indicate:after,
    #testDiv .indicate:after {
        display: block;
        visibility: hidden;
        content: ".";
        clear: both;
        text-indent: -9999px;
        height: 0;
    }

    .viSimpleSlider a.arrowBtn,
    #testDiv a.arrowBtn {
        display: block;
        position: absolute;
        width: 30px;
        top: 50%;
        height: 30px;
        margin-top: -15px;
        z-index: 120;
    }

    .viSimpleSlider a.arrowBtn.prev,
    #testDiv a.arrowBtn.prev {
        left: 10px;
        background-position: 50% 50%;
        background-size: 24px;
        background-repeat: no-repeat;
    }

    .viSimpleSlider a.arrowBtn.next,
    #testDiv a.arrowBtn.next {
        right: 10px;
        background-position: 50% 50%;
        background-size: 24px;
        background-repeat: no-repeat;
    }

    #footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        width: 100%;
        background-color: #333;
        text-align: center;
    }

    #footer a {
        color: #ddd;
        font-size: 11px;
    }
</style>

<div class="header-back-btn"><a href="<?= $mainpage_directory ?>/"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
<div class="header_title">
    <p>반짝 입점 신청하기</p>
</div>

<div id="testDiv">
    <ul>
        <li>
            <img src="../images/191107_1.jpg">
        </li>
        <li>
            <img src="../images/191107_2.jpg">
        </li>
        <li>
            <img src="../images/191107_3.jpg">
        </li>
        <li>
            <img src="../images/191107_4.jpg">
        </li>
        <li>
            <img src="../images/191107_5.jpg">
        </li>
    </ul>
</div>

<script>
    $('#testDiv').viSimpleSlider({
        ease: 'easeOutQuart',
        autoPlay: true,
        autoTime: 6000,
        speed: 400,
        mobileSwipe: true,
        desktopSwipe: true
    });
</script>

<div style="width:100%;font-size:13px;text-align:right;"><a href="<?= $mainpage_directory ?>/regist_shop_auth.php" class="go_button">반짝 입점 신청하기</a></div>
<br>
<br><br><br>
<br><br><br>
<?php include "../include/bottom.php"; ?>