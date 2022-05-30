<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/common/TRestAPI.php");
include($_SERVER['DOCUMENT_ROOT']."/include/skin/header.php");

$user_id = (isset($_SESSION['gobeauty_user_id'])) ? $_SESSION['gobeauty_user_id'] : "";
$user_name = (isset($_SESSION['gobeauty_user_nickname'])) ? $_SESSION['gobeauty_user_nickname'] : "";

$select_year = $_POST['graph_year'];
$pet_id = $_POST['pet_id'];

$api = new TRestAPI("http://stg-walkapi.banjjakpet.com:8080", "token 58de28d6170dcf11edf7c009bff81e37536a2fa4");
//$api = new TRestAPI("http://192.168.20.128:8080", "token 58de28d6170dcf11edf7c009bff81e37536a2fa4");
$year_log = $api->get("/walklog/year/".$user_id."/".$pet_id."/".$select_year);
$graph_month = [];
foreach($year_log['body'] as $val){
    array_push($graph_month, substr($val['ymonth'],4, 6));
}
sort($graph_month);
if(count($graph_month) > 0){
    $month_log = $api->get("/walklog/month/" . $user_id . "/" . $pet_id . "/" . $select_year. "/" . $graph_month[0]);
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
						<canvas id="verticalBar1"></canvas>
					</div>
				</div>
			</div>		

			<div class="basic-data-group vvsmall3">
				<div class="con-title-group">
					<h5 class="con-title">연도 선택</h5>
					<select class="arrow">
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
                    <select class="arrow">
                        <?php
                        for($i=0; $i < count($graph_month); $i++){
                            if($i == 0){
                                echo '<option value="" selected>'.$graph_month[$i].'월</option>';
                            }else{
                                echo '<option value="">'.$graph_month[$i].'월</option>';
                            }
                        }
                        ?>
                    </select>
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
$(function(){
    var date_arr = <?php echo json_encode($date_arr) ?>;
    var dist_arr = <?php echo json_encode($dist_arr) ?>;
    var time_arr = <?php echo json_encode($time_arr) ?>;
    console.log(date_arr);
	var verticalBarData = {
    label : ['2022-01-01', '2022-01-02','2022-01-03', '2022-01-04'], //x축 라벨 명
	datasets : [{
		borderWidth:0,
		//barThickness : 16,
		barPercentage : 0.5,
		categoryPercentage : 1,
		backgroundColor: 'rgb(104, 64, 177)' ,
		hoverBackgroundColor: 'rgb(104, 64, 177)',
		data : [200, 400], //y축 라벨 데이타값
	},{
		borderWidth:0,
		//barThickness : 16,
		barPercentage : 1,
		categoryPercentage : 0.5,
		backgroundColor: 'rgb(255, 72, 72)' ,
		hoverBackgroundColor: 'rgb(255, 72, 72)',
		data : [100, 399], //y축 라벨 데이타값
	}]
};
var verticalBarConfig = {
  type: 'bar',
  data: verticalBarData,
  options:{
	reponsive : true,
	maintainAspectRatio : false,
	title : {
		display:false
	},
	legend : {
		display:false
	},		
	tooltip : {
		enabled: false
	},	
	animation : false,
	scales: {
		xAxes: [{	                  
			scaleLabel : {
				display:false
			},		
			gridLines: {
				display:false,
				drawBorder:false
			},
			ticks : {
			  fontFamily:"-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'",
			  fontSize : 14,
			  lineHeight : 1.5,
			  fontColor : '#666'
			}
		}],
		yAxes: [{	
			scaleLabel : {
				display:false					
			},
			gridLines: {
				display:true,
				tickLength:10,
				color:'#ebebeb',
				drawBorder:false,
				zeroLineColor:'rgba(0,0,0,0)'
			},
			ticks : {
			  fontFamily:"-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'",
			  fontSize : 14,
			  lineHeight : 1.5,
			  fontColor : '#666',
			  padding : 0,
			  min : 0,
			  max : 2000,
			  stepSize : 500,
			}
		}]
	  },          
	  layout : {
		padding : {
		   left :0,
		   right :0,
		   top :0,
		   bottom :0
		}
	  }	,
	  plugins:{
		labels : false
		}
	}	
};

var verticalBarCtx = document.getElementById('verticalBar1');
var verticalBar = new Chart(verticalBarCtx , verticalBarConfig);

});
</script>
	
</body>
</html>