<?php include "../include/top.php"; ?>
<?php include "../include/Push.class.php"; ?>

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
a:link {color:black;}
a:visited {color:black;}
a:hover {color:black;}
a:active {color:black;}

.my_menu_div {
position:relative;
z-index:5;
width:93%;
height:30px;
text-align:left;
padding:5px;
border-bottom:1px solid #efefef;
border:1;
font-size:15px;
font-weight:bold;
margin:auto;
}
.my_menu_div2 {
position:relative;
z-index:5;
width:93%;
height:30px;
text-align:left;
padding:5px;
border-bottom:0px solid #efefef;
border:1;
font-size:15px;
font-weight:bold;
margin:auto;
}
.my_menu_div3 {
position:relative;
z-index:5;
width:93%;
height:40px;
text-align:left;
padding:5px;
border-bottom:1px solid #efefef;
border:1;
font-size:13px;
margin:auto;
}

.my_menu_img {
position:absolute;
z-index:5;
right:10px;
height:23px;
}
.my_menu_text {
position:absolute;
z-index:5;
left:10px;
height:30px;
}
.my_menu_img2 {
position:absolute;
z-index:5;
right:10px;
height:30px;
}

</style>

<style>
/* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 56px;
  height: 28px;
  vertical-align:middle;
}
 
/* Hide default HTML checkbox */
.switch input {display:none;}
 
/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}
 
.slider:before {
  position: absolute;
  content: "";
  height: 20px;
  width: 20px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}
 
input:checked + .slider {
  background-color: #2196F3;
}
 
input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}
 
input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}
 
/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}
 
.slider.round:before {
  border-radius: 50%;
}
 
.my_menu_img2 p {
  margin:0px;
  display:inline-block;
  font-size:15px;
  font-weight:bold;
}

.download_app {
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
	height:28px;
	line-height:28px;
	width:119px;
	text-decoration:none;
	text-align:center;
}
.download_app:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #a20dbd), color-stop(1, #c123de) );
	background:-moz-linear-gradient( center top, #a20dbd 5%, #c123de 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#a20dbd', endColorstr='#c123de');
	background-color:#a20dbd;
}.download_app:active {
	position:relative;
	top:1px;
}
</style>

<?php
	$push = new Push();
	$is_push = $push->is_push($user_id);
?>

        <div class="header-back-btn"><a href="<?=$mainpage_directory?>/mainpage_my_menu.php"><img src="<?=$image_directory?>/back.png" width="35px"></a></div>
        <div style="width:100%;height:19px;text-align:center;font-size:18px;font-weight:bold;"><p>설 정</p></div>

        <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">

<center>
                <a href="manage_password.php">
                <div class="my_menu_div">
                        <table class="my_menu_text"><tr><td>비밀번호 변경</td></tr></table>
                        <table class="my_menu_img2"><tr><td><font style="color:#999999;font-size:16px;"><b>〉</b></font></td></tr></table>
                </div>
                </a>

                <div class="my_menu_div2">
                        <table class="my_menu_text"><tr><td>알림수신</td></tr></table>
                        <table class="my_menu_img2">
				<tr>
                                        <td>
                                                <p>OFF</p>
                                                <p style="display:none;">ON</p>
                                        </td>
                                        <td>
                                                <label class="switch">
                                                        <input type="checkbox" 
                <?php
                                                        if ($is_push == 1) {
                                                                echo "checked";
                                                        }
                ?>
                                                        ><span class="slider round"></span>
                                                </label>
                                        </td>
				</tr>
			</table>
                </div>

		<div class="my_menu_div3">
			<table class="my_menu_text"><tr><td>
			<font style="font-size:13px;">*Off상태이면 예약관련 알림, 포인트 적립 등의 알림을 받을 수 없습니다.</font>
			</td></tr></table>
		</div>

<!--                <a href="<?=$login_directory?>/logout_process.php"> -->
                <div class="my_menu_div">
                        <table class="my_menu_text"><tr><td onclick="log_out();" style="cursor:point">로그아웃</td></tr></table>
                        <table class="my_menu_img2"><tr><td><img src="<?=$image_directory?>/logout.png" height="20px" style="opacity:0.5;"></td></tr></table>
                </div>
                </a>

		<br>
		<br>

                <a href="<?=$mainpage_directory?>/privacy_policy.php">
                <div class="my_menu_div">
                        <table class="my_menu_text"><tr><td>개인정보보호지침</td></tr></table>
                        <table class="my_menu_img2"><tr><td><font style="color:#999999;font-size:16px;"><b>〉</b></font></td></tr></table>
                </div>
                </a>

                <a href="<?=$mainpage_directory?>/terms_of_service.php">
                <div class="my_menu_div">
                        <table class="my_menu_text"><tr><td>이용약관</td></tr></table>
                        <table class="my_menu_img2"><tr><td><font style="color:#999999;font-size:16px;"><b>〉</b></font></td></tr></table>
                </div>
                </a>

                <!--a href="#">
                <div class="my_menu_div">
                        <table class="my_menu_text"><tr><td>앱버전정보 1.2.1</td></tr></table>
                        <table class="my_menu_img2"><tr><td><a href="#" class="download_app"><font style="color:#ffffff;">최신버전 받기</font></a></td></tr></table>
                </div>
                </a-->

                <br>
                <br>

                <a href="withdraw.php">
                <div class="my_menu_div">
                        <table class="my_menu_text"><tr><td>탈퇴하기</td></tr></table>
                        <table class="my_menu_img2"><tr><td><font style="color:#999999;font-size:16px;"><b>〉</b></font></td></tr></table>
                </div>
                </a>

</center>

<script>
function change_push()
{
        $.ajax({
                url: 'change_user_push.php',
                type: 'POST',
                success: function(data){
                       location.reload();
                },
                error : function(xhr, status, error) {
                }
        });
}

var check = $("input[type='checkbox']");
check.click(function(){
	$(".my_menu_img2 p").toggle();
	change_push();
});
var is_push = <?=$is_push?>;
if (is_push == 1) {
	$(".my_menu_img2 p").toggle();
}
</script>

<script>
	function log_out(){

		$.ajax({
			type		: "POST",
			url			: "<?=$login_directory?>/ajax_logout_pro.php",
			data		: {
				"id"			: "",
			},
			success: function(html){
				$("#logout_return_html").html(html);
			    location.href="<?=$mainpage_directory?>/index.php";
			}
		});
	}
</script>

<div id="logout_return_html"><div>
<?php include "../include/bottom.php"; ?>
