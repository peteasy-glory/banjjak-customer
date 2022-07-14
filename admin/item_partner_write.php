<?php
include "../include/top.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

$backurl = $_GET["backurl"];
$r_ip_seq = ($_GET["seq"] && $_GET["seq"] != "")? $_GET["seq"] : "";
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

$banks['003'] = "기업은행";
$banks['004'] = "국민은행";
$banks['011'] = "농협중앙회";
$banks['012'] = "단위농협";
$banks['020'] = "우리은행";
$banks['031'] = "대구은행";
$banks['005'] = "외환은행";
$banks['023'] = "SC제일은행";
$banks['032'] = "부산은행";
$banks['045'] = "새마을금고";
$banks['027'] = "한국씨티은행";
$banks['034'] = "광주은행";
$banks['039'] = "경남은행";
$banks['007'] = "수협";
$banks['048'] = "신협";
$banks['037'] = "전북은행";
$banks['035'] = "제주은행";
$banks['064'] = "산림조합";
$banks['071'] = "우체국";
$banks['081'] = "하나은행";
$banks['088'] = "신한은행";
$banks['090'] = "카카오뱅크";
$banks['209'] = "동양종금증권";
$banks['243'] = "한국투자증권";
$banks['240'] = "삼성증권";
$banks['230'] = "미래에셋";
$banks['247'] = "우리투자증권";
$banks['218'] = "현대증권";
$banks['266'] = "SK증권";
$banks['278'] = "신한금융투자";
$banks['262'] = "하이증권";
$banks['263'] = "HMC증권";
$banks['267'] = "대신증권";
$banks['270'] = "하나대투증권";
$banks['279'] = "동부증권";
$banks['280'] = "유진증권";
$banks['287'] = "메리츠증권";
$banks['291'] = "신영증권";
$banks['238'] = "대우증권";
asort($banks); // 가나다순
?>

