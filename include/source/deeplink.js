function getCookie_deep(name) {
    var obj = name + "=";
    var x = 0;
    while (x <= document.cookie.length) {
        var y = (x + obj.length);
        if (document.cookie.substring(x, y) == obj) {
            if ((endOfCookie = document.cookie.indexOf(";", y)) == -1)
                endOfCookie = document.cookie.length;
            return unescape(document.cookie.substring(y, endOfCookie));
        }
        x = document.cookie.indexOf(" ", x) + 1;
        if (x == 0)
            break;
    }
    return "";
}



let user_agent = navigator.userAgent.toLowerCase();



let needed ;

if(location.pathname === '/'){

    needed = '/main';
}else{

    needed = location.pathname + location.search;
}



let other_browser = String(location.href).split('//');

let visitedAt = (new Date()).getTime();



window.onload = function(){

    //window 거나 and app 이거나 ios app 인경우 다운로드 popup 비활성화
    if(!!document.getElementById('appDownloadPopup')){


        if(!user_agent.match(/app_gobeauty_and/i) && !user_agent.match(/app_gobeauty_ios/i) && !user_agent.match(/windows/i)){

            if(getCookie_deep('appDownloadpopup') !== 'Y'){

                setTimeout(function(){
                    document.getElementById('appDownloadPopup').classList.add('actived');
                },1500);
            }



        }



    if($('#appDownloadTop').hasClass('actived') && getCookie_deep('appDownloadTop') === 'Y'){

        document.getElementById('appDownloadTop').classList.remove('actived');
    }

    $('.layer-pop-jack').click(function (event) {
        event.stopPropagation();

    })


    if (user_agent.match(/iphone|ipad|ipod/i)) {




        $('.app-download-link').on('click',function (){

            location.href = `banjjakpet:/${needed}`;

            setTimeout(()=>{

                location.href = `itms-apps://itunes.apple.com/kr/app/apple-store/id1436568194`;
            },1500)

        })
    }else{

        $('.app-download-link').on('click',function(){

            location.href = `intent:/${needed}#Intent;scheme=banjjakpet;action=android.intent.action.VIEW;package=m.kr.gobeauty;end`;

        })

    }


    }





    if(!document.referrer.match(/banjjakpet/i)){

        if(getCookie_deep('anymore') !=='Y') {

            if (user_agent.match(/iphone|ipad|ipod/i)) { //ios 일때


                if (user_agent.match(/APP_GOBEAUTY_iOS/i)) { //ios app 일때
                    webkit.messageHandlers.SET_MoveMenu.postMessage(idx, email);

                } else { //ios web 일때

                    setTimeout( // 앱이 있으면 앱으로

                        function () {
                            if ((new Date()).getTime() - visitedAt < 2000) {
                                location.href = `banjjakpet:/${needed}`;
                            }
                        }
                        , 300);





                }

            } else if (!user_agent.match(/kakao/i) && user_agent.match(/android/i)) { //안드로이드 일때

                if (user_agent.match(/APP_GOBEAUTY_AND/i)) { // 안드로이드 app 일때
                    Banjjak_Android.SET_MoveMenu(idx, email);
                } else { // 안드로이드 web 일때


                    setTimeout(function () {

                        if ((new Date()).getTime() - visitedAt < 2000) {

                            location.href = `intent:/${needed}#Intent;scheme=banjjakpet;action=android.intent.action.VIEW;package=m.kr.gobeauty;end`;
                        }

                    }, 300);



                }


            } else if (user_agent.match(/kakao/i) && user_agent.match(/android/i)) {

                // location.href = 'kakaotalk://inappbrowser/close';

                setTimeout(function () {

                    if (new Date().getTime() - visitedAt < 2000) {

                        location.href = `intent:/${needed}#Intent;scheme=banjjakpet;action=android.intent.action.VIEW;package=m.kr.gobeauty;end`;
                    }

                }, 300);

                setTimeout(function () {


                    location.href = `intent://${other_browser[1]}#Intent;scheme=http;package=com.android.chrome;end`;


                }, 500);


            }

        }
    }

}
