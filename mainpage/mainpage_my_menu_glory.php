<?php
include "../include/top.php";
include "../include/Crypto.class.php";
include "../include/App.class.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

$is_android = 0;
$app = new App();
if ($app->is_app() == 1) {
    $is_android = 1;
}

$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

// 권한 체크
if($user_id != ""){
	$sql = "
		SELECT *
		FROM tb_customer
		WHERE id = '".$user_id."'
	";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	$admin_flag = $row["admin_flag"];
	$operator_flag = $row["operator_flag"];
	$my_shop_flag = $row["my_shop_flag"];
	$artist_flag = $row["artist_flag"];
	$photo = $row["photo"];
	$nickname = $row["nickname"];
	$id = $row["id"];
	$pet_seq = $row["pet_seq"];
	$cellphone = $row["cellphone"];
	$crypto = new Crypto();
    $cellphone = $crypto->decode(trim($cellphone), $access_key, $secret_key);

}
?>
<script>
    function check_next() {
        var nickname = document.getElementById('user_nickname').value;
        var old_nickname = "<?php echo $nickname ?>"
        if (!nickname || nickname == old_nickname ) {
//            return false;
			location.href="/pet/mainpage/index.php";
        } else {

			var YokList = new Array('개새끼', '개색기', '개색끼', '개자식', '개보지', '개자지', '개년', '개걸래', '개걸레', '씨발', '씨팔', '씨부랄', '씨바랄', '씹창', '씹탱', '씹보지', '씹자지', '씨방세', '씨방새', '씨펄', '시펄', '>십탱', '씨박', '썅', '쌍놈', '쌍넘', '싸가지', '쓰벌', '씁얼', '상넘이', '상놈의', '상놈이', '상놈을', '좆', '좃', '존나게', '존만한', '같은년', '넣을년', '버릴년', '부랄년', '바랄년', '미친년', '니기미', '니미씹', '니미씨', '니미럴', '니미랄', '호로', '후레아들', '호로새끼', '후레자식', '후래자식', '후라들년', '후라들넘', '빠구리', 'script', 'iframe', '병신', '보지', '자지', '씨.발', '졸라', '씹', '씹할', 'Go뷰티', '뷰티고', '뷰티야', 'go뷰티', 'Go펫', 'GO펫', '고펫', '반짝', '관리자');
			var Tmp;
			for (i = 0; i < YokList.length; i++) {
				Tmp = nickname.toLowerCase().indexOf(YokList[i]);
				if (Tmp >= 0) {
					$.MessageBox({
						buttonDone: "확인",
						message: "<center><font style='font-size:15px;font-weight:bold;'>금지어(" + YokList[i] + ")가 포함되어 있습니다.<br> 다시 작성해주세요.</font></center>"
					}).done(function() {});
					return false;
				}
			}
			var con = confirm("닉네임을 변경하시겠습니까?\n("+nickname+")");
			if (con == true) {
				document.getElementById('shop_form').submit();
			} else {
				location.href="/pet/mainpage/mainpage_my_menu.php"
			}
		}
        return true;
    }
