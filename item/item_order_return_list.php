<?php
include "../include/top.php";
include "../include/Crypto.class.php";
include "../Mobile_Detect.php";

// init
$r_no = ($_GET["no"] && $_GET["no"] != "")? $_GET["no"] : "";
$backurl = $_GET['backurl'];
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
?>

<script src="../js/fontawesome.min.js"></script>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">

<style>
    .top_menu { position: fixed; left: 0px; top: 0px; width: 100%; background-color: rgba(255,255,255,0.8); z-index: 1; }
	#item_order_return { margin-top: 61px; }
	#item_order_return .order_box .next {
		-webkit-appearance: none;
		border-radius: 0;
		background-color: #f5bf2e;
		-webkit-border-top-left-radius: 6px;
		-moz-border-radius-topleft: 6px;
		border-top-left-radius: 6px;
		-webkit-border-top-right-radius: 6px;
		-moz-border-radius-topright: 6px;
		border-top-right-radius: 6px;
		-webkit-border-bottom-right-radius: 6px;
		-moz-border-radius-bottomright: 6px;
		border-bottom-right-radius: 6px;
		-webkit-border-bottom-left-radius: 6px;
		-moz-border-radius-bottomleft: 6px;
		border-bottom-left-radius: 6px;
		text-indent: 0px;
		border: 0;
		display: inline-block;
		color: #ffffff;
		font-size: 15px;
		font-weight: bold;
		font-style: normal;
		line-height: 30px;
		width: 45%;
		text-decoration: none;
		text-align: center;
		padding: 5px 0;
		margin-left: 5px;
	}

	#item_order_return .order_box .cancel {
		-webkit-appearance: none;
		border-radius: 0;
		background-color: #fff;
		-webkit-border-top-left-radius: 6px;
		-moz-border-radius-topleft: 6px;
		border-top-left-radius: 6px;
		-webkit-border-top-right-radius: 6px;
		-moz-border-radius-topright: 6px;
		border-top-right-radius: 6px;
		-webkit-border-bottom-right-radius: 6px;
		-moz-border-radius-bottomright: 6px;
		border-bottom-right-radius: 6px;
		-webkit-border-bottom-left-radius: 6px;
		-moz-border-radius-bottomleft: 6px;
		border-bottom-left-radius: 6px;
		text-indent: 0px;
		border: 1px solid #ddd;
		display: inline-block;
		color: #ddd;
		font-size: 15px;
		font-weight: bold;
		font-style: normal;
		line-height: 30px;
		width: 45%;
		text-decoration: none;
		text-align: center;
		padding: 5px 0;
		margin-right: 5px;
	}

	#item_order_return .order_box .item_payment_log { margin: 10px; padding-bottom: 20px; border-radius: 10px; font-family: 'NL2GR'; }
	#item_order_return .order_box .item_payment_log button { border: 1px solid #ccc; border-radius: 5px; background-color: #eee; height: 30px; padding: 0px 10px; }
	#item_order_return .order_box .item_payment_log input[type='checkbox'] { display: none; width: 0px; height: 0px; padding: 0px; margin: 0px; border: 0px; font-size: 0px; }
	#item_order_return .order_box .item_payment_log input[type='checkbox']+label { position: relative; display: inline-block; padding: 0px 10px 0px 35px; height: 30px; line-height: 35px; border: 1px solid #ccc; background-color: #eee; border-radius: 5px; font-size: 16px; }
	#item_order_return .order_box .item_payment_log input[type='checkbox']+label span { position: absolute; left: 5px; top: 5px; display: inline-block; border: 1px solid #ccc; width: 20px; height: 20px; background-color: #fff; }
	#item_order_return .order_box .item_payment_log input[type='checkbox']:checked+label { border: 1px solid #f5bf2e; background-color: #f5bf2e; color: #fff; }
	#item_order_return .order_box .item_payment_log input[type='checkbox']:checked+label span { border: 1px solid #f5bf2e; }
	#item_order_return .order_box .item_payment_log input[type='checkbox']:checked+label span:before { content: ''; display: inline-block; width: 10px; height: 5px; border-left: 5px solid #f5bf2e; border-bottom: 5px solid #f5bf2e; transform: rotate(-45deg); margin-left: 2px; margin-top: 4px; }
	#item_order_return .order_box .item_payment_log .title { position: relative; line-height: 20px; height: 40px; background-color: #ffe; }
	#item_order_return .order_box .item_payment_log .title .right { position: absolute; right: 0px; top: 0px; }
	#item_order_return .order_box .item_payment_log .title .right button { height: 40px; }
	#item_order_return .order_box .item_payment_log .item_payment_data { position: relative; padding-top: 10px; }
	#item_order_return .order_box .item_payment_log .item_payment_data .item_name { min-height: 42px; width: calc(100% - 70px); }
	#item_order_return .order_box .item_payment_log .item_payment_data .item_price { position: absolute; right: 0px; top: 10px; font-weight: Bold; }
	#item_order_return .order_box .item_payment_log .item_cart { padding: 1px 0px; background-color: #eee; }
	#item_order_return .order_box .item_payment_log .item_cart .item_cart_wrap { background-color: #fff; margin: 10px; }
	#item_order_return .order_box .item_payment_log .item_cart .item_cart_wrap .item_cart_data { position: relative; min-height: 110px; }
	#item_order_return .order_box .item_payment_log .item_cart .item_cart_wrap .item_cart_data .item_image { position: absolute; left: 10px; top: 10px; width: 90px; height: 90px; border-radius: 5px; background-size: cover; background-repeat: no-repeat; background-position: center; }
	#item_order_return .order_box .item_payment_log .item_cart .item_cart_wrap .item_cart_data .item_detail { margin-left: 110px; min-height: 100px; padding-top: 10px; }
	#item_order_return .order_box .item_payment_log .item_cart .item_cart_wrap .item_cart_data .item_detail>div { padding-bottom: 5px; }
	#item_order_return .order_box .item_payment_log .item_cart .item_cart_wrap .item_cart_data .item_detail .item_price { font-weight: Bold; }
	#item_order_return .order_box .item_payment_log .item_cart .item_cart_wrap .btn_wrap { text-align: right; padding: 10px; }
