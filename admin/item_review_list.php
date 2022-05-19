<?php
include "../include/top.php";

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
<link rel="stylesheet" href="<?= $css_directory ?>/fancybox.min.css" />
<script src="<?= $js_directory ?>/fancybox.min.js"></script>
<script src="<?= $js_directory ?>/fontawesome.min.js"></script>
<style>
	@font-face {font-family: 'NL2GB';src: url("../fonts/NEXON_Lv2_Gothic_Bold.woff");}
	@font-face {font-family: 'NL2GR';src: url('../fonts/NEXON_Lv2_Gothic.woff') format('woff');}
	ul { list-style: none; padding: 0px; margin: 0px; }

	.bjj_top_menu { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; border-bottom: 1px solid #ccc; background-color: rgba(255, 255, 255, 0.8); margin-top: env(safe-area-inset-top); margin-top: constant(safe-area-inset-top); z-index: 10; }
	.bjj_top_menu .bjj-back-btn { position: absolute; left: 8px; top: 12px; }
	.bjj_top_menu .bjj-back-btn img { width: 26px; vertical-align: middle; }
	.bjj_top_menu .bjj_top_title { width: 100%; height: 50px; line-height: 50px; text-align: center; padding: 0px; }
	.bjj_top_menu .bjj_top_title p { padding: 0px; margin: 0px; }
	.bjj_top_menu .bjj_review { position: absolute; right: 5px; top: 8px; }
	.bjj_top_menu .bjj_review button { height: 35px; padding: 0px 10px; font-size: 14px; line-height: 18px; border-radius: 5px; border: 1px solid #ccc; background-color: #eee; }
	.bjj_top_menu .bjj_review span { font-size: 18px; }

	#item_review_list { position: relative; width: 100%; margin-top: 61px; margin-top: calc(env(safe-area-inset-top) + 61px); margin-top: calc(constant(safe-area-inset-top) + 61px); font-family: 'NL2GR'; }
	/* item_review */
	#item_review_list .customer_review { position: relative; margin-bottom: 50px; }
	#item_review_list .customer_review .admin_action { position: absolute; right: 10px; top: 0px; display: flex; align-items: center; justify-content: center; }
	#item_review_list .customer_review .admin_action>div { display: inline-block; display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; text-align: center; background-color: #eee; margin-left: 5px; border-radius: 5px; }
	#item_review_list .customer_review .admin_action>div.on { background-color: #f5bf2e; color: #fff; }
	#item_review_list .customer_review .img { position: absolute; left: 10px; top: 0px; width: 50px; height: 50px; border-radius: 25px; background-color: #e9e9e9; overflow: hidden; background-size: cover; background-position: center; background-repeat: no-repeat; }
	#item_review_list .customer_review ul { margin-left: 70px; width: calc(100% - 70px); }
	#item_review_list .customer_review ul li { padding: 5px 0px; }
	#item_review_list .customer_review .product { color: #999; font-size: 14px; padding-bottom: 10px;}
	#item_review_list .customer_review .review { position: relative; background-color: #f6f6f6; border-radius: 10px; min-height: 50px; padding: 10px; line-height: 22px; }
	#item_review_list .customer_review .review.on { background-color: #fcc; }
	#item_review_list .customer_review .review .review_image_wrap { min-height: 70px; margin: 10px 0px; }
	#item_review_list .customer_review .review .review_image_wrap a { padding: 2px; }
	#item_review_list .customer_review .review .review_image_wrap .review_image { display: inline-block; width: 70px; height: 70px; border-radius: 10px; overflow: hidden; background-size: cover; background-position: center; background-repeat: no-repeat; }
	#item_review_list .customer_review .star-rating { font-size: 12px; color: #999; line-height: 40px; }
	#item_review_list .customer_review .star-rating .star { color: #f5bf2e; }
	#item_review_list .customer_review .review_dt { color: #999; font-size: 14px; padding: 5px; }
	#item_review_list .no_review { text-align: center; padding: 40px 0px; background-color: #eee; color: #999; }
	#item_review_list .customer_review .reply_wrap { display: none; }
	#item_review_list .customer_review .reply_wrap.on { display: block; }
	#item_review_list .customer_review .reply { position: relative; background-color: #f6f6f6; border-radius: 10px; min-height: 50px; padding: 10px; line-height: 22px; }
	#item_review_list .customer_review .reply.on { background-color: #fcc; }
	#item_review_list .customer_review .reply .reply_icon { position: absolute; left: -35px; top: 0px; font-size: 30px; color: #999; transform: rotate(150deg); }
	#item_review_list .customer_review .reply textarea { width: calc(100% - 10px); min-height: 100px; background-color: transparent; }
	#item_review_list .customer_review .reply .set_update_reply_btn { width: calc(100% - 4px); height: 30px; border: 1px solid #ccc; background-color: #eee; }
	#item_review_list .customer_review .reply_dt { color: #999; font-size: 14px; padding: 5px; }

</style>

<div class="bjj_top_menu">
	<?php if($backurl){ ?>
    <div class="bjj-back-btn"><a href="<?=$backurl ?>"><img src="<?= $image_directory ?>/btn_back_2.png"></a></div>
	<?php }else{ ?>
    <div class="bjj-back-btn"><a href="<?=$admin_directory ?>/"><img src="<?= $image_directory ?>/btn_back_2.png"></a></div>
	<?php } ?>
    <div class="bjj_top_title"><p>상품 리뷰 리스트</p></div>
	<div class="bjj_review"><button type="button" class="set_review_list_btn">후기추가 <span><i class="fas fa-plus"></i></span></button></div>
</div>

<div id="item_review_list">
</div>

<script>
	var $item_review_list = $("#item_review_list");
	var product_no = "<?=$r_product_no ?>";

	$(function(){
		get_item_review();
	});

	$(document).on("click", ".set_review_list_btn", function(){
		location.href = "item_review_write.php?product_no="+product_no+"&backurl=<?= urlencode($_SERVER['REQUEST_URI']) ?>";
	});

	$item_review_list.on("click", ".reply_review_btn", function(){
		var _this = $(this);
		var ir_seq = _this.data("id");
		var is_reply = ($item_review_list.find(".customer_review[data-id='"+ir_seq+"'] .reply_wrap").hasClass("on"))? "2" : "1";

		if(ir_seq != ""){
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode: "set_update_item_review",
					ir_seq: ir_seq,
					is_reply: is_reply
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						if(_this.hasClass("on")){
							_this.removeClass("on");
							$item_review_list.find(".customer_review[data-id='"+ir_seq+"'] .reply_wrap").removeClass("on");
						}else{
							_this.addClass("on");
							$item_review_list.find(".customer_review[data-id='"+ir_seq+"'] .reply_wrap").addClass("on");
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
		}
	});

	$item_review_list.on("click", ".blind_review_btn", function(){
		var _this = $(this);
		var ir_seq = _this.data("id");
		var is_blind = ($item_review_list.find(".customer_review[data-id='"+ir_seq+"'] .review").hasClass("on"))? "2" : "1";

		if(ir_seq != ""){
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode: "set_update_item_review",
					ir_seq: ir_seq,
					is_blind: is_blind
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						if(_this.hasClass("on")){
							_this.removeClass("on");
							$item_review_list.find(".customer_review[data-id='"+ir_seq+"'] .review").removeClass("on");
							$item_review_list.find(".customer_review[data-id='"+ir_seq+"'] .reply").removeClass("on");
						}else{
							_this.addClass("on");
							$item_review_list.find(".customer_review[data-id='"+ir_seq+"'] .review").addClass("on");
							$item_review_list.find(".customer_review[data-id='"+ir_seq+"'] .reply").addClass("on");
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
		}
	});
	$item_review_list.on("click", ".update_review_btn", function(){
		var ir_seq = $(this).data("id");
		location.href = "item_review_write.php?ir_seq="+ir_seq;
	});
	$item_review_list.on("click", ".delete_review_btn", function(){
		var ir_seq = $(this).data("id");
		$.MessageBox({
			buttonDone: "확인",
			buttonFail: "취소",
			message: "삭제 하시겠습니까?"
		}).done(function(){
			set_delete_item_review(ir_seq);		
		});
	});

	// textarea auto height
	$item_review_list.on('keyup', 'textarea[name="reply"]', function() {
		$(this).height(1);
		$(this).height((12+$(this).prop("scrollHeight")));
	//	$(window).scrollTop($("body").height());
	});

	$item_review_list.on("click", ".set_update_reply_btn", function(){
		var ir_seq = $(this).data("id");
		var reply = $(this).siblings("textarea").val();
		
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode: "set_update_item_review",
				ir_seq: ir_seq,
				reply: reply
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					$.MessageBox("답글이 반영되었습니다.");
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

	function set_delete_item_review(ir_seq){
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode: "set_delete_item_review",
				ir_seq: ir_seq,
				delete_id: "<?=$user_id ?>",
				delete_msg: "admin.item_reivew_write에서 관리자 삭제"
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					$.MessageBox("리뷰가 삭제되었습니다.");

					get_item_review();
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

	function get_item_review(){
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode: "get_item_review",
				product_no: product_no
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					var html = '';
					
					if(data.data.length > 0){
						$.each(data.data, function(i, v){
							var photo = (v.photo && v.photo != "")? v.photo : "<?=$image_directory ?>/who5.png";
							var review = (v.review && v.review != "")? v.review.replace(/(?:\r\n|\r|\n)/g, "<br />") : "";
							var reply = (v.reply && v.reply != "")? v.reply.replace(/(?:\r\n|\r|\n)/g, "<br />") : "";
							var nickname = (v.customer_id && v.customer_id != "" && v.nickname && v.nickname != "")? v.nickname : v.name;
							var reg_dt = new Date(v.reg_dt.replace(/-/g, '/')); // ios cross browsing
							reg_dt = reg_dt.getFullYear()+'-'+fillZero(2, (reg_dt.getMonth()+1))+'-'+fillZero(2, reg_dt.getDate());
							var reply_dt = (v.reply_dt && v.reply_dt != "")? new Date(v.reply_dt.replace(/-/g, '/')) : ""; // ios cross browsing
							reply_dt = (reply_dt != "")? reply_dt.getFullYear()+'-'+fillZero(2, (reply_dt.getMonth()+1))+'-'+fillZero(2, reply_dt.getDate()) : "";
							var is_blind = (v.is_blind == "1")? " on " : "";
							var is_reply = (v.is_reply == "1")? " on " : "";
							if(v.is_admin == "1"){
								var pay_data = v.product_option_txt;
							}else{
								/*
								var pay_data = JSON.parse(v.pay_data);
								$.each(pay_data, function(i2, v2){
									if(i2 == 0){
										pay_data = v.product_name+", "+v2.txt+", "+v2.amount+"개";
									}
								});
								*/
								var pay_data = v.product_name;
							}
							html += '<div class="customer_review" data-id="'+v.ir_seq+'">';
							html += '	<div class="admin_action">';
							html += '		<div class="reply_review_btn '+is_reply+'" data-id="'+v.ir_seq+'"><i class="fas fa-reply"></i></div>';
							html += '		<div class="blind_review_btn '+is_blind+'" data-id="'+v.ir_seq+'"><i class="far fa-eye"></i></div>';
							html += '		<div class="update_review_btn" data-id="'+v.ir_seq+'"><i class="far fa-edit"></i></div>';
							html += '		<div class="delete_review_btn" data-id="'+v.ir_seq+'"><i class="far fa-trash-alt"></i></div>';
							html += '	</div>';
							html += '	<div class="img" style="background-image: url(\''+photo+'\')"></div>';
							html += '	<ul>';
							html += '		<li>'+nickname+'</li>';
							html += '		<li>';
							html += '			<div class="star-rating"><span class="star">';
							for(var _i = 1; _i <= 5; _i++){
								html += '<i class="fas fa-star"></i>';
								if(_i == v.rating){ html += '</span>'; }
							}
							html += '			</div>';
							html += '		</li>';
							html += '		<li>';
							html += '			<div class="review '+is_blind+'">';
							html += '				<div class="product">'+pay_data+'</div>';
							html += '				<div>'+review+'</div>';
							html += '				<div class="review_image_wrap"></div>';
							html += '			</div>';
							html += '		</li>';
							html += '		<li class="review_dt">'+reg_dt+'</li>';
							html += '	</ul>';
							html += '	<ul class="reply_wrap '+is_reply+'">';
							html += '		<li>';
							html += '			<div class="reply '+is_blind+'">';
							html += '				<div class="reply_icon"><i class="fas fa-reply"></i></div>';
							html += '				<div>';
							html += '					<textarea name="reply">'+reply+'</textarea>';
							html += '					<button type="button" class="set_update_reply_btn" data-id="'+v.ir_seq+'">답글 입력/수정</button>';
							html += '				</div>';
							html += '			</div>';
							html += '		</li>';
							html += '		<li class="reply_dt">'+reply_dt+'</li>';
							html += '	</ul>';
							html += '</div>';
							if(v.review_image != ""){
								var review_img_list = [];
								var review_image = v.review_image.split(',');
								$.each(review_image, function(i2, v2){
									if(v2 != ""){
										review_img_list.push(v2);
									}
								});
								get_item_review_image(v.ir_seq, review_img_list.join(','));
							}
						});
					}else{
						html += '<div class="customer_review">';
						html += '	<div class="no_review">해당 상품의 리뷰가 없습니다.</div>';
						html += '</div>';
					}

					$item_review_list.html(html);
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

	function get_item_review_image(target, img_list){
		// img_loading
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
					console.log(data.data);
					var html = '';
					$.each(data.data, function(i, v){
						html += '<a data-fancybox="gallery_'+target+'" href="'+v.file_path+'"><div class="review_image" style="background-image: url(\''+v.file_path+'\')"></div></a>';
					});

					$item_review_list.find(".customer_review[data-id='"+target+"'] .review_image_wrap").html("").html(html);
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

	//남는 길이만큼 0으로 채움
	function fillZero(width, str){
		var str = String(str);//문자열 변환
		return str.length >= width ? str:new Array(width-str.length+1).join('0')+str;
	}
</script>