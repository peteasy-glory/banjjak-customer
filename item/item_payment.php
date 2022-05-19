<?php
include "../include/top.php";
include "../include/Crypto.class.php";
include "../Mobile_Detect.php";
include "../include/App.class.php";
include "../include/mobile_check.php";
$obj = new module();

$r_no = ($_GET['no'] && $_GET['no'] != "")? $_GET['no'] : "";
$backurl = $_GET['backurl'];
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

//비회원도 결제 가능하도록 오픈
/*
$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}
*/

if($user_id != ""){
	if(strpos($user_id, '@') !== false) {  
		$user_email = explode('@', $user_id);
	}
	$sql = "
		SELECT *
		FROM tb_customer
		WHERE id = '".$user_id."'
	";
	$result = mysql_query($sql);
	$user_row = mysql_fetch_assoc($result);
	$Crypto = new Crypto();
	$cellphone = $Crypto->decode($user_row['cellphone'], $access_key, $secret_key);
}

$is_android = 0;
$app = new App();
if ($app->is_app() == 1) 
{
    $is_android = 1;
}

$user_agent = $_SERVER['HTTP_USER_AGENT'];
$is_ios = 0;
if ($user_agent) 
{
    $token_index = strpos($user_agent, "APP_GOBEAUTY_iOS");
    if ($token_index > 0) 
    {
        $is_ios = 1;
        $is_android = 1; //is_android 속성을 많이 사용하고 있어서 일단 Android앱으로 판단후 iOS로 구분한다.
    }
}

if($r_no == ""){
?>
    <script>
        $.MessageBox({
            buttonDone: "확인",
            message: "잘못된 접근입니다. 메인페이지로 이동합니다."
        }).done(function() {
            location.href = "<?= $mainpage_directory ?>/index.php";
        });
    </script>
<?php
	exit;
}

$email_arr = array(
	"gmail.com", 
	"naver.com", 
	"daum.net", 
	"kakao.com", 
	"hotmail.net"
);

$shipping_memo_arr = array(
	"부재시 경비실에 맡겨주세요.", 
	"부재시 휴대폰으로 연락바랍니다.", 
	"집 앞에 놓아주세요.", 
	"택배함에 넣어주세요."
);


// Mobile
//$INI_shopNumber = ""; // 발급ID - 실거래
$INI_shopNumber = "banjjak001"; // 발급ID - 테스트
$INI_host = "mobile.inicis.com";
$INI_url = array(
	"card"		=> "https://".$INI_host."/smart/payment/",	// 신용카드
	"bank"		=> "https://".$INI_host."/smart/payment/",	// 계좌이체
	"vbank"		=> "https://".$INI_host."/smart/payment/",	// 가상계좌
	"moblie"	=> "https://".$INI_host."/smart/payment/",	// 휴대폰
	"culture"	=> "https://".$INI_host."/smart/culture/",	// 문화상품권
	"hpmn"		=> "https://".$INI_host."/smart/hpmn/",		// 해피머니상품권
	"dgcl"		=> "https://".$INI_host."/smart/dgcl/"		// 스마트문상
);
$siteDomain = "https://www.gopet.kr/pet"; //가맹점 도메인 입력

?>

<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script src="../js/fontawesome.min.js"></script>
<!-- 상용 JS(가맹점 MID 변경 시 주석 해제, 테스트용 JS 주석 처리 필수!) -->
<script language="javascript" type="text/javascript" src="https://stdpay.inicis.com/stdjs/INIStdPay.js" charset="UTF-8"></script>

<!-- 테스트 JS(샘플에 제공된 테스트 MID 전용) -->
<!--script language="javascript" type="text/javascript" src="https://stgstdpay.inicis.com/stdjs/INIStdPay.js" charset="UTF-8"></script-->

<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<style>
	.top_menu { position: fixed; left: 0px; top: 0px; width: 100%; background-color: rgba(255,255,255,0.8); z-index: 2; }
	#item_order_sheet { margin-top: 61px; }
#customer_addr_wrap .order_table { 
	width: 100%;
}

#customer_addr_wrap .order_table tr td { 
    padding:5px;
	border: 1px solid #ccc;
}

#customer_addr_wrap .order_table tr td.order_right {
	background-color: #f9f9f9;
	text-align: right;
	padding-right: 5px;
}

#customer_addr_wrap .order_table tr td input[type="text"] {
	border: 1px solid #ccc;
    border-radius: 5px;
    width: 90%;
    height: 20px;
    line-height: 20px;
    background-color: #FFF;
    padding: 5px;
    font-size: 14px;
}

#customer_addr_wrap .order_table tr td input[type="text"].input2 {
    width: 38%;
}
#item_order_sheet .shipping_addr_wrap .customer_addr_list_btn { margin-left: 10px; }
#item_order_sheet .direct_card { display:none; }
#item_order_sheet .direct_card .yes_payment_naverpay { width: 100%; height: 40px; line-height: 40px; padding: 0px; text-align: center; border: 1px solid #00bd39; background-color: #00c73c; color: #fff; font-family: '돋움', Dotum, Helvetica, sans-serif; font-size: 14px; margin: 2px 0px; }
#item_order_sheet .direct_card .yes_payment_naverpay>span { position: static; display:inline-block; background-image: url('../images/npay_sp_payment.png'); background-size:46px 80px; background-position: 50% 0; width: 46px; height: 40px; vertical-align: top; }
#item_order_sheet .direct_card .yes_payment_kakaopay { width: 100%; height: 40px; line-height: 40px; padding: 0px; text-align: center; border: 1px solid #FFEB00; background-color: #FFEB00; color: #000; font-family: '돋움', Dotum, Helvetica, sans-serif; font-size: 14px; margin: 2px 0px; }
#item_order_sheet .direct_card .yes_payment_kakaopay>span { position: static; display:inline-block; background-image: url('../images/payment_icon_yellow_small.png'); background-position: center; background-size: 60px; background-repeat: no-repeat; width: 55px; height: 40px; vertical-align: top; margin-left: -4px; }
#item_order_sheet .use_point { display: none; }
#item_order_sheet .event_first_payment_point { display: none; }
#item_order_sheet .event_first_payment_point td { background-color: #ddd; color: #f00; text-align: right; }
#item_order_sheet .event_first_payment_point td>div { padding-right: 20px; }
#item_order_sheet .order_box .order_table tbody tr.gray_bg table tr td input.point[type="number"] { text-align: right; border: 1px solid #ccc; border-radius: 5px; width: 60%; height: 20px; line-height: 20px; background-color: #FFF; padding: 5px; font-size: 14px; }

.messagebox_overlay { z-index: 900; }
#item_payment .pay_type_wrap tbody { display: none; }
#item_payment .pay_type_wrap tbody.on { display: table-row-group; }
#item_payment .customer_email_suffix_list { width: calc(100% - 16px); height: 30px; line-height: 30px; padding: 5px; border-radius: 5px; border: 1px solid #ccc; }
#item_payment .shipping_info tr td { position: relative; }
#item_payment .shipping_info tr td span.same_order_name { position: absolute; right: 0px; bottom: 0px; }
#item_payment .shipping_memo_list { width: calc(100% - 14px); height: 30px; line-height: 30px; border: 1px solid #ccc; background-color: transparent; border-radius: 5px; margin-bottom: 5px; }
#item_order_sheet .order_data_wrap { font-family: 'NL2GR'; }
#item_order_sheet .order_data_wrap .cart_data { vertical-align: top; }
#item_order_sheet .order_data_wrap .cart_data>div { margin: 2px 0px; }
#item_order_sheet .order_data_wrap .cart_data>div.item_name { font-weight: Bold; padding-bottom: 5px; }
#item_order_sheet .order_data_wrap .cart_data>div.item_option { font-size: 12px; }
#item_order_sheet .order_data_wrap .cart_data>div.item_free_shipping { font-size: 12px; color: #999; padding-top: 5px; }
#item_order_sheet .order_data_wrap .item .product_img>div { width: 100px;height: 100px;background-color: #eee;background-size: cover;background-position: center;background-repeat: no-repeat;border-radius:10px; }
#item_payment input[type='number'] { border: 1px solid #ccc; border-radius: 5px; width: 90%; height: 20px; line-height: 20px; background-color: #FFF; padding: 5px; font-size: 14px; }

#item_order_sheet .newyear_event_banner { display: none; background-color: #FFF8DE; }
#item_order_sheet .newyear_event_banner.on { display: table-row-group; }
#item_order_sheet .newyear_event_banner td { position: relative; text-align: center; }
#item_order_sheet .newyear_event_banner input[type='checkbox'] { display: none; }
#item_order_sheet .newyear_event_banner input[type='checkbox']+label { position: relative; display: inline-block; width: calc(100% - 47px); max-width: 375px; height: 35px; line-height: 35px; padding-left: 35px; border-radius: 5px; border: 1px solid #ccc; background-color: #fff; margin: 0 auto; }
#item_order_sheet .newyear_event_banner input[type='checkbox']+label>span { position: absolute; left: 5px; top: 5px; width: 20px; height: 20px; border: 1px solid #ccc; border-radius: 5px; background-color: #fff; }
#item_order_sheet .newyear_event_banner input[type='checkbox']:checked+label { border: 1px solid #f5bf2e; background-color: #f5bf2e; color: #fff; }
#item_order_sheet .newyear_event_banner input[type='checkbox']:checked+label>span { background-color: #fff; border: 1px solid #f5bf2e; color: #fff; }
#item_order_sheet .newyear_event_banner input[type='checkbox']:checked+label>span:before { content: ''; position: absolute; left: 3px; top: 3px; width: 10px; height: 5px; border-left: 5px solid #f5bf2e; border-bottom: 5px solid #f5bf2e; transform: rotate(-45deg); }
#item_order_sheet .newyear_event_banner .newyear_banner_img { width: 100%; height: 100px; background-color: #FFF8DE; background-size: auto 100%; background-repeat: no-repeat; background-position: center; }
#item_order_sheet .event_newyear_point { display: none; }
#item_order_sheet .event_newyear_point.on { display: table-row; }
#item_order_sheet .event_newyear_point td { text-align: right; }
#item_order_sheet .event_newyear_point td>div { display: inline-block; width: calc(100% - 20px); margin-right: 17px; color: #f00; }
</style>
<div class="top_menu">
	<div class="header-back-btn"><a href="javascript:;"><img src="/pet/images/btn_back_2.png" width="26px"></a></div>
	<div class="top_title">
		<p>결제정보</p>
	</div>
