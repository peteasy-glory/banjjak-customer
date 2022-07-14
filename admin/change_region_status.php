<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login.php");
include($_SERVER['DOCUMENT_ROOT']."/include/skin/header.php");
include($_SERVER['DOCUMENT_ROOT']."/include/Region.class.php");
?>

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

