<?php
include "../include/top.php";

//$cl_result = include "../include/check_login.php";
//if ($cl_result == 0) {
//    return false;
//}

$r_cate = ($_GET["cate"] && $_GET["cate"] != "")? $_GET["cate"] : "";
$r_supp = ($_GET["supp"] && $_GET["supp"] != "")? $_GET["supp"] : "";
$r_word = ($_GET["word"] && $_GET["word"] != "")? $_GET["word"] : "";
$r_sold = ($_GET["sold"] && $_GET["sold"] != "")? $_GET["sold"] : "";
$r_ivm1 = ($_GET["ivm1"] && $_GET["ivm1"] != "")? $_GET["ivm1"] : "";
$r_page = ($_GET["page"] && $_GET["page"] != "")? $_GET["page"] : "";
$r_selected_sort = ($_GET['selected_sort'] && $_GET['selected_sort'] != "")? $_GET['selected_sort'] : "강아지";
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
<link rel="stylesheet" href="<?= $css_directory ?>/m_new_3.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new_3.css') ?>">
<link rel="stylesheet" href="<?= $css_directory ?>/m_layout.css?<?= filemtime($upload_static_directory . $css_directory . '/m_layout.css') ?>">
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


	#map { background:none!important; }
	.loading { position: absolute; left: 50%; top: 50%; width: 30px; height: 23px; margin-top: 7px; text-align: center; margin-left: -15px; margin-top: -15px; }
	.messagebox_overlay { z-index: 101; }
	.main-content .location_tab button img{width:25px;}
	.search_btn{margin-left:10px;margin-right:10px;width:27px;height:27px;text-indent:-99999px;background: url("/pet/images/search2.png") no-repeat center center;outline:none;border:none;}
	.more_btn{width:100%;}

</style>
<div id="main-content" style=" padding-bottom: calc(constant(safe-area-inset-bottom) + 40px); padding-bottom: calc(env(safe-area-inset-bottom) + 40px);">

	<div id="fixed-menu">
		<div class="top_menu">
			<div class="header-back-btn"><a href="<?= $mainpage_directory ?>/"><img src="/pet/images/btn_back_2.png" width="26px"></a></div>
			<div class="top_title">
				<p>상품 검색</p>
			</div>
			<div class="top_reflash"><a href="<?=$mainpage_directory?>/"><img src="../images/reflash.png"></a></div>
		</div>
	</div>
	<div class="location_tab" style="margin-top:58px;">
		<div class="search_box" style="position:fixed;left:0;top:0px;background:#fff;z-index:10;width:100%;">
			<form action="search_item_1.php" method="GET" style="">
				<input type="hidden" name="selected_sort" value="<?=$r_selected_sort ?>">
				<input type="text" name="word" id="search_name" placeholder="어떤 상품을 찾으세요?" value="" style="outline:none;border:none;margin-left:-10px;background:#efefef;padding:10px 0px 10px 10px;width:70%;">
