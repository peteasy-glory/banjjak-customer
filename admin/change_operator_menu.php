<?php include "../include/top.php"; ?>

<?php
$status = $_REQUEST['type'];
$middle = $_REQUEST['name_id'];

if ($status == '1') {
	$sql = "update tb_operator_management set ".$middle." = 0;";
	$result = mysql_query($sql);
} else {
        $sql = "update tb_operator_management set ".$middle." = 1;";
        $result = mysql_query($sql);
}
?>

<?php include "../include/bottom.php"; ?>
