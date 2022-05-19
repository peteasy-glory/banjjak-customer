<?php
ini_set('memory_limit', -1); 

include "../include/configure.php";
include "../include/db_connection.php";
include "../include/session.php";
include "../include/php_function.php";
include "../include/Crypto.class.php";

$user_id = $_SESSION['gobeauty_user_id'];
make_user_directory($upload_static_directory.$upload_directory, $user_id);

// 설정
$allowed_ext = array('jpg','jpeg','png','gif');
 
// 변수 정리
//$error = $_FILES['myfile']['error'];
//$name = $_FILES['myfile']['name'];

$filename = $_REQUEST['filepath'];
$filename = trim($filename);
echo "filename:".$filename."<br>";
$new_filename = $_REQUEST['newfilepath'];
$new_filename = trim($new_filename);
echo "newfile:".$newfilename."<br>";
$ext = array_pop(explode('.', $filename));

// 오류 확인
if( $error != UPLOAD_ERR_OK ) {
	switch( $error ) {
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
			echo "파일이 너무 큽니다. ($error)";
			break;
		case UPLOAD_ERR_NO_FILE:
			echo "파일이 첨부되지 않았습니다. ($error)";
			break;
		default:
			echo "파일이 제대로 업로드되지 않았습니다. ($error)";
	}
	exit;
}
 
// 확장자 확인
if( !in_array(strtolower($ext), $allowed_ext) ) {
	echo "허용되지 않는 확장자입니다.";
	exit;
}
 
$upload_direcoty_full_path = $upload_directory."/".$new_filename;
//move_uploaded_file( $_FILES['myfile']['tmp_name'], $upload_static_directory.$upload_direcoty_full_path);

$oldfile = $upload_static_directory."/pet/upload/appupload/".$filename;
$newfile = $upload_static_directory.$upload_direcoty_full_path;
$imgpath = $oldfile;
$target = $newfile;

			//모바일 세로 이미지 로테이션...
$mklotaion=false;
//			$tmpp_file=$_FILES["imgupfile"]["tmp_name"];
			$exifData = exif_read_data($imgpath); 
//print_r($exifData);
			if($exifData['Orientation']){
//echo "lotation";
					if($exifData['Orientation'] == 6) { 
						// 시계방향으로 90도 돌려줘야 정상인데 270도 돌려야 정상적으로 출력됨 
						$degree = 270; 
					} 
					else if($exifData['Orientation'] == 8) { 
						// 반시계방향으로 90도 돌려줘야 정상 
						$degree = 90; 
					} 
					else if($exifData['Orientation'] == 3) { 
						$degree = 180; 
					} 
//echo $exifData['Orientation'];
					if($degree) { 
						if($exifData[FileType] == 1) { 
							$source = imagecreatefromgif($imgpath); 
							$source = imagerotate ($source , $degree, 0); 
							imagegif($source, $target); 
							$mklotaion=true;
//	echo "-1".$degree;
						} 
						else if($exifData[FileType] == 2) { 
							$source = imagecreatefromjpeg($imgpath); 
							$source = imagerotate ($source , $degree, 0); 
						   imagejpeg($source, $target); 
 							$mklotaion=true;
//	echo "-2/".$degree."/".$source."/".$target;
						} 
						else if($exifData[FileType] == 3) { 
							$source = imagecreatefrompng($imgpath); 
							$source = imagerotate ($source , $degree, 0); 
							imagepng($source, $target); 
							$mklotaion=true;
//	echo "-3".$degree;
						} 
				} //if($degree)
					//로테이션 처리...를 못했으면 그냥 업로드 처리
					if($mklotaion==false){
						//move_uploaded_file($imgpath, $target);
						copy($imgpath, $target);
					}
			}else{  //if($exifData['Orientation'])
				//move_uploaded_file($imgpath, $target);
						copy($imgpath, $target);
//echo "그냥업로드";
			} //if($exifData['Orientation'])
//기존 앱에서 올린 파일 삭제..
@unlink($imgpath);

$crypto = new Crypto();
$user_id = $crypto->encode(trim($user_id), $access_key, $secret_key);

$sql = "select * from tb_request_artist where customer_id = '".$user_id."';";
$result = mysql_query($sql);
if ($result_datas = mysql_fetch_object($result)) {
        $asql = "update tb_request_artist set business_license = '".$upload_direcoty_full_path."' where customer_id = '".$user_id."';";
//      echo $asql;
        $aresult = mysql_query($asql);
} else {
        $insert_sql = "insert into tb_request_artist (customer_id, business_license, update_time) values ('".$user_id."', '".$upload_direcoty_full_path."', now());";
//      echo $insert_sql;
        $insert_result = mysql_query($insert_sql);
}




/* if(file_exists($oldfile)) { 
      if(!copy($oldfile, $newfile)) { 
            echo "파일 복사에 실패하였습니다."; 
      } else if(file_exists($newfile)) { 
            // 복사에 성공하면 원본 파일을 삭제합니다. 
            if(!@unlink($oldfile)){ 
                 if(@unlink($newfile)){ 
                      echo "파일이동에 실패하였습니다."; 
                 } 
            } 
      } 
 }*/ 

//move_uploaded_file( "/home/hosting_users/pickmongb/www/upload/appupload/".$filename, $upload_static_directory.$upload_direcoty_full_path);
//echo "11 /home/hosting_users/pickmongb/www/upload/appupload/".$filename."<br>";
//echo "22 ".$upload_static_directory.$upload_direcoty_full_path."<br>";
//$crypto = new Crypto();
//$user_id = $crypto->encode(trim($user_id), $access_key, $secret_key);

/*$sql = "select * from tb_usage_reviews where customer_id = '".$user_id."' and order_id = '".$order_id."';";
$result = mysql_query($sql);
if ($result_datas = mysql_fetch_object($result)) {
	$asql = "update tb_usage_reviews set review = '".$review."' where customer_id = '".$user_id."';";
//	echo $asql;
	$aresult = mysql_query($asql);
} else {
	$insert_sql = "insert into tb_request_artist (customer_id, business_license, update_time) values ('".$user_id."', '".$upload_direcoty_full_path."', now());";
//	echo $insert_sql;
        $insert_result = mysql_query($insert_sql);
}*/

// 파일 정보 출력
/*echo "<h2>파일 정보</h2>
<ul>
	<li>파일명: $name</li>
	<li>확장자: $ext</li>
	<li>파일형식: {$_FILES['myfile']['type']}</li>
	<li>파일크기: {$_FILES['myfile']['size']} 바이트</li>
</ul>";
*/
//header('Content-type: application/json');
?>

{"upfilename": "<?=$upload_direcoty_full_path?>","allpath": "<?=$upload_static_directory.$upload_direcoty_full_path?>","msg": "","imagewidth": "300"} 
