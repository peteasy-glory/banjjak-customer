<?php
include "../include/top.php";
include "../include/Crypto.class.php";
include "../Mobile_Detect.php";
include "../include/App.class.php";

// app init
$is_android = 0;
$app = new App();
if ($app->is_app() == 1) {
    $is_android = 1;
}

// init
$r_no = ($_GET["no"] && $_GET["no"] != "")? $_GET["no"] : "";
$r_iplp_seq = ($_GET["iplp_seq"] && $_GET["iplp_seq"] != "")? $_GET["iplp_seq"] : "";
$r_seq = ($_GET["seq"] && $_GET["seq"] != "")? $_GET["seq"] : "";
$backurl = $_GET['backurl'];
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

$pay_status_arr = array(
	"1" => "진행중",
	"2" => "입금대기",
	"3" => "상품준비중",
	"4" => "배송중",
	"5" => "배송완료",
	"7" => "취소",
	"8" => "보류",
	"9" => "실패"
);

if($user_id == "" || ($r_no == "" && $r_seq == "")){
?>
    <script>
        $.MessageBox({
            buttonDone: "확인",
            message: "잘못된 접근입니다. 메인페이지로 이동합니다."
        }).done(function() {
            location.href = "<?= $mainpage_directory ?>/";
        });
    </script>	
<?php
	return false;
}

?>

<link type="text/css" rel="stylesheet" href="<?= $css_directory ?>/bootstrap.min.css">
<link type="text/css" rel="stylesheet" href="<?= $css_directory ?>/star-rating.css" media="all">
<link type="text/css" rel="stylesheet" href="<?= $css_directory ?>/jquery.fancybox.css?<?= filemtime($upload_static_directory . $css_directory . '/jquery.fancybox.css') ?>">
<link type="text/css" rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<script type="text/javascript" src="<?= $js_directory ?>/jquery.fancybox.js"></script>
<script type="text/javascript" src="<?= $js_directory ?>/fontawesome.min.js"></script>
<script type="text/javascript" src="<?= $js_directory ?>/star-rating.js?<?= filemtime($upload_static_directory . $js_directory . '/star-rating.js') ?>"></script>
<script type="text/javascript" src="<?= $js_directory ?>/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="<?= $js_directory ?>/jquery.fileupload.js"></script>
<style>
	#item_review_write { margin-top: 65px; font-family: 'NL2GR'; padding: 10px;  }
	#item_review_write button { background-color: transparent; border: 1px solid #ccc; color: #ccc; border-radius: 5px; padding: 0px 10px; height: 40px; font-size: 18px; }
	#item_review_write button img { height: 100%; overflow: hidden; }
	#item_review_write button#gallery { border: 0px; }
	#item_review_write button#camera { border: 0px; }
	#item_review_write ul li { position: relative; padding-bottom: 20px; }
	#item_review_write .title { font-size: 12px; margin: 5px 0px; }
	#item_review_write .item_order_list { position: relative; border: 1px solid #ccc; border-radius: 10px; padding: 10px; }
	#item_review_write .item_order_list .item_title { font-family: 'NL2GB'; }
	#item_review_write .item_order_list .item_img { position: absolute; left: 10px; top: 10px; width: 100px; height: 100px; background-size: cover; background-repeat: no-repeat; background-position: center; }
	#item_review_write .item_order_list .item_txt { position: relative; margin-left: 120px; width: calc(100% - 120px); height: 100px; padding-top: 10px; } 
	#item_review_write .item_order_list .item_txt .shipping_dt { position: absolute; left: 0px; bottom: 0px; font-size: 10px; color: #999; }
	#item_review_write .review { border: 1px solid #ccc; width: 100%; min-height: 100px; height: 100px; padding: 10px; font-size: 16px; }
	#item_review_write .upload_img_btn { width: 100%; color: #333; border: 1px solid #333; }
	#item_review_write .img_list { border: 1px solid #ccc; min-height: 80px; margin-top: 5px; margin-bottom: 10px; text-align: left; padding: 10px; }
	#item_review_write .img_list .upload_file_wrap { position: relative; display: inline-block; width: 80px; height: 80px; border: 1px solid #eee; }
	#item_review_write .img_list .upload_file_wrap img { width: 100%; }
	#item_review_write .img_list .upload_file_wrap .set_delete_file_btn { position: absolute; right: 0px; top: 0px; width: 20px; height: 20px; line-height: 20px; text-align: center; background-color: rgba(255,255,255,0.8); border-radius: 10px; padding-top: 2px; }
	#item_review_write .star-rating input[name='rating'] { display: none; }

	#item_review_write .btn_wrap { text-align: center; }
	#item_review_write .btn_wrap .set_insert_item_review { width: 100%; background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
	#item_review_write .btn_wrap .set_update_item_review { width: 100%; background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
