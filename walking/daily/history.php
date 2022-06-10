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

//$api = new TRestAPI("https://walkapi.banjjakpet.com:8080");
$api = new TRestAPI("http://stg-walkapi.banjjakpet.com:8080", "token 58de28d6170dcf11edf7c009bff81e37536a2fa4");
//$api = new TRestAPI("http://192.168.20.128:8080", "token 58de28d6170dcf11edf7c009bff81e37536a2fa4");
$months_log = $api->get("/walklog/photo/".$user_id."/".$pet_id."/".$year."/".$month);

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

            <div class="gallery-pop-wrap">
                <div class="gallery-pop-inner">
                    <div class="gallery-pop-data">
                        <div class="gallery-pop-slider">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="slider-item">
                                            <span class="loading-bar"><span class="sk-fading-circle"><span class="sk-circle1 sk-circle"></span><span class="sk-circle2 sk-circle"></span><span class="sk-circle3 sk-circle"></span><span class="sk-circle4 sk-circle"></span><span class="sk-circle5 sk-circle"></span><span class="sk-circle6 sk-circle"></span><span class="sk-circle7 sk-circle"></span><span class="sk-circle8 sk-circle"></span><span class="sk-circle9 sk-circle"></span><span class="sk-circle10 sk-circle"></span><span class="sk-circle11 sk-circle"></span><span class="sk-circle12 sk-circle"></span></span></span>
                                            <img src="/static/pub/images/gate_picture.jpg" alt=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-page"></div>
                            <button type="button" class="btn-swiper-slider-prev"></button>
                            <button type="button" class="btn-swiper-slider-next"></button>
                        </div>
                        <div class="gallery-pop-ui">
                            <button type="button" class="btn-gallery-pop-nav btn-gallery-mode" onclick="gallery.viewModeChange(this);">
                                <span class="icon icon-size-24 icon-viewall-white off"></span>
                                <span class="icon icon-size-24 icon-viewmax-white on"></span>
                            </button>
                            <button type="button" class="btn-gallery-pop-nav" onclick="gallery.close();"><span class="icon icon-size-24 icon-close-white"></span></button>
                        </div>
                    </div>
                    <div class="gallery-thumb-data">
                        <div class="gallery-thumb-list">
                            <button type="button" class="btn-gallery-thumb-nav">
                                <span class="loading-bar"><span class="sk-fading-circle"><span class="sk-circle1 sk-circle"></span><span class="sk-circle2 sk-circle"></span><span class="sk-circle3 sk-circle"></span><span class="sk-circle4 sk-circle"></span><span class="sk-circle5 sk-circle"></span><span class="sk-circle6 sk-circle"></span><span class="sk-circle7 sk-circle"></span><span class="sk-circle8 sk-circle"></span><span class="sk-circle9 sk-circle"></span><span class="sk-circle10 sk-circle"></span><span class="sk-circle11 sk-circle"></span><span class="sk-circle12 sk-circle"></span></span></span>
                                <img src="/static/pub/images/user_thumb.png" alt="">
                            </button>
                        </div>
                    </div>
                </div>
            </div>

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
                            foreach($months_log['body'] as $val){


                                echo '<li class="accordion-cell">';
                                    if($val["track_map_path"] !== ""){
                                        echo '<button type="button" class="btn-accordion-menu btn-record-accordion photo-target">'.
                                            '<span class="btn-record-accordion-inner">'.
                                            '<span class="record-accordion-date">'.$val["date"].'</span>'.
                                            '<span class="record-accordion-option">'.number_format(intval($val["distance"])/1000,2).'Km'.', '.number_format(intval($val["time"])/60, 2).'분</span>'.
                                            '</span>'.
                                            '</button>'.
                                            '<span class="record-accordion-idx record-accordion-idx-'.$val["idx"].'">'.$val["idx"].'</span>'.
                                            '<div class="accordion-content">'.
                                            '<div class="record-accordion-data">';
                                    }else if($val["track_map_path"] == ""){
                                        echo '<button type="button" class="btn-accordion-menu-no-after btn-record-accordion btn-accordion-menu-no-padding">'.
                                            '<span class="btn-record-accordion-inner">'.
                                            '<span class="record-accordion-date">'.$val["date"].'</span>'.
                                            '<span class="record-accordion-option">'.number_format(intval($val["distance"])/1000,2).'Km'.', '.number_format(intval($val["time"])/60, 2).'분</span>'.
                                            '</span>'.
                                            '<svg xmlns="http://www.w3.org/2000/svg" width="23.756" height="27.351" viewBox="0 0 23.756 27.351">
                                              <g id="그룹_2" data-name="그룹 2" transform="translate(-322.25 -179.219)">
                                                <g id="_10_ic_36_shop_location" data-name="10_ic/36/shop_location" transform="translate(323 184.82)">
                                                  <g id="Group_2" data-name="Group 2">
                                                    <path id="Oval" d="M8,21s8-8.244,8-12.783A8.111,8.111,0,0,0,8,0,8.111,8.111,0,0,0,0,8.217C0,12.756,8,21,8,21Z" fill="#fff" stroke="#202020" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5"/>
                                                    <circle id="Oval-2" data-name="Oval" cx="2.5" cy="2.5" r="2.5" transform="translate(5.647 5.727)" fill="#fff" stroke="#202020" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5"/>
                                                  </g>
                                                </g>
                                                <path id="Stroke_1" data-name="Stroke 1" d="M0,6A6,6,0,1,0,6,0,6,6,0,0,0,0,6Z" transform="translate(328.187 188.552) rotate(-45)" fill="#fff" stroke="#202020" stroke-miterlimit="10" stroke-width="1.2"/>
                                                <path id="Stroke_3" data-name="Stroke 3" d="M0,0V7" transform="translate(334.197 186.078) rotate(-45)" fill="none" stroke="#ff4848" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5"/>
                                                <path id="Stroke_5" data-name="Stroke 5" d="M7,0H0" transform="translate(334.197 191.027) rotate(-45)" fill="none" stroke="#ff4848" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5"/>
                                              </g>
                                            </svg>'.
                                            '</button>';
                                    }

                                        if($val["track_map_path"] !== ""){
                                            echo    '<div class="record-accordion-detail"><img class="map-target" src="'.$val["track_map_path"].'" alt="">'.
                                                    '</div>'.
                                                    '<div class="record-accordion-header" style="background: #8f8f8f">'.
                                                        '<div class="item-sort">'.
                                                            '<div class="icon icon-size-24 icon-clock-small-white"></div>'.
                                                            '<div class="item-value">'.number_format(intVal($val["time"])/60,2).'분'.'</div>'.
                                                        '</div>'.
                                                        '<div class="item-sort">'.
                                                            '<div class="item-value">'.number_format(intVal($val["distance"])/1000,2).'Km'.'</div>'.
                                                        '</div>'.
                                                        '<div class="item-sort">'.
                                                            '<div class="icon icon-size-24 icon-defecate-small-white"></div>'.
                                                            '<div class="item-value">'.number_format($val["sum_poo"]+$val["sum_pee"]).'회'.'</div>'.
                                                        '</div>'.
                                                    '</div>'.
                                                    '<button type="button" class="test-button"></button>'.
                                                    '<button type="button" class="btn-record-kakao-share" data-map_url="'.$val["track_map_path"].'"></button>'.
                                            '</div>';



                                        }else if($val["track_map_path"] == ""){



                                            '</div>';


                                        }


                                echo '</li>';


                            }
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
<script src="/static/pub/js/imagesloaded.pkgd.min.js"></script>
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



    
    //산책일지 갤러리
    const target_id =" <?=$user_id ?>";
    const target_pet_id = <?= $pet_id ?>;
    const target_year = <?= $year?>;
    const target_month = <?= $month?>;


    $(".photo-target").on("click", function(e){



        let idx = $(this).siblings('span').text();

            $.ajax({
            url:'/data/walking_ajax.php',
            data:{
                mode:"getTPhoto",
                pet_id: target_pet_id,
                year : target_year,
                month : target_month,
                idx : idx,

            },
            type:"post",

            success: (data) => {
                let resultData = JSON.parse(data)
                if(resultData.code == "000000"){

                    let cnt = 0;
                    let photos = [];

                    for(cnt; cnt < resultData.data.length; cnt++) {

                        photos.push(resultData.data[cnt].path);
                    }

                    console.log(photos);



                    console.log($(this))


                    $(this).siblings(".accordion-content").find("button.test-button").attr("onclick",`showReviewGallery(${resultData.data.length-1},'${photos.toString()}')`)
                }
            },
            error:function(request,status,error){
                console.log("실패")
                console.log("error = " + error)
            },




        })
    })


    //갤러리

    let gallery = {

        element : null,
        swiper : null,
        swiperCur : 0,
        swiperLen : -1,

        init : function(){
            gallery.element = $('.gallery-pop-wrap');
            gallery.swiperLen = gallery.element.find('.swiper-slide').length;
            gallery.swiper = new Swiper( gallery.element.find('.swiper-container')[0] , {
                loop : false,
                slidesPerView : 1 ,
                spaceBetween : 0,
                simulateTouch : true,
                speed : 450,
                navigation: {
                    nextEl: gallery.element.find('.btn-swiper-slider-next')[0],
                    prevEl: gallery.element.find('.btn-swiper-slider-prev')[0]
                }
            });
            gallery.swiper.on('slideChange' , function(){
                gallery.swiperCur = this.realIndex;
                gallery.pageSort();
            });
            gallery.pageSort();

            $(document).on('click' , '.btn-gallery-thumb-nav' , function(){
                let $index = $(this).index();
                gallery.swiper.slideTo($index , 450);
            });
        },
        pageSort : function(){
            let _value = '<em>' + String((gallery.swiperCur + 1) + '</em> / ' + gallery.swiperLen);
            gallery.element.find('.swiper-page').html(_value);
            gallery.element.find('.gallery-thumb-list > .btn-gallery-thumb-nav').eq(gallery.swiperCur).addClass('actived').siblings().removeClass('actived');
        },

        dataSet : function(imgList){
            //샘플링 데이타
            // -> <div class="swiper-slide"><div class="slider-item"><img src="/static/pub/images/gate_picture.jpg" alt=""/></div></div>
            let i = 0;
            let len = Math.floor(Math.random() * (14 - 1)) + 1;

            let result = '';
            let resultThumb = '';
            for(i = 0; i < imgList.length; i++){

                result += '<div class="swiper-slide"><div class="slider-item hide">';
                result += '<span class="loading-bar"><span class="sk-fading-circle"><span class="sk-circle1 sk-circle"></span><span class="sk-circle2 sk-circle"></span><span class="sk-circle3 sk-circle"></span><span class="sk-circle4 sk-circle"></span><span class="sk-circle5 sk-circle"></span><span class="sk-circle6 sk-circle"></span><span class="sk-circle7 sk-circle"></span><span class="sk-circle8 sk-circle"></span><span class="sk-circle9 sk-circle"></span><span class="sk-circle10 sk-circle"></span><span class="sk-circle11 sk-circle"></span><span class="sk-circle12 sk-circle"></span></span></span>	';
                result += '<img src="'+imgList[i]+'" alt="" />';
                result += '</div></div>';

                resultThumb += '<button type="button" class="btn-gallery-thumb-nav hide">';
                resultThumb += '<span class="loading-bar"><span class="sk-fading-circle"><span class="sk-circle1 sk-circle"></span><span class="sk-circle2 sk-circle"></span><span class="sk-circle3 sk-circle"></span><span class="sk-circle4 sk-circle"></span><span class="sk-circle5 sk-circle"></span><span class="sk-circle6 sk-circle"></span><span class="sk-circle7 sk-circle"></span><span class="sk-circle8 sk-circle"></span><span class="sk-circle9 sk-circle"></span><span class="sk-circle10 sk-circle"></span><span class="sk-circle11 sk-circle"></span><span class="sk-circle12 sk-circle"></span></span></span>';
                resultThumb += '<img src="'+imgList[i]+'" alt="" />';
                resultThumb += '</button>';
            };

            //데이타 삽입
            gallery.element.find('.swiper-wrapper').html(result);
            gallery.element.find('.gallery-thumb-list').html(resultThumb);

            gallery.element.find('.swiper-wrapper .slider-item').each(function(){
                $(this).imagesLoaded().always(function(instance){
                    //console.log('model image loaded');
                }).done(function(instance){
                    $(instance.elements).removeClass('hide');
                }).fail( function(){
                    //alert('프로필 이미지가 없습니다.');
                }).progress(function(instance,image){

                });
            });

            gallery.element.find('.gallery-thumb-list .btn-gallery-thumb-nav').each(function(){
                $(this).imagesLoaded().always(function(instance){
                    //console.log('model image loaded');
                }).done(function(instance){
                    $(instance.elements).removeClass('hide');
                }).fail( function(){
                    //alert('프로필 이미지가 없습니다.');
                }).progress(function(instance,image){

                });
            });

            /*
            $('#heroModel').imagesLoaded().always(function(instance){
                //console.log('model image loaded');
            }).done(function(instance){
                $('#heroModel').removeClass('loading');
            }).fail( function(){
                //alert('프로필 이미지가 없습니다.');
            }).progress(function(instance,image){

            });
            */

            //데이타 삽입 후 재설정
            gallery.swiperCur = 0;
            gallery.swiperLen = i;

            //데이타 삽입 후 재정렬
            gallery.viewUpdate();
            gallery.pageSort();



        },

        open : function(){
            gallery.element.addClass('actived');
            gallery.viewUpdate();
            gallery.swiper.slideTo(0,0);
        },
        close : function(){
            gallery.element.removeClass('actived');
        },
        viewModeChange : function(obj){
            if($(obj).hasClass('actived')){
                //리스트 비활성화
                $(obj).removeClass('actived');
                gallery.element.removeClass('thumb');
            }else{
                //리스트 활성화
                $(obj).addClass('actived')
                gallery.element.addClass('thumb');
            }

            setTimeout(function(){
                if(gallery.swiper) gallery.viewUpdate();
            } , 300);
        },
        viewUpdate : function(){
            gallery.swiper.update();
            gallery.swiper.updateSize();
            gallery.swiper.updateSlides();
            gallery.swiper.updateProgress();
        }
    };
    $(function(){
        gallery.init();
    });

    function showReviewGallery(startIndex, img_list){
        let imgs	= img_list.split(',');
        imgs.forEach(element => {
            element = img_link_change(element);
        });
        console.log(imgs);
        gallery.dataSet(imgs);
        gallery.open(startIndex);
       3

  };

    //스크롤페이징
    let deviceHeight = window.screen.height;
    console.log("deviceHeight = " + deviceHeight);
    let listHeight = $(".accordion-list").height();
    console.log("listHieght =  " + listHeight);

    let cellHeight = $(".accordion-cell").eq(1).outerHeight(true);
    console.log("cellHeight = " + cellHeight)

    let headerGroupHeight = $("#header").outerHeight(true) + $(".con-title-group").outerHeight(true);
    console.log("headerGroupHeight = " + headerGroupHeight)


    let listHeightResult = (4+deviceHeight-headerGroupHeight-cellHeight*2)



    window.addEventListener("touchmove", function(){
        console.log(window.pageYOffset)
    })




</script>
	
</body>
</html>