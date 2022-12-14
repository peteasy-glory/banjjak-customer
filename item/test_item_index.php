<?php 
	include "../include/top.php"; 

	$r_category = ($_GET["category"] && $_GET["category"] != "")? $_GET["category"] : "";
	$r_category_group = ($_GET["cg"] && $_GET["cg"] != "")? $_GET["cg"] : "";
	$r_page = ($_GET["page"] && $_GET["page"] != "")? $_GET["page"] : "";
	$backurl = $_GET["backurl"];
	$user_id = isset($_SESSION['gobeauty_user_id']) ? $_SESSION['gobeauty_user_id'] : "";
	$user_name = isset($_SESSION['gobeauty_user_nickname']) ? $_SESSION['gobeauty_user_nickname'] : "";
?>
<script src="<?= $js_directory ?>/fontawesome.min.js"></script>
<link rel="stylesheet" href="<?= $css_directory ?>/m_layout.css?<?= filemtime($upload_static_directory . $css_directory . '/m_layout.css') ?>">
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<style>
	#fixed-menu { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; background-color: #fff; z-index: 2; border-bottom: 1px solid #ccc; z-index: 100; }

	#item_category_list .cate2 { width: 100%; background-color: #f9f9f9; }
	#item_category_list .cate2 ul.table { display: table; width: 100%; padding-top: 10px; }
	#item_category_list .cate2 ul.table>li { position: relative; display: table-cell; width: 33.3%; text-align: center; line-height: 30px; height: 30px; }
	#item_category_list .cate2 ul.table>li div { width: calc(100% - 30px); background-color: #fff; border-radius: 30px; margin: 10px auto; }
	#item_category_list .cate2 ul.table>li.on div { background-color: #f5bf2e; color: #fff; }

	#item_category_list { border-bottom: 1px solid #ccc; padding-bottom: 20px; }
	#item_category_list .cate3 { position: relative; width: 100%; padding: 10px 0px 30px 0px; }
	#item_category_list .cate3 ul.table { display: table; width: 100%; }
	#item_category_list .cate3 ul.table li { display: table-cell; width: 33.3%; height: 40px; line-height: 40px; text-align: center; }
	#item_category_list .cate3 ul.table li>div.on { color: #f5bf2e; }
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
		<div class="center_menu">??????
		</div>
		<div class="right_menu">
		</div>
		<div class="scroll_top"><i class="fas fa-chevron-up"></i></div>
	</div>
