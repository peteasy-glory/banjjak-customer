<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$data = array();
$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
$mode = ($_POST["mode"] && $_POST["mode"] != "")? $_POST["mode"] : "";

if($mode){
    if($mode == "get_new_shop_type"){
        $sql = "
            SELECT * FROM tb_product_dog_static
            WHERE customer_id IN (
                SELECT customer_id FROM tb_shop 
                WHERE is_mainshop_new = '1' 
            ) ORDER BY customer_id
        ";
        $array = sql_fetch_array($sql);
        foreach($array as $rs){

        }


    }else{

    }


}else{
    $return_data = array("code" => "000009", "data" => "중요 데이터 누락");
}
echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>