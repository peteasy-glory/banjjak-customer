<?php 
	include "../include/top.php";

	$backurl = $_GET["backurl"];
	$point_event = $_GET["event_back"]; // 포인트 장려 이벤트일 시 backurl
?>
<script src="<?= $js_directory ?>/fontawesome.min.js"></script>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<style>
	#fixed-menu { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; background-color: #fff; z-index: 2; border-bottom: 1px solid #ccc; }
	#fixed-menu .fixed-menu-wrap { position: relative; width: 100%; height: 50px; font-size: 24px; }
	#fixed-menu .fixed-menu-wrap .left_menu { position: absolute; left: 0px; top: 0px; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
	#fixed-menu .fixed-menu-wrap .center_menu { width: 100%; height: 50px; line-height: 50px; text-align: center; font-size: 18px; }
	#fixed-menu .fixed-menu-wrap .center_menu a { color: #bbb; }
	#fixed-menu .fixed-menu-wrap .center_menu img { width: 55px; margin-top: 10px; padding: 0px; }
	#fixed-menu .fixed-menu-wrap .right_menu { position: absolute; right: 0px; top: 0px; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
	#fixed-menu .fixed-menu-wrap .right_menu a img { }
	#fixed-menu .scroll_top { display: none; position: fixed; right: 10px; bottom: 10px; z-index: 1; width: 50px; height: 50px; background-color: rgba(255, 255, 255, 0.6); border: 1px solid #ccc; border-radius: 25px; -webkit-align-items: center; -webkit-justify-content: center; }
	#fixed-menu .scroll_top.on { display: flex; }

	#direct_playroom { margin: 50px 0px; padding-bottom: 60px; }
	#direct_playroom .banner { width: 100%; text-align: center; }
	#direct_playroom .banner img { width: 100%; max-width: 720px; }
	#button{border:2px solid #ffbe46; text-align:center; border-radius:50px; height:50px; width:300px;margin:0 auto; margin-top:10px; line-height:50px; margin-bottom:10px;}
	.button4 {color:#ffbe46; font-size:25px;}
</style>
<div id="fixed-menu">
	<div class="fixed-menu-wrap">
		<div class="left_menu">
		<?php if($backurl){ ?>
			<a href="<?=$backurl ?>"><i class="fas fa-chevron-left"></i></a>
		<?php }else if($point_event == "1"){ // 포인트 장려 이벤트일 시 backurl ?> 
			<a href="<?=$mainpage_directory ?>/manage_my_point.php"><i class="fas fa-chevron-left"></i></a>
		<?php }else{ ?>
			<a href="<?=$mainpage_directory ?>/"><i class="fas fa-chevron-left"></i></a>
		<?php } ?>
		</div>
		<div class="center_menu">반짝 포인트 적립
		</div>
		<div class="right_menu">
		</div>
		<!--div class="scroll_top"><i class="fas fa-chevron-up"></i></div-->
	</div>
</div>
<div id="direct_playroom">
	<div class="banner">
		<img src="../images/view_event5_6.jpg" />
	</div>


	<div id="button">
		<a href="https://www.gopet.kr/pet/login/registration_agree.php">
			<div class="button4">
				회원가입
			</div>
		</a>
	</div>

</div>


<script>
</script>
<?php 
	include "../include/bottom.php";
?>