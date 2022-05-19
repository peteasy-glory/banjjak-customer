<?php 
	include "../include/top.php";
?>
<script src="<?= $js_directory ?>/fontawesome.min.js"></script>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<style>
	.bjj_top_menu { z-index: 100; }
	#direct_playroom { margin-top: 60px; }
	#direct_playroom .banner { width: 100%; background-image: url('../images/20201207_holiday_detail_1_line.jpg'); background-size: 100%; text-align: center; }
	#direct_playroom .banner img { width: 100%; }
	#direct_playroom .btn_wrap { padding: 20px 0px 0px; }
	#direct_playroom .banner .link_btn { width: 80%; height: 40px; border: 1px solid #ffd790; border-radius: 5px; background-color: #ffd790; z-index: 1; font-size: 1em; margin: 10px auto; cursor: pointer; }
	#direct_playroom .banner .google_btn { width: 80%; height: 40px; border: 1px solid #ffd790; border-radius: 5px; background-color: #ffd790; z-index: 1; font-size: 1em; margin: 10px auto; cursor: pointer; }
	#direct_playroom .banner .app_down_btn { width: 80%; height: 40px; border: 1px solid #ffd790; border-radius: 5px; background-color: #ffd790; z-index: 1; font-size: 1em; margin: 10px auto; cursor: pointer; }
	#direct_playroom .title { font-size: 24px; margin: 10px 0px; }
	#direct_playroom ul { width: 80%; margin: 0 auto; }
	#direct_playroom ul li { text-align: center; }
	#direct_playroom ul li div { text-align: left; }
	#direct_playroom ul li div>span { display: inline-block; color: #f00; font-size: 18px; line-height: 30px; height: 30px; }
	#direct_playroom ul li div a { display: inline-block; margin: 0px 5px; color: #f5bf2e; padding: 5px; border: 1px solid #ccc; backgroujnd-color: #eee; border-radius: 5px; }
	#direct_playroom .banner ul li img { width: 60%; margin: 0 auto; margin: 30px 10px; }
</style>
<div class="bjj_top_menu">
	<div class="header-back-btn"><a href="<?= $mainpage_directory ?>/"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
	<div class="bjj_top_title">
		<p>Holiday 이벤트</p>
	</div>
	<!--div class="bjj_top_home"><a href="index.php"><img src="../images/btn_myshop_01.png"></a></div-->
</div>
<div id="direct_playroom">
	<div class="banner">
		<img src="../images/20201207_holiday_detail_1_1_1.jpg" />
		<!--div class="btn_wrap">
			<button type="button" class="link_btn"> 펜션 바로가기 > </button>		
		</div>
		<img src="../images/20201207_holiday_detail_1_2.jpg" />
		<div>
			<div class="title">참여방법</div>
			<ul>
				<li>
					<div><span>STEP 1. </span><br/>반짝 인스타그램<a href="https://www.instagram.com/banjjak_official" target="_blank">@banjjak_official</a> 계정 팔로우 하기</div>
					<img src="../images/20201207_holiday_detail_1_4.png" />
				</li>
				<li>
					<div><span>STEP 2. </span><br/>반짝 어플 다운로드 및 회훤가입 하기</div>
					<img src="../images/20201207_holiday_detail_1_5.png" />
					<button type="button" class="app_down_btn"> 반짝 앱 다운로드 > </button>
				</li>
				<li>
					<div><span>STEP 3. </span><br/>이벤트 참여정보 등록하기</div>
					<img src="../images/20201207_holiday_detail_1_6.jpg" />
					<button type="button" class="google_btn"> 정보 등록하기 > </button>
				</li>
			</ul>
		</div>
		<img src="../images/20201207_holiday_detail_1_3.jpg" />
	</div 이벤트 부분-->
</div>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-152043924-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'UA-152043924-1');
</script>

<script>
	var $direct_playroom = $("#direct_playroom");
	var tmp_val = checkMobile2();

	$(function(){
	});

	$direct_playroom.on("click", ".link_btn", function(){
		window.open("http://www.psmonaco.com/", "_blank");
	});

	$direct_playroom.on("click", ".app_down_btn", function(){
		if(tmp_val == "android"){
			location.href = "https://play.google.com/store/apps/details?id=m.kr.gobeauty";
		}else if(tmp_val == "ios"){
			location.href = "https://apps.apple.com/kr/app/id1436568194";
		}
	});

	$direct_playroom.on("click", ".google_btn", function(){
		window.open("https://forms.gle/pMQLtCNGg1e4zXpb9", "_blank");
	});

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
</script>
<?php 
	include "../include/bottom.php";
?>