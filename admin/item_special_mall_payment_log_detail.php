<?php
include "../include/top.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

// init
$r_no = ($_GET["no"] && $_GET["no"] != "")? $_GET["no"] : "";
$backurl = $_GET['backurl'];
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

$pay_status_arr = array(
	"1" => "진행중",
	"2" => "입금대기",
	"3" => "상품준비중",
	"4" => "배송준비중",
	"5" => "배송중",
	"6" => "배송완료",
	"7" => "취소",
	"8" => "보류",
	"9" => "실패"
);

$banks['003'] = "기업은행";
$banks['004'] = "국민은행";
$banks['011'] = "농협중앙회";
$banks['012'] = "단위농협";
$banks['020'] = "우리은행";
$banks['031'] = "대구은행";
$banks['005'] = "외환은행";
$banks['023'] = "SC제일은행";
$banks['032'] = "부산은행";
$banks['045'] = "새마을금고";
$banks['027'] = "한국씨티은행";
$banks['034'] = "광주은행";
$banks['039'] = "경남은행";
$banks['007'] = "수협";
$banks['048'] = "신협";
$banks['037'] = "전북은행";
$banks['035'] = "제주은행";
$banks['064'] = "산림조합";
$banks['071'] = "우체국";
$banks['081'] = "하나은행";
$banks['088'] = "신한은행";
$banks['090'] = "카카오뱅크";
$banks['209'] = "동양종금증권";
$banks['243'] = "한국투자증권";
$banks['240'] = "삼성증권";
$banks['230'] = "미래에셋";
$banks['247'] = "우리투자증권";
$banks['218'] = "현대증권";
$banks['266'] = "SK증권";
$banks['278'] = "신한금융투자";
$banks['262'] = "하이증권";
$banks['263'] = "HMC증권";
$banks['267'] = "대신증권";
$banks['270'] = "하나대투증권";
$banks['279'] = "동부증권";
$banks['280'] = "유진증권";
$banks['287'] = "메리츠증권";
$banks['291'] = "신영증권";
$banks['238'] = "대우증권";
asort($banks); // 가나다순
?>
<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script src="../js/fontawesome.min.js"></script>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<style>

    .top_menu { position: fixed; left: 0px; top: 0px; width: 100%; background-color: rgba(255,255,255,0.8); z-index: 1; }
	#item_order_detail { margin-top: 61px; }
	#item_order_detail .order_box .order_table tbody tr td input[type="text"] { height: 30px; line-height: 30px; }
	#item_order_detail .order_box .order_table tbody tr td input[name="shipping_zip"] { width: 60px; }
	#item_order_detail .order_box .order_table tbody tr td input[name='shipping_invoice'] { width: 140px; }
	#item_order_detail .order_box .order_table tbody tr td select { height: 30px; line-height: 30px; border: 1px solid #ccc; background-color: transparent; border-radius: 6px; }
	#item_order_detail .order_box .order_table tbody tr td button { height: 30px; line-height: 30px; border: 1px solid #ccc; background-color: transparent; border-radius: 6px; padding: 0px 10px; }
	#item_order_detail .order_box .order_table tbody tr td button.search_addr_btn { background-color: #eee; border: 1px solid #ccc; color: #333; }
	#item_order_detail .order_box .order_table tbody tr td button.set_update_shipping_info_btn { background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
	#item_order_detail .order_box .order_table tbody tr td button.set_update_pay_status_btn { background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
	#item_order_detail .order_box .order_table tbody tr td button.set_update_pay_status_use_talk_btn { background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
	#item_order_detail .order_box .order_table tbody tr td button.set_update_shipping_invoice_btn { background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
	#item_order_detail .order_box .order_table tbody tr td button.set_update_bank_deposit_btn { background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
	#item_order_detail .order_box .order_table tbody tr td button.set_update_bank_deposit_use_talk_btn { background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
	#item_order_detail .order_box .order_box_title { position: relative; height: 20px; }
	#item_order_detail .order_box .order_box_title span.pay_status { position: absolute; left: 20px; top: 15px; font-size: 16px; }
	#item_order_detail .order_box .order_box_title span.pay_status span.card { position: initial; color: #f00; }
	#item_order_detail .order_box .order_box_title span.pay_status span.bank { position: initial; color: #00f; }
	#item_order_detail .order_box .order_box_title span.order_num { position: absolute; right: 25px; top: 20px; }
	#item_order_detail button.set_update_shipping_info_btn { width: 100%; height: 30px; line-height: 30px; margin-top: 20px; }
	#item_order_detail .bank_chk_wrap { display: none; }
	#item_order_detail .bank_chk_wrap.on { display: block; }
	#item_order_detail .set_update_pay_status_use_talk_btn { display: none; }
	#item_order_detail .set_update_pay_status_use_talk_btn.on { display: inline-block; }
	#item_order_detail .bank_cancel_wrap { display: none; }
	#item_order_detail .bank_cancel_wrap.on { display: block; }
	#item_order_detail .cancel_box { padding: 10px; background-color: #fcc; color: #f00; }
	#item_order_detail .cancel_box span { display: block; width: calc(100% - 22px); margin-top: 10px; padding: 10px; border: 1px solid #fee; }
	#item_order_detail .return_box { padding: 10px; background-color: #ff9966; color: #660000; }
	#item_order_detail .return_box span { display: block; width: calc(100% - 22px); margin-top: 10px; padding: 10px; border: 1px solid #fed; }

	#item_order_detail .order_box .item_payment_log { margin: 10px; padding-bottom: 20px; border-radius: 10px; font-family: 'NL2GR'; }
	#item_order_detail .order_box .item_payment_log button { border: 1px solid #ccc; border-radius: 5px; background-color: #eee; height: 30px; padding: 0px 10px; }
	#item_order_detail .order_box .item_payment_log input[type='checkbox'] { display: none; width: 0px; height: 0px; padding: 0px; margin: 0px; border: 0px; font-size: 0px; }
	#item_order_detail .order_box .item_payment_log input[type='checkbox']+label { position: relative; display: inline-block; padding: 0px 10px 0px 35px; height: 30px; line-height: 35px; border: 1px solid #ccc; background-color: #eee; border-radius: 5px; font-size: 16px; }
	#item_order_detail .order_box .item_payment_log input[type='checkbox']+label span { position: absolute; left: 5px; top: 5px; display: inline-block; border: 1px solid #ccc; width: 20px; height: 20px; background-color: #fff; }
	#item_order_detail .order_box .item_payment_log input[type='checkbox']:checked+label { border: 1px solid #f5bf2e; background-color: #f5bf2e; color: #fff; }
	#item_order_detail .order_box .item_payment_log input[type='checkbox']:checked+label span { border: 1px solid #f5bf2e; }
	#item_order_detail .order_box .item_payment_log input[type='checkbox']:checked+label span:before { content: ''; display: inline-block; width: 10px; height: 5px; border-left: 5px solid #f5bf2e; border-bottom: 5px solid #f5bf2e; transform: rotate(-45deg); margin-left: 2px; margin-top: 4px; }
	#item_order_detail .order_box .item_payment_log .title { position: relative; line-height: 20px; height: 40px; background-color: #ffe; }
	#item_order_detail .order_box .item_payment_log .title .right { position: absolute; right: 0px; top: 0px; }
	#item_order_detail .order_box .item_payment_log .title .right button { height: 40px; }
	#item_order_detail .order_box .item_payment_log .item_payment_data { position: relative; padding: 10px 10px 10px 0px; }
	#item_order_detail .order_box .item_payment_log .item_payment_data .item_name { width: calc(100% - 70px); }
	#item_order_detail .order_box .item_payment_log .item_payment_data .item_price { position: absolute; right: 0px; top: 10px; font-weight: Bold; }
	#item_order_detail .order_box .item_payment_log .item_cart { padding: 1px 0px; background-color: #eee; }
	#item_order_detail .order_box .item_payment_log .item_cart .item_cart_wrap { background-color: #fff; margin: 10px; }
	#item_order_detail .order_box .item_payment_log .item_cart .item_cart_wrap .item_cart_data { position: relative; min-height: 110px; }
	#item_order_detail .order_box .item_payment_log .item_cart .item_cart_wrap .item_cart_data .item_image { position: absolute; left: 10px; top: 10px; width: 90px; height: 90px; border-radius: 5px; background-size: cover; background-repeat: no-repeat; background-position: center; }
	#item_order_detail .order_box .item_payment_log .item_cart .item_cart_wrap .item_cart_data .item_detail { margin-left: 110px; min-height: 100px; padding-top: 10px; }
	#item_order_detail .order_box .item_payment_log .item_cart .item_cart_wrap .item_cart_data .item_detail>div { padding-bottom: 5px; }
	#item_order_detail .order_box .item_payment_log .item_cart .item_cart_wrap .item_cart_data .item_detail .item_price { font-weight: Bold; }
	#item_order_detail .order_box .item_payment_log .item_cart .item_cart_wrap .btn_wrap { text-align: right; padding: 10px; }

	#item_order_detail .jbook_list { border: 1px solid #ddd; width: 95%; margin: 0 auto; }
	#item_order_detail .jbook_list>.title { font-size: 16px; height: 30px; line-height: 30px; padding: 0px 10px; background-color: #0A8242; color: #fff; }
	#item_order_detail .jbook_list .bjOrderData { padding: 10px; }
	#item_order_detail .jbook_list .bjOrderData>ul>li { padding: 2px 0px; height: 30px; }
	#item_order_detail .jbook_list .bjOrderData>ul>li .title { font-size: 10px; color: #999; }
	#item_order_detail .jbook_list .btn_wrap { padding: 10px; text-align: center; }
	#item_order_detail .jbook_list .btn_wrap button { border: 1px solid #ccc; background-color: #eee; height: 30px; padding: 0px 10px; border-radius: 5px; }
