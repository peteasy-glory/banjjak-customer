<?php include "../include/top.php"; ?>

        <div style="position:absolute;z-index:5;top:5px;left:10px;"><a href="<?=$admin_directory?>/"><img src="<?=$image_directory?>/back.png" width="35px"></a></div>
        <div style="width:100%;height:19px;text-align:center;font-size:18px;font-weight:bold;"><p>푸시 결과</p></div>

        <hr style="color:#999999;width:100%;border:0;border:1px dashed #999999;">

<?php
	// 발송 
	$push_title=$_REQUEST["push_title"];
	$push_message=$_REQUEST["push_message"];
	$push_url=$_REQUEST["push_url"];
	$push_image=$_REQUEST["push_image"];

// 입력값 확인
if($push_title != "" and $push_message != "" and $push_url != "" and $push_image != "") {

	// 대상자 - 내부테스트/peteasy 임직원
	if($_REQUEST['sendto'] == "peteasy") {
		//
		$_tot_cnt = "7";
		$arr_userapikey=array();
		$sql = "SELECT id, token
					FROM gobeautypet.tb_customer
					where id in (
							'itseokbeom@gmail.com',
							'kungem@hotmail.com',
							'ttme93@gmail.com',
							'pickmon@pickmon.com',
							'saychanjin@naver.com',
							'sally@peteasy.kr',
							'chansworld@pickmon.com',
							'sunny@peteasy.kr',
							'power0617@naver.com'
						) and (token is not null and token !='') and enable_flag =1 and push_option =1 and token!='-'
					";
					/*
					
					*/
		$result = mysql_query($sql);
		while($rs = mysql_fetch_object($result)) {
			if ($rs->token != null && $rs->token != "") {
						array_push($arr_userapikey,$rs->token);
			}
		}
		
		// 
		app_push($arr_userapikey, $push_title, $push_message, $push_url, $push_image);
	
	// 대상자 - 전체 회원 (발송시 실시간 확인, 1000명씩 발송)
	} else if($_REQUEST['sendto'] == "alluser") {
		// 20210614 - 전체발송용 (전체 회원 대상)
		//$rt = all_push($push_title,$push_message,$push_url,$push_image);
		//$rt = all_push ($tokens, "푸시테스트타이틀", "푸시테스트메모입니다. 문장이 들어갑니다. 끝내줘요.", "http://gobeauty.kr/mainpage/?sequence=4", "http://gobeauty.kr/images/logo_login.jpg");

		// https://firebase.google.com/docs/cloud-messaging/http-server-ref
		// registration_ids => 최대 1000개, 1000개 단위로 쪼개서 발송 필요
		
		// 전체 수
		$_sqry = "select * from tb_customer	where (token is not null and token !='') and enable_flag =1 and push_option =1 and token!='-'";
		$result = mysql_query($_sqry);
		$_tot_cnt = mysql_num_rows($result);
		//echo $_tot_cnt ."<br><br>";

		// 999개씩 발송
		for($_i=1; $_i<=round($_tot_cnt / 999) + 1; $_i++) {
			$_tmp_limit_start = ($_i-1)*1000;
			$_tmp_limit_end = ($_i-1)*1000 + 999;
			//
			$arr_userapikey = array(); // 999개 token 담기
			$_sqry1 = "select * from tb_customer	where (token is not null and token !='') and enable_flag =1 and push_option =1 and token!='-' limit {$_tmp_limit_start}, 999";
			$_rslt1 = mysql_query($_sqry1);
			while($rs = mysql_fetch_object($_rslt1)) {
				if ($rs->{'token'} != null && $rs->{'token'} != "" && $rs->{'token'} != "-") {
					array_push($arr_userapikey,$rs->{'token'});
				}
			}

			// debug
			// echo $_i . "-" . $_tmp_limit_start . "-" . $_tmp_limit_end . "<br>";
			 echo $_sqry1 . "<br>";
			// print_r($arr_userapikey);
			
			// 999개씩 발송
			app_push($arr_userapikey, $push_title, $push_message, $push_url, $push_image);		
		}

	// 대상자 - 펫샵 회원 (발송시 실시간 확인, 1000명씩 발송)
	} else if($_REQUEST['sendto'] == "petshop") {

		// 전체 수
		$_sqry = "select * from tb_customer where enable_flag =1 and (my_hotel_flag=1 or my_playroom_flag=1 or my_shop_flag=1) and (token is not null and token !='') and push_option =1 and token!='-'";
		$result = mysql_query($_sqry);
		$_tot_cnt = mysql_num_rows($result);
		//echo $_tot_cnt ."<br><br>";

		// 999개씩 발송
		for($_i=1; $_i<=round($_tot_cnt / 999) + 1; $_i++) {
			$_tmp_limit_start = ($_i-1)*1000;
			$_tmp_limit_end = ($_i-1)*1000 + 999;
			//
			$arr_userapikey = array(); // 999개 token 담기
			$_sqry1 = "select * from tb_customer where enable_flag =1 and (my_hotel_flag=1 or my_playroom_flag=1 or my_shop_flag=1) and (token is not null and token !='') and push_option =1 and token!='-' limit {$_tmp_limit_start}, 999";
			$_rslt1 = mysql_query($_sqry1);
			while($rs = mysql_fetch_object($_rslt1)) {
				if ($rs->{'token'} != null && $rs->{'token'} != "" && $rs->{'token'} != "-") {
					array_push($arr_userapikey,$rs->{'token'});
				}
			}

			// debug
			//echo $_i . "-" . $_tmp_limit_start . "-" . $_tmp_limit_end . "<br>";
			echo $_sqry1 . "<br>";
			//print_r($arr_userapikey);
			
			// 999개씩 발송
			app_push($arr_userapikey, $push_title, $push_message, $push_url, $push_image);
		}

	// 대상자 - 견주 회원 (발송시 실시간 확인, 1000명씩 발송)
	} else if($_REQUEST['sendto'] == "notpetshop") {

		// 전체 수
		$_sqry = "select * from tb_customer where enable_flag =1 and my_hotel_flag!=1 and my_playroom_flag!=1 and my_shop_flag!=1 and (token is not null and token !='') and push_option =1 and token!='-'";
		$result = mysql_query($_sqry);
		$_tot_cnt = mysql_num_rows($result);
		//echo $_tot_cnt ."<br><br>";

		// 999개씩 발송
		for($_i=1; $_i<=round($_tot_cnt / 999) + 1; $_i++) {
			$_tmp_limit_start = ($_i-1)*1000;
			$_tmp_limit_end = ($_i-1)*1000 + 999;
			//
			$arr_userapikey = array(); // 999개 token 담기
			$_sqry1 = "select * from tb_customer where enable_flag =1 and my_hotel_flag!=1 and my_playroom_flag!=1 and my_shop_flag!=1 and (token is not null and token !='') and push_option =1 and token!='-' limit {$_tmp_limit_start}, 999";
			$_rslt1 = mysql_query($_sqry1);
			while($rs = mysql_fetch_object($_rslt1)) {
				if ($rs->{'token'} != null && $rs->{'token'} != "" && $rs->{'token'} != "-") {
					array_push($arr_userapikey,$rs->{'token'});
				}
			}

			// debug
			//echo $_i . "-" . $_tmp_limit_start . "-" . $_tmp_limit_end . "<br>";
			echo $_sqry1 . "<br>";
			//print_r($arr_userapikey);
			
			// 999개씩 발송
			app_push($arr_userapikey, $push_title, $push_message, $push_url, $push_image);
		}

	}

}//if

 
	echo "<font style='font-size:13px;'>".$rt."</font>";

	closeDB();
?>
<br>
<br>
<br>
<br>
<?=$_tot_cnt?> 건 발송 성공!
<br>

<?php include "../include/bottom.php"; ?>

<script language="javascript">
//	location.href="<?=$mainpage_directory?>/index.php";
</script>

