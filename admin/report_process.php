<?php
include "../include/top.php";
include "../include/Report.class.php";

$report = new Report();

$key = $_REQUEST['key'];
$review_seq = $_REQUEST['review_seq'];
$review_customer_id = $_REQUEST['review_customer_id'];

if ($key == 'deleteReport') {
	$report->delete_report ($review_seq, $review_customer_id);
} else if ($key == 'deleteReview') {
	$report->delete_review ($review_seq, $review_customer_id);
}

include "../include/bottom.php";
?>

<script>
	location.href = 'manage_report.php';
</script>
