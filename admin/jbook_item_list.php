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
<style>
	.bjj_top_menu { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; border-bottom: 1px solid #ccc; background-color: rgba(9, 139, 69, 0.8); margin-top: env(safe-area-inset-top); margin-top: constant(safe-area-inset-top); z-index: 10; }
	.bjj_top_menu .bjj-back-btn { position: absolute; left: 0px; top: 0px; display: flex; justify-content: center; align-items: center; width: 50px; height: 50px; font-size: 24px; }
	.bjj_top_menu .bjj-back-btn a { color: #fff; }
	/*.bjj_top_menu .bjj-back-btn img { width: 26px; vertical-align: middle; }*/
	.bjj_top_menu .bjj_top_title { width: 100%; height: 50px; line-height: 50px; text-align: center; padding: 0px; color: #fff; }
	.bjj_top_menu .bjj_top_title p { padding: 0px; margin: 0px; }
	.scroll_top { display: none; position: fixed; right: 10px; bottom: 10px; z-index: 1; width: 50px; height: 50px; background-color: rgba(255, 255, 255, 0.6); border: 1px solid #ccc; border-radius: 25px; display: none; -webkit-align-items: center; -webkit-justify-content: center; }
	.scroll_top.on { display: flex; }
	
	ul { list-style: none; padding: 0px; margin: 0px; }
	input[type="text"] { border: 0px; border-bottom: 1px solid #ccc; background-color: transparent; padding: 5px; margin: 0px; height: 24px; }
	select { border: 1px solid #ccc; background-color: transparent; padding: 5px; margin: 0px; height: 30px; }

	#jbook_item_list { position: relative; width: 100%; margin-top: calc(env(safe-area-inset-top) + 61px); margin-top: calc(constant(safe-area-inset-top) + 61px); }
	#jbook_item_list .search_wrap { border: 1px solid #ccc; padding: 5px 5px 35px 5px; position: relative; }
	#jbook_item_list .search_wrap ul li { padding: 5px 0px; }
	#jbook_item_list .search_wrap .title { font-size: 10px; color: #ccc; padding-bottom: 2px; }
	#jbook_item_list .search_wrap .btn_wrap { position: absolute; right: 5px; bottom: 5px; }
	#jbook_item_list .search_wrap .btn_wrap button { padding: 0px 10px; height: 30px; background-color: #ccc; border: 1px solid #ccc; color: #000; border-radius: 5px; }
	#jbook_item_list .item_list table { border-collapse: collapse; width: 100%; text-align: center; font-size: 14px; }
	#jbook_item_list .item_list table caption { position: relative; margin-top: 10px; height: 30px; font-size: 12px; }
	#jbook_item_list .item_list table caption .total_cnt { position: absolute; left: 5px; bottom: 5px; }
	#jbook_item_list .item_list table caption .unit { position: absolute; right: 5px; bottom: 5px; }
	#jbook_item_list .item_list table tr.runout { background-color: #eee; }
	#jbook_item_list .item_list table tr th { position: sticky; top: 50px; border-bottom: 1px solid #ccc; background-color: #eee; padding: 5px; white-space: nowrap; }
	#jbook_item_list .item_list table tr th.lft { text-align: left; }
	#jbook_item_list .item_list table tr th.rht { text-align: right; }
	#jbook_item_list .item_list table tr td { border-bottom: 1px solid #ccc; padding: 2px 5px; }
	#jbook_item_list .item_list table tr td .category { color: #999; font-size: 8px; }
	#jbook_item_list .item_list table tr td .go_jbook_item_detail_btn { cursor: pointer; }
	#jbook_item_list .item_list table tr td.lft { text-align: left; }
	#jbook_item_list .item_list table tr td.rht { text-align: right; }
	#jbook_item_list .item_list .more_btn { width: 100%; height: 40px; border: 1px solid #0A8242; background-color: #0A8242; color: #fff; }
	#jbook_item_list .item_list .table_end { width: 100%; height: 40px; border: 1px solid #eee; background-color: #eee; color: #999; text-align: center; line-height: 40px; font-size: 12px; }
	#jbook_item_list .item_list .no_data { color: #999; text-align: center; padding: 30px 0px; }
</style>
<script src="../js/fontawesome.min.js"></script>

<div class="bjj_top_menu">
	<?php if($backurl){ ?>
    <div class="bjj-back-btn"><a href="<?= $backurl ?>"><i class="fas fa-chevron-left"></i></a></div>
	<?php }else{ ?>
	<div class="bjj-back-btn"><a href="<?= $admin_directory ?>/"><i class="fas fa-chevron-left"></i></a></div>
	<?php } ?>
	<div class="bjj_top_title"><p>정글북 상품 리스트</p></div>
</div>
<div class="scroll_top"><i class="fas fa-chevron-up"></i></div>

<div id="jbook_item_list">
</div>
<script>
	var $jbook_item_list = $("#jbook_item_list");
	var page = ("<?=$r_page ?>" != "")? parseInt("<?=$r_page?>") : 1; // 현재 페이지(1페이지당 100개)
	var total_cnt = 0; // 총 상품 수
	var total_page_cnt = 0; // 총 페이지 수
	var category = "<?=$r_cate ?>"; // 검색 - 카테고리 후 array로 변경
	var lastScrollTop = 0;

	$(function(){
		category_html();
	});

	// 상위 숨기기
	$(window).on("scroll", function(){
		var st = $(this).scrollTop();
		if(st > lastScrollTop || st == 0){
			$(document).find(".scroll_top").removeClass("on");
		}else{
			$(document).find(".scroll_top").addClass("on");
		}
		if((st + $(window).height()) >= $("body").height()){
			$(document).find(".scroll_top").addClass("on");
		}
		lastScrollTop = st;
	});

	// 맨위로 스크롤
	$(document).on("click", ".scroll_top", function(){
		$('html, body').animate({scrollTop : '0'}, 100);
	});

	// 카테고리 n차검색
	$jbook_item_list.on("change", ".search_wrap .category select", function(){
		var value = $(this).children("option:selected").val();
		var depth = parseInt($(this).data("depth"));

		$jbook_item_list.find(".search_wrap .category input[name='category']").val(value);
		category = value;

		console.log(value);
		for(var _i = (depth+1); _i <= 4; _i++){
			$jbook_item_list.find(".search_wrap .category select[name='category_"+_i+"']").html("<option value=''>전체</option>");
		}

		if(value != ""){
			get_jbook_category(value, value, depth);
		}
	});

	// 검색
	$jbook_item_list.on("click", ".search_wrap .search_btn", function(){
		page = 1;
		$jbook_item_list.find(".item_list").remove(); // list init
		if(category != ""){
			get_jbook_list("category", category+"/goods/"+page);
		}else{
			get_jbook_list("goods", "all/"+page);
		}
		history.replaceState('', '', '../admin/jbook_item_list.php?cate='+category+'&page='+page);
	});

	// 초기화
	$jbook_item_list.on("click", ".search_wrap .search_reset_btn", function(){
		page = 1;
		$jbook_item_list.find(".search_wrap .category select[name='category_2']").html("<option value=''>전체</option>");
		$jbook_item_list.find(".search_wrap .category select[name='category_3']").html("<option value=''>전체</option>");
		$jbook_item_list.find(".search_wrap .category select[name='category_4']").html("<option value=''>전체</option>");
		$jbook_item_list.find(".search_wrap .category select").val("");
		$jbook_item_list.find(".item_list").remove();
		get_jbook_list("goods", "all/"+page);
		history.replaceState('', '', '../admin/jbook_item_list.php');
	});

	// 더보기
	$jbook_item_list.on("click", ".more_btn", function(){
		page += 1;
		history.replaceState('', '', '../admin/jbook_item_list.php?cate='+category+'&page='+page);
		if(category != ""){
			get_jbook_list("category", category+"/goods/"+page);
		}else{
			get_jbook_list("goods", "all/"+page);
		}
	});

	// 상세페이지 이동
	$jbook_item_list.on("click", ".go_jbook_item_detail_btn", function(){
		//console.log($(this).closest("tr").data("goodsno")); //.data("goodsNo")
		var category = $jbook_item_list.find("input[name='category']").val();
		history.replaceState('', '', '../admin/jbook_item_list.php?cate='+category+'&page='+page);
		localStorage.setItem('windowscrolltop',$(window).scrollTop()); // scroll position
		localStorage.setItem('itemlistpage',page); // more_list_cnt
		location.href = "../admin/jbook_item_detail.php?q="+$(this).closest("tr").data("goodsno")+"&backurl="+encodeURIComponent(window.location.pathname+"?cate="+category+'&page='+page+'&backurl=<?=urlencode($backurl) ?>');
	});

	function category_html(){
		var html = '';
		html += '<div class="search_wrap">';
		html += '	<ul>';
		html += '		<li class="category">';
		html += '			<input type="hidden" name="category" value="'+category+'" />';
		html += '			<div class="title">카테고리</div>';
		html += '			<div class="content">';
		html += '				<select name="category_1" data-depth="1">';
		html += '					<option value="">전체</option>';
		html += '				</select>';
		html += '				<select name="category_2" data-depth="2">';
		html += '					<option value="">전체</option>';
		html += '				</select>';
		html += '				<select name="category_3" data-depth="3">';
		html += '					<option value="">전체</option>';
		html += '				</select>';
		html += '				<select name="category_4" data-depth="4">';
		html += '					<option value="">전체</option>';
		html += '				</select>';
		html += '			</div>';
		html += '		</li>';
		/*
		html += '		<li class="category">';
		html += '			<div class="title">브랜드명</div>';
		html += '			<div class="content">';
		html += '				<input type="text" name="brand" value="">';
		html += '			</div>';
		html += '		</li>';
		html += '		<li class="category">';
		html += '			<div class="title">제품명</div>';
		html += '			<div class="content">';
		html += '				<input type="text" name="goodsNm" value="">';
		html += '			</div>';
		html += '		</li>';
		*/
		html += '	</ul>';
		html += '	<div class="btn_wrap">';
		html += '		<button type="button" class="search_btn">검색</button>';
		html += '		<button type="button" class="search_reset_btn">초기화</button>';
		html += '	</div>';
		html += '</div>';
		$jbook_item_list.append(html);

		get_jbook_category('', category, '0');
		
		console.log(category);

		if(typeof category != "undefined" && category != ""){
			var category_tmp = '';
			for(var _i = 0; _i < 12; _i+= 3){
				if(category.substring(_i, _i+3) != ""){
					category_tmp += category.substring(_i, _i+3);
					get_jbook_category(category_tmp, category, _i/3+1);
				}
			}
		}

		if(typeof category != "undefined" && category != ""){
			if(page != 1){
				for(var _i = 1; _i <= page; _i++){
					get_jbook_list("category", category+"/goods/"+_i);
				}
			}else{
				get_jbook_list("category", category+"/goods/"+page);
			}
		}else{
			get_jbook_list("goods", "all/"+page);
		}
	}

	function get_jbook_category(option, category, depth){
		console.log(option, category, depth);
		$.ajax({
			url: '<?=$admin_directory?>/jbook_item_ajax.php',
			data: {
				mode : "get_item_category",
				option: option
			},
			type: 'POST',
			dataType: 'JSON',
			async: false,
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					var html = '';
					if(depth == 0){ // init
						// default selected
						$.each(data.data, function(i, v){
							var is_selected = (v.categoryCode == category.substring(0, 3))? " selected " : "";
							html += '<option value="'+v.categoryCode+'" '+is_selected+'>'+v.categoryNm+'</option>';
						});
						$jbook_item_list.find(".search_wrap .category select[name='category_1']").append(html);
					}else{
						// children call
						$.each(data.data, function(i, v){
							if(v.categoryCode == option){
								$jbook_item_list.find(".search_wrap .category select[name='category_"+depth+"']").val(v.categoryCode);
							}
							if(v.children && v.children.length > 0){
								$.each(v.children, function(i2, v2){
									//var is_selected = (v2.categoryCode == category.substring((depth*3), ((depth*3)+3)))? " selected " : "";
									html += '<option value="'+v2.categoryCode+'" >'+v2.categoryNm+'</option>';
								});
							}else{
								// no_category
								$jbook_item_list.find(".search_wrap .category select[name='category_"+(depth+1)+"']").html("<option value=''>-</option>");
								$jbook_item_list.find(".search_wrap .category select[name='category_"+(depth+1)+"']").val('');
							}
						});
						$jbook_item_list.find(".search_wrap .category select[name='category_"+(depth+1)+"']").append(html);
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

	function get_jbook_list(menu, option){
		console.log(menu, option);
		$.ajax({
			url: '<?=$admin_directory?>/jbook_item_ajax.php',
			data: {
				mode : "get_item_list",
				menu : menu,
				option : option
			},
			type: 'POST',
			dataType: 'JSON',
			async: false,
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);

					var html = '';
					total_cnt = data.data.total;
					total_page_cnt = data.data.totalPageCnt;
					$jbook_item_list.find(".more_btn").remove();
					
					if(data.data.data && data.data.data.length > 0){
						if(data.data.currentPage == "1"){
							html += '<div class="item_list">';
							html += '	<table>';
							html += '		<caption>';
							html += '			<div class="total_cnt">총 제품 수 : '+total_cnt.format()+'개</div>';
							html += '			<div class="unit">단위(원)</div>';
							html += '		</caption>';
							html += '		<tr>';
							html += '			<th colspan="2" class="lft">카테고리명<br/>[브랜드명] 제품명 (패키지입수량)</th>';
							html += '			<th>재고</th>';
							html += '			<th class="rht" style="font-size: 10px;">상품판매가<br/>권장판매가<br/>권장소비자가</th>';
							html += '		</tr>';
						}
						$.each(data.data.data, function(i, v){
							var is_runout = (v.runout == "1")? "runout" : "";
							html += '		<tr class="goods_data '+is_runout+'" data-goodsno="'+v.goodsNo+'">';
							html += '			<td><img src="'+v.goodsRepImage+'" style="width:50px; height: 50px;" /></td>';
							html += '			<td class="lft"><span class="category">'+v.goodsNo+'</span>:<span class="category">'+v.categoryNm+'</span><br/><span class="go_jbook_item_detail_btn">['+v.brand+'] '+v.goodsNm+'('+v.inPackageEA+'EA)</span></td>';
							html += '			<td class="rht">'+v.goodsStock.format()+'</td>';
							html += '			<td class="rht">'+v.goodsPrice.format()+'<br/>'+v.suggestionSalesPrice.format()+'<br/>'+v.suggestionRetailPrice.format()+'</td>';
							html += '		</tr>';
						});
						if(data.data.currentPage == "1"){
							html += '	</table>';
							html += '</div>';
						}
					}else{
						// no data..
						html += '<div class="item_list">';
						html += '	<div class="no_data">제품이 없습니다.</div>';
						html += '</div>';
					}
					
					if(data.data.currentPage == "1"){
						$jbook_item_list.append(html);
					}else{
						$jbook_item_list.find(".item_list table").append(html);
					}
					if(total_page_cnt != page){
						$jbook_item_list.find(".item_list table").after('<button type="button" class="more_btn"><i class="fas fa-chevron-circle-down"></i> 더보기</button>');
					}else{
						$jbook_item_list.find(".item_list table").after('<div class="table_end">상품의 마지막입니다.</div>');
					}

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
