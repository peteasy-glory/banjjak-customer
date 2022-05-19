<?php
include "../include/top.php";
include "../include/Crypto.class.php";
include "../Mobile_Detect.php";

//$r_seq = ($_GET['seq'] && $_GET['seq'] != "")? $_GET['seq'] : "";
$r_no = ($_GET['no'] && $_GET['no'] != "")? $_GET['no'] : "";
$r_sh = ($_GET['sh'] && $_GET['sh'] != "")? $_GET['sh'] : "";
$r_admin = ($_GET['adn'] && $_GET['adn'] != "")? $_GET['adn'] : "";
$backurl = $_GET['backurl'];
$r_word = ($_GET["word"] && $_GET["word"] != "")? $_GET["word"] : "";
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

if($r_no == ""){
?>
    <script>
        $.MessageBox({
            buttonDone: "확인",
            message: "잘못된 접근입니다. 메인페이지로 이동합니다."
        }).done(function() {
            location.href = "<?= $mainpage_directory ?>/index.php";
        });
    </script>
<?php
	exit;
}

// top_back_btn_event
if($backurl != ""){
	$top_back = '<a href="'.$backurl.'"><img src="'.$image_directory.'/btn_back_2.png" width="26px"></a>';
}else{
	$top_back = '<a href="'.$mainpage_directory.'/index.php"><img src="'.$image_directory.'/btn_back_2.png" width="26px"></a>';
}

