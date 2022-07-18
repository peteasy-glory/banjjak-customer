<?php
	include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

	$user_id = $_SESSION['gobeauty_user_id'];

	//유효성
	if(empty($user_id)){
		//실패
		$arr = array(
			"ret" => false,
			"msg" => "실패",
		);
		echo(json_encode($arr));
		exit();
	}

	$select_sql = "select * from tb_customer where id = '{$user_id}'";
	$select_result = mysqli_query($connection, $select_sql);
	$row = mysqli_fetch_object($select_result);

	if($row->is_regist_by_naver == '2'){
		$client_id = "UJ2SBwYTjhQSTvsZF8TO";
		$client_secret = "3gFya4za76";
		$code = $_GET["code"];;
		$state = $_GET["state"];;
		$redirectURI = urlencode("https://customer.banjjakpet.com/login/naver.php");
		$url = "https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id=".$client_id."&client_secret=".$client_secret."&redirect_uri=".$redirectURI."&state=RAMDOM_STATE";
		$is_post = false;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, $is_post);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$headers = array();
		$response = curl_exec ($ch);

		$json_response = json_decode($response);

		$url = "https://nid.naver.com/oauth2.0/token?grant_type=delete&client_id=".$client_id."&client_secret=".$client_secret."&access_token=".$json_response->access_token."&service_provider=NAVER";
		$is_post = false;
		$chd = curl_init();
		curl_setopt($chd, CURLOPT_URL, $url);
		curl_setopt($chd, CURLOPT_POST, $is_post);
		curl_setopt($chd, CURLOPT_RETURNTRANSFER, true);
		$headers = array();
		$response = curl_exec ($chd);
		$status_coded = curl_getinfo($chd, CURLINFO_HTTP_CODE);
		curl_close ($chd);
	}

	//회원정보
	$sql = "UPDATE tb_customer
	        SET enable_flag = 0,
            delete_time = NOW()
		    WHERE id = '{$user_id}'";
	$result = mysqli_query($connection, $sql);

	//[미구현]주문이력

	//[미구현]단골

	//[미구현]쿠폰

	if($result){
		//성공
		$arr = array(
			"ret" => true,
			"msg" => "성공",
		);
		echo(json_encode($arr));
		exit();
	}else{
		//실패
		$arr = array(
			"ret" => false,
			"msg" => "실패",
		);
		echo(json_encode($arr));
		exit();
	}
?>
