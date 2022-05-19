<?php
include "../include/top.php";
include "../include/Crypto.class.php";

$r_top = ($_GET['top'] && $_GET['top'] != "")? $_GET['top'] : "서울";
$r_middle = ($_GET['middle'] && $_GET['middle'] != "")? $_GET['middle'] : "";
$user_id = isset($_SESSION['gobeauty_user_id']) ? $_SESSION['gobeauty_user_id'] : "";
$user_name = isset($_SESSION['gobeauty_user_nickname']) ? $_SESSION['gobeauty_user_nickname'] : "";
/*
$data = array();
$sql = "
	SELECT DISTINCT top 
	FROM tb_region 
	WHERE open_flag = true
";
$result = mysql_query($sql);
while($row = mysql_fetch_assoc($result)){
	$data[] = $row;
}

$location_html = "";
foreach($data AS $key => $value){
	$location_html .= ($key % 3 == 0)? "<div>" : "";
	$location_html .= "<div class='location_top' data-region='".$value["top"]."'><button type='button'>".$value["top"]."</button></div>";
	$location_html .= ($key % 3 == 2)? "</div>" : "";
}
$location_html .= ($key % 3 != 2)? "</div>" : "";
*/
?>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<script src="<?= $js_directory ?>/fontawesome.min.js"></script>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=f2cf1f3b7e2ca88cb298196078ef550f"></script>
<style>
	/*#main-content .button_wrap { position: relative; margin-top: 30px; }*/
	#map { background:none!important; }
	.loading { position: absolute; left: 50%; top: 50%; width: 30px; height: 23px; margin-top: 7px; text-align: center; margin-left: -15px; margin-top: -15px; }
	.messagebox_overlay { z-index: 101; }
</style>
<div id="main-content" style="padding-bottom: 61px; padding-bottom: calc(constant(safe-area-inset-bottom) + 61px); padding-bottom: calc(env(safe-area-inset-bottom) + 61px);">
	<div id="fixed-menu">
		<div class="top_menu">
			<div class="header-back-btn"><a href="https://www.gopet.kr/pet/mainpage/index.php?tab=2"><img src="/pet/images/btn_back_2.png" width="26px"></a></div>
			<div class="top_title">
				<p>펫샵 검색</p>
			</div>
			<div class="top_reflash"><a href="<?=$mainpage_directory?>/"><img src="../images/reflash.png"></a></div>
		</div>
	</div>
	<div class="location_tab" style="margin-top:58px;">
		<div style="">
			<a href="javascript:;" class="tab on"  data-id='tab1'>지역 검색</a>
			<a href="javascript:;" class="tab" data-id='tab2'>내주변 검색</a>
		</div>
	</div>
	<div class="location_top_wrap">
		<input type="hidden" name="top" value="<?=$r_top ?>" />
		<input type="hidden" name="middle" value="<?=$r_middle ?>" />
		<div id="map" class="location_box" style="width:100%;min-height:50vh;position:relative;background:none!important;">
			<?php echo $location_html ?>
		</div>
	</div>
	<div class="button_wrap">
		<button type="button" class="location_select_btn">검색</button>
	</div>
</div>

<script>
var tab = "tab1";
var location_top = "<?=$r_top ?>";
var location_middle = "<?=$r_middle ?>";
var lat = "";
var lng = "";
var timerId = "";
var is_timer = 0;
var mapContainer = document.getElementById('map'), // 지도를 표시할 div 
	mapOption = { 
		center: new kakao.maps.LatLng(lat, lng), // 지도의 중심좌표
		draggable: false,
		disableDoubleClickZoom: true,
		level: 2 // 지도의 확대 레벨
	};

$(function(){
	add_top();
});

$(document).on('click', '#main-content .tab', function(){
	tab = $(this).data('id');
	console.log(tab);
	$('#main-content .tab').removeClass('on');
	$('#main-content .tab[data-id="'+tab+'"]').addClass('on');

	if(tab == "tab1"){
		add_top();
	}else{	// 내주변 검색
		myLocate();
	}
});

$(document).on('click', '.loading', function(){
	myLocate();
});