</div>
<div id="shop_mainpage">
</div>
<script>
	var $shop_mainpage = $("#shop_mainpage");
	var category = ("<?=$r_category ?>" != "")? "<?=$r_category ?>" : "1"; // ????????? ?????????????????? ???????????? ????????????
	var category_group = ("<?=$r_category_group ?>" != "")? "<?=$r_category_group ?>" : "1";
	var customer_id = "<?=$user_id ?>";
	var category_list = (category != "" && category.indexOf('n') != -1)? category.replace(/n/gi, ',') : category+"";
	var swiper_item = '';
	var swiper_category = '';
	var flag = 0;
	var cnt = ("<?=$r_page ?>" != "")? parseInt("<?=$r_page?>") : 10; // ????????? ????????? ????????? ??????
	var page_loaded = false;
	var lastScrollTop = 0;
	var timer = null;
	var $item_category_list = $("#item_category_list");
	var category_img_list = {};

	$(function(){
		$("#loading").css("align-items", "center").css("justify-content", "center");
		$("#loading").html("<span style='color: #fff; max-width: 50%; text-align:center;'><img src='/pet/images/logo_loading0.gif' style='width: 100px; height: 100px;'/><br/><span style='color:#f5bf2e;'>???</span>???????????? ???<span style='color:#f5bf2e;'>???</span></span>");

		item_html()
			.then(get_cate_list)
			.then(get_item_cate2)
			.then(get_item_cate3)
			.then(get_hot_item_list)
			.then(get_item_list_html)
			.then(get_item_list)
			.then(footer_html)
			.then(get_cart_cnt)
			.then(get_banner);

		page_loaded = (!page_loaded)? true : false;
	});

	$(window).on('popstate', function(event) {
		if(page_loaded){
			window.location = document.location.href;
		}
	});

	// ?????? ?????????
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

		// scrollStop event
		if(timer !== null) { clearTimeout(timer); }
		timer = setTimeout(function() {
			if(st != 0){
				$(document).find(".scroll_top").addClass("on");
			}
		}, 150);
	});

	// ????????? ?????????
	$(document).on("click", ".scroll_top", function(){
		$('html, body').animate({scrollTop : '0'}, 100);
	});

	$shop_mainpage.on("click", ".item_menu_list .item_menu .flex-item", function(){
		category = $(this).data("category")+"";
		category_list = (category != "" && category.indexOf('n') != -1)? category.replace(/n/gi, ',') : category+"";
		history.pushState('', '', '/pet/item/?category='+category);
		flag = 0; // init
		cnt = 10; // init
		console.log(flag, cnt);

		$shop_mainpage.find(".item_menu_list .item_menu .flex-item").removeClass("on");
		$(this).addClass("on");
		$shop_mainpage.find(".hot_item").remove();
		$shop_mainpage.find(".item_list").remove();
		$shop_mainpage.find(".middle_banner").remove();
		$shop_mainpage.find("#footer-contents").remove();
		
		get_cate_list()
			.then(get_hot_item_list)
			.then(get_item_list_html)
			.then(get_item_list)
			.then(footer_html)
			.then(get_cart_cnt)
			.then(get_banner);
	});

	$shop_mainpage.on("click", ".item_list .more_btn", function(){
		if(cnt == 10){
			flag += cnt;
		}else{
			flag = cnt;
			cnt = 10;
		}
		history.replaceState('', '', window.location.pathname+'?category='+category+'&page='+(flag+cnt)+'&cg='+category_group);
		get_item_list();
	});

	$shop_mainpage.on("click", ".item_list .item_link_btn", function(){
		console.log(localStorage.getItem('windowscrolltop'));
		localStorage.setItem('windowscrolltop',$(window).scrollTop()); // scroll position
		console.log(localStorage.getItem('windowscrolltop'));
		localStorage.setItem('itemlistpage',(flag+cnt)); // more_list_cnt
		history.replaceState('', '', window.location.pathname+'?category='+category+'&page='+(flag+cnt)+'&cg='+category_group);
		location.href = "<?=$mainpage_directory ?>/item_product_page.php?no="+$(this).data("product_no")+"&backurl="+encodeURIComponent(window.location.pathname+'?category='+category+'&page='+(flag+cnt)+'&cg='+category_group+'&backurl=<?=urlencode($backurl) ?>');
	});

	$shop_mainpage.on("click", ".hot_item .item_link_btn", function(){
		history.replaceState('', '', window.location.pathname+'?category='+category+'&page='+(flag+cnt)+'&cg='+category_group);
		location.href = "<?=$mainpage_directory ?>/item_product_page.php?no="+$(this).data("product_no")+"&backurl="+encodeURIComponent(window.location.pathname+'?category='+category+'&page='+(flag+cnt)+'&cg='+category_group+'&backurl=<?=urlencode($backurl) ?>');
	});

	// ???????????? ??????
	$shop_mainpage.on("click", "#bj_item_cart", function(){
		history.replaceState('', '', window.location.pathname+'?category='+category+'&page='+(flag+cnt)+'&cg='+category_group);
		location.href = "<?=$item_directory ?>/item_cart.php?backurl="+encodeURIComponent(window.location.pathname+'?category='+category+'&page='+(flag+cnt)+'&cg='+category_group+'&backurl=<?=urlencode($backurl) ?>');
	});

	function item_html(){
		return new Promise(function(resolve, reject) {
			/*
			var html = '';

			html += '<div class="item_menu_list">';
			var is_checked = (category == "31n82")? " on " : "";
			html += '	<div class="item_menu">';
			html += '		<div class="flex-item '+is_checked+'" data-category="31n82"><h5 class="menu_name">??????/??????</h5></div>';
			html += '	</div>';
			var is_checked = (category == "32n83")? " on " : "";
			html += '	<div class="item_menu">';
			html += '		<div class="flex-item '+is_checked+'" data-category="32n83"><h5 class="menu_name">????????????</h5></div>';
			html += '	</div>';
			var is_checked = (category == "29n84")? " on " : "";
			html += '	<div class="item_menu">';
			html += '		<div class="flex-item '+is_checked+'" data-category="29n84"><h5 class="menu_name">????????????</h5></div>';
			html += '	</div>';
			var is_checked = (category == "30n81")? " on " : "";
			html += '	<div class="item_menu">';
			html += '		<div class="flex-item '+is_checked+'" data-category="30n81"><h5 class="menu_name">??????/??????</h5></div>';
			html += '	</div>';
			var is_checked = (category == "50n99")? " on " : "";
			html += '	<div class="item_menu">';
			html += '		<div class="flex-item '+is_checked+'" data-category="50n99"><h5 class="menu_name">????????????</h5></div>';
			html += '	</div>';
			var is_checked = (category == "28n75n76n90")? " on " : "";
			html += '	<div class="item_menu">';
			html += '		<div class="flex-item '+is_checked+'" data-category="28n75n76n90"><h5 class="menu_name">????????????</h5></div>';
			html += '	</div>';
			var is_checked = (category == "33n77n78n79n80")? " on " : "";
			html += '	<div class="item_menu">';
			html += '		<div class="flex-item '+is_checked+'" data-category="33n77n78n79n80"><h5 class="menu_name">?????????</h5></div>';
			html += '	</div>';
			var is_checked = (category == "34")? " on " : "";
			html += '	<div class="item_menu">';
			html += '		<div class="flex-item '+is_checked+'" data-category="34"><h5 class="menu_name">????????????</h5></div>';
			html += '	</div>';
			var is_checked = (category == "36")? " on " : "";
			html += '	<div class="item_menu">';
			html += '		<div class="flex-item '+is_checked+'" data-category="36"><h5 class="menu_name">????????????</h5></div>';
			html += '	</div>';
			html += '</div>';

			$shop_mainpage.html(html);
			*/

			$shop_mainpage.html('<div id="item_category_list"></div>');
			$item_category_list = $shop_mainpage.find("#item_category_list");

			//get_item_cate1()
			//	.then(get_item_cate2)
			//	.then(get_item_cate3);
			//get_item_cate3('27');

			resolve('1');
		});
	}

	$shop_mainpage.on("click", ".get_item_cate2_btn", function(){
		var _this = $(this);
		$shop_mainpage.find('#item_category_list .get_item_cate2_btn').removeClass('on');
		_this.addClass('on');
		get_item_cate2(_this.data('id'))
			.then(get_item_cate3);
	});

	$shop_mainpage.on("click", ".get_item_cate3_btn", function(){
		var _this = $(this);
		$shop_mainpage.find('#item_category_list .get_item_cate3_btn').removeClass('on');
		category_group = 1;
		history.replaceState('', '', window.location.pathname+'?category='+category+'&page='+(flag+cnt)+'&cg='+category_group);
		_this.addClass('on');
		get_item_cate3(_this.data('id'));
	});

	$shop_mainpage.on("click", ".link_item_list_btn", function(){
		var _this = $(this);
		//location.href = '<?=$item_directory ?>/test_item_index.php?category='+_this.data('id')+'&cg='+category_group;
		category = _this.data("id")+"";

		if(category == "2"){ // ????????????(????????? ???????????? ???,???,????????? ?????? ??????)
			var _category_list = [];
			for(var _i = 3; _i <= 11; _i++){
				_category_list.push(_i);
			}
			category_list = _category_list.join(',');
			$("#fixed-menu .center_menu").text('????????????');
		}else if(category == "12"){
			var _category_list = [];
			for(var _i = 13; _i <= 26; _i++){
				_category_list.push(_i);
			}
			category_list = _category_list.join(',');
			$("#fixed-menu .center_menu").text('????????????');
		}else if(category == "27"){
			var _category_list = [];
			for(var _i = 28; _i <= 50; _i++){
				_category_list.push(_i);
			}
			category_list = _category_list.join(',');
			$("#fixed-menu .center_menu").text('????????????');
		}else{
			category_list = (category != "" && category.indexOf('n') != -1)? category.replace(/n/gi, ',') : category+"";
		}
		console.log(category_list);
		//history.pushState('', '', '/pet/item/?category='+category);
		history.replaceState('', '', window.location.pathname+'?category='+category+'&page='+(flag+cnt)+'&cg='+category_group);
		flag = 0; // init
		cnt = 10; // init
		console.log(flag, cnt);

		$shop_mainpage.find(".hot_item").remove();
		$shop_mainpage.find(".item_list").remove();
		$shop_mainpage.find(".middle_banner").remove();
		$shop_mainpage.find("#footer-contents").remove();
		
		item_html()
			.then(get_cate_list)
			.then(get_item_cate2)
			.then(get_item_cate3)
			.then(get_hot_item_list)
			.then(get_item_list_html)
			.then(get_item_list)
			.then(footer_html)
			.then(get_cart_cnt)
			.then(get_banner);
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

						$item_category_list.html(html);
						resolve(data.data[0].ic_seq);
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
	}

	function get_item_cate2(node_path){
		return new Promise(function(resolve, reject) {
			//$item_category_list.find('.cate2').remove(); // init
			$item_category_list.find('.cate3').remove(); // init
			console.log(node_path);

			var _node_path = node_path.split('^');

			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_cate2",
					cate1 : _node_path[0]
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
							var is_on = (_node_path[1] == v.ic_seq)? 'on' : '';
							html += '		<li class="get_item_cate3_btn '+is_on+'" data-id="'+v.node_path+'">';
							html += '			<div>'+v.cate_name+'</div>';
							html += '		</li>';
						});
						html += '	</ul>';
						html += '</div>';

						//$item_category_list.find('.cate1').after(html);
						$item_category_list.html(html);
						resolve(node_path);
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
	}

	function get_item_cate3(node_path){
		return new Promise(function(resolve, reject) {
			$item_category_list.find('.cate3').remove(); // init
			console.log(node_path);
			
			var _node_path = node_path.split('^');

			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_cate3",
					cate2 : _node_path[1],
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
						var cnt = 0; // + all

						if(category == "2"){ // ????????????(????????? ???????????? ???,???,????????? ?????? ??????)
							var _category_list = [];
							for(var _i = 3; _i <= 11; _i++){
								_category_list.push(_i);
							}
							category_list = _category_list.join(',');
							$("#fixed-menu .center_menu").text('????????????');
						}else if(category == "12"){
							var _category_list = [];
							for(var _i = 13; _i <= 26; _i++){
								_category_list.push(_i);
							}
							category_list = _category_list.join(',');
							$("#fixed-menu .center_menu").text('????????????');
						}else if(category == "27"){
							var _category_list = [];
							for(var _i = 28; _i <= 50; _i++){
								_category_list.push(_i);
							}
							category_list = _category_list.join(',');
							$("#fixed-menu .center_menu").text('????????????');
						}

						html += '<div class="cate3">';
						html += '	<div class="swiper-container-category">';
						html += '		<div class="swiper-wrapper">';
						html += '			<div class="swiper-slide">';
						html += '				<ul class="table">';
						$.each(data.data, function(i, v){
							var is_on = '';
							if(cnt == 0){
								is_on = (_node_path[1] == category)? 'on' : '';
								html += '					<li>';
								html += '						<div class="link_item_list_btn '+is_on+'" data-id="'+_node_path[1]+'">';
								html += '							<div class="value">????????????</div>';
								html += '						</div>';
								html += '					</li>';
								cnt++;
							}

							is_on = (v.ic_seq == category)? 'on' : '';
							if(v.ic_seq == category){ // title
								$("#fixed-menu .center_menu").text(v.cate_name);
							}

							if(cnt % 9 == 0){
								html += '				</ul>';
								html += '			</div>'; 
								html += '			<div class="swiper-slide">';
								html += '				<ul class="table">';
							}else if(cnt % 3 == 0){
								html += '				</ul>';
								html += '				<ul class="table">';
							}

							if(v.ic_seq == '7' || v.ic_seq == '8' || v.ic_seq == '9' || v.ic_seq == '14' || v.ic_seq == '25'){
							}else{
								html += '					<li>';
								html += '						<div class="link_item_list_btn '+is_on+'" data-id="'+v.ic_seq+'">';
								html += '							<div class="value">'+v.cate_name+'</div>';
								html += '						</div>'; 
								html += '					</li>';

								cnt++;
							}
						});

						var tmp_length = data.data.length - 1;
						$item_category_list.find('.get_item_cate3_btn').each(function(i, v){
							if($(this).hasClass('on') && $(this).data('id') == '1^2'){
								tmp_length = parseInt(tmp_length) - 4;
								console.log('1');
							}
						});

						if(tmp_length % 3 != 0){ // ALL ??????
							for(var _i = 0; _i < 3 - tmp_length % 3; _i++){
								html += '					<li>';
								html += '						<div class="no_data">';
								html += '							<div></div>';
								html += '						</div>'; 
								html += '					</li>';
							}
						}
				
						html += '			</div>'; 
						html += '		</div>';
						html += '		<div class="swiper-pagination"></div>';
						html += '	</div>';
						html += '</div>';

						$item_category_list.find('.cate2').after(html);
						//$item_category_list.html(html);
						
						//category_group = 1;
						swiper_category = new Swiper('.swiper-container-category', {
							slidesPerView: 'auto',
							spaceBetween: 30,
							initialSlide: (category_group - 1),
							pagination: {
								el: '.swiper-pagination',
								type: 'fraction',
							},
							on: {
								slideChangeTransitionEnd: function(){
									category_group = this.activeIndex + 1;
								},
							},
						});

						resolve();
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
	}

	function get_cate_list(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_cate_list",
					cate: category_list
				},
				type: 'POST',
				dataType: 'JSON',
				beforeSend: function(){
					$("#loading").css("display", "flex");
				},
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						var node_path = '';
						$("#fixed-menu .center_menu").text(data.data[0].cate_name);
						if(data.data && data.data.length > 0){
							$.each(data.data, function(i, v){
								node_path = v.node_path;
							});
						}

						resolve(node_path);
					}else{
						alert(data.data+"("+data.code+")");
						console.log(data.code);
					}
				},
				error: function(xhr, status, error) {
					//alert(error + "??????????????????1");
					if(xhr.status != 0){
						alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // ?????? ??? ??????
					}
				}
			});
		});
	}

	function get_hot_item_list(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_item_list",
					category: category_list,
					is_view_main_6: "1",
					is_view: "2", // ????????????
					is_shop: "2", // ?????????
					flag: 0,
					cnt: 10
				},
				type: 'POST',
				dataType: 'JSON',
				beforeSend: function(){
					$("#loading").css("display", "flex");
				},
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						//console.log(data.sql);

						var html = '';
						
						if(data.data.list && data.data.list.length > 0){
							html += '<div class="hot_item">';
							html += '	<div class="title"><span>?????? ??????</span></div>';
							html += '	<div class="swiper-container-item">';
							html += '		<div class="swiper-wrapper">';
							$.each(data.data.list, function(i, v){
								var percent = (parseInt(v.product_price) == 0 || parseInt(v.sale_price) == 0)? 0 : 100 - (parseInt(v.sale_price) / parseInt(v.product_price) * 100);
								html += '			<div class="swiper-slide">';
								html += '				<a href="javascript:;">';
								html += '					<div class="item item_link_btn" data-id="'+v.il_seq+'" data-product_no="'+v.product_no+'">';
								if(v.is_soldout == "2"){
									html += '						<div class="item_image soldout"></div>';
									html += '						<div class="item_name">'+v.product_name+'</div>';
									html += '						<div class="item_price"><span class="soldout">????????????</span></div>';
								}else{
									html += '						<div class="item_image"></div>';
									html += '						<div class="item_name">'+v.product_name+'</div>';
									html += '						<div class="item_price"><span class="percent">'+Math.round(percent)+'%</span> <span class="sale_price">'+v.sale_price.format()+'???</span></div>';
									html += '						<div class="item_rating"><span class="no_star"><i class="fas fa-star"></i></span> <span>0</span> (<span>0)</div>';
								}
								html += '					</div>';
								html += '				</a>';
								html += '			</div>';
							});
							html += '		</div>';
							html += '		<!-- Add Pagination -->';
							html += '		<div class="swiper-pagination"></div>';
							html += '	</div>';
							html += '</div>';
							$shop_mainpage.append(html);
							swiper_item = new Swiper('.swiper-container-item', {
								slidesPerView: 'auto',
								spaceBetween: 30,
								loop: (data.data.list.length > 1)? true : false,
							});

							//loading image
							$.each(data.data.list, function(i, v){
								var target = ".hot_item .item";

								get_file_list(target, v.il_seq, v.product_img, v.goodsRepImage);
								get_item_review(target, v.il_seq, v.product_no);
								if(v.is_use_option == "1" && v.sale_price == "0"){ // ???????????? + ?????? 0???
									get_item_option(target, v.il_seq);
								}
							});
						}else{
							html += '<div class="hot_item">';
							html += '	<div class="title"><span>?????? ??????</span></div>';
							html += '		<div class="no_data">';
							html += '			??????????????? ??????, ??????';
							html += '		</div>';
							html += '	</div>';
							html += '</div>';

							$shop_mainpage.append(html);
						}
						resolve();
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
	}

	function get_item_list_html(){
		return new Promise(function(resolve, reject) {
			var html = '';

			html += '<div class="item_list">';
			html += '	<div class="caption">';
			html += '		<div class="total_cnt">0?????? ??????</div>';
			html += '		<div class="order">';
			//html += '			<select class="orderby">';
			//html += '				<option value="">????????????</option>';
			//html += '			</select>';
			html += '		</div>';
			html += '	</div>';
			html += '	<div class="new_item_wrap">';
			html += '	</div>';
			html += '</div>';

			$shop_mainpage.append(html);
			resolve();
		});
	}

	function get_item_list(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_item_list",
					category: category_list,
					is_view: "1", // ???????????? 1-??????, 2-??????
					is_shop: "2", // ?????????
					flag : flag,
					cnt : cnt
				},
				type: 'POST',
				dataType: 'JSON',
				beforeSend: function(){
					$("#loading").css("display", "flex");
				},
				success: function(data) {
					if(data.code == "000000"){
						$("#loading").hide();
						$shop_mainpage.find(".more_btn").remove();
						console.log(data.data);
						console.log(data.sql);

						var html = '';

						if(data.data.list && data.data.list.length > 0){
							$.each(data.data.list, function(i, v){
								var percent = (parseInt(v.product_price) == 0 || parseInt(v.sale_price) == 0)? 0 : 100 - (parseInt(v.sale_price) / parseInt(v.product_price) * 100);
								html += '		<a href="javascript:;">';
								html += '			<div class="item item_link_btn" data-id="'+v.il_seq+'" data-product_no="'+v.product_no+'">';
								if(v.is_soldout == "2"){
									html += '				<div class="item_image soldout"></div>';
									html += '				<div class="item_name">'+v.product_name+'</div>';
									html += '				<div class="item_price"><span class="soldout">????????????</span></div>';
								}else{
									html += '				<div class="item_image"></div>';
									html += '				<div class="item_name">'+v.product_name+'</div>';
									html += '				<div class="item_price"><span class="percent">'+Math.round(percent)+'%</span> <span class="sale_price">'+v.sale_price.format()+'???</span></div>';
									html += '				<div class="item_rating"><span class="no_star"><i class="fas fa-star"></i></span> <span>0</span> (<span>0)</div>';
								}
								html += '			</div>';
								html += '		</a>';
							});
							$shop_mainpage.find('.new_item_wrap').append(html);
							$shop_mainpage.find('.item_list .total_cnt').text(data.data.total_cnt+'?????? ??????');

							if(data.data.list.length == 10 && data.data.list_cnt != data.data.total_cnt){
								$shop_mainpage.find('.new_item_wrap').after('<div class="more_btn">????????? ('+data.data.list_cnt+' / '+data.data.total_cnt+')</div>');
							}
							
							//loading image
							$.each(data.data.list, function(i, v){
								var target = ".item_list .item";

								get_file_list(target, v.il_seq, v.product_img, v.goodsRepImage);
								get_item_review(target, v.il_seq, v.product_no);
								if(v.is_use_option == "1" && v.sale_price == "0"){ // ???????????? + ?????? 0???
									get_item_option(target, v.il_seq);
								}
							});
						}else{
							if(data.data.list_cnt != data.data.total_cnt){
								html += '		<div class="no_data">';
								html += '			??????????????? ??????, ??????';
								html += '		</div>';

								$shop_mainpage.find('.new_item_wrap').append(html);
							}
						}
						
						resolve();
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
	}

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
										$shop_mainpage.find(target+"[data-id='"+il_seq+"'] .item_image").css("background-image", "url('"+v.file_path+"')");
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
						//alert(error + "??????????????????");
						if(xhr.status != 0){
							alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // ?????? ??? ??????
						}
					}
				});
			}else{
				if(goodsRepImage != ""){
					$shop_mainpage.find(target+"[data-id='"+il_seq+"'] .item_image").css("background-image", "url('"+goodsRepImage+"')");
				}else{
					$shop_mainpage.find(target+"[data-id='"+il_seq+"'] .item_image").css("background-image", "url('../images/product_img.png')");
				}
			}
		});
	}

	function get_item_option(target, il_seq){
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
										$shop_mainpage.find(target+"[data-id='"+il_seq+"'] .item_price .sale_price").text(sale_price.format()+'???');
										$shop_mainpage.find(target+"[data-id='"+il_seq+"'] .item_price .percent").text(Math.round(percent)+'%');
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
						//alert(error + "??????????????????");
						if(xhr.status != 0){
							alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // ?????? ??? ??????
						}
					}
				});
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
								$shop_mainpage.find(target+"[data-id='"+il_seq+"'] .item_rating").html('<span class="star"><i class="fas fa-star"></i></span> '+rating+' ('+data.data.length+')');
							}

							resolve();
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
		});
	}

	function footer_html(){
		return new Promise(function(resolve, reject) {
			var html = '';

			html += '<div id="footer-contents">';
			html += '	<div class="info_wrap">';
			html += '		<div class="info_icon"><img src="../images/icon_call.png"></div>';
			html += '		<div class="info_1">????????????</div>';
			html += '		<div class="info_2"><?=$company_help_number?></div>';
			html += '		<div class="info_3"><?=$company_help_email?></div>';
			html += '	</div>';
			html += '	<br>';
			html += '	<div class="bottom_notice">';
			html += '		<a style="color:#000;" href="<?=$mainpage_directory ?>/view_event2.php">?????????/??????????????????</a> | <a style="color:#000;" href="<?=$mainpage_directory ?>/shop_entry.php">????????????/??????????????????</a> ';
			html += '	</div>';
			html += '	<br/>';
			html += '	<div class="bottom_notice">';
			html += '		<a href="<?=$mainpage_directory ?>/terms_of_service.php">????????????</a> | <a href="<?=$mainpage_directory ?>/privacy_policy.php">????????????????????????</a> | <a href="<?=$mainpage_directory ?>/proprietorship.php">?????????????????????</a>';
			html += '	</div>';
			html += '	<div class="f_wrap"> ';
			html += '		<div class="bottom_notice">';
			html += '			<div class="f_subwrap">';
			html += '				<ul>';
			html += '					<li>????????????(PetEasy Co.,Ltd) | ?????????:?????????</li>';
			html += '					<li>????????????????????? 157-86-01070 </li>';
			html += '					<li>??????????????? ??? 2021-????????????-0183???</li>';
			html += '					<li>????????? ????????? ??????6 5??? ?????????????????????????????? </li>';
			html += '					<li>????????????????????? ????????? privacy@peteasy.kr</li>';
			html += '					<li>?? PetEasy Co. Ltd. All Rights Reserved.</li>';
			html += '				</ul>';
			html += '				<ul>';
			html += '					<li>????????? ??????????????????????????? ??????????????? ???????????? ????????????.</li>';
			html += '					<li>????????? ????????? ?????????????????? ??? ????????? ?????? ???????????? ????????????. </li>';
			html += '					<li>?????? ????????? ???????????? ????????? ????????? ?????? ??????????????? ????????? ?????? </li>';
			html += '					<li>?????????.</li>';
			html += '				</ul>';
			html += '			</div>';
			html += '		</div>';
			html += '	</div>';

			$shop_mainpage.append(html);
			$("#loading").hide();
			resolve();

			console.log(localStorage.getItem('windowscrolltop'));
			if(localStorage.getItem('windowscrolltop') > 0){
				$('html, body').animate({
					scrollTop: localStorage.getItem('windowscrolltop')
				}, 500, function(){
					localStorage.setItem('windowscrolltop', 0); // scroll position					
				});
			}
		});
	}

	function get_banner(){
		return new Promise(function(resolve, reject) {
			var html = '';

				html += '<div class="middle_banner">';
				html += '	??????2';
				html += '</div>';
				//$shop_mainpage.find(".item_list").after(html);

			resolve();
		});
	}

	function get_cart_cnt(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode: "get_item_cart",
					is_session: "1",
					customer_id: customer_id
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);

						var cart_cnt = 0;
						if(data.data && data.data.length > 0){
							cart_cnt = data.data.length;
						}

						$shop_mainpage.find('#bj_item_cart .cart_cnt').text(cart_cnt);

						resolve();
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
	}

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
<?php include "../include/bottom.php"; ?>