</style>

<div class="top_menu">
	<!--div class="header-back-btn"><a href="<?=$admin_directory?>/item_payment_log.php"><img src="/pet/images/btn_back_2.png" width="26px"></a></div-->
	<?php include "../admin/admin_back_btn.php"; ?>
	<div class="top_title">
		<p>전문몰 상품 결제관리 상세</p>
	</div>
</div>
<div id="item_order_detail">
	<div class="order_box">
		<div class="order_detail_wrap">
			<div class="order_box_title">
				<span class="pay_status">상품준비중</span>
				<span class="order_num">주문번호:000000000</span>
			</div>
			<div class="order_box_text"style="">
				<table width="100%"class="order_table">
					<colgroup>
						<col width="30%">
						<col width="70%">
					</colgroup>
					<tbody>
						<tr style="border-bottom:2px solid #ddd;">
							<td style="padding-bottom: 5px;">배송지 정보</td>
							<td style="text-align: right;padding-bottom: 5px;" class="pay_dt">20.06.11</td>
						</tr>
						<tr>
							<td colspan="2" style="padding:5px 0 2px;font-size:12px;">받으시는 분 : <input type="text" name="shipping_name" value="" /></td>
						</tr>
						<tr>
							<td colspan="2" style="padding:2px 0;font-size:12px;">연락처 : <input type="text" name="shipping_cellphone" value="" /></td>
						</tr>
						<tr>
							<td colspan="2" style="padding:2px 0;font-size:12px;">받으시는 곳 : <input type="text" name="shipping_zip" value="" /><button type="button" class="search_addr_btn">검색</button><input type="text" name="shipping_addr" value="" /><input type="text" name="shipping_addr2" value="" /></td>
						</tr>
						<tr>
							<td colspan="2" style="padding:2px 0;font-size:12px;">배송요청사항 : <input type="text" name="shipping_memo" value="" /></td>
						</tr>
						<tr>
							<td colspan="2" style="padding:2px 0;font-size:12px;"><button type="button" class="set_update_shipping_info_btn">배송지 정보 수정</button></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="order_box_text" style="">
				<table width="100%"class="order_table">
					<colgroup>
						<col width="30%">
						<col width="70%">
					</colgroup>
					<tbody>
						<tr>
							<td style="padding:5px 0 2px;font-size:12px;">상품가격</td>
							<td style="text-align: right;padding:5px 0 2px;font-size:12px;" class="product_price">24,000원</td>
						</tr>
						<tr style="border-bottom:2px solid #ddd;">
							<td style="padding:5px 0;font-size:12px;">배송비</td>
							<td style="text-align: right;padding-bottom: 5px;font-size:12px;" class="shipping_price">2,500원</td>
						</tr>
						<tr>
							<td style="padding:5px 0 2px;font-size:12px;">포인트사용</td>
							<td style="text-align: right;padding:5px 0 2px;font-size:12px;" class="point_price">2,000원</td>
						</tr>
						<tr>
							<td style="padding:10px 0 2px;">총 결제 금액</td>
							<td style="text-align: right;padding:5px 0 2px;" class="total_price">26,000원</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="order_box_text"style="">
				<table width="100%"class="order_table">
					<colgroup>
						<col width="1%">
						<col width="*">
					</colgroup>
					<thead>
						<tr style="border-bottom:2px solid #ddd;">
							<td colspan="2"style="padding-bottom: 5px;">주문상품</td>
						</tr>
					</thead>
					<tbody class="item_option_wrap">
						<!--tr>
							<td style="padding:10px 0;"><img src="/pet/images/ex_image.png" width="100%"></td>
							<td style="padding:10px 0 10px 10px;">
								<p style="font-size:12px;">반짝 기능성 펫타올 S size</p>
								<p style="margin-top:10px;">1 / 9,800원</p>
							</td>
						</tr>
						<tr>
							<td style="padding:10px 0;"><img src="/pet/images/ex_image.png" width="100%"></td>
							<td style="padding:10px 0 10px 10px;">
								<p style="font-size:12px;">반짝 기능성 펫타올 S size</p>
								<p style="margin-top:10px;">1 /  9,800원</p>
							</td>
						</tr>
						<tr>
							<td colspan="2" style="text-align:right;">총 합계 : 19,600원</td>
						</tr-->
					</tbody>
				</table>
			</div>
			<div class="order_box_text item_payment" style="">
				<table width="100%"class="order_table">
					<colgroup>
						<col width="30%">
						<col width="70%">
					</colgroup>
					<thead>
						<tr style="border-bottom:2px solid #ddd;">
							<td colspan="2"style="padding-bottom: 5px;">결제상태 변경</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="2" style="padding: 10px 0px;">
								<select name="pay_status" style="height: 30px; height: 30px; ">
								<?php 
									foreach($pay_status_arr AS $key => $value){ 
								?>
									<option value="<?=$key?>"><?=$value?></option>
								<?php 
									}
								?>
								</select>
								<button type="button" style="height: 30px; line-height: 30px;" class="set_update_pay_status_btn">변경</button>
								<button type="button" style="height: 30px; line-height: 30px;" class="set_update_pay_status_use_talk_btn">변경(알림톡발송)</button>
								<div style="margin-top: 10px; font-size: 12px; font-family: 'NL2GN';">
									* 취소상태(반품, 취소)인 경우 결제상태를 변경하면 취소를 위해 넣어놓은 데이터가 초기화 되니 취소 변경시 주의하세요.
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="order_box_text"style="">
				<table width="100%"class="order_table">
					<colgroup>
						<col width="30%">
						<col width="70%">
					</colgroup>
					<thead>
						<tr style="border-bottom:2px solid #ddd;">
							<td colspan="2"style="padding-bottom: 5px;">송장번호 입력</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="2" style="padding: 10px 0px;">
								<input type="text" name="shipping_invoice" value="" placeholder="송장번호 입력" />
								<button type="button" style="height: 30px; line-height: 30px;" class="set_update_shipping_invoice_btn">송장번호추가</button>
								<select name="shipping_company">
									<option value="한진택배">한진택배</option>
									<option value="대한통운">대한통운</option>
									<option value="우체국">우체국</option>
								</select>
								<div style="margin-top: 10px; font-size: 12px; font-family: 'NL2GN'; ">
									* 배송알림 보내는 방법<br/>
									 1. 송장번호 입력<br/>
									 2. 배송업체 변경<br/>
									 3. 결제상태를 "배송중"으로 변경<br/>
									 4. "변경(알림톡발송)" 버튼을 눌러 발송<br/>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="order_box_text bank_chk_wrap" style="">
				<table width="100%"class="order_table">
					<colgroup>
						<col width="30%">
						<col width="70%">
					</colgroup>
					<thead>
						<tr style="border-bottom:2px solid #ddd;">
							<td colspan="2"style="padding-bottom: 5px;">계좌이체</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="2">
								<button type="button" style="height: 30px; line-height: 30px;" class="set_update_bank_deposit_btn">입금완료</button>
								<button type="button" style="height: 30px; line-height: 30px;" class="set_update_bank_deposit_use_talk_btn">입금완료(알림톡발송)</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="order_box_text bank_cancel_wrap" style="">
				<table width="100%"class="order_table">
					<colgroup>
						<col width="30%">
						<col width="70%">
					</colgroup>
					<thead>
						<tr style="border-bottom:2px solid #ddd;">
							<td colspan="2"style="padding-bottom: 5px;">결제취소(계좌이체)</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="2">
								<select name="reason_type">
									<option value="1">단순변심</option>
									<option value="2">상품불량</option>
									<option value="3">제품변경</option>
									<option value="etc">그밖에(직접입력)</option>
								</select>
								<textarea name="reason_detail"></textarea>
								<select name="cancel_type">
									<option value="cancel">취소</option>
									<option value="return">반품</option>
								</select>
								<select id="bank_option" name="cancel_bank" width="90%">
									<option value="">선택</option>
								<?php foreach($banks AS $key => $value){ ?>
									<option value="<?=$key?>"><?=$value?></option>
								<?php } ?>
								</select>
								<input type="text" name="cancel_account" value="" placeholder="계좌번호 입력" />
								<input type="text" name="cancel_price" value="" placeholder="환불금액 입력" />
								<button type="button" style="height: 30px; line-height: 30px;" class="set_update_bank_cancel_btn">결제취소</button>							
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<!--div class="order_box_text_none" style="text-align: center;">
			<a href="javascript:;" class="return_please">반품하기</a><a href="javascript:;" class="quick_search">배송조회</a>
		</div-->
	</div>