<!-- 					<input type="button" value="검색" onclick="search_result()"> -->
				<input type="submit" class="search_btn" name="button" value="검색">
				<input type="button" value="닫기" onclick="location.href='index.php?tab=3&selected_sort=<?=$r_selected_sort ?>'" style="outline:none;border:none;">
			</form>
		</div>
	</div>

	<div id="event_7">
		<div id="event">
			<div class="item_list">
	<!-- 	<div id="item_list"> -->
	<!-- 	숨기기 시작	 -->
				<div style="display:none;">
					<div class="top_wrap">
						<button class="add_jb_item_btn">정글북리스트</button>
						<button class="add_item_btn">상품추가</button>
					</div>
					<div class="search_wrap">
						<ul>
							<li>
								<div class="title_old">카테고리</div>
								<select class="cate_1">
									<option value="">선택</option>
								</select>
								<select class="cate_2">
									<option value="">선택</option>
								</select>
								<select class="cate_3">
									<option value="">선택</option>
								</select>
								<input type="hidden" name="ic_seq_list" value="<?=$r_cate ?>" />
							</li>
							<li>
								<div class="title_old">외부상품여부</div>
								<input type="checkbox" id="search_is_supply_1" name="search_is_supply" value="1" <?=($r_supp == "" || $r_supp == "1")? "checked" : "" ?> />
								<label for="search_is_supply_1">외부상품</label>
								<input type="checkbox" id="search_is_supply_2" name="search_is_supply" value="2" <?=($r_supp == "" || $r_supp == "2")? "checked" : "" ?> />
								<label for="search_is_supply_2">내부상품</label>
							</li>
							<li class="search_is_view_main">
								<div class="title_old">메인상품노출여부</div>
								<input type="checkbox" id="search_is_view_main_1" name="search_is_view_main_1" value="1" <?=($ivm_arr[1] == "1")? "checked" : "" ?> />
								<label for="search_is_view_main_1">메인MD</label>
								<input type="checkbox" id="search_is_view_main_2" name="search_is_view_main_2" value="2" <?=($ivm_arr[2] == "1")? "checked" : "" ?> />
								<label for="search_is_view_main_2">메인NEW</label>
								<input type="checkbox" id="search_is_view_main_3" name="search_is_view_main_3" value="3" <?=($ivm_arr[3] == "1")? "checked" : "" ?> />
								<label for="search_is_view_main_3">쇼핑MD</label>
								<input type="checkbox" id="search_is_view_main_4" name="search_is_view_main_4" value="4" <?=($ivm_arr[4] == "1")? "checked" : "" ?> />
								<label for="search_is_view_main_4">쇼핑NEW</label>
								<input type="checkbox" id="search_is_view_main_5" name="search_is_view_main_5" value="5" <?=($ivm_arr[5] == "1")? "checked" : "" ?> />
								<label for="search_is_view_main_5">베스트</label>
								<input type="checkbox" id="search_is_view_main_6" name="search_is_view_main_6" value="6" <?=($ivm_arr[6] == "1")? "checked" : "" ?> />
								<label for="search_is_view_main_6">인기</label>
							</li>
							<li>
								<div class="title_old">품절여부</div>
								<select name="search_is_soldout">
									<option value="">선택</option>
									<option value="1" <?=($r_sold == "1")? "selected" : "" ?>>판매가능</option>
									<option value="2" <?=($r_sold == "2")? "selected" : "" ?>>품절</option>
								</select>
							</li>
						</ul>
					</div>
				</div>
	<!-- 		숨기기 끝 -->
		<!-- 	<div class="btn_wrap"> -->
		<!-- 		<div class="title">검색</div> -->
		<!-- 		<input type="text" name="search_word" value="<?=$r_word ?>" placeholder="상품명, 상품번호, 브랜드명 등" /> -->
		<!-- 		<button type="button" class="search_btn"><i class="fas fa-search"></i> 검색</button> -->
		<!-- 		<button type="button" class="search_reset_btn"><i class="fas fa-redo"></i> 초기화</button> -->
		<!-- 	</div> -->
			<br>
	<!-- 		<div class="item_data_list"> * 상품의 No를 클릭하시면 상품판매 페이지(새창)로 이동합니다.</div> -->
			
				<div class="title"><span>검색결과</span></div>
				<div class="caption">
					<div class="total_cnt">0개의 상품</div>
					<div class="order"></div>
				</div>
				<div class="new_item_wrap">
				</div>
				<div class="more">
					<button class="more_btn">더보기</button>
				</div>
	<!-- 	</div> -->
			</div>
		</div>
	</div>

