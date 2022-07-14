<?php
include "../include/configure.php";
include "../include/session.php";
include "../include/db_connection.php";
include "../include/php_function.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

$r_year = ($_GET["year"])? $_GET["year"] : DATE("Y");

$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
$str_search = $_REQUEST['str_search'];
?>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width" />

<?php
$url = $_SERVER['REQUEST_URI'];

//특정 페이지에서 숫자를 전화번호로 인식하여 보이지 않는 문제 때문에 safari 내의 전화번호 검출 기능 off
if (strpos($url, "manage_my_reservation") !== false) {
?>
    <meta name="format-detection" content="telephone=no">
<?php
}
?>

<style>
    @font-face {
        font-family: 'SCDream2';
        src: url("../fonts/SCDream2.otf");
    }

    * {
        font-family: 'SCDream2';
        font-weight: bold;
    }

    input {
        -webkit-appearance: none;
        border-radius: 0;
    }

    .top_menu {
        height: 51px;
        position: relative;
    }

    .top_title {
        width: 100%;
        height: 19px;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        padding: 15px 0px 15px 0px;
        display: table;
        border-bottom: 0.5px solid #e1e1e1;
    }

    .top_title p {
        margin: 0px;
    }

    .top_back {
        top: 13px;
        position: absolute;
        bottom: 11px;
        left: 10px;
    }

	span.comment { font-size: 10px; }
	span.counseling_data_toggle_btn { position: absolute; right: 5px; top: -25px; display: block; border: 1px solid #b2d5ba; width: 50px; height: 50px; line-height: 50px; text-align: center; border-radius: 30px; background-color: #c3e6cb; color: #155724; font-size: 14px; }
	ul.counseling_data { display: none; width: 100%; list-style: none; padding: 0px; margin: 0px; margin-top: 10px; }
	ul.counseling_data.on { display: block; }
	ul.counseling_data li { position: relative; margin: 5px; border: 1px solid #ccc; border-radius: 5px; }
	ul.counseling_data li div { font-size: 14px; border-radius: 5px; }
	ul.counseling_data li div span.title { display: inline-block; background-color: #c3e6cb; color: #155724; width: 60px; }
	ul.counseling_data li div.approval_0 { border-left: 5px solid #f5a82e; }
	ul.counseling_data li div.approval_1 { border-left: 5px solid #000; }
	ul.counseling_data li div.approval_2 { border-left: 5px solid #155724; }
	ul.counseling_data li div.approval_3 { border-left: 5px solid #f00; }
	ul.counseling_data li div span.approval_0 { color: #f5a82e; }
	ul.counseling_data li div span.approval_1 { color: #000; }
	ul.counseling_data li div span.approval_2 { color: #155724; }
	ul.counseling_data li div span.approval_3 { color: #f00; }
	ul.counseling_data li div span.alert { color: #f00; font-size: 12px; padding: 0px; }

	.pet_month_wrap { width: 100%; overflow-x: scroll; }
	.pet_month_wrap::-webkit-scrollbar { display: none; }
	.pet_month_wrap .pet_month caption {caption-side: top; }
	.pet_month_wrap .pet_month caption .pet_month_search_wrap { text-align: right; }
	.pet_month_wrap .pet_month { width: 100%; text-align: center; border-collapse: collapse; }
	.pet_month_wrap .pet_month tr th { border: 1px solid #ccc; padding: 5px; background-color: c3e6cb; color: #155724; min-width: 65px; }
	.pet_month_wrap .pet_month tr td { border: 1px solid #ccc; padding: 2px 5px; }
	.pet_month_wrap .pet_month tr td.lft { text-align: left; }
	.pet_month_wrap .pet_month tr td.rht { text-align: right; }
</style>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<div class="top_menu">
    <div class="top_back"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div class="top_title">
        <p>등록 현황</p>
    </div>
</div><br>
<?php
$login_insert_sql = "SELECT * FROM tb_customer WHERE id = '" . $user_id . "' AND (admin_flag = true OR operator_flag = true)";
$result = mysql_query($login_insert_sql);

if ($result_datas = mysql_fetch_object($result)) {
?>
    <?php
    $pet_sql = "SELECT count(pet_seq) AS cnt_all, 
                    (SELECT count(pet_seq) FROM tb_mypet WHERE customer_id IS NOT NULL) AS cnt_customer_pet,
                    (SELECT count(pet_seq) FROM tb_mypet WHERE customer_id IS NULL)as cnt_tmp_user_pet
                FROM tb_mypet 
                ORDER BY pet_seq DESC;";
    $pet_result = mysql_query($pet_sql);
    if ($pet_result_datas = mysql_fetch_object($pet_result)) {
        $cnt_all = $pet_result_datas->cnt_all;
        $cnt_customer_pet = $pet_result_datas->cnt_customer_pet;
        $cnt_tmp_user_pet = $pet_result_datas->cnt_tmp_user_pet;
    }

    $payment_sql = "SELECT count(payment_log_seq) AS cnt_payment_all, 
                        (SELECT count(payment_log_seq) FROM tb_payment_log WHERE DATE_FORMAT(buy_time,'%Y-%m-%d') = date_sub(curdate(), interval 1 day)) AS cnt_payment_yesterday,
                        (SELECT count(payment_log_seq) FROM tb_payment_log WHERE DATE_FORMAT(buy_time,'%Y-%m-%d') = curdate()) AS cnt_payment_today,
                        (SELECT count(payment_log_seq) FROM tb_payment_log WHERE DATE_FORMAT(buy_time,'%Y-%m-%d') = curdate() AND pay_type NOT IN ('pos-card','pos-cash','')) AS cnt_payment_today_of_customer,
                        (SELECT count(payment_log_seq) FROM tb_payment_log WHERE DATE_FORMAT(buy_time,'%Y-%m') = DATE_FORMAT(date_sub(curdate(), interval 1 month),'%Y-%m')) AS cnt_payment_lastmonth,
                        (SELECT count(payment_log_seq) FROM tb_payment_log WHERE DATE_FORMAT(buy_time,'%Y-%m') = DATE_FORMAT(curdate(),'%Y-%m') AND pay_type NOT IN ('pos-card','pos-cash','')) AS cnt_payment_month_of_customer,
                        (SELECT count(payment_log_seq) FROM tb_payment_log WHERE DATE_FORMAT(buy_time,'%Y-%m') = DATE_FORMAT(curdate(),'%Y-%m')) AS cnt_payment_month
                    FROM tb_payment_log;";
    $payment_result = mysql_query($payment_sql);
    if ($payment_result_datas = mysql_fetch_object($payment_result)) {
        $cnt_payment_all = $payment_result_datas->cnt_payment_all;
        $cnt_payment_yesterday = $payment_result_datas->cnt_payment_yesterday;
        $cnt_payment_today = $payment_result_datas->cnt_payment_today;
        $cnt_payment_lastmonth = $payment_result_datas->cnt_payment_lastmonth;
        $cnt_payment_month = $payment_result_datas->cnt_payment_month;
        $cnt_payment_today_of_customer = $payment_result_datas->cnt_payment_today_of_customer;
        $cnt_payment_month_of_customer = $payment_result_datas->cnt_payment_month_of_customer;

        $diff_payment_day = $cnt_payment_today - $cnt_payment_yesterday;
        $diff_payment_month = $cnt_payment_month - $cnt_payment_lastmonth;

        if($diff_payment_day < 0){
            $class_payment_day = "text-danger";
        }else{
            $class_payment_day = "text-primary";
        }

        if($diff_payment_month < 0){
            $class_payment_month = "text-danger";
        }else{
            $class_payment_month = "text-primary";
        }
    }

    $review_sql = "SELECT count(review_seq) AS cnt_review_all, 
                        (SELECT count(review_seq) FROM tb_usage_reviews WHERE DATE_FORMAT(reg_time,'%Y-%m-%d') = curdate()) AS cnt_review_today,
                        (SELECT count(review_seq) FROM tb_usage_reviews WHERE DATE_FORMAT(reg_time,'%Y-%m') = DATE_FORMAT(curdate(),'%Y-%m')) AS cnt_review_month
                    FROM tb_usage_reviews;";
    $review_result = mysql_query($review_sql);
    if ($review_result_datas = mysql_fetch_object($review_result)) {
        $cnt_review_all = $review_result_datas->cnt_review_all;
        $cnt_review_today = $review_result_datas->cnt_review_today;
        $cnt_review_month = $review_result_datas->cnt_review_month;
    }

    $counseling_sql = "SELECT count(payment_log_seq) AS cnt_counseling_all, 
                            (SELECT count(payment_log_seq) FROM tb_payment_log WHERE DATE_FORMAT(update_time,'%Y-%m-%d') = curdate() AND approval <> 1 AND product_type != 'A') AS cnt_counseling_today,
							(SELECT count(payment_log_seq) FROM tb_payment_log WHERE DATE_FORMAT(update_time,'%Y-%m-%d') = SUBDATE(CURDATE(), INTERVAL 1 DAY) AND approval <> 1 AND product_type != 'A') AS cnt_counseling_prev,
                            (SELECT count(payment_log_seq) FROM tb_payment_log WHERE DATE_FORMAT(update_time,'%Y-%m') = DATE_FORMAT(curdate(),'%Y-%m') AND approval <> 1 AND product_type != 'A') AS cnt_counseling_month
                        FROM tb_payment_log
                        WHERE approval <> 1;";
    $counseling_result = mysql_query($counseling_sql);
    if ($counseling_result_datas = mysql_fetch_object($counseling_result)) {
        $cnt_counseling_all = $counseling_result_datas->cnt_counseling_all;
        $cnt_counseling_today = $counseling_result_datas->cnt_counseling_today;
		$cnt_counseling_prev = $counseling_result_datas->cnt_counseling_prev;
        $cnt_counseling_month = $counseling_result_datas->cnt_counseling_month;
    }

	$data = array();
	$approval_arr = array("상담대기", "상담취소(자동취소)", "상담완료", "예약거부");
	$sql = "
		SELECT pl.*, sh.name AS shop_name, cu.enable_flag, mp.tmp_yn
		FROM tb_payment_log AS pl 
			INNER JOIN tb_shop AS sh ON pl.artist_id = sh.customer_id 
			LEFT OUTER JOIN tb_customer AS cu ON pl.customer_id = cu.id
			LEFT OUTER JOIN tb_mypet AS mp ON pl.pet_seq = mp.pet_seq
		WHERE pl.update_time BETWEEN '".DATE("Y-m-d 00:00:00", strtotime("-1 day"))."' AND '".DATE("Y-m-d 23:59:59")."'
			AND pl.approval <> 1
			AND pl.product_type != 'A'
			AND mp.tmp_yn = 'N'
		ORDER BY pl.update_time DESC
	";
	$result = mysql_query($sql);
	$counseling_today_cnt = mysql_num_rows($result);
	while($row = mysql_fetch_array($result)){
		$data[] = $row;
	}

	$pet_month_tmp_arr = [0,0,0,0,0,0,0,0,0,0,0,0,0];
	$sql = "
		SELECT month, SUM(a.cnt) AS cnt
		FROM (
			SELECT mp.tmp_seq, MONTH(tu.reg_date) as month, COUNT(*) AS cnt
			FROM tb_mypet AS mp
				INNER JOIN tb_tmp_user AS tu ON mp.tmp_seq = tu.tmp_seq
			WHERE YEAR(tu.reg_date) = '".$r_year."'
			GROUP BY tmp_seq, MONTH(tu.reg_date)
		) AS a
		GROUP BY month		
	";
	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result)){
		$pet_month_tmp_arr[$row["month"]] = $row["cnt"];
	}
	$pet_month_customer_arr = [0,0,0,0,0,0,0,0,0,0,0,0,0];
	$sql = "
		SELECT month, SUM(a.cnt) AS cnt
		FROM (
			SELECT mp.tmp_seq, MONTH(cu.registration_time) as month, COUNT(*) AS cnt
			FROM tb_mypet AS mp
				INNER JOIN tb_customer AS cu ON mp.customer_id = cu.id
			WHERE YEAR(cu.registration_time) = '".$r_year."'
			GROUP BY tmp_seq, MONTH(cu.registration_time)
		) AS a
		GROUP BY month		
	";
	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result)){
		$pet_month_customer_arr[$row["month"]] = $row["cnt"];
	}

    ?>
    <div class="container">
        <div class="text-right">기준 시각 : <?=date('Y년 m월 d일 H시 i분')?></div>
        <ul class="list-group">
            <li class="list-group-item list-group-item-success text-center">펫 등록 현황</li>
            <li class="list-group-item">정회원 펫 : <?=number_format($cnt_customer_pet)?> 마리</li>
            <li class="list-group-item">가회원 펫 : <?=number_format($cnt_tmp_user_pet)?> 마리</li>
            <li class="list-group-item">전체회원 펫 : <?=number_format($cnt_all)?> 마리</li>
        </ul>
        <br>
		<div class="pet_month_wrap">
			<table class="pet_month">
				<caption>
					<div class="pet_month_search_wrap">
						<select class="search_year">
						<?php for($_i = DATE("Y"); $_i >= 2018; $_i--){ ?>
							<option value="<?=$_i ?>" <?=($r_year == $_i)? " selected " : "" ?>><?=$_i ?>년</option>
						<?php } ?>
						</select>
					</div>
				</caption>
				<colgroup>
				<?php for($_i = 0; $_i <= 12; $_i++){ ?>
					<col width="<?=100 / 13 ?>%" />
				<?php } ?>
				</colgroup>
				<tr>
					<th><?=$r_year ?>년</th>
				<?php for($_i = 1; $_i <= 12; $_i++){ ?>
					<th><?=$_i ?>월</th>
				<?php } ?>
				</tr>
				<tr>
				<?php foreach($pet_month_tmp_arr AS $key => $value){ ?>
					<?=($key == 0)? '<td>가회원</td>' : '<td class="rht">'.number_format($value).'</td>' ?>
				<?php } ?>
				</tr>
				<tr>
				<?php foreach($pet_month_customer_arr AS $key => $value){ ?>
					<?=($key == 0)? '<td>정회원</td>' : '<td class="rht">'.number_format($value).'</td>' ?>
				<?php } ?>
				</tr>
				<tr>
					<td>월펫수</td>
				<?php for($_i = 1; $_i <= 12; $_i++){ ?>
					<td class="rht"><?=number_format($pet_month_tmp_arr[$_i]+$pet_month_customer_arr[$_i]) ?></td>
				<?php } ?>
				</tr>
			</table>
		</div>
		<br/>
        <ul class="list-group">
            <li class="list-group-item list-group-item-success text-center">예약 현황</li>
            <li class="list-group-item">금일 예약 : <?=number_format($cnt_payment_today)?> 건
            <br> - 앱 예약 : <?=number_format($cnt_payment_today_of_customer)?> 건
            <br> - 전일대비 <span class="<?=$class_payment_day?>"><?=number_format($diff_payment_day)?></span> 건</li>
            <li class="list-group-item">금월 예약 : <?=number_format($cnt_payment_month)?> 건
            <br> - 앱 예약 : <?=number_format($cnt_payment_month_of_customer)?> 건
            <br> - 전월대비 <span class="<?=$class_payment_month?>"><?=number_format($diff_payment_month)?></span> 건</li>
            <li class="list-group-item">전체 누적 예약 : <?=number_format($cnt_payment_all)?> 건 </li>
        </ul>
        <br>
        <ul class="list-group">
            <li class="list-group-item list-group-item-success text-center">후기 현황</li>
            <li class="list-group-item">금일 후기 : <?=number_format($cnt_review_today)?> 건</li>
            <li class="list-group-item">금월 후기 : <?=number_format($cnt_review_month)?> 건</li>
            <li class="list-group-item">전체 누적 후기 : <?=number_format($cnt_review_all)?> 건</li>
        </ul>
        <br>
        <ul class="list-group">
            <li class="list-group-item list-group-item-success text-center">이용 상담 신청 현황</li>
            <li class="list-group-item">금일 이용 상담 신청 : <?=number_format($cnt_counseling_today)?> 건 (전일 : <?=number_format($cnt_counseling_prev)?>건)
				<div>
					<span class="counseling_data_toggle_btn on">접기</span>
				</div>
			<?php if($counseling_today_cnt > 0){ ?>
				<span class="comment"> * 전일 이용 상담 요청건까지 노출됩니다.</span>
				<ul class="counseling_data on">
				<?php foreach($data AS $key => $value){ ?>
					<li>
						<div class="approval_<?=$value["approval"]?>">
							<span class="title">회원ID</span> <span><?=$value["customer_id"]?></span><br/>
							<span class="title">휴대폰</span> <span><?=$value["cellphone"]?></span><br/>
							<span class="title">펫샵ID</span> <span><?=$value["artist_id"]?></span><br/>
							<span class="title">펫샵명</span> <span><?=$value["shop_name"]?></span><br/>
							<span class="title">신청일시</span> <span><?=DATE("Y-m-d H:i", STRTOTIME($value["update_time"]))?></span><br/>
							<span class="title">현상태</span> <span class="approval_<?=$value["approval"]?>"><?=$approval_arr[$value["approval"]]?></span><span class="alert"><?=($value["enable_flag"] == "1")? "" : " [탈퇴]" ?><?=($value["tmp_yn"] == "")? " [펫삭제]" : "" ?></span><br/>
						</div>
					</li>
				<?php } ?>
				</ul>
			<?php } ?>
			</li>
            <li class="list-group-item">금월 이용 상담 신청 : <?=number_format($cnt_counseling_month)?> 건</li>
            <li class="list-group-item">전체 누적 이용 상담 신청 : <?=number_format($cnt_counseling_all)?> 건</li>
        </ul>
    </div>
    <br>
<?php
}
?>
<script>
	$(".counseling_data_toggle_btn").click(function(){
		if($(".counseling_data").hasClass("on")){
			$(this).text('').text('펼치기');
			$(".counseling_data").removeClass("on");
		}else{
			$(this).text('').text('접기');
			$(".counseling_data").addClass("on");
		}
	});

	$(document).on("change", ".search_year", function(){
		var value = $(this).children("option:selected").val();
		location.href = "bjj_status_board.php?year="+value;
	});
</script>