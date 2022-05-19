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

$squence = $_REQUEST['squence'];
if (!$squence) {
    $squence = 1;
}
?>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">

<div class="layer_pop" style="width:90%;left:5%;top:170px;display:none;opacity:1;" id="popl">
    <div class="layer_con">
        <p class="fwb" id="poplmsg">이름표 / 이메일을 입력하여 주세요.</p>
        <!--a href="javascript:closelayer();" class="layer_close"><img src="../images/layer/layer_close.jpg" alt="닫기"></a-->
    </div>
</div>

<div class="top_back"><a href="<?= $mainpage_directory ?>/manage_1vs1.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
<div class="top_title">
    <p>1:1 문의</p>
</div>


<center id="manage_1vs1_request">
    <div class="question_wrap">
        <font style="font-size:12px;">접수하신 문의는 최대한 빨리<br>조치하여 회신 드리도록 하겠습니다.</font>
    </div>

    <div class="question_menu">
        <div>
            <a href="?squence=1">
                <?php
                if ($squence == 1) {
                    ?>
                    <div class="menu_on">일반회원관련</div>
                <?php
                } else {
                    ?>
                    <div class="menu_off" style="color:#666;">일반회원관련</div>
                <?php
                }
                ?>
            </a>
        </div>
        <div align="right">
            <a href="?squence=2">
                <?php
                if ($squence == 1) {
                    ?>
                    <div class="menu_off" style="color:#666;">펫샵회원관련</div>
                <?php
                } else {
                    ?>
                    <div class="menu_on">펫샵회원관련</div>
                <?php
                }
                ?>
            </a>
        </div>
    </div>

    <!--form action="manage_1vs1_request_process.php" id="region_form" method="POST"-->
    <form action="#" id="region_form" method="POST">
        <div width="90%">
            <div>
                <div height="5px"></div>
            </div>
            <?php
            if ($squence == 1) {
                ?>
                <div class="menu_chk01">
                    <div>
                        <div class="checks"><input type="radio" name="errtype" id="err1" value="오류/장애"><label for="err1" style="font-family: 'NanumGothic';font-weight: bold;">오류/장애</label></div>
                    </div>
                    <div>
                        <div class="checks"><input type="radio" name="errtype" id="err2" value="예약/결제"><label for="err2" style="font-family: 'NanumGothic';font-weight: bold;">예약/결제</label></div>
                    </div>
                    <div>
                        <div class="checks"><input type="radio" name="errtype" id="err3" value="일반/기타"><label for="err3" style="font-family: 'NanumGothic';font-weight: bold;">기타</label></div>
                    </div>
                </div>
            <?php
            } else {
                ?>
                <div class="menu_chk02">
                    <div>
                        <div class="checks"><input type="radio" name="errtype" id="err11" value="판매상품등록"><label for="err11" style="font-family: 'NanumGothic';font-weight: bold;">판매상품등록</label></div>
                    </div>
                    <div>
                        <div class="checks"><input type="radio" name="errtype" id="err12" value="정산관련"><label for="err12" style="font-family: 'NanumGothic';font-weight: bold;">정산관련</label></div>
                    </div>
                    <div>
                        <div class="checks"><input type="radio" name="errtype" id="err13" value="판매자신청"><label for="err13" style="font-family: 'NanumGothic';font-weight: bold;">판매자신청</label></div>
                    </div>

                    <div>
                        <div class="checks"><input type="radio" name="errtype" id="err14" value="예약결제관련"><label for="err14" style="font-family: 'NanumGothic';font-weight: bold;">예약결제관련</label></div>
                    </div>
                    <div colspan="2">
                        <div class="checks"><input type="radio" name="errtype" id="err15" value="아티스트/기타"><label for="err15" style="font-family: 'NanumGothic';font-weight: bold;">기타</label></div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
        <div width="90%" style="border:0;font-size:14px;border-collapse: collapse;">

            <!--tr>
	  <div width="20%">
		<div class="textbox">이메일</div>
	  </div>
	  <div width="80%">
		<input type="text" id="1vs1_email" name="1vs1_email">
	  </div>
	 </div>
	 <div>
	  <div colspan="2"> <font style="font-size:10px;"> *문의에 대한 회신을 이메일로 받으실수 있습니다.</font></div>
	 </div-->
            <div class="question_title">
                <div>
                    <div class="textbox" style="padding: 10px 0;border-top: 1px dashed #999;">제목</div>
                </div>
                <div>
                    <input type="text" id="1vs1_title" name="1vs1_title" required>
                </div>
            </div>
            <div class="question_title_2">
                <div>
                    <textarea id="1vs1_body" name="1vs1_body" style="width:100%;height:100px;resize:none; padding: 0px; border:1px solid #999999;" required></textarea>
                </div>
            </div>
            <div>
                <div colspan="2"><input href="#" onclick="return submit_1vs1();" type="submit" class="request_1vs1" value="문의하기">
                    <div></div>
                </div>
    </form>
</center>

<script>
	var _loading = false; // 중복 방지

	$(function(){
		$("#loading").css("align-items", "center").css("justify-content", "center");
		$("#loading").html("<span style='color: #fff; max-width: 50%; text-align:center;'><img src='/pet/images/logo_loading0.gif' style='width: 100px; height: 100px;'/><br/><span style='color:#f5bf2e;'>반</span>려생활의 단<span style='color:#f5bf2e;'>짝</span></span>");
	});

    function closelayer() {
        $('#popl').fadeOut();
    }

    function innermsglayer(msg, left, top) {
        clearTimeout(timer);
        $('#poplmsg').html(msg);
        $('#popl').show();
        var timer = setTimeout(closelayer, 1500);
    }

    function ck_title() {
        var title = document.getElementById("1vs1_title").value;
        if (!title) {
            innermsglayer("제목을 입력해주세요.", "center", "center");
            return false;
        }
        return true;
    }

    function ck_body() {
        var title = document.getElementById("1vs1_body").value;
        if (!title) {
            innermsglayer("내용을 입력해주세요.", "center", "center");
            return false;
        }
        return true;
    }

    function submit_1vs1() {
        var selected_type = $('input[name="errtype"]:checked').val();
        if (!selected_type) {
            innermsglayer("문의 종류를 선택해주세요.", "center", "center");
            return false;
        }

        if (!ck_title()) {
            return false;
        }
        if (!ck_body()) {
            return false;
        }

        //var email_value = document.getElementById("1vs1_email").value;
        var title_value = document.getElementById("1vs1_title").value;
        var body_value = document.getElementById("1vs1_body").value;
			
		if(_loading == false){
			_loading = true;
			$("#loading").css("display", "flex");
			$.ajax({
				url: 'manage_1vs1_request_process.php',
				data: {
					type: selected_type,
					//			email : email_value,
					title: title_value,
					body: body_value
				},
				type: 'POST',
				success: function(data) {
					_loading = false;
					$("#loading").css("display", "none");
					innermsglayer(data, "center", "center");
					//setTimeout(function() {
						location.href = "manage_1vs1.php";
					//}, 2000);
				},
				error: function(xhr, status, error) {
					_loading = false;
					$("#loading").css("display", "none");
					innermsglayer("실패하였습니다. 관리자에게 연락 부탁드립니다.", "center", "center");
				}
			});
		}
		return false;
    }
</script>

<?php include "../include/bottom.php"; ?>