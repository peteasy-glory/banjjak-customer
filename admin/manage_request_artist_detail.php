<?php
include "../include/top.php";
include "../include/Crypto.class.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
	return false;
}
$r_artist_id = ($_GET["artist_id"] && $_GET["artist_id"] != "")? $_GET["artist_id"] : "";
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
?>
<script src="https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js"></script>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=f2cf1f3b7e2ca88cb298196078ef550f&libraries=services"></script>
<link rel="stylesheet" href="<?= $css_directory ?>/fancybox.min.css" />
<script src="<?= $js_directory ?>/fancybox.min.js"></script>
<style>
	ul { list-style: none; padding: 0px; margin: 0px; }
	input[type='text'] { background-color: transparent; border: 0px; border-bottom: 1px solid #ccc; padding: 5px; height: 30px; font-size: 18px; }
	input[type='checkbox'] { display: none; width: 0px; height: 0px; margin: 0px; padding: 0px; }
	input[type='checkbox']+label { display: inline-block; height: 30px; line-height: 30px; padding: 0px 5px; font-size: 18px; border: 1px solid #ccc; border-radius: 5px; }
	input[type='checkbox']:checked+label { background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
	select { border: 1px solid #ccc; background-color: #eee; height: 30px; padding: 0px 5px; border-radius: 5px; font-size: 16px; }
	button { border: 1px solid #ccc; background-color: #eee; border-radius: 5px; height: 40px; font-size: 16px; }
	textarea { border: 1px solid #ccc; font-size: 14px; }

	.top_menu {font-family: 'NL2GB'; position: fixed; left: 0px; top: 0px; width: 100%; height: 49px; line-height: 49px; border-bottom: 1px solid #ccc; background-color: rgba(255,255,255,0.8); z-index: 6; }
	.top_menu .top_back { position: absolute; left: 10px; top: 12px; width: 26px; }
	.top_menu .top_back img { width: 100%; }
	.top_menu .top_title { width: 100%; text-align: center; font-size: 24px; }

	#manage_request_artist_detail { width: 100%; margin: 60px 0px; }
	#manage_request_artist_detail ul li { padding: 5px; text-align: center; }
	#manage_request_artist_detail ul li .crypto { font-size: 10px; color: #ccc; }
	#manage_request_artist_detail ul li .title { font-size: 12px; color: #999; padding-bottom: 5px; text-align: left; }
	#manage_request_artist_detail ul li input[type='text'] { width: calc(100% - 10px); }
	#manage_request_artist_detail ul li textarea { width: calc(100% - 10px); height: 50px; }
	#manage_request_artist_detail ul li .pet_image { width: 60px; height: 60px; background-size: cover; background-position: center; }
	#manage_request_artist_detail button.search_address_btn { width: 100%; }
	#manage_request_artist_detail button.set_update_manage_request_artist_btn { width: 100%; background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; border-radius: 0px; }
</style>

<div class="top_menu">
	<div class="top_back">
		<a href="<?=($backurl != "")? $backurl : $admin_directory."/manage_artist_list.php" ?>">
			<img src="<?= $image_directory ?>/btn_back_2.png" />
		</a>
	</div>
	<div class="top_title">입점신청 관리</div>
</div>

<div id="manage_request_artist_detail">
</div>
<div id="manage_request_artist_popup" style="display: none;">
	<div id="addr"></div>
	<div id="map" style="width:1px;height:1px;position:relative;overflow:hidden;"></div>
</div>
<script>
	var $manage_request_artist_detail = $("#manage_request_artist_detail");
	var $manage_request_artist_popup = $("#manage_request_artist_popup");
	var artist_id = '<?=$r_artist_id ?>';

    // 우편번호 찾기 찾기 화면을 넣을 element
    var element_wrap = document.getElementById('addr');

	var mapContainer = document.getElementById('map'), // 지도를 표시할 div 
		mapOption = {
			center: new kakao.maps.LatLng(37.566826, 126.9786567), // 지도의 중심좌표
			level: 1 // 지도의 확대 레벨
		};  

	// 지도를 생성합니다    
	var map = new kakao.maps.Map(mapContainer, mapOption); 

	// 주소-좌표 변환 객체를 생성합니다
	var geocoder = new kakao.maps.services.Geocoder();

	var marker = new kakao.maps.Marker(), // 클릭한 위치를 표시할 마커입니다
		infowindow = new kakao.maps.InfoWindow({zindex:1}); // 클릭한 위치에 대한 주소를 표시할 인포윈도우입니다


	$(function(){
		manage_request_artist_html();
	});

	$manage_request_artist_detail.on("click", ".search_address_btn", function(){
		$manage_request_artist_popup.dialog({
			modal: true,
			title: "주소검색",
			autoOpen: true,
			width: "96%",
			height: $(window).height() - 40,
			autoSize: true,
			resizable: false,
			draggable: false,
				/*
			buttons: {
				"확인": function() {
					// 선택처리
					$(this).dialog("close");
				},
				"취소": function() {
					$(this).dialog("close");
				}
			},
				*/
			open: function(event, ui) {
				sample3_execDaumPostcode();
			},
			close: function() {
			}
		});
	});

	$manage_request_artist_detail.on("change", "select[name='top_region'], select[name='middle_region']", function(){
		var top_region = $manage_request_artist_detail.find("select[name='top_region'] option:selected").val();
		var middle_region = $manage_request_artist_detail.find("select[name='middle_region'] option:selected").val();
		
		get_top_region(top_region+":"+middle_region);
	});

	$manage_request_artist_detail.on("click", ".set_update_manage_request_artist_btn", function(){
		var post_data = $manage_request_artist_detail.find("#manage_request_artist_form").serialize();
		post_data += "&mode=set_update_manage_request_artist";
		post_data += "&customer_id="+artist_id;
		$manage_request_artist_detail.find("input[type='checkbox']").each(function(){
			var choice_service = [];
			if($(this).attr("name") != "choice_service"){
				if($(this).is(":checked") == false){
					console.log($(this).attr("name"));
					post_data += "&"+$(this).attr("name")+"=0";
				}else{
					console.log($(this).attr("name")+" -> checked ");
				}
			}
		});
		console.log(post_data);
		$.ajax({
			url: '<?=$admin_directory ?>/manage_request_artist_ajax.php',
			data: post_data,
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					$.MessageBox("수정 되었습니다.");

				}else{
					alert(data.data+"("+data.code+")");
					console.log(data.data);
				}
			},
			error: function(xhr, status, error) {
				//alert(error + "네트워크에러");
				if(xhr.status != 0){
					alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
				}
			}
		});
	});

	function manage_request_artist_html(){
		var html = '';
		html += '<div>';
		html += '	<form id="manage_request_artist_form" method="POST">';
		html += '		<ul>';
		html += '			<li>';
		html += '				<div class="title">이메일</div>';
		html += '				<div class="content customer_id">';
		html += '				</div>';
		html += '			</li>';
		html += '			<li>';
		html += '				<div class="title">이름</div>';
		html += '				<div class="content">';
		html += '					<input type="text" name="name" value="" />';
		html += '				</div>';
		html += '			</li>';
		html += '			<li>';
		html += '				<div class="title">연락처</div>';
		html += '				<div class="content">';
		html += '					<input type="text" name="cellphone" value="" />';
		html += '				</div>';
		html += '			</li>';
		html += '			<li>';
		html += '				<div class="title">개인 여부</div>';
		html += '				<div class="content" style="text-align: left;">';
		html += '					<input type="checkbox" id="is_personal" name="is_personal" value="1" />';
		html += '					<label for="is_personal">개인</label>';
		html += '				</div>';
		html += '			</li>';
		html += '			<li>';
		html += '				<div class="title">사업자 여부</div>';
		html += '				<div class="content" style="text-align: left;">';
		html += '					<input type="checkbox" id="is_business" name="is_business" value="1" />';
		html += '					<label for="is_business">사업자</label>';
		html += '				</div>';
		html += '			</li>';
		html += '			<li>';
		html += '				<div class="title">사업자번호 (\'-\'없이 10자)</div>';
		html += '				<div class="content">';
		html += '					<input type="text" name="business_number" value="" />';
		html += '				</div>';
		html += '			</li>';
		html += '			<li>';
		html += '				<div class="title">사업자등록증</div>';
		html += '				<div class="content business_license">';
		html += '					<span>첨부된 이미지가 없습니다.</span>';
		html += '				</div>';
		html += '			</li>';
		html += '			<li>'; // 계좌 수정 시작
		html += '				<div class="title">정산계좌 은행명</div>';
		html += '				<div class="content">';
		html += '					<input type="text" name="bank_bankname" value="" />';
		html += '				</div>';
		html += '			</li>';
		html += '			<li>';
		html += '				<div class="title">정산계좌 예금주명</div>';
		html += '				<div class="content">';
		html += '					<input type="text" name="bank_name" value="" />';
		html += '				</div>';
		html += '			</li>';
		html += '			<li>';
		html += '				<div class="title">정산계좌 번호</div>';
		html += '				<div class="content">';
		html += '					<input type="text" name="bank_number" value="" />';
		html += '				</div>';
		html += '			</li>'; // 계좌 수정 끝
		html += '			<li>';
		html += '				<div class="title">지역</div>';
		html += '				<div class="content" style="text-align: left;">';
		html += '					<select name="top_region">';
		html += '						<option value="">선택</option>';
		html += '					</select>';
		html += '					<select name="middle_region">';
		html += '						<option value="">선택</option>';
		html += '					</select>';
		html += '					<input type="text" name="region" value="" />';
		html += '				</div>';
		html += '			</li>';
		html += '			<li>';
		html += '				<div class="title">매장 사용여부</div>';
		html += '				<div class="content" style="text-align: left;">';
		html += '					<input type="checkbox" id="is_got_offline_shop" name="is_got_offline_shop" value="1" />';
		html += '					<label for="is_got_offline_shop">사용</label>';
		html += '				</div>';
		html += '			</li>';
		html += '			<li>';
		html += '				<div class="title">매장명</div>';
		html += '				<div class="content">';
		html += '					<input type="text" name="offline_shop_name" value="" />';
		html += '				</div>';
		html += '			</li>';
		html += '			<li>';
		html += '				<div class="title">매장번호</div>';
		html += '				<div class="content">';
		html += '					<input type="text" name="offline_shop_phonenumber" value="" />';
		html += '				</div>';
		html += '			</li>';
		html += '			<li>';
		html += '				<div class="title">매장주소</div>';
		html += '				<div class="content">';
		html += '					<button type="button" class="search_address_btn">검색</button>';
		html += '					<textarea name="offline_shop_address" value="" readonly ></textarea>';
		html += '					<input type="text" name="offline_shop_address_detail" value="" placeholder="상세주소 입력" />';
		html += '				</div>';
		html += '			</li>';
		html += '			<li>';
		html += '				<div class="title">lat</div>';
		html += '				<div class="content">';
		html += '					<input type="text" name="lat" value="" readonly />';
		html += '				</div>';
		html += '			</li>';
		html += '			<li>';
		html += '				<div class="title">lng</div>';
		html += '				<div class="content">';
		html += '					<input type="text" name="lng" value="" readonly />';
		html += '				</div>';
		html += '			</li>';
		html += '			<li>';
		html += '				<div class="title">서비스선택</div>';
		html += '				<div class="content" style="text-align: left;">';
		html += '					<input type="checkbox" id="choice_service_1" name="choice_service[]" value="1" />';
		html += '					<label for="choice_service_1">미용</label>';
		html += '					<input type="checkbox" id="choice_service_2" name="choice_service[]" value="2" />';
		html += '					<label for="choice_service_2">호텔</label>';
		html += '					<input type="checkbox" id="choice_service_3" name="choice_service[]" value="3" />';
		html += '					<label for="choice_service_3">유치원</label>';
		html += '				</div>';
		html += '			</li>';
		html += '		</ul>';
		html += '		<div class="btn_wrap">';
		html += '			<button type="button" class="set_update_manage_request_artist_btn">수정</button>';
		html += '		</div>';
		html += '	</form>';
		html += '</div>';

		$manage_request_artist_detail.html(html);
		get_manage_request_artist();
	}

	function get_manage_request_artist(){
		if(artist_id != ""){
			$.ajax({
				url: '<?=$admin_directory ?>/manage_request_artist_ajax.php',
				data: {
					mode: "get_manage_request_artist",
					artist_id: artist_id
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						$manage_request_artist_detail.find(".customer_id").html(data.data.customer_id+"<br/><span class='crypto'>"+data.data.crypto[0].customer_id+"</span>");
						$manage_request_artist_detail.find("input[name='name']").val(data.data.name);
						$manage_request_artist_detail.find("input[name='cellphone']").val(data.data.cellphone);
						if(data.data.is_personal == "1"){
							$manage_request_artist_detail.find("input[name='is_personal']").prop("checked", true);
						}
						if(data.data.is_business == "1"){
							$manage_request_artist_detail.find("input[name='is_business']").prop("checked", true);
						}
						$manage_request_artist_detail.find("input[name='business_number']").val(data.data.business_number);
						if(typeof data.data.business_license != "undefined" && data.data.business_license != ""){
							$manage_request_artist_detail.find(".business_license").html('<a data-fancybox="gallery" href="'+data.data.business_license+'"><div class="pet_image" style="background-image:url('+data.data.business_license+');"></div></a>');
						}
						// 계좌 정보 뿌려주기
						$manage_request_artist_detail.find("input[name='bank_bankname']").val(data.data.bank_bankname);
						$manage_request_artist_detail.find("input[name='bank_name']").val(data.data.bank_name);
						$manage_request_artist_detail.find("input[name='bank_number']").val(data.data.bank_number);
						if(data.data.region != ""){
							get_top_region(data.data.region);
						}
						if(data.data.is_got_offline_shop == "1"){
							$manage_request_artist_detail.find("input[name='is_got_offline_shop']").prop("checked", true);
						}
						$manage_request_artist_detail.find("input[name='offline_shop_name']").val(data.data.offline_shop_name);
						$manage_request_artist_detail.find("input[name='offline_shop_phonenumber']").val(data.data.offline_shop_phonenumber);
						$manage_request_artist_detail.find("textarea[name='offline_shop_address']").val(data.data.offline_shop_address);
						$manage_request_artist_detail.find("input[name='lat']").val(data.data.lat);
						$manage_request_artist_detail.find("input[name='lng']").val(data.data.lng);
						if(data.data.choice_service && data.data.choice_service != "" && data.data.choice_service.indexOf(',') != -1){
							var tmp_cs = data.data.choice_service.split(',');
							for(var _i = 0; _i <= tmp_cs.length; _i++){
								$manage_request_artist_detail.find("input[name='choice_service[]'][value='"+tmp_cs[_i]+"']").prop("checked", true);
							}
						}
					}else{
						alert(data.data+"("+data.code+")");
						console.log(data.data);
					}
				},
				error: function(xhr, status, error) {
					//alert(error + "네트워크에러");
					if(xhr.status != 0){
						alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
					}
				}
			});
		}
	}

	function get_top_region(region){
		$.ajax({
			url: '<?=$admin_directory ?>/manage_request_artist_ajax.php',
			data: {
				mode: "get_top_region"
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					var html = '';
					if(data.data.length > 0){
						$.each(data.data, function(i, v){
							html += '<option value="'+v.top+'">'+v.top+'</option>';										
						});
						$manage_request_artist_detail.find("select[name='top_region']").html(html);
					}

					//세종시 예외처리
					if(region.split(':')[0] == "세종"){
						region = "세종:세종시";
					}

					get_middle_region(region);
				}else{
					alert(data.data+"("+data.code+")");
					console.log(data.data);
				}
			},
			error: function(xhr, status, error) {
				//alert(error + "네트워크에러");
				if(xhr.status != 0){
					alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
				}
			}
		});
	}

	function get_middle_region(region){
		var tmp_re = region.split(':');
		
		$.ajax({
			url: '<?=$admin_directory ?>/manage_request_artist_ajax.php',
			data: {
				mode: "get_middle_region",
				top: tmp_re[0]
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					var html = '';
					if(data.data.length > 0){
						$.each(data.data, function(i, v){
							html += '<option value="'+v.middle+'">'+v.middle+'</option>';										
						});
						$manage_request_artist_detail.find("select[name='middle_region']").html(html);
						$manage_request_artist_detail.find("select[name='top_region']").val(tmp_re[0]);
						$manage_request_artist_detail.find("select[name='middle_region']").val(tmp_re[1]);
						$manage_request_artist_detail.find("input[name='region']").val(region);
					}
				}else{
					alert(data.data+"("+data.code+")");
					console.log(data.data);
				}
			},
			error: function(xhr, status, error) {
				//alert(error + "네트워크에러");
				if(xhr.status != 0){
					alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
				}
			}
		});
	}

    function foldDaumPostcode() {
        // iframe을 넣은 element를 안보이게 한다.
        element_wrap.style.display = 'none';
    }

	function sample3_execDaumPostcode() {
        // 현재 scroll 위치를 저장해놓는다.
        var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
        new daum.Postcode({
            oncomplete: function(data) {
                // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 도로명 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var fullRoadAddr = data.roadAddress; // 도로명 주소 변수
                var extraRoadAddr = ''; // 도로명 조합형 주소 변수

                // 법정동명이 있을 경우 추가한다. (법정리는 제외)
                // 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
                if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
                    extraRoadAddr += data.bname;
                }
                // 건물명이 있고, 공동주택일 경우 추가한다.
                if(data.buildingName !== '' && data.apartment === 'Y'){
                   extraRoadAddr += (extraRoadAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                }
                // 도로명, 지번 조합형 주소가 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
                if(extraRoadAddr !== ''){
                    extraRoadAddr = ' (' + extraRoadAddr + ')';
                }
                // 도로명, 지번 주소의 유무에 따라 해당 조합형 주소를 추가한다.
                if(fullRoadAddr !== ''){
                    fullRoadAddr += extraRoadAddr;
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                //opener.document.getElementById('address_td').innerHTML = '<table width="100%"><tr><td><a onclick="open_payment_type();">'+data.zonecode+'<br>'+fullRoadAddr+'</a></td><td align="right"><img src="/images/find.png" width="25px" onclick="open_payment_type()"></td></tr></table>'; //5자리 새우편번호 사용
				//post_data = 'key=address&address='+data.zonecode+'|'+fullRoadAddr;
				//add_cart(post_data);
				$manage_request_artist_detail.find('textarea[name="offline_shop_address"]').val(data.zonecode+'|'+fullRoadAddr);
				var loadAddr = fullRoadAddr.split(' ')[0];
				if(loadAddr == "경기"){
					loadAddr = "경기도";
				}else if(loadAddr == "세종특별자치시"){
					loadAddr = "세종";
				}else if(loadAddr == "강원"){
					loadAddr = "강원도";
				}else if(loadAddr == "충남"){
					loadAddr = "충청남도";
				}else if(loadAddr == "충북"){
					loadAddr = "충청북도";
				}else if(loadAddr == "경남"){
					loadAddr = "경상남도";
				}else if(loadAddr == "경북"){
					loadAddr = "경상북도";
				}else if(loadAddr == "전남"){
					loadAddr = "전라남도";
				}else if(loadAddr == "전북"){
					loadAddr = "전라북도";
				}else if(loadAddr == "제주특별자치도"){
					loadAddr = "제주도";
				}

				get_top_region(loadAddr+":"+fullRoadAddr.split(' ')[1]);
				$manage_request_artist_detail.find("input[name='region']").val(loadAddr+":"+fullRoadAddr.split(' ')[1]);

				addressSearch(fullRoadAddr);
				/*              
				opener.document.getElementById('request_postcode').value = data.zonecode; //5자리 새우편번호 사용
                opener.document.getElementById('request_address1').value = data.roadAddress;
                opener.document.getElementById('request_address2').value = data.jibunAddress;
				*/
				// (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
                element_wrap.style.display = 'none';

                // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
                document.body.scrollTop = currentScroll;

				/*
				if(data.addressType === 'R') {
					opener.document.getElementById("request_address").value = fullRoadAddr;
				} else {
					opener.document.getElementById("request_address").value = data.jibunAddress;
				}
				*/
				//		opener.window.reload();
				//		window.close();

            },
            // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분. iframe을 넣은 element의 높이값을 조정한다.
            onresize : function(size) {
                element_wrap.style.height = size.height+'px';
            },
            width : '100%',
            height : '100%'
        }).embed(element_wrap);

        // iframe을 넣은 element를 보이게 한다.
        element_wrap.style.display = 'block';
    }

	function searchAddrFromCoords(coords, callback) {
		// 좌표로 행정동 주소 정보를 요청합니다
		geocoder.coord2RegionCode(coords.getLng(), coords.getLat(), callback);         
	}

	function searchDetailAddrFromCoords(coords, callback) {
		// 좌표로 법정동 상세 주소 정보를 요청합니다
		geocoder.coord2Address(coords.getLng(), coords.getLat(), callback);
	}

	// 지도 좌측상단에 지도 중심좌표에 대한 주소정보를 표출하는 함수입니다
	function displayCenterInfo(result, status) {
		if (status === kakao.maps.services.Status.OK) {
			var infoDiv = document.getElementById('centerAddr');

			for(var i = 0; i < result.length; i++) {
				// 행정동의 region_type 값은 'H' 이므로
				if (result[i].region_type === 'H') {
					infoDiv.innerHTML = result[i].address_name;
					break;
				}
			}
		}    
	}

	function addressSearch(search_addr){
		// 주소로 좌표를 검색합니다
		geocoder.addressSearch(search_addr, function(result, status) {

		// 정상적으로 검색이 완료됐으면 
		if (status === kakao.maps.services.Status.OK) {
			var coords = new kakao.maps.LatLng(result[0].y, result[0].x);
			// 결과값으로 받은 위치를 마커로 표시합니다
			var marker = new kakao.maps.Marker({
				map: map,
				position: coords
			});
			// 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
			map.setCenter(coords);
			// 인포윈도우로 장소에 대한 설명을 표시합니다
			var infowindow = new kakao.maps.InfoWindow({
				content: '<div style="width:150px;text-align:center;padding:6px 0;">'+map.getCenter()+'</div>'
			});
			infowindow.open(map, marker);
			$manage_request_artist_detail.find('input[name="lat"]').val(result[0].y);
			$manage_request_artist_detail.find('input[name="lng"]').val(result[0].x);
			//post_data += '&lat='+result[0].y+'&lng='+result[0].x;
			//alert('&lat='+result[0].y+'&lng='+result[0].x);
			$manage_request_artist_popup.dialog("close");
		}
		});
	}
</script>
