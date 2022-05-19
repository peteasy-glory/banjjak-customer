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
	#item_order_list { position: relative; width: 100%; margin-top: 60px; font-size: 16px; font-family: 'NL2GR'; font-weight: normal; }
	#item_order_list button { height: 30px; padding: 0px 10px; font-size: 14px; background-color: #eee; font-family: 'NL2GR'; border: 1px solid #ccc; border-radius: 5px; }
	#item_order_list .item_order_list { width: calc(100% - 20px); margin: 70px auto 10px; }
	#item_order_list .item_order_list>div:first-child { height: 30px; line-height: 30px; margin: 10px 0px; padding: 0px 10px; background-color: #eee; border-radius: 5px; font-size: 12px; }
	#item_order_list .item_order_list_wrap { background-color: #fff; border-radius: 5px; margin: 10px 0px; }
	#item_order_list .item_order_list_wrap .item_order>div { position: relative; background-color: #f3f3f3; margin: 10px 0px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
	#item_order_list .item_order_list_wrap .item_order>div .item_order_wrap>div:first-child { font-size: 12px; color: #ad1d15; }
	#item_order_list .item_order_list_wrap .item_order>div .right_menu { position: absolute; right: 10px; top: 10px; }
	#item_order_list .item_order_list_wrap .item_order>div .right_menu .move_item_order_detail_btn { display: inline-block; height: 30px; line-height: 30px; font-size: 12px; text-decoration: underline; }
	#item_order_list .item_order_list_wrap .item_order>div ul.table { display: table; width: 100%; font-size: 14px; margin: 10px 0px; }
	#item_order_list .item_order_list_wrap .item_order>div ul.table li { display: table-cell; }
	#item_order_list .item_order_list_wrap .item_order>div ul.table li:nth-child(1) { width: calc(100% - 120px); }
	#item_order_list .item_order_list_wrap .item_order>div ul.table li:nth-child(2) { text-align: right; font-weight: Bold; }
	#item_order_list .item_order_list_wrap .item_order>div .item_cart_data { position: relative; min-height: 110px; background-color: #fff; margin-top: 10px; font-size: 14px; }
	#item_order_list .item_order_list_wrap .item_order>div .item_cart_data .item_image { position: absolute; left: 10px; top: 10px; width: 90px; height: 90px; border-radius: 5px; border:2px solid #fff; background-size: cover; background-repeat: no-repeat; background-position: center; }
	#item_order_list .item_order_list_wrap .item_order>div .item_cart_data .item_pay_status { font-size: 16px; }
	#item_order_list .item_order_list_wrap .item_order>div .item_cart_data .item_detail { margin-left: 100px; min-height: 100px; padding: 10px; }
	#item_order_list .item_order_list_wrap .item_order>div .item_cart_data .item_detail>div { padding-bottom: 5px; }
	#item_order_list .item_order_list_wrap .item_order>div .item_cart_data .item_detail .item_name { font-size: 12px; }
	#item_order_list .item_order_list_wrap .item_order>div .item_cart_data .item_detail .item_price { font-weight: Bold; }
	#item_order_list .item_order_list_wrap .item_order>div .item_cart_data_old { position: relative; min-height: 110px; background-color: #fff; margin-top: 10px; font-size: 14px; }
	#item_order_list .item_order_list_wrap .item_order>div .item_cart_data_old .item_image { position: absolute; left: 10px; top: 10px; width: 90px; height: 90px; border-radius: 5px; border:2px solid #fff; background-size: cover; background-repeat: no-repeat; background-position: center; }
	#item_order_list .item_order_list_wrap .item_order>div .item_cart_data_old .item_pay_status { font-size: 16px; }
	#item_order_list .item_order_list_wrap .item_order>div .item_cart_data_old .item_detail { margin-left: 100px; min-height: 100px; padding: 10px; }
	#item_order_list .item_order_list_wrap .item_order>div .item_cart_data_old .item_detail>div { padding-bottom: 5px; }
	#item_order_list .item_order_list_wrap .item_order>div .item_cart_data_old .item_detail .item_name { font-size: 12px; }
	#item_order_list .item_order_list_wrap .item_order>div .item_cart_data_old .item_detail .item_price { font-weight: Bold; }
	#item_order_list .item_order_list_wrap .item_order>div .btn_wrap { text-align: right; padding: 10px; }
	#item_order_list .item_order_list_wrap .item_order>div .btn_wrap button.on { background-color: #fff; border: 1px solid #f5bf2e; color: #f5bf2e; }
