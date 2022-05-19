<?php include "../include/top.php"; ?>

<?php
	$user_name=trim($_POST["gobeauty_user_name"]);
	
if ($user_name == "pickmon@pickmon.com") {
?>
<script language="javascript">
        $.MessageBox({
                buttonDone      : "확인",
                message         : "관리자로는 전환이 불가능 합니다."
        }).done(function(){
                location.href="<?=$login_directory?>/index.php";
        });
</script>
<?php
	return;
} else {

	$login_insert_sql = "select * from tb_customer where id = '".$user_name."' and enable_flag = 1;";
	$result = mysql_query($login_insert_sql);

	while ($result_datas = mysql_fetch_object($result)) {
		$nickname = $result_datas->nickname;
		$artist_flag = $result_datas->artist_flag;
		
		$login_result = 1;
		session_start();
		$_SESSION['gobeauty_user_id'] = $user_name;
		$_SESSION['gobeauty_user_nickname'] = $nickname;
		if($artist_flag == "1"){
			$artist_sql = "SELECT * FROM tb_shop_artist WHERE artist_id = '{$user_name}' AND del_yn = 'N'";
			$artist_result = mysql_query($artist_sql);

			if($artist_data = mysql_fetch_object($artist_result)){
				$_SESSION['artist_flag'] = true;
				$_SESSION['shop_user_id'] = $artist_data->customer_id;
				$_SESSION['shop_user_nickname'] = $artist_data->name;
			}
		}
	}

	//쿠키 전체 삭제(2019-06-21 hue)
	$past = time() - 3600;
	foreach ($_COOKIE as $key => $value)
	{
		setcookie($key, '', $past, '/','.'.$_SERVER['HTTP_HOST'] );
	}
}
	closeDB();
?>

<script>
function tmsg(msg) {
	Command: toastr["success"](msg);
}
</script>

<script language="javascript">
   <?if(!$login_result){?>
	$.MessageBox({
		buttonDone      : "확인",
		message         : "없는 아이디입니다."
	}).done(function(){
		location.href="<?=$login_directory?>/index.php";
	});
   <?}else{?>
		location.href="<?=$mainpage_directory?>/index.php";
   <?}?>
</script>

<?php include "../include/bottom.php"; ?>
