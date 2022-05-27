<!DOCTYPE html>
<html lang="ko" class="">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<title>반짝 - 반려생활의 단짝</title>
	<meta name="format-detection" content="telephone=no">
    <meta name="description" content="미용•쇼핑•산책부터 반려 생활의 모든 것은 반짝에서!">
    <meta name="facebook-domain-verification" content="663zro6ll5klfq6g4wte3d8w4ky8ps" />
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

<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '370029338412107');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=370029338412107&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->

</head>
<!-- 로딩화면 -->
<!--<div id="loading" class="actived">-->
<!--    <div class="loading-wrap">-->
<!--        <div class="loading-bar">-->
<!--            <div class="loading-obj">-->
<!--                <svg xmlns="http://www.w3.org/2000/svg">-->
<!--                    <circle cx="50%" cy="50%" r="24"  class="background" stroke-linecap="butt"></circle>-->
<!--                    <circle cx="50%" cy="50%" r="24"  class="yellow" stroke-linecap="butt" ></circle>-->
<!--                </svg>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
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
