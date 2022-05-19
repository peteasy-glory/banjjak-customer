<?php include "../include/top.php"; ?>

<?php include "../include/check_login.php"; ?>

<div style="position:absolute;z-index:5;top:5px;left:10px;"><a href="manage_my_reservation.php"><img src="<?=$image_directory?>/back.png" width="35px"></a></div>
<div style="width:100%;height:19px;text-align:center;font-size:18px;font-weight:bold;"><p>예약 변경</p></div>

<hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">


<style>
a{text-decoration:none; }
a:link {color:black;}
a:visited {color:black;}
a:hover {color:black;}
a:active {color:black;}
select {
    height:20px;
    padding-left:7px;
    font-size:14px;
    color: #000000;
    border: 1px solid #999999;
    border-radius: 3px;
}
.date_submit {
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
	font-size:14px;
	font-weight:bold;
	font-style:normal;
	height:25px;
	line-height:20px;
	width:100%;
	text-decoration:none;
	text-align:center;
}
.date_submit:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #a20dbd), color-stop(1, #c123de) );
	background:-moz-linear-gradient( center top, #a20dbd 5%, #c123de 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#a20dbd', endColorstr='#c123de');
	background-color:#a20dbd;
}.date_submit:active {
	position:relative;
	top:1px;
}
</style>

<script>
function scrollToBottom(id){
   var div = document.getElementById(id);
   div.scrollTop = div.scrollHeight - div.clientHeight;
}
</script>
<?php
$user_id = $_REQUEST['artist_id'];
$payment_log_seq = $_REQUEST['payment_log_seq'];

$yy = $_REQUEST['yy'];
$mm = $_REQUEST['mm'];

if($yy == '') $yy = date('Y');
if($mm == '') $mm = date('m');

function sel_yy($yy, $func) {
	if($yy == '') $yy = date('Y');
	if($func=='') {
		$str = "<select name='yy'>\n<option value=''></option>\n";
	} else {
		$str = "<select name='yy' onChange='$func'>\n<option value=''></option>\n";
	}
	$gijun = date('Y');
	for($i=$gijun;$i<$gijun+2;$i++) {
		if($yy == $i) $str .= "<option value='$i' selected>$i</option>";
		else $str .= "<option value='$i'>$i</option>";
	}
	$str .= "</select>";

	return $str;
}

function sel_mm($mm, $func) {
	if($func=='') {
		$str = "<select name='mm'>\n";
	} else {
		$str = "<select name='mm' onChange='$func'>\n";
	}

	for($i=1;$i<13;$i++) {
		if($mm == $i) $str .= "<option value='$i' selected>$i</option>";
		else $str .= "<option value='$i'>$i</option>";
	}

	$str .= "</select>";

	return $str;
}

function get_schedule($yy,$mm,$dd) {
	$mm = str_pad($mm, 2, "0", STR_PAD_LEFT);
	$dd = str_pad($dd, 2, "0", STR_PAD_LEFT);
	$dtstr = $yy."-".$mm."-".$dd;
	$sql = "SELECT * FROM schedule WHERE frdt <= '$dtstr' AND todt >= '$dtstr' ORDER BY frdt, todt";

	$ret = dbquery($sql) or die(mysql_error());
	while($row = dbfetch($ret)) {
		$str .= "<font style='font-size:8pt;'>- $row[name]</font><br>";
	}
	return $str;
}

// 1. 총일수 구하기
$last_day = date("t", strtotime($yy."-".$mm."-01"));

// 2. 시작요일 구하기
$start_week = date("w", strtotime($yy."-".$mm."-01"));

// 3. 총 몇 주인지 구하기
$total_week = ceil(($last_day + $start_week) / 7);

// 4. 마지막 요일 구하기
$last_week = date('w', strtotime($yy."-".$mm."-".$last_day));

// 법정 공휴일 쉬는 여부 1이 쉰다.
$is_rest_public_holiday = 1;
$working_sql = "select * from tb_working_schedule where customer_id = '".$user_id."';";
$working_result = mysql_query($working_sql);
if ($working_datas = mysql_fetch_object($working_result)) {
	$is_rest_public_holiday = $working_datas->rest_public_holiday;
}