</style>

<div class="top_menu">
	<!--div class="header-back-btn"><a href="javascript:;"><img src="/pet/images/btn_back_2.png" width="26px"></a></div-->
	<div class="top_title">
		<p>반품요청</p>
	</div>
</div>

<div id="item_order_return">
	<div class="order_box">
		<div class="order_box_title">1.반품상품 선택</div>
		<div class="order_box_text">
			<div style="margin:20px 0;text-align:center;">반품 상품을 선택해 주세요.</div>
			<table width="100%"class="order_table">
				<colgroup>
					<col width="5%">
					<col width="30%">
					<col width="65%">
				</colgroup>
				<tbody class="item_payment_log_wrap">
					<!--tr style="border:1px solid #ddd;">
						<td style="padding:10px 5px;"><input type="checkbox" id="chk_ag" name="chk_ag" value=""></td>
						<td style="padding:10px 0;"><div class="product_image"></div></td>
						<td style="padding:10px;">
							<p style="font-size:12px;">반짝 기능성 펫타올 S size</p>
							<p style="font-size:12px;"> 1 / 9,800원</p>
						</td>
					</tr>
					<tr style="border:1px solid #ddd;">
						<td style="padding:10px 5px;"><input type="checkbox" id="chk_ag" name="chk_ag" value=""></td>
						<td style="padding:10px 0;"><div class="product_image"></div></td>
						<td style="padding:10px;">
							<p style="font-size:12px;">반짝 기능성 펫타올 L size</p>
							<p style="font-size:12px;"> 1 / 9,800원</p>
						</td>
					</tr-->
				</tbody>
			</table>
			<div style="text-align:center;margin-top:20px;"><a href="javascript:;" class="cancel">취소하기</a><a href="javascript:;" class="next">다음단계</a></div>
		</div>
	</div>
</div>

