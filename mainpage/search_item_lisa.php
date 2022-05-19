<?php
include "../include/top.php";
include "../include/Crypto.class.php";

$r_top = ($_GET['top'] && $_GET['top'] != "")? $_GET['top'] : "서울";
$r_middle = ($_GET['middle'] && $_GET['middle'] != "")? $_GET['middle'] : "";
$r_selected_sort = ($_GET['selected_sort'] && $_GET['selected_sort'] != "")? $_GET['selected_sort'] : "강아지";
$user_id = isset($_SESSION['gobeauty_user_id']) ? $_SESSION['gobeauty_user_id'] : "";
$user_name = isset($_SESSION['gobeauty_user_nickname']) ? $_SESSION['gobeauty_user_nickname'] : "";
$rank_one = $_GET["one"];
$rank_two = $_GET["two"];
$rank_three = $_GET["three"];
$rank_four = $_GET["four"];
$rank_five = $_GET["five"];
//$test = "사료"
?>
<!-- <link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>"> -->
<link rel="stylesheet" href="<?= $css_directory ?>/m_new_3.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new_3.css') ?>">
<link rel="stylesheet" href="<?= $css_directory ?>/m_layout.css?<?= filemtime($upload_static_directory . $css_directory . '/m_layout.css') ?>">
<script src="<?= $js_directory ?>/fontawesome.min.js"></script>
<style>
	/*#main-content .button_wrap { position: relative; margin-top: 30px; }*/
	#map { background:none!important; }
	.loading { position: absolute; left: 50%; top: 50%; width: 30px; height: 23px; margin-top: 7px; text-align: center; margin-left: -15px; margin-top: -15px; }
	.messagebox_overlay { z-index: 101; }
	.main-content .location_tab button img{width:25px;}
	.search_btn{margin-left:10px;margin-right:5px;width:20px;height:20px;text-indent:-99999px;background: url("/pet/images/search2.png") no-repeat center center;outline:none;border:none;background-size:contain;}

</style>
<div id="main-content" style=" padding-bottom: calc(constant(safe-area-inset-bottom) + 40px); padding-bottom: calc(env(safe-area-inset-bottom) + 40px);">
	<div id="fixed-menu">
		<div class="top_menu">
			<div class="header-back-btn"><a href="<?= $mainpage_directory ?>/"><img src="/pet/images/btn_back_2.png" width="26px"></a></div>
			<div class="top_title">
				<p>상품 검색</p>
			</div>
			<div class="top_reflash"><a href="<?=$mainpage_directory?>/"><img src="../images/reflash.png"></a></div>
		</div>
	</div>
	<div class="location_tab" style="margin-top:58px;">
		<div style="position:fixed;left:-7px;top:25px;background:#fff;z-index:10;width:100%;">
			<form action="search_item_1.php" method="GET" style="">
				<input type="hidden" name="selected_sort" value="<?=$r_selected_sort ?>">
				<input type="text" name="word" id="search_name" placeholder="어떤 상품을 찾으세요?"  autofocus value="" style="outline:none;border:none;margin-left:-10px;background:#efefef;padding:10px 0px 10px 10px;width:70%;">
				<input type="submit" class="search_btn" name="button" value="검색">
				<input type="button" value="닫기" style="outline:none;border:none;" onClick="location.href='https://gopet.kr/pet/mainpage/index_lisa.php?tab=3'">
			</form>
		</div>
	</div>
	
	<div id="event_7">
		<div id="event" style="margin-top:100px;">
			<div class="location_top_wrap">
				<input type="hidden" name="top" value="<?=$r_top ?>" />
				<input type="hidden" name="middle" value="<?=$r_middle ?>" />
				<div id="map" class="location_box" style="width:100%;position:relative;background:none!important;">
					<div class="location_bok">
				<div class="location_top" style="font-size:16px;margin-left:-10px;margin-top:30px;">인기검색어</div>
				
						<div class="location_middle_wrap" style="height:200px;background:none;padding:0px 20px 0px 20px;box-sizing:border-box;">