</div>
<div id="item_order_sheet">
	<div class="order_box">
		<div class="order_box_title">주문서 작성</div>
		<div class="order_box_text">
			<form id="item_payment">
				<input type="hidden" name="order_num" value="<?=$r_no ?>" />
				<input type="hidden" name="customer_id" value="<?=$user_id ?>" />
				<input type="hidden" name="product_price" value="0" />
				<input type="hidden" name="shipping_price" value="<?=$shipping_price ?>" />
				<input type="hidden" name="point_score" value="0" />
				<input type="hidden" name="total_price" value="0" />
				<input type="hidden" name="product_name" value="" />
				<input type="hidden" name="is_shop" value="2" />
				<input type="hidden" id="receipt_id" name="receipt_id" value="-">
				<input type="hidden" id="is_use_point" value="2" />
				<table width="100%" class="order_table">
					<colgroup>
						<col width="30%">
						<col width="70%">
					</colgroup>
					<tbody class="order_data_wrap">
					</tbody>
					<tbody class="order_user_wrap">
						<tr style="border-top:2px solid #eee;">
							<td colspan="2" class="td_padd">주문자 정보</td>
						</tr>
						<tr style="">
							<td class="table_line order_right" style="">주문자 성명</td>
							<td class="table_line" style="text-align:center;"><input type="text" id="customer_name" name="customer_name" class="input"></td>
						</tr>
						<tr style="">
							<td class="table_line order_right" style="">연락처</td>
							<td class="table_line" style="text-align:center;"><input type="number" id="cellphone" name="cellphone" class="input" numberonly placeholder="'-' 없이 입력" maxlength="12" value="<?=($cellphone != "")? $cellphone : '010';?>"></td>
						</tr>
						<tr style="">
							<td rowspan="2" class="table_line order_right" style="">이메일</td>
							<td class="table_line" style="text-align:center;">
								<input type="text" id="customer_email" name="customer_email" class="input2" value="<?=$user_email[0] ?>"> @ <input type="text" id="customer_email_suffix" name="customer_email_suffix" class="input2" value="<?=$user_email[1] ?>">
							</td>
						</tr>
						<tr style="">
							<td class="table_line" style="text-align:center;">
								<select class="customer_email_suffix_list" width="90%">
									<option value="">선택</option>
								<?php for($_i = 0; $_i < count($email_arr); $_i++){ ?>
									<option value="<?=$email_arr[$_i]?>"><?=$email_arr[$_i]?></option>
								<?php } ?>
									<option value="direct">직접입력</option>
								</select>
							</td>
						</tr>
					</tbody>
					<tbody class="shipping_info">
						<tr>
							<td colspan="2" class="td_padd">
								배송지 정보
								<span class="same_order_name">
									<input type="checkbox" id="same_order_name" class="same_order_name">
									<label for="same_order_name">주문자명과 동일</label>
								</span>
							</td>
						</tr>
					<?php if($user_id != ""){ ?>
						<tr>
							<td class="table_line order_right">배송지 선택</td>
							<td class="table_line">
								<div class="shipping_addr_wrap">
									<button type="button" class="customer_addr_list_btn">배송지추가</button>
								</div>
							</td>
						</tr>
					<?php } ?>
						<tr style="">
							<td class="table_line order_right" style="">받으시는 분</td>
							<td class="table_line" style="text-align:center;">
								<input type="text" id="shipping_name" name="shipping_name" class="input">
							</td>
						</tr>
						<tr style="">
							<td class="table_line order_right" style="">연락처</td>
							<td class="table_line" style="text-align:center;"><input type="number" id="shipping_cellphone" name="shipping_cellphone" class="input" numberonly="" placeholder="'-' 없이 입력" maxlength="12" value="010"></td>
						</tr>
						<tr style="">
							<td rowspan="3" class="table_line order_right" style="">받으시는 곳</td>
							<td class="table_line" style="padding-left: 6px;">
								<input type="text" id="zipcode" name="zipcode" class="input2 postcode" placeholder="주소입력" readonly>
								<button type="button" class="add_btn search_addr_btn">검색</button>
							</td>
						</tr>
						<tr>
							<td class="table_line" style="text-align:center;">
								<input type="text" id="addr1" name="addr1" class="input roadAddress" placeholder="기본주소" readonly>
								<input type="hidden" id="addr2" name="addr2" class="input jibunAddress" placeholder="지번주소" readonly>
								<input type="hidden" id="addr3" name="addr3" class="input extraAddress" placeholder="지번주소" readonly>
							</td>
						</tr>
						<tr>
							<td class="table_line" style="text-align:center;"><input type="text" id="addr4" name="addr4" class="input detailAddress" placeholder="상세주소" >
							</td>
						</tr>
						<tr style="">
							<td class="table_line order_right" style="">택배요청사항</td>
							<td class="table_line" style="text-align:center;">
								<select class="shipping_memo_list">
									<option value="">선택</option>
								<?php for($_i = 0; $_i < count($shipping_memo_arr); $_i++){ ?>
									<option value="<?=$shipping_memo_arr[$_i]?>"><?=$shipping_memo_arr[$_i]?></option>
								<?php } ?>
									<option value="direct">직접입력</option>
								</select>
								<textarea name="shipping_memo" readonly></textarea>
							</td>
						</tr>
					</tbody>
					<tbody class="newyear_event_banner">
						<tr>
							<td colspan="2">
								<div class="newyear_banner_img" style="background-image: url('/pet/images/newyear_banner_img_02.jpg');"></div>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<input type="checkbox" id="is_newyear" name="is_newyear" value="1" />
								<label for="is_newyear">
									<span></span>
									새뱃돈 받기
								</label>
							</td>
						</tr>
					</tbody>
					<tbody>
						<tr style="">
							<td colspan="2" class="td_padd">결제정보</td>
						</tr>
						<tr class="gray_bg">
							<td colspan="2">
								<table width="100%">
									<colgroup>
										<col width="50%">
										<col width="50%">
									</colgroup>
									<tbody>
										<tr>
											<td style="padding-left:20px;">상품금액</td>
											<td class="right_padd"><span class="product_price">0</span>원</td>
										</tr>
										<tr style="border-bottom:2px solid #aaa;">
											<td style="padding-left:20px;">+ 배송비</td>
											<td class="right_padd"><span class="shipping_price">0</span>원</td>
										</tr>
										<tr class="use_point">
											<td style="padding-left:20px;">포인트 사용</td>
											<td class="right_padd"><input class="point" name="point_price" type="number" value="0"> 원</td>
										</tr>
										<tr class="point_event event_first_payment_point">
											<td colspan="2"></td>
										</tr>
										<tr class="point_event event_newyear_point">
											<td colspan="2"></td>
										</tr>
										<tr class="use_point">
											<td colspan="2" class="right_padd">사용가능 포인트 : <span class="point_score">0</span>원</td>
										</tr>
										<tr class="use_point">
											<td colspan="2"><a href="javascript:;" class="point_use">포인트 전액 사용하기</a></td>
										</tr>
										<tr><td> </td></tr>
										<tr style="font-size:15px;">
											<td style="padding-left:20px;">총 결제예정 금액</td>
											<td class="right_padd"><span class="total_price">0</span>원</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
					<tbody>
						<tr style="">
							<td colspan="2" class="td_padd">결제수단 선택</td>
						</tr>
						<tr class="gray_bg">
							<td colspan="2">
								<table width="100%" class="pay_type_wrap">
									<colgroup>
										<col width="33%">
										<col width="33%">
										<col width="33%">
									</colgroup>
									<thead>
										<tr style="border-bottom:2px solid #aaa;">
											<td style="">결제 수단</td>
											<td class=""><input type="radio" name="pay_type" id="pay_type_1" value="1" class=""><label for="pay_type_1">카드결제</label></td>
											<td class=""><input type="radio" name="pay_type" id="pay_type_2" value="2" class="" checked><label for="pay_type_2">무통장입금</label></td>
										</tr>
									</thead>
									<tbody class="pay_type_tab_1">
										<tr>
											<td colspan="3"></td>
										</tr>
									</tbody>
									<tbody class="pay_type_tab_2 on">
										<tr style="">
											<td colspan="3" style="">기업은행 054-143076-01-013 <br>예금주 : 주식회사 펫이지</td>
										</tr>
										<tr>
											<td style="">입금자 성명</td>
											<td colspan="2"><input type="text" id="bank_name" name="bank_name" class="input" numberonly="" placeholder="" maxlength="12" value="" required="required"></td>
										</tr>
										<tr>
											<td colspan="3" style="font-size:12px;">* 계좌이체시 <span style="color:red;">반드시</span> 같은 이름으로 입금 부탁드립니다.</td>
										</tr>
										
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
					<tbody>
					<?php if($user_id == ""){ ?>
					<!----비회원 추가 동의 사항----->
						<tr style="">
							<td colspan="2" class="td_padd">주문동의</td>
						</tr>
						<tr style="border:1px solid #000;font-size:12px;">
							<td colspan="2" style="position: relative;padding-left: 5px;">
								<input type="checkbox" id="chk_ag2" name="chk_ag2" value=""><label for="chk_ag2">비회원 주문에 대한 개인정보 수집 이용 동의</label> 
							</td>
						</tr>			
						<tr class="be_member">
							<td colspan="2" style="padding:10px;">
								1. 개인정보 수집목적 및 이용목적 : 비회원 구매 서비스 제공<br>(서비스 제공에 따른 요금정산, 콘텐츠 제공, 구매 및 요금결제, 금융거래 본인 인증 및 금융서비스)<br>
								2. 수집하는 개인정보 항목 : 성명, 주소, 전화번호, 이메일, 결제정보<br>
								3. 보유기간 : 목적 달성 후 5일까지<br>
								(관계법률로 인해 필요 시, 일정기간 보존)<br><br>
								* 동의를 거부할 수 있으나, 거부시 비회원 구매 서비스 이용이 불가능합니다.
							</td>
						</tr>
					<!------------------------->
					<?php } ?>
						<tr>
							<td colspan="2" style="font-size:13px;padding-left: 5px;">
								<input type="checkbox" id="chk_ag" name="chk_ag" value=""><label for="chk_ag">구매내역을 확인하였으며, 구매진행에 동의합니다.</label>
							</td>
						</tr>
						<!--tr class="direct_card">
							<td colspan="2" style="text-align:center;"><a href="javascript:;" class="yes_payment_naverpay"><span></span> 결제</a><a href="javascript:;" class="yes_payment_kakaopay"><span></span>결제</a></td>
						</tr-->
						<tr>
							<td colspan="2" style="text-align:center;"><a href="javascript:;" class="yes_payment">결제하기</a><a href="javascript:;" class="no_payment">취소</a></td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
	</div>
</div>

<div id="customer_addr_wrap">
	<div id="one_page_window">
	</div>
</div>

<?php // 우편번호 검색창 ?>
<div id="search_addr_wrap" style="display: none;"></div>

<form id="INIpay_form" method="POST" action="<?=$INI_url['card']; ?>" accept-charset="euc-kr" onsubmit="emulAcceptCharset(this)">
	<input type="hidden" name="P_INI_PAYMENT" value="card" placeholder="결제요청 지불수단" />
	<input type="hidden" name="P_MID" value="<?=$INI_shopNumber ?>" placeholder="상점아이디" />
	<input type="hidden" name="P_OID" value="<?=$r_no ?>" placeholder="주문번호" />
	<input type="hidden" name="P_AMT" value="" placeholder="거래금액" />
	<input type="hidden" name="P_UNAME" value="" placeholder="고객성명" />
	<input type="hidden" name="P_MNAME" value="펫이지" placeholder="가맹점 이름" />
	<input type="hidden" name="P_NOTI" value="item" placeholder="기타주문정보(전달값그대로반환)" />
	<input type="hidden" name="P_GOODS" value="" placeholder="결제상품명" />
	<input type="hidden" name="P_MOBILE" value="<?=$cellphone ?>" placeholder="구매자 휴대폰번호" />
	<input type="hidden" name="P_EMAIL" value="<?=$user_id ?>" placeholder="구매자 이메일" />
	<input type="hidden" name="P_NEXT_URL" value="<?php echo $siteDomain ?>/mainpage/INI_return.php?PHPSESSID=<?=$_COOKIE["PHPSESSID"]?>&no=<?=$r_no?>" placeholder="인증결과수신 URL" /> 
	<input type="hidden" name="P_NOTI_URL" value="<?php echo $siteDomain ?>/mainpage/INI_result.php" placeholder="승인결과통보 URL" />
	<!--input type="hidden" name="P_TAX" value="" placeholder="부가세" /-->
	<!--input type="hidden" name="P_TAXFREE" value="" placeholder="비과세" /-->
	<!--input type="hidden" name="P_OFFER_PERIOD" value="별도 제공 기간 없음" placeholder="제공기간" /-->
	<!-- 신용카드 전용 필드 -->
	<input type="hidden" name="P_CARD_OPTION" value="" placeholder="신용카드 우선선택 옵션" />
	<input type="hidden" name="P_ONLY_CARDCODE" value="" placeholder="신용카드 노출제한 옵션" />
	<input type="hidden" name="P_QUOTABASE" value="" placeholder="신용카드 할부기간 지정" />
	<!-- 기타 옵션 필드 -->
	<input type="hidden" name="P_CHARSET" value="utf8" placeholder="케릭터셋 설정" />
	<!-- 복합필드(&으로 구분), 2020-10-14 아이폰 결제용으로 app_scheme=banjjack 추가 -->
	<?php if($token_index > 0){ // iOS ?>
		<input type="hidden" name="P_RESERVED" value="vbank_receipt=N&bank_receipt=N&cp_yn=N&app_scheme=banjjack://" placeholder="vbank_receipt=N&bank_receipt=N&cp_yn=N&app_scheme=banjjack://" />
	<?php }else{ ?>
		<input type="hidden" name="P_RESERVED" value="vbank_receipt=N&bank_receipt=N&cp_yn=N&app_scheme=banjjack" placeholder="vbank_receipt=N&bank_receipt=N&cp_yn=N&app_scheme=banjjack" />
	<?php } ?>
