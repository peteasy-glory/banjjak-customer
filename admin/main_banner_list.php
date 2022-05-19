<?php
include "../include/top.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

$r_tab = ($_GET["tab"] && $_GET["tab"] != "")? $_GET["tab"] : "";
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
?>

<script src="<?= $js_directory ?>/fontawesome.min.js"></script>
<style>
	table { width: 100%; border-collapse: collapse; margin: 0px; padding: 0px; }
	ul { list-style: none; padding: 0px; margin: 0px; }
	.bjj_top_menu { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; border-bottom: 1px solid #ccc; background-color: rgba(255, 255, 255, 0.8); margin-top: env(safe-area-inset-top); margin-top: constant(safe-area-inset-top); z-index: 10; }
	.bjj_top_menu .bjj-back-btn { position: absolute; left: 8px; top: 12px; }
	.bjj_top_menu .bjj-back-btn img { width: 26px; vertical-align: middle; }
	.bjj_top_menu .bjj_top_title { width: 100%; height: 50px; line-height: 50px; text-align: center; padding: 0px; }
	.bjj_top_menu .bjj_top_title p { padding: 0px; margin: 0px; }
	.bjj_top_menu .bjj_top_home { position: absolute; right: 8px; top: 14px; font-size: 24px; cursor: pointer; }
	.bjj_top_menu .bjj_top_home a { text-decoration: none; color: #000; }

	#main_banner { position: relative; width: 100%; margin-top: calc(env(safe-area-inset-top) + 61px); margin-top: calc(constant(safe-area-inset-top) + 61px); font-family: 'NL2GR'; font-weight: normal; }
	#main_banner .btn_wrap { position:relative; margin: 10px; height: 70px; }
	#main_banner .btn_wrap button { height: 30px; padding: 0px 10px; border: 1px solid #f5bf2e; background-color: #fff; color: #f5bf2e; border-radius: 5px; cursor: pointer; }
	#main_banner .btn_wrap button.insert_banner_btn { position: absolute; right: 0px; bottom: 0px; border: 1px solid #f5bf2e; background-color: #f5bf2e; color: #fff; }
	#main_banner .tab_wrap {  }
	#main_banner .tab_wrap ul.table { display: table; width: 100%; margin: 10px 0px; }
	#main_banner .tab_wrap ul.table li { display: table-cell; width: 25%; text-align: center; height: 40px; line-height: 40px; background-color: #f9f9f9; color: #999; white-space: nowrap; }
	#main_banner .tab_wrap ul.table li.on { background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
	#main_banner .banner_list {  }
	#main_banner .banner_list table { font-size: 16px; font-family: 'NL2GR'; }
	#main_banner .banner_list table tr th { padding: 5px; background-color: #eee; }
	#main_banner .banner_list table tr td { text-align: center; padding: 5px 5px; }
	#main_banner .banner_list table tr td.lft { text-align: left; }
	#main_banner .banner_list table tr td.rgt { text-align: right; }
	#main_banner .banner_list table tr td.no_data { padding: 40px 0px; background-color: #f9f9f9; color: #999; }
	#main_banner .banner_list table tr td.is_use { background-color: #999; }
	#main_banner .banner_list table tr td .banner_img { width: 80px; height: 80px; border: 1px solid #eee; background-color: #eee; border-radius: 10px; background-size: cover; background-repeat: no-repeat; background-position: center; }
	#main_banner .banner_list table tr td .more_btn { height: 40px; padding: 0px 10px; width: 100%; border: 1px solid #ccc; background-color: #eee; border-radius: 10px; }
	#main_banner .banner_list table tr td .is_use_time { font-size: 10px; color: #999; }
	#main_banner .banner_list table tr td .link { display: inline-block; background-color: #fff; border: 1px solid #ccc; border-radius: 5px; text-align: center; padding: 5px; }
</style>

<div class="bjj_top_menu">
    <div class="bjj-back-btn"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/btn_back_2.png"></a></div>
    <div class="bjj_top_title"><p>메인페이지 관리</p></div>
	<div class="bjj_top_home"><a href="../test/test_index_2021.php"><i class="fas fa-home"></i></a></div>
</div>

<div id="main_banner">

</div>

<script>
	var $main_banner = $("#main_banner");
	var limit_0 = 0;
	var limit_1 = 50;
	var tab = ("<?=$r_tab ?>" != "")? "<?=$r_tab ?>" : "1";

	$(function(){
		main_banner_html()
			.then(get_main_banner);
	});

	$main_banner.on("click", ".tab_wrap ul li div", function(){
		tab = $(this).data("tab");
		$main_banner.find(".tab_wrap ul li").removeClass("on");
		$(this).parent().addClass("on");
		history.pushState('', '', '<?=$admin_directory ?>/main_banner_list.php?tab='+tab);
		$main_banner.find(".banner_list_wrap").html('');
		get_main_banner();
	});

	$main_banner.on("click", ".insert_banner_btn", function(){
		location.href = "main_banner_write.php";
	});

	$main_banner.on("click", ".update_banner_btn", function(){
		var seq = $(this).parent().data("id");
		location.href = "main_banner_write.php?seq="+seq;
	});

	$main_banner.on("click", ".more_btn", function(){
		$main_banner.find('tr.more').remove();
		limit_0 += limit_1;
		get_main_banner();
	});

	$main_banner.on("click", ".review_best_btn", function(){
		location.href = "<?=$admin_directory ?>/review_best.php";
	});

	$main_banner.on("click", ".main_banner_shop_list_btn", function(){
		location.href = "<?=$admin_directory ?>/main_banner_shop_list.php";
	});

	$main_banner.on("click", ".item_list_btn", function(){
		location.href = "<?=$admin_directory ?>/item_list.php";
	});

	function main_banner_html(){
		return new Promise(function(resolve, reject) {
			var html = '';

			html += '<div class="btn_wrap">';
			html += '	<button type="button" class="review_best_btn">베스트 후기 관리</button>';
			html += '	<button type="button" class="main_banner_shop_list_btn">추천 펫샵 설정</button>';
			html += '	<button type="button" class="item_list_btn">추천 상품 설정</button>';
			html += '	<button type="button" class="insert_banner_btn">배너추가</button>';
			html += '</div>';
			html += '<div class="tab_wrap">';
			html += '	<ul class="table">';
			html += '		<li class="on">';
			html += '			<div data-tab="1">메인</div>';
			html += '		</li>';
			html += '		<li>';
			html += '			<div data-tab="2">미용/호텔</div>';
			html += '		</li>';
			html += '		<li>';
			html += '			<div data-tab="3">상품(강아지)</div>';
			html += '		</li>';
			html += '		<li>';
			html += '			<div data-tab="4">상품(고양이)</div>';
			html += '		</li>';
			html += '	<ul>';
			html += '</div>';
			html += '<div class="banner_list">';
			html += '	<table>';
			html += '		<colgroup>';
			html += '			<col width="1%" />';
			html += '			<col width="80px" />';
			html += '			<col width="*" />';
			html += '			<col width="100px" />';
			html += '		</colgroup>';
			html += '		<thead>';
			html += '			<tr>';
			html += '				<th>No</th>';
			html += '				<th>배너</th>';
			html += '				<th>배너명<br/>게시일시</th>';
			html += '				<th>작성일시</th>';
			html += '			</tr>';
			html += '		</thead>';
			html += '		<tbody class="banner_list_wrap">';
			html += '		</tbody>';
			html += '	</table>';
			html += '</div>';

			$main_banner.html(html);
			$main_banner.find(".banner_list_wrap").sortable({
				axis: 'y',
				update: function(e, i){
					$main_banner.find(".banner_list_wrap tr").each(function(i, v){
						var post_data = {};
						post_data.mode = "set_update_main_banner";
						post_data.mb_seq = $(this).data("id");
						post_data['odr_'+tab] = parseInt(i)+1;
						console.log(post_data);
						set_update_main_banner(post_data);
					});
				}
			});

			resolve();
		});
	}

	function set_update_main_banner(post_data){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$admin_directory?>/main_banner_ajax.php',
				data: post_data,
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						//$.MessageBox("변경되었습니다.");

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

	function get_main_banner(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$admin_directory ?>/main_banner_ajax.php',
				data: {
					mode: "get_main_banner",
					tab: tab,
					order: "1",
					limit_0: limit_0,
					limit_1: limit_1
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						var html = '';

						if(data.data.list && data.data.list.length > 0){
							$.each(data.data.list, function(i, v){
								var is_use_time = (v.is_use_time == "1")? '<div class="is_use_time">'+v.start_dt+'~<br/>'+v.end_dt+'</div>' : '';
								var is_use = (v.is_use == "1")? '' : 'is_use';
								var link = (v.link && v.link != "")? '<a class="link" href="'+v.link+'" target="_blank">'+v.mb_seq+'</a>' : v.mb_seq;
								html += '<tr id="sort_'+i+'" data-id="'+v.mb_seq+'">';
								html += '	<td class="'+is_use+'">'+link+'</td>';
								html += '	<td><div class="banner_img"></div></td>';
								html += '	<td class="lft update_banner_btn">'+v.title+''+is_use_time+'</td>';
								html += '	<td>'+v.reg_dt+'</td>';
								html += '</tr>';
							});
							if(data.data.list.length > limit_1){
								html += '<tr class="more"><td colspan="4"><button type="button" class="more_btn">더보기('+data.data.list_cnt+' / '+data.data.total_cnt+')</button></td></tr>';
							}
							$main_banner.find(".banner_list_wrap").append(html);
							$.each(data.data.list, function(i, v){
								get_file_list(".banner_list_wrap tr", v.mb_seq, v.banner);
							});
						}else{
							html += '<tr>';
							html += '	<td colspan="4" class="no_data">등록된 배너가 없습니다.</td>';
							html += '</tr>';
							$main_banner.find(".banner_list_wrap").append(html);
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
			resolve();
		});
	}

	function get_file_list(target, seq, img_list){
		return new Promise(function(resolve, reject) {
			console.log(img_list);
			// img_loading
			if(img_list && img_list != ""){
				$.ajax({
					url: '../test/test_fileupload_ajax.php',
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
							$.each(data.data, function(i, v){
								if(i == 0){
									$main_banner.find(target+"[data-id='"+seq+"'] .banner_img").css("background-image", "url('"+v.file_path+"')");
								}
							});

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
				$main_banner.find(target+"[data-id='"+seq+"'] .banner_img").css("background-image", "url('../images/product_img.png')");
			}
		});
	}
</script>

<?php
    include "../include/bottom.php";
?>
