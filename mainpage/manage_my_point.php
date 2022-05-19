<?php include "../include/top.php"; ?>
<?php include "../include/Point.class.php"; ?>
<?php
$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}
?>
<?php
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
$sequence = 0;
if ($_REQUEST['sequence']) {
    $sequence = $_REQUEST['sequence'];
}
?>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<style>

</style>
<div class="top_menu">
	<div class="top_back"><a href="<?= $mainpage_directory ?>/mainpage_my_menu.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
	<div class="top_title">
		<p>포인트</p>
	</div>
	<div class="top_charge"><a class="gobeauty_small_button4" href="<?= $mainpage_directory ?>/payment_point.php">포인트 충전</a></div>
</div>
<div id="manage_my_point">
	<div class="point_wrap">
		<div class="point_big_title"> 현재 포인트</div>
		<div class="point_score">
	<?php
	$point = new Point;
	$result = $point->select_point($user_id);
	if ($result == true) {
	echo number_format($point->get_point());
	} else {
	echo "0";
	}
	?>
	P	</div>
	</div>
	<div class="point_section">
		<div>
			<div class="point_menu"> <a href="?sequence=0">
					<?php
					if ($sequence == 0) {
						echo "<div style='height:40px; line-height: 40px;  width:100%; background-color: #f5bf2e; color:#ffffff;'>";
					} else {
						echo "<div style='height:40px; line-height: 40px;  width:100%; border-bottom:0px solid #999999;color:#999999;'>";
					}
					?>
					적립 내역 </div>
			</a>
		</div>
		<div class="point_menu"> <a href="?sequence=1">
				<?php
				if ($sequence == 1) {
					echo "<div style='height:40px; line-height: 40px;  width:100%; background-color: #f5bf2e; color:#ffffff;'>";
				} else {
					echo "<div style='height:40px; line-height: 40px;  width:100%;border-bottom:0px solid #999999;color:#999999;'>";
				}
				?>
				사용 내역 </div>
		</a>
	</div>
	<div class="point_menu"> <a href="?sequence=2">
			<?php
			if ($sequence == 2) {
				echo "<div style='height:40px; line-height: 40px;  width:100%; background-color: #f5bf2e; color:#ffffff;'>";
			} else {
				echo "<div style='height:40px; line-height: 40px;  width:100%;border-bottom:0px solid #999999;color:#999999;'>";
			}
			?>
			취소 내역 </div>
	</a>
	</div>
	</div>
	</div>
	<?php
	if ($sequence == 0) {
		$total_purchase = 0;
		$select_sql = "select adding_point from tb_point_history where customer_id = '" . $user_id . "' and type = 'BUY';";
		$select_result = mysql_query($select_sql);
		for ($ch_index = 0; $result_datas = mysql_fetch_object($select_result); $ch_index++) {
			$total_purchase += $result_datas->adding_point;
		}
		$total_accumulate = 0;
		$select_sql = "select adding_point from tb_point_history where customer_id = '" . $user_id . "' and type = 'ACCUMLATE';";
		$select_result = mysql_query($select_sql);
		for ($ch_index = 0; $result_datas = mysql_fetch_object($select_result); $ch_index++) {
			$total_accumulate += $result_datas->adding_point;
		}
		$total_event = 0;
		$select_sql = "select adding_point from tb_point_history where customer_id = '" . $user_id . "' and type = 'EVENT';";
		$select_result = mysql_query($select_sql);
		for ($ch_index = 0; $result_datas = mysql_fetch_object($select_result); $ch_index++) {
			$total_event += $result_datas->adding_point;
		}
		?>
		<div class="section_1">
			<div class="point_title">총 적립 포인트</div>
			<div class="s1_article">
				<div width='100%'>
					<div>
						<div class="article_title">구매 포인트 : </div>
						<div align='right'>
							<?= number_format($total_purchase) ?>
							P</div>
					</div>
				</div>
			</div>
			<div class="s1_article">
				<div width='100%'>
					<div>
						<div class="article_title">적립 포인트 : </div>
						<div align='right'>
							<?= number_format($total_accumulate) ?>
							P</div>
					</div>
				</div>
			</div>
			<div class="s1_article">
				<div width='100%'>
					<div>
						<div class="article_title">이벤트 포인트 : </div>
						<div align='right'>
							<?= number_format($total_event) ?>
							P</div>
					</div>
				</div>
			</div>
			<!-- 포인트장려이벤트 배너 시작 -->
			<div class="s1_article" style="margin-top:20px;">
				<div width='100%'>
					<a href="<?=$mainpage_directory ?>/view_event5.php?event_back=1"><img src="../images/middle_banner_1_1.jpg" style="width:100%;"></a>
				</div>
			</div>
			<!-- 포인트장려이벤트 배너 끝 -->
			<div class="article_wrap">
				<div class="point_title">상세 내역</div>
				<div class="s1_article">
					<?php
						$select_sql = "select * from tb_point_history where customer_id = '" . $user_id . "' and type <> 'SPEND' order by update_time desc;";
						$select_result = mysql_query($select_sql);
						for ($ch_index = 0; $result_datas = mysql_fetch_object($select_result); $ch_index++) {
							$event_name = $result_datas->event_name;
							$type = $result_datas->type;
							$adding_point = $result_datas->adding_point;
							$payment_log_seq = $result_datas->payment_log_seq;
							$update_time = $result_datas->update_time;
							if ($type == 'EVENT') {
								?>
							<div style="height:5px;"></div>
							<div width='100%' style="font-size:13px;padding:5px;border:1px solid #999999;"> <b>
									<?= $update_time ?>
								</b> / EVENT<br>
								적립 :
								<?= number_format($adding_point) ?>
								P </div>
						<?php
								 } else if ($type == 'ACCUMLATE') {
									?>
							<div style="height:5px;"></div>
							<div width='100%' style="font-size:13px;padding:5px;border:1px solid #999999;"> <b>
									<?= $update_time ?>
								</b> /
								<?php
									$sql2 = "select ts.name from tb_payment_log tpl, tb_shop ts where tpl.payment_log_seq = '" . $payment_log_seq . "' and tpl.artist_id = ts.customer_id and tpl.is_cancel = 0;";
									$result2 = mysql_query($sql2);
									if ($result_datas2 = mysql_fetch_object($result2)) {
										echo $result_datas2->name;
									} else {
										echo "적립";
									}
								?>
								<br>
								적립 :
								<?= number_format($adding_point) ?>
								P </div>
						<?php
								} else if ($type == 'BUY') {
									?>
							<div style="height:5px;"></div>
							<div width='100%' style="font-size:13px;padding:5px;border:1px solid #999999;"> <b>
									<?= $update_time ?>
								</b> / 구매<br>
								적립 :
								<?= number_format($adding_point) ?>
								P </div>
						<?php
								}
							}
							if ($ch_index == 0) {
								?>
						<div style="height:5px;"></div>
						<div width='100%' style="font-size:13px;padding:5px;border:1px solid #999999;"> 내역이 없습니다. </div>
					<?php
						}
						?>
				</div>
			</div>





		<?php
		} else if ($sequence == 1) {
			$data = array();
			$total_spend = 0;
			$sql = "
				SELECT *
				FROM tb_point_history
				WHERE customer_id = '".$user_id."'
					AND type = 'SPEND'
				ORDER BY update_time DESC
			";
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)){
				if($row["payment_log_seq"] != ""){
					$data["point_history"][$row["point_history_seq"]] = $row;

					$tmp_arr = array();
					$tmp_arr = explode("_", $row["order_id"]);
					if($tmp_arr[0] == "product"){ // 상품구매
						$sql = "
							SELECT *
							FROM tb_item_payment_log
							WHERE ip_log_seq = '".$row["payment_log_seq"]."'
								AND order_num = '".$tmp_arr[1]."'
							LIMIT 0, 1
						";
						$result2 = mysql_query($sql);
						$row2 = mysql_fetch_assoc($result2);
						$row_cnt = mysql_num_rows($result2);
						if($row_cnt > 0){
							$data["html"][] = array(
								"update_time" => $row["update_time"],
								"artist_name" => "[상품]".$row2["product_name"],
								"total_price" => $row2["product_price"]+$row2["shipping_price"],
								"spend_point" => $row["spending_point"],
								"result_price" => $row2["product_price"]+$row2["shipping_price"]-$row["spending_point"]
							);
							$total_spend += $row["spending_point"];
						}
					}else{
						$sql = "
							SELECT pl.*, sh.name AS artist_name
							FROM tb_payment_log AS pl
								INNER JOIN tb_shop AS sh ON pl.artist_id = sh.customer_id
							WHERE pl.payment_log_seq = '".$row["payment_log_seq"]."'
								AND pl.is_cancel = '0'
							LIMIT 0, 1
						";
						$result2 = mysql_query($sql);
						$row2 = mysql_fetch_assoc($result2);
						$row_cnt = mysql_num_rows($result2);
						if($row_cnt > 0){
							$data["html"][] = array(
								"update_time" => $row["update_time"],
								"artist_name" => $row2["artist_name"],
								"total_price" => $row2["total_price"],
								"spend_point" => $row["spending_point"],
								"result_price" => $row2["total_price"]-$row["spending_point"]
							);
							$total_spend += $row["spending_point"];
						}
					}
				}
			}
		?>
			<div class="section_2">
				<div class="point_title" style="float:left;">총 사용포인트</div>
				<div class="score">
					<div width='100%'>
						<div>
							<div align='right' style="font-family: 'NanumGothic';font-weight: bold;font-size:14px;">
								<?= number_format($total_spend) ?>
								P</div>
						</div>
					</div>
				</div>
				<div class="point_title" style="margin-top: 16px;">상세 내역</div>
				<div class="s1_article">
		<?php
			foreach($data["html"] AS $key => $value){
		?>
					<div style="height:5px;"></div>
					<div width='100%' style="font-size:13px;padding:5px;border:1px solid #999999;"> <b>
							<?= $value["update_time"] ?>
						</b> /
						<?= $value["artist_name"] ?>
						<br>
						<div width='100%' style="text-align:right"> 결제 예정금액 :
							<?= number_format($value["total_price"]) ?>
							원<br>
							사용 :
							<?= number_format($value["spend_point"]) ?>
							P<br>
							최종 결제금액 :
							<?= number_format($value["result_price"]) ?>
							원<br>
						</div>
					</div>
		<?php
			}
		?>
				</div>
			</div>
		<?php
			//echo "<pre>";
			//print_r($data);
			//echo "</pre>";
		} else {
			$total_spend = 0;
			$select_sql = "select spending_point from tb_point_history where customer_id = '" . $user_id . "' and type = 'CANCEL';";
			$select_result = mysql_query($select_sql);
			for ($ch_index = 0; $result_datas = mysql_fetch_object($select_result); $ch_index++) {
				$total_spend += $result_datas->spending_point;
			}
			?>
			<div class="section_2">
				<div class="point_title" style="float:left;">총 취소 반환 포인트</div>
				<div class="score">
					<div>
						<div>
							<div align='right' style="font-family: 'NanumGothic';font-weight: bold;font-size:14px;">
								<?= number_format($total_spend) ?>
								P</div>
						</div>
					</div>
				</div>
				<div class="point_title" style="margin-top: 16px;">상세 내역</div>
				<div class="s1_article">
					<?php
						$select_sql = "select * from tb_point_history where customer_id = '" . $user_id . "' and type = 'CANCEL' order by update_time desc;";
						$select_result = mysql_query($select_sql);
						for ($ch_index = 0; $result_datas = mysql_fetch_object($select_result); $ch_index++) {
							$spending_point = $result_datas->spending_point;
							$payment_log_seq = $result_datas->payment_log_seq;
							$update_time = $result_datas->update_time;
							$artist_id = $result_datas->artist_id;
							$artist_name = $result_datas->name;
							$total_price = $result_datas->total_price;
							$type = $result_datas->type;
							?>
						<div style="height:5px;"></div>
						<div width='100%' style="font-size:13px;padding:5px;border:1px solid #999999;"> <b>
								<?= $update_time ?>
							</b> <br>
							<div width='100%' style="text-align:right"> 취소 반환 포인트 :
								<?= number_format($spending_point) ?>
								P<br>
							</div>
						</div>
					<?php
						}
						if ($ch_index == 0) {
							?>
						<div style="height:5px;"></div>
						<div width='100%' style="font-size:13px;padding:5px;border:1px solid #999999;"> 내역이 없습니다. </div>
					<?php
						}
						?>
				</div>
			<?php
			}
			?>
			</div>
		</div>
</div>
    <?php
    closeDB();
    ?>
    <?php include "../include/bottom.php"; ?>