// 개인 정기 휴일 설정 1이 쉰다.
$is_monday = 0;
$is_tuesday = 0;
$is_wednesday = 0;
$is_thursday = 0;
$is_friday = 0;
$is_saturday = 0;
$is_sunday = 0;
$get_regular_sql = "select * from tb_regular_holiday where customer_id = '".$user_id."';";
$get_regular_result = mysql_query($get_regular_sql);
if ($get_regular_datas = mysql_fetch_object($get_regular_result)) {
	$is_monday = $get_regular_datas->is_monday;
        $is_tuesday = $get_regular_datas->is_tuesday;
        $is_wednesday = $get_regular_datas->is_wednesday;
        $is_thursday = $get_regular_datas->is_thursday;
        $is_friday = $get_regular_datas->is_friday;
        $is_saturday = $get_regular_datas->is_saturday;
        $is_sunday = $get_regular_datas->is_sunday;
}

$holiday_array = array(0);
$select_public_holiday = "select * from tb_public_holiday where year = ".$yy." and month = ".$mm.";";
$select_result = mysql_query($select_public_holiday);
while ($select_datas = mysql_fetch_object($select_result)) {
	$a_day = $select_datas->day;
	array_push($holiday_array, $a_day);
}

$private_all_holiday_array = array(0);
$private_notall_holiday_array = array(0);
$ph_sql = "select * from tb_private_holiday where customer_id = '".$user_id."' and start_year = ".$yy." and start_month = ".$mm.";";
$ph_result = mysql_query($ph_sql);
while ($ph_datas = mysql_fetch_object($ph_result)) {
	$type = $ph_datas->type;
        $sy = $ph_datas->start_year;
        $sm = $ph_datas->start_month;
        $sd = $ph_datas->start_day;
        $sh = $ph_datas->start_hour;
        $ey = $ph_datas->end_year;
        $em = $ph_datas->end_month;
        $ed = $ph_datas->end_day;
        $eh = $ph_datas->end_hour;

	if ($type == "all") {
		if ($sm != $em) {
			$ed = $ed + 31;
		}
		for ($index = $sd ; $index <= $ed ; $index++) {
			array_push($private_all_holiday_array, $index);
		}
	}
	if ($type == "notall" || $type == "not") {
                array_push($private_notall_holiday_array, $sd);
	}
}
/*$ph_sql = "select * from tb_private_holiday where customer_id = '".$user_id."' and end_year = ".$yy." and end_month = ".$mm.";";
$ph_result = mysql_query($ph_sql);
while ($ph_datas = mysql_fetch_object($ph_result)) {
        $type = $ph_datas->type;
        $sy = $ph_datas->start_year;
        $sm = $ph_datas->start_month;
        $sd = $ph_datas->start_day;
        $sh = $ph_datas->start_hour;
        $ey = $ph_datas->end_year;
        $em = $ph_datas->end_month;
        $ed = $ph_datas->end_day;
        $eh = $ph_datas->end_hour;

        if ($type == "all") {
                if ($sm != $em) {
                        $sd = 0;
                }
                for ($index = $sd ; $index <= $ed ; $index++) {
                        array_push($private_all_holiday_array, $index);
                }
        }
        if ($type == "notall") {
                array_push($private_notall_holiday_array, $sd);
        }
}*/

?>

<center>
<table width="95%">
	<tr>
		<td style="font-size:15px;font-weight:bold;">
			*날짜를 선택하세요.
			(오늘 기준 3개월까지 예약가능)
		</td>
	</tr>
</table>
</center>

<form name="form" method="get">
<input type="hidden" name="artist_id" value="<?=$user_id?>">
<table width='100%' cellpadding='0' cellspacing='1' bgcolor="#999999">

<tr>
<td colspan="7" bgcolor="#FFFFFF" height="40"> 
	<table width="100%">
		<tr>
			<td align="left">
<?php
	$go_year = $yy;
	$go_month = $mm;
	if ($go_month == 1) {
		$go_year = $go_year - 1;
		$go_month = 12;
	} else {
		$go_month = $go_month - 1;
	}
