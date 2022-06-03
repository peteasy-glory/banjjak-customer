<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/common/TRestAPI.php");
include($_SERVER['DOCUMENT_ROOT']."/include/skin/header.php");

$user_id = (isset($_SESSION['gobeauty_user_id'])) ? $_SESSION['gobeauty_user_id'] : "";
$user_name = (isset($_SESSION['gobeauty_user_nickname'])) ? $_SESSION['gobeauty_user_nickname'] : "";

$year_month = $_POST['year'];
$year = substr($year_month, 0, 4);
$month = substr($year_month, 4, 6);
$pet_id = $_POST['pet_id'];

$api = new TRestAPI("https://walkapi.banjjakpet.com:8080");
//$api = new TRestAPI("http://stg-walkapi.banjjakpet.com:8080", "token 58de28d6170dcf11edf7c009bff81e37536a2fa4");
//$api = new TRestAPI("http://192.168.20.128:8080", "token 58de28d6170dcf11edf7c009bff81e37536a2fa4");
$months_log = $api->get("/walklog/month/".$user_id."/".$pet_id."/".$year."/".$month);

$year_log = $api->get("/walklog/year/".$user_id."/".$pet_id."/".$year);
foreach ($year_log['body'] as $val){
    if($val["ymonth"] == $year_month){
        $sum_dist = $val["sum_distance"];
        $sum_time = $val["sum_time"];
        $sum_poo = $val["sum_poo"];
        $sum_pee = $val["sum_pee"];
        break;
    }
}

?>
<!-- header -->
<header id="header">	
	<div class="header-left">
		<a href="../daily" class="btn-page-ui btn-page-prev"><div class="icon icon-size-24 icon-page-prev">페이지 뒤로가기</div></a>
	</div>
	<div class="page-title">월별 산책기록</div>
</header>
<!-- //header -->

<!-- container -->
<section id="container"> 
	<!-- page-body -->    
	<div class="page-body">
		<!-- page-contents -->  
		<div class="page-contents small">
			<div class="con-title-group">
				<h4 class="con-title"><?=$year?>.<?=$month?></h4>
				<!-- 20220115 수정 -->
				<div class="con-title-option">
					<div class="option-cell"><?=number_format(intval($sum_dist)/1000,2)?>Km</div>
					<div class="option-cell"><?=number_format(intVal($sum_time)/60,2)?>분</div>
					<div class="option-cell"><div class="icon icon-defecation-gray-small"></div><?=number_format($sum_poo+$sum_pee)?>회</div>
				</div>
				<!-- //20220115 수정 -->
			</div>
			<div class="record-month-list">
				<ul class="accordion-list">

                    <?php
                        if($months_log['body'] != null){
                            foreach ($months_log['body'] as $val){
                        echo '<li class="accordion-cell">'.
                                    '<button type="button" class="btn-accordion-menu btn-record-accordion">'.
                                        '<span class="btn-record-accordion-inner">'.
                                            '<span class="record-accordion-date">'.$val["date"].'</span>'.
                                            '<span class="record-accordion-option">'.number_format(intval($val["distance"])/1000, 2).'Km'.', '.number_format(intval($val["time"])/60, 2).'분</span>'.
                                        '</span>'.
                                    '</button>'.
                                    '<div class="accordion-content">'.
                                        '<div class="record-accordion-data">';
//                                        if($val["track_map_path"] != null){
                                echo         '<div class="record-accordion-detail" ><img src="'.$val["track_map_path"].'" alt=""/></div>';
//                                        }else{
                                echo        '<div class="record-accordion-header" style="background: #8f8f8f">'.
                                            '<div class="item-sort">'.
                                            '<div class="icon icon-size-24 icon-clock-small-white"></div>'.
                                            '<div class="item-value">'.number_format(intVal($val["time"])/60, 2).'분'.'</div>'.
                                            '</div>'.
                                            '<div class="item-sort">'.
                                            '<div class="item-value">'.number_format(intval($val["distance"])/1000, 2).'Km'.'</div>'.
                                            '</div>'.
                                            '<div class="item-sort">'.
                                            '<div class="icon icon-size-24 icon-defecate-small-white"></div>'.
                                            '<div class="item-value">'.number_format($val["sum_poo"]+$val["sum_pee"]).'회'.'</div>'.
                                            '</div>'.
                                            '</div>';
                                        if($val["track_map_path"] != null) {
                                echo        '<button type="button" class="btn-record-kakao-share" data-map_url="'.$val["track_map_path"].'"></button>';
                                        }
                                echo    '</div>';
//                                        }
                          echo      '</div>'.
                              '</li>';
                            }
                        }else{

                        }
                    ?>
				</ul>
			</div>
		</div>
		<!-- //page-contents -->  
	</div>
	<!-- //page-body -->    

</section>
<!-- //container -->
<!--  기본 메세지 팝업(버튼2) -->
<article id="pop_app_dw" class="layer-pop-wrap">
    <div class="layer-pop-parent">
        <div class="layer-pop-children">

            <div class="pop-data alert-pop-data">
                <div class="pop-body">
                    <div class="msg-txt">텍스트 입니다.</div>
                </div>
                <div class="pop-footer">
                    <button type="button" class="btn btn-confirm btn-cc" onclick="popalert.close();">다음에</button>
                    <button type="button" class="btn btn-confirm btn-cf">지금 다운로드</button>
                </div>
            </div>

        </div>
    </div>
</article>
<script>
    $(".btn-record-kakao-share").click(function(){
        var mobile = checkMobile2();
        if(mobile === "in_app_and" || mobile === "in_app_ios"){
            onWalkingLogMapShare(mobile, $(this).data('map_url'));
        }else if(mobile === "android") {
            popalert.confirm('pop_app_dw', "공유 기능은 반짝앱에서 사용 할 수 있습니다.", "https://play.google.com/store/apps/details?id=m.kr.gobeauty");
        }else if(mobile === "ios") {
            popalert.confirm('pop_app_dw', "공유 기능은 반짝앱에서 사용 할 수 있습니다.", "https://apps.apple.com/kr/app/id1436568194");
        }else{
            $('#firstRequestMsg1').find('.msg-txt').text('공유 기능은 반짝앱에서 사용 할 수 있습니다.');
            pop.open('firstRequestMsg1');
        }
    });

    // 기기 체크
    function checkMobile2(){
        const varUA = window.navigator.userAgent.toLowerCase(); //userAgent 값 얻기
        if ( varUA.indexOf("app_gobeauty_and") > -1 ) {
            return "in_app_and";
        } else if (varUA.indexOf("app_gobeauty_ios") > -1 ) {
            return "in_app_ios";
        } else if ( varUA.indexOf('android') > -1 ) {
            return "android";
        } else if ( varUA.indexOf("iphone") > -1||varUA.indexOf("ipad") > -1||varUA.indexOf("ipod") > -1 ) {
            return "ios";
        } else {
            return "other";
        }
    }

    function onWalkingLogMapShare(device, map_url){
        const id	=  "map";
        const event = map_url;
        if(device.indexOf("in_app_and") >-1){
            walking_daily_android.onWalkingLogMapShare(id, event);
        }
        else if(device.indexOf("in_app_ios") > -1){
            let messages = {
                'id': id,
                'event': map_url
            };
            webkit.messageHandlers.onWalkingLogMapShare.postMessage(messages);
        }

    }
</script>
	
</body>
</html>