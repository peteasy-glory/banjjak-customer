<?php
	include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

	$artist_id = $_REQUEST['artist_id'];
	$user_id = $_SESSION['gobeauty_user_id'];
	$is_on = (int)$_REQUEST['is_on'];
	$id = $_REQUEST['id'];

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

	//
	if($is_on == 1){
		//좋아요 추가
		$sql = "insert into tb_like_artist(customer_id, artist_id, update_time)
			values('{$user_id}', '{$artist_id}', NOW())";
		$result = mysqli_query($connection, $sql);
		
		
	}else{
		//좋아요 삭제
		$sql = "delete from tb_like_artist
			where customer_id = '{$user_id}'
			and artist_id = '{$artist_id}'";
		$result = mysqli_query($connection, $sql);
	}

	if($result){
		//성공
		$arr = array(
			"ret" => true,
			"msg" => "성공",
			"is_on" => $is_on,
			"id" => $id,
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
