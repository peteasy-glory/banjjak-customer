<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login.php");
include($_SERVER['DOCUMENT_ROOT']."/include/skin/header.php");


$r_seq = ($_GET["seq"] && $_GET["seq"] != "")? $_GET["seq"] : "";
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
?>
    <link rel="stylesheet" href="m_new.css">
<style>
	table { width: 100%; border-collapse: collapse; margin: 0px; padding: 0px; }
	ul { list-style: none; padding: 0px; margin: 0px; }
	.bjj_top_menu { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; border-bottom: 1px solid #ccc; background-color: rgba(255, 255, 255, 0.8); margin-top: env(safe-area-inset-top); margin-top: constant(safe-area-inset-top); z-index: 10; }
	.bjj_top_menu .bjj-back-btn { position: absolute; left: 8px; top: 12px; }
	.bjj_top_menu .bjj-back-btn img { width: 26px; vertical-align: middle; }
	.bjj_top_menu .bjj_top_title { width: 100%; height: 50px; line-height: 50px; text-align: center; padding: 0px; }
	.bjj_top_menu .bjj_top_title p { padding: 0px; margin: 0px; }

	#main_banner_write { position: relative; width: 100%; margin-top: calc(env(safe-area-inset-top) + 61px); margin-top: calc(constant(safe-area-inset-top) + 61px); font-family: 'NL2GR'; font-weight: normal; }
	#main_banner_write .banner_write { width: calc(100% - 20px); margin: 0px auto 60px; }
	#main_banner_write input[type='text'] { height: 30px; padding: 0px 10px; border: 0px; border-radius: 0px; border-bottom: 1px solid #ccc; }
	#main_banner_write input[type='checkbox'] { display: none; width: 0px; height: 0px; font-size: 0px; margin: 0px; padding: 0px; border: 0px; }
	#main_banner_write input[type='checkbox']+label { display: inline-block; height: 30px; line-height: 30px; white-space: nowrap; padding: 0px 10px; border: 1px solid #ccc; background-color: #eee; border-radius: 5px; font-weight: Bold; }
	#main_banner_write input[type='checkbox']:checked+label { background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
	#main_banner_write button { height: 30px; padding: 0px 10px; border: 1px solid #ccc; background-color: #eee; border-radius: 5px; font-size: 16px; font-family: 'NL2GR'; }
	#main_banner_write .title { font-size: 12px; color: #999; padding-bottom: 5px; }
	#main_banner_write ul li { padding: 10px 0px; }
	#main_banner_write ul li.use_time_box { display: none; }
	#main_banner_write ul li.use_time_box.on { display: block; }
	#main_banner_write ul li .img_list { border: 1px solid #ccc; min-height: 80px; margin-top: 5px; margin-bottom: 10px; text-align: left; padding: 10px; }
	#main_banner_write ul li .img_list .upload_file_wrap { position: relative; display: inline-block; width: 80px; height: 80px; border: 1px solid #eee; }
	#main_banner_write ul li .img_list .upload_file_wrap img { width: 100%; }
	#main_banner_write ul li .img_list .upload_file_wrap .set_delete_file_btn { position: absolute; right: 0px; top: 0px; width: 20px; height: 20px; line-height: 20px; text-align: center; background-color: rgba(255,255,255,0.8); border-radius: 10px; padding-top: 2px; }
	#main_banner_write ul li input[name='title'] { width: calc(100% - 20px); }
	#main_banner_write ul li input[name='comment'] { width: calc(100% - 20px); }
	#main_banner_write ul li input[name='link'] { width: calc(100% - 20px); }
	#main_banner_write ul li input[name='start_date'] { width: calc(50% - 30px); text-align: center; }
	#main_banner_write ul li input[name='start_time'] { width: calc(50% - 30px); text-align: center; }
	#main_banner_write ul li input[name='end_date'] { width: calc(50% - 30px); text-align: center; }
	#main_banner_write ul li input[name='end_time'] { width: calc(50% - 30px); text-align: center; }
	#main_banner_write .btn_wrap { padding: 10px; }
	#main_banner_write .btn_wrap button { width: 100%; height: 40px; }
	#main_banner_write .btn_wrap button.on { background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
	#main_banner_write .btn_wrap button.set_delete_banner_btn { margin-bottom: 20px; }
</style>

<div class="bjj_top_menu">
    <div class="bjj-back-btn"><a href="<?= $admin_directory ?>/main_banner_list.php"><img src="<?= $image_directory ?>/btn_back_2.png"></a></div>
    <div class="bjj_top_title"><p>메인배너 <?=($r_seq != "")? "수정" : "등록"; ?></p></div>
</div>

<div id="main_banner_write">

</div>

<?php // 파일업로드용 ?>
<input id="fileupload" type="file" name="files[]" data-url="<?=$mainpage_directory?>/fileupload_ajax.php" multiple style="display: none;" />

<script>
	var $main_banner_write = $("#main_banner_write");
	var seq = "<?=$r_seq ?>";
	var customer_id = "<?=$user_id ?>";
	var img_list = [];

	$(function(){
		main_banner_write_html()
			.then(get_main_banner);

		$('#fileupload').fileupload({
			formData: {
				mode: "upload_img",
				target: "tb_main_banner.banner",
				folder: "main_banner"
			},
			dataType: 'json',
			done: function (e, data) {
				//console.log(e);
				//console.log(data);
				if(data.result.code == "000000"){
					$.each(data.result.data, function (index, file) {
						//console.log(index);
						//console.log(file);
						var html = '';
						img_list.push(file.f_seq);
						img_list = img_list.filter(function(v){return v!==''}); // remove empty data

						$main_banner_write.find('.img_list').append(html);
						$main_banner_write.find('input[name="banner"]').val(img_list.join(","));
						get_file_list(img_list);
					});

					// item product_img update
					if(seq != "" && img_list.length > 0){
						$.ajax({
							url: '<?=$admin_directory?>/main_banner_ajax.php',
							data: {
								mode: "set_update_main_banner",
								mb_seq: seq,
								banner: img_list.join(",")
							},
							type: 'POST',
							dataType: 'JSON',
							success: function(data) {
								if(data.code == "000000"){
									console.log("product_img update OK");
								}else{
									alert(data.data+"("+data.code+")");
									console.log(data.data);
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
				}else{
					alert(data.result.data+"("+data.result.code+")");
					console.log(data.result.data);
				}
			},
			fail: function(e, data){
				console.log(e);
				console.log(data);
			}
		});
	});

	$main_banner_write.on("click", ".upload_img_btn", function(){
		$("#fileupload").trigger("click");
	});

	$main_banner_write.on("click", ".img_list .set_delete_file_btn", function(){
		var f_seq = $(this).data("seq");
        var result = confirm('해당 이미지를 삭제하시겠습니까?');
        if(result){
            set_delete_banner_img(f_seq);
        }
	});

	$main_banner_write.on("click", "input[name='is_use_time']", function(){
		if($(this).is(":checked") == true){
			$main_banner_write.find(".banner_write ul li.use_time_box").addClass("on");
		}else{
			$main_banner_write.find(".banner_write ul li.use_time_box").removeClass("on");
		}
	});

	$main_banner_write.on("click", ".set_insert_banner_btn", function(){
		// validate
		var validate = validate_chk();
		if(validate === false){
			return false;
		}

		var post_data = $main_banner_write.find("#banner_form").serialize();
		post_data += '&mode=set_insert_main_banner';
		post_data += '&customer_id='+customer_id;
		if($main_banner_write.find("input[name='is_use']").is(":checked") == false){
			post_data += '&is_use=2';
		}
		if($main_banner_write.find("input[name='is_use_time']").is(":checked") == false){
			post_data += '&is_use_time=2';
		}
		if($main_banner_write.find("input[name='target']").is(":checked") == false){
			post_data += '&target=';
		}
		console.log(post_data);
		set_insert_main_banner(post_data);
	});

	$main_banner_write.on("click", ".set_update_banner_btn", function(){
		// validate
		var validate = validate_chk();
		if(validate === false){
			return false;
		}

		var post_data = $main_banner_write.find("#banner_form").serialize();
		post_data += '&mode=set_update_main_banner';
		post_data += '&customer_id='+customer_id;
		post_data += '&mb_seq='+seq;
		if($main_banner_write.find("input[name='is_use']").is(":checked") == false){
			post_data += '&is_use=2';
		}
		if($main_banner_write.find("input[name='is_use_time']").is(":checked") == false){
			post_data += '&is_use_time=2';
		}
		if($main_banner_write.find("input[name='target']").is(":checked") == false){
			post_data += '&target=';
		}
		console.log(post_data);
		set_update_main_banner(post_data);
	});

	$main_banner_write.on("click", ".set_delete_banner_btn", function(){
		$.MessageBox({
			buttonDone: "확인",
			buttonFail: "취소",
			message: "<center>삭제 하시겠습니까?</center>"
		}).done(function(){		
			set_delete_main_banner(seq);
		});
	});

	function validate_chk(){
		if($main_banner_write.find('input[name="banner"]').val() == ""){
			$.MessageBox("사진을 업로드 해주세요.");
			return false;
		}
		if($main_banner_write.find('input[name="title"]').val() == ""){
			$.MessageBox("배너명을 입력 해주세요.");
			return false;
		}
		var type_cnt = 0;
		$main_banner_write.find('input[name="type[]"]').each(function(i, v){
			if($(this).is(":checked") == true){
				type_cnt++;
			}
		});
		if(type_cnt == 0){
			$.MessageBox("게시는 한개 이상 선택해주세요.");
			return false;
		}
	}

	function get_main_banner(){
		return new Promise(function(resolve, reject) {
			if(seq != ""){
				$.ajax({
					url: '<?=$admin_directory ?>/main_banner_ajax.php',
					data: {
						mode: "get_main_banner",
						mb_seq: seq
					},
					type: 'POST',
					dataType: 'JSON',
					success: function(data) {
						if(data.code == "000000"){
							console.log(data.data);
							var html = '';

							if(data.data && data.data.list.length > 0){
								$.each(data.data.list, function(i, v){
									img_list = (v.banner && v.banner != '' && v.banner.indexOf(',') != -1)? v.banner.split(',') : [v.banner];
									var type = (v.type && v.type != '' && v.type.indexOf(',') != -1)? v.type.split(',') : [v.type];
									$main_banner_write.find("input[name='title']").val(v.title);
									$main_banner_write.find("input[name='comment']").val(v.comment);
									$main_banner_write.find("input[name='banner']").val(v.banner);
									if(type && type.length > 0){
										$.each(type, function(i, v){
											$main_banner_write.find("input[name='type[]'][value='"+v+"']").prop("checked", true);
										});
									}
									$main_banner_write.find("input[name='link']").val(v.link);
									$main_banner_write.find(".link_full").html("[Psuh 알림시 이용]<br>" + 'https://gopet.kr' + v.link);	// 
									if(v.target && v.target != ""){
										$main_banner_write.find("input[name='target']").prop("checked", true);
									}
									$main_banner_write.find("input[name='type']").val(v.type);
									if(v.is_use == '1'){
										$main_banner_write.find("input[name='is_use']").prop("checked", true);
									}
									if(v.is_use_time == '1'){
										$main_banner_write.find("input[name='is_use_time']").prop("checked", true);
										$main_banner_write.find(".banner_write ul li.use_time_box").addClass("on");
										$main_banner_write.find("input[name='start_date']").val(v.start_dt.split(' ')[0]);
										$main_banner_write.find("input[name='start_time']").val(v.start_dt.split(' ')[1]);
										$main_banner_write.find("input[name='end_date']").val(v.end_dt.split(' ')[0]);
										$main_banner_write.find("input[name='end_time']").val(v.end_dt.split(' ')[1]);
									}
									$main_banner_write.find("input[name='odr_1']").val(v.odr_1);
									$main_banner_write.find("input[name='odr_2']").val(v.odr_2);
									$main_banner_write.find("input[name='odr_3']").val(v.odr_3);
									$main_banner_write.find("input[name='odr_4']").val(v.odr_4);
									get_file_list(img_list);
								});
							}

							resolve();
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
				resolve();
			}
		});
	}

	function set_insert_main_banner(post_data){
		$.ajax({
			url: '<?=$admin_directory?>/main_banner_ajax.php',
			data: post_data,
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log("OK");
					seq = data.data;
					$(document).find(".bjj_top_title p").text("메인배너 수정");
					$main_banner_write.find(".btn_wrap button").addClass("set_update_banner_btn");
					$main_banner_write.find(".btn_wrap button").removeClass("set_insert_banner_btn");
					$main_banner_write.find(".btn_wrap button.set_update_banner_btn").text("수정");
					$main_banner_write.find(".btn_wrap").prepend('<button type="button" class="set_delete_banner_btn">삭제</button>');
					history.pushState('', '', '<?=$admin_directory ?>/main_banner_write.php?seq='+seq);
					$.MessageBox("입력되었습니다.");
				}else{
					alert(data.data+"("+data.code+")");
					console.log(data.data);
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

	function set_update_main_banner(post_data){
		$.ajax({
			url: '<?=$admin_directory?>/main_banner_ajax.php',
			data: post_data,
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					$.MessageBox("변경되었습니다.");
				}else{
					alert(data.data+"("+data.code+")");
					console.log(data.data);
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

	function set_delete_main_banner(mb_seq){
		$.ajax({
			url: '<?=$admin_directory?>/main_banner_ajax.php',
			data: {
				mode: "set_delete_main_banner",
				delete_id: customer_id,
				delete_msg: "admin.main_banner_write에서 삭제",
				mb_seq: mb_seq
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log("OK");
					$.MessageBox({
						buttonDone: "확인",
						message: "삭제되었습니다."
					}).done(function(){
						location.href = "<?=$admin_directory ?>/main_banner_list.php";
					});
				}else{
					alert(data.data+"("+data.code+")");
					console.log(data.data);
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

	function main_banner_write_html(){
		return new Promise(function(resolve, reject) {
			var html = '';

			html += '<div class="banner_write">';
			html += '	<form id="banner_form" method="POST">';
			html += '	<ul>';
			html += '		<li>';
			html += '			<div class="title">배너</div>';
			html += '			<button type="button" class="upload_img_btn">사진등록</button>';
			html += '			<div class="img_list">업로드된 이미지가 없습니다.</div>';
			html += '			<input type="hidden" name="banner" value="" />';
			html += '			<div class="banner_img_full" style="font-size:10pt;" />';
			html += '		</li>';
			html += '		<li>';
			html += '			<div class="title">게시여부</div>';
			html += '			<input type="checkbox" id="type_1" name="type[]" value="1" />';
			html += '			<label for="type_1">메인</label>';
			html += '			<input type="checkbox" id="type_2" name="type[]" value="2" />';
			html += '			<label for="type_2">미용/호텔</label>';
			html += '			<input type="checkbox" id="type_3" name="type[]" value="3" />';
			html += '			<label for="type_3">상품(강아지)</label>';
			html += '			<input type="checkbox" id="type_4" name="type[]" value="4" />';
			html += '			<label for="type_4">상품(고양이)</label>';
			html += '			<input type="checkbox" id="type_5" name="type[]" value="5" />';
			html += '			<label for="type_5">전문몰</label>';
			html += '		</li>';
			html += '		<li>';
			html += '			<div class="title">배너명</div>';
			html += '			<input type="text" name="title" value="이벤트배너" />';
			html += '		</li>';
			html += '		<li>';
			html += '			<div class="title">배너설명</div>';
			html += '			<input type="text" name="comment" value="" />';
			html += '		</li>';
			html += '		<li>';
			html += '			<div class="title">링크</div>';
			html += '			<input type="text" name="link" value="" />';
			html += '			<div class="link_full" style="font-size:10pt;" />';
			html += '		</li>';
			html += '		<li>';
			html += '			<div class="title">새창여부</div>';
			html += '			<input type="checkbox" id="target" name="target" value="_BLANK" />';
			html += '			<label for="target">사용</label>';
			html += '		</li>';
			html += '		<li>';
			html += '			<div class="title">게시여부</div>';
			html += '			<input type="checkbox" id="is_use" name="is_use" value="1" />';
			html += '			<label for="is_use">사용</label>';
			html += '		</li>';
			// html += '		<li>';
			// html += '			<div class="title">게시일시사용여부</div>';
			// html += '			<input type="checkbox" id="is_use_time" name="is_use_time" value="1" />';
			// html += '			<label for="is_use_time">사용</label>';
			// html += '		</li>';
			// html += '		<li class="use_time_box">';
			// html += '			<div class="title">게시시작일시</div>';
			// html += '			<input type="text" name="start_date" value="" />~';
			// html += '			<input type="text" name="start_time" value="00:00:00" />';
			// html += '		</li>';
			// html += '		<li class="use_time_box">';
			// html += '			<div class="title">게시마감일시</div>';
			// html += '			<input type="text" name="end_date" value="" />~';
			// html += '			<input type="text" name="end_time" value="23:45:00" />';
			// html += '		</li>';
			html += '		<li>';
			html += '			<div class="title">정렬순서 (수동변경)</div>';
			html += '			<ul>';
			html += '				<li>';
			html += '					<span>메인</span>';
			html += '					<input type="text" name="odr_1" value="" placeholder="1-9999 사이 숫자 입력" />';
			html += '				</li>';
			html += '				<li>';
			html += '					<span>미용/호텔</span>';
			html += '					<input type="text" name="odr_2" value="" placeholder="1-9999 사이 숫자 입력" />';
			html += '				</li>';
			html += '				<li>';
			html += '					<span>상품(강아지)</span>';
			html += '					<input type="text" name="odr_3" value="" placeholder="1-9999 사이 숫자 입력" />';
			html += '				</li>';
			html += '				<li>';
			html += '					<span>상품(고양이)</span>';
			html += '					<input type="text" name="odr_4" value="" placeholder="1-9999 사이 숫자 입력" />';
			html += '				</li>';
			html += '			</ul>';
			html += '		</li>';
			html += '	</ul>';
			html += '	</form>';
			html += '</div>';
			html += '<div class="btn_wrap">';
			if(seq != ""){
				html += '	<button type="button" class="set_delete_banner_btn">삭제</button>';
				html += '	<button type="button" class="set_update_banner_btn on">수정</button>';
			}else{
				html += '	<button type="button" class="set_insert_banner_btn on">추가</button>';
			}
			html += '</div>';

			$main_banner_write.html(html);
			// $main_banner_write.find('input[name="start_date"]').datepicker({ dateFormat: 'yy-mm-dd' });
			// $main_banner_write.find('input[name="end_date"]').datepicker({ dateFormat: 'yy-mm-dd' });
			// $main_banner_write.find('input[name="start_time"]').timepicker({ timeFormat: 'HH:mm:ss', interval: 15 });
			// $main_banner_write.find('input[name="end_time"]').timepicker({ timeFormat: 'HH:mm:ss', interval: 15 });
			resolve();
		});
	}

	function get_file_list(img_list){
		if(img_list != ""){
			console.log(img_list);
			img_list = img_list.filter(function(item) {
			return item !== null && item !== undefined && item !== '';
			});
			var tmp_img_list = img_list.join(',');

			$.ajax({
				url: '<?=$mainpage_directory?>/fileupload_ajax.php',
				data: {
					mode: "get_file_list",
					file_list: tmp_img_list
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						var html = '';
						var idx = 0;
						var _lst_img = '';
						$.each(data.data, function(i, v){
							html += '<div class="upload_file_wrap">';
							html += '	<a data-fancybox="gallery" href="https://image.banjjakpet.com'+img_link_change(v.file_path)+'">';
							html += '		<img src="https://image.banjjakpet.com'+img_link_change(v.file_path)+'" alt="'+v.file_name+'" title="'+v.file_name+'" />';
							html += '	</a>';
							html += '	<div class="set_delete_file_btn" data-seq="'+v.f_seq+'"><i class="fas fa-times"></i></div>';
							html += '</div>';
							//
							idx++;
							//
							_lst_img += 'https://gopet.kr' + v.file_path + '<br>\n';	//
						});

						$(".banner_img_full").html("[Psuh 알림시 이용]<br>" +  _lst_img );

						if(idx == 0){
							html += '업로드된 이미지가 없습니다.';
						}
						$main_banner_write.find('.img_list').html(html);
					}else{
						alert(data.data+"("+data.code+")");
						console.log(data.data);
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
	}

	function set_delete_banner_img(f_seq){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$mainpage_directory?>/fileupload_ajax.php',
				data: {
					mode: "set_delete_file",
					f_seq: f_seq,
					user_id: "<?=$user_id ?>",
					delete_txt: "직접 삭제"
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						$.each(img_list, function(i, v){
							if(v == f_seq){
								img_list.splice(i, 1);
							}
						});
						get_file_list(img_list);

						// item product_img update
						$.ajax({
							url: '<?=$admin_directory?>/main_banner_ajax.php',
							data: {
								mode: "set_update_main_banner",
								mb_seq: seq,
								banner: img_list.join(",")
							},
							type: 'POST',
							dataType: 'JSON',
							success: function(data) {
								if(data.code == "000000"){
									console.log("product_img update OK");
									$main_banner_write.find('input[name="banner"]').val(img_list.join(","));
								}else{
									alert(data.data+"("+data.code+")");
									console.log(data.data);
								}
							},
							error: function(xhr, status, error) {
								//alert(error + "네트워크에러");
								if(xhr.status != 0){
									alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
								}
							}
						});
					}else{
						alert(data.data+"("+data.code+")");
						console.log(data.data);
					}
				},
				error: function(xhr, status, error) {
					//alert(error + "네트워크에러");
					if(xhr.status != 0){
						alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
					}
				}
			});	

			resolve();
		});
	}
</script>