function gridMap(){
	$('#main-content div.location_box').html('<div class="loading"><i class="fas fa-redo-alt"></i></div>');
	if((lat == "" || lat == "0" || lat == "0.0") && (lng == "" || lng == "0" || lng == "0.0")){
		//myLocate();
		//$.MessageBox("<center>위치 서비스를 사용할 수 없습니다.<br/>잠시 후 다시 시도해주세요.</center>");
		//clearInterval(timerId);
	}else{
		//$.MessageBox("<center>준비중입니다.("+lat+"/"+lng+")</center>");
		mapOption = { 
			center: new kakao.maps.LatLng(lat, lng), // 지도의 중심좌표
			draggable: false,
			disableDoubleClickZoom: true,
			level: 2 // 지도의 확대 레벨
		};
		// 지도를 표시할 div와  지도 옵션으로  지도를 생성합니다
		var map = new kakao.maps.Map(mapContainer, mapOption); 
		map.setZoomable(false);
		// 마커가 표시될 위치입니다 
		var markerPosition  = new kakao.maps.LatLng(lat, lng); 

		// 마커를 생성합니다
		var marker = new kakao.maps.Marker({
			position: markerPosition
		});

		// 마커가 지도 위에 표시되도록 설정합니다
		marker.setMap(map);

		clearInterval(timerId);
		is_timer = 0;
	}
}

function locationLatitude(_lat){
	lat = _lat;
	//alert("lat:"+_lat);
}

function locationLongitude(_lng){
	lng = _lng;
	//alert("lng:"+_lng);
}

function isGps(_data){
	// to do something..
	//alert(_data);
	if(_data == "false"){
		$.MessageBox("GPS가 비활성화 상태입니다. GPS를 활성화 하세요.");
	}else{
		window.webkit.messageHandlers.myLocate.postMessage('a');
		window.webkit.messageHandlers.myLocate.postMessage('');
	}
}

//웹에서 호출하는 좌표구하기 함수 
function myLocate(){ 
	var tmp_val = checkMobile2();
	
	if(tmp_val == "in_app_and"){	//

		lat = window.Android.myLocate('lat'); 
		lng = window.Android.myLocate(null); 
		<? /*// 20210602 by migo - console.log() 사용시, GPS 내위치 조회에서 오류가 발생함, 사용하면 안 됨 */ ?>

	}else if(tmp_val == "android"){
		// to do something..
		getLocation();
	}else if(tmp_val == "in_app_ios"){
		window.webkit.messageHandlers.isGps.postMessage('a');
		//alert("in_app_ios");
	}else if(tmp_val == "ios"){
		// to do something..
		getLocation();
	}else{
		//모바일 기기가 아니라면
		getLocation();
	}
	
	if(is_timer == 0){
		timerId = setInterval(gridMap, 1000);
		is_timer++;
	}
	return true;
}

function getLocation() {	
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(showPosition, err);	
	} else {
		alert("위치 정보 서비스를 지원하지 않는 기기입니다.");
		//$.MessageBox("Geolocation is not supported by this browser.");
	}
}

function err(e){
	var errors = {
		1: '위치 정보 권한이 없습니다.\n휴대폰 설정에서 위치(GPS)사용을 허용해주세요.',
		2: '위치 서비스를 사용할 수 없습니다.\n잠시 후 다시 시도해주세요.',
		3: '요청시간을 초과하였습니다.\n잠시 후 다시 시도해주세요.'
	};
	alert("Error: " + errors[e.code]);
}

function test2(obj){
	var str = "";
	for( var i in obj ){
		str += ", " + i + " : '" + obj[i] + "'";
	}
	str = str.substring(1, str.length);
	return "{"+ str +"}";
}

function showPosition(position) {
	lat = position.coords.latitude;
	lng = position.coords.longitude;
	//alert(lat+'/'+lng);
	console.log('showPisition() > ' + lat +'-'+ lng);
}

