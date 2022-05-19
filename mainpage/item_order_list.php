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
		#item_order_list .order_box .order_table tbody tr td .cancel_btn { margin: 0px 0px 5px 0px; width: 48%; height: 32px; line-height: 20px; border: 1px solid #ddd; border-radius: 5px; background-color: #fff; color: #ddd; font-size: 0.8rem; padding: 5px; }
	#item_order_list .order_box .order_table tbody tr td .quick_search_btn { margin: 0px 0px 5px 0px; width: 48%; height: 32px; line-height: 20px; border: 1px solid #f5bf2e; border-radius: 5px; background-color: #fff; color: #f5bf2e; font-size: 0.8rem; padding: 5px; }
	#item_order_list .order_box .order_table tbody tr td .shipping_done_btn { margin: 0px 0px 5px 0px; width: 48%; height: 32px; line-height: 20px; border: 1px solid #f5bf2e; border-radius: 5px; background-color: #fff; color: #f5bf2e; font-size: 0.8rem; padding: 5px; }
	#item_order_list .order_box .order_table tbody tr td .return_please_btn { margin: 0px 0px 5px 0px; width: 48%; height: 32px; line-height: 20px; border: 1px solid #ddd; border-radius: 5px; background-color: #fff; color: #ddd; font-size: 0.8rem; padding: 5px; }
	.red { color:#D51A3D; }
	.no_data { text-align: center; padding: 30px 0px; }
	.item_image { width: 90px; height: 90px; background-size: cover; background-repeat: no-repeat; background-position: center; }
	#item_order_list .order_box .item_payment_log { margin: 10px; padding-bottom: 20px; border-radius: 10px; font-family: 'NL2GR'; }
	#item_order_list .order_box .item_payment_log button { border: 1px solid #f5bf2e; border-radius: 5px; background-color: #f5bf2e; height: 30px; padding: 0px 10px; color:#fff;}
	#item_order_list .order_box .item_payment_log .title { position: relative; line-height: 20px; height: 40px; /*background-color: #ffe;*/ }
	#item_order_list .order_box .item_payment_log .title .order_data{border-bottom:3px dotted #fff;padding-bottom:5px;}
	#item_order_list .order_box .item_payment_log .title .right { position: absolute; right: 0px; top: 0px; }
	#item_order_list .order_box .item_payment_log .title .right button { height: 40px; background:#fff6d2; color:#f5bf2e;border:none; text-decoration:underline; outline:none;}
	#item_order_list .order_box .item_payment_log .item_payment_data { position: relative; padding-top: 10px;  }
	#item_order_list .order_box .item_payment_log .item_payment_data .item_price { position: absolute; right: 0px; top: 10px; font-weight: Bold; }
	#item_order_list .order_box .item_payment_log .item_payment_data .item_name{width:calc(100% - 70px); min-height:42px;} 
	#item_order_list .order_box .item_payment_log .item_cart { padding: 1px 0px; background-color: #fff; border-radius:10px; }
	#item_order_list .order_box .item_payment_log .item_cart .item_cart_wrap { background-color: #fff; margin: 2px;}
	#item_order_list .order_box .item_payment_log .item_cart .item_cart_wrap .item_cart_data { position: relative; min-height: 110px;/* background:#fff6d2;border:1px solid #fce985;*/}
	#item_order_list .order_box .item_payment_log .item_cart .item_cart_wrap .item_cart_data .item_image { position: absolute; left: 10px; top: 10px; width: 90px; height: 90px; border-radius: 5px; border:2px solid #fff;}
	#item_order_list .order_box .item_payment_log .item_cart .item_cart_wrap .item_cart_data .item_detail { margin-left: 110px; min-height: 100px; padding-top: 10px;  }
	#item_order_list .order_box .item_payment_log .item_cart .item_cart_wrap .item_cart_data .item_detail>div { padding-bottom: 5px; }
	#item_order_list .order_box .item_payment_log .item_cart .item_cart_wrap .item_cart_data .item_detail .item_price { font-weight: Bold; }
	#item_order_list .order_box .item_payment_log .item_cart .item_cart_wrap .btn_wrap { text-align: right; padding: 10px; }


#item_order_list .item_order_list_wrap { margin: 60px 0px; }
#item_order_list .move_item_special_mall_order_list_btn { background-color: #FFC000; color: #fff; width: calc(100% - 40px); margin: 5px auto; line-height: 40px; height: 40px; padding: 0px 10px; text-align: center; }
#item_order_list .order_box{font-size: 14px;border: 1px solid #e1e1e1;margin: 10px 12px;width: calc(100% - 24px);border-radius: 10px;overflow: hidden;}
#item_order_list .order_box .order_box_title{position: relative;border-radius: 10px 10px 0 0;margin: -3px 0 0 -3px;width: calc(100% - 14px);padding: 10px 12px;background-color: #f3f3f3;border-top: 1px solid #e1e1e1;font-size: 18px;color:#666;}
#item_order_list .order_box .order_box_text{width:95%;margin:10px auto;border:1px solid #ddd;padding: 10px;box-sizing: border-box;}
#item_order_list .order_box .order_table{width:100%;}
#item_order_list .order_box .order_table tbody tr td .detail_btn{width: 48%;height: 32px;line-height: 20px;border: 1px solid #f5bf2e;border-radius: 5px;background-color: #f5bf2e;color: #fff;font-size: 0.8rem;padding: 5px;}
#item_order_list .order_box .order_table tbody tr td .cancel_btn { margin: 0px 0px 5px 0px; width: 48%; height: 32px; line-height: 20px; border: 1px solid #ddd; border-radius: 5px; background-color: #fff; color: #ddd; font-size: 0.8rem; padding: 5px; }
#item_order_list .order_box .order_table tbody tr td .quick_search_btn { margin: 0px 0px 5px 0px; width: 48%; height: 32px; line-height: 20px; border: 1px solid #f5bf2e; border-radius: 5px; background-color: #fff; color: #f5bf2e; font-size: 0.8rem; padding: 5px; }
#item_order_list .order_box .order_table tbody tr td .shipping_done_btn { margin: 0px 0px 5px 0px; width: 48%; height: 32px; line-height: 20px; border: 1px solid #f5bf2e; border-radius: 5px; background-color: #fff; color: #f5bf2e; font-size: 0.8rem; padding: 5px; }
#item_order_list .order_box .order_table tbody tr td .return_please_btn { margin: 0px 0px 5px 0px; width: 48%; height: 32px; line-height: 20px; border: 1px solid #ddd; border-radius: 5px; background-color: #fff; color: #ddd; font-size: 0.8rem; padding: 5px; }
#item_order_list .red { color:#ad1d15; font-family:"NL2GB"; font-size:16px;}
#item_order_list .no_data { text-align: center; padding: 30px 0px; }
#item_order_list .order_box .order_box_text td{font-family:"NL2GR";} 
.order_right{font-family:"NL2GR";}

.order_list_wrap{ margin:5px; border-radius:10px; background:#fff6d2;padding:6px;}
}t; padding: 10px; }
</style>
<div class="top_menu">
	<?php if($backurl){ ?>
		<div class="top_back"><a href="<?= $backurl ?>"><img src="/pet/images/btn_back_2.png" width="26px"></a></div>
	<?php }else{ ?>
		<div class="top_back"><a href="<?= $mainpage_directory ?>/mainpage_my_menu.php"><img src="/pet/images/btn_back_2.png" width="26px"></a></div>
	<?php } ?>
	<div class="top_title">
		<p>상품 결제 내역</p>
	</div>
