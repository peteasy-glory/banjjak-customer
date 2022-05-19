<?php
include "../include/top.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

$r_cate = ($_GET["cate"] && $_GET["cate"] != "")? $_GET["cate"] : "";
$r_supp = ($_GET["supp"] && $_GET["supp"] != "")? $_GET["supp"] : "";
$r_word = ($_GET["word"] && $_GET["word"] != "")? $_GET["word"] : "";
$r_sold = ($_GET["sold"] && $_GET["sold"] != "")? $_GET["sold"] : "";
$r_page = ($_GET["page"] && $_GET["page"] != "")? $_GET["page"] : "";
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

$ivm_arr = array();
for($_i = 1; $_i <= 6; $_i++){
	if($r_ivm1 != ""){
		$ivm_arr[$_i] = substr($r_ivm1, ($_i - 1), 1);
	}else{
		$ivm_arr[$_i] = 0;
	}
}
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
	#item_list .top_wrap { position: relative; padding: 10px; min-height: 40px; }
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
	#item_list table tr td a.product_link { display: inline-block; padding: 5px; border: 1px solid #ccc; border: 1px solid #ccc; border-radius: 5px; background-color: #eee; text-align: center; width: 20px; height: 20px; line-height: 20px; text-decoration: none; color: #000; }
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

</style>

<div class="bjj_top_menu">
    <div class="bjj-back-btn"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/btn_back_2.png"></a></div>
    <div class="bjj_top_title"><p>반짝 전문몰 상품 리스트</p></div>
</div>

<div id="item_list">
	<div class="top_wrap">
		<button class="add_item_btn">상품추가</button>
	</div>
	<div class="search_wrap">
		<ul>
			<li>
				<div class="title">카테고리</div>
				<select class="cate_1">
					<option value="">선택</option>
				</select>
				<!--
				<select class="cate_2">
					<option value="">선택</option>
				</select>
				<select class="cate_3">
					<option value="">선택</option>
				</select>
				-->
				<input type="hidden" name="ic_seq_list" value="<?=$r_cate ?>" />
			</li>
			<li>
				<div class="title">외부상품여부</div>
				<input type="checkbox" id="search_is_supply_1" name="search_is_supply" value="1" <?=($r_supp == "" || $r_supp == "1")? "checked" : "" ?> />
				<label for="search_is_supply_1">외부상품</label>
				<input type="checkbox" id="search_is_supply_2" name="search_is_supply" value="2" <?=($r_supp == "" || $r_supp == "2")? "checked" : "" ?> />
				<label for="search_is_supply_2">내부상품</label>
			</li>
			<li>
				<div class="title">품절여부</div>
				<select name="search_is_soldout">
					<option value="">선택</option>
					<option value="1" <?=($r_sold == "1")? "selected" : "" ?>>판매가능</option>
					<option value="2" <?=($r_sold == "2")? "selected" : "" ?>>품절</option>
				</select>
			</li>
			<li>
				<div class="title">검색</div>
				<input type="text" name="search_word" value="<?=$r_word ?>" placeholder="상품명, 상품번호, 브랜드명 등" />
			</li>
		</ul>
		<div class="btn_wrap">
			<button type="button" class="search_btn"><i class="fas fa-search"></i> 검색</button>
			<button type="button" class="search_reset_btn"><i class="fas fa-redo"></i> 초기화</button>
		</div>
	</div>
	<div class="info"> * 상품의 No를 클릭하시면 상품판매 페이지(새창)로 이동합니다.</div>
	<table>
		<caption>
		</caption>
		<colgroup>
			<col width="5%" />
			<col width="1%" />
			<col width="*" />
			<col width="1%" />
		</colgroup>
		<tr>
			<th>No</th>
			<th>사진</th>
			<th>이름</th>
			<th>금액</th>
		</tr>
		<tbody class="item_data_list">
			<!--tr>
				<td colspan="4" class="no_data">등록된 상품이 없습니다.</td>
			</tr-->
		</tbody>
	</table>
	<div class="more">
		<button class="more_btn">더보기</button>
	</div>
</div>

<script>
var item_list_flag = 0;		 // 현재 표시된 갯수 묶음
var item_list_page_cnt = ("<?=$r_page ?>" != "")? parseInt("<?=$r_page?>") : 10; // 화면에 표시될 아이템 갯수
var search_cate = '<?=$r_cate ?>';
var search_supp = '<?=$r_supp ?>';
var search_word = '<?=$r_word ?>';
var search_sold = '<?=$r_sold ?>'; // soldout
var lastScrollTop = 0;
var page_loaded = false;

