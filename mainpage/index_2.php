<?php
include "../include/top.php";

$r_top = ($_GET['top'] && $_GET['top'] != "")? $_GET['top'] : "";
$r_middle = ($_GET['middle'] && $_GET['middle'] != "")? $_GET['middle'] : "";
$r_lat = ($_GET['lat'] && $_GET['lat'] != "")? $_GET['lat'] : "";
$r_lng = ($_GET['lng'] && $_GET['lng'] != "")? $_GET['lng'] : "";
$r_tab = ($_GET['tab'] && $_GET['tab'] != "")? $_GET['tab'] : "";
$user_id = isset($_SESSION['gobeauty_user_id']) ? $_SESSION['gobeauty_user_id'] : "";
$user_name = isset($_SESSION['gobeauty_user_nickname']) ? $_SESSION['gobeauty_user_nickname'] : "";

// shop_redirect
/*
if($user_id != ""){
	if(!isset($_SESSION["my_shop_flag"])){
		$sql = "
			SELECT *
			FROM tb_customer
			WHERE id = '".$user_id."'
		";
		$result = mysql_query($sql);
		while($row = mysql_fetch_assoc($result)){
			if($row["my_shop_flag"] == "1"){
				$_SESSION["my_shop_flag"] = "1";
				echo "<meta http-equiv='refresh' content='0; url=/pet/shop/index.php'>";
			}
		}
	}
}
*/

?>
<script src="<?= $js_directory ?>/fontawesome.min.js"></script>
<script src="<?= $js_directory ?>/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $css_directory ?>/jquery.fancybox.css?<?= filemtime($upload_static_directory . $css_directory . '/jquery.fancybox.css') ?>">
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<link rel="stylesheet" href="<?= $css_directory ?>/m_layout_2.css?<?= filemtime($upload_static_directory . $css_directory . '/m_layout_2.css') ?>">

<style>
	#mainpage_main_index .item_menu_tab ul.table { display: table; width: 100%; padding-top: 10px; }
	#mainpage_main_index .item_menu_tab ul.table>li { position: relative; display: table-cell; width: 33.3%; text-align: center; line-height: 30px; height: 30px; }
	#mainpage_main_index .item_menu_tab ul.table>li div { width: calc(100% - 30px); background-color: #fff; border-radius: 30px; margin: 10px auto; }
	#mainpage_main_index .item_menu_tab ul.table>li.on div { background-color: #f5bf2e; color: #fff; }
	#mainpage_main_index .item_menu_list { display: none; width: calc(100% - 20px); margin: 0px auto; }
	#mainpage_main_index .item_menu_list.on { display: block; }
	#mainpage_main_index .item_menu_list .item_menu .flex-item img { width: 40px; height: 40px; margin: 10px 0px; }
	

	@media screen and (min-width: 430px) {
		#mainpage_main_index .swiper-container .swiper-slide>div a>.item_image { background-size: auto 230px; } 
		#mainpage_main_index .hot_pick_shop_wrap li .shop_image { background-size: auto 200px; }
		#mainpage_main_index .shop_list_wrap ul.shop_data li .shop_image { background-size: auto 200px; }
	}

	/* ????????? ?????? css */
	.search_item { width: 40px;}
	.sort_radio { display: flex;}
	.chk_rad { font-size: 10px;}

</style>

<div id="fixed-menu">
	<div class="fixed-menu-wrap">
		<div class="left_menu">
			<a href="<?=$mainpage_directory ?>/mainpage_my_menu.php">
				<i class="fas fa-bars"></i>
			</a>
		</div>
		<div class="center_menu">
			<!--a href="<?= $mainpage_directory ?>/"-->
				<img src="<?= $image_directory ?>/main_logo.png" />
			<!--/a-->
<!-- 			<select id="item_select" style="font-size:13px;width:80px;height:30px;border-radius:15px;padding:5px 5px 5px 8px;box-sizing:border-box;border:2px solid #f6bf2e;outline:none;position:absolute;right:10px;top:10px;line-height:30px;background:#fff;"> -->
<!-- 				<option value="?????????" selected>?????????</option> -->
<!-- 				<option value="?????????">?????????</option> -->
<!-- 			</select> -->
		</div>
		<div class="right_menu">
<!-- 			<a href="<?= $mainpage_directory ?>/manage_user_info.php"> -->
<!-- 				<i class="fas fa-user-circle"></i> -->
<!-- 			</a> -->
		</div>
	</div>
	<div class="fixed-menu-wrap2">
		<ul class="tab table">
			<li class="on" data-id="1"><span>HOME</span></li>
			<li data-id="2"><span>?????? / ??????</span></li>
			<li data-id="3"><span>??????</span></li>
		</ul>
	</div>
</div>

<div id="mainpage_main_index">
</div>





<div id="popup_wrap">
	<div class="custom-modal-content">
		<div class="popup_img">
			<div class="swiper-container_front">
				<div class="swiper-wrapper">
					<!--?????? ????????????-->
					<div class="swiper-slide">
						<a href="javascript:;">
							<img src="../images/sul_banner.jpg" />
						</a>
					</div>

				</div>
				<!-- Add Arrows -->
				<div class="next"><i class="fas fa-chevron-left"></i></div>
				<div class="prev"><i class="fas fa-chevron-right"></i></div>
				<div class="swiper-pagination_front"></div>
			</div>
		</div>
	</div>
</div>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'UA-152043924-1');
</script>


<script>
	var $mainContent = $("#mainpage_main_index");
	var tmp_val = checkMobile2();
	var lastScrollTop = 0;
	var tab = ("<?=$r_tab ?>" != "")? "<?=$r_tab ?>" : 1;
	var customer_id = "<?=$user_id ?>";
	var is_artist = 0;
	var is_android = 0;
	var r_top = "<?=$r_top ?>";
	var r_middle = "<?=$r_middle ?>";
	var r_lat = "<?=$r_lat ?>";
	var r_lng = "<?=$r_lng ?>";
	var location_txt = (r_top != "" && r_middle != "")? r_top+" "+r_middle : "?????? ??????";
	location_txt = (location_txt == "?????? ??????" && r_lat != "" && r_lng != "")? "??? ??????" : location_txt;

	// ????????? ?????? ??????
