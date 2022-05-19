<?php include "../include/top.php"; ?>

<?php
$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
	return false;
}
?>

<?php
	$user_id = $_SESSION['gobeauty_user_id'];
	$user_name = $_SESSION['gobeauty_user_nickname'];
?>

<style>
a{text-decoration:none; }
a:link {color:white;}
a:visited {color:white;}
a:hover {color:white;}
a:active {color:white;}

.my_reservation{
position:relative;
z-index:5;
width:100%;
text-align:left;
padding:10px;
border:1 solid #999999;
margin:auto;
}

.change_reservation {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #c123de), color-stop(1, #a20dbd) );
	background:-moz-linear-gradient( center top, #c123de 5%, #a20dbd 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#c123de', endColorstr='#a20dbd');
	background-color:#c123de;
	-webkit-border-top-left-radius:6px;
	-moz-border-radius-topleft:6px;
	border-top-left-radius:6px;
	-webkit-border-top-right-radius:6px;
	-moz-border-radius-topright:6px;
	border-top-right-radius:6px;
	-webkit-border-bottom-right-radius:6px;
	-moz-border-radius-bottomright:6px;
	border-bottom-right-radius:6px;
	-webkit-border-bottom-left-radius:6px;
	-moz-border-radius-bottomleft:6px;
	border-bottom-left-radius:6px;
	text-indent:0;
	border:1px solid #a511c0;
	display:inline-block;
	color:#ffffff;
	font-family:Arial;
	font-size:20px;
	font-weight:bold;
	font-style:normal;
	height:36px;
	line-height:36px;
	width:100%;
	text-decoration:none;
	text-align:center;
}
.change_reservation:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #a20dbd), color-stop(1, #c123de) );
	background:-moz-linear-gradient( center top, #a20dbd 5%, #c123de 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#a20dbd', endColorstr='#c123de');
	background-color:#a20dbd;
}.change_reservation:active {
	position:relative;
	top:1px;
}
.cancel_reservation {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #b5b5b5), color-stop(1, #999999) );
	background:-moz-linear-gradient( center top, #ededed 5%, #dfdfdf 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#dfdfdf');
	background-color:#999999;
	-webkit-border-top-left-radius:6px;
	-moz-border-radius-topleft:6px;
	border-top-left-radius:6px;
	-webkit-border-top-right-radius:6px;
	-moz-border-radius-topright:6px;
	border-top-right-radius:6px;
	-webkit-border-bottom-right-radius:6px;
	-moz-border-radius-bottomright:6px;
	border-bottom-right-radius:6px;
	-webkit-border-bottom-left-radius:6px;
	-moz-border-radius-bottomleft:6px;
	border-bottom-left-radius:6px;
	text-indent:0;
	border:1px solid #dcdcdc;
	display:inline-block;
	color:#777777;
	font-family:Arial;
	font-size:15px;
	font-weight:bold;
	font-style:normal;
	height:36px;
	line-height:36px;
	width:88px;
	text-decoration:none;
	text-align:center;
}
.cancel_reservation:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #b5b5b5), color-stop(1, #999999) );
	background:-moz-linear-gradient( center top, #dfdfdf 5%, #ededed 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#dfdfdf', endColorstr='#ededed');
	background-color:#999999;
}.cancel_reservation:active {
	position:relative;
	top:1px;
}

.event_title {
	color:#000;font-size:20px;font-weight:bold;
}
.event_body {
	color:#000;font-size:16px;
}

.go_button {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #fa9dcc), color-stop(1, #f192c1) );
	background:-moz-linear-gradient( center top, #c123de 5%, #a20dbd 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#fa9dcc', endColorstr='#f192c1');
	background-color:#fa9dcc;
	-webkit-border-top-left-radius:6px;
	-moz-border-radius-topleft:6px;
	border-top-left-radius:6px;
	-webkit-border-top-right-radius:6px;
	-moz-border-radius-topright:6px;
	border-top-right-radius:6px;
	-webkit-border-bottom-right-radius:6px;
	-moz-border-radius-bottomright:6px;
	border-bottom-right-radius:6px;
	-webkit-border-bottom-left-radius:6px;
	-moz-border-radius-bottomleft:6px;
	border-bottom-left-radius:6px;
	text-indent:0;
	border:0px solid #a511c0;
	display:inline-block;
	color:#ffffff;
	font-family:Arial;
	font-size:20px;
	font-weight:bold;
	font-style:normal;
	height:47px;
	line-height:47px;
	width:100%;
	text-decoration:none;
	text-align:center;
}
.go_button:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #a20dbd), color-stop(1, #c123de) );
	background:-moz-linear-gradient( center top, #a20dbd 5%, #c123de 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#a20dbd', endColorstr='#c123de');
	background-color:#a20dbd;
}.go_button:active {
	position:relative;
	top:1px;
}
input[type=text], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 6px;
    box-sizing: border-box;
}
</style>

        <div style="position:absolute;z-index:5;top:5px;left:10px;"><a href="<?=$mainpage_directory?>/request_artist_region.php"><img src="<?=$image_directory?>/back.png" width="35px"></a></div>
        <div style="width:100%;height:19px;text-align:center;font-size:18px;font-weight:bold;"><p>반짝 입점 신청</p></div>

        <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">

	<div style="height:10px;"></div>

	<form action="<?=$mainpage_directory?>/insert_artist_regist.php" method="POST" id="shop_form">
	<input type="hidden" name="step" value="3"/>
        <table style="width:100%;font-size:20px;">
		<tr><td height="10px"></td></tr>
		<tr>
			<td colspan="2" style="font-size:17px;">
				<b>전문분야 선택(복수 선택가능)</b>
			</td>
		</tr>
		<tr><td height="5px"></td></tr>
		<tr>
			<td colspan="2" style="font-size:18px;">
<?php
                $pro_sql = "select * from tb_config_professional order by sequence asc;";
                $pro_result = mysql_query($pro_sql);
                for ($pro_index = 0 ; $pro_datas = mysql_fetch_object($pro_result); $pro_index++) {
                        $pro_name = $pro_datas->name;
                        $pro_value = $pro_datas->value;
                        echo $pro_value;
                        echo "<input type='checkbox' id='region_$pro_index' name='region_$pro_index' value='$pro_value' ";
                        echo "> &nbsp;&nbsp; ";
			if (($pro_index % 3) == 2) {
				echo "<br>";
			}
                }
?>

<script>
function check_next () {
	var flag = 1;
	var pro_index = '<?=$pro_index?>';
	for (var i=0 ; i < pro_index ; i++) {
		var agree = document.getElementById('region_'+i);
	        if ($(agree).prop("checked") == true) {
			flag = 0;
	        }
	}
	if (flag) {
		$.MessageBox({
                	buttonDone      : "확인",
                        message         : "1개 이상 선택해주세요."
                }).done(function(){
                });
		return false;
	}
	document.getElementById('shop_form').submit();
}
</script>
			</td>
		</tr>
		<tr><td height="10px"></td></tr>
		<tr>
			<td colspan="2" style="font-size:17px;">
				<input type="button" onclick="check_next();" href="#" class="change_reservation" value="다 &nbsp; &nbsp;&nbsp;&nbsp; 음">
			</td>
		</tr>
        </table>
	</form>

	<br>
	<br><br><br>
	<br><br><br>
<?php include "../include/bottom.php"; ?>
