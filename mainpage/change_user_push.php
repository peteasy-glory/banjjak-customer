<?php include "../include/top.php"; ?>
<?php include "../include/Push.class.php"; ?>

<?php
$push = new Push();

$user_id = $_SESSION['gobeauty_user_id'];

$is_push = $push->is_push($user_id);
if ($is_push == 1) {
	$push->unset_push($user_id);
} else {
	$push->set_push($user_id);
}
?>

<?php include "../include/bottom.php"; ?>
