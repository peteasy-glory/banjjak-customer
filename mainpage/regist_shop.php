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
a{text-decoration:none; }
a:link {color:white;}
a:visited {color:white;}
a:hover {color:white;}
a:active {color:white;}

.my_reservation{
position:relative;
z-index:5;
width:100%;
text-align:left;
padding:10px;
border:1 solid #999999;
margin:auto;
}

.change_reservation {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #c123de), color-stop(1, #a20dbd) );
	background:-moz-linear-gradient( center top, #c123de 5%, #a20dbd 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#c123de', endColorstr='#a20dbd');
	background-color:#c123de;
	-webkit-border-top-left-radius:6px;
	-moz-border-radius-topleft:6px;
	border-top-left-radius:6px;
	-webkit-border-top-right-radius:6px;
	-moz-border-radius-topright:6px;
	border-top-right-radius:6px;
	-webkit-border-bottom-right-radius:6px;
	-moz-border-radius-bottomright:6px;
	border-bottom-right-radius:6px;
	-webkit-border-bottom-left-radius:6px;
	-moz-border-radius-bottomleft:6px;
	border-bottom-left-radius:6px;
	text-indent:0;
	border:1px solid #a511c0;
	display:inline-block;
	color:#ffffff;
	font-family:Arial;
	font-size:15px;
	font-weight:bold;
	font-style:normal;
	height:36px;
	line-height:36px;
	width:88px;
	text-decoration:none;
	text-align:center;
}
.change_reservation:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #a20dbd), color-stop(1, #c123de) );
	background:-moz-linear-gradient( center top, #a20dbd 5%, #c123de 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#a20dbd', endColorstr='#c123de');
	background-color:#a20dbd;
}.change_reservation:active {
	position:relative;
	top:1px;
}
.cancel_reservation {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #b5b5b5), color-stop(1, #999999) );
	background:-moz-linear-gradient( center top, #ededed 5%, #dfdfdf 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#dfdfdf');
	background-color:#999999;
	-webkit-border-top-left-radius:6px;
	-moz-border-radius-topleft:6px;
	border-top-left-radius:6px;
	-webkit-border-top-right-radius:6px;
	-moz-border-radius-topright:6px;
	border-top-right-radius:6px;
	-webkit-border-bottom-right-radius:6px;
	-moz-border-radius-bottomright:6px;
	border-bottom-right-radius:6px;
	-webkit-border-bottom-left-radius:6px;
	-moz-border-radius-bottomleft:6px;
	border-bottom-left-radius:6px;
	text-indent:0;
	border:1px solid #dcdcdc;
	display:inline-block;
	color:#777777;
	font-family:Arial;
	font-size:15px;
	font-weight:bold;
	font-style:normal;
	height:36px;
	line-height:36px;
	width:88px;
	text-decoration:none;
	text-align:center;
}
.cancel_reservation:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #b5b5b5), color-stop(1, #999999) );
	background:-moz-linear-gradient( center top, #dfdfdf 5%, #ededed 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#dfdfdf', endColorstr='#ededed');
	background-color:#999999;
}.cancel_reservation:active {
	position:relative;
	top:1px;
}

.event_title {
	color:#000;font-size:20px;font-weight:bold;
}
.event_body {
	color:#000;font-size:16px;
}

.go_button {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #fa9dcc), color-stop(1, #f192c1) );
	background:-moz-linear-gradient( center top, #c123de 5%, #a20dbd 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#fa9dcc', endColorstr='#f192c1');
	background-color:#fa9dcc;
	-webkit-border-top-left-radius:6px;
	-moz-border-radius-topleft:6px;
	border-top-left-radius:6px;
	-webkit-border-top-right-radius:6px;
	-moz-border-radius-topright:6px;
	border-top-right-radius:6px;
	-webkit-border-bottom-right-radius:6px;
	-moz-border-radius-bottomright:6px;
	border-bottom-right-radius:6px;
	-webkit-border-bottom-left-radius:6px;
	-moz-border-radius-bottomleft:6px;
	border-bottom-left-radius:6px;
	text-indent:0;
	border:0px solid #a511c0;
	display:inline-block;
	color:#ffffff;
	font-family:Arial;
	font-size:17px;
	font-weight:bold;
	font-style:normal;
	height:47px;
	line-height:47px;
	width:100%;
	text-decoration:none;
	text-align:center;
}
.go_button:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #a20dbd), color-stop(1, #c123de) );
	background:-moz-linear-gradient( center top, #a20dbd 5%, #c123de 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#a20dbd', endColorstr='#c123de');
	background-color:#a20dbd;
}.go_button:active {
	position:relative;
	top:1px;
}
</style>

        <div class="header-back-btn"><a href="<?=$mainpage_directory?>/"><img src="<?=$image_directory?>/back.png" width="35px"></a></div>
        <div style="width:100%;height:19px;text-align:center;font-size:18px;font-weight:bold;"><p>반짝 입점 신청</p></div>

        <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">

	<div style="height:10px;"></div>
        <table style="width:100%;text-align:left;">
                <tr>    
                        <td class="event_title">
                                애견미용 뷰티플랫폼! 반짝입니다.
                        </td>
                </tr>   
        </table>

	<table style="width:100%;text-align:left;">
		<tr><td height="30px"></td></tr>
                <tr>
                        <td class="event_body">
				블로그 관리하랴 미용중 전화상담받으랴 정신없으시죠?<br>
				<br>
                                아직 오프라인 매장이 없으신가요?<br>
                                매장은 있지만 혼자 매장만 지키고 계신가요?<br>
				<br>
				매장이 있는 분은 더욱 편리하게,<br>
				매장이 없는 분이라도 전혀  불편없게<br>
				나만의 매장을 무료로 드립니다.<br>
				<br>
				반짝 온라인 매장으로 입점하세요.<br>
				반짝 서비스는 온라인 매장을 완벽하게 지원합니다. 업무시간 조정, 예약관리, 고객관리, 휴일지정은 물론 잠시 외출/조퇴도 가능합니다.<br>
				<br>
				반짝과 함께 하세요.
                        </td>
                </tr>
		<tr><td height="20px"></td></tr>
        </table>
	<div style="height:10px;"></div>
	<div style="width:100%;font-size:13px;text-align:right;"><a href="regist_shop_auth.php" class="go_button">입점 신청 시작하기</a></div>
	<br>
	<br><br><br>
	<br><br><br>
<?php include "../include/bottom.php"; ?>
