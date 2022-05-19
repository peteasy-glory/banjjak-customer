<?php
include "../include/top.php";
include "../include/Crypto.class.php";
include "../Mobile_Detect.php";
include "../include/check_header_log.php";

$detect = new Mobile_Detect;
if ($detect->isMobile()) {
    ?>
    <!DOCTYPE HTML>
    <html>

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>반짝</title>
        <link rel="stylesheet" href="../css/index_pc_t.css">
    </head>

    <body>
        <header>
            <div class="wrap">
                <img class="logo" src="../images/logo_01.png" alt="타이틀 로고" />
            </div>
        </header>

        <div id="section-wrapper">
            <section class="for-companion section pc">
                <div class="wrap">
                    <div class="contents">
                        <section class="paragraph">
                            <h1 class="title">반려인 여러분!</h1>
                            <p>아직도 전화로 예약하세요?</p>
                            <br />
                            <p>국내유일, 1등 애견미용 예약앱</p>
                            <p>반짝으로 예약하세요</p>
                            <p class="small">(호텔 예약도 오픈 예정)</p>
                        </section>

                        <section class="download">
                            <h1 class="title">지금 다운로드 하세요~</h1>
                            <div class="link">
                                <div class="img-wrap">
                                    <img src="../images/logo_02.png" alt="앱 로고" />
                                </div>
                                <ul>
                                    <li><a href="https://play.google.com/store/apps/details?id=m.kr.gobeauty&hl=ko " target="_blank"><img src="/pet/images/gopetkr_image05newR.png" width="150px" alt="구글 플레이 링크 이미지" /></a></li>
                                    <li><a href="https://itunes.apple.com/kr/app/id1436568194" target="_blank"><img src="/pet/images/gopetkr_image06newR.png" width="150px" alt="애플 앱스토어 링크 이미지" /></a></li>
                                </ul>
                            </div>
                        </section>
                    </div>
                    <div class="multi-field image-field">
                        <img src="../images/img_banjjak_1.png" alt="앱 메인 이미지" />
                    </div>
                </div>
            </section>

            <section class="for-companion section mobile">
                <div class="wrap">
                    <div class="contents">
                        <section class="paragraph">
                            <h1 class="title">반려인 여러분!</h1>
                            <p>아직도 전화로 예약하세요?</p>
                            <br />
                            <p>국내유일, 1등 애견미용 예약앱</p>
                            <p>반으로 예약하세요</p>
                            <p class="small">(호텔 예약도 오픈 예정)</p>
                        </section>

                        <section class="download">
                            <h1 class="title">지금 다운로드 하세요~</h1>
                            <div class="link">
                                <div class="img-wrap">
                                    <img src="../images/logo_02.png" alt="앱 로고" />
                                </div>
                                <ul>
                                    <li><a href="https://play.google.com/store/apps/details?id=m.kr.gobeauty&hl=ko " target="_blank"><img src="/pet/images/gopetkr_image05newR.png" width="150px" alt="구글 플레이 링크 이미지" /></a></li>
                                    <li><a href="https://itunes.apple.com/kr/app/id1436568194" target="_blank"><img src="/pet/images/gopetkr_image06newR.png" width="150px" alt="애플 앱스토어 링크 이미지" /></a></li>
                                </ul>
                            </div>
                        </section>
                    </div>
                </div>
            </section>

            <section class="for-companion section mobile">
                <div class="wrap">
                    <div class="multi-field image-field">
                        <img src="../images/img_banjjak_1.png" alt="앱 메인 이미지" />
                    </div>
                </div>
            </section>

            <section class="for-owner section">
                <div class="wrap">
                    <div class="contents">
                        <section class="paragraph pc">
                            <h1 class="title">펫샵 사장님!!</h1>
                            <p>예약 접수, 고객 관리 프로그램</p>
                            <p>아직도 돈 내고 쓰세요?</p>
                            <br />
                            <p>반짝으로 오시면 무료!</p>
                            <br />
                            <p>App에서 입점 신청하세요~</p>
                            <p>지금 다운로드~</p>
                        </section>
                        <section class="paragraph mobile">
                            <h1 class="title">펫샵 사장님!!</h1>
                            <p>예약 접수, 고객 관리 프로그램 아직도 돈 내고 쓰세요?</p>
                            <br />
                            <p>반짝으로 오시면 무료!</p>
                            <br />
                            <p>App에서 입점 신청하세요~ 지금 다운로드~</p>
                        </section>
                    </div>
                    <div class="multi-field video-field">
                        <img src="../images/img_petshop.png">
                    </div>
                </div>
            </section>
            <footer class="section">
                <div class="wrap pc">
                    <div class="contents">
                        
                        <p>㈜펫이지(PetEasy Co.,Ltd) | 대표자:신동찬</p>
                        <p><a href="http://naver.me/FiH2GCJ7" target="_blank" class="underline">서울 노원구 동일로 174길 27 1층 Station :D</a></p><br>
                        <p>사업자등록번호 : 157-86-01070 <a href="http://www.ftc.go.kr/bizCommPop.do?wrkr_no=&apv_perm_no=2019322023630200169" target="_blank" class="underline">사업자 정보 확인</a></p>
                        <p>통신판매업 : 제 2019-서울노원-0540호</p><br>
                        <p>대표메일 : <a href="/cdn-cgi/l/email-protection#b2dad7dec2f2c2d7c6d7d3c1cb9cd9c0"><span class="__cf_email__" data-cfemail="7c1419100c3c0c1908191d0f0552170e">[email&#160;protected]</span></a></p><br>
                    </div>
                    <div class="multi-field image-field">
                        <img width="45%" src="../images/logo_02.png" alt="앱 로고" />
						<ul>
                            <li><a href="https://play.google.com/store/apps/details?id=m.kr.gobeauty&hl=ko " target="_blank"><img src="/pet/images/gopetkr_image05newR.png" width="150px" alt="구글 플레이 링크 이미지" /></a></li>
                            <li><a href="https://itunes.apple.com/kr/app/id1436568194" target="_blank"><img src="/pet/images/gopetkr_image06newR.png" width="150px" alt="애플 앱스토어 링크 이미지" /></a></li>
                        </ul>
                    </div>
                </div>
                <div class="wrap mobile">
                    <div class="multi-field image-field">
                        <img width="45%" src="../images/logo_02.png" alt="앱 로고" />
                        <ul>
                            <li><a href="https://play.google.com/store/apps/details?id=m.kr.gobeauty&hl=ko " target="_blank"><img src="/pet/images/gopetkr_image05newR.png" width="150px" alt="구글 플레이 링크 이미지" /></a></li>
                            <li><a href="https://itunes.apple.com/kr/app/id1436568194" target="_blank"><img src="/pet/images/gopetkr_image06newR.png" width="150px" alt="애플 앱스토어 링크 이미지" /></a></li>
                        </ul>
                    </div>
                    <div class="contents">
                        <p>㈜펫이지(PetEasy Co.,Ltd) | 대표자:신동찬</p>
                        <p><a href="http://naver.me/FiH2GCJ7" target="_blank" class="underline">서울 노원구 동일로 174길 27 1층 Station :D</a></p><br>
                        <p>사업자등록번호 : 157-86-01070 <a href="http://www.ftc.go.kr/bizCommPop.do?wrkr_no=&apv_perm_no=2019322023630200169" target="_blank" class="underline">사업자 정보 확인</a></p>
                        <p>통신판매업 : 제 2019-서울노원-0540호</p><br>
                        <p>대표메일 : <a href="/cdn-cgi/l/email-protection#48202d243808382d3c2d293b3166233a"><span class="__cf_email__" data-cfemail="cda5a8a1bd8dbda8b9a8acbeb4e3a6bf">[email&#160;protected]</span></a></p><br>
                    </div>
                </div>
            </footer>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        
    </body>
</html>


<?php
} else {
    ?>
    <!DOCTYPE HTML>
    <html>

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>반짝</title>
        <link rel="stylesheet" href="../css/index_pc.css">
    </head>

    <body>
        <header>
            <div class="wrap">
                <img class="logo" src="../images/logo_01.png" alt="타이틀 로고" />
            </div>
        </header>

        <div id="section-wrapper">
            <section class="for-companion section pc">
                <div class="wrap">
                    <div class="contents">
                        <section class="paragraph">
                            <h1 class="title">반려인 여러분!</h1>
                            <p>아직도 전화로 예약하세요?</p>
                            <br />
                            <p>국내유일, 1등 애견미용 예약앱</p>
                            <p>반짝으로 예약하세요</p>
                            <p class="small">(호텔 예약도 오픈 예정)</p>
                        </section>

                        <section class="download">
                            <h1 class="title">지금 다운로드 하세요~</h1>
                            <div class="link">
                                <div class="img-wrap">
                                    <img src="../images/logo_02.png" alt="앱 로고" />
                                </div>
                                <ul>
                                    <li><a href="https://play.google.com/store/apps/details?id=m.kr.gobeauty&hl=ko " target="_blank"><img src="/pet/images/gopetkr_image05newR.png" width="150px" alt="구글 플레이 링크 이미지" /></a></li>
                                    <li><a href="https://itunes.apple.com/kr/app/id1436568194" target="_blank"><img src="/pet/images/gopetkr_image06newR.png" width="150px" alt="애플 앱스토어 링크 이미지" /></a></li>
                                </ul>
                            </div>
                        </section>
                    </div>
                    <div class="multi-field image-field">
                        <img src="../images/img_banjjak_1.png" alt="앱 메인 이미지" />
                    </div>
                </div>
            </section>

            <section class="for-companion section mobile">
                <div class="wrap">
                    <div class="contents">
                        <section class="paragraph">
                            <h1 class="title">반려인 여러분!</h1>
                            <p>아직도 전화로 예약하세요?</p>
                            <br />
                            <p>국내유일, 1등 애견미용 예약앱</p>
                            <p>반으로 예약하세요</p>
                            <p class="small">(호텔 예약도 오픈 예정)</p>
                        </section>

                        <section class="download">
                            <h1 class="title">지금 다운로드 하세요~</h1>
                            <div class="link">
                                <div class="img-wrap">
                                    <img src="../images/logo_02.png" alt="앱 로고" />
                                </div>
                                <ul>
                                    <li><a href="https://play.google.com/store/apps/details?id=m.kr.gobeauty&hl=ko " target="_blank"><img src="/pet/images/gopetkr_image05newR.png" width="150px" alt="구글 플레이 링크 이미지" /></a></li>
                                    <li><a href="https://itunes.apple.com/kr/app/id1436568194" target="_blank"><img src="/pet/images/gopetkr_image06newR.png" width="150px" alt="애플 앱스토어 링크 이미지" /></a></li>
                                </ul>
                            </div>
                        </section>
                    </div>
                </div>
            </section>

            <section class="for-companion section mobile">
                <div class="wrap">
                    <div class="multi-field image-field">
                        <img src="../images/logo_02.png" alt="앱 메인 이미지" />
                    </div>
                </div>
            </section>

            <section class="for-owner section">
                <div class="wrap">
                    <div class="contents">
                        <section class="paragraph pc">
                            <h1 class="title">펫샵 사장님!!</h1>
                            <p>예약 접수, 고객 관리 프로그램</p>
                            <p>아직도 돈 내고 쓰세요?</p>
                            <br />
                            <p>반짝으로 오시면 무료!</p>
                            <br />
                            <p>App에서 입점 신청하세요~</p>
                            <p>지금 다운로드~</p>
                        </section>
                        <section class="paragraph mobile">
                            <h1 class="title">펫샵 사장님!!</h1>
                            <p>예약 접수, 고객 관리 프로그램 아직도 돈 내고 쓰세요?</p>
                            <br />
                            <p>반짝으로 오시면 무료!</p>
                            <br />
                            <p>App에서 입점 신청하세요~ 지금 다운로드~</p>
                        </section>
                    </div>
                    <div class="multi-field video-field">
                        <img src="../images/img_petshop.png">
                    </div>
                </div>
            </section>
            <footer class="section">
                <div class="wrap pc">
                    <div class="contents">
                        <p>㈜펫이지(PetEasy Co.,Ltd) | 대표자:신동찬</p>
                        <p><a href="http://naver.me/FiH2GCJ7" target="_blank" class="underline">서울 노원구 동일로 174길 27 1층 Station :D</a></p><br>
                        <p>사업자등록번호 : 157-86-01070 <a href="http://www.ftc.go.kr/bizCommPop.do?wrkr_no=1578601070" target="_blank" class="underline">사업자 정보 확인</a></p>
                        <p>통신판매업 : 제 2019-서울노원-0540호</p><br>
                        <p>대표메일 : <a href="mailto:help@peteasy.kr">help@peteasy.kr</a></p><br>
                    </div>
                    <div class="multi-field image-field">
                        <img width="45%" src="../images/logo_02.png" alt="앱 로고" />
                        <ul>
                            <li><a href="https://play.google.com/store/apps/details?id=m.kr.gobeauty&hl=ko " target="_blank"><img src="/pet/images/gopetkr_image05newR.png" width="150px" alt="구글 플레이 링크 이미지" /></a></li>
                            <li><a href="https://itunes.apple.com/kr/app/id1436568194" target="_blank"><img src="/pet/images/gopetkr_image06newR.png" width="150px" alt="애플 앱스토어 링크 이미지" /></a></li>
                        </ul>
                    </div>
                </div>
                <div class="wrap mobile">
                    <div class="multi-field image-field">
                        <img width="45%" src="../images/logo_02.png" alt="앱 로고" />
                        <ul>
                            <li><a href="https://play.google.com/store/apps/details?id=m.kr.gobeauty&hl=ko " target="_blank"><img src="/pet/images/gopetkr_image05newR.png" width="150px" alt="구글 플레이 링크 이미지" /></a></li>
                            <li><a href="https://itunes.apple.com/kr/app/id1436568194" target="_blank"><img src="/pet/images/gopetkr_image06newR.png" width="150px" alt="애플 앱스토어 링크 이미지" /></a></li>
                        </ul>
                    </div>
                    <div class="contents">
                        <p>㈜펫이지(PetEasy Co.,Ltd) | 대표자:신동찬</p>
                        <p><a href="http://naver.me/FiH2GCJ7" target="_blank" class="underline">서울 노원구 동일로 174길 27 1층 Station :D</a></p><br>
                        <p>사업자등록번호 : 157-86-01070 <a href="http://www.ftc.go.kr/bizCommPop.do?wrkr_no=1578601070" target="_blank" class="underline">사업자 정보 확인</a></p>
                        <p>통신판매업 : 제 2019-서울노원-0540호</p><br>
                        <p>대표메일 : <a href="mailto:help@peteasy.kr">help@peteasy.kr</a></p><br>
                    </div>
                </div>
            </footer>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    </body>
    </html>
<?php
}
?>