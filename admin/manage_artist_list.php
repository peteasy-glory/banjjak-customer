<?php
include "../include/top.php";
include "../include/Crypto.class.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

$r_shop_name = ($_GET["shop_name"] && $_GET["shop_name"] != "")? $_GET["shop_name"] : "";
$data = array();
$crypto = new Crypto();
$where_qy = '';

if($r_shop_name != ""){
	$where_qy .= " AND (name like '%".$r_shop_name."%' OR customer_id like '%".$r_shop_name."%') ";
}

$sql = "
	SELECT *
	FROM tb_shop
	WHERE 1=1
		".$where_qy."
	ORDER BY update_time DESC
";
$result = mysql_query($sql);
while($row = mysql_fetch_assoc($result)){
	$data[$row["customer_id"]] = $row;
}

foreach($data AS $key => $value){
	$enc_artist_id = $crypto->encode(trim($value["customer_id"]), $access_key, $secret_key);
	$sql = "
		SELECT *
		FROM tb_request_artist
		WHERE customer_id = '".$enc_artist_id."'
	";
	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result)){
		$data[$value["customer_id"]]["request"]["cellphone"] = $crypto->decode(trim($row['cellphone']), $access_key, $secret_key);
		$data[$value["customer_id"]]["request"]["business_number"] = $crypto->decode(trim($row['business_number']), $access_key, $secret_key);
		$data[$value["customer_id"]]["request"]["region"] = $crypto->decode(trim($row['region']), $access_key, $secret_key);
		$data[$value["customer_id"]]["request"]["offline_shop_name"] = $crypto->decode(trim($row['offline_shop_name']), $access_key, $secret_key);
		$data[$value["customer_id"]]["request"]["offline_shop_phonenumber"] = $crypto->decode(trim($row['offline_shop_phonenumber']), $access_key, $secret_key);
		$data[$value["customer_id"]]["request"]["offline_shop_address"] = $crypto->decode(trim($row['offline_shop_address']), $access_key, $secret_key);
		$data[$value["customer_id"]]["request"]["working_years"] = $row['working_years'];
		$data[$value["customer_id"]]["request"]["enter_path"] = $row['enter_path'];
		$data[$value["customer_id"]]["request"]["choice_service"] = $row['choice_service'];
		$data[$value["customer_id"]]["request"]["is_got_offline_shop"] = $row['is_got_offline_shop'];
		$data[$value["customer_id"]]["request"]["is_personal"] = $row['is_personal'];
		$data[$value["customer_id"]]["request"]["is_business"] = $row['is_business'];
		$data[$value["customer_id"]]["request"]["lat"] = $row['lat'];
		$data[$value["customer_id"]]["request"]["lng"] = $row['lng'];
	}

	$sql = "
		SELECT *
		FROM tb_artist_payment_info
		WHERE customer_id = '".$value["customer_id"]."'
	";
	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result)){
		$data[$value["customer_id"]]["payment"]["bankname"] = $crypto->decode(trim($row['bankname']), $access_key, $secret_key);
		$data[$value["customer_id"]]["payment"]["account_holder"] = $crypto->decode(trim($row['account_holder']), $access_key, $secret_key);
		$data[$value["customer_id"]]["payment"]["bank_account"] = $crypto->decode(trim($row['bank_account']), $access_key, $secret_key);
	}

	$sql = "
		SELECT *
		FROM tb_hotel
		WHERE artist_id = '".$value["customer_id"]."'
	";
	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result)){
		$data[$value["customer_id"]]["hotel"] = $row;
	}

	$sql = "
		SELECT *
		FROM tb_playroom
		WHERE artist_id = '".$value["customer_id"]."'
	";
	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result)){
		$data[$value["customer_id"]]["playroom"] = $row;
	}
}

