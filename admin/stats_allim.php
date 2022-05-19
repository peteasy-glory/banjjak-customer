<?php
include "../include/top.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

$n_date = ($_GET["search_date"] && $_GET["search_date"] != "")? $_GET["search_date"] : Date('Y-m-d');

$now_dt = DATE("Y-m-d");
$n_year = date("Y", strtotime($now_dt));
$n_month = date("m", strtotime($now_dt));
$n_day = date("d", strtotime($now_dt));

$ivm_arr = array();
for($_i = 1; $_i <= 6; $_i++){
	if($r_ivm1 != ""){
		$ivm_arr[$_i] = substr($r_ivm1, ($_i - 1), 1);
	}else{
		$ivm_arr[$_i] = 0;
	}
}
?>

<script src="<?= $js_directory ?>/fontawesome.min.js"></script>
<style>
	ul { list-style: none; padding: 0px; margin: 0px; }
	.bjj_top_menu { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; border-bottom: 1px solid #ccc; background-color: rgba(255, 255, 255, 0.8); margin-top: env(safe-area-inset-top); margin-top: constant(safe-area-inset-top); z-index: 10; }
	.bjj_top_menu .bjj-back-btn { position: absolute; left: 8px; top: 12px; }
	.bjj_top_menu .bjj-back-btn img { width: 26px; vertical-align: middle; }
	.bjj_top_menu .bjj_top_title { width: 100%; height: 50px; line-height: 50px; text-align: center; padding: 0px; }
	.bjj_top_menu .bjj_top_title p { padding: 0px; margin: 0px; }

	#item_list { position: relative; width: 100%; margin-top: calc(env(safe-area-inset-top) + 61px); margin-top: calc(constant(safe-area-inset-top) + 61px); }
	#item_list .top_wrap { position: relative; padding: 10px; }
	#item_list .top_wrap button.add_jb_item_btn { height: 40px; line-height: 40px; border: 1px solid #047C3C; background-color: #047C3C; color: #fff; border-radius: 5px; padding: 0px 10px; }
	#item_list .top_wrap button.add_item_btn { position: absolute; right: 10px; top: 10px; height: 40px; line-height: 40px; border: 1px solid #f5bf2e; background-color: #f5bf2e; color: #fff; border-radius: 5px; padding: 0px 10px; }
	#item_list .search_wrap { margin-bottom: 20px; padding: 10px; border: 1px solid #ccc; }
	#item_list .search_wrap .title { font-size: 12px; color: #999; padding-bottom: 5px; }
	#item_list .search_wrap ul li { padding: 5px 0px; }
	#item_list .search_wrap input[type='text'] { height: 30px; padding: 0px 10px; border: 0px; border-bottom: 1px solid #ccc; }
	#item_list .search_wrap input[name='search_word'] { width: calc(100% - 20px); }
	#item_list .search_wrap input[type='checkbox'] { display: none; width: 0px; height: 0px; font-size: 0px; }
	#item_list .search_wrap input[type='checkbox']+label { display: inline-block; height: 30px; line-height: 30px; padding: 0px 10px; border: 1px solid #ccc; background-color: #eee; text-align: center; border-radius: 5px; font-size: 14px; }
	#item_list .search_wrap input[type='checkbox']:checked+label { background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
	#item_list .search_wrap select { height: 30px; padding: 0px 10px; border: 1px solid #ccc; min-width: 60px; background-color: #fff; }
	#item_list .search_wrap .btn_wrap { text-align: right; margin-top: 10px; }
	#item_list .search_wrap .btn_wrap button { height: 30px; padding: 0px 10px; border: 1px solid #ccc; border-radius: 5px; background-color: #eee; }
	#item_list .info { font-size: 12px; color: #999; padding: 5px; }
	#item_list table { border-collapse: collapse; width: 100%; margin: 0 auto; text-align: center; font-size: 14px; font-family: 'NL2GR'; }
	#item_list table caption { text-align: left; }
	#item_list table tr th { background-color: #e9e9e9; padding: 5px; border: 1px solid #eee; font-weight: Bold; white-space: nowrap; }
	#item_list table tr td { position: relative; padding: 2px 5px; border: 1px solid #eee; font-size: 14px; vertical-align: top; font-weight: normal; }
	#item_list table tr td.not_view { background-color: #999; color: #ccc; }
	#item_list table tr td.lft { text-align: left; }
	#item_list table tr td.rgt { text-align: right; }
	#item_list table tr td.item_write_btn { cursor: pointer; }
	#item_list table tr td.item_price { line-height: 24px; white-space: nowrap; }
	#item_list table tr td.soldout { color: #f00; }
	#item_list table tr td.no_data { background-color: #f9f9f9; padding: 50px 0px; }
	#item_list table tr td.item_image { vertical-align: middle; }
	#item_list table tr td a.product_link { display: flex; justify-content: center; align-items: center; border: 1px solid #ccc; border-radius: 5px; background-color: #eee; text-align: center; width: auto; height: 30px; line-height: 30px; text-decoration: none; color: #000; }
	#item_list table tr td ul.table { width: 100%; display: table; }
	#item_list table tr td ul.table li { display: table-cell; border: 1px solid #ccc; vertical-align: top; }
	#item_list table tr td ul.table li:first-child { width: 32px; }
	#item_list table tr td ul.table li:last-child { width: calc(100% - 32px); }
	#item_list table tr td div { width: 100%; }
	#item_list table tr td div.img { width: 40px; height: 40px; border: 1px solid #eee; background-color: #eee; background-size: cover; background-repeat: no-repeat; background-position: center; }
	#item_list table tr td div.img img { width: 100%; }
	#item_list table tr td .option_list { position: absolute; right: 5px; top: 33px; width: auto; background-color: #fff; padding: 10px; border: 1px solid #ccc; border-radius: 10px; z-index: 1; white-space: nowrap; }
	#item_list table tr td .product_no { font-size: 12px; color: #999; }
	#item_list table tr td .item_name { font-weight: Bold; }
	#item_list table tr td .view_tag { display: inline-block; border: 1px solid #f5bf2e; color: #f5bf2e; padding: 0px 5px; margin: 2px 4px 2px 0px; min-width: 40px; text-align: center; white-space: nowrap; font-size: 12px; font-weight: Bold; }
	#item_list table tr td button { border: 1px solid #ccc; border-radius: 5px; background-color: #eee; height: 30px; padding: 0px 10px; white-space: nowrap; }
	#item_list .more { text-align: center; padding: 10px; }
	#item_list .more button { width: 100%; height: 50px; line-height: 50px; background-color: #eee; border: 1px solid #ccc; border-radius: 10px; display: none; }

	@media screen and (min-width: 720px) {
		body { background-color: #fff; }
	}

	.fail_deail:hover{background-color: #eee;}

</style>

<div class="bjj_top_menu">
    <div class="bjj-back-btn"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/btn_back_2.png"></a></div>
    <div class="bjj_top_title"><p>알림톡 통계</p></div>
</div>

<div id="item_list">
	<div class="top_wrap">
		<input type="text" id="searchDate" name="searchDate" class="datePicker" value="<?=$n_date?>" />
		<button type="button" class="search_btn"><i class="fas fa-search"></i> 검색</button>
<!-- 		<button onclick="window.open('http://dev.gopet.kr/pet/artist/index_glory.php')">test</button> -->
		<button onclick="location.href='http://dev.gopet.kr/pet/artist/index_glory.php?artist_name=그을로리'">test</button>
		<span style="font-size: 13px; color: red;">2021-06-07 부터 인포뱅크로 변경</span>
<!-- 		<div id="test"> -->
<!-- 			연도:<p id="year"></p> -->
<!-- 			월:<p id="month"></p> -->
<!-- 			일:<p id="day"></p> -->
<!-- 			날짜:<p id="mydate"></p> -->
<!-- 		</div> -->
<!-- 		<div id="test1"></div> -->
	</div>
	
	<table>
		<caption>
		</caption>
		<colgroup>
			<col width="30%" />
			<col width="15%" />
			<col width="15%" />
		</colgroup>
		<tr>
			<th>구분</th>
			<th>발송성공 건수</th>
			<th>발송실패 건수</th>
		</tr>
		<tr class="reserve_info">
			<td><p>미용예약안내(예약접수시)</p></td>
			<td></td>
			<td></td>
		</tr>
		<tr class="reserve_update">
			<td><p>예약변경안내(예약변경시)</p></td>
			<td></td>
			<td></td>
		</tr>
		<tr class="reserve_finish">
			<td><p>미용종료안내(설정한 샵만)</p></td>
			<td></td>
			<td></td>
		</tr>
		<tr class="reserve_tomorrow">
			<td><p>전날알림</p></td>
			<td></td>
			<td></td>
		</tr>
	</table>
	<br>
	<table>
		<colgroup>
			<col width="15%" />
			<col width="10%" />
			<col width="10%" />
			<col width="40%" />
			<col width="35%" />
		</colgroup>
		<tr>
			<th>시간</th>
			<th>발송번호</th>
			<th>실패코드</th>
			<th>실패이유</th>
			<th>문자발송여부</th>
		</tr>
		<tbody class="fail_reason">
		</tbody>
	</table>
	<div class="more">
		<button class="more_btn">더보기</button>
	</div>
</div>

<script>

var search_date = '<?=$n_date ?>';
var year = '<?=$n_year ?>';
var month = '<?=$n_month ?>';
var day = '<?=$n_day ?>';


$("#searchDate").datepicker({
	dateFormat: "yy-mm-dd",
	dayNames: ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'],
	dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
	monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
	monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
	showMonthAfterYear: true,
	yearSuffix: "년",
	nextText: "다음달",
	prevText: "이전달",
	onSelect:function(date){
		
		var arr = date.split("-");
		year = arr[0];
		month = arr[1];
		day = arr[2];
		
//		$("#year").text(year);
//		$("#month").text(month);
//		$("#day").text(day);
//		
//		$("#mydate").text(date);
		search_date = date;
	}
});


var reserve_info = "미용예약안내(예약접수시)";
var reserve_info_class = ".reserve_info";
var reserve_update = "예약변경안내(예약변경시)";
var reserve_update_class = ".reserve_update";
var reserve_finish = "미용종료안내(설정한 샵만)";
var reserve_finish_class = ".reserve_finish";
var reserve_tomorrow = "전날알림";
var reserve_tomorrow_class = ".reserve_tomorrow";

// 날짜 검색 시 이벤트
$("#item_list").on("click", ".search_btn", function(){
//	$("#test").html(search_date);
	get_reserve_info("14040", reserve_info, reserve_info_class)
		.then(get_reserve_info("14041", reserve_update, reserve_update_class))
		.then(get_reserve_info("14042", reserve_finish, reserve_finish_class))
		.then(get_reserve_info("14043", reserve_tomorrow, reserve_tomorrow_class));
	$("#item_list").find(".fail_reason").html("");

});


// 알림톡 발송 건수 가져오기
function get_reserve_info(template_code, position, position_class){
	return new Promise(function(resolve, reject) {

		$.ajax({
			url: 'stats_allim_ajax.php',
			data: {
				mode : "get_allim",
				date : search_date,
				year : year,
				month : month,
				day : day,
				code : template_code
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data);
					var html = '';

					if(data.data && data.data.length > 0){
						$.each(data.data, function(i, v){
							html += '<td><p>'+position+'</p></td>';
							html += '<td><p>'+v.sucsess_cnt+'</p></td>';
							html += '<td class="fail_deail" id="fail_detail_'+template_code+'"><p>'+v.fail_cnt+'</p></td>';
							
						});
						$("#item_list").find(position_class).html(html);

					}
					resolve();
				}else{
					alert(data.data+"("+data.code+")");
					console.log(data.code);
				}
			},
			error: function(xhr, status, error) {
				$("#loading").css("display", "none");
				//alert(error + "네트워크에러");
				if(xhr.status != 0){
					alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
				}
			}
		});
	});
}

// 실패건수 클릭 이벤트
$("#item_list").on("click", "[id^='fail_detail']", function(){
	var tem_code = $(this).attr('id').substring(12,this.length); // 템플릿 코드 가져오기
	console.log(tem_code);
	if(tem_code == '14042'){
		get_fail_detail(tem_code)
			.then(get_send_message_end);
	}else{
		get_fail_detail(tem_code)
			.then(get_send_message);
	}
});

// 알림톡 실패 정보 가져오기
function get_fail_detail(tem_code){
	return new Promise(function(resolve, reject) {
		
		$.ajax({
				url: 'stats_allim_ajax.php',
				data: {
					mode : "get_fail_detail",
					date : search_date,
					year : year,
					month : month,
					day : day,
					code : tem_code
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data);
						var html = '';
						
						$("#test1").html(data.data);

						if(data.data && data.data.length > 0){
							$.each(data.data, function(i, v){
								
								var reason = ""
								switch(v.report_code){
									// 이노탭 오류코드
									case "KKO_3008" : reason = "전화번호 오류";
									break;
									case "KKO_3018" : reason = "메시지를 전송할 수 없음<br> - (공통) 카카오톡을 사용하지 않는 사용자<br> - (알림톡 push방식일 경우) 최근 카카오톡을 사용하지 않은 사용자<br> - (알림톡일 경우) 알림톡 차단을 선택한 사용자<br> - (친구톡일 경우)발신프로필과 친구 관계가 아닌 사용자";
									break;
									case "KKO_ME09" : reason = "사용자의 알림톡 메시지 수신여부 불투명(성공 불확실)";
									break;
									case "KKO_3016" : reason = "메시지 내용이 템플릿과 일치하지 않음";
									break;
									// 인포뱅크 오류코드
									case "3050" : reason = "카카오톡을 사용하지 않는 사용자<br> - 168시간 이내에 카카오톡 사용 이력이 없는 사용자<br> - 알림톡 차단을 선택한 사용자<br> - 친구톡의 경우 친구가 아닌경우<br> - 카카오톡 하위버전 사용자(android 6.0.0 미만 / ios 6.0.0 미만)";
									break;
									case "3027" : reason = "카카오톡을 사용하지 않는 사용자 (전화번호 오류)";
									break;
								
								}
//								if(v.recipient_num == "01086331776") {v.recipient_num = "글로리테스트";};

								html += '<tr>';
								html += '<td><p>'+v.date_mt_report+'</p></td>';
								html += '<td><p>'+v.recipient_num+'</p></td>';
								html += '<td><p>'+v.report_code+'</p></td>';
								html += '<td><p>'+reason+'</p></td>';
								html += '<td class="send_message" data-id="'+v.payment_log+'" data-num="'+v.recipient_num+'"></td>';
								html += '</tr>';
//								html += '<tr class="send_message" data-id="'+v.payment_log+'"></tr>'; // 문자 성공 여부 담을 <tr>
								html += '<tr><td style="padding-bottom:10px;" colspan="5"></td></tr>'
								
							});
							$("#item_list").find(".fail_reason").html(html);

						}
						resolve();
					}else{
						alert(data.data+"("+data.code+")");
						console.log(data.code);
					}
				},
				error: function(xhr, status, error) {
					$("#loading").css("display", "none");
					//alert(error + "네트워크에러");
					if(xhr.status != 0){
						alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
					}
				}
			});
	
	});
}