$(document).ready(function() {
	get_item_list(search_cate, search_supp, search_word, search_sold);
	search_cate1_html();
	search_cate_init();
	page_loaded = (!page_loaded)? true : false;
});

$(window).on('popstate', function(event) {
	if(page_loaded){
		window.location = document.location.href;
	}
});

$(document).on("click", "#item_list .more_btn", function(){
	if(item_list_page_cnt == 10){
		item_list_flag += item_list_page_cnt;
	}else{
		item_list_flag = item_list_page_cnt;
		item_list_page_cnt = 10;
	}
	history.replaceState('', '', window.location.pathname+'?cate='+search_cate+'&supp='+search_supp+'&word='+search_word+'&sold='+search_sold+'&page='+(item_list_flag+item_list_page_cnt));
	get_item_list(search_cate, search_supp, search_word, search_sold);
});

$(document).on("click", "#item_list .add_item_btn", function(){
	location.href = "<?=$admin_directory?>/item_special_mall_write.php?backurl=<?= urlencode($_SERVER['REQUEST_URI']) ?>";
});

$(document).on("keyup", "#item_list .search_wrap input[name='search_word']", function(e){
	if($(this).val() != "" && e.keyCode == "13"){
		item_search();
	}
});

$(document).on("click", "#item_list .search_wrap .search_btn", function(){
	item_search();
});

function item_search(){
	search_cate = $(document).find("#item_list .search_wrap input[name='ic_seq_list']").val();
	search_supp = $(document).find("#item_list .search_wrap input[name='search_is_supply']:checked").val();
	search_word = $(document).find("#item_list .search_wrap input[name='search_word']").val();
	search_sold = $(document).find("#item_list .search_wrap select[name='search_is_soldout'] option:selected").val();

	item_list_flag = 0;
	item_list_page_cnt = 10;

	var _chk = 0;
	$(document).find("#item_list .search_wrap input[name='search_is_supply']").each(function(i, v){
		if($(this).is(":checked") == true){
			search_supp = $(this).val();
			_chk++;
		}
	});
	if(_chk != 1){
		if(_chk > 1){ // 2
			search_supp = "";
		}else if(_chk < 1){ // 0
			search_supp = "0";
		}
	}
	
	$(".item_data_list").html('');
	history.pushState('', '', window.location.pathname+'?cate='+search_cate+'&supp='+search_supp+'&word='+search_word+'&sold='+search_sold+'&page='+(item_list_flag+item_list_page_cnt));
	get_item_list(search_cate, search_supp, search_word, search_sold);
}

$(document).on("click", "#item_list .search_wrap .search_reset_btn", function(){
	search_cate = '';
	search_supp = '';
	search_word = '';
	search_sold = '';
	$(document).find("#item_list .search_wrap .cate_1").val('');
	//$(document).find("#item_list .search_wrap .cate_2").html('<option value="">선택</option>');
	//$(document).find("#item_list .search_wrap .cate_3").html('<option value="">선택</option>');
	$(document).find("#item_list .search_wrap input[name='ic_seq_list']").val('');
	$(document).find("#item_list .search_wrap input[name='search_word']").val('');
	$(document).find("#item_list .search_wrap select[name='search_is_soldout']").val('');
	$(document).find("#item_list .search_wrap input[name='search_is_supply']").each(function(i, v){
		$(this).prop("checked", true);
	});

	$(".item_data_list").html('');
	history.pushState('', '', window.location.pathname);
	get_item_list(search_cate, search_supp, search_word, search_sold);
});

