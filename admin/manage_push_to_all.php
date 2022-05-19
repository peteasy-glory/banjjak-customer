<?php
include "../include/top.php";

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
    ?>

    <style>
        input[type=text],
        input[type=password] {
            width: 100%;
            height: 25px;
            border: 1px solid #cbcaca;
            font-size: 13px;
        }

        .go_login {
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
            //	height:30px;
            line-height: 30px;
            width: 100%;
            text-decoration: none;
            text-align: center;
            padding: 5px 20px;
            margin: 8px 0;
        }

        .go_login:hover {
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #a20dbd), color-stop(1, #c123de));
            background: -moz-linear-gradient(center top, #a20dbd 5%, #c123de 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#a20dbd', endColorstr='#c123de');
            background-color: #a20dbd;
        }

        .go_login:active {
            position: relative;
            top: 1px;
        }

        .cancelbtn {
            width: auto;
            padding: 10px 18px;
            background-color: #f44336;
        }

        .imgcontainer {
            text-align: center;
            margin: 24px 0 12px 0;
        }

        img.avatar {
            width: 40%;
            border-radius: 50%;
        }

        .container {
            padding: 10px;
        }

        span.psw {
            float: right;
            padding-top: 3px;
        }

        /* Change styles for span and cancel button on extra small screens */
        @media screen and (max-width: 300px) {
            span.psw {
                display: block;
                float: none;
            }

            .cancelbtn {
                width: 100%;
            }
        }

        .find_password {
            font-size: 14px;
            text-decoration: none;
        }

        .find_password:hover {
            color: #000000;
        }

        .find_password:active {
            color: #000000;
        }

        .find_password:link {
            color: #000000;
        }

        .find_password:visited {
            color: #000000;
        }
    </style>

    <script>
        $(function(){
            $(document).on("click", "#btn_submit", function(){

                var _val_radiobox = $("input[name=sendto]:checked").val();
                var _tmp_tosend = "";
                if(_val_radiobox == "peteasy") {
                    _tmp_tosend = "내부테스트(peteasy 임직원)";
                } else if(_val_radiobox == "petshop") {
                    _tmp_tosend = "펫샵 회원 대상";
                } else if(_val_radiobox == "notpetshop") {
                    _tmp_tosend = "견주 회원 대상";
                } else if(_val_radiobox == "alluser") {
                    _tmp_tosend = "전체 회원 대상";
                }
                //
                var push_title = $.trim($("input[name=push_title]").val());
                var push_message = $.trim($("input[name=push_message]").val());
                var push_url = $.trim($("input[name=push_url]").val());
                var push_image = $.trim($("input[name=push_image]").val());
                
                if(push_title != "" && push_message!="" && push_url!="" && push_image!="") {
                    if(confirm("**주의**\n\n[" + _tmp_tosend + "] 대상으로 발송하시겠습니까?")) {
                        $("#frm_send_push_to_all").submit();
                    }
                } else {
                    alert('푸시 발송용 정보를 입력하세요.');
                }
            });
        });
    </script>

    <div class="header-back-btn"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/back.png" width="35px"></a></div>
    <div style="width:100%;height:19px;text-align:center;font-size:18px;font-weight:bold;">
        <p>푸시 메시지 - 전체 보내기</p>
        <a href='manage_push_to_all_stat.php' style='display: inline-block; text-align: right; width: 100%;'>통계</a>
    </div>

    <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">

    <form action="manage_push_to_all_process.php" method="POST" id="frm_send_push_to_all">
        <table width="100%">
            <tr>
                <td align="center">
                    <table class="container" style="width:95%;font-size:13px;">
                        <tr>
                            <td>
                                <b>발송대상</b><br>
                                <input type="radio"  name="sendto" value='peteasy' checked> PetEasy 직원 (내부 테스트)
                                <input type="radio" name="sendto" value='petshop'> 펫샵 회원 
                                <input type="radio" name="sendto" value='notpetshop'> 견주 회원 
                                <input type="radio" name="sendto" value='alluser'> 전체 회원 (펫샵 + 견주)
                                <div style='font-weight:normal;color:red;'>- enable_flag, push_option, token 보유자 대상 </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>타이틀</b><br>
                                <input type="text" placeholder="타이틀을 입력해주세요" name="push_title" id="push_title" required>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>메시지</b><br>
                                <input type="text" placeholder="내용을 입력해주세요" name="push_message" id="push_message" required>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Link URL</b><br>
                                <input type="text" placeholder="Link 주소" name="push_url" id="push_url" value="https://gopet.kr/pet/mainpage/" required>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>이미지 URL</b><br>
                                <!--input type="text" placeholder="이미지" name="push_image" id="push_image" value="http://gopet.kr/pet/images/logo_login.jpg" required-->
								<input type="text" placeholder="이미지" name="push_image" id="push_image" value="" required>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="height:7px;width:100%;"></div>
                                <button class="go_login" type="button" id='btn_submit'>메시지 전송</button>
                                <br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!--div class="container" style="background-color:#f1f1f1;text-align:center;">
    <button type="button" class="cancelbtn" onClick="javascript:history.back();">Cancel</button>
  </div-->
    </form>

    <?php include "../include/bottom.php"; ?>
<?php } ?>