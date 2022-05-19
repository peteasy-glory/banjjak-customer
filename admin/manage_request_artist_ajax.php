<?php
	include "../include/configure.php";
	include "../include/Crypto.class.php";
	include "../include/session.php";
	include "../include/db_connection.php";
	include "../include/php_function.php";

	$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
	
	$r_mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";
	$data = array();

	if($r_mode){
		if($r_mode == "get_manage_request_artist"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			
			if($r_artist_id != ""){
				$crypto = new Crypto();
				$enc_artist_id = $crypto->encode(trim($r_artist_id), $access_key, $secret_key);

				$sql = "
					SELECT *
					FROM tb_request_artist
					WHERE customer_id = '".$enc_artist_id."'
				";
				$result = mysql_query($sql);
				$cnt = mysql_num_rows($result);
				if($cnt > 0){
					$row = mysql_fetch_assoc($result);

					$data["customer_id"] = $crypto->decode(trim($row["customer_id"]), $access_key, $secret_key);
					$data["name"] = $crypto->decode(trim($row["name"]), $access_key, $secret_key);
					$data["cellphone"] = $crypto->decode(trim($row["cellphone"]), $access_key, $secret_key);
					$data["business_number"] = $crypto->decode(trim($row["business_number"]), $access_key, $secret_key);
					$data["business_license"] = $row["business_license"];
					$data["region"] = $crypto->decode(trim($row["region"]), $access_key, $secret_key);
					$data["offline_shop_name"] = $crypto->decode(trim($row["offline_shop_name"]), $access_key, $secret_key);
					$data["offline_shop_phonenumber"] = $crypto->decode(trim($row["offline_shop_phonenumber"]), $access_key, $secret_key);
					$data["offline_shop_address"] = $crypto->decode(trim($row["offline_shop_address"]), $access_key, $secret_key);
					$data["is_personal"] = $row["is_personal"];
					$data["is_business"] = $row["is_business"];
					$data["is_got_offline_shop"] = $row["is_got_offline_shop"];
					$data["update_time"] = $row["update_time"];
					$data["lat"] = $row["lat"];
					$data["lng"] = $row["lng"];
					$data["enter_path"] = $row["enter_path"];
					$data["choice_service"] = $row["choice_service"];
					$data["crypto"][] = $row;

					$return_data = array("code" => "000000", "data" => $data);
				}else{
					$return_data = array("code" => "000001", "data" => "불러오기에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "000002", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_update_manage_request_artist"){
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_name = ($_POST["name"] && $_POST["name"] != "")? $_POST["name"] : "";
			$r_cellphone = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";
			$r_is_personal = ($_POST["is_personal"] != "")? $_POST["is_personal"] : "";
			$r_is_business = ($_POST["is_business"] != "")? $_POST["is_business"] : "";
			$r_business_number = ($_POST["business_number"] && $_POST["business_number"] != "")? $_POST["business_number"] : "";
			$r_region = ($_POST["region"] && $_POST["region"] != "")? $_POST["region"] : "";
			$r_is_got_offline_shop = ($_POST["is_got_offline_shop"] != "")? $_POST["is_got_offline_shop"] : "";
			$r_offline_shop_name = ($_POST["offline_shop_name"] && $_POST["offline_shop_name"] != "")? $_POST["offline_shop_name"] : "";
			$r_offline_shop_phonenumber = ($_POST["offline_shop_phonenumber"] && $_POST["offline_shop_phonenumber"] != "")? $_POST["offline_shop_phonenumber"] : "";
			$r_offline_shop_address = ($_POST["offline_shop_address"] && $_POST["offline_shop_address"] != "")? $_POST["offline_shop_address"] : "";
			$r_offline_shop_address_detail = ($_POST["offline_shop_address_detail"] && $_POST["offline_shop_address_detail"] != "")? $_POST["offline_shop_address_detail"] : "";
			$r_lat = ($_POST["lat"] && $_POST["lat"] != "")? $_POST["lat"] : "";
			$r_lng = ($_POST["lng"] && $_POST["lng"] != "")? $_POST["lng"] : "";
			$r_choice_service = ($_POST["choice_service"] && $_POST["choice_service"] != "")? $_POST["choice_service"] : "";
			$update_qy = "";
			$where_qy = "";
			$crypto = new Crypto();

			if($r_name != ""){
				$enc_name = $crypto->encode(trim($r_name), $access_key, $secret_key);
				$update_qy .= " name = '".$enc_name."', ";
			}
			if($r_cellphone != ""){
				$enc_cellphone = $crypto->encode(trim($r_cellphone), $access_key, $secret_key);
				$update_qy .= " cellphone = '".$enc_cellphone."', ";
			}
			if($r_is_personal != ""){
				$update_qy .= " is_personal = '".$r_is_personal."', ";
			}
			if($r_is_business != ""){
				$update_qy .= " is_business = '".$r_is_business."', ";
			}
			if($r_business_number != ""){
				$enc_business_number = $crypto->encode(trim($r_business_number), $access_key, $secret_key);
				$update_qy .= " business_number = '".$enc_business_number."', ";
			}
			if($r_region != ""){
				$enc_region = $crypto->encode(trim($r_region), $access_key, $secret_key);
				$update_qy .= " region = '".$enc_region."', ";
			}
			if($r_is_got_offline_shop != ""){
				$update_qy .= " is_got_offline_shop = '".$r_is_got_offline_shop."', ";
			}
			if($r_offline_shop_name != ""){
				$enc_offline_shop_name = $crypto->encode(trim($r_offline_shop_name), $access_key, $secret_key);
				$update_qy .= " offline_shop_name = '".addslashes($enc_offline_shop_name)."', ";
			}
			if($r_offline_shop_phonenumber != ""){
				$enc_offline_shop_phonenumber = $crypto->encode(trim($r_offline_shop_phonenumber), $access_key, $secret_key);
				$update_qy .= " offline_shop_phonenumber = '".$enc_offline_shop_phonenumber."', ";
			}
			if($r_offline_shop_address != ""){
				$offline_shop_address = $r_offline_shop_address." ".$r_offline_shop_address_detail;
				$enc_offline_shop_address = $crypto->encode(trim($offline_shop_address), $access_key, $secret_key);
				$update_qy .= " offline_shop_address = '".$enc_offline_shop_address."', ";
			}
			if($r_lat != ""){
				$update_qy .= " lat = '".$r_lat."', ";
			}
			if($r_lng != ""){
				$update_qy .= " lng = '".$r_lng."', ";
			}
			if($r_choice_service != ""){
				$update_qy .= " choice_service = '".implode(",", $r_choice_service)."', ";
			}

			if($r_customer_id != ""){
				$enc_artist_id = $crypto->encode(trim($r_customer_id), $access_key, $secret_key);
				$where_qy = " AND customer_id = '".$enc_artist_id."' ";
			}

			if($r_customer_id != "" && $update_qy != "" && $where_qy != ""){
				$sql = "
					UPDATE tb_request_artist SET
						".$update_qy."
						update_time = NOW()
					WHERE 1=1 
						".$where_qy."
				";
				$result = mysql_query($sql);
				//$result = true;
				if($result){
					// lat, lng tb_shop update
					$sql = "
						UPDATE tb_shop SET
							lat = '".$r_lat."',
							lng = '".$r_lng."',
							name = '".addslashes($r_offline_shop_name)."'
						WHERE customer_id = '".$r_customer_id."'
					";
					$result = mysql_query($sql);
					if($result){
						$return_data = array("code" => "000000", "data" => "OK");
					}else{
						$return_data = array("code" => "000003", "data" => "수정에 실패했습니다.".$sql); // tb_shop
					}
				}else{
					$return_data = array("code" => "000001", "data" => "수정에 실패했습니다."); // tb_request_artist
				}
			}else{
				$return_data = array("code" => "000002", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "get_top_region"){
			$sql = "
				SELECT DISTINCT top
				FROM tb_region
				WHERE open_flag = TRUE
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data[] = $row;
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_middle_region"){
			$r_top = ($_POST["top"] && $_POST["top"] != "")? $_POST["top"] : "";
			
			if($r_top){
				$sql = "
					SELECT DISTINCT middle
					FROM tb_region
					WHERE open_flag = TRUE
						AND top = '".$r_top."'
				";
				$result = mysql_query($sql);
				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}

				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else{
			$return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
		}
	}else{
		$return_data = array("code" => "999998", "data" => "올바르지 않은 접근입니다.");
	}

	echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>