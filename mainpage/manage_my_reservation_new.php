<?php include "../include/top.php"; ?>
<?php include "../include/Crypto.class.php"; ?>
<?php
$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
	return false;
}
?>

<?php
	$user_id = $_SESSION['gobeauty_user_id'];
	$user_name = $_SESSION['gobeauty_user_nickname'];
?>

<style>
a{text-decoration:none; }
a:link {color:white;}
a:visited {color:white;}
a:hover {color:white;}
a:active {color:white;}

.my_reservation{
position:relative;
z-index:0;
width:100%;
text-align:left;
padding:10px;
border:1 solid #999999;
margin:auto;
}

.change_reservation {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #c123de), color-stop(1, #a20dbd) );
	background:-moz-linear-gradient( center top, #c123de 5%, #a20dbd 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#c123de', endColorstr='#a20dbd');
	background-color:#c123de;
	-webkit-border-top-left-radius:6px;
	-moz-border-radius-topleft:6px;
	border-top-left-radius:6px;
	-webkit-border-top-right-radius:6px;
	-moz-border-radius-topright:6px;
	border-top-right-radius:6px;
	-webkit-border-bottom-right-radius:6px;
	-moz-border-radius-bottomright:6px;
	border-bottom-right-radius:6px;
	-webkit-border-bottom-left-radius:6px;
	-moz-border-radius-bottomleft:6px;
	border-bottom-left-radius:6px;
	text-indent:0;
	border:1px solid #a511c0;
	display:inline-block;
	color:#ffffff;
	font-family:Arial;
	font-size:15px;
	font-weight:bold;
	font-style:normal;
	height:36px;
	line-height:36px;
	width:88px;
	text-decoration:none;
	text-align:center;
}
.change_reservation:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #a20dbd), color-stop(1, #c123de) );
	background:-moz-linear-gradient( center top, #a20dbd 5%, #c123de 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#a20dbd', endColorstr='#c123de');
	background-color:#a20dbd;
}.change_reservation:active {
	position:relative;
	top:1px;
}
.cancel_reservation {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #b5b5b5), color-stop(1, #999999) );
	background:-moz-linear-gradient( center top, #ededed 5%, #dfdfdf 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#dfdfdf');
	background-color:#999999;
	-webkit-border-top-left-radius:6px;
	-moz-border-radius-topleft:6px;
	border-top-left-radius:6px;
	-webkit-border-top-right-radius:6px;
	-moz-border-radius-topright:6px;
	border-top-right-radius:6px;
	-webkit-border-bottom-right-radius:6px;
	-moz-border-radius-bottomright:6px;
	border-bottom-right-radius:6px;
	-webkit-border-bottom-left-radius:6px;
	-moz-border-radius-bottomleft:6px;
	border-bottom-left-radius:6px;
	text-indent:0;
	border:1px solid #dcdcdc;
	display:inline-block;
	color:#777777;
	font-family:Arial;
	font-size:15px;
	font-weight:bold;
	font-style:normal;
	height:36px;
	line-height:36px;
	width:88px;
	text-decoration:none;
	text-align:center;
}
.cancel_reservation:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #b5b5b5), color-stop(1, #999999) );
	background:-moz-linear-gradient( center top, #dfdfdf 5%, #ededed 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#dfdfdf', endColorstr='#ededed');
	background-color:#999999;
}.cancel_reservation:active {
	position:relative;
	top:1px;
}
</style>

        <div style="position:absolute;z-index:5;top:5px;left:10px;"><a href="<?=$mainpage_directory?>/mainpage_my_menu.php"><img src="<?=$image_directory?>/back.png" width="35px"></a></div>
        <div style="width:100%;height:19px;text-align:center;font-size:18px;font-weight:bold;"><p>내가 한 예약</p></div>

        <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">

<center>
<?php
	$login_insert_sql = "select *, tpl.is_vat as is_vat, tpl.update_time as d_time from tb_payment_log tpl, tb_shop ts where tpl.customer_id = '".$user_id."' and tpl.is_cancel = 0 and ts.customer_id = tpl.artist_id group by tpl.update_time desc;";
	$result = mysql_query($login_insert_sql);

	for ($ch_index = 0 ; $result_datas = mysql_fetch_object($result) ; $ch_index++ ) {
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
		$to_hour = $result_datas->to_hour;
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
?>
		<div class="my_reservation">
                                <table style="border:1px solid #999999;width:95%;padding:5px;font-size:14px;">
				 <tr><td height="10px"></td></tr>
                                 <tr>
                                  <td>
                                        <b><?=$year?>년 <?=$month?>월 <?=$day?>일 <?=$hour?>시~<?=($to_hour+1)?>시 / <?=$artist_name?> / <? if ($is_only_point) { echo "포인트"; } else { if ($pay_type == "card") { echo "신용카드"; } else if ($pay_type == "bank") { echo "계좌이체"; } else { echo "매장결제"; } }  ?></b>
                                  </td>
                                 </tr>
<?php
		if ($status == "BR") {
?>
                                 <tr>
                                  <td colspan="3">
                                        <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">
                                  </td>
                                 </tr>
                                 <tr>
                                  <td colspan="3">
					<b>결제 진행중</b><br>
					아래 계좌에 "<?=$bank?>"이름으로 입금부탁드립니다.<br><br>
                                        <b>기업은행<br>054-143076-01-013<br>주식회사 펫이지</b><br><br>
					입금기한 : <?=date("Y년 m월 d일 H시 i분 s초", strtotime($expire_time))?>
                                  </td>
                                 </tr>
<?php
		}
?>
                                 <tr>
                                  <td colspan="3">
                                        <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">
                                  </td>
                                 </tr>
<?php
	if ($go_2_offline) {
				$crypto = new Crypto();
                                $enc_artist_id = $crypto->encode(trim($artist_id), $access_key, $secret_key);
                                $off_sql1 = "select * from tb_request_artist where customer_id = '".$enc_artist_id."';";
                                $off_result1 = mysql_query($off_sql1);
                                if ($off_rows = mysql_fetch_object($off_result1)) {
                                        if ($off_rows->is_got_offline_shop == 1) {
?>
				 <tr>
				  <td> <b>매장정보</b><br>
					매장방문을 선택하셨습니다.
                        <center>
                                <div style="height:3px"></div>
                                <a href="<?=$shop_directory?>/daum_map.php?artist_id=<?=$artist_id?>&backurl=<?=$mainpage_directory?>/manage_my_reservation.php">

                                <div style="width:100%;height:80px;position:relative;z-index:-1;background-image:url('<?=$image_directory?>/map_back.png');background-size:cover;">
                                        <table width="100%" height="100%" style="position:absolute;z-index:1;opacity:0.4;background-color:#000000;">
                                                <tr>
                                                        <td></td>
                                                </tr>
                                        </table>
                                <table width="100%" style="position:absolute;z-index:2;height:80px;">
                                <tr><td valign="middle" style="padding:10px;font-weight:bold;">
                                <!--b><?=$crypto->decode($off_rows->offline_shop_name, $access_key, $secret_key)?></b><br><br-->
                                <?php
                                        $address_off = $crypto->decode($off_rows->offline_shop_address, $access_key, $secret_key);
                                        if ($address_off) {
?>
                                                <font style="color:#fff;font-size:14px;"><?=str_replace("|", "", strstr($address_off, "|"))?></font>
<?php
                                        }
                                ?>
                                </td></tr></table>
                                </div>
                                </a>
                        </center>

				  </td>
				 </tr>
<?php
					}
				}
	} else {
?>
                                 <tr>
                                  <td> <b>방문정보</b><br>
					출장요청지 : <?=str_replace("|", "", strstr($address1.$address2, "|"))?><br>
					전화번호 : <?=$cellphone?><br><br>
                                  </td>
                                 </tr>

<?php
	}
?>
                                 <tr>
                                  <td>
                                        <b>결제상품</b><br>
<?php
                                        $total_price = $per_diem;
                                        $product_info = $product;
					$selected_pet_info_array = explode(",", $product_info);
?>
                                        <table width="100%" style="border:1px solid #999999;">
<?php
                for ($spiai = 0 ; $spiai < sizeof($selected_pet_info_array) ; $spiai = $spiai + 1) {
                        //if (startsWith($selected_pet_info_array[$spiai], $name."|")) 
			{
                                //$selected_pet_service_number = $selected_pet_service_number + 1;
                                $service_infos = explode("|", $selected_pet_info_array[$spiai]);
?>
					<tr><td colspan="4"><b><?=$service_infos[0]?>/<?=$service_infos[1]?></b></td></tr>
<?php
                                if ($service_infos[1] == "개") {

?>
                                        <tr>
                                         <td colspan="4" style="font-size:13px;">
                                                <!-- $selected_pet_info_array[$spiai] -->
                                                <table width="100%">
                                                 <tr>
                                                  <td>
                                                <?=$service_infos[3]?>/<?=explode(":",$service_infos[4])[0]?>/<?=explode(":",$service_infos[5])[0]."Kg"?>
                                                  </td>
                                                  <td align="right">
                                                <?php
                                                        $dog_total_i_price = 0;
                                                        $basic_a_p = intval(explode(":",$service_infos[4])[1]) + intval(explode(":",$service_infos[5])[1]);
                                                        $total_price = $total_price + $basic_a_p;
                                                        $dog_total_i_price = $dog_total_i_price + $basic_a_p;
                                                        echo number_format($basic_a_p)."원";
                                                ?>
                                                  </td>
                                                 </tr>
<?php
                                                if (strlen($service_infos[6]) > 0) {
?>
                                                 <tr>
                                                  <td>
                                                        <?=explode(":",$service_infos[6])[0]?>
                                                  </td>
                                                  <td align="right">
                                                        <?php
                                                        $ahbaci = intval(explode(":",$service_infos[6])[1]);
                                                        $total_price = $total_price + $ahbaci;
                                                        $dog_total_i_price = $dog_total_i_price + $ahbaci;
                                                        echo number_format($ahbaci)."원";
                                                        ?>
                                                  </td>
                                                 </tr>
<?php
                                                }
?>
<?php
                                                if (startsWith($service_infos[4], "전체미용")) {
?>
                                                 <tr>
                                                  <td>
                                                        <?=explode(":",$service_infos[7])[0]?>mm
                                                  </td>
                                                  <td align="right">
                                                        <?php
                                                        $ahbaci = intval(explode(":",$service_infos[7])[1]);
                                                        $total_price = $total_price + $ahbaci;
                                                        $dog_total_i_price = $dog_total_i_price + $ahbaci;
                                                        echo number_format($ahbaci)."원";
                                                        ?>
                                                  </td>
                                                 </tr>
<?php
                                                }
?>
<?php
                                                if (!startsWith($service_infos[4], "부분미용")) {
?>
                                                 <tr>
                                                  <td>
                                                        <?=explode(":",$service_infos[8])[0]?>
                                                  </td>
                                                  <td align="right">
                                                        <?php
                                                        $ahbaci = intval(explode(":",$service_infos[8])[1]);
                                                        $total_price = $total_price + $ahbaci;
                                                        $dog_total_i_price = $dog_total_i_price + $ahbaci;
                                                        echo number_format($ahbaci)."원";
                                                        ?>
                                                  </td>
                                                 </tr>
<?php
                                                }
?>
<?php
                                                if (strlen($service_infos[9]) > 0) {
?>
                                                 <tr>
                                                  <td>
                                                        발톱
                                                  </td>
                                                  <td align="right">
                                                        <?php
                                                        $ahbaci = intval($service_infos[9]);
                                                        $total_price = $total_price + $ahbaci;
                                                        $dog_total_i_price = $dog_total_i_price + $ahbaci;
                                                        echo number_format($ahbaci)."원";
                                                        ?>
                                                  </td>
                                                 </tr>
<?php
                                                }
?>
<?php
                                                if (strlen($service_infos[10]) > 0) {
?>
                                                 <tr>
                                                  <td>
                                                        나팔/장화/방울
                                                  </td>
                                                  <td align="right">
                                                        <?php
                                                        $ahbaci = intval($service_infos[10]);
                                                        $total_price = $total_price + $ahbaci;
                                                        $dog_total_i_price = $dog_total_i_price + $ahbaci;
                                                        echo number_format($ahbaci)."원";
                                                        ?>
                                                  </td>
                                                 </tr>
<?php
                                                }
?>
<?php
                                                if (strlen($service_infos[11]) > 0) {
?>
                                                 <tr>
                                                  <td>
                                                        나팔/장화/방울
                                                  </td>
                                                  <td align="right">
                                                        <?php
                                                        $ahbaci = intval($service_infos[11]);
                                                        $total_price = $total_price + $ahbaci;
                                                        $dog_total_i_price = $dog_total_i_price + $ahbaci;
                                                        echo number_format($ahbaci)."원";
                                                        ?>
                                                  </td>
                                                 </tr>
<?php
                                                }
?>

<?php
                                                if (strlen($service_infos[13]) > 0) {
?>
                                                 <tr>
                                                  <td>
                                                        염색(<?=explode(":",$service_infos[12])[0]?> 포인트)
                                                  </td>
                                                  <td align="right">
                                                        <?php
                                                        $ahbaci = intval(explode(":",$service_infos[12])[1]);
                                                        $total_price = $total_price + $ahbaci;
                                                        $dog_total_i_price = $dog_total_i_price + $ahbaci;
                                                        echo number_format($ahbaci)."원";
                                                        ?>
                                                  </td>
                                                 </tr>
<?php
                                                }
?>
<?php
                                                $option_count_1 = intval($service_infos[14]);
                                                $etc_p_index = 15 + $option_count_1;

                                                for ($option_ii = 0 ; $option_ii < $option_count_1 ; $option_ii = $option_ii + 1) {
?>
                                                 <tr>
                                                  <td>
                                                        <?=explode(":",$service_infos[14+($option_ii + 1)])[0]?>
                                                  </td>
                                                  <td align="right">
                                                        <?php
                                                        $ahbaci = intval(explode(":",$service_infos[14+($option_ii + 1)])[1]);
                                                        $total_price = $total_price + $ahbaci;
                                                        $dog_total_i_price = $dog_total_i_price + $ahbaci;
                                                        echo number_format($ahbaci)."원";
                                                        ?>
                                                  </td>
                                                 </tr>
<?php
                                                }
?>
<?php
                                                $option_count_1 = intval($service_infos[$etc_p_index]);
                                                for ($option_ii = 0 ; $option_ii < $option_count_1 ; $option_ii = $option_ii + 1) {
?>
                                                 <tr>
                                                  <td>
                                                        <?=explode(":",$service_infos[$etc_p_index+($option_ii + 1)])[1]?>
                                                  </td>
                                                  <td align="right">
                                                        <?php
                                                        $ahbaci = intval(explode(":",$service_infos[$etc_p_index+($option_ii + 1)])[2]);
                                                        $total_price = $total_price + $ahbaci;
                                                        $dog_total_i_price = $dog_total_i_price + $ahbaci;
                                                        echo number_format($ahbaci)."원";
                                                        ?>
                                                  </td>
                                                 </tr>
<?php
                                                }
?>
                                                 <tr>
                                                  <td colspan="3" align="right">
                                                        <b>합산 금액 : <?=number_format($dog_total_i_price)?> 원</b>
                                                  </td>
                                                 </tr>
                                                </table>
                                         </td>
                                        </tr>
<?php
                                } else { // cat
?>
                                        <tr>
                                         <td colspan="4" style="font-size:13px;">
                                                <!-- =$selected_pet_info_array[$spiai] -->

                                                <table width="100%">
                                                 <tr>
                                                  <td>
                                                <?=$service_infos[3]?>/<?=explode(":",$service_infos[5])[0]?>/<?="~".explode(":",$service_infos[4])[0]."Kg"?>
                                                  </td>
                                                  <td align="right">
                                                <?php
                                                        $cat_total_i_price = 0;
                                                        $basic_a_p = intval(explode(":",$service_infos[5])[1]) + intval(explode(":",$service_infos[4])[1]);
                                                        $total_price = $total_price + $basic_a_p;
                                                        $cat_total_i_price = $cat_total_i_price + $basic_a_p;
                                                        echo number_format($basic_a_p)."원";
                                                ?>
                                                  </td>
                                                 </tr>

<?php
                                                if (strlen($service_infos[6]) > 0) {
?>
                                                 <tr>
                                                  <td>
                                                        발톱
                                                  </td>
                                                  <td align="right">
                                                        <?php
                                                        $ahbaci = intval($service_infos[6]);
                                                        $total_price = $total_price + $ahbaci;
                                                        $cat_total_i_price = $cat_total_i_price + $ahbaci;
                                                        echo number_format($ahbaci)."원";
                                                        ?>
                                                  </td>
                                                 </tr>
<?php
                                                }
?>
<?php
                                                if (strlen($service_infos[7]) > 0) {
?>
                                                 <tr>
                                                  <td>
                                                        목욕
                                                  </td>
                                                  <td align="right">
                                                        <?php
                                                        $ahbaci = intval($service_infos[7]);
                                                        $total_price = $total_price + $ahbaci;
                                                        $cat_total_i_price = $cat_total_i_price + $ahbaci;
                                                        echo number_format($ahbaci)."원";
                                                        ?>
                                                  </td>
                                                 </tr>
<?php
                                                }
?>
<?php
                                                $option_count_1 = intval($service_infos[8]);
                                                $etc_p_index = 9 + $option_count_1;

                                                for ($option_ii = 0 ; $option_ii < $option_count_1 ; $option_ii = $option_ii + 1) {
?>
                                                 <tr>
                                                  <td>
                                                        <?=explode(":",$service_infos[8+($option_ii + 1)])[0]?>
                                                  </td>
                                                  <td align="right">
                                                        <?php
                                                        $ahbaci = intval(explode(":",$service_infos[8+($option_ii + 1)])[1]);
                                                        $total_price = $total_price + $ahbaci;
                                                        $cat_total_i_price = $cat_total_i_price + $ahbaci;
                                                        echo number_format($ahbaci)."원";
                                                        ?>
                                                  </td>
                                                 </tr>
<?php
                                                }
?>
<?php
                                                $option_count_1 = intval($service_infos[$etc_p_index]);
                                                for ($option_ii = 0 ; $option_ii < $option_count_1 ; $option_ii = $option_ii + 1) {
?>
                                                 <tr>
                                                  <td>
                                                        <?=explode(":",$service_infos[$etc_p_index+($option_ii + 1)])[1]?>
                                                  </td>
                                                  <td align="right">
                                                        <?php
                                                        $ahbaci = intval(explode(":",$service_infos[$etc_p_index+($option_ii + 1)])[2]);
                                                        $total_price = $total_price + $ahbaci;
                                                        $cat_total_i_price = $cat_total_i_price + $ahbaci;
                                                        echo number_format($ahbaci)."원";
                                                        ?>
                                                  </td>
                                                 </tr>
<?php
                                                }
?>
                                                 <tr>
                                                  <td colspan="3" align="right">
                                                        <b>합산 금액 : <?=number_format($cat_total_i_price)?> 원</b>
                                                  </td>
                                                 </tr>
                                                </table>

                                         </td>
                                        </tr>
<?php
                                }
?>
<?php
                        }
                }
?>
                                        </table>
                                        <div style="height:5px;"></div>











					<table width="100%">
                                         <tr>
                                          <td colspan="5">
                                                <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">
                                          </td>
                                         </tr>
                                         <tr>
                                          <td colspan="5" align="right">
                                                출장비 : <?=number_format($per_diem)?>원
                                          </td>
                                         </tr>
<?php
        $vat_price = 0;
        if ($is_vat) {
                $vat_price = ($total_price / 10);
                $total_price = $total_price + $vat_price;
?>
                                         <tr>
                                          <td colspan="5" align="right">
                                                부가세 10% : <?=number_format($vat_price)?>원
                                          </td>
                                         </tr>
<?php
        }
?>

                                         <tr>
                                          <td colspan="5" align="right">
                                                사용포인트 : <?=number_format($spend_point)?>점
                                          </td>
                                         </tr>
                                         <tr>
                                          <td colspan="5">
                                                <hr style="color:#999999;width:100%;border:0;border:1px solid #999999;">
                                          </td>
                                         </tr>
                                         <tr>
                                          <td colspan="5" align="right">
<?php
						if ($pay_type == "offline") {
							echo "<b>최종 결제예상금액</b>";
						} else {
							echo "<b>최종 결제금액</b>";
						}
?> 
                                                 <b><?=number_format($total_price - $spend_point)?>원</b>
                                          </td>
                                         </tr>
					 <tr><td height="10px;"></td></tr>
					 <tr>
					  <td colspan="5" align="right">
<script>
function cancel_button (artist_id, payment_log_seq) {

	$.MessageBox({
		buttonFail	: "아니오",
		buttonDone      : "예",
		message         : "<center><font style='font-size:15px;font-weight:bold;'>예약을 취소 하시겠습니까?</font><br>(취소시 수수료가 발생할 수 있음)</center>"
	}).done(function(){
		location.href = 'cancel_reservation.php?artist_id='+artist_id+'&payment_log_seq='+payment_log_seq;
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
/*
				echo "now_year".$now_year."<br>";
				echo "now_month".$now_month."<br>";
				echo "now_day".$now_day."<br>";
				echo "now_hour".$now_hour."<br>";
				echo "update_year".$update_year."<br>";
				echo "update_month".$update_month."<br>";
				echo "update_day".$update_day."<br>";
*/
				$d_date = date('Y-m-d H:i:s', strtotime($year."-".$month."-".$day." ".$to_hour.":00:00"));
				$ds_date = date('Y-m-d H:i:s', strtotime($year."-".$month."-".$day." ".$hour.":00:00"));
				$n_date = date('Y-m-d H:i:s');
				/*if (strtotime($d_date."+3 hour") < strtotime($n_date)) {
						<a href="?order_id=<?=$order_id?>" class="change_reservation">후기작성</a>
				} */
				//echo date(strtotime("-2 hours", strtotime($ds_date)))."<br>";
				//echo date(strtotime($n_date))."<br>";
				if (strtotime("-2 hours", strtotime($ds_date)) > strtotime($n_date)) {
?>
<script>
function notice_no_noway() {
	$.MessageBox({
	    buttonDone      : "확인",
	    message         : "<center>예약 당일 취소는<br>수수료가 발생합니다.<br><br>고객센터를 이용해주세요.<br>( 1661-9956 )</center>"
	}).done(function(){
	});
}
</script>
						<a href="change_customer_schedule_board.php?artist_id=<?=$artist_id?>&payment_log_seq=<?=$payment_log_seq?>" class="change_reservation">예약변경</a>
						<!--a href="#" class="change_reservation">예약변경</a-->
<?php
					if ($now_day != $day || $now_month != $month) {
?>
						<a href="#" class="cancel_reservation" onclick="cancel_button('<?=$artist_id?>', '<?=$payment_log_seq?>')">예약취소</a>
<?php
					} else if ($now_year == $year && $now_month == $month && $now_day == $day) {
						if ($update_year == $year && $update_month == $month && $update_day == $day) {
							// 당일 구매 당일 2시간 전 취소
?>
							<a href="#" class="cancel_reservation" onclick="cancel_button('<?=$artist_id?>', '<?=$payment_log_seq?>')">예약취소</a>
<?php
						} else {
?>
							<a href="#" class="cancel_reservation" onclick="notice_no_noway();">예약취소</a>
<?php
						}
					} else {
?>
						<a href="#" class="cancel_reservation" onclick="notice_no_noway();">예약취소</a>
<?php
						// 장난해 취소 못해...
					}
?>
<?php
				}
?>
					  </td>
					 </tr>
                                        </table>
                                  </td>
                                 </tr>
                                </table>
		</div>
<?php
	}

	if ($ch_index == 0) {
?>
		<br>
		<br>
		<img src="<?=$image_directory?>/myschedule.png" width="20%" style="opacity:0.5;">
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