</style>

<div class="top_menu">
	<div class="top_back"><a href="<?= $mainpage_directory ?>/mainpage_my_menu.php"><img src="/pet/images/btn_back_2.png" width="26px"></a></div>
	<div class="top_title">
		<p>상품 결제 내역</p>
	</div>
</div>
<div id="item_order_list"></div>

<script>
var $item_order_list = $('#item_order_list');
var customer_id = "<?=$user_id ?>";
var item_list = [];
var pay_status_arr = $.parseJSON('<?=json_encode($pay_status_arr)?>');
var pay_status_arr_old = $.parseJSON('<?=json_encode($pay_status_arr_old)?>');
var order_status_arr = $.parseJSON('<?=json_encode($order_status_arr)?>');

$(function(){
	init_html()
		.then(get_item_payment_cnt)
		.then(get_item_payment_cancel_cnt)
		.then(get_item_payment_log_list)
		.then(get_item_payment_log_product);
});

$item_order_list.on('click', '.move_item_order_detail_btn', function(){
	var no = $(this).data("no");
	location.href = "<?=$item_directory ?>/item_order_detail.php?no="+no;
});

$item_order_list.on("click", ".return_please_btn", function(){ // 반품요청
	var no = $(this).data("no");
	location.href = "<?=$item_directory ?>/item_order_return_list.php?no="+no;
});

$item_order_list.on("click", ".shipping_done_btn", function(){ // 수취확인
	var no = $(this).data("no");
	$.MessageBox({
		buttonDone  : "확인",
		buttonFail  : "취소",
		message     : "수취 확인 하시겠습니까?"
	}).done(function(data, button){
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "set_update_product_pay_status",
				pay_status: "4",
				order_num: no
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data); 
					console.log(data.sql); 
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

$item_order_list.on("click", ".cancel_btn", function(){ // 구매취소
	var no = $(this).data("no");
	location.href = "<?=$item_directory ?>/item_order_cancel_list.php?no="+no;
});

$item_order_list.on("click", ".review_btn", function(){ // 리뷰작성
	var no = $(this).data("no");
	location.href = "<?=$mainpage_directory?>/item_review_write.php?no="+no+"&backurl="+encodeURIComponent(window.location.pathname);
});

$item_order_list.on("click", ".quick_search_btn", function(){ // 배송조회
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

function init_html(){
	return new Promise(function(resolve, reject) {
		var html = '';

		html += '<div class="item_order_list">';
		html += '	<div>주문 <span class="total_cnt">0건</span> / 반품(취소) <span class="cancel_cnt">0건</span></div>';
		html += '	<div class="item_order_list_wrap">';
		html += '	<div>';
		html += '</div>';
		$item_order_list.html(html);
		resolve();
	});
}

function get_item_payment_cnt(){
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_item_payment_cnt",
			customer_id : customer_id
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				console.log(data.sql);
				$item_order_list.find(".total_cnt").text(data.data+"건");
			}else{
				alert(data.data+"("+data.code+")");
				console.log(data.code);
			}
		},
		error: function(xhr, status, error) {
			alert(error + "네트워크에러");
		}
	});
}

function get_item_payment_cancel_cnt(){
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_item_payment_cancel_cnt",
			customer_id : customer_id
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				//console.log(data.data);
				$item_order_list.find(".cancel_cnt").text(data.data+"건");
			}else{
				alert(data.data+"("+data.code+")");
				console.log(data.code);
			}
		},
		error: function(xhr, status, error) {
			alert(error + "네트워크에러");
		}
	});
}

