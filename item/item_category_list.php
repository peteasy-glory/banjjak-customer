<?php
include "../include/top.php";

$backurl = $_GET["backurl"];
$r_category = ($_GET["category"] && $_GET["category"] != "")? $_GET["category"] : "2";
$r_cate = ($_GET["cate"] && $_GET["cate"] != "")? $_GET["cate"] : "1";
$r_selected_sort = ($_GET['selected_sort'] && $_GET['selected_sort'] != "")? $_GET['selected_sort'] : "강아지";
$user_id = isset($_SESSION['gobeauty_user_id']) ? $_SESSION['gobeauty_user_id'] : "";
$user_name = isset($_SESSION['gobeauty_user_nickname']) ? $_SESSION['gobeauty_user_nickname'] : "";
?>
<script src="<?= $js_directory ?>/fontawesome.min.js"></script>
<link rel="stylesheet" href="<?= $css_directory ?>/m_layout.css?<?= filemtime($upload_static_directory . $css_directory . '/m_layout.css') ?>">
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">

<style>
	#fixed-menu { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; background-color: #fff; z-index: 2; border-bottom: 1px solid #ccc; }

	#item_catrgory_list { margin: 50px 0px; }
	
	#item_catrgory_list .cate1 { width: 100%; background-color: #eee; }
	#item_catrgory_list .cate1 ul.table { display: table; width: 100%; }
	#item_catrgory_list .cate1 ul.table>li { position: relative; display: table-cell; width: 50%; text-align: center; line-height: 50px; height: 50px; border-bottom: 1px solid #eee; }
	#item_catrgory_list .cate1 ul.table>li.on { overflow: hidden; color: #f5bf2e; border-bottom: 1px solid #eee; }
	#item_catrgory_list .cate1 ul.table>li.on:before { content: ''; position: absolute; left: 50%; bottom: -13px; display: inline-block; width: 150px; margin-left: -75px; height: 20px; border-radius: 10px; background-color: #f5bf2e; }
	
	#item_catrgory_list .cate2 { width: 100%; background-color: #f9f9f9; }
	#item_catrgory_list .cate2 ul.table { display: table; width: 100%; padding-top: 10px; }
	#item_catrgory_list .cate2 ul.table>li { position: relative; display: table-cell; width: 33.3%; text-align: center; line-height: 30px; height: 30px; }
	#item_catrgory_list .cate2 ul.table>li div { width: calc(100% - 30px); background-color: #fff; border-radius: 30px; margin: 10px auto; }
	#item_catrgory_list .cate2 ul.table>li.on div { background-color: #f5bf2e; color: #fff; }

	#item_catrgory_list .cate3 { width: 100%; height: calc(100vh - 100px); background-color: #fff; overflow-y: auto; padding-bottom: 50px; }
	#item_catrgory_list .cate3 .cate3_wrap { display: flex; justify-content: flex-start; flex-wrap: wrap; align-items: flex-start; width: calc(100% - 18px); margin: 0 auto; border: 0px solid #f00; overflow-y: auto; }
	#item_catrgory_list .cate3 .cate3_wrap .flex-item { position: relative; flex: 1 1 25%; color: #fff; text-align: center;box-sizing: border-box; white-space: nowrap; font-family: 'NL2GR'; border: 0px solid #ccf; height: 100px; }
	#item_catrgory_list .cate3 .cate3_wrap .flex-item:before { content: ''; display: inline-block; width: 30px; height: 30px; background-color: rgba(245, 191, 46, 0.2); position: absolute; left: 50%; top: 45%; border-radius: 15px; margin-left: -15px; margin-top: -20px; }
	#item_catrgory_list .cate3 .cate3_wrap .flex-item:after { content: ''; display: block; }
	#item_catrgory_list .cate3 .cate3_wrap .flex-item .inner { position: absolute; left: calc(50% - 40px); top: calc(50% - 40px); width: 80px; height: 80px; border-radius: 10px; box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.3); margin:0 auto; z-index: 1; }
	#item_catrgory_list .cate3 .cate3_wrap .flex-item .inner .image { position: absolute; left: 15px; top: 5px; width: 50px; height: 50px; background-size: 50px 50px; background-repeat: no-repeat; background-position: center; }
	#item_catrgory_list .cate3 .cate3_wrap .flex-item .inner .value { position: absolute; left: 5px; bottom: 5px; width: calc(100% - 10px); font-family: 'NL2GR'; font-weight: Bold; color: #61373D; font-size: 12px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
	#item_catrgory_list .cate3 .cate3_wrap .flex-item.no_item .inner { background-color: inherit; box-shadow: none; }
	#item_catrgory_list .cate3 .cate3_wrap .flex-item.no_item:before { display: none; }
	
</style>

<div id="fixed-menu">
	<div class="fixed-menu-wrap">
		<div class="left_menu">
		<?php if($backurl){ ?>
			<a href="<?=$backurl ?>"><i class="fas fa-chevron-left"></i></a>
		<?php }else{ ?>
			<a href="<?=$mainpage_directory ?>/"><i class="fas fa-chevron-left"></i></a>
		<?php } ?>
		</div>
		<div class="center_menu">상품 카테고리
		</div>
		<div class="right_menu">
		</div>
		<div class="scroll_top"><i class="fas fa-chevron-up"></i></div>
	</div>
