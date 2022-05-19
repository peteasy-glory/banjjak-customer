<?php include "../include/top.php"; ?>
<?php include "../include/Crypto.class.php"; ?>
<?php include "../include/App.class.php"; ?>

<?php
$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}
?>

<?php
$is_android = 0;
$app = new App();
if ($app->is_app() == 1) {
    $is_android = 1;
}
?>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new_2.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new_2.css') ?>">

<script>
    function check_next() {
        var nickname = document.getElementById('user_nickname').value;
        if (!nickname) {
            return false;
        }

        var YokList = new Array('개새끼', '개색기', '개색끼', '개자식', '개보지', '개자지', '개년', '개걸래', '개걸레', '씨발', '씨팔', '씨부랄', '씨바랄', '씹창', '씹탱', '씹보지', '씹자지', '씨방세', '씨방새', '씨펄', '시펄', '>십탱', '씨박', '썅', '쌍놈', '쌍넘', '싸가지', '쓰벌', '씁얼', '상넘이', '상놈의', '상놈이', '상놈을', '좆', '좃', '존나게', '존만한', '같은년', '넣을년', '버릴년', '부랄년', '바랄년', '미친년', '니기미', '니미씹', '니미씨', '니미럴', '니미랄', '호로', '후레아들', '호로새끼', '후레자식', '후래자식', '후라들년', '후라들넘', '빠구리', 'script', 'iframe', '병신', '보지', '자지', '씨.발', '졸라', '씹', '씹할', 'Go뷰티', '뷰티고', '뷰티야', 'go뷰티', 'Go펫', 'GO펫', '고펫');
        var Tmp;
        for (i = 0; i < YokList.length; i++) {
            Tmp = nickname.toLowerCase().indexOf(YokList[i]);
            if (Tmp >= 0) {
                $.MessageBox({
                    buttonDone: "확인",
                    message: "<center><font style='font-size:15px;font-weight:bold;'>금지어(" + YokList[i] + ")가 포함되어 있습니다.<br> 다시 작성해주세요.</font></center>"
                }).done(function() {});
                return false;
            }
        }

        document.getElementById('shop_form').submit();
        return true;
    }
</script>

<?php
$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

$login_insert_sql = "select * from tb_customer where id = '" . $user_id . "'";
$result = mysql_query($login_insert_sql);