<script src="../js/fontawesome.min.js"></script>
<style>
	.bjj_top_menu { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; border-bottom: 1px solid #ccc; background-color: #fff; z-index: 10; }
	.bjj_top_menu .bjj-back-btn { position: absolute; left: 0px; top: 0px; display: flex; justify-content: center; align-items: center; width: 50px; height: 50px; font-size: 24px; }
	.bjj_top_menu .bjj-back-btn a { color: #000; }
	/*.bjj_top_menu .bjj-back-btn img { width: 26px; vertical-align: middle; }*/
	.bjj_top_menu .bjj_top_title { width: 100%; height: 50px; line-height: 50px; text-align: center; padding: 0px; color: #000; }
	.bjj_top_menu .bjj_top_title p { padding: 0px; margin: 0px; }
	.scroll_top { display: none; position: fixed; right: 10px; bottom: 10px; z-index: 1; width: 50px; height: 50px; background-color: rgba(255, 255, 255, 0.6); border: 1px solid #ccc; border-radius: 25px; display: none; -webkit-align-items: center; -webkit-justify-content: center; }
	.scroll_top.on { display: flex; }
	
	#item_partner_list ul { list-style: none; padding: 0px; margin: 0px; }
	#item_partner_list input[type="text"],
	#item_partner_list input[type="number"] { border: 0px; border-bottom: 1px solid #ccc; background-color: transparent; padding: 5px; margin: 0px; height: 24px; }
	#item_partner_list select { border: 1px solid #ccc; background-color: transparent; padding: 5px; margin: 0px; height: 40px; font-size: 16px; }
	#item_partner_list textarea { border: 1px solid #999; padding: 10px; font-family: 'NL2GR'; font-weight: normal; font-size: 14px; }
	#item_partner_list button { height: 30px; padding: 0px 10px; border: 1px solid #ccc; background-color: #eee; border-radius: 5px; }

	#item_partner_list { position: relative; width: 100%; margin: 60px 0px; font-family: 'NL2GR'; font-size: 14px; padding-bottom: 60px; }
	#item_partner_list>div { width: calc(100% - 20px); margin: 10px auto; }
	#item_partner_list .title { color: #999; padding: 5px; }
	#item_partner_list .title.mandatory { color: #f5bf2e; } 
	#item_partner_list ul li { padding: 10px 0px; }
	#item_partner_list ul li input[type='text'] { width: calc(100% - 10px); font-size: 16px; margin: 5px 0px; }
	#item_partner_list ul li input[type='number'] { width: calc(100% - 35px); font-size: 16px; text-align: right; padding-right: 25px; }
	#item_partner_list ul li textarea { width: calc(100% - 20px); height: 60px; }
	#item_partner_list ul li select { width: 100%; }
	#item_partner_list ul li input[name='partner_id'] { width: calc(100% - 120px); }
	#item_partner_list ul li input[name='yield'] { position: relative; }
	#item_partner_list ul li .percent { position: relative; }
	#item_partner_list ul li .percent:after { content: '%'; position: absolute; right: 10px; top: 7px; }
	#item_partner_list .btn_wrap { width: 100%; margin: 20px 0px; }
	#item_partner_list .btn_wrap button { width: 100%; height: 40px; font-size: 16px; }
	#item_partner_list .btn_wrap button.on { background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
</style>

<div class="bjj_top_menu">
	<?php if($backurl){ ?>
    <div class="bjj-back-btn"><a href="<?= $backurl ?>"><i class="fas fa-chevron-left"></i></a></div>
	<?php }else{ ?>
	<div class="bjj-back-btn"><a href="<?= $admin_directory ?>/"><i class="fas fa-chevron-left"></i></a></div>
	<?php } ?>
	<div class="bjj_top_title"><p>파트너 리스트</p></div>
</div>
<div class="scroll_top"><i class="fas fa-chevron-up"></i></div>
<div id="item_partner_list"></div>

<script>
	var $item_partner_list = $("#item_partner_list");
	var ip_seq = "<?=$r_ip_seq ?>"; // 번호
	var banks = $.parseJSON('<?=json_encode($banks)?>');
	var _banks = [];
	// asort
	$.each(banks, function(i, v){
		_banks.push({key: i, value: v});
	});
	_banks.sort(function(a, b){
		return (a.value < b.value)? -1 : (a.value > b.value)? 1 : 0;
	});

	$(function(){
		init_html()
			.then(get_item_partner_list);
	});

	$item_partner_list.on('focus focusout', 'input, select', function(){
		simple_validate();
	});

	function simple_validate(){
		var flag = 1;
		if($item_partner_list.find('input[name="partner_id"]').val() == ''){
			flag = 0;
		}else{
			if(isEmail($item_partner_list.find('input[name="partner_id"]').val()) != true){
				flag = 0;
			}
		}
		if($item_partner_list.find('.chk_customer').val() == ''){
			flag = 0;
		}

		if(flag == 1){
			$item_partner_list.find('.set_write_item_partner_btn').addClass('on');
		}else{
			$item_partner_list.find('.set_write_item_partner_btn').removeClass('on');
		}
	}

	$item_partner_list.on('click', '.set_write_item_partner_btn', function(){
		if($item_partner_list.find('.set_write_item_partner_btn').hasClass('on')){
			vaildate();
		}
	});

	function vaildate(){
		if($item_partner_list.find('input[name="partner_id"]').val() == ''){
			$.MessageBox({ 
				message: "담당자 ID를 입력해주세요."
			}).done(function(){
				$item_partner_list.find('input[name="partner_id"]').focus();
			});

			return false;
		}else{
			if(isEmail($item_partner_list.find('input[name="partner_id"]').val()) != true){
				$.MessageBox({ 
					message: "이메일 형식으로 입력해주세요."
				}).done(function(){
					$item_partner_list.find('input[name="partner_id"]').focus();
				});

				return false;
			}
		}

		if($item_partner_list.find('.chk_customer').val() == ''){
			$.MessageBox({ 
				message: "회원 중복체크를 해주세요."
			}).done(function(){
				$item_partner_list.find('input[name="partner_id"]').focus();
			});

			return false;
		}

		var _msg = (ip_seq != "")? "수정 하시겠습니까?" : "입력 하시겠습니까?";

		$.MessageBox({
			buttonDone: "확인",
			buttonFail: "취소",
			message: _msg
		}).done(function(){
			set_white_item_partner()
				.then(init_html)
				.then(get_item_partner_list);
		});

	}

	function isEmail(asValue) {
		var regExp = /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i;
		return regExp.test(asValue); // 형식에 맞는 경우 true 리턴	
	}

	$item_partner_list.on('change', '.select_bank', function(){
		var _this = $(this);
		$item_partner_list.find('input[name="bank_name"]').val(_this.find('option:selected').text());
		$item_partner_list.find('input[name="bank_code"]').val(_this.find('option:selected').val());
	});

	$item_partner_list.on('click', '.chk_customer_btn', function(){
		return new Promise(function(resolve, reject) {
			var partner_id = $item_partner_list.find('input[name="partner_id"]').val();

			if(partner_id != ""){
				if(isEmail($item_partner_list.find('input[name="partner_id"]').val()) == true){
					$.ajax({
						url: '<?=$partner_directory ?>/partner_ajax.php',
						data: {
							mode: "chk_customer",
							partner_id: partner_id,
							ip_seq: ip_seq
						},
						type: 'POST',
						dataType: 'JSON',
						beforeSend: function(){
							$("#loading").css("display", "flex");
						},
						success: function(data) {
							$("#loading").css("display", "none");
							if(data.code == "000000"){
								console.log(data.data);

								if(data.data && data.data.length == 0){
									$item_partner_list.find('.chk_customer').val('1');
									$.MessageBox("등록 가능합니다.");
								}else{
									$item_partner_list.find('.chk_customer').val('');
									$item_partner_list.find('input[name="partner_id"]').val('');
									$.MessageBox({ 
										message:"이미 등록된 파트너 회원입니다."
									}).done(function(){
										$item_partner_list.find('input[name="partner_id"]').focus();
									});
								}
								
								resolve();
							}else{
								alert(data.data+"("+data.code+")");
								console.log(data.code);
							}
						},
						error: function(xhr, status, error) {
							$("#loading").css("display", "none");
							//alert(error + "네트워크에러");
							if(xhr.status != 0){
								alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
							}
						}
					});
				}else{
					$.MessageBox({ 
						message:"이메일 형식으로 입력해주세요."
					}).done(function(){
						$item_partner_list.find('.chk_customer').val('');
						$item_partner_list.find('input[name="partner_id"]').focus();
					});
				}
			}else{
				$.MessageBox({ 
					message:"담당자 ID를 입력해주세요."
				}).done(function(){
					$item_partner_list.find('.chk_customer').val('');
					$item_partner_list.find('input[name="partner_id"]').focus();
				});
			}
		});
	});
	
	function init_html(){
		return new Promise(function(resolve, reject) {
			let html = '';
			let _btn_txt = (ip_seq != "")? "수정" : "입력";
			
			html += '<div>';
			html += '	<form id="item_partner_form" method="POST">';
			html += '		<input type="hidden" name="ip_seq" value="'+ip_seq+'" />';
			html += '		<ul>';
			html += '			<li>';
			html += '				<div class="title mandatory">담당자 ID *</div>';
			html += '				<div>';
			html += '					<input type="hidden" class="chk_customer" value="" />';
			html += '					<input type="text" name="partner_id" value="" placeholder="아이디" />';
			html += '					<button type="button" class="chk_customer_btn">회원 중복체크</button>';
			html += '				</div>';
			html += '			</li>';
			html += '			<li>';
			html += '				<div class="title">회사명</div>';
			html += '				<div>';
			html += '					<input type="text" name="company_name" value="" placeholder="회사명" />';
			html += '				</div>';
			html += '			</li>';
			html += '			<li>';
			html += '				<div class="title">수수료율(%) (VAT 별도)</div>';
			html += '				<div class="percent">';
			html += '					<input type="number" name="yield" value="" placeholder="퍼센트로 입력" />';
			html += '				</div>';
			html += '			</li>';
			html += '			<li>';
			html += '				<div class="title">정산정보</div>';
			html += '				<div>';
			html += '					<select class="select_bank">';
			html += '						<option value="">선택</option>';
			$.each(_banks, function(i, v){
				html += '						<option value="'+v.key+'">'+v.value+'</option>';
			});
			html += '					</select>';
			html += '					<input type="hidden" name="bank_name" value="" placeholder="은행명" />';
			html += '					<input type="hidden" name="bank_code" value="" placeholder="은행코드" />';
			html += '					<input type="text" name="bank_account" value="" placeholder="계좌번호(\'-\'없이)" />';
			html += '					<input type="text" name="bank_user" value="" placeholder="계좌주" />';
			html += '				</div>';
			html += '			</li>';
			html += '			<li>';
			html += '				<div class="title">설명</div>';
			html += '				<div>';
			html += '					<textarea name="comment"></textarea>';
			html += '				</div>';
			html += '			</li>';
			html += '		</ul>';
			html += '	</form>';
			html += '	<div class="btn_wrap">';
			html += '		<button type="button" class="set_write_item_partner_btn">'+_btn_txt+'</button>';
			html += '	</div>';
			html += '</div>';
			$item_partner_list.html(html);
			resolve();
		});
	}

	function get_item_partner_list(){
		return new Promise(function(resolve, reject) {
			if(ip_seq != ""){ // update
				$.ajax({
					url: '<?=$partner_directory ?>/partner_ajax.php',
					data: {
						mode : "get_item_partner_list",
						ip_seq: ip_seq
					},
					type: 'POST',
					dataType: 'JSON',
					beforeSend: function(){
						$("#loading").css("display", "flex");
					},
					success: function(data) {
						$("#loading").css("display", "none");
						if(data.code == "000000"){
							console.log(data.data);

							if(data.data && data.data.length > 0){
								$.each(data.data, function(i, v){
									$item_partner_list.find('.chk_customer').val('1');
									$item_partner_list.find('input[name="partner_id"]').val(v.partner_id);
									$item_partner_list.find('input[name="company_name"]').val(v.company_name);
									$item_partner_list.find('input[name="yield"]').val(v.yield);
									$item_partner_list.find('input[name="bank_name"]').val(v.bank_name);
									$item_partner_list.find('input[name="bank_account"]').val(v.bank_account);
									$item_partner_list.find('input[name="bank_user"]').val(v.bank_user);
									$item_partner_list.find('input[name="bank_code"]').val(v.bank_code);
									$item_partner_list.find('select.select_bank').val(v.bank_code);
									$item_partner_list.find('textarea[name="comment"]').val(v.comment);
									$item_partner_list.find('.set_write_item_partner_btn').addClass('on');
								});
							}
							
							resolve();
						}else{
							alert(data.data+"("+data.code+")");
							console.log(data.code);
						}
					},
					error: function(xhr, status, error) {
						$("#loading").css("display", "none");
						//alert(error + "네트워크에러");
						if(xhr.status != 0){
							alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
						}
					}
				});
			}else{
				resolve(); // insert
			}
		});
	}

	function set_white_item_partner(){
		return new Promise(function(resolve, reject) {
			var post_data = $item_partner_list.find("#item_partner_form").serialize();
			if(ip_seq != ""){ // update
				post_data += '&mode=set_update_item_partner';
			}else{
				post_data += '&mode=set_insert_item_partner';
			}

			$.ajax({
				url: '<?=$partner_directory ?>/partner_ajax.php',
				data: post_data,
				type: 'POST',
				dataType: 'JSON',
				beforeSend: function(){
					$("#loading").css("display", "flex");
				},
				success: function(data) {
					$("#loading").css("display", "none");
					if(data.code == "000000"){
						console.log(data.data);
						if(ip_seq != ""){ // update
							$.MessageBox("수정되었습니다.");
						}else{
							$.MessageBox("등록되었습니다.");
							ip_seq = data.data; // mysql_insert_id
							history.replaceState('', '', window.location.pathname+'?seq='+ip_seq+'&backurl=<?=$backurl ?>');
						}
						resolve();
					}else{
						alert(data.data+"("+data.code+")");
						console.log(data.code);
					}
				},
				error: function(xhr, status, error) {
					$("#loading").css("display", "none");
					//alert(error + "네트워크에러");
					if(xhr.status != 0){
						alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
					}
				}
			});
		});
	}
</script>