</form>

<form id="SendPayForm_id" name="" method="POST">
	<input type="hidden" name="version" value="1.0" >
	<input type="hidden" name="mid" value="" >
	<input type="hidden" name="goodname" value="" >
	<input type="hidden" name="oid" value="<?=$r_no ?>" >
	<input type="hidden" name="price" value="" >
	<input type="hidden" name="currency" value="WON" >
	<input type="hidden" name="buyername" value="" >
	<input type="hidden" name="buyertel" value="<?=$cellphone ?>" >
	<input type="hidden" name="buyeremail" value="<?=$user_id ?>" >
	<input type="hidden" name="timestamp" value="" >
	<input type="hidden" name="signature" value="" >
	<input type="hidden" name="returnUrl" value="<?php echo $siteDomain ?>/mainpage/INI_return_pc.php" > <!-- INIStdPayReturn.php -->
	<input type="hidden" name="mKey" value="" >
	<input type="hidden" name="gopaymethod" value="Card" >
	<input type="hidden" name="offerPeriod" value="" >
	<input type="hidden" name="acceptmethod" value="HPP(1):no_receipt:va_receipt:vbanknoreg(0):below1000" >
	<input type="hidden" name="languageView" value="ko" >
	<input type="hidden" name="charset" value="UTF-8" >
	<input type="hidden" name="payViewType" value="overlay" >
	<input type="hidden" name="closeUrl" value="<?php echo $siteDomain ?>/test/close.php" >
	<input type="hidden" name="nointerest" value="" >
	<input type="hidden" name="quotabase" value="" >
	<input type="hidden" name="merchantData" value="item" >
</form>

<script>
var no = "<?=$r_no ?>";
var orderItem = [];
var shipping_price = 2500;
var is_free_shipping_limit = ["", 100000, 30000, 50000]; // '', 업체배송, 직배송, 하이포닉
var is_free_shipping_total = ["", 0, 0, 0]; // '', 업체배송, 직배송, 하이포닉
var is_free_shipping_cnt = ["", 0, 0, 0]; // '', 업체배송, 직배송, 하이포닉
var is_free_shipping_price = ["", 0, 0, 0]; // '', 업체배송, 직배송, 하이포닉
var soldout_flag = [0, 0]; // 시도횟수, 재고없음처리횟수
var first_payment = [0, 0]; // 첫결제여부
var is_jbook_runout = 0; // 정글북 재고없음 여부(1-품절, 0-재고있음)
var is_newyear = [0, 0]; // 새뱃돈 여부
var newyear_product_no = ['JB-BA-A20', 'JB-BA-A21', 'ETCB-DB-A07', 'ETCB-DB-A01', 'ETCB-DB-A06', 'ETCB-DB-A12', 'ETCB-DB-A13', 'towel01', 'JB-S-A138', 'JB-S-A03', 'JB-S-A05', 'JB-S-A37', 'JB-S-A42', 'JB-S-A43', 'JB-S-A44', 'JB-S-A45', 'ETCB-DB-A14', 'JB-BM-A18', 'JB-BM-A19', 'JB-BM-A20', 'JB-BM-A21', 'JB-BM-A01', 'JB-BM-A02', 'JB-BM-A03', 'PE-D-A01', 'ETCB-DB-A10', 'JB-DC-A38', 'JB-DC-A37', 'JB-DC-A36', 'JB-DC-A35', 'JB-DC-A20', 'JB-HM-A49', 'JB-HM-A48', 'JB-HM-A42', 'JB-HM-A44', 'JB-HM-A21', 'JB-HM-A50'];
var moblieChk = "<?=$obj->mobileConcertCheck() ?>";

$(document).ready(function() {
	if (!is_app) {
		if(moblieChk != "mobile"){
			if(isIOSchk != true){
				$("#item_order_sheet").remove();
				$.MessageBox({ 
					buttonDone: "확인",
					message: "반짝 앱에서만 결제가 가능합니다."
				}).done(function(){
					location.href = '<?=$mainpage_directory ?>/index.php';
				});
				return false;
			}
		}
	}

	if("<?=$user_id?>" != ""){
		$(".customer_email_suffix_list").val("direct"); // 정회원이면 직접 입력으로 변경
	}

	$("#loading").css("align-items", "center").css("justify-content", "center");
	$("#loading").html("<span style='color: #fff; max-width: 50%; text-align:center;'><img src='/pet/images/logo_loading0.gif' style='width: 100px; height: 100px;'/><br/><span style='color:#f5bf2e;'>반</span>려생활의 단<span style='color:#f5bf2e;'>짝</span></span>");
	//$("#loading").css("display", "flex");

	get_item_cart()
		.then(price_calculator)
		.then(get_first_payment)
		.then(newyear_event_chk)
		.then(get_addr);

	if(getCookie("is_shop")){
		// 펫샵 데이터 호출
		$.ajax({
			url: '<?=$mainpage_directory?>/',
			data: {
				mode : "get_shop",
				customer_id: "<?=$user_id ?>"
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					$("#item_order_sheet input[name='customer_name']").val(data.data[0].name);
					$("#item_order_sheet input.same_order_name").prop("checked", true);
					$("#item_order_sheet input[name='shipping_name']").val($("#item_order_sheet input[name='customer_name']").val());
					$("#item_order_sheet input[name='shipping_cellphone']").val($("#item_order_sheet input[name='cellphone']").val());
					$("#item_order_sheet input[name='zipcode']").val(data.data.offline_shop_address.split("|")[0]);
					$("#item_order_sheet input[name='addr1']").val(data.data.offline_shop_address.split("|")[1]);
					$("#item_order_sheet input[name='addr4']").val("(샵 주소)");
					$("#item_order_sheet input[name='bank_name']").val($("#item_order_sheet input[name='customer_name']").val());
					$("#item_order_sheet #pay_type_1").hide();
					$("#item_order_sheet #pay_type_1+label").hide();
					$("#item_order_sheet .order_user_wrap").hide();
				}else{
					alert(data.data+"("+data.code+")");
					console.log(data.data);
				}
			},
			error: function(xhr, status, error) {
				//alert(error + "네트워크에러");
				if(xhr.status != 0){
					alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
				}
			}
		});
	}

	// 포인트 불러오기
	if("<?= $user_id ?>" != ""){
		$.ajax({
			type: 'post',
			url: "<?=$mainpage_directory?>/customer_ajax.php",
			data: {
				mode: "get_customer_point",
				customer_id: "<?= $user_id ?>"
			},
			dataType: 'json',
			beforeSend: function() {
				  //$("#loading").css("display","flex");
			},
			error: function() {
			},
			success: function(json) {
				if(json.code == "000000"){
					//console.log(json.data);
					$("#item_order_sheet .point_score").html('').html(Math.round(json.data).format());
					$("#item_order_sheet input[name='point_score']").val(Math.round(json.data));
					$("#item_order_sheet input[name='point_score']").data("point", Math.round(json.data));
				}else{
					alert(json.data+"("+json.code+")");
				}
			},
			complete: function() {
				// console.log('complete');
				//서브밋 차단 해제
				//$("#loading").css("display","none");
			}
		});
	}else{
		$("#item_order_sheet .point_score").html('').html(0);
		$("#item_order_sheet input[name='point_score']").val(0);
		$("#item_order_sheet input[name='point_score']").data("point", 0);
	}
});

$('#item_order_sheet').on("click", "input[name='is_newyear']", function(){
	// init
	$('#item_order_sheet input[name="point_price"]').val(0);
	$('#item_order_sheet input[name="point_price"]').prop("readonly", false);
	$('#item_order_sheet input[name="point_score"]').val($('#item_order_sheet input[name="point_score"]').data("point"));
	$('#item_order_sheet').find('.event_newyear_point').removeClass("on");
	$('#item_order_sheet').find('.event_first_payment_point').hide();

	if($(this).is(":checked") == true){
		//console.log("on");
		price_calculator()
			.then(get_newyear_payment);
	}else{
		//console.log("off");
		price_calculator()
			.then(get_first_payment);
	}
});

function get_item_cart(){
	return new Promise(function(resolve, reject) {
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "get_cart",
				is_session : "1",
				no: no
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					var html = '';
					html += '<tr class="in_product_title">';
					html += '	<td colspan="2" style="text-align: center; background-color: #fce985; font-weight: Bold; color: #736452;">직배송</td>';
					html += '</tr>';
					html += '<tr class="in_product">';
					html += '	<td colspan="2"></td>';
					html += '</tr>';
					html += '<tr class="out_product_title">';
					html += '	<td colspan="2" style="text-align: center; background-color: #c4e4a5; font-weight: Bold; color: #736452;">업체배송</td>';
					html += '</tr>';
					html += '<tr class="out_product">';
					html += '	<td colspan="2"></td>';
					html += '</tr>';
					//html += '<tr>';
					//html += '	<td colspan="2" style="text-align:right;padding:20px;font-weight: Bold;">총 합계 : <span class="total_price_txt">0</span>원</td>';
					//html += '</tr>';

					$("#item_order_sheet .order_data_wrap").html(html);

					//$.each(data.data, function(i, v){
					//	get_item_list(i, v, data.data.length)
					//		.then(price_calculator)
					//		.then(get_first_payment);
					//});

					var p = $.when();
					var c = 0;
					$.each(data.data, function(i, v){
						p = p.then(function(){
							c++;
							return get_item_list(i, v, data.data.length);
						}).done(function(){
							//console.log("### done");
							if(c == data.data.length){
								resolve();
							}
						});
					});

					//resolve();
				}else{
					alert(data.data+"("+data.code+")");
					console.log(data.data);
				}
			},
			error: function(xhr, status, error) {
				//alert(error + "네트워크에러");
				if(xhr.status != 0){
					alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
				}
			}
		});
	});
}

