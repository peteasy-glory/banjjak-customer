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
$r_ivm1 = ($_GET["ivm1"] && $_GET["ivm1"] != "")? $_GET["ivm1"] : "";
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

</style>

<div class="bjj_top_menu">
    <div class="bjj-back-btn"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/btn_back_2.png"></a></div>
    <div class="bjj_top_title"><p>?????? ?????????</p></div>
</div>

<div id="item_list">
	<div class="top_wrap">
		<button class="add_jb_item_btn">??????????????????</button>
		<button class="add_item_btn">????????????</button>
	</div>
	<div class="search_wrap">
		<ul>
			<li>
				<div class="title">????????????</div>
				<select class="cate_1">
					<option value="">??????</option>
				</select>
				<select class="cate_2">
					<option value="">??????</option>
				</select>
				<select class="cate_3">
					<option value="">??????</option>
				</select>
				<input type="hidden" name="ic_seq_list" value="<?=$r_cate ?>" />
			</li>
			<li>
				<div class="title">??????????????????</div>
				<input type="checkbox" id="search_is_supply_1" name="search_is_supply" value="1" <?=($r_supp == "" || $r_supp == "1")? "checked" : "" ?> />
				<label for="search_is_supply_1">????????????</label>
				<input type="checkbox" id="search_is_supply_2" name="search_is_supply" value="2" <?=($r_supp == "" || $r_supp == "2")? "checked" : "" ?> />
				<label for="search_is_supply_2">????????????</label>
			</li>
			<li class="search_is_view_main">
				<div class="title">????????????????????????</div>
				<input type="checkbox" id="search_is_view_main_1" name="search_is_view_main_1" value="1" <?=($ivm_arr[1] == "1")? "checked" : "" ?> />
				<label for="search_is_view_main_1">??????MD</label>
				<input type="checkbox" id="search_is_view_main_2" name="search_is_view_main_2" value="2" <?=($ivm_arr[2] == "1")? "checked" : "" ?> />
				<label for="search_is_view_main_2">??????NEW</label>
				<input type="checkbox" id="search_is_view_main_3" name="search_is_view_main_3" value="3" <?=($ivm_arr[3] == "1")? "checked" : "" ?> />
				<label for="search_is_view_main_3">??????MD(?????????)</label>
				<input type="checkbox" id="search_is_view_main_4" name="search_is_view_main_4" value="4" <?=($ivm_arr[4] == "1")? "checked" : "" ?> />
				<label for="search_is_view_main_4">??????NEW(?????????)</label>
				<input type="checkbox" id="search_is_view_main_5" name="search_is_view_main_5" value="5" <?=($ivm_arr[5] == "1")? "checked" : "" ?> />
				<label for="search_is_view_main_5">?????????(?????????)</label>
				<input type="checkbox" id="search_is_view_main_6" name="search_is_view_main_6" value="6" <?=($ivm_arr[6] == "1")? "checked" : "" ?> />
				<label for="search_is_view_main_6">??????</label>
				<input type="checkbox" id="search_is_view_main_7" name="search_is_view_main_7" value="7" <?=($ivm_arr[7] == "1")? "checked" : "" ?> />
				<label for="search_is_view_main_7">??????MD(?????????)</label>
				<input type="checkbox" id="search_is_view_main_8" name="search_is_view_main_8" value="8" <?=($ivm_arr[8] == "1")? "checked" : "" ?> />
				<label for="search_is_view_main_8">??????NEW(?????????)</label>
				<input type="checkbox" id="search_is_view_main_9" name="search_is_view_main_9" value="9" <?=($ivm_arr[9] == "1")? "checked" : "" ?> />
				<label for="search_is_view_main_9">?????????(?????????)</label>
			</li>
			<li>
				<div class="title">????????????</div>
				<select name="search_is_soldout">
					<option value="">??????</option>
					<option value="1" <?=($r_sold == "1")? "selected" : "" ?>>????????????</option>
					<option value="2" <?=($r_sold == "2")? "selected" : "" ?>>??????</option>
				</select>
			</li>
			<li>
				<div class="title">??????</div>
				<input type="text" name="search_word" value="<?=$r_word ?>" placeholder="?????????, ????????????, ???????????? ???" />
			</li>
		</ul>
		<div class="btn_wrap">
			<button type="button" class="search_btn"><i class="fas fa-search"></i> ??????</button>
			<button type="button" class="search_reset_btn"><i class="fas fa-redo"></i> ?????????</button>
		</div>
	</div>
	<div class="info"> * ????????? No??? ??????????????? ???????????? ?????????(??????)??? ???????????????.</div>
	<table>
		<caption>
		</caption>
		<colgroup>
			<col width="5%" />
			<col width="1%" />
			<col width="*" />
			<col width="1%" />
			<col width="1%" />
		</colgroup>
		<tr>
			<th>No</th>
			<th>??????</th>
			<th>??????</th>
			<th>??????</th>
			<th>??????</th>
		</tr>
		<tbody class="item_data_list">
			<!--tr>
				<td colspan="4" class="no_data">????????? ????????? ????????????.</td>
			</tr-->
		</tbody>
	</table>
	<div class="more">
		<button class="more_btn">?????????</button>
	</div>
