


<!--  기본 메세지 팝업(버튼2) -->
<article id="pop2" class="layer-pop-wrap">
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
<!-- //기본 메세지 팝업(버튼2) -->
<script>
	
	$(document).ready(function(){
		$(".btn-page-prev").click(function(){
			//history.back();
		})
		
		$("#container").append(`
			<div class="page-bottom">
				<div class="common-navigation">
					<a href="/" class="btn-navigation-item navigation-1 <?=$userBottomActived[0]?>"><div class="icon"></div><div class="txt">홈</div></a>
					<a href="/reserve_main" class="btn-navigation-item navigation-2 <?=$userBottomActived[1]?>"><div class="icon"></div><div class="txt">예약</div></a>
					<a href="#" class="btn-navigation-item navigation-4">
						<div class="ani-icon">
							<div class="ani-icon-off"></div>
							<div class="ani-icon-on"></div>
						</div>
						<div class="icon"></div>
						<div class="txt">산책</div>
					</a>
					<a href="/shop_main" class="btn-navigation-item navigation-3 <?=$userBottomActived[3]?>"><div class="icon"></div><div class="txt">쇼핑</div></a>
					<a href="/mypage_main" class="btn-navigation-item navigation-5 <?=$userBottomActived[4]?>"><div class="icon"></div><div class="txt">마이반짝</div></a>
				</div>
			</div>
		`);
		
		$(".navigation-4").click(function(){
            var mobile = checkMobile2();
            if(mobile == "in_app_and" || mobile == "in_app_ios"){
                var usr_email	= '<?=$_SESSION['gobeauty_user_id']?>';
                SET_MoveMenu(2, usr_email);
            }else if(mobile == "android") {
                popalert.confirm('pop2', "산책 서비스는 반짝 앱에서 이용가능합니다.", "https://play.google.com/store/apps/details?id=m.kr.gobeauty");
            }else if(mobile == "ios"){
                popalert.confirm('pop2', "산책 서비스는 반짝 앱에서 이용가능합니다.", "https://apps.apple.com/kr/app/id1436568194");
            }else{
                window.location.href = "/walking/daily";
                /*
                $('#firstRequestMsg1').find('.msg-txt').text('산책 서비스는 반짝 앱에서 이용가능합니다.');
                pop.open('firstRequestMsg1');
                 */
            }

			/*
			if(usr_email==''){
				popalert.open('firstRequestMsg1', '로그인 후 이용해주세요.');
				return false;	
			}
			*/
			
		});
		
		/*
		if(typeof menu_idx !== 'undefined'){
			SET_MoveMenu(menu_idx, "");
		}else{
			SET_MoveMenu("0", "");	
		}
		//SET_MoveMenu("1", "");
		*/
		
		$(".common-navigation a").click(function(){
            var mobile = checkMobile2();
            if(mobile == "in_app_and" || mobile == "in_app_ios"){
                var idx	= $(".common-navigation a").index($(this))	;
                SET_MoveMenu(idx, '<?=$_SESSION['gobeauty_user_id']?>');
            }

		})
	});

    // 기기 체크
    function checkMobile2(){
        var varUA = navigator.userAgent.toLowerCase(); //userAgent 값 얻기
        if ( varUA.indexOf("app_gobeauty_and") > -1 ) {
            //APP
            return "in_app_and";
        } else if (varUA.indexOf("app_gobeauty_ios") > -1 ) {
            //안드로이드
            return "in_app_ios";
        } else if ( varUA.indexOf('android') > -1 ) {
            //안드로이드
            return "android";
        } else if ( varUA.indexOf("iphone") > -1||varUA.indexOf("ipad") > -1||varUA.indexOf("ipod") > -1 ) {
            //IOS
            return "ios";
        } else {
            //아이폰, 안드로이드 외
            return "other";
        }
    }

	
	// app에서 호출
	function SET_MoveMenu(idx, email){
		$(".common-navigation a").removeClass("actived");
		$(".common-navigation a:eq("+idx+")").addClass("actived");
		//alert(window.navigator.userAgent);
		
		var varUA = window.navigator.userAgent.toLowerCase(); //userAgent 값 얻기

		if(varUA.indexOf("android") >-1){
	        Banjjak_Android.SET_MoveMenu(idx, email);
		}
		else if(varUA.indexOf("iphone") > -1 || varUA.indexOf("ipad") > -1){
			// let messages = {
            //         			'idx': idx,
            //         			'email': email
            //     			};
	        // 	webkit.messageHandlers.SET_MoveMenu.postMessage(messages);
		}
	
	}
</script>

</body>
</html>
