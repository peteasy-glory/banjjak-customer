<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-152043924-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'UA-152043924-1');
</script>
<?php
include "../include/top.php";
include "../include/configure.php";
?>
<?php
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$is_android = 0;
if ($user_agent) {
    if (strpos($user_agent, "APP_GOBEAUTY_AND") > 0) {
        $is_android = 1;
    }
}

$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];
$payment_log_seq = $_REQUEST['payment_log_seq'];
$artist_id = $_REQUEST['artist_id'];
$backurl = $_GET["backurl"];

$memo = "";
$update_time = "";
$rating = "5";
$sql = "select * from tb_usage_reviews where customer_id='" . $user_id . "' and payment_log_seq = '" . $payment_log_seq . "' and is_delete = 0;";
$result = mysql_query($sql);
if ($rows = mysql_fetch_object($result)) {
    $memo = $rows->review;
    $rating = $rows->rating;
    $update_time = $rows->update_time;
    $review_images = $rows->review_images;
}

?>
<link type="text/css" rel="stylesheet" href="<?= $css_directory ?>/bootstrap.min.css">
<link type="text/css" rel="stylesheet" href="<?= $css_directory ?>/font-awesome.min.css">
<link type="text/css" rel="stylesheet" href="<?= $css_directory ?>/star-rating.css" media="all">
<script type="text/javascript" src="<?= $js_directory ?>/star-rating.js?<?= filemtime($upload_static_directory . $js_directory . '/star-rating.js') ?>"></script>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">

<div class="top_menu">
	<?php if($backurl){ ?>
    <div class="top_back"><a href="<?= $backurl ?>"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
	<?php }else{ ?>
    <div class="top_back"><a href="<?= $mainpage_directory ?>/manage_my_postwrite.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
	<?php } ?>
    <div class="top_title">
        <p>후기작성</p>
    </div>
</div>