function get_item_list(cart_no, cart_data, item_cart_no){
	return new Promise(function(resolve, reject) {

		// 새뱃돈 이벤트 체크
		if($.inArray(cart_data.product_no, newyear_product_no) != -1){
			//console.log("YES! - "+cart_data.product_no);
			is_newyear[0] = 1;
		}else{
			//console.log("NO!! - "+cart_data.product_no);
		}

		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "get_item_list",
				product_no: cart_data.product_no
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);

					var html = '';
					var item_name = '';
					var product_price = 0;
					var parse_cart_data = JSON.parse(cart_data.cart_data);

					$.each(data.data.list, function(i, v){
						var is_free_shipping = 0;
						if(cart_no == 0){
							item_name = (item_cart_no > 1)? v.product_name+' 외 '+(parseInt(item_cart_no)-1)+'건' : v.product_name; // 자기자신은 횟수에서 제외
						//}else{
						//	item_name = v.product_name;
						}
						console.log("!@#$", cart_no, item_name, item_cart_no);

						html += '<div class="item" data-id="'+cart_data.ic_seq+'" style="position: relative; min-height: 120px;">';
						html += '	<div class="product_img" style="position: absolute; left: 0px; top: 0px;"></div>';
						html += '	<div class="cart_data" style="margin-left: 110px;">';
						html += '		<div class="item_name">'+v.product_name+'</div>';
						$.each(parse_cart_data, function(i2, v2){
							html += '		<div class="item_option">'+v2.txt+' / '+v2.value.format()+'원 / '+v2.amount+'개</div>';
							if(v2.is_free_shipping == 1){
								if(cart_data.ip_seq == "7"){ // 하이포닉 상품일때
									is_free_shipping_total[3]++;
								}else{
									is_free_shipping_total[v.is_supply]++;
								}
								is_free_shipping = 1;
							}
							if(cart_data.ip_seq == "7"){ // 하이포닉 상품일때
								is_free_shipping_cnt[3]++;
							}else{
								is_free_shipping_cnt[v.is_supply]++;
							}
							product_price += parseInt(v2.value * v2.amount);

							if(v.is_supply == "1" && v.supplier == "정글북"){ // 외주업체(정글북)
								get_jbook_list2("goods", v.goodsNo, v2.amount, cart_data.ic_seq);
							}
						});
						//if(is_free_shipping == 1){
						//	html += '		<div class="item_free_shipping">배송비 무료</div>';
						//}else{
						//	html += '		<div class="item_free_shipping">배송비 '+shipping_price.format()+'원</div>';
						//}
						html += '		<div style="position: absolute; right: 15px; bottom: 15px; font-weight: Bold;">'+product_price.format()+' 원</div>';
						html += '	</div>';
						html += '</div>';

						if(v.ip_seq == '7'){ // 하이포닉 상품일때
							is_free_shipping_price[3] += product_price;
						}else{
							is_free_shipping_price[v.is_supply] += product_price;
						}
						if(v.is_supply == "1"){
							$("#item_order_sheet .order_data_wrap tr.out_product td").append(html);
						}else if(v.is_supply == "2"){
							$("#item_order_sheet .order_data_wrap tr.in_product td").append(html);
						}

						// 전문몰여부(1-전문몰, 2-반짝몰)
						if(v.is_shop == 1){
							$('#item_order_sheet input[name="is_shop"]').val(1);
						}
						//console.log("is_shop:", $('#item_order_sheet input[name="is_shop"]').val());

					});

					if(item_name != ""){
						$("#item_order_sheet .top_menu .top_title p").text(item_name);
						$("#item_order_sheet input[name='product_name']").val(item_name);
						$("#INIpay_form input[name='P_GOODS']").val(item_name);
						$("#SendPayForm_id input[name='goodname']").val(item_name);
					}

					$.each(data.data.list, function(i, v){
						get_file_list(cart_data.ic_seq, v.product_img, v.goodsRepImage);

						// 20201203 ulmo 포인트 사용여부 추가 - 하나라도 포인트 결제가 있다면 포인트 사용 가능처리
						console.log(v.is_use_point);
						$("input#is_use_point").val(v.is_use_point);
						if(v.is_use_point == "1"){
							$("#item_order_sheet .use_point").css("display", "table-row");
						//}else{
						//	console.log(v);
						}
					});

					resolve(cart_no);
				}else{
					alert(data.data+"("+data.code+")");
					console.log(data.data);
				}
			},
			error: function(xhr, status, error) {
				//alert(error + "네트워크에러");
				if(xhr.status != 0){
					alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
				}
			}
		});
	});
}

function get_jbook_list2(menu, option, amount, ic_seq){
	//console.log(menu, option);
	$.ajax({
		url: '<?=$admin_directory?>/jbook_item_ajax.php',
		data: {
			mode : "get_item_list",
			menu : menu,
			option : option
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				var result_txt = '0';
				if(data.data && parseInt(data.data.total) > 0){
					$.each(data.data.data, function(i, v){
						result_txt = (v.runout == "1")? '품절' : v.goodsStock;
						if(v.runout == "1"){
							is_jbook_runout = 1;
							$("#item_order_sheet .order_data_wrap tr.out_product td .item[data-id='"+ic_seq+"'] .cart_data").append('<div style="color:#f00;">품절</div>');
							// 품절
							$.MessageBox({
								buttonDone: "확인",
								message: "품절된 상품이 있습니다."
							}).done(function(){
								// 장바구니로 이동
							});
						}else{
							orderItem.push({goodsNo: option, ea: amount}); // 정글북 데이터 입력
							$("#item_order_sheet .order_data_wrap tr.out_product td .item[data-id='"+ic_seq+"']").attr('data-amount', result_txt);
						}
					});
				}
				
			}else{
				alert(data.data+"("+data.code+")");
				console.log(data.code);
			}
		},
		error: function(xhr, status, error) {
			//alert(error + "네트워크에러");
			if(xhr.status != 0){
				alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
			}
		}
	});
}

function get_file_list(ic_seq, img_list, goodsRepImage){
	console.log(img_list);
	if(img_list && typeof img_list != "undefined" && img_list != ""){
		$.ajax({
			url: '<?=$mainpage_directory ?>/fileupload_ajax.php',
			data: {
				mode : "get_file_list",
				file_list: img_list
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					var html = '';
					if(data.data && data.data.length > 0){
						$.each(data.data, function(i, v){
							if(i == 0){
								html += '<div style="background-image: url(\''+v.file_path+'\');"></div>';
							}
						});
					}else{
						html += '<div></div>';
					}
					$('#item_order_sheet .order_data_wrap .item[data-id="'+ic_seq+'"] .product_img').html(html);
				}else{
					alert(data.data+"("+data.code+")");
					console.log(data.data);
				}
			},
			error: function(xhr, status, error) {
				//alert(error + "네트워크에러");
				if(xhr.status != 0){
					alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
				}
			}
		});
	}else{
		if(goodsRepImage != ""){
			$('#item_order_sheet .order_data_wrap .item[data-id="'+ic_seq+'"] .product_img').html('<div style="background-image: url(\''+goodsRepImage+'\');"></div>');
		}else{
			$('#item_order_sheet .order_data_wrap .item[data-id="'+ic_seq+'"] .product_img').html('<div></div>');
		}
	}
}

function price_calculator(){
	return new Promise(function(resolve, reject) {
		console.log(is_free_shipping_price);

		var _shipping_price = ['', 0, 0, 0]; // 직배송 + 업체배송
		var _total_price = parseInt(is_free_shipping_price[1]) + parseInt(is_free_shipping_price[2]) + parseInt(is_free_shipping_price[3]);
		
		if(is_free_shipping_price[1] == 0 && is_free_shipping_price[2] == 0 && is_free_shipping_price[3] == 0){
			_shipping_price = ['', 0, 0, 0];
		}else{
			if(is_free_shipping_price[1] >= is_free_shipping_limit[1]){ // 10만원 이상 구매시
				_shipping_price[1] = parseInt(0);
			}else{
				if(is_free_shipping_total[1] == is_free_shipping_cnt[1]){ // 업체상품 전부 무료일 경우 무료
					_shipping_price[1] = parseInt(0);
				}else{
					_shipping_price[1] = parseInt(shipping_price);
				}
			}
			if(is_free_shipping_price[2] >= is_free_shipping_limit[2]){ // 3만원 이상 구매시
				_shipping_price[2] = parseInt(0);
			}else{
				if(is_free_shipping_total[2] == is_free_shipping_cnt[2]){ // 직상품 전부 무료일 경우 무료
					_shipping_price[2] = parseInt(0);
				}else{
					_shipping_price[2] = parseInt(shipping_price);
				}
			}
			if(is_free_shipping_price[3] >= is_free_shipping_limit[3]){ // 18만원 이상 구매시
				_shipping_price[3] = parseInt(0);
			}else{
				if(is_free_shipping_total[3] == is_free_shipping_cnt[3]){ // 뭔상품 전부 무료일 경우 무료
					_shipping_price[3] = parseInt(0);
				}else{
					_shipping_price[3] = parseInt(shipping_price);
				}
			}

			if(_shipping_price[1] < 0){
				_shipping_price[1] = 0;
			}
			if(_shipping_price[2] < 0){
				_shipping_price[2] = 0;
			}
			if(_shipping_price[3] < 0){
				_shipping_price[3] = 0;
			}

			if(is_free_shipping_cnt[1] == 0){
				_shipping_price[1] = 0;
			}
			if(is_free_shipping_cnt[2] == 0){
				_shipping_price[2] = 0;
			}
			if(is_free_shipping_cnt[3] == 0){
				_shipping_price[3] = 0;
			}
		}

		// 제품 주문 체크
		if(is_free_shipping_cnt[1] == "0"){
			$("#item_order_sheet tr.out_product_title").hide();
			$("#item_order_sheet tr.out_product").hide();
		}else{
			$("#item_order_sheet tr.out_product_title").show();
			$("#item_order_sheet tr.out_product").show();
		}
		if(is_free_shipping_cnt[2] == "0"){
			if(is_free_shipping_cnt[3] == "0"){
				$("#item_order_sheet tr.in_product_title").hide();
				$("#item_order_sheet tr.in_product").hide();
			}else{
				$("#item_order_sheet tr.in_product_title").show();
				$("#item_order_sheet tr.in_product").show();
			}
		}else{
			$("#item_order_sheet tr.in_product_title").show();
			$("#item_order_sheet tr.in_product").show();
		}
//		if(is_free_shipping_cnt[3] == "0"){
//			$("#item_order_sheet tr.in_product_title").hide();
//			$("#item_order_sheet tr.in_product").hide();
//		}else{
//			$("#item_order_sheet tr.in_product_title").show();
//			$("#item_order_sheet tr.in_product").show();
//		}
		$("#item_order_sheet input[name='product_price']").val(_total_price);
		$("#item_order_sheet input[name='shipping_price']").val((_shipping_price[1] + _shipping_price[2] + _shipping_price[3]));
		$("#item_order_sheet input[name='total_price']").val(_total_price + (_shipping_price[1] + _shipping_price[2] + _shipping_price[3]));
		$("#item_order_sheet .product_price").text(_total_price.format());
		$("#item_order_sheet .shipping_price").text((_shipping_price[1] + _shipping_price[2] + _shipping_price[3]).format());
		$("#item_order_sheet .total_price").text((_total_price + (_shipping_price[1] + _shipping_price[2] + _shipping_price[3])).format());
		$("#item_order_sheet .order_data_wrap .total_price_txt").text((_total_price + (_shipping_price[1] + _shipping_price[2] + _shipping_price[3])).format());

		resolve([_total_price, (_shipping_price[1] + _shipping_price[2] + _shipping_price[3])]);
	});
}

function newyear_event_chk(price_data){
	return new Promise(function(resolve, reject) {
		//console.log("### 3-2");
		//console.log(price_data);
		//console.log(price_data[0], is_newyear);
		if(price_data[0] >= 30000 && is_newyear[0] == 1 && new Date('2021-02-14 23:59:59'.replace(/-/g, '/')).getTime() > new Date().getTime()){ // 3만원 이상 노출 + 이벤트 제품 구매 + 마감일 이전(+ios cross browsing)
			$("#item_order_sheet").find(".newyear_event_banner").addClass("on");
		}
		resolve();
	});
}

function get_newyear_payment(price_data){
	return new Promise(function(resolve, reject) {
		console.log("### 3-3");
		console.log(price_data);
		var event_point = 5000;
		var point_score = $('#item_order_sheet input[name="point_score"]').val();

		if(price_data[0] >= 50000){
			event_point = 10000;
		}else if(price_data[0] >= 30000){
			event_point = 5000;
		}

		is_newyear[1] = event_point;
		$('#item_order_sheet input[name="is_newyear"]').val(is_newyear[1]);
		$('#item_order_sheet input[name="point_price"]').val(is_newyear[1]);
		$('#item_order_sheet input[name="point_price"]').prop("readonly", true);
		$('#item_order_sheet input[name="point_score"]').val(parseInt(point_score) + is_newyear[1]);
		$('#item_order_sheet input[name="total_price"]').val(parseInt(price_data[0]) + parseInt(price_data[1]) - parseInt(is_newyear[1]));
		$('#item_order_sheet .total_price').text((parseInt(price_data[0]) + parseInt(price_data[1]) - parseInt(is_newyear[1])).format());
		$('#item_order_sheet').find('.event_newyear_point').addClass("on");
		$('#item_order_sheet').find('.event_newyear_point td').html('<div>새뱃돈 '+is_newyear[1].format()+'원 할인!</div>');
		$('#item_order_sheet').find('.use_point').hide();

		resolve();
	});
}

