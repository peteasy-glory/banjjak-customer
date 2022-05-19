<?php
include "../include/top.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

$r_mode = ($_GET["mode"] && $_GET["mode"] != "")? $_GET["mode"] : "";
$r_ie_seq = ($_GET["seq"] && $_GET["seq"] != "")? $_GET["seq"] : "";

$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
?>
<script src="<?= $js_directory ?>/fontawesome.min.js"></script>
<script src="<?= $js_directory ?>/jquery.datetimepicker.full.min.js"></script>
<link rel="stylesheet" href="<?= $css_directory ?>/jquery.datetimepicker.css" />
<style>
	.bjj_top_menu { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; border-bottom: 1px solid #ccc; background-color: rgba(255, 255, 255, 0.8); margin-top: env(safe-area-inset-top); margin-top: constant(safe-area-inset-top); z-index: 10; }
	.bjj_top_menu .bjj-back-btn { position: absolute; left: 8px; top: 12px; }
	.bjj_top_menu .bjj-back-btn img { width: 26px; vertical-align: middle; }
	.bjj_top_menu .bjj_top_title { width: 100%; height: 50px; line-height: 50px; text-align: center; padding: 0px; }
	.bjj_top_menu .bjj_top_title p { padding: 0px; margin: 0px; }

	#item_event_write { position: relative; width: 100%; margin-top: calc(env(safe-area-inset-top) + 61px); margin-top: calc(constant(safe-area-inset-top) + 61px); padding-bottom: 60px; }
	#item_event_write ul { list-style: none; margin: 10px; padding: 0px; }
	#item_event_write ul li { text-align: right; }
	#item_event_write ul li .title { font-size: 14px; text-align: left; }
	#item_event_write ul li .value { padding: 10px 0px; }
	#item_event_write ul li .value select { width: 100%; height: 30px; font-size: 16px; border: 1px solid #ccc; border-radius: 0px; background-color: #fff; }
	#item_event_write ul li .value input[type='number'],
	#item_event_write ul li .value input[type='text'] { width: calc(100% - 10px); border: 0px; border-bottom: 1px solid #ccc; height: 30px; padding: 0px 5px; font-size: 16px; border-radius: 0px; }
	#item_event_write ul li .value input[name='plus_cnt'],
	#item_event_write ul li .value input[name='total_cnt'] { text-align: right; }
	#item_event_write ul li .value input[type='radio'] { display: none; }
	#item_event_write ul li .value input[type='radio']+label { display: inline-block; border: 1px solid #ccc; background-color: #eee; color: #000; border-radius: 5px; height: 30px; line-height: 30px; padding: 0px 10px; font-size: 14px; }
	#item_event_write ul li .value input[type='radio']:checked+label { border: 1px solid #f5bf2e; background-color: #f5bf2e; color: #fff; }
	#item_event_write ul li ul.table { display: table; margin: 10px 0px; }
	#item_event_write ul li ul.table li { display: table-cell; width: 50%; }
	#item_event_write ul li ul.table li .value { padding: 0px 5px; }
	#item_event_write .btn_wrap { position: fixed; left: 0px; bottom: 0px; width: calc(100% - 10px); padding: 5px; }
	#item_event_write .btn_wrap .set_item_event_insert_btn { width: 100%; height: 40px; border: 1px solid #ccc; border-radius: 5px; margin: 20px 0px 0px 0px; font-size: 16px; border: 1px solid #f5bf2e; background-color: #f5bf2e; color: #fff; }
</style>
<div class="bjj_top_menu">
    <div class="bjj-back-btn"><a href="<?= $admin_directory ?>/item_event.php"><img src="<?= $image_directory ?>/btn_back_2.png"></a></div>
    <div class="bjj_top_title"><p><?=($r_mode == "update")? "상품 판매 이벤트 수정" : "상품 판매 이벤트 입력" ?></p></div>
</div>

<div id="item_event_write">

</div>
<script>
	var $item_event_write = $("#item_event_write");
	var mode = "<?=$r_mode ?>";
	var ie_seq = "<?=$r_ie_seq ?>";
	var item_list = []; // 상품 리스트
	
	$(function(){
		if(mode == "update"){
			get_item_event(ie_seq);
		}else{
			//html
			get_item_list('');
		}
	});

	$item_event_write.on("keyup", "input[name='total_cnt']", function(){
		let value = $(this).val();
		$(this).val(value.replace(/(^0+)/, "")); // 앞 0 제거
		if(value == ""){
			$(this).val(0);
		}
	});

	$item_event_write.on("keyup", "input[name='plus_cnt']", function(){
		let value = $(this).val();
		$(this).val(value.replace(/(^0+)/, "")); // 앞 0 제거
		if(value == ""){
			$(this).val(0);
		}
	});

	$item_event_write.on("click", ".set_item_event_insert_btn", function(){
		var validate = true;
		var post_data = $item_event_write.find("#item_event_form").serialize();
		post_data += (mode == "update")? "&mode=set_update_item_event" : "&mode=set_insert_item_event";

		if($item_event_write.find("select[name='il_seq'] option:selected").val() == ""){
			$.MessageBox({ 
				buttonDone: "확인",
				message: "이벤트 상품을 선택해주세요."
			}).done(function(){
				$item_event_write.find("select[name='il_seq']").focus();			
			});
			return false;
		}
		if($item_event_write.find("input[name='title']").val() == ""){
			$.MessageBox({ 
				buttonDone: "확인",
				message: "이벤트명을 입력해주세요."
			}).done(function(){
				$item_event_write.find("input[name='title']").focus();			
			});
			return false;
		}
		if($item_event_write.find("input[name='total_cnt']").val() <= 0){
			$.MessageBox({ 
				buttonDone: "확인",
				message: "마감 건 수를 정확히 입력해주세요."
			}).done(function(){
				$item_event_write.find("input[name='total_cnt']").focus();			
			});
			return false;
		}
		if($item_event_write.find("input[name='start_dt']").val() == ""){
			$.MessageBox({ 
				buttonDone: "확인",
				message: "시작일시를 입력해주세요."
			}).done(function(){
				$item_event_write.find("input[name='start_dt']").focus();			
			});
			return false;
		}
		if($item_event_write.find("input[name='end_dt']").val() == ""){
			$.MessageBox({ 
				buttonDone: "확인",
				message: "마감일시를 입력해주세요."
			}).done(function(){
				$item_event_write.find("input[name='end_dt']").focus();			
			});
			return false;
		}
		if(mode == "update"){
			if($item_event_write.find("input[name='plus_cnt']").val() == ""){
				$.MessageBox({ 
					buttonDone: "확인",
					message: "추가 건 수를 정확히 입력해주세요."
				}).done(function(){
					$item_event_write.find("input[name='plus_cnt']").focus();			
				});
				return false;
			}
		}

		if(validate){
			validate = false;
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: post_data,
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					validate = true;
					if(data.code == "000000"){
						console.log(data.data);
						let msg = (mode == "update")? "수정 되었습니다." : "입력 되었습니다.";

						$.MessageBox({
							buttonDone: "확인",
							message: msg
						}).done(function(){
							location.href = "<?=$admin_directory ?>/item_event.php";
						});
					}else{
						alert(data.data+"("+data.code+")");
						console.log(data.code);
					}
				},
				error: function(xhr, status, error) {
					validate = true;
					//alert(error + "네트워크에러");
					if(xhr.status != 0){
						alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
					}
				}
			});
		}
	});

	function get_item_event(ie_seq){
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "get_item_event",
				ie_seq : ie_seq
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);

					get_item_list(data.data[0]);
				}else{
					alert(data.data+"("+data.code+")");
					console.log(data.code);
				}
			},
			error: function(xhr, status, error) {
				//alert(error + "네트워크에러");
				if(xhr.status != 0){
					alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
				}
			}
		});
	}

	function get_item_list(_data){
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "get_item_list",
				orderby : "abc",
				limit_all : "1"
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					item_list = data.data;
					item_event_write_html(_data);
				}else{
					alert(data.data+"("+data.code+")");
					console.log(data.code);
				}
			},
			error: function(xhr, status, error) {
				//alert(error + "네트워크에러");
				if(xhr.status != 0){
					alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
				}
			}
		});
	}

	function item_event_write_html(data){
		var html = '';
		var ie_seq = (data)? data.ie_seq : '';
		var il_seq = (data)? data.il_seq : '';
		var title = (data)? data.title : '';
		var plus_cnt = (data)? data.plus_cnt : '0';
		var total_cnt = (data)? data.total_cnt : '0';
		var start_dt = (data)? data.start_dt : '';
		var end_dt = (data)? data.end_dt : '';
		var is_use = (data)? data.is_use : '';

		html += '<form id="item_event_form" method="POST">';
		html += '	<input type="hidden" name="ie_seq" value="'+ie_seq+'" />';
		html += '	<ul>';
		html += '		<li>';
		html += '			<div class="title">이벤트 상품</div>';
		html += '			<div class="value">';
		html += '				<select name="il_seq">';
		html += '					<option value="">선택</option>';
		if(item_list && item_list.list.length > 0){
			$.each(item_list.list, function(i, v){
				var is_selected = (il_seq == v.il_seq)? ' selected ' : '';
				html += '					<option value="'+v.il_seq+'" '+is_selected+'>'+v.product_name+'</option>';
			});
		}
		html += '				</select>';
		html += '			</div>';
		html += '		</li>';
		html += '		<li>';
		html += '			<div class="title">이벤트명</div>';
		html += '			<div class="value">';
		html += '				<input type="text" name="title" value="'+title+'" placeholder="얼리버드" />';
		html += '			</div>';
		html += '		</li>';
		if(mode == "update"){
			html += '		<li>';
			html += '			<div class="title">추가 건 수</div>';
			html += '			<div class="value">';
			html += '				<input type="number" name="plus_cnt" value="'+plus_cnt+'" />';
			html += '			</div>';
			html += '		</li>';
		}
		html += '		<li>';
		html += '			<div class="title">마감 건 수</div>';
		html += '			<div class="value">';
		html += '				<input type="number" name="total_cnt" value="'+total_cnt+'" />';
		html += '			</div>';
		html += '		</li>';
		html += '		<li>';
		html += '			<ul class="table">';
		html += '				<li>';
		html += '					<div class="title">시작일시</div>';
		html += '					<div class="value">';
		html += '						<input type="text" name="start_dt" class="datetimepicker" value="'+start_dt+'" placeholder="<?=DATE("Y-m-d H:i:s") ?>" />';
		html += '					</div>';
		html += '				</li>';
		html += '				<li>';
		html += '					<div class="title">마감일시</div>';
		html += '					<div class="value">';
		html += '						<input type="text" name="end_dt" class="datetimepicker" value="'+end_dt+'" placeholder="<?=DATE("Y-m-d H:i:s") ?>" />';
		html += '					</div>';
		html += '				</li>';
		html += '			</ul>';
		html += '		</li>';
		if(mode == "update"){
			html += '		<li>';
			html += '			<div class="title">사용여부</div>';
			html += '			<div class="value">';
			html += '				<span>';
			var is_checked = (is_use == "1")? ' checked ' : '';
			html += '					<input type="radio" id="is_use_1" name="is_use" value="1" '+is_checked+' />';
			html += '					<label for="is_use_1">사용</label>';
			html += '				</span>';
			html += '				<span>';
			var is_checked = (is_use == "2")? ' checked ' : '';
			html += '					<input type="radio" id="is_use_2" name="is_use" value="2" '+is_checked+' />';
			html += '					<label for="is_use_2">미사용</label>';
			html += '				</span>';
			html += '			</div>';
			html += '		</li>';
		}
		html += '	</ul>';
		html += '</form>';
		html += '<div class="btn_wrap">';
		html += '	<button type="button" class="set_item_event_insert_btn">';
		html += (mode == "update")? "수정" : "입력";
		html +=	'	</button>';
		html += '</div>';

		$item_event_write.html(html);
		//datetimepicker
		$item_event_write.find('.datetimepicker').datetimepicker({
			format: 'Y-m-d H:i:00'
		});
	}

</script>
<?php
    include "../include/bottom.php";
?>