</script>
<script type="text/javascript" src="<?= $js_directory ?>/auto_login.js"></script>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new_2.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new_2.css') ?>">
<link rel="stylesheet" href="<?= $css_directory ?>/m_layout_lisa.css?<?= filemtime($upload_static_directory . $css_directory . '/m_layout_lisa.css') ?>">
<style>
	#mainpage_my_menu { max-width: 400px; margin: 60px auto; background-color: #fcfcfc; font-family: 'NL2GR'; }
	#mainpage_my_menu .my_shop_div { background-color: #ffebc0; }
	#cs_shop_index { box-sizing: border-box; width: 100%; height:100%; margin: 30px auto; }
	#cs_shop_index .shop_menu_wrap { display: flex; justify-content: flex-start; flex-wrap: wrap; align-items: flex-start; margin:0 auto; width: calc(100% - 20px); padding-bottom: 60px; }
	#cs_shop_index .flex-item { position: relative; flex: 1 1 33%; color: #fff; text-align: center; box-sizing: border-box; white-space: nowrap; }
	#cs_shop_index .flex-item .inner { width: 75px; height: 75px; border:1px solid #332600; border-radius: 100%; margin:15px auto 40px; }
	#cs_shop_index .flex-item .inner img { width: calc(100% - 40px); min-width: 35px; min-height: 35px; margin-top: 20px; }
	#cs_shop_index .flex-item .inner h5 { padding-top: 30px; font-family: 'NL2GR'; font-weight: normal; }
	#cs_shop_index .flex-item2 { flex: 1 1 50%; margin-top: 40px; }
	#cs_shop_index .flex-item2:after { content: ''; background-color: #fffbe3; display: inline-block; width: 140px; height: 100px; border-radius: 30px; position: absolute; left: 50%; top: 0px; margin-left: -70px; z-index: 0; }
	#cs_shop_index .flex-item2 .inner2 { position: relative; z-index: 1; padding: 20px 0px; }
	#cs_shop_index .flex-item2 .inner2 img { width: 60px; }
	#cs_shop_index .flex-item2 .inner2 h5 { padding-top: 35px; font-family: 'NL2GR'; font-weight: normal; }
	#mainpage_my_menu .my_tester_div { background-color: #5F5243; color: #fff; }
	#mainpage_my_menu .my_tester_div .my_shop_text { color: #fff; }
	#mainpage_my_menu .my_tester_div img { filter: opacity(0.5) drop-shadow(0px 0px 0px #fff); }
</style>




<div id="mainpage_my_menu">
	<div class="mymenu_wrap">
	<?php
	if ($admin_flag || $operator_flag) {
	?>
		<a href="<?= $admin_directory ?>/index.php">
			<div class="my_admin_div">
				<div class="my_admin_icon"><img src="../images/new_admin_icon.png"></div>
				<div class="my_admin_text">관리자 모드</div>
				<img src="<?= $image_directory ?>/new_myshop_btn.png" class="my_admin_img">
			</div>
		</a>
	<?php
	}
	?>
	<?php
	if ($my_shop_flag) {
	?>
		<a href="<?= $shop_directory ?>/index.php?artist_id=<?= urlencode($user_id) ?>">
			<div class="my_shop_div">
				<div class="my_shop_icon"><img src="../images/new_shop_icon.png"></div>
				<div class="my_shop_text">MY SHOP</div>
				<img src="<?= $image_directory ?>/new_myshop_btn.png" class="my_shop_img">
			</div>
		</a>
	<?php
	}
	?>
	<?php
	if ($artist_flag == "1") {
	?>
		<a href="<?= $shop_directory ?>/manage_sell_info.php">
			<div class="my_shop_div">
				<div class="my_shop_text">
					<div>
						<div>SHOP 예약 접수</div>
					</div>
				</div>
				<img src="<?= $image_directory ?>/new_myshop_btn.png" class="my_shop_img">
			</div>
		</a>
	<?php
	}
	?>
	<?php
	if ($my_shop_flag) {
	?>
		<a href="<?= $shop_directory ?>/manage_counseling_request.php">
			<div class="my_counseling_div">
				<div class="my_shop_icon"><img src="../images/new_shop_info.png"></div>
				<div class="my_shop_text">이용 상담 관리</div>
				<img src="<?= $image_directory ?>/new_myshop_btn.png" class="my_shop_img">
			</div>
		</a>
	<?php
	}
	?>
	<?php
	if ($user_id == "nakmsna@naver.com") { // 앱개발자용 테스트 페이지 링크 추가(개발 후 삭제 필수!)
	?>
		<a href="../test/test_item_product_page.php?no=PE-D-A01">
			<div class="my_tester_div">
				<div class="my_shop_icon"><img src="../images/n_menu01.png"></div>
				<div class="my_shop_text">테스트 결제</div>
				<img src="<?= $image_directory ?>/new_myshop_btn.png" class="my_shop_img">
			</div>
		</a>
	<?php
	}
	?>

