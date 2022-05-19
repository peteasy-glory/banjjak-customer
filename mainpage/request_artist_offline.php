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
    @font-face {
        font-family: 'SCDream2';
        src: url("../fonts/SCDream2.otf");
    }

    html,
    div,
    p,
    button {
        font-weight: bold;
        font-family: 'SCDream2';
    }

    a {
        text-decoration: none;
    }

    a:link {
        color: white;
    }

    a:visited {
        color: white;
    }

    a:hover {
        color: white;
    }

    a:active {
        color: white;
    }

    .my_reservation {
        position: relative;
        z-index: 5;
        width: 100%;
        text-align: left;
        padding: 10px;
        border: 1 solid #999999;
        margin: auto;
    }

    .change_reservation {

        background-color: #f5bf2e;
        -webkit-border-top-left-radius: 6px;
        -moz-border-radius-topleft: 6px;
        border-top-left-radius: 6px;
        -webkit-border-top-right-radius: 6px;
        -moz-border-radius-topright: 6px;
        border-top-right-radius: 6px;
        -webkit-border-bottom-right-radius: 6px;
        -moz-border-radius-bottomright: 6px;
        border-bottom-right-radius: 6px;
        -webkit-border-bottom-left-radius: 6px;
        -moz-border-radius-bottomleft: 6px;
        border-bottom-left-radius: 6px;
        text-indent: 0;
        border: 1px solid #f5bf2e;
        display: inline-block;
        color: #ffffff;
        font-family: 'SCDream2';
        font-size: 18px;
        font-weight: bold;
        font-style: normal;
        height: 40px;
        line-height: 36px;
        width: 100%;
        text-decoration: none;
        text-align: center;
    }

    .cancel_reservation {

        background-color: #f5bf2e;
        -webkit-border-top-left-radius: 6px;
        -moz-border-radius-topleft: 6px;
        border-top-left-radius: 6px;
        -webkit-border-top-right-radius: 6px;
        -moz-border-radius-topright: 6px;
        border-top-right-radius: 6px;
        -webkit-border-bottom-right-radius: 6px;
        -moz-border-radius-bottomright: 6px;
        border-bottom-right-radius: 6px;
        -webkit-border-bottom-left-radius: 6px;
        -moz-border-radius-bottomleft: 6px;
        border-bottom-left-radius: 6px;
        text-indent: 0;
        border: 1px solid #f5bf2e;
        display: inline-block;
        color: #777777;
        font-family: 'SCDream2';
        font-size: 18px;
        font-weight: bold;
        font-style: normal;
        height: 40px;
        line-height: 36px;
        width: 88px;
        text-decoration: none;
        text-align: center;
    }


    .event_title {
        color: #000;
        font-size: 20px;
        font-weight: bold;
    }

    .event_body {
        color: #000;
        font-size: 16px;
    }

    .go_button {
        background-color: #f5bf2e;
        -webkit-border-top-left-radius: 6px;
        -moz-border-radius-topleft: 6px;
        border-top-left-radius: 6px;
        -webkit-border-top-right-radius: 6px;
        -moz-border-radius-topright: 6px;
        border-top-right-radius: 6px;
        -webkit-border-bottom-right-radius: 6px;
        -moz-border-radius-bottomright: 6px;
        border-bottom-right-radius: 6px;
        -webkit-border-bottom-left-radius: 6px;
        -moz-border-radius-bottomleft: 6px;
        border-bottom-left-radius: 6px;
        text-indent: 0;
        border: 0px solid #f5bf2e;
        display: inline-block;
        color: #ffffff;
        font-family: ;
        font-size: 20px;
        font-weight: bold;
        font-style: normal;
        height: 47px;
        line-height: 47px;
        width: 100%;
        text-decoration: none;
        text-align: center;
    }

    input[type=text],
    input[type=number],
    select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-sizing: border-box;
    }

	input[type=text],
	input[type=number],
	input[type=button] { 
		-webkit-appearance: none;
	}

    .top_menu {
        height: 51px;
        position: relative;
    }

    .top_title {
        width: 100%;
        height: 19px;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        padding: 15px 0px 15px 0px;
        display: table;
        border-bottom: 0.5px solid #e1e1e1;
    }

    .top_title p {
        margin: 0px;
    }

    .top_back {
        position: absolute;
        bottom: 11px;
        left: 10px;
    }

	ul { list-style: none; margin: 0px; padding: 0px; }
	ul.table { display: table; width: 100%; font-size: 16px; }
	ul.table li { position: relative; display: table-cell; width: 33%; text-align: center; vertical-align: middle; }
	ul.table li:last-child { width: 34px; }
	ul.table li input[type='checkbox'] { display: none; }
	ul.table li input[type='checkbox']+label { display: inline-block; width: calc(100% - 4px); height: 30px; line-height: 30px; border: 1px solid #ccc; background-color: #eee; border-radius: 5px; }
	ul.table li input[type='checkbox']:checked+label { background-color: #f5bf2e; border: 1px solid #f5bf2e; color: #fff; }
</style>
<div class="top_menu">
    <div class="top_back"><a href="<?= $mainpage_directory ?>/request_artist_region.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div class="top_title">
        <p>반짝 입점 신청</p>
    </div>
</div>

<form action="<?= $mainpage_directory ?>/insert_artist_regist.php" method="POST" id="shop_form">
    <input type="hidden" name="step" value="4" />
    <table style="width:90%; margin: 0px auto; margin-top: 20px; font-size:20px;">
		<tr>
            <td height="10px"></td>
        </tr>
        <tr>
            <td colspan="2" style="font-size:17px;">
                <b>제공 서비스 선택 (복수 선택 가능)</b>
            </td>
        </tr>
        <tr>
            <td height="5px"></td>
        </tr>
        <tr>
            <td colspan="2">
				<ul class="table">
					<li>
						<input type="checkbox" id="service_type_1" name="service_type[]" value="1" onclick="check()" />
						<label for="service_type_1">미용</label>
					</li>
					<li>
						<input type="checkbox" id="service_type_2" name="service_type[]" value="2" onclick="check()" />
						<label for="service_type_2">호텔</label>
					</li>
					<li>
						<input type="checkbox" id="service_type_3" name="service_type[]" value="3" onclick="check()" />
						<label for="service_type_3">유치원</label>
					</li>
				</ul>
            </td>
        </tr>
        <tr>
            <td height="10px"></td>
        </tr>
        <tr>
            <td colspan="2" style="font-size:17px;">
                <b>오프라인 매장 유/무</b>
            </td>
        </tr>
        <tr>
            <td height="5px"></td>
        </tr>
        <tr>
            <td style="font-size:15px; text-align: right; padding-right: 6px;" width="11%">
                유 <input type="radio" id="yes" name="offline_yesno" value="1" onclick="javascript:show_pop();" checked>
            </td>
            <td style="font-size:15px;text-align: left; padding-left: 6px;" width="50%">
                무 <input type="radio" id="no" name="offline_yesno" value="0" onclick="javascript:fadout_pop();">
            </td>
        </tr>
        <tr>
            <td height="5px"></td>
        </tr>
        <tr>
            <td colspan="2" style="font-size:14px;">
                <div style="padding:4px;margin:4px;display:none;" id="offline_pop">
                    <table width="100%" style="font-size:16px;">
                        <tr>
                            <td height="30px"><b>매장주소</b></td>
                            <td id="offline_address">
                                <a href="#" onclick="location.href = 'find_address.php?backurl=request_artist_offline_2&chk=' + chk">
                                    <font style="font-size:16px;color:#000000;">
                                        <?php
                                        if (
                                            $_SESSION['gobeauty_address'] && $_SESSION['gobeauty_rest_address']
                                            && $_SESSION['gobeauty_address'] != "" && $_SESSION['gobeauty_rest_address'] != ""
                                        ) {
                                            ?>
                                            <?php
                                                $address = $_SESSION['gobeauty_address'];
                                                echo str_replace("|", "<br>", $address);
                                                echo "<br>";
                                                echo $_SESSION['gobeauty_rest_address'];
                                                ?>
                                        <?php
                                        } else {
                                            ?>
                                            <font style="font-size:16px;color:#999999;">
                                                <div style="border:1px solid #999999;padding:12px 15px;border-radius: 6px;">주소찾기 (선택해 주세요.)</div>
                                            </font>
                                        <?php
                                        }
                                        ?>
                                    </font>
                                </a>
                            </td>
                            <!--td><input type="text" name="offline_address" id="offline_address" style="width:100%;font-size:17px;" placeholder="선택해주세요" onclick="open_address_daum();" required></td-->
                        </tr>
                        <tr>
                            <td width="30%"><b>매 장 명</b></td>
                            <td width="70%"><input type="text" name="offline_shopname" id="offline_shopname" style="width:100%;font-size:16px;" placeholder="매장이름입력" required></td>
                        </tr>
                        <tr>
                            <td><b>매장번호</b></td>
                            <td><input type="number" name="offline_shop_cellphone" id="offline_shop_cellphone" style="width:100%;font-size:16px;" placeholder="숫자만입력" required></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td height="10px"></td>
        </tr>
        <tr>
            <td colspan="2" style="font-size:16px;">
                <script>
                    function go_next() {
                        var selected_type = $('input[name="offline_yesno"]:checked').val();
                        if (selected_type == 0) {
                            document.getElementById('shop_form').submit();
                            return true;
                        }

                        var bb = document.getElementById('offline_shopname').value;
                        if (!bb) {
                            $.MessageBox({
                                buttonDone: "확인",
                                message: "매장명을 입력해주세요."
                            }).done(function() {});
                            return false;
                        }
                        var cc = document.getElementById('offline_shop_cellphone').value;
                        if (!cc) {
                            $.MessageBox({
                                buttonDone: "확인",
                                message: "전화번호를 입력해주세요."
                            }).done(function() {});
                            return false;
                        }
						var dd = $('input[name="service_type[]"]:checked').val();
						if (typeof dd == "undefined") {
                            $.MessageBox({
                                buttonDone: "확인",
                                message: "제공 서비스를 1개 이상 입력해주세요."
                            }).done(function() {});
                            return false;
						}
                        document.getElementById('shop_form').submit();
                    }

					// 업종 체크시 넘길 번호 생성
					var chk = 0;
					function check(){
						if(document.getElementById('service_type_1').checked == true) {
							if(document.getElementById('service_type_2').checked == true) {
								if(document.getElementById('service_type_3').checked == true) {
									chk = 7;
								}else if(document.getElementById('service_type_3').checked == false) {
									chk = 4;
								}
							}else if(document.getElementById('service_type_2').checked == false) {
								if(document.getElementById('service_type_3').checked == true) {
									chk = 6;
								}else if(document.getElementById('service_type_3').checked == false) {
									chk = 1;
								}
							}
						}else if(document.getElementById('service_type_1').checked == false) {
							if(document.getElementById('service_type_2').checked == true) {
								if(document.getElementById('service_type_3').checked == true) {
									chk = 5;
								}else if(document.getElementById('service_type_3').checked == false) {
									chk = 2;
								}
							}else if(document.getElementById('service_type_2').checked == false) {
								if(document.getElementById('service_type_3').checked == true) {
									chk = 3;
								}
							}
						}
						console.log("chk = " + chk);
					}

					// 주소의 파라미터 값 변수로 가져오기
					function getParameterByName(name) {
						name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
						var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
							results = regex.exec(location.search);
						return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
					}
					var resultChk = getParameterByName('chk');
					console.log("resultChk=" + resultChk);
					
					// 주소찾고 돌아왔을때 항목 선택
					if(resultChk == 1) {
						document.getElementById('service_type_1').checked = true;
					}else if(resultChk == 2) {
						document.getElementById('service_type_2').checked = true;
					}else if(resultChk == 3) {
						document.getElementById('service_type_3').checked = true;
					}else if(resultChk == 4) {
						document.getElementById('service_type_1').checked = true;
						document.getElementById('service_type_2').checked = true;
					}else if(resultChk == 5) {
						document.getElementById('service_type_2').checked = true;
						document.getElementById('service_type_3').checked = true;
					}else if(resultChk == 6) {
						document.getElementById('service_type_1').checked = true;
						document.getElementById('service_type_3').checked = true;
					}else if(resultChk == 7) {
						document.getElementById('service_type_1').checked = true;
						document.getElementById('service_type_2').checked = true;
						document.getElementById('service_type_3').checked = true;
					}

                </script>
                <input type="button" onclick="go_next()" href="#" class="change_reservation" value="다 &nbsp; &nbsp;&nbsp;&nbsp; 음">
            </td>
        </tr>
    </table>
</form>

<script>
    function show_pop() {
        $('#offline_pop').show();
    }

    function fadout_pop() {
        $('#offline_pop').fadeOut();
    }

    function open_address_daum() {
        window.name = "parentForm";
        openWin = window.open("find_address.php", "childForm", "window=100%, height=100%, resizeable=no, scrollbars=no");
    }
    show_pop();
</script>

<br>
<br><br><br>
<br><br><br>
<?php include "../include/bottom.php"; ?>