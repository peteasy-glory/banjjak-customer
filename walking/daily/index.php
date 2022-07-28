<?
header("Access-Control-Allow-Origin: *");


header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // 과거 아무 때나 잡으면 됨.
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");?>

<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/common/TRestAPI.php");
include($_SERVER['DOCUMENT_ROOT']."/include/skin/header.php");


$user_id = (isset($_SESSION['gobeauty_user_id'])) ? $_SESSION['gobeauty_user_id'] : "";
$user_name = (isset($_SESSION['gobeauty_user_nickname'])) ? $_SESSION['gobeauty_user_nickname'] : "";


$api = new TRestAPI("https://walkapi.banjjakpet.com:8080");
//$api = new TRestAPI("http://stg-walkapi.banjjakpet.com:8080", "token 58de28d6170dcf11edf7c009bff81e37536a2fa4");
//$api = new TRestAPI("http://192.168.20.128:8080", "token 58de28d6170dcf11edf7c009bff81e37536a2fa4");
$mypets = $api->get("/walklog/pets/".$user_id);
$_SESSION['backurl_shop'] = $_SERVER[ "REQUEST_URI" ];

$pet_id = (isset($_GET['pet'])) ? $_GET['pet'] : "";

$log_year = $_GET['year'];
if($log_year == null){
    $log_year = date("Y");
}

function retValue($val1, $val2){
    if ($val2 == 0)
        return 0;
    return intval($val1/$val2);
}