<form action="save_user_info.php" id="shop_form" method="POST" onsubmit="return false">
        <div class="top_menu">
            <div class="top_back"><a href="#" onclick="return check_next ();"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
            <div class="top_title">
                <p>마이 반짝</p>
            </div>
<!--             <div class="top_save"><a href="#" onclick="return check_next ();" class="save_info">저장</a></div> -->
        </div>



        <center id="manage_user_info">
            <div style="width:100%; margin: 0px auto;">

                <div style="overflow:hidden;border:1px solid #665c40;margin:7px;padding-bottom:15px;box-sizing:border-box;margin-top:15px;">
                    <div width="40%" height="200px" valign="bottom" style="padding:10px;float:left;box-sizing:border-box;margin-right:8px;width:38%;margin-left:15px;margin-top:8px;">
                        <!--div id="main_front_text">사진이 없습니다.</div-->
                        <div><img id="main_front_image" width="100%"></div>
                        <div>
                            <?php
                                if ($photo) {
                                    ?>
                                <div class="profile_wrap"><img src="<?= $photo ?>" id="main_front_image" /></div>
                            <?php
                                } else {
                                    echo "<div class='profile_wrap'><img src='../images/mypage.png'/></div>";
                                }
                                ?>
                            <div width="100%" class="filebox">
                                <label>
                                    <span style="float:right;width:100%;" id="fileuparea">
                                        <button class="change_photo" type="button" name="button1" onclick="javascript:MemofocusNcursor('gobeauty_profile');">
                                            사진 변경
                                        </button>
                                    </span>
                                    <span style="float:right;width:100%;display:none;" id="fileuparea2">
                                        <button type="button" onclick="javascript:galleryup('gobeauty_profile');">
                                            <span class="gallery_load">사진 변경</span>
                                            <!-- <img src="<?= $image_directory ?>/photo3.png" align="absmiddle" style="width:33px">&nbsp; -->
                                        </button>
                                        <!-- <button type="button" onclick="javascript:cameraup('gobeauty_profile');">
                                            <img src="<?= $image_directory ?>/photo2.png" align="absmiddle" style="width:33px">&nbsp;
                                        </button> -->
                                    </span>
                                    <span id="fileuplodingarea" style="display:none;float:right">
                                    </span>

                                </label>
                            </div>
                        </div>
                        <div style="display:none;/*position:absolute;*/top:0px;float:left;">
						<input type="file" accept="image/*" name=imgupfile id=addimgfile></div>
                        <script language="javascript">
                            var last_upload_type = "gobeauty_profile";

                            $('#addimgfile').bind('change', function(e) {
                                $("#fileuparea").hide();
                                $("#fileuplodingarea").show();
                                var ext = $('#addimgfile').val().split('.').pop().toLowerCase();
                                if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                                    alert('gif,png,jpg,jpeg 파일만 업로드 할수 있습니다.');
                                    $("#fileuparea").show();
                                    $("#fileuplodingarea").hide();
                                    return;
                                }

                                var filename = $("input[name=imgupfile]")[0].files[0].name;
                                var newfilename = GetPhotoFilename('customer_photo', '<?= $user_id ?>', filename);
                                var formData = new FormData();

                                formData.append("myfile", $("input[name=imgupfile]")[0].files[0]);
                                formData.append("filepath", newfilename);
                                formData.append("petname", last_upload_type);

                                $.ajax({
                                    url: 'upload_user_photo.php',
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    type: 'POST',
                                    success: function(data) {
                                        // alert(data);

                                        $("#fileuparea").show();
                                        $("#fileuplodingarea").hide();

                                        if (/(MSIE|Trident)/.test(navigator.userAgent)) {
                                            // ie 일때 input[type=file] init.
                                            $("#addimgfile").replaceWith($("#addimgfile").clone(true));
                                        } else {
                                            // other browser 일때 input[type=file] init.
                                            $("#addimgfile").val("");
                                        }

                                        addwidth = " style='width:30%' ";
                                        mkimg_str = "<img src='/upload/" + newfilename + "' " + addwidth + ">";

                                        location.reload();
                                    },
                                    error: function(xhr, status, error) {
                                        // alert(error+"에러발생");
                                    }
                                });


                            });
                            //안드로이드 앱일경우애만 ... 실행..
                            $(document).ready(function() {
                                if (<?= $is_android ?> == 1) {
                                    $("#fileuparea").hide();
                                    $("#fileuparea2").show();
                                }
                            });

                            function galleryup(name) {
                                last_upload_type = name;
                                window.Android.openGallery();
                            }

                            function cameraup(name) {
                                last_upload_type = name;
                                window.Android.openCamera();
                            }

                            //안드에서 업로드가 끝나면 무조건 호출..
                            function uploadEnd(fileName) {
                                $("#fileuparea2").hide();
                                $("#fileuplodingarea").show();

                                var newfilename = GetPhotoFilename('appupload_photo', '<?= $user_id ?>', fileName);
                                var post_data = 'filepath=' + fileName + '&newfilepath=' + newfilename + '&petname=' + last_upload_type;

                                $.ajax({
                                    url: 'upload_user_photo_byapp.php',
                                    data: post_data,
                                    type: 'POST',
                                    success: function(data) {
                                        // alert(data);

                                        $("#fileuparea2").show();
                                        $("#fileuplodingarea").hide();

                                        if (/(MSIE|Trident)/.test(navigator.userAgent)) {
                                            // ie 일때 input[type=file] init.
                                            $("#addimgfile").replaceWith($("#addimgfile").clone(true));
                                        } else {
                                            // other browser 일때 input[type=file] init.
                                            $("#addimgfile").val("");
                                        }

                                        addwidth = " style='width:30%' ";
                                        mkimg_str = "<img src='/upload/" + newfilename + "' " + addwidth + ">";

                                        location.reload();
                                    },
                                    error: function(xhr, status, error) {
                                        //alert(error + "네트워크에러");
										if(xhr.status != 0){
											alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
										}
                                    }
                                });
                            }

                            function MemofocusNcursor(name) {
                                last_upload_type = name;

                                html = "<div id='upimgarea'></div>";
                                var sel, range;
                                if (window.getSelection) {
                                    // IE9 and non-IE
                                    sel = window.getSelection();
                                    if (sel.getRangeAt && sel.rangeCount) {
                                        range = sel.getRangeAt(0);
                                        range.deleteContents();

                                        // Range.createContextualFragment() would be useful here but is
                                        // non-standard and not supported in all browsers (IE9, for one)
                                        var el = document.createElement("div");
                                        el.innerHTML = html;
                                        var frag = document.createDocumentFragment(),
                                            node, lastNode;
                                        while ((node = el.firstChild)) {
                                            lastNode = frag.appendChild(node);
                                        }
                                        range.insertNode(frag);

                                        // Preserve the selection
                                        if (lastNode) {
                                            range = range.cloneRange();
                                            range.setStartAfter(lastNode);
                                            range.collapse(true);
                                            sel.removeAllRanges();
                                            sel.addRange(range);
                                        }
                                    }
                                } else if (document.selection && document.selection.type != "Control") {
                                    // IE < 9
                                    document.selection.createRange().pasteHTML(html);
                                }
                                $("#addimgfile").trigger("click");
                            }
                        </script>

                    </div>
                    <div width="60%" style="float:left;width:46%;">
                        <div width='100%' style="font-size:15px; margin: 0 auto;">
                            <div>
                                <div>
                                    <font style="font-size:15px;"><b><?= $name ?></b></font>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <div class="user_info">
                                        <div class="user_title"><b>닉네임</b></div>
                                        <div><input name="user_nickname" id="user_nickname" type="text" value="<?= $nickname ?>" placeholder="닉네임" required><?php if ($admin_flag) {
                                                                                                                                                                    echo "<br>[관리자]";
                                                                                                                                                                } ?></div>
                                        <div class="user_title">이메일ID</div>
                                        <div style="overflow:auto;"><?= $id ?></div>
                                        <div class="user_title">전화번호</div>
                                        <div style="text-decoration: none;"><?= $cellphone ?></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </form>