//	var location_tt = (r_top != "" && r_middle != "")? r_top+" "+r_middle : "??? ??????";
	
	// ????????? ????????? ??????
	var selected_sort = '';
	var select_dog_val = '';
	var select_cat_val = '';
	
	var swiper_home = '';
	var swiper_shop = '';
	var swiper_item = '';
	var swiper2 = '';
	var swiper3 = '';
	var this_page = 0;
	var html_out = '';
	var counsel_cnt = 0;
	var cart_cnt = 0;
	var swiper2 = new Swiper('.swiper-container_front', {
		navigation: {
			nextEl: '.prev',
			prevEl: '.next',
		},
		pagination: {
			el: '.swiper-pagination_front',
			clickable: true,
		},
		initialSlide: return_index("swiper-container_front")
	});

	$(function(){
		$("#loading").css("align-items", "center").css("justify-content", "center");
		$("#loading").html("<span style='color: #fff; max-width: 50%; text-align:center;'><img src='/pet/images/logo_loading0.gif' style='width: 100px; height: 100px;'/><br/><span style='color:#f5bf2e;'>???</span>???????????? ???<span style='color:#f5bf2e;'>???</span></span>");

		if(r_middle != "" || r_lat != ""){
			tab = 2;
			$(document).find(".fixed-menu-wrap2 .tab li").removeClass("on");
			$(document).find(".fixed-menu-wrap2 .tab li[data-id='2']").addClass("on");
		}

		if(tab == "1"){
			$(document).find(".fixed-menu-wrap2 .tab li").removeClass("on");
			$(document).find(".fixed-menu-wrap2 .tab li[data-id='"+tab+"']").addClass("on");
			get_customer()
				.then(main_html)
				.then(hot_pick_shop)
				.then(beauty_comment)
				.then(md_choice_item)
				.then(new_item)
				.then(new_beauty_shop)
				.then(footer_html)
				.then(get_counsel_cnt)
				.then(get_cart_cnt)
				.then(get_item_cart_for_guest)
				.then(get_banner)
				.then(no_app);
		}else if(tab == "2"){
			$(document).find(".fixed-menu-wrap2 .tab li").removeClass("on");
			$(document).find(".fixed-menu-wrap2 .tab li[data-id='"+tab+"']").addClass("on");

			var html = '';
			html += '<a href="<?=$mainpage_directory ?>/search_location.php?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng+'">';
			html += location_txt;
			html += '	<img src="/pet/images/shop_point.png" />';
			html += '</a>';
			$("#fixed-menu .center_menu").html(html);

			if(r_middle != "" || r_lat != ""){
				get_customer()
					.then(shop_html)
					.then(get_shop_list)
					.then(footer_html)
					.then(get_counsel_cnt)
					.then(get_cart_cnt)
					.then(get_item_cart_for_guest)
					.then(get_banner)
					.then(no_app);
			}else{
				get_customer()
					.then(shop_html)
					.then(beauty_comment)
					.then(hot_pick_shop)
					.then(new_beauty_shop)
					.then(footer_html)
					.then(get_counsel_cnt)
					.then(get_cart_cnt)
					.then(get_item_cart_for_guest)
					.then(get_banner)
					.then(no_app);
			}
		}else if(tab == "3"){
			$(document).find(".fixed-menu-wrap2 .tab li").removeClass("on");
			$(document).find(".fixed-menu-wrap2 .tab li[data-id='"+tab+"']").addClass("on");

			var html = '';
			html += '<a href="<?=$mainpage_directory ?>/search_item.php?tab='+tab+'">';
			html += '?????? ??????';
			html += '</a>';
//			html += '<select id="item_select" style="font-size:13px;width:80px;height:30px;border-radius:15px;padding:5px 5px 5px 8px;box-sizing:border-box;border:2px solid #f6bf2e;outline:none;position:absolute;right:10px;top:10px;line-height:30px;background:#fff;">'
//			html += '<option value="?????????" selected>?????????</option>'
//			html += '<option value="?????????">?????????</option>'
//			html += '</select>'
			$("#fixed-menu .center_menu").html(html);

			get_customer()
				.then(item_html)
				.then(md_choice_item)
				.then(best_item)
				.then(new_item)
				.then(footer_html)
				.then(get_counsel_cnt)
				.then(get_cart_cnt)
				.then(get_item_cart_for_guest)
				.then(get_banner)
				.then(no_app);
				//.then(mainshop_banner); ??????
		
		}else{
			get_customer()
				.then(main_html)
				.then(hot_pick_shop)
				.then(beauty_comment)
				.then(md_choice_item)
				.then(new_item)
				.then(new_beauty_shop)
				.then(footer_html)
				.then(get_counsel_cnt)
				.then(get_cart_cnt)
				.then(get_item_cart_for_guest)
				.then(get_banner)
				.then(no_app);

		}
	});

	// ?????? ?????????
	$(window).on("scroll", function(){
		var st = $(this).scrollTop();
		clearTimeout($.data(this, 'scrollTimer'));
		$.data(this, 'scrollTimer', setTimeout(function() {
			if(st > lastScrollTop){
				$("#fixed-menu").stop().animate({"top" : "-50px"}, 100);
			}else{
				$("#fixed-menu").stop().animate({"top" : "0px"}, 100);
			}

			lastScrollTop = st;
		}, 100));
	});

	// ?????? ???
	$(document).on("click", ".tab li", function(){
		tab = $(this).data('id');
		$(document).find(".fixed-menu-wrap2 .tab li").removeClass("on");
		$(this).addClass("on");
		location_txt = "?????? ??????";
		r_top = '';
		r_middle = '';
		r_lat = '';
		r_lng = '';
		history.pushState('', '', '<?=$mainpage_directory ?>/?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lat);

		$("#loading").css("display", "flex");
		if(tab == "1"){ // home
			$("#fixed-menu .center_menu").html('<img src="<?= $image_directory ?>/main_logo.png" />'); // ??? ????????? ?????? ?????????
			main_html()
				.then(hot_pick_shop)
				.then(beauty_comment)
				.then(md_choice_item)
				.then(new_item)
				.then(new_beauty_shop)
				.then(footer_html)
				.then(get_counsel_cnt)
				.then(get_cart_cnt)
				.then(get_banner);
		}else if(tab == "2"){ // shop
			var html = '';
			html += '<a href="<?=$mainpage_directory ?>/search_location.php?top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng+'">';
			html += location_txt;
			html += '	<img src="/pet/images/shop_point.png" />';
			html += '</a>';
			$("#fixed-menu .center_menu").html(html);
			shop_html()
				.then(beauty_comment)
				.then(hot_pick_shop)
				.then(new_beauty_shop)
				.then(footer_html)
				.then(get_counsel_cnt)
				.then(get_cart_cnt)
				.then(get_banner);
		}else if(tab == "3"){ // item
			var html = '';
			
			$("#fixed-menu .right_menu").html(html);
			var html = '';
			select_dog_val = 'selected';
			html += '<a href="<?=$mainpage_directory ?>/search_item.php?tab='+tab+'">';
			html += '?????? ??????';
			html += '</a>';
//			html += '<select id="item_select" style="font-size:13px;width:80px;height:30px;border-radius:15px;padding:5px 5px 5px 8px;box-sizing:border-box;border:2px solid #f6bf2e;outline:none;position:absolute;right:10px;top:10px;line-height:30px;background:#fff;">';
//			html += '<option value="?????????" '+select_dog_val+'>?????????</option>';
//			html += '<option value="?????????" '+select_cat_val+'>?????????</option>';
//			html += '</select>';

			$("#fixed-menu .center_menu").html(html);

			$("#item_select").on('change', function(){
				console.log(this.value);
				selected_sort = this.value; // ????????? ???
				console.log("????????? "+selected_sort);

				$("#loading").css("display", "flex");
				
				if(selected_sort == "?????????"){
					select_dog_val = 'selected';
					select_cat_val = '';
					
					item_html()
					.then(md_choice_item)
					.then(best_item)
					.then(new_item)
					.then(footer_html)
					.then(get_counsel_cnt)
					.then(get_cart_cnt)
					.then(get_banner);

				}else if(selected_sort == "?????????"){
					select_cat_val = 'selected';
					select_dog_val = '';

					item_html_test()
					.then(md_choice_item)
					.then(best_item)
					.then(new_item)
					.then(footer_html)
					.then(get_counsel_cnt)
					.then(get_cart_cnt)
					.then(get_banner);
				}

			});

//			$("#fixed-menu .center_menu").html('<img src="<?= $image_directory ?>/main_logo.png" />'); // ??? ????????? ?????? ?????????
			item_html()
				.then(md_choice_item)
				.then(best_item)
				.then(new_item)
				.then(footer_html)
				.then(get_counsel_cnt)
				.then(get_cart_cnt)
				.then(get_banner);
				//.then(mainshop_banner); ??????
		}
	});


	// ?????? ????????? ??????
	$(document).on("click", "#no_app .no_download", function(){
		$("#no_app").removeClass("on");
		if(tmp_val == "in_app_ios"){
			setCookie_popup_ios("no_app", 1, 1);
		}else{
			setCookie_popup("no_app", 1, 1);
		}
	});
	
	// ????????????
	$mainContent.on("click", ".search_location_btn", function(){
		location.href = "<?=$mainpage_directory ?>/search_location.php?top="+r_top+"&middle="+r_middle+"&lat="+r_lat+"&lng="+r_lng;
	});

	// ???????????? ??????
	$mainContent.on("click", "#bj_item_cart", function(){
		location.href = "<?=$item_directory ?>/item_cart.php?backurl="+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng);
	});

	// ?????? ?????? ??????
	$mainContent.on("click", "#bj_hamburger", function(){
		var html = '';
		if($(this).hasClass("on")){
			$(this).removeClass("on");
			$(this).children(".fa-times").remove();
			$(this).html('<img src="<?=$image_directory ?>/floating_myshop.png" /><span class="counsel_cnt">'+counsel_cnt+'</span>');
			$(".bj_backBlock").remove();
			$(this).children(".counsel_cnt").css("opacity", "1");
			$(this).stop().animate({right: "10", bottom: "10"}, 10, function(){
				$(".bj_sub_menu").stop().animate({
						width: '0',
						bottom: '-200'
					}, 200, function(){
					$(".bj_sub_menu").hide(10);
					$(".bj_sub_menu").remove();
				});
			});
		}else{
			html += '<div class="bj_sub_menu">';
			html += '	<ul>';
			if(is_artist == 1){
				html += '		<li>';
				html += '			<img src="<?=$image_directory ?>/floating_myshop_1.png" />';
				html += '			<button type="button" class="go_shop">MY SHOP ??????</button>';
				html += '		</li>';
			}else{
				// ???????????? ??????
			}
			html += '		<li>';
			html += '			<img src="<?=$image_directory ?>/floating_myshop_3.png" />';
			html += '			<button type="button" class="go_manage_sell_info">????????????</button>';
			html += '		</li>';
			html += '		<li>';
			html += '			<img src="<?=$image_directory ?>/floating_myshop_4.png" />';
			html += '			<button type="button" class="go_manage_customer_list">????????????</button>';
			html += '		</li>';
			html += '		<li>';
			html += '			<img src="<?=$image_directory ?>/floating_myshop_2.png" />';
			html += '			<button type="button" class="go_manage_counseling_request">???????????? : <span class="counsel_cnt">'+counsel_cnt+'</span></button>';
			html += '		</li>';
			html += '	</ul>';
			html += '</div>';
			$(this).after(html);
			$(this).addClass("on");
			$(this).children(".fa-bars").remove();
			$(this).html('<i class="fas fa-times"></i>');
			$(this).after('<div class="bj_backBlock"></div>');
			$(this).children(".counsel_cnt").css("opacity", "0");
			$(this).stop().animate({right: "20", bottom: "20"}, 10, function(){
				$(".bj_sub_menu").show(10, function(){
					$(".bj_sub_menu").stop().animate({
						width: '180',
						bottom: '10'
					}, 100);
				});			
			});
		}
	});

	// ?????? ?????? ?????? - ?????? ??????
	$mainContent.on("click", ".bj_backBlock", function(){
		$("#bj_hamburger").removeClass("on");
		$("#bj_hamburger").children(".fa-times").remove();
		$("#bj_hamburger").append('<img src="<?=$image_directory ?>/floating_myshop.png" /><span class="counsel_cnt">0</span>');
		$(".bj_backBlock").remove();
		$("#bj_hamburger").children(".counsel_cnt").css("opacity", "1");
		$("#bj_hamburger").stop().animate({right: "10", bottom: "10"}, 10, function(){
			$(".bj_sub_menu").stop().animate({
					width: '0',
					bottom: '-200'
				}, 200, function(){
				$(".bj_sub_menu").hide(10);
			});
		});
	});

	// ?????? ?????? ?????? - ??????
	$mainContent.on("click", ".bj_sub_menu button", function(){
		if($(this).hasClass("go_shop")){
			location.href = "<?= $shop_directory ?>/index.php?from=main";
		}else if($(this).hasClass("go_manage_sell_info")){
			location.href = "<?= $shop_directory ?>/manage_sell_info.php?ch=month&from=main";
		}else if($(this).hasClass("go_manage_customer_list")){
			location.href = "<?= $shop_directory ?>/manage_customer_list.php?from=main";
		}else if($(this).hasClass("go_manage_counseling_request")){
			location.href = "<?= $shop_directory ?>/manage_counseling_request.php?from=main";
		}
	});

	$mainContent.on("click", ".item_cate_btn", function(){
		var _this = $(this);
		$mainContent.find('.item_cate_btn').removeClass('on');
		$mainContent.find('.item_menu_list').removeClass('on');
		_this.addClass('on');
		$mainContent.find('.item_menu_list[data-id="'+_this.data('id')+'"]').addClass('on');
	});

	function mainshop_banner(){
		if (getCookie_popup('mainshop_banner') != 'Y') {
			$("#popup_wrap").dialog({
				modal: true,
				title: "",
				autoOpen: true,
				//maxWidth: "96%",
				//minHeight: Number($(window).height()) - 40,
				//width: 'auto',
				//height: 'auto',
				autoSize: false,
				resize: 'auto',
				resizable: false,
				draggable: false,
				buttons: {
					'??????': function() {
						//setCookie_popup('mainshop_banner', 'Y', 100);
						$(this).dialog("close");
					},
					"7?????? ????????????!": function() {
						if(checkMobile2() == "in_app_ios"){
							setCookie_popup_ios('mainshop_banner', 'Y', 7);
						}else{
							setCookie_popup('mainshop_banner', 'Y', 7);
						}
						$(this).dialog("close");
					}
				},
				open: function(event, ui) {
					swiper2.update();
					$(event.target).parent().css('position', 'fixed'); // dialog fixed
					$(event.target).parent().css('top', '50%'); // dialog fixed
					$(event.target).parent().css('left', '50%'); // dialog fixed
					$(event.target).parent().css('transform', 'translate(-50%, -50%)'); // dialog fixed
					//$('.ui-dialog').position({ my: "center", at: "center", of: window });
				},
				close: function() {
				}
			});
		}
	}

	function get_customer(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$mainpage_directory ?>/index_ajax.php',
				data: {
					mode : "get_customer",
					customer_id : customer_id
				},
				type: 'POST',
				dataType: 'JSON',
				beforeSend: function(){
					$("#loading").css("display", "flex");
				},
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						is_artist = parseInt(data.data.is_artist);
						is_android = parseInt(data.data.is_android);

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

	function main_html(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$admin_directory ?>/main_banner_ajax.php',
				data: {
					mode : "get_main_banner",
					tab : tab,
					order : "1"
				},
				type: 'POST',
				dataType: 'JSON',
				beforeSend: function(){
					//$("#loading").css("display", "flex");
				},
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						var html = '';
						var cnt = 0;

						if(data.data.list && data.data.list.length > 0){
							html += '<div class="top_line" style="">???&nbsp;<span>???????????? ???</span>&nbsp;???</div>';
							html += '<!-- Swiper -->';
							html += '<div class="swiper-container swiper-container-home">';
							html += '	<div class="swiper-wrapper">';
							$.each(data.data.list, function(i, v){
								var target = (v.target && v.target != "")? v.target : "";
								var link = (v.link && v.link != "")? v.link : "";
								var backurl = 'backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng);
								link = (link != "")? ((link.indexOf('?') != -1)? link+'&'+backurl : link+'?'+backurl) : "";
								link = (link != "")? '<a href="'+link+'" target="'+target+'"><div class="item_image"></div></a>' : '<a href="javascript:;"><div class="item_image"></div></a>';
								if(v.is_use == "1"){
									if(v.is_use_time == "1"){
										if(new Date().getTime() < new Date(v.end_dt).getTime() && new Date().getTime() > new Date(v.start_dt).getTime()){
											html += '		<div class="swiper-slide" data-id="'+v.mb_seq+'">';
											html += '			<div>'+link+'</div>';
											html += '		</div>';
										}else{
											// ????????? OR ????????? ???????????? ?????????
											cnt++;
										}
									}else{
										html += '		<div class="swiper-slide" data-id="'+v.mb_seq+'">';
										html += '			<div>'+link+'</div>';
										html += '		</div>';
									}
								}
							});
							html += '	</div>';
							html += '	<!-- Add Pagination -->';
							html += '	<div class="swiper-pagination"></div>';
							html += '</div>';
							if(cnt != data.data.list.length){ // ???????????? ?????? ????????? ????????? ???????????? ???????????????
								$mainContent.html(html);
							}else{
								$mainContent.html('');
							}

							$.each(data.data.list, function(i, v){
								get_file_list('.swiper-container-home .swiper-slide', v.mb_seq, v.banner, '');
							});

							swiper_home = new Swiper('.swiper-container-home', {
								loop: true,
								autoplay: {
									delay: 4500,
									disableOnInteraction: false,
								},
								pagination: {
									el: '.swiper-pagination',
									type: 'fraction',
								},
							});
						}else{
							// ???????????? ??????
							$mainContent.html('');
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

	function shop_html(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$admin_directory ?>/main_banner_ajax.php',
				data: {
					mode : "get_main_banner",
					tab : tab,
					order : "1"
				},
				type: 'POST',
				dataType: 'JSON',
				beforeSend: function(){
					//$("#loading").css("display", "flex");
				},
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						var html = '';
						var cnt = 0;

						if(data.data.list && data.data.list.length > 0){
							html += '<div class="top_line" style="">???&nbsp;<span>???????????? ???</span>&nbsp;???</div>';
							html += '<!-- Swiper -->';
							html += '<div class="swiper-container swiper-container-shop">';
							html += '	<div class="swiper-wrapper">';
							$.each(data.data.list, function(i, v){
								var target = (v.target && v.target != "")? v.target : "";
								var link = (v.link && v.link != "")? v.link : "";
								var backurl = 'backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng);
								link = (link != "")? ((link.indexOf('?') != -1)? link+'&'+backurl : link+'?'+backurl) : "";
								link = (link != "")? '<a href="'+link+'" target="'+target+'"><div class="item_image"></div></a>' : '<a href="javascript:;"><div class="item_image"></div></a>';
								if(v.is_use == "1"){
									if(v.is_use_time == "1"){
										if(new Date().getTime() < new Date(v.end_dt).getTime() && new Date().getTime() > new Date(v.start_dt).getTime()){
											html += '		<div class="swiper-slide" data-id="'+v.mb_seq+'">';
											html += '			<div>'+link+'</div>';
											html += '		</div>';
										}else{
											// ????????? OR ????????? ???????????? ?????????
											cnt++;
										}
									}else{
										html += '		<div class="swiper-slide" data-id="'+v.mb_seq+'">';
										html += '			<div>'+link+'</div>';
										html += '		</div>';
									}
								}
							});
							html += '	</div>';
							html += '	<!-- Add Pagination -->';
							html += '	<div class="swiper-pagination"></div>';
							html += '</div>';
							if(cnt != data.data.list.length){ // ???????????? ?????? ????????? ????????? ???????????? ???????????????
								$mainContent.html(html);
							}else{
								$mainContent.html('');
							}

							$.each(data.data.list, function(i, v){
								get_file_list('.swiper-container-shop .swiper-slide', v.mb_seq, v.banner, '');
							});

							swiper_home = new Swiper('.swiper-container-shop', {
								loop: true,
								autoplay: {
									delay: 4500,
									disableOnInteraction: false,
								},
								pagination: {
									el: '.swiper-pagination',
									type: 'fraction',
								},
							});
						}else{
							// ???????????? ??????
							$mainContent.html('');
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

	
	
	function item_html(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$admin_directory ?>/main_banner_ajax.php',
				data: {
					mode : "get_main_banner",
					tab : tab,
					order : "1"
				},
				type: 'POST',
				dataType: 'JSON',
				beforeSend: function(){
					//$("#loading").css("display", "flex");
				},
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						var html = '';
						var cnt = 0;
						if(data.data.list && data.data.list.length > 0){
							html += '<div class="top_line" style="">???&nbsp;<span>???????????? ???</span>&nbsp;???</div>';
							html += '<!-- Swiper -->';
							html += '<div class="swiper-container swiper-container-item">';
							html += '	<div class="swiper-wrapper">';
							$.each(data.data.list, function(i, v){
								var target = (v.target && v.target != "")? v.target : "";
								var link = (v.link && v.link != "")? v.link : "";
								var backurl = 'backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng);
								link = (link != "")? ((link.indexOf('?') != -1)? link+'&'+backurl : link+'?'+backurl) : "";
								link = (link != "")? '<a href="'+link+'" target="'+target+'"><div class="item_image"></div></a>' : '<a href="javascript:;"><div class="item_image"></div></a>';
								if(v.is_use == "1"){
									if(v.is_use_time == "1"){
										if(new Date().getTime() < new Date(v.end_dt).getTime() && new Date().getTime() > new Date(v.start_dt).getTime()){
											html += '		<div class="swiper-slide" data-id="'+v.mb_seq+'">';
											html += '			<div>'+link+'</div>';
											html += '		</div>';
										}else{
											// ????????? OR ????????? ???????????? ?????????
											cnt++;
										}
									}else{
										html += '		<div class="swiper-slide" data-id="'+v.mb_seq+'">';
										html += '			<div>'+link+'</div>';
										html += '		</div>';
									}
								}
							});
							html += '	</div>';
							html += '	<!-- Add Pagination -->';
							html += '	<div class="swiper-pagination"></div>';
							html += '</div>';
							if(cnt != data.data.list.length){ // ???????????? ?????? ????????? ????????? ???????????? ???????????????
								$mainContent.html(html);
							}else{
								$mainContent.html('');
							}

							$.each(data.data.list, function(i, v){
								get_file_list('.swiper-container-item .swiper-slide', v.mb_seq, v.banner, '');
							});

							swiper_home = new Swiper('.swiper-container-item', {
								loop: true,
								autoplay: {
									delay: 4500,
									disableOnInteraction: false,
								},
								pagination: {
									el: '.swiper-pagination',
									type: 'fraction',
								},
							});
						}else{
							// ???????????? ??????
							$mainContent.html('');
						}
						html = '';
						html += '<div class="item_menu_tab">';
						html += '	<ul class="table">';
						html += '		<li class="item_cate_btn" data-id="2"><div>??????</div></li>';
						html += '		<li class="item_cate_btn" data-id="12"><div>??????</div></li>';
						html += '		<li class="item_cate_btn" data-id="27"><div>??????</div></li>';
						html += '	</ul>';
						html += '</div>';
						html += '<div class="item_menu_list" data-id="2">';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=3&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_2_3.png"><h5 class="menu_name">??????(1?????????)</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=4&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_2_4.png"><h5 class="menu_name">?????????(1-6???)</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=5&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_2_5.png"><h5 class="menu_name">?????????(7?????????)</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=6&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_2_6.png"><h5 class="menu_name">?????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=10&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_2_10.png"><h5 class="menu_name">????????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=11&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_2_11.png"><h5 class="menu_name">???????????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/item_category_list.php?category=2&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_more2.png"><h5 class="menu_name">+ ?????????</h5></a></div></div>';
						html += '	</div>';
						html += '</div>';

						html += '<div class="item_menu_list" data-id="12">';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=13&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_12_13.png"><h5 class="menu_name">????????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=15&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_12_15.png"><h5 class="menu_name">?????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=19&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_12_19.png"><h5 class="menu_name">??????/??????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=20&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_12_20.png"><h5 class="menu_name">?????????/??????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=21&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_12_21.png"><h5 class="menu_name">???/?????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=23&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_12_23.png"><h5 class="menu_name">?????????/??????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=26&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_12_26.png"><h5 class="menu_name">?????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/item_category_list.php?category=12&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_more2.png"><h5 class="menu_name">+ ?????????</h5></a></div></div>';
						html += '	</div>';
						html += '</div>';

						html += '<div class="item_menu_list" data-id="27">';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=32&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_2.png"><h5 class="menu_name">????????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=29&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_3.png"><h5 class="menu_name">????????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=28&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_8.png"><h5 class="menu_name">????????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=36&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_9.png"><h5 class="menu_name">????????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=37&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_22.png"><h5 class="menu_name">?????????/??????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=42&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_12.png"><h5 class="menu_name">?????????/?????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=33&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_6.png"><h5 class="menu_name">?????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/item_category_list.php?category=27&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_more2.png"><h5 class="menu_name">+ ?????????</h5></a></div></div>';
						html += '	</div>';
						html += '</div>';
						$mainContent.append(html);
						$mainContent.find('.item_menu_tab li[data-id="27"]').addClass('on');
						$mainContent.find('.item_menu_list[data-id="27"]').addClass('on');

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

	function item_html_test(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$admin_directory ?>/main_banner_ajax.php',
				data: {
					mode : "get_main_banner",
					tab : tab,
					order : "1"
				},
				type: 'POST',
				dataType: 'JSON',
				beforeSend: function(){
					//$("#loading").css("display", "flex");
				},
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						var html = '';
						var cnt = 0;
						if(data.data.list && data.data.list.length > 0){
							html += '<div class="top_line" style="">???&nbsp;<span>???????????? ???</span>&nbsp;???</div>';
							html += '<!-- Swiper -->';
							html += '<div class="swiper-container swiper-container-item">';
							html += '	<div class="swiper-wrapper">';
							$.each(data.data.list, function(i, v){
								var target = (v.target && v.target != "")? v.target : "";
								var link = (v.link && v.link != "")? v.link : "";
								var backurl = 'backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng);
								link = (link != "")? ((link.indexOf('?') != -1)? link+'&'+backurl : link+'?'+backurl) : "";
								link = (link != "")? '<a href="'+link+'" target="'+target+'"><div class="item_image"></div></a>' : '<a href="javascript:;"><div class="item_image"></div></a>';
								if(v.is_use == "1"){
									if(v.is_use_time == "1"){
										if(new Date().getTime() < new Date(v.end_dt).getTime() && new Date().getTime() > new Date(v.start_dt).getTime()){
											html += '		<div class="swiper-slide" data-id="'+v.mb_seq+'">';
											html += '			<div>'+link+'</div>';
											html += '		</div>';
										}else{
											// ????????? OR ????????? ???????????? ?????????
											cnt++;
										}
									}else{
										html += '		<div class="swiper-slide" data-id="'+v.mb_seq+'">';
										html += '			<div>'+link+'</div>';
										html += '		</div>';
									}
								}
							});
							html += '	</div>';
							html += '	<!-- Add Pagination -->';
							html += '	<div class="swiper-pagination"></div>';
							html += '</div>';
							if(cnt != data.data.list.length){ // ???????????? ?????? ????????? ????????? ???????????? ???????????????
								$mainContent.html(html);
							}else{
								$mainContent.html('');
							}

							$.each(data.data.list, function(i, v){
								get_file_list('.swiper-container-item .swiper-slide', v.mb_seq, v.banner, '');
							});

							swiper_home = new Swiper('.swiper-container-item', {
								loop: true,
								autoplay: {
									delay: 4500,
									disableOnInteraction: false,
								},
								pagination: {
									el: '.swiper-pagination',
									type: 'fraction',
								},
							});
						}else{
							// ???????????? ??????
							$mainContent.html('');
						}
						html = '';
						html += '<div class="item_menu_tab">';
						html += '	<ul class="table">';
						html += '		<li class="item_cate_btn" data-id="52"><div>??????</div></li>';
						html += '		<li class="item_cate_btn" data-id="61"><div>??????</div></li>';
						html += '		<li class="item_cate_btn" data-id="74"><div>??????</div></li>';
						html += '	</ul>';
						html += '</div>';
						html += '<div class="item_menu_list" data-id="52">';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=53&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_2_3.png"><h5 class="menu_name">??????(1?????????)</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=54&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_2_4.png"><h5 class="menu_name">?????????(1-6???)</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=55&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_2_5.png"><h5 class="menu_name">?????????(7?????????)</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=56&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_2_6.png"><h5 class="menu_name">?????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=58&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_2_10.png"><h5 class="menu_name">????????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=59&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_2_11.png"><h5 class="menu_name">?????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=60&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_2_11.png"><h5 class="menu_name">?????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/item_category_list.php?category=52&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_more2.png"><h5 class="menu_name">+ ?????????</h5></a></div></div>';
						html += '	</div>';
						html += '</div>';

						html += '<div class="item_menu_list" data-id="61">';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=62&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_12_13.png"><h5 class="menu_name">?????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=63&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_12_15.png"><h5 class="menu_name">???????????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=67&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_12_19.png"><h5 class="menu_name">??????/??????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=66&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_12_20.png"><h5 class="menu_name">??????/????????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=68&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_12_21.png"><h5 class="menu_name">??????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=64&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_12_23.png"><h5 class="menu_name">??????/??????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=73&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_1_12_26.png"><h5 class="menu_name">?????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/item_category_list.php?category=61&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_more2.png"><h5 class="menu_name">+ ?????????</h5></a></div></div>';
						html += '	</div>';
						html += '</div>';

						html += '<div class="item_menu_list" data-id="74">';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=75&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_2.png"><h5 class="menu_name">???????????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=76&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_3.png"><h5 class="menu_name">???????????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=77&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_8.png"><h5 class="menu_name">?????????/?????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=80&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_9.png"><h5 class="menu_name">????????????/??????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=92&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_22.png"><h5 class="menu_name">?????????/??????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=84&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_12.png"><h5 class="menu_name">????????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/?category=82&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_6.png"><h5 class="menu_name">????????????</h5></a></div></div>';
						html += '	</div>';
						html += '	<div class="item_menu">';
						html += '		<div class="flex-item"><div class="inner"><a href="../item/item_category_list.php?category=74&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'"><img src="../images/item_menu_icon_more2.png"><h5 class="menu_name">+ ?????????</h5></a></div></div>';
						html += '	</div>';
						html += '</div>';
						$mainContent.append(html);
						$mainContent.find('.item_menu_tab li[data-id="74"]').addClass('on');
						$mainContent.find('.item_menu_list[data-id="74"]').addClass('on');

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

	function hot_pick_shop(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$mainpage_directory ?>/index_ajax.php',
				data: {
					mode: "get_shop_recommend_list",
					limit: 10
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log("hotpickshop");
						console.log(data.data);
						console.log(Object.keys(data.data).length);
						var html = '';

						if(tab == "1"){
							if(data.data && Object.keys(data.data).length > 0){
								html += '<div class="hot_pick_shop">';
								html += '	<div class="title"><span>?????? ???????????????!</span></div>';
								html += '	<div class="swiper-container2">';
								html += '		<div class="swiper-wrapper">';
								$.each(data.data, function(i, v){
									var tag_list = v.total_service.split(',');
									html += '			<div class="swiper-slide">';
									html += '				<a href="<?=$artist_directory?>/?type=beauty&artist_name='+encodeURIComponent(v.shop.name)+'&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'">';
									html += '					<div style="height: 320px;">';
									html += '						<div class="shop_image" style="background-image: url(\''+v.shop.front_image+'\');"></div>';
									html += '						<ul class="shop">';
									html += '							<li class="shop_name">'+v.shop.name+' <span class="choice_mark">??????</span></li>';
									html += '							<li class="shop_tag">';
									for(var _i = 0; _i < tag_list.length; _i++){
										var tag_class = '';
										if(tag_list[_i] == "?????????" || tag_list[_i] == "?????????" || tag_list[_i] == "?????????" || tag_list[_i] == "?????????"){
											tag_class = "dog";
										}else if(tag_list[_i] == "?????????"){
											tag_class = "cat";
										}else if(tag_list[_i] == "??????" || tag_list[_i] == "??????"){
											tag_class = "place";
										}else if(tag_list[_i] == "??????"){
											tag_class = "hotel";
										}else if(tag_list[_i] == "?????????"){
											tag_class = "play";
										}
										html += '<span class="tag '+tag_class+'">'+tag_list[_i]+'</span>';
									}
									html += '							</li>';
									html += '							<li class="shop_location"><span><i class="fas fa-map-marker-alt"></i></span> '+v.shop_address+'</li>';
									html += '						</ul>';
									html += '					</div>';
									html += '				</a>';
									html += '			</div>';
								});
								html += '		</div>';
								html += '		<!-- Add Pagination -->';
								html += '		<div class="swiper-pagination"></div>';
								html += '	</div>';
								html += '</div>';
								$mainContent.append(html);
								swiper2 = new Swiper('.swiper-container2', {
									slidesPerView: 'auto',
									spaceBetween: 30,
									loop: true,
									autoHeight: true,
								});
							}else{
								// list_none
								html += '<div class="hot_pick_shop">';
								html += '	<div class="title"><span>?????? ???????????????!</span></div>';
								html += '	<div class="no_data">';
								html += '		??????????????? ??????, ??????';
								html += '	</div>';
								html += '</div>';
								$mainContent.append(html);
							}
						}else if(tab == "2"){
							if(data.data && Object.keys(data.data).length > 0){
								html += '<div class="hot_pick_shop">';
								html += '	<div class="title"><span>?????? ???????????????!</span></div>';
								html += '	<ul class="hot_pick_shop_wrap">';
								$.each(data.data, function(i, v){
									var tag_list = v.total_service.split(',');
									html += '		<li>';
									html += '			<a href="<?=$artist_directory?>/?type=beauty&artist_name='+encodeURIComponent(v.shop.name)+'&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'">';
									html += '				<div class="shop_image" style="background-image: url(\''+v.shop.front_image+'\');"></div>';
									html += '				<ul class="shop">';
									html += '					<li class="shop_name">'+v.shop.name+' <span class="choice_mark">??????</span></li>';
									html += '					<li class="shop_tag">';
									for(var _i = 0; _i < tag_list.length; _i++){
										var tag_class = '';
										if(tag_list[_i] == "?????????" || tag_list[_i] == "?????????" || tag_list[_i] == "?????????" || tag_list[_i] == "?????????"){
											tag_class = "dog";
										}else if(tag_list[_i] == "?????????"){
											tag_class = "cat";
										}else if(tag_list[_i] == "??????" || tag_list[_i] == "??????"){
											tag_class = "place";
										}else if(tag_list[_i] == "??????"){
											tag_class = "hotel";
										}else if(tag_list[_i] == "?????????"){
											tag_class = "play";
										}
										html += '<span class="tag '+tag_class+'">'+tag_list[_i]+'</span>';
									}
									html += '					</li>';
									html += '					<li class="shop_location"><span><i class="fas fa-map-marker-alt"></i></span> '+v.shop_address+'</li>';
									html += '				</ul>';
									html += '			</a>';
									html += '		</li>';
								});
								html += '	</ul>';
								html += '</div>';
								$mainContent.append(html);								
							}else{
								// list_none
								html += '<div class="hot_pick_shop">';
								html += '	<div class="title"><span>?????? ???????????????!</span></div>';
								html += '	<div class="no_data">';
								html += '		??????????????? ??????, ??????';
								html += '	</div>';
								html += '</div>';
								$mainContent.append(html);
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

	function beauty_comment(){
		return new Promise(function(resolve, reject) {
			var html = '';
			var review_cnt = (tab == "1")? 3 : 10;

			$.ajax({
				url: '../admin/review_best_ajax.php',
				data: {
					mode : "get_review_best_list2",
					flag : 0,
					page_cnt : review_cnt,
					best_list : '1',
					review_seq : ''
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);

						html += '<div class="beauty_comment">';
						html += '	<div class="title"><span>Best ?????? ??????</span></div>';
						if(tab == "1"){
							$.each(data.data, function(i, v){
								var review_image = (v.review_images != "")? v.review_images : "";
								review_image = (review_image.indexOf('|') !== -1)? review_image.split('|')[0] : review_image;
								review_image_list = (review_image.indexOf('|') !== -1)? review_image.split('|') : [review_image];
								var review = (v.review != "")? v.review : "";
								review = (review.length > 40)? review.substr(0, 40)+'...' : review;
								var rating = (v.rating != "")? v.rating : "5";
								var use_cnt = (v.use_cnt != "")? v.use_cnt : "1";

								html += '	<a href="<?=$artist_directory?>/?type=beauty&sequence=1&rev='+v.review_seq+'&artist_name='+encodeURIComponent(v.artist_name)+'&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'">';
								html += '		<div class="best_comment" style="background-image: url(\''+review_image+'\');">';
								html += '			<ul>';
								html += '				<li class="shop_name">'+v.artist_name+'</li>';
								html += '				<li class="customer_comment"><div>"'+review+'"</div></li>';
								html += '				<li class="customer_rating"><span class="star">';
								for(var _i = 1; _i <= 5; _i++){
									html += '<i class="fas fa-star"></i>';
									if(_i >= rating){
										html += '</span>';
									}
								}
								html += '				'+rating+'</li>';
								html += '			</ul>';
								html += '			<div class="customer_use_cnt" data-id="'+i+'">0??? ??????</div>';
								html += '		</div>';
								html += '	</a>';
							});
						}else if(tab == "2"){
							html += '<!-- Swiper -->';
							html += '<div class="swiper-container swiper-container3">';
							html += '	<div class="swiper-wrapper">';

							$.each(data.data, function(i, v){
								var review_image = (v.review_images != "")? v.review_images : "";
								review_image_list = (review_image.indexOf('|') !== -1)? review_image.split('|') : [review_image];
								console.log(review_image_list);
								var review = (v.review != "")? v.review : "";
								review = (review.length > 40)? review.substr(0, 40)+'...' : review;
								var rating = (v.rating != "")? v.rating : "5";
								var use_cnt = (v.use_cnt != "")? v.use_cnt : "1";
								var regType1 = /[???-???|???-???|???-???]/;
								var customer_name = (v.customer_name != "")? v.customer_name : "";
								customer_name = (regType1.test(customer_name) == true)? v.customer_id.substr(0, 2)+'***' : customer_name.substr(0, (v.customer_name.length - 4))+'***';

								html += '		<div class="swiper-slide">';
								html += '			<div class="cs_line_box">';
								html += '				<a href="<?=$artist_directory?>/?type=beauty&sequence=1&rev='+v.review_seq+'&artist_name='+encodeURIComponent(v.artist_name)+'&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'">';
								html += '					<div class="thumbnail_list">';
								$.each(review_image_list, function(i2, v2){
									var image_length = (review_image_list.length > 3)? 3 : review_image_list.length; // ?????? ?????? ?????? 3???
									if(i2 < 3){
										html += '			<div class="thumbnail_'+image_length+'"><div class="thumbnail" style="background-image: url(\''+v2+'\');background-repeat:no-repeat; "></div></div>';
									}
								});
								html += '					</div>';
								html += '					<div class="review_info"><span class="cs_nanum">'+customer_name+'</span><span style="color:#666;">'+v.artist_name+'</span></div>';
								html += '					<div class="star_point"><span class="star">';
								for(var _i = 1; _i <= 5; _i++){
									html += '<i class="fas fa-star"></i>';
									if(_i >= rating){
										html += '</span>';
									}
								}
								html += '					'+rating+'</div>';
								
								html += '					<div class="review_txt" style="">&quot;'+review+'&quot;</div>';
								html += '					<div class="review_use_cnt" data-id="'+i+'" style=""><span><font style="color:#333; padding: 0px 2px;">0</font>??? ??????</span></div>';
								html += '				</a>';
								html += '			</div>';
								html += '		</div>';
							});

							html += '	</div>';
							html += '</div>';
						}

						html += '</div>';

						$mainContent.append(html);

						$.each(data.data, function(i, v){
							get_use_cnt(v.customer_id, v.artist_id, i);
						});
						
						if(tab == "2"){
							swiper3 = new Swiper('.swiper-container3', {
								slidesPerView: 'auto',
								spaceBetween: 20,
								loop: true,
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
		});
	}

	function get_use_cnt(customer_id, artist_id, list_no){ // ?????? ?????? ?????? ?????? ??????
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '../admin/review_best_ajax.php',
				data: {
					mode : "get_payment_log_cnt",
					customer_id : customer_id,
					artist_id : artist_id
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						//console.log(data.data);
						var use_cnt = data.data;
						var html = '';
						
						if(tab == "1"){
							html += use_cnt+'??? ??????';
							$mainContent.find(".beauty_comment .customer_use_cnt[data-id='"+list_no+"']").html(html);
						}else if(tab == "2"){
							html += '<span><font style="color:#333; padding: 0px 2px;">'+use_cnt+'</font>??? ??????</span>';
							$mainContent.find(".beauty_comment .review_use_cnt[data-id='"+list_no+"']").html(html);
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

	function md_choice_item(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_item_list",
					is_view_main_1: (tab == "1")? "1" : "0",
					is_view_main_3: (tab == "3")? "1" : "0",
					is_view: "1", // ????????????
					orderby: (tab == "1")? "mdmain" : "shopmain",
					flag: 0,
					cnt: 10
				},
				type: 'POST',
				dataType: 'JSON',
				beforeSend: function(){
					//$("#loading").css("display", "flex");
				},
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						console.log(data.sql);
						var html = '';

						if(data.data.list && data.data.list.length > 0){
							html += '<div class="md_choice_item">';
							html += '	<div class="title"><span>MD ?????? ?????????</span></div>';
							html += '	<div class="md_choice_item_wrap">';
							html += '		<ul>';
							$.each(data.data.list, function(i, v){
								var percent = (parseInt(v.product_price) == 0 || parseInt(v.sale_price) == 0)? 0 : 100 - (parseInt(v.sale_price) / parseInt(v.product_price) * 100);
								html += '			<li data-id="'+v.il_seq+'">';
								html += '				<a href="<?=$mainpage_directory ?>/item_product_page.php?no='+v.product_no+'&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'">';
								if(v.is_soldout == "2"){
									html += '					<div class="item_image soldout"></div>';
									html += '					<ul class="item">';
									html += '						<li class="item_name">'+v.product_name+'</li>';
									html += '						<li class="item_price"><span class="soldout">????????????</span></li>';
									html += '					</ul>';
								}else{
									html += '					<div class="item_image"></div>';
									html += '					<ul class="item">';
									html += '						<li class="item_name">'+v.product_name+'</li>';
									html += '						<li class="item_price"><span class="percent">'+Math.round(percent)+'%</span> <span class="sale_price">'+v.sale_price.format()+'???</span></li>';
									html += '						<li class="item_rating"><span class="no_star"><i class="fas fa-star"></i></span> 0 (0)</li>';
									html += '					</ul>';
								}
								if(v.md_icon && v.md_name && v.md_comment && v.md_icon != "" && v.md_name != "" && v.md_comment != ""){
									html += '					<div class="md">';
									html += '						<div class="md_box">';
									html += '							<div class="md_icon" style="background-image: url(\''+v.md_icon+'\');"></div>';
									html += '							<div class="md_name">MD '+v.md_name+'</div>';
									html += '						</div>';
									html += '						<div class="md_comment">"'+v.md_comment+'"</div>';
									html += '					</div>';
								}
								html += '				</a>';
								html += '			</li>';
							});
							html += '		</ul>';
							html += '	</div>';
							html += '</div>';

							$mainContent.append(html);
							
							//loading image n option n rating
							$.each(data.data.list, function(i, v){
								var target = ".md_choice_item ul li";

								get_file_list(target, v.il_seq, v.product_img, v.goodsRepImage);
								get_item_review(target, v.il_seq, v.product_no);
								if(v.is_use_option == "1" && v.sale_price == "0"){ // ???????????? + ?????? 0???
									get_item_option(target, v.il_seq);
								}
							});
						}else{
							html += '<div class="md_choice_item">';
							html += '	<div class="title"><span>MD ?????? ?????????</span></div>';
							html += '	<div class="md_choice_item_wrap">';
							html += '		<div class="no_data">';
							html += '			??????????????? ??????, ??????';
							html += '		</div>';
							html += '	</div>';
							html += '</div>';

							$mainContent.append(html);
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

	function best_item(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_item_list",
					is_view_main_5: "1",
					is_view: "1", // ????????????
					flag: 0,
					cnt: 30
				},
				type: 'POST',
				dataType: 'JSON',
				beforeSend: function(){
					//$("#loading").css("display", "flex");
				},
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);

						var html = '';

						if(data.data.list && data.data.list.length > 0){
							html += '<div class="best_item">';
							html += '	<div class="title"><span>Best ?????????</span></div>';
							html += '	<div class="best_item_wrap">';
							html += '		<div>';
							html += '			<ul>';
							$.each(data.data.list, function(i, v){
								var percent = (parseInt(v.product_price) == 0 || parseInt(v.sale_price) == 0)? 0 : 100 - (parseInt(v.sale_price) / parseInt(v.product_price) * 100);
								if(i != 0 && i % 2 == 0){
									html += '			</ul>';
									html += '		</div>';
									html += '		<div>';
									html += '			<ul>';
								}
								html += '				<li data-id="'+v.il_seq+'">';
								html += '					<a href="<?=$mainpage_directory ?>/item_product_page.php?no='+v.product_no+'&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'">';
								if(v.is_soldout == "2"){
									html += '						<div class="item_image soldout"></div>';
									html += '						<ul class="item">';
									html += '							<li class="item_name">'+v.product_name+'</li>';
									html += '							<li class="item_price"><span class="soldout">????????????</span></li>';
									html += '						</ul>';
								}else{
									html += '						<div class="item_image"></div>';
									html += '						<ul class="item">';
									html += '							<li class="item_name">'+v.product_name+'</li>';
									html += '							<li class="item_price"><span class="percent">'+Math.round(percent)+'%</span> <span class="sale_price">'+v.sale_price.format()+'???</span></li>';
									html += '							<li class="item_rating"><span class="no_star"><i class="fas fa-star"></i></span> 0 (0)</li>';
									html += '						</ul>';
								}
								html += '					</a>';
								html += '				</li>';
							});
							html += '			</ul>';
							html += '		</div>';
							html += '	</div>';
							html += '</div>';

							$mainContent.append(html);
							
							//loading image n option n rating
							$.each(data.data.list, function(i, v){
								var target = ".best_item ul li";

								get_file_list(target, v.il_seq, v.product_img, v.goodsRepImage);
								get_item_review(target, v.il_seq, v.product_no);
								if(v.is_use_option == "1" && v.sale_price == "0"){ // ???????????? + ?????? 0???
									get_item_option(target, v.il_seq);
								}
							});
						}else{
							html += '<div class="best_item">';
							html += '	<div class="title"><span>Best ?????????</span></div>';
							html += '	<div class="best_item_wrap">';
							html += '		<div class="no_data">';
							html += '			??????????????? ??????, ??????';
							html += '		</div>';
							html += '	</div>';
							html += '</div>';

							$mainContent.append(html);
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

	function new_item(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_item_list",
					is_view_main_2: (tab == "1")? "1" : "0",
					is_view_main_4: (tab == "3")? "1" : "0",
					is_view: "1", // ????????????
					flag: 0,
					cnt: 30
				},
				type: 'POST',
				dataType: 'JSON',
				beforeSend: function(){
					//$("#loading").css("display", "flex");
				},
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);

						var html = '';

						if(data.data.list && data.data.list.length > 0){
							html += '<div class="new_item">';
							html += '	<div class="title"><span>New ?????????</span></div>';
							html += '	<div class="new_item_wrap">';
							html += '		<div>';
							html += '			<ul>';
							$.each(data.data.list, function(i, v){
								var percent = (parseInt(v.product_price) == 0 || parseInt(v.sale_price) == 0)? 0 : 100 - (parseInt(v.sale_price) / parseInt(v.product_price) * 100);
								var sale_price = (v.sale_price && v.sale_price != "")? v.sale_price : 0;
								var product_name = (v.product_name && v.product_name != "")? v.product_name : '';
								if(i != 0 && i % 2 == 0){
									html += '			</ul>';
									html += '		</div>';
									html += '		<div>';
									html += '			<ul>';
								}
								html += '				<li data-id="'+v.il_seq+'">';
								html += '					<a href="<?=$mainpage_directory ?>/item_product_page.php?no='+v.product_no+'&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'">';
								if(v.is_soldout == "2"){
									html += '						<div class="item_image soldout"></div>';
									html += '						<ul class="item">';
									html += '							<li class="item_name">'+product_name+'</li>';
									html += '							<li class="item_price"><span class="soldout">????????????</span></li>';
									html += '						</ul>';
								}else{
									html += '						<div class="item_image"></div>';
									html += '						<ul class="item">';
									html += '							<li class="item_name">'+product_name+'</li>';
									html += '							<li class="item_price"><span class="percent">'+Math.round(percent)+'%</span> <span class="sale_price">'+sale_price.format()+'???</span></li>';
									html += '							<li class="item_rating"><span class="no_star"><i class="fas fa-star"></i></span> 0 (0)</li>';
									html += '						</ul>';
								}
								html += '					</a>';
								html += '				</li>';
							});
							html += '			</ul>';
							html += '		</div>';
							html += '	</div>';
							html += '</div>';

							$mainContent.append(html);
							
							//loading image n option n rating
							$.each(data.data.list, function(i, v){
								var target = ".new_item ul li";

								get_file_list(target, v.il_seq, v.product_img, v.goodsRepImage);
								get_item_review(target, v.il_seq, v.product_no);
								if(v.is_use_option == "1" && v.sale_price == "0"){ // ???????????? + ?????? 0???
									get_item_option(target, v.il_seq);
								}
							});
						}else{
							html += '<div class="new_item">';
							html += '	<div class="title"><span>New ?????????</span></div>';
							html += '	<div class="new_item_wrap">';
							html += '		<div class="no_data">';
							html += '			??????????????? ??????, ??????';
							html += '		</div>';
							html += '	</div>';
							html += '</div>';

							$mainContent.append(html);
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
							$.each(data.data, function(i, v){
								if(i == 0){
									$mainContent.find(target+"[data-id='"+il_seq+"'] .item_image").css("background-image", "url('"+v.file_path+"')");
								}
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
			}else{
				if(goodsRepImage != ""){
					$mainContent.find(target+"[data-id='"+il_seq+"'] .item_image").css("background-image", "url('"+goodsRepImage+"')");
				}else{
					$mainContent.find(target+"[data-id='"+il_seq+"'] .item_image").css("background-image", "url('../images/product_img.png')");
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
										$mainContent.find(target+"[data-id='"+il_seq+"'] .item_price .sale_price").text(sale_price.format()+'???');
										$mainContent.find(target+"[data-id='"+il_seq+"'] .item_price .percent").text(Math.round(percent)+'%');
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
								$mainContent.find(target+"[data-id='"+il_seq+"'] .item_rating").html('<span class="star"><i class="fas fa-star"></i></span> '+rating+' ('+data.data.length+')');
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

	function new_beauty_shop(){
		return new Promise(function(resolve, reject) {
			var html = '';

			$.ajax({
				url: '<?=$mainpage_directory ?>/index_ajax.php',
				data: {
					mode: "get_shop_new_list",
					limit: 10
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);

						html += '<div class="new_beauty_shop">';
						html += '	<div class="title"><span>?????? ???????????????!</span></div>';
						html += '	<ul class="new_beauty_shop_wrap">';
						$.each(data.data, function(i, v){
							var tag_list = v.total_service.split(',');
							var is_recommend = (v.is_recommend = "")? '<span class="choice_mark">??????</span>' : '';
							html += '		<li>';
							html += '			<a href="<?=$artist_directory?>/?type=beauty&artist_name='+encodeURIComponent(v.shop.name)+'&backurl='+encodeURIComponent(window.location.pathname+'?tab='+tab+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng)+'">';
							html += '				<div class="shop_image" style="background-image: url(\''+v.shop.front_image+'\');"></div>';
							html += '				<ul class="shop">';
							html += '					<li class="shop_name">'+v.shop.name+' '+is_recommend+'</li>';
							html += '					<li class="shop_tag">';
							for(var _i = 0; _i < tag_list.length; _i++){
								var tag_class = '';
								if(tag_list[_i] == "?????????" || tag_list[_i] == "?????????" || tag_list[_i] == "?????????" || tag_list[_i] == "?????????"){
									tag_class = "dog";
								}else if(tag_list[_i] == "?????????"){
									tag_class = "cat";
								}else if(tag_list[_i] == "??????" || tag_list[_i] == "??????"){
									tag_class = "place";
								}else if(tag_list[_i] == "??????"){
									tag_class = "hotel";
								}else if(tag_list[_i] == "?????????"){
									tag_class = "play";
								}
								html += '<span class="tag '+tag_class+'">'+tag_list[_i]+'</span>';
							}
							html += '					</li>';
							html += '					<li class="shop_location"><span><i class="fas fa-map-marker-alt"></i></span> '+v.shop_address+'</li>';
							html += '				</ul>';
							html += '			</a>';
							html += '		</li>';
						});
						html += '	</ul>';
						html += '	<div class="btn_wrap">';
						html += '		<button type="button" class="search_location_btn"><span><i class="fas fa-map-marker-alt"></i></span> ?????? ??????</button>';
						html += '	</div>';
						html += '</div>';
						$mainContent.append(html);
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
			html += '					<li>????????????(PetEasy Co.,Ltd) | ??????:?????????</li>';
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
			if (is_artist > 0) {
				html += '<div id="bj_hamburger"><img src="<?=$image_directory ?>/floating_myshop.png" /><span class="counsel_cnt">'+counsel_cnt+'</span></div>';
			}
			if(tab == "3"){ // item_cart
				html += '<div id="bj_item_cart"><img src="<?=$image_directory ?>/floating_cart.png" /><span class="cart_cnt">'+cart_cnt+'</span></div>';
			}
			html += '</div>';

			$mainContent.append(html);
			$("#loading").css("display", "none");
			resolve();
		});
	}

	function get_counsel_cnt(){
		return new Promise(function(resolve, reject) {
			if(customer_id != ""){
				$.ajax({
					url: '<?=$mainpage_directory ?>/index_ajax.php',
					data: {
						mode: "get_counsel_cnt",
						artist_id: customer_id
					},
					type: 'POST',
					dataType: 'JSON',
					success: function(data) {
						if(data.code == "000000"){
							console.log(data.data);
							counsel_cnt = data.data;
							$mainContent.find('#bj_hamburger .counsel_cnt').text(counsel_cnt);
							//$mainContent.find('.footer_btn .count').text(data.data);

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
				resolve();
			}
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

						cart_cnt = 0;
						if(data.data && data.data.length > 0){
							cart_cnt = data.data.length;
						}

						$mainContent.find('#bj_item_cart .cart_cnt').text(cart_cnt);

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

	function get_item_cart_for_guest(){
		return new Promise(function(resolve, reject) {
			// ???????????? ???????????? ??????
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode: "get_item_cart",
					is_session: "1"
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);

						if(data.data && data.data.length > 0){
							$.each(data.data, function(i, v){
								console.log(v.customer_id, customer_id);
								if(v.customer_id == "" && customer_id != ""){
									console.log("1"); // update
									set_update_item_cart(v.ic_seq);
								}else{
									// not used cart item
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
		});
	}

	function set_update_item_cart(ic_seq){
		return new Promise(function(resolve, reject) {
			console.log(ic_seq, customer_id, '<?=$sessionid ?>');
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "set_update_cart",
					is_session: "1",
					cart_update: "1",
					ic_seq: ic_seq,
					customer_id: customer_id
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						
						resolve();
					}else{
						//alert(data.data+"("+data.code+")");
						console.log('dont cart update');
						console.log(data.data);
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

	function get_banner(){
		return new Promise(function(resolve, reject) {
			var html = '';

			if(tab == "1"){
				html = '';
				html += '<div class="middle_banner">';
				html += '<a href="<?=$mainpage_directory ?>/view_event4.php"><img src="../images/middle_banner_9.jpg"></a>';/*???????????? ????????? <img src="../images/middle_banner_1_1.jpg">*/
				html += '</div>';
				$mainContent.find(".beauty_comment").after(html);

				html = '';
				html += '<div class="middle_banner">';
				html += '<a href="<?=$mainpage_directory ?>/view_event5.php"><img src="../images/middle_banner_1_1.jpg"></a>';/*???????????? ????????? <img src="../images/middle_banner_11.jpg">   <img src="../images/middle_banner_3 .jpg">*/
				html += '</div>';
				$mainContent.find(".new_beauty_shop").after(html);
			}else if(tab == "2"){
				html = '';
				html += '<div class="middle_banner">';
				html += '<a href="<?=$mainpage_directory ?>/view_event4.php"><img src="../images/middle_banner_9.jpg"></a>'; /*???????????? ????????? <img src="../images/middle_banner_7.jpg">  <img src="../images/middle_banner_3.jpg"> */
				$mainContent.find(".beauty_comment").after(html);

				html = '';
				html += '<div class="middle_banner">';
				html += '<a href="<?=$mainpage_directory ?>/view_event9.php"><img src="../images/beginingdogbanner.jpg"></a>';/*???????????? ????????? <img src="../images/middle_banner_6.jpg"> */
				html += '</div>';
				$mainContent.find(".new_beauty_shop").after(html);
			}else if(tab == "3"){
				html = '';
				html += '<div class="middle_banner">';
				html += '<a href="<?=$mainpage_directory ?>/view_event9.php"><img src="../images/middle_banner_9.jpg"></a>';/*???????????? ????????? <img src="../images/middle_banner_1_1.jpg">*/
				html += '</div>';
				$mainContent.find(".md_choice_item").after(html);

				html = '';
				html += '<div class="middle_banner">';
				html += '<a href="<?=$mainpage_directory ?>/view_event4.php"><img src="../images/beginingdogbanner.jpg"></a>';/*???????????? ????????? <img src="../images/middle_banner_6.jpg">*/
				html += '</div>';
				$mainContent.find(".new_item").after(html);
			}
			
			resolve();
		});
	}

	function no_app(){
		return new Promise(function(resolve, reject) {
			var html = '';

			if(tmp_val == "android" || tmp_val == "ios"){
			// to do somethings..
				var link = "https://play.google.com/store/apps/details?id=m.kr.gobeauty";
				if(tmp_val == "ios"){
					link = "https://apps.apple.com/kr/app/id1436568194";
				}

				html += '<div id="no_app">';
				html += '	<div class="no_app_wrap">';
				html += '		<div class="popup_wrap">';
				html += '			<a class="link" href="">';
				html += '				<table>';
				html += '					<colgroup>';
				html += '						<col width="*" />';
				html += '						<col width="90px" />';
				html += '					</colgroup>';
				html += '					<tr>';
				html += '						<td>???????????? ?????? <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></td>';
				html += '						<td rowspan="2" class="img"><img src="/pet/images/app_logo.png" /></td>';
				html += '					</tr>';
				html += '					<tr>';
				html += '						<td><div class="comment">"????????? ?????????<br/>????????? ?????? ?????????"</div></td>';
				html += '					</tr>';
				html += '				</table>';
				html += '				<button type="button" class="download_app">?????? ????????? ?????? <i class="fas fa-chevron-right"></i></button>';
				html += '			</a>';
				html += '			<div class="no_download" style="cursor: pointer;">????????????. ?????? ????????? ?????????</div>';
				html += '		</div>';
				html += '	</div>';
				html += '</div>';
				$mainContent.after(html);
				$("#no_app").addClass("on");
				$("#no_app .link").attr("href", link);
			}
			resolve();
		});
	}

	function get_shop_list(){
		return new Promise(function(resolve, reject) {
			$mainContent.find('.more_shop').remove();
			console.log(this_page);

			$.ajax({
				url: '<?=$mainpage_directory ?>/index_ajax.php',
				data: {
					mode: "get_shop_list",
					top: r_top,
					middle: r_middle,
					lat: r_lat,
					lng: r_lng,
					limit_0: this_page,
					limit_1: 10,
					shop_list: ''
				},
				type: 'POST',
				dataType: 'JSON',
				async: false,
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						var idx = 0;
						if(this_page == 0){
							$mainContent.append('<div class="shop_list_wrap"><div class="title"><span>??????/?????? ??????</span></div><ul class="shop_data"></ul></div>');
						}
						//console.log(data.data);
						console.log(data.sql);

						var p = $.when();
						var c = 0;
						$.each(data.data, function(i, v){
							p = p.then(function(){
								c++;
								if(c == Object.keys(data.data).length){
									resolve();
								}
								return get_main_contents(v);
							});
						});
						/*
						$.each(data.data, function(i, v){
							//shop_list.push(v.dec_customer_id);
							get_main_contents(v);
							idx++;
						});

						if(idx == 10){
							$mainContent.find('.shop_list_wrap').after('<div class="more_shop" onclick="javascript:get_shop_list();">?????????</div>');
							this_page += 10;
						}else if(idx == 0){
							var html = '';
							html += '<div class="artist_list">';
							html += '    <div class="shop_wrap">';
							html += '		<div class="no_data">';
							html += '			??? ????????? ?????????<br/>';
							html += '			??????/???????????????????????? ????????????.<br/>';
							html += '			(????????? Beta????????? ?????????,<br/>';
							html += '			????????? ?????????????????? ????????? ??? ????????????.)<br/>';
							html += '		</div>';
							html += '    </div>';
							html += '</div>';
							$mainContent.find('.shop_list_wrap').html(html);
						}
						*/
						resolve();
					}else{
						alert(data.data+"("+data.code+")");
						console.log(data.data);
					}
				},
				complete: function(){
					if(html_out != ""){
						$mainContent.find('.shop_list_wrap .shop_data').append('<div class="sub_title">???????????? ???</div>');
						$mainContent.find('.shop_list_wrap .shop_data').append(html_out);
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


	function get_main_contents(list){
		console.log(list);
		$.ajax({
			url: '<?=$mainpage_directory ?>/index_ajax.php',
			data: {
				mode: "get_main_contents",
				customer_id: list.dec_customer_id
			},
			type: 'POST',
			dataType: 'JSON',
			async: false,
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);

					var html = '';
					var shop_address = (typeof data.data.shop_address != "undefined")? data.data.shop_address : "";
					var tag_list = (data.data.total_service && data.data.total_service.indexOf(',') != -1)? data.data.total_service.split(',') : [data.data.total_service];
					var is_recommend = (list[0].is_recommend == "1")? '<span class="choice_mark">??????</span>' : '';

					html += '		<li>';
					html += '			<a href="<?=$artist_directory?>/?type=beauty&artist_name='+encodeURIComponent(list[0].name)+'&top='+r_top+'&middle='+r_middle+'&lat='+r_lat+'&lng='+r_lng+'">';
					html += '				<div class="shop_image" style="background-image: url(\''+list[0].front_image+'\');">';
					if(r_lat != "" && r_lng != ""){
						html += '				<span class="distance" style="position: absolute; right: 5px; bottom: 5px; font-size: 14px; color: rgba(255,255,255,1); border: 0px; padding: 5px; border-radius: 10px; background-color: rgba(125,125,125,0.6);"><i class="fas fa-map-marker-alt"></i> '+Math.round(list[0].distance*100)/100+' km</span>';
					}
					html += '				</div>';
					html += '				<ul class="shop">';
					html += '					<li class="shop_name">'+list[0].name+' '+is_recommend+'</li>';
					html += '					<li class="shop_tag">';
					for(var _i = 0; _i < tag_list.length; _i++){
						var tag_class = '';
						if(tag_list[_i] == "?????????" || tag_list[_i] == "?????????" || tag_list[_i] == "?????????" || tag_list[_i] == "?????????"){
							tag_class = "dog";
						}else if(tag_list[_i] == "?????????"){
							tag_class = "cat";
						}else if(tag_list[_i] == "??????" || tag_list[_i] == "??????"){
							tag_class = "place";
						}else if(tag_list[_i] == "??????"){
							tag_class = "hotel";
						}else if(tag_list[_i] == "?????????"){
							tag_class = "play";
						}

						html += '<span class="tag '+tag_class+'">'+tag_list[_i]+'</span>';
					}
					html += '					</li>';
					html += '					<li class="shop_location"><span><i class="fas fa-map-marker-alt"></i></span> '+shop_address+'</li>';
					html += '				</ul>';
					html += '			</a>';
					html += '		</li>';

					if(data.data.out_shop_product == "1" && r_top != "" && r_middle != ""){
						html_out += html;
					}

					$mainContent.find('.shop_list_wrap .shop_data').append(html);
				}else{
					alert(data.data+"("+data.code+")");
					console.log(data.data);
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

	function checkMobile2(){
		var varUA = navigator.userAgent.toLowerCase(); //userAgent ??? ??????
		if ( varUA.indexOf("app_gobeauty_and") > -1 ) {
			//APP
			return "in_app_and";
		} else if (varUA.indexOf("app_gobeauty_ios") > -1 ) {
			//???????????????
			return "in_app_ios";
		} else if ( varUA.indexOf('android') > -1 ) {
			//???????????????
			return "android";
		} else if ( varUA.indexOf("iphone") > -1||varUA.indexOf("ipad") > -1||varUA.indexOf("ipod") > -1 ) {
			//IOS
			return "ios";
		} else {
			//?????????, ??????????????? ???
			return "other";
		}
	}

	function setCookie_popup(name, value, expiredays) {
		var todayDate = new Date();
		todayDate.setDate(todayDate.getDate() + expiredays);
		document.cookie = name + '=' + escape(value) + '; path=/; expires=' + todayDate.toGMTString() + '; SameSite=None; Secure';
	}

	function setCookie_popup_ios(name, value, expiredays) { // ios
		var todayDate = new Date();
		todayDate.setDate(todayDate.getDate() + expiredays);
		document.cookie = name + '=' + escape(value) + '; path=/; expires=' + todayDate.toGMTString() + '; ';
	}

	function getCookie_popup(name) {
		var obj = name + "=";
		var x = 0;
		while (x <= document.cookie.length) {
			var y = (x + obj.length);
			if (document.cookie.substring(x, y) == obj) {
				if ((endOfCookie = document.cookie.indexOf(";", y)) == -1)
					endOfCookie = document.cookie.length;
				return unescape(document.cookie.substring(y, endOfCookie));
			}
			x = document.cookie.indexOf(" ", x) + 1;
			if (x == 0)
				break;
		}
		return "";
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

	function return_index(div_elmt){
		let r_index = Math.floor(Math.random() * ($('div.'+div_elmt+' .swiper-slide:not(.swiper-slide-duplicate)').length));
		return parseInt(r_index);
	}
</script>

<?php include "../include/bottom.php"; ?>