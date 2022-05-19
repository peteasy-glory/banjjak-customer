<?php
    include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

	$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
	
	$r_mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";
	$data = array();

	if($r_mode){
		if($r_mode == "get_is_counsel"){
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$approval_flag = '';

			$sql = "
				SELECT update_time, approval
				FROM tb_payment_log
				WHERE customer_id = '".$r_customer_id."'
					AND artist_id = '".$r_artist_id."'
				GROUP BY approval
			";
			$result = mysqli_query($connection, $sql);
			$cnt = mysqli_num_rows($result);
			if($cnt > 0){
				while($row = mysqli_fetch_assoc($result)){
					$approval = $row["approval"];
					$hours_time = strtotime("-12 hours");
					$update_time = strtotime($row["update_time"]);

					if ($approval != '1') {
						if ($approval == '0') {
							if ($update_time > $hours_time) {
								$approval_flag = 0;
							} else {
								$approval_flag = 3;
							}
						} else if ($approval == '2') {
							$approval_flag = 2;
						} else if ($approval == '3') {
							$approval_flag = 3;
						}
					}else{
						$approval_flag = 1;
					}
				}
			}else{
				$approval_flag = 'none'; // 첫미용상담
			}

			$return_data = array("code" => "000000", "data" => $approval_flag);
		}else if($r_mode == "get_is_offline"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$is_out = 0;

			$sql = "
				SELECT * 
				FROM tb_product_dog_static
				WHERE customer_id = '".$r_artist_id."'
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				if($row["out_shop_product"] > 0){
					$is_out = 1;
				}
			}

			$sql = "
				SELECT * 
				FROM tb_product_cat
				WHERE customer_id = '".$r_artist_id."'
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				if($row["out_shop_product"] > 0){
					$is_out = 1;
				}
			}
		
			if (!$is_out) {
				$_SESSION['gobeauty_go_offline'] = 'yes';
			}

			$return_data = array("code" => "000000", "data" => $is_out);
		}else if($r_mode == "get_is_offline_shop"){ // 오프라인 매장 여부
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			
			$crypto = new Crypto();
			$enc_artist_id = $crypto->encode(trim($r_artist_id), $access_key, $secret_key);
			
			$sql = "
				SELECT * 
				FROM tb_request_artist 
				WHERE customer_id = '".$enc_artist_id."'
			";
			$result = mysql_query($sql);
			$row = mysql_fetch_assoc($result);

			$return_data = array("code" => "000000", "data" => $row["is_got_offline_shop"]);
		}else if($r_mode == "get_customer"){ // 회원 데이터
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";

			$sql = "
				SELECT *
				FROM tb_customer
				WHERE id = '".$r_customer_id."'
			";
			$result = mysql_query($sql);
			$data = mysql_fetch_assoc($result);

			$crypto = new Crypto();
			$data["enc_cellphone"] = $crypto->decode(trim($data["cellphone"]), $access_key, $secret_key);
			$_SESSION['gobeauty_cellphone'] = $data["enc_cellphone"];
			
			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_shop"){ // 상점 데이터
			//$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : ""; // 추후 artist_id 로 변경해야 됨
		
			if($r_artist_id != ""){
				$sql = "
					SELECT *, sh.photo as photo2
					FROM tb_shop as sh
						INNER JOIN tb_customer AS cu ON sh.customer_id = cu.id
					WHERE customer_id = '".addslashes($r_artist_id)."'
				";
				$result = mysqli_query($connection, $sql);
				$data = mysqli_fetch_assoc($result);

				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다1.");
			}
		}else if($r_mode == "get_top_region"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";

			if($r_artist_id != ""){
				$sql = "
					SELECT DISTINCT r.top 
					FROM tb_working_region AS wr
						INNER JOIN tb_region AS r ON wr.region_id = r.id
					WHERE wr.customer_id = '".$r_artist_id."'
				";
				$result = mysql_query($sql);
				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}

				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다2.");
			}
		}else if($r_mode == "get_middle_region"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_top_region = ($_POST["top_region"] && $_POST["top_region"] != "")? $_POST["top_region"] : "";

			if($r_artist_id != ""){
				$sql = "
					SELECT DISTINCT r.middle 
					FROM tb_working_region AS wr
						INNER JOIN tb_region AS r ON wr.region_id = r.id
					WHERE wr.customer_id = '".$r_artist_id."'
						AND r.top = '".$r_top_region."'
				";
				$result = mysql_query($sql);
				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}

				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다3.");
			}
		}else if($r_mode == "get_bottom_region"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_top_region = ($_POST["top_region"] && $_POST["top_region"] != "")? $_POST["top_region"] : "";
			$r_middle_region = ($_POST["middle_region"] && $_POST["middle_region"] != "")? $_POST["middle_region"] : "";

			if($r_artist_id != ""){
				$sql = "
					SELECT DISTINCT r.bottom, wr.price 
					FROM tb_working_region AS wr
						INNER JOIN tb_region AS r ON wr.region_id = r.id
					WHERE wr.customer_id = '".$r_artist_id."'
						AND r.top = '".$r_top_region."'
						AND r.middle = '".$r_middle_region."'
				";
				$result = mysql_query($sql);
				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}

				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다4.");
			}

		}else if($r_mode == "get_mypet"){
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_pet_seq = ($_POST["pet_seq"] && $_POST["pet_seq"] != "")? $_POST["pet_seq"] : "";
			$where_qy = "";

			if($r_customer_id != ""){
				$where_qy = " AND customer_id = '".$r_customer_id."' ";
			}

			if($r_pet_seq != ""){
				$where_qy = " AND pet_seq = '".$r_pet_seq."' ";
			}

			if($r_customer_id != "" || $r_pet_seq != ""){
				$sql = "
					SELECT *
					FROM tb_mypet
					WHERE 1=1 
						".$where_qy."
				";
				$result = mysql_query($sql);
				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}

				$return_data = array("code" => "000000", "data" => $data);
			}else if($r_customer_id == "" && $r_pet_seq == "0"){
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다5.");
			}
		}else if($r_mode == "set_select_session"){
			$r_pet_seq = ($_POST["pet_seq"] != "")? $_POST["pet_seq"] : "";
			$r_action = ($_POST["action"] && $_POST["action"] != "")? $_POST["action"] : "";

			$_SESSION['gobeauty_product_selected_pet'] = str_replace("\"", "", json_encode($r_pet_seq));
			if($r_action == 'reset'){
				$_SESSION['gobeauty_product_selected_pet_info'] = '';
				$_SESSION['gobeauty_cart_year'] = '';
				$_SESSION['gobeauty_cart_month'] = '';
				$_SESSION['gobeauty_cart_day'] = '';
				$_SESSION['gobeauty_cart_hour'] = '';
				$_SESSION['gobeauty_cart_minute'] = '';
				$_SESSION['gobeauty_cart_to_hour'] = '';
				$_SESSION['gobeauty_cart_to_minute'] = '';
				$_SESSION['gobeauty_cart_region_top'] = '';
				$_SESSION['gobeauty_cart_region_middle'] = '';
				$_SESSION['gobeauty_cart_region_bottom'] = '';
				$_SESSION['gobeauty_cart_per_diem_price'] = '';
			}

			$return_data = array("code" => "000000", "data" => "OK");
		}else if($r_mode == "set_region_session"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_top_region = ($_POST["top_region"] && $_POST["top_region"] != "")? $_POST["top_region"] : "";
			$r_middle_region = ($_POST["middle_region"] && $_POST["middle_region"] != "")? $_POST["middle_region"] : "";
			$r_bottom_region = ($_POST["bottom_region"] && $_POST["bottom_region"] != "")? $_POST["bottom_region"] : "";
			$r_price = ($_POST["price"] && $_POST["price"] != "")? $_POST["price"] : "";

			unset($_SESSION['gobeauty_go_offline']);
			$_SESSION['gobeauty_cart_region_top'] = $r_top_region;
			$_SESSION['gobeauty_cart_region_middle'] = $r_middle_region;
			$_SESSION['gobeauty_cart_region_bottom'] = $r_bottom_region;
			$_SESSION['gobeauty_cart_per_diem_price'] = $r_price;

			$return_data = array("code" => "000000", "data" => "OK");
		}else if($r_mode == "set_offline_session"){
			$r_offline = ($_POST["offline"] && $_POST["offline"] != "")? $_POST["offline"] : "";

			if($r_offline == "1"){
				$_SESSION['gobeauty_go_offline'] = 'yes';
				unset($_SESSION['gobeauty_cart_per_diem_price']);
				unset($_SESSION['gobeauty_cart_region_bottom']);
				unset($_SESSION['gobeauty_cart_region_middle']);
				unset($_SESSION['gobeauty_cart_region_top']);
				unset($_SESSION['gobeauty_cart_product']);
			}else{
				unset($_SESSION['gobeauty_go_offline']);
			}

			$return_data = array("code" => "000000", "data" => "OK");
		}else if($r_mode == "set_init_reservation_session"){
			$_SESSION['gobeauty_cart_year'] = '';
			$_SESSION['gobeauty_cart_month'] = '';
			$_SESSION['gobeauty_cart_day'] = '';
			$_SESSION['gobeauty_cart_hour'] = '';
			$_SESSION['gobeauty_cart_minute'] = '';
			$_SESSION['gobeauty_cart_to_hour'] = '';
			$_SESSION['gobeauty_cart_to_minute'] = '';

			$return_data = array("code" => "000000", "data" => "OK");
		}else if($r_mode == "get_reservation"){
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_session_id = ($_POST["session_id"] && $_POST["session_id"] != "")? $_POST["session_id"] : "";

			$sql = "
				SELECT *
				FROM tb_reservation
				WHERE customer_id = '".$r_customer_id."'
					AND artist_id = '".$r_artist_id."'
					AND session_id = '".$r_session_id."'
			";
			$result = mysql_query($sql);
			$data = mysql_fetch_assoc($result);

			$return_data = array("code" => "000000", "data" => $data);
		}else{
			$return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
		}
	}else{
		$return_data = array("code" => "999998", "data" => "올바르지 않은 접근입니다.");
	}

	echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>