// 문자 발송 여부 정보 가져오기
function get_send_message(){
	return new Promise(function(resolve, reject) {

		$.ajax({
			url: 'stats_allim_ajax.php',
			data: {
				mode : "get_send_message",
				date : search_date,
				year : year,
				month : month,
				day : day,
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log("문자" +data);
					

					if(data.data && data.data.length > 0){
						$.each(data.data, function(i, v){
							var html = '';

							var reason = '';
							switch(v.mt_report_code_ib){
								case "3014" : reason = "기타";
								break;
								case "3004" : reason = "단말기 서비스 일시정지";
								break;
								case "3015" : reason = "전송경로 없음";
								break;
								case "3013" : reason = "서비스 거부";
								break;
								case "1020" : reason = "지원하지 않는 문자열 포함(EUC-KR)";
								break;
							}

							if(v.mt_report_code_ib == "1000"){
								html += '<p">문자발송성공</p>';
								html += '<p>'+v.date_mt_report+'</p>';
								$("#item_list").find(".send_message[data-id='"+v.payment_log+"']").css('background-color','#40FF00');
							}else{
								html += '<p>'+v.date_mt_report+'</p>';
								html += '<p>문자발송실패(오류코드 : '+v.mt_report_code_ib+')</p>';
								html += '<p>'+reason+'</p>';
								$("#item_list").find(".send_message[data-id='"+v.payment_log+"']").css('background-color','#FE2E2E');
							}
							
							$("#item_list").find(".send_message[data-id='"+v.payment_log+"']").html(html);
						});

					}
					resolve();
				}else{
					alert(data.data+"("+data.code+")");
					console.log(data.code);
				}
			},
			error: function(xhr, status, error) {
				$("#loading").css("display", "none");
				//alert(error + "네트워크에러");
				if(xhr.status != 0){
					alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
				}
			}
		});
	});
}


