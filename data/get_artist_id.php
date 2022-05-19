<?php

function get_artist_id ($artist_id) {
    global $connection;    
	$shop_sql = "select name from tb_shop where customer_id = '".$artist_id."';";
	$shop_result = mysqli_query($connection, $shop_sql);
	if(isset($shop_result)){
        if ($shop_datas = mysqli_fetch_object($shop_result)) {
            return $shop_datas->name;
        }
    }
    // error_log('----- $shop_sql : '.$shop_sql);
	return null;
}
?>
