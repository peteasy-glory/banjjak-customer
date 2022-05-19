<?php include "../include/top.php"; ?>

<?php
	$admin_notice_2_artist=$_POST["admin_notice_2_artist"];

	$sql = "select * from tb_admin_notice;";
	$result = mysql_query($sql);
	if ($rows = mysql_fetch_object($result)) {
		$login_insert_sql = "update tb_admin_notice set notice = '".$admin_notice_2_artist."', update_time = now();";
		$result1 = mysql_query($login_insert_sql);
	} else {
                $login_insert_sql = "insert into tb_admin_notice (customer_id, notice, update_time) values ('pickmon@pickmon.com', '".$admin_notice_2_artist."', now());";
                $result1 = mysql_query($login_insert_sql);
	}

	closeDB();
?>

<script language="javascript">
	$.MessageBox({
		buttonDone      : "확인",
		message         : "완료"
	}).done(function(){
		location.href="<?=$admin_directory?>/index.php";
	});
</script>

<?php include "../include/bottom.php"; ?>
