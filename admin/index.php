<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login.php");
include($_SERVER['DOCUMENT_ROOT']."/include/skin/header.php");
?>



<?php
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
?>
<link rel="stylesheet" href="m_new.css">
<style>
@font-face {font-family: 'BMJUA';src: url("../fonts/BMJUA.ttf");}
.top_menu { z-index: 6; }

.pna_cnt{background-color:red; font-size:15px; padding:3px; border-radius:5px; color:white; margin:0px 4px 0px; color:white;}
</style>
<header id="header">
    <div class="header-left">
        <a href="../mypage_main" class="btn-page-ui btn-page-prev"><div class="icon icon-size-24 icon-page-prev">페이지 뒤로가기</div></a>
    </div>
    <div class="page-title">관리자모드</div>
</header>

<?php
//////////////// 1대1 문의 답변 카운트 시작

// 문의 갯수 || 20211005 대표님 요청 1로 등록된 문의글 안보이게 처리 by glory (AND NOT title = '1')
$pna_sql = "SELECT COUNT(*) AS pna_cnt FROM tb_1vs1_pna WHERE pna_seq > 400 AND NOT title = '1'";
$pna_result = mysqli_query($connection,$pna_sql);
$pna_datas = mysqli_fetch_object($pna_result);
$pna_cnt = $pna_datas->pna_cnt;

// 답변 갯수
$pna_sub_sql = "SELECT COUNT(*) AS pna_sub_cnt FROM tb_1vs1_pna_sub WHERE sub_seq > 323";
$pna_sub_result = mysqli_query($connection,$pna_sub_sql);
$pna_sub_datas = mysqli_fetch_object($pna_sub_result);
$pna_sub_cnt = $pna_sub_datas->pna_sub_cnt;

// 신규 문의 갯수
$pna_cnt_result = $pna_cnt - $pna_sub_cnt;
//////////////// 1대1 문의 답변 카운트 끝

$login_insert_sql = "select * from tb_customer where id = '" . $user_id . "' and ( admin_flag = true or operator_flag = true);";
$result = mysqli_query($connection,$login_insert_sql);

