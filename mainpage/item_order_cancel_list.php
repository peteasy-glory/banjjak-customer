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

	#item_order_cancel .order_box .cancel {
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
		<div class="order_box_title">1.???????????? ??????</div>
		<div class="order_box_text">
			<div style="margin:20px 0;text-align:center;">?????? ????????? ????????? ?????????.</div>
			<table width="100%"class="order_table">
				<colgroup>
					<col width="5%">
					<col width="30%">
					<col width="65%">
				</colgroup>
				<tbody class="item_payment_log_wrap">
					<!--tr style="border:1px solid #ddd;">
						<td style="padding:10px 5px;"><input type="checkbox" id="chk_ag" name="chk_ag" value=""></td>
						<td style="padding:10px 0;"><div class="product_image"></div></td>
						<td style="padding:10px;">
							<p style="font-size:12px;">?????? ????????? ????????? S size</p>
							<p style="font-size:12px;"> 1 / 9,800???</p>
						</td>
					</tr>
					<tr style="border:1px solid #ddd;">
						<td style="padding:10px 5px;"><input type="checkbox" id="chk_ag" name="chk_ag" value=""></td>
						<td style="padding:10px 0;"><div class="product_image"></div></td>
						<td style="padding:10px;">
							<p style="font-size:12px;">?????? ????????? ????????? L size</p>
							<p style="font-size:12px;"> 1 / 9,800???</p>
						</td>
					</tr-->
				</tbody>
			</table>
			<div style="text-align:center;margin-top:20px;"><a href="javascript:;" class="cancel">????????????</a><a href="javascript:;" class="next">????????????</a></div>
		</div>
	</div>
</div>

<script>
	var no = "<?=$r_no ?>";
	
	$(function(){
		$.ajax({
			url: '<?=$mainpage_directory?>/item_list_ajax.php',
			data: {
				mode : "get_item_order_list",
				order_num: no
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					var html = '';
					
					console.log($.parseJSON(data.data[0].pay_data));
					$.each($.parseJSON(data.data[0].pay_data), function(i, v){
						html += '<tr style="border:1px solid #ddd;">';
						html += '	<td style="padding:10px 5px;"><input type="checkbox" id="chk_ag'+i+'" name="chk_ag[]" value="'+v.seq+'" /></td>';
						html += '	<td style="padding:10px 0;"><div class="product_image"></div></td>';
						html += '	<td style="padding:10px;">';
						html += '		<p style="font-size:12px;">'+v.txt+'</p>';
						html += '		<p style="font-size:12px;">'+v.amount+'/ '+v.value+'???</p>';
						html += '	</td>';
						html += '</tr>';
					});
					$("#item_order_cancel .item_payment_log_wrap").html('').html(html);

					
					// img_loading
					$.ajax({
						url: '<?=$mainpage_directory?>/fileupload_ajax.php',
						data: {
							mode : "get_file_list",
							file_list: data.data[0].product_img
						},
						type: 'POST',
						dataType: 'JSON',
						success: function(data) {
							if(data.code == "000000"){
								console.log(data.data); 
								var html = '';
								html += '<img src="'+data.data[0].file_path+'" style="width:100%;">';

								$("#item_order_cancel .product_image").html("").html(html).css("background-image", "none");
							}else{
								alert(data.data+"("+data.code+")");
								console.log(data.code);
							}
						},
						error: function(xhr, status, error) {
							alert(error + "??????????????????");
						}
					});
				}else{
					alert(data.data+"("+data.code+")");
					console.log(data.code);
				}
			},
			error: function(xhr, status, error) {
				alert(error + "??????????????????");
			}
		});
	});

	$(document).on("click", "#item_order_cancel .item_payment_log_wrap tr", function(){
		$(this).find("input[name='chk_ag[]']").prop("checked", true);
	});

	$(document).on("click", ".header-back-btn", function(){
		window.history.back();
	});

	$(document).on("click", ".next", function(){
		var chk_list = [];
		$.each($("#item_order_cancel input[type='checkbox']"), function(i, v){
			if($(this).is(":checked") == true){
				chk_list.push($(this).val());
			}
		});
		chk_list = chk_list.join(",");

		if(chk_list != ""){
			$.ajax({
				url: '<?=$mainpage_directory?>/item_list_ajax.php',
				data: {
					mode : "set_update_item_return_n_cancel_step1",
					action_type : "cancel",
					chk_list : chk_list,
					order_num : no
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data); 
						location.href = "<?=$mainpage_directory?>/item_order_cancel_reason.php?no="+no;
					}else{
						alert(data.data+"("+data.code+")");
						console.log(data.code);
					}
				},
				error: function(xhr, status, error) {
					alert(error + "??????????????????");
				}
			});

		}else{
			$.MessageBox("????????? ????????? ???????????????.");
		}
	});

	$(document).on("click", ".cancel", function(){
		$.MessageBox({
			buttonDone  : "??????",
			buttonFail  : "??????",
			message     : "?????? ????????? ?????? ???????????????????"
		}).done(function(data, button){
			$.ajax({
				url: '<?=$mainpage_directory?>/item_list_ajax.php',
				data: {
					mode : "set_update_item_return_n_cancel_to_cancel"
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data); 
						window.history.back();
					}else{
						alert(data.data+"("+data.code+")");
						console.log(data.code);
					}
				},
				error: function(xhr, status, error) {
					alert(error + "??????????????????");
				}
			});
		});
	});
	
</script>