</div>

<script>
var item_list_flag = 0;		 // ?????? ????????? ?????? ??????
var item_list_page_cnt = ("<?=$r_page ?>" != "")? parseInt("<?=$r_page?>") : 10; // ????????? ????????? ????????? ??????
var search_cate = '<?=$r_cate ?>';
var search_supp = '<?=$r_supp ?>';
var search_word = '<?=$r_word ?>';
var search_sold = '<?=$r_sold ?>'; // soldout
var search_ivm1 = JSON.parse('<?=json_encode($ivm_arr) ?>');
	search_ivm1 = $.map(search_ivm1, function(e){ return e; }).join('');
var lastScrollTop = 0;
var page_loaded = false;

$(document).ready(function() {
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

$(document).on("click", "#item_list .more_btn", function(){
	if(item_list_page_cnt == 10){
		item_list_flag += item_list_page_cnt;
	}else{
		item_list_flag = item_list_page_cnt;
		item_list_page_cnt = 10;
	}
	history.replaceState('', '', window.location.pathname+'?cate='+search_cate+'&supp='+search_supp+'&word='+search_word+'&sold='+search_sold+'&ivm1='+search_ivm1+'&page='+(item_list_flag+item_list_page_cnt));
	get_item_list(search_cate, search_supp, search_word, search_sold, search_ivm1);
});

$(document).on("click", "#item_list .add_item_btn", function(){
	location.href = "<?=$admin_directory?>/item_write.php?backurl=<?= urlencode($_SERVER['REQUEST_URI']) ?>";
});

$(document).on("click", "#item_list .add_jb_item_btn", function(){
	location.href = "<?=$admin_directory?>/jbook_item_list.php?backurl=<?= urlencode($_SERVER['REQUEST_URI']) ?>";
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
	search_ivm1 = {};

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
	
	$(document).find("#item_list .search_wrap .search_is_view_main input[type='checkbox']").each(function(i, v){
		if($(this).is(":checked") == true){
			search_ivm1[$(this).val()] = 1;
		}else{
			search_ivm1[$(this).val()] = 0;
		}
	});
	search_ivm1 = $.map(search_ivm1, function(e){ return e; }).join('');

	$(".item_data_list").html('');
	history.pushState('', '', window.location.pathname+'?cate='+search_cate+'&supp='+search_supp+'&word='+search_word+'&sold='+search_sold+'&ivm1='+search_ivm1+'&page='+(item_list_flag+item_list_page_cnt));
	get_item_list(search_cate, search_supp, search_word, search_sold, search_ivm1);
}

$(document).on("click", "#item_list .search_wrap .search_reset_btn", function(){
	search_cate = '';
	search_supp = '';
	search_word = '';
	search_sold = '';
	search_ivm1 = '';
	$(document).find("#item_list .search_wrap .cate_1").val('');
	$(document).find("#item_list .search_wrap .cate_2").html('<option value="">??????</option>');
	$(document).find("#item_list .search_wrap .cate_3").html('<option value="">??????</option>');
	$(document).find("#item_list .search_wrap input[name='ic_seq_list']").val('');
	$(document).find("#item_list .search_wrap input[name='search_word']").val('');
	$(document).find("#item_list .search_wrap select[name='search_is_soldout']").val('');
	$(document).find("#item_list .search_wrap input[name='search_is_supply']").each(function(i, v){
		$(this).prop("checked", true);
	});
	$(document).find("#item_list .search_wrap .search_is_view_main input[type='checkbox']").each(function(i, v){
		$(this).prop("checked", false);
	});

	$(".item_data_list").html('');
	history.pushState('', '', window.location.pathname);
	get_item_list(search_cate, search_supp, search_word, search_sold, search_ivm1);
});

$(document).on("change", "#item_list .search_wrap .cate_1", function(){
	$(document).find("#item_list .search_wrap input[name='ic_seq_list']").val($(this).children("option:selected").val());
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
				html += '<option value="">??????</option>';
				$.each(data.data, function(i, v){			
					html += '<option value="'+v.ic_seq+'" data-path="'+v.node_path+'">'+v.cate_name+'</option>';
				});
				$(document).find("#item_list .search_wrap .cate_2").html('').html(html);
				$(document).find("#item_list .search_wrap .cate_3").html('<option value="">??????</option>');
			}else{
				alert(data.data+"("+data.code+")");
				console.log(data.code);
			}
		},
		error: function(xhr, status, error) {
			//alert(error + "??????????????????");
			if(xhr.status != 0){
				alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // ?????? ??? ??????
			}
		}
	});
});