<!-- <div class="mypet" style="margin-top:30px; overflow:hidden;margin-left:30px;margin-bottom: -20px;"> -->
<!-- 	<div class="flex-item" style="float:left;margin-right:20px;"> -->
<!-- 		<a href="/pet/mainpage/manage_user_info_2.php"><img src="../images/plus.png" style="width:45px;margin-top:10px;"><h5 class="menu_name" style="margin-top:15px;">마이펫 등록</h5></a> -->
<!-- 	</div> -->
<!-- 	<div class="flex-item" style="float:left;margin-right:20px;"> -->
<!-- 		<a href="/pet/mainpage/manage_modify_mypet_2.php"><img src="../images/dog1.jpg" style="padding-left: 10px;width:60px;"></a> -->
<!-- 	</div> -->
<!-- 	<div class="flex-item" style="float:left;margin-right:20px;"> -->
<!-- 		<a href="/pet/mainpage/manage_modify_mypet_2.php"><img src="../images/cat1.jpg" style="padding-left: 10px;width:60px;"></a> -->
<!-- 	</div> -->
<!-- </div> -->
	<div class="mypet" style="margin-top:20px;margin-left:30px;margin-bottom: -35px;overflow-x:scroll;overflow-y:hidden;height:110px;display:flex;">
		<div class="flex-item" style="float:left;margin-right:20px; display:flex;">
	<?php
		$pet_sql_cnt = "select * from tb_mypet where customer_id = '" . $user_id . "' ;";
		$cnt_result = mysql_query($pet_sql_cnt);
		$cnt = mysql_fetch_array($cnt_result);
		if ($cnt == "") {
	?>
			<a href="/pet/mainpage/manage_user_info.php"><img src="../images/plus1.png" style="width:40px;margin-top:10px;"><h5 class="menu_name" style="margin-top:20px;color:#807f7f;">마이펫 등록</h5></a>
			<span style="float:left;margin-left:20px;line-height:110px;font-size:15px;color:#706f6f;">내 새꾸를 등록하세요 ~ </span>
	<?php	
		} else {
	?>
			<a href="/pet/mainpage/manage_user_info.php"><img src="../images/plus1.png" style="width:40px;margin-top:10px;"><h5 class="menu_name" style="margin-top:20px;">마이펫 등록</h5></a>
	<?php
		}
	?>
		</div>	
	<?php
		$pet_sql = "select * from tb_mypet where customer_id = '" . $user_id . "' order by '" . $pet_seq ."' asc  ;";
		$pet_result = mysql_query($pet_sql);
		
		while ($pet_rows = mysql_fetch_object($pet_result)) {
			$name_for_owner = $pet_rows->name_for_owner;
			$type = $pet_rows->type;
			$pet_type = $pet_rows->pet_type;
			$pet_type2 = $pet_rows->pet_type2;
			$photo = $pet_rows->photo;
	?>
		<div class="flex-item" style="float:left;margin-right:20px;">			
			<a href="/pet/mainpage/manage_user_info.php">
				<?php
						if (!$photo || $photo == "") {
							if ($type == "dog") {
								echo "<img src='../images/dogicon.png' style='padding-left: 10px; width:50px;height:50px;border-radius:50%;float:left;margin-bottom:20px;' title='멍멍이'>";
				?>
				<?php
							} else if ($type == "cat") {
								echo "<img src='../images/caticon.png'style='padding-left: 10px; width:50px;height:50px;border-radius:50%;float:left;margin-bottom:20px;' title='야옹이'>";
							}
						} else {
							echo "<img src='$photo' width='50px' style='padding-left: 10px width:50px;height:50px;border-radius:50%;float:left; margin-bottom:20px;object-fit: cover;'>";
						}
				?>
			
			<h5 class="menu_name" style="margin-top:10px;">
				<?= $name_for_owner ?>		
			</h5>
			</a>
		</div>
	<?php
		}
	
	?>
	</div>
		<div id="cs_shop_index">
			<div class="shop_menu_wrap">
				<div class="flex-item"><a href="<?= $mainpage_directory ?>/manage_my_statement.php"><div class="inner"><img src="../images/n_menu01.png" style="padding-left: 10px;"><h5 class="menu_name">이용상담내역</h5></div></a></div>
				<div class="flex-item"><a href="<?= $mainpage_directory ?>/manage_my_reservation.php"><div class="inner"><img src="../images/n_menu02.png"><h5 class="menu_name">예약내역</h5></div></a></div>
				<div class="flex-item"><a href="<?= $item_directory ?>/item_order_list.php"><div class="inner"><img src="../images/n_menu03.png"><h5 class="menu_name">상품결제내역</h5></div></a></div>
				<div class="flex-item"><a href="<?= $mainpage_directory ?>/manage_my_coupon.php"><div class="inner"><img src="../images/n_menu04.png"><h5 class="menu_name">매장구입쿠폰</h5></div></a></div>
				<div class="flex-item"><a href="<?= $mainpage_directory ?>/manage_my_point.php"><div class="inner"><img src="../images/n_menu05_1.png"><h5 class="menu_name">포인트</h5></div></a></div>
				<div class="flex-item"><a href="<?= $mainpage_directory ?>/manage_my_postwrite.php"><div class="inner"><img src="../images/n_menu06.png"><h5 class="menu_name">내후기작성/변경</h5></div></a></div>
				<div class="flex-item"><a href="<?= $mainpage_directory ?>/manage_my_likeartist.php"><div class="inner"><img src="../images/n_menu07.png"><h5 class="menu_name new">나의단골펫샵</h5></div></a></div>
				<div class="flex-item"><a href="<?= $mainpage_directory ?>/manage_1vs1.php"><div class="inner"><img src="../images/n_menu08.png"><h5 class="menu_name">1대1문의</h5></div></a></div>
				<div class="flex-item"><a href="<?= $mainpage_directory ?>/manage_setting.php"><div class="inner"><img src="../images/n_menu09.png"><h5 class="menu_name">설정</h5></div></a></div>
				<div class="flex-item no_item"></div>
				<div class="flex-item no_item"></div>
			<?php
			if (!$my_shop_flag) {
			?>
				<div class="flex-item flex-item2"><a href="<?= $mainpage_directory ?>/view_event2.php"><div class="inner2"><img src="../images/n_menu11_2.png"><h5 class="menu_name">미용샵/<br>호텔입점신청</h5></div></a></div>
			<?php
			}
			?>
				<div class="flex-item flex-item2"><a href="<?= $mainpage_directory ?>/shop_entry.php"><div class="inner2"><img src="../images/n_menu12.png"><h5 class="menu_name">쇼핑입점/<br>제휴 광고문의</h5></div></a></div>
			</div>
		</div>
	</div>
