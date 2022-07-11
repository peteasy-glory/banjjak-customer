<?php include "../include/top.php"; ?>
<?php include "../include/Crypto.class_old.php"; ?>

<?php
	$artist_id = $_REQUEST['artist_id'];
	$round_id = $_REQUEST['id'];
	echo $artist_id;
	echo $round_id;

	if ($round_id && $artist_id) {

		$round_info = explode("_", $round_id);
		$a_yy = $round_info[0];
		$a_round = $round_info[1];
		$a_mm = $round_info[2];
		$a_index = $round_info[3];

		$is_personal = 0;
		$is_business = 0;
                $crypto = new Crypto();

		$bank_info = "";
		$user_info = "";
		$bank_sql = "select * from tb_artist_payment_info where customer_id = '".$artist_id."';";
                $bank_result = mysql_query($bank_sql);
                if ($bank_datas = mysql_fetch_object($bank_result)) {
                        $enc_bankname = $bank_datas->bankname;
                        $enc_account_holder = $bank_datas->account_holder;
                        $enc_bank_account = $bank_datas->bank_account;

                        $bankname = $crypto->decode($enc_bankname, $access_key, $secret_key);
                        $account_holder = $crypto->decode($enc_account_holder, $access_key, $secret_key);
                        $bank_account = $crypto->decode($enc_bank_account, $access_key, $secret_key);

			$bank_info = $bankname.",".$bank_account.",".$account_holder;


			$enc_customer_id = $crypto->encode(TRIM($artist_id), $access_key, $secret_key);
        	        $ra_sql = "select * from tb_request_artist where customer_id = '".$enc_customer_id."';";
                	$ra_result = mysql_query($ra_sql);
        	        if ($ra_rows = mysql_fetch_object($ra_result)) {
                	        $user_real_name = $crypto->decode($ra_rows->name, $access_key, $secret_key);
                        	$is_personal = $ra_rows->is_personal;
	                        $is_business = $ra_rows->is_business;
				$business_number = $ra_rows->business_number;
				$business_number = $crypto->decode($business_number, $access_key, $secret_key);
                        	if ($is_personal) {
					$user_info = $user_real_name."/개인";
	                        }
        	                if ($is_business) {
					$user_info = $user_real_name."/사업자/".$business_number;
	                        }
	                }

		}
		$start_date = "";
		$end_date = "";
		$last_start_date = "";
		$last_end_date = "";
		$last_round_id = "";

		$year_month = $a_yy."-".$a_mm;
		if ($a_index == '1') {
			$start_date = $year_month."-01 00:00:00";
			$end_date = $year_month."-10 23:59:59";
	
			if (intval($a_mm) == 1) {
				$last_year_month = strval(intval($a_yy)-1)."-12";
				$last_round_id = strval(intval($a_yy)-1)."_".strval(intval($a_round)-1)."_12_3";
			} else {
				$last_year_month = $a_yy."-".(intval($a_mm)-1);
				$last_round_id = $a_yy."_".strval(intval($a_round)-1)."_".(intval($a_mm)-1)."_3";
			}
			$last_start_date = $last_year_month."-21 00:00:00";
			$last_end_date = $last_year_month."-31 23:59:59";
		} else if ($a_index == '2') {
			$start_date = $year_month."-11 00:00:00";
			$end_date = $year_month."-20 23:59:59";

			$last_year_month = $a_yy."-".$a_mm;
			$last_start_date = $last_year_month."-01 00:00:00";
			$last_end_date = $last_year_month."-10 23:59:59";

			$last_round_id = $a_yy."_".strval(intval($a_round)-1)."_".$a_mm."_1";
		} else if ($a_index == '3') {
			$start_date = $year_month."-21 00:00:00";
			$end_date = $year_month."-31 23:59:59";

			$last_year_month = $a_yy."-".$a_mm;
			$last_start_date = $last_year_month."-11 00:00:00";
			$last_end_date = $last_year_month."-20 23:59:59";
			$last_round_id = $a_yy."_".strval(intval($a_round)-1)."_".$a_mm."_2";
		}

	
		$sql = "SELECT * FROM tb_payment_log 
			WHERE pay_type <> 'offline' AND pay_type <> 'offline-card' AND pay_type <> 'offline-cash' 
				AND pay_type <> 'pos' AND pay_type <> 'pos-card' AND pay_type <> 'pos-cash' 
				AND is_cancel = 0 AND artist_id = '".$_REQUEST['artist_id']."' AND DATE(buy_time) BETWEEN '".$start_date."' AND '".$end_date."';";
		$result = mysql_query($sql);
		$default_balance_accounts = "";
		$all_total = 0;
		$all_price1 = 0;
		$all_price2 = 0;
		$all_price3 = 0;
		$all_price4 = 0;
		for ($alli = 0 ; $rows = mysql_fetch_object($result); $alli++) {
			$total = intval($rows->total_price);
			$p_price2 = round($total*0.035);
			$p_price3 = round($p_price2*0.1);
			if ($is_personal) {
				$p_price1 = round(($total-($p_price2+$p_price3))*0.033);
			} else {
				$p_price1 = 0;
			}
			$p_price4 = $total-($p_price1+$p_price2+$p_price3);

			if ($alli != 0) {
				$default_balance_accounts = $default_balance_accounts."|";
			}

			//$default_balance_accounts = $default_balance_accounts.strval($total).",".strval($p_price1).",".strval($p_price2).",".strval($p_price3).",".strval($p_price4);
			//$default_balance_accounts = $default_balance_accounts.intval(round($total)).",".intval(round($p_price1)).",".intval(round($p_price2)).",".intval(round($p_price3)).",".intval(round($p_price4));
			$default_balance_accounts = $default_balance_accounts.$rows->payment_log_seq.",".$rows->update_time.",".$total.",".$p_price1.",".$p_price2.",".$p_price3.",".$p_price4;

			$all_total += $total;
			$all_price1 += $p_price1;
			$all_price2 += $p_price2;
			$all_price3 += $p_price3;
			$all_price4 += $p_price4;
		}

		$cancel_history = "";
		$cancel_sql = "SELECT * FROM tb_payment_log 
			WHERE pay_type <> 'offline' AND pay_type <> 'offline-card' AND pay_type <> 'offline-cash' 
				AND pay_type <> 'pos' AND pay_type <> 'pos-card' AND pay_type <> 'pos-cash' 
				AND is_cancel = 1 AND artist_id = '".$_REQUEST['artist_id']."' and DATE(buy_time) BETWEEN '".$last_start_date."' AND '".$last_end_date."';";
		$cancel_result = mysql_query($cancel_sql);
		for ($abc_i = 0 ; $cancel_rows = mysql_fetch_object($cancel_result) ; $abc_i++) {
			$buy_time = $cancel_rows->buy_time;
			$cancel_time = $cancel_rows->cancel_time;
			$payment_log_seq = $cancel_rows->payment_log_seq;

			$balance_accounts_time = "";
			$balance_accounts = "";
			$select_last_sql = "select * from tb_balance_accounts where customer_id = '".$_REQUEST['artist_id']."' and id = '".$last_round_id."';";
			$select_last_result = mysql_query($select_last_sql);
			if ($select_last_rows = mysql_fetch_object($select_last_result)) {
				$default_balance_accounts_1 = $select_last_rows->default_balance_accounts;
				$dba_array = explode("|", $default_balance_accounts_1);
				for ($dba_i = 0 ; $dba_i < sizeof($dba_array) ; $dba_i++) {
					$dba_value = explode(",", $dba_array[$dba_i]);
					if ($dba_value[0] == $payment_log_seq) {
						$balance_accounts_time = $select_last_rows->make_time;
						$balance_accounts = $dba_value[6];
					}
				}
			}
			if (!$balance_accounts) { continue; }
			if ($abc_i != 0) {
				$cancel_history = $cancel_history."|";
			}
			$cancel_history = $cancel_history.$payment_log_seq.",".$buy_time.",".$cancel_time.",".$balance_accounts_time.",".$balance_accounts;
		}

		$select_sql = "select * from tb_balance_accounts where customer_id = '".$artist_id."' and id = '".$round_id."';";
		$select_result = mysql_query($select_sql);
		if ($select_rows = mysql_fetch_object($select_result)) {
			$update_sql = "update tb_balance_accounts set make_date = now(), bank = '".$bank_info."', artist_info = '".$user_info."', default_balance_accounts = '".$default_balance_accounts."', total_price = '".strval($all_total)."', total_private_tax = '".strval($all_price1)."', total_platform_tax = '".strval($all_price2)."', total_vat_tax = '".strval($all_price3)."', total_result = '".strval($all_price4)."', cancel_history = '".$cancel_history."', plus_option = '', minus_option = '', final_result = '', update_time = now() where customer_id = '".$artist_id."' and id = '".$round_id."';";
			$update_result = mysql_query($update_sql);
		} else {
			$insert_sql = "insert into tb_balance_accounts (customer_id, id, make_date, bank, artist_info, default_balance_accounts, total_price, total_private_tax, total_platform_tax, total_vat_tax, total_result, cancel_history, plus_option, minus_option, final_result, update_time) values ('".$artist_id."', '".$round_id."', now(), '".$bank_info."', '".$user_info."', '".$default_balance_accounts."', '".strval($all_total)."', '".strval($all_price1)."', '".strval($all_price2)."', '".strval($all_price3)."', '".strval($all_price4)."', '".$cancel_history."', '', '', '', now());";
			echo $insert_sql;
			$insert_result = mysql_query($insert_sql);

		}
	}
?>
<?php
include "../include/buttom.php";
?>
<script>
	location.href = 'manage_balance_accounts.php?round=<?=$round_id?>&artist_id=<?=$artist_id?>';
</script>
