<?php

include "../include/top.php";
include "../include/Crypto.class.php";

$user_id = 'sheepoi@naver.com';
$photo = "";
	
$rating = $_REQUEST["rating"];
//echo $rating."<br>";

$order_id = $_REQUEST["order_id"];
$artist_id = $_REQUEST["artist_id"];
$nickname = $_REQUEST["nickname"];

$memo = $_REQUEST["memo"];
//echo $memo;
$key = $_REQUEST["key"];
//echo $key;
if ($key == "delete") {
//echo "delete";
		$update_sql = "update tb_usage_reviews set is_delete = 1 where customer_id = '".$user_id."' and order_id = '".$order_id."';";
                //echo $update_sql;
                $update_result = mysql_query($update_sql);
                if (mysql_affected_rows() > 0) {
?>
                        <script language="javascript">
                                location.href="manage_my_postwrite.php";
                        </script>
<?php
                } else {
?>
                        <script language="javascript">
                                $.MessageBox({
                                        buttonDone      : "확인",
                                        message         : "저장을 하지 못했습니다. 문의 부탁드립니다."
                                }).done(function(){
                                        location.href="manage_my_postwrite.php";
                                });
                        </script>
<?php
                }

} else {
	$sql = "select * from tb_usage_reviews where customer_id = '".$user_id."' and order_id = '".$order_id."';";
	$result = mysql_query($sql);
	if ($result_datas = mysql_fetch_object($result)) {
		$update_sql = "update tb_usage_reviews set is_v = 1, photo = '".$photo."', nickname = '".$nickname."', review = '".$memo."', rating = '".$rating."', is_delete = 0, is_report = 0 where customer_id = '".$user_id."' and order_id = '".$order_id."';";
		//echo $update_sql;
		$update_result = mysql_query($update_sql);
		if (mysql_affected_rows() > 0) {
?>
                        <script language="javascript">
                                location.href="index.php";
                        </script>
<?php
                } else {
?>
                        <script language="javascript">
                                $.MessageBox({
                                        buttonDone      : "확인",
                                        message         : "저장을 하지 못했습니다. 글 내용을 확인해 주세요.<br>(특수문자 사용시 저장이 안될수 있습니다.)"
                                }).done(function(){
                                        location.href="index.php";
                                });
                        </script>
<?php
                }
        } else {
                $insert_sql = "insert into tb_usage_reviews (is_v, photo, nickname, customer_id, artist_id, order_id, rating, review, is_delete, update_time) values (1, '".$photo."', '".$nickname."', '".$user_id."', '".$artist_id."', '".$order_id."', '".$rating."', '".$memo."', 0, now());";
		//echo $insert_sql;
                $insert_result = mysql_query($insert_sql);
                if (mysql_affected_rows() > 0) {
?>
                        <script language="javascript">
                                location.href="index.php";
                        </script>
<?php
                } else {
?>
                        <script language="javascript">
                                $.MessageBox({
                                        buttonDone      : "확인",
                                        message         : "저장을 하지 못했습니다. 글 내용을 확인해 주세요.<br>(특수문자 사용시 저장이 안될수 있습니다.)"
                                }).done(function(){
                                        location.href="index.php";
                                });
                        </script>
<?php
                }
        }
}

include "../include/bottom.php";
?>