?>
<script src="<?= $js_directory ?>/fontawesome.min.js"></script>
<style>
	a { color: #c18b0a; text-decoration: none; }

	.bjj_top_menu { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; border-bottom: 1px solid #ccc; background-color: rgba(255, 255, 255, 0.8); margin-top: env(safe-area-inset-top); margin-top: constant(safe-area-inset-top); z-index: 10; }
	.bjj_top_menu .bjj-back-btn { position: absolute; left: 8px; top: 12px; }
	.bjj_top_menu .bjj-back-btn img { width: 26px; vertical-align: middle; }
	.bjj_top_menu .bjj_top_title { width: 100%; height: 50px; line-height: 50px; text-align: center; padding: 0px; }
	.bjj_top_menu .bjj_top_title p { padding: 0px; margin: 0px; }

	#manage_artist_list { position: relative; width: 100%; margin-top: calc(env(safe-area-inset-top) + 61px); margin-top: calc(constant(safe-area-inset-top) + 61px); padding-bottom: 60px; box-sizing: border-box; }
	#manage_artist_list .search_wrap { padding: 10px 0px; }
	#manage_artist_list .search_wrap input[type='text'] { border: 1px solid #ccc; height: 30px; padding: 0px 10px; background-color: #fff; }
	#manage_artist_list .search_wrap button { border: 1px solid #ccc; height: 30px; padding: 0px 10px; border-radius: 5px; background-color: #eee; }
	#manage_artist_list table { position: relative; width: 100%; margin: 0px; padding: 0px; font-size: 10px; border-collapse: collapse; }
	#manage_artist_list table tr th { position: sticky; top: 50px; box-shadow: rgba(0, 0, 0, 0.4); background-color: #eee; padding: 5px; border: 1px solid #ccc; }
	#manage_artist_list table tr th .info { font-size: 8px; }
	#manage_artist_list table tr td { background-color: #f9f9f9; padding: 2px 5px; text-align: center; border: 1px solid #ccc; }
	#manage_artist_list table tr td.lft { text-align: left; }
	#manage_artist_list table tr td.rht { text-align: right; }
	#manage_artist_list table tr td span.gps { color: #999; }
	#manage_artist_list table tr td input[type='checkbox'] { display: none; }
	#manage_artist_list table tr td input[type='checkbox']+label { display: inline-block; height: 20px; line-height: 20px; white-space: nowrap; border: 1px solid #ccc; color: #333; border-radius: 5px; cursor: pointer; }
	#manage_artist_list table tr td input[type='checkbox']+label:hover { background-color: #eee; border: 1px solid #999; color: #222; }
	#manage_artist_list table tr td input[type='checkbox']:checked+label { background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
	#manage_artist_list table tr td input[type='checkbox']:checked+label:hover { background-color: #e4ae1d; border: 1px solid #e4ae1d; color: #eee; }
</style>

<div class="bjj_top_menu">
    <div class="bjj-back-btn"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/btn_back_2.png"></a></div>
    <div class="bjj_top_title"><p>펫샵 회원 조회</p></div>
</div>
<div id="manage_artist_list">
	<?php //echo"<pre>";print_r($data);echo"</pre>"; ?>
	<div class="search_wrap">
		<input type="text" class="search_shop_name" value="<?=$r_shop_name ?>" placeholder="펫샵명 또는 이메일" />
		<button type="button" class="search_btn">검색</button>
		<button type="button" class="search_reset_btn">초기화</button>
	</div>
	<table>
		<colgroup>
			<col width="12%" />
			<col width="10%" />
			<col width="8%" />
			<col width="*" />
			<col width="7%" />
			<col width="7%" />
			<col width="5%" />
			<col width="5%" />
			<col width="1%" />
			<col width="1%" />
			<col width="1%" />
		</colgroup>
		<tr>
			<th>상태<br/><span class="info">[미용]오픈/매장/사업자/유입</span></th> <!-- 미용/호텔/유치원<br/> -->
			<th>펫샵명<br/>(등록명)<br/>email</th>
			<th>연락처<br/>(등록연락처)</th>
			<th>주소<br/>지역<br/>좌표</th>
			<th>사업자번호</th>
			<th>경력</th>
			<th>서비스선택</th>
			<th colspan="3">오픈<div style="border: 1px solid #ccc; padding: 2px; "><span style="background-color:#f5bf2e; display: inline-block; width: 10px; height: 10px;"></span> 오픈 <span style="background-color:#fff; display: inline-block; width: 10px; height: 10px;"></span> 숨김<div></th>
		</tr>
	<?php 
		$cnt = 0;
		foreach($data AS $key => $value){ 
			$cellphone = ($value["request"]["cellphone"] != "")? $value["request"]["cellphone"] : "-";
			if(strlen($cellphone) == 10){
				$cellphone = substr($value["request"]["cellphone"], 0, 3)."-".substr($value["request"]["cellphone"], 3, 3)."-".substr($value["request"]["cellphone"], 6, 4);
			}else if(strlen($cellphone) == 11){
				$cellphone = substr($value["request"]["cellphone"], 0, 3)."-".substr($value["request"]["cellphone"], 3, 4)."-".substr($value["request"]["cellphone"], 7, 4);
			}
			$offline_cellphone = ($value["request"]["offline_shop_phonenumber"] != "")? $value["request"]["offline_shop_phonenumber"] : "-";
			if(strlen($offline_cellphone) == 10){
				$offline_cellphone = substr($value["request"]["offline_shop_phonenumber"], 0, 3)."-".substr($value["request"]["offline_shop_phonenumber"], 3, 3)."-".substr($value["request"]["offline_shop_phonenumber"], 6, 4);
			}else if(strlen($offline_cellphone) == 11){
				$offline_cellphone = substr($value["request"]["offline_shop_phonenumber"], 0, 3)."-".substr($value["request"]["offline_shop_phonenumber"], 3, 4)."-".substr($value["request"]["offline_shop_phonenumber"], 7, 4);
			}
			$is_enable_flag_beauty = ($value["enable_flag"] == "1")? "<span style='background-color:#0f0;'>등록</span>" : "<span style='background-color:#ccc;color:#f00;'>탈퇴</span>";
			$is_enable_flag_hotel = ($value["hotel"]["is_enable"] == "1")? "<span style='background-color:#0f0;'>등록</span>" : "<span style='background-color:#ccc;color:#f00;'>탈퇴</span>";
			$is_enable_flag_playroom = ($value["playroom"]["is_enable"] == "1")? "<span style='background-color:#0f0;'>등록</span>" : "<span style='background-color:#ccc;color:#f00;'>탈퇴</span>";
			$is_open_flag = ($value["open_flag"] == "1")? "<span style='background-color:#f5bf2e;'>오픈</span>" : "<span style='background-color:#666;color:#ccc;'>폐점</span>";
			$is_got_offline_shop = ($value["request"]["is_got_offline_shop"] == "1")? "<span style='background-color:#00f;color:#fff;'>매장</span>" : "<span style='background-color:#f00;color:#fff;'>출장</span>";
			$is_business = "";
			if($value["request"]["is_business"] == "1"){
				if($value["request"]["is_personal"] == "1"){
					$is_business = "<span style='background-color:#f9f;'>개/사</span>";
				}else{
					$is_business = "<span style='background-color:#09f;color:#fff;'>사업자</span>";
				}
			}else{
				if($value["request"]["is_personal"] == "1"){
					$is_business = "<span style='background-color:#f0f;color:#fff;'>개인</span>";
				}else{
					$is_business = "";
				}
			}
			$business_number = ($value["request"]["business_number"] != "" && strlen($value["request"]["business_number"]) == 10)? substr($value["request"]["business_number"], 0, 3)."-".substr($value["request"]["business_number"], 3, 2)."-".substr($value["request"]["business_number"], 5, 5) : $value["request"]["business_number"];
			$choice_service_arr = ($value["request"]["choice_service"] != "" && strpos($value["request"]["choice_service"], ",") !== false)? explode(",", $value["request"]["choice_service"]) : array();
			$choice_service = "";
			foreach($choice_service_arr AS $_x => $_y){
				if($_y == "1"){
					$choice_service .= "미용<br/>";
				}
				if($_y == "2"){
					$choice_service .= "호텔<br/>";
				}
				if($_y == "3"){
					$choice_service .= "유치원";
				}
			}
			//$choice_service = substr($choice_service, 0, -1);
			$enter_path = ($value["request"]["enter_path"] == "web")? "<span style='background-color:#0ff;'>WEB</span>" : "<span style='color: #999;'>Moblie</span>";
	?>
		<tr>
			<td>
				<!-- <span class="enable_flag beauty_enable_<?=$cnt ?>"><?=$is_enable_flag_beauty ?></span>/<span class="enable_flag hotel_enable_<?=$cnt ?>"><?=$is_enable_flag_hotel ?></span>/<span class="enable_flag playroom_enable_<?=$cnt ?>"><?=$is_enable_flag_playroom ?></span><br/> -->
				<?=$is_open_flag ?>/<?=$is_got_offline_shop ?>/<?=$is_business ?>/<?=$enter_path ?>
			</td>
			<td>
				<a href="<?=$artist_directory ?>/?artist_name=<?=$value["name"] ?>" target="_blank"><?=$value["name"] ?></a><br/>
				(<a href="<?=$admin_directory ?>/manage_request_artist_detail.php?artist_id=<?=$value["customer_id"] ?>"><?=$value["request"]["offline_shop_name"] ?></a>)
				<br/><?=$value["customer_id"] ?>
			</td>
			<td><?=$cellphone ?><br/>(<?=$offline_cellphone ?>)</td>
			<td><a href="<?=$shop_directory ?>/daum_map.php?artist_id=<?=$value["customer_id"] ?>"><?=$value["request"]["offline_shop_address"] ?></a><br/><?=($value["request"]["region"] != "")? $value["request"]["region"] : "<span style='color: #f00;'>●</span>" ?><br/><span class="gps"><?=$value["lat"] ?> / <?=$value["lng"] ?></span></td>
			<td><?=$business_number ?></td>
			<td><?=$value["working_years"]."년" ?></td>
			<td><?=$choice_service ?></td>
			<td><input type="checkbox" name="is_enable" id="set_update_beauty_enable_<?=$cnt ?>" value="1" data-type="beauty" data-id="<?=$key ?>" data-cnt="<?=$cnt ?>" <?=($value["enable_flag"] == "1")? "checked" : "" ?> /><label for="set_update_beauty_enable_<?=$cnt ?>">미용</label></td>
			<td><input type="checkbox" name="is_enable" id="set_update_hotel_enable_<?=$cnt ?>" value="1" data-type="hotel" data-id="<?=$key ?>" data-cnt="<?=$cnt ?>" <?=($value["hotel"]["is_enable"] == "1")? "checked" : "" ?> /><label for="set_update_hotel_enable_<?=$cnt ?>">호텔</label></td>
			<td><input type="checkbox" name="is_enable" id="set_update_playroom_enable_<?=$cnt ?>" value="1" data-type="playroom" data-id="<?=$key ?>" data-cnt="<?=$cnt ?>" <?=($value["playroom"]["is_enable"] == "1")? "checked" : "" ?> /><label for="set_update_playroom_enable_<?=$cnt ?>">유치원</label></td>
		</tr>
	<?php 
			$cnt++;
		} 
	?>
	</table>
</div>

<script>
	var $manage_artist_list = $("#manage_artist_list");

	// 검색
	$manage_artist_list.on("click", ".search_btn", function(){
		var shop_name = $manage_artist_list.find(".search_shop_name").val();
		if(shop_name != ""){
			location.href = "manage_artist_list.php?shop_name="+$manage_artist_list.find(".search_shop_name").val();
		}
	});
	$manage_artist_list.on("keyup", ".search_shop_name", function(e){
		var shop_name = $(this).val();
		if(e.keyCode == '13'){
			if(shop_name != ""){
				location.href = "manage_artist_list.php?shop_name="+$manage_artist_list.find(".search_shop_name").val();
			}
		}
	});

	// 초기화
	$manage_artist_list.on("click", ".search_reset_btn", function(){
		location.href = "manage_artist_list.php";
	});

	$manage_artist_list.on("click", "input[name='is_enable']", function(){
		var _this = $(this);
		var artist_id = _this.data("id");
		var type = _this.data("type");
		var value = 1;
		var cnt = _this.data("cnt");

		$.MessageBox({
			buttonDone: "확인",
			buttonFail: "취소",
			message: "변경 하시겠습니까?"
		}).done(function(){
			if(_this.is(":checked") == true){
				value = 1;
			}else{
				if(type == "beauty"){
					value = 0;
				}else{
					value = 2;
				}
			}

			$.ajax({
				url: 'manage_artist_ajax.php',
				data: {
					mode: "set_update_enable",
					artist_id: artist_id,
					type: type,
					value: value
				},
				type: 'POST',
				dataType: 'JSON',
				async: false,
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						if(value == 1){
							$manage_artist_list.find("."+type+"_enable_"+cnt).html("<span style='background-color:#0f0;'>등록</span>");
						}else{
							$manage_artist_list.find("."+type+"_enable_"+cnt).html("<span style='background-color:#ccc;color:#f00;'>탈퇴</span>");
						}

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
	});
</script>