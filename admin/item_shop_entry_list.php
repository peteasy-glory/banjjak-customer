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

	#item_shop_entry_list { margin: 60px 0px; }
	#item_shop_entry_list ul { list-style: none; margin: 0px; padding: 0px; border: 0px; }
	#item_shop_entry_list .item_shop_entry_data { padding-bottom: 60px; }
	#item_shop_entry_list .item_shop_entry_data>div { border: 1px solid #ccc; width: calc(100% - 20px); margin: 10px auto; border-radius: 10px; background-color: #fcfcfc; }
	#item_shop_entry_list .item_shop_entry_data>div ul {  }
	#item_shop_entry_list .item_shop_entry_data>div ul li { padding: 10px 0px; background-color: #f9f9f9; border-radius: 10px; }
	#item_shop_entry_list .item_shop_entry_data>div ul li:first-child { background-color: #eee; position: relative; text-align: center; height: 20px; line-height: 10px; border-radius: 10px 10px 0px 0px;  }
	#item_shop_entry_list .item_shop_entry_data>div ul li:first-child .entry_type { position: absolute; left: 10px; top: 15px; font-size: 12px; }
	#item_shop_entry_list .item_shop_entry_data>div ul li:first-child .reg_dt { font-size: 12px; }
	#item_shop_entry_list .item_shop_entry_data>div ul li:first-child .name { position: absolute; right: 10px; top: 15px; }
	#item_shop_entry_list .item_shop_entry_data>div ul li .title { margin: 0px 10px; font-size: 12px; color: #999; }
	#item_shop_entry_list .item_shop_entry_data>div ul li .cellphone { margin: 0px 10px; }
	#item_shop_entry_list .item_shop_entry_data>div ul li .email { margin: 0px 10px; }
	#item_shop_entry_list .item_shop_entry_data>div ul li .comment { background-color: #fff; border: 1px solid #eee; margin: 0px 10px; padding: 10px; width: calc(100% - 40px); min-height: 100px; word-break: break-all; }
	#item_shop_entry_list .item_shop_entry_data .more_btn { height: 40px; line-height: 40px; text-align: center; border: 1px solid #ccc; background-color: #eee; cursor: pointer; }
</style>

<div class="bjj_top_menu">
    <div class="bjj-back-btn"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/btn_back_2.png"></a></div>
    <div class="bjj_top_title"><p>입점/제휴/광고 문의 내역</p></div>
</div>

<div id="item_shop_entry_list">
</div>

<script>
	var $item_shop_entry_list = $("#item_shop_entry_list");
	var entry_type_arr = ['', '입점신청', '제휴', '광고'];
	var flag = 0;
	var cnt = 10;

	$(function(){
		get_item_shop_entry_html()
			.then(get_item_shop_entry_list)
	});

	function get_item_shop_entry_html(){
		return new Promise(function(resolve, reject) {
			var html = '';

			html += '<div class="item_shop_entry_list_wrap">';
			html += '	<div class="item_shop_entry_data">';
			html += '	</div>';
			html += '</div>';
			
			$item_shop_entry_list.html(html);
			resolve();
		});
	}

	$item_shop_entry_list.on("click", ".more_btn", function(){
		flag += cnt;
		get_item_shop_entry_list();
	});

	function get_item_shop_entry_list(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$mainpage_directory?>/customer_ajax.php',
				data: {
					mode : "get_shop_entry"
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);

						var html = '';
						if(data.data && parseInt(data.data.total_cnt) > 0){
							$item_shop_entry_list.find('.more_btn').remove();

							$.each(data.data.list, function(i, v){
								var entry_type_list = (v.entry_type && v.entry_type.indexOf(','))? v.entry_type.split(',') : [v.entry_type];
								$.each(entry_type_list, function(i, v){
									entry_type_list[i] = entry_type_arr[entry_type_list[i]];
								});
								var reg_dt = (v.reg_dt && v.reg_dt != "")? new Date(v.reg_dt.replace(/-/g, '/')) : ""; // ios cross browsing
								reg_dt = (reg_dt != "")? reg_dt.getFullYear()+'-'+fillZero('2', reg_dt.getMonth()+1)+'-'+fillZero('2', reg_dt.getDate())+'<br/>'+fillZero('2', reg_dt.getHours())+':'+fillZero('2', reg_dt.getMinutes()) : "";
								html += '<div>';
								html += '	<ul>';
								html += '		<li>';
								html += '			<div class="entry_type">'+entry_type_list.join(',')+'</div>';
								html += '			<div class="reg_dt">'+reg_dt+'</div>';
								html += '			<div class="name">'+v.name+'</div>';
								html += '		</li>';
								html += '		<li>';
								html += '			<div class="title">연락처</div>';
								html += '			<div class="cellphone">'+v.cellphone+'</div>';
								html += '		</li>';
								html += '		<li>';
								html += '			<div class="title">이메일</div>';
								html += '			<div class="email">'+v.email+'</div>';
								html += '		</li>';
								html += '		<li>';
								html += '			<div class="comment">'+v.comment+'</div>';
								html += '		</li>';
								html += '	</ul>';
								html += '</div>';							
							});
						}

						if(data.data.list_cnt == 10 && data.data.total_cnt != data.data.list_cnt){
								html += '<div>';
								html += '	<div class="more_btn">더보기 ('+data.data.list_cnt+' / '+data.data.total_cnt+')</div>';
								html += '</div>';
						}

						$item_shop_entry_list.find('.item_shop_entry_data').append(html);
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

	//남는 길이만큼 0으로 채움
	function fillZero(width, str){
		var str = String(str);//문자열 변환
		return str.length >= width ? str:new Array(width-str.length+1).join('0')+str;
	}
</script>