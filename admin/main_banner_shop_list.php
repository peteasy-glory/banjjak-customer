<?php
include "../include/top.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

$r_seq = ($_GET["seq"] && $_GET["seq"] != "")? $_GET["seq"] : "";
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
?>

<script src="<?= $js_directory ?>/fontawesome.min.js"></script>
<style>
	table { width: 100%; border-collapse: collapse; margin: 0px; padding: 0px; }
	ul { list-style: none; padding: 0px; margin: 0px; }
	.bjj_top_menu { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; border-bottom: 1px solid #ccc; background-color: rgba(255, 255, 255, 0.8); margin-top: env(safe-area-inset-top); margin-top: constant(safe-area-inset-top); z-index: 10; }
	.bjj_top_menu .bjj-back-btn { position: absolute; left: 8px; top: 12px; }
	.bjj_top_menu .bjj-back-btn img { width: 26px; vertical-align: middle; }
	.bjj_top_menu .bjj_top_title { width: 100%; height: 50px; line-height: 50px; text-align: center; padding: 0px; }
	.bjj_top_menu .bjj_top_title p { padding: 0px; margin: 0px; }

	#main_banner_shop_list { position: relative; width: 100%; margin-top: calc(env(safe-area-inset-top) + 61px); margin-top: calc(constant(safe-area-inset-top) + 61px); font-family: 'NL2GR'; font-weight: normal; }
	#main_banner_shop_list table { text-align: center; font-size: 12px; max-width: 100%; }
	#main_banner_shop_list table tr th { position: sticky; top: 50px; background-color: #eee; padding: 5px; }
	#main_banner_shop_list table tr td { border: 1px solid #ccc; padding: 2px 5px; }
	#main_banner_shop_list table tr td.lft { text-align: left; }
	#main_banner_shop_list table tr td.rht { text-align: right; }
	#main_banner_shop_list input[type="checkbox"] { display: none; width: 0px; height: 0px; font-size: 0px; margin: 0px; padding: 0px; border: 0px; }
	#main_banner_shop_list input[type="checkbox"]+label { display: inline-block; padding: 0px 5px; height: 20px; line-height: 20px; text-align: center; border-radius: 5px; border: 1px solid #ccc; background-color: #eee; color: #000; white-space: nowrap; }
	#main_banner_shop_list input[type="checkbox"]:checked+label { border: 1px solid #f5bf2e; background-color: #f5bf2e; color: #fff; }
</style>

<div class="bjj_top_menu">
    <div class="bjj-back-btn"><a href="<?= $admin_directory ?>/main_banner_list.php"><img src="<?= $image_directory ?>/btn_back_2.png"></a></div>
    <div class="bjj_top_title"><p>펫샵 추천 관리</p></div>
</div>

<div id="main_banner_shop_list">

</div>

<script>
	var $main_banner_shop_list = $("#main_banner_shop_list");
	var limit_0 = 0;
	var limit_1 = 100;

	$(function(){
		main_banner_shop_list_html()
			.then(get_main_banner_shop_list_cnt);
	});

	$main_banner_shop_list.on("click", "input[type='checkbox']", function(){
		console.log();
		var customer_id = $(this).parent().parent().data('id');
		var name = $(this).attr('name');

		if($(this).is(":checked") == true){
			set_update_shop(customer_id, name, "1");
		}else{
			set_update_shop(customer_id, name, "0");
		}
		
	});

	function set_update_shop(customer_id, name, value){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$admin_directory?>/main_banner_ajax.php',
				data: {
					mode: "set_update_shop",
					customer_id: customer_id,
					column_name: name,
					column_value: value,
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						$.MessageBox("변경되었습니다.");

						resolve();
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
		});
	}

	function main_banner_shop_list_html(){
		return new Promise(function(resolve, reject) {
			var html = '';

			html += '<div class="shop_list">';
			html += '	<table>';
			html += '		<colgroup>';
			html += '			<col width="*" />';
			html += '			<col width="10%" />';
			html += '			<col width="10%" />';
			html += '			<col width="10%" />';
			html += '		</colgroup>';
			html += '		<thead>';
			html += '			<tr>';
			html += '				<th>펫샵명<br/>아이디</th>';
			html += '				<th>추천</th>';
			html += '				<th>핫픽샵</th>';
			html += '				<th>뉴샵</th>';
			html += '			</tr>';
			html += '		</thead>';
			html += '		<tbody class="shop_list_wrap">';
			html += '		</tbody>';
			html += '	</table>';
			html += '</div>';
			$main_banner_shop_list.html(html);
			resolve();
		});
	}

	function get_main_banner_shop_list_cnt(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$admin_directory?>/main_banner_ajax.php',
				data: {
					mode: "get_main_banner_shop_list_cnt"
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);

						for(var _i = 0; _i <= data.data; _i += 100){
							limit_0 = _i;
							get_main_banner_shop_list();
						}
						resolve();
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
		});
	}

	function get_main_banner_shop_list(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$admin_directory?>/main_banner_ajax.php',
				data: {
					mode: "get_main_banner_shop_list",
					limit_0: limit_0,
					limit_1: limit_1
				},
				type: 'POST',
				dataType: 'JSON',
				async:false,
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						//$.MessageBox("변경되었습니다.");
						var html = '';

						$.each(data.data, function(i, v){
							var is_recommend = (v.is_recommend && v.is_recommend == "1")? " checked " : "";
							var is_mainshop_recommend = (v.is_mainshop_recommend && v.is_mainshop_recommend == "1")? " checked " : "";
							var is_mainshop_new = (v.is_mainshop_new && v.is_mainshop_new == "1")? " checked " : "";
							html += '<tr data-id="'+v.customer_id+'">';
							html += '	<td class="lft"><span style="font-weight: Bold;">'+v.name+'</span><br/><span style="color: #999;">'+v.customer_id+'</span></td>';
							html += '	<td><input type="checkbox" id="is_recommend_'+(limit_0+i)+'" name="is_recommend" value="1" '+is_recommend+' /><label for="is_recommend_'+(limit_0+i)+'">추천</label></td>';
							html += '	<td><input type="checkbox" id="is_mainshop_recommend_'+(limit_0+i)+'" name="is_mainshop_recommend" value="1" '+is_mainshop_recommend+' /><label for="is_mainshop_recommend_'+(limit_0+i)+'">노출</label></td>';
							html += '	<td><input type="checkbox" id="is_mainshop_new_'+(limit_0+i)+'" name="is_mainshop_new" value="1" '+is_mainshop_new+' /><label for="is_mainshop_new_'+(limit_0+i)+'">노출</label></td>';
							html += '<tr>';
						});
						
						$main_banner_shop_list.find('.shop_list_wrap').append(html);
						resolve();
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
		});
	}
</script>

<?php
    include "../include/bottom.php";
?>