$(document).on("change", "#item_list .search_wrap .cate_2", function(){
	$(document).find("#item_list .search_wrap input[name='ic_seq_list']").val($(this).children("option:selected").val());
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
				html += '<option value="">??????</option>';
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
			//alert(error + "??????????????????");
			if(xhr.status != 0){
				alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // ?????? ??? ??????
			}
		}
	});
});

$(document).on("change", "#item_list .search_wrap .cate_3", function(){
	$(document).find("#item_list .search_wrap input[name='ic_seq_list']").val($(this).children("option:selected").val());
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
				html += '<option value="">??????</option>';
				$.each(data.data, function(i, v){			
					html += '<option value="'+v.ic_seq+'" data-path="'+v.node_path+'">'+v.cate_name+'</option>';
				});
				$(document).find("#item_list .search_wrap .cate_1").html('').html(html);
			}else{
				alert(data.data+"("+data.code+")");
				console.log(data.code);
			}
		},
		error: function(xhr, status, error) {
			//alert(error + "??????????????????");
			if(xhr.status != 0){
				alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // ?????? ??? ??????
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
						$(document).find("#item_list .search_wrap .cate_1").val(node_path[0]);
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
									html += '<option value="">??????</option>';
									$.each(data.data, function(i, v){			
										html += '<option value="'+v.ic_seq+'" data-path="'+v.node_path+'">'+v.cate_name+'</option>';
									});
									$(document).find("#item_list .search_wrap .cate_2").html('').html(html);
									$(document).find("#item_list .search_wrap .cate_2").val(node_path[1]);
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
												html += '<option value="">??????</option>';
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
											//alert(error + "??????????????????");
											if(xhr.status != 0){
												alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // ?????? ??? ??????
											}
										}
									});
								}else{
									alert(data.data+"("+data.code+")");
									console.log(data.code);
								}
							},
							error: function(xhr, status, error) {
								//alert(error + "??????????????????");
								if(xhr.status != 0){
									alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // ?????? ??? ??????
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
				//alert(error + "??????????????????");
				if(xhr.status != 0){
					alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // ?????? ??? ??????
				}
			}
		});
	}
}

