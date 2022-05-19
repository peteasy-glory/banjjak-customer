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
					WHERE is_delete != '2'
						AND customer_id = '".$r_admin_id."'
				";
				$result = mysql_query($sql);
				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}
				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락 되었습니다.");
			}
		}else if($r_mode == "get_payment_log_summary"){
			$r_start_date	   = ($_POST["start_date"] && $_POST["start_date"] != "")? $_POST["start_date"] : "";
			$r_end_date	   = ($_POST["end_date"] && $_POST["end_date"] != "")? $_POST["end_date"] : "";
			$r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_artist_id	   = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$where_qy = "";

			if($r_start_date != "" && $r_end_date != ""){
				$where_qy .= " AND DATE(buy_time) BETWEEN '".$r_start_date."' AND '".$r_end_date."' ";
			}
			if($r_customer_id != ""){
				$where_qy .= " AND customer_id like '%".$r_customer_id."%' ";
			}
			if($r_artist_id != ""){
				$where_qy .= " AND artist_id like '%".$r_artist_id."%' ";
			}

			if($where_qy != ""){
				$sql = "
					SELECT COUNT(*) AS cnt
					FROM tb_payment_log
					WHERE 1=1 
						".$where_qy."
				";
				$result = mysql_query($sql);
				while($row = mysql_fetch_assoc($result)){
					$data['cnt'] = $row['cnt'];
				}
				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락 되었습니다.");
			}
		}else if($r_mode == "get_payment_log_cnt"){
			$r_start_date	   = ($_POST["start_date"] && $_POST["start_date"] != "")? $_POST["start_date"] : "";
			$r_end_date	   = ($_POST["end_date"] && $_POST["end_date"] != "")? $_POST["end_date"] : "";
			$r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_artist_id	   = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_pay_type	   = ($_POST["pay_type"] && $_POST["pay_type"] != "")? $_POST["pay_type"] : "";
			$where_qy = "";

			if($r_start_date != "" && $r_end_date != ""){
				$where_qy .= " AND DATE(buy_time) BETWEEN '".$r_start_date."' AND '".$r_end_date."' ";
			}
			if($r_customer_id != ""){
				$where_qy .= " AND customer_id like '%".$r_customer_id."%' ";
			}
			if($r_artist_id != ""){
				$where_qy .= " AND artist_id like '%".$r_artist_id."%' ";
			}
			if($r_pay_type != "" && is_array($r_pay_type) && count($r_pay_type) > 0){
				$tmp_qy = "";
				for($_i = 0; $_i < count($r_pay_type); $_i++){
					if($r_pay_type[$_i] == "pos"){
						$tmp_qy .= "'pos-card','pos-cash','pos',";
					}else if($r_pay_type[$_i] == "offline"){
						$tmp_qy .= "'offline-card','offline-cash','offline',";
					}else{
						$tmp_qy .= "'".$r_pay_type[$_i]."',";
					}
				}
				$tmp_qy = substr($tmp_qy, 0, -1);
				$where_qy .= " AND pay_type IN (".$tmp_qy.") ";
			}

			if($where_qy != ""){
				$sql = "
					SELECT COUNT(*) AS cnt
					FROM tb_payment_log
					WHERE 1=1 
						".$where_qy."
				";
				$result = mysql_query($sql);
				while($row = mysql_fetch_assoc($result)){
					$data['cnt'] = $row['cnt'];
				}
				$return_data = array("code" => "000000", "data" => $data);
			}else{
				$return_data = array("code" => "000001", "data" => "중요 데이터가 누락 되었습니다.");
			}
		}else if($r_mode == "get_payment_log"){
			$r_start_date	   = ($_POST["start_date"] && $_POST["start_date"] != "")? $_POST["start_date"] : "";
			$r_end_date	   = ($_POST["end_date"] && $_POST["end_date"] != "")? $_POST["end_date"] : "";
			$r_customer_id	   = ($_POST["customer_id"] && $_POST["customer_id"] != "")? $_POST["customer_id"] : "";
			$r_artist_id	   = ($_POST["artist_id"] && $_POST["artist_id"] != "")? $_POST["artist_id"] : "";
			$r_pay_type	   = ($_POST["pay_type"] && $_POST["pay_type"] != "")? $_POST["pay_type"] : "";
			$r_limit_0	   = ($_POST["limit_0"] && $_POST["limit_0"] != "")? $_POST["limit_0"] : "0";
			$r_limit_1	   = ($_POST["limit_1"] && $_POST["limit_1"] != "")? $_POST["limit_1"] : "10";
			$where_qy = "";

			if($r_start_date != "" && $r_end_date != ""){
				$where_qy .= " AND DATE(pl.buy_time) BETWEEN '".$r_start_date."' AND '".$r_end_date."' ";
			}
			if($r_customer_id != ""){
				$where_qy .= " AND pl.customer_id like '%".$r_customer_id."%' ";
			}
			if($r_artist_id != ""){
				$where_qy .= " AND pl.artist_id like '%".$r_artist_id."%' ";
			}
			if($r_pay_type != "" && is_array($r_pay_type) && count($r_pay_type) > 0){
				$tmp_qy = "";
				for($_i = 0; $_i < count($r_pay_type); $_i++){
					if($r_pay_type[$_i] == "pos"){
						$tmp_qy .= "'pos-card','pos-cash','pos',";
					}else if($r_pay_type[$_i] == "offline"){
						$tmp_qy .= "'offline-card','offline-cash','offline',";
					}else{
						$tmp_qy .= "'".$r_pay_type[$_i]."',";
					}
				}
				$tmp_qy = substr($tmp_qy, 0, -1);
				$where_qy .= " AND pl.pay_type IN (".$tmp_qy.") ";
			}

			if($where_qy != ""){
				$sql = "
					SELECT pl.*, sh.name
					FROM tb_payment_log AS pl 
					LEFT JOIN tb_shop AS sh
					ON pl.artist_id = sh.customer_id
					WHERE 1=1 
						".$where_qy."
					ORDER BY buy_time DESC
					LIMIT ".$r_limit_0.", ".$r_limit_1."
				";
				$result = mysql_query($sql);
				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}
				$return_data = array("code" => "000000", "data" => $data);
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