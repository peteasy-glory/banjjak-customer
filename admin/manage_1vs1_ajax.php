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
		if($r_mode == "get_customer"){
			$r_admin_id	   = ($_POST["admin_id"] && $_POST["admin_id"] != "")? $_POST["admin_id"] : "";
			
			if($r_admin_id != ""){
				$sql = "
					SELECT *
					FROM tb_customer
					WHERE id = '".$r_admin_id."'
				";
				$result = mysql_query($sql);
				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}
				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락 되었습니다.");
			}
		}else if($r_mode == "get_1vs1_reply"){
			$r_limit_0 = ($_POST["limit_0"] && $_POST["limit_0"] != "")? $_POST["limit_0"] : "0";
			$r_limit_1 = ($_POST["limit_1"] && $_POST["limit_1"] != "")? $_POST["limit_1"] : "10";
			
			$sql = "
				SELECT * 
				FROM tb_1vs1_pna 
				GROUP BY update_time DESC
				LIMIT ".$r_limit_0.", ".$r_limit_1."
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data[] = $row;
			}
			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_1vs1_reply_sub"){
			$r_qna_id = ($_POST["qna_id"] && $_POST["qna_id"] != "")? $_POST["qna_id"] : "";
			
			if($r_qna_id != ""){
				$sql = "
					SELECT * 
					FROM tb_1vs1_pna_sub 
					WHERE qna_id = '".$r_qna_id."'
				";
				$result = mysql_query($sql);
				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}
				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락 되었습니다.");
			}
		}else if($r_mode == "set_insert_1vs1_reply_sub"){
			$r_qna_id = ($_POST["qna_id"] && $_POST["qna_id"] != "")? $_POST["qna_id"] : "";
			$r_body = ($_POST["body"] && $_POST["body"] != "")? $_POST["body"] : "";
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";

			if($r_qna_id != "" && $r_body != ""){
				$sql = "
					INSERT INTO tb_1vs1_pna_sub (
						`qna_id`, `body`, `update_time`
					) VALUES (
						'".$r_qna_id."', '".addslashes($r_body)."', now()
					)
				";

				$result = mysql_query($sql);
				if($result){
			        $message = "1대1 문의에 대한 답글이 게시되었습니다.";
					$path = "http://gopet.kr/pet/mainpage/manage_1vs1.php";
					//$image = "http://gopet.kr/pet/images/logo_login.jpg";
					$image = "";
					//a_push($customer_id, "반짝, 반려생활의 단짝. 1대1 문의 답글알림", $message, $path, $image);

					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "000002", "data" => "1대1 문의 답글 등록에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락 되었습니다.");
			}
		}else if($r_mode == "set_update_1vs1_reply_sub"){
			$r_sub_seq = ($_POST["sub_seq"] && $_POST["sub_seq"] != "")? $_POST["sub_seq"] : "";
			$r_qna_id = ($_POST["qna_id"] && $_POST["qna_id"] != "")? $_POST["qna_id"] : "";
			$r_body = ($_POST["body"] && $_POST["body"] != "")? $_POST["body"] : "";
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";

			if($r_sub_seq != "" && $r_qna_id != "" && $r_body != ""){
				$sql = "
					UPDATE tb_1vs1_pna_sub SET
						body = '".addslashes($r_body)."',
						update_time = NOW()
					WHERE sub_seq = '".$r_sub_seq."'
						AND qna_id = '".$r_qna_id."'
				";

				$result = mysql_query($sql);
				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "000002", "data" => "1대1 문의 답글 수정에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락 되었습니다.");
			}
		}else{
			$return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
		}
	}else{
		$return_data = array("code" => "999998", "data" => "올바르지 않은 접근입니다.");
	}

	echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>