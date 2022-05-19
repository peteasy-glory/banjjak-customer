<?php 
include "../include/top.php";
include "../include/Crypto.class.php";

$crypto = new Crypto();

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
?>
<script src="../js/fontawesome.min.js"></script>
<style>
	/* init */
	#manage_1vs1_reply .bjj_top_menu { position: fixed; left: 0px; top: 0px; width: 100%; text-align: center; background-color: #fff; z-index: 100; }
	#manage_1vs1_reply .bjj_top_menu .bjj-back-btn { position: absolute; left: 0px; top: 0px; min-width: 50px; height: 50px; display: flex; justify-content: center; align-items: center; font-size: 24px; }
	#manage_1vs1_reply { position: relative; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; font-size: 14px; font-weight: normal; font-family: 'NL2GR'; }
	#manage_1vs1_reply ul { list-style: none; margin: 0px; padding: 0px; }
	#manage_1vs1_reply textarea { border: 1px solid #ccc; border-radius: 5px; width: calc(100% - 20px); min-height: 60px; padding: 10px; }
	#manage_1vs1_reply button { border: 1px solid #ccc; border-radius: 5px; background-color: #eee; height: 30px; padding: 0px 10px; }

	/* 1vs1 reply */
	#manage_1vs1_reply .manage_1vs1_reply_wrap { margin: 50px 0px; padding-bottom: 50px; }
	#manage_1vs1_reply .manage_1vs1_reply_wrap .reply .reply_wrap { margin-bottom: 10px; padding: 10px 0px; border-bottom: 2px solid #999; }
	#manage_1vs1_reply .manage_1vs1_reply_wrap .reply .reply_wrap>ul { margin: 0px 5px; }
	#manage_1vs1_reply .manage_1vs1_reply_wrap .reply .reply_wrap>ul>li { padding: 0px 5px; }
	#manage_1vs1_reply .manage_1vs1_reply_wrap .reply .reply_wrap>ul>li .body { margin: 5px 0px; padding: 10px 5px; border: 1px solid #ccc; border-radius: 5px; line-height: 20px; }
	#manage_1vs1_reply .manage_1vs1_reply_wrap .reply .reply_wrap .reply_sub { background-color: #f3f3f3; padding: 10px; margin: 10px; text-align: right; }
	#manage_1vs1_reply .manage_1vs1_reply_wrap .reply .reply_wrap .reply_sub textarea { margin: 5px 0px; }
	#manage_1vs1_reply .manage_1vs1_reply_wrap .more_btn_wrap { width: calc(100% - 20px); padding: 10px; }
	#manage_1vs1_reply .manage_1vs1_reply_wrap .more_btn_wrap button { width: 100%; height: 40px; }
</style>

<div id="manage_1vs1_reply">
</div>

