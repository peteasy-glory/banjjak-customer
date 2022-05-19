<?php
include "../include/top.php";
include "../include/Crypto.class.php";
include "../Mobile_Detect.php";

// init
$r_no = ($_GET["no"] && $_GET["no"] != "")? $_GET["no"] : "";
$backurl = $_GET['backurl'];
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
?>

<script src="../js/fontawesome.min.js"></script>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">

<div class="top_menu">
	<!--div class="header-back-btn"><a href="javascript:;"><img src="/pet/images/btn_back_2.png" width="26px"></a></div-->
	<div class="top_title">
		<p>반품요청</p>
	</div>
</div>

<div id="item_order_return">
	<div class="order_box">
		<div class="order_box_title">2.사유선택</div>
		<div class="order_box_text">
			<div style="margin:20px 0;text-align:center;">반품 사유를 선택해 주세요.</div>
			<table width="100%"class="order_table">
				<colgroup>
					<col width="100%">
				</colgroup>
				<tbody>
					<tr style="border:1px solid #ddd;">
						<td style="padding:10px;"><input type="radio" id="return1" name="reason_type" value="1"><label for="return1">단순변심</label></td>
					<tr>
					<tr style="border:1px solid #ddd;">
						<td style="padding:10px;"><input type="radio" id="return2" name="reason_type" value="2"><label for="return2">상품불량</label></td>
					<tr>
					<tr style="border:1px solid #ddd;">
						<td style="padding:10px;"><input type="radio" id="return3" name="reason_type" value="3"><label for="return3">제품변경</label></td>
					<tr>
					<tr style="border:1px solid #ddd;border-bottom:0;">
						<td style="padding:10px;"><input type="radio" id="return4" name="reason_type" value="etc"><label for="return4">그밖에(직접입력)</label></td>
					<tr>
					<tr style="border:1px solid #ddd;border-top:0;">
						<td style="padding:0px 10px 10px;text-align: center;" class="other_text"><textarea name="reason_detail"></textarea></td>
					<tr>

				</tbody>
			</table>		
			<div style="text-align:center;margin-top:20px;"><a href="javascript:;" class="prev">이전단계</a><a href="javascript:;" class="next">다음단계</a></div>
		</div>
	</div>
</div>

<script>
	var no = "<?=$r_no ?>";

	$(document).on("click", ".header-back-btn", function(){
		window.history.back();
	});

	$(document).on("click", ".prev", function(){
		window.history.back();
	});

	$(document).on("click", ".next", function(){
		var reason_type = $("#item_order_return input[name='reason_type']:checked").val();
		var reason_detail = $("#item_order_return textarea[name='reason_detail']").val();

		if(reason_type != ""){
			if(reason_type == "etc" && reason_detail == ""){
				$.MessageBox("반품사유를 입력해주세요.");
				return false;
			}
		}else{
			$.MessageBox("반품사유를 선택해주세요.");
			return false;
		}

		$.ajax({
			url: '<?=$mainpage_directory?>/item_list_ajax.php',
			data: {
				mode : "set_update_item_return_n_cancel_step2",
				reason_type : reason_type,
				reason_detail : reason_detail
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data); 
					location.href="<?=$mainpage_directory?>/item_order_return_info.php?no="+no;
				}else{
					alert(data.data+"("+data.code+")");
					console.log(data.code);
				}
			},
			error: function(xhr, status, error) {
				alert(error + "네트워크에러");
			}
		});
	});
</script>