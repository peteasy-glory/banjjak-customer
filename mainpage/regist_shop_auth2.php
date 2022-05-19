<?php include "../include/top.php"; ?>
<?php include "../include/App.class.php"; ?>

<?php
$is_android = 0;
$app = new App();
if ($app->is_app() == 1) {
    $is_android = 1;
}
?>

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
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<style>
	input[type='number'] {
		width: 100%;
		padding: 12px 20px;
		margin: 8px 0;
		display: inline-block;
		border: 1px solid #ccc;
		border-radius: 6px;
		box-sizing: border-box;
		font-size: 17px;
	}
</style>
<div class="top_menu">
    <div class="top_back"><a href="<?= $mainpage_directory ?>/view_event2.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div class="top_title">
        <p>반짝 입점 신청</p>
    </div>
</div>


<form action="<?= $mainpage_directory ?>/insert_artist_regist.php" method="POST" id="regist_shop_auth2" style="margin-top:73px;">
    <input type="hidden" name="step" value="1-1" />
    <table style="width:90%; margin: 0px auto; font-size:20px;">
        <tr style="text-align:center;">
            <td class="event_title" width="50%">
                <a href="regist_shop_auth.php">
                    <div style="padding:15px 15px 5px 15px; margin-bottom: 10px;border-bottom:3px solid #999999;color:#999999;">개인</div>
                </a>
            </td>
            <td class="event_title" width="50%">
                <a href="regist_shop_auth2.php">
                    <div style="padding:15px 15px 5px 15px; margin-bottom: 10px;border-bottom:3px solid #f5bf2e;color:#f5bf2e;">사업자</div>
                </a>
            </td>
        </tr>
        <tr>
            <td height="10px"></td>
        </tr>
        <tr>
            <td colspan="2" style="font-size:17px;">
                <b>사업자명(사업자 등록상 이름)</b><br>
                <input type="text" name="artist_real_name" id="artist_real_name" style="width:100%;font-size:14px;" placeholder="사업자명 입력" required>
            </td>
        </tr>
        <tr>
            <td height="10px"></td>
        </tr>
        <tr>
            <td colspan="2" style="font-size:17px;">
                <b>사업자 번호 / 등록증 사본 업로드</b><br>
                <table>
                    <tr>
                        <td>
                            <input type="number" maxlength="3" name="sa_num1" id="sa_num1" style="width:100%;font-size:14px;" required>
                        </td>
                        <td>
                            <input type="number" maxlength="2" name="sa_num2" id="sa_num2" style="width:100%;font-size:14px;" required>
                        </td>
                        <td>
                            <input type="number" maxlength="5" name="sa_num3" id="sa_num3" style="width:100%;font-size:14px;" required>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <div width="100%" class="filebox">
                    <label>
                        <!--input type="file" accept="image/*" id="camera"><div class="go_button">등록증 첨부</div></input-->
                        <span style="float:right;width:100%;" id="fileuparea">
                            <button class="go_button" type="button" name="button1" onclick="javascript:MemofocusNcursor();">
                                등록증 첨부
                            </button>
                        </span>
                        <span style="float:right;width:100%;display:none;" id="fileuparea2">
                            <button class="go_button" type="button" onclick="javascript:galleryup();">
                                등록증 첨부
                            </button>
                            <!--button class="go_button" type="button" onclick="javascript:cameraup();">
                                                        등록증 첨부 ( <img src="<?= $image_directory ?>/photo2.png" align="absmiddle"  style="width:25px"> )&nbsp;
                                                </button-->
                        </span>
                        <span id="fileuplodingarea" style="display:none;float:right">
                            <img src='<?= $image_directory ?>/loading_s.gif' style='padding-left:0px;width:30px'>
                        </span>

                    </label>
                </div>
                <div style="display:block;position:absolute;top:-50px"><input type="file" accept="image/*" name=imgupfile id=addimgfile></div>
                <img width="100%" id="main_front_image">
                <script language="javascript">
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

                        /*               var formData = new FormData();
                                          formData.append("toronclass", $("input[name=toronclass]").val());
                                          formData.append("imgupfile", $("input[name=imgupfile]")[0].files[0]);*/
                        $.ajax({
                            url: '<?= $mainpage_directory ?>/upload_request_artistlicense_photo.php',
                            data: formData,
                            processData: false,
                            contentType: false,
                            type: 'POST',
                            success: function(data) {

                                //      alert(data);
                                //      var json = JSON.parse(data);
                                //alert(data.upfilename+"/"+data.allpath+"/"+data.msg)

                                $("#fileuparea").show();
                                $("#fileuplodingarea").hide();
                                //alert(data)
                                //      return;
                                if (/(MSIE|Trident)/.test(navigator.userAgent)) {
                                    // ie 일때 input[type=file] init.
                                    $("#addimgfile").replaceWith($("#addimgfile").clone(true));
                                } else {
                                    // other browser 일때 input[type=file] init.
                                    $("#addimgfile").val("");
                                }
                                //if (data.msg){
                                //      tmsg(data.msg);
                                //alert(data.upfilename)
                                //document.toronwriteform.haveimg.value=document.toronwriteform.haveimg.value+newfilename+"|";
                                addwidth = " style='width:30%' ";
                                mkimg_str = "<img src='/pet/upload/" + newfilename + "' " + addwidth + ">";
                                //              alert(mkimg_str);
                                //                                                if ($('#dmemo').html().indexOf("upimgarea") != -1){
                                //                                                        $('#dmemo').html($('#dmemo').html().replace("<div id=\"upimgarea\"></div>",mkimg_str));
                                //                                                }else{
                                //                                                        $('#dmemo').append(mkimg_str);
                                //                                                }
                                //                                                location.reload();
                                var main_frame = document.getElementById('main_front_image');
                                main_frame.src = '/pet/upload/' + newfilename;

                            },
                            error: function(xhr, status, error) {
                                // alert(error+"에러발생");
                            }
                        });


                    });
                    //안드로이드 앱일경우애만 ... 실행..
                    $(document).ready(function() {
                        //dvck=getDeviceCheck();
                        if (<?= $is_android ?> == 1) {
                            $("#fileuparea").hide();
                            $("#fileuparea2").show();
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
                        //              alert(fileName);
                        $("#fileuparea2").hide();
                        $("#fileuplodingarea").show();

                        var newfilename = GetPhotoFilename('appupload_photo', '<?= $user_id ?>', fileName);
                        //              alert(newfilename);

                        var post_data = 'filepath=' + fileName + '&newfilepath=' + newfilename;
                        //              alert(post_data);
                        var formData = new FormData();
                        formData.append("filepath", fileName);
                        formData.append("newfilepath", newfilename);
                        $.ajax({
                            url: '<?= $mainpage_directory ?>/upload_request_artistlicense_photo_byapp.php',
                            data: post_data,
                            type: 'POST',
                            success: function(data) {
                                //                              alert(data);

                                //alert(data.imagewidth+"/"+ data.upfilename+"/"+data.allpath+"/"+data.msg)
                                $("#fileuparea2").show();
                                $("#fileuplodingarea").hide();
                                //alert(data)
                                //      return;
                                if (/(MSIE|Trident)/.test(navigator.userAgent)) {
                                    // ie 일때 input[type=file] init.
                                    $("#addimgfile").replaceWith($("#addimgfile").clone(true));
                                } else {
                                    // other browser 일때 input[type=file] init.
                                    $("#addimgfile").val("");
                                }
                                //if (data.msg){
                                //      tmsg(data.msg);
                                //alert(data.upfilename)
                                //document.toronwriteform.haveimg.value=document.toronwriteform.haveimg.value+newfilename+"|";
                                addwidth = " style='width:30%' ";
                                mkimg_str = "<img src='/pet/upload/" + newfilename + "' " + addwidth + ">";
                                //               alert(mkimg_str);
                                /*                                                if ($('#dmemo').html().indexOf("upimgarea") != -1){
                                                                                        $('#dmemo').html($('#dmemo').html().replace("<div id=\"upimgarea\"></div>",mkimg_str));
                                                                                }else{
                                                                                        $('#dmemo').append(mkimg_str);
                                                                                }
                                */
                                //}
                                //                                        location.reload();
                                var main_frame = document.getElementById('main_front_image');
                                main_frame.src = '/pet/upload/' + newfilename;
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
                        //document.getElementById('dmemo').focus();
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

            </td>
        </tr>
        <tr>
            <td height="50px"></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" class="change_reservation" value="다 &nbsp; &nbsp;&nbsp;&nbsp; 음">
            </td>
        </tr>
    </table>
</form>

<br>
<br><br><br>
<br><br><br>
<?php include "../include/bottom.php"; ?>