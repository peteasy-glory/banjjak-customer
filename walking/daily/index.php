<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");


$user_id = (isset($_SESSION['gobeauty_user_id'])) ? $_SESSION['gobeauty_user_id'] : "";
$user_name = (isset($_SESSION['gobeauty_user_nickname'])) ? $_SESSION['gobeauty_user_nickname'] : "";


?>

<!DOCTYPE html>
<html lang="ko" class="">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>반짝</title>
	<meta name="format-detection" content="telephone=no">
	<link href="/static/pub/css/style.css" rel="stylesheet">
	<script src="/static/pub/js/jquery-3.4.1.min.js"></script>    
	<script src="/static/pub/js/jquery-ui.min.js"></script>    
	<script src="/static/pub/js/swiper.min.js"></script>
	<script src="/static/pub/js/common.js"></script>
</head>
<body>        

<!-- header -->
<header id="header">	
	<div class="header-left">
		<a href="#" class="btn-page-ui btn-page-prev"><div class="icon icon-size-24 icon-page-prev">페이지 뒤로가기</div></a>
	</div>
	<div class="page-title">산책일지</div>
</header>
<!-- //header -->

<!-- container -->
<section id="container"> 
	<!-- page-body -->    
	<div class="page-body">
		<!-- page-contents -->  
		<div class="page-contents">
			<div class="user-pet-list-wrap">
				<div class="list-inner">
					<!-- btn-user-pet-item 클래스에 actived클래스 추가시 활성화 -->
					<div class="list-cell"><a href="#" class="btn-user-pet-item actived"><div class="icons"></div><div class="txt">봄이</div></a></div>
					<div class="list-cell"><a href="#" class="btn-user-pet-item"><div class="icons"><img src="/static/pub/images/user_pet_thumb.png" alt=""/></div><div class="txt">봄이</div></a></div>
					<div class="list-cell"><a href="#" class="btn-user-pet-item"><div class="icons"><img src="/static/pub/images/user_pet_thumb.png" alt=""/></div><div class="txt">봄이</div></a></div>
					<div class="list-cell"><a href="#" class="btn-user-pet-item add"><div class="icons"></div><div class="txt">추가</div></a></div>
				</div>				
			</div>
			<!-- 산책 상세 -->
			<div>
				<!-- 자료가 있을 때 -->
				<div class="basic-data-group middle">
					<div class="recode-card-list">
						<div class="recode-card-item">
							<div class="recode-card-name">봄이의 산책카드</div>
							<div class="recode-card-info">
								<div class="item-thumb">
									<div class="user-thumb middle"><img src="/static/pub/images/user_thumb.png" alt=""></div>
								</div>
								<div class="item-data">
									<div class="item-data-inner">
										<div class="item-rank">
											<div class="item-rank-value">상위<strong>100%</strong></div>
											<button type="button" class="btn-desc-question"></button>
										</div>
										<!-- bar클래스에 inline-style방식으로 width값을 0~100%로 지정 -->
										<div class="item-progress"><div class="bar" style="width:50%;"></div></div>
										<div class="item-msg">아이에게 산책을 선물하세요</div>
									</div>
								</div>
							</div>
							<div class="recode-card-data">
								<div class="recode-card-data-item">
									<div class="recode-card-data-title">전체 산책</div>
									<div class="record-display">
										<div class="state-item">
											<div class="state-item-value">0</div>
											<div class="state-item-label">회</div>
										</div>
										<div class="state-item">
											<div class="state-item-value">0</div>
											<div class="state-item-label">Km</div>
										</div>
										<div class="state-item">
											<div class="state-item-value">0</div>
											<div class="state-item-label">분</div>
										</div>
									</div>
								</div>
								<div class="recode-card-data-item">
									<div class="recode-card-data-title">평균/1회 산책</div>
									<div class="record-display">
										<div class="state-item">
											<div class="state-item-value">0</div>
											<div class="state-item-label">Km</div>
										</div>
										<div class="state-item">
											<div class="state-item-value">0</div>
											<div class="state-item-label">분</div>
										</div>
										<div class="state-item">
											<div class="state-item-value">0</div>
											<div class="state-item-label"><img src="/static/pub/images/icon/icon_defecate.png" alt="" width="24"/></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- //자료가 있을 때 -->
				<!-- 상세 리스트 자료 없을 때 -->
				<div class="basic-data-group middle">
					<div class="recode-none">
						<div class="con-title-group">
							<h4 class="con-title">산책 기록이 없습니다.</h4>
						</div>
					</div>
				</div>
				<!-- //상세 리스트 자료 없을 때 -->
				<!-- 상세 리스트 자료 있을 때 -->
				<div class="basic-data-group middle">
					<div class="con-title-group">
						<h4 class="con-title">월별 산책기록</h4>		
						<select class="arrow">
							<option value="">2021년</option>
							<option value="">2021년</option>
							<option value="">2021년</option>
						</select>
					</div>
					<!-- 20220115 수정 -->
					<a href="#" class="btn btn-middle-size btn-outline-gray btn-round btn-graph-view"><span class="icon icon-graph-view"></span>그래프로 보기</a>
					<!-- //20220115 수정 -->
					<!-- 20220115 수정 : 클래스 수정  -->
					<div class="single-btns-list top-none-line">
					<!-- //20220115 수정 -->
						<div class="list-cell"><a href="#" class="btn-single-item arrow"><div class="txt">2021.10</div></a></div>
						<div class="list-cell"><a href="#" class="btn-single-item arrow"><div class="txt">2021.09</div></a></div>
						<div class="list-cell"><a href="#" class="btn-single-item arrow"><div class="txt">2021.08</div></a></div>
						<div class="list-cell"><a href="#" class="btn-single-item arrow"><div class="txt">2021.07</div></a></div>
						<div class="list-cell"><a href="#" class="btn-single-item arrow"><div class="txt">2021.06</div></a></div>
						<div class="list-cell"><a href="#" class="btn-single-item arrow"><div class="txt">2021.05</div></a></div>
						<div class="list-cell"><a href="#" class="btn-single-item arrow"><div class="txt">2021.04</div></a></div>
						<div class="list-cell"><a href="#" class="btn-single-item arrow"><div class="txt">2021.03</div></a></div>
						<div class="list-cell"><a href="#" class="btn-single-item arrow"><div class="txt">2021.02</div></a></div>
						<div class="list-cell"><a href="#" class="btn-single-item arrow"><div class="txt">2021.01</div></a></div>
					</div>
				</div>
				<!-- //상세 리스트 자료 있을 때 -->
			</div>
			<!-- //산책 상세 -->
		</div>
		<!-- //page-contents -->  
	</div>
	<!-- //page-body -->    

</section>
<!-- //container -->

<script>
    $(".btn-page-prev").click(function(){
        var mobile = checkMobile2();
        if(mobile === "in_app_and" || mobile === "in_app_ios"){
            onBackWalkTop(mobile);
        }else {
            window.location.href = "../";
        }
    });

    // 기기 체크
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

    function onBackWalkTop(device){
        var id	=  "<?=$_SESSION['gobeauty_user_id']?>";
        var event = "0";
        if(device.indexOf("in_app_and") >-1){
            walking_daily_android.onBackWalkTop(id, event);
        }
        else if(device.indexOf("in_app_ios") > -1){
            let messages = {
                'id': id,
                'event': event
            };
            webkit.messageHandlers.onBackWalkTop.postMessage(messages);
        }

    }
</script>
</body>


</html>