?>
<a href="?artist_id=<?=$user_id?>&yy=<?=$go_year?>&mm=<?=$go_month?>" onclick="move_month();"><img src="<?=$image_directory?>/arrow2left.png" width="30px"></a>
			</td>
			<td align="center">
<?=sel_yy($yy,'submit();')?>년 <?=sel_mm($mm,'submit();')?>월 <!--input type="submit" value="보기"-->
			</td>
			<td align="right">
<?php
        $go2_year = $yy;
        $go2_month = $mm;
        if ($go2_month == 12) {
                $go2_year = $go2_year + 1;
                $go2_month = 1;
        } else {
                $go2_month = $go2_month + 1;
        }
?>
<a href="?artist_id=<?=$user_id?>&yy=<?=$go2_year?>&mm=<?=$go2_month?>" onclick="move_month();"><img src="<?=$image_directory?>/arrow2right.png" width="30px"></a></td>
			</td>
		</tr>
	</table>
</tr>
<tr>
	<td height="30" align="center" bgcolor="#DDDDDD"><b>일</b></td>
	<td align="center" bgcolor="#DDDDDD"><b>월</b></td>
	<td align="center" bgcolor="#DDDDDD"><b>화</b></td>
	<td align="center" bgcolor="#DDDDDD"><b>수</b></td>
	<td align="center" bgcolor="#DDDDDD"><b>목</b></td>
	<td align="center" bgcolor="#DDDDDD"><b>금</b></td>
	<td align="center" bgcolor="#DDDDDD"><b>토</b></td>
</tr>

<?

$today_yy = date('Y');
$today_mm = date('m');

// 5. 화면에 표시할 화면의 초기값을 1로 설정
$day=1;

