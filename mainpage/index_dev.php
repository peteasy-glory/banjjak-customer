<?php include "../include/top.php"; ?>
<?php include "../include/Crypto.class.php"; ?>
<?php include "mainpage_top_menu.php"; ?>
<?php include "mainpage_swiper_image.php"; ?>
<style>
.artist_list {
    width: 100%;
    height: 120px;
    border: 2px solid white;
    padding: 0px;
    margin: 0px;
}
a{text-decoration:none; }
.artist_list a:link {color:white;}
.artist_list a:visited {color:white;}
.artist_list a:hover {color:white;}
.artist_list a:active {color:white;}

.bottom_notice a:link {color:#999999;}
.bottom_notice a:visited {color:#999999;}
.bottom_notice a:hover {color:#999999;}
.bottom_notice a:active {color:#999999;}

select {
    height:25px;
    padding-left:7px;
    font-size:15px;
    font-weight:bold;
    color: #000000;
    border: 1px solid #999999;
    border-radius: 3px;
}

/*.home_pro_type {
  width:100%;
  height:40px;
  line-height:40px;
  margin:0px;
  top-margin:10px;
  text-align:center;
  font-size:20px;
  font-weight:bold;
  display:inline-block;
  color:#999999;
}*/
.home_pro_type {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #f9f9f9), color-stop(1, #e9e9e9) );
	background:-moz-linear-gradient( center top, #f9f9f9 5%, #e9e9e9 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#f9f9f9', endColorstr='#e9e9e9');
	background-color:#f9f9f9;
	-webkit-border-top-left-radius:28px;
	-moz-border-radius-topleft:28px;
	border-top-left-radius:28px;
	-webkit-border-top-right-radius:28px;
	-moz-border-radius-topright:28px;
	border-top-right-radius:28px;
	-webkit-border-bottom-right-radius:0px;
	-moz-border-radius-bottomright:0px;
	border-bottom-right-radius:0px;
	-webkit-border-bottom-left-radius:0px;
	-moz-border-radius-bottomleft:0px;
	border-bottom-left-radius:0px;
	text-indent:0px;
	border:1px solid #dcdcdc;
	display:inline-block;
	color:#666666;
	font-family:Arial;
	font-size:16px;
	font-weight:bold;
	font-style:normal;
	height:33px;
	line-height:33px;
	width:100%;
	text-decoration:none;
	text-align:center;
}
.home_pro_type:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #e9e9e9), color-stop(1, #f9f9f9) );
	background:-moz-linear-gradient( center top, #e9e9e9 5%, #f9f9f9 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#e9e9e9', endColorstr='#f9f9f9');
	background-color:#e9e9e9;
}.home_pro_type:active {
	position:relative;
	top:1px;
}
.more_view {
  width:100%;
  height:50px;
  line-height:50px;
  margin:0px;
  text-align:center;
  font-size:15px;
  font-weight:bold;
  display:inline-block;
  color:#999999;
  vertical-align:bottom;
}
/*.more_view {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #f9f9f9), color-stop(1, #e9e9e9) );
	background:-moz-linear-gradient( center top, #f9f9f9 5%, #e9e9e9 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#f9f9f9', endColorstr='#e9e9e9');
	background-color:#f9f9f9;
	-webkit-border-top-left-radius:20px;
	-moz-border-radius-topleft:20px;
	border-top-left-radius:20px;
	-webkit-border-top-right-radius:20px;
	-moz-border-radius-topright:20px;
	border-top-right-radius:20px;
	-webkit-border-bottom-right-radius:20px;
	-moz-border-radius-bottomright:20px;
	border-bottom-right-radius:20px;
	-webkit-border-bottom-left-radius:20px;
	-moz-border-radius-bottomleft:20px;
	border-bottom-left-radius:20px;
	text-indent:0px;
	border:1px solid #dcdcdc;
	display:inline-block;
	color:#666666;
	font-family:Arial;
	font-size:16px;
	font-weight:bold;
	font-style:normal;
	height:33px;
	line-height:33px;
	width:195px;
	text-decoration:none;
	text-align:center;
}
.more_view:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #e9e9e9), color-stop(1, #f9f9f9) );
	background:-moz-linear-gradient( center top, #e9e9e9 5%, #f9f9f9 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#e9e9e9', endColorstr='#f9f9f9');
	background-color:#e9e9e9;
}.more_view:active {
	position:relative;
	top:1px;
}*/
hr.type_4 {
border: 0;
height: 5px;
background-color:#d6d6d6;
//background-image: url(../images/type_4.png);
background-repeat: no-repeat;
}
.reservation_button {
        background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #2869B8), color-stop(1, #2869B8) );
        background:-moz-linear-gradient( center top, #2869B8 5%, #2869B8 100% );
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#2869B8', endColorstr='#2869B8');
        background-color:#2869B8;
        -webkit-border-top-left-radius:42px;
        -moz-border-radius-topleft:42px;
        border-top-left-radius:42px;
        -webkit-border-top-right-radius:42px;
        -moz-border-radius-topright:42px;
        border-top-right-radius:42px;
        -webkit-border-bottom-right-radius:42px;
        -moz-border-radius-bottomright:42px;
        border-bottom-right-radius:42px;
        -webkit-border-bottom-left-radius:42px;
        -moz-border-radius-bottomleft:42px;
        border-bottom-left-radius:42px;
        text-indent:0;
        display:inline-block;
        color:#ffffff;
        font-family:Arial;
        font-size:30px;
        font-weight:bold;
        font-style:normal;
        height:59px;
        line-height:59px;
        width:59px;
        text-decoration:none;
        text-align:center;
}
.reservation_button:hover {
        background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #2869B8), color-stop(1, #2869B8) );
        background:-moz-linear-gradient( center top, #2869B8 5%, #2869B8 100% );
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#2869B8', endColorstr='#2869B8');
        background-color:#2869B8;
}.reservation_button:active {
        position:relative;
        top:1px;
}
a{text-decoration:none; }
a:link {color:white;}
a:visited {color:white;}
a:hover {color:white;}
a:active {color:white;}
</style>
<?php
        $user_id = $_SESSION['gobeauty_user_id'];
        $user_name = $_SESSION['gobeauty_user_nickname'];
	$is_artist = 0;
	$is_sql = "select * from tb_customer tc, tb_shop ts where tc.id = '".$user_id."' and ts.customer_id = '".$user_id."';";
	$is_result = mysql_query($is_sql);
	if ($is_rows = mysql_fetch_object($is_result)) {
		if ($is_rows->my_shop_flag && $is_rows->open_flag) {
			$is_artist = 1;
		}
	}
?>

<script>
var get_token = window.Android.onAppGetToken();
if (get_token) {
        $.ajax({
                url: '/login/android.php',
                data: {
			id : '<?=$user_id?>',
			token : get_token
		},
                type: 'POST',
                success: function(data)
                {
                },
                error : function(xhr, status, error) 
                {
                }
        });
}

function SaveTokeniOS(userid, usertoken)
{
	<?php
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if ($user_agent) 
		{
			$token_index = strpos($user_agent, "APP_GOBEAUTY_iOS");
	        if ($token_index > 0) 
	        {
		?>
				var login_id = userid;
				var token = usertoken;
				
				if (login_id && token) 
				{
					$.ajax({
			                url: '../login/ios.php',
			                data: { id : login_id, token : token},
			                type: 'POST',
			                success: function(result){
				                alert("saveok");
			                },
			                error : function(xhr, status, error) 
			                {
			                }
			        });		
				}
	<?php
			}
		}
	?>	
}

function AutoLoginiOS(userid, usertoken)
{
	<?php
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if ($user_agent) 
		{
			$token_index = strpos($user_agent, "APP_GOBEAUTY_iOS");
	        if ($token_index > 0) 
	        {
		?>
				var login_id = userid;
				var token = usertoken;
				var session_login_id = '<?=$_SESSION['gobeauty_user_id']?>';
				
				if (!session_login_id && login_id) 
				{
					var post_data = 'login_id='+login_id+'&token='+token;
			        $.ajax({
			                url: '../include/autologin.php',
			                data: post_data,
			                type: 'POST',
			                success: function(data)
			                {
			                },
			                error : function(xhr, status, error) 
			                {
			                }
			        });					
				}
				
				if (login_id && token) 
				{
					$.ajax({
			                url: '../login/ios.php',
			                data: { id : login_id, token : token},
			                type: 'POST',
			                success: function(result){
			                },
			                error : function(xhr, status, error) 
			                {
			                }
			        });		
				}
	<?php
			}
		}
	?>	
}

</script>

<table width="100%" height="40px" style="background-color:#ffffff;">
	<tr style="text-align:center;">
		<td height="30px" style="font-size:15px;font-weight:bold;">지역<br>필터</td>
		<td align="right">
				<select name="top_region" id="top_region">
<?php
$s_top = $_REQUEST['top'];
$s_middle = $_REQUEST['middle'];

		                //$top_sql = "select distinct top from tb_region where open_flag = true and ( top = '서울' or top = '경기도' or top = '인천' );";
		                $top_sql = "select distinct top from tb_region where open_flag = true;";
		                $top_result = mysql_query($top_sql);
		                while ($top_datas = mysql_fetch_object($top_result)) {
		                        $top = $top_datas->top;
		                        echo "<option value='$top'";
					if (trim($top) == trim($s_top)) { echo "selected"; }
					echo ">$top</option>";
		                }

/*                                $top_sql = "select distinct tr.top from tb_region tr, tb_working_region twr where twr.customer_id = '".$artist_id."' and twr.region_id = tr.id and tr.open_flag = true;";
                                $top_result = mysql_query($top_sql);
                                while ($top_datas = mysql_fetch_object($top_result)) {
                                        $top = $top_datas->top;
                                        echo "<option value='$top'>$top</option>";
                                }*/
?>
		                </select>
		                <select name="middle_region" id="middle_region">
		                </select>
		</td>
		<td>
			<img src="<?=$image_directory?>/main_search_button.png" width="30px" onclick="select_region();">
		</td>
	</tr>
	<tr><td height="3px"></td></tr>
</table>
<script>
var top_region = document.getElementById('top_region');
var middle_region = document.getElementById('middle_region');
function select_region () {
        var selected_top = top_region.options[top_region.selectedIndex].value;
        var selected_middle = middle_region.options[middle_region.selectedIndex].value;
        location.href = '?top='+selected_top+'&middle='+selected_middle;
}
function add_middle()
{
	var artist_id = '<?=$artist_id?>';
        var selected_top = top_region.options[top_region.selectedIndex].value;
        var post_data = 'top_region='+encodeURI(selected_top)+'&artist_id='+artist_id+'&type=open_flag';
        $.ajax({
                url: '<?=$mainpage_directory?>/get_middle_region.php',
                data: post_data,
                type: 'POST',
                success: function(data){
                        var array_middle = data.split(",");
                        var select = document.getElementById('middle_region');
                        select.options.length = 0; // clear out existing items
                        for (var i = 0; i < array_middle.length; i++) {
                                var d = array_middle[i];
                                select.options.add(new Option(d, d));
				if (d.trim() == '<?=$s_middle?>'.trim()) {
					$("#middle_region").val(d).prop("selected", true);
				}
                        }
			//$("#middle_region").val().prop("selected", true);
                },
                error : function(xhr, status, error) {
                }
        });
}
add_middle();
top_region.addEventListener('change', function(e) {
        add_middle();
});
</script>
<?php
if ($s_top && $s_middle) {
?>
	<div style="height:20px;text-align:center;padding:5px;background-color:#ababab;color:#ffffff;font-size:16px;font-weight:bold;">
	<b>오프라인 매장 샵</b>
	</div>
<?php
	$is_able_offline_shop = 0;
	$crypto = new Crypto();
	$enc_region = $crypto->encode(trim($s_top).":".trim($s_middle), $access_key, $secret_key);	
	$artist_sql1 = "select * from tb_request_artist where TRIM(region) = TRIM('".$enc_region."');";
	$artist_result1 = mysql_query($artist_sql1);
        for ($index = 0 ; $artist_datas1 = mysql_fetch_object($artist_result1) ; $index++) {
		$is_got_offline_shop = $artist_datas1->is_got_offline_shop;
		if (!$is_got_offline_shop) { continue; }
		$enc_customer_id = $artist_datas1->customer_id;
		$dec_customer_id = $crypto->decode(trim($enc_customer_id), $access_key, $secret_key);
		$artist_sql = "select * from tb_shop where open_flag = true and enable_flag = true and customer_id = '".$dec_customer_id."';";
		$artist_result = mysql_query($artist_sql);
        	if ($artist_datas = mysql_fetch_object($artist_result)) {

                	$check_sql = "select enable_flag from tb_customer where id = '".$artist_datas->customer_id."';";
        	        $check_result = mysql_query($check_sql);
	                if ($check_rows = mysql_fetch_object($check_result)) {
                        	if(!$check_rows->enable_flag) {
                	                continue;
        	                }
	                }

			$is_able_offline_shop = 1;
?>
                <a href="../artist/?artist_name=<?=urlencode($artist_datas->name)?>">
                <div class="artist_list">
                <!--div style="position:relative;z-index:-1;opacity:0.6;background:#000000;width:100%;height:100%;background-image:url(<?=$artist_datas->front_image?>);background-size: 100%;"-->
                <div style="width:100%;height:120px;position:relative;z-index:-1;background-image:url(<?=$artist_datas->front_image?>);background-size: 100%;">
                <table width="100%" height="100%" style="opacity:0.6;background-color:#000000;">
                        <tr>
                                <td></td>
                        </tr>
                </table>

                <!-- 아티스트 얼굴 -->
                <div style="width:90px;height:90px;position:absolute;left:15px;top:15px;z-index:1;display:block;border-radius:20%;background-image:url(<?=$artist_datas->photo?>);background-size:cover;">
                        <!--img src="<?=$artist_datas->photo?>" style="position:absolute;left:5px;top:5px;z-index:1;display:block;border-radius:50%;" height="90px"-->
                </div>

                <!-- 아티스트 설명 -->
                <div style="position:absolute;right:15px;top:5px;z-index:14;width:60%;height:90px;text-align:rigth;">
                        <table width="100%" height="90px">
                                <tr>
                                        <td style="text-align:right;">
                                                <font style="font-size:20px;"><b><?=$artist_datas->name?></b></font><br>
                                                <font style="font-size:11px;"><?=$artist_datas->working_years?>년<br>
<?php
        $my_pro_sql = "select * from tb_professional where customer_id = '".$artist_datas->customer_id."';";
        $my_pro_result = mysql_query($my_pro_sql);
        for ($cc_i = 0 ; $my_pro_datas = mysql_fetch_object($my_pro_result) ; $cc_i = $cc_i + 1) {
?>
                <?=trim($my_pro_datas->value)?>
<?php
                if ($cc_i == 4) { echo " ..."; break; }
                if (($cc_i % 3) == 2) { echo "<br>"; }
        }
?>
                                                </font>
                                                <br>
<?php
	if ($is_got_offline_shop) {
?>
						<font style="font-size:13px;"><?=trim($s_top)?> <?=trim($s_middle)?></font><br>
<?php
	} else {
        	$now_sql = "select distinct tr.middle, tr.top from tb_working_region twr, tb_region tr where twr.customer_id = '".$artist_datas->customer_id."' and twr.region_id = tr.id;";
	        $now_result = mysql_query($now_sql);
        	for ($index = 0 ; $index < 2 && $now_datas = mysql_fetch_object($now_result) ; $index++) {
                	$now_top = $now_datas->top;
	                $now_middle = $now_datas->middle;
?>
                                                <font style="font-size:13px;"><?=trim($now_top)?> <?=trim($now_middle)?></font><br>
<?php
		}
        }
?>
                                        </td>
                                </tr>
                        </table>
                </div>

                </div>
                </div>
                </a>
<?php
		}
?>
<?php
	}
        if ($index == 0 || $is_able_offline_shop == 0) {
?>
        <center>
                <br>
                <font style="font-size:14px;font-weight:bold;">이 지역에 등록된<br></font>
		<font style="font-size:14px;font-weight:bold;">펫샵/펫스타일리스트가 없습니다.<br></font>
		<font style="font-size:12px;font-weight:bold;">(Go펫은 Beta서비스 중이며,<br></font>
		<font style="font-size:12px;font-weight:bold;">조만간 전국서비스로 만나실 수 있습니다.)<br></font>
                <!--div style="font-size:14px;width:100%;position:absolute;display:block;text-align:center;color:#fff;font-weight:bold;">
                        <div style="height:70px;"></div><br><font style="font-size:16px;">애견뷰티 플렛폼 Go펫은<br>Beta서비스 중 입니다.</font><br><br>조만간 전국서비스로<br>만나실 수 있습니다.<br>감사합니다.</div>
                <img src="<?=$image_directory?>/no_response.jpg" width="100%"-->
        </center>
<?php
        }
?>
	<div style="width:100%;height:10px;"></div>
	<div style="height:20px;text-align:center;padding:5px;background-color:#ababab;color:#ffffff;font-size:16px;font-weight:bold;">
	<b>출장가능 샵</b><br>
	</div>
<?php
	$is_able_goout_shop = 0;
	$artist_sql = "select * from tb_shop where open_flag = true and enable_flag = true and customer_id in (select customer_id from tb_working_region where region_id in (select id from tb_region where TRIM(top) = TRIM('".$s_top."') and TRIM(middle) = TRIM('".$s_middle."')));";
        $artist_result = mysql_query($artist_sql);
        for ($index = 0 ; $artist_datas = mysql_fetch_object($artist_result) ; $index++) {

                $check_sql = "select enable_flag from tb_customer where id = '".$artist_datas->customer_id."';";
                $check_result = mysql_query($check_sql);
                if ($check_rows = mysql_fetch_object($check_result)) {
                        if(!$check_rows->enable_flag) {
                                continue;
                        }
                }

                $is_out = 0;
                $shop_fi_sql = "select * from tb_product_dog_static where customer_id = '".$artist_datas->customer_id."';";
                $shop_fi_result = mysql_query($shop_fi_sql);
                while ($shop_fi_datas = mysql_fetch_object($shop_fi_result)) {
                        $n_out_shop_product = $shop_fi_datas->out_shop_product;
                        if ($n_out_shop_product) { $is_out = 1; }
                }
/*
                $shop_fi_sql = "select * from tb_product_dog_common where customer_id = '".$artist_datas->customer_id."';";
                $shop_fi_result = mysql_query($shop_fi_sql);
                if ($shop_fi_datas = mysql_fetch_object($shop_fi_result)) {
                        $n_out_shop_product = $shop_fi_datas->out_shop_product;
                        if ($n_out_shop_product) { $is_out = 1; }
                }
*/
                $shop_fi_sql = "select * from tb_product_cat where customer_id = '".$artist_datas->customer_id."';";
                $shop_fi_result = mysql_query($shop_fi_sql);
                if ($shop_fi_datas = mysql_fetch_object($shop_fi_result)) {
                        $n_out_shop_product = $shop_fi_datas->out_shop_product;
                        if ($n_out_shop_product) { $is_out = 1; }
                }

                if (!$is_out) {
			continue;
		}
		$is_able_goout_shop = 1;
?>
                <a href="../artist/?artist_name=<?=urlencode($artist_datas->name)?>">
                <div class="artist_list">
                <!--div style="position:relative;z-index:-1;opacity:0.6;background:#000000;width:100%;height:100%;background-image:url(<?=$artist_datas->front_image?>);background-size: 100%;"-->
                <div style="width:100%;height:120px;position:relative;z-index:-1;background-image:url(<?=$artist_datas->front_image?>);background-size: 100%;">
                <table width="100%" height="100%" style="opacity:0.6;background-color:#000000;">
                        <tr>
                                <td></td>
                        </tr>
                </table>

                <!-- 아티스트 얼굴 -->
                <div style="width:90px;height:90px;position:absolute;left:15px;top:15px;z-index:1;display:block;border-radius:20%;background-image:url(<?=$artist_datas->photo?>);background-size:cover;">
                        <!--img src="<?=$artist_datas->photo?>" style="position:absolute;left:5px;top:5px;z-index:1;display:block;border-radius:50%;" height="90px"-->
                </div>

                <!-- 아티스트 설명 -->
                <div style="position:absolute;right:15px;top:5px;z-index:14;width:60%;height:90px;text-align:rigth;">
                        <table width="100%" height="90px">
                                <tr>
                                        <td style="text-align:right;">
                                                <font style="font-size:20px;"><b><?=$artist_datas->name?></b></font><br>
                                                <font style="font-size:11px;"><?=$artist_datas->working_years?>년<br>
<?php
        $my_pro_sql = "select * from tb_professional where customer_id = '".$artist_datas->customer_id."';";
        $my_pro_result = mysql_query($my_pro_sql);
        for ($cc_i = 0 ; $my_pro_datas = mysql_fetch_object($my_pro_result) ; $cc_i = $cc_i + 1) {
?>
                <?=trim($my_pro_datas->value)?>
<?php
                if ($cc_i == 4) { echo " ..."; break; }
                if (($cc_i % 3) == 2) { echo "<br>"; }
        }
?>
                                                </font>
                                                <br>
<?php
		if ($is_got_offline_shop) {
?>
                                                <font style="font-size:13px;"><?=trim($s_top)?> <?=trim($s_middle)?></font><br>
<?php
	        } else {
?>
<?php
	        $now_sql = "select distinct tr.middle, tr.top from tb_working_region twr, tb_region tr where twr.customer_id = '".$artist_datas->customer_id."' and twr.region_id = tr.id;";
	        $now_result = mysql_query($now_sql);
	        for ($index = 0 ; $index < 2 && $now_datas = mysql_fetch_object($now_result) ; $index++) {
	                $now_top = $now_datas->top;
        	        $now_middle = $now_datas->middle;
?>
                                                <font style="font-size:13px;"><?=trim($now_top)?> <?=trim($now_middle)?></font><br>
<?php
		}
        }
?>
                                        </td>
                                </tr>
                        </table>
                </div>

                </div>
                </div>
                </a>

<?php
	}
        if ($index == 0 || $is_able_goout_shop == 0) {
?>
        <center>
                <br>
                <font style="font-size:14px;font-weight:bold;">이 지역에 등록된<br></font>
                <font style="font-size:14px;font-weight:bold;">펫샵/펫스타일리스트가 없습니다.<br></font>
                <font style="font-size:12px;font-weight:bold;">(Go펫은 Beta서비스 중이며,<br></font>
                <font style="font-size:12px;font-weight:bold;">조만간 전국서비스로 만나실 수 있습니다.)<br></font>
        </center>
<?php
        }
} else {

	$select_list = "select customer_id from tb_shop where open_flag = true and enable_flag = true order by rand();";
	$select_list_result = mysql_query($select_list);
?>
	<table width="100%">
	 <tbody id="main_table_addition">
<?php
	$artist_array = array();
	$artist_array_value = "";
	for ($rai = 0 ; $rows = mysql_fetch_object($select_list_result); $rai = $rai + 1)
	{
		$check_sql = "select enable_flag from tb_customer where id = '".$rows->customer_id."';";
		$check_result = mysql_query($check_sql);
		if ($check_rows = mysql_fetch_object($check_result)) {
			if(!$check_rows->enable_flag) {
				continue;
			}
		}

		if ($rai < 10) {
?>
         <tr>
          <td>
<?php
		$artist_id = $rows->customer_id;
		include "main_artist_shop.php";
?>
          </td>
         </tr>		
<?php
		} else {
			if ($rai == 10) {
				$artist_array_value = $rows->customer_id;
			} else {
				$artist_array_value = $artist_array_value."|".$rows->customer_id;
			}
			array_push($artist_array, $rows->customer_id);
		}
	}
?>
	 </tbody>
	</table>
<script>
var increase_section = document.getElementById('main_table_addition');
var artist_array_size = <?=sizeof($artist_array)?>;
var artist_array_value = '<?=$artist_array_value?>';
var artist_array = artist_array_value.split("|");
var addtion_count = 0;
function add_section () {
	for (var i=0 ; i < 10 ; i++) {
	    var artist_id = artist_array[i+(10*addtion_count)];
	    if (!artist_id && i == 0) {
		// 아티스트가 너무 적어서 다시 나오게만든다.
		addtion_count = 0;
		continue;
/*
                $.MessageBox({
                    buttonDone  : "확인",
                    message     : "마지막 리스트 입니다."
                }).done(function(){
                        return false;
                });
		break;
*/
	    }
	    if (!artist_id) {
                break;
            }
            $.ajax({
                url: 'main_artist_shop_ajax.php',
                data: {
                        artist_id : artist_id
                },
                type: 'POST',
                success: function(data){
                        var row = increase_section.insertRow( increase_section.rows.length );
                        var cell1 = row.insertCell(0);
                        cell1.innerHTML = data;
                },
                error : function(xhr, status, error) {
                }
            });
	}
	addtion_count++;
}
</script>

	<div onclick="javascript:add_section();" width="100%" style="border:1px solid #999999;padding:5px;text-align:center;font-weight:bold;font-size:15px;color:#999999;">더보기</div>

<?php
	if ($rai == 0) {
?>
	<center>
		<br><br>
		<font style="font-size:15ps;font-weight:bold;">이 지역에 등록된<br>애견아티스트가 없습니다.</font>
		<br><br><br>
		<div style="font-size:14px;width:100%;position:absolute;display:block;text-align:center;color:#fff;font-weight:bold;">
			<div style="height:70px;"></div><br><font style="font-size:16px;">애견뷰티 플렛폼 Go펫은<br>Beta서비스 중 입니다.</font><br><br>조만간 전국서비스로<br>만나실 수 있습니다.<br>감사합니다.</div>
		<img src="<?=$image_directory?>/no_response.jpg" width="100%">
	</center>
<?php
	}
}
?>
<br>
<table style="width:100%;background-color:#cdcdcd;color:#ffffff;">
<tr>
<td style="text-align:center;padding:10px;font-weight:bold;">
        <font style="font-size:18px;color:white;">고객센터</font><br>
        <font style="font-size:16px;color:white;">1661-9956</font><br>
        <font style="font-size:13px;color:white;">info@pickmon.com</font><br>
</td>
</tr>
</table>
<br>
<font style="font-size:13px;color:#999999;" class="bottom_notice">
<center><a href="terms_of_service.php">이용약관</a> | <a href="privacy_policy.php">개인정보처리방침</a> | <a href="proprietorship.php">사업자정보확인</a></center>
</font>
<font style="font-size:11px;color:#999999;line-height:1em;" class="bottom_notice">
<br>
㈜픽몬 | 대표자:신동찬 | 사업자등록번호 101-86-94635<br>
통신판매업 제2018-서울강남-01255호 | 서울 강남구 강남대로 354 혜천빌딩 11층 |
개인정보담당자 양원일 privacypet@gobeauty.kr<br>
<br>
© PickMON Co. Ltd.  All Rights Reserved.<br>
<br>
Go펫은 통신판매중개자이며 통신판매의 당사자가 아닙니다. 따라서 Go펫은 상품거래정보 및 거래에 대해 책임지지 않습니다. 다만 회사가 판매하는 직매입 상품의 경우 판매업체의 지위를 갖
게 됩니다.<br>
<br><br><br>
</font>

<?php
	if ($is_artist == 1) {
?>
	<div style="position:fixed;bottom:15px;right:15px;z-index:99999;">
                <a href="<?=$shop_directory?>/manage_sell_info.php" class="reservation_button"><font style="color:#ffffff;">$</font></a>
        </div>
        <div style="position:fixed;bottom:15px;left:15px;z-index:99999;">
                <a href="<?=$shop_directory?>/manage_working_schedule.php"><img src="<?=$image_directory?>/go_schedule.png" width="59px" style="-webkit-border-top-left-radius:42px;-moz-border-radius-topleft:42px;border-top-left-radius:42px;-webkit-border-top-right-radius:42px;-moz-border-radius-topright:42px;border-top-right-radius:42px;-webkit-border-bottom-right-radius:42px;-moz-border-radius-bottomright:42px;border-bottom-right-radius:42px;-webkit-border-bottom-left-radius:42px;-moz-border-radius-bottomleft:42px;border-bottom-left-radius:42px;"></a>
        </div>
<?php
	}
?>
<?php include "../include/bottom.php"; ?>