<script>
	var $manage_1vs1_reply = $("#manage_1vs1_reply");
	var admin_id = '<?=$user_id ?>';
	var limit_0 = 0;
	var limit_1 = 10;

	$(function(){
		get_customer()
			.then(init_html)
			.then(get_1vs1_reply);
	});

	$manage_1vs1_reply.on("keyup", "textarea", function(){
		resize($(this));
	});

	$manage_1vs1_reply.on("click", ".more_btn", function(){
		limit_0 += limit_1;
		get_1vs1_reply({limit_0: limit_0, limit_1: limit_1});
	});

	$manage_1vs1_reply.on("click", ".set_update_1vs1_reply_sub_btn", function(){
		var type = $(this).data('type');
		var qna_id = $(this).data('qna_id');
		var customer_id = $(this).data('customer_id');
		var sub_seq = (typeof $(this).data('sub_seq') != "undefined")? $(this).data('sub_seq') : "";
		var body = $manage_1vs1_reply.find('.manage_1vs1_reply_wrap .reply_wrap[data-qna_id="'+qna_id+'"] .reply_sub textarea[name="body"]').val();
		set_write_1vs1_reply_sub(type, qna_id, customer_id, sub_seq, body);
	});

	function set_write_1vs1_reply_sub(type, qna_id, customer_id, sub_seq, body){
		return new Promise(function(resolve, reject) {
			//console.log(type, qna_id, customer_id, sub_seq, body);

			var mode = '';
			if(type == "insert"){
				mode = 'set_insert_1vs1_reply_sub';
			}else if(type == "update"){
				mode = 'set_update_1vs1_reply_sub';
			}

			if(mode != ""){
				$.ajax({
					url: 'manage_1vs1_ajax.php',
					data: {
						mode: mode,
						qna_id: qna_id,
						customer_id: customer_id,
						sub_seq: sub_seq,
						body: body
					},
					type: 'POST',
					dataType: 'JSON',
					success: function(data) {
						if(data.code == "000000"){
							console.log(data.data);
							$.MessageBox("반영 되었습니다.");
							get_1vs1_reply_sub(qna_id, customer_id);

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
			}else{
				$.MessageBox("잘못된 접근입니다.");
			}
		});
	}

	function get_customer(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: 'manage_1vs1_ajax.php',
				data: {
					mode: 'get_customer',
					admin_id: admin_id
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						if(data.data && data.data.length > 0){
							resolve();
						}else{
							$.MessageBox("권한이 없습니다.");
							reject();
						}
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
		});
	}

	function init_html(){
		return new Promise(function(resolve, reject) {
			var html = '';

			//html += '';		
			html += '<div class="bjj_top_menu">';
			html += '	<div class="bjj-back-btn"><a href="<?= $admin_directory ?>/"><i class="fas fa-chevron-left"></i></a></div>';
			html += '	<div class="bjj_top_title"><p>1대1 문의 답글달기</p></div>';
			html += '</div>';
			html += '<div class="manage_1vs1_reply_wrap">';
			html += '</div>';
			$manage_1vs1_reply.html(html);
			resolve({limit_0: limit_0, limit_1: limit_1});
		});
	}

	function get_1vs1_reply(post_data){
		return new Promise(function(resolve, reject) {
			$manage_1vs1_reply.find('.manage_1vs1_reply_wrap .more_btn_wrap').remove();

			$.ajax({
				url: 'manage_1vs1_ajax.php',
				data: {
					mode: 'get_1vs1_reply',
					limit_0: post_data.limit_0,
					limit_1: post_data.limit_1
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						var html = '';


						if(data.data && data.data.length > 0){
							html += '<div class="reply">';
							$.each(data.data, function(i, v){
								html += '	<div class="reply_wrap" data-qna_id="'+v.id+'">';
								html += '		<ul>';
								html += '			<li>'+v.update_time+' ('+v.request_main_type+''+v.request_sub_type+')</li>';
								html += '			<li>'+v.customer_id+'</li>';
								html += '			<li>'+v.title+'</li>';
								html += '			<li><div class="body">'+v.body+'</div></li>';
								html += '		</ul>';
								html += '		<div class="reply_sub"></div>';
								html += '	</div>';
							});
							html += '</div>';

							if(data.data.length == post_data.limit_1){
								html += '<div class="more_btn_wrap"><button class="more_btn">더보기</button></div>';
							}
						}else{
							html += '<div class="reply">';
							html += '	<div class="no_data">문의가 없습니다.</div>';
							html += '</div>';
						}
						$manage_1vs1_reply.find('.manage_1vs1_reply_wrap').append(html);

						if(data.data && data.data.length > 0){
							$.each(data.data, function(i, v){
								get_1vs1_reply_sub(v.id, v.customer_id);
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
		});
	}

	function get_1vs1_reply_sub(qna_id, customer_id){
		return new Promise(function(resolve, reject) {
			if(qna_id != ""){
				$manage_1vs1_reply.find('.manage_1vs1_reply_wrap .reply_wrap[data-qna_id="'+qna_id+'"] .reply_sub').html('');
				$.ajax({
					url: 'manage_1vs1_ajax.php',
					data: {
						mode: 'get_1vs1_reply_sub',
						qna_id: qna_id
					},
					type: 'POST',
					dataType: 'JSON',
					success: function(data) {
						if(data.code == "000000"){
							console.log(data.data);
							var html = '';

							if(data.data && data.data.length > 0){
								$.each(data.data, function(i, v){
									html += '		<ul>';
									html += '			<li>'+v.update_time+'</li>';
									html += '			<li><textarea name="body">'+v.body+'</textarea></li>';
									html += '		</ul>';
									html += '		<div class="btn_wrap">';
									html += '			<button type="button" class="set_update_1vs1_reply_sub_btn" data-type="update" data-sub_seq="'+v.sub_seq+'" data-qna_id="'+qna_id+'" data-customer_id="'+customer_id+'">수정</button>';
									html += '		</div>';
								});
							}else{
								html += '		<ul>';
								html += '			<li><textarea name="body"></textarea></li>';
								html += '		</ul>';
								html += '		<div class="btn_wrap">';
								html += '			<button type="button" class="set_update_1vs1_reply_sub_btn" data-type="insert" data-qna_id="'+qna_id+'" data-customer_id="'+customer_id+'">답글달기</button>';
								html += '		</div>';
							}
							$manage_1vs1_reply.find('.manage_1vs1_reply_wrap .reply_wrap[data-qna_id="'+qna_id+'"] .reply_sub').append(html);
							resize($manage_1vs1_reply.find('.manage_1vs1_reply_wrap .reply_wrap[data-qna_id="'+qna_id+'"] .reply_sub textarea'));
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
			}
		});
	}

	function resize(obj) {
		obj.height(1).height( obj.prop('scrollHeight')+12 );	
	}
</script>
<?php include "../include/bottom.php"; ?>