</div>

<?php // 우편번호 검색창 ?>
<div id="search_addr_wrap" style="display: none;"></div>

<script>
var no = "<?=$r_no ?>";
var img_list = "";
var pay_data_seq = [];
var pay_status_arr = $.parseJSON('<?=json_encode($pay_status_arr)?>');
var banks = $.parseJSON('<?=json_encode($banks)?>');
var jbookOrderStatusList = ['주문접수', '결제완료', '상품준비중', '출고완료'];
var jbookOrderClaimStatusList = {'0': '', '40': '취소요청', '41': '취소접수','42': '취소진행','44': '취소완료','50': '결제시도','51': 'PG에러','54': '결제실패'};
var jbookInsStatusList = {'0': '','1': '검수중','2': 'Boxing','3': '검수완료','10': '검수불가','11': '입고대기'};
console.log(banks);

$(function(){
	get_item_payment_log();
	/*
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_item_order_list",
			order_num: no
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				if(data.data && data.data.length > 0){
					$.each(data.data, function(i, v){
						var pay_type = (v.pay_type == "1")? "<span class='card'>카드</span>" : "<span class='bank'>계좌</span>";
						var shipping_company = (v.shipping_company && v.shipping_company != "")? v.shipping_company : "한진택배";
						$("#item_order_detail .pay_status").html(pay_status_arr[v.pay_status]+'/'+pay_type);
						$("#item_order_detail .order_num").text("주문번호:"+v.order_num);
						$("#item_order_detail .pay_dt").text(v.pay_dt);
						$("#item_order_detail input[name='shipping_name']").val(v.shipping_name);
						$("#item_order_detail input[name='shipping_cellphone']").val(v.shipping_cellphone);
						if(v.shipping_zipcode && v.shipping_zipcode != ""){
							$("#item_order_detail input[name='shipping_zip']").val(v.shipping_zipcode);
							$("#item_order_detail input[name='shipping_addr']").val(v.shipping_addr);
							$("#item_order_detail input[name='shipping_addr2']").val(v.shipping_addr2);
						}else{
							$("#item_order_detail input[name='shipping_zip']").val(v.shipping_addr.split("|")[0]);
							$("#item_order_detail input[name='shipping_addr']").val(v.shipping_addr.split("|")[1]);
						}
						$("#item_order_detail input[name='shipping_memo']").val(v.shipping_memo);
						$("#item_order_detail .product_price").text(v.product_price.format()+"원");
						$("#item_order_detail .shipping_price").text(v.shipping_price.format()+"원");
						$("#item_order_detail .point_price").text(v.point_price.format()+"원");
						$("#item_order_detail .total_price").text(v.total_price.format()+"원");
						$("#item_order_detail input[name='shipping_invoice']").val(v.shipping_invoice);
						$("#item_order_detail select[name='shipping_company']").val(shipping_company);
						$("#item_order_detail select[name='pay_status']").val(v.pay_status);

						// 정글북 결제내역 여부
						if(v.jbOrdNo && v.jbOrdNo != ""){
							get_item_payment_log_jbook(v.order_num, v.jbOrdNo);
						}

						img_list = v.product_img;
						var pay_data = $.parseJSON(v.pay_data.replace(/\\/g, ''));
						var cart_html = '';
						console.log(pay_data);
						$.each(pay_data, function(i, v){
							cart_html += '<tr>';
							cart_html += '	<td style="padding:10px 0; min-width: 50px;" class="product_img"><img src="/pet/images/ex_image.png" width="100%"></td>';
							cart_html += '	<td style="padding:10px 0 10px 10px;">';
							cart_html += '		<p style="font-size:12px;">'+v.txt+'</p>';
							cart_html += '		<p style="margin-top:10px;">'+v.amount+'개 / '+v.value.format()+'원</p>';
							cart_html += '	</td>';
							cart_html += '</tr>';
							pay_data_seq.push(v.seq);
						});
						$("#item_order_detail .item_option_wrap").html("").html(cart_html);

						// 계좌이체 여부
						if(v.pay_status == "2"){
							$("#item_order_detail .bank_chk_wrap").addClass("on");
						}else{
							$("#item_order_detail .bank_chk_wrap").removeClass("on");
						}

						// 배송진행 알림톡 발송 여부
						if(v.pay_status == "5" && v.shipping_invoice != ""){
							$("#item_order_detail .set_update_pay_status_use_talk_btn").addClass("on");
						}else{
							$("#item_order_detail .set_update_pay_status_use_talk_btn").removeClass("on");
						}

						// 취소 여부
						if(v.pay_status == "7"){
							$("#item_order_detail .bank_cancel_wrap").addClass("on");
						}else{
							$("#item_order_detail .bank_cancel_wrap").removeClass("on");
						}

						// 고객 취소/반품 여부
						if(v.is_cancel == '2'){
							var html = '';
							var cancel_reason = {
								'1' : '단순변심',
								'2' : '상품불량',
								'3' : '제품변경',
								'etc' : '기타'
							};

							html += '<div class="cancel_box">';
							html += '	고객 취소사유 : '+cancel_reason[v.cancel_result.split('|')[0]]+' ('+v.cancel_dt+')';
							html += (v.cancel_result.split('|')[0] == 'etc')? '<span>'+v.cancel_result.split('|')[1]+'</span>' : '';
							if(v.cancel_result2.split('|')[1] == "2"){
								html += '	<br/>(환불계좌 : '+banks[fillZero(3, v.cancel_result2.split('|')[3])]+' '+v.cancel_result2.split('|')[2]+' '+v.cancel_result2.split('|')[4].format()+'원)';
							}
							html += '</div>';

							$("#item_order_detail .order_box_title").after(html);
						}

						if(v.is_return == '2'){
							var html = '';
							var return_reason = {
								'1' : '단순변심',
								'2' : '상품불량',
								'3' : '제품변경',
								'etc' : '기타'
							};

							html += '<div class="return_box">';
							html += '	고객 반품사유 : '+return_reason[v.return_result.split('|')[0]]+' ('+v.return_dt+')';
							html += (v.return_result.split('|')[0] == 'etc')? '<span>'+v.return_result.split('|')[1]+'</span>' : '';
							if(v.return_result2.split('|')[1] == "2"){
								html += '	<br/>(환불계좌 : '+banks[fillZero(3, v.return_result2.split('|')[3])]+' '+v.return_result2.split('|')[2]+' '+v.return_result2.split('|')[4].format()+'원)';
							}
							html += '</div>';

							$("#item_order_detail .order_box_title").after(html);
						}

						// img_loading
						console.log(img_list);

						img_list = img_list.split(',').filter(function(item) {
							return item !== null && item !== undefined && item !== '';
						});
						var tmp_img_list = img_list.join(',');

						if(typeof img_list != "undefined" && img_list != ""){
							$.ajax({
								url: '<?=$mainpage_directory?>/fileupload_ajax.php',
								data: {
									mode : "get_file_list",
									file_list: tmp_img_list
								},
								type: 'POST',
								dataType: 'JSON',
								success: function(data) {
									if(data.code == "000000"){
										console.log(data.data); 
										var html = '';
										html += '<div style="background-image: url(\''+data.data[0].file_path+'\'); width: 80px; height: 80px; background-size:cover; background-position: center; background-repeat: no-repeat; border-radius: 5px;"></div>';

										$("#item_order_detail .product_img").html("").html(html);
									}else{
										alert(data.data+"("+data.code+")");
										console.log(data.code);
									}
								},
								error: function(xhr, status, error) {
									alert(error + "네트워크에러");
								}
							});
						}else{
							if(v.goodsRepImage && v.goodsRepImage != ""){
								$("#item_order_detail .product_img").html('<div style="background-image: url(\''+v.goodsRepImage+'\'); width: 80px; height: 80px; background-size:cover; background-position: center; background-repeat: no-repeat; border-radius: 5px;"></div>');
							}
						}
					});
				}else{
					$.MessageBox({
						buttonDone: "확인",
						message: "상품이 삭제 되었거나 잘못된 결제번호입니다.<br/> 리스트로 돌아갑니다."
					}).done(function(){
						location.href = "<?=$admin_directory ?>/item_payment_log.php";
					});
				}
			}else{
				alert(data.data+"("+data.code+")");
				console.log(data.code);
			}
		},
		error: function(xhr, status, error) {
			alert(error + "네트워크에러");
		}
	});	
	*/
});

