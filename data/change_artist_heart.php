<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$heart = new Heart();

$artist_id = $_REQUEST['artist_id'];
echo $artist_id;
$customer_id = $_REQUEST['customer_id'];
echo $customer_id;

$is_like = $heart->is_like_artist($customer_id, $artist_id);
if ($is_like) {
	echo "unset";
	$heart->unset_heart($customer_id, $artist_id);
} else {
	echo "set";
	$heart->set_heart($customer_id, $artist_id);
}
?>
