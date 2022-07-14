<?php include "../include/top.php"; ?>
<?php
$cl_result = include "../include/check_login.php";
if ($cl_result == 0) {
    return false;
}

$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

$squence = $_REQUEST['seq'];
if (!$squence) {
    $squence = 1;
}

$login_insert_sql = "select * from tb_customer where id = '" . $user_id . "' and (admin_flag = true or operator_flag = true)";
$result = mysql_query($login_insert_sql);

if ($result_datas = mysql_fetch_object($result)) {

    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $is_android = 0;
    if ($user_agent) {
        if (strpos($user_agent, "APP_GOBEAUTY_AND") > 0) {
            $is_android = 1;
        }
    }

    $user_id = $_SESSION['gobeauty_user_id'];
    $user_name = $_SESSION['gobeauty_user_nickname'];
    $review_seq = $_REQUEST['review_seq'];
    $artist_id = $_REQUEST['artist_id'];

    $memo = "";
    $update_time = "";
    $rating = "5";
    $sql = "select * from tb_usage_reviews where customer_id='" . $user_id . "' and review_seq = '" . $review_seq . "' and is_delete = 0;";
    $result = mysql_query($sql);
    if ($rows = mysql_fetch_object($result)) {
        $memo = $rows->review;
        $rating = $rows->rating;
        $update_time = $rows->update_time;
    }

    ?>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/star-rating.css" media="all" type="text/css" />
    <script src="/js/star-rating.js" type="text/javascript"></script>

    <style>
        a {
            text-decoration: none;
        }

        a:link {
            color: black;
        }

        a:visited {
            color: black;
        }

        a:hover {
            color: black;
        }

        a:active {
            color: black;
        }

        .change_reservation {
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #c123de), color-stop(1, #a20dbd));
            background: -moz-linear-gradient(center top, #c123de 5%, #a20dbd 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#c123de', endColorstr='#a20dbd');
            background-color: #c123de;
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
            border: 1px solid #a511c0;
            display: inline-block;
            color: #ffffff;
            font-family: Arial;
            font-size: 15px;
            font-weight: bold;
            font-style: normal;
            height: 36px;
            line-height: 36px;
            width: 100%;
            text-decoration: none;
            text-align: center;
        }

        .change_reservation:hover {
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #a20dbd), color-stop(1, #c123de));
            background: -moz-linear-gradient(center top, #a20dbd 5%, #c123de 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#a20dbd', endColorstr='#c123de');
            background-color: #a20dbd;
        }

        .change_reservation:active {
            position: relative;
            top: 1px;
        }
    </style>

    <div class="header-back-btn"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/back.png" width="35px"></a></div>
    <div style="width:100%;height:19px;text-align:center;font-size:18px;font-weight:bold;">
        <p>이용후기작성</p>
    </div>
    <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">

    <style>
        .btn_blue2 {
            font-size: 12px;
            line-height: 26px;
            text-align: center;
            background: #3a719b;
            color: #fff;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            border: 2px solid #2f5d80;
            min-width: 80px
        }
    </style>
    <?php
        $order_id = str2hex("FAKE_ADMIN") . "_" . rand_id();
        ?>
    <form action="insert_usage_review.php" name="toronwriteform" method="post" style="border:0;margin:0" enctype="multipart/form-data" id="frm">
        <table width="100%">
            <thead>
                <tr>
                    <th colspan="2">
                        <table>
                            <tr>
                                <td style="font-size:15px"><b>아티스트선택</b>
                                <td>
                                <td> &nbsp;&nbsp; &nbsp; &nbsp;
                                    <select id="artist_id" name="artist_id">
                                        <?php
                                            $ssql = "select customer_id, name from tb_shop where open_flag = 1 order by name asc;";
                                            $sresult = mysql_query($ssql);
                                            while ($srows = mysql_fetch_object($sresult)) {
                                                $customer_id = $srows->customer_id;
                                                $name = $srows->name;
                                                ?>
                                            <option value='<?= $customer_id ?>'><?= $name ?></option>
                                        <?php
                                            }
                                            ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </th>
                </tr>
                <tr>
                    <th colspan="2">
                        <table>
                            <tr>
                                <td style="font-size:15px"><b>닉네임</b>
                                <td>
                                <td> &nbsp;&nbsp; &nbsp; &nbsp;
                                    <input type="text" id="nickname" name="nickname" placeholder="닉네임을 입력해주세요." required>
                                </td>
                            </tr>
                        </table>
                    </th>
                </tr>
                <tr>
                    <th colspan="2">
                        <table>
                            <tr>
                                <td style="font-size:15px"><b>주문번호</b>
                                <td>
                                <td> &nbsp;&nbsp; &nbsp; &nbsp;
                                    <input type="text" id="order_id" name="order_id" value="<?= $order_id ?>" placeholder="닉네임을 입력>해주세요." readonly>
                                </td>
                            </tr>
                        </table>
                    </th>
                </tr>
                <tr>
                    <th colspan="2">
                        <table>
                            <tr>
                                <td style="font-size:15px"><b>평점</b> &nbsp;&nbsp; </td>
                                <td> <input type="text" id="rating" class="rating" name="rating" value="<?= $rating ?>" data-size="xs" title="평점"></td>
                            </tr>
                        </table>

                        <script>
                            $('#rating').on(
                                'change',
                                function() {
                                    console.log('Rating selected: ' + $(this).val());
                                    document.getElementById('rating').value = $(this).val();
                                });
                        </script>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td height="5px"></td>
                </tr>
                <tr>
                    <th colspan="2">
                        <b style="float:left;font-size:18px;">내용</b>
                        <span style="float:right" id="fileuparea">
                            <button type="button" name="button1" onclick="javascript:MemofocusNcursor();">
                                <img src="<?= $image_directory ?>/photo3.png" align="absmiddle" style="width:40px">&nbsp;
                            </button>
                        </span>
                        <span style="float:right;display:none" id="fileuparea2">
                            <button type="button" onclick="javascript:galleryup();">
                                <img src="<?= $image_directory ?>/photo3.png" align="absmiddle" style="width:40px">&nbsp;
                            </button>
                            <button type="button" onclick="javascript:cameraup();">
                                <img src="<?= $image_directory ?>/photo2.png" align="absmiddle" style="width:40px">&nbsp;
                            </button>
                        </span>
                        <span id="fileuplodingarea" style="display:none;float:right">
                            <img src='<?= $image_directory ?>/loading_s.gif' style='padding-left:0px;width:30px'>
                        </span>
                    </th>
                </tr>
                <div style="display:block;position:absolute;top:-50px"><input type="file" accept="image/*" name=imgupfile id=addimgfile></div>

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

                        var newfilename = GetPhotoFilename('usage_review_photo', '<?= $user_id ?>', filename);

                        var formData = new FormData();
                        formData.append("myfile", $("input[name=imgupfile]")[0].files[0]);
                        formData.append("filepath", newfilename);

                        /*	         var formData = new FormData();
                                	  formData.append("toronclass", $("input[name=toronclass]").val());
                                	  formData.append("imgupfile", $("input[name=imgupfile]")[0].files[0]);*/
                        $.ajax({
                            url: '<?= $mainpage_directory ?>/upload_usages_review_photo.php',
                            data: formData,
                            processData: false,
                            contentType: false,
                            type: 'POST',
                            success: function(data) {

                                //	alert(data);
                                //	var json = JSON.parse(data);
                                //alert(data.upfilename+"/"+data.allpath+"/"+data.msg)

                                $("#fileuparea").show();
                                $("#fileuplodingarea").hide();
                                //alert(data)
                                //	return;
                                if (/(MSIE|Trident)/.test(navigator.userAgent)) {
                                    // ie 일때 input[type=file] init.
                                    $("#addimgfile").replaceWith($("#addimgfile").clone(true));
                                } else {
                                    // other browser 일때 input[type=file] init.
                                    $("#addimgfile").val("");
                                }
                                //if (data.msg){
                                //	tmsg(data.msg);
                                //alert(data.upfilename)
                                //document.toronwriteform.haveimg.value=document.toronwriteform.haveimg.value+newfilename+"|";
                                addwidth = " style='width:30%' ";
                                mkimg_str = "<img src='/upload/" + newfilename + "' " + addwidth + ">";
                                //		alert(mkimg_str);
                                if ($('#dmemo').html().indexOf("upimgarea") != -1) {
                                    $('#dmemo').html($('#dmemo').html().replace("<div id=\"upimgarea\"></div>", mkimg_str));
                                } else {
                                    $('#dmemo').append(mkimg_str);
                                }

                            },
                            error: function(xhr, status, error) {
                                // alert(error+"에러발생");
                            }
                        });


                    });
                </script>
                <tr>
                    <th colspan="2">
                        <!--p style="height:25px"><input type="checkbox" name="anonym" value=1 <? if ($anonym) { ?>checked<? } ?>>&nbsp;익명으로 올리기</p-->
                        <textarea id="memo" name="memo" style="display:none"></textarea>
                        <div id="dmemo" contentEditable="true" style="width:100%;height:400px;border:1px solid #cccccc;font-size:14px;color:#616161;overflow-y:scroll;padding:10px;line-height:140%"><?= $memo ?></div>
                    </th>
                </tr>
            </tbody>
        </table>
        <div class="block">
            <script>
                $(function() {
                    $("button").click(function(e) {
                        $(this).next('span').find('input').click();
                    });
                });
            </script>
            <!--p class="pst_01">저작권 침해나 명예를 훼손하는 내용은 처벌받을 수 있습니다. 타인의 글 을 인용한 경우 출처를 밝혀주시고 내용의 30%를 넘지 않도록 해주세요</p-->
        </div>
        <div style="height:5px;"></div>
        <div class="btn_area_bottom">
            <button class="change_reservation" type="submit" onclick="return check_write();">저 장</button>
        </div>

        </div>
        </div>
    </form>

    <script language="javascript">
        function check_write() {
            obj = document.toronwriteform;
            obj.memo.value = $('#dmemo').html()
            if (obj.memo.value == "") {
                $.MessageBox({
                    buttonDone: "확인",
                    message: "<center><font style='font-size:15px;font-weight:bold;'>내용을 입력해주세요</font></center>"
                }).done(function() {});
                return false;
            }

            var YokList = new Array('개새끼', '개색기', '개색끼', '개자식', '개보지', '개자지', '개년', '개걸래', '개걸레', '씨발', '씨팔', '씨부랄', '씨바랄', '씹창', '씹탱', '씹보지', '씹자지', '씨방세', '씨방새', '씨펄', '시펄', '십탱', '씨박', '썅', '쌍놈', '쌍넘', '싸가지', '쓰벌', '씁얼', '상넘이', '상놈의', '상놈이', '상놈을', '좆', '좃', '존나게', '존만한', '같은년', '넣을년', '버릴년', '부랄년', '바랄년', '미친년', '니기미', '니미씹', '니미씨', '니미럴', '니미랄', '호로', '후레아들', '호로새끼', '후레자식', '후래자식', '후라들년', '후라들넘', '빠구리', 'script', 'iframe', '병신', '보지', '자지', '씨.발', '졸라', '씹', '씹할');
            var Tmp;
            for (i = 0; i < YokList.length; i++) {
                Tmp = obj.memo.value.toLowerCase().indexOf(YokList[i]);
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
            //		alert(fileName);
            $("#fileuparea2").hide();
            $("#fileuplodingarea").show();

            var newfilename = GetPhotoFilename('appupload_photo', '<?= $user_id ?>', fileName);
            //		alert(newfilename);

            var post_data = 'filepath=' + fileName + '&newfilepath=' + newfilename;
            //		alert(post_data);
            var formData = new FormData();
            formData.append("filepath", fileName);
            formData.append("newfilepath", newfilename);
            $.ajax({
                url: '<?= $mainpage_directory ?>/upload_usages_review_photo_byapp.php',
                data: post_data,
                type: 'POST',
                success: function(data) {
                    //				alert(data);

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
                    if ($('#dmemo').html().indexOf("upimgarea") != -1) {
                        $('#dmemo').html($('#dmemo').html().replace("<div id=\"upimgarea\"></div>", mkimg_str));
                    } else {
                        $('#dmemo').append(mkimg_str);
                    }

                    //}
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
            document.getElementById('dmemo').focus();
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
            if (document.toronwriteform.lockon[0].checked) {
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
                obj = document.toronwriteform;

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
                obj = document.toronwriteform;

                var len = document.toronwriteform.lockon.length;
                var check = "";

                for (var i = 0; i < len; i++) {
                    if (document.toronwriteform.lockon[i].checked) {
                        lockon = document.toronwriteform.lockon[i].value;
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
            //	document.toronwriteform.tag_value.value=mk_tag_value;
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
            //document.toronwriteform.tag_value.value=mk_tag_value;
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
                var len = document.toronwriteform.fcate.length;
                for (var i = 0; i < len; i++) {
                    if (document.toronwriteform.fcate[i].value == "<?= $fcate ?>") {
                        document.toronwriteform.fcate[i].selected = true;
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
<?php } ?>