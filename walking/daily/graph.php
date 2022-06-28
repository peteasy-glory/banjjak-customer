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

$api = new TRestAPI("https://walkapi.banjjakpet.com:8080");
//$api = new TRestAPI("http://stg-walkapi.banjjakpet.com:8080", "token 58de28d6170dcf11edf7c009bff81e37536a2fa4");
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
    $month_log = $api->get("/walklog/graph/" . $user_id . "/" . $select_year. "/" . $select_month);
}


foreach ($month_log["body"] as $days){
//    array_push($date_arr, substr($days["date"],9));
    array_push($date_arr, $days["s_date"]);
    array_push($dist_arr, $days["sum_dist"]);
    array_push($time_arr, $days["sum_time"]);
}
$date_arr = array_reverse($date_arr);
$dist_arr = array_reverse($dist_arr );
$time_arr = array_reverse($time_arr);

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
        <a href="../daily/" class="btn-page-ui btn-page-prev"><div class="icon icon-size-24 icon-page-prev">페이지 뒤로가기</div></a>
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
            <div class="basic-data-group vvsmall3" id="vvsmall_1">
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
            <div class="basic-data-group vvsmall3" id="vvsmall_2">
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

            <div class="chart-wrap">

                <div class="legend-wrap">
                    <div class="legend-wrap-item legend-dist-wrap">
                        <div class="legend-dist-round"></div>
                        <span class="legend-span">산책거리(m)</span>
                    </div>
                    <div class="legend-wrap-item legend-div-wrap">
                        <div class="legend-div-line"></div>
                        <span class="legend-span">월평균</span>
                    </div>
                    <div class="legend-wrap-item legend-time-wrap">
                        <div class="legend-time-round"></div>
                        <span class="legend-span">산책시간(분/시간)</span>
                    </div>
                </div>

                <div class="chart-view">

                    <div class="chart-front">



                    </div>

                    <div class="chart-back">
                        <div class="chart-back-left">
                            <div class="chart-grid-left chart-grid-left-dash"></div>
                            <div class="chart-grid-left"></div>
                            <div class="chart-grid-left chart-grid-left-dash"></div>
                            <div class="chart-grid-left"></div>
                            <div class="chart-grid-left chart-grid-left-dash"></div>
                            <div class="chart-grid-left"></div>
                            <div class="chart-grid-left chart-grid-left-dash" style="border-right:1px solid #f4f4f4;"></div>

                        </div>
                        <div class="chart-grid-middle"></div>

                        <div class="chart-back-right">
                            <div class="chart-grid-right chart-grid-right-dash " style="border-left:1px solid #f4f4f4;"></div>
                            <div class="chart-grid-right"></div>
                            <div class="chart-grid-right chart-grid-right-dash"></div>
                            <div class="chart-grid-right"></div>
                            <div class="chart-grid-right chart-grid-right-dash"></div>
                            <div class="chart-grid-right"></div>
                            <div class="chart-grid-right chart-grid-right-dash"></div>
                        </div>
                    </div>

                    <div class="chart-div">
                        <div class="chart-div-left-box">
                            <div class="chart-div-left"></div>
                        </div>

                        <div class="chart-grid-middle"></div>

                        <div class="chart-div-right-box">
                             <div class="chart-div-right"></div>
                        </div>
                    </div>







                </div>
            </div>
        </div>
        <!-- //walk-graph-view -->
    </div>
    <!-- //page-body -->