</div>

<div id="item_catrgory_list">
</div>

<script>
	var $item_catrgory_list = $("#item_catrgory_list");
	var category_img_list = {};
	var category = "<?=$r_category ?>";
	var cate = "<?=$r_cate ?>";

	$(function(){
		get_item_cate2(cate)
			.then(get_item_cate3);
	});

	$item_catrgory_list.on("click", ".get_item_cate2_btn", function(){
		var _this = $(this);
		$item_catrgory_list.find('.get_item_cate2_btn').removeClass('on');
		_this.addClass('on');
		get_item_cate2(_this.data('id'))
			.then(get_item_cate3);
	});

	$item_catrgory_list.on("click", ".get_item_cate3_btn", function(){
		var _this = $(this);
		$item_catrgory_list.find('.get_item_cate3_btn').removeClass('on');
		_this.addClass('on');
		get_item_cate3(_this.data('id'));
	});

	$item_catrgory_list.on("click", ".link_item_list_btn", function(){
		var _this = $(this);
		history.replaceState('', '', window.location.pathname+'?backurl=<?=urlencode($backurl) ?>&category='+category);
		location.href = '<?=$item_directory ?>/?category='+_this.data('id')+'&backurl='+encodeURIComponent(window.location.pathname+'?backurl=<?=urlencode($backurl) ?>&category='+category);
	});
	

	function get_item_cate1(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_cate1"
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

						html += '<div class="cate1">';
						html += '	<ul class="table">';
						$.each(data.data, function(i, v){
							var is_on = (i == 0)? 'on' : '';
							html += '		<li class="get_item_cate2_btn '+is_on+'" data-id="'+v.ic_seq+'">';
							html += '			<div>'+v.cate_name+'</div>';
							html += '		</li>';
						});
						html += '	</ul>';
						html += '</div>';

						//$item_catrgory_list.html(html);
						resolve(data.data[0].ic_seq);
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

	function get_item_cate2(cate1_no){
		return new Promise(function(resolve, reject) {
			//$item_catrgory_list.find('.cate2').remove(); // init
			$item_catrgory_list.find('.cate3').remove(); // init

			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_cate2",
					cate1 : cate1_no
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

						html += '<div class="cate2">';
						html += '	<ul class="table">';
						$.each(data.data, function(i, v){
							var is_on = (category == v.ic_seq)? 'on' : '';
							html += '		<li class="get_item_cate3_btn '+is_on+'" data-id="'+v.ic_seq+'">';
							html += '			<div>'+v.cate_name+'</div>';
							html += '		</li>';
						});
						html += '	</ul>';
						html += '</div>';

						//$item_catrgory_list.find('.cate1').after(html);
						$item_catrgory_list.html(html);
						//resolve(data.data[0].ic_seq);
						resolve(category);
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

	function get_item_cate3(cate2_no){
		return new Promise(function(resolve, reject) {
			$item_catrgory_list.find('.cate3').remove(); // init

			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_cate3",
					cate2 : cate2_no,
					orderby : "1"
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

						html += '<div class="cate3">';
						html += '	<div class="cate3_wrap">';
							html += '		<div class="flex-item link_item_list_btn" data-id="'+cate2_no+'">';
							html += '			<div class="inner">'
							html += '				<div class="image" style="background-image: url(\'/pet/images/item_menu_icon_all.png\')"></div>';
							html += '				<div class="value">ALL</div>';
							html += '			</div>';
							html += '		</div>'; 
						$.each(data.data, function(i, v){
							if(v.ic_seq == '7' || v.ic_seq == '8' || v.ic_seq == '9' || v.ic_seq == '14' || v.ic_seq == '25' || v.ic_seq == '57' || v.ic_seq == '65' || v.ic_seq == '71' || v.ic_seq == '72'){
							}else{
								html += '		<div class="flex-item link_item_list_btn" data-id="'+v.ic_seq+'">';
								html += '			<div class="inner">'
								html += '				<div class="image" style="background-image: url(\'/pet/images/item_menu_icon_'+v.node_path.replace(/\^/gi, '_')+'.png\')"></div>';
								html += '				<div class="value">'+v.cate_name+'</div>';
								html += '			</div>';
								html += '		</div>'; 
							}
						});

						var tmp_length = data.data.length - 1;
						$item_catrgory_list.find('.get_item_cate3_btn').each(function(i, v){
							if($(this).hasClass('on') && $(this).data('id') == '2'){
								tmp_length = parseInt(tmp_length) - 1;
							}else if($(this).hasClass('on') && $(this).data('id') == '12'){
								tmp_length = parseInt(tmp_length) - 2;
//							}else if($(this).hasClass('on') && $(this).data('id') == '74'){
//								tmp_length = parseInt(tmp_length) + 3;
							}
						});

						if(tmp_length % 4 - 1 != 0){ // ALL 제외
							for(var _i = 0; _i < tmp_length % 4; _i++){
								html += '		<div class="flex-item no_item">';
								html += '			<div class="inner">'
								html += '			</div>';
								html += '		</div>'; 
							}
						}
						html += '	</div>';
						html += '</div>';

						$item_catrgory_list.find('.cate2').after(html);
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
</script>
<?php include "../include/bottom.php"; ?>