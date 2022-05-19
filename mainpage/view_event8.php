<?php 
	include "../include/top.php";

	$backurl = $_GET["backurl"];

	$user_id = isset($_SESSION['gobeauty_user_id']) ? $_SESSION['gobeauty_user_id'] : "";
	$user_name = isset($_SESSION['gobeauty_user_nickname']) ? $_SESSION['gobeauty_user_nickname'] : "";
?>
<script src="<?= $js_directory ?>/fontawesome.min.js"></script>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<link rel="stylesheet" href="<?= $css_directory ?>/m_layout.css?<?= filemtime($upload_static_directory . $css_directory . '/m_layout.css') ?>">
<style>
	#fixed-menu { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; background-color: #fff; z-index: 2; border-bottom: 1px solid #ccc; }
	
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
		<div class="center_menu">메리도그
		</div>
		<div class="right_menu">
		</div>
		<div class="scroll_top"><i class="fas fa-chevron-up"></i></div>
	</div>
</div>
<div id="event_8">
	<div class="banner">
		<img src="../images/view_event8_merry_banner_1.jpg" />
	</div>

	<div id="event">
	</div>
</div>

<script>
	var $event = $("#event");
	var event_item_list = ['ETCB-DB-A12', 'ETCB-DB-A13', 'ETCB-DB-A14'];
	var flag = 0;
	var cnt = 10;
	var lastScrollTop = 0;
	var timer = null;
	var customer_id = "<?=$user_id ?>";

	$(function(){
		$("#loading").css("align-items", "center").css("justify-content", "center");
		$("#loading").html("<span style='color: #fff; max-width: 50%; text-align:center;'><img src='/pet/images/logo_loading0.gif' style='width: 100px; height: 100px;'/><br/><span style='color:#f5bf2e;'>반</span>려생활의 단<span style='color:#f5bf2e;'>짝</span></span>");

		get_event_item_list_html()
			.then(get_event_list)
			.then(footer_html)
			.then(get_cart_cnt);
	});

	$(window).on('popstate', function(event) {
		if(page_loaded){
			window.location = document.location.href;
		}
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

		// scrollStop event
		if(timer !== null) { clearTimeout(timer); }
		timer = setTimeout(function() {
			if(st != 0){
				$(document).find(".scroll_top").addClass("on");
			}
		}, 150);
	});

	// 맨위로 스크롤
	$(document).on("click", ".scroll_top", function(){
		$('html, body').animate({scrollTop : '0'}, 100);
	});

	$event.on("click", ".item_link_btn", function(){
		console.log(localStorage.getItem('windowscrolltop_event8'));
		localStorage.setItem('windowscrolltop_event8',$(window).scrollTop()); // scroll position
		history.replaceState('', '', window.location.pathname);
		location.href = "<?=$mainpage_directory ?>/item_product_page.php?no="+$(this).data("product_no")+"&backurl="+encodeURIComponent(window.location.pathname);
	});

	// 장바구니 이동
	$event.on("click", "#bj_item_cart", function(){
		history.replaceState('', '', window.location.pathname);
		location.href = "<?=$item_directory ?>/item_cart.php?backurl="+encodeURIComponent(window.location.pathname);
	});

	function get_event_item_list_html(){
		return new Promise(function(resolve, reject) {
			var html = '';

			html += '<div class="title"><span>메리도그 세트</span></div>';
			html += '<div class="item_list">';
			html += '	<div class="caption">';
			html += '		<div class="total_cnt">0개의 상품</div>';
			html += '		<div class="order">';
			//html += '			<select class="orderby">';
			//html += '				<option value="">첫랭킹순</option>';
			//html += '			</select>';
			html += '		</div>';
			html += '	</div>';
			html += '	<div class="new_item_wrap">';
			html += '	</div>';
			html += '</div>';

			$event.html(html);
			resolve();
		});
	}

	function get_event_list(){
		return new Promise(function(resolve, reject) {
			$.each(event_item_list, function(i, v){
				get_item_list(v);
			});

			$event.find('.item_list .total_cnt').text(event_item_list.length+'개의 상품');
			resolve();
		});
	}
	
	function get_item_list(product_no){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_item_list",
					product_no : product_no,
					is_view: "1", // 노출여부 1-노출, 2-전부
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
						$event.find(".more_btn").remove();
						console.log(data.data);

						var html = '';

						if(data.data.list && data.data.list.length > 0){
							$.each(data.data.list, function(i, v){
								var percent = (parseInt(v.product_price) == 0 || parseInt(v.sale_price) == 0)? 0 : 100 - (parseInt(v.sale_price) / parseInt(v.product_price) * 100);
								html += '		<a href="javascript:;">';
								html += '			<div class="item item_link_btn" data-id="'+v.il_seq+'" data-product_no="'+v.product_no+'">';
								if(v.is_soldout == "2"){
									html += '				<div class="item_image soldout"></div>';
									html += '				<div class="item_name">'+v.product_name+'</div>';
									html += '				<div class="item_price"><span class="soldout">일시품절</span></div>';
								}else{
									html += '				<div class="item_image"></div>';
									html += '				<div class="item_name">'+v.product_name+'</div>';
									html += '				<div class="item_price"><span class="percent">'+Math.round(percent)+'%</span> <span class="sale_price">'+v.sale_price.format()+'원</span></div>';
									html += '				<div class="item_rating"><span class="no_star"><i class="fas fa-star"></i></span> <span>0</span> (<span>0)</div>';
								}
								html += '			</div>';
								html += '		</a>';
							});
							$event.find('.new_item_wrap').append(html);

							//if(data.data.list.length == 10 && data.data.list_cnt != data.data.total_cnt){
							//	$event.find('.new_item_wrap').after('<div class="more_btn">더보기 ('+data.data.list_cnt+' / '+data.data.total_cnt+')</div>');
							//}
							
							//loading image
							$.each(data.data.list, function(i, v){
								var target = ".item_list .item";

								get_file_list(target, v.il_seq, v.product_img, v.goodsRepImage);
								get_item_review(target, v.il_seq, v.product_no);
								if(v.is_use_option == "1" && v.sale_price == "0"){ // 옵션사용 + 가격 0원
									get_item_option(target, v.il_seq);
								}
							});
						}else{
							if(data.data.list_cnt != data.data.total_cnt){
								html += '		<div class="no_data">';
								html += '			반려생활의 단짝, 반짝';
								html += '		</div>';

								$event.find('.new_item_wrap').append(html);
							}
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
										$event.find(target+"[data-id='"+il_seq+"'] .item_image").css("background-image", "url('"+v.file_path+"')");
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
					$event.find(target+"[data-id='"+il_seq+"'] .item_image").css("background-image", "url('"+goodsRepImage+"')");
				}else{
					$event.find(target+"[data-id='"+il_seq+"'] .item_image").css("background-image", "url('../images/product_img.png')");
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
										$event.find(target+"[data-id='"+il_seq+"'] .item_price .sale_price").text(sale_price.format()+'원');
										$event.find(target+"[data-id='"+il_seq+"'] .item_price .percent").text(Math.round(percent)+'%');
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
								$event.find(target+"[data-id='"+il_seq+"'] .item_rating").html('<span class="star"><i class="fas fa-star"></i></span> '+rating+' ('+data.data.length+')');
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

	function footer_html(){
		return new Promise(function(resolve, reject) {
			var html = '';

			html += '<div id="footer-contents">';
			/*
			html += '	<div class="info_wrap">';
			html += '		<div class="info_icon"><img src="../images/icon_call.png"></div>';
			html += '		<div class="info_1">고객센터</div>';
			html += '		<div class="info_2"><?=$company_help_number?></div>';
			html += '		<div class="info_3"><?=$company_help_email?></div>';
			html += '	</div>';
			html += '	<br>';
			html += '	<div class="bottom_notice">';
			html += '		<a href="<?=$mainpage_directory ?>/terms_of_service.php">이용약관</a> | <a href="<?=$mainpage_directory ?>/privacy_policy.php">개인정보처리방침</a> | <a href="<?=$mainpage_directory ?>/proprietorship.php">사업자정보확인</a>';
			html += '	</div>';
			html += '	<div class="f_wrap"> ';
			html += '		<div class="bottom_notice">';
			html += '			<div class="f_subwrap">';
			html += '				<ul>';
			html += '					<li>㈜펫이지(PetEasy Co.,Ltd) | 대표자:신동찬</li>';
			html += '					<li>사업자등록번호 157-86-01070 </li>';
			html += '					<li>통신판매업 제 2019-서울노원-0540호</li>';
			html += '					<li>서울시 종로구 종로6 5층 서울창조경제혁신센터 </li>';
			html += '					<li>개인정보담당자 전영도 privacy@peteasy.kr</li>';
			html += '					<li>© PetEasy Co. Ltd. All Rights Reserved.</li>';
			html += '				</ul>';
			html += '				<ul>';
			html += '					<li>반짝은 통신판매중개자이며 통신판매의 당사자가 아닙니다.</li>';
			html += '					<li>따라서 반짝은 상품거래정보 및 거래에 대해 책임지지 않습니다. </li>';
			html += '					<li>다만 회사가 판매하는 직매입 상품의 경우 판매업체의 지위를 갖게 </li>';
			html += '					<li>됩니다.</li>';
			html += '				</ul>';
			html += '			</div>';
			html += '		</div>';
			html += '	</div>';
			*/
			html += '	<div id="bj_item_cart"><img src="<?=$image_directory ?>/floating_cart.png" /><span class="cart_cnt">0</span></div>';
			html += '</div>';

			$event.append(html);
			$("#loading").hide();
			resolve();

			console.log(localStorage.getItem('windowscrolltop_event8'));
			if(localStorage.getItem('windowscrolltop_event8') > 0){
				$('html, body').animate({
					scrollTop: localStorage.getItem('windowscrolltop_event8')
				}, 500, function(){
					localStorage.setItem('windowscrolltop_event8', 0); // scroll position					
				});
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

						var cart_cnt = 0;
						if(data.data && data.data.length > 0){
							cart_cnt = data.data.length;
						}

						$event.find('#bj_item_cart .cart_cnt').text(cart_cnt);

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