// 상품 주문 리스트
function get_item_payment_log_list(){
	return new Promise(function(resolve, reject) {
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "get_item_payment_log",
				customer_id: customer_id
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data); 
					var html = '';

					if(data.data && data.data.length > 0){
						html += '<div class="item_order">';
						$.each(data.data, function(i, v){
							if(v.order_status == '2' || v.order_status == '3' || v.order_status == '8' || v.order_status == '9'){
								let _pay_dt = (v.pay_dt && typeof v.pay_dt != "undefined" && v.pay_dt != "")? new Date(v.pay_dt.replace(/-/gi, '/')) : "";
								_pay_dt = (_pay_dt != "")? _pay_dt.getFullYear()+'-'+fillZero(2, (_pay_dt.getMonth()+1))+'-'+fillZero(2, _pay_dt.getDate()) : "";
								html += '	<div>';
								html += '		<div class="item_order_wrap">';
								html += '			<div>'+v.order_num+'<br/>['+order_status_arr[v.order_status]+'] '+_pay_dt+'</div>';
								html += '			<div>';
								html += '				<ul class="table">';
								html += '					<li>'+v.product_name+'</li>';
								html += '					<li>'+v.total_price.format()+'원</li>';
								html += '				</ul>';
								html += '			</div>';
								html += '			<div class="right_menu"><span class="move_item_order_detail_btn" data-no="'+v.order_num+'">자세히보기 <i class="fas fa-chevron-right"></i></span></div>';
								html += '		</div>';
								html += '		<div class="item_option_list" data-order_num="'+v.order_num+'">';
								html += '		</div>';
								html += '	</div>';
							}
						});
						html += '</div>';
					}

					$item_order_list.find('.item_order_list_wrap').append(html);
					resolve(data.data);
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

function get_item_payment_log_product(post_data){
	return new Promise(function(resolve, reject) {
		if(post_data && Object.keys(post_data).length > 0){
			$.each(post_data, function(i, v){
				$.ajax({
					url: '<?=$item_directory ?>/item_list_ajax.php',
					data: {
						mode : "get_item_payment_log_product",
						order_num: v.order_num
					},
					type: 'POST',
					dataType: 'JSON',
					success: function(data) {
						if(data.code == "000000"){
							//console.log(data.data); 
							//post_data.product.push(data.data);
							var html = '';

							if(data.data && data.data.length > 0){
								$.each(data.data, function(i2, v2){
									var pay_data = $.parseJSON(v2.option_data.replace(/\\/g, ''));

									html += '<div class="item_cart_data" data-id="'+v2.product_no+'" data-iplp_seq="'+v2.iplp_seq+'">';
									html += '	<div class="item_image" style=" background-image: url(\'/pet/images/product_img.png\');"></div>';
									html += '	<div class="item_detail" data-no="'+i2+'">';
									html += '		<div class="item_pay_status" data-pay_status="'+v2.pay_status+'">'+pay_status_arr[v2.pay_status]+'</div>';
									$.each(pay_data, function(i3, v3){
										html += '		<div class="item_name">'+v3.txt+'</div>';
										html += '		<div class="item_price">'+v3.value.format()+'원 x '+v3.amount+'개</div>';
									});
									html += '	</div>';
									html += '</div>';
								});

								$item_order_list.find('.item_order_list_wrap .item_option_list[data-order_num="'+v.order_num+'"]').append(html);

								$.each(data.data, function(i2, v2){
									//get_item(v.order_num, v2.iplp_seq, data.data);
									get_item_image('.item_order_list_wrap .item_option_list[data-order_num="'+v.order_num+'"] .item_cart_data', v2.product_no);
								});
							}else{
								console.log("!@#!@#");
								// tb_item_payment_log_product 없는 경우 표시
								let _pay_data_list = (v.pay_data && v.pay_data != "")? ((v.pay_data.indexOf('||') != -1)? v.pay_data.split('||') : [v.pay_data]) : [];

								if(_pay_data_list.length > 0){
									$.each(_pay_data_list, function(i2, v2){
										let _pay_data = (v2 && v2 != "")? $.parseJSON(v2.replace(/\\/g, '')) : [];
										html += '<div class="item_cart_data_old">';
										html += '	<div class="item_image" style=" background-image: url(\'/pet/images/product_img.png\');"></div>';
										html += '	<div class="item_detail">';
										html += '		<div class="item_pay_status" data-pay_status="'+v.pay_status+'">'+pay_status_arr_old[v.pay_status]+'</div>';

										if(_pay_data.length > 0){
											$.each(_pay_data, function(i3, v3){
												html += '		<div class="item_name">'+v3.txt+'</div>';
												html += '		<div class="item_price">'+v3.value.format()+'원 x '+v3.amount+'개</div>';
											});
										}
										html += '	</div>';
										html += '</div>';
									});
								}

								$item_order_list.find('.item_order_list_wrap .item_option_list[data-order_num="'+v.order_num+'"]').append(html);

								if(_pay_data_list.length > 0){
									$.each(_pay_data_list, function(i2, v2){
										let _pay_data = (v2 && v2 != "")? $.parseJSON(v2.replace(/\\/g, '')) : [];
										if(_pay_data.length > 0){
											$.each(_pay_data, function(i3, v3){
												get_item_option(v.order_num, v3);
											});
										}
									});
								}
							}

							resolve({order_num: v.order_num, data: post_data});
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
	});
}

function get_item(order_num, iplp_seq, post_data){
	return new Promise(function(resolve, reject) {
		console.log(order_num, iplp_seq, post_data);
		
		$.each(post_data, function(i, v){
			if(v.iplp_seq == iplp_seq){
				var pay_data = $.parseJSON(v.option_data.replace(/\\/g, ''));

				var html = '';
				$.each(pay_data, function(i2, v2){
						html += '		<div class="item_name">'+v2.txt+'</div>';
						html += '		<div class="item_price">'+v2.value.format()+'원 x '+v2.amount+'개</div>';
				});
			}
			
			$item_order_list.find('.item_order_list_wrap .item_option_list[data-order_num="'+order_num+'"] .item_cart_data[data-iplp_seq="'+iplp_seq+'"] .item_detail').append(html);
			get_item_image('.item_order_list_wrap .item_option_list[data-order_num="'+order_num+'"] .item_cart_data[data-iplp_seq="'+iplp_seq+'"]', post_data[0].product_no);
		});

	});
}

/*

// 상품 주문 리스트
function get_item_payment_log_list(){
	return new Promise(function(resolve, reject) {
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "get_item_payment_log",
				customer_id: customer_id
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					//console.log(data.data); 
					var html = '';

					if(data.data && data.data.length > 0){
						html += '<div class="item_order">';
						$.each(data.data, function(i, v){
							//if(v.order_status == '2' || v.order_status == '3' || v.order_status == '8' || v.order_status == '9'){
								let _pay_dt = (v.pay_dt && typeof v.pay_dt != "undefined" && v.pay_dt != "")? new Date(v.pay_dt.replace(/-/gi, '/')) : "";
								_pay_dt = (_pay_dt != "")? _pay_dt.getFullYear()+'-'+fillZero(2, (_pay_dt.getMonth()+1))+'-'+fillZero(2, _pay_dt.getDate()) : "";
								html += '	<div>';
								html += '		<div class="item_order_wrap">';
								html += '			<div>'+v.order_num+'<br/>['+order_status_arr[v.order_status]+'] '+_pay_dt+'</div>';
								html += '			<div>';
								html += '				<ul class="table">';
								html += '					<li>'+v.product_name+'</li>';
								html += '					<li>'+v.total_price.format()+'원</li>';
								html += '				</ul>';
								html += '			</div>';
								html += '			<div class="right_menu"><span class="move_item_order_detail_btn" data-no="'+v.order_num+'">자세히보기 <i class="fas fa-chevron-right"></i></span></div>';
								html += '		</div>';
								html += '		<div class="item_option_list" data-order_num="'+v.order_num+'">';
								html += '		</div>';
								html += '	</div>';
							//}
						});
						html += '</div>';
					}

					$item_order_list.find('.item_order_list_wrap').append(html);

					if(data.data && data.data.length > 0){
						let p = $.when();
						let c = 0;
						$.each(data.data, function(i, v){
							p = p.then(function(){
								c++;
								//console.log("1111111");
								return get_item_payment_log_product(v.order_num);
							}).done(function(){
								if(c == data.data.length){
									console.log("!get_item_payment_log_list_end");
									resolve();
								}
							});
						});
					}else{
						resolve();
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

// 상품 주문 제품 리스트
function get_item_payment_log_product(order_num){
	return new Promise(function(resolve, reject) {
		var cnt = 0;

		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "get_item_payment_log_product",
				order_num: order_num
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					//console.log(data.data); 
					var html = '';

					if(data.data && data.data.length > 0){
						$.each(data.data, function(i, v){
							html += '<div class="item_cart_data" data-id="'+v.iplp_seq+'">';
							html += '	<div class="item_image" style=" background-image: url(\'/pet/images/product_img.png\');"></div>';
							html += '	<div class="item_detail">';
							html += '		<div class="item_pay_status" data-pay_status="'+v.pay_status+'">'+pay_status_arr[v.pay_status]+'</div>';
							html += '	</div>';
							html += '</div>';
						});
					}

					$item_order_list.find('.item_order_list_wrap .item_option_list[data-order_num="'+order_num+'"]').append(html);

					if(data.data && data.data.length > 0){
						let p = $.when();
						let c = 0;

						$.each(data.data, function(i, v){
							var pay_data_list = (v.option_data && v.option_data.indexOf('||') != -1)? v.option_data.split('||') : [v.option_data];

							p = p.then(function(){
								c++;
								return get_item(v.iplp_seq, order_num, pay_data_list);
							}).done(function(){
								if(c == data.data.length){
									console.log("!get_item_payment_log_product_end");
									resolve();
								}
							});
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

function get_item(iplp_seq, order_num, pay_data_list){
	return new Promise(function(resolve, reject) {
		//console.log("!get_item", iplp_seq, order_num, pay_data_list);

		$.each(pay_data_list, function(i, v){
			var pay_data = $.parseJSON(v.replace(/\\/g, ''));
			let p = $.when();
			let c = 0;
			
			$.each(pay_data, function(i, v){
				p = p.then(function(){
					c++;
					return get_item_option(iplp_seq, order_num, v);
				}).done(function(){
					if(c == pay_data.length){
						console.log("!get_item_end");
						resolve();
					}
				});
			});
		});
	});
}

*/

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
								$('.item_order_list_wrap .item_option_list[data-order_num="'+order_num+'"] .item_cart_data_old').attr('data-id', v.product_no);
								get_item_image('.item_order_list_wrap .item_option_list[data-order_num="'+order_num+'"] .item_cart_data_old', v.product_no);
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
									$item_order_list.find(target+"[data-id='"+product_no+"'] .item_image").css("background-image", "url('"+v.file_path+"')");
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
				$item_order_list.find(target+"[data-id='"+product_no+"'] .item_image").css("background-image", "url('"+goodsRepImage+"')");
			}else{
				$item_order_list.find(target+"[data-id='"+product_no+"'] .item_image").css("background-image", "url('../images/product_img.png')");
			}
		}
	});
}

// 상품 상세 리스트
		/*
function get_item(){
	return new Promise(function(resolve, reject) {
		resolve();
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "get_item",
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
					console.log(data.data);
				}
			},
			error: function(xhr, status, error) {
				alert(error + "네트워크에러");
			}
		});
	});
}
		*/

// 상품 이미지 가져오기
function get_item_file_list(){
	return new Promise(function(resolve, reject) {
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "get_item",
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
					console.log(data.data);
				}
			},
			error: function(xhr, status, error) {
				alert(error + "네트워크에러");
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

//남는 길이만큼 0으로 채움
function fillZero(width, str){
	var str = String(str);//문자열 변환
	return str.length >= width ? str:new Array(width-str.length+1).join('0')+str;
}
</script>