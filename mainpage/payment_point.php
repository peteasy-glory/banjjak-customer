<?php include "../include/top.php"; ?>

<?php include "../include/check_login.php"; ?>
<?php include "../include/Point.class.php"; ?>
<?php include "../include/App.class.php"; ?>

<?php
$is_android = 0;
$app = new App();
if ($app->is_app() == 1) {
    $is_android = 1;
}
?>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">

<div class="top_menu">
    <div class="top_back" style="top:13px;"><a href="<?= $mainpage_directory ?>/manage_my_point.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div class="top_title">
        <p>포인트 충전하기</p>
    </div>
</div>
<?php
$price = $_REQUEST['p'];
$user_id = $_SESSION['gobeauty_user_id'];
$_SESSION['gobeauty_order_id'] = str2hex($user_id) . "_" . rand_id();
$_SESSION['gobeauty_point_price'] = $price;
?>

<div id="payment_point">
	<div class="point_wrap">
		<div class="point_big_title"> 현재 포인트</div>
		<div class="point_score">
			<?php
			$point = new Point;
			$result = $point->select_point($user_id);
			if ($result == true) {
				echo number_format($point->get_point());
			} else {
				echo "0";
			}
			?>
			점
		</div>
	</div>


	<!--a href="?p=1000">
			<div width='100%'
					<?php
					if ($price == 1000) {
						//echo "style='padding:7px;border:2px solid #f5bf2e;color:#f5bf2e;font-weight:bold;background-color:#f5bf2e;'";
						echo "style='padding:7px;border:0px solid #f5bf2e;color:#fff;font-weight:bold;background-color:#f5bf2e;'";
					} else {
						echo "style='padding:7px;border:1px solid #adacac;color:#000;'";
					}
					?>
					>
					결제 테스트용 1천원 충전
			</div>
			</a-->

	<div class="cash_wrap">
		<a href="?p=30000">
			<div width='100%' <?php
								if ($price == 30000) {
									//echo "style='padding:7px;border:2px solid #f5bf2e;color:#f5bf2e;font-weight:bold;background-color:#f5bf2e;'";
									echo "style='padding:7px;border:0px solid #f5bf2e;color:#fff;font-weight:bold;background-color:#f5bf2e;'";
								} else {
									echo "style='padding:7px;border:1px solid #adacac;color:#000;'";
								}
								?>>
				3만원 충전
			</div>
		</a>



		<a href="?p=50000">
			<div width='100%' <?php
								if ($price == 50000) {
									//echo "style='padding:7px;border:2px solid #f5bf2e;color:#f5bf2e;font-weight:bold;background-color:#f5bf2e;'";
									echo "style='padding:7px;border:0px solid #f5bf2e;color:#fff;font-weight:bold;background-color:#f5bf2e;'";
								} else {
									echo "style='padding:7px;border:1px solid #adacac;color:#000;'";
								}
								?>>
				5만원 충전
			</div>
		</a>



		<a href="?p=100000">
			<div width='100%' <?php
								if ($price == 100000) {
									//echo "style='padding:7px;border:2px solid #f5bf2e;color:#f5bf2e;font-weight:bold;background-color:#f5bf2e;'";
									echo "style='padding:7px;border:0px solid #f5bf2e;color:#fff;font-weight:bold;background-color:#f5bf2e;'";
								} else {
									echo "style='padding:7px;border:1px solid #adacac;color:#000;'";
								}
								?>>
				10만원 충전
			</div>
		</a>



		<a href="?p=200000">
			<div width='100%' <?php
								if ($price == 200000) {
									//echo "style='padding:7px;border:2px solid #f5bf2e;color:#f5bf2e;font-weight:bold;background-color:#f5bf2e;'";
									echo "style='padding:7px;border:0px solid #f5bf2e;color:#fff;font-weight:bold;background-color:#f5bf2e;'";
								} else {
									echo "style='padding:7px;border:1px solid #adacac;color:#000;'";
								}
								?>>
				20만원 충전
			</div>
		</a>



		<a href="?p=300000">
			<div width='100%' <?php
								if ($price == 300000) {
									//echo "style='padding:7px;border:2px solid #f5bf2e;color:#f5bf2e;font-weight:bold;background-color:#f5bf2e;'";
									echo "style='padding:7px;border:0px solid #f5bf2e;color:#fff;font-weight:bold;background-color:#f5bf2e;'";
								} else {
									echo "style='padding:7px;border:1px solid #adacac;color:#000;'";
								}
								?>>
				30만원 충전
			</div>
		</a>



		<a href="?p=500000">
			<div width='100%' <?php
								if ($price == 500000) {
									//echo "style='padding:7px;border:2px solid #f5bf2e;color:#f5bf2e;font-weight:bold;background-color:#f5bf2e;'";
									echo "style='padding:7px;border:0px solid #f5bf2e;color:#fff;font-weight:bold;background-color:#f5bf2e;'";
								} else {
									echo "style='padding:7px;border:1px solid #adacac;color:#000;'";
								}
								?>>
				50만원 충전
			</div>
		</a>
	</div>
	<hr style="color:#999999;width:100%;border:0;border:3px solid #e1e1e1; margin: 20px 0px;">

	<div style="width: 90%; margin: 0px auto; ">
		<!--div width='100%' style="padding:5px;border:0px solid #f5bf2e;background-color:#000;"-->
		<!--div width='100%' class="gobeauty_color bold" style='padding:1px;font-size:15px;'> 충전후 포인트</div-->
		<div style="text-align: center;font-size:18px;">충전 후 포인트</div>
		<!--div width='100%' class="gobeauty_color bold" style='padding:1px;font-size:16px;color:#fff;'> 충전후 포인트</div-->
		<div class="point_score">
			<?php
			echo number_format($point->get_point() + intval($price));
			?>
			점
		</div>
	</div>

	<meta name="bootpay-application-id" content="5acc2185b6d49c7b637d9c12" />
	<script src="https://cdn.bootpay.co.kr/js/bootpay-1.1.3.min.js"></script>

	<form action="payment_point_process.php" id="shop_form" method="POST">
		<input type="hidden" id="receipt_id" name="receipt_id" value="-">
		<center>
			<table style="border:0px solid #ffffff;width:100%;font-size:12px;">
				<script>
					/*function checkDisable()
	{
		if (document.getElementById("agreement").checked == true) {
			document.getElementById("payment_submit_button").disabled = false;
		} else {
			document.getElementById("payment_submit_button").disabled = true;
		}
	}*/

					var pay_flag = 0;

					function set_pay_notice_flag() {
						if (pay_flag == 0) {
							pay_flag = 1;
							document.getElementById("pay_notice").style.display = "block";
						} else {
							pay_flag = 0;
							document.getElementById("pay_notice").style.display = "none";
						}
					}
				</script>

				<script>
					var is_app = <?= $is_android ?>;

					function buyBtnClickAndroid(product_price, product_name) {
						var gobeauty_order_id = '<?= $_SESSION['gobeauty_order_id'] ?>';
						window.Android.onBootpay(product_price, product_name, gobeauty_order_id);
					}

					function onBootpayDone(receipt_id, message) {
						//alert(receipt_id);
						//alert(message);
						document.getElementById('receipt_id').value = receipt_id;
						document.getElementById('shop_form').submit();
						return true;
					}

					function onBootpayCancel(receipt_id, message) {
						//alert(receipt_id);
						//alert(message);
						$.MessageBox({
							buttonDone: "확인",
							message: "\"" + message + "\"로 결제가 취소되었습니다."
						}).done(function() {});
						return false;
					}

					function onBootpayError(receipt_id, message) {
						//alert(receipt_id);
						//alert(message);
						$.MessageBox({
							buttonDone: "확인",
							message: "\"" + message + "\"로 에러가 발생하였습니다."
						}).done(function() {});
						return false;
					}

					function buyBtnClick(product_price, product_name) {
						var gobeauty_order_id = '<?= $_SESSION['gobeauty_order_id'] ?>';
						var pg_type = 'card';

						//결제요청을 위한 Api, 실제 사용 코드
						BootPay.request({
							price: product_price, //실제 결제되는 가격
							application_id: '5acc2185b6d49c7b637d9c0f',
							name: product_name, //결제창에서 보여질 이름
							pg: 'danal',
							show_agree_window: 0, // 부트페이 정보 동의 창 보이기 여부
							/*            items: [
									JSON.parse(product_list)
											{
												item_name: , //상품명
												qty: 1, //수량
												unique: 'P_A32332123', //해당 상품을 구분짓는 primary key
												price: 40000, //상품 단가
												cat1: '헤어', // 대표 상품의 카테고리 상, 50글자 이내
												cat2: '헤어', // 대표 상품의 카테고리 중, 50글자 이내
												cat3: '혼주머리(여성)', // 대표상품의 카테고리 하, 50글자 이내
											}
										],*/
							/*            user_info: {
											username: '양원일',
											email: 'sheepoi@naver.com',
											addr: '서울 강남구 논현로 608 덕수빌딩 7층 (주)픽몬',
											phone: '010-4635-4696'
										},*/
							method: pg_type, //결제수단, 입력하지 않으면 결제수단 선택부터 화면이 시작합니다.
							order_id: gobeauty_order_id, //관리하시는 고유 주문번호를 입력해주세요
							params: {
								callback1: 'result1',
								callback2: 'result2',
								customvar1234: 'test111'
							},
						}).error(function(data) {
							//결제 진행시 에러가 발생하면 수행됩니다.
							console.log("error");
							console.log(data);
							$.MessageBox({
								buttonDone: "확인",
								message: "\"" + data.message + "\"로 에러가 발생하였습니다."
							}).done(function() {});
							return false;
						}).cancel(function(data) {
							//결제가 취소되면 수행됩니다.
							console.log("cancel");
							console.log(data);
							$.MessageBox({
								buttonDone: "확인",
								message: "\"" + data.message + "\"로 결제가 취소되었습니다."
							}).done(function() {});
							return false;
						}).ready(function(data) {
							console.log("ready");
							console.log(data);
							/*	    console.log(data.receipt_id);
									console.log(data.bankcode);
									console.log(data.bankname);
									console.log(data.expiredate);
									console.log(data.item_name);
									console.log(data.status);*/
							document.getElementById('receipt_id').value = data.receipt_id;
							document.getElementById('shop_form').submit();
							return true;

						}).confirm(function(data) {
							//결제가 실행되기 전에 수행되며, 주로 재고를 확인하는 로직이 들어갑니다.
							//주의 - 카드 수기결제일 경우 이 부분이 실행되지 않습니다.
							console.log("confirm");
							console.log(data);
							//            if(somthing) { // 재고 수량 관리 로직 혹은 다른 처리
							this.transactionConfirm(data); // 조건이 맞으면 승인 처리를 한다.
							//            } else {
							//              this.removeWindow(); // 조건이 맞지 않으면 결제 창을 닫고 결제를 승인하지 않는다.
							//            }
						}).done(function(data) {
							//결제가 정상적으로 완료되면 수행됩니다
							//비즈니스 로직을 수행하기 전에 결제 유효성 검증을 하시길 추천합니다.
							console.log("done");
							console.log(data);
							//                $.MessageBox({
							//                        buttonDone      : "확인",
							//                        message         : "\""+product_name+"\"상품이 결제되었습니다. 감사합니다."
							//                }).done(function(){
							document.getElementById('receipt_id').value = data.receipt_id;
							document.getElementById('shop_form').submit();
							//                });
							return true;
						});
					}
				</script>

				<!--tr>
			<td>
				<input type="checkbox" name="agreement" id="agreement">
			</td>
			<td>
				(필수) 구매내용과 예약취소규정을 확인하였습니다.
			</td>
			<td align="right">
				<a href="#" onclick="set_pay_notice_flag();">보기</a>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<iframe id="pay_notice" src="<?= $artist_directory ?>/pay_notice.php" style="overflow:hidden;height:100%;width:100%;display:none;border:1px solid #999999;" frameborder="1" width="100%" height="500" marginwidth="0" marginheight="0" scrolling="0"></iframe>
			</td>
		</tr-->
				<script>
					function zero_fill(str, cnt) {
						str = '000000' + str;
						return str.substr(str.length - cnt, cnt);
					}

					function order_next(pprice, pname) {
						if (pprice <= 0) {
							$.MessageBox({
								buttonDone: "확인",
								message: "상품을 선택해주세요."
							}).done(function() {});
							return false;
						}
						/*	var agree = document.getElementById('agreement');
								if ($(agree).prop("checked") == false) {
										$.MessageBox({
												buttonDone      : "확인",
												message         : "구매확인을 체크해주세요."
										}).done(function(){
										});
										return false;
								}*/

						if (pprice > 0) {
							if (is_app) {
								buyBtnClickAndroid(pprice, pname);
							} else {
								buyBtnClick(pprice, pname);
							}
						}
						return true;
					}
				</script>
				<tr>
					<td colspan="3">
						<div style="height:6px;width:100%;"></div>
						<?php
						$_SESSION['gobeauty_point_price'] = $price;
						$_SESSION['gobeauty_point_product'] = strval($price) . "원 포인트충전";
						?>
						<input onclick="order_next(<?= (intval($price)) ?>, '<?= $_SESSION['gobeauty_point_product'] ?>');" id="payment_submit_button" type="button" href="payment_process.php" class="pay_submit" value="결제하기">
						<font style="color:#ffffff;">결제하기</font></input>

					</td>
				</tr>
			</table>
		</center>
	</form>
</div>
<?php include "../include/bottom.php"; ?>