</div>
<!--
<div class="navigation">
	<ul class="list">
		<li class="home on" ><a href="https://www.gopet.kr/pet/mainpage/index_lisa.php?tab=1"></a></li>
		<li class="beauty"><a href="https://www.gopet.kr/pet/mainpage/index_lisa.php?tab=2"></a></li>
		<li class="shopping"><a href="https://www.gopet.kr/pet/mainpage/index_lisa.php?tab=3"></a></li>
		<li class="search"><a href="https://www.gopet.kr/pet/mainpage/search_item_lisa.php"></a></li>
		<li class="morebtn"><a href="https://www.gopet.kr/pet/mainpage/mainpage_my_menu_glory.php"></a></li>
	</ul>
	<div>
		<ul class="text">
			<li>홈</li>
			<li>미용/호텔</li>
			<li>쇼핑</li>
			<li>검색</li>
			<li>마이반짝</li>
		</ul>
	</div>
</div>
-->
<!-- 20210531
<div class="navigation">
	<ul class="list">
		<li class="home <?=($_SERVER['PHP_SELF']=="/pet/mainpage/index_lisa.php" and ($_REQUEST['tab'] == "" or $_REQUEST['tab']==1))?"on":""?>"" ><a href="https://www.gopet.kr/pet/mainpage/index_lisa.php?tab=1"></a></li>
		<li class="beauty <?=($_SERVER['PHP_SELF']=="/pet/mainpage/index_lisa.php" and $_REQUEST['tab']==2)?"on":""?>"><a href="https://www.gopet.kr/pet/mainpage/index_lisa.php?tab=2"></a></li>
		<li class="shopping <?=($_SERVER['PHP_SELF']=="/pet/mainpage/index_lisa.php" and $_REQUEST['tab']==3)?"on":""?>""><a href="https://www.gopet.kr/pet/mainpage/index_lisa.php?tab=3"></a></li>
		<li class="search <?=($_SERVER['PHP_SELF']=="/pet/mainpage/search_item_lisa.php")?"on":""?>"><a href="https://www.gopet.kr/pet/mainpage/search_item_lisa.php"></a></li>
		<li class="morebtn <?=($_SERVER['PHP_SELF']=="/pet/mainpage/mainpage_my_menu_glory.php")?"on":""?>"><a href="https://www.gopet.kr/pet/mainpage/mainpage_my_menu_glory.php"></a></li>
	</ul>
	<div>
		<ul class="text">
			<li>홈</li>
			<li>미용/호텔</li>
			<li>쇼핑</li>
			<li>검색</li>
			<li>마이반짝</li>
		</ul>
	</div>
