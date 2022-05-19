<?php
include "../include/top.php";
include "../include/Crypto.class.php";
include "../Mobile_Detect.php";

// init
$r_no = ($_GET["no"] && $_GET["no"] != "")? $_GET["no"] : "";
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

$pay_status_arr = array(
	"1" => "상품준비중",
	"2" => "배송준비중",
	"3" => "배송중",
	"4" => "배송완료",
	"8" => "반품",
	"9" => "취소"
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

$reason_type_arr = array(
	"1" => "단순변심", 
	"2" => "상품불량", 
	"3" => "제품변경", 
	"etc" => "기타"
);

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
	#item_order_detail { position: relative; width: 100%; margin-top: 70px; font-size: 16px; font-family: 'NL2GR'; font-weight: normal; }
	#item_order_detail .order_box .order_box_title { position: relative; height: 20px; }
	#item_order_detail .order_box .order_box_title span.order_status { position: absolute; left: 20px; top: 15px; font-size: 16px; }
	#item_order_detail .order_box .order_box_title span.order_num { position: absolute; right: 25px; top: 20px; }
	#item_order_detail .order_box .button_wrap { margin-top: 30px; }
	#item_order_detail .order_box a.quick_search_btn { border: 1px solid #999; border-radius: 6px; color: #999; padding: 0px 10px; text-decoration: none; width: auto; height: 30px; line-height: 30px; margin: 10px 0px 5px 0px; text-align: center; font-weight: Bold; font-size: 14px; }
	#item_order_detail .order_box a.shipping_done { border: 1px solid #f5bf2e; border-radius: 6px; color: #f5bf2e; padding: 0px 10px; text-decoration: none; width: auto; height: 30px; line-height: 30px; margin: 10px 0px 5px 0px; text-align: center; font-weight: Bold; font-size: 14px; }
	#item_order_detail .order_box a.review_btn { border: 1px solid #f5bf2e; border-radius: 6px; color: #f5bf2e; padding: 0px 10px; text-decoration: none; width: auto; height: 30px; line-height: 30px; margin: 10px 0px 5px 0px; text-align: center; font-weight: Bold; font-size: 14px; }
	#item_order_detail .order_box a.cancel_please { border: 1px solid #999; border-radius: 6px; color: #999; padding: 0px 10px; text-decoration: none; width: auto; height: 30px; line-height: 30px; margin: 10px 0px 5px 0px; text-align: center; font-weight: Bold; font-size: 14px; }
	#item_order_detail .order_box a.return_please { border: 1px solid #999; border-radius: 6px; color: #999; padding: 0px 10px; text-decoration: none; width: auto; height: 30px; line-height: 30px; margin: 10px 0px 5px 0px; text-align: center; font-weight: Bold; font-size: 14px; }
	#item_order_detail .order_box .product_img { vertical-align: top; }
	#item_order_detail .order_box .item_image { background-repeat: no-repeat; background-position: top; background-size: cover; }
	#item_order_detail .order_box a.quick_search2 { height: 30px; line-height: 30px; text-align: center; padding: 0px 10px; background-color: #fff; border: 1px solid #f5bf2e; color: #f5bf2e; border-radius: 10px; }
</style>
<div class="top_menu">
	<div class="header-back-btn"><a href="<?=$item_directory ?>/item_order_list.php"><img src="/pet/images/btn_back_2.png" width="26px"></a></div>
	<div class="top_title">
		<p>결제/주문 상세내역</p>
	</div>
</div>
<div id="item_order_detail"></div>
<script>
var $item_order_detail = $("#item_order_detail");
var no = "<?=$r_no ?>";
var img_list = [];
var pay_status_arr = $.parseJSON('<?=json_encode($pay_status_arr)?>');
var order_status_arr = $.parseJSON('<?=json_encode($order_status_arr)?>');
var pay_status_arr_old = $.parseJSON('<?=json_encode($pay_status_arr_old)?>');
var reason_type_arr = $.parseJSON('<?=json_encode($reason_type_arr)?>');

$(function(){
	init_html()
		.then(get_item_order_list)
		.then(get_item_payment_log_product);
		//.then(item_image);
});

$item_order_detail.on("click", ".return_please", function(){
	location.href = "<?=$item_directory ?>/item_order_return_list.php?no="+no;
});

$item_order_detail.on("click", ".cancel_please", function(){
	location.href = "<?=$item_directory ?>/item_order_cancel_list.php?no="+no;
});

$item_order_detail.on("click", ".quick_search_btn", function(){ // 배송조회
	var shipping_invoice = $(this).data("shipping_invoice");
	var shipping_company = $(this).data("shipping_company");

	if(shipping_company == "우체국"){
		//window.open("https://service.epost.go.kr/iservice/usr/trace/usrtrc001k01.jsp", "_BLANK");
	}else if(shipping_company == "대한통운"){
		window.open("https://www.cjlogistics.com/ko/tool/parcel/newTracking?gnbInvcNo="+shipping_invoice, "_BLANK");
		//window.open("https://www.cjlogistics.com/ko/tool/parcel/tracking", "_BLANK");
		//window.open("http://nplus.doortodoor.co.kr/web/detail.jsp?slipno="+shipping_invoice, "_BLANK");
	}else if(shipping_company == "한진택배"){
		html = '';
		html += '<form id="send_shipping_data" method="post" action="https://m.hanex.hanjin.co.kr/inquiry/incoming/resultWaybill" style="display: none;">';
		html += '	<input type="text" id="div" name="div" value="B" />';
		html += '	<input type="text" id="show" name="show" value="true" />';
		html += '	<input type="text" id="wblNum" name="wblNum" value="'+shipping_invoice+'" />';
		html += '</form>';
		$(document).find("#item_order_list").after(html);
		$(document).find("#send_shipping_data").submit();
	}
});

$item_order_detail.on("click", ".shipping_done", function(){
	var _iplp_seq = $(this).data('iplp_seq');
	$.MessageBox({
		buttonDone  : "확인",
		buttonFail  : "취소",
		message     : "수취 확인 하시겠습니까?"
	}).done(function(data, button){
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "set_update_item_payment_log_product_pay_status",
				pay_status: "4",
				order_num: no,
				iplp_seq: _iplp_seq
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
				alert(error + "네트워크에러");
			}
		});
	}).fail(function(data, button){
	});
});