function get_first_payment(price_data){
	return new Promise(function(resolve, reject) {
		console.log("### 3-1");
		var customer_id = "<?= $user_id ?>";

		if(customer_id && customer_id != "" && $("input#is_use_point").val() == "1"){
			$.ajax({
				type: 'post',
				url: "<?=$item_directory ?>/item_list_ajax.php",
				data: {
					mode: "get_first_payment",
					customer_id: customer_id
				},
				dataType: 'json',
				success: function(json) {
					if(json.code == "000000"){
						console.log(json.data);

						if(json.data){
							if(json.data.cnt == 0){ // 결제내역 없음 = 첫결제
								var event_point = 0;
								var point_score = $('#item_order_sheet input[name="point_score"]').val();
								console.log(price_data);

								if(price_data[0] >= 30000){
									event_point = 3000;
								}else if(price_data[0] >= 20000){
									event_point = 2000;
								}else if(price_data[0] >= 10000){
									event_point = 1000;
								}
								
								first_payment[0] = 1;
								first_payment[1] = event_point;
								$('#item_order_sheet input[name="point_price"]').val(event_point);
								$('#item_order_sheet input[name="point_price"]').prop("readonly", false);
								$('#item_order_sheet input[name="point_score"]').val(parseInt(point_score) + event_point);
								$('#item_order_sheet input[name="total_price"]').val(price_data[0] + price_data[1] - event_point);
								$('#item_order_sheet .total_price').text((price_data[0] + price_data[1] - event_point).format());
								if(event_point > 0){
									$('#item_order_sheet .event_first_payment_point').show();
									$('#item_order_sheet .event_first_payment_point td').html('<div>첫 구매 자동 '+event_point.format()+'원 할인!</div>');
								}
								console.log(first_payment);
							}else{
								console.log(json.data);
							}

							$('#item_order_sheet').find('.use_point').show();
						}else{
							console.log("no result");
						}
						resolve(price_data);
					}else{
						alert(json.data+"("+json.code+")");
					}
				},
				complete: function() {
					// console.log('complete');
					//서브밋 차단 해제
					//$("#loading").css("display","none");
				}
			});
		}else{
			//resolve(price_data);
		}
	});
}

function get_addr(){
	var customer_id = "<?= $user_id ?>";

	if(customer_id != ""){
		$.ajax({
			type: 'post',
			url: "<?=$mainpage_directory ?>/customer_ajax.php",
			data: {
				mode: "get_customer_addr_list",
				customer_id: customer_id
			},
			dataType: 'json',
			beforeSend: function() {
				  //$("#loading").css("display","flex");
			},
			error: function() {
			},
			success: function(json) {
				if(json.code == "000000"){
					console.log(json.data);
					var html = '';
					$.each(json.data, function(i, v){
						html += '<span>';
						html += '	<input type="radio" id="choice_addr_'+(i+1)+'" name="choice_addr" value="'+v.ca_seq+'" />';
						html += '	<label for="choice_addr_'+(i+1)+'">'+v.addr_name+'</label>';
						html += '</span>';
					});
					html += '<button type="button" class="customer_addr_list_btn" style="background:#fff; border:1px solid #736452; color:#736452;  border-radius:5px; margin:5px; padding:10px; width:40%;">배송지추가</button>';
					$("#item_order_sheet .shipping_addr_wrap").html('').append(html);
				}else{
					alert(json.data+"("+json.code+")");
				}
			},
			complete: function() {
				// console.log('complete');
				//서브밋 차단 해제
				//$("#loading").css("display","none");
			}
		});
	}
}

// 뒤로가기
$(document).on("click", ".header-back-btn", function(){
	window.history.back();
});

// 이메일 뒷주소 선택
$(document).on("change", "#item_order_sheet .customer_email_suffix_list", function(){
	var email = $(this).children("option:selected").val();
	$("#item_order_sheet input[name='customer_email_suffix']").val('');

	if(email == ""){
		$("#item_order_sheet input[name='customer_email_suffix']").prop("readonly", true);
	}else if(email == "direct"){
		$("#item_order_sheet input[name='customer_email_suffix']").prop("readonly", false);
	}else{
		$("#item_order_sheet input[name='customer_email_suffix']").prop("readonly", true);
		$("#item_order_sheet input[name='customer_email_suffix']").val(email);
	}
	var value = $("#item_order_sheet input[name='customer_email']").val();

	$("#INIpay_form input[name='P_EMAIL']").val(value+"@"+email);
	$("#SendPayForm_id input[name='buyeremail']").val(value+"@"+email);
});

// INI 결제용 이름
$(document).on("keyup", "#item_order_sheet input[name='customer_name']", function(){
	var value = $(this).val();

	$("#INIpay_form input[name='P_UNAME']").val(value);
	$("#SendPayForm_id input[name='buyername']").val(value);
});

// INI 결제용 연락처
$(document).on("keyup", "#item_order_sheet input[name='cellphone']", function(){
	var value = $(this).val();

	$("#INIpay_form input[name='P_MOBILE']").val(value);
	$("#SendPayForm_id input[name='buyertel']").val(value);
});

// INI 결제용 이메일
$(document).on("keyup", "#item_order_sheet input[name='customer_email']", function(){
	var value = $(this).val();
	var value2 = $("#item_order_sheet input[name='customer_email_suffix']").val();

	$("#INIpay_form input[name='P_EMAIL']").val(value+"@"+value2);
	$("#SendPayForm_id input[name='buyeremail']").val(value+"@"+value2);
});

// INI 결제용 이메일2
$(document).on("keyup", "#item_order_sheet input[name='customer_email_suffix']", function(){
	var value = $("#item_order_sheet input[name='customer_email']").val();
	var value2 = $(this).val();

	$("#INIpay_form input[name='P_EMAIL']").val(value+"@"+value2);
	$("#SendPayForm_id input[name='buyeremail']").val(value+"@"+value2);
});

// 받는사람, 연락처에 주문자명, 연락처 가져오기
$(document).on("click", "#item_order_sheet input.same_order_name", function(){
	if($(this).is(":checked") == true){
		$("#item_order_sheet input[name='shipping_name']").val($("#item_order_sheet input[name='customer_name']").val());
		$("#item_order_sheet input[name='shipping_cellphone']").val($("#item_order_sheet input[name='cellphone']").val());
	}else{
		$("#item_order_sheet input[name='shipping_cellphone']").val('010');
		$("#item_order_sheet input[name='shipping_name']").val('');
	}
});

// 주소 선택(정회원)
$(document).on("click", "#item_order_sheet .shipping_addr_wrap input[name='choice_addr']", function(){
	var choice_addr_seq = $(this).val();
	
	$.ajax({
		type: 'post',
		url: "<?=$mainpage_directory?>/customer_ajax.php",
		data: {
			mode: "get_customer_addr",
			customer_id: "<?= $user_id ?>",
			ca_seq: choice_addr_seq
		},
		dataType: 'json',
		beforeSend: function() {
			  //$("#loading").css("display","flex");
		},
		error: function() {
		},
		success: function(json) {
			if(json.code == "000000"){
				//console.log(json.data);
				// 배송지에 추가하기
				$.each(json.data, function(i, v){
					$("input[name='addr1']").val(v.road_addr);
					$("input[name='addr2']").val(v.jibun_addr);
					$("input[name='addr3']").val(v.extra_addr);
					$("input[name='addr4']").val(v.detail_addr);
					$("input[name='zipcode']").val(v.zipcode);
				});
			}else{
				alert(json.data+"("+json.code+")");
			}
		},
		complete: function() {
			// console.log('complete');
			//서브밋 차단 해제
			$("#loading").css("display","none");
		}
	});
});

// 배송메모 선택
$(document).on("change", "#item_order_sheet .shipping_memo_list", function(){
	var memo = $(this).children("option:selected").val();
	$("#item_order_sheet textarea[name='shipping_memo']").val('');
	if(memo == ""){
		$("#item_order_sheet textarea[name='shipping_memo']").prop("readonly", true);
	}else if(memo == "direct"){
		$("#item_order_sheet textarea[name='shipping_memo']").prop("readonly", false);
	}else{
		$("#item_order_sheet textarea[name='shipping_memo']").prop("readonly", true);
		$("#item_order_sheet textarea[name='shipping_memo']").val(memo);
	}
});

// 포인트 입력
$(document).on("keyup", "#item_order_sheet input[name='point_price']", function(){
	var _this = $(this);
	var point_price = parseInt(_this.val());
	var point_score = parseInt($("#item_order_sheet input[name='point_score']").val());
	var product_price = parseInt($("#item_order_sheet input[name='product_price']").val());
	var shipping_price = parseInt($("#item_order_sheet input[name='shipping_price']").val());
	var total_price = product_price + shipping_price;

	if(point_price > point_score){
		if(point_score >= total_price){
			point_price = total_price;
		}else{
			point_price = point_score;
		}
	}else{
		if(point_price > total_price){
			point_price = total_price;
		}

		if(point_price <= parseInt(first_payment[1])){
			point_price = parseInt(first_payment[1]);
		}

		if(isNaN(point_price)){
			point_price = parseInt(first_payment[1]);
		}
	}

	//_this.val(point_price);
	total_price_calculator(); // 결제금액 계산
});

$(document).on("focusout", "#item_order_sheet input[name='point_price']", function(){
	var _this = $(this);
	var point_price = parseInt(_this.val());
	var point_score = parseInt($("#item_order_sheet input[name='point_score']").val());
	var product_price = parseInt($("#item_order_sheet input[name='product_price']").val());
	var shipping_price = parseInt($("#item_order_sheet input[name='shipping_price']").val());
	var total_price = product_price + shipping_price;

	if(point_price > point_score){
		if(point_score >= total_price){
			point_price = total_price;
		}else{
			point_price = point_score;
		}
	}else{
		if(point_price > total_price){
			point_price = total_price;
		}

		if(point_price <= parseInt(first_payment[1])){
			point_price = parseInt(first_payment[1]);
		}

		if(isNaN(point_price)){
			point_price = parseInt(first_payment[1]);
		}
	}

	_this.val(point_price);
	total_price_calculator(); // 결제금액 계산
});

// 포인트 전부 사용
$(document).on("click", "#item_order_sheet .point_use", function(){
	var point_score = parseInt($("#item_order_sheet input[name='point_score']").val());
	var product_price = parseInt($("#item_order_sheet input[name='product_price']").val());
	var shipping_price = parseInt($("#item_order_sheet input[name='shipping_price']").val());
	var total_price = product_price + shipping_price;
	//console.log(total_price, point_score);
	var point = 0;

	if(point_score > 0){ // 포인트 여부
		if(point_score >= total_price){
			point = total_price;
		}else{
			point = point_score;
		}
	}

	$("#item_order_sheet input[name='point_price']").val(point);
	total_price_calculator(); // 결제금액 계산
});

function total_price_calculator(){
	var product_price = parseInt($("#item_order_sheet input[name='product_price']").val());
	var shipping_price = parseInt($("#item_order_sheet input[name='shipping_price']").val());
	var point_price = parseInt($("#item_order_sheet input[name='point_price']").val());
	var total_price = 0;

	total_price = product_price + shipping_price - point_price;
	if(total_price <= 0){
		total_price = 0;
	}

	if(isNaN(total_price)){
		total_price = 0;
	}

	$("#item_order_sheet input[name='total_price']").val(total_price);
	$("#item_order_sheet span.total_price").text(total_price.format());
}

// 결제수단 선택(PG, 계좌이체)
$(document).on("click", "#item_order_sheet input[name='pay_type']", function(){
	var value = $(this).val();
	$("#item_order_sheet .pay_type_wrap tbody").removeClass("on");
	$("#item_order_sheet .pay_type_tab_"+value).addClass("on");
	if(value == "1"){
		$("#item_order_sheet .direct_card").css("display", "table-row");
	}else{
		$("#item_order_sheet .direct_card").css("display", "none");
	}
});

