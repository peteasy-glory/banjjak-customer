<?php
	include "../include/top.php";
	include "../include/Crypto.class.php";

	$cl_result = include "../include/check_login.php";
	if ($cl_result == 0) {
		return false;
	}

	$r_startDate = ($_GET["startDate"] && $_GET["startDate"] != "")? $_GET["startDate"] : Date('Y-m-d');
	$r_endDate = ($_GET["endDate"] && $_GET["endDate"] != "")? $_GET["endDate"] : Date('Y-m-d');
	$user_id = $_SESSION['gobeauty_user_id'];
	$user_name = $_SESSION['gobeauty_user_nickname'];
?>
<script src="<?= $js_directory ?>/fontawesome.min.js"></script>
<style>
	@font-face {font-family: 'NL2GR';src: url('../fonts/NEXON_Lv2_Gothic.woff') format('woff');}
	/* init */
	#manage_payment_log { width: 100%; height: 100%; position: relative; border: 0px; padding: 0px; margin: 0px; font-family: 'NL2GR'; font-weight: normal; font-size: 14px; }
	#manage_payment_log h1,h2,h3,h4,h5,h6 { margin: 0px; padding: 0px; font-size: 14px; }
	#manage_payment_log select { font-size: 14px; border: 1px solid #ccc; padding: 0px 10px; height: 30px; border-radius: 5px; background-color: #fff; }
	#manage_payment_log input[type='text'] { font-size: 14px; border: 1px solid #ccc; border-radius: 5px; height: 30px; padding: 0px 10px; background-color: #fff; }
	#manage_payment_log input[type='checkbox'] { display: none; }
	#manage_payment_log input[type='checkbox']+label { display: inline-block; padding: 0px 10px; border: 1px solid #ccc; border-radius: 5px; background-color: #eee; height: 30px; line-height: 30px; text-align: center; }
	#manage_payment_log input[type='checkbox']:checked+label { border: 1px solid #f5bf2e; background-color: #f5bf2e; color: #fff; font-weight: Bold; }
	#manage_payment_log ul { list-style: none; margin: 0px; padding: 0px; }
	#manage_payment_log button { border: 1px solid #ccc; background-color: #eee; border-radius: 5px; padding: 0px 10px; height: 30px; }

	/* header */
	#manage_payment_log .header { position: sticky; top: 0px; height: 50px; width: 100%; line-height: 50px; text-align: center; background-color: #fff; z-index: 100; font-size: 18px; }
	#manage_payment_log .header .h_left { position: absolute; display: flex; justify-content: center; align-items: center; left: 0px; top: 0px; min-width: 50px; height: 50px; cursor: pointer; }
	#manage_payment_log .header .h_center>h2 { font-size: 18px; }
	#manage_payment_log .header .h_right { position: absolute; display: flex; justify-content: center; align-items: center; right: 0px; top: 0px; min-width: 50px; height: 50px; }

	/* content */
	#manage_payment_log .content { width: 100%; height: 100%; position: relative; padding-bottom: 50px; }
	#manage_payment_log .content .search_wrap { width: calc(100% - 20px); padding: 10px; overflow-x: auto; background-color: #f9f9f9; }
	#manage_payment_log .content .search_wrap ul.table { display: table; width: 100%; }
	#manage_payment_log .content .search_wrap ul.table li { display: table-cell; white-space: nowrap; text-align: center; padding: 0px 10px; }
	#manage_payment_log .content .search_wrap ul.table li input[type='text'] { width: 100px; }
	#manage_payment_log .content .search_wrap ul.table li input + button { background-color: #fff; width: 30px; height: 30px; line-height: 30px; border: 0px; vertical-align: top; padding: 0px; }
	#manage_payment_log .content .search_wrap ul.table li input + button>img { vertical-align: middle; width: 100%; }
	#manage_payment_log .content .search_wrap ul.table li input[type='checkbox']+label {  }
	#manage_payment_log .content .payment_log_summary { font-size: 14px; padding-left: 10px; margin: 10px 0px; }
	#manage_payment_log .content table { width: 100%; border-collapse: collapse; font-size: 12px; border-bottom: 5px solid #999; }
	#manage_payment_log .content table tr:nth-child(odd) { background-color: #f9f9f9; }
	#manage_payment_log .content table tr:hover { background-color: #ffe; }
	#manage_payment_log .content table tr th { position: sticky; top: 50px; background-color: #eee; padding: 5px; white-space: nowrap; }
	#manage_payment_log .content table tr td { font-size: 10px; padding: 2px 5px; text-align: center; }
	#manage_payment_log .content table tr td.lft { text-align: left; }
	#manage_payment_log .content table tr td.rht { text-align: right; }
	#manage_payment_log .content table tr td.no_data { padding: 50px 0px; color: #999; background-color: #f9f9f9; }
	#manage_payment_log .content table tr:hover td.no_data { background-color: #f9f9f9; }
	#manage_payment_log .content table tr td .gray { color: #999; }
