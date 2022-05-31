<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/common/TRestAPI.php");
include($_SERVER['DOCUMENT_ROOT']."/include/skin/header.php");

$user_id = (isset($_SESSION['gobeauty_user_id'])) ? $_SESSION['gobeauty_user_id'] : "";
$user_name = (isset($_SESSION['gobeauty_user_nickname'])) ? $_SESSION['gobeauty_user_nickname'] : "";

$select_year = (isset($_GET['graph_year'])) ? $_GET['graph_year'] : "";
$select_month = (isset($_GET['graph_month'])) ? $_GET['graph_month'] : "";
$pet_id = (isset($_GET['pet_id'])) ? $_GET['pet_id'] : "";
if($select_year == ""){
    $select_year = (isset($_POST['graph_year'])) ? $_POST['graph_year'] : "";
}
if($pet_id == ""){
    $pet_id = (isset($_POST['pet_id'])) ? $_POST['pet_id'] : "";
}

$api = new TRestAPI("http://stg-walkapi.banjjakpet.com:8080", "token 58de28d6170dcf11edf7c009bff81e37536a2fa4");
//$api = new TRestAPI("http://192.168.20.128:8080", "token 58de28d6170dcf11edf7c009bff81e37536a2fa4");
$year_log = $api->get("/walklog/year/".$user_id."/".$pet_id."/".$select_year);
$graph_month = [];
foreach($year_log['body'] as $val){
    array_push($graph_month, substr($val['ymonth'],4, 6));
}
if(count($graph_month) > 0){
    sort($graph_month);
    if($select_month == ""){
        $select_month = $graph_month[0];
    }
    $month_log = $api->get("/walklog/month/" . $user_id . "/" . $pet_id . "/" . $select_year. "/" . $select_month);
}

$date_arr = [];
$dist_arr = [];
$time_arr = [];
$poo_arr = [];
$pee_arr = [];
foreach ($month_log["body"] as $days){
    array_push($date_arr, substr($days["date"],9));
    array_push($dist_arr, $days["distance"]);
    array_push($time_arr, $days["time"]);
    array_push($poo_arr, $days["sum_poo"]);
    array_push($pee_arr, $days["sum_pee"]);
}
$date_arr = array_reverse($date_arr);
$dist_arr = array_reverse($dist_arr);
$time_arr = array_reverse($time_arr);
$poo_arr = array_reverse($poo_arr);
$pee_arr = array_reverse($pee_arr);