</div>
<div id="item_order_list">
	<div class="item_order_list_wrap">
		<div class="order_box">
			<div class="order_box_title">주문 <span class="total_cnt">0건</span> / 반품(취소) <span class="cancel_cnt">0건</span></div>
			<div class="order_list_wrap">
			</div>
		</div>
	</div>
</div>

<script>
var customer_id = "<?=$user_id ?>";
var item_list = [];
var pay_status_arr = $.parseJSON('<?=json_encode($pay_status_arr)?>');

$(function(){
	get_item_payment_cnt();
	get_item_payment_cancel_cnt();
	get_item_payment_log();
	get_item_special_mall_log();
});

$(document).on("click", "#item_order_list .detail_btn", function(){
	var no = $(this).data("no");
	location.href = "<?=$mainpage_directory ?>/item_order_detail.php?no="+no+"&backurl="+encodeURIComponent(window.location.pathname+"?backurl=<?=$backurl ?>");
});

$(document).on("click", "#item_order_list .return_please_btn", function(){
	var no = $(this).data("no");
	location.href = "<?=$item_directory ?>/item_order_return_list.php?no="+no+"&backurl="+encodeURIComponent(window.location.pathname+"?backurl=<?=$backurl ?>");
});

$(document).on("click", "#item_order_list .review_btn", function(){
	var no = $(this).data("no");
	location.href = "<?=$mainpage_directory ?>/item_review_write.php?no="+no+"&backurl="+encodeURIComponent(window.location.pathname+"?backurl=<?=$backurl ?>");
});