$(document).on("change", "#item_list .search_wrap .cate_1", function(){
	$(document).find("#item_list .search_wrap input[name='ic_seq_list']").val($(this).children("option:selected").val());
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_special_mall_cate2",
			cate1 : $(this).children("option:selected").val()
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				var html = '';
				html += '<option value="">선택</option>';
				$.each(data.data, function(i, v){			
					html += '<option value="'+v.ic_seq+'" data-path="'+v.node_path+'">'+v.cate_name+'</option>';
				});
				//$(document).find("#item_list .search_wrap .cate_2").html('').html(html);
				//$(document).find("#item_list .search_wrap .cate_3").html('<option value="">선택</option>');
			}else{
				alert(data.data+"("+data.code+")");
				console.log(data.code);
			}
		},
		error: function(xhr, status, error) {
			//alert(error + "네트워크에러");
		}
	});
});
/*
$(document).on("change", "#item_list .search_wrap .cate_2", function(){
	$(document).find("#item_list .search_wrap input[name='ic_seq_list']").val($(this).children("option:selected").val());
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_special_mall_cate3",
			cate2 : $(this).children("option:selected").val()
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				var html = '';
				html += '<option value="">선택</option>';
				$.each(data.data, function(i, v){			
					html += '<option value="'+v.ic_seq+'" data-path="'+v.node_path+'">'+v.cate_name+'</option>';
				});
				$(document).find("#item_list .search_wrap .cate_3").html('').html(html);
			}else{
				alert(data.data+"("+data.code+")");
				console.log(data.code);
			}
		},
		error: function(xhr, status, error) {
			//alert(error + "네트워크에러");
		}
	});
});

$(document).on("change", "#item_list .search_wrap .cate_3", function(){
	$(document).find("#item_list .search_wrap input[name='ic_seq_list']").val($(this).children("option:selected").val());
});
*/
function search_cate1_html(){
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_special_mall_cate1"
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				var html = '';
				html += '<option value="">선택</option>';
				$.each(data.data, function(i, v){			
					html += '<option value="'+v.ismc_seq+'" data-path="'+v.node_path+'">'+v.cate_name+'</option>';
				});
				$(document).find("#item_list .search_wrap .cate_1").html('').html(html);
			}else{
				alert(data.data+"("+data.code+")");
				console.log(data.code);
			}
		},
		error: function(xhr, status, error) {
			//alert(error + "네트워크에러");
		}
	});
}

function search_cate_init(){
	if(search_cate != ""){
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "get_special_mall_cate_list",
				cate : search_cate
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					$.each(data.data, function(i, v){
						var node_path = (v.node_path && v.node_path != "" && v.node_path.indexOf('^') != -1)? v.node_path.split('^') : "";
						$(document).find("#item_list .search_wrap .cate_1").val(node_path[0]);
						// cate2
						$.ajax({
							url: '<?=$item_directory ?>/item_list_ajax.php',
							data: {
								mode : "get_special_mall_cate2",
								cate1 : node_path[0]
							},
							type: 'POST',
							dataType: 'JSON',
							success: function(data) {
								if(data.code == "000000"){
									var html = '';
									html += '<option value="">선택</option>';
									$.each(data.data, function(i, v){			
										html += '<option value="'+v.ic_seq+'" data-path="'+v.node_path+'">'+v.cate_name+'</option>';
									});
									$(document).find("#item_list .search_wrap .cate_2").html('').html(html);
									$(document).find("#item_list .search_wrap .cate_2").val(node_path[1]);
									// cate3
									$.ajax({
										url: '<?=$item_directory ?>/item_list_ajax.php',
										data: {
											mode : "get_special_mall_cate3",
											cate2 : node_path[1]
										},
										type: 'POST',
										dataType: 'JSON',
										success: function(data) {
											if(data.code == "000000"){
												var html = '';
												html += '<option value="">선택</option>';
												$.each(data.data, function(i, v){			
													html += '<option value="'+v.ic_seq+'" data-path="'+v.node_path+'">'+v.cate_name+'</option>';
												});
												$(document).find("#item_list .search_wrap .cate_3").html('').html(html);
												$(document).find("#item_list .search_wrap .cate_3").val(node_path[2]);
											}else{
												alert(data.data+"("+data.code+")");
												console.log(data.code);
											}
										},
										error: function(xhr, status, error) {
											//alert(error + "네트워크에러");
										}
									});
								}else{
									alert(data.data+"("+data.code+")");
									console.log(data.code);
								}
							},
							error: function(xhr, status, error) {
								//alert(error + "네트워크에러");
							}
						});
					});
				}else{
					alert(data.data+"("+data.code+")");
					console.log(data.code);
				}
			},
			error: function(xhr, status, error) {
				//alert(error + "네트워크에러");
			}
		});
	}
}