$graph_max = 0;
if (count($dist_arr) > 0){
//    if( max($dist_arr) > 10000)
//        $graph_max = 10000;
//    else
        $graph_max = max($dist_arr);
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
		<!-- walk-graph-view -->  
		<div class="walk-graph-view">
			<div class="horizontal-swiper-scoller">
				<div class="scroller-inner">
					<div class="customer-state-graph vertical-bar-graph">
						<canvas id="verticalBar1" ></canvas>
					</div>
				</div>
			</div>		

			<div class="basic-data-group vvsmall3">
				<div class="con-title-group">
					<h5 class="con-title">연도 선택</h5>
					<select class="arrow" id="choice_year">
                        <?php
                            $startY = 2022;
                            $currY = date("Y");
                            $finishY = $currY + 3;
                            for($i=$startY; $i < $finishY; $i++){
                                if ($i == $select_year) {
                                    echo '<option value=""  selected >'.$i.'년</option>';
                                }else{
                                    echo '<option value="">'.$i.'년</option>';
                                }
                            }
                        ?>
					</select>
				</div>
			</div>
            <div class="basic-data-group vvsmall3">
                <div class="con-title-group">
                    <h5 class="con-title">월 선택</h5>
                    <?php
                        if(count($graph_month) > 0){
                           echo  '<select class="arrow" id="choice_month">';
                        }
                    ?>
                        <?php
                        for($i=0; $i < count($graph_month); $i++){
                            if($graph_month[$i] == $select_month){
                                echo '<option value="" selected>'.$graph_month[$i].'월</option>';
                            }else{
                                echo '<option value="">'.$graph_month[$i].'월</option>';
                            }
                        }
                        ?>
                    <?php
                    if(count($graph_month) > 0){
                        echo  '</select>';
                    }
                    ?>
                </div>
            </div>
		</div>
		<!-- //walk-graph-view -->  
	</div>
	<!-- //page-body -->    

</section>
<!-- //container -->
<script src="/static/pub/js/chart2/Chart.bundle.min.js"></script>
<script src="/static/pub/js/chart2/chartjs-plugin-labels.js"></script>
<script src="/static/pub/js/chart2/chartjs-chart-treemap@0.2.3.js"></script>
<script>
    $(document).on("change", "#choice_year", function(){
        const year = $(this).children("option:selected").text().substr(0, 4);
        const month = $("#choice_month").children("option:selected").text().substr(0, 2);
        location.href = "?pet_id="+<?=$pet_id?>+"&graph_year="+year+"&graph_month="+month;
    });

    $(document).on("change", "#choice_month", function(){
        const year = $("#choice_year").children("option:selected").text().substr(0, 4);
        const month = $(this).children("option:selected").text().substr(0, 2);
        location.href = "?pet_id="+<?=$pet_id?>+"&graph_year="+year+"&graph_month="+month;
    });

    $(function(){
        if(<?=$graph_max?> == 0){
            return;
        }
        let date_arr =<?php echo json_encode($date_arr) ?>;
        let dist_arr =<?php echo json_encode($dist_arr) ?>;
        let time_arr =<?php echo json_encode($time_arr) ?>;
        const verticalBarData = {
            labels: date_arr, //x축 라벨 명
            datasets: [{
                label: '거리(미터)',
                borderWidth: 0,
                //barThickness : 16,
                barPercentage: 0.5,
                categoryPercentage: 1,
                backgroundColor: 'rgb(104, 64, 177)',
                hoverBackgroundColor: 'rgb(104, 64, 177)',
                data: dist_arr, //y축 라벨 데이타값
            }, {
                label: '시간(초)',
                borderWidth: 0,
                //barThickness : 16,
                barPercentage: 1,
                categoryPercentage: 0.5,
                backgroundColor: 'rgb(255, 72, 72)',
                hoverBackgroundColor: 'rgb(255, 72, 72)',
                data: time_arr, //y축 라벨 데이타값
            }]
        };
        const verticalBarConfig = {
            type: 'bar',
            data: verticalBarData,
            options: {
                reponsive: true,
                maintainAspectRatio: false,
                title: {
                    display: true
                },
                legend: {
                    display: true
                },
                tooltip: {
                    enabled: false
                },
                animation: false,
                scales: {
                    xAxes: [{
                        scaleLabel: {
                            display: true
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            fontFamily: "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'",
                            fontSize: 14,
                            lineHeight: 1.5,
                            fontColor: '#666'
                        }
                    }],
                    yAxes: [{
                        scaleLabel: {
                            display: false
                        },
                        gridLines: {
                            display: false,
                            tickLength: 10,
                            color: '#ebebeb',
                            drawBorder: false,
                            zeroLineColor: 'rgba(0,0,0,0)'
                        },
                        ticks: {
                            fontFamily: "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'",
                            fontSize: 14,
                            lineHeight: 1.5,
                            fontColor: '#666',
                            padding: 0,
                            min: 0,
                            max: <?=$graph_max?>,
                            stepSize: 1000,
                        }
                    }]
                },
                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 0,
                        bottom: 0
                    }
                },
                plugins: {
                    labels: false
                }
            }
        };

        const verticalBarCtx = document.getElementById('verticalBar1');
        const verticalBar = new Chart(verticalBarCtx, verticalBarConfig);

    });
</script>
	
</body>
</html>