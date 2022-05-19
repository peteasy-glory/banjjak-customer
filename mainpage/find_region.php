<?php
	include "../include/top.php";
?>
<script src="https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js"></script>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=f2cf1f3b7e2ca88cb298196078ef550f&libraries=services"></script>
<div id="find_region">
	<div id="addr"></div>
	<div id="map" style="width:100%;height:100%;position:relative;overflow:hidden;"></div>
	<!--div class="hAddr"><span id="centerAddr"></span></div-->
	<div>
		
	</div>
</div>
<script>
	var post_data = '';
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
		sample3_execDaumPostcode();

		// 현재 지도 중심좌표로 주소를 검색해서 지도 좌측 상단에 표시합니다
		//searchAddrFromCoords(map.getCenter(), displayCenterInfo);
	});

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
				post_data = 'key=address&address='+data.zonecode+'|'+fullRoadAddr;
				addressSearch(fullRoadAddr);
				//add_cart(post_data);
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
			post_data += '&lat='+result[0].y+'&lng='+result[0].x;
			add_cart();
		}
		});
	}

	// 주소의 파라미터 값 변수로 가져오기
	function getParameterByName(name) {
		name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
			results = regex.exec(location.search);
		return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}
	var chk = getParameterByName('chk');
	console.log("result=" + chk);
	function add_cart(){
		console.log(post_data);
        $.ajax({
			url: '../artist/set_cart_session.php',
			data: post_data,
			type: 'POST',
			success: function(data){
				// alert(data);
				// opener.location.reload();
				// window.close();
				location.href = 'find_address.php?chk=' + chk;
			},
			error : function(xhr, status, error) {
			}
		});
	}
</script>
<?php
	include "../include/bottom.php";
?>