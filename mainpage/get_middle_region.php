<?php
include "../include/db_connection.php";

	$user_id = $_SESSION['gobeauty_user_id'];

	$top_region = $_POST["top_region"];
	$artist_id = $_POST["artist_id"];
	$type = $_POST["type"];

	$sql = "select distinct middle from tb_region where top = '".$top_region."';";
	if ($type == "open_flag") {
		$sql = "select distinct middle from tb_region where top = '".$top_region."' and open_flag = true";
	}
	//$sql = "select distinct tr.middle from tb_region tr, tb_working_region twr where twr.customer_id = '".$artist_id."' and twr.region_id = tr.id and top = '".$top_region."' and open_flag = true";
	$result = mysql_query($sql);
	for ($index=0;$top_datas = mysql_fetch_object($result);$index++) {
		if ($index != 0) {
			echo ",";
		}
		echo $top_datas->middle;
	}

closeDB();
?>

