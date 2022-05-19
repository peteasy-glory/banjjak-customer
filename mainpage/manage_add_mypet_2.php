<?php include "../include/top.php"; ?>
<?php include "../include/Crypto.class.php"; ?>
<?php include "../include/MCASH_SEED.php"; ?>

<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<div class="top_menu">
    <div class="top_back"><a href="<?= $mainpage_directory ?>/manage_user_info.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div class="top_title">
        <p>마이 펫 프로필 작성</p>
    </div>
</div>
<!--div style="position:absolute;z-index:5;top:5px;right:10px;"><a href="<?= $mainpage_directory ?>/"><img src="<?= $image_directory ?>/logo.jpg" height="35px"></a></div-->

<?php
$user_id = $_SESSION['gobeauty_user_id'];
$mypet_name = $_REQUEST['mypet_name'];

$p_type = "";
$p_pet_type = "";
$p_pet_type2 = "";
$p_year = "";
$p_month = "";
$p_day = "";
$p_gender = "";
$p_weight = "";
if ($mypet_name) {
    $pet_sql = "select * from tb_mypet where customer_id = '" . $user_id . "' and name = '" . $mypet_name . "';";
    $pet_result = mysql_query($pet_sql);
    if ($pet_rows = mysql_fetch_object($pet_result)) {
        $p_type = $pet_rows->type;
        $p_pet_type = $pet_rows->pet_type;
        $p_pet_type2 = $pet_rows->pet_type2;
        $p_year = $pet_rows->year;
        $p_month = $pet_rows->month;
        $p_day = $pet_rows->day;
        $p_gender = $pet_rows->gender;
        $p_weight = $pet_rows->weight;
    }
}
?>
<div id="manage_add_mypet" class="checks">
    <div>
        <div colspan="2" align="center">
            <form action="save_mypet.php" id="next_form" method="POST">
                <input type="hidden" name="customer_id" value="<?= $rows->id ?>">
                <div width="100%" style="margin-top: 25px;">
                    <div class="mp_wrap box_line">
                        <div class="mp_title box_title">펫 이름</div>
                        <div class="mp_entry">
                            <input type="text" placeholder="펫이름 입력" name="mypet_name" id="mypet_name" value="">
                        </div>
                    </div>
                    <div class="mp_wrap box_line">
                        <div class="mp_title box_title">품종</div>
                        <div>
                            <div class="mp_radio2">
                                <label for="pet_dog" class="radio"><input class="radio_pet_kind" type="radio" name="pet_kind" id="pet_dog" value="dog" onclick="get_pet_type_list();" <?php
                                                                                                                                                                                        if (!$p_type || $p_type == "dog") {
                                                                                                                                                                                            echo "checked";
                                                                                                                                                                                        }
                                                                                                                                                                                        ?> />
                                    <img id="pet_kind_dog_img" src="<?= $image_directory ?>/dog_select.png" height="70px" title="멍멍이">
                                </label>
                                <label for="pet_cat" class="radio"><input class="radio_pet_kind" type="radio" name="pet_kind" id="pet_cat" value="cat" onclick="get_pet_type_list();" <?php
                                                                                                                                                                                        if ($p_type == "cat") {
                                                                                                                                                                                            echo "checked";
                                                                                                                                                                                        }
                                                                                                                                                                                        ?> />
                                    <img id="pet_kind_cat_img" src="<?= $image_directory ?>/cat_none.png" height="70px" title="야옹이">
                                </label>
                            </div>
                            <div class="mp_kind">
                                <select name="mypet_type" id="mypet_type" class="select_wrap"></select><br>
                                <input style="display:none;" type="text" placeholder="품종입력" name="mypet_type2" id="mypet_type2" value="<?= $p_pet_type2 ?>">
                            </div>
                        </div>
                    </div>
                    <div class="mp_wrap box_line">
                        <div class="mp_title box_title">생일</div>
                        <div class="mp_select_2">
                            <select name="mypet_b_year" id="mypet_b_year" class="select_wrap2">
                                <?php
                                for ($i = intval(date("Y")); $i > intval(date("Y")) - 40; $i--) {
                                    echo "<option value='$i'";
                                    if ($i == intval($p_year)) {
                                        echo "selected";
                                    }
                                    echo ">$i</option>";
                                }
                                ?>
                            </select>년
                            <select name="mypet_b_month" id="mypet_b_month" class="select_wrap2">
                                <?php
                                for ($i = 1; $i < 13; $i++) {
                                    echo "<option value='$i'";
                                    if ($i == intval($p_month)) {
                                        echo "selected";
                                    }
                                    echo ">$i</option>";
                                }
                                ?>
                            </select>월
                            <select name="mypet_b_day" id="mypet_b_day" class="select_wrap2">
                                <?php
                                for ($i = 1; $i < 32; $i++) {
                                    echo "<option value='$i'";
                                    if ($i == intval($p_day)) {
                                        echo "selected";
                                    }
                                    echo ">$i</option>";
                                }
                                ?>
                            </select>일
                        </div>
                    </div>
                    <div class="mp_wrap box_line">
                        <div class="mp_title box_title">성별</div>
                        <div class="mp_radio">
                            <label for="check_rad1"><input type="radio" id="check_rad1" name="gender" value="남아" />
                                <div class="chk_rad">남아</div>
                            </label>
                            <label for="check_rad2"><input type="radio" id="check_rad2" name="gender" value="여아" />
                                <div class="chk_rad">여아</div>
                            </label>
                        </div>
                    </div>
                    <div class="mp_wrap box_line">
                        <div class="mp_title box_title">중성화 여부</div>
                        <div class="mp_radio">
                            <label for="check_X"><input type="radio" id="check_X" name="neutral" value="0" />
                                <div class="chk_rad">X</div>
                            </label>
                            <label for="check_O"><input type="radio" id="check_O" name="neutral" value="1" />
                                <div class="chk_rad">O</div>
                            </label>
                        </div>
                    </div>
                    <div class="mp_wrap box_line">
                        <div class="mp_title box_title">몸무게</div>
                        <?php
                        $p_w1 = "";
                        $p_w2 = "";
                        if ($p_weight) {
                            $array_w = explode(".", $p_weight);
                            $p_w1 = $array_w[0];
                            $p_w2 = $array_w[1];
                        }
                        ?>
                        <div class="mp_select_3">
                            <select name="mypet_weight1" id="mypet_weight1">
                                <?php
                                for ($i = 0; $i < 51; $i++) {
                                    echo "<option value='$i'";
                                    if ($i == intval($p_w1)) {
                                        echo "selected";
                                    }
                                    echo ">$i</option>";
                                }
                                ?>
                            </select>
                            <font style="font-size:20px;"><b>.</b></font> <select name="mypet_weight2" id="mypet_weight2">
                                <?php
                                for ($i = 0; $i < 10; $i++) {
                                    echo "<option value='$i'";
                                    if ($i == intval($p_w2)) {
                                        echo "selected";
                                    }
                                    echo ">$i</option>";
                                }
                                ?>
                            </select>
							kg
                        </div>
                    </div>
                    <div class="mp_wrap box_line">
                        <div class="mp_title box_title">미용 경험</div>
                        <div class="mp_select">
                            <select id="beauty_exp" name="beauty_exp">
                                <option value="">선택</option>
                                <option value="없음">없음</option>
                                <option value="1회">1회</option>
                                <option value="2회">2회</option>
                                <option value="3회">3회 이상</option>
                            </select>
                        </div>
                    </div>
                    <div class="mp_wrap box_line">
                        <div class="mp_title box_title">싫어하는 부위</div>
                        <div class="mp_chk">
                            <div class="chk_wrap1">
                                <label for="dt_eye"><input type="checkbox" id="dt_eye" name="check_dt[]" onclick="check_dt()" value="dt_eye" />
                                    <div class="chk_art">눈</div>
                                </label>
                                <label for="dt_nose"><input type="checkbox" id="dt_nose" name="check_dt[]" onclick="check_dt()" value="dt_nose" />
                                    <div class="chk_art">코</div>
                                </label>
                                <label for="dt_mouth"><input type="checkbox" id="dt_mouth" name="check_dt[]" onclick="check_dt()" value="dt_mouth" />
                                    <div class="chk_art">입</div>
                                </label>
                                <label for="dt_ear"><input type="checkbox" id="dt_ear" name="check_dt[]" onclick="check_dt()" value="dt_ear" />
                                    <div class="chk_art">귀</div>
                                </label>
                                <label for="dt_neck"><input type="checkbox" id="dt_neck" name="check_dt[]" onclick="check_dt()" value="dt_neck" />
                                    <div class="chk_art">목</div>
                                </label>
                            </div>
                            <div class="chk_wrap2">
                                <label for="dt_body"><input type="checkbox" id="dt_body" name="check_dt[]" onclick="check_dt()" value="dt_body" />
                                    <div class="chk_art">몸통</div>
                                </label>
                                <label for="dt_leg"><input type="checkbox" id="dt_leg" name="check_dt[]" onclick="check_dt()" value="dt_leg" />
                                    <div class="chk_art">다리</div>
                                </label>
                                <label for="dt_tail"><input type="checkbox" id="dt_tail" name="check_dt[]" onclick="check_dt()" value="dt_tail" />
                                    <div class="chk_art">꼬리</div>
                                </label>
                                <label for="dt_genitalia"><input type="checkbox" id="dt_genitalia" name="check_dt[]" onclick="check_dt()" value="dt_genitalia" />
                                    <div class="chk_art">생식기</div>
                                </label>
                                <label for="nothing"><input type="checkbox" id="nothing" name="check_dt[]" onclick="check_dt_nothing()" value="nothing" checked />
                                    <div class="chk_art">없음</div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mp_wrap box_line">
                        <div class="mp_title box_title">예방 접종</div>
                        <div class="mp_select">
                            <select id="vaccination" name="vaccination">
                                <option value="">선택</option>
                                <option value="2차 이하">2차 이하</option>
                                <option value="3차 완료">3차 완료</option>
                                <option value="4차 완료">4차 완료</option>
                                <option value="5차 완료">5차 완료</option>
                            </select>
                        </div>
                    </div>
					<?php if($user_id == "ulmo26@gmail.com"){ ?>
					<div class="mp_wrap box_line">
						<div class="mp_title box_title">질병기록</div>
						<div class="mp_chk3">
							<label for="heart_trouble"><input type="checkbox" id="heart_trouble" name="check_special[]" value="heart_trouble" />
								<div class="chk_spe">심장질환</div>
							</label>
							<label for="dermatosis"><input type="checkbox" id="dermatosis" name="check_special[]" value="dermatosis" />
								<div class="chk_spe">피부병</div>
							</label>
							<select id="luxation" name="luxation">
								<option value="">슬개골탈구</option>
								<option value="없음">없음</option>
								<option value="1기">1기</option>
								<option value="2기">2기</option>
								<option value="3기">3기</option>
								<option value="4기">4기</option>
							</select>
						</div>
                        <div class="mp_sn">
                            <textarea id="etc_for_disease" name="etc_for_disease" class="specialnote" rows="3"></textarea>
                        </div>
					</div>
					<div class="mp_wrap box_line">
						<div class="mp_title box_title">특이사항</div>
                        <div class="mp_chk3">
                            <label for="bite"><input type="checkbox" id="bite" name="check_special[]" value="bite" />
                                <div class="chk_spe">입질</div>
                            </label>
                            <label for="marking"><input type="checkbox" id="marking" name="check_special[]" value="marking" />
                                <div class="chk_spe">마킹</div>
                            </label>
                            <label for="mounting"><input type="checkbox" id="mounting" name="check_special[]" value="mounting" />
                                <div class="chk_spe">마운팅</div>
                            </label>
                        </div>
                        <div class="mp_sn">
                            <textarea id="etc_for_owner" name="etc_for_owner" class="specialnote" rows="3"></textarea>
                        </div>
					</div>
					<?php }else{ ?>
                    <div class="mp_wrap box_line">
                        <div class="mp_title box_title">입질</div>
                        <div class="mp_select">
                            <select id="bite" name="bite">
                                <option value="">선택</option>
                                <option value="0">안해요</option>
                                <option value="1">해요</option>
                            </select>
                        </div>
                    </div>
                    <div class="mp_wrap box_line">
                        <div class="mp_title box_title">슬개골 탈구</div>
                        <div class="mp_select">
                            <select id="luxation" name="luxation">
                                <option value="">선택</option>
                                <option value="없음">없음</option>
                                <option value="1기">1기</option>
                                <option value="2기">2기</option>
                                <option value="3기">3기</option>
                                <option value="4기">4기</option>
                            </select>
                        </div>
                    </div>
                    <div class="mp_wrap box_line">
                        <div class="mp_title box_title">특이사항</div>
                        <div class="mp_chk2">
                            <label for="dermatosis"><input type="checkbox" id="dermatosis" name="check_special[]" value="dermatosis" />
                                <div class="chk_spe">피부병</div>
                            </label>
                            <label for="heart_trouble"><input type="checkbox" id="heart_trouble" name="check_special[]" value="heart_trouble" />
                                <div class="chk_spe">심장질환</div>
                            </label>
                            <label for="marking"><input type="checkbox" id="marking" name="check_special[]" value="marking" />
                                <div class="chk_spe">마킹</div>
                            </label>
                            <label for="mounting"><input type="checkbox" id="mounting" name="check_special[]" value="mounting" />
                                <div class="chk_spe">마운팅</div>
                            </label>
                        </div>
                        <div class="mp_sn">
                            <textarea id="etc_for_owner" name="etc_for_owner" class="specialnote" rows="3"></textarea>
                        </div>
                    </div>
					<?php } ?>
                    <div>
                        <div height="10px"></div>
                    </div>
                    <div>
                        <div align="center" colspan="2">
                            <input type="button" onclick="check_all();" class="cell_confirm" value="저 장"></input>
                        </div>
                    </div>
                    <div>
                        <div height="10px"></div>
                    </div>
                </div>
            </form>
            <script>
                var select = document.getElementById('mypet_type');

                function get_pet_type_list() {
                    var pet_kind = $('input[name="pet_kind"]:checked').val();
                    var post_data = 'pet_type=' + pet_kind;

                    if (pet_kind == 'dog') {
                        $("#pet_kind_dog_img").attr('src', '../images/dog_pet.png');
                        $("#pet_kind_cat_img").attr('src', '../images/cat_back_n.png');
                    } else if (pet_kind == 'cat') {
                        $("#pet_kind_dog_img").attr('src', '../images/dog_back_n.png');
                        $("#pet_kind_cat_img").attr('src', '../images/cat_pet.png');
                    } else {
                        $("#pet_kind_dog_img").attr('src', '../images/dog_pet.png');
                        $("#pet_kind_cat_img").attr('src', '../images/cat_back_n.png');
                    }

                    $.ajax({
                        url: '<?= $mainpage_directory ?>/get_pet_type.php',
                        data: post_data,
                        type: 'POST',
                        success: function(data) {
                            var array_middle = data.split(",");
                            var select = document.getElementById('mypet_type');
                            select.options.length = 0; // clear out existing items
                            for (var i = 0; i < array_middle.length; i++) {
                                var d = array_middle[i];
                                d = $.trim(d);
                                select.options.add(new Option(d, d));
                                if (d == '<?= trim($p_pet_type) ?>') {
                                    $("#mypet_type").val(d.trim()).prop("selected", true);
                                }

                            }
                            //$("#middle_region").val().prop("selected", true);
                        },
                        error: function(xhr, status, error) {}
                    });
                }

                get_pet_type_list();
                select.addEventListener('change', function(e) {
                    if (select.value.trim() == "기타") {
                        document.getElementById("mypet_type2").style.display = "block";
                    } else {
                        document.getElementById("mypet_type2").style.display = "none";
                    }
                });

                function check_dt() {
                    if ($("#dt_eye, #dt_nose, #dt_mouth, #dt_ear, #dt_neck, #dt_body, #dt_leg, #dt_tail, #dt_genitalia").is(":checked")) {
                        $("#nothing").prop('checked', false);
                    } else {
                        $("#nothing").prop('checked', true);
                    }
                }

                function check_dt_nothing() {
                    $("#dt_eye, #dt_nose, #dt_mouth, #dt_ear, #dt_neck, #dt_body, #dt_leg, #dt_tail, #dt_genitalia").prop('checked', false);
                    if ($("#dt_eye, #dt_nose, #dt_mouth, #dt_ear, #dt_neck, #dt_body, #dt_leg, #dt_tail, #dt_genitalia").is(":checked")) {
                        $("#nothing").prop('checked', false);
                    } else {
                        $("#nothing").prop('checked', true);
                    }
                }

                function check_all() {
                    if (!$('#mypet_name').val()) {
                        $.MessageBox({
                            buttonDone: "확인",
                            message: "이름을 입력해주세요."
                        }).done(function() {
                            // $('#mypet_name').focus();
                        });
                        return;
                    } else if (select.value.trim() == "기타") {
                        if (!$('#mypet_type2').val()) {
                            $.MessageBox({
                                buttonDone: "확인",
                                message: "품종을 입력해주세요."
                            }).done(function() {});
                            return;
                        }
					} else if ($('#check_rad1').is(":checked") == false && $('#check_rad2').is(":checked") == false) {
						$.MessageBox({
                            buttonDone: "확인",
                            message: "성별을 선택하세요."
                        }).done(function() {});
						return;
					} else if ($('#check_X').is(":checked") == false && $('#check_O').is(":checked") == false) {
						$.MessageBox({
                            buttonDone: "확인",
                            message: "중성화 여부를 선택하세요."
                        }).done(function() {});
						return;
                    } else if ($('#mypet_weight1').val() == '0') {
                        $.MessageBox({
                            buttonDone: "확인",
                            message: "몸무게를 바르게 선택하세요."
                        }).done(function() {});
                        return;
                    } else if ($('#beauty_exp').val() == "") {
                        $.MessageBox({
                            buttonDone: "확인",
                            message: "미용 경험을 선택해주세요."
                        }).done(function() {});
                        return;
                    } else if ($('#vaccination').val() == "") {
                        $.MessageBox({
                            buttonDone: "확인",
                            message: "예방 접종을 선택해주세요."
                        }).done(function() {});
                        return;
                    } else if ($('#bite').val() == "") {
                        $.MessageBox({
                            buttonDone: "확인",
                            message: "입질 여부를 선택해주세요."
                        }).done(function() {});
                        return;
                    } else if ($('#luxation').val() == "") {
                        $.MessageBox({
                            buttonDone: "확인",
                            message: "탈구 여부를 선택해주세요."
                        }).done(function() {});
                        return;
                    }

                    // document.getElementById('next_form').submit();

                    $("#loading").show();
                    $.ajax({
                        cache: false,
                        url: "save_mypet.php",
                        type: 'POST',
                        data: $("#next_form").serialize(),
                        success: function(data) {
                            location.href = "<?= $mainpage_directory ?>/mainpage_my_menu_2.php";
                        },
                        error: function(xhr, status) {
                            location.href = "<?= $mainpage_directory ?>/mainpage_my_menu_2.php";
                            // alert(xhr + " : " + status);
                        }
                    });
                }
            </script>
        </div>
    </div>
</div>
<br><br><br><br>
<?php include "../include/bottom.php"; ?>