<?php
	include "../include/configure.php";
	include "../include/Crypto.class.php";
	include "../include/session.php";
	include "../include/db_connection.php";
	include "../include/php_function.php";
	include "../include/Point.class.php";

	$data = array();
	$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
	
	$r_mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";

	if($r_mode){
		if($r_mode == "get_review_list"){
			$r_flag = ($_POST["flag"] && $_POST["flag"] != "")? $_POST["flag"] : "0";
			$r_page_cnt = ($_POST["page_cnt"] && $_POST["page_cnt"] != "")? $_POST["page_cnt"] : "10";

			$sql = "
				SELECT ur.*, cu.nickname AS customer_name, sh.name AS artist_name, (SELECT rb_seq FROM tb_review_best WHERE review_seq = ur.review_seq AND is_delete = '1') AS rb_seq
				FROM tb_usage_reviews AS ur
					LEFT OUTER JOIN tb_customer AS cu ON ur.customer_id = cu.id
					LEFT OUTER JOIN tb_shop AS sh ON ur.artist_id = sh.customer_id
				WHERE is_delete = '0'
				ORDER BY reg_time DESC
				LIMIT ".$r_flag." , ".$r_page_cnt."
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data[] = $row;
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_review_best_list"){
			$r_flag = ($_POST["flag"] && $_POST["flag"] != "")? $_POST["flag"] : "0";
			$r_page_cnt = ($_POST["page_cnt"] && $_POST["page_cnt"] != "")? $_POST["page_cnt"] : "10";
			$r_best_list = ($_POST["best_list"] && $_POST["best_list"] != "")? $_POST["best_list"] : "";
			$r_review_seq = ($_POST["review_seq"] && $_POST["review_seq"] != "")? $_POST["review_seq"] : "";

			$where_qy = "";
			if($r_best_list != ""){
				$where_qy .= " AND rb.order_num != '0' ";
			}

			if($r_review_seq != ""){
				$where_qy .= " AND rb.review_seq = '".$r_review_seq."' ";
			}

			$sql = "
				SELECT 
					rb.*, ur.customer_id, ur.artist_id, ur.review_seq, ur.rating, 
					ur.review_images, ur.review, ur.reg_time, ur.customer_name, ur.artist_name
				FROM tb_review_best AS rb
					LEFT OUTER JOIN (
						SELECT ur.*, cu.nickname AS customer_name, sh.name AS artist_name, cu.id AS c_id, sh.customer_id AS a_id
						FROM tb_usage_reviews AS ur
							LEFT OUTER JOIN tb_customer AS cu ON ur.customer_id = cu.id
							LEFT OUTER JOIN tb_shop AS sh ON ur.artist_id = sh.customer_id
						WHERE is_delete = '0'
					)AS ur ON rb.review_seq = ur.review_seq
				WHERE rb.is_delete = '1'
					".$where_qy."
				ORDER BY rb.update_dt DESC, rb.reg_dt DESC
				LIMIT ".$r_flag." , ".$r_page_cnt."
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$data[] = $row;
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "get_review_best_list2"){
			$r_flag = ($_POST["flag"] && $_POST["flag"] != "")? $_POST["flag"] : "0";
			$r_page_cnt = ($_POST["page_cnt"] && $_POST["page_cnt"] != "")? $_POST["page_cnt"] : "10";
			$r_best_list = ($_POST["best_list"] && $_POST["best_list"] != "")? $_POST["best_list"] : "";
			$r_review_seq = ($_POST["review_seq"] && $_POST["review_seq"] != "")? $_POST["review_seq"] : "";

			$where_qy = "";
			if($r_best_list != ""){
				$where_qy .= " AND rb.order_num != '0' ";
			}

			if($r_review_seq != ""){
				$where_qy .= " AND rb.review_seq = '".$r_review_seq."' ";
			}

			$sql = "
				SELECT *
				FROM tb_review_best as rb
				WHERE rb.is_delete = '1'
					".$where_qy."
				ORDER BY rb.update_dt DESC, rb.reg_dt DESC
				LIMIT ".$r_flag." , ".$r_page_cnt."
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				$sql2 = "
					SELECT ur.*, 
						( SELECT id FROM tb_customer WHERE id = ur.customer_id ) AS c_id,
						( SELECT nickname FROM tb_customer WHERE id = ur.customer_id ) AS customer_name,
						( SELECT customer_id FROM tb_shop WHERE customer_id = ur.artist_id ) AS a_id,
						( SELECT name FROM tb_shop WHERE customer_id = ur.artist_id ) AS artist_name
					FROM tb_usage_reviews AS ur
					WHERE ur.is_delete = '0'
						AND ur.review_seq = '".$row["review_seq"]."'
				";
				$result2 = mysql_query($sql2);
				while($row2 = mysql_fetch_assoc($result2)){
					$data[] = array_merge($row, $row2);
				}
			}

			$return_data = array("code" => "000000", "data" => $data);
		}else if($r_mode == "set_insert_review_best"){
			$r_review_seq = ($_POST["review_seq"] && $_POST["review_seq"] != "")? $_POST["review_seq"] : "";
			$r_order_num = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : "0";
	
			if($r_review_seq != ""){
				$sql = "
					INSERT INTO tb_review_best (`review_seq`, `order_num`) VALUES ('".$r_review_seq."', '".$r_order_num."')
				";
				$result = mysql_query($sql);
				if($result){
					$review_seq = mysql_insert_id();
					$return_data = array("code" => "000000", "data" => $review_seq);
				}else{
					$return_data = array("code" => "000001", "data" => "베스트 댓글 등록에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "000002", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_update_review_best"){
			$r_rb_seq = ($_POST["rb_seq"] && $_POST["rb_seq"] != "")? $_POST["rb_seq"] : "";
			$r_review_seq = ($_POST["review_seq"] && $_POST["review_seq"] != "")? $_POST["review_seq"] : "";
			$r_order_num = ($_POST["order_num"] && $_POST["order_num"] != "")? $_POST["order_num"] : "0";
	
			if($r_rb_seq != ""){
				$sql = "
					UPDATE tb_review_best SET
						order_num = '".$r_order_num."',
						update_dt = NOW()
					WHERE rb_seq = '".$r_rb_seq."'
						AND review_seq = '".$r_review_seq."'
				";
				$result = mysql_query($sql);

				if($result){
					/*
					if($r_order_num != "0"){
						$sql = "
							UPDATE tb_review_best SET
								order_num = '0'
							WHERE rb_seq != '".$r_rb_seq."'
								AND review_seq != '".$r_review_seq."'
								AND order_num = '".$r_order_num."'
						";
						$result = mysql_query($sql);
						if($result){
							$return_data = array("code" => "000000", "data" => "OK_change");
						}else{
							$return_data = array("code" => "000003", "data" => "베스트 댓글 취소 변경에 실패했습니다.");
						}
					}else{
					*/
						$return_data = array("code" => "000000", "data" => "OK_add");
					//}
				}else{
					$return_data = array("code" => "000001", "data" => "베스트 댓글 변경에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "000002", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_delete_review_best"){
			$r_rb_seq = ($_POST["rb_seq"] && $_POST["rb_seq"] != "")? $_POST["rb_seq"] : "";
			$r_user_id = ($_POST["user_id"] && $_POST["user_id"] != "")? $_POST["user_id"] : "";
			$r_delete_txt = ($_POST["delete_txt"] && $_POST["delete_txt"] != "")? $_POST["delete_txt"] : "";
	
			if($r_rb_seq != ""){
				$sql = "
					UPDATE tb_review_best SET
						is_delete = '2',
						delete_txt = '".$r_user_id."|".$r_delete_txt."'
						delete_dt = NOW()
					WHERE rb_seq = '".$r_rb_seq."'
				";
				$result = mysql_query($sql);

				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "000001", "data" => "베스트 댓글 삭제에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "000002", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "set_insert_review_best_log"){
			$r_rb_seq = ($_POST["rb_seq"] && $_POST["rb_seq"] != "")? $_POST["rb_seq"] : "";
			$r_status = ($_POST["status"] && $_POST["status"] != "")? $_POST["status"] : "";
	
			if($r_rb_seq != ""){
				$sql = "
					INSERT INTO tb_review_best_log (`rb_seq`, `status`) VALUES ('".$r_rb_seq."', '".$r_status."')
				";
				$result = mysql_query($sql);
				if($result){
					$return_data = array("code" => "000000", "data" => "OK");
				}else{
					$return_data = array("code" => "000001", "data" => "베스트 댓글 내역 등록에 실패했습니다.");
				}
			}else{
				$return_data = array("code" => "000002", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "get_payment_log_cnt"){
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_artist_id = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$where_qy = "";

			if($r_customer_id != ""){
				$where_qy .= " AND customer_id = '".$r_customer_id."' ";
			}
			if($r_artist_id != ""){
				$where_qy .= " AND artist_id = '".$r_artist_id."' ";
			}

			if($where_qy != "" && $r_customer_id != "" && $r_artist_id != ""){
				$sql = "
					SELECT COUNT(*) AS cnt 
					FROM tb_payment_log 
					WHERE approval = '1' 
						AND product_type = 'B' 
						AND is_cancel = '0' 
						AND is_no_show = '0'
						".$where_qy."
				";
				$result = mysql_query($sql);
				$data = mysql_fetch_assoc($result);
				$return_data = array("code" => "000000", "data" => $data["cnt"]);
			}else{
				$return_data = array("code" => "000002", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else if($r_mode == "get_user_point"){
			$r_customer_id = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";

			if($r_customer_id != ""){
				$point = new Point;
				$point->select_point($r_customer_id);
				$data = $point->get_point();

				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "000002", "data" => "중요 데이터가 누락되었습니다.");
			}
		}else{
			$return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
		}
	}else{
		$return_data = array("code" => "999998", "data" => "올바르지 않은 접근입니다.");
	}

	echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>