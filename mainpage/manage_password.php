<?php include "../include/top.php"; ?>
<?php include "../include/Crypto.class.php"; ?>
<?php include "../include/MCASH_SEED.php"; ?>

<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">

<div class="top_menu">
    <div class="top_back" style="top:13px;"><a href="<?= $mainpage_directory ?>/manage_setting.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
    <div class="top_title">
        <p>비밀번호 변경</p>
    </div>
</div>

<table id="manage_password" width="100%">
    <tr>
        <td height="20px"></td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <form action="change_password.php" id="next_form" method="POST">
                <input type="hidden" name="customer_id" value="<?= $rows->id ?>">
                <table class="password_wrap">
                    <tr>
                        <td>
                            <font style="font-size:16px;">*새로운 비밀번호를 입력해주세요.</font>
                        </td>
                    </tr>
                    <tr>
                        <td height="10px"></td>
                    </tr>
                    <tr>
                        <td align="center">
                            <input type="password" placeholder="비밀번호입력(6~16자 영문,숫자조합)" name="gobeauty_pwd" id="gobeauty_pwd" onblur="ck_pwd()" required>
                            <span id="MsgPw" class="none">유효성체크</span>
                        </td>
                    </tr>
                    <tr>
                        <td height="10px"></td>
                    </tr>
                    <tr>
                        <td align="center">
                            <input type="password" placeholder="비밀번호확인" name="gobeauty_pwd_ck" id="gobeauty_pwd_ck" onblur="ck_pwd2()" required>
                            <span id="MsgPwck" class="none">유효성체크</span>
                        </td>
                    </tr>
                    <tr>
                        <td height="10px"></td>
                    </tr>
                    <tr>
                        <td align="center">
                            <input type="submit" onclick="return check_all();" href="#" class="cell_confirm" value="비밀번호 변경"></input>
                        </td>
                    </tr>
                </table>
            </form>
            <script>
                function ck_pwd() {
                    var pwd = document.getElementById("gobeauty_pwd")
                    var MsgPw = document.getElementById("MsgPw")
                    var isPwd = /^(?=.*[a-zA-Z])((?=.*\d)|(?=.*\W)).{6,20}$/

                    if (!isPwd.test(pwd.value)) {
                        MsgPw.style.display = "block";
                        MsgPw.className = 'error'
                        MsgPw.innerHTML = "<font style='font-size:13px;color:red;'>숫자포함 최소 6자리 이상</font>"
                        //            pwd.focus()
                        return false;
                    } else {
                        MsgPw.className = 'vaild'
                        MsgPw.innerHTML = "<font style='font-size:13px;color:green;'>ok</font>"
                        return true;
                    }
                }


                function ck_pwd2() {
                    var pwd_ck = document.getElementById("gobeauty_pwd_ck")
                    var pwd = document.getElementById("gobeauty_pwd").value
                    var MsgPwck = document.getElementById("MsgPwck")

                    if (pwd_ck.value != pwd || pwd_ck.value == "") {
                        MsgPwck.style.display = "block";
                        MsgPwck.className = 'error'
                        MsgPwck.innerHTML = "<font style='font-size:13px;color:red;'>비밀번호가 일치하지 않습니다.</font>"
                        //            pwd_ck.focus()
                        return false;
                    } else {
                        MsgPwck.className = 'vaild'
                        MsgPwck.innerHTML = "<font style='font-size:13px;color:green;'>ok</font>"
                        return true;
                    }
                }

                function check_all() {
                    if (!ck_pwd()) {
                        $.MessageBox({
                            buttonDone: "확인",
                            message: "비밀번호를 확인해주세요."
                        }).done(function() {});
                        return false;
                    }
                    if (!ck_pwd2()) {
                        $.MessageBox({
                            buttonDone: "확인",
                            message: "비밀번호 확인을 확인해주세요."
                        }).done(function() {});
                        return false;
                    }
                    /*      if (!ck_name()) {
                                    $.MessageBox({
                                        buttonDone      : "확인",
                                        message         : "닉네임을 확인해주세요."
                                    }).done(function(){
                                            var id = document.getElementById("name");
                                            id.focus();
                                    });
                                    return false;
                            }
                    */
                    //        document.getElementById('next_form').submit();
                    return true;
                }
            </script>
        </td>
    </tr>
</table>
<br><br><br><br>
<?php include "../include/bottom.php"; ?>