// 6. 총 주 수에 맞춰서 세로줄 만들기
for($i=1; $i <= $total_week; $i++){?>
<tr>
<?
	// 7. 총 가로칸 만들기
	for ($j=0; $j<7; $j++){
?>
<td width="130" height="50" align="left" valign="top" style="padding:1px;"
<?php
$is_rest = false;
$rest_word = "";

$current_time = date("".$yy."-".$mm."-".$day." 00:00:00");
$next_3month_time = date("Y-m-d 00:00:00", strtotime ("+3 months"));
$next_3month_int = strtotime($next_3month_time);

$cal_time = date('Y-m-d 00:00:00');
$current_int = strtotime($current_time);
$cal_int = strtotime($cal_time);

if ($current_int < $cal_int || $current_int > $next_3month_int) { echo " bgcolor='#bbbbbb' "; $rest_word = ""; $is_rest = true; }
else if (($j==0 && $is_sunday) || ($j==0 && $is_rest_public_holiday) || ($is_rest_public_holiday && in_array($day, $holiday_array))) { echo " bgcolor='#bbbbbb' "; $rest_word = "정휴"; $is_rest = true; }
else if ($j==1 && $is_monday) { echo " bgcolor='#bbbbbb' "; $rest_word = "정휴"; $is_rest = true; }
else if ($j==2 && $is_tuesday) { echo " bgcolor='#bbbbbb' "; $rest_word = "정휴"; $is_rest = true; }
else if ($j==3 && $is_wednesday) { echo " bgcolor='#bbbbbb' "; $rest_word = "정휴"; $is_rest = true; }
else if ($j==4 && $is_thursday) { echo " bgcolor='#bbbbbb' "; $rest_word = "정휴"; $is_rest = true; }
else if ($j==5 && $is_friday) { echo " bgcolor='#bbbbbb' "; $rest_word = "정휴"; $is_rest = true; }
else if ($j==6 && $is_saturday) { echo " bgcolor='#bbbbbb' "; $rest_word = "정휴"; $is_rest = true; }
else if (in_array($day, $private_all_holiday_array)) { echo " bgcolor='#bbbbbb' "; $rest_word = "임시휴무"; $is_rest = true; }
else if (in_array($day, $private_notall_holiday_array)) { echo " bgcolor='#ffffff' "; /*echo " bgcolor='#bbbbbb' "; $rest_word = "반휴";*/ }
else { echo " bgcolor='#FFFFFF' "; }

/*                $current_time = date("".$yy."-".$mm."-".$day." 00:00:00");
                $cal_time = date('Y-m-d 00:00:00');
                $current_int = strtotime($current_time);
                $cal_int = strtotime($cal_time);
                if ($current_int > $cal_int) {
//                      echo "미래";
                }
                else if ($current_int < $cal_int) {
//                      echo "과거";
			echo " bgcolor='#bbbbbb' "; $rest_word = ""; $is_rest = true;
                }
                else {
//                      echo "현재";
                }
*/

?>
>
<?
	// 8. 첫번째 주이고 시작요일보다 $j가 작거나 마지막주이고 $j가 마지막 요일보다 크면 표시하지 않아야하므로
	//    그 반대의 경우 -  ! 으로 표현 - 에만 날자를 표시한다.
	if (!(($i == 1 && $j < $start_week) || ($i == $total_week && $j > $last_week))){


		if (!$is_rest) {
?>
                        <div onclick="select_date('<?=$user_id?>',<?=$yy?>, <?=$mm?>, <?=$day?>);" style="height:100%;width:100%;">
<?php
                }




		if($j == 0 || in_array($day, $holiday_array)){
			// $j가 0이면 일요일이므로 빨간색
			echo "<font color='#FF0000'><b>";
		}else if($j == 6){
			// 10. $j가 0이면 토요일이므로 파란색
			echo "<font color='#0000FF'><b>";
		}else{
			// 11. 그외는 평일이므로 검정색
			echo "<font color='#000000'><b>";
		}

		// check한 날자면 동그라미
		if ($_SESSION['gobeauty_cart_year'] && $_SESSION['gobeauty_cart_month'] && $_SESSION['gobeauty_cart_day'] &&
			$_SESSION['gobeauty_cart_year'] == $yy && $_SESSION['gobeauty_cart_month'] == $mm && $_SESSION['gobeauty_cart_day'] == $day) {
			//echo "<img src='".$image_directory."/check1.png' width='30px' style='position:absolute;z-index:5'>";
//			echo "<img src='".$image_directory."/check1.png' width='30px'>";
		}

		// 12. 오늘 날자면 굵은 글씨
		if($today_yy == $yy && $today_mm == $mm && $day == date("j")){
//			echo "<u>";
		}

		// 13. 날자 출력
		echo $day;

		if($today_yy == $yy && $today_mm == $mm && $day == date("j")){
//			echo "</u>";
			echo "<font style='font-size:12px;'> 오늘</font>";
		}

                if ($_SESSION['gobeauty_cart_year'] && $_SESSION['gobeauty_cart_month'] && $_SESSION['gobeauty_cart_day'] &&
                        $_SESSION['gobeauty_cart_year'] == $yy && $_SESSION['gobeauty_cart_month'] == $mm && $_SESSION['gobeauty_cart_day'] == $day) {
                        echo "<br><img src='".$image_directory."/check4.png' width='25px' style='position:absolute;z-index:5;'>";
                }

		echo "</b></font>";

		//스케줄 출력
		//$schstr = get_schedule($yy,$mm,$day);
		echo $schstr;
		echo "<br><font style='font-size:12px;'>".$rest_word."</font>";

		if (!$is_rest) {
			echo "</div>";
		}

		// 14. 날자 증가
		$day++;
	}

	?>

</td>

<?}?>

</tr>

<?}?>

</table> 

</form>

