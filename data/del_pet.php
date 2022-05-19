<?php
	include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

	$pet_seq = $_REQUEST['pet_seq'];

	//유효성
	if(empty($pet_seq)){
		//실패
		$arr = array(
			"ret" => false,
			"msg" => "잘못된 접근입니다.",
		);
		echo(json_encode($arr));
		exit();
	}

	//펫 정보 삭제
	$sql = "delete from tb_mypet
		where pet_seq = {$pet_seq}";
	$result = mysqli_query($connection, $sql);

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