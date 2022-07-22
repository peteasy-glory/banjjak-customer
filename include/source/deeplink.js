let user_agent = navigator.userAgent.toLowerCase();

let split_url = String(location.href).split('/');

let needed = split_url.at(-1);

if(needed === ''){

    needed = 'main';
}

console.log(needed);


let other_browser = String(location.href).split('//');

let visitedAt = (new Date()).getTime();


$('.layer-pop-jack').click(function (event) {
    event.stopPropagation();

})


$(document).ready(function(){

    alert("document.ready");
    if(getCookie_popup('anymore') !=='Y'){
        alert("anymore !==Y");
        if(user_agent.match(/iphone|ipad|ipod|safari/i)){ //ios 일때
            alert("match ios");


            if(user_agent.match(/APP_GOBEAUTY_iOS/i)){ //ios app 일때
                webkit.messageHandlers.SET_MoveMenu.postMessage(idx, email);
                alert("match ios app");
            }else{ //ios web 일때
                alert("match ios web");
                setTimeout( // 앱이 있으면 앱으로

                    function(){
                        if((new Date()).getTime() - visitedAt <2000){
                            location.href = `banjjakpet://${needed}`;
                        }
                    }
                    ,300);



                $('.app-download-link').attr('href','itms-apps://itunes.apple.com/kr/app/apple-store/id1436568194');


            }

        }else if(!user_agent.match(/kakao/i) && user_agent.match(/android/i)){ //안드로이드 일때

            if(user_agent.match(/APP_GOBEAUTY_AND/i)){ // 안드로이드 app 일때
                Banjjak_Android.SET_MoveMenu(idx, email);
            }else{ // 안드로이드 web 일때


                setTimeout(function(){

                    if((new Date()).getTime() - visitedAt < 2000){

                        location.href = `banjjakpet://${needed}`;
                    }

                },300);

                $('.app-download-link').attr('href','market://details?id=m.kr.gobeauty')

            }







        }else if(user_agent.match(/kakao/i) && user_agent.match(/android/i)){

            // location.href = 'kakaotalk://inappbrowser/close';

            setTimeout(function () {

                if (new Date().getTime() - visitedAt < 2000) {

                    location.href = `banjjakpet://${needed}`;
                }

            }, 300);

            setTimeout(function(){



                location.href = `intent://${other_browser[1]}#Intent;scheme=http;package=com.android.chrome;end`;



            },500);

            $('.app-download-link').attr('href', 'market://details?id=m.kr.gobeauty');
        }
    }
})