<script>
	var no = "<?=$r_no ?>";
	var chk_list = ("<?=$_SESSION['RNC_CHKLIST'] ?>" != "")? (("<?=$_SESSION['RNC_CHKLIST'] ?>".indexOf(','))? "<?=$_SESSION['RNC_CHKLIST'] ?>".split(',') : ["<?=$_SESSION['RNC_CHKLIST'] ?>"]) : [];
	
	$(function(){
		get_item_payment_log()
			.then(get_item_payment_log_product)
			.then(chk_val);
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
					var html = '';
					
					console.log($.parseJSON(data.data[0].pay_data));
					$.each($.parseJSON(data.data[0].pay_data), function(i, v){
						html += '<tr style="border:1px solid #ddd;">';
						html += '	<td style="padding:10px 5px;"><input type="checkbox" id="chk_ag'+i+'" name="chk_ag[]" value="'+v.seq+'" /></td>';
						html += '	<td style="padding:10px 0;"><div class="product_image"></div></td>';
						html += '	<td style="padding:10px;">';
						html += '		<p style="font-size:14px;">'+v.txt+'</p>';
						html += '		<p style="font-size:14px;">'+v.amount+'/ '+v.value+'원</p>';
						html += '	</td>';
						html += '</tr>';
					});
					$("#item_order_return .item_payment_log_wrap").html('').html(html);

					
					// img_loading
					$.ajax({
						url: '<?=$mainpage_directory ?>/fileupload_ajax.php',
						data: {
							mode : "get_file_list",
							file_list: data.data[0].product_img
						},
						type: 'POST',
						dataType: 'JSON',
						success: function(data) {
							if(data.code == "000000"){
								console.log(data.data); 
								var html = '';
								html += '<img src="'+data.data[0].file_path+'" style="width:100%;">';

								$("#item_order_return .product_image").html("").html(html).css("background-image", "none");
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

	$(document).on("click", "#item_order_return .item_payment_log_wrap tr", function(){
		$(this).find("input[name='chk_ag[]']").prop("checked", true);
	});

	$(document).on("click", ".header-back-btn", function(){
		window.history.back();
	});

	$(document).on("click", ".next", function(){
		chk_list = [];
		$.each($("#item_order_return input[type='checkbox']"), function(i, v){
			var _this = $(this);
			console.log(_this.is(":checked"), _this.val());
			if($(this).is(":checked") == true){
				chk_list.push(_this.val());
			}
		});
		chk_list = chk_list.join(",");

		if(chk_list != ""){
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "set_update_item_return_n_cancel_step1",
					action_type : "return",
					chk_list : chk_list,
					order_num : no
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data); 
						location.href = "<?=$item_directory ?>/item_order_return_reason.php?no="+no;
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
			$.MessageBox("반품할 상품을 선택하세요.");
		}
	});

	$(document).on("click", ".cancel", function(){
		$.MessageBox({
			buttonDone  : "확인",
			buttonFail  : "취소",
			message     : "반품 신청을 취소 하시겠습니까?"
		}).done(function(data, button){
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "set_update_item_return_n_cancel_to_cancel"
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data); 
						window.history.back();
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
							var pay_dt = (v.pay_dt != null)? new Date(v.pay_dt) : "";
							pay_dt = (pay_dt != "")? pay_dt.getFullYear()+'-'+fillZero(2, (pay_dt.getMonth()+1))+'-'+fillZero(2, pay_dt.getDate()) : ((v.pay_dt == null && v.pay_type == "2")? "입금 대기중" : "결제 대기중");
							var color_red = (v.pay_status == 7 || v.pay_status == 8 || v.pay_status == 9)? "red" : "";
							var pay_data_list = (v.pay_data && v.pay_data.indexOf('||') != -1)? v.pay_data.split('||') : [v.pay_data];

							html += '<div class="item_payment_log">';
							//html += '	<div class="title">';
							//html += '		<div class="order_data '+color_red+'">'+v.order_num+'<br/>'+pay_dt+'</div>';
							//html += '		<div class="right">';
							//html += '			<button class="detail_btn" data-no="'+v.order_num+'">자세히 보기 <i class="fas fa-angle-right"></i></button>';
							//html += '		</div>';
							//html += '	</div>';
							html += '	<div class="item_payment_data">';
							html += '		<div class="item_detail">';
							html += '			<div class="item_name">'+v.product_name+'</div>';
							html += '			<div class="item_price">'+v.total_price.format()+'원</div>';
							html += '		</div>';
							html += '	</div>';
							html += '	<div class="item_cart">';
							//$.each(pay_data_list, function(i2, v2){
							//	html += '	<div class="item_cart_wrap" data-id="'+v.ip_log_seq+'" data-item_no="'+i2+'"></div>';
							//});
							html += '	</div>';
							html += '</div>';
							
							idx++;
						});

						if(idx == 0){
							html += '<div>';
							html += '	<div class="no_data">구매한 내역이 없습니다.</div>';
							html += '</div>';
						}

						$("#item_order_return .item_payment_log_wrap").html(html);

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

	function get_item_payment_log_product(post_data){
		return new Promise(function(resolve, reject) {
			console.log(post_data);
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
								var _option_data = $.parseJSON(v.option_data.replace(/\\/g, ''));
								var _chk_val = v.iplp_seq+'_'+i;
								
								if(v.pay_status == "4"){ // 결제 완료된 상품만 반품 가능
									html += '<div class="item_cart_wrap" data-id="'+v.iplp_seq+'" data-item_no="'+i+'">';
									html += '	<div class="item_cart_data">';
									html += '		<div class="item_image" style=" background-image: url(\'/pet/images/product_img.png\');"></div>';
									html += '		<div class="item_detail">';
									html += '			<div class="item_chk">';
									html += '				<input type="checkbox" id="chk_ag'+v.iplp_seq+''+i+'" name="chk_ag[]" value="'+_chk_val+'" />';
									html += '				<label for="chk_ag'+v.iplp_seq+''+i+'"><span></span>반품</label>';
									html += '			</div>';
									$.each(_option_data, function(i2, v2){
										console.log(v2);
										var item_name = (v2.seq && v2.seq != "")? ((post_data && post_data.length > 0)? post_data[0].product_name+' / '+v2.txt : v2.txt) : v2.txt;
										//var chk_val = (v2.seq && v2.seq != "")? v2.seq : "I"+v2.il_seq;
										html += '			<div class="item_name">'+item_name+'</div>';
										html += '			<div class="item_price">'+v2.value.format()+'원 / '+v2.amount+'개</div>';
									});
									//if(v.shipping_invoice && v.shipping_invoice != ""){
									//	html += '			<div class="shipping_invoice">['+v.shipping_company+']'+v.shipping_invoice+'</div>';
									//}
									html += '		</div>';
									html += '	</div>';
									html += '</div>';
								}
							});

							$("#item_order_return .item_payment_log_wrap .item_cart").append(html);

							$.each(data.data, function(i, v){
								$("#item_order_return .item_payment_log_wrap .item_cart_wrap[data-id='"+v.iplp_seq+"'][data-item_no='"+i+"'] .item_cart_data").attr('data-id', v.product_no);
								get_item_image(".item_cart_wrap[data-id='"+v.iplp_seq+"'][data-item_no='"+i+"'] .item_cart_data", v.product_no);
							});
						}else{

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

	function chk_val(){
		return new Promise(function(resolve, reject) {
			console.log(chk_list);
			if(chk_list.length > 0){
				$.each(chk_list, function(i, v){
					$.each($("#item_order_return input[type='checkbox']"), function(i2, v2){
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
			
			html += '<div class="item_cart_data">';
			html += '	<div class="item_image" style=" background-image: url(\'/pet/images/product_img.png\');"></div>';
			html += '	<div class="item_detail">';
			$.each(pay_data, function(i, v){
				console.log(v);
				var item_name = (v.seq && v.seq != "")? product_name+' / '+v.txt : v.txt;
				var chk_val = (v.seq && v.seq != "")? v.seq : "I"+v.il_seq;
				html += '		<div class="item_chk">';
				html += '			<input type="checkbox" id="chk_ag'+ip_log_seq+''+target+''+i+'" name="chk_ag[]" value="'+chk_val+'" />';
				html += '			<label for="chk_ag'+ip_log_seq+''+target+''+i+'"><span></span>반품</label>';
				html += '		</div>';
				html += '		<div class="item_name">'+item_name+'</div>';
				html += '		<div class="item_price">'+v.value.format()+'원 / '+v.amount+'개</div>';
			});
			if(shipping_invoice != ""){
				html += '		<div class="shipping_invoice">['+shipping_company+']'+shipping_invoice+'</div>';
			}
			html += '	</div>';

			$("#item_order_return .item_payment_log_wrap .item_cart_wrap[data-id='"+ip_log_seq+"'][data-item_no='"+target+"']").append(html);

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
									$("#item_order_return .item_payment_log_wrap .item_cart_wrap[data-id='"+ip_log_seq+"'][data-item_no='"+target+"'] .item_cart_data").attr('data-id', v.product_no);
									get_item_image('.item_cart_wrap[data-id="'+ip_log_seq+'"][data-item_no="'+target+'"] .item_cart_data', v.product_no);
								});

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
										$("#item_order_return .item_payment_log_wrap").find(target+"[data-id='"+product_no+"'] .item_image").css("background-image", "url('"+v.file_path+"')");
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
					$("#item_order_return .item_payment_log_wrap").find(target+"[data-id='"+product_no+"'] .item_image").css("background-image", "url('"+goodsRepImage+"')");
				}else{
					$("#item_order_return .item_payment_log_wrap").find(target+"[data-id='"+product_no+"'] .item_image").css("background-image", "url('../images/product_img.png')");
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