<div id="write_usage_review">
<form action="insert_usage_review.php" name="reviewForm" method="post" style="border:0;margin:0" enctype="multipart/form-data" id="frm">
    <input type="hidden" name="payment_log_seq" value="<?= $payment_log_seq ?>">
    <input type="hidden" name="artist_id" value="<?= $artist_id ?>">
    <input type="hidden" name="review_images" id="review_images" value="<?= $review_images ?>">
    <div class="rev_photo">
        <!--사진 올리기 -->
        <div class="photo_01">
            <center><b>귀여운 아이사진으로<br /> 생생한 이용후기를 전하세요<b></center>
            <center>
                <span class="fileuparea">
                    <button type="button" name="button1" onclick="javascript:MemofocusNcursor();">
                        <img src="<?= $image_directory ?>/icon_photo.png" align="absmiddle" style="width:40px;">

                    </button>
                </span>

                <span class="fileuparea2" style="display:none;">
                    <button type="button" onclick="javascript:galleryup();">
                        <img src="<?= $image_directory ?>/icon_photo.png" align="absmiddle" style="width:40px;">&nbsp;

                    </button>
                    <!--<button type="button" onclick="javascript:cameraup();">
						<img src="<?= $image_directory ?>/icon_camera.png" align="absmiddle" style="width:42px;">&nbsp;
					</button>-->
                </span>
                <span id="fileuplodingarea" style="display:none;">
                    <img src='<?= $image_directory ?>/loading_s.gif' style='padding-left:0px;width:30px'>
                </span>
            </center>
        </div>

        <div class="photo_02">
            <div class="btn_photo fileuparea">
                <img src="<?= $image_directory ?>/icon_photo.png" onclick="javascript:MemofocusNcursor();">
            </div>

            <div class="btn_photo fileuparea2" style="display:none;">
                <img src="<?= $image_directory ?>/icon_photo.png" onclick="javascript:galleryup();">
            </div>
            <div class="review_img_view"></div>
        </div>

    </div>
    <div class="wrap">

        <div>
            <div>
                <div class="rev_rating"><b>평점</b></div>
                <div><input type="text" id="rating" class="rating" name="rating" value="<?= $rating ?>" data-size="xs" title="평점"></div>
            </div>
            <script>
                $('#rating').on(
                    'change',
                    function() {
                        console.log('Rating selected: ' + $(this).val());
                        document.getElementById('rating').value = $(this).val();
                    });
            </script>
        </div>
        <div>
            <div class="wrap_content">
                <div class="rev_content">
                    <b>후기 작성</b>
                </div>
            </div>
            <div style="display:block;position:absolute;top:-50px"><input type="file" accept="image/*" name="imgupfile" id="addimgfile"></div>

            <script language="javascript">
                $('#addimgfile').bind('change', function(e) {
                    $(".fileuparea").hide();
                    $("#fileuplodingarea").show();
                    var ext = $('#addimgfile').val().split('.').pop().toLowerCase();
                    if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                        alert('gif,png,jpg,jpeg 파일만 업로드 할수 있습니다.');
                        $(".fileuparea").show();
                        $("#fileuplodingarea").hide();
                        return;
                    }

                    var filename = $("input[name=imgupfile]")[0].files[0].name;
                    var newfilename = GetPhotoFilename('usage_review_photo', '<?= $user_id ?>', filename);
                    var formData = new FormData();

                    formData.append("myfile", $("input[name=imgupfile]")[0].files[0]);
                    formData.append("filepath", newfilename);
                    formData.append("payment_log_seq", "<?= $payment_log_seq ?>");
                    formData.append("artist_id", "<?= $artist_id ?>");

                    $.ajax({
                        url: 'upload_usages_review_photo.php',
                        data: formData,
                        processData: false,
                        contentType: false,
                        type: 'POST',
                        success: function(data) {
                            $(".fileuparea").show();
                            $("#fileuplodingarea").hide();
                            if (/(MSIE|Trident)/.test(navigator.userAgent)) {
                                // ie 일때 input[type=file] init.
                                $("#addimgfile").replaceWith($("#addimgfile").clone(true));
                            } else {
                                // other browser 일때 input[type=file] init.
                                $("#addimgfile").val("");
                            }

                            if ($("#review_images").val() == "" || $("#review_images").val() == undefined) {
                                $("#review_images").val('<?= $upload_directory ?>' + '/' + newfilename);
                            } else {
                                $("#review_images").val($("#review_images").val() + '|' + '<?= $upload_directory ?>' + '/' + newfilename);
                            }

                            review_img_arr = $("#review_images").val().split('|');
                            if (review_img_arr.length > 0) {

                                var review_img_output = "";
                                for (i = 0; i < review_img_arr.length; i++) {
                                    review_img_output += '<img id="img_arr_' + i + '" src="' + review_img_arr[i] + '" onclick="review_img_delete(' + i + ')">';
                                }
                                $(".review_img_view").html(review_img_output);
                                $(".review_img_view").show();
                                $(".photo_02").show();
                                $(".photo_01").hide();
                            }
                        },
                        error: function(xhr, status, error) {
                            // alert(error+"에러발생");
                        }
                    });


                });

                function review_img_delete(idx) {
                    var review_img_view = "";
                    var replace_str = "";
                    var this_src = "";
                    $.MessageBox({
                        buttonFail: "아니오",
                        buttonDone: "예",
                        message: "<center>해당 사진을 삭제하시겠습니까?</center>"
                    }).done(function() {
                        this_src = $("#img_arr_" + idx).attr("src");
                        $.ajax({
                            url: 'delete_usages_review_photo.php',
                            data: {
                                'src': $("#img_arr_" + idx).attr("src"),
                                'payment_log_seq': '<?= $payment_log_seq ?>',
                                'artist_id': '<?= $artist_id ?>',
                                'user_id': '<?= $user_id ?>'
                            },
                            type: 'POST',
                            success: function(data) {
                                var RIA_length = 0;
                                review_img_view = $(".review_img_view").html();
                                replace_str = '<img id="img_arr_' + idx + '" src="' + this_src + '" onclick="review_img_delete(' + idx + ')">';
                                review_img_view = review_img_view.replace(replace_str, '');
                                $(".review_img_view").html(review_img_view);

                                var review_images_split = $("#review_images").val();
                                review_images_split = review_images_split.split('|');
                                RIA_length = review_images_split.length;

                                if (RIA_length > 1) {
                                    if (review_images_split[0] != this_src) {
                                        $("#review_images").val($("#review_images").val().replace('|' + this_src, ''));
                                    } else {
                                        $("#review_images").val($("#review_images").val().replace(this_src + '|', ''));
                                    }
                                } else {
                                    $("#review_images").val("");
                                }

                                if ($("#review_images").val() == "" || $("#review_images").val() == undefined) {
                                    $(".review_img_view").hide();
                                    $(".photo_02").hide();
                                    $(".photo_01").show();
                                }
                            },
                            error: function(xhr, status, error) {}
                        });
                    });
                    return false;
                }
            </script>
            <div class="rev_write">
                <div id="dmemo" contentEditable="true" style="display:none;"></div>
                <textarea class="review_textarea" id="memo" name="memo"><?= $memo ?></textarea>
            </div>
        </div>
    </div>
    <div class="block">
        <script>
            $(function() {
                $("button").click(function(e) {
                    $(this).next('span').find('input').click();
                });
            });
        </script>
    </div>


    <div style="height:5px;"></div>
    <div style="height:5px;"></div>
    <div class="btn_area_bottom">
        <button class="change_reservation" type="submit" onclick="return check_write();">저 장</button>
    </div>


    </img>
    </div>
