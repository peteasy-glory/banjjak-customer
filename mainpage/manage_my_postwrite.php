<?php 
	include "../include/top.php"; 

	if(!isset($_SESSION['gobeauty_user_id']) || !isset($_SESSION['gobeauty_user_nickname'])) {
	?>
	<div class="section_event">
		<!--img src="../images/review_event_3.jpg" style="width:100%;" /-->
		<img src="../images/review_event_3_1_2.jpg" style="width:100%;" />
	</div>
	<?php
		return false;
	}

	$backurl = $_GET['backurl'];
	$r_tab = ($_GET['tab'] && $_GET['tab'] != "")? $_GET['tab'] : "";
	$user_id = $_SESSION['gobeauty_user_id'];
	$user_name = $_SESSION['gobeauty_user_nickname'];
?>
<link rel="stylesheet" href="<?= $css_directory ?>/fancybox.min.css" />
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<script src="../js/fontawesome.min.js"></script>
<script src="<?= $js_directory ?>/fancybox.min.js"></script>
<style>
	#manage_my_postwrite { margin: 60px auto 0px; font-family: 'NL2GR'; max-width: 500px; }
	#manage_my_postwrite .review_tab { position: sticky; top: 60px; z-index: 5; background-color: #fff; }
	#manage_my_postwrite .review_tab ul.table { display: table; width: 100%; }
	#manage_my_postwrite .review_tab ul.table li { display: table-cell; width: 50%; text-align: center; border-bottom: 1px solid #ccc; color: #999; font-size: 18px; margin-top: 20px; }
	#manage_my_postwrite .review_tab ul.table li.on { border-bottom: 3px solid #f5bf2e; color: #f5bf2e; font-family: 'NL2GB'; }
	#manage_my_postwrite .review_tab ul.table li div { height: 40px; line-height: 40px; }
	#manage_my_postwrite .review_banner { position: relative; margin-top: 10px; }
	#manage_my_postwrite .review_banner .point_reward_info { position: absolute; top: 0px; right: 0px; width: 30px; height: 24px; font-size: 18px; text-align: center; padding-top: 6px; color: #666; cursor: pointer; }
	#manage_my_postwrite .review_content ul { padding: 0px 10px; }
	#manage_my_postwrite .review_content ul li { border: 1px solid #ccc; border-radius: 10px; position: relative; padding: 5px; margin: 20px 0px; }
	#manage_my_postwrite .review_content ul li .payment_data_1 { padding-top: 5px; }
	#manage_my_postwrite .review_content ul li .payment_data_1 .title { font-family: 'NL2GB'; }
	#manage_my_postwrite .review_content ul li .payment_product { padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 5px; }
	#manage_my_postwrite .review_content ul li .payment_product .review_data { position: relative; border-top: 1px solid #ccc; margin-top: 10px; padding: 50px 0px 0px 0px; min-height: 50px; }
	#manage_my_postwrite .review_content ul li .payment_product .review_data .btn_wrap { position: absolute; right: 0px; top: 0px; display: inline-flex; }
	#manage_my_postwrite .review_content ul li .payment_product .review_data .btn_wrap .update_member_review_btn { display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; background-color: #eee; border-radius: 5px; margin: 5px 2px; }
	#manage_my_postwrite .review_content ul li .payment_product .review_data .btn_wrap .delete_member_review_btn { display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; background-color: #eee; border-radius: 5px; margin: 5px 2px; }
	#manage_my_postwrite .review_content ul li .payment_product .review_data .review_data_wrap { position: relative; border: 1px solid #ccc; background-color: #f9f9f9; padding: 10px; border-radius: 5px; }
	#manage_my_postwrite .review_content ul li .payment_product .review_data .review_data_wrap .rating { font-size: 14px; color: #999; }
	#manage_my_postwrite .review_content ul li .payment_product .review_data .review_data_wrap .rating .star { color: #f5bf2e; }
	#manage_my_postwrite .review_content ul li .payment_product .review_data .review_data_wrap .reg_dt { position: absolute; right: 10px; top: 10px; font-size: 14px; }
	#manage_my_postwrite .review_content ul li .payment_product .review_data .review_data_wrap .review_img a { width: 58px; height: 58px; margin: 10px 2px 5px 2px; }
	#manage_my_postwrite .review_content ul li .payment_product .review_data .review_data_wrap .review_img a .review_image { width: 100%; height: 100%; background-size: cover; background-repeat: no-repeat; background-position: center; background-color: #eee; }
	#manage_my_postwrite .review_content ul li .payment_product .review_data .review_data_wrap .review { margin-top: 10px; width: 100%; word-break: break-all; text-overflow:ellipsis; white-space: nowrap; overflow: hidden; height: 55px; }
	#manage_my_postwrite .review_content ul li .payment_product .review_data .review_data_wrap .more_btn { position: absolute; right: 0px; bottom: 0px; border: 1px solid #ccc; border-radius: 5px; background-color: #eee; padding: 0px 10px; height: 30px; }
	#manage_my_postwrite .review_content ul li .payment_product .standby { background-color: #eee; color: #999; text-align: center; padding: 20px 0px; margin-top: 10px; }
	#manage_my_postwrite .review_content ul li .payment_product .insert_member_review_btn{ background-color: #f5bf2e !important; border: 1px solid #f5bf2e !important; color: #fff !important; height: 40px !important; font-size: 18px; width: 100%; border-radius: 5px; margin-top: 10px; }
	#manage_my_postwrite .insert_member_review_btn{ background-color: #f5bf2e !important; border: 1px solid #f5bf2e !important; color: #fff !important; height: 40px !important; font-size: 18px; width: 100%; border-radius: 5px; margin-top: 10px; }
	#manage_my_postwrite .review_content ul li .no_data { background-color: #eee; color: #999; text-align: center; padding: 20px 0px; margin-top: 10px; }

	#manage_my_postwrite .review_content .no_review img { width: 100%; }

