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

$api = new TRestAPI("http://stg-walkapi.banjjakpet.com:8080", "token 58de28d6170dcf11edf7c009bff81e37536a2fa4");
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
					<div class="option-cell"><div class="icon icon-defecation-gray-small"></div><?=number_format($sum_poo)?>회</div>
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
                                echo         '<div class="record-accordion-detail"><img src="'.$val["track_map_path"].'" alt=""/></div>';
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
                                            '<div class="item-value">'.number_format($val["sum_poo"]).'회'.'</div>'.
                                            '</div>'.
                                            '</div>';
                                        if($val["track_map_path"] != null) {
                                echo        '<button type="button" class="btn-record-kakao-share"></button>';
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

	
</body>
</html>