<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/include/global.php");
    $user_id = (isset($_SESSION['gobeauty_user_id']))? $_SESSION['gobeauty_user_id'] : "";
?>
<!---->
<!--    <a href="https://play.app.goo.gl/?link=https://play.google.com/store/apps/details?id=m.kr.gobeauty" id="app-link-market" ><strong style="display:none;">market</strong></a>-->
<!--    <a href="itms-apps://itunes.apple.com/kr/app/apple-store/id1436568194" id="app-link-appstore" ><strong  style="display:none;">appstore</strong></a>-->
<!---->
<!--    <a href="intent://walking#Intent;scheme=banjjakpet;action=android.intent.action.VIEW;package=m.kr.gobeauty;end" id="app-link-intent" ><strong  style="display:none;">intent</strong></a>-->
<!--    <a href="banjjakpet://walking" id="app-link-walking" ><strong  style="display:none;">walking</strong></a>-->
<!---->
<!---->
<!---->

    <!DOCTYPE html>
    <html lang="ko" class="">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <title>반짝 - 반려생활의 단짝</title>
        <meta name="format-detection" content="telephone=no">
        <meta name="facebook-domain-verification" content="663zro6ll5klfq6g4wte3d8w4ky8ps" />
        <meta property="og:description" content="미용•쇼핑•산책부터 반려 생활의 모든 것은 반짝에서!">
        <meta property="og:image" content="https://image.banjjakpet.com/images/meta_walking.jpg">
        <link rel="shortcut icon" type="image/x-icon" href="https://www.gopet.kr/pet/ico/favicon.ico" />
        <link rel="icon" type="image/x-icon" href="https://www.gopet.kr/pet/ico/favicon.png" />
        <link href="/static/pub/css/reset.css?v=<?=$cssVersion?>" rel="stylesheet"/>
        <link href="/static/pub/css/swiper.min.css?v=<?=$cssVersion?>" rel="stylesheet"/>
        <link href="/static/pub/css/jquery-ui.css?v=<?=$cssVersion?>" rel="stylesheet"/>
        <link href="/static/pub/css/common.css?v=<?=$cssVersion?>" rel="stylesheet"/>
        <link href="/static/pub/css/form.css?v=<?=$cssVersion?>" rel="stylesheet"/>
        <script src="/static/pub/js/jquery-3.4.1.min.js"></script>
        <script src="/static/pub/js/jquery-ui.min.js"></script>
        <script src="/static/pub/js/swiper.min.js"></script>
        <script src="/static/pub/js/common.js?v=<?=$jsVersion?>"></script>
        <script src="/static/pub/js/dev_common.js?v=<?=$jsVersion?>"></script>
        <script src="/static/pub/js/jquery.fileupload.js"></script>
        <script src="/static/pub/js/jquery.ui.widget.js"></script>

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-J9ENXG8BLD"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-J9ENXG8BLD');
        </script>

    </head>
    <!-- 로딩화면 -->
    <div id="loading" class="">
        <div class="loading-wrap">
            <div class="loading-bar">
                <div class="loading-obj">
                    <svg xmlns="http://www.w3.org/2000/svg">
                        <circle cx="50%" cy="50%" r="24"  class="background" stroke-linecap="butt"></circle>
                        <circle cx="50%" cy="50%" r="24"  class="yellow" stroke-linecap="butt" ></circle>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <script>
        // window.onload = function(){
        //     $('#loading').removeClass("actived");
        // }
    </script>
    <body>
    <!-- [필수사항]을(를) 입력해주세요.  -->
    <article id="firstRequestMsg1" class="layer-pop-wrap">
        <div class="layer-pop-parent">
            <div class="layer-pop-children">

                <div class="pop-data alert-pop-data">
                    <div class="pop-body">
                        <div class="msg-txt"></div>
                    </div>
                    <div class="pop-footer">
                        <button type="button" class="btn btn-confirm" onclick="pop.close();">확인</button>
                    </div>
                </div>

            </div>
        </div>
    </article>


    <!--  기본 메세지 팝업(버튼2) -->
    <!-- popalert.confirm('firstRequestMsg2', '안내문구', '확인시 이동url'); -->
    <article id="firstRequestMsg2" class="layer-pop-wrap">
        <div class="layer-pop-parent">
            <div class="layer-pop-children">

                <div class="pop-data alert-pop-data">
                    <div class="pop-body">
                        <div class="msg-txt">텍스트 입니다.</div>
                    </div>
                    <div class="pop-footer">
                        <button type="button" class="btn btn-confirm btn-cf">확인</button>
                        <button type="button" class="btn btn-confirm btn-cc" onclick="popalert.close();">취소</button>
                    </div>
                </div>

            </div>
        </div>
    </article>


<script>


    let userAgent = navigator.userAgent.toLowerCase();

    let visitedAt = (new Date()).getTime();





window.onload = function() {
    if (userAgent.match(/iphone|ipad|ipod/i)) {

        if (userAgent.match(/APP_GOBEAUTY_iOS/i)) {
            webkit.messageHandlers.SET_MoveMenu.postMessage(idx, email);
        } else {

            setTimeout(
                function () {
                    if ((new Date()).getTime() - visitedAt < 2000) {
                        window.location.href = 'banjjakpet://walking'


                    }
                }
                , 300);

            setTimeout(
                function () {
                    if ((new Date()).getTime() - visitedAt < 2000) {
                        window.location.href = 'itms-apps://itunes.apple.com/kr/app/apple-store/id1436568194'
                    }
                }
                , 500);
        }
    } else if (!userAgent.match(/kakao/i) && userAgent.match(/android/i)) {
        if (userAgent.match(/APP_GOBEAUTY_AND/i)) {
            Banjjak_Android.SET_MoveMenu(idx, email);
        }


        if (userAgent.match(/chrome/i)) {
            setTimeout(
                function () {

                    // document.getElementById('app-link-intent').click();
                    // $('#app-link-intent').get(0).click();
                    window.location.href = 'intent://walking#Intent;scheme=banjjakpet;action=android.intent.action.VIEW;package=m.kr.gobeauty;end';
                }, 500);


        } else {
            setTimeout(
                function () {

                    window.location.href = 'banjjakpet://walking';


                }, 500);
            setTimeout(
                function () {

                    if (new Date().getTime() - visitedAt < 2000) {
                        window.location.href = 'market://details?id=m.kr.gobeauty';
                    }
                }

                , 800);
            // }

        }


    }else if(userAgent.match(/kakao/i) && userAgent.match(/android/i)){

        // location.href = 'kakaotalk://inappbrowser/close';

        setTimeout(function () {

            if (new Date().getTime() - visitedAt < 2000) {

                location.href = 'banjjakpet://walking'
            }

        }, 300);

        setTimeout(function(){



            location.href = `intent://${other_browser[1]}#Intent;scheme=http;package=com.android.chrome;end`;



        },500);

        $('.app-download-link').attr('href', 'market://details?id=m.kr.gobeauty');
    }
}


</script>

<?
include($_SERVER['DOCUMENT_ROOT']."/include/skin/footer.php");
?>