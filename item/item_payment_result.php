<?php
include "../include/top.php";
include "../include/Crypto.class.php";
include "../Mobile_Detect.php";

// init
$r_no = ($_GET["no"] && $_GET["no"] != "")? $_GET["no"] : "";
$backurl = $_GET['backurl'];
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

if($r_no != ""){
	$sql = "
		SELECT * 
		FROM tb_item_payment_log
		WHERE order_num = '".$r_no."'
	";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$expire_dt = ($row["expire_dt"] != "")? DATE("Y년 m월 d일 H시 i분 s초", STRTOTIME($row["expire_dt"])) : "";
	$ip_cnt = mysql_num_rows($result);
	if($ip_cnt <= 0){
?>
    <script>
        $.MessageBox({
            buttonDone: "확인",
            message: "잘못된 접근입니다. 메인페이지로 이동합니다."
        }).done(function() {
            location.href = "<?=$mainpage_directory ?>/";
        });
    </script>
<?php
		return false;
	}
}else{
?>
    <script>
        $.MessageBox({
            buttonDone: "확인",
            message: "잘못된 접근입니다. 메인페이지로 이동합니다."
        }).done(function() {
            location.href = "<?=$mainpage_directory ?>/";
        });
    </script>
<?php
	return false;
}
?>

<script src="../js/fontawesome.min.js"></script>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<style>
	#item_order_complete { margin-top: 61px; }
</style>

<div class="top_menu">
	<div class="top_title">
		<p>결제 완료</p>
	</div>
</div>

<div id="item_order_complete">
	<div class="order_box" style="border: 1px solid #ddd;">
		<div class="order_box_title">결제완료</div>
		
		
		<div class="order_box_text">
			<table width="100%"class="order_table" style="border:1px solid #ddd;">
				<colgroup>
					<col width="30%">
					<col width="70%">
				</colgroup>
			<?php if($row["pay_type"] == "1"){ ?>
				<!----카드결제------->
				<tbody>
					<tr>
						<td style="padding:20px 10px 10px;font-size:20px;">결제가 완료 되었습니다.</td>
					</tr>
					<tr>
						<td style="padding:10px;"><span style="border-bottom:1px solid #666;">주문번호 : <?=$r_no ?></span></td>
					</tr>
					<tr>
						<td style="padding:10px;font-size:12px;color:#aaa;">비회원으로 주문시<br>주문번호로만 주문조회가 가능합니다</td>
					</tr>
					<tr>
						<td style="padding:10px 10px 20px;"><a href="javascript:;" class="content_ok">구매상품 확인</a></td>
					</tr>
				</tbody>
				<!------------------>
			<?php }else{ ?>
				<!----무통장입금------->
				<tbody>
					<tr>
						<td style="padding:20px 10px 10px;font-size: 20px;">주문이 완료 되었습니다.</td>
					</tr>
					<tr>
						<td style="padding:10px;">기업은행 054-143076-01-013<br>[주식회사 펫이지]<br><br><p style=" font-size:12px;color:#333;">계좌이체시 반드시 입력하신 이름으로 입금 부탁드립니다.<p></td>
					</tr>
					<tr>
						<td style="padding:10px;">입금기한 : <?=$expire_dt?><br><p style=" font-size:11px;">입금기한이 지나면 주문자동취소되니 유의해 주시기 바랍니다.<p></td>
					</tr>
					<tr>
						<td style="padding:10px;"><span style="border-bottom:1px solid #666;">주문번호 : <?=$r_no ?></span></td>
					</tr>
					<tr>
						<td style="padding:10px;font-size:12px;color:#aaa;">비회원으로 주문시<br>주문번호로만 주문조회가 가능합니다</td>
					</tr>
					<tr>
						<td style="padding:10px 10px 20px;"><a href="javascript:;" class="content_ok">구매상품 확인</a></td>
					</tr>
				</tbody>
				<!------------>
			<?php } ?>
			</table>
		<div>
	</div>
</div>

<script>
var user_id = "<?=$user_id?>";
$(document).on("click", "#item_order_complete .content_ok", function(){
	if(user_id != ""){
		location.href = "<?=$mainpage_directory ?>/item_order_list.php?no=<?=$r_no?>";
	}else{
		location.href = "../chkodr/?no=<?=$r_no?>";
	}
});
</script>