<?php
include "../include/top.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

// init
$r_no = ($_GET["no"] && $_GET["no"] != "")? $_GET["no"] : "";
$r_order_status = ($_GET["os"] && $_GET["os"] != "")? $_GET["os"] : "";
$r_pay_status = ($_GET["ps"] && $_GET["ps"] != "")? $_GET["ps"] : "";
$r_item_list = ($_GET["il"] && $_GET["il"] != "")? $_GET["il"] : "";
$r_customer_name = ($_GET["cn"] && $_GET["cn"] != "")? $_GET["cn"] : "";
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

$pay_status_color_old = array(
	"1" => "#999999",
	"2" => "#2969C4",
	"3" => "#ff0000",
	"4" => "#ffcc00",
	"5" => "#339900",
	"6" => "#333333",
	"7" => "#333333",
	"8" => "#595959",
	"9" => "#404040"
);

?>
<script type="text/javascript" src="<?= $js_directory ?>/fontawesome.min.js"></script>
<style>
	html, body { position: relative; padding: 0px; margin: 0px; width: 100%; height: 100%; font-family: 'SCDream2'; src: url("../fonts/SCDream2.otf"); }
    input { -webkit-appearance: none; border-radius: 0; }

    .top_menu { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; background-color: rgba(255,255,255,0.8); border: 1px solid #ccc; z-index: 1; }
    .top_title { width: 100%; height: 50px; line-height: 50px; text-align: center; }
    .top_title p { margin: 0px; padding: 0px; }
    .header-back-btn { top: 13px; position: absolute; left: 10px; }
	#admin_item_payment_log { margin-top: 61px; }
	#admin_item_payment_log .search_wrap { line-height: 40px; padding: 5px; margin-bottom: 10px; border-bottom: 1px solid #f9f9f9; }
	#admin_item_payment_log .search_order_status { height: 30px; padding: 0px 10px; }
	#admin_item_payment_log .search_pay_status { height: 30px; padding: 0px 10px; }
	#admin_item_payment_log .search_item_list { max-width: 350px; height: 30px; padding: 0px 10px; }
	#admin_item_payment_log .search_customer_name { height: 30px; border: 1px solid #ccc; background-color: #fff; padding: 0px 10px; }
	#admin_item_payment_log .search_btn { border: 1px solid #ccc; background-color: #eee; height: 30px; padding: 0px 10px; border-radius: 5px; }
	#admin_item_payment_log .search_reset_btn { border: 1px solid #ccc; background-color: #eee; height: 30px; padding: 0px 10px; border-radius: 5px; }
	#admin_item_payment_log .order_box_title { text-align: right; font-size: 14px; padding: 0px 5px 5px 0px; }
	#admin_item_payment_log .order_table { width: 100%; border-collapse: collapse; font-size: 12px; text-align: center; }
	#admin_item_payment_log .order_table tr { border-bottom: 1px solid #eee; }
	#admin_item_payment_log .order_table tr:hover { background-color: #fff9ee; }
	#admin_item_payment_log .order_table tr.cancel { background-color: #fcc; color: #f00; }
	#admin_item_payment_log .order_table tr.return { background-color: #ff9966; color: #660000; }
	#admin_item_payment_log .order_table tr.return_finish { background-color: #ff9966; color: #660000; }
	#admin_item_payment_log .order_table tr.ready { color: #ccc; }
	#admin_item_payment_log .order_table tr.ready td>a { color: #ccc; }
	#admin_item_payment_log .order_table tr th { padding: 5px; background-color: #ddd; white-space: nowrap; }
	#admin_item_payment_log .order_table tr td { padding: 2px 5px; height: 50px; }
	#admin_item_payment_log .order_table tr td a { color: #333; }
	#admin_item_payment_log .order_table tr td.lft { text-align: left; }
	#admin_item_payment_log .order_table tr td.rht { text-align: right; }
	#admin_item_payment_log .order_table tr td img { width: 100%; min-width: 50px; max-width: 50px; }
	#admin_item_payment_log .order_table tr td .product_img { width: 50px; height: 50px; background-size: cover; background-repeat: no-repeat; background-position: center; }
	#admin_item_payment_log .order_table tr td .card { color: #f00; font-weight: Bold; font-size: 10px; }
	#admin_item_payment_log .order_table tr td .bank { color: #00f; font-weight: Bold; font-size: 10px; }
	#admin_item_payment_log .order_table tr td .jbook { color: #339900; }
	.more_btn { width: 100%; border: 0px; border-bottom: 1px solid #e4ae1d; background-color: #f5bf2e; color: #fff; height: 50px; line-height: 50px; }

	@media only screen and (max-width: 670px)  {
		#admin_item_payment_log .item_product_list { display: none; }
	}
	
</style>
<div id="admin_item_payment_log">
	<div class="top_menu">
		<div class="header-back-btn"><a href="/pet/admin/"><img src="/pet/images/btn_back_2.png" width="26px"></a></div>
        <div class="top_title">
            <p>전문몰 상품 결제관리</p>
        </div>
    </div>
	<div class="search_wrap">
		<select class="search_order_status">
			<option value="">전체</option>
		<?php 
			foreach($order_status_arr AS $key => $value){
				$is_selected = ($key == $r_order_status)? " selected " : "";
				echo "<option value='".$key."' ".$is_selected.">".$value."</option>";
			}
		?>
		</select>
		<select class="search_pay_status">
			<option value="">전체</option>
		<?php 
			foreach($pay_status_arr AS $key => $value){
				$is_selected = ($key == $r_pay_status)? " selected " : "";
				echo "<option value='".$key."' ".$is_selected.">".$value."</option>";
			}
		?>
		</select>
		<input type="text" class="search_customer_name" placeholder="회원명 검색" value="<?=$r_customer_name ?>" />
		<button type="button" class="search_btn"><i class="fas fa-search"></i></button>
		<button type="button" class="search_reset_btn"><i class="fas fa-undo"></i></button>
	</div>
	<div class="order_box">
		<div class="order_box_title">주문 <span class="total_cnt">0건</span> / 반품(취소) <span class="cancel_cnt">0건</span></div>
		<div>
			<table width="100%" class="order_table">
				<colgroup>
					<col width="1%">
					<col width="1%">
					<col width="*">
					<col width="1%">
					<col width="1%">
				</colgroup>
				<thead>
					<tr>
						<th>상태</th>
						<th>사진</th>
						<th>상품명<br/>결제수단</th>
						<th>주문자명<br/>연락처<br/>결제금액</th>
						<th>결제일시<br/>(취소/반품)</th>
					</tr>
				</thead>
				<tbody class="order_list_wrap">
				</tbody>
			</table>
		</div>
		<div class="more">
			<button class="more_btn">더보기</button>
		</div>
	</div>
</div>

<script>
	var customer_id = "<?=$user_id ?>";
	var item_list = [];
	var order_status_arr = $.parseJSON('<?=json_encode($order_status_arr)?>');
	var order_status_color = $.parseJSON('<?=json_encode($order_status_color)?>');
	var pay_status_arr = $.parseJSON('<?=json_encode($pay_status_arr)?>');
	var pay_status_color = $.parseJSON('<?=json_encode($pay_status_color)?>');
	var pay_status_arr_old = $.parseJSON('<?=json_encode($pay_status_arr_old)?>');
	var pay_status_color_old = $.parseJSON('<?=json_encode($pay_status_color_old)?>');
	var search_pay_status = "<?=$r_pay_status ?>";
	var search_order_status = "<?=$r_order_status ?>";
	var search_item_list = "<?=$r_item_list ?>";
	var search_customer_name = "<?=$r_customer_name ?>";
	var item_list_flag = 0;		 // 현재 표시된 갯수 묶음
	var item_list_page_cnt = 20; // 화면에 표시될 아이템 갯수

	$(function(){
		get_item_payment_log()
			.then(get_item_payment_log_product_list)
			.then(get_item_payment_cnt)
			.then(get_item_payment_cancel_cnt);
	});

	$(document).on("change", ".search_pay_status, .search_order_status", function(){
		location.href = "item_payment_log.php?os="+$(".search_order_status").children("option:selected").val()+"&ps="+$(".search_pay_status").children("option:selected").val()+"&il="+$(".search_item_list").children("option:selected").val()+"&cn="+$(".search_customer_name").val();
	});

	$(document).on("click", ".search_btn", function(){
		location.href = "item_payment_log.php?os="+$(".search_order_status").children("option:selected").val()+"&ps="+$(".search_pay_status").children("option:selected").val()+"&il="+$(".search_item_list").children("option:selected").val()+"&cn="+$(".search_customer_name").val();
	});
	$(document).on("click", ".search_reset_btn", function(){
		location.href = "item_payment_log.php";
	});

	$(document).on("click", "#admin_item_payment_log .more_btn", function(){
		item_list_flag += item_list_page_cnt;
		get_item_payment_log()
			.then(get_item_payment_log_product_list);
	});

	$(document).on("click", "#admin_item_payment_log .cancel_btn", function(){
		var no = $(this).data("no");
		location.href = "?no="+no;
	});

	$(document).on("click", "#admin_item_payment_log .go_item_payment_detail_btn", function(){
		var order_num = $(this).data("order_num");
		history.pushState(null, null, window.location.pathname+"?os="+search_order_status+"&ps="+search_pay_status+"&il="+search_item_list+"&cn="+search_customer_name);
		localStorage.setItem('windowscrolltop',$(window).scrollTop()); // scroll position
		localStorage.setItem('itemlistflag',item_list_flag); // more_list_cnt
		location.href = '<?=$admin_directory?>/item_special_mall_payment_log_detail.php?no='+order_num+'&backurl='+encodeURIComponent(window.location.pathname+"?os="+search_order_status+"&ps="+search_pay_status+"&il="+search_item_list+"&cn="+search_customer_name);
	});


	function get_item_payment_log(){
		return new Promise(function(resolve, reject) {
			var post_itemlistflag = localStorage.getItem('itemlistflag');
			var post_item_list_flag = item_list_flag;
			var post_item_list_page_cnt = item_list_page_cnt;
			if(post_itemlistflag > 0){
				post_item_list_flag = 0;
				post_item_list_page_cnt = parseInt(item_list_page_cnt) + parseInt(post_itemlistflag);
				item_list_flag = parseInt(post_itemlistflag);
				localStorage.setItem('itemlistflag', 0); // more_list_cnt
			}

			$.ajax({
				type: 'post',
				url: "../test/test_item_list_ajax.php",
				data: {
					mode: "get_item_payment_log",
					order_status : $(".search_order_status").children("option:selected").val(),
					pay_status : $(".search_pay_status").children("option:selected").val(),
					//item_list : $(".search_item_list").children("option:selected").val(),
					customer_name : $(".search_customer_name").val(),
					is_shop: "1",
					flag : post_item_list_flag,
					cnt  : post_item_list_page_cnt
				},
				dataType: 'json',
				success: function(json) {
					if(json.code == "000000"){
						//console.log(json.data);
						var html = '';
						var idx = 0;
					
						$.each(json.data, function(i, v){
							var return_info = "";
							switch(v.is_return){
								case "2" : return_info = " </br> (반품요청) ";
								break;
								case "3" : return_info = " </br> (반품완료) ";
							}
							var pay_dt = (v.pay_dt != null)? v.pay_dt : "-";
							var is_ready = (v.order_status == "1")? " ready " : "";
							var is_user_cancel = (v.is_cancel == "2")? " cancel " : "";
							var cancel_dt = (v.cancel_dt != null)? v.cancel_dt : "-";
							var is_user_return = (v.is_return == "2")? " return " : "";
							var is_return_finish = (v.is_return == "3")? " return_finish " : "";
							var return_dt = (v.return_dt != null)? v.return_dt : "-";
							var pay_type = (v.pay_type == "1")? "<span class='card'>카드</span>" : "<span class='bank'>계좌</span>";
							var is_jbook = (v.jbOrdNo && v.jbOrdNo != "")? '<span class="jbook"><i class="fas fa-circle"></i></span>' : '';
							var is_test = (v.receipt_id && v.receipt_id.indexOf('INIpayTest') != -1)? '<span style="color: #fff;font-weight: Bold; background-color: #f00;">[!TEST!]</span>' : '';

							html += '			<tr class="'+is_user_cancel+' '+is_user_return+' '+is_return_finish+' '+is_ready+'" data-id="'+v.order_num+'">';
							html += '				<td style="border-right: 2px solid '+order_status_color[v.order_status]+'; white-space: nowrap;">'+order_status_arr[v.order_status]+return_info+'</td>';
							html += '				<td><div class="product_img" data-ip_log_seq="'+v.ip_log_seq+'" style="background-image: url(\'/pet/images/product_img.png\'); "></div></td>';
							html += '				<td class="lft item_data"><a href="javascript:;" class="go_item_payment_detail_btn" data-order_num="'+v.order_num+'"><span style="font-size: 9px;">'+v.order_num+'</span>'+pay_type+'<br/>'+is_test+''+v.product_name+' '+is_jbook+'</a></td>';
							html += '				<td class="rht">'+v.guest_info.split(',')[0]+'<br/><span style="font-size: 10px;">'+v.cellphone+'</span><br/>'+v.total_price.format()+'원</td>';
							if(v.is_cancel == "2"){
								html += '				<td>'+(cancel_dt.substring(2, cancel_dt.length)).substring(0, cancel_dt.length-5)+'</td>';
							}else if(v.is_return == "2"){
								html += '				<td>'+(return_dt.substring(2, return_dt.length)).substring(0, return_dt.length-5)+'</td>';
							}else{
								html += '				<td>'+(pay_dt.substring(2, pay_dt.length)).substring(0, pay_dt.length-5)+'</td>';
							}
							html += '			</tr>';

							item_list.push(v.product_no);
							
							idx++;
						});

						if(idx == 0){
							$("#admin_item_payment_log .more_btn").hide();
							html += '			<tr style="border-bottom:2px solid #ddd;">';
							html += '				<td colspan="5" class="no_data">등록된 결제가 없습니다.</td>';
							html += '			</tr>';
						}else if(idx >= item_list_page_cnt){
							$("#admin_item_payment_log .more_btn").show();
						}else{
							$("#admin_item_payment_log .more_btn").hide();
						}

						$("#admin_item_payment_log .order_list_wrap").append(html);

						$.each(json.data, function(i, v){ // 상품 리스트
							var pay_data_list = (v.pay_data && v.pay_data.indexOf('||') != -1)? v.pay_data.split('||') : [v.pay_data];
							var shipping_invoice = (v.pay_status == 4 && v.shipping_invoice != "" && v.shipping_company != "")? v.shipping_invoice : "";
							var shipping_company = (v.pay_status == 4 && v.shipping_invoice != "" && v.shipping_company != "")? v.shipping_company : "";
							var p = $.when();
							var c = 0;

							$.each(pay_data_list, function(i2, v2){
								if(i2 == 0){ // 대표상품만 표시
									var pay_data = (v2 && v2 != "")? $.parseJSON(v2.replace(/\\/g, '')) : "";
									//console.log(v.ip_log_seq, pay_data);

									p = p.then(function(){
										c++;
										return get_item(v.ip_log_seq, i2, pay_data, v.product_name, v.pay_status, shipping_invoice, shipping_company);
									}).done(function(){
										if(c == pay_data_list.length){
											resolve(json.data);
										}
									});
								}
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

	function get_item_payment_log_product_list(post_data){
		return new Promise(function(resolve, reject) {
			console.log("product", post_data);
			var p = $.when();
			var c = 0;

			$.each(post_data, function(i, v){
				p = p.then(function(){
					c++;
					return get_item_payment_log_product(v.order_num, v.pay_data, v.pay_status, v.order_status);
				}).done(function(){
					if(c == post_data.length){
						resolve();
					}
				});
			});
		});
	}

	function get_item_payment_log_product(order_num, pay_data, pay_status, order_status){ // 주문번호, 주문내역(기존), 주문상태(기존), 결제상태
		return new Promise(function(resolve, reject) {
			console.log("product_list!", order_num, pay_data, pay_status, order_status);

			$.ajax({
				url: '../test/test_item_list_ajax.php',
				data: {
					mode: 'get_item_payment_log_product',
					order_num: order_num
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log('ㅁㄴㅇㅁㄴㅇ', data.data);
						var html = '';
						var direct = 0;
						if(data.data && data.data.length > 0){
							//html += '<tr>';
							//html += '	<td colspan="5">';
							html += '		<div class="item_product_list" style="border: 1px solid #ccc; background-color: #eee; padding: 2px; border-radius: 5px; font-size: 10px;">';
							$.each(data.data, function(i, v){
								let _pay_data = (v.option_data && v.option_data != "")? $.parseJSON(v.option_data.replace(/\\/g, '')) : "";
								let _pay_status = (order_status == "2")? order_status_arr["2"] : pay_status_arr[v.pay_status];
								let _pay_status_color = (order_status == "2")? order_status_color["2"] : pay_status_color[v.pay_status];
								$.each(_pay_data, function(i2, v2){
									html += '			<span style="border-right: 5px solid '+_pay_status_color+'; display: inline-block; width: 50px;">'+_pay_status+'</span>';
									html += '			<span>'+v2.amount+'개 / '+v2.value.format()+'원</span>';
									html += '			<span>'+v2.txt+'</span><br/>';
									if(v.is_supply == "2"){
										direct += 1;
									}
								});
							});
							html += '		</div>';
							//html += '	</td>';
							//html += '</tr>';
							$("#admin_item_payment_log tbody.order_list_wrap tr[data-id='"+order_num+"'] .item_data").append(html);
						}else{
							// 삭제된 상품이 있음
							//$("#item_order_list .order_list_wrap .item_cart_wrap[data-id='"+ip_log_seq+"'][data-item_no='"+target+"'] .item_cart_data .item_detail").append('<div class="not_found">상품 없음</div>');
							//html += '<tr>';
							//html += '	<td colspan="5">';
							let _pay_data_list = (pay_data && pay_data != "")? ((pay_data.indexOf('||') != -1)? pay_data.split('||') : [pay_data]) : [];
							html += '		<div class="item_product_list" style="border: 1px solid #ccc; background-color: #eee; padding: 2px; border-radius: 5px; font-size: 10px;">';
							$.each(_pay_data_list, function(i2, v2){
								let _pay_data = (v2 && v2 != "")? $.parseJSON(v2.replace(/\\/g, '')) : "";

								$.each(_pay_data, function(i3, v3){
									html += '			<span style="border-right: 5px solid '+pay_status_color_old[pay_status]+'; display: inline-block; width: 50px;">'+pay_status_arr_old[pay_status]+'</span>';
									html += '			<span>'+v3.amount+'개 / '+v3.value.format()+'원</span>';
									html += '			<span>'+v3.txt+'</span><br/>';
								});
							});
							html += '		</div>';
							//html += '	</td>';
							//html += '</tr>';
							$("#admin_item_payment_log tbody.order_list_wrap tr[data-id='"+order_num+"'] .item_data").append(html);
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

	function get_item(ip_log_seq, target, pay_data, product_name, pay_status, shipping_invoice, shipping_company){
		return new Promise(function(resolve, reject) {
			var cnt = 0;
			var html = '';
			
			html += '<div class="item_cart_data">';
			html += '	<div class="item_image" style=" background-image: url(\'/pet/images/product_img.png\');"></div>';
			html += '</div>';

			$("#admin_item_payment_log .order_list_wrap .item_cart_wrap[data-id='"+ip_log_seq+"'][data-item_no='"+target+"']").append(html);
			if(pay_data != ""){
				$.each(pay_data, function(i, v){ // thumbnail - product_no
					var post_data = {};
					if(v.seq && v.seq != ""){
						post_data.mode = "get_item_option";
						post_data.io_seq = v.seq;
					}else{
						post_data.mode = "get_item";
						post_data.il_seq = v.il_seq;
					}

					//console.log(ip_log_seq, post_data);

					$.ajax({
						url: '<?=$item_directory ?>/item_list_ajax.php',
						data: post_data,
						type: 'POST',
						dataType: 'JSON',
						success: function(data) {
							if(data.code == "000000"){
								//console.log(data.data);
								var html = '';
								if(data.data && data.data.length > 0){
									$.each(data.data, function(i, v){
										$("#admin_item_payment_log .order_list_wrap .product_img[data-ip_log_seq='"+ip_log_seq+"']").attr('data-id', v.product_no);
										get_item_image('.product_img', v.product_no);
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
			}
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
							//console.log(data.data);
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
										//console.log(target, product_no);
										$("#admin_item_payment_log .order_list_wrap").find(target+"[data-id='"+product_no+"']").css("background-image", "url('"+v.file_path+"')");
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
				if(goodsRepImage && goodsRepImage != ""){
					$("#admin_item_payment_log .order_list_wrap").find(target+"[data-id='"+product_no+"']").css("background-image", "url('"+goodsRepImage+"')");
				}else{
					$("#admin_item_payment_log .order_list_wrap").find(target+"[data-id='"+product_no+"']").css("background-image", "url('../images/product_img.png')");
				}
			}
		});
	}

	function get_item_payment_cnt(){
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "get_item_payment_cnt",
				is_shop : "1"
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					$("#admin_item_payment_log .total_cnt").text(data.data+"건");
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
				is_shop : "1"
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					$("#admin_item_payment_log .cancel_cnt").text(data.data+"건");
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