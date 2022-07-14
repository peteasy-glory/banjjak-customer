<?php
include "../include/top.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

$r_rank_one = ($_POST["rank_one"] && $_POST["rank_one"] != "")? $_POST["rank_one"] : "";
$r_rank_two = ($_POST["rank_two"] && $_POST["rank_two"] != "")? $_POST["rank_two"] : "";
$r_rank_three = ($_POST["rank_three"] && $_POST["rank_three"] != "")? $_POST["rank_three"] : "";
$r_rank_four = ($_POST["rank_four"] && $_POST["rank_four"] != "")? $_POST["rank_four"] : "";
$r_rank_five = ($_POST["rank_five"] && $_POST["rank_five"] != "")? $_POST["rank_five"] : "";
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

?>

<script src="<?= $js_directory ?>/fontawesome.min.js"></script>
<style>
	ul { list-style: none; padding: 0px; margin: 0px; }
	.bjj_top_menu { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; border-bottom: 1px solid #ccc; background-color: rgba(255, 255, 255, 0.8); margin-top: env(safe-area-inset-top); margin-top: constant(safe-area-inset-top); z-index: 10; }
	.bjj_top_menu .bjj-back-btn { position: absolute; left: 8px; top: 12px; }
	.bjj_top_menu .bjj-back-btn img { width: 26px; vertical-align: middle; }
	.bjj_top_menu .bjj_top_title { width: 100%; height: 50px; line-height: 50px; text-align: center; padding: 0px; }
	.bjj_top_menu .bjj_top_title p { padding: 0px; margin: 0px; }

	#item_list { position: relative; width: 100%; margin-top: calc(env(safe-area-inset-top) + 61px); margin-top: calc(constant(safe-area-inset-top) + 61px); }
	#item_list .top_wrap { position: relative; padding: 10px; }
	#item_list .top_wrap button.add_jb_item_btn { height: 40px; line-height: 40px; border: 1px solid #047C3C; background-color: #047C3C; color: #fff; border-radius: 5px; padding: 0px 10px; }
	#item_list .top_wrap button.add_item_btn { position: absolute; right: 10px; top: 10px; height: 40px; line-height: 40px; border: 1px solid #f5bf2e; background-color: #f5bf2e; color: #fff; border-radius: 5px; padding: 0px 10px; }
	#item_list .search_wrap { margin-bottom: 20px; padding: 10px; border: 1px solid #ccc; }
	#item_list .search_wrap .title { font-size: 12px; color: #999; padding-bottom: 5px; }
	#item_list .search_wrap ul li { padding: 5px 0px; }
	#item_list .search_wrap input[type='text'] { height: 30px; padding: 0px 10px; border: 0px; border-bottom: 1px solid #ccc; }
	#item_list .search_wrap input[name='search_word'] { width: calc(100% - 20px); }
	#item_list .search_wrap input[type='checkbox'] { display: none; width: 0px; height: 0px; font-size: 0px; }
	#item_list .search_wrap input[type='checkbox']+label { display: inline-block; height: 30px; line-height: 30px; padding: 0px 10px; border: 1px solid #ccc; background-color: #eee; text-align: center; border-radius: 5px; font-size: 14px; }
	#item_list .search_wrap input[type='checkbox']:checked+label { background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
	#item_list .search_wrap select { height: 30px; padding: 0px 10px; border: 1px solid #ccc; min-width: 60px; background-color: #fff; }
	#item_list .search_wrap .btn_wrap { text-align: right; margin-top: 10px; }
	#item_list .search_wrap .btn_wrap button { height: 30px; padding: 0px 10px; border: 1px solid #ccc; border-radius: 5px; background-color: #eee; }
	#item_list .info { font-size: 12px; color: #999; padding: 5px; }
	#item_list table { border-collapse: collapse; width: 100%; margin: 0 auto; text-align: center; font-size: 14px; font-family: 'NL2GR'; }
	#item_list table caption { text-align: left; }
	#item_list table tr th { background-color: #e9e9e9; padding: 5px; border: 1px solid #eee; font-weight: Bold; white-space: nowrap; }
	#item_list table tr td { position: relative; padding: 2px 5px; border: 1px solid #eee; font-size: 14px; vertical-align: top; font-weight: normal; }
	#item_list table tr td.not_view { background-color: #999; color: #ccc; }
	#item_list table tr td.lft { text-align: left; }
	#item_list table tr td.rgt { text-align: right; }
	#item_list table tr td.item_write_btn { cursor: pointer; }
	#item_list table tr td.item_price { line-height: 24px; white-space: nowrap; }
	#item_list table tr td.soldout { color: #f00; }
	#item_list table tr td.no_data { background-color: #f9f9f9; padding: 50px 0px; }
	#item_list table tr td.item_image { vertical-align: middle; }
	#item_list table tr td a.product_link { display: flex; justify-content: center; align-items: center; border: 1px solid #ccc; border-radius: 5px; background-color: #eee; text-align: center; width: auto; height: 30px; line-height: 30px; text-decoration: none; color: #000; }
	#item_list table tr td ul.table { width: 100%; display: table; }
	#item_list table tr td ul.table li { display: table-cell; border: 1px solid #ccc; vertical-align: top; }
	#item_list table tr td ul.table li:first-child { width: 32px; }
	#item_list table tr td ul.table li:last-child { width: calc(100% - 32px); }
	#item_list table tr td div { width: 100%; }
	#item_list table tr td div.img { width: 40px; height: 40px; border: 1px solid #eee; background-color: #eee; background-size: cover; background-repeat: no-repeat; background-position: center; }
	#item_list table tr td div.img img { width: 100%; }
	#item_list table tr td .option_list { position: absolute; right: 5px; top: 33px; width: auto; background-color: #fff; padding: 10px; border: 1px solid #ccc; border-radius: 10px; z-index: 1; white-space: nowrap; }
	#item_list table tr td .product_no { font-size: 12px; color: #999; }
	#item_list table tr td .item_name { font-weight: Bold; }
	#item_list table tr td .view_tag { display: inline-block; border: 1px solid #f5bf2e; color: #f5bf2e; padding: 0px 5px; margin: 2px 4px 2px 0px; min-width: 40px; text-align: center; white-space: nowrap; font-size: 12px; font-weight: Bold; }
	#item_list table tr td button { border: 1px solid #ccc; border-radius: 5px; background-color: #eee; height: 30px; padding: 0px 10px; white-space: nowrap; }
	#item_list .more { text-align: center; padding: 10px; }
	#item_list .more button { width: 100%; height: 50px; line-height: 50px; background-color: #eee; border: 1px solid #ccc; border-radius: 10px; display: none; }

	@media screen and (min-width: 720px) {
		body { background-color: #fff; }
	}

	.pop_search th{width: 100px; height:20px;}
	.pop_search td input{ width: calc(100% - 20px); }
	.search_btn1{border: 1px solid #ccc; border-radius: 5px; background-color: #eee; height: 30px; padding: 0px 10px; white-space: nowrap;}
	.banner_img { width: 80px; height: 80px; border: 1px solid #eee; background-color: #eee; border-radius: 10px; background-size: cover; background-repeat: no-repeat; background-position: center; }
	.banner_list table, td, th {
	  border : 1px solid #F4F4F4;
	  border-collapse : collapse;
	}
	.banner_list table tr th {background-color: #e9e9e9; height: 30px;}
</style>

<div class="bjj_top_menu">
    <div class="bjj-back-btn"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/btn_back_2.png"></a></div>
    <div class="bjj_top_title"><p> 전문몰 인기 검색어 관리</p></div>
</div>

<div id="item_list"></div>
<div id="beauty_item_payment"></div>
<div class="btn_wrap">
	<button type="button" class="search_btn1">검색어 수정</button>
	<br><br><hr><br>
</div>

<script>

var $beauty_item_payment = $("#item_list");
var $main_banner = $("#main_banner");
var $search_banner = $("#search_banner");
	

	$(function(){
		init_html()
			.then(get_beauty_list);
	});

	function init_html(){
		return new Promise(function(resolve, reject) {
			var html = '';

			html += '<div class="item_beauty_list">';
			html += '	<div>';
			html += '		<div class="title"></div>';
			html += '		<div class="search_wrap">';
			html += '			<table class="pop_search">';
			html += '				<thead>';
			html += '					<tr><th>인기검색어 순위</th><th>검색어</th></tr>';
			html += '				</thead>';
			html += '				<tbody>';
			html += '				</tbody>';
			html += '			</table>';
			html += '		</div>';
			html += '	</div>';
			html += '</div>';
			$beauty_item_payment.html(html);
			resolve();
		});
	}

	
	// 검색어 불러오기
	function get_beauty_list(){
		return new Promise(function(resolve, reject) {

			$.ajax({
				url: 'item_search_manage_ajax.php',
				data: {
					mode : "get_rank_partner"
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						var rebuy = 0;
						var html = '';

						if(data.data && data.data.length > 0){
							$.each(data.data, function(i, v){
								html += '			<tr>';
								html += '				<td>'+v.num+'위</td>';
								html += '				<td><input class="beauty_cnt" type="text" name="rank_'+v.num+'" id="rank_'+v.num+'" value="'+v.search+'"></td>';
								html += '			</tr>';
								
								if(i > 0){
									rebuy += Number(v.search);
								}
							});
							$beauty_item_payment.find('.item_beauty_list tbody').append(html);
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

	// 검색어 수정 버튼 이벤트
	$(".btn_wrap").on("click", ".search_btn1", function(){
		var rank_one = document.getElementById('rank_1').value;
		var rank_two = document.getElementById('rank_2').value;
		var rank_three = document.getElementById('rank_3').value;
		var rank_four = document.getElementById('rank_4').value;
		var rank_five = document.getElementById('rank_5').value;
		$.ajax({
			url: 'item_search_manage_ajax.php',
			data: {
				mode : "change_rank_partner",
				rank_one : rank_one,
				rank_two : rank_two,
				rank_three : rank_three,
				rank_four : rank_four,
				rank_five : rank_five
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				location.href = "item_search_manage_shop.php";
			},
			error: function(xhr, status) {
//				location.href = "item_search_manage.php";
				 alert(xhr + " : " + status);
			}

		});
	});
	
	
</script>

<?php
    include "../include/bottom.php";
?>
