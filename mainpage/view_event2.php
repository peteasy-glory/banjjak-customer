<?php include "../include/top.php"; ?>

<?php
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-152043924-1"></script>

<link rel="stylesheet" href="../css/reset.css?<?= filemtime($upload_static_directory . $css_directory . '/reset.css') ?>">
<link rel="stylesheet" href="../css/viSimpleSlider2.css?<?= filemtime($upload_static_directory . $css_directory . '/viSimpleSlider2.css') ?>">
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<meta name="apple-mobile-web-app-capable" content="yes" />
<script type="text/javascript" src="../js/jquery.easing.js"></script>
<script type="text/javascript" src="../js/vinyli.viSimpleSlider.js"></script>
<style>
	.top_menu { z-index: 11; }
</style>

<?php
// $prevPage = $_SERVER['HTTP_REFERER'];
// $prev_location = explode('/pet/',$prevPage);
// $prevPage = '/pet/'.$prev_location[1];
$prevPage = $mainpage_directory;
?>

<div class="top_menu">
    <div class="top_back"><a href="<?= $prevPage ?>"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div class="top_title">
        <p style="color:#000;">반짝 입점 신청하기</p>
    </div>
</div>

<div id="cs_view_event">
	<div id="testDiv" style="height: 580px;">
		<ul>
			<li>
				<img src="../images/20210106_1_1_1.jpg">
			</li>
			<li>
				<img src="../images/20210106_2.jpg">
			</li>
			<li>
				<img src="../images/20210106_3.jpg">
			</li>
			<li>
				<img src="../images/20210106_4.jpg">
			</li>
			<li>
				<img src="../images/20210106_5.jpg">
			</li>
			<li>
				<img src="../images/20210106_6.jpg">
			</li>
			<li>
				<img src="../images/20210106_7_1.jpg">
			</li>
		</ul>
	</div>

	<div style="width:100%;font-size:13px;text-align:right;"><a href="<?= $mainpage_directory ?>/regist_shop_auth.php" class="go_button">반짝 입점 신청하기</a></div>
</div>
<br>
<br><br><br>
<br><br><br>

<script>
	$('#testDiv').viSimpleSlider({
		ease: 'easeOutQuart',
		autoPlay: true,
		autoTime: 6000,
		speed: 400,
		mobileSwipe: true,
		desktopSwipe: true
	});

	// Global site tag (gtag.js) - Google Analytics 
	window.dataLayer = window.dataLayer || [];
	function gtag() { dataLayer.push(arguments); }
	gtag('js', new Date());
	gtag('config', 'UA-152043924-1');
</script>
<?php include "../include/bottom.php"; ?>