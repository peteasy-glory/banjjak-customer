<?php 
	include "../include/top.php";

	$cl_result = include "../include/check_login.php";
	if ($cl_result == 0) {
		return false;
	}

	$user_id = $_SESSION['gobeauty_user_id'];
	$user_name = $_SESSION['gobeauty_user_nickname'];
?>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<style>
	#shop_entry { margin: 60px 0px; font-family: 'NL2GR'; font-weight: normal; }
	#shop_entry input[type='text'],
	#shop_entry input[type='number'] { border: 0px; border-bottom: 1px solid #ccc; border-radius: 0px; height: 30px; padding: 0px 10px; font-size: 18px; font-family: 'NL2GR'; font-weight: normal; }
	#shop_entry input[type='checkbox'] { display: none; width: 0px; height: 0px; font-size: 0px; margin: 0px; padding: 0px; border: 0px; }
	#shop_entry input[type='checkbox']+label { position: relative; display: inline-block; padding: 0px 10px 0px 35px; height: 30px; line-height: 30px; border: 1px solid #ccc; background-color: #eee; border-radius: 5px; vertical-align: top; }
	#shop_entry input[type='checkbox']+label span { position: absolute; left: 5px; top: 5px; display: inline-block; border: 1px solid #ccc; background-color: #fff; border-radius: 5px; width: 20px; height: 20px; }
	#shop_entry input[type='checkbox']:checked+label { border: 1px solid #f5bf2e; background-color: #f5bf2e; color: #fff; }
	#shop_entry input[type='checkbox']:checked+label span { border: 1px solid #f5bf2e; }
	#shop_entry input[type='checkbox']:checked+label span:before { content: ''; position: absolute; left: 3px; top: 3px; display: inline-block; width: 10px; height: 5px; border-left: 5px solid #f5bf2e; border-bottom: 5px solid #f5bf2e; transform: rotate(-45deg); }
	#shop_entry textarea { border: 1px solid #ccc; min-height: 100px; padding: 10px; font-size: 18px; font-family: 'NL2GR'; font-weight: normal; }
	#shop_entry button { height: 40px; padding: 0px 10px; border: 1px solid #ccc; border-radius: 5px; background-color: #eee; color: #333; font-size: 18px; font-family: 'NL2GR'; font-weight: normal; }
	#shop_entry .title { color: #999; font-size: 12px; padding-bottom: 5px; }
	#shop_entry ul li { padding: 10px; }
	#shop_entry ul li input[type="text"],
	#shop_entry ul li input[type="number"] { width: calc(100% - 20px); }
	#shop_entry ul li textarea { width: calc(100% - 20px); }
	#shop_entry .btn_wrap { position: relative; width: 100%; text-align: center; margin-top: 40px; }
	#shop_entry .btn_wrap button { width: calc(100% - 20px); }
	#shop_entry .btn_wrap button.on { border: 1px solid #f5bf2e; background-color: #f5bf2e; color: #fff; }
</style>

<div class="top_menu">
    <div class="top_back"><a href="<?= $mainpage_directory ?>"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div class="top_title">
        <p>상품입점 / 제휴문의</p>
    </div>
</div>

