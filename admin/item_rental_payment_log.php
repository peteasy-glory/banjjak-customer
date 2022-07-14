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
    .top_title p { margin: 0px; padding: 0px; font-size:20px; }
    .header-back-btn { top: 13px; position: absolute; left: 10px; }
	#admin_item_payment_log { margin-top: 61px; }
	#admin_item_payment_log .search_wrap { line-height: 40px; padding: 5px; margin-bottom: 10px; border-bottom: 1px solid #f9f9f9; }
	#admin_item_payment_log .search_order_status { height: 30px; padding: 0px 10px; }
	#admin_item_payment_log .search_pay_status { height: 30px; padding: 0px 10px; }
	#admin_item_payment_log .search_item_list { max-width: 350px; height: 30px; padding: 0px 10px; }
	#admin_item_payment_log .search_customer_name { height: 30px; border: 1px solid #ccc; background-color: #fff; padding: 0px 10px; }
	#admin_item_payment_log .search_btn { border: 1px solid #ccc; background-color: #eee; height: 30px; padding: 0px 10px; border-radius: 5px; }
	#admin_item_payment_log .search_reset_btn { border: 1px solid #ccc; background-color: #eee; height: 30px; padding: 0px 10px; border-radius: 5px; }
	#admin_item_payment_log .old_item_payment_log_btn { border: 1px solid #ccc; background-color: #eee; height: 30px; padding: 0px 10px; border-radius: 5px; }
	#admin_item_payment_log .data_give_finish_0 { border: 1px solid #ccc; background-color: #eee; height: 30px; padding: 0px 10px; border-radius: 5px; }
	#admin_item_payment_log .data_give_finish_1 { border: none; background-color: #FAFAFA; color:#E6E6E6; height: 30px; padding: 0px 10px; border-radius: 5px; }
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

	#admin_item_payment_log .order_table tr.finish_1 {background-color:#F8E0E0; z-index:109;} 

	@media only screen and (max-width: 670px)  {
		#admin_item_payment_log .item_product_list { display: none; }
	}
	
</style>
<div id="admin_item_payment_log">
	<div class="top_menu">
		<div class="header-back-btn"><a href="/pet/admin/"><img src="/pet/images/btn_back_2.png" width="26px"></a></div>
        <div class="top_title">
            <p>상담신청 이력</p>
        </div>
    </div>


	<div class="order_box">
		<div>
			<table width="100%" class="order_table">
				<colgroup>
					<col width="5%">
					<col width="*">
					<col width="10%">
					<col width="15%">
					<col width="15%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th>사진</th>
						<th>상품명</th>
						<th>주문자명</th>
						<th>연락처</th>
						<th>신청일시</th>
						<th>전달완료처리</th>
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
	});

	$(document).on("click", "#admin_item_payment_log .more_btn", function(){
		item_list_flag += item_list_page_cnt;
		get_item_payment_log();
	});

	$(document).on("click", "#admin_item_payment_log .data_give_finish_0", function(){
		var ir_seq = $(this).data("seq");
		var customer_id = $(this).data("id");
		$.MessageBox({
			buttonDone: "완료",
			buttonFail: "취소",
			message: "전달완료처리를 하시겠습니까?"
		}).done(function() {
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "update_rental_info",
					ir_seq: ir_seq,
					customer_id: customer_id,
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						alert("완료!");
						location.reload();
					}else if(data.code == "000301"){
						$.MessageBox("실패!");
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
			var post_itemlistflag = localStorage.getItem('rentalitemlistflag');
			var post_item_list_flag = item_list_flag;
			var post_item_list_page_cnt = item_list_page_cnt;
			if(post_itemlistflag > 0){
				post_item_list_flag = 0;
				post_item_list_page_cnt = parseInt(item_list_page_cnt) + parseInt(post_itemlistflag);
				item_list_flag = parseInt(post_itemlistflag);
				localStorage.setItem('rentalitemlistflag', 0); // more_list_cnt
			}
			$.ajax({
				type: 'post',
				url: "<?=$item_directory ?>/item_list_ajax.php",
				data: {
					mode : "get_rental_info",
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

							html += '			<tr class="finish_'+v.data_give_finish+'">';
							html += '				<td><div class="product_img" data-id="'+v.product_no+'" style="background-image: url(\'/pet/images/product_img.png\'); "></div></td>';
							html += '				<td><div class="product_name" data-id="'+v.product_no+'"></div></td>';
							html += '				<td>'+v.name+'</td>';
							html += '				<td>'+v.cellphone+'</td>';
							html += '				<td>'+(v.reg_date).substring(0, (v.reg_date).length-3)+'</td>';
							html += '				<td><button type="button" class="data_give_finish_'+v.data_give_finish+'" data-seq="'+v.ir_seq+'" data-id="'+v.customer_id+'">완료</button></td>';
							html += '			</tr>';

//							item_list.push(v.product_no);
							get_item_image('.product_img', v.product_no);

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
								if(v.product_name != ""){
									$("#admin_item_payment_log .order_list_wrap").find(".product_name[data-id='"+v.product_no+"']").text(v.product_name);
								}else{
									$("#admin_item_payment_log .order_list_wrap").find(".product_name[data-id='"+product_no+"']").text("발신견주정보표시 서비스 신청");
								}
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
				mode : "get_item_payment_cnt"
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
				mode : "get_item_payment_cancel_cnt"
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