<?php
include "../include/top.php";
include "../include/App.class.php";

// app init
$is_android = 0;
$app = new App();
if ($app->is_app() == 1) {
    $is_android = 1;
}

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

$r_product_no = ($_GET["product_no"] && $_GET["product_no"] != "")? $_GET["product_no"] : "";
$r_ir_seq = ($_GET["ir_seq"] && $_GET["ir_seq"] != "")? $_GET["ir_seq"] : "";
$backurl = $_GET["backurl"];
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
?>
<link type="text/css" rel="stylesheet" href="<?= $css_directory ?>/bootstrap.min.css">
<link type="text/css" rel="stylesheet" href="<?= $css_directory ?>/star-rating.css" media="all">
<link type="text/css" rel="stylesheet" href="<?= $css_directory ?>/jquery.fancybox.css?<?= filemtime($upload_static_directory . $css_directory . '/jquery.fancybox.css') ?>">

<script type="text/javascript" src="<?= $js_directory ?>/jquery.fancybox.js"></script>
<script type="text/javascript" src="<?= $js_directory ?>/fontawesome.min.js"></script>
<script type="text/javascript" src="<?= $js_directory ?>/star-rating.js?<?= filemtime($upload_static_directory . $js_directory . '/star-rating.js') ?>"></script>
<script type="text/javascript" src="<?= $js_directory ?>/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="<?= $js_directory ?>/jquery.fileupload.js"></script>
<style>
	@font-face {font-family: 'NL2GB';src: url("../fonts/NEXON_Lv2_Gothic_Bold.woff");}
	@font-face {font-family: 'NL2GR';src: url('../fonts/NEXON_Lv2_Gothic.woff') format('woff');}
	ul { list-style: none; margin: 0px; padding: 0px; }

	.bjj_top_menu { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; border-bottom: 1px solid #ccc; background-color: rgba(255, 255, 255, 0.8); margin-top: env(safe-area-inset-top); margin-top: constant(safe-area-inset-top); z-index: 10; }
	.bjj_top_menu .bjj-back-btn { position: absolute; left: 8px; top: 12px; }
	.bjj_top_menu .bjj-back-btn img { width: 26px; vertical-align: middle; }
	.bjj_top_menu .bjj_top_title { width: 100%; height: 50px; line-height: 50px; text-align: center; padding: 0px; }
	.bjj_top_menu .bjj_top_title p { padding: 0px; margin: 0px; }
	.bjj_top_menu .bjj_review { position: absolute; right: 5px; top: 8px; }
	.bjj_top_menu .bjj_review button { height: 35px; padding: 0px 10px; font-size: 14px; line-height: 18px; border-radius: 5px; border: 1px solid #ccc; background-color: #eee; }
	.bjj_top_menu .bjj_review span { font-size: 18px; }

	#item_review_write { position: relative; width: 100%; margin-top: 61px; margin-top: calc(env(safe-area-inset-top) + 61px); margin-top: calc(constant(safe-area-inset-top) + 61px); font-family: 'NL2GR'; }
	#item_review_write input[type='text'] { height: 30px; padding: 0px 10px; border: 0px; border-bottom: 1px solid #ccc; width: 100%; }
	#item_review_write button { background-color: transparent; border: 1px solid #ccc; color: #ccc; border-radius: 5px; padding: 0px 10px; height: 40px; font-size: 18px; }
	#item_review_write button img { height: 100%; overflow: hidden; }
	#item_review_write ul { margin: 10px; }
	#item_review_write ul li { position: relative; padding-bottom: 20px; }
	#item_review_write input[name='name'] { width: calc(100% - 60px); }
	#item_review_write button.reroll_btn { position: absolute; right: 0px; top: 13px; color: #333; }
	#item_review_write .title { font-size: 12px; margin: 5px 0px; }
	#item_review_write .item_order_list { position: relative; border: 1px solid #ccc; border-radius: 10px; padding: 10px; }
	#item_review_write .item_order_list .item_img { position: absolute; left: 10px; top: 10px; width: 100px; min-height: 100px; background-color: #eee; }
	#item_review_write .item_order_list .item_txt { position: relative; margin-left: 110px; width: calc(100% - 110px); height: 100px; } 
	#item_review_write .item_order_list .item_txt .item_title { font-family: 'NL2GB'; }
	#item_review_write .item_order_list .item_txt .shipping_dt { position: absolute; left: 0px; bottom: 0px; font-size: 10px; color: #999; }
	#item_review_write .item_order_list select { padding: 0px 10px; height: 30px; border: 1px solid #ccc; }
	#item_review_write .review { border: 1px solid #ccc; width: 100%; min-height: 100px; height: 100px; padding: 10px; font-size: 16px; }
	#item_review_write .upload_img_btn { width: 100%; color: #333; border: 1px solid #333; }
	#item_review_write .img_list { border: 1px solid #ccc; min-height: 80px; margin-top: 5px; margin-bottom: 10px; text-align: left; padding: 10px; }
	#item_review_write .img_list .upload_file_wrap { position: relative; display: inline-block; width: 80px; height: 80px; border: 1px solid #eee; }
	#item_review_write .img_list .upload_file_wrap img { width: 100%; }
	#item_review_write .img_list .upload_file_wrap .set_delete_file_btn { position: absolute; right: 0px; top: 0px; width: 20px; height: 20px; line-height: 20px; text-align: center; background-color: rgba(255,255,255,0.8); border-radius: 10px; padding-top: 2px; }
	#item_review_write .no_review { text-align: center; padding: 40px 0px; background-color: #eee; color: #999; }
	#item_review_write .star-rating input[name='rating'] { display: none; }

	#item_review_write .btn_wrap { text-align: center; }
	#item_review_write .btn_wrap .set_insert_item_review { width: 100%; background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
	#item_review_write .btn_wrap .set_update_item_review { width: 100%; background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
</style>

<div class="bjj_top_menu">
	<?php if($backurl){ ?>
    <div class="bjj-back-btn"><a href="<?=$backurl ?>"><img src="<?= $image_directory ?>/btn_back_2.png"></a></div>
	<?php }else{ ?>
    <div class="bjj-back-btn"><a href="<?=$admin_directory ?>/"><img src="<?= $image_directory ?>/btn_back_2.png"></a></div>
	<?php } ?>
    <div class="bjj_top_title"><p>상품 리스트</p></div>
</div>

<div id="item_review_write">
</div>

<?php // 파일업로드용 ?>
<input id="fileupload" type="file" name="files[]" data-url="<?=$mainpage_directory?>/fileupload_ajax.php" multiple style="display: none;" />

<script>
	var $item_review_write = $("#item_review_write");
	var product_no = "<?=$r_product_no ?>";
	var ir_seq = "<?=$r_ir_seq ?>";
	var img_list = []; // image_upload
	var is_android = "<?=$is_android ?>";

	var random_id = Math.floor(Math.random() * 4) + 7;

	$(function(){
		get_item_review_html();
		
		$('#fileupload').fileupload({
			formData: {
				mode: "upload_img",
				target: "tb_item_review.image",
				folder: "review"
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
						//html += '<div class="upload_file_wrap">';
						//html += '	<img src="'+file.file_path+'" alt="'+file.file_name+'" title="'+file.file_name+'" />';
						//html += '	<div class="set_delete_file_btn" data-seq="'+file.f_seq+'"><i class="fas fa-times"></i></div>';
						//html += '</div>';
						//$item_review_write.find('.img_list').append(html);
						$item_review_write.find('input[name="review_image"]').val(img_list.join(","));
						get_file_list(img_list);
					});

					// item review_image update
					if(ir_seq != ""){ // 수정일때만 사용
						$.ajax({
							url: '<?=$item_directory ?>/item_list_ajax.php',
							data: {
								mode: "set_update_item_review",
								ir_seq: ir_seq,
								review_image: img_list.join(",")
							},
							type: 'POST',
							dataType: 'JSON',
							success: function(data) {
								if(data.code == "000000"){
									console.log("review_image update OK");
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

	function galleryup(id) {
		rid = id;
		window.Android.openGallery();
	}

	function cameraup(id) {
		rid = id;
		window.Android.openCamera();
	}

	function uploadEnd(fileName) {
		$.ajax({
			url: '<?=$mainpage_directory?>/fileupload_ajax.php',
			data: {
				mode: "upload_img_app",
				file: fileName,
				target: "tb_item_review.image",
				folder: "review"
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					//console.log(img_list);
					img_list.push(data.data[0].f_seq);

					$.each(img_list, function(i, v){
						if(v == ""){
							img_list.splice(i, 1);
						}
					});
					//$item_review_write.find('.img_list').append(html);
					$item_review_write.find('input[name="review_image"]').val(img_list.join(","));
					get_file_list(img_list);

					// item product_img update
					if(ir_seq != ""){ // 수정일때만 사용
						$.ajax({
							url: '<?=$item_directory ?>/item_list_ajax.php',
							data: {
								mode: "set_update_item_review",
								ir_seq: ir_seq,
								review_image: img_list.join(",")
							},
							type: 'POST',
							dataType: 'JSON',
							success: function(data) {
								if(data.code == "000000"){
									console.log("review_image update OK");
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
					$.MessageBox(data.data+"("+data.code+")");
					alert(data.data);
				}
			},
			error: function(xhr, status, error) {}
		});
	}	

	// textarea auto height
	$item_review_write.on('keyup', 'textarea[name="review"]', function() {
		$(this).height(1);
		$(this).height((12+$(this).prop("scrollHeight")));
		$(window).scrollTop($("body").height());
	});

	// star-rating change
	$item_review_write.on('change', '#rating', function() {
		$item_review_write.find('#rating').val($(this).val());
	});

	// nickname reroll
	$item_review_write.on("click", ".reroll_btn", function(){
		get_nickname(random_id); // rand nickname
	});

	// upload_img delete
	$item_review_write.on("click", ".img_list .set_delete_file_btn", function(){
		var f_seq = $(this).data("seq");

		$.MessageBox({
			buttonFail: "아니오",
			buttonDone: "예",
			message: "<center><font style='font-size:15px;font-weight:bold;'>해당 이미지를 삭제 하시겠습니까?</font></center>"
		}).done(function() {
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

						// item review_image update
						if(ir_seq != ""){ // 수정일때만 사용
							$.ajax({
								url: '<?=$item_directory ?>/item_list_ajax.php',
								data: {
									mode: "set_update_item_review",
									ir_seq: ir_seq,
									review_image: img_list.join(",")
								},
								type: 'POST',
								dataType: 'JSON',
								success: function(data) {
									if(data.code == "000000"){
										console.log("review_image update OK");
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
						alert(data.data+"("+data.code+")");
						console.log(data.data);
					}
				},
				error: function(xhr, status, error) {
					alert(error + "네트워크에러");
				}
			});	
		});
	});

	// upload_img upload
	$item_review_write.on("click", ".upload_img_btn", function(){
		$("#fileupload").trigger("click");
	});

	// submit
	$item_review_write.on("click", ".set_insert_item_review", function(){
		var post_data = $item_review_write.find("#item_review_write_form").serialize();
		post_data += "&mode=set_insert_item_review";
		post_data += "&product_no="+product_no;
		post_data += "&product_option_txt="+$item_review_write.find("input[name='product_name']").val().replace(/&/gi, '___');
		post_data += ($item_review_write.find("select[name='option']").length > 0 && $item_review_write.find("select[name='option'] option:selected").val() != "")? ", "+$item_review_write.find("select[name='option'] option:selected").val() : "";
		post_data += ", "+$item_review_write.find("select[name='amount'] option:selected").val()+"개";
		post_data += "&is_admin=1";

		console.log(post_data);
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: post_data,
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					$.MessageBox({
						buttonDone: "확인",
						message : "<center>리뷰가 추가되었습니다.</center>"
					}).done(function(){
						location.href = "<?=$admin_directory ?>/item_review_list.php?product_no="+product_no+"&backurl=<?= urlencode($_SERVER['REQUEST_URI']) ?>";					
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
	});

	// update
	$item_review_write.on("click", ".set_update_item_review", function(){
		var post_data = $item_review_write.find("#item_review_write_form").serialize();
		post_data += "&mode=set_update_item_review";
		post_data += "&ir_seq="+ir_seq;
		post_data += "&is_admin=1";
		post_data += "&product_option_txt="+$item_review_write.find("input[name='product_name']").val().replace(/&/gi, '___');
		post_data += ($item_review_write.find("select[name='option']").length > 0 && $item_review_write.find("select[name='option'] option:selected").val() != "")? ", "+$item_review_write.find("select[name='option'] option:selected").val() : "";
		post_data += ", "+$item_review_write.find("select[name='amount'] option:selected").val()+"개";

		console.log(post_data);
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: post_data,
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					$.MessageBox({
						buttonDone: "확인",
						message : "<center>리뷰가 수정되었습니다.</center>"
					}).done(function(){
						location.href = "<?=$admin_directory ?>/item_review_list.php?product_no="+product_no+"&backurl=<?= urlencode($_SERVER['REQUEST_URI']) ?>";					
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
	});

	function get_item_review_html(){
		var html = '';
		html += '<form id="item_review_write_form" method="POST">';
		html += '	<input type="hidden" name="product_no" value="" />';
		html += '	<ul>';
		html += '		<li>';
		html += '			<div class="title">구매상품</div>';
		html += '			<div class="content item_order_list">';
		html += '				<div class="img">제품이미지</div>';
		html += '				<div class=""></div>';
		html += '			</div>';
		html += '		</li>';
		html += '		<li>';
		html += '			<div class="title">닉네임</div>';
		html += '			<div class="content">';
		html += '				<input type="text" name="name" value="" />';
		html += '				<button type="button" class="reroll_btn"><i class="fas fa-dice"></i></button>';
		html += '			</div>';
		html += '		</li>';
		html += '		<li>';
		html += '			<div class="title">사진첨부</div>';
		html += '			<div class="content">';
		html += '				<div class="img_list">업로드된 이미지가 없습니다.</div>';
		html += '				<input type="hidden" name="review_image" value="" />';
		if(is_android == "1"){
			html += '				<button type="button" id="gallery" onclick="javascript:galleryup();"><img src="<?= $image_directory ?>/photo3.png" /></button>';
			html += '				<button type="button" id="camera" onclick="javascript:cameraup();"><img src="<?= $image_directory ?>/photo2.png" /></button>';
		}else{
			html += '				<button type="button" class="upload_img_btn">사진등록</button>';
		}
		html += '			</div>';
		html += '		</li>';
		html += '		<li>';
		html += '			<div class="title">평점</div>';
		html += '			<div class="content star-rating">';
		html += '				<input type="text" id="rating" class="rating" name="rating" value="5" data-size="xs" title="평점" />';
		html += '			</div>';
		html += '		</li>';
		html += '		<li>';
		html += '			<div class="title">구매후기</div>';
		html += '			<div class="content">';
		html += '				<textarea name="review" class="review"></textarea>';
		html += '			</div>';
		html += '		</li>';
		html += '	</ul>';
		html += '</form>';
		html += '<div class="btn_wrap">';
		if(ir_seq != ""){
			html += '	<button type="button" class="set_update_item_review">수정</button>';
		}else{
			html += '	<button type="button" class="set_insert_item_review">입력</button>';
		}
		html += '</div>';

		$item_review_write.html(html);
		$item_review_write.find("#rating").rating({'step': 1});
		if(ir_seq != ""){
			get_item_review();
		}else{
			get_nickname(random_id); // rand nickname
			get_item_list('');
		}
	}

	function get_item_review(){
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "get_item_review",
				ir_seq : ir_seq
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					var product_option_txt = '';
					$.each(data.data, function(i, v){
						var name = (v.customer_id && v.customer_id != "" && v.nickname && v.nickname != "")? v.nickname : v.name;
						$item_review_write.find("textarea[name='review']").val(v.review);
						$item_review_write.find("input[name='review_image']").val(v.review_image);
						$item_review_write.find("#rating").rating('update', v.rating);
						$item_review_write.find("input[name='name']").val(name);
						order_num = v.order_num;
						product_no = v.product_no;
						product_option_txt = v.product_option_txt;
						var review_image = v.review_image.split(',');
						$.each(review_image, function(i2, v2){
							if(v2 != ""){
								img_list.push(v2);
							}
						});
						get_file_list(img_list);
						$item_review_write.find('textarea[name="review"]').height((12+$item_review_write.find('textarea[name="review"]').prop("scrollHeight")));
					});
					get_item_list(product_option_txt);
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

	function get_item_list(product_option_txt){
		if(product_no != ""){
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_item_list",
					product_no: product_no
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);

						var html = '';
						var il_seq = '';
						var is_option = 0;
						$.each(data.data.list, function(i, v){
							html += '<div class="item_img" data-num="'+v.product_img+'" data-goodsRepImage="'+v.goodsRepImage+'" />';
							html += '<div class="item_txt">';
							html += '	<div class="item_title">'+v.product_name+'</div>';
							html += '</div>';
							il_seq = v.il_seq;
							is_option = v.is_use_option;
						});

						$item_review_write.find(".item_order_list").html(html);
						get_item_image();
						get_item_option(il_seq, is_option, product_option_txt);
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
	}

	function get_item_option(il_seq, is_option, product_option_txt){
		if(is_option == "1"){
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_item_option",
					il_seq: il_seq
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);

						var html = '';
						var product_name = $item_review_write.find(".item_title").text();
						var product_option = (product_option_txt != "" && product_option_txt.indexOf(', ') != -1)? product_option_txt.split(', ') : ['', '', ''];
						html += '<div>';
						html += '	<input type="hidden" name="product_name" value="'+product_name+'" />';
						html += '	<select name="option">';
						$.each(data.data, function(i, v){
							var is_selected = (v.option_name == product_option[1])? " selected " : "";
							html += '		<option value="'+v.option_name+'" '+is_selected+'>'+v.option_name+'</option>';
						});
						html += '	</select>';
						html += '	<select name="amount">';
						for(var _i = 1; _i <= 20; _i++){
							var is_selected = (_i == product_option[2].replace('개', ''))? " selected " : "";
							html += '		<option value="'+_i+'" '+is_selected+'>'+_i+'개</option>';
						}
						html += '	</select>';
						html += '</div>';
						$item_review_write.find(".item_order_list .item_txt").append(html);
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
			var html = '';
			var product_name = $item_review_write.find(".item_title").text();
			var product_option = (product_option_txt != "" && product_option_txt.indexOf(', ') != -1)? product_option_txt.split(', ') : ['', ''];
			html += '<div>';
			html += '	<input type="hidden" name="product_name" value="'+product_name+'" />';
			html += '	<select name="amount">';
			for(var _i = 1; _i <= 20; _i++){
				var is_selected = (_i == product_option[1].replace('개', ''))? " selected " : "";
				html += '		<option value="'+_i+'" '+is_selected+'>'+_i+'개</option>';
			}
			html += '	</select>';
			html += '</div>';

			$item_review_write.find(".item_order_list .item_txt").append(html);
		}
	}

	function get_item_image(){
		var item_img = $item_review_write.find(".item_img").data("num");
		if(item_img != ""){
			$.ajax({
				url: '<?=$mainpage_directory?>/fileupload_ajax.php',
				data: {
					mode : "get_file_list",
					file_list: item_img
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						var html = '';
						// 대표이미지 가져오기
						if(data.data.length > 0){
							$.each(data.data, function(i, v){
								if(i == 0){
									html += '<img src="'+v.file_path+'" style="width: 100%;">';						
								}
							});
						}

						$item_review_write.find(".item_img").html("").html(html);
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
			var item_goodsRepImage = $item_review_write.find(".item_img").data("goodsrepimage");
			if(item_goodsRepImage != ""){
				$item_review_write.find(".item_img").html('<img src="'+item_goodsRepImage+'" style="width: 100%;">');
			}
		}
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
						$.each(data.data, function(i, v){
							html += '<div class="upload_file_wrap">';
							html += '	<a data-fancybox="gallery" href="'+v.file_path+'">';
							html += '		<img src="'+v.file_path+'" alt="'+v.file_name+'" title="'+v.file_name+'" />';
							html += '	</a>';
							html += '	<div class="set_delete_file_btn" data-seq="'+v.f_seq+'"><i class="fas fa-times"></i></div>';
							html += '</div>';
							idx++;
						});

						if(idx == 0){
							html += '업로드된 이미지가 없습니다.';
						}
						$item_review_write.find('.img_list').html('').html(html);
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

	function get_nickname(length){
		var txt_rand = makeRandom(1, 5);
		var num_rand = length - txt_rand;
		$item_review_write.find("input[name='name']").val(randomString_txt(txt_rand)+randomString_num(num_rand));

	}

	function makeRandom(min, max){
		var RandVal = Math.floor(Math.random()*(max-min+1)) + min;
		return RandVal;
	}

	function randomString_txt(string_length) {
		var chars = "abcdefghiklmnopqrstuvwxyz";
		var randomstring = '';
		for (var i=0; i<string_length; i++) {
		var rnum = Math.floor(Math.random() * chars.length);
		randomstring += chars.substring(rnum,rnum+1);
		}
		return randomstring;
	}

	function randomString_num(string_length) {
		var chars = "0123456789";
		var randomstring = '';
		for (var i=0; i<string_length; i++) {
		var rnum = Math.floor(Math.random() * chars.length);
		randomstring += chars.substring(rnum,rnum+1);
		}
		return randomstring;
	}

</script>