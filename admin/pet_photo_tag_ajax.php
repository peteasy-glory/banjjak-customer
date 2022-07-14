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
		if($r_mode == "get_photo_data"){
			$r_start_cnt = ($_POST["start_cnt"] && $_POST["start_cnt"] != "")? $_POST["start_cnt"] : 0;
			$r_end_cnt = ($_POST["end_cnt"] && $_POST["end_cnt"] != "")? $_POST["end_cnt"] : 20;
		
			$r_limit = ($_POST["limit"] && $_POST["limit"] == "on")? "LIMIT ".$r_start_cnt.", ".$r_end_cnt : "";

			$r_where = "";
			if($_POST["where"] && $_POST["where"] == "finish"){
				$r_where = "WHERE atm.is_work_finish = 1 AND atm.is_delete = 0 ";
			}else if($_POST["where"] && $_POST["where"] == "delete"){
				$r_where = "WHERE atm.is_delete = 1 ";
			}
			$sql = "
				SELECT atm.*, ac.name, ac.idx AS pet_idx, ak.name_kor FROM tb_ani_tag_mgr AS atm
				LEFT JOIN tb_ani_kinds AS ak ON ak.idx = atm.kind_idx
				LEFT JOIN tb_ani_class AS ac ON ac.idx = ak.class_idx
				".$r_where."
				ORDER BY atm.idx
				".$r_limit."
			";
			$result = mysql_query($sql);

			if($r_limit == ""){
				$data[] = mysql_num_rows($result);
			}else{
				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_photo_data_cnt"){ // 안쓰는것같음
			$sql = "
				SELECT COUNT(*) FROM tb_ani_tag_mgr
			";
			$result = mysql_query($sql);
		}else if($r_mode == "get_cut_option"){

			// 펫종류
			$sql = "
				SELECT * FROM tb_ani_kinds WHERE is_delete = '0'
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data["pet_kind"][] = $row;
			}

			// 바디컷
			$sql = "
				SELECT * FROM tb_ani_body_cut WHERE is_delete = '0'
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data["body_cut"][] = $row;
			}
			
			// 헤드컷
			$sql = "
				SELECT * FROM tb_ani_head_cut WHERE is_delete = '0'
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data["head_cut"][] = $row;
			}

			// 다리컷
			$sql = "
				SELECT * FROM tb_ani_foot_cut WHERE is_delete = '0'
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data["foot_cut"][] = $row;
			}

			// 털칼라
			$sql = "
				SELECT * FROM tb_ani_color WHERE is_delete = '0'
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data["color"][] = $row;
			}

			// 기타-메인
			$sql = "
				SELECT * FROM tb_ani_etc_main WHERE is_delete = '0'
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data["etc_main"][] = $row;
			}

			// 기타-서브
			$sql = "
				SELECT * FROM tb_ani_etc_sub WHERE is_delete = '0'
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data["etc_sub"][] = $row;
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_etc_sub"){
			$r_etc_main = ($_POST["etc_main"] && $_POST["etc_main"] != "")? $_POST["etc_main"] : "";

			$sql = "
				SELECT * FROM tb_ani_etc_sub
				WHERE etc_main_idx = '".$r_etc_main."'
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data[] = $row;
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "insert_etc_main"){
			$sql = "INSERT INTO tb_ani_etc_main () VALUE ()";
			$result = mysql_query($sql);
			if($result){
				$sql_select = "
					SELECT * FROM tb_ani_etc_main ORDER BY idx DESC LIMIT 1
				";
				$result_select = mysql_query($sql_select);
				$data[] = mysql_fetch_assoc($result_select);
				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "999996", "data" => "에러! 글로리를 불러주세요~! 값이 안들어갔습니다!");
			}
		}else if($r_mode == "insert_etc_sub"){
			$r_idx = ($_POST["idx"] && $_POST["idx"] != "")? $_POST["idx"] : "";
			$r_sub_name = ($_POST["sub_name"] && $_POST["sub_name"] != "")? $_POST["sub_name"] : "";
			if($r_idx && $r_idx  != "" && $r_sub_name && $r_sub_name  != ""){
				$sql = "
					INSERT INTO tb_ani_etc_sub (`etc_main_idx`, `name`) VALUE ('".$r_idx."', '".$r_sub_name."')
				";
				$result = mysql_query($sql);
				$return_data = array("code" => "000000", "data" => "ok");
			}else{
				$return_data = array("code" => "999996", "data" => "에러! 글로리를 불러주세요~! 값이 안들어갔습니다!");
			}
		}else if($r_mode == "data_update"){
			$r_idx = ($_POST["idx"] && $_POST["idx"] != "")? $_POST["idx"] : "";
			if($r_idx && $r_idx  != ""){
				$r_kind_idx = ($_POST["pet_kind_".$r_idx] && $_POST["pet_kind_".$r_idx] != "")? " kind_idx = ".$_POST["pet_kind_".$r_idx].", " : "";
				$r_body_cut_idx = ($_POST["body_cut_".$r_idx] && $_POST["body_cut_".$r_idx] != "")? " body_cut_idx = ".$_POST["body_cut_".$r_idx].", " : "";
				$r_head_cut_idx = ($_POST["head_cut_".$r_idx] && $_POST["head_cut_".$r_idx] != "")? " head_cut_idx = ".$_POST["head_cut_".$r_idx].", " : "";
				$r_foot_cut_idx = ($_POST["foot_cut_".$r_idx] && $_POST["foot_cut_".$r_idx] != "")? " foot_cut_idx = ".$_POST["foot_cut_".$r_idx].", " : "";
				$r_color_idx = ($_POST["color_".$r_idx] && $_POST["color_".$r_idx] != "")? " color_idx = ".$_POST["color_".$r_idx].", " : "";
				$r_finish = ($_POST["finish"] && $_POST["finish"] != "")? " is_work_finish = ".$_POST["finish"].", " : "";
				$r_delete = ($_POST["delete"] && $_POST["delete"] != "")? " is_delete = ".$_POST["delete"].", " : "";
				$r_etc_main_idx = ($_POST["etc_main_idx"] && $_POST["etc_main_idx"] != "")? " etc_main_idx = ".$_POST["etc_main_idx"].", " : "";
				$sql = "
					UPDATE tb_ani_tag_mgr SET 
						".$r_kind_idx. 
						$r_body_cut_idx. 
						$r_head_cut_idx. 
						$r_foot_cut_idx. 
						$r_color_idx.
						$r_finish.
						$r_delete.
						$r_etc_main_idx."
						reg_date = now() 
					WHERE idx = '".$r_idx."'
				";
				$result = mysql_query($sql);
				$return_data = array("code" => "000000", "data" => "ok");
			}else{
				$return_data = array("code" => "999996", "data" => "에러! 글로리를 불러주세요~! 값이 안들어갔습니다!");
			}
		}else if($r_mode == "update_etc_sub"){
			$r_idx = ($_POST["idx"] && $_POST["idx"] != "")? $_POST["idx"] : "";
			$r_sub_name = ($_POST["sub_name"] && $_POST["sub_name"] != "")? $_POST["sub_name"] : "";
			if($r_idx && $r_idx  != ""){
				$sql = "
					UPDATE tb_ani_etc_sub SET 
						name = '".$r_sub_name."', 
						reg_date = now() 
					WHERE idx = '".$r_idx."'
				";
				$result = mysql_query($sql);
				$return_data = array("code" => "000000", "data" => "ok");
			}else{
				$return_data = array("code" => "999996", "data" => "에러! 글로리를 불러주세요~! 값이 안들어갔습니다!");
			}
		}else{
			$return_data = array("code" => "999997", "data" => "에러! 글로리를 불러주세요~! 모드가 설정이 안되었습니다!");
		}
	}else{
		$return_data = array("code" => "999998", "data" => "올바르지 않은 접근입니다.");
	}

	echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>