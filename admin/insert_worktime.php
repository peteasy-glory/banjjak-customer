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


		$sql = "SELECT * FROM tb_shop WHERE not customer_id IN (
					SELECT artist_id FROM tb_product_dog_worktime 
				) ";
		$sql = $sql . " order by update_time desc;";
		$result = mysql_query($sql);
		$count = mysql_num_rows($result);
		echo "<font style='font-size:13px;'>총 : " . $count . "건</font><br>";
		while ($result_datas = mysql_fetch_object($result)) {
			$id = ($result_datas->customer_id)? $result_datas->customer_id : "";
			


/*
//			if($id == 'itseokbeom@gmail.com'){
				$insert_sql = "
					INSERT INTO `tb_product_dog_worktime` 
					(`artist_id`, `worktime1`, `worktime2`, `worktime3`, `worktime4`, `worktime5`, `worktime6`, `worktime7`, `worktime8`, `worktime9`, `worktime10`, `worktime11`, `worktime12`, `worktime13`, `worktime14`, `worktime10_title`, `worktime11_title`, `worktime12_title`, `worktime13_title`, `worktime14_title`, `worktime1_disp_yn`, `worktime2_disp_yn`, `worktime3_disp_yn`, `worktime4_disp_yn`, `worktime5_disp_yn`, `worktime6_disp_yn`, `worktime7_disp_yn`, `worktime8_disp_yn`, `worktime9_disp_yn`, `worktime10_disp_yn`, `worktime11_disp_yn`, `worktime12_disp_yn`, `worktime13_disp_yn`, `worktime14_disp_yn`, `reg_dt`, `update_dt`, `is_delete`, `delete_msg`, `delete_dt`) 
					VALUES ('".$id."', '90', '60', '90', '120', '150', '180', '210', '180', '30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'y', 'n', 'n', 'n', 'n', 'n', '2021-06-14 14:48:48', '2022-03-03 13:23:08', '2', NULL, NULL);
				";
				$insert_result = mysql_query($insert_sql);
				if(!$insert_result){
				echo $insert_sql;
				}
//			}
*/


			?>
<div class="my_menu_div">
	<table width="100%">
		<tr>
			<td>
				아이디 : <?= $id ?><?= ($enter_path == "web")? " [".strtoupper($enter_path)."]" : "" ?><br>
			</td>
		</tr>
	</table>
</div>
<?php
		}
	} else {
		?>
<?php
		$sql = "select * from tb_product_dog_worktime ";
		$sql = $sql . " order by reg_dt desc;";
		$result = mysql_query($sql);
		$count = mysql_num_rows($result);
		echo "<font style='font-size:13px;'>총 : " . $count . "건</font><br>";
		while ($result_datas = mysql_fetch_object($result)) {
			$id = ($result_datas->artist_id)? $result_datas->artist_id : "";
			$worktime1 = ($result_datas->worktime1)? $result_datas->worktime1 : "";
			$worktime2 = ($result_datas->worktime2)? $result_datas->worktime2 : "";
			$worktime3 = ($result_datas->worktime3)? $result_datas->worktime3 : "";
			$worktime4 = ($result_datas->worktime4)? $result_datas->worktime4 : "";
			$worktime5 = ($result_datas->worktime5)? $result_datas->worktime5 : "";
			$worktime6 = ($result_datas->worktime6)? $result_datas->worktime6 : "";
			$worktime7 = ($result_datas->worktime7)? $result_datas->worktime7 : "";
			$worktime8 = ($result_datas->worktime8)? $result_datas->worktime8 : "";
			$worktime9 = ($result_datas->worktime9)? $result_datas->worktime9 : "";
			

			?>
<div class="my_menu_div">
	<table width="100%">
		<tr>
			<td>
				아이디 : <?= $id ?><br>
				목욕 : <?= $worktime1 ?><br>
				부분미용 : <?= $worktime2 ?><br>
				부분+목욕 : <?= $worktime3 ?><br>
				위생 : <?= $worktime4 ?><br>
				위생+목욕 : <?= $worktime5 ?><br>
				전체미용 : <?= $worktime6 ?><br>
				스포팅 : <?= $worktime7 ?><br>
				가위컷 : <?= $worktime8 ?><br>
				썸머컷 : <?= $worktime9 ?><br>
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