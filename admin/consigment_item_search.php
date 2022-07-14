<?php
include "../include/top.php";
include "../include/App.class.php";
// include "../include/check_header_log.php";

$is_android = 0;
$app = new App();
if ($app->is_app() == 1) {
    $is_android = 1;
}


$startDate = ($_GET['startDate'] && $_GET['startDate'] != "")? $_GET['startDate'] : date('Y-m-d', strtotime(DATE('Y-m-d')."-1 days")); // 달력검색 시작 날짜
$endDate = ($_GET['endDate'] && $_GET['endDate'] != "")? $_GET['endDate'] : DATE('Y-m-d'); // 달력검색 끝 날짜

$startYear = date("Y", strtotime($startDate)); // 시작 년(알림톡 테이블명 땜시)
$endYear = date("Y", strtotime($endDate)); // 끝 년
$startMonth = date("m", strtotime($startDate)); // 시작 월(알림톡 테이블명 땜시)
$endMonth = date("m", strtotime($endDate)); // 끝 월
$startYearMonth = date("Ym", strtotime($startDate)); // 시작 년월(알림톡 테이블명 땜시)
$endYearMonth = date("Ym", strtotime($endDate)); // 끝 월

// 테이블명 조회 계산
$count = 0;
if($startYear == $endYear){ // 검색 시작과 끝 년도가 같을때
	$count = $endMonth - $startMonth;
}else{
	$count_year = ($endYear - $startYear - 1)*12; // 2년 이상 차이가 나면 12개월을 더한다.
	$count = 12 - $startMonth + $endMonth + $count_year;
}

$backurl_1 = $_GET['backurl_1'];
$backurl = $_GET['backurl'];
$quk = $_GET['quk'];
$cellphone = $_GET['cellphone'];
if(!$cellphone){
	
}

$searchList = null;
$data = array();


// 결제액 합계
if (($startDate != "" && $startDate != null) && ($endDate != "" && $endDate != null)) {
	
	// 상세내역
	$query = "
			SELECT 
				iplp.supplier AS supplier, DATE_FORMAT(ipl.pay_dt, '%m/%d') AS date, SUBSTRING_INDEX(ipl.guest_info, ',', 1) AS order_name, ipl.shipping_name AS shipping_name, 
				ipl.shipping_cellphone AS cellphone, ipl.shipping_zipcode AS zipcode, 
				CONCAT(ipl.shipping_addr, ' ', ipl.shipping_addr2) AS address, ipl.shipping_memo AS memo, iplp.option_data AS option_data, iplp.product_price AS product_price, iplp.pay_status AS pay_status
			FROM tb_item_payment_log_product AS iplp 
			INNER JOIN tb_item_payment_log AS ipl ON ipl.order_num = iplp.order_num
			WHERE ipl.order_status = '3'
			AND ipl.pay_dt BETWEEN '".$startDate."' AND '".$endDate." 23:59:59'
			AND NOT (iplp.is_supply = '1' AND iplp.supplier = '정글북' AND iplp.ip_seq = '')
			AND iplp.is_supply = '1'
			-- AND iplp.pay_status = '1'
			ORDER BY ipl.ip_log_seq
	";
	$result = mysql_query($query);

	while ($data = mysql_fetch_array($result)) {
		$searchList[] = $data;
	}
	
}

?>
<script src="../js/fontawesome.min.js"></script>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">

