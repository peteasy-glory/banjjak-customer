<?php
	include "../include/configure.php";
	include "../include/Crypto.class.php";
	include "../include/session.php";
	include "../include/db_connection.php";
	include "../include/php_function.php";

	$data = array();
	$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
	
	$r_mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";
	$r_date = ($_POST["date"] && $_POST["date"] != "")? $_POST["date"] : "";
	$r_year = ($_POST["year"] && $_POST["year"] != "")? $_POST["year"] : "";
	$r_month = ($_POST["month"] && $_POST["month"] != "")? $_POST["month"] : "";
	$r_day = ($_POST["day"] && $_POST["day"] != "")? $_POST["day"] : "";
	$r_code = ($_POST["code"] && $_POST["code"] != "")? $_POST["code"] : "";
	$r_payment_log = ($_POST["payment_log"] && $_POST["payment_log"] != "")? $_POST["payment_log"] : "";
	$r_change_com = "2021-06-07"; // 업체 변경 날
	$r_update_message = "2021-06-09"; // 실패시 문자 전송

	if($r_mode){
		// 2021-06-07 이전 조회(이노탭)
		if($r_year != "" && $r_month != "" && $r_day != "" && $r_date < $r_change_com){
			if($r_mode == "get_allim"){
				$sql = "
					SELECT COUNT(case when send_result_code1 = 'ok' then 1 END) AS sucsess_cnt, 
							COUNT(case when not send_result_code1 = 'ok' then 1 END) AS fail_cnt 
					FROM TSMS_ATALK_AGENT_MESSAGE_LOG_".$r_year.$r_month." 
						WHERE template_code = '".$r_code."' 
						AND DATE(send_reserve_date) = '".$r_date."'
				";
				$result = mysql_query($sql);

				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}
				$return_data = array("code" => "000000", "data" => $data); 

			}else if($r_mode == "get_fail_detail"){
				
				$sql = "
					SELECT send_date AS date_mt_report, receive_mobile_no AS recipient_num, send_result_code1 AS report_code 
					FROM TSMS_ATALK_AGENT_MESSAGE_LOG_".$r_year.$r_month." 
						WHERE template_code = '".$r_code."'  
						AND not send_result_code1 = 'ok'
						AND DATE(send_reserve_date) = '".$r_date."'
				";
				$result = mysql_query($sql);

				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}
				$return_data = array("code" => "000000", "data" => $data); 

			}else{
				$return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
			}
		// 2021-06-07 부터 조회(인포뱅크)
		}else if($r_year != "" && $r_month != "" && $r_day != "" && $r_date >= $r_change_com){
			if($r_mode == "get_allim"){
				$sql = "
					SELECT COUNT(case when report_code = '1000' then 1 END) AS sucsess_cnt,
							COUNT(case when not report_code = '1000' then 1 END) AS fail_cnt
					FROM ita_talk_log_".$r_year.$r_month." 
						WHERE template_code = '1000004530_".$r_code."' 
						AND DATE(date_mt_report) = '".$r_date."'
				";
				$result = mysql_query($sql);

				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}
				$return_data = array("code" => "000000", "data" => $data); 

			}else if($r_mode == "get_fail_detail"){
				
				$sql = "
					SELECT date_mt_report, recipient_num, report_code, 
					SUBSTRING_INDEX(SUBSTRING_INDEX(kko_btn_info, '=', '-1' ), '\"', '1') AS payment_log,
					SUBSTRING_INDEX(template_code, '_', '-1') AS tem_code
					FROM ita_talk_log_".$r_year.$r_month." 
						WHERE template_code = '1000004530_".$r_code."' 
						AND not report_code = '1000'
						AND DATE(date_mt_report) = '".$r_date."'
				";
				$result = mysql_query($sql);

				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}
				$return_data = array("code" => "000000", "data" => $data); 

			}else if($r_mode == "get_send_message"){
				
				$sql = "
					SELECT date_mt_report, recipient_num, mt_report_code_ib,
						SUBSTRING_INDEX(content, '=', '-1' ) AS payment_log
					FROM em_mmt_log_".$r_year.$r_month." 
					WHERE DATE(date_mt_report) = '".$r_date."'
				";
//				WHERE SUBSTRING_INDEX(content, '=', '-1' ) = '".$r_payment_log."'
				$result = mysql_query($sql);

				while($row = mysql_fetch_assoc($result)){
					$data[] = $row;
				}
				$return_data = array("code" => "000000", "data" => $data); 

			}else if($r_mode == "get_send_message_end"){
				
				$sql = "
					SELECT date_mt_report, recipient_num, mt_report_code_ib
					FROM em_mmt_log_".$r_year.$r_month." 
					WHERE DATE(date_mt_report) = '".$r_date."'
					AND content LIKE '%종료시간을%'
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
			$return_data = array("code" => "000001", "data" => "중요 데이터가 누락되었습니다.");
		}
	}else{
		$return_data = array("code" => "999998", "data" => "올바르지 않은 접근입니다.");
	}

	echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>