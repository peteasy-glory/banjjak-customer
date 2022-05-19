<?php
	include "../include/configure.php";
	include "../include/Crypto.class.php";
	include "../include/session.php";
	include "../include/db_connection.php";
	include "../include/php_function.php";

	$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
	
	$data = array();
	$r_mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";

	if($r_mode){
		if($r_mode == "get_member_payment_list"){
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";

			if($r_customer_id != ""){
				$sql = "
					SELECT *, ts.photo AS artist_photo
					FROM tb_payment_log tpl, tb_shop ts
					WHERE tpl.customer_id = '".$r_customer_id."' 
						AND tpl.is_cancel = 0 
						AND tpl.approval = 1 
						AND ts.customer_id = tpl.artist_id 
					GROUP BY tpl.update_time 
					ORDER BY tpl.year DESC, tpl.month DESC, tpl.day DESC, tpl.hour DESC, tpl.minute DESC
				";
				$result = mysql_query($sql);
				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}
				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "get_member_review_list"){
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_payment_log_seq = ($_POST["payment_log_seq"] && $_POST["payment_log_seq"] != "")? $_POST["payment_log_seq"] : "";

			$sql = "
				SELECT * 
				FROM tb_usage_reviews 
				WHERE payment_log_seq = '".$r_payment_log_seq."' 
					AND customer_id = '".$r_customer_id."' 
					AND is_delete = 0
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