<style>
/*	#manage_statistics { padding-bottom: 50px; }*/
/*	#manage_statistics #searchForm { padding-bottom: 5px; }*/
	#manage_statistics #searchForm ul li input[name='startDate'] { width: calc(100% - 34px); }
	#manage_statistics #searchForm ul li input[name='endDate'] { width: calc(100% - 34px); }
	#manage_statistics #searchForm button#resetBtn { height: 40px; line-height: 40px; }
	#manage_statistics #searchForm button#searchBtn { height: 30px; line-height: 40px; }
	#manage_statistics .pc table tr td {border-bottom: 1px solid #2A0A0A;}

	.bj_sub_menu { display: none; position: fixed; width: 0px; left: 10px; bottom: -200px; border: 0px solid #ccc; background-color: #FFECB9; text-align: center; border-radius: 10px; padding: 10px; box-sizing: border-box; z-index: 101; }
	.bj_sub_menu .titie { position: relative; width: 100%; height: 30px; margin-bottom: 5px; }
	.bj_sub_menu .titie .close_btn { position: absolute; right: 0px; top: 0px; width: 30px; height: 30px; border: 1px solid #ccc; line-height: 30px; text-align: center; }
	.bj_sub_menu .titie .close_btn .fa-times { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); }
	.bj_sub_menu ul { list-style: none; padding: 0px; margin: 0px; margin-bottom: 60px; }
	.bj_sub_menu ul li { height: 50px; line-height: 50px; position: relative; }
	.bj_sub_menu ul li>img { position: absolute; left: 9px; top: 14px; width: 24px; }
	.bj_sub_menu ul li button { width: 100%; height: 40px; border: 0px solid #ccc; border-radius: 5px; background-color: #fff; font-size: 16px; text-align: left; padding-left: 40px; white-space: nowrap; }
	.bj_sub_menu ul li button span.new_text { display:inline-block; width: 16px;padding-left: 5px; }
	.bj_sub_menu ul li button span.new_text img { width: 100%; vertical-align: text-bottom; }
	.bj_backBlock { width: 100%; height: 100%; position: fixed; left:0; right:0; top:0; bottom:0; background-color: rgba(0,0,0,0.6); z-index: 100; }

	.order_finsh {background-color:#81F7F3;}

</style>

<div class="top_menu">
<!-- 	<div class="top_back"><a href="<?=$backurl_1 ?>"><img src="/pet/images/btn_back_2.png" style="width:26px;"></a></div> -->
		<div class="top_title">
			<p class="header_font">위탁업체 일괄주문</p>
		</div>
	<div class="top_home"><a href="https://gopet.kr/pet/mainpage/index.php"><img src="../images/btn_myshop_01.png" ></a></div>
</div>

<div id="manage_statistics">
	<div class="search-box">
		<form id="searchForm" method="get" autocomplete="off">
			<input type="hidden" name="quk" value="<?=$quk?>">
			<input type="hidden" name="backurl_1" value="<?=$backurl_1 ?>">
			<input type="hidden" name="cellphone" value="<?=$cellphone ?>">

			<ul>
				<li>
					<input type="text" id="startDate" name="startDate" class="datePicker" value="<?= $startDate ?>" />
				</li>
				<li style="padding-right:5px;"> ~ </li>
				<li>
					<input type="text" id="endDate" name="endDate" class="datePicker" value="<?= $endDate ?>" />
				</li>
				<li>
					<button type="submit" id="searchBtn"><i class="fas fa-search"></i></button>
				</li>
			</ul>
			<ul>
<!-- 				<li> -->
<!-- 					<button type="button" id="resetBtn"><i class="fas fa-undo"></i></button> -->
<!-- 				</li> -->
<!-- 				<li> -->
<!-- 					<button type="submit" id="searchBtn"><i class="fas fa-search"></i></button> -->
<!-- 				</li> -->
			</ul>
		</form>
	</div>

	<div class="pc" style="padding-bottom:0px;">
		<div class="table_header">
			<table>
				<colgroup>
					<col width="5%" />
					<col width="3%" />
					<col width="3%" />
					<col width="3%" />
					<col width="7%" />
					<col width="6%" />
					<col width="30%" />
					<col width="13%" />
					<col width="21%" />
					<col width="5%" />
					<col width="3%" />
				</colgroup>
				<tr class="title">
					<th>업체명</th>
					<th>일자</th>
					<th>구매자</th>
					<th>수취인</th>
					<th>연락처</th>
					<th>우편번호</th>
					<th>주소</th>
					<th>배송메세지</th>
					<th>모델명</th>
					<th>판매가</th>
					<th>수량</th>
				</tr>
			</table>
		</div>
		<div>
			<table class="stat_data">
				<colgroup>
					<col width="5%" />
					<col width="3%" />
					<col width="3%" />
					<col width="3%" />
					<col width="7%" />
					<col width="6%" />
					<col width="30%" />
					<col width="13%" />
					<col width="21%" />
					<col width="5%" />
					<col width="3%" />
				</colgroup>
			<?php
				if(count($searchList) > 0){
					foreach($searchList AS $key => $value){

						$option_data = json_decode($value["option_data"], JSON_UNESCAPED_UNICODE); // option_data json
						foreach($option_data AS $key_ => $value_){
							$pay_status = ($value["pay_status"] != "1")? "order_finsh": "";
			?>
							<tr class="<?=$pay_status ?>">
								<td class="center"><?=$value["supplier"] ?></td>
								<td class="center"><?=$value["date"] ?></td>
								<td class="center"><?=$value["order_name"] ?></td>
								<td class="center"><?=$value["shipping_name"] ?></td>
								<td class="center"><?=$value["cellphone"] ?></td>
								<td class="center"><?=$value["zipcode"] ?></td>
								<td class="center"><?=$value["address"] ?></td>
								<td class="center"><?=$value["memo"] ?></td>
								<td class="center"><?=$value_["txt"] ?></td>
								<td class="center"><?=$value_["value"] ?></td>
								<td class="center"><?=$value_["amount"] ?></td>
							</tr>
			<?php
						}
					}
				}else{
			?>
				<tr class="not_list">
					<td class="center" colspan="10">
						발송 내역이 없습니다.
					</td>
				</tr>
			<?php
				}
			?>
			</table>
		</div>
	</div>
</div>
<script>

$(document).ready(function() {

	$("#startDate").datepicker({
		dateFormat: "yy-mm-dd",
		dayNames: ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'],
		dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
		monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
		monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
		showMonthAfterYear: true,
		yearSuffix: "년",
		nextText: "다음달",
		prevText: "이전달",
		showOn: "both",
//		minDate: new Date('2021-06-05'),
		buttonImage: "<?= $image_directory ?>/calendar_ico.png",
		onClose: function(selectedDate) {
			if (selectedDate != "") {
				$("#endDate").datepicker("option", "minDate", selectedDate);
				$(".quk_date").removeClass("on");
				$("input[name='quk']").val("");
			}
		}
	});

	$("#endDate").datepicker({
		dateFormat: "yy-mm-dd",
		dayNames: ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'],
		dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
		monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
		monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
		showMonthAfterYear: true,
		yearSuffix: "년",
		nextText: "다음달",
		prevText: "이전달",
		showOn: "both",
		buttonImage: "<?= $image_directory ?>/calendar_ico.png",
		onClose: function(selectedDate) {
			if (selectedDate != "") {
				$("#startDate").datepicker("option", "maxDate", selectedDate);
				$(".quk_date").removeClass("on");
				$("input[name='quk']").val("");
			}
		}
	});
var starMmonth = "<?=$startMonth ?>";
var endMonth = "<?=$endMonth ?>";
var count = "<?=$count ?>";
var count_year = "<?=$count_year ?>";
var startYearMonth = "<?=$startYearMonth ?>";
var endYearMonth = "<?=$endYearMonth ?>";
console.log("starMmonth = "+starMmonth);
console.log("endMonth = "+endMonth);
console.log("count = "+count);
console.log("count_year = "+count_year);
console.log("startYearMonth = "+startYearMonth);
console.log("endYearMonth = "+endYearMonth);
});



// date - today(Y-m-d)
function getRecentDate(){
    var dt = new Date();
 
    var recentYear = dt.getFullYear();
    var recentMonth = dt.getMonth() + 1;
    var recentDay = dt.getDate();
 
    if(recentMonth < 10) recentMonth = "0" + recentMonth;
    if(recentDay < 10) recentDay = "0" + recentDay;
 
    return recentYear + "-" + recentMonth + "-" + recentDay;
}

// date - week(Y-m-d)
function getWeekDate(period){
    var dt = new Date();
 
 	dt.setDate(dt.getDate() - (7 * period));
 
    var year = dt.getFullYear();
    var month = dt.getMonth() + 1;
    var day = dt.getDate();
 
    if(month < 10) month = "0" + month;
    if(day < 10) day = "0" + day;
 
    return year + "-" + month + "-" + day;
}

// date - period(Y-m-d)
function getPastDate(period){
    var dt = new Date();
 
    dt.setMonth((dt.getMonth() + 1) - period);
 
    var year = dt.getFullYear();
    var month = dt.getMonth();
    var day = dt.getDate();
 
    if(month < 10) month = "0" + month;
    if(day < 10) day = "0" + day;
 
    return year + "-" + month + "-" + day;
}

// date - period(Y-m-d)
function getStartDate(period){
    var dt = new Date();
 
    dt.setMonth(dt.getMonth() - period);
 
    var year = dt.getFullYear();
    var month = (dt.getMonth() + 1);
    var day = 1;
 
    if(month < 10) month = "0" + month;
    if(day < 10) day = "0" + day;
 
    return year + "-" + month + "-" + day;
}

// date - endDay(Y-m-d)
function getEndDate(period){
    var dt = new Date();
 
    dt.setMonth(dt.getMonth() - period);
 
    var year = dt.getFullYear();
    var month = (dt.getMonth() + 1);
    var day = ( new Date(year, month, 0) ).getDate();
 
    if(month < 10) month = "0" + month;
    if(day < 10) day = "0" + day;
 
    return year + "-" + month + "-" + day;
}
</script>
<?php include "../include/bottom.php"; ?>