function get_item_list(search_cate, search_supp, search_word, search_sold){
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_item_list",
			search_cate : search_cate,
			search_supp : search_supp,
			search_word : search_word,
			search_sold : search_sold,
			is_shop : "1",
			flag : item_list_flag,
			cnt  : item_list_page_cnt
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				var html = '';
				var idx = 0;
				$.each(data.data.list, function(i, v){
					var product_img = (v.product_img && v.product_img != "")? v.product_img : "dog_pet.png";
					var option_price = (v.option_price && v.option_price != "")? v.option_price : 0;
					var is_view = (v.is_view != "1")? "not_view" : "";
					var is_soldout = (v.is_soldout != "1")? "soldout" : "";

					html += '<tr class="item" data-seq="'+v.il_seq+'">';
					html += '	<td class="'+is_view+'"><a class="product_link" href="../test/test_item_product_page.php?no='+v.product_no+'&adn=1" target="_blank">'+v.il_seq+'</a></td>'; // 관리자로 볼때만 상품페이지 확인 가능하도록 adn=1 추가 + test 경로로 수정(적용후 경로 메인으로 변경해야함)
					html += '	<td class="item_image">';
					if(v.goodsRepImage && v.goodsRepImage != ""){
						html += '<div class="img" style="background-image: url(\''+v.goodsRepImage+'\');"></div>';
					}else{
						html += (data.data.file && typeof data.data.file[v.il_seq] != "undefined")? '<div class="img" style="background-image: url(\''+data.data.file[v.il_seq]+'\');"></div>' : '<div class="img"></div>';
					}
					html += '	</td>';
					html += '	<td class="lft item_write_btn '+is_soldout+'" data-seq="'+v.il_seq+'">';
					html += '		<div class="product_no">'+v.product_no+'</div>';
					html += '		<div class="item_name">'+v.product_name+'</div>';
					html += '	</td>';
					html += '	<td class="rgt item_price">'+v.product_price.format()+'원</td>';
					html += '</tr>';
					idx++;
				});
				$(".item_data_list").append(html);

				$.each(data.data.list, function(i, v){
					if(v.is_use_option == "1" && v.product_price == 0){
						get_item_option(v.il_seq);
					}
				});

				if(idx == 0){
					$("#item_list .more_btn").hide();
					if(item_list_flag == 0){
						html += '<tr>';
						html += '	<td colspan="4" class="no_data">등록된 상품이 없습니다.</td>';
						html += '</tr>';
					}
					$(".item_data_list").html('').html(html);
				}else if(idx >= item_list_page_cnt){
					$("#item_list .more_btn").show();
				}else{
					$("#item_list .more_btn").hide();
				}
				
				$("#item_list table caption").html("현재 상품 수 : "+data.data.list_cnt+"개 / 총 상품 수 : "+data.data.total_cnt+"개 ");
				$("#item_list .more_btn").text("더보기 ("+data.data.list_cnt+" / "+data.data.total_cnt+")");


				if(localStorage.getItem('windowscrolltop') > 0){
					$('html, body').animate({
						scrollTop: localStorage.getItem('windowscrolltop')
					}, 500, function(){
						localStorage.setItem('windowscrolltop', 0); // scroll position					
					});
				}
			}else{
				alert(data.data+"("+data.code+")");
				console.log(data.code);
			}
		},
		error: function(xhr, status, error) {
			//alert(error + "네트워크에러");
		}
	});
}

function get_item_option(il_seq){
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_item_option",
			il_seq: il_seq
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				var html = '';

				html += '<button type="button" class="toggle_btn" data-seq="'+il_seq+'">옵션 <i class="fas fa-toggle-on"></i></button>';
				html += '<div class="option_list" data-seq="'+il_seq+'" style="display: none;">';
				html += '	<ul>';
				$.each(data.data, function(i, v){
					html += '	<li>';
						html += '	<span class="option">'+v.option_name+' : '+v.sale_price.format()+' 원</span>';
					html += '	</li>';
				});
				html += '	</ul>';
				html += '</div>';

				$("#item_list .item_data_list tr.item[data-seq='"+il_seq+"'] .item_price").html(html);


			}else{
				alert(data.data+"("+data.code+")");
				console.log(data.code);
			}
		},
		error: function(xhr, status, error) {
			//alert(error + "네트워크에러");
		}
	});
}

$(document).on("click", "#item_list .toggle_btn", function(){
	$("#item_list .option_list[data-seq='"+$(this).data("seq")+"']").toggle();
});

$(document).on("click", "#item_list .item_write_btn", function(){
	localStorage.setItem('windowscrolltop',$(window).scrollTop()); // scroll position
	localStorage.setItem('itemlistpage',(item_list_flag+item_list_page_cnt)); // more_list_cnt
	history.replaceState('', '', window.location.pathname+'?cate='+search_cate+'&supp='+search_supp+'&word='+search_word+'&sold='+search_sold+'&page='+(item_list_flag+item_list_page_cnt));
	location.href = "<?=$admin_directory?>/item_special_mall_write.php?seq="+$(this).data("seq")+"&backurl="+encodeURIComponent(window.location.pathname+'?cate='+search_cate+'&supp='+search_supp+'&word='+search_word+'&sold='+search_sold+'&page='+(item_list_flag+item_list_page_cnt)+'&backurl=<?=urlencode($backurl) ?>');
});

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

<?php
    include "../include/bottom.php";
?>