</div>
-->

<div class="navigation" style="margin-left:-1px;">
	<ul class="list">
		<li class="home <?=($_SERVER['PHP_SELF']=="/pet/mainpage/index_lisa.php" and ($_REQUEST['tab']=="" or $_REQUEST['tab']==1))?"on":""?>"" ><a href="https://gopet.kr/pet/mainpage/index_lisa.php?tab=1" style="margin-left:-8px;"><br><p style="margin-left:-8.5px;">홈</p></a></li>
		<li class="beauty <?=($_SERVER['PHP_SELF']=="/pet/mainpage/index_lisa.php" and $_REQUEST['tab']==2)?"on":""?>"><a href="https://gopet.kr/pet/mainpage/index_lisa.php?tab=2"><br><p style="margin-left:-8.5px;">미용 / 호텔</p></a></li>
		<li class="shopping <?=($_SERVER['PHP_SELF']=="/pet/mainpage/index_lisa.php" and $_REQUEST['tab']==3)?"on":""?>""><a href="https://gopet.kr/pet/mainpage/index_lisa.php?tab=3"><br><p style="margin-left:-8.5px;">쇼 핑</p></a></li>
		<li class="search <?=($_SERVER['PHP_SELF']=="/pet/mainpage/search_item_lisa.php")?"on":""?>"><a href="https://gopet.kr/pet/mainpage/search_item_lisa.php"><br><p style="margin-left:-8.5px;">검 색</p></a></li>
		<li class="morebtn <?=($_SERVER['PHP_SELF']=="/pet/mainpage/mainpage_my_menu_glory.php")?"on":""?>" ><a href="https://gopet.kr/pet/mainpage/mainpage_my_menu_glory.php" style="margin-left:-7px;"><br><p style="margin-left:-8.5px;">마 이 반 짝</p></a></li>
	</ul>
	<!--<div>
		<ul class="text">
			<li>홈</li>
			<li>미용/호텔</li>
			<li>쇼핑</li>
			<li>검색</li>
			<li>마이반짝</li>
		</ul>
	</div>-->
</div>
<script type="text/javascript" src="<?= $js_directory ?>/auto_login.js"></script>
<?php include "../include/bottom.php"; ?>