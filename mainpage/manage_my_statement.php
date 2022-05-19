<?php include "../include/top.php"; ?>

<?php
$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}
?>

<?php
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

$review_images_array;
?>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">

<div class="top_menu">
    <div class="top_back"><a href="<?= $mainpage_directory ?>/mainpage_my_menu.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div class="top_title">
        <p>이용 상담 신청내역</p>
    </div>
</div>
<script>
    function toggle_notice(idx) {
        if ($("#hiddenList" + idx).is(":visible")) {
            $("#hiddenList" + idx).slideUp();
        } else {
            $("#hiddenList" + idx).slideDown();
        }
    }
</script>

<div id="manage_my_statement">
	<div class="guide_wrap">
		<div class="guide">
			<ul id="guideul">
				<li>상담 완료 : 해당샵에 예약 가능한 상태</li>
				<li>상담 대기 : 상담을 기다리고 있는 상태</li>
				<li>상담 취소 : 샵의 사정으로 예약이 불가한 상태</li>
			</ul>
			<ul id="hiddenList1" class="hiddenList">
				<li>*최초 이용샵의 경우 이용상담 신청 후 예약이 가능합니다.</li>
				<li>*최대 12시간 이내에 예약 상담이 이루어지며 이후 시간 선택 및 예약이 가능해집니다.<br />(12시간 이후 상담이 이루어지지 않으면 자동으로 상담이 취소됩니다.)</li>
			</ul>
		</div>
		<div class="spreadBtn_wrap">
			<a class="spreadBtn" onClick="toggle_notice(1)"><img src="../images/btn_seemore.png"></a>
		</div>
	</div>
	<center class="review_wrap">
		<?php
		$login_insert_sql = "SELECT tpl.artist_id, tpl.update_time, tpl.approval, ts.photo AS artist_photo, ts.name
		FROM tb_payment_log tpl, tb_shop ts, tb_mypet tm
		WHERE tpl.customer_id = '{$user_id}' 
		AND tpl.approval<>'1' 
		AND tm.pet_seq = tpl.pet_seq
		AND ts.customer_id = tpl.artist_id 
		ORDER BY tpl.update_time DESC;";
		// error_log('----- $login_insert_sql : '.$login_insert_sql);
		$result = mysql_query($login_insert_sql);

		for ($opinionkey = 0; $result_datas = mysql_fetch_object($result); $opinionkey++) {
			$artist_id = $result_datas->artist_id;
			$approval = $result_datas->approval;
			$update_time = $result_datas->update_time;
			$artist_photo = $result_datas->artist_photo;
			$artist_name = $result_datas->name;

			// error_log('----- index(' . $opinionkey . ') : ' . json_encode($result_datas, JSON_PRETTY_PRINT));
		?>

			<div class="my_reservation">
				<div class="review_subwrap">
					<div>
						<div style=" float: left; height:40px;width:40px;top:5px;right:10px;background-image:url(<?= $artist_photo ?>);background-size:cover;border-radius:20%;"></div>
						<div class="section_02">
							<div><?= $artist_name ?></div>
							<div><?php
									$diff_12hours_time = strtotime("-12 hours");
									$update_time = strtotime($update_time);
									$str_update_time = date('Y년 m월 d일 H시 i분', $update_time);
									echo $str_update_time;
									?>
							</div>
						</div>
					</div>
					<div>
						<?php
						if ($update_time > $diff_12hours_time) {
							if ($approval == '0') {
						?>
								<div class="counseling_btn2">
									<font>상담 대기중</font>
								</div>
							<?php
							} elseif ($approval == '2') {
							?>
								<div class="counseling_btn">
									<font>상담 완료</font>
								</div>
							<?php
							} elseif ($approval == '3') {
							?>
								<div class="counseling_btn3">
									<font>상담 취소</font>
								</div>
							<?php
							}
						} else {
							//----- 상담 신청에 대한 응대 유효시간이 지난 후 [상담 완료] 처리 가능성을 대비하여 추가
							if ($approval == '2') {
							?>
								<div class="counseling_btn">
									<font>상담 완료</font>
								</div>
							<?php
							} else {
							?>
								<div class="counseling_btn3">
									<font>상담 취소</font>
								</div>
						<?php
							}
						}
						?>
					</div>
				</div>
			</div>
		<?php
		}
		?>
		<?php
		if ($opinionkey == 0) {
		?>
			<br>
			<br>
			<br>
			<br>
			<br>
			<div class="review_none"><img src="../images/new_statement_none_1.jpg"></div>
		<?php
		}

		closeDB();
		?>
	</center>
</div>
<?php include "../include/bottom.php"; ?>