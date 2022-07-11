<?php
include "../include/top.php";

$r_goodsNo = ($_GET["q"] && $_GET["q"] != "")? $_GET["q"] : "";
$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

$backurl = $_GET["backurl"];
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
	.scroll_top { display: none; position: fixed; right: 10px; bottom: 60px; z-index: 1; width: 50px; height: 50px; background-color: rgba(255, 255, 255, 0.6); border: 1px solid #ccc; border-radius: 25px; -webkit-align-items: center; -webkit-justify-content: center; }
	.scroll_top.on { display: flex; }
	
	ul { list-style: none; padding: 0px; margin: 0px; }
	input[type="text"], input[type="number"] { border: 0px; border-bottom: 1px solid #ccc; background-color: transparent; padding: 5px; margin: 0px; height: 24px; }
	select { border: 1px solid #ccc; background-color: transparent; padding: 5px; margin: 0px; height: 30px; }

	#jbook_item_detail { position: relative; width: 100%; margin-top: calc(env(safe-area-inset-top) + 61px); margin-top: calc(constant(safe-area-inset-top) + 61px); }
	#jbook_item_detail ul { margin: 10px; }
	#jbook_item_detail ul li { padding: 5px 0px; }
	#jbook_item_detail .goods_data { position: sticky; top: 60px; width: calc(100% - 20px); margin: 0 auto; border: 1px solid #ccc; border-radius: 10px; background-color: #fff; }
	#jbook_item_detail img { width: 100% !important; }
	#jbook_item_detail .thumbnail { text-align: center; }
	#jbook_item_detail .thumbnail img { width: 50%; }
	#jbook_item_detail .category { font-size: 12px; color: #666; }
	#jbook_item_detail table { border-collapse: collapse; text-align: center; font-size: 12px; width: 100%; }
	#jbook_item_detail table tr td { padding: 2px 5px; }
	#jbook_item_detail table tr td.lft { text-align: left; }
	#jbook_item_detail table tr td.rht { text-align: right; }
	#jbook_item_detail .goodsdetail { text-align: center; margin-top: 20px; padding-bottom: 65px; }
	#jbook_item_detail .goodsdetail>span.title { border: 1px solid #ccc; padding: 5px 20px; font-size: 14px; }
	#jbook_item_detail .btn_wrap { position: fixed; left: 0px; bottom: 0px; width: 100%; height: 50px; text-align: center; }  
	#jbook_item_detail .btn_wrap .set_insert_item_btn { width: 100%; height: 100%; background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; font-size: 18px; }

	#jbook_popup select { padding: 0px 10px; height: 30px; border: 1px solid #ccc; }
	#jbook_popup button { padding: 0px 10px; height: 30px; border: 1px solid #ccc; background-color: #eee; border-radius: 5px; }
	#jbook_popup ul li { padding: 10px 0px; }
	#jbook_popup .title { font-size: 12px; color: #999; margin-top: 10px; padding-bottom: 5px; }
	#jbook_popup .category_list { border-bottom: 1px solid #eee; min-height: 60px; }
	#jbook_popup .category_list .no_data { text-align: center; color: #999; padding: 30px 0px; }
	#jbook_popup input[name='product_no'] { width: 100%; }
	#jbook_popup input[name='product_name'] { width: 100%; }
	#jbook_popup input[name='product_comment'] { width: 100%; }
	#jbook_popup input[name='supplier_price'] { width: calc(100% - 30px); text-align: right; }
	#jbook_popup input[name='product_price'] { width: calc(100% - 30px); text-align: right; }
	#jbook_popup input[name='sale_price'] { width: calc(100% - 30px); text-align: right; }
	#jbook_popup button.selected_cate_btn { width: 100%; margin: 10px 0px; }
	#jbook_popup .category_list .selected_category { position: relative; border: 1px solid #ccc; padding: 5px; margin: 2px 5px; }
	#jbook_popup .category_list .selected_category .del_category { position: absolute; right: 0px; top: 0px; width: 30px; height: 30px; text-align: center; display: flex; align-items: center; justify-content: center; }
</style>
<script src="../js/fontawesome.min.js"></script>

<div class="bjj_top_menu">
	<?php if($backurl){ ?>
    <div class="bjj-back-btn"><a href="<?= $backurl ?>"><i class="fas fa-chevron-left"></i></a></div>
	<?php }else{ ?>
    <div class="bjj-back-btn"><a href="<?= $admin_directory ?>/jbook_item_list.php"><i class="fas fa-chevron-left"></i></a></div>
	<?php } ?>
    <div class="bjj_top_title"><p>정글북 상품 내역</p></div>
</div>
<div class="scroll_top"><i class="fas fa-chevron-up"></i></div>
<div id="jbook_item_detail"></div>
<div id="jbook_popup"></div>

<script>
	var $jbook_item_detail = $("#jbook_item_detail");
	var $jbook_popup = $("#jbook_popup");
	var goodsNo = "<?=$r_goodsNo ?>";
	var lastScrollTop = 0;
	var jbook_list = {}; // 데이터

	$(function(){
		get_jbook_list("goods", goodsNo);
	});

	$(window).on("scroll", function(){
		var st = $(this).scrollTop();
		if(st > lastScrollTop){
			$(document).find(".scroll_top").addClass("on");
		}else{
			$(document).find(".scroll_top").removeClass("on");
		}
		lastScrollTop = st;
	});

	// 맨위로 스크롤
	$(document).on("click", ".scroll_top", function(){
		$('html, body').animate({scrollTop : '0'}, 100);
	});

	$jbook_item_detail.on("click", ".set_insert_item_btn", function(){
		$jbook_popup.dialog({
			modal: true,
			title: "상품 등록",
			autoOpen: true,
			width: "96%",
			height: $(window).height() - 40,
			autoSize: true,
			resizable: false,
			draggable: false,
			buttons: {
				"등록": function() {
					if($jbook_popup.find("input[name='product_no']").val() == ""){
						$.MessageBox("제품번호를 적어주세요.");
						return false;
					}
					if($jbook_popup.find("input[name='product_name']").val() == ""){
						$.MessageBox("제품명을 적어주세요.");
						return false;
					}
					//if($jbook_popup.find("input[name='product_comment']").val() == ""){
					//	$.MessageBox("제품설명을 적어주세요.");
					//	return false;
					//}
					if($jbook_popup.find(".ic_seq_list").val() == ""){
						$.MessageBox("카테고리 선택을 해주세요.");
						return false;
					}
					if($jbook_popup.find("input[name='supplier_price']").val() == ""){
						$.MessageBox("금액을 입력해주세요.");
						return false;
					}
					if($jbook_popup.find("input[name='product_price']").val() == ""){
						$.MessageBox("금액을 입력해주세요.");
						return false;
					}
					if($jbook_popup.find("input[name='sale_price']").val() == ""){
						$.MessageBox("금액을 입력해주세요.");
						return false;
					}
					// 선택처리
					set_insert_item_list();
				},
				"취소": function() {
					$(this).dialog("close");
				}
			},
			open: function(event, ui) {
				var html = '';
				var goodsNm = '';
				var goodsPrice = 0;
				var suggestionRetailPrice = 0;
				console.log(jbook_list);

				html += '<div>';
				html += '	<form method="POST" id="jbook_item_detail_form">';
				$.each(jbook_list, function(i, v){
					goodsNm = v.goodsNm;
					goodsPrice = v.goodsPrice;
					suggestionRetailPrice = v.suggestionRetailPrice;
					//html += '		<input type="hidden" name="ic_seq" value="" />';
					//html += '		<input type="hidden" name="product_name" value="'+v.goodsNm+'" />';
					//html += '		<input type="hidden" name="supplier_price" value="'+v.goodsPrice+'" />';
					//html += '		<input type="hidden" name="product_price" value="'+v.suggestionRetailPrice+'" />';
					//html += '		<input type="hidden" name="sale_price" value="'+v.suggestionRetailPrice+'" />';
					html += '		<input type="hidden" name="product_img" value="" />';
					html += '		<input type="hidden" name="is_soldout" value="2" />';
					html += '		<input type="hidden" name="is_view" value="2" />';
					html += '		<input type="hidden" name="is_use_point" value="2" />';
					html += '		<input type="hidden" name="is_use_option" value="2" />';
					html += '		<input type="hidden" name="product_detail" value="'+escapeHtml(v.goodsDetail)+'" />';
					html += '		<input type="hidden" name="goodsNo" value="'+v.goodsNo+'" />';
					html += '		<input type="hidden" name="goodsRepImage" value="'+v.goodsRepImage+'" />';
					html += '		<input type="hidden" name="inPackageEA" value="'+v.inPackageEA+'" />';
					html += '		<input type="hidden" name="origin" value="'+v.origin+'" />';
					html += '		<input type="hidden" name="maker" value="'+v.maker+'" />';
					html += '		<input type="hidden" name="brand" value="'+v.brand+'" />';
					html += '		<input type="hidden" name="barcode" value="'+v.barcode+'" />';
					html += '		<input type="hidden" name="supplier" value="정글북" />';
					html += '		<input type="hidden" name="is_supply" value="1" />';
				});
				html += '		<ul>';
				html += '			<li>';
				html += '				<div class="title">제품번호</div>';
				html += '				<div>';
				html += '					<input type="text" class="product_no" name="product_no" value="" placeholder="PE-D-A01" />';
				html += '				</div>';
				html += '			</li>';
				html += '			<li>';
				html += '				<div class="title">제품명</div>';
				html += '				<div>';
				html += '					<input type="text" name="product_name" value="'+goodsNm+'" placeholder="초극세사 반려동물 목욕수건" />';
				html += '				</div>';
				html += '			</li>';
				html += '			<li>';
				html += '				<div class="title">제품설명</div>';
				html += '				<div>';
				html += '					<input type="text" name="product_comment" value="" placeholder="초극세사 반려동물 목욕수건" />';
				html += '				</div>';
				html += '			</li>';
				html += '			<li>';
				html += '				<div class="title">공급가</div>';
				html += '				<div>';
				html += '					<input type="number" name="supplier_price" value="'+goodsPrice+'" placeholder="" />원';
				html += '				</div>';
				html += '			</li>';
				html += '			<li>';
				html += '				<div class="title">판매가</div>';
				html += '				<div>';
				html += '					<input type="number" name="product_price" value="'+suggestionRetailPrice+'" placeholder="" />원';
				html += '				</div>';
				html += '			</li>';
				html += '			<li>';
				html += '				<div class="title">할인가</div>';
				html += '				<div>';
				html += '					<input type="number" name="sale_price" value="'+suggestionRetailPrice+'" placeholder="" />원';
				html += '				</div>';
				html += '			</li>';
				html += '			<li>';
				html += '				<div class="title">카테고리 선택</div>';
				html += '				<div style="white-space: nowrap;">';
				html += '					<select class="cate_1">';
				html += '						<option value="">선택</option>';
				html += '					</select>';
				html += '					<select class="cate_2">';
				html += '						<option value="">선택</option>';
				html += '					</select>';
				html += '					<select class="cate_3">';
				html += '						<option value="">선택</option>';
				html += '					</select>';
				html += '				</div>';
				html += '				<div class="btn_wrap">';
				html += '					<button type="button" class="selected_cate_btn">선택</button>';
				html += '					<input type="hidden" class="ic_seq_tmp" value="" />';
				html += '					<input type="hidden" class="ic_seq_list" name="ic_seq" value="" />';
				html += '					<div class="category_list"></div>';
				html += '				<div>';
				html += '			</li>';
				html += '		</ul>';
				html += '	</form>';
				html += '</div>';
				
				$jbook_popup.html(html);
				jbook_popup_html();
			},
			close: function() {
			}
		});
	});

	function set_insert_item_list(){
		var post_data = $jbook_popup.find("#jbook_item_detail_form").serialize();
		post_data += "&mode=set_insert_item";
		//console.log(post_data);

		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: post_data,
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					$.MessageBox("등록되었습니다.");
					$jbook_popup.dialog("close");
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

	$jbook_popup.on("change", ".cate_1", function(){
		$jbook_popup.find(".ic_seq_tmp").val($(this).children("option:selected").val());
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
					$jbook_popup.find(".cate_2").html('').html(html);
					$jbook_popup.find(".cate_3").html('<option value="">선택</option>');
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

	$jbook_popup.on("change", ".cate_2", function(){
		$jbook_popup.find(".ic_seq_tmp").val($(this).children("option:selected").val());
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
					$jbook_popup.find(".cate_3").html('').html(html);
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

	$jbook_popup.on("change", ".cate_3", function(){
		$jbook_popup.find(".ic_seq_tmp").val($(this).children("option:selected").val());
	});

	$jbook_popup.on("click", ".selected_cate_btn", function(){
		var ic_seq = $jbook_popup.find(".ic_seq_list").val();
		var ic_seq_tmp = $jbook_popup.find(".ic_seq_tmp").val();
		if(ic_seq_tmp != ""){
			if(ic_seq && typeof ic_seq != "undefined" && ic_seq != ""){
				ic_seq = ic_seq.split(",");
				var tmp_chk = 0;
				$.each(ic_seq, function(i, v){
					if(v == ic_seq_tmp){
						tmp_chk = 1;
					}	
				});
				if(tmp_chk == 0){
					ic_seq.push(ic_seq_tmp);
					$jbook_popup.find(".ic_seq_list").val(ic_seq.join(","));
				}
			}else{
				$jbook_popup.find(".ic_seq_list").val(ic_seq_tmp);
			}
			
			$jbook_popup.find(".ic_seq_tmp").val("");
			$jbook_popup.find(".cate_1").val("");
			$jbook_popup.find(".cate_2").html('<option value="">선택</option>');
			$jbook_popup.find(".cate_3").html('<option value="">선택</option>');
			selected_category_list();
			//$("#category_wrap").dialog("close");
		}
	});

	$jbook_popup.on("click", ".del_category", function(){
		var ic_seq = $(this).data("id");
		var ic_seq_arr = $jbook_popup.find(".ic_seq_list").val().split(",");
		$.each(ic_seq_arr, function(i, v){
			if(v == ic_seq){
				ic_seq_arr.splice(i, 1);
			}
		});
		$jbook_popup.find(".ic_seq_list").val(ic_seq_arr);
		selected_category_list();
	});

	function jbook_popup_html(){
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
					$jbook_popup.find(".cate_1").html('').html(html);

					selected_category_list();
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
	
	function selected_category_list(){
		// 현재 카테고리 불러오기
		var cate = $jbook_popup.find(".ic_seq_list").val();
		if(cate && cate != ""){
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_cate_list",
					cate : cate
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						var html = '';
						$.each(data.data, function(i, v){			
							html += '<div class="selected_category">'+v.cate_name+'<span class="del_category" data-id="'+v.ic_seq+'"><i class="fas fa-times"></i></span></div>';
						});
						$jbook_popup.find(".category_list").html('').html(html);

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
			var html = '';
			html += '<div class="no_data">선택된 카테고리가 없습니다.</div>';
			$jbook_popup.find(".category_list").html('').html(html);
		}
	}

	function get_jbook_list(menu, option){
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
					var html = '';
					var detail = '';
					jbook_list = data.data.data;

					if(data.data.total > 0){
						$.each(data.data.data, function(i, v){
							html += '<div class="thumbnail"><img src="'+v.goodsRepImage+'" /></div>';
							html += '<div class="goods_data">';
							html += '	<ul>';
							html += '		<li>';
							html += '			<div class="category">'+v.categoryNm+'</div>';
							html += '			<div class="sticky_title">['+v.brand+'] '+v.goodsNm+'('+v.inPackageEA+'EA)</div>';
							html += '		</li>';
							html += '		<li>';
							html += '			<table>';
							html += '				<tr>';
							html += '					<td class="lft">제조사 : '+v.maker+'</td>';
							html += '					<td class="rht">매입가 : '+v.goodsPrice.format()+'원</td>';
							html += '				</tr>';
							html += '				<tr>';
							html += '					<td class="lft">원산지 : '+v.origin+'</td>';
							html += '					<td class="rht">권장판매가 : '+v.suggestionSalesPrice.format()+'원</td>';
							html += '				</tr>';
							html += '				<tr>';
							html += '					<td class="lft">등록일 : '+v.regDt+'</td>';
							html += '					<td class="rht">권장소비자가 : '+v.suggestionRetailPrice.format()+'원</td>';
							html += '				<tr>';
							html += '			</table>';
							html += '		</li>';
							html += '	</ul>';
							html += '</div>';
							html += '<div class="goodsdetail"><span class="title">상세설명</span><br/>'+v.goodsDetail+'</div>';
							detail = v.goodsDetail;
						});
					}
					html += '<div class="btn_wrap"><button type="button" class="set_insert_item_btn">제품등록</button></div>';

					$jbook_item_detail.html(html);
					$jbook_item_detail.find(".product_detail").text(detail);
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

	// htmlspecialchars in php function
	function escapeHtml(str) {
		var map = {
			'&': '&amp;',
			'<': '&lt;',
			'>': '&gt;',
			'"': '&quot;',
			"'": '&#039;'
		};
		return str.replace(/[&<>"']/g, function(m) { return map[m]; });
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