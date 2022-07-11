<?php
	include "../include/configure.php";
	include "../include/Crypto.class.php";
	include "../include/session.php";
	include "../include/db_connection.php";
	include "../include/php_function.php";

	$data = array();
	$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
	
	$r_id = ($_POST["id"] && $_POST["id"] != "")? $_POST["id"] : "";
	$r_mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";
	
	$sql = "SELECT * FROM tb_customer WHERE	id = '".$r_id."'";
	$result = mysql_query($sql);
	$sql_cnt = mysql_num_rows($result);

	if($sql_cnt == 1){
		if($r_mode){
			if($r_mode == "give_shop"){ // 샵 권한 부여

				$sql = "
					UPDATE tb_customer SET my_shop_flag = '1' WHERE id = '".$r_id."'
				";
				$result = mysql_query($sql);

				$return_data = array("code" => "000000", "data" => $data); 

			}else if($r_mode == "return_shop"){ // 일반회원으로 강등

				$sql = "
					UPDATE tb_customer SET my_shop_flag = '0' WHERE id = '".$r_id."'
				";
				$result = mysql_query($sql);

				$return_data = array("code" => "000000", "data" => $data);  

			}else if($r_mode == "secession"){ // 탈퇴

				$sql = "
					UPDATE tb_customer SET enable_flag = '0', delete_time = NOW() WHERE id = '".$r_id."'
				";
				$result = mysql_query($sql);

				$return_data = array("code" => "000000", "data" => $data);  

			}else if($r_mode == "secession_cancel"){ // 탈퇴 번복

				$sql = "
					UPDATE tb_customer SET enable_flag = '1' WHERE id = '".$r_id."'
				";
				$result = mysql_query($sql);

				$return_data = array("code" => "000000", "data" => $data);  

			}else{
				$return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
			}
		}else{
			$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
		}
	}else{
		$return_data = array("code" => "999998", "data" => "올바르지 않은 접근입니다.");
	}

	echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>