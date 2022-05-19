<?php include "../include/top.php"; ?>

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
z-index:5;
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
        <div style="width:100%;height:19px;text-align:center;font-size:18px;font-weight:bold;"><p>아티스트 입점 문의</p></div>

        <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">

<center>
<?php
	//$login_insert_sql = "select * from tb_payment_log tpl, tb_shop ts where tpl.customer_id = '".$user_id."' and ts.customer_id = tpl.artist_id group by tpl.update_time asc;";
	//$result = mysql_query($login_insert_sql);

	//for ($ch_index = 0 ; $result_datas = mysql_fetch_object($result) ; $ch_index++ ) {
	for ($ch_index = 0 ; 0 ; $ch_index++ ) {
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
?>
		<div class="my_reservation">
                                <table style="border:1px solid #999999;width:95%;padding:5px;font-size:14px;">
				 <tr><td height="10px"></td></tr>
                                 <tr>
                                  <td>
                                        <b><?=$year?>년 <?=$month?>월 <?=$day?>일 <?=$hour?>시 / <?=$artist_name?></b>
                                  </td>
                                 </tr>
                                 <tr>
                                  <td colspan="3">
                                        <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">
                                  </td>
                                 </tr>
                                 <tr>
                                  <td>
                                        <b>결제상품</b><br>
                                        <table width="100%">
<?php
                                        $total_price = $per_diem;
                                        $product_info = $product;
                                        $selected = explode (",", $product_info);
                                        for ($index=0 ; $index < sizeof($selected) ; $index++) {
                                                if ($selected[$index]) {
                                                        list($pname, $pprice, $pcount) = split(":", $selected[$index]);
?>
                                                        <tr><td><?=$pname?></td><td align="right"><?=number_format($pprice)?>원</td></tr>
<?php
                                                        $total_price += ($pprice * $pcount);
                                                }
                                        }
?>
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
                                         <tr>
                                          <td colspan="5">
                                                <hr style="color:#999999;width:100%;border:0;border:1px solid #999999;">
                                          </td>
                                         </tr>
                                         <tr>
                                          <td colspan="5" align="right">
                                                <b>최종 결제금액</b> <b><?=number_format($total_price)?>원</b>
                                          </td>
                                         </tr>
					 <tr><td height="10px;"></td></tr>
					 <tr>
					  <td colspan="2" align="right">
						<a href="#" class="change_reservation">예약변경</a>
						<a href="#" class="cancel_reservation">예약취소</a>
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
		<img src="<?=$image_directory?>/letmein.jpg" width="20%" style="opacity:0.5;">
		<br>
		<br>
		<br>
		<font style="font-size:18px;">준비중입니다.</font>
<?php
	}

	closeDB();
?>
</center>

<?php include "../include/bottom.php"; ?>
