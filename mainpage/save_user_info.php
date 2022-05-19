<?php
include "../include/top.php";

	$user_id = $_SESSION['gobeauty_user_id'];

	$user_nickname = $_REQUEST["user_nickname"];

	$sql = "update tb_customer set nickname = '".$user_nickname."' where id = '".$user_id."'";
	$result = mysql_query($sql);
?>
	<script language="javascript">
		<?if(!$result){?>
			location.href="index.php";
		<?}else{?>
			location.href="index.php";
		 <?}?>
	</script>
<?
include "../include/bottom.php";
?>

