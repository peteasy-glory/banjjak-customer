<?php
include "../include/top.php";
include "../include/Crypto.class.php";

$r_top = ($_GET['top'] && $_GET['top'] != "")? $_GET['top'] : "서울";
$r_middle = ($_GET['middle'] && $_GET['middle'] != "")? $_GET['middle'] : "";
$user_id = isset($_SESSION['gobeauty_user_id']) ? $_SESSION['gobeauty_user_id'] : "";
$user_name = isset($_SESSION['gobeauty_user_nickname']) ? $_SESSION['gobeauty_user_nickname'] : "";
?>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new_3.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new_3.css') ?>">
<script src="<?= $js_directory ?>/fontawesome.min.js"></script>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=f2cf1f3b7e2ca88cb298196078ef550f"></script>
<style>
	/*#main-content .button_wrap { position: relative; margin-top: 30px; }*/
	#map { background:none!important; }
	.loading { position: absolute; left: 50%; top: 50%; width: 30px; height: 23px; margin-top: 7px; text-align: center; margin-left: -15px; margin-top: -15px; }
	.messagebox_overlay { z-index: 101; }
	.main-content .location_tab button img{width:25px;}
	.search_btn{margin-left:10px;margin-right:5px;width:20px;height:20px;text-indent:-99999px;background: url("/pet/images/search2.png") no-repeat center center;outline:none;border:none;background-size:contain;}
	
</style>
<div id="main-content" style=" padding-bottom: calc(constant(safe-area-inset-bottom) + 40px); padding-bottom: calc(env(safe-area-inset-bottom) + 40px);">
	<div id="fixed-menu">
		<div class="top_menu">
			<div class="header-back-btn"><a href="<?= $mainpage_directory ?>/"><img src="/pet/images/btn_back_2.png" width="26px"></a></div>
			<div class="top_title">
				<p>상품 검색</p>
			</div>
			<div class="top_reflash"><a href="<?=$mainpage_directory?>/"><img src="../images/reflash.png"></a></div>
		</div>
	</div>
	<div class="location_tab" style="margin-top:58px;">
		<div style="position:fixed;left:-7px;top:25px;background:#fff;z-index:10;width:100%;">
			<input type="text" placeholder="어떤 상품을 찾으세요?" style="outline:none;border:none;background:#efefef;padding:10px 0px 10px 10px;width:70%;">
			<input type="button" name="button" value="검색" class="search_btn"></button>
			<input type="button" value="닫기" style="outline:none;border:none;" onClick="location.href='https://www.gopet.kr/pet/mainpage/index_lisa.php?tab=3'">

		</div>
		
	</div>
	<div class="location_top_wrap">
		<input type="hidden" name="top" value="<?=$r_top ?>" />
		<input type="hidden" name="middle" value="<?=$r_middle ?>" />
		<div id="map" class="location_box" style="width:100%;position:relative;background:none!important;">
			<div class="location_bok">
				<div class="location_top" style="font-size:16px;margin-left:-10px;margin-top:30px;">인기검색어</div>
				
				<div class="location_middle_wrap" style="height:200px;background:none;padding:0px 20px 0px 20px;box-sizing:border-box;">
					<div class="location_middl" style="height:40px;border-bottom:1px solid #ccc;line-height:40px;font-size:14px;color:#f6bf2e;">1</div>
					<div class="location_middl" style="height:40px;border-bottom:1px solid #ccc;line-height:40px;font-size:14px;color:#f6bf2e;">2</div>
					<div class="location_middl" style="height:40px;border-bottom:1px solid #ccc;line-height:40px;font-size:14px;color:#f6bf2e;">3</div>
					<div class="location_middl" style="height:40px;border-bottom:1px solid #ccc;line-height:40px;font-size:14px;color:#f6bf2e;">4</div>
					<div class="location_middl" style="height:40px;border-bottom:1px solid #ccc;line-height:40px;font-size:14px;color:#f6bf2e;">5</div>
				</div>
			</div>
		</div>
	</div>
	<div><img src="../images/dental.jpg" style="width:100%;padding:45px 20px;box-sizing:border-box;"></div>
</div>



<?php include "../include/bottom.php"; ?>