<?
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/skin/header.php");



?>
<!-- header -->
<header id="header">
    <div class="header-left">
        <a href="<?=$_SESSION['backurl1']?>" class="btn-page-ui btn-page-prev"><div class="icon icon-size-24 icon-page-prev">페이지 뒤로가기</div></a>
    </div>
    <div class="page-title" id="title"></div>
</header>
<!-- //header -->

<!-- container -->
<section id="container">
    <!-- page-body -->
    <div class="page-body fix-bottom-page" id="event">


    </div>
    <!-- //page-body -->
    <div class="common-bottom-ui left">
        <a href="shop_cart" class="btn-page-cart"><em>0</em></a>
    </div>
    <div class="common-bottom-ui right">
        <button type="button" id="btnPageTop" class="btn-page-top" onclick="common.pageMove(0);">상단 바로가기</button>
    </div>
</section>
<!-- //container -->
<script>



    // 세자리 숫자 콤마
    Number.prototype.format = function() {
        if (this == 0) return 0;

        var reg = /(^[+-]?\d+)(\d{3})/;
        var n = (this + '');

        while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');

        return n;
    };

    // 문자열 타입에서 쓸 수 있도록 format() 함수 추가
    String.prototype.format = function() {
        var num = parseFloat(this);
        if (isNaN(num)) return "0";

        return num.format();
    };



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
                            window.location.href = 'banjjakpet://campaign'


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
                        window.location.href = 'intent://campaign#Intent;scheme=banjjakpet;action=android.intent.action.VIEW;package=m.kr.gobeauty;end';
                    }, 500);


            } else {
                setTimeout(
                    function () {

                        window.location.href = 'banjjakpet://campaign';


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

                    location.href = 'banjjakpet://campaign'
                }

            }, 300);

            setTimeout(function(){



                location.href = `intent://${other_browser[1]}#Intent;scheme=http;package=com.android.chrome;end`;



            },500);

            $('.app-download-link').attr('href', 'market://details?id=m.kr.gobeauty');
        }
    }
</script>

</body>
</html>
