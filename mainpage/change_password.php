<?php
include "../include/top.php";

        $user_id = $_SESSION['gobeauty_user_id'];
        $user_name = $_SESSION['gobeauty_user_nickname'];

$sql = "update tb_customer set password = '".make_password_hash($_REQUEST['gobeauty_pwd'])."' where id = '".$user_id."';";
$result = mysql_query($sql);
?>
        <script>
                $.MessageBox({
                    buttonDone      : "확인",
                    message         : "변경되었습니다."
                }).done(function(){
                        location.href = 'manage_setting.php';
                });
        </script>
<?
include "../include/bottom.php";
?>