function checkMobile2(){
    var varUA = navigator.userAgent.toLowerCase(); //userAgent 값 얻기
    if ( varUA.indexOf("app_gobeauty_and") > -1 ) {
        //APP
		return "in_app_and";
	} else if (varUA.indexOf("app_gobeauty_ios") > -1 ) {
        //안드로이드
        return "in_app_ios";
	} else if ( varUA.indexOf('android') > -1 ) {
        //안드로이드
        return "android";
    } else if ( varUA.indexOf("iphone") > -1||varUA.indexOf("ipad") > -1||varUA.indexOf("ipod") > -1 ) {
        //IOS
        return "ios";
    } else {
        //아이폰, 안드로이드 외
        return "other";
    }
}

$(document).on('click', '#main-content .location_top', function(){
	var top_region = $(this).data('region');
	if($(this).hasClass('on')){
		$(this).removeClass('on');
		$('#main-content .location_middle_wrap').remove();
	}else{
		$('#main-content .location_top').removeClass('on');
		$(this).addClass('on');
		add_middle(top_region);
	}
});

$(document).on('click', '#main-content .location_middle', function(){
	var top = $(this).data('top');
	var middle = $(this).data('middle');
	$('#main-content .location_middle').removeClass('on');
	$(this).addClass('on');
	$('#main-content input[name="top"]').val(top);
	$('#main-content input[name="middle"]').val(middle);
});

$(document).on('click', '#main-content .location_select_btn', function(){
	if(tab == "tab1"){
		var top = $('#main-content input[name="top"]').val();
		var middle = $('#main-content input[name="middle"]').val();
		if(top != "" && middle != ""){
			location.href = '<?=$mainpage_directory?>/index.php?tab=2&top='+top+'&middle='+middle;
		}else{
			$.MessageBox("지역을 선택해주세요.");
		}
	}else{
		if((lat == "" || lat == "0" || lat == "0.0") && (lng == "" || lng == "0" || lng == "0.0")){
			//$.MessageBox("위치 데이터를 불러오는 중입니다.");
			myLocate();
		}else{
			location.href = '<?=$mainpage_directory ?>/?tab=2&lat='+lat+'&lng='+lng;
		}
	}
});

function add_top() {
	$.ajax({
		url: '<?=$mainpage_directory ?>/index_ajax.php',
		data: {
			mode : 'get_location_top'
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == '000000'){
				//console.log(data.data);
				var html = '';
				var top_region = (location_top != "")? location_top : "서울";

				$.each(data.data, function(i, v){
					html += (i % 3 == 0)? '<div>' : '';
					html += '<div class="location_top" data-region="'+v.top+'"><button type="button">'+v.top+'</button></div>';
					html += (i % 3 == 2)? '</div>' : '';
				});
				//html += (i % 3 != 2)? '</div>' : '';
				
				$('#main-content div.location_box').html(html);
				$('#main-content div.location_top[data-region="'+top_region+'"]').addClass('on');
				add_middle(top_region);
			}else{
				alert(data.data+'('+data.code+')');
				console.log(data.data);
			}
		},
		error: function(xhr, status, error) {}
	});
}

function add_middle(top_region) {
	$.ajax({
		url: '<?=$mainpage_directory ?>/index_ajax.php',
		data: {
			mode : 'get_location_middle',
			top_region : top_region
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == '000000'){
				//console.log(data.data);
				var html = '';

				html += '<div class="location_middle_wrap">';
				$.each(data.data, function(i, v){
					if(v.middle == "창원시 마산회원구"){
						html += '<button type="button" class="location_middle" data-top="'+top_region+'" data-middle="'+v.middle+'" style="font-size: 12px;">'+v.middle+'</button>';
					}else{
						html += '<button type="button" class="location_middle" data-top="'+top_region+'" data-middle="'+v.middle+'">'+v.middle+'</button>';
					}
				});
				html += '<div class="info_text"> * 입점샵이 있는 지역만 표시됩니다.</div>';
				html += '</div>';
				
				$('#main-content .location_middle_wrap').remove();
				$('#main-content div.location_top[data-region="'+top_region+'"]').parent().after(html);
				// 전달된 값이 있으면 선택처리
				if(location_top != "" && location_middle != ""){
					$('#main-content .location_middle[data-middle="'+location_middle+'"]').addClass('on');
				}
			}else{
				alert(data.data+'('+data.code+')');
				console.log(data.data);
			}
		},
		error: function(xhr, status, error) {}
	});
}
</script>

<?php include "../include/bottom.php"; ?>
