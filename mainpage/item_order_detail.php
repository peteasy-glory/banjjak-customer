<?php
include "../include/top.php";
include "../include/Crypto.class.php";
include "../Mobile_Detect.php";

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

$reason_type_arr = array(
	"1" => "단순변심", 
	"2" => "상품불량", 
	"3" => "제품변경", 
	"etc" => "기타"
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

if($user_id == ""){
?>
    <script>
        $.MessageBox({
            buttonDone: "확인",
            message: "잘못된 접근입니다. 메인페이지로 이동합니다."
        }).done(function() {
            location.href = "<?= $mainpage_directory ?>/index.php";
        });
    </script>	
<?php
	return false;
}

?>

<script src="../js/fontawesome.min.js"></script>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<style>
	#item_order_detail { margin-top: 61px; }
	#item_order_detail .order_box .order_box_title { position: relative; height: 20px; }
	#item_order_detail .order_box .order_box_title span.pay_status { position: absolute; left: 20px; top: 15px; font-size: 16px; }
	#item_order_detail .order_box .order_box_title span.order_num { position: absolute; right: 25px; top: 20px; }
	#item_order_detail .order_box .button_wrap { margin-top: 30px; padding-bottom: 60px; }
	#item_order_detail .order_box a.return_please { width: 100%; margin: 0px 0px 5px 0px; color: #ddd; border: 1px solid #ddd; }
	#item_order_detail .order_box a.quick_search { width: 100%; margin: 0px 0px 5px 0px; }
	#item_order_detail .order_box a.shipping_done { border: 1px solid #f5bf2e; border-radius: 6px; color: #f5bf2e; width: 100%; padding: 5px 0px; text-decoration: none; line-height: 30px; margin: 0px 0px 5px 0px; }
	#item_order_detail .order_box a.review_btn { border: 1px solid #f5bf2e; border-radius: 6px; color: #f5bf2e; width: 100%; padding: 5px 0px; text-decoration: none; line-height: 30px; margin: 0px 0px 5px 0px; }
	#item_order_detail .order_box a.cancel_please { border: 1px solid #ddd; border-radius: 6px; color: #ddd; width: 100%; padding: 5px 0px; text-decoration: none; line-height: 30px; margin: 0px 0px 5px 0px; }

	#item_order_detail .order_box .item_payment_log { margin: 10px 0px; border-radius: 10px; font-family: 'NL2GR'; }
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
	#item_order_detail .order_box .item_payment_log .item_cart .item_cart_wrap .item_cart_data .item_detail .item_name { width: calc(100% - 10px); }
	#item_order_detail .order_box .item_payment_log .item_cart .item_cart_wrap .item_cart_data .item_detail .item_price { font-weight: Bold; }
	#item_order_detail .order_box .item_payment_log .item_cart .item_cart_wrap .btn_wrap { text-align: right; padding: 10px; }

	#item_order_detail .jbook_shipping_data { font-family: 'NL2GR'; }
	#item_order_detail .jbook_shipping_data table tr td:first-child { font-weight: Bold; }
	#item_order_detail .jbook_shipping_data table tr td>div { margin-top: 5px; }
	#item_order_detail .order_box a.quick_search2 { height: 30px; line-height: 30px; text-align: center; padding: 0px 10px; background-color: #fff; border: 1px solid #f5bf2e; color: #f5bf2e; border-radius: 10px; }

</style>
<div id="item_order_detail">
	<div class="top_menu">
		<?php if($backurl){ ?>
			<div class="header-back-btn"><a href="<?= $backurl ?>"><img src="/pet/images/btn_back_2.png" width="26px"></a></div>
		<?php }else{ ?>
			<div class="header-back-btn"><a href="<?= $mainpage_directory ?>/mainpage_my_menu.php"><img src="/pet/images/btn_back_2.png" width="26px"></a></div>
		<?php } ?>
        <div class="top_title">
            <p>결제/주문 상세내역</p>
        </div>
    </div>
	<div class="order_box">
		<div class="order_detail_wrap">
			<div class="order_box_title">
				<span class="pay_status"></span>
				<span class="order_num"></span>
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
							<td style="text-align: right;padding-bottom: 5px;" class="pay_dt"></td>
						</tr>
						<tr>
							<td colspan="2" style="padding:5px 0 2px;font-size:12px;">받으시는 분 : <span class="shipping_name"></span></td>
						</tr>
						<tr>
							<td colspan="2" style="padding:2px 0;font-size:12px;">연락처 : <span class="shipping_cellphone"></span></td>
						</tr>
						<tr>
							<td colspan="2" style="padding:2px 0;font-size:12px;">받으시는 곳 : <span class="shipping_addr"></span></td>
						</tr>
						<tr>
							<td colspan="2" style="padding:2px 0;font-size:12px;">배송요청사항 : <span class="shipping_memo"></span></td>
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
					<tbody>
						<tr>
							<td style="padding:5px 0 2px;font-size:12px;">상품가격</td>
							<td style="text-align: right;padding:5px 0 2px;font-size:12px;" class="product_price">0원</td>
						</tr>
						<tr style="border-bottom:2px solid #ddd;">
							<td style="padding:5px 0;font-size:12px;">배송비</td>
							<td style="text-align: right;padding-bottom: 5px;font-size:12px;" class="shipping_price">0원</td>
						</tr>
						<tr>
							<td style="padding:5px 0 2px;font-size:12px;">포인트사용</td>
							<td style="text-align: right;padding:5px 0 2px;font-size:12px;" class="point_price">0원</td>
						</tr>
						<tr>
							<td style="padding:10px 0 2px;">총 결제 금액</td>
							<td style="text-align: right;padding:5px 0 2px;" class="total_price">0원</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="order_box_text" style="">
				<table width="100%" class="order_table">
					<colgroup>
						<col width="30%">
						<col width="70%">
					</colgroup>
					<thead>
						<tr style="border-bottom:2px solid #ddd;">
							<td colspan="2"style="padding-bottom: 5px;">주문상품</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="2">
								<div class="item_option_wrap"></div>
							</td>
						</tr>
					</tbody>
					<!--tbody class="item_option_wrap">
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
						</tr->
					</tbody-->
				</table>
			</div>
		</div>
		<div class="order_box_text_none button_wrap" style="text-align: center;">
			<!-- a href="javascript:;" class="return_please">반품하기</a><a href="javascript:;" class="quick_search">배송조회</a -->
		</div>
	</div>
</div>
<script>
var no = "<?=$r_no ?>";
var img_list = [];
var pay_status_arr = $.parseJSON('<?=json_encode($pay_status_arr)?>');
var reason_type_arr = $.parseJSON('<?=json_encode($reason_type_arr)?>');
var bank = $.parseJSON('<?=json_encode($banks)?>');

$(function(){
	get_item_payment_log();
	/*
	$.ajax({
		url: '<?=$mainpage_directory?>/item_list_ajax.php',
		data: {
			mode : "get_item_order_list",
			order_num: no
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				$.each(data.data, function(i, v){
					if(i != 0){
						return false;
					}
					$("#item_order_detail .pay_status").text(pay_status_arr[v.pay_status]);
					$("#item_order_detail .order_num").text("주문번호:"+v.order_num);
					$("#item_order_detail .pay_dt").text(v.pay_dt);
					$("#item_order_detail .shipping_name").text(v.shipping_name);
					$("#item_order_detail .shipping_cellphone").text(v.shipping_cellphone);
					if(v.shipping_zipcode && v.shipping_zipcode != ""){
						$("#item_order_detail .shipping_addr").text("("+v.shipping_zipcode+")"+v.shipping_addr+" "+v.shipping_addr2);
					}else{
						$("#item_order_detail .shipping_addr").text("("+v.shipping_addr.split("|")[0]+")"+v.shipping_addr.split("|")[1]);
					}
					$("#item_order_detail .shipping_memo").text(v.shipping_memo);
					$("#item_order_detail .product_price").text(v.product_price.format()+"원");
					$("#item_order_detail .shipping_price").text(v.shipping_price.format()+"원");
					$("#item_order_detail .point_price").text(v.point_price.format()+"원");
					$("#item_order_detail .total_price").text(v.total_price.format()+"원");

					// 상태별 버튼 노출여부
					if(v.pay_status == 1){
						$("#item_order_detail .order_box .button_wrap").append('<a href="javascript:;" class="cancel_please">취소요청</a><br/>');
					}else if(v.pay_status == 2){
						var html = '';
						var bank_name = (v.bank_name && v.bank_name != "")? v.bank_name : v.guest_info.split(',')[0];
						html += '<div class="order_box_text"style="">';
						html += '	<table width="100%"class="order_table">';
						html += '		<colgroup>';
						html += '			<col width="30%">';
						html += '			<col width="70%">';
						html += '		</colgroup>';
						html += '		<tbody>';
						html += '			<tr>';
						html += '				<td style="padding-bottom: 5px;">입금자명</td>';
						html += '				<td style="text-align: right;padding-bottom: 5px;" class="bank_name">'+bank_name+'</td>';
						html += '			</tr>';
						html += '			<tr>';
						html += '				<td style="padding-bottom: 5px;">입금계좌</td>';
						html += '				<td style="text-align: right;padding-bottom: 5px;" class="bank_account">기업은행<br/>054-143076-01-013<br/>주)펫이지</td>';
						html += '			</tr>';
						html += '			<tr>';
						html += '				<td style="padding-bottom: 5px;">입금마감일시</td>';
						html += '				<td style="text-align: right;padding-bottom: 5px;" class="expire_dt">'+v.expire_dt+'</td>';
						html += '			</tr>';
						html += '		</tbody>';
						html += '	</table>';
						html += '</div>';
						$("#item_order_detail .order_box .order_detail_wrap").append(html);
						$("#item_order_detail .order_box .button_wrap").append('<a href="javascript:;" class="cancel_please">취소요청</a><br/>');
					}else if(v.pay_status == 3){
						$("#item_order_detail .order_box .button_wrap").append('<a href="javascript:;" class="cancel_please">취소요청</a><br/>');
					}else if(v.pay_status == 4){
					}else if(v.pay_status == 5){
						if(v.shipping_invoice != ""){
							console.log("!");
							var html = '';
							html += '<div class="order_box_text"style="">';
							html += '	<table width="100%"class="order_table">';
							html += '		<colgroup>';
							html += '			<col width="30%">';
							html += '			<col width="70%">';
							html += '		</colgroup>';
							html += '		<tbody>';
							html += '			<tr>';
							html += '				<td style="padding: 5px 0px;">배송업체</td>';
							html += '				<td style="text-align: right; padding: 5px 0px;" class="shipping_company">'+v.shipping_company+'</td>';
							html += '			</tr>';
							html += '			<tr>';
							html += '				<td style="padding: 5px 0px;">송장번호</td>';
							html += '				<td style="text-align: right; padding: 5px 0px;" class="shipping_invoice">'+v.shipping_invoice+'</td>';
							html += '			</tr>';
							html += '		</tbody>';
							html += '	</table>';
							html += '</div>';
							$("#item_order_detail .order_box .order_detail_wrap").append(html);
							$("#item_order_detail .order_box .button_wrap").append('<a href="javascript:;" class="quick_search" data-shipping_invoice="'+v.shipping_invoice+'" data-shipping_company="'+v.shipping_company+'">배송조회</a><br/>');
							$("#item_order_detail .order_box .button_wrap").append('<a href="javascript:;" class="shipping_done">수취확인</a><br/>');
						}
						$("#item_order_detail .order_box .button_wrap").append('<a href="javascript:;" class="return_please">반품요청</a><br/>');
					}else if(v.pay_status == 6){
						$("#item_order_detail .order_box .button_wrap").append('<a href="javascript:;" class="review_btn">리뷰작성</a><br/>');
						$("#item_order_detail .order_box .button_wrap").append('<a href="javascript:;" class="return_please">반품요청</a><br/>');
					}else if(v.pay_status == 7){
						var html = '';
						html += '<div class="order_box_text">';
						html += '	<table width="100%" class="order_table">';
							html += '		<colgroup>';
							html += '			<col width="30%">';
							html += '			<col width="70%">';
							html += '		</colgroup>';
							html += '		<tbody>';
							if(v.is_cancel == "2"){
								var cancel_result = v.cancel_result.split('|'); // 취소사유 / 기타사유
								var cancel_result_detail = (cancel_result[0] == "etc")? "("+cancel_result[1]+")" : "";
								var cancel_result2 = v.cancel_result2.split('|'); // 상품번호 / 결제타입 / 계좌번호 / 은행 / 환불금액

								html += '			<tr>';
								html += '				<td style="padding-bottom: 5px;">취소사유</td>';
								html += '				<td style="text-align: right;padding-bottom: 5px;">'+reason_type_arr[cancel_result[0]]+cancel_result_detail+'</td>';
								html += '			</tr>';
								if(cancel_result2[1] == "1"){ // card
									if(v.pg_log){
										var pg_log = v.pg_log.split('&');
										var pg_log_card = pg_log[26].split('=')[1];
										html += '			<tr>';
										html += '				<td style="padding-bottom: 5px;">취소내역</td>';
										html += '				<td style="text-align: right;padding-bottom: 5px;">카드결제</td>';
										html += '			</tr>';
										html += '			<tr>';
										html += '				<td style="padding-bottom: 5px;">환불은행</td>';
										html += '				<td style="text-align: right;padding-bottom: 5px;">'+pg_log_card+'</td>';
										html += '			</tr>';
										html += '			<tr>';
										html += '				<td style="padding-bottom: 5px;">환불금액</td>';
										html += '				<td style="text-align: right;padding-bottom: 5px;">'+parseInt(cancel_result2[4]).format()+'원</td>';
										html += '			</tr>';
										html += '			<tr>';
										html += '				<td style="padding-bottom: 5px;">환불일시</td>';
										html += '				<td style="text-align: right;padding-bottom: 5px;">'+v.cancel_dt+'</td>';
										html += '			</tr>';
									}
								}else{ // bank
									html += '			<tr>';
									html += '				<td style="padding-bottom: 5px;">취소내역</td>';
									html += '				<td style="text-align: right;padding-bottom: 5px;">계좌이체</td>';
									html += '			</tr>';
									html += '			<tr>';
									html += '				<td style="padding-bottom: 5px;">환불은행</td>';
									html += '				<td style="text-align: right;padding-bottom: 5px;">'+bank[cancel_result2[3]]+'</td>';
									html += '			</tr>';
									html += '			<tr>';
									html += '				<td style="padding-bottom: 5px;">환불계좌</td>';
									html += '				<td style="text-align: right;padding-bottom: 5px;">'+cancel_result2[2]+'</td>';
									html += '			</tr>';
									html += '			<tr>';
									html += '				<td style="padding-bottom: 5px;">환불금액</td>';
									html += '				<td style="text-align: right;padding-bottom: 5px;">'+parseInt(cancel_result2[4]).format()+'원</td>';
									html += '			</tr>';
									html += '			<tr>';
									html += '				<td style="padding-bottom: 5px;">환불일시</td>';
									html += '				<td style="text-align: right;padding-bottom: 5px;">'+v.cancel_dt+'</td>';
									html += '			</tr>';
								}
							}
							if(v.is_return == "2"){
								var return_result = v.return_result.split('|'); // 반품사유 / 기타사유
								var return_result_detail = (return_result[0] == "etc")? "("+return_result[1]+")" : "";
								var return_result2 = v.return_result2.split('|'); // 상품번호 / 결제타입 / 계좌번호 / 은행 / 환불금액

								html += '			<tr>';
								html += '				<td style="padding-bottom: 5px;">취소사유</td>';
								html += '				<td style="text-align: right;padding-bottom: 5px;">'+reason_type_arr[return_result[0]]+return_result_detail+'</td>';
								html += '			</tr>';
								if(return_result2[1] == "1"){ // card
									if(v.pg_log){
										var pg_log = v.pg_log.split('&');
										var pg_log_card = pg_log[26].split('=')[1];
										html += '			<tr>';
										html += '				<td style="padding-bottom: 5px;">반품내역</td>';
										html += '				<td style="text-align: right;padding-bottom: 5px;">카드결제</td>';
										html += '			</tr>';
										html += '			<tr>';
										html += '				<td style="padding-bottom: 5px;">환불은행</td>';
										html += '				<td style="text-align: right;padding-bottom: 5px;">'+pg_log_card+'</td>';
										html += '			</tr>';
										html += '			<tr>';
										html += '				<td style="padding-bottom: 5px;">환불금액</td>';
										html += '				<td style="text-align: right;padding-bottom: 5px;">'+parseInt(return_result2[4]).format()+'원</td>';
										html += '			</tr>';
										html += '			<tr>';
										html += '				<td style="padding-bottom: 5px;">환불일시</td>';
										html += '				<td style="text-align: right;padding-bottom: 5px;">'+v.return_dt+'</td>';
										html += '			</tr>';
									}
								}else{ // bank
									html += '			<tr>';
									html += '				<td style="padding-bottom: 5px;">반품내역</td>';
									html += '				<td style="text-align: right;padding-bottom: 5px;">계좌이체</td>';
									html += '			</tr>';
									html += '			<tr>';
									html += '				<td style="padding-bottom: 5px;">환불은행</td>';
									html += '				<td style="text-align: right;padding-bottom: 5px;">'+bank[return_result2[3]]+'</td>';
									html += '			</tr>';
									html += '			<tr>';
									html += '				<td style="padding-bottom: 5px;">환불계좌</td>';
									html += '				<td style="text-align: right;padding-bottom: 5px;">'+return_result2[2]+'</td>';
									html += '			</tr>';
									html += '			<tr>';
									html += '				<td style="padding-bottom: 5px;">환불금액</td>';
									html += '				<td style="text-align: right;padding-bottom: 5px;">'+parseInt(return_result2[4]).format()+'원</td>';
									html += '			</tr>';
									html += '			<tr>';
									html += '				<td style="padding-bottom: 5px;">환불일시</td>';
									html += '				<td style="text-align: right;padding-bottom: 5px;">'+v.return_dt+'</td>';
									html += '			</tr>';
								}
							}
							html += '		</tbody>';
						html += '	</table>';
						html += '</div>';
						$("#item_order_detail .order_box .order_detail_wrap").append(html);
					}else if(v.pay_status == 8 || v.pay_status == 9){
					}else{
						console.log("@");
					}

					img_list = v.product_img;

					img_list = data.data[0].product_img;
					img_list = img_list.split(',').filter(function(item) {
						return item !== null && item !== undefined && item !== '';
					});
					var tmp_img_list = img_list.join(',');

					/*
					var pay_data = $.parseJSON(v.pay_data.replace(/\\/g, ''));
					var cart_html = '';
					console.log(pay_data);
					$.each(pay_data, function(i, v){
						cart_html += '<tr>';
						cart_html += '	<td style="padding:10px 0;" class="product_img"><img src="/pet/images/ex_image.png" width="100%"></td>';
						cart_html += '	<td style="padding:10px 0 10px 10px;">';
						cart_html += '		<p style="font-size:12px;">'+v.txt+'</p>';
						cart_html += '		<p style="margin-top:10px;">'+v.amount+' / '+v.value.format()+'원</p>';
						cart_html += '	</td>';
						cart_html += '</tr>';
					});
					/
					//$("#item_order_detail .item_option_wrap").html("").html(cart_html);
								
					// img_loading
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
								html += '<img src="'+data.data[0].file_path+'" style="width:100%;">';

								$("#item_order_detail .product_img").html("").html(html);
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

				});
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
	*/
});

$(document).on("click", "#item_order_detail .return_please", function(){
	location.href = "<?=$item_directory?>/item_order_return_list.php?no="+no;
});

$(document).on("click", "#item_order_detail .review_btn", function(){
	location.href = "<?=$mainpage_directory?>/item_review_write.php?no="+no+"&backurl="+encodeURIComponent(window.location.pathname+"?no="+no);
});

$(document).on("click", "#item_order_detail .shipping_done", function(){
	$.MessageBox({
		buttonDone  : "확인",
		buttonFail  : "취소",
		message     : "수취 확인 하시겠습니까?"
	}).done(function(data, button){
		$.ajax({
			url: '<?=$mainpage_directory?>/item_list_ajax.php',
			data: {
				mode : "set_update_pay_status",
				pay_status: "5",
				order_num: no
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data); 
					$.MessageBox({
						buttonDone  : "확인",
						message     : "수취확인 되었습니다."
					}).done(function(data, button){
						location.reload();
					});
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
	}).fail(function(data, button){
	});
});

$(document).on("click", "#item_order_detail .cancel_please", function(){
	//location.href = "<?=$item_directory?>/item_order_cancel_list.php?no="+no;
	get_jbook_order(no);
	/*
	$.MessageBox({
		buttonDone  : "확인",
		buttonFail  : "취소",
		message     : "주문을 취소 하시겠습니까?"
	}).done(function(data, button){
		// (추후)취소 이유를 물어보고 취소 확인 버튼 누르면 취소 처리 확인 후 DB update 후 새로고침
		// 지금은 바로 취소 처리 요청 받아서 수기로 취소 하는걸로

		$.ajax({
			url: '<?=$mainpage_directory?>/item_list_ajax.php',
			data: {
				mode : "set_update_pay_status",
				pay_status: "7",
				order_num: no
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data); 
					$.MessageBox({
						buttonDone  : "확인",
						message     : "취소되었습니다."
					}).done(function(data, button){
						location.reload();
					});
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
	}).fail(function(data, button){
	});
	*/
});

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
				if(data.data.orderClaimStatus == "0"){ // 취소여부
					if(data.data.orderStatus == "3"){ // 출고완료
						$.MessageBox("이미 배송 중인 상품이므로 취소문의는 대표전화(1661-9956)로 연락 주시기 바랍니다.");
					}else{
						location.href = "<?=$item_directory ?>/item_order_cancel_list.php?no="+option+"&backurl="+encodeURIComponent(window.location.pathname+"?backurl=<?=$backurl ?>");
					}
				}else{
					if(data.data.orderClaimStatus == "44"){ // 취소완료
						$.MessageBox("이미 상품이 취소완료된 상품이므로 추가문의는 대표전화(1661-9956)로 연락 주시기 바랍니다.");
					}else{
						$.MessageBox("이미 상품이 취소중인 상품이므로 추가문의는 대표전화(1661-9956)로 연락 주시기 바랍니다.");
					}
				}
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

$(document).on("click", "#item_order_detail .quick_search", function(){
	var shipping_invoice = $(this).data("shipping_invoice");
	var shipping_company = $(this).data("shipping_company");

	if(shipping_company == "우체국"){
		//window.open("https://service.epost.go.kr/iservice/usr/trace/usrtrc001k01.jsp", "_BLANK");
		window.open("https://m.epost.go.kr/postal/mobile/mobile.trace.RetrieveDomRigiTraceList.comm?ems_gubun=E&sid1="+shipping_invoice+"&POST_CODE=&mgbn=trace&traceselect=1&target_command=&JspURI=&postNum=6865478949003&deviceVer=&marketVer=&message=", "_BLANK");
	}else if(shipping_company == "대한통운"){
		window.open("https://www.cjlogistics.com/ko/tool/parcel/newTracking?gnbInvcNo="+shipping_invoice, "_BLANK");
	}else if(shipping_company == "한진택배"){
		html = '';
		html += '<form id="send_shipping_data" method="post" action="https://m.hanex.hanjin.co.kr/inquiry/incoming/resultWaybill">';
		html += '	<input type="hidden" id="div" name="div" value="B" />';
		html += '	<input type="hidden" id="show" name="show" value="true" />';
		html += '	<input type="hidden" id="wblNum" name="wblNum" value="'+shipping_invoice+'" />';
		html += '</form>';
		$(document).find("#item_order_detail").after(html);
		$(document).find("#send_shipping_data").submit();
	}
});

	function get_item_payment_log(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				type: 'post',
				url: "<?=$item_directory ?>/item_list_ajax.php",
				data: {
					mode: "get_item_payment_log",
					order_num: no
				},
				dataType: 'json',
				success: function(json) {
					if(json.code == "000000"){
						//console.log(json.data);
						var html = '';
						var idx = 0;
					
						$.each(json.data, function(i, v){
							if(v.pay_status == 1){ // 진행중은 미표시
								$("#item_order_detail").remove();
								$.MessageBox({
									buttonDone: "확인",
									message: "결제 진행 중이오니 결제 완료 후 확인 부탁드립니다."
								}).done(function(){
									location.href = "<?=$mainpage_directory ?>/item_order_list.php";
								});
								return false;
							}
							if(i != 0){ // 하나 이상의 주문은 미표시(주문 오류)
								return false;
							}
							$("#item_order_detail .pay_status").text(pay_status_arr[v.pay_status]);
							$("#item_order_detail .order_num").text("주문번호:"+v.order_num);
							$("#item_order_detail .pay_dt").text(v.pay_dt);
							$("#item_order_detail .shipping_name").text(v.shipping_name);
							$("#item_order_detail .shipping_cellphone").text(v.shipping_cellphone);
							if(v.shipping_zipcode && v.shipping_zipcode != ""){
								$("#item_order_detail .shipping_addr").text("("+v.shipping_zipcode+")"+v.shipping_addr+" "+v.shipping_addr2);
							}else{
								$("#item_order_detail .shipping_addr").text("("+v.shipping_addr.split("|")[0]+")"+v.shipping_addr.split("|")[1]);
							}
							$("#item_order_detail .shipping_memo").text(v.shipping_memo);
							$("#item_order_detail .product_price").text(v.product_price.format()+"원");
							$("#item_order_detail .shipping_price").text(v.shipping_price.format()+"원");
							$("#item_order_detail .point_price").text(v.point_price.format()+"원");
							$("#item_order_detail .total_price").text(v.total_price.format()+"원");

							// 상태별 버튼 노출여부
							if(v.pay_status == 1){
								$("#item_order_detail .order_box .button_wrap").append('<a href="javascript:;" class="cancel_please">취소요청</a><br/>');
							}else if(v.pay_status == 2){
								var html3 = '';
								var bank_name = (v.bank_name && v.bank_name != "")? v.bank_name : v.guest_info.split(',')[0];
								html3 += '<div class="order_box_text" style="">';
								html3 += '	<table width="100%"class="order_table">';
								html3 += '		<colgroup>';
								html3 += '			<col width="30%">';
								html3 += '			<col width="70%">';
								html3 += '		</colgroup>';
								html3 += '		<tbody>';
								html3 += '			<tr>';
								html3 += '				<td style="padding-bottom: 5px;">입금자명</td>';
								html3 += '				<td style="text-align: right;padding-bottom: 5px;" class="bank_name">'+bank_name+'</td>';
								html3 += '			</tr>';
								html3 += '			<tr>';
								html3 += '				<td style="padding-bottom: 5px;">입금계좌</td>';
								html3 += '				<td style="text-align: right;padding-bottom: 5px;" class="bank_account">기업은행<br/>054-143076-01-013<br/>주)펫이지</td>';
								html3 += '			</tr>';
								html3 += '			<tr>';
								html3 += '				<td style="padding-bottom: 5px;">입금마감일시</td>';
								html3 += '				<td style="text-align: right;padding-bottom: 5px;" class="expire_dt">'+v.expire_dt+'</td>';
								html3 += '			</tr>';
								html3 += '		</tbody>';
								html3 += '	</table>';
								html3 += '</div>';
								$("#item_order_detail .order_box .order_detail_wrap").append(html3);
								$("#item_order_detail .order_box .button_wrap").append('<a href="javascript:;" class="cancel_please">취소요청</a><br/>');
							}else if(v.pay_status == 3){
								$("#item_order_detail .order_box .button_wrap").append('<a href="javascript:;" class="cancel_please">취소요청</a><br/>');
							}else if(v.pay_status == 4){
							}else if(v.pay_status == 5){
								var html2 = '';
								if(v.shipping_invoice != ""){
									html2 += '<div class="order_box_text shipping_data" style="">';
									html2 += '	<table width="100%"class="order_table">';
									html2 += '		<colgroup>';
									html2 += '			<col width="30%">';
									html2 += '			<col width="70%">';
									html2 += '		</colgroup>';
									html2 += '		<tbody>';
									html2 += '			<tr>';
									html2 += '				<td style="padding: 5px 0px;">배송업체</td>';
									html2 += '				<td style="text-align: right; padding: 5px 0px;" class="shipping_company">'+v.shipping_company+'</td>';
									html2 += '			</tr>';
									html2 += '			<tr>';
									html2 += '				<td style="padding: 5px 0px;">송장번호</td>';
									html2 += '				<td style="text-align: right; padding: 5px 0px;" class="shipping_invoice">'+v.shipping_invoice+'</td>';
									html2 += '			</tr>';
									html2 += '		</tbody>';
									html2 += '	</table>';
									html2 += '</div>';
									$("#item_order_detail .order_box .order_detail_wrap").append(html2);
									$("#item_order_detail .order_box .button_wrap").append('<a href="javascript:;" class="quick_search" data-shipping_invoice="'+v.shipping_invoice+'" data-shipping_company="'+v.shipping_company+'">배송조회</a><br/>');
									$("#item_order_detail .order_box .button_wrap").append('<a href="javascript:;" class="shipping_done">수취확인</a><br/>');
								}else{
									$("#item_order_detail .order_box .order_detail_wrap .shipping_data").remove();
									html2 += '<div class="order_box_text shipping_data" style="display: none;">';
									html2 += '</div>';
									$("#item_order_detail .order_box .order_detail_wrap").append(html2);
								}
								$("#item_order_detail .order_box .button_wrap").append('<a href="javascript:;" class="return_please">반품요청</a><br/>');
							}else if(v.pay_status == 6){
								$("#item_order_detail .order_box .button_wrap").append('<a href="javascript:;" class="review_btn">리뷰작성</a><br/>');
								$("#item_order_detail .order_box .button_wrap").append('<a href="javascript:;" class="return_please">반품요청</a><br/>');
							}else if(v.pay_status == 7){
								var html1 = '';
								html1 += '<div class="order_box_text">';
								html1 += '	<table width="100%" class="order_table">';
									html1 += '		<colgroup>';
									html1 += '			<col width="30%">';
									html1 += '			<col width="70%">';
									html1 += '		</colgroup>';
									html1 += '		<tbody>';
									if(v.is_cancel == "2"){
										var cancel_result = v.cancel_result.split('|'); // 취소사유 / 기타사유
										var cancel_result_detail = (cancel_result[0] == "etc")? "("+cancel_result[1]+")" : "";
										var cancel_result2 = v.cancel_result2.split('|'); // 상품번호 / 결제타입 / 계좌번호 / 은행 / 환불금액

										html1 += '			<tr>';
										html1 += '				<td style="padding-bottom: 5px;">취소사유</td>';
										html1 += '				<td style="text-align: right;padding-bottom: 5px;">'+reason_type_arr[cancel_result[0]]+cancel_result_detail+'</td>';
										html1 += '			</tr>';
										if(cancel_result2[1] == "1"){ // card
											if(v.pg_log){
												var pg_log = v.pg_log.split('&');
												var pg_log_card = pg_log[26].split('=')[1];
												html1 += '			<tr>';
												html1 += '				<td style="padding-bottom: 5px;">취소내역</td>';
												html1 += '				<td style="text-align: right;padding-bottom: 5px;">카드결제</td>';
												html1 += '			</tr>';
												html1 += '			<tr>';
												html1 += '				<td style="padding-bottom: 5px;">환불은행</td>';
												html1 += '				<td style="text-align: right;padding-bottom: 5px;">'+pg_log_card+'</td>';
												html1 += '			</tr>';
												html1 += '			<tr>';
												html1 += '				<td style="padding-bottom: 5px;">환불금액</td>';
												html1 += '				<td style="text-align: right;padding-bottom: 5px;">'+parseInt(cancel_result2[4]).format()+'원</td>';
												html1 += '			</tr>';
												html1 += '			<tr>';
												html1 += '				<td style="padding-bottom: 5px;">환불일시</td>';
												html1 += '				<td style="text-align: right;padding-bottom: 5px;">'+v.cancel_dt+'</td>';
												html1 += '			</tr>';
											}
										}else{ // bank
											html1 += '			<tr>';
											html1 += '				<td style="padding-bottom: 5px;">취소내역</td>';
											html1 += '				<td style="text-align: right;padding-bottom: 5px;">계좌이체</td>';
											html1 += '			</tr>';
											html1 += '			<tr>';
											html1 += '				<td style="padding-bottom: 5px;">환불은행</td>';
											html1 += '				<td style="text-align: right;padding-bottom: 5px;">'+bank[cancel_result2[3]]+'</td>';
											html1 += '			</tr>';
											html1 += '			<tr>';
											html1 += '				<td style="padding-bottom: 5px;">환불계좌</td>';
											html1 += '				<td style="text-align: right;padding-bottom: 5px;">'+cancel_result2[2]+'</td>';
											html1 += '			</tr>';
											html1 += '			<tr>';
											html1 += '				<td style="padding-bottom: 5px;">환불금액</td>';
											html1 += '				<td style="text-align: right;padding-bottom: 5px;">'+parseInt(cancel_result2[4]).format()+'원</td>';
											html1 += '			</tr>';
											html1 += '			<tr>';
											html1 += '				<td style="padding-bottom: 5px;">환불일시</td>';
											html1 += '				<td style="text-align: right;padding-bottom: 5px;">'+v.cancel_dt+'</td>';
											html1 += '			</tr>';
										}
									}
									if(v.is_return == "2"){
										var return_result = v.return_result.split('|'); // 반품사유 / 기타사유
										var return_result_detail = (return_result[0] == "etc")? "("+return_result[1]+")" : "";
										var return_result2 = v.return_result2.split('|'); // 상품번호 / 결제타입 / 계좌번호 / 은행 / 환불금액

										html1 += '			<tr>';
										html1 += '				<td style="padding-bottom: 5px;">취소사유</td>';
										html1 += '				<td style="text-align: right;padding-bottom: 5px;">'+reason_type_arr[return_result[0]]+return_result_detail+'</td>';
										html1 += '			</tr>';
										if(return_result2[1] == "1"){ // card
											if(v.pg_log){
												var pg_log = v.pg_log.split('&');
												var pg_log_card = pg_log[26].split('=')[1];
												html1 += '			<tr>';
												html1 += '				<td style="padding-bottom: 5px;">반품내역</td>';
												html1 += '				<td style="text-align: right;padding-bottom: 5px;">카드결제</td>';
												html1 += '			</tr>';
												html1 += '			<tr>';
												html1 += '				<td style="padding-bottom: 5px;">환불은행</td>';
												html1 += '				<td style="text-align: right;padding-bottom: 5px;">'+pg_log_card+'</td>';
												html1 += '			</tr>';
												html1 += '			<tr>';
												html1 += '				<td style="padding-bottom: 5px;">환불금액</td>';
												html1 += '				<td style="text-align: right;padding-bottom: 5px;">'+parseInt(return_result2[4]).format()+'원</td>';
												html1 += '			</tr>';
												html1 += '			<tr>';
												html1 += '				<td style="padding-bottom: 5px;">환불일시</td>';
												html1 += '				<td style="text-align: right;padding-bottom: 5px;">'+v.return_dt+'</td>';
												html1 += '			</tr>';
											}
										}else{ // bank
											html1 += '			<tr>';
											html1 += '				<td style="padding-bottom: 5px;">반품내역</td>';
											html1 += '				<td style="text-align: right;padding-bottom: 5px;">계좌이체</td>';
											html1 += '			</tr>';
											html1 += '			<tr>';
											html1 += '				<td style="padding-bottom: 5px;">환불은행</td>';
											html1 += '				<td style="text-align: right;padding-bottom: 5px;">'+bank[return_result2[3]]+'</td>';
											html1 += '			</tr>';
											html1 += '			<tr>';
											html1 += '				<td style="padding-bottom: 5px;">환불계좌</td>';
											html1 += '				<td style="text-align: right;padding-bottom: 5px;">'+return_result2[2]+'</td>';
											html1 += '			</tr>';
											html1 += '			<tr>';
											html1 += '				<td style="padding-bottom: 5px;">환불금액</td>';
											html1 += '				<td style="text-align: right;padding-bottom: 5px;">'+parseInt(return_result2[4]).format()+'원</td>';
											html1 += '			</tr>';
											html1 += '			<tr>';
											html1 += '				<td style="padding-bottom: 5px;">환불일시</td>';
											html1 += '				<td style="text-align: right;padding-bottom: 5px;">'+v.return_dt+'</td>';
											html1 += '			</tr>';
										}
									}
									html += '		</tbody>';
								html += '	</table>';
								html += '</div>';

							}else if(v.pay_status == 8 || v.pay_status == 9){
							}else{
								console.log("@");
							}
							$("#item_order_detail .order_box .order_detail_wrap").append(html1);

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
							
						});

						$("#item_order_detail .order_box .order_detail_wrap .item_option_wrap").append(html);

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
									if(c == pay_data_list.length){
										resolve();
									}
									return get_item(v.ip_log_seq, i2, pay_data, v.product_name, v.pay_status, shipping_invoice, shipping_company);
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

	function chk_val(){
		return new Promise(function(resolve, reject) {
			console.log(chk_list);
			if(chk_list.length > 0){
				$.each(chk_list, function(i, v){
					$.each($("#item_order_cancel input[type='checkbox']"), function(i2, v2){
						var _this = $(this);

						if(_this.val() == v){
							_this.prop("checked", true);
						}
					});
				});
			}
			resolve();
		});
	}

	function get_item(ip_log_seq, target, pay_data, product_name, pay_status, shipping_invoice, shipping_company){
		return new Promise(function(resolve, reject) {
			var cnt = 0;
			var html = '';
			html += '	<div class="item_cart_data">';
			html += '		<div class="item_image" style=" background-image: url(\'/pet/images/product_img.png\');"></div>';
			html += '		<div class="item_detail">';
			$.each(pay_data, function(i, v){
				console.log(v);
				//var item_name = (v.seq && v.seq != "")? product_name+' / '+v.txt : v.txt;
				var item_name = v.txt;
				html += '			<div class="item_name">'+item_name+'</div>';
				html += '			<div class="item_price">'+v.value.format()+'원 / '+v.amount+'개</div>';
			});
			if(shipping_invoice != ""){
				html += '			<div class="shipping_invoice">['+shipping_company+']'+shipping_invoice+'</div>';
			}
			html += '		</div>';
			html += '	</div>';

			$("#item_order_detail .order_box .order_detail_wrap .item_cart_wrap[data-id='"+ip_log_seq+"'][data-item_no='"+target+"']").append(html);

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
									$("#item_order_detail .order_box .order_detail_wrap .item_cart_wrap[data-id='"+ip_log_seq+"'][data-item_no='"+target+"'] .item_cart_data").attr('data-id', v.product_no);
									get_item_image('.item_cart_wrap[data-id="'+ip_log_seq+'"][data-item_no="'+target+'"] .item_cart_data', v.product_no);
								});

								if(v.is_supply == 1 && v.supplier == "정글북"){
									get_jbook_invoice(no);
								}

								cnt++;
								if(cnt == pay_data.length){
									resolve();
								}
							}else{
								// 삭제된 상품이 있음
								//$("#item_order_list .order_list_wrap .item_cart_wrap[data-id='"+ip_log_seq+"'][data-item_no='"+target+"'] .item_cart_data .item_detail").append('<div class="not_found">상품 없음</div>');
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
										$("#item_order_detail .order_box .order_detail_wrap").find(target+"[data-id='"+product_no+"'] .item_image").css("background-image", "url('"+v.file_path+"')");
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
					$("#item_order_detail .order_box .order_detail_wrap").find(target+"[data-id='"+product_no+"'] .item_image").css("background-image", "url('"+goodsRepImage+"')");
				}else{
					$("#item_order_detail .order_box .order_detail_wrap").find(target+"[data-id='"+product_no+"'] .item_image").css("background-image", "url('../images/product_img.png')");
				}
			}
		});
	}

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
					var html = '';
					var _invoiceNo = data.data.invoiceNo;
					_invoiceNo = (_invoiceNo != "")? ((_invoiceNo.indexOf(','))? _invoiceNo.split(',') : [_invoiceNo]) : [];

					html += '<div class="order_box_text jbook_shipping_data" style="">';
					html += '	<table width="100%"class="order_table">';
					html += '		<colgroup>';
					html += '			<col width="30%">';
					html += '			<col width="70%">';
					html += '		</colgroup>';
					html += '		<tbody>';
					html += '			<tr>';
					html += '				<td style="padding: 5px 0px;">배송업체</td>';
					html += '				<td style="text-align: right; padding: 5px 0px;" class="shipping_company">'+data.data.invoiceCompanyNm+'</td>';
					html += '			</tr>';
					html += '			<tr>';
					html += '				<td style="padding: 5px 0px;">송장번호</td>';
					html += '				<td style="text-align: right; padding: 5px 0px;" class="shipping_invoice">';
					$.each(_invoiceNo, function(i, v){
						html += '				<div>';
						html += '					<span>'+v+'</span>';
						if(data.data.invoiceCompanySno == "13"){ // 롯데택배
							html += '					<a class="quick_search2" href="https://www.lotteglogis.com/mobile/reservation/tracking/linkView?InvNo='+v+'">배송조회</a>';
						}
						html += '				</div>';
					});
					html += '				</td>';
					html += '			</tr>';
					html += '		</tbody>';
					html += '	</table>';
					html += '</div>';

					if(data.data.invoiceNo != ""){ // 배송번호가 있을때 추가하도록
						$(document).find("#item_order_detail .order_detail_wrap .jbook_shipping_data").remove();
						$(document).find("#item_order_detail .order_detail_wrap .shipping_data").after(html);
					}
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

//남는 길이만큼 0으로 채움
function fillZero(width, str){
	var str = String(str);//문자열 변환
	return str.length >= width ? str:new Array(width-str.length+1).join('0')+str;
}
</script>