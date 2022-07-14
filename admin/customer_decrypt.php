<?php
include "../include/top.php";
include "../include/Crypto.class.php";
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
?>

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
$result = mysql_query($login_insert_sql);

if ($result_datas = mysql_fetch_object($result)) {
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
	<p>반짝 입점 신청 관리</p>
</div>

<hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;"-->
<div class="bjj_header">
	<a href="<?= $admin_directory ?>/"><div class="top_left"><img src="<?= $image_directory ?>/btn_back_2.png"></div></a>
	<div class="top_title">반짝 입점 신청 관리</div>
</div>

<?
	$seq = $_REQUEST['seq'];
	if ($seq == null || $seq == "") {
		$seq = 2;
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
				미승인
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
				승인
			</a>
		</td>
	</tr>
</table>

<?php

	if ($seq == 1) {


		$sql = "select * from tb_customer ";
		$sql = $sql . " order by registration_time desc;";
		$result = mysql_query($sql);
		$count = mysql_num_rows($result);
		echo "<font style='font-size:13px;'>총 : " . $count . "건</font><br>";
		while ($result_datas = mysql_fetch_object($result)) {
			$id = ($result_datas->id)? $result_datas->id : "";
			$password = ($result_datas->password)? $result_datas->password : "";
			$usr_name = ($result_datas->usr_name)? $result_datas->usr_name : "";
			$cellphone = ($result_datas->cellphone)? $result_datas->cellphone : "";
			$cellphone_confirm = ($result_datas->cellphone_confirm)? $result_datas->cellphone_confirm : 0;
			$email = ($result_datas->email)? $result_datas->email : "";
			$email_confirm = ($result_datas->email_confirm)? $result_datas->email_confirm : 0;
			$bill_flag = ($result_datas->bill_flag)? $result_datas->bill_flag : 0;
			$nickname = ($result_datas->nickname)? $result_datas->nickname : "";
			$photo = ($result_datas->photo)? $result_datas->photo : "";
			$last_login_time = ($result_datas->last_login_time)? $result_datas->last_login_time : "";
			$registration_time = ($result_datas->registration_time)? $result_datas->registration_time : "";
			$delete_time = ($result_datas->delete_time)? $result_datas->delete_time : "";
			$mileage = ($result_datas->mileage)? $result_datas->mileage : 0;
			$enable_flag = ($result_datas->enable_flag)? $result_datas->enable_flag : 0;
			$push_option = ($result_datas->push_option)? $result_datas->push_option : 0;
			$my_shop_flag = ($result_datas->my_shop_flag)? $result_datas->my_shop_flag : 0;
			$artist_flag = ($result_datas->artist_flag)? $result_datas->artist_flag : 0;
			$admin_flag = ($result_datas->admin_flag)? $result_datas->admin_flag : 0;
			$my_hotel_flag = ($result_datas->my_hotel_flag)? $result_datas->my_hotel_flag : 0;
			$my_playroom_flag = ($result_datas->my_playroom_flag)? $result_datas->my_playroom_flag : 0;
			$partner_flag = ($result_datas->partner_flag)? $result_datas->partner_flag : 0;
			$mainpage_type = ($result_datas->mainpage_type)? $result_datas->mainpage_type : 0;
			$token = ($result_datas->token)? $result_datas->token : "";
			$partner_token = ($result_datas->partner_token)? $result_datas->partner_token : "";
			$is_regist_by_naver = ($result_datas->is_regist_by_naver)? $result_datas->is_regist_by_naver : 0;
			$is_regist_by_apple = ($result_datas->is_regist_by_apple)? $result_datas->is_regist_by_apple : "";
			$age = ($result_datas->age)? $result_datas->age : "";
			$gender = ($result_datas->gender)? $result_datas->gender : "";
			$is_android = ($result_datas->is_android)? $result_datas->is_android : 0;
			$operator_flag = ($result_datas->operator_flag)? $result_datas->operator_flag : 0;
			$last_applogin_time = ($result_datas->last_applogin_time)? $result_datas->last_applogin_time : "";
			$data_delete = ($result_datas->data_delete)? $result_datas->data_delete : 0;



			$cellphone = ($result_datas->cellphone)? $crypto->decode($cellphone, $access_key, $secret_key):"";



//			if($id == 'itseokbeom@gmail.com'){
				$insert_sql = "
					INSERT INTO `tb_customer_decrypt` 
						(`id`, `password`, `usr_name`, `cellphone`, 
						`cellphone_confirm`, `email`, `email_confirm`, `bill_flag`, `nickname`, `photo`, `last_login_time`, `registration_time`, `delete_time`, 
						`mileage`, `enable_flag`, `push_option`, `my_shop_flag`, `artist_flag`, `admin_flag`, `my_hotel_flag`, `my_playroom_flag`, `partner_flag`, 
						`mainpage_type`, `token`, `partner_token`, 
						`is_regist_by_naver`, `is_regist_by_apple`, `age`, `gender`, `is_android`, `operator_flag`, `last_applogin_time`, `data_delete`) 
					VALUES 
						('".$id."', '".$password."', NULLIF('".$usr_name."',''), NULLIF('".$cellphone."',''), 
						'".$cellphone_confirm."', '".$email."', '".$email_confirm."', '".$bill_flag."', '".$nickname."', NULLIF('".$photo."',''), '".$last_login_time."', '".$registration_time."', NULLIF('".$delete_time."',''), 
						'".$mileage."', '".$enable_flag."', '".$push_option."', '".$my_shop_flag."', '".$artist_flag."', '".$admin_flag."', '".$my_hotel_flag."', '".$my_playroom_flag."', '".$partner_flag."', 
						'".$mainpage_type."', NULLIF('".$token."',''), NULLIF('".$partner_token."',''), 
						'".$is_regist_by_naver."', NULLIF('".$is_regist_by_apple."',''), NULLIF('".$age."',''), NULLIF('".$gender."',''), '".$is_android."', '".$operator_flag."', NULLIF('".$last_applogin_time."',''), '".$data_delete."');
				";
				$insert_result = mysql_query($insert_sql);
				if(!$insert_result){
				echo $insert_sql;
				}
//			}



			?>
<div class="my_menu_div">
	<table width="100%">
		<tr>
			<td>
				아이디 : <?= $id ?><?= ($enter_path == "web")? " [".strtoupper($enter_path)."]" : "" ?><br>
				전화번호 : <?= $cellphone ?>
				<br>
				닉네임 : <?= $nickname ?><br>
				가입일 : <?= $registration_time ?><br>
			</td>
		</tr>
	</table>
</div>
<?php
		}
	} else {
		?>
<?php
		$sql = "select * from tb_customer_decrypt ";
		$sql = $sql . " order by registration_time desc;";
		$result = mysql_query($sql);
		$count = mysql_num_rows($result);
		echo "<font style='font-size:13px;'>총 : " . $count . "건</font><br>";
		while ($result_datas = mysql_fetch_object($result)) {
			$id = ($result_datas->id)? $result_datas->id : "";
			$password = ($result_datas->password)? $result_datas->password : "";
			$usr_name = ($result_datas->usr_name)? $result_datas->usr_name : "";
			$cellphone = ($result_datas->cellphone)? $result_datas->cellphone : "";
			$cellphone_confirm = ($result_datas->cellphone_confirm)? $result_datas->cellphone_confirm : "";
			$email = ($result_datas->email)? $result_datas->email : "";
			$email_confirm = ($result_datas->email_confirm)? $result_datas->email_confirm : "";
			$bill_flag = ($result_datas->bill_flag)? $result_datas->bill_flag : "";
			$nickname = ($result_datas->nickname)? $result_datas->nickname : "";
			$photo = ($result_datas->photo)? $result_datas->photo : "";
			$last_login_time = ($result_datas->last_login_time)? $result_datas->last_login_time : "";
			$registration_time = ($result_datas->registration_time)? $result_datas->registration_time : "";
			$delete_time = ($result_datas->delete_time)? $result_datas->delete_time : "";
			$mileage = ($result_datas->mileage)? $result_datas->mileage : "";
			$enable_flag = ($result_datas->enable_flag)? $result_datas->enable_flag : "";
			$push_option = ($result_datas->push_option)? $result_datas->push_option : 0;
			$my_shop_flag = ($result_datas->my_shop_flag)? $result_datas->my_shop_flag : "";
			$artist_flag = ($result_datas->artist_flag)? $result_datas->artist_flag : "";
			$admin_flag = ($result_datas->admin_flag)? $result_datas->admin_flag : "";
			$my_hotel_flag = ($result_datas->my_hotel_flag)? $result_datas->my_hotel_flag : "";
			$my_playroom_flag = ($result_datas->my_playroom_flag)? $result_datas->my_playroom_flag : "";
			$partner_flag = ($result_datas->partner_flag)? $result_datas->partner_flag : "";
			$mainpage_type = ($result_datas->mainpage_type)? $result_datas->mainpage_type : "";
			$token = ($result_datas->token)? $result_datas->token : "";
			$partner_token = ($result_datas->partner_token)? $result_datas->partner_token : "";
			$is_regist_by_naver = ($result_datas->is_regist_by_naver)? $result_datas->is_regist_by_naver : "";
			$is_regist_by_apple = ($result_datas->is_regist_by_apple)? $result_datas->is_regist_by_apple : "";
			$age = ($result_datas->age)? $result_datas->age : "";
			$gender = ($result_datas->gender)? $result_datas->gender : "";
			$is_android = ($result_datas->is_android)? $result_datas->is_android : "";
			$operator_flag = ($result_datas->operator_flag)? $result_datas->operator_flag : "";
			$last_applogin_time = ($result_datas->last_applogin_time)? $result_datas->last_applogin_time : "";
			$data_delete = ($result_datas->data_delete)? $result_datas->data_delete : "";

			?>
<div class="my_menu_div">
	<table width="100%">
		<tr>
			<td>
				아이디 : <?= $id ?><?= ($enter_path == "web")? " [".strtoupper($enter_path)."]" : "" ?><br>
				전화번호 : <?= $cellphone ?>
				<br>
				닉네임 : <?= $nickname ?><br>
				가입일 : <?= $registration_time ?><br>
			</td>
		</tr>
	</table>
</div>
<?php
		}
	}

}

include "../include/bottom.php";
?>