?>

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
                    <?
                        $once = false;
                        if($pet_id == null)
                            $once = true;
                        foreach ($mypets['body'] as $val){
                            $photo = $val['photo'];
                            $photo = str_replace("/pet/upload", "/upload", $photo);
                            if ($photo == ""){
                                if($val['type'] == "cat"){
                                    $photo = "../../images/cat/cat_90x90/cat_90x90@3x.png";
                                }
                                else{
                                    $photo = "../../images/dog/dog_90x90/dog_90x90@3x.png";
                                }
                            }else{
                                $photo = "https://image.banjjakpet.com".$photo;
                            }
                            $name = $val['name'];
                            if ($once || $pet_id == $val["pet_seq"]) {
                                $once = false;
                                $pet_id = $val["pet_seq"];
                                $pet_name = $val["name"];
                                $pet_thumb = $photo;
                                $mypet_log = $api->get("/walklog/pet/".$val['pet_seq']);
                                echo '<div class="list-cell" ><a href="#" id="pet_'.$val["pet_seq"].'" class="btn-user-pet-item actived"><div class="icons"><img src="'.$photo.'" alt=""/></div><div class="txt">'.$name.'</div></a></div>';
                            }else{
                                echo '<div class="list-cell" ><a href="#" id="pet_'.$val["pet_seq"].'" class="btn-user-pet-item"><div class="icons"><img src="'.$photo.'" alt=""/></div><div class="txt">'.$name.'</div></a></div>';
                            }
                        }
                    ?>
					<div class="list-cell"><a href="#" class="btn-user-pet-item add"><div class="icons"></div><div class="txt">추가</div></a></div>
				</div>				
			</div>
			<!-- 산책 상세 -->
			<div id="pet_card">
				<!-- 자료가 있을 때 -->
                <?php
                    if($mypet_log['body'] != null){
                 ?>
                <div class="basic-data-group middle">
                    <div class="recode-card-list">
                        <div class="recode-card-item">
                            <div class="recode-card-name" id="card_name">
                                <?= $pet_name?> 산책카드
                            </div>
                            <div class="recode-card-info">
                                <div class="item-thumb">
                                    <div class="user-thumb middle"><img src="<?=$pet_thumb?>" alt=""></div>
                                </div>
                                <div class="item-data">
                                    <div class="item-data-inner">
                                        <div class="item-rank">
                                        <?php
                                            if($mypet_log['body'][0]['rank'] == 0)
                                                echo '<div class="item-rank-value">상위<strong>100%</strong></div>';
                                            else
                                                echo '<div class="item-rank-value">상위<strong>'.$mypet_log['body'][0]['per'].'%</strong></div>';
                                        ?>
                                            <button type="button" class="btn-desc-question" ></button>
                                        </div>
                                        <!-- bar클래스에 inline-style방식으로 width값을 0~100%로 지정 -->
                                        <?php
                                        if($mypet_log['body'][0]['rank'] == 0)
                                            echo '<div class="item-progress"><div class="bar" style="width:0%"></div></div>';
                                        else
                                            echo '<div class="item-progress"><div class="bar" style="width:'.strval(100-$mypet_log['body'][0]['per']).'%"></div></div>';
                                        ?>
                                        <div class="item-msg">아이에게 산책을 선물하세요</div>
                                    </div>
                                </div>
                            </div>
                            <div class="recode-card-data">
                                <div class="recode-card-data-item">
                                    <div class="recode-card-data-title">전체 산책</div>
                                    <div class="record-display">
                                        <div class="state-item">
                                            <div class="state-item-value" id=""><?=number_format(intval($mypet_log['body'][0]['count']))?></div>
                                            <div class="state-item-label">회</div>
                                        </div>
                                        <div class="state-item">
                                            <div class="state-item-value"><?=number_format(intval($mypet_log['body'][0]['dist'])/1000,2)?></div>
                                            <div class="state-item-label">Km</div>
                                        </div>
                                        <div class="state-item">
                                            <div class="state-item-value"><?=number_format(intval($mypet_log['body'][0]['time'])/60, 2)?></div>
                                            <div class="state-item-label">분</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="recode-card-data-item">
                                    <div class="recode-card-data-title">평균/1회 산책</div>
                                    <div class="record-display">
                                        <div class="state-item">
                                            <div class="state-item-value"><?=number_format(intval(retValue($mypet_log['body'][0]['dist'],$mypet_log['body'][0]['count']))/1000, 2)?></div>
                                            <div class="state-item-label">Km</div>
                                        </div>
                                        <div class="state-item">
                                            <div class="state-item-value"><?=number_format(intval(retValue($mypet_log['body'][0]['time'],$mypet_log['body'][0]['count']))/60,2)?></div>
                                            <div class="state-item-label">분</div>
                                        </div>
                                        <div class="state-item">
                                            <div class="state-item-value"><?=number_format(intval(retValue($mypet_log['body'][0]['poo'],$mypet_log['body'][0]['count']))
                                                                                               +intval(retValue($mypet_log['body'][0]['pee'],$mypet_log['body'][0]['count'])))?></div>
                                            <div class="state-item-label"><img src="/static/pub/images/icon/icon_defecate.png" alt="" width="24"/></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="basic-data-group middle">
                    <div class="con-title-group">
                        <h4 class="con-title">월별 산책기록</h4>
                        <select class="arrow">
                            <?php
                                $startY = 2022;
                                $currY = date("Y");
                                $finishY = $currY + 3;
                                for($i=$startY; $i < $finishY; $i++){
                                    if ($i == $log_year) {
                                        echo '<option value=""  selected >'.$i.'년</option>';
                                    }else{
                                        echo '<option value="">'.$i.'년</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <?php
                        $month_log = $api->get("/walklog/year/".$user_id."/".$pet_id."/".$log_year);
                        if($month_log['body'] != null){
                            echo '<a href="#" class="btn btn-middle-size btn-outline-gray btn-round btn-graph-view"><span class="icon icon-graph-view"></span>그래프로 보기</a>';
                            echo '<div class="single-btns-list top-none-line">';
                            foreach ($month_log['body'] as $val){
                                echo ' <div class="list-cell"><a href="#" class="btn-single-item arrow" id="'.$val['ymonth'].'"><div class="txt">'.$val['ymonth'].'</div></a></div>';
                            }
                            echo '</div>';
                        }else{
                    ?>
                    <div class="basic-data-group middle">
                        <div class="recode-none">
                            <div class="con-title-group">
                                <h6 class="con-title">월별 기록이 없습니다.</h6>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                </div>
               <?php
                    }else{
                ?>
                <div class="basic-data-group middle">
                    <div class="recode-none">
                        <div class="con-title-group">
                            <h4 class="con-title">아이에게 산책을 선물하세요.</h4>
                        </div>
                    </div>
                </div>
                <?php
                    }
                ?>

			</div>

			<!-- //산책 상세 -->
		</div>
		<!-- //page-contents -->  
	</div>
	<!-- //page-body -->    

</section>
<!-- //container -->
<?php
function jsAlert($year){
    echo "alert(".$year.")";
}
?>
<script>

    //스크롤페이징
    let deviceHeight = window.screen.height;
    console.log("deviceHeight = " + deviceHeight);
    let listHeight = $(".accordion-list").height();
    console.log("listHieght =  " + listHeight);

    let cellHeight = 82;


    let headerGroupHeight = $("#header").outerHeight(true) + $(".con-title-group").outerHeight(true);
    console.log("headerGroupHeight = " + headerGroupHeight)


    let cellNumber = Math.floor((deviceHeight)/cellHeight)-2

    console.log(cellNumber)



    $(".btn-page-prev").click(function(){
        var mobile = checkMobile2();
        if(mobile === "in_app_and" || mobile === "in_app_ios"){
            onBackWalkTop(mobile);
        }else {
            location.href = "/";
        }
    });

    $(".btn-user-pet-item").click(function(){
        if($(this).attr("id") == null){
            var form = document.createElement('form');
            var objs = document.createElement('input');
            objs.setAttribute('type', 'hidden');
            objs.setAttribute('name', 'from_walk_daily');
            objs.setAttribute('value', "walking/daily/");
            form.appendChild(objs);
            form.setAttribute('method', 'post');
            form.setAttribute('action', "/mypage_pet_list");
            document.body.appendChild(form);
            form.submit();
        }else{
            var pet_id = $(this).attr("id");
            pet_id = pet_id.substr(4);
            $(".btn-user-pet-item").removeClass('actived');
            $(this).addClass('actived');
            location.href = "?pet="+pet_id;
        }
    });

    $(".btn-desc-question").click(function(){
        $('#firstRequestMsg1').find('.msg-txt').text('전체이용자 중 하루 평균 산책시간을 기준으로 아이의 평균산책시간을 백분위로 표시하였습니다.');
        pop.open('firstRequestMsg1');
    });

    $(document).on("change", ".arrow", function(){
        if('<?=$pet_id?>' === "") return;
        const year = $(this).children("option:selected").text().substr(0, 4);
        location.href = "?pet=<?=$pet_id?>&year="+year;
    });

    $(".btn-graph-view").click(function(){
        const year = $(".arrow").children("option:selected").text().substr(0, 4);
        var form = document.createElement('form');
        var objs = document.createElement('input');
        objs.setAttribute('type', 'hidden');
        objs.setAttribute('name', 'graph_year');
        objs.setAttribute('value', year);
        form.appendChild(objs);

        var objs1 = document.createElement('input');
        objs1.setAttribute('type', 'hidden');
        objs1.setAttribute('name', 'pet_id');
        objs1.setAttribute('value', <?=$pet_id?>);
        form.appendChild(objs1);

        form.setAttribute('method', 'post');
        form.setAttribute('action', "/walking/daily/graph.php");
        document.body.appendChild(form);
        form.submit(); 
    });



    $(".btn-single-item").click(function(){
        var form = document.createElement('form');
        var objs = document.createElement('input');
        objs.setAttribute('type', 'hidden');
        objs.setAttribute('name', 'year');
        objs.setAttribute('value', $(this).attr("id"));
        form.appendChild(objs);

        var objs1 = document.createElement('input');
        objs1.setAttribute('type', 'hidden');
        objs1.setAttribute('name', 'pet_id');
        objs1.setAttribute('value', <?=$pet_id?>);
        form.appendChild(objs1);

        var objs2 = document.createElement('input');
        objs2.setAttribute('type', 'hidden');
        objs2.setAttribute('name', 'cellNumber');
        objs2.setAttribute('value', cellNumber);
        form.appendChild(objs2)

        var objs3 = document.createElement('input');
        objs3.setAttribute('type','hidden');
        objs3.setAttribute('name', 'pet_photo');
        objs3.setAttribute('value','<?=$pet_thumb?>');
        form.appendChild(objs3);

        var objs4 = document.createElement('input');
        objs4.setAttribute('type','hidden');
        objs4.setAttribute('name', 'pet_name');
        objs4.setAttribute('value','<?=$pet_name?>');
        form.appendChild(objs4);




        form.setAttribute('method', 'post');
        form.setAttribute('action', "/walking/daily/history.php");
        document.body.appendChild(form);
        form.submit();
    });

    // 기기 체크
    function checkMobile2(){
        var varUA = navigator.userAgent.toLowerCase(); //userAgent 값 얻기
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

    function onBackWalkTop(device){
        const id	=  "back";
        const event = "0";
        if(device.indexOf("in_app_and") >-1){
            walking_daily_android.onBackWalkTop(id, event);
        }
        else if(device.indexOf("in_app_ios") > -1){
            let messages = {
                'id': "back",
                'event': "0"
            };
            webkit.messageHandlers.onBackWalkTop.postMessage(messages);
        }

    }
</script>
</body>


</html>