// 네이버페이 바로결제
$(document).on("click", "#item_order_sheet .yes_payment_naverpay", function(){
	// 정글북인경우 재고 체크
	/*
	console.log(orderItem.length);
	if(orderItem.length > 0){
		$.each(orderItem, function(i, v){
			get_jbook_list("goods", v.goodsNo, 'naverpay');
		});
	}else{
		change_payment('naverpay');
	}
	*/
	if(is_jbook_runout == 0){
		change_payment('naverpay');
	}else{
		$.MessageBox("품절된 상품은 결제하실 수 없습니다.");
	}
});

// 카카오페이 바로결제
$(document).on("click", "#item_order_sheet .yes_payment_kakaopay", function(){
	// 정글북인경우 재고 체크
	/*
	console.log(orderItem.length);
	if(orderItem.length > 0){
		$.each(orderItem, function(i, v){
			get_jbook_list("goods", v.goodsNo, 'kakaopay');
		});
	}else{
		change_payment('kakaopay');
	}
	*/
	if(is_jbook_runout == 0){
		change_payment('kakaopay');
	}else{
		$.MessageBox("품절된 상품은 결제하실 수 없습니다.");
	}
});

// 일반결제
$(document).on("click", "#item_order_sheet .yes_payment", function(){
	// 정글북인경우 재고 체크
	/*
	console.log(orderItem.length);
	if(orderItem.length > 0){
		$.each(orderItem, function(i, v){
			get_jbook_list("goods", v.goodsNo, '');
		});
	}else{
		change_payment('');
	}
	*/
	if(is_jbook_runout == 0){
		change_payment('');
	}else{
		$.MessageBox("품절된 상품은 결제하실 수 없습니다.");
	}
});

// 정글북 재고체크 완료여부
function chk_soldout_flag(payment_type){
	console.log(soldout_flag[0], orderItem.length);
	//if(soldout_flag[0] == orderItem.length){
		if(soldout_flag[1] == 0){
			console.log("결제", payment_type);
			change_payment(payment_type);
		}else{
			$.MessageBox({
				buttonDone: "확인",
				message: "<center>구매 진행중인 제품 중<br/> 일시품절된 상품이 있어 장바구니로 돌아갑니다.<br/><br/> (품절상품 삭제 후 결제 해주세요.)</center>"
			}).done(function(){
				location.href = "<?=$item_directory ?>/item_cart.php";
			});
		}
	//}else{
		// to do something..
	//}
}

// 정글북 재고 확인
function get_jbook_list(menu, option, payment_type){
	change_payment(payment_type); // 재고확인 추후에 재작업
	/*
	$.ajax({
		url: '<?=$admin_directory?>/jbook_item_ajax.php',
		data: {
			mode : "get_item_list",
			menu : menu,
			option : option
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data, parseInt(data.data.total));

				if(data.data && parseInt(data.data.total) > 0){
					$.each(data.data.data, function(i, v){
						if(v.runout == "1"){ // 재고없음
							soldout_flag[1]++;
							set_update_item_soldout(v.goodsNo); // 상품 재고 없음 처리
						}
					});
				}

				soldout_flag[0]++;
				chk_soldout_flag(payment_type); // each end chk function
			}else{
				alert(data.data+"("+data.code+")");
				console.log(data.code);
			}
		},
		error: function(xhr, status, error) {
			//alert(error + "네트워크에러");
			if(xhr.status != 0){
				alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
			}
		}
	});
	*/
}

// 상품 재고 없음 처리
function set_update_item_soldout(goodsNo){
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "set_update_item_soldout",
			goodsNo: goodsNo
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
			}else{
				alert(data.data+"("+data.code+")");
				console.log(data.code);
			}
		},
		error: function(xhr, status, error) {
			//alert(error + "네트워크에러");
			if(xhr.status != 0){
				alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
			}
		}
	});
}

function change_payment(type){
	var reserve = "vbank_receipt=N&bank_receipt=N&cp_yn=N";
	var reserve2 = "Card";
	var reserve3 = "HPP(1):no_receipt:va_receipt:vbanknoreg(0):below1000";

	if(type == "naverpay"){
		reserve += "&d_npay=Y";
		reserve2 = "onlynaverpay";
		reserve3 += ":cardonly";				
	}else if(type == "kakaopay"){
		reserve += "&d_kakaopay=Y";
		reserve2 = "onlykakaopay";
		reserve3 += ":cardonly";				
	}

	if(parseInt("<?=$token_index ?>") > 0){
		reserve += "&app_scheme=banjjack://";
	}else{
		reserve += "&app_scheme=banjjack";
	}

	$("#INIpay_form input[name='P_RESERVED']").val(reserve);
	$("#SendPayForm_id input[name='gopaymethod']").val(reserve2);
	$("#SendPayForm_id input[name='acceptmethod']").val(reserve3);

	yes_payment();
}

// 결제
function yes_payment(){
	var _this = $(this);
	_this.prop("disabled", true);
	if($("#item_order_sheet input[name='customer_name']").val() == ""){
		$.MessageBox("주문자 성명을 입력해주세요.");
		$("#item_order_sheet input[name='customer_name']").focus();
		_this.prop("disabled", false);
		return false;
	}
	if($("#item_order_sheet input[name='cellphone']").val() == "010" || $("#item_order_sheet input[name='cellphone']").val() == ""){
		$.MessageBox("주문자 연락처를 입력해주세요.");
		$("#item_order_sheet input[name='cellphone']").focus();
		_this.prop("disabled", false);
		return false;
	}
	if($("#item_order_sheet input[name='customer_email']").val() == ""){
		$.MessageBox("주문자 이메일을 입력해주세요.");
		$("#item_order_sheet input[name='customer_email']").focus();
		_this.prop("disabled", false);
		return false;
	}
	if($("#item_order_sheet input[name='customer_email_suffix']").val() == ""){
		$.MessageBox("주문자 이메일을 입력해주세요.");
		$("#item_order_sheet input[name='customer_email_suffix']").focus();
		_this.prop("disabled", false);
		return false;
	}
	if($("#item_order_sheet input[name='shipping_name']").val() == ""){
		$.MessageBox("배송 받으시는 분의 성명을 입력해주세요.");
		$("#item_order_sheet input[name='shipping_name']").focus();
		_this.prop("disabled", false);
		return false;
	}
	if($("#item_order_sheet input[name='shipping_cellphone']").val() == "010" || $("#item_order_sheet input[name='cellphone']").val() == ""){
		$.MessageBox("배송 받으시는 분의 연락처를 입력해주세요.");
		$("#item_order_sheet input[name='shipping_cellphone']").focus();
		_this.prop("disabled", false);
		return false;
	}
	if($("#item_order_sheet input[name='zipcode']").val() == ""){
		$.MessageBox("배송 받으실 주소를 검색해주세요.");
		$("#item_order_sheet input[name='zipcode']").focus();
		_this.prop("disabled", false);
		return false;
	}
	if($("#item_order_sheet input[name='addr4']").val() == ""){
		$.MessageBox("배송 받으실 상세주소를 입력해주세요.");
		$("#item_order_sheet input[name='addr4']").focus();
		_this.prop("disabled", false);
		return false;
	}
	if($("#item_order_sheet textarea[name='shipping_memo']").val() == ""){
		$.MessageBox("택배 요청사항을 입력해주세요.");
		$("#item_order_sheet textarea[name='shipping_memo']").focus();
		_this.prop("disabled", false);
		return false;
	}
	if($("#item_order_sheet input[name='bank_name']").val() == "" && $("#item_order_sheet input:radio[name='pay_type']:checked").val() == "2"){
		$.MessageBox("입금자명을 입력해주세요.");
		$("#item_order_sheet input[name='bank_name']").focus();
		_this.prop("disabled", false);
		return false;
	}
	if($("#chk_ag").is(":checked") == false){
		$.MessageBox("구매진행에 동의해주세요.");
		_this.prop("disabled", false);
		return false;
	}
	if($("#item_order_sheet input[name='customer_id']").val() == ""){
		if($("#chk_ag2").is(":checked") == false){
			$.MessageBox("비회원 주문에 대한 개인정보 활용에 동의해주세요.");
			_this.prop("disabled", false);
			return false;
		}
	}

	if($("#item_order_sheet input[name='is_newyear']").is(":checked") == true){
		//console.log($("#item_order_sheet input[name='is_newyear']").val());
		$.ajax({
			type: 'post',
			url: "<?=$item_directory ?>/item_list_ajax.php",
			data: {
				mode: "set_insert_newyear_event",
				order_num: no,
				customer_id: "<?=$user_id ?>",
				point: $("#item_order_sheet input[name='is_newyear']").val()
			},
			dataType: 'json',
			beforeSend: function() {
				  //$("#loading").css("display","flex");
			},
			error: function() {
			},
			success: function(json) {
				if(json.code == "000000"){
					console.log(json.data);
					
				}else{
					alert(json.data+"("+json.code+")");
				}
			},
			complete: function() {
				// console.log('complete');
				//서브밋 차단 해제
				$("#loading").css("display","none");
			}
		});
	}

	var is_payment = false;
	var post_data = $("#item_payment").serialize();
	post_data += "&mode=set_insert_item_payment_log_ready";
	//console.log(data);
	if(is_payment == false){
		is_payment = true;
		$.ajax({
			type: 'post',
			url: "<?=$item_directory ?>/item_list_ajax.php",
			data: {
				mode: "get_item_payment_log_chk",
				order_num: no
			},
			dataType: 'json',
			beforeSend: function() {
				  $("#loading").css("display","flex");
			},
			error: function() {
				is_payment = false;
				_this.prop("disabled", false);
			},
			success: function(json) {
				is_payment = false;
				_this.prop("disabled", false);
				if(json.code == "000000"){
					console.log(json.data);
					if(json.data == "0"){
						if(orderItem.length > 0){ // 정글북이면 정글북 등록 후 결제 준비 > 이동
							$.ajax({
								type: 'post',
								url: "<?=$admin_directory ?>/jbook_item_ajax.php",
								data: {
									mode: "set_insert_item_order",
									order_num: no,
									orderItem: orderItem
								},
								dataType: 'json',
								beforeSend: function() {
									  //$("#loading").css("display","flex");
								},
								error:function(request,status,error){
									$.MessageBox("에러가 발생했습니다.");
									alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
								},
								success: function(json) {
									if(json.code == "000000"){
										console.log(json.data);

										$.ajax({
											type: 'post',
											url: "<?=$item_directory ?>/item_list_ajax.php",
											data: post_data,
											dataType: 'json',
											beforeSend: function() {
												  //$("#loading").css("display","flex");
											},
											success: function(json) {
												if(json.code == "000000"){
													console.log(json.data);
													order_next(json.data.price, json.data.product_name, json.data.pay_type);
												}else if(json.code == "003902"){ // 이미 등록된 결제 진행이 있음
													$.MessageBox({
														buttonDone: "확인",
														message: "결제를 다시 진행해 주십시요."
													}).done(function(){
														location.href = "<?=$mainpage_directory ?>/index.php";
													});
													return false;
												}else{
													alert(json.data+"("+json.code+")");
													console.log(json.data);
												}
											},
											complete: function() {
												// console.log('complete');
												//서브밋 차단 해제
												$("#loading").css("display","none");
											},
											error:function(request,status,error){
												$.MessageBox("에러가 발생했습니다.");
												alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
											}
										});
									}else{
										alert(json.data+"("+json.code+")");
									}
								},
								complete: function() {
									// console.log('complete');
									//서브밋 차단 해제
									$("#loading").css("display","none");
								}
							});
						}else{ // 정글북 아니면 바로 결제처리
							$.ajax({
								type: 'post',
								url: "<?=$item_directory ?>/item_list_ajax.php",
								data: post_data,
								dataType: 'json',
								beforeSend: function() {
									  //$("#loading").css("display","flex");
								},
								success: function(json) {
									if(json.code == "000000"){
										console.log(json.data);
										order_next(json.data.price, json.data.product_name, json.data.pay_type);
									}else if(json.code == "003902"){ // 이미 등록된 결제 진행이 있음
										$.MessageBox({
											buttonDone: "확인",
											message: "결제를 다시 진행해 주십시요."
										}).done(function(){
											location.href = "<?=$mainpage_directory ?>/index.php";
										});
										return false;
									}else{
										alert(json.data+"("+json.code+")");
										console.log(json.data);
									}
								},
								complete: function() {
									// console.log('complete');
									//서브밋 차단 해제
									$("#loading").css("display","none");
								},
								error:function(request,status,error){
									$.MessageBox("에러가 발생했습니다.");
									alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
								}
							});
						}
					}else{
						// 이미 입력됨
						var is_chk = 0;
						$.each(json.data, function(i, v){
							if(v.pay_status == '1' && v.receipt_id === null){
								is_chk++;
							}
						});
						if(is_chk > 0){
							if(is_chk == 1){
								//그러시면 결제로 바로 가시죠
								//alert("재결제");
								var pay_type_arr = ['', 'card', 'bank'];
								var total_price = $('#item_order_sheet input[name="total_price"]').val();
								var product_name = $('#item_order_sheet input[name="product_name"]').val();
								var pay_type = $('#item_order_sheet input[name="pay_type"]:checked').val();
								//order_next(total_price, product_name, pay_type_arr[pay_type]);
								// 재결제 루틴 필요
								$.ajax({
									type: 'post',
									url: "<?=$item_directory ?>/item_list_ajax.php",
									data: {
										mode: "set_update_item_payment_retry",
										pay_type: pay_type,
										product_name: product_name,
										total_price: total_price,
										order_num: no
									},
									dataType: 'json',
									beforeSend: function() {
										  $("#loading").css("display","flex");
									},
									success: function(json) {
										if(json.code == "000000"){
											console.log(json.data);
											order_next(total_price, product_name, pay_type_arr[pay_type]);
										}else{
											alert(json.data+"("+json.code+")");
											console.log(json.data);
										}
									},
									complete: function() {
										// console.log('complete');
										//서브밋 차단 해제
										$("#loading").css("display","none");
									},
									error:function(request,status,error){
										$.MessageBox("에러가 발생했습니다.");
										alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
										$("#loading").css("display","none");
									}
								});
							}else{
								// 결제건 중복이므로 문의
								alert("결제도중 문제가 발생했습니다.");
							}
						}
					}
				}else{
					alert(json.data+"("+json.code+")");
				}
			},
			complete: function() {
				// console.log('complete');
				//서브밋 차단 해제
				$("#loading").css("display","none");
				is_payment = false;
				_this.prop("disabled", false);
			}
		});
	}else{
		// To do something..
		// 따닥방지
		is_payment = false;
	}
}