<div id="shop_entry">
</div>
<script>
	var $shop_entry = $('#shop_entry');
	var customer_id = '<?=$user_id ?>';
	var submit_flag = false;
	
	$(function(){
		get_shop_entry_html();
	});

	$shop_entry.on('keyup', 'input, textarea', function() {
		chk_validate();
	});

	$shop_entry.on('click', 'input[type="checkbox"]', function() {
		chk_validate();
	});

	// textarea auto height
	$shop_entry.on('keyup', 'textarea[name="comment"]', function() {
		$(this).height(1);
		$(this).height((1+$(this).prop("scrollHeight")));
		$(window).scrollTop($("body").height());
	});

	$shop_entry.on("click", ".set_insert_shop_entry_btn", function(){	
		if($(this).hasClass("on") == false){
			return false;
		}

		var chk_flag = 0;
		$shop_entry.find('input[name="entry_type[]"]').each(function(i, v){
			if($(this).is(':checked') == true){
				chk_flag++;
			}
		});
		if(chk_flag == 0){
			$.MessageBox("문의 분류를 선택해주세요.");
			return false;
		}
		if($shop_entry.find('input[name="name"]').val() == ""){
			$.MessageBox({ 
				buttonDone: "확인",
				message: "상호를 입력해주세요."
			}).done(function(){
				$shop_entry.find('input[name="name"]').focus();
			});
			return false;
		}
		if($shop_entry.find('input[name="brand"]').val() == ""){
			$.MessageBox({ 
				buttonDone: "확인",
				message: "브랜드명을 입력해주세요."
			}).done(function(){
				$shop_entry.find('input[name="brand"]').focus();
			});
			return false;
		}
		if($shop_entry.find('input[name="cellphone"]').val() == ""){
			$.MessageBox({ 
				buttonDone: "확인",
				message: "전화번호를 입력해주세요."
			}).done(function(){
				$shop_entry.find('input[name="cellphone"]').focus();
			});
			return false;
		}
		if(isCellphone($shop_entry.find('input[name="cellphone"]').val()) == ""){
			$.MessageBox({ 
				buttonDone: "확인",
				message: "전화번호 형식으로 입력해주세요."
			}).done(function(){
				$shop_entry.find('input[name="cellphone"]').focus();
			});
			return false;
		}
		if($shop_entry.find('input[name="email"]').val() == ""){
			$.MessageBox({ 
				buttonDone: "확인",
				message: "이메일을 입력해주세요."
			}).done(function(){
				$shop_entry.find('input[name="email"]').focus();
			});
			return false;
		}
		if(isEmail($shop_entry.find('input[name="email"]').val()) == ""){
			$.MessageBox({ 
				buttonDone: "확인",
				message: "이메일 형식으로 입력해주세요."
			}).done(function(){
				$shop_entry.find('input[name="email"]').focus();
			});
			return false;
		}
		if($shop_entry.find('textarea[name="comment"]').val() == ""){
			$.MessageBox({ 
				buttonDone: "확인",
				message: "문의내용을 입력해주세요."
			}).done(function(){
				$shop_entry.find('textarea[name="comment"]').focus();
			});
			return false;
		}
		
		$.MessageBox({
			buttonDone: "확인",
			buttonFail: "취소",
			message: "접수 하시겠습니까?"
		}).done(function(){
			set_insert_shop_entry();		
		});
	});

	function get_shop_entry_html(){
		var html = '';
		
		html += '<div class="shop_entry_wrap">';
		html += '	<form id="shop_entry_form" method="POST">';
		html += '		<ul>';
		html += '			<li style="text-align: center; padding: 20px 0px;">';
		html += '				<input type="checkbox" id="entry_type_1" name="entry_type[]" value="1" />';
		html += '				<label for="entry_type_1"><span></span>상품입점</label>';
		html += '				<input type="checkbox" id="entry_type_2" name="entry_type[]" value="2" />';
		html += '				<label for="entry_type_2"><span></span>제휴</label>';
		html += '				<input type="checkbox" id="entry_type_3" name="entry_type[]" value="3" />';
		html += '				<label for="entry_type_3"><span></span>광고</label>';
		html += '			</li>';
		html += '			<li>';
		html += '				<div class="title">상호</div>';
		html += '				<input type="text" name="name" />';
		html += '			</li>';
		html += '			<li>';
		html += '				<div class="title">브랜드명</div>';
		html += '				<input type="text" name="brand" />';
		html += '			</li>';
		html += '			<li>';
		html += '				<div class="title">전화번호</div>';
		html += '				<input type="number" name="cellphone" placeholder="\'-\'없이 숫자" />';
		html += '			</li>';
		html += '			<li>';
		html += '				<div class="title">이메일</div>';
		html += '				<input type="text" name="email" placeholder="user@email.com" />';
		html += '			</li>';
		html += '			<li>';
		html += '				<div class="title">문의내용</div>';
		html += '				<textarea name="comment"></textarea>';
		html += '			</li>';
		html += '		</ul>';
		html += '		<div class="btn_wrap">';
		html += '			<button type="button" class="set_insert_shop_entry_btn">문의 접수</button>';
		html += '		</div>';
		html += '	</form>';
		html += '</div>';
		$shop_entry.html(html);
	}

	function chk_validate(){ // 입력시 검증
		var fchk_flag = 0;
		var chk_flag = 0;
		$shop_entry.find('input[name="entry_type[]"]').each(function(i, v){
			if($(this).is(':checked') == true){
				chk_flag++;
			}
		});
		if(chk_flag == 0){
			fchk_flag++;
		}
		if($shop_entry.find('input[name="name"]').val() == ""){
			fchk_flag++;
		}
		if($shop_entry.find('input[name="brand"]').val() == ""){
			fchk_flag++;
		}
		if($shop_entry.find('input[name="cellphone"]').val() == ""){
			fchk_flag++;
		}
		if($shop_entry.find('input[name="email"]').val() == ""){
			fchk_flag++;
		}
		if($shop_entry.find('textarea[name="comment"]').val() == ""){
			fchk_flag++;
		}

		if(fchk_flag == 0){
			$shop_entry.find('.set_insert_shop_entry_btn').addClass("on");
		}else{
			$shop_entry.find('.set_insert_shop_entry_btn').removeClass("on");
		}
	}

	function set_insert_shop_entry(){
		if(customer_id != "" && submit_flag == false){
			submit_flag = true;
			var post_data = $shop_entry.find("#shop_entry_form").serialize();
			post_data += '&mode=set_insert_shop_entry';
			post_data += '&customer_id='+customer_id;

			$.ajax({
				url: '<?=$mainpage_directory ?>/customer_ajax.php',
				data: post_data,
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						$.MessageBox({
							buttonDone: "확인", 
							message: "<center>문의가 잘 접수되었습니다.<br/>최대한 빠른 시일내에<br/>연락드리도록 하겠습니다.<br/><br/>감사합니다.</center>"
						}).done(function(){
							location.href = "<?=$mainpage_directory ?>/";
						});
					}else{
						alert(data.data+"("+data.code+")");
						console.log(data.data);
					}
				},
				complete: function(){
					submit_flag = false;
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

	function isEmail(asValue) {
		var regExp = /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i;
		return regExp.test(asValue); // 형식에 맞는 경우 true 리턴	
	}

	function isCellphone(asValue) {
		var regExp = /^(01[016789]{1}|02|0[3-9]{1}[0-9]{1})-?[0-9]{3,4}-?[0-9]{4}$/;
		return regExp.test(asValue); // 형식에 맞는 경우 true 리턴	
	}

</script>
<?php include "../include/bottom.php"; ?>
