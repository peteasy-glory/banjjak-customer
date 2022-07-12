<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login.php");

	$data = array();
	$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
	
	$r_mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";

	if($r_mode){
		if($r_mode == "get_main_banner"){
			$r_mb_seq = ($_POST["mb_seq"] && $_POST["mb_seq"] != "")? $_POST["mb_seq"] : "";
			$r_tab = ($_POST["tab"] && $_POST["tab"] != "")? $_POST["tab"] : "";
			$r_order = ($_POST["order"] && $_POST["order"] != "")? $_POST["order"] : "";
			$r_limit_0 = ($_POST["limit_0"] && $_POST["limit_0"] != "")? $_POST["limit_0"] : "0";
			$r_limit_1 = ($_POST["limit_1"] && $_POST["limit_1"] != "")? $_POST["limit_1"] : "50";
			$where_qy = "";
			$order_qy = "";
			$limit_qy = "";
			
			if($r_limit_0 != "" && $r_limit_1 != ""){
				$limit_qy = " LIMIT ".$r_limit_0.", ".$r_limit_1." ";
			}

			if($r_mb_seq != ""){
				$where_qy .= " AND mb_seq = '".$r_mb_seq."' ";
			}
			if($r_tab != ""){
				$where_qy .= " AND type REGEXP '".$r_tab."' ";
			}

			if($r_tab != "" && $r_order != ""){
				$order_qy = " ORDER BY odr_".$r_tab." ASC, reg_dt DESC ";
			}else{
				$order_qy = " ORDER BY reg_dt DESC ";
			}

			$sql = "
				SELECT *
				FROM tb_main_banner
				WHERE is_delete = '2'
					".$where_qy."
			";
			$result = mysqli_query($connection,$sql);
			$data["total_cnt"] = mysqli_num_rows($result);

			$sql = "
				SELECT *
				FROM tb_main_banner
				WHERE is_delete = '2'
					".$where_qy."
				".$order_qy."
				".$limit_qy."
			";
			$result = mysqli_query($connection,$sql);
			$data["list_cnt"] = $r_limit_0 + mysqli_num_rows($result);
			while($row = mysqli_fetch_assoc($result)){
				$data["list"][] = $row;
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "set_insert_main_banner"){
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_title = ($_POST["title"] && $_POST["title"] != "")? $_POST["title"] : "";
			$r_banner = ($_POST["banner"] && $_POST["banner"] != "")? $_POST["banner"] : "";
			$r_comment = ($_POST["comment"] && $_POST["comment"] != "")? $_POST["comment"] : "";
			$r_link = ($_POST["link"] && $_POST["link"] != "")? $_POST["link"] : "";
			$r_target = ($_POST["target"] != "")? $_POST["target"] : "";
			$r_type = ($_POST["type"] && $_POST["type"] != "")? $_POST["type"] : "";
			$r_odr_1 = ($_POST["odr_1"] && $_POST["odr_1"] != "")? $_POST["odr_1"] : "";
			$r_odr_2 = ($_POST["odr_2"] && $_POST["odr_2"] != "")? $_POST["odr_2"] : "";
			$r_odr_3 = ($_POST["odr_3"] && $_POST["odr_3"] != "")? $_POST["odr_3"] : "";
			$r_odr_4 = ($_POST["odr_4"] && $_POST["odr_4"] != "")? $_POST["odr_4"] : "";
			$r_is_use = ($_POST["is_use"] && $_POST["is_use"] != "")? $_POST["is_use"] : "";
			$r_is_use_time = ($_POST["is_use_time"] && $_POST["is_use_time"] != "")? $_POST["is_use_time"] : "";
			$r_start_date = ($_POST["start_date"] && $_POST["start_date"] != "")? $_POST["start_date"] : "";
			$r_start_time = ($_POST["start_time"] && $_POST["start_time"] != "")? $_POST["start_time"] : "";
			$r_end_date = ($_POST["end_date"] && $_POST["end_date"] != "")? $_POST["end_date"] : "";
			$r_end_time = ($_POST["end_time"] && $_POST["end_time"] != "")? $_POST["end_time"] : "";
			$insert_1_qy = "";
			$insert_2_qy = "";

			if($r_customer_id != ""){
				$insert_1_qy .= " `customer_id`,";
				$insert_2_qy .= " '".$r_customer_id."',";
			}
			if($r_title != ""){
				$insert_1_qy .= " `title`,";
				$insert_2_qy .= " '".addslashes($r_title)."',";
			}
			if($r_banner != ""){
				$insert_1_qy .= " `banner`,";
				$insert_2_qy .= " '".$r_banner."',";
			}
			if($r_comment != ""){
				$insert_1_qy .= " `comment`,";
				$insert_2_qy .= " '".addslashes($r_comment)."',";
			}
			if($r_link != ""){
				$insert_1_qy .= " `link`,";
				$insert_2_qy .= " '".addslashes($r_link)."',";
			}
			if($r_target != ""){
				$insert_1_qy .= " `target`,";
				$insert_2_qy .= " '".$r_target."',";
			}
			if($r_odr_1 != ""){
				$insert_1_qy .= " `odr_1`,";
				$insert_2_qy .= " '".$r_odr_1."',";
			}
			if($r_odr_2 != ""){
				$insert_1_qy .= " `odr_2`,";
				$insert_2_qy .= " '".$r_odr_2."',";
			}
			if($r_odr_3 != ""){
				$insert_1_qy .= " `odr_3`,";
				$insert_2_qy .= " '".$r_odr_3."',";
			}
			if($r_odr_4 != ""){
				$insert_1_qy .= " `odr_4`,";
				$insert_2_qy .= " '".$r_odr_4."',";
			}
			if($r_type != ""){
				$insert_1_qy .= " `type`,";
				$insert_2_qy .= " '".implode(',', $r_type)."',";
			}
			if($r_is_use != ""){
				$insert_1_qy .= " `is_use`,";
				$insert_2_qy .= " '".$r_is_use."',";
			}
			if($r_is_use_time != ""){
				$insert_1_qy .= " `is_use_time`,";
				$insert_2_qy .= " '".$r_is_use_time."',";
			}
			if($r_start_date != "" && $r_start_time != ""){
				$insert_1_qy .= " `start_dt`,";
				$insert_2_qy .= " '".$r_start_date." ".$r_start_time."',";
			}
			if($r_end_date != "" && $r_end_time != ""){
				$insert_1_qy .= " `end_dt`,";
				$insert_2_qy .= " '".$r_end_date." ".$r_end_time."',";
			}
	
			if($insert_1_qy != "" && $insert_2_qy != ""){
				$sql = "
					INSERT INTO tb_main_banner (
						".$insert_1_qy." `reg_dt`
					) VALUES (
						".$insert_2_qy." NOW()
					)
				";
				$result = mysqli_query($connection,$sql);
				if($result){
					$return_data = array("code" => "000000", "data" => mysqli_insert_id());
				}else{
					$return_data = array("code" => "000002", "data" => "배너 생성에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_update_main_banner"){
			$r_mb_seq = ($_POST["mb_seq"] && $_POST["mb_seq"] != "")? $_POST["mb_seq"] : "";
			$r_title = ($_POST["title"] && $_POST["title"] != "")? $_POST["title"] : "";
			$r_banner = ($_POST["banner"] && $_POST["banner"] != "")? $_POST["banner"] : "";
			$r_comment = ($_POST["comment"] && $_POST["comment"] != "")? $_POST["comment"] : "";
			$r_link = ($_POST["link"] && $_POST["link"] != "")? $_POST["link"] : "";
			$r_target = ($_POST["target"] != "")? $_POST["target"] : "";
			$r_type = ($_POST["type"] != "")? implode(',', $_POST["type"]) : "";
			$r_odr_1 = ($_POST["odr_1"] && $_POST["odr_1"] != "")? $_POST["odr_1"] : "";
			$r_odr_2 = ($_POST["odr_2"] && $_POST["odr_2"] != "")? $_POST["odr_2"] : "";
			$r_odr_3 = ($_POST["odr_3"] && $_POST["odr_3"] != "")? $_POST["odr_3"] : "";
			$r_odr_4 = ($_POST["odr_4"] && $_POST["odr_4"] != "")? $_POST["odr_4"] : "";
			$r_odr_5 = ($_POST["odr_5"] && $_POST["odr_5"] != "")? $_POST["odr_5"] : "";
			$r_is_use = ($_POST["is_use"] && $_POST["is_use"] != "")? $_POST["is_use"] : "";
			$r_is_use_time = ($_POST["is_use_time"] && $_POST["is_use_time"] != "")? $_POST["is_use_time"] : "";
			$r_start_date = ($_POST["start_date"] && $_POST["start_date"] != "")? $_POST["start_date"] : "";
			$r_start_time = ($_POST["start_time"] && $_POST["start_time"] != "")? $_POST["start_time"] : "";
			$r_end_date = ($_POST["end_date"] && $_POST["end_date"] != "")? $_POST["end_date"] : "";
			$r_end_time = ($_POST["end_time"] && $_POST["end_time"] != "")? $_POST["end_time"] : "";
			$update_qy = "";

			if($r_title != ""){
				$update_qy .= " `title` = '".addslashes($r_title)."', ";
			}
			if($r_banner != ""){
				$update_qy .= " `banner` = '".$r_banner."', ";
			}
			if($r_comment != ""){
				$update_qy .= " `comment` = '".addslashes($r_comment)."', ";
			}
			if($r_link != ""){
				$update_qy .= " `link` = '".addslashes($r_link)."', ";
			}
			if($r_target != ""){
				$update_qy .= " `target` = '".$r_target."', ";
			}else{
				$update_qy .= " `target` = '', ";
			}
			if($r_type != ""){
				$update_qy .= " `type` = '".$r_type."', ";
			}
			if($r_odr_1 != ""){
				$update_qy .= " `odr_1` = '".$r_odr_1."', ";
			}
			if($r_odr_2 != ""){
				$update_qy .= " `odr_2` = '".$r_odr_2."', ";
			}
			if($r_odr_3 != ""){
				$update_qy .= " `odr_3` = '".$r_odr_3."', ";
			}
			if($r_odr_4 != ""){
				$update_qy .= " `odr_4` = '".$r_odr_4."', ";
			}
			if($r_odr_5 != ""){
				$update_qy .= " `odr_5` = '".$r_odr_5."', ";
			}
			if($r_is_use != ""){
				$update_qy .= " `is_use` = '".$r_is_use."', ";
			}
			if($r_is_use_time != ""){
				$update_qy .= " `is_use_time` = '".$r_is_use_time."', ";
			}
			if($r_start_date != "" && $r_start_time != ""){
				$update_qy .= " `start_dt` = '".$r_start_date." ".$r_start_time."', ";
			}
			if($r_end_date != "" && $r_end_time != ""){
				$update_qy .= " `end_dt` = '".$r_end_date." ".$r_end_time."', ";
			}			

			if($r_mb_seq != "" && $update_qy != ""){
				$sql = "
					UPDATE tb_main_banner SET
						".$update_qy."
						update_dt = NOW()
					WHERE is_delete = '2'
						AND mb_seq = '".$r_mb_seq."'
				";
				$result = mysqli_query($connection,$sql);
				if($result){
					$return_data = array("code" => "000000", "data" => "OK");	
				}else{
					$return_data = array("code" => "000002", "data" => "배너 수정에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_delete_main_banner"){
			$r_mb_seq = ($_POST["mb_seq"] && $_POST["mb_seq"] != "")? $_POST["mb_seq"] : "";
			$r_delete_id = ($_POST["delete_id"] && $_POST["delete_id"] != "")? $_POST["delete_id"] : "";
			$r_delete_msg = ($_POST["delete_msg"] && $_POST["delete_msg"] != "")? $_POST["delete_msg"] : "";

			if($r_mb_seq != ""){
				$sql = "
					UPDATE tb_main_banner SET
						is_delete = '1',
						delete_msg = '".addslashes($r_delete_id)."|".addslashes($r_delete_msg)."',
						update_dt = NOW()
					WHERE is_delete = '2'
						AND mb_seq = '".$r_mb_seq."'
				";
				$result = mysqli_query($connection,$sql);
				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "000002", "data" => "배너 삭제에 실패했습니다.".$sql);
				}
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "get_main_banner_shop_list_cnt"){ // cnt
			$sql = "
				SELECT *
				FROM tb_shop
				ORDER BY name ASC
			";
			$result = mysqli_query($connection,$sql);
			$cnt = mysqli_num_rows($result);
			$return_data = array("code" => "000000", "data" => $cnt);
		}else if($r_mode == "get_main_banner_shop_list"){
			$r_limit_0 = ($_POST["limit_0"] && $_POST["limit_0"] != "")? $_POST["limit_0"] : "0";
			$r_limit_1 = ($_POST["limit_1"] && $_POST["limit_1"] != "")? $_POST["limit_1"] : "100";
			$limit_qy = "";

			if($r_limit_0 != "" && $r_limit_1 != ""){
				$limit_qy = " LIMIT ".$r_limit_0.", ".$r_limit_1." ";
			}
			$sql = "
				SELECT *
				FROM tb_shop
				ORDER BY name ASC
				".$limit_qy."
			";
			$result = mysqli_query($connection,$sql);
			while($row = mysqli_fetch_assoc($result)){
				$data[] = $row;
			}
			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "set_update_shop"){
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_column_name = ($_POST["column_name"] && $_POST["column_name"] != "")? $_POST["column_name"] : "";
			$r_column_value = ($_POST["column_value"] != "")? $_POST["column_value"] : "";
			$column_list = ["is_recommend", "is_mainshop_recommend", "is_mainshop_new"];
			$update_qy = "";

			if($r_customer_id != "" && $r_column_name != "" && $r_column_value != ""){
				if(in_array($r_column_name, $column_list)){
					$sql = "
						UPDATE tb_shop SET
							`".$r_column_name."` = '".$r_column_value."'
						WHERE customer_id = '".$r_customer_id."'
					";
					$result = mysqli_query($connection,$sql);
					if($result){
						$return_data = array("code" => "000000", "data" => "OK");
					}else{
						$return_data = array("code" => "000003", "data" => "펫샵 데이터 변경에 실패했습니다.");
					}
				}else{
					$return_data = array("code" => "000002", "data" => "잘못된 데이터 접근입니다.");
				}
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