// 결제 취소 버튼
$(document).on("click", "#item_order_sheet .no_payment", function(){
	$.MessageBox({
		buttonFail: "아니오",
		buttonDone: "예",
		message: "<center><font style='font-size:15px;font-weight:bold;'>결제 진행을 중단 하시겠습니까?</font></center>"
	}).done(function() {
		// 결제 쿠키 초기화
		if(checkMobile2() == "in_app_ios"){
			setCookie_ios("order_num", "", -1); 
			setCookie_ios("order_step", "", -1);
			setCookie_ios("is_shop", "", -1);
		}else{
			setCookie("order_num", "", -1); 
			setCookie("order_step", "", -1);
			setCookie("is_shop", "", -1);
		}
		location.href="<?=$mainpage_directory?>/";
	});
});

/////////////////////////////////
// 정회원 주소 리스트 시작
/////////////////////////////////

// 배송지 추가 모달창
$(document).on("click", "#item_payment .customer_addr_list_btn", function(){
	var html = '';
	$("#customer_addr_wrap").dialog({
		modal: true,
		title: '배송지 추가/변경',
		autoOpen: true,
		maxWidth: "96%",
		minHeight: Number($(window).height()) - 40,
		width: "96%",
		height: Number($(window).height()) - 40,
		autoSize: true,
		resizable: false,
		draggable: false,
		open: function(event, ui) {
			// to do something...
			get_customer_addr_list();
		},
		close: function() {
			// to do something...
		}
	});
});

// 주소 리스트 호출(정회원만)
function get_customer_addr_list(){
	$.ajax({
		type: 'post',
		url: "<?=$mainpage_directory?>/customer_ajax.php",
		data: {
			mode: "get_customer_addr_list",
			customer_id: "<?= $user_id ?>"
		},
		dataType: 'json',
		beforeSend: function() {
			  //$("#loading").css("display","flex");
		},
		error:function(request,status,error){
			$.MessageBox("에러가 발생했습니다.");
			alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		},
		success: function(json) {
			if(json.code == "000000"){
				//console.log(json.data);
				var html = '';
				html += '<button type="button" class="add_customer_addr" style="background:#fff; border:1px solid #736452; color:#736452;  border-radius:5px; margin:5px; padding:10px; width:40%; font-weight:bold;">배송지 추가 +</button>';
				html += '<ul>';
				$.each(json.data, function(i, v){
					html += '	<li style="margin-top:10px;">';
					html += '		<input type="radio" id="choice_addr_'+(i+1)+'" name="choice_addr" value="'+v.ca_seq+'" />';
					html += '		<label for="choice_addr_'+(i+1)+'">'+v.addr_name+' | '+v.road_addr+' '+v.detail_addr+'</label>';
					html += '		<a href="javascript:;" class="set_delete_addr_btn" data-ca_seq="'+v.ca_seq+'"><i style="color:#fff; border:1px solid #f5bf2e; padding:3px; border-radius:5px; background:#f5bf2e" class="fas fa-times"></i></a>';
					html += '	</li>';
				});
				html += '</ul>';
				html += '<button type="button" class="choice_addr_btn" style="background:#f5bf2e; border:none; color:#fff; margin:0px auto; border-radius:5px; padding:5px; width:100%; margin-top:20px;">배송지선택</button>';
				$("#one_page_window").html('').html(html);

				// addr_loading
				$.ajax({
					type: 'post',
					url: "<?=$mainpage_directory?>/customer_ajax.php",
					data: {
						mode: "get_customer_addr_list",
						customer_id: "<?= $user_id ?>"
					},
					dataType: 'json',
					beforeSend: function() {
						  //$("#loading").css("display","flex");
					},
					error: function() {
					},
					success: function(json) {
						if(json.code == "000000"){
							//console.log(json.data);
							var html = '';
							$.each(json.data, function(i, v){
								html += '<span>';
								html += '	<input type="radio" id="choice_addr_'+(i+1)+'" name="choice_addr" value="'+v.ca_seq+'" />';
								html += '	<label for="choice_addr_'+(i+1)+'">'+v.addr_name+'</label>';
								html += '</span>';
							});
							html += '<button type="button" class="customer_addr_list_btn" style="background:#fff; border:1px solid #736452; color:#736452;  border-radius:5px; margin:5px; padding:10px; width:40%;">배송지추가</button>';
							$("#item_order_sheet .shipping_addr_wrap").html('').append(html);
						}else{
							alert(json.data+"("+json.code+")");
						}
					},
					complete: function() {
						// console.log('complete');
						//서브밋 차단 해제
						//$("#loading").css("display","none");
					}
				});
			}else{
				alert(json.data+"("+json.code+")");
			}
		},
		complete: function() {
			// console.log('complete');
			//서브밋 차단 해제
			//$("#loading").css("display","none");
		}
	});
}

// 배송지 선택 시 배송지 주소 입력
$(document).on("click", "#customer_addr_wrap .choice_addr_btn", function(){
	var choice_addr_seq = $("input[name='choice_addr']:checked").val();
	
	if(choice_addr_seq && choice_addr_seq != ""){
		$.ajax({
			type: 'post',
			url: "<?=$mainpage_directory?>/customer_ajax.php",
			data: {
				mode: "get_customer_addr",
				customer_id: "<?= $user_id ?>",
				ca_seq: choice_addr_seq
			},
			dataType: 'json',
			beforeSend: function() {
				  //$("#loading").css("display","flex");
			},
			error: function() {
			},
			success: function(json) {
				if(json.code == "000000"){
					//console.log(json.data);
					// 배송지에 추가하기
					$.each(json.data, function(i, v){
						$("input[name='addr1']").val(v.road_addr);
						$("input[name='addr2']").val(v.jibun_addr);
						$("input[name='addr3']").val(v.extra_addr);
						$("input[name='addr4']").val(v.detail_addr);
						$("input[name='zipcode']").val(v.zipcode);
					});
					$("#customer_addr_wrap").dialog("close");
				}else{
					alert(json.data+"("+json.code+")");
				}
			},
			complete: function() {
				// console.log('complete');
				//서브밋 차단 해제
				//$("#loading").css("display","none");
			}
		});
	}else{
		$.MessageBox("배송지를 선택해주세요.");
	}
});

// 주소 생성 html
$(document).on("click", "#customer_addr_wrap .add_customer_addr", function(){
	var html = '';
	html += '<div>';
	html += '<form id="customer_addr">';
	html += '	<input type="hidden" name="customer_id" value="<?=$user_id ?>" />';
	html += '	<table class="order_table">';
	html += '		<colgroup>';
	html += '			<col width="30%" />';
	html += '			<col width="*" />';
	html += '		</colgroup>';
	html += '		<tr style="">';
	html += '			<td class="table_line order_right" style="padding:5px 5px 5px 5px;">주소명칭</td>';
	html += '			<td class="table_line" style="text-align:center;"><input type="text" name="addr_name" class="input"></td>';
	html += '		</tr>';
	html += '		<tr style="">';
	html += '			<td rowspan="3" class="table_line order_right" style="">주소검색</td>';
	html += '			<td class="table_line" style="text-align:center;">';
	html += '				<input type="text" name="zipcode" class="input2 postcode" placeholder="주소입력" readonly>';
	html += '				<button type="button" class="add_btn search_addr_btn" style="background:#f5bf2e; color:#fff; border-radius:5px; border:none; margin:5px; padding:5px; width:30%;">검색</button>';
	html += '			</td>';
	html += '		</tr>';
	html += '		<tr>';
	html += '			<td class="table_line" style="text-align:center;">';
	html += '				<input type="text" name="road_addr" class="input roadAddress" placeholder="기본주소" readonly>';
	html += '				<input type="hidden" name="jibun_addr" class="input jibunAddress" placeholder="지번주소" readonly>';
	html += '				<input type="hidden" name="extra_addr" class="input extraAddress" placeholder="지번주소" readonly>';
	html += '			</td>';
	html += '		</tr>';
	html += '		<tr>';
	html += '			<td class="table_line" style="text-align:center;"><input type="text" name="detail_addr" class="input detailAddress" placeholder="상세주소" >';
	html += '			</td>';
	html += '		</tr>';
	html += '	</table>';
	html += '</form>';
	html += '<button class="add_customer_addr_btn" style="background:#f5bf2e; color:#fff; border-radius:5px; border:none; margin:5px; margin-left:0px;padding:5px; width:30%;">추가</button>';
	html += '<button class="customer_addr_list_btn" style="background:#f5bf2e; color:#fff; border-radius:5px; border:none; margin:5px; padding:5px; width:30%;">목록</button>';
	html += '</div>';
	$("#one_page_window").html('').html(html);
});

// 주소 목록으로
$(document).on("click", "#customer_addr_wrap .customer_addr_list_btn", function(){
	get_customer_addr_list();
});

