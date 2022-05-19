<?php
	session_id($_GET["PHPSESSID"]); // 세션 유지를 위해 GET으로 가져옴

	if( !class_exists('XenoPostToForm') ){
		class XenoPostToForm {
			public static function check() {
				return !isset($_COOKIE['PHPSESSID']) && count($_POST) && isset($_SERVER['HTTP_REFERER']) && !preg_match('~^https://'.preg_quote($_SERVER['HTTP_HOST'], '~').'/~', $_SERVER['HTTP_REFERER']);
			}

			public static function submit($posts) {
				echo '<html><head><meta charset="UTF-8"></head><body>';
				echo '<form id="f" name="f" method="post">';
				echo self::makeInputArray($posts);
				echo '</form>';
				echo '<script>';
						echo 'document.f.submit();';
						echo '</script></body></html>';
				exit;
			}

			public static function makeInputArray($posts) {
				$res = [];
				foreach($posts as $k => $v) {
					$res[] = self::makeInputArray_($k, $v);
				}
				return implode('', $res);
			}

			private static function makeInputArray_($k, $v) {
				if(is_array($v)) {
					$res = [];
					foreach($v as $i => $j) {
						$res[] = self::makeInputArray_($k.'['.htmlspecialchars($i).']', $j);
					}
					return implode('', $res);
				}
				return '<input type="hidden" name="'.$k.'" value="'.htmlspecialchars($v).'" />';
			}
		}
	}
	if(XenoPostToForm::check()) XenoPostToForm::submit($_POST); // session_start(); 하기 전에

	include "../include/configure.php";
	include "../include/session.php";
	include "../include/db_connection.php";
	include "../include/php_function.php";
	include "../include/Crypto.class.php";
	include "../include/Point.class.php";
	include "../include/Allimtalk.class.php";

	require_once('../include/libs/INIStdPayUtil.php');
	require_once('../include/libs/HttpClient.php');

	$util = new INIStdPayUtil();
	try {
		if (strcmp("0000", $_REQUEST["resultCode"]) == 0) {
			//############################################
			// 1.전문 필드 값 설정(***가맹점 개발수정***)
			//############################################;

			$mid 			= $_REQUEST["mid"];     					// 가맹점 ID 수신 받은 데이터로 설정
			$signKey 		= "WEZJN0NGWW44K1Z3WitTdjVIWVNIZz09"; 		// 가맹점에 제공된 키(이니라이트키) (가맹점 수정후 고정) !!!절대!! 전문 데이터로 설정금지 / 테스트 SU5JTElURV9UUklQTEVERVNfS0VZU1RS
			$timestamp 		= $util->getTimestamp();   					// util에 의해서 자동생성
			$charset 		= "UTF-8";        							// 리턴형식[UTF-8,EUC-KR](가맹점 수정후 고정)
			$format 		= "JSON";        							// 리턴형식[XML,JSON,NVP](가맹점 수정후 고정)
			$authToken 		= $_REQUEST["authToken"];   				// 취소 요청 tid에 따라서 유동적(가맹점 수정후 고정)
			$authUrl 		= $_REQUEST["authUrl"];    					// 승인요청 API url(수신 받은 값으로 설정, 임의 세팅 금지)
			$netCancel 		= $_REQUEST["netCancelUrl"];   				// 망취소 API url(수신 받은f값으로 설정, 임의 세팅 금지)
			$mKey 			= hash("sha256", $signKey);					// 가맹점 확인을 위한 signKey를 해시값으로 변경 (SHA-256방식 사용)
			$merchantData 	= $_REQUEST["merchantData"];     			// 가맹점 관리데이터 수신

			//#####################
			// 2.signature 생성
			//#####################
			$signParam["authToken"] 	= $authToken;  	// 필수
			$signParam["timestamp"] 	= $timestamp;  	// 필수
			// signature 데이터 생성 (모듈에서 자동으로 signParam을 알파벳 순으로 정렬후 NVP 방식으로 나열해 hash)
			$signature = $util->makeSignature($signParam);

			//#####################
			// 3.API 요청 전문 생성
			//#####################
			$authMap["mid"] 			= $mid;   		// 필수
			$authMap["authToken"] 		= $authToken; 	// 필수
			$authMap["signature"] 		= $signature; 	// 필수
			$authMap["timestamp"] 		= $timestamp; 	// 필수
			$authMap["charset"] 		= $charset;  	// default=UTF-8
			$authMap["format"] 			= $format;  	// default=XML

			try {
				$httpUtil = new HttpClient();
				//#####################
				// 4.API 통신 시작
				//#####################

				$authResultString = "";

				if ($httpUtil->processHTTP($authUrl, $authMap)) {
					$authResultString = $httpUtil->body;
				} else {
					echo "Http Connect Error\n";
					echo $httpUtil->errormsg;

					throw new Exception("Http Connect Error");
				}

				//############################################################
				//5.API 통신결과 처리(***가맹점 개발수정***)
				//############################################################
				$resultMap = json_decode($authResultString, true);

				/*************************  결제보안 추가 2016-05-18 START ****************************/ 
				$secureMap["mid"]		= $mid;							//mid
				$secureMap["tstamp"]	= $timestamp;					//timestemp
				$secureMap["MOID"]		= $resultMap["MOID"];			//MOID
				$secureMap["TotPrice"]	= $resultMap["TotPrice"];		//TotPrice

				// signature 데이터 생성 
				$secureSignature = $util->makeSignatureAuth($secureMap);
				/*************************  결제보안 추가 2016-05-18 END ****************************/
				//print_r($resultMap);
				if ((strcmp("0000", $resultMap["resultCode"]) == 0) && (strcmp($secureSignature, $resultMap["authSignature"]) == 0) ){	//결제보안 추가 2016-05-18
					if($merchantData == "beauty"){ // 미용 -- test_INIpay_ajax.php/mode:set_update_payment_log_price_zero 에도 같은 로직이 포함됨.
						$sql = "
							SELECT *
							FROM tb_payment_log
							WHERE left(order_id, 40) = '".$resultMap["MOID"]."'
						"; // order_id 의 글자 제한수가 40자 이하라서 order_id의 앞부분만 전달 후 비교
						$result = mysql_query($sql);
						$row = mysql_fetch_assoc($result);
						$payment_cnt = mysql_num_rows($result);

						if($payment_cnt == 1){
							// 예약건 삭제
							$sql = "
								DELETE FROM tb_reservation
								WHERE customer_id = '".$row["customer_id"]."'
									AND artist_id = '".$row["artist_id"]."'
									AND year = '".$row["year"]."'
									AND month = '".$row["month"]."'
									AND day = '".$row["day"]."'
									AND hour = '".$row["hour"]."'
									AND minute = '".$row["minute"]."'
							";
							$result = mysql_query($sql);

							// 거래내역 업데이트
							$sql = "
								UPDATE tb_payment_log SET
									pg_log = '".addslashes(trim($authResultString))."',
									receipt_id = '".$resultMap["tid"]."',
									is_cancel = 0,
									update_time = NOW()
								WHERE session_id = '".$row["session_id"]."'
									AND left(order_id, 40) = '".$resultMap["MOID"]."'
							";
							$result = mysql_query($sql);
							if($result){
								// 포인트 처리
								$point = new Point;
								$result = $point->select_point($row["customer_id"]);
								$point->spend_point($row['spend_point'], $row['payment_log_seq'], $row['order_id']);

								// 실 결제 포인트 적립
								$original_price = intval($row['total_price']) - intval($row['spend_point']);
								$saving_point = (($original_price * 0.5) / 100);
								if ($original_price > 0) {
									$result = $point->select_point ($user_id);
									$point->add_accumulate_percent_point($original_price, 0.5, $row['payment_log_seq'], $row['order_id']);
								}

								// 알림톡 발송 / PUSH 발송
								$artist_id = $row['artist_id'];
								$artist_name = explode("|", $row['product']);
								$artist_name = $artist_name[2];
								$user_id = $row['cellphone'];
								$message = $row['year']."년".$row['month']."월".$row['day']."일 신규 예약등록. 작업스케줄을 관리하세요.";
								$path = "https://www.gopet.kr/pet/shop/manage_sell_info.php?yy=".$row['year']."&mm=".$row['month']."&dd=".$row['day'];
								//$image = "https://www.gopet.kr/pet/images/logo_login.jpg";
								$image = "";
								a_push($artist_id, "반짝, 반려생활의 단짝. 신규 예약 알림", $message, $path, $image);

								$admin_message = $user_id."가 펫샵(".$artist_id." | ".$artist_name.")에 예약하였습니다. ".$row['year']."년".$row['month']."월".$row['day']."일 신규 예약등록. 작업스케줄을 관리하세요.";
								a_push("pickmon@pickmon.com", "반짝, 반려생활의 단짝. 신규 예약 알림", $admin_message, $path, $image);

								// 거래완료 페이지 이동
								?><script language="javascript">
									location.href='<?=$artist_directory ?>/payment_result.php?oid=<?=$row["order_id"] ?>';
								</script><?php
							}else{
								// 거래 업데이트 실패 - 변경 실패
								?><script language="javascript">
									alert("네트워크 환경으로 예약실패. 재시도 해주세요. [ EP01 ]");
									location.href = '<?=$artist_directory ?>/payment.php';
								</script><?php
							}
						}else if($payment_cnt <= 0){
							// 데이터가 존재하지 않음 - 변경할 데이터가 없음
							?><script language="javascript">
								alert("네트워크 환경으로 예약실패. 재시도 해주세요. [ EP02 ]");
								location.href = '<?=$artist_directory ?>/payment.php';
							</script><?php
						}else{
							// 중복 거래건이 있음 - 변경할 경우 데이터 손실 가능성 있음
							?><script language="javascript">
								alert("네트워크 환경으로 예약실패. 재시도 해주세요. [ EP03 ]");
								location.href = '<?=$artist_directory ?>/payment.php';
							</script><?php
						}
					}else if($merchantData == "item"){ // 상품 -- test_item_list_ajax.php/mode:set_update_item_payment_log_price_zero 에도 같은 로직이 포함됨.
						$sql = "
							SELECT *
							FROM tb_item_payment_log
							WHERE order_num = '".$resultMap["MOID"]."'
						"; 
						$result = mysql_query($sql);
						$row = mysql_fetch_assoc($result);
						$payment_cnt = mysql_num_rows($result);

						if($payment_cnt == 1){
							// 장바구니 > 상품내역 기록(+추가)
							$sql = "
								INSERT INTO tb_item_payment_log_product (
									`session_id`, `product_no`, `order_num`, `customer_id`, `product_price`, 
									`option_data`
								) SELECT 
									session_id, product_no, order_num, customer_id, cart_price, 
									cart_data 
								FROM tb_item_cart 
								WHERE is_delete = '1' 
									AND order_num = '".$resultMap["MOID"]."'
							";
							$result = mysql_query($sql);

							// 예약건 삭제
							$delete_id = ($row["customer_id"] != "")? $row["customer_id"] : $row["cellphone"];
							$sql = "
								UPDATE tb_item_cart SET
									`is_delete` = '2', 
									`delete_txt` = '".$delete_id."|결제 완료로 인해 자동 삭제', 
									`delete_dt` = NOW()
								WHERE is_delete = '1'
									AND order_num = '".$resultMap["MOID"]."'
							";
							$result = mysql_query($sql);

							// 거래내역 업데이트
							$order_status = ($resultMap["payMethod"] == "VCard")? "3" : "2"; // 결제완료 / 입금대기
							$sql = "
								UPDATE tb_item_payment_log SET
									pg_log = '".addslashes(trim($authResultString))."',
									receipt_id = '".$resultMap["tid"]."',
									pay_status = '3',
									order_status = '".$order_status."',
									pay_dt = NOW(),
									update_dt = NOW()
								WHERE order_num = '".$resultMap["MOID"]."'
							";
							$result = mysql_query($sql);
							if($result){
								if($row["customer_id"] != "" && $row["point_price"] > 0){ // 포인트 결제 사용시 포인트 감소
									$point = new Point;
									$result = $point->select_point($row["customer_id"]);

									// 새뱃돈 이벤트
									$sql21 = "
										SELECT *
										FROM tb_newyear_event
										WHERE order_num = '".$row["order_num"]."'
									";
									$result21 = mysql_query($sql21);
									$cnt21 = mysql_num_rows($result21);
									if($cnt21 > 0){
										$row21 = mysql_fetch_assoc($result21);
										$event_point = 0;
										$event_point = $row21["point"];

										$sql212 = "
											UPDATE tb_newyear_event SET
												is_use = '1',
												update_dt = NOW()
											WHERE is_delete = '2'
												AND order_num = '".$row["order_num"]."'
												AND ny_seq = '".$row21["ny_seq"]."'
										";
										$result212 = mysql_query($sql212);
										if($result212){
											$event_id = "PAYM_NYET_" . rand_id();
											//$point->print_stdio();
											$point->add_accumulate_point_by_event($event_point, $event_id);
										}
									}else{
										// 첫구매 이벤트
										$sql22 = "
											SELECT * 
											FROM tb_item_payment_log 
											WHERE is_delete = '1' 
												AND customer_id = '".$row["customer_id"]."' 
												AND receipt_id IS NOT NULL 
												AND pay_status IN ('2', '3', '4', '5', '6') 
												AND total_price > 0
												AND pay_dt > '2021-01-22 00:00:00'
										"; // 이벤트 시작일 추가
										$result22 = mysql_query($sql22);
										$cnt22 = mysql_num_rows($result22);
										if($cnt22 == 0){
											$event_point = 0;
											if($row["product_price"] >= 30000){
												$event_point = 3000;
											}else if($row["product_price"] >= 20000){
												$event_point = 2000;
											}else if($row["product_price"] >= 10000){
												$event_point = 1000;
											}
											
											if($event_point > 0){
												$event_id = "PAYM_1ODR_" . rand_id();
												//$point->print_stdio();
												$point->add_accumulate_point_by_event($event_point, $event_id);
											}
										}
									}
									
									$point->spend_point($row["point_price"], $row["ip_log_seq"], "product_".$row["order_num"]);
								}

								// 정글북 여부(데이터 있으면 처리)
								$sql = "
									SELECT *
									FROM tb_item_payment_log_jbook
									WHERE order_num = '".$row["order_num"]."'
								";
								$result2 = mysql_query($sql);
								$cnt = mysql_num_rows($result2);
								if($cnt > 0){
									$row2 = mysql_fetch_assoc($result2);
									$jbook_headers = array('Authorization: RittQ1EwLzJtb2pKWUtwUXR2VE9lMHVoZDdRbU1NMEY2ajF6Z24ycDQ2Q2s3dzVNVU9USlNaNUd4UlVxaldyeUNkMWhPcVNZZGdsL1FYYWhZMHRXeXVZcndsKzFPMEpYcU55WEI2QVpPcmFncWM5M3VDRU12S0NhOHJYa2dWb3M');
									$err_code = array(
										"001" => "필수 파라미터 오류",
										"002" => "파라미터 형식 오류",
										"101" => "구매 및 결제확인 동의하지 않음 으로인한 주문접수 불가",
										"102" => "주문상품중 중복으로 주문한 상품이 있음",
										"103" => "구매 불가 상품 주문",
										"104" => "주문 상품 없음",
										"105" => "중복주문 오류",
										"201" => "주문상품 찾을수 없음",
										"202" => "상품옵션 오류",
										"203" => "묶음주문 수량 오류",
										"204" => "최소 구매 수량 오류",
										"205" => "최대 구매 수량 오류",
										"206" => "주문상품 품절",
										"207" => "주문상품 재고부족",
										"208" => "공급사 요청으로 인한 구매제한 상품 주문",
									);

									$shipping_name = $row["shipping_name"];
									if($shipping_name == ""){
										$guest_name = ($row["guest_info"] != "" && strpos($row["guest_info"], ',') !== false)? explode(',', $row["guest_info"]) : $row["cellphone"];	
										$shipping_name = $guest_name[0];
									}
									if($row["shipping_zipcode"] != ""){
										$shipping_zipcode = $row["shipping_zipcode"];
										$shipping_addr = $row["shipping_addr"];
										$shipping_addr2 = $row["shipping_addr2"];
									}else{
										$shipping_addr_list = ($row["shipping_addr"] != "" && strpos($row["shipping_addr"], '|') !== false)? explode('|', $row["shipping_addr"]) : "";
										$shipping_zipcode = $shipping_addr_list[0];
										$shipping_addr = $shipping_addr_list[1];
										$shipping_addr2 = "1";
									}

									$post_data = array(
										"oaType" => "api",
										"oaOrderNo" => $row["order_num"],
										"phoneReceiver" => add_hyphen($row["cellphone"]), //수령자 전화번호1
										"mobileReceiver" => add_hyphen($row["cellphone"]), //수령자 전화번호2
										"nameReceiver" => $shipping_name, //수령자: 2글자 이상의 수령자 이름
										"zipCode" => $shipping_zipcode, //(구/신)우편번호: 5자리 이상의 구 우편번호 또는 신 우편번호
										"address" => $shipping_addr, //(지번/도로명)주소: 주소정재 API를 이용하기 때문에 가급적 신주소로 요청하시기 바랍니다.
										"address2" => $shipping_addr2, //나머지 주소: 아파트명, 동, 호수 등 나머지 주소
										"settleKind" => "s", //결제방식: "a" => (무통장->주문접수만), "s" => (캐쉬결제->주문접수+캐쉬결제)
										"bankSender" => $shipping_name, //입금자명: settleKind 파라미터가 "a" (무통장)인경우 필수
										"doubleCheck" => 1, //구매동의여부(1-동의, 0-미동의) * 미동의시 진행안됨
										"memo" => $row["shipping_memo"], //배송메세지: 최대 100글자
										"orderItem" => json_decode($row2["orderItem"], 1) // 주문상품 정보
									);

									$data = post_jb($jbook_headers, 'data='.json_encode($post_data));

									if($data["orderResult"]["success"] == "1"){ // success
										$order_step = ($row["order_num"] == $data["orderResult"]["oaApiOrdno"])? "2" : "9"; // 9-거래번호 다름(정글북에 문의)
										$cash_balance = ($data["orderResult"]["cashBalance"] != "")? $data["orderResult"]["cashBalance"] : "0";
										$payAmount = ($data["orderResult"]["payAmount"] != "")? $data["orderResult"]["payAmount"] : "0";

										$sql = "
											UPDATE tb_item_payment_log_jbook SET
												orderResult = '".$data["orderResult"]["success"]."',
												ordNo = '".$data["orderResult"]["ordNo"]."',
												oaType = '".$data["orderResult"]["oaType"]."',
												oaApiOrdno = '".$data["orderResult"]["oaApiOrdno"]."',
												nameReceiver = '".$data["orderResult"]["nameReceiver"]."',
												zipCode = '".$data["orderResult"]["zipCode"]."',
												address = '".$data["orderResult"]["address"]."',
												orderGoods = '".$data["orderResult"]["orderGoods"]."',
												settleKind = '".$data["orderResult"]["settleKind"]."',
												settlePrice = '".$data["orderResult"]["settlePrice"]."',
												totalGoodsPrice = '".$data["orderResult"]["totalGoodsPrice"]."',
												delivery = '".$data["orderResult"]["delivery"]."',
												memo = '".$data["orderResult"]["memo"]."',
												payResult = '".$data["payResult"]["success"]."',
												payResultMsg = '".$data["payResult"]["payResultMsg"]."',
												cashBalance = '".$cash_balance."',
												payAmount = '".$payAmount."',
												order_step = '".$order_step."',
												update_dt = NOW()
											WHERE is_delete = '2'
												AND order_num = '".$row["order_num"]."'
										";
										$result3 = mysql_query($sql);
										if($result3){
											$sql = "
												UPDATE tb_item_payment_log SET
													jbOrdNo = '".$data["orderResult"]["ordNo"]."',
													update_dt = NOW()
												WHERE order_num = '".$data["orderResult"]["oaApiOrdno"]."'
											";
											$result3 = mysql_query($sql);
										}
									}else{
										$cash_balance = ($data["payResult"]["cashBalance"] != "")? $data["payResult"]["cashBalance"] : "0";
										$payAmount = ($data["payResult"]["payAmount"] != "")? $data["payResult"]["payAmount"] : "0";

										$sql = "
											UPDATE tb_item_payment_log_jbook SET
												orderResult = '".$data["orderResult"]["success"]."',
												errCode = '".$data["orderResult"]["errCode"]."',
												parameter = '".json_encode($data["orderResult"]["parameter"])."',
												errMsg = '".$err_code[$data["orderResult"]["errCode"]]."',
												payResult = '".$data["payResult"]["success"]."',
												payResultMsg = '".$data["payResult"]["payResultMsg"]."',
												cashBalance = '".$cash_balance."',
												payAmount = '".$payAmount."',
												order_step = '2',
												update_dt = NOW()
											WHERE is_delete = '2'
												AND order_num = '".$row["order_num"]."'
										";
										$result3 = mysql_query($sql);
									}
								}

								// 알림톡 발송 / PUSH 발송
								if($row["pay_type"] == "1"){ // 신용카드
									$talk = new Allimtalk();

									$talk->cellphone = $row["cellphone"];

									$talkCustomerName = substr($row["cellphone"], -4);
									$talkDate = date("y년 m월 d일 H시 i분", STRTOTIME(DATE("Y-m-d H:i:s")));
									$talkBtnLink = "https://gopet.kr/pet/chkodr/?no=".$row["order_num"];
									$talkResult = $talk->sendOrderReceipt_new($talkCustomerName, $row["order_num"], $talkDate, $row["product_name"], $talkBtnLink);
								}else if($row["pay_type"] == "2"){ // 계좌이체
									$talk = new Allimtalk();

									$talk->cellphone = $row["cellphone"];

									$talkCustomerName = substr($row["cellphone"], -4);
									$talkDate = date("y년 m월 d일 H시 i분", STRTOTIME(DATE("Y-m-d H:i:s")));
									$talkExpireDate = date("y년 m월 d일 H시 i분", STRTOTIME($row["expire_dt"]));
									$talkTotalPrice = number_format($row["total_price"])."원";
									$talkBtnLink = "https://gopet.kr/pet/chkodr/?no=".$row["order_num"];
									$talkResult = $talk->sendOrderAccount_new($talkCustomerName, $row["order_num"], $talkDate, $row["product_name"], $talkExpireDate, $talkTotalPrice, $row["shipping_name"], $talkBtnLink);
								}

								// 관리자 푸시 발송
								$pushPath = "https://www.gopet.kr/pet/admin/item_payment_log_detail.php?no=".$row["order_num"];
								//$pushImage = "https://www.gopet.kr/pet/images/logo_login.jpg";
								$pushImage = "";
								$pushPayType = ($row["pay_type"] == "1")? "카드" : "계좌이체";
								$admin_message = substr($row["cellphone"], -4) . "(".explode(",", $row["guest_info"])[1].")님이 [".$row["product_name"]."]을 구매(".$pushPayType."). 상품결제 관리를 확인하세요";
								a_push("pickmon@pickmon.com", "반짝_상품구매알림", $admin_message, $pushPath, $pushImage);

								// 거래완료 페이지 이동
								?><script language="javascript">
									location.href='<?=$item_directory ?>/item_payment_result.php?no=<?=$row["order_num"] ?>';
								</script><?php
							}else{
								// 거래 업데이트 실패 - 변경 실패
								?><script language="javascript">
									alert("네트워크 환경으로 예약실패. 재시도 해주세요. [ EP01 ]");
									location.href = '<?=$item_directory ?>/item_payment.php?no=<?=$row["order_num"] ?>';
								</script><?php
							}
						}else if($payment_cnt <= 0){
							// 데이터가 존재하지 않음 - 변경할 데이터가 없음
							?><script language="javascript">
								alert("네트워크 환경으로 예약실패. 재시도 해주세요. [ EP02 ]");
								location.href = '<?=$item_directory ?>/item_payment.php?no=<?=$row["order_num"] ?>';
							</script><?php
						}else{
							// 중복 거래건이 있음 - 변경할 경우 데이터 손실 가능성 있음
							?><script language="javascript">
								alert("네트워크 환경으로 예약실패. 재시도 해주세요. [ EP03 ]");
								location.href = '<?=$item_directory ?>/item_payment.php?no=<?=$row["order_num"] ?>';
							</script><?php
						}
					//}else if($merchantData == "hotel"){ // 호텔
						// 데이터 쌓이고 난 후 개발 예정
					//}else if($merchantData == "playroom"){ // 유치원/놀이방
						// 데이터 쌓이고 난 후 개발 예정
					}else{
						// 누구세요? 어디서 오셨어요?
						?><script language="javascript">
							alert("네트워크 환경으로 예약실패. 재시도 해주세요. [ EM04 ]");
							location.href = '<?=$mainpage_directory ?>/index.php'; // 도착지가 정해지지 않으면 메인페이지로 이동
						</script><?php
					}
					

					// 결제 테스트 성공시 내용 : { "CARD_Quota": "00", "CARD_ClEvent": "", "buyerTel": "010-1234-5678", "parentEmail": "", "applDate": "20200828", "buyerEmail": "test@inicis.com", "p_Sub": "", "resultCode": "0000", "mid": "INIpayTest", "CARD_UsePoint": "", "CARD_Num": "536510*********8", "authSignature": "a4b96482e151485014d970b38530a7eaa9fa241e399c88c56a179cf755cb1530", "ISP_CardCode": "050204900094081", "tid": "StdpayISP_INIpayTest20200828091840690107", "EventCode": "", "goodName": "테스트", "TotPrice": "1000", "payMethod": "VCard", "CARD_MemberNum": "", "MOID": "INIpayTest_1598573846421", "CARD_Point": "", "currency": "WON", "CARD_PurchaseCode": "", "CARD_PrtcCode": "1", "applTime": "091841", "goodsName": "테스트", "CARD_CheckFlag": "1", "FlgNotiSendChk": "", "CARD_Code": "06", "CARD_BankCode": "04", "CARD_TerminalNum": "019058I000", "ISP_RetrievalNum": "", "buyerName": "홍길동", "p_SubCnt": "", "applNum": "26045706", "resultMsg": "정상완료", "CARD_Interest": "0", "CARD_SrcCode": "", "CARD_ApplPrice": "1000", "CARD_GWCode": "G", "custEmail": "ulmo26@gmail.com", "CARD_PurchaseName": "국민계열", "CARD_PRTC_CODE": "1", "payDevice": "PC" }


				}else{
					// 승인 실패
					?><script language="javascript">
						alert('네트워크 환경으로 예약실패. 재시도 해주세요. [ EP<?=$resultMap["resultCode"] ?> ]');
						if("<?=$merchantData ?>" == "beauty"){
							location.href = '<?=$artist_directory ?>/payment.php';
						}else if("<?=$merchantData ?>" == "item"){
							location.href = '<?=$item_directory ?>/item_payment.php';
						//}else if("<?=$merchantData ?>" == "hotel"){
						//	location.href = "../test/test_hotel_payment.php";
						//}else if("<?=$merchantData ?>" == "playroom"){
						//	location.href = "../test/test_playroom_payment.php";
						}else{
							location.href = '<?=$mainpage_directory ?>/index.php'; // 도착지가 정해지지 않으면 메인페이지로 이동
						}
					</script><?php
				}
			} catch (Exception $e) {
				//####################################
				// 실패시 처리(***가맹점 개발수정***)
				//####################################
				//---- db 저장 실패시 등 예외처리----//
				//$s = $e->getMessage() . ' (오류코드:' . $e->getCode() . ')';
				//echo $s;
				?><script language="javascript">
					alert('네트워크 환경으로 예약실패. 재시도 해주세요. [ EP<?=$e->getCode() ?> ]');
					if("<?=$merchantData ?>" == "beauty"){
						location.href = '<?=$artist_directory ?>/payment.php';
					}else if("<?=$merchantData ?>" == "item"){
						location.href = '<?=$item_directory ?>/item_payment.php';
					//}else if("<?=$merchantData ?>" == "hotel"){
					//	location.href = "../test/test_hotel_payment.php";
					//}else if("<?=$merchantData ?>" == "playroom"){
					//	location.href = "../test/test_playroom_payment.php";
					}else{
						location.href = '<?=$mainpage_directory ?>/index.php'; // 도착지가 정해지지 않으면 메인페이지로 이동
					}
				</script><?php
				//#####################
				// 망취소 API
				//#####################

				$netcancelResultString = ""; // 망취소 요청 API url(고정, 임의 세팅 금지)
				
				if ($httpUtil->processHTTP($netCancel, $authMap)) {
					$netcancelResultString = $httpUtil->body;
				} else {
					echo "Http Connect Error\n";
					echo $httpUtil->errormsg;

					throw new Exception("Http Connect Error");
				}

				echo "<br/>## 망취소 API 결과 ##<br/>";
				
				/*##XML output##*/
				//$netcancelResultString = str_replace("<", "&lt;", $$netcancelResultString);
				//$netcancelResultString = str_replace(">", "&gt;", $$netcancelResultString);
				
				// 취소 결과 확인
				echo "<p>". $netcancelResultString . "</p>";
			}
		} else {

			//#############
			// 인증 실패시
			//#############
			//echo "<br/>";
			//echo "####인증실패####";

			//echo "<pre>" . var_dump($_REQUEST) . "</pre>";
			?><script language="javascript">
				alert('네트워크 환경으로 예약실패. 재시도 해주세요. [ EPIJX ]');
				if("<?=$merchantData ?>" == "beauty"){
					location.href = '<?=$artist_directory ?>/payment.php';
				}else if("<?=$merchantData ?>" == "item"){
					location.href = '<?=$item_directory ?>/item_payment.php';
				//}else if("<?=$merchantData ?>" == "hotel"){
				//	location.href = "../test/test_hotel_payment.php";
				//}else if("<?=$merchantData ?>" == "playroom"){
				//	location.href = "../test/test_playroom_payment.php";
				}else{
					location.href = '<?=$mainpage_directory ?>/index.php'; // 도착지가 정해지지 않으면 메인페이지로 이동
				}
			</script><?php
		}
	} catch (Exception $e) {
		//$s = $e->getMessage() . ' (오류코드:' . $e->getCode() . ')';
		//echo $s;
		?><script language="javascript">
			alert('네트워크 환경으로 예약실패. 재시도 해주세요. [ EP<?=$e->getCode() ?> ]');
			if("<?=$merchantData ?>" == "beauty"){
				location.href = '<?=$artist_directory ?>/payment.php';
			}else if("<?=$merchantData ?>" == "item"){
				location.href = '<?=$item_directory ?>/item_payment.php';
			//}else if("<?=$merchantData ?>" == "hotel"){
			//	location.href = "../test/test_hotel_payment.php";
			//}else if("<?=$merchantData ?>" == "playroom"){
			//	location.href = "../test/test_playroom_payment.php";
			}else{
				location.href = '<?=$mainpage_directory ?>/index.php'; // 도착지가 정해지지 않으면 메인페이지로 이동
			}
		</script><?php
	}

	function post_jb($headers, $post_data){
		$P_REQ_URL = "http://api.junglebook.co.kr/order";		// live
		//$P_REQ_URL = "http://api.junglebook.co.kr/order/test";	// test

		$ch = curl_init(); //curl 사용 전 초기화 필수(curl handle)
		curl_setopt($ch, CURLOPT_URL, $P_REQ_URL);			// URL 지정하기
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		// 결과를 노출(0-print, 1-변수저장)
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	// https ssl 인증서 확인 하지 않도록 함
		curl_setopt($ch, CURLOPT_SSLVERSION,3);				// 주소가 https가 아니라면 지울것
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);		// header 정보 전달

		curl_setopt($ch, CURLOPT_POST, 1);					// 0이 default 값이며 POST 통신을 위해 1로 설정해야 함
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);	// POST로 보낼 데이터 지정하기

		$response = curl_exec($ch);
		$tmp_arr = json_decode($response, true); //결과값 확인하기

		$tmp_err = curl_errno($ch);//마지막 에러 번호 출력 
		$tmp_err .= curl_error($ch);//현재 세션의 마지막 에러 출력
		curl_close($ch);

		return ($tmp_err == 0)? $tmp_arr : $tmp_err;
	}
?>