<?php
include "../include/top.php";

        $user_id = $_SESSION['gobeauty_user_id'];
        $user_name = $_SESSION['gobeauty_user_nickname'];

	$sql = "update tb_customer set enable_flag = 0, delete_time = now() where id = '".$user_id."';";
	$result = mysql_query($sql);

    session_destroy();

	//쿠키 전체 삭제(2019-06-21 hue)
	$past = time() - 3600;
	foreach ($_COOKIE as $key => $value)
	{
		setcookie($key, '', $past, '/','.'.$_SERVER['HTTP_HOST'] );
	}
?>
        <script>
                $.MessageBox({
                    buttonDone      : "확인",
                    message         : "탈퇴처리되었습니다."
                }).done(function(){
                        location.href = '../login/logout_process.php';
                });
        </script>
<?
include "../include/bottom.php";
?>