</section>
<!-- //container -->
<!--<script src="/static/pub/js/chart2/Chart.bundle.min.js"></script>-->
<!--<script src="/static/pub/js/chart2/chartjs-plugin-labels.js"></script>-->
<!--<script src="/static/pub/js/chart2/chartjs-chart-treemap@0.2.3.js"></script>-->
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


    let month_log = <?= json_encode($month_log["body"]) ?>;
    console.log(month_log);

    console.log(month_log[0].s_date.substr(8,2));

    //한자리 숫자 0 채우기
    const fill_0 = function (n,w){
        const str = String(n);
        return str.padStart(w,'0');

    }
    //


    let chart = [];
    let select_month = <?= json_encode($select_month)?>;
    console.log(select_month);

    if(select_month == '02' ){
        for(let i=1; i<=28;i++){

            chart.push({date:fill_0(i,2),dist:0,time:0})
        }
    }else if(select_month == '04' || select_month == '06' || select_month == '09' || select_month == '11'){
        for(let i=1; i<=30;i++){

            chart.push({date:fill_0(i,2),dist:0,time:0})
        }
    }else{
        for(let i=1; i<=31;i++){

            chart.push({date:fill_0(i,2),dist:0,time:0})
        }
    }


    month_log[0].s_date.substr(8,2);

    console.log(month_log[0].sum_dist)


    for(let i=0; i <= month_log.length; i++){

        for(let j=0; j <= chart.length; j++){

            if(chart[j]?.date === month_log[i]?.s_date.substr(8,2) ){

                if(month_log[i]?.sum_dist !== undefined && month_log[i]?.sum_time !== undefined){
                    chart[j].dist = month_log[i]?.sum_dist;
                    chart[j].time = month_log[i]?.sum_time;

                }
            }


        }
    }


    console.log(chart);

    $(function(){


        let chart_back_left_width = $(".chart-back-left").width();



        //dist 최대값
        let max_dist = chart[0].dist;

        for(let j = 0 ; j <= chart.length; j++){

            if(chart[j]?.dist !== undefined){


                if(max_dist < chart[j].dist){

                    max_dist = chart[j].dist;
                }

            }

        }

        //time 최대값
        let max_time = chart[0].time;

        for(let k = 0 ; k <= chart.length; k++){
            if(chart[k]?.time !== undefined){

                if(max_time < chart[k].time){

                    max_time = chart[k].time;
                }
            }
        }




        for(let i = 0 ; i <= chart.length; i++){

            if(chart[i]?.date !== undefined){
                console.log($(this));
                $(`<div class="chart-front-container">
                        <div class="chart-front-container">
                            <div class="chart-front-left-box">
                                <div class="chart-front-left ${chart[i].dist/max_dist === 1 ? 'chart-front-left-active' : ""}" style="width:${chart[i].dist/max_dist*100}%">
                                    <div class="chart-front-left-balloon" style="${chart[i].dist/max_dist === 1 ? 'display:block;' : 'display:none;'}">${chart[i].dist}</div>
                                </div>
                            </div>
                            
                            <div class="chart-front-middle">${chart[i].date} 일</div>

                            <div class="chart-front-right-box">
                                <div class="chart-front-right ${chart[i].time/max_time === 1 ? 'chart-front-right-active' : "" }" style="width:${chart[i].time/max_time*100}%">
                                    <div class="chart-front-right-balloon" style="${chart[i].time/max_time === 1 ? `display:block;right:calc(-100% + 25px)`:"display:none" }">${chart[i].time}</div>
                                </div>
                            </div>
                        </div>

                   </div>`).appendTo($(".chart-front"));
            }




        }


        $(".chart-front-left").on("click",function(){

            $(".chart-front-left").removeClass('chart-front-left-active');
            $(".chart-front-right").removeClass('chart-front-right-active');
            $(".chart-front-left-balloon").css("display","none");
            $(".chart-front-right-balloon").css("display","none");

            $(this).addClass('chart-front-left-active')
            $(this).children('div').css("display","block");
            if($(this).width() < 25){
                console.log($(this).width())
                console.log("25이하");
                $(this).children('div').css("left",`-30px`)
            }
        })

        $(".chart-front-right").on("click",function(){

            $(".chart-front-left").removeClass('chart-front-left-active');
            $(".chart-front-right").removeClass('chart-front-right-active');
            $(".chart-front-left-balloon").css("display","none");
            $(".chart-front-right-balloon").css("display","none");



            $(this).addClass('chart-front-right-active')
            $(this).children('div').css("display","block");
            if($(this).width() < 25){
                // console.log($(this).width())
                // console.log("25이하");
                $(this).children('div').css("right",`-${$(this).width()}px`)
            }else{
                // console.log($(this).width())
                // console.log("25이상");
                $(this).children('div').css("right",`-${$(this).width()-25}px`)
            }



        })

        let month_sum_dist = 0;
        for(let i=0; i<=chart.length; i++){

            if(chart[i]?.dist !== undefined){

                month_sum_dist += chart[i].dist;
            }

        }

        let month_div_dist = month_sum_dist/chart.length;

        $(".chart-div-left").css("width",month_div_dist/max_dist*100+"%")

        let month_sum_time = 0;
        for(let i=0; i<chart.length; i++){

            if(chart[i]?.time !== undefined){

                month_sum_time += chart[i].time;
            }
        }

        let month_div_time = month_sum_time/chart.length;
        $(".chart-div-right").css("width",month_div_time/max_time*100+"%")






    })


    // $(".btn-page-prev").click(function(){
    //     history.back(); return false;
    // });






</script>

</body>
</html>