<!-- 							<div class="location_middl" style="height:40px;border-bottom:1px solid #ccc;line-height:40px;font-size:14px;color:#f6bf2e;">1 -->
<!-- 								<a href="search_item_3_1.php?word=<?=$test ?>"  style="margin-left:7px;"><?=$test ?></a> -->
<!-- 							</div> -->
<!-- 							<div class="location_middl" style="height:40px;border-bottom:1px solid #ccc;line-height:40px;font-size:14px;color:#f6bf2e;">2 -->
<!-- 								<a href="search_item_3_1.php?word=반짝"  style="margin-left:7px;">반짝</a> -->
<!-- 							</div> -->
<!-- 							<div class="location_middl" style="height:40px;border-bottom:1px solid #ccc;line-height:40px;font-size:14px;color:#f6bf2e;">3 -->
<!-- 								<a href="search_item_3_1.php?word=간식"  style="margin-left:7px;">간식</a> -->
<!-- 							</div> -->
<!-- 							<div class="location_middl" style="height:40px;border-bottom:1px solid #ccc;line-height:40px;font-size:14px;color:#f6bf2e;">4 -->
<!-- 								<a href="search_item_3_1.php?word=오로라펫"  style="margin-left:7px;">오로라펫</a> -->
<!-- 							</div> -->
<!-- 							<div class="location_middl" style="height:40px;border-bottom:1px solid #ccc;line-height:40px;font-size:14px;color:#f6bf2e;">5 -->
<!-- 								<a href="search_item_3_1.php?word=펫츠프라임"  style="margin-left:7px;">펫츠프라임</a> -->
<!-- 							</div> -->
						</div>
					</div>
				</div>
			</div>
			<div class="location_bottom_wrap">
<!-- 				<img src="../images/dental.jpg" onclick="location.href='../mainpage/view_event16.php'" style="width:100%;padding:20px 20px;box-sizing:border-box;"> -->
			</div>
		</div>
	</div>
</div>

<script>
var $beauty_item_payment = $("#map");
var selected_sort = "<?=$r_selected_sort ?>";

$(function(){
		get_beauty_list()
		.then(get_search_banner);
	});

function get_beauty_list(){
	return new Promise(function(resolve, reject) {

		$.ajax({
			url: '../admin/item_search_manage_ajax.php',
			data: {
				mode : "get_rank"
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					var rebuy = 0;
					var html = '';

					if(data.data && data.data.length > 0){
						$.each(data.data, function(i, v){
							html += '							<div class="location_middl" style="height:40px;border-bottom:1px solid #ccc;line-height:40px;font-size:14px;color:#f6bf2e;" onclick="location.href=\'search_item_1.php?selected_sort='+selected_sort+'&word='+v.search+'\'">'+v.num;
							html += '								<a href="search_item_1.php?selected_sort='+selected_sort+'&word='+v.search+'"  style="margin-left:7px;">'+v.search+'</a>';
							html += '							</div>';
							
							if(i > 0){
								rebuy += Number(v.search);
							}
						});
						$beauty_item_payment.find('.location_bok .location_middle_wrap').html(html);
						console.log();

					}else{
						//html += '			<tr>';
						//html += '				<td colspan="3">';
						//html += '					<div class="no_data">등록된 파트너가 없습니다.</div>';
						//html += '				<td>';
						//html += '			</tr>';
						//$item_partner_list.append(html);
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

function get_search_banner(){
	return new Promise(function(resolve, reject) {
		$.ajax({
			url: '../admin/item_search_manage_ajax.php',
			data: {
				mode: "get_search_banner",
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					var html = '';

					if(data.data && data.data.length > 0){
						$.each(data.data, function(i, v){
							var link = "'"+v.link+"'";
//							html += '<tr id="sort_'+i+'" data-id="'+v.mb_seq+'">';
//							html += '	<td class="">'+link+'</td>';
//							html += '	<td><div class="banner_img"></div></td>';
//							html += '	<td class="lft update_banner_btn">'+v.title+'</td>';
//							html += '</tr>';
							html += '<img src="" id="banner_img" onclick="location.href='+link+'" style="width:100%;padding:45px 20px;box-sizing:border-box;">'
						});
						$(".location_bottom_wrap").append(html);
						$.each(data.data, function(i, v){
							get_file_list(v.banner);
						});
					}else{
//						html += '<tr>';
//						html += '	<td colspan="4" class="no_data">등록된 배너가 없습니다.</td>';
//						html += '</tr>';
//						$(".location_bottom_wrap").find(".banner_list_wrap").append(html);
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
	});
}

// 배너사진 노출
function get_file_list(img_list){
	return new Promise(function(resolve, reject) {
		console.log(img_list);
		// img_loading
		if(img_list && img_list != ""){
			$.ajax({
				url: '../test/test_fileupload_ajax.php',
				data: {
					mode : "get_file_list",
					file_list: img_list
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						var html = '';
						$.each(data.data, function(i, v){
							if(i == 0){
//								$(".banner_img").src = v.file_path; //css("background-image", "url('"+v.file_path+"')");
								$("#banner_img").attr("src", v.file_path);
							}
						});

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
			$(".location_bottom_wrap").find(".banner_img").css("background-image", "url('../images/product_img.png')");
		}
	});
}
</script>

<?php include "../include/bottom.php"; ?>