$(document).on("click", "#item_order_detail .set_update_bank_deposit_btn", function(){
	var pay_status = '3';
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "set_update_bank_deposit",
			pay_status : pay_status,
			use_talk : "0",
			order_num : no
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data); 
				$("#item_order_detail .pay_status").text(pay_status_arr[pay_status]);
				$("#item_order_detail select[name='pay_status']").val(pay_status);
				$(".bank_chk_wrap").removeClass("on");
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

/*
$(document).on("click", "#item_order_detail .set_update_bank_cancel_btn", function(){
	var cancel_type = $("#item_order_detail select[name='cancel_type'] option:selected").val();
	var reason_type = $("#item_order_detail select[name='reason_type'] option:selected").val();
	var reason_detail = $("#item_order_detail textarea[name='reason_detail']").val();
	var cancel_bank = $("#item_order_detail select[name='cancel_bank'] option:selected").val();
	var cancel_account = $("#item_order_detail input[name='cancel_account']").val();
	var cancel_price = $("#item_order_detail input[name='cancel_price']").val();
	console.log(cancel_type, reason_type, reason_detail, cancel_bank, cancel_account, cancel_price);
	console.log(pay_data_seq);
});
*/

$(document).on("click", "#item_order_detail .set_update_bank_deposit_use_talk_btn", function(){
	var pay_status = '3';
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "set_update_bank_deposit",
			pay_status : pay_status,
			use_talk : "1",
			order_num : no
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data); 
				$("#item_order_detail .pay_status").text(pay_status_arr[pay_status]);
				$("#item_order_detail select[name='pay_status']").val(pay_status);
				$(".bank_chk_wrap").removeClass("on");
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

$(document).on("click", "#item_order_detail .set_update_shipping_invoice_btn", function(){
	var shipping_invoice = $("#item_order_detail input[name='shipping_invoice']").val();
	var shipping_company = $("#item_order_detail select[name='shipping_company'] option:selected").val();
	console.log(shipping_invoice);

	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "set_update_shipping_invoice",
			shipping_invoice: shipping_invoice,
			shipping_company: shipping_company,
			order_num: no
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data); 
				$.MessageBox("송장번호가 변경되었습니다.");
				if($("#item_order_detail select[name='pay_status'] option:selected").val() == "5" && $("#item_order_detail input[name='shipping_invoice']").val() != ""){
					$("#item_order_detail .set_update_pay_status_use_talk_btn").addClass("on");
				}else{
					$("#item_order_detail .set_update_pay_status_use_talk_btn").removeClass("on");
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
});

$(document).on("click", "#item_order_detail .set_update_pay_status_btn", function(){
	var pay_status = $("#item_order_detail select[name='pay_status'] option:selected").val();
	var shipping_invoice = $("#item_order_detail input[name='shipping_invoice']").val();
	console.log(pay_status);

	if(pay_status != ""){
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "set_update_pay_status",
				pay_status: pay_status,
				shipping_invoice: shipping_invoice,
				use_talk: "0",
				order_num: no
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data); 
					$("#item_order_detail .pay_status").text(pay_status_arr[pay_status]);
					if(pay_status == "2"){ // 계좌이체 여부
						$(".bank_chk_wrap").addClass("on");
					}else{
						$(".bank_chk_wrap").removeClass("on");
					}
					/*
					if(pay_status == "7"){ // 계좌이체 여부
						$(".bank_cancel_wrap").addClass("on");
					}else{
						$(".bank_cancel_wrap").removeClass("on");
					}
					*/
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
});

$(document).on("change", "#item_order_detail select[name='pay_status']", function(){
	if($("#item_order_detail select[name='pay_status'] option:selected").val() == "5" && $("#item_order_detail input[name='shipping_invoice']").val() != ""){
		$("#item_order_detail .set_update_pay_status_use_talk_btn").addClass("on");
	}else{
		$("#item_order_detail .set_update_pay_status_use_talk_btn").removeClass("on");
	}
});

$(document).on("click", "#item_order_detail .set_update_pay_status_use_talk_btn", function(){
	var pay_status = $("#item_order_detail select[name='pay_status'] option:selected").val();
	var shipping_invoice = $("#item_order_detail input[name='shipping_invoice']").val();
	var shipping_company = $("#item_order_detail select[name='shipping_company'] option:selected").val();
	console.log(pay_status);

	if(pay_status != ""){
		if(pay_status == "5" && shipping_invoice != ""){
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "set_update_pay_status",
					pay_status: pay_status,
					shipping_invoice: shipping_invoice,
					shipping_company: shipping_company,
					use_talk: "1",
					order_num: no
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data); 
						$.MessageBox("발송되었습니다.");
						$("#item_order_detail .pay_status").text(pay_status_arr[pay_status]);
						if(pay_status == "2"){ // 계좌이체 여부
							$(".bank_chk_wrap").addClass("on");
						}else{
							$(".bank_chk_wrap").removeClass("on");
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
		}else{
			$.MessageBox("송장번호가 필요합니다.");
		}
	}
});

$(document).on("click", "#item_order_detail .set_update_shipping_info_btn", function(){
	var shipping_name = $("#item_order_detail input[name='shipping_name']").val();
	var shipping_cellphone = $("#item_order_detail input[name='shipping_cellphone']").val();
	var shipping_zip = $("#item_order_detail input[name='shipping_zip']").val();
	var shipping_addr = $("#item_order_detail input[name='shipping_addr']").val();
	var shipping_addr2 = $("#item_order_detail input[name='shipping_addr2']").val();
	var shipping_memo = $("#item_order_detail input[name='shipping_memo']").val();

	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "set_update_shipping_info",
			shipping_name: shipping_name,
			shipping_cellphone: shipping_cellphone,
			shipping_zipcode: shipping_zip,
			shipping_addr: shipping_addr,
			shipping_addr2: shipping_addr2,
			shipping_memo: shipping_memo,
			order_num: no
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data); 
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

//주소 검색
$(document).on("click", "#item_order_detail .search_addr_btn", function(){
	$("#search_addr_wrap").dialog({
		modal: true,
		title: '우편번호 검색',
		autoOpen: true,
		maxWidth: "96%",
		minHeight: Number($(window).height()) - 200,
		width: "96%",
		height: Number($(window).height()) - 200,
		autoSize: true,
		resizable: false,
		draggable: false,
		open: function(event, ui) {
			// to do something...
			execDaumPostcode($("#item_order_detail"));
		},
		close: function() {
			// to do something...
		}
	});
});

function get_item_payment_log_jbook(order_num, jbOrdNo){
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_item_payment_log_jbook",
			order_num: order_num,
			jbOrdNo: jbOrdNo
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data); 

				var html = '';
				var order_step_list = {'1': '접수진행','2': '접수완료','9': '거래번호상이(정글북문의필요)'};
				$.each(data.data, function(i, v){
					if(i == 0){
						html += '<div class="jbook_list">';
						html += '	<div class="title">정글북 상세내역</div>';
						html += '	<div class="bjOrderData">';
						html += '		<ul>';
						html += '			<li><div class="title">거래번호</div><div>'+jbOrdNo+'</div></li>';
						html += '			<li><div class="title">접수상태</div><div>'+order_step_list[v.order_step]+'</div></li>';
						html += '			<li><div class="title">접수일시</div><div>'+v.reg_dt+'</div></li>';
						html += '			<li><div class="title">주문상태</div><div class="orderStatus">-</div></li>';
						html += '			<li><div class="title">주문클레임상태</div><div class="orderClaimStatus">-</div></li>';
						html += '			<li><div class="title">검수상태</div><div class="insStatus">-</div></li>';
						html += '			<li><div class="title">배송사명</div><div class="invoiceCompanyNm">-</div></li>';
						html += '			<li><div class="title">송장번호</div><div class="invoiceNo">-</div></li>';
						html += '		</ul>';
						html += '	</div>';
						html += '	<div class="btn_wrap">';
						html += '		<button type="button" class="jbOrderBtn">주문조회</button>';
						//html += '		<button type="button" class="jbInvoiceBtn">송장번호확인</button>';
						html += '	</div>';
						html += '</div>';
					}
				});

				$(document).find(".item_payment").after(html);
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

function get_item_payment_log(){
	return new Promise(function(resolve, reject) {
		$.ajax({
			type: 'post',
			url: "<?=$item_directory ?>/item_list_ajax.php",
			data: {
				mode: "get_item_payment_log",
				order_num : no
			},
			dataType: 'json',
			success: function(json) {
				if(json.code == "000000"){
					console.log(json.data);
					var html = '';
					var idx = 0;
				
					$.each(json.data, function(i, v){
						var pay_type = (v.pay_type == "1")? "<span class='card'>카드</span>" : "<span class='bank'>계좌</span>";
						var shipping_company = (v.shipping_company && v.shipping_company != "")? v.shipping_company : "한진택배";
						$("#item_order_detail .pay_status").html(pay_status_arr[v.pay_status]+'/'+pay_type);
						$("#item_order_detail .order_num").text("주문번호:"+v.order_num);
						$("#item_order_detail .pay_dt").text(v.pay_dt);
						$("#item_order_detail input[name='shipping_name']").val(v.shipping_name);
						$("#item_order_detail input[name='shipping_cellphone']").val(v.shipping_cellphone);
						if(v.shipping_zipcode && v.shipping_zipcode != ""){
							$("#item_order_detail input[name='shipping_zip']").val(v.shipping_zipcode);
							$("#item_order_detail input[name='shipping_addr']").val(v.shipping_addr);
							$("#item_order_detail input[name='shipping_addr2']").val(v.shipping_addr2);
						}else{
							$("#item_order_detail input[name='shipping_zip']").val(v.shipping_addr.split("|")[0]);
							$("#item_order_detail input[name='shipping_addr']").val(v.shipping_addr.split("|")[1]);
						}
						$("#item_order_detail input[name='shipping_memo']").val(v.shipping_memo);
						$("#item_order_detail .product_price").text(v.product_price.format()+"원");
						$("#item_order_detail .shipping_price").text(v.shipping_price.format()+"원");
						$("#item_order_detail .point_price").text(v.point_price.format()+"원");
						$("#item_order_detail .total_price").text(v.total_price.format()+"원");
						$("#item_order_detail input[name='shipping_invoice']").val(v.shipping_invoice);
						$("#item_order_detail select[name='shipping_company']").val(shipping_company);
						$("#item_order_detail select[name='pay_status']").val(v.pay_status);

						// 정글북 결제내역 여부
						if(v.jbOrdNo && v.jbOrdNo != ""){
							get_item_payment_log_jbook(v.order_num, v.jbOrdNo);
						}

						var pay_dt = (v.pay_dt != null)? new Date(v.pay_dt) : "";
						pay_dt = (pay_dt != "")? pay_dt.getFullYear()+'-'+fillZero(2, (pay_dt.getMonth()+1))+'-'+fillZero(2, pay_dt.getDate()) : ((v.pay_dt == null && v.pay_type == "2")? "입금 대기중" : "결제 대기중");
						var color_red = (v.pay_status == 7 || v.pay_status == 8 || v.pay_status == 9)? "red" : "";
						var pay_data_list = (v.pay_data && v.pay_data.indexOf('||') != -1)? v.pay_data.split('||') : [v.pay_data];

						html += '<div class="item_payment_log">';
						html += '	<div class="item_cart">';
						$.each(pay_data_list, function(i2, v2){
							html += '	<div class="item_cart_wrap" data-id="'+v.ip_log_seq+'" data-item_no="'+i2+'"></div>';
						});
						html += '	</div>';
						html += '</div>';
						
						idx++;
					});

					if(idx == 0){
						html += '<div>';
						html += '	<div class="no_data">구매한 내역이 없습니다.</div>';
						html += '</div>';
					}

					$("#item_order_detail .item_option_wrap").html(html);

					$.each(json.data, function(i, v){ // 상품 리스트
						var pay_data_list = (v.pay_data && v.pay_data.indexOf('||') != -1)? v.pay_data.split('||') : [v.pay_data];
						var shipping_invoice = (v.pay_status == 4 && v.shipping_invoice != "" && v.shipping_company != "")? v.shipping_invoice : "";
						var shipping_company = (v.pay_status == 4 && v.shipping_invoice != "" && v.shipping_company != "")? v.shipping_company : "";
						var p = $.when();
						var c = 0;

						$.each(pay_data_list, function(i2, v2){
							var pay_data = $.parseJSON(v2.replace(/\\/g, ''));

							p = p.then(function(){
								c++;
								if(c == pay_data.length){
									resolve();
								}
								return get_item(v.ip_log_seq, i2, pay_data, v.product_name, v.pay_status, shipping_invoice, shipping_company, v.order_num);
							});
						});
					});
				}else{
					alert(json.data+"("+json.code+")");
					console.log(json.data);
				}
			},
			error: function() {
			},
			complete: function() {
				// console.log('complete');
				//서브밋 차단 해제
				$("#loading").hide();
			}
		});
	});
}

function get_item(ip_log_seq, target, pay_data, product_name, pay_status, shipping_invoice, shipping_company, order_num){
	return new Promise(function(resolve, reject) {
		var cnt = 0;
		var html = '';
		
		html += '<div class="item_cart_data">';
		html += '	<div class="item_image" style=" background-image: url(\'/pet/images/product_img.png\');"></div>';
		html += '	<div class="item_detail">';
			html += '		<div class="item_pay_status">'+pay_status_arr[pay_status]+'</div>';
		$.each(pay_data, function(i, v){
			console.log(v);
			//var item_name = (v.seq && v.seq != "")? product_name+' / '+v.txt : v.txt;
			var item_name = v.txt;
			html += '		<div class="item_name">'+item_name+'</div>';
			html += '		<div class="item_price">'+v.value.format()+'원 x '+v.amount+'개</div>';
		});
		//if(shipping_invoice != ""){
		//	html += '		<div class="shipping_invoice">['+shipping_company+']'+shipping_invoice+'</div>';
		//}
		html += '	</div>';

		$("#item_order_detail .item_option_wrap .item_cart_wrap[data-id='"+ip_log_seq+"'][data-item_no='"+target+"']").append(html);

		$.each(pay_data, function(i, v){ // thumbnail - product_no
			var post_data = {};
			if(v.seq && v.seq != ""){
				post_data.mode = "get_item_option";
				post_data.io_seq = v.seq;
			}else{
				post_data.mode = "get_item";
				post_data.il_seq = v.il_seq;
			}

			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: post_data,
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						var html = '';
						if(data.data && data.data.length > 0){
							$.each(data.data, function(i, v){
								var html = '';
								// 상태별 버튼 노출여부
								html += '<div class="btn_wrap">';
								if(pay_status == 1){
									html += '	<button type="button" class="cancel_btn" data-no="'+order_num+'">취소요청</button>';
								}else if(pay_status == 2){
									html += '	<button type="button" class="cancel_btn" data-no="'+order_num+'">취소요청</button>';
								}else if(pay_status == 3){
									html += '	<button type="button" class="cancel_btn" data-no="'+order_num+'">취소요청</button>';
								}else if(pay_status == 4){
								}else if(pay_status == 5){
									html += '	<button type="button" class="return_please_btn" data-no="'+order_num+'">반품요청</button>';
									if(shipping_invoice != ""){
										html += '	<button type="button" class="quick_search_btn" data-shipping_invoice="'+shipping_invoice+'" data-shipping_company="'+shipping_company+'">배송조회</button>';
										html += '	<button type="button" class="shipping_done_btn" data-no="'+order_num+'">수취확인</button>';
									}
								}else if(pay_status == 6){
									html += '	<button type="button" class="return_please_btn" data-no="'+order_num+'">반품요청</button>';
									html += '	<button type="button" class="review_btn" data-no="'+order_num+'">리뷰작성</button>';
								}else if(pay_status == 7 || pay_status == 8 || pay_status == 9){
								}else{
								}

								html += '</div>';

								//$("#item_order_detail .item_option_wrap .item_cart_wrap[data-id='"+ip_log_seq+"'][data-item_no='"+target+"'] .item_cart_data").append(html);
								$("#item_order_detail .item_option_wrap .item_cart_wrap[data-id='"+ip_log_seq+"'][data-item_no='"+target+"'] .item_cart_data").attr('data-id', v.product_no);
								get_item_image('.item_cart_wrap[data-id="'+ip_log_seq+'"][data-item_no="'+target+'"] .item_cart_data', v.product_no);
							});

							cnt++;
							if(cnt == pay_data.length){
								resolve();
							}
						}else{
							// 삭제된 상품이 있음
							//$("#item_order_detail .item_option_wrap .item_cart_wrap[data-id='"+ip_log_seq+"'][data-item_no='"+target+"'] .item_cart_data .item_detail").append('<div class="not_found">상품 없음</div>');
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
		});
	});
}

function get_item_image(target, product_no){
	return new Promise(function(resolve, reject) {
		if(product_no && product_no != ""){ 
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_item_list",
					product_no : product_no
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						var html = '';
						$.each(data.data.list, function(i, v){
							get_file_list(target, v.product_no, v.product_img, v.goodsRepImage);
						});
						resolve();
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
	});
}

function get_file_list(target, product_no, img_list, goodsRepImage){
	return new Promise(function(resolve, reject) {
		//console.log(img_list);
		// img_loading
		if(img_list && img_list != ""){
			$.ajax({
				url: '<?=$mainpage_directory ?>/fileupload_ajax.php',
				data: {
					mode : "get_file_list",
					file_list: img_list
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						//console.log(data.data);
						var html = '';
						if(data.data && data.data.length > 0){
							$.each(data.data, function(i, v){
								if(i == 0){
									$("#item_order_detail .item_option_wrap").find(target+"[data-id='"+product_no+"'] .item_image").css("background-image", "url('"+v.file_path+"')");
								}
							});
						}

						resolve();
					}else{
						alert(data.data+"("+data.code+")");
						console.log(data.code);
					}
				},
				error: function(xhr, status, error) {
					//alert(error + "네트워크에러");
					if(xhr.status != 0){
						alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
					}
				}
			});
		}else{
			if(goodsRepImage != ""){
				$("#item_order_detail .item_option_wrap").find(target+"[data-id='"+product_no+"'] .item_image").css("background-image", "url('"+goodsRepImage+"')");
			}else{
				$("#item_order_detail .item_option_wrap").find(target+"[data-id='"+product_no+"'] .item_image").css("background-image", "url('../images/product_img.png')");
			}
		}
	});
}

$(document).on("click", "#item_order_detail .jbOrderBtn", function(){
	get_jbook_order(no); // no
});

$(document).on("click", "#item_order_detail .jbInvoiceBtn", function(){
	get_jbook_invoice(no); // no
});

function get_jbook_invoice(option){
	console.log(option);
	$.ajax({
		url: '<?=$admin_directory?>/jbook_item_ajax.php',
		data: {
			mode : "get_item_invoice",
			option: option
		},
		type: 'POST',
		dataType: 'JSON',
		async: false,
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				$(document).find("#item_order_detail .jbook_list .invoiceNo").text(data.data.invoiceNo);
			}else{
				alert(data.data+"("+data.code+")");
				console.log(data.code);
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

function get_jbook_order(option){
	console.log(option);
	$.ajax({
		url: '<?=$admin_directory?>/jbook_item_ajax.php',
		data: {
			mode : "get_item_order",
			option: option
		},
		type: 'POST',
		dataType: 'JSON',
		async: false,
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				$(document).find("#item_order_detail .jbook_list .orderStatus").text(jbookOrderStatusList[data.data.orderStatus]);
				$(document).find("#item_order_detail .jbook_list .orderClaimStatus").text(jbookOrderClaimStatusList[data.data.orderClaimStatus]);
				$(document).find("#item_order_detail .jbook_list .insStatus").text(jbookInsStatusList[data.data.insStatus]);
				$(document).find("#item_order_detail .jbook_list .invoiceCompanyNm").text(data.data.invoiceCompanyNm);
				$(document).find("#item_order_detail .jbook_list .invoiceNo").text(data.data.invoiceNo);
			}else{
				alert(data.data+"("+data.code+")");
				console.log(data.code);
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

// 우편번호 찾기 찾기 화면을 넣을 element
var element_wrap = document.getElementById('search_addr_wrap');

function execDaumPostcode(target) {
	// 현재 scroll 위치를 저장해놓는다.
	var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
	new daum.Postcode({
		oncomplete: function(data) {
			// 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

			// 각 주소의 노출 규칙에 따라 주소를 조합한다.
			// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
			var addr = ''; // 주소 변수
			var extraAddr = ''; // 참고항목 변수

			//사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
			/*
			if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
				addr = data.roadAddress;
			} else { // 사용자가 지번 주소를 선택했을 경우(J)
				addr = data.jibunAddress;
			}
			*/

			// 사용자가 선택한 주소가 도로명 타입일때 참고항목을 조합한다.
			if(data.userSelectedType === 'R'){
				// 법정동명이 있을 경우 추가한다. (법정리는 제외)
				// 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
				if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
					extraAddr += data.bname;
				}
				// 건물명이 있고, 공동주택일 경우 추가한다.
				if(data.buildingName !== '' && data.apartment === 'Y'){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
				if(extraAddr !== ''){
					extraAddr = ' (' + extraAddr + ')';
				}
				// 조합된 참고항목을 해당 필드에 넣는다.
				//document.getElementById("extraAddress").value = extraAddr;
				target.find(".extraAddress").val(extraAddr);
			
			} else {
				//document.getElementById("extraAddress").value = '';
				target.find(".extraAddress").val('');
			
			}
			/*
			// 우편번호와 주소 정보를 해당 필드에 넣는다.
			document.getElementById('postcode').value = data.zonecode;
			document.getElementById("roadAddress").value = data.roadAddress;
			document.getElementById("jibunAddress").value = data.jibunAddress;
			// 커서를 상세주소 필드로 이동한다.
			document.getElementById("detailAddress").focus();
			*/
			//target.find(".postcode").val(data.zonecode);
			//target.find(".roadAddress").val(data.roadAddress);
			//target.find(".jibunAddress").val(data.jibunAddress);
			target.find("input[name='shipping_zip']").val(data.zonecode);
			target.find("input[name='shipping_addr']").val(data.roadAddress+" "+extraAddr+" ").focus();

			// iframe을 넣은 element를 안보이게 한다.
			// (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
			element_wrap.style.display = 'none';
			$("#search_addr_wrap").dialog('close');

			// 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
			document.body.scrollTop = currentScroll;
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

//남는 길이만큼 0으로 채움
function fillZero(width, str){
	var str = String(str);//문자열 변환
	return str.length >= width ? str:new Array(width-str.length+1).join('0')+str;
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