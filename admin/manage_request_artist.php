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
	@font-face {font-family: 'NL2GB';src: url("../fonts/NEXON_Lv2_Gothic_Bold.woff");}
	@font-face {font-family: 'NL2GR';src: url('../fonts/NEXON_Lv2_Gothic.woff') format('woff');}
	html, body { font-family: 'NL2GR'; }

	a {
		text-decoration: none;
	}

	a:link {
		color: white;
	}

	a:visited {
		color: white;
	}

	a:hover {
		color: white;
	}

	a:active {
		color: white;
	}

	.my_shop_div {
		position: relative;
		z-index: 5;
		width: 93%;
		height: 30px;
		text-align: left;
		padding: 5px;
		border-bottom: 1px solid #efefef;
		border: 1;
		font-size: 15px;
		font-weight: bold;
		background-image: url('<?= $image_directory ?>/shop_back3.jpg');
		background-size: 100%;
		background-repeat: no-repeat;
		margin: auto;
	}

	.my_shop_img {
		position: absolute;
		z-index: 5;
		right: 10px;
		height: 30px;
	}

	.my_shop_text {
		position: absolute;
		z-index: 5;
		left: 10px;
		height: 30px;
	}

	.my_menu_div {
		position: relative;
		z-index: 0;
		width: 97%;
		text-align: left;
		padding: 5px;
		border: 1px solid #999999;
		border: 1;
		font-size: 14px;
		//font-weight:bold;
		//margin:auto;
	}

	.my_menu_img {
		position: absolute;
		z-index: 5;
		right: 10px;
		height: 23px;
	}

	.my_menu_text {
		position: absolute;
		z-index: 5;
		left: 10px;
		height: 30px;
	}

	.my_menu_img2 {
		position: absolute;
		z-index: 5;
		right: 10px;
		height: 30px;
	}