if ($result_datas = mysqli_fetch_object($result)) {
    $nickname = $result_datas->nickname;
    $photo = $result_datas->photo;
    $cellphone = $result_datas->cellphone;
    $cellphone_confirm = $result_datas->cellphone_confirm;
    $id = $result_datas->id;
    $email_confirm = $result_datas->email_confirm;
    $mileage = $result_datas->mileage;
    $my_shop_flag = $result_datas->my_shop_flag;
    $push_option = $result_datas->push_option;
    $admin_flag = $result_datas->admin_flag;
    $operator_flag = $result_datas->operator_flag;
?>
    <section id="manager_set" style="margin-top: 57px;">
        <?php
        if ($admin_flag || $operator_flag) {

            $is_bank_account = 0;
            $is_request_artist = 0;
            $is_shop_open = 0;
            $is_1vs1 = 0;
            $is_report = 0;
            $is_change_user = 0;
            $is_fake_review = 0;
            $is_push_to_all = 0;
            $is_new_customer = 0;
            $is_user = 0;
            $is_region = 0;
            $is_notice = 0;
            $is_statistics = 0;
            $is_pet_shop = 0;
            $is_balance_accounts = 0;
            $is_payment_logs = 0;
            $is_point_logs = 0;
            $is_public_holiday = 0;
            $is_admin_user = 0;
            $is_point = 0;
            $is_recommend = 0;

            $sqqql = "select * from tb_operator_management;";
            $sqqq_result = mysqli_query($connection,$sqqql);
            if ($sq_rows = mysqli_fetch_object($sqqq_result)) {
                $is_bank_account = $sq_rows->is_bank_account;
                $is_request_artist = $sq_rows->is_request_artist;
                $is_shop_open = $sq_rows->is_shop_open;
                $is_1vs1 = $sq_rows->is_1vs1;
                $is_report = $sq_rows->is_report;
                $is_change_user = $sq_rows->is_change_user;
                $is_fake_review = $sq_rows->is_fake_review;
                $is_push_to_all = $sq_rows->is_push_to_all;
                $is_new_customer = $sq_rows->is_new_customer;
                $is_user = $sq_rows->is_user;
                $is_region = $sq_rows->is_region;
                $is_notice = $sq_rows->is_notice;
                $is_statistics = $sq_rows->is_statistics;
                $is_pet_shop = $sq_rows->is_pet_shop;
                $is_balance_accounts = $sq_rows->is_balance_accounts;
                $is_payment_logs = $sq_rows->is_payment_logs;
                $is_point_logs = $sq_rows->is_point_logs;
                $is_public_holiday = $sq_rows->is_public_holiday;
                $is_admin_user = $sq_rows->is_admin_user;
                $is_point = $sq_rows->is_point;
                $is_recommend = $sq_rows->is_recommend;
            }
        ?>

            <?php
            if ($admin_flag || $is_bank_account) {
            ?>
                <a href="manage_bank_payment.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>계좌이체 결제 관리</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="/images/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag || $is_request_artist) {
            ?>
                <a href="<?= $admin_directory ?>/manage_request_artist.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>반짝 입점 신청 관리</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag || $is_shop_open) {
            ?>
                <a href="<?= $admin_directory ?>/manage_request_open_shop.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>반짝 SHOP 오픈 신청 관리</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag || $is_request_artist) {
            ?>
                <a href="<?=$admin_directory?>/main_banner_list.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>메인페이지 관리</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag || $is_request_artist) {
            ?>
                <a href="<?=$admin_directory?>/item_list.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>상품 관리</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
			<?php
            if ($admin_flag || $is_request_artist) {
            ?>
                <a href="<?=$admin_directory?>/item_search_manage.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>인기검색어 관리</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
			<?php
            if ($admin_flag || $is_request_artist) {
            ?>
                <a href="<?=$admin_directory?>/item_special_mall_list.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>전문몰 상품 관리</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
			<?php
            if ($admin_flag || $is_request_artist) {
            ?>
                <a href="<?=$admin_directory?>/item_event.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>상품 판매 이벤트 관리</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag || $is_request_artist) {
            ?>
                <a href="<?=$admin_directory?>/item_payment_log.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>상품 결제 관리</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag || $is_request_artist) {
            ?>
                <a href="<?=$admin_directory?>/item_special_mall_payment_log.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>전문몰 상품 결제 관리</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag) {
            ?>
                <a href="<?= $admin_directory ?>/item_shop_entry_list.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>입점/제휴/광고 문의 내역</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag) {
            ?>
                <a href="<?= $admin_directory ?>/item_partner_list.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>파트너(반짝몰, 전문몰) 계정 관리</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag || $is_1vs1) {
            ?>
                <a href="<?= $admin_directory ?>/manage_1vs1_reply_2.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>1대1 문의 답글달기 <span class="pna_cnt"> <?=$pna_cnt_result ?> </span></td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag || $is_report) {
            ?>

                <a href="<?= $admin_directory ?>/manage_report.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>신고된 글 관리하기</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag || $is_change_user) {
            ?>
                <a href="<?= $admin_directory ?>/manage_change_user.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>사용자 전환<font color="red">(앱에서 금지)</font>
                                    <font style="font-size:11px;">앱에서 접속하면 푸시를 뺏어옵니다.</font>
                                </td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag || $is_fake_review) {
            ?>
                <a href="<?= $admin_directory ?>/manage_fake_review.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>후기 작성하기</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag || $is_push_to_all) {
            ?>
                <a href="<?= $admin_directory ?>/manage_push_to_all.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>푸시 메시지</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag || $is_new_customer) {
            ?>
                <a href="<?= $admin_directory ?>/manage_new_customer.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>임시 신규 사용자 추가</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag || $is_user) {
            ?>
                <a href="<?= $admin_directory ?>/manage_user.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>가입자 정보</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag || $is_region) {
            ?>
                <a href="<?= $admin_directory ?>/manage_region.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>검색 영업지역 오픈 관리</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag || $is_notice) {
            ?>
                <a href="<?= $admin_directory ?>/manage_notice.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>펫샵 공지사항 관리</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag || $is_statistics) {
            ?>
                <a href="<?= $admin_directory ?>/manage_statistics.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>통 계</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag || $is_pet_shop) {
            ?>
                <!--a href="<?= $admin_directory ?>/manage_pet_shop.php"-->
				<a href="<?= $admin_directory ?>/manage_artist_list.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <!--td>펫샵 오픈 관리</td-->
								<td>펫샵 회원 조회/오픈 관리(PC)</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag || $is_recommend) {
            ?>
                <a href="<?= $admin_directory ?>/bjj_status_board.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>등록 현황</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
			/*
            if ($admin_flag || $is_recommend) {
            ?>
                <a href="<?= $admin_directory ?>/manage_recommend_shop.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>추천 펫샵 설정</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
			*/
            ?>
            <?php
            if ($admin_flag || $is_balance_accounts) {
            ?>
                <a href="<?= $admin_directory ?>/manage_balance_accounts.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>정산(PC)</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag || $is_payment_logs) {
            ?>
                <a href="<?= $admin_directory ?>/manage_payment_logs_2.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>구매정보 조회(PC)</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>

                <!--a href="<?= $admin_directory ?>/manage_point.php">
                <div class="my_menu_div">
                        <table class="my_shop_text"><tr><td>포인트 조회</td></tr></table>
                        <table class="my_menu_img2"><tr><td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td></tr></table>
                </div>
                </a-->
            <?php
            }
            ?>
            <?php
            if ($admin_flag || $is_payment_logs) {
            ?>
                <a href="<?= $admin_directory ?>/manage_customer_list.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>정회원 거래통계 조회(PC)</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
			<?php
            if ($admin_flag) {
            ?>
                <a href="<?= $admin_directory ?>/beauty_item_payment.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>정회원 쇼핑구매통계 조회</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag || $is_point_logs) {
            ?>
                <a href="<?= $admin_directory ?>/manage_point_logs.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>포인트 사용내역 조회(PC)</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
			 <?php
            if ($admin_flag || $is_point_logs) {
            ?>
                <a href="<?= $admin_directory ?>/stats_allim.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>알림톡 통계</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag || $is_public_holiday) {
            ?>
                <a href="<?= $admin_directory ?>/manage_public_holiday.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>공휴일 관리</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag) {
            ?>
                <a href="<?= $admin_directory ?>/manage_admin_user.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>운영자 관리</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
            ?>
            <?php
            if ($admin_flag || $is_point) {
            ?>
                <a href="<?= $admin_directory ?>/manage_point.php">
                    <div class="my_menu_div">
                        <table class="my_shop_text">
                            <tr>
                                <td>포인트 추가</td>
                            </tr>
                        </table>
                        <table class="my_menu_img2">
                            <tr>
                                <td><img src="<?= $image_directory ?>/setting2.png" width="20px"></td>
                            </tr>
                        </table>
                    </div>
                </a>
            <?php
            }
        }
        ?>

    </section>

<?php
}
?>