// 문자 발송 여부 정보 가져오기(미용종료알림)
function get_send_message_end(){
	return new Promise(function(resolve, reject) {

		$.ajax({
			url: 'stats_allim_ajax.php',
			data: {
				mode : "get_send_message_end",
				date : search_date,
				year : year,
				month : month,
				day : day,
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log("문자" +data);
					

					if(data.data && data.data.length > 0){
						$.each(data.data, function(i, v){
							var html = '';

							var reason = '';
							switch(v.mt_report_code_ib){
								case "3014" : reason = "기타";
								break;
								case "3004" : reason = "단말기 서비스 일시정지";
								break;
								case "3015" : reason = "전송경로 없음";
								break;
								case "3013" : reason = "서비스 거부";
								break;
							}

							if(v.mt_report_code_ib == "1000"){
								html += '<p">문자발송성공</p>';
								html += '<p>'+v.date_mt_report+'</p>';
								$("#item_list").find(".send_message[data-num='"+v.recipient_num+"']").css('background-color','#40FF00');
							}else{
								html += '<p>'+v.date_mt_report+'</p>';
								html += '<p>문자발송실패(오류코드 : '+v.mt_report_code_ib+')</p>';
								html += '<p>'+reason+'</p>';
								$("#item_list").find(".send_message[data-num='"+v.recipient_num+"']").css('background-color','#FE2E2E');
							}
							
							$("#item_list").find(".send_message[data-num='"+v.recipient_num+"']").html(html);
						});

					}
					resolve();
				}else{
					alert(data.data+"("+data.code+")");
					console.log(data.code);
				}
			},
			error: function(xhr, status, error) {
				$("#loading").css("display", "none");
				//alert(error + "네트워크에러");
				if(xhr.status != 0){
					alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
				}
			}
		});
	});
}


// 세자리 숫자 콤마
Number.prototype.format = function() {
	if (this == 0) return 0;

	var reg = /(^[+-]?\d+)(\d{3})/;
	var n = (this + '');

	while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');

	return n;
};

// 문자열 타입에서 쓸 수 있도록 format() 함수 추가
String.prototype.format = function() {
	var num = parseFloat(this);
	if (isNaN(num)) return "0";

	return num.format();
};
</script>

<?php
    include "../include/bottom.php";
?>
