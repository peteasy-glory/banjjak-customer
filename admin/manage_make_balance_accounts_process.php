<?php include "../include/top.php"; ?>
<?php include "../include/Crypto.class.php"; ?>

<?php
$artist_id = $_REQUEST['artist_id'];
$round_id = $_REQUEST['round'];
$type = $_REQUEST['type'];

if($type == "plus") {
	$name = $_REQUEST['name'];
	$price = $_REQUEST['price'];
	$sql = "select * from tb_balance_accounts where customer_id = '".$artist_id."' and id = '".$round_id."';";
	$result = mysql_query($sql);
        if ($rows = mysql_fetch_object($result)) {
		$plus_option = $rows->plus_option;
		if (strlen($plus_option) > 3) {
			$plus_option = $plus_option."|".$name.",".$price;
		} else {
			$plus_option = $name.",".$price;
		}
		$u_sql = "update tb_balance_accounts set plus_option = '".$plus_option."' where customer_id = '".$artist_id."' and id = '".$round_id."';";
		$u_result = mysql_query($u_sql);
	}
} else if ($type == "minus") {
        $name = $_REQUEST['name'];
        $price = $_REQUEST['price'];
        $sql = "select * from tb_balance_accounts where customer_id = '".$artist_id."' and id = '".$round_id."';";
        $result = mysql_query($sql);
        if ($rows = mysql_fetch_object($result)) {
                $minus_option = $rows->minus_option;
                if (strlen($minus_option) > 3) {
                        $minus_option = $minus_option."|".$name.",".$price;
                } else {
                        $minus_option = $name.",".$price;
                }
                $u_sql = "update tb_balance_accounts set minus_option = '".$minus_option."' where customer_id = '".$artist_id."' and id = '".$round_id."';";
                $u_result = mysql_query($u_sql);
        }
} else if ($type == "delplus") {
        $index = $_REQUEST['index'];
        $sql = "select * from tb_balance_accounts where customer_id = '".$artist_id."' and id = '".$round_id."';";
        $result = mysql_query($sql);
        if ($rows = mysql_fetch_object($result)) {
                $plus_option = $rows->plus_option;
		$plus_array = explode("|", $plus_option);

		$new_plus_option = "";
		for ($dba_i = 0 ; $dba_i < sizeof($plus_array) ; $dba_i++) {
			if (($dba_i+1) == intval($index)) {
				continue;
			}

		        if (strlen($new_plus_option) > 3) {
                	        $new_plus_option = $new_plus_option."|".$plus_array[$dba_i];
                	} else {
                        	$new_plus_option = $plus_array[$dba_i];
                	}
		}
                $u_sql = "update tb_balance_accounts set plus_option = '".$new_plus_option."' where customer_id = '".$artist_id."' and id = '".$round_id."';";
                $u_result = mysql_query($u_sql);
        }
} else if ($type == "delminus") {
	$index = $_REQUEST['index'];
        $sql = "select * from tb_balance_accounts where customer_id = '".$artist_id."' and id = '".$round_id."';";
        $result = mysql_query($sql);
        if ($rows = mysql_fetch_object($result)) {
                $minus_option = $rows->minus_option;
		$minus_array = explode("|", $minus_option);

		$new_minus_option = "";
                for ($dba_i = 0 ; $dba_i < sizeof($minus_array) ; $dba_i++) {
                        if (($dba_i+1) == intval($index)) {
                                continue;
                        }

                        if (strlen($new_minus_option) > 3) {
                                $new_minus_option = $new_minus_option."|".$minus_array[$dba_i];
                        } else {
                                $new_minus_option = $minus_array[$dba_i];
                        }
                }

                $u_sql = "update tb_balance_accounts set minus_option = '".$new_minus_option."' where customer_id = '".$artist_id."' and id = '".$round_id."';";
                $u_result = mysql_query($u_sql);
        }
}
?>

<?php include "../include/buttom.php"; ?>
<script>
	location.href = 'manage_balance_accounts.php?round=<?=$round_id?>&artist_id=<?=$artist_id?>';
</script>
