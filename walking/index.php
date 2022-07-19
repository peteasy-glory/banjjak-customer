<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/include/global.php");
    include($_SERVER['DOCUMENT_ROOT'] . "/include/skin/header.php");
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
    } else if (userAgent.match(/android/i)) {
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


    }
}


</script>

<?
include($_SERVER['DOCUMENT_ROOT']."/include/skin/footer.php");
?>