</style>

<div class="top_menu">
    <div class="top_back"><a href="<?= $mainpage_directory ?>/mainpage_my_menu.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div class="top_title">
        <p>내 후기 작성/변경</p>
    </div>
</div>

<div id="manage_my_postwrite"></div>

<script>
	var $manage_my_postwrite = $("#manage_my_postwrite");
	var customer_id = "<?=$user_id ?>";
	var tab = ("<?=$r_tab ?>" != "")? "<?=$r_tab ?>" : '1';

	$(function(){
		get_review_html();
	});

	// 탭 이동
	$(document).on("click", ".review_tab ul li", function(){
		tab = $(this).data("tab");
		$(".review_tab ul li").removeClass("on");
		$(this).addClass("on");
		history.pushState('', '', '<?=$mainpage_directory ?>/manage_my_postwrite.php?tab='+tab);

		get_review_html();
	});

	// 더보기
	$manage_my_postwrite.on("click", ".more_btn", function(){
		if($(this).hasClass("on")){
			$(this).removeClass("on");
			$(this).html('더보기 <i class="fas fa-chevron-down"></i>');
			$(this).parent().parent().find(".review").css("text-overflow", "ellipsis");
			$(this).parent().parent().find(".review").css("white-space", "nowrap");
			$(this).parent().parent().find(".review").css("overflow", "hidden");
			$(this).parent().parent().find(".review").css("height", "50px");
		}else{
			$(this).addClass("on");
			$(this).html('더보기 <i class="fas fa-chevron-up"></i>');
			$(this).parent().parent().find(".review").css("text-overflow", "inherit");
			$(this).parent().parent().find(".review").css("white-space", "inherit");
			$(this).parent().parent().find(".review").css("overflow", "inherit");
			$(this).parent().parent().find(".review").css("height", "100%");
		}
	});

	// 미용 - 입력
	$manage_my_postwrite.on("click", ".beauty_review .insert_member_review_btn", function(){
		var payment_log_seq = $(this).parent().parent().data("payment_log_seq");
		var artist_id = $(this).parent().parent().data("artist_id");
		history.pushState('', '', '<?=$mainpage_directory ?>/manage_my_postwrite.php?tab='+tab)
		location.href = "<?=$mainpage_directory ?>/write_usage_review.php?key=add&payment_log_seq="+payment_log_seq+"&artist_id="+artist_id+"&backurl="+encodeURIComponent(window.location.pathname+"?tab="+tab);
	});
	
	// 미용 - 수정
	$manage_my_postwrite.on("click", ".beauty_review .update_member_review_btn", function(){
		var payment_log_seq = $(this).parent().parent().data("payment_log_seq");
		var artist_id = $(this).parent().parent().data("artist_id");
		history.pushState('', '', '<?=$mainpage_directory ?>/manage_my_postwrite.php?tab='+tab)
		location.href = "<?=$mainpage_directory ?>/write_usage_review.php?key=add&payment_log_seq="+payment_log_seq+"&artist_id="+artist_id+"&backurl="+encodeURIComponent(window.location.pathname+"?tab="+tab);
	});

	// 미용 - 삭제
	$manage_my_postwrite.on("click", ".beauty_review .delete_member_review_btn", function(){
		var payment_log_seq = $(this).parent().parent().data("payment_log_seq");
		var artist_id = $(this).parent().parent().data("artist_id");
		$.MessageBox({
			buttonDone: "확인",
			buttonFail: "취소",
			message: "삭제 하시겠습니까?"
		}).done(function(){		
			location.href = "<?=$mainpage_directory ?>/insert_usage_review.php?key=delete&payment_log_seq="+payment_log_seq+"&artist_id="+artist_id;
		});
	});

	// 상품 - 입력
	$manage_my_postwrite.on("click", ".item_review .insert_member_review_btn", function(){
		var order_num = $(this).data("order_num");
		var iplp_seq = $(this).data("iplp_seq");
		history.pushState('', '', '<?=$mainpage_directory ?>/manage_my_postwrite.php?tab='+tab)
		location.href = "<?=$item_directory ?>/item_review_write.php?no="+order_num+"&iplp_seq="+iplp_seq+"&backurl="+encodeURIComponent(window.location.pathname+"?tab="+tab);
	});
	
	// 상품 - 수정
	$manage_my_postwrite.on("click", ".item_review .update_member_review_btn", function(){
		var ir_seq = $(this).data("ir_seq");
		history.pushState('', '', '<?=$mainpage_directory ?>/manage_my_postwrite.php?tab='+tab)
		location.href = "<?=$item_directory ?>/item_review_write.php?seq="+ir_seq+"&backurl="+encodeURIComponent(window.location.pathname+"?tab="+tab);
	});

	// 상품 - 삭제
	$manage_my_postwrite.on("click", ".item_review .delete_member_review_btn", function(){
		var ir_seq = $(this).data("ir_seq");
		$.MessageBox({
			buttonDone: "확인",
			buttonFail: "취소",
			message: "삭제 하시겠습니까?"
		}).done(function(){		
			set_delete_item_review(ir_seq);
		});
	});

	// 상품 - 후기 삭제
	function set_delete_item_review(ir_seq){
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode: "set_delete_item_review",
				ir_seq: ir_seq,
				delete_id: customer_id,
				delete_msg: "my_postwrite에서 직접 삭제"
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					$.MessageBox("삭제되었습니다.");
					
					get_review_html();
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

	function get_review_html(){
		var html = '';
		html += '<div class="review_tab">';
		html += '	<ul class="table">';
		html += '		<li data-tab="1">';
		html += '			<div>미용</div>';
		html += '		</li>';
		html += '		<li data-tab="2">';
		html += '			<div>상품</div>';
		html += '		</li>';
		html += '	</ul>';
		html += '</div>';
		html += '<div class="review_content"></div>';
		$manage_my_postwrite.html(html);
		$manage_my_postwrite.find(".review_tab ul li[data-tab='"+tab+"']").addClass("on");

		if(tab == "1"){
			// add beauty banner
			$manage_my_postwrite.find('.review_tab').after('<div class="review_banner"><img src="../images/n_200205_review_banner_04_1.jpg"><p style="margin-top:5px;">*포인트는 작성한 달의 다음달 초에 적립됩니다.</p></div>');
			get_member_payment_list();
		}else{
			$manage_my_postwrite.find('.review_tab').after('<div class="review_banner"><img src="../images/n_200205_review_banner_04_1_2_3.jpg"><p style="margin-top:5px;">*포인트는 작성한 달의 다음달 초에 적립됩니다.</p></div>');
			get_member_item_list()
				.then(get_item_payment_log_product);
		}
	}

	// 상품 후기
	function get_member_item_list(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode: "get_item_payment_log",
					customer_id: customer_id,
					is_review: "1"
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						var html = '';
						
						if(data.data.length > 0){
							$.each(data.data, function(i, v){
								var reg_dt = new Date(v.reg_dt.replace(/-/g, '/')); // ios cross browsing

								html += '<div class="item_review" data-id="'+v.ip_log_seq+'">';
								html += '	<ul>';
								html += '		<li class="item_payment_log" data-order_num="'+v.order_num+'">';
								html += '			<div class="payment_data_1">';
								html += '				<div class="title">'+v.product_name+'</div>';
								html += '				<div>'+reg_dt.getFullYear()+'년 '+fillZero(2, (reg_dt.getMonth()+1))+'월 '+fillZero(2, reg_dt.getDate())+'일 '+fillZero(2, reg_dt.getHours())+'시 '+fillZero(2, reg_dt.getMinutes())+'분 </div>';
								html += '			</div>';
								html += '		</li>';
								html += '	</ul>';
								html += '</div>';
							});
							$manage_my_postwrite.find(".review_content").html(html);

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
							// 상품 구매 내역이 없음
							html = '<div class="no_review"><img src="../images/review_none_1_2.jpg" /></div>';
							$manage_my_postwrite.find(".review_content").html(html);
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
									var _pay_data_list = (v.option_data && v.option_data != '')? ((v.option_data.indexOf('||') != -1)? v.option_data.split('||') : [v.option_data]) : [];
									if(_pay_data_list.length > 0){
										$.each(_pay_data_list, function(i2, v2){
											var _pay_data = $.parseJSON(v2.replace(/\\/g, ''));
											html += '<div class="payment_product" data-iplp_seq="'+v.iplp_seq+'" style="position: relative; min-height: 50px;">';
											html += '	<div class="item_image" style="position: absolute; left: 10px; top: 10px; width: 40px; height: 40px; background-image: url(\'/pet/images/product_img.png\'); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>';
											if(_pay_data.length > 0){
												$.each(_pay_data, function(i3, v3){
													html += '<div style="margin: 5px 0px 5px 50px; min-height: 30px;">';
													html +=		'<span>'+v3.txt+', '+v3.amount+'개</span>';
													html += '</div>';
												});
											}
											html += '</div>';
										});
									}
								});
								$manage_my_postwrite.find(".review_content .item_review[data-id='"+ip_log_seq+"'] ul li").append(html);
								$.each(data.data, function(i, v){
									get_member_item_review_list(v.order_num, v.iplp_seq, v.pay_status, ip_log_seq);
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
								$manage_my_postwrite.find(".review_content .item_review[data-id='"+ip_log_seq+"'] ul li").append(html);
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

	function get_member_item_review_list(order_num, iplp_seq, pay_status, ip_log_seq){
		return new Promise(function(resolve, reject) {
			console.log(order_num, iplp_seq);
			if(order_num != "" && iplp_seq != ""){
				$.ajax({
					url: '<?=$item_directory ?>/item_list_ajax.php',
					data: {
						mode: "get_item_review",
						order_num: order_num,
						iplp_seq: iplp_seq
					},
					type: 'POST',
					dataType: 'JSON',
					success: function(data) {
						if(data.code == "000000"){
							console.log(data.data);
							var html = '';
							
							if(data.data.length > 0){
								$.each(data.data, function(i, v){
									var reg_dt = new Date(v.reg_dt.replace(/-/g, '/')); // ios cross browsing
									var review = (v.review && typeof v.review != "undefined" && v.review != "")? v.review.replace(/(?:\r\n|\r|\n)/g, "<br />") : "";
									html += '<div class="review_data">';
									html += '	<div class="btn_wrap">';
									html += '		<div class="update_member_review_btn" data-ir_seq="'+v.ir_seq+'"><i class="far fa-edit"></i></div>';
									html += '		<div class="delete_member_review_btn" data-ir_seq="'+v.ir_seq+'"><i class="far fa-trash-alt"></i></div>';
									html += '	</div>';
									html += '	<div class="review_data_wrap">';
									html += '		<div class="rating"><span class="star">';
									for(var _i = 1; _i <= 5; _i++){
										html += '<i class="fas fa-star"></i>';
										if(_i == v.rating){
											html += '</span>';
										}
									}
									html += '		</div>';
									html += '		<div class="reg_dt">'+reg_dt.getFullYear()+'-'+fillZero(2, (reg_dt.getMonth()+1))+'-'+fillZero(2, reg_dt.getDate())+'</div>';
									html += '		<div class="review_img">';
									html += '		</div>';
									html += '		<div class="review">'+review+'</div>';
									html += '		<button type="button" class="more_btn">더보기 <i class="fas fa-chevron-down"></i></button>';
									html += '	</div>';
									html += '</div>';
									if(v.reply && v.reply != ""){
										var reply_dt = (v.reply_dt && typeof v.review != "undefined" && v.review != "")? new Date(v.reply_dt.replace(/-/g, '/')) : ""; // ios cross browsing
										html = ''; // init
										html += '	<div class="reply_wrap">';
										html += '		<div class="reply_icon"><i class="fas fa-reply"></i></div>';
										html += '		<div class="reply">'+v.reply+'</div>';
										if(reply_dt != ""){
											html += '		<div class="reply_dt">'+reply_dt.getFullYear()+'-'+fillZero(2, (reply_dt.getMonth()+1))+'-'+fillZero(2, reply_dt.getDate())+'</div>';
										}
										html += '	</div>';
									}
								});
								
							}else{
								if(pay_status == "4"){
									html += '	<button type="button" class="insert_member_review_btn" data-order_num="'+order_num+'" data-iplp_seq="'+iplp_seq+'">후기작성</button>';
								}else{
									html += '	<div class="standby">배송완료 후 후기 작성이 가능합니다.</div>';
								}
							}
							$manage_my_postwrite.find(".review_content .item_review[data-id='"+ip_log_seq+"'] ul li .payment_product[data-iplp_seq='"+iplp_seq+"']").append(html);
							
							if(data.data.length > 0){
								$.each(data.data, function(i, v){
									get_item_review_image(order_num, v.review_image);
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
			}else{
				// 리뷰없음
				if(pay_status == "6"){
					html += '	<button type="button" class="insert_member_review_btn" data-order_num="'+order_num+'" data-iplp_seq="'+iplp_seq+'">후기작성</button>';
				}else{
					html += '	<div class="standby">배송완료 후 후기 작성이 가능합니다.</div>';
				}
				$manage_my_postwrite.find(".review_content .item_review[data-id='"+ip_log_seq+"'] ul li .payment_product[data-iplp_seq='"+iplp_seq+"']").append(html);
			}
		});
	}

	// 리뷰 이미지
	function get_item_review_image(target, img_list){
		// img_loading
		if(typeof img_list != "undefined" && img_list != ""){
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

						$manage_my_postwrite.find(".review_content ul li.item_payment_log[data-order_num='"+target+"'] .review_img").html("").html(html);
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

	// 상품 후기 이미지
	function get_item(ip_log_seq, pay_data){
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
									if(i == 0){ // 1장만 필요
										$manage_my_postwrite.find('.item_review[data-id="'+ip_log_seq+'"] .payment_product').attr('data-id', v.product_no);
										get_item_image('.item_review[data-id="'+ip_log_seq+'"] .payment_product', v.product_no);
									}
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
								get_file_list(target, v.product_no, v.product_img, v.goodsRepImage);
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

	function get_file_list(target, product_no, img_list, goodsRepImage){
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
										$manage_my_postwrite.find(target+'[data-id="'+product_no+'"] .item_image').css('background-image', 'url("'+v.file_path+'")');
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
					$manage_my_postwrite.find(target+'[data-id="'+product_no+'"] .item_image').css('background-image', 'url("'+goodsRepImage+'")');
				}else{
					$manage_my_postwrite.find(target+'[data-id="'+product_no+'"] .item_image').css('background-image', 'url("../images/product_img.png")');
				}
			}
		});
	}


	// 미용 후기
	function get_member_payment_list(){
		$.ajax({
			url: '<?=$mainpage_directory ?>/manage_my_postwrite_ajax.php',
			data: {
				mode : "get_member_payment_list",
				customer_id: customer_id
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					//console.log(data.data);

					var html = '';
					if(data.data.length > 0){
						$.each(data.data, function(i, v){
							var service_info = v.product.split('|');
							html += '<div class="beauty_review">';
							html += '	<ul>';
							html += '		<li class="payment_log" data-payment_log_seq="'+v.payment_log_seq+'" data-artist_id="'+v.artist_id+'">';
							html += '			<div class="artist_photo" style="background-image: url('+v.artist_photo+')"></div>';
							html += '			<div class="section_02">';
							html += '				<div class="title">'+v.name+'</div>';
							html += '				<div>'+v.year+'년 '+fillZero(2, v.month)+'월 '+fillZero(2, v.day)+'일 '+fillZero(2, v.hour)+'시</div>';
							html += '			</div>';
							html += '			<div class="section_03">';
							html += '			</div>';
							html += '			<div class="section_04">';
							if(service_info[1] = "개"){
								html += service_info[0];
								html += (service_info[3] != "")? ' / '+service_info[3] : '';
								html += (service_info[4].split(':')[0] != "")? ' / '+service_info[4].split(':')[0] : '';
								html += (service_info[5].split(':')[0] != "")? ' / ~'+service_info[5].split(':')[0]+' Kg' : '';
								html += (service_info[3] == "" && service_info[4].split(':')[0] == "" && service_info[5].split(':')[0] == "")? ' - 기타 미용' : '';
							}else{
								html += service_info[0];
								html += (service_info[3] != "")? ' / '+service_info[3] : '';
								html += (service_info[5].split(':')[0] != "")? ' / '+service_info[5].split(':')[0] : '';
								html += (service_info[4].split(':')[0] != "")? ' / ~'+service_info[4].split(':')[0]+' Kg' : '';
							}
							html += '			</div>';
							html += '			<div class="section_05"></div>';
							html += '			<div class="section_06">';
							var beauty_date = new Date(v.year, fillZero(2, v.month - 1), fillZero(2, v.day), fillZero(2, v.to_hour), "00", "00");
							beauty_date.setMinutes(beauty_date.getMinutes() + 30);
							console.log(beauty_date, new Date(), beauty_date.getTime(), new Date().getTime());
							if(beauty_date.getTime() < new Date().getTime()){
								html += '				<button type="button" class="insert_member_review_btn">후기작성</button>';
							}else{
								html += '				<div class="standby">미용 종료 30분 후 후기 작성이 가능합니다.</div>';
							}
							html += '			</div>';
							html += '		</li>';
							html += '	</ul>';
							html += '</div>';
						});

						$manage_my_postwrite.find(".review_content").html(html);
						$manage_my_postwrite.find(".review_content ul li.payment_log").each(function(i, v){
							get_member_review_list($(this).data("payment_log_seq"));
						});
						$manage_my_postwrite.find(".review_banner").append('<div class="point_reward_info"><i class="far fa-question-circle"></i></div>');
					}else{
						html = '<div class="no_review"><img src="../images/review_none_1_2.jpg" /></div>';
						$manage_my_postwrite.find(".review_content").html(html);
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

	function get_member_review_list(payment_log_seq){
		$.ajax({
			url: '<?=$mainpage_directory ?>/manage_my_postwrite_ajax.php',
			data: {
				mode : "get_member_review_list",
				customer_id: customer_id,
				payment_log_seq: payment_log_seq
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);

					var html = '';
					$.each(data.data, function(i, v){
						var reg_dt = new Date(v.reg_time.replace(/-/g, '/')); // ios cross browsing
						var review = v.review.replace(/(?:\r\n|\r|\n)/g, "<br />");
						var review_images = (v.review_images.indexOf('|'))? v.review_images.split('|') : [v.review_images];
						html = ''; // init
						html += '			<div class="update_member_review_btn"><i class="far fa-edit"></i></div>';
						html += '			<div class="delete_member_review_btn"><i class="far fa-trash-alt"></i></div>';
						$manage_my_postwrite.find(".review_content ul li.payment_log[data-payment_log_seq='"+payment_log_seq+"'] .section_03").html(html);

						html = ''; // init
						html += '	<div>';
						html += '		<div class="rating"><span class="star">';
						for(var _i = 1; _i <= 5; _i++){
							html += '<i class="fas fa-star"></i>';
							if(_i == v.rating){
								html += '</span>';
							}
						}
						html += '		</div>';
						html += '		<div class="reg_dt">'+reg_dt.getFullYear()+'-'+fillZero(2, (reg_dt.getMonth()+1))+'-'+fillZero(2, reg_dt.getDate())+'</div>';
						html += '		<div class="review_img">';
						if(review_images.length > 0){
							$.each(review_images, function(i2, v2){
								if(v2 != ""){
									html += '<a data-fancybox="gallery_'+payment_log_seq+'" href="'+v2+'"><div class="review_image" style="background-image: url(\''+v2+'\')"></div></a>';
								}
							});
						}
						html += '		</div>';

						html += '		<div class="review">'+review+'</div>';
						html += '		<button type="button" class="more_btn">더보기 <i class="fas fa-chevron-down"></i></button>';
						html += '	</div>';
						$manage_my_postwrite.find(".review_content ul li.payment_log[data-payment_log_seq='"+payment_log_seq+"'] .section_05").html(html);
						$manage_my_postwrite.find(".review_content ul li.payment_log[data-payment_log_seq='"+payment_log_seq+"'] .section_06").html('');
					});
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