function get_item_list(search_cate, search_supp, search_word, search_sold, search_ivm1){
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_item_list",
			search_cate : search_cate,
			search_supp : search_supp,
			search_word : search_word,
			search_sold : search_sold,
			search_ivm1 : search_ivm1,
			orderby : "admin",
			is_shop : "2",
			flag : item_list_flag,
			cnt  : item_list_page_cnt
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log("????????????"+data.data);
				console.log(data.sql);
				var html = '';
				var idx = 0;
				$.each(data.data.list, function(i, v){
					var product_img = (v.product_img && v.product_img != "")? v.product_img : "dog_pet.png";
					var option_price = (v.option_price && v.option_price != "")? v.option_price : 0;
					var is_view = (v.is_view != "1")? "not_view" : "";
					var is_soldout = (v.is_soldout != "1")? "soldout" : "";
					var is_view_main_1 = (v.is_view_main_1 == "1")? "<span class='view_tag'>??????MD</span>" : "";
					var is_view_main_2 = (v.is_view_main_2 == "1")? "<span class='view_tag'>??????NEW</span>" : "";
					var is_view_main_3 = (v.is_view_main_3 == "1")? "<span class='view_tag'>??????MD(?????????)</span>" : "";
					var is_view_main_4 = (v.is_view_main_4 == "1")? "<span class='view_tag'>??????NEW(?????????)</span>" : "";
					var is_view_main_5 = (v.is_view_main_5 == "1")? "<span class='view_tag'>?????????(?????????)</span>" : "";
					var is_view_main_6 = (v.is_view_main_6 == "1")? "<span class='view_tag'>??????</span>" : "";
					var is_view_main_7 = (v.is_view_main_7 == "1")? "<span class='view_tag'>??????MD(?????????)</span>" : "";
					var is_view_main_8 = (v.is_view_main_8 == "1")? "<span class='view_tag'>??????NEW(?????????)</span>" : "";
					var is_view_main_9 = (v.is_view_main_9 == "1")? "<span class='view_tag'>?????????(?????????)</span>" : "";

					html += '<tr class="item" data-seq="'+v.il_seq+'">';
					html += '	<td class="'+is_view+'"><a class="product_link" href="../test/test_item_product_page.php?no='+v.product_no+'&adn=1" target="_blank">'+v.il_seq+'</a></td>'; // ???????????? ????????? ??????????????? ?????? ??????????????? adn=1 ?????? + test ????????? ??????(????????? ?????? ???????????? ???????????????)
					html += '	<td class="item_image">';
					if(v.goodsRepImage && v.goodsRepImage != ""){
						html += '<div class="img" style="background-image: url(\''+v.goodsRepImage+'\');"></div>';
					}else{
						if(data.data && data.data.file && Object.keys(data.data.file).length > 0){
							console.log(data.data.file[v.il_seq]);
							html += (typeof data.data.file[v.il_seq] != "undefined")? '<div class="img" style="background-image: url(\''+data.data.file[v.il_seq]+'\');"></div>' : '<div class="img"></div>';
						}else{
							html += '<div class="img"></div>';
						}
					}
					html += '	</td>';
					html += '	<td class="lft item_write_btn '+is_soldout+'" data-seq="'+v.il_seq+'">';
					html += '		<div class="product_no">'+v.product_no+'</div>';
					html += '		<div class="item_name">'+v.product_name+'</div>';
					html += '		<div>'+is_view_main_1+''+is_view_main_2+''+is_view_main_3+''+is_view_main_4+''+is_view_main_5+''+is_view_main_6+''+is_view_main_7+''+is_view_main_8+''+is_view_main_9+'</div>';
					html += '	</td>';
					html += '	<td class="item_amount">-</td>';
					html += '	<td class="rgt item_price">'+v.product_price.format()+'???</td>';
					html += '</tr>';
					idx++;
				});
				$(".item_data_list").append(html);

				$.each(data.data.list, function(i, v){
					if(v.is_use_option == "1" && v.product_price == 0){
						get_item_option(v.il_seq);
					}
					if(v.is_supply == "1" && v.supplier == "?????????"){
						get_jbook_list("goods", v.goodsNo, v.il_seq);
					}
				});

				if(idx == 0){
					$("#item_list .more_btn").hide();
					if(item_list_flag == 0){
						html += '<tr>';
						html += '	<td colspan="4" class="no_data">????????? ????????? ????????????.</td>';
						html += '</tr>';
					}
					$(".item_data_list").html('').html(html);
				}else if(idx >= item_list_page_cnt){
					$("#item_list .more_btn").show();
				}else{
					$("#item_list .more_btn").hide();
				}
				
				$("#item_list table caption").html("?????? ?????? ??? : "+data.data.list_cnt+"??? / ??? ?????? ??? : "+data.data.total_cnt+"??? ");
				$("#item_list .more_btn").text("????????? ("+data.data.list_cnt+" / "+data.data.total_cnt+")");


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
			//alert(error + "??????????????????");
			if(xhr.status != 0){
				alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // ?????? ??? ??????
			}
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

				html += '<button type="button" class="toggle_btn" data-seq="'+il_seq+'">?????? <i class="fas fa-toggle-on"></i></button>';
				html += '<div class="option_list" data-seq="'+il_seq+'" style="display: none;">';
				html += '	<ul>';
				$.each(data.data, function(i, v){
					html += '	<li>';
						html += '	<span class="option">'+v.option_name+' : '+v.sale_price.format()+' ???</span>';
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
			//alert(error + "??????????????????");
			if(xhr.status != 0){
				alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // ?????? ??? ??????
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
						result_txt = (v.runout == "1")? '??????' : v.goodsStock;
					});
				}
				$("#item_list .item_data_list tr.item[data-seq='"+il_seq+"'] .item_amount").html(result_txt);
			}else{
				alert(data.data+"("+data.code+")");
				console.log(data.code);
			}
		},
		error: function(xhr, status, error) {
			//alert(error + "??????????????????");
			if(xhr.status != 0){
				alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // ?????? ??? ??????
			}
		}
	});
}

