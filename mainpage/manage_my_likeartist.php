<?php include "../include/top.php"; ?>
<?php include "../include/Heart.class.php"; ?>
<?php
$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}
?>

<?php
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
?>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<div class="top_menu">
    <div class="top_back"><a href="<?= $mainpage_directory ?>/mainpage_my_menu.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div class="top_title">
        <p>나의 단골 펫샵</p>
    </div>
</div>

<script>
    function change_heart(customer_id, artist_id) {

        $.MessageBox({
            buttonDone: "삭제",
            buttonFail: "취소",
            message: "삭제하시겠습니까?"
        }).done(function() {
            $.ajax({
                url: '<?= $artist_directory ?>/change_artist_heart.php',
                data: {
                    customer_id: customer_id,
                    artist_id: artist_id
                },
                type: 'POST',
                success: function(data) {
                    location.reload();
                },
                error: function(xhr, status, error) {}
            });
        }).fail(function() {});

    }
</script>

<center id="manage_my_likeartist">
    <?php
    $login_insert_sql = "select * from tb_like_artist tur, tb_shop ts where tur.customer_id = '" . $user_id . "' and tur.artist_id = ts.customer_id;";
    $result = mysql_query($login_insert_sql);
    for ($ch_index = 0; $result_datas = mysql_fetch_object($result); $ch_index++) {
        $artist_id = $result_datas->artist_id;
        $update_time = $result_datas->update_time;
        $artist_name = $result_datas->name;
        $artist_photo = $result_datas->photo;

        $heart = new Heart();
        $is_like = $heart->is_like_artist($user_id, $artist_id);
        ?>
     
		<table  class="my_reservation">
			<tbody>
				<tr>
					<td width="20%">
						<div class="artist_img" onclick="location.href='<?= $artist_directory ?>/?artist_name=<?= urlencode($artist_name) ?>&backurl=<?= urlencode($_SERVER['REQUEST_URI']) ?>'">
							<div style="height:50px;width:50px;top:5px;right:10px;background-image:url(<?= $artist_photo ?>);background-size:cover;border-radius:20%"></div>
						</div>
					</td>
					<td width="58%">
						<div class="artist_name" onclick="location.href='<?= $artist_directory ?>/?artist_name=<?= urlencode($artist_name) ?>&backurl=<?= urlencode($_SERVER['REQUEST_URI']) ?>'"><b><?= $artist_name ?></b></div>
					</td>
					<td width="12%">
						<div class="artist_like" style=""><img src="<?= $image_directory ?>/btn_like_o.png" width="28px" onclick="javascript:change_heart('<?= $user_id ?>', '<?= $artist_id ?>');"></div>
					</td>
				</tr>
			</tbody>
		</table>
    <?php
    }
    if ($ch_index == 0) {
        ?>
        <br>
        <br>
        <img src="<?= $image_directory ?>/likeartist.png" width="20%" style="opacity:0.5;">
        <br>
        <br>
        <br>
        <font style="font-size:18px;">지정된 단골 아티스트가 없습니다.</font>
    <?php
    }

    closeDB();
    ?>
	
</center>

<?php include "../include/bottom.php"; ?>