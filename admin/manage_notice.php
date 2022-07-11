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
            height: 30px;
            border: 0;
            border-bottom: 1px solid #d01fd6;
            text-align: center;
            font-size: 15px;
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

        /*
button {
    background-color: #fa9dcc;
    color: white;
    padding: 5px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    width: 100%;
    font-size:18px;
    font-weight:bold;
}*/

        /*button:hover {
    opacity: 0.8;
}*/

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
        //	function onLogin (id) {
        //	}
        function on_login() {
            var id = document.getElementById('gobeauty_user_name').value;
            return window.Android.onLogin(id);
        }
    </script>

    <div class="header-back-btn"><a href="<?= $admin_directory ?>/"><img src="<?= $image_directory ?>/back.png" width="35px"></a></div>
    <div style="width:100%;height:19px;text-align:center;font-size:18px;font-weight:bold;">
        <p>펫샵 공지사항 관리</p>
    </div>

    <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">
    <?php
        $notice = "";
        $update_time = "";
        $notice_sql = "select * from tb_admin_notice;";
        $notice_result = mysql_query($notice_sql);
        if ($notice_rows = mysql_fetch_object($notice_result)) {
            $notice = $notice_rows->notice;
            $update_time = $notice_rows->update_time;
        }
        ?>
    <form action="manage_notice_process.php" method="POST">
        <table width="100%">
            <tr>
                <td align="center">
                    <table class="container" style="width:100%;">
                        <tr>
                            <td>
                                <!--label for="uname"><b>Username</b></label-->
                                <textarea type="text" placeholder="공지사항입력" name="admin_notice_2_artist" id="admin_notice_2_artist" style="width:100%;height:400px;"><?= $notice ?></textarea>
                                <div style="height:7px;width:100%;"></div>

                                <!--label for="psw"><b>Password</b></label-->
                                <button class="go_login" type="submit" onclick="on_login();"> 저 장 </button>
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

<?php
    include "../include/bottom.php";
}
?>