</div>
<script>
var item_list_flag = 0;		 // 현재 표시된 갯수 묶음
var item_list_page_cnt = ("<?=$r_page ?>" != "")? parseInt("<?=$r_page?>") : 10; // 화면에 표시될 아이템 갯수
var search_cate = '<?=$r_cate ?>';
var search_supp = '<?=$r_supp ?>';
var search_word_before = '<?=$r_word ?>';
var search_word = search_word_before.replace(/ /gi, '%\' and product_name like \'%'); // 띄어쓰기를 %로 변경하여 다중 검색 가능
var search_sold = '<?=$r_sold ?>'; // soldout
var search_ivm1 = JSON.parse('<?=json_encode($ivm_arr) ?>');
	search_ivm1 = $.map(search_ivm1, function(e){ return e; }).join('');
var lastScrollTop = 0;
var page_loaded = false;

$(document).ready(function() {
//	get_event_item_list_html();
	get_item_list(search_cate, search_supp, search_word, search_sold, search_ivm1);
	search_cate1_html();
	search_cate_init();
	page_loaded = (!page_loaded)? true : false;
	console.log(search_ivm1);
});

$(window).on('popstate', function(event) {
	if(page_loaded){
		window.location = document.location.href;
	}
});

$(document).on("click", ".item_list .more_btn", function(){
	if(item_list_page_cnt == 10){
		item_list_flag += item_list_page_cnt;
	}else{
		item_list_flag = item_list_page_cnt;
		item_list_page_cnt = 10;
	}
	history.replaceState('', '', window.location.pathname+'?cate='+search_cate+'&supp='+search_supp+'&word='+search_word+'&sold='+search_sold+'&ivm1='+search_ivm1+'&page='+(item_list_flag+item_list_page_cnt));
	get_item_list(search_cate, search_supp, search_word, search_sold, search_ivm1);
});

$(document).on("click", ".item_list .add_item_btn", function(){
	location.href = "<?=$admin_directory?>/item_write.php?backurl=<?= urlencode($_SERVER['REQUEST_URI']) ?>";
});

$(document).on("click", ".item_list .add_jb_item_btn", function(){
	location.href = "<?=$admin_directory?>/jbook_item_list.php?backurl=<?= urlencode($_SERVER['REQUEST_URI']) ?>";
});

$(document).on("keyup", ".fixed-menu .btn_wrap input[name='word']", function(e){
	if($(this).val() != "" && e.keyCode == "13"){
		item_search();
	}
});

$(document).on("click", ".fixed-menu .search_btn", function(){
	item_search();
});

