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
<?php include "../include/top.php"; ?>
<?php include "../include/configure.php"; ?>
<style>
@font-face {font-family: 'BMJUA';font-weight: normal;src: url("../fonts/BMJUA.otf");}
@font-face {font-family: 'NanumGothic';src: url("../fonts/NanumGothic.ttf");}

    html,
    div,
    p,
    a {
        font-family: 'NL2GB';
text-decoration:none;
    }

    body {
        padding: 0px;
        margin: 0px;
    }

    .section_event {
        padding-top: 20px;
        width: 700px;
        margin: 0px auto;
        margin-bottom: 20px;
    }

    .section_event img {
        width: 100%;
    }

    .top_menu {
        display: none;
    }

    body,
    ul,
    p,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        margin: 0;
        padding: 0;
    }

    header {
        width: 100%;
        height: 50px;
        top: 0;
        z-index: 10;
        padding: 10px 0;
        background-color: #fff;
        border-bottom: 1px solid #ccc;
    }

    header .wrap {
        height: 100%;
    }

    header .logo {
        float: left;
        height: 100%;
    }

    header .title {
        float: left;
        margin-left: 30px;
        height: 50px;
        line-height: 50px;
        color: #222;
    }

    .wrap {
        max-width: 1100px;
        margin: 0 auto;
    }

    .wrap:after {
        content: " ";
        display: block;
        clear: both;
    }

    /* max-width 760px */
    @media (max-device-width: 760px) {
        header {
            display: none;
        }

        .top_menu {
            display: block;
        }

        .section_event {
            width: 100%;
            padding-top: 0px;
            margin-bottom: 0px;
        }

        .section_event img {
            width: 100%;
        }
.top_menu {position:relative; }
.top_back {position: absolute;top: 15px;left: 10px;}
.top_title {width: 100%;text-align: center;font-size: 25px;font-weight: normal;padding: 15px 0px 15px 0px;border-bottom: 0.5px solid #e1e1e1;}
.top_title p {margin: 0px;font-family: 'NL2GB';font-weight: normal;line-height:normal;}

	#button{border:2px solid #ff973a; text-align:center; border-radius:50px; height:50px; width:300px;margin:0 auto; margin-top:10px; line-height:50px; margin-bottom:10px;}
	.button4 {color:#ff973a; font-size:25px;}
    }
</style>

<div class="top_menu">
    <div class="top_back"><a href="<?= $mainpage_directory ?>"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div class="top_title">
        <p>리뷰 이벤트</p>
    </div>
</div>

<div class="section_event"><img src="../images/review_event_3_1_2_3.jpg"></div>


	<div id="button">
		<a href="https://www.gopet.kr/pet/login/index.php">
			<div class="button4">
				회원가입하기
			</div>
		</a>
	</div>

	<div id="button">
		<a href="https://www.gopet.kr/pet/mainpage/manage_my_postwrite.php">
			<div class="button4">
				미용후기 남기기
			</div>
		</a>
	</div>


<?php include "../include/bottom.php"; ?>