$(document).on("click", "#item_order_list .shipping_done_btn", function(){
	var no = $(this).data("no");
	$.MessageBox({
		buttonDone  : "확인",
		buttonFail  : "취소",
		message     : "수취 확인 하시겠습니까?"
	}).done(function(data, button){
		$.ajax({
			url: '<?=$mainpage_directory?>/item_list_ajax.php',
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
				//alert(error + "네트워크에러");
				if(xhr.status != 0){
					alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
				}
			}
		});
	}).fail(function(data, button){
	});
});

$(document).on("click", "#item_order_list .cancel_btn", function(){
	var jbOrdNo = $(this).data("jbordno");
	if(jbOrdNo && jbOrdNo != ""){
		get_jbook_order($(this).data("no")); // 정글북 출고 체크
	}else{
		location.href = "<?=$item_directory ?>/item_order_cancel_list.php?no="+$(this).data("no")+"&backurl="+encodeURIComponent(window.location.pathname+"?backurl=<?=$backurl ?>");
	}
});

$(document).on("click", "#item_order_list .quick_search_btn", function(){
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
		html += '<form id="send_shipping_data" method="post" action="https://m.hanex.hanjin.co.kr/inquiry/incoming/resultWaybill">';
		html += '	<input type="text" id="div" name="div" value="B" />';
		html += '	<input type="text" id="show" name="show" value="true" />';
		html += '	<input type="text" id="wblNum" name="wblNum" value="'+shipping_invoice+'" />';
		html += '</form>';
		$(document).find("#item_order_list").after(html);
		$(document).find("#send_shipping_data").submit();
	}
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

function get_item_payment_log(){
	return new Promise(function(resolve, reject) {
		$.ajax({
			type: 'post',
			url: "<?=$item_directory ?>/item_list_ajax.php",
			data: {
				mode: "get_item_payment_log",
				customer_id: customer_id,
				is_shop: "2"
			},
			dataType: 'json',
			success: function(json) {
				if(json.code == "000000"){
					console.log(json.data);
					var html = '';
					var idx = 0;
				
					$.each(json.data, function(i, v){
						var pay_dt = (v.pay_dt != null)? new Date(v.pay_dt.replace(/-/gi, '/')) : "";
						pay_dt = (pay_dt != "")? pay_dt.getFullYear()+'-'+fillZero(2, (pay_dt.getMonth()+1))+'-'+fillZero(2, pay_dt.getDate()) : ((v.pay_dt == null && v.pay_type == "2")? "입금 대기중" : "결제 대기중");
						var color_red = (v.pay_status == 7 || v.pay_status == 8 || v.pay_status == 9)? "red" : "";
						var pay_data_list = (v.pay_data && v.pay_data.indexOf('||') != -1)? v.pay_data.split('||') : [v.pay_data];
						
						if(v.pay_status != 1){ // 진행중은 미표시
							html += '<div class="item_payment_log">';
							html += '	<div class="title">';
							html += '		<div class="order_data '+color_red+'">'+v.order_num+'<br/>'+pay_dt+'</div>';
							html += '		<div class="right">';
							html += '			<button class="detail_btn" data-no="'+v.order_num+'">자세히 보기 <i class="fas fa-angle-right"></i></button>';
							html += '		</div>';
							html += '	</div>';
							html += '	<div class="item_payment_data">';
							html += '		<div class="item_detail">';
							html += '			<div class="item_name">'+v.product_name+'</div>';
							html += '			<div class="item_price">'+v.total_price.format()+'원</div>';
							html += '		</div>';
							html += '	</div>';
							html += '	<div class="item_cart">';
							$.each(pay_data_list, function(i2, v2){
								html += '	<div class="item_cart_wrap" data-id="'+v.ip_log_seq+'" data-item_no="'+i2+'"></div>';
							});
							html += '	</div>';
							html += '</div>';
							
							idx++;

						}
					});

					if(idx == 0){
						html += '<div>';
						html += '	<div class="no_data">구매한 내역이 없습니다.</div>';
						html += '</div>';
					}

					$("#item_order_list .order_list_wrap").html(html);

					$.each(json.data, function(i, v){ // 상품 리스트
						var pay_data_list = (v.pay_data && v.pay_data.indexOf('||') != -1)? v.pay_data.split('||') : [v.pay_data];
						var shipping_invoice = (v.pay_status == 4 && v.shipping_invoice != "" && v.shipping_company != "")? v.shipping_invoice : "";
						var shipping_company = (v.pay_status == 4 && v.shipping_invoice != "" && v.shipping_company != "")? v.shipping_company : "";
						var p = $.when();
						var c = 0;

						if(v.pay_status != 1){ // 진행중은 미표시
							$.each(pay_data_list, function(i2, v2){
								var pay_data = $.parseJSON(v2.replace(/\\/g, ''));

								p = p.then(function(){
									c++;
									if(c == pay_data.length){
										resolve();
									}
									return get_item(v.ip_log_seq, i2, pay_data, v.product_name, v.pay_status, shipping_invoice, shipping_company, v.order_num, v.jbOrdNo);
								});
							});
						}
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

$("#item_order_list").on("click", ".move_item_special_mall_order_list_btn", function(){
	location.href= "<?=$shop_directory ?>/item_special_mall_order_list.php?backurl="+encodeURIComponent(window.location.pathname);
});

function get_item_special_mall_log(){
	return new Promise(function(resolve, reject) {
		$.ajax({
			type: 'post',
			url: "<?=$item_directory ?>/item_list_ajax.php",
			data: {
				mode: "get_item_payment_log",
				customer_id: customer_id,
				is_shop: "1"
			},
			dataType: 'json',
			success: function(json) {
				if(json.code == "000000"){
					console.log(json.data);
					var html = '';

					if(json.data && json.data.length > 0){
						var special_mall_flag = 0;
						$.each(json.data, function(i, v){
							if(v.pay_status != "1"){
								special_mall_flag = 1;
							}
						});

						if(special_mall_flag == 1){
							$("#item_order_list .order_box").before("<div class='move_item_special_mall_order_list_btn'>반짝 전문몰 결제내역 보기 <i class='fas fa-chevron-right'></i> </div>");
						}
					}

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

function get_item(ip_log_seq, target, pay_data, product_name, pay_status, shipping_invoice, shipping_company, order_num, jbOrdNo){
	return new Promise(function(resolve, reject) {
		var cnt = 0;
		var html = '';
		var color_red = (pay_status == 7 || pay_status == 8 || pay_status == 9)? "red" : "";
		
		html += '<div class="item_cart_data">';
		html += '	<div class="item_image" style=" background-image: url(\'/pet/images/product_img.png\');"></div>';
		html += '	<div class="item_detail">';
			html += '		<div class="item_pay_status '+color_red+'">'+pay_status_arr[pay_status]+'</div>';
		$.each(pay_data, function(i, v){
			console.log(v);
			var item_name = (v.seq && v.seq != "")? product_name+' / '+v.txt : v.txt;
			html += '		<div class="item_name">'+item_name+'</div>';
			html += '		<div class="item_price">'+v.value.format()+'원 x '+v.amount+'개</div>';
		});
		if(shipping_invoice != ""){
			html += '		<div class="shipping_invoice">['+shipping_company+']'+shipping_invoice+'</div>';
		}
		html += '	</div>';

		$("#item_order_list .order_list_wrap .item_cart_wrap[data-id='"+ip_log_seq+"'][data-item_no='"+target+"']").append(html);

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
									html += '	<button type="button" class="cancel_btn" data-no="'+order_num+'" data-jbordno="'+jbOrdNo+'">취소요청</button>';
								}else if(pay_status == 2){
									html += '	<button type="button" class="cancel_btn" data-no="'+order_num+'" data-jbordno="'+jbOrdNo+'">취소요청</button>';
								}else if(pay_status == 3){
									html += '	<button type="button" class="cancel_btn" data-no="'+order_num+'" data-jbordno="'+jbOrdNo+'">취소요청</button>';
								}else if(pay_status == 4){
								}else if(pay_status == 5){
									html += '	<button type="button" class="return_please_btn" data-no="'+order_num+'" data-jbordno="'+jbOrdNo+'">반품요청</button>';
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

								$("#item_order_list .order_list_wrap .item_cart_wrap[data-id='"+ip_log_seq+"'][data-item_no='"+target+"'] .item_cart_data").append(html);
								$("#item_order_list .order_list_wrap .item_cart_wrap[data-id='"+ip_log_seq+"'][data-item_no='"+target+"'] .item_cart_data").attr('data-id', v.product_no);
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
									$("#item_order_list .order_list_wrap").find(target+"[data-id='"+product_no+"'] .item_image").css("background-image", "url('"+v.file_path+"')");
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
				$("#item_order_list .order_list_wrap").find(target+"[data-id='"+product_no+"'] .item_image").css("background-image", "url('"+goodsRepImage+"')");
			}else{
				$("#item_order_list .order_list_wrap").find(target+"[data-id='"+product_no+"'] .item_image").css("background-image", "url('../images/product_img.png')");
			}
		}
	});
}

function get_item_payment_cnt(){
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_item_payment_cnt",
			customer_id : customer_id,
			is_shop: "2"
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				$("#item_order_list .total_cnt").text(data.data+"건");
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

function get_item_payment_cancel_cnt(){
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_item_payment_cancel_cnt",
			customer_id : customer_id,
			is_shop: "2"
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				$("#item_order_list .cancel_cnt").text(data.data+"건");
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