$item_order_detail.on("click", ".shipping_done_old", function(){
	var _iplp_seq = $(this).data('iplp_seq');
	$.MessageBox({
		buttonDone  : "확인",
		buttonFail  : "취소",
		message     : "수취 확인 하시겠습니까?"
	}).done(function(data, button){
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "set_update_pay_status",
				pay_status: "6",
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
				alert(error + "네트워크에러");
			}
		});
	}).fail(function(data, button){
	});
});

function init_html(){
	return new Promise(function(resolve, reject) {
		var html = '';

		html += '<div class="order_box">';
		html += '	<div class="order_detail_wrap">';
		html += '		<div class="order_box_title">';
		html += '			<span class="order_status"></span>';
		html += '			<span class="order_num"></span>';
		html += '		</div>';
		html += '		<div class="order_box_text"style="">';
		html += '			<table width="100%"class="order_table">';
		html += '				<colgroup>';
		html += '					<col width="30%">';
		html += '					<col width="70%">';
		html += '				</colgroup>';
		html += '				<tbody>';
		html += '					<tr style="border-bottom:2px solid #ddd;">';
		html += '						<td style="padding-bottom: 5px;">배송지 정보</td>';
		html += '						<td style="text-align: right;padding-bottom: 5px;" class="pay_dt"></td>';
		html += '					</tr>';
		html += '					<tr>';
		html += '						<td colspan="2" style="padding:5px 0 2px;font-size:12px;">받으시는 분 : <span class="shipping_name"></span></td>';
		html += '					</tr>';
		html += '					<tr>';
		html += '						<td colspan="2" style="padding:2px 0;font-size:12px;">연락처 : <span class="shipping_cellphone"></span></td>';
		html += '					</tr>';
		html += '					<tr>';
		html += '						<td colspan="2" style="padding:2px 0;font-size:12px;">받으시는 곳 : <span class="shipping_addr"></span></td>';
		html += '					</tr>';
		html += '					<tr>';
		html += '						<td colspan="2" style="padding:2px 0;font-size:12px;">배송요청사항 : <span class="shipping_memo"></span></td>';
		html += '					</tr>';
		html += '				</tbody>';
		html += '			</table>';
		html += '		</div>';
		html += '		<div class="order_box_text"style="">';
		html += '			<table width="100%" class="order_table">';
		html += '				<colgroup>';
		html += '					<col width="30%">';
		html += '					<col width="70%">';
		html += '				</colgroup>';
		html += '				<tbody>';
		html += '					<tr>';
		html += '						<td style="padding:5px 0 2px;font-size:12px;">상품가격</td>';
		html += '						<td style="text-align: right;padding:5px 0 2px;font-size:12px;" class="product_price">0원</td>';
		html += '					</tr>';
		html += '					<tr style="border-bottom:2px solid #ddd;">';
		html += '						<td style="padding:5px 0;font-size:12px;">배송비</td>';
		html += '						<td style="text-align: right;padding-bottom: 5px;font-size:12px;" class="shipping_price">0원</td>';
		html += '					</tr>';
		html += '					<tr>';
		html += '						<td style="padding:5px 0 2px;font-size:12px;">포인트사용</td>';
		html += '						<td style="text-align: right;padding:5px 0 2px;font-size:12px;" class="point_price">0원</td>';
		html += '					</tr>';
		html += '					<tr>';
		html += '						<td style="padding:10px 0 2px;">총 결제 금액</td>';
		html += '						<td style="text-align: right;padding:5px 0 2px;" class="total_price">0원</td>';
		html += '					</tr>';
		html += '				</tbody>';
		html += '			</table>';
		html += '		</div>';
		html += '		<div class="order_box_text"style="">';
		html += '			<table width="100%"class="order_table">';
		html += '				<colgroup>';
		html += '					<col width="100px">';
		html += '					<col width="*">';
		html += '				</colgroup>';
		html += '				<thead>';
		html += '					<tr style="border-bottom:2px solid #ddd;">';
		html += '						<td colspan="2"style="padding-bottom: 5px;">주문상품</td>';
		html += '					</tr>';
		html += '				</thead>';
		html += '				<tbody class="item_option_wrap">';
		html += '				</tbody>';
		html += '			</table>';
		html += '		</div>';
		html += '	</div>';
		html += '	<div class="order_box_text_none button_wrap" style="text-align: center;">';
		html += '	</div>';
		html += '</div>';

		$item_order_detail.html(html);
		resolve();
	});
}

