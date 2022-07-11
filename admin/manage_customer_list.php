<?php 
include "../include/top.php";
include "../include/Crypto.class.php";

$crypto = new Crypto();

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

$r_paytype = ($_GET["pt"] && $_GET["pt"] != "")? $_GET["pt"] : "0";
$r_customer_year = ($_GET["cyr"] && $_GET["cyr"] != "")? $_GET["cyr"] : "";
$r_payment_year = ($_GET["pyr"] && $_GET["pyr"] != "")? $_GET["pyr"] : "";
$where_qy = "";
$where_qy2 = "";

if($r_paytype == "1"){
	$where_qy .= " AND pl.pay_type IN ('card', 'bank', 'offline-card', 'offline-cash') ";
}
if($r_customer_year != ""){
	$where_qy2 .= " AND YEAR(cu.registration_time) = '".$r_customer_year."' ";
}
if($r_payment_year != ""){
	$where_qy .= " AND YEAR(pl.buy_time) = '".$r_payment_year."' ";
}

$data = array();
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
$table_row_arr = array(
	"id" => "아이디",
	"cellphone" => "전번",
	"registration_time" => "가입일",
	"last_login_time" => "최종로그인",
	"payment_cnt" => "예약횟수",
	"shop_list" => "이용펫샵"
);
$last_login_time = "2018-12-31 23:59:59";
$customer_ban_list = array("saychanjin@naver.com", "real-jade@naver.com", "chansworld@pickmon.com");
$customer_ban_list = (count($customer_ban_list) > 0)? "'".implode("','", $customer_ban_list)."'" : "";

$sql = "
	SELECT cu.id, cu.cellphone AS cellphone2, pl.cellphone AS cellphone, cu.registration_time, cu.last_login_time, IFNULL(pl.payment_cnt, 0) AS payment_cnt, IFNULL(pl.shop_list, '') AS shop_list
	FROM tb_customer AS cu
		LEFT OUTER JOIN (
			SELECT 
				pl.customer_id, pl.cellphone, 
				GROUP_CONCAT(DISTINCT IF(artist_id <> '', concat(artist_id,'(',SUBSTRING_INDEX(SUBSTRING_INDEX(product,'|',3),'|',-1),')'), '') SEPARATOR ',') AS shop_list, 
				count(*) AS payment_cnt
			FROM tb_payment_log AS pl
				INNER JOIN tb_customer AS cu on pl.customer_id = cu.id
			WHERE cu.enable_flag = '1'
				AND cu.my_shop_flag = '0'
				AND cu.admin_flag = '0'
				AND cu.operator_flag = '0'
				AND pl.approval = '1'
				AND pl.product_type = 'B'
				".$where_qy."
			GROUP BY pl.customer_id
		) AS pl ON cu.id = pl.customer_id
	WHERE cu.enable_flag = '1'
		AND cu.my_shop_flag = '0'
		AND cu.admin_flag = '0'
		AND cu.operator_flag = '0'
		AND cu.id NOT IN (".$customer_ban_list.")
		AND cu.last_login_time > '".$last_login_time."'
		".$where_qy2."
	ORDER BY pl.payment_cnt DESC, cu.last_login_time DESC
";
$result = mysql_query($sql);
$customer_cnt = mysql_num_rows($result);
while($row = mysql_fetch_assoc($result)){
	$data["customer"][] = $row;
}

//rowspan query
$sql = "
	SELECT IFNULL(pl.payment_cnt, 0) AS payment_cnt, COUNT(*) AS cnt
	FROM tb_customer AS cu
		LEFT OUTER JOIN (
			SELECT 
				pl.customer_id, pl.cellphone, 
				GROUP_CONCAT(DISTINCT IF(artist_id <> '', concat(artist_id,'(',SUBSTRING_INDEX(SUBSTRING_INDEX(product,'|',3),'|',-1),')'), '') SEPARATOR ',') AS shop_list, 
				count(*) AS payment_cnt
			FROM tb_payment_log AS pl
				INNER JOIN tb_customer AS cu on pl.customer_id = cu.id
			WHERE cu.enable_flag = '1'
				AND cu.my_shop_flag = '0'
				AND cu.admin_flag = '0'
				AND cu.operator_flag = '0'
				AND pl.approval = '1'
				AND pl.product_type = 'B'
				".$where_qy."
			GROUP BY pl.customer_id
		) AS pl ON cu.id = pl.customer_id
	WHERE cu.enable_flag = '1'
		AND cu.my_shop_flag = '0'
		AND cu.admin_flag = '0'
		AND cu.operator_flag = '0'
		AND cu.id NOT IN (".$customer_ban_list.")
		AND cu.last_login_time > '".$last_login_time."'
		".$where_qy2."
	GROUP BY payment_cnt
	ORDER BY payment_cnt DESC