function item_search(){
	search_cate = $(document).find(".item_list .search_wrap input[name='ic_seq_list']").val();
	search_supp = $(document).find(".item_list .search_wrap input[name='search_is_supply']:checked").val();
	search_word = $(document).find(".fixed-menu .btn_wrap input[name='word']").val();
	search_sold = $(document).find(".item_list .search_wrap select[name='search_is_soldout'] option:selected").val();
	search_ivm1 = {};

	item_list_flag = 0;
	item_list_page_cnt = 10;

	var _chk = 0;
	$(document).find(".item_list .search_wrap input[name='search_is_supply']").each(function(i, v){
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
	
	$(document).find(".item_list .search_wrap .search_is_view_main input[type='checkbox']").each(function(i, v){
		if($(this).is(":checked") == true){
			search_ivm1[$(this).val()] = 1;
		}else{
			search_ivm1[$(this).val()] = 0;
		}
	});
	search_ivm1 = $.map(search_ivm1, function(e){ return e; }).join('');

	$("#event").html('');
	history.pushState('', '', window.location.pathname+'?cate='+search_cate+'&supp='+search_supp+'&word='+search_word+'&sold='+search_sold+'&ivm1='+search_ivm1+'&page='+(item_list_flag+item_list_page_cnt));
	get_item_list(search_cate, search_supp, search_word, search_sold, search_ivm1);
}

$(document).on("click", ".item_list .search_wrap .search_reset_btn", function(){
	search_cate = '';
	search_supp = '';
	search_word = '';
	search_sold = '';
	search_ivm1 = '';
	$(document).find(".item_list .search_wrap .cate_1").val('');
	$(document).find(".item_list .search_wrap .cate_2").html('<option value="">선택</option>');
	$(document).find(".item_list .search_wrap .cate_3").html('<option value="">선택</option>');
	$(document).find(".item_list .search_wrap input[name='ic_seq_list']").val('');
	$(document).find(".fixed-menu .btn_wrap input[name='word']").val('');
	$(document).find(".item_list .search_wrap select[name='search_is_soldout']").val('');
	$(document).find(".item_list .search_wrap input[name='search_is_supply']").each(function(i, v){
		$(this).prop("checked", true);
	});
	$(document).find(".item_list .search_wrap .search_is_view_main input[type='checkbox']").each(function(i, v){
		$(this).prop("checked", false);
	});

	$("#event").html('');
	history.pushState('', '', window.location.pathname);
	get_item_list(search_cate, search_supp, search_word, search_sold, search_ivm1);
});

$(document).on("change", ".item_list .search_wrap .cate_1", function(){
	$(document).find(".item_list .search_wrap input[name='ic_seq_list']").val($(this).children("option:selected").val());
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_cate2",
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
				$(document).find(".item_list .search_wrap .cate_2").html('').html(html);
				$(document).find(".item_list .search_wrap .cate_3").html('<option value="">선택</option>');
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

$(document).on("change", ".item_list .search_wrap .cate_2", function(){
	$(document).find(".item_list .search_wrap input[name='ic_seq_list']").val($(this).children("option:selected").val());
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_cate3",
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
				$(document).find(".item_list .search_wrap .cate_3").html('').html(html);
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

$(document).on("change", ".item_list .search_wrap .cate_3", function(){
	$(document).find(".item_list .search_wrap input[name='ic_seq_list']").val($(this).children("option:selected").val());
});

function search_cate1_html(){
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_cate1"
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
				$(document).find(".item_list .search_wrap .cate_1").html('').html(html);
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

function search_cate_init(){
	if(search_cate != ""){
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "get_cate_list",
				cate : search_cate
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					$.each(data.data, function(i, v){
						var node_path = (v.node_path && v.node_path != "" && v.node_path.indexOf('^') != -1)? v.node_path.split('^') : "";
						$(document).find(".item_list .search_wrap .cate_1").val(node_path[0]);
						// cate2
						$.ajax({
							url: '<?=$item_directory ?>/item_list_ajax.php',
							data: {
								mode : "get_cate2",
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
									$(document).find(".item_list .search_wrap .cate_2").html('').html(html);
									$(document).find(".item_list .search_wrap .cate_2").val(node_path[1]);
									// cate3
									$.ajax({
										url: '<?=$item_directory ?>/item_list_ajax.php',
										data: {
											mode : "get_cate3",
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
												$(document).find(".item_list .search_wrap .cate_3").html('').html(html);
												$(document).find(".item_list .search_wrap .cate_3").val(node_path[2]);
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
}
//function get_event_item_list_html(){
//	return new Promise(function(resolve, reject) {
//		var html = '';
//
//		html += '<div class="item_list">';
//		html += '	<div class="title"><span>검색결과</span></div>';
//		html += '	<div class="caption">';
//		html += '		<div class="total_cnt">0개의 상품</div>';
//		html += '		<div class="order">';
//		html += '		</div>';
//		html += '	</div>';
//		html += '	<div class="new_item_wrap">';
//		html += '	</div>';
//		html += '</div>';
//
//		$(".item_data_list").html(html);
//		resolve();
//	});
//}
function get_item_list(search_cate, search_supp, search_word, search_sold, search_ivm1){
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_item_list",
//			search_cate : search_cate,
//			search_supp : search_supp,
			search_word : search_word,
			search_sold : search_sold,
//			search_ivm1 : search_ivm1,
			orderby : "search",
			is_shop : "2",
			is_view : "1",
			customer_search_item : "1",
			flag : item_list_flag,
			cnt  : item_list_page_cnt
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				console.log(data.sql);
				var html = '';
				var idx = 0;
				$.each(data.data.list, function(i, v){
					var product_img = (v.product_img && v.product_img != "")? v.product_img : "dog_pet.png";
					var option_price = (v.option_price && v.option_price != "")? v.option_price : 0;
					var is_view = (v.is_view != "1")? "not_view" : "";
//					var is_soldout = (v.is_soldout == "2")? "soldout" : "";
//					var is_view_main_1 = (v.is_view_main_1 == "1")? "<span class='view_tag'>메인MD</span>" : "";
//					var is_view_main_2 = (v.is_view_main_2 == "1")? "<span class='view_tag'>메인NEW</span>" : "";
//					var is_view_main_3 = (v.is_view_main_3 == "1")? "<span class='view_tag'>쇼핑MD</span>" : "";
//					var is_view_main_4 = (v.is_view_main_4 == "1")? "<span class='view_tag'>쇼핑NEW</span>" : "";
//					var is_view_main_5 = (v.is_view_main_5 == "1")? "<span class='view_tag'>베스트</span>" : "";
//					var is_view_main_6 = (v.is_view_main_6 == "1")? "<span class='view_tag'>인기</span>" : "";
					var percent = (parseInt(v.product_price) == 0 || parseInt(v.sale_price) == 0)? 0 : 100 - (parseInt(v.sale_price) / parseInt(v.product_price) * 100);

					html += '<a href="../mainpage/item_product_page.php?no='+v.product_no+'&backurl='+encodeURIComponent(window.location.pathname)+'?word='+search_word+'">';
					html += '	<div class="item" data-id="'+v.il_seq+'">';
					if(v.is_soldout == "2"){
						html += '	<div class="item_image soldout"></div>';
						html += '	<div class="item_name">'+v.product_name+'</div>';
						html += '	<div class="item_price"><span class="soldout">일시품절</span></div>';
					} else {
						html += '	<div class="item_image"></div>';
						html += '	<div class="item_name">'+v.product_name+'</div>';
						html += '	<div class="item_price"><span class="percent">'+Math.round(percent)+'%</span> <span class="sale_price">'+v.sale_price.format()+'원</span></div>';
						html += '	<div class="item_rating"><span class="no_star"><i class="fas fa-star"></i></span> <span>0</span> (<span>0)</div>';
					}
					html += '	</div>';
					html += '</a>';
					idx++;
				});
				$("#event").find('.item_list .new_item_wrap').append(html);

				$.each(data.data.list, function(i, v){
					var target = ".item_list .item";

					get_file_list(target, v.il_seq, v.product_img, v.goodsRepImage);
					get_item_review(target, v.il_seq, v.product_no);
					if(v.is_use_option == "1" && v.sale_price == "0"){ // 옵션사용 + 가격 0원
						get_item_option_1(target, v.il_seq);
					}
				});

				$.each(data.data.list, function(i, v){
					if(v.is_use_option == "1" && v.product_price == 0){
						get_item_option(v.il_seq);
					}
					if(v.is_supply == "1" && v.supplier == "정글북"){
						get_jbook_list("goods", v.goodsNo, v.il_seq);
					}
				});

				if(idx == 0){
					$(".item_list .more_btn").hide();
					if(item_list_flag == 0){
						html += '<tr>';
						html += '	<td colspan="4" class="no_data">등록된 상품이 없습니다.</td>';
						html += '</tr>';
					}
					$("#event").html('').html(html);
				}else if(idx >= item_list_page_cnt){
					if(data.data.list_cnt == data.data.total_cnt){
						$(".item_list .more_btn").hide();
					}else{
						$(".item_list .more_btn").show();
					}
				}else {
					$(".item_list .more_btn").hide();
				}
				
				$(".item_list .caption").html("현재 상품 수 : "+data.data.list_cnt+"개 / 총 상품 수 : "+data.data.total_cnt+"개 ");
				$(".item_list .more_btn").text("더보기 ("+data.data.list_cnt+" / "+data.data.total_cnt+")");


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
			if(xhr.status != 0){
				alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
			}
		}
	});
}

// 2021.05.14 검색기능으로 넣은것
function get_file_list(target, il_seq, img_list, goodsRepImage){
	return new Promise(function(resolve, reject) {
		console.log(img_list);
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
						console.log(data.data);
						var html = '';
						if(data.data && data.data.length > 0){
							$.each(data.data, function(i, v){
								if(i == 0){
									$("#event").find(target+"[data-id='"+il_seq+"'] .item_image").css("background-image", "url('"+v.file_path+"')");
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
				$("#event").find(target+"[data-id='"+il_seq+"'] .item_image").css("background-image", "url('"+goodsRepImage+"')");
			}else{
				$("#event").find(target+"[data-id='"+il_seq+"'] .item_image").css("background-image", "url('../images/product_img.png')");
			}
		}
	});
}

function get_item_review(target, il_seq, product_no){
	return new Promise(function(resolve, reject) {

		if(product_no != ""){
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_item_review",
					product_no: product_no
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log("item_review");
						console.log(data.data);
						if(data.data && data.data.length > 0){
							var rating = 0;
							var sum_rating = 0;
							$.each(data.data, function(i, v){
								sum_rating += parseInt(v.rating);
							});
							rating = Math.round(sum_rating / data.data.length * 10) / 10;
							$("#event").find(target+"[data-id='"+il_seq+"'] .item_rating").html('<span class="star"><i class="fas fa-star"></i></span> '+rating+' ('+data.data.length+')');
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
		}
	});
}

function get_item_option_1(target, il_seq){
	return new Promise(function(resolve, reject) {

		if(il_seq != ""){
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
						console.log("item_option");
						console.log(data.data);
						if(data.data && data.data.length > 0){
							$.each(data.data, function(i, v){
								if(i == 0){
									console.log(v.sale_price);
									var percent = (parseInt(v.option_price) == 0 || parseInt(v.sale_price) == 0)? 0 : 100 - (parseInt(v.sale_price) / parseInt(v.option_price) * 100);
									var sale_price = (v.sale_price && v.sale_price != "")? v.sale_price : 0;
									$("#event").find(target+"[data-id='"+il_seq+"'] .item_price .sale_price").text(sale_price.format()+'원');
									$("#event").find(target+"[data-id='"+il_seq+"'] .item_price .percent").text(Math.round(percent)+'%');
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
		}
	});
}
// 2021.05.14 검색기능으로 넣은것 끝

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

				$(".item_list .item_data_list tr.item[data-seq='"+il_seq+"'] .item_price").html(html);


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

function get_jbook_list(menu, option, il_seq){
	//console.log(menu, option);
	$.ajax({
		url: '<?=$admin_directory?>/jbook_item_ajax.php',
		data: {
			mode : "get_item_list",
			menu : menu,
			option : option
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				var result_txt = '0';
				if(data.data && parseInt(data.data.total) > 0){
					$.each(data.data.data, function(i, v){
						result_txt = (v.runout == "1")? '품절' : v.goodsStock;
					});
				}
				$(".item_list .item_data_list tr.item[data-seq='"+il_seq+"'] .item_amount").html(result_txt);
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

$(document).on("click", ".item_list .toggle_btn", function(){
	$(".item_list .option_list[data-seq='"+$(this).data("seq")+"']").toggle();
});

$(document).on("click", ".item_list .item_write_btn", function(){
	localStorage.setItem('windowscrolltop',$(window).scrollTop()); // scroll position
	localStorage.setItem('itemlistpage',(item_list_flag+item_list_page_cnt)); // more_list_cnt
	history.replaceState('', '', window.location.pathname+'?cate='+search_cate+'&supp='+search_supp+'&word='+search_word+'&sold='+search_sold+'&ivm1='+search_ivm1+'&page='+(item_list_flag+item_list_page_cnt));
	location.href = "<?=$admin_directory?>/item_write.php?seq="+$(this).data("seq")+"&backurl="+encodeURIComponent(window.location.pathname+'?cate='+search_cate+'&supp='+search_supp+'&word='+search_word+'&sold='+search_sold+'&ivm1='+search_ivm1+'&page='+(item_list_flag+item_list_page_cnt)+'&backurl=<?=urlencode($backurl) ?>');
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

// 검색 눌렀을때 다시 인기검색어 뜨게하는 기능
//function search_item() {
//	var $event = $("#event");
//
//	$(function(){
//		$("#loading").css("align-items", "center").css("justify-content", "center");
//		$("#loading").html("<span style='color: #fff; max-width: 50%; text-align:center;'><img src='/pet/images/logo_loading0.gif' style='width: 100px; height: 100px;'/><br/><span style='color:#f5bf2e;'>반</span>려생활의 단<span style='color:#f5bf2e;'>짝</span></span>");
//
//		get_search_html()
//
//	});
//
//	function get_search_html(){
//		return new Promise(function(resolve, reject) {
//			var html = '';
//
//			html += '<div class="location_top_wrap">';
//			html += '	<input type="hidden" name="top" value="<?=$r_top ?>" />';
//			html += '	<input type="hidden" name="middle" value="<?=$r_middle ?>" />';
//			html += '	<div id="map" class="location_box" style="width:100%;position:relative;background:none!important;">';
//			html += '		<div class="location_bok">';
//			html += '			<div class="location_top" style="font-size:16px;margin-left:-10px;">인기검색어</div>';
//			html += '			<div class="location_middle_wrap" style="height:200px;background:none;padding:0px 20px 0px 20px;box-sizing:border-box;">';
//			html += '				<div class="location_middl" style="height:40px;border-bottom:1px solid #ccc;line-height:40px;font-size:14px;color:#f6bf2e;">1</div>';
//			html += '				<div class="location_middl" style="height:40px;border-bottom:1px solid #ccc;line-height:40px;font-size:14px;color:#f6bf2e;">2</div>';
//			html += '				<div class="location_middl" style="height:40px;border-bottom:1px solid #ccc;line-height:40px;font-size:14px;color:#f6bf2e;">3</div>';
//			html += '				<div class="location_middl" style="height:40px;border-bottom:1px solid #ccc;line-height:40px;font-size:14px;color:#f6bf2e;">4</div>';
//			html += '				<div class="location_middl" style="height:40px;border-bottom:1px solid #ccc;line-height:40px;font-size:14px;color:#f6bf2e;">5</div>';
//			html += '			</div>';
//			html += '		</div>';
//			html += '	</div>';
//			html += '</div>';
//			html += '<div class="location_bottom_wrap">';
//			html += '	<img src="../images/dental.jpg" style="width:100%;padding:20px 20px;box-sizing:border-box;">';
//			html += '</div>';
//
//			$event.html(html);
//			resolve();
//		});
//	}
//
////	function change_canle_btn(){
////		return new Promise(function(resolve, reject) {
////			var html = '';
////			
////			html +='<form action="search_item_3_1.php" method="GET" style="">';
////			html +='	<input type="text" name="word" id="search_name" placeholder="어떤 상품을 찾으세요?" onfocus="search_item()" value="" style="outline:none;border:none;margin-left:-10px;background:#efefef;padding:10px 0px 10px 10px;width:70%;">';
////			html +='	<input type="submit" class="search_btn" name="button" value="검색">';
////			html +='	<input type="button" value="닫기" onclick="location.href=''" style="outline:none;border:none;">';
////			html +='</form>';
////
////			$('.location_tab').find('.search_box').html(html);
////			resolve();
////		}
////	}
//}
</script>

<?php
    include "../include/bottom.php";
?>
