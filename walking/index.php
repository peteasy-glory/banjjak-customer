<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/include/global.php");
    include($_SERVER['DOCUMENT_ROOT'] . "/include/skin/header.php");
    $user_id = (isset($_SESSION['gobeauty_user_id']))? $_SESSION['gobeauty_user_id'] : "";
?>
<!--    <a href="market://details?id=m.kr.gobeauty" id="app-link" ><strong >테스트</strong></a>-->
<script>

    let visitedAt = (new Date()).getTime();




    $(document).ready(function(){

        let userAgent = navigator.userAgent.toLowerCase();

        if(userAgent.match(/iphone|ipad|ipod/i)){
            if(userAgent.match(/APP_GOBEAUTY_iOS/i)){
                webkit.messageHandlers.SET_MoveMenu.postMessage(idx, email);
            }else{
                setTimeout(
                    function(){
                        if((new Date()).getTime() - visitedAt < 2000){
                            window.location.href = 'itms-apps://itunes.apple.com/kr/app/apple-store/id1436568194';
                        }
                    }
                    ,1500);

                setTimeout(
                    function(){
                        window.location.href = 'banjjakpet://walking';
                    }
                    ,0);
            }


        }else if(userAgent.match(/android/i)){

            if(userAgent.match(/APP_GOBEAUTY_AND/i)){
                Banjjak_Android.SET_MoveMenu(idx, email);
            }



            if(userAgent.match(/chrome/i)){
                setTimeout(
                    function(){
                        window.location.href = "intent://walking#Intent;scheme=banjjakpet;action=android.intent.action.VIEW;package=m.kr.gobeauty;end";
                    },500);

            }else{
                setTimeout(
                    function(){
                        if((new Date()).getTime() - visitedAt < 2000){
                            // location.href = "https://play.google.com/store/apps/details?id=m.kr.gobeauty";
                            window.location.href = "https://play.app.goo.gl/?link=https://play.google.com/store/apps/details?id=m.kr.gobeauty";
                        }
                    } ,1500);






            }

        }else{
            if('<?=$user_id?>' === '')
                window.location.href = "/login_1"
            else
                window.location.href = "/main"
        }


    })





    //$('#app-link').on('click',function(){
    //    var varUA = window.navigator.userAgent.toLowerCase(); //userAgent 값 얻기
    //    if(varUA.indexOf("android") >-1){
    //        if( varUA.indexOf("APP_GOBEAUTY_AND") > -1){
    //            Banjjak_Android.SET_MoveMenu(idx, email);
    //        }else{
    //            setTimeout(
    //                function(){
    //                    if((new Date()).getTime() - visitedAt < 2000){
    //                        location.href = "https://play.google.com/store/apps/details?id=m.kr.gobeauty";
    //                    }
    //                } ,1500);
    //
    //            setTimeout(
    //                function(){
    //                    location.href = "intent://walking#Intent;scheme=banjjakpet;action=android.intent.action.VIEW;package=m.kr.gobeauty;end";
    //                },0);
    //        }
    //    }else if(varUA.indexOf("iphone") > -1 || varUA.indexOf("ipad") > -1){
    //
    //        if (varUA.indexOf("APP_GOBEAUTY_iOS") > -1) {
    //            webkit.messageHandlers.SET_MoveMenu.postMessage(idx, email);
    //        }else {
    //            setTimeout(
    //                function(){
    //                    if((new Date()).getTime() - visitedAt < 2000){
    //                        location.href = 'itms-apps://itunes.apple.com/kr/app/apple-store/id1436568194';
    //                    }
    //                }
    //                ,1500);
    //
    //            setTimeout(
    //                function(){
    //                    location.href = 'banjjakpet://walking';
    //                }
    //                ,0);
    //        }
    //    }else{
    //        if('<?//=$user_id?>//' === '')
    //            location.href = "/login_1"
    //        else
    //            location.href = "/main"
    //    }
    //});
</script>

<?
include($_SERVER['DOCUMENT_ROOT']."/include/skin/footer.php");
?>