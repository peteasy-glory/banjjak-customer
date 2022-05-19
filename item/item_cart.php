<?php
include "../include/top.php";
include "../include/Crypto.class.php";
include "../Mobile_Detect.php";

$backurl = $_GET['backurl'];
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

$order_num = "I".STRTOTIME(DATE("Y-m-d H:i:s")).str_pad(rand(0,99),"2","0",STR_PAD_LEFT); // 결제용
?>

<script src="../js/fontawesome.min.js"></script>
<script src="<?= $js_directory ?>/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $css_directory ?>/jquery.fancybox.css?<?= filemtime($upload_static_directory . $css_directory . '/jquery.fancybox.css') ?>">
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<style>
	.top_menu { position: fixed; left: 0px; top: 0px; width: 100%; background-color: rgba(255,255,255,0.8); z-index: 2; }
	.scroll_top { display: none; position: fixed; right: 10px; bottom: 10px; width: 50px; height: 50px; background-color: rgba(255, 255, 255, 0.4); border-radius: 25px; -webkit-align-items: center; -webkit-justify-content: center; border: 1px solid #ccc; }
	.scroll_top.on { display: flex; }
	#item_cart { margin-top: 60px; font-family: 'NL2GR'; padding-top: 1px; }
	#item_cart>div { width: 100%; margin: 0px auto; }
	#item_cart .title { font-family: 'NL2GB'; font-size: 18px; background-color: #eee; height: 40px; line-height: 40px; padding: 0px 10px; text-align: center; }
	#item_cart .in_product { display: none; margin-bottom: 40px; }
	#item_cart .in_product.on { display: block; }
	#item_cart .in_product .title { background-color: #fce985; color: #736452; }
	#item_cart .out_product { display: none; margin-bottom: 40px; }
	#item_cart .out_product.on { display: block; }
	#item_cart .out_product .title { background-color: #c4e4a5; color: #736452; }
	#item_cart .special_product { display: none; margin-bottom: 40px; }
	#item_cart .special_product.on { display: block; }
	#item_cart .special_product .title { background-color: #F4F14E	; color: #736452; }
	#item_cart input[type='checkbox'] { display: none; width: 0px; height: 0px; font-size: 1px; }
	#item_cart input[type='checkbox']+label { position: relative; display: inline-block; border: 1px solid #ccc; padding: 0px 10px; height: 30px; line-height: 30px; border-radius: 5px; text-align: center; padding-left: 35px; }
	#item_cart input[type='checkbox']:checked+label { background-color: #fff; border: 1px solid #736452; color: #736452; }
	#item_cart input[type='checkbox']+label span { position: absolute; left: 4px; top: 4px; background-color: #eee; border: 1px solid #ccc; display: inline-block; width: 20px; height: 20px; }
	#item_cart input[type='checkbox']:checked+label span { background-color: #fff; border: 1px solid #736452; }
	#item_cart input[type='checkbox']:checked+label span:before { content: ''; display: inline-block; position: absolute; left: 2px; top: 4px; width: 10px; height: 5px; border-left: 5px solid #736452; border-bottom: 5px solid #736452; transform: rotate(-45deg); }
	#item_cart>div input[name='allchk']+label { margin: 10px; font-family: 'NL2GB'; }
	#item_cart ul.item_cart_list { }
	#item_cart ul.item_cart_list .sum_price { text-align: right; font-weight: Bold; }
	#item_cart ul.item_cart_list>li { position: relative; padding-bottom: 20px; border: 1px solid #ccc; padding: 10px; border-radius: 10px; margin: 10px; box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.2); }
	#item_cart ul.item_cart_list>li input[name='chk[]']+label { position: absolute; left: 10px; top: 10px; z-index: 1; background-color: transparent; border: 0px; }
	#item_cart ul.item_cart_list>li .set_delete_cart_btn { position: absolute; right: 10px; top: 10px; display: inline-block; width: 24px; height: 24px; display: flex; justify-content: center; align-items: center; color: #ccc; z-index: 3; }
	#item_cart ul.item_cart_list .item_data ul.table { display: table; width: 100%; }
	#item_cart ul.item_cart_list .item_data ul.table li { display: table-cell; vertical-align: top; }
	#item_cart ul.item_cart_list .item_data ul.table li:first-child { width: 80px; }
	#item_cart ul.item_cart_list .item_data ul.table li .item_image { width: 80px; height: 80px; background-color: #eee; background-size: cover; background-position: center; background-repeat: no-repeat; border-radius: 5px; }
	#item_cart ul.item_cart_list .item_data ul.table li>div {  }
	#item_cart ul.item_cart_list .item_data ul.table li .item_name { font-weight: Bold; width: calc(100% - 30px); margin-left: 10px; margin-bottom: 5px; line-height: 24px; }
	#item_cart ul.item_cart_list .item_data ul.table li .item_option { margin-left: 10px; line-height: 20px; }
	#item_cart ul.item_cart_list .item_data ul.table li .item_free_shipping { font-size: 12px; color: #999; margin: 5px 0px 5px 10px; }
	#item_cart ul.item_cart_list .item_data ul.table li .update_item_cart_btn { background-color: #fff; border: 1px solid #736452; color: #736452; height: 30px; padding: 0px 10px; border-radius: 5px; margin: 5px 0px 5px 5px; }
	#item_cart ul.item_cart_list .item_data .is_soldout { position: absolute; left: 0; right: 0; top: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.6); color: #fff; font-size: 24px; text-align: center; border-radius: 10px; display: flex; justify-content: center; align-content: center; flex-direction: column; z-index: 2; }
	#item_cart .product_price_wrap { font-size: 18px; width: calc(100% - 40px); margin: 0 auto; }
	#item_cart .product_price_wrap ul li { position: relative; text-align: left; padding: 2px 0px; }
	#item_cart .product_price_wrap ul li .tp_title {  }
	#item_cart .product_price_wrap ul li .tp_value { position: absolute; right: 2px; top: 2px; font-weight: Bold; }
	#item_cart .no_data { text-align: center; padding: 0px 0px; background-color: #fff; color: #999; }
	#item_cart .shipping_cost_dc { position: relative; border: 1px solid #ccc; background-color: #736452; color: #fff; padding: 10px; border-radius: 5px; margin: 20px 10px; }
	#item_cart .shipping_cost_dc .dc_title { font-weight: Bold; }
	#item_cart .shipping_cost_dc .dc_info { position: absolute; right: 10px; top: 16px; font-size: 12px; }
	#item_cart .shipping_cost_dc .dc_progressbar { width: 100%; margin: 10px auto 0px; border: 0px; background-color: #999; height: 3px; box-sizing: border-box; border-radius: 0px; }
	#item_cart .shipping_cost_dc .dc_progressbar .ui-progressbar-value { width: 25%; background-color: #f5bf2e; height: 100%; }
	#item_cart .payment_info { width: calc(100% - 20px); padding: 20px 0px; text-align: center; margin: 20px auto; border: 0px; border-radius: 0px; }
	#item_cart .payment_info .more_product { padding: 20px 0px 40px; }
	#item_cart .payment_info .more_product>a { text-decoration: underline; font-size: 16px; }
	#item_cart .payment_info .more_product>a>svg { vertical-align: top; }
	#item_cart .payment_info .total_price_wrap { font-size: 18px; font-weight: Bold; width: calc(100% - 40px); margin: 0 auto; }
	#item_cart .payment_info .total_price_wrap ul li { position: relative; text-align: left; padding: 2px 0px; }
	#item_cart .payment_info .total_price_wrap ul li .tp_title {  }
	#item_cart .payment_info .total_price_wrap ul li .tp_value { position: absolute; right: 2px; top: 2px; font-weight: Bold; }
	#item_cart .payment_info .btn_wrap { margin-top: 30px; }
	#item_cart .payment_info .btn_wrap button { width: 100%; height: 40px; border: 1px solid #ccc; background-color: #eee; color: #333; border-radius: 5px; font-size: 18px; }
	#item_cart .payment_info .btn_wrap button.on { background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
	.ui-dialog { position: fixed; top: auto !important; left: 0px !important; bottom: 0px !important; }
	.ui-dialog .ui-dialog-title { height: 1px; }
	.ui-dialog .ui-dialog-titlebar { padding: 0px; }
	.ui-dialog .ui-dialog-titlebar-close { right: calc(50% - 50px); width: 100px; margin: -25px 0 0 0; height: 20px; border-radius: 15px 15px 0px 0px; border: 1px solid #c5c5c5; border-bottom: 0px; color: #000; outline: 0px; }
	.ui-dialog .ui-state-hover, .ui-dialog .ui-widget-content .ui-dialog .ui-state-hover, .ui-dialog .ui-widget-header .ui-dialog .ui-state-hover, .ui-dialog .ui-state-focus, .ui-dialog .ui-widget-content .ui-dialog .ui-state-focus, .ui-dialog .ui-widget-header .ui-dialog .ui-state-focus, .ui-dialog .ui-button:hover, .ui-dialog .ui-button:focus { border: 1px solid #fff; background: #fff; font-weight: normal; color: #000; }
	.ui-dialog .ui-state-active, .ui-dialog .ui-widget-content .ui-dialog .ui-state-active, .ui-dialog .ui-widget-header .ui-dialog .ui-state-active, .ui-dialog a.ui-button:active, .ui-dialog .ui-button:active, .ui-dialog .ui-button.ui-state-active:hover { border: 1px solid #fff; background-color: #fff; color: #000; }
	.ui-dialog .ui-dialog .ui-state-active a, .ui-dialog .ui-state-active a:link, .ui-dialog .ui-state-active a:visited { color: #000; text-decoration: none; }
	.ui-dialog .ui-icon-closethick { background-position: -64px 0px; }
	#item_cart_popup select[name='item_option'] { width: calc(100% - 8px); height: 30px; padding: 0px 5px; margin-bottom: 20px; }
	#item_cart_popup select[name='item_option'] option[value='soldout'] { background-color: #999; color: #ccc; }
	#item_cart_popup .cart_wrap { max-height: 240px; border-bottom: 1px solid #ccc; overflow: auto; }
	#item_cart_popup tr td { position: relative; line-height: 30px; font-size: 14px; padding: 5px; font-family: 'NL2GR'; }
	#item_cart_popup .cart_plus_btn { width: auto; height: 25px; }
	#item_cart_popup .cart_plus_btn img { height: 100%; vertical-align: middle; }
	#item_cart_popup .cart_minus_btn { width: auto; height: 25px; }
	#item_cart_popup .cart_minus_btn img { height: 100%; vertical-align: middle; }
	#item_cart_popup .cart_delete_btn { width: auto; height: 25px; }
	#item_cart_popup .cart_delete_btn img { height: 100%; vertical-align: middle; }
	#item_cart_popup .item_number[type="text"] {font-size:12px;text-align:center;border: 1px solid #ccc;border-radius: 5px;width:25%;height: 13px;line-height: 20px;background-color: #FFF;padding: 5px;}
	#item_cart_popup .btn_wrap { position: absolute; left: 0px; bottom: 0px; width: 100%; }
	#item_cart_popup .btn_wrap .total_price { text-align: right; padding: 10px; margin-top: 10px; }
	#item_cart_popup .btn_wrap button { width: calc(100% - 8px); height: 40px; padding: 0px 10px; border: 1px solid #ccc; background-color: #eee; border-radius: 5px; font-family: 'NL2GB'; }
	#item_cart_popup .btn_wrap button.on { background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
</style>
<div class="top_menu">
	<?php if($backurl){ ?>
	<div class="top_back"><a href="<?=$backurl ?>"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
	<?php }else{ ?>
	<div class="top_back"><a href="<?=$mainpage_directory ?>"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
	<?php } ?>
	<div class="top_title">
		<p>장바구니</p>
	</div>
	<div class="top_home"></div>
	<div class="scroll_top"><i class="fas fa-chevron-up"></i></div>
</div>
<div id="item_cart">
</div>
<div id="item_cart_popup">
</div>

<script>
	var $item_cart = $("#item_cart");
	var $item_cart_popup = $("#item_cart_popup");
	var customer_id = "<?=$user_id ?>";
	var shipping_price = 2500;
	var is_free_shipping_limit = ["", 100000, 30000, 50000]; // '', 업체배송, 직배송, 하이포닉
	var is_free_shipping_total = ["", 0, 0, 0]; // '', 업체배송, 직배송, 하이포닉
	var is_free_shipping_cnt = ["", 0, 0, 0]; // '', 업체배송, 직배송, 하이포닉
	var is_free_shipping_price = ["", 0, 0, 0]; // '', 업체배송, 직배송, 하이포닉
	var new_order_num = "<?=$order_num ?>";
	var soldout_flag = 0;
	var item_update_flag = 0;
	var item_cart_cnt = 0;
	var cart = [];
	var _height = 220; // 팝업창 높이 default 220

	$(function(){
		get_item_cart_for_guest() // 비로그인일때 담아둔 데이터 있는지부터 확인(우선순위 변경)
			.then(get_item_cart)
			.then(price_calculator);
	});

	$item_cart.on("click", "input[name='allchk']", function(){
		var chk_cnt = 0;
		is_free_shipping_total = ["", 0, 0, 0];
		is_free_shipping_cnt = ["", 0, 0, 0];
		is_free_shipping_price = ['', 0, 0, 0];
		if($(this).is(":checked") == true){
			$item_cart.find("input[name='chk[]']").each(function(i, v){
				$(this).prop("checked", true);
				//console.log($(this).siblings(".sum_price").data("price"));
				if($(this).siblings('.item').find('.item_data').data('ip_seq') == '7'){ // 하이포닉 상품일때
					is_free_shipping_price[3] += parseInt($(this).siblings('.item').find('.sum_price').data('price'));
				}else{
					is_free_shipping_price[$(this).siblings('.item').find('.sum_price').data('is_supply')] += parseInt($(this).siblings('.item').find('.sum_price').data('price'));
				}

//				if($(this).siblings('.item').find('.sum_price').data('is_supply') == "1"){
//					is_free_shipping_total[$(this).siblings('.item').find('.sum_price').data('is_supply')]++;
//				}

				console.log("--------"+$(this).siblings('.item').find('.item_data').data('ip_seq'));
				if($(this).siblings('.item').find('.item_data').data('ip_seq') == '7'){ // 하이포닉 상품일때
					is_free_shipping_cnt[3]++;
				}else{
					is_free_shipping_cnt[$(this).siblings('.item').find('.sum_price').data('is_supply')]++;
				}
				chk_cnt++;
			});
		}else{
			$item_cart.find("input[name='chk[]']").each(function(i, v){
				$(this).prop("checked", false);
			});
		}
		price_calculator();
	});

	$item_cart.on("click", "input[name='chk[]']", function(){
		var flag = 0;
		var chk_cnt = 0;
		is_free_shipping_total = ["", 0, 0, 0];
		is_free_shipping_cnt = ["", 0, 0, 0];
		is_free_shipping_price = ['', 0, 0, 0];
		$item_cart.find("input[name='chk[]']").each(function(i, v){
			if($(this).is(":checked") == false){
				flag = 1;
			}else{
				if($(this).siblings('.item').find('.item_data').data('ip_seq') == '7'){ // 하이포닉 상품일때
					is_free_shipping_price[3] += parseInt($(this).siblings('.item').find('.sum_price').data('price'));
				}else{
					is_free_shipping_price[$(this).siblings('.item').find('.sum_price').data('is_supply')] += parseInt($(this).siblings('.item').find('.sum_price').data('price'));
				}

//				if($(this).siblings('.item').find('.sum_price').data('is_supply') == "1"){
//					is_free_shipping_total[$(this).siblings('.item').find('.sum_price').data('is_supply')]++;
//				}

				if($(this).siblings('.item').find('.item_data').data('ip_seq') == '7'){ // 하이포닉 상품일 때
					is_free_shipping_cnt[3]++;
				}else{
					is_free_shipping_cnt[$(this).siblings('.item').find('.sum_price').data('is_supply')]++;
				}
				chk_cnt++;
			}
		});
		price_calculator();

		if(flag == 0){
			$item_cart.find("input[name='allchk']").prop("checked", true);
		}else{
			$item_cart.find("input[name='allchk']").prop("checked", false);
		}
	});

	$item_cart.on("click", ".set_delete_cart_btn", function(){
		var ic_seq = $(this).parent("li").data("seq");

		$.MessageBox({
			buttonDone: "확인",
			buttonFail: "취소",
			message: "삭제 하시겠습니까?"
		}).done(function(){
			set_delete_item_cart(ic_seq);
		});
	});

	$item_cart.on("click", ".buy_go", function(){
		if($(this).hasClass("on") == true){
			if((parseInt(is_free_shipping_price[1]) + parseInt(is_free_shipping_price[2]) + parseInt(is_free_shipping_price[3])) > 0){
				//$.MessageBox({
				//	buttonDone: "확인",
				//	buttonFail: "취소",
				//	message: "구매 하시겠습니까?"
				//}).done(function(){
					set_item_payment();
				//});
			}else{
				$.MessageBox("구매하실 상품을 선택해주세요.");
			}
		}else{
			// 구매하기 버튼 무응답
		}
	});

	// 주문내용 수정 ---- start
	$item_cart.on("click", ".update_item_cart_btn", function(){
		var il_seq = $(this).data("il_seq");
		var ic_seq = $(this).data("ic_seq");
		var is_use_option = $(this).data("is_use_option");
		var is_supply = ($(this).data("is_supply") && $(this).data("is_supply") != "")? $(this).data("is_supply") : "2";
		var supplier = ($(this).data("supplier") && $(this).data("supplier") != "")? $(this).data("supplier") : "";
		var goodsNo = ($(this).data("goodsNo") && $(this).data("goodsNo") != "")? $(this).data("goodsNo") : "";

		console.log(il_seq, ic_seq, customer_id);

		_height = 220;
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode: "get_item_cart",
				is_session: "1",
				ic_seq: ic_seq,
				customer_id: customer_id
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log("@#$@$$");
					console.log(data.data);
					if(data.data, data.data.length > 0){
						$.each(data.data, function(i, v){
							cart = JSON.parse(v.cart_data);
						});
					}

					console.log(il_seq);
					$item_cart_popup.dialog({
						modal: true,
						title: '',
						autoOpen: true,
						maxWidth: "calc(100% - 2px)",
						minHeight: _height,
						width: "calc(100% - 2px)",
						height: _height,
						autoSize: true,
						resizable: false,
						draggable: false,
						show: {
							effect: "drop",
							duration: 100
						},
						hide: {
							effect: "drop",
							duration: 100
						},
						open: function(event, ui) {
							// to do something...
							$("body").css({ overflow: 'hidden' });

							var html = '';
							html += '<div>';
							html += '	<select name="item_option">';
							html += '	</select>';
							html += '	<div class="cart_wrap">';
							html += '		<table>';
							html += '			<colgroup>';
							html += '				<col width="60%">';
							html += '				<col width="30%">';
							html += '				<col width="*">';
							html += '			</colgroup>';
							html += '			<tbody>';
							html += '			</tbody>';
							html += '		</table>';
							html += '	</div>';
							html += '	<div class="btn_wrap">';
							html += '		<div class="total_price" data-price="0">총 합계 : 0원</div>';
							html += '		<button type="button" class="set_update_item_cart_btn" data-ic_seq="'+ic_seq+'">주문내용 수정하기</button>';
							html += '	</div>';
							html += '</div>';
							$item_cart_popup.html(html);
							get_item_option(il_seq, is_use_option, is_supply, supplier, goodsNo); // 옵션 사용여부 추가
							item_option_html(is_use_option);

							var _total_price = 0;
							$.each(cart, function(i, v){
								_total_price += parseInt(v.value * v.amount);
							});
							_height += (cart.length <= 6)? (40 * cart.length) : 0;
							$item_cart_popup.dialog("option", "height", _height);
							$item_cart_popup.dialog("option", "maxHeight", _height);
							$item_cart_popup.find(".total_price").html('총 합계 : '+_total_price.format()+'원');
							$item_cart_popup.find(".total_price").data("price", _total_price);
							if(cart.length > 0){
								$item_cart_popup.find(".set_update_item_cart_btn").addClass("on");
							}else{
								$item_cart_popup.find(".set_update_item_cart_btn").removeClass("on");
							}
						},
						close: function() {
							// to do something...
							$("body").css({ overflow: 'inherit' });
						}
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
	});

	// 주문내용 수정
	$item_cart_popup.on("click", ".set_update_item_cart_btn", function(){
		if($(this).hasClass("on") == true){
			var ic_seq = $(this).data("ic_seq");

			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode: "set_update_cart",
					is_session: "1",
					ic_seq: ic_seq,
					cart_data: JSON.stringify(cart),
					cart_price: $item_cart_popup.find(".total_price").data("price")
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						$.MessageBox("수정되었습니다.");

						// init
						cart = [];
						soldout_flag = 0;
						is_free_shipping_total = ["", 0, 0, 0];
						is_free_shipping_cnt = ["", 0, 0, 0];
						is_free_shipping_price = ['', 0, 0, 0];
						item_update_flag = 0;
						item_cart_cnt = 0;

						get_item_cart();
						$item_cart_popup.dialog("close");
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

	$item_cart_popup.on("change", "select[name='item_option']", function(){
		var value = $(this).children("option:selected").val();
		var seq = $(this).children("option:selected").data("option_seq");
		var txt = $(this).children("option:selected").data("option_txt");
		var is_free_shipping = $(this).children("option:selected").data("free_shipping");
		var is_use_option = $(this).children("option:selected").data("is_use_option");
		var is_supply = $(this).children("option:selected").data("is_supply");
		var supplier = $(this).children("option:selected").data("supplier");
		var goodsNo = $(this).children("option:selected").data("goodsNo");
		var html = '';
		if(value == "soldout" || value == ""){
			$.MessageBox("품절 상품입니다. 다른 상품을 선택해주세요.");
			$(this).val('');
		}else{
			cart.push({"seq" : seq, "value" : value, "amount" : 1, "is_free_shipping" : is_free_shipping, "txt" : txt, "il_seq" : "", "is_supply" : is_supply, "supplier" : supplier, "goodsNo" : goodsNo});
			console.log(cart);

			item_option_html(is_use_option);
			$(this).val('');

			var total_price = 0;
			$.each(cart, function(i, v){
				total_price += parseInt(v.value * v.amount);
			});
			_height += (cart.length <= 6)? 40 : 0;
			$item_cart_popup.dialog("option", "height", _height);
			$item_cart_popup.dialog("option", "maxHeight", _height);
			$item_cart_popup.find(".total_price").html('총 합계 : '+total_price.format()+'원');
			$item_cart_popup.find(".total_price").data("price", total_price);
		}
	});

	$item_cart_popup.on("click", ".cart_plus_btn", function(){
		var cart_no = $(this).parent().parent().closest(".select_line").data("no");
		var amount = parseInt($(this).siblings("input.cart_amount").val());
		amount += 1;
		if(amount > 99){
			amount = 99;
		}
		$(this).siblings("input.cart_amount").val(amount);
		cart[cart_no].amount = amount;

		var total_price = 0;
		$.each(cart, function(i, v){
			total_price += parseInt(v.value * v.amount);
		});

		$item_cart_popup.find(".total_price").html('총 합계 : '+total_price.format()+'원');
		$item_cart_popup.find(".total_price").data("price", total_price);
	});

	$item_cart_popup.on("click", ".cart_minus_btn", function(){
		var cart_no = $(this).parent().parent().closest(".select_line").data("no");
		var amount = parseInt($(this).siblings("input.cart_amount").val());
		amount -= 1;
		if(amount <= 0){
			amount = 1;
		}
		$(this).siblings("input.cart_amount").val(amount);
		cart[cart_no].amount = amount;

		var total_price = 0;
		$.each(cart, function(i, v){
			total_price += parseInt(v.value * v.amount);
		});

		$item_cart_popup.find(".total_price").html('총 합계 : '+total_price.format()+'원');
		$item_cart_popup.find(".total_price").data("price", total_price);
	});

	$item_cart_popup.on("click", ".cart_delete_btn", function(){
		var cart_no = $(this).parent().parent().closest(".select_line").data("no");
		var is_use_option = $(this).data("is_use_option");
		var html = '';
		$.each(cart, function(i, v){
			if(i == cart_no){
				cart.splice(i, 1);
			}
		});
		console.log(cart);

		item_option_html(is_use_option);

		var total_price = 0;
		$.each(cart, function(i, v){
			total_price += parseInt(v.value * v.amount);
		});
		_height -= (cart.length < 6)? 40 : 0;
		$item_cart_popup.dialog("option", "height", _height);
		$item_cart_popup.dialog("option", "maxHeight", _height);
		$item_cart_popup.find(".total_price").html('총 합계 : '+total_price.format()+"원");
		$item_cart_popup.find(".total_price").data("price", total_price);
	});
	// 주문내용 수정 ---- end


	function get_item_cart_for_guest(){
		return new Promise(function(resolve, reject) {
			// 비회원도 장바구니 체크
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode: "get_item_cart",
					is_session: "1",
					is_shop: "2"
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);

						if(data.data && data.data.length > 0){
							$.each(data.data, function(i, v){
								console.log(v.customer_id, customer_id);
								if(v.customer_id == "" && customer_id != ""){
									console.log("1"); // update
									set_update_item_cart(v.ic_seq);
								}else{
									// not used cart item
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
		});
	}

	function get_item_cart(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode: "get_item_cart",
					is_session: "1",
					is_shop: "2",
					customer_id: customer_id
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						var html = '';
						var ip_seq = data.data[0].ip_seq;

						if(data.data && data.data.length > 0){
							html += '<div class="">';
							html += '	<input type="checkbox" id="allchk" name="allchk" value="" checked />';
							html += '	<label for="allchk"><span></span>전체선택</label>';
							html += '	<div class="in_product">';
							html += '		<div class="title">직배송</div>';
							html += '		<div class="product_wrap">';
							html += '			<ul class="item_cart_list">';
							html += '			</ul>';
							html += '		</div>';
							html += '		<div class="shipping_cost_dc">';
							html += '			<div class="dc_title">3만원이상 무료배송</div>';
							html += '			<div class="dc_info"><span>0</span>원 추가하면 무료배송</div>';
							html += '			<div class="dc_progressbar">';
							html += '				<div class="ui-progressbar-value"></div>';
							html += '			</div>';
							html += '		</div>';
							html += '		<div class="product_price_wrap">';
							html += '			<ul>';
							html += '				<li>';
							html += '					<div class="tp_title">상품소계</div>';
							html += '					<div class="tp_value"><span class="product_price_txt">0</span>원</div>';
							html += '				<li>';
							html += '				<li>';
							html += '					<div class="tp_title">배송비</div>';
							html += '					<div class="tp_value"><span class="shipping_cost_txt">0</span>원</div>';
							html += '				<li>';
							html += '			<ul>';
							html += '		</div>';
							html +=	'	</div>';
							html += '	<div class="special_product">';
							html += '		<div class="title">직배송</div>';
							html += '		<div class="product_wrap">';
							html += '			<ul class="item_cart_list">';
							html += '			</ul>';
							html += '		</div>';
							html += '		<div class="shipping_cost_dc">';
							html += '			<div class="dc_title">5만원이상 무료배송</div>';
							html += '			<div class="dc_info"><span>0</span>원 추가하면 무료배송</div>';
							html += '			<div class="dc_progressbar">';
							html += '				<div class="ui-progressbar-value"></div>';
							html += '			</div>';
							html += '		</div>';
							html += '		<div class="product_price_wrap">';
							html += '			<ul>';
							html += '				<li>';
							html += '					<div class="tp_title">상품소계</div>';
							html += '					<div class="tp_value"><span class="product_price_txt">0</span>원</div>';
							html += '				<li>';
							html += '				<li>';
							html += '					<div class="tp_title">배송비</div>';
							html += '					<div class="tp_value"><span class="shipping_cost_txt">0</span>원</div>';
							html += '				<li>';
							html += '			<ul>';
							html += '		</div>';
							html +=	'	</div>';
							html += '	<div class="out_product">';
							html += '		<div class="title">업체배송</div>';
							html += '		<div class="product_wrap">';
							html += '			<ul class="item_cart_list">';
							html += '			</ul>';
							html += '		</div>';
							html += '		<div class="shipping_cost_dc">';
							html += '			<div class="dc_title">10만원이상 무료배송</div>';
							html += '			<div class="dc_info"><span>0</span>원 추가하면 무료배송</div>';
							html += '			<div class="dc_progressbar">';
							html += '				<div class="ui-progressbar-value"></div>';
							html += '			</div>';
							html += '		</div>';
							html += '		<div class="product_price_wrap">';
							html += '			<ul>';
							html += '				<li>';
							html += '					<div class="tp_title">상품소계</div>';
							html += '					<div class="tp_value"><span class="product_price_txt">0</span>원</div>';
							html += '				<li>';
							html += '				<li>';
							html += '					<div class="tp_title">배송비</div>';
							html += '					<div class="tp_value"><span class="shipping_cost_txt">0</span>원</div>';
							html += '				<li>';
							html += '			<ul>';
							html += '		</div>';
							html += '	</div>';
							html += '</div>';
							html += '<div class="payment_info">';
							html += '	<div class="more_product">';
							if(ip_seq =="7"){ // 하이포닉 상품일때
								html += '		<a href="<?=$shop_directory ?>/item_special_mall_hyponic.php?backurl='+encodeURIComponent(window.location.pathname+'?backurl=<?=urlencode($backurl) ?>')+'">더 채울 상품 둘러보기 <i class="fas fa-arrow-right"></i> </a>';
							}else{
								html += '		<a href="<?=$mainpage_directory ?>/index.php?tab=3&backurl='+encodeURIComponent(window.location.pathname+'?backurl=<?=urlencode($backurl) ?>')+'">더 채울 상품 둘러보기 <i class="fas fa-arrow-right"></i> </a>';
							}
							html += '	</div>';
							html += '	<div class="total_price_wrap">';
							html += '		<ul>';
							html += '			<li>';
							html += '				<div class="tp_title">총주문금액</div>';
							html += '				<div class="tp_value"><span class="total_price_txt">0</span>원</div>';
							html += '			<li>';
							html += '			<li>';
							html += '				<div class="tp_title">배송비</div>';
							html += '				<div class="tp_value"><span class="shipping_cost_txt">0</span>원</div>';
							html += '			<li>';
							html += '		<ul>';
							html += '	</div>';
							html += '	<div class="btn_wrap">';
							html += '		<button type="button" class="buy_go"><span>0</span>원 구매하기</button>';
							html += '	</div>';
							html += '</div>';
							$item_cart.html(html);
							item_cart_cnt = data.data.length;

							$.each(data.data, function(i, v){
								get_item_list(v)
									.then(price_calculator);
							});
						}else{
							html += '<div class="">';
							html += '	<div class="no_data"><img width="370px;" src="../images/clean_cart.jpg"/></div>';
							html += '</div>';
							$item_cart.html(html);
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
		});
	}

	function item_option_html(is_use_option){
		var html = '';
		$.each(cart, function(i, v){
			var is_free_shipping_txt = (v.is_free_shipping == "1")? "[무료배송] " : "";

			html += '<tr class="select_line" data-no="'+i+'">';
			html += '	<td>'+is_free_shipping_txt+''+v.txt+'</td>';
			html += '	<td style="text-align: right;">';
			html += '		<a href="javascript:;" class="cart_plus_btn"><img src="../images/btn/item_plus.png" class="btn_set"></a>';
			html += '		<input class="item_number cart_amount" type="text" value="'+v.amount+'" readonly />';
			html += '		<a href="javascript:;" class="cart_minus_btn"><img src="../images/btn/item_minus.png" class="btn_set"></a>';
			html += '	</td>';
			if(is_use_option == "1"){
				html += '	<td>';
				html += '		<a href="javascript:;" class="cart_delete_btn" data-is_use_option="'+is_use_option+'"><img src="../images/btn/item_close.png" class="btn_set"></a>';
				html += '	</td>';
			}
			html += '</tr>';
		});

		$item_cart_popup.find(".cart_wrap table tbody").html(html);
		if(cart.length > 0){
			$item_cart_popup.find(".set_update_item_cart_btn").addClass("on");
		}else{
			$item_cart_popup.find(".set_update_item_cart_btn").removeClass("on");
		}
	}

	function set_update_item_cart(ic_seq){ // 비로그인 세션 상품 > 회원 업데이트
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "set_update_cart",
					is_session: "1",
					cart_update : "1",
					ic_seq: ic_seq,
					customer_id: customer_id
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						
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
		});
	}

	function price_calculator(){
		return new Promise(function(resolve, reject) {
			console.log(is_free_shipping_price);
			if(item_update_flag == item_cart_cnt){ // each end flag
				console.log("end");
				var _shipping_price = ['', 0, 0, 0]; // 직배송 + 업체배송
				var _total_price = is_free_shipping_price[1] + is_free_shipping_price[2] + is_free_shipping_price[3];
				
				if(is_free_shipping_price[1] == 0 && is_free_shipping_price[2] == 0 && is_free_shipping_price[3] == 0){
					_shipping_price = ['', 0, 0, 0];
					$item_cart.find('.out_product .shipping_cost_dc .dc_info').html((is_free_shipping_limit[1]).format()+'원 추가하면 무료배송');
					$item_cart.find('.out_product .shipping_cost_dc .dc_progressbar .ui-progressbar-value').animate({width: '0%'}, 100);
					$item_cart.find('.in_product .shipping_cost_dc .dc_info').html((is_free_shipping_limit[2]).format()+'원 추가하면 무료배송');
					$item_cart.find('.in_product .shipping_cost_dc .dc_progressbar .ui-progressbar-value').animate({width: '0%'}, 100);
					$item_cart.find('.special_product .shipping_cost_dc .dc_info').html((is_free_shipping_limit[3]).format()+'원 추가하면 무료배송');
					$item_cart.find('.special_product .shipping_cost_dc .dc_progressbar .ui-progressbar-value').animate({width: '0%'}, 100);
				}else{
					if(is_free_shipping_price[1] >= is_free_shipping_limit[1]){ // 10만원 이상 구매시
						_shipping_price[1] = 0;

						$item_cart.find('.out_product .shipping_cost_dc .dc_info').html('무료배송');
						$item_cart.find('.out_product .shipping_cost_dc .dc_progressbar .ui-progressbar-value').animate({width: '100%'}, 100);
					}else{
						console.log("이게문제"+is_free_shipping_total[1] +" / "+ is_free_shipping_cnt[1]);
						if(is_free_shipping_total[1] == is_free_shipping_cnt[1]){ // 업체상품 전부 무료일 경우 무료
							_shipping_price[1] = 0;

							$item_cart.find('.out_product .shipping_cost_dc .dc_info').html('무료배송');
							$item_cart.find('.out_product .shipping_cost_dc .dc_progressbar .ui-progressbar-value').animate({width: '100%'}, 100);
						}else{
							_shipping_price[1] = parseInt(shipping_price);

							$item_cart.find('.out_product .shipping_cost_dc .dc_info').html((is_free_shipping_limit[1] - is_free_shipping_price[1]).format()+'원 추가하면 무료배송');
							$item_cart.find('.out_product .shipping_cost_dc .dc_progressbar .ui-progressbar-value').animate({width: Math.round(is_free_shipping_price[1] / is_free_shipping_limit[1] * 100)+'%'}, 100);
						}
					}
					if(is_free_shipping_price[2] >= is_free_shipping_limit[2]){ // 3만원 이상 구매시
						_shipping_price[2] = 0;

						$item_cart.find('.in_product .shipping_cost_dc .dc_info').html('무료배송');
						$item_cart.find('.in_product .shipping_cost_dc .dc_progressbar .ui-progressbar-value').animate({width: '100%'}, 100);
					}else{
						console.log("이건안문제"+is_free_shipping_total[2] +" / "+ is_free_shipping_cnt[2]);
						if(is_free_shipping_total[2] == is_free_shipping_cnt[2]){ // 직상품 전부 무료일 경우 무료
							_shipping_price[2] = 0;

							$item_cart.find('.in_product .shipping_cost_dc .dc_info').html('무료배송');
							$item_cart.find('.in_product .shipping_cost_dc .dc_progressbar .ui-progressbar-value').animate({width: '100%'}, 100);
						}else{
							_shipping_price[2] = parseInt(shipping_price);

							$item_cart.find('.in_product .shipping_cost_dc .dc_info').html((is_free_shipping_limit[2] - is_free_shipping_price[2]).format()+'원 추가하면 무료배송');
							$item_cart.find('.in_product .shipping_cost_dc .dc_progressbar .ui-progressbar-value').animate({width: Math.round(is_free_shipping_price[2] / is_free_shipping_limit[2] * 100)+'%'}, 100);
						}
					}
					if(is_free_shipping_price[3] >= is_free_shipping_limit[3]){ // 하이포닉 5만원 이상 구매시
						_shipping_price[3] = 0;

						$item_cart.find('.special_product .shipping_cost_dc .dc_info').html('무료배송');
						$item_cart.find('.special_product .shipping_cost_dc .dc_progressbar .ui-progressbar-value').animate({width: '100%'}, 100);
					}else{
						if(is_free_shipping_total[3] == is_free_shipping_cnt[3]){ // 하이포닉 전부 무료일 경우 무료
							console.log("cnt"+is_free_shipping_cnt[3]);
							console.log("total"+is_free_shipping_total[3]);
							_shipping_price[3] = 0;

							$item_cart.find('.special_product .shipping_cost_dc .dc_info').html('무료배송');
							$item_cart.find('.special_product .shipping_cost_dc .dc_progressbar .ui-progressbar-value').animate({width: '100%'}, 100);
						}else{
							_shipping_price[3] = parseInt(shipping_price);

							$item_cart.find('.special_product .shipping_cost_dc .dc_info').html((is_free_shipping_limit[3] - is_free_shipping_price[3]).format()+'원 추가하면 무료배송');
							$item_cart.find('.special_product .shipping_cost_dc .dc_progressbar .ui-progressbar-value').animate({width: Math.round(is_free_shipping_price[3] / is_free_shipping_limit[3] * 100)+'%'}, 100);
						}
					}

					if(_shipping_price[1] < 0){
						_shipping_price[1] = 0;
					}
					if(_shipping_price[2] < 0){
						_shipping_price[2] = 0;
					}
					if(_shipping_price[3] < 0){
						_shipping_price[3] = 0;
					}

					if(is_free_shipping_cnt[1] == 0){
						_shipping_price[1] = 0;
					}
					if(is_free_shipping_cnt[2] == 0){
						_shipping_price[2] = 0;
					}
					if(is_free_shipping_cnt[3] == 0){
						_shipping_price[3] = 0;
					}
				}

				$item_cart.find('.out_product .product_price_wrap .product_price_txt').html((is_free_shipping_price[1]).format());
				$item_cart.find('.in_product .product_price_wrap .product_price_txt').html((is_free_shipping_price[2]).format());
				$item_cart.find('.special_product .product_price_wrap .product_price_txt').html((is_free_shipping_price[3]).format());
				$item_cart.find('.out_product .product_price_wrap .shipping_cost_txt').html((_shipping_price[1]).format());
				$item_cart.find('.in_product .product_price_wrap .shipping_cost_txt').html((_shipping_price[2]).format());
				$item_cart.find('.special_product .product_price_wrap .shipping_cost_txt').html((_shipping_price[3]).format());

				if((parseInt(_shipping_price[1]) + parseInt(_shipping_price[2]) + parseInt(_shipping_price[3])) == 0){
					$item_cart.find('.payment_info .more_product').hide();
				}else{
					$item_cart.find('.payment_info .more_product').show();
				}
				$item_cart.find('.payment_info .total_price_wrap .total_price_txt').text(_total_price.format());
				$item_cart.find('.payment_info .total_price_wrap .shipping_cost_txt').text((_shipping_price[1] + _shipping_price[2] + _shipping_price[3]).format());
				$item_cart.find('.btn_wrap .buy_go span').text((_total_price + (_shipping_price[1] + _shipping_price[2] + _shipping_price[3])).format());
				if(_total_price > 0){
					if(soldout_flag == 0){
						$item_cart.find('.buy_go').addClass("on");
					}else{
						$item_cart.find('.buy_go').removeClass("on");
					}
				}else{
					$item_cart.find('.buy_go').removeClass("on");
				}
			}

			resolve();
		});
	}

	function get_item_list(cart_data){
		return new Promise(function(resolve, reject) {
			console.log(cart_data);
			//var cart_data = JSON.parse(cart_data);
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_item_list",
					product_no: cart_data.product_no
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						var html = '';
						var sum_price = 0;

						if(data.data && data.data.list && data.data.list.length > 0){
							$.each(data.data.list, function(i, v){
								var is_free_shipping = 0;
								var parse_cart_data = JSON.parse(cart_data.cart_data);

								html += '		<li data-seq="'+cart_data.ic_seq+'">';
								html += '			<div class="set_delete_cart_btn"><i class="fas fa-trash-alt"></i></div>';
								html += '			<input type="checkbox" id="chk_'+cart_data.ic_seq+'" name="chk[]" value="'+cart_data.ic_seq+'" checked />';
								html += '			<label for="chk_'+cart_data.ic_seq+'"><span></span></label>';
								html += '			<div class="item">';
								html += '				<div class="item_data" data-id="'+v.il_seq+'" data-ip_seq="'+v.ip_seq+'">';
								if(v.is_soldout == "2"){
									html += '					<div class="is_soldout">일 시 품 절</div>';
									soldout_flag++;
								}
								html += '					<ul class="table">';
								html += '						<li>';
								html += '							<div class="item_image"></div>';
								html += '						</li>';
								html += '						<li>';
								html += '							<div class="item_name">'+v.product_name+'</div>';
								$.each(parse_cart_data, function(i2, v2){
									html += '							<div class="item_option">'+v2.txt+' / '+v2.value.format()+'원 / '+v2.amount+'개</div>';
									if(v2.is_free_shipping == 1){
										is_free_shipping_total[v.is_supply]++;
										is_free_shipping = 1;
									}
									if(v.ip_seq == "7"){ // 하이포닉 상품일때
										is_free_shipping_cnt[3]++;
									}else{
										is_free_shipping_cnt[v.is_supply]++;
									}
									sum_price += parseInt(v2.value * v2.amount);
								});
								if(is_free_shipping == 1){
									html += '							<div class="item_free_shipping">배송비 무료</div>';
								}else{
									html += '							<div class="item_free_shipping">배송비 '+shipping_price.format()+'원</div>';
								}
								html += '								<button type="button" class="update_item_cart_btn" data-il_seq="'+v.il_seq+'" data-ic_seq="'+cart_data.ic_seq+'" data-free_shipping="2" data-is_use_option="'+v.is_use_option+'" data-is_supply="'+v.is_supply+'" data-supplier="'+v.supplier+'" data-goodsNo="'+v.goodsNo+'">주문내용 수정</button>';
								html += '							</div>';
								html += '							<div class="sum_price" data-is_supply="'+v.is_supply+'" data-price="'+sum_price+'">총 합계 : '+sum_price.format()+'원</div>';
								html += '						</li>';
								html += '					</ul>';
								html += '				</div>';
								html += '			</div>';
								html += '		</li>';
								
								if(v.ip_seq == "7"){
									is_free_shipping_price[3] += sum_price;
								}else{
									is_free_shipping_price[v.is_supply] += sum_price;
								}
								if(v.is_supply == "1"){ // 업체배송
									$item_cart.find('.out_product .product_wrap .item_cart_list').append(html);
									$item_cart.find('.out_product').addClass('on');
								}else if(v.is_supply == "2"){ // 직배송
									if(v.ip_seq == '7'){ // 하이포닉 상품일 때
										$item_cart.find('.special_product .product_wrap .item_cart_list').append(html);
										$item_cart.find('.special_product').addClass('on');
									}else{
										$item_cart.find('.in_product .product_wrap .item_cart_list').append(html);
										$item_cart.find('.in_product').addClass('on');
									}
								}else{
									// 제품 구분이 없음 - 예외처리 필요
								}

								get_file_list(cart_data.ic_seq, v.il_seq, v.product_img, v.goodsRepImage);
								item_update_flag++;
								resolve();
							});
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
		});
	}

	function get_item_option(il_seq, is_use_option, is_supply, supplier, goodsNo){
		// item_option loading
		if(is_use_option == "1"){
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_item_option",
					il_seq: il_seq
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						var select_html = '<option value="">선택</option>';
						$.each(data.data, function(i, v){
							var price_val = (v.is_soldout == "2")? "soldout" : v.sale_price;
							var price_txt = (v.is_soldout == "2")? "품절" : v.sale_price.format()+"원";
							var price_view_txt = (v.is_soldout == "2")? "[품절]" : '<span class="line-through">'+v.option_price.format()+'원</span> &gt; '+v.sale_price.format()+'원';
							var free_shippping_txt = (v.is_free_shipping == "1")? "[무료배송]" : "";
							select_html += '<option value="'+price_val+'" data-option_seq="'+v.io_seq+'" data-option_txt="'+v.option_name+'" data-free_shipping="'+v.is_free_shipping+'" data-is_use_option="'+is_use_option+'"  data-is_supply="'+is_supply+'"  data-supplier="'+supplier+'"  data-goodsNo="'+goodsNo+'" class="'+price_val+'">'+free_shippping_txt+''+v.option_name+' '+price_txt+'</option>';
						});

						$item_cart_popup.find("select[name='item_option']").html(select_html);

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
			var select_html = '<option value="">단일상품</option>';
			$item_cart_popup.find("select[name='item_option']").html(select_html);
		}
	}

	function get_file_list(ic_seq, il_seq, img_list, goodsRepImage){
		console.log(img_list);
		if(img_list && typeof img_list != "undefined" && img_list != ""){
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
						console.log(data.data);
						var html = '';
						$.each(data.data, function(i, v){
							if(i == 0){
								$item_cart.find('.item_cart_list li[data-seq="'+ic_seq+'"] .item_data[data-id="'+il_seq+'"] .item_image').css('background-image', 'url("'+v.file_path+'")');
							}
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
		}else{
			if(goodsRepImage && goodsRepImage != ""){
				$item_cart.find('.item_cart_list li[data-seq="'+ic_seq+'"] .item_data[data-id="'+il_seq+'"] .item_image').css('background-image', 'url("'+goodsRepImage+'")');
			}else{
				// no_image
			}
		}
	}

	function set_delete_item_cart(ic_seq){
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "set_delete_item_cart",
				ic_seq: ic_seq,
				user_id: customer_id,
				delete_txt: "item_cart에서 직접 삭제"
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					$.MessageBox("삭제되었습니다.");

					// init
					cart = [];
					soldout_flag = 0;
					is_free_shipping_total = ["", 0, 0, 0];
					is_free_shipping_cnt = ["", 0, 0, 0];
					is_free_shipping_price = ['', 0, 0, 0];
					item_update_flag = 0;
					item_cart_cnt = 0;

					get_item_cart();
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

	function set_item_payment(){
		// 선택한 상품들에게 같은 결제번호 부여(update) 후 결제페이지로 이동
		var cart_list = [];
		var cnt = 0;
		$item_cart.find("input[name='chk[]']").each(function(i, v){
			if($(this).is(":checked") == true && $(this).val() != ""){
				cart_list.push($(this).val());
			}
		});
		console.log(cart_list);
		if(cart_list.length > 0){
			$.each(cart_list, function(i, v){
				$.ajax({
					url: '<?=$item_directory ?>/item_list_ajax.php',
					data: {
						mode: "set_update_cart",
						is_session: "1",
						ic_seq: v,
						order_num: new_order_num
					},
					type: 'POST',
					dataType: 'JSON',
					success: function(data) {
						if(data.code == "000000"){
							console.log(data.data);
							// 쿠키값 변경??, IOS 예외처리??
							//setCookie("order_num", data.data, 1);
							//setCookie("order_step", "1", 1); // 1 - 구매, 2 - 조회
							cnt++;

							if(cnt == cart_list.length){
								location.href = "<?=$item_directory ?>/item_payment.php?no="+new_order_num;
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
		}
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