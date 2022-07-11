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
		if($r_mode == "change_rank"){
			$r_rank_one = ($_POST["rank_one"] && $_POST["rank_one"] != "")? $_POST["rank_one"] : "";
			$r_rank_two = ($_POST["rank_two"] && $_POST["rank_two"] != "")? $_POST["rank_two"] : "";
			$r_rank_three = ($_POST["rank_three"] && $_POST["rank_three"] != "")? $_POST["rank_three"] : "";
			$r_rank_four = ($_POST["rank_four"] && $_POST["rank_four"] != "")? $_POST["rank_four"] : "";
			$r_rank_five = ($_POST["rank_five"] && $_POST["rank_five"] != "")? $_POST["rank_five"] : "";

			if($r_rank_one != "" && $r_rank_two != "" && $r_rank_three != "" && $r_rank_four != "" && $r_rank_five != ""){
				$sql = "
					UPDATE tb_item_rank SET
						search = CASE num WHEN '1' THEN '".$r_rank_one."' ELSE search END,
						search = CASE num WHEN '2' THEN '".$r_rank_two."' ELSE search END,
						search = CASE num WHEN '3' THEN '".$r_rank_three."' ELSE search END,
						search = CASE num WHEN '4' THEN '".$r_rank_four."' ELSE search END,
						search = CASE num WHEN '5' THEN '".$r_rank_five."' ELSE search END
					WHERE is_shop = '2'
				";
				$result = mysql_query($sql);
//				if($r_type == "beauty"){
//					$sql = "
//						UPDATE tb_shop SET
//							enable_flag = '".$r_value."'
//						WHERE customer_id = '".$r_artist_id."'
//					";
//				}else if($r_type == "hotel"){
//					$sql = "
//						UPDATE tb_hotel SET
//							is_enable = '".$r_value."'
//						WHERE is_delete = '2'
//							AND artist_id = '".$r_artist_id."'
//					";
//				}else if($r_type == "playroom"){
//					$sql = "
//						UPDATE tb_playroom SET
//							is_enable = '".$r_value."'
//						WHERE is_delete = '2'
//							AND artist_id = '".$r_artist_id."'
//					";
//				}
//				$result = mysql_query($sql);

				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "000001", "data" => "변경에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}

		}else if($r_mode == "change_rank_partner"){
			$r_rank_one = ($_POST["rank_one"] && $_POST["rank_one"] != "")? $_POST["rank_one"] : "";
			$r_rank_two = ($_POST["rank_two"] && $_POST["rank_two"] != "")? $_POST["rank_two"] : "";
			$r_rank_three = ($_POST["rank_three"] && $_POST["rank_three"] != "")? $_POST["rank_three"] : "";
			$r_rank_four = ($_POST["rank_four"] && $_POST["rank_four"] != "")? $_POST["rank_four"] : "";
			$r_rank_five = ($_POST["rank_five"] && $_POST["rank_five"] != "")? $_POST["rank_five"] : "";

			if($r_rank_one != "" && $r_rank_two != "" && $r_rank_three != "" && $r_rank_four != "" && $r_rank_five != ""){
				$sql = "
					UPDATE tb_item_rank SET
						search = CASE num WHEN '1' THEN '".$r_rank_one."' ELSE search END,
						search = CASE num WHEN '2' THEN '".$r_rank_two."' ELSE search END,
						search = CASE num WHEN '3' THEN '".$r_rank_three."' ELSE search END,
						search = CASE num WHEN '4' THEN '".$r_rank_four."' ELSE search END,
						search = CASE num WHEN '5' THEN '".$r_rank_five."' ELSE search END
					WHERE is_shop = '1'
				";
				$result = mysql_query($sql);
//				if($r_type == "beauty"){
//					$sql = "
//						UPDATE tb_shop SET
//							enable_flag = '".$r_value."'
//						WHERE customer_id = '".$r_artist_id."'
//					";
//				}else if($r_type == "hotel"){
//					$sql = "
//						UPDATE tb_hotel SET
//							is_enable = '".$r_value."'
//						WHERE is_delete = '2'
//							AND artist_id = '".$r_artist_id."'
//					";
//				}else if($r_type == "playroom"){
//					$sql = "
//						UPDATE tb_playroom SET
//							is_enable = '".$r_value."'
//						WHERE is_delete = '2'
//							AND artist_id = '".$r_artist_id."'
//					";
//				}
//				$result = mysql_query($sql);

				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "000001", "data" => "변경에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}

		}else if($r_mode == "get_rank"){
			
			$sql = "
				SELECT * FROM tb_item_rank WHERE is_shop = '2' ORDER BY num
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data[] = $row;
			}
			$return_data = array("code" => "000000", "data" => $data); 

		}else if($r_mode == "get_rank_partner"){
			
			$sql = "
				SELECT * FROM tb_item_rank WHERE is_shop = '1' ORDER BY num
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data[] = $row;
			}
			$return_data = array("code" => "000000", "data" => $data); 

		}else if($r_mode == "get_main_banner"){
			
			$sql = "
				SELECT * FROM tb_main_banner
				WHERE is_use = 1 AND is_delete = 2
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data[] = $row;
			}
			$return_data = array("code" => "000000", "data" => $data); 

		}else if($r_mode == "change_banner"){
			$r_mb_seq = ($_POST["mb_seq"] && $_POST["mb_seq"] != "")? $_POST["mb_seq"] : "";
			
			if($r_mb_seq != ""){
				$sql = "
					UPDATE tb_main_banner SET
						search_rank = CASE mb_seq WHEN '".$r_mb_seq."' THEN '1' END
				";
				$result = mysql_query($sql);
				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "000001", "data" => "변경에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}

		}else if($r_mode == "get_search_banner"){
			$sql = "
				SELECT * FROM tb_main_banner
				WHERE is_use = 1 AND is_delete = 2 AND search_rank = 1
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data[] = $row;
			}
			$return_data = array("code" => "000000", "data" => $data);

		}else{
			$return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
		}
	}else{
		$return_data = array("code" => "999998", "data" => "올바르지 않은 접근입니다.");
	}

	echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>