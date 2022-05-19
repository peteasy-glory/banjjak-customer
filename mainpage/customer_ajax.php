<?php
	include "../include/configure.php";
	include "../include/Crypto.class.php";
	include "../include/session.php";
	include "../include/db_connection.php";
	include "../include/php_function.php";

	$data = array();
	$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
	$r_mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";

	if($r_mode){
		if($r_mode == "get_customer_addr_list"){
			$r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";

			$sql = "
				SELECT *
				FROM tb_customer_addr
				WHERE is_delete != '2'
					AND customer_id = '".$r_customer_id."'
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data[] = $row;
			}
			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_customer_addr"){
			$r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_ca_seq		   = ($_POST["ca_seq"] && $_POST["ca_seq"] != "")? $_POST["ca_seq"] : "";

			$sql = "
				SELECT *
				FROM tb_customer_addr
				WHERE is_delete != '2'
					AND customer_id = '".$r_customer_id."'
					AND ca_seq = '".$r_ca_seq."'
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data[] = $row;
			}
			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "set_insert_customer_addr"){
			$r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_addr_name	   = ($_POST["addr_name"] && $_POST["addr_name"] != "")? $_POST["addr_name"] : "";
			$r_road_addr	   = ($_POST["road_addr"] && $_POST["road_addr"] != "")? $_POST["road_addr"] : "";
			$r_jibun_addr	   = ($_POST["jibun_addr"] && $_POST["jibun_addr"] != "")? $_POST["jibun_addr"] : "";
			$r_extra_addr	   = ($_POST["extra_addr"] && $_POST["extra_addr"] != "")? $_POST["extra_addr"] : "";
			$r_detail_addr	   = ($_POST["detail_addr"] && $_POST["detail_addr"] != "")? $_POST["detail_addr"] : "";
			$r_zipcode		   = ($_POST["zipcode"] && $_POST["zipcode"] != "")? $_POST["zipcode"] : "1";

			$sql = "
				INSERT INTO tb_customer_addr (
					`customer_id`, `addr_name`, `zipcode`, `road_addr`, `jibun_addr`, 
					`extra_addr`, `detail_addr`, reg_dt
				) VALUES (
					'".$r_customer_id."', '".addslashes($r_addr_name)."', '".$r_zipcode."', '".addslashes($r_road_addr)."', '".addslashes($r_jibun_addr)."', 
					'".addslashes($r_extra_addr)."', '".addslashes($r_detail_addr)."', NOW()
				)
			";
			$result = mysql_query($sql);

			if($result){
				$customer_addr_seq = mysql_insert_id();
				$return_data = array("code" => "000000", "data" => $customer_addr_seq);
			}else{
				$return_data = array("code" => "020301", "data" => "배송지 생성에 실패했습니다.");
			}
		}else if($r_mode == "set_update_customer_addr"){
			$r_ca_seq		   = ($_POST["ca_seq"] && $_POST["ca_seq"] != "")? $_POST["ca_seq"] : "";
			$r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_addr_name	   = ($_POST["addr_name"] && $_POST["addr_name"] != "")? $_POST["addr_name"] : "";
			$r_road_addr	   = ($_POST["road_addr"] && $_POST["road_addr"] != "")? $_POST["road_addr"] : "";
			$r_jibun_addr	   = ($_POST["jibun_addr"] && $_POST["jibun_addr"] != "")? $_POST["jibun_addr"] : "";
			$r_extra_addr	   = ($_POST["extra_addr"] && $_POST["extra_addr"] != "")? $_POST["extra_addr"] : "";
			$r_detail_addr	   = ($_POST["detail_addr"] && $_POST["detail_addr"] != "")? $_POST["detail_addr"] : "";
			$r_zipcode		   = ($_POST["zipcode"] && $_POST["zipcode"] != "")? $_POST["zipcode"] : "1";

			$sql = "
				UPDATE tb_customer_addr SET
					`addr_name` = '".$r_addr_name."', 
					`road_addr` = '".$r_road_addr."', 
					`jibun_addr` = '".$r_jibun_addr."',
					`extra_addr` = '".$r_extra_addr."',
					`detail_addr` = '".$r_detail_addr."',
					`zipcode` = '".$r_zipcode."',
					`update_dt` = NOW()
				WHERE ca_seq = '".$r_ca_seq."'
					AND `customer_id` = '".$r_customer_id."'
			";
			$result = mysql_query($sql);

			if($result){
				$item_option_seq = mysql_insert_id();
				$return_data = array("code" => "000000", "data" => $item_option_seq);
			}else{
				$return_data = array("code" => "020401", "data" => "배송지 변경에 실패했습니다.");
			}		
		}else if($r_mode == "set_delete_customer_addr"){
			$r_ca_seq		   = ($_POST["ca_seq"] && $_POST["ca_seq"] != "")? $_POST["ca_seq"] : "";
			$r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_delete_txt	   = ($_POST["delete_txt"] && $_POST["delete_txt"] != "")? $_POST["delete_txt"] : "";
			
			$sql = "
				UPDATE tb_customer_addr SET
					`is_delete` = '2', 
					`delete_txt` = '".$r_customer_id."|".$r_delete_txt."', 
					`delete_dt` = NOW()
				WHERE ca_seq = '".$r_ca_seq."'
					AND `customer_id` = '".$r_customer_id."'
			";
			$result = mysql_query($sql);

			if($result){
				$return_data = array("code" => "000000", "data" => "OK");
			}else{
				$return_data = array("code" => "020501", "data" => "배송지 삭제에 실패했습니다.");
			}
		}else if($r_mode == "get_customer_point"){
			include "../include/Point.class.php";
			$r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";

			if($r_customer_id != ""){
				$point = new Point;
				$result = $point->select_point($r_customer_id);
				if ($result == true) {
					$data = $point->get_point();
				} else {
					$data = "0";
				}
				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "020601", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_insert_shop_entry"){
			$r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_name			   = ($_POST["name"] && $_POST["name"] != "")? $_POST["name"] : "";
			$r_brand		   = ($_POST["brand"] && $_POST["brand"] != "")? $_POST["brand"] : "";
			$r_cellphone	   = ($_POST["cellphone"] && $_POST["cellphone"] != "")? $_POST["cellphone"] : "";
			$r_email		   = ($_POST["email"] && $_POST["email"] != "")? $_POST["email"] : "";
			$r_entry_type	   = ($_POST["entry_type"] && $_POST["entry_type"] != "")? $_POST["entry_type"] : "";
			$r_comment		   = ($_POST["comment"] && $_POST["comment"] != "")? $_POST["comment"] : "";
			
			if($r_customer_id != ""){
				$sql = "
					INSERT INTO tb_item_shop_entry (
						`customer_id`, `name`, `brand`, `cellphone`, `email`, 
						`entry_type`, `comment`
					) VALUES (
						'".$r_customer_id."', '".addslashes($r_name)."', '".addslashes($r_brand)."', '".str_replace('-', '', $r_cellphone)."', '".$r_email."', 
						'".implode(',', $r_entry_type)."', '".addslashes($r_comment)."'
					)
				";
				$result = mysql_query($sql);
				if($result){
					$return_data = array("code" => "000000", "data" => $data);
				}else{
					$return_data = array("code" => "020702", "data" => "상품입점/제휴문의 등록에 실패했습니다.".$sql);
				}
			}else{
				$return_data = array("code" => "020701", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "get_shop_entry"){
			$r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_flag	   = ($_POST["flag"] && $_POST["flag"] != "")? $_POST["flag"] : "0";
			$r_cnt	   = ($_POST["cnt"] && $_POST["cnt"] != "")? $_POST["cnt"] : "10";
			$where_qy = "";

			if($r_customer_id != ""){
				$where_qy .= " AND customer_id = '".$r_customer_id."' ";
			}

			//if($where_qy != ""){
				$sql = "
					SELECT *
					FROM tb_item_shop_entry
					WHERE is_delete = '2'
						".$where_qy."
				";
				$result = mysql_query($sql);
				$data["total_cnt"] = mysql_num_rows($result);

				$sql = "
					SELECT *
					FROM tb_item_shop_entry
					WHERE is_delete = '2'
						".$where_qy."
					ORDER BY reg_dt DESC
					LIMIT ".$r_flag.", ".$r_cnt."
				";
				$result = mysql_query($sql);
				$data["list_cnt"] = $r_flag + mysql_num_rows($result);
				while($row = mysql_fetch_assoc($result)){
					$data["list"][] = $row;
				}

				$return_data = array("code" => "000000", "data" => $data);
			//}else{
			//	$return_data = array("code" => "020801", "data" => "중요 데이터가 누락되었습니다.");
			//}
		}else{
			$return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
		}
	}else{
		$return_data = array("code" => "999998", "data" => "올바르지 않은 접근입니다.");
	}

	echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>