</style>

<div class="top_menu">
	<?php if($backurl){ ?>
	<div class="top_back"><a href="<?= $backurl ?>"><img src="/pet/images/btn_back_2.png" width="26px"></a></div>
	<?php }else{ ?>
	<div class="top_back"><a href="<?= $mainpage_directory ?>/mainpage_my_menu.php"><img src="/pet/images/btn_back_2.png" width="26px"></a></div>
	<?php } ?>
	<div class="top_title">
		<p>상품 리뷰하기</p>
	</div>
</div>
<div id="item_review_write">
</div>

<?php // 파일업로드용 ?>
<input id="fileupload" type="file" name="files[]" data-url="<?=$mainpage_directory?>/fileupload_ajax.php" multiple style="display: none;" />

<script>
	var $item_review_write = $("#item_review_write");
	var customer_id = "<?=$user_id ?>";
	var order_num = "<?=$r_no ?>";
	var iplp_seq = "<?=$r_iplp_seq ?>";
	var ir_seq = "<?=$r_seq ?>";
	var product_no = "";
	var img_list = []; // image_upload
	var is_android = "<?=$is_android ?>";

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

	// upload_img delete
	$item_review_write.on("click", ".img_list .set_delete_file_btn", function(){
		var f_seq = $(this).data("seq");

		$.MessageBox({
			buttonFail: "아니오",
			buttonDone: "예",
			message: "<center><font style='font-size:15px;font-weight:bold;'>해당 이미지를 삭제 하시겠습니까?</font></center>"
		}).done(function() {
			$.ajax({
				url: '<?=$mainpage_directory ?>/fileupload_ajax.php',
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
						if(ir_seq != ""){
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
					//alert(error + "네트워크에러");
					if(xhr.status != 0){
						alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
					}
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
		post_data += "&customer_id="+customer_id;
		post_data += "&product_no="+product_no;
		post_data += "&order_num="+order_num;
		post_data += "&iplp_seq="+iplp_seq;

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
						location.href = "<?=$mainpage_directory ?>/manage_my_postwrite.php?tab=2"; // 상품탭으로 가기
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
		post_data += "&customer_id="+customer_id;
		post_data += "&ir_seq="+ir_seq;

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
						location.href = "<?=$mainpage_directory ?>/manage_my_postwrite.php?tab=2"; // 상품탭으로 가기
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
			get_item_order_list();
		}
	}

	function get_item_review(){
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "get_item_review",
				ir_seq : ir_seq,
				customer_id: customer_id
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					$.each(data.data, function(i, v){
						$item_review_write.find("textarea[name='review']").val(v.review);
						$item_review_write.find("input[name='review_image']").val(v.review_image);
						$item_review_write.find("#rating").rating('update', v.rating);
						order_num = v.order_num;
						iplp_seq = v.iplp_seq;
						var review_image = v.review_image.split(',');
						$.each(review_image, function(i2, v2){
							if(v2 != ""){
								img_list.push(v2);
							}
						});
						get_file_list(img_list);
						$item_review_write.find('textarea[name="review"]').height((12+$item_review_write.find('textarea[name="review"]').prop("scrollHeight")));
					});
					get_item_order_list();
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

	function get_item_order_list(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_item_payment_log",
					customer_id : customer_id,
					order_num: order_num
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);

						var html = '';
						if(data.data && data.data.length > 0){
							$.each(data.data, function(i, v){
								product_no = v.product_no;
								//var pay_data = JSON.parse(v.pay_data);
								var pay_data_list = (v.pay_data && v.pay_data.indexOf('||') != -1)? v.pay_data.split('||') : [v.pay_data];
								var shipping_dt = (v.shipping_dt && v.shipping_dt != "")? '<div class="shipping_dt">배송일시 : '+v.shipping_dt+'</div>' : '';

								//if(pay_data_list.length > 0){
									html += '<div class="item_list" data-id="'+v.ip_log_seq+'">';
									html += '	<div class="item_title">'+v.product_name+'</div>';
									//html += '	<ul>';
									//$.each(pay_data_list, function(i2, v2){
									//	html += '	<li data-list_no="'+i2+'">';
									//	html += '		<div class="item_img"></div>';
									//	html +=	'		<div class="item_txt">';
									//	var pay_data = $.parseJSON(v2.replace(/\\/g, ''));
									//	$.each(pay_data, function(i3, v3){
									//		html += '			<div>'+v3.txt+' / '+v3.amount+'개</div>';						
									//	});
									//	html += shipping_dt;
									//	html += '		</div>';
									//	html += '	</li>';
									//});
									html += '	</ul>';
									html += '</div>';
								//}
							});
							$item_review_write.find(".item_order_list").html(html);
							//$item_review_write.find("input[name='product_no']").val(product_no);
							//$.each(data.data, function(i, v){
							//	get_item_image(v.goodsRepImage);
							//});
							//$.each(data.data, function(i, v){
							//	var pay_data_list = (v.pay_data && v.pay_data.indexOf('||') != -1)? v.pay_data.split('||') : [v.pay_data];
							//	if(pay_data_list.length > 0){
							//		$.each(pay_data_list, function(i2, v2){
							//			var pay_data = $.parseJSON(v2.replace(/\\/g, ''));
							//			get_item(v.ip_log_seq, i2, pay_data);
							//		});
							//	}
							//});

							var p = $.when();
							var c = 0;
							var reg_order_num = '';

							$.each(data.data, function(i, v){
								reg_order_num = (i == 0)? v.order_num : reg_order_num;

								p = p.then(function(){
									c++;
									return get_item_payment_log_product(v.order_num, v.ip_log_seq);
								}).done(function(){
									if(c == data.data.length){	
										resolve(reg_order_num);
									}
								});
							});
						}else{
							// 리뷰 구매 상품이 없음 - 지워졌거나 사용자가 다름(일반적인 에러가 아님)
							$.MessageBox({
								buttonDone: "확인",
								message: "<center>상품의 결제정보가 확인되지 않아<br/>리뷰를 작성하실 수 없습니다.</center>"
							}).done(function(){
								window.history.back();
							});
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

	function get_item_payment_log_product(order_num, ip_log_seq){
		return new Promise(function(resolve, reject) {
			console.log(order_num);
			if(order_num != "" && typeof order_num != "undefined"){
				$.ajax({
					url: '<?=$item_directory ?>/item_list_ajax.php',
					data: {
						mode : "get_item_payment_log_product",
						order_num: order_num
					},
					type: 'POST',
					dataType: 'JSON',
					success: function(data) {
						if(data.code == "000000"){
							console.log(data.data);
							var html = '';

							if(data.data && data.data.length > 0){
								$.each(data.data, function(i, v){
									if(v.iplp_seq == iplp_seq){
										var _pay_data_list = (v.option_data && v.option_data != '')? ((v.option_data.indexOf('||') != -1)? v.option_data.split('||') : [v.option_data]) : [];
										if(_pay_data_list.length > 0){
											$.each(_pay_data_list, function(i2, v2){
												var _pay_data = $.parseJSON(v2.replace(/\\/g, ''));
												html += '<div class="payment_product" data-iplp_seq="'+v.iplp_seq+'" style="position: relative; min-height: 50px;">';
												html += '	<div class="item_image" style="position: absolute; left: 0px; top: 0px; width: 40px; height: 40px; background-image: url(\'/pet/images/product_img.png\'); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>';
												if(_pay_data.length > 0){
													$.each(_pay_data, function(i3, v3){
														html += '<div style="margin: 5px 0px 5px 50px;">';
														html +=		'<span>'+v3.txt+', '+v3.amount+'개</span>';
														html += '</div>';
													});
												}
												html += '</div>';
											});
										}
									}
								});
								$item_review_write.find(".item_order_list .item_list[data-id='"+ip_log_seq+"']").append(html);
								$.each(data.data, function(i, v){
									//get_member_item_review_list(v.order_num, v.iplp_seq, v.pay_status, ip_log_seq);
									var _pay_data_list = (v.option_data && v.option_data != '')? ((v.option_data.indexOf('||') != -1)? v.option_data.split('||') : [v.option_data]) : [];
									if(_pay_data_list.length > 0){
										$.each(_pay_data_list, function(i2, v2){
											var _pay_data = $.parseJSON(v2.replace(/\\/g, ''));
												get_item(ip_log_seq, _pay_data);
										});
									}
								});
							}else{
								html += '<div>';
								html += '	<div class="no_data">상품이 없습니다</div>';
								html += '</div>';
								$item_review_write.find(".item_order_list .item_list[data-id='"+ip_log_seq+"']").append(html);
							}

							resolve();
						}else{
							alert(data.data+"("+data.code+")");
							console.log(data.data);
						}
					},
					error: function(xhr, status, error) {
						alert(error + "네트워크에러");
					}
				});
			}else{
				console.log("거래번호 없음");
			}
		});
	}

	function get_item(ip_log_seq, list_no, pay_data){
		return new Promise(function(resolve, reject) {
			$.each(pay_data, function(i, v){ // thumbnail - product_no
				var post_data = {};
				if(v.seq && v.seq != ""){
					post_data.mode = "get_item_option";
					post_data.io_seq = v.seq;
				}else{
					post_data.mode = "get_item";
					post_data.il_seq = v.il_seq;
				}

				$.ajax({
					url: '<?=$item_directory ?>/item_list_ajax.php',
					data: post_data,
					type: 'POST',
					dataType: 'JSON',
					success: function(data) {
						if(data.code == "000000"){
							console.log(data.data);
							var html = '';
							if(data.data && data.data.length > 0){
								$.each(data.data, function(i, v){
									$item_review_write.find('.item_list[data-id="'+ip_log_seq+'"] ul li[data-list_no="'+list_no+'"] .item_img').attr('data-id', v.product_no);
									get_item_image('.item_list[data-id="'+ip_log_seq+'"] ul li[data-list_no="'+list_no+'"] .item_img', v.product_no);
								});

								resolve();
							}else{
								// 삭제된 상품이 있음
							}
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
		});
	}

	function get_item_image(target, product_no){
		return new Promise(function(resolve, reject) {
			if(product_no && product_no != ""){ 
				$.ajax({
					url: '<?=$item_directory ?>/item_list_ajax.php',
					data: {
						mode : "get_item_list",
						product_no : product_no
					},
					type: 'POST',
					dataType: 'JSON',
					success: function(data) {
						if(data.code == "000000"){
							console.log(data.data);
							var html = '';
							$.each(data.data.list, function(i, v){
								get_product_file_list(target, v.product_no, v.product_img, v.goodsRepImage);
							});
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
			}
		});
	}

	function get_product_file_list(target, product_no, img_list, goodsRepImage){
		return new Promise(function(resolve, reject) {
			//console.log(img_list);
			// img_loading
			if(img_list && img_list != ""){
				$.ajax({
					url: '<?=$mainpage_directory ?>/fileupload_ajax.php',
					data: {
						mode : "get_file_list",
						file_list: img_list
					},
					type: 'POST',
					dataType: 'JSON',
					success: function(data) {
						if(data.code == "000000"){
							//console.log(data.data);
							var html = '';
							if(data.data && data.data.length > 0){
								$.each(data.data, function(i, v){
									if(i == 0){
										$item_review_write.find(target+'[data-id="'+product_no+'"]').css('background-image', 'url("'+v.file_path+'")');
									}
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
			}else{
				if(goodsRepImage != ""){
					$item_review_write.find(target+'[data-id="'+product_no+'"]').css('background-image', 'url("'+goodsRepImage+'")');
				}else{
					$item_review_write.find(target+'[data-id="'+product_no+'"]').css('background-image', 'url("../images/product_img.png")');
				}
			}
		});
	}

/*
	function get_item_image(goodsRepImage){
		var item_img = $item_review_write.find(".item_img").data("num");
		if(item_img && item_img != ""){
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
						html += '<img src="'+data.data[0].file_path+'" style="width: 100%;">';

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
			if(goodsRepImage && goodsRepImage != ""){
				$item_review_write.find(".item_img").html('<img src="'+goodsRepImage+'" style="width: 100%;">');
			}
		}
	}
*/

	function get_file_list(img_list){
		console.log(img_list);
		if(img_list && img_list != ""){
			img_list = img_list.filter(function(item) {
			return item !== null && item !== undefined && item !== '';
			});
			var tmp_img_list = img_list.join(',');
			if(tmp_img_list != ""){
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
			}else{ // empty
				$item_review_write.find('.img_list').html('');
			}
		}else{ // empty
			$item_review_write.find('.img_list').html('');
		}
	}
</script>
<?php include "../include/bottom.php"; ?>