<?php
if ($_SESSION['gobeauty_cart_year'] && $mm == $_SESSION['gobeauty_cart_month']) {
?>
	<div style="height:10px;"></div>
	<center>
	<div id="time_view">
	<table style="border:0px solid #999999;width:100%;padding:0px;font-size:15px;">
		<tr>
			<td>
				<b>*예약 가능 시간</b>
			</td>
		</tr>
	</table>
	</div>
	</center>

        <center>
        <table style="border:1px solid #999999;width:100%;padding:0px;font-size:15px;">
                <tr>
                        <td>
                                <b style="margin:0px;"><?=$_SESSION['gobeauty_cart_year']?>년 <?=$_SESSION['gobeauty_cart_month']?>월 <?=$_SESSION['gobeauty_cart_day']?>일</b><br>
				<table width="100%" id="hour_details">
				        <tbody id='region_tbody'>
				                <tr><td></td></tr>
				        </tbody>
				</table><br>
				<table width="100%"><tr><td width="20%">
				시간선택</td><td width="50%"><select name="c_input_hour" id="c_input_hour" style="height:20px;padding-left:7px;font-size:14px;color: #000000;border:1px solid #999999;border-radius: 3px;width:80%;">
<?php
/*			        for ($d_index=0;$d_index < 25;$d_index++) {
			                echo "<option value='$d_index'>$d_index</option>";
			        }
*/
?>
				</select> 시
				</td><td width="30%">
				<a href="#" onclick="setParentText()" class="date_submit"><font style="color:#ffffff;">선택 완료</font></a>
				</td></tr></table>
				<font style="font-size:10px;"> &nbsp; *현재시간 기준 3시간 이후 부터 예약 가능</font><br>

                        </td>
                </tr>
        </table>
        </center><br>
<?php
}
?>

<script>
var hour_details = document.getElementById("hour_details");
var region_tbody = document.getElementById('region_tbody');

function zero_fill(str, cnt) { 
str = '000000'+str; 
  return str.substr(str.length-cnt, cnt); 
} 

function get_hour_schedule(artist_id, yy,mm,dd)
{
        var post_data = 's_year='+yy+'&s_month='+mm+'&s_day='+dd+'&artist_id='+artist_id;
        $.ajax({
                url: '<?=$artist_directory?>/get_hour_schedule.php',
                data: post_data,
                type: 'POST',
                success: function(data){
			//alert(data);
                        var array_middle = data.split(",");

                        region_tbody.deleteRow(region_tbody.rows.length-1);

                        var region_tr = document.createElement('tr');
                        region_tbody.appendChild(region_tr);
                        var region_td = document.createElement('td');
                        region_tr.appendChild(region_td);

                        region_td.setAttribute('colspan', '3');
			
			clearSelectOption ();
			var result = '<table width="100%" height="80px" border="1" style="border:1px solid #999999;border-collapse:collapse;"><tr>';
			for (var i = 0; i < array_middle.length; i++) {
				result += '<td';
				if (array_middle[i].trim() == '1') {
					result += ' bgcolor="#999999"><font style="color:#ffffff;font-size:10px;">'+i+'H<br>OFF</font></div></td>';
				} else if (array_middle[i].trim() == '2') {
					result += ' bgcolor="#eba9f1"><font style="color:#ffffff;font-size:10px;">'+i+'H<br>예약</font></td>';
				} else if (array_middle[i].trim() == '3') {
					result += ' bgcolor="#b523ac"><font style="color:#ffffff;font-size:10px;">'+i+'H<br>예약</font></td>';
				} else if (array_middle[i].trim() == '4') {
					result += ' bgcolor="#d4d4d4"><font style="color:#000;font-size:10px;">'+i+'H</font></td>';
				} else {
					result += '><div onclick="$(c_input_hour).val('+i+');"><table><tr><td><font style="font-size:10px;"><b>'+i+'H</b></font></td></tr></table></div></td>';
					setSelectOption(i);
				}
				if (i==11) { result += '</tr><tr>';}
			}
			result += '</tr></table></div>';

                        region_td.innerHTML = result;
			window.scrollTo(0,document.body.scrollHeight);
                },
                error : function(xhr, status, error) {
                }
        });
}

