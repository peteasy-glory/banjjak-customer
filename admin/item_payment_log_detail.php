<?php
include "../include/top.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

// init
$r_order_num = ($_GET["no"] && $_GET["no"] != "")? $_GET["no"] : "";
$backurl = $_GET['backurl'];
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

$order_status_arr = array(
	"1" => "주문접수",
	"2" => "입금대기",
	"3" => "결제완료",
	"7" => "결제실패",
	"8" => "입금시간만료",
	"9" => "결제취소"
);

$order_status_color = array(
	"1" => "#cccccc",
	"2" => "#9fcfff",
	"3" => "#000000",
	"4" => "#333333",
	"8" => "#404040",
	"9" => "#404040"
);

$pay_status_arr = array(
	"1" => "상품준비중",
	"2" => "배송준비중",
	"3" => "배송중",
	"4" => "배송완료",
	"8" => "반품",
	"9" => "취소"
);

$pay_status_color = array(
	"1" => "#ff0000",
	"2" => "#ffcc00",
	"3" => "#339900",
	"4" => "#333333",
	"8" => "#404040",
	"9" => "#404040"
);

$pay_status_arr_old = array(
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
	#item_order_list { position: relative; width: 100%; max-width: 500px; height: 100%; font-size: 14px; font-family: 'NL2GR'; font-weight: normal; margin: 0 auto; }
	#item_order_list input[type='text'] { border: 0px; border-bottom: 1px solid #ccc; padding: 0px 10px; height: 30px; border-radius: 0px; font-size: 14px; vertical-align: top; font-family: 'NL2GR'; font-weight: normal; }
	#item_order_list input[type='radio'] { display: none; }
	#item_order_list input[type='radio']+label { position: relative; display: inline-block; height: 30px; line-height: 30px; padding: 0px 10px 0px 35px; border: 1px solid #ccc; background-color: #fff; border-radius: 5px; }
	#item_order_list input[type='radio']+label>span { position: absolute; left: 5px; top: 5px; display: inline-block; width: 20px; height: 20px; border: 1px solid #ccc; border-radius: 100%; }
	#item_order_list input[type='radio']:checked+label { background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
	#item_order_list input[type='radio']:checked+label>span { background-color: #fff; border: 1px solid #f5bf2e; text-align: center; }
	#item_order_list input[type='radio']:checked+label>span:before { content: ''; display: inline-block; width: 10px; height: 10px; background-color: #000; border-radius: 100%; margin: 5px; }
	#item_order_list button { border: 1px solid #ccc; background-color: #eee; border-radius: 5px; padding: 0px 10px; height: 30px; vertical-align: top; font-family: 'NL2GR'; font-weight: normal; }
	#item_order_list select { border: 1px solid #ccc; height: 30px; padding: 0px 10px; font-family: 'NL2GR'; font-weight: normal; }
	#item_order_list .item_order_list_wrap { margin-top: 60px; padding-top: 5px; padding-bottom: 50px; }
	#item_order_list .item_order_list_wrap .t_red { color: #f00; }
	#item_order_list .item_order_list_wrap .t_blue { color: #00f; }
	#item_order_list .item_order_list_wrap .t_bold { font-weight: Bold; }
	#item_order_list .item_order_list_wrap .t_s10 { font-size: 10px; }
	#item_order_list .item_order_list_wrap .t_s12 { font-size: 12px; }
	#item_order_list .item_order_list_wrap .t_s14 { font-size: 14px; }
	#item_order_list .item_order_list_wrap .t_s18 { font-size: 18px; }
	#item_order_list .item_order_list_wrap .title { font-weight: Bold; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap { position: relative; width: calc(100% - 20px); margin: 10px auto; border-radius: 10px; border: 1px solid #ccc; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap>.title { position: relative; background-color: #eee; height: 30px; line-height: 30px; padding: 0px 10px; border-radius: 10px 10px 0px 0px; } 
	#item_order_list .item_order_list_wrap .item_payment_log_wrap>.title .right_menu { position: absolute; right: 0px; top: 0px; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap>.title .right_menu .order_num { padding-right: 10px; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap>.content { position: relative; margin: 10px; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap>.content>ul.test_box,
	#item_order_list .item_order_list_wrap .item_payment_log_wrap>.content>ul.cancel_box,
	#item_order_list .item_order_list_wrap .item_payment_log_wrap>.content>ul.return_box { border-bottom: 1px solid #ccc; margin-bottom: 5px; background-color: #fcc; color: #f00; text-align: center; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap>.content>ul.customer_info { border-bottom: 1px solid #ccc; margin-bottom: 5px; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap>.content>ul.customer_info li:last-child { padding-bottom: 10px; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap>.content>ul>li { position: relative; padding: 5px 0px; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap>.content>ul>li .right_menu { position: absolute; right: 0px; top: 0px; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap>.content>ul>li .right_menu .price { display: inline-block; height: 30px; line-height: 30px; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap>.content .info { margin-top: 10px; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap>.content input[name='shipping_zipcode'] { width: 50px; text-align: center; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap>.content input[name='shipping_addr'],
	#item_order_list .item_order_list_wrap .item_payment_log_wrap>.content input[name='shipping_addr2'],
	#item_order_list .item_order_list_wrap .item_payment_log_wrap>.content input[name='shipping_memo'] { width: calc(100% - 20px); }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap>.content select[name='order_status'] { width: 100%; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap .btn_wrap { background-color: #fff; padding: 10px; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap .btn_wrap button { width: 100%; height: 30px; background-color: #fff; border: 1px solid #f5bf2e; color: #f5bf2e; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap .btn_wrap ul.table { display: table; width: 100%; table-layout:fixed; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap .btn_wrap ul.table>li { display: table-cell; white-space: nowrap; }
	.return_finish { width: 90%; height: 30px; background-color: white !important; border: 1px solid #f5bf2e; color: #f5bf2e; }
	/*
	#item_order_list .item_order_list_wrap .item_payment_log_wrap .btn_wrap>button.item_payment_log_product_pay_status_btn,
	#item_order_list .item_order_list_wrap .item_payment_log_wrap .btn_wrap>button.item_payment_log_product_kakao_btn,
	#item_order_list .item_order_list_wrap .item_payment_log_wrap .btn_wrap>button.item_payment_log_product_shipping_btn { width: calc(50% - 2px); }
	*/
	#item_order_list .item_order_list_wrap .item_payment_log_wrap .btn_wrap>button.set_update_bank_deposit_btn { background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap .btn_wrap>button.set_update_bank_deposit_old_btn { background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }

	/* 결제상품정보 */
	#item_order_list .item_order_list_wrap .item_payment_log_wrap .content .product_data { position: relative; margin-bottom: 5px; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap .content .product_data .item_image { position: absolute; left: 0px; top: 0px; width: 100px; height: 100px; background-color: #eee; background-size: cover; background-position: center; background-repeat: no-repeat; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap .content .product_data ul.item { margin-left: 100px; padding-left: 10px; width: calc(100% - 100px); min-height: 100px; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap .content .product_data ul.item li { padding: 5px 0px; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap .content .product_data ul.item .supply_1 { background-color: #c4e4a5; text-align: center; padding: 3px 0px; width: calc(100% - 10px); }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap .content .product_data ul.item .supply_11 { background-color: #F2AAAA; text-align: center; padding: 3px 0px; width: calc(100% - 10px); }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap .content .product_data ul.item .supply_2 { background-color: #fce985; text-align: center; padding: 3px 0px; width: calc(100% - 10px); }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap .content .product_data ul.item .item_price { font-weight: Bold; text-align: right; padding-right: 10px; margin-top: -10px; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap.jbook_list>.title { background-color: #0A8242; color: #fff; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap .bjOrderData { padding: 10px; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap .bjOrderData>ul>li { position: relative; padding: 2px 0px; height: 30px; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap .bjOrderData>ul>li .title { font-size: 12px; color: #999; }
	#item_order_list .item_order_list_wrap .item_payment_log_wrap .bjOrderData>ul>li>div:last-child { text-align: right; margin-top: -15px; }
	#item_order_list .bank_chk_wrap { display: none; }
	#item_order_list .bank_chk_wrap.on { display: block; }
	#item_order_list .bank_chk_wrap_old { display: none; }
	#item_order_list .bank_chk_wrap_old.on { display: block; }

	#item_order_popup { font-size: 14px; font-family: 'NL2GR'; font-weight: normal; }
	#item_order_popup input[type='text'] { border: 0px; border-bottom: 1px solid #ccc; padding: 0px 10px; height: 30px; border-radius: 0px; font-size: 14px; vertical-align: top; font-family: 'NL2GR'; font-weight: normal; }
	#item_order_popup button { border: 1px solid #ccc; background-color: #eee; border-radius: 5px; padding: 0px 10px; height: 30px; vertical-align: top; font-family: 'NL2GR'; font-weight: normal; }
	#item_order_popup select { border: 1px solid #ccc; height: 30px; padding: 0px 10px; font-family: 'NL2GR'; font-weight: normal; }
	#item_order_popup select[name='pay_status'] { width: 100%; }
	#item_order_popup .title { font-weight: Bold; padding: 2px 0px; }
	#item_order_popup .t_s12 { font-size: 12px; }
	#item_order_popup .info { padding: 10px; }
	#item_order_popup .btn_wrap { background-color: #fff; padding: 10px; }
	#item_order_popup .btn_wrap>button { width: 100%; height: 30px; border: 1px solid #ccc; background-color: #fff; color: #333; }
	#item_order_popup .btn_wrap>button.set_update_item_payment_log_product_pay_status_btn { background-color: #fff; border: 1px solid #f5bf2e; color: #f5bf2e; }
</style>
<div id="item_order_list"></div>
<div id="item_order_popup"></div>

<script>
	var $item_order_list = $("#item_order_list");
	var $item_order_popup = $("#item_order_popup");
	var order_num = "<?=$r_order_num ?>";
	var backurl = "<?=$backurl ?>";
	var banks = $.parseJSON('<?=json_encode($banks)?>');
	var order_status_arr = $.parseJSON('<?=json_encode($order_status_arr)?>');
	var order_status_color = $.parseJSON('<?=json_encode($order_status_color)?>');
	var pay_status_arr = $.parseJSON('<?=json_encode($pay_status_arr)?>');
	var pay_status_color = $.parseJSON('<?=json_encode($pay_status_color)?>');
	var pay_status_arr_old = $.parseJSON('<?=json_encode($pay_status_arr_old)?>');
	var shipping_company_arr = ['한진택배', '대한통운', '우체국', '롯데택배'];
	var jbookOrderStatusList = ['주문접수', '결제완료', '상품준비중', '출고완료'];
	var jbookOrderClaimStatusList = {'0': '', '40': '취소요청', '41': '취소접수','42': '취소진행','44': '취소완료','50': '결제시도','51': 'PG에러','54': '결제실패'};
	var jbookInsStatusList = {'0': '','1': '검수중','2': 'Boxing','3': '검수완료','10': '검수불가','11': '입고대기'};
	var element_wrap = document.getElementById('item_order_popup'); // 우편번호 찾기 찾기 화면을 넣을 element
	var is_test = 0; // 테스트 결제여부(1-테스트, 0-실결제)

	$(function(){
		init_html()
			.then(get_item_payment_log)
			.then(get_item_payment_log_product)
			.then(get_item_payment_log_jbook);
	});

	// 반품 완료 처리
	$item_order_list.on("click", ".return_finish", function(){
		$.MessageBox({
			buttonDone: "확인",
			buttonFail: "취소",
			message: "반품완료처리를 하시겠습니까?"
		}).done(function(){
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "set_update_return_finish",
					order_num: order_num
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						location.reload();
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

	// 상품별 상태변경
	$item_order_list.on("click", ".product_wrap .item_payment_log_product_pay_status_btn", function(){
		let _iplp_seq = $(this).data('iplp_seq');
		let _pay_status = $item_order_list.find('.item_order_list_wrap .item_payment_log_wrap .content>div.product_data[data-id="'+_iplp_seq+'"] ul li.pay_status').data('pay_status');
		console.log(_pay_status);

		$item_order_popup.dialog({
			modal: true,
			title: '상품별 상태변경',
			autoOpen: true,
			maxWidth: "96%",
			minHeight: "200",
			width: "96%",
			height: "200",
			autoSize: true,
			resizable: false,
			draggable: false,
			open: function(event, ui) {
				// to do something...
				get_item_payment_log_product_pay_status(_iplp_seq, _pay_status);
				$('html').css('overflow', 'hidden');
			},
			close: function() {
				// to do something...
				$('html').css('overflow', 'auto');
			}
		});
	});

	$item_order_popup.on("click", ".item_payment_log_product .set_update_item_payment_log_product_pay_status_btn", function(){
		let _iplp_seq = $(this).data('iplp_seq');
		let _product_no = $(this).data('product_no');
		let _first_pay_status = $(this).data('first_pay_status');
		let _pay_status = $item_order_popup.find('select[name="pay_status"] option:selected').val();

		console.log(_iplp_seq, _pay_status);
		set_update_item_payment_log_product_pay_status(_iplp_seq, _pay_status, _product_no, _first_pay_status);
	});


	// 결제상태 변경(기존)
	$item_order_list.on("click", ".set_update_item_payment_log_pay_status_old_btn", function(){
		var pay_status = $item_order_list.find(".order_status_wrap_old select[name='pay_status'] option:selected").val();
		var shipping_invoice = $item_order_list.find(".shipping_wrap_old input[name='shipping_invoice']").val();

		if(pay_status != ""){
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "set_update_pay_status",
					pay_status: pay_status,
					shipping_invoice: shipping_invoice,
					use_talk: "0",
					order_num: order_num
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data); 
						$item_order_list.find(".product_wrap_old .pay_status").text(pay_status_arr_old[pay_status]);
						if(pay_status == "2"){ // 계좌이체 여부
							$item_order_list.find(".bank_chk_wrap_old").addClass("on");
						}else{
							$item_order_list.find(".bank_chk_wrap_old").removeClass("on");
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
	});

	// 배송정보 변경(기존)
	$item_order_list.on("click", ".set_update_item_payment_log_shipping_old_btn", function(){
		var shipping_invoice = $item_order_list.find(".shipping_wrap_old input[name='shipping_invoice']").val();
		var shipping_company = $item_order_list.find(".shipping_wrap_old select[name='shipping_company'] option:selected").val();
		
		if(shipping_invoice && shipping_invoice != "" && shipping_company && shipping_company != "" && order_num && order_num != ""){
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "set_update_shipping_invoice",
					shipping_invoice: shipping_invoice,
					shipping_company: shipping_company,
					order_num: order_num
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data); 
						$.MessageBox("송장번호가 변경되었습니다.");
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

	// 계좌이체 완료 변경(기존)
	$item_order_list.on("click", ".bank_chk_wrap_old .set_update_bank_deposit_old_btn", function(){
		if(order_num && order_num != ""){
			var pay_status = '3';
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "set_update_bank_deposit",
					pay_status : pay_status,
					use_talk : "0",
					order_num : order_num
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data); 
						$item_order_list.find(".product_wrap_old .pay_status").text(pay_status_arr_old[pay_status]);
						$item_order_list.find(".order_status_wrap_old select[name='pay_status']").val(pay_status);
						$item_order_list.find(".bank_chk_wrap_old").removeClass("on");
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

	// 알림톡 발송
	$item_order_list.on("click", ".kakao_wrap .set_update_item_payment_log_kakao_btn", function(){
		let _kakao_type = $item_order_list.find('input[name="kakao"]:checked').val();

		console.log(_kakao_type);

		if(_kakao_type && _kakao_type != '' && typeof _kakao_type != 'undefined'){
			$.MessageBox({
				buttonDone: "확인",
				buttonFail: "취소",
				message: "발송 하시겠습니까?<br/>(입금, 배송[송장,택배사] 상태를 확인하시고 발송해주세요.)"
			}).done(function(){
				// send
				send_kakao(_kakao_type, '');
			});
		}else{
			$.MessageBox("발송할 메시지 종류를 선택해주세요.");
		}
	});

	$item_order_list.on("click", ".product_wrap .item_payment_log_product_shipping_kakao_btn", function(){
		let _kakao_type = $(this).data('kakao_type');
		let _iplp_seq = $(this).data('iplp_seq');

		console.log(_kakao_type);

		if(_kakao_type && _kakao_type != '' && typeof _kakao_type != 'undefined'){
			$.MessageBox({
				buttonDone: "확인",
				buttonFail: "취소",
				message: "발송 하시겠습니까?<br/>(입금, 배송[송장,택배사] 상태를 확인하시고 발송해주세요.)"
			}).done(function(){
				// send
				send_kakao(_kakao_type, _iplp_seq);
			});
		}else{
			$.MessageBox("발송할 메시지 종류를 선택해주세요.");
		}
	});

	function send_kakao(kakao_type, iplp_seq){
		return new Promise(function(resolve, reject) {
			console.log(kakao_type, iplp_seq);
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "send_kakao",
					kakao_type: kakao_type,
					order_num: order_num,
					iplp_seq: iplp_seq
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data); 
						$.MessageBox("알림톡이 발송되었습니다.");
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
	}

	// 상품별 배송정보변경
	$item_order_list.on("click", ".product_wrap .item_payment_log_product_shipping_btn", function(){
		let _iplp_seq = $(this).data('iplp_seq');

		$item_order_popup.dialog({
			modal: true,
			title: '상품별 배송정보변경',
			autoOpen: true,
			maxWidth: "96%",
			minHeight: "250",
			width: "96%",
			height: "250",
			autoSize: true,
			resizable: false,
			draggable: false,
			open: function(event, ui) {
				// to do something...
				get_item_payment_log_product_shipping(_iplp_seq);
				$('html').css('overflow', 'hidden');
			},
			close: function() {
				// to do something...
				$('html').css('overflow', 'auto');
			}
		});
	});

	// 상품별 배송정보 변경
	$item_order_popup.on("click", ".set_update_item_payment_log_shipping_btn", function(){
		let _shipping_invoice = $item_order_popup.find(".shipping_wrap input[name='shipping_invoice']").val();
		let _shipping_company = $item_order_popup.find(".shipping_wrap select[name='shipping_company'] option:selected").val();
		let _iplp_seq = $(this).data('iplp_seq');
		//console.log(_shipping_invoice, _shipping_company, _iplp_seq, order_num);

		if(_shipping_invoice && _shipping_invoice != "" && _shipping_company && _shipping_company != "" && order_num && order_num != "" && _iplp_seq && _iplp_seq != ""){
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "set_update_item_payment_log_product_shipping_invoice",
					shipping_invoice: _shipping_invoice,
					shipping_company: _shipping_company,
					order_num: order_num,
					iplp_seq: _iplp_seq
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data); 
						$.MessageBox({ 
							buttonDone: "확인",
							message:"배송정보가 변경되었습니다."
						}).done(function(){
							$item_order_list.find('.item_order_list_wrap .item_payment_log_wrap .content>div.product_data[data-id="'+_iplp_seq+'"] .shipping_invoice').text(_shipping_invoice);
							$item_order_list.find('.item_order_list_wrap .item_payment_log_wrap .content>div.product_data[data-id="'+_iplp_seq+'"] .shipping_company').text('['+shipping_company_arr[_shipping_company]+']');
							get_item_payment_log()
								.then(get_item_payment_log_product)
								.then(get_item_payment_log_jbook);
							$item_order_popup.dialog('close');
						});
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

	// 결제상태 변경
	$item_order_list.on("click", ".order_status_wrap .set_update_item_payment_log_order_status_btn", function(){
		let _order_status = $item_order_list.find("select[name='order_status'] option:selected").val();
		$.MessageBox({
			buttonDone: "확인",
			buttonFail: "취소",
			message: "결제 상태를 변경하시겠습니까?<br/>(이미 결제나 취소된 내용은 변경하지 않습니다.)"
		}).done(function(){
			set_update_item_payment_log_order_status(_order_status, '0');
		});
	});

	// 입금완료
	$item_order_list.on("click", ".set_update_bank_deposit_btn", function(){
		$.MessageBox({
			buttonDone: "확인",
			buttonFail: "취소",
			message: "결제 상태를 결제완료로 변경하시겠습니까?<br/>(결제 시간이 같이 변경됩니다.)"
		}).done(function(){
			let _order_status = '3'; // 결제완료
			set_update_item_payment_log_order_status(_order_status, '1');
		});
	});


	// 정글북 조회
	$item_order_list.on("click", ".jbook_list .jbOrderBtn", function(){
		get_jbook_order(order_num);
	});

	// 정글북 주문 접수(계좌이체 입금용)
	$item_order_list.on("click", ".jbook_list .jbOrderCallBtn", function(){ // 정글북 주문처리
		$.MessageBox({
			buttonDone: "확인",
			buttonFail: "취소",
			message: "정글북 주문접수 하시겠습니까?"
		}).done(function(){
			get_jbook_order_call(order_num);
		});
	});

	// 주소 검색
	$item_order_list.on("click", ".address_wrap .search_addr_btn", function(){
		$item_order_popup.dialog({
			modal: true,
			title: '우편번호 검색',
			autoOpen: true,
			maxWidth: "96%",
			minHeight: Number($(window).height()) - 40,
			width: "96%",
			height: Number($(window).height()) - 40,
			autoSize: true,
			resizable: false,
			draggable: false,
			open: function(event, ui) {
				// to do something...
				execDaumPostcode($item_order_list);
				$('html').css('overflow', 'hidden');
			},
			close: function() {
				// to do something...
				$('html').css('overflow', 'auto');
			}
		});
	});

	// 배송지 정보 수정
	$item_order_list.on("click", ".address_wrap .set_update_item_payment_log_addr_btn", function(){
		var shipping_name = $item_order_list.find(".address_wrap input[name='shipping_name']").val();
		var shipping_cellphone = $item_order_list.find("input[name='shipping_cellphone']").val();
		var shipping_zipcode = $item_order_list.find("input[name='shipping_zipcode']").val();
		var shipping_addr = $item_order_list.find("input[name='shipping_addr']").val();
		var shipping_addr2 = $item_order_list.find("input[name='shipping_addr2']").val();
		var shipping_memo = $item_order_list.find("input[name='shipping_memo']").val();

		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "set_update_shipping_info",
				shipping_name: shipping_name,
				shipping_cellphone: shipping_cellphone,
				shipping_zipcode: shipping_zipcode,
				shipping_addr: shipping_addr,
				shipping_addr2: shipping_addr2,
				shipping_memo: shipping_memo,
				order_num: order_num
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data); 
					$.MessageBox("배송지 정보가 수정되었습니다.");
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

	function init_html(){
		return new Promise(function(resolve, reject) {
			let html = '';

			html += '<div class="top_menu">';
			html += '	<div class="top_left">';
			if(backurl != ""){
				html += '		<div class="header-back-btn"><a href="'+backurl+'"><img src="../images/btn_back_2.png" width="26px"></a></div>';	
			}else{
				html += '		<div class="header-back-btn"><a href="<?= $admin_directory ?>/"><img src="../images/btn_back_2.png" width="26px"></a></div>';
			}
			html += '	</div>';
			html += '	<div class="top_title">';
			html += '		<p>상품 결제관리 상세</p>';
			html += '	</div>';
			html += '</div>';
			html += '<div class="item_order_list_wrap">';
			html += '</div>';

			$item_order_list.html(html);
			//$item_order_list.find('.top_menu .top_left').load('<?=$admin_directory ?>/admin_back_btn.php');
			resolve();
		});
	}

	function get_item_payment_log(){
		return new Promise(function(resolve, reject) {
			$item_order_list.find('.item_order_list_wrap').html('');
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode: "get_item_payment_log",
					order_num: order_num
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data); 

						let html = '';

						if(data.data && data.data.length > 0){
							$.each(data.data, function(i, v){
								var black_friday = ((v.product_price)*1) + ((v.shipping_price)*1) - ((v.point_price)*1); // 블프 이벤트 여부 != v.total_price 일때 
								let _pay_type = (v.pay_type == "1")? '<span class="t_red t_s12">카드</span>' : '<span class="t_blue t_s12">계좌</span>';
								let _shipping_invoice = (v.shipping_invoice && v.shipping_invoice != "")? v.shipping_invoice : '';

								// 반품일 시 결제완료로 표시
								var order_status_text = order_status_arr[v.order_status];
								if(v.is_return == "2"){
									order_status_text = "결제완료";
								}else if(v.is_return == "3"){
									order_status_text = "결제완료";
								}
							
								// 결제금액
								html += '<div class="item_payment_log_wrap price_wrap">';
								html += '	<div class="title">';
								html += '		<span>'+order_status_text+' / '+_pay_type+'</span>';
								html += '		<div class="right_menu"><span class="order_num t_s12">주문번호: '+v.order_num+'</span></div>';
								html += '	</div>';
								html += '	<div class="content">';
								// test 결제여부(실결제 아님!)
								if(v.receipt_id && v.receipt_id.indexOf('INIpayTest') != -1){
									html += '		<ul class="test_box">';
									html += '			<li>';
									html += '				<div>';
									html += '					테스트 주문건!! (정글북 상품일 경우 따로 취소요청 필요!!) ';
									html += '				</div>';
									html += '			</li>';
									html += '		</ul>';
									is_test = 0; // 테스트 flag
								}

								// 고객 취소 여부
								if(v.is_cancel == '2'){
									var cancel_reason = {
										'1' : '단순변심',
										'2' : '상품불량',
										'3' : '제품변경',
										'etc' : '기타'
									};
									
									html += '		<ul class="cancel_box">';
									html += '			<li>';
									html += '				<div>';
									html += '					고객 취소사유 : '+cancel_reason[v.cancel_result.split('|')[0]]+' ('+v.cancel_dt+')';
									html += (v.cancel_result.split('|')[0] == 'etc')? '<span>'+v.cancel_result.split('|')[1]+'</span>' : '';
									if(v.cancel_result2.split('|')[1] == "2"){
										html += '					<br/>(환불계좌 : '+banks[fillZero(3, v.cancel_result2.split('|')[3])]+' '+v.cancel_result2.split('|')[2]+' '+v.cancel_result2.split('|')[4].format()+'원)';
									}
									html += '				</div>';
									html += '			</li>';
									html += '		</ul>';
								}

								// 고객 반품 여부
								if(v.is_return == '2'){
									var return_reason = {
										'1' : '단순변심',
										'2' : '상품불량',
										'3' : '제품변경',
										'etc' : '기타'
									};
									
									html += '		<ul class="return_box">';
									html += '			<li>';
									html += '				<div>';
									html += '					고객 반품사유 : '+return_reason[v.return_result.split('|')[0]]+' ('+v.return_dt+')';
									html += (v.return_result.split('|')[1] != '')? '</br><span>'+v.return_result.split('|')[1]+'</span>' : '';
									if(v.return_result2.split('|')[1] == "2"){
										console.log("반품상태값"+v.return_result.split('|')[0]);
										// 제품변경일때(고객계좌가 없으므로)
										if(v.return_result.split('|')[0] == '3'){
											html += '						<br/><button type="button" class="return_finish">반품완료</button>';
										}else{
											html += '						<br/>(환불계좌 : '+banks[fillZero(3, v.return_result2.split('|')[3])]+' '+v.return_result2.split('|')[2]+' '+v.return_result2.split('|')[4].format()+'원)';
											html += '						<br/><button type="button" class="return_finish">반품완료</button>';
										}
									}
									html += '				</div>';
									html += '			</li>';
									html += '		</ul>';
								}

								// 반품 완료 시
								if(v.is_return == '3'){
									var return_reason = {
										'1' : '단순변심',
										'2' : '상품불량',
										'3' : '제품변경',
										'etc' : '기타'
									};
									
									html += '		<ul class="return_box">';
									html += '			<li>';
									html += '				<div>';
									html += '					고객 반품사유 : '+return_reason[v.return_result.split('|')[0]]+' ('+v.return_dt+')';
									html += (v.return_result.split('|')[0] == 'etc')? '<span>'+v.return_result.split('|')[1]+'</span>' : '';
									if(v.return_result2.split('|')[1] == "2"){
										console.log("반품상태값"+v.return_result.split('|')[0]);
										// 제품변경일때(고객계좌가 없으므로)
										if(v.return_result.split('|')[0] == '3'){
											html += '						<br/><span style="font-size:16px; color:blue;">반품완료 ('+v.update_dt+')</span>';
										}else{
											html += '						<br/>(환불계좌 : '+banks[fillZero(3, v.return_result2.split('|')[3])]+' '+v.return_result2.split('|')[2]+' '+v.return_result2.split('|')[4].format()+'원)';
											html += '						<br/><span style="font-size:16px; color:blue;">반품완료 ('+v.update_dt+')</span>';
										}
									}
									html += '				</div>';
									html += '			</li>';
									html += '		</ul>';
								}

								html += '		<ul class="customer_info">';
								html += '			<li>';
								html += '				<div class="title">구매자명</div>';
								html += '				<div class="right_menu">';
								if(v.customer_id != ''){ // 비회원 주문 표시하기
								}else{
									html += '<span style="color:#2E2EFE; font-size:12px;">*비회원 </span>';									
								}
								html += '					<span class="price">'+v.guest_info.split(',')[0]+' ( '+phoneFomatter(v.cellphone)+' )</span>';
								html += '				</div>';
								html += '			</li>';
								// 계좌이체일시 입금자명 노출
								if(v.pay_type != "1"){
									html += '			<li>';
									html += '				<div class="title">입금자명</div>';
									html += '				<div class="right_menu"><span class="price">'+v.bank_name+'</span></div>';
									html += '			</li>';	
								}
								html += '			<li>';
								html += '				<div class="title">결제일시</div>';
								html += '				<div class="right_menu"><span class="price">'+v.pay_dt+'</span></div>';
								html += '			</li>';
								html += '		</ul>';
								html += '		<ul>';
								html += '			<li>';
								html += '				<div class="title">상품가격</div>';
								html += '				<div class="right_menu"><span class="price">'+v.product_price.format()+'원</span></div>';
								html += '			</li>';
								html += '			<li>';
								html += '				<div class="title">배송비</div>';
								html += '				<div class="right_menu"><span class="price">'+v.shipping_price.format()+'원</span></div>';
								html += '			</li>';
								html += '			<li>';
								html += '				<div class="title">포인트사용</div>';
								html += '				<div class="right_menu"><span class="price">'+v.point_price.format()+'원</span></div>';
								html += '			</li>';
//								if(black_friday*0.95 == v.total_price){
//									html += '			<li>';
//									html += '				<div class="title"><span style="background-color:yellow;">블랙프라이데이 5% 할인</span></div>';
//									html += '				<div class="right_menu"><span class="price">'+(black_friday - v.total_price).format()+'원</span></div>';
//									html += '			</li>';
//								}
								html += '			<li>';
								html += '				<div class="title t_bold t_s18">총결제금액</div>';
								html += '				<div class="right_menu"><span class="price t_bold t_s18">'+v.total_price.format()+'원</span></div>';
								html += '			</li>';
								html += '		</ul>';
								html += '	</div>';
								html += '</div>';

								// 계좌이체 변경(기존)
								let is_bank_on = (v.order_status == "2" || v.pay_type == "2")? "on" : "";
								html += '<div class="item_payment_log_wrap bank_chk_wrap '+is_bank_on+'">';
								html += '	<div class="title">계좌이체 변경</div>';
								html += '	<div class="content">';
								html += '		<div class="btn_wrap">';
								html += '			<button type="button" class="set_update_bank_deposit_btn">입금완료</button>';
								html += '		</div>';
								html += '	</div>';
								html += '</div>';

								// 결제상태 변경
								html += '<div class="item_payment_log_wrap order_status_wrap">';
								html += '	<div class="title">결제상태 변경</div>';
								html += '	<div class="content">';
								if(is_test == "1"){
									html += '		<div style="background-color: #fcc; color: #f00; text-align: center; padding: 10px;">테스트 거래 상태변경 불가</div>';
								}else{
									html += '		<select name="order_status">';
									$.each(order_status_arr, function(i2, v2){
										let is_selected = (i2 == v.order_status)? 'selected' : '';
										html += '			<option value="'+i2+'" '+is_selected+'>'+v2+'</option>';
									});
									html += '		</select>';
									html += '		<div class="info t_s12">* 결제상태를 변경하면 입력된 데이터(결제 또는 반품, 취소상태)는 그대로고 현재 상태값만 변경됩니다.</div>';
									html += '		<div class="btn_wrap">';
									html += '			<button type="button" class="set_update_item_payment_log_order_status_btn">변경</button>';
									html += '		</div>';
								}
								html += '	</div>';
								html += '</div>';

								// 알림톡 발송
								html += '<div class="item_payment_log_wrap kakao_wrap">';
								html += '	<div class="title">알림톡 발송</div>';
								html += '	<div class="content">';
								if(is_test == "1"){
									html += '		<div style="background-color: #fcc; color: #f00; text-align: center; padding: 10px;">테스트 거래 알림톡 발송 불가</div>';
								}else{
								html += '		<ul>';
								html += '			<li>';
								html += '				<span>';
								html += '					<input type="radio" id="kakao_1" name="kakao" value="1" />';
								html += '					<label for="kakao_1"><span></span>주문확인</label>';
								html += '				</span>';
								html += '				<span>';
								html += '					<input type="radio" id="kakao_2" name="kakao" value="2" />';
								html += '					<label for="kakao_2"><span></span>입금요청</label>';
								html += '				</span>';
								html += '				<span>';
								html += '					<input type="radio" id="kakao_3" name="kakao" value="3" />';
								html += '					<label for="kakao_3"><span></span>입금확인</label>';
								html += '				</span>';
								//html += '				<span>';
								//html += '					<input type="radio" id="kakao_4" name="kakao" value="4" />';
								//html += '					<label for="kakao_4"><span></span>배송출발</label>';
								//html += '				</span>';
								html += '			</li>';
								html += '		</ul>';
								html += '		<div class="btn_wrap">';
								html += '			<button type="button" class="set_update_item_payment_log_kakao_btn">알림톡 발송</button>';
								html += '		</div>';
								}
								html += '	</div>';
								html += '</div>';

								// 배송지 정보
								html += '<div class="item_payment_log_wrap address_wrap">';
								html += '	<div class="title">배송지 정보</div>';
								html += '	<div class="content">';
								html += '		<ul>';
								html += '			<li>';
								html += '				<div class="title t_s12">수령자명</div>';
								html += '				<input type="text" name="shipping_name" value="'+v.shipping_name+'" />';
								html += '			</li>';
								html += '			<li>';
								html += '				<div class="title t_s12">배송연락처</div>';
								html += '				<input type="text" name="shipping_cellphone" value="'+v.shipping_cellphone+'" />';
								html += '			</li>';
								html += '			<li>';
								html += '				<div class="title t_s12">배송주소</div>';
								html += '				<input type="text" name="shipping_zipcode" value="'+v.shipping_zipcode+'" readonly />';
								html += '				<button type="button" class="search_addr_btn">주소검색</button>';
								html += '				<input type="text" name="shipping_addr" value="'+v.shipping_addr+'" readonly />';
								html += '				<input type="text" name="shipping_addr2" value="'+v.shipping_addr2+'" />';
								html += '			</li>';
								html += '			<li>';
								html += '				<div class="title t_s12">배송요청사항</div>';
								html += '				<input type="text" name="shipping_memo" value="'+v.shipping_memo+'" />';
								html += '			</li>';
								html += '		</ul>';
								html += '		<div class="btn_wrap">';
								html += '			<button type="button" class="set_update_item_payment_log_addr_btn">배송지 정보 수정</button>';
								html += '		</div>';
								html += '	</div>';
								html += '</div>';

							});
							$item_order_list.find('.item_order_list_wrap').append(html);
						}else{
							// 데이터가 없음
							$.MessageBox({
								buttonDone: "확인",
								message: "존재하지 않는 상품번호입니다. 리스트로 돌아갑니다."
							}).done(function(){
								location.href = "item_payment_log.php";
							});
						}
						resolve(data.data);
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
	}

	function get_item_payment_log_product(post_data){
		return new Promise(function(resolve, reject) {
			$item_order_list.find('.item_order_list_wrap .product_wrap').remove();
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode: "get_item_payment_log_product",
					order_num: order_num
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data); 
						let html = '';

						if(data.data && data.data.length > 0){
							html += '<div class="item_payment_log_wrap product_wrap">';
							html += '	<div class="title">결제상품 정보</div>';
							html += '	<div class="content">';
							$.each(data.data, function(i, v){
								let _option_data = $.parseJSON(v.option_data.replace(/\\/g, ''));
								let _is_supply = (v.is_supply == "1")? "업체배송" : "직배송";
//								let _is_consignment = (v.ip_seq == "2" || v.ip_seq == "3" || v.ip_seq == "8" || v.ip_seq == "9" || v.ip_seq == "10" || v.ip_seq == "11" || v.ip_seq == "12" || v.ip_seq == "13" || v.ip_seq == "14")? "1" : "";
								let _is_consignment = (v.ip_seq != '' && v.ip_seq > 0)? "1" : "";
								let _pay_status = (post_data[0].order_status == "2")? order_status_arr[post_data[0].order_status] : pay_status_arr[v.pay_status];
								let _pay_status_color = (post_data[0].order_status == "2")? order_status_color[post_data[0].order_status] : pay_status_color[v.pay_status];
								// 결제상품 정보
								html += '		<div class="product_data" data-id="'+v.iplp_seq+'">';
								html += '			<div class="item_image" style="background-image: url(\'/pet/images/product_img.png\'); "></div>';
								html += '			<ul class="item">';
								html += '				<li class="is_supply supply_'+v.is_supply+_is_consignment+'">'+_is_supply+'</li>';
								html += '				<li class="pay_status t_s18" data-pay_status="'+v.pay_status+'" style="border-left: 5px solid '+_pay_status_color+'; padding-left: 5px;">'+_pay_status+'</li>';
								$.each(_option_data, function(i2, v2){
									html += '				<li class="item_name">'+v2.txt+'</li>';
									html += '				<li class="item_price"><span class="sale_price">'+v2.amount+'개 / '+v2.value.format()+'원</span></li>';
								});
								if(v.pay_status == '3' && v.shipping_invoice && v.shipping_invoice != ""){
									html += '				<li class="shipping"><span class="shipping_company">['+shipping_company_arr[v.shipping_company]+']</span> <span class="shipping_invoice">'+v.shipping_invoice+'</span></li>';
								}
								html += '			</ul>';
								html += '			<div class="btn_wrap">';
								if(is_test == "1"){
									html += '				<div style="background-color: #fcc; color: #f00; text-align: center; padding: 10px;">테스트 거래 상태변경 불가</div>';
								}else{
									html += '				<ul class="table">';
									html += '					<li>';
									html += '						<button type="button" class="item_payment_log_product_pay_status_btn" data-iplp_seq="'+v.iplp_seq+'">상태변경</button>';
									html += '					</li>';
									html += '					<li>';
									html += '						<button type="button" class="item_payment_log_product_shipping_btn" data-iplp_seq="'+v.iplp_seq+'">배송정보변경</button>';
									html += '					</li>';
									if(v.pay_status == '3' && v.shipping_invoice && v.shipping_invoice != "" && v.shipping_company && v.shipping_company != ""){
										html += '					<li>';
										html += '						<button type="button" class="item_payment_log_product_shipping_kakao_btn" data-iplp_seq="'+v.iplp_seq+'" data-kakao_type="4">알림톡(배송출발)</button>';
										html += '					</li>';
									}
									html += '				</ul>';
								}
								html += '			</div>';
								html += '		</div>';
							});
							html += '	</div>';
							html += '</div>';
							$item_order_list.find('.item_order_list_wrap .price_wrap').after(html);

							$.each(data.data, function(i, v){
								let _option_data = $.parseJSON(v.option_data.replace(/\\/g, ''));
								$.each(_option_data, function(i2, v2){
									get_item_option('.item_order_list_wrap .item_payment_log_wrap .content>div.product_data[data-id="'+v.iplp_seq+'"]', v2);
								});
							});
						}else{
							// 결제상품 정보가 없다?
							/*
							$.MessageBox({
								buttonDone: "확인",
								message: "결제내역 상품이 없습니다. 관리자에게 문의하세요."
							}).done(function(){
								// to do something..
							});
							*/
							console.log(post_data);

							if(post_data && post_data.length > 0){
								html += '<div class="item_payment_log_wrap product_wrap_old">';
								html += '	<div class="title">결제상품 정보</div>';
								html += '	<div class="content">';
								$.each(post_data, function(i, v){
									let _pay_data_list = (v.pay_data && v.pay_data != "")? ((v.pay_data.indexOf('||') != -1)? v.pay_data.split('||') : [v.pay_data]) : [];
									console.log(_pay_data_list);
									$.each(_pay_data_list, function(i2, v2){
										let _pay_data = (v2 && v2 != "")? $.parseJSON(v2.replace(/\\/g, '')) : [];
										console.log(_pay_data);
										html += '		<div class="product_data" data-id="'+i2+'">';
										html += '			<div class="item_image" style="background-image: url(\'/pet/images/product_img.png\'); "></div>';
										html += '			<ul class="item">';
										$.each(_pay_data, function(i3, v3){
											let _is_supply = (v3.is_supply == "1")? "업체배송" : "직배송";
											html += '				<li class="is_supply supply_'+v3.is_supply+'">'+_is_supply+'</li>';
											html += '				<li class="pay_status t_s18">'+pay_status_arr_old[v.pay_status]+'</li>';
											html += '				<li class="item_name">'+v3.txt+'</li>';
											html += '				<li class="item_price"><span class="sale_price">'+v3.amount+'개 /'+v3.value.format()+'원</span></li>';
										});
										html += '			</ul>';
										html += '			<div class="btn_wrap">';
										html += '				<div style="background-color: #fcc; color: #f00; text-align: center; padding: 10px;">개별상품 상태변경 불가</div>';
										html += '			</div>';
										html += '		</div>';
									});
								});
								html += '	</div>';
								html += '</div>';
								$item_order_list.find('.item_order_list_wrap .price_wrap').after(html);

								$.each(post_data, function(i, v){
									let _pay_data_list = (v.pay_data && v.pay_data != "")? ((v.pay_data.indexOf('||') != -1)? v.pay_data.split('||') : [v.pay_data]) : [];
									$.each(_pay_data_list, function(i2, v2){
										let _pay_data = (v2 && v2 != "")? $.parseJSON(v2.replace(/\\/g, '')) : [];
										$.each(_pay_data, function(i3, v3){
											get_item_option('.item_order_list_wrap .item_payment_log_wrap .content>div.product_data[data-id="'+i2+'"]', v3);
										});
									});

									// 결제상태 변경(기존)
									html = '';
									html += '<div class="item_payment_log_wrap order_status_wrap_old">';
									html += '	<div class="title">결제상태 변경</div>';
									html += '	<div class="content">';
									if(is_test == "1"){
										html += '		<div style="background-color: #fcc; color: #f00; text-align: center; padding: 10px;">테스트 거래 상태변경 불가</div>';
									}else{
										html += '		<select name="pay_status">';
										$.each(pay_status_arr_old, function(i2, v2){
											let is_selected = (i2 == v.pay_status)? 'selected' : '';
											html += '			<option value="'+i2+'" '+is_selected+'>'+v2+'</option>';
										});
										html += '		</select>';
										html += '		<div class="info t_s12">* 결제상태를 변경하면 입력된 데이터(결제 또는 반품, 취소상태)도 변경되니 변경시 주의하세요.</div>';
										html += '		<div class="btn_wrap">';
										html += '			<button type="button" class="set_update_item_payment_log_pay_status_old_btn">변경</button>';
										html += '		</div>';
									}
									html += '	</div>';
									html += '</div>';

									// 배송정보 변경(기존)
									let _shipping_invoice = (v.shipping_invoice && v.shipping_invoice != "")? v.shipping_invoice : '';

									html += '<div class="item_payment_log_wrap shipping_wrap_old">';
									html += '	<div class="title">배송정보 변경(기존)</div>';
									html += '	<div class="content">';
									html += '		<input type="text" name="shipping_invoice" value="'+_shipping_invoice+'" placeholder="송장번호 입력" />';
									html += '		<select name="shipping_company">';
									$.each(shipping_company_arr, function(i2, v2){
										let is_selected = (i == v.shipping_company)? 'selected' : '';
										html += '			<option value="'+i2+'" '+is_selected+'>'+v2+'</option>';
									});
									html += '		</select>';
									html += '		<div class="info t_s12">';
									html += '			* 배송알림 보내는 방법<br/>';
									html += '			1. 송장번호 입력<br/>';
									html += '			2. 배송업체 변경<br/>';
									html += '			3. "알림톡(배송출발)" 버튼을 눌러 발송<br/>';
									html += '		</div>';
									html += '		<div class="btn_wrap">';
									html += '			<button type="button" class="set_update_item_payment_log_shipping_old_btn">배송정보 변경</button>';
									html += '		</div>';
									html += '	</div>';
									html += '</div>';

									// 계좌이체 변경(기존)
									let is_bank_on = (v.pay_status == "2" && v.pay_type == "2")? "on" : "";
									html += '<div class="item_payment_log_wrap bank_chk_wrap_old '+is_bank_on+'">';
									html += '	<div class="title">계좌이체 변경(기존)</div>';
									html += '	<div class="content">';
									html += '		<div class="btn_wrap">';
									html += '			<button type="button" class="set_update_bank_deposit_old_btn">입금완료</button>';
									html += '		</div>';
									html += '	</div>';
									html += '</div>';
									
									$item_order_list.find('.bank_chk_wrap').removeClass('on'); // 기존내역이 있다면 숨김처리
								});

								$item_order_list.find('.item_order_list_wrap').append(html);
							}
						}
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
		});
	}

	function get_item_option(target, pay_data){
		return new Promise(function(resolve, reject) {
			console.log("!get_item_option", pay_data);
			var post_data = {};
			var html = '';

			if(pay_data.seq && pay_data.seq != ""){
				post_data.mode = "get_item_option";
				post_data.io_seq = pay_data.seq;
			}else{
				post_data.mode = "get_item";
				post_data.il_seq = pay_data.il_seq;
			}

			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: post_data,
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);

						if(data.data && data.data.length > 0){
							var p = $.when();
							var c = 0;

							$.each(data.data, function(i, v){
								p.then(function(){
									c++;
									return get_item_image(target, v.product_no);
								}).done(function(){
									if(c == data.data.length){
										resolve();
									}
								});
							});
						}

					}else{
						alert(data.data+"("+data.code+")");
						console.log(data.data);
					}
				},
				error: function(xhr, status, error) {
					alert(error + "네트워크에러");
				}
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
							var p = $.when();
							var c = 0;

							$.each(data.data.list, function(i, v){
								$item_order_list.find(target).attr("data-no", product_no);
								p.then(function(){
									c++;
									return get_file_list(target, v.product_no, v.product_img, v.goodsRepImage);
								}).done(function(){
									if(c == data.data.length){
										resolve();
									}
								});
							});
						}else{
							alert(data.data+"("+data.code+")");
							console.log(data.data);
						}
					},
					error: function(xhr, status, error) {
						alert(error + "네트워크에러");
					}
				});
			}
		});
	}

	function get_file_list(target, product_no, img_list, goodsRepImage){
		return new Promise(function(resolve, reject) {
			console.log(target, product_no, img_list, goodsRepImage);
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
										$item_order_list.find(target+"[data-no='"+product_no+"'] .item_image").css("background-image", "url('"+v.file_path+"')");
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
						alert(error + "네트워크에러");
					}
				});
			}else{
				if(goodsRepImage != ""){
					$item_order_list.find(target+"[data-no='"+product_no+"'] .item_image").css("background-image", "url('"+goodsRepImage+"')");
				}else{
					$item_order_list.find(target+"[data-no='"+product_no+"'] .item_image").css("background-image", "url('../images/product_img.png')");
				}
			}
		});
	}

	function get_item_payment_log_jbook(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_item_payment_log_jbook",
					order_num: order_num
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data); 
						
						if(data.data && data.data.length > 0){
							var html = '';
							var order_step_list = {'1': '접수진행','2': '접수완료','9': '거래번호상이(정글북문의필요)'};
							$.each(data.data, function(i, v){
								if(i == 0){
									var _jbOrdNo = (v.ordNo && v.ordNo != "")? v.ordNo : "";
									html += '<div class="item_payment_log_wrap jbook_list">';
									html += '	<div class="title">정글북 상세내역</div>';
									html += '	<div class="bjOrderData">';
									html += '		<ul>';
									html += '			<li><div class="title">거래번호</div><div>'+_jbOrdNo+'</div></li>';
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
									if(v.order_step == "1"){
										html += '		<button type="button" class="jbOrderCallBtn">주문발송</button>';
									}else if(v.order_step == "2"){
										html += '		<button type="button" class="jbOrderBtn">주문조회</button>';
										//html += '		<button type="button" class="jbInvoiceBtn">송장번호확인</button>';
									}
									html += '	</div>';
									html += '</div>';
								}
							});

							$item_order_list.find(".item_order_list_wrap .order_status_wrap").after(html);
						}

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
					$item_order_list.find(".jbook_list .orderStatus").text(jbookOrderStatusList[data.data.orderStatus]);
					$item_order_list.find(".jbook_list .orderClaimStatus").text(jbookOrderClaimStatusList[data.data.orderClaimStatus]);
					$item_order_list.find(".jbook_list .insStatus").text(jbookInsStatusList[data.data.insStatus]);
					$item_order_list.find(".jbook_list .invoiceCompanyNm").text(data.data.invoiceCompanyNm);
					$item_order_list.find(".jbook_list .invoiceNo").text(data.data.invoiceNo);
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

	function get_jbook_order_call(option){ // 정글북 주문 접수
		console.log(option);
		$.ajax({
			url: '<?=$admin_directory?>/jbook_item_ajax.php',
			data: {
				mode : "set_update_item_order_call",
				order_num : option
			},
			type: 'POST',
			dataType: 'JSON',
			async: false,
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					$.MessageBox("주문 접수 되었습니다.");
					$item_order_list.find(".jbook_list").remove();
					get_item_payment_log_jbook();
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

	function set_update_item_payment_log_order_status(order_status, is_confirm){ // 입금대기 > 결제완료시 pay_dt 추가
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "set_update_item_payment_log_order_status",
					order_num : order_num,
					order_status : order_status,
					is_confirm : is_confirm
				},
				type: 'POST',
				dataType: 'JSON',
				async: false,
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						$.MessageBox("결제 상태가 변경되었습니다.");
						init_html()
							.then(get_item_payment_log)
							.then(get_item_payment_log_product)
							.then(get_item_payment_log_jbook);
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
	}
	
	function set_update_item_payment_log_product_pay_status(iplp_seq, pay_status, product_no, first_pay_status){
		return new Promise(function(resolve, reject) {
			if(iplp_seq != ""){
				$.ajax({
					url: '<?=$item_directory ?>/item_list_ajax.php',
					data: {
						mode : "set_update_item_payment_log_product_pay_status",
						iplp_seq: iplp_seq,
						pay_status: pay_status
					},
					type: 'POST',
					dataType: 'JSON',
					success: function(data) {
						if(data.code == "000000"){
							console.log(data.data);
							if(pay_status == "4" && first_pay_status != "4"){
								$.ajax({
									url: '<?=$item_directory ?>/item_list_ajax.php',
									data: {
										mode : "increase_sales_cnt",
										product_no: product_no
									},
									type: 'POST',
									dataType: 'JSON',	
									success: function(data) {
									},
									error: function(xhr, status, error) {
										//alert(error + "네트워크에러");
										if(xhr.status != 0){
											alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
										}
									}
								});
							}else if((pay_status == "8" || pay_status == "9") && first_pay_status == "4"){
								$.ajax({
									url: '<?=$item_directory ?>/item_list_ajax.php',
									data: {
										mode : "decrease_sales_cnt",
										product_no: product_no
									},
									type: 'POST',
									dataType: 'JSON',	
									success: function(data) {
									},
									error: function(xhr, status, error) {
										//alert(error + "네트워크에러");
										if(xhr.status != 0){
											alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
										}
									}
								});
							}
							$.MessageBox({
								buttonDone: "확인",
								message: "상품의 상태가 변경되었습니다."
							}).done(function(){
								$item_order_list.find('.item_order_list_wrap .item_payment_log_wrap .content>div.product_data[data-id="'+iplp_seq+'"] ul li.pay_status').attr('data-pay_status', pay_status);
								$item_order_list.find('.item_order_list_wrap .item_payment_log_wrap .content>div.product_data[data-id="'+iplp_seq+'"] ul li.pay_status').text(pay_status_arr[pay_status]);
								get_item_payment_log()
									.then(get_item_payment_log_product)
									.then(get_item_payment_log_jbook);
								$item_order_popup.dialog('close');
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
			}else{
				$.MessageBox("잘못된 접근입니다.");
			}
		});
	}

	function get_item_payment_log_product_shipping(iplp_seq){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_item_payment_log_product",
					iplp_seq: iplp_seq
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data); 
						let html = '';

						if(data.data && data.data.length > 0){
							$.each(data.data, function(i, v){
								var _shipping_invoice = (v.shipping_invoice && v.shipping_invoice != "")? v.shipping_invoice : "";
								html += '<div class="item_payment_log_wrap shipping_wrap">';
								html += '	<div class="title">배송정보 변경</div>';
								html += '	<div class="content">';
								html += '		<input type="text" name="shipping_invoice" value="'+_shipping_invoice+'" placeholder="송장번호 입력" />';
								html += '		<select name="shipping_company">';
								$.each(shipping_company_arr, function(i2, v2){
									let is_selected = (i2 == v.shipping_company)? 'selected' : '';
									html += '			<option value="'+i2+'" '+is_selected+'>'+v2+'</option>';
								});
								html += '		</select>';
								html += '		<div class="info t_s12">';
								html += '			* 배송알림 보내는 방법<br/>';
								html += '			1. 송장번호 입력<br/>';
								html += '			2. 배송업체 변경<br/>';
								html += '			3. "알림톡(배송출발)" 버튼을 눌러 발송<br/>';
								html += '		</div>';
								html += '		<div class="btn_wrap">';
								html += '			<button type="button" class="set_update_item_payment_log_shipping_btn" data-iplp_seq="'+iplp_seq+'">배송정보 변경</button>';
								html += '		</div>';
								html += '	</div>';
								html += '</div>';
							});
							$item_order_popup.html(html);
						}else{
							// 결제 상품이 없으면 안되는데?!
						}

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
		});
	}

	function get_item_payment_log_product_pay_status(iplp_seq, pay_status){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_item_payment_log_product",
					iplp_seq: iplp_seq
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data); 
						let html = '';

						if(data.data && data.data.length > 0){
							$.each(data.data, function(i, v){
								html += '<div class="item_payment_log_product">';
								html += '	<div class="title">상품 상태</div>';
								html += '	<div class="content">';
								html += '		<ul>';
								html += '			<li>';
								html += '				<div class="title"></div>';
								html += '				<select name="pay_status">';
								$.each(pay_status_arr, function(i2, v2){
									is_selected = (i2 == pay_status)? 'selected' : '';
									html += '					<option value="'+i2+'" '+is_selected+'>'+v2+'</option>';
								});
								html += '				</select>';
								html += '			</li>';
								html += '			<li>';
								html += '				<div class="info t_s12">* 상품상태를 변경하면 입력된 데이터(배송 또는 반품, 취소상태)가 변경되니 변경시 유의해주세요.';
								html += '				</div>';
								html += '			</li>';
								html += '		</ul>';
								html += '		<div class="btn_wrap">';
								html += '			<button type="button" class="set_update_item_payment_log_product_pay_status_btn" data-iplp_seq="'+iplp_seq+'" data-product_no="'+v.product_no+'" data-first_pay_status="'+v.pay_status+'">변경</button>';
								html += '		</div>';
								html += '	</div>';
								html += '</div>';
							});
							$item_order_popup.html(html);
						}else{
							// 결제 상품이 없으면 안되는데?!
						}

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
		});
	}

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
				target.find("input[name='shipping_zipcode']").val(data.zonecode);
				target.find("input[name='shipping_addr']").val(data.roadAddress+" "+extraAddr+" ");
				target.find("input[name='shipping_addr2']").focus();

				// iframe을 넣은 element를 안보이게 한다.
				// (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
				element_wrap.style.display = 'none';
				$item_order_popup.dialog('close');

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

	// 전화번호 포멧 변경
	function phoneFomatter(num,type){
		var formatNum = '';
		if(num.length==11){
			if(type==0){
				formatNum = num.replace(/(\d{3})(\d{4})(\d{4})/, '$1-****-$3');
			}else{
				formatNum = num.replace(/(\d{3})(\d{4})(\d{4})/, '$1-$2-$3');
			}
		}else if(num.length==8){
			formatNum = num.replace(/(\d{4})(\d{4})/, '$1-$2');
		}else{
			if(num.indexOf('02')==0){
				if(type==0){
					formatNum = num.replace(/(\d{2})(\d{4})(\d{4})/, '$1-****-$3');
				}else{
					formatNum = num.replace(/(\d{2})(\d{4})(\d{4})/, '$1-$2-$3');
				}
			}else{
				if(type==0){
					formatNum = num.replace(/(\d{3})(\d{3})(\d{4})/, '$1-***-$3');
				}else{
					formatNum = num.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
				}
			}
		}
		return formatNum;
	}
</script>