function get_item_order_list(){
	return new Promise(function(resolve, reject) {
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
					var img_list = '';
					var jbook_chk = 0;

					$.each(data.data, function(i, v){
						$item_order_detail.find(".order_status").text(order_status_arr[v.order_status]);
						$item_order_detail.find(".order_num").text("주문번호:"+v.order_num);
						$item_order_detail.find(".pay_dt").text(v.pay_dt);
						$item_order_detail.find(".shipping_name").text(v.shipping_name);
						$item_order_detail.find(".shipping_cellphone").text(v.shipping_cellphone);
						if(v.shipping_zipcode && v.shipping_zipcode != ""){
							$item_order_detail.find(".shipping_addr").text("("+v.shipping_zipcode+")"+v.shipping_addr+" "+v.shipping_addr2);
						}else{
							$item_order_detail.find(".shipping_addr").text("("+v.shipping_addr.split("|")[0]+")"+v.shipping_addr.split("|")[1]);
						}
						$item_order_detail.find(".shipping_memo").text(v.shipping_memo);
						$item_order_detail.find(".product_price").text(v.product_price.format()+"원");
						$item_order_detail.find(".shipping_price").text(v.shipping_price.format()+"원");
						$item_order_detail.find(".point_price").text(v.point_price.format()+"원");
						$item_order_detail.find(".total_price").text(v.total_price.format()+"원");

						// 상태별 버튼 노출여부
						//if(v.order_status == '3'){ // 결제완료
						//	$item_order_detail.find(".order_box .button_wrap").append('<a href="javascript:;" class="cancel_please">취소요청</a><br/>');
						//}
						
						img_list = v.product_img;
						/*
						var pay_data_list = (v.pay_data && v.pay_data.indexOf('||') != -1)? v.pay_data.split('||') : [v.pay_data];
						var cart_html = '';
						$.each(pay_data_list, function(i2, v2){
							var pay_data = $.parseJSON(v2.replace(/\\/g, ''));
							console.log(pay_data);
							$.each(pay_data, function(i3, v3){
								cart_html += '<tr>';
								cart_html += '	<td style="padding:10px 0;" class="product_img"><img src="/pet/images/ex_image.png" width="100%"></td>';
								cart_html += '	<td style="padding:10px 0 10px 10px;">';
								cart_html += '		<p style="font-size:12px;">'+v3.txt+'</p>';
								cart_html += '		<p style="margin-top:10px;">'+v3.amount+' / '+v3.value.format()+'원</p>';
								cart_html += '	</td>';
								cart_html += '</tr>';
							});
						});

						$item_order_detail.find(".item_option_wrap").html("").html(cart_html);
						*/
						if(v.jbOrdNo && v.jbOrdNo != ""){
							jbook_chk++;
						}
					});

					if(jbook_chk > 0){
						get_jbook_invoice(no); // 정글북 배송조회
					}

					resolve(data.data);
				}else{
					alert(data.data+"("+data.code+")");
					console.log(data.code);
				}
			},
			error: function(xhr, status, error) {
				alert(error + "네트워크에러");
			}
		});
	});
}

