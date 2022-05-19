<?php
include "../include/top.php";

$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}
$r_seq = ($_GET['seq'] && $_GET['seq'] != "")? $_GET['seq'] : "";
$backurl = $_GET['backurl'];
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

?>
<link rel="stylesheet" href="<?= $css_directory ?>/fancybox.min.css" />
<script src="<?= $js_directory ?>/fancybox.min.js"></script>
<script src="<?= $js_directory ?>/fontawesome.min.js"></script>
<script src="<?= $js_directory ?>/jquery.ui.widget.js"></script>
<script src="<?= $js_directory ?>/jquery.iframe-transport.js"></script>
<script src="<?= $js_directory ?>/jquery.fileupload.js"></script>
<style>
	.bjj_top_menu { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; border-bottom: 1px solid #ccc; background-color: rgba(255, 255, 255, 0.8); margin-top: env(safe-area-inset-top); margin-top: constant(safe-area-inset-top); z-index: 10; }
	.bjj_top_menu .bjj-back-btn { position: absolute; left: 8px; top: 12px; }
	.bjj_top_menu .bjj-back-btn img { width: 26px; vertical-align: middle; }
	.bjj_top_menu .bjj_top_title { width: 100%; height: 50px; line-height: 50px; text-align: center; padding: 0px; }
	.bjj_top_menu .bjj_top_title p { padding: 0px; margin: 0px; }
	.bjj_top_menu .bjj_review { position: absolute; right: 5px; top: 8px; }
	.bjj_top_menu .bjj_review button { height: 35px; padding: 0px 10px; font-size: 14px; line-height: 18px; border-radius: 5px; border: 1px solid #ccc; background-color: #eee; }
	.bjj_top_menu .bjj_review span { font-size: 18px; }

	#item_write { position: relative; width: 100%; margin-top: calc(env(safe-area-inset-top) + 61px); margin-top: calc(constant(safe-area-inset-top) + 61px); } 
	#item_write .item_write_wrap { width: calc(100% - 20px); padding: 10px; }
	#item_write ul { list-style: none; width: 100%; padding: 0px; margin: 0px; }
	#item_write ul li { position: relative; padding: 10px 0px; }
	#item_write ul li.line { border-top: 1px solid #eee; padding-top: 10px; margin-top: 10px; }
	#item_write ul li.line .info { top: 12px; }
	#item_write ul li.line .value { margin-top: 5px; }
	#item_write ul li .info { position: absolute; left: 0px; top: 0px; width: 100px; height: 40px; line-height: 40px; }
	#item_write ul li .info span { color: #f00; }
	#item_write ul li .value { margin-top: -10px; margin-left: 110px; width: calc(100% - 110px); text-align: right; }
	#item_write ul li .value .category_list { border: 1px solid #ccc; min-height: 80px; margin-top: 5px; margin-bottom: 10px; text-align: left; margin-left: -110px; padding: 10px; }
	#item_write ul li .value .category_list div { height: 30px; line-height: 30px; }
	#item_write ul li .value .img_list { border: 1px solid #ccc; min-height: 80px; margin-top: 5px; margin-bottom: 10px; text-align: left; margin-left: -110px; padding: 10px; }
	#item_write ul li .value .img_list .upload_file_wrap { position: relative; display: inline-block; width: 80px; height: 80px; border: 1px solid #eee; }
	#item_write ul li .value .img_list .upload_file_wrap img { width: 100%; }
	#item_write ul li .value .img_list .upload_file_wrap .set_delete_file_btn { position: absolute; right: 0px; top: 0px; width: 20px; height: 20px; line-height: 20px; text-align: center; background-color: rgba(255,255,255,0.8); border-radius: 10px; padding-top: 2px; }
	#item_write ul li .msg { font-size: 10px; color: #999; }
	#item_write ul li input[type='text'] { width: calc(100% - 40px); border: 1px solid #ccc; background-color: #fff; height: 40px; line-height: 40px; font-size: 16px; padding: 0px 10px; border-radius: 5px; }
	#item_write ul li input[type='text'].err { background-color: #fcc; color: #f00; }
	#item_write ul li input[type='text'].amount { text-align: right; }
	#item_write ul li input[type='checkbox'] { -webkit-appearance: none; width: 0px; top: 0px; font-size: 0px; margin: 0px; padding: 0px; }
	#item_write ul li input[type='checkbox']+label { display: inline-block; border: 1px solid #ccc; border-radius: 5px; height: 40px; line-height: 40px; padding: 0px 10px; }
	#item_write ul li input[type='checkbox']:checked+label { display: inline-block; border: 1px solid #f5bf2e; background-color: #f5bf2e; color: #fff; border-radius: 5px; }
	#item_write ul li button { height: 40px; line-height: 40px; padding: 0px 10px; border: 1px solid #ccc; background-color: #fff; color: #333; border-radius: 5px; }
	#item_write ul li textarea { width: calc(100% + 90px); min-height: 100px; margin-top: 50px; margin-left: -110px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
	#item_write ul li select { width: calc(100% - 18px); height: 40px; line-height: 40px; margin-bottom: 2px; padding: 0px 10px; border: 1px solid #ccc; background-color: #fff; border-radius: 5px; }
	#item_write ul li .option_use_wrap { margin-top: 20px; text-align: center; display: none; }
	#item_write ul li .option_use_wrap.on { display: block; }
	#item_write ul li .option_use_wrap .option_box { border: 1px solid #ccc; border-radius: 5px; margin-top: 10px; }
	#item_write ul li .option_use_wrap .option_box .title { width: 100%; height: 50px; line-height: 50px; background-color: #eee; border-radius: 5px 5px 0px 0px; text-align: left; }
	#item_write ul li .option_use_wrap .option_box .title input[type='text'] { width: calc(100% - 80px); margin-top: 2px; margin-left: 10px; background-color: transparent; border: 0px; border-bottom: 1px solid #999; border-radius: 0px; }
	#item_write ul li .option_use_wrap .option_box .del_option_btn { width: 40px; height: 40px; font-size: 16px; }
	#item_write ul li .option_use_wrap .option_box ul { width: calc(100% - 20px); padding: 10px; }
	#item_write ul li .option_use_wrap ul li { padding: 5px 0px; }
	#item_write ul li .option_use_wrap .info { text-align: left; font-size: 14px; }
	#item_write ul li .option_use_wrap .value { width: calc(100% - 110px); }
	#item_write ul li .option_use_wrap .option_button_wrap { padding: 10px; }
	#item_write ul li .option_use_wrap .option_button_wrap .option_update_btn { width: 100%; border: 1px solid #f5bf2e; background-color: #f5bf2e; color: #fff; font-size: 16px; }
	#item_write ul li .add_option_btn { margin-top: 10px; background-color: #eee; font-size: 16px; width: calc(100% - 20px); }
	#item_write ul li .curation_wrap { border: 1px solid #ccc; border-radius: 5px; }
	#item_write ul li .curation_wrap .title { height: 50px; line-height: 50px; text-align: center; background-color: #eee; border-radius: 5px 5px 0px 0px; }
	#item_write ul li .curation_wrap .value { width: calc(100% - 20px); margin: 0px; padding: 10px; text-align: left; }
	#item_write ul li button.item_group_option_dialog_btn { height: 42px; padding: 2px 10px; }
	#item_write ul li.md_list { display: none; border: 1px solid #ccc; padding: 5px; margin: 10px; border-radius: 10px; background-color: #f9f9f9; }
	#item_write ul li.md_list.on { display: block; }
	#item_write ul li.md_list .info { white-space: nowrap; font-size: 14px; }
	#item_write ul li.md_list .value input { width: calc(100% - 60px); }
	#item_write .btn_wrap { width: 100%; text-align: center; }
	#item_write .submit_btn { width: 100%; height: 50px; line-height: 50px; background-color: #fff; color: #333; border: 1px solid #ccc; font-size: 16px; border-radius: 5px; margin-bottom: 5px; }
	#item_write .submit_btn.on { background-color: #f5bf2e; color: #fff; border: 1px solid #f5bf2e; }
	#item_write .set_delete_item_list_btn { width: 100%; height: 50px; line-height: 50px; background-color: #F5442E; color: #fff; border: 1px solid #ccc; font-size: 16px; border-radius: 5px; }

	/* 카테고리 팝업 */
	#category_wrap select { width: 100%; padding: 0px 10px; height: 40px; line-height: 40px; border: 1px solid #ccc; background-color: #fff; margin-bottom: 2px; }
	#category_wrap button { width: 100%; padding: 0px 10px; height: 40px; line-height: 40px; background-color: #f5bf2e; color: #fff; border: 1px solid #f5bf2e; }
	#item_write .del_category,
	#category_wrap .del_category { display: inline-block; width: 24px; height: 24px; line-height: 24px; text-align: center; text-decoration: none; border: 1px solid #ccc; background-color: #f9f9f9; border-radius: 5px; color: #333; vertical-align: middle; margin-left: 2px; }
	#item_write .del_category svg,
	#category_wrap .del_category svg { margin-top: 4px; }
	#category_wrap .category_list { border: 1px solid #ccc; padding: 10px; margin-top: 10px; min-height: 80px; }
	#category_wrap .category_list div { height: 30px; line-height: 30px; }

	/* 그룹옵션 */
	#item_group_option { border: 1px solid #ccc; font-size: 14px; font-family: 'NL2GR'; font-weight: normal; padding: 0px; }
	#item_group_option ul { list-style: none; margin: 0px; padding: 0px; }
	#item_group_option button { height: 30px; line-height: 30px; padding: 0px 10px; border: 1px solid #ccc; background-color: #eee; border-radius: 5px; font-family: 'NL2GR'; font-weight: normal; }
	#item_group_option button>span { display: none; }
	#item_group_option input[type='text'],
	#item_group_option input[type='number'] { min-width: 100px; height: 30px; padding: 0px 10px; border: 0px; border-bottom: 1px solid #ccc; border-radius: 0px; background-color: #fff; }
	#item_group_option input[type='radio'] { display: none; width: 0px; height: 0px; font-size: 0px; border: 0px; margin: 0px; padding: 0px; }
	#item_group_option input[type='radio']+label { position: relative; display: inline-block; height: 30px; line-height: 30px; padding: 0px 10px 0px 35px; border: 1px solid #ccc; border-radius: 10px; background-color: #fff; }
	#item_group_option input[type='radio']+label>span { position: absolute; display: inline-block; left: 5px; top: 5px; width: 20px; height: 20px; border: 1px solid #ccc; border-radius: 100%; background-color: #fff; }
	#item_group_option input[type='radio']:checked+label { background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
	#item_group_option input[type='radio']:checked+label>span { border: 1px solid #f5bf2e; }
	#item_group_option input[type='radio']:checked+label>span::before { content: ''; position: absolute; display: inline-block; left: 5px; top: 5px; width: 10px; height: 10px; background-color: #333; border-radius: 100%; }
	#item_group_option .title { position: relative; height: 40px; line-height: 40px; padding: 0px 10px; font-size: 16px; font-weight: Bold; }
	#item_group_option .title .right_menu { position: absolute; right: 0px; top: 0px; }
	#item_group_option .title .right_menu button { margin: 5px 5px 0px 0px; }
	#item_group_option .title .right_menu button.insert_item_group_option_btn { margin: 5px 10px 0px 0px; }
	#item_group_option .no_data { width: calc(100% - 20px); margin: 10px auto; border-radius: 5px; text-align: center; padding: 40px 0px; background-color: #eee; color: #999; }
	#item_group_option .set_write_item_group_option_box { width: calc(100% - 40px); margin: 10px auto; padding: 10px; background-color: #fff; border: 1px solid #ccc; border-radius: 10px; }
	#item_group_option .set_write_item_group_option_box ul li { padding: 10px; }
	#item_group_option .set_write_item_group_option_box ul li .title { font-size: 12px; line-height: 20px; height: 20px; padding: 0px; font-weight: normal; }
	#item_group_option .set_write_item_group_option_box ul li input[type='text'] { width: calc(100% - 20px); }
	#item_group_option .set_write_item_group_option_box .btn_wrap { margin-top: 10px; border-top: 1px dashed #999; padding-top: 10px; }
	#item_group_option .set_write_item_group_option_box .btn_wrap button { width: 100%; height: 40px; }
	#item_group_option .item_group_option_detail_list_wrap { width: calc(100% - 20px); margin: 0 auto; }
	#item_group_option .item_group_option_detail_list_wrap .no_data { padding: 20px 0px; }
	#item_group_option .item_group_option_detail_list_wrap .group_option { width: 100%; background-color: #f9f9f9; border-radius: 5px; margin: 10px auto; border: 1px solid #ccc; }
	#item_group_option .item_group_option_detail_list_wrap .group_option .title>span { display: inline-block; width: 60%; white-space: nowrap; overflow: hidden; text-overflow:ellipsis; }
	#item_group_option .item_group_option_detail_list_wrap .group_option button.insert_item_group_option_detail_btn { background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
	#item_group_option .item_group_option_detail_list_wrap .group_option .group_fold { display: none; width: calc(100% - 20px); background-color: #eee; border-radius: 5px; margin: 10px auto; border: 1px solid #eee; }
	#item_group_option .item_group_option_detail_list_wrap .group_option .group_fold.on { display: block; }
	#item_group_option .item_group_option_detail_list_wrap .group_option_detail { width: 100%; padding: 1px 0px; }
	#item_group_option .item_group_option_detail_list_wrap .group_option_detail .title { position: relative; width: initial; font-weight: normal; }
	#item_group_option .item_group_option_detail_list_wrap .group_option_detail .title .image { position: absolute; left: 5px; top: 5px; display: inline-block; width: 30px; height: 30px; background-color: #ccc; border-radius: 5px; background-size: cover; background-repeat: no-repeat; background-position: center; }
	#item_group_option .item_group_option_detail_list_wrap .set_write_item_group_option_detail_box { width: calc(100% - 40px); margin: 10px auto; padding: 10px; background-color: #fff; border: 1px solid #ccc; border-radius: 10px; }
	#item_group_option .item_group_option_detail_list_wrap .set_write_item_group_option_detail_box .title { font-weight: Bold; }
	#item_group_option .item_group_option_detail_list_wrap .set_write_item_group_option_detail_box ul li { padding: 10px; }
	#item_group_option .item_group_option_detail_list_wrap .set_write_item_group_option_detail_box ul li .title { font-size: 12px; line-height: 20px; height: 20px; padding: 0px; font-weight: normal; }
	#item_group_option .item_group_option_detail_list_wrap .set_write_item_group_option_detail_box ul li input[type='text'] { width: calc(100% - 20px); }
	#item_group_option .item_group_option_detail_list_wrap .set_write_item_group_option_detail_box ul li input[type='number'] { width: calc(100% - 20px); text-align: right; }
	#item_group_option .item_group_option_detail_list_wrap .set_write_item_group_option_detail_box ul li .img_list { border: 1px solid #ccc; min-height: 80px; margin-top: 5px; margin-bottom: 10px; text-align: left; padding: 10px; }
	#item_group_option .item_group_option_detail_list_wrap .set_write_item_group_option_detail_box ul li .img_list .upload_file_wrap { position: relative; display: inline-block; width: 80px; height: 80px; border: 1px solid #eee; }
	#item_group_option .item_group_option_detail_list_wrap .set_write_item_group_option_detail_box ul li .img_list .upload_file_wrap img { width: 100%; }
	#item_group_option .item_group_option_detail_list_wrap .set_write_item_group_option_detail_box ul li .img_list .upload_file_wrap .set_delete_file_btn { position: absolute; right: 0px; top: 0px; width: 20px; height: 20px; line-height: 20px; text-align: center; background-color: rgba(255,255,255,0.8); border-radius: 10px; padding-top: 2px; }
	#item_group_option .item_group_option_detail_list_wrap .set_write_item_group_option_detail_box .btn_wrap { margin-top: 10px; border-top: 1px dashed #999; padding-top: 10px; }
	#item_group_option .item_group_option_detail_list_wrap .set_write_item_group_option_detail_box .btn_wrap button { width: 100%; height: 40px; }
	.stop-scrolling { height: 100%; overflow: hidden; }

	@media screen and (min-width: 400px) {
		/* show button name */
		#item_group_option button>span { display: inline; }
	}
</style>

<div class="bjj_top_menu">
    <?php include "../include/bjj_shop_back_btn.php"; ?>
    <div class="bjj_top_title"><p>상품 내역</p></div>
	<?php if($r_seq != ""){ ?><div class="bjj_review"><button type="button" class="review_list_btn">후기관리 <span><i class="fas fa-user-edit"></i></span></button></div><?php } ?>
</div>

<div id="item_write">
	<div class="item_write_wrap">
		<form id="item_form">
			<input type="hidden" name="il_seq" value="<?=$r_seq ?>" />
			<input type="hidden" name="is_shop" value="2" />
			<ul>
				<li>
					<div class="info">카테고리 <span>*</span></div>
					<div class="value">
						<button type="button" class="cate_write_btn">입력/수정</button>
						<div class="category_list">선택된 카테고리가 없습니다.</div>
						<input type="hidden" name="ic_seq" value="" />
					</div>
				</li>
				<li>
					<div class="info">상품번호 <span>*</span></div>
					<div class="value">
						<input type="text" name="product_no" value="" />
					</div>
					<div class="msg">
						* 주의!<br/>
						상품 결제 후 번호 변경시 상품이 정상적으로 노출되지 않으니 되도록이면 처음 설정 후 바뀌지 않도록 주의 바랍니다.<br/>
						상품번호 규칙 : <br/>
						 - PE-[카테고리]-[해당반려동물구분][생성번호2자리] <br/>
						 >> 카테고리(D-device, C-cloth, ..)<br/>
						 >> 해당반려동물구분(A-all, C-cat, D-dog)<br/>
						 - ex)PE-C-A01
					</div>
				</li>
				<li>
					<div class="info">상품명 <span>*</span></div>
					<div class="value">
						<input type="text" name="product_name" value="" />
					</div>
				</li>
				<li>
					<div class="info">상품이미지</div>
					<div class="value">
						<button type="button" class="upload_img_btn">사진등록</button>
						<div class="img_list">업로드된 이미지가 없습니다.</div>
						<input type="hidden" name="product_img" value="" />
					</div>
				</li>
				<li>
					<div class="info">공급사</div>
					<div class="value">
						<select class="ip_seq">
							<option value="">선택</option>
						</select>
						<input type="hidden" name="ip_seq" value="" />
						<input type="text" name="supplier" value="" />
					</div>
				</li>
				<li>
					<div class="info">상품설명</div>
					<div class="value">
						<input type="text" name="product_comment" value="" />
					</div>
				</li>
				<li>
					<div class="info">상품상세</div>
					<div class="value">
						<textarea name="product_detail"></textarea>
					</div>
				</li>
				<li>
					<div class="info">공급금액</div>
					<div class="value">
						<input type="text" name="supplier_price" class="amount" value="" />
					</div>
				</li>
				<li>
					<div class="info">상품금액 <span>*</span></div>
					<div class="value">
						<input type="text" name="product_price" class="amount" value="" />
					</div>
				</li>
				<li>
					<div class="info">할인금액 <span>*</span></div>
					<div class="value">
						<select class="sale_percent" dir="rtl">
						<?php for($_i = 0; $_i <= 100; $_i++){ ?>
							<option value="<?=$_i?>"><?=$_i?>%</option>
						<?php } ?>
						</select>
						<div class="sale_price_txt" style="padding: 10px 5px;">(-) 0</div>
					</div>
				<li>
					<div class="info">최종판매가 <span>*</span></div>
					<div class="value">
						<input type="text" name="sale_price" class="amount" value="" />
					</div>
				</li>
				<li>
					<div class="info"><span style="line-height: 24px; color: #000;">판매촉진<br/>노출선택</span></div>
					<div class="value">
						<span>
							<input type="checkbox" id="is_view_main_1" name="is_view_main_1" value="1" />
							<label for="is_view_main_1">메인MD</label>
						</span>
						<span>
							<input type="checkbox" id="is_view_main_2" name="is_view_main_2" value="1" />
							<label for="is_view_main_2">메인NEW</label>
						</span>
						<span>
							<input type="checkbox" id="is_view_main_3" name="is_view_main_3" value="1" />
							<label for="is_view_main_3">쇼핑MD(강아지)</label>
						</span>
						<span>
							<input type="checkbox" id="is_view_main_4" name="is_view_main_4" value="1" />
							<label for="is_view_main_4">쇼핑NEW(강아지)</label>
						</span>
						<span>
							<input type="checkbox" id="is_view_main_5" name="is_view_main_5" value="1" />
							<label for="is_view_main_5">베스트(강아지)</label>
						</span>
						<span>
							<input type="checkbox" id="is_view_main_6" name="is_view_main_6" value="1" />
							<label for="is_view_main_6">인기</label>
						</span>
						<span>
							<input type="checkbox" id="is_view_main_7" name="is_view_main_7" value="1" />
							<label for="is_view_main_7">쇼핑MD(고양이)</label>
						</span>
						<span>
							<input type="checkbox" id="is_view_main_8" name="is_view_main_8" value="1" />
							<label for="is_view_main_8">쇼핑NEW(고양이)</label>
						</span>
						<span>
							<input type="checkbox" id="is_view_main_9" name="is_view_main_9" value="1" />
							<label for="is_view_main_9">베스트(고양이)</label>
					</div>
				</li>
				<li class="md_list">
					<ul>
						<li>
							<div class="info">MD 이름</div>
							<div class="value">MD<input type="text" name="md_name" value="" placeholder="써니" /></div>
						</li>
						<li>
							<div class="info">MD 아이콘(url)</div>
							<div class="value"><input type="text" name="md_icon" value="" placeholder="/pet/images/icon.png" /></div>
						</li>
						<li>
							<div class="info">MD 한줄평</div>
							<div class="value"><input type="text" name="md_comment" value="" placeholder="제품이 맛있어요!" /></div>
						</li>
					</ul>
				</li>
				<li>
					<div class="info">품절여부</div>
					<div class="value">
						<span>
							<input type="checkbox" id="is_soldout" name="is_soldout" value="2" />
							<label for="is_soldout">품절</label>
						</span>
					</div>
				</li>
				<li>
					<div class="info">노출여부</div>
					<div class="value">
						<span>
							<input type="checkbox" id="is_view" name="is_view" value="1" />
							<label for="is_view">노출</label>
						</span>
					</div>
				</li>
				<li>
					<div class="info">포인트사용</div>
					<div class="value">
						<span>
							<input type="checkbox" id="is_use_point" name="is_use_point" value="1" checked />
							<label for="is_use_point">사용</label>
						</span>
					</div>
				</li>
				<li>
					<div class="info">상품그룹옵션</div>
					<div class="value">
						<?php if($r_seq != ""){ ?>
						<span>
							<button type="button" class="item_group_option_dialog_btn">그룹옵션설정</button>
						</span>
						<?php } ?>
						<span>
							<input type="checkbox" id="is_use_group_option" name="is_use_group_option" value="1" />
							<label for="is_use_group_option">사용</label>
						</span>
					</div>
					<div class="msg">
						* 주의!<br/>
						상품 그룹옵션 변경 전에 본 상품을 저장 후 추가하세요.
					</div>
				</li>
				<li>
					<div class="info">상품옵션</div>
					<div class="value">
						<span>
							<input type="checkbox" id="is_use_option" name="is_use_option" value="1" />
							<label for="is_use_option">사용</label>
						</span>
					</div>
					<div class="msg">
						* 주의!<br/>
						상품 옵션 변경 전에 본 상품을 저장 후 추가하세요.<br/>
						상품 옵션은 한번에 한 옵션씩 수정한 후 저장하세요.
					</div>
				</li>
			</ul>
		</form>
		<div class="btn_wrap">
			<button type="button" class="submit_btn on">상품 <?=($r_seq == "")? "입력" : "수정"; ?></button>
		</div>
		<ul>
			<li class="line">
				<div>옵션리스트</div>
				<div class="option_use_wrap">
					<div class="option_list_wrap">
					</div>
					<button type="button" class="add_option_btn"><i class="fas fa-plus"></i> 옵션추가</button>
				</div>
			</li>
		</ul>
		<ul>
			<li class="line">
				<div class="curation_wrap">
					<div class="title">큐레이션</div>
					<div class="value">
						<span>
							<input type="checkbox" id="curation_1" name="curation[]" value="개" />
							<label for="curation_1">개</label>
						</span>
						<span>
							<input type="checkbox" id="curation_2" name="curation[]" value="고양이" />
							<label for="curation_2">고양이</label>
						</span>
					</div>
				</div>
			</li>
		</ul>
		<div class="btn_wrap">
			<button type="button" class="set_delete_item_list_btn">상품 삭제</button>
		</div>
	</div>
</div>

<div id="category_wrap" style="display:none;">
	<select class="cate_1">
		<option value="">선택</option>
	</select>
	<select class="cate_2">
		<option value="">선택</option>
	</select>
	<select class="cate_3">
		<option value="">선택</option>
	</select>
	<input type="hidden" class="ic_seq_list" value="" />
	<button type="button" class="selected_cate_btn">선택</button>
	<div class="category_list"></div>
</div>

<?php // 그룹옵션용 ?>
<div id="item_group_option"></div>

<?php // 파일업로드용 ?>
<input id="fileupload" type="file" name="files[]" data-url="<?=$mainpage_directory?>/fileupload_ajax.php" multiple style="display: none;" />

<script>
var option_cnt = 0; // default_option
var il_seq = "<?=$r_seq ?>";
var img_list = [];
var old_product_no = "";
var upload_flag = ""; // upload_file_target

// 그룹옵션
var $item_group_option = $("#item_group_option");
var option_product_no = "";
var igod_seq = ""; // image_update용
var option_img_list = []; // image_update용

$(document).ready(function() {
	$("#loading").css("align-items", "center").css("justify-content", "center");
	$("#loading").html("<span style='color: #fff; max-width: 50%; text-align:center;'><img src='/pet/images/logo_loading0.gif' style='width: 100px; height: 100px;'/><br/><span style='color:#f5bf2e;'>반</span>려생활의 단<span style='color:#f5bf2e;'>짝</span></span>");
	get_partner_list();

	$('#fileupload').fileupload({
		formData: {
			mode: "upload_img",
			target: "tb_item_list.product_img",
			folder: "shop"
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

					if(upload_flag == "item_list"){
						$('#item_write #item_form .img_list').append(html);
						$("#item_write #item_form input[name='product_img']").val(img_list.join(","));
						get_file_list(img_list);
					}else if(upload_flag == "item_group_option"){
						$item_group_option.find(".set_write_item_group_option_detail_box input[name='image']").val(option_img_list.join(","));
						get_file_list_option(option_img_list);
					}
				});

				if(upload_flag == "item_list"){
					// item product_img update
					if(il_seq != ""){
						$.ajax({
							url: '<?=$item_directory ?>/item_list_ajax.php',
							data: {
								mode: "set_update_item_img",
								il_seq: il_seq,
								product_img: img_list.join(",")
							},
							type: 'POST',
							dataType: 'JSON',
							success: function(data) {
								if(data.code == "000000"){
									console.log("product_img update OK");
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
				}else if(upload_flag == "item_group_option"){
					// item product_img update
					if(igod_seq != ""){
						$.ajax({
							url: '../test/test_item_list_ajax.php',
							data: {
								mode: "set_update_item_group_option_detail",
								igod_seq: igod_seq,
								image: option_img_list.join(",")
							},
							type: 'POST',
							dataType: 'JSON',
							success: function(data) {
								if(data.code == "000000"){
									console.log("product_img update OK");
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

	if(il_seq != ""){
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode: "get_item",
				il_seq: il_seq
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log("asd"+data.data);
					$.each(data.data, function(i, v){
						var sale_percent = Math.round(100 - (v.sale_price / v.product_price * 100));
						var sale_price_txt = v.product_price - v.sale_price;
						$(".bjj_review .review_list_btn").data("product_no", v.product_no);
						$("#item_write #item_form input[name='ic_seq']").val(v.ic_seq);
						$("#item_write #item_form input[name='product_no']").val(v.product_no);
						$("#item_write #item_form input[name='product_name']").val(v.product_name);
						$("#item_write #item_form input[name='product_img']").val(v.product_img);
						img_list = v.product_img.split(",");
						$("#item_write #item_form input[name='ip_seq']").val(v.ip_seq);
						$("#item_write #item_form input[name='supplier']").val(v.supplier);
						$("#item_write #item_form input[name='product_comment']").val(v.product_comment);
						$("#item_write #item_form textarea[name='product_detail']").val(v.product_detail);
						$("#item_write #item_form input[name='supplier_price']").val(v.supplier_price);
						$("#item_write #item_form input[name='product_price']").val(v.product_price);
						$("#item_write #item_form input[name='sale_price']").val(v.sale_price);
						$("#item_write #item_form select.sale_percent").val(sale_percent);
						$("#item_write #item_form .sale_price_txt").text('(-) '+sale_price_txt);
						if(v.is_view_main_1 == "1"){
							$("#item_write #item_form input[name='is_view_main_1']").prop("checked", true);
							$(document).find("#item_write li.md_list").addClass("on");
							$("#item_write #item_form input[name='md_name']").val(v.md_name);
							$("#item_write #item_form input[name='md_icon']").val(v.md_icon);
							$("#item_write #item_form input[name='md_comment']").val(v.md_comment);
						}
						if(v.is_view_main_2 == "1"){
							$("#item_write #item_form input[name='is_view_main_2']").prop("checked", true);
						}
						if(v.is_view_main_3 == "1"){
							$("#item_write #item_form input[name='is_view_main_3']").prop("checked", true);
							$(document).find("#item_write li.md_list").addClass("on");
							$("#item_write #item_form input[name='md_name']").val(v.md_name);
							$("#item_write #item_form input[name='md_icon']").val(v.md_icon);
							$("#item_write #item_form input[name='md_comment']").val(v.md_comment);
						}
						if(v.is_view_main_4 == "1"){
							$("#item_write #item_form input[name='is_view_main_4']").prop("checked", true);
						}
						if(v.is_view_main_5 == "1"){
							$("#item_write #item_form input[name='is_view_main_5']").prop("checked", true);
						}
						if(v.is_view_main_6 == "1"){
							$("#item_write #item_form input[name='is_view_main_6']").prop("checked", true);
						}
						if(v.is_view_main_7 == "1"){
							$("#item_write #item_form input[name='is_view_main_7']").prop("checked", true);
						}
						if(v.is_view_main_8 == "1"){
							$("#item_write #item_form input[name='is_view_main_8']").prop("checked", true);
						}
						if(v.is_view_main_9 == "1"){
							$("#item_write #item_form input[name='is_view_main_9']").prop("checked", true);
						}

						if(v.is_soldout == "2"){
							$("#item_write #item_form input[name='is_soldout']").prop("checked", true);
						}
						if(v.is_view == "1"){
							$("#item_write #item_form input[name='is_view']").prop("checked", true);
						}
						if(v.is_use_point == "1"){
							$("#item_write #item_form input[name='is_use_point']").prop("checked", true);
						}else{
							$("#item_write #item_form input[name='is_use_point']").prop("checked", false);
						}
						if(v.is_use_option == "1"){
							$("#item_write #item_form input[name='is_use_option']").prop("checked", true);
							$("#item_write .option_use_wrap").addClass("on");
						}
						if(v.is_use_group_option == "1"){
							$("#item_write #item_form input[name='is_use_group_option']").prop("checked", true);
						}
						old_product_no = v.product_no; // 변경 체크를 위해 추가
						option_product_no = v.product_no; // 그룹옵션용
					});
					console.log(img_list);
					get_file_list(img_list);

					var sale_price = $("#item_write #item_form input[name='sale_price']").val();
					var product_price = $("#item_write #item_form input[name='product_price']").val();
					var sale_percent = percent_calculator(sale_price, product_price);
					$("#item_write #item_form .sale_price_text").text(product_price - sale_price);
					$("#item_write #item_form input[name='sale_price']").siblings("select.sale_percent").val(100 - sale_percent);

					// item_option
					$.ajax({
						url: '<?=$item_directory ?>/item_list_ajax.php',
						data: {
							mode: "get_item_option",
							il_seq: il_seq
						},
						type: 'POST',
						dataType: 'JSON',
						success: function(data) {
							if(data.code == "000000"){
								console.log(data.data);
								if(data.data && data.data != ""){
									$.each(data.data, function(i, v){
										option_html(v);
									});
								}else{
									console.log("no_option");
								}

								selected_category_list();
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
		selected_category_list();
	}
});

$(document).on("click", ".review_list_btn", function(){
	location.href = "item_review_list.php?product_no="+$(this).data("product_no")+"&backurl=<?= urlencode($_SERVER['REQUEST_URI']) ?>";
});

$(document).on("click", "#item_write input[name='is_view_main_1']", function(){
	var main_1 = $("#item_write input[name='is_view_main_1']").is(":checked");
	var main_3 = $("#item_write input[name='is_view_main_3']").is(":checked");
	if(main_1 == true || main_3 == true){
		$(document).find("#item_write li.md_list").addClass("on");
	}else{
		$(document).find("#item_write li.md_list").removeClass("on");
	}
});

$(document).on("click", "#item_write input[name='is_view_main_3']", function(){
	var main_1 = $("#item_write input[name='is_view_main_1']").is(":checked");
	var main_3 = $("#item_write input[name='is_view_main_3']").is(":checked");
	if(main_1 == true || main_3 == true){
		$(document).find("#item_write li.md_list").addClass("on");
	}else{
		$(document).find("#item_write li.md_list").removeClass("on");
	}
});

$(document).on("change", "#item_form ul li select.ip_seq", function(){
	let _ip_seq = $(this).children("option:selected").val();
	let _company_name = $(this).children("option:selected").data("company_name");

	console.log(_ip_seq, _company_name);
	$("#item_form ul li input[name='ip_seq']").val(_ip_seq);
	$("#item_form ul li input[name='supplier']").val(_company_name);
});

function get_partner_list(){
	return new Promise(function(resolve, reject) {
		$.ajax({
			url: '../partner/partner_ajax.php',
			data: {
				mode: "get_partner_list"
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					console.log(data.data);
					var html = '';

					if(data.data && data.data.length > 0){
						$.each(data.data, function(i, v){
							html += '<option value="'+v.ip_seq+'" data-company_name="'+v.company_name+'">'+v.company_name+'</option>';
						});
						$("#item_form ul li select.ip_seq").append(html);
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
					$('#item_write #item_form .img_list').html(html);
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
		$('#item_write #item_form .img_list').html('업로드된 이미지가 없습니다.');
	}
}

$(document).on("click", "#item_write #item_form .img_list .set_delete_file_btn", function(){
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

					// item product_img update
					$.ajax({
						url: '<?=$item_directory ?>/item_list_ajax.php',
						data: {
							mode: "set_update_item_img",
							il_seq: il_seq,
							product_img: img_list.join(",")
						},
						type: 'POST',
						dataType: 'JSON',
						success: function(data) {
							if(data.code == "000000"){
								console.log("product_img update OK");
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

$(document).on("click", "#item_write .upload_img_btn", function(){
	upload_flag = "item_list";
	$('#fileupload').bind('fileuploadsubmit', function (e, data) {
		// selecting each input with name "doctype" property checked true
		data.formData = {
			mode: "upload_img",
			target: "tb_item_list.product_img",
			folder: "shop"
		};
	});
	$("#fileupload").trigger("click");
});

$(document).on("click", "#item_write .option_update_btn", function(){
	var _this = $(this);
	var cnt = _this.data("cnt"); // 옵션번호
	var io_seq = $("form#item_option_"+cnt+" input[name='io_seq']").val(); // 옵션번호(DB)
	var data = $("#item_option_"+cnt).serialize();
	$("#item_option_"+cnt+" input[type='checkbox']").each(function(i, v){
		if($(this).is(":checked") == false){
			if($(this).attr("name") == "is_soldout"){
				data += "&"+v.name+"=1";
			}else if($(this).attr("name") == "is_view"){
				data += "&"+v.name+"=2";
			}else if($(this).attr("name") == "is_free_shipping"){
				data += "&"+v.name+"=2";
			}
		}
	});
	data += (io_seq == "")? "&mode=set_insert_item_option" : "&mode=set_update_item_option";
	data += (il_seq != "")? "&il_seq="+il_seq : "";
	data += ($("#item_write input[name='product_no']").val() != "")? "&product_no="+$("#item_write input[name='product_no']").val() : "";

	console.log(data);
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: data,
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				if(io_seq == ""){
					$.MessageBox("등록되었습니다.");
				}else{
					$.MessageBox("수정되었습니다.");
				}
				$("#item_write .option_use_wrap .option_list_wrap").html(''); // option_init
				option_cnt = 0;

				$.ajax({
					url: '<?=$item_directory ?>/item_list_ajax.php',
					data: {
						mode: "get_item_option",
						il_seq: il_seq
					},
					type: 'POST',
					dataType: 'JSON',
					success: function(data) {
						if(data.code == "000000"){
							console.log(data.data);
							if(data.data && data.data != ""){
								$.each(data.data, function(i, v){
									option_html(v);
								});
								$("#item_write #is_use_option").prop("checked", true);
								$("#item_write .option_use_wrap").addClass("on");
							}else{
								console.log("no_option");
							}

							selected_category_list();
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

$(document).on("click", "#item_write #is_use_option", function(){
	if($(this).is(":checked") == true){
		$("#item_write .option_use_wrap").addClass("on");
	}else{
		$("#item_write .option_use_wrap").removeClass("on");
	}
});

$(document).on("keyup", "#item_write input[type='text'].amount", function(){
	if($(this).val() == ""){
		$(this).val("0");
	}
});

// price + sale_price = percent
function percent_calculator(sale_price, product_price){
	if(sale_price > 0 && product_price > 0){
		var sale_percent = parseInt(sale_price / product_price * 100);
		if(sale_percent > 0 && sale_percent <= 100){
			return sale_percent;
		}else{
			return "0";
		}
	}else{
		return "0";
	}
}

// price + percent = sale_price
function percent_calculator2(percent, product_price){
	if(product_price > 0 && percent > 0){
		var sale_price = product_price * percent / 100;
		if(sale_price > 0){
			return sale_price;
		}else{
			return "0";
		}
	}else{
		return "0";
	}
}

$(document).on("keyup", "#item_write #item_form input[name='product_price']", function(){
	console.log("1");
	var price = $(this).val();
	var percent = $("#item_write #item_form .sale_percent option:selected").val();
	var sale_price = percent_calculator2(percent, price);
	$("#item_write #item_form .sale_price_txt").text("(-) "+sale_price);
	$("#item_write #item_form input[name='sale_price']").val(price - sale_price);
});

$(document).on("change", "#item_write #item_form .sale_percent", function(){
	var price = $("#item_write #item_form input[name='product_price']").val();
	var percent = $(this).children("option:selected").val();
	var sale_price = percent_calculator2(percent, price);
	$("#item_write #item_form .sale_price_txt").text("(-) "+sale_price);
	$("#item_write #item_form input[name='sale_price']").val(price - sale_price);
});

$(document).on("keyup", "#item_write #item_form input[name='sale_price']", function(){
	console.log("!");
	var sale_price = $(this).val();
	var percent = $("#item_write #item_form .sale_percent option:selected").val();
	var price = $("#item_write #item_form input[name='product_price']").val();
	var sale_percent = percent_calculator(sale_price, price);
	var sale_price = percent_calculator2(100 - sale_percent, price);
	$("#item_write #item_form .sale_price_txt").text("(-) "+sale_price);
	$("#item_write #item_form input[name='sale_price']").siblings("select.sale_percent").val(100 - sale_percent);
});

$(document).on("keyup", "#item_write .option_list_wrap input[name='option_price']", function(){
	var target = $(this).parent().parent().parent().closest("form").attr("id");
	var price = $(this).val();
	var percent = $("#item_write #"+target+" .sale_percent option:selected").val();
	var sale_price = percent_calculator2(percent, price);
	$("#item_write #"+target+" .sale_price_txt").text("(-) "+sale_price);
	$("#item_write #"+target+" input[name='sale_price']").val(price - sale_price);
});

$(document).on("change", "#item_write .option_list_wrap .sale_percent", function(){
	var target = $(this).parent().parent().parent().closest("form").attr("id");
	var price = $("#item_write #"+target+" input[name='option_price']").val();
	var percent = $(this).children("option:selected").val();
	var sale_price = percent_calculator2(percent, price);
	$("#item_write #"+target+" .sale_price_txt").text("(-) "+sale_price);
	$("#item_write #"+target+" input[name='sale_price']").val(price - sale_price);
});

$(document).on("keyup", "#item_write .option_list_wrap input[name='sale_price']", function(){
	var target = $(this).parent().parent().parent().closest("form").attr("id");
	var sale_price = $(this).val();
	var price = $("#item_write #"+target+" input[name='option_price']").val();
	var percent = $("#item_write #"+target+" .sale_percent option:selected").val();
	var sale_percent = percent_calculator(sale_price, price);
	var sale_price = percent_calculator2(100 - sale_percent, price);
	$("#item_write #"+target+" .sale_price_txt").text("(-) "+sale_price);
	$("#item_write #"+target+" select.sale_percent").val(100 - sale_percent);
});

$(document).on("click", "#item_write .del_option_btn", function(){
	console.log("!");
	var cnt = $(this).data("cnt");
	var io_seq = $("#item_option_wrap_"+cnt+" input[name='io_seq']").val();
	$.MessageBox({
		buttonFail: "아니오",
		buttonDone: "예",
		message: "<center><font style='font-size:15px;font-weight:bold;'>해당 옵션을 삭제 하시겠습니까?</font></center>"
	}).done(function() {
		if(io_seq != ""){
			//ajax
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode: "set_delete_item_option",
					io_seq: io_seq,
					user_id: "<?=$user_id ?>",
					delete_txt: "직접 삭제"
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						$("#item_option_wrap_"+cnt).remove();
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
			$("#item_option_wrap_"+cnt).remove();
		}
	});

});

$(document).on("click", "#item_write .add_option_btn", function(){
	if(il_seq != ""){
		option_html('');
	}else{
		$.MessageBox("상품 생성 후에 옵션 추가가 가능합니다.");
	}
});

function option_html(data){
	var html = '';
	var io_seq = (data.io_seq && data.io_seq != "")? data.io_seq : "";
	var option_name = (data.option_name && data.option_name != "")? data.option_name : "";
	var supplier_price = (data.supplier_price && data.supplier_price != "")? data.supplier_price : "0";
	var option_price = (data.option_price && data.option_price != "")? data.option_price : "0";
	var sale_price = (data.sale_price && data.sale_price != "")? data.sale_price : "0";
	var is_soldout = (data.is_soldout == "2")? " checked " : "";
	var is_view = (data.is_view == "1")? " checked " : "";
	var is_use = (data.is_use == "1")? " checked " : "";
	var is_free_shipping = (data.is_free_shipping == "1")? " checked " : "";
	var btn_txt = (data == "")? "옵션 입력" : "옵션 수정";
	var sale_percent = percent_calculator(sale_price, option_price);

	html += '<div id="item_option_wrap_'+option_cnt+'" class="option_box">';
	html += '	<form id="item_option_'+option_cnt+'" data-io_seq="'+io_seq+'">';
	html += '		<input type="hidden" name="io_seq" value="'+io_seq+'" />';
	html += '		<div class="title">';
	html += '			<input type="text" name="option_name" value="'+option_name+'" placeholder="옵션'+option_cnt+'" />';
	if(option_cnt != 0){
		html += '			<button type="button" class="del_option_btn" data-cnt="'+option_cnt+'"><i class="fas fa-times"></i></button>';
	}
	html += '		</div>';
	html += '		<ul>';
	html += '			<li>';
	html += '				<div class="info">공급금액</div>';
	html += '				<div class="value">';
	html += '					<input type="text" name="supplier_price" class="amount" value="'+supplier_price+'" />';
	html += '				</div>';
	html += '			</li>';
	html += '			<li>';
	html += '				<div class="info">상품금액 <span>*</span></div>';
	html += '				<div class="value">';
	html += '					<input type="text" name="option_price" class="amount" value="'+option_price+'" />';
	html += '				</div>';
	html += '			</li>';
	html += '			<li>';
	html += '				<div class="info">할인금액 <span>*</span></div>';
	html += '				<div class="value">';
	html += '					<select class="sale_percent" dir="rtl">';
	for(var _i = 0; _i <= 100; _i++){
		var is_selected = (_i == (100 - sale_percent))? " selected " : "";
		html += '						<option value="'+_i+'" '+is_selected+'>'+_i+'%</option>';
	}
	html += '					</select>';
	html += '					<div class="sale_price_txt" style="padding: 10px 5px;">(-) '+(option_price - sale_price)+'</div>';
	html += '				</div>';
	html += '			</li>';
	html += '			<li>';
	html += '				<div class="info">최종판매가 <span>*</span></div>';
	html += '				<div class="value">';
	html += '					<input type="text" name="sale_price" class="amount" value="'+sale_price+'" />';
	html += '				</div>';
	html += '			</li>';
	html += '			<li>';
	html += '				<div class="info">품절여부</div>';
	html += '				<div class="value">';
	html += '					<span>';
	html += '						<input type="checkbox" id="is_soldout'+option_cnt+'" name="is_soldout" value="2" '+is_soldout+' />';
	html += '						<label for="is_soldout'+option_cnt+'">품절</label>';
	html += '					</span>';
	html += '				</div>';
	html += '			</li>';
	html += '			<li>';
	html += '				<div class="info">노출여부</div>';
	html += '				<div class="value">';
	html += '					<span>';
	html += '						<input type="checkbox" id="is_view'+option_cnt+'" name="is_view" value="1" '+is_view+' />';
	html += '						<label for="is_view'+option_cnt+'">노출</label>';
	html += '					</span>';
	html += '				</div>';
	html += '			</li>';
	html += '			<li>';
	html += '				<div class="info">배송비여부</div>';
	html += '				<div class="value">';
	html += '					<span>';
	html += '						<input type="checkbox" id="is_free_shipping'+option_cnt+'" name="is_free_shipping" value="1" '+is_free_shipping+' />';
	html += '						<label for="is_free_shipping'+option_cnt+'">무료</label>';
	html += '					</span>';
	html += '				</div>';
	html += '			</li>';
	html += '		</ul>';
	html += '		<div class="option_button_wrap">';
	html += '			<button type="button" class="option_update_btn" data-cnt="'+option_cnt+'">'+btn_txt+'</button>';
	html += '		</div>';
	html += '	</form>';
	html += '</div>';
	option_cnt++;
	$("#item_write .option_use_wrap .option_list_wrap").append(html);
}

$(document).on("click", "#item_write .submit_btn", function(){
	var data = $("#item_form").serialize();
	var product_no = $("#item_form input[name='product_no']").val();

	$("#item_write #item_form input[type='checkbox']").each(function(i, v){
		if($(this).is(":checked") == false){
			if($(this).attr("name") == "is_soldout"){
				data += "&"+v.name+"=1";
			}else if($(this).attr("name") == "is_use_point"){
				data += "&"+v.name+"=2";
			}else if($(this).attr("name") == "is_view"){
				data += "&"+v.name+"=2";
			}else if($(this).attr("name") == "is_use_option"){
				data += "&"+v.name+"=2";
			}else if($(this).attr("name") == "is_use_group_option"){
				data += "&"+v.name+"=2";
			}else if($(this).attr("name") == "is_view_main_1"){
				data += "&"+v.name+"=2";
			}else if($(this).attr("name") == "is_view_main_2"){
				data += "&"+v.name+"=2";
			}else if($(this).attr("name") == "is_view_main_3"){
				data += "&"+v.name+"=2";
			}else if($(this).attr("name") == "is_view_main_4"){
				data += "&"+v.name+"=2";
			}else if($(this).attr("name") == "is_view_main_5"){
				data += "&"+v.name+"=2";
			}else if($(this).attr("name") == "is_view_main_6"){
				data += "&"+v.name+"=2";
			}else if($(this).attr("name") == "is_view_main_7"){
				data += "&"+v.name+"=2";
			}else if($(this).attr("name") == "is_view_main_8"){
				data += "&"+v.name+"=2";
			}else if($(this).attr("name") == "is_view_main_9"){
				data += "&"+v.name+"=2";
			}

		}
	});
	if($("#item_write input[name='il_seq']").val() != ""){
		data += "&mode=set_update_item";
	}else{
		data += "&mode=set_insert_item";
	}
	console.log(data);
	
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode: "get_item_product_no_chk",
			product_no: product_no,
			old_product_no: old_product_no
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(json) {
			if(json.code == "000000"){
				if(json.data == 0){
					$.ajax({
						url: '<?=$item_directory ?>/item_list_ajax.php',
						data: data,
						type: 'POST',
						dataType: 'JSON',
						success: function(data) {
							if(data.code == "000000"){
								// 옵션 등록/수정 추가 필요
								//alert("등록되었습니다(아님)");
								if($("#item_write input[name='il_seq']").val() == ""){
									$.MessageBox("등록되었습니다.");
								}else{
									$.MessageBox("수정되었습니다.");
								}
								//location.href = "<?=$admin_directory?>/item_list.php";
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
					$.MessageBox("이미 생성된 상품번호입니다.");
				}
			}else{
				alert(json.data+"("+json.code+")");
				console.log(json.data);
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

$(document).on("keyup", "#item_write input[name='product_no']", function(){
	product_no_chk();
});

function product_no_chk(){
	var product_no = $("#item_form input[name='product_no']").val();

	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode: "get_item_product_no_chk",
			product_no: product_no,
			old_product_no: old_product_no
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(json) {
			if(json.code == "000000"){
				if(json.data == 0){
					$("#item_form input[name='product_no']").removeClass("err");
				}else{
					$("#item_form input[name='product_no']").addClass("err");
				}
			}else{
				alert(json.data+"("+json.code+")");
				console.log(json.data);
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


$(document).on("click", "#item_write .cate_write_btn", function(){
	$("#category_wrap").dialog({
		modal: true,
		title: '카테고리 변경',
		autoOpen: true,
		maxWidth: "96%",
		minHeight: "400",
		width: "96%",
		height: "400",
		autoSize: true,
		resizable: false,
		draggable: false,
		open: function(event, ui) {
			// to do something...
			$.ajax({
				url: '<?=$item_directory ?>/item_list_ajax.php',
				data: {
					mode : "get_cate1"
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						var html = '';
						html += '<option value="">선택</option>';
						$.each(data.data, function(i, v){			
							html += '<option value="'+v.ic_seq+'" data-path="'+v.node_path+'">'+v.cate_name+'</option>';
						});
						$("#category_wrap .cate_1").html('').html(html);
	
						selected_category_list();
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
		},
		close: function() {
			// to do something...
			$("#category_wrap .cate_1").html('<option value="">선택</option>');
			$("#category_wrap .cate_2").html('<option value="">선택</option>');
			$("#category_wrap .cate_3").html('<option value="">선택</option>');
			$("#category_wrap .ic_seq_list").html('');
		}
	});
});

function selected_category_list(){
	// 현재 카테고리 불러오기
	var cate = $("#item_write input[name='ic_seq']").val();
	if(cate && cate != ""){
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "get_cate_list",
				cate : cate
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					var html = '';
					$.each(data.data, function(i, v){			
						html += '<div>'+v.cate_name+'<span class="del_category" data-id="'+v.ic_seq+'"><i class="fas fa-times"></i></span></div>';
					});
					$("#category_wrap .category_list").html('').html(html);
					$("#item_write .category_list").html('').html(html);
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
		html += '<div>선택된 카테고리가 없습니다.</div>';
		$("#category_wrap .category_list").html('').html(html);
		$("#item_write .category_list").html('').html(html);
	}
}
$(document).on("click", "#item_write .del_category, #category_wrap .del_category", function(){
	var ic_seq = $(this).data("id");
	var ic_seq_arr = $("#item_write input[name='ic_seq']").val().split(",");
	$.each(ic_seq_arr, function(i, v){
		if(v == ic_seq){
			ic_seq_arr.splice(i, 1);
		}
	});
	$("#item_write input[name='ic_seq']").val(ic_seq_arr);
	selected_category_list();
});

$(document).on("change", "#category_wrap .cate_1", function(){
	$("#category_wrap .ic_seq_list").val($(this).children("option:selected").val());
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_cate2",
			cate1 : $(this).children("option:selected").val()
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				var html = '';
				html += '<option value="">선택</option>';
				$.each(data.data, function(i, v){			
					html += '<option value="'+v.ic_seq+'" data-path="'+v.node_path+'">'+v.cate_name+'</option>';
				});
				$("#category_wrap .cate_2").html('').html(html);
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

$(document).on("change", "#category_wrap .cate_2", function(){
	$("#category_wrap .ic_seq_list").val($(this).children("option:selected").val());
	$.ajax({
		url: '<?=$item_directory ?>/item_list_ajax.php',
		data: {
			mode : "get_cate3",
			cate2 : $(this).children("option:selected").val()
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data) {
			if(data.code == "000000"){
				var html = '';
				html += '<option value="">선택</option>';
				$.each(data.data, function(i, v){			
					html += '<option value="'+v.ic_seq+'" data-path="'+v.node_path+'">'+v.cate_name+'</option>';
				});
				$("#category_wrap .cate_3").html('').html(html);
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

$(document).on("change", "#category_wrap .cate_3", function(){
	$("#category_wrap .ic_seq_list").val($(this).children("option:selected").val());
});

$(document).on("click", "#category_wrap .selected_cate_btn", function(){
	var ic_seq = $("#item_write input[name='ic_seq']").val();
	if(ic_seq != ""){
		ic_seq = ic_seq.split(",");
		ic_seq.push($("#category_wrap .ic_seq_list").val());
		$("#item_write input[name='ic_seq']").val(ic_seq);
	}else{
		$("#item_write input[name='ic_seq']").val($("#category_wrap .ic_seq_list").val());
	}
	selected_category_list();
	$("#category_wrap").dialog("close");
});

$(document).on("click", "#item_write .set_delete_item_list_btn", function(){
	$.MessageBox({
		buttonFail: "아니오",
		buttonDone: "예",
		message: "<center><font style='font-size:15px;font-weight:bold;'>해당 상품을 삭제 하시겠습니까?</font></center>"
	}).done(function() {
		$.ajax({
			url: '<?=$item_directory ?>/item_list_ajax.php',
			data: {
				mode : "set_delete_item",
				il_seq : il_seq,
				user_id : "<?=$user_id ?>",
				delete_txt: "직접 삭제"
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if(data.code == "000000"){
					$.MessageBox({
						buttonDone: "예",
						message: "<center><font style='font-size:15px;font-weight:bold;'>삭제되었습니다.</font></center>"
					}).done(function() {
						location.replace("<?=$admin_directory?>/item_list.php");
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
});

///////////// item_option
$(document).on("click", ".item_group_option_dialog_btn", function(){
	if(option_product_no != ""){
		item_group_option_html();
	}else{
		$.MessageBox("상품을 찾을 수 없습니다. 상품생성 후 입력하세요.");
	}
});

$item_group_option.on("click", ".insert_item_group_option_btn", function(){
	write_item_group_option_html('insert', ''); // add_group
	$item_group_option.find('.group_option').css('background-color', 'inherit');
	$item_group_option.find('.group_option_detail').css('background-color', 'inherit');
	$item_group_option.find('.group_option>.title').show();
	$item_group_option.find('.group_option>.group_fold').css('background-color', '#eee');
	$item_group_option.find('.group_option>.group_fold').css('border', '1px solid #eee');
	$item_group_option.find('.group_option>.group_fold ul').show();
	$item_group_option.find('.set_write_item_group_option_detail_box').remove();
});

$item_group_option.on("click", ".update_item_group_option_btn", function(e){
	e.stopPropagation(); // block bubbling
	var igo_seq = $(this).parent().parent().parent().data("id");
	write_item_group_option_html('update', igo_seq); // upd_group
	$(this).parent().parent().parent().css('background-color', '#f5bf2e');
	$item_group_option.find('.group_option_detail').css('background-color', 'inherit');
	$item_group_option.find('.group_option>.title').show();
	$item_group_option.find('.group_option>.group_fold').css('background-color', '#eee');
	$item_group_option.find('.group_option>.group_fold').css('border', '1px solid #eee');
	$item_group_option.find('.group_option>.group_fold ul').show();
	$item_group_option.find('.set_write_item_group_option_detail_box').remove();
});

$item_group_option.on("click", ".set_delete_item_option_btn", function(e){
	e.stopPropagation(); // block bubbling
	var igo_seq = $(this).parent().parent().parent().data("id");
	console.log(igo_seq);
	$.MessageBox({
		buttonDone: "확인",
		buttonFail: "취소",
		message: "해당 그룹을 삭제 하시겠습니까?"
	}).done(function(){
		$item_group_option.find('.group_option').css('background-color', 'inherit');
		$item_group_option.find('.group_option_detail').css('background-color', 'inherit');
		$item_group_option.find('.group_option>.title').show();
		$item_group_option.find('.group_option>.group_fold').css('background-color', '#eee');
		$item_group_option.find('.group_option>.group_fold').css('border', '1px solid #eee');
		$item_group_option.find('.group_option>.group_fold ul').show();
		set_write_item_group_option('delete', igo_seq); // del_group
	});
});

$item_group_option.on("click", ".update_item_group_option_fold_btn", function(){
	var _this = $(this);
	var igo_seq = $(this).parent().data("id");
	item_group_option_fold(_this, igo_seq); // fold_group
});

$item_group_option.on("click", ".set_update_item_option_btn", function(){
	set_write_item_group_option('update', ''); // update_group
});

$item_group_option.on("click", ".set_insert_item_option_btn", function(){
	set_write_item_group_option('insert', ''); // insert_group
});

$item_group_option.on("click", ".close_item_group_option_box_btn", function(){
	$item_group_option.find('.group_option').css('background-color', 'inherit');
	$item_group_option.find('.group_option_detail').css('background-color', 'inherit');
	$item_group_option.find('.group_option>.title').show();
	$item_group_option.find('.group_option>.group_fold').css('background-color', '#eee');
	$item_group_option.find('.group_option>.group_fold').css('border', '1px solid #eee');
	$item_group_option.find('.group_option>.group_fold ul').show();
	$item_group_option.find('.set_write_item_group_option_box').remove();
});

$item_group_option.on("click", ".insert_item_group_option_detail_btn", function(e){
	e.stopPropagation(); // block bubbling
	var igo_seq = $(this).parent().parent().parent().data("id");
	write_item_group_option_detail_html(igo_seq, ''); // add_group_detail
	$item_group_option.find('.group_option').css('background-color', 'inherit');
	$item_group_option.find('.group_option_detail').css('background-color', 'inherit');
	$item_group_option.find('.group_option>.title').show();
	$item_group_option.find('.group_option>.group_fold').css('background-color', '#eee');
	$item_group_option.find('.group_option>.group_fold').css('border', '1px solid #eee');
	$item_group_option.find('.group_option>.group_fold ul').show();
	$item_group_option.find('.set_write_item_group_option_box').remove();
});

$item_group_option.on("click", ".update_item_group_option_detail_btn", function(){
	var igo_seq = $(this).parent().parent().parent().data("id");
	var igod_seq = $(this).parent().parent().parent().data("id2");
	console.log(igo_seq, igod_seq);
	write_item_group_option_detail_html(igo_seq, igod_seq); // add_group_detail
	$item_group_option.find('.group_option').css('background-color', 'inherit');
	$(this).parent().parent().parent().css('background-color', '#f5bf2e');
	$item_group_option.find('.group_option>.title').show();
	$item_group_option.find('.group_option>.group_fold').css('background-color', '#eee');
	$item_group_option.find('.group_option>.group_fold').css('border', '1px solid #eee');
	$item_group_option.find('.group_option>.group_fold ul').show();
	$item_group_option.find('.set_write_item_group_option_box').remove();
});

$item_group_option.on("click", ".set_delete_item_option_detail_btn", function(){
	var igod_seq = $(this).parent().parent().parent().data("id2");

	console.log(igod_seq);
	$.MessageBox({
		buttonDone: "확인",
		buttonFail: "취소",
		message: "해당 옵션을 삭제 하시겠습니까?"
	}).done(function(){
		$item_group_option.find('.group_option').css('background-color', 'inherit');
		$item_group_option.find('.group_option_detail').css('background-color', 'inherit');
		$item_group_option.find('.group_option>.title').show();
		$item_group_option.find('.group_option>.group_fold').css('background-color', '#eee');
		$item_group_option.find('.group_option>.group_fold').css('border', '1px solid #eee');
		$item_group_option.find('.group_option>.group_fold ul').show();
		set_write_item_group_option_detail('delete', igod_seq); // del_group
	});
});

$item_group_option.on("click", ".set_update_item_option_detail_btn", function(){
	set_write_item_group_option_detail('update'); // update_group
});

$item_group_option.on("click", ".set_insert_item_option_detail_btn", function(){
	set_write_item_group_option_detail('insert'); // insert_group
});

$item_group_option.on("click", ".close_item_group_option_detail_box_btn", function(){
	$item_group_option.find('.group_option').css('background-color', 'inherit');
	$item_group_option.find('.group_option_detail').css('background-color', 'inherit');
	$item_group_option.find('.group_option_detail>.title').show();
	$item_group_option.find('.group_option>.title').show();
	$item_group_option.find('.group_option>.group_fold').css('background-color', '#eee');
	$item_group_option.find('.group_option>.group_fold').css('border', '1px solid #eee');
	$item_group_option.find('.group_option>.group_fold ul').show();
	$item_group_option.find('.set_write_item_group_option_detail_box').remove();
});

$item_group_option.on("click", ".upload_img_btn", function(){
	upload_flag = "item_group_option";
	$('#fileupload').bind('fileuploadsubmit', function (e, data) {
		// selecting each input with name "doctype" property checked true
		data.formData = {
			mode: "upload_img",
			target: "tb_item_group_option_detail.image",
			folder: "shop"
		};
	});
	$("#fileupload").trigger("click");
});

$item_group_option.on("click", ".set_delete_file_btn", function(){
	var f_seq = $(this).data("seq");
	$.MessageBox({
		buttonFail: "아니오",
		buttonDone: "예",
		message: "<center><font style='font-size:15px;font-weight:bold;'>해당 이미지를 삭제 하시겠습니까?</font></center>"
	}).done(function() {
		igod_seq = $item_group_option.find(".set_write_item_group_option_detail_box input[name='igod_seq']").val();
		set_delete_file(f_seq);
	});
});

function item_group_option_fold(target, igo_seq){
	return new Promise(function(resolve, reject) {
		var _target = (target.hasClass("on"))? '1' : '2';

		$.ajax({
			url: '../test/test_item_list_ajax.php',
			data: {
				mode: "set_update_item_group_option",
				is_fold: _target,
				igo_seq: igo_seq
			},
			type: 'POST',
			dataType: 'JSON',
			beforeSend: function(){
				//$("#loading").css("display", "flex");
			},
			success: function(data) {
				//$("#loading").css("display", "none");
				if(data.code == "000000"){
					console.log(data.data);
					if(target.hasClass("on")){
						$item_group_option.find('.group_fold[data-id="'+igo_seq+'"]').removeClass("on");
						target.removeClass("on");
						//target.html('<i class="fas fa-folder"></i>'); // 펼치기
					}else{
						$item_group_option.find('.group_fold[data-id="'+igo_seq+'"]').addClass("on");
						target.addClass("on");
						//target.html('<i class="fas fa-folder-open"></i>'); // 접기
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

function item_group_option_html(){
	return new Promise(function(resolve, reject) {
		$item_group_option.dialog({
			modal: true,
			title: '카테고리 변경',
			autoOpen: true,
			maxWidth: "96%",
			minHeight: $(window).height() - 40,
			width: "96%",
			height: $(window).height() - 40,
			autoSize: true,
			resizable: false,
			draggable: false,
			open: function(event, ui) {
				// to do something...
				$('body').addClass('stop-scrolling');
				item_group_option_refresh_html();

				resolve();
			},
			close: function() {
				$('body').removeClass('stop-scrolling');
				// to do something...
			}
		});
	});
}

function item_group_option_refresh_html(){
	return new Promise(function(resolve, reject) {
		var html = '';

		html += '<div class="item_group_option_wrap">';
		html += '	<div class="title">';
		html += '		상품그룹옵션';
		html += '		<div class="right_menu"><button type="button" class="insert_item_group_option_btn"><i class="fas fa-folder-plus"></i> <span>그룹추가</span></div>'; // 그룹추가
		html += '	</div>';
		html += '	<div class="item_group_option_detail_list_wrap">';
		html += '	</div>';
		html += '</div>';

		$item_group_option.html(html);
		get_item_group_option_list();

		resolve();
	});
}

function set_write_item_group_option_detail(mode, igod_seq){
	return new Promise(function(resolve, reject) {
		var post_data = $item_group_option.find("#set_write_item_group_option_detail_form").serialize();
		post_data += '&product_no='+option_product_no;
		if(mode == "insert"){
			post_data += '&mode=set_insert_item_group_option_detail';
		}else if(mode == "update"){
			post_data += '&mode=set_update_item_group_option_detail';
		}else if(mode == "delete"){
			// init
			post_data = '&mode=set_delete_item_group_option_detail';
			post_data += '&igod_seq='+igod_seq;
			post_data += '&delete_id=<?=$user_id ?>';
			post_data += '&delete_txt=admin.item_write에서 삭제';
		}
		var igo_seq = $item_group_option.find("#set_write_item_group_option_detail_form input[name='igo_seq']").val();
		
		console.log(post_data);
		if(mode != ""){
			$.ajax({
				url: '../test/test_item_list_ajax.php',
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
						if(mode == "insert"){
							$.MessageBox("옵션이 등록되었습니다.");
						}else if(mode == "update"){
							$.MessageBox("옵션이 수정되었습니다.");
						}else if(mode == "delete"){
							$.MessageBox("옵션이 삭제되었습니다.");
						}
						item_group_option_refresh_html();

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
		}else{
			$.MessageBox("잘못된 호출입니다.");
		}
	});
}

function write_item_group_option_detail_html(igo_seq, igod_seq){
	return new Promise(function(resolve, reject) {
		$item_group_option.find('.set_write_item_group_option_detail_box').remove();

		var html = '';

		if(igod_seq != ""){ // update
			$.ajax({
				url: '../test/test_item_list_ajax.php',
				data: {
					mode: "get_item_group_option_detail_list",
					igod_seq: igod_seq
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
						var igod_seq = "";
						
						if(data.data && data.data.length > 0){
							$.each(data.data, function(i, v){
								var is_checked = "";
								igod_seq = v.igod_seq;

								html += '<div class="set_write_item_group_option_detail_box">';
								html += '	<form id="set_write_item_group_option_detail_form">';
								html += '		<input type="hidden" name="igod_seq" value="'+v.igod_seq+'" />';
								html += '		<input type="hidden" name="igo_seq" value="'+igo_seq+'" />';
								html += '		<div class="title">';
								html += '			<span>상품옵션수정</span>';
								html += '			<div class="right_menu"><button type="button" class="close_item_group_option_detail_box_btn"><i class="fas fa-times"></i> <span>닫기</span></button></div>'; // 닫기
								html += '		</div>';
								html += '		<ul>';
								html += '			<li>';
								html += '				<div class="title">상품옵션명</div>';
								html += '				<div>';
								html += '					<input type="text" name="option_name" value="'+v.option_name+'" placeholder="옵션명 입력" />';
								html += '				</div>';
								html += '			</li>';
								html += '			<li>';
								html += '				<div class="title">추가금액</div>';
								html += '				<div>';
								html += '					<input type="number" name="plus_price" value="'+v.plus_price+'" />';
								html += '				</div>';
								html += '			</li>';
								html += '			<li>';
								html += '				<div class="title">옵션이미지</div>';
								html += '				<div>';
								html += '					<input type="hidden" name="image" value="'+v.image+'" />';
								html += '					<button type="button" class="upload_img_btn">사진등록</button>';
								html += '					<div class="img_list"></div>';
								html += '				</div>';
								html += '			</li>';
								html += '		</ul>';
								html += '	</form>';
								html += '	<div class="btn_wrap">';
								html += '		<button type="button" class="set_update_item_option_detail_btn"><i class="far fa-edit"></i> 옵션수정</button>';
								html += '	</div>';
								html += '</div>';								
							});
						}

						$item_group_option.find('.item_group_option_detail_list_wrap .group_option[data-id="'+igo_seq+'"] .group_option_detail[data-id2="'+igod_seq+'"]>.title').after(html);
						$item_group_option.find('.item_group_option_detail_list_wrap .group_option[data-id="'+igo_seq+'"] .group_option_detail[data-id2="'+igod_seq+'"]>.title').hide();
						$item_group_option.find('input[name="option_name"]').focus();

						if(data.data && data.data.length > 0){
							$.each(data.data, function(i, v){
								var _image = (v.image && v.image != "")? ((v.image.indexOf(',') != false)? v.image.split(',') : [v.image]) : [];
								get_file_list_option(_image);
							});
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
		}else{ // insert
			html += '<div class="set_write_item_group_option_detail_box">';
			html += '	<form id="set_write_item_group_option_detail_form">';
			html += '		<input type="hidden" name="igo_seq" value="'+igo_seq+'" />';
			html += '		<div class="title">';
			html += '			<span>상품옵션추가</span>';
			html += '			<div class="right_menu"><button type="button" class="close_item_group_option_detail_box_btn"><i class="fas fa-times"></i> <span>닫기</span></button></div>'; // 닫기
			html += '		</div>';
			html += '		<ul>';
			html += '			<li>';
			html += '				<div class="title">상품옵션명</div>';
			html += '				<div>';
			html += '					<input type="text" name="option_name" value="" placeholder="옵션명 입력" />';
			html += '				</div>';
			html += '			</li>';
			html += '			<li>';
			html += '				<div class="title">추가금액</div>';
			html += '				<div>';
			html += '					<input type="number" name="plus_price" value="0" />';
			html += '				</div>';
			html += '			</li>';
			html += '			<li>';
			html += '				<div class="title">옵션이미지</div>';
			html += '				<div>';
			html += '					<input type="hidden" name="image" value="" />';
			html += '					<button type="button" class="upload_img_btn">사진등록</button>';
			html += '					<div class="img_list"></div>';
			html += '				</div>';
			html += '			</li>';
			html += '		</ul>';
			html += '	</form>';
			html += '	<div class="btn_wrap">';
			html += '		<button type="button" class="set_insert_item_option_detail_btn"><i class="far fa-plus-square"></i> 옵션추가</button>';
			html += '	</div>';
			html += '</div>';

			$item_group_option.find('.item_group_option_detail_list_wrap .group_option[data-id="'+igo_seq+'"]>.title').after(html);
			$item_group_option.find('input[name="option_name"]').focus();

			resolve();
		}
	});
}

function write_item_group_option_html(mode, igo_seq){
	return new Promise(function(resolve, reject) {
		$item_group_option.find('.set_write_item_group_option_box').remove();

		var html = '';

		if(mode == "update" && igo_seq != ""){ // update
			$.ajax({
				url: '../test/test_item_list_ajax.php',
				data: {
					mode: "get_item_group_option_list",
					igo_seq: igo_seq
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
						
						$.each(data.data, function(i, v){
							var is_checked = "";

							html += '<div class="set_write_item_group_option_box">';
							html += '	<form id="set_write_item_group_option_form">';
							html += '		<input type="hidden" name="igo_seq" value="'+v.igo_seq+'" />';
							html += '		<div class="title">';
							html += '			<span>상품옵션 그룹수정</span>';
							html += '			<div class="right_menu"><button type="button" class="close_item_group_option_box_btn"><i class="fas fa-times"></i>  <span>닫기</span></button></div>'; // 닫기
							html += '		</div>';
							html += '		<ul>';
							html += '			<li>';
							html += '				<div class="title">상품옵션 그룹명</div>';
							html += '				<div>';
							html += '					<input type="text" name="group_name" value="'+v.group_name+'" placeholder="그룹명 입력" />';
							html += '				</div>';
							html += '			</li>';
							html += '			<li>';
							html += '				<div class="title">표시종류 선택</div>';
							html += '				<div>';
							is_checked = (v.kind == "1")? "checked" : "";
							html += '					<input type="radio" id="kind_1" name="kind" value="1" '+is_checked+' />';
							html += '					<label for="kind_1"><span></span>선택리스트</label>';
							is_checked = (v.kind == "2")? "checked" : "";
							html += '					<input type="radio" id="kind_2" name="kind" value="2" '+is_checked+' />';
							html += '					<label for="kind_2"><span></span>나열아이콘</label>';
							html += '				</div>';
							html += '			</li>';
							html += '		</ul>';
							html += '	</form>';
							html += '	<div class="btn_wrap">';
							html += '		<button type="button" class="set_update_item_option_btn"><i class="fas fa-wrench"></i> 그룹수정</button>';
							html += '	</div>';
							html += '</div>';								
						});

						$item_group_option.find('.group_option[data-id="'+igo_seq+'"]>.title').after(html);
						$item_group_option.find('.group_option[data-id="'+igo_seq+'"]>.title').hide();
						$item_group_option.find('.group_option[data-id="'+igo_seq+'"]>.group_fold').css('background-color', 'inherit');
						$item_group_option.find('.group_option[data-id="'+igo_seq+'"]>.group_fold').css('border', '0px');
						$item_group_option.find('.group_option[data-id="'+igo_seq+'"]>.group_fold ul').hide();
						$item_group_option.find('input[name="group_name"]').focus();

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
		}else{ // insert
			html += '<div class="set_write_item_group_option_box">';
			html += '	<form id="set_write_item_group_option_form">';
			html += '		<div class="title">';
			html += '			<span>상품옵션 그룹추가</span>';
			html += '			<div class="right_menu"><button type="button" class="close_item_group_option_box_btn"><i class="fas fa-times"></i>  <span>닫기</span></button></div>'; // 닫기
			html += '		</div>';
			html += '		<ul>';
			html += '			<li>';
			html += '				<div class="title">상품옵션 그룹명</div>';
			html += '				<div>';
			html += '					<input type="text" name="group_name" value="" placeholder="그룹명 입력" />';
			html += '				</div>';
			html += '			</li>';
			html += '			<li>';
			html += '				<div class="title">표시종류 선택</div>';
			html += '				<div>';
			html += '					<input type="radio" id="kind_1" name="kind" value="1" checked />';
			html += '					<label for="kind_1"><span></span>선택리스트</label>';
			html += '					<input type="radio" id="kind_2" name="kind" value="2" />';
			html += '					<label for="kind_2"><span></span>나열아이콘</label>';
			html += '				</div>';
			html += '			</li>';
			html += '		</ul>';
			html += '	</form>';
			html += '	<div class="btn_wrap">';
			html += '		<button type="button" class="set_insert_item_option_btn"><i class="fas fa-folder-plus"></i> 그룹추가</button>';
			html += '	</div>';
			html += '</div>';

			$item_group_option.find('.item_group_option_wrap>.title').after(html);
			$item_group_option.find('input[name="group_name"]').focus();

			resolve();
		}
	});
}

function set_write_item_group_option(mode, igo_seq){
	return new Promise(function(resolve, reject) {
		var post_data = $item_group_option.find("#set_write_item_group_option_form").serialize();
		post_data += '&product_no='+option_product_no;
		if(mode == "insert"){
			post_data += '&mode=set_insert_item_group_option';
		}else if(mode == "update"){
			post_data += '&mode=set_update_item_group_option';
		}else if(mode == "delete"){
			// init
			post_data = '&mode=set_delete_item_group_option';
			post_data += '&igo_seq='+igo_seq;
			post_data += '&delete_id=<?=$user_id ?>';
			post_data += '&delete_txt=admin.item_write에서 삭제';
		}
		
		console.log(post_data);
		if(mode != ""){
			$.ajax({
				url: '../test/test_item_list_ajax.php',
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
						if(mode == "insert"){
							$.MessageBox("그룹이 등록되었습니다.");
						}else if(mode == "update"){
							$.MessageBox("그룹이 수정되었습니다.");
						}else if(mode == "delete"){
							$.MessageBox("그룹이 삭제되었습니다.");
						}
						item_group_option_refresh_html();

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
		}else{
			$.MessageBox("잘못된 호출입니다.");
		}
	});
}

function get_item_group_option_list(){
	return new Promise(function(resolve, reject) {
		$item_group_option.find('.item_group_option_detail_list_wrap').html('');

		$.ajax({
			url: '../test/test_item_list_ajax.php',
			data: {
				mode : "get_item_group_option_list",
				product_no : option_product_no
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
					var html = '';
					
					if(data.data && data.data.length > 0){
						html += '<div>';
						html += '	<ul>';
						$.each(data.data, function(i, v){
							var _is_fold = (v.is_fold == "1")? '' : 'on';
							var _is_fold_txt = (v.is_fold == "1")? '<i class="fas fa-folder"></i>' : '<i class="fas fa-folder-open"></i>'; // 펼치기 / 접기
							var _kind = (v.kind == "1")? '<i class="fas fa-list"></i>' : '<i class="fas fa-grip-horizontal"></i>';

							html += '		<li class="group_option" data-id="'+v.igo_seq+'">';
							html += '			<div class="title update_item_group_option_fold_btn '+_is_fold+'">';
							html += '				<span>'+_kind+' '+v.group_name+'</span>';
							html += '				<div class="right_menu">';
							html += '					<button type="button" class="set_delete_item_option_btn"><i class="fas fa-folder-minus"></i> <span>그룹삭제</span></button>'; // 그룹삭제
							html += '					<button type="button" class="update_item_group_option_btn"><i class="fas fa-wrench"></i> <span>그룹수정</span></button>'; // 그룹수정
							//html += '					<button type="button" >'+_is_fold_txt+'</button>';
							html += '					<button type="button" class="insert_item_group_option_detail_btn"><i class="far fa-plus-square"></i> <span>옵션추가</span></button>'; // 옵션추가
							html += '				</div>';
							html += '			</div>';
							html += '		</li>';
						});
						html += '	</ul>';
						html += '</div>';
					}else{
						html += '<div class="no_data">';
						html += '	상품 그룹옵션이 없습니다.';
						html += '</div>';
					}
					$item_group_option.find('.item_group_option_detail_list_wrap').append(html);

					if(data.data && data.data.length > 0){
						$.each(data.data, function(i, v){
							get_item_group_option_detail_list(v.igo_seq, v.is_fold);
						});
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

function get_item_group_option_detail_list(igo_seq, is_fold){
	return new Promise(function(resolve, reject) {
		var _is_fold = (is_fold == "1")? '' : 'on';

		$.ajax({
			url: '../test/test_item_list_ajax.php',
			data: {
				mode : "get_item_group_option_detail_list",
				product_no : option_product_no,
				igo_seq : igo_seq
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
					var html = '';
					
					if(data.data && data.data.length > 0){
						html += '<div class="group_fold '+_is_fold+'" data-id="'+igo_seq+'">';
						html += '	<ul>';
						$.each(data.data, function(i, v){
							var _image = (v.image != "")? '<div class="image"></div>' : '';
							var _image_css = (v.image != "")? 'style="padding-left: 40px;"' : '';
							var _plus_price = (v.plus_price != "" && parseInt(v.plus_price) > 0)? ' [ +'+v.plus_price.format()+'원 ]' : '';

							html += '		<li class="group_option_detail" data-id="'+v.igo_seq+'" data-id2="'+v.igod_seq+'">';
							html += '			<div class="title" '+_image_css+' >';
							html += _image;
							html += '				<span>'+v.option_name+_plus_price+'</span>';
							html += '				<div class="right_menu">';
							html += '					<button type="button" class="update_item_group_option_detail_btn"><i class="far fa-edit"></i> <span>옵션수정</span></button>'; // 옵션수정
							html += '					<button type="button" class="set_delete_item_option_detail_btn"><i class="far fa-minus-square"></i> <span>옵션삭제</span></button>'; // 옵션삭제
							html += '				</div>';
							html += '			</div>';
							html += '		</li>';
						});
						html += '	</ul>';
						html += '</div>';
					}else{
						html += '<div class="no_data">';
						html += '	상품 그룹옵션 상품이 없습니다.';
						html += '</div>';
					}
					$item_group_option.find('.item_group_option_detail_list_wrap .group_option[data-id="'+igo_seq+'"]').append(html);
					$item_group_option.find('.item_group_option_detail_list_wrap .group_option[data-id="'+igo_seq+'"]').focus();

					if(data.data && data.data.length > 0){
						$.each(data.data, function(i, v){
							var _image = (v.image && v.image != "")? ((v.image.indexOf(',') != false)? v.image.split(',') : [v.image]) : [];
							get_thumbnail(_image, v.igo_seq, v.igod_seq);
						});
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

function get_thumbnail(img_list, igo_seq, igod_seq){
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

					if(data.data && data.data.length > 0){
						$.each(data.data, function(i, v){
							if(i == 0){ // only 1
								$item_group_option.find('.item_group_option_detail_list_wrap .group_option[data-id="'+igo_seq+'"] .group_option_detail[data-id2="'+igod_seq+'"] .image').css('background-image', 'url("'+v.file_path+'")');					
							}
						});
					}
					/*
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
					//$('#item_write #item_form .img_list').html(html);
					*/
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
	//}else{
	//	$('#item_write #item_form .img_list').html('업로드된 이미지가 없습니다.');
	}
}

function get_file_list_option(img_list){
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
					$item_group_option.find('.set_write_item_group_option_detail_box .img_list').html(html);
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
		$item_group_option.find('.set_write_item_group_option_detail_box .img_list').html('업로드된 이미지가 없습니다.');
	}
}

function set_delete_file(f_seq){
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
				var img_data = $item_group_option.find('.set_write_item_group_option_detail_box input[name="image"]').val();
				option_img_list = (img_data != "")? ((img_data.indexOf(','))? img_data.split(',') : [img_data]) : [];

				$.each(option_img_list, function(i, v){
					if(v == f_seq){
						option_img_list.splice(i, 1);
					}
				});
				console.log(option_img_list);

				if(igod_seq != ""){
					$.ajax({
						url: '../test/test_item_list_ajax.php',
						data: {
							mode: "set_update_item_group_option_detail",
							igod_seq: igod_seq,
							image: option_img_list.join(",")
						},
						type: 'POST',
						dataType: 'JSON',
						success: function(data) {
							if(data.code == "000000"){
								console.log("product_img update OK");

								$item_group_option.find('.set_write_item_group_option_detail_box input[name="image"]').val(option_img_list.join(","));
								get_file_list_option(option_img_list);
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
}

$.fn.selectRange = function(start, end) {
	return this.each(function() {
		 if(this.setSelectionRange) {
			 this.focus();
			 this.setSelectionRange(start, end);
		 } else if(this.createTextRange) {
			 var range = this.createTextRange();
			 range.collapse(true);
			 range.moveEnd('character', end);
			 range.moveStart('character', start);
			 range.select();
		 }
	 });
 }; 

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

<?php
    include "../include/bottom.php";
?>
