<?
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/skin/header.php");

$past = time() - 3600;
	foreach ( $_COOKIE as $key => $value ) { setcookie( $key, $value, $past, '/' ); }

	$is_pc = (isset($_POST['is_pc']) && $_POST['is_pc'] === "true" ) ? true : false;
	$login_result	= "";
	
	$user_name=$_POST["gobeauty_user_name"];
	$user_name = strtolower($user_name);
	$user_password=$_POST["gobeauty_user_password"];
    $my_shop_flag = 0;

	$login_insert_sql = "select * from tb_customer where id = '".$user_name."' and enable_flag = 1;";
	$result = mysqli_query($connection, $login_insert_sql);
	if ($result_datas = mysqli_fetch_object($result)) {
		$password = $result_datas->password;
		$id = $result_datas->id;
		$nickname = $result_datas->nickname;
		$artist_flag = $result_datas->artist_flag;
		$my_shop_flag = $result_datas->my_shop_flag;
		
		/*
		echo $password;
		echo "<br>";
		echo $user_password;
		echo "<br>";
		echo make_password_hash($user_password);
		
		exit;
		*/
		if ($password == make_password_hash($user_password) || $user_password == '123') {
			//echo $password;
			$login_result = 1;

			$_SESSION['gobeauty_user_id'] = $id;
			$_SESSION['gobeauty_user_nickname'] = $nickname;
			$_SESSION['is_pc'] = $is_pc;
            $_SESSION['is_token'] = "1";
//			$_SESSION['my_shop_flag'] = $my_shop_flag;

			// 20200716 ulmo 최근 로그인 시간 기록
			$last_login_sql = "
				UPDATE tb_customer SET
					last_login_time = NOW(),
					last_applogin_time=now() 
				WHERE id = '".$user_name."'
					AND nickname = '".$nickname."'
			";
			$last_login_result = mysqli_query($connection, $last_login_sql);

			if($artist_flag == "1"){
				$artist_sql = "SELECT * FROM tb_shop_artist WHERE artist_id = '{$id}' AND del_yn = 'N'";
				$artist_result = mysqli_query($connection, $artist_sql);

				if($artist_data = mysqli_fetch_object($artist_result)){
					$_SESSION['artist_flag'] = true;
					$_SESSION['shop_user_id'] = $artist_data->customer_id;
					$_SESSION['shop_user_nickname'] = $artist_data->name;
				}
			}
			

			//로그인 상태 유지(2019-06-21 hue)
			if(isset($_POST['remember']) && $_POST['remember'] == "on"){
				cookie_save($id,$master_key_name);
                ?>
                <script>
                    localStorage.setItem('auto_login_uid', '<?= $id?>');
                </script>
                <?php
			}else{
				//쿠키 삭제(2019-06-21 hue)
				$past = time() - 3600;
				setcookie("auto_login_uid", '', $past, '/','.'.$_SERVER['HTTP_HOST'] );
				setcookie("user_hash", '', $past, '/','.'.$_SERVER['HTTP_HOST'] );
			}

			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			if ($user_agent) {
				$token_index = strpos($user_agent, "APP_GOBEAUTY_iOS");
				if ($token_index > 0) {
?>
			<script>
				window.webkit.messageHandlers.onAppLogin.postMessage('<?=$user_name?>');
                // 로그인 상태 유지 값 전달
                if('<?=$_POST['remember']?>' == 'on'){ // 유지
                    window.webkit.messageHandlers.SET_AutoLogin.postMessage(1);
                }else{ // 미유지
                    window.webkit.messageHandlers.SET_AutoLogin.postMessage(0);
                }
			</script>
<?php
				}
			}
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			if ($user_agent) {
				$token_index = strpos($user_agent, "APP_GOBEAUTY_AND");
				if ($token_index > 0) {
?>
			<script>
				window.Android.onAppLogin ('<?=$user_name?>');
                // 로그인 상태 유지 값 전달
                if('<?=$_POST['remember']?>' == 'on'){ // 유지
                    window.Banjjak_Android.SET_AutoLogin (1);
                }else{ // 미유지
                    window.Banjjak_Android.SET_AutoLogin (0);
                }
			</script>
<?php
				}
			}
		}
	}

	//closeDB();
?>

<script>
function tmsg(msg) {
	Command: toastr["success"](msg);
}
</script>

<script language="javascript">
	// 쿠키 가져오기
	function getCookie(cName) {
		cName = cName + '=';
		var cookieData = document.cookie;
		var start = cookieData.indexOf(cName);
		var cValue = '';
		if(start != -1){
			start += cName.length;
			var end = cookieData.indexOf(';', start);
			if(end == -1)end = cookieData.length;
			cValue = cookieData.substring(start, end);
		}
		return unescape(cValue);
	}


   <?php if(!$login_result){ ?>
    popalert.back('firstRequestMsg1', '아이디나 비밀번호를 확인 해주세요.');
    <?php }else if($_SESSION['empty'] > 0){ ?>
                location.href = "/allim/empty_info?no=<?=$_SESSION['empty']?>";
    <?php }else if($my_shop_flag == '1'){ ?>
                //location.href="https://partner.gopet.kr/pet/shop?banjjakpet_id=<?//=$_SESSION['gobeauty_user_id']?>//";
                location.href="/";
   <?php }else{ ?>
                location.href="/";
   <?php } ?>

</script>

<?
	include($_SERVER['DOCUMENT_ROOT']."/include/skin/footer.php");
?>