function get_item_payment_log_product(post_data){
	return new Promise(function(resolve, reject) {
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "get_item_payment_log_product",
				order_num: no
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					var html = '';

					if(data.data && data.data.length > 0){
						$.each(data.data, function(i, v){
							var pay_data = $.parseJSON(v.option_data.replace(/\\/g, ''));
							html += '<tr data-id="'+v.product_no+'" data-iplp_seq="'+v.iplp_seq+'">';
							html += '	<td style="padding:10px 0; background-image: url(\'/pet/images/product_img.png\');" class="product_img"><div style="background-image: url(\'/pet/images/product_img.png\'); width: 100px; height: 100px;" class="item_image"></div></td>';
							html += '	<td style="padding:10px 0 10px 10px; vertical-align: top;">';
							html += '		<div style="font-size:18px;">'+pay_status_arr[v.pay_status]+'</div>';
							$.each(pay_data, function(i2, v2){
								html += '		<div style="font-size:12px; margin:10px 0px 2px;">'+v2.txt+'</div>';
								html += '		<div style="font-weight: Bold;">'+v2.amount+'개 / '+v2.value.format()+'원</div>';
							});
							html += '		<div class="shipping_wrap"></div>';
							html += '		<div class="btn_wrap"></div>';
							html += '	</td>';
							html += '</tr>';
						});

						$item_order_detail.find(".item_option_wrap").html(html);

						$.each(data.data, function(i, v){
							var html_shipping = '';
							var html_btn = '';

							if(v.pay_status == "1"){
								html_btn += '		<a href="javascript:;" class="cancel_please">취소요청</a>';
							}else if(v.pay_status == "2"){
								if(v.shipping_company && v.shipping_company != "" && v.shipping_invoice && v.shipping_invoice != ""){
									html_shipping += '		<div style="margin-top: 10px;">['+v.shipping_company+'] '+v.shipping_invoice+'</div>';
									html_btn += '		<a href="javascript:;" class="quick_search_btn" data-shipping_company="'+v.shipping_company+'" data-shipping_invoice="'+v.shipping_invoice+'">배송조회</a>';
								}
								html_btn += '		<a href="javascript:;" class="cancel_please">취소요청</a>';
							}else if(v.pay_status == "3"){
								if(v.shipping_company && v.shipping_company != "" && v.shipping_invoice && v.shipping_invoice != ""){
									html_shipping += '		<div style="margin-top: 10px;">['+v.shipping_company+'] '+v.shipping_invoice+'</div>';
									html_btn += '		<a href="javascript:;" class="quick_search_btn" data-shipping_company="'+v.shipping_company+'" data-shipping_invoice="'+v.shipping_invoice+'">배송조회</a>';
								}
								html_btn += '		<a href="javascript:;" class="shipping_done" data-iplp_seq="'+v.iplp_seq+'">수취확인</a>';
							}else if(v.pay_status == "4"){
								html_btn += '		<a href="javascript:;" class="return_please">반품요청</a>';
							}else if(v.pay_status == "8"){
								// 반품사유
							}else if(v.pay_status == "9"){
								// 취소사유
							}
							
							$item_order_detail.find(".item_option_wrap tr[data-iplp_seq='"+v.iplp_seq+"'] .shipping_wrap").html(html_shipping);
							$item_order_detail.find(".item_option_wrap tr[data-iplp_seq='"+v.iplp_seq+"'] .btn_wrap").html(html_btn);
							get_item_image('.item_option_wrap tr', v.product_no);
						});
					}else{
						// tb_item_payment_log_product 없을때
						console.log(post_data);
						var html_btn = '';

						if(post_data && post_data.length > 0){
							$.each(post_data, function(i, v){
								let _pay_data_list = (v.pay_data && v.pay_data != "")? ((v.pay_data.indexOf('||') != -1)? v.pay_data.split('||') : [v.pay_data]) : [];

								if(_pay_data_list.length > 0){
									$.each(_pay_data_list, function(i2, v2){
										let _pay_data = (v2 && v2 != "")? $.parseJSON(v2.replace(/\\/g, '')) : [];
										html += '<tr class="item_product_old">';
										html += '	<td>';
										html += '		<div class="item_image" style="background-image: url(\'/pet/images/product_img.png\'); width: 100px; height: 100px;"></div>';
										html += '	</td>';
										html += '	<td class="item_detail">';
										html += '		<div class="item_pay_status" data-pay_status="'+v.pay_status+'">'+pay_status_arr_old[v.pay_status]+'</div>';

										if(_pay_data.length > 0){
											$.each(_pay_data, function(i3, v3){
												html += '		<div class="item_name">'+v3.txt+'</div>';
												html += '		<div class="item_price">'+v3.value.format()+'원 x '+v3.amount+'개</div>';
											});
										}
										html += '	</td>';
										html += '</tr>';
									});

									$item_order_detail.find(".item_option_wrap").html(html);

									$.each(_pay_data_list, function(i2, v2){
										let _pay_data = (v2 && v2 != "")? $.parseJSON(v2.replace(/\\/g, '')) : [];
										if(_pay_data.length > 0){
											$.each(_pay_data, function(i3, v3){
												get_item_option(v.order_num, v3);
											});
										}
									});
								}
								
								console.log(v.pay_status);
								if(v.pay_status == "1"){
									// 진행중
								}else if(v.pay_status == "2"){
									// 입금대기
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
									$item_order_detail.find(".order_box .order_detail_wrap").append(html3);
									$item_order_detail.find(".order_box .button_wrap").append('<a href="javascript:;" class="cancel_please">취소요청</a><br/>');
								}else if(v.pay_status == "3"){
									// 상품준비중
									$item_order_detail.find(".order_box .button_wrap").append('<a href="javascript:;" class="cancel_please">취소요청</a><br/>');
								}else if(v.pay_status == "4"){
									// 배송준비중
									$item_order_detail.find(".order_box .button_wrap").append('<a href="javascript:;" class="cancel_please">취소요청</a><br/>');
								}else if(v.pay_status == "5"){
									// 배송중
									var html2 = '';
									if(v.shipping_invoice && v.shipping_invoice != ""){
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
										$("#item_order_detail .order_box .button_wrap").append('<a href="javascript:;" class="shipping_done_old">수취확인</a><br/>');
									}else{
										$("#item_order_detail .order_box .order_detail_wrap .shipping_data").remove();
										html2 += '<div class="order_box_text shipping_data" style="display: none;">';
										html2 += '</div>';
										$("#item_order_detail .order_box .order_detail_wrap").append(html2);
									}
								}else if(v.pay_status == "6"){
									// 배송완료
									$item_order_detail.find(".order_box .button_wrap").append('<a href="javascript:;" class="review_btn">리뷰작성</a><br/>');
									$item_order_detail.find(".order_box .button_wrap").append('<a href="javascript:;" class="return_please">반품요청</a><br/>');
								}else if(v.pay_status == "7"){
									// 취소
									var html1 = '';
									if(v.is_cancel == "2" || v.is_return == "2"){
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
										$("#item_order_detail .order_box .order_detail_wrap").append(html1);
									}
								}else if(v.pay_status == "8"){
									// 보류
								}else if(v.pay_status == "9"){
									// 실패
								}
							});
						}
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
	});
}

function get_item_option(order_num, pay_data){
	return new Promise(function(resolve, reject) {
		console.log("!get_item_option", order_num, pay_data);
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
						$.each(data.data, function(i, v){
							if(i == 0){
								$item_order_detail.find('.item_option_wrap tr').attr('data-id', v.product_no);
								get_item_image('.item_option_wrap tr', v.product_no);
							}
						});
					}
					resolve();
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
									$item_order_detail.find(target+"[data-id='"+product_no+"'] .item_image").css("background-image", "url('"+v.file_path+"')");
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
				$item_order_detail.find(target+"[data-id='"+product_no+"'] .item_image").css("background-image", "url('"+goodsRepImage+"')");
			}else{
				$item_order_detail.find(target+"[data-id='"+product_no+"'] .item_image").css("background-image", "url('../images/product_img.png')");
			}
		}
	});
}

function get_jbook_invoice(option){
	console.log("come");
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
					$(document).find("#item_order_detail .order_detail_wrap").append(html);
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
</script>