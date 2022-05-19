<?php
include "../include/top.php";
include "../include/Crypto.class.php";
// include "../include/check_header_log.php";
?>
<?php
$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}
?>

<?php
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
$type = $_GET['type'];

if ($type == NULL || $type == "") {
    $type = "reservation";
}
?>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<style>


</style>
<div class="top_menu">
    <div class="top_back"><a href="<?= $mainpage_directory ?>/mainpage_my_menu.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div class="top_title">
        <p>예약 및 취소 내역</p>
    </div>
</div>

<center id="manage_my_reservation">

    <div class="search-tab">
        <div class="<?php echo (($type == "reservation") ? "on" : ""); ?>"><a href="?type=reservation">예약 내역</a></div>
        <div class="<?php echo (($type == "cancel") ? "on" : ""); ?>"><a href="?type=cancel">취소 내역</a></div>
    </div>
    <?php
    $str_minute = "";
    $str_to_minute = "";
    $type_query = "";
    if ($type == "reservation") {
        $type_query = "AND tpl.is_cancel = 0";
    } else {
        $type_query = "AND tpl.is_cancel = 1";
    }
	// 20200910 ulmo 진행중(RO)은 표시되지 않도록 추가
    $login_insert_sql = "SELECT *, tpl.is_vat as is_vat, tpl.update_time as d_time 
                        FROM tb_payment_log tpl, tb_shop ts 
                        WHERE tpl.customer_id = '" . $user_id . "' 
                              AND ts.customer_id = tpl.artist_id 
                              AND tpl.approval = 1 
							  AND tpl.status != 'R0'
                              $type_query
                        GROUP BY tpl.update_time 
                        ORDER BY tpl.year DESC, tpl.month DESC, tpl.day DESC, tpl.hour DESC, tpl.minute DESC;";
    // error_log('-----$login_insert_sql : ' . $login_insert_sql);
    $result = mysql_query($login_insert_sql);
    for ($ch_index = 0; $result_datas = mysql_fetch_object($result); $ch_index++) {
        $session_id = $result_datas->session_id;
        $artist_id = $result_datas->artist_id;
        $artist_name = $result_datas->name;
        $customer_id = $result_datas->customer_id;
        $status = $result_datas->status;
        $top_region = $result_datas->top_region;
        $middle_region = $result_datas->middle_region;
        $bottom_region = $result_datas->bottom_region;
        $year = $result_datas->year;
        $month = $result_datas->month;
        $day = $result_datas->day;
        $hour = $result_datas->hour;
        $minute = $result_datas->minute;
        $to_hour = $result_datas->to_hour;
        $to_minute = $result_datas->to_minute;
        $product = $result_datas->product;
        $address1 = $result_datas->address1;
        $address2 = $result_datas->address2;
        $cellphone = $result_datas->cellphone;
        $pay_type = $result_datas->pay_type;
        $card = $result_datas->card;
        $plan = $result_datas->plan;
        $bank = $result_datas->bank;
        $cash_type = $result_datas->cash_type;
        $cash_key = $result_datas->cash_key;
        $cash_value = $result_datas->cash_value;
        $per_diem = $result_datas->per_diem;
        $update_time = $result_datas->update_time;
        $expire_time = $result_datas->expire_time;
        $is_only_point = $result_datas->is_only_point;
        $total_price = $result_datas->total_price;
        $spend_point = $result_datas->spend_point;
        $payment_log_seq = $result_datas->payment_log_seq;
        $go_2_offline = $result_datas->go_2_offline;
        $is_vat = $result_datas->is_vat;
        $d_time = $result_datas->d_time;
        $is_cancel = $result_datas->is_cancel;
        $cancel_time = date("Y년 m월 d일 H시", strtotime($result_datas->cancel_time));
        // error_log('----- $result_datas : ' . print_r($result_datas, true));
    ?>
	<?php 
		if($type == "cancel"){
	?>
		<div style="width: calc(90% - 30px); background-color: #f9f9f9; font-size: 12px; text-align: left; padding: 10px 10px 10px 20px; line-height: 16px; color: #999;">
			<span style="margin-left: -10px;">*</span> 카드 취소의 경우 PG사에서 카드사로 취소 반영에 2~3일 소요될 수 있으니 양해부탁드립니다.
		</div>
	<?php
		}
	?>
        <div class="my_reservation">
            <div class="res_wrap">
                <?php if ($is_cancel == 1) { ?>
                    <div>
                        <div>
                            <div class="cancle-header">
                                <div>예약취소일자</div>
                                <div><?php echo $cancel_time; ?></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div>
                    <div height="10px"></div>
                </div>
                <?php
                if ($minute == 0) {
                    $str_minute = "";
                } else {
                    $str_minute = $minute . "분";
                }

                if ($to_minute == 0) {
                    $str_to_minute = "";
                } else {
                    $str_to_minute = $to_minute . "분";
                }

				$timediff = 0;
				if($hour != "" && $to_hour != "" && $minute != "" && $to_minute != ""){
					$_s_date = date($year."-".$month."-".$day." ".$hour.":".$minute.":00");
					$_e_date = date($year."-".$month."-".$day." ".$to_hour.":".$to_minute.":00");
					$timediff = (strtotime($_e_date) - strtotime($_s_date)) / 60; // minute
				}
                ?>
                <div>
                    <div class="res_info">
                        <b><?= $year ?>년 <?= $month ?>월 <?= $day ?>일 <?= $hour ?>시 <?= $str_minute ?> ~ <?= ($to_hour) ?>시 <?= $str_to_minute ?> / <?= $artist_name ?> / <? if ($is_only_point) {
                                                                                                                                                                                echo "포인트";
                                                                                                                                                                            } else {
                                                                                                                                                                                if ($pay_type == "card") {
                                                                                                                                                                                    echo "신용카드";
                                                                                                                                                                                } else if ($pay_type == "bank") {
                                                                                                                                                                                    echo "계좌이체";
                                                                                                                                                                                } else {
                                                                                                                                                                                    echo "매장결제";
                                                                                                                                                                                }
                                                                                                                                                                            }  ?></b>
                    </div>
                </div>
                <?php
                if ($status == "BR") {
                ?>
                    <div>
                        <div colspan="3">
                            <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">
                        </div>
                    </div>
                    <div>
                        <div colspan="3">
                            <b>결제 진행중</b><br>
                            아래 계좌에 "<?= $bank ?>"이름으로 입금부탁드립니다.<br><br>
                            <b class="account-no">기업은행<br>054-143076-01-013<br>주식회사 펫이지</b><br><br>
                            입금기한 : <?= date("Y년 m월 d일 H시 i분 s초", strtotime($expire_time)) ?>
                        </div>
                    </div>
                <?php
                }
                ?>

                <?php
                if ($go_2_offline) {
                    $crypto = new Crypto();
                    $enc_artist_id = $crypto->encode(trim($artist_id), $access_key, $secret_key);
                    $off_sql1 = "select * from tb_request_artist where customer_id = '" . $enc_artist_id . "';";
                    $off_result1 = mysql_query($off_sql1);
                    if ($off_rows = mysql_fetch_object($off_result1)) {
                        if ($off_rows->is_got_offline_shop == 1) {
                ?>
                            <div>
                                <div>
                                    <div class="info_font_1">매장 위치</div>
                                    <center>
                                        <div style="height:3px"></div>
                                        <a href="<?= $shop_directory ?>/daum_map.php?artist_id=<?= $artist_id ?>&backurl=<?= $mainpage_directory ?>/manage_my_reservation.php">

                                            <div style="width:100%;height:80px;position:relative;z-index:-1;background-image:url('<?= $image_directory ?>/map_back.png');background-size:cover;">

                                                <div style=" width: 100%;position:absolute;z-index:2;height:80px; opacity:0.6;background-color:#000000;">
                                                    <div>
                                                        <div valign="middle" style="height: 100%; margin-top: 4px; padding:16px;font-weight:bold;">
                                                            <!--b><?= $crypto->decode($off_rows->offline_shop_name, $access_key, $secret_key) ?></b><br><br-->
                                                            <?php
                                                            $address_off = $crypto->decode($off_rows->offline_shop_address, $access_key, $secret_key);
                                                            if ($address_off) {
                                                            ?>
                                                                <div style="color:#fff;  font-size:14px; z-index: 99; vertical-align: middle; font-weight: bold;"><?= str_replace("|", "", strstr($address_off, "|")) ?></div>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </center>

                                </div>
                            </div>
                    <?php
                        }
                    }
                } else {
                    ?>
                    <div>
                        <div> <b>방문 정보</b><br>
                            출장요청지 : <?= str_replace("|", "", strstr($address1 . $address2, "|")) ?><br>
                            전화번호 : <?= $cellphone ?><br><br>
                        </div>
                    </div>

                <?php
                }
                ?>
                <div class="info_section">
                    <div>
                        <div class="info_font_1">결제상품</div>
                        <?php
                        $total_price = $per_diem;
                        $product_info = $product;
                        ?>
                        <div width="100%" style="border:1px solid #999999;">
                            <?php
                            $service_infos = explode("|", $product_info);

                            //-----Start line >> 견주 고객이 설정한 펫이름으로 조회
                            $customer_infos_sql = "";
                            $product_pet_name = "";
                            $customer_pet_name = "";

                            $product_pet_name = $service_infos[0];
                            if (isset($product_pet_name)) {
                                $customer_infos_sql =
                                    "SELECT *
                                            FROM tb_mypet
                                            WHERE customer_id = '" . $user_id . "'
                                            AND name = '" . $product_pet_name . "';";
                                $customer_infos_result = mysql_query($customer_infos_sql);
                                // error_log('-----$customer_infos_result : ' . $customer_infos_result);

                                if ($customer_infos_rows = mysql_fetch_object($customer_infos_result)) {
                                    $customer_pet_name = $customer_infos_rows->name_for_owner;
                                }
                            }
                            //-----End line >> 견주 고객이 설정한 펫이름으로 조회
                            ?>
                            <div class="res_subwrap">
                                <div colspan="4"><b><?= $customer_pet_name ?>/<?= $service_infos[1] ?></b></div>
                            </div>
				<?php if ($service_infos[1] == "개") { ?>
					<div class="res_subwrap" style="font-size: 13px;">
						<div class="res_rec">
							<div class="res_title"><?= $service_infos[3] ?>/<?= explode(":", $service_infos[4])[0] ?>/<span class="is_over"><?="~ ".explode(":", $service_infos[5])[0]."Kg" ?></span></div>
							<div class="res_pay">
								<?php
									$dog_total_i_price = 0;
									$basic_a_p = intval(explode(":", $service_infos[4])[1]) + intval(explode(":", $service_infos[5])[1]);
									$total_price = $total_price + $basic_a_p;
									$dog_total_i_price = $dog_total_i_price + $basic_a_p;
									echo number_format($basic_a_p) . "원";
								?>
							</div>
						</div>
						<?php //if (!startsWith($service_infos[4], "부분미용")) { 
							if($service_infos[8] != ""){
								$ahbaci_arr = explode(",", $service_infos[8]);
								foreach($ahbaci_arr AS $key => $value){
									$ahbaci += intval(explode(":", $value)[1]);
									$ahbaci_name .= explode(":", $value)[0].",";
								}
								
								$ahbaci_name = substr($ahbaci_name, 0, -1);
						?>
						<div class="res_rec">
							<div class="res_title"><?= $ahbaci_name ?></div>
							<div class="res_pay">
								<?php
									$total_price = $total_price + $ahbaci;
									$dog_total_i_price = $dog_total_i_price + $ahbaci;
									echo number_format($ahbaci) . "원";
								?>
							</div>
						</div>
						<?php } //} ?>
						<?php if(explode(":", $service_infos[7])[1] != ""){ //if (startsWith($service_infos[4], "전체미용") || startsWith($service_infos[4], "스포팅") || startsWith($service_infos[4], "썸머컷") || startsWith($service_infos[4], "가위컷")) { ?>
						<div class="res_rec">
							<div class="res_title"><?= explode(":", $service_infos[7])[0] ?>mm</div>
							<div class="res_pay">
								<?php
									$ahbaci = intval(explode(":", $service_infos[7])[1]);
									$total_price = $total_price + $ahbaci;
									$dog_total_i_price = $dog_total_i_price + $ahbaci;
									echo number_format($ahbaci) . "원";
								?>
							</div>
						</div>
						<?php } //} ?>									
						<?php if (explode(":", $service_infos[6])[0] != "" && strlen($service_infos[6]) > 0) { ?>
						<div class="res_rec">
							<div class="res_title"><?= explode(":", $service_infos[6])[0] ?></div>
							<div class="res_pay">
								<?php
									$ahbaci = intval(explode(":", $service_infos[6])[1]);
									$total_price = $total_price + $ahbaci;
									$dog_total_i_price = $dog_total_i_price + $ahbaci;
									echo number_format($ahbaci) . "원";
								?>
							</div>
						</div>
						<?php } ?>
						<?php if (strlen($service_infos[9]) > 0) { ?>
						<div class="res_rec">
							<div class="res_title">발톱</div>
							<div class="res_pay">
								<?php
									$ahbaci = intval($service_infos[9]);
									$total_price = $total_price + $ahbaci;
									$dog_total_i_price = $dog_total_i_price + $ahbaci;
									echo number_format($ahbaci) . "원";
								?>
							</div>
						</div>
						<?php } ?>
						<?php if (strlen($service_infos[10]) > 0) { ?>
						<div class="res_rec">
							<div class="res_title">장화</div>
							<div class="res_pay">
								<?php
									$ahbaci = intval($service_infos[10]);
									$total_price = $total_price + $ahbaci;
									$dog_total_i_price = $dog_total_i_price + $ahbaci;
									echo number_format($ahbaci) . "원";
								?>
							</div>
						</div>
						<?php } ?>
						<?php if (strlen($service_infos[11]) > 0) { ?>
						<div class="res_rec">
							<div class="res_title">방울</div>
							<div class="res_pay">
								<?php
									$ahbaci = intval($service_infos[11]);
									$total_price = $total_price + $ahbaci;
									$dog_total_i_price = $dog_total_i_price + $ahbaci;
									echo number_format($ahbaci) . "원";
								?>
							</td>
						</div>
						<?php } ?>
						<?php if (strlen($service_infos[13]) > 0) { ?>
						<div class="res_rec">
							<div class="res_title">염색(<?= explode(":", $service_infos[12])[0] ?> 포인트)</div>
							<div class="res_pay">
								<?php
									$ahbaci = intval(explode(":", $service_infos[12])[1]);
									$total_price = $total_price + $ahbaci;
									$dog_total_i_price = $dog_total_i_price + $ahbaci;
									echo number_format($ahbaci) . "원";
								?>
							</div>
						</div>
						<?php } ?>
						<?php
						$option_count = intval($service_infos[14]);
						for ($option_ii = 0; $option_ii < $option_count; $option_ii = $option_ii + 1) {
						?>
						<div class="res_rec">
							<div class="res_title"><?= explode(":", $service_infos[14 + ($option_ii + 1)])[0] ?></div>
							<div class="res_pay">
								<?php
									$ahbaci = intval(explode(":", $service_infos[14 + ($option_ii + 1)])[1]);
									$total_price = $total_price + $ahbaci;
									$dog_total_i_price = $dog_total_i_price + $ahbaci;
									echo number_format($ahbaci) . "원";
								?>
							</div>
						</div>
						<?php } ?>
						<?php
						$spa_p_index = 14 + $option_count + 1;
						$spa_option_count = intval($service_infos[$spa_p_index]);
						for ($option_ii = 0; $option_ii < $spa_option_count; $option_ii = $option_ii + 1) {
						?>
						<div class="res_rec">
							<div class="res_title"><?= explode(":", $service_infos[$spa_p_index + ($option_ii + 1)])[0] ?></div>
							<div class="res_pay">
								<?php
									$ahbaci = intval(explode(":", $service_infos[$spa_p_index + ($option_ii + 1)])[1]);
									$total_price = $total_price + $ahbaci;
									$dog_total_i_price = $dog_total_i_price + $ahbaci;
									echo number_format($ahbaci) . "원";
								?>
							</div>
						</div>
						<?php } ?>
						<?php
						$dyeing_p_index = ($spa_p_index + 1) + $spa_option_count;
						$dyeing_option_count = intval($service_infos[$dyeing_p_index]);
						for ($option_ii = 0; $option_ii < $dyeing_option_count; $option_ii = $option_ii + 1) {
						?>
						<div class="res_rec">
							<div class="res_title"><?= explode(":", $service_infos[$dyeing_p_index + ($option_ii + 1)])[0] ?></div>
							<div class="res_pay">
								<?php
									$ahbaci = intval(explode(":", $service_infos[$dyeing_p_index + ($option_ii + 1)])[1]);
									$total_price = $total_price + $ahbaci;
									$dog_total_i_price = $dog_total_i_price + $ahbaci;
									echo number_format($ahbaci) . "원";
								?>
							</div>
						</div>
						<?php } ?>
						<?php
						$other_p_index = ($dyeing_p_index + 1) + $dyeing_option_count;
						$other_option_count = intval($service_infos[$other_p_index]);
						for ($option_ii = 0; $option_ii < $other_option_count; $option_ii = $option_ii + 1) {
						?>
						<div class="res_rec">
							<div class="res_title"><?= explode(":", $service_infos[$other_p_index + ($option_ii + 1)])[0] ?></div>
							<div class="res_pay">
								<?php
									$ahbaci = intval(explode(":", $service_infos[$other_p_index + ($option_ii + 1)])[1]);
									$total_price = $total_price + $ahbaci;
									$dog_total_i_price = $dog_total_i_price + $ahbaci;
									echo number_format($ahbaci) . "원";
								?>
							</div>
						</div>
						<?php } ?>
						<?php
						$etc_p_index = ($other_p_index + 1) + $other_option_count;
						$etc_count = intval($service_infos[$etc_p_index]);
						for ($option_ii = 0; $option_ii < $etc_count; $option_ii = $option_ii + 1) {
						?>
						<div class="res_rec">
							<div class="res_title"><?= explode(":", $service_infos[$etc_p_index + ($option_ii + 1)])[1]." [".number_format(explode(":", $service_infos[$etc_p_index + ($option_ii + 1)])[2])."원x".explode(":", $service_infos[$etc_p_index + ($option_ii + 1)])[3]."개]" ?></div>
							<div class="res_pay">
								<?php
									$ahbaci = intval(explode(":", $service_infos[$etc_p_index + ($option_ii + 1)])[2] * explode(":", $service_infos[$etc_p_index + ($option_ii + 1)])[3]);
									$total_price = $total_price + $ahbaci;
									$dog_total_i_price = $dog_total_i_price + $ahbaci;
									echo number_format($ahbaci) . "원";
								?>
							</div>
						</div>
						<?php } ?>
						<?php
						$coupon_p_index = ($etc_p_index + 1) + $etc_count;
						$coupon_count = intval($service_infos[$coupon_p_index]);
						for ($option_ii = 0; $option_ii < $coupon_count; $option_ii = $option_ii + 1) {
						?>
						<div class="res_rec">
							<div class="res_title">쿠폰 : <?= explode(":", $service_infos[$coupon_p_index + ($option_ii + 1)])[1] ?></div>
							<div class="res_pay">
								<?php
									$ahbaci = intval(explode(":", $service_infos[$coupon_p_index + ($option_ii + 1)])[2]);
									$total_price = $total_price + $ahbaci;
									$dog_total_i_price = $dog_total_i_price + $ahbaci;
									echo number_format($ahbaci) . "원";
								?>
							</div>
						</div>
						<?php } ?>
						<div>
							<div class="res_total">
								<b>합산 금액 : <?= number_format($dog_total_i_price) ?> 원</b>
							</div>
						</div>
					</div>
			<?php } else { // cat ?>
					<div class="res_subwrap" style="font-size: 13px;">
						<div class="res_rec">
							<div class="res_title"><?= $service_infos[3] ?><?= "/".explode(":", $service_infos[5])[0] ?><?= (explode(":", $service_infos[4])[0] != "all")? "/~" . explode(":", $service_infos[4])[0] . "Kg" : "" ?></div>
							<div class="res_pay">
								<?php
									$cat_total_i_price = 0;
									$basic_a_p = intval(explode(":", $service_infos[5])[1]) + intval(explode(":", $service_infos[4])[1]);
									$total_price = $total_price + $basic_a_p;
									$cat_total_i_price = $cat_total_i_price + $basic_a_p;
									echo number_format($basic_a_p) . "원";
								?>
							</div>
						</div>
						<?php if (strlen($service_infos[6]) > 0) { ?>
						<div class="res_rec">
							<div class="res_title">발톱</div>
							<div class="res_pay">
								<?php
									$ahbaci = intval($service_infos[6]);
									$total_price = $total_price + $ahbaci;
									$cat_total_i_price = $cat_total_i_price + $ahbaci;
									echo number_format($ahbaci) . "원";
								?>
							</div>
						</div>
						<?php } ?>
						<?php if (strlen($service_infos[7]) > 0) { ?>
						<div class="res_rec">
							<div class="res_title">단모 목욕</div>
							<div class="res_pay">
								<?php
									$ahbaci = intval($service_infos[7]);
									$total_price = $total_price + $ahbaci;
									$cat_total_i_price = $cat_total_i_price + $ahbaci;
									echo number_format($ahbaci) . "원";
								?>
							</div>
						</div>
						<?php } ?>
						<?php if (strlen($service_infos[8]) > 0) { ?>
						<div class="res_rec">
							<div class="res_title">장모 목욕</div>
							<div class="res_pay">
								<?php
									$ahbaci = intval($service_infos[8]);
									$total_price = $total_price + $ahbaci;
									$cat_total_i_price = $cat_total_i_price + $ahbaci;
									echo number_format($ahbaci) . "원";
								?>
							</div>
						</div>
						<?php } ?>
						<?php
						$option_count = intval($service_infos[9]);
						for ($option_ii = 0; $option_ii < $option_count; $option_ii = $option_ii + 1) {
						?>
						<div class="res_rec">
							<div class="res_title"><?= explode(":", $service_infos[9 + ($option_ii + 1)])[0] ?></div>
							<div class="res_pay">
								<?php
									$ahbaci = intval(explode(":", $service_infos[9 + ($option_ii + 1)])[1]);
									$total_price = $total_price + $ahbaci;
									$cat_total_i_price = $cat_total_i_price + $ahbaci;
									echo number_format($ahbaci) . "원";
								?>
							</div>
						</div>
						<?php } ?>
						<?php
						$etc_p_index = 10 + $option_count;
						$etc_count = intval($service_infos[$etc_p_index]);
						for ($option_ii = 0; $option_ii < $etc_count; $option_ii = $option_ii + 1) {
						?>
						<div class="res_rec">
							<div class="res_title"><?= explode(":", $service_infos[$etc_p_index + ($option_ii + 1)])[1]." [".number_format(explode(":", $service_infos[$etc_p_index + ($option_ii + 1)])[2])."원x".explode(":", $service_infos[$etc_p_index + ($option_ii + 1)])[3]."개]" ?></div>
							<div class="res_pay">
								<?php
									$ahbaci = intval(explode(":", $service_infos[$etc_p_index + ($option_ii + 1)])[2] * explode(":", $service_infos[$etc_p_index + ($option_ii + 1)])[3]);
									$total_price = $total_price + $ahbaci;
									$cat_total_i_price = $cat_total_i_price + $ahbaci;
									echo number_format($ahbaci) . "원";
								?>
							</div>
						</div>
						<?php } ?>
						<?php
						$coupon_p_index = ($etc_p_index + 1) + $etc_count;
						$coupon_count = intval($service_infos[$coupon_p_index]);
						for ($option_ii = 0; $option_ii < $coupon_count; $option_ii = $option_ii + 1) {
						?>
						<div class="res_rec">
							<div class="res_title">쿠폰 : <?= explode(":", $service_infos[$coupon_p_index + ($option_ii + 1)])[1] ?></div>
							<div class="res_pay">
								<?php
									$ahbaci = intval(explode(":", $service_infos[$coupon_p_index + ($option_ii + 1)])[2]);
									$total_price = $total_price + $ahbaci;
									$cat_total_i_price = $cat_total_i_price + $ahbaci;
									echo number_format($ahbaci) . "원";
								?>
							</div>
						</div>
						<?php } ?>
						<div>
							<div class="res_total">
								<b>합산 금액 : <?= number_format($cat_total_i_price) ?> 원</b>
							</div>
						</div>
					</div>
			<?php } ?>
				</div>
                        <div style="height:5px;"></div>

                        <div class="res_subwrap">
                            <div>
                                <div class="res_total">
                                    출장비 : <?= number_format($per_diem) ?>원
                                </div>
                            </div>
                            <?php
                            $vat_price = 0;
                            if ($is_vat) {
                                $vat_price = ($total_price / 10);
                                $total_price = $total_price + $vat_price;
                            ?>
                                <div>
                                    <div class="res_total">
                                        부가세 10% : <?= number_format($vat_price) ?>원
                                    </div>
                                </div>
                            <?php
                            }
                            ?>

                            <div>
                                <div class="res_total">
                                    사용포인트 : <?
                                            if (isset($spend_point)) {
                                                $spend_point = floatval($spend_point);
                                                echo number_format($spend_point);
                                            }
                                            ?>점
                                </div>
                            </div>
                            <div>
                                <div colspan="5">
                                    <hr style="color:#999999;width:100%;border:0;border:1px solid #999999;">
                                </div>
                            </div>
                            <div>
                                <div colspan="5" align="right">
                                    <?php
                                    if ($pay_type == "offline") {
                                        echo "<b>최종 결제예상금액</b>";
                                    } else {
                                        echo "<b>최종 결제금액</b>";
                                    }
                                    ?>
                                    <b><?= number_format($total_price - $spend_point) ?>원</b>
                                </div>
                            </div>
                            <div>
                                <div height="10px;"></div>
                            </div>
                            <div>
                                <div class="res_btn">
                                    <script>
                                        function cancel_button(artist_id, payment_log_seq) {

                                            $.MessageBox({
                                                buttonFail: "아니오",
                                                buttonDone: "예",
                                                message: "<center><font style='font-size:15px;font-weight:bold;'>예약을 취소 하시겠습니까?</font></center><br><br/>* 취소시 수수료가 발생할 수 있습니다<br/>* 카드 취소의 경우 PG사에서 카드사로 취소 반영에 2~3일 소요될 수 있으니 양해부탁드립니다."
                                            }).done(function() {
                                                location.href = 'cancel_reservation.php?artist_id=' + artist_id + '&payment_log_seq=' + payment_log_seq;
                                            });
                                            return false;
                                        }
                                    </script>
                                    <?php
                                    $now_year = date('Y');
                                    $now_month = date('m');
                                    $now_day = date('d');
                                    $now_hour = date('H');
                                    $update_year = date('Y', strtotime($d_time));
                                    $update_month = date('m', strtotime($d_time));
                                    $update_day = date('d', strtotime($d_time));
                                    $d_date = date('Y-m-d H:i:s', strtotime($year . "-" . $month . "-" . $day . " " . $to_hour . ":00:00"));
                                    $ds_date = date('Y-m-d H:i:s', strtotime($year . "-" . $month . "-" . $day . " " . $hour . ":00:00"));
                                    $n_date = date('Y-m-d H:i:s');

                                    $enc_id = $crypto->encode($artist_id, $access_key, $secret_key);
                                    $phone_query = "SELECT * FROM tb_request_artist WHERE customer_id = '{$enc_id}'";
                                    $phone_result = mysql_query($phone_query);
                                    $phone_data = mysql_fetch_object($phone_result);

                                    $dec_phone = "";
                                    if ($phone_data != null && isset($phone_data)) {
                                        $dec_phone = $crypto->decode($phone_data->offline_shop_phonenumber, $access_key, $secret_key);
                                        $dec_phone = add_hyphen($dec_phone);
                                    }

                                    if (strtotime("-2 hours", strtotime($ds_date)) > strtotime($n_date)) {
                                    ?>
                                        <script>
                                            function notice_no_noway() {
                                                $.MessageBox({
                                                    buttonDone: "확인",
                                                    message: "<center>예약 당일 취소는<br>수수료가 발생합니다.<br><br>고객센터를 이용해주세요.<br>( 1661-9956 )</center>"
                                                }).done(function() {});
                                            }

                                            function pos_alert() {
                                                $.MessageBox({
                                                    buttonDone: "통화",
                                                    buttonFail: "취소",
                                                    message: "<center>이 예약은 펫샵에서 잡은 예약이네요^^<br>변경/취소는 펫샵을 통해 가능합니다.<br>통화하시려면 통화(<?= $dec_phone ?>)를 눌러주세요</center>"
                                                }).done(function() {
                                                    window.open('tel:<?= $dec_phone ?>');
                                                }).fail(function() {});
                                            }
                                        </script>

                                        <?php
                                        if ($is_cancel == 0) {
                                        ?>

                                            <?php if (!startsWith($pay_type, "pos")) { ?>
                                                <a href="change_customer_schedule_board.php?artist_id=<?= $artist_id ?>&payment_log_seq=<?= $payment_log_seq ?>&timediff=<?=$timediff ?>" class="change_reservation">예약변경</a>
                                            <?php } else { ?>
                                                <a onclick="pos_alert();" class="change_reservation">예약변경</a>
                                            <?php } ?>


                                            <?php if (!startsWith($pay_type, "pos")) { ?>
                                                <?php
                                                if ($now_day != $day || $now_month != $month) {
                                                ?>
                                                    <a href="#" class="cancel_reservation" onclick="cancel_button('<?= $artist_id ?>', '<?= $payment_log_seq ?>')" style="color: #fff;">예약취소</a>
                                                    <?php
                                                } else if ($now_year == $year && $now_month == $month && $now_day == $day) {
                                                    if ($update_year == $year && $update_month == $month && $update_day == $day) {
                                                        // 당일 구매 당일 2시간 전 취소
                                                    ?>
                                                        <a href="#" class="cancel_reservation" onclick="cancel_button('<?= $artist_id ?>', '<?= $payment_log_seq ?>')" style="color: #fff;">예약취소</a>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <a href="#" class="cancel_reservation" onclick="notice_no_noway();">예약취소</a>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <a href="#" class="cancel_reservation" onclick="notice_no_noway();">예약취소</a>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <a href="#" class="cancel_reservation" onclick="pos_alert();">예약취소</a>
                                            <?php } ?>
                                        <?php
                                        }
                                        ?>


                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
    <?php
    }

    if ($ch_index == 0) {
    ?>
        <br>
        <br>
        <img src="<?= $image_directory ?>/myschedule.png" width="20%" style="opacity:0.5;">
        <br>
        <br>
        <br>
        <font style="font-size:18px;">예약 내역이 없습니다.</font>
    <?php
    }

    closeDB();
    ?>
</center>

<?php include "../include/bottom.php"; ?>