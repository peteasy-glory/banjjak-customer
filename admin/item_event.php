<?php
include "../include/top.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
?>
<script src="<?= $js_directory ?>/fontawesome.min.js"></script>
<style>
	.bjj_top_menu { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; border-bottom: 1px solid #ccc; background-color: rgba(255, 255, 255, 0.8); margin-top: env(safe-area-inset-top); margin-top: constant(safe-area-inset-top); z-index: 10; }
	.bjj_top_menu .bjj-back-btn { position: absolute; left: 8px; top: 12px; }
	.bjj_top_menu .bjj-back-btn img { width: 26px; vertical-align: middle; }
	.bjj_top_menu .bjj_top_title { width: 100%; height: 50px; line-height: 50px; text-align: center; padding: 0px; }
	.bjj_top_menu .bjj_top_title p { padding: 0px; margin: 0px; }

	#item_event { position: relative; width: 100%; margin-top: calc(env(safe-area-inset-top) + 61px); margin-top: calc(constant(safe-area-inset-top) + 61px); padding-bottom: 60px; }
	#item_event .event { margin: 20px 10px; border: 1px solid #ccc; border-radius: 10px; }
	#item_event .event .event_title { position: relative; border-radius: 10px 10px 0px 0px; background-color: #eee; line-height: 50px; }
	#item_event .event .event_title .ie_seq { position: absolute; left: 0px; top: 0px; width: 50px; height: 50px; text-align: center; background-color: #f5bf2e;  border-radius: 10px 0px 0px 0px; }
	#item_event .event .event_title .ie_seq a span { display: inline-block; width: 100%; height: 100%; color: #fff; font-size: 18px; }
	#item_event .event .event_title .title { padding-left: 55px; white-space: nowrap; }
	#item_event .event .event_title .btn_wrap { position: absolute; right: 10px; top: -2px; white-space: nowrap; }
	#item_event .event .event_title .btn_wrap button { border: 1px solid #ccc; background-color: #fff; border-radius: 5px; height: 30px; padding: 0px 10px; }
	#item_event .event .event_content { margin: 10px; }
	#item_event .event .event_content ul { list-style: none; margin: 0px; padding: 0px; }
	#item_event .event .event_content ul li { text-align: right; }
	#item_event .event .event_content ul li .info { font-size: 12px; text-align: left; margin-top: 10px; }
	#item_event .event .direct_update_wrap { position: relative; margin: 20px 10px 10px 10px; padding: 10px; background-color: #f9f9f9; border: 1px solid #ccc; border-radius: 0px 0px 10px 10px; }
	#item_event .event .direct_update_wrap ul { list-style: none; margin: 0px; padding: 0px; }
	#item_event .event .direct_update_wrap ul li { text-align: right; }
	#item_event .event .direct_update_wrap ul li .info { font-size: 12px; text-align: left; margin-top: 10px; }
	#item_event .event .direct_update_wrap ul li input[name='plus_cnt'] { text-align: right; border: 0px; border-bottom: 1px solid #ccc; height: 30px; padding: 0px 10px; font-size: 16px; background-color: transparent; border-radius: 0px; }
	#item_event .event .direct_update_wrap ul li input[type='radio'] { display: none; }
	#item_event .event .direct_update_wrap ul li input[type='radio']+label { display: inline-block; border: 1px solid #ccc; background-color: #eee; color: #000; border-radius: 5px; height: 30px; line-height: 30px; padding: 0px 10px; font-size: 14px; }
	#item_event .event .direct_update_wrap ul li input[type='radio']:checked+label { border: 1px solid #f5bf2e; background-color: #f5bf2e; color: #fff; }
	#item_event .event .direct_update_wrap ul li .item_event_direct_update_btn { width: 100%; height: 40px; border: 1px solid #333; border-radius: 5px; margin: 20px 0px 0px 0px; font-size: 16px; }
	#item_event .no_event { text-align: center; color: #999; background-color: #eee; height: 80vh; line-height: 80vh; }

	.item_event_insert_btn { position: fixed; z-index: 10; right: 10px; bottom: 10px; width: 50px; height: 50px; box-sizing: border-box; border: 1px solid #f5bf2e; background-color: #f5bf2e; color: #fff; text-align: center; line-height: 50px; border-radius: 25px; font-size: 18px; }
	.item_event_insert_btn .fa-plus { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); }
</style>
<div class="bjj_top_menu">
    <div class="bjj-back-btn"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/btn_back_2.png"></a></div>
    <div class="bjj_top_title"><p>상품 판매 이벤트 관리</p></div>
</div>

<div id="item_event"></div>
<div class="item_event_insert_btn"><i class="fas fa-plus"></i></div>
<script>
	var $item_event = $("#item_event");
	var item_list = []; // 상품 리스트
	
	$(function(){
		get_item_list();
	});

	$item_event.on("keyup", "input[name='plus_cnt']", function(){
		let value = $(this).val();
		$(this).val(value.replace(/(^0+)/, "")); // 앞 0 제거
		if(value == ""){
			$(this).val(0);
		}
	});

	$(document).on("click", ".item_event_insert_btn", function(){
		location.href = "<?=$admin_directory ?>/item_event_write.php";
	});

	$item_event.on("click", ".item_event_update_btn", function(){
		let seq = $(this).data('seq');
		location.href = "<?=$admin_directory ?>/item_event_write.php?mode=update&seq="+seq;		
	});

	$item_event.on("click", ".item_event_delete_btn", function(){
		let seq = $(this).data('seq');
		$.MessageBox({
			buttonDone: "확인",
			buttonFail: "취소",
			message: "해당 이벤트를 삭제 하시겠습니까?"
		}).done(function(){
			set_delete_item_event(seq);
		});
	});

	$item_event.on("click", ".item_event_direct_update_btn", function(){
		let seq = $(this).data('seq');
		let plus_cnt = $item_event.find(".event_"+seq+" input[name='plus_cnt']").val();
		let is_use = $item_event.find(".event_"+seq+" input[name='is_use_"+seq+"']:checked").val();
		console.log(seq, plus_cnt, is_use);
		$.MessageBox({
			buttonDone: "확인",
			buttonFail: "취소",
			message: "수정 하시겠습니까?"
		}).done(function(){
			set_update_item_event(seq, plus_cnt, is_use);
		});
	});

	function get_item_list(){
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
					//console.log(data.data);
					item_list = data.data;
					get_item_event();
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

	function set_update_item_event(ie_seq, plus_cnt, is_use){
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "set_update_item_event",
				ie_seq : ie_seq,
				plus_cnt : plus_cnt,
				is_use : is_use				
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					$.MessageBox("수정 되었습니다.");
					get_item_event();
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

	function set_delete_item_event(ie_seq){
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "set_delete_item_event",
				ie_seq : ie_seq,
				delete_id : "<?=$user_id ?>",
				delete_msg : "admin_item_event에서 직접 삭제"
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					$.MessageBox("삭제 되었습니다.");
					get_item_event();
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

	function get_item_event(){
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "get_item_event"
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					var html = '';

					if(data.data.length > 0){
						$.each(data.data, function(i, v){
							let product_name = '';
							let product_no = '';
							console.log(item_list.list);
							if(item_list && item_list.list.length > 0){
								$.each(item_list.list, function(i2, v2){
									console.log(v2);
									if(v.il_seq == v2.il_seq){
										product_no = v2.product_no;
										product_name = v2.product_name;
									}
								});
							}

							html += '<div class="event event_'+v.ie_seq+'">';
							html += '	<div class="event_title">';
							html += '		<div class="ie_seq"><a href="<?=$mainpage_directory ?>/item_product_page.php?no='+product_no+'" target="_blank"><span>'+v.ie_seq+'</span></a></div>';
							html += '		<div class="title">'+v.title+'</div>';
							html += '		<div class="btn_wrap">';
							html += '			<button type="button" class="item_event_update_btn" data-seq="'+v.ie_seq+'">수정</button>';
							html += '			<button type="button" class="item_event_delete_btn" data-seq="'+v.ie_seq+'">삭제</button>';
							html += '		</div>';
							html += '	</div>';
							html += '	<div class="event_content">';
							html += '		<ul>';
							html += '			<li>';
							html += '				<div class="info">상품명</div>';
							html += '				<div>'+product_name+'</div>';
							html += '			</li>';
							html += '			<li>';
							html += '				<div class="info">건 수 (추가 건 수 제외) / 마감 건 수</div>';
							html += '				<div><span class="now_cnt2_'+v.ie_seq+'">0</span>(<span class="now_cnt_'+v.ie_seq+'">0</span>) / '+v.total_cnt+'</div>';
							html += '			</li>';
							html += '			<li>';
							html += '				<div class="info">이벤트 기간 (시작 ~ 마감)</div>';
							html += '				<div>'+v.start_dt+'<br/> ~ '+v.end_dt+'</div>';
							html += '			</li>';
							html += '		</ul>';
							html += '	</div>';
							html += '	<div class="direct_update_wrap">';
							html += '		<ul>';
							html += '			<li>';
							html += '				<div class="info">추가 건 수</div>';
							html += '				<div>';
							html += '					<input type="number" name="plus_cnt" value="'+v.plus_cnt+'" />';
							html += '				</div>';
							html += '			</li>';
							html += '			<li>';
							html += '				<div class="info">이벤트 사용여부</div>';
							html += '				<div>';
							html += '					<span>';
							var is_checked = (v.is_use == "1")? " checked " : "";
							html += '						<input type="radio" id="is_use_1_'+v.ie_seq+'" name="is_use_'+v.ie_seq+'" value="1" '+is_checked+' />';
							html += '						<label for="is_use_1_'+v.ie_seq+'">사용</label>';
							html += '					</span>';
							html += '					<span>';
							var is_checked = (v.is_use == "2")? " checked " : "";
							html += '						<input type="radio" id="is_use_2_'+v.ie_seq+'" name="is_use_'+v.ie_seq+'" value="2" '+is_checked+' />';
							html += '						<label for="is_use_2_'+v.ie_seq+'">미사용</label>';
							html += '					</span>';
							html += '				</div>';
							html += '			</li>';
							html += '			<li>';
							html += '				<button type="button" class="item_event_direct_update_btn" data-seq="'+v.ie_seq+'">건 수 / 사용여부 수정</button>';
							html += '			</li>';
							html += '		</ul>';
							html += '	</div>';
							html += '</div>';		
							get_now_cnt(v.ie_seq, v.start_dt, v.end_dt, v.plus_cnt, product_no);
						});
					}else{
						html += '<div class="no_event">';
						html += '	등록된 이벤트가 없습니다.';
						html += '</div>';
					}
					$item_event.html(html);
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

	function get_now_cnt(ie_seq, start_dt, end_dt, plus_cnt, product_no){
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "get_now_cnt",
				product_no : product_no,
				start_dt : start_dt,
				end_dt : end_dt				
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					if(data.data){
						console.log(parseInt(data.data), parseInt(plus_cnt));
						$item_event.find(".now_cnt_"+ie_seq).text(data.data);
						$item_event.find(".now_cnt2_"+ie_seq).text(parseInt(data.data) + parseInt(plus_cnt));
					}else{
						$item_event.find(".now_cnt_"+ie_seq).text("0");
						$item_event.find(".now_cnt2_"+ie_seq).text(parseInt(plus_cnt));
					}
				}else if(data.code == "004501"){ // 날짜오류
					alert(data.data);
					$item_event.find(".now_cnt_"+ie_seq).text("0");
					$item_event.find(".now_cnt2_"+ie_seq).text("0");
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
</script>
<?php
    include "../include/bottom.php";
?>