</style>
<div id="manage_payment_log">
</div>
<script>
	var $manage_payment_log = $("#manage_payment_log");
	var admin_id = '<?=$user_id ?>';
	var start_date = '<?=$r_startDate ?>';
	var end_date = '<?=$r_endDate ?>';
	var summary = [0, 0, 0, 0]; // ??????, ??????, ?????????, ?????????
	var artist_list = []; // ??????
	var pay_type = {POS: 0, CARD: 0, BANK: 0, OFFLINE: 0}; // ????????????(????????????, ??????, ??????, ????????????)

	$(function(){
		get_customer()
			.then(init_html);
	});

	$manage_payment_log.on("click", ".get_search_btn", function(){
		$(this).html('<i class="fas fa-spinner"></i>');
		summary = [0, 0, 0, 0]; // ??????, ??????, ?????????, ?????????
		artist_list = []; // ??????
		pay_type = {POS: 0, CARD: 0, BANK: 0, OFFLINE: 0}; // ????????????(????????????, ??????, ??????, ????????????)

		get_payment_log_summary()
			.then(get_beauty_payment_log_cnt);
			//.then(get_beauty_payment_log);
	});

	$manage_payment_log.on("click", ".get_reset_btn", function(){
		$manage_payment_log.find("#search input[name='startDate']").val(start_date);
		$manage_payment_log.find("#search input[name='endDate']").val(end_date);
		$manage_payment_log.find("#search input[name='customer_id']").val('');
		$manage_payment_log.find("#search input[name='artist_id']").val('');
		$manage_payment_log.find("#search input[name='pay_type[]']").prop('checked', false);
		$manage_payment_log.find('.content table.payment_log').remove();
		$manage_payment_log.find('.content div.payment_log_summary').remove();
		$manage_payment_log.find('.payment_log_summary').remove();
	});
	
	$manage_payment_log.on("click", ".admin_main_btn", function(){
		location.href = "<?=$admin_directory ?>/index.php";
	});
	

	function get_payment_log_summary(){
		return new Promise(function(resolve, reject) {
			$manage_payment_log.find('.content div.payment_log_summary').remove();
			var html = '';

			html += '<div class="payment_log_summary">';
			html += '	??? <span class="total_cnt">0???</span>, ';
			html += '	<span class="total_price">0???</span> | ';
			html += '	?????? ??? <span class="shop_cnt">0???</span> | ';
			html += '	????????? ??? <span class="customer_cnt">0???</span> / ';
			html += '	????????? ??? <span class="tmp_cnt">0???</span> | ';
			html += '	??? ?????? ??? <span class="pay_type_cnt">[0???]</span>';
			html += '</div>';

			$manage_payment_log.find('.content').append(html);
			resolve();
			/*
			var start_date = $manage_payment_log.find("#search input[name='startDate']").val();
			var end_date = $manage_payment_log.find("#search input[name='endDate']").val();
			var customer_id = $manage_payment_log.find("#search input[name='customer_id']").val();
			var artist_id = $manage_payment_log.find("#search input[name='artist_id']").val();

			$.ajax({
				url: 'manage_payment_logs_ajax.php',
				data: {
					mode: 'get_payment_log_summary',
					start_date: start_date,
					end_date: end_date,
					customer_id: customer_id,
					artist_id: artist_id
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						var html = '';

						html += '<div class="payment_log_summary">';
						html += '	<div class=""></div>';
						html += '</div>';

						$manage_payment_log.find('.content').append(html);
						resolve();
					}else{
						alert(data.data+"("+data.code+")");
						console.log(data.code);
					}
				},
				error: function(xhr, status, error) {
					//alert(error + "??????????????????");
					if(xhr.status != 0){
						alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // ?????? ??? ??????
					}
				}
			});
			*/
		});
	}

	function get_beauty_payment_log_cnt(){
		return new Promise(function(resolve, reject) {
			$manage_payment_log.find('.content table.payment_log').remove();
			var start_date = $manage_payment_log.find("#search input[name='startDate']").val();
			var end_date = $manage_payment_log.find("#search input[name='endDate']").val();
			var customer_id = $manage_payment_log.find("#search input[name='customer_id']").val();
			var artist_id = $manage_payment_log.find("#search input[name='artist_id']").val();
			var _pay_type = [];
			$manage_payment_log.find("#search input[name='pay_type[]']").each(function(i, v){
				if($(this).is(":checked") == true){
					_pay_type.push($(this).val());
				}
			});

			$.ajax({
				url: 'manage_payment_logs_ajax.php',
				data: {
					mode: 'get_payment_log_cnt',
					start_date: start_date,
					end_date: end_date,
					customer_id: customer_id,
					artist_id: artist_id,
					pay_type: _pay_type
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						var html = '';

						html += '<table class="payment_log">';
						html += '	<thead>';
						html += '		<tr>';
						html += '			<th>?????????</th>';
						html += '			<th>?????????</th>';
						html += '			<th>??????</th>';
						html += '			<th>????????????</th>';
						html += '			<th>??????</th>';
						html += '			<th>?????????</th>';
						html += '			<th>???????????????</th>';
						html += '			<th>?????????</th>';
						html += '			<th>VAT</th>';
						html += '			<th>????????????</th>';
						html += '			<th>????????????</th>';
						html += '			<th>????????????</th>';
						html += '			<th>??????</th>';
						html += '			<th>?????????</th>';
						html += '		</tr>';
						html += '	</thead>';
						html += '	<tbody>';
						html += '	</tbody>';
						html += '</table>';
						$manage_payment_log.find('.content').append(html);

						if(parseInt(data.data.cnt) > 2000){
							$.MessageBox({
								buttonDone: "??????",
								buttonFail: "??????",
								message: "?????? ????????? ?????? 2000??? ???????????????.("+data.data.cnt+"???) <br/> ????????? ?????? ???????????????????"
							}).done(function(){
								get_beauty_payment_log({limit_0: 0, limit_1: 100, cnt: data.data.cnt});
							});
						}else if(parseInt(data.data.cnt) <= 0){
							html = '';
							html += '<tr>';
							html += '	<td colspan="14" class="no_data">??????????????? ????????????.</td>';
							html += '</tr>';
							$manage_payment_log.find('.content table tbody').append(html);
							$manage_payment_log.find(".get_search_btn").text("??????");
						}else{
							get_beauty_payment_log({limit_0: 0, limit_1: 100, cnt: data.data.cnt});
						}

						resolve(); // {limit_0: 0, limit_1: 100, cnt: data.data.cnt}
					}else{
						alert(data.data+"("+data.code+")");
						console.log(data.code);
					}
				},
				error: function(xhr, status, error) {
					//alert(error + "??????????????????");
					if(xhr.status != 0){
						alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // ?????? ??? ??????
					}
				}
			});
		});
	}

	function get_beauty_payment_log(post_data){
		return new Promise(function(resolve, reject) {
			var start_date = $manage_payment_log.find("#search input[name='startDate']").val();
			var end_date = $manage_payment_log.find("#search input[name='endDate']").val();
			var customer_id = $manage_payment_log.find("#search input[name='customer_id']").val();
			var artist_id = $manage_payment_log.find("#search input[name='artist_id']").val();
			var _pay_type = [];
			$manage_payment_log.find("#search input[name='pay_type[]']").each(function(i, v){
				if($(this).is(":checked") == true){
					_pay_type.push($(this).val());
				}
			});
			var limit_0 = post_data.limit_0;
			var limit_1 = post_data.limit_1;

			$.ajax({
				url: 'manage_payment_logs_ajax.php',
				data: {
					mode: 'get_payment_log',
					start_date: start_date,
					end_date: end_date,
					customer_id: customer_id,
					artist_id: artist_id,
					pay_type: _pay_type,
					limit_0: limit_0,
					limit_1: limit_1
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						var html = '';
						$.each(data.data, function(i, v){
							var _product = (v.product)? v.product.split('|') : "";
							var _is_vat = (v.is_vat == "1")? '????????? 10%' : '-';
							var _customer_id = (v.customer_id && v.customer_id != "")? v.customer_id : '<span class="gray">'+v.cellphone+' (???)</span>';
							var _go_2_offline = (v.go_2_offline == "1")? '????????????' : v.address1+v.address2+v.cellphone;
							var _service = _product[0];
							var _local_price = (v.local_price && v.local_price > 0)? parseInt(v.local_price) : 0;
							var _total_price = (v.total_price && v.total_price > 0)? v.total_price.format() : 0;
							var _spend_point = (v.spend_point && v.spend_point > 0)? v.spend_point.format() : 0;
							var _per_diem = (v.per_diem && v.per_diem > 0)? v.per_diem.format() : 0;
							var _pay_type = '';
							var _is_cancel = (v.is_cancel == "1")? '??????' : '-';
							var _cancel_time = (v.cancel_time && v.cancel_time != "")? v.cancel_time : '';
							if(_product[1] == "???"){
								_service += (_product[3] != "")? '/'+_product[3] : '';
								_service += (_product[4] != "")? '/'+_product[4] : '';
								_service += (_product[5].split(':')[0] != "" && _product[5].split(':')[1] >= 0)? '/'+_product[5].split(':')[0]+'Kg, '+_product[5].split(':')[1].format()+'???' : '';
							}
							if(v.pay_type.indexOf("pos") != -1){
								_pay_type = 'POS';
							}else if(v.pay_type.indexOf("offline") != -1){
								_pay_type = 'OFFLINE';
							}else if(v.pay_type == "card"){
								_pay_type = 'CARD';
							}else if(v.pay_type == "bank"){
								_pay_type = 'BANK';
							}
							var update_time_info_1 = (v.update_time)? v.update_time.split(' ')[0] : "";
							var update_time_info_2 = (v.update_time)? v.update_time.split(' ')[1] : "";
							html += '<tr>';
							html += '	<td><span class="gray">'+v.buy_time.split(' ')[0]+'</span><br/>'+v.buy_time.split(' ')[1]+'</td>';
							html += '	<td><span class="gray">'+update_time_info_1+'</span><br/>'+update_time_info_2+'</td>';
							html += '	<td>'+_customer_id+'</td>';
							html += '	<td>'+v.artist_id+'<br/>'+v.name+'</td>';
							html += '	<td>'+_service+'</td>';
							html += '	<td class="rht">'+_total_price+'</td>';
							html += '	<td class="rht">'+_spend_point+'</td>';
							html += '	<td class="rht">'+_per_diem+'</td>';
							html += '	<td>'+_is_vat+'</td>';
							html += '	<td>'+_go_2_offline+'</td>';
							html += '	<td><span class="gray">'+v.year+'-'+fillZero(2, v.month)+'-'+fillZero(2, v.day)+'</span><br/>'+fillZero(2, v.hour)+':'+fillZero(2, v.minute)+'~'+fillZero(2, v.to_hour)+':'+fillZero(2, v.to_minute)+'</td>';
							html += '	<td>'+_pay_type+'</td>';
							html += '	<td>'+_is_cancel+'</td>';
							html += '	<td>'+_cancel_time+'</td>';
							html += '</tr>';

							summary[0]++; // ??? ???
							summary[1] += ((v.pay_type == 'card' || v.pay_type == 'bank')? (parseInt((v.total_price > 0)? v.total_price : 0) - parseInt((v.spend_point > 0)? v.spend_point : 0)) + _local_price : _local_price); // ????????????
							summary[2] += (v.customer_id && v.customer_id != "")? 1 : 0; // ?????????
							summary[3] += (v.customer_id && v.customer_id != "")? 0 : 1; // ?????????
							artist_list.push(v.artist_id); // ??????

							pay_type[_pay_type]++; // ????????????
						});
						$manage_payment_log.find('.content table tbody').append(html);
					}else{
						alert(data.data+"("+data.code+")");
						console.log(data.code);
					}
				},
				error: function(xhr, status, error) {
					//alert(error + "??????????????????");
					if(xhr.status != 0){
						alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // ?????? ??? ??????
					}
				}
			}).done(function(){
				console.log(limit_0, limit_1, post_data.cnt);				
				if(parseInt(limit_0) + parseInt(limit_1) <= parseInt(post_data.cnt)){
					get_beauty_payment_log({limit_0: (parseInt(limit_0) + parseInt(limit_1)), limit_1: 100, cnt: post_data.cnt});
				}else{
					$manage_payment_log.find(".get_search_btn").text("??????");
				}

				var artistArr = artist_list.filter((element, index) => {
					return artist_list.indexOf(element) === index;
				});
				var pay_type_summary = '';
				//pay_type_summary += (pay_type['POS'] > 0)? '[???????????? '+pay_type['POS']+'???]' : '';
				pay_type_summary += (pay_type['CARD'] > 0)? '[???????????? '+pay_type['CARD']+'???]' : '';
				pay_type_summary += (pay_type['BANK'] > 0)? '[???????????? '+pay_type['BANK']+'???]' : '';
				pay_type_summary += (pay_type['OFFLINE'] > 0)? '[???????????? '+pay_type['OFFLINE']+'???]' : '';
				pay_type_summary = (pay_type_summary == "")? '[0???]' : pay_type_summary;
				
				$manage_payment_log.find(".payment_log_summary .total_cnt").text(summary[0]+'???');
				$manage_payment_log.find(".payment_log_summary .total_price").text(summary[1].format()+'???');
				$manage_payment_log.find(".payment_log_summary .customer_cnt").text(summary[2]+'???');
				$manage_payment_log.find(".payment_log_summary .tmp_cnt").text(summary[3]+'???');
				$manage_payment_log.find(".payment_log_summary .shop_cnt").text(artistArr.length+'???');
				$manage_payment_log.find(".payment_log_summary .pay_type_cnt").text(pay_type_summary);

				resolve();
			});
		});
	}

	function get_customer(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				url: 'manage_payment_logs_ajax.php',
				data: {
					mode: 'get_customer',
					admin_id: admin_id
				},
				type: 'POST',
				dataType: 'JSON',
				success: function(data) {
					if(data.code == "000000"){
						console.log(data.data);
						resolve();
					}else{
						alert(data.data+"("+data.code+")");
						console.log(data.code);
					}
				},
				error: function(xhr, status, error) {
					//alert(error + "??????????????????");
					if(xhr.status != 0){
						alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // ?????? ??? ??????
					}
				}
			});
		});
	}

	function init_html(){
		return new Promise(function(resolve, reject) {
			var html = '';
			var nextYear = new Date();
			nextYear.setFullYear(nextYear.getFullYear() + 1);
			
			html += '<div class="header">';
			html += '	<div class="h_left admin_main_btn">';
			html += '		<i class="fas fa-chevron-left"></i>';
			html += '	</div>';
			html += '	<div class="h_center">';
			html += '		<h2>???????????? ??????(PC)</h2>';
			html += '	</div>';
			html += '	<div class="h_right">';
			html += '	</div>';
			html += '</div>';
			html += '<div class="content">';
			html += '	<div class="search_wrap">';
			html += '		<form id="search">';
			html += '			<ul class="table">';
			html += '				<li>';
			html += '					<div>';
			html += '						<input type="text" id="startDate" name="startDate" class="datePicker" value="'+start_date+'" />';
			html += '					</div>';
			html += '				</li>';
			html += '				<li>';
			html += '					<span>~</span>';
			html += '				</li>';
			html += '				<li>';
			html += '					<div>';
			html += '						<input type="text" id="endDate" name="endDate" class="datePicker" value="'+end_date+'" />';
			html += '					</div>';
			html += '				</li>';
			html += '				<li>';
			html += '					<div>';
			html += '						<span>??????ID</span>';
			html += '						<input type="text" name="customer_id" value="">';
			html += '					</div>';
			html += '				</li>';
			html += '				<li>';
			html += '					<div>';
			html += '						<span>??????ID</span>';
			html += '						<input type="text" name="artist_id" value="">';
			html += '					</div>';
			html += '				</li>';
			html += '				<li>';
			html += '					<div>';
			html += '						<span>????????????</span>';
			html += '						<input type="checkbox" id="pay_type_pos" name="pay_type[]" value="pos">';
			html += '						<label for="pay_type_pos"><span></span>????????????</label>';
			html += '						<input type="checkbox" id="pay_type_offline" name="pay_type[]" value="offline">';
			html += '						<label for="pay_type_offline"><span></span>????????????</label>';
			html += '						<input type="checkbox" id="pay_type_card" name="pay_type[]" value="card">';
			html += '						<label for="pay_type_card"><span></span>????????????</label>';
			html += '						<input type="checkbox" id="pay_type_cash" name="pay_type[]" value="BANK">';
			html += '						<label for="pay_type_cash"><span></span>????????????</label>';
			html += '					</div>';
			html += '				</li>';
			html += '				<li>';
			html += '					<div class="btn_wrap">';
			html += '						<button type="button" class="get_search_btn">??????</button>';
			html += '						<button type="button" class="get_reset_btn">?????????</button>';
			html += '					</div>';
			html += '				</li>';
			html += '			</ul>';
			html += '		</form>';
			html += '	</div>';
			html += '</div>';
			html += '<div class="footer">';
			html += '</div>';
			$manage_payment_log.html(html);

			$("#startDate").datepicker({
				dateFormat: "yy-mm-dd",
				dayNames: ['?????????', '?????????', '?????????', '?????????', '?????????', '?????????', '?????????'],
				dayNamesMin: ['???', '???', '???', '???', '???', '???', '???'],
				monthNames: ['1???', '2???', '3???', '4???', '5???', '6???', '7???', '8???', '9???', '10???', '11???', '12???'],
				monthNamesShort: ['1???', '2???', '3???', '4???', '5???', '6???', '7???', '8???', '9???', '10???', '11???', '12???'],
				showMonthAfterYear: true,
				yearSuffix: "???",
				nextText: "?????????",
				prevText: "?????????",
				showOn: "both",
				buttonImage: "<?= $image_directory ?>/calendar_ico.png",
				onClose: function(selectedDate) {
					if (selectedDate != "") {
						$("#endDate").datepicker("option", "minDate", selectedDate);
						$(".quk_date").removeClass("on");
						$("input[name='quk']").val("");
					}
				}
			});

			$("#endDate").datepicker({
				dateFormat: "yy-mm-dd",
				dayNames: ['?????????', '?????????', '?????????', '?????????', '?????????', '?????????', '?????????'],
				dayNamesMin: ['???', '???', '???', '???', '???', '???', '???'],
				monthNames: ['1???', '2???', '3???', '4???', '5???', '6???', '7???', '8???', '9???', '10???', '11???', '12???'],
				monthNamesShort: ['1???', '2???', '3???', '4???', '5???', '6???', '7???', '8???', '9???', '10???', '11???', '12???'],
				showMonthAfterYear: true,
				yearSuffix: "???",
				nextText: "?????????",
				prevText: "?????????",
				showOn: "both",
				buttonImage: "<?= $image_directory ?>/calendar_ico.png",
				onClose: function(selectedDate) {
					if (selectedDate != "") {
						$("#startDate").datepicker("option", "maxDate", selectedDate);
						$(".quk_date").removeClass("on");
						$("input[name='quk']").val("");
					}
				}
			});
			resolve();
		});
	}

	// ????????? ?????? ??????
	Number.prototype.format = function() {
		if (this == 0) return 0;

		var reg = /(^[+-]?\d+)(\d{3})/;
		var n = (this + '');

		while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');

		return n;
	};

	// ????????? ???????????? ??? ??? ????????? format() ?????? ??????
	String.prototype.format = function() {
		var num = parseFloat(this);
		if (isNaN(num)) return "0";

		return num.format();
	};

	//?????? ???????????? 0?????? ??????
	function fillZero(width, str){
		var str = String(str);//????????? ??????
		return str.length >= width ? str:new Array(width-str.length+1).join('0')+str;
	}
</script>

<?php include "../include/bottom.php"; ?>
