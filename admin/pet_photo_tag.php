<?php
include "../include/top.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

// init
$r_no = ($_GET["no"] && $_GET["no"] != "")? $_GET["no"] : "";
$r_order_status = ($_GET["os"] && $_GET["os"] != "")? $_GET["os"] : "";
$r_pay_status = ($_GET["ps"] && $_GET["ps"] != "")? $_GET["ps"] : "";
$r_item_list = ($_GET["il"] && $_GET["il"] != "")? $_GET["il"] : "";
$r_customer_name = ($_GET["cn"] && $_GET["cn"] != "")? $_GET["cn"] : "";
$backurl = $_GET['backurl'];
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];



?>
<script type="text/javascript" src="<?= $js_directory ?>/fontawesome.min.js"></script>
<link rel="stylesheet" href="<?= $css_directory ?>/fancybox.min.css" />
<script src="<?= $js_directory ?>/fancybox.min.js"></script>
<style>
	html, body { position: relative; padding: 0px; margin: 0px; width: 100%; height: 100%; font-family: 'SCDream2'; src: url("../fonts/SCDream2.otf"); }
    input { -webkit-appearance: none; border-radius: 0; }

    .top_menu { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; background-color: rgba(255,255,255,0.8); border: 1px solid #ccc; z-index: 1; }
    .top_title { width: 100%; height: 50px; line-height: 50px; text-align: center; }
    .top_title p { margin: 0px; padding: 0px; font-size:20px; }
    .header-back-btn { top: 13px; position: absolute; left: 10px; }
	#admin_item_payment_log { margin-top: 61px; }
	#admin_item_payment_log .search_wrap { line-height: 40px; padding: 5px; margin-bottom: 10px; border-bottom: 1px solid #f9f9f9; }
	#admin_item_payment_log .search_order_status { height: 30px; padding: 0px 10px; }
	#admin_item_payment_log .search_pay_status { height: 30px; padding: 0px 10px; }
	#admin_item_payment_log .search_item_list { max-width: 350px; height: 30px; padding: 0px 10px; }
	#admin_item_payment_log .search_customer_name { height: 30px; border: 1px solid #ccc; background-color: #fff; padding: 0px 10px; }
	#admin_item_payment_log .search_btn { border: 1px solid #ccc; background-color: #eee; height: 30px; padding: 0px 10px; border-radius: 5px; }
	#admin_item_payment_log .search_reset_btn { border: 1px solid #ccc; background-color: #eee; height: 30px; padding: 0px 10px; border-radius: 5px; }
	#admin_item_payment_log .old_item_payment_log_btn { border: 1px solid #ccc; background-color: #eee; height: 30px; padding: 0px 10px; border-radius: 5px; }
	#admin_item_payment_log .data_give_finish_0 { border: 1px solid #ccc; background-color: #eee; height: 30px; padding: 0px 10px; border-radius: 5px; }
	#admin_item_payment_log .data_give_finish_1 { border: none; background-color: #FAFAFA; color:#E6E6E6; height: 30px; padding: 0px 10px; border-radius: 5px; }
	#admin_item_payment_log .order_box_title { text-align: right; font-size: 14px; padding: 0px 5px 5px 0px; }
	#admin_item_payment_log .order_table { width: 100%; border-collapse: collapse; font-size: 12px; text-align: center; }
	#admin_item_payment_log .order_table tr { border-bottom: 1px solid #eee; }
	#admin_item_payment_log .order_table tr:hover { background-color: #fff9ee; }
	#admin_item_payment_log .order_table tr.is_delete:hover { background-color: #ccc !important; }
	#admin_item_payment_log .order_table tr.cancel { background-color: #fcc; color: #f00; }
	#admin_item_payment_log .order_table tr.return { background-color: #ff9966; color: #660000; }
	#admin_item_payment_log .order_table tr.return_finish { background-color: #ff9966; color: #660000; }
	#admin_item_payment_log .order_table tr.ready { color: #ccc; }
	#admin_item_payment_log .order_table tr.ready td>a { color: #ccc; }
	#admin_item_payment_log .order_table tr th { padding: 5px; background-color: #ddd; white-space: nowrap; }
	#admin_item_payment_log .order_table tr td { padding: 2px 5px; height: 50px; }
	#admin_item_payment_log .order_table tr td a { color: #333; }
	#admin_item_payment_log .order_table tr td.lft { text-align: left; }
	#admin_item_payment_log .order_table tr td.rht { text-align: right; }
	#admin_item_payment_log .order_table tr td img { width: 100%; min-width: 50px; max-width: 50px; }
	#admin_item_payment_log .order_table tr td .product_img { width: 50px; height: 50px; background-size: cover; background-repeat: no-repeat; background-position: center; }
	#admin_item_payment_log .order_table tr td .card { color: #f00; font-weight: Bold; font-size: 10px; }
	#admin_item_payment_log .order_table tr td .bank { color: #00f; font-weight: Bold; font-size: 10px; }
	#admin_item_payment_log .order_table tr td .jbook { color: #339900; }
	.more_btn { width: 100%; border: 0px; border-bottom: 1px solid #e4ae1d; background-color: #f5bf2e; color: #fff; height: 50px; line-height: 50px; }

	#admin_item_payment_log .order_table tr.finish_1 {background-color:#F8E0E0; z-index:109;} 


	@media only screen and (max-width: 670px)  {
		#admin_item_payment_log .item_product_list { display: none; }
	}

	.is_delete{background-color:#ccc !important;}
	.is_finish{background-color:#81F7F3;}
	#page_num_wrap a{color:black; text-decoration:none}
	#page_num_wrap .on:hover{cursor:default;}
	#page_num_wrap .on{background-color:#F4FA58;}
	
</style>
<div id="admin_item_payment_log">
	<div class="top_menu">
		<div class="header-back-btn"><a href="/pet/admin/"><img src="/pet/images/btn_back_2.png" width="26px"></a></div>
        <div class="top_title">
            <p>펫 사진 태그</p>
        </div>
    </div>


	<div class="order_box">
		<div>작업진척도 : <span id="end_data_cnt"></span> (완료: <span id="finish_data_cnt"></span>, 삭제: <span id="delete_data_cnt"></span>)/ <span id="total_data_cnt"></span></div>
		<div>
			<form id="pet_data">
				<table width="100%" class="order_table">
					<colgroup>
						<col width="5%">
						<col width="8%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<col width="12%">
						<col width="15%">
					</colgroup>
					<thead>
						<tr>
							<th>체크</th>
							<th>사진</th>
							<th>펫타입</th>
							<th>펫종류</th>
							<th>바디컷</th>
							<th>얼굴컷</th>
							<th>발모양</th>
							<th>색깔</th>
							<th>기타</th>
							<th>버튼</th>

						</tr>
					</thead>
					<tbody class="order_list_wrap">
					</tbody>
				</table>
			<div id="save_btn"></div>
			</form>
		</div>
		<div id="etc_popup"></div>
	</div>
	<div id="page_num_wrap"></div>
</div>

<script>

	var total_data_cnt = 0; // 총 데이터 수
	var finish_data_cnt = 0; // 작업완료 수
	var delete_data_cnt = 0; // 삭제 수
	var start_cnt = 0; // limit 시작 
	var end_cnt = 20; // limit 끝 한페이지에 게시물 수
	var start_page = 1; // 첫페이지 넘버
	var page_cnt = 50; // 한페이지에 페이지넘버 몇개 넣을지
	var array_idx = []; // 저장버튼 idx 담을 배열

	$(function(){
		$.ajax({
			type: 'post',
			url: "<?=$admin_directory ?>/pet_photo_tag_ajax.php",
			data: {
				mode : "get_photo_data"
			},
			dataType: 'json',
			success: function(json) {
				if(json.code == "000000"){
					total_data_cnt = json.data[0]; // 총 데이터 수
					$("#admin_item_payment_log #total_data_cnt").text(total_data_cnt.format());
					var total_cnt = Math.floor(total_data_cnt/end_cnt); // 총 페이지 수
					get_cut_option(start_cnt, start_page, total_cnt)

				}else{
					alert(json.data+"("+json.code+")");
					console.log(json.data);
				}
			},
			error: function() {
			},
			complete: function() {
				// console.log('complete');
				//서브밋 차단 해제
				$("#loading").hide();
			}
		});
	});

	// 태그 옵션 목록 불러오기
	function get_cut_option(start_cnt, start_page, total_cnt){
		$.ajax({
			type: 'post',
			url: "<?=$admin_directory ?>/pet_photo_tag_ajax.php",
			data: {
				mode : "get_cut_option"
			},
			dataType: 'json',
			success: function(json) {
				if(json.code == "000000"){
					get_end_data_cnt(json.data, start_cnt, start_page, total_cnt);
//					get_photo_data(json.data, start_cnt, start_page, total_cnt);
				}else{
					alert(json.data+"("+json.code+")");
					console.log(json.data);
				}
			},
			error: function() {
			},
			complete: function() {
				// console.log('complete');
				//서브밋 차단 해제
				$("#loading").hide();
			}
		});
	}

	// 상단 데이터 진행상황 가져오기
	function get_end_data_cnt(data, start_cnt, start_page, total_cnt){
		$.ajax({
			type: 'post',
			url: "<?=$admin_directory ?>/pet_photo_tag_ajax.php",
			data: {
				mode : "get_photo_data",
				where : "finish"
			},
			dataType: 'json',
			success: function(json) {
				finish_data_cnt = json.data[0];
				$("#admin_item_payment_log #finish_data_cnt").text(finish_data_cnt.format());
				$.ajax({
					type: 'post',
					url: "<?=$admin_directory ?>/pet_photo_tag_ajax.php",
					data: {
						mode : "get_photo_data",
						where : "delete"
					},
					dataType: 'json',
					success: function(json_) {
						delete_data_cnt = json_.data[0];
						$("#admin_item_payment_log #delete_data_cnt").text(delete_data_cnt.format());
						$("#admin_item_payment_log #end_data_cnt").text((delete_data_cnt+finish_data_cnt).format());
						get_photo_data(data, start_cnt, start_page, total_cnt);
					},
					error: function() {
					},
					complete: function() {
						// console.log('complete');
						//서브밋 차단 해제
						$("#loading").hide();
					}
				});
			},
			error: function() {
			},
			complete: function() {
				// console.log('complete');
				//서브밋 차단 해제
				$("#loading").hide();
			}
		});
	}

	// 데이터 가져오기
	function get_photo_data(data, start_cnt, start_page, total_cnt){
		var page_num = ".page_num_"+((start_cnt/end_cnt)+1); // 현재 페이지 넘버
		var end_page = (start_page+page_cnt-1 > total_cnt)? total_cnt : start_page+page_cnt-1; // 마지막페이지 넘버
		$.ajax({
			type: 'post',
			url: "<?=$admin_directory ?>/pet_photo_tag_ajax.php",
			data: {
				mode : "get_photo_data",
				limit : "on",
				start_cnt : start_cnt,
				end_cnt : end_cnt
			},
			dataType: 'json',
			success: function(json) {
				if(json.code == "000000"){
					//console.log(json.data);
					var html = '';
					array_idx = [];
				console.log(json.data.length);
					$.each(json.data, function(i, v){
						var is_delete = (v.is_delete == '1')? "is_delete" : "";
						var is_finish = (v.is_work_finish == '1')? "is_finish" : "";
						
						html += '			<tr class="tr_'+v.idx+' '+is_delete+' '+is_finish+'">';
						html += '				<td></td>';
						html += '				<td><a data-fancybox="gallery" href="'+v.img_path+'"><div class="product_img" data-id="'+v.idx+'" style="background-image: url(\''+v.img_path+'\'); "></div></a></td>';
						html += '				<td><div class="">'+v.name+'</div></td>';

						// 펫종류
						html += '				<td>';
						html += '					<select name="pet_kind_'+v.idx+'">';
						$.each(data.pet_kind, function(kind_i, kind_v){
							if(v.pet_idx == kind_v.class_idx){
								var selected = (v.kind_idx == kind_v.idx)? "selected" : "";
								html += '				<option value="'+kind_v.idx+'" '+selected+'>'+kind_v.name_kor+'</option>';
							}
						});
						html += '					</select>';
						html += '				</td>';

						// 바디컷
						html += '				<td>';
						html += '					<select name="body_cut_'+v.idx+'">';
						$.each(data.body_cut, function(body_i, body_v){
							if(v.pet_idx == body_v.class_idx){
								var selected = (v.body_cut_idx == body_v.idx)? "selected" : "";
								html += '				<option value="'+body_v.idx+'" '+selected+'>'+body_v.name+'</option>';
							}
						});
						html += '					</select>';
						html += '				</td>';

						// 머리컷
						html += '				<td>';
						html += '					<select name="head_cut_'+v.idx+'">';
						$.each(data.head_cut, function(head_i, head_v){
							if(v.pet_idx == head_v.class_idx){
								var selected = (v.head_cut_idx == head_v.idx)? "selected" : "";
								html += '				<option value="'+head_v.idx+'" '+selected+'>'+head_v.name+'</option>';
							}
						});
						html += '					</select>';
						html += '				</td>';

						// 다리컷
						html += '				<td>';
						html += '					<select name="foot_cut_'+v.idx+'">';
						$.each(data.foot_cut, function(foot_i, foot_v){
							if(v.pet_idx == foot_v.class_idx){
								var selected = (v.foot_cut_idx == foot_v.idx)? "selected" : "";
								html += '				<option value="'+foot_v.idx+'" '+selected+'>'+foot_v.name+'</option>';
							}
						});
						html += '					</select>';
						html += '				</td>';

						// 색깔
						html += '				<td>';
						html += '					<select name="color_'+v.idx+'">';
						$.each(data.color, function(color_i, color_v){
							var selected = (v.color_idx == color_v.idx)? "selected" : "";
							html += '					<option value="'+color_v.idx+'" '+selected+'>'+color_v.name+'</option>';
						});
						html += '					</select>';
						html += '				</td>';

						// 기타
						if(v.etc_main_idx && v.etc_main_idx != ''){
							var etc_sub = "";
							$.each(data.etc_sub, function(etc_sub_i, etc_sub_v){
									if(v.etc_main_idx == etc_sub_v.etc_main_idx){
										etc_sub += etc_sub_v.name+", ";
									}
							});
							etc_sub = etc_sub.slice(0, -2);
							html += '				<td><span class="etc_input" data-etc_main_idx="'+v.etc_main_idx+'" data-idx="'+v.idx+'" data-start_cnt="'+start_cnt+'" data-start_page="'+start_page+'" data-total_cnt="'+total_cnt+'"><input type="text" id="etc_input_'+v.idx+'" value="'+etc_sub+'" disabled></span></td>';
						}else{
							html += '				<td><span class="etc_input" data-etc_main_idx="'+v.etc_main_idx+'" data-idx="'+v.idx+'" data-start_cnt="'+start_cnt+'" data-start_page="'+start_page+'" data-total_cnt="'+total_cnt+'"><input type="text" id="etc_input_'+v.idx+'" value="" disabled></span></td>';
						}

						html += '				<td>';
						html += '					<button type="button" onclick="javascript:reset_or_delete('+start_cnt+', '+start_page+', '+total_cnt+','+v.idx+',\'reset\')">초기화</button>';
						html += '					<button type="button" onclick="javascript:reset_or_delete('+start_cnt+', '+start_page+', '+total_cnt+','+v.idx+',\'delete\')">삭제</button>';
						html += '				</td>';
						html += '			</tr>';

						array_idx.push(v.idx);

					});
					$("#admin_item_payment_log .order_list_wrap").html(html);

					var btn_html = '<button type="button" onclick="javascript:save_all('+start_cnt+', '+start_page+', '+total_cnt+')">저장</button>';
					$("#admin_item_payment_log #save_btn").html(btn_html);
					set_page_number(start_page, end_page, total_cnt, page_num);

				}else{
					alert(json.data+"("+json.code+")");
					console.log(json.data);
				}
			},
			error: function() {
			},
			complete: function() {
				// console.log('complete');
				//서브밋 차단 해제
				$("#loading").hide();
			}
		});
	}

	// 초기화 및 삭제
	function reset_or_delete(start_cnt, start_page, total_cnt, idx, state){

		if(state == "reset"){
			$.MessageBox({
				buttonDone: "예스",
				buttonFail: "놉",
				message: "초기화 할까요?"
			}).done(function(){
				$.ajax({
					type: 'post',
					url: "<?=$admin_directory ?>/pet_photo_tag_ajax.php",
					data: {
						mode : "data_update",
						idx : idx,
						finish : 0,
						delete : 0
					},
					dataType: 'json',
					success: function(json) {
						if(json.code == "000000"){
							$(".tr_"+idx).removeClass("is_delete");
						}
					},
					error: function() {
					},
					complete: function() {
						// console.log('complete');
						//서브밋 차단 해제
						$("#loading").hide();
					}
				});
//				$.MessageBox("초기화 사실상 무의미");
			});

		}else if(state == "delete"){
			$.MessageBox({
				buttonDone: "예스",
				buttonFail: "놉",
				message: "삭제 하시겠습니까?"
			}).done(function(){
				$.ajax({
					type: 'post',
					url: "<?=$admin_directory ?>/pet_photo_tag_ajax.php",
					data: {
						mode : "data_update",
						idx : idx,
						delete : 1
					},
					dataType: 'json',
					success: function(json) {
						if(json.code == "000000"){
							$(".tr_"+idx).addClass("is_delete");
						}
					},
					error: function() {
					},
					complete: function() {
						// console.log('complete');
						//서브밋 차단 해제
						$("#loading").hide();
					}
				});
			});
		}
	}



	// 한 페이지 저장
	function save_all(start_cnt, start_page, total_cnt){
		var save_tf = true;
		$.each(array_idx, function(i, v){
			var post_data = $("#pet_data").serialize();
			post_data += "&mode=data_update";
			post_data += "&finish=1";
			post_data += "&delete=0";
			post_data += "&idx="+v;
			
			$.ajax({
				type: 'post',
				url: "<?=$admin_directory ?>/pet_photo_tag_ajax.php",
				data: post_data,
				dataType: 'json',
				success: function(json) {
					if(json.code == "000000"){
						save_tf = true;
					}else if(json.code == "999996"){
						save_tf = false;
					}else{
						save_tf = false;
					}
				},
				error: function() {
				},
				complete: function() {
					// console.log('complete');
					//서브밋 차단 해제
					$("#loading").hide();
				}
			});
		});

		if(save_tf){
			$.MessageBox({
				buttonDone: "예스",
				buttonFail: "놉",
				message: "저장완료! 다음 페이지로 가실?"
			}).done(function(){
				var page_num = (start_cnt/end_cnt)+1; // 페이지넘버
				start_page = (page_num%50 == 0)? (start_page+page_cnt) : start_page; // 마지막 페이지일때 페이지구간 넘기기
				move_page(start_cnt+end_cnt,start_page, total_cnt);
			}).fail(function(){
				move_page(start_cnt, start_page, total_cnt);
			});
		}else{
			$.MessageBox("오류!! 글로리를 불러주세요!!");
		}
	}


	// 페이지 넘버
	function set_page_number(start_page, end_page, total_cnt, page_num){
		var page_start_num = (end_page == total_cnt)? start_page : end_page+1-page_cnt; // 페이지 첫 넘버 구하기

		var html = '';
		if(start_page != 1){ // 첫 페이지는 이전 버튼 없음
			html += '<a href="#" onclick="javascript:move_page('+((start_page-2)*end_cnt)+','+(page_start_num-page_cnt)+','+total_cnt+')">이전</a> | ';
		}
		for(start_page; start_page<=end_page; start_page++){
			html += '<a href="#" class="page_num_'+start_page+'" onclick="javascript:move_page('+((start_page-1)*end_cnt)+','+page_start_num+','+total_cnt+')">'+start_page+'</a>';
			html += ' | ';
		}
		if(end_page < total_cnt){ // 마지막 페이지 다음버튼 없음
			html += '<a href="#" onclick="javascript:move_page('+((start_page-1)*end_cnt)+','+(page_start_num+page_cnt)+','+total_cnt+')">다음</a>';
		}
		$("#page_num_wrap").html(html);
		$(page_num).addClass('on');
	}

	// 페이지 이동
	function move_page(start_cnt, start_page, total_cnt){ // 리미트 앞, 페이지박스 첫페이지 
//		$('.page_num_'+page_num).addClass('on');
//		$('.page_num_'+((start_cnt/end_cnt)+1)).removeAttr('href');
		get_cut_option(start_cnt, start_page, total_cnt);
	}

	// 기타 선택
	$(document).on('click', '.etc_input', function(){
		var etc_main_idx = $(this).data('etc_main_idx'); // etc_main table의 idx
		var start_cnt = $(this).data('start_cnt'); // start_cnt
		var start_page = $(this).data('start_page'); // start_page
		var total_cnt = $(this).data('total_cnt'); // total_cnt
		var idx = $(this).data("idx"); // tab_mgr table의 idx
		var cnt = 0; // etc_sub 개수
		var etc_array_sub = []; // etc_sub
		var etc_array_sub_idx = []; // etc_sub_idx

		$("#etc_popup").dialog({
			modal: true,
			title: "test",
			autoOpen: true,
			position:{
				of : $(this),
				at : 'right bottom',
				my : 'right top'	
			},
			width: $(this).width() + 100,
//			height: $(window).height() - 400,
			autoSize: true,
			resizable: false,
			draggable: false,
			buttons: {
				"저장": function() {
					console.log("update_must = "+cnt); // 이미 기타 sub에 저장되어 있는 값
					
					var etc_sub_form_name = $("#etc_popup").find("#etc_sub_form").serializeArray(); // 기타 입력 값
					var value_cnt = 0; // 기타에 입력한 값
					for(var i=0; i<etc_sub_form_name.length; i++){
						if(etc_sub_form_name[i].value && etc_sub_form_name[i].value != ""){
							value_cnt = value_cnt+1;
						}
					}
					var sub_value_cnt = value_cnt - cnt; // update 하고 남은 insert 쳐야할 값 수
					console.log("입렵값은 "+value_cnt);
//					console.log("total_cnt = "+etc_sub_form_name[1].value); // [i].value 가 없는값일때==================
//					console.log("data_cnt = "+etc_sub_form_name.length); // 무조건 3 빈값이 들어간 data가 읽혀짐

					if(etc_main_idx && etc_main_idx != "" && cnt > 0){ // update

						for(var i=0; i<cnt; i++){
							$.ajax({
								url: '<?=$admin_directory ?>/pet_photo_tag_ajax.php',
								data: {
									mode: "update_etc_sub",
									idx: etc_array_sub_idx[i],
									sub_name: etc_sub_form_name[i].value
								},
								type: 'POST',
								dataType: 'JSON',
								success: function(data){
//									if(json.code != "000000"){
//										$.MessageBox("에러!!!!!!!!!!");
//									}
								},
								error: function(xhr, status, error){
									if(xhr.status != 0){
										alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
									}
								}
							});
						}
						if(sub_value_cnt > 0){
							for(var i=cnt; i<(sub_value_cnt+cnt); i++){
								console.log("i= "+i);
								$.ajax({
									url: '<?=$admin_directory ?>/pet_photo_tag_ajax.php',
									data: {
										mode: "insert_etc_sub",
										idx: etc_main_idx,
										sub_name: etc_sub_form_name[i].value
									},
									type: 'POST',
									dataType: 'JSON',
									success: function(json){
										if(json.code != "000000"){
											$.MessageBox("에러!!!!!!!!!!");
										}
									},
									error: function(xhr, status, error){
										if(xhr.status != 0){
											alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
										}
									}
								});
							}
							console.log("value "+sub_value_cnt);
						}
						$("#etc_popup").dialog("close");
						get_cut_option(start_cnt, start_page, total_cnt);

					}else{ // insert

						// etc_main 데이터 생성
						$.ajax({
							url: '<?=$admin_directory ?>/pet_photo_tag_ajax.php',
							data: {
								mode: "insert_etc_main"
							},
							type: 'POST',
							dataType: 'JSON',
							success: function(data){

								// 입력 값 만큼 insert
								for(var i=0; i<value_cnt; i++){
									$.ajax({
										url: '<?=$admin_directory ?>/pet_photo_tag_ajax.php',
										data: {
											mode: "insert_etc_sub",
											idx: data.data[0].idx,
											sub_name: etc_sub_form_name[i].value
										},
										type: 'POST',
										dataType: 'JSON',
										success: function(json){
											if(json.code == "000000"){

												// tab_mgr 테이블의 etc_main_idx UPDATE
												$.ajax({
													type: 'post',
													url: "<?=$admin_directory ?>/pet_photo_tag_ajax.php",
													data: {
														mode : "data_update",
														idx : idx,
														etc_main_idx : data.data[0].idx
													},
													dataType: 'json',
													success: function(data_) {
														if(data_.code == "000000"){
															$("#etc_popup").dialog("close");
															get_cut_option(start_cnt, start_page, total_cnt);
														}
													},
													error: function() {
													},
													complete: function() {
														// console.log('complete');
														//서브밋 차단 해제
														$("#loading").hide();
													}
												});
											}
										},
										error: function(xhr, status, error){
											if(xhr.status != 0){
												alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
											}
										}
									});
								}
							},
							error: function(xhr, status, error){
								if(xhr.status != 0){
									alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
								}
							}
						});
					}
				}
			},
			open: function(event, ui) {
				var html = '';

				html += '<div>';
				html += '	<form id="etc_sub_form" method="POST">';
				html += '		<input type="text" class="etc_sub_detail_0" name="etc_sub_detail_0">';
				html += '		<input type="text" class="etc_sub_detail_1" name="etc_sub_detail_1">';
				html += '		<input type="text" class="etc_sub_detail_2" name="etc_sub_detail_2">';
				html += '	</form>';
				html += '</div>';

				$(this).html(html);

				if(etc_main_idx && etc_main_idx != ''){
					console.log("data = "+etc_main_idx);
					$.ajax({
						type: 'post',
						url: "<?=$admin_directory ?>/pet_photo_tag_ajax.php",
						data: {
							mode : "get_etc_sub",
							etc_main : etc_main_idx
						},
						dataType: 'json',
						success: function(json) {
							if(json.code == "000000"){
								cnt = json.data.length; // etc_sub 개수
								$.each(json.data, function(i, v){
									$("#etc_sub_form .etc_sub_detail_"+i).val(v.name);
									etc_array_sub.push(v.name); // etc_sub
									etc_array_sub_idx.push(v.idx); // etc_sub_idx
								});

							}else{
								alert(json.data+"("+json.code+")");
								console.log(json.data);
							}
						},
						error: function() {
						},
						complete: function() {
							// console.log('complete');
							//서브밋 차단 해제
							$("#loading").hide();
						}
					});
				}

			},
			close: function() {
				$(this).html('');
			}
		});
	});


	// 세자리 숫자 콤마
	Number.prototype.format = function() {
		if (this == 0) return 0;

		var reg = /(^[+-]?\d+)(\d{3})/;
		var n = (this + '');

		while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');

		return n;
	};

	// 문자열 타입에서 쓸 수 있도록 format() 함수 추가
	String.prototype.format = function() {
		var num = parseFloat(this);
		if (isNaN(num)) return "0";

		return num.format();
	};
</script>