$(document).on("click", "#item_list .toggle_btn", function(){
	$("#item_list .option_list[data-seq='"+$(this).data("seq")+"']").toggle();
});

$(document).on("click", "#item_list .item_write_btn", function(){
	localStorage.setItem('windowscrolltop',$(window).scrollTop()); // scroll position
	localStorage.setItem('itemlistpage',(item_list_flag+item_list_page_cnt)); // more_list_cnt
	history.replaceState('', '', window.location.pathname+'?cate='+search_cate+'&supp='+search_supp+'&word='+search_word+'&sold='+search_sold+'&ivm1='+search_ivm1+'&page='+(item_list_flag+item_list_page_cnt));
	location.href = "<?=$admin_directory?>/item_write.php?seq="+$(this).data("seq")+"&backurl="+encodeURIComponent(window.location.pathname+'?cate='+search_cate+'&supp='+search_supp+'&word='+search_word+'&sold='+search_sold+'&ivm1='+search_ivm1+'&page='+(item_list_flag+item_list_page_cnt)+'&backurl=<?=urlencode($backurl) ?>');
});

// ????????? ?????? ??????
Number.prototype.format = function() {
	if (this == 0) return 0;

	var reg = /(^[+-]?\d+)(\d{3})/;
	var n = (this + '');

	while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');

	return n;
};

// ????????? ???????????? ??? ??? ????????? format() ?????? ??????
String.prototype.format = function() {
	var num = parseFloat(this);
	if (isNaN(num)) return "0";

	return num.format();
};
</script>

<?php
    include "../include/bottom.php";
?>