</style>
<style type="text/css">
	.agree_button {
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #c123de), color-stop(1, #a20dbd));
		background: -moz-linear-gradient(center top, #c123de 5%, #a20dbd 100%);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#c123de', endColorstr='#a20dbd');
		background-color: #c123de;
		-webkit-border-top-left-radius: 0px;
		-moz-border-radius-topleft: 0px;
		border-top-left-radius: 0px;
		-webkit-border-top-right-radius: 0px;
		-moz-border-radius-topright: 0px;
		border-top-right-radius: 0px;
		-webkit-border-bottom-right-radius: 0px;
		-moz-border-radius-bottomright: 0px;
		border-bottom-right-radius: 0px;
		-webkit-border-bottom-left-radius: 0px;
		-moz-border-radius-bottomleft: 0px;
		border-bottom-left-radius: 0px;
		text-indent: 0;
		border: 1px solid #a511c0;
		display: inline-block;
		color: #ffffff;
		font-family: Arial;
		font-size: 15px;
		font-weight: bold;
		font-style: normal;
		height: 40px;
		line-height: 40px;
		width: 122px;
		text-decoration: none;
		text-align: center;
	}

	.agree_button:hover {
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #a20dbd), color-stop(1, #c123de));
		background: -moz-linear-gradient(center top, #a20dbd 5%, #c123de 100%);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#a20dbd', endColorstr='#c123de');
		background-color: #a20dbd;
	}

	.agree_button:active {
		position: relative;
		top: 1px;
	}

	.agree_button2 {
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #ededed), color-stop(1, #dfdfdf));
		background: -moz-linear-gradient(center top, #ededed 5%, #dfdfdf 100%);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#dfdfdf');
		background-color: #ededed;
		-webkit-border-top-left-radius: 0px;
		-moz-border-radius-topleft: 0px;
		border-top-left-radius: 0px;
		-webkit-border-top-right-radius: 0px;
		-moz-border-radius-topright: 0px;
		border-top-right-radius: 0px;
		-webkit-border-bottom-right-radius: 0px;
		-moz-border-radius-bottomright: 0px;
		border-bottom-right-radius: 0px;
		-webkit-border-bottom-left-radius: 0px;
		-moz-border-radius-bottomleft: 0px;
		border-bottom-left-radius: 0px;
		text-indent: 0;
		border: 1px solid #dcdcdc;
		display: inline-block;
		color: #777777;
		font-family: Arial;
		font-size: 15px;
		font-weight: bold;
		font-style: normal;
		height: 40px;
		line-height: 40px;
		width: 122px;
		text-decoration: none;
		text-align: center;
	}

	.agree_button2:hover {
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #dfdfdf), color-stop(1, #ededed));
		background: -moz-linear-gradient(center top, #dfdfdf 5%, #ededed 100%);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#dfdfdf', endColorstr='#ededed');
		background-color: #dfdfdf;
	}

	.agree_button2:active {
		position: relative;
		top: 1px;
	}

	.bjj_header { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; box-sizing: border-box; border-bottom: 1px solid #999; background-color: rgba(255,255,255,0.8); text-align: center; z-index: 100; }
	.bjj_header .top_left { position: absolute; left: 0px; top: 0px; width: 40px; height: 50px; padding-top: 10px; padding-right: 10px; }
	.bjj_header .top_left img { width: 26px; vertical-align: text-bottom; }
	.bjj_header .top_title { font-size: 18px; line-height: 50px; }
</style>

<?php
$crypto = new Crypto();

$login_insert_sql = "select * from tb_customer where id = '" . $user_id . "' and (admin_flag = true or operator_flag = true)";
$result = mysqli_query($connection,$login_insert_sql);

if ($result_datas = mysqli_fetch_object($result)) {
	$nickname = $result_datas->nickname;
	$photo = $result_datas->photo;
	$c_cellphone = $result_datas->cellphone;
	$cellphone_confirm = $result_datas->cellphone_confirm;
	$id = $result_datas->id;
	$email_confirm = $result_datas->email_confirm;
	$mileage = $result_datas->mileage;
	$my_shop_flag = $result_datas->my_shop_flag;
	$push_option = $result_datas->push_option;
	$admin_flag = $result_datas->admin_flag;
	?>

<!--div class="header-back-btn"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/back.png" width="35px"></a></div>
<div style="width:100%;height:19px;text-align:center;font-size:18px;font-weight:bold;">
	<p>?????? ?????? ?????? ??????</p>
</div>

<hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;"-->
<div class="bjj_header">
	<a href="<?= $admin_directory ?>/"><div class="top_left"><img src="<?= $image_directory ?>/btn_back_2.png"></div></a>
	<div class="top_title">?????? ?????? ?????? ??????</div>
</div>

<?
	$seq = $_REQUEST['seq'];
	if ($seq == null || $seq == "") {
		$seq = 1;
	}
	?>

<table width="100%" style="color:#999999;font-size:15px;text-align:center;margin-top: 60px;height: 50px;position: sticky;left:0px;top:50px;background-color:#fff;z-index:99;">
	<tr>
		<td>
			<a href="?seq=1" <?php
									if ($seq == 1) {
										echo " style='color:#000000;font-weight:bold;font-size:16px;padding:10px;border-bottom:3px solid #F69ECE;' ";
									} else {
										echo " style='color:#999999;font-weight:bold;font-size:16px;padding:10px;border-bottom:0px solid #F69ECE;' ";
									}
									?>>
				?????????
			</a>
		</td>
		<td>
			<a href="?seq=2" <?php
									if ($seq == 2) {
										echo " style='color:#000000;font-weight:bold;font-size:16px;padding:10px;border-bottom:3px solid #F69ECE;' ";
									} else {
										echo " style='color:#999999;font-weight:bold;font-size:16px;padding:10px;border-bottom:0px solid #F69ECE;' ";
									}
									?>>
				??????
			</a>
		</td>
	</tr>
</table>

<?php

	if ($seq == 1) {


		$sql = "select * from tb_request_artist where step <= 5 order by update_time desc;";
		$result = mysqli_query($connection,$sql);
		$count = mysqli_num_rows($result);
		echo "<font style='font-size:13px;'>??? : " . $count . "???</font><br>";
		while ($result_datas = mysqli_fetch_object($result)) {
			$customer_id = $result_datas->customer_id;
			$step = $result_datas->step;
			$name = $result_datas->name;
			$cellphone = $result_datas->cellphone;
			$is_personal = $result_datas->is_personal;
			$is_business = $result_datas->is_business;
			$business_number = $result_datas->business_number;
			$business_license = $result_datas->business_license;
			if(strpos($business_license, "/pet/") === false && $business_license != ''){
				$business_license = "https://image.banjjakpet.com/".$business_license;
			}
			$region = $result_datas->region;
			$professional = $result_datas->professional;
			$is_got_offline_shop = $result_datas->is_got_offline_shop;
			$offline_shop_name = $result_datas->offline_shop_name;
			$offline_shop_phonenumber = $result_datas->offline_shop_phonenumber;
			$offline_shop_address = $result_datas->offline_shop_address;
			$update_time = $result_datas->update_time;
			$working_years = $result_datas->working_years;
			$enter_path = $result_datas->enter_path;

			$customer_id = $crypto->decode($customer_id, $access_key, $secret_key);
			$name = $crypto->decode($name, $access_key, $secret_key);
			$cellphone = $crypto->decode($cellphone, $access_key, $secret_key);
			$business_number = $crypto->decode($business_number, $access_key, $secret_key);
			$region = $crypto->decode($region, $access_key, $secret_key);
			$professional = $crypto->decode($professional, $access_key, $secret_key);
			$offline_shop_name = $crypto->decode($offline_shop_name, $access_key, $secret_key);
			$offline_shop_phonenumber = $crypto->decode($offline_shop_phonenumber, $access_key, $secret_key);
			$offline_shop_address = $crypto->decode($offline_shop_address, $access_key, $secret_key);
			?>
<div class="my_menu_div">
	<table width="100%">
		<tr>
			<td>
				<?php
							//if ($step == 5) { echo "<font style='color:blue;font-weight:bold;font-size:17px;'>???????????????</font><br>"; } else { echo "<font style='color:black;font-weight:bold;font-size:17px;'>????????????</font><br>"; }
							?>
				????????? : <?= $customer_id ?><?= ($enter_path == "web")? " [".strtoupper($enter_path)."]" : "" ?><br>
				<?php
							if ($is_personal) {
								echo $name;
								echo "/??????<br>";
							}
							if ($is_business) {
								echo $name;
								echo "/????????? (<span style='color:#fff;'>/</span>";
								echo $business_number . "<span style='color:#fff;'>/</span>) <br>";
								echo "<img src='" . $business_license . "' width='100%'><br>";
							}
							?>
				???????????? : ????????? <?= $cellphone ?> / ?????????
				<?php
							$s_sql = "select * from tb_customer where id = '" . $customer_id . "'";
							$s_result = mysqli_query($connection,$s_sql);
							if ($s_result_datas = mysqli_fetch_object($s_result)) {
								echo $crypto->decode($s_result_datas->cellphone, $access_key, $secret_key);
							}
							?>
				<br>
				???????????? : <?= $region ?><br>
				???????????? : <?= $professional ?><br>
				?????? : <?= $working_years ?> ???<br>
				<?php
							echo "?????????????????? : ";
							if ($is_got_offline_shop) {
								echo $offline_shop_name . " (" . $offline_shop_phonenumber . ")<br>";
								echo $offline_shop_address . "<br>";
							} else {
								echo "-<br>";
							}
							?>
				????????? : <?= $update_time ?><br>
				<div style="height:5px;"></div>
				<a href="#" class="agree_button" onclick="javascript:agree_artist('<?= $customer_id ?>');">??? ???</a>
				<a href="#" class="agree_button2" onclick="javascript:delete_artist('<?= $customer_id ?>');">
					<font style="color:#777777;">??? ???</font>
				</a>
			</td>
		</tr>
	</table>
</div>
<?php
		}
	} else {
		?>
<form action="manage_request_artist.php" method="POST" style="margin-top: 20px;">
	<input type="hidden" name="seq" value="<?= $seq ?>" />
	??????ID : <input type="text" name="ph_customer_id" value="<?= $_REQUEST['ph_customer_id'] ?>" /> <button type="submit">??? ???</button> <br>
</form>
<?php
		$sql = "select * from tb_request_artist where step > 5 ";
		if ($_REQUEST['ph_customer_id']) {
			$enc_customer_id = $crypto->encode($_REQUEST['ph_customer_id'], $access_key, $secret_key);
			$sql = $sql . " and customer_id = '" . $enc_customer_id . "' ";
		}
		$sql = $sql . " order by update_time desc;";
		$result = mysqli_query($connection,$sql);
		$count = mysqli_num_rows($result);
		echo "<font style='font-size:13px;'>??? : " . $count . "???</font><br>";
		while ($result_datas = mysqli_fetch_object($result)) {
			$customer_id = $result_datas->customer_id;
			$step = $result_datas->step;
			$name = $result_datas->name;
			$cellphone = $result_datas->cellphone;
			$is_personal = $result_datas->is_personal;
			$is_business = $result_datas->is_business;
			$business_number = $result_datas->business_number;
			$business_license = $result_datas->business_license;
			if(strpos($business_license, "/pet/") === false && $business_license != ''){
				$business_license = "https://image.banjjakpet.com/".$business_license;
			}
			$region = $result_datas->region;
			$professional = $result_datas->professional;
			$is_got_offline_shop = $result_datas->is_got_offline_shop;
			$offline_shop_name = $result_datas->offline_shop_name;
			$offline_shop_phonenumber = $result_datas->offline_shop_phonenumber;
			$offline_shop_address = $result_datas->offline_shop_address;
			$update_time = $result_datas->update_time;
			$working_years = $result_datas->working_years;

			$customer_id = $crypto->decode($customer_id, $access_key, $secret_key);
			$name = $crypto->decode($name, $access_key, $secret_key);
			$cellphone = $crypto->decode($cellphone, $access_key, $secret_key);
			$business_number = $crypto->decode($business_number, $access_key, $secret_key);
			$region = $crypto->decode($region, $access_key, $secret_key);
			$professional = $crypto->decode($professional, $access_key, $secret_key);
			$offline_shop_name = $crypto->decode($offline_shop_name, $access_key, $secret_key);
			$offline_shop_phonenumber = $crypto->decode($offline_shop_phonenumber, $access_key, $secret_key);
			$offline_shop_address = $crypto->decode($offline_shop_address, $access_key, $secret_key);
			?>
<div class="my_menu_div">
	<table width="100%">
		<tr>
			<td>
				<?php
							//if ($step == 5) { echo "<font style='color:blue;font-weight:bold;font-size:17px;'>???????????????</font><br>"; } else { echo "<font style='color:black;font-weight:bold;font-size:17px;'>????????????</font><br>"; }
							?>
				????????? : <?= $customer_id ?><br>
				<?php
							if ($is_personal) {
								echo $name;
								echo "/??????<br>";
							}
							if ($is_business) {
								echo $name;
								echo "/????????? (";
								echo $business_number . ") <br>";
								echo "<img src='" . $business_license . "' width='100%'><br>";
							}
							?>
				???????????? : ????????? <?= $cellphone ?> / ?????????
				<?php
							$s_sql = "select * from tb_customer where id = '" . $customer_id . "'";
							$s_result = mysqli_query($connection,$s_sql);
							if ($s_result_datas = mysqli_fetch_object($s_result)) {
								echo $crypto->decode($s_result_datas->cellphone, $access_key, $secret_key);
							}
							?>
				<br>
				???????????? : <?= $region ?><br>
				???????????? : <?= $professional ?><br>
				?????? : <?= $working_years ?> ???<br>
				<?php
							echo "?????????????????? : ";
							if ($is_got_offline_shop) {
								echo $offline_shop_name . " (" . $offline_shop_phonenumber . ")<br>";
								echo $offline_shop_address . "<br>";
							} else {
								echo "-<br>";
							}
							?>
				????????? : <?= $update_time ?><br>
				<div style="height:5px;"></div>
				<!--a href="#" class="agree_button" onclick="javascript:agree_artist('<?= $customer_id ?>');">??? ???</a-->
				<!--a href="#" class="agree_button2" onclick="javascript:delete_artist('<?= $customer_id ?>');"><font style="color:#777777;">???   ???</font></a-->
			</td>
		</tr>
	</table>
</div>
<?php
		}
	}
	?>
<div class="btn_top" style="position: fixed; right: 10px; bottom: 10px; width: 50px; height: 50px; text-align: center; line-height: 50px; border: 1px solid #ccc; background-color: #fff; border-radius: 25px;">top</div>
<script>
	function delete_artist(c_id) {
        var result = confirm("?????????????????????????");
        if(result){
            $.ajax({
                url: 'delete_request_artist.php',
                data: {
                    customer_id: c_id
                },
                type: 'POST',
                success: function(data) {
                    alert(data);
                    location.reload();
                },
                error: function(xhr, status, error) {
                    alert("????????????");
                }
            });
        }
	}

	function agree_artist(c_id) {
        var result = confirm("?????????????????????????");
        if(result){
            $.ajax({
                url: 'agree_request_artist.php',
                data: {
                    customer_id: c_id
                },
                type: 'POST',
                success: function(data) {
                    alert(data);
                    location.reload();
                },
                error: function(xhr, status, error) {
                    alert("????????????");
                }
            });
        }
	}

	$(document).on("click", ".btn_top", function(){
		$('html, body').animate({scrollTop: 0}, 100);
	});
</script>
<?php
}

?>