<?php
include "../include/top.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

$backurl = $_GET["backurl"];
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
?>

<script src="../js/fontawesome.min.js"></script>
<style>
	.bjj_top_menu { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; border-bottom: 1px solid #ccc; background-color: #fff; z-index: 10; }
	.bjj_top_menu .bjj-back-btn { position: absolute; left: 0px; top: 0px; display: flex; justify-content: center; align-items: center; width: 50px; height: 50px; font-size: 24px; }
	.bjj_top_menu .bjj-back-btn a { color: #000; }
	/*.bjj_top_menu .bjj-back-btn img { width: 26px; vertical-align: middle; }*/
	.bjj_top_menu .bjj_top_title { width: 100%; height: 50px; line-height: 50px; text-align: center; padding: 0px; color: #000; }
	.bjj_top_menu .bjj_top_title p { padding: 0px; margin: 0px; }
	.bjj_top_menu .bjj-insert-btn { position: absolute; right: 0px; top: 0px; }
	.bjj_top_menu .bjj-insert-btn button { height: 30px; padding: 0px 10px; margin: 10px; border: 1px solid #f5bf2e; background-color: #fff; color: #f5bf2e; border-radius: 5px; }

	#beauty_item_payment { margin: 60px 0px; }
	#beauty_item_payment ul { list-style: none; margin: 0px; padding: 0px; }
	#beauty_item_payment ul li { padding: 0px 10px; }
</style>

<div class="bjj_top_menu">
	<?php if($backurl){ ?>
    <div class="bjj-back-btn"><a href="<?= $backurl ?>"><i class="fas fa-chevron-left"></i></a></div>
	<?php }else{ ?>
	<div class="bjj-back-btn"><a href="<?= $admin_directory ?>/"><i class="fas fa-chevron-left"></i></a></div>
	<?php } ?>
	<div class="bjj_top_title"><p>정회원 쇼핑구매통계 조회</p></div>
	<div class="bjj-insert-btn"></div>
</div>
<div id="beauty_item_payment"></div>

<script>
	var $beauty_item_payment = $("#beauty_item_payment");

	$(function(){
		init_html()
			.then(get_beauty_item_payment)
			.then(html_table)
			.then(get_beauty_list);
	});

	function init_html(){
		return new Promise(function(resolve, reject) {
			var html = '';

			html += '<div class="get_beauty_item_payment_wrap">';
			html += '	<div>';
			html += '		<div class="title"></div>';
			html += '		<div class="content">';
			html += '			<ul>';
			html += '				<li>미용이용 누적 회원 수 : <span class="beauty_cnt">0</span>명 / 미용이용 누적 건 수 : <span class="beauty_total_cnt">0</span>건</li>';
			html += '				<li>상품결제 누적 회원 수 : <span class="item_cnt">0</span>명 / 상품결제 누적 건 수 : <span class="item_total_cnt">0</span>건</li>';
			html += '				<li>상품 구매자 중 미용 이용 회원 수 : <span class="beauty_item_cnt">0</span>명 (<span class="beauty_item_percent">0</span>%)</li>';
			html += '				<li>상품 구매자 중 재구매 수 : <span class="rebuy_cnt">0</span>명 (<span class="rebuy_percent">0</span>%)</li>';
			html += '			</ul>';
			html += '		</div>';
			html += '	</div>';
			html += '</div>';
			$beauty_item_payment.html(html);
			resolve();
		});
	}

	function get_beauty_item_payment(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				//url: '<?=$item_directory ?>/item_list_ajax.php',
				url: '../test/test_item_list_ajax.php',
				data: {
					mode : "get_beauty_item_payment"
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);

						$beauty_item_payment.find(".beauty_cnt").html(data.data.beauty_cnt);
						$beauty_item_payment.find(".beauty_total_cnt").html(data.data.beauty_total_cnt);
						$beauty_item_payment.find(".item_cnt").html(data.data.item_cnt);
						$beauty_item_payment.find(".item_total_cnt").html(data.data.item_total_cnt);
						$beauty_item_payment.find(".item_cnt").attr("data-cnt", data.data.item_cnt);
						$beauty_item_payment.find(".beauty_item_cnt").html(data.data.beauty_item_cnt);
						$beauty_item_payment.find(".beauty_item_percent").html((Math.round(data.data.beauty_item_cnt / data.data.item_cnt * 100 * 10)/10));
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

	function html_table(){
		return new Promise(function(resolve, reject) {
			var html = '';

			html += '<div class="item_beauty_list">';
			html += '	<table>';
			html += '		<thead>';
			html += '			<tr>';
			html += '				<th>주문건수</th>';
			html += '				<th>회원수</th>';
			html += '			</tr>';
			html += '		</thead>';
			html += '		<tbody>';
			html += '		</tbody>';
			html += '	</table>';
			html += '</div>';

			$beauty_item_payment.append(html);
			resolve();
		});
	}

	function get_beauty_list(){
		return new Promise(function(resolve, reject) {

			$.ajax({
				url: '../test/test_item_list_ajax.php',
				data: {
					mode : "get_beauty_list"
				},
				type: 'POST',
				dataType: 'JSON',
				beforeSend: function(){
					$("#loading").css("display", "flex");
				},
				success: function(data) {
					$("#loading").css("display", "none");
					if(data.code == "000000"){
						console.log(data.data);
						var rebuy = 0;
						var html = '';

						if(data.data && data.data.length > 0){
							$.each(data.data, function(i, v){
								html += '			<tr>';
								html += '				<td>'+v.cnt+'건</td>';
								html += '				<td>'+v.cnt_payment+'명</td>';
								html += '			</tr>';
								
								if(i > 0){
									rebuy += Number(v.cnt_payment);
								}
							});
							$beauty_item_payment.find('.item_beauty_list tbody').append(html);
							$beauty_item_payment.find(".rebuy_cnt").html(rebuy);
							$beauty_item_payment.find(".rebuy_percent").html((Math.round(rebuy / $beauty_item_payment.find(".item_cnt").data("cnt") * 100 * 10)/10));
							console.log();

						}else{
							//html += '			<tr>';
							//html += '				<td colspan="3">';
							//html += '					<div class="no_data">등록된 파트너가 없습니다.</div>';
							//html += '				<td>';
							//html += '			</tr>';
							//$item_partner_list.append(html);
						}
						
						resolve();
					}else{
						alert(data.data+"("+data.code+")");
						console.log(data.code);
					}
				},
				error: function(xhr, status, error) {
					$("#loading").css("display", "none");
					//alert(error + "네트워크에러");
					if(xhr.status != 0){
						alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
					}
				}
			});
		});
	}
</script>