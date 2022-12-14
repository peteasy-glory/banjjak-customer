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

<style>
    .top_menu { position: fixed; left: 0px; top: 0px; width: 100%; background-color: rgba(255,255,255,0.8); z-index: 1; }
	#item_order_cancel { margin-top: 61px; }
	#item_order_cancel .order_box .next {
		-webkit-appearance: none;
		border-radius: 0;
		background-color: #f5bf2e;
		-webkit-border-top-left-radius: 6px;
		-moz-border-radius-topleft: 6px;
		border-top-left-radius: 6px;
		-webkit-border-top-right-radius: 6px;
		-moz-border-radius-topright: 6px;
		border-top-right-radius: 6px;
		-webkit-border-bottom-right-radius: 6px;
		-moz-border-radius-bottomright: 6px;
		border-bottom-right-radius: 6px;
		-webkit-border-bottom-left-radius: 6px;
		-moz-border-radius-bottomleft: 6px;
		border-bottom-left-radius: 6px;
		text-indent: 0px;
		border: 0;
		display: inline-block;
		color: #ffffff;
		font-size: 15px;
		font-weight: bold;
		font-style: normal;
		line-height: 30px;
		width: 45%;
		text-decoration: none;
		text-align: center;
		padding: 5px 0;
		margin-left: 5px;
	}

	#item_order_cancel .order_box .prev {
		-webkit-appearance: none;
		border-radius: 0;
		background-color: #fff;
		-webkit-border-top-left-radius: 6px;
		-moz-border-radius-topleft: 6px;
		border-top-left-radius: 6px;
		-webkit-border-top-right-radius: 6px;
		-moz-border-radius-topright: 6px;
		border-top-right-radius: 6px;
		-webkit-border-bottom-right-radius: 6px;
		-moz-border-radius-bottomright: 6px;
		border-bottom-right-radius: 6px;
		-webkit-border-bottom-left-radius: 6px;
		-moz-border-radius-bottomleft: 6px;
		border-bottom-left-radius: 6px;
		text-indent: 0px;
		border: 1px solid #ddd;
		display: inline-block;
		color: #ddd;
		font-size: 15px;
		font-weight: bold;
		font-style: normal;
		line-height: 30px;
		width: 45%;
		text-decoration: none;
		text-align: center;
		padding: 5px 0;
		margin-right: 5px;
	}
</style>

<div class="top_menu">
	<!--div class="header-back-btn"><a href="javascript:;"><img src="/pet/images/btn_back_2.png" width="26px"></a></div-->
	<div class="top_title">
		<p>????????????</p>
	</div>
</div>

<div id="item_order_cancel">
	<div class="order_box">
		<div class="order_box_title">2.????????????</div>
		<div class="order_box_text">
			<div style="margin:20px 0;text-align:center;">?????? ????????? ????????? ?????????.</div>
			<table width="100%"class="order_table">
				<colgroup>
					<col width="100%">
				</colgroup>
				<tbody>
					<tr style="border:1px solid #ddd;">
						<td style="padding:10px;"><input type="radio" id="cancel1" name="reason_type" value="1"><label for="cancel1">????????????</label></td>
					<tr>
					<tr style="border:1px solid #ddd;">
						<td style="padding:10px;"><input type="radio" id="cancel2" name="reason_type" value="2"><label for="cancel2">????????????</label></td>
					<tr>
					<tr style="border:1px solid #ddd;">
						<td style="padding:10px;"><input type="radio" id="cancel3" name="reason_type" value="3"><label for="cancel3">????????????</label></td>
					<tr>
					<tr style="border:1px solid #ddd;border-bottom:0;">
						<td style="padding:10px;"><input type="radio" id="cancel4" name="reason_type" value="etc"><label for="cancel4">?????????(????????????)</label></td>
					<tr>
					<tr style="border:1px solid #ddd;border-top:0;">
						<td style="padding:0px 10px 10px;text-align: center;" class="other_text"><textarea name="reason_detail"></textarea></td>
					<tr>

				</tbody>
			</table>		
			<div style="text-align:center;margin-top:20px;"><a href="javascript:;" class="prev">????????????</a><a href="javascript:;" class="next">????????????</a></div>
		</div>
	</div>
</div>

<script>
	var no = "<?=$r_no ?>";
	var reason_chk = ("<?=$_SESSION['RNC_REASONTYPE'] ?>" != "")? "<?=$_SESSION['RNC_REASONTYPE'] ?>" : "";
	console.log(reason_chk);

	$(function(){
		$.each($("#item_order_cancel input[type='radio']"), function(i2, v2){
			var _this = $(this);

			if(_this.val() == reason_chk){
				_this.prop("checked", true);
			}
		});
	});

	$(document).on("click", ".header-back-btn", function(){
		window.history.back();
	});

	$(document).on("click", ".prev", function(){
		window.history.back();
	});

	$(document).on("click", ".next", function(){
		var reason_type = '';
		var reason_detail = $("#item_order_cancel textarea[name='reason_detail']").val();

		$.each($("#item_order_cancel input[type='radio']"), function(i2, v2){
			var _this = $(this);

			if(_this.is(":checked") == true){
				reason_type = _this.val();
			}
		});
		console.log(reason_type);

		if(reason_type != ""){
			if(reason_type == "etc" && reason_detail == ""){
				$.MessageBox("??????????????? ??????????????????.");
				return false;
			}
		}else{
			$.MessageBox("??????????????? ??????????????????.");
			return false;
		}

		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
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
					location.href="<?=$item_directory ?>/item_order_cancel_info.php?no="+no;
				}else{
					alert(data.data+"("+data.code+")");
					console.log(data.code);
				}
			},
			error: function(xhr, status, error) {
				//alert(error + "??????????????????");
				if(xhr.status != 0){
					alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // ?????? ??? ??????
				}
			}
		});
	});
</script>