?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-152043924-1"></script>
<script src="<?= $js_directory ?>/fontawesome.min.js"></script>
<script src="<?= $js_directory ?>/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $css_directory ?>/jquery.fancybox.css?<?= filemtime($upload_static_directory . $css_directory . '/jquery.fancybox.css') ?>">
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<style>
.top_menu { position: fixed; left: 0px; top: 0px; width: 100%; background-color: rgba(255,255,255,0.8); z-index: 2; }
.scroll_top { display: none; position: fixed; right: 10px; bottom: 10px; width: 50px; height: 50px; background-color: rgba(255, 255, 255, 0.4); border-radius: 25px; -webkit-align-items: center; -webkit-justify-content: center; border: 1px solid #ccc; }
.scroll_top.on { display: flex; }
.messagebox_overlay { z-index: 999; }
#item_product_page { margin-top: 58px; }


/* item_image_swiper */
#item_product_page .swiper-container_front {height: 300px;width: 100%;margin: 0px auto;overflow: hidden;position: relative;z-index: 1;}
#item_product_page .swiper-wrapper {transform: translate3d(0px, 0px, 0px);-webkit-transform: translate3d(0, 0, 0);-moz-transform: translate3d(0, 0, 0);-o-transform: translate(0, 0);}
#item_product_page .swiper-wrapper {width: 100%;height: 100%;display: flex;position: relative;z-index: 1;box-sizing: content-box;transition-property: transform;-webkit-transform-style: preserve-3d;-moz-transform-style: preserve-3d;-webkit-transition-property: -webkit-transform;-moz-transition-property: -moz-transform;-o-transition-property: -o-transform;-webkit-box-sizing: content-box;-moz-box-sizing: content-box;}
#item_product_page .swiper-slide {background: #ffffff;display: -webkit-box;display: -ms-flexbox;display: -webkit-flex;display: flex;-webkit-box-pack: center;-ms-flex-pack: center;-webkit-justify-content: center;justify-content: center;-webkit-box-align: center;-ms-flex-align: center;-webkit-align-items: center;align-items: center;overflow: hidden;}
#item_product_page .swiper-slide {flex: 0 0 auto;width: 100%;height: 100%;position: relative;-webkit-transform-style: preserve-3d;-moz-transform-style: preserve-3d;-webkit-flex-shrink: 0;}
#item_product_page .swiper-slide img {height: 100%;width: auto;}
#item_product_page .line-through { text-decoration: line-through; color: #999; }

/* item */
#item_product_page .content_box { width:90%; margin:20px auto; }
#item_product_page .content_box ul.table { display: table; width: 100%; }
#item_product_page .content_box ul.table li { display: table-cell; width: 50%; padding: 0px 10px; }
#item_product_page .content_box div.buy_cart {-webkit-appearance: none;border-radius: 0;background-color: #f5bf2e;-webkit-border-top-left-radius: 6px;-moz-border-radius-topleft: 6px;border-top-left-radius: 6px;-webkit-border-top-right-radius: 6px;-moz-border-radius-topright: 6px;border-top-right-radius: 6px;-webkit-border-bottom-right-radius: 6px;-moz-border-radius-bottomright: 6px;border-bottom-right-radius: 6px;-webkit-border-bottom-left-radius: 6px;-moz-border-radius-bottomleft: 6px;border-bottom-left-radius: 6px;text-indent: 0px;border: 0;display: inline-block;color: #ffffff;font-size: 18px;font-weight: normal;font-style: normal;line-height: 30px;width: 100%;margin-top: 20px;text-decoration: none;text-align: center;padding: 5px 0;cursor:pointer;}
#item_product_page .content_box div.buy_cart.disabled { background-color: #999; color: #ccc; }
#item_product_page .content_box div.buy_go {-webkit-appearance: none;border-radius: 0;background-color: #f5bf2e;-webkit-border-top-left-radius: 6px;-moz-border-radius-topleft: 6px;border-top-left-radius: 6px;-webkit-border-top-right-radius: 6px;-moz-border-radius-topright: 6px;border-top-right-radius: 6px;-webkit-border-bottom-right-radius: 6px;-moz-border-radius-bottomright: 6px;border-bottom-right-radius: 6px;-webkit-border-bottom-left-radius: 6px;-moz-border-radius-bottomleft: 6px;border-bottom-left-radius: 6px;text-indent: 0px;border: 0;display: inline-block;color: #ffffff;font-size: 18px;font-weight: normal;font-style: normal;line-height: 30px;width: 100%;margin-top: 20px;text-decoration: none;text-align: center;padding: 5px 0;cursor:pointer;}
#item_product_page .content_box div.buy_go.disabled { background-color: #999; color: #ccc; }
#item_product_page .content_box .content_box_wrap .product_name { font-size:18px; padding-bottom: 5px; }
#item_product_page .content_box .content_box_wrap .item_option_wrap{font-size:14px;font-family: 'NanumGothic';font-weight: bold;}
#item_product_page .content_box .content_box_wrap .item_option_wrap .right { text-align: right; }
#item_product_page .content_box .content_box_wrap .item_option_wrap #item_option { width: 80%; height: 30px; border: 1px solid #ccc; background-color: transparent; }
#item_product_page .content_box .content_box_wrap .item_option_wrap #item_option option[value='soldout'] { background-color: #999; color: #ccc; }
#item_product_page .content_box .content_box_wrap .total {border: 1px solid #ddd;width:100%;box-sizing: border-box;border-radius: 3px;padding:10px;margin-top:10px;position: relative;}
#item_product_page .content_box .content_box_wrap .total span{position: absolute;top: 10px;right: 10px;color:#D51A3D;}
#item_product_page .content_box .content_box_wrap {width:100%;}
#item_product_page .content_box .content_box_wrap .right{text-align: right;}
#item_product_page .content_box .content_box_wrap .mini_title{font-size:14px;color:#666;}
#item_product_page .content_box .content_box_wrap .charge_line{font-size:12px;color:#666;border-bottom:1px solid #aaa;padding: 10px 0;}
#item_product_page .content_box .content_box_wrap .padd20_cen{padding:20px;text-align: center;}
#item_product_page .content_box .content_box_wrap select#size_option{margin-left:10px;width:70%;}
#item_product_page .content_box .content_box_wrap a{width:auto;height:25px;vertical-align: bottom;}
#item_product_page .content_box .content_box_wrap .btn_set{/*width:100%;*/height:100%;}
#item_product_page .content_box .content_box_wrap input.item_number[type="text"] {font-size:12px;text-align:center;border: 1px solid #ccc;border-radius: 5px;width:25%;height: 13px;line-height: 20px;background-color: #FFF;padding: 5px;}
#item_product_page .content_box .content_box_wrap .select_line{border: 1px solid #aaa;}
#item_product_page .content_box .content_box_wrap .select_line td {font-size:14px;padding: 5px;}
#item_product_page .content_box .content_box_wrap .product_review { font-family: 'NL2GR'; font-size: 14px; padding-bottom: 10px; }
#item_product_page .content_box .content_box_wrap .product_review .star { color: #f5bf2e; }
#item_product_page .content_box .content_box_wrap .cart_wrap ul li { vertical-align: middle; height: 30px; font-size:14px; padding: 5px; }
#item_product_page .content_box .content_box_wrap .cart_wrap ul li:last-child { text-align: right; }
#item_product_page .video-container { position: relative; padding-bottom: 56.25%; padding-top: 30px; height: 0; overflow: hidden; margin-bottom: 20px; }
#item_product_page .video-container iframe,
#item_product_page .video-container object,
#item_product_page .video-container embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }

/* item_event */
#item_product_page .event { border-top: 5px solid #ddd; background-color: #eee; }
#item_product_page .event .event_top { position: relative; width: calc(100% - 40px); padding: 15px 0px; margin: 0px auto 10px; text-align: center; border-bottom: 1px solid #999; font-size: 18px; }
#item_product_page .event .event_top .total_cnt { position: absolute; right: 15px; top: 15px; }
#item_product_page .event .event_progress { position: relative; padding: 20px; font-size: 16px; }
#item_product_page .event .event_progress .end_cnt { position: absolute; right: 20px; top: 20px; }
#item_product_page .event .event_progress .progress_bar { width: 100%; margin: 10px auto; border: 0px; background-color: #fff; height: 10px; box-sizing: border-box; border-radius: 0px; }
#item_product_page .event .event_progress .progress_bar .ui-progressbar-value { width: 25%; background-color: #5AA1FB; height: 100%; }
#item_product_page .event .event_progress .cnt_line { position: relative; width: calc(100% - 20px); margin: 10px auto; border: 0px; background-color: transparent; height: 20px; box-sizing: border-box; }
#item_product_page .event .event_progress .now_cnt { color: #5AA1FB; }
#item_product_page .event .event_progress .end_dt { text-align: right; font-size: 14px; color: #5AA1FB; }

/* item_detail_infomation */
#item_product_page .content_detail{border-top: 5px solid #ddd;width:100%;padding:20px 0;}
#item_product_page .content_detail div.content_title{border: 1px solid #ddd;width:30%;padding:5px 0;margin: 0 auto;text-align: center;font-size:16px;}
#item_product_page .content_detail div.content_text{border: 1px solid #eee;width:90%;margin: 10px auto;}
#item_product_page .content_detail .content_text img { width: 100% !important; }
#item_product_page .content_detail .content_text iframe { width: 340px !important; height: 192px !important; }
#item_product_page .content_detail .loading { text-align: center; padding: 40px 0px; background-color: #eee; color: #999; font-size: 18px; font-weight: normal; font-family: 'NL2GR'; }

/* item_review */
#item_product_page .content_detail .content_text img { width: 100% !important; }
#item_product_page .content_detail .content_review { margin: 20px 0px; font-family: 'NL2GR'; }
#item_product_page .content_detail .content_review .customer_review { position: relative; margin-bottom: 50px; }
#item_product_page .content_detail .content_review .customer_review .img { position: absolute; left: 10px; top: 0px; width: 50px; height: 50px; border-radius: 25px; background-color: #e9e9e9; overflow: hidden; background-size: cover; background-position: center; background-repeat: no-repeat; }
#item_product_page .content_detail .content_review .customer_review ul { margin-left: 70px; width: calc(100% - 70px); }
#item_product_page .content_detail .content_review .customer_review ul li { padding: 5px 0px; }
#item_product_page .content_detail .content_review .customer_review .product { color: #999; font-size: 14px; padding-bottom: 10px;}
#item_product_page .content_detail .content_review .customer_review .review { position: relative; background-color: #f6f6f6; border-radius: 10px; min-height: 50px; padding: 10px; line-height: 22px; }
#item_product_page .content_detail .content_review .customer_review .review .review_image_wrap { min-height: 70px; margin: 10px 0px; }
#item_product_page .content_detail .content_review .customer_review .review .review_image_wrap .review_image { display: inline-block; width: 70px; height: 70px; border-radius: 10px; overflow: hidden; background-size: cover; background-position: center; background-repeat: no-repeat; }
#item_product_page .content_detail .content_review .customer_review .star-rating { font-size: 12px; color: #999; line-height: 40px; }
#item_product_page .content_detail .content_review .customer_review .star-rating .star { color: #f5bf2e; }
#item_product_page .content_detail .content_review .customer_review .review_dt { color: #999; font-size: 14px; padding: 5px; }
#item_product_page .content_detail .content_review .customer_review .blind { color: #999; text-align: center; padding: 30px 0px; }
#item_product_page .content_detail .content_review .customer_review .reply { position: relative; background-color: #f6f6f6; border-radius: 10px; min-height: 50px; padding: 10px; line-height: 22px; }
#item_product_page .content_detail .content_review .customer_review .reply .reply_icon { position: absolute; left: -35px; top: 0px; font-size: 30px; color: #999; transform: rotate(150deg); }
#item_product_page .content_detail .content_review .customer_review .reply_dt { color: #999; font-size: 14px; padding: 5px; }
#item_product_page .content_detail .content_review .customer_review .no_review { text-align: center; color: #999; padding: 30px 0px; }

/* item_group_option */
#item_product_page .item_group_option {font-size: 14px; width: calc(100% - 20px); margin: 20px auto; border: 1px solid #ccc; border-radius: 5px; }
#item_product_page .item_group_option .item_group_option_wrap { position: relative; width: calc(100% - 10px); margin: 5px auto; padding: 0px 5px; }
#item_product_page .item_group_option .item_group_option_wrap>.title { height: 40px; line-height: 40px; }
#item_product_page .item_group_option .item_group_option_wrap ul { position: absolute; right: 5px; top: 5px; }
#item_product_page .item_group_option .item_group_option_wrap ul li select { height: 30px; padding: 0px 10px; border: 1px solid #ccc; border-radius: 5px; width: 150px; }
#item_product_page .item_group_option .item_group_option_wrap .group_option_detail2 { display: flex; justify-content: flex-start; flex-wrap: wrap; align-items: flex-start; padding: 10px 0px; }
#item_product_page .item_group_option .item_group_option_wrap .group_option_detail2 .right_menu { position: absolute; right: 5px; top: 5px; }
#item_product_page .item_group_option .item_group_option_wrap .group_option_detail2 .right_menu input[type="text"] { height: 30px; border: 0px; border-bottom: 1px solid #ccc; padding: 0px 10px; text-align: right; }
#item_product_page .item_group_option .item_group_option_wrap .group_option_detail2 .flex-item { position: relative; flex: 1 1 16%; text-align: center; height: 50px; }
#item_product_page .item_group_option .item_group_option_wrap .group_option_detail2 .flex-item input[type="radio"] { display: none; width: 0px; height: 0px; border: 0px; margin: 0px; padding: 0px; font-size: 0px; }
#item_product_page .item_group_option .item_group_option_wrap .group_option_detail2 .flex-item input[type="radio"]+label { display: inline-block; border: 2px solid #fff; width: 40px; height: 40px; }
#item_product_page .item_group_option .item_group_option_wrap .group_option_detail2 .flex-item input[type="radio"]+label .image { background-size: cover; background-repeat: no-repeat; background-position: center; width: 100%; height: 100%; }
#item_product_page .item_group_option .item_group_option_wrap .group_option_detail2 .flex-item input[type="radio"]+label .option_name { display: none; }
#item_product_page .item_group_option .item_group_option_wrap .group_option_detail2 .flex-item input[type="radio"]:checked+label { border: 2px solid #f5bf2e; }
#item_product_page .item_group_option .btn_wrap { padding: 10px; }
#item_product_page .item_group_option .btn_wrap button { width: 100%; height: 30px; padding: 0px 10px; border: 1px solid #ccc; background-color: #eee; border-radius: 5px; }
#bj_item_cart { position: fixed; display: inline-block; left: 10px; bottom: 10px; width: 50px; height: 50px; text-align: center; border-radius: 25px; border: 1px solid #f5bf2e; background-color: #f5bf2e; vertical-align: middle; font-size: 18px; color: #fff; z-index: 99; }
#bj_item_cart:before { content: ''; background-color: #FFECB9; display: inline-block; width: 27px; height: 27px; position: absolute; left: 7px; top: 7px; border-radius: 100%; }
#bj_item_cart .fa-bars { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); }
#bj_item_cart .fa-times { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); }
#bj_item_cart .cart_cnt { position: absolute; right: -5px; top: -5px; display: inline-block; background-color: #72a7ff; width: 20px; height: 20px; line-height: 20px; border-radius: 10px; font-size: 14px; }
#bj_item_cart>img { position: relative; width: 24px; top: 8px; }

@media (min-device-width: 800px) {
	#item_product_page{width:800px;margin: 58px auto 0;}
	#item_product_page .swiper-pagination-bullet-active { background-color: #999; } 
	#item_product_page .content_detail .content_text iframe { width: 720px !important; height: 405px !important; }
}
</style>
<div class="top_menu">
	<div class="top_back"><?=$top_back?></div>
	<div class="top_title">
		<p>상품 페이지</p>
	</div>
	<div class="top_right"><a href="/pet/mainpage/"><img src="/pet/images/main_logo.png" height="24px"></a></div>
	<div class="scroll_top"><i class="fas fa-chevron-up"></i></div>
	<div id="bj_item_cart"><img src="<?=$image_directory ?>/floating_cart.png" /><span class="cart_cnt">0</span></div>
</div>
<div id="item_product_page">
	<div class="swiper-container_front">
		<div class="swiper-wrapper">
			<div class="swiper-slide"><img src="../images/product_img.png"></div>
		</div>
        <div class="swiper-pagination" style="margin-bottom: 5%;"></div>
    </div>
	<div class="content_box">
		<form id="cart">
			<input type="hidden" name="total_price" value="0" />
			<input type="hidden" name="is_special_mall" value="2" />
			<div class="content_box_wrap">
				<div class="product_comment mini_title"></div>
				<div class="product_name"></div>
				<div class="product_review"></div>
				<div class="item_option_wrap"></div>
				<div class="cart_wrap"></div>
			</div>
		</form>
		<div class="total">총 상품금액<span>0원</span></div>
		<ul class="table">
			<li>
				<div class="buy_cart" style="cursor: pointer;">장바구니 담기</div>
			</li>
			<li>
				<div class="buy_go" style="cursor: pointer;">바로 구매</div>
			</li>
		</ul>
	</div>
	<div class="content_detail">
		<div class="content_title">상세정보</div>
		<div class="content_text">
			<div class="loading">loading...</div>
		</div>
		<div class="content_title" id="content_review">상품리뷰</div>
		<div class="content_review">
			<div class="loading">loading...</div>
		</div>
	</div>
</div>

<script>
var il_seq = '<?=$r_seq?>';
var product_no = '<?=$r_no?>';
var is_shop = '<?=$r_sh?>';
var backurl = '<?=$backurl ?>';
var cart = [];
var shipping_price = 2500;
var event_complete = 0; // 완료 카운트
var lastScrollTop = 0;
var timer = null;
var group_option = [];
var ip_seq = ''; // 공급사번호
var is_supply = 2; // 공급사(1-업체, 2-자체)
var supplier = ''; // 공급사명
var customer_id = '<?=$user_id ?>';
var swiper = new Swiper('.swiper-container_front', {
	mousewheel: true,
	pagination: {
		el: '.swiper-pagination',
		clickable: true,
	},
});

// Global site tag (gtag.js) - Google Analytics 
window.dataLayer = window.dataLayer || [];
function gtag() { dataLayer.push(arguments); }
gtag('js', new Date());
gtag('config', 'UA-152043924-1');

$(document).ready(function() {
	get_cart_cnt();
	get_item();
});

// 리뷰로 스크롤
$(document).on("click", "#item_product_page .product_review", function(){
	$('html, body').animate({scrollTop : ($("#content_review").position().top - 70)}, 100);
});

// 맨위로 스크롤
$(document).on("click", ".scroll_top", function(){
	$('html, body').animate({scrollTop : '0'}, 100);
});

// 상위 숨기기
$(window).on("scroll", function(){
	var st = $(this).scrollTop();
	if(st > lastScrollTop || st == 0){
		$(document).find(".scroll_top").removeClass("on");
	}else{
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

// 장바구니 이동
$(document).on("click", "#bj_item_cart", function(){
	location.href = "<?=$item_directory ?>/item_cart.php?backurl="+encodeURIComponent(window.location.pathname+'?no='+product_no+'&backurl='+backurl);
});

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
					$(document).find('#bj_item_cart .cart_cnt').text(cart_cnt);

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

function get_item(){
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_item",
			product_no: product_no
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				var html = '';
				var img_list = '';
				if(data.data && data.data.length > 0){
					$.each(data.data, function(i, v){
						if(i == 0){
							if("<?=$r_admin ?>" == "1" || v.is_view == "1"){
								$("#item_product_page .content_box .product_comment").text(v.product_comment);
								$("#item_product_page .content_box .product_name").text(v.product_name);
								$("#item_product_page .content_detail .content_text").html(v.product_detail);
								$("#item_product_page #cart input[name='is_special_mall']").val(v.is_shop);
								is_supply = (v.is_supply && v.is_supply != "")? v.is_supply : "2";
								ip_seq = (v.ip_seq && v.ip_seq != "")? v.ip_seq : "";
								supplier = (v.supplier && v.supplier != "")? v.supplier : "";
								var goodsNo = (v.goodsNo && v.goodsNo != "")? v.goodsNo : "";
								img_list = v.product_img;

								if(v.is_soldout == "2"){ // 품절
									html += '<div class="right"><span class="line-through">품절</div>';
									$("#item_product_page .content_box .item_option_wrap").html(html);
									$("#item_product_page #item_option").html("");
									$("#item_product_page #content_review").remove();
									$("#item_product_page .content_detail .content_review").remove();
									$("#item_product_page .buy_cart").remove();
									$("#item_product_page .buy_go").remove();
								}else{
									if(v.is_supply == "1" && v.supplier == "정글북"){
										get_jbook_list("goods", v.goodsNo); // 재고체크
									}
									if(v.is_use_option == "1" || v.is_use_group_option == "1"){ // 옵션상품
										if(v.is_use_option == "1"){ // 옵션상품
											item_option(v.il_seq, is_supply, supplier, goodsNo);
										}else if(v.is_use_group_option == "1"){
											item_group_option(v);
										}

									}else{ // 단품
										cart = [];
										cart.push({"seq" : "", "value" : v.sale_price, "amount" : 1, "is_free_shipping" : v.is_free_shipping, "txt" : v.product_name, "il_seq" : v.il_seq, "is_supply" : is_supply, "supplier" : supplier, "goodsNo" : goodsNo});
										
										html += '<div class="right"><span class="line-through">'+v.product_price.format()+'원</span> &gt; '+v.sale_price.format()+'원</div>';
										if(v.is_free_shipping == "1"){
											html += '<div class="charge_line">배송비 무료</div>';
										}else{
											html += '<div class="charge_line">배송비 : '+shipping_price.format()+'원</div>';
										}
										$("#item_product_page .content_box .item_option_wrap").html(html);

										html = '';
										html += '<ul class="table select_line" data-no="1">';
										html += '	<li>'+v.product_name+'</li>';
										html += '	<li style="vertical-align: middle; height: 30px; text-align: right;">';
										html += '		<a href="javascript:;" class="cart_plus_btn"><img src="../images/btn/item_plus.png" class="btn_set"></a>';
										html += '		<input class="item_number cart_amount" type="text" value="1" readonly />';
										html += '		<a href="javascript:;" class="cart_minus_btn"><img src="../images/btn/item_minus.png" class="btn_set"></a>';
										//html += '		<a href="javascript:;" class="cart_delete_btn"><img src="../images/btn/item_close.png" class="btn_set"></a>'; // 단품시 삭제 불가
										html += '	</li>';
										html += '</ul>';

										$("#item_product_page .total span").html(v.sale_price.format()+"원");
										$("#item_product_page #cart input[name='total_price']").val(v.sale_price);
										$("#item_product_page #cart input[name='is_special_mall']").val(v.is_shop);
										$("#item_product_page .cart_wrap").html(html);
									}
									
									get_item_event(v.il_seq);
									get_item_review(product_no);
								}

								get_file_list(img_list, v.goodsRepImage); // main_image
							}else{
								$.MessageBox("해당 상품은 판매하지 않는 상품입니다.");
								// no data
								$("#item_product_page .content_box .item_option_wrap").html("");
								$("#item_product_page #item_option").html("");
								$("#item_product_page .content_detail .content_title").remove();
								$("#item_product_page .content_detail .content_text").remove();
								$("#item_product_page .content_detail .content_review").remove();
								$("#item_product_page .buy_cart").remove();
								$("#item_product_page .buy_go").remove();
								return false;
							}
						}
					});
				}else{
					$.MessageBox("해당 상품은 판매하지 않는 상품입니다.");
					// no data
					$("#item_product_page .content_box .item_option_wrap").html("");
					$("#item_product_page #item_option").html("");
					$("#item_product_page .content_detail .content_title").remove();
					$("#item_product_page .content_detail .content_text").remove();
					$("#item_product_page .content_detail .content_review").remove();
					$("#item_product_page .buy_cart").remove();
					$("#item_product_page .buy_go").remove();
					return false;
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
				if(data.data && parseInt(data.data.total) > 0){
					$.each(data.data.data, function(i, v){
						if(v.runout == "1"){ // 품절
							set_update_item_soldout(option); // 재고없음 처리
						}
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

// 상품 재고 없음 처리
function set_update_item_soldout(goodsNo){
	if(goodsNo != ""){
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "set_update_item_soldout",
				goodsNo: goodsNo
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					html += '<div class="right"><span class="line-through">품절</div>';
					$("#item_product_page .content_box .item_option_wrap").html(html);
					$("#item_product_page #item_option").html("");
					$("#item_product_page #content_review").remove();
					$("#item_product_page .content_detail .content_review").remove();
					$("#item_product_page .buy_cart").remove();
					$("#item_product_page .buy_go").remove();
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

function item_group_option(item_data){
	console.log("item_group_option", item_data);
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_item_group_option_list",
			product_no : product_no
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				var html = '';

				html += '<div class="right"><span class="line-through">'+item_data.product_price.format()+'원</span> &gt; '+item_data.sale_price.format()+'원</div>';
				if(item_data.is_free_shipping == "1"){
					html += '<div class="charge_line">배송비 무료</div>';
				}else{
					html += '<div class="charge_line">배송비 : '+shipping_price.format()+'원</div>';
				}
				$("#item_product_page .content_box .item_option_wrap").html(html);

				if(data.data && data.data.length > 0){
					html += '<div class="item_group_option">';
					$.each(data.data, function(i, v){
						html += '	<div class="item_group_option_wrap">';
						html += '		<div class="title">'+v.group_name+'</div>';
						html += '		<div class="group_option" data-id="'+v.igo_seq+'">';
						html += '		</div>';
						html += '	</div>';
					});
					html += '	<div class="btn_wrap">';
					html += '		<button type="button" class="group_option_select_btn" data-il_seq="'+item_data.il_seq+'" data-product_name="'+item_data.product_name+'" data-sale_price="'+item_data.sale_price+'" data-is_supply="'+item_data.is_supply+'" data-supplier="'+item_data.supplier+'" data-goodsNo="'+item_data.goodsNo+'" data-is_free_shipping="'+item_data.is_free_shipping+'">옵션 선택</button>';
					html += '	</div>';
					html += '</div>';
				}
				$("#item_product_page .content_box .item_option_wrap").html(html);

				if(data.data && data.data.length > 0){
					group_option = [];
					$.each(data.data, function(i, v){
						item_group_option_detail(v.igo_seq, v.kind, i);
						group_option.push({
							igod_seq: "",
							option_name: "",
							sale_price: 0
						});
					});
				}
			
			}else{
				alert(data.data+"("+data.code+")");
				console.log(data.code);
			}
		},
		error: function(xhr, status, error) {
			alert(error + "네트워크에러");
		}
	});
}

function item_group_option_init(){
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_item_group_option_list",
			product_no : product_no
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				var html = '';

				if(data.data && data.data.length > 0){
					group_option = [];
					$.each(data.data, function(i, v){
						item_group_option_detail(v.igo_seq, v.kind, i);
						group_option.push({
							igod_seq: "",
							option_name: "",
							sale_price: 0
						});
					});
				}
			
			}else{
				alert(data.data+"("+data.code+")");
				console.log(data.code);
			}
		},
		error: function(xhr, status, error) {
			alert(error + "네트워크에러");
		}
	});
}

$("#item_product_page .content_box .item_option_wrap").on("change", ".select_group_option_btn", function(){
	var _this = $(this);
	var option_no = _this.data('option_no');

	group_option[option_no].igod_seq = _this.val();
	group_option[option_no].option_name = _this.children("option:selected").data("text");
	group_option[option_no].plus_price = _this.children("option:selected").data("price");
	console.log(group_option);
});

$("#item_product_page .content_box .item_option_wrap").on("click", ".choice_group_option_btn", function(){
	var _this = $(this);
	var igo_seq = _this.parent().parent().data('id');
	var option_no = _this.data('option_no');
	if(_this.is(":checked") == true){
		group_option[option_no].igod_seq = _this.val();
		group_option[option_no].option_name = _this.next("label").find(".option_name").data("text");
		group_option[option_no].plus_price = _this.next("label").find(".option_name").data("price");
		$("#item_product_page .content_box .item_option_wrap .group_option_detail2[data-id='"+igo_seq+"'] .group_option_txt_"+option_no).val(_this.next("label").find(".option_name").text());
	}
	console.log(group_option);
});

$("#item_product_page .content_box .item_option_wrap").on("click", ".group_option_select_btn", function(){
	var _this = $(this);
	var html = '';
	var _price = _this.data('sale_price');
	var _amount = 1;
	var _is_free_shipping = _this.data('is_free_shipping');
	var _il_seq = _this.data('il_seq');
	var _product_name = _this.data('product_name');
	var _is_supply = _this.data('_is_supply');
	var _supplier =_this.data('_supplier');
	var _goodsNo = _this.data('_goodsNo');
	var _igod_seq = '';
	var igod_seq_arr = [];
	var igod_name_arr = [];
	var _total_price = 0;
	var flag = true;

	$.each(group_option, function(i, v){
		if(v.igod_seq == ""){
			$.MessageBox("필수 옵션을 선택해주세요.");
			flag = false;
			return false;
		}
		_price += parseInt(v.plus_price);
		igod_name_arr.push(v.option_name);
		igod_seq_arr.push(v.igod_seq);
	});
	_igod_seq = igod_seq_arr.join(',');
	_product_name += (igod_name_arr.length > 0)? ' ('+igod_name_arr.join('/')+')' : '';

	var is_duplicate = 0;
	$.each(cart, function(i, v){
		if(v.igod_seq == _igod_seq){
			is_duplicate = 1;
		}
	});

	if(flag == true){
		if(is_duplicate == 0){
			cart.push({"seq" : "", "value" : _price, "amount" : _amount, "is_free_shipping" : _is_free_shipping, "txt" : _product_name, "il_seq" : _il_seq, "is_supply" : _is_supply, "supplier" : _supplier, "goodsNo" : _goodsNo, "igod_seq" : _igod_seq });
			console.log(cart);

			html = '';
			$.each(cart, function(i, v){
				html += '<ul class="table select_line" data-no="'+(i+1)+'">';
				html += '	<li>'+v.txt+'</li>';
				html += '	<li style="vertical-align: middle; height: 30px; text-align: right;">';
				html += '		<a href="javascript:;" class="cart_plus_btn"><img src="../images/btn/item_plus.png" class="btn_set"></a>';
				html += '		<input class="item_number cart_amount" type="text" value="'+v.amount+'" readonly />';
				html += '		<a href="javascript:;" class="cart_minus_btn"><img src="../images/btn/item_minus.png" class="btn_set"></a>';
				html += '		<a href="javascript:;" class="cart_delete_btn"><img src="../images/btn/item_close.png" class="btn_set"></a>'; // 단품시 삭제 불가
				html += '	</li>';
				html += '</ul>';
				_total_price += parseInt(v.value) * parseInt(v.amount);
			});
			
			$("#item_product_page .total span").html(_total_price.format()+"원");
			$("#item_product_page #cart input[name='total_price']").val(_total_price);
			$("#item_product_page .cart_wrap").html(html);

			item_group_option_init();
		}else{
			$.MessageBox("이미 입력한 옵션입니다.");
		}
	}
});

function item_group_option_detail(igo_seq, kind, option_no){
	console.log("item_group_option_detail");
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_item_group_option_detail_list",
			product_no : product_no,
			igo_seq : igo_seq
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				var html = '';

				if(data.data && data.data.length > 0){
					if(kind == "1"){
						html += '<ul>';
						html += '	<li class="group_option_detail" data-id="'+igo_seq+'">';
						html += '		<select class="select_group_option_btn group_option_'+option_no+'" data-option_no="'+option_no+'">';
						html += '			<option value="">선택</option>';
						$.each(data.data, function(i, v){
							var _math_symbol = (v.plus_price && v.plus_price > 0)? '+' : '-';
							var _plus_price = (v.plus_price && v.plus_price > 0)? '['+_math_symbol+''+v.plus_price.format()+'원]' : '';
							html += '			<option value="'+v.igod_seq+'" data-text="'+v.option_name+'" data-price="'+v.plus_price+'">'+v.option_name+' '+_plus_price+'</option>';
						});
						html += '		</select>';
						html += '	</li>';
						html += '</ul>';
					}else if(kind == "2"){
						html += '<div class="group_option_detail2" data-id="'+igo_seq+'">';
						html += '	<div class="right_menu">';
						html += '		<input type="text" class="group_option_txt_'+option_no+'" value="" readonly />';
						html += '	</div>';
						$.each(data.data, function(i, v){
							var _plus_price = (v.plus_price && v.plus_price > 0)? '['+v.plus_price.format()+'원]' : '';
							html += '	<div class="flex-item" data-id="'+v.igod_seq+'">';
							html += '		<input type="radio" id="group_option_detail_'+v.igod_seq+'" name="group_option_detail_'+option_no+'" data-option_no="'+option_no+'" class="choice_group_option_btn" value="'+v.igod_seq+'">';
							html += '		<label for="group_option_detail_'+v.igod_seq+'">';
							html += '			<div class="image"></div>';
							html += '			<div class="option_name" data-text="'+v.option_name+'" data-price="'+v.plus_price+'">'+v.option_name+' '+_plus_price+'</div>';
							html += '		</label>';
							html += '	</div>';
						});
						if(data.data.length % 6 != 0){
							for(var _i = 0; _i < 6 - data.data.length % 6; _i++){
								html += '	<div class="flex-item no_data">';
								html += '	</div>';
							}
						}
						html += '</div>';
					}
				}
				$("#item_product_page .content_box .item_option_wrap .item_group_option .group_option[data-id='"+igo_seq+"'] ").html(html);
				
				if(kind == "2"){
					if(data.data && data.data.length > 0){
						$.each(data.data, function(i, v){
							var _image = (v.image && v.image != "")? ((v.image.indexOf(',') != false)? v.image.split(',') : [v.image]) : [];
							get_file_list_option(_image, igo_seq, v.igod_seq);
						});
					}
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

function get_file_list_option(img_list, igo_seq, igod_seq){
	if(img_list != ""){
		console.log(img_list);
		img_list = img_list.filter(function(item) {
		return item !== null && item !== undefined && item !== '';
		});
		var tmp_img_list = img_list.join(',');

		$.ajax({
			url: '<?=$mainpage_directory?>/fileupload_ajax.php',
			data: {
				mode: "get_file_list",
				file_list: tmp_img_list
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					var html = '';
					var idx = 0;
					$.each(data.data, function(i, v){
						if(i == 0){ // only 1
							$('#item_product_page .content_box .item_option_wrap .item_group_option .group_option_detail2[data-id="'+igo_seq+'"] .flex-item[data-id="'+igod_seq+'"] .image').css('background-image', 'url("'+v.file_path+'")');
						}
					});
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
}

function item_option(il_seq, is_supply, supplier, goodsNo){
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
				var select_html = '<option value="">선택</option>';
				$.each(data.data, function(i, v){
					var price_val = (v.is_soldout == "2")? "soldout" : v.sale_price;
					var price_txt = (v.is_soldout == "2")? "품절" : v.sale_price.format()+"원";
					var price_view_txt = (v.is_soldout == "2")? "[품절]" : '<span class="line-through">'+v.option_price.format()+'원</span> &gt; '+v.sale_price.format()+'원';
					var free_shippping_txt = (v.is_free_shipping == "1")? "[무료배송]" : "";

					html += '<div class="right">'+v.option_name+' '+price_view_txt+'</div>';
					select_html += '<option value="'+price_val+'" data-option_seq="'+v.io_seq+'" data-free_shipping="'+v.is_free_shipping+'" data-option_txt="'+v.option_name+'" data-is_supply="'+is_supply+'" data-supplier="'+supplier+'" data-goodsNo="'+goodsNo+'">'+free_shippping_txt+''+v.option_name+' '+price_txt+'</option>';
				});
				html += '<div class="charge_line">배송비 : '+shipping_price.format()+'원</div>';
				html += '<div class="padd20_cen">';
				html += '	<span>옵션 :</span>';
				html += '	<select id="item_option">';
				html += '	</select>';
				html += '</div>';
				$("#item_product_page .content_box .item_option_wrap").html(html);
				$("#item_product_page #item_option").html(select_html);
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

function get_file_list(img_list, goodsRepImage){
	if(img_list != ""){
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
						html += '<div class="swiper-slide"><img src="'+v.file_path+'"></div>';
					});

					$("#item_product_page .swiper-wrapper").html(html);
					swiper.update();
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
		if(goodsRepImage && goodsRepImage != ""){
			html += '<div class="swiper-slide"><img src="'+goodsRepImage+'"></div>';
		}else{
			html += '<div class="swiper-slide"><img src="../images/product_img.png"></div>';
		}
		$("#item_product_page .swiper-wrapper").html(html);
		swiper.update();
	}
}

function get_item_review(product_no){
	console.log(product_no);
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_item_review",
			product_no : product_no
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				var html = '';
				var sum_rating = 0;

				if(data.data.length > 0){
					$.each(data.data, function(i, v){
						var photo = (v.photo && v.photo != "")? v.photo : "<?=$image_directory ?>/who5.png";
						var review = (v.review && v.review != "")? v.review.replace(/(?:\r\n|\r|\n)/g, "<br />") : "";
						var reply = (v.reply && v.reply != "")? v.reply.replace(/(?:\r\n|\r|\n)/g, "<br />") : "";
						var nickname = (v.name && v.name != "")? v.name : v.nickname;
						var num = v.ir_seq;
					// 아이디 가리기
						var seq_cnt = (num % 5) + 1; // *처리시 ir_seq 기준으로 고정 * 갯수
						var blur_cnt = nickname.length - 5;
						var random_cnt = Math.floor(Math.random() * 5) + 2 ; // *갯수 랜덤 -> 사용안함
						var blur_text = "**"
						var cnt = 0;
//						while(cnt < blur_cnt){
//							blur_text +="*"
//							cnt++;
//						}
						while(cnt < seq_cnt){
							blur_text +="*"
							cnt++;
						}
						if(nickname.length < 6 && nickname != ""){
							nickname = nickname.substring(0, nickname.length - 2) + "***";
						}else{
							if(seq_cnt >= 3){
								nickname = nickname.substring(0, 4) + blur_text;
							}else if(seq_cnt < 3){
								nickname = nickname.substring(0, 3) + blur_text + "*";
							}
						}
//						nickname = (nickname != "" && nickname.length > 6)? nickname.substring(0, 4) + blur_text : nickname;
					// 아이디 가리기 끝
						var reg_dt = new Date(v.reg_dt.replace(/-/g, '/')); // ios cross browsing
						reg_dt = reg_dt.getFullYear()+'-'+fillZero(2, (reg_dt.getMonth()+1))+'-'+fillZero(2, reg_dt.getDate());
						var reply_dt = (v.reply_dt && v.reply_dt != "")? new Date(v.reply_dt.replace(/-/g, '/')) : new Date(); // ios cross browsing
						reply_dt = reply_dt.getFullYear()+'-'+fillZero(2, (reply_dt.getMonth()+1))+'-'+fillZero(2, reply_dt.getDate());
						if(v.is_admin == "1"){
							var pay_data = v.product_option_txt;
						}else{
							/*
							let _option_data = $.parseJSON(v.p_data.replace(/\\/g, ''));
							$.each(_option_data, function(i2, v2){
								var pay_data = JSON.parse(v2);
								$.each(pay_data, function(i3, v3){
									if(i3 == 0){
										pay_data = v.product_name+", "+v3.txt+", "+v3.amount+"개";
									}
								});
							});
							*/
							pay_data = v.product_name;
						}
						html += '<div class="customer_review" data-id="'+v.ir_seq+'">';
						if("<?=$user_id ?>" != v.customer_id && v.is_blind == "1"){
							html += '	<div class="img" style="background-image: url(\''+photo+'\')"></div>';
							html += '	<ul>';
							html += '		<li>'+nickname+'</li>';
							html += '		<li></li>';
							html += '		<li>';
							html += '			<div class="review">';
							html += '				<div class="product">'+pay_data+'</div>';
							html += '				<div class="blind"><i class="fas fa-lock"></i> 판매자에게만 보이는 리뷰입니다.</div>';
							html += '			</div>';
							html += '		</li>';
							html += '		<li class="review_dt">'+reg_dt+'</li>';
							html += '	</ul>';
							if(v.is_reply == "1"){
								html += '	<ul>';
								html += '		<li>';
								html += '			<div class="reply">';
								html += '				<div class="reply_icon"><i class="fas fa-reply"></i></div>';
								html += '				<div class="blind"><i class="fas fa-lock"></i> 리뷰 작성자에게만 보이는 리뷰입니다.</div>';
								html += '			</div>';
								html += '		</li>';
								html += '		<li class="reply_dt">'+reply_dt+'</li>';
								html += '	</ul>';
							}
						}else{
							html += '	<div class="img" style="background-image: url(\''+photo+'\')"></div>';
							html += '	<ul>';
							html += '		<li>'+nickname+'</li>';
							html += '		<li>';
							html += '			<div class="star-rating"><span class="star">';
							for(var _i = 1; _i <= 5; _i++){
								html += '<i class="fas fa-star"></i>';
								if(_i == v.rating){
									html += '</span>';
								}
							}
							html += '			</div>';
							html += '		</li>';
							html += '		<li>';
							html += '			<div class="review">';
							html += '				<div class="product">'+pay_data+'</div>';
							html += '				<div>'+review+'</div>';
							if(v.review_image != ""){
								html += '				<div class="review_image_wrap"></div>';
							}
							html += '			</div>';
							html += '		</li>';
							html += '		<li class="review_dt">'+reg_dt+'</li>';
							html += '	</ul>';
							if(v.is_reply == "1"){
								html += '	<ul>';
								html += '		<li>';
								html += '			<div class="reply">';
								html += '				<div class="reply_icon"><i class="fas fa-reply"></i></div>';
								html += '				<div>'+reply+'</div>';
								html += '			</div>';
								html += '		</li>';
								html += '		<li class="reply_dt">'+reply_dt+'</li>';
								html += '	</ul>';
							}
							if(v.review_image != ""){
								var review_img_list = [];
								var review_image = v.review_image.split(',');
								$.each(review_image, function(i2, v2){
									if(v2 != ""){
										review_img_list.push(v2);
									}
								});
								get_item_review_image(v.ir_seq, review_img_list.join(','));
							}
						}

						sum_rating += parseInt(v.rating);
						html += '</div>';
					});
				}else{
						html += '<div class="customer_review">';
						html += '	<div class="no_review">첫 구매후기를 남겨주세요.</div>';
						html += '</div>';
				}

				$("#item_product_page .content_detail .content_review").html(html);
				if(sum_rating != 0 && data.data.length != 0){
					$("#item_product_page .product_review").html('<span class="star"><i class="fas fa-star"></i></span> '+(Math.round(sum_rating / data.data.length * 10)/10)+' / '+data.data.length+'&nbsp;&nbsp;<i class="fas fa-chevron-right"></i>');
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

function get_item_review_image(target, img_list){
	// img_loading
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
					html += '<a data-fancybox="gallery_'+target+'" href="'+v.file_path+'"><div class="review_image" style="background-image: url(\''+v.file_path+'\')"></div></a>';
				});

				$("#item_product_page .content_detail .content_review .customer_review[data-id='"+target+"'] .review_image_wrap").html("").html(html);
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

function get_item_event(il_seq){
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_item_event",
			il_seq : il_seq
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				//console.log(data.data);
				var html = '';
				var cnt = 0;

				$.each(data.data, function(i, v){ // 이벤트 달성 기준 카운트
					if(v.is_use == '1'){
						cnt++;
					}
				});

				$.each(data.data, function(i, v){
					var end_dt = new Date(v.end_dt.replace(/-/g, '/'));
					var date_diff = Math.round(day_diff(end_dt, new Date()));
					if(end_dt.getTime() < new Date().getTime()){ // 이벤트시간 마감시 0으로 변환
						date_diff = 0;
					}
					if(v.is_use == '1'){
						html = '';
						html += '<div class="event event_'+v.ie_seq+'">';
						html += '	<div class="event_top">';
						html += '		<div class="event_title">'+v.title+'</div>';
						html += '	</div>';
						html += '	<div class="event_progress">';
						html += '		<div class="now_cnt">0건</div>';
						html += '		<div class="end_cnt">'+v.total_cnt+'건</div>';
						html += '		<div class="progress_bar">';
						html += '			<div class="ui-progressbar-value"></div>';
						html += '		</div>';
						html += '		<div class="end_dt">'+date_diff+'일 남음</div>';
						html += '	</div>';
						html += '</div>';
						
						$("#item_product_page .content_box").after(html);
						get_now_cnt(v.ie_seq, v.il_seq, v.start_dt, v.end_dt, v.plus_cnt, v.total_cnt, cnt);
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
}

function get_now_cnt(ie_seq, il_seq, start_dt, end_dt, plus_cnt, total_cnt, count){
	console.log(product_no, start_dt, end_dt);
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_now_cnt",
			product_no : product_no,
			start_dt : start_dt,
			end_dt : end_dt				
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				let _width = 0;
				let _now_cnt = (data.data && data.data != "")? parseInt(data.data) + parseInt(plus_cnt) : parseInt(plus_cnt);
				if(_now_cnt < 0){
					_now_cnt = 0;
				}else if(_now_cnt >= total_cnt){
					_now_cnt = total_cnt;
				}

				$("#item_product_page .event_"+ie_seq+" .now_cnt").text(_now_cnt.format()+"건 주문");

				if(parseInt(total_cnt) <= parseInt(data.data)){ // 총수량보다 많은 주문이 있을 경우
					_width = total_cnt;
					event_complete++;
					$("#item_product_page .event_"+ie_seq+" .now_cnt").text("완판 주문마감");
					$("#item_product_page .event_"+ie_seq+" .now_cnt").css("color", "#FF4546");
					$("#item_product_page .event_"+ie_seq+" .ui-progressbar-value").css("background-color", "#FF4546");
					$("#item_product_page .event_"+ie_seq+" .end_dt").css("color", "#FF4546");
				}else{
					_width = Math.round(_now_cnt / parseInt(total_cnt) * 100);
					if(_width >= 100){
						_width = 100;
						event_complete++;
						$("#item_product_page .event_"+ie_seq+" .now_cnt").text("완판 주문마감");
						$("#item_product_page .event_"+ie_seq+" .now_cnt").css("color", "#FF4546");
						$("#item_product_page .event_"+ie_seq+" .ui-progressbar-value").css("background-color", "#FF4546");
						$("#item_product_page .event_"+ie_seq+" .end_dt").css("color", "#FF4546");
					}else if(_width < 0){
						_width = 0;
					}
				}

				$("#item_product_page .event_"+ie_seq+" .ui-progressbar-value").css("width", _width+"%");
			
				if(event_complete == parseInt(count)){ // 이벤트 달성 체크
					$("#item_product_page .buy_cart").text("이벤트 종료");
					$("#item_product_page .buy_cart").addClass("disabled");
					$("#item_product_page .buy_go").text("이벤트 종료");
					$("#item_product_page .buy_go").addClass("disabled");
				}
			}else if(data.code == "004501"){ // 날짜오류
				$("#item_product_page .event_"+ie_seq+" .now_cnt").text("0건 주문");
				$("#item_product_page .event_"+ie_seq+" .now_cnt").css("color", "#f00");
				$("#item_product_page .event_"+ie_seq+" .ui-progressbar-value").css("width", "0%");
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

$(document).on("change", "#item_product_page #item_option", function(){
	var value = $(this).children("option:selected").val();
	var seq = $(this).children("option:selected").data("option_seq");
	var txt = $(this).children("option:selected").data("option_txt");
	var is_free_shipping = $(this).children("option:selected").data("free_shipping");
	var is_free_shipping_txt = (is_free_shipping == "1")? "[무료배송] " : "";
	is_supply = $(this).children("option:selected").data("is_supply");
	supplier = $(this).children("option:selected").data("supplier");
	var goodsNo = $(this).children("option:selected").data("goodsNo");
	var html = '';

	if(value == "soldout" || value == ""){
		$.MessageBox("품절 상품입니다. 다른 상품을 선택해주세요.");
		$(this).val('');
	}else{
		cart.push({"seq" : seq, "value" : value, "amount" : 1, "is_free_shipping" : is_free_shipping, "txt" : txt, "il_seq" : "", "is_supply" : is_supply, "supplier" : supplier, "goodsNo" : goodsNo});
		console.log(cart);

		html += '<ul class="table select_line" data-no="'+cart.length+'">';
		html += '	<li style="font-size:14px;padding: 5px;">'+is_free_shipping_txt+''+txt+'</li>';
		html += '	<li>';
		html += '		<a href="javascript:;" class="cart_plus_btn"><img src="../images/btn/item_plus.png" class="btn_set"></a>';
		html += '		<input class="item_number cart_amount" type="text" value="1" readonly />';
		html += '		<a href="javascript:;" class="cart_minus_btn"><img src="../images/btn/item_minus.png" class="btn_set"></a>';
		html += '		<a href="javascript:;" class="cart_delete_btn"><img src="../images/btn/item_close.png" class="btn_set"></a>';
		html += '	</li>';
		html += '</ul>';
		$("#item_product_page .cart_wrap").append(html);
		$(this).val('');

		var total_price = 0;
		$.each(cart, function(i, v){
			total_price += parseInt(v.value * v.amount);
		});

		$("#item_product_page .total span").html('').html(total_price.format()+"원");
		$("#item_product_page #cart input[name='total_price']").val(total_price);
	}
});

$(document).on("click", "#item_product_page .cart_plus_btn", function(){
	var cart_no = $(this).parent().parent().closest(".select_line").data("no");
	var amount = parseInt($(this).siblings("input.cart_amount").val());
	amount += 1;
	if(amount > 99){
		amount = 99;
	}
	$(this).siblings("input.cart_amount").val(amount);
	cart[cart_no - 1].amount = amount;

	var total_price = 0;
	$.each(cart, function(i, v){
		total_price += parseInt(v.value * v.amount);
	});

	$("#item_product_page .total span").html('').html(total_price.format()+"원");
	$("#item_product_page #cart input[name='total_price']").val(total_price);
});

$(document).on("click", "#item_product_page .cart_minus_btn", function(){
	var cart_no = $(this).parent().parent().closest(".select_line").data("no");
	var amount = parseInt($(this).siblings("input.cart_amount").val());
	amount -= 1;
	if(amount <= 0){
		amount = 1;
	}
	$(this).siblings("input.cart_amount").val(amount);
	cart[cart_no - 1].amount = amount;

	var total_price = 0;
	$.each(cart, function(i, v){
		total_price += parseInt(v.value * v.amount);
	});

	$("#item_product_page .total span").html('').html(total_price.format()+"원");
	$("#item_product_page #cart input[name='total_price']").val(total_price);
});

$(document).on("click", "#item_product_page .cart_delete_btn", function(){
	var cart_no = $(this).parent().parent().closest(".select_line").data("no");
	var html = '';
	$.each(cart, function(i, v){
		if(i == (cart_no - 1)){
			cart.splice(i, 1);
		}
	});
	console.log(cart);

	$.each(cart, function(i, v){
		var is_free_shipping_txt = (v.is_free_shipping == "1")? "[무료배송] " : "";
		html += '<ul class="table select_line" data-no="'+(i+1)+'">';
		html += '	<li style="font-size:14px;padding: 5px;">'+is_free_shipping_txt+''+v.txt+'</li>';
		html += '	<li>';
		html += '		<a href="javascript:;" class="cart_plus_btn"><img src="../images/btn/item_plus.png" class="btn_set"></a>';
		html += '		<input class="item_number cart_amount" type="text" value="'+v.amount+'" readonly />';
		html += '		<a href="javascript:;" class="cart_minus_btn"><img src="../images/btn/item_minus.png" class="btn_set"></a>';
		html += '		<a href="javascript:;" class="cart_delete_btn"><img src="../images/btn/item_close.png" class="btn_set"></a>';
		html += '	</li>';
		html += '</ul>';
	});
	$("#item_product_page .cart_wrap").html("").append(html);

	var total_price = 0;
	$.each(cart, function(i, v){
		total_price += parseInt(v.value * v.amount);
	});

	$("#item_product_page .total span").html('').html(total_price.format()+"원");
	$("#item_product_page #cart input[name='total_price']").val(total_price);
});

function get_cart_list(){
	return new Promise(function(resolve, reject) {
		console.log("(1) get_cart_list ");
		$.ajax({ // (1)
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "get_cart",
				is_session: "1"
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);

					var returnData = {cart_list_data: data.data};
					resolve(returnData);
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

function get_item_option(param){
	return new Promise(function(resolve, reject) {
		console.log("(2-0) get_item_option - " , param);
		$.ajax({ // (1)
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "get_item_option",
				io_seq: param.io_seq
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					var product_no = "";
					
					$.each(data.data, function(i, v){
						product_no = v.product_no;
					});
					
					var returnData = {il_seq: '', product_no: product_no, cart_list_data: param.cart_list_data};
					resolve(returnData);
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

function get_item_list(param){
	return new Promise(function(resolve, reject) {
		console.log("(2-0) get_item_list - " , param);
		$.ajax({ // (1)
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "get_item",
				il_seq: param.il_seq,
				product_no: param.product_no
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					
					var returnData = {item_data: data.data, cart_list_data: param.cart_list_data};
					resolve(returnData);
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

function set_delete_item_cart(param){
	return new Promise(function(resolve, reject) {
		console.log("(2-1) set_delete_item_cart - " , param);
		var total_price = $("#item_product_page #cart input[name='total_price']").val();
		var is_special_mall = $("#item_product_page #cart input[name='is_special_mall']").val();
		var user_id = "<?=$user_id ?>";

		$.each(param.item_data, function(i, v){
			console.log(v.is_shop, is_special_mall);
			if(v.is_shop != is_special_mall){ // (2-1)
				console.log(v, param.cart_list_data);
				$.each(param.cart_list_data, function(i2, v2){
					$.ajax({ // (2-1)
						url: '<?=$item_directory ?>/item_list_ajax.php',
						data: {
							mode : "set_delete_item_cart",
							ic_seq: v2.ic_seq,
							user_id: user_id,
							delete_txt: "반짝몰or전문몰 상품 충돌 삭제"
						},
						type: 'POST',
						dataType: 'JSON',
						success: function(data) {
							if(data.code == "000000"){
								console.log(data.data);

								var returnData = {cart_list_data: {}};
								resolve(returnData);
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
			}else{
				var returnData = {cart_list_data: param.cart_list_data};
				resolve(returnData);
			}
		});

	});
}

function get_special_mall_chk(param){
	return new Promise(function(resolve, reject) {
		console.log("(2) get_special_mall_chk - " , param);
		var total_price = $("#item_product_page #cart input[name='total_price']").val();
		var user_id = "<?=$user_id ?>";

		// (2)
		if(param.cart_list_data && param.cart_list_data.length > 0){
			var p = $.when();
			var c = 0;
			$.each(param.cart_list_data, function(i, v){
				p = p.then(function(){
					var cart_data = JSON.parse(v.cart_data);
					$.each(cart_data, function(i2, v2){
						if(v2.seq != ""){ // 단일/옵션상품 여부
							// item_option > item_list > is_shop
							console.log("optn---------------------------");
							var returnData = {io_seq: v2.seq, cart_list_data: param.cart_list_data};
							return get_item_option(returnData)
								.then(get_item_list)
								.then(set_delete_item_cart);
								//.then(get_cart_item_duplicate_chk);
						}else{
							// item_list > is_shop
							console.log("dan1---------------------------");
							var returnData = {il_seq: v2.il_seq, product_no: '', cart_list_data: param.cart_list_data};
							return get_item_list(returnData)
								.then(set_delete_item_cart);
								//.then(get_cart_item_duplicate_chk);
						}
					});

					c++;
				}).done(function(){
					console.log("### done");
					if(c == Object.keys(param.cart_list_data).length){
						console.log("### done - end");
						resolve(param);
					}
				});
			});
			/*
			$.each(param.cart_list_data, function(i, v){
				// 상품데이터 호출 - 반복 끝나면 resolve() 호출
				var cart_data = JSON.parse(v.cart_data);
				$.each(cart_data, function(i2, v2){
					console.log(v2);
					if(v2.seq != ""){ // 단일/옵션상품 여부
						// item_option > item_list > is_shop
						console.log("optn---------------------------");
						var returnData = {io_seq: v2.seq, cart_list_data: param.cart_list_data};
						get_item_option(returnData)
							.then(get_item_list)
							.then(set_delete_item_cart);
							//.then(get_cart_item_duplicate_chk);
					}else{
						// item_list > is_shop
						console.log("dan1---------------------------");
						var returnData = {il_seq: v2.il_seq, product_no: '', cart_list_data: param.cart_list_data};
						get_item_list(returnData)
							.then(set_delete_item_cart);
							//.then(get_cart_item_duplicate_chk);

					}
				});

			});
			*/
		}else{
			// 장바구니 비어있음
			// => 장바구니 추가 (3)에서 추가하므로 중복 제거
			//set_insert_cart(total_price, user_id);
			resolve(param);
		}
	});
}

function get_cart_item_duplicate_chk(param){ // (3)
	return new Promise(function(resolve, reject) {
		console.log("(3) get_cart_item_duplicate_chk - " , param);
		var total_price = $("#item_product_page #cart input[name='total_price']").val();
		var is_special_mall = $("#item_product_page #cart input[name='is_special_mall']").val();
		var user_id = "<?=$user_id ?>";

		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "get_cart",
				is_session: "1"
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);

					if(data.data && data.data.length > 0){
						// 장바구니 호출
						$.each(data.data, function(i, v){
							var cart_data = JSON.parse(v.cart_data);
							var cart_flag = 0;
							$.each(cart_data, function(i2, v2){
								// 리스트 호출
								console.log(v2.il_seq, v2.amount);
								$.each(cart, function(i3, v3){
									console.log(v3.il_seq, v3.il_seq);
									if(v2.il_seq == v3.il_seq){
										// 중복상품
										console.log("!!");
										//console.log(cart_data[i2]);
										cart_data[i2].amount += v3.amount; // 수량증가
										cart.splice(i3, 1);// 카트에서 제외
										cart_flag++;
									}else{
										// 중복상품 아님
										console.log("@@");
									}
								});
							});
							console.log(cart_data);
							console.log(v.ic_seq);

							if(cart_flag > 0){
								console.log("수량추가");
								set_update_cart_amount(v.ic_seq, cart_data);
							}
						});
						if(Object.keys(cart).length > 0){
							// 남은 제품 장바구니 제품 추가
							console.log(cart);
							console.log("제품추가");
							set_insert_cart(total_price, user_id, is_special_mall);
						}else{
							// 처리완료 메시지
							if(checkMobile2() == "in_app_ios"){
								setCookie_ios("order_num", data.data, 1);
								setCookie_ios("order_step", "3", 1); // 1 - 구매, 2 - 조회, 3 - 장바구니
								if(is_shop == "1"){ // 펫샵일경우 ( 추후 분리예정 )
									setCookie_ios("is_shop", "1", 1);
								}
							}else{
								setCookie("order_num", data.data, 1);
								setCookie("order_step", "3", 1); // 1 - 구매, 2 - 조회, 3 - 장바구니
								if(is_shop == "1"){ // 펫샵일경우 ( 추후 분리예정 )
									setCookie("is_shop", "1", 1);
								}
							}
							if(user_id != ""){
								get_cart_cnt(); // cart_count
								$.MessageBox({
									buttonDone: "지금 이동하기",
									buttonFail: "취소",
									message : "<center>장바구니에 잘 담았습니다. 지금 이동하시겠어요?</center>"
								}).done(function(){
									location.href = "<?=$item_directory ?>/item_cart.php?backurl="+encodeURIComponent(window.location.pathname+'?no='+product_no+'&backurl=<?=urlencode($backurl) ?>');
								}).fail(function(){
									return false;
								});
							}else{
								location.href = "/pet/login/";
							}
						}
					}else{
						// 장바구니 제품 추가
						console.log(cart);
						console.log("제품추가2");
						set_insert_cart(total_price, user_id, is_special_mall);
					}
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

$(document).on("click", "#item_product_page .buy_cart", function(){
	var total_price = $("#item_product_page #cart input[name='total_price']").val();
	var user_id = "<?=$user_id ?>";

	if($(this).hasClass("disabled")){
		return false;
	}
	// 구매한 정보(cart)를 세션에 담는다
	if(cart.length <= 0){
		$.MessageBox("상품을 선택해주세요.");
	}else{
		// 해당 상품을 장바구니 상품과 비교
		// - 현재 장바구니 제품 리스트 호출 (1)
		// - 제품 리스트 호출 (2)
		// - 전문몰/반짝몰 비교 (2)
		//  = 현재 상품과 비교하여 다른 몰 상품이 1개 이상 있을 경우 장바구니 상품 삭제 (2-1)
		// - 반복문 처리하여 비교 (3)
		//  = 중복상품이 있다면 수량만 추가 (3-1)
		//  = 중복상품이 없다면 제품 추가 (3-2)


		get_cart_list()
			.then(get_special_mall_chk)
			.then(get_cart_item_duplicate_chk);
	}
});

function set_update_cart_amount(ic_seq, cart_data){
	// 수량 추가
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "set_update_cart",
			is_session : "1",
			customer_id : "<?=$user_id ?>",
			ic_seq : ic_seq,
			cart_data : JSON.stringify(cart_data)
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data); 

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

function set_insert_cart(total_price, user_id, is_special_mall){

	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "set_insert_cart",
			total_price: total_price,
			user_id: user_id,
			product_no: product_no,
			is_shop: is_special_mall,
			ip_seq: ip_seq,
			is_supply: is_supply,
			supplier: supplier,
			cart_data: JSON.stringify(cart)
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				if(checkMobile2() == "in_app_ios"){
					setCookie_ios("order_num", data.data, 1);
					setCookie_ios("order_step", "3", 1); // 1 - 구매, 2 - 조회, 3 - 장바구니
					if(is_shop == "1"){ // 펫샵일경우 ( 추후 분리예정 )
						setCookie_ios("is_shop", "1", 1);
					}
				}else{
					setCookie("order_num", data.data, 1);
					setCookie("order_step", "3", 1); // 1 - 구매, 2 - 조회, 3 - 장바구니
					if(is_shop == "1"){ // 펫샵일경우 ( 추후 분리예정 )
						setCookie("is_shop", "1", 1);
					}
				}
				if(user_id != ""){
					get_cart_cnt(); // cart_count
					$.MessageBox({
						buttonDone: "지금 이동하기",
						buttonFail: "취소",
						message : "<center>장바구니에 잘 담았습니다. 지금 이동하시겠어요?</center>"
					}).done(function(){
						location.href = "<?=$item_directory ?>/item_cart.php?backurl="+encodeURIComponent(window.location.pathname+'?no='+product_no+'&backurl=<?=urlencode($backurl) ?>');
					}).fail(function(){
						return false;
					});
				}else{
					location.href = "/pet/login/";
				}
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

$(document).on("click", "#item_product_page .buy_go", function(){
	var total_price = $("#item_product_page #cart input[name='total_price']").val();
	var is_special_mall = $("#item_product_page #cart input[name='special_mall']").val();
	var user_id = "<?=$user_id ?>";
	console.log(total_price);

	if($(this).hasClass("disabled")){
		return false;
	}
	// 구매한 정보(cart)를 세션에 담는다
	if(cart.length <= 0){
		alert("상품을 선택해주세요.");
	}else{
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "set_insert_cart",
				total_price: total_price,
				user_id: user_id,
				product_no: product_no,
				is_shop: is_special_mall,
				ip_seq: ip_seq,
				is_supply: is_supply,
				supplier: supplier,
				cart_data: JSON.stringify(cart)
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					if(checkMobile2() == "in_app_ios"){
						setCookie_ios("order_num", data.data, 1);
						setCookie_ios("order_step", "1", 1); // 1 - 구매, 2 - 조회, 3 - 장바구니
						if(is_shop == "1"){ // 펫샵일경우 ( 추후 분리예정 )
							setCookie_ios("is_shop", "1", 1);
						}
					}else{
						setCookie("order_num", data.data, 1);
						setCookie("order_step", "1", 1); // 1 - 구매, 2 - 조회, 3 - 장바구니
						if(is_shop == "1"){ // 펫샵일경우 ( 추후 분리예정 )
							setCookie("is_shop", "1", 1);
						}
					}
					if(user_id != ""){
						location.href = "<?=$item_directory ?>/item_payment.php?no="+data.data;
					}else{
						location.href = "<?=$login_directory?>/";
					}
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
});

// 접속기기 체크
function checkMobile2(){
	var varUA = navigator.userAgent.toLowerCase(); //userAgent 값 얻기
	if ( varUA.indexOf("app_gobeauty_and") > -1 ) {
		//APP
		return "in_app_and";
	} else if (varUA.indexOf("app_gobeauty_ios") > -1 ) {
		//안드로이드
		return "in_app_ios";
	} else if ( varUA.indexOf('android') > -1 ) {
		//안드로이드
		return "android";
	} else if ( varUA.indexOf("iphone") > -1||varUA.indexOf("ipad") > -1||varUA.indexOf("ipod") > -1 ) {
		//IOS
		return "ios";
	} else {
		//아이폰, 안드로이드 외
		return "other";
	}
}

// 쿠키 생성
function setCookie(cName, cValue, cDay){
	var expire = new Date();
	expire.setDate(expire.getDate() + cDay);
	cookies = cName + '=' + escape(cValue) + '; path=/ '; // 한글 깨짐을 막기위해 escape(cValue)를 합니다.
	if(typeof cDay != 'undefined') cookies += ';expires=' + expire.toGMTString() + '; SameSite=None; Secure ';
	document.cookie = cookies;
}

// 쿠키 생성
function setCookie_ios(cName, cValue, cDay){ // IOS bug - SameSite=None; Secure를 SameSite=static으로 인식하는 문제
	var expire = new Date();
	expire.setDate(expire.getDate() + cDay);
	cookies = cName + '=' + escape(cValue) + '; path=/ '; // 한글 깨짐을 막기위해 escape(cValue)를 합니다.
	if(typeof cDay != 'undefined') cookies += ';expires=' + expire.toGMTString() + '; ';
	document.cookie = cookies;
}

// 쿠키 가져오기
function getCookie(cName) {
	cName = cName + '=';
	var cookieData = document.cookie;
	var start = cookieData.indexOf(cName);
	var cValue = '';
	if(start != -1){
		start += cName.length;
		var end = cookieData.indexOf(';', start);
		if(end == -1)end = cookieData.length;
		cValue = cookieData.substring(start, end);
	}
	return unescape(cValue);
}

function day_diff(date1, date2){
	var diff = 1;
	var date1_time = new Date(date1).getTime();
	var date2_time = new Date(date2).getTime();
	if(date1_time > date2_time){
		return ((date1_time - date2_time) / (24 * 60 * 60 * 1000));
	}else{
		return ((date2_time - date1_time) / (24 * 60 * 60 * 1000));
	}
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

//남는 길이만큼 0으로 채움
function fillZero(width, str){
	var str = String(str);//문자열 변환
	return str.length >= width ? str:new Array(width-str.length+1).join('0')+str;
}

</script>