/***************** 주소 생성 중복 데이터로 판단하여 주석처리
// 주소 생성
$(document).on("click", "#customer_addr_wrap .set_insert_customer_addr", function(){
	$.ajax({
		type: 'post',
		url: "<?=$item_directory ?>/item_list_ajax.php",
		data: {
			mode: "set_insert_customer_addr",
			customer_id: "<?= $artist_id ?>",
			road_addr: $("#customer_addr_wrap").find(".roadAddress").val(),
			jibun_addr: $("#customer_addr_wrap").find(".jibunAddress").val(),
			extra_addr: $("#customer_addr_wrap").find(".extraAddress").val(),
			detail_addr: $("#customer_addr_wrap").find(".detailAddress").val(),
			zipcode: $("#customer_addr_wrap").find(".postcode").val(),
			addr_name: $("#customer_addr_wrap").find(".addr_name").val()
		},
		dataType: 'json',
		beforeSend: function() {
			  $("#loading").css("display","flex");
		},
		error: function() {
		},
		success: function(json) {
			if(json.code == "000000"){
				//console.log(json.data);
				get_customer_addr_list();
			}else{
				alert(json.data+"("+json.code+")");
			}
		},
		complete: function() {
			// console.log('complete');
			//서브밋 차단 해제
			$("#loading").css("display","none");
		}
	});
});
*/

// 주소 입력
$(document).on("click", "#customer_addr_wrap .add_customer_addr_btn", function(){
	var data = $("#customer_addr").serialize();
	data += "&mode=set_insert_customer_addr";

	$.ajax({
		type: 'post',
		url: "<?=$mainpage_directory?>/customer_ajax.php",
		data: data,
		dataType: 'json',
		beforeSend: function() {
			 //$("#loading").css("display","flex");
		},
		error: function() {
		},
		success: function(json) {
			if(json.code == "000000"){
				//console.log(json.data);
				get_customer_addr_list();
			}else{
				alert(json.data+"("+json.code+")");
			}
		},
		complete: function() {
			// console.log('complete');
			//서브밋 차단 해제
			//$("#loading").css("display","none");
		}
	});
});

// 주소 삭제
$(document).on("click", "#customer_addr_wrap .set_delete_addr_btn", function(){
	var ca_seq = $(this).data("ca_seq");

	$.MessageBox({
		buttonFail: "아니오",
		buttonDone: "예",
		message: "<center><font style='font-size:15px;font-weight:bold;'>해당 주소를 삭제 하시겠습니까?</font></center>"
	}).done(function() {
		$.ajax({
			type: 'post',
			url: "<?=$mainpage_directory?>/customer_ajax.php",
			data: {
				mode: "set_delete_customer_addr",
				ca_seq: ca_seq,
				customer_id: "<?= $user_id ?>",
				delete_txt: "직접 삭제"
			},
			dataType: 'json',
			beforeSend: function() {
				  //$("#loading").css("display","flex");
			},
			error: function() {
			},
			success: function(json) {
				if(json.code == "000000"){
					//console.log(json.data);
					get_customer_addr_list();
				}else{
					alert(json.data+"("+json.code+")");
				}
			},
			complete: function() {
				// console.log('complete');
				//서브밋 차단 해제
				//$("#loading").css("display","none");
			}
		});
	});
});

/////////////////////////////////
// 정회원 주소 리스트 끝
/////////////////////////////////


/////////////////////////////////
// 주소 입력창 시작
/////////////////////////////////

// 주소 검색 모달창
$(document).on("click", "#customer_addr_wrap .search_addr_btn", function(){
	$("#search_addr_wrap").dialog({
		modal: true,
		title: '우편번호 검색',
		autoOpen: true,
		maxWidth: "96%",
		minHeight: Number($(window).height()) - 40,
		width: "96%",
		height: Number($(window).height()) - 40,
		autoSize: true,
		resizable: false,
		draggable: false,
		open: function(event, ui) {
			// to do something...
			execDaumPostcode($("#customer_addr_wrap"));
		},
		close: function() {
			// to do something...
		}
	});
});

// 주소 검색 모달창
$(document).on("click", "#item_order_sheet .search_addr_btn", function(){
	$("#search_addr_wrap").dialog({
		modal: true,
		title: '우편번호 검색',
		autoOpen: true,
		maxWidth: "96%",
		minHeight: Number($(window).height()) - 40,
		width: "96%",
		height: Number($(window).height()) - 40,
		autoSize: true,
		resizable: false,
		draggable: false,
		open: function(event, ui) {
			// to do something...
			execDaumPostcode($("#item_payment"));
		},
		close: function() {
			// to do something...
		}
	});
});

// 우편번호 찾기 찾기 화면을 넣을 element
var element_wrap = document.getElementById('search_addr_wrap');

function execDaumPostcode(target) {
	// 현재 scroll 위치를 저장해놓는다.
	var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
	new daum.Postcode({
		oncomplete: function(data) {
			// 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

			// 각 주소의 노출 규칙에 따라 주소를 조합한다.
			// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
			var addr = ''; // 주소 변수
			var extraAddr = ''; // 참고항목 변수

			//사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
			/*
			if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
				addr = data.roadAddress;
			} else { // 사용자가 지번 주소를 선택했을 경우(J)
				addr = data.jibunAddress;
			}
			*/

			// 사용자가 선택한 주소가 도로명 타입일때 참고항목을 조합한다.
			if(data.userSelectedType === 'R'){
				// 법정동명이 있을 경우 추가한다. (법정리는 제외)
				// 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
				if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
					extraAddr += data.bname;
				}
				// 건물명이 있고, 공동주택일 경우 추가한다.
				if(data.buildingName !== '' && data.apartment === 'Y'){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
				if(extraAddr !== ''){
					extraAddr = ' (' + extraAddr + ')';
				}
				// 조합된 참고항목을 해당 필드에 넣는다.
				//document.getElementById("extraAddress").value = extraAddr;
				target.find(".extraAddress").val(extraAddr);
			
			} else {
				//document.getElementById("extraAddress").value = '';
				target.find(".extraAddress").val('');
			
			}
			/*
			// 우편번호와 주소 정보를 해당 필드에 넣는다.
			document.getElementById('postcode').value = data.zonecode;
			document.getElementById("roadAddress").value = data.roadAddress;
			document.getElementById("jibunAddress").value = data.jibunAddress;
			// 커서를 상세주소 필드로 이동한다.
			document.getElementById("detailAddress").focus();
			*/
			target.find(".postcode").val(data.zonecode);
			target.find(".roadAddress").val(data.roadAddress);
			target.find(".jibunAddress").val(data.jibunAddress);
			target.find(".detailAddress").focus();

			// iframe을 넣은 element를 안보이게 한다.
			// (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
			element_wrap.style.display = 'none';
			$("#search_addr_wrap").dialog('close');

			// 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
			document.body.scrollTop = currentScroll;
		},
		// 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분. iframe을 넣은 element의 높이값을 조정한다.
		onresize : function(size) {
			element_wrap.style.height = size.height+'px';
		},
		width : '100%',
		height : '100%'
	}).embed(element_wrap);

	// iframe을 넣은 element를 보이게 한다.
	element_wrap.style.display = 'block';
}
/////////////////////////////////
// 주소 입력창 끝
/////////////////////////////////


/////////////////////////////////
// 결제 모듈 시작
/////////////////////////////////
var is_app = <?=$is_android?>;
var is_iOS = <?=$is_ios?>;
if (is_app == false){
    is_app = is_iOS;
}
// 사파리 모바일에서 MacOS로 체크되는 경우 예외처리용
var isIOSchk = /iPad|iPhone|iPod/.test(navigator.platform) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);

// accept-charset 지원하지 않는 브라우저 처리용
function emulAcceptCharset(form) {
	if (form.canHaveHTML) { // detect IE
		document.charset = form.acceptCharset;
	}
	return true;
}

// 1. 모바일, PC 결제여부 분기
function order_next (pprice, pname, payment_type) {
	//console.log(pprice, pname, payment_type, is_app, moblieChk);
    if (pprice > 0) {
		$("#INIpay_form").find('input[name="P_AMT"]').val(pprice);
        if (payment_type == 'bank') {
            // 결제모듈 없이 바로 완료처리
			set_update_item_payment_log_price_zero();
			return false;
        }else{
			if (is_app) {
				$("#INIpay_form").submit();
			} else {
				if(moblieChk == "mobile"){
					$("#INIpay_form").submit();
				}else{
					if(isIOSchk == true){
						$("#INIpay_form").submit();
					}else{
						// PC init
						$.MessageBox("모바일에서만 결제가 가능합니다.");
						return false;
						/*
						$.ajax({
							type: 'post',
							url: "<?=$item_directory ?>/item_list_ajax.php",
							data: {
								mode: "INI_PC_init",
								product_no: no,
								price: pprice
							},
							dataType: 'json',
							beforeSend: function() {
								 //$("#loading").css("display","flex");
							},
							error:function(request,status,error){
								$.MessageBox("에러가 발생했습니다.");
								alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
							},
							success: function(json) {
								if(json.code == "000000"){
									console.log(json.data);
									$("#SendPayForm_id").find('input[name="mid"]').val(json.data.mid);
									$("#SendPayForm_id").find('input[name="price"]').val(pprice);
									$("#SendPayForm_id").find('input[name="goodname"]').val(pname);
									$("#SendPayForm_id").find('input[name="timestamp"]').val(json.data.timestamp);
									$("#SendPayForm_id").find('input[name="signature"]').val(json.data.sign);
									$("#SendPayForm_id").find('input[name="mKey"]').val(json.data.mKey);
									$("#SendPayForm_id").find('input[name="nointerest"]').val(json.data.cardNoInterestQuota);
									$("#SendPayForm_id").find('input[name="quotabase"]').val(json.data.cardQuotaBase);									

									INIStdPay.pay('SendPayForm_id'); // PC
								}else{
									alert(json.data+"("+json.code+")");
								}
							},
							complete: function() {
								// console.log('complete');
								//서브밋 차단 해제
								//$("#loading").css("display","none");
							}
						});
						*/
					}
				}
			}
		}
    }else if (pprice == 0) {
		set_update_item_payment_log_price_zero();
    }
}

function set_update_item_payment_log_price_zero(){
	// 회원만 포인트로 가격을 0 만들 수 있으므로 정회원 기준으로 작업
	$.ajax({
		type: 'post',
		url: "<?=$item_directory ?>/item_list_ajax.php",
		data: {
			mode: "set_update_item_payment_log_price_zero",
			order_num: no,
			customer_id: "<?=$user_id ?>" 
		},
		dataType: 'json',
		beforeSend: function() {
			  //$("#loading").css("display","flex");
		},
		error:function(request,status,error){
			$.MessageBox("에러가 발생했습니다.");
			alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		},
		success: function(json) {
			if(json.code == "000000"){
				console.log(json.data);
				location.href = "<?=$item_directory ?>/item_payment_result.php?no="+no;
			}else{
				alert(json.data+"("+json.code+")");
			}
		},
		complete: function() {
			// console.log('complete');
			//서브밋 차단 해제
			//$("#loading").css("display","none");
		}
	});
}
/////////////////////////////////
// 결제모듈 끝
/////////////////////////////////

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

// 쿠키 생성
function setCookie(cName, cValue, cDay){
	var expire = new Date();
	expire.setDate(expire.getDate() + cDay);
	cookies = cName + '=' + escape(cValue) + '; path=/ '; // 한글 깨짐을 막기위해 escape(cValue)를 합니다.
	if(typeof cDay != 'undefined') cookies += ';expires=' + expire.toGMTString() + '; SameSite=None; Secure ';
	document.cookie = cookies;
}

function setCookie_ios(cName, cValue, cDay) { // IOS bug - SameSite=None; Secure를 SameSite=static으로 인식하는 문제
	var expire = new Date();
	expire.setDate(expire.getDate() + cDay);
	cookies = cName + '=' + escape(cValue) + '; path=/ '; // 한글 깨짐을 막기위해 escape(cValue)를 합니다.
	if(typeof cDay != 'undefined') cookies += ';expires=' + expire.toGMTString() + '; ';
	document.cookie = cookies;
}

// 쿠키 가져오기
function getCookie(cName) {
	cName = cName + '=';
	var cookieData = document.cookie;
	var start = cookieData.indexOf(cName);
	var cValue = '';
	if(start != -1){
		start += cName.length;
		var end = cookieData.indexOf(';', start);
		if(end == -1)end = cookieData.length;
		cValue = cookieData.substring(start, end);
	}
	return unescape(cValue);
}
</script>