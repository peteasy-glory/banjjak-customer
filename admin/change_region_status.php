<?php include "../include/top.php"; ?>
<?php include "../include/Region.class.php"; ?>

<?php
$region = new Region();

$status = $_REQUEST['type'];
$top = $_REQUEST['top_region'];
$middle = $_REQUEST['middle_region'];

if ($status == '1') {
	echo "unset";
	$region->unset_region($top, $middle);
} else {
	echo "set";
	$region->set_region($top, $middle);
}
?>

<?php include "../include/bottom.php"; ?>
