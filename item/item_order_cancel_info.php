<?php
include "../include/top.php";
include "../include/Crypto.class.php";
include "../Mobile_Detect.php";

// init
$r_no = ($_GET["no"] && $_GET["no"] != "")? $_GET["no"] : "";
$backurl = $_GET['backurl'];
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

$reason_type_arr = array(
	"1" => "단순변심", 
	"2" => "상품불량", 
	"3" => "제품변경", 
	"etc" => "그밖에(직접입력)"
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
	#item_order_cancel_info { margin-top: 61px; }
	#item_order_cancel_info .order_box .order_box_text_none .set_update_item_payment_cancel {
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

	#item_order_cancel_info .order_box .order_box_text_none .prev {
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
	.pay_type_1 { display: none; }
	.pay_type_2 { display: none; }
	.pay_type_1.on { display: block; }
	.pay_type_2.on { display: block; }
	#item_order_cancel_info .order_box .order_table tbody tr td input[name="shipping_zip"] { width: 60px; }
	#item_order_cancel_info .order_box .item_payment_log { margin: 10px; padding-bottom: 20px; border-radius: 10px; font-family: 'NL2GR'; }
	#item_order_cancel_info .order_box .item_payment_log button { border: 1px solid #ccc; border-radius: 5px; background-color: #eee; height: 30px; padding: 0px 10px; }
	#item_order_cancel_info .order_box .item_payment_log input[type='checkbox'] { display: none; width: 0px; height: 0px; padding: 0px; margin: 0px; border: 0px; font-size: 0px; }
	#item_order_cancel_info .order_box .item_payment_log input[type='checkbox']+label { position: relative; display: inline-block; padding: 0px 10px 0px 35px; height: 30px; line-height: 35px; border: 1px solid #ccc; background-color: #eee; border-radius: 5px; font-size: 16px; }
	#item_order_cancel_info .order_box .item_payment_log input[type='checkbox']+label span { position: absolute; left: 5px; top: 5px; display: inline-block; border: 1px solid #ccc; width: 20px; height: 20px; background-color: #fff; }
	#item_order_cancel_info .order_box .item_payment_log input[type='checkbox']:checked+label { border: 1px solid #f5bf2e; background-color: #f5bf2e; color: #fff; }
	#item_order_cancel_info .order_box .item_payment_log input[type='checkbox']:checked+label span { border: 1px solid #f5bf2e; }
	#item_order_cancel_info .order_box .item_payment_log input[type='checkbox']:checked+label span:before { content: ''; display: inline-block; width: 10px; height: 5px; border-left: 5px solid #f5bf2e; border-bottom: 5px solid #f5bf2e; transform: rotate(-45deg); margin-left: 2px; margin-top: 4px; }
	#item_order_cancel_info .order_box .item_payment_log .title { position: relative; line-height: 20px; height: 40px; background-color: #ffe; }
	#item_order_cancel_info .order_box .item_payment_log .title .right { position: absolute; right: 0px; top: 0px; }
	#item_order_cancel_info .order_box .item_payment_log .title .right button { height: 40px; }
	#item_order_cancel_info .order_box .item_payment_log .item_payment_data { position: relative; padding-top: 10px; height: 30px; line-height: 30px; }
	#item_order_cancel_info .order_box .item_payment_log .item_payment_data .item_price { position: absolute; right: 0px; top: 10px; font-weight: Bold; }
	#item_order_cancel_info .order_box .item_payment_log .item_cart { padding: 1px 0px; background-color: #eee; }
	#item_order_cancel_info .order_box .item_payment_log .item_cart .item_cart_wrap { background-color: #fff; margin: 10px; }
	#item_order_cancel_info .order_box .item_payment_log .item_cart .item_cart_wrap .item_cart_data { position: relative; min-height: 110px; }
	#item_order_cancel_info .order_box .item_payment_log .item_cart .item_cart_wrap .item_cart_data .item_image { position: absolute; left: 10px; top: 10px; width: 90px; height: 90px; border-radius: 5px; background-size: cover; background-repeat: no-repeat; background-position: center; }
	#item_order_cancel_info .order_box .item_payment_log .item_cart .item_cart_wrap .item_cart_data .item_detail { margin-left: 110px; min-height: 100px; padding-top: 10px; }
	#item_order_cancel_info .order_box .item_payment_log .item_cart .item_cart_wrap .item_cart_data .item_detail>div { padding-bottom: 5px; }
	#item_order_cancel_info .order_box .item_payment_log .item_cart .item_cart_wrap .item_cart_data .item_detail .item_price { font-weight: Bold; }
	#item_order_cancel_info .order_box .item_payment_log .item_cart .item_cart_wrap .btn_wrap { text-align: right; padding: 10px; }

</style>

<div class="top_menu">
	<!--div class="header-back-btn"><a href="javascript:;"><img src="/pet/images/btn_back_2.png" width="26px"></a></div-->
	<div class="top_title">
		<p>취소요청</p>
	</div>
</div>

<div id="item_order_cancel_info">
	<div class="order_box">
		<div class="order_box_title">3.취소정보/회수 확인</div>
		<div style="margin:20px 0;text-align:center;">취소 정보를 확인해 주세요.</div>
		<div class="order_box_text"style="">
			<table width="100%"class="order_table">
				<colgroup>
					<col width="30%">
					<col width="70%">
				</colgroup>
				<thead>
					<tr style="border-bottom:2px solid #ddd;">
						<td colspan="2"style="padding-bottom: 5px;">취소상품</td>
					</tr>
				</thead>
			</table>
			<div class="cart_list_wrap">
			</div>
		</div>
		<div class="order_box_text"style="">
			<table width="100%"class="order_table">
				<colgroup>
					<col width="30%">
					<col width="70%">
				</colgroup>
				<tbody>
					<tr style="border-bottom:2px solid #ddd;">
						<td style="padding-bottom: 5px;">취소사유</td>
					</tr>
					<tr>
						<td style="padding:5px 0 2px;font-size:12px;"><?=($_SESSION["RNC_REASONTYPE"] == "etc")? "[직접입력]".$_SESSION["RNC_REASONDETAIL"] : $reason_type_arr[$_SESSION["RNC_REASONTYPE"]]; ?></td>
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
		<div class="order_box_text"style="">
			<table width="100%"class="order_table">
				<colgroup>
					<col width="30%">
					<col width="70%">
				</colgroup>
				<tbody>
					<tr style="border-bottom:2px solid #ddd;">
						<td colspan="2"style="padding-bottom: 5px;">환불 정보</td>
					</tr>
					<tr>
						<td style="padding:5px 0 2px;font-size:12px;">상품금액</td>
						<td style="text-align: right;padding:5px 0 2px;font-size:12px;"><span class="total_price">0</span>원</td>
					</tr>
					<tr>
						<td style="padding:5px 0 2px;font-size:12px;">포인트사용</td>
						<td style="text-align: right;padding:5px 0 2px;font-size:12px;"><span class="point_price">0</span>원</td>
					</tr>
					<tr>
						<td style="padding:5px 0 2px;font-size:12px;">취소비용(차감)</td>
						<td style="text-align: right;padding:5px 0 2px;font-size:12px;"><span class="minus_price">0</span>원</td>
					</tr>
					<tr>
						<td style="padding:10px 0 2px;color:#D51A3D;">환불 예상금액</td>
						<td style="text-align: right;padding:5px 0 2px;color:#D51A3D;"><span class="cancel_price">0</span>원</td>
					</tr>
				</tbody>
			</table>
		</div>
<!----카드결제시---->
		<div class="order_box_text pay_type_1" style="padding:0;">
			<table width="100%">
				<colgroup>
					<col width="30%">
					<col width="70%">
				</colgroup>
				<tbody>
					<tr style="">
						<td style="padding:10px;">환불수단</td>
						<td class="pay_detail" style="padding:10px;text-align:right;">0원</td>
					<tr>
				</tbody>
			</table>
		</div>
<!---------------->
<!----무통장입금 결제시---->
		<div class="order_box_text pay_type_2" style="padding:0;">
			<table width="100%">
				<colgroup>
					<col width="35%">
					<col width="65%">
				</colgroup>
				<tbody>
					<tr style="">
						<td style="padding:10px 10px 0;">환불수단</td>
						<td class="pay_detail" style="padding:10px;text-align:right;">무통장입금 / 17,100원</td>
					<tr>
					<tr style="">
						<td style="padding:0 10px 10px;">
							<select id="bank_option" name="cancel_bank" width="90%">
								<option value="">선택</option>
							<?php foreach($banks AS $key => $value){ ?>
								<option value="<?=$key?>"><?=$value?></option>
							<?php } ?>
							</select>
						</td>
						<td style="padding:0 10px 10px 0;text-align:right;"><input class="point" name="cancel_account" type="number" value="" placeholder="계좌번호를 입력해주세요"></td>
					<tr>
				</tbody>
			</table>
		</div>
<!---------------->
		<div class="order_box_text_none" style="text-align: center;">
			<a href="javascript:;" class="prev">이전단계</a><a href="javascript:;" class="set_update_item_payment_cancel">취소접수</a>
		</div>
		
	</div>
</div>

<?php // 우편번호 검색창 ?>
<div id="search_addr_wrap" style="display: none;"></div>

<script>
	var no = "<?=$r_no ?>";
	var user_id = "<?=$user_id ?>";
	var cancel_pay_type = 2;
	var cancel_price = 0;
	var minus_price = 0;
	var receipt_id = "";
	var customer_name = "";
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
					
					$("#item_order_cancel_info input[name='shipping_name']").val(data.data[0].shipping_name);
					$("#item_order_cancel_info input[name='shipping_cellphone']").val(data.data[0].shipping_cellphone);
					if(data.data[0].shipping_zipcode && data.data[0].shipping_zipcode != ""){
						$("#item_order_cancel_info input[name='shipping_zip']").val(data.data[0].shipping_zipcode);
						$("#item_order_cancel_info input[name='shipping_addr']").val(data.data[0].shipping_addr);
						$("#item_order_cancel_info input[name='shipping_addr2']").val(data.data[0].shipping_addr2);
					}else{
						$("#item_order_cancel_info input[name='shipping_zip']").val(data.data[0].shipping_addr.split("|")[0]);
						$("#item_order_cancel_info input[name='shipping_addr']").val(data.data[0].shipping_addr.split("|")[1]);
					}
					$("#item_order_cancel_info input[name='shipping_memo']").val(data.data[0].shipping_memo);
					cancel_price = (parseInt(data.data[0].total_price) + parseInt(data.data[0].point_price)) - parseInt(minus_price);
					if(cancel_price <= 0){
						cancel_price = 0;
					}
					$("#item_order_cancel_info span.total_price").text(data.data[0].total_price.format());
					$("#item_order_cancel_info span.point_price").text(data.data[0].point_price.format());
					$("#item_order_cancel_info span.minus_price").text("-"+minus_price.format());
					$("#item_order_cancel_info span.cancel_price").text(cancel_price.format());
					if(data.data[0].pay_type == "1"){
						if(data.data[0].pg_log){
							var pg_log = data.data[0].pg_log.split('&');
							var pg_log_card_company = "";

							$.each(pg_log, function(i, v){
								if(v.split('=')[0] == "P_FN_NM"){
									pg_log_card_company = v.split('=')[1];
								}
							});
							$("#item_order_cancel_info .pay_type_1").addClass("on");
							$("#item_order_cancel_info .pay_type_1 .pay_detail").text(pg_log_card_company+' / '+parseInt(cancel_price).format()+'원');
						}
					}else{
						$("#item_order_cancel_info .pay_type_2").addClass("on");
						$("#item_order_cancel_info .pay_type_2 .pay_detail").text('계좌이체 / '+parseInt(data.data[0].total_price).format()+'원');
					}
					cancel_pay_type = data.data[0].pay_type; // 결제방식
					receipt_id = data.data[0].receipt_id; // 결제번호
					customer_name = data.data[0].guest_info.split(",")[0];
					console.log(receipt_id, customer_name);
					var html = '';

					$.each($.parseJSON(data.data[0].pay_data), function(i, v){
						html += '<tr>';
						html += '	<td style="padding:10px 0;"><div class="product_image"><img src="/pet/images/ex_image.png" width="100%"></div></td>';
						html += '	<td style="padding:10px 0 10px 10px;">';
						html += '		<p style="font-size:12px;">'+v.txt+'</p>';
						html += '		<p style="margin-top:10px;">'+v.amount+' / '+v.value.format()+'원</p>';
						html += '		<p style="margin-top:10px;">배송비 '+data.data[0].shipping_price.format()+'원</p>';
						html += '	</td>';
						html += '</tr>';
					});
					html += '<tr style="border-top: 2px solid #adadad;">';
					html += '	<td colspan="2" style="text-align:right; padding-top: 10px;">포인트사용 : '+data.data[0].point_price.format()+'원</td>';
					html += '</tr>';
					html += '<tr>';
					html += '	<td colspan="2" style="text-align:right; padding-top: 10px;">총 합계 : '+data.data[0].total_price.format()+'원</td>';
					html += '</tr>';

					$("#item_order_cancel_info .cart_list_wrap").html('').html(html);

					
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

								$("#item_order_cancel_info .product_image").html("").html(html).css("background-image", "none");
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
							$("#item_order_cancel_info input[name='shipping_name']").val(v.shipping_name);
							$("#item_order_cancel_info input[name='shipping_cellphone']").val(v.shipping_cellphone);
							if(v.shipping_zipcode && v.shipping_zipcode != ""){
								$("#item_order_cancel_info input[name='shipping_zip']").val(v.shipping_zipcode);
								$("#item_order_cancel_info input[name='shipping_addr']").val(v.shipping_addr);
								$("#item_order_cancel_info input[name='shipping_addr2']").val(v.shipping_addr2);
							}else{
								$("#item_order_cancel_info input[name='shipping_zip']").val(v.shipping_addr.split("|")[0]);
								$("#item_order_cancel_info input[name='shipping_addr']").val(v.shipping_addr.split("|")[1]);
							}
							$("#item_order_cancel_info input[name='shipping_memo']").val(v.shipping_memo);
							cancel_price = (parseInt(v.product_price) + parseInt(v.shipping_price) - parseInt(v.point_price)) - parseInt(minus_price);
							if(cancel_price <= 0){
								cancel_price = 0;
							}
							$("#item_order_cancel_info span.total_price").text(v.total_price.format());
							$("#item_order_cancel_info span.point_price").text(v.point_price.format());
							$("#item_order_cancel_info span.minus_price").text("-"+minus_price.format());
							$("#item_order_cancel_info span.cancel_price").text(cancel_price.format());
							if(v.pay_type == "1"){
								if(v.pg_log){
									var pg_log = v.pg_log.split('&');
									var pg_log_card_company = "";

									$.each(pg_log, function(i, v){
										if(v.split('=')[0] == "P_FN_NM"){
											pg_log_card_company = v.split('=')[1];
										}
									});
									$("#item_order_cancel_info .pay_type_1").addClass("on");
									$("#item_order_cancel_info .pay_type_1 .pay_detail").text(pg_log_card_company+' / '+parseInt(cancel_price).format()+'원');
								}
							}else{
								$("#item_order_cancel_info .pay_type_2").addClass("on");
								$("#item_order_cancel_info .pay_type_2 .pay_detail").text('계좌이체 / '+parseInt(v.total_price).format()+'원');
							}
							cancel_pay_type = v.pay_type; // 결제방식
							receipt_id = v.receipt_id; // 결제번호
							customer_name = v.guest_info.split(",")[0];
							console.log(receipt_id, customer_name);
							
							var pay_dt = (v.pay_dt != null)? new Date(v.pay_dt) : "";
							pay_dt = (pay_dt != "")? pay_dt.getFullYear()+'-'+fillZero(2, (pay_dt.getMonth()+1))+'-'+fillZero(2, pay_dt.getDate()) : ((v.pay_dt == null && v.pay_type == "2")? "입금 대기중" : "결제 대기중");
							var color_red = (v.pay_status == 7 || v.pay_status == 8 || v.pay_status == 9)? "red" : "";
							var pay_data_list = (v.pay_data && v.pay_data.indexOf('||') != -1)? v.pay_data.split('||') : [v.pay_data];

							html += '<div class="item_payment_log">';
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

						$("#item_order_cancel_info .cart_list_wrap").html(html);

						/*
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
						*/

						resolve(json.data);

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

								html += '<div class="item_cart_wrap" data-id="'+v.iplp_seq+'" data-item_no="'+i+'">';
								html += '	<div class="item_cart_data">';
								html += '		<div class="item_image" style=" background-image: url(\'/pet/images/product_img.png\');"></div>';
								html += '		<div class="item_detail">';
								html += '			<div class="item_chk">';
								html += '				<input type="checkbox" id="chk_ag'+v.iplp_seq+''+i+'" name="chk_ag[]" value="'+_chk_val+'" />';
								html += '				<label for="chk_ag'+v.iplp_seq+''+i+'"><span></span>취소</label>';
								html += '			</div>';
								$.each(_option_data, function(i2, v2){
									console.log(v2);
									var item_name = (v2.seq && v2.seq != "")? ((post_data && post_data.length > 0)? post_data[0].product_name+' / '+v2.txt : v2.txt) : v2.txt;
									//var chk_val = (v2.seq && v2.seq != "")? v2.seq : "I"+v2.il_seq;
									html += '			<div class="item_name">'+item_name+'</div>';
									html += '			<div class="item_price">'+v2.value.format()+'원 / '+v2.amount+'개</div>';
								});
								if(v.shipping_invoice && v.shipping_invoice != ""){
									html += '			<div class="shipping_invoice">['+v.shipping_company+']'+v.shipping_invoice+'</div>';
								}
								html += '		</div>';
								html += '	</div>';
								html += '</div>';
							});

							$("#item_order_cancel_info .cart_list_wrap .item_cart").html(html);

							$.each(data.data, function(i, v){
								$("#item_order_cancel_info .item_payment_log .item_cart_wrap[data-id='"+v.iplp_seq+"'][data-item_no='"+i+"'] .item_cart_data").attr('data-id', v.product_no);
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
					$.each($("#item_order_cancel_info input[type='checkbox']"), function(i2, v2){
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
				html += '			<input type="checkbox" id="chk_ag'+i+'" name="chk_ag[]" value="'+chk_val+'" disabled />'; // 체크 결과만 보여줌
				html += '			<label for="chk_ag'+i+'"><span></span>삭제</label>';
				html += '		</div>';
				html += '		<div class="item_name">'+item_name+'</div>';
				html += '		<div class="item_price">'+v.amount.format()+'/ '+v.value+'원</div>';
			});
			if(shipping_invoice != ""){
				html += '		<div class="shipping_invoice">['+shipping_company+']'+shipping_invoice+'</div>';
			}
			html += '	</div>';

			$("#item_order_cancel_info .cart_list_wrap .item_cart_wrap[data-id='"+ip_log_seq+"'][data-item_no='"+target+"']").append(html);

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
									$("#item_order_cancel_info .cart_list_wrap .item_cart_wrap[data-id='"+ip_log_seq+"'][data-item_no='"+target+"'] .item_cart_data").attr('data-id', v.product_no);
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
										$("#item_order_cancel_info .cart_list_wrap").find(target+"[data-id='"+product_no+"'] .item_image").css("background-image", "url('"+v.file_path+"')");
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
					$("#item_order_cancel_info .cart_list_wrap").find(target+"[data-id='"+product_no+"'] .item_image").css("background-image", "url('"+goodsRepImage+"')");
				}else{
					$("#item_order_cancel_info .cart_list_wrap").find(target+"[data-id='"+product_no+"'] .item_image").css("background-image", "url('../images/product_img.png')");
				}
			}
		});
	}

	$(document).on("click", "#item_order_cancel_info .set_update_shipping_info_btn", function(){
		var shipping_name = $("#item_order_cancel_info input[name='shipping_name']").val();
		var shipping_cellphone = $("#item_order_cancel_info input[name='shipping_cellphone']").val();
		var shipping_zip = $("#item_order_cancel_info input[name='shipping_zip']").val();
		var shipping_addr = $("#item_order_cancel_info input[name='shipping_addr']").val();
		var shipping_addr2 = $("#item_order_cancel_info input[name='shipping_addr2']").val();
		var shipping_memo = $("#item_order_cancel_info input[name='shipping_memo']").val();

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

	$(document).on("click", ".header-back-btn", function(){
		window.history.back();
	});

	$(document).on("click", "#item_order_cancel_info .prev", function(){
		window.history.back();
	});

	$(document).on("click", "#item_order_cancel_info .set_update_item_payment_cancel", function(){
		var cancel_account = $("#item_order_cancel_info input[name='cancel_account']").val();
		var cancel_bank = $("#item_order_cancel_info select[name='cancel_bank'] option:selected").val();

		if(cancel_price <= 0){
			$.MessageBox("환불금액이 없어 진행할 수 없습니다.");
			return false;
		}

		if(cancel_pay_type == "2"){
			if(cancel_bank == ""){
				$.MessageBox("환불받을 은행을 선택하세요.");
				return false;
			}
			if(cancel_account == ""){
				$.MessageBox("환불받을 계좌를 입력하세요.");
				return false;
			}
		}
		
		if(cancel_pay_type == "1" && receipt_id != null){ // 카드 결제 취소처리
			// 0024 상품결제취소
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "item_payment_cancel",
					receipt_id : receipt_id,
					customer_name : customer_name,
					cancel_reason : "사용자 요청에 의해 직접 취소",
					cancel_price : cancel_price,
					cancel_type : "cancel"
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data); 
						// 취소처리 0036
						$.ajax({
							url: '<?=$item_directory ?>/item_list_ajax.php',
							data: {
								mode : "set_update_item_return_n_cancel_step3",
								return_pay_type : cancel_pay_type,
								return_account : cancel_account,
								return_bank : cancel_bank,
								return_price : cancel_price,
								order_num : no
							},
							type: 'POST',
							dataType: 'JSON',
							success: function(data) {
								if(data.code == "000000"){
									console.log(data.data); 
									$.MessageBox({
										buttonDone  : "확인",
										message     : "취소처리가 완료되었습니다."
									}).done(function(data, button){
										if(user_id != ""){
											location.href = "<?=$mainpage_directory ?>/item_order_list.php";
										}else{
											location.href = "../chkodr/item_order_detail.php?no="+no;
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
			// 취소처리 0036
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "set_update_item_return_n_cancel_step3",
					return_pay_type : cancel_pay_type,
					return_account : cancel_account,
					return_bank : cancel_bank,
					return_price : cancel_price,
					order_num : no
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data); 
						$.MessageBox({
							buttonDone  : "확인",
							message     : "취소처리가 완료되었습니다."
						}).done(function(data, button){
							if(user_id != ""){
								location.href = "<?=$mainpage_directory ?>/item_order_list.php";
							}else{
								location.href = "../chkodr/item_order_detail.php?no="+no;
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
		}
	});

	//주소 검색
	$(document).on("click", "#item_order_cancel_info .search_addr_btn", function(){
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
				execDaumPostcode($("#item_order_cancel_info"));
			},
			close: function() {
				// to do something...
			}
		});
	});

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