</form>
</div>
<script language="javascript">
    function check_write() {
        obj = document.reviewForm;
        if ($('#memo').val().trim() == "") {
            $.MessageBox({
                buttonDone: "확인",
                message: "<center><font style='font-size:15px;font-weight:bold;'>내용을 입력해주세요</font></center>"
            }).done(function() {});
            return false;
        }

		if($('#rating').val() == "0" || $('#rating').val() == ""){
            $.MessageBox({
                buttonDone: "확인",
                message: "<center><font style='font-size:15px;font-weight:bold;'>평점을 입력해주세요</font></center>"
            }).done(function() {});
			return false;
		}

        var YokList = new Array('개새끼', '개색기', '개색끼', '개자식', '개보지', '개자지', '개년', '개걸래', '개걸레', '씨발', '씨팔', '씨부랄', '씨바랄', '씹창', '씹탱', '씹보지', '씹자지', '씨방세', '씨방새', '씨펄', '시펄', '십탱', '씨박', '썅', '쌍놈', '쌍넘', '싸가지', '쓰벌', '씁얼', '상넘이', '상놈의', '상놈이', '상놈을', '좆', '좃', '존나게', '존만한', '같은년', '넣을년', '버릴년', '부랄년', '바랄년', '미친년', '니기미', '니미씹', '니미씨', '니미럴', '니미랄', '호로', '후레아들', '호로새끼', '후레자식', '후래자식', '후라들년', '후라들넘', '빠구리', 'script', 'iframe', '병신', '보지', '자지', '씨.발', '졸라', '씹', '씹할');
        var Tmp;
        for (i = 0; i < YokList.length; i++) {
            Tmp = $('#memo').val().toLowerCase().indexOf(YokList[i]);
            if (Tmp >= 0) {
                $.MessageBox({
                    buttonDone: "확인",
                    message: "<center><font style='font-size:15px;font-weight:bold;'>금지어(" + YokList[i] + ")가 포함되어 있습니다.<br> 다시 작성해주세요.</font></center>"
                }).done(function() {});
                return false;
            }
        }

        return true;
    }


    $(document).ready(function() {
        if ($("#review_images").val() == "" || $("#review_images").val() == undefined) {
            $(".review_img_view").hide();
            $(".photo_02").hide();
            $(".photo_01").show();
        } else {
            // console.log('----- $("#review_images").val() : ' + $("#review_images").val());
            review_img_arr = $("#review_images").val().split('|');
            // console.log('----- review_img_arr.length : ' + review_img_arr.length);
            if (review_img_arr.length > 0) {
                var review_img_output = "";
                for (i = 0; i < review_img_arr.length; i++) {
                    review_img_output += '<center class="img_wrap"><img id="img_arr_' + i + '" src="' + review_img_arr[i] + '" onclick="review_img_delete(' + i + ')"></center>';
                }
                $(".review_img_view").html(review_img_output);
                $(".review_img_view").show();
                $(".photo_02").show();
                $(".photo_01").hide();
            }
        }

        //dvck=getDeviceCheck();
        if (<?= $is_android ?> == 1) {
            $(".fileuparea").hide();
            $(".fileuparea2").show();
        }
    });

    function galleryup() {
        //alert("갤러리")
        window.Android.openGallery();
    }

    function cameraup() {
        //alert("카메라")
        window.Android.openCamera();
    }

    //안드에서 업로드가 끝나면 무조건 호출..
    function uploadEnd(fileName) {
        $(".fileuparea2").hide();
        $("#fileuplodingarea").show();

        var newfilename = GetPhotoFilename('appupload_photo', '<?= $user_id ?>', fileName);
        var post_data =
            'filepath=' + fileName +
            '&newfilepath=' + newfilename +
            '&payment_log_seq=' + '<?= $payment_log_seq ?>' +
            '&artist_id=' + '<?= $artist_id ?>';

        $.ajax({
            url: 'upload_usages_review_photo_byapp.php',
            data: post_data,
            type: 'POST',
            success: function(data) {
                $(".fileuparea2").show();
                $("#fileuplodingarea").hide();
                // alert(data)
                //      return;
                if (/(MSIE|Trident)/.test(navigator.userAgent)) {
                    // ie 일때 input[type=file] init.
                    $("#addimgfile").replaceWith($("#addimgfile").clone(true));
                } else {
                    // other browser 일때 input[type=file] init.
                    $("#addimgfile").val("");
                }

                if ($("#review_images").val() == "" || $("#review_images").val() == undefined) {
                    $("#review_images").val('<?= $upload_directory ?>' + '/' + newfilename);
                } else {
                    $("#review_images").val($("#review_images").val() + '|' + '<?= $upload_directory ?>' + '/' + newfilename);
                }

                review_img_arr = $("#review_images").val().split('|');
                if (review_img_arr.length > 0) {

                    var review_img_output = "";
                    for (i = 0; i < review_img_arr.length; i++) {
                        review_img_output += '<img id="img_arr_' + i + '" src="' + review_img_arr[i] + '" onclick="review_img_delete(' + i + ')">';
                    }
                    $(".review_img_view").html(review_img_output);
                    $(".review_img_view").show();
                    $(".photo_02").show();
                    $(".photo_01").hide();
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

    function MemofocusNcursor() {
        html = "<div id='upimgarea'></div>";
        document.getElementById('memo').focus();
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

    function openpass() {
        if (document.reviewForm.lockon[0].checked) {
            $("#openpassarea").hide();
            $("#openpassareamemo").hide();

        } else {
            $("#openpassarea").show();
            $("#openpassareamemo").show();
        }
    }

    function send_write() {

        ck = check_write();
        if (ck == true) {
            obj = document.reviewForm;

            var check = "";

            $("#upclick").html("<img src='<?= $image_directory ?>/loading_s.gif' style='padding-left:20px'>");
            url = "/square/writeOk.php";

            var formData = new FormData();
            formData.append("squarekey", obj.squarekey.value);
            formData.append("haveimg", obj.haveimg.value);
            formData.append("memo", $('#dmemo').html());
            formData.append("tag_value", $("#tag_valueid").val());
            formData.append("pass", obj.pass.value);
            formData.append("opinionsendpush", opinionsendpushvalue);
            formData.append("anonym", anonym);
            $.ajax({
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                type: 'POST',
                dataType: "text",
                success: function(data) {

                    if (data == "0") {
                        tmsg("글쓴이가 아닙니다.다시 확인해주세요");
                        $("#upclick").html("<a href='javascript:send_write();'>올리기</a>");
                        return;
                    }
                    <? if ($squarekey) { ?>
                        location.href = "/m/square/toron_sub.php?squarekey=<?= $squarekey ?>";
                    <? } else { ?>
                        location.href = "/m/square/toron_main.php?catecode=" + obj.scate.value
                    <? } ?>
                    //   location.href="/square/";	

                },
                error: function(xhr, status, error) {
                    tmsg(error + "에러발생");
                }
            });
        }
    }

    function send_temp() {
        ck = check_write();
        if (ck == true) {
            obj = document.reviewForm;

            var len = document.reviewForm.lockon.length;
            var check = "";

            for (var i = 0; i < len; i++) {
                if (document.reviewForm.lockon[i].checked) {
                    lockon = document.reviewForm.lockon[i].value;
                }
            }
            obj.memo.value = $('#dmemo').html();
            url = "temp_write.php";
            params = "toronclass=" + obj.toronclass.value + "&fcate=" + obj.fcate.value + "&scate=" + obj.scate.value + "&title=" + obj.title.value + "&memo=" + obj.memo.value + "&haveimg=" + obj.haveimg.value + "&tag_value=" + $("#tag_valueid").val() + "&lockon=" + lockon;
            $.ajax({
                type: "POST",
                url: url,
                data: params,
                dataType: "text",
                success: function(data) {
                    //alert(data)
                    //$('#imsisql').html(data);
                    viewobj('#tempwritelayer');
                },
                error: function(xhr, status, error) {
                    tmsg(error + "에러발생");
                }
            });
        }
    }


    delno = 0;

    function make_tag() {

        if ($("div.tag_li").length > 9) {
            tmsg("태그는 10개까지 만들수 있습니다.")
            return;
        }
        $("#tag_word").val($("#tag_word").val().split(" ").join(""));
        if ($("#tag_word").val() == "") {
            tmsg("단어를 넣어주세요")
            return;
        }
        beforeStr = $("#tag_area").html();
        // mk_tageStr="<div class='tag_li' id=delid"+delno+">"+$("#tag_word").val()+"<span class='tag_close'><a href='javascript:del_tag(\"#delid"+delno+"\")' class='deltag'><img src='../images/all/tag_close.jpg' alt='' /></a></span></div>";
        mk_tageStr = "<li id=delid" + delno + " ><dl class='clearfix' ><dt  class='tag_li'>" + $("#tag_word").val() + "</dt><dd><button class='del' onclick='javascript:del_tag(\"#delid" + delno + "\")'>삭제</button></dd></dl></li>";
        $("#tag_area").html(beforeStr + mk_tageStr);


        mk_tag_value = "";
        for (kk = 0; kk < $("dt.tag_li").length; kk++) {
            mk_tag_value = mk_tag_value + "#" + $('dt.tag_li').eq(kk).text()
        }
        //		alert(mk_tag_value)
        //	document.reviewForm.tag_value.value=mk_tag_value;
        $("#tag_valueid").val(mk_tag_value);
        $("#tag_word").val("");
        delno++;
    }



    function del_tag(obj) {
        $(obj).remove();
        mk_tag_value = "";
        for (kk = 0; kk < $("dt.tag_li").length; kk++) {
            mk_tag_value = mk_tag_value + "#" + $('dt.tag_li').eq(kk).text()
        }
        //document.reviewForm.tag_value.value=mk_tag_value;
        $("#tag_valueid").val(mk_tag_value);
    }

    function Enterword() {
        if (event.keyCode == 13 || event.keyCode == 32) {
            make_tag();
            return;
        }
    }
    <? if ($fcate) { ?>

        function onloadselect() {
            var len = document.reviewForm.fcate.length;
            for (var i = 0; i < len; i++) {
                if (document.reviewForm.fcate[i].value == "<?= $fcate ?>") {
                    document.reviewForm.fcate[i].selected = true;
                }
            }
            url = "/common/scate_select.php";
            params = "fcate=" + $('#fcate').val() + "&scate=<?= $scate ?>";
            $.ajax({
                type: "POST",
                url: url,
                data: params,
                dataType: "text",
                success: function(data) {
                    $('#scate_area').html(data);
                },
                error: function(xhr, status, error) {
                    tmsg(error + "에러발생");
                }
            });
            mk_tageStr = "";
            <?
            $tag_values = explode("#", $tag_value);
            for ($kk = 1; $kk < sizeof($tag_values); $kk++) {
            ?>
                mk_tageStr = mk_tageStr + "<li id=delid<?= $kk ?> ><dl class='clearfix' ><dt  class='tag_li'><?= $tag_values[$kk] ?></dt><dd><button class='del' onclick='javascript:del_tag(\"#delid<?= $kk ?>\")'>삭제</button></dd></dl></li>";
                //mk_tageStr=mk_tageStr+"<div class='tag_li' id=delid<?= $kk ?>><?= $tag_values[$kk] ?><span class='tag_close'><a href='javascript:del_tag(\"#delid<?= $kk ?>\")' class='deltag'><img src='../images/all/tag_close.jpg' alt='' /></a></span></div>";
            <?
            }
            ?>

            $("#tag_area").html(mk_tageStr);
            $("#tag_valueid").val("<?= $tag_value ?>")
        }
        window.onload = onloadselect;
    <? } ?>
</script>

<?php include "../include/bottom.php"; ?>