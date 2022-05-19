<?php
include "../include/configure.php";
include "../include/session.php";
include "../include/db_connection.php";
include "../include/php_function.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

// init
$r_no = ($_GET["no"] && $_GET["no"] != "")? $_GET["no"] : "";
$backurl = $_GET['backurl'];
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width" />

<script src="<?= $js_directory ?>/jquery-3.3.1.min.js"></script>
<link rel="stylesheet" href="<?= $css_directory ?>/fancybox.min.css" />
<script src="<?= $js_directory ?>/fancybox.min.js"></script>
<script src="<?= $js_directory ?>/fontawesome.min.js"></script>
<style>
	html, body { position: relative; padding: 0px; margin: 0px; width: 100%; height: 100%; font-family: 'SCDream2'; src: url("../fonts/SCDream2.otf"); }
    input { -webkit-appearance: none; border-radius: 0; }
	input[type='radio'] { display: none; width: 0px; height: 0px; font-size: 1px; margin: 0px; padding: 0px; }
	ul { list-style: none; padding: 0px; margin: 0px; }

	.top_menu { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; background-color: rgba(255,255,255,0.8); border: 1px solid #ccc; z-index: 1; }
    .top_title { width: 100%; height: 50px; line-height: 50px; text-align: center; }
    .top_title p { margin: 0px; padding: 0px; }
    .header-back-btn { top: 13px; position: absolute; left: 10px; }

	#admin_review_best { margin-top: 61px; font-size: 14px; }
	#admin_review_best .tab_wrap { border-bottom: 2px solid #999; }
	#admin_review_best .tab_wrap ul { display: table; width: 100%; }
	#admin_review_best .tab_wrap ul li { display: table-cell; width: 33.3%; text-align: center; height: 50px; line-height: 50px; }
	#admin_review_best .tab_wrap ul li input[name='tab']+label { display: inline-block; width: 100%; }
	#admin_review_best .tab_wrap ul li input[name='tab']:checked+label { display: inline-block; width: 100%; background-color: #999; color: #fff; }
	#admin_review_best .review_wrap { display: none; }
	#admin_review_best .review_wrap.on { display: block; }
	#admin_review_best .review_wrap ul li { position: relative; border-bottom: 1px solid #ccc; }
	#admin_review_best .review_wrap ul li .review_info { width: calc(100% - 60px); height: 80px; padding: 0px 5px; }
	#admin_review_best .review_wrap ul li .review_info .point { white-space: nowrap; }
	#admin_review_best .review_wrap ul li .review_info span img { width: 14px; vertical-align: -10%; }
	#admin_review_best .review_wrap ul li .review_info .toggle_btn { position: absolute; right: 0px; top: 0px; width: 40px; height: 30px; text-align: center; font-size: 20px; padding-top: 10px; color: #999; } 
	#admin_review_best .review_wrap ul li .toggle_wrap { display: none; }
	#admin_review_best .review_wrap ul li .toggle_wrap.on { display: block; }
	#admin_review_best .review_wrap .no_data { text-align: center; padding: 50px 0px; font-size: 16px; color: #999; background-color: #eee; }
	#admin_review_best .set_insert_review_best { padding: 0px 10px; border: 1px solid #f5bf2e; background-color: #f5bf2e; color: #fff; height: 40px; line-height: 40px; font-size: 16px; border-radius: 10px; }
	#admin_review_best .set_update_review_best { padding: 0px 10px; border: 1px solid #ccc; background-color: #ccc; color: #000; height: 40px; line-height: 40px; font-size: 16px; border-radius: 10px; }
	#admin_review_best .more_btn { width: 100%; border: 0px; border-bottom: 1px solid #ccc; background-color: #ccc; color: #000; height: 50px; line-height: 50px; font-size: 16px; }
	#admin_review_best .top_btn { display: none; position: fixed; right: 10px; bottom: 60px; border: 1px solid #ddd; border-radius: 20px; background-color: #eee; width: 30px; height: 30px; line-height: 30px; text-align: center; color: #999; }
	#admin_review_best .top_btn.on { display: inline-block; }
	#admin_review_best .top_btn svg { margin-top: 7px; }
</style>

<div class="top_menu">
	<div class="header-back-btn"><a href="/pet/admin/"><img src="/pet/images/btn_back_2.png" width="26px"></a></div>
	<div class="top_title">
		<p>베스트 후기 관리</p>
	</div>
</div>
<div id="admin_review_best">
	<div class="tab_wrap">
		<ul>
			<li>
				<input type="radio" id="tab1" name="tab" value="1" checked />
				<label for="tab1">선정후기</label>
			</li>
			<li>
				<input type="radio" id="tab2" name="tab" value="2" />
				<label for="tab2">이용후기</label>
			</li>
			<li>
				<input type="radio" id="tab3" name="tab" value="3" />
				<label for="tab3">선정이력</label>
			</li>
		</ul>
	</div>
	<div class="review_wrap review_wrap_1 on">
	</div>
	<div class="review_wrap review_wrap_2">
	</div>
	<div class="review_wrap review_wrap_3">
	</div>
	<div class="top_btn">
		<i class="fas fa-chevron-up"></i>
	</div>
</div>
<script>
var tab = $("#admin_review_best input[name='tab']:checked").val(); // 상단 탭
var review_best_list = []; // 선택리스트
var review_list_flag = 0;		 // 현재 표시된 갯수 묶음
var review_list_page_cnt = 10; // 화면에 표시될 아이템 갯수
var best_list_flag = 0;		 // 현재 표시된 갯수 묶음
var best_list_page_cnt = 10; // 화면에 표시될 아이템 갯수

$(function(){
	get_review_best_list("1", "");
});

// scrolltop toggle
$(document).on("scroll", function(){
	if($(window).scrollTop() > 0){
		$("#admin_review_best .top_btn").addClass("on");
	}else{
		$("#admin_review_best .top_btn").removeClass("on");
	}
});

// scroll to top
$(document).on("click", "#admin_review_best .top_btn", function(){
	$('html, body').animate({scrollTop : 0}, 400);
});

$(document).on("click", "#admin_review_best input[name='tab']", function(){
	tab = $(this).val();
	$("#admin_review_best .review_wrap").removeClass("on");
	$("#admin_review_best .review_wrap_"+tab).addClass("on");
});

$(document).on("click", "#admin_review_best .more_btn", function(){
	if(tab == "2"){
		review_list_flag += review_list_page_cnt;
		get_review_list();
	}else if(tab == "3"){
		best_list_flag += best_list_page_cnt;
		get_review_best_list("", "");
	}
});

$(document).on("click", "#admin_review_best .set_insert_review_best", function(){
	var review_seq = $(this).parent().parent().data("seq"); // li
	$(this).addClass("set_update_review_best");
	$(this).removeClass("set_insert_review_best");
	console.log(review_seq);
	get_review_best_list("", review_seq);
});

$(document).on("click", "#admin_review_best .set_update_review_best", function(){
	var review_seq = $(this).parent().parent().data("seq"); // li
	var rb_seq = $(this).parent().parent().data("rb"); // li
	$(this).addClass("set_insert_review_best");
	$(this).removeClass("set_update_review_best");
	set_update_review_best(rb_seq, review_seq, "0");
});

function change_tab(tab){
	if(tab == "1"){
		get_review_best_list("1", "");
	}else if(tab == "2"){
		get_review_list();
	}else{
		get_review_best_list("", "");
	}
}

function get_review_list(){
	$.ajax({
		url: 'review_best_ajax.php',
		data: {
			mode : "get_review_list",
			flag : review_list_flag,
			page_cnt : review_list_page_cnt
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				write_review(data.data, "2");
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

function get_review_best_list(best_list, review_seq){
	$.ajax({
		url: 'review_best_ajax.php',
		data: {
			mode : "get_review_best_list",
			flag : best_list_flag,
			page_cnt : best_list_page_cnt,
			best_list : best_list,
			review_seq : review_seq
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				if(best_list != ""){
					review_best_list = [];
					$.each(data.data, function(i, v){
						review_best_list.push(v.review_seq);
					});
					write_review(data.data, "1");
					get_review_list();
					get_review_best_list("", "");
				}else{
					if(review_seq != ""){
						// to do somethings..
						if(data.data.length > 0){
							var rb_seq = data.data[0].rb_seq;
							set_update_review_best(rb_seq, review_seq, "1"); // 수정
						}else{
							set_insert_review_best(review_seq); // 입력
						}
					}else{
						write_review(data.data, "3");
					}
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
}

function write_review(data, tab){
	var html = '';
	var idx = 0;

	$.each(data, function(i, v){
		var img_list = v.review_images.split("|");
		if(img_list.length == 1 && img_list[0] == ""){
			//img_list = ["/pet/images/dog_pet.png"];
			img_list = "";
		}
		var rating_int = parseInt(v.rating);

		html += '<ul>';
		html += '	<li data-seq="'+v.review_seq+'" data-rb="'+v.rb_seq+'">';
		html += '		<div class="review_info">';
		html += '			회　원: '+v.customer_id+'<span class="point"></span><br/>작성일: '+v.reg_time.split(' ')[0]+'<br/>펫　샵: '+v.artist_name+'<br/>평　점: '+v.rating;
		html += '			<span>';
		for(var _i = 0; _i < rating_int; _i++){ 
			html += '				<img src="../images/img_star_1.png" />'; 
		}
		if(v.rating % 1 != 0){ 
			html += '				<img src="../images/img_star_2.png" />'; 
		}
		html += '			</span>';
		html += '			<div class="toggle_btn on">';
		html += '				<i class="fas fa-caret-square-up"></i>';
		html += '			</div>';
		html += '		</div>';
		html += '		<div class="toggle_wrap on">';
		html += '			<div class="thumbnail_list" style="width: calc(100% - 10px); padding: 5px;">';
		$.each(img_list, function(i2, v2){
			html += '			<a data-fancybox="gallery_'+v.review_seq+'" href="'+v2+'"><div class="thumbnail" style="background-image: url(\''+v2+'\'); background-size: 100%; width: 50px; height: 50px; display: inline-block; "></div></a>';					
		});
		html += '			</div>';
		html += '			<div class="review_txt" style="width: calc(100% - 22px); min-height: 100px; padding: 5px; margin: 5px; border: 1px solid #ccc; font-size: 12px;">'+v.review+'</div>';
		html += '		</div>';
		html += '		<div class="button_wrap" style="width: calc(100% - 10px); padding: 5px; text-align: right;">';
		if($.inArray(v.review_seq, review_best_list) != -1){
			html += '			<button type="button" class="set_update_review_best">선정취소</button>';
		}else{
			html += '			<button type="button" class="set_insert_review_best">선정하기</button>';
		}
		html += '		</div>';
		html += '	</li>';
		html += '</ul>';
		idx++;
	});

	if(idx == 0){
		html += '<ul>';
		html += '	<li class="no_data">등록된 리뷰가 없습니다.</li>';
		html += '</ul>';
	}else if(idx == review_list_page_cnt){
		html += '<div class="more">';
		html += '	<button class="more_btn">더보기</button>';
		html += '</div>';
	}

	$("#admin_review_best .review_wrap_"+tab+" .more").remove();
	$("#admin_review_best .review_wrap_"+tab).append(html);

	$.each(data, function(i, v){
		get_user_point(tab, v.customer_id, v.review_seq);
	});
}

function get_user_point(tab, customer_id, review_seq){
	$.ajax({
		url: 'review_best_ajax.php',
		data: {
			mode : "get_user_point",
			customer_id : customer_id
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				var point = parseInt(data.data);

				if(point >= 0){
					$("#admin_review_best .review_wrap_"+tab+" ul li[data-seq='"+review_seq+"'] .point").text(' ( 포인트: '+point.format()+'점 ) ');
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
}

$(document).on("click", "#admin_review_best .toggle_btn", function(){
	if($(this).hasClass("on")){
		$(this).removeClass("on");
		$(this).html('<i class="fas fa-caret-square-down"></i>');
		$(this).parent().parent().find(".toggle_wrap").removeClass("on");
	}else{
		$(this).addClass("on");
		$(this).html('<i class="fas fa-caret-square-up"></i>');
		$(this).parent().parent().find(".toggle_wrap").addClass("on");
	}
});

function set_insert_review_best(review_seq){
	$.ajax({
		url: 'review_best_ajax.php',
		data: {
			mode : "set_insert_review_best",
			review_seq : review_seq,
			order_num : "1"
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				set_insert_review_best_log(data.data, "1");
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

function set_update_review_best(rb_seq, review_seq, order_num){
	$.ajax({
		url: 'review_best_ajax.php',
		data: {
			mode : "set_update_review_best",
			rb_seq : rb_seq,
			review_seq : review_seq,
			order_num : order_num
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				if(order_num == "0"){
					set_insert_review_best_log(rb_seq, "2");
				}else{
					set_insert_review_best_log(rb_seq, "1");
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
}

function set_insert_review_best_log(rb_seq, status){
	$.ajax({
		url: 'review_best_ajax.php',
		data: {
			mode : "set_insert_review_best_log",
			rb_seq : rb_seq,
			status : status
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				location.reload();
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

<?php include "../include/bottom.php"; ?>
