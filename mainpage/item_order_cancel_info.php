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
				<tbody class="cart_list_wrap">
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
					</tr-->
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

	$(function(){
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
						url: '<?=$mainpage_directory?>/fileupload_ajax.php',
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
	});

	$(document).on("click", "#item_order_cancel_info .set_update_shipping_info_btn", function(){
		var shipping_name = $("#item_order_cancel_info input[name='shipping_name']").val();
		var shipping_cellphone = $("#item_order_cancel_info input[name='shipping_cellphone']").val();
		var shipping_zip = $("#item_order_cancel_info input[name='shipping_zip']").val();
		var shipping_addr = $("#item_order_cancel_info input[name='shipping_addr']").val();
		var shipping_addr2 = $("#item_order_cancel_info input[name='shipping_addr2']").val();
		var shipping_memo = $("#item_order_cancel_info input[name='shipping_memo']").val();

		$.ajax({
			url: '<?=$mainpage_directory?>/item_list_ajax.php',
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
				alert(error + "네트워크에러");
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
				url: '<?=$mainpage_directory?>/item_list_ajax.php',
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
							url: '<?=$mainpage_directory?>/item_list_ajax.php',
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
											location.href = "<?=$mainpage_directory?>/item_order_list.php";
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
								alert(error + "네트워크에러");
							}
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
		}else{
			// 취소처리 0036
			$.ajax({
				url: '<?=$mainpage_directory?>/item_list_ajax.php',
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
								location.href = "<?=$mainpage_directory?>/item_order_list.php";
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
					alert(error + "네트워크에러");
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
</script>