function select_date (artist_id, yy, mm, dd) {
	var post_data = 'key=check_date&year='+yy+'&month='+mm+'&day='+dd;
        add_cart('date', post_data, 1, 0);
}
function move_month () {
        var post_data = 'key=uncheck_date';
        add_cart('date', post_data, 0, 0);
}
function add_cart(key, post_data, reloadf, closef)
{
        $.ajax({
                url: '<?=$artist_directory?>/set_cart_session.php',
                data: post_data,
                type: 'POST',
                success: function(data){
			//alert(data);
			if (data == "fail") {
				$.MessageBox({
		                        buttonDone      : "확인",
                		        message         : "죄송합니다. 방금 다른 회원이 이 시간을 예약하였습니다."
		                }).done(function(){
					location.reload();
					opener.location.reload();
		                });
				return;
			} else if (data == "warring") {
                                $.MessageBox({
                                        buttonDone      : "확인",
                                        message         : "선택하신 시간은 바로 뒷예약과 가까워 출장거리, 아티스트 스케줄 등의 이유로 예약이 취소될 수 있습니다."
                                }).done(function(){
					if(reloadf) {
	        	                        location.reload();
		                        }
                	        	if (closef) {
                		                opener.location.reload();
        	                	        window.close();
		                        }
                                });
				return;
			} 
			if(reloadf) {
				location.reload();
			}
			if (closef) {
				opener.location.reload();
				window.close();
			}
                },
                error : function(xhr, status, error) {
                }
        });
}
function add_cart2(key, post_data, reloadf, closef)
{
        $.ajax({
                url: 'update_payment_log_date.php',
                data: post_data,
                type: 'POST',
                success: function(data){
                        if (data == "fail") {
                                $.MessageBox({
                                        buttonDone      : "확인",
                                        message         : "죄송합니다. 방금 다른 회원이 이 시간을 예약하였습니다."
                                }).done(function(){
                                        location.reload();
//                                        opener.location.reload();
                                });
                                return;
                        } else if (data == "warring") {
                                $.MessageBox({
                                        buttonDone      : "확인",
                                        message         : "선택하신 시간은 바로 뒷예약과 가까워 출장거리, 아티스트 스케줄 등의 이유로 예약이 취소될 수 있습니다."
                                }).done(function(){
                                        if(reloadf) {
  //                                              location.reload();
                                        }
                                        if (closef) {
    //                                            opener.location.reload();
      //                                          window.close();
                                        }
                                });
                                return;
                        } else {
                                $.MessageBox({
                                        buttonDone      : "확인",
                                        message         : "변경되었습니다."
                                }).done(function(){
                                        location.href = 'manage_my_reservation.php';
                                });
			}
                },
                error : function(xhr, status, error) {
                }
        });
}

function setParentText(){
//	var input_year = document.getElementById("c_input_year");
//	var input_month = document.getElementById("c_input_month");
//	var input_day = document.getElementById("c_input_day");
	var artist_id = '<?=$user_id?>';
	var payment_log_seq = '<?=$payment_log_seq?>';
	var input_hour = document.getElementById("c_input_hour");
	if (!input_hour.options[input_hour.selectedIndex].value) {
		return false;
	}

	var check_year = '<?=$_SESSION['gobeauty_cart_year']?>';
	var check_month = '<?=$_SESSION['gobeauty_cart_month']?>';
	var check_day = '<?=$_SESSION['gobeauty_cart_day']?>';

	var post_data = 'key=date&year='+check_year+'&month='+zero_fill(check_month,2)+'&day='+zero_fill(check_day,2)+'&hour='+zero_fill(input_hour.value,2)+'&artist_id='+artist_id+'&payment_log_seq='+payment_log_seq;
	add_cart2('date', post_data, 1, 1);	
}
function clearSelectOption () {
	var select = document.getElementById('c_input_hour');
        select.options.length = 0;
}
function setSelectOption (hour) {
	var select = document.getElementById('c_input_hour');
	select.options.add(new Option(hour, hour))
}

var check_year = '<?=$_SESSION['gobeauty_cart_year']?>';
var check_month = '<?=$_SESSION['gobeauty_cart_month']?>';
var check_day = '<?=$_SESSION['gobeauty_cart_day']?>';
var artist_id = '<?=$user_id?>';

if (check_year > '0' && check_month > '0' && check_day > '0') {
	get_hour_schedule(artist_id, check_year,check_month, check_day);
}

//hour_details.style.visibility = "hidden";
</script>


<?php include "../include/bottom.php"; ?>