";
$result = mysql_query($sql);
while($row = mysql_fetch_array($result)){
	$data["cnt"][$row["payment_cnt"]] = $row["cnt"];
}

// 이용횟수 0회 정회원 중 2020년도 가입자
$sql = "
	SELECT YEAR(cu.registration_time) AS registration_year, IFNULL(pl.payment_cnt, 0) AS payment_cnt, COUNT(*) AS cnt
	FROM tb_customer AS cu
		LEFT OUTER JOIN (
			SELECT 
				pl.customer_id, pl.cellphone, 
				GROUP_CONCAT(DISTINCT IF(artist_id <> '', concat(artist_id,'(',SUBSTRING_INDEX(SUBSTRING_INDEX(product,'|',3),'|',-1),')'), '') SEPARATOR ',') AS shop_list, 
				count(*) AS payment_cnt
			FROM tb_payment_log AS pl
				INNER JOIN tb_customer AS cu on pl.customer_id = cu.id
			WHERE cu.enable_flag = '1'
				AND cu.my_shop_flag = '0'
				AND cu.admin_flag = '0'
				AND cu.operator_flag = '0'
				AND pl.approval = '1'
				AND pl.product_type = 'B'
				".$where_qy."
			GROUP BY pl.customer_id
		) AS pl ON cu.id = pl.customer_id
	WHERE cu.enable_flag = '1'
		AND cu.my_shop_flag = '0'
		AND cu.admin_flag = '0'
		AND cu.operator_flag = '0'
		AND cu.id NOT IN (".$customer_ban_list.")
		AND cu.last_login_time > '".$last_login_time."'
		AND YEAR(cu.registration_time) = '2020'
		AND pl.payment_cnt IS NULL
";
$result = mysql_query($sql);
$row = mysql_fetch_assoc($result);
$data["cnt_0_2020year"]["cnt"] = $row["cnt"];