if ($result_datas = mysql_fetch_object($result)) {
    $nickname = $result_datas->nickname;
    $photo = $result_datas->photo;
    $cellphone = $result_datas->cellphone;
    $cellphone_confirm = $result_datas->cellphone_confirm;
    $id = $result_datas->id;
    $email_confirm = $result_datas->email_confirm;
    $mileage = $result_datas->mileage;
    $my_shop_flag = $result_datas->my_shop_flag;
    $push_option = $result_datas->push_option;
    $admin_flag = $result_datas->admin_flag;

    $crypto = new Crypto();
    $cellphone = $crypto->decode(trim($cellphone), $access_key, $secret_key);
    ?>
    <form action="save_user_info.php" id="shop_form" method="POST">
        <div class="top_menu">
            <div class="top_back"><a href="/pet/mainpage/mainpage_my_menu.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
            <div class="top_title">
                <p>마이펫 관리</p>
            </div>
<!--             <div class="top_save"><a href="#" onclick="return check_next ();" class="save_info">저장</a></div> -->
        </div>



        <center id="manage_user_info">
            <div style="width:100%; margin: 0px auto;">

                <div class="user_info_wrap2">
                    <div width="40%" height="200px" valign="bottom" style="padding:5px;">
                        <!--div id="main_front_text">사진이 없습니다.</div-->
                        <div><img id="main_front_image" width="100%" /></div>
                        <div>
                            <?php
                                if ($photo) {
                                    ?>
                                <div class="profile_wrap"><img src="<?= $photo ?>" id="main_front_image" /></div>
                            <?php
                                } else {
                                    echo "<font style='font-size:12px;'>사진을 등록해 주세요.</font>";
                                }
                                ?>
                            <div width="100%" class="filebox">
                                <label>
                                    <span style="float:right;width:100%;" id="fileuparea">
                                        <button class="change_photo" type="button" name="button1" onclick="javascript:MemofocusNcursor('gobeauty_profile');">
                                            사진 변경
                                        </button>
                                    </span>
                                    <span style="float:right;width:100%;display:none;" id="fileuparea2">
                                        <button type="button" onclick="javascript:galleryup('gobeauty_profile');">
                                            <span class="gallery_load">사진 변경</span>
                                            <!-- <img src="<?= $image_directory ?>/photo3.png" align="absmiddle" style="width:33px">&nbsp; -->
                                        </button>
                                        <!-- <button type="button" onclick="javascript:cameraup('gobeauty_profile');">
                                            <img src="<?= $image_directory ?>/photo2.png" align="absmiddle" style="width:33px">&nbsp;
                                        </button> -->
                                    </span>
                                    <span id="fileuplodingarea" style="display:none;float:right">
                                    </span>

                                </label>
                            </div>
                        </div>
                        <div style="display:block;position:absolute;top:-50px"><input type="file" accept="image/*" name=imgupfile id=addimgfile></div>
                        <script language="javascript">
                            var last_upload_type = "gobeauty_profile";

                            $('#addimgfile').bind('change', function(e) {
                                $("#fileuparea").hide();
                                $("#fileuplodingarea").show();
                                var ext = $('#addimgfile').val().split('.').pop().toLowerCase();
                                if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                                    alert('gif,png,jpg,jpeg 파일만 업로드 할수 있습니다.');
                                    $("#fileuparea").show();
                                    $("#fileuplodingarea").hide();
                                    return;
                                }

                                var filename = $("input[name=imgupfile]")[0].files[0].name;
                                var newfilename = GetPhotoFilename('customer_photo', '<?= $user_id ?>', filename);
                                var formData = new FormData();

                                formData.append("myfile", $("input[name=imgupfile]")[0].files[0]);
                                formData.append("filepath", newfilename);
                                formData.append("petname", last_upload_type);

                                $.ajax({
                                    url: 'upload_user_photo.php',
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    type: 'POST',
                                    success: function(data) {
                                        // alert(data);

                                        $("#fileuparea").show();
                                        $("#fileuplodingarea").hide();

                                        if (/(MSIE|Trident)/.test(navigator.userAgent)) {
                                            // ie 일때 input[type=file] init.
                                            $("#addimgfile").replaceWith($("#addimgfile").clone(true));
                                        } else {
                                            // other browser 일때 input[type=file] init.
                                            $("#addimgfile").val("");
                                        }

                                        addwidth = " style='width:30%' ";
                                        mkimg_str = "<img src='/upload/" + newfilename + "' " + addwidth + ">";

                                        location.reload();
                                    },
                                    error: function(xhr, status, error) {
                                        // alert(error+"에러발생");
                                    }
                                });


                            });
                            //안드로이드 앱일경우애만 ... 실행..
                            $(document).ready(function() {
                                if (<?= $is_android ?> == 1) {
                                    $("#fileuparea").hide();
                                    $("#fileuparea2").show();
                                }
                            });

                            function galleryup(name) {
                                last_upload_type = name;
                                window.Android.openGallery();
                            }

                            function cameraup(name) {
                                last_upload_type = name;
                                window.Android.openCamera();
                            }

                            //안드에서 업로드가 끝나면 무조건 호출..
                            function uploadEnd(fileName) {
                                $("#fileuparea2").hide();
                                $("#fileuplodingarea").show();

                                var newfilename = GetPhotoFilename('appupload_photo', '<?= $user_id ?>', fileName);
                                var post_data = 'filepath=' + fileName + '&newfilepath=' + newfilename + '&petname=' + last_upload_type;

                                $.ajax({
                                    url: 'upload_user_photo_byapp.php',
                                    data: post_data,
                                    type: 'POST',
                                    success: function(data) {
                                        // alert(data);

                                        $("#fileuparea2").show();
                                        $("#fileuplodingarea").hide();

                                        if (/(MSIE|Trident)/.test(navigator.userAgent)) {
                                            // ie 일때 input[type=file] init.
                                            $("#addimgfile").replaceWith($("#addimgfile").clone(true));
                                        } else {
                                            // other browser 일때 input[type=file] init.
                                            $("#addimgfile").val("");
                                        }

                                        addwidth = " style='width:30%' ";
                                        mkimg_str = "<img src='/upload/" + newfilename + "' " + addwidth + ">";

                                        location.reload();
                                    },
                                    error: function(xhr, status, error) {
                                        //alert(error + "네트워크에러");
										if(xhr.status != 0){
											alert("code = "+ xhr.status + " message = " + xhr.responseText + " error = " + error); // 실패 시 처리
										}
                                    }
                                });
                            }

                            function MemofocusNcursor(name) {
                                last_upload_type = name;

                                html = "<div id='upimgarea'></div>";
                                var sel, range;
                                if (window.getSelection) {
                                    // IE9 and non-IE
                                    sel = window.getSelection();
                                    if (sel.getRangeAt && sel.rangeCount) {
                                        range = sel.getRangeAt(0);
                                        range.deleteContents();

                                        // Range.createContextualFragment() would be useful here but is
                                        // non-standard and not supported in all browsers (IE9, for one)
                                        var el = document.createElement("div");
                                        el.innerHTML = html;
                                        var frag = document.createDocumentFragment(),
                                            node, lastNode;
                                        while ((node = el.firstChild)) {
                                            lastNode = frag.appendChild(node);
                                        }
                                        range.insertNode(frag);

                                        // Preserve the selection
                                        if (lastNode) {
                                            range = range.cloneRange();
                                            range.setStartAfter(lastNode);
                                            range.collapse(true);
                                            sel.removeAllRanges();
                                            sel.addRange(range);
                                        }
                                    }
                                } else if (document.selection && document.selection.type != "Control") {
                                    // IE < 9
                                    document.selection.createRange().pasteHTML(html);
                                }
                                $("#addimgfile").trigger("click");
                            }
                        </script>

                    </div>
                    <div width="60%">
                        <div width='90%' style="font-size:15px; margin: 0px auto;">
                            <div>
                                <div>
                                    <font style="font-size:15px;"><b><?= $name ?></b></font>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <div class="user_info">
                                        <div class="user_title"><b>닉네임</b></div>
                                        <div><input name="user_nickname" id="user_nickname" type="text" value="<?= $nickname ?>" placeholder="닉네임" required><?php if ($admin_flag) {
                                                                                                                                                                    echo "<br>[관리자]";
                                                                                                                                                                } ?></div>
                                        <div class="user_title">이메일ID</div>
                                        <div><?= $id ?></div>
                                        <div class="user_title">전화번호</div>
                                        <div style="text-decoration: none;"><?= $cellphone ?></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </form>
    <div style="height:75px;"></div>
    <a href="manage_add_mypet.php" class="gobeauty_button">마이 펫 등록/추가하기</a>
    <div style="padding:15px 0;font-family: 'NanumGothic';font-weight: bold;font-size: 12px;text-align: left;width:90%;">* 이용하시던 펫샵에서 등록한 펫을 지우면 이용이력도 삭제되오니 이름이나 기타 정보가 다르면 변경하여 이용해 주시기 바랍니다. </div>

    <?php
        $pet_sql = "select * from tb_mypet where customer_id = '" . $user_id . "';";
        $pet_result = mysql_query($pet_sql);
        while ($pet_rows = mysql_fetch_object($pet_result)) {
            $name_for_owner = $pet_rows->name_for_owner;
			$pet_name = ($name_for_owner == "")? $pet_rows->name : $name_for_owner; // 견주용 펫 이름이 null 값이면 shop용 펫 이름 가져오기
            $type = $pet_rows->type;
            $pet_type = $pet_rows->pet_type;
            $pet_type2 = $pet_rows->pet_type2;
            $year = $pet_rows->year;
            $month = $pet_rows->month;
            $day = $pet_rows->day;
            $gender = $pet_rows->gender;
            $weight = $pet_rows->weight;
            $photo = $pet_rows->photo;
			$pet_seq = $pet_rows->pet_seq;
            ?>
        <div class="user_section">
            <div width="100%" class="mypet_wrap">
                <div>
                    <div style="width: 100px; float: left; margin-right: 15px;">
                        <div style="width: 100px; height: 100px; margin-right: 10px; overflow: hidden; object-fit: cover;">
                            <?php
                                    if (!$photo || $photo == "") {
                                        if ($type == "dog") {
                                            echo "<img src='$image_directory/dog_pet.png' width='90%' title='멍멍이'>";
                                            ?>
                            <?php
                                        } else if ($type == "cat") {
                                            echo "<img src='$image_directory/cat_pet.png' width='90%' title='야옹이'>";
                                        }
                                    } else {
                                        echo "<img src='$photo' width='100%' style='width: 90px; height: 90px; object-fit: cover;'>";
                                    }
                                    ?>
                        </div>
                        <label>
                            <?php
                                    if ($is_android == 0) {
                                        ?>
                                <span style="float:right;width:100%; margin-top: 10px;">
                                    <button class="change_photo" type="button" name="button1" onclick="javascript:MemofocusNcursor('<?= $name_for_owner ?>');">사진 변경</button>
                                </span>
                            <?php
                                    } else {
                                        ?>
                                <span class="img_file">
                                    <button type="button" onclick="javascript:galleryup('<?= $name_for_owner ?>');">
                                        <img src="<?= $image_directory ?>/photo3.png" align="absmiddle" style="width:33px">&nbsp;
                                    </button>
                                    <button type="button" onclick="javascript:cameraup('<?= $name_for_owner ?>');">
                                        <img src="<?= $image_directory ?>/photo2.png" align="absmiddle" style="width:33px">&nbsp;
                                    </button>
                                </span>
                                <span id="fileuplodingarea" style="display:none;float:right">
                                    <img src='<?= $image_directory ?>/loading_s.gif' style='padding-left:0px;width:30px'>
                                </span>
                            <?php
                                    }
                                    ?>
                        </label>
                    </div>
                    <div class="mypet_info">
                        <b>이름</b> : <?= $pet_name ?><br> <!-- 견주용 펫 이름이 null 값이면 shop용 펫 이름 가져오기 -->
                        <b>품종</b> : <?= $pet_type ?> <?= $pet_type2 ?><br>
                        <?php
                                $ttttt = sprintf("%d-%d-%d", $year, $month, $day);
                                $birth_time   = strtotime($ttttt);
                                $now          = date('Ymd');
                                $birthday     = date('Ymd', $birth_time);
                                $age           = floor(($now - $birthday) / 10000);

                                $nowY = date("Y");
                                $nowM = date("m");
                                $postY = $year;
                                $postM = $month;
                                $dist = ($nowY - $postY) * 12 + ($nowM - $postM);
                                ?>
                        <b>생일</b> : <?= $year ?>.<?= $month ?>.<?= $day ?><font style="font-size:13px;"> (<?php if ($age != 0) {
                                                                                                                        echo $age . "년";
                                                                                                                    } ?><?= $dist % 12 ?>개월)</font><br>
                        <b>성별</b> : <?= $gender ?><br>
                        <b>몸무게</b> : <?= $weight ?> Kg<br>
                        <div class="mypet_btn">
                            <a href="manage_modify_mypet.php?pet_seq=<?=$pet_seq ?>" class="btn_img"><img src="../images/btn_review.png"></a>
                            <a onclick="javascript:delete_mypet('<?= $user_id ?>', '<?= $name_for_owner ?>', '<?= $pet_seq ?>');" class="btn_img"><img src="../images/btn_delete.png"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="height:5px;"></div>
    <?php
        }
        ?>

    <br><br><br><br>
    <script>
        function delete_mypet(customer_id, name, pet_seq) {
            $.MessageBox({
                buttonDone: "삭제",
                buttonFail: "취소",
                message: "[펫 삭제시 주의사항] </br></br>적립포인트,이용이력 등이 삭제되어 복구되지 않으며,</br></br> 예약된 미용건이 있다면 예약이 삭제됩니다.</br></br> (펫샵에서 등록한 펫이 있다면 수정하여 이용하시기 바랍니다.)</br></br>삭제하시겠습니까?"
            }).done(function() {
                $.ajax({
                    url: '<?= $mainpage_directory ?>/delete_mypet.php',
                    data: {
                        customer_id: customer_id,
                        pet_name: name,
						pet_seq: pet_seq
                    },
                    type: 'POST',
                    success: function(data) {
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        location.reload();
                    }
                });
            });
        }

        function selectImage(path) {
            var post_data = 'path=' + path;
            $.ajax({
                url: '<?= $shop_directory ?>/change_front_image.php',
                data: post_data,
                type: 'POST',
                success: function(data) {
                    $.MessageBox({
                        buttonDone: "확인",
                        message: data
                    }).done(function() {
                        location.reload();
                    });
                    document.getElementById('main_front_image').src = path;
                },
                error: function(xhr, status, error) {
                    $.MessageBox({
                        buttonDone: "확인",
                        message: "적용 실패."
                    }).done(function() {});
                }
            });
        }
    </script>

<?php
} else {
    ?>
    <script>
        $.MessageBox({
            buttonDone: "확인",
            message: "잘못된 접근입니다. 메인페이지로 이동합니다."
        }).done(function() {
            location.href = "<?= $mainpage_directory ?>/index.php";
        });
    </script>

<?php
}
?>

<?php include "../include/bottom.php"; ?>