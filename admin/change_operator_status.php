<?php include "../include/top.php"; ?>
<?php include "../include/Operator.class.php"; ?>

<?php
$region = new Operator();

$status = $_REQUEST['type'];
$middle = $_REQUEST['customer_id'];

if ($status == '1') {
	echo "unset";
	$region->unset_region($top, $middle);
} else {
	echo "set";
	$region->set_region($top, $middle);
}
?>

<?php include "../include/bottom.php"; ?>