//print_r($data["cnt"]);
?>
<style>
	ul { margin: 0px; padding: 0px; list-style: none; }
	select { margin: 0px; padding: 0px; }
	.bjj_top_menu { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; border-bottom: 1px solid #ccc; background-color: rgba(255, 255, 255, 0.8); margin-top: env(safe-area-inset-top); margin-top: constant(safe-area-inset-top); z-index: 10; }
	.bjj_top_menu .bjj-back-btn { position: absolute; left: 8px; top: 12px; }
	.bjj_top_menu .bjj-back-btn img { width: 26px; vertical-align: middle; }
	.bjj_top_menu .bjj_top_title { width: 100%; height: 50px; line-height: 50px; text-align: center; padding: 0px; }
	.bjj_top_menu .bjj_top_title p { padding: 0px; margin: 0px; }

	#manage_customer { margin-top: 61px; padding: 10px; }
	#manage_customer .search_wrap { padding: 10px 0px; }
	#manage_customer .search_wrap .table { display: table; width: 100%; }
	#manage_customer .search_wrap ul li { display: table-cell; vertical-align: middle; line-height: 30px; }
	#manage_customer .search_wrap ul li:first-child { text-align: center; }
	#manage_customer .search_wrap ul li>div { background-color: #eee; font-weight: Bold; height: 30px; line-height: 30px; }
	#manage_customer .search_wrap ul li select { height: 26px; margin-left: 5px; }
	#manage_customer table { border-collapse: collapse; width: 100%; text-align: center; font-size: 14px; margin-bottom: 20px; }
	#manage_customer table caption { text-align: right; }
	#manage_customer table th { background-color: #eee; border: 1px solid #ccc; padding: 5px; }
	#manage_customer table td { border: 1px solid #ccc; padding: 2px 5px; }
	#manage_customer table td.lft { text-align: left; }
	#manage_customer table td.rht { text-align: right; }
</style>

<div class="bjj_top_menu">
    <div class="bjj-back-btn"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/btn_back_2.png"></a></div>
    <div class="bjj_top_title"><p>정회원 거래통계 조회</p></div>
</div>

<div id="manage_customer">
	<div class="search_wrap">
		<ul class="table">
			<li style="width:50px;">
				<div>검색</div>
			</li>
			<li style="width:150px;">
				<span>예약구분</span>
				<select name="pay_type">
					<option value="0" <?=($r_paytype == "0")? "selected" : "" ?> >전체</option>
					<option value="1" <?=($r_paytype == "1")? "selected" : "" ?> >앱예약</option>
				</select>
			</li>
			<li style="width:150px;">
				<span>가입일자</span>
				<select name="customer_year">
					<option value="">전체</option>
				<?php for($_i = DATE('Y'); $_i >= 2019; $_i--){ ?>
					<option value="<?=$_i ?>" <?=($r_customer_year == $_i)? "selected" : "" ?> ><?=$_i ?>년</option>
				<?php } ?>
				</select>
			</li>
			<li>
				<span>예약일자</span>
				<select name="payment_year">
					<option value="">전체</option>
				<?php for($_i = DATE('Y'); $_i >= 2019; $_i--){ ?>
					<option value="<?=$_i ?>" <?=($r_payment_year == $_i)? "selected" : "" ?> ><?=$_i ?>년</option>
				<?php } ?>
				</select>
			</li>
		</ul>
	</div>
	<table>
	<tr>
		<th>거래건수</th>
	<?php $total_cnt = 0; ?>
	<?php foreach($data["cnt"] AS $key => $value){ ?>
		<!--td><?=($key == 0)? $key."(2020년)" : $key?></td-->
		<td style="color: #333;"><?=$key ?><?php $total_cnt += $key*$value ?></td>
	<?php }?>
	</tr>
	<tr>
		<th>회원수</th>
	<?php foreach($data["cnt"] AS $key => $value){ ?>
		<!--td><?=($key == 0)? $value."(".number_format($data["cnt_0_2020year"]["cnt"])."명)" : $value?></td-->
		<td style="color: #333;"><?=$value ?></td>
	<?php }?>
	</tr>
	<tr>
		<th>거래건수x회원수 (합계 : <?=$total_cnt ?>)</th>
	
	<?php foreach($data["cnt"] AS $key => $value){ ?>
		<!--td><?=($key == 0)? $value."(".number_format($data["cnt_0_2020year"]["cnt"])."명)" : $value?></td-->
		<td style="color: #000; background-color: #f9f9f9;"><?=$key*$value ?></td>
	<?php }?>
	</tr>
	</table>

	<table>
		<caption>
			<?="재이용율: ".round(($customer_cnt - $data["cnt"][1] - $data["cnt"][0]) / ($customer_cnt - $data["cnt"][0]) * 100, 1)."% / 이용율 : ".round(($customer_cnt - $data["cnt"][0]) / $customer_cnt * 100, 1)."% / 총 정회원 수 : ".number_format($customer_cnt)."명" ?>
		</caption>
		<colgroup>
			<?php foreach($table_row_arr AS $key2 => $value2){ ?>
				<col width="<?=100/6 ?>%" />
			<?php } ?>
		</colgroup>
		<thead>
			<tr>
			<?php foreach($table_row_arr AS $key2 => $value2){ ?>
				<th><?= $value2 ?></th>
			<?php } ?>
			</tr>
		</thead>
		<tbody>
		<?php 
			$tmp_cnt = 1;
			foreach($data["customer"] AS $key => $value){ 
		?>
			<tr>
			<?php 
				foreach($table_row_arr AS $key2 => $value2){ 
					$tmp_val = $value[$key2];
					if($key2 == "cellphone"){
						$tmp_val = ($tmp_val != "")? $tmp_val : $crypto->decode($value["cellphone2"], $access_key, $secret_key);
			?>
				<td><?= $tmp_val ?></td>
			<?php
					}else if($key2 == "shop_list"){
						$tmp_val = str_replace(",", "<br/>", $tmp_val);
			?>
				<td style="font-size: 12px;"><?= $tmp_val ?></td>
			<?php
					}else if($key2 == "payment_cnt"){
						if($tmp_cnt == 1){
							$tmp_cnt = $data["cnt"][$tmp_val];
			?>
				<td rowspan="<?=$data["cnt"][$tmp_val]?>"><?= $tmp_val."(".$tmp_cnt.")" ?></td>
			<?php 
						}else{
							$tmp_cnt--;
						}
					}else{
			?>
				<td><?= $tmp_val ?></td>
			<?php
					}
				}
			?>
			</tr>
		<?php 
			} 
		?>
		</tbody>
	</table>
</div>
<script>
	var pt = "<?=$r_pettype ?>";
	var cyr = "<?=$r_customer_year ?>";
	var pyr = "<?=$r_payment_year ?>";

	$(document).on("change", ".search_wrap select", function(){
		pt = $("select[name='pay_type'] option:selected").val();
		cyr = $("select[name='customer_year'] option:selected").val();
		pyr = $("select[name='payment_year'] option:selected").val();
		pt = (pt != "")? "pt="+pt : "";
		cyr = (cyr != "")? "&cyr="+cyr : "";
		pyr = (pyr != "")? "&pyr="+pyr : "";
		location.href = "?"+pt+cyr+pyr;
	});
</script>