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
		if($r_mode == "set_update_enable"){
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_type = ($_POST["type"] && $_POST["type"] != "")? $_POST["type"] : "";
			$r_value = ($_POST["value"] != "")? $_POST["value"] : "";

			if($r_artist_id != "" && $r_type != "" && $r_value != ""){
				if($r_type == "beauty"){
					$sql = "
						UPDATE tb_shop SET
							enable_flag = '".$r_value."'
						WHERE customer_id = '".$r_artist_id."'
					";
				}else if($r_type == "hotel"){
					$sql = "
						UPDATE tb_hotel SET
							is_enable = '".$r_value."'
						WHERE is_delete = '2'
							AND artist_id = '".$r_artist_id."'
					";
				}else if($r_type == "playroom"){
					$sql = "
						UPDATE tb_playroom SET
							is_enable = '".$r_value."'
						WHERE is_delete = '2'
							AND artist_id = '".$r_artist_id."'
					";
				}
				$result = mysql_query($sql);

				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "000001", "data" => "변경에 실패했습니다.");
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