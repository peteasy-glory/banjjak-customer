<?php include "../include/top.php"; ?>

<?php
//$cl_result = include "../include/check_login.php";
//if ($cl_result == 0) {
//	return false;
//}
?>

<?php
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
?>

<style>
@font-face {font-family: 'BMJUA';font-weight: normal;src: url("../fonts/BMJUA.otf");}
@font-face {font-family: 'NanumGothic';src: url("../fonts/NanumGothic.ttf");}

    html,
    div,
    p,
    a {
        font-family: 'BMJUA';
		font-weight:normal;
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
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #a20dbd), color-stop(1, #c123de));
        background: -moz-linear-gradient(center top, #a20dbd 5%, #c123de 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#a20dbd', endColorstr='#c123de');
        background-color: #a20dbd;
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
        font-size: 25px;
        padding: 15px 0px 15px 0px;
        display: table;
        border-bottom: 0.5px solid #e1e1e1;
    }

    .header_title p {
        margin: 0px;
    }
.header-back-btn{top:15px;}

</style>

<div class="header-back-btn"><a href="<?= $mainpage_directory ?>/"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
<div class="header_title">
    <p>애견미용뷰티 플랫폼</p>
</div>




<div class="b_section"><img src="../images/banjjak_01.jpg"></div>

<br><br><br>
<br><br><br>
<?php include "../include/bottom.php"; ?>