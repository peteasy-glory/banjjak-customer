<?php
include "../include/top.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

$backurl = $_GET["backurl"];
$r_cate = ($_GET["cate"] && $_GET["cate"] != "")? $_GET["cate"] : "";
$r_page = ($_GET["page"] && $_GET["page"] != "")? $_GET["page"] : "";
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
	.scroll_top { display: none; position: fixed; right: 10px; bottom: 10px; z-index: 1; width: 50px; height: 50px; background-color: rgba(255, 255, 255, 0.6); border: 1px solid #ccc; border-radius: 25px; display: none; -webkit-align-items: center; -webkit-justify-content: center; }
	.scroll_top.on { display: flex; }
	
	ul { list-style: none; padding: 0px; margin: 0px; }
	input[type="text"] { border: 0px; border-bottom: 1px solid #ccc; background-color: transparent; padding: 5px; margin: 0px; height: 24px; }
	select { border: 1px solid #ccc; background-color: transparent; padding: 5px; margin: 0px; height: 30px; }

	#item_partner_list { position: relative; width: 100%; margin-top: calc(env(safe-area-inset-top) + 61px); margin-top: calc(constant(safe-area-inset-top) + 61px); }
	#item_partner_list .item_partner_list { width: calc(100% - 20px); margin: 10px auto; }
	#item_partner_list table { width: 100%; font-family: 'NL2GR'; font-weight: normal; font-size: 14px; border-collapse: collapse; border: 1px solid #eee; }
	#item_partner_list table thead tr th { position: sticky; top: 50px; }
	#item_partner_list table tbody tr:hover { background-color: #eee; cursor: pointer; }
	#item_partner_list table tr th { padding: 5px; border-bottom: 1px solid #ccc; background-color: #eee; }
	#item_partner_list table tr td { padding: 5px; text-align: center; border-bottom: 1px solid #eee; }
	#item_partner_list .btn_wrap { width: calc(100% - 20px); margin: 0 auto; }
	#item_partner_list .btn_wrap button { width: 100%; height: 40px; padding: 0px 10px; border: 1px solid #ccc; background-color: #eee; border-radius: 10px; }
</style>

<div class="bjj_top_menu">
	<?php if($backurl){ ?>
    <div class="bjj-back-btn"><a href="<?= $backurl ?>"><i class="fas fa-chevron-left"></i></a></div>
	<?php }else{ ?>
	<div class="bjj-back-btn"><a href="<?= $admin_directory ?>/"><i class="fas fa-chevron-left"></i></a></div>
	<?php } ?>
	<div class="bjj_top_title"><p>파트너 리스트</p></div>
	<div class="bjj-insert-btn"><button type="button" class="move_write_item_partner_btn">파트너 등록</button></div>
</div>
<div class="scroll_top"><i class="fas fa-chevron-up"></i></div>
<div id="item_partner_list"></div>

<script>
	var $item_partner_list = $("#item_partner_list");
	var page = ("<?=$r_page ?>" != "")? parseInt("<?=$r_page?>") : 1; // 현재 페이지(1페이지당 100개)
	var total_cnt = 0; // 총 상품 수
	var total_page_cnt = 10; // 총 페이지 수
	var category = "<?=$r_cate ?>"; // 검색 - 카테고리 후 array로 변경
	var lastScrollTop = 0;

	$(function(){
		init_html()
			.then(get_item_partner_list);
	});

	$(document).on('click', '.move_write_item_partner_btn', function(){
		var _ip_seq = $(this).data('seq');
		_ip_seq = (typeof _ip_seq != 'undefined' && _ip_seq != '')? '?seq='+_ip_seq+'&' : '?';
		location.href = '<?=$admin_directory ?>/item_partner_write.php'+_ip_seq+'backurl='+encodeURIComponent(window.location.pathname);
	});

	$(document).on('click', '.more_btn', function(){
		total_cnt += total_page_cnt;
		get_item_partner_list();
	});

	function init_html(){
		return new Promise(function(resolve, reject) {
			$(".bjj_top_menu .bjj-insert-btn").remove();
			$(".bjj_top_menu").append('<div class="bjj-insert-btn"><button type="button" class="move_write_item_partner_btn">파트너 등록</button></div>');
			var html = '';

			html += '<div class="item_partner_list">';
			html += '	<table>';
			html += '		<thead>';
			html += '			<tr>';
			html += '				<th>No</th>';
			html += '				<th>담당자ID</th>';
			html += '				<th>파트너명</th>';
			html += '			</tr>';
			html += '		</thead>';
			html += '		<tbody>';
			html += '		</tbody>';
			html += '	</table>';
			html += '</div>';

			$item_partner_list.append(html);
			resolve();
		});
	}

	function get_item_partner_list(){
		return new Promise(function(resolve, reject) {
			$item_partner_list.find('.btn_wrap').remove();

			$.ajax({
				url: '<?=$partner_directory ?>/partner_ajax.php',
				data: {
					mode : "get_item_partner_list",
					limit_0 : total_cnt,
					limit_1 : total_page_cnt
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
						var html = '';

						if(data.data && data.data.length > 0){
							$.each(data.data, function(i, v){
								html += '			<tr class="move_write_item_partner_btn" data-seq="'+v.ip_seq+'">';
								html += '				<td>'+v.ip_seq+'</td>';
								html += '				<td>'+v.partner_id+'</td>';
								html += '				<td>'+v.company_name+'</td>';
								html += '			</tr>';
							});
							$item_partner_list.find('.item_partner_list tbody').append(html);

							if(data.data.length == total_page_cnt){
								$item_partner_list.append('<div class="btn_wrap"><button type="button" class="more_btn">더보기</button></div>');
							}
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
